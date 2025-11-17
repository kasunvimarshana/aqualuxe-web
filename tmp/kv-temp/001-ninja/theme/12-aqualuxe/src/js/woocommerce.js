/**
 * AquaLuxe WooCommerce JavaScript
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // WooCommerce functionality
    const AquaLuxeWooCommerce = {
        /**
         * Initialize
         */
        init: function() {
            this.initAjaxAddToCart();
            this.initQuantityButtons();
            this.initQuickView();
            this.initWishlist();
            this.initCompare();
            this.initMiniCart();
            this.initShopFilters();
            this.initProductGallery();
            this.initProductTabs();
            this.initCheckoutEnhancements();
        },

        /**
         * Initialize AJAX Add to Cart
         */
        initAjaxAddToCart: function() {
            $(document).on('click', '.add_to_cart_button:not(.product_type_variable)', function(e) {
                const $button = $(this);
                
                if ($button.hasClass('ajax_add_to_cart')) {
                    e.preventDefault();
                    
                    if ($button.hasClass('loading')) {
                        return;
                    }
                    
                    const productId = $button.data('product_id');
                    const quantity = $button.data('quantity') || 1;
                    
                    $button.addClass('loading');
                    
                    $.ajax({
                        type: 'POST',
                        url: aqualuxeWooCommerce.ajaxurl,
                        data: {
                            action: 'aqualuxe_ajax_add_to_cart',
                            product_id: productId,
                            quantity: quantity,
                            nonce: aqualuxeWooCommerce.nonce
                        },
                        success: function(response) {
                            $button.removeClass('loading');
                            
                            if (response.fragments) {
                                $.each(response.fragments, function(key, value) {
                                    $(key).replaceWith(value);
                                });
                            }
                            
                            if (response.cart_hash) {
                                $button.addClass('added');
                                
                                // Show notification
                                AquaLuxeWooCommerce.showNotification(aqualuxeWooCommerce.i18n.addedToCart);
                                
                                // Trigger event so themes can refresh other areas
                                $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);
                            }
                        },
                        error: function() {
                            $button.removeClass('loading');
                            AquaLuxeWooCommerce.showNotification(aqualuxeWooCommerce.i18n.errorMessage, 'error');
                        }
                    });
                }
            });
        },

        /**
         * Initialize Quantity Buttons
         */
        initQuantityButtons: function() {
            $(document).on('click', '.quantity-button', function() {
                const $button = $(this);
                const $input = $button.parent().find('input.qty');
                const oldValue = parseFloat($input.val());
                let newVal = oldValue;
                
                if ($button.hasClass('plus')) {
                    const max = parseFloat($input.attr('max'));
                    if (max && (max === oldValue || oldValue > max)) {
                        newVal = max;
                    } else {
                        newVal = oldValue + 1;
                    }
                } else {
                    const min = parseFloat($input.attr('min'));
                    if (min && (min === oldValue || oldValue < min)) {
                        newVal = min;
                    } else if (oldValue > 0) {
                        newVal = oldValue - 1;
                    }
                }
                
                $input.val(newVal).trigger('change');
            });
            
            // Add quantity buttons if they don't exist
            $('.quantity:not(.buttons-added)').addClass('buttons-added').append('<button type="button" class="quantity-button plus">+</button>').prepend('<button type="button" class="quantity-button minus">-</button>');
        },

        /**
         * Initialize Quick View
         */
        initQuickView: function() {
            $(document).on('click', '.quick-view', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const productId = $button.data('product-id');
                
                if (!productId) {
                    return;
                }
                
                // Create modal if it doesn't exist
                if ($('#quick-view-modal').length === 0) {
                    $('body').append('<div id="quick-view-modal" class="quick-view-modal"></div>');
                }
                
                const $modal = $('#quick-view-modal');
                
                $modal.addClass('loading');
                $modal.addClass('active');
                
                $.ajax({
                    type: 'POST',
                    url: aqualuxeWooCommerce.ajaxurl,
                    data: {
                        action: 'aqualuxe_quick_view',
                        product_id: productId,
                        nonce: aqualuxeWooCommerce.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            $modal.html(response.data);
                            $modal.removeClass('loading');
                            
                            // Initialize quantity buttons
                            AquaLuxeWooCommerce.initQuantityButtons();
                            
                            // Initialize gallery
                            AquaLuxeWooCommerce.initQuickViewGallery();
                            
                            // Close modal when clicking on close button or outside
                            $('.quick-view-close, .quick-view-modal').on('click', function(e) {
                                if (e.target === this) {
                                    $modal.removeClass('active');
                                }
                            });
                            
                            // Prevent closing when clicking inside content
                            $('.quick-view-content').on('click', function(e) {
                                e.stopPropagation();
                            });
                        }
                    },
                    error: function() {
                        $modal.removeClass('loading').removeClass('active');
                        AquaLuxeWooCommerce.showNotification(aqualuxeWooCommerce.i18n.errorMessage, 'error');
                    }
                });
            });
        },

        /**
         * Initialize Quick View Gallery
         */
        initQuickViewGallery: function() {
            $('.quick-view-thumbnail').on('click', function() {
                const $thumbnail = $(this);
                const $mainImage = $('.quick-view-image-main');
                const imageHtml = $thumbnail.html();
                
                $mainImage.html(imageHtml);
                $thumbnail.siblings().removeClass('active');
                $thumbnail.addClass('active');
            });
        },

        /**
         * Initialize Wishlist
         */
        initWishlist: function() {
            $(document).on('click', '.add-to-wishlist', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const productId = $button.data('product-id');
                
                if (!productId) {
                    return;
                }
                
                $button.addClass('loading');
                
                $.ajax({
                    type: 'POST',
                    url: aqualuxeWooCommerce.ajaxurl,
                    data: {
                        action: 'aqualuxe_add_to_wishlist',
                        product_id: productId,
                        nonce: aqualuxeWooCommerce.nonce
                    },
                    success: function(response) {
                        $button.removeClass('loading');
                        
                        if (response.success) {
                            $button.addClass('added');
                            $button.find('i').removeClass('far').addClass('fas');
                            AquaLuxeWooCommerce.showNotification(aqualuxeWooCommerce.i18n.addedToWishlist);
                        }
                    },
                    error: function() {
                        $button.removeClass('loading');
                        AquaLuxeWooCommerce.showNotification(aqualuxeWooCommerce.i18n.errorMessage, 'error');
                    }
                });
            });
            
            $(document).on('click', '.remove-from-wishlist', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const productId = $button.data('product-id');
                const $item = $button.closest('.wishlist-item');
                
                if (!productId) {
                    return;
                }
                
                $button.addClass('loading');
                
                $.ajax({
                    type: 'POST',
                    url: aqualuxeWooCommerce.ajaxurl,
                    data: {
                        action: 'aqualuxe_remove_from_wishlist',
                        product_id: productId,
                        nonce: aqualuxeWooCommerce.nonce
                    },
                    success: function(response) {
                        $button.removeClass('loading');
                        
                        if (response.success) {
                            $item.fadeOut(300, function() {
                                $item.remove();
                                
                                // Check if wishlist is empty
                                if ($('.wishlist-items .wishlist-item').length === 0) {
                                    $('.wishlist-items').html('<p class="wishlist-empty">' + aqualuxeWooCommerce.i18n.emptyWishlist + '</p>');
                                }
                            });
                            
                            AquaLuxeWooCommerce.showNotification(aqualuxeWooCommerce.i18n.removedFromWishlist);
                        }
                    },
                    error: function() {
                        $button.removeClass('loading');
                        AquaLuxeWooCommerce.showNotification(aqualuxeWooCommerce.i18n.errorMessage, 'error');
                    }
                });
            });
        },

        /**
         * Initialize Compare
         */
        initCompare: function() {
            $(document).on('click', '.add-to-compare', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const productId = $button.data('product-id');
                
                if (!productId) {
                    return;
                }
                
                $button.addClass('loading');
                
                $.ajax({
                    type: 'POST',
                    url: aqualuxeWooCommerce.ajaxurl,
                    data: {
                        action: 'aqualuxe_add_to_compare',
                        product_id: productId,
                        nonce: aqualuxeWooCommerce.nonce
                    },
                    success: function(response) {
                        $button.removeClass('loading');
                        
                        if (response.success) {
                            $button.addClass('added');
                            AquaLuxeWooCommerce.showNotification(aqualuxeWooCommerce.i18n.addedToCompare);
                        }
                    },
                    error: function() {
                        $button.removeClass('loading');
                        AquaLuxeWooCommerce.showNotification(aqualuxeWooCommerce.i18n.errorMessage, 'error');
                    }
                });
            });
            
            $(document).on('click', '.remove-from-compare', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const productId = $button.data('product-id');
                const $item = $button.closest('.compare-item');
                
                if (!productId) {
                    return;
                }
                
                $button.addClass('loading');
                
                $.ajax({
                    type: 'POST',
                    url: aqualuxeWooCommerce.ajaxurl,
                    data: {
                        action: 'aqualuxe_remove_from_compare',
                        product_id: productId,
                        nonce: aqualuxeWooCommerce.nonce
                    },
                    success: function(response) {
                        $button.removeClass('loading');
                        
                        if (response.success) {
                            $item.fadeOut(300, function() {
                                $item.remove();
                                
                                // Check if compare is empty
                                if ($('.compare-items .compare-item').length === 0) {
                                    $('.compare-items').html('<p class="compare-empty">' + aqualuxeWooCommerce.i18n.emptyCompare + '</p>');
                                }
                            });
                            
                            AquaLuxeWooCommerce.showNotification(aqualuxeWooCommerce.i18n.removedFromCompare);
                        }
                    },
                    error: function() {
                        $button.removeClass('loading');
                        AquaLuxeWooCommerce.showNotification(aqualuxeWooCommerce.i18n.errorMessage, 'error');
                    }
                });
            });
        },

        /**
         * Initialize Mini Cart
         */
        initMiniCart: function() {
            // Toggle mini cart on click
            $('.cart-contents').on('click', function(e) {
                e.preventDefault();
                $('.mini-cart-dropdown').toggleClass('active');
            });
            
            // Close mini cart when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.site-header-cart').length) {
                    $('.mini-cart-dropdown').removeClass('active');
                }
            });
            
            // Update mini cart on added_to_cart event
            $(document.body).on('added_to_cart', function() {
                $('.mini-cart-dropdown').addClass('active');
                
                // Hide mini cart after 3 seconds
                setTimeout(function() {
                    $('.mini-cart-dropdown').removeClass('active');
                }, 3000);
            });
        },

        /**
         * Initialize Shop Filters
         */
        initShopFilters: function() {
            // Toggle shop filters on mobile
            $('.shop-filters-toggle').on('click', function() {
                $('.shop-filters-content').slideToggle(300);
                $(this).toggleClass('active');
            });
            
            // Price slider
            if (typeof $.fn.slider !== 'undefined') {
                const $priceSlider = $('.price_slider');
                
                if ($priceSlider.length) {
                    const minPrice = parseInt($priceSlider.data('min'), 10);
                    const maxPrice = parseInt($priceSlider.data('max'), 10);
                    const currentMinPrice = parseInt($priceSlider.data('current-min'), 10);
                    const currentMaxPrice = parseInt($priceSlider.data('current-max'), 10);
                    
                    $priceSlider.slider({
                        range: true,
                        min: minPrice,
                        max: maxPrice,
                        values: [currentMinPrice, currentMaxPrice],
                        slide: function(event, ui) {
                            $('.price_slider_amount .from').text(
                                aqualuxeWooCommerce.currency + ui.values[0]
                            );
                            
                            $('.price_slider_amount .to').text(
                                aqualuxeWooCommerce.currency + ui.values[1]
                            );
                            
                            $('input.min_price').val(ui.values[0]);
                            $('input.max_price').val(ui.values[1]);
                        }
                    });
                }
            }
        },

        /**
         * Initialize Product Gallery
         */
        initProductGallery: function() {
            if (typeof $.fn.flexslider !== 'undefined') {
                $('.woocommerce-product-gallery').each(function() {
                    const $gallery = $(this);
                    const $mainSlider = $gallery.find('.woocommerce-product-gallery__wrapper');
                    const $thumbnails = $gallery.find('.flex-control-thumbs');
                    
                    // Main slider
                    $mainSlider.flexslider({
                        animation: 'slide',
                        controlNav: false,
                        animationLoop: false,
                        slideshow: false,
                        directionNav: true,
                        prevText: '<i class="fas fa-chevron-left"></i>',
                        nextText: '<i class="fas fa-chevron-right"></i>',
                        start: function() {
                            $gallery.css('opacity', 1);
                        }
                    });
                    
                    // Thumbnails
                    $thumbnails.on('click', 'li', function() {
                        const index = $(this).index();
                        $mainSlider.flexslider(index);
                        $thumbnails.find('li').removeClass('flex-active-slide');
                        $(this).addClass('flex-active-slide');
                    });
                });
            }
        },

        /**
         * Initialize Product Tabs
         */
        initProductTabs: function() {
            $('.woocommerce-tabs').each(function() {
                const $tabs = $(this);
                const $tabLinks = $tabs.find('.tabs li a');
                const $tabPanels = $tabs.find('.woocommerce-Tabs-panel');
                
                $tabLinks.on('click', function(e) {
                    e.preventDefault();
                    
                    const $link = $(this);
                    const target = $link.attr('href');
                    
                    // Remove active class from all links and add to current
                    $tabLinks.parent().removeClass('active');
                    $link.parent().addClass('active');
                    
                    // Hide all panels and show current
                    $tabPanels.hide();
                    $(target).show();
                });
                
                // Show first tab by default
                $tabLinks.first().trigger('click');
            });
        },

        /**
         * Initialize Checkout Enhancements
         */
        initCheckoutEnhancements: function() {
            // Toggle shipping address form
            $('#ship-to-different-address-checkbox').on('change', function() {
                $('.shipping_address').slideToggle(300);
            });
            
            // Payment method selection
            $('.wc_payment_methods input[type="radio"]').on('change', function() {
                const $radio = $(this);
                const $method = $radio.closest('.wc_payment_method');
                
                $('.wc_payment_method').removeClass('active');
                $method.addClass('active');
                
                $('.payment_box').slideUp(300);
                $method.find('.payment_box').slideDown(300);
            });
            
            // Select first payment method by default
            $('.wc_payment_methods input[type="radio"]').first().trigger('change');
        },

        /**
         * Show Notification
         */
        showNotification: function(message, type = 'success') {
            // Create notification container if it doesn't exist
            if ($('#woocommerce-notification').length === 0) {
                $('body').append('<div id="woocommerce-notification"></div>');
            }
            
            const $notification = $('#woocommerce-notification');
            
            // Create notification
            const $notice = $('<div class="woocommerce-notification ' + type + '">' + message + '</div>');
            
            // Add to container
            $notification.html($notice);
            
            // Show notification
            setTimeout(function() {
                $notice.addClass('show');
            }, 100);
            
            // Hide notification after 3 seconds
            setTimeout(function() {
                $notice.removeClass('show');
                
                setTimeout(function() {
                    $notice.remove();
                }, 300);
            }, 3000);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeWooCommerce.init();
    });

})(jQuery);