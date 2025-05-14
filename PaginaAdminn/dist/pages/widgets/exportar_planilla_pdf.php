<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once 'conexion.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$query = "SELECT * FROM planilla ORDER BY fecha_registro DESC LIMIT 1";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}

$data = mysqli_fetch_assoc($result);

// Crear HTML con estilo tipo planilla mensual
$html = "
<style>
    body { font-family: Arial, sans-serif; font-size: 10px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid black; padding: 4px; text-align: center; }
    th { background-color: #f2f2f2; }
</style>
<h2 style='text-align:center;'>PLANILLA MENSUAL</h2>
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
            <td>Q {$data['sueldo_base']}</td>
            <td>{$data['horas_extras']}</td>
            <td>Q " . number_format(($data['horas_extras'] * 10), 2) . "</td> <!-- ejemplo: valor horas -->
            <td>Q {$data['comisiones']}</td>
            <td>Q {$data['bonificacion']}</td>
            <td>Q {$data['total_ingresos']}</td>
            <td>Q {$data['isss']}</td>
            <td>Q {$data['anticipos']}</td>
            <td>Q {$data['descuentos_judiciales']}</td>
            <td>Q 0.00</td> <!-- Prestaciones -->
            <td>Q {$data['isr']}</td>
            <td>Q {$data['total_descuentos']}</td>
            <td><strong>Q {$data['liquido_recibir']}</strong></td>
        </tr>
    </tbody>
</table>
<p style='text-align:right;'>Fecha: {$data['fecha_registro']}</p>
";

// Generar PDF horizontal (landscape)
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('letter', 'landscape');   // 👈 horizontal
$dompdf->render();

header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=planilla_mensual.pdf");
echo $dompdf->output();
exit;
?>
