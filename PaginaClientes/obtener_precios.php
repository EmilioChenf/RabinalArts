<?php
include 'php/config.php';  // Conexión a la BD

// Recibir el JSON con los IDs
$data = json_decode(file_get_contents("php://input"), true);
$ids = $data['ids'] ?? [];

$response = [];

if (!empty($ids)) {
    // Preparar lista segura para SQL
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $conn->prepare("SELECT id, precio FROM productos WHERE id IN ($placeholders)");
    $stmt->execute($ids);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $response[$row['id']] = $row['precio'];
    }
}

echo json_encode($response);
?>
