/**
 * AquaLuxe Mini Cart JavaScript
 * 
 * Handles AJAX functionality for the mini cart widget.
 */
(function($) {
    'use strict';

    // Mini Cart Object
    var AquaLuxeMiniCart = {
        /**
         * Initialize the mini cart functionality
         */
        init: function() {
            this.bindEvents();
            this.initDropdown();
            this.initSidebar();
            this.initPopup();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Remove item from cart
            $(document).on('click', '.aqualuxe-mini-cart-item-remove .remove-item', this.removeItem);
            
            // Update item quantity
            $(document).on('click', '.quantity-decrease, .quantity-increase', this.updateQuantity);
            
            // Close mini cart when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.aqualuxe-mini-cart').length) {
                    $('.aqualuxe-mini-cart').removeClass('open');
                }
            });
            
            // Update mini cart on AJAX add to cart event
            $(document.body).on('added_to_cart', this.updateMiniCart);
        },

        /**
         * Initialize dropdown functionality
         */
        initDropdown: function() {
            $(document).on('click', '.aqualuxe-mini-cart.cart-style-dropdown .aqualuxe-mini-cart-header', function(e) {
                e.preventDefault();
                $(this).closest('.aqualuxe-mini-cart').toggleClass('open');
            });
        },

        /**
         * Initialize sidebar functionality
         */
        initSidebar: function() {
            // Toggle sidebar
            $(document).on('click', '.aqualuxe-mini-cart.cart-style-sidebar .aqualuxe-mini-cart-header', function(e) {
                e.preventDefault();
                $(this).closest('.aqualuxe-mini-cart').addClass('open');
                $('body').addClass('mini-cart-sidebar-open');
            });
            
            // Close sidebar
            $(document).on('click', '.aqualuxe-mini-cart.cart-style-sidebar .aqualuxe-mini-cart-close', function(e) {
                e.preventDefault();
                $(this).closest('.aqualuxe-mini-cart').removeClass('open');
                $('body').removeClass('mini-cart-sidebar-open');
            });
            
            // Close sidebar when clicking on overlay
            $(document).on('click', '.aqualuxe-mini-cart-overlay', function(e) {
                e.preventDefault();
                $('.aqualuxe-mini-cart.cart-style-sidebar').removeClass('open');
                $('body').removeClass('mini-cart-sidebar-open');
            });
            
            // Add overlay if it doesn't exist
            if ($('.aqualuxe-mini-cart.cart-style-sidebar').length && !$('.aqualuxe-mini-cart-overlay').length) {
                $('body').append('<div class="aqualuxe-mini-cart-overlay"></div>');
            }
        },

        /**
         * Initialize popup functionality
         */
        initPopup: function() {
            // Toggle popup
            $(document).on('click', '.aqualuxe-mini-cart.cart-style-popup .aqualuxe-mini-cart-header', function(e) {
                e.preventDefault();
                $(this).closest('.aqualuxe-mini-cart').addClass('open');
                $('body').addClass('mini-cart-popup-open');
            });
            
            // Close popup
            $(document).on('click', '.aqualuxe-mini-cart.cart-style-popup .aqualuxe-mini-cart-close', function(e) {
                e.preventDefault();
                $(this).closest('.aqualuxe-mini-cart').removeClass('open');
                $('body').removeClass('mini-cart-popup-open');
            });
            
            // Close popup when clicking on overlay
            $(document).on('click', '.aqualuxe-mini-cart-overlay', function(e) {
                e.preventDefault();
                $('.aqualuxe-mini-cart.cart-style-popup').removeClass('open');
                $('body').removeClass('mini-cart-popup-open');
            });
            
            // Add overlay if it doesn't exist
            if ($('.aqualuxe-mini-cart.cart-style-popup').length && !$('.aqualuxe-mini-cart-overlay').length) {
                $('body').append('<div class="aqualuxe-mini-cart-overlay"></div>');
            }
            
            // Add close button if it doesn't exist
            $('.aqualuxe-mini-cart.cart-style-popup, .aqualuxe-mini-cart.cart-style-sidebar').each(function() {
                if (!$(this).find('.aqualuxe-mini-cart-close').length) {
                    $(this).find('.aqualuxe-mini-cart-header').append('<span class="aqualuxe-mini-cart-close">&times;</span>');
                }
            });
        },

        /**
         * Remove item from cart
         */
        removeItem: function(e) {
            e.preventDefault();
            
            var $this = $(this);
            var itemKey = $this.data('item-key');
            var $item = $this.closest('.aqualuxe-mini-cart-item');
            
            // Add loading state
            $item.addClass('removing');
            
            // Send AJAX request
            $.ajax({
                url: aqualuxeMiniCart.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_update_mini_cart',
                    nonce: aqualuxeMiniCart.nonce,
                    action_type: 'remove',
                    item_key: itemKey
                },
                success: function(response) {
                    if (response.success) {
                        // Remove item from DOM
                        $item.slideUp(300, function() {
                            $(this).remove();
                            
                            // Update cart count and total
                            AquaLuxeMiniCart.updateCartInfo(response.data);
                            
                            // Check if cart is empty
                            if (response.data.is_empty) {
                                AquaLuxeMiniCart.showEmptyCart();
                            }
                        });
                    } else {
                        // Show error message
                        alert(aqualuxeMiniCart.i18n.errorOccurred);
                        $item.removeClass('removing');
                    }
                },
                error: function() {
                    // Show error message
                    alert(aqualuxeMiniCart.i18n.errorOccurred);
                    $item.removeClass('removing');
                }
            });
        },

        /**
         * Update item quantity
         */
        updateQuantity: function(e) {
            e.preventDefault();
            
            var $this = $(this);
            var itemKey = $this.data('item-key');
            var quantity = $this.data('quantity');
            var $item = $this.closest('.aqualuxe-mini-cart-item');
            
            // Don't allow negative quantities
            if (quantity < 0) {
                quantity = 0;
            }
            
            // Add loading state
            $item.addClass('updating');
            
            // Send AJAX request
            $.ajax({
                url: aqualuxeMiniCart.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_update_mini_cart',
                    nonce: aqualuxeMiniCart.nonce,
                    action_type: 'update',
                    item_key: itemKey,
                    quantity: quantity
                },
                success: function(response) {
                    if (response.success) {
                        if (quantity === 0) {
                            // Remove item from DOM
                            $item.slideUp(300, function() {
                                $(this).remove();
                                
                                // Check if cart is empty
                                if (response.data.is_empty) {
                                    AquaLuxeMiniCart.showEmptyCart();
                                }
                            });
                        } else {
                            // Update quantity display
                            $item.find('.quantity').text(quantity);
                            
                            // Update decrease/increase buttons
                            $item.find('.quantity-decrease').data('quantity', quantity - 1);
                            $item.find('.quantity-increase').data('quantity', quantity + 1);
                            
                            $item.removeClass('updating');
                        }
                        
                        // Update cart count and total
                        AquaLuxeMiniCart.updateCartInfo(response.data);
                    } else {
                        // Show error message
                        alert(aqualuxeMiniCart.i18n.errorOccurred);
                        $item.removeClass('updating');
                    }
                },
                error: function() {
                    // Show error message
                    alert(aqualuxeMiniCart.i18n.errorOccurred);
                    $item.removeClass('updating');
                }
            });
        },

        /**
         * Update mini cart after adding item to cart
         */
        updateMiniCart: function(e, fragments, cart_hash, $button) {
            // Refresh the mini cart
            $.ajax({
                url: aqualuxeMiniCart.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_update_mini_cart',
                    nonce: aqualuxeMiniCart.nonce,
                    action_type: 'refresh'
                },
                success: function(response) {
                    if (response.success) {
                        // Update cart count and total
                        AquaLuxeMiniCart.updateCartInfo(response.data);
                        
                        // Open mini cart
                        $('.aqualuxe-mini-cart').addClass('open');
                        
                        // Add body class if sidebar or popup
                        if ($('.aqualuxe-mini-cart.cart-style-sidebar').hasClass('open')) {
                            $('body').addClass('mini-cart-sidebar-open');
                        } else if ($('.aqualuxe-mini-cart.cart-style-popup').hasClass('open')) {
                            $('body').addClass('mini-cart-popup-open');
                        }
                    }
                }
            });
        },

        /**
         * Update cart information (count and total)
         */
        updateCartInfo: function(data) {
            // Update count
            $('.aqualuxe-mini-cart-count .count').text(data.cart_count);
            
            // Update count text (item/items)
            var countText = data.cart_count === 1 ? 'item' : 'items';
            $('.aqualuxe-mini-cart-count .count-text').text(countText);
            
            // Update total
            $('.aqualuxe-mini-cart-total').html(data.cart_total);
            
            // Update subtotal
            $('.aqualuxe-mini-cart-subtotal .subtotal-amount').html(data.cart_total);
            
            // Update WooCommerce cart fragments if they exist
            if (typeof wc_cart_fragments_params !== 'undefined') {
                $(document.body).trigger('wc_fragment_refresh');
            }
        },

        /**
         * Show empty cart message
         */
        showEmptyCart: function() {
            var $miniCart = $('.aqualuxe-mini-cart');
            var $content = $miniCart.find('.aqualuxe-mini-cart-content');
            
            // Add empty class to mini cart
            $miniCart.addClass('cart-empty');
            
            // Create empty cart message
            var emptyMessage = '<div class="aqualuxe-mini-cart-empty">' +
                '<p>' + aqualuxeMiniCart.i18n.emptyCart + '</p>' +
                '<a href="' + aqualuxeMiniCart.shopUrl + '" class="button aqualuxe-mini-cart-shop-button">' + aqualuxeMiniCart.i18n.shopNow + '</a>' +
                '</div>';
            
            // Remove items and actions
            $content.find('.aqualuxe-mini-cart-items, .aqualuxe-mini-cart-subtotal, .aqualuxe-mini-cart-actions').remove();
            
            // Add empty message
            $content.append(emptyMessage);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeMiniCart.init();
    });

})(jQuery);