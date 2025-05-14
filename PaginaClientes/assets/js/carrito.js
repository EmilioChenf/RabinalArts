class Carrito {
    comprarProducto(e) {
        e.preventDefault();
        if (e.target.classList.contains('agregar-carrito')) {
            const producto = e.target.parentElement;
            this.leerDatosProducto(producto);
        }
    }

    leerDatosProducto(producto) {
        const cantidad = parseInt(producto.querySelector('.cantidad-input').value);
        const infoProducto = {
            imagen: producto.querySelector('img').src,
            titulo: producto.querySelector('h5').textContent,
            precio: parseFloat(producto.querySelector('.precio').textContent),
            id: producto.querySelector('a').getAttribute('data-id'),
            cantidad: cantidad
        };

        let productosLS = this.obtenerProductosLocalStorage();
        let index = productosLS.findIndex(p => p.id === infoProducto.id);

        if (index !== -1) {
            productosLS[index].cantidad = cantidad;
        } else {
            productosLS.push(infoProducto);
        }

        this.guardarProductosLocalStorage(productosLS);
        this.actualizarCookie(productosLS);

        Swal.fire({
            icon: 'success',
            title: 'Producto agregado al carrito',
            timer: 2000,
            showConfirmButton: false
        });
    }

    guardarProductosLocalStorage(productos) {
        localStorage.setItem('productos', JSON.stringify(productos));
    }

    obtenerProductosLocalStorage() {
        return JSON.parse(localStorage.getItem('productos')) || [];
    }

    actualizarCookie(productos) {
        document.cookie = "carrito=" + encodeURIComponent(JSON.stringify(productos)) + "; path=/; SameSite=Lax";
    }

    limpiarCarrito() {
        localStorage.removeItem('productos');
        document.cookie = "carrito=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }
}

// Instancia global
const carrito = new Carrito();

// Al cargar la página
document.addEventListener('DOMContentLoaded', function () {
    carrito.leerLocalStorageCompra();
});

// ✅ Ya no volvemos a agregar el evento 'click' para .agregar-carrito aquí
// para evitar duplicidad si ya se hace desde productos.php

// Mantener funciones de incremento/decremento/cálculo total

function eliminarProducto(id, btn) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Se eliminará el producto del carrito',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            let productosLS = JSON.parse(localStorage.getItem('productos')) || [];
            productosLS = productosLS.filter(producto => producto.id !== id);
            localStorage.setItem('productos', JSON.stringify(productosLS));
            carrito.actualizarCookie(productosLS);

            let row = btn.closest('tr');
            if (row) row.remove();

            calcularTotal();

            Swal.fire({
                icon: 'success',
                title: 'Producto eliminado',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}

function actualizarCantidad(input) {
    let row = input.closest('tr');
    if (!row) return;

    let id = row.dataset.id;
    let productosLS = JSON.parse(localStorage.getItem('productos')) || [];
    let producto = productosLS.find(p => p.id === id);

    if (producto) {
        producto.cantidad = parseInt(input.value);
        localStorage.setItem('productos', JSON.stringify(productosLS));
        carrito.actualizarCookie(productosLS);

        let subtotalElement = row.querySelector('.subtotal');
        if (subtotalElement) {
            subtotalElement.textContent = (producto.precio * producto.cantidad).toFixed(2);
        }

        calcularTotal();
    }
}

function calcularTotal() {
    let productosLS = JSON.parse(localStorage.getItem('productos')) || [];
    let total = productosLS.reduce((sum, producto) => sum + (producto.precio * producto.cantidad), 0);

    let subtotalElement = document.getElementById('subtotal');
    let totalElement = document.getElementById('total');

    if (subtotalElement) subtotalElement.innerHTML = "$" + total.toFixed(2);
    if (totalElement) totalElement.value = "$" + total.toFixed(2);
}

Carrito.prototype.leerLocalStorageCompra = function () {
    let productosLS = JSON.parse(localStorage.getItem('productos')) || [];
    let listaCompra = document.querySelector('#lista-compra tbody');

    if (!listaCompra) return;

    listaCompra.innerHTML = '';

    if (productosLS.length === 0) {
        console.warn("No hay productos en el carrito.");
        return;
    }

    productosLS.forEach(producto => {
        let subtotal = producto.precio * producto.cantidad;
        const row = document.createElement('tr');
        row.dataset.id = producto.id;
        row.innerHTML = `
            <td><img src="${producto.imagen}" width=100></td>
            <td>${producto.titulo}</td>
            <td>$${producto.precio.toFixed(2)}</td>
            <td>
                <button class="btn-decrementar">-</button>
                <input type="number" class="cantidad-carrito" value="${producto.cantidad}" min="1">
                <button class="btn-incrementar">+</button>
            </td>
            <td>$<span class="subtotal">${subtotal.toFixed(2)}</span></td>
            <td><button class="borrar-producto" data-id="${producto.id}">🗑️</button></td>
        `;
        listaCompra.appendChild(row);
    });

    calcularTotal();
};
