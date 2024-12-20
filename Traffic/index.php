<?php
// Sertakan file konfigurasi untuk koneksi ke database
require_once '../php/config.php';

// Ambil tanggal dari URL atau gunakan default
$tanggal_terpilih = isset($_GET['tanggal']) ? $_GET['tanggal'] : null;

// Query untuk mendapatkan semua tanggal unik
$sql_dates = "SELECT DISTINCT tanggal FROM transportasi ORDER BY tanggal ASC";
$result_dates = $conn->query($sql_dates);

// Query untuk mendapatkan data transportasi berdasarkan tanggal yang dipilih
if ($tanggal_terpilih) {
    $sql_transport = "SELECT * FROM transportasi WHERE tanggal = '$tanggal_terpilih'";
} else {
    $sql_transport = "SELECT * FROM transportasi WHERE 1=0"; // Tidak menampilkan data jika belum ada tanggal terpilih
}
$result_transport = $conn->query($sql_transport);
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

    <!-- Tombol Filter Tanggal -->
    <div class="date-filter">
        <?php
        if ($result_dates->num_rows > 0) {
            while ($row_date = $result_dates->fetch_assoc()) {
                $tanggal = $row_date['tanggal'];
                $formatted_date = date("l, d M Y", strtotime($tanggal));
                $is_active = ($tanggal_terpilih == $tanggal) ? 'active' : '';
                echo "<form method='get' class='date-button-form'>
                          <input type='hidden' name='tanggal' value='$tanggal'>
                          <button type='submit' class='date-button $is_active'>$formatted_date</button>
                      </form>";
            }
        } else {
            echo "<p>Tidak ada tanggal yang tersedia.</p>";
        }
        ?>
    </div>

    <!-- Tabel Transportasi -->
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
            <?php if ($result_transport->num_rows > 0): ?>
                <?php while ($row = $result_transport->fetch_assoc()): ?>
                    <?php
                    // Hitung waktu tiba
                    $time_departure = strtotime($row['berangkat']); // Waktu keberangkatan
                    $duration_in_minutes = (int)filter_var($row['durasi'], FILTER_SANITIZE_NUMBER_INT); // Ambil angka dari durasi
                    $time_arrival = date("H:i", strtotime("+$duration_in_minutes minutes", $time_departure)); // Hitung waktu tiba
                    
                    // Format asal dan tujuan
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
                        <td><?= $asal_berangkat ?></td>
                        <td><?= $row['durasi'] ?></td>
                        <td><?= $tujuan_tiba ?></td>
                        <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Tidak ada data transportasi untuk tanggal ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
<?php include_once('../php/footer.php'); ?>

</body>
</html>
