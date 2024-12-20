<?php
include '../login_adgov/check_session.php';
include '../php/config.php';

if (!isset($_GET['id'])) {
    header("Location: read_government.php");
    exit();
}

$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM government WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: read_government.php");
    exit();
}

$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);
        $sql = "UPDATE government SET email=?, password=?, role=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $email, $password, $role, $id);
    } else {
        $sql = "UPDATE government SET email=?, role=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $email, $role, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['message'] = "Data berhasil diupdate!";
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
    <title>Update Data Government</title>
    <link rel="stylesheet" href="update_government.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-edit"></i> Update Data Government</h2>

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

            <div class="form-group">
                <label><i class="fas fa-user-tag"></i> Role</label>
                <select name="role" required>
                    <option value="perhubungan" <?php if($row['role'] == 'perhubungan') echo 'selected'; ?>>
                        Perhubungan
                    </option>
                    <option value="lingkungan" <?php if($row['role'] == 'lingkungan') echo 'selected'; ?>>
                        Lingkungan
                    </option>
                    <option value="sipil" <?php if($row['role'] == 'sipil') echo 'selected'; ?>>
                        Sipil
                    </option>
                </select>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="read_government.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</body>
</html>