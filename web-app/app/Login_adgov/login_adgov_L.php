<?php
session_start();
include '../php/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Cek di tabel admin
    $sql_admin = "SELECT * FROM admin WHERE email = ? AND password = ?";
    $stmt_admin = $conn->prepare($sql_admin);
    $stmt_admin->bind_param("ss", $email, $password);
    $stmt_admin->execute();
    $result_admin = $stmt_admin->get_result();

    if ($result_admin->num_rows > 0) {
        $row = $result_admin->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_type'] = 'admin';
        $_SESSION['email'] = $email;
        $_SESSION['loggedIn'] = true;
        header("Location: ../admin/admin.php");
        exit();
    } else {
        // Jika tidak ditemukan di admin, cek di government
        $sql_gov = "SELECT * FROM government WHERE email = ? AND password = ?";
        $stmt_gov = $conn->prepare($sql_gov);
        $stmt_gov->bind_param("ss", $email, $password);
        $stmt_gov->execute();
        $result_gov = $stmt_gov->get_result();

        if ($result_gov->num_rows > 0) {
            $row = $result_gov->fetch_assoc();
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
            echo "Email atau Password salah!";
        }
    }

    $stmt_admin->close();
    $stmt_gov->close();
    $conn->close();
}
?>