<?php
// Hataları ekranda açıkça görebilmek için hata ayıklamayı açıyoruz
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
    $demirbas_no = mysqli_real_escape_string($conn, $_POST['demirbas_no']);
    $seri_no = mysqli_real_escape_string($conn, $_POST['seri_no']);
    $cihaz_turu = mysqli_real_escape_string($conn, $_POST['cihaz_turu']);
    $marka = mysqli_real_escape_string($conn, $_POST['marka']);
    $model = mysqli_real_escape_string($conn, $_POST['model']);
    $ip_adresi = mysqli_real_escape_string($conn, $_POST['ip_adresi']);
    $mac_adresi = mysqli_real_escape_string($conn, $_POST['mac_adresi']);
    $satin_alma_tarihi = mysqli_real_escape_string($conn, $_POST['satin_alma_tarihi']);
    $garanti_bitis_tarihi = mysqli_real_escape_string($conn, $_POST['garanti_bitis_tarihi']);
    $durum = mysqli_real_escape_string($conn, $_POST['durum']);
    $bulundugu_birim = mysqli_real_escape_string($conn, $_POST['bulundugu_birim']);
    $zimmetli_personel = mysqli_real_escape_string($conn, $_POST['zimmetli_personel']);
    $aciklama = mysqli_real_escape_string($conn, $_POST['aciklama']);

    $sql = "INSERT INTO cihazlar (demirbas_no, seri_no, cihaz_turu, marka, model, ip_adresi, mac_adresi, satin_alma_tarihi, garanti_bitis_tarihi, durum, bulundugu_birim, zimmetli_personel, aciklama) 
            VALUES ('$demirbas_no', '$seri_no', '$cihaz_turu', '$marka', '$model', '$ip_adresi', '$mac_adresi', '$satin_alma_tarihi', '$garanti_bitis_tarihi', '$durum', '$bulundugu_birim', '$zimmetli_personel', '$aciklama')";

    if (mysqli_query($conn, $sql)) {
        $mesaj = "<div class='alert alert-success'>Cihaz başarıyla eklendi!</div>";
    } else {
        $mesaj = "<div class='alert alert-danger'>Hata: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Cihaz Ekle - Hastane Bilgi İşlem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="card shadow p-4">
            <h3 class="mb-4 text-primary">Yeni Cihaz Kaydı Ekle</h3>
            <?php echo $mesaj; ?>
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Demirbaş Numarası</label>
                        <input type="text" name="demirbas_no" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Seri Numarası</label>
                        <input type="text" name="seri_no" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Cihaz Türü</label>
                        <input type="text" name="cihaz_turu" class="form-control" placeholder="Örn: Bilgisayar, Yazıcı">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Marka</label>
                        <input type="text" name="marka" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Model</label>
                        <input type="text" name="model" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">IP Adresi</label>
                        <input type="text" name="ip_adresi" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">MAC Adresi</label>
                        <input type="text" name="mac_adresi" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Satın Alma Tarihi</label>
                        <input type="date" name="satin_alma_tarihi" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Garanti Bitiş Tarihi</label>
                        <input type="date" name="garanti_bitis_tarihi" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Cihaz Durumu</label>
                        <select name="durum" class="form-control">
                            <option value="AKTIF">AKTİF</option>
                            <option value="ARIZALI">ARIZALI</option>
                            <option value="DEPODA">DEPODA</option>
                            <option value="SERVISTE">SERVİSTE</option>
                            <option value="HURDA">HURDA</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Bulunduğu Birim</label>
                        <input type="text" name="bulundugu_birim" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Zimmetli Personel</label>
                        <input type="text" name="zimmetli_personel" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Açıklama</label>
                    <textarea name="aciklama" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Cihazı Kaydet</button>
                <a href="panel.php" class="btn btn-secondary">Panele Dön</a>
            </form>
        </div>
    </div>
</body>
</html>