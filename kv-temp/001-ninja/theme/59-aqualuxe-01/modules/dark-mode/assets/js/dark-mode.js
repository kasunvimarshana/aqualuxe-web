/**
 * Dark Mode Module
 * 
 * Handles dark mode functionality with persistent user preference.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Check if Alpine.js is loaded
    if (typeof Alpine === 'undefined') {
        console.error('Alpine.js is required for dark mode functionality');
        return;
    }
    
    // Initialize dark mode toggle outside of Alpine for immediate execution
    const darkModeToggle = document.querySelector('.aqualuxe-dark-mode-toggle');
    if (darkModeToggle) {
        // Add accessibility attributes
        const toggleButton = darkModeToggle.querySelector('.aqualuxe-dark-mode-toggle__button');
        if (toggleButton) {
            toggleButton.setAttribute('role', 'switch');
            toggleButton.setAttribute('aria-checked', document.documentElement.classList.contains('dark') ? 'true' : 'false');
        }
    }
    
    // Listen for system preference changes
    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    mediaQuery.addEventListener('change', (e) => {
        // Only update if user hasn't set a preference
        if (!localStorage.getItem('_x_aqualuxe_dark_mode')) {
            if (e.matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            // Update toggle button state
            const toggleButton = document.querySelector('.aqualuxe-dark-mode-toggle__button');
            if (toggleButton) {
                toggleButton.setAttribute('aria-checked', e.matches ? 'true' : 'false');
            }
        }
    });
});

// Add keyboard support for dark mode toggle
document.addEventListener('keydown', function(e) {
    // Alt + D shortcut for dark mode toggle
    if (e.altKey && e.key === 'd') {
        e.preventDefault();
        
        // Get current state
        const isDark = document.documentElement.classList.contains('dark');
        
        // Toggle dark mode
        if (isDark) {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
        
        // Update Alpine.js state if available
        if (typeof Alpine !== 'undefined') {
            const darkModeData = Alpine.store('darkMode');
            if (darkModeData) {
                darkModeData.isDark = !isDark;
            }
        }
        
        // Save preference
        localStorage.setItem('_x_aqualuxe_dark_mode', !isDark);
        
        // Update toggle button state
        const toggleButton = document.querySelector('.aqualuxe-dark-mode-toggle__button');
        if (toggleButton) {
            toggleButton.setAttribute('aria-checked', !isDark ? 'true' : 'false');
        }
    }
});