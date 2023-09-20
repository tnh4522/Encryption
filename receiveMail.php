<?php
session_start();
use phpseclib\Crypt\RSA;
require 'vendor/autoload.php';
if (isset($_SESSION['encrypted_message'])) {
    $encryptedMessage = base64_decode($_SESSION['encrypted_message']);
    $privateKey = file_get_contents('private_key.pem');
    $rsa = new RSA();
    $rsa->loadKey($privateKey);
    $decryptedContent = $rsa->decrypt($encryptedMessage);
    echo 'Nội dung email đã được giải mã: ' . $decryptedContent . '<br>';
    unset($_SESSION['encrypted_message']);
} else {
    echo 'Không có nội dung email để hiển thị.';
}
?>
