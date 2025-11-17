/**
 * Preloader Module
 *
 * This file contains the JavaScript code for the preloader functionality.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get preloader element
const preloader = document.getElementById('preloader');

// Hide preloader when page is loaded
const hidePreloader = () => {
    if (!preloader) return;
    
    // Add fade-out class
    preloader.classList.add('fade-out');
    
    // Remove preloader after animation
    setTimeout(() => {
        preloader.style.display = 'none';
        document.body.classList.remove('preloader-active');
    }, 500);
};

// Initialize preloader functionality
const initPreloader = () => {
    if (!preloader) return;
    
    // Add active class to body
    document.body.classList.add('preloader-active');
    
    // Hide preloader when page is loaded
    if (document.readyState === 'complete') {
        hidePreloader();
    } else {
        window.addEventListener('load', hidePreloader);
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', initPreloader);

// Export module
export default {
    initPreloader,
    hidePreloader
};