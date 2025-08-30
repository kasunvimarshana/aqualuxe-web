/**
 * AquaLuxe WooCommerce JavaScript
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    // Document ready
    $(document).ready(function() {
        // Initialize WooCommerce features
        AquaLuxeWoo.init();
    });
    
    // AquaLuxeWoo object
    window.AquaLuxeWoo = {
        /**
         * Initialize WooCommerce features
         */
        init: function() {
            this.bindEvents();
            this.initProductQuickView();
            this.initAjaxAddToCart();
            this.initVariationSelection();
            this.initQuantityButtons();
        },
        
        /**
         * Bind events
         */
        bindEvents: function() {
            // Quick view
            $(document).on('click', '.quick-view', this.handleQuickViewClick);
            
            // Ajax add to cart
            $(document).on('click', '.ajax_add_to_cart', this.handleAjaxAddToCart);
            
            // Variation selection
            $(document).on('change', '.variations select', this.handleVariationChange);
            
            // Quantity buttons
            $(document).on('click', '.quantity-button', this.handleQuantityButtonClick);
        },
        
        /**
         * Initialize product quick view
         */
        initProductQuickView: function() {
            // Add quick view buttons to product listings
            $('.product-card, .product').each(function() {
                var productId = $(this).data('product-id');
                if (productId) {
                    $(this).append('<button class="quick-view" data-product-id="' + productId + '">Quick View</button>');
                }
            });
        },
        
        /**
         * Initialize AJAX add to cart
         */
        initAjaxAddToCart: function() {
            // Add loading class to add to cart buttons
            $(document).on('click', '.add_to_cart_button', function() {
                $(this).addClass('loading');
            });
            
            // Remove loading class when AJAX is complete
            $(document.body).on('added_to_cart', function() {
                $('.add_to_cart_button').removeClass('loading');
            });
        },
        
        /**
         * Initialize variation selection
         */
        initVariationSelection: function() {
            // Only run on product pages
            if (!$('body').hasClass('single-product')) {
                return;
            }
            
            // Handle variation selection
            $('.variations_form').on('found_variation', function(event, variation) {
                // Update price display
                if (variation.price_html) {
                    $('.product-price').html(variation.price_html);
                }
                
                // Update stock status
                if (variation.availability_html) {
                    $('.stock').html(variation.availability_html);
                }
            });
        },
        
        /**
         * Initialize quantity buttons
         */
        initQuantityButtons: function() {
            // Add quantity buttons to quantity inputs
            $('.quantity').each(function() {
                var quantityInput = $(this).find('input[type="number"]');
                var quantityValue = parseInt(quantityInput.val());
                
                // Add buttons if they don't exist
                if (!$(this).find('.quantity-button').length) {
                    quantityInput.before('<button type="button" class="quantity-button quantity-down">-</button>');
                    quantityInput.after('<button type="button" class="quantity-button quantity-up">+</button>');
                }
            });
        },
        
        /**
         * Handle quick view click
         */
        handleQuickViewClick: function() {
            var button = $(this);
            var productId = button.data('product-id');
            
            // Add loading class
            button.addClass('loading');
            
            // Send AJAX request
            $.post(aqualuxe_vars.ajax_url, {
                action: 'aqualuxe_quick_view',
                product_id: productId,
                nonce: aqualuxe_vars.nonce
            }, function(response) {
                // Remove loading class
                button.removeClass('loading');
                
                // Handle response
                if (response.success) {
                    // Show quick view modal
                    AquaLuxeWoo.showQuickViewModal(response.data);
                } else {
                    // Show error message
                    AquaLuxeWoo.showMessage(response.data, 'error');
                }
            });
        },
        
        /**
         * Handle AJAX add to cart
         */
        handleAjaxAddToCart: function() {
            var button = $(this);
            var productID = button.data('product_id');
            var quantity = button.siblings('.quantity').find('input[type="number"]').val() || 1;
            
            // Add loading class
            button.addClass('loading');
            
            // Send AJAX request
            $.post(aqualuxe_vars.ajax_url, {
                action: 'aqualuxe_add_to_cart',
                product_id: productID,
                quantity: quantity,
                nonce: aqualuxe_vars.nonce
            }, function(response) {
                // Remove loading class
                button.removeClass('loading');
                
                // Handle response
                if (response.success) {
                    // Update cart fragments
                    $(document.body).trigger('wc_fragment_refresh');
                    
                    // Show success message
                    AquaLuxeWoo.showMessage(response.data, 'success');
                } else {
                    // Show error message
                    AquaLuxeWoo.showMessage(response.data, 'error');
                }
            });
        },
        
        /**
         * Handle variation change
         */
        handleVariationChange: function() {
            var form = $(this).closest('.variations_form');
            var variationId = form.find('input[name="variation_id"]').val();
            
            // Update add to cart button
            if (variationId) {
                form.find('.single_add_to_cart_button').removeClass('disabled').prop('disabled', false);
            } else {
                form.find('.single_add_to_cart_button').addClass('disabled').prop('disabled', true);
            }
        },
        
        /**
         * Handle quantity button click
         */
        handleQuantityButtonClick: function() {
            var button = $(this);
            var quantityInput = button.siblings('input[type="number"]');
            var currentValue = parseInt(quantityInput.val());
            var maxValue = parseInt(quantityInput.attr('max')) || 0;
            var minValue = parseInt(quantityInput.attr('min')) || 0;
            
            // Increase or decrease quantity
            if (button.hasClass('quantity-up')) {
                if (maxValue === 0 || currentValue < maxValue) {
                    quantityInput.val(currentValue + 1);
                }
            } else if (button.hasClass('quantity-down')) {
                if (currentValue > minValue) {
                    quantityInput.val(currentValue - 1);
                }
            }
            
            // Trigger change event
            quantityInput.trigger('change');
        },
        
        /**
         * Show quick view modal
         */
        showQuickViewModal: function(content) {
            // Create modal element
            var modal = $('<div class="quick-view-modal"><div class="quick-view-content">' + content + '<button class="close-modal">X</button></div></div>');
            
            // Add to page
            $('body').append(modal);
            
            // Add close event
            modal.find('.close-modal').on('click', function() {
                modal.remove();
            });
            
            // Close on escape key
            $(document).on('keyup', function(e) {
                if (e.keyCode === 27) {
                    modal.remove();
                }
            });
        },
        
        /**
         * Show message
         */
        showMessage: function(message, type) {
            // Create message element
            var messageElement = $('<div class="aqualuxe-message ' + type + '">' + message + '</div>');
            
            // Add to page
            $('body').append(messageElement);
            
            // Remove after 3 seconds
            setTimeout(function() {
                messageElement.fadeOut(function() {
                    messageElement.remove();
                });
            }, 3000);
        },
        
        /**
         * Update cart count
         */
        updateCartCount: function(count) {
            $('.cart-count').text(count);
        }
    };
    
})(jQuery);