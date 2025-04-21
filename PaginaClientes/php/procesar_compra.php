<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || empty($_COOKIE['carrito'])) {
    header("Location: ../carrito.php?error=carrito_vacio");
    exit();
}

$user_id = $_SESSION['user_id'];
$productos = json_decode($_COOKIE['carrito'], true);

if (!is_array($productos) || count($productos) === 0) {
    header("Location: ../carrito.php?error=carrito_vacio");
    exit();
}

// Insertar venta
$fecha = date("Y-m-d");
$stmtVenta = $conn->prepare("INSERT INTO ventas (cliente_id, fecha) VALUES (?, ?)");
$stmtVenta->bind_param("is", $user_id, $fecha);
$stmtVenta->execute();
$idVenta = $stmtVenta->insert_id;

// Insertar detalle
$stmtDetalle = $conn->prepare("INSERT INTO detalle_venta (venta_id, producto_id, cantidad, precio_unitario, total) VALUES (?, ?, ?, ?, ?)");

foreach ($productos as $producto) {
    $idProducto = intval($producto['id']);
    $cantidad = intval($producto['cantidad']);

    if ($idProducto <= 0 || $cantidad <= 0) continue;

    $query = $conn->prepare("SELECT precio FROM productos WHERE id = ?");
    $query->bind_param("i", $idProducto);
    $query->execute();
    $result = $query->get_result();

    if ($row = $result->fetch_assoc()) {
        $precio = floatval($row['precio']);
        $total = $precio * $cantidad;

        $stmtDetalle->bind_param("iiidd", $idVenta, $idProducto, $cantidad, $precio, $total);
        $stmtDetalle->execute();
    }
}

// Limpiar carrito (cookie y localStorage mediante JS)
setcookie("carrito", "", time() - 3600, "/");

// Redirigir
header("Location: ../carrito.php?compra=ok");
exit();
