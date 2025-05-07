document.addEventListener('DOMContentLoaded', () => {
    const transportList = document.getElementById('transport-list');

    function fetchTransportData(date) {
        const data = {
            "2024-12-24": [
                { type: "Bus", departure: "10.00 WIB", duration: "30 Menit", arrival: "10.30 WIB", price: 3000 },
                { type: "MRT", departure: "12.00 WIB", duration: "15 Menit", arrival: "12.15 WIB", price: 7000 },
                { type: "LRT", departure: "17.30 WIB", duration: "20 Menit", arrival: "17.50 WIB", price: 5000 },
            ]
        };
        return data[date] || [];
    }

    function renderTransports(date) {
        const data = fetchTransportData(date);
        if (data.length === 0) {
            transportList.innerHTML = '<p>Tidak ada data transportasi.</p>';
            return;
        }

        transportList.innerHTML = data.map(item => `
            <div class="transport-item">
                <div>${item.type}</div>
                <div>${item.departure}</div>
                <div>${item.duration}</div>
                <div>${item.arrival}</div>
                <div>Rp${item.price.toLocaleString()}</div>
            </div>
        `).join('');
    }

    renderTransports('2024-12-24');
});


document.addEventListener('DOMContentLoaded', function () {
    const dateTabs = document.querySelectorAll('.date-tab');

    dateTabs.forEach(tab => {
        tab.addEventListener('click', function () {
            // Ambil tanggal dari teks tombol
            const selectedDate = this.textContent.trim();

            // Lakukan filter berdasarkan tanggal
            const rows = document.querySelectorAll('.transport-table tbody tr');
            rows.forEach(row => {
                const rowDate = row.getAttribute('data-date'); // Pastikan Anda menambahkan atribut data-date di PHP
                if (rowDate === selectedDate) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
});
