
<?php
// gestion_de_cuentas.php
include 'conexion.php';

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
      <h3 class="mb-4">Gestión de Cuentas Contables</h3>

      <!-- SweetAlert según acción -->
      <?php if (isset($_GET['msg'])): ?>
      <script>
        document.addEventListener('DOMContentLoaded', () => {
          let m = "<?= $_GET['msg'] ?>";
          let titles = {
            created:   '¡Cuenta creada!',
            updated:   '¡Cuenta actualizada!',
            deleted:   '¡Cuenta eliminada!'
          };
          Swal.fire({
            icon: 'success',
            title: titles[m] || '¡Hecho!',
            timer: 1500,
            showConfirmButton: false
          });
        });
      </script>
      <?php endif; ?>

      <!-- FORMULARIO CREAR / EDITAR -->
      <form method="POST" class="row g-3 border p-4 rounded bg-light mb-5">
        <input type="hidden" name="id" value="<?= $edit['id'] ?? 0 ?>">
        <div class="col-md-4">
          <label class="form-label">Nombre de cuenta</label>
          <input type="text" name="nombre" required
                 class="form-control"
                 value="<?= htmlspecialchars($edit['nombre'] ?? '') ?>">
        </div>
        <div class="col-md-2 form-check">
          <input type="checkbox" class="form-check-input" id="egr" name="nominal_egreso"
                 <?= (!empty($edit) && $edit['nominal_egreso'])?'checked':'' ?>>
          <label for="egr" class="form-check-label">Egreso</label>
        </div>
        <div class="col-md-2 form-check">
          <input type="checkbox" class="form-check-input" id="ing" name="nominal_ingreso"
                 <?= (!empty($edit) && $edit['nominal_ingreso'])?'checked':'' ?>>
          <label for="ing" class="form-check-label">Ingreso</label>
        </div>
        <div class="col-md-2 form-check">
          <input type="checkbox" class="form-check-input" id="deb" name="balance_deudor"
                 <?= (!empty($edit) && $edit['balance_deudor'])?'checked':'' ?>>
          <label for="deb" class="form-check-label">Deudor</label>
        </div>
        <div class="col-md-2 form-check">
          <input type="checkbox" class="form-check-input" id="acre" name="balance_acreedor"
                 <?= (!empty($edit) && $edit['balance_acreedor'])?'checked':'' ?>>
          <label for="acre" class="form-check-label">Acreedor</label>
        </div>
        <div class="col-md-4">
          <label class="form-label">Clasificación</label>
          <input type="text" name="clasificacion" required
                 class="form-control"
                 value="<?= htmlspecialchars($edit['clasificacion'] ?? '') ?>">
        </div>
        <div class="col-12 text-end">
          <button name="save_account" type="submit"
                  class="btn btn-<?= $edit ? 'warning':'primary' ?>">
            <?= $edit ? 'Actualizar':'Crear' ?>
          </button>
          <?php if($edit): ?>
            <a href="gestion_de_cuentas.php" class="btn btn-secondary">Cancelar</a>
          <?php endif; ?>
        </div>
      </form>

      <!-- LISTADO DE CUENTAS -->
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Egr.</th>
            <th>Ing.</th>
            <th>Deudor</th>
            <th>Acreedor</th>
            <th>Clasificación</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $all = $conn->query("SELECT * FROM cuentas_contables ORDER BY id DESC");
        while($row = $all->fetch_assoc()):
        ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['nombre']) ?></td>
            <td><?= $row['nominal_egreso'] ? '✔':'—' ?></td>
            <td><?= $row['nominal_ingreso'] ? '✔':'—' ?></td>
            <td><?= $row['balance_deudor'] ? '✔':'—' ?></td>
            <td><?= $row['balance_acreedor'] ? '✔':'—' ?></td>
            <td><?= htmlspecialchars($row['clasificacion']) ?></td>
            <td>
              <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">✎</a>
              <a href="?delete=<?= $row['id'] ?>"
                 onclick="return confirm('¿Eliminar esta cuenta?')"
                 class="btn btn-sm btn-danger">🗑</a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
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
