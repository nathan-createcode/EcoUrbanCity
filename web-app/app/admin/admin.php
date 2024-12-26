<?php
include '../login_adgov/check_session.php';
include '../php/config.php';
include 'sidebar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <?php echo $sidebar; ?>

        <main class="main-content">
            <header class="header">
        <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
        <div class="user-info">
            <?php
            // Get the logged in admin's email
            $admin_email = $_SESSION['email']; // Assuming email is stored in session
            $query = mysqli_query($conn, "SELECT email FROM admin WHERE email = '$admin_email'");
            $admin = mysqli_fetch_assoc($query);
            ?>
            <span><?php echo $admin['email']; ?></span>
            <a href="../login_adgov/logout_adgov.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
            </header>

            <div class="stats-grid">
                <div class="stat-card turquoise">
                    <i class="fas fa-user-shield"></i>
                    <h3>Admin Users</h3>
                    <div class="stat-value">
                        <?php
                        $query = mysqli_query($conn, "SELECT COUNT(*) as total FROM admin");
                        $result = mysqli_fetch_assoc($query);
                        echo $result['total'];
                        ?>
                    </div>
                </div>
                <div class="stat-card green">
                    <i class="fas fa-users"></i>
                    <h3>Government Users</h3>
                    <div class="stat-value">
                        <?php
                        $query = mysqli_query($conn, "SELECT COUNT(*) as total FROM government");
                        $result = mysqli_fetch_assoc($query);
                        echo $result['total'];
                        ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>