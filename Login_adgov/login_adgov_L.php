<?php
session_start();
include '../php/config.php'; // Koneksi ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Hash password input dengan MD5

    // Koneksi database
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
        $_SESSION['loggedIn'] = true; // Menandakan bahwa sesi login berhasil
        header("Location: ../admin/admin.php");
        exit();
    }

    // Cek di tabel government
    $sql_gov = "SELECT * FROM government WHERE email = '$email' AND password = '$password'";
    $result_gov = $conn->query($sql_gov);

    if ($result_gov->num_rows > 0) {
        $row = $result_gov->fetch_assoc(); // Ambil data dari database
        $_SESSION['role'] = $row['role'];  // Simpan role spesifik
        $_SESSION['email'] = $email;
        $_SESSION['loggedIn'] = true; // Menandakan bahwa sesi login berhasil

        // Redirect berdasarkan role government
        if ($row['role'] == 'perhubungan') {
            header("Location: ../government/perhubungan_module.php");
        } elseif ($row['role'] == 'lingkungan') {
            header("Location: ../government/lingkungan_module.php");
        } elseif ($row['role'] == 'sipil') {
            header("Location: ../government/sipil_module.php");
        } else {
            echo "Role tidak dikenali!";
        }
        exit();
    }

    // Jika tidak cocok di kedua tabel
    echo "Email atau Password salah!";
    $conn->close();
}
?>
