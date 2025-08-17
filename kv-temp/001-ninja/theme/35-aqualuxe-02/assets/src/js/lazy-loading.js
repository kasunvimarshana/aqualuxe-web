/**
 * AquaLuxe Lazy Loading
 *
 * This file contains scripts for lazy loading images and other elements.
 */

(function() {
    'use strict';

    /**
     * Lazy Loading Handler
     */
    class AqualuxeLazyLoading {
        /**
         * Constructor
         */
        constructor() {
            this.images = document.querySelectorAll('img[loading="lazy"]');
            this.backgrounds = document.querySelectorAll('.aqualuxe-lazy-background');
            this.videos = document.querySelectorAll('video[loading="lazy"]');
            this.iframes = document.querySelectorAll('iframe[loading="lazy"]');
            
            this.observer = null;
            this.observerOptions = {
                root: null,
                rootMargin: '50px 0px',
                threshold: 0.1
            };
            
            this.init();
        }
        
        /**
         * Initialize lazy loading
         */
        init() {
            // Check if Intersection Observer is supported
            if ('IntersectionObserver' in window) {
                this.setupIntersectionObserver();
            } else {
                this.loadAllElements();
            }
            
            // Add event listeners
            window.addEventListener('load', this.checkVisibleElements.bind(this));
            window.addEventListener('scroll', this.debounce(this.checkVisibleElements.bind(this), 100));
            window.addEventListener('resize', this.debounce(this.checkVisibleElements.bind(this), 100));
        }
        
        /**
         * Setup Intersection Observer
         */
        setupIntersectionObserver() {
            this.observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        this.loadElement(entry.target);
                        this.observer.unobserve(entry.target);
                    }
                });
            }, this.observerOptions);
            
            // Observe images
            this.images.forEach((image) => {
                // Skip images that are already loaded or have native lazy loading
                if (image.complete || image.hasAttribute('data-loaded')) {
                    return;
                }
                
                this.observer.observe(image);
            });
            
            // Observe background elements
            this.backgrounds.forEach((element) => {
                if (element.hasAttribute('data-loaded')) {
                    return;
                }
                
                this.observer.observe(element);
            });
            
            // Observe videos
            this.videos.forEach((video) => {
                if (video.hasAttribute('data-loaded')) {
                    return;
                }
                
                this.observer.observe(video);
            });
            
            // Observe iframes
            this.iframes.forEach((iframe) => {
                if (iframe.hasAttribute('data-loaded')) {
                    return;
                }
                
                this.observer.observe(iframe);
            });
        }
        
        /**
         * Load an element
         * 
         * @param {Element} element The element to load
         */
        loadElement(element) {
            // Handle different element types
            if (element.tagName === 'IMG') {
                this.loadImage(element);
            } else if (element.classList.contains('aqualuxe-lazy-background')) {
                this.loadBackground(element);
            } else if (element.tagName === 'VIDEO') {
                this.loadVideo(element);
            } else if (element.tagName === 'IFRAME') {
                this.loadIframe(element);
            }
            
            // Mark as loaded
            element.setAttribute('data-loaded', 'true');
            
            // Trigger event
            this.triggerEvent('aqualuxe:element:loaded', { element });
        }
        
        /**
         * Load an image
         * 
         * @param {HTMLImageElement} img The image element
         */
        loadImage(img) {
            const src = img.getAttribute('data-src');
            const srcset = img.getAttribute('data-srcset');
            const sizes = img.getAttribute('data-sizes');
            
            if (src) {
                img.src = src;
            }
            
            if (srcset) {
                img.srcset = srcset;
            }
            
            if (sizes) {
                img.sizes = sizes;
            }
            
            img.classList.add('aqualuxe-loaded');
        }
        
        /**
         * Load a background image
         * 
         * @param {HTMLElement} element The element with background
         */
        loadBackground(element) {
            const src = element.getAttribute('data-background');
            
            if (src) {
                element.style.backgroundImage = `url(${src})`;
            }
            
            element.classList.add('aqualuxe-loaded');
        }
        
        /**
         * Load a video
         * 
         * @param {HTMLVideoElement} video The video element
         */
        loadVideo(video) {
            const src = video.getAttribute('data-src');
            const sources = video.querySelectorAll('source[data-src]');
            
            if (src) {
                video.src = src;
            }
            
            if (sources.length) {
                sources.forEach((source) => {
                    const sourceSrc = source.getAttribute('data-src');
                    if (sourceSrc) {
                        source.src = sourceSrc;
                    }
                });
                
                video.load();
            }
            
            video.classList.add('aqualuxe-loaded');
        }
        
        /**
         * Load an iframe
         * 
         * @param {HTMLIFrameElement} iframe The iframe element
         */
        loadIframe(iframe) {
            const src = iframe.getAttribute('data-src');
            
            if (src) {
                iframe.src = src;
            }
            
            iframe.classList.add('aqualuxe-loaded');
        }
        
        /**
         * Check for visible elements
         */
        checkVisibleElements() {
            // If we have an observer, it will handle this
            if (this.observer) {
                return;
            }
            
            // Fallback for browsers without Intersection Observer
            const viewportHeight = window.innerHeight;
            const viewportTop = window.pageYOffset;
            const viewportBottom = viewportTop + viewportHeight;
            
            // Check images
            this.images.forEach((image) => {
                if (image.hasAttribute('data-loaded')) {
                    return;
                }
                
                const rect = image.getBoundingClientRect();
                const elementTop = rect.top + viewportTop;
                const elementBottom = elementTop + rect.height;
                
                if (elementBottom >= viewportTop && elementTop <= viewportBottom) {
                    this.loadElement(image);
                }
            });
            
            // Check backgrounds
            this.backgrounds.forEach((element) => {
                if (element.hasAttribute('data-loaded')) {
                    return;
                }
                
                const rect = element.getBoundingClientRect();
                const elementTop = rect.top + viewportTop;
                const elementBottom = elementTop + rect.height;
                
                if (elementBottom >= viewportTop && elementTop <= viewportBottom) {
                    this.loadElement(element);
                }
            });
            
            // Check videos
            this.videos.forEach((video) => {
                if (video.hasAttribute('data-loaded')) {
                    return;
                }
                
                const rect = video.getBoundingClientRect();
                const elementTop = rect.top + viewportTop;
                const elementBottom = elementTop + rect.height;
                
                if (elementBottom >= viewportTop && elementTop <= viewportBottom) {
                    this.loadElement(video);
                }
            });
            
            // Check iframes
            this.iframes.forEach((iframe) => {
                if (iframe.hasAttribute('data-loaded')) {
                    return;
                }
                
                const rect = iframe.getBoundingClientRect();
                const elementTop = rect.top + viewportTop;
                const elementBottom = elementTop + rect.height;
                
                if (elementBottom >= viewportTop && elementTop <= viewportBottom) {
                    this.loadElement(iframe);
                }
            });
        }
        
        /**
         * Load all elements immediately
         */
        loadAllElements() {
            // Load all images
            this.images.forEach((image) => {
                this.loadElement(image);
            });
            
            // Load all backgrounds
            this.backgrounds.forEach((element) => {
                this.loadElement(element);
            });
            
            // Load all videos
            this.videos.forEach((video) => {
                this.loadElement(video);
            });
            
            // Load all iframes
            this.iframes.forEach((iframe) => {
                this.loadElement(iframe);
            });
        }
        
        /**
         * Debounce function
         * 
         * @param {Function} func The function to debounce
         * @param {number} wait The wait time in milliseconds
         * @return {Function} The debounced function
         */
        debounce(func, wait) {
            let timeout;
            
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
        
        /**
         * Trigger a custom event
         * 
         * @param {string} name The event name
         * @param {Object} detail The event detail
         */
        triggerEvent(name, detail = {}) {
            const event = new CustomEvent(name, {
                bubbles: true,
                cancelable: true,
                detail
            });
            
            document.dispatchEvent(event);
        }
    }

    /**
     * Animation Handler
     */
    class AqualuxeAnimation {
        /**
         * Constructor
         */
        constructor() {
            this.elements = document.querySelectorAll('.aqualuxe-animate');
            this.observer = null;
            this.observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };
            
            this.init();
        }
        
        /**
         * Initialize animations
         */
        init() {
            // Check if Intersection Observer is supported
            if ('IntersectionObserver' in window) {
                this.setupIntersectionObserver();
            } else {
                this.animateAllElements();
            }
        }
        
        /**
         * Setup Intersection Observer
         */
        setupIntersectionObserver() {
            this.observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        this.animateElement(entry.target);
                        this.observer.unobserve(entry.target);
                    }
                });
            }, this.observerOptions);
            
            // Observe elements
            this.elements.forEach((element) => {
                if (element.hasAttribute('data-animated')) {
                    return;
                }
                
                this.observer.observe(element);
            });
        }
        
        /**
         * Animate an element
         * 
         * @param {Element} element The element to animate
         */
        animateElement(element) {
            element.classList.add('aqualuxe-animated');
            element.setAttribute('data-animated', 'true');
            
            // Trigger event
            const event = new CustomEvent('aqualuxe:element:animated', {
                bubbles: true,
                cancelable: true,
                detail: { element }
            });
            
            document.dispatchEvent(event);
        }
        
        /**
         * Animate all elements immediately
         */
        animateAllElements() {
            this.elements.forEach((element) => {
                this.animateElement(element);
            });
        }
    }

    /**
     * Initialize when the DOM is ready
     */
    document.addEventListener('DOMContentLoaded', () => {
        // Initialize lazy loading
        new AqualuxeLazyLoading();
        
        // Initialize animations
        new AqualuxeAnimation();
    });
})();