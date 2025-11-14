// Simple JavaScript for the PHP project
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Simple PHP Project loaded successfully!');
    
    // Add some interactivity to forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            console.log('Form submitted:', form.action || 'current page');
        });
    });
    
    // Add click tracking to navigation links
    const navLinks = document.querySelectorAll('nav a');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            console.log('Navigation:', this.href);
        });
    });
    
    // Simple form validation enhancement
    const inputs = document.querySelectorAll('input[required], textarea[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.style.borderColor = '#e53e3e';
            } else {
                this.style.borderColor = '#48bb78';
            }
        });
    });
});

// Utility function for API calls
function callAPI(action) {
    fetch(`api.php?action=${action}`)
        .then(response => response.json())
        .then(data => {
            console.log('API Response:', data);
            alert(JSON.stringify(data, null, 2));
        })
        .catch(error => {
            console.error('API Error:', error);
            alert('Error calling API');
        });
}