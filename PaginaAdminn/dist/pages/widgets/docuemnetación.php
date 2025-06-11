
<?php
// compras.php
include 'conexion.php';

// 1) Si viene POST, grabamos la factura
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['save_invoice'])) {
    $tipo            = $_POST['tipo_documento'];
    $proveedor_id    = intval($_POST['proveedor_id']);
    $nit             = $_POST['nit'];
    $numero_factura  = $_POST['numero_factura'];
    $fecha_compra    = $_POST['fecha_compra'];
    $descripcion     = trim($_POST['descripcion']);
    $forma_pago      = $_POST['forma_pago'];
    $clasificacion   = $_POST['clasificacion'];
    $bienes_sin_iva  = floatval($_POST['bienes_sin_iva']);
    $serv_sin_iva    = floatval($_POST['servicios_sin_iva']);
    $iva             = floatval($_POST['iva']);
    $total_sin_iva   = floatval($_POST['total_sin_iva']);
    $total_con_iva   = floatval($_POST['total_con_iva']);
    $periodo_pago    = $_POST['periodo_pago'];

$stmt = $conn->prepare("
  INSERT INTO compras
    (tipo_documento, proveedor_id, nit, numero_factura, fecha_compra, descripcion,
     forma_pago, clasificacion, bienes_sin_iva, servicios_sin_iva,
     iva, total_sin_iva, total_con_iva, periodo_pago)
  VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)
");
if ( ! $stmt) {
  die("Error al preparar SQL: " . $conn->error);
}

// TIPOS:
// 1 s: tipo_documento
// 2 i: proveedor_id
// 3 s: nit
// 4 s: numero_factura
// 5 s: fecha_compra
// 6 s: descripcion
// 7 s: forma_pago
// 8 s: clasificacion
// 9 d: bienes_sin_iva
//10 d: servicios_sin_iva
//11 d: iva
//12 d: total_sin_iva
//13 d: total_con_iva
//14 s: periodo_pago
$stmt->bind_param(
  "sissssssddddds",
  $tipo,
  $proveedor_id,
  $nit,
  $numero_factura,
  $fecha_compra,
  $descripcion,
  $forma_pago,
  $clasificacion,
  $bienes_sin_iva,
  $serv_sin_iva,
  $iva,
  $total_sin_iva,
  $total_con_iva,
  $periodo_pago
);

$stmt->execute();
$stmt->close();

    header("Location: compras.php?success=1");
    exit;
}

// Cargo listas para los selects
$proveedores   = $conn->query("SELECT id, nombre, nit FROM proveedores ORDER BY nombre");
$tipos         = ['Factura electrónica','Factura especial','Nota de crédito'];
$formas_pago   = ['Efectivo','Cheque','Transferencia','Letra de cambio'];
$clasificaciones = ['Mercadería','Papelería','Equipo de cómputo']; // ajusta según necesites

// Autonúmero sencillo de factura
$numero_factura = 'F-' . date('YmdHis');
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
      <h3 class="mb-4">Registro de Compras (Internas)</h3>

      <?php if(isset($_GET['success'])): ?>
        <script>
          Swal.fire({
            icon:'success',
            title:'¡Factura guardada!',
            showConfirmButton:false,
            timer:1500
          });
        </script>
      <?php endif; ?>

      <form method="POST" class="row g-3 border p-4 rounded bg-light shadow-sm">
        <!-- A. Datos básicos -->
        <div class="col-md-4">
          <label class="form-label">Tipo de documento</label>
          <select name="tipo_documento" class="form-select" required>
            <?php foreach($tipos as $t): ?>
              <option><?= htmlspecialchars($t) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Proveedor</label>
          <select id="prov" name="proveedor_id" class="form-select" required>
            <option value="">-- elige proveedor --</option>
            <?php while($pr=$proveedores->fetch_assoc()): ?>
              <option 
                value="<?= $pr['id'] ?>"
                data-nit="<?= htmlspecialchars($pr['nit']) ?>"
              ><?= htmlspecialchars($pr['nombre']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">NIT</label>
          <input id="nit" name="nit" class="form-control" readonly>
        </div>

        <div class="col-md-4">
          <label class="form-label">Número de factura</label>
          <input name="numero_factura" class="form-control" readonly 
                 value="<?= $numero_factura ?>">
        </div>

        <div class="col-md-4">
          <label class="form-label">Fecha de compra</label>
          <input type="date" name="fecha_compra" class="form-control"
                 value="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="col-md-12">
          <label class="form-label">Descripción</label>
          <input type="text" name="descripcion" class="form-control">
        </div>

        <div class="col-md-4">
          <label class="form-label">Forma de pago</label>
          <select name="forma_pago" class="form-select">
            <?php foreach($formas_pago as $f): ?>
              <option><?= htmlspecialchars($f) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- B. Detalle contable -->
        <div class="col-md-4">
          <label class="form-label">Clasificación</label>
          <select name="clasificacion" class="form-select">
            <?php foreach($clasificaciones as $c): ?>
              <option><?= htmlspecialchars($c) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-2">
          <label class="form-label">Bienes sin IVA</label>
          <input type="number" step="0.01" name="bienes_sin_iva" id="bienes"
                 class="form-control" value="0">
        </div>
        <div class="col-md-2">
          <label class="form-label">Servicios sin IVA</label>
          <input type="number" step="0.01" name="servicios_sin_iva" id="serv"
                 class="form-control" value="0">
        </div>

        <div class="col-md-2">
          <label class="form-label">IVA (12%)</label>
          <input type="number" step="0.01" name="iva" id="iva"
                 class="form-control" readonly>
        </div>
        <div class="col-md-3">
          <label class="form-label">Total sin IVA</label>
          <input type="number" step="0.01" name="total_sin_iva" id="totSin"
                 class="form-control" readonly>
        </div>
        <div class="col-md-3">
          <label class="form-label">Total con IVA</label>
          <input type="number" step="0.01" name="total_con_iva" id="totCon"
                 class="form-control" readonly>
        </div>

        <div class="col-md-4">
          <label class="form-label">Periodo de compra</label>
          <input type="text" name="periodo_pago" class="form-control"
                 placeholder="Ej. 30 días crédito">
        </div>

        <div class="col-12 text-end">
          <button name="save_invoice" type="submit" class="btn btn-success">
            💾 Generar
          </button>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      // Al cambiar NIT, actualiza nombre
      document.getElementById('nitProveedor').addEventListener('change', function(){
        const opt = this.options[this.selectedIndex];
        document.getElementById('nombreProveedor').value = opt.getAttribute('data-nombre') || '';
      });
      // Agrega línea de detalle
      document.getElementById('addDetalle').addEventListener('click', function(){
        const tbody = document.querySelector('#detalleRows');
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>
            <select name="clasificacion_detalle[]" class="form-select">
              <option>Mercadería</option><option>Papelería</option><option>Equipo de cómputo</option>
            </select>
          </td>
          <td><input type="checkbox" name="afecto_iva[]"></td>
          <td><input type="number" name="cantidad[]" class="form-control" value="1"></td>
          <td><input type="number" name="precio_unitario[]" class="form-control" step="0.01"></td>
          <td><input type="number" name="subtotal[]" class="form-control" step="0.01" readonly></td>
        `;
        // Calcular subtotal al cambiar cantidad/precio
        tr.addEventListener('input', e=>{
          const row = e.target.closest('tr');
          const qty = parseFloat(row.querySelector('[name="cantidad[]"]').value)||0;
          const pu  = parseFloat(row.querySelector('[name="precio_unitario[]"]').value)||0;
          row.querySelector('[name="subtotal[]"]').value = (qty*pu).toFixed(2);
        });
        tbody.appendChild(tr);
      });
      // SweetAlert al guardar
      document.getElementById('facturaInternaForm').addEventListener('submit', function(e){
        e.preventDefault();
        Swal.fire({icon:'success',title:'Factura guardada!',timer:1500,showConfirmButton:false})
          .then(()=> this.submit());
      });
    </script>
    <script>
// al cambiar proveedor rellena el NIT
document.getElementById('prov').addEventListener('change', function(){
  let nit = this.options[this.selectedIndex].dataset.nit||'';
  document.getElementById('nit').value = nit;
});

// calcula IVA y totales
function calc() {
  let bienes = parseFloat(document.getElementById('bienes').value)||0;
  let serv   = parseFloat(document.getElementById('serv').value)||0;
  let sinIVA = bienes + serv;
  let iva    = sinIVA * 0.12;
  let conIVA = sinIVA + iva;
  document.getElementById('iva').value    = iva.toFixed(2);
  document.getElementById('totSin').value = sinIVA.toFixed(2);
  document.getElementById('totCon').value = conIVA.toFixed(2);
}
document.getElementById('bienes').addEventListener('input', calc);
document.getElementById('serv').addEventListener('input', calc);
calc();  // inicial

</script>
    <!--end::OverlayScrollbars Configure-->
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>
