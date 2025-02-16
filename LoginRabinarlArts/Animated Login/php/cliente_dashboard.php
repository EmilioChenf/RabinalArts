<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'cliente') {
    header("Location: index.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Consultar si el usuario ya tiene los datos completos
$stmt = $conn->prepare("SELECT telefono, direccion FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($telefono, $direccion);
$stmt->fetch();
$stmt->close();

// Verificar si los datos están vacíos
$datos_incompletos = empty($telefono) || empty($direccion);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Cliente</title>
</head>
<body>
    <h1>Bienvenido Cliente, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>

    <?php if ($datos_incompletos): ?>
        <h2>Completa tu información</h2>
        <form action="completar_datos.php" method="POST">
            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" required>

            <label for="direccion">Dirección:</label>
            <input type="text" name="direccion" required>

            <button type="submit">Guardar</button>
        </form>
    <?php else: ?>
        <p>Tus datos están completos.</p>
    <?php endif; ?>

    <a href="logout.php">Cerrar sesión</a>
</body>
</html>
