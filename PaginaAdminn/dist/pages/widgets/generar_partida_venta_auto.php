<?php
// generar_partida_venta_auto.php
session_start();
header('Content-Type: application/json; charset=utf-8');

include 'conexion.php';
require_once __DIR__.'/contabilidad_auto.php';

try {
  $raw = file_get_contents('php://input');
  $in  = json_decode($raw, true);
  if (!is_array($in)) $in = [];

  $ventaId    = isset($in['venta_id']) ? (int)$in['venta_id'] : 0;
  $formaCobro = isset($in['forma_cobro']) ? trim($in['forma_cobro']) : '';
  $desc       = isset($in['descripcion']) ? trim($in['descripcion']) : '';

  if ($ventaId <= 0)        throw new Exception('Falta venta_id');
  if ($formaCobro === '')   throw new Exception('Falta forma_cobro (Efectivo | Transferencia)');

  // 1) Traer cliente y fecha de la venta
  $stmt = $conn->prepare("SELECT cliente_id, fecha FROM ventas WHERE id = ?");
  $stmt->bind_param("i", $ventaId);
  $stmt->execute();
  $stmt->bind_result($clienteId, $fechaVenta);
  if (!$stmt->fetch()) {
    $stmt->close();
    throw new Exception('La venta no existe.');
  }
  $stmt->close();

  // 2) TOTAL (IVA incluido) desde detalle_venta
  $stmt = $conn->prepare("SELECT COALESCE(SUM(total),0) FROM detalle_venta WHERE venta_id = ?");
  $stmt->bind_param("i", $ventaId);
  $stmt->execute();
  $stmt->bind_result($totalConIva);
  $stmt->fetch();
  $stmt->close();

  $totalConIva = round((float)$totalConIva, 2);
  if ($totalConIva <= 0) throw new Exception('La venta no tiene detalle o el total es 0.');

  // 3) IVA incluido -> base = total/1.12, IVA = total - base
  $base = round($totalConIva / 1.12, 2);
  $iva  = round($totalConIva - $base, 2);

  // 4) Huella para idempotencia y trazabilidad
  $externalUid = sha1('venta|'.$ventaId.'|'.$clienteId.'|'.$base.'|'.$iva.'|'.$totalConIva);

  // 5) Generar la partida (Caja/Bancos — Ventas — IVA por Pagar)
  $partidaId = generarPartidaVenta(
    $conn,
    (int)$clienteId,
    $formaCobro,
    $base,
    $iva,
    $totalConIva,
    $externalUid,
    $desc,
    $ventaId
  );

  echo json_encode(['success'=>true, 'partida_id'=>$partidaId], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode(['success'=>false, 'message'=>$e->getMessage()], JSON_UNESCAPED_UNICODE);
}
