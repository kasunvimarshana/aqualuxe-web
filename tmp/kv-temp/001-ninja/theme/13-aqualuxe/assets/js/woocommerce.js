/**
 * AquaLuxe Theme WooCommerce JavaScript
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
            this.initQuantityButtons();
            this.initProductGallery();
            this.initQuickView();
            this.initWishlist();
            this.initFilters();
            this.initCheckout();
            this.initCategoryTabs();
            this.initProductTabs();
            this.initAjaxAddToCart();
            this.initPriceSlider();
            this.initVariationSwatches();
        },

        /**
         * Initialize Quantity Buttons
         */
        initQuantityButtons: function() {
            // Add quantity buttons if they don't exist
            if ($('.quantity').length > 0 && $('.quantity-button').length === 0) {
                $('.quantity').each(function() {
                    var $quantityInput = $(this).find('input.qty');
                    
                    // Add minus button
                    $('<button type="button" class="quantity-button minus">-</button>').insertBefore($quantityInput);
                    
                    // Add plus button
                    $('<button type="button" class="quantity-button plus">+</button>').insertAfter($quantityInput);
                });
            }

            // Handle quantity button clicks
            $(document).on('click', '.quantity-button', function() {
                var $button = $(this);
                var $input = $button.parent().find('input.qty');
                var oldValue = parseFloat($input.val());
                var max = parseFloat($input.attr('max'));
                var min = parseFloat($input.attr('min'));
                var step = parseFloat($input.attr('step'));

                if (isNaN(oldValue)) {
                    oldValue = 0;
                }

                if (isNaN(max)) {
                    max = 100;
                }

                if (isNaN(min)) {
                    min = 0;
                }

                if (isNaN(step)) {
                    step = 1;
                }

                if ($button.hasClass('plus')) {
                    if (oldValue < max) {
                        var newVal = oldValue + step;
                    } else {
                        var newVal = max;
                    }
                } else {
                    if (oldValue > min) {
                        var newVal = oldValue - step;
                    } else {
                        var newVal = min;
                    }
                }

                $input.val(newVal);
                $input.trigger('change');
            });
        },

        /**
         * Initialize Product Gallery
         */
        initProductGallery: function() {
            // Check if product gallery exists
            if ($('.woocommerce-product-gallery').length === 0) {
                return;
            }

            // Handle thumbnail clicks
            $('.woocommerce-product-gallery__wrapper').on('click', '.woocommerce-product-gallery__image a', function(e) {
                e.preventDefault();
            });

            // Handle thumbnail hover
            $('.woocommerce-product-gallery__wrapper').on('mouseenter', '.woocommerce-product-gallery__image', function() {
                var $image = $(this).find('img');
                var $mainImage = $('.woocommerce-product-gallery__image:first-child img');
                var imageSrc = $image.attr('src');
                var imageFullSrc = $(this).find('a').attr('href');
                
                // Update main image
                $mainImage.attr('src', imageSrc);
                $mainImage.attr('srcset', '');
                $mainImage.closest('a').attr('href', imageFullSrc);
            });
        },

        /**
         * Initialize Quick View
         */
        initQuickView: function() {
            var self = this;

            // Handle quick view button click
            $(document).on('click', '.quick-view-button', function(e) {
                e.preventDefault();
                var productId = $(this).data('product-id');
                self.openQuickView(productId);
            });

            // Close quick view modal
            $(document).on('click', '#quick-view-close', function() {
                self.closeQuickView();
            });

            // Close on overlay click
            $(document).on('click', '#quick-view-modal', function(e) {
                if ($(e.target).is('#quick-view-modal')) {
                    self.closeQuickView();
                }
            });

            // Close on escape key
            $(document).on('keydown', function(e) {
                if (e.keyCode === 27) {
                    self.closeQuickView();
                }
            });
        },

        /**
         * Open Quick View
         *
         * @param {number} productId Product ID
         */
        openQuickView: function(productId) {
            var self = this;
            var $modal = $('#quick-view-modal');
            var $content = $modal.find('.quick-view-content');
            var $loading = $modal.find('.quick-view-loading');

            // Show modal and loading spinner
            $modal.removeClass('hidden');
            $content.addClass('hidden');
            $loading.removeClass('hidden');

            // Load product data via AJAX
            $.ajax({
                url: aqualuxeQuickView.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_load_product_quick_view',
                    product_id: productId,
                    nonce: aqualuxeQuickView.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Update modal content
                        $modal.find('.product-title').html(response.data.title);
                        $modal.find('.product-image').html(response.data.image);
                        $modal.find('.product-price').html(response.data.price_html);
                        $modal.find('.product-rating').html(response.data.rating_html);
                        $modal.find('.product-excerpt').html(response.data.excerpt);
                        $modal.find('.product-add-to-cart').html(response.data.add_to_cart_html);
                        $modal.find('.view-full-details').attr('href', response.data.permalink);

                        // Initialize quantity buttons
                        self.initQuantityButtons();

                        // Show content
                        $content.removeClass('hidden');
                        $loading.addClass('hidden');
                    } else {
                        self.closeQuickView();
                        alert(aqualuxeQuickView.i18n.error);
                    }
                },
                error: function() {
                    self.closeQuickView();
                    alert(aqualuxeQuickView.i18n.error);
                }
            });
        },

        /**
         * Close Quick View
         */
        closeQuickView: function() {
            $('#quick-view-modal').addClass('hidden');
        },

        /**
         * Initialize Wishlist
         */
        initWishlist: function() {
            // Handle wishlist button click
            $(document).on('click', '.wishlist-toggle', function(e) {
                e.preventDefault();
                var $button = $(this);
                var productId = $button.data('product-id');

                // Check if user is logged in
                if (!aqualuxeWishlist.isLoggedIn) {
                    if (confirm(aqualuxeWishlist.i18n.loginRequired)) {
                        window.location.href = aqualuxeWishlist.loginUrl;
                    }
                    return;
                }

                // Toggle wishlist via AJAX
                $.ajax({
                    url: aqualuxeWishlist.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: aqualuxeWishlist.isLoggedIn ? 'aqualuxe_toggle_wishlist' : 'aqualuxe_toggle_wishlist_guest',
                        product_id: productId,
                        nonce: aqualuxeWishlist.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update wishlist icon
                            if (response.data.status === 'added') {
                                $button.find('.wishlist-icon').addClass('text-red-500 fill-current');
                                alert(aqualuxeWishlist.i18n.added);
                            } else {
                                $button.find('.wishlist-icon').removeClass('text-red-500 fill-current');
                                alert(aqualuxeWishlist.i18n.removed);
                            }

                            // Update wishlist count
                            $('.wishlist-count').text(response.data.count);
                        }
                    }
                });
            });

            // Handle wishlist remove button click
            $(document).on('click', '.wishlist-remove', function(e) {
                e.preventDefault();
                var $button = $(this);
                var productId = $button.data('product-id');

                // Remove from wishlist via AJAX
                $.ajax({
                    url: aqualuxeWishlist.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: aqualuxeWishlist.isLoggedIn ? 'aqualuxe_toggle_wishlist' : 'aqualuxe_toggle_wishlist_guest',
                        product_id: productId,
                        nonce: aqualuxeWishlist.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // Remove product from wishlist page
                            $button.closest('.wishlist-product').fadeOut(300, function() {
                                $(this).remove();

                                // Show empty message if no products left
                                if ($('.wishlist-product').length === 0) {
                                    $('.wishlist-products').replaceWith('<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md transition-colors duration-300">' + aqualuxeWishlist.i18n.emptyWishlist + ' <a class="button inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-300 ml-4" href="' + aqualuxeWishlist.shopUrl + '">' + aqualuxeWishlist.i18n.browseProducts + '</a></div>');
                                }
                            });

                            // Update wishlist count
                            $('.wishlist-count').text(response.data.count);
                        }
                    }
                });
            });
        },

        /**
         * Initialize Filters
         */
        initFilters: function() {
            var self = this;

            // Toggle filter sidebar
            $('#filter-toggle').on('click', function() {
                $('#filter-sidebar').removeClass('hidden');
            });

            $('#filter-close').on('click', function() {
                $('#filter-sidebar').addClass('hidden');
            });

            // Close on overlay click
            $('#filter-sidebar').on('click', function(e) {
                if ($(e.target).is('#filter-sidebar')) {
                    $('#filter-sidebar').addClass('hidden');
                }
            });

            // Handle filter form submission
            $('#product-filter-form').on('submit', function(e) {
                e.preventDefault();
                self.applyFilters();
            });

            // Handle filter form reset
            $('#product-filter-form').on('reset', function() {
                setTimeout(function() {
                    self.applyFilters();
                }, 10);
            });

            // Handle tag filter clicks
            $('.tag-label').on('click', function() {
                var $input = $(this).find('input');
                var $span = $(this).find('span');

                if ($input.is(':checked')) {
                    $span.removeClass('bg-primary text-white').addClass('bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300');
                } else {
                    $span.removeClass('bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300').addClass('bg-primary text-white');
                }
            });

            // Handle remove filter button clicks
            $(document).on('click', '.remove-filter', function() {
                var filter = $(this).data('filter');
                var value = $(this).data('value');

                // Remove filter
                if (filter === 'price') {
                    $('input[name="min_price"]').val('');
                    $('input[name="max_price"]').val('');
                } else if (filter === 'rating') {
                    $('input[name="rating[]"][value="' + value + '"]').prop('checked', false);
                } else {
                    $('input[name="' + filter + '[]"][value="' + value + '"]').prop('checked', false);
                }

                // Apply filters
                self.applyFilters();
            });

            // Handle clear all filters button click
            $(document).on('click', '.clear-all-filters', function() {
                $('#product-filter-form').trigger('reset');
                self.applyFilters();
            });
        },

        /**
         * Apply Filters
         */
        applyFilters: function() {
            var $form = $('#product-filter-form');
            var $shopContainer = $('.woocommerce-products-wrapper');
            var $overlay = $('<div class="woocommerce-overlay"><div class="woocommerce-overlay-inner"><div class="woocommerce-overlay-loader"></div></div></div>');

            // Show loading overlay
            $shopContainer.append($overlay);
            $overlay.fadeIn(300);

            // Get form data
            var formData = $form.serialize();

            // Add action and nonce
            formData += '&action=aqualuxe_filter_products&nonce=' + aqualuxeFilters.nonce;

            // Send AJAX request
            $.ajax({
                url: aqualuxeFilters.ajaxUrl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Update products
                        $('.products').replaceWith(response.data.products_html);

                        // Update product count
                        $('.woocommerce-result-count').text(aqualuxeFilters.i18n.showing + ' ' + response.data.found_posts + ' ' + aqualuxeFilters.i18n.results);

                        // Close filter sidebar on mobile
                        if (window.innerWidth < 768) {
                            $('#filter-sidebar').addClass('hidden');
                        }

                        // Scroll to top of products
                        $('html, body').animate({
                            scrollTop: $shopContainer.offset().top - 100
                        }, 500);
                    } else {
                        alert(aqualuxeFilters.i18n.error);
                    }

                    // Remove loading overlay
                    $overlay.fadeOut(300, function() {
                        $overlay.remove();
                    });
                },
                error: function() {
                    alert(aqualuxeFilters.i18n.error);

                    // Remove loading overlay
                    $overlay.fadeOut(300, function() {
                        $overlay.remove();
                    });
                }
            });
        },

        /**
         * Initialize Checkout
         */
        initCheckout: function() {
            // Add custom validation
            $(document).on('checkout_place_order', function() {
                var isValid = true;

                // Validate required fields
                $('.woocommerce-checkout input[required], .woocommerce-checkout select[required]').each(function() {
                    if ($(this).val() === '') {
                        isValid = false;
                        $(this).addClass('border-red-500').removeClass('border-gray-300 dark:border-gray-700');
                    } else {
                        $(this).removeClass('border-red-500').addClass('border-gray-300 dark:border-gray-700');
                    }
                });

                return isValid;
            });

            // Handle payment method selection
            $(document).on('change', 'input[name="payment_method"]', function() {
                var paymentMethod = $(this).val();
                $('.payment_box').slideUp(300);
                $('.payment_box--' + paymentMethod).slideDown(300);
            });
        },

        /**
         * Initialize Category Tabs
         */
        initCategoryTabs: function() {
            // Handle category tab clicks
            $(document).on('click', '.category-tab-button', function() {
                var category = $(this).data('category');
                
                // Update active tab
                $('.category-tab-button').removeClass('border-primary text-primary dark:border-primary-dark dark:text-primary-dark').addClass('border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600');
                $(this).removeClass('border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600').addClass('border-primary text-primary dark:border-primary-dark dark:text-primary-dark');
                
                // Show selected content
                $('.category-tab-content').addClass('hidden');
                $('.category-tab-content[data-category="' + category + '"]').removeClass('hidden');
            });
        },

        /**
         * Initialize Product Tabs
         */
        initProductTabs: function() {
            // Handle product tab clicks
            $('.wc-tabs li a').on('click', function(e) {
                e.preventDefault();
                var target = $(this).attr('href');
                
                // Update active tab
                $('.wc-tabs li').removeClass('active');
                $(this).parent().addClass('active');
                
                // Show selected content
                $('.woocommerce-Tabs-panel').hide();
                $(target).show();
            });
        },

        /**
         * Initialize AJAX Add to Cart
         */
        initAjaxAddToCart: function() {
            var self = this;

            // Handle add to cart button click
            $(document).on('click', '.ajax_add_to_cart', function(e) {
                e.preventDefault();
                var $button = $(this);
                
                // Don't proceed if already loading
                if ($button.is('.loading')) {
                    return;
                }
                
                // Get product data
                var productId = $button.data('product_id');
                var quantity = $button.data('quantity') || 1;
                var variationId = $button.data('variation_id') || 0;
                var variations = {};
                
                // Get variations if any
                if (variationId > 0) {
                    $('.variations select').each(function() {
                        variations[$(this).attr('name')] = $(this).val();
                    });
                }
                
                // Add loading state
                $button.addClass('loading');
                
                // Add to cart via AJAX
                $.ajax({
                    url: wc_add_to_cart_params.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'woocommerce_add_to_cart',
                        product_id: productId,
                        quantity: quantity,
                        variation_id: variationId,
                        variation: variations
                    },
                    success: function(response) {
                        if (response.error && response.product_url) {
                            window.location = response.product_url;
                            return;
                        }
                        
                        // Update cart fragments
                        if (response.fragments) {
                            $.each(response.fragments, function(key, value) {
                                $(key).replaceWith(value);
                            });
                        }
                        
                        // Trigger event
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);
                        
                        // Remove loading state
                        $button.removeClass('loading');
                        
                        // Show mini cart
                        self.showMiniCart();
                    },
                    error: function() {
                        // Remove loading state
                        $button.removeClass('loading');
                        
                        // Show error
                        alert(wc_add_to_cart_params.i18n_ajax_error);
                    }
                });
            });
        },

        /**
         * Show Mini Cart
         */
        showMiniCart: function() {
            // Check if mini cart exists
            if ($('.mini-cart-wrapper').length === 0) {
                return;
            }
            
            // Show mini cart
            $('.mini-cart-wrapper').addClass('active');
            
            // Hide after 5 seconds
            setTimeout(function() {
                $('.mini-cart-wrapper').removeClass('active');
            }, 5000);
        },

        /**
         * Initialize Price Slider
         */
        initPriceSlider: function() {
            // Check if price range slider exists
            if ($('.price-range-slider').length === 0) {
                return;
            }
            
            // Initialize price slider
            $('.price-range-slider').each(function() {
                var $slider = $(this);
                var $minInput = $slider.parent().find('input[name="min_price"]');
                var $maxInput = $slider.parent().find('input[name="max_price"]');
                var $minPrice = $slider.parent().find('.min-price');
                var $maxPrice = $slider.parent().find('.max-price');
                var min = parseFloat($slider.data('min'));
                var max = parseFloat($slider.data('max'));
                var currentMin = parseFloat($slider.data('current-min'));
                var currentMax = parseFloat($slider.data('current-max'));
                
                // Initialize slider
                $slider.slider({
                    range: true,
                    min: min,
                    max: max,
                    values: [currentMin, currentMax],
                    slide: function(event, ui) {
                        // Update inputs
                        $minInput.val(ui.values[0]);
                        $maxInput.val(ui.values[1]);
                        
                        // Update display
                        $minPrice.text(self.formatPrice(ui.values[0]));
                        $maxPrice.text(self.formatPrice(ui.values[1]));
                    }
                });
            });
        },

        /**
         * Format Price
         *
         * @param {number} price Price to format
         * @return {string} Formatted price
         */
        formatPrice: function(price) {
            return woocommerce_price_slider_params.currency_format_symbol + price.toFixed(2);
        },

        /**
         * Initialize Variation Swatches
         */
        initVariationSwatches: function() {
            // Check if variation swatches exist
            if ($('.variations').length === 0) {
                return;
            }
            
            // Convert dropdowns to swatches
            $('.variations select').each(function() {
                var $select = $(this);
                var attributeName = $select.attr('name');
                var $wrapper = $select.parent();
                var $options = $select.find('option');
                
                // Skip if no options or only one option
                if ($options.length <= 1) {
                    return;
                }
                
                // Create swatches container
                var $swatches = $('<div class="variation-swatches"></div>');
                
                // Add swatches
                $options.each(function() {
                    var $option = $(this);
                    var value = $option.val();
                    var text = $option.text();
                    
                    // Skip if empty value
                    if (value === '') {
                        return;
                    }
                    
                    // Create swatch
                    var $swatch = $('<div class="variation-swatch" data-value="' + value + '">' + text + '</div>');
                    
                    // Add swatch to container
                    $swatches.append($swatch);
                });
                
                // Hide select
                $select.hide();
                
                // Add swatches to wrapper
                $wrapper.append($swatches);
                
                // Handle swatch click
                $swatches.on('click', '.variation-swatch', function() {
                    var $swatch = $(this);
                    var value = $swatch.data('value');
                    
                    // Update select
                    $select.val(value).trigger('change');
                    
                    // Update active swatch
                    $swatches.find('.variation-swatch').removeClass('active');
                    $swatch.addClass('active');
                });
                
                // Set initial active swatch
                var initialValue = $select.val();
                if (initialValue) {
                    $swatches.find('.variation-swatch[data-value="' + initialValue + '"]').addClass('active');
                }
            });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeWooCommerce.init();
    });

})(jQuery);