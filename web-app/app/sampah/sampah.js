document.addEventListener('DOMContentLoaded', function() {
    const scheduleData = [];

    const scheduleCardsContainer = document.getElementById('scheduleCards');

    scheduleData.forEach(schedule => {
        const card = document.createElement('div');
        card.className = 'card';
        card.innerHTML = `
            <div class="card-content">
                <div>
                    <i class="fas fa-map-marker-alt icon-location card-icon"></i>
                    <span>${schedule.area}</span>
                </div>
                <div>
                    <i class="fas fa-clock card-icon"></i>
                    <span>${schedule.time}</span>
                </div>
                <div>
                    <i class="fas fa-calendar-alt icon-calendar card-icon"></i>
                    <span>Setiap <span class="highlight">${schedule.days}</span></span>
                </div>
            </div>
        `;
        scheduleCardsContainer.appendChild(card);
    });
});