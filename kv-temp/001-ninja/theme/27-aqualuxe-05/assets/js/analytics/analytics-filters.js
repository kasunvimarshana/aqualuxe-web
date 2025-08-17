/**
 * AquaLuxe Analytics Filters
 *
 * JavaScript file for handling filters in the analytics dashboard.
 */

(function($) {
    'use strict';

    // Analytics Filters Class
    const AquaLuxeAnalyticsFilters = {
        /**
         * Initialize filters
         */
        init: function() {
            this.initDateRangePicker();
            this.initProductFilter();
            this.initCategoryFilter();
            this.initCustomerFilter();
            this.initStatusFilter();
            this.initCompareToggle();
            this.initFilterReset();
            this.initFilterSave();
            this.initFilterPresets();
        },

        /**
         * Initialize date range picker
         */
        initDateRangePicker: function() {
            // Check if daterangepicker is available
            if (typeof $.fn.daterangepicker === 'undefined') {
                return;
            }
            
            // Get date format
            const dateFormat = aqualuxeAnalytics.dateFormat || 'YYYY-MM-DD';
            
            // Initialize single date pickers
            $('#start_date, #end_date, #compare_start_date, #compare_end_date').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoApply: true,
                locale: {
                    format: dateFormat
                }
            });
            
            // Initialize date range picker
            $('#date_range').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'This Quarter': [moment().startOf('quarter'), moment().endOf('quarter')],
                    'Last Quarter': [moment().subtract(1, 'quarter').startOf('quarter'), moment().subtract(1, 'quarter').endOf('quarter')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
                locale: {
                    format: dateFormat
                }
            }, function(start, end, label) {
                // Update single date inputs
                $('#start_date').val(start.format(dateFormat)).trigger('change');
                $('#end_date').val(end.format(dateFormat)).trigger('change');
                
                // If compare is enabled, set compare dates
                if ($('#compare_toggle').is(':checked')) {
                    const days = end.diff(start, 'days');
                    const compareEnd = moment(start).subtract(1, 'days');
                    const compareStart = moment(compareEnd).subtract(days, 'days');
                    
                    $('#compare_end_date').val(compareEnd.format(dateFormat)).trigger('change');
                    $('#compare_start_date').val(compareStart.format(dateFormat)).trigger('change');
                }
            });
        },

        /**
         * Initialize product filter
         */
        initProductFilter: function() {
            // Check if select2 is available
            if (typeof $.fn.select2 === 'undefined') {
                return;
            }
            
            // Initialize product select
            $('#product_filter').select2({
                placeholder: 'Select products',
                allowClear: true,
                ajax: {
                    url: ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            action: 'aqualuxe_analytics_search_products',
                            term: params.term,
                            page: params.page || 1,
                            nonce: aqualuxeAnalytics.nonce
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        
                        return {
                            results: data.data,
                            pagination: {
                                more: (params.page * 10) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2,
                templateResult: this.formatProductResult,
                templateSelection: this.formatProductSelection
            });
            
            // Handle product selection change
            $('#product_filter').on('change', function() {
                // Update hidden input with selected product IDs
                const selectedProducts = $(this).val();
                $('#product_ids').val(selectedProducts ? selectedProducts.join(',') : '');
            });
        },

        /**
         * Format product result in dropdown
         * 
         * @param {Object} product Product data
         * @return {jQuery|string} Formatted result
         */
        formatProductResult: function(product) {
            if (product.loading) {
                return product.text;
            }
            
            const $container = $(
                '<div class="select2-result-product">' +
                '<div class="select2-result-product__image">' +
                '<img src="' + (product.image || 'https://via.placeholder.com/32') + '" alt="' + product.text + '">' +
                '</div>' +
                '<div class="select2-result-product__info">' +
                '<div class="select2-result-product__title">' + product.text + '</div>' +
                '<div class="select2-result-product__meta">' +
                'SKU: ' + (product.sku || 'N/A') + ' | ' +
                'Price: ' + (product.price || 'N/A') +
                '</div>' +
                '</div>' +
                '</div>'
            );
            
            return $container;
        },

        /**
         * Format product selection in dropdown
         * 
         * @param {Object} product Product data
         * @return {string} Formatted selection
         */
        formatProductSelection: function(product) {
            return product.text;
        },

        /**
         * Initialize category filter
         */
        initCategoryFilter: function() {
            // Check if select2 is available
            if (typeof $.fn.select2 === 'undefined') {
                return;
            }
            
            // Initialize category select
            $('#category_filter').select2({
                placeholder: 'Select categories',
                allowClear: true,
                ajax: {
                    url: ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            action: 'aqualuxe_analytics_search_categories',
                            term: params.term,
                            page: params.page || 1,
                            nonce: aqualuxeAnalytics.nonce
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        
                        return {
                            results: data.data,
                            pagination: {
                                more: (params.page * 10) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2
            });
            
            // Handle category selection change
            $('#category_filter').on('change', function() {
                // Update hidden input with selected category IDs
                const selectedCategories = $(this).val();
                $('#category_ids').val(selectedCategories ? selectedCategories.join(',') : '');
            });
        },

        /**
         * Initialize customer filter
         */
        initCustomerFilter: function() {
            // Check if select2 is available
            if (typeof $.fn.select2 === 'undefined') {
                return;
            }
            
            // Initialize customer select
            $('#customer_filter').select2({
                placeholder: 'Select customers',
                allowClear: true,
                ajax: {
                    url: ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            action: 'aqualuxe_analytics_search_customers',
                            term: params.term,
                            page: params.page || 1,
                            nonce: aqualuxeAnalytics.nonce
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        
                        return {
                            results: data.data,
                            pagination: {
                                more: (params.page * 10) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2,
                templateResult: this.formatCustomerResult,
                templateSelection: this.formatCustomerSelection
            });
            
            // Handle customer selection change
            $('#customer_filter').on('change', function() {
                // Update hidden input with selected customer IDs
                const selectedCustomers = $(this).val();
                $('#customer_ids').val(selectedCustomers ? selectedCustomers.join(',') : '');
            });
        },

        /**
         * Format customer result in dropdown
         * 
         * @param {Object} customer Customer data
         * @return {jQuery|string} Formatted result
         */
        formatCustomerResult: function(customer) {
            if (customer.loading) {
                return customer.text;
            }
            
            const $container = $(
                '<div class="select2-result-customer">' +
                '<div class="select2-result-customer__avatar">' +
                '<img src="' + (customer.avatar || 'https://via.placeholder.com/32') + '" alt="' + customer.text + '">' +
                '</div>' +
                '<div class="select2-result-customer__info">' +
                '<div class="select2-result-customer__name">' + customer.text + '</div>' +
                '<div class="select2-result-customer__email">' + (customer.email || '') + '</div>' +
                '</div>' +
                '</div>'
            );
            
            return $container;
        },

        /**
         * Format customer selection in dropdown
         * 
         * @param {Object} customer Customer data
         * @return {string} Formatted selection
         */
        formatCustomerSelection: function(customer) {
            return customer.text;
        },

        /**
         * Initialize status filter
         */
        initStatusFilter: function() {
            // Handle status filter change
            $('.status-filter').on('change', function() {
                // Update hidden input with selected status
                const selectedStatus = $(this).val();
                $('#status').val(selectedStatus);
            });
        },

        /**
         * Initialize compare toggle
         */
        initCompareToggle: function() {
            // Handle compare toggle change
            $('#compare_toggle').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.compare-date-fields').show();
                    
                    // Set default compare dates if empty
                    if (!$('#compare_start_date').val() || !$('#compare_end_date').val()) {
                        const startDate = moment($('#start_date').val());
                        const endDate = moment($('#end_date').val());
                        const days = endDate.diff(startDate, 'days');
                        
                        const compareEndDate = moment(startDate).subtract(1, 'days');
                        const compareStartDate = moment(compareEndDate).subtract(days, 'days');
                        
                        $('#compare_end_date').val(compareEndDate.format('YYYY-MM-DD')).trigger('change');
                        $('#compare_start_date').val(compareStartDate.format('YYYY-MM-DD')).trigger('change');
                    }
                } else {
                    $('.compare-date-fields').hide();
                }
            });
            
            // Trigger initial state
            $('#compare_toggle').trigger('change');
        },

        /**
         * Initialize filter reset
         */
        initFilterReset: function() {
            // Handle filter reset button click
            $('.reset-filters').on('click', function(e) {
                e.preventDefault();
                
                // Reset date range
                const endDate = moment();
                const startDate = moment().subtract(30, 'days');
                
                $('#end_date').val(endDate.format('YYYY-MM-DD')).trigger('change');
                $('#start_date').val(startDate.format('YYYY-MM-DD')).trigger('change');
                
                // Reset compare dates
                $('#compare_toggle').prop('checked', false).trigger('change');
                
                // Reset product filter
                if ($('#product_filter').length) {
                    $('#product_filter').val(null).trigger('change');
                    $('#product_ids').val('');
                }
                
                // Reset category filter
                if ($('#category_filter').length) {
                    $('#category_filter').val(null).trigger('change');
                    $('#category_ids').val('');
                }
                
                // Reset customer filter
                if ($('#customer_filter').length) {
                    $('#customer_filter').val(null).trigger('change');
                    $('#customer_ids').val('');
                }
                
                // Reset status filter
                if ($('.status-filter').length) {
                    $('.status-filter').val('all').trigger('change');
                    $('#status').val('all');
                }
                
                // Submit form
                $(this).closest('form').submit();
            });
        },

        /**
         * Initialize filter save
         */
        initFilterSave: function() {
            // Handle save filter button click
            $('.save-filters').on('click', function(e) {
                e.preventDefault();
                
                // Show save filter modal
                $('#save-filter-modal').show();
            });
            
            // Handle save filter form submit
            $('#save-filter-form').on('submit', function(e) {
                e.preventDefault();
                
                const filterName = $('#filter_name').val();
                
                if (!filterName) {
                    alert('Please enter a name for this filter.');
                    return;
                }
                
                // Get current filter values
                const filterData = {
                    name: filterName,
                    start_date: $('#start_date').val(),
                    end_date: $('#end_date').val(),
                    compare: $('#compare_toggle').is(':checked'),
                    compare_start_date: $('#compare_start_date').val(),
                    compare_end_date: $('#compare_end_date').val(),
                    product_ids: $('#product_ids').val(),
                    category_ids: $('#category_ids').val(),
                    customer_ids: $('#customer_ids').val(),
                    status: $('#status').val()
                };
                
                // Save filter
                $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    data: {
                        action: 'aqualuxe_analytics_save_filter',
                        filter_data: filterData,
                        nonce: aqualuxeAnalytics.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // Close modal
                            $('#save-filter-modal').hide();
                            
                            // Reset form
                            $('#filter_name').val('');
                            
                            // Show success message
                            alert('Filter saved successfully!');
                            
                            // Reload saved filters
                            AquaLuxeAnalyticsFilters.loadSavedFilters();
                        } else {
                            alert('Error saving filter: ' + response.data);
                        }
                    },
                    error: function() {
                        alert('Error saving filter. Please try again.');
                    }
                });
            });
            
            // Handle modal close
            $('.modal-close, .modal-cancel').on('click', function() {
                $(this).closest('.modal').hide();
            });
        },

        /**
         * Initialize filter presets
         */
        initFilterPresets: function() {
            // Load saved filters
            this.loadSavedFilters();
            
            // Handle filter preset selection
            $(document).on('click', '.filter-preset', function(e) {
                e.preventDefault();
                
                const filterId = $(this).data('id');
                
                // Load filter
                $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    data: {
                        action: 'aqualuxe_analytics_load_filter',
                        filter_id: filterId,
                        nonce: aqualuxeAnalytics.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            const filter = response.data;
                            
                            // Set filter values
                            $('#start_date').val(filter.start_date).trigger('change');
                            $('#end_date').val(filter.end_date).trigger('change');
                            
                            $('#compare_toggle').prop('checked', filter.compare).trigger('change');
                            
                            if (filter.compare) {
                                $('#compare_start_date').val(filter.compare_start_date).trigger('change');
                                $('#compare_end_date').val(filter.compare_end_date).trigger('change');
                            }
                            
                            // Set product filter
                            if (filter.product_ids && $('#product_filter').length) {
                                // Load product data
                                $.ajax({
                                    url: ajaxurl,
                                    method: 'POST',
                                    data: {
                                        action: 'aqualuxe_analytics_get_products',
                                        product_ids: filter.product_ids,
                                        nonce: aqualuxeAnalytics.nonce
                                    },
                                    success: function(productResponse) {
                                        if (productResponse.success) {
                                            const products = productResponse.data;
                                            
                                            // Create options and set selected
                                            const productIds = filter.product_ids.split(',');
                                            
                                            $('#product_ids').val(filter.product_ids);
                                            
                                            // Clear and set new options
                                            $('#product_filter').empty();
                                            
                                            $.each(products, function(index, product) {
                                                const option = new Option(product.text, product.id, true, true);
                                                $('#product_filter').append(option);
                                            });
                                            
                                            $('#product_filter').trigger('change');
                                        }
                                    }
                                });
                            }
                            
                            // Set category filter
                            if (filter.category_ids && $('#category_filter').length) {
                                // Load category data
                                $.ajax({
                                    url: ajaxurl,
                                    method: 'POST',
                                    data: {
                                        action: 'aqualuxe_analytics_get_categories',
                                        category_ids: filter.category_ids,
                                        nonce: aqualuxeAnalytics.nonce
                                    },
                                    success: function(categoryResponse) {
                                        if (categoryResponse.success) {
                                            const categories = categoryResponse.data;
                                            
                                            // Create options and set selected
                                            const categoryIds = filter.category_ids.split(',');
                                            
                                            $('#category_ids').val(filter.category_ids);
                                            
                                            // Clear and set new options
                                            $('#category_filter').empty();
                                            
                                            $.each(categories, function(index, category) {
                                                const option = new Option(category.text, category.id, true, true);
                                                $('#category_filter').append(option);
                                            });
                                            
                                            $('#category_filter').trigger('change');
                                        }
                                    }
                                });
                            }
                            
                            // Set customer filter
                            if (filter.customer_ids && $('#customer_filter').length) {
                                // Load customer data
                                $.ajax({
                                    url: ajaxurl,
                                    method: 'POST',
                                    data: {
                                        action: 'aqualuxe_analytics_get_customers',
                                        customer_ids: filter.customer_ids,
                                        nonce: aqualuxeAnalytics.nonce
                                    },
                                    success: function(customerResponse) {
                                        if (customerResponse.success) {
                                            const customers = customerResponse.data;
                                            
                                            // Create options and set selected
                                            const customerIds = filter.customer_ids.split(',');
                                            
                                            $('#customer_ids').val(filter.customer_ids);
                                            
                                            // Clear and set new options
                                            $('#customer_filter').empty();
                                            
                                            $.each(customers, function(index, customer) {
                                                const option = new Option(customer.text, customer.id, true, true);
                                                $('#customer_filter').append(option);
                                            });
                                            
                                            $('#customer_filter').trigger('change');
                                        }
                                    }
                                });
                            }
                            
                            // Set status filter
                            if (filter.status && $('.status-filter').length) {
                                $('.status-filter').val(filter.status).trigger('change');
                                $('#status').val(filter.status);
                            }
                            
                            // Submit form
                            setTimeout(function() {
                                $('.apply-filters').closest('form').submit();
                            }, 500);
                        } else {
                            alert('Error loading filter: ' + response.data);
                        }
                    },
                    error: function() {
                        alert('Error loading filter. Please try again.');
                    }
                });
            });
            
            // Handle filter preset delete
            $(document).on('click', '.delete-filter-preset', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if (!confirm('Are you sure you want to delete this filter preset?')) {
                    return;
                }
                
                const filterId = $(this).data('id');
                
                // Delete filter
                $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    data: {
                        action: 'aqualuxe_analytics_delete_filter',
                        filter_id: filterId,
                        nonce: aqualuxeAnalytics.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // Reload saved filters
                            AquaLuxeAnalyticsFilters.loadSavedFilters();
                        } else {
                            alert('Error deleting filter: ' + response.data);
                        }
                    },
                    error: function() {
                        alert('Error deleting filter. Please try again.');
                    }
                });
            });
        },

        /**
         * Load saved filters
         */
        loadSavedFilters: function() {
            // Get saved filters
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'aqualuxe_analytics_get_saved_filters',
                    nonce: aqualuxeAnalytics.nonce
                },
                success: function(response) {
                    if (response.success) {
                        const filters = response.data;
                        const $container = $('.saved-filters-list');
                        
                        // Clear container
                        $container.empty();
                        
                        if (filters.length === 0) {
                            $container.append('<p>No saved filters.</p>');
                            return;
                        }
                        
                        // Add filters
                        $.each(filters, function(index, filter) {
                            const $filter = $(
                                '<div class="saved-filter-item">' +
                                '<a href="#" class="filter-preset" data-id="' + filter.id + '">' +
                                filter.name +
                                '</a>' +
                                '<a href="#" class="delete-filter-preset" data-id="' + filter.id + '">' +
                                '<span class="dashicons dashicons-trash"></span>' +
                                '</a>' +
                                '</div>'
                            );
                            
                            $container.append($filter);
                        });
                    }
                }
            });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeAnalyticsFilters.init();
    });

})(jQuery);