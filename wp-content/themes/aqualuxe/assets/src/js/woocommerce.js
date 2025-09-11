/**
 * WooCommerce JavaScript
 * 
 * Handles WooCommerce-specific functionality
 */

(function($) {
    'use strict';

    const WooCommerce = {
        
        init: function() {
            this.initQuickView();
            this.initProductGallery();
            this.initCartFunctionality();
            this.initCheckoutEnhancements();
            this.initProductFilters();
            this.initProductComparison();
            this.initWishlist();
        },

        initQuickView: function() {
            $('.quick-view-button').on('click', function(e) {
                e.preventDefault();
                
                const productId = $(this).data('product-id');
                const button = $(this);
                
                button.addClass('loading');
                
                $.ajax({
                    url: aqualuxe_wc.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'quick_view_product',
                        product_id: productId,
                        nonce: aqualuxe_wc.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            WooCommerce.showQuickViewModal(response.data);
                        }
                    },
                    error: function() {
                        alert('Error loading product details');
                    },
                    complete: function() {
                        button.removeClass('loading');
                    }
                });
            });
        },

        showQuickViewModal: function(content) {
            const modal = $(`
                <div class="quick-view-modal fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="quick-view-content bg-white dark:bg-gray-800 rounded-lg max-w-4xl w-full mx-4 max-h-90vh overflow-y-auto">
                        <div class="quick-view-header flex justify-between items-center p-4 border-b">
                            <h3 class="text-lg font-semibold">Quick View</h3>
                            <button class="quick-view-close text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="quick-view-body p-6">
                            ${content}
                        </div>
                    </div>
                </div>
            `);
            
            $('body').append(modal);
            modal.fadeIn(300);
            
            // Close modal events
            modal.find('.quick-view-close').on('click', function() {
                modal.fadeOut(300, function() {
                    modal.remove();
                });
            });
            
            modal.on('click', function(e) {
                if (e.target === this) {
                    modal.fadeOut(300, function() {
                        modal.remove();
                    });
                }
            });
        },

        initProductGallery: function() {
            $('.woocommerce-product-gallery').each(function() {
                const gallery = $(this);
                const mainImage = gallery.find('.woocommerce-product-gallery__wrapper img');
                const thumbnails = gallery.find('.flex-control-thumbs img');
                
                thumbnails.on('click', function() {
                    const newSrc = $(this).attr('src');
                    mainImage.attr('src', newSrc);
                    thumbnails.removeClass('active');
                    $(this).addClass('active');
                });
            });
        },

        initCartFunctionality: function() {
            // Update cart on quantity change
            $(document).on('change', '.cart .qty', function() {
                $('[name="update_cart"]').trigger('click');
            });
            
            // AJAX add to cart
            $(document).on('click', '.ajax-add-to-cart', function(e) {
                e.preventDefault();
                
                const button = $(this);
                const productId = button.data('product-id');
                const quantity = button.closest('.product').find('.qty').val() || 1;
                
                button.addClass('loading').text('Adding...');
                
                $.ajax({
                    url: aqualuxe_wc.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'add_to_cart',
                        product_id: productId,
                        quantity: quantity,
                        nonce: aqualuxe_wc.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update cart count
                            $('.cart-count').text(response.data.cart_count);
                            
                            // Show success message
                            WooCommerce.showNotification('Product added to cart!', 'success');
                            
                            // Trigger cart update event
                            $(document.body).trigger('added_to_cart', [response.data.fragments, response.data.cart_hash, button]);
                        } else {
                            WooCommerce.showNotification(response.data.message, 'error');
                        }
                    },
                    error: function() {
                        WooCommerce.showNotification('Error adding product to cart', 'error');
                    },
                    complete: function() {
                        button.removeClass('loading').text('Add to Cart');
                    }
                });
            });
        },

        initCheckoutEnhancements: function() {
            // Auto-update checkout on field changes
            $(document).on('change', '.woocommerce-checkout .input-text, .woocommerce-checkout select', function() {
                $('body').trigger('update_checkout');
            });
            
            // Address autocomplete
            if (typeof google !== 'undefined' && google.maps) {
                this.initAddressAutocomplete();
            }
        },

        initAddressAutocomplete: function() {
            const addressFields = ['billing_address_1', 'shipping_address_1'];
            
            addressFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    const autocomplete = new google.maps.places.Autocomplete(field);
                    autocomplete.addListener('place_changed', function() {
                        const place = autocomplete.getPlace();
                        // Auto-fill address components
                        WooCommerce.fillAddressFields(place, fieldId.startsWith('billing') ? 'billing' : 'shipping');
                    });
                }
            });
        },

        fillAddressFields: function(place, type) {
            const components = place.address_components;
            const mapping = {
                street_number: `${type}_address_1`,
                route: `${type}_address_1`,
                locality: `${type}_city`,
                administrative_area_level_1: `${type}_state`,
                postal_code: `${type}_postcode`,
                country: `${type}_country`
            };
            
            // Fill fields based on address components
            components.forEach(component => {
                const type = component.types[0];
                if (mapping[type]) {
                    const field = document.getElementById(mapping[type]);
                    if (field) {
                        if (type === 'street_number' || type === 'route') {
                            // Combine street number and route
                            const existingValue = field.value;
                            field.value = existingValue ? `${existingValue} ${component.long_name}` : component.long_name;
                        } else {
                            field.value = component.long_name;
                        }
                    }
                }
            });
        },

        initProductFilters: function() {
            // Price range filter
            $('.price-range-slider').each(function() {
                const slider = $(this);
                const minPrice = slider.data('min-price');
                const maxPrice = slider.data('max-price');
                
                // Initialize range slider (requires a range slider library)
                if ($.fn.slider) {
                    slider.slider({
                        range: true,
                        min: minPrice,
                        max: maxPrice,
                        values: [minPrice, maxPrice],
                        slide: function(event, ui) {
                            $('.price-range-display').text(`$${ui.values[0]} - $${ui.values[1]}`);
                        },
                        stop: function(event, ui) {
                            // Apply filter
                            WooCommerce.applyPriceFilter(ui.values[0], ui.values[1]);
                        }
                    });
                }
            });
            
            // Category filters
            $('.product-category-filter input[type="checkbox"]').on('change', function() {
                WooCommerce.applyFilters();
            });
            
            // Attribute filters
            $('.product-attribute-filter input[type="checkbox"]').on('change', function() {
                WooCommerce.applyFilters();
            });
        },

        applyPriceFilter: function(minPrice, maxPrice) {
            const url = new URL(window.location);
            url.searchParams.set('min_price', minPrice);
            url.searchParams.set('max_price', maxPrice);
            window.location.href = url.toString();
        },

        applyFilters: function() {
            const form = $('.shop-filters form');
            if (form.length) {
                form.submit();
            }
        },

        initProductComparison: function() {
            $('.compare-button').on('click', function(e) {
                e.preventDefault();
                
                const productId = $(this).data('product-id');
                const button = $(this);
                
                // Get current comparison list from localStorage
                let compareList = JSON.parse(localStorage.getItem('product_comparison') || '[]');
                
                if (compareList.includes(productId)) {
                    // Remove from comparison
                    compareList = compareList.filter(id => id !== productId);
                    button.removeClass('active').text('Compare');
                } else {
                    // Add to comparison (limit to 4 products)
                    if (compareList.length >= 4) {
                        WooCommerce.showNotification('You can compare up to 4 products', 'warning');
                        return;
                    }
                    compareList.push(productId);
                    button.addClass('active').text('Remove Compare');
                }
                
                localStorage.setItem('product_comparison', JSON.stringify(compareList));
                WooCommerce.updateComparisonCounter(compareList.length);
            });
        },

        updateComparisonCounter: function(count) {
            $('.comparison-counter').text(count);
            if (count > 0) {
                $('.comparison-link').show();
            } else {
                $('.comparison-link').hide();
            }
        },

        initWishlist: function() {
            $('.wishlist-button').on('click', function(e) {
                e.preventDefault();
                
                const productId = $(this).data('product-id');
                const button = $(this);
                
                button.addClass('loading');
                
                $.ajax({
                    url: aqualuxe_wc.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'toggle_wishlist',
                        product_id: productId,
                        nonce: aqualuxe_wc.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.data.added) {
                                button.addClass('active').find('.wishlist-text').text('Remove from Wishlist');
                                WooCommerce.showNotification('Added to wishlist!', 'success');
                            } else {
                                button.removeClass('active').find('.wishlist-text').text('Add to Wishlist');
                                WooCommerce.showNotification('Removed from wishlist!', 'info');
                            }
                            
                            // Update wishlist counter
                            $('.wishlist-count').text(response.data.count);
                        }
                    },
                    error: function() {
                        WooCommerce.showNotification('Error updating wishlist', 'error');
                    },
                    complete: function() {
                        button.removeClass('loading');
                    }
                });
            });
        },

        showNotification: function(message, type = 'info') {
            const notification = $(`
                <div class="wc-notification wc-notification-${type} fixed top-4 right-4 bg-white dark:bg-gray-800 border-l-4 border-${type === 'success' ? 'green' : type === 'error' ? 'red' : type === 'warning' ? 'yellow' : 'blue'}-500 p-4 rounded shadow-lg z-50 transform translate-x-full transition-transform duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            ${type === 'success' ? '✓' : type === 'error' ? '✗' : type === 'warning' ? '⚠' : 'ℹ'}
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
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                notification.addClass('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
            
            // Close button
            notification.find('.notification-close').on('click', function() {
                notification.addClass('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            });
        }
    };

    // Initialize when ready
    $(document).ready(function() {
        if (typeof woocommerce !== 'undefined' || $('body').hasClass('woocommerce')) {
            WooCommerce.init();
        }
    });

    // Make available globally
    window.AquaLuxeWooCommerce = WooCommerce;

})(jQuery);