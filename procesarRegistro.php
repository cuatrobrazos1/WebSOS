<?php
// Validar método de solicitud
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["nombre"]) && isset($_POST["correo"]) && isset($_POST["telefonoEmg"]) && isset($_POST["contrasena"])) {
        $nombre = $_POST["nombre"];
        $correo = $_POST["correo"];
        $tel = $_POST["telefonoEmg"];
        $pass = $_POST["contrasena"];
    } else {
        die("Faltan datos en el formulario.");
    }
} else {
    die("Método de solicitud no permitido.");
}

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vitalsos";

// Conectar a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Validar el número de teléfono (10 dígitos)
$regEx = "/^\d{10}$/";
$new_pass = md5($pass); // Cifrar la contraseña

if (preg_match($regEx, $tel)) {
    // Preparar consulta SQL para evitar inyección
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, contrasena, mail, telefono_emergencia) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("ssss", $nombre, $new_pass, $correo, $tel);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir en caso de éxito
        header("Location: finRegistro.html");
        exit();
    } else {
        echo "<p>Error al ejecutar la consulta: " . $stmt->error . "</p>";
    }

    $stmt->close();
} else {
    echo "<p>El número de teléfono no es válido. Debe tener 10 dígitos.</p>";
}

$conn->close(); // Cerrar conexión
?>
