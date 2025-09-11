/**
 * AquaLuxe Wishlist Module
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Wishlist functionality
     */
    class AquaLuxeWishlist {
        constructor() {
            this.init();
        }

        /**
         * Initialize wishlist functionality
         */
        init() {
            this.bindEvents();
            this.updateWishlistCount();
        }

        /**
         * Bind events
         */
        bindEvents() {
            // Wishlist button clicks
            $(document).on('click', '.aqualuxe-wishlist-button', this.handleWishlistToggle.bind(this));
            $(document).on('click', '.remove-from-wishlist', this.handleWishlistRemove.bind(this));
            
            // Update button states on page load
            this.updateButtonStates();
        }

        /**
         * Handle wishlist toggle
         */
        handleWishlistToggle(e) {
            e.preventDefault();
            
            const $button = $(e.currentTarget);
            const productId = $button.data('product-id');
            const isInWishlist = $button.hasClass('in-wishlist');
            
            if (isInWishlist) {
                this.removeFromWishlist(productId, $button);
            } else {
                this.addToWishlist(productId, $button);
            }
        }

        /**
         * Handle wishlist remove
         */
        handleWishlistRemove(e) {
            e.preventDefault();
            
            const $button = $(e.currentTarget);
            const productId = $button.data('product-id');
            
            this.removeFromWishlist(productId, $button);
        }

        /**
         * Add product to wishlist
         */
        addToWishlist(productId, $button) {
            if (!productId) return;

            $button.addClass('loading').prop('disabled', true);

            $.ajax({
                url: aqualuxe_wishlist.ajax_url,
                type: 'POST',
                data: {
                    action: 'add_to_wishlist',
                    product_id: productId,
                    nonce: aqualuxe_wishlist.nonce
                },
                success: (response) => {
                    if (response.success) {
                        $button.addClass('in-wishlist');
                        $button.find('.wishlist-text').text(aqualuxe_wishlist.remove_text);
                        $button.attr('aria-label', aqualuxe_wishlist.remove_text);
                        
                        this.updateWishlistCount(response.data.count);
                        this.showNotification(response.data.message, 'success');
                        
                        // Update all buttons for this product
                        $(`.aqualuxe-wishlist-button[data-product-id="${productId}"]`).addClass('in-wishlist');
                    } else {
                        this.showNotification(response.data || 'Error adding to wishlist', 'error');
                    }
                },
                error: () => {
                    this.showNotification('Error adding to wishlist', 'error');
                },
                complete: () => {
                    $button.removeClass('loading').prop('disabled', false);
                }
            });
        }

        /**
         * Remove product from wishlist
         */
        removeFromWishlist(productId, $button) {
            if (!productId) return;

            $button.addClass('loading').prop('disabled', true);

            $.ajax({
                url: aqualuxe_wishlist.ajax_url,
                type: 'POST',
                data: {
                    action: 'remove_from_wishlist',
                    product_id: productId,
                    nonce: aqualuxe_wishlist.nonce
                },
                success: (response) => {
                    if (response.success) {
                        $button.removeClass('in-wishlist');
                        $button.find('.wishlist-text').text(aqualuxe_wishlist.add_text);
                        $button.attr('aria-label', aqualuxe_wishlist.add_text);
                        
                        this.updateWishlistCount(response.data.count);
                        this.showNotification(response.data.message, 'success');
                        
                        // Update all buttons for this product
                        $(`.aqualuxe-wishlist-button[data-product-id="${productId}"]`).removeClass('in-wishlist');
                        
                        // Remove item from wishlist page if present
                        $(`.wishlist-item[data-product-id="${productId}"]`).fadeOut(() => {
                            $(this).remove();
                            
                            // Check if wishlist is now empty
                            if ($('.wishlist-item').length === 0) {
                                location.reload();
                            }
                        });
                    } else {
                        this.showNotification(response.data || 'Error removing from wishlist', 'error');
                    }
                },
                error: () => {
                    this.showNotification('Error removing from wishlist', 'error');
                },
                complete: () => {
                    $button.removeClass('loading').prop('disabled', false);
                }
            });
        }

        /**
         * Update wishlist count in header/navigation
         */
        updateWishlistCount(count) {
            if (typeof count !== 'undefined') {
                $('.wishlist-count').text(count);
                
                if (count > 0) {
                    $('.wishlist-count').addClass('has-items');
                } else {
                    $('.wishlist-count').removeClass('has-items');
                }
            }
        }

        /**
         * Update button states based on current wishlist
         */
        updateButtonStates() {
            // This would typically be populated from server-side data
            // For now, we'll rely on the server-rendered states
        }

        /**
         * Show notification
         */
        showNotification(message, type = 'info') {
            // Remove existing notifications
            $('.aqualuxe-notification').remove();
            
            const $notification = $(`
                <div class="aqualuxe-notification aqualuxe-notification--${type}">
                    <div class="aqualuxe-notification__content">
                        <span class="aqualuxe-notification__message">${message}</span>
                        <button class="aqualuxe-notification__close" aria-label="Close">×</button>
                    </div>
                </div>
            `);
            
            $('body').append($notification);
            
            // Auto-hide after 3 seconds
            setTimeout(() => {
                $notification.fadeOut(() => {
                    $notification.remove();
                });
            }, 3000);
            
            // Manual close
            $notification.find('.aqualuxe-notification__close').on('click', () => {
                $notification.fadeOut(() => {
                    $notification.remove();
                });
            });
        }

        /**
         * Get wishlist count from server
         */
        getWishlistCount() {
            $.ajax({
                url: aqualuxe_wishlist.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_wishlist_count',
                    nonce: aqualuxe_wishlist.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.updateWishlistCount(response.data.count);
                    }
                }
            });
        }
    }

    // Initialize when document is ready
    $(document).ready(() => {
        if (typeof aqualuxe_wishlist !== 'undefined') {
            window.AquaLuxeWishlist = new AquaLuxeWishlist();
        }
    });

})(jQuery);