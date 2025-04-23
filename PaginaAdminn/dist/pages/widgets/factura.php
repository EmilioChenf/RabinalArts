<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../../LoginRabinarlArts/Animated Login/");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generar Factura</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h2>Generar Encabezado de Factura</h2>

<form id="form-buscar-cliente">
    <label for="cliente_id">ID Cliente:</label>
    <input type="number" id="cliente_id" name="cliente_id" required>
    <button type="submit">Buscar Cliente</button>
</form>

<div id="info-cliente" style="margin-top: 20px;">
    <p><strong>Nombre:</strong> <span id="nombre"></span></p>
    <p><strong>Teléfono:</strong> <span id="telefono"></span></p>
    <p><strong>Dirección:</strong> <span id="direccion"></span></p>
</div>

<form id="form-generar-factura" method="POST" action="crear_factura.php">
    <input type="hidden" name="cliente_id" id="cliente_id_hidden">
    <button type="submit">Generar Factura</button>
</form>

<script>
document.getElementById("form-buscar-cliente").addEventListener("submit", function(e) {
    e.preventDefault();
    const id = document.getElementById("cliente_id").value;

    fetch("buscar_cliente.php?id=" + id)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("nombre").textContent = data.nombre;
                document.getElementById("telefono").textContent = data.telefono;
                document.getElementById("direccion").textContent = data.direccion;
                document.getElementById("cliente_id_hidden").value = id;
            } else {
                alert("Cliente no encontrado");
            }
        });
});
</script>

</body>
</html>
