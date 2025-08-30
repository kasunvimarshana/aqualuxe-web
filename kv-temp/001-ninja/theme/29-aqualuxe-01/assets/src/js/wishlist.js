/**
 * AquaLuxe Wishlist Functionality
 * 
 * Handles wishlist operations for the AquaLuxe theme
 */
(function($) {
    'use strict';

    // Main wishlist object
    const AquaLuxeWishlist = {
        // Initialize the wishlist functionality
        init: function() {
            this.wishlistButton = $('.wishlist-button');
            this.wishlistCount = $('.wishlist-count');
            this.wishlistItems = [];
            
            // Load wishlist from cookie
            this.loadWishlist();
            
            // Bind events
            this.bindEvents();
            
            // Update wishlist count
            this.updateWishlistCount();
        },

        // Bind all events
        bindEvents: function() {
            const self = this;
            
            // Toggle wishlist
            $(document).on('click', '.wishlist-button', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const productId = $button.data('product-id');
                
                self.toggleWishlistItem(productId, $button);
            });
            
            // Remove from wishlist
            $(document).on('click', '.remove-from-wishlist', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const productId = $button.data('product-id');
                
                self.removeFromWishlist(productId);
            });
        },

        // Load wishlist from cookie
        loadWishlist: function() {
            const wishlistCookie = this.getCookie('aqualuxe_wishlist');
            
            if (wishlistCookie) {
                try {
                    this.wishlistItems = JSON.parse(wishlistCookie);
                } catch (e) {
                    this.wishlistItems = [];
                    console.error('Error parsing wishlist cookie:', e);
                }
            }
        },

        // Save wishlist to cookie
        saveWishlist: function() {
            this.setCookie('aqualuxe_wishlist', JSON.stringify(this.wishlistItems), 30);
        },

        // Toggle wishlist item
        toggleWishlistItem: function(productId, $button) {
            const index = this.wishlistItems.indexOf(parseInt(productId));
            
            if (index === -1) {
                // Add to wishlist
                this.wishlistItems.push(parseInt(productId));
                this.updateButtonState($button, true);
                this.showNotification(aqualuxeWooCommerce.i18n.addedToWishlist);
            } else {
                // Remove from wishlist
                this.wishlistItems.splice(index, 1);
                this.updateButtonState($button, false);
                this.showNotification(aqualuxeWooCommerce.i18n.removedFromWishlist);
            }
            
            // Save wishlist
            this.saveWishlist();
            
            // Update wishlist count
            this.updateWishlistCount();
            
            // Update all buttons for this product
            this.updateAllButtons(productId);
            
            // Send AJAX request
            this.sendWishlistRequest(productId);
        },

        // Remove from wishlist
        removeFromWishlist: function(productId) {
            const index = this.wishlistItems.indexOf(parseInt(productId));
            
            if (index !== -1) {
                // Remove from wishlist
                this.wishlistItems.splice(index, 1);
                
                // Save wishlist
                this.saveWishlist();
                
                // Update wishlist count
                this.updateWishlistCount();
                
                // Update all buttons for this product
                this.updateAllButtons(productId);
                
                // Send AJAX request
                this.sendWishlistRequest(productId);
                
                // Show notification
                this.showNotification(aqualuxeWooCommerce.i18n.removedFromWishlist);
                
                // If on wishlist page, remove the row
                if ($('.aqualuxe-wishlist-item[data-product-id="' + productId + '"]').length) {
                    $('.aqualuxe-wishlist-item[data-product-id="' + productId + '"]').fadeOut(300, function() {
                        $(this).remove();
                        
                        // Check if wishlist is empty
                        if ($('.aqualuxe-wishlist-item').length === 0) {
                            $('.aqualuxe-wishlist-products').replaceWith(
                                '<div class="aqualuxe-wishlist-empty">' +
                                '<p>' + aqualuxeWooCommerce.i18n.wishlistEmpty + '</p>' +
                                '<a href="' + aqualuxeWooCommerce.shopUrl + '" class="button">' + aqualuxeWooCommerce.i18n.browseProducts + '</a>' +
                                '</div>'
                            );
                        }
                    });
                }
            }
        },

        // Update button state
        updateButtonState: function($button, inWishlist) {
            if (inWishlist) {
                $button.addClass('in-wishlist');
                $button.find('.wishlist-icon svg').attr('fill', 'currentColor');
                $button.find('.wishlist-text').text(aqualuxeWooCommerce.i18n.removeFromWishlist);
            } else {
                $button.removeClass('in-wishlist');
                $button.find('.wishlist-icon svg').attr('fill', 'none');
                $button.find('.wishlist-text').text(aqualuxeWooCommerce.i18n.addToWishlist);
            }
        },

        // Update all buttons for a product
        updateAllButtons: function(productId) {
            const self = this;
            const inWishlist = this.wishlistItems.indexOf(parseInt(productId)) !== -1;
            
            $('.wishlist-button[data-product-id="' + productId + '"]').each(function() {
                self.updateButtonState($(this), inWishlist);
            });
        },

        // Update wishlist count
        updateWishlistCount: function() {
            const count = this.wishlistItems.length;
            
            this.wishlistCount.text(count);
            
            if (count > 0) {
                this.wishlistCount.removeClass('hidden');
            } else {
                this.wishlistCount.addClass('hidden');
            }
        },

        // Send wishlist AJAX request
        sendWishlistRequest: function(productId) {
            $.ajax({
                url: aqualuxeWooCommerce.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_add_to_wishlist',
                    nonce: aqualuxeWooCommerce.nonce,
                    product_id: productId
                },
                success: function(response) {
                    // Success
                }
            });
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
        },

        // Get cookie
        getCookie: function(name) {
            const value = '; ' + document.cookie;
            const parts = value.split('; ' + name + '=');
            
            if (parts.length === 2) {
                return parts.pop().split(';').shift();
            }
            
            return '';
        },

        // Set cookie
        setCookie: function(name, value, days) {
            let expires = '';
            
            if (days) {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = '; expires=' + date.toUTCString();
            }
            
            document.cookie = name + '=' + value + expires + '; path=/';
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeWishlist.init();
    });

})(jQuery);