// UI/UX Module - Main JavaScript File
// Provides enhanced UI/UX functionality with graceful degradation

(function($) {
    'use strict';
    
    console.log('UI/UX module loaded.');
    
    // UI/UX Enhancement Object
    const AquaLuxeUIUX = {
        
        init: function() {
            this.initBasicAnimations();
            this.initInteractiveElements();
            this.checkOptionalLibraries();
        },
        
        // Basic CSS-based animations that don't require external libraries
        initBasicAnimations: function() {
            // Fade in elements on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('fade-in-visible');
                        }
                    });
                }, observerOptions);
                
                // Observe elements with fade-in class
                document.querySelectorAll('.fade-in').forEach(function(el) {
                    observer.observe(el);
                });
            }
        },
        
        // Initialize interactive elements
        initInteractiveElements: function() {
            // Smooth scroll for anchor links
            $('a[href^="#"]').on('click', function(e) {
                const target = $(this.getAttribute('href'));
                if (target.length) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top - 80
                    }, 800);
                }
            });
            
            // Add hover effects to cards
            $('.card, .product-card, .service-card').hover(
                function() { $(this).addClass('hover-effect'); },
                function() { $(this).removeClass('hover-effect'); }
            );
        },
        
        // Check for optional libraries and initialize if available
        checkOptionalLibraries: function() {
            // THREE.js for 3D effects (optional)
            if (typeof THREE !== 'undefined') {
                console.log('THREE.js is available - enhanced 3D effects enabled');
                this.initThreeJS();
            } else {
                console.info('THREE.js not loaded - using CSS-based alternatives');
            }
            
            // GSAP for advanced animations (optional)
            if (typeof gsap !== 'undefined') {
                console.log('GSAP is available - enhanced animations enabled');
                this.initGSAP();
            } else {
                console.info('GSAP not loaded - using CSS-based alternatives');
            }
            
            // D3.js for data visualization (optional)
            if (typeof d3 !== 'undefined') {
                console.log('D3.js is available - data visualization enabled');
                this.initD3();
            } else {
                console.info('D3.js not loaded - basic charts available');
            }
        },
        
        // Initialize THREE.js if available
        initThreeJS: function() {
            // THREE.js initialization code would go here
        },
        
        // Initialize GSAP if available
        initGSAP: function() {
            // GSAP animation code would go here
        },
        
        // Initialize D3.js if available
        initD3: function() {
            // D3.js visualization code would go here
        }
    };
    
    // Initialize when document is ready
    $(document).ready(function() {
        AquaLuxeUIUX.init();
    });
    
    // Make available globally if needed
    window.AquaLuxeUIUX = AquaLuxeUIUX;
    
})(jQuery);
