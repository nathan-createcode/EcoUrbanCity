<?php
session_start();
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']); // Hapus pesan setelah ditampilkan
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Smart City Portal Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="login_adgov.css">
    
</head>
<body>
    <div class="container">
        <div class="form-container">
            <form method="POST" action="login_adgov_L.php" id="loginForm" class="active" autocomplete="off">
                <h2 class="welcome-text">Smart City Portal</h2>
                <p class="greeting-text">Halo, Admin dan Government!</p>

                <div class="form-group">
                    <input id="email" name="email" type="email" placeholder="Email" required>
                </div>

                <div class="form-group">
                    <input id="password" name="password" type="password" placeholder="Password" required>
                </div>

                <button type="submit" class="submit-btn">LOG IN</button>
            </form>
        </div>
    </div>

    <script src="login_adgov.js"></script>
    <script>
        // Tampilkan alert jika ada pesan kesalahan
        <?php if ($error_message): ?>
            alert("<?= htmlspecialchars($error_message) ?>");
        <?php endif; ?>
    </script>
</body>
</html>
