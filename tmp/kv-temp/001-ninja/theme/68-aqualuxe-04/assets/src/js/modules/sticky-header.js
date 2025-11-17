/**
 * Sticky Header Module
 *
 * This file contains the JavaScript code for the sticky header functionality.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get header element
const header = document.querySelector('.site-header');
const headerHeight = header ? header.offsetHeight : 0;
let lastScrollTop = 0;
let ticking = false;

// Handle sticky header
const handleStickyHeader = () => {
    if (!header || !header.classList.contains('sticky-header')) return;
    
    const scrollTop = window.scrollY || document.documentElement.scrollTop;
    
    // Add sticky class when scrolled down
    if (scrollTop > headerHeight) {
        header.classList.add('is-sticky');
        
        // Hide header when scrolling down, show when scrolling up
        if (scrollTop > lastScrollTop && scrollTop > headerHeight * 2) {
            header.classList.add('is-hidden');
        } else {
            header.classList.remove('is-hidden');
        }
    } else {
        header.classList.remove('is-sticky');
        header.classList.remove('is-hidden');
    }
    
    lastScrollTop = scrollTop;
    ticking = false;
};

// Initialize sticky header functionality
const initStickyHeader = () => {
    if (!header || !header.classList.contains('sticky-header')) return;
    
    // Add event listener for scroll
    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                handleStickyHeader();
                ticking = false;
            });
            ticking = true;
        }
    });
    
    // Initial check
    handleStickyHeader();
    
    // Add padding to body to prevent content jump
    document.body.style.paddingTop = headerHeight + 'px';
    
    // Update padding on window resize
    window.addEventListener('resize', () => {
        const newHeaderHeight = header.offsetHeight;
        document.body.style.paddingTop = newHeaderHeight + 'px';
    });
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', initStickyHeader);

// Export module
export default {
    initStickyHeader
};