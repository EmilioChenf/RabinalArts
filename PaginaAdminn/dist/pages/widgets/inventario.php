
<?php
// gestion_de_cuentas.php
include 'conexion.php';
$sections = [
  'ACTIVO' => [
    'ACTIVO CORRIENTE DISPONIBLE',
    'ACTIVO CORRIENTE EXIGIBLE',
    'ACTIVO CORRIENTE REALIZABLE',
    'ACTIVO NO CORRIENTE',
  ],
  'PASIVO' => [
    'PASIVO CORRIENTE',
    'PASIVO NO CORRIENTE',
  ],
  'PATRIMONIO NETO' => [
    'CUENTA DE CAPITAL',
  ],
];
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
      <!--begin::App Main-->


  <main class="app-main p-4">
        <div class="container">
          <!-- Título principal -->
          <h3 class="mb-4">Clasificación de Cuentas e Inventario</h3>

          <!-- Botón Exportar PDF -->
<div class="text-end mb-3">
  <a href="exportar_inventario_pdf.php" target="_blank" class="btn btn-danger">
    <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
  </a>
</div>


          <!-- Formulario / Tablas por Sección -->
          <form id="inventarioForm" method="POST" action="guardar_inventario.php">
            <?php foreach($sections as $main => $subgroups): 
              $key = strtolower(str_replace(' ', '-', $main));
            ?>
              <!-- Título de sección -->
              <div class="section-title"><?= $main ?></div>

              <!-- Cada sección tiene su propia tabla -->
              <table id="table-<?= $key ?>" class="table inventario-table">
                <thead>
                  <tr>
                    <th style="width:50%">Cuenta</th>
                    <th style="width:25%">Debe</th>
                    <th style="width:25%">Haber</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($subgroups as $sub): ?>
                    <!-- Subtítulo de subgrupo -->
                    <tr class="subgroup">
                      <td colspan="3"><?= htmlspecialchars($sub) ?></td>
                    </tr>
                    <?php
                      // Traemos monto desde grupos_inventario_detalle
                      $stmt = $conn->prepare("
                        SELECT c.id, c.nombre, d.monto
                          FROM cuentas_contables AS c
                          JOIN grupos_inventario_detalle AS d ON c.id = d.cuenta_id
                          JOIN grupos_inventario AS g ON g.id = d.grupo_id
                         WHERE g.clasificacion = ?
                         ORDER BY c.nombre
                      ");
                      if (!$stmt) {
                        die("Error al preparar SQL: " . $conn->error);
                      }
                      $stmt->bind_param("s", $sub);
                      $stmt->execute();
                      $res = $stmt->get_result();

                      while($row = $res->fetch_assoc()):
                        // Para “ACTIVO” se coloca el monto en “Debe”; en otros, en “Haber”
                        $debeVal  = ($main === 'ACTIVO') ? $row['monto'] : 0;
                        $haberVal = ($main !== 'ACTIVO') ? $row['monto'] : 0;
                    ?>
                      <tr>
                        <td>
                          <?= htmlspecialchars($row['nombre']) ?>
                          <input type="hidden" name="cuenta_id[]" value="<?= $row['id'] ?>">
                        </td>
                        <td>
                          <input type="number"
                                 name="debe[]"
                                 class="form-control input-debe"
                                 step="0.01"
                                 value="<?= number_format($debeVal, 2, '.', '') ?>">
                        </td>
                        <td>
                          <input type="number"
                                 name="haber[]"
                                 class="form-control input-haber"
                                 step="0.01"
                                 value="<?= number_format($haberVal, 2, '.', '') ?>">
                        </td>
                      </tr>
                    <?php 
                      endwhile; 
                      $stmt->close();
                    ?>
                  <?php endforeach; ?>
                </tbody>
                <tfoot>
                  <tr>
                    <!-- En esta versión: mostramos la suma de “Debe + Haber” total de la sección,
                         pero la colocamos en la columna “Haber”. La primera celda se une. -->
                    <th colspan="2" class="text-end">TOTAL <?= $main ?>:</th>
                    <th class="text-end doble-subrayado">0.00</th>
                  </tr>
                </tfoot>
              </table>
            <?php endforeach; ?>

            <!-- Botón “Guardar” (mantener lógica de SweetAlert) -->
            <div class="text-end">
              <button type="submit" class="btn btn-primary">💾 Guardar Inventario</button>
            </div>
          </form>
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
     <!-- Scripts JS -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="../../../dist/js/adminlte.js"></script>
    <script>
      /**
       * Recalcula el total de cada sección sumando los valores de “Debe” + “Haber”,
       * y coloca el resultado en la celda de “Haber” del pie (tfoot).
       */
      function recalcTotals(sectionKey) {
        const table      = document.getElementById('table-' + sectionKey);
        let sumaSection   = 0.00;

        // Sumar todos los inputs "Debe" de esta sección
        table.querySelectorAll('.input-debe').forEach(input => {
          sumaSection += parseFloat(input.value) || 0;
        });

        // Sumar todos los inputs "Haber" de esta sección
        table.querySelectorAll('.input-haber').forEach(input => {
          sumaSection += parseFloat(input.value) || 0;
        });

        // Actualizar la celda del pie de tabla (tfoot) en “Haber”
        const totalCell = document.querySelector('#table-' + sectionKey + ' tfoot .doble-subrayado');
        if (totalCell) {
          totalCell.textContent = sumaSection.toFixed(2);
        }
      }

      // Asociar listener a cada input de cada sección, e inicializar
      <?php foreach($sections as $main => $subgroups):
        $key = strtolower(str_replace(' ', '-', $main));
      ?>
        document.querySelectorAll('#table-<?= $key ?> .input-debe, #table-<?= $key ?> .input-haber')
          .forEach(input => {
            input.addEventListener('input', () => recalcTotals('<?= $key ?>'));
          });
        // Inicializa el total de esta sección al cargar la página
        recalcTotals('<?= $key ?>');
      <?php endforeach; ?>

      // SweetAlert2 al guardar
      document.getElementById('inventarioForm').addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
          icon: 'success',
          title: '¡Inventario guardado!',
          showConfirmButton: false,
          timer: 1500
        }).then(() => {
          this.submit();
        });
      });
    </script><!--end::Script-->
  </body>
  <!--end::Body-->
</html>
