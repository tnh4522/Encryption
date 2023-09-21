<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h1>Send Encrypted Email</h1>
<form action="sendMail.php" method="post">
    <label for="recipient">Recipient Email:</label>
    <input type="email" name="recipient" required><br><br>
    <label for="message">Message:</label>
    <textarea name="message" rows="4" cols="50" required></textarea><br><br>
    <input type="submit" value="Send Email">
</form>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use phpseclib\Crypt\RSA;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'vendor/autoload.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipientEmail = $_POST['recipient'];
    $message = $_POST['message'];
    $publicKey = file_get_contents('public_key.pem');
    $rsa = new RSA();
    $rsa->loadKey($publicKey);
    $encryptedMessage = $rsa->encrypt($message);
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tnh2045@gmail.com';
        $mail->Password = 'hexp btpb zjyq jmli';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('tnh2045@gmail.com', 'Huy Tran Ngoc');
        $mail->addAddress($recipientEmail);
//    $mail->addAddress('ellen@example.com');
//    $mail->addReplyTo('info@example.com', 'Information');
//    $mail->addCC('cc@example.com');
//    $mail->addBCC('bcc@example.com');

//    $mail->addAttachment('/var/tmp/file.tar.gz');
//    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Encrypted Email';
        $mail->Body = $encryptedMessage;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
</body>
</html>