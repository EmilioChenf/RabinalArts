<?php
// generar_partida_compras_auto.php
// Debe vivir en la MISMA carpeta que factura_compras.php
// Responde SIEMPRE JSON.

declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

require_once 'conexion.php';

function json_fail(string $msg, int $code = 400) {
  http_response_code($code);
  echo json_encode(['success' => false, 'message' => $msg], JSON_UNESCAPED_UNICODE);
  exit;
}
function json_ok(array $data = []) {
  echo json_encode(array_merge(['success' => true], $data), JSON_UNESCAPED_UNICODE);
  exit;
}

// 1) Leer input JSON (fallback a POST clásico por si acaso)
$raw = file_get_contents('php://input');
$in  = json_decode($raw ?? '', true);
if (!is_array($in)) {
  // Intentar x-www-form-urlencoded por compatibilidad
  $in = $_POST ?? [];
}

$compra_id   = isset($in['compra_id']) ? (int)$in['compra_id'] : 0;
$descripcion = isset($in['descripcion']) ? trim((string)$in['descripcion']) : '';

if ($compra_id <= 0) {
  json_fail('Parámetro compra_id inválido.');
}

// 2) Traer compra
$stmt = $conn->prepare("SELECT id, forma_pago, periodo_pago, nombre_producto,
                               numero_cuenta_contable,
                               valor_iva, valor_sin_iva,
                               total_producto_sin_iva, total_iva,
                               total_sin_iva_general, total_general,
                               fecha_registro
                          FROM compras_internas
                         WHERE id = ?");
$stmt->bind_param('i', $compra_id);
$stmt->execute();
$compra = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$compra) json_fail('Compra interna no encontrada.');

// 3) Montos (ya vienen calculados en tu tabla)
$sinIva = (float)$compra['total_sin_iva_general'];  // Debe
$iva    = (float)$compra['total_iva'];              // Debe
$total  = (float)$compra['total_general'];          // Haber

// 4) Resolver cuentas
// Cuenta de gasto/activo viene en 'numero_cuenta_contable' (esperamos el ID)
$ctaMercaderia = (int)$compra['numero_cuenta_contable'];
if ($ctaMercaderia <= 0) json_fail('La compra no tiene número de cuenta contable válido.');

// Buscar cuenta por nombre (devuelve el id con menor id si hay duplicados)
function findCuentaIdByNombre(mysqli $conn, string $nombreBuscado): ?int {
  $sql = "SELECT id FROM cuentas_contables WHERE LOWER(nombre) = LOWER(?) ORDER BY id ASC LIMIT 1";
  $stm = $conn->prepare($sql);
  $stm->bind_param('s', $nombreBuscado);
  $stm->execute();
  $res = $stm->get_result()->fetch_assoc();
  $stm->close();
  return $res ? (int)$res['id'] : null;
}

$ctaIVAporCobrar = findCuentaIdByNombre($conn, 'IVA por Cobrar');
if (!$ctaIVAporCobrar) json_fail('No existe la cuenta "IVA por Cobrar" en el catálogo.');

$forma = trim((string)$compra['forma_pago']);
$ctaHaber = null;
switch ($forma) {
  case 'Efectivo':
    $ctaHaber = findCuentaIdByNombre($conn, 'Caja'); // en tu DB existe id=6
    if (!$ctaHaber) json_fail('No existe la cuenta "Caja" para forma de pago Efectivo.');
    break;

  case 'Crédito':
    $ctaHaber = findCuentaIdByNombre($conn, 'Proveedores o Acreedores Comerciales'); // id=72 en tu dump
    if (!$ctaHaber) json_fail('No existe la cuenta "Proveedores o Acreedores Comerciales" para forma de pago Crédito.');
    break;

  case 'Crédito con documentos: Cheque':
    $ctaHaber = findCuentaIdByNombre($conn, 'Documentos por Pagar Comerciales CP'); // id=85 en tu dump
    if (!$ctaHaber) json_fail('No existe la cuenta "Documentos por Pagar Comerciales CP" para Cheque.');
    break;

  default:
    // Si algún día agregas Transferencia, podrías usar Bancos
    $ctaHaber = findCuentaIdByNombre($conn, 'Bancos'); // fallback
    if (!$ctaHaber) json_fail('No existe la cuenta "Bancos" (forma de pago no reconocida).');
}

if ($sinIva < 0 || $iva < 0 || $total <= 0) {
  json_fail('Montos inválidos (revisa total_sin_iva_general, total_iva, total_general).');
}

// Ajuste de redondeo: que Debe == Haber
$sumDebe = round($sinIva + $iva, 2);
$haber   = round($total, 2);
if ($sumDebe !== $haber) {
  // Alinear al centavo en el Haber
  $haber = $sumDebe;
}

// 5) Insertar partida + detalle + reflejo en libro_mayor
$conn->begin_transaction();

try {
  // Partida
  $desc = $descripcion !== '' ? $descripcion : ('Partida automática compra interna #' . $compra_id);
  $sqlPart = "INSERT INTO partidas_contables_compras (compra_id, descripcion, created_at)
              VALUES (?, ?, NOW())";
  $sp = $conn->prepare($sqlPart);
  $sp->bind_param('is', $compra_id, $desc);
  $sp->execute();
  $partida_id = (int)$conn->insert_id;
  $sp->close();

  // Helper inserta línea detalle y devuelve id
  $sqlDet = "INSERT INTO partidas_contables_compras_detalle (partida_id, cuenta_id, debe, haber)
             VALUES (?, ?, ?, ?)";
  $sd = $conn->prepare($sqlDet);

  // Debe: cuenta de gasto/activo (sin IVA)
  $debe1 = $sinIva; $haber0 = 0.00;
  $sd->bind_param('iidd', $partida_id, $ctaMercaderia, $debe1, $haber0);
  $sd->execute();
  $det1_id = (int)$conn->insert_id;

  // Debe: IVA por Cobrar
  $debe2 = $iva;
  $sd->bind_param('iidd', $partida_id, $ctaIVAporCobrar, $debe2, $haber0);
  $sd->execute();
  $det2_id = (int)$conn->insert_id;

  // Haber: forma de pago
  $debe0 = 0.00; $haber3 = $haber;
  $sd->bind_param('iidd', $partida_id, $ctaHaber, $debe0, $haber3);
  $sd->execute();
  $det3_id = (int)$conn->insert_id;

  $sd->close();

  // Reflejo en libro_mayor (opcional pero recomendado)
  $fechaMov = date('Y-m-d H:i:s'); // usamos ahora
  $sqlLM = "INSERT INTO libro_mayor
              (cuenta_id, origen, origen_detalle_id, partida_id, fecha, debe, haber)
            VALUES (?, 'compras', ?, ?, ?, ?, ?)";
  $slm = $conn->prepare($sqlLM);

  // Línea 1
  $slm->bind_param('iiisdd', $ctaMercaderia, $det1_id, $partida_id, $fechaMov, $debe1, $haber0);
  $slm->execute();
  // Línea 2
  $slm->bind_param('iiisdd', $ctaIVAporCobrar, $det2_id, $partida_id, $fechaMov, $debe2, $haber0);
  $slm->execute();
  // Línea 3
  $slm->bind_param('iiisdd', $ctaHaber, $det3_id, $partida_id, $fechaMov, $debe0, $haber3);
  $slm->execute();

  $slm->close();

  $conn->commit();
  json_ok(['partida_id' => $partida_id]);

} catch (Throwable $e) {
  $conn->rollback();
  json_fail('Error al generar partida: ' . $e->getMessage(), 500);
}
