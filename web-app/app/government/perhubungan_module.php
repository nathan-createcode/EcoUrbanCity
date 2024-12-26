<?php
include '../login_adgov/check_session.php';
include '../php/config.php';
include 'perhubungan_sidebar.php';

// Periksa apakah user sudah login
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'perhubungan') {
    header('Location: ../login_adgov/login_adgov.php');
    exit();
}

// Mengambil jumlah total transportasi
$query_transport = mysqli_query($conn, "SELECT COUNT(*) as total FROM transportasi");
$total_transport = mysqli_fetch_assoc($query_transport)['total'];

// Mengambil jumlah total laporan
$query_reports = mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan_infrastruktur WHERE kategori = 'perhubungan'");
$total_reports = mysqli_fetch_assoc($query_reports)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Perhubungan - EcoUrbanCity</title>
    <link rel="stylesheet" href="perhubungan_module.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <?php echo $sidebar; ?>

        <main class="main-content">
            <header class="header">
                <h1><i class="fas fa-tachometer-alt"></i> Dashboard Perhubungan</h1>
                <div class="user-info">
                    <!-- <span><?php echo $_SESSION['email']; ?></span> -->
                    <a href="../Login_adgov/logout_adgov.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </header>

            <div class="stats-grid">
                <div class="stat-card primary">
                    <i class="fas fa-bus"></i>
                    <h3>Total Transportasi</h3>
                    <div class="stat-value"><?php echo $total_transport; ?></div>
                </div>
                <div class="stat-card secondary">
                    <i class="fas fa-file-alt"></i>
                    <h3>Total Laporan</h3>
                    <div class="stat-value"><?php echo $total_reports; ?></div>
                </div>
            </div>

            <div class="quick-actions">
                <a href="perhubungan_read_transport.php" class="btn btn-primary">
                    <i class="fas fa-list"></i> Lihat Data Transportasi
                </a>
                <a href="perhubungan_read_reports.php" class="btn btn-secondary">
                    <i class="fas fa-clipboard-list"></i> Lihat Laporan Infrastruktur
                </a>
            </div>
        </main>
    </div>
</body>
</html>