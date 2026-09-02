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

// URL'den gelen ID değerini alıyoruz
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Cihaz bilgilerini veritabanından çekiyoruz
$sorgu = "SELECT * FROM cihazlar WHERE id = $id";
$sonuc = mysqli_query($conn, $sorgu);

if (mysqli_num_rows($sonuc) == 1) {
    $cihaz = mysqli_fetch_assoc($sonuc);
} else {
    echo "<script>alert('Cihaz bulunamadı!'); window.location.href='cihazlar.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Cihaz Detayı - Hastane Bilgi İşlem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="card shadow p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="text-primary">Cihaz Detay Kartı</h3>
                <div>
                    <a href="cihazlar.php" class="btn btn-secondary">← Cihaz Listesine Dön</a>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 border bg-white rounded">
                        <strong>Demirbaş Numarası:</strong> <span class="text-muted"><?php echo htmlspecialchars($cihaz['demirbas_no']); ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border bg-white rounded">
                        <strong>Seri Numarası:</strong> <span class="text-muted"><?php echo htmlspecialchars($cihaz['seri_no']); ?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border bg-white rounded">
                        <strong>Cihaz Türü:</strong> <span class="text-muted"><?php echo htmlspecialchars($cihaz['cihaz_turu']); ?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border bg-white rounded">
                        <strong>Marka:</strong> <span class="text-muted"><?php echo htmlspecialchars($cihaz['marka']); ?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border bg-white rounded">
                        <strong>Model:</strong> <span class="text-muted"><?php echo htmlspecialchars($cihaz['model']); ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border bg-white rounded">
                        <strong>IP Adresi:</strong> <span class="text-muted"><?php echo htmlspecialchars($cihaz['ip_adresi']); ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border bg-white rounded">
                        <strong>MAC Adresi:</strong> <span class="text-muted"><?php echo htmlspecialchars($cihaz['mac_adresi']); ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border bg-white rounded">
                        <strong>Satın Alma Tarihi:</strong> <span class="text-muted"><?php echo htmlspecialchars($cihaz['satin_alma_tarihi']); ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border bg-white rounded">
                        <strong>Garanti Bitiş Tarihi:</strong> <span class="text-muted"><?php echo htmlspecialchars($cihaz['garanti_bitis_tarihi']); ?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border bg-white rounded">
                        <strong>Cihaz Durumu:</strong> <span class="badge bg-info"><?php echo htmlspecialchars($cihaz['durum']); ?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border bg-white rounded">
                        <strong>Bulunduğu Birim:</strong> <span class="text-muted"><?php echo htmlspecialchars($cihaz['bulundugu_birim']); ?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border bg-white rounded">
                        <strong>Zimmetli Personel:</strong> <span class="text-muted"><?php echo htmlspecialchars($cihaz['zimmetli_personel']); ?></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="p-3 border bg-white rounded">
                        <strong>Açıklama:</strong> <p class="text-muted mt-1 mb-0"><?php echo nl2br(htmlspecialchars($cihaz['aciklama'])); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>