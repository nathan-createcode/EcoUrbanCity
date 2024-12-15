<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: login_adgov.html"); // Redirect jika tidak login
    exit();
}
?>