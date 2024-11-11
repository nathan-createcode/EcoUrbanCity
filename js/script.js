document.addEventListener('DOMContentLoaded', function() {
    // Initialize Feather Icons
    feather.replace();

    // Fungsi untuk update data secara real-time (simulasi)
    function updateDashboard() {
        // Update status lalu lintas
        const trafficStatus = document.querySelector('.card:nth-child(1) .status');
        const trafficStates = ['normal', 'warning', 'danger'];
        const trafficMessages = ['Lancar', 'Kepadatan Sedang', 'Padat'];
        
        setInterval(() => {
            const randomIndex = Math.floor(Math.random() * 3);
            if (trafficStatus) {
                trafficStatus.className = `status status-${trafficStates[randomIndex]}`;
                trafficStatus.textContent = trafficMessages[randomIndex];
            }
        }, 5000);

        // Update kualitas udara
        const airQuality = document.querySelector('.card:nth-child(2) .status');
        setInterval(() => {
            const aqi = Math.floor(Math.random() * 100) + 1;
            const status = aqi <= 50 ? 'normal' : aqi <= 100 ? 'warning' : 'danger';
            if (airQuality) {
                airQuality.className = `status status-${status}`;
                airQuality.textContent = aqi <= 50 ? 'Baik' : aqi <= 100 ? 'Sedang' : 'Buruk';
            }
        }, 8000);
    }

    // Jalankan update dashboard
    updateDashboard();

    // Handle form submission
    const reportForm = document.getElementById('reportForm');
    if (reportForm) {
        reportForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Laporan berhasil dikirim!');
            this.reset();
        });
    }

    // Add smooth scrolling to all links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Navbar functionality
    const hamburger = document.querySelector('.hamburger-menu');
    const navLinks = document.querySelector('.nav-links');
    const dropdowns = document.querySelectorAll('.dropdown');
    const navItems = document.querySelectorAll('.nav-link');

    // Toggle hamburger menu
    hamburger.addEventListener('click', function() {
        this.classList.toggle('active');
        navLinks.classList.toggle('active');
    });

    // Dropdown functionality
    dropdowns.forEach(dropdown => {
        const link = dropdown.querySelector('.dropdown-toggle');
        link.addEventListener('click', function(e) {
            e.preventDefault();
            if (window.innerWidth <= 768) {
                this.parentNode.classList.toggle('active');
            } else {
                // Tutup dropdown lain saat membuka yang baru
                dropdowns.forEach(d => {
                    if (d !== this.parentNode) d.classList.remove('active');
                });
                this.parentNode.classList.toggle('active');
            }
        });
    });

    // Tutup dropdown saat mengklik di luar area dropdown
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            dropdowns.forEach(dropdown => dropdown.classList.remove('active'));
        }
    });

    // Set status aktif untuk item navbar
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            if (!this.classList.contains('dropdown-toggle')) {
                e.preventDefault();
                navItems.forEach(navItem => navItem.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });

    // Set status aktif awal berdasarkan halaman saat ini
    function setInitialActiveState() {
        const currentPage = window.location.pathname.split('/').pop() || 'index.html';
        navItems.forEach(item => {
            if (item.getAttribute('data-page') === currentPage.replace('.html', '')) {
                item.classList.add('active');
            }
        });
    }

    setInitialActiveState();
});