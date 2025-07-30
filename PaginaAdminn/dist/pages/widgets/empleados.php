<?php
include 'conexion.php';

// Carga de datos para edición
$edit_mode = false;
if (isset($_GET['edit_id'])) {
    $edit_mode = true;
    $edit_id = intval($_GET['edit_id']);
    $res = mysqli_query($conn, "SELECT * FROM empleados WHERE id = $edit_id");
    $emp_edit = mysqli_fetch_assoc($res);
}

// Procesar acciones CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['editar'])) {
        $stmt = $conn->prepare("
            UPDATE empleados SET
                nombre              = ?,
                puesto              = ?,
                telefono            = ?,
                email               = ?,
                salario             = ?,
                fecha_contratacion  = ?,
                fecha_nacimiento    = ?,
                telefono_emergencia = ?,
                direccion           = ?,
                bonificacion        = ?,
                anticipo            = ?
            WHERE id = ?
        ");
        $stmt->bind_param(
            "ssssdssssddi",
            $_POST['nombre'],
            $_POST['puesto'],
            $_POST['telefono'],
            $_POST['email'],
            $_POST['salario'],
            $_POST['fecha_contratacion'],
            $_POST['fecha_nacimiento'],
            $_POST['telefono_emergencia'],
            $_POST['direccion'],
            $_POST['bonificacion'],
            $_POST['anticipo'],
            $_POST['id']
        );
        $stmt->execute();
        header('Location: empleados.php');
        exit;
    }

    if (isset($_POST['agregar'])) {
        $stmt = $conn->prepare("
            INSERT INTO empleados 
                (nombre, puesto, telefono, email, salario, fecha_contratacion, 
                 fecha_nacimiento, telefono_emergencia, direccion, bonificacion, anticipo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "ssssdsssssd",
            $_POST['nombre'],
            $_POST['puesto'],
            $_POST['telefono'],
            $_POST['email'],
            $_POST['salario'],
            $_POST['fecha_contratacion'],
            $_POST['fecha_nacimiento'],
            $_POST['telefono_emergencia'],
            $_POST['direccion'],
            $_POST['bonificacion'],
            $_POST['anticipo']
        );
        $stmt->execute();
        header('Location: empleados.php');
        exit;
    }

    if (isset($_POST['eliminar'])) {
        $stmt = $conn->prepare("DELETE FROM empleados WHERE id = ?");
        $stmt->bind_param("i", $_POST['id']);
        $stmt->execute();
        header('Location: empleados.php');
        exit;
    }
}

$empleados = mysqli_query($conn, "SELECT * FROM empleados ORDER BY id DESC");
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
      <main class="app-main">
        <div class="container-fluid p-4">
          <h2>Gestión de Empleados</h2>

          <?php if ($edit_mode): ?>
          <!-- Formulario de edición -->
          <form method="POST" class="row g-3 mb-4">
            <input type="hidden" name="editar" value="1">
            <input type="hidden" name="id" value="<?= $emp_edit['id'] ?>">

            <div class="col-md-3">
              <input type="text" name="nombre" class="form-control" placeholder="Nombre" value="<?= htmlspecialchars($emp_edit['nombre']) ?>" required>
            </div>
            <div class="col-md-3">
              <input type="text" name="puesto" class="form-control" placeholder="Puesto" value="<?= htmlspecialchars($emp_edit['puesto']) ?>" required>
            </div>
            <div class="col-md-2">
              <input type="text" name="telefono" class="form-control" placeholder="Teléfono" value="<?= htmlspecialchars($emp_edit['telefono']) ?>">
            </div>
            <div class="col-md-2">
              <input type="email" name="email" class="form-control" placeholder="Email" value="<?= htmlspecialchars($emp_edit['email']) ?>">
            </div>
            <div class="col-md-1">
              <input type="number" step="0.01" name="salario" class="form-control" placeholder="Salario" value="<?= $emp_edit['salario'] ?>" required>
            </div>
            <div class="col-md-2">
              <input type="date" name="fecha_contratacion" class="form-control" value="<?= $emp_edit['fecha_contratacion'] ?>" required>
            </div>
            <div class="col-md-2">
              <input type="date" name="fecha_nacimiento" class="form-control" placeholder="Fecha Nac." value="<?= $emp_edit['fecha_nacimiento'] ?>">
            </div>
            <div class="col-md-2">
              <input type="text" name="telefono_emergencia" class="form-control" placeholder="Tel. Emerg." value="<?= htmlspecialchars($emp_edit['telefono_emergencia']) ?>">
            </div>
            <div class="col-md-3">
              <input type="text" name="direccion" class="form-control" placeholder="Dirección" value="<?= htmlspecialchars($emp_edit['direccion']) ?>">
            </div>
            <div class="col-md-1">
              <input type="number" step="0.01" name="bonificacion" class="form-control" placeholder="Bonificación" value="<?= $emp_edit['bonificacion'] ?>">
            </div>
            <div class="col-md-1">
              <input type="number" step="0.01" name="anticipo" class="form-control" placeholder="Anticipo" value="<?= $emp_edit['anticipo'] ?>">
            </div>

            <div class="col-12">
              <button type="submit" class="btn btn-primary">Actualizar</button>
              <a href="empleados.php" class="btn btn-secondary">Cancelar</a>
            </div>
          </form>
          <?php else: ?>
          <!-- Formulario de alta -->
          <form method="POST" class="row g-3 mb-4">
            <input type="hidden" name="agregar" value="1">

            <div class="col-md-3">
              <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
            </div>
            <div class="col-md-3">
              <input type="text" name="puesto" class="form-control" placeholder="Puesto" required>
            </div>
            <div class="col-md-2">
              <input type="text" name="telefono" class="form-control" placeholder="Teléfono">
            </div>
            <div class="col-md-2">
              <input type="email" name="email" class="form-control" placeholder="Email">
            </div>
            <div class="col-md-1">
              <input type="number" step="0.01" name="salario" class="form-control" placeholder="Salario" required>
            </div>
            <div class="col-md-2">
              <input type="date" name="fecha_contratacion" class="form-control" required>
            </div>
            <div class="col-md-2">
              <input type="date" name="fecha_nacimiento" class="form-control" placeholder="Fecha Nac.">
            </div>
            <div class="col-md-2">
              <input type="text" name="telefono_emergencia" class="form-control" placeholder="Tel. Emerg.">
            </div>
            <div class="col-md-3">
              <input type="text" name="direccion" class="form-control" placeholder="Dirección">
            </div>
            <div class="col-md-1">
              <input type="number" step="0.01" name="bonificacion" class="form-control" placeholder="Bonificación">
            </div>
            <div class="col-md-1">
              <input type="number" step="0.01" name="anticipo" class="form-control" placeholder="Anticipo">
            </div>
            <div class="col-12">
              <button type="submit" class="btn btn-success">Agregar</button>
            </div>
          </form>
          <?php endif; ?>

          <!-- Tabla empleados -->
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>ID</th><th>Nombre</th><th>Puesto</th><th>Teléfono</th><th>Email</th>
                <th>Salario</th><th>Fecha Contr.</th><th>Fecha Nac.</th><th>Tel. Emerg.</th>
                <th>Dirección</th><th>Bonificación</th><th>Anticipo</th><th>Edad</th><th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php while($emp = mysqli_fetch_assoc($empleados)): ?>
              <tr>
                <td><?= $emp['id'] ?></td>
                <td><?= htmlspecialchars($emp['nombre']) ?></td>
                <td><?= htmlspecialchars($emp['puesto']) ?></td>
                <td><?= htmlspecialchars($emp['telefono']) ?></td>
                <td><?= htmlspecialchars($emp['email']) ?></td>
                <td>Q<?= number_format($emp['salario'], 2) ?></td>
                <td><?= $emp['fecha_contratacion'] ?></td>
                <td><?= $emp['fecha_nacimiento'] ?></td>
                <td><?= htmlspecialchars($emp['telefono_emergencia']) ?></td>
                <td><?= htmlspecialchars($emp['direccion']) ?></td>
                <td>Q<?= number_format($emp['bonificacion'], 2) ?></td>
                <td>Q<?= number_format($emp['anticipo'], 2) ?></td>
                <td><?= $emp['edad'] ?></td>
                <td>
                  <form method="GET" class="d-inline">
                    <input type="hidden" name="edit_id" value="<?= $emp['id'] ?>">
                    <button type="submit" class="btn btn-warning btn-sm">Editar</button>
                  </form>
                  <form method="POST" class="d-inline">
                    <input type="hidden" name="eliminar" value="1">
                    <input type="hidden" name="id" value="<?= $emp['id'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                  </form>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </main>
      <!--end::App Main-->
      <!--begin::Footer-->
      <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">Anything you want</div>
        <strong>
          Copyright &copy; 2014-2024&nbsp;
          <a href="https://adminlte.io" class="text-decoration-none"></a>.
        </strong>
        All rights reserved.
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
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)-->
    <!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <script src="../../../dist/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)-->
    <!--begin::OverlayScrollbars Configure-->
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
