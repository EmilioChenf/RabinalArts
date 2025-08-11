<?php
// factura_planilla.php
include 'conexion.php';

// 1) Si se pasa planilla_id por GET, cargamos esa planilla
$planilla_info = [];
if (isset($_GET['planilla_id'])) {
    $pid = (int)$_GET['planilla_id'];
    $stmt = $conn->prepare("
        SELECT
          id, nombre, puesto, sueldo_base, horas_extras, comisiones,
          bonificacion, anticipo, total_ingresos, isss, isr,
          descuentos_judiciales, otros_descuentos, total_descuentos,
          liquido_recibir, fecha_registro
        FROM planilla
        WHERE id = ?
    ");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $planilla_info = $stmt->get_result()->fetch_assoc();
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

    <!-- Logo en la esquina superior -->
    <div style="position: relative;">
      <img src="../../../dist/assets/img/rabi.png" 
           alt="Logo Rabinalarts" 
           style="position: absolute; top: 0; right: 0; height: 60px;">
    </div>





    <h1 class="mb-4">Detalle / Comprobante de Planilla</h1>

    <!-- Encabezado de empresa -->
    <div class="mb-3">
      <h4 class="fw-bold">RABINALARTS</h4>
      <p>
        <strong>Fecha:</strong> <?= date("Y-m-d") ?>
        | <strong>Folio:</strong>
        <?= isset($planilla_info['id'])
            ? str_pad($planilla_info['id'], 5, "0", STR_PAD_LEFT)
            : '----' ?>
      </p>
    </div>

    <!-- Form para buscar una planilla -->
    <form method="GET" class="row g-3 mb-4">
      <div class="col-md-3">
        <label for="planilla_id" class="form-label">ID de Planilla</label>
        <input
          type="number"
          name="planilla_id"
          id="planilla_id"
          class="form-control"
          required
        />
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-dark">Buscar Planilla</button>
      </div>
    </form>

    <?php if (!empty($planilla_info)): ?>
      <!-- Detalles de la planilla en tabla -->
      <div class="mb-4">
        <h5>Detalles de la Planilla</h5>
        <table class="table table-bordered bg-light">
          <thead class="table-secondary">
            <tr>
              <th>Campo</th>
              <th>Valor</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>ID</td><td><?= $planilla_info['id'] ?></td></tr>
            <tr><td>Empleado</td><td><?= htmlspecialchars($planilla_info['nombre']) ?></td></tr>
            <tr><td>Puesto</td><td><?= htmlspecialchars($planilla_info['puesto']) ?></td></tr>
            <tr><td>Sueldo base</td><td>Q<?= number_format($planilla_info['sueldo_base'], 2) ?></td></tr>
            <tr><td>Horas extras</td><td><?= (int)$planilla_info['horas_extras'] ?></td></tr>
            <tr><td>Comisiones</td><td>Q<?= number_format($planilla_info['comisiones'], 2) ?></td></tr>
            <tr><td>Bonificación</td><td>Q<?= number_format($planilla_info['bonificacion'], 2) ?></td></tr>
            <tr><td>Anticipo</td><td>Q<?= number_format($planilla_info['anticipo'], 2) ?></td></tr>

            <tr><td>Total ingresos</td><td>Q<?= number_format($planilla_info['total_ingresos'], 2) ?></td></tr>
            <tr><td>ISSS</td><td>Q<?= number_format($planilla_info['isss'], 2) ?></td></tr>
            <tr><td>ISR</td><td>Q<?= number_format($planilla_info['isr'], 2) ?></td></tr>

            <tr><td>Descuentos judiciales</td><td>Q<?= number_format($planilla_info['descuentos_judiciales'], 2) ?></td></tr>
            <tr><td>Otros descuentos</td><td>Q<?= number_format($planilla_info['otros_descuentos'], 2) ?></td></tr>
            <tr><td>Total descuentos</td><td>Q<?= number_format($planilla_info['total_descuentos'], 2) ?></td></tr>

            <tr><td>Líquido a recibir</td><td><strong>Q<?= number_format($planilla_info['liquido_recibir'], 2) ?></strong></td></tr>
            <tr><td>Fecha de registro</td><td><?= $planilla_info['fecha_registro'] ?></td></tr>
          </tbody>
        </table>

        <!-- Botones de acción -->
        <div class="d-flex gap-2">
          <a
            href="exportar_planilla_pdf.php?id=<?= $planilla_info['id'] ?>"
            target="_blank"
            class="btn btn-outline-danger"
          >
            <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
          </a>

          <button
            type="button"
            id="btnPartidaContablePlanilla"
            class="btn btn-outline-danger"
          >
            <i class="bi bi-journal-text"></i> Generar Partida Contable
          </button>
        </div>
      </div>

      <script>
        document.getElementById('btnPartidaContablePlanilla')
          .addEventListener('click', function() {
            const id = <?= json_encode($planilla_info['id'], JSON_NUMERIC_CHECK) ?>;
            window.open(
              'generar_partida_planilla.php?planilla_id=' + id,
              'PartidaContablePopup',
              'width=800,height=600,top=100,left=100,' +
              'menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes'
            );
          });
      </script>
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
