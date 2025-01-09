<?php
// Cargar PHPMailer manualmente
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Iniciar sesión para capturar el ID del usuario autenticado
session_start();

// Configuración de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vitalsos";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipoEmergencia = $_POST['tipoEmergencia'] ?? null; // "grave" o "leve"

    if (empty($tipoEmergencia)) {
        http_response_code(400);
        echo json_encode(["error" => "Faltan datos requeridos"]);
        exit;
    }

    // Obtener el ID del usuario desde la sesión
    $usuarioId = $_SESSION['user_id'] ?? null;

    if (empty($usuarioId)) {
        http_response_code(403);
        echo json_encode(["error" => "Usuario no autenticado"]);
        exit;
    }

    // Inicializar PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'vitalsosgrupo2@gmail.com';
        $mail->Password = 'Vital1234';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('vitalsosgrupo2@gmail.com', 'Sistema de Emergencias');
        $mail->isHTML(true);

        // Manejo de emergencia leve
        if ($tipoEmergencia === "leve") {
            $name = $_POST['name'] ?? 'No especificado';
            $email = $_POST['email'] ?? 'No especificado';
            $phone = $_POST['phone'] ?? 'No especificado';
            $address = $_POST['address'] ?? 'No especificado';
            $servicios = isset($_POST['servicio']) ? implode(", ", $_POST['servicio']) : "Ninguno";

            $mensaje = "<p><strong>Nombre:</strong> $name</p>";
            $mensaje .= "<p><strong>Correo:</strong> $email</p>";
            $mensaje .= "<p><strong>Teléfono:</strong> $phone</p>";
            $mensaje .= "<p><strong>Dirección:</strong> $address</p>";
            $mensaje .= "<p><strong>Servicios solicitados:</strong> $servicios</p>";
            $mensaje .= "<p>Tipo de emergencia: Leve</p>";

            $mail->addAddress($email);
            $mail->Subject = "Emergencia Leve";
            $mail->Body = $mensaje;

        // Manejo de emergencia grave
        } elseif ($tipoEmergencia === "grave") {
            $name = $_POST['name'] ?? 'No especificado';
            $email = $_POST['email'] ?? 'No especificado';
            $phone = $_POST['phone'] ?? 'No especificado';
            $ubicacion = $_POST['ubicacion'] ?? 'No especificado';
            $servicios = isset($_POST['servicio']) ? implode(", ", $_POST['servicio']) : "Ninguno";

            // Crear conexión a la base de datos
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                throw new Exception("Conexión fallida: " . $conn->connect_error);
            }

            $sql = "INSERT INTO emergencias (ID_Usuario, nombre_usuario, mail_usuario, telefono_usuario, ubicacion, servicios, tipo_emergencia)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Error al preparar la consulta SQL: " . $conn->error);
            }

            $tipoEmergenciaGrave = "grave";
            $stmt->bind_param("issssss", $usuarioId, $name, $email, $phone, $ubicacion, $servicios, $tipoEmergenciaGrave);

            if (!$stmt->execute()) {
                throw new Exception("Error al guardar en la base de datos: " . $stmt->error);
            }

            $mensaje = "<p><strong>Nombre:</strong> $name</p>";
            $mensaje .= "<p><strong>Correo:</strong> $email</p>";
            $mensaje .= "<p><strong>Teléfono:</strong> $phone</p>";
            $mensaje .= "<p><strong>Ubicación:</strong> $ubicacion</p>";
            $mensaje .= "<p><strong>Servicios solicitados:</strong> $servicios</p>";
            $mensaje .= "<p>Tipo de emergencia: Grave</p>";

            $mail->addAddress('destinatario@example.com');
            $mail->Subject = "Emergencia Grave";
            $mail->Body = $mensaje;

            $stmt->close();
            $conn->close();

        } else {
            throw new Exception("Tipo de emergencia no válido");
        }

        $mail->send();
        echo json_encode(["success" => "Correo enviado y datos almacenados correctamente"]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Error al procesar la solicitud: " . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
