// Esperamos a que el documento esté completamente cargado
document.addEventListener('DOMContentLoaded', function () {

    // Obtener el formulario
    const form = document.querySelector('form');

    // Obtener los campos del formulario
    const nombre = document.getElementById('nombre');
    const correo = document.getElementById('correo');
    const contrasena = document.getElementById('contrasena');
    const confirmarContrasena = document.getElementById('confirmarContrasena');
    const submitButton = form.querySelector('button');

    // Función para validar que las contraseñas coincidan
    function validarContraseñas() {
        if (contrasena.value !== confirmarContrasena.value) {
            confirmarContrasena.setCustomValidity('Las contraseñas no coinciden.');
        } else {
            confirmarContrasena.setCustomValidity('');
        }
    }

    // Evento para validar las contraseñas cuando el usuario las escribe
    contrasena.addEventListener('input', validarContraseñas);
    confirmarContrasena.addEventListener('input', validarContraseñas);

    // Validar el formulario antes de enviarlo
    form.addEventListener('submit', function (event) {
        event.preventDefault();  // Evitar el envío para validar

        // Verificar si todos los campos están llenos
        if (!nombre.value || !correo.value || !contrasena.value || !confirmarContrasena.value) {
            alert('Por favor, complete todos los campos.');
            return;
        }

        // Si las contraseñas no coinciden
        if (contrasena.value !== confirmarContrasena.value) {
            alert('Las contraseñas no coinciden.');
            return;
        }
        form.submit();
        window.location.href = 'Decision.html'
    });

    form.style.opacity = 0;
    setTimeout(() => {
        form.style.transition = 'opacity 1s';
        form.style.opacity = 1;
    }, 500);

});
