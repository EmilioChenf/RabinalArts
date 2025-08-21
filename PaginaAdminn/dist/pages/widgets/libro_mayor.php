<?php
// ===== Libro Mayor (replica la lógica del Libro Diario) =====
session_start();
include 'conexion.php';

function validDate($d){ return preg_match('/^\d{4}-\d{2}-\d{2}$/',$d)?$d:date('Y-m-d'); }
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function q($n){ return number_format((float)$n,2,'.',','); }
function fecha_corta($dt){
  static $mes = [1=>'ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];
  $ts = strtotime($dt);
  if ($ts === false) return h($dt);
  $d=(int)date('d',$ts); $m=(int)date('m',$ts);
  return sprintf('%02d-%s',$d,$mes[$m]??date('M',$ts));
}

// ---- filtros (mismo estilo que el diario) ----
$from = validDate($_GET['from'] ?? date('Y-m-01'));
$to   = validDate($_GET['to']   ?? date('Y-m-d'));

$from_sql = $conn->real_escape_string($from);
$to_sql   = $conn->real_escape_string($to);

// ---- Traer todos los movimientos (como en el Diario) ----
// NOTA: usamos una subconsulta X y filtramos afuera por DATE(fecha) para unificar.
$sqlMov = "
SELECT origen, partida_id, fecha, descripcion, cuenta_id, cuenta, debe, haber
FROM (
  SELECT 'compras' AS origen, p.id AS partida_id, p.created_at AS fecha, p.descripcion,
         d.cuenta_id, c.nombre AS cuenta, d.debe, d.haber
    FROM partidas_contables_compras p
    JOIN partidas_contables_compras_detalle d ON d.partida_id = p.id
    JOIN cuentas_contables c ON c.id = d.cuenta_id

  UNION ALL

  SELECT 'ventas', p.id, p.fecha, p.descripcion,
         d.cuenta_id, c.nombre, d.debe, d.haber
    FROM partidas_contables_ventas p
    JOIN partida_detalle_ventas d ON d.partida_id = p.id
    JOIN cuentas_contables c ON c.id = d.cuenta_id

  UNION ALL

  SELECT 'planilla', p.id, p.created_at, p.descripcion,
         d.cuenta_id, c.nombre, d.debe, d.haber
    FROM partidas_contables_planilla p
    JOIN partida_detalle_planilla d ON d.partida_id = p.id
    JOIN cuentas_contables c ON c.id = d.cuenta_id

  UNION ALL

  SELECT IF(p.tipo='apertura','apertura','general') AS origen, p.id, p.fecha, p.descripcion,
         d.cuenta_id, c.nombre, d.debe, d.haber
    FROM partidas_contables p
    JOIN partida_detalle d ON d.partida_id = p.id
    JOIN cuentas_contables c ON c.id = d.cuenta_id
) X
WHERE DATE(fecha) BETWEEN '$from_sql' AND '$to_sql'
ORDER BY fecha, origen, partida_id, cuenta_id
";

$resMov = $conn->query($sqlMov);
if (!$resMov) {
  die("Error obteniendo movimientos: ".$conn->error);
}

// Agrupamos por cuenta (Libro Mayor = 1 cuadro por cuenta)
$porCuenta = [];             // cuenta_id => ['nombre'=>..., 'movs'=>[]]
$hayAperturaEnRango = [];    // cuenta_id => bool (si hubo una partida 'apertura' en el rango)

while ($r = $resMov->fetch_assoc()) {
  $cid = (int)$r['cuenta_id'];
  if (!isset($porCuenta[$cid])) {
    $porCuenta[$cid] = ['nombre'=>$r['cuenta'], 'movs'=>[]];
  }
  $porCuenta[$cid]['movs'][] = [
    'fecha'      => $r['fecha'],
    'origen'     => $r['origen'],
    'partida_id' => (int)$r['partida_id'],
    'descripcion'=> (string)$r['descripcion'],
    'debe'       => (float)$r['debe'],
    'haber'      => (float)$r['haber'],
  ];
  if ($r['origen'] === 'apertura') {
    $hayAperturaEnRango[$cid] = true;
  }
}
$resMov->free();

// ---- Función para calcular saldo anterior a FROM (usando el mismo universo UNION) ----
function saldoAnteriorCuenta($conn, $cuentaId, $fromDate) {
  $s = 0.0;
  $sql = "
    SELECT
      COALESCE(SUM(debe),0) AS tot_debe,
      COALESCE(SUM(haber),0) AS tot_haber
    FROM (
      SELECT d.debe, d.haber, p.created_at AS fecha
        FROM partidas_contables_compras p
        JOIN partidas_contables_compras_detalle d ON d.partida_id=p.id
       WHERE d.cuenta_id = ? AND DATE(p.created_at) < ?
      UNION ALL
      SELECT d.debe, d.haber, p.fecha
        FROM partidas_contables_ventas p
        JOIN partida_detalle_ventas d ON d.partida_id=p.id
       WHERE d.cuenta_id = ? AND DATE(p.fecha) < ?
      UNION ALL
      SELECT d.debe, d.haber, p.created_at
        FROM partidas_contables_planilla p
        JOIN partida_detalle_planilla d ON d.partida_id=p.id
       WHERE d.cuenta_id = ? AND DATE(p.created_at) < ?
      UNION ALL
      SELECT d.debe, d.haber, p.fecha
        FROM partidas_contables p
        JOIN partida_detalle d ON d.partida_id=p.id
       WHERE d.cuenta_id = ? AND DATE(p.fecha) < ?
    ) U
  ";
  if ($st = $conn->prepare($sql)) {
  $st->bind_param('isisisis', $cuentaId,$fromDate,$cuentaId,$fromDate,$cuentaId,$fromDate,$cuentaId,$fromDate);
    // (ambas funcionan igual; depende de tu versión de PHP)
    if (!$st->execute()) { $st->close(); return 0.0; }
    $row = $st->get_result()->fetch_assoc();
    $st->close();
    $s = (float)$row['tot_debe'] - (float)$row['tot_haber'];
  }
  return $s;
}

// ---- Si quieres preseleccionar TODAS las cuentas cuando hay rango (como antes) ----
$catalogo = [];
$q = $conn->query("SELECT id, nombre FROM cuentas_contables ORDER BY nombre");
while($c = $q->fetch_assoc()) { $catalogo[(int)$c['id']] = $c['nombre']; }
$q->free();

// Si NO hubo movimientos en el rango, igual mostramos cuentas seleccionadas?:
$seleccion = isset($_GET['cuentas']) && is_array($_GET['cuentas']) ? array_map('intval', $_GET['cuentas']) : [];
if ($from && $to && empty($seleccion)) {
  // Si vienen fechas pero no selección, mostramos todas
  $seleccion = array_keys($catalogo);
}

// Si hay selección, filtramos $porCuenta por esos IDs; si no, lo dejamos tal cual
if (!empty($seleccion)) {
  $porCuenta = array_filter(
    $porCuenta,
    fn($cid) => in_array($cid, $seleccion, true),
    ARRAY_FILTER_USE_KEY
  );
}

// ---- Generar UI ----
?>

<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
     <link rel="stylesheet" href="/dist/css/adminlte.min.css">
  <!-- …otros CSS de bootstrap, plugins, etc… -->

  <!-- Aquí, **TU** CSS personalizado -->
  <link rel="stylesheet" href="../../../dist/css/custom.css"> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE 4 | Widgets - Small Box</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE 4 | Widgets - Small Box" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"
    />
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
      integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="../../../dist/css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>

          </ul>         
  

          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->
      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="../index.html" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src="../../../dist/assets/img/rabi.png"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">RabinalArts</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="menu"
              data-accordion="false"
            >




            <li class="nav-item menu-open">
                <a href="#" class="nav-link active">
                  <i class="nav-icon bi bi-box-seam-fill"></i>
                  <p>
                    Gestiones
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">

                  <li class="nav-item">
                    <a href="../widgets/proveedores.php" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Gestión de proveedores</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="../widgets/info-box.php" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Sistema contable</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="productos.php" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Gestión de Productos</p>
                    </a>
                  </li>


                  <li class="nav-item">
                    <a href="../widgets/compras.php" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>compras a proveedores</p>
                    </a>
                  </li>



                  <li class="nav-item">
                    <a href="../widgets/venta_factura.php" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Generar Facturas</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="../widgets/clientes_info.php" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Info Clientes</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="../widgets/planilla.php" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Planilla de sueldos</p>
                    </a>
                  </li>



  <li class="nav-item">
                    <a href="../widgets/empleados.php" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Gestion de empleados</p>
                    </a>
                  </li>


                                    <li class="nav-item">
                    <a href="../widgets/gestion_de_cuentas.php" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Gestion de cuentas</p>
                    </a>
                  </li>
                  



          
                                <!--   <li class="nav-item">
                    <a href="../widgets/inventario.php" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>inventario</p>
                    </a>
                  </li>


                  <li class="nav-item">
                    <a href="../widgets/clasificar_inventario.php" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Clasificación de inventario</p>
                    </a>
                  </li>-->
                                    <li class="nav-item">
                    <a href="../widgets/docuemnetación.php" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Registro de Compras (Internas)</p>
                    </a>
                  </li>



                                              <li class="nav-item">
                    <a href="../widgets/infro_registro_compras.php" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Infro Registro de Compras (Internas)</p>
                    </a>
                  </li>

                                              <li class="nav-item">
                    <a href="../widgets/factura_comrpas.php" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>IFactura de Compras (Internas)</p>
                    </a>
                  </li>


                  <li class="nav-item">
                    <a href="../widgets/movimientos_contables.php" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Movimientos contables y jornalizaciones</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="../widgets/factura_planilla.php" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Factura Planillas</p>
                    </a>
                  </li>
                  

      <li class="nav-item">
                    <a href="../widgets/librodiarip.php" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Libro Diario</p>
                    </a>
                  </li>



                   <li class="nav-item">
                    <a href="../widgets/libro_mayor.php" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Libro Mayor</p>
                    </a>
                  </li>




                  
                </ul>
              </li>


            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->
      <!--begin::App Main-->


      
<main class="app-main p-4">

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Libro Mayor</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet"/>
  <style>
    body { font-family: 'Times New Roman', serif; font-size: 12px; }
    .lm-card { background:#fff; border:1px solid #d1d5db; border-radius:10px; margin-bottom:18px; }
    .lm-header { text-align:center; padding:12px 8px 0 8px; }
    .lm-header h2 { font-size:18px; margin:0; font-weight:700; letter-spacing:.5px; }
    .lm-header h3 { font-size:14px; margin:2px 0 0; }
    .lm-sub { font-size:11px; color:#6b7280; margin-top:2px; }
    .table-ledger { width:100%; border-collapse:collapse; }
    .table-ledger th, .table-ledger td { border:1px solid #000; padding:6px 8px; vertical-align:middle; background:#fff; }
    .table-ledger thead th { background:#f1f5f9; text-align:center; font-weight:700; }
    .table-ledger tfoot td { background:#e9ecef; font-weight:700; }
    .num { text-align:right; font-variant-numeric: tabular-nums; }
    .folio { background:#eaf3ff; border:1px solid #93c5fd; padding:6px 10px; border-radius:8px; font-weight:600; }
    .ref-pill { font-size:11px; color:#6b7280; border:1px solid #d1d5db; padding:1px 6px; border-radius:999px; }
    .desc-input { width:100%; border:0; background:transparent; outline:none; }
    .desc-input::placeholder { color:#9ca3af; }
    .logo { position:absolute; top:12px; right:12px; height:52px; }
    @media print {
      .no-print { display:none !important; }
      .lm-card { border:none; }
      body { background:#fff; }
    }
  </style>
</head>
<body class="p-4">

  <img src="../../../dist/assets/img/rabi.png" class="logo" alt="Logo">

  <div class="container-fluid">
    <!-- Filtros -->
    <div class="row g-3 align-items-end no-print mb-3">
      <div class="col-12">
        <form method="GET" class="row g-2" id="frmFiltro">
          <div class="col-sm-3">
            <label class="form-label"><strong>Desde</strong></label>
            <input type="date" name="from" class="form-control" value="<?= h($from) ?>">
          </div>
          <div class="col-sm-3">
            <label class="form-label"><strong>Hasta</strong></label>
            <input type="date" name="to" class="form-control" value="<?= h($to) ?>">
          </div>
          <div class="col-sm-6">
            <label class="form-label"><strong>Cuentas contables</strong> (múltiple)</label>
            <select name="cuentas[]" id="cuentas" class="form-select" multiple data-placeholder="Selecciona una o más cuentas">
              <?php foreach ($catalogo as $id => $nom): ?>
                <option value="<?= (int)$id ?>" <?= in_array($id, $seleccion, true) ? 'selected':'' ?>>
                  <?= h($nom) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-sm-12 d-flex align-items-end gap-2">
            <button class="btn btn-primary">Ver</button>
            <button type="button" class="btn btn-outline-secondary" id="btnSelTodas">Seleccionar todas</button>
            <button type="button" class="btn btn-outline-dark" onclick="window.print()">🖨️ Imprimir</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Encabezado -->
    <div class="lm-header mb-2">
      <h2>RABINALARTS</h2>
      <h3>LIBRO MAYOR</h3>
      <div class="lm-sub">Cifras expresadas en Quetzales (Q)</div>
      <div class="lm-sub">Período: <?= h($from) ?> a <?= h($to) ?></div>
    </div>

    <?php if (empty($porCuenta) && empty($seleccion)): ?>
      <div class="alert alert-info">No hay movimientos en el rango. Elige un período y/o cuentas.</div>
    <?php else: ?>

      <?php
      // si hay selección pero no hubo movimientos, igual mostramos las cuentas vacías
      $cuentasParaMostrar = !empty($seleccion)
        ? array_values($seleccion)
        : array_keys($porCuenta);

      foreach ($cuentasParaMostrar as $cid):
        $cid = (int)$cid;
        $nombreCuenta = $catalogo[$cid] ?? ($porCuenta[$cid]['nombre'] ?? '');
        if ($nombreCuenta === '') continue;

        $movs = $porCuenta[$cid]['movs'] ?? [];
        // ordena por fecha/partida
        usort($movs, function($a,$b){
          $fa = strtotime($a['fecha']??'1970-01-01');
          $fb = strtotime($b['fecha']??'1970-01-01');
          if ($fa === $fb){
            if (($a['partida_id']??0) === ($b['partida_id']??0)) return 0;
            return ($a['partida_id']??0) <=> ($b['partida_id']??0);
          }
          return $fa <=> $fb;
        });

        // ¿Hubo apertura en el rango para esta cuenta?
        $tieneApertura = !empty($hayAperturaEnRango[$cid]);
        // si NO hubo apertura en el rango, calculamos saldo anterior y lo insertamos como “Saldo inicial”
        $saldoPrev = 0.0;
        if (!$tieneApertura) {
          $saldoPrev = saldoAnteriorCuenta($conn, $cid, $from);
        }

        // Totales del rango
        $totDebe = 0.0; $totHaber = 0.0;
        foreach ($movs as $m) { $totDebe += $m['debe']; $totHaber += $m['haber']; }

        // Saldo acumulado
        $saldo = $saldoPrev;
      ?>
      <div class="lm-card p-3">
        <div class="d-flex justify-content-between align-items-center my-2 no-print">
          <div><strong>Cuenta:</strong> <?= h($nombreCuenta) ?></div>
          <div class="folio">FOLIO: <?= (int)$cid ?></div>
        </div>

        <div class="table-responsive">
          <table class="table-ledger">
            <thead>
              <tr>
                <th style="width:90px;">Fecha</th>
                <th style="width:70px;">Ref.</th>
                <th>Descripción</th>
                <th class="num" style="width:120px;">Debe</th>
                <th class="num" style="width:120px;">Haber</th>
                <th class="num" style="width:120px;">Saldo</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$tieneApertura && abs($saldoPrev) > 0.004): ?>
                <tr>
                  <td><?= h(fecha_corta($from)) ?></td>
                  <td><span class="ref-pill">—</span></td>
                  <td><input class="desc-input" value="Saldo inicial"></td>
                  <td class="num">—</td>
                  <td class="num">—</td>
                  <td class="num"><strong><?= q($saldoPrev) ?></strong></td>
                </tr>
              <?php endif; ?>

              <?php if (empty($movs)): ?>
                <tr><td colspan="6" class="text-center text-muted">Sin movimientos en el rango.</td></tr>
              <?php else: ?>
                <?php foreach ($movs as $mv):
                  $saldo = $saldo + (float)$mv['debe'] - (float)$mv['haber'];
                  switch ($mv['origen']){
                    case 'compras':  $descSug = 'Compras'; break;
                    case 'ventas':   $descSug = 'Ventas'; break;
                    case 'planilla': $descSug = 'Planilla'; break;
                    case 'apertura': $descSug = 'Partida de apertura'; break;
                    default:         $descSug = 'Partida general';
                  }
                ?>
                  <tr>
                    <td><?= h(fecha_corta($mv['fecha'])) ?></td>
                    <td><span class="ref-pill"><?= (int)$mv['partida_id'] ?></span></td>
                    <td><input class="desc-input" value="<?= h($descSug) ?>"></td>
                    <td class="num"><?= $mv['debe'] > 0 ? q($mv['debe']) : '' ?></td>
                    <td class="num"><?= $mv['haber'] > 0 ? q($mv['haber']) : '' ?></td>
                    <td class="num"><strong><?= q($saldo) ?></strong></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3">Totales del período</td>
                <td class="num"><?= q($totDebe) ?></td>
                <td class="num"><?= q($totHaber) ?></td>
                <td class="num"><?= q($saldo) ?></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <?php endforeach; ?>

    <?php endif; ?>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(function(){
      $('#cuentas').select2({ theme:'bootstrap-5', width:'100%', placeholder: $('#cuentas').data('placeholder')||'Selecciona una o más cuentas' });
      $('#btnSelTodas').on('click', function(){
        const allVals = Array.from(document.querySelectorAll('#cuentas option')).map(o=>o.value);
        $('#cuentas').val(allVals).trigger('change');
      });
    });
  </script>
</body>
</html>
</main>

    <!--end::App Main-->
      <!--begin::Footer-->
      <footer class="app-footer">
        <!--begin::To the end-->
        <div class="float-end d-none d-sm-inline">Anything you want</div>
        <!--end::To the end-->
        <!--begin::Copyright-->
        <strong>
          Copyright &copy; 2014-2024&nbsp;
          <a href="https://adminlte.io" class="text-decoration-none"></a>.
        </strong>
        All rights reserved.
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="../../../dist/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure-->
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>
