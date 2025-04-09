<?php
session_start();
include 'config.php'; // Asegurar conexión a la base de datos

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener los datos del usuario
$stmt = $conn->prepare("SELECT telefono, direccion FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($telefono, $direccion);
$stmt->fetch();
$stmt->close();
?>
