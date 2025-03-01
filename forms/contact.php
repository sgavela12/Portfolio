<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Cargar las variables de entorno desde el archivo .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USERNAME'];  
        $mail->Password = $_ENV['MAIL_PASSWORD'];  
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configurar la codificación para soportar acentos
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        // Enviar correo a mí mismo
        $mail->setFrom($_ENV['MAIL_USERNAME'], 'CONTACTO-PORTFOLIO');
        $mail->addAddress($_ENV['MAIL_USERNAME']); 

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = "<h3>Nuevo mensaje de contacto</h3>
                       <p><strong>Nombre:</strong> $name</p>
                       <p><strong>Email:</strong> $email</p>
                       <p><strong>Mensaje:</strong> $message</p>";

        $mail->send();

        

        // Enviar confirmación al usuario
        $mail->clearAddresses();
        $mail->addAddress($email);
        $mail->Subject = "Mensaje recibido - Portfolio";
        $mail->Body = "<h3>¡Hola $name!</h3>
                       <p>Gracias por ponerte en contacto conmigo a través de mi portfolio.</p>
                       <p>He recibido tu mensaje y te responderé lo antes posible.</p>
                       <hr>
                       <p><strong>Tu mensaje:</strong></p>
                       <p><strong>Asunto:</strong> $subject</p>
                       <p><strong>Mensaje:</strong> $message</p>
                       <br>
                       <p>Nos vemos pronto,</p>
                       <p><strong>Sergio</strong></p>";

        $mail->send();

        echo "Tu mensaje ha sido enviado correctamente.";
    } catch (Exception $e) {
        echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
    }
}
?>
