/**
 * AquaLuxe Theme Dark Mode
 *
 * Handles dark mode functionality and persistence.
 */

// This script runs immediately to prevent FOUC (Flash of Unstyled Content)
(function() {
    // Check for saved theme preference or use OS preference
    const darkModeEnabled = 
        localStorage.getItem('aqualuxe_dark_mode') === 'true' || 
        (!localStorage.getItem('aqualuxe_dark_mode') && 
         window.matchMedia('(prefers-color-scheme: dark)').matches);
    
    // Apply theme immediately
    if (darkModeEnabled) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
})();