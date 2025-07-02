<?php
// Iniciar sesión si no está activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Parámetros de conexión a la base de datos
$host   = "localhost";
$user   = "root";
$pass   = "";
$dbname = "rabinalarts_db";

// Conexión MySQLi
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Definición de constantes de PayPal (solo si no existen)
if (!defined('PAYPAL_CLIENT_ID')) {
    define('PAYPAL_CLIENT_ID', 'AdBfcxD3V26g0iL6zHg-5EQYrpxqHxmBzxU4UmWTwNL7oeUuAP0LNSby5Neclf8kqJILX6ffzdqC1591');
}

if (!defined('PAYPAL_SECRET')) {
    define('PAYPAL_SECRET', 'EHZWCESf9TXAKC-RerBXOXv3kWmtwmkDbtlzRxYhdMAVwnvwaa5-gBFYMYaB4K9Fo1ou9VKyOyhTRb2n');
}

if (!defined('PAYPAL_SANDBOX')) {
    define('PAYPAL_SANDBOX', true);
}

// URL base según entorno
if (!defined('PAYPAL_BASE_URL')) {
    if (PAYPAL_SANDBOX) {
        define('PAYPAL_BASE_URL', 'https://api.sandbox.paypal.com');
    } else {
        define('PAYPAL_BASE_URL', 'https://api.paypal.com');
    }
}
