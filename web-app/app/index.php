<?php
// config.php
$db_host = 'db'; // Gunakan nama service yang sesuai di Docker Compose
$db_user = 'root'; // Username MySQL
$db_password = 'root_password'; // Password root untuk MySQL
$db_name = 'ecourbancity'; // Nama database yang digunakan

// Membuat koneksi ke MySQL
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Mengecek apakah koneksi berhasil
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

header("Location: login/login.php");
?>
