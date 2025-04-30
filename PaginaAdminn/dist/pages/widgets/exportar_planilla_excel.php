<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
include 'conexion.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$query = "SELECT * FROM planilla ORDER BY fecha_registro DESC LIMIT 1";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    die("No hay datos disponibles para exportar.");
}

$data = mysqli_fetch_assoc($result);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle("Planilla Generada");

$sheet->setCellValue('A1', 'Campo');
$sheet->setCellValue('B1', 'Valor');

$campos = [
    'Nombre' => 'nombre',
    'Puesto' => 'puesto',
    'Sueldo Base' => 'sueldo_base',
    'Horas Extras' => 'horas_extras',
    'Comisiones' => 'comisiones',
    'Bonificación' => 'bonificacion',
    'Total Ingresos' => 'total_ingresos',
    'ISSS' => 'isss',
    'ISR' => 'isr',
    'Anticipos' => 'anticipos',
    'Descuentos Judiciales' => 'descuentos_judiciales',
    'Otros Descuentos' => 'otros_descuentos',
    'Total Descuentos' => 'total_descuentos',
    'Líquido a Recibir' => 'liquido_recibir',
    'Fecha' => 'fecha_registro'
];

$row = 2;
foreach ($campos as $label => $campo) {
    $sheet->setCellValue("A{$row}", $label);
    $sheet->setCellValue("B{$row}", $data[$campo]);
    $row++;
}

// Estilos básicos (opcional)
$sheet->getStyle("A1:B1")->getFont()->setBold(true);
$sheet->getColumnDimension("A")->setAutoSize(true);
$sheet->getColumnDimension("B")->setAutoSize(true);

// Salida como archivo descargable
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="planilla_generada.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
