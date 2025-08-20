<?php
// factura_planilla.php
include 'conexion.php';

// ===== Entrada por mes (YYYY-MM) =====
$periodo_mes = isset($_GET['periodo_mes']) ? trim($_GET['periodo_mes']) : '';
$planillas = [];
$tot = ['sueldos'=>0.0, 'bonif'=>0.0, 'liquido'=>0.0];

if ($periodo_mes !== '' && preg_match('/^\d{4}-\d{2}$/', $periodo_mes)) {
    $start = $periodo_mes . '-01';
    // fin exclusivo = primer día del mes siguiente
    $end   = date('Y-m-d', strtotime($start . ' +1 month'));

    $stmt = $conn->prepare("
        SELECT id, nombre, puesto, sueldo_base, bonificacion, liquido_recibir, fecha_registro
          FROM planilla
         WHERE fecha_registro >= ? AND fecha_registro < ?
         ORDER BY fecha_registro, nombre
    ");
    $stmt->bind_param('ss', $start, $end);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $planillas[] = $row;
        $tot['sueldos']  += (float)$row['sueldo_base'];
        $tot['bonif']    += (float)$row['bonificacion'];
        $tot['liquido']  += (float)$row['liquido_recibir'];
    }
    $stmt->close();
}

// ===== Cálculos agregados del mes =====
// (Todos con base en TOTAL LIQUIDADO del mes, como pediste)
$igssLab   = round($tot['liquido'] * 0.0483, 2);
$patronal  = round($tot['liquido'] * 0.1267, 2);
$baseISR   = ($tot['liquido'] - 4000.00) - $igssLab;  // regla solicitada
$isr       = round(max($baseISR, 0) * 0.05, 2);

// DEBE = Sueldos + Bonif + Cuota patronal (como gasto)
$totalDebe = round($tot['sueldos'] + $tot['bonif'] + $patronal, 2);

// HABER parcial = IGSS laboral + Patronales por pagar + ISR por pagar
$otrosHaber = round($igssLab + $patronal + $isr, 2);

// HABER Bancos/Caja (neto a pagar)
$bancosCaja = round($totalDebe - $otrosHaber, 2);
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
                  



          
                    <!--end::Sidebar Menu              <li class="nav-item">
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
  <div class="container">

    <!-- Logo -->
    <div style="position: relative;">
      <img src="../../../dist/assets/img/rabi.png"
           alt="Logo Rabinalarts"
           style="position: absolute; top: 0; right: 0; height: 60px;">
    </div>

    <h1 class="mb-4">Planillas por Mes</h1>

    <div class="mb-3">
      <h4 class="fw-bold">RABINALARTS</h4>
      <p><strong>Fecha:</strong> <?= date("Y-m-d") ?></p>
    </div>

    <!-- Buscar por mes -->
    <form method="GET" class="row g-3 mb-4">
      <div class="col-md-4">
        <label for="periodo_mes" class="form-label">Mes</label>
        <input type="month" name="periodo_mes" id="periodo_mes" class="form-control"
               value="<?= htmlspecialchars($periodo_mes ?: date('Y-m')) ?>" required>
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-dark w-100">Buscar</button>
      </div>
    </form>

    <?php if ($periodo_mes && count($planillas) > 0): ?>
      <!-- Resumen del mes -->
      <div class="row g-3 mb-3">
        <div class="col-md-3">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <div class="fw-bold">Total Sueldos</div>
              <div>Q<?= number_format($tot['sueldos'], 2) ?></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <div class="fw-bold">Total Bonificaciones</div>
              <div>Q<?= number_format($tot['bonif'], 2) ?></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <div class="fw-bold">Total Liquidado (base cálculos)</div>
              <div>Q<?= number_format($tot['liquido'], 2) ?></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <div class="fw-bold">Registros del mes</div>
              <div><?= count($planillas) ?></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabla de planillas del mes -->
      <div class="mb-4">
        <h5>Detalle de Planillas del mes <?= htmlspecialchars($periodo_mes) ?></h5>
        <table class="table table-bordered table-sm bg-light">
          <thead class="table-secondary">
            <tr>
              <th>#</th>
              <th>Empleado</th>
              <th>Puesto</th>
              <th>Sueldo base</th>
              <th>Bonificación</th>
              <th>Liquido recibir</th>
              <th>Fecha</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($planillas as $i => $p): ?>
            <tr>
              <td><?= $i+1 ?></td>
              <td><?= htmlspecialchars($p['nombre']) ?></td>
              <td><?= htmlspecialchars($p['puesto']) ?></td>
              <td>Q<?= number_format($p['sueldo_base'], 2) ?></td>
              <td>Q<?= number_format($p['bonificacion'], 2) ?></td>
              <td>Q<?= number_format($p['liquido_recibir'], 2) ?></td>
              <td><?= htmlspecialchars($p['fecha_registro']) ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="3" class="text-end">Totales:</th>
              <th>Q<?= number_format($tot['sueldos'], 2) ?></th>
              <th>Q<?= number_format($tot['bonif'], 2) ?></th>
              <th>Q<?= number_format($tot['liquido'], 2) ?></th>
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>

      <!-- Vista previa de la partida agregada -->
      <div class="mb-3">
        <h5>Previa de Partida (mensual agregada)</h5>
        <table class="table table-bordered table-sm">
          <thead class="table-light">
            <tr><th>Cuenta</th><th class="text-end">Debe</th><th class="text-end">Haber</th></tr>
          </thead>
          <tbody>
            <tr><td>Sueldos</td><td class="text-end">Q<?= number_format($tot['sueldos'],2) ?></td><td class="text-end">Q0.00</td></tr>
            <tr><td>Bonificaciones</td><td class="text-end">Q<?= number_format($tot['bonif'],2) ?></td><td class="text-end">Q0.00</td></tr>
            <tr><td>Cuota Patronal Sueldos (12.67%)</td><td class="text-end">Q<?= number_format($patronal,2) ?></td><td class="text-end">Q0.00</td></tr>

            <tr><td>Cuota Laboral IGSS por Pagar (4.83%)</td><td class="text-end">Q0.00</td><td class="text-end">Q<?= number_format($igssLab,2) ?></td></tr>
            <tr><td>Cuotas Patronales por Pagar</td><td class="text-end">Q0.00</td><td class="text-end">Q<?= number_format($patronal,2) ?></td></tr>
            <tr><td>ISR por Pagar sobre Sueldos</td><td class="text-end">Q0.00</td><td class="text-end">Q<?= number_format($isr,2) ?></td></tr>
            <tr><td>Bancos/Caja (neto a pagar)</td><td class="text-end">Q0.00</td><td class="text-end">Q<?= number_format($bancosCaja,2) ?></td></tr>
          </tbody>
          <tfoot class="table-secondary">
            <tr>
              <th>Total</th>
              <th class="text-end">Q<?= number_format($totalDebe,2) ?></th>
              <th class="text-end">Q<?= number_format($otrosHaber + $bancosCaja,2) ?></th>
            </tr>
          </tfoot>
        </table>
      </div>

      <!-- Controles para generar partida -->
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label">Medio de pago</label>
          <select id="medio_pago" class="form-select">
            <option value="Bancos">Bancos</option>
            <option value="Caja">Caja</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Descripción (opcional)</label>
          <input id="desc_partida" class="form-control"
                 value="<?= 'Planilla mensual '.$periodo_mes ?>">
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <button type="button" id="btnPartidaAuto" class="btn btn-primary w-100">
            ⚡ Generar Partida del Mes
          </button>
        </div>
      </div>

      <div id="exportBtnContainer" class="mt-3"></div>

      <script>
        document.getElementById('btnPartidaAuto')?.addEventListener('click', async function(){
          const periodo = <?= json_encode($periodo_mes) ?>;
          const medio   = document.getElementById('medio_pago').value;
          const desc    = document.getElementById('desc_partida').value || '';

          try {
            const resp = await fetch('generar_partida_planilla_auto.php', {
              method: 'POST',
              headers: {'Content-Type':'application/json'},
              body: JSON.stringify({
                modo: 'mes',
                periodo: periodo,       // YYYY-MM
                medio_pago: medio,
                descripcion: desc
              })
            });
            const data = await resp.json();
            if (!data.success) throw new Error(data.message || 'Error desconocido');

            alert('Partida creada (#'+data.partida_id+').');
            document.getElementById('exportBtnContainer').innerHTML =
              `<a href="exportar_partida_planilla_pdf.php?partida_id=${data.partida_id}"
                  target="_blank" class="btn btn-danger">📄 Exportar Partida PDF (#${data.partida_id})</a>`;
          } catch(e) {
            alert('No se pudo generar: ' + e.message);
          }
        });
      </script>

    <?php elseif ($periodo_mes !== ''): ?>
      <div class="alert alert-warning">No hay planillas registradas en <?= htmlspecialchars($periodo_mes) ?>.</div>
    <?php endif; ?>
  </div>
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
