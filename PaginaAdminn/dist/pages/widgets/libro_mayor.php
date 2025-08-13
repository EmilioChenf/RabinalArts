<?php
// factura_compras.php
include 'conexion.php';

// 1) Si se pasa compra_id por GET, cargamos esa compra interna
$compra_info = [];
if (isset($_GET['compra_id'])) {
    $cid = (int)$_GET['compra_id'];
    $stmt = $conn->prepare("SELECT * FROM compras_internas WHERE id = ?");
    $stmt->bind_param("i", $cid);
    $stmt->execute();
    $compra_info = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
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
<?php
// libro_diario.php — Libro Mayor con filtro de fechas y todas las cuentas
date_default_timezone_set('America/Guatemala');
include 'conexion.php';

// Helpers
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function q($n){ return number_format((float)$n, 2, '.', ','); }
function fecha_es_corta($dt){
  static $mes = [1=>'ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];
  $ts = strtotime($dt);
  if ($ts === false) return h($dt);
  $d  = (int)date('d', $ts);
  $m  = (int)date('m', $ts);
  return sprintf('%02d-%s', $d, $mes[$m] ?? date('M', $ts));
}

// Params
$saldoInicial = isset($_GET['saldo_inicial']) ? (float)$_GET['saldo_inicial'] : 0.00;
$desdeRaw     = isset($_GET['desde']) ? trim($_GET['desde']) : '';
$hastaRaw     = isset($_GET['hasta']) ? trim($_GET['hasta']) : '';
$esFecha      = fn($s)=> (bool)preg_match('/^\d{4}-\d{2}-\d{2}$/', $s);
$desdeOk      = $esFecha($desdeRaw) ? $desdeRaw : '';
$hastaOk      = $esFecha($hastaRaw) ? $hastaRaw : '';
$desdeDT      = $desdeOk ? $desdeOk.' 00:00:00' : '1970-01-01 00:00:00';
$hastaDT      = $hastaOk ? $hastaOk.' 23:59:59' : '9999-12-31 23:59:59';

// Catálogo de cuentas (todas)
$cuentasCatalogo = [];
$res = $conn->query("SELECT id, nombre FROM cuentas_contables ORDER BY nombre");
if ($res) {
  while ($row = $res->fetch_assoc()) $cuentasCatalogo[] = $row;
  $res->free();
}

// Selección de cuentas (si hay fechas, forzamos TODAS)
$seleccion     = isset($_GET['cuentas']) && is_array($_GET['cuentas']) ? $_GET['cuentas'] : [];
$selIds        = array_values(array_unique(array_filter(array_map('intval', $seleccion), fn($x)=>$x>0)));
if ($desdeOk && $hastaOk) {
  // Mostrar TODAS las cuentas y dejarlas preseleccionadas en el filtro
  $selIds = array_map(fn($c)=> (int)$c['id'], $cuentasCatalogo);
}

// Función: obtiene movimientos de libro_mayor por cuenta dentro del rango
function obtener_movimientos($conn, $cuentaId, $desdeDT, $hastaDT){
  $rows=[]; $totDebe=0.00; $totHaber=0.00;
  $sql = "SELECT id, fecha, partida_id, debe, haber, origen
            FROM libro_mayor
           WHERE cuenta_id = ? AND fecha BETWEEN ? AND ?
        ORDER BY fecha ASC, partida_id ASC, id ASC";
  if ($st = $conn->prepare($sql)){
    $st->bind_param("iss", $cuentaId, $desdeDT, $hastaDT);
    $st->execute();
    $r = $st->get_result();
    while ($x = $r->fetch_assoc()){
      $x['debe']  = (float)$x['debe'];
      $x['haber'] = (float)$x['haber'];
      $totDebe  += $x['debe'];
      $totHaber += $x['haber'];
      $rows[] = $x;
    }
    $st->close();
  }
  return [$rows,$totDebe,$totHaber];
}
?>
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
    body { font-family: 'Times New Roman', serif; font-size: 12px; background:#f6f7fb; }
    .app-main { background:transparent; }
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
    .toolbar .btn { border-radius:8px; }
    .logo { position:absolute; top:12px; right:12px; height:52px; }
    .desc-input { width:100%; border:0; background:transparent; outline:none; }
    .desc-input::placeholder { color:#9ca3af; }
    @media print {
      .no-print { display:none !important; }
      .lm-card { border:none; }
      body { background:#fff; }
      .desc-input { border:0; background:transparent; }
    }
  </style>
</head>
<body>
<main class="app-main p-4">

  <img src="../../../dist/assets/img/rabi.png" class="logo" alt="Logo">

  <div class="container-fluid">
    <!-- Filtros -->
    <div class="row g-3 align-items-end no-print mb-3">
      <div class="col-12">
        <form id="frmFiltro" class="row g-2">
          <div class="col-sm-3">
            <label class="form-label"><strong>Desde</strong></label>
            <input type="date" name="desde" id="desde" class="form-control" value="<?= h($desdeOk) ?>">
          </div>
          <div class="col-sm-3">
            <label class="form-label"><strong>Hasta</strong></label>
            <input type="date" name="hasta" id="hasta" class="form-control" value="<?= h($hastaOk) ?>">
          </div>
          <div class="col-sm-6">
            <label class="form-label"><strong>Cuentas contables</strong> (múltiple)</label>
            <select id="cuentas" name="cuentas[]" class="form-select" multiple
                    data-placeholder="Selecciona una o más cuentas">
              <?php foreach ($cuentasCatalogo as $c): ?>
                <option value="<?= (int)$c['id'] ?>"
                  <?= in_array($c['id'], $selIds, true) ? 'selected':'' ?>>
                  <?= h($c['nombre']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-sm-3">
            <label class="form-label">Saldo inicial (opcional)</label>
            <input type="number" name="saldo_inicial" id="saldo_inicial" step="0.01"
                   class="form-control" value="<?= q($saldoInicial) ?>">
          </div>
          <div class="col-sm-9 d-flex align-items-end gap-2">
            <button class="btn btn-primary">Ver</button>
            <button type="button" class="btn btn-outline-secondary" id="btnSelTodas">Seleccionar todas</button>
            <button type="button" class="btn btn-outline-dark" onclick="window.print()">🖨️ Imprimir</button>
          </div>
        </form>
        <div class="form-text">
          * Si eliges un rango de fechas, se mostrarán **todas** las cuentas automáticamente.
        </div>
      </div>
    </div>

    <!-- Encabezado general -->
    <div class="lm-header mb-2">
      <h2>RABINALARTS</h2>
      <h3>LIBRO MAYOR</h3>
      <div class="lm-sub">Cifras expresadas en Quetzales (Q)</div>
      <?php if ($desdeOk || $hastaOk): ?>
        <div class="lm-sub">Período: <?= h($desdeOk?:'—') ?> a <?= h($hastaOk?:'—') ?></div>
      <?php endif; ?>
    </div>

    <?php if (empty($selIds)): ?>
      <div class="alert alert-info">Selecciona un período o una o más cuentas para mostrar su Libro Mayor.</div>
    <?php else: ?>
      <?php foreach ($selIds as $cid):
        // nombre de cuenta
        $nombreCuenta = '';
        if ($st = $conn->prepare("SELECT nombre FROM cuentas_contables WHERE id=?")){
          $st->bind_param("i", $cid);
          $st->execute();
          $st->bind_result($nombreCuenta);
          $st->fetch();
          $st->close();
        }
        if ($nombreCuenta === '') {
          echo '<div class="alert alert-warning">La cuenta #'.(int)$cid.' no existe.</div>';
          continue;
        }
        // movimientos del rango
        [$movs,$totDebe,$totHaber] = obtener_movimientos($conn, $cid, $desdeDT, $hastaDT);
        $saldo = (float)$saldoInicial;
      ?>
      <div class="lm-card p-3">
        <div class="d-flex justify-content-between align-items-center my-2 no-print">
          <div><strong>Cuenta:</strong> <?= h($nombreCuenta) ?></div>
          <div class="d-flex gap-2 align-items-center">
            <div class="folio">FOLIO: <?= (int)$cid ?></div>
            <form method="post" action="guardar_libro_mayor.php" class="d-inline">
              <input type="hidden" name="cuenta_id" value="<?= (int)$cid ?>">
              <button type="submit" class="btn btn-outline-secondary btn-sm">🔄 Sincronizar cuenta</button>
            </form>
          </div>
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
              <?php
              // Saldo inicial (si aplica)
              if ($saldoInicial != 0){
                // Si se filtró por fecha, ponemos la fecha "desde" como referencia del saldo
                $fechaSI = $desdeOk ? $desdeOk : date('Y-m-01');
                echo '<tr>'.
                     '<td>'.h(fecha_es_corta($fechaSI)).'</td>'.
                     '<td><span class="ref-pill">—</span></td>'.
                     '<td><input class="desc-input" value="Saldo inicial"></td>'.
                     '<td class="num">—</td>'.
                     '<td class="num">—</td>'.
                     '<td class="num"><strong>'.q($saldo).'</strong></td>'.
                     '</tr>';
              }

              if (empty($movs)){
                echo '<tr><td colspan="6" class="text-center text-muted">No hay movimientos en el Libro Mayor para esta cuenta en el período seleccionado.</td></tr>';
              } else {
                foreach ($movs as $mv){
                  // descripción editable sugerida
                  switch ($mv['origen']){
                    case 'compras':  $sugerido = 'Compras'; break;
                    case 'ventas':   $sugerido = 'Ventas';  break;
                    case 'planilla': $sugerido = 'Planilla';break;
                    default:         $sugerido = 'Partida general';
                  }
                  $saldo = $saldo + $mv['debe'] - $mv['haber'];
                  echo '<tr>'.
                       '<td>'.h(fecha_es_corta($mv['fecha'])).'</td>'.
                       '<td><span class="ref-pill">'.(int)$mv['partida_id'].'</span></td>'.
                       '<td><input class="desc-input" placeholder="Escribe la descripción…" value="'.h($sugerido).'"></td>'.
                       '<td class="num">'.($mv['debe']>0?q($mv['debe']):'').'</td>'.
                       '<td class="num">'.($mv['haber']>0?q($mv['haber']):'').'</td>'.
                       '<td class="num"><strong>'.q($saldo).'</strong></td>'.
                       '</tr>';
                }
              }
              ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3">Totales</td>
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
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function(){
  // Select2
  $('#cuentas').select2({
    theme: 'bootstrap-5',
    width: '100%',
    placeholder: $('#cuentas').data('placeholder') || 'Selecciona una o más cuentas'
  });

  // Botón "Seleccionar todas"
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
