<?php
// include '../login_adgov/check_session.php';
session_start();
include '../php/config.php';
include 'lingkungan_sidebar.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'lingkungan') {
  header("Location: ../login_adgov/login_adgov.php");
  exit();
}

// Ambil email user
$email = $_SESSION['email'];

// Query untuk menghitung total area jadwal sampah
$query_sampah = "SELECT COUNT(*) as total FROM jadwal_sampah";
$result_sampah = mysqli_query($conn, $query_sampah);
$row_sampah = mysqli_fetch_assoc($result_sampah);
$total_sampah = $row_sampah['total'];

// Query untuk menghitung total laporan dengan role='lingkungan'
$query_laporan = "SELECT COUNT(*) as total FROM laporan_infrastruktur WHERE role = 'lingkungan'";
$result_laporan = mysqli_query($conn, $query_laporan);
$row_laporan = mysqli_fetch_assoc($result_laporan);
$total_laporan = $row_laporan['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Lingkungan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="lingkungan_module.css">
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <?php echo $sidebar; ?>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1><i class="fas fa-tachometer-alt"></i> Dashboard Lingkungan</h1>
                <div class="user-info">
                    <!-- <span><?php echo $email; ?></span> -->
                    <a href="../login_adgov/logout_adgov.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card primary">
                    <i class="fas fa-calendar"></i>
                    <h3>Jadwal Sampah</h3>
                    <div class="stat-value"><?php echo $total_sampah; ?></div>
                    <p>Area</p>
                </div>

                <div class="stat-card secondary">
                    <i class="fas fa-file-alt"></i>
                    <h3>Laporan Infrastruktur</h3>
                    <div class="stat-value"><?php echo $total_laporan; ?></div>
                    <p>Laporan</p>
                </div>
            </div>

            <div class="quick-actions">
                <a href="lingkungan_read_sampah.php" class="btn btn-primary">
                    <i class="fas fa-eye"></i>
                    Lihat Jadwal Sampah
                </a>
                <a href="lingkungan_read_reports.php" class="btn btn-secondary">
                    <i class="fas fa-clipboard-list"></i>
                    Lihat Laporan Infrastruktur
                </a>
            </div>
        </div>
    </div>
</body>
</html>