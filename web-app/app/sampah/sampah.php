<?php
session_start();
require_once '../php/config.php';
require_once '../dashboard/auth.php';
require_once '../php/config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: ../login/login.php');
    exit();
}

// Get user data
$userData = getUserData($_SESSION['user_id']);
if (!$userData) {
    header('Location: ../login/login.php');
    exit();
}

$firstName = htmlspecialchars($userData['firstName'] ?? 'User');
$lastName = htmlspecialchars($userData['lastName'] ?? '');

// Database connection is handled in db.php


// Query untuk mengambil data
$sql = "SELECT area, time, days FROM jadwal_sampah";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoUrbanCity - Informasi Sampah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="sampah.css">
    <script src="sampah.js" defer></script>
</head>
<body>
    <?php include_once('../php/header.php'); ?>

    <main class="container">
        <section class="hero">
            <div class="hero-content">
                <h1>Informasi Sampah</h1>
                <blockquote>
                    "Respect the planet—put your trash in the right place."
                </blockquote>
            </div>
            <div class="hero-image">
                <img src="../img/planet-earth.png" alt="planet-earth">
            </div>
        </section>

        <section>
            <h2>Jadwal Pengangkutan Sampah</h2>
            <?php if ($result->num_rows > 0): ?>
                <div class="schedule-cards" id="scheduleCards">
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="card">
                            <div class="card-content">
                                <div>
                                    <i class="fas fa-map-marker-alt icon-location card-icon"></i>
                                    <span><?= htmlspecialchars($row["area"]) ?></span>
                                </div>
                                <div>
                                    <i class="fas fa-clock card-icon"></i>
                                    <span><?= htmlspecialchars($row["time"]) ?></span>
                                </div>
                                <div>
                                    <i class="fas fa-calendar-alt icon-calendar card-icon"></i>
                                    <span>Setiap <span class="highlight"><?= htmlspecialchars($row["days"]) ?></span></span>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="no-schedule-message">Tidak ada data jadwal pengangkutan sampah.</p>
            <?php endif; ?>
        </section>
    </main>

    <?php include_once('../php/footer.php'); ?>
</body>
</html>