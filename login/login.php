<?php
session_start();
require_once '../php/config.php'; // File koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil input email dan password dari form
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validasi input kosong
    if (empty($email) || empty($password)) {
        echo "error"; // Input kosong
        exit();
    }

    // Query untuk mencari user berdasarkan email
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Query error: " . $conn->error);
    }

    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek jika user ditemukan
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verifikasi password dengan password_hash() dari kolom "password"
        if (password_verify($password, $user['password'])) {
            // Set session jika password cocok
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['first_name'] = $user['firstName'];
            $_SESSION['last_name'] = $user['lastName'];

            // Kirim respons sukses ke JavaScript
            echo "success";
            exit();
        } else {
            // Password salah
            echo "error";
            exit();
        }
    } else {
        // Jika email tidak ditemukan
        echo "error";
        exit();
    }
} else {
    // Redirect jika halaman diakses langsung
    header('Location: login.html');
    exit();
}
?>
