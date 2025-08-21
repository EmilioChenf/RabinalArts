<?php
header('Content-Type: application/json');
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
require __DIR__ . '/config.php';

$input = json_decode(file_get_contents('php://input'), true);
if (empty($input['orderID'])) {
    echo json_encode(['error' => 'orderID missing']); exit;
}
$orderID = $input['orderID'];

// Usa la base de la config
$baseUrl = PAYPAL_BASE_URL;

// 1) Token
$ch = curl_init($baseUrl . '/v1/oauth2/token');
curl_setopt_array($ch, [
  CURLOPT_HTTPHEADER => ['Accept: application/json','Accept-Language: en_US'],
  CURLOPT_USERPWD    => PAYPAL_CLIENT_ID . ':' . PAYPAL_SECRET,
  CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
  CURLOPT_RETURNTRANSFER => true,
]);
$tokenData = json_decode(curl_exec($ch), true);
curl_close($ch);

if (empty($tokenData['access_token'])) {
  echo json_encode(['error' => 'No se obtuvo access_token']); exit;
}
$accessToken = $tokenData['access_token'];

// 2) Capturar
$ch = curl_init($baseUrl . "/v2/checkout/orders/{$orderID}/capture");
curl_setopt_array($ch, [
  CURLOPT_HTTPHEADER => ['Content-Type: application/json',"Authorization: Bearer $accessToken"],
  CURLOPT_POST => true,
  CURLOPT_RETURNTRANSFER => true,
]);
$capData = json_decode(curl_exec($ch), true);
curl_close($ch);

if (!empty($capData['status']) && $capData['status'] === 'COMPLETED') {
  echo json_encode(['success' => true]);
} else {
  $msg = $capData['message'] ?? json_encode($capData, JSON_UNESCAPED_UNICODE);
  echo json_encode(['error' => "Falló la captura: $msg"]);
}
