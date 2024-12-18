<?php
session_start();
include('config.php'); // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = md5(trim($_POST['password'])); // Gunakan MD5

    // Query untuk verifikasi
    $query = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['firstName'] . ' ' . $user['lastName'];
        echo "success"; // Respon untuk redirect
    } else {
        echo "error"; // Login gagal
    }
}
?>
