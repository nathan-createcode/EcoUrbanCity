<?php
include '../login_adgov/check_session.php';
include '../php/config.php';
include 'sipil_sidebar.php';

// Mengecek role user
if ($_SESSION['role'] !== 'sipil') {
    header('Location: ../login_adgov/login_adgov.php');
    exit();
}

// Mengambil jumlah total users
$query_users = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$total_users = mysqli_fetch_assoc($query_users)['total'];

// Mengambil email user yang sedang login
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipil Dashboard - EcoUrbanCity</title>
    <link rel="stylesheet" href="sipil_module.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <?php echo $sidebar; ?>

        <main class="main-content">
            <header class="header">
                <h1><i class="fas fa-tachometer-alt"></i> Dashboard Sipil</h1>
                <div class="user-info">
                    <span><?php echo $email; ?></span>
                    <a href="../Login_adgov/logout_adgov.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </header>

            <div class="stats-grid">
                <div class="stat-card primary">
                    <i class="fas fa-users"></i>
                    <h3>Total Users Terdaftar</h3>
                    <div class="stat-value">
                        <?php echo $total_users; ?>
                    </div>
                </div>
            </div>

            <!-- Events Section -->
            <div class="container">
                <div class="header">
                    <h2><i class="fas fa-calendar-alt"></i> Data Events</h2>
                    <a href="sipil_create_event.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Event
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
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari event...">
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-heading"></i> Judul</th>
                                <th><i class="fas fa-align-left"></i> Deskripsi</th>
                                <th><i class="fas fa-calendar"></i> Tanggal</th>
                                <th><i class="fas fa-clock"></i> Waktu</th>
                                <th><i class="fas fa-cogs"></i> Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM events ORDER BY event_date DESC";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0):
                                while($row = $result->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo htmlspecialchars(substr($row['description'], 0, 50)) . '...'; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['event_date'])); ?></td>
                                    <td><?php echo date('H:i', strtotime($row['event_time'])); ?></td>
                                    <td class="action-buttons">
                                        <a href="sipil_update_event.php?id=<?php echo $row['id']; ?>"
                                           class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="sipil_delete_event.php?id=<?php echo $row['id']; ?>"
                                           class="btn btn-danger"
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus event ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data event</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Fungsi pencarian
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let searchText = this.value.toLowerCase();
            let tableRows = document.querySelectorAll('tbody tr');

            tableRows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchText) ? '' : 'none';
            });
        });
    </script>
</body>
</html>