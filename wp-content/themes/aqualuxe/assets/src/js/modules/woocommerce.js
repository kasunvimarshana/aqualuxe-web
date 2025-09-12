/**
 * WooCommerce Module
 * 
 * Handles WooCommerce specific functionality
 * 
 * @package AquaLuxe
 */

jQuery(document).ready(function($) {
    'use strict';
    
    /**
     * WooCommerce functionality
     */
    const AquaLuxeWooCommerce = {
        
        /**
         * Initialize
         */
        init: function() {
            this.setupQuickView();
            this.setupProductGallery();
            this.setupWishlist();
            this.setupCartUpdates();
            this.setupShopFilters();
            this.setupProductVariations();
            this.setupMiniCart();
            this.setupCheckoutEnhancements();
        },
        
        /**
         * Setup quick view functionality
         */
        setupQuickView: function() {
            $(document).on('click', '.quick-view-btn', function(e) {
                e.preventDefault();
                
                const productId = $(this).data('product-id');
                const $button = $(this);
                
                $button.addClass('loading');
                
                $.post(aqualuxe_vars.ajax_url, {
                    action: 'aqualuxe_quick_view',
                    product_id: productId,
                    nonce: aqualuxe_vars.nonce
                })
                .done(function(response) {
                    if (response.success) {
                        // Create modal
                        const modal = $(`
                            <div class="quick-view-modal fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                                <div class="bg-white dark:bg-gray-800 rounded-lg max-w-4xl w-full max-h-screen overflow-y-auto">
                                    <div class="p-6">
                                        <button class="quick-view-close float-right text-gray-400 hover:text-gray-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                        <div class="quick-view-content">
                                            ${response.data}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);
                        
                        $('body').append(modal);
                        
                        // Initialize product form
                        modal.find('form.cart').wc_variation_form();
                    }
                })
                .fail(function() {
                    alert('Failed to load product details.');
                })
                .always(function() {
                    $button.removeClass('loading');
                });
            });
            
            // Close quick view
            $(document).on('click', '.quick-view-close, .quick-view-modal', function(e) {
                if (e.target === this) {
                    $('.quick-view-modal').remove();
                }
            });
        },
        
        /**
         * Setup product gallery enhancements
         */
        setupProductGallery: function() {
            // Product image zoom
            $('.woocommerce-product-gallery__image').each(function() {
                const $this = $(this);
                const $img = $this.find('img');
                
                if ($img.length) {
                    $this.on('mouseenter', function() {
                        $img.addClass('scale-110');
                    });
                    
                    $this.on('mouseleave', function() {
                        $img.removeClass('scale-110');
                    });
                }
            });
        },
        
        /**
         * Setup wishlist functionality
         */
        setupWishlist: function() {
            $(document).on('click', '.add-to-wishlist', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const productId = $button.data('product-id');
                
                $button.addClass('loading');
                
                $.post(aqualuxe_vars.ajax_url, {
                    action: 'aqualuxe_add_to_wishlist',
                    product_id: productId,
                    nonce: aqualuxe_vars.nonce
                })
                .done(function(response) {
                    if (response.success) {
                        $button.addClass('added').text('Added to Wishlist');
                        
                        // Update wishlist count
                        $('.wishlist-count').text(response.data.count);
                    } else {
                        alert(response.data || 'Failed to add to wishlist.');
                    }
                })
                .fail(function() {
                    alert('Failed to add to wishlist.');
                })
                .always(function() {
                    $button.removeClass('loading');
                });
            });
        },
        
        /**
         * Setup cart updates
         */
        setupCartUpdates: function() {
            // Update cart on quantity change
            $(document).on('change', '.cart .qty', function() {
                const $form = $(this).closest('form');
                $form.find('[name="update_cart"]').prop('disabled', false).trigger('click');
            });
            
            // Mini cart updates
            $(document.body).on('added_to_cart', function(event, fragments) {
                // Update cart count
                if (fragments['div.widget_shopping_cart_content']) {
                    $('.widget_shopping_cart_content').html(
                        $(fragments['div.widget_shopping_cart_content']).html()
                    );
                }
                
                // Show cart notification
                const notification = $(`
                    <div class="cart-notification fixed bottom-4 right-4 bg-green-500 text-white p-4 rounded-lg shadow-lg z-50">
                        <p>Product added to cart!</p>
                    </div>
                `);
                
                $('body').append(notification);
                
                setTimeout(() => {
                    notification.fadeOut(() => notification.remove());
                }, 3000);
            });
        },
        
        /**
         * Setup shop filters
         */
        setupShopFilters: function() {
            // Price filter
            $('.price-filter-slider').each(function() {
                const $slider = $(this);
                // Initialize range slider (you can use a library like noUiSlider here)
                // For now, we'll just handle the form submission
                
                $slider.closest('form').on('submit', function() {
                    // Add loading state
                    $('.shop-products').addClass('loading');
                });
            });
            
            // Category filter
            $('.product-categories input[type="checkbox"]').on('change', function() {
                const $form = $(this).closest('form');
                
                // Auto-submit form on filter change
                setTimeout(() => {
                    $form.submit();
                }, 100);
            });
            
            // Sort by dropdown
            $('.woocommerce-ordering select').on('change', function() {
                $(this).closest('form').submit();
            });
        },
        
        /**
         * Setup product variations
         */
        setupProductVariations: function() {
            // Custom variation display
            $(document).on('show_variation', '.variations_form', function(event, variation) {
                const $form = $(this);
                const $price = $form.find('.price');
                
                if (variation.price_html) {
                    $price.html(variation.price_html);
                }
                
                // Update stock status
                if (variation.is_in_stock) {
                    $form.find('.stock').removeClass('out-of-stock').addClass('in-stock');
                } else {
                    $form.find('.stock').removeClass('in-stock').addClass('out-of-stock');
                }
            });
            
            // Reset variations
            $(document).on('reset_data', '.variations_form', function() {
                const $form = $(this);
                $form.find('.stock').removeClass('in-stock out-of-stock');
            });
        },
        
        /**
         * Setup mini cart
         */
        setupMiniCart: function() {
            // Toggle mini cart
            $(document).on('click', '.mini-cart-toggle', function(e) {
                e.preventDefault();
                $('.mini-cart-dropdown').toggle();
            });
            
            // Close mini cart when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.mini-cart').length) {
                    $('.mini-cart-dropdown').hide();
                }
            });
        },
        
        /**
         * Setup checkout enhancements
         */
        setupCheckoutEnhancements: function() {
            if ($('body').hasClass('woocommerce-checkout')) {
                // Auto-update checkout on field changes
                let updateTimer;
                
                $(document).on('change', '.checkout .input-text, .checkout select, .checkout input[type="checkbox"]', function() {
                    clearTimeout(updateTimer);
                    updateTimer = setTimeout(() => {
                        $('body').trigger('update_checkout');
                    }, 1000);
                });
                
                // Smooth scrolling to checkout errors
                $(document).on('checkout_error', function() {
                    const $errors = $('.woocommerce-error, .woocommerce-message');
                    if ($errors.length) {
                        $('html, body').animate({
                            scrollTop: $errors.first().offset().top - 100
                        }, 600);
                    }
                });
            }
        }
    };
    
    // Initialize WooCommerce functionality
    if (typeof wc_add_to_cart_params !== 'undefined') {
        AquaLuxeWooCommerce.init();
    }
});