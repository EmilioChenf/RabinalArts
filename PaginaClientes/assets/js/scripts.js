jQuery('document').ready(function($){

    //menu
    var menuBtn=$('.menu-icon'),
    menu=$('.navigation ul');
    
        menuBtn.click(function(){
            if(menu.hasClass('show')){
                menu.removeClass('show');
            }else{
                menu.addClass('show');
            }
        });
    
    //carrito-menu
    var carritoMenuBtn=$('.car'),
    carritomenu=$('.dropdown-menu');
    
    carritoMenuBtn.click(function(){
            if(carritomenu.hasClass('show')){
                carritomenu.removeClass('show');
            }else{
                carritomenu.addClass('show');
            }
        });
    
    });
    
    
    //productos
    let imagenes = document.querySelectorAll('.card-img');
    let modal = document.querySelector('#modal');
    let img = document.querySelector('#modal-img');
    let boton = document.querySelector('#modal-boton');
    
    for(let i = 0; i<imagenes.length;i++){
        imagenes[i].addEventListener('click',function(e){
            modal.classList.toggle("modal-open");
            let src = e.target.src;
            img.setAttribute("src",src);
        });
    }
    boton.addEventListener('click',function(){
        modal.classList.toggle("modal-open");
    });
    document.addEventListener('DOMContentLoaded', function() {
        let botonProcesarCompra = document.getElementById('procesar-compra');
    
        if (botonProcesarCompra) {
            botonProcesarCompra.addEventListener('click', function() {
                console.log("🛒 Compra procesada correctamente.");
            });
        } else {
            console.warn("⚠️ El botón 'procesar-compra' no fue encontrado en el DOM.");
        }
    });
    
    
    
    //validar correo
    function validarCorreo(correo){
        var expReg = /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/;
        var esValido = expReg.test(correo);
        if(esValido == true){
            console.log("es valido el correo");
        }
        else{
            alert("el correo no es válido");
        }
    }
    
    //validar nombres
    function sololetras(e){
        key = e.keyCode || e.which;
        teclado = String.fromCharCode(key).toLowerCase();
        letras = " abcdefghijklmnñopqrstuvwxyzáéíóúü";
        especiales = "8-37-38-46-164";
        teclado_especial = false;
        for(var i in especiales){
            if(key == especiales[i]){
                teclado_especial = true; break;
            }
        }
        if(letras.indexOf(teclado) == -1 && !teclado_especial){
            return false;
        }
    }
    
    //validar numeros
    function solonumeros(e){
        key = e.keyCode || e.which;
        teclado = String.fromCharCode(key).toLowerCase();
        numeros = "0123456789";
        especiales = "8-37-38-46-164";
        teclado_especial = false;
        for(var i in especiales){
            if(key == especiales[i]){
                teclado_especial = true; break;
            }
        }
        if(numeros.indexOf(teclado) == -1 && !teclado_especial){
            return false;
        }
    }
    
    
    //validar formulario-contacto
    /* function validar(){
        let nombre = document.getElementById("nombre").value;
        let correo = document.getElementById("correo").value;
        let celular = document.getElementById("celular").value;
        let mensaje = document.getElementById("mensaje").value;
        let expresion = /\w+@\w+\.+[a-z]/;
        if(nombre ==="" || correo ==="" || celular ==="" || mensaje ===""){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Todos los campos son obligatorios',
                timer: 2500,
                showConfirmButton: true
            })
            return false;
        }
        else if(!expresion.test(correo)){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'El correo no es válido',
                timer: 2500,
                showConfirmButton: true
            })
            return false;
        }
        else if(celular.length>10){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'El celular es muy largo',
                timer: 2500,
                showConfirmButton: true
            })
            return false;
        }
        else if(isNaN(celular)){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'El celular ingresado no es un número',
                timer: 2500,
                showConfirmButton: true
            })
            return false;
        }
    } */
    