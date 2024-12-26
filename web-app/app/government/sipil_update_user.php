<?php
include '../login_adgov/check_session.php';
include '../php/config.php';
include 'sipil_sidebar.php';

if ($_SESSION['role'] !== 'sipil') {
    header('Location: ../login_adgov/login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: sipil_read_users.php");
    exit();
}

$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: sipil_read_users.php");
    exit();
}

$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $street = mysqli_real_escape_string($conn, $_POST['street']);
    $postalCode = mysqli_real_escape_string($conn, $_POST['postalCode']);
    $occupation = mysqli_real_escape_string($conn, $_POST['occupation']);

    $sql = "UPDATE users SET firstName=?, lastName=?, email=?, phone=?, street=?, postalCode=?, occupation=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $firstName, $lastName, $email, $phone, $street, $postalCode, $occupation, $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Data user berhasil diupdate!";
        header("Location: sipil_read_users.php");
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
    <title>Update User - EcoUrbanCity</title>
    <link rel="stylesheet" href="sipil_create_update.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <?php echo $sidebar; ?>

        <main class="main-content">
            <div class="container">
                <h2><i class="fas fa-user-edit"></i> Update User</h2>

                <?php if (isset($error)): ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="" method="post">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Nama Depan</label>
                        <input type="text" name="firstName" value="<?php echo htmlspecialchars($row['firstName']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Nama Belakang</label>
                        <input type="text" name="lastName" value="<?php echo htmlspecialchars($row['lastName']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-phone"></i> Nomor Telepon</label>
                        <input type="tel" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-map-marker-alt"></i> Alamat</label>
                        <input type="text" name="street" value="<?php echo htmlspecialchars($row['street']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-mail-bulk"></i> Kode Pos</label>
                        <input type="text" name="postalCode" value="<?php echo htmlspecialchars($row['postalCode']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-briefcase"></i> Pekerjaan</label>
                        <input type="text" name="occupation" value="<?php echo htmlspecialchars($row['occupation']); ?>">
                    </div>

                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="sipil_read_users.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>