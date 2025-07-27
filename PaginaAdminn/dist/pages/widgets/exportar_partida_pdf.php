<?php
// exportar_partida_pdf.php
require_once __DIR__.'/../../../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

include 'conexion.php';
if (!isset($_GET['partida_id'], $_GET['cliente_id'])) {
    die('Faltan parámetros');
}
$pid = (int)$_GET['partida_id'];
$cid = (int)$_GET['cliente_id'];

// Cabecera partida + nombre cliente
$hdr = mysqli_fetch_assoc(
  mysqli_query($conn,
    "SELECT p.descripcion, p.fecha, u.nombre AS cliente
       FROM partidas_contables_ventas p
       JOIN usuarios u ON u.id=p.cliente_id
      WHERE p.id=$pid"
  )
);

// Detalle y agrupación
$det = mysqli_query($conn,
  "SELECT d.cuenta_id, c.nombre, d.debe, d.haber, c.clasificacion
     FROM partida_detalle_ventas d
     JOIN cuentas_contables c ON c.id=d.cuenta_id
    WHERE d.partida_id=$pid
    ORDER BY c.clasificacion, c.nombre"
);

$sections = ['ACTIVO'=>[], 'PASIVO'=>[], 'PATRIMONIO NETO'=>[]];
while($r = mysqli_fetch_assoc($det)) {
  $sec = strtoupper($r['clasificacion']);
  if (strpos($sec,'PASIVO')!==false) $g='PASIVO';
  elseif (strpos($sec,'PATRIMONIO')!==false) $g='PATRIMONIO NETO';
  else $g='ACTIVO';
  $sections[$g][] = $r;
}

$global = mysqli_fetch_assoc(
  mysqli_query($conn,
    "SELECT SUM(debe) AS total_debe, SUM(haber) AS total_haber
       FROM partida_detalle_ventas WHERE partida_id=$pid"
  )
);

ob_start();
?>
<!doctype html>
<html lang="es">
<head><meta charset="UTF-8">
  <title>Partida #<?= $pid ?></title>
  <style>
    body{font-family:serif;font-size:11pt;margin:0;padding:0;}
    .container{width:100%;padding:10px;}
    h1,p{margin:0;padding:0;}
    .linea{border-top:2px solid #000;margin:5px 0;}
    table{width:100%;border-collapse:collapse;margin:10px 0;font-size:10pt;}
    th,td{border:1px solid #333;padding:4px;}
    th{background:#f0f0f0;}
    .text-end{text-align:right;}
    .section-title{font-weight:bold;margin-top:12px;}
    .summary-table{margin-top:20px;}
    .summary-table th,.summary-table td{border:1px solid #333;padding:6px;}
    .summary-table th{background:#ddd;}
  </style>
</head>
<body>
  <div class="container">
    <p class="linea"></p>
    <h1>PARTIDA #<?= $pid ?> (Cliente: <?= htmlspecialchars($hdr['cliente']) ?>)</h1>
    <p>Fecha: <?= date('Y-m-d',strtotime($hdr['fecha'])) ?></p>
    <p>Descripción: <?= htmlspecialchars($hdr['descripcion']) ?></p>
    <p class="linea"></p>

    <?php foreach($sections as $main=>$rows):
      $td=0; $th=0;
    ?>
      <div class="section-title"><?= $main ?></div>
      <table>
        <thead><tr><th>Cuenta</th><th>Debe</th><th>Haber</th></tr></thead>
        <tbody>
          <?php foreach($rows as $r):
            $td += $r['debe'];
            $th += $r['haber'];
          ?>
            <tr>
              <td><?= htmlspecialchars($r['nombre']) ?></td>
              <td class="text-end"><?= number_format($r['debe'],2) ?></td>
              <td class="text-end"><?= number_format($r['haber'],2) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <th class="text-end">Total <?= $main ?>:</th>
            <th class="text-end"><?= number_format($td,2) ?></th>
            <th class="text-end"><?= number_format($th,2) ?></th>
          </tr>
        </tfoot>
      </table>
    <?php endforeach; ?>

    <div class="section-title">RESUMEN GLOBAL</div>
    <table class="summary-table">
      <thead><tr><th>Descripción</th><th>Total Debe</th><th>Total Haber</th></tr></thead>
      <tbody>
        <tr>
          <td><?= htmlspecialchars($hdr['descripcion']) ?></td>
          <td class="text-end"><?= number_format($global['total_debe'],2) ?></td>
          <td class="text-end"><?= number_format($global['total_haber'],2) ?></td>
        </tr>
      </tbody>
    </table>

  </div>
</body>
</html>
<?php
$html = ob_get_clean();
$options = new Options(); $options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4','portrait');
$dompdf->render();
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="partida_'.$pid.'.pdf"');
echo $dompdf->output();
exit;
