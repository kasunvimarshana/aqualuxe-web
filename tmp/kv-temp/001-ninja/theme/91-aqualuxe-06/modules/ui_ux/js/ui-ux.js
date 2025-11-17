// UI/UX Module - Main JavaScript File
// Note: Dependencies should be loaded via WordPress enqueue system

(function($) {
    'use strict';
    
    console.log('UI/UX module loaded.');
    
    // Check if THREE.js is available
    if (typeof THREE !== 'undefined') {
        console.log('THREE.js is available');
        // Initialize Three.js components here
    } else {
        console.warn('THREE.js not loaded');
    }
    
    // Check if GSAP is available
    if (typeof gsap !== 'undefined') {
        console.log('GSAP is available');
        // Initialize GSAP animations here
    } else {
        console.warn('GSAP not loaded');
    }
    
    // Check if D3 is available
    if (typeof d3 !== 'undefined') {
        console.log('D3.js is available');
        // Initialize D3 components here
    } else {
        console.warn('D3.js not loaded');
    }
    
})(jQuery);
