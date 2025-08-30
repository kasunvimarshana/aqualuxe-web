/**
 * AquaLuxe WooCommerce Scripts
 *
 * This file contains scripts for WooCommerce functionality.
 */

(function($) {
    'use strict';

    /**
     * AquaLuxe WooCommerce
     */
    var AqualuxeWooCommerce = {
        /**
         * Initialize
         */
        init: function() {
            this.miniCart();
            this.quickView();
            this.quantityButtons();
            this.ajaxAddToCart();
            this.productGallery();
            this.productTabs();
            this.checkoutEnhancements();
            this.shopFilters();
            this.infiniteScroll();
        },

        /**
         * Mini Cart
         */
        miniCart: function() {
            var $miniCart = $('.aqualuxe-mini-cart');
            var $miniCartToggle = $('.aqualuxe-mini-cart-toggle');
            var $miniCartClose = $('.aqualuxe-mini-cart-close');
            var $body = $('body');

            // Toggle mini cart
            $miniCartToggle.on('click', function(e) {
                e.preventDefault();
                $miniCart.toggleClass('active');
                $body.toggleClass('aqualuxe-mini-cart-open');
            });

            // Close mini cart
            $miniCartClose.on('click', function(e) {
                e.preventDefault();
                $miniCart.removeClass('active');
                $body.removeClass('aqualuxe-mini-cart-open');
            });

            // Close mini cart when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.aqualuxe-mini-cart').length && !$(e.target).closest('.aqualuxe-mini-cart-toggle').length) {
                    $miniCart.removeClass('active');
                    $body.removeClass('aqualuxe-mini-cart-open');
                }
            });

            // Update mini cart on added_to_cart event
            $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
                // Show mini cart
                $miniCart.addClass('active');
                $body.addClass('aqualuxe-mini-cart-open');

                // Auto-close after 5 seconds
                setTimeout(function() {
                    $miniCart.removeClass('active');
                    $body.removeClass('aqualuxe-mini-cart-open');
                }, 5000);
            });
        },

        /**
         * Quick View
         */
        quickView: function() {
            var self = this;
            var $modal = $('#aqualuxe-quick-view-modal');
            var $modalContent = $modal.find('.aqualuxe-quick-view-content');
            var $modalClose = $modal.find('.aqualuxe-modal-close');
            var $modalOverlay = $modal.find('.aqualuxe-modal-overlay');
            var $body = $('body');

            // Open quick view modal
            $(document).on('click', '.aqualuxe-quick-view', function(e) {
                e.preventDefault();

                var $this = $(this);
                var productId = $this.data('product-id');

                // Show loading
                $modalContent.html('<div class="aqualuxe-loading"><span></span></div>');
                $modal.addClass('active');
                $body.addClass('aqualuxe-modal-open');

                // Get product data
                $.ajax({
                    url: aqualuxeWooCommerce.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_quick_view',
                        product_id: productId,
                        nonce: aqualuxeWooCommerce.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            $modalContent.html(response.data.html);

                            // Initialize quantity buttons
                            self.quantityButtons();

                            // Initialize product gallery
                            self.productGallery();

                            // Trigger event
                            $(document.body).trigger('aqualuxe_quick_view_loaded');
                        } else {
                            $modalContent.html('<p class="aqualuxe-error">' + aqualuxeWooCommerce.i18n.error + '</p>');
                        }
                    },
                    error: function() {
                        $modalContent.html('<p class="aqualuxe-error">' + aqualuxeWooCommerce.i18n.error + '</p>');
                    }
                });
            });

            // Close modal
            $modalClose.on('click', function(e) {
                e.preventDefault();
                $modal.removeClass('active');
                $body.removeClass('aqualuxe-modal-open');
            });

            // Close modal when clicking on overlay
            $modalOverlay.on('click', function(e) {
                e.preventDefault();
                $modal.removeClass('active');
                $body.removeClass('aqualuxe-modal-open');
            });

            // Close modal with ESC key
            $(document).on('keyup', function(e) {
                if (e.keyCode === 27) {
                    $modal.removeClass('active');
                    $body.removeClass('aqualuxe-modal-open');
                }
            });
        },

        /**
         * Quantity Buttons
         */
        quantityButtons: function() {
            // Add quantity buttons
            function addQuantityButtons() {
                $('.quantity').each(function() {
                    var $quantity = $(this);
                    var $input = $quantity.find('input.qty');

                    // Only add buttons once
                    if ($quantity.find('.aqualuxe-qty-button').length === 0) {
                        $input.before('<button type="button" class="aqualuxe-qty-button aqualuxe-qty-minus">-</button>');
                        $input.after('<button type="button" class="aqualuxe-qty-button aqualuxe-qty-plus">+</button>');
                    }
                });
            }

            // Add quantity buttons on page load
            addQuantityButtons();

            // Add quantity buttons on AJAX events
            $(document.body).on('updated_cart_totals wc_fragments_loaded aqualuxe_quick_view_loaded', function() {
                addQuantityButtons();
            });

            // Handle quantity button clicks
            $(document).on('click', '.aqualuxe-qty-button', function() {
                var $button = $(this);
                var $input = $button.parent().find('input.qty');
                var min = $input.attr('min') || 0;
                var max = $input.attr('max') || '';
                var step = $input.attr('step') || 1;
                var currentValue = parseFloat($input.val());

                // Increment or decrement
                if ($button.hasClass('aqualuxe-qty-plus')) {
                    var newValue = currentValue + parseFloat(step);
                    if (max && newValue > parseFloat(max)) {
                        newValue = max;
                    }
                } else {
                    var newValue = currentValue - parseFloat(step);
                    if (newValue < parseFloat(min)) {
                        newValue = min;
                    }
                }

                // Update value
                $input.val(newValue).trigger('change');
            });
        },

        /**
         * AJAX Add to Cart
         */
        ajaxAddToCart: function() {
            // Single product add to cart
            $(document).on('click', '.single_add_to_cart_button:not(.disabled)', function(e) {
                var $button = $(this);
                var $form = $button.closest('form.cart');

                // Only proceed if AJAX add to cart is enabled
                if ($form.length === 0 || !$form.hasClass('aqualuxe-ajax-add-to-cart')) {
                    return;
                }

                e.preventDefault();

                // Get form data
                var formData = new FormData($form[0]);
                formData.append('action', 'aqualuxe_ajax_add_to_cart');
                formData.append('nonce', aqualuxeWooCommerce.nonce);

                // Add loading state
                $button.addClass('loading').prop('disabled', true);
                $button.html(aqualuxeWooCommerce.i18n.adding);

                // Send AJAX request
                $.ajax({
                    url: aqualuxeWooCommerce.ajaxUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.fragments) {
                            // Update fragments
                            $.each(response.fragments, function(key, value) {
                                $(key).replaceWith(value);
                            });

                            // Trigger event
                            $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);
                        }

                        // Reset button
                        $button.removeClass('loading').prop('disabled', false);
                        $button.html(aqualuxeWooCommerce.i18n.added);

                        // Reset button text after 2 seconds
                        setTimeout(function() {
                            $button.html(aqualuxeWooCommerce.addToCartText);
                        }, 2000);
                    },
                    error: function() {
                        // Reset button
                        $button.removeClass('loading').prop('disabled', false);
                        $button.html(aqualuxeWooCommerce.addToCartText);
                    }
                });
            });
        },

        /**
         * Product Gallery
         */
        productGallery: function() {
            // Initialize product gallery
            $('.woocommerce-product-gallery').each(function() {
                var $gallery = $(this);
                var $mainImage = $gallery.find('.woocommerce-product-gallery__image');
                var $thumbnails = $gallery.find('.woocommerce-product-gallery__thumbnails');
                var $thumbnailItems = $thumbnails.find('.woocommerce-product-gallery__thumbnail');

                // Handle thumbnail clicks
                $thumbnailItems.on('click', function(e) {
                    e.preventDefault();

                    var $thumbnail = $(this);
                    var fullSrc = $thumbnail.data('full-src');
                    var fullSrcset = $thumbnail.data('full-srcset');
                    var fullSizes = $thumbnail.data('full-sizes');
                    var $image = $thumbnail.find('img');
                    var alt = $image.attr('alt');

                    // Update main image
                    $mainImage.find('img').attr({
                        'src': fullSrc,
                        'srcset': fullSrcset,
                        'sizes': fullSizes,
                        'alt': alt
                    });

                    // Update active state
                    $thumbnailItems.removeClass('active');
                    $thumbnail.addClass('active');
                });

                // Set first thumbnail as active
                $thumbnailItems.first().addClass('active');
            });
        },

        /**
         * Product Tabs
         */
        productTabs: function() {
            var $tabs = $('.woocommerce-tabs');
            var $tabLinks = $tabs.find('.wc-tabs li a');
            var $tabPanels = $tabs.find('.woocommerce-Tabs-panel');

            // Handle tab clicks
            $tabLinks.on('click', function(e) {
                e.preventDefault();

                var $link = $(this);
                var target = $link.attr('href');
                var $targetPanel = $(target);

                // Update active state
                $tabLinks.parent().removeClass('active');
                $link.parent().addClass('active');

                // Show target panel
                $tabPanels.removeClass('active');
                $targetPanel.addClass('active');
            });
        },

        /**
         * Checkout Enhancements
         */
        checkoutEnhancements: function() {
            // Add field validation
            var $checkoutForm = $('form.checkout');

            // Handle field validation
            $checkoutForm.on('blur', '.input-text', function() {
                var $field = $(this);
                var value = $field.val();
                var required = $field.prop('required');

                if (required && value === '') {
                    $field.addClass('aqualuxe-error');
                } else {
                    $field.removeClass('aqualuxe-error');
                }
            });

            // Handle country/state changes
            $(document.body).on('country_to_state_changed', function() {
                // Reinitialize select2 for state fields
                $('.state_select').select2({
                    minimumResultsForSearch: 5
                });
            });
        },

        /**
         * Shop Filters
         */
        shopFilters: function() {
            var $filterToggle = $('.aqualuxe-filter-toggle');
            var $filterWidget = $('.aqualuxe-filter-widget');
            var $filterClose = $('.aqualuxe-filter-close');
            var $body = $('body');

            // Toggle filters
            $filterToggle.on('click', function(e) {
                e.preventDefault();
                $filterWidget.toggleClass('active');
                $body.toggleClass('aqualuxe-filter-open');
            });

            // Close filters
            $filterClose.on('click', function(e) {
                e.preventDefault();
                $filterWidget.removeClass('active');
                $body.removeClass('aqualuxe-filter-open');
            });

            // Close filters when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.aqualuxe-filter-widget').length && !$(e.target).closest('.aqualuxe-filter-toggle').length) {
                    $filterWidget.removeClass('active');
                    $body.removeClass('aqualuxe-filter-open');
                }
            });

            // Price range slider
            $('.price_slider').each(function() {
                var $slider = $(this);
                var $amount = $slider.siblings('.price_slider_amount');
                var $minPrice = $amount.find('input[name="min_price"]');
                var $maxPrice = $amount.find('input[name="max_price"]');
                var minPrice = parseInt($minPrice.data('min'), 10);
                var maxPrice = parseInt($maxPrice.data('max'), 10);
                var currentMinPrice = parseInt($minPrice.val(), 10);
                var currentMaxPrice = parseInt($maxPrice.val(), 10);

                // Initialize slider
                $slider.slider({
                    range: true,
                    min: minPrice,
                    max: maxPrice,
                    values: [currentMinPrice, currentMaxPrice],
                    slide: function(event, ui) {
                        $minPrice.val(ui.values[0]);
                        $maxPrice.val(ui.values[1]);
                        $amount.find('.price_label span.from').html(formatPrice(ui.values[0]));
                        $amount.find('.price_label span.to').html(formatPrice(ui.values[1]));
                    }
                });

                // Format price
                function formatPrice(price) {
                    return accounting.formatMoney(price, {
                        symbol: woocommerce_price_slider_params.currency_format_symbol,
                        decimal: woocommerce_price_slider_params.currency_format_decimal_sep,
                        thousand: woocommerce_price_slider_params.currency_format_thousand_sep,
                        precision: woocommerce_price_slider_params.currency_format_num_decimals,
                        format: woocommerce_price_slider_params.currency_format
                    });
                }
            });
        },

        /**
         * Infinite Scroll
         */
        infiniteScroll: function() {
            var $container = $('.aqualuxe-products');
            var $pagination = $('.woocommerce-pagination');
            var $loadMore = $('.aqualuxe-load-more');
            var loading = false;
            var threshold = 200;

            // Only proceed if infinite scroll is enabled
            if (!$container.hasClass('aqualuxe-infinite-scroll')) {
                return;
            }

            // Load more button
            $loadMore.on('click', function(e) {
                e.preventDefault();

                if (loading) {
                    return;
                }

                var $button = $(this);
                var nextPage = $button.data('next-page');
                var maxPages = $button.data('max-pages');

                loadProducts(nextPage, maxPages);
            });

            // Infinite scroll
            if ($container.hasClass('aqualuxe-infinite-scroll-auto')) {
                $(window).on('scroll', function() {
                    if (loading) {
                        return;
                    }

                    var scrollPosition = $(window).scrollTop() + $(window).height();
                    var threshold = $container.offset().top + $container.height() - 200;

                    if (scrollPosition > threshold) {
                        var nextPage = $loadMore.data('next-page');
                        var maxPages = $loadMore.data('max-pages');

                        loadProducts(nextPage, maxPages);
                    }
                });
            }

            // Load products
            function loadProducts(nextPage, maxPages) {
                if (nextPage > maxPages) {
                    $loadMore.text(aqualuxeWooCommerce.i18n.noMoreItems).addClass('disabled');
                    return;
                }

                loading = true;
                $loadMore.text(aqualuxeWooCommerce.i18n.loading).addClass('loading');

                $.ajax({
                    url: aqualuxeWooCommerce.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_load_more_products',
                        page: nextPage,
                        nonce: aqualuxeWooCommerce.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // Append products
                            var $items = $(response.data.html);
                            $container.append($items);

                            // Update load more button
                            $loadMore.data('next-page', nextPage + 1);
                            $loadMore.text(aqualuxeWooCommerce.i18n.loadMore).removeClass('loading');

                            // Check if we've reached the last page
                            if (nextPage + 1 > maxPages) {
                                $loadMore.text(aqualuxeWooCommerce.i18n.noMoreItems).addClass('disabled');
                            }

                            // Trigger event
                            $(document.body).trigger('aqualuxe_products_loaded', [$items]);
                        } else {
                            $loadMore.text(aqualuxeWooCommerce.i18n.loadMore).removeClass('loading');
                        }

                        loading = false;
                    },
                    error: function() {
                        $loadMore.text(aqualuxeWooCommerce.i18n.loadMore).removeClass('loading');
                        loading = false;
                    }
                });
            }
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AqualuxeWooCommerce.init();
    });
})(jQuery);