<?php
session_start();

// Menangani cache
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Memeriksa sesi login
if (!isset($_SESSION['email']) || !isset($_SESSION['user_type'])) {
    // Jika tidak ada sesi, arahkan ke halaman login
    header("Location: login_adgov.php");
    exit();
}

// Arahkan ke halaman yang sesuai berdasarkan user_type
if ($_SESSION['user_type'] === 'admin') {
    // Jika halaman saat ini bukan halaman admin, redirect
    if (!strpos($_SERVER['PHP_SELF'], 'admin/')) {
        header("Location: ../admin/admin.php");
        exit();
    }
} elseif ($_SESSION['user_type'] === 'government') {
    // Untuk pengguna government, arahkan ke modul yang sesuai
    if (!strpos($_SERVER['PHP_SELF'], 'government/')) {
        switch ($_SESSION['role']) {
            case 'perhubungan':
                header("Location: ../government/perhubungan_module.php");
                break;
            case 'lingkungan':
                header("Location: ../government/lingkungan_module.php");
                break;
            case 'sipil':
                header("Location: ../government/sipil_module.php");
                break;
            default:
                echo "Role tidak dikenali!";
                exit();
        }
    }
} else {
    // Jika user_type tidak dikenali, logout dan arahkan ke halaman login
    session_destroy();
    header("Location: login_adgov.php");
    exit();
}
?>