<?php
// generar_partida_planilla_auto.php
// Soporta:
//  - modo: 'mes', periodo: 'YYYY-MM', medio_pago, descripcion
//  - (compat) planilla_id, medio_pago, descripcion  -> una sola planilla

declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

include 'conexion.php';
require_once __DIR__.'/contabilidad_auto.php';

try {
  $raw = file_get_contents('php://input');
  $in  = json_decode($raw, true);
  if (!is_array($in)) $in = $_POST ?? [];

  $modo   = isset($in['modo']) ? trim((string)$in['modo']) : '';
  $desc   = isset($in['descripcion']) ? trim((string)$in['descripcion']) : '';
  $medio  = isset($in['medio_pago'])  ? trim((string)$in['medio_pago'])  : '';

  if ($medio === '') throw new Exception('Falta medio_pago (Bancos | Caja).');

  // ===== MODO MENSUAL (agregado) =====
  if ($modo === 'mes' || !empty($in['periodo'])) {
    $periodo = isset($in['periodo']) ? trim((string)$in['periodo']) : '';
    if ($periodo === '' || !preg_match('/^\d{4}-\d{2}$/', $periodo)) {
      throw new Exception('Período inválido. Usa YYYY-MM.');
    }
    $start = $periodo . '-01';
    $end   = date('Y-m-d', strtotime($start . ' +1 month'));

    // Traer sumas del mes
    $stmt = $conn->prepare("
      SELECT
        SUM(sueldo_base)     AS sum_sueldos,
        SUM(bonificacion)    AS sum_bonif,
        SUM(liquido_recibir) AS sum_liquido,
        COUNT(*)             AS cnt
      FROM planilla
      WHERE fecha_registro >= ? AND fecha_registro < ?
    ");
    $stmt->bind_param('ss', $start, $end);
    $stmt->execute();
    $sum = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$sum || (int)$sum['cnt'] === 0) {
      throw new Exception('No hay planillas en el período seleccionado.');
    }

    $sueldos = round((float)$sum['sum_sueldos'], 2);
    $bonif   = round((float)$sum['sum_bonif'], 2);
    $liqTot  = round((float)$sum['sum_liquido'], 2);

    // Cálculos con base EN EL TOTAL LIQUIDADO del mes
    $igssLab  = round($liqTot * 0.0483, 2);
    $patronal = round($liqTot * 0.1267, 2);
    $baseISR  = ($liqTot - 4000.00) - $igssLab;
    $isr      = round(max($baseISR, 0) * 0.05, 2);

    $totalDebe  = round($sueldos + $bonif + $patronal, 2);
    $otrosHaber = round($igssLab + $patronal + $isr, 2);
    $bancosCaja = round($totalDebe - $otrosHaber, 2);

    // UID para idempotencia mensual (no duplica)
    $externalUid = sha1("planilla-mes|$periodo|$sueldos|$bonif|$liqTot|$igssLab|$patronal|$isr|$bancosCaja");

    // Si no envían descripción, armamos una
    if ($desc === '') $desc = "Partida automática planilla mensual $periodo";

    // planilla_id = 0 para mensual
    $partidaId = generarPartidaPlanilla(
      $conn,
      0,              // planilla mensual
      $medio,
      $sueldos,
      $bonif,
      $igssLab,
      $patronal,
      $isr,
      $bancosCaja,
      $externalUid,
      $desc . " | PERIODO=$periodo"
    );

    echo json_encode(['success'=>true, 'partida_id'=>$partidaId], JSON_UNESCAPED_UNICODE);
    exit;
  }

  // ===== MODO INDIVIDUAL (compatibilidad) =====
  $planillaId = isset($in['planilla_id']) ? (int)$in['planilla_id'] : 0;
  if ($planillaId <= 0) throw new Exception('Falta planilla_id o periodo.');

  // Traer una planilla
  $stmt = $conn->prepare("
    SELECT id, sueldo_base, bonificacion, liquido_recibir
      FROM planilla
     WHERE id = ?
     LIMIT 1
  ");
  $stmt->bind_param("i", $planillaId);
  $stmt->execute();
  $pl = $stmt->get_result()->fetch_assoc();
  $stmt->close();
  if (!$pl) throw new Exception('La planilla no existe.');

  $sueldos = round((float)$pl['sueldo_base'], 2);
  $bonif   = round((float)$pl['bonificacion'], 2);
  $liq     = round((float)$pl['liquido_recibir'], 2);

  $igssLab  = round($liq * 0.0483, 2);
  $patronal = round($liq * 0.1267, 2);
  $baseISR  = ($liq - 4000.00) - $igssLab;
  $isr      = round(max($baseISR, 0) * 0.05, 2);

  $totalDebe  = round($sueldos + $bonif + $patronal, 2);
  $otrosHaber = round($igssLab + $patronal + $isr, 2);
  $bancosCaja = round($totalDebe - $otrosHaber, 2);

  $externalUid = sha1("planilla-uno|$planillaId|$sueldos|$bonif|$liq|$igssLab|$patronal|$isr|$bancosCaja");
  if ($desc === '') $desc = "Partida automática planilla PID=$planillaId";

  $partidaId = generarPartidaPlanilla(
    $conn,
    $planillaId,
    $medio,
    $sueldos,
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
