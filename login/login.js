document.addEventListener('DOMContentLoaded', function () {
  const loginForm = document.getElementById('loginForm');

  if (loginForm) {
      loginForm.addEventListener('submit', function (e) {
          e.preventDefault(); // Mencegah submit default form

          const emailInput = document.getElementById('email');
          const passwordInput = document.getElementById('password');

          const email = emailInput.value.trim();
          const password = passwordInput.value.trim();

          // Kirim data ke server dengan fetch
          fetch('login.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
          })
          .then(response => response.text()) // Respons dari server
          .then(data => {
              if (data === 'success') {
                  // Redirect ke dashboard jika login berhasil
                  window.location.href = '../dashboard/dashboard.php';
              } else {
                  // Tampilkan alert jika login gagal
                  alert('Login gagal. Periksa email atau password Anda.');
              }
          })
          .catch(error => {
              console.error('Error:', error);
              alert('Terjadi kesalahan. Coba lagi nanti.');
          });
      });
  } else {
      console.error('Form login tidak ditemukan.');
  }
});
