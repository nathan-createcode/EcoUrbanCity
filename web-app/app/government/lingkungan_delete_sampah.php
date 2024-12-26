<?php
include '../login_adgov/check_session.php';
include '../php/config.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'lingkungan') {
    header('Location: ../login_adgov/login_adgov.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM jadwal_sampah WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Jadwal sampah berhasil dihapus!";
    } else {
        $_SESSION['message'] = "Error: " . $conn->error;
    }

    $stmt->close();
} else {
    $_SESSION['message'] = "ID jadwal sampah tidak valid.";
}

header('Location: lingkungan_read_sampah.php');
exit();
?>