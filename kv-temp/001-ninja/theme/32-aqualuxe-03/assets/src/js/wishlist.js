/**
 * AquaLuxe Theme - Wishlist
 *
 * Handles the wishlist functionality.
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Add to wishlist
        $(document).on('click', '.add-to-wishlist', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const productId = $button.data('product-id');
            
            // Prevent multiple clicks
            if ($button.hasClass('loading')) {
                return;
            }
            
            // Add loading state
            $button.addClass('loading');
            
            // AJAX call to add product to wishlist
            $.ajax({
                url: aqualuxeSettings.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_add_to_wishlist',
                    product_id: productId,
                    nonce: aqualuxeSettings.nonce
                },
                success: function(response) {
                    // Remove loading state
                    $button.removeClass('loading');
                    
                    if (response.success) {
                        // Update button state
                        $button.addClass('in-wishlist');
                        $button.attr('title', response.data.remove_title);
                        $button.attr('aria-label', response.data.remove_title);
                        
                        // Update button text if it exists
                        if ($button.find('.wishlist-text').length) {
                            $button.find('.wishlist-text').text(response.data.remove_text);
                        }
                        
                        // Update wishlist count
                        updateWishlistCount(response.data.count);
                        
                        // Show success message
                        showNotification(response.data.message, 'success');
                    } else {
                        // Show error message
                        showNotification(response.data.message, 'error');
                    }
                },
                error: function() {
                    // Remove loading state
                    $button.removeClass('loading');
                    
                    // Show error message
                    showNotification('Failed to add product to wishlist. Please try again.', 'error');
                }
            });
        });

        // Remove from wishlist
        $(document).on('click', '.remove-from-wishlist, .in-wishlist', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const productId = $button.data('product-id');
            const isWishlistPage = $('body').hasClass('wishlist-page');
            
            // Prevent multiple clicks
            if ($button.hasClass('loading')) {
                return;
            }
            
            // Add loading state
            $button.addClass('loading');
            
            // AJAX call to remove product from wishlist
            $.ajax({
                url: aqualuxeSettings.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_remove_from_wishlist',
                    product_id: productId,
                    nonce: aqualuxeSettings.nonce
                },
                success: function(response) {
                    // Remove loading state
                    $button.removeClass('loading');
                    
                    if (response.success) {
                        if (isWishlistPage) {
                            // Remove product from wishlist page
                            $button.closest('.wishlist-item').fadeOut(300, function() {
                                $(this).remove();
                                
                                // Show empty message if no items left
                                if ($('.wishlist-item').length === 0) {
                                    $('.wishlist-items').html('<div class="wishlist-empty">' + response.data.empty_text + '</div>');
                                }
                            });
                        } else {
                            // Update button state
                            $button.removeClass('in-wishlist');
                            $button.attr('title', response.data.add_title);
                            $button.attr('aria-label', response.data.add_title);
                            
                            // Update button text if it exists
                            if ($button.find('.wishlist-text').length) {
                                $button.find('.wishlist-text').text(response.data.add_text);
                            }
                        }
                        
                        // Update wishlist count
                        updateWishlistCount(response.data.count);
                        
                        // Show success message
                        showNotification(response.data.message, 'success');
                    } else {
                        // Show error message
                        showNotification(response.data.message, 'error');
                    }
                },
                error: function() {
                    // Remove loading state
                    $button.removeClass('loading');
                    
                    // Show error message
                    showNotification('Failed to remove product from wishlist. Please try again.', 'error');
                }
            });
        });

        // Move to cart
        $(document).on('click', '.move-to-cart', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const productId = $button.data('product-id');
            
            // Prevent multiple clicks
            if ($button.hasClass('loading')) {
                return;
            }
            
            // Add loading state
            $button.addClass('loading');
            
            // AJAX call to move product to cart
            $.ajax({
                url: aqualuxeSettings.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_move_to_cart',
                    product_id: productId,
                    nonce: aqualuxeSettings.nonce
                },
                success: function(response) {
                    // Remove loading state
                    $button.removeClass('loading');
                    
                    if (response.success) {
                        // Remove product from wishlist
                        $button.closest('.wishlist-item').fadeOut(300, function() {
                            $(this).remove();
                            
                            // Show empty message if no items left
                            if ($('.wishlist-item').length === 0) {
                                $('.wishlist-items').html('<div class="wishlist-empty">' + response.data.empty_text + '</div>');
                            }
                        });
                        
                        // Update wishlist count
                        updateWishlistCount(response.data.wishlist_count);
                        
                        // Update cart count
                        updateCartCount(response.data.cart_count);
                        
                        // Show success message
                        showNotification(response.data.message, 'success');
                    } else {
                        // Show error message
                        showNotification(response.data.message, 'error');
                    }
                },
                error: function() {
                    // Remove loading state
                    $button.removeClass('loading');
                    
                    // Show error message
                    showNotification('Failed to move product to cart. Please try again.', 'error');
                }
            });
        });

        // Share wishlist
        $(document).on('click', '.share-wishlist', function(e) {
            e.preventDefault();
            
            // Show share modal
            $('.wishlist-share-modal').addClass('active');
            $('body').addClass('modal-open');
        });

        // Close share modal
        $(document).on('click', '.wishlist-share-modal .modal-close, .wishlist-share-modal .modal-overlay', function() {
            $('.wishlist-share-modal').removeClass('active');
            $('body').removeClass('modal-open');
        });

        // Copy wishlist link
        $(document).on('click', '.copy-wishlist-link', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const wishlistUrl = $button.data('url');
            
            // Create temporary input
            const $temp = $('<input>');
            $('body').append($temp);
            $temp.val(wishlistUrl).select();
            
            // Copy to clipboard
            document.execCommand('copy');
            $temp.remove();
            
            // Show success message
            $button.text('Copied!');
            
            // Reset button text after 2 seconds
            setTimeout(function() {
                $button.text('Copy Link');
            }, 2000);
        });

        // Update wishlist count
        function updateWishlistCount(count) {
            const $wishlistCount = $('.wishlist-count');
            
            if ($wishlistCount.length) {
                $wishlistCount.text(count);
                
                if (count > 0) {
                    $wishlistCount.removeClass('hidden');
                } else {
                    $wishlistCount.addClass('hidden');
                }
            }
        }

        // Update cart count
        function updateCartCount(count) {
            const $cartCount = $('.cart-count');
            
            if ($cartCount.length) {
                $cartCount.text(count);
                
                if (count > 0) {
                    $cartCount.removeClass('hidden');
                } else {
                    $cartCount.addClass('hidden');
                }
            }
        }

        // Show notification
        function showNotification(message, type) {
            // Remove existing notifications
            $('.aqualuxe-notification').remove();
            
            // Create notification
            const $notification = $(`
                <div class="aqualuxe-notification ${type}">
                    <div class="notification-content">
                        <span class="notification-message">${message}</span>
                        <button class="notification-close" aria-label="Close notification">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            `);
            
            // Append to body
            $('body').append($notification);
            
            // Show notification
            setTimeout(function() {
                $notification.addClass('show');
            }, 10);
            
            // Hide notification after 3 seconds
            setTimeout(function() {
                $notification.removeClass('show');
                
                // Remove notification after animation
                setTimeout(function() {
                    $notification.remove();
                }, 300);
            }, 3000);
            
            // Close notification on click
            $notification.on('click', '.notification-close', function() {
                $notification.removeClass('show');
                
                // Remove notification after animation
                setTimeout(function() {
                    $notification.remove();
                }, 300);
            });
        }
    });

})(jQuery);