// Lazy loading functionality
(function() {
    'use strict';
    
    const LazyLoading = {
        init: function() {
            if ('IntersectionObserver' in window) {
                this.setupIntersectionObserver();
            } else {
                this.fallbackLazyLoad();
            }
        },
        
        setupIntersectionObserver: function() {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.loadImage(entry.target);
                        imageObserver.unobserve(entry.target);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });
            
            const lazyImages = document.querySelectorAll('img[data-src]');
            lazyImages.forEach(img => imageObserver.observe(img));
        },
        
        loadImage: function(img) {
            if (img.dataset.src) {
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
            }
            if (img.dataset.srcset) {
                img.srcset = img.dataset.srcset;
                img.removeAttribute('data-srcset');
            }
            img.classList.remove('lazy');
            img.classList.add('loaded');
        },
        
        fallbackLazyLoad: function() {
            const lazyImages = document.querySelectorAll('img[data-src]');
            lazyImages.forEach(img => this.loadImage(img));
        }
    };
    
    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => LazyLoading.init());
    } else {
        LazyLoading.init();
    }
})();