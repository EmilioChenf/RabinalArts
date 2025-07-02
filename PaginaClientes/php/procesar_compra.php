<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || empty($_COOKIE['carrito'])) {
    header("Location: ../carrito.php?error=carrito_vacio");
    exit();
}

$user_id   = $_SESSION['user_id'];
$productos = json_decode($_COOKIE['carrito'], true);

if (!is_array($productos) || count($productos) === 0) {
    header("Location: ../carrito.php?error=carrito_vacio");
    exit();
}

// 1) Insertar venta
$fecha       = date("Y-m-d");
$stmtVenta   = $conn->prepare("INSERT INTO ventas (cliente_id, fecha) VALUES (?, ?)");
$stmtVenta->bind_param("is", $user_id, $fecha);
$stmtVenta->execute();
$idVenta     = $stmtVenta->insert_id;
$stmtVenta->close();

// 2) Insertar detalle y restar stock
$stmtDetalle     = $conn->prepare("
    INSERT INTO detalle_venta (venta_id, producto_id, cantidad, precio_unitario, total)
    VALUES (?, ?, ?, ?, ?)
");
$stmtUpdateStock = $conn->prepare("
    UPDATE productos
       SET stock = stock - ?
     WHERE id = ?
       AND stock >= ?
");

foreach ($productos as $producto) {
    $idProducto = intval($producto['id']);
    $cantidad   = intval($producto['cantidad']);

    if ($idProducto <= 0 || $cantidad <= 0) {
        continue;
    }

    // obtener precio actual
    $query = $conn->prepare("SELECT precio FROM productos WHERE id = ?");
    $query->bind_param("i", $idProducto);
    $query->execute();
    $result = $query->get_result();
    if (! $row = $result->fetch_assoc()) {
        $query->close();
        continue;
    }
    $precio = floatval($row['precio']);
    $total  = $precio * $cantidad;
    $query->close();

    // a) insertar detalle
    $stmtDetalle->bind_param("iiidd", $idVenta, $idProducto, $cantidad, $precio, $total);
    $stmtDetalle->execute();

    // b) restar stock
    $stmtUpdateStock->bind_param("iii", $cantidad, $idProducto, $cantidad);
    $stmtUpdateStock->execute();
}

$stmtDetalle->close();
$stmtUpdateStock->close();

// 3) Limpiar carrito (cookie)
setcookie("carrito", "", time() - 3600, "/");

// 4) Redirigir con éxito
header("Location: ../carrito.php?compra=ok");
exit();
