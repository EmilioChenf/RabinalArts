<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Verificar si el token es válido y no ha expirado
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE reset_token = ? AND reset_expiration > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        // Actualizar la contraseña y limpiar el token
        $stmt->close();
        $stmt = $conn->prepare("UPDATE usuarios SET password = ?, reset_token = NULL, reset_expiration = NULL WHERE id = ?");
        $stmt->bind_param("si", $new_password, $user_id);
        $stmt->execute();

        echo "Tu contraseña ha sido restablecida correctamente.";
    } else {
        echo "El enlace de restablecimiento no es válido o ha expirado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
</head>
<body>
    <h2>Restablecer Contraseña</h2>
    <form action="reset_password.php" method="POST">
        <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
        <label>Nueva Contraseña:</label>
        <input type="password" name="password" required>
        <button type="submit">Restablecer Contraseña</button>
    </form>
</body>
</html>
