<?php
require __DIR__ . '/../../vendor/autoload.php';// Cargar la librería de Google
include 'config.php';
session_start();

$clientID = "681178776325-g3m0qma3l9flkfoep8knfk50mt9o8mus.apps.googleusercontent.com";
$clientSecret = "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";
$redirectUri = "http://127.0.0.1/sadasd/RabinalArts/LoginRabinarlArts/Animated%20Login/php/google_callback.php";

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

if (!isset($_GET['code'])) {
    // Redirige a Google para autenticar
    header("Location: " . $client->createAuthUrl());
    exit();
} else {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($token["error"])) {
        $client->setAccessToken($token['access_token']);

        $oauth2 = new Google_Service_Oauth2($client);
        $userInfo = $oauth2->userinfo->get();

        $email = $userInfo->email;
        $name = $userInfo->name;

        // Verifica si el usuario ya existe en la base de datos
        $stmt = $conn->prepare("SELECT id, nombre, rol FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $db_name, $rol);
        $stmt->fetch();

        if ($stmt->num_rows > 0) {
            // Usuario ya existe, inicia sesión
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $db_name;
            $_SESSION['user_role'] = $rol;

            if ($rol === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: cliente_dashboard.php");
            }
            exit();
        } else {
            // Usuario no existe, lo registramos
            $default_password = password_hash("google_auth_user", PASSWORD_DEFAULT);
            $default_role = "cliente"; // Por defecto es cliente

            $insert_stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, password, rol, fecha_registro) VALUES (?, ?, ?, ?, NOW())");
            $insert_stmt->bind_param("ssss", $name, $email, $default_password, $default_role);
            if ($insert_stmt->execute()) {
                $_SESSION['user_id'] = $insert_stmt->insert_id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_role'] = $default_role;

                header("Location: cliente_dashboard.php");
                exit();
            } else {
                echo "Error en el registro.";
            }
        }
        $stmt->close();
        $conn->close();
    } else {
        echo "Error en la autenticación.";
    }
}
?>
