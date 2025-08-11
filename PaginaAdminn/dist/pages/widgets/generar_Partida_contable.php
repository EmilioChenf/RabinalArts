
<?php
// generar_Partida_contable.php
include 'conexion.php';

if (!isset($_GET['cliente_id'])) {
  die('Falta parámetro cliente_id');
}
$cliente_id = (int)$_GET['cliente_id'];

$cuentas = mysqli_query($conn,"
  SELECT id, nombre, clasificacion
    FROM cuentas_contables
   ORDER BY nombre
");
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Generar Partida Contable</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      font-family: 'Times New Roman', serif;
      font-size: 12px;
    }
    
    .debe-input[readonly], .haber-input[readonly] {
      background-color: #d3d3d3 !important;
      opacity: 1;
      color: #666;
    }
    
    .partida-table {
      border: 2px solid #000;
      background: white;
      margin-top: 20px;
    }
    
    .partida-table th, .partida-table td {
      border: 1px solid #000;
      padding: 8px;
      vertical-align: middle;
    }
    
    .partida-table thead th {
      background-color: #f8f9fa;
      font-weight: bold;
      text-align: center;
    }
    
    .cuenta-nombre {
      text-transform: uppercase;
      font-weight: bold;
    }
    
    .final-totals {
      border-top: 2px solid #000;
      background-color: #e9ecef;
      font-weight: bold;
    }
    
    .amounts-column {
      width: 120px;
      text-align: right;
    }
    
    .cuenta-column {
      width: 300px;
    }
    
    .descripcion-totales {
      font-style: italic;
      color: #666;
      font-weight: bold;
    }
  </style>
</head>
<body class="p-4">

    <!-- Logo en la esquina superior -->
    <div style="position: relative;">
      <img src="../../../dist/assets/img/rabi.png" 
           alt="Logo Rabinalarts" 
           style="position: absolute; top: 0; right: 0; height: 60px;">
    </div>

  <h1 class="mb-3">Partida Contable (Cliente #<?= $cliente_id ?>)</h1>
  <input type="hidden" id="cliente_id" value="<?= $cliente_id ?>">

  <div class="row mb-3">
    <?php for ($i = 0; $i < 2; $i++):
      $tipo = $i === 0 ? 'debe' : 'haber';
      $label = strtoupper($tipo);
      $sel   = $i === 0 ? 'selectDebe' : 'selectHaber';
    ?>
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-light">
          <strong>Elegir cuentas para <span class="<?= $tipo ?>"><?= $label ?></span></strong>
        </div>
        <div class="card-body">
          <select id="<?= $sel ?>" class="form-select" multiple>
            <?php
            mysqli_data_seek($cuentas, 0);
            while ($c = mysqli_fetch_assoc($cuentas)): ?>
            <option
              value="<?= $c['id'] ?>"
              data-nombre="<?= htmlspecialchars($c['nombre'], ENT_QUOTES) ?>"
              data-clasificacion="<?= htmlspecialchars($c['clasificacion'], ENT_QUOTES) ?>"
            ><?= htmlspecialchars($c['nombre']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
      </div>
    </div>
    <?php endfor; ?>
  </div>

  <div class="mb-4">
    <label for="descripcion" class="form-label"><strong>Descripción</strong></label>
    <textarea id="descripcion" class="form-control" rows="2" placeholder="Escribe una descripción..."></textarea>
  </div>

  <!-- Tabla única continua -->
  <div class="mb-4">
    <table id="tabla-partida" class="table partida-table">
      <thead>
        <tr>
          <th class="cuenta-column">Cuenta</th>
          <th class="amounts-column">Debe</th>
          <th class="amounts-column">Haber</th>
        </tr>
      </thead>
      <tbody id="tbody-cuentas">
        <!-- Las cuentas se insertan aquí dinámicamente en orden: Activo, Pasivo, Patrimonio -->
      </tbody>
      <tfoot>
        <tr class="final-totals">
          <td class="descripcion-totales"><span id="descripcion-final">Descripción de la partida:</span></td>
          <td class="text-end"><strong><span id="total-debe">0.00</span></strong></td>
          <td class="text-end"><strong><span id="total-haber">0.00</span></strong></td>
        </tr>
      </tfoot>
    </table>
  </div>

  <div class="text-end mb-5">
    <button id="btnGuardar" class="btn btn-primary">💾 Guardar Partida</button>
    <span id="exportBtnContainer"></span>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script>
  $(function(){
    $('#selectDebe, #selectHaber').select2({
      theme: 'bootstrap-5',
      placeholder: 'Escribe para buscar...',
      width: '100%'
    });

    function seccionPara(clasif) {
      clasif = clasif.toLowerCase();
      if (clasif.includes('pasivo')) return 'pasivo';
      if (clasif.includes('patrimonio')||clasif.includes('capital')) return 'patrimonio';
      return 'activo';
    }

    function ordenSeccion(seccion) {
      // Define el orden: activo = 1, pasivo = 2, patrimonio = 3
      if (seccion === 'activo') return 1;
      if (seccion === 'pasivo') return 2;
      if (seccion === 'patrimonio') return 3;
      return 4;
    }

    function reordenarTabla() {
      // Obtener todas las filas y ordenarlas por sección
      let filas = [];
      $('#tbody-cuentas tr[data-id]').each(function() {
        let seccion = $(this).data('seccion');
        let orden = ordenSeccion(seccion);
        filas.push({
          orden: orden,
          elemento: $(this).clone(true)
        });
        $(this).remove();
      });

      // Ordenar por sección
      filas.sort((a, b) => a.orden - b.orden);

      // Volver a insertar en orden
      filas.forEach(fila => {
        $('#tbody-cuentas').append(fila.elemento);
      });
    }

    function manejarCambio(sel, tipo) {
      $('#' + sel).on('change', function(){
        let vals = $(this).val()||[];
        $('#' + sel + ' option').each(function(){
          let id = this.value,
              existe = vals.includes(id),
              fila   = $(`tr[data-id="${id}"][data-tipo="${tipo}"]`);
          
          if (existe && !fila.length) {
            let nombre = $(this).data('nombre'),
                clasif = $(this).data('clasificacion'),
                sec    = seccionPara(clasif),
                row    = $(`
                  <tr data-id="${id}" data-tipo="${tipo}" data-seccion="${sec}">
                    <td class="cuenta-nombre">${nombre}</td>
                    <td class="text-end">
                      <input type="number" step="0.01" class="form-control debe-input text-end" 
                             ${tipo==='haber'?'readonly':''} style="border:none; background:transparent;">
                    </td>
                    <td class="text-end">
                      <input type="number" step="0.01" class="form-control haber-input text-end" 
                             ${tipo==='debe'?'readonly':''} style="border:none; background:transparent;">
                    </td>
                  </tr>
                `);
            $('#tbody-cuentas').append(row);
            reordenarTabla();
          }
          if (!existe && fila.length) {
            fila.remove();
          }
        });
        recalcular();
      });
    }
    
    manejarCambio('selectDebe','debe');
    manejarCambio('selectHaber','haber');

    function recalcular(){
      let totalDebe = 0, totalHaber = 0;
      
      $('.debe-input').each(function(){
        totalDebe += parseFloat($(this).val()) || 0;
      });
      
      $('.haber-input').each(function(){
        totalHaber += parseFloat($(this).val()) || 0;
      });
      
      $('#total-debe').text(totalDebe.toFixed(2));
      $('#total-haber').text(totalHaber.toFixed(2));
    }

    // Actualizar descripción en tiempo real
    $('#descripcion').on('input', function() {
      let desc = $(this).val();
      if (desc.trim() === '') {
        $('#descripcion-final').text('Descripción de la partida:');
      } else {
        $('#descripcion-final').text(desc + ':');
      }
    });

    $(document).on('input change keyup', '.debe-input, .haber-input', recalcular);
    recalcular();

    $('#btnGuardar').on('click', function(){
      let totalDebe = parseFloat($('#total-debe').text());
      let totalHaber = parseFloat($('#total-haber').text());
      
      if (totalDebe !== totalHaber) {
        return Swal.fire({icon:'error',title:'Error',text:'Debe debe igualar a Haber.'});
      }
      
      let detalles = [];
      $('tr[data-id]').each(function(){
        let debe = parseFloat($(this).find('.debe-input').val()) || 0;
        let haber = parseFloat($(this).find('.haber-input').val()) || 0;
        
        if (debe > 0 || haber > 0) {
          detalles.push({
            cuenta_id: $(this).data('id'),
            debe: debe,
            haber: haber
          });
        }
      });
      
      if (detalles.length === 0) {
        return Swal.fire({icon:'error',title:'Error',text:'Debe agregar al menos una cuenta con monto.'});
      }
      
      $.ajax({
        url: 'guardar_partida.php',
        method: 'POST',
        dataType:'json',
        contentType:'application/json',
        data: JSON.stringify({
          cliente_id: $('#cliente_id').val(),
          descripcion: $('#descripcion').val(),
          detalles:    detalles
        })
      }).done(res=>{
        if (!res.success) {
          return Swal.fire({icon:'error',title:'Error',text:res.message});
        }
        Swal.fire({icon:'success',title:'Guardado',timer:1200,showConfirmButton:false})
          .then(()=>{
            $('#exportBtnContainer').html(
              `<a href="exportar_partida_pdf.php?partida_id=${res.partida_id}&cliente_id=${$('#cliente_id').val()}"
                 target="_blank" class="btn btn-danger ms-2">📄 Exportar PDF</a>`
            );
          });
      });
    });
  });
  </script>
</body>
</html>