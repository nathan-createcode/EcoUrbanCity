<?php
include '../login_adgov/check_session.php';
include '../php/config.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'perhubungan') {
    header('Location: ../login_adgov/login_adgov.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM transportasi WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Data transportasi berhasil dihapus!";
    } else {
        $_SESSION['message'] = "Error: " . $conn->error;
    }

    $stmt->close();
} else {
    $_SESSION['message'] = "ID transportasi tidak valid.";
}

header('Location: perhubungan_read_transport.php');
exit();
?>