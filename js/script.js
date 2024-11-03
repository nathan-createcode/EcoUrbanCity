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
      marker.style.opacity = '1';
      marker.style.transition = 'opacity 0.5s ease-out';
      hero.appendChild(marker);

      // Remove the marker after 2 seconds
      setTimeout(() => {
          marker.style.opacity = '0';
          setTimeout(() => {
              hero.removeChild(marker);
          }, 500);
      }, 500); // Update 1: Changed timeout duration to 500ms
  });

  // Add functionality to category buttons
  const categoryButtons = document.querySelectorAll('.category-btn');
  categoryButtons.forEach(button => {
      button.addEventListener('click', function() {
          categoryButtons.forEach(btn => btn.classList.remove('active'));
          this.classList.add('active');
          // Here you would typically filter the initiatives based on the selected category
      });
  });
});