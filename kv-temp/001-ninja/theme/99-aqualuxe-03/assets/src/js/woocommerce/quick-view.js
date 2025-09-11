// Quick view functionality
(function($) {
    'use strict';
    
    const QuickView = {
        init: function() {
            this.addQuickViewButtons();
            this.bindEvents();
        },
        
        addQuickViewButtons: function() {
            $('.product').each(function() {
                const $product = $(this);
                const productId = $product.data('id') || $product.find('[data-product_id]').data('product_id');
                
                if (productId && !$product.find('.quick-view-btn').length) {
                    const $btn = $(`
                        <button class="quick-view-btn" data-product-id="${productId}">
                            Quick View
                        </button>
                    `);
                    
                    $product.find('.woocommerce-loop-product__link').append($btn);
                }
            });
        },
        
        bindEvents: function() {
            $(document).on('click', '.quick-view-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const productId = $(this).data('product-id');
                this.openQuickView(productId);
            }.bind(this));
            
            $(document).on('click', '.quick-view-overlay, .quick-view-close', function(e) {
                if (e.target === this) {
                    this.closeQuickView();
                }
            }.bind(this));
        },
        
        openQuickView: function(productId) {
            // Create quick view modal
            const $modal = $(`
                <div class="quick-view-overlay">
                    <div class="quick-view-modal">
                        <button class="quick-view-close">&times;</button>
                        <div class="quick-view-content">
                            <div class="loading">Loading product details...</div>
                        </div>
                    </div>
                </div>
            `);
            
            $('body').append($modal);
            
            // Prevent body scroll
            $('body').addClass('quick-view-open');
            
            // Simulate loading product data
            setTimeout(() => {
                this.loadProductData(productId, $modal.find('.quick-view-content'));
            }, 500);
        },
        
        closeQuickView: function() {
            $('.quick-view-overlay').fadeOut(300, function() {
                $(this).remove();
            });
            $('body').removeClass('quick-view-open');
        },
        
        loadProductData: function(productId, $container) {
            // In a real implementation, this would load via AJAX
            const mockProductData = `
                <div class="product-quick-view">
                    <div class="product-images">
                        <img src="https://via.placeholder.com/400x400?text=Product+${productId}" alt="Product Image">
                    </div>
                    <div class="product-summary">
                        <h2>Product Title ${productId}</h2>
                        <p class="price">$29.99</p>
                        <div class="product-description">
                            <p>This is a sample product description for product ${productId}.</p>
                        </div>
                        <form class="cart">
                            <div class="quantity">
                                <input type="number" value="1" min="1" max="10">
                            </div>
                            <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                        </form>
                    </div>
                </div>
            `;
            
            $container.html(mockProductData);
            
            // Initialize quantity buttons for quick view
            if (window.AquaLuxeWooCommerce && window.AquaLuxeWooCommerce.setupQuantityButtons) {
                window.AquaLuxeWooCommerce.setupQuantityButtons();
            }
        }
    };
    
    $(document).ready(function() {
        QuickView.init();
    });
    
})(jQuery);