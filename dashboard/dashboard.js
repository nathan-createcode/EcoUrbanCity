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
  const fileInput = document.getElementById("photo");

  if (!uploadArea || !fileInput) return;

  uploadArea.onclick = () => fileInput.click();

  uploadArea.ondragover = (e) => {
      e.preventDefault();
      uploadArea.classList.add("dragover");
  };

  uploadArea.ondragleave = () => {
      uploadArea.classList.remove("dragover");
  };

  uploadArea.ondrop = (e) => {
      e.preventDefault();
      uploadArea.classList.remove("dragover");

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
  if (!uploadArea) return;

  const reader = new FileReader();
  reader.onload = (e) => {
      uploadArea.innerHTML = `
          <div class="preview-container">
              <img src="${e.target.result}" alt="Preview" class="file-preview">
              <p class="file-info">
                  <span class="file-name">${file.name}</span>
                  <button type="button" class="remove-file" onclick="resetUpload()">
                      <i data-feather="x"></i>
                  </button>
              </p>
          </div>
      `;
      feather.replace();
  };
  reader.readAsDataURL(file);
}

function resetUpload() {
  const uploadArea = document.getElementById("uploadArea");
  const fileInput = document.getElementById("photo");

  if (!uploadArea || !fileInput) return;

  fileInput.value = "";
  uploadArea.innerHTML = `
      <i data-feather="upload" class="upload-icon"></i>
      <p>Seret dan lepas foto di sini atau <span class="choose-photo">pilih foto</span></p>
      <p class="file-info">Format yang didukung: JPG, PNG, GIF (Ukuran maks: 5 MB)</p>
  `;
  feather.replace();
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
      const textInput = form.querySelector('input[name="textField"]');
      if (!textInput || textInput.value.trim() === "") {
          alert("Field tidak boleh kosong!");
          return;
      }

      const submitButton = form.querySelector('button[type="submit"]');

      try {
          if (submitButton) {
              submitButton.disabled = true;
              submitButton.textContent = "Mengirim...";
          }

          const formData = new FormData(form);
          const response = await fetch("/api/submit-report", {
              method: "POST",
              body: formData,
          });

          if (!response.ok) throw new Error("Gagal mengirim laporan");

          alert("Laporan berhasil dikirim!");
          form.reset();
          resetUpload();
      } catch (error) {
          console.error("Error:", error);
          alert("Gagal mengirim laporan. Silakan coba lagi nanti.");
      } finally {
          if (submitButton) {
              submitButton.disabled = false;
              submitButton.textContent = "Kirim Laporan";
          }
      }
  };
}

// Expose necessary functions globally
window.showEventDetails = showEventDetails;
window.resetUpload = resetUpload;
