<?php
session_start();
include '../../Animated Login/config.php'; // Ajusta la ruta según tu estructura

// Verifica que el usuario haya iniciado sesión
if (!isset($_SESSION['user_id'])) {
    echo "Error: No has iniciado sesión.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $numero = trim($_POST['numero']);
    $direccion = trim($_POST['direccion']);

    // Validar que los campos no estén vacíos
    if (empty($numero) || empty($direccion)) {
        echo "Por favor, completa todos los campos.";
        exit();
    }

    // Actualizar los datos en la base de datos
    $stmt = $conn->prepare("UPDATE usuarios SET telefono = ?, direccion = ? WHERE id = ?");
    $stmt->bind_param("ssi", $numero, $direccion, $user_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Datos guardados correctamente.');
                window.location.href = '../index.php';
              </script>";
    } else {
        echo "Error al actualizar los datos.";
    }

    $stmt->close();
    $conn->close();
}
?>
