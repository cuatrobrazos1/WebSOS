<?php
// Importar las clases necesarias de PHPMailer al principio del archivo
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if (!isset($_SESSION['user_id'])) {
    throw new Exception("El usuario no ha iniciado sesión.");
}

$usuarioId = $_SESSION['user_id']; // Usar 'user_id' de la sesión

// Configuración de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vitalsos";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipoEmergencia = $_POST['tipoEmergencia'] ?? null;

    if (empty($tipoEmergencia)) {
        http_response_code(400);
        echo json_encode(["error" => "Faltan datos requeridos"]);
        exit;
    }

    try {
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

            // Usar PHPMailer para enviar el correo
            require 'vendor/autoload.php'; // Asegúrate de incluir el autoload de Composer

            $mail = new PHPMailer(true);

            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // Cambia por el servidor SMTP adecuado
                $mail->SMTPAuth = true;
                $mail->Username = 'vitalsosgrupo2@gmail.com';  // Tu correo
                $mail->Password = 'Vital1234';        // Tu contraseña de correo
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Remitente y destinatario
                $mail->setFrom('tucorreo@gmail.com', 'Sistema de Emergencias');
                $mail->addAddress($email);

                // Contenido del correo
                $mail->isHTML(true);
                $mail->Subject = 'Emergencia Leve';
                $mail->Body    = $mensaje;

                $mail->send();

            } catch (Exception $e) {
                throw new Exception("Error al enviar el correo: {$mail->ErrorInfo}");
            }

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

            // Usar PHPMailer para enviar el correo
            require 'vendor/autoload.php'; // Asegúrate de incluir el autoload de Composer

            $mail = new PHPMailer(true);

            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // Cambia por el servidor SMTP adecuado
                $mail->SMTPAuth = true;
                $mail->Username = 'vitalsosgrupo2@gmail.com';  // Tu correo
                $mail->Password = 'Vital1234';        // Tu contraseña de correo
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Remitente y destinatario
                $mail->setFrom('tucorreo@gmail.com', 'Sistema de Emergencias');
                $mail->addAddress('destinatario@example.com');  // Cambia el destinatario

                // Contenido del correo
                $mail->isHTML(true);
                $mail->Subject = 'Emergencia Grave';
                $mail->Body    = $mensaje;

                $mail->send();

            } catch (Exception $e) {
                throw new Exception("Error al enviar el correo: {$mail->ErrorInfo}");
            }

            $stmt->close();
            $conn->close();

        } else {
            throw new Exception("Tipo de emergencia no válido");
        }

        echo json_encode(["success" => "Correo enviado y datos almacenados correctamente"]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Error al procesar la solicitud: " . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>
