<?php
session_start();
include('config.php'); // Hubungkan ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Periksa pengguna di database
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Simpan informasi pengguna ke sesi
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['firstName'] . ' ' . $user['lastName'];

            // Arahkan ke dashboard.html
            header('Location: dashboard.html');
            exit();
        } else {
            echo "<script>alert('Password salah! Silakan coba lagi.'); window.location.href = 'login.html';</script>";
        }
    } else {
        echo "<script>alert('Email tidak ditemukan! Anda belum terdaftar.'); window.location.href = 'login.html';</script>";
    }
}
?>