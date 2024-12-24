<?php
session_start();
require_once '../php/config.php'; // File koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari input form
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validasi input kosong
    if (empty($email) || empty($password)) {
        error_log("Login gagal: Email atau password kosong.");
        echo "error";
        exit();
    }

    // Query untuk mencari pengguna berdasarkan email
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);

    // Periksa jika statement gagal disiapkan
    if (!$stmt) {
        error_log("Error preparing query: " . $conn->error);
        echo "error";
        exit();
    }

    $stmt->bind_param('s', $email); // Bind parameter email
    $stmt->execute();
    $result = $stmt->get_result();

    // Periksa apakah email ditemukan
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc(); // Ambil data pengguna

        // Verifikasi password menggunakan password_verify
        if (password_verify($password, $user['password'])) {
            // Simpan data ke sesi
            $_SESSION['user_type'] = 'user';
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header('Location: ../dashboard/dashboard.php');
            exit();
        } else {
            error_log("Password tidak cocok untuk email: $email");
        }
    } else {
        error_log("Email tidak ditemukan: $email");
    }
    // Jika login gagal
    echo "email atau password salah";
    exit();
} else {
    // Jika bukan metode POST, arahkan ke halaman login
    header('Location: login.php');

    exit();
}
?>
