/**
 * Lazy Loading functionality
 *
 * @package AquaLuxe
 */

(function () {
    'use strict';

    /**
     * Lazy Loading
     */
    const AqualuxeLazyLoading = {
        /**
         * Initialize
         */
        init: function () {
            this.setupLazyLoading();
            this.setupLazyBackgrounds();
            this.setupLazyIframes();
        },

        /**
         * Setup lazy loading for images
         */
        setupLazyLoading: function () {
            // Check if browser supports native lazy loading
            if ('loading' in HTMLImageElement.prototype) {
                // Browser supports native lazy loading
                // Just add loading="lazy" attribute to images (done via PHP)
                this.convertImagesToNativeLazy();
            } else {
                // Browser doesn't support native lazy loading
                // Use Intersection Observer API
                this.setupIntersectionObserver();
            }
        },

        /**
         * Convert images to use native lazy loading
         */
        convertImagesToNativeLazy: function () {
            const images = document.querySelectorAll('img.lazy:not([loading="lazy"])');
            
            images.forEach(function (img) {
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                }
                
                if (img.dataset.srcset) {
                    img.srcset = img.dataset.srcset;
                }
                
                img.classList.remove('lazy');
                img.setAttribute('loading', 'lazy');
            });
        },

        /**
         * Setup Intersection Observer for lazy loading
         */
        setupIntersectionObserver: function () {
            if (!('IntersectionObserver' in window)) {
                // Intersection Observer not supported, load all images immediately
                this.loadAllLazyImages();
                return;
            }
            
            const lazyImageObserver = new IntersectionObserver(function (entries, observer) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        const lazyImage = entry.target;
                        
                        if (lazyImage.dataset.src) {
                            lazyImage.src = lazyImage.dataset.src;
                        }
                        
                        if (lazyImage.dataset.srcset) {
                            lazyImage.srcset = lazyImage.dataset.srcset;
                        }
                        
                        lazyImage.classList.remove('lazy');
                        lazyImageObserver.unobserve(lazyImage);
                    }
                });
            });
            
            const lazyImages = document.querySelectorAll('img.lazy');
            lazyImages.forEach(function (lazyImage) {
                lazyImageObserver.observe(lazyImage);
            });
        },

        /**
         * Load all lazy images immediately
         */
        loadAllLazyImages: function () {
            const lazyImages = document.querySelectorAll('img.lazy');
            
            lazyImages.forEach(function (lazyImage) {
                if (lazyImage.dataset.src) {
                    lazyImage.src = lazyImage.dataset.src;
                }
                
                if (lazyImage.dataset.srcset) {
                    lazyImage.srcset = lazyImage.dataset.srcset;
                }
                
                lazyImage.classList.remove('lazy');
            });
        },

        /**
         * Setup lazy loading for background images
         */
        setupLazyBackgrounds: function () {
            if (!('IntersectionObserver' in window)) {
                // Intersection Observer not supported, load all backgrounds immediately
                this.loadAllLazyBackgrounds();
                return;
            }
            
            const lazyBackgroundObserver = new IntersectionObserver(function (entries, observer) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        const lazyBackground = entry.target;
                        
                        if (lazyBackground.dataset.background) {
                            lazyBackground.style.backgroundImage = 'url(' + lazyBackground.dataset.background + ')';
                        }
                        
                        lazyBackground.classList.remove('lazy-background');
                        lazyBackgroundObserver.unobserve(lazyBackground);
                    }
                });
            });
            
            const lazyBackgrounds = document.querySelectorAll('.lazy-background');
            lazyBackgrounds.forEach(function (lazyBackground) {
                lazyBackgroundObserver.observe(lazyBackground);
            });
        },

        /**
         * Load all lazy backgrounds immediately
         */
        loadAllLazyBackgrounds: function () {
            const lazyBackgrounds = document.querySelectorAll('.lazy-background');
            
            lazyBackgrounds.forEach(function (lazyBackground) {
                if (lazyBackground.dataset.background) {
                    lazyBackground.style.backgroundImage = 'url(' + lazyBackground.dataset.background + ')';
                }
                
                lazyBackground.classList.remove('lazy-background');
            });
        },

        /**
         * Setup lazy loading for iframes
         */
        setupLazyIframes: function () {
            if ('loading' in HTMLIFrameElement.prototype) {
                // Browser supports native lazy loading for iframes
                const iframes = document.querySelectorAll('iframe.lazy:not([loading="lazy"])');
                
                iframes.forEach(function (iframe) {
                    if (iframe.dataset.src) {
                        iframe.src = iframe.dataset.src;
                    }
                    
                    iframe.classList.remove('lazy');
                    iframe.setAttribute('loading', 'lazy');
                });
            } else if ('IntersectionObserver' in window) {
                // Use Intersection Observer API
                const lazyIframeObserver = new IntersectionObserver(function (entries, observer) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            const lazyIframe = entry.target;
                            
                            if (lazyIframe.dataset.src) {
                                lazyIframe.src = lazyIframe.dataset.src;
                            }
                            
                            lazyIframe.classList.remove('lazy');
                            lazyIframeObserver.unobserve(lazyIframe);
                        }
                    });
                });
                
                const lazyIframes = document.querySelectorAll('iframe.lazy');
                lazyIframes.forEach(function (lazyIframe) {
                    lazyIframeObserver.observe(lazyIframe);
                });
            } else {
                // Intersection Observer not supported, load all iframes immediately
                const lazyIframes = document.querySelectorAll('iframe.lazy');
                
                lazyIframes.forEach(function (lazyIframe) {
                    if (lazyIframe.dataset.src) {
                        lazyIframe.src = lazyIframe.dataset.src;
                    }
                    
                    lazyIframe.classList.remove('lazy');
                });
            }
        }
    };

    // Initialize on document ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            AqualuxeLazyLoading.init();
        });
    } else {
        AqualuxeLazyLoading.init();
    }

})();