<?php
// exportar_planilla_pdf.php
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once 'conexion.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// ====== CONFIG FORMATO (solo presentación) ======
$empresa_nombre = 'RabinalArts';
$logo_base64 = 'PEGA_AQUI_TU_LOGO_BASE64'; // sin 'data:image/png;base64,'
// ================================================

// 1) Recuperar todas las planillas
$sql    = "SELECT * FROM planilla ORDER BY fecha_registro ASC";
$result = mysqli_query($conn, $sql);
if (!$result || mysqli_num_rows($result) === 0) {
    die("No hay planillas disponibles.");
}

// 2) Procesar datos y almacenar en un array (exactamente como antes)
$filas  = [];
$counter = 1;
while ($data = mysqli_fetch_assoc($result)) {
    $sueldoNeto      = $data['sueldo_base'] - $data['anticipo'];         // sueldo ordinario menos anticipo
    $valorHora       = $data['sueldo_base'] / 240;                        // valor hora (240 h/mes)
    $totalHorasExtra = $valorHora * $data['horas_extras'];                // total horas extra
    $totalDevengado  = $sueldoNeto + $totalHorasExtra + $data['comisiones'] + $data['bonificacion']; // total devengado

    $filas[] = [
        'no'               => $counter,
        'nombre'           => htmlspecialchars($data['nombre']),
        'puesto'           => htmlspecialchars($data['puesto']),
        'sueldo'           => number_format($sueldoNeto, 2),
        'horas_extras'     => intval($data['horas_extras']),
        'valor_extra'      => number_format($totalHorasExtra, 2),
        'comisiones'       => number_format($data['comisiones'], 2),
        'bonificacion'     => number_format($data['bonificacion'], 2),
        'total_devengado'  => number_format($totalDevengado, 2),
        'isss'             => number_format($data['isss'], 2),
        'anticipo'         => number_format($data['anticipo'], 2),
        'descuentos_jud'   => number_format($data['descuentos_judiciales'], 2),
        'prestaciones'     => '0.00',
        'isr'              => number_format($data['isr'], 2),
        'total_descuentos' => number_format($data['total_descuentos'], 2),
        'liquido'          => number_format($data['liquido_recibir'], 2),
    ];
    $counter++;
}

// 3) Generar HTML con formato unificado (solo presentación)
ob_start();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Planilla Mensual - Listado Completo</title>
  <style>
    @page { margin: 1cm; }
    body {
      font-family: 'Times New Roman', Times, serif;
      font-size: 12px;  /* cuerpo 12 */
      margin: 0; padding: 10px;
    }

    /* Encabezado corporativo en dos columnas */
    .hdr { width: 100%; border-collapse: collapse; }
    .hdr td { vertical-align: top; }
    .hdr .left { text-align: center; padding: 0 8px; }
    .hdr .right { text-align: right; width: 140px; }
    .hdr h1 {
      margin: 0;
      font-size: 14px;   /* título 14 */
      font-weight: bold; /* negrita */
      text-transform: uppercase;
    }
    .divider { border-bottom: 2px solid #000; margin: 6px 0 10px 0; }
    .logo { width: 110px; height: auto; }

    /* Tabla de planilla */
    table.tbl {
      border: 2px solid #000;
      border-collapse: collapse;
      width: 100%;
      margin-top: 10px;
      table-layout: fixed;
    }
    .tbl th, .tbl td {
      border: 1px solid #000;
      padding: 4px 6px;
      vertical-align: middle;
      text-align: center;
      word-wrap: break-word;
    }
    .tbl thead th {
      background-color: #f2f2f2;
      font-weight: bold;
    }
    .num { text-align: right !important; }  /* números a la derecha */
    tfoot td { font-weight: bold; }

    /* Pie de página */
    .footer {
      position: fixed;
      bottom: 8px; left: 10px; right: 10px;
      font-size: 12px;
      text-align: left;
    }
  </style>
</head>
<body>

  <!-- Encabezado -->
  <table class="hdr">
    <tr>
      <td class="left">
        <h1>PLANILLA MENSUAL - LISTADO COMPLETO</h1>
        <div><?= $empresa_nombre ?></div>
      </td>
      <td class="right">
        <?php if (!empty($logo_base64)): ?>
          <img class="logo" src="data:image/png;base64,<?= $logo_base64 ?>" alt="Logo">
        <?php endif; ?>
      </td>
    </tr>
  </table>
  <div class="divider"></div>

  <!-- Tabla -->
  <table class="tbl">
    <thead>
      <tr>
        <th style="width:40px;">No.</th>
        <th style="width:160px;">Nombres</th>
        <th style="width:120px;">Cargo</th>
        <th style="width:90px;">Sueldo Ordinario</th>
        <th style="width:70px;">Horas Extras<br>Número</th>
        <th style="width:90px;">Horas Extras<br>Valor</th>
        <th style="width:90px;">Comisiones</th>
        <th style="width:90px;">Bonificación</th>
        <th style="width:100px;">Total Devengado</th>
        <th style="width:80px;">IGSS</th>
        <th style="width:80px;">Antic.</th>
        <th style="width:80px;">Judic.</th>
        <th style="width:80px;">Prest.</th>
        <th style="width:80px;">ISR</th>
        <th style="width:110px;">Total Descuentos</th>
        <th style="width:110px;">Total Líquido</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($filas as $f): ?>
        <tr>
          <td><?= $f['no'] ?></td>
          <td><?= $f['nombre'] ?></td>
          <td><?= $f['puesto'] ?></td>
          <td class="num">Q <?= $f['sueldo'] ?></td>
          <td class="num"><?= $f['horas_extras'] ?></td>
          <td class="num">Q <?= $f['valor_extra'] ?></td>
          <td class="num">Q <?= $f['comisiones'] ?></td>
          <td class="num">Q <?= $f['bonificacion'] ?></td>
          <td class="num">Q <?= $f['total_devengado'] ?></td>
          <td class="num">Q <?= $f['isss'] ?></td>
          <td class="num">Q <?= $f['anticipo'] ?></td>
          <td class="num">Q <?= $f['descuentos_jud'] ?></td>
          <td class="num">Q <?= $f['prestaciones'] ?></td>
          <td class="num">Q <?= $f['isr'] ?></td>
          <td class="num">Q <?= $f['total_descuentos'] ?></td>
          <td class="num"><strong>Q <?= $f['liquido'] ?></strong></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="16" style="text-align:left;">TOTALES</td>
      </tr>
    </tfoot>
  </table>

  <p style="text-align:right; margin-top:10px;">
    Generado: <?= date('d/m/Y H:i') ?>
  </p>

  <!-- Pie unificado -->
  <div class="footer">
    <?= $empresa_nombre ?> — Fecha de emisión: <?= date('d/m/Y') ?>
  </div>

</body>
</html>
<?php
$html = ob_get_clean();

// 5) Renderizar con Dompdf (misma configuración y orientación)
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('letter', 'landscape');
$dompdf->render();

// 6) Enviar al navegador
header("Content-Type: application/pdf");
header("Content-Disposition: inline; filename=planilla_listado_completo.pdf");
echo $dompdf->output();
exit;
