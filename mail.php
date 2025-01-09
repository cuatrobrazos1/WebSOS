<?php
// Cargar PHPMailer manualmente
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Configuración de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vitalsos";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipoEmergencia = $_POST['tipoEmergencia']; // "grave" o "leve"

    if (empty($tipoEmergencia)) {
        http_response_code(400);
        echo json_encode(["error" => "Faltan datos requeridos"]);
        exit;
    }

    // Inicializar PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'vitalsosgrupo2@gmail.com'; // Tu dirección de correo
        $mail->Password = 'Vital1234'; // Tu contraseña o contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Habilitar depuración
        $mail->SMTPDebug = 2; // 2 muestra detalles completos del protocolo SMTP
        $mail->Debugoutput = 'html'; // Salida en formato HTML (más legible)

        $mail->setFrom('vitalsosgrupo2@gmail.com', 'Sistema de Emergencias'); // Dirección de envío
        $mail->isHTML(true);

        if ($tipoEmergencia === "leve") {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $servicios = isset($_POST['servicio']) ? implode(", ", $_POST['servicio']) : "Ninguno";

            $mensaje = "<p><strong>Nombre:</strong> $name</p>";
            $mensaje .= "<p><strong>Correo:</strong> $email</p>";
            $mensaje .= "<p><strong>Teléfono:</strong> $phone</p>";
            $mensaje .= "<p><strong>Dirección:</strong> $address</p>";
            $mensaje .= "<p><strong>Servicios solicitados:</strong> $servicios</p>";
            $mensaje .= "<p>Tipo de emergencia: Leve</p>";

            $mail->addAddress($email); // Dirección del destinatario
            $mail->Subject = "Emergencia Leve";
            $mail->Body = $mensaje;
        } elseif ($tipoEmergencia === "grave") {
            // Conexión a la base de datos y envío de correo
            // (Este bloque sigue igual)
        } else {
            throw new Exception("Tipo de emergencia no válido");
        }

        // Enviar el correo
        $mail->send();
        echo json_encode(["success" => "Correo enviado correctamente"]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Error al enviar el correo: " . $mail->ErrorInfo]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
