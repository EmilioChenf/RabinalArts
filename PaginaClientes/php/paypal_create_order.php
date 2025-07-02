<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/paypal_helpers.php';

$data      = json_decode(file_get_contents('php://input'), true);
$productos = $data['carrito'] ?? [];

if (empty($productos)) {
    http_response_code(400);
    echo json_encode(['error' => 'Carrito vacío']);
    exit;
}

// Calcula total
$total = 0;
foreach ($productos as $p) {
    $total += floatval($p['precio']) * intval($p['cantidad']);
}

// Crear orden en PayPal
$token = obtenerAccessTokenPayPal();
$order = crearOrdenPayPal($token, $total);

if (!empty($order['id'])) {
    echo json_encode(['orderID' => $order['id']]);
    exit;
}

// Error al crear orden
http_response_code(500);
echo json_encode(['error' => 'No fue posible crear la orden']);
exit;
