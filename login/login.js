document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    console.log('Form submitted:', { name, email, password });
});

// Add this to both login.js and registration.js
document.addEventListener('DOMContentLoaded', function() {
    // Add transition effect when leaving page
    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            
            // Fade out current page
            document.querySelector('.container').style.opacity = '0';
            document.querySelector('.container').style.transform = 'translateY(20px)';
            
            // Navigate to new page after animation
            setTimeout(() => {
                window.location.href = href;
            }, 500);
        });
    });
});