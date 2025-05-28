<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
include 'conexion.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Obtener fecha si se envió (en formato YYYY-MM-DD)
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : null;

ob_start();
$html = "<h2 style='text-align:center;'>Sistema Contable - RABINALARTS</h2>";

// ----------- Reporte Diario si se selecciona fecha -----------
if ($fecha) {
    $html .= "<h3>Reporte Diario (Productos ingresados el $fecha)</h3>";
    $html .= "<table border='1' cellspacing='0' cellpadding='5' width='100%'>";
    $html .= "<thead><tr><th>Nombre</th><th>Categoría</th><th>Precio</th><th>Stock</th><th>Total</th></tr></thead><tbody>";

    $stmt = $conn->prepare("SELECT nombre, categoria, precio, stock FROM productos WHERE DATE(fecha_creacion) = ?");
    $stmt->bind_param("s", $fecha);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($fila = $result->fetch_assoc()) {
        $total = $fila['precio'] * $fila['stock'];
        $html .= "<tr>
            <td>{$fila['nombre']}</td>
            <td>{$fila['categoria']}</td>
            <td>$ " . number_format($fila['precio'], 2) . "</td>
            <td>{$fila['stock']}</td>
            <td>$ " . number_format($total, 2) . "</td>
        </tr>";
    }
    $html .= "</tbody></table><br>";
}

// ----------- Resumen por Categoría -----------
$html .= "<h3>Resumen de Ingresos por Categoría</h3>";
$html .= "<table border='1' cellspacing='0' cellpadding='5' width='100%'>";
$html .= "<thead><tr><th>Categoría</th><th>Total ingresos ($)</th></tr></thead><tbody>";

$queryCat = "SELECT categoria, SUM(precio * stock) AS total FROM productos";
if ($fecha) $queryCat .= " WHERE DATE(fecha_creacion) = '$fecha'";
$queryCat .= " GROUP BY categoria";

$resumen = mysqli_query($conn, $queryCat);
while ($fila = mysqli_fetch_assoc($resumen)) {
    $html .= "<tr><td>{$fila['categoria']}</td><td>$ " . number_format($fila['total'], 2) . "</td></tr>";
}
$html .= "</tbody></table><br>";

// ----------- Ingresos Mensuales -----------
$html .= "<h3>Reporte total de ingresos por mes</h3>";
$html .= "<table border='1' cellspacing='0' cellpadding='5' width='100%'>";
$html .= "<thead><tr><th>Mes</th><th>Total ingresos ($)</th></tr></thead><tbody>";

$queryMes = "SELECT DATE_FORMAT(fecha_creacion, '%M %Y') AS mes, SUM(precio * stock) AS total FROM productos";
if ($fecha) {
    $mes = date("m", strtotime($fecha));
    $anio = date("Y", strtotime($fecha));
    $queryMes .= " WHERE MONTH(fecha_creacion) = $mes AND YEAR(fecha_creacion) = $anio";
}
$queryMes .= " GROUP BY mes ORDER BY MIN(fecha_creacion)";

$reporte = mysqli_query($conn, $queryMes);
while ($fila = mysqli_fetch_assoc($reporte)) {
    $html .= "<tr><td>{$fila['mes']}</td><td>$ " . number_format($fila['total'], 2) . "</td></tr>";
}
$html .= "</tbody></table><br>";

// ----------- Ingresos Anuales -----------
$html .= "<h3>Reporte total de ingresos por año</h3>";
$html .= "<table border='1' cellspacing='0' cellpadding='5' width='100%'>";
$html .= "<thead><tr><th>Año</th><th>Total ingresos ($)</th></tr></thead><tbody>";

$queryAnio = "SELECT YEAR(fecha_creacion) AS anio, SUM(precio * stock) AS total FROM productos";
if ($fecha) {
    $anio = date("Y", strtotime($fecha));
    $queryAnio .= " WHERE YEAR(fecha_creacion) = $anio";
}
$queryAnio .= " GROUP BY anio ORDER BY anio";

$reporteAnual = mysqli_query($conn, $queryAnio);
while ($fila = mysqli_fetch_assoc($reporteAnual)) {
    $html .= "<tr><td>{$fila['anio']}</td><td>$ " . number_format($fila['total'], 2) . "</td></tr>";
}
$html .= "</tbody></table><br>";

// ----------- Ganancias por Producto (ventas) -----------
$html .= "<h3>Ganancias por Producto (Ventas)</h3>";
$html .= "<table border='1' cellspacing='0' cellpadding='5' width='100%'>";
$html .= "<thead><tr><th>Producto</th><th>Categoría</th><th>Precio</th><th>Stock</th><th>Ganancia ($)</th></tr></thead><tbody>";

$queryProductos = "SELECT nombre, categoria, precio, stock FROM productos";
if ($fecha) $queryProductos .= " WHERE DATE(fecha_creacion) = '$fecha'";
$productos = mysqli_query($conn, $queryProductos);
while ($fila = mysqli_fetch_assoc($productos)) {
    $ganancia = $fila['precio'] * $fila['stock'];
    $html .= "<tr>
        <td>{$fila['nombre']}</td>
        <td>{$fila['categoria']}</td>
        <td>$ " . number_format($fila['precio'], 2) . "</td>
        <td>{$fila['stock']}</td>
        <td>$ " . number_format($ganancia, 2) . "</td>
    </tr>";
}
$html .= "</tbody></table><br>";

// ----------- Balance General -----------
$html .= "<h3>Balance General</h3>";
$queryBalance = "SELECT SUM(precio * stock) AS total FROM productos";
if ($fecha) $queryBalance .= " WHERE DATE(fecha_creacion) = '$fecha'";
$activos = mysqli_fetch_assoc(mysqli_query($conn, $queryBalance));
$pasivos = 5000;
$capital = $activos['total'] - $pasivos;

$html .= "<ul>
    <li><strong>Activos:</strong> $ " . number_format($activos['total'], 2) . "</li>
    <li><strong>Pasivos:</strong> $ " . number_format($pasivos, 2) . "</li>
    <li><strong>Capital:</strong> $ " . number_format($capital, 2) . "</li>
</ul>";

// ----------- Ganancias del Mes Actual -----------
$html .= "<h3>Ganancias del Mes Actual</h3>";
$mesActual = date('Y-m');
$stmt = $conn->prepare("SELECT SUM(precio * stock) AS ganancias_mes FROM productos WHERE DATE_FORMAT(fecha_creacion, '%Y-%m') = ?");
$stmt->bind_param("s", $mesActual);
$stmt->execute();
$resultado = $stmt->get_result();
$gananciasMes = $resultado->fetch_assoc();
$ganancia = $gananciasMes['ganancias_mes'] ?? 0;
$html .= "<p><strong>Ganancias:</strong> Q " . number_format($ganancia, 2) . "</p>";

$html .= "<p style='text-align:right;'>Fecha de generación: " . date("Y-m-d H:i:s") . "</p>";

$htmlContent = $html;
ob_end_clean();

// DOMPDF
$options = new Options();   
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($htmlContent);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_contable_rabinalarts.pdf", ["Attachment" => false]);
exit;
