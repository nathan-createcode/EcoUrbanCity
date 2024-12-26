<?php
include '../login_adgov/check_session.php';
include '../php/config.php';

if ($_SESSION['role'] !== 'sipil') {
    header('Location: ../login_adgov/login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User berhasil dihapus!";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }

    $stmt->close();
}

header("Location: sipil_read_users.php");
exit();