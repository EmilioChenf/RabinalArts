<?php
// venta_factura.php
session_start();
include 'conexion.php';

$cliente_info = [];
$ventas = [];
$venta_detalle = [];
$venta_seleccionada = 0;

// 1) Buscar cliente
if (isset($_GET['cliente_id'])) {
  $cliente_id = (int)$_GET['cliente_id'];
  if ($cliente_id > 0) {
    // Datos del cliente
    $stmt = $conn->prepare("SELECT id, nombre, correo, telefono, direccion FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $cliente_info = $stmt->get_result()->fetch_assoc() ?: [];
    $stmt->close();

    if ($cliente_info) {
      // 2) Ventas del cliente + total (IVA incluido) desde detalle_venta
      $sqlVentas = "
        SELECT v.id, v.fecha,
               COALESCE(SUM(dv.total),0) AS total_con_iva
          FROM ventas v
          LEFT JOIN detalle_venta dv ON dv.venta_id = v.id
         WHERE v.cliente_id = ?
         GROUP BY v.id, v.fecha
         ORDER BY v.fecha DESC, v.id DESC
      ";
      $stmt = $conn->prepare($sqlVentas);
      $stmt->bind_param("i", $cliente_id);
      $stmt->execute();
      $ventas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
      $stmt->close();

      // 3) Venta seleccionada (por GET) o la más reciente
      if (!empty($ventas)) {
        $venta_seleccionada = isset($_GET['venta_id']) ? (int)$_GET['venta_id'] : (int)$ventas[0]['id'];

        // Detalle de la venta seleccionada
        $sqlDet = "
          SELECT dv.producto_id, p.nombre, dv.cantidad, dv.precio_unitario, dv.total
            FROM detalle_venta dv
            JOIN productos p ON p.id = dv.producto_id
           WHERE dv.venta_id = ?
           ORDER BY dv.id ASC
        ";
        $stmt = $conn->prepare($sqlDet);
        $stmt->bind_param("i", $venta_seleccionada);
        $stmt->execute();
        $venta_detalle = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
      }
    }
  }
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
  <div class="container">

    <!-- Logo -->
    <div style="position: relative;">
      <img src="../../../dist/assets/img/rabi.png"
           alt="Logo Rabinalarts"
           style="position: absolute; top: 0; right: 0; height: 60px;">
    </div>

    <h1 class="mb-4">Ventas — Factura y Partida automática</h1>

    <!-- Encabezado Empresa -->
    <div class="mb-3">
      <h4 class="fw-bold">RABINALARTS</h4>
      <p><strong>Fecha:</strong> <?= date("Y-m-d") ?> | <strong>Folio:</strong> <?= rand(10000, 99999) ?></p>
    </div>

    <!-- Buscar cliente -->
    <form method="GET" class="row g-3 mb-4">
      <div class="col-md-3">
        <label for="cliente_id" class="form-label">ID Cliente</label>
        <input type="number" name="cliente_id" id="cliente_id" class="form-control" required
               value="<?= isset($_GET['cliente_id']) ? (int)$_GET['cliente_id'] : '' ?>">
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-dark">Buscar Cliente</button>
      </div>
    </form>

    <?php if ($cliente_info): ?>
      <!-- Datos del cliente -->
      <div class="mb-4">
        <h5>Datos del Cliente</h5>
        <table class="table table-bordered bg-light">
          <thead class="table-secondary">
            <tr><th>Campo</th><th>Valor</th></tr>
          </thead>
          <tbody>
            <tr><td>ID</td><td><?= $cliente_info['id'] ?></td></tr>
            <tr><td>Nombre</td><td><?= htmlspecialchars($cliente_info['nombre']) ?></td></tr>
            <tr><td>Correo</td><td><?= htmlspecialchars($cliente_info['correo']) ?></td></tr>
            <tr><td>Teléfono</td><td><?= htmlspecialchars($cliente_info['telefono']) ?></td></tr>
            <tr><td>Dirección</td><td><?= htmlspecialchars($cliente_info['direccion']) ?></td></tr>
          </tbody>
        </table>
      </div>

      <!-- Ventas del cliente -->
      <div class="card mb-4">
        <div class="card-header">Ventas del cliente</div>
        <div class="card-body">
          <?php if (empty($ventas)): ?>
            <div class="text-muted">Este cliente no tiene ventas registradas.</div>
          <?php else: ?>
            <form method="GET" class="row g-3 mb-3">
              <input type="hidden" name="cliente_id" value="<?= (int)$cliente_info['id'] ?>">
              <div class="col-md-6">
                <label class="form-label">Selecciona una venta</label>
                <select name="venta_id" class="form-select" onchange="this.form.submit()">
                  <?php foreach ($ventas as $v):
                    $sel = ((int)$v['id'] === $venta_seleccionada) ? 'selected' : '';
                    $tot_iva = (float)$v['total_con_iva'];
                  ?>
                    <option value="<?= (int)$v['id'] ?>" <?= $sel ?>>
                      Venta #<?= (int)$v['id'] ?> — <?= date('Y-m-d', strtotime($v['fecha'])) ?> — Total (IVA incl.) Q<?= number_format($tot_iva,2) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-3 d-flex align-items-end">
                <a class="btn btn-outline-danger"
                   target="_blank"
                   href="exportar_factura_pdf.php?cliente_id=<?= (int)$cliente_info['id'] ?>&venta_id=<?= (int)$venta_seleccionada ?>">
                  Exportar factura PDF
                </a>
              </div>
            </form>

            <!-- Detalle de la venta seleccionada -->
            <h6 class="mb-2">Detalle de la venta #<?= (int)$venta_seleccionada ?></h6>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Producto</th>
                  <th>Cantidad</th>
                  <th>Precio Unitario (Q, IVA incl.)</th>
                  <th>Importe (Q, IVA incl.)</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $total_con_iva_sel = 0.0;
                  foreach ($venta_detalle as $it):
                    $total_con_iva_sel += (float)$it['total'];
                ?>
                  <tr>
                    <td><?= htmlspecialchars($it['nombre']) ?></td>
                    <td><?= (int)$it['cantidad'] ?></td>
                    <td>Q<?= number_format((float)$it['precio_unitario'], 2) ?></td>
                    <td>Q<?= number_format((float)$it['total'], 2) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
              <?php
                // IVA incluido: base = total/1.12, IVA = total - base
                $base_sel = round($total_con_iva_sel / 1.12, 2);
                $iva_sel  = round($total_con_iva_sel - $base_sel, 2);
              ?>
              <tfoot>
                <tr><th colspan="3" class="text-end">Base (sin IVA):</th><th>Q<?= number_format($base_sel, 2) ?></th></tr>
                <tr><th colspan="3" class="text-end">IVA (12%):</th><th>Q<?= number_format($iva_sel, 2) ?></th></tr>
                <tr><th colspan="3" class="text-end">Total (IVA incl.):</th><th>Q<?= number_format($total_con_iva_sel, 2) ?></th></tr>
              </tfoot>
            </table>

            <!-- Controles partida automática -->
            <div class="row g-3">
              <div class="col-md-3">
                <label class="form-label">Forma de cobro</label>
                <select id="forma_cobro" class="form-select">
                  <option value="Efectivo">Efectivo</option>
                  <option value="Transferencia">Transferencia</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Descripción (opcional)</label>
                <input id="desc_partida" class="form-control"
                       placeholder="Ej. Venta mostrador (VID <?= (int)$venta_seleccionada ?>)">
              </div>
              <div class="col-md-3 d-flex align-items-end">
                <button type="button" id="btnAutoPartidaVenta" class="btn btn-primary w-100">
                  ⚡ Generar Partida Automática
                </button>
              </div>
            </div>

            <div id="exportVentaBtnContainer" class="mt-3"></div>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>

  </div>

  <script>
    document.getElementById('btnAutoPartidaVenta')?.addEventListener('click', async function () {
      const clienteId = <?= json_encode($cliente_info['id'] ?? 0, JSON_NUMERIC_CHECK) ?>;
      const ventaId   = <?= json_encode($venta_seleccionada, JSON_NUMERIC_CHECK) ?>;
      if (!clienteId || !ventaId) {
        alert('Selecciona un cliente y una venta.');
        return;
      }
      const forma = document.getElementById('forma_cobro')?.value || '';
      const desc  = document.getElementById('desc_partida')?.value || '';

      try {
        const resp = await fetch('generar_partida_venta_auto.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          credentials: 'same-origin',
          body: JSON.stringify({
            venta_id:    ventaId,
            forma_cobro: forma,
            descripcion: desc
          })
        });
        const data = await resp.json();
        if (!data.success) throw new Error(data.message || 'Error desconocido');

        alert('Partida creada (#' + data.partida_id + ').');
        const ctn = document.getElementById('exportVentaBtnContainer');
        if (ctn) {
          ctn.innerHTML =
            `<a href="exportar_partida_pdf.php?partida_id=${data.partida_id}&cliente_id=${clienteId}"
               target="_blank" class="btn btn-danger">📄 Exportar PDF (partida #${data.partida_id})</a>`;
        }
      } catch (e) {
        alert('No se pudo generar: ' + e.message);
      }
    });
  </script>
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
