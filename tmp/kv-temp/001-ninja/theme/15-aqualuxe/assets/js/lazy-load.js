/**
 * AquaLuxe Lazy Loading
 * 
 * Handles lazy loading of images and iframes to improve page load performance
 */
(function() {
    'use strict';

    // Check if IntersectionObserver is supported
    if ('IntersectionObserver' in window) {
        const lazyLoadOptions = {
            rootMargin: '200px 0px', // Start loading 200px before the element enters the viewport
            threshold: 0.01 // Trigger when 1% of the element is visible
        };

        // Create observer for images
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    
                    // Handle <img> elements
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        if (img.dataset.srcset) {
                            img.srcset = img.dataset.srcset;
                        }
                    }
                    
                    // Handle background images
                    if (img.dataset.bg) {
                        img.style.backgroundImage = `url('${img.dataset.bg}')`;
                    }
                    
                    // Add loaded class for fade-in effect
                    img.classList.add('lazy-loaded');
                    
                    // Stop observing the element
                    observer.unobserve(img);
                }
            });
        }, lazyLoadOptions);

        // Create observer for iframes
        const iframeObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const iframe = entry.target;
                    
                    if (iframe.dataset.src) {
                        iframe.src = iframe.dataset.src;
                    }
                    
                    iframe.classList.add('lazy-loaded');
                    observer.unobserve(iframe);
                }
            });
        }, lazyLoadOptions);

        // Find and observe all lazy images and iframes
        const registerLazyElements = () => {
            // Lazy load images
            document.querySelectorAll('img.lazy, .lazy-bg').forEach(img => {
                imageObserver.observe(img);
            });
            
            // Lazy load iframes (like YouTube embeds)
            document.querySelectorAll('iframe.lazy').forEach(iframe => {
                iframeObserver.observe(iframe);
            });
        };

        // Initial registration
        registerLazyElements();
        
        // Re-register on AJAX content load
        document.addEventListener('aqualuxe_ajax_content_loaded', registerLazyElements);
        
        // Re-register on DOM changes (for dynamically added content)
        if ('MutationObserver' in window) {
            const mutationObserver = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.addedNodes && mutation.addedNodes.length > 0) {
                        // Check if any of the added nodes or their descendants are lazy elements
                        let hasLazyElements = false;
                        
                        mutation.addedNodes.forEach((node) => {
                            if (node.nodeType === 1) { // Element node
                                if (node.classList && (node.classList.contains('lazy') || node.classList.contains('lazy-bg'))) {
                                    hasLazyElements = true;
                                } else if (node.querySelectorAll) {
                                    const lazyElements = node.querySelectorAll('img.lazy, .lazy-bg, iframe.lazy');
                                    if (lazyElements.length > 0) {
                                        hasLazyElements = true;
                                    }
                                }
                            }
                        });
                        
                        if (hasLazyElements) {
                            registerLazyElements();
                        }
                    }
                });
            });
            
            // Start observing the document body for DOM changes
            mutationObserver.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    } else {
        // Fallback for browsers that don't support IntersectionObserver
        const lazyLoad = () => {
            const lazyImages = document.querySelectorAll('img.lazy, .lazy-bg');
            const lazyIframes = document.querySelectorAll('iframe.lazy');
            const windowHeight = window.innerHeight;
            
            // Load images that are in the viewport
            lazyImages.forEach(img => {
                const rect = img.getBoundingClientRect();
                
                if (rect.top <= windowHeight + 200 && rect.bottom >= 0) {
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        if (img.dataset.srcset) {
                            img.srcset = img.dataset.srcset;
                        }
                    }
                    
                    if (img.dataset.bg) {
                        img.style.backgroundImage = `url('${img.dataset.bg}')`;
                    }
                    
                    img.classList.add('lazy-loaded');
                    img.classList.remove('lazy');
                    img.classList.remove('lazy-bg');
                }
            });
            
            // Load iframes that are in the viewport
            lazyIframes.forEach(iframe => {
                const rect = iframe.getBoundingClientRect();
                
                if (rect.top <= windowHeight + 200 && rect.bottom >= 0) {
                    if (iframe.dataset.src) {
                        iframe.src = iframe.dataset.src;
                    }
                    
                    iframe.classList.add('lazy-loaded');
                    iframe.classList.remove('lazy');
                }
            });
            
            // If all elements are loaded, stop listening for scroll events
            if (lazyImages.length === 0 && lazyIframes.length === 0) {
                document.removeEventListener('scroll', lazyLoadThrottled);
                window.removeEventListener('resize', lazyLoadThrottled);
                window.removeEventListener('orientationchange', lazyLoadThrottled);
            }
        };
        
        // Throttle function to limit how often lazyLoad runs
        const throttle = (callback, limit) => {
            let waiting = false;
            return function() {
                if (!waiting) {
                    callback.apply(this, arguments);
                    waiting = true;
                    setTimeout(() => {
                        waiting = false;
                    }, limit);
                }
            };
        };
        
        // Throttled version of lazyLoad
        const lazyLoadThrottled = throttle(lazyLoad, 200);
        
        // Add event listeners
        document.addEventListener('scroll', lazyLoadThrottled);
        window.addEventListener('resize', lazyLoadThrottled);
        window.addEventListener('orientationchange', lazyLoadThrottled);
        
        // Initial load
        lazyLoad();
    }
    
    // Helper function to convert regular images to lazy-loaded images
    window.aqualuxeConvertToLazyLoad = function() {
        const images = document.querySelectorAll('img:not(.lazy):not(.no-lazy)');
        
        images.forEach(img => {
            // Skip images that are already loaded or don't have a src
            if (!img.src || img.complete) {
                return;
            }
            
            // Store original attributes
            const src = img.getAttribute('src');
            const srcset = img.getAttribute('srcset');
            
            // Set data attributes
            img.setAttribute('data-src', src);
            if (srcset) {
                img.setAttribute('data-srcset', srcset);
                img.removeAttribute('srcset');
            }
            
            // Remove src to prevent loading
            img.removeAttribute('src');
            
            // Add lazy class
            img.classList.add('lazy');
        });
        
        // Re-register lazy elements
        if (window.aqualuxeRegisterLazyElements) {
            window.aqualuxeRegisterLazyElements();
        }
    };
})();