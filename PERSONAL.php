<?php
    session_start();
    if (isset($_SESSION["nombre"]) && isset($_SESSION["mail"]) && isset($_SESSION["tel"])) {
        $nombre = $_SESSION["nombre"];
        $mail = $_SESSION["mail"];
        $tel = $_SESSION["tel"];
    }
?>
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="style2.css">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
    <title>Información Personal</title>
<body>
<div class="form-container">
    <h2>Información Personal</h2>
    <div class="user-data">
        <h3>Datos del Usuario:</h3>
        <p><strong>Nombre:</strong> <span id="user-name"><?php echo $nombre?></span></p>
        <p><strong>Correo:</strong> <span id="user-email"><?php echo $mail?></span></p>
        <p><strong>Contacto de Emergencia:</strong> <span id="user-contact"><?php echo $tel?></span></p>
    </div>
    <div class ='under-button'>
        <a href="EmergenciaGRAVE.html">
            <button>EMERGENCIA</button>
        </a>
        <a href="Emergencialeve.html">
            <button>Emergencia Leve </button>
        </a>
    </div>
</div>
</body>
</html>