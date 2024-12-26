<?php
// First, let's verify the file exists by adding a test echo
// echo "File is loading"; // Add this temporarily to test if the file is being accessed

include '../login_adgov/check_session.php';
include '../php/config.php';
include 'sidebar.php';

$sql = "SELECT * FROM admin ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Admin</title>
    <!-- Make sure these CSS files exist in the same directory -->
    <link rel="stylesheet" href="./admin.css">
    <link rel="stylesheet" href="./crud.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <?php echo $sidebar; ?>

        <main class="main-content">
            <div class="container">
                <div class="header">
                    <h2><i class="fas fa-user-shield"></i> Data Admin</h2>
                    <a href="create_admin.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Data
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
                    <input type="text" id="searchInput" placeholder="Cari data...">
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-envelope"></i> Email</th>
                                <th><i class="fas fa-calendar"></i> Created At</th>
                                <th><i class="fas fa-calendar-alt"></i> Updated At</th>
                                <th><i class="fas fa-cogs"></i> Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['created_at']; ?></td>
                                        <td><?php echo $row['update_at']; ?></td>
                                        <td class="action-buttons">
                                            <a href="update_admin.php?id=<?php echo $row['id']; ?>"
                                              class="btn btn-warning">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="delete_admin.php?id=<?php echo $row['id']; ?>"
                                              class="btn btn-danger"
                                              onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data</td>
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