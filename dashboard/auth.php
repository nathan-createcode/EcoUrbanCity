<?php
require_once __DIR__ . '/../php/config.php'; // Perbaiki path menuju config.php

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserData($userId) {
    global $conn;
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
?>
