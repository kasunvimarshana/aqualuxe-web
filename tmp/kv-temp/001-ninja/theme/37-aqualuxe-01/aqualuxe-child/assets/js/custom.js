/**
 * AquaLuxe Child Theme - Custom JavaScript
 * 
 * This file contains custom JavaScript functionality for the AquaLuxe Child theme.
 */

(function($) {
    'use strict';

    // Document ready function
    $(document).ready(function() {
        // Example: Add a scroll effect to the header
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $('.site-header').addClass('scrolled');
            } else {
                $('.site-header').removeClass('scrolled');
            }
        });

        // Example: Add a smooth scroll effect to anchor links
        $('a[href*="#"]:not([href="#"])').click(function() {
            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
                let target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 1000);
                    return false;
                }
            }
        });

        // Example: Add a toggle for mobile submenu
        $('.menu-item-has-children > a').append('<span class="submenu-toggle"><i class="fas fa-chevron-down"></i></span>');
        $('.submenu-toggle').on('click', function(e) {
            e.preventDefault();
            $(this).toggleClass('toggled');
            $(this).closest('.menu-item-has-children').find('> .sub-menu').slideToggle(200);
        });

        // Example: WooCommerce specific functionality
        if (typeof woocommerce !== 'undefined') {
            // Add quick view functionality (example)
            $('.quick-view-button').on('click', function(e) {
                e.preventDefault();
                const productId = $(this).data('product-id');
                
                // Show loading spinner
                $('#quick-view-modal').addClass('loading').fadeIn();
                
                // AJAX call to get product details
                $.ajax({
                    url: aqualuxe_child.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_quick_view',
                        product_id: productId,
                        security: aqualuxe_child.nonce
                    },
                    success: function(response) {
                        $('#quick-view-modal').removeClass('loading');
                        $('#quick-view-content').html(response);
                    }
                });
            });
        }
    });

    // Example: Custom product image zoom effect
    function initProductZoom() {
        $('.woocommerce-product-gallery__image').zoom({
            url: $(this).find('img').attr('data-large_image'),
            touch: false
        });
    }

    // Initialize custom features if needed
    function initCustomFeatures() {
        // Check if we're on a product page
        if ($('.single-product').length) {
            initProductZoom();
        }
    }

    // Call initialization function
    $(window).on('load', function() {
        initCustomFeatures();
    });

})(jQuery);