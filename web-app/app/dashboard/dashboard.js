// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
          target.scrollIntoView({ behavior: 'smooth' });
      } else {
          console.error("Target elemen tidak ditemukan:", this.getAttribute('href'));
      }
  });
});

// Parallax effect on scroll
window.addEventListener('scroll', () => {
  const scrolled = window.pageYOffset;
  const hero = document.querySelector('.hero-content');
  if (hero) {
      hero.style.transform = `translateY(${scrolled * 0.4}px)`;
  }
});

// Scroll reveal animation
const revealElements = document.querySelectorAll('.event-card, .service-card, .quote-content, .report-form');
const revealElementsOnScroll = () => {
  revealElements.forEach(element => {
      const elementTop = element.getBoundingClientRect().top;
      const elementBottom = element.getBoundingClientRect().bottom;
      const isVisible = (elementTop < window.innerHeight - 100) && (elementBottom > 0);
      if (isVisible) {
          element.classList.add('reveal');
      }
  });
};

window.addEventListener('scroll', revealElementsOnScroll);
revealElementsOnScroll(); // Call once to check initial state

// Add active class to navigation links on scroll
const sections = document.querySelectorAll('section');
const navLinks = document.querySelectorAll('nav ul li a');

window.addEventListener('scroll', () => {
  let current = '';
  sections.forEach(section => {
      const sectionTop = section.offsetTop;
      const sectionHeight = section.clientHeight;
      if (pageYOffset >= sectionTop - sectionHeight / 3) {
          current = section.getAttribute('id');
      }
  });

  navLinks.forEach(link => {
      link.classList.remove('active');
      if (link.getAttribute('href').slice(1) === current) {
          link.classList.add('active');
      }
  });
});

// Ensure DOM is ready before executing any script
document.addEventListener("DOMContentLoaded", function () {
  if (typeof feather !== "undefined") {
      feather.replace();
  }

  initializeModal();
  initializeFileUpload();
  initializeRealTimeData();
  initializeForm();
});

// Modal functionality
function initializeModal() {
  const modal = document.getElementById("eventModal");
  const closeBtn = document.querySelector(".close");

  if (modal && closeBtn) {
      closeBtn.onclick = () => (modal.style.display = "none");
      window.onclick = (event) => {
          if (event.target === modal) {
              modal.style.display = "none";
          }
      };
  }
}

// Event details functionality
async function showEventDetails(eventId) {
  const modal = document.getElementById("eventModal");
  const content = document.getElementById("eventDetailsContent");

  if (!modal || !content) return;

  try {
      // Menampilkan loading state
      content.innerHTML = '<p class="loading">Memuat data...</p>';
      modal.style.display = "block";

      // Mengambil data dari endpoint PHP
      const response = await fetch(`../php/get_event_details.php?id=${eventId}`);

      if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
      }

      const event = await response.json();

      // Format tanggal ke format Indonesia
      const eventDate = new Date(event.event_date);
      const formattedDate = eventDate.toLocaleDateString('id-ID', {
          weekday: 'long',
          year: 'numeric',
          month: 'long',
          day: 'numeric'
      });

      // Memperbarui konten modal dengan data event
      content.innerHTML = `
          <div class="modal-header">
              <h2>${event.title}</h2>
          </div>
          <div class="modal-body">
              <div class="event-image">
                  <img src="${event.image_url}" alt="${event.title}" class="event-detail-img">
              </div>
              <div class="event-info">
                  <p><strong>Tanggal:</strong> ${formattedDate}</p>
                  <p><strong>Waktu:</strong> ${event.event_time}</p>
              </div>
              <div class="event-description">
                  <p>${event.description}</p>
              </div>
          </div>
      `;
  } catch (error) {
      console.error("Error fetching event details:", error);
      content.innerHTML = `
          <div class="error-message">
              <p>Gagal memuat detail event. Silakan coba lagi nanti.</p>
          </div>
      `;
  }
}

// Memastikan fungsi showEventDetails tersedia secara global
window.showEventDetails = showEventDetails;

// Inisialisasi modal saat dokumen dimuat
document.addEventListener("DOMContentLoaded", function() {
  const modal = document.getElementById("eventModal");
  const closeBtn = document.querySelector(".close");

  if (modal && closeBtn) {
      // Menutup modal saat tombol close diklik
      closeBtn.onclick = () => {
          modal.style.display = "none";
      };

      // Menutup modal saat mengklik di luar modal
      window.onclick = (event) => {
          if (event.target === modal) {
              modal.style.display = "none";
          }
      };
  }
});

// File upload functionality
function initializeFileUpload() {
  const uploadArea = document.getElementById("uploadArea");
  const fileInput = document.getElementById("image_file");
  const previewContainer = document.getElementById("preview-container");

  if (!uploadArea || !fileInput || !previewContainer) return;

  uploadArea.onclick = () => fileInput.click();

  uploadArea.ondragover = (e) => {
      e.preventDefault();
      uploadArea.classList.add("drag-over");
  };

  uploadArea.ondragleave = () => {
      uploadArea.classList.remove("drag-over");
  };

  uploadArea.ondrop = (e) => {
      e.preventDefault();
      uploadArea.classList.remove("drag-over");

      const files = e.dataTransfer.files;
      if (files.length) {
          fileInput.files = files;
          updateUploadPreview(files[0]);
      }
  };

  fileInput.onchange = (e) => {
      if (e.target.files.length) {
          updateUploadPreview(e.target.files[0]);
      }
  };
}

function updateUploadPreview(file) {
  const uploadArea = document.getElementById("uploadArea");
  const previewContainer = document.getElementById("preview-container");
  if (!uploadArea || !previewContainer) return;

  const reader = new FileReader();
  reader.onload = (e) => {
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

function removeImage() {
  const previewContainer = document.getElementById("preview-container");
  const fileInput = document.getElementById("image_file");

  if (!previewContainer || !fileInput) return;

  previewContainer.innerHTML = `
      <div class="preview-content">
          <p class="placeholder-text">Drag and drop photo here or <span class="choose-photo">choose photo</span></p>
          <p class="file-info">Format yang didukung: JPG, PNG, GIF (Ukuran maks: 5 MB)</p>
      </div>
  `;
  fileInput.value = '';
}

// Real-time data updates
function initializeRealTimeData() {
  function updateData() {
      const trafficStatus = document.getElementById("traffic-status");
      const airQuality = document.getElementById("air-quality");

      if (trafficStatus) {
          trafficStatus.textContent = "Memuat...";
          setTimeout(() => {
              trafficStatus.textContent = "Normal";
              trafficStatus.className = "status-badge success";
          }, 1000);
      }

      if (airQuality) {
          airQuality.textContent = "Memuat...";
          setTimeout(() => {
              airQuality.textContent = "Baik";
              airQuality.className = "status-badge success";
          }, 1000);
      }
  }

  updateData();
  setInterval(updateData, 300000); // Update every 5 minutes
}

// Form submission
function initializeForm() {
  const form = document.getElementById("infrastructureForm");
  if (!form) return;

  form.onsubmit = async (e) => {
    e.preventDefault();

    // Validasi kategori
    const categorySelect = form.querySelector('select[name="category"]');
    if (!categorySelect || categorySelect.value.trim() === "") {
      alert("Silakan pilih kategori!");
      return;
    }

    // Validasi deskripsi
    const descriptionTextarea = form.querySelector('textarea[name="description"]');
    if (!descriptionTextarea || descriptionTextarea.value.trim() === "") {
      alert("Deskripsi masalah tidak boleh kosong!");
      return;
    }

    // Validasi file gambar
    const fileInput = form.querySelector('input[name="image_file"]');
    if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
      alert("Silakan pilih gambar!");
      return;
    }

    const submitButton = form.querySelector('button[type="submit"]');

    try {
      if (submitButton) {
        submitButton.disabled = true;
        submitButton.textContent = "Mengirim...";
      }

      const formData = new FormData(form);

      // Tambahkan kategori ke formData
      formData.append('category', categorySelect.value);

      // Kirim ke endpoint PHP yang sama
      const response = await fetch(window.location.href, {
        method: "POST",
        body: formData
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const result = await response.json();

      if (result.success) {
        alert(result.message);
        form.reset();

        // Reset preview gambar
        const previewContainer = document.getElementById("preview-container");
        if (previewContainer) {
          previewContainer.innerHTML = `
            <div class="preview-content">
              <p class="placeholder-text">Drag and drop photo here or <span class="choose-photo">choose photo</span></p>
              <p class="file-info">Format yang didukung: JPG, PNG, GIF (Ukuran maks: 5 MB)</p>
            </div>
          `;
        }
      } else {
        throw new Error(result.message || 'Gagal mengirim laporan');
      }

    } catch (error) {
      console.error("Error:", error);
      alert(error.message || "Gagal mengirim laporan. Silakan coba lagi nanti.");
    } finally {
      if (submitButton) {
        submitButton.disabled = false;
        submitButton.textContent = "Kirim Laporan";
      }
    }
  };
}

// File upload preview
function initializeFileUpload() {
  const uploadArea = document.getElementById('uploadArea');
  const fileInput = document.getElementById('image_file');
  const previewContainer = document.getElementById('preview-container');

  if (!uploadArea || !fileInput || !previewContainer) return;

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
}

// Ensure all functions are initialized when the DOM is ready
document.addEventListener("DOMContentLoaded", function () {
  if (typeof feather !== "undefined") {
    feather.replace();
  }

  initializeModal();
  initializeFileUpload();
  initializeRealTimeData();
  initializeForm();
});

// Expose necessary functions globally
window.showEventDetails = showEventDetails;
window.removeImage = removeImage;
