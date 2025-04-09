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
        <a href="../LoginRabinarlArts/Animated%20Login/">Iniciar sesión</a>
      <?php endif; ?>
    </li>
  </ul>
</nav>



      </div>
    </header>


<main>

        <div class="modal" id="modal">
          <div class="modal-content">
            <img src="" alt="" class="modal-img" id="modal-img">
          </div>
          <div class="modal-boton" id="modal-boton">X</div>
        </div>

        <div class="container-productos" id="lista-productos">

          <div class="card">
            <img src="assets/img/1.png" class="card-img">
            <h5>Trompos de madera, diseños y colores surtidos</h5>
            <p>$<small class="precio">2.00</small></p>
            <div class="cantidad">
                <button class="btn-decrementar">-</button>
                <input type="number" class="cantidad-input" value="1" min="1">
                <button class="btn-incrementar">+</button>
            </div>
            <a href="#" class="button agregar-carrito" data-id="1">Comprar</a>
        </div>
        
        <div class="card">
            <img src="assets/img/2.png" class="card-img">
            <h5>Sombreros típicos</h5>
            <p>$<small class="precio">12.00</small></p>
            <div class="cantidad">
                <button class="btn-decrementar">-</button>
                <input type="number" class="cantidad-input" value="1" min="1">
                <button class="btn-incrementar">+</button>
            </div>
            <a href="#" class="button agregar-carrito" data-id="2">Comprar</a>
        </div>
    

          <div class="card">
            <img src="assets/img/3.png" class="card-img">
            <h5>Sombreros típicos</h5>
          
            <p>$<small class="precio">25</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="3">Comprar</a>
          
        </div>

          <div class="card">
            <img src="assets/img/4.png" class="card-img">
            <h5>Camisas Típicas, colores surtidos</h5>
            
            <p>$<small class="precio">15</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="4">Comprar</a>
          </div>





          <div class="card">
            <img src="assets/img/5.png" class="card-img">
            <h5>Camisolas de la selección de Guatemala, colores surtidos</h5>
            
            <p>$<small class="precio">20</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="5">Comprar</a>
          </div>






          <div class="card">
            <img src="assets/img/6.png" class="card-img">
            <h5>Gorras guatemalteca, diseños y colores surtidos</h5>
            
            <p>$<small class="precio">8</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="6">Comprar</a>
          </div>





          <div class="card">
            <img src="assets/img/7.png" class="card-img">
            <h5>Figuras del Quetzal de madera</h5>
            
            <p>$<small class="precio">10</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="7">Comprar</a>
          </div>





          <div class="card">
            <img src="assets/img/8.png" class="card-img">
            <h5>Figuras Mayas de madera</h5>
            
            <p>$<small class="precio">5</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="8">Comprar</a>
          </div>







          <div class="card">
            <img src="assets/img/9.png" class="card-img">
            <h5>LLaveros de Quetzal</h5>
            
            <p>$<small class="precio">3</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="9">Comprar</a>
          </div>




          <div class="card">
            <img src="assets/img/10.png" class="card-img">
            <h5>Manteles Tipicos</h5>
            
            <p>$<small class="precio">30</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="10">Comprar</a>
          </div>




          <div class="card">
            <img src="assets/img/11.png" class="card-img">
            <h5>Toallas tipicas, con diseños y colores surtidos</h5>
            
            <p>$<small class="precio">10</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="11">Comprar</a>
          </div>




          <div class="card">
            <img src="assets/img/12.png" class="card-img">
            <h5>Mochlas tipicas, dieños y colores surtidos</h5>
            
            <p>$<small class="precio">14</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="12">Comprar</a>
          </div>



          <div class="card">
            <img src="assets/img/13.png" class="card-img">
            <h5>Morrales típicos, deseños y colores surtidos</h5>
            
            <p>$<small class="precio">25</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="13">Comprar</a>
          </div>



          <div class="card">
            <img src="assets/img/14.png" class="card-img">
            <h5>Carteras Bordadas, diseños típicos</h5>
            
            <p>$<small class="precio">20</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="14">Comprar</a>
          </div>





          <div class="card">
            <img src="assets/img/15.png" class="card-img">
            <h5>Imanes de refrigeradora Típicos</h5>
          
            <p>$<small class="precio">3</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="15">Comprar</a>
          </div>





          <div class="card">
            <img src="assets/img/16.png" class="card-img">
            <h5>Destapabotellas, deseño de cerveza Gallo</h5>
          
            <p>$<small class="precio">4</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="16">Comprar</a>
          </div>





          <div class="card">
            <img src="assets/img/17.png" class="card-img">
            <h5>LLaveros de cuero</h5>
          
            <p>$<small class="precio">10</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="17">Comprar</a>
          </div>







          <div class="card">
            <img src="assets/img/18.png" class="card-img">
            <h5>LLaveros de metal</h5>
          
            <p>$<small class="precio">6</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="18">Comprar</a>
          </div>






          <div class="card">
            <img src="assets/img/19.png" class="card-img">
            <h5>Pulseras Bordadas tipicas</h5>
          
            <p>$<small class="precio">3</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="19">Comprar</a>
          </div>



          <div class="card">
            <img src="assets/img/20.png" class="card-img">
            <h5>Pulseras tipicas</h5>
            
            <p>$<small class="precio">2</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="20">Comprar</a>
          </div>



          <div class="card">
            <img src="assets/img/21.png" class="card-img">
            <h5>Floreros</h5>
            
            <p>$<small class="precio">18</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="21">Comprar</a>
          </div>


<div class="card">
  <img src="assets/img/22.png" class="card-img">
  <h5>Porta lapiceros</h5>
  
  <p>$<small class="precio">4</small></p>
  <div class="cantidad">
    <button class="btn-decrementar">-</button>
    <input type="number" class="cantidad-input" value="1" min="1">
    <button class="btn-incrementar">+</button>
</div>

<a href="#" class="button agregar-carrito" data-id="22">Comprar</a>
</div>


<div class="card">
  <img src="assets/img/23.png" class="card-img">
  <h5>Funda para asientos</h5>
  
  <p>$<small class="precio">35</small></p>
  <div class="cantidad">
    <button class="btn-decrementar">-</button>
    <input type="number" class="cantidad-input" value="1" min="1">
    <button class="btn-incrementar">+</button>
</div>

<a href="#" class="button agregar-carrito" data-id="23">Comprar</a>
</div>


<div class="card">
  <img src="assets/img/24.png" class="card-img">
  <h5>Funda para asientos de cuero</h5>
  
  <p>$<small class="precio">50</small></p>
  <div class="cantidad">
    <button class="btn-decrementar">-</button>
    <input type="number" class="cantidad-input" value="1" min="1">
    <button class="btn-incrementar">+</button>
</div>

<a href="#" class="button agregar-carrito" data-id="24">Comprar</a>
</div>


<div class="card">
  <img src="assets/img/25.png" class="card-img">
  <h5>Jarrones de plástico</h5>
  
  <p>$<small class="precio">10</small></p>
  <div class="cantidad">
    <button class="btn-decrementar">-</button>
    <input type="number" class="cantidad-input" value="1" min="1">
    <button class="btn-incrementar">+</button>
</div>

<a href="#" class="button agregar-carrito" data-id="25">Comprar</a>
</div>


<div class="card">
  <img src="assets/img/26.png" class="card-img">
  <h5>Botellas de plástico</h5>
  
  <p>$<small class="precio">2</small></p>
  <div class="cantidad">
    <button class="btn-decrementar">-</button>
    <input type="number" class="cantidad-input" value="1" min="1">
    <button class="btn-incrementar">+</button>
</div>

<a href="#" class="button agregar-carrito" data-id="26">Comprar</a>
</div>


<div class="card">
  <img src="assets/img/27.png" class="card-img">
  <h5>Pelotas de plástico</h5>
  
  <p>$<small class="precio">4</small></p>
  <div class="cantidad">
    <button class="btn-decrementar">-</button>
    <input type="number" class="cantidad-input" value="1" min="1">
    <button class="btn-incrementar">+</button>
</div>

<a href="#" class="button agregar-carrito" data-id="27">Comprar</a>
</div>


<div class="card">
  <img src="assets/img/28.png" class="card-img">
  <h5>Cesta de mimbre de colores</h5>
  
  <p>$<small class="precio">22</small></p>
  <div class="cantidad">
    <button class="btn-decrementar">-</button>
    <input type="number" class="cantidad-input" value="1" min="1">
    <button class="btn-incrementar">+</button>
</div>

<a href="#" class="button agregar-carrito" data-id="28">Comprar</a>
</div>


<div class="card">
  <img src="assets/img/29.png" class="card-img">
  <h5>Canasta de mimbre</h5>
  
  <p>$<small class="precio">18</small></p>
  <div class="cantidad">
    <button class="btn-decrementar">-</button>
    <input type="number" class="cantidad-input" value="1" min="1">
    <button class="btn-incrementar">+</button>
</div>

<a href="#" class="button agregar-carrito" data-id="29">Comprar</a>
</div>



<div class="card">
            <img src="assets/img/30.png" class="card-img">
            <h5>Canasta de mimbre</h5>
            
            <p>$<small class="precio">20</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="30">Comprar</a>
          </div>



<div class="card">
            <img src="assets/img/31.png" class="card-img">
            <h5>Cesta de mimbre</h5>
            
            <p>$<small class="precio">10</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="31">Comprar</a>
          </div>
        



<div class="card">
            <img src="assets/img/32.png" class="card-img">
            <h5>Batidor típico</h5>
            
            <p>$<small class="precio">15</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="32">Comprar</a>
          </div>
        



<div class="card">
            <img src="assets/img/33.png" class="card-img">
            <h5>Cuenco de ceramica</h5>
            
            <p>$<small class="precio">8</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="33">Comprar</a>
          </div>
        



<div class="card">
            <img src="assets/img/34.png" class="card-img">
            <h5>Tacones típicos</h5>
            
            <p>$<small class="precio">25</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="34">Comprar</a>
          </div>
        



<div class="card">
            <img src="assets/img/35.png" class="card-img">
            <h5>Tacones típicos</h5>
            
            <p>$<small class="precio">25</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="35">Comprar</a>
          </div>
        



<div class="card">
            <img src="assets/img/36.png" class="card-img">
            <h5>Tacones típicos</h5>
            
            <p>$<small class="precio">25</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="36">Comprar</a>
          </div>
        



<div class="card">
            <img src="assets/img/37.png" class="card-img">
            <h5>Sandalias típicas</h5>
            
            <p>$<small class="precio">15</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="37">Comprar</a>
          </div>
        



<div class="card">
            <img src="assets/img/38.png" class="card-img">
            <h5>Sandalias típicas</h5>
            
            <p>$<small class="precio">15</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="38">Comprar</a>
          </div>
        



<div class="card">
            <img src="assets/img/39.png" class="card-img">
            <h5>Sandalias típicas</h5>
            
            <p>$<small class="precio">15</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="39">Comprar</a>
          </div>
        



<div class="card">
            <img src="assets/img/40.png" class="card-img">
            <h5>Tacones típicos</h5>
            
            <p>$<small class="precio">25</small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
          </div>

          <a href="#" class="button agregar-carrito" data-id="40">Comprar</a>
          </div>
        



        <div class="card">
                    <img src="assets/img/41.png" class="card-img">
                    <h5>Tacones típicos</h5>
                    
                    <p>$<small class="precio">25</small></p>
                    <div class="cantidad">
                      <button class="btn-decrementar">-</button>
                      <input type="number" class="cantidad-input" value="1" min="1">
                      <button class="btn-incrementar">+</button>
                  </div>
        
                  <a href="#" class="button agregar-carrito" data-id="41">Comprar</a>
                  </div>
        



        <div class="card">
                    <img src="assets/img/42.png" class="card-img">
                    <h5>Tacones típicos</h5>
                    
                    <p>$<small class="precio">25</small></p>
                    <div class="cantidad">
                      <button class="btn-decrementar">-</button>
                      <input type="number" class="cantidad-input" value="1" min="1">
                      <button class="btn-incrementar">+</button>
                  </div>
        
                  <a href="#" class="button agregar-carrito" data-id="42">Comprar</a>
                  </div>
        
        

        </div>
</main>
  

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
    <script src="http://127.0.0.1/sadasd/RabinalArts/PaginaClientes/assets/js/carrito.js"></script>
    <script src="http://127.0.0.1/sadasd/RabinalArts/PaginaClientes/assets/js/scripts.js"></script>
    <script src="http://127.0.0.1/sadasd/RabinalArts/PaginaClientes/assets/js/pedido.js"></script>
    <script src="http://127.0.0.1/sadasd/RabinalArts/PaginaClientes/assets/js/compra.js"></script>
</body>
</html>
