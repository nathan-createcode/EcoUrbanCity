document.addEventListener('DOMContentLoaded', function() {
  const hero = document.querySelector('.hero');

  // Add click event listener to the hero section
  hero.addEventListener('click', function(e) {
      // Create a marker at click position
      const marker = document.createElement('div');
      marker.style.position = 'absolute';
      marker.style.left = (e.offsetX - 5) + 'px';
      marker.style.top = (e.offsetY - 5) + 'px';
      marker.style.width = '10px';
      marker.style.height = '10px';
      marker.style.borderRadius = '50%';
      marker.style.backgroundColor = '#e31b23';
      hero.appendChild(marker);
  });
});
