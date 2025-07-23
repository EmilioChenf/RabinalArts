<?php
include 'conexion.php';
header('Content-Type: application/json');

$id          = (int) $_POST['id'];
$nombre      = mysqli_real_escape_string($conn, $_POST['nombre']);
$cuenta_id   = (int) $_POST['cuenta_id'];
$precios     = (float) $_POST['precios'];
$cantidad    = (int) $_POST['cantidad'];
$descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);

$sql = "UPDATE gestion_productos_internos
        SET nombre='$nombre', cuenta_id=$cuenta_id,
            precios=$precios, cantidad=$cantidad,
            descripcion='$descripcion'
        WHERE id=$id";

if (mysqli_query($conn, $sql)) {
  // Obtener nombre de cuenta actualizada
  $res = mysqli_query($conn, "SELECT nombre FROM cuentas_contables WHERE id=$cuenta_id");
  $c   = mysqli_fetch_assoc($res);
  echo json_encode([
    'success' => true,
    'data' => [
      'id'       => $id,
      'nombre'   => $nombre,
      'cuenta'   => $c['nombre'],
      'precios'  => $precios,
      'cantidad' => $cantidad,
      'descripcion' => $descripcion
    ]
  ]);
} else {
  echo json_encode(['success'=>false,'message'=>mysqli_error($conn)]);
}
