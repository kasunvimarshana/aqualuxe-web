/**
 * AquaLuxe WooCommerce JavaScript Enhancements
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

(function($) {
    'use strict';

    const AquaLuxeWooCommerce = {

        /**
         * Initialize WooCommerce features
         */
        init: function() {
            this.initAjaxAddToCart();
            this.initProductGallery();
            this.initQuantityButtons();
            this.initCartFragments();
            this.initCheckoutEnhancements();
            this.initProductFilters();
            this.initWishlist();
        },

        /**
         * Initialize AJAX Add to Cart
         */
        initAjaxAddToCart: function() {
            $(document).on('click', '.ajax_add_to_cart', function(e) {
                e.preventDefault();
                
                const button = $(this);
                const productId = button.data('product_id');
                const quantity = button.data('quantity') || 1;
                
                button.addClass('loading');
                
                $.ajax({
                    url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
                    type: 'POST',
                    data: {
                        product_id: productId,
                        quantity: quantity
                    },
                    success: function(response) {
                        if (response.error) {
                            AquaLuxeWooCommerce.showNotification(response.product_title + ' could not be added to cart.', 'error');
                        } else {
                            AquaLuxeWooCommerce.showNotification(response.product_title + ' added to cart!', 'success');
                            
                            // Update cart fragments
                            $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, button]);
                        }
                    },
                    complete: function() {
                        button.removeClass('loading');
                    }
                });
            });
        },

        /**
         * Initialize product gallery enhancements
         */
        initProductGallery: function() {
            // Add image zoom functionality
            $('.woocommerce-product-gallery__image img').each(function() {
                $(this).wrap('<div class="image-zoom-wrapper"></div>');
            });

            // Thumbnail navigation
            $('.flex-control-thumbs li').on('click', function() {
                $(this).addClass('flex-active').siblings().removeClass('flex-active');
            });
        },

        /**
         * Initialize quantity buttons
         */
        initQuantityButtons: function() {
            // Add plus/minus buttons to quantity inputs
            $('input[type="number"].qty').each(function() {
                const input = $(this);
                const wrapper = $('<div class="quantity-wrapper"></div>');
                
                input.wrap(wrapper);
                input.before('<button type="button" class="quantity-minus">-</button>');
                input.after('<button type="button" class="quantity-plus">+</button>');
            });

            // Handle quantity changes
            $(document).on('click', '.quantity-plus', function(e) {
                e.preventDefault();
                const input = $(this).siblings('.qty');
                const currentVal = parseInt(input.val());
                const max = parseInt(input.attr('max'));
                
                if (max && currentVal >= max) {
                    return;
                }
                
                input.val(currentVal + 1).trigger('change');
            });

            $(document).on('click', '.quantity-minus', function(e) {
                e.preventDefault();
                const input = $(this).siblings('.qty');
                const currentVal = parseInt(input.val());
                const min = parseInt(input.attr('min'));
                
                if (currentVal <= (min || 1)) {
                    return;
                }
                
                input.val(currentVal - 1).trigger('change');
            });
        },

        /**
         * Initialize cart fragments
         */
        initCartFragments: function() {
            $(document.body).on('added_to_cart', function(event, fragments, cart_hash) {
                // Update cart count in header
                $('.cart-contents-count').text(fragments['div.widget_shopping_cart_content'].match(/(\d+)/)[0]);
                
                // Show cart notification
                AquaLuxeWooCommerce.showCartNotification();
            });
        },

        /**
         * Initialize checkout enhancements
         */
        initCheckoutEnhancements: function() {
            // Auto-fill city based on postcode
            $('input[name="billing_postcode"]').on('change', function() {
                // Implementation for auto-fill based on postal code
            });

            // Validate checkout form in real-time
            $('.checkout input, .checkout select').on('blur', function() {
                const field = $(this);
                AquaLuxeWooCommerce.validateField(field);
            });

            // Show/hide payment method details
            $('input[name="payment_method"]').on('change', function() {
                $('.payment_box').slideUp();
                $('div.payment_method_' + $(this).val()).slideDown();
            });
        },

        /**
         * Initialize product filters
         */
        initProductFilters: function() {
            // Price range slider
            if ($('.price-slider').length) {
                $('.price-slider').each(function() {
                    const slider = $(this);
                    const min = parseInt(slider.data('min'));
                    const max = parseInt(slider.data('max'));
                    
                    slider.slider({
                        range: true,
                        min: min,
                        max: max,
                        values: [min, max],
                        slide: function(event, ui) {
                            $('.price-range-min').text('$' + ui.values[0]);
                            $('.price-range-max').text('$' + ui.values[1]);
                        },
                        stop: function(event, ui) {
                            AquaLuxeWooCommerce.filterProducts();
                        }
                    });
                });
            }

            // Category filters
            $('.product-categories input[type="checkbox"]').on('change', function() {
                AquaLuxeWooCommerce.filterProducts();
            });

            // Sort by dropdown
            $('.orderby').on('change', function() {
                $(this).closest('form').submit();
            });
        },

        /**
         * Filter products
         */
        filterProducts: function() {
            const form = $('.woocommerce-ordering, .widget_product_categories').closest('form');
            
            if (form.length) {
                $.ajax({
                    url: form.attr('action'),
                    type: 'GET',
                    data: form.serialize(),
                    beforeSend: function() {
                        $('.products').addClass('loading');
                    },
                    success: function(response) {
                        const newProducts = $(response).find('.products').html();
                        $('.products').html(newProducts);
                        
                        // Re-initialize product features
                        AquaLuxeWooCommerce.initAjaxAddToCart();
                    },
                    complete: function() {
                        $('.products').removeClass('loading');
                    }
                });
            }
        },

        /**
         * Initialize wishlist functionality
         */
        initWishlist: function() {
            // Add wishlist buttons to products
            $('.woocommerce ul.products li.product').each(function() {
                if (!$(this).find('.add-to-wishlist').length) {
                    $(this).find('.button').after('<button class="add-to-wishlist" title="Add to Wishlist"><i class="fas fa-heart"></i></button>');
                }
            });

            // Handle wishlist actions
            $(document).on('click', '.add-to-wishlist', function(e) {
                e.preventDefault();
                
                const button = $(this);
                const productId = button.closest('.product').find('[data-product_id]').data('product_id');
                
                button.toggleClass('active');
                
                if (button.hasClass('active')) {
                    AquaLuxeWooCommerce.addToWishlist(productId);
                } else {
                    AquaLuxeWooCommerce.removeFromWishlist(productId);
                }
            });
        },

        /**
         * Add product to wishlist
         */
        addToWishlist: function(productId) {
            let wishlist = JSON.parse(localStorage.getItem('aqualuxe_wishlist') || '[]');
            
            if (wishlist.indexOf(productId) === -1) {
                wishlist.push(productId);
                localStorage.setItem('aqualuxe_wishlist', JSON.stringify(wishlist));
                AquaLuxeWooCommerce.showNotification('Added to wishlist!', 'success');
            }
        },

        /**
         * Remove product from wishlist
         */
        removeFromWishlist: function(productId) {
            let wishlist = JSON.parse(localStorage.getItem('aqualuxe_wishlist') || '[]');
            const index = wishlist.indexOf(productId);
            
            if (index > -1) {
                wishlist.splice(index, 1);
                localStorage.setItem('aqualuxe_wishlist', JSON.stringify(wishlist));
                AquaLuxeWooCommerce.showNotification('Removed from wishlist!', 'info');
            }
        },

        /**
         * Validate form field
         */
        validateField: function(field) {
            const value = field.val();
            const fieldName = field.attr('name');
            let isValid = true;
            let errorMessage = '';

            // Email validation
            if (fieldName.includes('email')) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    isValid = false;
                    errorMessage = 'Please enter a valid email address.';
                }
            }

            // Phone validation
            if (fieldName.includes('phone')) {
                const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
                if (!phoneRegex.test(value.replace(/\s/g, ''))) {
                    isValid = false;
                    errorMessage = 'Please enter a valid phone number.';
                }
            }

            // Required field validation
            if (field.attr('required') && !value.trim()) {
                isValid = false;
                errorMessage = 'This field is required.';
            }

            // Show/hide validation messages
            field.removeClass('error valid');
            field.next('.field-error').remove();

            if (!isValid) {
                field.addClass('error');
                field.after('<span class="field-error">' + errorMessage + '</span>');
            } else if (value.trim()) {
                field.addClass('valid');
            }

            return isValid;
        },

        /**
         * Show notification
         */
        showNotification: function(message, type = 'info') {
            const notification = $('<div class="aqualuxe-notification aqualuxe-notification-' + type + '">' + message + '</div>');
            
            $('body').append(notification);
            
            setTimeout(function() {
                notification.addClass('show');
            }, 100);

            setTimeout(function() {
                notification.removeClass('show');
                setTimeout(function() {
                    notification.remove();
                }, 300);
            }, 3000);
        },

        /**
         * Show cart notification
         */
        showCartNotification: function() {
            if (!$('.cart-notification').length) {
                const cartNotification = $(`
                    <div class="cart-notification">
                        <div class="cart-notification-content">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Product added to cart!</span>
                            <a href="${wc_add_to_cart_params.cart_url}" class="view-cart">View Cart</a>
                        </div>
                    </div>
                `);
                
                $('body').append(cartNotification);
                
                setTimeout(function() {
                    cartNotification.addClass('show');
                }, 100);

                setTimeout(function() {
                    cartNotification.removeClass('show');
                    setTimeout(function() {
                        cartNotification.remove();
                    }, 300);
                }, 4000);
            }
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        AquaLuxeWooCommerce.init();
    });

    // Make available globally
    window.AquaLuxeWooCommerce = AquaLuxeWooCommerce;

})(jQuery);