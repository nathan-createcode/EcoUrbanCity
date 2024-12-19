<!-- <?php
// $host = "127.0.0.1"; // Alamat server (localhost)
// $username = "root"; // Username database
// $password = ""; // Password database
// $dbname = "ecourbancity"; // Nama database Anda

// define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASS', '');
// define('DB_NAME', 'ecourbancity');

// // Buat koneksi
// $conn = new mysqli($host, $username, $password, $dbname);

// // Periksa koneksi
// if ($conn->connect_error) {
//     die("Koneksi gagal: " . $conn->connect_error);
// }
?> -->

<?php
// Cek jika konstanta sudah didefinisikan sebelumnya
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

// Buat koneksi menggunakan konstanta
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>

