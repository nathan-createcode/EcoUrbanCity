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

    // Handle image upload
    $image_url = $row['image_url']; // Default to existing image URL

    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = "../img_events_update/";

        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Generate unique filename
        $file_extension = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;

        // Validate file type
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['image_file']['type'], $allowed_types)) {
            $_SESSION['error'] = "Format file tidak valid. Hanya JPG, JPEG, PNG, dan GIF yang diizinkan.";
            header("Location: sipil_update_event.php?id=" . $id);
            exit();
        }

        // Validate file size (5MB max)
        if ($_FILES['image_file']['size'] > 5 * 1024 * 1024) {
            $_SESSION['error'] = "Ukuran file terlalu besar (maksimal 5MB)";
            header("Location: sipil_update_event.php?id=" . $id);
            exit();
        }

        // Move uploaded file
        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $upload_path)) {
            // Store the new path in database
            $image_url = $upload_path;
        } else {
            $_SESSION['error'] = "Gagal mengunggah file";
            header("Location: sipil_update_event.php?id=" . $id);
            exit();
        }
    }

    // Update database
    $sql = "UPDATE events SET title=?, description=?, image_url=?, event_date=?, event_time=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $title, $description, $image_url, $event_date, $event_time, $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Event berhasil diupdate!";
        header("Location: sipil_read_events.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
        header("Location: sipil_update_event.php?id=" . $id);
        exit();
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

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="message error">
                        <?php
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>

                <form action="" method="post" enctype="multipart/form-data" class="form">
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
                        <label for="image_file">
                            <i class="fas fa-image"></i> Unggah Gambar
                        </label>
                        <div class="upload-container">
                            <div id="uploadArea" class="upload-area">
                                <input type="file"
                                       id="image_file"
                                       name="image_file"
                                       accept="image/png, image/jpeg, image/jpg, image/gif"
                                       style="display: none;">
                                <div id="preview-container" class="preview-container">
                                    <?php if ($row['image_url']): ?>
                                        <div class="image-preview">
                                            <img src="<?php echo htmlspecialchars($row['image_url']); ?>"
                                                 alt="Current Event Image"
                                                 class="uploaded-image">
                                            <button type="button" class="remove-image" onclick="removeImage()">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <div class="preview-content">
                                            <p class="placeholder-text">Drag and drop photo here or <span class="choose-photo">choose photo</span></p>
                                            <p class="file-info">Format yang didukung: JPG, PNG, GIF (Ukuran maks: 5 MB)</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.getElementById('uploadArea');
            const fileInput = document.getElementById('image_file');
            const previewContainer = document.getElementById('preview-container');

            function previewImage(file) {
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.onload = function() {
                        const containerWidth = previewContainer.offsetWidth;
                        const containerHeight = previewContainer.offsetHeight;
                        const imgRatio = this.width / this.height;
                        const containerRatio = containerWidth / containerHeight;

                        let finalWidth, finalHeight;

                        if (imgRatio > containerRatio) {
                            finalWidth = containerWidth;
                            finalHeight = containerWidth / imgRatio;
                        } else {
                            finalHeight = containerHeight;
                            finalWidth = containerHeight * imgRatio;
                        }

                        previewContainer.innerHTML = `
                            <div class="image-preview">
                                <img
                                    src="${e.target.result}"
                                    alt="Preview"
                                    class="uploaded-image"
                                    style="width: ${finalWidth}px; height: ${finalHeight}px;"
                                >
                                <button type="button" class="remove-image" onclick="removeImage()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `;
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }

            window.removeImage = function() {
                previewContainer.innerHTML = `
                    <div class="preview-content">
                        <p class="placeholder-text">Drag and drop photo here or <span class="choose-photo">choose photo</span></p>
                        <p class="file-info">Format yang didukung: JPG, PNG, GIF (Ukuran maks: 5 MB)</p>
                    </div>
                `;
                fileInput.value = '';
            };

            function validateFile(file) {
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Format file tidak valid. Hanya JPG, JPEG, PNG, dan GIF yang diizinkan.');
                    return false;
                }
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file melebihi 5 MB. Silakan pilih file yang lebih kecil.');
                    return false;
                }
                return true;
            }

            uploadArea.addEventListener('click', () => fileInput.click());

            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('drag-over');
            });

            uploadArea.addEventListener('dragleave', () => {
                uploadArea.classList.remove('drag-over');
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('drag-over');
                const file = e.dataTransfer.files[0];
                if (file && validateFile(file)) {
                    fileInput.files = e.dataTransfer.files;
                    previewImage(file);
                }
            });

            fileInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file && validateFile(file)) {
                    previewImage(file);
                }
            });
        });
    </script>
</body>
</html>

