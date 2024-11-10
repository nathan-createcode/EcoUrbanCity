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
            // Here you would typically send the form data to a server
            // For this example, we'll just show an alert
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
});