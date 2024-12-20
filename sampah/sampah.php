<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecourbancity"; //nama DB, diganti aja kalo ga sama

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mengambil data
$sql = "SELECT area, time, days FROM jadwal_sampah"; //nama tabelnya jadwal_sampah
$result = $conn->query($sql);

// Tampilkan data jika ada hasil
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<div class="card">';
        echo '    <div class="card-content">';
        echo '        <div>';
        echo '            <i class="fas fa-map-marker-alt icon-location card-icon"></i>';
        echo '            <span>' . htmlspecialchars($row["area"]) . '</span>';
        echo '        </div>';
        echo '        <div>';
        echo '            <i class="fas fa-clock card-icon"></i>';
        echo '            <span>' . htmlspecialchars($row["time"]) . '</span>';
        echo '        </div>';
        echo '        <div>';
        echo '            <i class="fas fa-calendar-alt icon-calendar card-icon"></i>';
        echo '            <span>Setiap <span class="highlight">' . htmlspecialchars($row["days"]) . '</span></span>';
        echo '        </div>';
        echo '    </div>';
        echo '</div>';
    }
} else {
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoUrbanCity - Informasi Sampah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="sampah.css">
    <script src="sampah.js" defer></script>
</head>
<body>
    <!--HEADER-->
    <?php include '../php/header.php'; ?>

    <main class="container">
        <section class="hero">
            <div class="hero-content">
                <h1>Informasi Sampah</h1>
                <blockquote>
                    "Respect the planet—put your trash in the right place."
                </blockquote>
            </div>
            <div class="hero-image">
                <img src="../img/truck.png" alt="Garbage Truck">
            </div>
        </section>

        <section>
            <h2>Jadwal Pengangkutan Sampah</h2>
            <p class="no-schedule-message">Tidak ada data jadwal pengangkutan sampah.</p>
            <div class="schedule-cards" id="scheduleCards">
                <!-- Schedule cards will be dynamically inserted here -->
            </div>
        </section>
    </main>

    <?php include '../php/footer.php'; ?>
</body>
</html>