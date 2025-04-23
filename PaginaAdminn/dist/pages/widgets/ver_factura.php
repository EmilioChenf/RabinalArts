<?php
session_start();
include 'conexion.php';

// Eliminar validación de sesión para pruebas
$id_usuario = 1; // ← Asegúrate de que este ID exista en la tabla 'usuarios'

// Inicializar el detalle de factura si no existe
if (!isset($_SESSION['factura_detalle'])) {
  $_SESSION['factura_detalle'] = [];
}

// Agregar producto al detalle
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['producto_id'], $_POST['cantidad'])) {
  $producto_id = (int) $_POST['producto_id'];
  $cantidad = (int) $_POST['cantidad'];

  if ($producto_id > 0 && $cantidad > 0) {
    $query = $conn->prepare("SELECT nombre, precio FROM productos WHERE id = ?");
    $query->bind_param("i", $producto_id);
    $query->execute();
    $result = $query->get_result();

    if ($row = $result->fetch_assoc()) {
      $nombre = $row['nombre'];
      $precio = (float) $row['precio'];
      $subtotal = $precio * $cantidad;

      $_SESSION['factura_detalle'][] = [
        'producto_id' => $producto_id,
        'nombre' => $nombre,
        'cantidad' => $cantidad,
        'precio' => $precio,
        'subtotal' => $subtotal
      ];
    }
  }
}

$productos = mysqli_query($conn, "SELECT id, nombre FROM productos");

$total_general = 0;
foreach ($_SESSION['factura_detalle'] as $item) {
  $total_general += $item['subtotal'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalle de Factura</title>
  <link rel="stylesheet" href="../../../dist/css/adminlte.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
@media print {
  .btn, nav, footer, .sidebar-wrapper {
    display: none !important;
  }
  body {
    margin: 0;
    padding: 0;
    font-size: 14pt;
  }
}
</style>

</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">
  <main class="app-main p-4">
    <div class="container">
      <h2>🧾 Detalle de Factura #<?= $info['id'] ?></h2>
      <p><strong>Cliente:</strong> <?= htmlspecialchars($info['cliente']) ?></p>
      <p><strong>Fecha:</strong> <?= $info['fecha'] ?></p>
      <p><strong>Total:</strong> Q<?= number_format($info['total'], 2) ?></p>

      <hr>

      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($fila = mysqli_fetch_assoc($detalle)): ?>
          <tr>
            <td><?= htmlspecialchars($fila['nombre']) ?></td>
            <td><?= $fila['cantidad'] ?></td>
            <td>Q<?= number_format($fila['precio_unitario'], 2) ?></td>
            <td>Q<?= number_format($fila['total'], 2) ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <a href="historial_facturas.php" class="btn btn-secondary mt-3">🔙 Volver</a>
      <a href="historial_facturas.php" class="btn btn-secondary">🔙 Volver</a>
<button onclick="window.print()" class="btn btn-success">🖨️ Imprimir</button>

    </div>
  </main>
</div>
</body>
</html>
