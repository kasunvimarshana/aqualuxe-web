/**
 * AquaLuxe Theme - Responsive Images Module
 * 
 * Handles responsive image loading and optimization.
 */

const ResponsiveImages = (function() {
    'use strict';
    
    /**
     * Initialize responsive images functionality
     */
    function init() {
        // Initialize lazy loading
        initLazyLoading();
        
        // Initialize responsive background images
        initResponsiveBackgrounds();
        
        // Initialize image loading optimization
        initImageLoadOptimization();
    }
    
    /**
     * Initialize lazy loading for images
     */
    function initLazyLoading() {
        // Check if native lazy loading is supported
        if ('loading' in HTMLImageElement.prototype) {
            // Browser supports native lazy loading
            // We're already adding loading="lazy" via PHP, but let's handle any that might be missed
            const images = document.querySelectorAll('img:not([loading])');
            images.forEach(img => {
                img.setAttribute('loading', 'lazy');
            });
        } else {
            // Browser doesn't support native lazy loading, use IntersectionObserver
            if ('IntersectionObserver' in window) {
                const lazyImageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const lazyImage = entry.target;
                            
                            // Handle data-src
                            if (lazyImage.dataset.src) {
                                lazyImage.src = lazyImage.dataset.src;
                                lazyImage.removeAttribute('data-src');
                            }
                            
                            // Handle data-srcset
                            if (lazyImage.dataset.srcset) {
                                lazyImage.srcset = lazyImage.dataset.srcset;
                                lazyImage.removeAttribute('data-srcset');
                            }
                            
                            // Handle data-sizes
                            if (lazyImage.dataset.sizes) {
                                lazyImage.sizes = lazyImage.dataset.sizes;
                                lazyImage.removeAttribute('data-sizes');
                            }
                            
                            lazyImage.classList.remove('lazyload');
                            lazyImage.classList.add('lazyloaded');
                            observer.unobserve(lazyImage);
                        }
                    });
                });
                
                // Observe all images with lazyload class
                const lazyImages = document.querySelectorAll('img.lazyload');
                lazyImages.forEach(lazyImage => {
                    lazyImageObserver.observe(lazyImage);
                });
            } else {
                // Fallback for browsers that don't support IntersectionObserver
                // Load all images immediately
                const lazyImages = document.querySelectorAll('img.lazyload');
                lazyImages.forEach(img => {
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                    }
                    if (img.dataset.srcset) {
                        img.srcset = img.dataset.srcset;
                        img.removeAttribute('data-srcset');
                    }
                    img.classList.remove('lazyload');
                });
            }
        }
    }
    
    /**
     * Initialize responsive background images
     */
    function initResponsiveBackgrounds() {
        // Handle elements with data-background attribute
        const backgroundElements = document.querySelectorAll('[data-background]');
        
        if (backgroundElements.length > 0) {
            // Function to set the appropriate background image based on screen size
            const setResponsiveBackground = (element) => {
                const backgrounds = JSON.parse(element.dataset.background);
                const windowWidth = window.innerWidth;
                
                // Find the appropriate background for the current screen size
                let selectedBackground = backgrounds.default;
                
                if (windowWidth >= 1200 && backgrounds.xl) {
                    selectedBackground = backgrounds.xl;
                } else if (windowWidth >= 992 && backgrounds.lg) {
                    selectedBackground = backgrounds.lg;
                } else if (windowWidth >= 768 && backgrounds.md) {
                    selectedBackground = backgrounds.md;
                } else if (windowWidth >= 576 && backgrounds.sm) {
                    selectedBackground = backgrounds.sm;
                }
                
                // Set the background image
                if (selectedBackground) {
                    element.style.backgroundImage = `url(${selectedBackground})`;
                }
            };
            
            // Set initial backgrounds
            backgroundElements.forEach(element => {
                setResponsiveBackground(element);
            });
            
            // Update backgrounds on window resize
            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    backgroundElements.forEach(element => {
                        setResponsiveBackground(element);
                    });
                }, 250);
            });
        }
    }
    
    /**
     * Initialize image loading optimization
     */
    function initImageLoadOptimization() {
        // Handle image load errors
        document.addEventListener('error', function(event) {
            const target = event.target;
            
            // Only handle image errors
            if (target.tagName === 'IMG') {
                // Log the error
                console.warn('Image failed to load:', target.src);
                
                // Add error class
                target.classList.add('image-load-error');
                
                // Try to load a fallback image if available
                if (target.dataset.fallback) {
                    target.src = target.dataset.fallback;
                }
            }
        }, true);
        
        // Optimize image loading order - prioritize visible images
        if ('IntersectionObserver' in window && 'requestIdleCallback' in window) {
            const deferredImages = document.querySelectorAll('img[loading="lazy"]:not(.critical-image)');
            
            if (deferredImages.length > 0) {
                const imageObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            
                            // Change loading attribute to eager for visible images
                            img.loading = 'eager';
                            
                            // Stop observing this image
                            imageObserver.unobserve(img);
                        }
                    });
                }, {
                    rootMargin: '200px' // Start loading when image is 200px from viewport
                });
                
                // Observe all deferred images
                deferredImages.forEach(img => {
                    imageObserver.observe(img);
                });
            }
        }
    }
    
    // Public API
    return {
        init: init
    };
})();

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', ResponsiveImages.init);