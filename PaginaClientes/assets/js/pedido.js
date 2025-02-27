document.addEventListener('DOMContentLoaded', function () {
    const productos = document.getElementById('lista-productos');
    const carritoElement = document.getElementById('carrito');
    const procesarPedidoBtn = document.getElementById('procesar-pedido');

    if (productos) {
        productos.addEventListener('click', (e) => carrito.comprarProducto(e));
    }
    
    if (procesarPedidoBtn) {
        procesarPedidoBtn.addEventListener('click', procesarPedido);
    }

    function procesarPedido(e) {
        e.preventDefault();
        let productosLS = carrito.obtenerProductosLocalStorage();
        if (productosLS.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'El carrito está vacío, agrega un producto',
                timer: 2500,
                showConfirmButton: false
            });
        } else {
            window.location.href = "carrito.html";
        }
    }
});
