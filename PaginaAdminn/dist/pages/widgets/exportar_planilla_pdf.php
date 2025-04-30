<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once 'conexion.php';

use Dompdf\Dompdf;

$query = "SELECT * FROM planilla ORDER BY fecha_registro DESC LIMIT 1";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}

$data = mysqli_fetch_assoc($result);

$html = "
    <h1 style='text-align:center;'>Planilla Generada</h1>
    <table border='1' cellpadding='5' cellspacing='0' style='width: 100%; font-size: 12px;'>
        <tr><th>Nombre</th><td>{$data['nombre']}</td></tr>
        <tr><th>Puesto</th><td>{$data['puesto']}</td></tr>
        <tr><th>Sueldo Base</th><td>Q {$data['sueldo_base']}</td></tr>
        <tr><th>Horas Extras</th><td>{$data['horas_extras']}</td></tr>
        <tr><th>Comisiones</th><td>Q {$data['comisiones']}</td></tr>
        <tr><th>Bonificación</th><td>Q {$data['bonificacion']}</td></tr>
        <tr><th>Total Ingresos</th><td>Q {$data['total_ingresos']}</td></tr>
        <tr><th>ISSS</th><td>Q {$data['isss']}</td></tr>
        <tr><th>ISR</th><td>Q {$data['isr']}</td></tr>
        <tr><th>Anticipos</th><td>Q {$data['anticipos']}</td></tr>
        <tr><th>Descuentos Judiciales</th><td>Q {$data['descuentos_judiciales']}</td></tr>
        <tr><th>Otros Descuentos</th><td>Q {$data['otros_descuentos']}</td></tr>
        <tr><th>Total Descuentos</th><td>Q {$data['total_descuentos']}</td></tr>
        <tr><th>Líquido a Recibir</th><td><strong>Q {$data['liquido_recibir']}</strong></td></tr>
        <tr><th>Fecha</th><td>{$data['fecha_registro']}</td></tr>
    </table>
";

// Generar PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('letter', 'portrait');
$dompdf->render();

header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=planilla.pdf");
echo $dompdf->output();
