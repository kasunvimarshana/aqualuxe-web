/**
 * Scroll to Top Module
 *
 * This file contains the JavaScript code for the scroll to top functionality.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get back to top button
const backToTopButton = document.getElementById('back-to-top');

// Show/hide back to top button based on scroll position
const toggleBackToTopButton = () => {
    if (!backToTopButton) return;
    
    if (window.scrollY > 300) {
        backToTopButton.classList.add('visible');
    } else {
        backToTopButton.classList.remove('visible');
    }
};

// Scroll to top when button is clicked
const scrollToTop = (e) => {
    e.preventDefault();
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
};

// Initialize scroll to top functionality
const initScrollToTop = () => {
    if (!backToTopButton) return;
    
    // Add event listeners
    window.addEventListener('scroll', toggleBackToTopButton);
    backToTopButton.addEventListener('click', scrollToTop);
    
    // Initial check
    toggleBackToTopButton();
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', initScrollToTop);

// Export module
export default {
    initScrollToTop
};