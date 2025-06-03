<?php
// exportar_inventario_pdf.php

// 1) Requerimos Dompdf y la conexión a la BD
require_once __DIR__ . '/../../../vendor/autoload.php';// Ajusta ruta si lo instalaste manualmente
use Dompdf\Dompdf;
use Dompdf\Options;

include 'conexion.php';

// 2) Definimos las secciones y subgrupos exactamente igual que en inventario.php
$sections = [
    'ACTIVO' => [
        'ACTIVO CORRIENTE DISPONIBLE',
        'ACTIVO CORRIENTE EXIGIBLE',
        'ACTIVO CORRIENTE REALIZABLE',
        'ACTIVO NO CORRIENTE',
    ],
    'PASIVO' => [
        'PASIVO CORRIENTE',
        'PASIVO NO CORRIENTE',
    ],
    'PATRIMONIO NETO' => [
        'CUENTA DE CAPITAL',
    ],
];

// 3) Construimos el HTML completo (cabecera + tablas)
ob_start();
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Libro de Inventarios</title>
  <style>
    /* --- Reset básico para PDF --- */
    body {
      font-family: serif;
      margin: 0;
      padding: 0;
      font-size: 11pt;
      color: #000;
    }
    h1, h2, h3, h4, h5, h6, p {
      margin: 0;
      padding: 0;
    }
    
    /* --- Estilos generales --- */
    .container {
      width: 100%;
      margin: 0 auto;
      padding: 0 10px;
    }
    
    .titulo-principal {
      text-align: center;
      text-transform: uppercase;
      font-size: 14pt;
      font-weight: bold;
      margin-bottom: 4px;
    }
    .subtitulo {
      text-align: center;
      font-size: 10pt;
      font-style: italic;
      margin-bottom: 8px;
    }
    
    /* --- Líneas rojas superior e inferior del encabezado --- */
    .linea-roja {
      border-top: 2px solid red;
      margin: 0;
      padding: 0;
    }
    .linea-roja-bottom {
      border-bottom: 2px solid red;
      margin: 0;
      padding: 0;
    }
    
    /* --- Estilos de las tablas de inventario --- */
    .inventario-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 12px;
      page-break-inside: avoid;
    }
    .inventario-table th,
    .inventario-table td {
      border: 1px solid red;
      padding: 4px 6px;
    }
    .inventario-table thead th {
      background-color: #f8f8f8;
      font-weight: bold;
      border-bottom: 2px solid red;
      text-align: left;
    }
    .inventario-table tbody tr.subgroup td {
      font-weight: bold;
      background-color: #ffffff;
      border-top: none;
      border-left: 1px solid red;
      border-right: 1px solid red;
      border-bottom: 1px solid red;
    }
    .inventario-table tfoot th {
      border-top: 2px solid red;
      font-weight: bold;
    }
    .doble-subrayado {
      border-bottom: 3px double red !important;
    }
    
    /* Alineación numérica a la derecha */
    .text-end {
      text-align: right;
    }
    
    /* Evitar saltos extraños */
    .page-break {
      page-break-after: always;
    }
    
    /* Título de sección */
    .section-title {
      font-size: 12pt;
      font-weight: bold;
      margin-top: 12px;
      margin-bottom: 4px;
    }
  </style>
</head>
<body>

  <!-- === Encabezado con líneas rojas === -->
  <div class="container">
    <p class="linea-roja"></p>
    <h2 class="titulo-principal">LIBRO DE INVENTARIOS</h2>
    <p class="subtitulo">
      Inventario No. 1 del “Almacén la Fridera”, practicado el <?= date('j \d\e F \d\e Y') ?>.
    </p>
    <p class="subtitulo">(Cifras en quetzales)</p>
    <p class="linea-roja-bottom"></p>
  </div>

  <?php
  // Recorremos cada sección (ACTIVO, PASIVO, PATRIMONIO NETO)
  foreach ($sections as $main => $subgroups):
      // Preparamos un acumulador del total de sección
      $totalSection = 0.00;
  ?>
    <div class="container">

      <!-- Título de la sección -->
      <div class="section-title"><?= $main ?></div>

      <!-- Tabla de la sección -->
      <table class="inventario-table">
        <thead>
          <tr>
            <th style="width:50%;">Cuenta</th>
            <th style="width:25%;">Debe</th>
            <th style="width:25%;">Haber</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($subgroups as $sub):
              // Imprimimos fila de subgrupo
          ?>
            <tr class="subgroup">
              <td colspan="3"><?= htmlspecialchars($sub) ?></td>
            </tr>
            <?php
            // Consulta SQL para traer las cuentas y montos de este subgrupo
            $stmt = $conn->prepare("
              SELECT c.id, c.nombre, d.monto
                FROM cuentas_contables AS c
                JOIN grupos_inventario_detalle AS d ON c.id = d.cuenta_id
                JOIN grupos_inventario       AS g ON g.id = d.grupo_id
               WHERE g.clasificacion = ?
               ORDER BY c.nombre
            ");
            $stmt->bind_param("s", $sub);
            $stmt->execute();
            $res = $stmt->get_result();

            while ($row = $res->fetch_assoc()):
                // Según la lógica: si la sección es “ACTIVO”, monto va a Debe; 
                // de lo contrario (PASIVO / PATRIMONIO NETO) va a Haber.
                $debeVal  = ($main === 'ACTIVO') ? (float)$row['monto'] : 0.00;
                $haberVal = ($main !== 'ACTIVO') ? (float)$row['monto'] : 0.00;

                // Sumamos al total de la sección (siempre monto total, independientemente de Debe/Haber)
                $totalSection += (float)$row['monto'];
            ?>
              <tr>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td class="text-end"><?= number_format($debeVal, 2, '.', ',') ?></td>
                <td class="text-end"><?= number_format($haberVal, 2, '.', ',') ?></td>
              </tr>
            <?php
            endwhile;
            $stmt->close();
          endforeach;
          ?>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="2" class="text-end">TOTAL <?= $main ?>:</th>
            <!-- El total de la sección (suma de todos los montos) se coloca en Haber -->
            <th class="text-end doble-subrayado"><?= number_format($totalSection, 2, '.', ',') ?></th>
          </tr>
        </tfoot>
      </table>

    </div>

  <?php endforeach; ?>

</body>
</html>

<?php
// 4) Capturamos el HTML
$html = ob_get_clean();

// 5) Configuramos Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true); // Si quieres usar imágenes externas (no obligatorio aquí)
$dompdf = new Dompdf($options);

// Cargamos el HTML generado
$dompdf->loadHtml($html);

// (Opcional) Puedes configurar el tamaño de papel y orientación:
$dompdf->setPaper('A4', 'portrait');

// 6) Renderizamos el PDF
$dompdf->render();

// 7) Enviamos al navegador para forzar descarga
$pdfFilename = 'inventario_' . date('Ymd_His') . '.pdf';
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $pdfFilename . '"');
echo $dompdf->output();
exit();
