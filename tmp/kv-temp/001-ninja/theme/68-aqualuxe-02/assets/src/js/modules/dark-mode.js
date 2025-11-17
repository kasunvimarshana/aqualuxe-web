/**
 * Dark Mode Module
 *
 * This file contains the JavaScript code for the dark mode functionality.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get dark mode toggle button
const darkModeToggle = document.querySelector('.dark-mode-button');
const htmlElement = document.documentElement;

// Check if dark mode is enabled in local storage or system preference
const isDarkMode = () => {
    // Check local storage first
    if (localStorage.getItem('aqualuxe_dark_mode') !== null) {
        return localStorage.getItem('aqualuxe_dark_mode') === 'true';
    }
    
    // Check system preference
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
};

// Set dark mode
const setDarkMode = (isDark) => {
    if (isDark) {
        htmlElement.classList.add('dark-mode');
        localStorage.setItem('aqualuxe_dark_mode', 'true');
        if (darkModeToggle) {
            darkModeToggle.setAttribute('aria-pressed', 'true');
        }
    } else {
        htmlElement.classList.remove('dark-mode');
        localStorage.setItem('aqualuxe_dark_mode', 'false');
        if (darkModeToggle) {
            darkModeToggle.setAttribute('aria-pressed', 'false');
        }
    }
};

// Initialize dark mode
const initDarkMode = () => {
    // Set initial state
    setDarkMode(isDarkMode());
    
    // Add event listener to toggle button
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', () => {
            const isCurrentlyDark = htmlElement.classList.contains('dark-mode');
            setDarkMode(!isCurrentlyDark);
        });
    }
    
    // Listen for system preference changes
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            // Only change if user hasn't manually set a preference
            if (localStorage.getItem('aqualuxe_dark_mode') === null) {
                setDarkMode(e.matches);
            }
        });
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', initDarkMode);

// Export module
export default {
    isDarkMode,
    setDarkMode,
    initDarkMode
};