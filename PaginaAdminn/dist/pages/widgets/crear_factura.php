<?php
include 'conexion.php';
session_start();

if (!isset($_POST['cliente_id'])) {
    header("Location: factura.php");
    exit();
}

$cliente_id = intval($_POST['cliente_id']);
$fecha = date("Y-m-d");

$stmt = $conn->prepare("INSERT INTO ventas (cliente_id, fecha, total) VALUES (?, ?, 0)");
$stmt->bind_param("is", $cliente_id, $fecha);
$stmt->execute();

$id_venta = $stmt->insert_id;

header("Location: detalle_factura.php?venta_id=" . $id_venta);
exit();
