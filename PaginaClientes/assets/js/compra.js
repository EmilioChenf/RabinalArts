document.addEventListener('DOMContentLoaded', function () {
    const carrito2 = document.getElementById('carrito');
    const procesarCompraBtn = document.getElementById('procesar-compra');
    const cliente = document.getElementById('cliente');
    const correo = document.getElementById('correo');

    if (carrito2) {
        carrito2.addEventListener('click', (e) => eliminarProducto(e));
    }

    if (procesarCompraBtn) {
        procesarCompraBtn.addEventListener('click', function (e) {
            e.preventDefault();
            if (!cliente.value || !correo.value) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Ingrese todos los campos requeridos',
                    timer: 2500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Compra realizada con éxito',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    localStorage.removeItem('productos');
                    window.location.href = "productos.html";
                });
            }
        });
    }
});
