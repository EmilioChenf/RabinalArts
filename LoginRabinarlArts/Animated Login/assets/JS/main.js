document.addEventListener("DOMContentLoaded", function () {
    const signUpButton = document.getElementById("signUp");
    const signInButton = document.getElementById("signIn");
    const container = document.getElementById("container");

    // Añade la clase para mostrar el formulario de registro
    signUpButton.addEventListener("click", function () {
        container.classList.add("right-panel-active");
    });

    // Quita la clase para mostrar el formulario de inicio de sesión
    signInButton.addEventListener("click", function () {
        container.classList.remove("right-panel-active");
    });
    
});
