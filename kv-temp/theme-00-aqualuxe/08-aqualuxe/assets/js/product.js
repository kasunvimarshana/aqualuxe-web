/**
 * Single Product JavaScript for AquaLuxe Theme
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

(function($) {
    'use strict';

    var AquaLuxeProduct = {
        
        /**
         * Initialize product functions
         */
        init: function() {
            this.productGallery();
            this.productTabs();
            this.variationHandling();
            this.quantityButtons();
            this.stickyAddToCart();
            this.productZoom();
            this.reviewHandling();
        },

        /**
         * Enhanced product gallery
         */
        productGallery: function() {
            // Initialize Flexslider for product gallery
            if ($('.woocommerce-product-gallery').length) {
                $('.woocommerce-product-gallery').flexslider({
                    animation: 'slide',
                    controlNav: 'thumbnails',
                    animationLoop: false,
                    slideshow: false,
                    itemWidth: 400,
                    itemMargin: 5,
                    asNavFor: '.woocommerce-product-gallery'
                });
            }

            // Gallery navigation with keyboard support
            $(document).on('keydown', '.woocommerce-product-gallery', function(e) {
                var $gallery = $(this);
                
                switch(e.keyCode) {
                    case 37: // Left arrow
                        e.preventDefault();
                        $gallery.flexslider('prev');
                        break;
                    case 39: // Right arrow
                        e.preventDefault();
                        $gallery.flexslider('next');
                        break;
                }
            });

            // Thumbnail hover effect
            $('.flex-control-thumbs li').on('mouseenter', function() {
                var index = $(this).index();
                $('.woocommerce-product-gallery').flexslider(index);
            });
        },

        /**
         * Product tabs enhancement
         */
        productTabs: function() {
            var $tabs = $('.woocommerce-tabs');
            
            if (!$tabs.length) return;

            // Add ARIA attributes
            $tabs.find('.tabs li a').attr('role', 'tab');
            $tabs.find('.panel').attr('role', 'tabpanel');

            // Handle tab switching
            $tabs.on('click', '.tabs li a', function(e) {
                e.preventDefault();
                
                var $tab = $(this);
                var $tabList = $tab.closest('.tabs');
                var $panels = $tab.closest('.woocommerce-tabs').find('.panel');
                var target = $tab.attr('href');

                // Update active states
                $tabList.find('li').removeClass('active');
                $tab.parent().addClass('active');

                // Update ARIA states
                $tabList.find('a').attr('aria-selected', 'false');
                $tab.attr('aria-selected', 'true');

                // Show/hide panels
                $panels.hide().attr('aria-hidden', 'true');
                $(target).show().attr('aria-hidden', 'false');

                // Scroll to tabs on mobile
                if ($(window).width() < 768) {
                    $('html, body').animate({
                        scrollTop: $tabs.offset().top - 100
                    }, 300);
                }
            });

            // Initialize first tab
            $tabs.find('.tabs li:first-child a').trigger('click');
        },

        /**
         * Variation handling
         */
        variationHandling: function() {
            var $variationForm = $('.variations_form');
            
            if (!$variationForm.length) return;

            // Custom variation display
            $variationForm.on('show_variation', function(event, variation) {
                $('.single-product-price').html(variation.price_html);
                
                if (variation.image && variation.image.src) {
                    $('.woocommerce-product-gallery img').first().attr('src', variation.image.src);
                }

                // Update availability
                if (variation.availability_html) {
                    $('.stock').html(variation.availability_html);
                }
            });

            // Reset variation
            $variationForm.on('hide_variation', function() {
                $('.single-product-price').html($('.single-product-price').data('original-price'));
                $('.woocommerce-product-gallery img').first().attr('src', $('.woocommerce-product-gallery img').first().data('original-src'));
            });

            // Store original values
            $('.single-product-price').data('original-price', $('.single-product-price').html());
            $('.woocommerce-product-gallery img').first().data('original-src', $('.woocommerce-product-gallery img').first().attr('src'));
        },

        /**
         * Quantity buttons
         */
        quantityButtons: function() {
            // Add quantity buttons
            $('body').on('click', '.quantity .plus, .quantity .minus', function() {
                var $button = $(this);
                var $input = $button.siblings('.qty');
                var currentVal = parseFloat($input.val()) || 0;
                var max = parseFloat($input.attr('max')) || '';
                var min = parseFloat($input.attr('min')) || 0;
                var step = parseFloat($input.attr('step')) || 1;

                if ($button.hasClass('plus')) {
                    if (max && (currentVal >= max)) {
                        $input.val(max);
                    } else {
                        $input.val(currentVal + step);
                    }
                } else {
                    if (currentVal <= min) {
                        $input.val(min);
                    } else {
                        $input.val(currentVal - step);
                    }
                }

                $input.trigger('change');
            });

            // Add buttons to quantity inputs
            $('.quantity:not(.buttons_added)').addClass('buttons_added').append('<input type="button" value="+" class="plus" />').prepend('<input type="button" value="-" class="minus" />');
        },

        /**
         * Sticky add to cart
         */
        stickyAddToCart: function() {
            var $stickyCart = $('.sticky-add-to-cart');
            var $productSummary = $('.product .summary');
            
            if (!$stickyCart.length || !$productSummary.length) return;

            $(window).on('scroll', function() {
                var summaryOffset = $productSummary.offset().top + $productSummary.outerHeight();
                var scrollTop = $(window).scrollTop();
                var windowHeight = $(window).height();

                if (scrollTop + windowHeight > summaryOffset + 100) {
                    $stickyCart.addClass('visible');
                } else {
                    $stickyCart.removeClass('visible');
                }
            });

            // Sync sticky cart with main form
            $stickyCart.find('.single_add_to_cart_button').on('click', function(e) {
                e.preventDefault();
                $('.product .summary .single_add_to_cart_button').trigger('click');
            });
        },

        /**
         * Product zoom functionality
         */
        productZoom: function() {
            if (typeof $.fn.zoom === 'undefined') return;

            $('.woocommerce-product-gallery__image').each(function() {
                var $image = $(this).find('img');
                
                if ($image.attr('data-large_image')) {
                    $(this).zoom({
                        url: $image.attr('data-large_image'),
                        touch: false
                    });
                }
            });
        },

        /**
         * Review handling
         */
        reviewHandling: function() {
            // Star rating interaction
            $('.comment-form-rating .stars').on('click', 'a', function(e) {
                e.preventDefault();
                var $star = $(this);
                var rating = $star.text();
                
                $star.siblings('a').removeClass('active');
                $star.addClass('active').prevAll('a').addClass('active');
                
                $('#rating').val(rating);
            });

            // Review form validation
            $('#commentform').on('submit', function(e) {
                var rating = $('#rating').val();
                
                if ($('.comment-form-rating').length && !rating) {
                    e.preventDefault();
                    alert('Please select a rating');
                    $('.comment-form-rating .stars').focus();
                }
            });

            // Review helpful buttons
            $('.review-helpful').on('click', '.helpful-btn', function(e) {
                e.preventDefault();
                
                var $button = $(this);
                var reviewId = $button.data('review-id');
                var helpful = $button.hasClass('helpful-yes');
                
                $.ajax({
                    url: aqualuxe_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_review_helpful',
                        review_id: reviewId,
                        helpful: helpful,
                        nonce: aqualuxe_ajax.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            $button.find('.count').text(response.data.count);
                            $button.addClass('voted').prop('disabled', true);
                        }
                    }
                });
            });
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        AquaLuxeProduct.init();
    });

    // Reinitialize on AJAX complete (for variation forms)
    $(document).ajaxComplete(function() {
        AquaLuxeProduct.variationHandling();
        AquaLuxeProduct.quantityButtons();
    });

})(jQuery);