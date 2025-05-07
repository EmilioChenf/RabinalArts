<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
include 'conexion.php';

use Dompdf\Dompdf;
use Dompdf\Options;

ob_start();

// ----------- DATOS: Resumen por Categoría -----------
$html = "<h2 style='text-align:center;'>Sistema Contable - RABINALARTS</h2>";
$html .= "<h3>Resumen de Ingresos por Categoría</h3>";
$html .= "<table border='1' cellspacing='0' cellpadding='5' width='100%'>";
$html .= "<thead><tr><th>Categoría</th><th>Total ingresos ($)</th></tr></thead><tbody>";

$resumen = mysqli_query($conn, "SELECT categoria, SUM(precio * stock) AS total FROM productos GROUP BY categoria");
while ($fila = mysqli_fetch_assoc($resumen)) {
    $html .= "<tr><td>{$fila['categoria']}</td><td>$ " . number_format($fila['total'], 2) . "</td></tr>";
}
$html .= "</tbody></table><br>";

// ----------- DATOS: Ingresos por Mes -----------
$html .= "<h3>Reporte total de ingresos por mes</h3>";
$html .= "<table border='1' cellspacing='0' cellpadding='5' width='100%'>";
$html .= "<thead><tr><th>Mes</th><th>Total ingresos (Q)</th></tr></thead><tbody>";

$reporte = mysqli_query($conn, "
  SELECT DATE_FORMAT(fecha_creacion, '%M %Y') AS mes, SUM(precio * stock) AS total 
  FROM productos GROUP BY mes ORDER BY fecha_creacion
");
while ($fila = mysqli_fetch_assoc($reporte)) {
    $html .= "<tr><td>{$fila['mes']}</td><td>$ " . number_format($fila['total'], 2) . "</td></tr>";
}
$html .= "</tbody></table><br>";

// ----------- DATOS: Balance General -----------
$html .= "<h3>Balance General</h3>";
$activos = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(precio * stock) AS total FROM productos"));
$pasivos = 5000;
$capital = $activos['total'] - $pasivos;

$html .= "<ul>
    <li><strong>Activos:</strong> $ " . number_format($activos['total'], 2) . "</li>
    <li><strong>Pasivos:</strong> $ " . number_format($pasivos, 2) . "</li>
    <li><strong>Capital:</strong> $ " . number_format($capital, 2) . "</li>
</ul>";

$html .= "<p style='text-align:right;'>Fecha de generación: " . date("Y-m-d H:i:s") . "</p>";

$htmlContent = $html;
ob_end_clean();

// DOMPDF CONFIGURACIÓN
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($htmlContent);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("sistema_contable.pdf", ["Attachment" => false]);
exit;
