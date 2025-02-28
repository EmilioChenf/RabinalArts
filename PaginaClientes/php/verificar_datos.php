<?php
session_start();
include 'config.php';

$response = ["success" => false];

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT telefono, direccion FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($telefono, $direccion);
    $stmt->fetch();
    $stmt->close();

    $datos_completos = !empty($telefono) && !empty($direccion);

    $response = [
        "success" => true,
        "datos_completos" => $datos_completos,
        "telefono" => $telefono,
        "direccion" => $direccion
    ];
}

echo json_encode($response);
?>
