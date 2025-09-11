/**
 * WooCommerce JavaScript Module
 * 
 * Handles WooCommerce-specific functionality with graceful fallbacks.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // WooCommerce object
    const WooCommerce = {
        
        /**
         * Initialize WooCommerce functionality
         */
        init() {
            // Only initialize if WooCommerce is active
            if (!this.isWooCommerceActive()) {
                return;
            }
            
            this.bindEvents();
            this.initQuantityInputs();
            this.initProductGallery();
            this.initQuickView();
            this.initWishlist();
        },
        
        /**
         * Check if WooCommerce is active
         */
        isWooCommerceActive() {
            return typeof woocommerce !== 'undefined' || 
                   document.body.classList.contains('woocommerce') || 
                   document.body.classList.contains('woocommerce-page');
        },
        
        /**
         * Bind events
         */
        bindEvents() {
            // Add to cart button
            $(document).on('click', '.add_to_cart_button', this.handleAddToCart.bind(this));
            
            // Quantity changes
            $(document).on('change', '.qty', this.handleQuantityChange.bind(this));
            
            // Product variation changes
            $(document).on('change', '.variations select', this.handleVariationChange.bind(this));
            
            // Cart updates
            $(document).on('click', '.update-cart', this.handleCartUpdate.bind(this));
        },
        
        /**
         * Initialize quantity inputs
         */
        initQuantityInputs() {
            $('.quantity').each(function() {
                const $quantity = $(this);
                const $input = $quantity.find('.qty');
                const $minus = $('<button class="qty-btn minus" type="button">-</button>');
                const $plus = $('<button class="qty-btn plus" type="button">+</button>');
                
                $quantity.prepend($minus);
                $quantity.append($plus);
                
                // Minus button
                $minus.on('click', function(e) {
                    e.preventDefault();
                    const currentVal = parseInt($input.val()) || 0;
                    const min = parseInt($input.attr('min')) || 0;
                    if (currentVal > min) {
                        $input.val(currentVal - 1).trigger('change');
                    }
                });
                
                // Plus button
                $plus.on('click', function(e) {
                    e.preventDefault();
                    const currentVal = parseInt($input.val()) || 0;
                    const max = parseInt($input.attr('max')) || 999;
                    if (currentVal < max) {
                        $input.val(currentVal + 1).trigger('change');
                    }
                });
            });
        },
        
        /**
         * Initialize product gallery
         */
        initProductGallery() {
            // This would integrate with a carousel/lightbox library
            $('.product-gallery').each(function() {
                // Implementation would depend on chosen gallery library
                console.log('Product gallery initialization');
            });
        },
        
        /**
         * Initialize quick view
         */
        initQuickView() {
            $(document).on('click', '.quick-view-btn', function(e) {
                e.preventDefault();
                
                const productId = $(this).data('product-id');
                // Implementation for quick view modal
                console.log('Quick view for product:', productId);
            });
        },
        
        /**
         * Initialize wishlist
         */
        initWishlist() {
            $(document).on('click', '.wishlist-btn', function(e) {
                e.preventDefault();
                
                const $btn = $(this);
                const productId = $btn.data('product-id');
                
                // Toggle wishlist state
                $btn.toggleClass('active');
                
                console.log('Wishlist toggle for product:', productId);
            });
        },
        
        /**
         * Handle add to cart
         */
        handleAddToCart(e) {
            const $btn = $(e.currentTarget);
            
            // Add loading state
            $btn.addClass('loading').prop('disabled', true);
            
            // Original WooCommerce functionality will handle the actual AJAX
            // This is just for UI enhancements
        },
        
        /**
         * Handle quantity change
         */
        handleQuantityChange(e) {
            const $input = $(e.target);
            const quantity = parseInt($input.val()) || 0;
            const $row = $input.closest('tr');
            
            // Update subtotal display (if applicable)
            console.log('Quantity changed to:', quantity);
        },
        
        /**
         * Handle variation change
         */
        handleVariationChange(e) {
            const $select = $(e.target);
            const $form = $select.closest('form');
            
            // Update price and availability
            console.log('Variation changed');
        },
        
        /**
         * Handle cart update
         */
        handleCartUpdate(e) {
            const $btn = $(e.currentTarget);
            
            // Add loading state
            $btn.addClass('loading').prop('disabled', true).text('Updating...');
        }
    };
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        WooCommerce.init();
    });
    
    // Expose to global scope
    window.AquaLuxeWooCommerce = WooCommerce;
    
})(jQuery);