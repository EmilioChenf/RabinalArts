<?php
// compras.php (o el archivo donde tienes el form)
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_invoice'])) {
    // 1) Decodificamos el JSON
    $compras = json_decode($_POST['compras_data'], true);
    if (!is_array($compras) || empty($compras)) {
        die("No hay datos de compras para guardar.");
    }

    // 2) Preparamos el INSERT (adaptar columnas según tu tabla)
    $stmt = $conn->prepare("
      INSERT INTO compras_internas
        (forma_pago, periodo_pago, nombre_producto, numero_cuenta_contable,
         valor_iva, valor_sin_iva, total_producto_sin_iva, total_iva,
         total_sin_iva_general, total_general)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    if (!$stmt) {
        die("Error al preparar statement: " . $conn->error);
    }

    // 3) Recorremos cada compra y la insertamos
    foreach ($compras as $c) {
        $stmt->bind_param(
          'ssssdddddd',
          $c['forma_pago'],
          $c['periodo_pago'],
          $c['nombre_producto'],
          $c['numero_cuenta_contable'],
          $c['valor_iva'],
          $c['valor_sin_iva'],
          $c['valor_sin_iva'],          // total_producto_sin_iva = valor_sin_iva
          $c['valor_iva'],               // total_iva = valor_iva
          $c['total_sin_iva_general'],
          $c['total_general']
        );
        if (! $stmt->execute()) {
            die("Error al insertar compra: " . $stmt->error);
        }
    }
    $stmt->close();

    // 4) Redirigimos con éxito
    header("Location: compras.php?success=1");
    exit;
}

// opciones de forma de pago
$formas_pago = ['Efectivo', 'Crédito', 'Crédito con documentos: Cheque'];
?>

<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
     <link rel="stylesheet" href="/dist/css/adminlte.min.css">
  <!-- …otros CSS de bootstrap, plugins, etc… -->

  <!-- Aquí, **TU** CSS personalizado -->
  <link rel="stylesheet" href="../../../dist/css/custom.css"> 
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
  <div class="container">


      <!-- Logo en la esquina superior -->
    <div style="position: relative;">
      <img src="../../../dist/assets/img/rabi.png" 
           alt="Logo Rabinalarts" 
           style="position: absolute; top: 0; right: 0; height: 60px;">
    </div>

    <h3 class="mb-4">Registro de Compras Internas</h3>

    <?php if (isset($_GET['success'])): ?>
      <script>
        Swal.fire({
          icon: 'success',
          title: '¡Compras registradas!',
          showConfirmButton: false,
          timer: 1500
        });
      </script>
    <?php endif; ?>

    <form id="comprasForm" method="POST" novalidate class="row g-3 border p-4 rounded bg-light shadow-sm">
      <div class="col-md-4">
        <label class="form-label">Forma de pago</label>
        <select name="forma_pago" id="forma_pago" class="form-select" required>
          <?php foreach ($formas_pago as $f): ?>
            <option><?= htmlspecialchars($f) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Periodo de compra</label>
        <input type="text" name="periodo_pago" id="periodo_pago" class="form-control" placeholder="Ej. 30 días crédito" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Nombre del producto comprado</label>
        <input type="text" name="nombre_producto" id="nombre_producto" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Número de cuenta contable</label>
        <input type="text" name="numero_cuenta_contable" id="numero_cuenta_contable" class="form-control" required>
      </div>

      <!-- Aquí el precio del producto, ya incluye IVA -->
      <div class="col-md-4">
        <label class="form-label">Precio del producto (incluye IVA 12%)</label>
        <input type="number" step="0.01" name="precio_producto" id="precio_producto" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Valor sin IVA</label>
        <input type="number" step="0.01" name="valor_sin_iva" id="valor_sin_iva" class="form-control" readonly>
      </div>
      <div class="col-md-4">
        <label class="form-label">Valor del IVA</label>
        <input type="number" step="0.01" name="valor_iva" id="valor_iva" class="form-control" readonly>
      </div>
      <div class="col-md-4">
        <label class="form-label">Total sin IVA</label>
        <input type="number" step="0.01" name="total_sin_iva_general" id="total_sin_iva_general" class="form-control" readonly>
      </div>
      <div class="col-md-4">
        <label class="form-label">Total general</label>
        <input type="number" step="0.01" name="total_general" id="total_general" class="form-control" readonly>
      </div>

      <div class="col-12 text-start">
        <button type="button" id="addCompra" class="btn btn-secondary">＋ Agregar Compra</button>
      </div>

      <div class="col-12 mt-4">
        <h5>Compras a registrar</h5>
        <table class="table table-sm table-bordered" id="comprasTable">
          <thead class="table-light">
            <tr>
              <th>#</th><th>Forma pago</th><th>Periodo</th><th>Producto</th>
              <th>Cuenta</th><th>Precio</th><th>Valor sin IVA</th>
              <th>Valor IVA</th><th>Total sin IVA</th><th>Total general</th><th>Acción</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>

      <input type="hidden" name="compras_data" id="compras_data" value="">

      <div class="col-12 text-end">
        <button name="save_invoice" type="submit" class="btn btn-success">💾 Registrar Compras</button>
      </div>
    </form>
  </div>
</main>

<script>
(function() {
  const compras = [];
  const tableBody = document.querySelector('#comprasTable tbody');
  const formaPagoEl          = document.getElementById('forma_pago');
  const periodoPagoEl        = document.getElementById('periodo_pago');
  const nombreProductoEl     = document.getElementById('nombre_producto');
  const cuentaContableEl     = document.getElementById('numero_cuenta_contable');
  const precioEl             = document.getElementById('precio_producto');
  const valorSinIvaEl        = document.getElementById('valor_sin_iva');
  const valorIvaEl           = document.getElementById('valor_iva');
  const totalSinIvaGenEl     = document.getElementById('total_sin_iva_general');
  const totalGeneralEl       = document.getElementById('total_general');
  const comprasDataEl        = document.getElementById('compras_data');
  const addBtn               = document.getElementById('addCompra');
  const formEl               = document.getElementById('comprasForm');

  // Recalcular a partir del precio con IVA
  function recalc() {
    const price  = parseFloat(precioEl.value) || 0;
    const sinIva = price / 1.12;
    const iva    = price - sinIva;
    valorSinIvaEl.value    = sinIva.toFixed(2);
    valorIvaEl.value       = iva.toFixed(2);
    totalSinIvaGenEl.value = sinIva.toFixed(2);
    totalGeneralEl.value   = price.toFixed(2);
  }
  precioEl.addEventListener('input', recalc);
  recalc();

  addBtn.addEventListener('click', function() {
    const compra = {
      forma_pago:              formaPagoEl.value.trim(),
      periodo_pago:            periodoPagoEl.value.trim(),
      nombre_producto:         nombreProductoEl.value.trim(),
      numero_cuenta_contable:  cuentaContableEl.value.trim(),
      precio_producto:         parseFloat(precioEl.value)            || 0,
      valor_sin_iva:           parseFloat(valorSinIvaEl.value)       || 0,
      valor_iva:               parseFloat(valorIvaEl.value)          || 0,
      total_sin_iva_general:   parseFloat(totalSinIvaGenEl.value)    || 0,
      total_general:           parseFloat(totalGeneralEl.value)      || 0
    };
    if (!compra.forma_pago || !compra.periodo_pago ||
        !compra.nombre_producto || !compra.numero_cuenta_contable ||
        compra.precio_producto <= 0) {
      return Swal.fire('Completa todos los campos y un precio válido.', '', 'warning');
    }
    compras.push(compra);

    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${compras.length}</td>
      <td>${compra.forma_pago}</td>
      <td>${compra.periodo_pago}</td>
      <td>${compra.nombre_producto}</td>
      <td>${compra.numero_cuenta_contable}</td>
      <td>Q${compra.precio_producto.toFixed(2)}</td>
      <td>Q${compra.valor_sin_iva.toFixed(2)}</td>
      <td>Q${compra.valor_iva.toFixed(2)}</td>
      <td>Q${compra.total_sin_iva_general.toFixed(2)}</td>
      <td>Q${compra.total_general.toFixed(2)}</td>
      <td><button type="button" class="btn btn-sm btn-danger btn-remove">✕</button></td>
    `;
    tableBody.appendChild(tr);
    tr.querySelector('.btn-remove').addEventListener('click', () => {
      const idx = Array.from(tableBody.children).indexOf(tr);
      compras.splice(idx, 1);
      tr.remove();
      Array.from(tableBody.children).forEach((r,i) =>
        r.firstElementChild.textContent = i+1
      );
    });

    formEl.reset();
    recalc();
  });

  formEl.addEventListener('submit', function(e) {
    if (compras.length === 0) {
      e.preventDefault();
      return Swal.fire('Agrega al menos una compra antes de registrar.', '', 'error');
    }
    comprasDataEl.value = JSON.stringify(compras);
  });
})();
</script>
  <footer class="app-footer">
      <div class="float-end d-none d-sm-inline">RabinalArts</div>
      <strong>&copy; 2014-2025</strong> Todos los derechos reservados.
    </footer>
  </div>

  <script src="../../../dist/js/adminlte.js"></script>
  <script>
  (function() {
    const compras = [];
    const tableBody = document.querySelector('#comprasTable tbody');
    const formaPagoEl = document.getElementById('forma_pago');
    const periodoPagoEl = document.getElementById('periodo_pago');
    const nombreProductoEl = document.getElementById('nombre_producto');
    const cuentaContableEl = document.getElementById('numero_cuenta_contable');
    const valorIvaEl = document.getElementById('valor_iva');
    const valorSinIvaEl = document.getElementById('valor_sin_iva');
    const totalSinIvaProdEl = document.getElementById('total_producto_sin_iva');
    const totalIvaEl = document.getElementById('total_iva');
    const totalSinIvaGenEl = document.getElementById('total_sin_iva_general');
    const totalGeneralEl = document.getElementById('total_general');
    const comprasDataEl = document.getElementById('compras_data');
    const addBtn = document.getElementById('addCompra');
    const formEl = document.getElementById('comprasForm');

    function recalc() {
      const iva = parseFloat(valorIvaEl.value) || 0;
      const sin = parseFloat(valorSinIvaEl.value) || 0;
      const total = sin + iva;
      totalSinIvaProdEl.value = sin.toFixed(2);
      totalIvaEl.value = iva.toFixed(2);
      totalSinIvaGenEl.value = sin.toFixed(2);
      totalGeneralEl.value = total.toFixed(2);
    }
    [valorIvaEl, valorSinIvaEl].forEach(el => el.addEventListener('input', recalc));
    recalc();

    addBtn.addEventListener('click', () => {
      const compra = {
        forma_pago: formaPagoEl.value.trim(),
        periodo_pago: periodoPagoEl.value.trim(),
        nombre_producto: nombreProductoEl.value.trim(),
        numero_cuenta_contable: cuentaContableEl.value.trim(),
        valor_iva: parseFloat(valorIvaEl.value) || 0,
        valor_sin_iva: parseFloat(valorSinIvaEl.value) || 0,
        total_producto_sin_iva: parseFloat(totalSinIvaProdEl.value) || 0,
        total_iva: parseFloat(totalIvaEl.value) || 0,
        total_sin_iva_general: parseFloat(totalSinIvaGenEl.value) || 0,
        total_general: parseFloat(totalGeneralEl.value) || 0
      };
      if (!compra.forma_pago || !compra.periodo_pago ||
          !compra.nombre_producto || !compra.numero_cuenta_contable) {
        return Swal.fire('Completa todos los campos antes de agregar.', '', 'warning');
      }
      compras.push(compra);
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${compras.length}</td>
        <td>${compra.forma_pago}</td>
        <td>${compra.periodo_pago}</td>
        <td>${compra.nombre_producto}</td>
        <td>${compra.numero_cuenta_contable}</td>
        <td>Q${compra.valor_iva.toFixed(2)}</td>
        <td>Q${compra.valor_sin_iva.toFixed(2)}</td>
        <td>Q${compra.total_producto_sin_iva.toFixed(2)}</td>
        <td>Q${compra.total_iva.toFixed(2)}</td>
        <td>Q${compra.total_general.toFixed(2)}</td>
        <td><button type="button" class="btn btn-sm btn-danger btn-remove">✕</button></td>
      `;
      tableBody.appendChild(tr);
      tr.querySelector('.btn-remove').addEventListener('click', () => {
        const idx = Array.from(tableBody.children).indexOf(tr);
        compras.splice(idx, 1);
        tr.remove();
        Array.from(tableBody.children).forEach((r, i) => r.firstChild.textContent = i+1);
      });
      formEl.reset();
      recalc();
    });

    formEl.addEventListener('submit', e => {
      if (compras.length === 0) {
        e.preventDefault();
        return Swal.fire('Agrega al menos una compra antes de registrar.', '', 'error');
      }
      comprasDataEl.value = JSON.stringify(compras);
    });
  })();
  </script>


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
