<?php
// Gunakan konstanta jika belum didefinisikan
if (!defined('DB_HOST')) {
    define('DB_HOST', '127.0.0.1');
}
if (!defined('DB_USER')) {
    define('DB_USER', 'root');
}
if (!defined('DB_PASS')) {
    define('DB_PASS', '');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'ecourbancity');
}

// Buat koneksi menggunakan MySQLi
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Periksa koneksi
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Database connection error. Please try again later.");
}

// Optional: Tampilkan pesan keberhasilan (untuk debugging saja)
// echo "Koneksi berhasil!";
?>


