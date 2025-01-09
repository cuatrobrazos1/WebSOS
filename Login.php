<?php
session_start();

// Depuración: verificar si hay datos en la sesión
if (isset($_SESSION['user_id'])) {
    echo "Sesión ya iniciada para el usuario con ID: " . $_SESSION['user_id'];
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["correo"]) && isset($_POST["contrasena"])) {
        $mail = $_POST["correo"];
        $pass = $_POST["contrasena"];
    }
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vitalsos";

// Conexión a la base de datos
$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Ha fallado la conexión: " . $mysqli->connect_error);
}

// Consulta para verificar el correo
$sql = "SELECT ID_Usuario, nombre, telefono_emergencia, contraseña FROM usuarios WHERE mail = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $mail);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si el correo existe
if ($result && $result->num_rows > 0) {
    // Obtener los datos del usuario
    $row = $result->fetch_assoc();

    // Verificar la contraseña con password_verify
    if (password_verify($pass, $row['contraseña'])) {
        // Si la contraseña coincide
        $_SESSION["user_id"] = $row["ID_Usuario"]; // Guardar el ID del usuario en la sesión
        $_SESSION["nombre"] = $row["nombre"];
        $_SESSION["mail"] = $mail;
        $_SESSION["tel"] = $row["telefono_emergencia"];

        // Depuración: verificar el contenido de la sesión
        var_dump($_SESSION);

        // Redirigir al panel de usuario
        header("Location: PERSONAL.php");
    } else {
        // Si la contraseña no coincide
        echo "<p>El correo o la contraseña no son válidos.</p>";
    }
} else {
    echo "<p>El correo no está registrado.</p>";
}

// Cerrar la conexión
$stmt->close();
$mysqli->close();
?>
