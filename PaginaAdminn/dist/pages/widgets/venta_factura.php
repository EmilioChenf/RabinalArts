<?php
// venta_factura.php
session_start();
include 'conexion.php';

// Inicializar detalle si no existe
if (!isset($_SESSION['factura_detalle'])) {
    $_SESSION['factura_detalle'] = [];
}

$mensaje = "";

// Agregar producto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar'])) {
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];
    $sql = $conn->query("SELECT nombre, precio FROM productos WHERE id = $producto_id");
    if ($row = $sql->fetch_assoc()) {
        $_SESSION['factura_detalle'][] = [
            'producto_id' => $producto_id,
            'nombre' => $row['nombre'],
            'cantidad' => $cantidad,
            'precio' => $row['precio'],
            'subtotal' => $row['precio'] * $cantidad
        ];
    }
}

// Buscar cliente por ID
$cliente_info = [];
if (isset($_GET['cliente_id'])) {
    $cid = (int) $_GET['cliente_id'];
    $sql = $conn->query("SELECT * FROM usuarios WHERE id = $cid");
    $cliente_info = $sql->fetch_assoc();

    // Obtener ultima venta
    $venta = $conn->query("SELECT * FROM ventas WHERE cliente_id = $cid ORDER BY id DESC LIMIT 1")->fetch_assoc();
    $venta_id = $venta['id'] ?? 0;
    if ($venta_id) {
        $detalles = $conn->query("SELECT d.*, p.nombre FROM detalle_venta d JOIN productos p ON d.producto_id = p.id WHERE d.venta_id = $venta_id");
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
    <h1 class="mb-4">Generar Factura</h1>

    <!-- Encabezado Empresa -->
    <div class="mb-3">
      <h4 class="fw-bold">RABINALARTS</h4>
      <p><strong>Fecha:</strong> <?= date("Y-m-d") ?> | <strong>Folio:</strong> <?= rand(10000, 99999) ?></p>
    </div>

    <!-- Buscar cliente -->
    <form method="GET" class="row g-3 mb-4">
      <div class="col-md-3">
        <label for="cliente_id" class="form-label">ID Cliente</label>
        <input type="number" name="cliente_id" id="cliente_id" class="form-control" required>
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-dark">Buscar Cliente</button>
      </div>
    </form>

    <!-- Mostrar datos cliente en tabla -->
    <?php if (!empty($cliente_info)): ?>
      <div class="mb-4">
        <h5>Datos del Cliente</h5>
        <table class="table table-bordered bg-light">
          <thead class="table-secondary">
            <tr>
              <th>Campo</th>
              <th>Valor</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>ID</td>
              <td><?= $cliente_info['id'] ?></td>
            </tr>
            <tr>
              <td>Nombre</td>
              <td><?= htmlspecialchars($cliente_info['nombre']) ?></td>
            </tr>
            <tr>
              <td>Correo</td>
              <td><?= htmlspecialchars($cliente_info['correo']) ?></td>
            </tr>
            <tr>
              <td>Teléfono</td>
              <td><?= htmlspecialchars($cliente_info['telefono']) ?></td>
            </tr>
            <tr>
              <td>Dirección</td>
              <td><?= htmlspecialchars($cliente_info['direccion']) ?></td>
            </tr>
          </tbody>
        </table>

        <!-- Botones que solo aparecen después de buscar cliente -->
        <div class="d-flex gap-2">
          <a href="exportar_factura_pdf.php?cliente_id=<?= $cliente_info['id'] ?>"
             class="btn btn-outline-danger"
             target="_blank">
            Exportar como PDF
          </a>
          <button
            type="button"
            id="btnPartidaContable"
            class="btn btn-outline-danger"
          >
            Generar Partida contable
          </button>
        </div>
      </div>
    <?php endif; ?>

    <!-- Última factura del cliente -->
    <?php if (!empty($detalles)): ?>
      <div class="mb-4">
        <h5>Última Factura Registrada</h5>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Producto</th>
              <th>Cantidad</th>
              <th>Precio Unitario ($)</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
          <?php
          $total_ultima = 0;
          $rows = [];
          while ($row = $detalles->fetch_assoc()) {
              $rows[] = $row;
              $total_ultima += $row['total'];
          }
          $iva_ultima = $total_ultima * 0.12;
          $total_con_iva_ultima = $total_ultima + $iva_ultima;
          ?>

          <?php foreach ($rows as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['nombre']) ?></td>
            <td><?= $row['cantidad'] ?></td>
            <td>$<?= number_format($row['precio_unitario'], 2) ?></td>
            <td>$<?= number_format($row['total'], 2) ?></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="3" class="text-end">Subtotal:</th>
              <th>$<?= number_format($total_ultima, 2) ?></th>
            </tr>
            <tr>
              <th colspan="3" class="text-end">IVA (12%):</th>
              <th>$<?= number_format($iva_ultima, 2) ?></th>
            </tr>
            <tr>
              <th colspan="3" class="text-end">Total con IVA:</th>
              <th>$<?= number_format($total_con_iva_ultima, 2) ?></th>
            </tr>
          </tfoot>
        </table>
      </div>
    <?php endif; ?>

    <!-- Detalle de factura actual -->
    <?php if (!empty($_SESSION['factura_detalle'])): ?>
      <h4>Detalle de la factura actual</h4>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
        <?php 
          $total = 0;
          foreach ($_SESSION['factura_detalle'] as $item): 
            $total += $item['subtotal'];
        ?>
          <tr>
            <td><?= htmlspecialchars($item['nombre']) ?></td>
            <td><?= $item['cantidad'] ?></td>
            <td>Q<?= number_format($item['precio'], 2) ?></td>
            <td>Q<?= number_format($item['subtotal'], 2) ?></td>
          </tr>
        <?php endforeach; ?>

        <?php
          $iva = $total * 0.12;
          $total_final = $total + $iva;
        ?>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="3" class="text-end">Subtotal:</th>
            <th>Q<?= number_format($total, 2) ?></th>
          </tr>
          <tr>
            <th colspan="3" class="text-end">IVA (12%):</th>
            <th>Q<?= number_format($iva, 2) ?></th>
          </tr>
          <tr>
            <th colspan="3" class="text-end">Total con IVA:</th>
            <th>Q<?= number_format($total_final, 2) ?></th>
          </tr>
        </tfoot>
      </table>

      <form method="POST" action="guardar_factura.php">
        <div class="text-end">
          <button type="submit" class="btn btn-success">Confirmar y Ver Factura</button>
        </div>
      </form>
    <?php endif; ?>
  </div>

  <script>
    document.getElementById('btnPartidaContable')?.addEventListener('click', function() {
      const clienteId = <?= json_encode($cliente_info['id'] ?? '', JSON_NUMERIC_CHECK) ?>;
      if (!clienteId) return;
      const url = `generar_Partida_contable.php?cliente_id=${clienteId}`;
      window.open(
        url,
        'PartidaContablePopup',
        'width=800,'  +
        'height=600,' +
        'top=100,'    +
        'left=100,'   +
        'menubar=no,' +
        'toolbar=no,' +
        'location=no,'+
        'status=no,'  +
        'scrollbars=yes,'+
        'resizable=yes'
      );
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
