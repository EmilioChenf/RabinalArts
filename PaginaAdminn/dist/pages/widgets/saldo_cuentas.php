<?php
// saldo_cuentas.php
date_default_timezone_set('America/Guatemala');

// Cargar conexión
include 'conexion.php'; // ajusta la ruta si es necesario

// Sanitizar cuenta_id
$cuentaId = isset($_GET['cuenta_id']) ? (int)$_GET['cuenta_id'] : 0;
if ($cuentaId <= 0) {
  http_response_code(400);
  echo "<div style='padding:1rem;color:#b00'>Falta parámetro válido: cuenta_id</div>";
  exit;
}

// Obtener nombre de la cuenta
$cuentaNombre = '';
if ($stmt = $conn->prepare("SELECT nombre FROM cuentas_contables WHERE id = ?")) {
  $stmt->bind_param("i", $cuentaId);
  $stmt->execute();
  $stmt->bind_result($cuentaNombre);
  $stmt->fetch();
  $stmt->close();
}
if ($cuentaNombre === '') {
  echo "<div style='padding:1rem;color:#b00'>La cuenta #{$cuentaId} no existe.</div>";
  exit;
}

// Unificar todas las partidas que usan esta cuenta
// Campos: fecha, no_partida, debe, haber, tipo
$sql = "
  (SELECT pc.fecha              AS fecha,
          pd.partida_id         AS no_partida,
          pd.debe               AS debe,
          pd.haber              AS haber,
          'general'             AS tipo
     FROM partida_detalle pd
     JOIN partidas_contables pc
       ON pc.id = pd.partida_id
    WHERE pd.cuenta_id = ?)
  UNION ALL
  (SELECT pcc.created_at        AS fecha,
          pccd.partida_id       AS no_partida,
          pccd.debe             AS debe,
          pccd.haber            AS haber,
          'compras'             AS tipo
     FROM partidas_contables_compras_detalle pccd
     JOIN partidas_contables_compras pcc
       ON pcc.id = pccd.partida_id
    WHERE pccd.cuenta_id = ?)
  UNION ALL
  (SELECT pcv.fecha             AS fecha,
          pdv.partida_id        AS no_partida,
          pdv.debe              AS debe,
          pdv.haber             AS haber,
          'ventas'              AS tipo
     FROM partida_detalle_ventas pdv
     JOIN partidas_contables_ventas pcv
       ON pcv.id = pdv.partida_id
    WHERE pdv.cuenta_id = ?)
  UNION ALL
  (SELECT pcp.created_at        AS fecha,
          pdp.partida_id        AS no_partida,
          pdp.debe              AS debe,
          pdp.haber             AS haber,
          'planilla'            AS tipo
     FROM partida_detalle_planilla pdp
     JOIN partidas_contables_planilla pcp
       ON pcp.id = pdp.partida_id
    WHERE pdp.cuenta_id = ?)
  ORDER BY fecha ASC, no_partida ASC
";

$rows = [];
$totDebe = 0.0;
$totHaber = 0.0;

if ($stmt = $conn->prepare($sql)) {
  // Misma cuenta_id para cada subconsulta
  $stmt->bind_param("iiii", $cuentaId, $cuentaId, $cuentaId, $cuentaId);
  $stmt->execute();
  $res = $stmt->get_result();
  while ($r = $res->fetch_assoc()) {
    // Normalizar tipos numéricos
    $r['debe']  = (float)$r['debe'];
    $r['haber'] = (float)$r['haber'];
    $totDebe  += $r['debe'];
    $totHaber += $r['haber'];
    $rows[] = $r;
  }
  $stmt->close();
}

// Helper para formateo
function money($n) { return $n == 0 ? '—' : number_format($n, 2, '.', ','); }
function fdate($s) {
  // Acepta timestamp/datetime
  // Formato: dd/mm/yyyy hh:mm
  $ts = strtotime($s);
  if ($ts === false) return htmlspecialchars($s, ENT_QUOTES);
  return date('d/m/Y H:i', $ts);
}

$syncOk = isset($_GET['sync']) && $_GET['sync'] === 'ok';
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Saldos de la cuenta</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Si el parent ya tiene Bootstrap, no es obligatorio incluirlo aquí.
       Este CSS pequeño es para asegurar legibilidad dentro del iframe. -->
  <style>
    body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin: 0; }
    .wrap { padding: 16px; }
    .title { font-size: 18px; font-weight: 600; margin-bottom: 12px; display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 8px 10px; border-bottom: 1px solid #e5e7eb; text-align: left; }
    th { background: #f3f4f6; font-weight: 600; }
    td.num { text-align: right; font-variant-numeric: tabular-nums; }
    tfoot td { font-weight: 700; background: #fafafa; }
    .muted { color: #6b7280; font-size: 12px; }
    .pill { display:inline-block; font-size:11px; padding:2px 6px; border:1px solid #e5e7eb; border-radius:10px; color:#6b7280; }
    .empty { padding: 16px; color:#6b7280; }
    .btn { appearance:none; border:1px solid #d1d5db; background:#fff; padding:6px 10px; border-radius:8px; cursor:pointer; }
    .btn:hover { background:#f9fafb; }
    .alert { padding:10px 12px; background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; border-radius:8px; margin:0 0 12px; }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="title">
      <div>
        Saldos — Cuenta #<?= (int)$cuentaId ?> — <?= htmlspecialchars($cuentaNombre, ENT_QUOTES) ?>
        <span class="pill">acumulado</span>
      </div>
      <form method="post" action="guardar_libro_mayor.php" style="margin-left:auto">
        <input type="hidden" name="cuenta_id" value="<?= (int)$cuentaId ?>">
        <button type="submit" class="btn">Agregar/Actualizar en Libro Mayor</button>
      </form>
    </div>

    <?php if ($syncOk): ?>
      <div class="alert">¡Listo! Se sincronizó esta cuenta con el Libro Mayor.</div>
    <?php endif; ?>

    <?php if (empty($rows)): ?>
      <div class="empty">No hay partidas registradas para esta cuenta.</div>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Fecha</th>
            <th>No. Partida</th>
            <th class="num">Debe</th>
            <th class="num">Haber</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= fdate($r['fecha']) ?> <span class="muted">(<?= htmlspecialchars($r['tipo']) ?>)</span></td>
              <td><?= (int)$r['no_partida'] ?></td>
              <td class="num"><?= money($r['debe']) ?></td>
              <td class="num"><?= money($r['haber']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="2">Totales</td>
            <td class="num"><?= money($totDebe) ?></td>
            <td class="num"><?= money($totHaber) ?></td>
          </tr>
        </tfoot>
      </table>
      <div class="muted" style="margin-top:8px">
        * Ordenado por fecha ascendente. Origen entre paréntesis: general, compras, ventas, planilla.
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
