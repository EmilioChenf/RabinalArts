<?php
// gestion_de_cuentas.php
include 'conexion.php';

// 1) BORRAR CUENTA
if (isset($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM cuentas_contables WHERE id = ?");
    $stmt->bind_param("i", $del_id);
    $stmt->execute();
    header("Location: gestion_de_cuentas.php?msg=deleted");
    exit;
}

// 2) CREAR / ACTUALIZAR CUENTA (con las nuevas banderas)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_account'])) {
    $id            = intval($_POST['id'] ?? 0);
    $nombre        = trim($_POST['nombre']);
    $perdida       = isset($_POST['es_perdida'])  ? 1 : 0; // Pérdida   (antes nominal_egreso)
    $ganancia      = isset($_POST['es_ganancia']) ? 1 : 0; // Ganancia  (antes nominal_ingreso)
    $activo        = isset($_POST['es_activo'])   ? 1 : 0; // Activo    (antes balance_deudor)
    $pasivo        = isset($_POST['es_pasivo'])   ? 1 : 0; // Pasivo    (antes balance_acreedor)
    $clasificacion = trim($_POST['clasificacion']);

    if ($id > 0) {
        $stmt = $conn->prepare("
          UPDATE cuentas_contables
             SET nombre = ?, es_perdida = ?, es_ganancia = ?, es_activo = ?, es_pasivo = ?, clasificacion = ?
           WHERE id = ?
        ");
        $stmt->bind_param("siiiisi",
            $nombre, $perdida, $ganancia, $activo, $pasivo, $clasificacion, $id
        );
        $stmt->execute();
        $msg = 'updated';
    } else {
        $stmt = $conn->prepare("
          INSERT INTO cuentas_contables
            (nombre, es_perdida, es_ganancia, es_activo, es_pasivo, clasificacion)
          VALUES (?,?,?,?,?,?)
        ");
        $stmt->bind_param("siiiis",
            $nombre, $perdida, $ganancia, $activo, $pasivo, $clasificacion
        );
        $stmt->execute();
        $msg = 'created';
    }
    header("Location: gestion_de_cuentas.php?msg=$msg");
    exit;
}

// 3) PREFILL PARA EDITAR
$edit = null;
if (isset($_GET['edit'])) {
    $eid = intval($_GET['edit']);
    $res = $conn->query("SELECT * FROM cuentas_contables WHERE id = $eid");
    $edit = $res ? $res->fetch_assoc() : null;
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

    <!-- Título + botón (abre modal de Partida Inicial) -->
    <div class="d-flex align-items-center gap-2 mb-3">
      <h3 class="m-0">Gestión de Cuentas Contables</h3>
      <button class="btn btn-dark ms-2" data-bs-toggle="modal" data-bs-target="#modalPartidaInicial">
        <i class="bi bi-lightning-charge"></i> Crear Partida Inicial
      </button>
    </div>

    <!-- SweetAlert según acción -->
    <?php if (isset($_GET['msg'])): ?>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        let m = "<?= $_GET['msg'] ?>";
        let titles = {
          created: '¡Cuenta creada!',
          updated: '¡Cuenta actualizada!',
          deleted: '¡Cuenta eliminada!'
        };
        Swal.fire({ icon:'success', title: titles[m] || '¡Hecho!', timer: 1500, showConfirmButton: false });
      });
    </script>
    <?php endif; ?>

    <!-- FORMULARIO CREAR / EDITAR -->
    <form method="POST" class="row g-3 border p-4 rounded bg-light mb-5">
      <input type="hidden" name="id" value="<?= isset($edit['id']) ? (int)$edit['id'] : 0 ?>">

      <div class="col-md-6">
        <label class="form-label">Nombre de cuenta</label>
        <input type="text" name="nombre" required
               class="form-control"
               value="<?= htmlspecialchars($edit['nombre'] ?? '', ENT_QUOTES) ?>">
      </div>

      <div class="col-md-6">
        <label class="form-label">Clasificación</label>
        <input type="text" name="clasificacion" required
               class="form-control"
               value="<?= htmlspecialchars($edit['clasificacion'] ?? '', ENT_QUOTES) ?>">
      </div>

      <!-- Orden requerido: Pérdida - Ganancia - Activo - Pasivo -->
      <div class="col-12 d-flex flex-wrap gap-4">
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="perdida" name="es_perdida"
                 <?= (!empty($edit) && !empty($edit['es_perdida'])) ? 'checked' : '' ?>>
          <label for="perdida" class="form-check-label"><strong>Pérdida</strong></label>
        </div>

        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="ganancia" name="es_ganancia"
                 <?= (!empty($edit) && !empty($edit['es_ganancia'])) ? 'checked' : '' ?>>
          <label for="ganancia" class="form-check-label"><strong>Ganancia</strong></label>
        </div>

        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="activo" name="es_activo"
                 <?= (!empty($edit) && !empty($edit['es_activo'])) ? 'checked' : '' ?>>
          <label for="activo" class="form-check-label"><strong>Activo</strong></label>
        </div>

        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="pasivo" name="es_pasivo"
                 <?= (!empty($edit) && !empty($edit['es_pasivo'])) ? 'checked' : '' ?>>
          <label for="pasivo" class="form-check-label"><strong>Pasivo</strong></label>
        </div>
      </div>

      <div class="col-12 text-end">
        <button name="save_account" type="submit"
                class="btn btn-<?= !empty($edit) ? 'warning' : 'primary' ?>">
          <?= !empty($edit) ? 'Actualizar' : 'Crear' ?>
        </button>
        <?php if (!empty($edit)): ?>
          <a href="gestion_de_cuentas.php" class="btn btn-secondary">Cancelar</a>
        <?php endif; ?>
      </div>
    </form>

    <!-- LISTADO DE CUENTAS -->
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Pérd.</th>
          <th>Gan.</th>
          <th>Activo</th>
          <th>Pasivo</th>
          <th>Clasificación</th>
          <th style="width:220px">Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php
      $all = $conn->query("SELECT * FROM cuentas_contables ORDER BY id DESC");
      while ($row = $all->fetch_assoc()):
      ?>
        <tr>
          <td><?= (int)$row['id'] ?></td>
          <td><?= htmlspecialchars($row['nombre'], ENT_QUOTES) ?></td>
          <td><?= !empty($row['es_perdida'])  ? '✔' : '—' ?></td>
          <td><?= !empty($row['es_ganancia']) ? '✔' : '—' ?></td>
          <td><?= !empty($row['es_activo'])   ? '✔' : '—' ?></td>
          <td><?= !empty($row['es_pasivo'])   ? '✔' : '—' ?></td>
          <td><?= htmlspecialchars($row['clasificacion'], ENT_QUOTES) ?></td>
          <td class="d-flex gap-2">
            <a href="?edit=<?= (int)$row['id'] ?>" class="btn btn-sm btn-warning">✎</a>
            <a href="?delete=<?= (int)$row['id'] ?>"
               onclick="return confirm('¿Eliminar esta cuenta?')"
               class="btn btn-sm btn-danger">🗑</a>

            <!-- Botón Saldos -->
            <button type="button"
                    class="btn btn-sm btn-info btn-saldos"
                    data-id="<?= (int)$row['id'] ?>"
                    data-nombre="<?= htmlspecialchars($row['nombre'], ENT_QUOTES) ?>"
                    data-bs-toggle="modal"
                    data-bs-target="#modalSaldos">
              Saldos
            </button>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <!-- Modal Saldos -->
  <div class="modal fade" id="modalSaldos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            Saldos de la cuenta <span id="tituloCuenta"></span>
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body p-0" style="min-height:60vh">
          <iframe id="iframeSaldos" src="" style="border:0;width:100%;height:60vh"></iframe>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Cargar saldo_cuentas.php en el iframe al abrir el modal
    document.addEventListener('click', (e) => {
      const btn = e.target.closest('.btn-saldos');
      if (!btn) return;

      const id = parseInt(btn.dataset.id || '0', 10);
      const nombre = btn.dataset.nombre || '';
      const iframe = document.getElementById('iframeSaldos');
      const titulo = document.getElementById('tituloCuenta');

      iframe.src = 'saldo_cuentas.php?cuenta_id=' + encodeURIComponent(id);
      titulo.textContent = `#${id} — ${nombre}`;
    });

    // Limpiar el iframe al cerrar el modal (libera recursos)
    const modalEl = document.getElementById('modalSaldos');
    if (modalEl) {
      modalEl.addEventListener('hidden.bs.modal', () => {
        const iframe = document.getElementById('iframeSaldos');
        if (iframe) iframe.src = '';
      });
    }
  </script>

  <!-- Modal: Partida Inicial -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet"/>

  <div class="modal fade" id="modalPartidaInicial" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title">
            <i class="bi bi-journal-plus me-2"></i> Partida Inicial (Apertura)
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <?php
            // Traer cuentas para el selector
            $cuentasSel = $conn->query("SELECT id, nombre, clasificacion FROM cuentas_contables ORDER BY nombre ASC");
            $opciones = [];
            while($c = $cuentasSel->fetch_assoc()){
              $opciones[] = $c;
            }
          ?>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Descripción</label>
              <input type="text" id="ap_desc" class="form-control" placeholder="Ej.: Partida de apertura al 01/01/2025">
            </div>
            <div class="col-md-6">
              <label class="form-label">Fecha del asiento</label>
              <input type="datetime-local" id="ap_fecha" class="form-control"
                     value="<?= date('Y-m-01')?>T00:00">
              <div class="form-text">Usa una fecha al inicio del ejercicio para que aparezca de primera en el Libro Diario.</div>
            </div>
          </div>

          <div class="row g-3 mb-2">
            <div class="col-md-6">
              <label class="form-label fw-bold">Cuentas al DEBE</label>
              <select id="ap_selectDebe" class="form-select" multiple>
                <?php foreach($opciones as $o): ?>
                  <option value="<?= (int)$o['id'] ?>"
                          data-nombre="<?= htmlspecialchars($o['nombre'], ENT_QUOTES) ?>"
                          data-clasificacion="<?= htmlspecialchars($o['clasificacion'], ENT_QUOTES) ?>">
                    <?= htmlspecialchars($o['nombre']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Cuentas al HABER</label>
              <select id="ap_selectHaber" class="form-select" multiple>
                <?php foreach($opciones as $o): ?>
                  <option value="<?= (int)$o['id'] ?>"
                          data-nombre="<?= htmlspecialchars($o['nombre'], ENT_QUOTES) ?>"
                          data-clasificacion="<?= htmlspecialchars($o['clasificacion'], ENT_QUOTES) ?>">
                    <?= htmlspecialchars($o['nombre']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <style>
            .ap-table { border:2px solid #000; }
            .ap-table th, .ap-table td { border:1px solid #000; padding:8px; vertical-align:middle; }
            .ap-table thead th { background:#f2f2f2; text-align:center; }
            .ap-cuenta { text-transform:uppercase; font-weight:600; }
            .ap-importe { width: 160px; text-align: right; }
            .ap-readonly { background:#e9ecef!important; color:#666; }
          </style>

          <table class="table ap-table mt-3" id="ap_tabla">
            <thead>
              <tr>
                <th>Cuenta</th>
                <th class="ap-importe">Debe</th>
                <th class="ap-importe">Haber</th>
              </tr>
            </thead>
            <tbody id="ap_tbody"></tbody>
            <tfoot>
              <tr>
                <th class="text-end">TOTALES</th>
                <th class="text-end"><span id="ap_total_debe">0.00</span></th>
                <th class="text-end"><span id="ap_total_haber">0.00</span></th>
              </tr>
            </tfoot>
          </table>

        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button id="ap_btnGuardar" class="btn btn-dark">
            <i class="bi bi-save2"></i> Guardar Partida Inicial
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- libs -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script>
  (function(){
    function seccionPara(clasif){
      if(!clasif) return 'otros';
      const c = clasif.toLowerCase();
      if (c.includes('pasivo')) return 'pasivo';
      if (c.includes('patrimonio') || c.includes('capital')) return 'patrimonio';
      if (c.includes('activo')) return 'activo';
      return 'otros';
    }
    function ordenSeccion(seccion){
      if (seccion==='activo') return 1;
      if (seccion==='pasivo') return 2;
      if (seccion==='patrimonio') return 3;
      return 4;
    }

    function reordenarTabla(){
      let filas = [];
      $('#ap_tbody tr[data-id]').each(function(){
        filas.push({
          orden: ordenSeccion($(this).data('seccion')),
          el: $(this).detach()
        });
      });
      filas.sort((a,b)=>a.orden-b.orden);
      filas.forEach(f=>$('#ap_tbody').append(f.el));
    }

    function recalcular(){
      let d=0,h=0;
      $('#ap_tbody .ap-debe').each(function(){ d += parseFloat($(this).val())||0; });
      $('#ap_tbody .ap-haber').each(function(){ h += parseFloat($(this).val())||0; });
      $('#ap_total_debe').text(d.toFixed(2));
      $('#ap_total_haber').text(h.toFixed(2));
    }

    function bindChange(selId, tipo){
      $('#'+selId).on('change', function(){
        const vals = $(this).val()||[];
        $('#'+selId+' option').each(function(){
          const id   = this.value;
          const nom  = $(this).data('nombre');
          const clas = $(this).data('clasificacion');
          const sec  = seccionPara(clas);
          const existe = vals.includes(id);
          const rowSel = $(`tr[data-id="${id}"][data-tipo="${tipo}"]`);

          if (existe && !rowSel.length){
            const readonlyDebe = (tipo==='haber') ? 'ap-readonly' : '';
            const readonlyHab  = (tipo==='debe')  ? 'ap-readonly' : '';
            const row = $(`
              <tr data-id="${id}" data-tipo="${tipo}" data-seccion="${sec}">
                <td class="ap-cuenta">${nom}</td>
                <td><input type="number" step="0.01" class="form-control ap-debe ap-importe ${readonlyDebe}" ${tipo==='haber'?'readonly':''} value=""></td>
                <td><input type="number" step="0.01" class="form-control ap-haber ap-importe ${readonlyHab}" ${tipo==='debe'?'readonly':''} value=""></td>
              </tr>
            `);
            $('#ap_tbody').append(row);
            reordenarTabla();
          }
          if (!existe && rowSel.length){
            rowSel.remove();
          }
        });
        recalcular();
      });
    }

    // Inicializa Select2 cuando se abre el modal
    document.getElementById('modalPartidaInicial')?.addEventListener('shown.bs.modal', () => {
      $('#ap_selectDebe, #ap_selectHaber').select2({
        theme:'bootstrap-5',
        placeholder:'Escribe para buscar...',
        width:'100%'
      });
    });

    bindChange('ap_selectDebe','debe');
    bindChange('ap_selectHaber','haber');
    $(document).on('input change keyup', '.ap-debe, .ap-haber', recalcular);

    $('#ap_btnGuardar').on('click', async function(){
      const desc  = $('#ap_desc').val().trim();
      const fecha = $('#ap_fecha').val().trim();

      let detalles = [];
      $('#ap_tbody tr[data-id]').each(function(){
        const id   = parseInt($(this).data('id'),10);
        const debe = parseFloat($(this).find('.ap-debe').val())  || 0;
        const haber= parseFloat($(this).find('.ap-haber').val()) || 0;
        if (debe>0 || haber>0) detalles.push({cuenta_id:id, debe:debe, haber:haber});
      });

      const d = parseFloat($('#ap_total_debe').text()) || 0;
      const h = parseFloat($('#ap_total_haber').text()) || 0;

      if (!desc) return Swal.fire({icon:'error', title:'Falta descripción'});
      if (Math.abs(d-h) > 0.005) return Swal.fire({icon:'error', title:'Debe debe igualar a Haber'});
      if (detalles.length===0) return Swal.fire({icon:'error', title:'Agrega al menos una cuenta con importe'});

      try {
        const resp = await fetch('guardar_partida_inicial.php', {
          method: 'POST',
          headers: {'Content-Type':'application/json'},
          body: JSON.stringify({ descripcion: desc, fecha: fecha, detalles: detalles })
        });
        const data = await resp.json();
        if (!data.success) throw new Error(data.message||'Error desconocido');

        await Swal.fire({icon:'success', title:'Partida de apertura creada', timer:1300, showConfirmButton:false});
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalPartidaInicial'));
        modal?.hide();
        $('#ap_desc').val('');
        $('#ap_selectDebe').val(null).trigger('change');
        $('#ap_selectHaber').val(null).trigger('change');
        $('#ap_tbody').empty();
        recalcular();
      } catch (e) {
        Swal.fire({icon:'error', title:'No se pudo guardar', text:e.message});
      }
    });
  })();
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
