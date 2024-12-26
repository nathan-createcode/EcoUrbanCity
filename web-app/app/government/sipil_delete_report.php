<?php
include '../login_adgov/check_session.php';
include '../php/config.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'sipil') {
    header('Location: ../login_adgov/login_adgov.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM laporan_infrastruktur WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Laporan berhasil dihapus";
    } else {
        $_SESSION['message'] = "Error: Gagal menghapus laporan";
    }
} else {
    $_SESSION['message'] = "Error: ID laporan tidak ditemukan";
}

header("Location: sipil_read_reports.php");
exit();
?>