<?php
// venta_factura.php
session_start();
include 'conexion.php';
// 1) Parámetros de filtro
$from = $_GET['from'] ?? date('Y-m-01');
$to   = $_GET['to']   ?? date('Y-m-d');

// Escapamos para seguridad
$from_sql = $conn->real_escape_string($from);
$to_sql   = $conn->real_escape_string($to);

// 2) Partidas de Compras
$sql_comp = "
  SELECT id, descripcion, created_at AS fecha
  FROM partidas_contables_compras
  WHERE DATE(created_at) BETWEEN '$from_sql' AND '$to_sql'
  ORDER BY created_at DESC
";
$res_comp = $conn->query($sql_comp);
if (! $res_comp) {
    die("Error en consulta de partidas de compras: " . $conn->error);
}

// 3) Partidas de Ventas
$sql_vent = "
  SELECT id, cliente_id, descripcion, fecha
  FROM partidas_contables_ventas
  WHERE DATE(fecha) BETWEEN '$from_sql' AND '$to_sql'
  ORDER BY fecha DESC
";
$res_vent = $conn->query($sql_vent);
if (! $res_vent) {
    die("Error en consulta de partidas de ventas: " . $conn->error);
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
  <div class="container-fluid">
        <!-- Logo en la esquina superior -->
    <div style="position: relative;">
      <img src="../../../dist/assets/img/rabi.png" 
           alt="Logo Rabinalarts" 
           style="position: absolute; top: 0; right: 0; height: 60px;">
    </div>

    <h1 class="mb-4">Gestión de Movimientos Contables</h1>

    <!-- Filtro de fechas -->
    <form method="GET" class="row g-3 align-items-end mb-4">
      <div class="col-md-3">
        <label for="from" class="form-label">Desde</label>
        <input
          type="date"
          id="from"
          name="from"
          class="form-control"
          value="<?= htmlspecialchars($from) ?>"
        >
      </div>
      <div class="col-md-3">
        <label for="to" class="form-label">Hasta</label>
        <input
          type="date"
          id="to"
          name="to"
          class="form-control"
          value="<?= htmlspecialchars($to) ?>"
        >
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">
          <i class="bi bi-funnel-fill"></i> Filtrar
        </button>
      </div>
    </form>

    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12px;
        }
        
        .libro-diario {
            border: 2px solid #000;
            background: white;
            margin-top: 20px;
        }
        
        .libro-header {
            text-align: center;
            padding: 15px;
            border-bottom: 2px solid #000;
            font-weight: bold;
            background-color: #f8f9fa;
        }
        
        .partida-row {
            border-bottom: 1px solid #000;
            min-height: 40px;
            display: flex;
        }
        
        .partida-number {
            border-right: 2px solid #000;
            width: 60px;
            padding: 8px 5px;
            text-align: center;
            font-weight: bold;
            background-color: #f8f9fa;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }
        
        .partida-fecha-header {
            font-weight: bold;
            text-align: center;
            margin-bottom: 8px;
            font-size: 12px;
        }
        
        .partida-content {
            padding: 8px 10px;
            flex: 1;
        }
        
        .cuenta-row {
            margin: 2px 0;
            display: flex;
            align-items: center;
        }
        
        .cuenta-numero {
            display: inline-block;
            width: 20px;
            text-align: center;
            font-weight: bold;
            margin-right: 10px;
        }
        
        .cuenta-nombre {
            text-transform: uppercase;
            font-weight: bold;
        }
        
        .cuenta-subcuenta {
            margin-left: 30px;
            font-size: 11px;
            text-transform: uppercase;
        }
        
        .cuenta-descripcion {
            margin: 8px 0;
            margin-left: 20px;
            font-size: 11px;
            font-style: italic;
            color: #666;
            line-height: 1.3;
            font-weight: bold;
        }
        
        .amounts-column {
            width: 120px;
            border-left: 1px solid #000;
            padding: 8px 5px;
            text-align: right;
        }
        
        .amounts-column-haber {
            width: 120px;
            border-left: 1px solid #000;
            padding: 8px 5px;
            text-align: right;
        }
        
        .amount-cell {
            margin: 2px 0;
            min-height: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .currency {
            margin-right: 5px;
        }
        
        .amount-debe {
            color: #000;
        }
        
        .amount-haber {
            color: #000;
            margin-left: 20px;
        }
        
        .final-totals {
            border: 1px solid #000;
            background-color: #e9ecef;
            text-align: right;
            font-weight: bold;
            font-size: 12px;
            padding: 5px;
            margin-top: 5px;
        }
        
        .partida-totals {
            display: none;
        }
    </style>

    <?php
    // Tu código PHP existente + PLANILLA añadida
    $sql_diario = "
      SELECT p.id AS partida_id, p.created_at AS fecha, p.descripcion,
             d.cuenta_id, c.nombre AS cuenta, d.debe, d.haber
      FROM partidas_contables_compras p
      JOIN partidas_contables_compras_detalle d ON d.partida_id = p.id
      JOIN cuentas_contables c ON c.id = d.cuenta_id
      WHERE DATE(p.created_at) BETWEEN '$from_sql' AND '$to_sql'

      UNION ALL

      SELECT p.id AS partida_id, p.fecha AS fecha, p.descripcion,
             d.cuenta_id, c.nombre AS cuenta, d.debe, d.haber
      FROM partidas_contables_ventas p
      JOIN partida_detalle_ventas d ON d.partida_id = p.id
      JOIN cuentas_contables c ON c.id = d.cuenta_id
      WHERE DATE(p.fecha) BETWEEN '$from_sql' AND '$to_sql'

      UNION ALL

      SELECT p.id AS partida_id, p.created_at AS fecha, p.descripcion,
             d.cuenta_id, c.nombre AS cuenta, d.debe, d.haber
      FROM partidas_contables_planilla p
      JOIN partida_detalle_planilla d ON d.partida_id = p.id
      JOIN cuentas_contables c ON c.id = d.cuenta_id
      WHERE DATE(p.created_at) BETWEEN '$from_sql' AND '$to_sql'

      ORDER BY fecha, partida_id
    ";
    $res_diario = $conn->query($sql_diario);
    if (! $res_diario) {
      die("Error al obtener Libro Diario: " . $conn->error);
    }

    $libro = [];
    while ($row = $res_diario->fetch_assoc()) {
      $pid = $row['partida_id'];
      if (!isset($libro[$pid])) {
        $libro[$pid] = [
          'fecha'       => $row['fecha'],
          'descripcion' => $row['descripcion'],
          'lines'       => []
        ];
      }
      $libro[$pid]['lines'][] = $row;
    }
    ?>

    <?php if (empty($libro)): ?>
      <div class="alert alert-info">No hay partidas en este rango de fechas.</div>
    <?php else: ?>
      
      <!-- Libro Diario -->
      <div class="libro-diario">
        <!-- Header del libro -->
        <div class="libro-header">
          <div style="font-size: 14px; font-weight: bold;">LIBRO DIARIO</div>
          <div style="font-size: 12px;">RabinalArts", del <?= date('d/m/Y', strtotime($from)) ?> al <?= date('d/m/Y', strtotime($to)) ?></div>
          <div style="font-size: 11px;">(Cifras en Quetzales)</div>
        </div>

        <?php $contador = 1; ?>
        <?php foreach ($libro as $pid => $section): ?>
          <?php
            $sumDebe  = array_sum(array_column($section['lines'], 'debe'));
            $sumHaber = array_sum(array_column($section['lines'], 'haber'));
            $lineCount = count($section['lines']);
          ?>
          
          <!-- Partida -->
          <div class="partida-row">
            <div class="partida-number">Pda<?= $contador ?></div>
            <div class="partida-content">
              <!-- Fecha arriba de las cuentas -->
              <div class="partida-fecha-header"><?= date('d/m/Y', strtotime($section['fecha'])) ?></div>
              
              <?php $lineNum = 1; ?>
              <?php foreach ($section['lines'] as $line): ?>
                <div class="cuenta-row">
                  <span class="cuenta-numero"><?= $lineNum ?></span>
                  <span class="cuenta-nombre"><?= htmlspecialchars($line['cuenta']) ?></span>
                </div>
                <?php $lineNum++; ?>
              <?php endforeach; ?>
              
              <!-- Descripción con totales -->
              <div class="cuenta-descripcion">
                <?= htmlspecialchars($section['descripcion']) ?>:
              </div>
            </div>
            
            <!-- Columna DEBE -->
            <div class="amounts-column">
              <!-- Espacios vacíos para la fecha -->
              <div class="amount-cell" style="height: 20px;"></div>
              
              <?php foreach ($section['lines'] as $line): ?>
                <div class="amount-cell">
                  <?php if ($line['debe'] > 0): ?>
                    <span class="currency">Q</span>
                    <span><?= number_format($line['debe'], 2) ?></span>
                  <?php else: ?>
                    <span></span>
                    <span></span>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
              
              <!-- Total en la línea de descripción -->
              <div class="final-totals">
                Q <?= number_format($sumDebe, 2) ?>
              </div>
            </div>
            
            <!-- Columna HABER -->
            <div class="amounts-column-haber">
              <!-- Espacios vacíos para la fecha -->
              <div class="amount-cell" style="height: 20px;"></div>
              
              <?php foreach ($section['lines'] as $line): ?>
                <div class="amount-cell">
                  <?php if ($line['haber'] > 0): ?>
                    <span class="currency">Q</span>
                    <span><?= number_format($line['haber'], 2) ?></span>
                  <?php else: ?>
                    <span></span>
                    <span></span>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
              
              <!-- Total en la línea de descripción -->
              <div class="final-totals">
                Q <?= number_format($sumHaber, 2) ?>
              </div>
            </div>
          </div>
          
          <?php $contador++; ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
      <a
        href="exportar_libro_diario_pdf.php?from=<?= urlencode($from) ?>&to=<?= urlencode($to) ?>"
        class="btn btn-danger"
        target="_blank"
      >
        <i class="bi bi-file-earmark-pdf"></i> Exportar Libro Diario (PDF)
      </a>
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
