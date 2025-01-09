<?php
session_start();
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

$mysqli = new mysqli($servername, $username, $password, $dbname);

if (!$mysqli) {
    die("Ha fallado la conexion: " . mysqli_connect_error());
}

// Ejecutar la consulta SQL para verificar el correo
$sql = "SELECT nombre, telefono_emergencia, contraseña FROM usuarios WHERE mail = '$mail'";

$result = $mysqli->query($sql);

// Verificar si el correo existe
if ($result) {
    $numRows = mysqli_num_rows($result);
    if ($numRows == 0) {
        echo "<p>El correo no está registrado.</p>";
    } else {
        // Obtener los datos del usuario
        $row = mysqli_fetch_assoc($result);

        // Verificar la contraseña con password_verify
        if (password_verify($pass, $row['contraseña'])) {
            // Si la contraseña coincide
            $_SESSION["nombre"] = $row["nombre"];
            $_SESSION["mail"] = $mail;
            $_SESSION["tel"] = $row["telefono_emergencia"];
            header("Location: PERSONAL.php");
        } else {
            // Si la contraseña no coincide
            echo "<p>El correo o la contraseña no son válidos.</p>";
        }
    }
} else {
    echo "Error en la consulta SQL: " . $mysqli->error;
}
?>
