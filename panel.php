<?php
// Oturumu başlatıyoruz
session_start();

// Eğer giriş yapılmadan doğrudan bu sayfaya gelinmişse index.php'ye geri yönlendir
if (!isset($_SESSION['giris_yapildi'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Hastane Bilgi İşlem - Yönetim Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="alert alert-success shadow">
            <h2>Hoş Geldiniz, Yetkili!</h2>
            <p>Hastane Arıza ve Zimmet Takip Sistemi yönetim paneline başarıyla giriş yaptınız.</p>
            <hr>
            <a href="cikis.php" class="btn btn-danger">Güvenli Çıkış Yap</a>
        </div>
    </div>
</body>
</html>