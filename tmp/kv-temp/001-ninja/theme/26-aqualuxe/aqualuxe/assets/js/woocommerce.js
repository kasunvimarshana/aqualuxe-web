/**
 * File woocommerce.js.
 *
 * WooCommerce specific JavaScript functionality.
 */
(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        // Update cart count when products are added to cart via AJAX
        $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
            updateCartCount(fragments);
        });

        // Update cart count when cart is updated
        $(document.body).on('wc_fragments_loaded wc_fragments_refreshed', function(event, fragments) {
            updateCartCount(fragments);
        });

        // Function to update cart count
        function updateCartCount(fragments) {
            if (fragments && fragments['div.widget_shopping_cart_content']) {
                const cartCount = $('.cart-count');
                
                // Get cart count from fragments
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = fragments['div.widget_shopping_cart_content'];
                const count = $(tempDiv).find('.quantity').length;
                
                // Update cart count
                cartCount.text(count);
                
                // Add animation
                cartCount.addClass('pulse');
                setTimeout(function() {
                    cartCount.removeClass('pulse');
                }, 1000);
            }
        }

        // Product quantity buttons
        $('.quantity').each(function() {
            const spinner = $(this);
            const input = spinner.find('input[type="number"]');
            const btnUp = $('<button type="button" class="quantity-up">+</button>');
            const btnDown = $('<button type="button" class="quantity-down">-</button>');
            
            spinner.append(btnUp).prepend(btnDown);
            
            btnUp.on('click', function() {
                const oldValue = parseFloat(input.val());
                const max = parseFloat(input.attr('max'));
                let newVal;
                
                if (oldValue >= max && max !== '') {
                    newVal = max;
                } else {
                    newVal = oldValue + 1;
                }
                
                spinner.find('input').val(newVal);
                spinner.find('input').trigger('change');
            });
            
            btnDown.on('click', function() {
                const oldValue = parseFloat(input.val());
                const min = parseFloat(input.attr('min'));
                let newVal;
                
                if (oldValue <= min && min !== '') {
                    newVal = min;
                } else {
                    newVal = oldValue - 1;
                }
                
                spinner.find('input').val(newVal);
                spinner.find('input').trigger('change');
            });
        });

        // Product gallery zoom
        if ($.fn.zoom && $('body').hasClass('single-product')) {
            $('.woocommerce-product-gallery__image').each(function() {
                $(this).zoom({
                    url: $(this).find('img').attr('data-large_image'),
                    touch: false
                });
            });
        }

        // Quick view functionality
        $('.quick-view').on('click', function(e) {
            e.preventDefault();
            
            const productId = $(this).data('product-id');
            const modal = $('<div class="quick-view-modal"></div>');
            const modalContent = $('<div class="quick-view-content"></div>');
            const loader = $('<div class="quick-view-loader"><div class="spinner"></div></div>');
            
            // Add modal to page
            modal.append(modalContent);
            $('body').append(modal);
            modalContent.append(loader);
            
            // Show modal
            setTimeout(function() {
                modal.addClass('open');
            }, 50);
            
            // Get product data
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
                        loader.remove();
                        modalContent.html(response.data.content);
                        
                        // Initialize product gallery
                        if ($.fn.flexslider) {
                            modalContent.find('.woocommerce-product-gallery').flexslider({
                                animation: 'slide',
                                controlNav: 'thumbnails'
                            });
                        }
                        
                        // Initialize product zoom
                        if ($.fn.zoom) {
                            modalContent.find('.woocommerce-product-gallery__image').each(function() {
                                $(this).zoom({
                                    url: $(this).find('img').attr('data-large_image'),
                                    touch: false
                                });
                            });
                        }
                        
                        // Initialize variation form
                        if ($.fn.wc_variation_form) {
                            modalContent.find('.variations_form').wc_variation_form();
                        }
                        
                        // Add close button
                        const closeButton = $('<button class="quick-view-close" aria-label="Close">&times;</button>');
                        modalContent.append(closeButton);
                        
                        // Close modal on button click
                        closeButton.on('click', function() {
                            modal.removeClass('open');
                            setTimeout(function() {
                                modal.remove();
                            }, 300);
                        });
                        
                        // Close modal on ESC key
                        $(document).on('keyup', function(e) {
                            if (e.key === 'Escape') {
                                modal.removeClass('open');
                                setTimeout(function() {
                                    modal.remove();
                                }, 300);
                            }
                        });
                        
                        // Close modal on outside click
                        modal.on('click', function(e) {
                            if ($(e.target).is(modal)) {
                                modal.removeClass('open');
                                setTimeout(function() {
                                    modal.remove();
                                }, 300);
                            }
                        });
                    }
                },
                error: function() {
                    loader.remove();
                    modalContent.html('<p>Error loading product data. Please try again.</p>');
                }
            });
        });

        // Wishlist functionality
        $('.add-to-wishlist').on('click', function(e) {
            e.preventDefault();
            
            const button = $(this);
            const productId = button.data('product-id');
            const isInWishlist = button.hasClass('in-wishlist');
            const action = isInWishlist ? 'remove' : 'add';
            
            $.ajax({
                url: aqualuxeWishlist.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_wishlist',
                    product_id: productId,
                    action_type: action,
                    nonce: aqualuxeWishlist.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Update button state
                        if (action === 'add') {
                            button.addClass('in-wishlist');
                            button.html('<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" /></svg>');
                        } else {
                            button.removeClass('in-wishlist');
                            button.html('<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>');
                        }
                        
                        // Update wishlist count
                        const wishlistCount = $('.wishlist-count');
                        wishlistCount.text(response.data.wishlist.length);
                        
                        // Show notification
                        const notification = $('<div class="aqualuxe-notification"></div>');
                        notification.text(response.data.message);
                        $('body').append(notification);
                        
                        setTimeout(function() {
                            notification.addClass('show');
                        }, 50);
                        
                        setTimeout(function() {
                            notification.removeClass('show');
                            setTimeout(function() {
                                notification.remove();
                            }, 300);
                        }, 3000);
                    }
                }
            });
        });

        // Product filter functionality
        $('.aqualuxe-filter-widget .filter-title').on('click', function() {
            $(this).toggleClass('active');
            $(this).next('.filter-content').slideToggle(300);
        });

        // Price range slider
        if ($.fn.slider && $('.price-range-slider').length) {
            $('.price-range-slider').each(function() {
                const slider = $(this);
                const minInput = slider.siblings('.price-range-min');
                const maxInput = slider.siblings('.price-range-max');
                const min = parseFloat(slider.data('min'));
                const max = parseFloat(slider.data('max'));
                const currentMin = parseFloat(minInput.val()) || min;
                const currentMax = parseFloat(maxInput.val()) || max;
                
                slider.slider({
                    range: true,
                    min: min,
                    max: max,
                    values: [currentMin, currentMax],
                    slide: function(event, ui) {
                        minInput.val(ui.values[0]);
                        maxInput.val(ui.values[1]);
                        
                        slider.siblings('.price-range-display').html(
                            '<span class="from">' + formatPrice(ui.values[0]) + '</span> - ' +
                            '<span class="to">' + formatPrice(ui.values[1]) + '</span>'
                        );
                    }
                });
                
                // Initialize display
                slider.siblings('.price-range-display').html(
                    '<span class="from">' + formatPrice(currentMin) + '</span> - ' +
                    '<span class="to">' + formatPrice(currentMax) + '</span>'
                );
                
                // Update slider when inputs change
                minInput.on('change', function() {
                    const value = parseFloat($(this).val()) || min;
                    slider.slider('values', 0, value);
                    
                    slider.siblings('.price-range-display').html(
                        '<span class="from">' + formatPrice(value) + '</span> - ' +
                        '<span class="to">' + formatPrice(slider.slider('values', 1)) + '</span>'
                    );
                });
                
                maxInput.on('change', function() {
                    const value = parseFloat($(this).val()) || max;
                    slider.slider('values', 1, value);
                    
                    slider.siblings('.price-range-display').html(
                        '<span class="from">' + formatPrice(slider.slider('values', 0)) + '</span> - ' +
                        '<span class="to">' + formatPrice(value) + '</span>'
                    );
                });
                
                // Format price with currency symbol
                function formatPrice(price) {
                    return woocommerce_params.currency_symbol + price.toFixed(2);
                }
            });
        }

        // Ajax product filtering
        $('.aqualuxe-filter-form').on('submit', function(e) {
            e.preventDefault();
            
            const form = $(this);
            const productsContainer = $('.products');
            const loadingOverlay = $('<div class="aqualuxe-loading-overlay"><div class="spinner"></div></div>');
            
            // Add loading overlay
            productsContainer.append(loadingOverlay);
            
            // Get form data
            const formData = form.serialize();
            
            // Ajax request
            $.ajax({
                url: woocommerce_params.ajax_url,
                type: 'GET',
                data: formData + '&action=aqualuxe_filter_products',
                success: function(response) {
                    // Replace products
                    productsContainer.html(response);
                    
                    // Scroll to top of products
                    $('html, body').animate({
                        scrollTop: productsContainer.offset().top - 100
                    }, 500);
                    
                    // Update URL
                    const newUrl = form.attr('action') + '?' + formData;
                    window.history.pushState({}, '', newUrl);
                },
                error: function() {
                    loadingOverlay.remove();
                    alert('Error filtering products. Please try again.');
                }
            });
        });
    });

})(jQuery);