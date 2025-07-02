<?php
// Carga config.php para tener las constantes y $conn
require_once __DIR__ . '/config.php';

/**
 * Obtiene un access token válido de PayPal
 */
function obtenerAccessTokenPayPal() {
    $ch = curl_init('https://api-m.sandbox.paypal.com/v1/oauth2/token');
    curl_setopt($ch, CURLOPT_USERPWD, PAYPAL_CLIENT_ID . ':' . PAYPAL_SECRET);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = json_decode(curl_exec($ch), true);
    curl_close($ch);
    return $res['access_token'] ?? '';
}

/**
 * Crea una orden en PayPal con el total dado
 */
function crearOrdenPayPal($token, $total) {
    $body = [
        'intent' => 'CAPTURE',
        'purchase_units' => [[
            'amount' => [
                'currency_code' => 'USD',
                'value'         => number_format($total, 2, '.', '')
            ]
        ]]
    ];
    $ch = curl_init('https://api-m.sandbox.paypal.com/v2/checkout/orders');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $token"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = json_decode(curl_exec($ch), true);
    curl_close($ch);
    return $res;
}
