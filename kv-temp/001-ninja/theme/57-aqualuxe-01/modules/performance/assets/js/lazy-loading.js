/**
 * Lazy Loading JavaScript
 *
 * @package AquaLuxe
 * @subpackage Modules/Performance
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Lazy Loading object
    var AquaLuxeLazyLoad = {
        /**
         * Initialize
         */
        init: function() {
            // Set up variables
            this.threshold = typeof aqualuxeLazyLoading !== 'undefined' && aqualuxeLazyLoading.threshold ? parseInt(aqualuxeLazyLoading.threshold) : 200;
            this.fadeIn = typeof aqualuxeLazyLoading !== 'undefined' && aqualuxeLazyLoading.fadeIn ? aqualuxeLazyLoading.fadeIn : true;
            this.fadeInDuration = typeof aqualuxeLazyLoading !== 'undefined' && aqualuxeLazyLoading.fadeInDuration ? parseInt(aqualuxeLazyLoading.fadeInDuration) : 400;
            
            // Set up event listeners
            this.setupEventListeners();
            
            // Initialize lazy loading
            this.initializeLazyLoading();
        },

        /**
         * Set up event listeners
         */
        setupEventListeners: function() {
            // Scroll event
            $(window).on('scroll', this.throttle(this.lazyLoad.bind(this), 200));
            
            // Resize event
            $(window).on('resize', this.throttle(this.lazyLoad.bind(this), 200));
            
            // Orientation change event
            $(window).on('orientationchange', this.lazyLoad.bind(this));
            
            // Custom event for triggering lazy loading
            $(document).on('aqualuxe.lazyload', this.lazyLoad.bind(this));
        },

        /**
         * Initialize lazy loading
         */
        initializeLazyLoading: function() {
            // Initial load
            this.lazyLoad();
            
            // Set up Intersection Observer if available
            if ('IntersectionObserver' in window) {
                this.setupIntersectionObserver();
            }
        },

        /**
         * Set up Intersection Observer
         */
        setupIntersectionObserver: function() {
            var self = this;
            
            // Create observer
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        var $element = $(entry.target);
                        
                        // Load element
                        self.loadElement($element);
                        
                        // Unobserve element
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                rootMargin: self.threshold + 'px 0px',
                threshold: 0.01
            });
            
            // Observe elements
            $('.lazy-load').each(function() {
                observer.observe(this);
            });
        },

        /**
         * Lazy load elements
         */
        lazyLoad: function() {
            var self = this;
            
            // Skip if Intersection Observer is available
            if ('IntersectionObserver' in window) {
                return;
            }
            
            // Find elements to lazy load
            $('.lazy-load').each(function() {
                var $element = $(this);
                
                // Skip if already loaded
                if ($element.hasClass('lazy-loaded')) {
                    return;
                }
                
                // Check if element is in viewport
                if (self.isElementInViewport($element, self.threshold)) {
                    self.loadElement($element);
                }
            });
        },

        /**
         * Load element
         * 
         * @param {jQuery} $element Element to load
         */
        loadElement: function($element) {
            var self = this;
            
            // Skip if already loaded
            if ($element.hasClass('lazy-loaded')) {
                return;
            }
            
            // Get source
            var src = $element.data('src');
            
            // Skip if no source
            if (!src) {
                return;
            }
            
            // Handle different element types
            if ($element.is('img')) {
                // Create new image to preload
                var img = new Image();
                
                // Set up load event
                img.onload = function() {
                    // Set source
                    $element.attr('src', src);
                    
                    // Remove data-src attribute
                    $element.removeAttr('data-src');
                    
                    // Add loaded class
                    $element.addClass('lazy-loaded');
                    
                    // Fade in if enabled
                    if (self.fadeIn) {
                        $element.css('opacity', 0).animate({ opacity: 1 }, self.fadeInDuration);
                    }
                };
                
                // Set source to trigger loading
                img.src = src;
            } else if ($element.is('iframe')) {
                // Set source
                $element.attr('src', src);
                
                // Remove data-src attribute
                $element.removeAttr('data-src');
                
                // Add loaded class
                $element.addClass('lazy-loaded');
                
                // Fade in if enabled
                if (self.fadeIn) {
                    $element.css('opacity', 0).animate({ opacity: 1 }, self.fadeInDuration);
                }
            } else if ($element.is('video')) {
                // Set source
                $element.attr('src', src);
                
                // Remove data-src attribute
                $element.removeAttr('data-src');
                
                // Add loaded class
                $element.addClass('lazy-loaded');
                
                // Fade in if enabled
                if (self.fadeIn) {
                    $element.css('opacity', 0).animate({ opacity: 1 }, self.fadeInDuration);
                }
            } else {
                // Set background image
                $element.css('background-image', 'url(' + src + ')');
                
                // Remove data-src attribute
                $element.removeAttr('data-src');
                
                // Add loaded class
                $element.addClass('lazy-loaded');
                
                // Fade in if enabled
                if (self.fadeIn) {
                    $element.css('opacity', 0).animate({ opacity: 1 }, self.fadeInDuration);
                }
            }
            
            // Trigger event
            $element.trigger('aqualuxe.lazyloaded');
        },

        /**
         * Check if element is in viewport
         * 
         * @param {jQuery} $element Element to check
         * @param {number} threshold Threshold
         * @return {boolean}
         */
        isElementInViewport: function($element, threshold) {
            var rect = $element[0].getBoundingClientRect();
            
            return (
                rect.bottom >= 0 &&
                rect.right >= 0 &&
                rect.top <= (window.innerHeight + threshold || document.documentElement.clientHeight + threshold) &&
                rect.left <= (window.innerWidth || document.documentElement.clientWidth)
            );
        },

        /**
         * Throttle function
         * 
         * @param {Function} func Function to throttle
         * @param {number} wait Wait time in milliseconds
         * @return {Function}
         */
        throttle: function(func, wait) {
            var context, args, result;
            var timeout = null;
            var previous = 0;
            
            var later = function() {
                previous = Date.now();
                timeout = null;
                result = func.apply(context, args);
                if (!timeout) {
                    context = args = null;
                }
            };
            
            return function() {
                var now = Date.now();
                var remaining = wait - (now - previous);
                context = this;
                args = arguments;
                
                if (remaining <= 0 || remaining > wait) {
                    if (timeout) {
                        clearTimeout(timeout);
                        timeout = null;
                    }
                    previous = now;
                    result = func.apply(context, args);
                    if (!timeout) {
                        context = args = null;
                    }
                } else if (!timeout) {
                    timeout = setTimeout(later, remaining);
                }
                
                return result;
            };
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeLazyLoad.init();
    });

    // Add to global namespace
    window.AquaLuxeLazyLoad = AquaLuxeLazyLoad;

})(jQuery);