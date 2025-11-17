/**
 * AquaLuxe Theme Product Filter
 *
 * Handles AJAX product filtering functionality.
 */

(function($) {
    'use strict';

    const AqualuxeProductFilter = {
        /**
         * Initialize the product filter functionality
         */
        init: function() {
            this.filterForm = $('.aqualuxe-filter-form');
            this.productsContainer = $('.products');
            this.paginationContainer = $('.woocommerce-pagination');
            this.resultCount = $('.woocommerce-result-count');
            this.shopTopbar = $('.shop-topbar');
            this.activeFilters = $('.active-filters');
            this.isAjaxRunning = false;
            
            if (this.filterForm.length) {
                this.bindEvents();
                this.initRangeSliders();
                this.initMobileFilters();
                this.updateActiveFilters();
            }
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Filter form change event
            $(document).on('change', '.aqualuxe-filter-form input, .aqualuxe-filter-form select', this.handleFilterChange.bind(this));
            
            // Price range slider change event
            $(document).on('price_slider_change', this.handleFilterChange.bind(this));
            
            // Pagination click event
            $(document).on('click', '.woocommerce-pagination a', this.handlePaginationClick.bind(this));
            
            // Ordering change event
            $(document).on('change', '.woocommerce-ordering select.orderby', this.handleOrderingChange.bind(this));
            
            // Reset filters click event
            $(document).on('click', '.reset-filters', this.resetFilters.bind(this));
            
            // Remove active filter click event
            $(document).on('click', '.active-filter-item .remove', this.removeActiveFilter.bind(this));
            
            // View switcher click event
            $(document).on('click', '.view-switcher a', this.handleViewSwitch.bind(this));
            
            // Mobile filter toggle
            $(document).on('click', '.mobile-filter-toggle', this.toggleMobileFilters.bind(this));
        },

        /**
         * Handle filter form change
         * 
         * @param {Event} e - The change event
         */
        handleFilterChange: function(e) {
            e.preventDefault();
            
            // Don't trigger multiple AJAX requests
            if (this.isAjaxRunning) {
                return;
            }
            
            this.isAjaxRunning = true;
            this.showLoading();
            
            // Get all form data
            const formData = this.filterForm.serialize();
            
            // Add current page to form data
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(window.location.search);
            
            // Update browser history
            this.updateBrowserHistory(formData);
            
            // Make AJAX request
            $.ajax({
                url: aqualuxe_filter.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_filter_products',
                    form_data: formData,
                    security: aqualuxe_filter.nonce
                },
                success: this.handleAjaxSuccess.bind(this),
                error: this.handleAjaxError.bind(this)
            });
        },

        /**
         * Handle pagination click
         * 
         * @param {Event} e - The click event
         */
        handlePaginationClick: function(e) {
            e.preventDefault();
            
            if (this.isAjaxRunning) {
                return;
            }
            
            this.isAjaxRunning = true;
            this.showLoading();
            
            const pageUrl = $(e.currentTarget).attr('href');
            const pageNum = this.getParameterByName('paged', pageUrl) || this.getParameterByName('page', pageUrl) || 1;
            
            // Get all form data
            let formData = this.filterForm.serialize();
            
            // Add page number to form data
            formData += '&paged=' + pageNum;
            
            // Update browser history
            this.updateBrowserHistory(formData);
            
            // Make AJAX request
            $.ajax({
                url: aqualuxe_filter.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_filter_products',
                    form_data: formData,
                    security: aqualuxe_filter.nonce
                },
                success: this.handleAjaxSuccess.bind(this),
                error: this.handleAjaxError.bind(this)
            });
        },

        /**
         * Handle ordering change
         * 
         * @param {Event} e - The change event
         */
        handleOrderingChange: function(e) {
            e.preventDefault();
            
            if (this.isAjaxRunning) {
                return;
            }
            
            this.isAjaxRunning = true;
            this.showLoading();
            
            const orderby = $(e.currentTarget).val();
            
            // Get all form data
            let formData = this.filterForm.serialize();
            
            // Add orderby to form data
            formData += '&orderby=' + orderby;
            
            // Update browser history
            this.updateBrowserHistory(formData);
            
            // Make AJAX request
            $.ajax({
                url: aqualuxe_filter.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_filter_products',
                    form_data: formData,
                    security: aqualuxe_filter.nonce
                },
                success: this.handleAjaxSuccess.bind(this),
                error: this.handleAjaxError.bind(this)
            });
        },

        /**
         * Handle AJAX success
         * 
         * @param {Object} response - The AJAX response
         */
        handleAjaxSuccess: function(response) {
            if (response.success) {
                // Update products
                this.productsContainer.html(response.data.products);
                
                // Update pagination
                this.paginationContainer.html(response.data.pagination);
                
                // Update result count
                this.resultCount.html(response.data.result_count);
                
                // Update active filters
                this.updateActiveFilters();
                
                // Scroll to top of products
                $('html, body').animate({
                    scrollTop: this.productsContainer.offset().top - 100
                }, 500);
                
                // Trigger event for other scripts
                $(document).trigger('aqualuxe_products_updated');
            } else {
                console.error('Filter error:', response.data);
            }
            
            this.hideLoading();
            this.isAjaxRunning = false;
        },

        /**
         * Handle AJAX error
         * 
         * @param {Object} xhr - The XHR object
         * @param {string} status - The error status
         * @param {string} error - The error message
         */
        handleAjaxError: function(xhr, status, error) {
            console.error('Filter AJAX error:', error);
            this.hideLoading();
            this.isAjaxRunning = false;
        },

        /**
         * Reset all filters
         * 
         * @param {Event} e - The click event
         */
        resetFilters: function(e) {
            e.preventDefault();
            
            // Reset all form fields
            this.filterForm.get(0).reset();
            
            // Reset price slider if it exists
            if (typeof woocommerce_price_slider_params !== 'undefined') {
                // Reset price slider to min/max values
                $('.price_slider').slider('values', 0, woocommerce_price_slider_params.min_price);
                $('.price_slider').slider('values', 1, woocommerce_price_slider_params.max_price);
                $('.price_slider_amount #min_price').val(woocommerce_price_slider_params.min_price);
                $('.price_slider_amount #max_price').val(woocommerce_price_slider_params.max_price);
                $('.price_slider_amount').trigger('price_slider_change');
            }
            
            // Trigger change event to update products
            this.handleFilterChange(e);
        },

        /**
         * Remove an active filter
         * 
         * @param {Event} e - The click event
         */
        removeActiveFilter: function(e) {
            e.preventDefault();
            
            const $this = $(e.currentTarget);
            const filterType = $this.data('filter-type');
            const filterValue = $this.data('filter-value');
            
            if (filterType === 'price') {
                // Reset price slider
                if (typeof woocommerce_price_slider_params !== 'undefined') {
                    $('.price_slider').slider('values', 0, woocommerce_price_slider_params.min_price);
                    $('.price_slider').slider('values', 1, woocommerce_price_slider_params.max_price);
                    $('.price_slider_amount #min_price').val(woocommerce_price_slider_params.min_price);
                    $('.price_slider_amount #max_price').val(woocommerce_price_slider_params.max_price);
                    $('.price_slider_amount').trigger('price_slider_change');
                }
            } else if (filterType === 'orderby') {
                // Reset ordering
                $('.woocommerce-ordering select.orderby').val('menu_order');
            } else {
                // Find and uncheck the corresponding filter input
                this.filterForm.find('input[name="' + filterType + '"][value="' + filterValue + '"]').prop('checked', false);
                
                // For select elements
                this.filterForm.find('select[name="' + filterType + '"]').val('');
            }
            
            // Trigger change event to update products
            this.handleFilterChange(e);
        },

        /**
         * Update active filters display
         */
        updateActiveFilters: function() {
            if (!this.activeFilters.length) {
                return;
            }
            
            const activeFiltersHtml = [];
            let hasActiveFilters = false;
            
            // Check all form inputs
            this.filterForm.find('input:checked, select').each(function() {
                const $this = $(this);
                const name = $this.attr('name');
                const value = $this.val();
                
                // Skip empty values and pagination
                if (!value || name === 'paged') {
                    return;
                }
                
                // Skip price slider hidden inputs
                if (name === 'min_price' || name === 'max_price') {
                    return;
                }
                
                let label = '';
                
                // For checkboxes, get the label text
                if ($this.is(':checkbox')) {
                    label = $this.next('label').text().trim();
                    hasActiveFilters = true;
                    
                    activeFiltersHtml.push(
                        '<span class="active-filter-item">' +
                        '<span class="filter-label">' + label + '</span>' +
                        '<a href="#" class="remove" data-filter-type="' + name + '" data-filter-value="' + value + '">&times;</a>' +
                        '</span>'
                    );
                }
                
                // For select elements, get the selected option text
                if ($this.is('select') && $this.val() !== '') {
                    label = $this.find('option:selected').text().trim();
                    hasActiveFilters = true;
                    
                    activeFiltersHtml.push(
                        '<span class="active-filter-item">' +
                        '<span class="filter-label">' + label + '</span>' +
                        '<a href="#" class="remove" data-filter-type="' + name + '" data-filter-value="' + value + '">&times;</a>' +
                        '</span>'
                    );
                }
            });
            
            // Check price slider
            const minPrice = this.filterForm.find('input[name="min_price"]').val();
            const maxPrice = this.filterForm.find('input[name="max_price"]').val();
            
            if (minPrice && maxPrice && 
                (minPrice !== woocommerce_price_slider_params.min_price || 
                 maxPrice !== woocommerce_price_slider_params.max_price)) {
                
                hasActiveFilters = true;
                
                const currencySymbol = woocommerce_price_slider_params.currency_symbol;
                
                activeFiltersHtml.push(
                    '<span class="active-filter-item">' +
                    '<span class="filter-label">' + currencySymbol + minPrice + ' - ' + currencySymbol + maxPrice + '</span>' +
                    '<a href="#" class="remove" data-filter-type="price" data-filter-value="">&times;</a>' +
                    '</span>'
                );
            }
            
            // Check orderby
            const orderby = $('.woocommerce-ordering select.orderby').val();
            
            if (orderby && orderby !== 'menu_order') {
                hasActiveFilters = true;
                
                const orderbylabel = $('.woocommerce-ordering select.orderby option:selected').text().trim();
                
                activeFiltersHtml.push(
                    '<span class="active-filter-item">' +
                    '<span class="filter-label">' + aqualuxe_filter.sort_by + ': ' + orderbylabel + '</span>' +
                    '<a href="#" class="remove" data-filter-type="orderby" data-filter-value="">&times;</a>' +
                    '</span>'
                );
            }
            
            // Update active filters container
            if (hasActiveFilters) {
                this.activeFilters.html(
                    '<div class="active-filters-inner">' +
                    '<span class="active-filters-title">' + aqualuxe_filter.active_filters + ':</span>' +
                    activeFiltersHtml.join('') +
                    '<a href="#" class="reset-filters">' + aqualuxe_filter.reset_filters + '</a>' +
                    '</div>'
                ).show();
            } else {
                this.activeFilters.html('').hide();
            }
        },

        /**
         * Initialize price range sliders
         */
        initRangeSliders: function() {
            // Price range slider is handled by WooCommerce
            
            // Custom range sliders
            $('.range-slider').each(function() {
                const $this = $(this);
                const $slider = $this.find('.slider-range');
                const $minInput = $this.find('.min-value');
                const $maxInput = $this.find('.max-value');
                const min = parseInt($slider.data('min'), 10);
                const max = parseInt($slider.data('max'), 10);
                const step = parseInt($slider.data('step'), 10) || 1;
                
                $slider.slider({
                    range: true,
                    min: min,
                    max: max,
                    step: step,
                    values: [$minInput.val() || min, $maxInput.val() || max],
                    slide: function(event, ui) {
                        $minInput.val(ui.values[0]);
                        $maxInput.val(ui.values[1]);
                    },
                    change: function(event, ui) {
                        $minInput.val(ui.values[0]).trigger('change');
                        $maxInput.val(ui.values[1]);
                    }
                });
                
                // Update slider when inputs change
                $minInput.on('change', function() {
                    const value = parseInt($(this).val(), 10);
                    const currentValues = $slider.slider('values');
                    
                    if (value < min) {
                        $(this).val(min);
                        $slider.slider('values', 0, min);
                    } else if (value > currentValues[1]) {
                        $(this).val(currentValues[1]);
                        $slider.slider('values', 0, currentValues[1]);
                    } else {
                        $slider.slider('values', 0, value);
                    }
                });
                
                $maxInput.on('change', function() {
                    const value = parseInt($(this).val(), 10);
                    const currentValues = $slider.slider('values');
                    
                    if (value > max) {
                        $(this).val(max);
                        $slider.slider('values', 1, max);
                    } else if (value < currentValues[0]) {
                        $(this).val(currentValues[0]);
                        $slider.slider('values', 1, currentValues[0]);
                    } else {
                        $slider.slider('values', 1, value);
                    }
                });
            });
        },

        /**
         * Initialize mobile filters
         */
        initMobileFilters: function() {
            // Add mobile filter toggle button
            if ($('.shop-sidebar').length && !$('.mobile-filter-toggle').length) {
                this.shopTopbar.append(
                    '<button class="mobile-filter-toggle lg:hidden btn btn-outline flex items-center">' +
                    '<svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">' +
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />' +
                    '</svg>' +
                    aqualuxe_filter.filter_products +
                    '</button>'
                );
                
                // Add close button to mobile filters
                $('.shop-sidebar').prepend(
                    '<button class="mobile-filter-close lg:hidden absolute top-4 right-4 text-dark-500 dark:text-dark-400 hover:text-dark-700 dark:hover:text-dark-200">' +
                    '<svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">' +
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />' +
                    '</svg>' +
                    '</button>'
                );
                
                // Add mobile filter overlay
                $('body').append('<div class="mobile-filter-overlay fixed inset-0 bg-dark-900 bg-opacity-50 z-40 hidden lg:hidden"></div>');
                
                // Close mobile filters when clicking overlay
                $(document).on('click', '.mobile-filter-overlay, .mobile-filter-close', this.closeMobileFilters.bind(this));
            }
        },

        /**
         * Toggle mobile filters
         * 
         * @param {Event} e - The click event
         */
        toggleMobileFilters: function(e) {
            e.preventDefault();
            
            $('.shop-sidebar').toggleClass('mobile-active');
            $('.mobile-filter-overlay').toggleClass('hidden');
            $('body').toggleClass('overflow-hidden');
        },

        /**
         * Close mobile filters
         */
        closeMobileFilters: function() {
            $('.shop-sidebar').removeClass('mobile-active');
            $('.mobile-filter-overlay').addClass('hidden');
            $('body').removeClass('overflow-hidden');
        },

        /**
         * Handle view switch (grid/list)
         * 
         * @param {Event} e - The click event
         */
        handleViewSwitch: function(e) {
            e.preventDefault();
            
            const $this = $(e.currentTarget);
            const view = $this.data('view');
            
            // Update active state
            $('.view-switcher a').removeClass('active');
            $this.addClass('active');
            
            // Update products container class
            this.productsContainer.removeClass('grid-view list-view').addClass(view + '-view');
            
            // Save preference in cookie
            this.setCookie('aqualuxe_product_view', view, 30);
        },

        /**
         * Update browser history
         * 
         * @param {string} formData - The form data
         */
        updateBrowserHistory: function(formData) {
            if (window.history && window.history.pushState) {
                const newUrl = window.location.protocol + '//' + window.location.host + window.location.pathname + '?' + formData;
                window.history.pushState({ path: newUrl }, '', newUrl);
            }
        },

        /**
         * Show loading overlay
         */
        showLoading: function() {
            if ($('.aqualuxe-loading-overlay').length === 0) {
                this.productsContainer.append('<div class="aqualuxe-loading-overlay absolute inset-0 bg-white dark:bg-dark-900 bg-opacity-70 dark:bg-opacity-70 flex items-center justify-center z-10"><div class="loading-spinner"></div></div>');
            }
        },

        /**
         * Hide loading overlay
         */
        hideLoading: function() {
            $('.aqualuxe-loading-overlay').fadeOut(200, function() {
                $(this).remove();
            });
        },

        /**
         * Get URL parameter by name
         * 
         * @param {string} name - The parameter name
         * @param {string} url - The URL to parse
         * @returns {string|null} - The parameter value
         */
        getParameterByName: function(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, '\\$&');
            const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
            const results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        },

        /**
         * Set cookie
         * 
         * @param {string} name - The cookie name
         * @param {string} value - The cookie value
         * @param {number} days - The cookie expiration in days
         */
        setCookie: function(name, value, days) {
            let expires = '';
            
            if (days) {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = '; expires=' + date.toUTCString();
            }
            
            document.cookie = name + '=' + (value || '') + expires + '; path=/';
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AqualuxeProductFilter.init();
    });

})(jQuery);