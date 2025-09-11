/**
 * Quick View JavaScript
 * 
 * Handles product quick view functionality
 */

(function($) {
    'use strict';

    const QuickView = {
        
        init: function() {
            this.bindEvents();
            this.initModal();
        },

        bindEvents: function() {
            $(document).on('click', '.quick-view-trigger', this.openQuickView);
            $(document).on('click', '.quick-view-close', this.closeQuickView);
            $(document).on('click', '.quick-view-overlay', this.closeQuickView);
            $(document).on('keydown', this.handleKeyboard);
        },

        openQuickView: function(e) {
            e.preventDefault();
            
            const button = $(this);
            const productId = button.data('product-id');
            
            if (!productId) return;
            
            button.addClass('loading');
            
            // Show loading state
            QuickView.showLoadingModal();
            
            // AJAX request to get product data
            $.ajax({
                url: aqualuxe.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_quick_view_product',
                    product_id: productId,
                    nonce: aqualuxe.nonce
                },
                success: function(response) {
                    if (response.success) {
                        QuickView.showQuickViewModal(response.data);
                    } else {
                        QuickView.showError(response.data.message || 'Failed to load product');
                    }
                },
                error: function() {
                    QuickView.showError('Network error occurred');
                },
                complete: function() {
                    button.removeClass('loading');
                }
            });
        },

        showLoadingModal: function() {
            const modal = $(`
                <div class="quick-view-modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="quick-view-content bg-white dark:bg-gray-800 rounded-lg p-8 max-w-md w-full mx-4">
                        <div class="text-center">
                            <div class="spinner mx-auto mb-4"></div>
                            <p class="text-gray-600 dark:text-gray-400">Loading product...</p>
                        </div>
                    </div>
                </div>
            `);
            
            $('body').append(modal).addClass('quick-view-open');
        },

        showQuickViewModal: function(productData) {
            $('.quick-view-modal').remove();
            
            const modal = $(`
                <div class="quick-view-modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 animate-fade-in">
                    <div class="quick-view-content bg-white dark:bg-gray-800 rounded-lg max-w-6xl w-full mx-4 max-h-[90vh] overflow-hidden">
                        <div class="quick-view-header flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Quick View</h2>
                            <button class="quick-view-close p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="quick-view-body overflow-y-auto">
                            ${productData}
                        </div>
                    </div>
                    <div class="quick-view-overlay absolute inset-0"></div>
                </div>
            `);
            
            $('body').append(modal).addClass('quick-view-open');
            
            // Initialize product features in modal
            QuickView.initModalFeatures();
            
            // Focus management for accessibility
            modal.find('.quick-view-close').focus();
        },

        initModalFeatures: function() {
            const modal = $('.quick-view-modal');
            
            // Initialize product gallery
            QuickView.initProductGallery(modal);
            
            // Initialize variation selector
            QuickView.initVariationSelector(modal);
            
            // Initialize quantity selector
            QuickView.initQuantitySelector(modal);
            
            // Initialize add to cart
            QuickView.initAddToCart(modal);
            
            // Initialize product tabs
            QuickView.initProductTabs(modal);
        },

        initProductGallery: function(modal) {
            const gallery = modal.find('.product-gallery');
            const mainImage = gallery.find('.main-image img');
            const thumbnails = gallery.find('.thumbnails img');
            
            thumbnails.on('click', function() {
                const newSrc = $(this).data('large-src') || $(this).attr('src');
                mainImage.attr('src', newSrc);
                thumbnails.removeClass('active');
                $(this).addClass('active');
            });
            
            // Keyboard navigation for gallery
            thumbnails.on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    $(this).click();
                }
            });
        },

        initVariationSelector: function(modal) {
            const variationForm = modal.find('.variations_form');
            
            if (variationForm.length && $.fn.wc_variation_form) {
                variationForm.wc_variation_form();
                
                // Update image when variation changes
                variationForm.on('found_variation', function(event, variation) {
                    if (variation.image && variation.image.src) {
                        const mainImage = modal.find('.main-image img');
                        mainImage.attr('src', variation.image.src);
                    }
                });
            }
        },

        initQuantitySelector: function(modal) {
            const quantityContainer = modal.find('.quantity');
            const quantityInput = quantityContainer.find('input[type="number"]');
            const decreaseBtn = quantityContainer.find('.quantity-decrease');
            const increaseBtn = quantityContainer.find('.quantity-increase');
            
            decreaseBtn.on('click', function(e) {
                e.preventDefault();
                const currentValue = parseInt(quantityInput.val()) || 1;
                const minValue = parseInt(quantityInput.attr('min')) || 1;
                if (currentValue > minValue) {
                    quantityInput.val(currentValue - 1).trigger('change');
                }
            });
            
            increaseBtn.on('click', function(e) {
                e.preventDefault();
                const currentValue = parseInt(quantityInput.val()) || 1;
                const maxValue = parseInt(quantityInput.attr('max'));
                if (!maxValue || currentValue < maxValue) {
                    quantityInput.val(currentValue + 1).trigger('change');
                }
            });
        },

        initAddToCart: function(modal) {
            const addToCartForm = modal.find('form.cart');
            
            addToCartForm.on('submit', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const productId = form.find('input[name="product_id"]').val();
                const quantity = form.find('input[name="quantity"]').val() || 1;
                const variation = form.find('input[name="variation_id"]').val();
                
                const submitButton = form.find('button[type="submit"]');
                const originalText = submitButton.text();
                
                submitButton.addClass('loading').text('Adding...');
                
                const data = {
                    action: 'add_to_cart_ajax',
                    product_id: productId,
                    quantity: quantity,
                    nonce: aqualuxe.nonce
                };
                
                if (variation) {
                    data.variation_id = variation;
                    
                    // Add variation attributes
                    form.find('.variations select').each(function() {
                        const name = $(this).attr('name');
                        const value = $(this).val();
                        if (name && value) {
                            data[name] = value;
                        }
                    });
                }
                
                $.ajax({
                    url: aqualuxe.ajax_url,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            // Update cart count
                            $('.cart-count').text(response.data.cart_count);
                            
                            // Show success message
                            QuickView.showSuccessMessage('Product added to cart!');
                            
                            // Close modal after a delay
                            setTimeout(() => {
                                QuickView.closeQuickView();
                            }, 1500);
                            
                            // Trigger custom event
                            $(document).trigger('quick_view_add_to_cart', [response.data]);
                        } else {
                            QuickView.showError(response.data.message || 'Failed to add product to cart');
                        }
                    },
                    error: function() {
                        QuickView.showError('Network error occurred');
                    },
                    complete: function() {
                        submitButton.removeClass('loading').text(originalText);
                    }
                });
            });
        },

        initProductTabs: function(modal) {
            const tabs = modal.find('.product-tabs');
            const tabButtons = tabs.find('.tab-button');
            const tabContents = tabs.find('.tab-content');
            
            tabButtons.on('click', function(e) {
                e.preventDefault();
                
                const targetTab = $(this).data('tab');
                
                // Update active states
                tabButtons.removeClass('active');
                $(this).addClass('active');
                
                tabContents.removeClass('active');
                tabs.find(`.tab-content[data-tab="${targetTab}"]`).addClass('active');
            });
        },

        showSuccessMessage: function(message) {
            const modal = $('.quick-view-modal');
            const successMsg = $(`
                <div class="success-message absolute top-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-10 animate-fade-in-down">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        ${message}
                    </div>
                </div>
            `);
            
            modal.append(successMsg);
            
            setTimeout(() => {
                successMsg.remove();
            }, 3000);
        },

        showError: function(message) {
            $('.quick-view-modal').remove();
            
            const errorModal = $(`
                <div class="quick-view-modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="quick-view-content bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4">
                        <div class="text-center">
                            <div class="text-red-500 mb-4">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.346 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Error</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">${message}</p>
                            <button class="quick-view-close btn-primary">Close</button>
                        </div>
                    </div>
                </div>
            `);
            
            $('body').append(errorModal).addClass('quick-view-open');
            
            setTimeout(() => {
                QuickView.closeQuickView();
            }, 3000);
        },

        closeQuickView: function(e) {
            if (e) e.preventDefault();
            
            $('.quick-view-modal').addClass('animate-fade-out');
            $('body').removeClass('quick-view-open');
            
            setTimeout(() => {
                $('.quick-view-modal').remove();
            }, 300);
        },

        handleKeyboard: function(e) {
            if (!$('.quick-view-modal').length) return;
            
            switch (e.key) {
                case 'Escape':
                    QuickView.closeQuickView();
                    break;
                case 'Tab':
                    QuickView.trapFocus(e);
                    break;
            }
        },

        trapFocus: function(e) {
            const modal = $('.quick-view-modal');
            const focusableElements = modal.find('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            const firstElement = focusableElements.first();
            const lastElement = focusableElements.last();
            
            if (e.shiftKey) {
                if (document.activeElement === firstElement[0]) {
                    e.preventDefault();
                    lastElement.focus();
                }
            } else {
                if (document.activeElement === lastElement[0]) {
                    e.preventDefault();
                    firstElement.focus();
                }
            }
        }
    };

    // Initialize when ready
    $(document).ready(function() {
        QuickView.init();
    });

    // Make available globally
    window.AquaLuxeQuickView = QuickView;

})(jQuery);