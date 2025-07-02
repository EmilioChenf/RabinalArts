<?php
session_start();
include 'php/config.php';
include 'php/verificar_datos.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'cliente') {
    header("Location: index.php");
    exit();
}

$productosEnCarrito = json_decode($_COOKIE['carrito'] ?? '[]', true);
$total = 0;
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>RabinalArts</title>
  <link href="assets/css/style.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<?php if (isset($_GET['compra']) && $_GET['compra'] === 'ok'): ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    Swal.fire(
      "¡Gracias por tu compra!",
      "Tu pedido fue registrado exitosamente.",
      "success"
    );
    localStorage.removeItem('productos');
    document.cookie = "carrito=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
  </script>
<?php endif; ?>

<header>
  <div class="menu logo-nav">
    <a href="index.php" class="logo">RABINALARTS</a>
    <label class="menu-icon"><span class="fas fa-bars icomin"></span></label>
    <nav class="navigation">
      <ul>
        <li><a href="nosotros.php">Nosotros</a></li>
        <li><a href="productos.php">Productos</a></li>
        <li><a href="contacto.php">Contacto</a></li>
        <li class="car">
          <a href="carrito.php">
            <svg class="bi bi-cart3" width="2em" height="2em" viewBox="0 0 16 16" fill="currentColor">
              <path fill-rule="evenodd" d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm7 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
            </svg>
          </a>
        </li>
        <li class="user-info">
          <?php if (isset($_SESSION['user_name'])): ?>
            <span class="user-name"><?= htmlspecialchars($_SESSION['user_name']); ?></span>
            <a class="logout" href="../LoginRabinarlArts/Animated Login/php/logout.php">Cerrar sesión</a>
          <?php else: ?>
            <a href="../LoginRabinarlArts/Animated%20Login/">Iniciar sesión</a>
          <?php endif; ?>
        </li>
      </ul>
    </nav>
  </div>
</header>

<main>
  <div class="container-carrito">
    <h2>Realizar Compra</h2>

    <h3>Completa o actualiza tu información:</h3>
    <form id="guardar-datos">
      <div class="contenido titulo">
        <label for="numero">Número:</label>
        <input type="tel" id="numero" name="numero" class="form-control input-estilizado"
               placeholder="Ingrese su número" value="<?= htmlspecialchars($telefono); ?>" required>
      </div>
      <div class="contenido titulo">
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" class="form-control input-estilizado"
               placeholder="Ingrese su dirección" value="<?= htmlspecialchars($direccion); ?>" required>
      </div>
      <div class="botones-envio">
        <button type="submit" class="button">Guardar Datos</button>
      </div>
    </form>

    <p id="mensaje-respuesta" style="text-align:center; font-weight: bold;"></p>

    <div id="carrito" class="contenido">
      <table class="tabla" id="lista-compra">
        <thead>
          <tr>
            <th>Imagen</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Sub Total</th>
            <th>Eliminar</th>
          </tr>
        </thead>
        <tbody id="tabla-carrito"></tbody>
        <tfoot>
          <tr>
            <th colspan="4">TOTAL :</th>
            <th colspan="2"><input type="text" id="total" readonly></th>
          </tr>
        </tfoot>
      </table>
    </div>

    <div class="botones-envio">
      <a href="productos.php" class="button">Seguir comprando</a>
      <!-- Botón de compra manual -->
      <form action="php/procesar_compra.php" method="POST" style="display:inline-block;">
        <button type="submit" class="button">Realizar compra</button>
      </form>
    </div>

    <!-- PayPal Button Container -->
    <div id="paypal-button-container" style="margin-top:1.5rem;"></div>
  </div>
</main>

<footer class="footer-section">
  <div class="copyright-area">
    <!-- footer intacto -->
  </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // Guardar datos del usuario vía AJAX
  document.getElementById("guardar-datos").addEventListener("submit", function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    fetch("php/guardar_datos.php", { method: "POST", body: fd })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Swal.fire({ icon: "success", title: "¡Datos guardados!", timer: 2000, showConfirmButton: false });
          document.getElementById("numero").value = fd.get("numero");
          document.getElementById("direccion").value = fd.get("direccion");
        } else {
          Swal.fire("Error", data.error || "Hubo un problema al guardar los datos.", "error");
        }
      })
      .catch(() => Swal.fire("Error", "No se pudo conectar con el servidor.", "error"));
  });

  // Renderizar carrito
  function renderizarCarrito() {
    const tabla = document.getElementById('tabla-carrito');
    const productos = JSON.parse(localStorage.getItem('productos') || '[]');
    tabla.innerHTML = '';
    let total = 0;
    productos.forEach((p, i) => {
      const sub = p.precio * p.cantidad;
      total += sub;
      tabla.innerHTML += `
        <tr data-index="${i}">
          <td><img src="${p.imagen}" width="80"></td>
          <td>${p.titulo}</td>
          <td>$${p.precio.toFixed(2)}</td>
          <td>
            <button class="btn-decrementar">-</button>
            <input type="number" class="cantidad-carrito" value="${p.cantidad}" min="1">
            <button class="btn-incrementar">+</button>
          </td>
          <td>$${sub.toFixed(2)}</td>
          <td><button class="btn-eliminar">Eliminar</button></td>
        </tr>`;
    });
    document.getElementById('total').value = `$${total.toFixed(2)}`;
    document.cookie = "carrito=" + encodeURIComponent(JSON.stringify(productos)) + "; path=/;";
  }

  document.addEventListener('DOMContentLoaded', () => {
    renderizarCarrito();
    document.getElementById('tabla-carrito').addEventListener('click', e => {
      const row = e.target.closest('tr'), idx = row.dataset.index;
      const productos = JSON.parse(localStorage.getItem('productos') || '[]');
      if (e.target.classList.contains('btn-incrementar')) productos[idx].cantidad++;
      if (e.target.classList.contains('btn-decrementar') && productos[idx].cantidad > 1) productos[idx].cantidad--;
      if (e.target.classList.contains('btn-eliminar')) productos.splice(idx, 1);
      localStorage.setItem('productos', JSON.stringify(productos));
      renderizarCarrito();
    });
  });
</script>
<!-- PayPal SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=<?= PAYPAL_CLIENT_ID ?>&currency=USD"></script>
<script>
paypal.Buttons({
  onApprove: (data) => {
    return fetch('php/paypal_capture_order.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ orderID: data.orderID })
    })
    .then(res => res.json())
    .then(json => {
      if (json.success) {
        Swal.fire('¡Listo!','Pago procesado correctamente.','success')
          .then(() => window.location.href='carrito.php?compra=ok');
      } else {
        Swal.fire('Error', json.error || 'Error desconocido', 'error');
      }
    })
    .catch(err => {
      console.error('Fetch/parsing error:', err);
      Swal.fire('Error','Respuesta no válida del servidor. Revisa consola.','error');
    });
  },
  onError: err => {
    console.error('PayPal onError:', err);
    Swal.fire('Error','Ocurrió un error con PayPal.','error');
  }
}).render('#paypal-button-container');
</script>

</body>
</html>
