<?php
header('Content-Type: application/json');

// Koneksi ke database
$conn = new mysqli("db", "root", "t3", "ecourbancity");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Koneksi ke database gagal: ' . $conn->connect_error]);
    exit;
}

// Tangani hanya permintaan POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data input dari form
    $firstName = htmlspecialchars(trim($_POST['firstName'] ?? ''));
    $lastName = htmlspecialchars(trim($_POST['lastName'] ?? ''));
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $password = $_POST['password'] ?? '';
    $street = htmlspecialchars(trim($_POST['street'] ?? ''));
    $postalCode = htmlspecialchars(trim($_POST['postalCode'] ?? ''));
    $occupation = htmlspecialchars(trim($_POST['occupation'] ?? ''));
    $purpose = htmlspecialchars(trim($_POST['purpose'] ?? ''));
    $agreement = isset($_POST['agreement']) ? 1 : 0;

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Validasi data
    $errors = [];
    if (!$firstName) $errors[] = 'Nama depan wajib diisi.';
    if (!$lastName) $errors[] = 'Nama belakang wajib diisi.';
    if (!$email) $errors[] = 'Format email tidak valid.';
    if (!$password || strlen($password) < 8) $errors[] = 'Password minimal 8 karakter.';
    if (!$street) $errors[] = 'Area jalan wajib diisi.';
    if (!$postalCode) $errors[] = 'Kode pos wajib diisi.';
    if (!$purpose) $errors[] = 'Tujuan wajib dipilih.';
    if (!$agreement) $errors[] = 'Anda harus menyetujui syarat dan ketentuan.';

    // Jika ada error, kirimkan ke frontend
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Validasi gagal', 'errors' => $errors]);
        exit;
    }

    // Periksa apakah email sudah digunakan
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Kesalahan pada query: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Email sudah terdaftar. Silakan gunakan email lain.']);
        exit;
    }
    $stmt->close();

    // Simpan data ke database
    $stmt = $conn->prepare("
        INSERT INTO users (firstName, lastName, email, phone, password, street, postalCode, occupation, purpose, agreement)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Kesalahan pada query: ' . $conn->error]);
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
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method tidak diizinkan']);
}

$conn->close();
?>
