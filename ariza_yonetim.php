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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ariza_id']) && isset($_POST['yeni_durum'])) {
    $ariza_id = intval($_POST['ariza_id']);
    $yeni_durum = mysqli_real_escape_string($conn, $_POST['yeni_durum']);

    $GuncelleSQL = "UPDATE ariza_talepleri SET durum = '$yeni_durum' WHERE id = $ariza_id";
    if (mysqli_query($conn, $GuncelleSQL)) {
        if ($yeni_durum == 'COZULDU') {
            $cihazSorgu = "SELECT cihaz_id FROM ariza_talepleri WHERE id = $ariza_id";
            $res = mysqli_query($conn, $cihazSorgu);
            if ($row = mysqli_fetch_assoc($res)) {
                $c_id = $row['cihaz_id'];
                mysqli_query($conn, "UPDATE cihazlar SET durum = 'AKTIF' WHERE id = $c_id");
            }
        }
        $mesaj = "<div class='alert alert-success'>Talep durumu başarıyla güncellendi!</div>";
    } else {
        $mesaj = "<div class='alert alert-danger'>Hata: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Arıza Talep Yönetimi - Hastane Bilgi İşlem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="card shadow p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="text-primary">Arıza Talep ve Durum Yönetimi</h3>
                <div>
                    <a href="ariza_bildir.php" class="btn btn-success">Yeni Arıza Bildir</a>
                    <a href="panel.php" class="btn btn-secondary">Yönetim Paneli</a>
                </div>
            </div>

            <?php echo $mesaj; ?>

            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Demirbaş / Cihaz</th>
                            <th>Bildiren</th>
                            <th>Açıklama</th>
                            <th>Öncelik</th>
                            <th>Durum</th>
                            <th>İşlem / Durum Güncelle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sorgu = "SELECT a.*, c.demirbas_no, c.marka, c.model, c.bulundugu_birim FROM ariza_talepleri a JOIN cihazlar c ON a.cihaz_id = c.id ORDER BY a.id DESC";
                        $sonuc = mysqli_query($conn, $sorgu);

                        if (mysqli_num_rows($sonuc) > 0) {
                            while ($row = mysqli_fetch_assoc($sonuc)) {
                                echo "<tr>";
                                echo "<td>#" . $row['id'] . "</td>";
                                echo "<td><strong>" . htmlspecialchars($row['demirbas_no']) . "</strong><br>" . htmlspecialchars($row['marka']) . " " . htmlspecialchars($row['model']) . "<br><small class='text-muted'>" . htmlspecialchars($row['bulundugu_birim']) . "</small></td>";
                                echo "<td>" . htmlspecialchars($row['bildiren_personel']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['ariza_aciklamasi']) . "</td>";
                                
                                // Öncelik rozeti
                                $oncelik_badge = "bg-secondary";
                                if ($row['oncelik'] == 'NORMAL') $oncelik_badge = "bg-info text-dark";
                                if ($row['oncelik'] == 'YUKSEK') $oncelik_badge = "bg-warning text-dark";
                                if ($row['oncelik'] == 'KRITIK') $oncelik_badge = "bg-danger";
                                echo "<td><span class='badge " . $oncelik_badge . "'>" . $row['oncelik'] . "</span></td>";

                                // Durum rozeti
                                $durum_badge = "bg-primary";
                                if ($row['durum'] == 'YENI') $durum_badge = "bg-info text-dark";
                                if ($row['durum'] == 'ATANDI') $durum_badge = "bg-secondary";
                                if ($row['durum'] == 'ISLEMDE') $durum_badge = "bg-warning text-dark";
                                if ($row['durum'] == 'BEKLEMEDE') $durum_badge = "bg-dark";
                                if ($row['durum'] == 'COZULDU') $durum_badge = "bg-success";
                                if ($row['durum'] == 'IPTAL') $durum_badge = "bg-danger";

                                echo "<td><span class='badge " . $durum_badge . "'>" . $row['durum'] . "</span></td>";
                                
                                echo "<td>
                                        <form method='POST' class='d-flex gap-2'>
                                            <input type='hidden' name='ariza_id' value='" . $row['id'] . "'>
                                            <select name='yeni_durum' class='form-select form-select-sm'>
                                                <option value='YENI' " . ($row['durum'] == 'YENI' ? 'selected' : '') . ">YENİ</option>
                                                <option value='ATANDI' " . ($row['durum'] == 'ATANDI' ? 'selected' : '') . ">ATANDI</option>
                                                <option value='ISLEMDE' " . ($row['durum'] == 'ISLEMDE' ? 'selected' : '') . ">İŞLEMDE</option>
                                                <option value='BEKLEMEDE' " . ($row['durum'] == 'BEKLEMEDE' ? 'selected' : '') . ">BEKLEMEDE</option>
                                                <option value='COZULDU' " . ($row['durum'] == 'COZULDU' ? 'selected' : '') . ">ÇÖZÜLDÜ</option>
                                                <option value='IPTAL' " . ($row['durum'] == 'IPTAL' ? 'selected' : '') . ">İPTAL</option>
                                            </select>
                                            <button type='submit' class='btn btn-sm btn-primary'>Güncelle</button>
                                        </form>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>Kayıtlı arıza talebi bulunmuyor.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>