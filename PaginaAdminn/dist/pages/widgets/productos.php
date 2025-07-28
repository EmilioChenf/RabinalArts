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
x

            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->
      <!--begin::App Main-->


      


<main class="app-main">
  <!-- Encabezado Gestión de Productos Externos -->
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0">Gestión de Productos</h3>
        </div>
      </div>
    </div>
  </div>
  <div class="app-content">
    <div class="container-fluid">

      <!-- Formulario de Productos Externos -->
      <div class="card card-primary mb-4">
        <div class="card-header"><h3 class="card-title">Agregar nuevo producto</h3></div>
        <div class="card-body">
          <form action="crear_producto.php" method="POST" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" required/>
              </div>
              <div class="col-md-6 mb-3">
                <label>Categoría</label>
                <input type="text" name="categoria" class="form-control" required/>
              </div>
              <div class="col-md-6 mb-3">
                <label>Precio ($)</label>
                <input type="number" step="0.01" name="precio" class="form-control" required/>
              </div>
              <div class="col-md-6 mb-3">
                <label>Stock</label>
                <input type="number" name="stock" class="form-control" required/>
              </div>
              <div class="col-md-12 mb-3">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control" required></textarea>
              </div>
              <div class="col-md-12 mb-3">
                <label>Imagen</label>
                <input type="file" name="imagen" class="form-control" accept="image/*"/>
              </div>
            </div>
            <button type="submit" class="btn btn-success">Guardar</button>
          </form>
        </div>
      </div>

      <!-- Lista de Productos Externos desplegable -->
      <div class="card card-outline card-secondary mb-5">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title mb-0">
            <a href="#productosCollapse" data-bs-toggle="collapse" aria-expanded="true">Lista de productos</a>
          </h3>
          <div class="card-tools">
            <button class="btn btn-tool" data-bs-toggle="collapse" data-bs-target="#productosCollapse" aria-expanded="true">
              <i class="fas fa-chevron-up"></i>
            </button>
          </div>
        </div>
        <div id="productosCollapse" class="collapse show">
          <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
              <thead class="table-dark">
                <tr>
                  <th>ID</th><th>Nombre</th><th>Categoría</th><th>Precio</th><th>Stock</th><th>Imagen</th><th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $productos = mysqli_query($conn,"SELECT * FROM productos ORDER BY fecha_creacion DESC");
                  while($p = mysqli_fetch_assoc($productos)):
                ?>
                <tr>
                  <td><?= $p['id']?></td>
                  <td><?=htmlspecialchars($p['nombre'])?></td>
                  <td><?=htmlspecialchars($p['categoria'])?></td>
                  <td>$ <?=number_format($p['precio'],2)?></td>
                  <td><?=$p['stock']?></td>
                  <td>
                    <?php if($p['imagen']):?><img src="uploads/<?=$p['imagen']?>" width="60"/><?php else:?>Sin imagen<?php endif;?>
                  </td>
                  <td>
                    <a href="editar_producto.php?id=<?=$p['id']?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="eliminar_producto.php?id=<?=$p['id']?>" onclick="return confirm('¿Eliminar este producto?')" class="btn btn-danger btn-sm">Eliminar</a>
                  </td>
                </tr>
                <?php endwhile;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Encabezado Gestión de Productos Internos -->
      <div class="app-content-header mt-5">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">Gestión de Productos Internos</h3>
            </div>
          </div>
        </div>
      </div>

      <!-- Formulario de Productos Internos -->
      <div class="card card-primary mb-4">
        <div class="card-header"><h3 class="card-title">Agregar nuevo producto interno</h3></div>
        <div class="card-body">
          <form id="nuevo-interno-form" action="crear_producto_interno.php" method="POST">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" required/>
              </div>
              <div class="col-md-6 mb-3">
                <label>Cuenta contable</label>
                <select id="cuenta_id" name="cuenta_id" class="form-control" required>
                  <option></option>
                  <?php
                    $cuentas_list = [];
                    $res = mysqli_query($conn,"SELECT id,nombre FROM cuentas_contables ORDER BY nombre");
                    while($c = mysqli_fetch_assoc($res)){
                      $cuentas_list[] = $c;
                      echo '<option value="'.$c['id'].'">'.htmlspecialchars($c['nombre']).'</option>';
                    }
                  ?>
                </select>
              </div>
              <div class="col-md-4 mb-3">
                <label>Precio</label>
                <input type="number" step="0.01" name="precios" class="form-control" required/>
              </div>
              <div class="col-md-4 mb-3">
                <label>Cantidad</label>
                <input type="number" name="cantidad" class="form-control" required/>
              </div>
              <div class="col-md-12 mb-3">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control"></textarea>
              </div>
            </div>
            <button type="submit" class="btn btn-success">Guardar Interno</button>
          </form>
        </div>
      </div>

      <!-- Lista de Productos Internos con inline-edit y delete AJAX -->
      <div class="card card-outline card-secondary">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title mb-0">
            <a href="#internosCollapse" data-bs-toggle="collapse" aria-expanded="true">Lista de productos internos</a>
          </h3>
          <div class="card-tools">
            <button class="btn btn-tool" data-bs-toggle="collapse" data-bs-target="#internosCollapse" aria-expanded="true">
              <i class="fas fa-chevron-up"></i>
            </button>
          </div>
        </div>
        <div id="internosCollapse" class="collapse show">
          <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
              <thead class="table-dark">
                <tr>
                  <th>ID</th><th>Nombre</th><th>Cuenta</th><th>Precio</th><th>Cantidad</th><th>Descripción</th><th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $internos = mysqli_query($conn, "
                    SELECT g.id,g.nombre,g.cuenta_id,c.nombre AS cuenta,
                           g.precios,g.cantidad,g.descripcion
                    FROM gestion_productos_internos g
                    JOIN cuentas_contables c ON g.cuenta_id=c.id
                    ORDER BY g.id DESC
                  ");
                  while($i = mysqli_fetch_assoc($internos)):
                ?>
                <tr class="display-row" data-id="<?=$i['id']?>">
                  <td><?=$i['id']?></td>
                  <td><?=htmlspecialchars($i['nombre'])?></td>
                  <td><?=htmlspecialchars($i['cuenta'])?></td>
                  <td>$ <?=number_format($i['precios'],2)?></td>
                  <td><?=$i['cantidad']?></td>
                  <td><?=htmlspecialchars($i['descripcion'])?></td>
                  <td>
                    <button class="btn btn-warning btn-sm edit-interno-btn">Editar</button>
                    <button class="btn btn-danger btn-sm delete-interno-btn">Eliminar</button>
                  </td>
                </tr>
                <tr class="edit-row" data-id="<?=$i['id']?>" style="display:none;">
                  <td><?=$i['id']?></td>
                  <td><input type="text" class="form-control nombre-input" value="<?=htmlspecialchars($i['nombre'])?>"/></td>
                  <td>
                    <select class="form-control cuenta-select">
                      <?php foreach($cuentas_list as $c):?>
                        <option value="<?=$c['id']?>" <?=$c['id']==$i['cuenta_id']?'selected':''?>>
                          <?=htmlspecialchars($c['nombre'])?>
                        </option>
                      <?php endforeach;?>
                    </select>
                  </td>
                  <td><input type="number" step="0.01" class="form-control precios-input" value="<?=$i['precios']?>"/></td>
                  <td><input type="number" class="form-control cantidad-input" value="<?=$i['cantidad']?>"/></td>
                  <td><input type="text" class="form-control descripcion-input" value="<?=htmlspecialchars($i['descripcion'])?>"/></td>
                  <td>
                    <button class="btn btn-success btn-sm save-interno-btn">Guardar</button>
                    <button class="btn btn-secondary btn-sm cancel-interno-btn">Cancelar</button>
                  </td>
                </tr>
                <?php endwhile;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</main>




      <!-- Footer -->
      <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">Sistema Inventario</div>
        <strong>Copyright &copy; <?= date('Y') ?> <a href="#">TuEmpresa</a>.</strong> Todos los derechos reservados.
      </footer>
    </div>

<!-- Dependencias generales -->
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="../../../dist/js/adminlte.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_GET['mensaje'])): ?>
<script>
  let mensaje = "<?= $_GET['mensaje'] ?>";
  if (mensaje === "creado") {
    Swal.fire({ icon: 'success', title: 'Producto agregado', timer: 1500, showConfirmButton: false });
  } else if (mensaje === "actualizado") {
    Swal.fire({ icon: 'success', title: 'Producto actualizado', timer: 1500, showConfirmButton: false });
  } else if (mensaje === "eliminado") {
    Swal.fire({ icon: 'success', title: 'Producto eliminado', timer: 1500, showConfirmButton: false });
  } else if (mensaje === "error") {
    Swal.fire({ icon: 'error', title: 'Ocurrió un error', timer: 2000, showConfirmButton: false });
  }
</script>
<?php endif; ?>

<!-- jQuery y Select2 (necesarios para nuestro script) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Exponer en JS el listado de cuentas contables -->
<script>
  var cuentas_list = <?= json_encode($cuentas_list, JSON_HEX_TAG) ?>;
</script>

<!-- Nuestro código personalizado -->
<script>
$(function(){
  // Inicializar Select2
  $('#cuenta_id').select2({
    placeholder: 'Seleccione cuenta',
    allowClear: true,
    width: '100%'
  });

  // Inline edit
  $(document)
    .on('click', '.edit-interno-btn', function(){
      var id = $(this).closest('tr.display-row').data('id');
      $('tr.display-row[data-id="'+id+'"]').hide();
      var row = $('tr.edit-row[data-id="'+id+'"]').show();
      row.find('.cuenta-select').select2({ placeholder:'Seleccione cuenta', allowClear:true, width:'100%' });
    })
    .on('click', '.cancel-interno-btn', function(){
      var id = $(this).closest('tr.edit-row').data('id');
      $('tr.edit-row[data-id="'+id+'"]').hide();
      $('tr.display-row[data-id="'+id+'"]').show();
    })
    .on('click', '.save-interno-btn', function(){
      var editRow     = $(this).closest('tr.edit-row'),
          id          = editRow.data('id'),
          nombre      = editRow.find('.nombre-input').val(),
          cuenta_id   = editRow.find('.cuenta-select').val(),
          precios     = editRow.find('.precios-input').val(),
          cantidad    = editRow.find('.cantidad-input').val(),
          descripcion = editRow.find('.descripcion-input').val();

      $.post('editar_producto_interno.php',
        { id, nombre, cuenta_id, precios, cantidad, descripcion },
        function(r){
          if(!r.success){
            return Swal.fire({ icon: 'error', title: 'Error', text: r.message });
          }
          var d = r.data,
              disp = $('tr.display-row[data-id="'+d.id+'"]');
          disp.find('td:eq(1)').text(d.nombre);
          disp.find('td:eq(2)').text(d.cuenta);
          disp.find('td:eq(3)').text('$ '+parseFloat(d.precios).toFixed(2));
          disp.find('td:eq(4)').text(d.cantidad);
          disp.find('td:eq(5)').text(d.descripcion);
          editRow.hide();
          disp.show();
        },
        'json'
      ).fail(function(xhr){
        console.error(xhr.responseText);
        Swal.fire({ icon: 'error', title: 'Error de servidor' });
      });
    })
    .on('click', '.delete-interno-btn', function(){
      var row = $(this).closest('tr.display-row'),
          id  = row.data('id');
      if(!confirm('¿Eliminar este producto interno?')) return;
      $.post('eliminar_producto_interno.php',
        { id: id },
        function(res){
          if(res.success){
            $('tr.display-row[data-id="'+id+'"]').remove();
            $('tr.edit-row[data-id="'+id+'"]').remove();
          } else {
            Swal.fire({ icon: 'error', title: 'Error', text: res.message });
          }
        },
        'json'
      ).fail(function(xhr){
        console.error(xhr.responseText);
        Swal.fire({ icon: 'error', title: 'Error de servidor' });
      });
    });

  // Submit nuevo producto interno
  $('#nuevo-interno-form').on('submit', function(e){
    e.preventDefault();
    var form = $(this);
    $.ajax({
      url: form.attr('action'),
      method: 'POST',
      dataType: 'json',
      data: form.serialize()
    })
    .done(function(res){
      if(!res.success){
        return Swal.fire({ icon: 'error', title: 'Error', text: res.message });
      }
      // Alerta de éxito
      Swal.fire({ icon: 'success', title: '¡Producto agregado!', timer: 1500, showConfirmButton: false });

      var d = res.data;
      // Fila display
      var filaDisp = `
        <tr class="display-row" data-id="${d.id}">
          <td>${d.id}</td>
          <td>${d.nombre}</td>
          <td>${d.cuenta}</td>
          <td>$ ${parseFloat(d.precios).toFixed(2)}</td>
          <td>${d.cantidad}</td>
          <td>${d.descripcion}</td>
          <td>
            <button class="btn btn-warning btn-sm edit-interno-btn">Editar</button>
            <button class="btn btn-danger btn-sm delete-interno-btn">Eliminar</button>
          </td>
        </tr>`;
      // Fila edit inline
      var filaEdit = `
        <tr class="edit-row" data-id="${d.id}" style="display:none;">
          <td>${d.id}</td>
          <td><input type="text" class="form-control nombre-input" value="${d.nombre}"></td>
          <td>
            <select class="form-control cuenta-select">
              ${
                cuentas_list.map(c =>
                  `<option value="${c.id}" ${c.nombre===d.cuenta?'selected':''}>${c.nombre}</option>`
                ).join('')
              }
            </select>
          </td>
          <td><input type="number" step="0.01" class="form-control precios-input" value="${d.precios}"></td>
          <td><input type="number" class="form-control cantidad-input" value="${d.cantidad}"></td>
          <td><input type="text" class="form-control descripcion-input" value="${d.descripcion}"></td>
          <td>
            <button class="btn btn-success btn-sm save-interno-btn">Guardar</button>
            <button class="btn btn-secondary btn-sm cancel-interno-btn">Cancelar</button>
          </td>
        </tr>`;
      // Insertar y limpiar
      $('#internosCollapse table tbody')
        .prepend(filaEdit)
        .prepend(filaDisp);
      form[0].reset();
      $('#cuenta_id').val(null).trigger('change');
    })
    .fail(function(xhr){
      console.error(xhr.responseText);
      Swal.fire({ icon: 'error', title: 'Error de servidor' });
    });
  });
});
</script>

  </body>
</html>