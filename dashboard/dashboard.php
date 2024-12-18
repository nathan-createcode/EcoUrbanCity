<?php
session_start();
require_once 'config.php';
require_once 'auth.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.html');
    exit();
}

// Get user data
$userData = getUserData($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoUrbanCity - Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <!-- Navigation -->
    <?php include 'header.php'; ?>

    <main class="dashboard-container">
        <div class="hero-section">
            <h1>EcoUrbanCity</h1>
            <p>Inisiatif untuk menciptakan kota yang lebih cerdas, berkelanjutan, dan nyaman bagi seluruh warga.</p>
            <div class="search-container">
                <input type="text" placeholder="Search" class="search-input">
                <button class="search-button">Cari</button>
            </div>
        </div>

        <!-- Service Cards -->
        <section class="services-grid">
            <div class="service-card">
                <h3>Forum Komunitas</h3>
                <p>24 Diskusi Aktif</p>
                <a href="#" class="see-more">see more</a>
            </div>
            <div class="service-card">
                <h3>Informasi Sampah</h3>
                <p>Update Terkini</p>
                <a href="#" class="see-more">see more</a>
            </div>
            <div class="service-card">
                <h3>Statistik Kota</h3>
                <p>Data Real-time</p>
                <a href="#" class="see-more">see more</a>
            </div>
            <div class="service-card">
                <h3>Monitoring Lalu Lintas</h3>
                <p id="traffic-status">Memuat...</p>
                <a href="#" class="see-more">see more</a>
            </div>
            <div class="service-card">
                <h3>Kualitas Udara</h3>
                <p id="air-quality">Memuat...</p>
                <a href="#" class="see-more">see more</a>
            </div>
            <div class="service-card">
                <h3>Notifikasi Darurat</h3>
                <p>Tidak ada kejadian darurat</p>
                <a href="#" class="see-more">see more</a>
            </div>
            <div class="service-card">
                <h3>Laporan Infrastruktur</h3>
                <p>3 Laporan Baru</p>
                <a href="#" class="see-more">see more</a>
            </div>
        </section>

        <!-- Infrastructure Report Form -->
        <section class="report-section">
            <h2>Laporkan Masalah Infrastruktur</h2>
            <form id="infrastructureForm" class="report-form">
                <div class="form-group">
                    <label for="category">Kategori</label>
                    <div class="custom-select">
                        <select id="category" name="category" required>
                            <option value="">Pilih kategori</option>
                            <option value="dinas_perhubungan">Dinas Perhubungan</option>
                            <option value="dinas_lingkungan">Dinas Lingkungan</option>
                            <option value="dinas_sipil">Dinas Sipil</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Deskripsi Masalah</label>
                    <textarea id="description" name="description" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="photo">Foto Laporan</label>
                    <input type="file" id="photo" name="photo" accept="image/*">
                </div>
                <button type="submit" class="submit-button">Kirim Laporan</button>
            </form>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="dashboard.js"></script>
</body>
</html>