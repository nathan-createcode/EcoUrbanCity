<?php
include '../login_adgov/check_session.php';
include '../php/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $sql = "INSERT INTO government (email, password, role) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $email, $password, $role);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Data berhasil ditambahkan!";
        header("Location: read_government.php");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Government</title>
    <link rel="stylesheet" href="update_government.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-plus-circle"></i> Tambah Data Government</h2>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-user-tag"></i> Role</label>
                <select name="role" required>
                    <option value="perhubungan">Perhubungan</option>
                    <option value="lingkungan">Lingkungan</option>
                    <option value="sipil">Sipil</option>
                </select>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="read_government.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</body>
</html>