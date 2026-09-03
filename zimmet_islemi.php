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

// Zimmet güncelleme işlemi POST edildiğinde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cihaz_id = intval($_POST['cihaz_id']);
    $zimmetli_personel = mysqli_real_escape_string($conn, $_POST['zimmetli_personel']);
    $bulundugu_birim = mysqli_real_escape_string($conn, $_POST['bulundugu_birim']);

    $sql = "UPDATE cihazlar SET zimmetli_personel = '$zimmetli_personel', bulundugu_birim = '$bulundugu_birim' WHERE id = $cihaz_id";

    if (mysqli_query($conn, $sql)) {
        $mesaj = "<div class='alert alert-success'>Zimmet bilgisi başarıyla güncellendi!</div>";
    } else {
        $mesaj = "<div class='alert alert-danger'>Hata: " . mysqli_error($conn) . "</div>";
    }
}

// Cihazları listeden seçmek için çekiyoruz
$cihazlar_sorgu = "SELECT id, demirbas_no, marka, model, zimmetli_personel, bulundugu_birim FROM cihazlar ORDER BY demirbas_no ASC";
$cihazlar_sonuc = mysqli_query($conn, $cihazlar_sorgu);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Cihaz Zimmet Modülü - Hastane Bilgi İşlem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="card shadow p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="text-primary">Cihaz Zimmet ve Görevlendirme Modülü</h3>
                <div>
                    <a href="cihazlar.php" class="btn btn-secondary">Cihaz Listesine Dön</a>
                </div>
            </div>

            <?php echo $mesaj; ?>

            <form method="POST" class="row g-3 bg-white p-3 border rounded mb-4">
                <div class="col-md-4">
                    <label class="form-label">Cihaz Seçin (Demirbaş No - Marka)</label>
                    <select name="cihaz_id" class="form-select" required>
                        <option value="">Cihaz Seçiniz...</option>
                        <?php
                        while ($c = mysqli_fetch_assoc($cihazlar_sonuc)) {
                            echo "<option value='" . $c['id'] . "'>" . htmlspecialchars($c['demirbas_no']) . " - " . htmlspecialchars($c['marka']) . " " . htmlspecialchars($c['model']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Zimmetlenecek Personel</label>
                    <input type="text" name="zimmetli_personel" class="form-control" placeholder="Personel Adı Soyadı" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Bulunduğu Birim / Servis</label>
                    <input type="text" name="bulundugu_birim" class="form-control" placeholder="Örn: Poliklinik, Lab" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">Zimmetle</button>
                </div>
            </form>

            <h5 class="text-secondary mb-3">Mevcut Zimmetli Cihazlar</h5>
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Demirbaş No</th>
                            <th>Cihaz Türü</th>
                            <th>Marka / Model</th>
                            <th>Bulunduğu Birim</th>
                            <th>Zimmetli Personel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $liste_sorgu = "SELECT * FROM cihazlar WHERE zimmetli_personel != '' AND zimmetli_personel IS NOT NULL ORDER BY id DESC";
                        $liste_sonuc = mysqli_query($conn, $liste_sorgu);

                        if (mysqli_num_rows($liste_sonuc) > 0) {
                            while ($row = mysqli_fetch_assoc($liste_sonuc)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['demirbas_no']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['cihaz_turu']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['marka']) . " " . htmlspecialchars($row['model']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['bulundugu_birim']) . "</td>";
                                echo "<td><span class='badge bg-success'>" . htmlspecialchars($row['zimmetli_personel']) . "</span></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>Henüz zimmetlenmiş bir cihaz bulunmuyor.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>