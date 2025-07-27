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
    .debe-input[readonly], .haber-input[readonly] {
      background-color: #e9ecef;
      opacity: 1;
    }
    .section-table th, .section-table td { vertical-align: middle; }
    .total-row th { border-top: 2px solid #000; }
  </style>
</head>
<body class="p-4">
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

  <?php
  $sections = ['Activo','Pasivo','Patrimonio Neto'];
  foreach ($sections as $sec):
    $key = strtolower(str_replace(' ', '-', $sec));
  ?>
  <div class="mb-4">
    <h4><?= $sec ?></h4>
    <table id="table-<?= $key ?>" class="table table-bordered section-table">
      <thead class="table-light">
        <tr>
          <th>Cuenta</th>
          <th style="width:25%">Debe</th>
          <th style="width:25%">Haber</th>
        </tr>
      </thead>
      <tbody></tbody>
      <tfoot>
        <tr class="total-row">
          <th>Total <?= $sec ?>:</th>
          <th class="text-end"><span class="total-debe">0.00</span></th>
          <th class="text-end"><span class="total-haber">0.00</span></th>
        </tr>
      </tfoot>
    </table>
  </div>
  <?php endforeach; ?>

  <div class="row mb-4">
    <div class="col-6"></div>
    <div class="col-3 text-end"><strong>Total Debe:</strong> <span id="global-debe">0.00</span></div>
    <div class="col-3 text-end"><strong>Total Haber:</strong> <span id="global-haber">0.00</span></div>
  </div>

  <div class="mb-4">
    <h4>Resumen Global</h4>
    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>Descripción</th>
          <th>Total Debe</th>
          <th>Total Haber</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td id="preview-descripcion"></td>
          <td class="text-end" id="preview-debe">0.00</td>
          <td class="text-end" id="preview-haber">0.00</td>
        </tr>
      </tbody>
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
      if (clasif.includes('patrimonio')||clasif.includes('capital')) return 'patrimonio-neto';
      return 'activo';
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
                sec    = seccionPara($(this).data('clasificacion')),
                row    = $(`
                  <tr data-id="${id}" data-tipo="${tipo}">
                    <td>${nombre}</td>
                    <td><input type="number" step="0.01" class="form-control debe-input" ${tipo==='haber'?'readonly':''}></td>
                    <td><input type="number" step="0.01" class="form-control haber-input" ${tipo==='debe'?'readonly':''}></td>
                  </tr>
                `);
            $(`#table-${sec} tbody`).append(row);
          }
          if (!existe && fila.length) fila.remove();
        });
        recalcular();
      });
    }
    manejarCambio('selectDebe','debe');
    manejarCambio('selectHaber','haber');

    function recalcular(){
      let gD=0, gH=0;
      ['activo','pasivo','patrimonio-neto'].forEach(sec=>{
        let tD=0,tH=0;
        $(`#table-${sec} .debe-input`).each((_,el)=>tD+=parseFloat(el.value)||0);
        $(`#table-${sec} .haber-input`).each((_,el)=>tH+=parseFloat(el.value)||0);
        $(`#table-${sec} .total-debe`).text(tD.toFixed(2));
        $(`#table-${sec} .total-haber`).text(tH.toFixed(2));
        gD+=tD; gH+=tH;
      });
      $('#global-debe').text(gD.toFixed(2));
      $('#global-haber').text(gH.toFixed(2));
      $('#preview-descripcion').text($('#descripcion').val());
      $('#preview-debe').text(gD.toFixed(2));
      $('#preview-haber').text(gH.toFixed(2));
    }

    $(document).on('input change keyup', '.debe-input, .haber-input', recalcular);
    $('#descripcion').on('input', ()=>$('#preview-descripcion').text($('#descripcion').val()));
    recalcular();

    $('#btnGuardar').on('click', function(){
      if ($('#global-debe').text() !== $('#global-haber').text()) {
        return Swal.fire({icon:'error',title:'Error',text:'Debe debe igualar a Haber.'});
      }
      let detalles = [];
      $('tr[data-id]').each(function(){
        detalles.push({
          cuenta_id: $(this).data('id'),
          debe:      parseFloat($(this).find('.debe-input').val())||0,
          haber:     parseFloat($(this).find('.haber-input').val())||0
        });
      });
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
