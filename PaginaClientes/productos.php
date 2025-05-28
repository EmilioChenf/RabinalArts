<?php
session_start();
include "php/config.php";
// --- Procesar búsqueda ---
$termino = trim($_GET['q'] ?? '');
if ($termino !== '') {
    $param = '%' . mysqli_real_escape_string($conn, $termino) . '%';
    $sql = "SELECT * 
            FROM productos 
            WHERE nombre LIKE '$param' 
            ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM productos ORDER BY id DESC";
}
$result = mysqli_query($conn, $sql);
?>
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RabinalArts</title>
    <link href="assets/css/style.css" rel="stylesheet" type="text/css">
    <style>
      /* Estilos para el buscador */
      .busqueda-container {
        position: relative;
        max-width: 400px;
        margin: 1rem auto;
      }
      #search-input {
        width: 100%;
        padding: .5rem;
        font-size: 1rem;
      }
      .suggestions {
        position: absolute;
        top: 110%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #ccc;
        z-index: 10;
        max-height: 300px;
        overflow-y: auto;
      }
      .suggestion-item {
        display: flex;
        align-items: center;
        padding: .5rem;
        cursor: pointer;
      }
      .suggestion-item img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        margin-right: .5rem;
      }
      .suggestion-item:hover {
        background: #f0f0f0;
      }
      /* Highlight animado */
      .card.highlight {
        animation: highlightAnim 2s ease-in-out;
      }
      @keyframes highlightAnim {
        0%   { box-shadow: 0 0 0px rgba(255, 215, 0, 0.8); }
        50%  { box-shadow: 0 0 10px rgba(255, 215, 0, 0.8); }
        100% { box-shadow: 0 0 0px rgba(255, 215, 0, 0.8); }
      }
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

 <main>
      <!-- Buscador -->
      <div class="busqueda-container">
        <input 
          type="text" 
          id="search-input" 
          placeholder="Buscar producto por nombre…" 
          value="<?= htmlspecialchars($termino) ?>"
        >
        <div id="suggestions" class="suggestions"></div>
      </div>

      <!-- Lista de productos -->
      <div class="container-productos" id="lista-productos">
        <?php if (mysqli_num_rows($result) === 0): ?>
          <p>No se encontraron productos para «<?= htmlspecialchars($termino) ?>».</p>
        <?php endif; ?>

        <?php while ($row = mysqli_fetch_assoc($result)):
          $cardId = 'producto-' . $row['id'];
        ?>
          <div class="card" id="<?= $cardId ?>" data-id="<?= $row['id'] ?>">
            <img 
              src="http://localhost/sadasd/RabinalArts/PaginaAdminn/dist/pages/widgets/uploads/<?= htmlspecialchars($row['imagen']) ?>" 
              class="card-img"
            >
            <h5 class="card-title"><?= htmlspecialchars($row['nombre']) ?></h5>
            <p>$<small class="precio"><?= number_format($row['precio'], 2) ?></small></p>
            <div class="cantidad">
              <button class="btn-decrementar">-</button>
              <input type="number" class="cantidad-input" value="1" min="1">
              <button class="btn-incrementar">+</button>
            </div>
            <a href="#" class="button agregar-carrito" data-id="<?= $row['id'] ?>">Comprar</a>
          </div>
        <?php endwhile; ?>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.agregar-carrito').forEach(boton => {
    boton.addEventListener('click', function (e) {
      e.preventDefault();
      const card = e.target.closest('.card');
      const cantidad = parseInt(card.querySelector('.cantidad-input').value);
      const infoProducto = {
        imagen: card.querySelector('img').src,
        titulo: card.querySelector('h5').textContent,
        precio: parseFloat(card.querySelector('.precio').textContent),
        id: e.target.getAttribute('data-id'),
        cantidad: cantidad
      };

      let productos = JSON.parse(localStorage.getItem('productos')) || [];
      const index = productos.findIndex(p => p.id === infoProducto.id);
      if (index !== -1) {
        productos[index].cantidad = cantidad; 
      } else {
        productos.push(infoProducto);
      }

      localStorage.setItem('productos', JSON.stringify(productos));
      document.cookie = "carrito=" + encodeURIComponent(JSON.stringify(productos)) + "; path=/; SameSite=Lax";

      Swal.fire({
        icon: 'success',
        title: 'Producto agregado al carrito',
        timer: 1500,
        showConfirmButton: false
      });
    });
  });
});


    document.addEventListener('DOMContentLoaded', () => {
      const cards = Array.from(document.querySelectorAll('.card'));
      const products = cards.map(card => ({
        id: card.dataset.id,
        title: card.querySelector('.card-title').textContent.trim(),
        img: card.querySelector('img').src,
        price: card.querySelector('.precio').textContent.trim()
      }));

      const input = document.getElementById('search-input');
      const suggestions = document.getElementById('suggestions');

      input.addEventListener('input', () => {
        const term = input.value.trim().toLowerCase();
        suggestions.innerHTML = '';
        if (!term) return;

        const matches = products.filter(p => p.title.toLowerCase().includes(term));
        matches.forEach(p => {
          const div = document.createElement('div');
          div.classList.add('suggestion-item');
          div.innerHTML = `
            <img src="${p.img}" alt="${p.title}">
            <div>
              <strong>${p.title}</strong><br>
              $${p.price}
            </div>
          `;
          div.addEventListener('click', () => {
            const target = document.getElementById('producto-' + p.id);
            if (target) {
              target.scrollIntoView({ behavior: 'smooth', block: 'start' });
              target.classList.add('highlight');
              setTimeout(() => target.classList.remove('highlight'), 2000);
            }
            suggestions.innerHTML = '';
            input.value = '';
          });
          suggestions.appendChild(div);
        });
      });
     });


document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('btn-incrementar')) {
        let input = e.target.previousElementSibling;
        if (input && input.classList.contains('cantidad-input')) {
            input.value = parseInt(input.value) + 1;
        }
    } else if (e.target && e.target.classList.contains('btn-decrementar')) {
        let input = e.target.nextElementSibling;
        if (input && input.classList.contains('cantidad-input') && parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }
});

</script>


<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="assets/js/scripts.js"></script>
<script src="assets/js/pedido.js"></script>
<script src="assets/js/compra.js"></script>
</body>
</html>
