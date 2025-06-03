
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
    <h3 class="mb-4">Libro de Inventarios</h3>

    <form id="form-inventario" method="POST" action="procesar_inventario.php">
      <!-- 1) Clasificación -->
      <div class="mb-3">
        <label for="clasificacion" class="form-label">Clasificación</label>
        <select id="clasificacion" name="clasificacion" class="form-select" required>
          <option value="">-- Selecciona --</option>
          <option>ACTIVO CORRIENTE DISPONIBLE</option>
          <option>ACTIVO CORRIENTE EXIGIBLE</option>
          <option>ACTIVO CORRIENTE REALIZABLE</option>
          <option>ACTIVO NO CORRIENTE</option>
          <option>PASIVO CORRIENTE</option>
          <option>PASIVO NO CORRIENTE</option>
          <option>CUENTA DE CAPITAL</option>
        </select>
      </div>

      <!-- 2) Panel de búsqueda y detalle -->
      <div id="panel-cuentas" style="display:none;">
        <div class="row g-3 align-items-end">
          <div class="col-md-6">
            <label for="busqueda-cuenta" class="form-label">Buscar cuenta</label>
            <input 
              type="text" 
              id="busqueda-cuenta" 
              class="form-control mb-2" 
              placeholder="Escribe nombre de cuenta..."
              list="lista-cuentas" 
            >
            <datalist id="lista-cuentas">
              <?php while($r = $cuentas->fetch_assoc()): ?>
                <option data-id="<?= $r['id'] ?>" value="<?= htmlspecialchars($r['nombre']) ?>"></option>
              <?php endwhile; ?>
            </datalist>
          </div>
          <div class="col-md-4">
            <label class="form-label">Monto (Q)</label>
            <input type="number" step="0.01" id="monto-cuenta" class="form-control" placeholder="Ej. 1234.56" disabled>
          </div>
          <div class="col-md-2">
            <button type="button" id="btn-agregar" class="btn btn-secondary w-100" disabled>Agregar</button>
          </div>
        </div>

        <!-- 3) Tabla de detalle -->
        <table class="table table-bordered mt-4">
          <thead>
            <tr>
              <th>Cuenta</th>
              <th class="text-end">Monto (Q)</th>
              <th>Quitar</th>
            </tr>
          </thead>
          <tbody id="detalle-body"></tbody>
        </table>
      </div>

      <!-- 4) Botón Guardar -->
      <button type="submit" class="btn btn-primary mt-3">Guardar Inventario</button>
    </form>
  </div>

  <script>
    const panel       = document.getElementById('panel-cuentas');
    const buscador    = document.getElementById('busqueda-cuenta');
    const montoInp    = document.getElementById('monto-cuenta');
    const btnAgr      = document.getElementById('btn-agregar');
    const tbody       = document.getElementById('detalle-body');
    let detalle = [];

    // Mostrar panel al elegir clasificación
    document.getElementById('clasificacion')
      .addEventListener('change', e => {
        panel.style.display = e.target.value ? 'block' : 'none';
      });

    // Activar monto/boton al seleccionar cuenta válida
    buscador.addEventListener('input', () => {
      const val = buscador.value;
      const opt = Array.from(document.querySelectorAll('#lista-cuentas option'))
        .find(o => o.value === val);
      if (opt) {
        montoInp.disabled = false;
        btnAgr.disabled   = false;
        montoInp.focus();
      } else {
        montoInp.value    = '';
        montoInp.disabled = true;
        btnAgr.disabled   = true;
      }
    });

    // Agregar al detalle
    btnAgr.addEventListener('click', () => {
      const nombre = buscador.value;
      const opt    = Array.from(document.querySelectorAll('#lista-cuentas option'))
                        .find(o => o.value === nombre);
      const id     = opt?.dataset.id;
      const monto  = parseFloat(montoInp.value);
      if (!id || isNaN(monto)) {
        Swal.fire('Error','Selecciona cuenta válida y monto','error');
        return;
      }
      if (detalle.some(d => d.id === id)) {
        Swal.fire('Aviso','Ya agregaste esa cuenta','warning');
        return;
      }
      detalle.push({id,nombre,monto});
      renderDetalle();
      // reset
      buscador.value = '';
      montoInp.value = '';
      montoInp.disabled = true;
      btnAgr.disabled   = true;
    });

    function renderDetalle() {
      tbody.innerHTML = '';
      // borrar inputs ocultos previos
      document.querySelectorAll('input[name="cuenta_ids[]"], input[name="montos[]"]')
        .forEach(n=>n.remove());

      detalle.forEach((row, i) => {
        // fila con input editable
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${row.nombre}</td>
          <td class="text-end">
            <input 
              type="number" step="0.01" 
              class="form-control monto-edit text-end" 
              style="width:6rem; display:inline-block;"
              value="${row.monto.toFixed(2)}"
            >
          </td>
          <td class="text-center">
            <button type="button" class="btn btn-sm btn-danger">×</button>
          </td>`;
        // eliminar
        tr.querySelector('button').onclick = () => {
          detalle.splice(i,1);
          renderDetalle();
        };
        tbody.appendChild(tr);

        // crear ocultos con IDs únicos
        const f1 = document.createElement('input');
        f1.type = 'hidden'; 
        f1.name = 'cuenta_ids[]'; 
        f1.value= row.id;

        const f2 = document.createElement('input');
        f2.type = 'hidden'; 
        f2.name = 'montos[]';      
        f2.id   = `monto_hidden_${i}`; 
        f2.value= row.monto;

        document.getElementById('form-inventario').append(f1, f2);

        // atachar edición inline
        tr.querySelector('.monto-edit')
          .addEventListener('change', e => {
            let val = parseFloat(e.target.value);
            if (isNaN(val)) return;
            detalle[i].monto = val;
            document.getElementById(`monto_hidden_${i}`).value = val;
          });
      });
    }
  </script>
      <script>
      document.getElementById('form-inventario').addEventListener('submit', function(e) {
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
