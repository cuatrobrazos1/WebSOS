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

// Obtener el tipo de emergencia y otros datos del formulario
$tipoEmergencia = "leve"; // Siempre será "leve"
$id_usuario = $_SESSION["id"]; // ID del usuario desde la sesión

// Datos del formulario
$nombreUsuario = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : 'Nombre no especificado';
$emailUsuario = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : 'Email no especificado';
$telefonoUsuario = isset($_POST['phone']) ? $conn->real_escape_string($_POST['phone']) : null;
$direccion = isset($_POST['address']) ? $conn->real_escape_string($_POST['address']) : 'No especificada';
$servicios = isset($_POST['servicio']) ? implode(", ", $_POST['servicio']) : 'Ninguno';

// Validar que el número de teléfono no sea nulo
if ($telefonoUsuario === null) {
    echo "<script>
        alert('El número de teléfono es obligatorio.');
        window.location.href = 'FormularioSOS.html'; // Redirigir si no se ha ingresado teléfono
    </script>";
    exit();
}

// Consultar información del usuario desde la base de datos
$sql = "SELECT nombre, mail, telefono_emergencia FROM usuarios WHERE ID_Usuario = ?";
$stmt = $conn->prepare($sql);

// Verificar si la consulta fue preparada correctamente
if ($stmt === false) {
    die("Error en la consulta SQL: " . $conn->error);
}

$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Obtener los datos del usuario
    $row = $result->fetch_assoc();
    $nombreUsuario = $row['nombre'];
    $emailUsuario = $row['mail'];

    // Insertar datos en la tabla emergencias
    $sqlInsert = "INSERT INTO emergencias (ID_Usuario, nombre_usuario, mail_usuario, ubicacion, servicios, tipo_emergencia, policia, bomberos, ambulancia)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);

    // Verificar si la consulta de inserción fue preparada correctamente
    if ($stmtInsert === false) {
        die("Error en la consulta de inserción: " . $conn->error);
    }

    // Definir valores para los servicios
    $ubicacion = $direccion; // Tomamos la dirección del formulario como ubicación
    $policia = in_array("policia", $_POST['servicio']) ? 1 : 0;
    $bomberos = in_array("bomberos", $_POST['servicio']) ? 1 : 0;
    $ambulancia = in_array("ambulancia", $_POST['servicio']) ? 1 : 0;

    $stmtInsert->bind_param(
        "issssiiii",
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
            alert('Emergencia leve registrada correctamente. los servicios seleccionados estan en camino');
            window.location.href = 'EmergenciaLEVE.html'; // Redirigir después del registro
        </script>";
    } else {
        echo "Error al registrar la emergencia: " . $stmtInsert->error;
    }
} else {
    echo "<script>
        alert('Usuario no encontrado.');
        window.location.href = 'FormularioSOS.html';
    </script>";
}

// Cerrar conexión
$conn->close();
?>
