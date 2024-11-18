<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    // Validasi server-side
    $errors = [];
    if (!$firstName) $errors[] = 'Nama Depan wajib diisi.';
    if (!$lastName) $errors[] = 'Nama Belakang wajib diisi.';
    if (!$email) $errors[] = 'Email tidak valid.';
    if (!$password || strlen($password) < 8) $errors[] = 'Password minimal 8 karakter.';
    if ($password !== $confirmPassword) $errors[] = 'Password dan Konfirmasi Password tidak cocok.';
    if (!$street) $errors[] = 'Area Jalan wajib diisi.';
    if (!$postalCode) $errors[] = 'Kode Pos wajib diisi.';
    if (!$purpose) $errors[] = 'Tujuan wajib dipilih.';
    if (!$agreement) $errors[] = 'Anda harus menyetujui syarat dan ketentuan.';

    // Jika ada error, kirimkan kembali ke front-end
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Validasi gagal', 'errors' => $errors]);
        exit;
    }

    // Enkripsi password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Koneksi ke database
    $conn = new mysqli("localhost", "root", "", "ecourbancity");
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Koneksi ke database gagal: ' . $conn->connect_error]);
        exit;
    }

    // Menyimpan data ke database
    $stmt = $conn->prepare("
        INSERT INTO users 
        (firstName, lastName, email, phone, password, street, postalCode, occupation, purpose, agreement) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Kesalahan SQL: ' . $conn->error]);
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

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
