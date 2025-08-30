/**
 * WooCommerce specific JavaScript for AquaLuxe theme
 *
 * This file handles all the WooCommerce specific interactive functionality.
 */

(function($) {
    'use strict';

    // Document Ready
    $(document).ready(function() {
        // Initialize WooCommerce components
        initializeWooCommerce();
    });

    /**
     * Initialize all WooCommerce components
     */
    function initializeWooCommerce() {
        // Initialize product gallery
        initializeProductGallery();
        
        // Initialize quantity inputs
        initializeQuantityInputs();
        
        // Initialize quick view
        initializeQuickView();
        
        // Initialize ajax add to cart
        initializeAjaxAddToCart();
        
        // Initialize product filters
        initializeProductFilters();
        
        // Initialize product tabs
        initializeProductTabs();
        
        // Initialize wishlist
        initializeWishlist();
        
        // Initialize compare
        initializeCompare();
        
        // Initialize checkout enhancements
        initializeCheckout();
    }

    /**
     * Initialize product gallery
     */
    function initializeProductGallery() {
        // Only run if WooCommerce product gallery is present
        if (!$('.woocommerce-product-gallery').length) {
            return;
        }

        // Check if zoom is enabled
        if (typeof wc_single_product_params !== 'undefined' && wc_single_product_params.zoom_enabled === '1') {
            const zoomOptions = {
                touch: false,
                magnify: 1.5
            };

            // Apply zoom to main product image
            $('.woocommerce-product-gallery__image img').each(function() {
                const $image = $(this);
                
                if ($image.data('large_image_width') > $image.width()) {
                    $image.zoom(zoomOptions);
                }
            });
        }

        // Initialize product gallery slider
        if (typeof wc_single_product_params !== 'undefined' && wc_single_product_params.flexslider_enabled === '1') {
            // Main slider
            $('.woocommerce-product-gallery__wrapper').flexslider({
                selector: '.woocommerce-product-gallery__wrapper > .woocommerce-product-gallery__image',
                animation: 'slide',
                controlNav: false,
                animationLoop: false,
                slideshow: false,
                smoothHeight: true,
                sync: '.flex-control-thumbs'
            });

            // Thumbnail slider
            $('.flex-control-thumbs').flexslider({
                animation: 'slide',
                controlNav: false,
                animationLoop: false,
                slideshow: false,
                itemWidth: 100,
                itemMargin: 10,
                asNavFor: '.woocommerce-product-gallery__wrapper'
            });
        }

        // Initialize lightbox
        if (typeof wc_single_product_params !== 'undefined' && wc_single_product_params.photoswipe_enabled === '1') {
            $('.woocommerce-product-gallery__image > a').on('click', function(e) {
                e.preventDefault();
            });

            // Add photoswipe UI
            if (!$('.pswp').length) {
                const photoswipeTemplate = `
                    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="pswp__bg"></div>
                        <div class="pswp__scroll-wrap">
                            <div class="pswp__container">
                                <div class="pswp__item"></div>
                                <div class="pswp__item"></div>
                                <div class="pswp__item"></div>
                            </div>
                            <div class="pswp__ui pswp__ui--hidden">
                                <div class="pswp__top-bar">
                                    <div class="pswp__counter"></div>
                                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                                    <button class="pswp__button pswp__button--share" title="Share"></button>
                                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                                    <div class="pswp__preloader">
                                        <div class="pswp__preloader__icn">
                                            <div class="pswp__preloader__cut">
                                                <div class="pswp__preloader__donut"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                                    <div class="pswp__share-tooltip"></div>
                                </div>
                                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                                <div class="pswp__caption">
                                    <div class="pswp__caption__center"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                $('body').append(photoswipeTemplate);
            }

            // Initialize PhotoSwipe
            $('.woocommerce-product-gallery__image > a').on('click', function(e) {
                e.preventDefault();
                
                const $gallery = $(this).closest('.woocommerce-product-gallery');
                const $images = $gallery.find('.woocommerce-product-gallery__image > a');
                const items = [];
                
                $images.each(function() {
                    const $image = $(this);
                    const src = $image.attr('href');
                    const width = $image.data('large_image_width') || 1000;
                    const height = $image.data('large_image_height') || 1000;
                    const caption = $image.data('caption') || '';
                    
                    items.push({
                        src: src,
                        w: width,
                        h: height,
                        title: caption
                    });
                });
                
                const options = {
                    index: $images.index($(this)),
                    shareEl: false,
                    closeOnScroll: false,
                    history: false,
                    hideAnimationDuration: 0,
                    showAnimationDuration: 0
                };
                
                const gallery = new PhotoSwipe($('.pswp')[0], PhotoSwipeUI_Default, items, options);
                gallery.init();
            });
        }
    }

    /**
     * Initialize quantity inputs
     */
    function initializeQuantityInputs() {
        // Add quantity buttons
        function addQuantityButtons() {
            $('.quantity:not(.buttons-added)').addClass('buttons-added').append('<button type="button" class="plus">+</button>').prepend('<button type="button" class="minus">-</button>');
        }
        
        // Add buttons initially
        addQuantityButtons();
        
        // Add buttons when cart is updated
        $(document.body).on('updated_cart_totals', addQuantityButtons);
        
        // Handle quantity button clicks
        $(document).on('click', '.plus, .minus', function() {
            const $button = $(this);
            const $input = $button.closest('.quantity').find('input[type="number"]');
            const min = parseFloat($input.attr('min')) || 0;
            const max = parseFloat($input.attr('max'));
            const step = parseFloat($input.attr('step')) || 1;
            let value = parseFloat($input.val()) || 0;
            
            if ($button.hasClass('plus')) {
                if (max && value >= max) {
                    return;
                }
                value += step;
            } else {
                if (value <= min) {
                    return;
                }
                value -= step;
            }
            
            $input.val(value).trigger('change');
        });
    }

    /**
     * Initialize quick view
     */
    function initializeQuickView() {
        // Only run if quick view is enabled
        if (typeof aqualuxeData === 'undefined' || !aqualuxeData.quickViewEnabled) {
            return;
        }
        
        $(document).on('click', '.quick-view-button', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const productId = $button.data('product-id');
            
            if (!productId) {
                return;
            }
            
            // Show loading state
            $button.addClass('loading');
            
            // Fetch product data via AJAX
            $.ajax({
                url: aqualuxeData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_quick_view',
                    product_id: productId,
                    security: aqualuxeData.quickViewNonce
                },
                success: function(response) {
                    if (response.success) {
                        // Create modal
                        const $modal = $('<div class="quick-view-modal"><div class="quick-view-modal-inner"><div class="quick-view-content"><button class="quick-view-close">&times;</button><div class="quick-view-content-inner">' + response.data.html + '</div></div></div></div>');
                        
                        $('body').append($modal);
                        
                        // Show modal
                        setTimeout(function() {
                            $modal.addClass('active');
                            $('body').addClass('modal-open');
                        }, 100);
                        
                        // Initialize product gallery in quick view
                        initializeProductGallery();
                        
                        // Initialize quantity inputs in quick view
                        initializeQuantityInputs();
                        
                        // Initialize variations form
                        if (typeof wc_add_to_cart_variation_params !== 'undefined') {
                            $modal.find('.variations_form').wc_variation_form();
                        }
                    }
                },
                complete: function() {
                    $button.removeClass('loading');
                }
            });
        });
        
        // Close quick view modal
        $(document).on('click', '.quick-view-close, .quick-view-modal-inner', function(e) {
            if (e.target === this) {
                const $modal = $(this).closest('.quick-view-modal');
                
                $modal.removeClass('active');
                $('body').removeClass('modal-open');
                
                setTimeout(function() {
                    $modal.remove();
                }, 300);
            }
        });
    }

    /**
     * Initialize AJAX add to cart
     */
    function initializeAjaxAddToCart() {
        // Only run if AJAX add to cart is enabled
        if (typeof aqualuxeData === 'undefined' || !aqualuxeData.ajaxAddToCartEnabled) {
            return;
        }
        
        // Single product add to cart
        $(document).on('submit', 'form.cart', function(e) {
            const $form = $(this);
            
            // Skip if not a simple product or if form has variations
            if ($form.closest('.product-type-variable').length || $form.find('.variations').length) {
                return;
            }
            
            e.preventDefault();
            
            const $button = $form.find('button[type="submit"]');
            
            // Show loading state
            $button.addClass('loading');
            
            // Add to cart via AJAX
            $.ajax({
                url: aqualuxeData.ajaxUrl,
                type: 'POST',
                data: $form.serialize() + '&action=aqualuxe_ajax_add_to_cart&security=' + aqualuxeData.addToCartNonce,
                success: function(response) {
                    if (response.success) {
                        // Update cart fragments
                        if (response.data.fragments) {
                            $.each(response.data.fragments, function(key, value) {
                                $(key).replaceWith(value);
                            });
                        }
                        
                        // Show success message
                        showNotification(response.data.message, 'success');
                        
                        // Trigger event for other scripts
                        $(document.body).trigger('added_to_cart', [response.data.fragments, response.data.cart_hash, $button]);
                    } else {
                        // Show error message
                        showNotification(response.data.message, 'error');
                    }
                },
                complete: function() {
                    $button.removeClass('loading');
                }
            });
        });
        
        // Archive page add to cart
        $(document).on('click', '.add_to_cart_button:not(.product_type_variable):not(.product_type_grouped)', function(e) {
            const $button = $(this);
            
            // Skip if already has AJAX class
            if ($button.hasClass('ajax_add_to_cart')) {
                return;
            }
            
            e.preventDefault();
            
            const productId = $button.data('product_id');
            const quantity = $button.data('quantity') || 1;
            
            if (!productId) {
                return;
            }
            
            // Show loading state
            $button.addClass('loading');
            
            // Add to cart via AJAX
            $.ajax({
                url: aqualuxeData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_ajax_add_to_cart',
                    product_id: productId,
                    quantity: quantity,
                    security: aqualuxeData.addToCartNonce
                },
                success: function(response) {
                    if (response.success) {
                        // Update cart fragments
                        if (response.data.fragments) {
                            $.each(response.data.fragments, function(key, value) {
                                $(key).replaceWith(value);
                            });
                        }
                        
                        // Show success message
                        showNotification(response.data.message, 'success');
                        
                        // Add added_to_cart class
                        $button.addClass('added');
                        
                        // Trigger event for other scripts
                        $(document.body).trigger('added_to_cart', [response.data.fragments, response.data.cart_hash, $button]);
                    } else {
                        // Show error message
                        showNotification(response.data.message, 'error');
                    }
                },
                complete: function() {
                    $button.removeClass('loading');
                }
            });
        });
    }

    /**
     * Initialize product filters
     */
    function initializeProductFilters() {
        // Price range slider
        if ($.fn.slider && $('.price_slider').length) {
            const $slider = $('.price_slider');
            const $minInput = $('.price_slider_amount #min_price');
            const $maxInput = $('.price_slider_amount #max_price');
            const minPrice = parseFloat($minInput.data('min'));
            const maxPrice = parseFloat($maxInput.data('max'));
            const currentMinPrice = parseFloat($minInput.val());
            const currentMaxPrice = parseFloat($maxInput.val());
            
            $slider.slider({
                range: true,
                min: minPrice,
                max: maxPrice,
                values: [currentMinPrice, currentMaxPrice],
                slide: function(event, ui) {
                    $minInput.val(ui.values[0]);
                    $maxInput.val(ui.values[1]);
                    
                    $(document.body).trigger('price_slider_slide', [ui.values[0], ui.values[1]]);
                },
                change: function(event, ui) {
                    $(document.body).trigger('price_slider_change', [ui.values[0], ui.values[1]]);
                }
            });
        }
        
        // AJAX product filtering
        if (typeof aqualuxeData !== 'undefined' && aqualuxeData.ajaxFilteringEnabled) {
            // Filter form submission
            $(document).on('submit', '.woocommerce-widget-layered-nav-dropdown form, .widget_price_filter form', function(e) {
                e.preventDefault();
                
                const $form = $(this);
                const url = window.location.href.split('?')[0] + '?' + $form.serialize();
                
                // Show loading state
                $('body').addClass('ajax-filtering');
                
                // Fetch filtered products
                $.ajax({
                    url: url,
                    success: function(response) {
                        const $html = $(response);
                        const $products = $html.find('.products');
                        const $pagination = $html.find('.woocommerce-pagination');
                        const $count = $html.find('.woocommerce-result-count');
                        const $ordering = $html.find('.woocommerce-ordering');
                        
                        // Update products
                        $('.products').html($products.html());
                        
                        // Update pagination
                        if ($pagination.length) {
                            $('.woocommerce-pagination').html($pagination.html());
                        } else {
                            $('.woocommerce-pagination').empty();
                        }
                        
                        // Update count
                        if ($count.length) {
                            $('.woocommerce-result-count').html($count.html());
                        }
                        
                        // Update ordering
                        if ($ordering.length) {
                            $('.woocommerce-ordering').html($ordering.html());
                        }
                        
                        // Update URL
                        window.history.pushState({}, '', url);
                        
                        // Trigger event for other scripts
                        $(document.body).trigger('aqualuxe_ajax_filtered');
                    },
                    complete: function() {
                        $('body').removeClass('ajax-filtering');
                    }
                });
            });
        }
    }

    /**
     * Initialize product tabs
     */
    function initializeProductTabs() {
        $('.woocommerce-tabs').each(function() {
            const $tabs = $(this);
            const $tabLinks = $tabs.find('.wc-tabs li a');
            
            $tabLinks.on('click', function(e) {
                e.preventDefault();
                
                const $link = $(this);
                const tabId = $link.attr('href');
                
                // Update active state
                $tabLinks.parent().removeClass('active');
                $link.parent().addClass('active');
                
                // Show selected tab content
                $tabs.find('.woocommerce-Tabs-panel').hide();
                $(tabId).show();
                
                // Update URL hash
                window.history.replaceState({}, '', tabId);
            });
            
            // Show tab based on URL hash
            const hash = window.location.hash;
            if (hash && $tabs.find(`a[href="${hash}"]`).length) {
                $tabs.find(`a[href="${hash}"]`).trigger('click');
            } else {
                // Show first tab by default
                $tabLinks.first().trigger('click');
            }
        });
    }

    /**
     * Initialize wishlist functionality
     */
    function initializeWishlist() {
        // Only run if wishlist is enabled
        if (typeof aqualuxeData === 'undefined' || !aqualuxeData.wishlistEnabled) {
            return;
        }
        
        $(document).on('click', '.wishlist-button', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const productId = $button.data('product-id');
            
            if (!productId) {
                return;
            }
            
            // Show loading state
            $button.addClass('loading');
            
            // Add to wishlist via AJAX
            $.ajax({
                url: aqualuxeData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_wishlist',
                    product_id: productId,
                    security: aqualuxeData.wishlistNonce
                },
                success: function(response) {
                    if (response.success) {
                        // Update button state
                        if (response.data.in_wishlist) {
                            $button.addClass('in-wishlist');
                        } else {
                            $button.removeClass('in-wishlist');
                        }
                        
                        // Show message
                        showNotification(response.data.message, 'success');
                    } else {
                        // Show error message
                        showNotification(response.data.message, 'error');
                    }
                },
                complete: function() {
                    $button.removeClass('loading');
                }
            });
        });
    }

    /**
     * Initialize compare functionality
     */
    function initializeCompare() {
        // Only run if compare is enabled
        if (typeof aqualuxeData === 'undefined' || !aqualuxeData.compareEnabled) {
            return;
        }
        
        $(document).on('click', '.compare-button', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const productId = $button.data('product-id');
            
            if (!productId) {
                return;
            }
            
            // Show loading state
            $button.addClass('loading');
            
            // Add to compare via AJAX
            $.ajax({
                url: aqualuxeData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_compare',
                    product_id: productId,
                    security: aqualuxeData.compareNonce
                },
                success: function(response) {
                    if (response.success) {
                        // Update button state
                        if (response.data.in_compare) {
                            $button.addClass('in-compare');
                        } else {
                            $button.removeClass('in-compare');
                        }
                        
                        // Show message
                        showNotification(response.data.message, 'success');
                    } else {
                        // Show error message
                        showNotification(response.data.message, 'error');
                    }
                },
                complete: function() {
                    $button.removeClass('loading');
                }
            });
        });
    }

    /**
     * Initialize checkout enhancements
     */
    function initializeCheckout() {
        // Only run on checkout page
        if (!$('form.checkout').length) {
            return;
        }
        
        // Toggle shipping address form
        $('#ship-to-different-address-checkbox').on('change', function() {
            $('.shipping_address').toggle($(this).is(':checked'));
        }).trigger('change');
        
        // Payment method selection
        $('.wc_payment_methods input[type="radio"]').on('change', function() {
            const $selected = $(this);
            const $methods = $('.wc_payment_methods input[type="radio"]');
            
            $methods.each(function() {
                const $method = $(this);
                const $description = $method.closest('li').find('.payment_box');
                
                if ($method.is($selected)) {
                    $description.slideDown(200);
                } else {
                    $description.slideUp(200);
                }
            });
        });
        
        // Trigger change on page load
        $('.wc_payment_methods input[type="radio"]:checked').trigger('change');
    }

    /**
     * Show notification
     *
     * @param {string} message The message to display
     * @param {string} type The notification type (success, error, info, warning)
     */
    function showNotification(message, type = 'info') {
        const $notification = $(`
            <div class="aqualuxe-notification ${type}">
                <div class="notification-content">
                    <div class="notification-icon"></div>
                    <div class="notification-message">${message}</div>
                </div>
                <button class="notification-close">&times;</button>
            </div>
        `);
        
        $('body').append($notification);
        
        setTimeout(function() {
            $notification.addClass('active');
        }, 100);
        
        setTimeout(function() {
            $notification.removeClass('active');
            setTimeout(function() {
                $notification.remove();
            }, 300);
        }, 3000);
        
        $notification.find('.notification-close').on('click', function() {
            $notification.removeClass('active');
            setTimeout(function() {
                $notification.remove();
            }, 300);
        });
    }

})(jQuery);