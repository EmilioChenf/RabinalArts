<?php
// generar_partida_compras_auto.php
// => Genera la partida CONTABLE automática para una compra interna.
// Responde SIEMPRE JSON.

declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

require_once 'conexion.php'; // Debe exponer $conn (mysqli)

function json_fail(string $msg, int $code = 400) {
  http_response_code($code);
  echo json_encode(['success' => false, 'message' => $msg], JSON_UNESCAPED_UNICODE);
  exit;
}
function json_ok(array $data = []) {
  echo json_encode(array_merge(['success' => true], $data), JSON_UNESCAPED_UNICODE);
  exit;
}

// 1) Leer input JSON; fallback a x-www-form-urlencoded
$raw = file_get_contents('php://input');
$in  = json_decode($raw ?? '', true);
if (!is_array($in)) $in = $_POST ?? [];

$compra_id   = isset($in['compra_id']) ? (int)$in['compra_id'] : 0;
$descripcion = isset($in['descripcion']) ? trim((string)$in['descripcion']) : '';
if ($compra_id <= 0) json_fail('Parámetro compra_id inválido.');

// 2) Traer la compra
$stmt = $conn->prepare("
  SELECT id, forma_pago, periodo_pago, nombre_producto,
         numero_cuenta_contable,
         total_sin_iva_general, total_iva, total_general,
         fecha_registro
  FROM compras_internas
  WHERE id = ?
  LIMIT 1
");
if (!$stmt) json_fail('Error prepare compra: ' . $conn->error, 500);
$stmt->bind_param('i', $compra_id);
$stmt->execute();
$compra = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$compra) json_fail('Compra interna no encontrada.');

// 3) Montos (tu tabla ya trae los totales listos; el precio incluye IVA 12%)
$sinIva = (float)$compra['total_sin_iva_general']; // Debe
$iva    = (float)$compra['total_iva'];             // Debe
$total  = (float)$compra['total_general'];         // Haber
if ($sinIva < 0 || $iva < 0 || $total <= 0) {
  json_fail('Montos inválidos (revisa total_sin_iva_general, total_iva, total_general).');
}

// 4) Resolver cuentas
// a) Cuenta de gasto/activo viene en 'numero_cuenta_contable' (ID)
$ctaMercaderia = (int)$compra['numero_cuenta_contable'];
if ($ctaMercaderia <= 0) json_fail('La compra no tiene número de cuenta contable válido.');

// b) Buscar cuenta por nombre (intenta exacto y luego LIKE, case-insensitive)
function findCuentaIdByNombres(mysqli $conn, array $nombres): ?int {
  // Intento EXACTO
  $sql = "SELECT id FROM cuentas_contables WHERE LOWER(nombre)=LOWER(?) ORDER BY id ASC LIMIT 1";
  $st  = $conn->prepare($sql);
  if (!$st) return null;
  foreach ($nombres as $n) {
    $st->bind_param('s', $n);
    $st->execute();
    if ($row = $st->get_result()->fetch_assoc()) { $st->close(); return (int)$row['id']; }
  }
  $st->close();
  // Intento LIKE
  $sql = "SELECT id FROM cuentas_contables WHERE LOWER(nombre) LIKE LOWER(?) ORDER BY id ASC LIMIT 1";
  $st  = $conn->prepare($sql);
  if (!$st) return null;
  foreach ($nombres as $n) {
    $like = '%'.$n.'%';
    $st->bind_param('s', $like);
    $st->execute();
    if ($row = $st->get_result()->fetch_assoc()) { $st->close(); return (int)$row['id']; }
  }
  $st->close();
  return null;
}

// c) IVA por cobrar (busca varias variantes)
$ctaIVAporCobrar = findCuentaIdByNombres($conn, [
  'IVA por Cobrar','IVA por cobrar','IVA acreditable','IVA compras'
]);
if (!$ctaIVAporCobrar) json_fail('No existe la cuenta "IVA por Cobrar" en el catálogo.');

// d) Haber según forma de pago
$forma = mb_strtoupper(trim((string)$compra['forma_pago']), 'UTF-8');
if ($forma === 'EFECTIVO') {
  $ctaHaber = findCuentaIdByNombres($conn, ['Caja']);
  if (!$ctaHaber) json_fail('No existe la cuenta "Caja" para forma de pago Efectivo.');
} elseif ($forma === 'BANCOS' || $forma === 'TRANSFERENCIA') {
  $ctaHaber = findCuentaIdByNombres($conn, ['Bancos','Banco']);
  if (!$ctaHaber) json_fail('No existe la cuenta "Bancos" para forma de pago Bancos/Transferencia.');
} elseif ($forma === 'CRÉDITO' || $forma === 'CREDITO') {
  $ctaHaber = findCuentaIdByNombres($conn, ['Proveedores o Acreedores Comerciales','Proveedores','Cuentas por pagar']);
  if (!$ctaHaber) json_fail('No existe la cuenta "Proveedores o Acreedores Comerciales" para Crédito.');
} else {
  // Fallback prudente
  $ctaHaber = findCuentaIdByNombres($conn, ['Bancos','Banco']);
  if (!$ctaHaber) json_fail('Forma de pago no reconocida y no se encontró "Bancos".');
}

// 5) Ajuste por redondeo: Debe == Haber
$sumDebe = round($sinIva + $iva, 2);
$haber   = round($total, 2);
if ($sumDebe !== $haber) $haber = $sumDebe;

// 6) Insertar partida + detalle + reflejo en libro_mayor
$conn->begin_transaction();

try {
  // Partida (usa tu tabla real)
  $desc = $descripcion !== '' ? $descripcion : ('Partida automática compra interna #' . $compra_id);
  $sqlPart = "INSERT INTO partidas_contables_compras (compra_id, descripcion, created_at)
              VALUES (?, ?, NOW())";
  $sp = $conn->prepare($sqlPart);
  if (!$sp) throw new Exception('Error prepare partida: ' . $conn->error);
  $sp->bind_param('is', $compra_id, $desc);
  $sp->execute();
  $partida_id = (int)$conn->insert_id;
  $sp->close();

  // Detalle
  $sqlDet = "INSERT INTO partidas_contables_compras_detalle (partida_id, cuenta_id, debe, haber)
             VALUES (?, ?, ?, ?)";
  $sd = $conn->prepare($sqlDet);
  if (!$sd) throw new Exception('Error prepare detalle: ' . $conn->error);

  // Debe: compra (sin IVA)
  $debe1= $sinIva; $haber0=0.00;
  $sd->bind_param('iidd', $partida_id, $ctaMercaderia, $debe1, $haber0);
  $sd->execute(); $det1_id=(int)$conn->insert_id;

  // Debe: IVA por cobrar
  $debe2= $iva;
  $sd->bind_param('iidd', $partida_id, $ctaIVAporCobrar, $debe2, $haber0);
  $sd->execute(); $det2_id=(int)$conn->insert_id;

  // Haber: Caja / Bancos / Proveedores
  $debe0=0.00; $haber3=$haber;
  $sd->bind_param('iidd', $partida_id, $ctaHaber, $debe0, $haber3);
  $sd->execute(); $det3_id=(int)$conn->insert_id;

  $sd->close();

  // Libro mayor (reflejo)
  $fechaMov = date('Y-m-d H:i:s');
  $sqlLM = "INSERT INTO libro_mayor
              (cuenta_id, origen, origen_detalle_id, partida_id, fecha, debe, haber)
            VALUES (?, 'compras', ?, ?, ?, ?, ?)";
  $slm = $conn->prepare($sqlLM);
  if (!$slm) throw new Exception('Error prepare libro mayor: '.$conn->error);

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
