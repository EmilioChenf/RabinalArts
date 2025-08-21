<?php
require_once __DIR__ . '/config.php';

/** Obtiene un access token válido de PayPal (LIVE o SANDBOX según config) */
function obtenerAccessTokenPayPal() {
    $ch = curl_init(PAYPAL_BASE_URL . '/v1/oauth2/token');
    curl_setopt($ch, CURLOPT_USERPWD, PAYPAL_CLIENT_ID . ':' . PAYPAL_SECRET);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Opcional (si tu PHP/cURL no tiene CA bundle actualizado):
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    // curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . '/cacert.pem');
    $res = json_decode(curl_exec($ch), true);
    curl_close($ch);
    return $res['access_token'] ?? '';
}

function crearOrdenPayPal($token, $total) {
    $invoiceId = 'RA-' . date('YmdHis') . '-' . bin2hex(random_bytes(3));

    $body = [
        'intent' => 'CAPTURE',
        'purchase_units' => [[
            'invoice_id' => $invoiceId,
            'amount' => [
                'currency_code' => 'USD',
                'value' => number_format($total, 2, '.', '')
            ]
        ]]
    ];

    $ch = curl_init(PAYPAL_BASE_URL . '/v2/checkout/orders');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        "Authorization: Bearer $token"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = json_decode(curl_exec($ch), true);
    curl_close($ch);
    return $res;
}




