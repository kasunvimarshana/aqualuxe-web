/**
 * AquaLuxe Lazy Loading
 *
 * JavaScript functionality for lazy loading images
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Initialize lazy loading for images
     */
    function initLazyLoading() {
        // Check if Intersection Observer API is available
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    // If the image is in the viewport
                    if (entry.isIntersecting) {
                        const lazyImage = entry.target;
                        
                        // Replace the src with the data-src
                        if (lazyImage.dataset.src) {
                            lazyImage.src = lazyImage.dataset.src;
                        }
                        
                        // Replace the srcset with the data-srcset
                        if (lazyImage.dataset.srcset) {
                            lazyImage.srcset = lazyImage.dataset.srcset;
                        }
                        
                        // Add a class to indicate the image has loaded
                        lazyImage.classList.add('loaded');
                        
                        // Stop observing the image
                        observer.unobserve(lazyImage);
                    }
                });
            }, {
                rootMargin: '200px 0px', // Start loading images when they're 200px from entering the viewport
                threshold: 0.01 // Trigger when at least 1% of the image is visible
            });

            // Observe all images with the 'lazy' class
            document.querySelectorAll('img.lazy').forEach(img => {
                imageObserver.observe(img);
            });
        } else {
            // Fallback for browsers that don't support Intersection Observer
            loadLazyImagesLegacy();
        }
    }

    /**
     * Legacy fallback for browsers without Intersection Observer support
     */
    function loadLazyImagesLegacy() {
        let lazyImages = [].slice.call(document.querySelectorAll('img.lazy'));
        let active = false;

        const lazyLoad = function() {
            if (active === false) {
                active = true;

                setTimeout(function() {
                    lazyImages.forEach(function(lazyImage) {
                        if ((lazyImage.getBoundingClientRect().top <= window.innerHeight && lazyImage.getBoundingClientRect().bottom >= 0) && getComputedStyle(lazyImage).display !== 'none') {
                            if (lazyImage.dataset.src) {
                                lazyImage.src = lazyImage.dataset.src;
                            }
                            
                            if (lazyImage.dataset.srcset) {
                                lazyImage.srcset = lazyImage.dataset.srcset;
                            }
                            
                            lazyImage.classList.add('loaded');
                            
                            lazyImages = lazyImages.filter(function(image) {
                                return image !== lazyImage;
                            });

                            if (lazyImages.length === 0) {
                                document.removeEventListener('scroll', lazyLoad);
                                window.removeEventListener('resize', lazyLoad);
                                window.removeEventListener('orientationchange', lazyLoad);
                            }
                        }
                    });

                    active = false;
                }, 200);
            }
        };

        document.addEventListener('scroll', lazyLoad);
        window.addEventListener('resize', lazyLoad);
        window.addEventListener('orientationchange', lazyLoad);
        
        // Initial load
        lazyLoad();
    }

    // Initialize when document is ready
    $(document).ready(function() {
        initLazyLoading();
    });

})(jQuery);