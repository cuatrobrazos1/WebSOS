document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('#loginForm');
    const correo = document.getElementById('correo');
    const contrasena = document.getElementById('contrasena');

    form.addEventListener('submit', function (event) {
        event.preventDefault(); // Evita el envío del formulario para validarlo primero

        if (!correo.value || !contrasena.value) {
            alert('Por favor, complete todos los campos.');
            return; // Evita el envío si hay campos vacíos
        }

        window.location.href = 'Decision.html'; // Redirige a la página de decisión si los campos están completos
    });

    form.style.opacity = 0;
    setTimeout(() => {
        form.style.transition = 'opacity 1s';
        form.style.opacity = 1;
    }, 500);
});
