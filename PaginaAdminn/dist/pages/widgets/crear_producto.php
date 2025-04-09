<?php
include 'conexion.php';

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$categoria = $_POST['categoria'];
$precio = $_POST['precio'];
$stock = $_POST['stock'];

$imagen = '';
if ($_FILES['imagen']['name']) {
    $nombreImagen = uniqid() . '_' . basename($_FILES['imagen']['name']);
    move_uploaded_file($_FILES['imagen']['tmp_name'], "uploads/$nombreImagen");
    $imagen = $nombreImagen;
}

$sql = "INSERT INTO productos (nombre, descripcion, categoria, precio, stock, imagen)
        VALUES ('$nombre', '$descripcion', '$categoria', '$precio', '$stock', '$imagen')";

if (mysqli_query($conn, $sql)) {
    header("Location: productos.php?mensaje=creado");
    exit;
} else {
    header("Location: productos.php?mensaje=error");
    exit;
}
?>
