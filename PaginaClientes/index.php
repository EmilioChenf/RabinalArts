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
    <style>
:root{
  --color-primario:#5B3A1A; --color-secundario:#875728; --color-acento:#B07940; --color-fondo:#E0C8A8;
}
header{ background: var(--color-primario) !important; }
header a{ color: var(--color-fondo) !important; }
.button{ background: var(--color-primario) !important; }
.button:hover{ background: var(--color-acento) !important; }
.copyright-area{ background: var(--color-fondo) !important; }
.container-productos .card{ background: var(--color-secundario) !important; border-color: var(--color-primario) !important; }
.circulo{ border-color: var(--color-primario) !important; }
</style>

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
        <a href="../LoginRabinarlArts/Animated%20Login/">Iniciar sesión</a>
      <?php endif; ?>
    </li>
  </ul>
</nav>



      </div>
    </header>

<div class="slider">
  <ul>
    <li><img src="assets\img\olaprimera.jpg" alt="..."></li>
    <li><img src="assets\img\sliderrabi.jpg" alt="..."></li>
    <li><img src="assets\img\artesa.jpg" alt="..."></li>
  </ul>
</div>


  <div class="container-index">

    <div class="container-circulos">
      <div class="container-imagen">
        <img src="assets/img/4.png" class="circulo">
        <h2>Ropa y Accesorios</h2>
      </div>
      <div class="container-imagen">
        <img src="assets/img/10.png" class="circulo">
        <h2>Textiles</h2>
      </div>
      <div class="container-imagen">
        <img src="assets/img/32.png" class="circulo">
        <h2>Artesanias</h2>
      </div>
      <div class="container-imagen">
        <img src="assets/img/19.png" class="circulo">
        <h2>Joyería y Accesorios Personales</h2>
      </div>
      <div class="container-imagen">
        <img src="assets/img/7.png" class="circulo">
        <h2>Decoración y Souvenirs </h2>
      </div>
      <div class="container-imagen">
        <img src="assets/img/16.png" class="circulo">
        <h2>Utensilios y Herramientas</h2>
      </div>


    </div>

    <div class="ver-todo"><a class="button" href="productos.php">Ver todos</a></div>
    
    <div class="contenedor-columna">
      <div class="columna1">
        <h2>SOBRE RABINALARTS </h2>
        <p>Rabinal Arts es una empresa dedicada a la exportación de productos artesanales 100% 
          guatemaltecos, promoviendo la riqueza cultural del país en el extranjero. Con más de 24 
          años de experiencia, trabajando con artesanos del altiplano occidental de Guatemala.</p>
      </div>
      <div class="columna2">
        <img src="assets/img/Logo-Rabinal-Arts-con-sombra.png">
      </div>
    </div>

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
    <script  src="assets/js/scripts.js"></script>
</body>
</html>

