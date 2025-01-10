<?php
session_start();
// Verificar si la sesión está iniciada y contiene un ID de usuario válido
if (!isset($_SESSION["id"])) {
    echo "<script>
        alert('Debes iniciar sesión primero.');
        window.location.href = 'inicioSesion.php'; // Redirigir al inicio de sesión
    </script>";
    exit();
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vitalsos";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Fallo en la conexión: " . $conn->connect_error);
}

// Obtener el tipo de emergencia del formulario
$tipoEmergencia = isset($_POST['tipoEmergencia']) ? $_POST['tipoEmergencia'] : 'grave';

// ID del usuario desde la sesión
$id_usuario = $_SESSION["id"];

// Consultar información del usuario
$sql = "SELECT nombre, mail, telefono_emergencia FROM usuarios WHERE ID_Usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Obtener datos del usuario
    $row = $result->fetch_assoc();
    $nombreUsuario = $row['nombre'];
    $emailUsuario = $row['mail'];

    // Valores por defecto para ubicación y servicios
    $ubicacion = "Ubicación no especificada"; // Cambiar según sea necesario
    $policia = 1; // Activar policía por defecto
    $bomberos = 1; // Activar bomberos por defecto
    $servicios = "Emergencia GRAVE";
    $ambulancia = 1; // Activar ambulancia por defecto

    // Insertar datos en la tabla emergencias
    $sqlInsert = "INSERT INTO emergencias (ID_Usuario, nombre_usuario, mail_usuario, ubicacion, servicios, tipo_emergencia, policia, bomberos, ambulancia)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param(
        "isssssiii",
        $id_usuario,
        $nombreUsuario,
        $emailUsuario,
        $ubicacion,
        $servicios,
        $tipoEmergencia,
        $policia,
        $bomberos,
        $ambulancia
    );

    if ($stmtInsert->execute()) {
        echo "<script>
            alert('Se ha enviado un correo a $emailUsuario notificando la emergencia. Los servicios de emergencia estan en camino');
            window.location.href = 'EmergenciaGRAVE.html'; // Redirigir después del mensaje
        </script>";
    } else {
        echo "Error al insertar en la tabla emergencias: " . $stmtInsert->error;
    }
} else {
    echo "<script>
        alert('Usuario no encontrado.');
        window.location.href = 'EmergenciaGRAVE.html';
    </script>";
}

// Cerrar conexión
$conn->close();
?>
