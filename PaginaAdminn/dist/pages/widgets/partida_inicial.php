<?php
// partida_inicial.php
include 'conexion.php';

// Cargar catálogo de cuentas
$cuentas = mysqli_query($conn, "SELECT id, nombre, clasificacion FROM cuentas_contables ORDER BY nombre");
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Partida Inicial (Apertura de Mes)</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body { font-family:'Times New Roman', serif; font-size:12px; }
    .debe-input[readonly], .haber-input[readonly] { background:#d3d3d3!important; color:#666; }
    .partida-table { border:2px solid #000; background:#fff; margin-top:20px; }
    .partida-table th, .partida-table td { border:1px solid #000; padding:8px; vertical-align:middle; }
    .partida-table thead th { background:#f8f9fa; font-weight:bold; text-align:center; }
    .cuenta-nombre { text-transform:uppercase; font-weight:bold; }
    .final-totals { border-top:2px solid #000; background:#e9ecef; font-weight:bold; }
    .amounts-column { width: 140px; text-align:right; }
    .cuenta-column { width: 380px; }
    .descripcion-totales { font-style:italic; color:#666; font-weight:bold; }
    .watermark { position:absolute; top:10px; right:10px; height:60px; opacity:.9; }
  </style>
</head>
<body class="p-4 position-relative">

  <!-- Logo -->
  <img src="../../../dist/assets/img/rabi.png" alt="Logo" class="watermark">

  <h1 class="h4 mb-2">Partida Inicial del Mes</h1>
  <p class="text-muted mb-3">Esta partida se grabará como <b>Apertura</b> y aparecerá como la <b>primera partida</b> del mes seleccionado en el Libro Diario.</p>

  <div class="row g-3 mb-3">
    <div class="col-md-3">
      <label class="form-label">Mes de apertura</label>
      <input type="month" id="periodo" class="form-control" value="<?= date('Y-m') ?>">
    </div>
    <div class="col-md-9">
      <label class="form-label">Descripción</label>
      <input type="text" id="descripcion" class="form-control" placeholder="Ej: Partida inicial de agosto/2025" value="Partida inicial de <?= strftime('%B/%Y') ?>">
    </div>
  </div>

  <div class="row mb-3">
    <?php for ($i=0; $i<2; $i++):
      $tipo = $i===0 ? 'debe' : 'haber';
      $sel  = $i===0 ? 'selectDebe' : 'selectHaber';
      $ttl  = strtoupper($tipo);
    ?>
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-light"><strong>Elegir cuentas para <?= $ttl ?></strong></div>
        <div class="card-body">
          <select id="<?= $sel ?>" class="form-select" multiple>
            <?php mysqli_data_seek($cuentas, 0); while ($c = mysqli_fetch_assoc($cuentas)): ?>
              <option
                value="<?= (int)$c['id'] ?>"
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

  <table id="tabla-partida" class="table partida-table">
    <thead>
      <tr>
        <th class="cuenta-column">Cuenta</th>
        <th class="amounts-column">Debe</th>
        <th class="amounts-column">Haber</th>
      </tr>
    </thead>
    <tbody id="tbody-cuentas"></tbody>
    <tfoot>
      <tr class="final-totals">
        <td class="descripcion-totales"><span id="descripcion-final">Descripción de la partida:</span></td>
        <td class="text-end"><strong><span id="total-debe">0.00</span></strong></td>
        <td class="text-end"><strong><span id="total-haber">0.00</span></strong></td>
      </tr>
    </tfoot>
  </table>

  <div class="text-end">
    <button id="btnGuardar" class="btn btn-primary">💾 Guardar Partida de Apertura</button>
    <span id="exportBtnContainer"></span>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
  $(function(){
    $('#selectDebe,#selectHaber').select2({ theme:'bootstrap-5', placeholder:'Escribe para buscar...', width:'100%' });

    function seccion(cl){ cl=(cl||'').toLowerCase();
      if (cl.includes('pasivo')) return 2;
      if (cl.includes('patrimonio') || cl.includes('capital')) return 3;
      return 1; // activo primero
    }

    function reordenar(){
      let filas=[];
      $('#tbody-cuentas tr[data-id]').each(function(){
        filas.push({ord: parseInt($(this).data('ord'),10)||99, el: $(this).clone(true)});
        $(this).remove();
      });
      filas.sort((a,b)=>a.ord-b.ord);
      filas.forEach(f=>$('#tbody-cuentas').append(f.el));
    }

    function manejarCambio(sel, tipo){
      $('#'+sel).on('change', function(){
        const vals = $(this).val()||[];
        $('#'+sel+' option').each(function(){
          const id = this.value, existe = vals.includes(id);
          const fila = $(`tr[data-id="${id}"][data-tipo="${tipo}"]`);
          if (existe && !fila.length){
            const nombre = $(this).data('nombre'), clasif=$(this).data('clasificacion');
            const ord = seccion(clasif);
            const row = $(`
              <tr data-id="${id}" data-tipo="${tipo}" data-ord="${ord}">
                <td class="cuenta-nombre">${nombre}</td>
                <td class="text-end">
                  <input type="number" step="0.01" class="form-control debe-input text-end" ${tipo==='haber'?'readonly':''} style="border:none;background:transparent;">
                </td>
                <td class="text-end">
                  <input type="number" step="0.01" class="form-control haber-input text-end" ${tipo==='debe'?'readonly':''} style="border:none;background:transparent;">
                </td>
              </tr>
            `);
            $('#tbody-cuentas').append(row); reordenar();
          }
          if (!existe && fila.length){ fila.remove(); }
        });
        recalcular();
      });
    }
    manejarCambio('selectDebe','debe'); manejarCambio('selectHaber','haber');

    function recalcular(){
      let d=0,h=0;
      $('.debe-input').each(function(){ d += parseFloat(this.value)||0; });
      $('.haber-input').each(function(){ h += parseFloat(this.value)||0; });
      $('#total-debe').text(d.toFixed(2)); $('#total-haber').text(h.toFixed(2));
    }
    $(document).on('input change keyup','.debe-input,.haber-input',recalcular);

    $('#descripcion').on('input', function(){
      const t = $(this).val().trim();
      $('#descripcion-final').text( t ? t+':' : 'Descripción de la partida:' );
    });

    $('#btnGuardar').on('click', async function(){
      const periodo = $('#periodo').val(); // YYYY-MM
      if(!/^\d{4}-\d{2}$/.test(periodo)){
        return Swal.fire({icon:'error',title:'Periodo inválido',text:'Selecciona un mes (YYYY-MM).'});
      }
      const totalDebe = parseFloat($('#total-debe').text())||0;
      const totalHaber = parseFloat($('#total-haber').text())||0;
      if (Math.abs(totalDebe - totalHaber) > 0.001){
        return Swal.fire({icon:'error',title:'Descuadre',text:'Debe debe ser igual a Haber.'});
      }
      let detalles=[];
      $('tr[data-id]').each(function(){
        const id = parseInt($(this).data('id'),10);
        const debe  = parseFloat($(this).find('.debe-input').val()) || 0;
        const haber = parseFloat($(this).find('.haber-input').val()) || 0;
        if (debe>0 || haber>0) detalles.push({cuenta_id:id, debe:debe, haber:haber});
      });
      if (detalles.length===0){
        return Swal.fire({icon:'error',title:'Sin líneas',text:'Agrega al menos una cuenta con monto.'});
      }

      try{
        const resp = await fetch('guardar_partida_inicial.php', {
          method:'POST',
          headers:{'Content-Type':'application/json'},
          body: JSON.stringify({
            periodo: periodo,          // YYYY-MM
            descripcion: $('#descripcion').val(),
            detalles: detalles
          })
        });
        const data = await resp.json();
        if(!data.success) throw new Error(data.message||'Error desconocido');

        Swal.fire({icon:'success',title:'Apertura creada',timer:1400,showConfirmButton:false})
          .then(()=> window.close());
      }catch(e){
        Swal.fire({icon:'error',title:'No se pudo guardar',text:e.message});
      }
    });
  });
  </script>
</body>
</html>
