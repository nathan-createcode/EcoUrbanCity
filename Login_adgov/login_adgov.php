<?php
session_start();
include '../php/config.php'; // Koneksi ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Hash password input dengan MD5

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Cek di tabel admin
    $sql_admin = "SELECT * FROM admin WHERE email = '$email' AND password = '$password'";
    $result_admin = $conn->query($sql_admin);

    if ($result_admin->num_rows > 0) {
        $_SESSION['role'] = 'admin';
        $_SESSION['email'] = $email;
        header("Location: ../admin/admin_dashboard.html");
        exit();
    }

    // Cek di tabel government
    $sql_gov = "SELECT * FROM government WHERE email = '$email' AND password = '$password'";
    $result_gov = $conn->query($sql_gov);

    if ($result_gov->num_rows > 0) {
        $_SESSION['role'] = 'government';
        $_SESSION['email'] = $email;
        header("Location: ../government/government_dashboard.html");
        exit();
    }

    // Jika tidak cocok di kedua tabel
    echo "Email atau Password salah!";
    $conn->close();
}
?>
