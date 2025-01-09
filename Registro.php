<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vitalsos";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si los datos fueron enviados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefonoEmg = $_POST['telefonoEmg'];
    $contrasena = $_POST['contraseña'];
    $confirmarContrasena = $_POST['confirmarContrasena'];

    // Verificar que las contraseñas coincidan
    if ($contrasena !== $confirmarContrasena) {
        echo "<p>Las contraseñas no coinciden. Por favor, inténtalo de nuevo.</p>";
        exit;
    }

    // Encriptar la contraseña
    $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

    // Preparar y ejecutar la consulta SQL
    $sql = "INSERT INTO usuarios (nombre, contraseña, mail, telefono_emergencia) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nombre, $hashed_password, $correo, $telefonoEmg);

    if ($stmt->execute()) {
        echo "<p>Registro exitoso. Ahora puedes <a href='inicioSesion.html'>iniciar sesión</a>.</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
}

$conn->close();
?>