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

// Filtreleme parametrelerini alıyoruz
$arama = isset($_GET['arama']) ? mysqli_real_escape_string($conn, $_GET['arama']) : '';
$durum_filtre = isset($_GET['durum']) ? mysqli_real_escape_string($conn, $_GET['durum']) : '';

// Sorguyu filtreye göre dinamik oluşturuyoruz
$sorgu = "SELECT * FROM cihazlar WHERE 1=1";

if (!empty($arama)) {
    $sorgu .= " AND (demirbas_no LIKE '%$arama%' OR seri_no LIKE '%$arama%' OR marka LIKE '%$arama%' OR bulundugu_birim LIKE '%$arama%')";
}

if (!empty($durum_filtre)) {
    $sorgu .= " AND durum = '$durum_filtre'";
}

$sorgu .= " ORDER BY id DESC";
$sonuc = mysqli_query($conn, $sorgu);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Cihaz Listesi ve Filtreleme - Hastane Bilgi İşlem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="card shadow p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="text-primary">Kurum Cihaz Listesi & Filtreleme</h3>
                <div>
                    <a href="cihaz_ekle.php" class="btn btn-success">+ Yeni Cihaz Ekle</a>
                    <a href="panel.php" class="btn btn-secondary">Panele Dön</a>
                </div>
            </div>

            <!-- 4.6 Cihaz Filtreleme Formu -->
            <form method="GET" class="row g-3 mb-4 bg-white p-3 border rounded">
                <div class="col-md-6">
                    <input type="text" name="arama" class="form-control" placeholder="Demirbaş No, Seri No, Marka veya Birim Ara..." value="<?php echo htmlspecialchars($arama); ?>">
                </div>
                <div class="col-md-4">
                    <select name="durum" class="form-select">
                        <option value="">Tüm Durumlar</option>
                        <option value="AKTIF" <?php if($durum_filtre == 'AKTIF') echo 'selected'; ?>>AKTİF</option>
                        <option value="ARIZALI" <?php if($durum_filtre == 'ARIZALI') echo 'selected'; ?>>ARIZALI</option>
                        <option value="DEPODA" <?php if($durum_filtre == 'DEPODA') echo 'selected'; ?>>DEPODA</option>
                        <option value="SERVISTE" <?php if($durum_filtre == 'SERVISTE') echo 'selected'; ?>>SERVİSTE</option>
                        <option value="HURDA" <?php if($durum_filtre == 'HURDA') echo 'selected'; ?>>HURDA</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrele</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Demirbaş No</th>
                            <th>Cihaz Türü</th>
                            <th>Marka / Model</th>
                            <th>IP Adresi</th>
                            <th>Birim</th>
                            <th>Zimmetli Personel</th>
                            <th>Durum</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($sonuc) > 0) {
                            while ($row = mysqli_fetch_assoc($sonuc)) {
                                echo "<tr>";
                               echo "<td><a href='cihaz_detay.php?id=" . $row['id'] . "' class='text-decoration-none fw-bold'>" . htmlspecialchars($row['demirbas_no']) . "</a></td>";
                                echo "<td>" . htmlspecialchars($row['cihaz_turu']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['marka']) . " " . htmlspecialchars($row['model']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['ip_adresi']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['bulundugu_birim']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['zimmetli_personel']) . "</td>";
                                echo "<td><span class='badge bg-info'>" . htmlspecialchars($row['durum']) . "</span></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>Aranıza uygun cihaz bulunamadı.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>