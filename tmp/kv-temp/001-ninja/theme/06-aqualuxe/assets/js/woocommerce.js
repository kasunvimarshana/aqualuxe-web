/**
 * AquaLuxe Theme WooCommerce JavaScript
 *
 * This file contains the WooCommerce specific JavaScript functionality for the AquaLuxe theme.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * AquaLuxe WooCommerce Object
     */
    var AquaLuxeWooCommerce = {
        /**
         * Initialize
         */
        init: function() {
            // Initialize components
            this.quantityButtons();
            this.productGallery();
            this.productTabs();
            this.ajaxAddToCart();
            this.quickView();
            this.wishlist();
            this.productFilter();
            this.viewSwitcher();
            this.currencySwitcher();
        },

        /**
         * Quantity Buttons
         * 
         * Add plus and minus buttons to quantity inputs
         */
        quantityButtons: function() {
            // Add quantity buttons
            $('div.quantity:not(.buttons-added), td.quantity:not(.buttons-added)').addClass('buttons-added').append('<button type="button" class="plus" >+</button>').prepend('<button type="button" class="minus">-</button>');

            // Handle button clicks
            $(document).on('click', '.plus, .minus', function() {
                var $qty = $(this).closest('.quantity').find('.qty');
                var currentVal = parseFloat($qty.val());
                var max = parseFloat($qty.attr('max'));
                var min = parseFloat($qty.attr('min'));
                var step = parseFloat($qty.attr('step'));

                // Format values
                if (!currentVal || currentVal === '' || currentVal === 'NaN') currentVal = 0;
                if (max === '' || max === 'NaN') max = '';
                if (min === '' || min === 'NaN') min = 0;
                if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN') step = 1;

                // Change the value
                if ($(this).is('.plus')) {
                    if (max && (max == currentVal || currentVal > max)) {
                        $qty.val(max);
                    } else {
                        $qty.val(currentVal + step);
                    }
                } else {
                    if (min && (min == currentVal || currentVal < min)) {
                        $qty.val(min);
                    } else if (currentVal > 0) {
                        $qty.val(currentVal - step);
                    }
                }

                // Trigger change event
                $qty.trigger('change');
            });
        },

        /**
         * Product Gallery
         * 
         * Enhance the product gallery functionality
         */
        productGallery: function() {
            // Check if product gallery exists
            if (!$('.woocommerce-product-gallery').length) {
                return;
            }

            // Initialize product gallery
            $('.woocommerce-product-gallery').each(function() {
                var $gallery = $(this);
                var $mainImage = $gallery.find('.woocommerce-product-gallery__image:first-child a');
                var $thumbnails = $gallery.find('.flex-control-thumbs');

                // Add active class to first thumbnail
                $thumbnails.find('li:first-child').addClass('active');

                // Handle thumbnail click
                $thumbnails.on('click', 'li', function() {
                    var $this = $(this);
                    var index = $this.index();

                    // Update active class
                    $thumbnails.find('li').removeClass('active');
                    $this.addClass('active');

                    // Trigger click on the corresponding gallery image
                    $gallery.find('.woocommerce-product-gallery__image').eq(index).find('a').trigger('click');
                });
            });
        },

        /**
         * Product Tabs
         * 
         * Enhance the product tabs functionality
         */
        productTabs: function() {
            // Check if product tabs exist
            if (!$('.woocommerce-tabs').length) {
                return;
            }

            // Handle tab click
            $('.woocommerce-tabs ul.tabs li a').on('click', function(e) {
                e.preventDefault();

                var $this = $(this);
                var $tab = $this.closest('li');
                var $tabs = $tab.closest('ul.tabs');
                var $panels = $this.closest('.woocommerce-tabs').find('.panel');
                var tabId = $this.attr('href');

                // Update active tab
                $tabs.find('li').removeClass('active');
                $tab.addClass('active');

                // Show selected panel
                $panels.hide();
                $(tabId).show();

                // Update URL hash
                if (history.pushState) {
                    history.pushState(null, null, tabId);
                } else {
                    location.hash = tabId;
                }
            });

            // Show tab based on URL hash
            if (location.hash && $('.woocommerce-tabs').length) {
                var hash = location.hash;
                var $tab = $('.woocommerce-tabs ul.tabs li a[href="' + hash + '"]');

                if ($tab.length) {
                    $tab.trigger('click');
                }
            }
        },

        /**
         * AJAX Add to Cart
         * 
         * Handle AJAX add to cart functionality
         */
        ajaxAddToCart: function() {
            // Check if AJAX add to cart is enabled
            if (!aqualuxeWooCommerce.ajaxCartEnabled) {
                return;
            }

            // Handle add to cart button click
            $(document).on('click', '.ajax_add_to_cart', function(e) {
                var $thisbutton = $(this);

                if ($thisbutton.is('.product_type_simple')) {
                    if (!$thisbutton.attr('data-product_id')) {
                        return true;
                    }

                    e.preventDefault();

                    $thisbutton.removeClass('added');
                    $thisbutton.addClass('loading');

                    var data = {
                        action: 'aqualuxe_add_to_cart',
                        product_id: $thisbutton.attr('data-product_id'),
                        quantity: $thisbutton.attr('data-quantity'),
                        nonce: aqualuxeWooCommerce.nonce
                    };

                    // Ajax action
                    $.post(aqualuxeWooCommerce.ajaxUrl, data, function(response) {
                        if (!response) {
                            return;
                        }

                        if (response.error && response.product_url) {
                            window.location = response.product_url;
                            return;
                        }

                        // Redirect to cart option
                        if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {
                            window.location = wc_add_to_cart_params.cart_url;
                            return;
                        }

                        // Trigger event so themes can refresh other areas
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);

                        // Update button text
                        $thisbutton.removeClass('loading');
                        $thisbutton.addClass('added');
                    });

                    return false;
                }
            });

            // Handle add to cart on single product page
            $('.single_add_to_cart_button').on('click', function(e) {
                var $thisbutton = $(this);
                var $form = $thisbutton.closest('form.cart');

                if ($thisbutton.is('.disabled')) {
                    return;
                }

                if ($form.find('input[name="variation_id"]').length > 0 && $form.find('input[name="variation_id"]').val() === '') {
                    return true;
                }

                if (!aqualuxeWooCommerce.ajaxCartEnabled) {
                    return true;
                }

                e.preventDefault();

                $thisbutton.removeClass('added');
                $thisbutton.addClass('loading');

                var formData = new FormData($form[0]);
                formData.append('action', 'aqualuxe_add_to_cart');
                formData.append('nonce', aqualuxeWooCommerce.nonce);

                // Ajax action
                $.ajax({
                    url: aqualuxeWooCommerce.ajaxUrl,
                    data: formData,
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (!response) {
                            return;
                        }

                        if (response.error && response.product_url) {
                            window.location = response.product_url;
                            return;
                        }

                        // Redirect to cart option
                        if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {
                            window.location = wc_add_to_cart_params.cart_url;
                            return;
                        }

                        // Trigger event so themes can refresh other areas
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);

                        // Update button text
                        $thisbutton.removeClass('loading');
                        $thisbutton.addClass('added');
                    }
                });

                return false;
            });
        },

        /**
         * Quick View
         * 
         * Handle quick view functionality
         */
        quickView: function() {
            // Check if quick view is enabled
            if (!aqualuxeWooCommerce.quickViewEnabled) {
                return;
            }

            // Handle quick view button click
            $(document).on('click', '.aqualuxe-quick-view', function(e) {
                e.preventDefault();

                var $this = $(this);
                var productId = $this.data('product-id');
                var $modal = $('#aqualuxe-quick-view-modal');
                var $content = $modal.find('.aqualuxe-quick-view-inner');
                var $loader = $modal.find('.aqualuxe-quick-view-loader');

                // Show modal and loader
                $modal.addClass('open');
                $loader.show();
                $content.html('');

                // Ajax request
                $.ajax({
                    url: aqualuxeWooCommerce.ajaxUrl,
                    data: {
                        action: 'aqualuxe_quick_view',
                        product_id: productId,
                        nonce: aqualuxeWooCommerce.nonce
                    },
                    type: 'POST',
                    success: function(response) {
                        if (response.success) {
                            $content.html(response.data.html);
                            $loader.hide();

                            // Initialize product gallery
                            if (typeof wc_single_product_params !== 'undefined') {
                                $content.find('.woocommerce-product-gallery').each(function() {
                                    $(this).wc_product_gallery(wc_single_product_params);
                                });
                            }

                            // Initialize quantity buttons
                            AquaLuxeWooCommerce.quantityButtons();

                            // Initialize variation form
                            $content.find('.variations_form').each(function() {
                                $(this).wc_variation_form();
                            });
                        } else {
                            $content.html('<p class="error">' + response.data.message + '</p>');
                            $loader.hide();
                        }
                    },
                    error: function() {
                        $content.html('<p class="error">Error loading product information</p>');
                        $loader.hide();
                    }
                });
            });

            // Close quick view modal
            $(document).on('click', '.aqualuxe-quick-view-close, .aqualuxe-quick-view-overlay', function(e) {
                e.preventDefault();
                $('#aqualuxe-quick-view-modal').removeClass('open');
            });

            // Close quick view modal on ESC key press
            $(document).on('keyup', function(e) {
                if (e.key === 'Escape' && $('#aqualuxe-quick-view-modal').hasClass('open')) {
                    $('#aqualuxe-quick-view-modal').removeClass('open');
                }
            });
        },

        /**
         * Wishlist
         * 
         * Handle wishlist functionality
         */
        wishlist: function() {
            // Check if wishlist is enabled
            if (!aqualuxeWooCommerce.wishlistEnabled) {
                return;
            }

            // Handle wishlist button click
            $(document).on('click', '.aqualuxe-wishlist-button', function(e) {
                e.preventDefault();

                var $this = $(this);
                var productId = $this.data('product-id');
                var isInWishlist = $this.hasClass('in-wishlist');
                var action = isInWishlist ? 'aqualuxe_wishlist_remove' : 'aqualuxe_wishlist_add';

                $this.addClass('loading');

                // Ajax request
                $.ajax({
                    url: aqualuxeWooCommerce.ajaxUrl,
                    data: {
                        action: action,
                        product_id: productId,
                        nonce: aqualuxeWooCommerce.nonce
                    },
                    type: 'POST',
                    success: function(response) {
                        $this.removeClass('loading');

                        if (response.success) {
                            if (isInWishlist) {
                                $this.removeClass('in-wishlist');
                                $this.text(aqualuxeWooCommerce.i18n.addToWishlist);
                            } else {
                                $this.addClass('in-wishlist');
                                $this.text(aqualuxeWooCommerce.i18n.inWishlist);
                            }
                        }
                    }
                });
            });
        },

        /**
         * Product Filter
         * 
         * Handle product filter functionality
         */
        productFilter: function() {
            // Toggle filter
            $('.filter-toggle-button').on('click', function(e) {
                e.preventDefault();
                $('.filter-content').addClass('active');
            });

            // Close filter
            $('.filter-close').on('click', function(e) {
                e.preventDefault();
                $('.filter-content').removeClass('active');
            });

            // Close filter when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.filter-content').length && !$(e.target).closest('.filter-toggle-button').length) {
                    $('.filter-content').removeClass('active');
                }
            });

            // Price slider
            if (typeof $.fn.slider !== 'undefined') {
                $('.price-slider').each(function() {
                    var $slider = $(this);
                    var $min = $slider.data('min');
                    var $max = $slider.data('max');
                    var $minPrice = $slider.data('min-price');
                    var $maxPrice = $slider.data('max-price');

                    $slider.slider({
                        range: true,
                        min: $min,
                        max: $max,
                        values: [$minPrice, $maxPrice],
                        slide: function(event, ui) {
                            $('.price-slider-values .min-value').text(ui.values[0]);
                            $('.price-slider-values .max-value').text(ui.values[1]);
                            $('#min_price').val(ui.values[0]);
                            $('#max_price').val(ui.values[1]);
                        }
                    });
                });
            }

            // Apply filter
            $('.filter-apply').on('click', function(e) {
                e.preventDefault();
                $('#product-filter-form').submit();
            });

            // Reset filter
            $('.filter-reset').on('click', function(e) {
                e.preventDefault();
                window.location.href = window.location.href.split('?')[0];
            });
        },

        /**
         * View Switcher
         * 
         * Handle product view switcher functionality
         */
        viewSwitcher: function() {
            // Handle view switcher button click
            $('.aqualuxe-product-view-switcher button').on('click', function(e) {
                e.preventDefault();

                var $this = $(this);
                var view = $this.data('view');

                // Update active button
                $('.aqualuxe-product-view-switcher button').removeClass('active');
                $this.addClass('active');

                // Update product list view
                $('.products').removeClass('grid-view list-view').addClass(view + '-view');

                // Save preference in cookie
                document.cookie = 'aqualuxe_product_view=' + view + '; path=/; max-age=31536000';
            });
        },

        /**
         * Currency Switcher
         * 
         * Handle currency switcher functionality
         */
        currencySwitcher: function() {
            // Handle currency switcher change
            $('#aqualuxe-currency').on('change', function() {
                var currency = $(this).val();

                // Save currency in cookie
                document.cookie = 'aqualuxe_currency=' + currency + '; path=/; max-age=31536000';

                // Reload page
                window.location.reload();
            });
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        AquaLuxeWooCommerce.init();
    });

})(jQuery);