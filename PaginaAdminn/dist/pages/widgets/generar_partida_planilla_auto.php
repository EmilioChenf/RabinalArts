<?php
// generar_partida_planilla_auto.php
header('Content-Type: application/json; charset=utf-8');
session_start();

include 'conexion.php';
require_once __DIR__.'/contabilidad_auto.php';

try {
  $raw = file_get_contents('php://input');
  $in  = json_decode($raw, true);
  if (!is_array($in)) $in = [];

  $planillaId = isset($in['planilla_id']) ? (int)$in['planilla_id'] : 0;
  $medioPago  = isset($in['medio_pago'])  ? trim($in['medio_pago'])  : '';
  $desc       = isset($in['descripcion']) ? trim($in['descripcion']) : '';

  if ($planillaId <= 0) throw new Exception('Falta planilla_id');
  if ($medioPago === '') throw new Exception('Falta medio_pago (Bancos | Caja)');

  // 1) Traer planilla
  $stmt = $conn->prepare("
    SELECT id, nombre, sueldo_base, bonificacion, fecha_registro
      FROM planilla
     WHERE id = ?
  ");
  $stmt->bind_param("i", $planillaId);
  $stmt->execute();
  $stmt->bind_result($pid, $empleado, $sueldo, $bonif, $fecha);
  if (!$stmt->fetch()) {
    $stmt->close();
    throw new Exception('La planilla no existe.');
  }
  $stmt->close();

  $sueldo = round((float)$sueldo, 2);
  $bonif  = round((float)$bonif, 2);

  // 2) Reglas:
  // IGSS laboral = sueldo * 4.83%
  // Cuota patronal = sueldo * 12.67%
  // ISR = max((((sueldo + bonif) - 4000) - IGSS laboral) * 5%, 0)
  $igssLab = round($sueldo * 0.0483, 2);
  $patronal= round($sueldo * 0.1267, 2);
  $baseISR = ($sueldo + $bonif) - 4000.00 - $igssLab;
  $isr     = round(max($baseISR, 0) * 0.05, 2);

  // Totales
  $totalDebe  = round($sueldo + $bonif + $patronal, 2);
  $otrosHaber = round($igssLab + $patronal + $isr, 2);
  $bancosCaja = round($totalDebe - $otrosHaber, 2);

  // Ajuste por redondeo
  $recalcH = round($otrosHaber + $bancosCaja, 2);
  $diff = round($totalDebe - $recalcH, 2);
  if ($diff != 0.00) {
    $bancosCaja = round($bancosCaja + $diff, 2); // corrige centavos
  }

  // 3) UID para trazabilidad
  $externalUid = sha1('planilla|'.$planillaId.'|'.$sueldo.'|'.$bonif.'|'.$igssLab.'|'.$patronal.'|'.$isr.'|'.$bancosCaja);

  // 4) Generar partida
  $partidaId = generarPartidaPlanilla(
    $conn,
    $planillaId,
    $medioPago,   // Bancos|Caja
    $sueldo,
    $bonif,
    $igssLab,
    $patronal,
    $isr,
    $bancosCaja,
    $externalUid,
    $desc
  );

  echo json_encode(['success'=>true, 'partida_id'=>$partidaId], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode(['success'=>false, 'message'=>$e->getMessage()], JSON_UNESCAPED_UNICODE);
}
