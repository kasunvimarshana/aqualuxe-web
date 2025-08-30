/**
 * AquaLuxe Theme Lazy Loading JavaScript
 * 
 * Handles lazy loading of images and other media
 */

(function() {
    'use strict';

    /**
     * Initialize lazy loading functionality
     */
    function init() {
        setupLazyImages();
        setupLazyBackgrounds();
        setupLazyIframes();
    }

    /**
     * Lazy Loading for Images
     */
    function setupLazyImages() {
        // Check if IntersectionObserver is supported
        if (!('IntersectionObserver' in window)) {
            loadAllImages();
            return;
        }

        const lazyImages = document.querySelectorAll('img.lazy');
        if (!lazyImages.length) return;

        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    
                    // Load the image
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                    }
                    
                    // Load srcset if available
                    if (img.dataset.srcset) {
                        img.srcset = img.dataset.srcset;
                        img.removeAttribute('data-srcset');
                    }
                    
                    // Load sizes if available
                    if (img.dataset.sizes) {
                        img.sizes = img.dataset.sizes;
                        img.removeAttribute('data-sizes');
                    }
                    
                    // Remove lazy class
                    img.classList.remove('lazy');
                    
                    // Add loaded class with fade-in effect
                    img.classList.add('lazy-loaded');
                    
                    // Stop observing the image
                    observer.unobserve(img);
                }
            });
        }, {
            rootMargin: '200px 0px', // Load images 200px before they appear in viewport
            threshold: 0.01 // Trigger when at least 1% of the image is visible
        });
        
        lazyImages.forEach(image => {
            imageObserver.observe(image);
        });
    }

    /**
     * Lazy Loading for Background Images
     */
    function setupLazyBackgrounds() {
        // Check if IntersectionObserver is supported
        if (!('IntersectionObserver' in window)) {
            loadAllBackgrounds();
            return;
        }

        const lazyBackgrounds = document.querySelectorAll('.lazy-background');
        if (!lazyBackgrounds.length) return;

        const backgroundObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    
                    // Load the background image
                    if (element.dataset.background) {
                        element.style.backgroundImage = `url('${element.dataset.background}')`;
                        element.removeAttribute('data-background');
                    }
                    
                    // Remove lazy class
                    element.classList.remove('lazy-background');
                    
                    // Add loaded class with fade-in effect
                    element.classList.add('background-loaded');
                    
                    // Stop observing the element
                    observer.unobserve(element);
                }
            });
        }, {
            rootMargin: '200px 0px', // Load backgrounds 200px before they appear in viewport
            threshold: 0.01 // Trigger when at least 1% of the element is visible
        });
        
        lazyBackgrounds.forEach(background => {
            backgroundObserver.observe(background);
        });
    }

    /**
     * Lazy Loading for iframes (videos, maps, etc.)
     */
    function setupLazyIframes() {
        // Check if IntersectionObserver is supported
        if (!('IntersectionObserver' in window)) {
            loadAllIframes();
            return;
        }

        const lazyIframes = document.querySelectorAll('iframe.lazy');
        if (!lazyIframes.length) return;

        const iframeObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const iframe = entry.target;
                    
                    // Load the iframe source
                    if (iframe.dataset.src) {
                        iframe.src = iframe.dataset.src;
                        iframe.removeAttribute('data-src');
                    }
                    
                    // Remove lazy class
                    iframe.classList.remove('lazy');
                    
                    // Add loaded class
                    iframe.classList.add('iframe-loaded');
                    
                    // Stop observing the iframe
                    observer.unobserve(iframe);
                }
            });
        }, {
            rootMargin: '200px 0px', // Load iframes 200px before they appear in viewport
            threshold: 0.01 // Trigger when at least 1% of the iframe is visible
        });
        
        lazyIframes.forEach(iframe => {
            iframeObserver.observe(iframe);
        });
    }

    /**
     * Load all images immediately (fallback for browsers without IntersectionObserver)
     */
    function loadAllImages() {
        const lazyImages = document.querySelectorAll('img.lazy');
        
        lazyImages.forEach(img => {
            if (img.dataset.src) {
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
            }
            
            if (img.dataset.srcset) {
                img.srcset = img.dataset.srcset;
                img.removeAttribute('data-srcset');
            }
            
            if (img.dataset.sizes) {
                img.sizes = img.dataset.sizes;
                img.removeAttribute('data-sizes');
            }
            
            img.classList.remove('lazy');
        });
    }

    /**
     * Load all background images immediately (fallback for browsers without IntersectionObserver)
     */
    function loadAllBackgrounds() {
        const lazyBackgrounds = document.querySelectorAll('.lazy-background');
        
        lazyBackgrounds.forEach(element => {
            if (element.dataset.background) {
                element.style.backgroundImage = `url('${element.dataset.background}')`;
                element.removeAttribute('data-background');
            }
            
            element.classList.remove('lazy-background');
        });
    }

    /**
     * Load all iframes immediately (fallback for browsers without IntersectionObserver)
     */
    function loadAllIframes() {
        const lazyIframes = document.querySelectorAll('iframe.lazy');
        
        lazyIframes.forEach(iframe => {
            if (iframe.dataset.src) {
                iframe.src = iframe.dataset.src;
                iframe.removeAttribute('data-src');
            }
            
            iframe.classList.remove('lazy');
        });
    }

    /**
     * Add lazy loading to newly added elements (for AJAX loaded content)
     */
    function refreshLazyLoading() {
        setupLazyImages();
        setupLazyBackgrounds();
        setupLazyIframes();
    }

    // Make refreshLazyLoading available globally
    window.refreshLazyLoading = refreshLazyLoading;

    // Initialize when DOM is fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Refresh on window load to catch any images that might have been missed
    window.addEventListener('load', refreshLazyLoading);
    
    // Refresh on AJAX content load (if using a framework that triggers custom events)
    document.addEventListener('ajaxContentLoaded', refreshLazyLoading);
    
    // For WooCommerce AJAX events
    if (typeof jQuery !== 'undefined') {
        jQuery(document).on('ajaxComplete', function() {
            setTimeout(refreshLazyLoading, 100);
        });
    }
})();