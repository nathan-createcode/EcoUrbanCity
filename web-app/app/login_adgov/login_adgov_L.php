<?php
session_start();
include '../php/config.php';

$error_message = ""; // Variabel untuk menyimpan pesan kesalahan

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Cek di tabel admin
    $sql_admin = "SELECT * FROM admin WHERE email = ?";
    $stmt_admin = $conn->prepare($sql_admin);
    $stmt_admin->bind_param("s", $email);
    $stmt_admin->execute();
    $result_admin = $stmt_admin->get_result();

    if ($result_admin->num_rows > 0) {
        // Email ditemukan, sekarang cek password
        $row = $result_admin->fetch_assoc();
        if ($row['password'] === $password) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_type'] = 'admin';
            $_SESSION['email'] = $email;
            $_SESSION['loggedIn'] = true;
            header("Location: ../admin/admin.php");
            exit();
        } else {
            $error_message = "Password salah!"; // Pesan kesalahan untuk password salah
        }
    } else {
        // Jika tidak ditemukan di admin, cek di government
        $sql_gov = "SELECT * FROM government WHERE email = ?";
        $stmt_gov = $conn->prepare($sql_gov);
        if ($stmt_gov) { // Pastikan $stmt_gov didefinisikan
            $stmt_gov->bind_param("s", $email);
            $stmt_gov->execute();
            $result_gov = $stmt_gov->get_result();

            if ($result_gov->num_rows > 0) {
                // Email ditemukan, sekarang cek password
                $row = $result_gov->fetch_assoc();
                if ($row['password'] === $password) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['user_type'] = 'government';
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['email'] = $email;
                    $_SESSION['loggedIn'] = true;

                    switch ($row['role']) {
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
                    }
                    exit();
                } else {
                    $error_message = "Password salah!"; // Pesan kesalahan untuk password salah
                }
            } else {
                $error_message = "Email tidak terdaftar!"; // Pesan kesalahan untuk email tidak terdaftar
            }
        }
    }

    $stmt_admin->close();
    if (isset($stmt_gov)) { // Pastikan untuk menutup hanya jika didefinisikan
        $stmt_gov->close();
    }
    $conn->close();
}

// Jika ada pesan kesalahan, simpan di session dan tampilkan di halaman login
if (!empty($error_message)) {
    $_SESSION['error_message'] = $error_message;
    header("Location: login_adgov.php"); // Kembali ke halaman login
    exit();
}
?>