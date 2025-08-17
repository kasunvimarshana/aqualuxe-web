/**
 * AJAX Cart Functionality
 * 
 * Handles the AJAX cart functionality for the AquaLuxe theme.
 */

(function($) {
    'use strict';

    const AjaxCart = {
        /**
         * Initialize the AJAX cart functionality
         */
        init: function() {
            this.cacheDom();
            this.bindEvents();
        },

        /**
         * Cache DOM elements
         */
        cacheDom: function() {
            this.$body = $('body');
            this.$miniCart = $('.mini-cart');
            this.$miniCartLink = this.$miniCart.find('.mini-cart-link');
            this.$miniCartDropdown = this.$miniCart.find('.mini-cart-dropdown');
            this.$miniCartCount = this.$miniCart.find('.mini-cart-count');
            this.$addToCartButtons = $('.add_to_cart_button');
            this.$cartForm = $('form.cart');
            this.$quantityInputs = $('.quantity input.qty');
            this.ajaxUrl = aqualuxeData.ajaxUrl;
            this.isUpdating = false;
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Mini cart toggle
            this.$miniCartLink.on('click', this.toggleMiniCart.bind(this));
            
            // Close mini cart when clicking outside
            $(document).on('click', this.handleOutsideClick.bind(this));
            
            // Add to cart button
            this.$body.on('click', '.ajax_add_to_cart', this.handleAddToCart.bind(this));
            
            // Remove from cart
            this.$body.on('click', '.mini-cart-item-remove a', this.handleRemoveFromCart.bind(this));
            
            // Update cart item quantity
            this.$body.on('change', '.woocommerce-cart-form .quantity .qty', this.handleQuantityChange.bind(this));
            
            // Update cart on quantity buttons click
            this.$body.on('click', '.quantity-button', this.handleQuantityButtonClick.bind(this));
            
            // Single product add to cart
            this.$cartForm.on('submit', this.handleSingleAddToCart.bind(this));
            
            // WooCommerce AJAX events
            this.$body.on('added_to_cart', this.afterAddToCart.bind(this));
            this.$body.on('removed_from_cart', this.afterRemoveFromCart.bind(this));
            this.$body.on('wc_fragments_refreshed', this.afterFragmentsRefreshed.bind(this));
            
            // Handle escape key
            $(document).on('keyup', this.handleEscapeKey.bind(this));
        },

        /**
         * Toggle mini cart
         * 
         * @param {Event} e The event object
         */
        toggleMiniCart: function(e) {
            e.preventDefault();
            
            this.$miniCartDropdown.toggleClass('active');
            
            // Dispatch custom event
            if (this.$miniCartDropdown.hasClass('active')) {
                this.dispatchEvent('miniCartOpened');
            } else {
                this.dispatchEvent('miniCartClosed');
            }
        },

        /**
         * Handle outside click
         * 
         * @param {Event} e The event object
         */
        handleOutsideClick: function(e) {
            if (this.$miniCartDropdown.hasClass('active') && 
                !$(e.target).closest(this.$miniCart).length) {
                this.$miniCartDropdown.removeClass('active');
                
                // Dispatch custom event
                this.dispatchEvent('miniCartClosed');
            }
        },

        /**
         * Handle add to cart
         * 
         * @param {Event} e The event object
         */
        handleAddToCart: function(e) {
            const $button = $(e.currentTarget);
            
            // Add loading class
            $button.addClass('loading');
        },

        /**
         * Handle single product add to cart
         * 
         * @param {Event} e The event object
         */
        handleSingleAddToCart: function(e) {
            const $form = $(e.currentTarget);
            const $button = $form.find('.single_add_to_cart_button');
            
            // Check if AJAX add to cart is enabled for single products
            if (typeof wc_add_to_cart_params !== 'undefined' && wc_add_to_cart_params.ajax_add_to_cart_single) {
                e.preventDefault();
                
                // Don't proceed if already processing
                if ($button.hasClass('loading')) {
                    return;
                }
                
                // Add loading class
                $button.addClass('loading');
                
                // Get form data
                const formData = new FormData($form[0]);
                formData.append('action', 'aqualuxe_ajax_add_to_cart');
                
                // Send AJAX request
                $.ajax({
                    url: this.ajaxUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: (response) => {
                        if (response.success) {
                            // Update fragments
                            if (response.data.fragments) {
                                this.updateFragments(response.data.fragments);
                            }
                            
                            // Show mini cart
                            this.$miniCartDropdown.addClass('active');
                            
                            // Hide mini cart after 5 seconds
                            setTimeout(() => {
                                this.$miniCartDropdown.removeClass('active');
                            }, 5000);
                            
                            // Dispatch custom event
                            this.dispatchEvent('productAddedToCart', {
                                productId: formData.get('add-to-cart'),
                                quantity: formData.get('quantity')
                            });
                        } else {
                            // Show error message
                            this.showNotification(response.data.message, 'error');
                        }
                    },
                    error: () => {
                        // Show error message
                        this.showNotification('Error adding product to cart. Please try again.', 'error');
                    },
                    complete: () => {
                        // Remove loading class
                        $button.removeClass('loading');
                    }
                });
            }
        },

        /**
         * Handle remove from cart
         * 
         * @param {Event} e The event object
         */
        handleRemoveFromCart: function(e) {
            const $link = $(e.currentTarget);
            
            // Don't proceed if already processing
            if (this.isUpdating) {
                return;
            }
            
            e.preventDefault();
            
            // Set updating flag
            this.isUpdating = true;
            
            // Add loading class
            $link.addClass('loading');
            
            // Get cart item key
            const cartItemKey = $link.data('cart_item_key');
            
            // Send AJAX request
            $.ajax({
                url: this.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_remove_from_cart',
                    cart_item_key: cartItemKey
                },
                success: (response) => {
                    if (response.success) {
                        // Update fragments
                        if (response.data.fragments) {
                            this.updateFragments(response.data.fragments);
                        }
                        
                        // Dispatch custom event
                        this.dispatchEvent('productRemovedFromCart', {
                            cartItemKey: cartItemKey
                        });
                    } else {
                        // Show error message
                        this.showNotification(response.data.message, 'error');
                    }
                },
                error: () => {
                    // Show error message
                    this.showNotification('Error removing product from cart. Please try again.', 'error');
                },
                complete: () => {
                    // Remove loading class
                    $link.removeClass('loading');
                    
                    // Reset updating flag
                    this.isUpdating = false;
                }
            });
        },

        /**
         * Handle quantity change
         * 
         * @param {Event} e The event object
         */
        handleQuantityChange: function(e) {
            const $input = $(e.currentTarget);
            const $form = $input.closest('form');
            
            // Don't proceed if already processing
            if (this.isUpdating) {
                return;
            }
            
            // Clear previous timeout
            clearTimeout(this.quantityTimeout);
            
            // Set timeout to update cart
            this.quantityTimeout = setTimeout(() => {
                // Set updating flag
                this.isUpdating = true;
                
                // Add loading class to form
                $form.addClass('loading');
                
                // Update cart
                this.updateCart();
            }, 500);
        },

        /**
         * Handle quantity button click
         * 
         * @param {Event} e The event object
         */
        handleQuantityButtonClick: function(e) {
            const $button = $(e.currentTarget);
            const $input = $button.parent().find('input.qty');
            const $form = $input.closest('form');
            
            // Don't proceed if already processing
            if (this.isUpdating) {
                return;
            }
            
            // Clear previous timeout
            clearTimeout(this.quantityTimeout);
            
            // Set timeout to update cart
            this.quantityTimeout = setTimeout(() => {
                // Only update if in cart page
                if ($form.hasClass('woocommerce-cart-form')) {
                    // Set updating flag
                    this.isUpdating = true;
                    
                    // Add loading class to form
                    $form.addClass('loading');
                    
                    // Update cart
                    this.updateCart();
                }
            }, 500);
        },

        /**
         * Update cart
         */
        updateCart: function() {
            const $form = $('.woocommerce-cart-form');
            
            // Don't proceed if no form
            if (!$form.length) {
                return;
            }
            
            // Get form data
            const formData = $form.serialize();
            
            // Send AJAX request
            $.ajax({
                url: this.ajaxUrl,
                type: 'POST',
                data: formData + '&action=aqualuxe_update_cart',
                success: (response) => {
                    if (response.success) {
                        // Update fragments
                        if (response.data.fragments) {
                            this.updateFragments(response.data.fragments);
                        }
                        
                        // Update cart totals
                        if (response.data.cart_totals) {
                            $('.cart-collaterals').html(response.data.cart_totals);
                        }
                        
                        // Dispatch custom event
                        this.dispatchEvent('cartUpdated');
                    } else {
                        // Show error message
                        this.showNotification(response.data.message, 'error');
                    }
                },
                error: () => {
                    // Show error message
                    this.showNotification('Error updating cart. Please try again.', 'error');
                },
                complete: () => {
                    // Remove loading class
                    $form.removeClass('loading');
                    
                    // Reset updating flag
                    this.isUpdating = false;
                }
            });
        },

        /**
         * After add to cart
         * 
         * @param {Event} e The event object
         * @param {Object} fragments The fragments
         * @param {string} cart_hash The cart hash
         * @param {jQuery} $button The button
         */
        afterAddToCart: function(e, fragments, cart_hash, $button) {
            // Remove loading class
            $button.removeClass('loading');
            
            // Show mini cart
            this.$miniCartDropdown.addClass('active');
            
            // Hide mini cart after 5 seconds
            setTimeout(() => {
                this.$miniCartDropdown.removeClass('active');
            }, 5000);
        },

        /**
         * After remove from cart
         */
        afterRemoveFromCart: function() {
            // Update cart if on cart page
            if ($('.woocommerce-cart-form').length) {
                this.updateCart();
            }
        },

        /**
         * After fragments refreshed
         */
        afterFragmentsRefreshed: function() {
            // Recache DOM elements
            this.cacheDom();
        },

        /**
         * Update fragments
         * 
         * @param {Object} fragments The fragments
         */
        updateFragments: function(fragments) {
            // Update each fragment
            $.each(fragments, (key, value) => {
                $(key).replaceWith(value);
            });
            
            // Update cart hash
            if (typeof wc_cart_fragments_params !== 'undefined') {
                const cart_hash_key = wc_cart_fragments_params.cart_hash_key;
                const cart_hash = fragments['div.widget_shopping_cart_content'] ? fragments['div.widget_shopping_cart_content'].data('cart_hash') : '';
                
                // Set cart hash in session storage
                sessionStorage.setItem(cart_hash_key, cart_hash);
                localStorage.setItem(cart_hash_key, cart_hash);
            }
        },

        /**
         * Handle escape key
         * 
         * @param {Event} e The event object
         */
        handleEscapeKey: function(e) {
            if (e.key === 'Escape' && this.$miniCartDropdown.hasClass('active')) {
                this.$miniCartDropdown.removeClass('active');
                
                // Dispatch custom event
                this.dispatchEvent('miniCartClosed');
            }
        },

        /**
         * Show notification
         * 
         * @param {string} message The message
         * @param {string} type The type (success, error, warning, info)
         */
        showNotification: function(message, type = 'success') {
            // Check if notification function exists
            if (typeof AquaLuxe !== 'undefined' && typeof AquaLuxe.showNotification === 'function') {
                AquaLuxe.showNotification(message, type);
            } else {
                // Fallback to alert
                alert(message);
            }
        },

        /**
         * Dispatch custom event
         * 
         * @param {string} eventName The event name
         * @param {Object} detail The event detail
         */
        dispatchEvent: function(eventName, detail = {}) {
            const event = new CustomEvent(eventName, {
                detail: detail
            });
            
            document.dispatchEvent(event);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        // Only initialize if WooCommerce is active
        if (typeof aqualuxeData !== 'undefined' && aqualuxeData.isWooCommerceActive) {
            AjaxCart.init();
        }
    });

})(jQuery);