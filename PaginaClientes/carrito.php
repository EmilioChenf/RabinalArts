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
Swal.fire("¡Gracias por tu compra!", "Tu pedido fue registrado exitosamente.", "success");
</script>
<script>
// Limpieza de localStorage y cookie después de mostrar la alerta
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
                        <svg class="bi bi-cart3" width="2em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm7 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                        </svg>
                    </a>
                </li>
                <li class="user-info">
                    <?php if (isset($_SESSION['user_name'])): ?>
                        <span class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
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
        <input type="tel" class="form-control input-estilizado" id="numero" name="numero"
               placeholder="Ingrese su número" value="<?php echo htmlspecialchars($telefono); ?>" required>
    </div>

    <div class="contenido titulo">
        <label for="direccion">Dirección:</label>
        <input type="text" class="form-control input-estilizado" id="direccion" name="direccion"
               placeholder="Ingrese su dirección" value="<?php echo htmlspecialchars($direccion); ?>" required>
    </div>

    <div class="botones-envio">
        <button type="submit" class="button">Guardar Datos</button>
    </div>
</form>

<p id="mensaje-respuesta" style="text-align:center; font-weight: bold;"></p>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById("guardar-datos").addEventListener("submit", function(event) {
    event.preventDefault(); // Evita la recarga de la página

    var formData = new FormData(this);

    fetch("php/guardar_datos.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json()) // Convertir la respuesta a JSON
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: "success",
                title: "¡Datos guardados!",
                text: "Tu información se ha actualizado correctamente.",
                showConfirmButton: false,
                timer: 2000
            });

            // Actualizar valores en los inputs sin recargar la página
            document.getElementById("numero").value = formData.get("numero");
            document.getElementById("direccion").value = formData.get("direccion");
        } else {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: data.error || "Hubo un problema al guardar los datos.",
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con el servidor.",
        });
    });
});
</script>

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
      <tbody id="tabla-carrito">
        <!-- Se llena dinámicamente desde JS -->
      </tbody>
      <tfoot>
        <tr>
          <th colspan="4">TOTAL :</th>
          <th colspan="2"><input type="text" id="total" readonly></th>
        </tr>
      </tfoot>
    </table>
  </div>

  <form action="php/procesar_compra.php" method="POST">
    <div class="botones-envio">
      <a href="productos.php" class="button">Seguir comprando</a>
      <button type="submit" class="button">Realizar compra</button>
    </div>
  </form>
</div>
</main>

<footer class="footer-section">
  <div class="copyright-area">
      <div class="container-footer">
          <div class="row-footer">
              <div class="col-xl-6 col-lg-6 text-center text-lg-left">
                  <div class="copyright-text">
                      <p>&copy; 2025 RABINALARTS. Todos los derechos reservados.</p>
                  </div>
              </div>
              <div class="col-xl-6 col-lg-6 d-none d-lg-block text-right">
                  <div class="footer-menu">
                      <ul>
                          <li><a href="nosotros.php">Nosotros</a></li>
                          <li><a href="productos.php">Productos</a></li>
                          <li><a href="contacto.php">Contacto</a></li>
                      </ul>
                  </div>
              </div>
          </div>
      </div>
  </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function renderizarCarrito() {
  const tabla = document.getElementById('tabla-carrito');
  const productos = JSON.parse(localStorage.getItem('productos')) || [];
  tabla.innerHTML = '';
  let total = 0;

  productos.forEach((producto, index) => {
    const subtotal = producto.precio * producto.cantidad;
    total += subtotal;

    tabla.innerHTML += `
      <tr data-index="${index}">
        <td><img src="${producto.imagen}" width="80"></td>
        <td>${producto.titulo}</td>
        <td>$${producto.precio.toFixed(2)}</td>
        <td>
          <button class="btn-decrementar">-</button>
          <input type="number" class="cantidad-carrito" value="${producto.cantidad}" min="1">
          <button class="btn-incrementar">+</button>
        </td>
        <td>$${subtotal.toFixed(2)}</td>
        <td><button class="btn-eliminar">Eliminar</button></td>
      </tr>
    `;
  });

  document.getElementById('total').value = `$${total.toFixed(2)}`;
  document.cookie = "carrito=" + encodeURIComponent(JSON.stringify(productos)) + "; path=/;";
}

document.addEventListener('DOMContentLoaded', () => {
  renderizarCarrito();

  document.getElementById('tabla-carrito').addEventListener('click', e => {
    const row = e.target.closest('tr');
    const index = row.dataset.index;
    let productos = JSON.parse(localStorage.getItem('productos')) || [];

    if (e.target.classList.contains('btn-incrementar')) {
      productos[index].cantidad++;
    } else if (e.target.classList.contains('btn-decrementar') && productos[index].cantidad > 1) {
      productos[index].cantidad--;
    } else if (e.target.classList.contains('btn-eliminar')) {
      productos.splice(index, 1);
    }

    localStorage.setItem('productos', JSON.stringify(productos));
    renderizarCarrito();
  });
});
</script>
</body>
</html>