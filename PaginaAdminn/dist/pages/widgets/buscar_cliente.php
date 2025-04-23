<?php
include 'conexion.php';

$id = intval($_GET['id']);

$query = $conn->prepare("SELECT nombre, telefono, direccion FROM usuarios WHERE id = ? AND rol = 'cliente'");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();

if ($cliente = $result->fetch_assoc()) {
    echo json_encode([
        "success" => true,
        "nombre" => $cliente['nombre'],
        "telefono" => $cliente['telefono'],
        "direccion" => $cliente['direccion']
    ]);
} else {
    echo json_encode(["success" => false]);
}
