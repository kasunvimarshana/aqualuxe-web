/**
 * AquaLuxe Theme - Testimonials Module JavaScript
 */

(function($) {
    'use strict';

    // Testimonials Module
    const AquaLuxeTestimonials = {
        /**
         * Default settings
         */
        defaults: {
            autoplay: true,
            autoplaySpeed: 5000,
            animationSpeed: 500,
            pauseOnHover: true
        },

        /**
         * Initialize the testimonials functionality
         */
        init: function() {
            // Get settings from localized script
            this.settings = $.extend({}, this.defaults, window.aqualuxeTestimonials || {});
            
            // Initialize all testimonial sliders
            this.initSliders();
            
            // Initialize masonry layout
            this.initMasonry();
            
            // Bind events
            this.bindEvents();
        },

        /**
         * Initialize testimonial sliders
         */
        initSliders: function() {
            $('.aqualuxe-testimonials-slider').each(function(index, slider) {
                AquaLuxeTestimonials.setupSlider($(slider));
            });
        },

        /**
         * Initialize masonry layout
         */
        initMasonry: function() {
            // Check if imagesLoaded and Masonry are available
            if (typeof $.fn.imagesLoaded !== 'undefined' && typeof $.fn.masonry !== 'undefined') {
                $('.aqualuxe-testimonials-masonry .aqualuxe-testimonials-container').each(function() {
                    const $container = $(this);
                    
                    // Initialize Masonry after images are loaded
                    $container.imagesLoaded(function() {
                        $container.masonry({
                            itemSelector: '.aqualuxe-testimonial',
                            columnWidth: '.aqualuxe-testimonial',
                            percentPosition: true
                        });
                    });
                });
            }
        },

        /**
         * Setup a single slider
         * 
         * @param {jQuery} $slider - The slider element
         */
        setupSlider: function($slider) {
            const $testimonials = $slider.find('.aqualuxe-testimonial');
            const $dots = $slider.find('.aqualuxe-testimonials-dot');
            
            // If no testimonials, return
            if ($testimonials.length === 0) {
                return;
            }
            
            // Set first testimonial as active
            $testimonials.first().addClass('active');
            
            // Set up autoplay
            if (this.settings.autoplay) {
                const sliderId = $slider.attr('id');
                
                // Store autoplay timer
                this.startAutoplay(sliderId, $slider);
                
                // Pause on hover if enabled
                if (this.settings.pauseOnHover) {
                    $slider.on('mouseenter', function() {
                        AquaLuxeTestimonials.stopAutoplay(sliderId);
                    }).on('mouseleave', function() {
                        AquaLuxeTestimonials.startAutoplay(sliderId, $slider);
                    });
                }
            }
        },

        /**
         * Start autoplay for a slider
         * 
         * @param {string} sliderId - The slider ID
         * @param {jQuery} $slider - The slider element
         */
        startAutoplay: function(sliderId, $slider) {
            // Clear any existing timer
            this.stopAutoplay(sliderId);
            
            // Set new timer
            this.autoplayTimers = this.autoplayTimers || {};
            this.autoplayTimers[sliderId] = setInterval(function() {
                AquaLuxeTestimonials.nextSlide($slider);
            }, this.settings.autoplaySpeed);
        },

        /**
         * Stop autoplay for a slider
         * 
         * @param {string} sliderId - The slider ID
         */
        stopAutoplay: function(sliderId) {
            if (this.autoplayTimers && this.autoplayTimers[sliderId]) {
                clearInterval(this.autoplayTimers[sliderId]);
                delete this.autoplayTimers[sliderId];
            }
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Next button click
            $(document).on('click', '.aqualuxe-testimonials-next', function(e) {
                e.preventDefault();
                const $slider = $(this).closest('.aqualuxe-testimonials');
                AquaLuxeTestimonials.nextSlide($slider);
            });
            
            // Previous button click
            $(document).on('click', '.aqualuxe-testimonials-prev', function(e) {
                e.preventDefault();
                const $slider = $(this).closest('.aqualuxe-testimonials');
                AquaLuxeTestimonials.prevSlide($slider);
            });
            
            // Dot click
            $(document).on('click', '.aqualuxe-testimonials-dot', function(e) {
                e.preventDefault();
                const $dot = $(this);
                const $slider = $dot.closest('.aqualuxe-testimonials');
                const slideIndex = $dot.data('slide');
                
                AquaLuxeTestimonials.goToSlide($slider, slideIndex);
            });
            
            // Handle swipe events if touch events are available
            if ('ontouchstart' in window) {
                this.setupSwipeEvents();
            }
            
            // Handle window resize for masonry
            $(window).on('resize', this.debounce(function() {
                AquaLuxeTestimonials.initMasonry();
            }, 250));
        },

        /**
         * Setup swipe events for touch devices
         */
        setupSwipeEvents: function() {
            let touchStartX = 0;
            let touchEndX = 0;
            const minSwipeDistance = 50;
            
            $(document).on('touchstart', '.aqualuxe-testimonials-slider', function(e) {
                touchStartX = e.originalEvent.touches[0].clientX;
            });
            
            $(document).on('touchend', '.aqualuxe-testimonials-slider', function(e) {
                touchEndX = e.originalEvent.changedTouches[0].clientX;
                
                // Detect swipe direction
                if (touchStartX - touchEndX > minSwipeDistance) {
                    // Swipe left - go to next slide
                    AquaLuxeTestimonials.nextSlide($(this));
                } else if (touchEndX - touchStartX > minSwipeDistance) {
                    // Swipe right - go to previous slide
                    AquaLuxeTestimonials.prevSlide($(this));
                }
            });
        },

        /**
         * Go to next slide
         * 
         * @param {jQuery} $slider - The slider element
         */
        nextSlide: function($slider) {
            const $testimonials = $slider.find('.aqualuxe-testimonial');
            const $active = $testimonials.filter('.active');
            let $next = $active.next('.aqualuxe-testimonial');
            
            // If no next testimonial, go to first
            if ($next.length === 0) {
                $next = $testimonials.first();
            }
            
            this.goToSlide($slider, $testimonials.index($next));
        },

        /**
         * Go to previous slide
         * 
         * @param {jQuery} $slider - The slider element
         */
        prevSlide: function($slider) {
            const $testimonials = $slider.find('.aqualuxe-testimonial');
            const $active = $testimonials.filter('.active');
            let $prev = $active.prev('.aqualuxe-testimonial');
            
            // If no previous testimonial, go to last
            if ($prev.length === 0) {
                $prev = $testimonials.last();
            }
            
            this.goToSlide($slider, $testimonials.index($prev));
        },

        /**
         * Go to specific slide
         * 
         * @param {jQuery} $slider - The slider element
         * @param {number} index - The slide index
         */
        goToSlide: function($slider, index) {
            const $testimonials = $slider.find('.aqualuxe-testimonial');
            const $dots = $slider.find('.aqualuxe-testimonials-dot');
            const sliderId = $slider.attr('id');
            
            // If autoplay is enabled, reset timer
            if (this.settings.autoplay) {
                this.startAutoplay(sliderId, $slider);
            }
            
            // Hide current active testimonial
            $testimonials.filter('.active').removeClass('active');
            
            // Show new testimonial
            $testimonials.eq(index).addClass('active');
            
            // Update dots
            $dots.removeClass('active');
            $dots.eq(index).addClass('active');
            
            // Trigger custom event
            $slider.trigger('aqualuxe.testimonial.changed', [index]);
        },

        /**
         * Debounce function
         * 
         * @param {Function} func - Function to debounce
         * @param {number} wait - Wait time in milliseconds
         * @return {Function} - Debounced function
         */
        debounce: function(func, wait) {
            let timeout;
            
            return function() {
                const context = this;
                const args = arguments;
                
                clearTimeout(timeout);
                
                timeout = setTimeout(function() {
                    func.apply(context, args);
                }, wait);
            };
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeTestimonials.init();
    });

})(jQuery);