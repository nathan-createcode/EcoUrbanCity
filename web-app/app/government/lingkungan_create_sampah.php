<?php
include '../login_adgov/check_session.php';
include '../php/config.php';
include 'lingkungan_sidebar.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'lingkungan') {
    header('Location: ../login_adgov/login_adgov.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $area = mysqli_real_escape_string($conn, $_POST['area']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $days = implode(", ", $_POST['days']);

    $sql = "INSERT INTO jadwal_sampah (area, time, days) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $area, $time, $days);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Jadwal sampah berhasil ditambahkan!";
        header("Location: lingkungan_read_sampah.php");
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
    <title>Tambah Jadwal Sampah - EcoUrbanCity</title>
    <link rel="stylesheet" href="lingkungan_create_update.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <?php echo $sidebar; ?>

        <main class="main-content">
            <div class="container">
                <div class="header">
                    <h2><i class="fas fa-plus-circle"></i> Tambah Jadwal Sampah</h2>
                    <a href="lingkungan_read_sampah.php" class="btn btn-secondary">
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
                        <label for="area"><i class="fas fa-map-marker-alt"></i> Area</label>
                        <input type="text" id="area" name="area" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="time"><i class="fas fa-clock"></i> Waktu</label>
                        <input type="time" id="time" name="time" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> Hari</label>
                        <div class="checkbox-group">
                            <?php
                            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                            foreach ($days as $day) {
                                echo "<label><input type='checkbox' name='days[]' value='$day'> $day</label>";
                            }
                            ?>
                        </div>
                    </div>

                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="lingkungan_read_sampah.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>