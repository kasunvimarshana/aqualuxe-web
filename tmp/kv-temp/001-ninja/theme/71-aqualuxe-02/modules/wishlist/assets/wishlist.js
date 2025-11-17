/**
 * Wishlist JavaScript Module
 * 
 * Handles wishlist functionality including:
 * - Adding/removing items
 * - Local storage sync
 * - AJAX operations
 * - UI updates
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Wishlist Controller
     */
    class WishlistController {
        constructor() {
            this.settings = window.aqualuxeWishlist || {};
            this.storageKey = 'aqualuxe-wishlist';
            this.localWishlist = this.getLocalWishlist();
            this.isProcessing = false;
            
            this.init();
        }

        /**
         * Initialize the wishlist controller
         */
        init() {
            if (!this.settings.enabled) {
                return;
            }

            this.bindEvents();
            this.updateButtonStates();
            this.updateCounters();
            this.syncWithServer();
            
            console.log('AquaLuxe Wishlist initialized');
        }

        /**
         * Bind event listeners
         */
        bindEvents() {
            // Wishlist button clicks
            $(document).on('click', '.aqualuxe-wishlist-button', this.handleWishlistToggle.bind(this));
            
            // Bulk actions
            $(document).on('click', '[data-action="clear-wishlist"]', this.handleClearWishlist.bind(this));
            $(document).on('click', '[data-action="sync-wishlist"]', this.syncWithServer.bind(this));
            
            // Custom events
            $(document).on('aqualuxe:addToWishlist', this.handleAddToWishlist.bind(this));
            $(document).on('aqualuxe:removeFromWishlist', this.handleRemoveFromWishlist.bind(this));
            $(document).on('aqualuxe:clearWishlist', this.handleClearWishlist.bind(this));
            
            // Page visibility for sync
            document.addEventListener('visibilitychange', this.handleVisibilityChange.bind(this));
            
            // Storage events for cross-tab sync
            window.addEventListener('storage', this.handleStorageChange.bind(this));
            
            // Form submissions (for adding to cart from wishlist)
            $(document).on('submit', '.wishlist-add-to-cart-form', this.handleAddToCart.bind(this));
        }

        /**
         * Handle wishlist toggle button click
         */
        handleWishlistToggle(event) {
            event.preventDefault();
            
            if (this.isProcessing) {
                return;
            }

            const $button = $(event.currentTarget);
            const postId = parseInt($button.data('post-id'));
            const action = $button.data('action');

            if (!postId) {
                return;
            }

            if (action === 'add') {
                this.addToWishlist(postId, $button);
            } else if (action === 'remove') {
                this.removeFromWishlist(postId, $button);
            }
        }

        /**
         * Handle add to wishlist
         */
        handleAddToWishlist(event, postId) {
            if (postId) {
                this.addToWishlist(postId);
            }
        }

        /**
         * Handle remove from wishlist
         */
        handleRemoveFromWishlist(event, postId) {
            if (postId) {
                this.removeFromWishlist(postId);
            }
        }

        /**
         * Handle clear wishlist
         */
        handleClearWishlist(event) {
            event.preventDefault();
            
            if (this.isProcessing) {
                return;
            }

            if (confirm(this.settings.strings.confirmClear || 'Are you sure you want to clear your wishlist?')) {
                this.clearWishlist();
            }
        }

        /**
         * Handle add to cart from wishlist
         */
        handleAddToCart(event) {
            event.preventDefault();
            
            const $form = $(event.currentTarget);
            const postId = parseInt($form.find('[name="add-to-cart"]').val());
            
            if (!postId) {
                return;
            }

            this.addToCart(postId, $form);
        }

        /**
         * Handle page visibility change
         */
        handleVisibilityChange() {
            if (!document.hidden) {
                // Page became visible, sync with server
                this.syncWithServer();
            }
        }

        /**
         * Handle storage change (cross-tab sync)
         */
        handleStorageChange(event) {
            if (event.key === this.storageKey && event.newValue !== event.oldValue) {
                this.localWishlist = this.getLocalWishlist();
                this.updateButtonStates();
                this.updateCounters();
            }
        }

        /**
         * Add item to wishlist
         */
        addToWishlist(postId, $button = null) {
            if (this.isProcessing) {
                return;
            }

            this.isProcessing = true;

            // Update UI immediately for better UX
            this.addToLocalWishlist(postId);
            this.updateButtonState(postId, true);
            this.updateCounters();

            if ($button) {
                $button.addClass('loading');
            }

            // Send to server
            $.ajax({
                url: this.settings.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_add_to_wishlist',
                    nonce: this.settings.nonce,
                    post_id: postId
                },
                success: (response) => {
                    if (response.success) {
                        this.showNotification(response.data.message || this.settings.strings.addedToWishlist, 'success');
                        this.updateButtonHTML(postId, response.data.button_html);
                        this.updateCounters(response.data.count);
                    } else {
                        // Revert on failure
                        this.removeFromLocalWishlist(postId);
                        this.updateButtonState(postId, false);
                        this.updateCounters();
                        this.showNotification(response.data || this.settings.strings.error, 'error');
                    }
                },
                error: (xhr, status, error) => {
                    // Revert on error
                    this.removeFromLocalWishlist(postId);
                    this.updateButtonState(postId, false);
                    this.updateCounters();
                    this.showNotification(this.settings.strings.error, 'error');
                    console.error('Wishlist error:', error);
                },
                complete: () => {
                    this.isProcessing = false;
                    if ($button) {
                        $button.removeClass('loading');
                    }
                }
            });

            // Trigger custom event
            $(document).trigger('aqualuxe:wishlistItemAdded', [postId]);
        }

        /**
         * Remove item from wishlist
         */
        removeFromWishlist(postId, $button = null) {
            if (this.isProcessing) {
                return;
            }

            this.isProcessing = true;

            // Update UI immediately for better UX
            this.removeFromLocalWishlist(postId);
            this.updateButtonState(postId, false);
            this.updateCounters();

            if ($button) {
                $button.addClass('loading');
            }

            // Send to server
            $.ajax({
                url: this.settings.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_remove_from_wishlist',
                    nonce: this.settings.nonce,
                    post_id: postId
                },
                success: (response) => {
                    if (response.success) {
                        this.showNotification(response.data.message || this.settings.strings.removedFromWishlist, 'success');
                        this.updateButtonHTML(postId, response.data.button_html);
                        this.updateCounters(response.data.count);
                        
                        // Remove from wishlist page if present
                        $(`.aqualuxe-wishlist-item[data-post-id="${postId}"]`).fadeOut();
                    } else {
                        // Revert on failure
                        this.addToLocalWishlist(postId);
                        this.updateButtonState(postId, true);
                        this.updateCounters();
                        this.showNotification(response.data || this.settings.strings.error, 'error');
                    }
                },
                error: (xhr, status, error) => {
                    // Revert on error
                    this.addToLocalWishlist(postId);
                    this.updateButtonState(postId, true);
                    this.updateCounters();
                    this.showNotification(this.settings.strings.error, 'error');
                    console.error('Wishlist error:', error);
                },
                complete: () => {
                    this.isProcessing = false;
                    if ($button) {
                        $button.removeClass('loading');
                    }
                }
            });

            // Trigger custom event
            $(document).trigger('aqualuxe:wishlistItemRemoved', [postId]);
        }

        /**
         * Clear entire wishlist
         */
        clearWishlist() {
            if (this.isProcessing) {
                return;
            }

            this.isProcessing = true;

            // Update UI immediately
            const previousWishlist = [...this.localWishlist];
            this.localWishlist = [];
            this.saveLocalWishlist();
            this.updateButtonStates();
            this.updateCounters();

            // Send to server
            $.ajax({
                url: this.settings.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_clear_wishlist',
                    nonce: this.settings.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.showNotification(response.data.message || this.settings.strings.clearedWishlist, 'success');
                        $('.aqualuxe-wishlist-item').fadeOut();
                        this.updateCounters(0);
                    } else {
                        // Revert on failure
                        this.localWishlist = previousWishlist;
                        this.saveLocalWishlist();
                        this.updateButtonStates();
                        this.updateCounters();
                        this.showNotification(response.data || this.settings.strings.error, 'error');
                    }
                },
                error: (xhr, status, error) => {
                    // Revert on error
                    this.localWishlist = previousWishlist;
                    this.saveLocalWishlist();
                    this.updateButtonStates();
                    this.updateCounters();
                    this.showNotification(this.settings.strings.error, 'error');
                    console.error('Wishlist error:', error);
                },
                complete: () => {
                    this.isProcessing = false;
                }
            });

            // Trigger custom event
            $(document).trigger('aqualuxe:wishlistCleared');
        }

        /**
         * Add item to cart (WooCommerce integration)
         */
        addToCart(postId, $form) {
            const formData = $form.serialize();
            
            $form.addClass('loading');

            $.ajax({
                url: this.settings.wc ? this.settings.wc.ajax_url : this.settings.ajaxUrl,
                type: 'POST',
                data: formData + '&action=woocommerce_add_to_cart',
                success: (response) => {
                    if (response.error && response.product_url) {
                        window.location = response.product_url;
                    } else {
                        // Trigger WooCommerce events
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $form]);
                        this.showNotification(this.settings.strings.addedToCart || 'Added to cart!', 'success');
                    }
                },
                error: (xhr, status, error) => {
                    this.showNotification(this.settings.strings.error, 'error');
                    console.error('Add to cart error:', error);
                },
                complete: () => {
                    $form.removeClass('loading');
                }
            });
        }

        /**
         * Sync with server
         */
        syncWithServer() {
            if (!this.settings.ajaxUrl || this.isProcessing) {
                return;
            }

            $.ajax({
                url: this.settings.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_wishlist',
                    nonce: this.settings.nonce
                },
                success: (response) => {
                    if (response.success && response.data.items) {
                        const serverItems = response.data.items.map(item => item.id);
                        this.syncLocalWithServer(serverItems);
                        this.updateButtonStates();
                        this.updateCounters(response.data.total);
                    }
                },
                error: (xhr, status, error) => {
                    console.warn('Wishlist sync failed:', error);
                }
            });
        }

        /**
         * Sync local wishlist with server data
         */
        syncLocalWithServer(serverItems) {
            const currentLocal = new Set(this.localWishlist);
            const serverSet = new Set(serverItems);
            
            // Items in server but not in local
            const toAddLocally = [...serverSet].filter(id => !currentLocal.has(id));
            
            // Items in local but not in server
            const toRemoveLocally = [...currentLocal].filter(id => !serverSet.has(id));
            
            // Update local wishlist
            toAddLocally.forEach(id => this.addToLocalWishlist(id));
            toRemoveLocally.forEach(id => this.removeFromLocalWishlist(id));
        }

        /**
         * Local wishlist management
         */
        getLocalWishlist() {
            try {
                const stored = localStorage.getItem(this.storageKey);
                return stored ? JSON.parse(stored) : [];
            } catch (e) {
                console.warn('Failed to read wishlist from localStorage:', e);
                return [];
            }
        }

        saveLocalWishlist() {
            try {
                localStorage.setItem(this.storageKey, JSON.stringify(this.localWishlist));
            } catch (e) {
                console.warn('Failed to save wishlist to localStorage:', e);
            }
        }

        addToLocalWishlist(postId) {
            postId = parseInt(postId);
            if (!this.localWishlist.includes(postId)) {
                this.localWishlist.push(postId);
                this.saveLocalWishlist();
            }
        }

        removeFromLocalWishlist(postId) {
            postId = parseInt(postId);
            const index = this.localWishlist.indexOf(postId);
            if (index > -1) {
                this.localWishlist.splice(index, 1);
                this.saveLocalWishlist();
            }
        }

        isInWishlist(postId) {
            return this.localWishlist.includes(parseInt(postId));
        }

        /**
         * UI update methods
         */
        updateButtonStates() {
            $('.aqualuxe-wishlist-button').each((index, element) => {
                const $button = $(element);
                const postId = parseInt($button.data('post-id'));
                
                if (postId) {
                    this.updateButtonState(postId, this.isInWishlist(postId), $button);
                }
            });
        }

        updateButtonState(postId, inWishlist, $button = null) {
            if (!$button) {
                $button = $(`.aqualuxe-wishlist-button[data-post-id="${postId}"]`);
            }

            const action = inWishlist ? 'remove' : 'add';
            const text = inWishlist ? 
                this.settings.strings.removeFromWishlist : 
                this.settings.strings.addToWishlist;

            $button
                .toggleClass('in-wishlist', inWishlist)
                .attr('data-action', action)
                .attr('title', text);

            const $text = $button.find('.text');
            if ($text.length) {
                $text.text(text);
            }

            // Update icon if needed
            const $icon = $button.find('.icon');
            if ($icon.length) {
                if (inWishlist) {
                    $icon.html('<path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>');
                } else {
                    $icon.html('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>');
                }
            }
        }

        updateButtonHTML(postId, html) {
            if (html) {
                $(`.aqualuxe-wishlist-button[data-post-id="${postId}"]`).replaceWith(html);
            }
        }

        updateCounters(count = null) {
            if (count === null) {
                count = this.localWishlist.length;
            }

            // Update counter elements
            $('.aqualuxe-wishlist-count').each((index, element) => {
                const $counter = $(element);
                const showText = !$counter.hasClass('count-only');
                
                if (showText) {
                    const text = count === 1 ? 
                        this.settings.strings.oneItem || '1 item' :
                        (this.settings.strings.multipleItems || '%d items').replace('%d', count);
                    $counter.find('.count-text').text(text);
                } else {
                    $counter.text(count);
                }
            });

            // Update floating button
            const $floating = $('.aqualuxe-wishlist-floating');
            if ($floating.length) {
                const $countBadge = $floating.find('.count');
                if (count > 0) {
                    $countBadge.text(count).show();
                    $floating.show();
                } else {
                    $countBadge.hide();
                    if ($floating.hasClass('hide-when-empty')) {
                        $floating.hide();
                    }
                }
            }

            // Update wishlist page empty state
            if ($('.aqualuxe-wishlist-container').length) {
                const $emptyState = $('.aqualuxe-wishlist-empty');
                const $grid = $('.aqualuxe-wishlist-grid');
                
                if (count === 0) {
                    $grid.hide();
                    $emptyState.show();
                } else {
                    $emptyState.hide();
                    $grid.show();
                }
            }
        }

        /**
         * Show notification
         */
        showNotification(message, type = 'info') {
            // Check for existing notification systems
            if (typeof window.AquaLuxe !== 'undefined' && window.AquaLuxe.notifications) {
                window.AquaLuxe.notifications.show(message, type);
                return;
            }

            // Fallback notification system
            const $notification = $(`
                <div class="aqualuxe-notification aqualuxe-notification-${type}">
                    <div class="aqualuxe-notification-content">
                        <span class="aqualuxe-notification-message">${message}</span>
                        <button class="aqualuxe-notification-close" aria-label="Close">&times;</button>
                    </div>
                </div>
            `);

            // Add CSS if not already present
            if (!$('#aqualuxe-notification-styles').length) {
                $('head').append(`
                    <style id="aqualuxe-notification-styles">
                        .aqualuxe-notification {
                            position: fixed;
                            top: 20px;
                            right: 20px;
                            background: white;
                            border: 1px solid #ddd;
                            border-radius: 4px;
                            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                            z-index: 9999;
                            max-width: 350px;
                            animation: aqualuxeSlideIn 0.3s ease-out;
                        }
                        .aqualuxe-notification-success { border-left: 4px solid #059669; }
                        .aqualuxe-notification-error { border-left: 4px solid #dc2626; }
                        .aqualuxe-notification-info { border-left: 4px solid #4f46e5; }
                        .aqualuxe-notification-content {
                            padding: 12px 16px;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                        }
                        .aqualuxe-notification-message {
                            font-size: 14px;
                            color: #374151;
                        }
                        .aqualuxe-notification-close {
                            background: none;
                            border: none;
                            font-size: 18px;
                            color: #9ca3af;
                            cursor: pointer;
                            margin-left: 12px;
                        }
                        @keyframes aqualuxeSlideIn {
                            from { transform: translateX(100%); opacity: 0; }
                            to { transform: translateX(0); opacity: 1; }
                        }
                    </style>
                `);
            }

            $('body').append($notification);

            // Auto-hide after 5 seconds
            setTimeout(() => {
                $notification.fadeOut(() => $notification.remove());
            }, 5000);

            // Manual close
            $notification.find('.aqualuxe-notification-close').on('click', () => {
                $notification.fadeOut(() => $notification.remove());
            });
        }

        /**
         * Public API methods
         */
        getWishlist() {
            return [...this.localWishlist];
        }

        getWishlistCount() {
            return this.localWishlist.length;
        }

        addItem(postId) {
            this.addToWishlist(parseInt(postId));
        }

        removeItem(postId) {
            this.removeFromWishlist(parseInt(postId));
        }

        clearAll() {
            this.clearWishlist();
        }

        hasItem(postId) {
            return this.isInWishlist(parseInt(postId));
        }

        sync() {
            this.syncWithServer();
        }
    }

    /**
     * Initialize when DOM is ready
     */
    $(document).ready(function() {
        // Initialize wishlist controller
        window.aqualuxeWishlistController = new WishlistController();
        
        // Expose public API
        window.AquaLuxe = window.AquaLuxe || {};
        window.AquaLuxe.wishlist = {
            add: (postId) => window.aqualuxeWishlistController.addItem(postId),
            remove: (postId) => window.aqualuxeWishlistController.removeItem(postId),
            clear: () => window.aqualuxeWishlistController.clearAll(),
            has: (postId) => window.aqualuxeWishlistController.hasItem(postId),
            get: () => window.aqualuxeWishlistController.getWishlist(),
            count: () => window.aqualuxeWishlistController.getWishlistCount(),
            sync: () => window.aqualuxeWishlistController.sync()
        };

        // Handle floating wishlist button
        if ($('.aqualuxe-wishlist-floating').length) {
            $('.aqualuxe-wishlist-floating').on('click', function(e) {
                e.preventDefault();
                const wishlistUrl = $(this).attr('href') || '/wishlist/';
                window.location.href = wishlistUrl;
            });
        }
    });

    /**
     * WooCommerce integration
     */
    $(document).on('updated_wc_div', function() {
        // Re-initialize buttons after WooCommerce AJAX updates
        if (window.aqualuxeWishlistController) {
            window.aqualuxeWishlistController.updateButtonStates();
        }
    });

})(jQuery);
