<?php
include '../login_adgov/check_session.php';
include '../php/config.php';
include 'sipil_sidebar.php';

if ($_SESSION['role'] !== 'sipil') {
    header('Location: ../login_adgov/login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $event_date = mysqli_real_escape_string($conn, $_POST['event_date']);
    $event_time = mysqli_real_escape_string($conn, $_POST['event_time']);
    $upload_dir = "../img_events/"; // Folder tempat menyimpan gambar

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Membuat folder jika belum ada
    }

    // Proses unggah gambar
    $image_file = $_FILES['image_file'];
    if ($image_file['error'] === UPLOAD_ERR_OK) {
        $file_name = basename($image_file['name']);
        $target_file = $upload_dir . $file_name;
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validasi tipe file
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($image_file_type, $allowed_types)) {
            if (move_uploaded_file($image_file['tmp_name'], $target_file)) {
                // Simpan informasi gambar ke database
                $sql = "INSERT INTO events (title, description, image_url, event_date, event_time) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssss", $title, $description, $target_file, $event_date, $event_time);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "Event berhasil ditambahkan!";
                    header("Location: sipil_read_events.php");
                    exit();
                } else {
                    $error = "Error: " . $stmt->error;
                }
            } else {
                $error = "Gagal mengunggah gambar.";
            }
        } else {
            $error = "Format file tidak valid. Hanya JPG, JPEG, PNG, dan GIF yang diizinkan.";
        }
    } else {
        $error = "Terjadi kesalahan saat mengunggah gambar.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Event - EcoUrbanCity</title>
    <link rel="stylesheet" href="sipil_crud.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <?php echo $sidebar; ?>

        <main class="main-content">
            <div class="container">
                <div class="header">
                    <h2><i class="fas fa-plus-circle"></i> Tambah Event</h2>
                    <a href="sipil_read_events.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <?php if (isset($error)): ?>
                    <div class="message error">
                        <?php echo $error; ?>
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
                                  placeholder="Masukkan deskripsi event"></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="event_date">
                                <i class="fas fa-calendar"></i> Tanggal Event
                            </label>
                            <input type="date"
                                   id="event_date"
                                   name="event_date"
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
                   required
                   accept="image/png, image/jpeg, image/jpg, image/gif"
                   style="display: none;">
            <div id="preview-container" class="preview-container">
                <div class="preview-content">
                    <p class="placeholder-text">Drag and drop photo here or <span class="choose-photo">choose photo</span></p>
                    <p class="file-info">Format yang didukung: JPG, PNG, GIF (Ukuran maks: 5 MB)</p>
                </div>
            </div>
        </div>
    </div>
</div>

                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
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
                // Hitung rasio aspek untuk memastikan gambar sesuai container
                const containerWidth = previewContainer.offsetWidth;
                const containerHeight = previewContainer.offsetHeight;
                const imgRatio = this.width / this.height;
                const containerRatio = containerWidth / containerHeight;

                let finalWidth, finalHeight;

                if (imgRatio > containerRatio) {
                    // Gambar lebih lebar
                    finalWidth = containerWidth;
                    finalHeight = containerWidth / imgRatio;
                } else {
                    // Gambar lebih tinggi
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

    // Fungsi untuk menghapus gambar
    window.removeImage = function() {
        previewContainer.innerHTML = `
            <div class="preview-content">
                <p class="placeholder-text">Drag and drop photo here or <span class="choose-photo">choose photo</span></p>
                <p class="file-info">Format yang didukung: JPG, PNG, GIF (Ukuran maks: 5 MB)</p>
            </div>
        `;
        fileInput.value = '';
    };

    // Validasi file
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

    // Event Listeners
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