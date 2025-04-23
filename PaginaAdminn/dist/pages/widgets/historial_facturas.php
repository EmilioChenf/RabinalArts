<?php
session_start();
include 'conexion.php';

$facturas = mysqli_query($conn, "
    SELECT v.id, u.nombre AS cliente, v.fecha, v.total
    FROM ventas v
    INNER JOIN usuarios u ON v.cliente_id = u.id
    ORDER BY v.fecha DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de Facturas</title>
  <link rel="stylesheet" href="../../../dist/css/adminlte.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">
  <main class="app-main p-4">
    <div class="container">
      <h2>📜 Historial de Facturas</h2>
      <table class="table table-bordered mt-3">
        <thead>
          <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Fecha</th>
            <th>Total</th>
            <th>Ver Detalle</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($factura = mysqli_fetch_assoc($facturas)): ?>
          <tr>
            <td><?= $factura['id'] ?></td>
            <td><?= htmlspecialchars($factura['cliente']) ?></td>
            <td><?= $factura['fecha'] ?></td>
            <td>Q<?= number_format($factura['total'], 2) ?></td>
            <td>
              <a href="ver_factura.php?id=<?= $factura['id'] ?>" class="btn btn-sm btn-primary">
                Ver
              </a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>
</body>
</html>
