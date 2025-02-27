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
            productosLS[index].cantidad += cantidad;
        } else {
            productosLS.push(infoProducto);
        }

        this.guardarProductosLocalStorage(productosLS);
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
}

// Instancia global para que otros archivos puedan usarla
const carrito = new Carrito();


// Correcciones en carrito.js para evitar errores en el DOM
document.addEventListener('DOMContentLoaded', function () {
    cargarEventos();
    leerLocalStorageCompra();
});

// ✅ Captura eventos de todos los botones dentro de la tabla del carrito
document.addEventListener('click', function (e) {
    if (e.target && e.target.classList.contains('btn-incrementar')) {
        let input = e.target.previousElementSibling;
        if (input) {
            input.value = parseInt(input.value) + 1;
            actualizarCantidad(input);
        }
    } else if (e.target && e.target.classList.contains('btn-decrementar')) {
        let input = e.target.nextElementSibling;
        if (input && parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
            actualizarCantidad(input);
        }
    } else if (e.target && e.target.classList.contains('borrar-producto')) {
        let id = e.target.getAttribute('data-id');
        if (id) {
            eliminarProducto(id, e.target); // ✅ Solo ejecuta la alerta si el usuario hace clic en la papelera
        }
    }
});


// ✅ Función para eliminar un producto del carrito
function eliminarProducto(id, btn) {
    // Verifica si el botón tiene la clase 'borrar-producto' para evitar falsos positivos
    if (!btn.classList.contains('borrar-producto')) return;

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
            
            // Filtra los productos para remover el seleccionado
            productosLS = productosLS.filter(producto => producto.id !== id);
            
            // Guarda los productos actualizados en localStorage
            localStorage.setItem('productos', JSON.stringify(productosLS));

            // Elimina el producto del DOM
            let row = btn.closest('tr');
            if (row) {
                row.remove();
            }

            calcularTotal(); // Recalcular el total después de eliminar un producto

            Swal.fire({
                icon: 'success',
                title: 'Producto eliminado',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}


// ✅ Función para actualizar la cantidad y recalcular el subtotal
function actualizarCantidad(input) {
    let row = input.closest('tr');
    if (!row) {
        console.error("Error: No se encontró la fila del producto.");
        return;
    }

    let id = row.dataset.id;
    let productosLS = JSON.parse(localStorage.getItem('productos')) || [];
    let producto = productosLS.find(p => p.id === id);

    if (producto) {
        producto.cantidad = parseInt(input.value);
        localStorage.setItem('productos', JSON.stringify(productosLS));

        let subtotalElement = row.querySelector('.subtotal');
        if (subtotalElement) {
            subtotalElement.textContent = (producto.precio * producto.cantidad).toFixed(2);
        }

        calcularTotal();
    } else {
        console.error("Error: No se encontró el producto en LocalStorage.");
    }
}

// ✅ Función para calcular el total correctamente
function calcularTotal() {
    let productosLS = JSON.parse(localStorage.getItem('productos')) || [];
    let total = productosLS.reduce((sum, producto) => sum + (producto.precio * producto.cantidad), 0);

    let subtotalElement = document.getElementById('subtotal');
    let igvElement = document.getElementById('igv');
    let totalElement = document.getElementById('total');

    if (subtotalElement) subtotalElement.innerHTML = "S/. " + (total / 1.18).toFixed(2);
    if (igvElement) igvElement.innerHTML = "S/. " + (total * 0.18).toFixed(2);
    if (totalElement) totalElement.value = "S/. " + total.toFixed(2);
}

function cargarEventos() {
    let procesarCompraBtn = document.getElementById('procesar-compra');
    
    if (procesarCompraBtn) {
        procesarCompraBtn.addEventListener('click', procesarPedido);
    } else {
        console.warn("El botón 'procesar-compra' no fue encontrado en el DOM.");
    }
}

function leerLocalStorageCompra() {
    let productosLS = JSON.parse(localStorage.getItem('productos')) || [];
    let listaCompra = document.querySelector('#lista-compra tbody');

    if (!listaCompra) {
        console.error("Error: No se encontró la tabla del carrito en el DOM.");
        return;
    }

    listaCompra.innerHTML = '';

    if (productosLS.length === 0) {
        console.warn("No hay productos en el carrito.");
        return;
    }

    productosLS.forEach(producto => {
        if (!producto || !producto.id) {
            console.error("Producto inválido:", producto);
            return;
        }

        const row = document.createElement('tr');
        row.dataset.id = producto.id;
        row.innerHTML = `
            <td><img src="${producto.imagen}" width=100></td>
            <td>${producto.titulo}</td>
            <td>S/.${producto.precio.toFixed(2)}</td>
            <td>
                <button class="btn-decrementar">-</button>
                <input type="number" class="cantidad-carrito" value="${producto.cantidad}" min="1">
                <button class="btn-incrementar">+</button>
            </td>
            <td>S/.<span class="subtotal">${(producto.precio * producto.cantidad).toFixed(2)}</span></td>
            <td>
                <button class="borrar-producto" data-id="${producto.id}">🗑️</button>
            </td>
        `;
        listaCompra.appendChild(row);
    });

    calcularTotal();
}


function procesarPedido(e) {
    e.preventDefault();
    
    let productosLS = JSON.parse(localStorage.getItem('productos')) || [];
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

