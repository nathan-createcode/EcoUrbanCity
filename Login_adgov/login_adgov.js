// Ambil elemen input
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('password');

// Saat halaman dimuat, cek apakah pengguna sudah login sebelumnya
window.onload = function () {
    // Jangan isi form jika pengguna sudah login
    if (sessionStorage.getItem('loggedIn') === 'true') {
        window.location.href = 'dashboard.php'; // Redirect ke dashboard jika sudah login
    }
    // Isi form dengan data dari localStorage (hanya jika belum login)
    if (!sessionStorage.getItem('loggedIn')) {
        if (localStorage.getItem('email')) {
            emailInput.value = localStorage.getItem('email');
        }
        if (localStorage.getItem('password')) {
            passwordInput.value = localStorage.getItem('password');
        }
    }
};

window.onload = function() {
  // Menghapus data form setelah logout atau refresh
  document.getElementById('email').value = '';
  document.getElementById('password').value = '';

  // Cek jika pengguna sudah login dan arahkan ke dashboard
  if (sessionStorage.getItem('loggedIn') === 'true') {
      window.location.href = 'login_adgov.html'; // Atur ke halaman dashboard yang sesuai
  }
};

// Simpan email dan password ke localStorage jika login berhasil
function saveLoginData() {
    localStorage.setItem('email', emailInput.value);
    localStorage.setItem('password', passwordInput.value);
}

// Tambahkan event listener untuk form submit
document.getElementById('loginForm').addEventListener('submit', function (event) {
    saveLoginData(); // Simpan data login ke localStorage
});

// Pastikan untuk menghapus localStorage setelah logout
function logout() {
    localStorage.removeItem('email');
    localStorage.removeItem('password');
    sessionStorage.removeItem('loggedIn'); // Hapus status login dari sessionStorage
}
