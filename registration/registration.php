<?php
header('Content-Type: application/json');

// Cek apakah metode permintaan adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // **Menambahkan pengecekan email saat event blur** (pengecekan email terdaftar)
    if (isset($_POST['check_email'])) {
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        if (!$email) {
            echo json_encode(['available' => false, 'message' => 'Format email tidak valid.']);
            exit;
        }

        // Koneksi ke database
        $conn = new mysqli("localhost", "root", "", "ecourbancity");
        if ($conn->connect_error) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Koneksi ke database gagal: ' . $conn->connect_error]);
            exit;
        }

        // Periksa apakah email sudah digunakan
        $emailCheckQuery = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($emailCheckQuery);
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Kesalahan SQL: ' . $conn->error]);
            $conn->close();
            exit;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo json_encode(['available' => false, 'message' => 'Email sudah terdaftar. Silakan gunakan email lain.']);
        } else {
            echo json_encode(['available' => true, 'message' => 'Email tersedia.']);
        }

        $stmt->close();
        $conn->close();
        exit;
    }

    // Ambil data dari input pengguna
    $firstName = htmlspecialchars(trim($_POST['firstName'] ?? ''));
    $lastName = htmlspecialchars(trim($_POST['lastName'] ?? ''));
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $street = htmlspecialchars(trim($_POST['street'] ?? ''));
    $postalCode = htmlspecialchars(trim($_POST['postalCode'] ?? ''));
    $occupation = htmlspecialchars(trim($_POST['occupation'] ?? ''));
    $purpose = htmlspecialchars(trim($_POST['purpose'] ?? ''));
    $agreement = isset($_POST['agreement']) ? 1 : 0;

    // **Validasi server-side** untuk memastikan data yang dimasukkan valid
    $errors = [];
    if (!$firstName) $errors[] = ['field' => 'firstName', 'message' => 'Nama Depan wajib diisi.'];
    if (!$lastName) $errors[] = ['field' => 'lastName', 'message' => 'Nama Belakang wajib diisi.'];
    if (!$email) $errors[] = ['field' => 'email', 'message' => 'Email tidak valid.'];
    if (!$password || strlen($password) < 8) $errors[] = ['field' => 'password', 'message' => 'Password minimal 8 karakter.'];
    if ($password !== $confirmPassword) $errors[] = ['field' => 'confirmPassword', 'message' => 'Password dan Konfirmasi Password tidak cocok.'];
    if (!$street) $errors[] = ['field' => 'street', 'message' => 'Area Jalan wajib diisi.'];
    if (!$postalCode) $errors[] = ['field' => 'postalCode', 'message' => 'Kode Pos wajib diisi.'];
    if (!$purpose) $errors[] = ['field' => 'purpose', 'message' => 'Tujuan wajib dipilih.'];
    if (!$agreement) $errors[] = ['field' => 'agreement', 'message' => 'Anda harus menyetujui syarat dan ketentuan.'];

    // Jika ada error, kirimkan kembali ke front-end
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Validasi gagal', 'errors' => $errors]);
        exit;
    }

    // **Enkripsi password** untuk keamanan
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Koneksi ke database
    $conn = new mysqli("localhost", "root", "", "ecourbancity");
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Koneksi ke database gagal: ' . $conn->connect_error]);
        exit;
    }

    // **Menyimpan data ke database** setelah validasi berhasil
    $insertQuery = "
        INSERT INTO users 
        (firstName, lastName, email, phone, password, street, postalCode, occupation, purpose, agreement) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    $stmt = $conn->prepare($insertQuery);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Kesalahan SQL: ' . $conn->error]);
        $conn->close();
        exit;
    }

    $stmt->bind_param(
        "sssssssssi",
        $firstName,
        $lastName,
        $email,
        $phone,
        $hashedPassword,
        $street,
        $postalCode,
        $occupation,
        $purpose,
        $agreement
    );

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Pendaftaran berhasil']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Error saat menyimpan data: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
}

// Menampilkan error untuk debugging (dapat dimatikan pada production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
