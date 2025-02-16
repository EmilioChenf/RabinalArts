<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.html");
    exit();
}
echo "<h1>Bienvenido Admin, " . $_SESSION['user_name'] . "!</h1>";
?>
<a href='logout.php'>Cerrar sesión</a>
