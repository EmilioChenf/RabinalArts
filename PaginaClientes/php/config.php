<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }

$host   = "localhost";
$user   = "root";
$pass   = "";
$dbname = "rabinalarts_db";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) { die("Conexión fallida: " . $conn->connect_error); }

// === TUS CREDENCIALES LIVE ===
if (!defined('PAYPAL_CLIENT_ID')) {
  define('PAYPAL_CLIENT_ID', 'ASL-laHLPUATRI3V7_T5BKx0Aayc3BTvqKtRKwMe6HsWrCCkOAjLigt4mntSJacvaduzrvnIoU9usQg2');
}
if (!defined('PAYPAL_SECRET')) {
  define('PAYPAL_SECRET', 'EEO_DwrgBDJ7lo2DPi_tZVVwhrxaJ_vFuv__bDrc_UU9_Bn-FqpzxIa68Ww3RE8PJ9jJu_Rdt7inPxmd');
}

/* IMPORTANTE: si estás en LIVE, esto debe ser false */
if (!defined('PAYPAL_SANDBOX')) {
  define('PAYPAL_SANDBOX', false);
}

/* Usa api-m y deja que dependa del flag */
if (!defined('PAYPAL_BASE_URL')) {
  define(
    'PAYPAL_BASE_URL',
    PAYPAL_SANDBOX ? 'https://api-m.sandbox.paypal.com' : 'https://api-m.paypal.com'
  );
}
