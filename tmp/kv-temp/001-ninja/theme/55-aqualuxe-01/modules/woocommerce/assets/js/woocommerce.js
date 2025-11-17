/**
 * WooCommerce JavaScript
 *
 * @package AquaLuxe
 * @subpackage Modules/WooCommerce
 */

(function($) {
    'use strict';

    // WooCommerce functionality
    const AquaLuxeWooCommerce = {
        /**
         * Initialize WooCommerce functionality
         */
        init: function() {
            this.initQuickView();
            this.initWishlist();
            this.initCompare();
            this.initQuantityButtons();
            this.initProductGallery();
            this.initProductTabs();
            this.initSizeGuide();
            this.initProductCountdown();
            this.initAjaxAddToCart();
            this.initMiniCart();
        },

        /**
         * Initialize Quick View
         */
        initQuickView: function() {
            if (!aqualuxeWooCommerce.quickView) {
                return;
            }

            // Quick view functionality is handled by Alpine.js in the quick-view-modal.php template
            
            // Add event listener for quick view button clicks
            $(document).on('click', '.quick-view-button', function(e) {
                e.preventDefault();
                const productId = $(this).data('product-id');
                
                // Trigger Alpine.js quick view open
                const quickViewModal = document.getElementById('quick-view-modal');
                if (quickViewModal && quickViewModal.__x) {
                    const alpineComponent = quickViewModal.__x.getUnobservedData();
                    alpineComponent.productId = productId;
                    alpineComponent.isOpen = true;
                    alpineComponent.loadQuickViewContent();
                }
            });
        },

        /**
         * Initialize Wishlist
         */
        initWishlist: function() {
            if (!aqualuxeWooCommerce.wishlist) {
                return;
            }

            // Add event listener for wishlist button clicks
            $(document).on('click', '.wishlist-button', function(e) {
                e.preventDefault();
                const $button = $(this);
                const productId = $button.data('product-id');
                
                // Add loading state
                $button.addClass('loading');
                
                // Send AJAX request
                $.ajax({
                    url: aqualuxeWooCommerce.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_wishlist_toggle',
                        product_id: productId,
                        nonce: aqualuxeWooCommerce.nonce
                    },
                    success: function(response) {
                        // Remove loading state
                        $button.removeClass('loading');
                        
                        if (response.success) {
                            // Toggle wishlist class
                            if (response.data.in_wishlist) {
                                $button.addClass('in-wishlist');
                                $button.find('span').text(aqualuxeWooCommerce.i18n.removeFromWishlist);
                            } else {
                                $button.removeClass('in-wishlist');
                                $button.find('span').text(aqualuxeWooCommerce.i18n.addToWishlist);
                            }
                        }
                    },
                    error: function() {
                        // Remove loading state
                        $button.removeClass('loading');
                    }
                });
            });
        },

        /**
         * Initialize Compare
         */
        initCompare: function() {
            if (!aqualuxeWooCommerce.compare) {
                return;
            }

            // Add event listener for compare button clicks
            $(document).on('click', '.compare-button', function(e) {
                e.preventDefault();
                const $button = $(this);
                const productId = $button.data('product-id');
                
                // Add loading state
                $button.addClass('loading');
                
                // Send AJAX request
                $.ajax({
                    url: aqualuxeWooCommerce.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_compare_toggle',
                        product_id: productId,
                        nonce: aqualuxeWooCommerce.nonce
                    },
                    success: function(response) {
                        // Remove loading state
                        $button.removeClass('loading');
                        
                        if (response.success) {
                            // Toggle compare class
                            if (response.data.in_compare) {
                                $button.addClass('in-compare');
                                $button.find('span').text(aqualuxeWooCommerce.i18n.removeFromCompare);
                            } else {
                                $button.removeClass('in-compare');
                                $button.find('span').text(aqualuxeWooCommerce.i18n.addToCompare);
                            }
                        }
                    },
                    error: function() {
                        // Remove loading state
                        $button.removeClass('loading');
                    }
                });
            });
        },

        /**
         * Initialize Quantity Buttons
         */
        initQuantityButtons: function() {
            // Add quantity buttons
            function addQuantityButtons() {
                $('.quantity').each(function() {
                    const $quantity = $(this);
                    const $input = $quantity.find('input[type="number"]');
                    
                    // Only add buttons if they don't exist
                    if ($quantity.find('.quantity-button').length === 0) {
                        $quantity.addClass('quantity-with-buttons');
                        
                        // Add minus button
                        $('<button type="button" class="quantity-button quantity-down">-</button>')
                            .insertBefore($input);
                        
                        // Add plus button
                        $('<button type="button" class="quantity-button quantity-up">+</button>')
                            .insertAfter($input);
                    }
                });
            }
            
            // Add quantity buttons on page load
            addQuantityButtons();
            
            // Add quantity buttons when fragments are refreshed
            $(document.body).on('updated_cart_totals wc_fragments_refreshed', function() {
                addQuantityButtons();
            });
            
            // Handle quantity button clicks
            $(document).on('click', '.quantity-button', function() {
                const $button = $(this);
                const $input = $button.closest('.quantity').find('input[type="number"]');
                const step = $input.attr('step') ? parseFloat($input.attr('step')) : 1;
                const min = $input.attr('min') ? parseFloat($input.attr('min')) : 0;
                const max = $input.attr('max') ? parseFloat($input.attr('max')) : '';
                let value = parseFloat($input.val());
                
                // Increment or decrement value
                if ($button.hasClass('quantity-up')) {
                    value += step;
                } else {
                    value -= step;
                }
                
                // Check min and max values
                if (min && value < min) {
                    value = min;
                }
                
                if (max && value > max) {
                    value = max;
                }
                
                // Update input value
                $input.val(value).trigger('change');
            });
        },

        /**
         * Initialize Product Gallery
         */
        initProductGallery: function() {
            // Initialize product gallery on page load
            if (typeof $.fn.wc_product_gallery !== 'undefined') {
                $('.woocommerce-product-gallery').each(function() {
                    $(this).wc_product_gallery();
                });
            }
            
            // Initialize quick view thumbnails
            $(document).on('click', '.quick-view-thumbnail', function() {
                const $thumbnail = $(this);
                const $image = $thumbnail.find('img');
                const $gallery = $thumbnail.closest('.quick-view-product-image').find('.woocommerce-product-gallery__image');
                const $galleryImg = $gallery.find('img');
                const $galleryLink = $gallery.find('a');
                
                // Update gallery image
                $galleryImg.attr('src', $image.attr('src'));
                $galleryImg.attr('srcset', $image.attr('srcset'));
                $galleryImg.attr('data-src', $image.attr('src'));
                $galleryImg.attr('data-large_image', $image.attr('src'));
                
                // Update gallery link
                $galleryLink.attr('href', $image.attr('src'));
                
                // Update active thumbnail
                $thumbnail.siblings().removeClass('active');
                $thumbnail.addClass('active');
            });
        },

        /**
         * Initialize Product Tabs
         */
        initProductTabs: function() {
            // Product tabs functionality is handled by WooCommerce
        },

        /**
         * Initialize Size Guide
         */
        initSizeGuide: function() {
            // Size guide functionality is handled by Alpine.js in the size-guide-modal.php template
            
            // Add event listener for size guide button clicks
            $(document).on('click', '.size-guide-button', function(e) {
                e.preventDefault();
                
                // Trigger Alpine.js size guide open
                const sizeGuideModal = document.getElementById('size-guide-modal');
                if (sizeGuideModal && sizeGuideModal.__x) {
                    const alpineComponent = sizeGuideModal.__x.getUnobservedData();
                    alpineComponent.isOpen = true;
                }
            });
        },

        /**
         * Initialize Product Countdown
         */
        initProductCountdown: function() {
            // Initialize countdown timers
            $('.product-countdown').each(function() {
                const $countdown = $(this);
                const endDate = parseInt($countdown.data('end-date'), 10) * 1000;
                
                // Update countdown
                function updateCountdown() {
                    const now = new Date().getTime();
                    const distance = endDate - now;
                    
                    if (distance <= 0) {
                        // Countdown finished
                        clearInterval(interval);
                        $countdown.remove();
                        return;
                    }
                    
                    // Calculate days, hours, minutes, and seconds
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    // Update countdown values
                    $countdown.find('.days').text(days);
                    $countdown.find('.hours').text(hours);
                    $countdown.find('.minutes').text(minutes);
                    $countdown.find('.seconds').text(seconds);
                }
                
                // Update countdown immediately
                updateCountdown();
                
                // Update countdown every second
                const interval = setInterval(updateCountdown, 1000);
            });
        },

        /**
         * Initialize AJAX Add to Cart
         */
        initAjaxAddToCart: function() {
            // Add event listener for add to cart button clicks
            $(document).on('click', '.add_to_cart_button:not(.product_type_variable)', function() {
                const $button = $(this);
                
                // Add loading state
                $button.addClass('loading');
                
                // Update button text
                $button.text(aqualuxeWooCommerce.i18n.addingToCart);
            });
            
            // Handle added to cart event
            $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
                // Remove loading state
                $button.removeClass('loading');
                
                // Update button text
                $button.text(aqualuxeWooCommerce.i18n.addedToCart);
                
                // Reset button text after 2 seconds
                setTimeout(function() {
                    $button.text(aqualuxeWooCommerce.i18n.addToCart);
                }, 2000);
            });
        },

        /**
         * Initialize Mini Cart
         */
        initMiniCart: function() {
            // Mini cart functionality is handled by Alpine.js in the mini-cart.php template
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeWooCommerce.init();
    });
})(jQuery);