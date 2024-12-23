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
    header('Location: perhubungan_read_reports.php');
    exit();
}

$sql = "SELECT * FROM laporan_infrastruktur WHERE id = ? AND kategori = 'perhubungan'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$report = $result->fetch_assoc();

if (!$report) {
    $_SESSION['message'] = "Laporan tidak ditemukan.";
    header('Location: perhubungan_read_reports.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $deskripsi_masalah = mysqli_real_escape_string($conn, $_POST['deskripsi_masalah']);

    // Handle file upload
    $photo = $report['photo']; // Keep the existing photo by default
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "uploads/";
        $file_extension = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
        $new_filename = uniqid() . "_" . time() . "." . $file_extension;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            // If a new file is uploaded successfully, update the photo path
            $photo = $target_file;
        } else {
            $error = "Maaf, terjadi kesalahan saat mengunggah file.";
        }
    }

    $sql = "UPDATE laporan_infrastruktur SET deskripsi_masalah=?, photo=? WHERE id=? AND kategori='perhubungan'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $deskripsi_masalah, $photo, $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Laporan berhasil diperbarui!";
        header("Location: perhubungan_read_reports.php");
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
    <title>Edit Laporan Infrastruktur - EcoUrbanCity</title>
    <link rel="stylesheet" href="perhubungan_create_update.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <?php echo $sidebar; ?>

        <main class="main-content">
            <div class="container">
                <div class="header">
                    <h2><i class="fas fa-edit"></i> Edit Laporan Infrastruktur</h2>
                    <a href="perhubungan_read_reports.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <?php if (isset($error)): ?>
                    <div class="message error">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="post" enctype="multipart/form-data" class="form">
                    <div class="form-group">
                        <label for="deskripsi_masalah"><i class="fas fa-align-left"></i> Deskripsi Masalah</label>
                        <textarea id="deskripsi_masalah" name="deskripsi_masalah" required class="form-control" rows="4"><?php echo htmlspecialchars($report['deskripsi_masalah']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="photo"><i class="fas fa-image"></i> Foto</label>
                        <?php if ($report['photo']): ?>
                            <img src="<?php echo htmlspecialchars($report['photo']); ?>" alt="Current Photo" style="max-width: 200px; margin-bottom: 10px;">
                        <?php endif; ?>
                        <input type="file" id="photo" name="photo" class="form-control">
                    </div>

                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <a href="perhubungan_read_reports.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>