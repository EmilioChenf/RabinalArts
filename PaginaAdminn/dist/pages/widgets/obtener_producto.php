<?php
include 'conexion.php';

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT nombre, precio FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "success" => true,
        "nombre" => $row['nombre'],
        "precio" => $row['precio']
    ]);
} else {
    echo json_encode(["success" => false]);
}
?>
