<?php
session_start();
include 'php/config.php'; // Conexión a la base de datos
include 'php/verificar_datos.php'; // Verifica si el usuario tiene los datos completos

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'cliente') {
    header("Location: index.php");
    exit();
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link href="assets/css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <header>
        <div class="menu logo-nav">
            <a href="index.php" class="logo">RABINALARTS</a>
            <nav class="navigation">
                <ul>
                    <li><a href="nosotros.php">Nosotros</a></li>
                    <li><a href="productos.php">Productos</a></li>
                    <li><a href="contacto.php">Contacto</a></li>
                    <li class="car">
                        <a href="carrito.php">
                            <svg class="bi bi-cart3" width="2em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5z"/>
                            </svg>
                        </a>
                    </li>
                    <li class="user-info">
                        <span class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        <a class="logout" href="../LoginRabinarlArts/Animated Login/php/logout.php">Cerrar sesión</a>

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
                                      <th scope="col">Imagen</th>
                                      <th scope="col">Nombre</th>
                                      <th scope="col">Precio</th>
                                      <th scope="col">Cantidad</th>
                                      <th scope="col">Sub Total</th>
                                      <th scope="col">Eliminar</th>
                                  </tr>
                              </thead>
                              <tbody>

                              </tbody>
                              <tr>
    <th colspan="4" scope="col" class="">SUB TOTAL :</th>
    <th scope="col">
        <p id="subtotal"></p>
    </th>
</tr>
<tr>
    <th colspan="4" scope="col" class="">IGV :</th>
    <th scope="col">
        <p id="igv"></p>
    </th>
</tr>
<tr>
    <th colspan="4" scope="col" class="">TOTAL :</th>
    <th scope="col">
        <input type="text" id="total" name="monto_total" readonly>
    </th>
</tr>

                          </table>
                      </div>

                      <div class="" id="loaders">
                          <img id="cargando" src="assets/img/cargando.gif" width="220">
                      </div>

                      <div class="botones-envio">
                              <a href="productos.php" class="button" id="volver">Seguir comprando</a>
                              <input type="submit" class="button" id="procesar-compra" onclick="validarCorreo(form.correo.value)"value="Realizar compra">
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
                        <p>Copyright &copy; 2020, todos los derechos reservados <a href="index.php">RABINALARTS</a></p>
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



    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script  src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/emailjs-com@2/dist/email.min.js"></script>
    <script src="http://127.0.0.1/sadasd/RabinalArts/PaginaClientes/assets/js/carrito.js"></script>
    <script src="http://127.0.0.1/sadasd/RabinalArts/PaginaClientes/assets/js/scripts.js"></script>
    <script src="http://127.0.0.1/sadasd/RabinalArts/PaginaClientes/assets/js/pedido.js"></script>
    <script src="http://127.0.0.1/sadasd/RabinalArts/PaginaClientes/assets/js/compra.js"></script>
</body>
</html>

