<?php
include 'config.php';

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
        echo "El correo ya está registrado.";
    } else {
        // Inserta el usuario en la base de datos
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, password, rol, fecha_registro) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $name, $email, $password, $rol);
        
        if ($stmt->execute()) {
            echo "Registro exitoso. Ahora puedes iniciar sesión.";
        } else {
            echo "Error en el registro.";
        }
    }

    $checkEmail->close();
    $stmt->close();
    $conn->close();
}
?>
