/**
 * AquaLuxe WooCommerce Functionality
 * 
 * Handles WooCommerce specific functionality for the AquaLuxe theme
 */
(function($) {
    'use strict';

    // Main WooCommerce object
    const AquaLuxeWooCommerce = {
        // Initialize the WooCommerce functionality
        init: function() {
            this.quickViewButton = $('.quick-view-button');
            this.quickViewModal = $('#aqualuxe-quick-view-modal');
            this.quickViewClose = $('.aqualuxe-quick-view-close');
            this.quickViewContent = $('.aqualuxe-quick-view-content');
            
            // Bind events
            this.bindEvents();
            
            // Initialize quantity buttons
            this.initQuantityButtons();
            
            // Initialize AJAX add to cart
            if (aqualuxeWooCommerce.enableAjaxAddToCart === '1') {
                this.initAjaxAddToCart();
            }
        },

        // Bind all events
        bindEvents: function() {
            const self = this;
            
            // Quick view
            this.quickViewButton.on('click', function(e) {
                e.preventDefault();
                
                const productId = $(this).data('product-id');
                
                self.openQuickView(productId);
            });
            
            // Close quick view
            this.quickViewClose.on('click', function(e) {
                e.preventDefault();
                
                self.closeQuickView();
            });
            
            // Close quick view when clicking outside
            this.quickViewModal.on('click', function(e) {
                if ($(e.target).is(self.quickViewModal)) {
                    self.closeQuickView();
                }
            });
            
            // Close quick view when pressing ESC
            $(document).on('keyup', function(e) {
                if (e.key === 'Escape' && self.quickViewModal.hasClass('active')) {
                    self.closeQuickView();
                }
            });
        },

        // Initialize quantity buttons
        initQuantityButtons: function() {
            // Quantity plus button
            $(document).on('click', '.quantity-plus', function(e) {
                e.preventDefault();
                
                const $input = $(this).siblings('.qty');
                const val = parseInt($input.val());
                const max = parseInt($input.attr('max'));
                
                if (max && val >= max) {
                    $input.val(max);
                } else {
                    $input.val(val + 1);
                }
                
                $input.trigger('change');
            });
            
            // Quantity minus button
            $(document).on('click', '.quantity-minus', function(e) {
                e.preventDefault();
                
                const $input = $(this).siblings('.qty');
                const val = parseInt($input.val());
                const min = parseInt($input.attr('min'));
                
                if (min && val <= min) {
                    $input.val(min);
                } else if (val > 1) {
                    $input.val(val - 1);
                }
                
                $input.trigger('change');
            });
        },

        // Initialize AJAX add to cart
        initAjaxAddToCart: function() {
            const self = this;
            
            $(document).on('click', '.add_to_cart_button:not(.product_type_variable)', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                
                // Don't proceed if already adding
                if ($button.hasClass('adding')) {
                    return;
                }
                
                // Add loading state
                $button.addClass('adding').text(aqualuxeWooCommerce.i18n.addingToCart);
                
                // Get product data
                const productId = $button.data('product_id');
                const quantity = $button.data('quantity') || 1;
                
                // Add to cart via AJAX
                $.ajax({
                    url: aqualuxeWooCommerce.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_ajax_add_to_cart',
                        nonce: aqualuxeWooCommerce.nonce,
                        product_id: productId,
                        quantity: quantity
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update cart fragments
                            if (response.data.fragments) {
                                $.each(response.data.fragments, function(key, value) {
                                    $(key).replaceWith(value);
                                });
                            }
                            
                            // Update cart hash
                            if (response.data.cart_hash) {
                                $('.woocommerce-cart-form').attr('data-cart-hash', response.data.cart_hash);
                            }
                            
                            // Update button state
                            $button.removeClass('adding').addClass('added').text(aqualuxeWooCommerce.i18n.addedToCart);
                            
                            // Show notification
                            self.showNotification(aqualuxeWooCommerce.i18n.addedToCart);
                            
                            // Trigger event
                            $(document.body).trigger('added_to_cart', [response.data.fragments, response.data.cart_hash, $button]);
                        } else {
                            // Reset button state
                            $button.removeClass('adding').text(aqualuxeWooCommerce.i18n.addToCart);
                            
                            // Show error notification
                            self.showNotification(response.data.message || aqualuxeWooCommerce.i18n.errorAddingToCart);
                        }
                    },
                    error: function() {
                        // Reset button state
                        $button.removeClass('adding').text(aqualuxeWooCommerce.i18n.addToCart);
                        
                        // Show error notification
                        self.showNotification(aqualuxeWooCommerce.i18n.errorAddingToCart);
                    }
                });
            });
        },

        // Open quick view
        openQuickView: function(productId) {
            const self = this;
            
            // Show loading
            this.quickViewContent.html('<div class="aqualuxe-quick-view-loading"><div class="spinner"></div></div>');
            this.quickViewModal.addClass('active');
            
            // Get product data
            $.ajax({
                url: aqualuxeWooCommerce.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_quick_view',
                    nonce: aqualuxeWooCommerce.nonce,
                    product_id: productId
                },
                success: function(response) {
                    if (response.success) {
                        // Update content
                        self.quickViewContent.html(response.data.html);
                        
                        // Initialize quantity buttons
                        self.initQuantityButtons();
                        
                        // Initialize product gallery
                        self.initProductGallery();
                        
                        // Initialize variations
                        self.initVariations();
                    } else {
                        // Show error
                        self.quickViewContent.html('<div class="aqualuxe-quick-view-error">' + aqualuxeWooCommerce.i18n.errorLoadingProduct + '</div>');
                    }
                },
                error: function() {
                    // Show error
                    self.quickViewContent.html('<div class="aqualuxe-quick-view-error">' + aqualuxeWooCommerce.i18n.errorLoadingProduct + '</div>');
                }
            });
        },

        // Close quick view
        closeQuickView: function() {
            this.quickViewModal.removeClass('active');
            
            // Clear content after animation
            setTimeout(() => {
                this.quickViewContent.empty();
            }, 300);
        },

        // Initialize product gallery
        initProductGallery: function() {
            // Initialize product gallery slider
            if ($.fn.slick) {
                $('.aqualuxe-quick-view-gallery').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: true,
                    fade: true,
                    asNavFor: '.aqualuxe-quick-view-thumbnails'
                });
                
                $('.aqualuxe-quick-view-thumbnails').slick({
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    asNavFor: '.aqualuxe-quick-view-gallery',
                    dots: false,
                    arrows: false,
                    focusOnSelect: true
                });
            }
        },

        // Initialize variations
        initVariations: function() {
            const $form = $('.variations_form');
            
            if ($form.length) {
                $form.wc_variation_form();
            }
        },

        // Show notification
        showNotification: function(message) {
            // Check if notification container exists
            if (!$('.aqualuxe-notification-container').length) {
                $('body').append('<div class="aqualuxe-notification-container"></div>');
            }
            
            // Create notification
            const $notification = $('<div class="aqualuxe-notification">' + message + '</div>');
            
            // Add notification to container
            $('.aqualuxe-notification-container').append($notification);
            
            // Show notification
            setTimeout(function() {
                $notification.addClass('show');
            }, 100);
            
            // Hide notification
            setTimeout(function() {
                $notification.removeClass('show');
                
                setTimeout(function() {
                    $notification.remove();
                }, 300);
            }, 3000);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeWooCommerce.init();
    });

})(jQuery);