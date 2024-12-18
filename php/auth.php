<?php
require_once 'config.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserData($userId) {
    global $conn;
    $userId = $conn->real_escape_string($userId);

    $query = "SELECT id, firstName, lastName, email FROM users WHERE id = '$userId'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

function login($email, $password) {
    global $conn;
    $email = $conn->real_escape_string($email);

    $query = "SELECT id, password FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            return true;
        }
    }
    return false;
}