<?php
// guardar_partida_inicial.php
include 'conexion.php';
header('Content-Type: application/json; charset=utf-8');

// 0) Leer JSON del fetch
$payload = json_decode(file_get_contents('php://input'), true);
if (!$payload) {
  echo json_encode(['success'=>false,'message'=>'JSON inválido']);
  exit;
}

$descripcion = trim($payload['descripcion'] ?? '');
$fechaRaw    = trim($payload['fecha'] ?? ''); // viene de <input type="datetime-local">
$detalles    = $payload['detalles'] ?? [];

if ($descripcion === '' || !is_array($detalles) || count($detalles) === 0) {
  echo json_encode(['success'=>false,'message'=>'Faltan datos (descripcion/detalles)']);
  exit;
}

// 1) Normalizar fecha y periodo_mes para que salga primero del mes
// si no te mandan fecha, usamos el 1 del mes actual 00:00:00
try {
  if ($fechaRaw !== '') {
    // '2025-08-01T00:00' -> '2025-08-01 00:00:00'
    $fecha = str_replace('T', ' ', $fechaRaw) . (strlen($fechaRaw) === 16 ? ':00' : '');
  } else {
    $fecha = date('Y-m-01 00:00:00');
  }
  $periodo_mes = date('Y-m-01', strtotime($fecha));  // primer día de ese mes
} catch (\Throwable $e) {
  echo json_encode(['success'=>false,'message'=>'Fecha inválida']);
  exit;
}

// 2) Iniciar transacción
$conn->begin_transaction();
try {
  // 2.1 Insertar encabezado en partidas_contables
  $sqlP = "INSERT INTO partidas_contables (descripcion, fecha, periodo_mes, origen, origen_id)
           VALUES (?, ?, ?, 'general', 0)";
  $stP  = $conn->prepare($sqlP);
  if (!$stP) throw new Exception($conn->error);
  $stP->bind_param('sss', $descripcion, $fecha, $periodo_mes);
  if (!$stP->execute()) throw new Exception($stP->error);
  $partida_id = $stP->insert_id;
  $stP->close();

  // 2.2 Insertar líneas en partida_detalle
  $sqlD = "INSERT INTO partida_detalle (partida_id, cuenta_id, debe, haber) VALUES (?, ?, ?, ?)";
  $stD  = $conn->prepare($sqlD);
  if (!$stD) throw new Exception($conn->error);

  foreach ($detalles as $d) {
    $cuenta_id = (int)($d['cuenta_id'] ?? 0);
    $debe      = (float)($d['debe'] ?? 0);
    $haber     = (float)($d['haber'] ?? 0);
    if ($cuenta_id <= 0) continue;
    $stD->bind_param('iidd', $partida_id, $cuenta_id, $debe, $haber);
    if (!$stD->execute()) throw new Exception($stD->error);
  }
  $stD->close();

  // 2.3 Confirmar
  $conn->commit();
  echo json_encode(['success'=>true, 'partida_id'=>$partida_id]);
} catch (\Throwable $e) {
  $conn->rollback();
  // MUY IMPORTANTE: devolver JSON (no HTML) para que el fetch no truene
  echo json_encode(['success'=>false, 'message'=>$e->getMessage()]);
}
