
<?php
// gestion_de_cuentas.php
include 'conexion.php';
$cuentas = $conn->query("SELECT id, nombre FROM cuentas_contables ORDER BY nombre");
// 1) PROCESAR BORRADO
if (isset($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM cuentas_contables WHERE id = ?");
    $stmt->bind_param("i", $del_id);
    $stmt->execute();
    header("Location: gestion_de_cuentas.php?msg=deleted");
    exit;
}

// 2) PROCESAR CREAR / ACTUALIZAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_account'])) {
    $id            = intval($_POST['id'] ?? 0);
    $nombre        = trim($_POST['nombre']);
    $egr           = isset($_POST['nominal_egreso']) ? 1 : 0;
    $ing           = isset($_POST['nominal_ingreso']) ? 1 : 0;
    $deb           = isset($_POST['balance_deudor']) ? 1 : 0;
    $acre          = isset($_POST['balance_acreedor']) ? 1 : 0;
    $clasificacion = trim($_POST['clasificacion']);

    if ($id > 0) {
        // actualizar
        $stmt = $conn->prepare("
          UPDATE cuentas_contables 
            SET nombre=?, nominal_egreso=?, nominal_ingreso=?, balance_deudor=?, balance_acreedor=?, clasificacion=?
          WHERE id=?
        ");
        $stmt->bind_param("siiiisi",
            $nombre, $egr, $ing, $deb, $acre, $clasificacion, $id
        );
        $stmt->execute();
        $msg = 'updated';
    } else {
        // insertar
        $stmt = $conn->prepare("
          INSERT INTO cuentas_contables
            (nombre, nominal_egreso, nominal_ingreso, balance_deudor, balance_acreedor, clasificacion)
          VALUES (?,?,?,?,?,?)
        ");
        $stmt->bind_param("siiiis",
            $nombre, $egr, $ing, $deb, $acre, $clasificacion
        );
        $stmt->execute();
        $msg = 'created';
    }
    header("Location: gestion_de_cuentas.php?msg=$msg");
    exit;
}

// 3) SI VIENE ?edit=ID, traemos para prefilling
$edit = null;
if (isset($_GET['edit'])) {
    $eid = intval($_GET['edit']);
    $res = $conn->query("SELECT * FROM cuentas_contables WHERE id = $eid");
    $edit = $res->fetch_assoc();
}
?>
<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
              src="../../../dist/assets/img/AdminLTELogo.png"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">AdminLTE 4</span>
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


                  <li class="nav-item">
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
          <h3 class="mb-4">Gestión de Documentos Contables</h3>
          <ul class="nav nav-tabs mb-4">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#internos">Documentos Internos</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#externos">Documentos Externos</a></li>
          </ul>
          <div class="tab-content">
            <div id="internos" class="tab-pane fade show active">
              <h5>Registro de Compras (Internas)</h5>
              <form id="facturaInternaForm" method="POST" action="procesar_factura_interna.php">
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label">Tipo de documento</label>
                    <select name="tipo" class="form-select" required>
                      <option value="electronica">Factura electrónica</option>
                      <option value="especial">Factura especial</option>
                      <option value="credito">Nota de crédito</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">NIT del proveedor</label>
                    <select id="nitProveedor" name="nit" class="form-select" required></select>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Forma de pago</label>
                    <select name="forma_pago" class="form-select" required>
                      <option value="efectivo">Efectivo</option>
                      <option value="cheque">Cheque</option>
                      <option value="transferencia">Transferencia</option>
                      <option value="letra">Letra de cambio</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Fecha de compra</label>
                    <input type="date" name="fecha_compra" class="form-control" required>
                  </div>
                  <div class="col-md-8">
                    <label class="form-label">Descripción</label>
                    <input type="text" name="descripcion" class="form-control" placeholder="Razón de la compra" required>
                  </div>
                </div>
                <hr>
                <h6>Detalle contable</h6>
                <table class="table table-bordered mb-3">
                  <thead>
                    <tr><th>Clasificación</th><th>Afectos IVA</th><th>Cantidad</th><th>Precio Unit.</th><th>Subtotal</th></tr>
                  </thead>
                  <tbody id="detalleRows"></tbody>
                </table>
                <button type="button" id="addDetalle" class="btn btn-sm btn-secondary mb-3">➕ Agregar línea</button>
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Guardar Factura</button>
                </div>
              </form>
            </div>
            <div id="externos" class="tab-pane fade">
              <p>Próximamente: Registro de Ventas y Documentos Externos.</p>
            </div>
          </div>
        </div>
      </main>



  
<!-- AQUI PARA ABAJO ES LO DE ABAJO ES LO DE ABAJO VALDA LA REDUNDANCIA   -->
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
