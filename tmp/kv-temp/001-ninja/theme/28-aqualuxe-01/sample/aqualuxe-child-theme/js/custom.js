/**
 * AquaLuxe Child Theme - Custom JavaScript
 * 
 * This file contains custom JavaScript functionality for the AquaLuxe child theme.
 */

(function($) {
    'use strict';

    // Document ready function
    $(document).ready(function() {
        // Initialize custom functionality
        AquaLuxeChild.init();
    });

    // Main object for custom functionality
    var AquaLuxeChild = {
        // Initialize all functions
        init: function() {
            this.smoothScroll();
            this.enhanceProductGallery();
            this.improveAccessibility();
            this.addCustomEffects();
        },

        // Smooth scroll for anchor links
        smoothScroll: function() {
            $('a[href*="#"]:not([href="#"])').on('click', function() {
                if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
                    var target = $(this.hash);
                    target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                    if (target.length) {
                        $('html, body').animate({
                            scrollTop: target.offset().top - 100
                        }, 800);
                        return false;
                    }
                }
            });
        },

        // Enhance product gallery (if WooCommerce is active)
        enhanceProductGallery: function() {
            if ($('.woocommerce-product-gallery').length) {
                // Add zoom effect on product images
                $('.woocommerce-product-gallery__image').hover(
                    function() {
                        $(this).addClass('zoom-effect');
                    },
                    function() {
                        $(this).removeClass('zoom-effect');
                    }
                );

                // Add custom navigation for product gallery
                if ($('.flex-control-thumbs li').length > 4) {
                    $('.woocommerce-product-gallery').append('<div class="gallery-navigation"><button class="prev-thumb">←</button><button class="next-thumb">→</button></div>');
                    
                    $('.next-thumb').on('click', function() {
                        var $thumbs = $('.flex-control-thumbs');
                        var scrollPosition = $thumbs.scrollLeft();
                        $thumbs.animate({
                            scrollLeft: scrollPosition + 100
                        }, 300);
                    });
                    
                    $('.prev-thumb').on('click', function() {
                        var $thumbs = $('.flex-control-thumbs');
                        var scrollPosition = $thumbs.scrollLeft();
                        $thumbs.animate({
                            scrollLeft: scrollPosition - 100
                        }, 300);
                    });
                }
            }
        },

        // Improve accessibility
        improveAccessibility: function() {
            // Add ARIA attributes to menu items with submenus
            $('.menu-item-has-children > a').attr('aria-expanded', 'false');
            $('.menu-item-has-children > a').on('click', function(e) {
                if ($(window).width() > 992) {
                    return;
                }
                e.preventDefault();
                var $this = $(this);
                $this.attr('aria-expanded', $this.attr('aria-expanded') === 'false' ? 'true' : 'false');
                $this.siblings('.sub-menu').slideToggle();
            });

            // Add skip link focus
            $('.skip-link').on('focus', function() {
                $(this).css('top', '0');
            }).on('blur', function() {
                $(this).css('top', '-50px');
            });
        },

        // Add custom effects and interactions
        addCustomEffects: function() {
            // Fade in elements when they come into view
            $(window).on('scroll', function() {
                $('.fade-in-element').each(function() {
                    var $element = $(this);
                    if ($element.isInViewport() && !$element.hasClass('visible')) {
                        $element.addClass('visible');
                    }
                });
            });

            // Add hover effects to service items
            $('.service-item').hover(
                function() {
                    $(this).find('.service-icon').addClass('animate-bounce');
                },
                function() {
                    $(this).find('.service-icon').removeClass('animate-bounce');
                }
            );

            // Add counter animation to stats
            $('.stat-counter').each(function() {
                var $this = $(this);
                var countTo = $this.attr('data-count');
                
                $({ countNum: 0 }).animate({
                    countNum: countTo
                }, {
                    duration: 2000,
                    easing: 'linear',
                    step: function() {
                        $this.text(Math.floor(this.countNum));
                    },
                    complete: function() {
                        $this.text(this.countNum);
                    }
                });
            });
        }
    };

    // Helper function to check if element is in viewport
    $.fn.isInViewport = function() {
        var elementTop = $(this).offset().top;
        var elementBottom = elementTop + $(this).outerHeight();
        var viewportTop = $(window).scrollTop();
        var viewportBottom = viewportTop + $(window).height();
        return elementBottom > viewportTop && elementTop < viewportBottom;
    };

})(jQuery);