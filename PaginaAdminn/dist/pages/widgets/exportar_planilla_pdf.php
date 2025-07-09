<?php
// exportar_planilla_pdf.php
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once 'conexion.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// 1) Determinar planilla a exportar: por ID o la última
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id > 0) {
    $sql = "SELECT * FROM planilla WHERE id = $id";
} else {
    $sql = "SELECT * FROM planilla ORDER BY fecha_registro DESC LIMIT 1";
}
$result = mysqli_query($conn, $sql);
if (!$result || mysqli_num_rows($result) === 0) {
    die("Planilla no encontrada.");
}
$data = mysqli_fetch_assoc($result);

// 2) Procesar cálculo de campos
$sueldoNeto      = $data['sueldo_base'] - $data['anticipo'];
$valorHora       = $data['sueldo_base'] / 240;
$totalHorasExtra = $valorHora * $data['horas_extras'];
$totalDevengado  = $sueldoNeto + $totalHorasExtra + $data['comisiones'] + $data['bonificacion'];

// 3) Pasar a un array para la tabla
$filas = [[
    'no'               => 1,
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
]];

// 4) Generar HTML
$html = "
<!doctype html>
<html lang='es'>
<head>
  <meta charset='utf-8'>
  <style>
    body { font-family: Arial, sans-serif; font-size: 10px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #000; padding: 4px; text-align: center; }
    th { background-color: #f2f2f2; }
    h2 { text-align: center; margin-bottom: 10px; }
  </style>
</head>
<body>
  <h2>PLANILLA MENSUAL</h2>
  <table>
    <thead>
      <tr>
        <th>No.</th>
        <th>Nombres</th>
        <th>Cargo</th>
        <th>Sueldo Ordinario</th>
        <th>Horas Extras<br>Número</th>
        <th>Horas Extras<br>Valor</th>
        <th>Comisiones</th>
        <th>Bonificación</th>
        <th>Total Devengado</th>
        <th>IGSS</th>
        <th>Antic.</th>
        <th>Judic.</th>
        <th>Prest.</th>
        <th>ISR</th>
        <th>Total Descuentos</th>
        <th>Total Líquido</th>
      </tr>
    </thead>
    <tbody>
";

foreach ($filas as $f) {
    $html .= "
      <tr>
        <td>{$f['no']}</td>
        <td>{$f['nombre']}</td>
        <td>{$f['puesto']}</td>
        <td>Q {$f['sueldo']}</td>
        <td>{$f['horas_extras']}</td>
        <td>Q {$f['valor_extra']}</td>
        <td>Q {$f['comisiones']}</td>
        <td>Q {$f['bonificacion']}</td>
        <td>Q {$f['total_devengado']}</td>
        <td>Q {$f['isss']}</td>
        <td>Q {$f['anticipo']}</td>
        <td>Q {$f['descuentos_jud']}</td>
        <td>Q {$f['prestaciones']}</td>
        <td>Q {$f['isr']}</td>
        <td>Q {$f['total_descuentos']}</td>
        <td><strong>Q {$f['liquido']}</strong></td>
      </tr>
    ";
}

$html .= "
    </tbody>
  </table>
  <p style='text-align:right; margin-top:10px;'>
    Fecha de registro: {$data['fecha_registro']}
  </p>
</body>
</html>
";

// 5) Renderizar y enviar PDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('letter', 'landscape');
$dompdf->render();

header("Content-Type: application/pdf");
header("Content-Disposition: inline; filename=planilla_mensual.pdf");
echo $dompdf->output();
exit;
?>
