<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
error_reporting(E_ALL);
ini_set('display_errors', 1);
require __DIR__ . '/vendor/autoload.php';

echo "<h1>Página de recuperación de contraseña</h1>";

include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $token = bin2hex(random_bytes(50));
        $stmt->close();

        $stmt = $conn->prepare("UPDATE usuarios SET reset_token = ?, reset_expiration = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE correo = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        $reset_link = "http://127.0.0.1/sadasd/RabinalArts/LoginRabinarlArts/Animated%20Login/php/reset_password.php?token=" . $token;

        // Configurar PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tuemail@gmail.com';
            $mail->Password = 'tucontraseña'; // Usa una "Contraseña de Aplicaciones" si tienes verificación en dos pasos
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Configuración del correo
            $mail->setFrom('tuemail@gmail.com', 'RabinalArts');
            $mail->addAddress($email);
            $mail->Subject = "Restablecimiento de contraseña";
            $mail->Body = "Haz clic en el siguiente enlace para restablecer tu contraseña: " . $reset_link;

            $mail->send();
            echo "Revisa tu correo para restablecer tu contraseña.";
        } catch (Exception $e) {
            echo "Error al enviar el correo: {$mail->ErrorInfo}";
        }
    } else {
        echo "El correo no está registrado.";
    }
}
?>
