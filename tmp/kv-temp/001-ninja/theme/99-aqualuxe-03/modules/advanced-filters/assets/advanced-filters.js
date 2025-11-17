/**
 * AquaLuxe Advanced Filters Module
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Advanced Filters functionality
     */
    class AquaLuxeAdvancedFilters {
        constructor() {
            this.form = $('.filters-form');
            this.resultsContainer = $('.products');
            this.init();
        }

        /**
         * Initialize filters functionality
         */
        init() {
            this.bindEvents();
            this.initPriceSlider();
        }

        /**
         * Bind events
         */
        bindEvents() {
            // Form submission
            this.form.on('submit', this.handleFormSubmit.bind(this));
            
            // Filter toggle
            $('.filters-toggle').on('click', this.toggleFilters.bind(this));
            
            // Clear filters
            $('.clear-filters-btn').on('click', this.clearFilters.bind(this));
            
            // Live filtering
            this.form.find('input, select').on('change', this.debounce(this.handleFilterChange.bind(this), 300));
            
            // Price range inputs
            $('.price-input').on('input', this.handlePriceInput.bind(this));
        }

        /**
         * Handle form submission
         */
        handleFormSubmit(e) {
            e.preventDefault();
            this.applyFilters();
        }

        /**
         * Handle filter changes
         */
        handleFilterChange() {
            // Auto-apply filters on change (optional)
            // this.applyFilters();
        }

        /**
         * Apply filters
         */
        applyFilters() {
            const formData = new FormData(this.form[0]);
            const filters = {};
            
            // Convert FormData to object
            for (let [key, value] of formData.entries()) {
                if (key.includes('[]')) {
                    const cleanKey = key.replace('[]', '');
                    if (!filters[cleanKey]) {
                        filters[cleanKey] = [];
                    }
                    filters[cleanKey].push(value);
                } else {
                    filters[key] = value;
                }
            }

            this.showLoading();

            $.ajax({
                url: aqualuxe_filters.ajax_url,
                type: 'POST',
                data: {
                    action: 'filter_products',
                    filters: filters,
                    nonce: aqualuxe_filters.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.updateResults(response.data);
                        this.updateURL(filters);
                    } else {
                        this.showError(response.data || 'Error filtering products');
                    }
                },
                error: () => {
                    this.showError('Error filtering products');
                },
                complete: () => {
                    this.hideLoading();
                }
            });
        }

        /**
         * Update results
         */
        updateResults(data) {
            if (this.resultsContainer.length) {
                this.resultsContainer.html(data.content);
            } else {
                // If no specific results container, try to find products container
                const $productsContainer = $('.woocommerce-products-header').next();
                if ($productsContainer.length) {
                    $productsContainer.html(data.content);
                }
            }

            // Update results count
            this.updateResultsCount(data.found_posts);
            
            // Trigger custom event
            $(document).trigger('aqualuxe:filters:updated', [data]);
        }

        /**
         * Update results count
         */
        updateResultsCount(count) {
            const $countContainer = $('.results-count');
            if ($countContainer.length) {
                $countContainer.text(
                    count + ' ' + (count === 1 ? 'product' : 'products') + ' found'
                );
            }
        }

        /**
         * Update URL with filter parameters
         */
        updateURL(filters) {
            const url = new URL(window.location);
            
            // Clear existing filter params
            url.searchParams.delete('min_price');
            url.searchParams.delete('max_price');
            url.searchParams.delete('product_cat');
            url.searchParams.delete('stock_status');
            url.searchParams.delete('rating');
            
            // Add new filter params
            Object.keys(filters).forEach(key => {
                if (filters[key] && filters[key] !== '') {
                    if (Array.isArray(filters[key])) {
                        url.searchParams.set(key, filters[key].join(','));
                    } else {
                        url.searchParams.set(key, filters[key]);
                    }
                }
            });
            
            // Update URL without page reload
            window.history.replaceState({}, '', url);
        }

        /**
         * Clear all filters
         */
        clearFilters() {
            this.form[0].reset();
            this.resetPriceSlider();
            
            // Clear URL parameters
            const url = new URL(window.location);
            url.search = '';
            window.history.replaceState({}, '', url);
            
            // Reload page or apply empty filters
            window.location.href = aqualuxe_filters.shop_url;
        }

        /**
         * Toggle filters visibility
         */
        toggleFilters(e) {
            e.preventDefault();
            
            const $toggle = $(e.currentTarget);
            const $content = $('.filters-content');
            const isExpanded = $toggle.attr('aria-expanded') === 'true';
            
            $toggle.attr('aria-expanded', !isExpanded);
            $content.slideToggle(300);
            
            // Update toggle icon
            const $icon = $toggle.find('.toggle-icon');
            $icon.text(isExpanded ? '▼' : '▲');
        }

        /**
         * Initialize price slider
         */
        initPriceSlider() {
            const $minRange = $('.range-min');
            const $maxRange = $('.range-max');
            const $minInput = $('input[name="min_price"]');
            const $maxInput = $('input[name="max_price"]');
            
            if ($minRange.length && $maxRange.length) {
                // Sync range sliders with inputs
                $minRange.on('input', () => {
                    const minVal = parseInt($minRange.val());
                    const maxVal = parseInt($maxRange.val());
                    
                    if (minVal >= maxVal) {
                        $minRange.val(maxVal - 1);
                    }
                    
                    $minInput.val($minRange.val());
                });
                
                $maxRange.on('input', () => {
                    const minVal = parseInt($minRange.val());
                    const maxVal = parseInt($maxRange.val());
                    
                    if (maxVal <= minVal) {
                        $maxRange.val(minVal + 1);
                    }
                    
                    $maxInput.val($maxRange.val());
                });
            }
        }

        /**
         * Handle price input changes
         */
        handlePriceInput(e) {
            const $input = $(e.currentTarget);
            const value = parseInt($input.val());
            const name = $input.attr('name');
            
            if (name === 'min_price') {
                $('.range-min').val(value);
            } else if (name === 'max_price') {
                $('.range-max').val(value);
            }
        }

        /**
         * Reset price slider
         */
        resetPriceSlider() {
            const $minRange = $('.range-min');
            const $maxRange = $('.range-max');
            
            if ($minRange.length && $maxRange.length) {
                $minRange.val($minRange.attr('min'));
                $maxRange.val($maxRange.attr('max'));
            }
        }

        /**
         * Show loading state
         */
        showLoading() {
            this.form.addClass('loading');
            
            if (this.resultsContainer.length) {
                this.resultsContainer.prepend('<div class="filters-loading">' + aqualuxe_filters.loading_text + '</div>');
            }
        }

        /**
         * Hide loading state
         */
        hideLoading() {
            this.form.removeClass('loading');
            $('.filters-loading').remove();
        }

        /**
         * Show error message
         */
        showError(message) {
            // Remove existing error messages
            $('.filters-error').remove();
            
            const $error = $('<div class="filters-error alert alert-error">' + message + '</div>');
            this.form.before($error);
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                $error.fadeOut(() => {
                    $error.remove();
                });
            }, 5000);
        }

        /**
         * Debounce function
         */
        debounce(func, wait, immediate) {
            let timeout;
            return function executedFunction() {
                const context = this;
                const args = arguments;
                const later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                const callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        }
    }

    // Initialize when document is ready
    $(document).ready(() => {
        if (typeof aqualuxe_filters !== 'undefined' && $('.aqualuxe-product-filters').length) {
            window.AquaLuxeAdvancedFilters = new AquaLuxeAdvancedFilters();
        }
    });

})(jQuery);