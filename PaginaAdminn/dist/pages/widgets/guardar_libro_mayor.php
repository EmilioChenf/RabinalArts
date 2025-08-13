<?php
// guardar_libro_mayor.php
// Agrega/actualiza en libro_mayor todas las líneas de una cuenta sin duplicar
date_default_timezone_set('America/Guatemala');
include 'conexion.php';

$cuentaId = isset($_POST['cuenta_id']) ? (int)$_POST['cuenta_id'] : 0;
if ($cuentaId <= 0) {
  http_response_code(400);
  exit('Falta cuenta_id válido');
}

// Verificar que exista la cuenta
$ok = 0;
if ($st = $conn->prepare("SELECT 1 FROM cuentas_contables WHERE id=?")) {
  $st->bind_param("i", $cuentaId);
  $st->execute();
  $st->bind_result($ok);
  $st->fetch();
  $st->close();
}
if (!$ok) { http_response_code(404); exit('La cuenta no existe'); }

$conn->begin_transaction();
try {
  // GENERAL
  $sql = "
    INSERT INTO libro_mayor (cuenta_id, origen, origen_detalle_id, partida_id, fecha, debe, haber)
    SELECT pd.cuenta_id, 'general', pd.id, pd.partida_id, pc.fecha, pd.debe, pd.haber
    FROM partida_detalle pd
    JOIN partidas_contables pc ON pc.id = pd.partida_id
    WHERE pd.cuenta_id = ?
    ON DUPLICATE KEY UPDATE
      cuenta_id = VALUES(cuenta_id),
      partida_id = VALUES(partida_id),
      fecha     = VALUES(fecha),
      debe      = VALUES(debe),
      haber     = VALUES(haber)";
  $st = $conn->prepare($sql); $st->bind_param("i", $cuentaId); $st->execute(); $st->close();

  // COMPRAS
  $sql = "
    INSERT INTO libro_mayor (cuenta_id, origen, origen_detalle_id, partida_id, fecha, debe, haber)
    SELECT pccd.cuenta_id, 'compras', pccd.id, pccd.partida_id, pcc.created_at, pccd.debe, pccd.haber
    FROM partidas_contables_compras_detalle pccd
    JOIN partidas_contables_compras pcc ON pcc.id = pccd.partida_id
    WHERE pccd.cuenta_id = ?
    ON DUPLICATE KEY UPDATE
      cuenta_id = VALUES(cuenta_id),
      partida_id = VALUES(partida_id),
      fecha     = VALUES(fecha),
      debe      = VALUES(debe),
      haber     = VALUES(haber)";
  $st = $conn->prepare($sql); $st->bind_param("i", $cuentaId); $st->execute(); $st->close();

  // VENTAS
  $sql = "
    INSERT INTO libro_mayor (cuenta_id, origen, origen_detalle_id, partida_id, fecha, debe, haber)
    SELECT pdv.cuenta_id, 'ventas', pdv.id, pdv.partida_id, pcv.fecha, pdv.debe, pdv.haber
    FROM partida_detalle_ventas pdv
    JOIN partidas_contables_ventas pcv ON pcv.id = pdv.partida_id
    WHERE pdv.cuenta_id = ?
    ON DUPLICATE KEY UPDATE
      cuenta_id = VALUES(cuenta_id),
      partida_id = VALUES(partida_id),
      fecha     = VALUES(fecha),
      debe      = VALUES(debe),
      haber     = VALUES(haber)";
  $st = $conn->prepare($sql); $st->bind_param("i", $cuentaId); $st->execute(); $st->close();

  // PLANILLA
  $sql = "
    INSERT INTO libro_mayor (cuenta_id, origen, origen_detalle_id, partida_id, fecha, debe, haber)
    SELECT pdp.cuenta_id, 'planilla', pdp.id, pdp.partida_id, pcp.created_at, pdp.debe, pdp.haber
    FROM partida_detalle_planilla pdp
    JOIN partidas_contables_planilla pcp ON pcp.id = pdp.partida_id
    WHERE pdp.cuenta_id = ?
    ON DUPLICATE KEY UPDATE
      cuenta_id = VALUES(cuenta_id),
      partida_id = VALUES(partida_id),
      fecha     = VALUES(fecha),
      debe      = VALUES(debe),
      haber     = VALUES(haber)";
  $st = $conn->prepare($sql); $st->bind_param("i", $cuentaId); $st->execute(); $st->close();

  $conn->commit();

  // Redirigir de vuelta a la pantalla de saldos con mensaje
  header('Location: saldo_cuentas.php?cuenta_id='.urlencode($cuentaId).'&sync=ok');
  exit;
} catch (Throwable $e) {
  $conn->rollback();
  http_response_code(500);
  echo "Error al sincronizar: ".$e->getMessage();
}
