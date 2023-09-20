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
<?php
session_start();
use phpseclib\Crypt\RSA;
require 'vendor/autoload.php';

$message = "";
if(isset($_POST['submit'])) {
    $recipientEmail = $_POST['recipient'];
    $message = $_POST['message'];
    if($recipientEmail == "" || $message == "") {
        $message = "Email hoặc Message không được để trống!";
    } else {
        try {
            $publicKey = file_get_contents('public_key.pem');
            $rsa = new RSA();
            $rsa->loadKey($publicKey);
            $encryptedMessage = $rsa->encrypt($message);
            $_SESSION['encrypted_message'] = base64_encode($encryptedMessage);
            echo "Nội dung tin nhắn trước khi mã hóa: " . $message . "<br>";
            echo "Nội dung tin nhắn sau khi mã hóa: " . $encryptedMessage . "<br>";
            exit;
        }
        catch (Exception $e) {
            $message = $e->getMessage();
        }
    }
}
?>
<h1>Send Encrypted Email</h1>
<form action="sendMail.php" method="post">
    <label for="recipient">Recipient Email:</label>
    <input type="email" name="recipient" required><br><br>
    <label for="message">Message:</label>
    <textarea name="message" rows="4" cols="50" required></textarea><br><br>
    <p style="color: red"><?php echo $message; ?></p>
    <input type="submit" name="submit" value="Send Email">
</form>
</body>
</html>
