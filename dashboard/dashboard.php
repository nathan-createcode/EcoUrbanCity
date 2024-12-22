<?php
session_start();
require_once '../php/config.php';
require_once 'auth.php';

if (!isLoggedIn()) {
    header('Location: ../login/login.html');
    exit();
}

$userData = getUserData($_SESSION['user_id']);

if (!$userData) {
    header('Location: ../login/login.html');
    exit();
}

$firstName = htmlspecialchars($userData['firstName'] ?? 'User');
$lastName = htmlspecialchars($userData['lastName'] ?? '');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoUrbanCity - Dashboard</title>
    <link rel="stylesheet" href="../dashboard/dashboard.css">
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <?php include_once('../php/header.php'); ?>

    <main>
        <!-- Hero Section -->
        <section class="hero">
          <div class="hero-content">
            <h1>EcoUrbanCity</h1>
            <p>Inisiatif untuk menciptakan kota yang lebih cerdas, berkelanjutan, dan nyaman bagi seluruh warga.</p>
            <div class="search-container">
              <input type="text" placeholder="Search" class="search-input">
              <button type="submit" class="search-button">
                <i data-feather="search"></i>
              </button>
            </div>
          </div>
          <div class="hero-illustration">
            <img src="../img/2Purple Minimalist Zoom Virtual Background (2).png" alt="EcoUrbanCity Illustration" />
          </div>
          <div class="scroll-indicator"></div>
        </section>

        <!-- Services Section -->
        <section class="services">
            <h2>Layanan EcoUrbanCity</h2>
            <p class="subtitle">BEST CITY IN THE WORLD</p>

            <!-- Events Grid -->
            <div class="event-grid">
            <?php
            try {
                include '../php/config.php';
                $sql = "SELECT * FROM events ORDER BY event_date ASC";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $eventDate = new DateTime($row['event_date']);
                        $eventTime = new DateTime($row['event_time']);

                        echo "<div class='event-card'>";
                        echo "<img src='" . htmlspecialchars($row['image_url']) . "' alt='" . htmlspecialchars($row['title']) . "'>";
                        echo "<div class='event-overlay'>";
                        echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                        echo "<p class='event-date'>Tanggal: " . $eventDate->format('d F Y') . "</p>";
                        echo "<p class='event-time'>Waktu: " . $eventTime->format('H:i') . "</p>";
                        echo "<p>" . htmlspecialchars(substr($row['description'], 0, 100)) . "...</p>";
                        echo "<button class='cta-button' onclick='showEventDetails(" . $row['id'] . ")'>Lihat Detail</button>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p class='no-events'>Tidak ada event yang tersedia saat ini.</p>";
                }
            } catch (Exception $e) {
                echo "<p class='error-message'>Terjadi kesalahan saat memuat data event.</p>";
            } finally {
                if (isset($conn)) {
                    $conn->close();
                }
            }
            ?>
            </div>

            <!-- Service Cards Grid -->
            <div class="service-grid">
                <div class="service-card">
                    <i data-feather="message-square" class="service-icon"></i>
                    <h3>Forum Komunitas</h3>
                    <p>24 Diskusi Aktif</p>
                    <a href="#" class="see-more">see more</a>
                </div>
                <div class="service-card">
                    <i data-feather="trash-2" class="service-icon"></i>
                    <h3>Informasi Sampah</h3>
                    <p>Update Terkini</p>
                    <a href="#" class="see-more">see more</a>
                </div>
                <div class="service-card">
                    <i data-feather="bar-chart-2" class="service-icon"></i>
                    <h3>Statistik Kota</h3>
                    <p>Data Real-time</p>
                    <a href="#" class="see-more">see more</a>
                </div>
                <div class="service-card">
                    <i data-feather="activity" class="service-icon"></i>
                    <h3>Monitoring Lalu Lintas</h3>
                    <p id="traffic-status">Memuat...</p>
                    <a href="#" class="see-more">see more</a>
                </div>
                <div class="service-card">
                    <i data-feather="wind" class="service-icon"></i>
                    <h3>Kualitas Udara</h3>
                    <p id="air-quality">Memuat...</p>
                    <a href="#" class="see-more">see more</a>
                </div>
                <div class="service-card">
                    <i data-feather="alert-triangle" class="service-icon"></i>
                    <h3>Notifikasi Darurat</h3>
                    <p>Tidak ada kejadian darurat</p>
                    <a href="#" class="see-more">see more</a>
                </div>
                <div class="service-card">
                    <i data-feather="file-text" class="service-icon"></i>
                    <h3>Laporan Infrastruktur</h3>
                    <p>3 Laporan Baru</p>
                    <a href="#" class="see-more">see more</a>
                </div>
            </div>
        </section>

        <!-- Smart City Quote Section -->
        <section class="quote-section">
            <div class="quote-content">
                <blockquote>
                    "A smart city is not just about the technology; it's about the people and how technology empowers them to live better lives."
                </blockquote>
                <button class="get-in-touch">Get in touch</button>
            </div>
            <div class="quote-illustration">
                <!-- <i data-feather="home" class="quote-icon"></i> -->
            </div>
        </section>

        <!-- Infrastructure Report Form -->
        <section class="report-section">
            <h2>Laporkan Masalah Infrastruktur</h2>
            <form id="infrastructureForm" class="report-form">
                <div class="form-group">
                    <label for="category">Kategori</label>
                    <select id="category" name="category" required>
                        <option value="">Pilih kategori</option>
                        <option value="dinas_perhubungan">Dinas Perhubungan</option>
                        <option value="dinas_lingkungan">Dinas Lingkungan</option>
                        <option value="dinas_sipil">Dinas Sipil</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi Masalah</label>
                    <textarea id="description" name="description" required></textarea>
                </div>

                <div class="form-group">
                    <label for="photo">Foto Laporan</label>
                    <div class="upload-area" id="uploadArea">
                        <i data-feather="upload" class="upload-icon"></i>
                        <p>Drag and drop photo here or <span class="choose-photo">choose photo</span></p>
                        <p class="file-info">Supported formats: JPG, PNG, GIF (Max size: 5 MB)</p>
                        <input type="file" id="photo" name="photo" accept="image/*" hidden>
                    </div>
                </div>

                <button type="submit" class="submit-button">Kirim Laporan</button>
            </form>
        </section>
    </main>

    <?php include_once('../php/footer.php'); ?>

    <!-- Event Details Modal -->
    <div id="eventModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="eventDetailsContent"></div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="dashboard.js"></script>
    <script src="https://kit.fontawesome.com/YOUR-KIT-ID.js" crossorigin="anonymous"></script>
</body>
</html>

