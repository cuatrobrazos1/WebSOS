<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="style5.css">
</head>
<body class="pagina-registro">

<header>
    <h1>VITAL SOS</h1>
    <h2>Iniciar sesión</h2>
</header>

<section class="formulario">
    <form id="loginForm" action="Login.php" method="POST">
        <div class="campo">
            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" required>
        </div>

        <div class="campo">
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
        </div>

        <button type="submit">Iniciar sesión</button>
    </form>

    <?php
        session_start();
        if (isset($_SESSION['error'])) {
        echo "<p>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    ?>

    <p>¿No tienes cuenta? <a href="registro.html">Registrarse</a></p>
</section>

</body>
</html>