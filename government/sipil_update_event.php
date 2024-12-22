<?php
include '../login_adgov/check_session.php';
include '../php/config.php';
include 'sipil_sidebar.php';

if ($_SESSION['role'] !== 'sipil') {
    header('Location: ../login_adgov/login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: sipil_read_events.php");
    exit();
}

$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: sipil_read_events.php");
    exit();
}

$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $event_date = mysqli_real_escape_string($conn, $_POST['event_date']);
    $event_time = mysqli_real_escape_string($conn, $_POST['event_time']);
    $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);

    $sql = "UPDATE events SET title=?, description=?, image_url=?, event_date=?, event_time=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $title, $description, $image_url, $event_date, $event_time, $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Event berhasil diupdate!";
        header("Location: sipil_read_events.php");
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
    <title>Update Event - EcoUrbanCity</title>
    <link rel="stylesheet" href="sipil_crud.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <?php echo $sidebar; ?>

        <main class="main-content">
            <div class="container">
                <div class="header">
                    <h2><i class="fas fa-edit"></i> Update Event</h2>
                    <a href="sipil_read_events.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <?php if (isset($error)): ?>
                    <div class="message error">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="post" class="form">
                    <div class="form-group">
                        <label for="title">
                            <i class="fas fa-heading"></i> Judul Event
                        </label>
                        <input type="text"
                               id="title"
                               name="title"
                               value="<?php echo htmlspecialchars($row['title']); ?>"
                               required
                               class="form-control"
                               placeholder="Masukkan judul event">
                    </div>

                    <div class="form-group">
                        <label for="description">
                            <i class="fas fa-align-left"></i> Deskripsi
                        </label>
                        <textarea id="description"
                                  name="description"
                                  required
                                  class="form-control"
                                  rows="4"
                                  placeholder="Masukkan deskripsi event"><?php echo htmlspecialchars($row['description']); ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="event_date">
                                <i class="fas fa-calendar"></i> Tanggal Event
                            </label>
                            <input type="date"
                                   id="event_date"
                                   name="event_date"
                                   value="<?php echo $row['event_date']; ?>"
                                   required
                                   class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="event_time">
                                <i class="fas fa-clock"></i> Waktu Event
                            </label>
                            <input type="time"
                                   id="event_time"
                                   name="event_time"
                                   value="<?php echo $row['event_time']; ?>"
                                   required
                                   class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="image_url">
                            <i class="fas fa-image"></i> URL Gambar
                        </label>
                        <input type="url"
                               id="image_url"
                               name="image_url"
                               value="<?php echo htmlspecialchars($row['image_url']); ?>"
                               required
                               class="form-control"
                               placeholder="Masukkan URL gambar">
                    </div>

                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="sipil_read_events.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>