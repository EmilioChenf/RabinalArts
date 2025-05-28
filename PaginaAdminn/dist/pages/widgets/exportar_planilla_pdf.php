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

// 2) Calcular valor de hora extra (suponiendo 240 h/mes si no hay campo específico)
$valorHora = 0;
if (!empty($data['sueldo_base'])) {
    $valorHora = $data['sueldo_base'] / 240;
}
$totalHorasExtras = $valorHora * $data['horas_extras'];

// 3) Generar HTML con estilo de “Planilla Mensual”
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
      <tr>
        <td>1</td>
        <td>{$data['nombre']}</td>
        <td>{$data['puesto']}</td>
        <td>Q " . number_format($data['sueldo_base'],2) . "</td>
        <td>{$data['horas_extras']}</td>
        <td>Q " . number_format($totalHorasExtras,2) . "</td>
        <td>Q " . number_format($data['comisiones'],2) . "</td>
        <td>Q " . number_format($data['bonificacion'],2) . "</td>
        <td>Q " . number_format($data['total_ingresos'],2) . "</td>
        <td>Q " . number_format($data['isss'],2) . "</td>
        <td>Q " . number_format($data['anticipos'],2) . "</td>
        <td>Q " . number_format($data['descuentos_judiciales'],2) . "</td>
        <td>Q 0.00</td>
        <td>Q " . number_format($data['isr'],2) . "</td>
        <td>Q " . number_format($data['total_descuentos'],2) . "</td>
        <td><strong>Q " . number_format($data['liquido_recibir'],2) . "</strong></td>
      </tr>
    </tbody>
  </table>
  <p style='text-align:right; margin-top:10px;'>Fecha: {$data['fecha_registro']}</p>
</body>
</html>
";

// 4) Configurar Dompdf y generar PDF en orientación horizontal (landscape)
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('letter', 'landscape');
$dompdf->render();

// 5) Enviar PDF al navegador
header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=planilla_mensual.pdf");
echo $dompdf->output();
exit;
