<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
include 'conexion.php';
session_start();

use Dompdf\Dompdf;
use Dompdf\Options;

$cliente_id = $_GET['cliente_id'] ?? null;

if (!$cliente_id) {
    die("ID de cliente requerido");
}

// Obtener datos del cliente
$sql = $conn->query("SELECT * FROM usuarios WHERE id = $cliente_id");
$cliente = $sql->fetch_assoc();

// Obtener última venta
$venta = $conn->query("SELECT * FROM ventas WHERE cliente_id = $cliente_id ORDER BY id DESC LIMIT 1")->fetch_assoc();
$venta_id = $venta['id'] ?? 0;
$detalles = [];

if ($venta_id) {
    $result = $conn->query("SELECT d.*, p.nombre FROM detalle_venta d JOIN productos p ON d.producto_id = p.id WHERE d.venta_id = $venta_id");
    while ($row = $result->fetch_assoc()) {
        $detalles[] = $row;
    }
}

// También podemos mostrar la factura actual almacenada en sesión
$factura_actual = $_SESSION['factura_detalle'] ?? [];

ob_start();
?>

<h1 style="text-align: center;">Factura Generada</h1>

<h3>Cliente</h3>
<p><strong>ID:</strong> <?= $cliente['id'] ?></p>
<p><strong>Nombre:</strong> <?= $cliente['nombre'] ?></p>
<p><strong>Correo:</strong> <?= $cliente['correo'] ?></p>
<p><strong>Teléfono:</strong> <?= $cliente['telefono'] ?></p>
<p><strong>Dirección:</strong> <?= $cliente['direccion'] ?></p>

<?php if (!empty($detalles)): ?>
<h3>Última Factura</h3>
<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <thead>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
    <?php $total_ultima = 0; ?>
    <?php foreach ($detalles as $row): ?>
        <?php $total_ultima += $row['total']; ?>
        <tr>
            <td><?= $row['nombre'] ?></td>
            <td><?= $row['cantidad'] ?></td>
            <td>Q<?= number_format($row['precio_unitario'], 2) ?></td>
            <td>Q<?= number_format($row['total'], 2) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" align="right"><strong>Total:</strong></td>
            <td>Q<?= number_format($total_ultima, 2) ?></td>
        </tr>
    </tfoot>
</table>
<?php endif; ?>

<?php if (!empty($factura_actual)): ?>
<h3>Factura Actual (No confirmada)</h3>
<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <thead>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
    <?php $total = 0; ?>
    <?php foreach ($factura_actual as $item): ?>
        <?php $total += $item['subtotal']; ?>
        <tr>
            <td><?= $item['nombre'] ?></td>
            <td><?= $item['cantidad'] ?></td>
            <td>Q<?= number_format($item['precio'], 2) ?></td>
            <td>Q<?= number_format($item['subtotal'], 2) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" align="right"><strong>Total:</strong></td>
            <td>Q<?= number_format($total, 2) ?></td>
        </tr>
    </tfoot>
</table>
<?php endif; ?>

<p style="text-align: right;">Fecha: <?= date("Y-m-d H:i:s") ?></p>

<?php
$html = ob_get_clean();

// Configuración de Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Factura_cliente_{$cliente['id']}.pdf", ["Attachment" => false]);
exit;
