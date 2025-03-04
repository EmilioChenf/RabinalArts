<?php
session_start();
session_unset(); // Eliminar todas las variables de sesión
session_destroy(); // Destruir la sesión

// Redirigir al login en Animated Login
header("Location: http://127.0.0.1/sadasd/RabinalArts/LoginRabinarlArts/Animated%20Login/");
exit();
?>
