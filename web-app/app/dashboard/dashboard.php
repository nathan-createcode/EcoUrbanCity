<?php
session_start();
require_once '../php/config.php';
require_once 'auth.php';

// Mulai buffer output untuk memastikan tidak ada output tambahan
ob_start();

// Cek apakah user sudah login
if (!isLoggedIn()) {
    ob_end_clean();
    header('Location: ../login/login.php');
    exit();
}

// Ambil data user
$userData = getUserData($_SESSION['user_id']);

if (!$userData) {
    ob_end_clean();
    header('Location: ../login/login.php');
    exit();
}

$firstName = htmlspecialchars($userData['firstName'] ?? 'User');
$lastName = htmlspecialchars($userData['lastName'] ?? '');

// Hitung total users
$query_users = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
if (!$query_users) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Gagal menghitung total pengguna: ' . mysqli_error($conn)]);
    exit();
}
$total_users = mysqli_fetch_assoc($query_users)['total'];

// Hitung jumlah laporan untuk setiap kategori
$query_perhubungan = mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan_infrastruktur WHERE kategori = 'perhubungan'");
$query_lingkungan = mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan_infrastruktur WHERE kategori = 'lingkungan'");
$query_sipil = mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan_infrastruktur WHERE kategori = 'sipil'");

$total_perhubungan = mysqli_fetch_assoc($query_perhubungan)['total'];
$total_lingkungan = mysqli_fetch_assoc($query_lingkungan)['total'];
$total_sipil = mysqli_fetch_assoc($query_sipil)['total'];

$total_laporan = $total_perhubungan + $total_lingkungan + $total_sipil;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Content-Type: application/json');

    try {
        // Validasi input
        if (empty($_POST['category']) || empty($_POST['description'])) {
            throw new Exception('Field kategori dan deskripsi tidak boleh kosong!');
        }

        $kategori = $_POST['category'];
        $deskripsi = $_POST['description'];
        $photo = null;

        // Handle file upload
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = "../{$kategori}_reports_img/";

            // Buat direktori jika belum ada
            if (!file_exists($uploadDir) && !mkdir($uploadDir, 0777, true)) {
                throw new Exception('Gagal membuat folder untuk menyimpan file.');
            }

            // Generate unique filename
            $fileName = uniqid() . '_' . basename($_FILES['image_file']['name']);
            $targetPath = $uploadDir . $fileName;

            // Validasi tipe file
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!in_array($_FILES['image_file']['type'], $allowedTypes)) {
                throw new Exception('Tipe file tidak didukung. Hanya JPG, PNG, dan GIF yang diperbolehkan.');
            }

            // Validasi ukuran file (max 5MB)
            if ($_FILES['image_file']['size'] > 5 * 1024 * 1024) {
                throw new Exception('Ukuran file terlalu besar (maksimum 5MB).');
            }

            if (!move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)) {
                throw new Exception('Gagal mengupload file.');
            }

            $photo = $fileName; // Simpan hanya nama file, bukan full path
        }

        // Set role sama dengan kategori
        $role = $kategori;

        // Prepare statement untuk mencegah SQL injection
        $stmt = $conn->prepare("INSERT INTO laporan_infrastruktur (kategori, deskripsi_masalah, photo, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $kategori, $deskripsi, $photo, $role);

        if (!$stmt->execute()) {
            throw new Exception('Gagal menyimpan laporan: ' . $stmt->error);
        }

        $stmt->close();

        ob_end_clean();
        echo json_encode(['success' => true, 'message' => 'Laporan berhasil dikirim']);
    } catch (Exception $e) {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }

    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoUrbanCity - Dashboard</title>
    <link rel="stylesheet" href="../dashboard/dashboard.css">
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
                        <i class="fas fa-search"></i>
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
                    <i class="fas fa-trash-alt service-icon"></i>
                    <h3>Informasi Sampah</h3>
                    <p>Update Terkini</p>
                    <a href="../sampah/sampah.php" class="see-more">see more</a>
                </div>
                <div class="service-card">
                    <i class="fas fa-chart-bar service-icon"></i>
                    <h3>Statistik Kota</h3>
                    <p><?php echo $total_users; ?>/50 Citizen</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-car service-icon"></i>
                    <h3>Transportation</h3>
                    <p id="traffic-status">Check Now</p>
                    <a href="../traffic/traffic.php" class="see-more">see more</a>
                </div>
                <div class="service-card">
                    <i class="fas fa-wind service-icon"></i>
                    <h3>Kualitas Udara</h3>
                    <p>Check Now</p>
                    <a href="../cuaca/cuaca.php" class="see-more">see more</a>
                </div>
                <div class="service-card infrastructure-report">
                    <i class="fas fa-file-alt service-icon"></i>
                    <h3>Laporan Infrastruktur</h3>
                    <p><?php echo $total_laporan; ?> Laporan Total</p>
                    <a class="see-more" onclick="toggleReportDetails()">see more</a>
                    <div class="report-details" id="reportDetails">
                        <h4>Detail Laporan Infrastruktur</h4>
                        <p>Total Laporan: <?php echo $total_laporan; ?></p>
                        <h4>Berdasarkan Kategori:</h4>
                        <ul>
                            <li>Perhubungan: <?php echo $total_perhubungan; ?></li>
                            <li>Lingkungan: <?php echo $total_lingkungan; ?></li>
                            <li>Sipil: <?php echo $total_sipil; ?></li>
                        </ul>
                        <h4>Berdasarkan Role:</h4>
                        <ul>
                            <li>Perhubungan: <?php echo $total_perhubungan; ?></li>
                            <li>Lingkungan: <?php echo $total_lingkungan; ?></li>
                            <li>Sipil: <?php echo $total_sipil; ?></li>
                        </ul>
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
                        <option value="perhubungan">Perhubungan</option>
                        <option value="lingkungan">Lingkungan</option>
                        <option value="sipil">Sipil</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi Masalah</label>
                    <textarea id="description" name="description" required></textarea>
                </div>

                <div class="form-group">
                    <label for="image_file">
                        <i class="fas fa-image"></i> Unggah Gambar
                    </label>
                    <div class="upload-container">
                        <div id="uploadArea" class="upload-area">
                            <input type="file"
                                   id="image_file"
                                   name="image_file"
                                   required
                                   accept="image/png, image/jpeg, image/jpg, image/gif"
                                   style="display: none;">
                            <div id="preview-container" class="preview-container">
                                <div class="preview-content">
                                    <p class="placeholder-text">Drag and drop photo here or <span class="choose-photo">choose photo</span></p>
                                    <p class="file-info">Format yang didukung: JPG, PNG, GIF (Ukuran maks: 5 MB)</p>
                                </div>
                            </div>
                        </div>
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
    <script src="dashboard.js"></script>
    <script>
      function toggleReportDetails() {
    var details = document.getElementById('reportDetails');
    if (details.style.display === 'none' || details.style.display === '') {
        details.style.display = 'block';
    } else {
        details.style.display = 'none';
    }
}

// Menutup detail laporan jika mengklik di luar area
document.addEventListener('click', function(event) {
    var reportCard = document.querySelector('.infrastructure-report');
    var details = document.getElementById('reportDetails');
    if (!reportCard.contains(event.target) && details.style.display === 'block') {
        details.style.display = 'none';
    }
});
    </script>
</body>
</html>