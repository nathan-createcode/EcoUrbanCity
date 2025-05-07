<?php
include '../login_adgov/check_session.php';
include '../php/config.php';
include 'perhubungan_sidebar.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'perhubungan') {
    header('Location: ../login_adgov/login_adgov.php');
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: perhubungan_read_transport.php');
    exit();
}

$sql = "SELECT * FROM transportasi WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$transport = $result->fetch_assoc();

if (!$transport) {
    $_SESSION['message'] = "Data transportasi tidak ditemukan.";
    header('Location: perhubungan_read_transport.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
    $asal = mysqli_real_escape_string($conn, $_POST['asal']);
    $tujuan = mysqli_real_escape_string($conn, $_POST['tujuan']);
    $berangkat = mysqli_real_escape_string($conn, $_POST['berangkat']);
    $durasi = mysqli_real_escape_string($conn, $_POST['durasi']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);

    $sql = "UPDATE transportasi SET jenis=?, asal=?, tujuan=?, berangkat=?, durasi=?, harga=?, tanggal=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssiiis", $jenis, $asal, $tujuan, $berangkat, $durasi, $harga, $tanggal, $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Data transportasi berhasil diperbarui!";
        header("Location: perhubungan_read_transport.php");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Transportasi - EcoUrbanCity</title>
    <link rel="stylesheet" href="perhubungan_create_update.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <?php echo $sidebar; ?>

        <main class="main-content">
            <div class="container">
                <div class="header">
                    <h2><i class="fas fa-edit"></i> Edit Data Transportasi</h2>
                    <a href="perhubungan_read_transport.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <?php if (isset($error)): ?>
                    <div class="message error">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="post" class="form">
                    <div class="form-group">
                        <label for="jenis"><i class="fas fa-bus"></i> Jenis Transportasi</label>
                        <input type="text" id="jenis" name="jenis" value="<?php echo $transport['jenis']; ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="asal"><i class="fas fa-map-marker-alt"></i> Asal</label>
                        <input type="text" id="asal" name="asal" value="<?php echo $transport['asal']; ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="tujuan"><i class="fas fa-map-pin"></i> Tujuan</label>
                        <input type="text" id="tujuan" name="tujuan" value="<?php echo $transport['tujuan']; ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="berangkat"><i class="fas fa-clock"></i> Waktu Berangkat</label>
                        <input type="time" id="berangkat" name="berangkat" value="<?php echo $transport['berangkat']; ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="durasi"><i class="fas fa-hourglass-half"></i> Durasi (menit)</label>
                        <input type="number" id="durasi" name="durasi" value="<?php echo $transport['durasi']; ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="harga"><i class="fas fa-money-bill-wave"></i> Harga</label>
                        <input type="number" id="harga" name="harga" value="<?php echo $transport['harga']; ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="tanggal"><i class="fas fa-calendar-alt"></i> Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" value="<?php echo $transport['tanggal']; ?>" required class="form-control">
                    </div>

                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <a href="perhubungan_read_transport.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>