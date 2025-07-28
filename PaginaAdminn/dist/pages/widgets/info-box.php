<?php include 'conexion.php'; ?>
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
    <div class="app-content-header p-4">
      <div class="container-fluid">
        <h3 class="mb-0">Sistema Contable</h3>
      </div>
    </div>

    <div class="app-content p-4">
      <div class="container-fluid">

        <!-- Resumen por Categoría -->
        <div class="card mb-4">
          <div class="card-header"><strong>Resumen de ingresos por categoría</strong></div>
          <div class="card-body">
            <table class="table table-bordered">
              <thead>
                <tr><th>Categoría</th><th>Total ingresos ($)</th></tr>
              </thead>
              <tbody>
                <?php
                  $resumen = mysqli_query($conn, "SELECT categoria, SUM(precio * stock) AS total FROM productos GROUP BY categoria");
                  while($fila = mysqli_fetch_assoc($resumen)) {
                    echo "<tr><td>{$fila['categoria']}</td><td>$ " . number_format($fila['total'], 2) . "</td></tr>";
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>

          <!-- Ganancias por Producto -->
    <div class="card mb-4">
        <div class="card-header">Ganancias por producto (ventas)</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead><tr><th>Producto</th><th>Unidades Vendidas</th><th>Precio Unitario</th><th>Ganancia ($)</th></tr></thead>
                <tbody>
                <?php
                $res = mysqli_query($conn, "SELECT p.nombre, SUM(d.cantidad) AS cantidad_vendida, d.precio_unitario, SUM(d.total) AS total_ganancia FROM detalle_venta d JOIN productos p ON d.producto_id = p.id GROUP BY d.producto_id");
                while($row = mysqli_fetch_assoc($res)) {
                    echo "<tr><td>{$row['nombre']}</td><td>{$row['cantidad_vendida']}</td><td>$ ".number_format($row['precio_unitario'], 2)."</td><td>$ ".number_format($row['total_ganancia'], 2)."</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>


 <!-- Ganancias del mes actual -->
<div class="card mb-4">
    <div class="card-header"><strong>Ganancias del mes actual</strong></div>
    <div class="card-body">
        <?php
        $mesActual = date('Y-m'); // Formato YYYY-MM
        $query = "SELECT SUM(precio * stock) AS ganancias_mes 
                  FROM productos 
                  WHERE DATE_FORMAT(fecha_creacion, '%Y-%m') = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $mesActual);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $gananciasMes = $resultado->fetch_assoc();
        $ganancia = $gananciasMes['ganancias_mes'] ?? 0;
        ?>
        <h4 class="text-success">Q <?= number_format($ganancia, 2) ?></h4>
    </div>
</div>

<!-- Reporte filtrable por calendario -->
<div class="card mb-4">
    <div class="card-header"><strong>Reporte de ingresos por fecha</strong></div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <label for="fecha">Selecciona una fecha:</label>
            <input type="date" name="fecha" id="fecha" class="form-control" value="<?php echo isset($_GET['fecha']) ? $_GET['fecha'] : ''; ?>" required>
            <button type="submit" class="btn btn-primary mt-2">Ver reporte</button>
        </form>

        <?php
        if (isset($_GET['fecha'])) {
            $fechaSeleccionada = $_GET['fecha'];
            $dia = $fechaSeleccionada;
            $mes = date('Y-m', strtotime($fechaSeleccionada));
            $anio = date('Y', strtotime($fechaSeleccionada));

            // Reporte diario
            $diario = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(precio * stock) AS total FROM productos WHERE DATE(fecha_creacion) = '$dia'"));

            // Reporte mensual
            $mensual = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(precio * stock) AS total FROM productos WHERE DATE_FORMAT(fecha_creacion, '%Y-%m') = '$mes'"));

            // Reporte anual
            $anual = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(precio * stock) AS total FROM productos WHERE YEAR(fecha_creacion) = '$anio'"));

            echo "<h5>Resultados para: " . date('d-m-Y', strtotime($fechaSeleccionada)) . "</h5>";

            echo "<table class='table table-bordered'>
                <thead><tr><th>Periodo</th><th>Total Ingresos ($)</th></tr></thead>
                <tbody>
                    <tr><td>Dia seleccionado ($dia)</td><td>$ " . number_format($diario['total'] ?? 0, 2) . "</td></tr>
                    <tr><td>Mes actual ($mes)</td><td>$ " . number_format($mensual['total'] ?? 0, 2) . "</td></tr>
                    <tr><td>Año actual ($anio)</td><td>$ " . number_format($anual['total'] ?? 0, 2) . "</td></tr>
                </tbody>
            </table>";
        }
        ?>
    </div>
</div>


        <!-- Detalle por producto (nuevo) -->
        <div class="card mb-4">
          <div class="card-header"><strong>Detalle por producto</strong></div>
          <div class="card-body">
            <table class="table table-bordered">
              <thead>
                <tr><th>Nombre</th><th>Categoría</th><th>Precio</th><th>Stock</th><th>Total ($)</th></tr>
              </thead>
              <tbody>
                <?php
                  $detalle = mysqli_query($conn, "SELECT nombre, categoria, precio, stock FROM productos");
                  while($fila = mysqli_fetch_assoc($detalle)) {
                    $total = $fila['precio'] * $fila['stock'];
                    echo "<tr>
                      <td>{$fila['nombre']}</td>
                      <td>{$fila['categoria']}</td>
                      <td>$ " . number_format($fila['precio'], 2) . "</td>
                      <td>{$fila['stock']}</td>
                      <td>$ " . number_format($total, 2) . "</td>
                    </tr>";
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Balance General -->
        <div class="card mb-4">
          <div class="card-header"><strong>Balance general</strong></div>
          <div class="card-body">
            <ul>
              <li><strong>Activos:</strong> Inventario disponible = 
                <?php
                  $activos = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(precio * stock) AS total FROM productos"));
                  echo "$ " . number_format($activos['total'], 2);
                ?>
              </li>
              <li><strong>Pasivos:</strong> (simulados) = $ 5,000.00</li>
              <li><strong>Capital:</strong> 
                <?php
                  $capital = $activos['total'] - 5000;
                  echo "$ " . number_format($capital, 2);
                ?>
              </li>
            </ul>
          </div>
        </div>

<!-- Exportar PDF por fecha -->
<div class="card mb-4">
  <div class="card-header"><strong>Exportar Reporte Contable (PDF)</strong></div>
  <div class="card-body">
    <form action="exportar_pdf.php" method="get" class="row g-3">
      <div class="col-md-4">
        <label for="fecha" class="form-label">Selecciona una fecha</label>
        <input type="date" name="fecha" id="fecha" class="form-control">
      </div>
      <div class="col-md-4 d-flex align-items-end">
        <button type="submit" class="btn btn-danger">
          <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
        </button>
      </div>
      <div class="col-md-4 d-flex align-items-end">
        <a href="exportar_pdf.php" target="_blank" class="btn btn-secondary">
          <i class="bi bi-file-earmark-pdf"></i> Exportar TODO (sin filtrar)
        </a>
      </div>
    </form>
  </div>
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
