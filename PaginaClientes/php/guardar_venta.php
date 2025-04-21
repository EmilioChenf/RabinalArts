<?php
session_start();
include 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$productos = $data['productos'] ?? [];

if (empty($productos)) {
    echo json_encode(['success' => false, 'message' => 'No hay productos en la compra']);
    exit;
}

$user_id = $_SESSION['user_id'];
$fecha = date("Y-m-d");

$stmtVenta = $conn->prepare("INSERT INTO ventas (id_usuario, fecha) VALUES (?, ?)");
$stmtVenta->bind_param("is", $user_id, $fecha);

if (!$stmtVenta->execute()) {
    echo json_encode(['success' => false, 'message' => 'Error al crear venta']);
    exit;
}

$idVenta = $stmtVenta->insert_id;

$stmtDetalle = $conn->prepare("INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario, total) VALUES (?, ?, ?, ?, ?)");
foreach ($productos as $producto) {
    $idProducto = intval($producto['id']);
    $cantidad = intval($producto['cantidad']);
    $precio = floatval($producto['precio']);
    $total = $cantidad * $precio;

    $stmtDetalle->bind_param("iiidd", $idVenta, $idProducto, $cantidad, $precio, $total);
    $stmtDetalle->execute();
}

echo json_encode(['success' => true, 'message' => 'Compra registrada']);
