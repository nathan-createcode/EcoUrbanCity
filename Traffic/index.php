<?php
// Sertakan file konfigurasi untuk koneksi ke database
require_once '../php/config.php';

// Query untuk mendapatkan data transportasi
$sql = "SELECT * FROM transportasi";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartCity Transport</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include_once('../php/header.php'); ?>

    <main>
        <h2>SmartCity’s Transport</h2>
        <div class="date-filter">
            <?php
            $hari = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
            for ($i = 0; $i < 7; $i++) {
                $tanggal = date("d M Y", strtotime("+$i days"));
                echo "<button>$hari[$i]<br>$tanggal</button>";
            }
            ?>
        </div>
        <table class="transport-table">
            <thead>
                <tr>
                    <th>Transportasi</th>
                    <th>Berangkat</th>
                    <th>Durasi</th>
                    <th>Tiba</th>
                    <th>Harga</th>
                </tr>
            </thead>
<tbody>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php
            // Hitung waktu tiba
            $time_departure = strtotime($row['berangkat']); // Waktu keberangkatan
            $duration_in_minutes = (int)filter_var($row['durasi'], FILTER_SANITIZE_NUMBER_INT); // Ambil angka dari durasi
            $time_arrival = date("H:i", strtotime("+$duration_in_minutes minutes", $time_departure)); // Hitung waktu tiba
            
            
$asal_berangkat = $row['asal'] . ' (' . date("H:i", $time_departure) . ' WIB)';
$tujuan_tiba = $row['tujuan'] . ' (' . $time_arrival . ' WIB)';
            ?>
            <tr>
                <td>
                    <img src="../img/<?= strtolower($row['jenis']) ?>.png" 
                         alt="<?= $row['jenis'] ?>" 
                         class="icon">
                    <?= strtoupper($row['jenis']) ?>
                </td>
                <td><?= $asal_berangkat ?></td>                <td><?= $row['durasi'] ?></td>
                <td><?= $tujuan_tiba ?></td>   
                <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="7">Tidak ada data tersedia.</td>
        </tr>
    <?php endif; ?>
</tbody>

        </table>
    </main>
    <?php include_once('../php/footer.php'); ?>

</body>
</html>
