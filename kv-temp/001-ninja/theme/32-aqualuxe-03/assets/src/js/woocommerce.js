/**
 * AquaLuxe Theme - WooCommerce
 *
 * Handles WooCommerce specific functionality.
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Check if WooCommerce is active
        if (!$('body').hasClass('woocommerce')) {
            return;
        }

        // Product gallery
        initProductGallery();

        // Quantity buttons
        initQuantityButtons();

        // AJAX add to cart
        initAjaxAddToCart();

        // Quick view
        initQuickView();

        // Mini cart
        initMiniCart();

        // Product tabs
        initProductTabs();

        // Product variations
        initProductVariations();

        // Product reviews
        initProductReviews();

        // Initialize product gallery
        function initProductGallery() {
            // Check if product gallery exists
            if (!$('.woocommerce-product-gallery').length) {
                return;
            }

            // Initialize Swiper if available
            if (typeof Swiper !== 'undefined') {
                // Main product gallery
                const galleryThumbs = new Swiper('.gallery-thumbs', {
                    spaceBetween: 10,
                    slidesPerView: 4,
                    freeMode: true,
                    watchSlidesVisibility: true,
                    watchSlidesProgress: true,
                    breakpoints: {
                        576: {
                            slidesPerView: 5,
                        },
                        768: {
                            slidesPerView: 4,
                        },
                        992: {
                            slidesPerView: 5,
                        },
                    }
                });

                const galleryMain = new Swiper('.gallery-main', {
                    spaceBetween: 0,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    thumbs: {
                        swiper: galleryThumbs
                    },
                    zoom: {
                        maxRatio: 3,
                    },
                });

                // Enable zoom on hover
                $('.woocommerce-product-gallery__image').zoom({
                    url: function() {
                        return $(this).find('img').attr('data-large_image');
                    },
                    touch: false
                });

                // Update gallery on variation change
                $('form.variations_form').on('show_variation', function(event, variation) {
                    if (variation.image && variation.image.src) {
                        const variationImageIndex = $('.woocommerce-product-gallery__image').findIndex(function() {
                            return $(this).find('img').attr('src') === variation.image.src;
                        });

                        if (variationImageIndex >= 0) {
                            galleryMain.slideTo(variationImageIndex);
                        }
                    }
                });
            }
        }

        // Initialize quantity buttons
        function initQuantityButtons() {
            // Add quantity buttons if they don't exist
            if ($('.quantity').length && !$('.quantity-button').length) {
                $('.quantity').each(function() {
                    const $quantity = $(this);
                    const $input = $quantity.find('input[type="number"]');
                    
                    // Add decrease button
                    $('<button type="button" class="quantity-button quantity-down" aria-label="Decrease quantity">-</button>').insertBefore($input);
                    
                    // Add increase button
                    $('<button type="button" class="quantity-button quantity-up" aria-label="Increase quantity">+</button>').insertAfter($input);
                });
            }

            // Handle quantity button clicks
            $(document).on('click', '.quantity-button', function() {
                const $button = $(this);
                const $input = $button.parent().find('input[type="number"]');
                const step = parseFloat($input.attr('step')) || 1;
                const min = parseFloat($input.attr('min')) || 0;
                const max = parseFloat($input.attr('max')) || '';
                let value = parseFloat($input.val()) || 0;
                
                // Increase or decrease value
                if ($button.hasClass('quantity-up')) {
                    value += step;
                } else {
                    value -= step;
                }
                
                // Ensure value is within min/max
                if (min && value < min) {
                    value = min;
                }
                
                if (max && value > max) {
                    value = max;
                }
                
                // Update input value
                $input.val(value).trigger('change');
            });
        }

        // Initialize AJAX add to cart
        function initAjaxAddToCart() {
            // Single product add to cart
            $(document).on('click', '.single_add_to_cart_button:not(.disabled)', function(e) {
                const $button = $(this);
                const $form = $button.closest('form.cart');
                
                // Check if AJAX add to cart is enabled
                if ($('body').hasClass('ajax-add-to-cart-enabled') && $form.length) {
                    e.preventDefault();
                    
                    // Prevent multiple clicks
                    if ($button.hasClass('loading')) {
                        return;
                    }
                    
                    // Add loading state
                    $button.addClass('loading');
                    
                    // Get form data
                    const formData = new FormData($form[0]);
                    formData.append('action', 'aqualuxe_ajax_add_to_cart');
                    formData.append('nonce', aqualuxeSettings.nonce);
                    
                    // AJAX call to add product to cart
                    $.ajax({
                        url: aqualuxeSettings.ajaxUrl,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            // Remove loading state
                            $button.removeClass('loading');
                            
                            if (response.success) {
                                // Update cart fragments
                                if (response.data.fragments) {
                                    $.each(response.data.fragments, function(key, value) {
                                        $(key).replaceWith(value);
                                    });
                                }
                                
                                // Update cart count
                                if (response.data.cart_count) {
                                    $('.cart-count').text(response.data.cart_count).removeClass('hidden');
                                }
                                
                                // Show success message
                                showNotification(response.data.message, 'success');
                                
                                // Open mini cart
                                if (response.data.open_mini_cart) {
                                    setTimeout(function() {
                                        $('.mini-cart-toggle').trigger('click');
                                    }, 500);
                                }
                            } else {
                                // Show error message
                                showNotification(response.data.message, 'error');
                            }
                        },
                        error: function() {
                            // Remove loading state
                            $button.removeClass('loading');
                            
                            // Show error message
                            showNotification('Failed to add product to cart. Please try again.', 'error');
                        }
                    });
                }
            });

            // Archive page add to cart
            $(document).on('click', '.ajax_add_to_cart', function(e) {
                const $button = $(this);
                
                // Check if AJAX add to cart is enabled
                if ($('body').hasClass('ajax-add-to-cart-enabled')) {
                    // Add loading state
                    $button.addClass('loading');
                }
            });

            // Handle added_to_cart event
            $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
                // Remove loading state
                $button.removeClass('loading');
                
                // Show success message
                showNotification('Product added to cart.', 'success');
            });
        }

        // Initialize quick view
        function initQuickView() {
            // Quick view button click
            $(document).on('click', '.quick-view-button', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const productId = $button.data('product-id');
                
                // Prevent multiple clicks
                if ($button.hasClass('loading')) {
                    return;
                }
                
                // Add loading state
                $button.addClass('loading');
                
                // AJAX call to get quick view content
                $.ajax({
                    url: aqualuxeSettings.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_quick_view',
                        product_id: productId,
                        nonce: aqualuxeSettings.nonce
                    },
                    success: function(response) {
                        // Remove loading state
                        $button.removeClass('loading');
                        
                        if (response.success) {
                            // Append modal to body
                            $('body').append(response.data.html);
                            
                            // Show modal
                            $('.quick-view-modal').addClass('active');
                            $('body').addClass('modal-open');
                            
                            // Initialize product gallery in quick view
                            initQuickViewGallery();
                            
                            // Initialize quantity buttons in quick view
                            initQuantityButtons();
                            
                            // Initialize variations in quick view
                            if (typeof $.fn.wc_variation_form !== 'undefined') {
                                $('.quick-view-modal .variations_form').wc_variation_form();
                            }
                        } else {
                            // Show error message
                            showNotification(response.data.message, 'error');
                        }
                    },
                    error: function() {
                        // Remove loading state
                        $button.removeClass('loading');
                        
                        // Show error message
                        showNotification('Failed to load quick view. Please try again.', 'error');
                    }
                });
            });

            // Close quick view modal
            $(document).on('click', '.quick-view-modal .modal-close, .quick-view-modal .modal-overlay', function() {
                $('.quick-view-modal').removeClass('active');
                $('body').removeClass('modal-open');
                
                // Remove modal after animation
                setTimeout(function() {
                    $('.quick-view-modal').remove();
                }, 300);
            });

            // Initialize quick view gallery
            function initQuickViewGallery() {
                // Check if quick view gallery exists
                if (!$('.quick-view-gallery').length) {
                    return;
                }

                // Initialize Swiper if available
                if (typeof Swiper !== 'undefined') {
                    new Swiper('.quick-view-gallery', {
                        slidesPerView: 1,
                        spaceBetween: 0,
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true,
                        },
                    });
                }
            }
        }

        // Initialize mini cart
        function initMiniCart() {
            // Mini cart toggle
            $('.mini-cart-toggle').on('click', function(e) {
                e.preventDefault();
                
                $('.mini-cart').toggleClass('active');
                
                if ($('.mini-cart').hasClass('active')) {
                    $(this).attr('aria-expanded', 'true');
                } else {
                    $(this).attr('aria-expanded', 'false');
                }
            });

            // Close mini cart when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.mini-cart, .mini-cart-toggle').length && $('.mini-cart').hasClass('active')) {
                    $('.mini-cart').removeClass('active');
                    $('.mini-cart-toggle').attr('aria-expanded', 'false');
                }
            });

            // Remove item from mini cart
            $(document).on('click', '.mini-cart-remove', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const cartItemKey = $button.data('cart-item-key');
                
                // Prevent multiple clicks
                if ($button.hasClass('loading')) {
                    return;
                }
                
                // Add loading state
                $button.addClass('loading');
                
                // AJAX call to remove item from cart
                $.ajax({
                    url: aqualuxeSettings.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_remove_from_cart',
                        cart_item_key: cartItemKey,
                        nonce: aqualuxeSettings.nonce
                    },
                    success: function(response) {
                        // Remove loading state
                        $button.removeClass('loading');
                        
                        if (response.success) {
                            // Update cart fragments
                            if (response.data.fragments) {
                                $.each(response.data.fragments, function(key, value) {
                                    $(key).replaceWith(value);
                                });
                            }
                            
                            // Update cart count
                            if (response.data.cart_count) {
                                $('.cart-count').text(response.data.cart_count);
                                
                                if (response.data.cart_count > 0) {
                                    $('.cart-count').removeClass('hidden');
                                } else {
                                    $('.cart-count').addClass('hidden');
                                }
                            }
                            
                            // Show success message
                            showNotification(response.data.message, 'success');
                        } else {
                            // Show error message
                            showNotification(response.data.message, 'error');
                        }
                    },
                    error: function() {
                        // Remove loading state
                        $button.removeClass('loading');
                        
                        // Show error message
                        showNotification('Failed to remove item from cart. Please try again.', 'error');
                    }
                });
            });
        }

        // Initialize product tabs
        function initProductTabs() {
            // Check if product tabs exist
            if (!$('.woocommerce-tabs').length) {
                return;
            }

            // Handle tab click
            $('.wc-tabs li a').on('click', function(e) {
                e.preventDefault();
                
                const $tab = $(this);
                const tabId = $tab.attr('href');
                
                // Remove active class from all tabs
                $('.wc-tabs li').removeClass('active');
                $('.woocommerce-Tabs-panel').hide();
                
                // Add active class to current tab
                $tab.parent().addClass('active');
                $(tabId).show();
                
                // Update URL hash
                if (history.pushState) {
                    history.pushState(null, null, tabId);
                }
            });

            // Open tab from URL hash
            if (window.location.hash) {
                const hash = window.location.hash;
                const $tab = $('.wc-tabs li a[href="' + hash + '"]');
                
                if ($tab.length) {
                    $tab.trigger('click');
                }
            }
        }

        // Initialize product variations
        function initProductVariations() {
            // Check if variations form exists
            if (!$('.variations_form').length) {
                return;
            }

            // Handle variation change
            $('.variations_form').on('show_variation', function(event, variation) {
                // Update price
                if (variation.price_html) {
                    $('.product-price').html(variation.price_html);
                }
                
                // Update availability
                if (variation.availability_html) {
                    $('.product-availability').html(variation.availability_html);
                }
                
                // Update variation ID
                $('.variation_id').val(variation.variation_id);
                
                // Enable add to cart button
                $('.single_add_to_cart_button').removeClass('disabled');
            });

            // Handle variation hide
            $('.variations_form').on('hide_variation', function() {
                // Disable add to cart button
                $('.single_add_to_cart_button').addClass('disabled');
            });

            // Initialize select2 for variation selects if available
            if (typeof $.fn.select2 !== 'undefined') {
                $('.variations select').select2({
                    minimumResultsForSearch: 10,
                    dropdownCssClass: 'variation-dropdown'
                });
            }
        }

        // Initialize product reviews
        function initProductReviews() {
            // Check if review form exists
            if (!$('#review_form').length) {
                return;
            }

            // Handle rating selection
            $('.comment-form-rating .stars a').on('click', function(e) {
                e.preventDefault();
                
                const $star = $(this);
                const rating = $star.text();
                
                // Remove active class from all stars
                $('.comment-form-rating .stars a').removeClass('active');
                
                // Add active class to selected star and all stars before it
                $star.addClass('active').prevAll().addClass('active');
                
                // Update rating input
                $('#rating').val(rating);
            });

            // Validate review form before submission
            $('#review_form').on('submit', function(e) {
                const $form = $(this);
                const rating = $('#rating').val();
                const comment = $('#comment').val();
                
                // Check if rating is selected
                if (!rating || rating === '') {
                    e.preventDefault();
                    
                    // Show error message
                    showNotification('Please select a rating.', 'error');
                    
                    // Focus on rating
                    $('.comment-form-rating .stars').focus();
                    
                    return false;
                }
                
                // Check if comment is entered
                if (!comment || comment === '') {
                    e.preventDefault();
                    
                    // Show error message
                    showNotification('Please enter a review.', 'error');
                    
                    // Focus on comment
                    $('#comment').focus();
                    
                    return false;
                }
                
                return true;
            });
        }

        // Show notification
        function showNotification(message, type) {
            // Remove existing notifications
            $('.aqualuxe-notification').remove();
            
            // Create notification
            const $notification = $(`
                <div class="aqualuxe-notification ${type}">
                    <div class="notification-content">
                        <span class="notification-message">${message}</span>
                        <button class="notification-close" aria-label="Close notification">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            `);
            
            // Append to body
            $('body').append($notification);
            
            // Show notification
            setTimeout(function() {
                $notification.addClass('show');
            }, 10);
            
            // Hide notification after 3 seconds
            setTimeout(function() {
                $notification.removeClass('show');
                
                // Remove notification after animation
                setTimeout(function() {
                    $notification.remove();
                }, 300);
            }, 3000);
            
            // Close notification on click
            $notification.on('click', '.notification-close', function() {
                $notification.removeClass('show');
                
                // Remove notification after animation
                setTimeout(function() {
                    $notification.remove();
                }, 300);
            });
        }
    });

})(jQuery);