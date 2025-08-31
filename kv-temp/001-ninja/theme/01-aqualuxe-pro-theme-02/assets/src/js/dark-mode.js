// Dark mode toggle
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('dark-mode-toggle');
    const html = document.documentElement;

    // Check for saved preference
    if (localStorage.getItem('aqualuxe_dark_mode') === 'true') {
        html.classList.add('dark');
    }

    toggleButton.addEventListener('click', function() {
        html.classList.toggle('dark');
        localStorage.setItem('aqualuxe_dark_mode', html.classList.contains('dark'));
    });
});
