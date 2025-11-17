/**
 * AquaLuxe Theme Filters JavaScript
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * AquaLuxe Filters Object
     */
    var AquaLuxeFilters = {
        /**
         * Initialize
         */
        init: function() {
            this.filterForm = $('#product-filter-form');
            this.filterSidebar = $('#filter-sidebar');
            this.filterToggle = $('#filter-toggle');
            this.filterClose = $('#filter-close');
            this.shopContainer = $('.woocommerce-products-wrapper');
            this.productsContainer = $('.products');
            this.resultCount = $('.woocommerce-result-count');
            this.pagination = $('.woocommerce-pagination');
            
            this.setupEventListeners();
            this.initPriceSlider();
        },

        /**
         * Setup Event Listeners
         */
        setupEventListeners: function() {
            var self = this;

            // Toggle filter sidebar
            this.filterToggle.on('click', function() {
                self.filterSidebar.removeClass('hidden');
            });

            this.filterClose.on('click', function() {
                self.filterSidebar.addClass('hidden');
            });

            // Close on overlay click
            this.filterSidebar.on('click', function(e) {
                if ($(e.target).is(self.filterSidebar)) {
                    self.filterSidebar.addClass('hidden');
                }
            });

            // Close on escape key
            $(document).on('keydown', function(e) {
                if (e.keyCode === 27 && !self.filterSidebar.hasClass('hidden')) {
                    self.filterSidebar.addClass('hidden');
                }
            });

            // Handle filter form submission
            this.filterForm.on('submit', function(e) {
                e.preventDefault();
                self.applyFilters();
            });

            // Handle filter form reset
            this.filterForm.on('reset', function() {
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
                    
                    // Reset price slider if exists
                    if ($.fn.slider && $('.price-range-slider').length) {
                        var $slider = $('.price-range-slider');
                        var min = parseFloat($slider.data('min'));
                        var max = parseFloat($slider.data('max'));
                        
                        $slider.slider('values', 0, min);
                        $slider.slider('values', 1, max);
                        
                        // Update display
                        $('.min-price').text(self.formatPrice(min));
                        $('.max-price').text(self.formatPrice(max));
                    }
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
                self.filterForm.trigger('reset');
                self.applyFilters();
            });

            // Handle pagination clicks
            $(document).on('click', '.woocommerce-pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').match(/page\/(\d+)/);
                
                if (page && page[1]) {
                    self.applyFilters(parseInt(page[1]));
                } else {
                    self.applyFilters(1);
                }
            });

            // Handle orderby change
            $(document).on('change', '.woocommerce-ordering select', function() {
                self.applyFilters();
            });

            // Handle instant filtering for checkboxes
            if (aqualuxeFilters.instantFiltering) {
                $(document).on('change', '.filter-section input[type="checkbox"]', function() {
                    self.applyFilters();
                });
            }
        },

        /**
         * Initialize Price Slider
         */
        initPriceSlider: function() {
            var self = this;
            
            // Check if price range slider exists and jQuery UI slider is available
            if ($('.price-range-slider').length === 0 || !$.fn.slider) {
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
                    },
                    change: function(event, ui) {
                        // Apply filters on change if instant filtering is enabled
                        if (aqualuxeFilters.instantFiltering) {
                            self.applyFilters();
                        }
                    }
                });
            });
        },

        /**
         * Apply Filters
         *
         * @param {number} page Page number
         */
        applyFilters: function(page) {
            var self = this;
            var formData = this.filterForm.serialize();
            var $overlay = $('<div class="woocommerce-overlay"><div class="woocommerce-overlay-inner"><div class="woocommerce-overlay-loader"></div></div></div>');
            
            // Add page number if provided
            if (page) {
                formData += '&page=' + page;
            }
            
            // Add orderby if exists
            var orderby = $('.woocommerce-ordering select').val();
            if (orderby) {
                formData += '&orderby=' + orderby;
            }
            
            // Add action and nonce
            formData += '&action=aqualuxe_filter_products&nonce=' + aqualuxeFilters.nonce;
            
            // Show loading overlay
            this.shopContainer.append($overlay);
            $overlay.fadeIn(300);
            
            // Send AJAX request
            $.ajax({
                url: aqualuxeFilters.ajaxUrl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Update products
                        self.productsContainer.html(response.data.products_html);
                        
                        // Update result count
                        if (self.resultCount.length) {
                            self.resultCount.text(aqualuxeFilters.i18n.showing + ' ' + response.data.found_posts + ' ' + aqualuxeFilters.i18n.results);
                        }
                        
                        // Update pagination
                        if (response.data.pagination) {
                            if (self.pagination.length) {
                                self.pagination.html(response.data.pagination);
                            } else {
                                self.productsContainer.after(response.data.pagination);
                            }
                        } else {
                            self.pagination.remove();
                        }
                        
                        // Update active filters
                        if (response.data.active_filters) {
                            $('.active-filters').html(response.data.active_filters);
                        }
                        
                        // Close filter sidebar on mobile
                        if (window.innerWidth < 768) {
                            self.filterSidebar.addClass('hidden');
                        }
                        
                        // Scroll to top of products
                        $('html, body').animate({
                            scrollTop: self.shopContainer.offset().top - 100
                        }, 500);
                        
                        // Trigger custom event
                        $(document.body).trigger('aqualuxe_filters_applied', [response.data]);
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
         * Format Price
         *
         * @param {number} price Price to format
         * @return {string} Formatted price
         */
        formatPrice: function(price) {
            // Check if woocommerce_price_slider_params is available
            if (typeof woocommerce_price_slider_params !== 'undefined') {
                return woocommerce_price_slider_params.currency_format_symbol + price.toFixed(2);
            }
            
            // Fallback to default format
            return '$' + price.toFixed(2);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeFilters.init();
    });

})(jQuery);