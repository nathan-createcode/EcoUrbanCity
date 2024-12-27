<?php
session_start();
require_once '../php/config.php'; // Ensure this points to the correct config.php file

// Create a connection to MySQL
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from the form input
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validate empty input
    if (empty($email) || empty($password)) {
        header("Location: login.php?error=empty_fields");
        exit();
    }

    // Query to find the user by email
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);

    // Check if the statement failed to prepare
    if (!$stmt) {
        header("Location: login.php?error=query_failed");
        exit();
    }

    $stmt->bind_param('s', $email); // Bind the email parameter
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the email was found
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc(); // Get user data

        // Verify the password using password_verify
        if (password_verify($password, $user['password'])) {
            // Save data to session
            $_SESSION['user_type'] = 'user';
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header('Location: ../dashboard/dashboard.php');
            exit();
        } else {
            // If the password does not match
            header("Location: login.php?error=wrong_password");
            exit();
        }
    } else {
        // If the email was not found
        header("Location: login.php?error=email_not_found");
        exit();
    }
} else {
    // If not a POST method, redirect to the login page
    header('Location: login.php');
    exit();
}
?>