<?php
$host = "127.0.0.1"; // Alamat server (localhost)
$username = "root"; // Username database
$password = ""; // Password database
$dbname = "ecourbancity"; // Nama database Anda

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ecourbancity');

// Buat koneksi
$conn = new mysqli($host, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
