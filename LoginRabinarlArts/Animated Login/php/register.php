<?php
include 'config.php';

header('Content-Type: application/json'); // Establecer la respuesta como JSON

$response = ["success" => false];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encripta la contraseña
    $rol = 'cliente'; // Asignamos el rol por defecto

    // Verifica si el correo ya está registrado
    $checkEmail = $conn->prepare("SELECT id FROM usuarios WHERE correo = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        $response["message"] = "El correo ya está registrado.";
    } else {
        // Inserta el usuario en la base de datos
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, password, rol, fecha_registro) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $name, $email, $password, $rol);
        
        if ($stmt->execute()) {
            $response["success"] = true;
            $response["message"] = "¡Registro exitoso! Ahora puedes iniciar sesión.";
        } else {
            $response["message"] = "Error en el registro.";
        }
    }

    $checkEmail->close();
    $stmt->close();
    $conn->close();
}

echo json_encode($response); // Enviar la respuesta en formato JSON
?>
