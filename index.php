<?php
// Veritabanı bağlantımızı dahil ediyoruz
include("Baglanti.php");

$hata = "";

// Form gönderildiyse kontrol et
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kadi = mysqli_real_escape_string($conn, $_POST['kullanici_adi']);
    $sifre = mysqli_real_escape_string($conn, $_POST['sifre']);

    // Veritabanında kullanıcıyı sorguluyoruz
    $sorgu = "SELECT * FROM personel WHERE kullanici_adi = '$kadi' AND sifre = '$sifre'";
    $sonuc = mysqli_query($conn, $sorgu);


        if (mysqli_num_rows($sonuc) == 1) {
        session_start();
        $_SESSION['giris_yapildi'] = true;
        header("Location: panel.php");
        exit();
    }
        // İleride buraya panel sayfasına yönlendirme (header) ekleyeceğiz
    } else {
        $hata = "<div class='alert alert-danger text-center'>Kullanıcı adı veya şifre hatalı!</div>";
    }

?><?php
// Veritabanı bağlantı dosyamızı dahil ediyoruz
include("Baglanti.php");

$mesaj = "";

// Form gönderildi mi kontrol edelim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kadi = $_POST['kullanici_adi'];
    $sifre = $_POST['sifre'];

    // Basit bir güvenlik önlemi (İleride hash kontrolü ile güncelleyebiliriz)
    if (!empty($kadi) && !empty($sifre)) {
        // Örnek sorgu yapısı (Tablo adına göre veritabanına uyarlayacağız)
        $sorgu = "SELECT * FROM personel WHERE kullanici_adi = '$kadi' AND sifre = '$sifre'";
        // Not: İlerleyen günlerde bunu güvenli olması için Prepared Statements (PDO) ile güçlendireceğiz.
        
        $mesaj = "<div class='alert alert-info'>Giriş bilgileri kontrol ediliyor...</div>";
    } else {
        $mesaj = "<div class='alert alert-danger'>Lütfen tüm alanları doldurun!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hastane Arıza ve Zimmet Takip Sistemi - Giriş</title>
    <!-- Bootstrap CSS CDN (Modern ve şık bir görünüm için) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            background: #ffffff;
        }
        .login-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-header h2 {
            color: #0d6efd;
            font-weight: bold;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <h2>🏥 Hastane Bilgi İşlem</h2>
            <p class="text-muted">Arıza ve Zimmet Takip Sistemi</p>
        </div>

        <?php echo $mesaj; ?>

        <form action="index.php" method="POST">
            <div class="mb-3">
                <label for="kullanici_adi" class="form-label">Kullanıcı Adı / Sicil No</label>
                <input type="text" class="form-control" id="kullanici_adi" name="kullanici_adi" required>
            </div>
            <div class="mb-3">
                <label for="sifre" class="form-label">Şifre</label>
                <input type="password" class="form-control" id="sifre" name="sifre" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Sisteme Giriş Yap</button>
        </form>
    </div>

</body>
</html>