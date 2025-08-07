/**
 * Shop JavaScript for AquaLuxe Theme
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

(function($) {
    'use strict';

    var AquaLuxeShop = {
        
        /**
         * Initialize shop functions
         */
        init: function() {
            this.quickView();
            this.ajaxAddToCart();
            this.productFilters();
            this.productSorting();
            this.gridListToggle();
            this.updateMiniCart();
        },

        /**
         * Quick view functionality
         */
        quickView: function() {
            var self = this;

            $(document).on('click', '.quick-view-btn', function(e) {
                e.preventDefault();
                
                var $button = $(this);
                var productId = $button.data('product-id');
                
                if (!productId) return;

                // Show loading state
                $button.addClass('loading');
                
                // Create modal if it doesn't exist
                if (!$('#quick-view-modal').length) {
                    $('body').append('<div id="quick-view-modal" class="modal quick-view-modal"><div class="modal-content"><span class="close">&times;</span><div class="modal-body"></div></div></div>');
                }

                var $modal = $('#quick-view-modal');
                var $modalBody = $modal.find('.modal-body');

                // AJAX request for product data
                $.ajax({
                    url: aqualuxe_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_quick_view',
                        product_id: productId,
                        nonce: aqualuxe_ajax.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            $modalBody.html(response.data);
                            $modal.fadeIn();
                            $('body').addClass('modal-open');
                            
                            // Initialize WooCommerce scripts for the modal content
                            if (typeof wc_single_product_params !== 'undefined') {
                                $modalBody.find('.variations_form').wc_variation_form();
                            }
                        }
                    },
                    error: function() {
                        console.log('Quick view failed');
                    },
                    complete: function() {
                        $button.removeClass('loading');
                    }
                });
            });

            // Close modal
            $(document).on('click', '#quick-view-modal .close, #quick-view-modal', function(e) {
                if (e.target === this) {
                    $('#quick-view-modal').fadeOut();
                    $('body').removeClass('modal-open');
                }
            });

            // Close modal on escape key
            $(document).on('keydown', function(e) {
                if (e.keyCode === 27 && $('#quick-view-modal').is(':visible')) {
                    $('#quick-view-modal').fadeOut();
                    $('body').removeClass('modal-open');
                }
            });
        },

        /**
         * AJAX Add to Cart
         */
        ajaxAddToCart: function() {
            var self = this;

            $(document).on('click', '.ajax-add-to-cart', function(e) {
                e.preventDefault();
                
                var $button = $(this);
                var $form = $button.closest('form');
                var productId = $button.val() || $button.data('product_id');
                var quantity = $form.find('input[name="quantity"]').val() || 1;
                var variationId = $form.find('input[name="variation_id"]').val() || 0;
                var variation = {};

                // Get variation data
                $form.find('.variations select').each(function() {
                    var name = $(this).attr('name');
                    var value = $(this).val();
                    variation[name] = value;
                });

                // Show loading state
                $button.addClass('loading').prop('disabled', true);

                $.ajax({
                    url: aqualuxe_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_ajax_add_to_cart',
                        product_id: productId,
                        quantity: quantity,
                        variation_id: variationId,
                        variation: variation,
                        nonce: aqualuxe_ajax.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update cart count and total
                            $('.cart-count').text(response.data.cart_count);
                            $('.cart-total').html(response.data.cart_total);
                            
                            // Update mini cart
                            self.updateMiniCart();
                            
                            // Show success message
                            self.showNotification('Product added to cart!', 'success');
                            
                            // Update button text
                            $button.text('Added to Cart');
                            
                            // Trigger custom event
                            $(document).trigger('added_to_cart', [response.data, productId, $button]);
                        } else {
                            self.showNotification('Failed to add product to cart.', 'error');
                        }
                    },
                    error: function() {
                        self.showNotification('Failed to add product to cart.', 'error');
                    },
                    complete: function() {
                        $button.removeClass('loading').prop('disabled', false);
                        
                        // Reset button text after delay
                        setTimeout(function() {
                            $button.text($button.data('original-text') || 'Add to Cart');
                        }, 2000);
                    }
                });
            });
        },

        /**
         * Product filters
         */
        productFilters: function() {
            // Price filter
            if ($('.price-filter-slider').length) {
                $('.price-filter-slider').each(function() {
                    var $slider = $(this);
                    var min = parseFloat($slider.data('min')) || 0;
                    var max = parseFloat($slider.data('max')) || 1000;
                    var currentMin = parseFloat($slider.data('current-min')) || min;
                    var currentMax = parseFloat($slider.data('current-max')) || max;

                    $slider.slider({
                        range: true,
                        min: min,
                        max: max,
                        values: [currentMin, currentMax],
                        slide: function(event, ui) {
                            $('.price-filter-min').val(ui.values[0]);
                            $('.price-filter-max').val(ui.values[1]);
                            $('.price-display').text('$' + ui.values[0] + ' - $' + ui.values[1]);
                        },
                        stop: function(event, ui) {
                            $(this).closest('form').submit();
                        }
                    });
                });
            }

            // Attribute filters
            $('.woocommerce-widget-layered-nav-list a').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                window.location.href = url;
            });

            // Clear filters
            $('.clear-filters').on('click', function(e) {
                e.preventDefault();
                var shopUrl = $(this).data('shop-url') || window.location.pathname;
                window.location.href = shopUrl;
            });
        },

        /**
         * Product sorting
         */
        productSorting: function() {
            $('.woocommerce-ordering select').on('change', function() {
                $(this).closest('form').submit();
            });

            // Results count and sorting toggle
            $('.results-sorting-toggle').on('click', function(e) {
                e.preventDefault();
                $('.results-sorting').toggleClass('active');
            });
        },

        /**
         * Grid/List toggle
         */
        gridListToggle: function() {
            var $toggleButtons = $('.view-toggle button');
            var $productList = $('.products');

            $toggleButtons.on('click', function(e) {
                e.preventDefault();
                
                var $button = $(this);
                var view = $button.data('view');
                
                // Update active state
                $toggleButtons.removeClass('active');
                $button.addClass('active');
                
                // Update product list view
                $productList.removeClass('grid-view list-view').addClass(view + '-view');
                
                // Store preference
                localStorage.setItem('aqualuxe_shop_view', view);
            });

            // Load saved preference
            var savedView = localStorage.getItem('aqualuxe_shop_view');
            if (savedView) {
                $toggleButtons.removeClass('active');
                $toggleButtons.filter('[data-view="' + savedView + '"]').addClass('active');
                $productList.removeClass('grid-view list-view').addClass(savedView + '-view');
            }
        },

        /**
         * Update mini cart
         */
        updateMiniCart: function() {
            $.ajax({
                url: aqualuxe_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_update_mini_cart',
                    nonce: aqualuxe_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $('.mini-cart-container').html(response.data.mini_cart);
                        $('.cart-count').text(response.data.cart_count);
                        $('.cart-total').html(response.data.cart_total);
                    }
                }
            });
        },

        /**
         * Show notification
         */
        showNotification: function(message, type) {
            type = type || 'info';
            
            var $notification = $('<div class="notification notification-' + type + '">' + message + '</div>');
            
            // Add to page
            if (!$('.notifications-container').length) {
                $('body').append('<div class="notifications-container"></div>');
            }
            
            $('.notifications-container').append($notification);
            
            // Show notification
            setTimeout(function() {
                $notification.addClass('show');
            }, 100);
            
            // Hide notification after delay
            setTimeout(function() {
                $notification.removeClass('show');
                setTimeout(function() {
                    $notification.remove();
                }, 300);
            }, 3000);
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        AquaLuxeShop.init();
    });

    // Update fragments (for cart updates)
    $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
        // Update any fragments
        if (fragments) {
            $.each(fragments, function(key, value) {
                $(key).replaceWith(value);
            });
        }
    });

})(jQuery);