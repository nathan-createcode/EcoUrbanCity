<?php
include '../login_adgov/check_session.php';
include '../php/config.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'perhubungan') {
    header('Location: ../login_adgov/login_adgov.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // First, get the photo filename
    $sql = "SELECT photo FROM laporan_infrastruktur WHERE id = ? AND kategori = 'perhubungan'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $report = $result->fetch_assoc();

    // Delete the report from the database
    $sql = "DELETE FROM laporan_infrastruktur WHERE id = ? AND kategori = 'perhubungan'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // If database deletion is successful, delete the photo file
        if ($report && $report['photo'] && file_exists($report['photo'])) {
            unlink($report['photo']);
        }
        $_SESSION['message'] = "Laporan infrastruktur berhasil dihapus!";
    } else {
        $_SESSION['message'] = "Error: " . $conn->error;
    }

    $stmt->close();
} else {
    $_SESSION['message'] = "ID laporan tidak valid.";
}

header('Location: perhubungan_read_reports.php');
exit();
?>