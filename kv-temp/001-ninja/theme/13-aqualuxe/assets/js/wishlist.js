/**
 * AquaLuxe Theme Wishlist JavaScript
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * AquaLuxe Wishlist Object
     */
    var AquaLuxeWishlist = {
        /**
         * Initialize
         */
        init: function() {
            this.setupEventListeners();
            this.updateWishlistIcons();
        },

        /**
         * Setup Event Listeners
         */
        setupEventListeners: function() {
            var self = this;

            // Wishlist toggle button click
            $(document).on('click', '.wishlist-toggle', function(e) {
                e.preventDefault();
                var $button = $(this);
                var productId = $button.data('product-id');
                
                // Check if user is logged in
                if (!aqualuxeWishlist.isLoggedIn && aqualuxeWishlist.requireLogin) {
                    if (confirm(aqualuxeWishlist.i18n.loginRequired)) {
                        window.location.href = aqualuxeWishlist.loginUrl;
                    }
                    return;
                }
                
                self.toggleWishlist($button, productId);
            });

            // Wishlist remove button click
            $(document).on('click', '.wishlist-remove', function(e) {
                e.preventDefault();
                var $button = $(this);
                var productId = $button.data('product-id');
                var $product = $button.closest('.wishlist-product');
                
                self.removeFromWishlist($button, productId, $product);
            });
        },

        /**
         * Toggle Wishlist
         *
         * @param {jQuery} $button Button element
         * @param {number} productId Product ID
         */
        toggleWishlist: function($button, productId) {
            var self = this;
            var $icon = $button.find('.wishlist-icon');
            var isInWishlist = $icon.hasClass('text-red-500');
            
            // Add loading state
            $button.addClass('loading');
            
            // Toggle wishlist via AJAX
            $.ajax({
                url: aqualuxeWishlist.ajaxUrl,
                type: 'POST',
                data: {
                    action: aqualuxeWishlist.isLoggedIn ? 'aqualuxe_toggle_wishlist' : 'aqualuxe_toggle_wishlist_guest',
                    product_id: productId,
                    nonce: aqualuxeWishlist.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Update wishlist icon
                        if (response.data.status === 'added') {
                            $icon.addClass('text-red-500 fill-current');
                            self.showNotification(aqualuxeWishlist.i18n.added);
                        } else {
                            $icon.removeClass('text-red-500 fill-current');
                            self.showNotification(aqualuxeWishlist.i18n.removed);
                        }
                        
                        // Update all instances of the same product
                        $('.wishlist-toggle[data-product-id="' + productId + '"]').find('.wishlist-icon').toggleClass('text-red-500 fill-current', response.data.status === 'added');
                        
                        // Update wishlist count
                        $('.wishlist-count').text(response.data.count);
                    } else {
                        self.showNotification(aqualuxeWishlist.i18n.error);
                    }
                    
                    // Remove loading state
                    $button.removeClass('loading');
                },
                error: function() {
                    self.showNotification(aqualuxeWishlist.i18n.error);
                    $button.removeClass('loading');
                }
            });
        },

        /**
         * Remove from Wishlist
         *
         * @param {jQuery} $button Button element
         * @param {number} productId Product ID
         * @param {jQuery} $product Product element
         */
        removeFromWishlist: function($button, productId, $product) {
            var self = this;
            
            // Add loading state
            $button.addClass('loading');
            
            // Remove from wishlist via AJAX
            $.ajax({
                url: aqualuxeWishlist.ajaxUrl,
                type: 'POST',
                data: {
                    action: aqualuxeWishlist.isLoggedIn ? 'aqualuxe_toggle_wishlist' : 'aqualuxe_toggle_wishlist_guest',
                    product_id: productId,
                    nonce: aqualuxeWishlist.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Remove product from wishlist page
                        $product.fadeOut(300, function() {
                            $(this).remove();
                            
                            // Show empty message if no products left
                            if ($('.wishlist-product').length === 0) {
                                $('.wishlist-products').replaceWith('<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md transition-colors duration-300">' + aqualuxeWishlist.i18n.emptyWishlist + ' <a class="button inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-300 ml-4" href="' + aqualuxeWishlist.shopUrl + '">' + aqualuxeWishlist.i18n.browseProducts + '</a></div>');
                            }
                        });
                        
                        // Update all instances of the same product
                        $('.wishlist-toggle[data-product-id="' + productId + '"]').find('.wishlist-icon').removeClass('text-red-500 fill-current');
                        
                        // Update wishlist count
                        $('.wishlist-count').text(response.data.count);
                        
                        // Show notification
                        self.showNotification(aqualuxeWishlist.i18n.removed);
                    } else {
                        self.showNotification(aqualuxeWishlist.i18n.error);
                        $button.removeClass('loading');
                    }
                },
                error: function() {
                    self.showNotification(aqualuxeWishlist.i18n.error);
                    $button.removeClass('loading');
                }
            });
        },

        /**
         * Update Wishlist Icons
         */
        updateWishlistIcons: function() {
            // Get wishlist items
            $.ajax({
                url: aqualuxeWishlist.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_wishlist',
                    nonce: aqualuxeWishlist.nonce
                },
                success: function(response) {
                    if (response.success && response.data.items) {
                        // Update wishlist icons
                        $.each(response.data.items, function(index, productId) {
                            $('.wishlist-toggle[data-product-id="' + productId + '"]').find('.wishlist-icon').addClass('text-red-500 fill-current');
                        });
                        
                        // Update wishlist count
                        $('.wishlist-count').text(response.data.count);
                    }
                }
            });
        },

        /**
         * Show Notification
         *
         * @param {string} message Notification message
         */
        showNotification: function(message) {
            // Check if notification container exists
            var $container = $('#wishlist-notification');
            
            if (!$container.length) {
                // Create notification container
                $container = $('<div id="wishlist-notification" class="fixed bottom-4 right-4 bg-gray-800 text-white px-4 py-2 rounded-md shadow-lg z-50 transform translate-y-full opacity-0 transition-all duration-300"></div>');
                $('body').append($container);
            }
            
            // Set message
            $container.text(message);
            
            // Show notification
            setTimeout(function() {
                $container.removeClass('translate-y-full opacity-0');
            }, 10);
            
            // Hide notification after 3 seconds
            setTimeout(function() {
                $container.addClass('translate-y-full opacity-0');
            }, 3000);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeWishlist.init();
    });

})(jQuery);