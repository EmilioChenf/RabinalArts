<?php
// Ruta al autoload.php desde exportar_pdf.php
require_once __DIR__ . '/sadasd/RabinalArts/LoginRabinarlArts/vendor/autoload.php';


include 'conexion.php';

// Crear instancia del PDF
$pdf = new \TCPDF();
$pdf->SetCreator('RabinalArts');
$pdf->SetAuthor('Sistema Contable');
$pdf->SetTitle('Resumen de Ingresos por Categoría');
$pdf->SetMargins(15, 20, 15);
$pdf->AddPage();

// Consulta SQL: ingresos por categoría
$query = "SELECT categoria, SUM(precio * cantidad) AS total FROM productos GROUP BY categoria";
$resultado = mysqli_query($conn, $query);

// Encabezado del reporte
$html = '<h1 style="text-align:center;">Resumen de Ingresos por Categoría</h1>';
$html .= '<table border="1" cellpadding="6" style="border-collapse:collapse; width:100%;">';
$html .= '<thead><tr style="background-color:#f2f2f2;"><th><b>Categoría</b></th><th><b>Total Q</b></th></tr></thead>';
$html .= '<tbody>';

// Rellenar datos
while ($fila = mysqli_fetch_assoc($resultado)) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($fila['categoria']) . '</td>';
    $html .= '<td>Q ' . number_format($fila['total'], 2) . '</td>';
    $html .= '</tr>';
}

$html .= '</tbody></table>';

// Escribir en el PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Salida del PDF (en navegador)
$pdf->Output('resumen_ingresos_categoria.pdf', 'I');
