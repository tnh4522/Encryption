<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Receive Encrypted Email</title>
</head>
<body>
<?php
session_start();
use phpseclib\Crypt\RSA;
require 'vendor/autoload.php';
$notification = '';
$mail = $subject = '';
$encryptedMessage = '';
$decryptedMessage = '';
if (isset($_SESSION['recipient'])) {
    $mail = $_SESSION['recipient'];
    $subject = $_SESSION['subject'];
}
if (isset($_SESSION['encrypted_message'])) {
    $encryptedMessage = base64_decode($_SESSION['encrypted_message']);
    $privateKey = file_get_contents('private_key.pem');
    $rsa = new RSA();
    $rsa->loadKey($privateKey);
    $decryptedMessage = $rsa->decrypt($encryptedMessage);
    $notification = 'Email Decrypted';
    unset($_SESSION['encrypted_message'], $_SESSION['recipient'], $_SESSION['subject']);
} else {
    $notification = 'There is no email content to decode!';
}
?>
<div class="container">
    <h1>Receive Encrypted Email</h1>
    <form action="" method="post">
        <label for="recipient">From</label>
        <input type="text" id="recipient" name="recipient" placeholder="Email.." value="<?php echo $mail; ?>">
        <label for="subject">Subject</label>
        <input type="text" id="subject" name="subject" placeholder="Write subject.." value="<?php echo $subject; ?>"
        <label for="message">Encryption Mail</label>
        <textarea id="message" name="message" placeholder="Write something.." style="height:200px"><?php echo $encryptedMessage; ?></textarea>
        <label for="decryption">Decryption Mail</label>
        <textarea id="decryption" name="decryption" style="height:200px" readonly><?php echo $decryptedMessage; ?></textarea>
        <input type="submit" name="submit" value="Send Email">
        <p class="notification"><?php echo $notification ?></p>
    </form>
</div>
</body>
</html>
