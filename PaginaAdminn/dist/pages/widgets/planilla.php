<?php
session_start();
include 'conexion.php';




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

      <main class="app-main py-4">
  <div class="container">
    <h3 class="mb-4">Generar Planilla</h3>

<!-- Formulario -->
<form action="guardar_planilla.php" method="POST" class="row g-3 border p-4 rounded bg-light shadow-sm">
    <div class="col-md-6">
        <label class="form-label">Empleado</label>
        <select id="empleado" name="nombre" class="form-select" required>
            <option value="">Selecciona un empleado</option>
            <?php
            $empleados = mysqli_query($conn, "SELECT id, nombre, puesto, bonificacion, anticipo, salario FROM empleados");
            while ($emp = mysqli_fetch_assoc($empleados)) {
                echo "<option value='" . htmlspecialchars($emp['nombre']) . "' 
                        data-puesto='" . htmlspecialchars($emp['puesto']) . "' 
                        data-bonificacion='" . htmlspecialchars($emp['bonificacion']) . "' 
                        data-anticipo='" . htmlspecialchars($emp['anticipo']) . "' 
                        data-salario='" . htmlspecialchars($emp['salario']) . "'>" . htmlspecialchars($emp['id']) . "</option>";
            }
            ?>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Puesto</label>
        <input type="text" id="puesto" name="puesto" required class="form-control">
    </div>

    <div class="col-md-4">
        <label class="form-label">Sueldo base (Q)</label>
        <input type="number" step="0.01" id="sueldo_base" name="sueldo_base" required class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Horas extras</label>
        <input type="number" name="horas_extras" required class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Comisiones (Q)</label>
        <input type="number" step="0.01" name="comisiones" class="form-control">
    </div>

<div class="col-md-4">
  <label class="form-label">Bonificación (Q)</label>
  <input type="number" step="0.01" name="bonificacion" id="bonificacion" class="form-control">
</div>
<div class="col-md-4">
  <label class="form-label">Anticipo (Q)</label>
  <input type="number" step="0.01" name="anticipo"     id="anticipo"     class="form-control">
</div>  

    <div class="col-md-4">
        <label class="form-label">Descuentos judiciales (Q)</label>
        <input type="number" step="0.01" name="descuentos_judiciales" class="form-control">
    </div>

    <div class="col-md-12">
        <label class="form-label">Otros descuentos (Q)</label>
        <input type="number" step="0.01" name="otros_descuentos" class="form-control">
    </div>

    <div class="col-12 text-end">
        <button type="submit" class="btn btn-primary">Generar Planilla</button>
    </div>
    <a href="exportar_planilla_general.php" class="btn btn-danger mt-3">Exportar PDF</a>
</form>



<script>
document.addEventListener("DOMContentLoaded", () => {
    const empleadoSelect = document.getElementById('empleado');
    const puestoInput = document.getElementById('puesto');
    const sueldoInput = document.getElementById('sueldo_base');
    const bonificacionInput = document.getElementById('bonificacion');
    const anticipoInput = document.getElementById('anticipo');


    empleadoSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const puesto = selectedOption.getAttribute('data-puesto');
        const salario = selectedOption.getAttribute('data-salario');
        const bonificacion = selectedOption.getAttribute('data-bonificacion');
       const anticipo = selectedOption.getAttribute('data-anticipo'); 

        puestoInput.value = puesto ? puesto : '';
        sueldoInput.value = salario ? salario : '';
        bonificacionInput.value = bonificacion ? bonificacion : '';
        anticipoInput.value = anticipo; 
    });
});
</script>

    <!-- Aquí abajo se mostrará la planilla generada con SweetAlert -->
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script>
      Swal.fire({
        icon: 'success',
        title: '¡Planilla generada con éxito!',
        showConfirmButton: false,
        timer: 2000
      });
      </script>
    <?php endif; ?>

    <?php
    if (isset($_SESSION['ultimo_id'])):
      include 'conexion.php';
      $id = $_SESSION['ultimo_id'];
      $res = mysqli_query($conn, "SELECT * FROM planilla WHERE id = $id");
      if ($row = mysqli_fetch_assoc($res)):
    ?>
    <div class="card mt-5 border-success shadow">
      <div class="card-header bg-success text-white">Última Planilla Generada</div>
      <div class="card-body">
        <p><strong>Nombre:</strong> <?= htmlspecialchars($row['nombre']) ?></p>
        <p><strong>Puesto:</strong> <?= htmlspecialchars($row['puesto']) ?></p>
        <p><strong>Sueldo Base:</strong> Q<?= number_format($row['sueldo_base'], 2) ?></p>
        <p><strong>Horas Extras:</strong> <?= $row['horas_extras'] ?></p>
        <p><strong>Bonificación:</strong> Q<?= number_format($row['bonificacion'], 2) ?></p>
        <p><strong>Anticipo:</strong> Q<?= number_format($row['anticipo'],  2) ?></p>
        <p><strong>Total Ingresos:</strong> Q<?= number_format($row['total_ingresos'], 2) ?></p>
        <p><strong>Total Descuentos:</strong> Q<?= number_format($row['total_descuentos'], 2) ?></p>
        <p><strong>Líquido a recibir:</strong> <strong>Q<?= number_format($row['liquido_recibir'], 2) ?></strong></p>
      </div>
    </div>
    <?php
      unset($_SESSION['ultimo_id']);
      endif;
    endif;
    ?>
    <div class="container">
    <h3 class="mb-4">Lista de Planillas Generadas</h3>
    <table class="table table-bordered">
      <thead class="thead-light">
        <tr>
          <th>#</th>
          <th>Empleado</th>
          <th>Puesto</th>
          <th>Fecha</th>
          <th>Líquido a recibir</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $query = "SELECT id, nombre, puesto, fecha_registro, liquido_recibir 
                  FROM planilla 
                  ORDER BY fecha_registro DESC";
        $res = mysqli_query($conn, $query);
        $i = 1;
        while($row = mysqli_fetch_assoc($res)):
      ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($row['nombre']) ?></td>
          <td><?= htmlspecialchars($row['puesto']) ?></td>
          <td><?= htmlspecialchars($row['fecha_registro']) ?></td>
          <td>Q <?= number_format($row['liquido_recibir'],2) ?></td>
          <td>
            <a href="ver_planilla.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">Ver</a>
            <a href="exportar_planilla_pdf.php?id=<?= $row['id'] ?>" target="_blank" class="btn btn-sm btn-danger">
              PDF
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>

          <!-- ******************************* -->
      <!-- Aquí agregamos el botón “Certificado” -->
      <div class="text-end my-4">
        <button 
          type="button" 
          class="btn btn-success" 
          onclick="window.open('exportar_certificado_pdf.php', '_blank')"
        >
          🖨️ Realizar Certificado
        </button>
      </div>
      <!-- ******************************* -->

  </div>
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

    <!--end::OverlayScrollbars Configure-->
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>