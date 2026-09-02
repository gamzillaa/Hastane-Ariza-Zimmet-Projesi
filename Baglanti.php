<?php
// Bağlantı dosyamızı dahil ediyoruz
include 'baglanti.php';

// View üzerinden verileri çekiyoruz
$sql = "SELECT * FROM VW_ACIK_ARIZA_TALEPLERI";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Hastane Arıza ve Zimmet Takip Sistemi</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f6f9; }
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #007bff; color: white; }
    </style>
</head>
<body>

    <h2>Aktif Arıza Talepleri Listesi</h2>
    
    <table>
        <tr>
            <th>Talep No</th>
            <th>Başlık</th>
            <th>Durum</th>
            <th>Öncelik</th>
            <th>Talep Tarihi</th>
            <th>Demirbaş No</th>
            <th>Talep Eden</th>
        </tr>
        
        <?php while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) { ?>
        <tr>
            <td><?php echo $row['talep_no']; ?></td>
            <td><?php echo $row['baslik']; ?></td>
            <td><?php echo $row['durum']; ?></td>
            <td><?php echo $row['oncelik']; ?></td>
            <td><?php echo $row['talep_tarihi']->format('Y-m-d'); ?></td>
            <td><?php echo $row['demirbas_no']; ?></td>
            <td><?php echo $row['talep_eden']; ?></td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>

<?php
// Bağlantıyı kapatıyoruz
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>