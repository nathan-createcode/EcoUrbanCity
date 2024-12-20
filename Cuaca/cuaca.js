// API key untuk OpenWeatherMap
const API_KEY = '64f4e2d945baed10196604718400c1ff';

// Fungsi untuk mengambil data cuaca
async function getWeatherData(location) {
    try {
        const response = await fetch(`https://api.openweathermap.org/data/2.5/forecast?q=${location}&units=metric&appid=${API_KEY}`);
        if (!response.ok) {
            throw new Error('Tidak dapat mengambil data cuaca');
        }
        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengambil data cuaca');
    }
}

// Fungsi untuk memperbarui UI dengan data cuaca
function updateWeatherUI(data) {
    const today = data.list[0];
    //hilang td
    const nextDays = [];
    const seenDates = new Set();
    for (const item of data.list) {
        const date = new Date(item.dt * 1000).toDateString();
        if (!seenDates.has(date)) {
            seenDates.add(date);
            nextDays.push(item);
        }
        if (nextDays.length === 7) break;
    }

    // Memperbarui kartu cuaca hari ini
    const todayCard = document.querySelector('.card.today');
    if (todayCard) {
        todayCard.querySelector('.location').textContent = data.city.name;
        todayCard.querySelector('.temperature').textContent = `${Math.round(today.main.temp)}°C`;
        
        const weatherIcon = todayCard.querySelector('.weather-icon i');
        if (weatherIcon) {
            weatherIcon.setAttribute('data-lucide', getWeatherIcon(today.weather[0].main));
        }
        
        const detailsSpans = todayCard.querySelectorAll('.details span');
        if (detailsSpans.length >= 3) {
            detailsSpans[0].innerHTML = `<i data-lucide="wind"></i> ${today.wind.speed} km/h`;
            detailsSpans[1].innerHTML = `<i data-lucide="sun"></i> ${getUVIndex(today.main.temp)}`;
            detailsSpans[2].innerHTML = `<i data-lucide="droplets"></i> ${today.main.humidity}%`;
        }
    }

    // Memperbarui kartu cuaca untuk hari-hari berikutnya
    const dayCards = document.querySelectorAll('.weather-cards .card');
    nextDays.forEach((day, index) => { //slice(1)
        const card = dayCards[index];
        if (card) {
            const dayElement = card.querySelector('.day');
            const temperatureElement = card.querySelector('.temperature');
            const weatherIconElement = card.querySelector('.weather-icon i');
            
            if (dayElement) dayElement.textContent = new Date(day.dt * 1000).toLocaleDateString('en-US', { weekday: 'long' });
            if (temperatureElement) temperatureElement.textContent = `${Math.round(day.main.temp)}°C`;
            if (weatherIconElement) weatherIconElement.setAttribute('data-lucide', getWeatherIcon(day.weather[0].main));
        }
    });

    //memperbarui AQI
    if (today.main.aqi) {
        const aqi = calculateAQI(today.main.aqi);
        document.querySelector('.aqi-value').textContent = `${aqi.level} (${aqi.value})`;
        document.querySelector('.progress').style.width = `${aqi.percentage}%`;
    } else {
        document.querySelector('.aqi-value').textContent = 'Data tidak tersedia';
        document.querySelector('.progress').style.width = '0%';
    }

    // Memperbarui metrik
    updateMetric('.metric:nth-child(1) .metric-value', getUVIndex(today.main.temp));
    updateMetric('.metric:nth-child(2) .metric-value', `${today.main.humidity}%`);
    updateMetric('.metric:nth-child(3) .metric-value', `${today.wind.speed} km/h`);
    updateMetric('.metric:nth-child(4) .metric-value', `${Math.round(today.main.feels_like)}°C`);
    updateMetric('.metric:nth-child(5) .metric-value', `${today.visibility / 1000} km`);
    updateMetric('.metric:nth-child(6) .metric-value', `${today.main.pressure} hPa`);

    // Memperbarui ikon
    lucide.createIcons();
}

// Fungsi helper untuk memperbarui metrik
function updateMetric(selector, value) {
    const element = document.querySelector(selector);
    if (element) element.textContent = value;
}

//Fungsi menghitung AQI
function calculateAQI(aqiValue) {
    if (aqiValue <= 50) return { level: 'Good', value: aqiValue, percentage: aqiValue * 2 };
    if (aqiValue <= 100) return { level: 'Moderate', value: aqiValue, percentage: 50 + (aqiValue - 50) };
    if (aqiValue <= 150) return { level: 'Unhealthy for Sensitive Groups', value: aqiValue, percentage: 75 + (aqiValue - 100) / 2 };
    if (aqiValue <= 200) return { level: 'Unhealthy', value: aqiValue, percentage: 87.5 + (aqiValue - 150) / 4 };
    if (aqiValue <= 300) return { level: 'Very Unhealthy', value: aqiValue, percentage: 93.75 + (aqiValue - 200) / 8 };
    return { level: 'Hazardous', value: aqiValue, percentage: 100 };
}

// Fungsi untuk mendapatkan ikon cuaca yang sesuai
function getWeatherIcon(weatherMain) {
    const iconMap = {
        'Clear': 'sun',
        'Clouds': 'cloud',
        'Rain': 'cloud-rain',
        'Snow': 'cloud-snow',
        'Thunderstorm': 'cloud-lightning',
        'Drizzle': 'cloud-drizzle',
        'Mist': 'cloud-fog',
        'Smoke': 'cloud-fog',
        'Haze': 'cloud-fog',
        'Dust': 'wind',
        'Fog': 'cloud-fog',
        'Sand': 'wind',
        'Ash': 'wind',
        'Squall': 'wind',
        'Tornado': 'wind'
    };
    return iconMap[weatherMain] || 'help-circle';
}

//perkiraan UV Index berdasarkan suhu
function getUVIndex(temperature) {
    if (temperature > 30) return 'Very High';
    if (temperature > 25) return 'High';
    if (temperature > 20) return 'Moderate';
    return 'Low';
}

// Inisialisasi peta
let map;
let weatherLayer;
function initMap() {
    map = L.map('map').setView([0, 0], 2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    weatherLayer = L.layerGroup().addTo(map);
}

// Fungsi untuk memperbarui peta
function updateMap(lat, lon, weatherData) {
    if (map) {
        map.setView([lat, lon], 10);
        weatherLayer.clearLayers();

        //menambahkan icon cuaca ke peta
        const today = weatherData.list[0];
        const icon = L.divIcon({
            html: `<i class="weather-icon" data-lucide=""${getWeatherIcon(today.weather[0].main)}"></i`,
            iconSize: [30, 30],
            className: 'weather-map-icon'
        });
        L.marker([lat, lon], {icon: icon}).addTo(weatherLayer);

        //memperbarui ikon lucide di peta
        lucide.createIcons();
    }
}

// Event listener untuk pencarian
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi ikon Lucide
    lucide.createIcons();

    // Inisialisasi peta
    initMap();

    const searchInput = document.getElementById('search');
    searchInput.addEventListener('keypress', async function(e) {
        if (e.key === 'Enter') {
            const location = searchInput.value;
            try {
                const weatherData = await getWeatherData(location);
                if (weatherData) {
                    updateWeatherUI(weatherData);
                    updateMap(weatherData.city.coord.lat, weatherData.city.coord.lon, weatherData);
                }
            } catch (error) {
                console.error('Error updating weather data:', error);
                alert('Terjadi kesalahan saat memperbarui data cuaca. Silakan coba lagi.');
            }
        }
    });

    // Pencarian awal (opsional)
    getWeatherData('Sleman').then(data => {
        if (data) {
            try {
                updateWeatherUI(data);
                updateMap(data.city.coord.lat, data.city.coord.lon, data);
            } catch (error) {
                console.error('Error updating initial weather data:', error);
            }
        }
    }).catch(error => {
        console.error('Error fetching initial weather data:', error);
    });
});