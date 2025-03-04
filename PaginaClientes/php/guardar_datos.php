<?php
session_start();
include 'config.php'; // Ruta correcta para la conexión a la base de datos

header('Content-Type: application/json'); // Enviar JSON como respuesta

$response = ["success" => false];

if (!isset($_SESSION['user_id'])) {
    $response["error"] = "No has iniciado sesión.";
    echo json_encode($response);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $numero = isset($_POST['numero']) ? trim($_POST['numero']) : "";
    $direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : "";

    if (empty($numero) || empty($direccion)) {
        $response["error"] = "Los campos no pueden estar vacíos.";
        echo json_encode($response);
        exit();
    }

    // Actualizar datos en la base de datos
    $stmt = $conn->prepare("UPDATE usuarios SET telefono = ?, direccion = ? WHERE id = ?");
    $stmt->bind_param("ssi", $numero, $direccion, $user_id);

    if ($stmt->execute()) {
        $response["success"] = true;
        $response["message"] = "Datos guardados correctamente.";
    } else {
        $response["error"] = "Error al guardar los datos: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

echo json_encode($response);
?>
