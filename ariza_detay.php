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
$ariza_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Teknik personel işlem güncellemesi gönderdiyse
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $yeni_durum = mysqli_real_escape_string($conn, $_POST['yeni_durum']);
    $islem_yapan = mysqli_real_escape_string($conn, $_POST['islem_yapan']);
    $aciklama = mysqli_real_escape_string($conn, $_POST['islem_aciklamasi']);

    // Eski durumu öğrenelim
    $eskiSorgu = "SELECT durum FROM ariza_talepleri WHERE id = $ariza_id";
    $eskiRes = mysqli_query($conn, $eskiSorgu);
    $eskiRow = mysqli_fetch_assoc($eskiRes);
    $eski_durum = $eskiRow['durum'];

    // Ana talebi güncelle
    $guncelleSQL = "UPDATE ariza_talepleri SET durum = '$yeni_durum' WHERE id = $ariza_id";
    if (mysqli_query($conn, $guncelleSQL)) {
        // İşlem geçmişine kaydet (4.12 İşlem Geçmişi)
        $gecmisSQL = "INSERT INTO ariza_islem_gecmisi (ariza_id, islem_yapan_personel, eski_durum, yeni_durum, islem_aciklamasi) 
                      VALUES ($ariza_id, '$islem_yapan', '$eski_durum', '$yeni_durum', '$aciklama')";
        mysqli_query($conn, $gecmisSQL);

        $mesaj = "<div class='alert alert-success'>İşlem başarıyla kaydedildi ve geçmişe eklendi!</div>";
    } else {
        $mesaj = "<div class='alert alert-danger'>Hata: " . mysqli_error($conn) . "</div>";
    }
}

// Talep detaylarını çekelim
$sorgu = "SELECT a.*, c.demirbas_no, c.marka, c.model, c.seri_no, c.bulundugu_birim FROM ariza_talepleri a JOIN cihazlar c ON a.cihaz_id = c.id WHERE a.id = $ariza_id";
$sonuc = mysqli_query($conn, $sorgu);
$talep = mysqli_fetch_assoc($sonuc);

if (!$talep) {
    die("<div class='container mt-5'><div class='alert alert-danger'>Talep bulunamadı! <a href='talep_listesi.php'>Listeye dön</a></div></div>");
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Talep Detayı - #<?php echo $talep['id']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="card shadow p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="text-primary">Arıza Talep Detayı (#<?php echo $talep['id']; ?>)</h3>
                <a href="talep_listesi.php" class="btn btn-secondary">Talep Listesine Dön</a>
            </div>

            <?php echo $mesaj; ?>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Talep Numarası:</strong> #<?php echo $talep['id']; ?></li>
                        <li class="list-group-item"><strong>Talep Tarihi:</strong> <?php echo $talep['tarih']; ?></li>
                        <li class="list-group-item"><strong>Talebi Açan Kişi:</strong> <?php echo htmlspecialchars($talep['bildiren_personel']); ?></li>
                        <li class="list-group-item"><strong>Birim Bilgisi:</strong> <?php echo htmlspecialchars($talep['bulundugu_birim']); ?></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Cihaz Bilgisi:</strong> <?php echo htmlspecialchars($talep['demirbas_no']) . " - " . htmlspecialchars($talep['marka']) . " " . htmlspecialchars($talep['model']); ?></li>
                        <li class="list-group-item"><strong>Öncelik:</strong> <span class="badge bg-warning text-dark"><?php echo $talep['oncelik']; ?></span></li>
                        <li class="list-group-item"><strong>Mevcut Durum:</strong> <span class="badge bg-success"><?php echo $talep['durum']; ?></span></li>
                        <li class="list-group-item"><strong>Arıza Açıklaması:</strong> <?php echo htmlspecialchars($talep['ariza_aciklamasi']); ?></li>
                    </ul>
                </div>
            </div>

            <!-- Teknik Personel İşlem Formu -->
            <div class="card p-3 bg-white border mb-4">
                <h5 class="text-secondary mb-3">Teknik Personel İşlem ve Durum Güncelleme</h5>
                <form method="POST" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">İşlemi Yapan Personel</label>
                        <input type="text" name="islem_yapan" class="form-control" placeholder="Ad Soyad" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Yeni Durum Seç</label>
                        <select name="yeni_durum" class="form-select" required>
                            <option value="YENI" <?php echo ($talep['durum'] == 'YENI') ? 'selected' : ''; ?>>YENİ</option>
                            <option value="ATANDI" <?php echo ($talep['durum'] == 'ATANDI') ? 'selected' : ''; ?>>ATANDI</option>
                            <option value="ISLEMDE" <?php echo ($talep['durum'] == 'ISLEMDE') ? 'selected' : ''; ?>>İŞLEMDE</option>
                            <option value="BEKLEMEDE" <?php echo ($talep['durum'] == 'BEKLEMEDE') ? 'selected' : ''; ?>>BEKLEMEDE</option>
                            <option value="COZULDU" <?php echo ($talep['durum'] == 'COZULDU') ? 'selected' : ''; ?>>ÇÖZÜLDÜ</option>
                            <option value="IPTAL" <?php echo ($talep['durum'] == 'IPTAL') ? 'selected' : ''; ?>>İPTAL</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">İşlemi Kaydet</button>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Yapılan İşlem / Çözüm Açıklaması</label>
                        <textarea name="islem_aciklamasi" class="form-control" rows="2" placeholder="Cihaza yapılan müdahaleyi yazınız..." required></textarea>
                    </div>
                </form>
            </div>

            <!-- 4.12 Arıza İşlem Geçmişi Tablosu -->
            <h5 class="text-secondary mb-3">4.12 Arıza İşlem Geçmişi (Loglar)</h5>
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>İşlem Tarihi</th>
                            <th>İşlem Yapan Personel</th>
                            <th>Eski Durum</th>
                            <th>Yeni Durum</th>
                            <th>Yapılan İşlem Açıklaması</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $gecmisSorgu = "SELECT * FROM ariza_islem_gecmisi WHERE ariza_id = $ariza_id ORDER BY id DESC";
                        $gecmisSonuc = mysqli_query($conn, $gecmisSorgu);

                        if (mysqli_num_rows($gecmisSonuc) > 0) {
                            while ($g = mysqli_fetch_assoc($gecmisSonuc)) {
                                echo "<tr>";
                                echo "<td>" . $g['tarih'] . "</td>";
                                echo "<td>" . htmlspecialchars($g['islem_yapan_personel']) . "</td>";
                                echo "<td><span class='badge bg-secondary'>" . $g['eski_durum'] . "</span></td>";
                                echo "<td><span class='badge bg-primary'>" . $g['yeni_durum'] . "</span></td>";
                                echo "<td>" . htmlspecialchars($g['islem_aciklamasi']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>Bu talep için henüz işlem geçmişi bulunmuyor.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</body>
</html>