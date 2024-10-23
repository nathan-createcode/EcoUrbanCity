// Mock Transport Map Data
document.addEventListener("DOMContentLoaded", function () {
  const map = document.getElementById("map");
  map.innerHTML = "<p>Loading real-time transport data...</p>";
  setTimeout(() => {
    map.innerHTML = "<p>Real-time map of transport routes will appear here.</p>";
  }, 2000);

  // Mock Air Quality Data
  const airQuality = document.getElementById("airQuality");
  airQuality.innerHTML = "<p>Loading air quality data...</p>";
  setTimeout(() => {
    airQuality.innerHTML = "<p>Current Air Quality Index (AQI): Good (45)</p>";
  }, 2000);
});
