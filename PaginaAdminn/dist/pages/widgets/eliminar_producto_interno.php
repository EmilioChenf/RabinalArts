<?php
// eliminar_producto_interno.php
include 'conexion.php';
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(0);

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
if ($id <= 0) {
    echo json_encode(['success'=>false,'message'=>'ID inválido']);
    exit;
}

$sql = "DELETE FROM gestion_productos_internos WHERE id = $id";
if (mysqli_query($conn, $sql)) {
    echo json_encode(['success'=>true]);
} else {
    echo json_encode(['success'=>false,'message'=>mysqli_error($conn)]);
}
exit;
?>
