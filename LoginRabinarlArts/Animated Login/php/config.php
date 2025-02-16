<?php
$host = "localhost";
$user = "root"; // Usuario por defecto en XAMPP
$pass = ""; // Normalmente no tiene contraseña en XAMPP
$dbname = "rabinalarts_db";

// Crear conexión
$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
