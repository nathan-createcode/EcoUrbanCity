document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    console.log('Form submitted:', { name, email, password });
});

// Add this to both login.js and registration.js
document.addEventListener('DOMContentLoaded', function () {
  const container = document.querySelector('body');

  // Tambahkan kelas untuk memulai dengan animasi tampil
  container.classList.add('page-transition', 'show');

  // Tangani klik pada link navigasi
  document.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', function (e) {
          const href = this.getAttribute('href');
          const isSameDomain = href.startsWith('/') || href.startsWith('./') || href.startsWith('../');

          if (isSameDomain) {
              e.preventDefault(); // Cegah navigasi langsung

              // Tambahkan efek animasi "hide" sebelum berpindah halaman
              container.classList.remove('show');
              container.classList.add('hide');

              // Setelah animasi selesai, pindah ke halaman baru
              setTimeout(() => {
                  window.location.href = href;
              }, 600); // Durasi harus sama dengan CSS transition
          }
      });
  });
});