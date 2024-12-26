<?php
include '../login_adgov/check_session.php';
include '../php/config.php';
include 'lingkungan_sidebar.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'lingkungan') {
    header('Location: ../login_adgov/login_adgov.php');
    exit();
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM laporan_infrastruktur WHERE
        kategori = 'lingkungan' AND
        (deskripsi_masalah LIKE ? OR
        created_at LIKE ?)
        ORDER BY created_at DESC";

// Gunakan prepared statement untuk keamanan
$stmt = $conn->prepare($sql);
$searchTerm = "%$search%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Error in query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Infrastruktur Lingkungan - EcoUrbanCity</title>
    <link rel="stylesheet" href="lingkungan_crud.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="modal.css">
</head>
<body>
    <div class="dashboard">
        <?php echo $sidebar; ?>

        <main class="main-content">
            <div class="container">
                <div class="header">
                    <h2><i class="fas fa-file-alt"></i> Laporan Infrastruktur Lingkungan</h2>
                </div>

                <div class="search-box">
                    <form action="" method="GET">
                        <input type="text" name="search" placeholder="Cari laporan..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Deskripsi Masalah</th>
                                <th>Foto</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                                        <td><?php echo htmlspecialchars(substr($row['deskripsi_masalah'], 0, 50)) . '...'; ?></td>
                                        <td class="photo-cell">
                                            <?php if ($row['photo'] && file_exists("../lingkungan_reports_img/" . $row['photo'])): ?>
                                                <img src="../lingkungan_reports_img/<?php echo htmlspecialchars($row['photo']); ?>"
                                                    alt="Foto Laporan"
                                                    class="report-image"
                                                    onclick="openImageModal('<?php echo htmlspecialchars($row['photo']); ?>')"
                                                    loading="lazy">
                                            <?php else: ?>
                                                <span class="no-image">Tidak ada foto</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                                        <td>
                                            <a href="lingkungan_delete_report.php?id=<?php echo $row['id']; ?>"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">Tidak ada laporan infrastruktur lingkungan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="modal">
        <span class="close-modal">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <!-- Add JavaScript -->
    <script src="modal.js"></script>
</body>
</html>

