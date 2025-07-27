<?php
// guardar_partida_compras.php
include 'conexion.php';
header('Content-Type: application/json');

// Leer payload JSON
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
  echo json_encode(['success'=>false,'message'=>'JSON inválido']);
  exit;
}

$compra_id   = (int)   ($input['compra_id']   ?? 0);
$descripcion =         ($input['descripcion'] ?? '');
$detalles    = $input['detalles'] ?? [];

if (!$compra_id || !is_array($detalles)) {
  echo json_encode(['success'=>false,'message'=>'Datos incompletos']);
  exit;
}

// 1) Insertar la partida principal
$stmt = $conn->prepare(
  "INSERT INTO partidas_contables_compras (compra_id, descripcion)
   VALUES (?, ?)"
);
$stmt->bind_param('is', $compra_id, $descripcion);
if (!$stmt->execute()) {
  echo json_encode(['success'=>false,'message'=>$stmt->error]);
  exit;
}
$partida_id = $stmt->insert_id;
$stmt->close();

// 2) Insertar cada línea de detalle
$insert = $conn->prepare(
  "INSERT INTO partidas_contables_compras_detalle
     (partida_id, cuenta_id, debe, haber)
   VALUES (?, ?, ?, ?)"
);
foreach ($detalles as $d) {
  $cid   = (int)   $d['cuenta_id'];
  $debe  = (float) $d['debe'];
  $haber = (float) $d['haber'];
  $insert->bind_param('iidd', $partida_id, $cid, $debe, $haber);
  if (!$insert->execute()) {
    echo json_encode(['success'=>false,'message'=>$insert->error]);
    exit;
  }
}
$insert->close();

// 3) Responder OK
echo json_encode(['success'=>true,'partida_id'=>$partida_id]);
