<?php
session_start();

// Menangani cache
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Memeriksa sesi login
if (!isset($_SESSION['email']) || !isset($_SESSION['role'])) {
    // Jika tidak ada sesi, arahkan ke halaman login
    header("Location: login_adgov.html");
    exit();
}
?>

