<?php
// contabilidad_auto.php

/** Garantiza que exista la cuenta y devuelve su ID */
function ensureCuentaId(mysqli $conn, string $nombre, array $defaults = []): int {
  $stmt = $conn->prepare("SELECT id FROM cuentas_contables WHERE nombre = ? LIMIT 1");
  $stmt->bind_param("s", $nombre);
  $stmt->execute();
  $stmt->bind_result($id);
  if ($stmt->fetch()) { $stmt->close(); return (int)$id; }
  $stmt->close();

  $nominal_egreso   = $defaults['nominal_egreso']   ?? 0;
  $nominal_ingreso  = $defaults['nominal_ingreso']  ?? 0;
  $balance_deudor   = $defaults['balance_deudor']   ?? 0;
  $balance_acreedor = $defaults['balance_acreedor'] ?? 0;
  $clasificacion    = $defaults['clasificacion']    ?? 'Otros';

  $stmt = $conn->prepare("
    INSERT INTO cuentas_contables (nombre, nominal_egreso, nominal_ingreso, balance_deudor, balance_acreedor, clasificacion, created_at)
    VALUES (?, ?, ?, ?, ?, ?, NOW())
  ");
  $stmt->bind_param("siiiis", $nombre, $nominal_egreso, $nominal_ingreso, $balance_deudor, $balance_acreedor, $clasificacion);
  if (!$stmt->execute()) {
    $err = $conn->error; $stmt->close();
    throw new Exception("No se pudo crear la cuenta '$nombre': $err");
  }
  $newId = $stmt->insert_id;
  $stmt->close();
  return (int)$newId;
}

/** ==================== COMPRAS (desde compras_internas) ==================== */
function generarPartidaCompraDesdeInterna(
  mysqli $conn,
  int $compraId,
  ?string $descripcion = null,
  ?string $externalUid = null
): int {

  // 0) Si ya existe partida para esta compra, devuelve el id (idempotencia)
  $stmt = $conn->prepare("SELECT id FROM partidas_contables_compras WHERE compra_id = ? LIMIT 1");
  $stmt->bind_param("i", $compraId);
  $stmt->execute();
  $stmt->bind_result($exist);
  if ($stmt->fetch()) { $stmt->close(); return (int)$exist; }
  $stmt->close();

  // 1) Traer compra interna
  $stmt = $conn->prepare("
    SELECT id, forma_pago, periodo_pago, nombre_producto, numero_cuenta_contable,
           valor_iva, valor_sin_iva, total_producto_sin_iva, total_iva,
           total_sin_iva_general, total_general, fecha_registro
    FROM compras_internas
    WHERE id = ?
  ");
  $stmt->bind_param("i", $compraId);
  $stmt->execute();
  $compra = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  if (!$compra) throw new Exception("Compra interna #$compraId no existe.");

  // 2) Montos (tu tabla ya los tiene)
  $base   = round((float)$compra['total_sin_iva_general'], 2);
  $iva    = round((float)$compra['total_iva'], 2);
  $total  = round((float)$compra['total_general'], 2);

  // 3) Cuenta de gasto/activo segun numero_cuenta_contable (si es válido) o fallback "Compras"
  $gastoCuentaId = null;
  $numCta = (int)$compra['numero_cuenta_contable'];
  if ($numCta > 0) {
    $chk = $conn->prepare("SELECT id FROM cuentas_contables WHERE id = ? LIMIT 1");
    $chk->bind_param("i", $numCta);
    $chk->execute();
    $chk->bind_result($tmpId);
    if ($chk->fetch()) $gastoCuentaId = (int)$tmpId;
    $chk->close();
  }
  if (!$gastoCuentaId) {
    // Si no envían id válido, usamos "Compras" como gasto
    $gastoCuentaId = ensureCuentaId($conn, 'Compras', ['balance_deudor'=>1, 'clasificacion'=>'Gastos de Operación']);
  }

  // 4) Cuentas IVA y medios de pago / pasivos
  $ctaIVACobrar = ensureCuentaId($conn, 'IVA por Cobrar', ['balance_deudor'=>1, 'clasificacion'=>'Activo Corriente']);
  $ctaCaja      = ensureCuentaId($conn, 'Caja',   ['balance_deudor'=>1, 'clasificacion'=>'Activo Corriente']);
  $ctaBancos    = ensureCuentaId($conn, 'Bancos', ['balance_deudor'=>1, 'clasificacion'=>'Activo Corriente']);
  $ctaProv      = ensureCuentaId($conn, 'Proveedores o Acreedores Comerciales', ['balance_acreedor'=>1, 'clasificacion'=>'Pasivo Corriente']);
  $ctaDocs      = ensureCuentaId($conn, 'Documentos por Pagar Comerciales CP',   ['balance_acreedor'=>1, 'clasificacion'=>'Pasivo Corriente']);

  // 5) Selección de HABER según forma de pago
  $forma = trim($compra['forma_pago']);
  // Por las reglas: Efectivo -> Caja; (si tuvieras Transferencia -> Bancos)
  // Si es a crédito -> Proveedores; Crédito con documentos -> Documentos por pagar
  if (strcasecmp($forma, 'Efectivo') === 0) {
    $haberCuentaId = $ctaCaja;
  } elseif (stripos($forma, 'Crédito con documentos') !== false) {
    $haberCuentaId = $ctaDocs;
  } elseif (stripos($forma, 'Crédito') !== false) {
    $haberCuentaId = $ctaProv;
  } else {
    // fallback bancos si llegara otra etiqueta
    $haberCuentaId = $ctaBancos;
  }

  // 6) Descripción
  $desc = $descripcion;
  if ($desc === null || $desc === '') {
    $desc = 'Partida automática de compra';
  }
  $desc .= ' | CID='.$compraId;
  if ($externalUid) $desc .= ' | uid='.substr($externalUid,0,10);

  // 7) Insert partida y detalles
  $conn->begin_transaction();
  try {
    $stmt = $conn->prepare("INSERT INTO partidas_contables_compras (compra_id, descripcion, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $compraId, $desc);
    if (!$stmt->execute()) throw new Exception('No se pudo crear la partida (compras): '.$conn->error);
    $partidaId = $stmt->insert_id;
    $stmt->close();

    $ins = $conn->prepare("INSERT INTO partidas_contables_compras_detalle (partida_id, cuenta_id, debe, haber) VALUES (?, ?, ?, ?)");

    // DEBE: Gasto/Activo (base)
    if ($base > 0) {
      $debe = $base; $haber = 0.00;
      $ins->bind_param("iidd", $partidaId, $gastoCuentaId, $debe, $haber);
      if (!$ins->execute()) throw new Exception('Detalle base (debe): '.$conn->error);
    }

    // DEBE: IVA por Cobrar
    if ($iva > 0) {
      $debe = $iva; $haber = 0.00;
      $ins->bind_param("iidd", $partidaId, $ctaIVACobrar, $debe, $haber);
      if (!$ins->execute()) throw new Exception('Detalle IVA (debe): '.$conn->error);
    }

    // HABER: Medio de pago / Pasivo por total
    if ($total > 0) {
      $debe = 0.00; $haber = $total;
      $ins->bind_param("iidd", $partidaId, $haberCuentaId, $debe, $haber);
      if (!$ins->execute()) throw new Exception('Detalle pago (haber): '.$conn->error);
    }

    $ins->close();
    $conn->commit();
    return (int)$partidaId;
  } catch (Throwable $e) {
    $conn->rollback();
    throw $e;
  }
}

/** ==================== VENTAS ==================== */
function generarPartidaVenta(
  mysqli $conn,
  int $clienteId,
  string $formaCobro,   // 'Efectivo' | 'Transferencia'...
  float $base,
  float $iva,
  float $totalConIva,
  string $externalUid,
  string $descripcion = '',
  ?int $ventaId = null
): int {
  if (!empty($ventaId)) {
    $like = "%VID=".$ventaId."%";
    $stmt = $conn->prepare("SELECT id FROM partidas_contables_ventas WHERE descripcion LIKE ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $stmt->bind_result($existId);
    if ($stmt->fetch()) { $stmt->close(); return (int)$existId; }
    $stmt->close();
  }

  $ctaCaja     = ensureCuentaId($conn, 'Caja',     ['balance_deudor'=>1, 'clasificacion'=>'Activo Corriente']);
  $ctaBancos   = ensureCuentaId($conn, 'Bancos',   ['balance_deudor'=>1, 'clasificacion'=>'Activo Corriente']);
  $ctaVentas   = ensureCuentaId($conn, 'Ventas',   ['nominal_ingreso'=>1, 'balance_acreedor'=>1, 'clasificacion'=>'Ingresos']);
  $ctaIvaPagar = ensureCuentaId($conn, 'IVA por Pagar', ['balance_acreedor'=>1, 'clasificacion'=>'Pasivo Corriente']);

  $ctaCobro = (stripos($formaCobro, 'efectivo') !== false) ? $ctaCaja : $ctaBancos;

  $desc = trim($descripcion) !== '' ? $descripcion : 'Partida automática de venta';
  if (!empty($ventaId)) $desc .= ' | VID='.$ventaId;
  $desc .= ' | FC=' . strtoupper($formaCobro);
  $desc .= ' | base=' . number_format($base,2) . ' | iva=' . number_format($iva,2);
  $desc .= ' | uid=' . substr($externalUid,0,10);

  $conn->begin_transaction();
  try {
    $stmt = $conn->prepare("INSERT INTO partidas_contables_ventas (cliente_id, descripcion, fecha) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $clienteId, $desc);
    if (!$stmt->execute()) throw new Exception('No se pudo crear la partida (ventas): '.$conn->error);
    $partidaId = $stmt->insert_id;
    $stmt->close();

    $ins = $conn->prepare("INSERT INTO partida_detalle_ventas (partida_id, cuenta_id, debe, haber) VALUES (?, ?, ?, ?)");

    // DEBE: Cobro total
    $debe = round($totalConIva, 2); $haber = 0.00;
    $ins->bind_param("iidd", $partidaId, $ctaCobro, $debe, $haber);
    if (!$ins->execute()) throw new Exception('Detalle cobro: '.$conn->error);

    // HABER: Ventas (base)
    $debe = 0.00; $haber = round($base, 2);
    $ins->bind_param("iidd", $partidaId, $ctaVentas, $debe, $haber);
    if (!$ins->execute()) throw new Exception('Detalle ventas: '.$conn->error);

    // HABER: IVA por pagar
    $debe = 0.00; $haber = round($iva, 2);
    $ins->bind_param("iidd", $partidaId, $ctaIvaPagar, $debe, $haber);
    if (!$ins->execute()) throw new Exception('Detalle IVA: '.$conn->error);

    $ins->close();
    $conn->commit();
    return (int)$partidaId;
  } catch (Throwable $e) {
    $conn->rollback();
    throw $e;
  }
}

/** ==================== PLANILLA ==================== */
function generarPartidaPlanilla(
  mysqli $conn,
  int $planillaId,
  string $medioPago,    // 'Bancos' | 'Caja'
  float $sueldos,
  float $bonificaciones,
  float $igssLaboral,
  float $cuotaPatronal,
  float $isrSueldo,
  float $bancosCaja,
  string $externalUid,
  string $descripcion = ''
): int {

  // Idempotencia
  $like = "%PID=".$planillaId."%";
  $stmt = $conn->prepare("SELECT id FROM partidas_contables_planilla WHERE descripcion LIKE ? ORDER BY id DESC LIMIT 1");
  $stmt->bind_param("s", $like);
  $stmt->execute();
  $stmt->bind_result($existId);
  if ($stmt->fetch()) { $stmt->close(); return (int)$existId; }
  $stmt->close();

  // Asegurar cuentas
  $ctaSueldos       = ensureCuentaId($conn, 'Sueldos', ['balance_deudor'=>1, 'clasificacion'=>'Gastos de Operación']);
  $ctaBonificaciones= ensureCuentaId($conn, 'Bonificaciones', ['balance_deudor'=>1, 'clasificacion'=>'Gastos de Operación']);
  $ctaCuotaPatGasto = ensureCuentaId($conn, 'Cuota Patronal Sueldos', ['balance_deudor'=>1, 'clasificacion'=>'Gastos de Operación']);

  // Pasivos
  $ctaIGSSLaboral   = ensureCuentaId($conn, 'Cuota Laboral IGSS por Pagar', ['balance_acreedor'=>1, 'clasificacion'=>'Pasivo Corriente']);
  $ctaPatronalesXP  = ensureCuentaId($conn, 'Cuotas Patronales por Pagar',   ['balance_acreedor'=>1, 'clasificacion'=>'Pasivo Corriente']);
  $ctaISRXP         = ensureCuentaId($conn, 'ISR por Pagar sobre Sueldos',   ['balance_acreedor'=>1, 'clasificacion'=>'Pasivo Corriente']);

  // Pago
  $ctaBancos        = ensureCuentaId($conn, 'Bancos', ['balance_deudor'=>1, 'clasificacion'=>'Activo Corriente']);
  $ctaCaja          = ensureCuentaId($conn, 'Caja',   ['balance_deudor'=>1, 'clasificacion'=>'Activo Corriente']);
  $ctaPago          = (stripos($medioPago, 'caja') !== false) ? $ctaCaja : $ctaBancos;

  $desc = trim($descripcion) !== '' ? $descripcion : 'Partida automática de planilla';
  $desc .= ' | PID='.$planillaId;
  $desc .= ' | MP=' . strtoupper($medioPago);
  $desc .= ' | uid=' . substr($externalUid,0,10);

  $conn->begin_transaction();
  try {
    $stmt = $conn->prepare("INSERT INTO partidas_contables_planilla (planilla_id, descripcion, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $planillaId, $desc);
    if (!$stmt->execute()) throw new Exception('No se pudo crear la partida (planilla): '.$conn->error);
    $partidaId = $stmt->insert_id;
    $stmt->close();

    $ins = $conn->prepare("INSERT INTO partida_detalle_planilla (partida_id, cuenta_id, debe, haber) VALUES (?, ?, ?, ?)");

    // DEBE: Sueldos
    if ($sueldos > 0) {
      $debe = round($sueldos,2); $haber = 0.00;
      $ins->bind_param("iidd", $partidaId, $ctaSueldos, $debe, $haber);
      if (!$ins->execute()) throw new Exception('Detalle sueldos: '.$conn->error);
    }

    // DEBE: Bonificaciones
    if ($bonificaciones > 0) {
      $debe = round($bonificaciones,2); $haber = 0.00;
      $ins->bind_param("iidd", $partidaId, $ctaBonificaciones, $debe, $haber);
      if (!$ins->execute()) throw new Exception('Detalle bonificaciones: '.$conn->error);
    }

    // DEBE: Cuota Patronal (gasto)
    if ($cuotaPatronal > 0) {
      $debe = round($cuotaPatronal,2); $haber = 0.00;
      $ins->bind_param("iidd", $partidaId, $ctaCuotaPatGasto, $debe, $haber);
      if (!$ins->execute()) throw new Exception('Detalle cuota patronal (gasto): '.$conn->error);
    }

    // HABER: IGSS laboral por pagar
    if ($igssLaboral > 0) {
      $debe = 0.00; $haber = round($igssLaboral,2);
      $ins->bind_param("iidd", $partidaId, $ctaIGSSLaboral, $debe, $haber);
      if (!$ins->execute()) throw new Exception('Detalle IGSS laboral: '.$conn->error);
    }

    // HABER: Patronales por pagar
    if ($cuotaPatronal > 0) {
      $debe = 0.00; $haber = round($cuotaPatronal,2);
      $ins->bind_param("iidd", $partidaId, $ctaPatronalesXP, $debe, $haber);
      if (!$ins->execute()) throw new Exception('Detalle patronales por pagar: '.$conn->error);
    }

    // HABER: ISR por pagar
    if ($isrSueldo > 0) {
      $debe = 0.00; $haber = round($isrSueldo,2);
      $ins->bind_param("iidd", $partidaId, $ctaISRXP, $debe, $haber);
      if (!$ins->execute()) throw new Exception('Detalle ISR: '.$conn->error);
    }

    // HABER: Bancos/Caja (neto a pagar)
    if ($bancosCaja != 0) {
      $debe = 0.00; $haber = round($bancosCaja,2);
      $ins->bind_param("iidd", $partidaId, (stripos($medioPago,'caja')!==false?$ctaCaja:$ctaBancos), $debe, $haber);
      if (!$ins->execute()) throw new Exception('Detalle pago: '.$conn->error);
    }

    $ins->close();
    $conn->commit();
    return (int)$partidaId;
  } catch (Throwable $e) {
    $conn->rollback();
    throw $e;
  }
}
