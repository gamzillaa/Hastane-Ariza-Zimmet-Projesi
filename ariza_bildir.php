<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['giris_yapildi'])) {
    header("Location: index.php");
    exit();
}
include("Baglanti.php");

$mesaj = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cihaz_id = intval($_POST['cihaz_id']);
    $bildiren_personel = mysqli_real_escape_string($conn, $_POST['bildiren_personel']);
    $ariza_aciklamasi = mysqli_real_escape_string($conn, $_POST['ariza_aciklamasi']);
    $oncelik = mysqli_real_escape_string($conn, $_POST['oncelik']);

    $sql = "INSERT INTO ariza_talepleri (cihaz_id, bildiren_personel, ariza_aciklamasi, oncelik, durum) VALUES ($cihaz_id, '$bildiren_personel', '$ariza_aciklamasi', '$oncelik', 'YENI')";

    if (mysqli_query($conn, $sql)) {
        mysqli_query($conn, "UPDATE cihazlar SET durum = 'ARIZALI' WHERE id = $cihaz_id");
        $mesaj = "<div class='alert alert-success'>Arıza talebi başarıyla oluşturuldu!</div>";
    } else {
        $mesaj = "<div class='alert alert-danger'>Hata: " . mysqli_error($conn) . "</div>";
    }
}

$cihazlar_sorgu = "SELECT id, demirbas_no, marka, model, bulundugu_birim FROM cihazlar ORDER BY demirbas_no ASC";
$cihazlar_sonuc = mysqli_query($conn, $cihazlar_sorgu);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Arıza Bildirim Modülü - Hastane Bilgi İşlem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="card shadow p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="text-danger">Arıza Bildirim ve Talep Modülü</h3>
                <a href="ariza_yonetim.php" class="btn btn-primary">Talep Yönetim Paneli</a>
            </div>

            <?php echo $mesaj; ?>

            <form method="POST" class="row g-3 bg-white p-3 border rounded mb-5">
                <div class="col-md-6">
                    <label class="form-label">Arızalı Cihaz Seçin</label>
                    <select name="cihaz_id" class="form-select" required>
                        <option value="">Cihaz Seçiniz...</option>
                        <?php
                        while ($c = mysqli_fetch_assoc($cihazlar_sonuc)) {
                            echo "<option value='" . $c['id'] . "'>" . htmlspecialchars($c['demirbas_no']) . " - " . htmlspecialchars($c['marka']) . " " . htmlspecialchars($c['model']) . " (" . htmlspecialchars($c['bulundugu_birim']) . ")</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Bildiren Personel</label>
                    <input type="text" name="bildiren_personel" class="form-control" placeholder="Ad Soyad" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Öncelik Durumu</label>
                    <select name="oncelik" class="form-select" required>
                        <option value="DUSUK">DÜŞÜK</option>
                        <option value="NORMAL" selected>NORMAL</option>
                        <option value="YUKSEK">YÜKSEK</option>
                        <option value="KRITIK">KRİTİK</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Arıza Açıklaması / Belirtiler</label>
                    <textarea name="ariza_aciklamasi" class="form-control" rows="3" placeholder="Arızanın detaylarını yazınız..." required></textarea>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-danger w-100">Arıza Talebi Oluştur</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>