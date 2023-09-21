<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Encryption Send Email</title>
</head>
<body>
<?php
session_start();
use phpseclib\Crypt\RSA;
require 'vendor/autoload.php';
$notification = '';
$encryptedMessage = '';
if(isset($_POST['submit'])) {
    $_SESSION['recipient'] = $_POST['recipient'];
    $_SESSION['subject'] = $_POST['subject'];
    $message = $_POST['message'];
    $publicKey = file_get_contents('public_key.pem');
    $rsa = new RSA();
    $rsa->loadKey($publicKey);
    $encryptedMessage = $rsa->encrypt($message);
    $_SESSION['encrypted_message'] = base64_encode($encryptedMessage);
    $notification = 'Email Encrypted And Sent';
}
?>
<div class="container">
    <h1>Send Encrypted Email</h1>
    <form action="" method="post">
        <label for="recipient">To</label>
        <input type="text" id="recipient" name="recipient" placeholder="Email.." >
        <label for="subject">Subject</label>
        <input type="text" id="subject" name="subject" placeholder="Write subject..">
        <label for="message">Mail</label>
        <textarea id="message" name="message" placeholder="Write something.." style="height:200px"></textarea>
        <label for="encryption">Mail Encryption</label>
        <textarea id="encryption" name="encryption" style="height:200px" readonly><?php echo $encryptedMessage; ?></textarea>
        <input type="submit" name="submit" value="Send Email">
        <p class="notification"><?php echo $notification ?></p>
    </form>
</div>
</body>
</html>