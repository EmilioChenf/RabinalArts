<?php
// exportar_compra_interna_pdf.php

require_once __DIR__ . '/../../../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

include 'conexion.php';

// 1) Validar parámetros
if (!isset($_GET['partida_id'], $_GET['compra_id'])) {
    die('Faltan parámetros: partida_id y compra_id');
}

$pid = (int) $_GET['partida_id'];
$cid = (int) $_GET['compra_id'];

// 2) Cabecera de la partida (usamos created_at)
$sql_hdr = "
    SELECT
      p.descripcion,
      p.created_at AS fecha
    FROM partidas_contables_compras p
    WHERE p.id = $pid
";
$res_hdr = mysqli_query($conn, $sql_hdr);
if (!$res_hdr) {
    die("Error al obtener cabecera de partida: " . mysqli_error($conn));
}
if (mysqli_num_rows($res_hdr) === 0) {
    die("No existe la partida con ID $pid");
}
$hdr = mysqli_fetch_assoc($res_hdr);

// 3) Detalle y agrupación (tabla real)
$sql_det = "
    SELECT
      d.cuenta_id,
      c.nombre,
      d.debe,
      d.haber,
      c.clasificacion
    FROM partidas_contables_compras_detalle d
    JOIN cuentas_contables c ON c.id = d.cuenta_id
    WHERE d.partida_id = $pid
    ORDER BY c.clasificacion, c.nombre
";
$res_det = mysqli_query($conn, $sql_det);
if (!$res_det) {
    die("Error al obtener detalle de partida: " . mysqli_error($conn));
}

// 4) Organizar en secciones
$sections = [
  'ACTIVO'          => [],
  'PASIVO'          => [],
  'PATRIMONIO NETO' => []
];
while ($r = mysqli_fetch_assoc($res_det)) {
    $sec = strtoupper($r['clasificacion']);
    if (strpos($sec, 'PASIVO') !== false) {
        $g = 'PASIVO';
    } elseif (strpos($sec, 'PATRIMONIO') !== false) {
        $g = 'PATRIMONIO NETO';
    } else {
        $g = 'ACTIVO';
    }
    $sections[$g][] = $r;
}

// 5) Totales globales
$sql_glob = "
    SELECT
      SUM(debe)  AS total_debe,
      SUM(haber) AS total_haber
    FROM partidas_contables_compras_detalle
    WHERE partida_id = $pid
";
$res_glob = mysqli_query($conn, $sql_glob);
if (!$res_glob) {
    die("Error al obtener totales globales: " . mysqli_error($conn));
}
$global = mysqli_fetch_assoc($res_glob);

// 6) Generar HTML para PDF
ob_start();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Partida #<?= $pid ?> (Compra Interna: #<?= $cid ?>)</title>
  <style>
    body { font-family: serif; font-size: 11pt; margin: 0; padding: 0; }
    .container { width: 100%; padding: 10px; }
    h1, p { margin: 0; padding: 0; }
    .linea { border-top: 2px solid #000; margin: 5px 0; }
    table { width: 100%; border-collapse: collapse; margin: 10px 0; font-size: 10pt; }
    th, td { border: 1px solid #333; padding: 4px; }
    th { background: #f0f0f0; }
    .text-end { text-align: right; }
    .section-title { font-weight: bold; margin-top: 12px; }
    .summary-table { margin-top: 20px; }
    .summary-table th, .summary-table td { border: 1px solid #333; padding: 6px; }
    .summary-table th { background: #ddd; }
  </style>
</head>
<body>
  <div class="container">
    <p class="linea"></p>
    <h1>PARTIDA #<?= $pid ?> (Compra Interna: #<?= $cid ?>)</h1>
    <p>Fecha: <?= date('Y-m-d', strtotime($hdr['fecha'])) ?></p>
    <p>Descripción: <?= htmlspecialchars($hdr['descripcion']) ?></p>
    <p class="linea"></p>

    <?php foreach ($sections as $main => $rows):
      $td = 0; $th = 0;
    ?>
      <div class="section-title"><?= $main ?></div>
      <table>
        <thead>
          <tr><th>Cuenta</th><th>Debe</th><th>Haber</th></tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r):
            $td += $r['debe'];
            $th += $r['haber'];
          ?>
            <tr>
              <td><?= htmlspecialchars($r['nombre']) ?></td>
              <td class="text-end"><?= number_format($r['debe'], 2) ?></td>
              <td class="text-end"><?= number_format($r['haber'], 2) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <th class="text-end">Total <?= $main ?>:</th>
            <th class="text-end"><?= number_format($td, 2) ?></th>
            <th class="text-end"><?= number_format($th, 2) ?></th>
          </tr>
        </tfoot>
      </table>
    <?php endforeach; ?>

    <div class="section-title">RESUMEN GLOBAL</div>
    <table class="summary-table">
      <thead>
        <tr><th>Descripción</th><th>Total Debe</th><th>Total Haber</th></tr>
      </thead>
      <tbody>
        <tr>
          <td><?= htmlspecialchars($hdr['descripcion']) ?></td>
          <td class="text-end"><?= number_format($global['total_debe'], 2) ?></td>
          <td class="text-end"><?= number_format($global['total_haber'], 2) ?></td>
        </tr>
      </tbody>
    </table>
  </div>
</body>
</html>
<?php
$html = ob_get_clean();

// 7) Renderizar el PDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="partida_compras_'.$pid.'.pdf"');
echo $dompdf->output();
exit;
?>
