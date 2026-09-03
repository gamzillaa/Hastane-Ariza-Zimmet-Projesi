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
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Talep Listesi - Hastane Bilgi İşlem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="card shadow p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="text-primary">4.10 Talep Listesi ve Takibi</h3>
                <div>
                    <a href="ariza_bildir.php" class="btn btn-success">Yeni Arıza Bildir</a>
                    <a href="panel.php" class="btn btn-secondary">Yönetim Paneli</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Talep No</th>
                            <th>Talep Tarihi</th>
                            <th>Talebi Açan Kişi</th>
                            <th>Birim</th>
                            <th>Cihaz</th>
                            <th>Öncelik</th>
                            <th>Durum</th>
                            <th>Atanan Personel</th>
                            <th>İşlem / Detay</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sorgu = "SELECT a.*, c.demirbas_no, c.marka, c.model, c.bulundugu_birim FROM ariza_talepleri a JOIN cihazlar c ON a.cihaz_id = c.id ORDER BY a.id DESC";
                        $sonuc = mysqli_query($conn, $sorgu);

                        if (mysqli_num_rows($sonuc) > 0) {
                            while ($row = mysqli_fetch_assoc($sonuc)) {
                                echo "<tr>";
                                echo "<td><strong>#" . $row['id'] . "</strong></td>";
                                echo "<td>" . $row['tarih'] . "</td>";
                                echo "<td>" . htmlspecialchars($row['bildiren_personel']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['bulundugu_birim']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['demirbas_no']) . " - " . htmlspecialchars($row['marka']) . " " . htmlspecialchars($row['model']) . "</td>";
                                
                                // Öncelik
                                $oncelik_badge = "bg-secondary";
                                if ($row['oncelik'] == 'NORMAL') $oncelik_badge = "bg-info text-dark";
                                if ($row['oncelik'] == 'YUKSEK') $oncelik_badge = "bg-warning text-dark";
                                if ($row['oncelik'] == 'KRITIK') $oncelik_badge = "bg-danger";
                                echo "<td><span class='badge " . $oncelik_badge . "'>" . $row['oncelik'] . "</span></td>";

                                // Durum
                                echo "<td><span class='badge bg-primary'>" . $row['durum'] . "</span></td>";
                                
                                echo "<td>" . (!empty($row['atanan_personel'] ?? '') ? htmlspecialchars($row['atanan_personel']) : "<span class='text-muted'>Atanmadı</span>") . "</td>";
                                
                                // Detay sayfasına geçiş butonu (4.11 için)
                                echo "<td><a href='ariza_detay.php?id=" . $row['id'] . "' class='btn btn-sm btn-outline-primary'>Detay / İncele</a></td>";
                                echo "جر";
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center'>Kayıtlı talep bulunmuyor.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>