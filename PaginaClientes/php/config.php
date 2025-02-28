<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "rabinalarts_db";

// Crear conexión
$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
