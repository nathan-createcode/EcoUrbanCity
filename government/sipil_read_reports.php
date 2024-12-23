<?php
include '../login_adgov/check_session.php';
include '../php/config.php';
include 'sipil_sidebar.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'sipil') {
    header('Location: ../login_adgov/login_adgov.php');
    exit();
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM laporan_infrastruktur WHERE
        kategori = 'sipil' AND role = 'sipil' AND
        (deskripsi_masalah LIKE '%$search%' OR
        created_at LIKE '%$search%')
        ORDER BY created_at DESC";
$result = $conn->query($sql);

if (!$result) {
    die("Error in query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Infrastruktur Sipil - EcoUrbanCity</title>
    <link rel="stylesheet" href="sipil_crud.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <?php echo $sidebar; ?>

        <main class="main-content">
            <div class="container">
                <div class="header">
                    <h2><i class="fas fa-file-alt"></i> Laporan Infrastruktur Sipil</h2>
                    <a href="sipil_create_report.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Laporan
                    </a>
                </div>

                <div class="search-box">
                    <form action="" method="GET">
                        <input type="text" name="search" placeholder="Cari laporan..." value="<?php echo htmlspecialchars($search); ?>">
                        <i class="fas fa-search"></i>
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
                                        <td>
                                            <?php if ($row['photo']): ?>
                                                <img src="<?php echo htmlspecialchars($row['photo']); ?>" alt="Foto Laporan" style="width: 100px; height: auto;">
                                            <?php else: ?>
                                                Tidak ada foto
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                                        <td>
                                            <!-- <a href="sipil_update_report.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a> -->
                                            <a href="sipil_delete_report.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">Tidak ada laporan infrastruktur sipil.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>