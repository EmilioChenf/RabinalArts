<?php
session_start();
include 'conexion.php';

$id_usuario = $_SESSION['id'] ?? null;
if (!$id_usuario || empty($_SESSION['factura_detalle'])) {
  header("Location: venta_factura.php");
  exit;
}

$detalle = $_SESSION['factura_detalle'];
$total = array_sum(array_column($detalle, 'subtotal'));

// Insertar venta
$stmtVenta = $conn->prepare("INSERT INTO ventas (cliente_id, fecha, total) VALUES (?, NOW(), ?)");
$stmtVenta->bind_param("id", $id_usuario, $total);
$stmtVenta->execute();
$id_venta = $stmtVenta->insert_id;

// Insertar detalle
$stmtDetalle = $conn->prepare("INSERT INTO detalle_venta (venta_id, producto_id, cantidad, precio_unitario, total) VALUES (?, ?, ?, ?, ?)");

foreach ($detalle as $item) {
  $stmtDetalle->bind_param(
    "iiidd",
    $id_venta,
    $item['producto_id'],
    $item['cantidad'],
    $item['precio'],
    $item['subtotal']
  );
  $stmtDetalle->execute();
}

// Limpiar carrito temporal
unset($_SESSION['factura_detalle']);

// Redirigir a vista de factura
header("Location: ver_factura.php?id=$id_venta");
exit;
?>
