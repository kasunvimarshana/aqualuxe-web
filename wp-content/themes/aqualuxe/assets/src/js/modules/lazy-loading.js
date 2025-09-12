/**
 * Lazy Loading Module
 * Handles lazy loading of images and other content
 */

class LazyLoading {
    constructor() {
        this.imageObserver = null;
        this.contentObserver = null;
        this.init();
    }
    
    init() {
        if ('IntersectionObserver' in window) {
            this.initImageLazyLoading();
            this.initContentLazyLoading();
        } else {
            // Fallback for older browsers
            this.loadAllImages();
        }
    }
    
    /**
     * Initialize image lazy loading
     */
    initImageLazyLoading() {
        const images = document.querySelectorAll('img[data-src], img[loading="lazy"]');
        
        if (images.length === 0) return;
        
        const imageOptions = {
            root: null,
            rootMargin: '50px',
            threshold: 0.1
        };
        
        this.imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.loadImage(entry.target);
                    this.imageObserver.unobserve(entry.target);
                }
            });
        }, imageOptions);
        
        images.forEach(img => {
            this.imageObserver.observe(img);
        });
    }
    
    /**
     * Initialize content lazy loading
     */
    initContentLazyLoading() {
        const content = document.querySelectorAll('[data-lazy-content]');
        
        if (content.length === 0) return;
        
        const contentOptions = {
            root: null,
            rootMargin: '100px',
            threshold: 0.1
        };
        
        this.contentObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.loadContent(entry.target);
                    this.contentObserver.unobserve(entry.target);
                }
            });
        }, contentOptions);
        
        content.forEach(element => {
            this.contentObserver.observe(element);
        });
    }
    
    /**
     * Load image
     */
    loadImage(img) {
        const src = img.dataset.src || img.src;
        const srcset = img.dataset.srcset;
        
        if (!src) return;
        
        // Create new image to preload
        const imageLoader = new Image();
        
        imageLoader.onload = () => {
            // Image loaded successfully
            img.src = src;
            if (srcset) {
                img.srcset = srcset;
            }
            
            img.classList.add('loaded');
            img.classList.remove('loading');
            
            // Remove data attributes
            delete img.dataset.src;
            delete img.dataset.srcset;
            
            // Trigger load event
            img.dispatchEvent(new Event('lazy-loaded'));
        };
        
        imageLoader.onerror = () => {
            // Handle error
            img.classList.add('error');
            img.classList.remove('loading');
        };
        
        // Add loading class
        img.classList.add('loading');
        
        // Start loading
        imageLoader.src = src;
        if (srcset) {
            imageLoader.srcset = srcset;
        }
    }
    
    /**
     * Load content
     */
    loadContent(element) {
        const url = element.dataset.lazyContent;
        
        if (!url) return;
        
        // Add loading state
        element.classList.add('loading');
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(html => {
                element.innerHTML = html;
                element.classList.add('loaded');
                element.classList.remove('loading');
                
                // Initialize any new images in the loaded content
                const newImages = element.querySelectorAll('img[data-src]');
                newImages.forEach(img => {
                    if (this.imageObserver) {
                        this.imageObserver.observe(img);
                    }
                });
                
                // Trigger loaded event
                element.dispatchEvent(new Event('content-loaded'));
            })
            .catch(error => {
                console.error('Error loading content:', error);
                element.classList.add('error');
                element.classList.remove('loading');
                element.innerHTML = '<p>Error loading content.</p>';
            });
    }
    
    /**
     * Fallback for browsers without IntersectionObserver
     */
    loadAllImages() {
        const images = document.querySelectorAll('img[data-src]');
        
        images.forEach(img => {
            this.loadImage(img);
        });
    }
    
    /**
     * Destroy observers
     */
    destroy() {
        if (this.imageObserver) {
            this.imageObserver.disconnect();
        }
        
        if (this.contentObserver) {
            this.contentObserver.disconnect();
        }
    }
}

export default LazyLoading;