<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'cliente') {
    header("Location: index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("UPDATE usuarios SET telefono = ?, direccion = ? WHERE id = ?");
    $stmt->bind_param("ssi", $telefono, $direccion, $user_id);

    if ($stmt->execute()) {
        echo "Datos actualizados correctamente.";
        header("Location: cliente_dashboard.php"); // Redirigir al panel de cliente
        exit();
    } else {
        echo "Error al actualizar los datos.";
    }

    $stmt->close();
    $conn->close();
}
?>
