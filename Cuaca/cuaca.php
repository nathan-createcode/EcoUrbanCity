<?php
session_start();
require_once '../php/config.php';
require_once '../dashboard/auth.php';
require_once '../php/config.php'; // Added line

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: ../login/login.html');
    exit();
}

// Get user data
$userData = getUserData($_SESSION['user_id']);
if (!$userData) {
    header('Location: ../login/login.html');
    exit();
}

$firstName = htmlspecialchars($userData['firstName'] ?? 'User');
$lastName = htmlspecialchars($userData['lastName'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <link rel="stylesheet" href="../cuaca/cuaca.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="../cuaca/cuaca.js"></script>
</head>
<body>
    <?php include_once('../php/header.php'); ?>

    <div class="container">
        <header>
            <h1>Look up the Weather</h1>
            <div class="search-container">
                <input type="text" id="search" placeholder="Search">
                <i data-lucide="search" class="search-icon"></i>
            </div>
        </header>

        <div class="weather-cards">
            <div class="card today large-card">
                <div class="day">Monday</div>
                <div class="location">Sleman</div>
                <div class="temperature">28°C</div>
                <div class="weather-icon">
                    <i data-lucide="sun"></i>
                </div>
                <div class="details">
                    <span><i data-lucide="wind"></i> 20 km/h</span>
                    <span><i data-lucide="sun"></i> 10</span>
                    <span><i data-lucide="droplets"></i> 80%</span>
                </div>
            </div>
            <div class="card"><div class="day">Tuesday</div><div class="weather-icon"><i data-lucide="cloud-rain"></i></div><div class="temperature">25°C</div></div>
            <div class="card"><div class="day">Wednesday</div><div class="weather-icon"><i data-lucide="cloud"></i></div><div class="temperature">25°C</div></div>
            <div class="card"><div class="day">Thursday</div><div class="weather-icon"><i data-lucide="cloud"></i></div><div class="temperature">25°C</div></div>
            <div class="card"><div class="day">Friday</div><div class="weather-icon"><i data-lucide="sun"></i></div><div class="temperature">25°C</div></div>
            <div class="card"><div class="day">Saturday</div><div class="weather-icon"><i data-lucide="cloud-sun"></i></div><div class="temperature">25°C</div></div>
            <div class="card"><div class="day">Sunday</div><div class="weather-icon"><i data-lucide="cloud"></i></div><div class="temperature">25°C</div></div>
        </div>

        <div id="map"></div>

        <div class="aqi">
            <h2>AQI</h2>
            <div class="aqi-value">Healthy (90)</div>
            <div class="progress-bar">
                <div class="progress"></div>
            </div>
        </div>

        <div class="metrics">
            <div class="metric"><div class="metric-label"><i data-lucide="sun"></i> UV Index</div><div class="metric-value">Very High</div></div>
            <div class="metric"><div class="metric-label"><i data-lucide="droplets"></i> Humidity</div><div class="metric-value">80%</div></div>
            <div class="metric"><div class="metric-label"><i data-lucide="wind"></i> Wind</div><div class="metric-value">20 km/h</div></div>
            <div class="metric"><div class="metric-label"><i data-lucide="thermometer"></i> Dew Point</div><div class="metric-value">28°</div></div>
            <div class="metric"><div class="metric-label"><i data-lucide="eye"></i> Visibility</div><div class="metric-value">15 km</div></div>
            <div class="metric"><div class="metric-label"><i data-lucide="gauge"></i> Pressure</div><div class="metric-value">1007.9 mb</div></div>
        </div>
    </div>

    <?php include_once('../php/footer.php'); ?>
</body>
</html>