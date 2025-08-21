<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

require __DIR__ . '/config.php';
require __DIR__ . '/paypal_helpers.php';

// Cuerpo desde el fetch del frontend
$input = json_decode(file_get_contents('php://input'), true);
$carrito = $input['carrito'] ?? [];

if (!$carrito || !is_array($carrito)) {
  echo json_encode(['error' => 'Carrito vacío']);
  exit;
}

// Calcular total real
$total = 0;
foreach ($carrito as $p) {
  $cantidad = isset($p['cantidad']) ? (int)$p['cantidad'] : 1;
  $precio   = isset($p['precio']) ? (float)$p['precio'] : 0;
  $total   += $cantidad * $precio;
}

// Obtener token (¡ojo al nombre de la función!)
$token = obtenerAccessTokenPayPal();
if (!$token) {
  echo json_encode(['error' => 'No se pudo obtener access_token']);
  exit;
}

// Crear orden en PayPal con el total calculado
$orden = crearOrdenPayPal($token, $total);

if (!empty($orden['id'])) {
  echo json_encode(['orderID' => $orden['id']]);
} else {
  // Devuelve el error crudo que mandó PayPal para depurar
  echo json_encode(['error' => $orden]);
}
