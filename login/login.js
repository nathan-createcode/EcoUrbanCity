document.addEventListener('DOMContentLoaded', function () {
  const loginForm = document.getElementById('loginForm');

  if (loginForm) {
      loginForm.addEventListener('submit', async function (e) {
          e.preventDefault(); // Mencegah submit default form

          const emailInput = document.getElementById('email');
          const passwordInput = document.getElementById('password');

          const email = emailInput.value.trim();
          const password = passwordInput.value.trim();

          // Validasi input kosong sebelum mengirim data
          if (!email || !password) {
              alert('Email dan password tidak boleh kosong.');
              return;
          }

          // try {
          //     // Kirim data ke server dengan fetch
          //     const response = await fetch('login.php', {
          //         method: 'POST',
          //         headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          //         body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
          //     });

          //     const data = await response.text();

          //     // Penanganan respons dari server
          //     if (response.ok) {
          //         if (data === 'success') {
          //             // Redirect ke dashboard jika login berhasil
          //             window.location.href = '../dashboard/dashboard.php';
          //         } else {
          //             alert('Login gagal. Periksa email atau password Anda.');
          //         }
          //     } else {
          //         alert(`Terjadi kesalahan: ${response.status} - ${response.statusText}`);
          //     }
          // } catch (error) {
          //     console.error('Terjadi kesalahan jaringan:', error);
          //     alert('Terjadi kesalahan jaringan. Coba lagi nanti.');
          // }
      });
  } else {
      console.error('Form login tidak ditemukan.');
  }
});
