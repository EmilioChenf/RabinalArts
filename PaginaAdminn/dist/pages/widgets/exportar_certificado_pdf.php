<?php
// exportar_certificado_pdf.php

require_once __DIR__ . '/../../../vendor/autoload.php';
include 'conexion.php';

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Convierte un número de 0 a 999 a su representación en texto (menor de mil).
 * Ejemplo: 512 → "quinientos doce"
 */
function convertirMenorDeMil(int $n): string {
    $unidad = [
        '', 'uno', 'dos', 'tres', 'cuatro',
        'cinco', 'seis', 'siete', 'ocho', 'nueve'
    ];
    $decena = [
        '', 'diez', 'veinte', 'treinta', 'cuarenta',
        'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'
    ];
    $especiales = [
        11 => 'once',      12 => 'doce',       13 => 'trece',       14 => 'catorce',    15 => 'quince',
        16 => 'dieciséis', 17 => 'diecisiete', 18 => 'dieciocho',   19 => 'diecinueve',
        21 => 'veintiuno', 22 => 'veintidós',  23 => 'veintitrés',  24 => 'veinticuatro',25 => 'veinticinco',
        26 => 'veintiséis',27 => 'veintisiete',28 => 'veintiocho',  29 => 'veintinueve'
    ];

    $texto = '';
    $n = intval($n);

    // Centenas
    if ($n >= 100) {
        $c = intdiv($n, 100);
        if ($c === 1) {
            $texto .= ($n % 100 === 0) ? 'cien' : 'ciento';
        } elseif ($c === 5) {
            $texto .= 'quinientos';
        } elseif ($c === 7) {
            $texto .= 'setecientos';
        } elseif ($c === 9) {
            $texto .= 'novecientos';
        } else {
            $texto .= $unidad[$c] . 'cientos';
        }
        $n %= 100;
        if ($n > 0) {
            $texto .= ' ';
        }
    }

    // Decenas y unidades
    if ($n >= 30) {
        $d = intdiv($n, 10);
        $texto .= $decena[$d];
        $u = $n % 10;
        if ($u > 0) {
            $texto .= ' y ' . $unidad[$u];
        }
    } elseif ($n >= 10 && $n <= 29) {
        if (isset($especiales[$n])) {
            $texto .= $especiales[$n];
        } else {
            // 10 o 20 exactos
            if ($n === 10) {
                $texto .= 'diez';
            } elseif ($n === 20) {
                $texto .= 'veinte';
            }
        }
    } elseif ($n > 0 && $n < 10) {
        $texto .= $unidad[$n];
    } elseif ($n === 0 && $texto === '') {
        $texto .= 'cero';
    }

    return $texto;
}

/**
 * Convierte un número (hasta dos decimales) a texto en español.
 * Ejemplo: 51213.87 → "Cincuenta y un mil doscientos trece quetzales con ochenta y siete centavos."
 */
function numeroEnLetras(float $numero): string {
    // Asegurarse de tener siempre dos decimales
    $numeroFormateado = number_format($numero, 2, '.', '');
    list($parteEntera, $parteDecimales) = explode('.', $numeroFormateado);

    // ------------------------------------------------
    //  Convertir la parte entera en millones, miles y decenas
    // ------------------------------------------------
    $entero = intval($parteEntera);
    if ($entero === 0) {
        $textoEntero = 'cero';
    } else {
        $textoEntero = '';
        $millones = intdiv($entero, 1000000);
        $restoMillones = $entero % 1000000;

        if ($millones > 0) {
            if ($millones === 1) {
                $textoEntero .= 'un millón';
            } else {
                $textoEntero .= convertirMenorDeMil($millones) . ' millones';
            }
            if ($restoMillones > 0) {
                $textoEntero .= ' ';
            }
        }

        $miles = intdiv($restoMillones, 1000);
        $restoMiles = $restoMillones % 1000;
        if ($miles > 0) {
            if ($miles === 1) {
                $textoEntero .= 'mil';
            } else {
                $textoEntero .= convertirMenorDeMil($miles) . ' mil';
            }
            if ($restoMiles > 0) {
                $textoEntero .= ' ';
            }
        }

        if ($restoMiles > 0) {
            $textoEntero .= convertirMenorDeMil($restoMiles);
        }
    }

    // ------------------------------------------------
    //  Parte decimal (centavos), de 0 a 99
    // ------------------------------------------------
    $dec = intval($parteDecimales); // entre 0 y 99
    if ($dec === 0) {
        $textoDecimales = 'cero centavos';
    } else {
        $textoDecimales = convertirMenorDeMil($dec) . ' centavos';
    }

    // ------------------------------------------------
    //  Construir la oración final
    // ------------------------------------------------
    $resultado = ucfirst($textoEntero) . ' quetzales con ' . $textoDecimales . '.';
    return $resultado;
}

// ------------------------------------------------------
//  Paso 1: Obtener todas las planillas y sumar liquido_recibir
// ------------------------------------------------------
$sql = "SELECT id, nombre, puesto, fecha_registro, liquido_recibir 
          FROM planilla
         ORDER BY fecha_registro ASC";
$res = mysqli_query($conn, $sql);
if (!$res) {
    die("Error en la consulta: " . mysqli_error($conn));
}

$totalLiquido = 0.00;
$filas = [];
while ($fila = mysqli_fetch_assoc($res)) {
    $filas[] = $fila;
    $totalLiquido += floatval($fila['liquido_recibir']);
}

// ------------------------------------------------------
//  Paso 2: Generar el HTML para el PDF
// ------------------------------------------------------
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Certificado de Planillas</title>
  <style>
    body { font-family: Arial, sans-serif; font-size: 12px; }
    h2 { text-align: center; margin-bottom: 5px; }
    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
    th, td { border: 1px solid #000; padding: 6px; text-align: center; }
    th { background-color: #f2f2f2; }
    .totales { margin-top: 30px; font-style: italic; }
    .firma { margin-top: 60px; text-align: right; }
  </style>
</head>
<body>
  <h2>CERTIFICADO DE PLANILLAS GENERADAS</h2>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Empleado</th>
        <th>Puesto</th>
        <th>Fecha</th>
        <th>Líquido a recibir (Q)</th>
      </tr>
    </thead>
    <tbody>
';

$contador = 1;
foreach ($filas as $f) {
    $html .= '<tr>';
    $html .= '<td>' . ($contador++) . '</td>';
    $html .= '<td>' . htmlspecialchars($f['nombre']) . '</td>';
    $html .= '<td>' . htmlspecialchars($f['puesto']) . '</td>';
    $html .= '<td>' . htmlspecialchars($f['fecha_registro']) . '</td>';
    $html .= '<td style="text-align:right;">' . number_format($f['liquido_recibir'], 2) . '</td>';
    $html .= '</tr>';
}

// Después de listar todas las filas, agregamos el TOTAL numérico como una fila adicional:
$html .= '
    </tbody>
    <tfoot>
      <tr>
        <th colspan="4" style="text-align:right;">TOTAL:</th>
        <th style="text-align:right;">Q ' . number_format($totalLiquido, 2) . '</th>
      </tr>
    </tfoot>
  </table>

  <div class="totales">
    ';
$enLetras = numeroEnLetras($totalLiquido);
$textoNumeros = number_format($totalLiquido, 2, '.', '');
$html .= 'DE CONFORMIDAD CON LOS DATOS ANTERIORES, EL TOTAL DEVENGADO DE LA PRESENTE PLANILLA ASCIENDE A LA CANTIDAD DE ' 
       . $enLetras . ' (Q  ' . $textoNumeros . ').';
$html .= '
  </div>

  <div class="firma">
    _______________________________<br>
    Contador Oscar
  </div>

</body>
</html>
';

// ------------------------------------------------------
//  Paso 3: Generar PDF con Dompdf (papel carta, vertical)
// ------------------------------------------------------
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);

// Orientación vertical (portrait) en papel carta:
$dompdf->setPaper('letter', 'portrait');
$dompdf->render();

// Asegurarse de no imprimir nada antes de estas cabeceras:
header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=certificado_planillas.pdf");
echo $dompdf->output();
exit;
?>
