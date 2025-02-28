<?php
session_start();
include 'config.php';

$response = ["success" => false];

if (isset($_SESSION['user_id']) && isset($_POST['telefono']) && isset($_POST['direccion'])) {
    $user_id = $_SESSION['user_id'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    $stmt = $conn->prepare("UPDATE usuarios SET telefono = ?, direccion = ? WHERE id = ?");
    $stmt->bind_param("ssi", $telefono, $direccion, $user_id);
    if ($stmt->execute()) {
        $response["success"] = true;
    }
    $stmt->close();
}

echo json_encode($response);
?>
