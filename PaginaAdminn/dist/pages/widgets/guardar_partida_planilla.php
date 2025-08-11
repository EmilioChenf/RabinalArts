<?php
// guardar_partida_planilla.php
include 'conexion.php';
header('Content-Type: application/json; charset=utf-8');

$payload = json_decode(file_get_contents('php://input'), true);
if (!$payload) {
  echo json_encode(['success'=>false,'message'=>'JSON inválido']);
  exit;
}

$planilla_id = (int)($payload['planilla_id'] ?? 0);
$desc        = mysqli_real_escape_string($conn, $payload['descripcion'] ?? '');
$detalles    = $payload['detalles'] ?? [];

if ($planilla_id <= 0 || empty($detalles)) {
  echo json_encode(['success'=>false,'message'=>'Datos incompletos']);
  exit;
}

mysqli_begin_transaction($conn);

$sql = "INSERT INTO partidas_contables_planilla (planilla_id, descripcion)
        VALUES ($planilla_id, '$desc')";
if (!mysqli_query($conn, $sql)) {
  mysqli_rollback($conn);
  echo json_encode(['success'=>false,'message'=>mysqli_error($conn)]);
  exit;
}
$pid = mysqli_insert_id($conn);

$stmt = $conn->prepare(
  "INSERT INTO partida_detalle_planilla (partida_id, cuenta_id, debe, haber)
   VALUES (?,?,?,?)"
);
if (!$stmt) {
  mysqli_rollback($conn);
  echo json_encode(['success'=>false,'message'=>$conn->error]);
  exit;
}
foreach ($detalles as $d) {
  $cuenta_id = (int)$d['cuenta_id'];
  $debe  = (float)$d['debe'];
  $haber = (float)$d['haber'];
  $stmt->bind_param('iidd', $pid, $cuenta_id, $debe, $haber);
  if (!$stmt->execute()) {
    mysqli_rollback($conn);
    echo json_encode(['success'=>false,'message'=>$stmt->error]);
    exit;
  }
}

mysqli_commit($conn);
echo json_encode(['success'=>true,'partida_id'=>$pid]);
exit;
