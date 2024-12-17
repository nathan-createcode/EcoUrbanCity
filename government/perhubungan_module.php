<?php
session_start();

// Periksa apakah user sudah login
if (!isset($_SESSION['email']) || !isset($_SESSION['role'])) {
    header('Location: ../login_adgov.html'); // Redirect ke halaman login jika session tidak ada
    exit();
}

// Mencegah caching halaman
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Perhubungan</title>
</head>
<body>
    <h1>Selamat Datang di Dashboard Perhubungan</h1>
    <p>Halo, <?php echo $_SESSION['email']; ?>! Anda memiliki akses untuk mengelola transportasi dan perhubungan.</p>
    <a href="../Login_adgov/logout_adgov.php">Logout</a>
</body>
</html>
