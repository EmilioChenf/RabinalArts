<?php
// compras.php
include 'conexion.php';

// 1) Si viene POST, grabamos la compra interna
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_invoice'])) {
    $forma_pago               = $_POST['forma_pago'];
    $periodo_pago             = trim($_POST['periodo_pago']);
    $nombre_producto          = trim($_POST['nombre_producto']);
    $numero_cuenta_contable   = trim($_POST['numero_cuenta_contable']);
    $valor_iva                = floatval($_POST['valor_iva']);
    $valor_sin_iva            = floatval($_POST['valor_sin_iva']);
    $total_producto_sin_iva   = floatval($_POST['total_producto_sin_iva']);
    $total_iva                = floatval($_POST['total_iva']);
    $total_sin_iva_general    = floatval($_POST['total_sin_iva_general']);
    $total_general            = floatval($_POST['total_general']);

    // Insertar en la base de datos
    $stmt = $conn->prepare(
      "INSERT INTO compras_internas
         (forma_pago, periodo_pago,
          nombre_producto, numero_cuenta_contable,
          valor_iva, valor_sin_iva,
          total_producto_sin_iva, total_iva,
          total_sin_iva_general, total_general)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Error al preparar SQL: " . $conn->error);
    }
    $stmt->bind_param(
      'ssssdddddd',
      $forma_pago,
      $periodo_pago,
      $nombre_producto,
      $numero_cuenta_contable,
      $valor_iva,
      $valor_sin_iva,
      $total_producto_sin_iva,
      $total_iva,
      $total_sin_iva_general,
      $total_general
    );
    $stmt->execute();
    $stmt->close();

    header("Location: compras.php?success=1");
    exit;
}

// 2) Opciones de forma de pago
$formas_pago = ['Efectivo', 'Crédito', 'Crédito con documentos: Cheque'];
?>

<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                  <li class="nav-item">
                    <a href="../widgets/docuemnetación.php" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Registro de Compras (Internas)</p>
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
      <!--begin::App Main-->    <main class="app-main p-4">
      <div class="container">
        <h3 class="mb-4">Registro de Compras Internas</h3>

        <?php if (isset($_GET['success'])): ?>
          <script>
            Swal.fire({
              icon: 'success',
              title: '¡Compra registrada!',
              showConfirmButton: false,
              timer: 1500
            });
          </script>
        <?php endif; ?>

        <form id="comprasForm" method="POST" class="row g-3 border p-4 rounded bg-light shadow-sm">
          <div class="col-md-4">
            <label class="form-label">Forma de pago</label>
            <select name="forma_pago" class="form-select" required>
              <?php foreach ($formas_pago as $f): ?>
                <option><?= htmlspecialchars($f) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Periodo de compra</label>
            <input type="text" name="periodo_pago" class="form-control" placeholder="Ej. 30 días crédito" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Nombre del producto comprado</label>
            <input type="text" name="nombre_producto" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Número de cuenta contable</label>
            <input type="text" name="numero_cuenta_contable" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Valor del IVA</label>
            <input type="number" step="0.01" name="valor_iva" id="valor_iva" class="form-control" value="0" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Valor sin IVA</label>
            <input type="number" step="0.01" name="valor_sin_iva" id="valor_sin_iva" class="form-control" value="0" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Total del producto sin IVA</label>
            <input type="number" step="0.01" name="total_producto_sin_iva" id="total_producto_sin_iva" class="form-control" readonly>
          </div>
          <div class="col-md-4">
            <label class="form-label">Total del IVA</label>
            <input type="number" step="0.01" name="total_iva" id="total_iva" class="form-control" readonly>
          </div>
          <div class="col-md-4">
            <label class="form-label">Total sin IVA</label>
            <input type="number" step="0.01" name="total_sin_iva_general" id="total_sin_iva_general" class="form-control" readonly>
          </div>
          <div class="col-md-4">
            <label class="form-label">Total general</label>
            <input type="number" step="0.01" name="total_general" id="total_general" class="form-control" readonly>
          </div>
          <div class="col-12 text-end">
            <button name="save_invoice" type="submit" class="btn btn-success">💾 Registrar Compra</button>
          </div>
        </form>
      </div>
    </main>

    <footer class="app-footer">
      <div class="float-end d-none d-sm-inline">Anything you want</div>
      <strong>Copyright &copy; 2014-2024</strong> All rights reserved.
    </footer>
  </div>

  <script src="../../../dist/js/adminlte.js"></script>
  <script>
    function calcularTotales() {
      const iva = parseFloat(document.getElementById('valor_iva').value) || 0;
      const sin = parseFloat(document.getElementById('valor_sin_iva').value) || 0;
      document.getElementById('total_producto_sin_iva').value = sin.toFixed(2);
      document.getElementById('total_iva').value            = iva.toFixed(2);
      document.getElementById('total_sin_iva_general').value = sin.toFixed(2);
      document.getElementById('total_general').value        = (iva + sin).toFixed(2);
    }
    document.getElementById('valor_iva').addEventListener('input', calcularTotales);
    document.getElementById('valor_sin_iva').addEventListener('input', calcularTotales);
    calcularTotales();
  </script>
</body>
</html>
