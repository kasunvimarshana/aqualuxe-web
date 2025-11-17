/**
 * AquaLuxe Theme Dark Mode
 * 
 * This file handles the dark mode toggle functionality with localStorage persistence.
 */

// Document ready function
document.addEventListener('DOMContentLoaded', function() {
    initDarkMode();
});

/**
 * Initialize dark mode functionality
 */
function initDarkMode() {
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    const darkModeClass = 'dark';
    const darkModeStorageKey = 'aqualuxe_dark_mode';
    
    if (!darkModeToggle) return;
    
    // Check for system preference
    const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    // Check localStorage for saved preference
    const savedMode = localStorage.getItem(darkModeStorageKey);
    
    // Apply dark mode if saved preference is dark or if system prefers dark and no saved preference
    if (savedMode === 'dark' || (savedMode === null && prefersDarkMode)) {
        document.documentElement.classList.add(darkModeClass);
        darkModeToggle.checked = true;
    } else {
        document.documentElement.classList.remove(darkModeClass);
        darkModeToggle.checked = false;
    }
    
    // Toggle dark mode on click
    darkModeToggle.addEventListener('change', function() {
        if (this.checked) {
            document.documentElement.classList.add(darkModeClass);
            localStorage.setItem(darkModeStorageKey, 'dark');
            
            // Dispatch event for other components to react to dark mode change
            document.dispatchEvent(new CustomEvent('darkModeChange', { detail: { isDark: true } }));
        } else {
            document.documentElement.classList.remove(darkModeClass);
            localStorage.setItem(darkModeStorageKey, 'light');
            
            // Dispatch event for other components to react to dark mode change
            document.dispatchEvent(new CustomEvent('darkModeChange', { detail: { isDark: false } }));
        }
    });
    
    // Listen for system preference changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        // Only apply system preference if user hasn't set a preference
        if (localStorage.getItem(darkModeStorageKey) === null) {
            if (e.matches) {
                document.documentElement.classList.add(darkModeClass);
                darkModeToggle.checked = true;
            } else {
                document.documentElement.classList.remove(darkModeClass);
                darkModeToggle.checked = false;
            }
        }
    });
    
    // Make toggle visible after initialization to prevent flash
    darkModeToggle.parentElement.classList.remove('invisible');
    darkModeToggle.parentElement.classList.add('visible');
}

// Listen for dark mode changes from other scripts
document.addEventListener('darkModeChange', function(e) {
    console.log('Dark mode changed:', e.detail.isDark);
    // You can add additional functionality here
});

// Export functions for use in other files
export {
    initDarkMode
};