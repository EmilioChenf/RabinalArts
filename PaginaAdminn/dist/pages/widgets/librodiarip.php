<?php
// libro_diario.php (o reemplaza tu venta_factura.php si es el Libro Diario)
session_start();
include 'conexion.php';

function validDate($d){ return preg_match('/^\d{4}-\d{2}-\d{2}$/',$d)?$d:date('Y-m-d'); }
function money($n){ return number_format((float)$n,2); }
function ymFirst($ym){ return $ym.'-01 00:00:00'; }
function nextYmFirst($ym){ return date('Y-m-01 00:00:00', strtotime("$ym-01 +1 month")); }

// --- config de filtros
$from = validDate($_GET['from'] ?? date('Y-m-01'));
$to   = validDate($_GET['to']   ?? date('Y-m-d'));
$from_sql = $conn->real_escape_string($from);
$to_sql   = $conn->real_escape_string($to);

// --- helpers para cuentas de saldo inicial
function hayFlagMostrarSaldo($conn){
  $q = $conn->query("SHOW COLUMNS FROM cuentas_contables LIKE 'mostrar_saldo_inicial'");
  return $q && $q->num_rows>0;
}
function cuentaPorSlug($conn,$slug){
  $st=$conn->prepare("SELECT c.id,c.nombre FROM param_cuentas p JOIN cuentas_contables c ON c.id=p.cuenta_id WHERE p.slug=? LIMIT 1");
  $st->bind_param('s',$slug); $st->execute();
  $r=$st->get_result()->fetch_assoc(); $st->close();
  return $r?:null;
}
function cuentasSaldoInicial($conn){
  $out=[];
  if (hayFlagMostrarSaldo($conn)){
    $q=$conn->query("SELECT id,nombre FROM cuentas_contables WHERE mostrar_saldo_inicial=1 ORDER BY nombre");
    while($r=$q->fetch_assoc()) $out[]=['id'=>(int)$r['id'],'nombre'=>$r['nombre']];
    if(!empty($out)) return $out;
  }
  // fallback: solo Caja del param_cuentas
  if ($c=cuentaPorSlug($conn,'caja')) $out[]=['id'=>(int)$c['id'],'nombre'=>$c['nombre']];
  return $out;
}
function saldoInicial($conn,$cuentaId,$inicioMesTS){
  $st=$conn->prepare("SELECT COALESCE(SUM(debe-haber),0) s FROM libro_mayor WHERE cuenta_id=? AND fecha < ?");
  $st->bind_param('is',$cuentaId,$inicioMesTS);
  $st->execute(); $s=(float)$st->get_result()->fetch_assoc()['s']; $st->close(); return $s;
}

// --- meses cubiertos
$meses=[]; $cur=new DateTime($from); $cur->modify('first day of this month');
$end=new DateTime($to); $end->modify('first day of next month');
while($cur<$end){ $ym=$cur->format('Y-m'); $meses[]=['ym'=>$ym,'ini'=>ymFirst($ym),'ini_next'=>nextYmFirst($ym)]; $cur->modify('+1 month'); }

// --- partidas reales (compras, ventas, planilla, generales + aperturas)
$sql = "
  SELECT 'compras' origen, p.id partida_id, p.created_at fecha, p.descripcion,
         d.cuenta_id, c.nombre cuenta, d.debe, d.haber
  FROM partidas_contables_compras p
  JOIN partidas_contables_compras_detalle d ON d.partida_id=p.id
  JOIN cuentas_contables c ON c.id=d.cuenta_id
  WHERE DATE(p.created_at) BETWEEN '$from_sql' AND '$to_sql'

  UNION ALL
  SELECT 'ventas', p.id, p.fecha, p.descripcion,
         d.cuenta_id, c.nombre, d.debe, d.haber
  FROM partidas_contables_ventas p
  JOIN partida_detalle_ventas d ON d.partida_id=p.id
  JOIN cuentas_contables c ON c.id=d.cuenta_id
  WHERE DATE(p.fecha) BETWEEN '$from_sql' AND '$to_sql'

  UNION ALL
  SELECT 'planilla', p.id, p.created_at, p.descripcion,
         d.cuenta_id, c.nombre, d.debe, d.haber
  FROM partidas_contables_planilla p
  JOIN partida_detalle_planilla d ON d.partida_id=p.id
  JOIN cuentas_contables c ON c.id=d.cuenta_id
  WHERE DATE(p.created_at) BETWEEN '$from_sql' AND '$to_sql'

  UNION ALL
  SELECT IF(p.tipo='apertura','apertura','general') origen, p.id, p.fecha, p.descripcion,
         d.cuenta_id, c.nombre, d.debe, d.haber
  FROM partidas_contables p
  JOIN partida_detalle d ON d.partida_id=p.id
  JOIN cuentas_contables c ON c.id=d.cuenta_id
  WHERE DATE(p.fecha) BETWEEN '$from_sql' AND '$to_sql'
";
$sql .= " ORDER BY fecha, origen, partida_id";
$res = $conn->query($sql) or die("Error diario: ".$conn->error);

// --- agrupar por mes y partida
$realesPorMes=[]; $hayAperturaMes=[];
while($r=$res->fetch_assoc()){
  $ym = date('Y-m', strtotime($r['fecha']));
  $key = $r['origen'].':'.$r['partida_id'];
  if (!isset($realesPorMes[$ym][$key])){
    $realesPorMes[$ym][$key]=['fecha'=>$r['fecha'],'descripcion'=>$r['descripcion'],'origen'=>$r['origen'],'lines'=>[]];
  }
  $realesPorMes[$ym][$key]['lines'][]=[
    'cuenta_id'=>(int)$r['cuenta_id'],
    'cuenta'=>$r['cuenta'],
    'debe'=>(float)$r['debe'],
    'haber'=>(float)$r['haber'],
  ];
  if ($r['origen']==='apertura') $hayAperturaMes[$ym]=true;
}

// --- construir diario final (insertando saldo inicial si NO hay apertura)
$cuentasSaldo = cuentasSaldoInicial($conn);
$diario=[];

foreach($meses as $m){
  $ym = $m['ym'];
  $partidas=[];
  $inicioMes = $m['ini'];

  if (empty($hayAperturaMes[$ym])) {
    // Insertar renglones de “saldo inicial” por cuenta (presentación)
    foreach($cuentasSaldo as $cta){
      $si = saldoInicial($conn, $cta['id'], $inicioMes);
      if (abs($si) < 0.005) continue;
      $debe  = $si>=0 ? $si : 0;
      $haber = $si<0  ? -$si: 0;
      $partidas['saldo:'.$ym.':'.$cta['id']] = [
        'fecha'=>$inicioMes,
        'descripcion'=>'Saldo inicial de '.$cta['nombre'],
        'origen'=>'saldo',
        'lines'=>[[ 'cuenta_id'=>$cta['id'], 'cuenta'=>$cta['nombre'], 'debe'=>$debe, 'haber'=>$haber ]]
      ];
    }
  }

  if (!empty($realesPorMes[$ym])){
    uasort($realesPorMes[$ym], function($a,$b){
      $ta=strtotime($a['fecha']); $tb=strtotime($b['fecha']); return $ta<=>$tb;
    });
    foreach($realesPorMes[$ym] as $k=>$p){ $partidas[$k]=$p; }
  }

  if (!empty($partidas)) $diario[$ym]=$partidas;
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
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Libro Diario</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body{ font-family:'Times New Roman',serif; font-size:12px; }
    .libro-diario{ border:2px solid #000; background:#fff; margin-top:20px; }
    .libro-header{ text-align:center; padding:15px; border-bottom:2px solid #000; font-weight:bold; background:#f8f9fa; }
    .partida-row{ border-bottom:1px solid #000; min-height:40px; display:flex; }
    .partida-number{ border-right:2px solid #000; width:60px; padding:8px 5px; text-align:center; font-weight:bold; background:#f8f9fa; display:flex; align-items:flex-start; justify-content:center; }
    .partida-fecha-header{ font-weight:bold; text-align:center; margin-bottom:8px; font-size:12px; }
    .partida-content{ padding:8px 10px; flex:1; }
    .cuenta-row{ margin:2px 0; display:flex; align-items:center; }
    .cuenta-numero{ display:inline-block; width:20px; text-align:center; font-weight:bold; margin-right:10px; }
    .cuenta-nombre{ text-transform:uppercase; font-weight:bold; }
    .cuenta-descripcion{ margin:8px 0; margin-left:20px; font-size:11px; font-style:italic; color:#666; line-height:1.3; font-weight:bold; }
    .amounts-column, .amounts-column-haber{ width:120px; border-left:1px solid #000; padding:8px 5px; text-align:right; }
    .amount-cell{ margin:2px 0; min-height:18px; display:flex; justify-content:space-between; align-items:center; }
    .currency{ margin-right:5px; }
    .final-totals{ border:1px solid #000; background:#e9ecef; text-align:right; font-weight:bold; font-size:12px; padding:5px; margin-top:5px; }
  </style>
</head>
<body class="p-4">
  <div class="container-fluid">
    <div style="position:relative;">
      <img src="../../../dist/assets/img/rabi.png" alt="Logo" style="position:absolute;top:0;right:0;height:60px;">
    </div>
    <h1 class="mb-4">Gestión de Movimientos Contables</h1>

    <form method="GET" class="row g-3 align-items-end mb-4">
      <div class="col-md-3">
        <label class="form-label">Desde</label>
        <input type="date" name="from" class="form-control" value="<?= htmlspecialchars($from) ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Hasta</label>
        <input type="date" name="to" class="form-control" value="<?= htmlspecialchars($to) ?>">
      </div>
      <div class="col-md-2"><button class="btn btn-primary w-100"><i class="bi bi-funnel-fill"></i> Filtrar</button></div>
    </form>

    <?php if (empty($diario)): ?>
      <div class="alert alert-info">No hay partidas en este rango de fechas.</div>
    <?php else: ?>
      <div class="libro-diario">
        <div class="libro-header">
          <div style="font-size:14px;font-weight:bold;">LIBRO DIARIO</div>
          <div style="font-size:12px;">RabinalArts, del <?= date('d/m/Y', strtotime($from)) ?> al <?= date('d/m/Y', strtotime($to)) ?></div>
          <div style="font-size:11px;">(Cifras en Quetzales)</div>
        </div>

        <?php
        $contador=1;
        foreach ($meses as $mInfo):
          $ym = $mInfo['ym'];
          if (empty($diario[$ym])) continue;

          echo '<div class="p-2" style="border-bottom:2px solid #000;background:#f5f5f5;font-weight:bold;">'.strftime('%B %Y', strtotime($ym.'-01')).'</div>';

          foreach ($diario[$ym] as $section):
            $sumDebe=0;$sumHaber=0; foreach($section['lines'] as $ln){ $sumDebe+=$ln['debe']; $sumHaber+=$ln['haber']; }
        ?>
          <div class="partida-row">
            <div class="partida-number">Pda<?= $contador ?></div>
            <div class="partida-content">
              <div class="partida-fecha-header"><?= date('d/m/Y', strtotime($section['fecha'])) ?></div>
              <?php $i=1; foreach($section['lines'] as $ln): ?>
                <div class="cuenta-row">
                  <span class="cuenta-numero"><?= $i ?></span>
                  <span class="cuenta-nombre"><?= htmlspecialchars($ln['cuenta']) ?></span>
                </div>
              <?php $i++; endforeach; ?>
              <div class="cuenta-descripcion"><?= htmlspecialchars($section['descripcion']) ?>:</div>
            </div>
            <div class="amounts-column">
              <div class="amount-cell" style="height:20px;"></div>
              <?php foreach($section['lines'] as $ln): ?>
                <div class="amount-cell">
                  <?php if ($ln['debe']>0): ?><span class="currency">Q</span><span><?= money($ln['debe']) ?></span><?php else:?><span></span><span></span><?php endif; ?>
                </div>
              <?php endforeach; ?>
              <div class="final-totals">Q <?= money($sumDebe) ?></div>
            </div>
            <div class="amounts-column-haber">
              <div class="amount-cell" style="height:20px;"></div>
              <?php foreach($section['lines'] as $ln): ?>
                <div class="amount-cell">
                  <?php if ($ln['haber']>0): ?><span class="currency">Q</span><span><?= money($ln['haber']) ?></span><?php else:?><span></span><span></span><?php endif; ?>
                </div>
              <?php endforeach; ?>
              <div class="final-totals">Q <?= money($sumHaber) ?></div>
            </div>
          </div>
        <?php
          $contador++;
          endforeach;
        endforeach;
        ?>
      </div>
    <?php endif; ?>

    <a href="exportar_libro_diario_pdf.php?from=<?= urlencode($from) ?>&to=<?= urlencode($to) ?>" class="btn btn-danger mt-3" target="_blank">
      <i class="bi bi-file-earmark-pdf"></i> Exportar Libro Diario (PDF)
    </a>
  </div>
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
