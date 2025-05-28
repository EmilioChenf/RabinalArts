<?php
// ver_planilla.php
session_start();
include 'conexion.php';

$id = intval($_GET['id'] ?? 0);
if($id <= 0) {
  die("Planilla inválida");
}

$query = "SELECT * FROM planilla WHERE id = $id";
$res = mysqli_query($conn, $query);
if(!$data = mysqli_fetch_assoc($res)){
  die("Planilla no encontrada");
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Ver Planilla #<?= $id ?> | AdminLTE</title>
  <link rel="stylesheet" href="../../../dist/css/adminlte.css">
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<main class="app-main p-4">
  <div class="container">
    <h3 class="mb-4">Planilla de: <?= htmlspecialchars($data['nombre']) ?></h3>
    <table class="table table-bordered">
      <tr><th>Nombre</th><td><?= htmlspecialchars($data['nombre']) ?></td></tr>
      <tr><th>Puesto</th><td><?= htmlspecialchars($data['puesto']) ?></td></tr>
      <tr><th>Sueldo Base</th><td>Q <?= number_format($data['sueldo_base'],2) ?></td></tr>
      <tr><th>Horas Extras</th><td><?= $data['horas_extras'] ?></td></tr>
      <tr><th>Comisiones</th><td>Q <?= number_format($data['comisiones'],2) ?></td></tr>
      <tr><th>Bonificación</th><td>Q <?= number_format($data['bonificacion'],2) ?></td></tr>
      <tr><th>Total Ingresos</th><td>Q <?= number_format($data['total_ingresos'],2) ?></td></tr>
      <tr><th>Anticipos</th><td>Q <?= number_format($data['anticipos'],2) ?></td></tr>
      <tr><th>Descuentos Judiciales</th><td>Q <?= number_format($data['descuentos_judiciales'],2) ?></td></tr>
      <tr><th>Otros Descuentos</th><td>Q <?= number_format($data['otros_descuentos'],2) ?></td></tr>
      <tr><th>Total Descuentos</th><td>Q <?= number_format($data['total_descuentos'],2) ?></td></tr>
      <tr><th>Líquido a Recibir</th><td><strong>Q <?= number_format($data['liquido_recibir'],2) ?></strong></td></tr>
      <tr><th>Fecha de Registro</th><td><?= htmlspecialchars($data['fecha_registro']) ?></td></tr>
    </table>
    <a href="exportar_planilla_pdf.php?id=<?= $id ?>" target="_blank" class="btn btn-danger">
      <i class="bi bi-file-earmark-pdf"></i> Descargar PDF
    </a>
    <a href="planilla.php" class="btn btn-secondary">Volver al listado</a>
  </div>
</main>
</body>
</html>
