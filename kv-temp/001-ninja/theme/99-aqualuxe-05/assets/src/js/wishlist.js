/**
 * Wishlist JavaScript
 * 
 * Handles wishlist functionality
 */

(function($) {
    'use strict';

    const Wishlist = {
        
        items: [],
        
        init: function() {
            this.loadWishlist();
            this.bindEvents();
            this.updateUI();
        },

        bindEvents: function() {
            $(document).on('click', '.wishlist-toggle', this.toggleWishlist.bind(this));
            $(document).on('click', '.wishlist-remove', this.removeFromWishlist.bind(this));
            $(document).on('click', '.wishlist-clear', this.clearWishlist.bind(this));
        },

        loadWishlist: function() {
            // Load from localStorage for guests
            if (!aqualuxe.is_user_logged_in) {
                this.items = JSON.parse(localStorage.getItem('aqualuxe_wishlist') || '[]');
                return;
            }

            // Load from server for logged-in users
            $.ajax({
                url: aqualuxe.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_wishlist',
                    nonce: aqualuxe.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.items = response.data.items || [];
                        this.updateUI();
                    }
                }
            });
        },

        saveWishlist: function() {
            if (!aqualuxe.is_user_logged_in) {
                localStorage.setItem('aqualuxe_wishlist', JSON.stringify(this.items));
                return;
            }

            // Save to server for logged-in users
            $.ajax({
                url: aqualuxe.ajax_url,
                type: 'POST',
                data: {
                    action: 'save_wishlist',
                    items: this.items,
                    nonce: aqualuxe.nonce
                }
            });
        },

        toggleWishlist: function(e) {
            e.preventDefault();
            
            const button = $(e.currentTarget);
            const productId = button.data('product-id');
            
            if (!productId) return;
            
            button.addClass('loading');
            
            if (this.isInWishlist(productId)) {
                this.removeFromWishlist(productId, button);
            } else {
                this.addToWishlist(productId, button);
            }
        },

        addToWishlist: function(productId, button) {
            // Get product data
            this.getProductData(productId)
                .then((productData) => {
                    this.items.push({
                        id: productId,
                        name: productData.name,
                        price: productData.price,
                        image: productData.image,
                        url: productData.url,
                        date_added: new Date().toISOString()
                    });
                    
                    this.saveWishlist();
                    this.updateUI();
                    this.showNotification(`${productData.name} added to wishlist!`, 'success');
                    
                    if (button) {
                        this.updateButton(button, true);
                    }
                })
                .catch(() => {
                    this.showNotification('Error adding to wishlist', 'error');
                })
                .finally(() => {
                    if (button) {
                        button.removeClass('loading');
                    }
                });
        },

        removeFromWishlist: function(productId, button) {
            const item = this.items.find(item => item.id == productId);
            this.items = this.items.filter(item => item.id != productId);
            
            this.saveWishlist();
            this.updateUI();
            
            if (item) {
                this.showNotification(`${item.name} removed from wishlist`, 'info');
            }
            
            if (button) {
                this.updateButton(button, false);
                button.removeClass('loading');
            }
        },

        clearWishlist: function(e) {
            if (e) e.preventDefault();
            
            if (confirm('Are you sure you want to clear your wishlist?')) {
                this.items = [];
                this.saveWishlist();
                this.updateUI();
                this.showNotification('Wishlist cleared', 'info');
            }
        },

        isInWishlist: function(productId) {
            return this.items.some(item => item.id == productId);
        },

        getProductData: function(productId) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: aqualuxe.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'get_product_data',
                        product_id: productId,
                        nonce: aqualuxe.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            resolve(response.data);
                        } else {
                            reject(response.data.message);
                        }
                    },
                    error: function() {
                        reject('Network error');
                    }
                });
            });
        },

        updateUI: function() {
            // Update counter
            $('.wishlist-count').text(this.items.length);
            
            // Show/hide counter badge
            if (this.items.length > 0) {
                $('.wishlist-count').removeClass('hidden');
            } else {
                $('.wishlist-count').addClass('hidden');
            }
            
            // Update wishlist buttons
            $('.wishlist-toggle').each((index, element) => {
                const button = $(element);
                const productId = button.data('product-id');
                this.updateButton(button, this.isInWishlist(productId));
            });
            
            // Update wishlist page
            this.updateWishlistPage();
        },

        updateButton: function(button, isInWishlist) {
            const icon = button.find('.wishlist-icon');
            const text = button.find('.wishlist-text');
            
            if (isInWishlist) {
                button.addClass('active in-wishlist');
                icon.removeClass('far').addClass('fas');
                if (text.length) {
                    text.text('Remove from Wishlist');
                }
                button.attr('title', 'Remove from Wishlist');
            } else {
                button.removeClass('active in-wishlist');
                icon.removeClass('fas').addClass('far');
                if (text.length) {
                    text.text('Add to Wishlist');
                }
                button.attr('title', 'Add to Wishlist');
            }
        },

        updateWishlistPage: function() {
            const wishlistContainer = $('.wishlist-items');
            
            if (!wishlistContainer.length) return;
            
            if (this.items.length === 0) {
                wishlistContainer.html(`
                    <div class="empty-wishlist text-center py-12">
                        <div class="text-gray-400 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Your wishlist is empty</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">Add some products to your wishlist to see them here.</p>
                        <a href="${aqualuxe.shop_url}" class="btn-primary">Continue Shopping</a>
                    </div>
                `);
                return;
            }
            
            let html = '';
            this.items.forEach(item => {
                html += this.renderWishlistItem(item);
            });
            
            wishlistContainer.html(html);
        },

        renderWishlistItem: function(item) {
            return `
                <div class="wishlist-item bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-4" data-product-id="${item.id}">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <img src="${item.image}" alt="${item.name}" class="w-16 h-16 object-cover rounded-md">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                <a href="${item.url}" class="hover:text-primary-600 dark:hover:text-primary-400">${item.name}</a>
                            </h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Added ${this.formatDate(item.date_added)}</p>
                            <p class="text-lg font-bold text-primary-600 dark:text-primary-400 mt-1">${item.price}</p>
                        </div>
                        <div class="flex-shrink-0 flex space-x-2">
                            <button class="add-to-cart-from-wishlist btn-primary btn-sm" data-product-id="${item.id}">
                                Add to Cart
                            </button>
                            <button class="wishlist-remove btn-ghost btn-sm text-red-600 hover:text-red-700" data-product-id="${item.id}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        },

        formatDate: function(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffTime = Math.abs(now - date);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays === 1) {
                return 'yesterday';
            } else if (diffDays < 7) {
                return `${diffDays} days ago`;
            } else {
                return date.toLocaleDateString();
            }
        },

        showNotification: function(message, type = 'info') {
            const notification = $(`
                <div class="wishlist-notification fixed top-4 right-4 bg-white dark:bg-gray-800 border-l-4 border-${type === 'success' ? 'green' : type === 'error' ? 'red' : 'blue'}-500 p-4 rounded shadow-lg z-50 transform translate-x-full transition-transform duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 text-${type === 'success' ? 'green' : type === 'error' ? 'red' : 'blue'}-500">
                            ${type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ'}
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-700 dark:text-gray-300">${message}</p>
                        </div>
                        <div class="ml-4">
                            <button class="notification-close text-gray-400 hover:text-gray-600">×</button>
                        </div>
                    </div>
                </div>
            `);
            
            $('body').append(notification);
            
            // Animate in
            setTimeout(() => notification.removeClass('translate-x-full'), 100);
            
            // Auto-hide after 4 seconds
            setTimeout(() => {
                notification.addClass('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 4000);
            
            // Close button
            notification.find('.notification-close').on('click', function() {
                notification.addClass('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            });
        },

        // Public methods for external use
        addProduct: function(productId) {
            if (!this.isInWishlist(productId)) {
                this.addToWishlist(productId);
            }
        },

        removeProduct: function(productId) {
            if (this.isInWishlist(productId)) {
                this.removeFromWishlist(productId);
            }
        },

        getItems: function() {
            return this.items;
        },

        getCount: function() {
            return this.items.length;
        }
    };

    // Initialize when ready
    $(document).ready(function() {
        Wishlist.init();
    });

    // Handle add to cart from wishlist
    $(document).on('click', '.add-to-cart-from-wishlist', function(e) {
        e.preventDefault();
        
        const button = $(this);
        const productId = button.data('product-id');
        const originalText = button.text();
        
        button.addClass('loading').text('Adding...');
        
        $.ajax({
            url: aqualuxe.ajax_url,
            type: 'POST',
            data: {
                action: 'add_to_cart',
                product_id: productId,
                quantity: 1,
                nonce: aqualuxe.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Update cart count
                    $('.cart-count').text(response.data.cart_count);
                    
                    // Show success message
                    Wishlist.showNotification('Product added to cart!', 'success');
                    
                    // Optionally remove from wishlist
                    if (aqualuxe.remove_from_wishlist_after_cart) {
                        Wishlist.removeFromWishlist(productId);
                    }
                } else {
                    Wishlist.showNotification(response.data.message || 'Failed to add to cart', 'error');
                }
            },
            error: function() {
                Wishlist.showNotification('Network error occurred', 'error');
            },
            complete: function() {
                button.removeClass('loading').text(originalText);
            }
        });
    });

    // Make available globally
    window.AquaLuxeWishlist = Wishlist;

})(jQuery);