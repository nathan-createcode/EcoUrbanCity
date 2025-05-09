<?php
include '../login_adgov/check_session.php';

// Periksa apakah user sudah login
if (!isset($_SESSION['email']) || !isset($_SESSION['role'])) {
    header('Location: ../login_adgov/login_adgov.php'); // Redirect ke halaman login
    exit();
}

$role = $_SESSION['role'];

// Redirect ke modul sesuai role
if ($role == 'perhubungan') {
    header('Location: perhubungan_module.php');
    exit();
} elseif ($role == 'lingkungan') {
    header('Location: lingkungan_module.php');
    exit();
} elseif ($role == 'sipil') {
    header('Location: sipil_module.php');
    exit();
} else {
    echo "Role tidak dikenali! Hubungi administrator.";
    exit();
}
?>