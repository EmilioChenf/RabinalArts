<?php
// crear_producto_interno.php
include 'conexion.php';
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(0);

// Recoge y sanea
$nombre      = mysqli_real_escape_string($conn, $_POST['nombre']);
$cuenta_id   = (int) $_POST['cuenta_id'];
$precios     = (float) $_POST['precios'];
$cantidad    = (int) $_POST['cantidad'];
$descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);

// Inserta
$sql = "INSERT INTO gestion_productos_internos (nombre, cuenta_id, precios, cantidad, descripcion)
        VALUES ('$nombre', $cuenta_id, $precios, $cantidad, '$descripcion')";

if (mysqli_query($conn, $sql)) {
    $id = mysqli_insert_id($conn);
    // Busca el nombre de la cuenta
    $r = mysqli_query($conn, "SELECT nombre FROM cuentas_contables WHERE id=$cuenta_id");
    $c = mysqli_fetch_assoc($r);

    echo json_encode([
      'success' => true,
      'data' => [
        'id'          => $id,
        'nombre'      => $nombre,
        'cuenta'      => $c['nombre'],
        'precios'     => $precios,
        'cantidad'    => $cantidad,
        'descripcion' => $descripcion
      ]
    ]);
} else {
    echo json_encode([
      'success' => false,
      'message' => mysqli_error($conn)
    ]);
}
exit;
