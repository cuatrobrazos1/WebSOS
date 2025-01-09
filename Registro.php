<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y obtener los datos del formulario
    $nombre = $_POST["nombre"] ?? null;
    $correo = $_POST["correo"] ?? null;
    $tel = $_POST["telefonoEmg"] ?? null;
    $pass = $_POST["contrasena"] ?? null;

    // Verificar si se enviaron todos los datos requeridos
    if (!$nombre || !$correo || !$tel || !$pass) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Configuración de la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "vitalsos";

    // Crear la conexión
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Encriptar la contraseña de forma segura
    $new_pass = password_hash($pass, PASSWORD_BCRYPT);

    // Construir la consulta preparada para evitar inyección SQL
    $sql = "INSERT INTO usuarios (nombre, contraseña, mail, telefono_emergencia) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    // Asociar los parámetros
    $stmt->bind_param("ssss", $nombre, $new_pass, $correo, $tel);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir a la página de confirmación
        header("Location: finRegistro.html");
        exit();
    } else {
        echo "Error al insertar datos: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
} else {
    echo "Error: Método no permitido.";
}
?>
