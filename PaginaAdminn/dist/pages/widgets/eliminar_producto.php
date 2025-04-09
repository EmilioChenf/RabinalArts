<?php
include 'conexion.php';

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT imagen FROM productos WHERE id = $id");
$producto = mysqli_fetch_assoc($result);

if ($producto && $producto['imagen']) {
    $rutaImagen = "uploads/" . $producto['imagen'];
    if (file_exists($rutaImagen)) {
        unlink($rutaImagen);
    }
}

mysqli_query($conn, "DELETE FROM productos WHERE id = $id");
header("Location: productos.php?mensaje=eliminado");
exit;
?>
