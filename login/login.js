document.addEventListener('DOMContentLoaded', function () {
  const loginForm = document.getElementById('loginForm');

  if (loginForm) {
      // Tangkap event submit
      loginForm.addEventListener('submit', function (e) {
          e.preventDefault();

          // Ambil nilai input email dan password
          const email = document.getElementById('email').value.trim();
          const password = document.getElementById('password').value.trim();

          // Validasi input kosong
          if (email === '' || password === '') {
              alert('Email dan Password harus diisi!');
              return;
          }

          // Kirim data ke server
          fetch('login.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
          })
          .then(response => response.text())
          .then(data => {
              console.log('Response dari server:', data);

              // Redirect ke dashboard jika login sukses
              if (data.includes('success')) {
                  window.location.href = 'dashboard.html';
              } else {
                  alert('Login gagal. Periksa email atau password Anda.');
              }
          })
          .catch(error => {
              console.error('Error:', error);
              alert('Terjadi kesalahan. Coba lagi nanti.');
          });
      });
  }
});
