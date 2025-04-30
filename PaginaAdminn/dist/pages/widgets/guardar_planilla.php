<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $puesto = $_POST['puesto'];
    $sueldo = floatval($_POST['sueldo_base']);
    $extras = intval($_POST['horas_extras']);
    $comisiones = floatval($_POST['comisiones']);
    $bonificacion = floatval($_POST['bonificacion']);
    $anticipos = floatval($_POST['anticipos']);
    $judicial = floatval($_POST['descuentos_judiciales']);
    $otros = floatval($_POST['otros_descuentos']);

    $valor_hora = $sueldo / 30 / 8;
    $pago_extras = $valor_hora * 1.5 * $extras;

    $ingresos = $sueldo + $pago_extras + $comisiones + $bonificacion;
    $isss = $ingresos * 0.0483;
    $isr = ($ingresos * 12 >= 78000) ? ($ingresos * 0.05) : 0;

    $descuentos = $isss + $isr + $anticipos + $judicial + $otros;
    $liquido = $ingresos - $descuentos;

    $stmt = $conn->prepare("INSERT INTO planilla 
        (nombre, puesto, sueldo_base, horas_extras, comisiones, bonificacion, total_ingresos, isss, isr, anticipos, descuentos_judiciales, otros_descuentos, total_descuentos, liquido_recibir)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiddddddddddd", $nombre, $puesto, $sueldo, $extras, $comisiones, $bonificacion, $ingresos, $isss, $isr, $anticipos, $judicial, $otros, $descuentos, $liquido);
    $stmt->execute();

    $_SESSION['ultimo_id'] = $stmt->insert_id;
    header("Location: planilla.php?success=1");
    exit();
}
?>
