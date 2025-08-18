<?php
// php/contact_mail.php
declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['success' => false, 'message' => 'Método no permitido']);
  exit;
}

// ===== Cargar PHPMailer (Composer) =====
$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) {
  echo json_encode(['success' => false, 'message' => 'PHPMailer no está instalado (composer).']);
  exit;
}
require $autoload;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ===== Cargar config =====
$configFile = __DIR__ . '/mailer_config.php';
if (!file_exists($configFile)) {
  echo json_encode(['success' => false, 'message' => 'Falta archivo de configuración SMTP.']);
  exit;
}
$config = require $configFile;

// ===== Sanitizar/validar =====
$nombre  = isset($_POST['cliente']) ? trim($_POST['cliente']) : '';
$correo  = isset($_POST['correo'])  ? trim($_POST['correo'])  : '';
$celular = isset($_POST['celular']) ? trim($_POST['celular']) : '';
$mensaje = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : '';

if ($nombre === '' || $correo === '' || $celular === '' || $mensaje === '') {
  echo json_encode(['success' => false, 'message' => 'Completa todos los campos.']);
  exit;
}
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(['success' => false, 'message' => 'El correo no es válido.']);
  exit;
}

// ===== Construir correo =====
$subject = 'Nuevo contacto desde RabinalArts';
$bodyText = "Has recibido un nuevo mensaje de contacto:\n\n"
          . "Nombre:  {$nombre}\n"
          . "Correo:  {$correo}\n"
          . "Celular: {$celular}\n\n"
          . "Mensaje:\n{$mensaje}\n\n"
          . "Enviado el: " . date('Y-m-d H:i:s');

$bodyHtml = '<h2>Nuevo contacto desde RabinalArts</h2>'
          . '<p><strong>Nombre:</strong> '  . htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8')  . '</p>'
          . '<p><strong>Correo:</strong> '  . htmlspecialchars($correo, ENT_QUOTES, 'UTF-8')  . '</p>'
          . '<p><strong>Celular:</strong> ' . htmlspecialchars($celular, ENT_QUOTES, 'UTF-8') . '</p>'
          . '<p><strong>Mensaje:</strong><br>' . nl2br(htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8')) . '</p>'
          . '<hr><small>Enviado el: ' . date('Y-m-d H:i:s') . '</small>';

try {
  $mail = new PHPMailer(true);
  $mail->CharSet  = 'UTF-8';
  $mail->Encoding = 'base64';

  // SMTP
  $mail->isSMTP();
  $mail->Host       = $config['host'];
  $mail->SMTPAuth   = true;
  $mail->Username   = $config['username'];
  $mail->Password   = $config['password'];
  $mail->SMTPSecure = $config['secure']; // 'tls' o 'ssl'
  $mail->Port       = (int)$config['port'];

  // Remitente y destinatario
  $mail->setFrom($config['from_email'], $config['from_name']);
  $mail->addAddress($config['to_email'], $config['to_name']);
  $mail->addReplyTo($correo, $nombre); // responderá al usuario

  // Contenido
  $mail->isHTML(true);
  $mail->Subject = $subject;
  $mail->Body    = $bodyHtml;
  $mail->AltBody = $bodyText;

  $mail->send();
  echo json_encode(['success' => true, 'message' => 'Mensaje enviado correctamente.']);
} catch (Exception $e) {
  // No exponemos ErrorInfo completo al usuario final:
  echo json_encode(['success' => false, 'message' => 'No se pudo enviar el correo por SMTP. Revisa credenciales/puerto/seguridad.']);
}
