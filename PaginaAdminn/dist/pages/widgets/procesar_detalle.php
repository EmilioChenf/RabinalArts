<?php
include 'conexion.php';

$venta_id = $_POST['venta_id'];
$producto_id = $_POST['producto_id'];
$cantidad = $_POST['cantidad'];

$stmt = $conn->prepare("SELECT precio FROM productos WHERE id = ?");
$stmt->bind_param("i", $producto_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    $precio_unitario = $row['precio'];
    $total = $precio_unitario * $cantidad;

    $stmt_insert = $conn->prepare("INSERT INTO detalle_venta (venta_id, producto_id, cantidad, precio_unitario, total) VALUES (?, ?, ?, ?, ?)");
    $stmt_insert->bind_param("iiidd", $venta_id, $producto_id, $cantidad, $precio_unitario, $total);
    $stmt_insert->execute();

    header("Location: venta_factura.php?id_venta=$venta_id&success=1");
    exit;
} else {
    echo "Producto no encontrado.";
}
?>
