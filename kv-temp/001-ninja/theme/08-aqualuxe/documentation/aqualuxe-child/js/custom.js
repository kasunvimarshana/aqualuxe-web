/**
 * AquaLuxe Child Theme Custom JavaScript
 * 
 * This file contains custom JavaScript for the AquaLuxe child theme.
 * Add your custom JavaScript code here.
 */

(function($) {
    'use strict';
    
    // Document ready function
    $(document).ready(function() {
        
        // Example: Add a scroll to top button
        // Uncomment the code below to enable this feature
        /*
        // Create the button element
        var scrollToTopButton = $('<button id="scroll-to-top" aria-label="Scroll to top"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg></button>');
        
        // Append the button to the body
        $('body').append(scrollToTopButton);
        
        // Show/hide the button based on scroll position
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                scrollToTopButton.addClass('show');
            } else {
                scrollToTopButton.removeClass('show');
            }
        });
        
        // Scroll to top when the button is clicked
        scrollToTopButton.on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({scrollTop: 0}, 500);
        });
        */
        
        // Example: Add smooth scrolling to anchor links
        // Uncomment the code below to enable this feature
        /*
        $('a[href*="#"]:not([href="#"])').on('click', function() {
            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 500);
                    return false;
                }
            }
        });
        */
        
        // Example: Add a mobile menu enhancement
        // Uncomment the code below to enable this feature
        /*
        $('.mobile-menu-toggle').on('click', function() {
            $(this).toggleClass('active');
            $('.mobile-menu').toggleClass('active');
            $('body').toggleClass('mobile-menu-open');
        });
        
        // Close mobile menu when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.mobile-menu, .mobile-menu-toggle').length) {
                $('.mobile-menu-toggle').removeClass('active');
                $('.mobile-menu').removeClass('active');
                $('body').removeClass('mobile-menu-open');
            }
        });
        */
        
        // Example: Add a custom product image zoom effect
        // Uncomment the code below to enable this feature
        /*
        if ($('.woocommerce-product-gallery__image').length) {
            $('.woocommerce-product-gallery__image').on('mousemove', function(e) {
                var zoomer = $(this);
                var offsetX = e.offsetX ? e.offsetX : e.pageX - zoomer.offset().left;
                var offsetY = e.offsetY ? e.offsetY : e.pageY - zoomer.offset().top;
                var x = offsetX / zoomer.width() * 100;
                var y = offsetY / zoomer.height() * 100;
                zoomer.css('background-position', x + '% ' + y + '%');
            });
        }
        */
        
        // Example: Add a sticky header on scroll
        // Uncomment the code below to enable this feature
        /*
        var header = $('.site-header');
        var headerOffset = header.offset().top;
        
        $(window).scroll(function() {
            if ($(this).scrollTop() > headerOffset) {
                header.addClass('sticky');
                $('body').css('padding-top', header.outerHeight());
            } else {
                header.removeClass('sticky');
                $('body').css('padding-top', 0);
            }
        });
        */
        
        // Example: Add a custom accordion functionality
        // Uncomment the code below to enable this feature
        /*
        $('.custom-accordion .accordion-header').on('click', function() {
            var accordionItem = $(this).parent();
            var accordionContent = $(this).next('.accordion-content');
            
            if (accordionItem.hasClass('active')) {
                accordionItem.removeClass('active');
                accordionContent.slideUp(300);
            } else {
                $('.custom-accordion .accordion-item').removeClass('active');
                $('.custom-accordion .accordion-content').slideUp(300);
                accordionItem.addClass('active');
                accordionContent.slideDown(300);
            }
        });
        */
        
        // Add your custom JavaScript code here
        
    });
    
    // Window load function
    $(window).on('load', function() {
        // Example: Add a page loader
        // Uncomment the code below to enable this feature
        /*
        setTimeout(function() {
            $('.page-loader').fadeOut(500);
        }, 500);
        */
        
        // Add your custom JavaScript code here
        
    });
    
    // Window resize function
    $(window).on('resize', function() {
        // Example: Adjust elements on window resize
        // Uncomment the code below to enable this feature
        /*
        var windowWidth = $(window).width();
        
        if (windowWidth < 768) {
            // Mobile adjustments
        } else if (windowWidth < 1024) {
            // Tablet adjustments
        } else {
            // Desktop adjustments
        }
        */
        
        // Add your custom JavaScript code here
        
    });
    
})(jQuery);