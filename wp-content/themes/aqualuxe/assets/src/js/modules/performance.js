/**
 * Performance Module
 * Handles performance optimizations
 * 
 * @package AquaLuxe
 */

(function($) {
    'use strict';

    class PerformanceHandler {
        constructor() {
            this.init();
        }

        init() {
            this.setupLazyLoading();
            this.setupImageOptimization();
            this.preloadCriticalResources();
        }

        setupLazyLoading() {
            // Intersection Observer for lazy loading
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            img.classList.add('loaded');
                            observer.unobserve(img);
                        }
                    });
                }, {
                    rootMargin: '50px 0px'
                });

                // Observe lazy images
                document.querySelectorAll('img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });

                // Observe lazy backgrounds
                const bgObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const element = entry.target;
                            element.style.backgroundImage = `url(${element.dataset.bg})`;
                            element.classList.remove('lazy-bg');
                            observer.unobserve(element);
                        }
                    });
                });

                document.querySelectorAll('[data-bg]').forEach(element => {
                    bgObserver.observe(element);
                });
            }
        }

        setupImageOptimization() {
            // Add loading="lazy" to images below the fold
            const images = document.querySelectorAll('img:not([loading])');
            images.forEach((img, index) => {
                if (index > 2) { // Skip first 3 images (likely above fold)
                    img.setAttribute('loading', 'lazy');
                }
            });
        }

        preloadCriticalResources() {
            // Preload critical fonts
            const criticalFonts = [
                '/assets/dist/fonts/inter-regular.woff2',
                '/assets/dist/fonts/inter-bold.woff2'
            ];

            criticalFonts.forEach(font => {
                const link = document.createElement('link');
                link.rel = 'preload';
                link.as = 'font';
                link.type = 'font/woff2';
                link.crossOrigin = 'anonymous';
                link.href = font;
                document.head.appendChild(link);
            });
        }
    }

    // Initialize performance handler
    new PerformanceHandler();

})(jQuery);