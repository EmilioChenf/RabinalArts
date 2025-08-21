<?php
// php/paypal_capture_order.php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

session_start();
require __DIR__ . '/config.php';
require __DIR__ . '/paypal_insert_venta.php'; // <- tu función insertarVentaCompleta()

// 1) Leer body
$input = json_decode(file_get_contents('php://input'), true);
$orderID   = $input['orderID']   ?? '';
$telefono  = $input['telefono']  ?? ''; // opcional, por si lo mandas desde el front
$direccion = $input['direccion'] ?? ''; // opcional, por si lo mandas desde el front

if (!$orderID) {
  echo json_encode(['error' => 'orderID missing']);
  exit;
}

// 2) Token PayPal
$ch = curl_init(PAYPAL_BASE_URL . '/v1/oauth2/token');
curl_setopt_array($ch, [
  CURLOPT_HTTPHEADER     => ['Accept: application/json','Accept-Language: en_US'],
  CURLOPT_USERPWD        => PAYPAL_CLIENT_ID . ':' . PAYPAL_SECRET,
  CURLOPT_POSTFIELDS     => 'grant_type=client_credentials',
  CURLOPT_RETURNTRANSFER => true,
]);
$tokenData = json_decode(curl_exec($ch), true);
curl_close($ch);

if (empty($tokenData['access_token'])) {
  echo json_encode(['error' => 'No se obtuvo access_token']);
  exit;
}
$accessToken = $tokenData['access_token'];

// 3) Capturar la orden
$ch = curl_init(PAYPAL_BASE_URL . "/v2/checkout/orders/{$orderID}/capture");
curl_setopt_array($ch, [
  CURLOPT_HTTPHEADER     => ['Content-Type: application/json', "Authorization: Bearer {$accessToken}"],
  CURLOPT_POST           => true,
  CURLOPT_RETURNTRANSFER => true,
]);
$captureRes = curl_exec($ch);
$capData    = json_decode($captureRes, true);
curl_close($ch);

// 4) Si el pago quedó COMPLETED => guardar en BD
if (!empty($capData['status']) && $capData['status'] === 'COMPLETED') {
  // Recuperar usuario y carrito
  $user_id   = $_SESSION['user_id'] ?? 0;

  // Preferimos el carrito del body si lo envías; si no, usamos la cookie
  $productos = $input['carrito'] ?? json_decode($_COOKIE['carrito'] ?? '[]', true);
  if (!is_array($productos)) { $productos = []; }

  // Si no te mandan teléfono/dirección en el body, igual se guardará vacío o puedes leerlos de DB aquí.
  $ok = insertarVentaCompleta($user_id, $productos, $telefono, $direccion);

  if ($ok) {
    // limpiar carrito
    setcookie('carrito', '', time() - 3600, '/');
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['error' => 'Pago capturado, pero no se pudo registrar la venta en BD.']);
  }
  exit;
}

// 5) Error de captura
$msg = $capData['message'] ?? json_encode($capData, JSON_UNESCAPED_UNICODE);
echo json_encode(['error' => "Falló la captura: {$msg}"]);
