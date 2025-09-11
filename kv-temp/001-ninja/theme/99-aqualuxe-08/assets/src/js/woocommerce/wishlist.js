// Wishlist functionality
(function($) {
    'use strict';
    
    const Wishlist = {
        init: function() {
            this.addWishlistButtons();
            this.bindEvents();
        },
        
        addWishlistButtons: function() {
            // Add wishlist buttons to products
            $('.product').each(function() {
                const $product = $(this);
                const productId = $product.data('id') || $product.find('[data-product_id]').data('product_id');
                
                if (productId && !$product.find('.wishlist-btn').length) {
                    const $btn = $(`
                        <button class="wishlist-btn" data-product-id="${productId}" aria-label="Add to wishlist">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </button>
                    `);
                    
                    $product.find('.woocommerce-loop-product__link, .product-image').first().append($btn);
                }
            });
        },
        
        bindEvents: function() {
            $(document).on('click', '.wishlist-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const $btn = $(this);
                const productId = $btn.data('product-id');
                
                $btn.addClass('loading');
                
                // Simulate AJAX request
                setTimeout(() => {
                    $btn.removeClass('loading').addClass('added');
                    $btn.attr('aria-label', 'Remove from wishlist');
                    
                    // Show notification
                    this.showNotification('Product added to wishlist!');
                }, 500);
            }.bind(this));
        },
        
        showNotification: function(message) {
            const $notification = $(`
                <div class="wishlist-notification">
                    ${message}
                </div>
            `);
            
            $('body').append($notification);
            
            setTimeout(() => {
                $notification.addClass('show');
            }, 10);
            
            setTimeout(() => {
                $notification.removeClass('show');
                setTimeout(() => $notification.remove(), 300);
            }, 3000);
        }
    };
    
    $(document).ready(function() {
        Wishlist.init();
    });
    
})(jQuery);