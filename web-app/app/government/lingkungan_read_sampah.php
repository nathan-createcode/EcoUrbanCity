<?php
include '../login_adgov/check_session.php';
include '../php/config.php';
include 'lingkungan_sidebar.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'lingkungan') {
    header('Location: ../login_adgov/login_adgov.php');
    exit();
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM jadwal_sampah WHERE
        area LIKE '%$search%' OR
        time LIKE '%$search%' OR
        days LIKE '%$search%'
        ORDER BY area ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Sampah - EcoUrbanCity</title>
    <link rel="stylesheet" href="lingkungan_crud.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <?php echo $sidebar; ?>

        <main class="main-content">
            <div class="container">
                <div class="header">
                    <h2><i class="fas fa-trash"></i> Jadwal Sampah</h2>
                    <a href="lingkungan_create_sampah.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Jadwal
                    </a>
                </div>

                <?php if (isset($_SESSION['message'])): ?>
                    <div class="message success">
                        <?php
                            echo $_SESSION['message'];
                            unset($_SESSION['message']);
                        ?>
                    </div>
                <?php endif; ?>

                <div class="search-box">
                    <form action="" method="GET">
                        <input type="text" name="search" placeholder="Cari jadwal..." value="<?php echo $search; ?>">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Area</th>
                                <th>Waktu</th>
                                <th>Hari</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo $row['area']; ?></td>
                                        <td><?php echo $row['time']; ?></td>
                                        <td><?php echo $row['days']; ?></td>
                                        <td>
                                            <a href="lingkungan_update_sampah.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="lingkungan_delete_sampah.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">Tidak ada data jadwal sampah.</td>
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