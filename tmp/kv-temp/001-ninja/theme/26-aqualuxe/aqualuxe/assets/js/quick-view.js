/**
 * File quick-view.js.
 *
 * Handles the quick view functionality for WooCommerce products.
 */
(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        // Quick view button click
        $(document).on('click', '.quick-view', function(e) {
            e.preventDefault();
            
            const productId = $(this).data('product-id');
            
            // Create modal container if it doesn't exist
            if (!$('#quick-view-modal').length) {
                $('body').append('<div id="quick-view-modal" class="quick-view-modal"><div class="quick-view-container"><div class="quick-view-content"></div><button class="quick-view-close" aria-label="Close">&times;</button></div></div>');
            }
            
            const modal = $('#quick-view-modal');
            const content = modal.find('.quick-view-content');
            
            // Show loading state
            content.html('<div class="quick-view-loading"><div class="spinner"></div><p>' + aqualuxeQuickView.i18n.loading + '</p></div>');
            modal.addClass('open');
            
            // Prevent body scrolling
            $('body').addClass('quick-view-open');
            
            // Get product data via AJAX
            $.ajax({
                url: aqualuxeQuickView.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_quick_view',
                    product_id: productId,
                    nonce: aqualuxeQuickView.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Update modal content
                        content.html(response.data.content);
                        
                        // Initialize product gallery if available
                        if (typeof $.fn.flexslider === 'function') {
                            $('.quick-view-gallery').flexslider({
                                animation: 'slide',
                                controlNav: 'thumbnails'
                            });
                        }
                        
                        // Initialize product variations if available
                        if (typeof $.fn.wc_variation_form === 'function') {
                            $('.variations_form').wc_variation_form();
                        }
                        
                        // Initialize quantity buttons
                        initQuantityButtons();
                    } else {
                        content.html('<div class="quick-view-error"><p>' + response.data.message + '</p></div>');
                    }
                },
                error: function() {
                    content.html('<div class="quick-view-error"><p>Error loading product data. Please try again.</p></div>');
                }
            });
            
            // Close modal on close button click
            $(document).on('click', '.quick-view-close', function() {
                closeQuickView();
            });
            
            // Close modal on background click
            $(document).on('click', '.quick-view-modal', function(e) {
                if ($(e.target).hasClass('quick-view-modal')) {
                    closeQuickView();
                }
            });
            
            // Close modal on ESC key press
            $(document).on('keyup', function(e) {
                if (e.key === 'Escape') {
                    closeQuickView();
                }
            });
            
            // Function to close quick view modal
            function closeQuickView() {
                modal.removeClass('open');
                $('body').removeClass('quick-view-open');
                
                // Clear content after animation
                setTimeout(function() {
                    content.empty();
                }, 300);
            }
            
            // Function to initialize quantity buttons
            function initQuantityButtons() {
                // Add quantity buttons if they don't exist
                if (!$('.quick-view-content .quantity-button').length) {
                    $('.quick-view-content .quantity input.qty').before('<button type="button" class="quantity-button minus">-</button>');
                    $('.quick-view-content .quantity input.qty').after('<button type="button" class="quantity-button plus">+</button>');
                }
                
                // Quantity button click handlers
                $('.quick-view-content .quantity-button.plus').on('click', function() {
                    const input = $(this).prev('input.qty');
                    const val = parseFloat(input.val());
                    const max = parseFloat(input.attr('max'));
                    
                    if (max && val >= max) {
                        input.val(max);
                    } else {
                        input.val(val + 1);
                    }
                    
                    input.trigger('change');
                });
                
                $('.quick-view-content .quantity-button.minus').on('click', function() {
                    const input = $(this).next('input.qty');
                    const val = parseFloat(input.val());
                    const min = parseFloat(input.attr('min'));
                    
                    if (min && val <= min) {
                        input.val(min);
                    } else if (val > 1) {
                        input.val(val - 1);
                    }
                    
                    input.trigger('change');
                });
            }
        });
        
        // Add to cart from quick view
        $(document).on('submit', '.quick-view-content .cart', function(e) {
            e.preventDefault();
            
            const form = $(this);
            const button = form.find('button[type="submit"]');
            const formData = form.serialize();
            
            // Show loading state
            button.addClass('loading').prop('disabled', true);
            
            // Add to cart via AJAX
            $.ajax({
                url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.error && response.product_url) {
                        window.location = response.product_url;
                        return;
                    }
                    
                    // Show success message
                    const modal = $('#quick-view-modal');
                    const content = modal.find('.quick-view-content');
                    
                    content.html('<div class="quick-view-success"><p>' + wc_add_to_cart_params.i18n_added_to_cart + '</p><div class="quick-view-buttons"><a href="' + wc_add_to_cart_params.cart_url + '" class="button view-cart">' + wc_add_to_cart_params.i18n_view_cart + '</a><a href="#" class="button continue-shopping">' + wc_add_to_cart_params.i18n_continue_shopping + '</a></div></div>');
                    
                    // Continue shopping button click
                    $('.continue-shopping').on('click', function(e) {
                        e.preventDefault();
                        closeQuickView();
                    });
                    
                    // Update cart fragments
                    if (response.fragments) {
                        $.each(response.fragments, function(key, value) {
                            $(key).replaceWith(value);
                        });
                    }
                    
                    // Trigger added_to_cart event
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, button]);
                },
                error: function() {
                    button.removeClass('loading').prop('disabled', false);
                    alert(wc_add_to_cart_params.i18n_ajax_error);
                }
            });
        });
        
        // Function to close quick view modal
        function closeQuickView() {
            const modal = $('#quick-view-modal');
            modal.removeClass('open');
            $('body').removeClass('quick-view-open');
            
            // Clear content after animation
            setTimeout(function() {
                modal.find('.quick-view-content').empty();
            }, 300);
        }
    });

})(jQuery);