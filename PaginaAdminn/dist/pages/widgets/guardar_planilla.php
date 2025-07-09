<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario
    $nombre      = $_POST['nombre'];
    $puesto      = $_POST['puesto'];
    $sueldo      = floatval($_POST['sueldo_base']);
    $extras      = intval($_POST['horas_extras']);
    $comisiones  = floatval($_POST['comisiones']);
    $bonificacion= floatval($_POST['bonificacion']);
    $anticipo    = floatval($_POST['anticipo'] ?? 0);            // <-- corregido: leer 'anticipo'
    $judicial    = floatval($_POST['descuentos_judiciales']);
    $otros       = floatval($_POST['otros_descuentos']);

    // Cálculo de horas extras
    $valor_hora  = $sueldo / 30 / 8;
    $pago_extras = $valor_hora * 1.5 * $extras;

    // Cálculo de ingresos y descuentos
    $ingresos    = $sueldo + $pago_extras + $comisiones + $bonificacion;
    $isss        = $ingresos * 0.0483;
    $isr         = ($ingresos * 12 >= 78000) ? ($ingresos * 0.05) : 0;

    $descuentos  = $isss + $isr + $anticipo + $judicial + $otros;
    $liquido     = $ingresos - $descuentos;

    // Preparar e insertar en la base
    $stmt = $conn->prepare("
        INSERT INTO planilla
        (nombre,
         puesto,
         sueldo_base,
         horas_extras,
         comisiones,
         bonificacion,
         total_ingresos,
         isss,
         isr,
         anticipo,
         descuentos_judiciales,
         otros_descuentos,
         total_descuentos,
         liquido_recibir)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    // Tipos: s=string, d=double, i=integer
    $stmt->bind_param(
        'ssdidddddddddd',
        $nombre,
        $puesto,
        $sueldo,
        $extras,
        $comisiones,
        $bonificacion,
        $ingresos,
        $isss,
        $isr,
        $anticipo,    // <-- aquí usamos la variable corregida
        $judicial,
        $otros,
        $descuentos,
        $liquido
    );

    if (! $stmt->execute()) {
        die("Error al guardar la planilla: " . $stmt->error);
    }

    // Guardar ID y redirigir
    $_SESSION['ultimo_id'] = $stmt->insert_id;
    header("Location: planilla.php?success=1");
    exit();
}

// Si acceden por GET, volver al formulario
header("Location: planilla.php");
exit();
?>
