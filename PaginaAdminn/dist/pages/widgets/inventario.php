
<?php
// gestion_de_cuentas.php
include 'conexion.php';
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
    <!-- CABECERA ROJA -->
    <div style="border-top:3px solid red; border-bottom:3px solid red; padding:8px 0; margin-bottom:10px;">
      <h4 style="margin:0; text-align:center; text-transform:uppercase; font-family:serif;">
        LIBRO DE INVENTARIOS
      </h4>
      <p style="margin:2px 0; text-align:center; font-style:italic; font-size:0.9em;">
        Inventario No. 1 del "Almacén la Fridera", practicado el 1 de febrero de 2010.
      </p>
      <p style="margin:2px 0; text-align:center; font-style:italic; font-size:0.9em;">
        (Cifras en quetzales)
      </p>
    </div>

    <?php
      // Traemos todas las cuentas para el dropdown
      $q = $conn->query("SELECT id, nombre FROM cuentas_contables ORDER BY nombre");
      $cuentas = [];
      while($f = $q->fetch_assoc()){
        $cuentas[] = $f;
      }
      $cuentasJSON = json_encode($cuentas, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP);
    ?>

    <form id="inventarioForm">
      <div class="row mb-3">
        <div class="col-md-3">
          <label class="form-label">Fecha de Inventario</label>
          <input type="date" name="fecha" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
        </div>
      </div>

      <!-- TABLA CON LÍNEAS ROJAS -->
      <table id="inventarioTable" class="table inventario-table">
        <thead>
          <tr>
            <th style="width:25%">Cuenta</th>
            <th style="width:35%">Detalle</th>
            <th style="width:15%">Debe</th>
            <th style="width:15%">Haber</th>
            <th style="width:10%">Acción</th>
          </tr>
        </thead>
        <tbody></tbody>
        <tfoot>
          <tr>
            <th colspan="2" class="text-end">Totales:</th>
            <th id="totalDebe">0.00</th>
            <th id="totalHaber">0.00</th>
            <th></th>
          </tr>
        </tfoot>
      </table>

      <div class="mb-3">
        <button id="addRow" type="button" class="btn btn-secondary">➕ Agregar fila</button>
        <button type="submit" class="btn btn-primary">💾 Guardar Inventario</button>
      </div>
    </form>
  </div>

  <!-- ESTILOS INLINE PARA SIMULAR TU PLANTILLA -->
  <style>
    .inventario-table {
      width:100%;
      border-collapse: collapse;
      font-family: serif;
      font-size: 0.9em;
    }
    .inventario-table th, .inventario-table td {
      border: 1px solid #000;
      padding: 4px;
    }
    /* Líneas rojas exteriores */
    .inventario-table thead th {
      border-top: 2px solid red;
      border-bottom: 2px solid red;
    }
    .inventario-table tfoot th, .inventario-table tfoot td {
      border-top: 2px solid red;
    }
    /* Líneas rojas verticales en los extremos */
    .inventario-table th:first-child,
    .inventario-table td:first-child {
      border-left: 2px solid red;
    }
    .inventario-table th:last-child,
    .inventario-table td:last-child {
      border-right: 2px solid red;
    }
  </style>

  <script>
    const cuentas = <?php echo $cuentasJSON; ?>;
    const inventarioTable = document.querySelector('#inventarioTable tbody');
    const totalDebeEl  = document.getElementById('totalDebe');
    const totalHaberEl = document.getElementById('totalHaber');
    const addRowBtn    = document.getElementById('addRow');

    function recalcTotals(){
      let debe = 0, haber = 0;
      document.querySelectorAll('.input-debe').forEach(i=>{
        debe += parseFloat(i.value)||0;
      });
      document.querySelectorAll('.input-haber').forEach(i=>{
        haber += parseFloat(i.value)||0;
      });
      totalDebeEl.textContent  = debe.toFixed(2);
      totalHaberEl.textContent = haber.toFixed(2);
    }

    function addRow(){
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>
          <select name="cuenta_id[]" class="form-select" required>
            <option value="">-- Selecciona cuenta --</option>
            ${cuentas.map(c=>
              `<option value="${c.id}">${c.nombre}</option>`
            ).join('')}
          </select>
        </td>
        <td><input type="text" name="detalle[]" class="form-control" placeholder="Descripción"></td>
        <td><input type="number" name="debe[]" class="form-control input-debe" min="0" step="0.01" value="0.00"></td>
        <td><input type="number" name="haber[]" class="form-control input-haber" min="0" step="0.01" value="0.00"></td>
        <td class="text-center">
          <button type="button" class="btn btn-sm btn-danger btn-remove">✖</button>
        </td>
      `;
      tr.querySelectorAll('.input-debe, .input-haber').forEach(inp=>{
        inp.addEventListener('input', recalcTotals);
      });
      tr.querySelector('.btn-remove').addEventListener('click', ()=>{
        tr.remove();
        recalcTotals();
      });
      inventarioTable.appendChild(tr);
    }

    addRowBtn.addEventListener('click', addRow);
    document.addEventListener('DOMContentLoaded', ()=> addRow());

    document.getElementById('inventarioForm').addEventListener('submit', e=>{
      e.preventDefault();
      Swal.fire({
        icon: 'success',
        title: 'Inventario guardado',
        timer: 1500,
        showConfirmButton: false
      });
      // Aquí podrías enviar por fetch() los datos al servidor...
    });
  </script>
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
