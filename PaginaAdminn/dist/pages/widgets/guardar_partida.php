<?php
// guardar_partida.php
include 'conexion.php';
header('Content-Type: application/json; charset=utf-8');

$payload = json_decode(file_get_contents('php://input'), true);
if (!$payload) {
  echo json_encode(['success'=>false,'message'=>'JSON inválido']);
  exit;
}

$cliente_id = (int)$payload['cliente_id'];
$desc       = mysqli_real_escape_string($conn, $payload['descripcion']);
$detalles   = $payload['detalles'];

mysqli_begin_transaction($conn);

$sql = "INSERT INTO partidas_contables_ventas (cliente_id, descripcion)
        VALUES ($cliente_id, '$desc')";
if (!mysqli_query($conn, $sql)) {
  mysqli_rollback($conn);
  echo json_encode(['success'=>false,'message'=>mysqli_error($conn)]);
  exit;
}
$pid = mysqli_insert_id($conn);

$stmt = $conn->prepare(
  "INSERT INTO partida_detalle_ventas (partida_id, cuenta_id, debe, haber)
   VALUES (?,?,?,?)"
);
foreach ($detalles as $d) {
  $stmt->bind_param('iidd',
    $pid,
    $d['cuenta_id'],
    $d['debe'],
    $d['haber']
  );
  if (!$stmt->execute()) {
    mysqli_rollback($conn);
    echo json_encode(['success'=>false,'message'=>$stmt->error]);
    exit;
  }
}

mysqli_commit($conn);
echo json_encode(['success'=>true,'partida_id'=>$pid]);
exit;
