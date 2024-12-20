<?php
include '../login_adgov/check_session.php';
include '../php/config.php';

if (!isset($_GET['id'])) {
    header("Location: read_admin.php");
    exit();
}

$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM admin WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: read_admin.php");
    exit();
}

$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);
        $sql = "UPDATE admin SET email=?, password=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $email, $password, $id);
    } else {
        $sql = "UPDATE admin SET email=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $email, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['message'] = "Data admin berhasil diupdate!";
        header("Location: read_admin.php");
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
    <title>Update Data Admin</title>
    <link rel="stylesheet" href="update_government.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-edit"></i> Update Data Admin</h2>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password">
                <div class="password-note">
                    <i class="fas fa-info-circle"></i> Kosongkan jika tidak ingin mengubah password
                </div>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="read_admin.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</body>
</html>

