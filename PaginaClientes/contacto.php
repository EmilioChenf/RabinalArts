<?php
session_start();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RabinalArts</title>
    <link href="assets/css/style.css" rel="stylesheet" type="text/css">
  </head>
  <body>
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
        <a href="../Animated Login/login.php">Iniciar sesión</a>
      <?php endif; ?>
    </li>
  </ul>
</nav>



      </div>
    </header>
  
  <div class="container-contacto">
  <form action="#" id="form" method="POST">
    <h2>CONTACTO</h2>
    <br>
    <input type="text" name="cliente" id="nombre" placeholder="Ingrese su Nombre" onkeypress="return sololetras(event)" onpaste="return false" required>
    <input type="text" name="correo" id="correo" placeholder="Ingrese su Correo" required>
    <input type="text" name="celular" id="celular" placeholder="Ingrese su Celular" onkeypress="return solonumeros(event)" onpaste="return false" required>
    <textarea name="mensaje" placeholder="Escriba su Mensaje" required></textarea>
    <input type="submit" id="button-contacto" value="ENVIAR" class="button" onclick="validarCorreo(form.correo.value)">
  </form>
  </div>

    <footer class="footer-section">
      <div class="copyright-area">
          <div class="container-footer">
              <div class="row-footer">
                  <div class="col-xl-6 col-lg-6 text-center text-lg-left">
                      <div class="copyright-text">
                          <p>Copyright &copy; 2025, todos los derechos reservados <a href="index.php">RABINALARTS</a></p>
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
    <script src="https://cdn.jsdelivr.net/npm/emailjs-com@2/dist/email.min.js"></script>
    <script  src="assets/js/scripts.js"></script>
    <script  src="assets/js/contacto.js"></script>
    <script src="assets/js/config.js"></script> <!-- Incluye el archivo de configuración -->
</body>
</html>
