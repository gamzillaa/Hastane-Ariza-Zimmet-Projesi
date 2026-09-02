<?php
// Oturumu başlatıyoruz
session_start();

// Tüm oturum değişkenlerini siliyoruz
$_SESSION = array();

// Oturumu tamamen sonlandırıyoruz
session_destroy();

// Kullanıcıyı tekrar giriş ekranına yönlendiriyoruz
header("Location: index.php");
exit();
?>