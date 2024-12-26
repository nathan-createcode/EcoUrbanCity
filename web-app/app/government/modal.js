// modal.js

// Perbaikan fungsi getImagePath untuk memastikan path yang benar
function getImagePath(imageName) {
  const currentPath = window.location.pathname;
  let department = '';

  // Tentukan department berdasarkan URL
  if (currentPath.includes('lingkungan')) {
      department = 'lingkungan_reports_img';
  } else if (currentPath.includes('perhubungan')) {
      department = 'perhubungan_reports_img';
  } else if (currentPath.includes('sipil')) {
      department = 'sipil_reports_img';
  }

  // Pastikan department tidak kosong
  if (!department) {
      console.error('Department tidak dapat ditentukan');
      return '../img/image-not-found.png';
  }

  return `../${department}/${imageName}`;
}

// Perbaikan fungsi openImageModal dengan error handling yang lebih baik
function openImageModal(imageName) {
  const modal = document.getElementById("imageModal");
  const modalImg = document.getElementById("modalImage");

  if (!modal || !modalImg) {
      console.error("Elemen modal tidak ditemukan");
      return;
  }

  if (!imageName) {
      console.error("Nama file gambar tidak valid");
      return;
  }

  const imagePath = getImagePath(imageName);
  modal.style.display = "block";

  // Tambahkan loading indicator
  modalImg.src = '../img/loading.gif'; // Tambahkan gambar loading

  // Buat Image object untuk pre-load
  const img = new Image();
  img.onload = function() {
      modalImg.src = imagePath;
      modalImg.style.opacity = "1";
  };

  img.onerror = function() {
      modalImg.src = '../img/image-not-found.png';
      console.error("Gambar tidak dapat dimuat:", imagePath);
  };

  img.src = imagePath;
}

// Inisialisasi modal saat dokumen dimuat
function initializeImageModal() {
  const modal = document.getElementById("imageModal");
  const closeBtn = document.querySelector(".close-modal");

  if (!modal || !closeBtn) {
      console.error("Elemen modal tidak ditemukan");
      return;
  }

  // Tutup modal saat tombol close diklik
  closeBtn.onclick = function() {
      modal.style.display = "none";
  };

  // Tutup modal saat mengklik di luar gambar
  window.onclick = function(event) {
      if (event.target === modal) {
          modal.style.display = "none";
      }
  };

  // Tutup modal dengan tombol Escape
  document.addEventListener('keydown', function(event) {
      if (event.key === "Escape") {
          modal.style.display = "none";
      }
  });

  // Tambahkan handler error untuk gambar
  document.querySelectorAll('.report-image').forEach(img => {
      img.onerror = function() {
          this.onerror = null;
          this.src = '../img/image-not-found.png'; // Ganti dengan path gambar default Anda
          console.error("Gambar tidak dapat dimuat:", this.src);
      };
  });
}

// Ekspos fungsi ke global scope
window.openImageModal = openImageModal;

// Jalankan inisialisasi saat dokumen dimuat
document.addEventListener('DOMContentLoaded', initializeImageModal);