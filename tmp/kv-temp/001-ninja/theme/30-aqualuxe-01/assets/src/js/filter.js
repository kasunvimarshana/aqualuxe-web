/**
 * AquaLuxe Advanced Product Filtering
 * 
 * AJAX-powered product filtering for the WooCommerce shop
 */
(function($) {
    'use strict';

    // Main filter object
    const AquaLuxeFilter = {
        // Initialize the filter functionality
        init: function() {
            this.filterContainer = $('.aqualuxe-filter-sidebar');
            this.filterButton = $('.aqualuxe-filter-button');
            this.filterClose = $('.aqualuxe-filter-close');
            this.filterOverlay = $('.aqualuxe-filter-overlay');
            this.filterApply = $('.aqualuxe-filter-apply');
            this.filterReset = $('.aqualuxe-filter-reset');
            this.productsContainer = $('.products');
            this.shopContainer = $('.woocommerce-shop');
            this.pagination = $('.woocommerce-pagination');
            this.orderby = $('.woocommerce-ordering');
            this.filterForm = $('#aqualuxe-filter-form');
            this.filterInputs = this.filterForm.find('input, select');
            this.priceSlider = $('#price-slider');
            this.priceMin = $('#price-min');
            this.priceMax = $('#price-max');
            this.isFiltering = false;
            this.currentFilters = {};
            this.activeFilters = $('.aqualuxe-active-filters');

            this.bindEvents();
            this.initPriceSlider();
            this.initRangeSliders();
            this.updateActiveFilters();
        },

        // Bind all events
        bindEvents: function() {
            const self = this;

            // Toggle filter sidebar
            this.filterButton.on('click', function(e) {
                e.preventDefault();
                self.openFilterSidebar();
            });

            // Close filter sidebar
            this.filterClose.on('click', function(e) {
                e.preventDefault();
                self.closeFilterSidebar();
            });

            // Close filter sidebar when clicking overlay
            this.filterOverlay.on('click', function(e) {
                e.preventDefault();
                self.closeFilterSidebar();
            });

            // Apply filters
            this.filterApply.on('click', function(e) {
                e.preventDefault();
                self.applyFilters();
                self.closeFilterSidebar();
            });

            // Reset filters
            this.filterReset.on('click', function(e) {
                e.preventDefault();
                self.resetFilters();
            });

            // Handle pagination clicks
            $(document).on('click', '.woocommerce-pagination a', function(e) {
                if (self.isAjaxEnabled()) {
                    e.preventDefault();
                    const page = $(this).attr('href').match(/page\/(\d+)/);
                    if (page) {
                        self.currentFilters.page = page[1];
                    } else {
                        const queryString = $(this).attr('href').split('?')[1];
                        if (queryString) {
                            const params = new URLSearchParams(queryString);
                            if (params.has('paged')) {
                                self.currentFilters.page = params.get('paged');
                            }
                        }
                    }
                    self.filterProducts();
                }
            });

            // Handle orderby change
            this.orderby.find('select').on('change', function() {
                if (self.isAjaxEnabled()) {
                    self.currentFilters.orderby = $(this).val();
                    self.filterProducts();
                }
            });

            // Handle filter changes for instant filtering
            this.filterInputs.on('change', function() {
                if (self.isAjaxEnabled() && $(this).data('instant-filter')) {
                    self.collectFilters();
                    self.filterProducts();
                }
            });

            // Remove active filter
            $(document).on('click', '.aqualuxe-active-filter-remove', function(e) {
                e.preventDefault();
                const filter = $(this).data('filter');
                const value = $(this).data('value');
                
                if (filter === 'price') {
                    self.priceMin.val('');
                    self.priceMax.val('');
                    if (self.priceSlider.length) {
                        self.priceSlider.slider('values', 0, self.priceSlider.data('min'));
                        self.priceSlider.slider('values', 1, self.priceSlider.data('max'));
                    }
                } else if (filter === 'rating') {
                    $('input[name="rating"]').prop('checked', false);
                } else {
                    $('input[name="' + filter + '"][value="' + value + '"]').prop('checked', false);
                    $('select[name="' + filter + '"]').val('');
                }
                
                self.collectFilters();
                self.filterProducts();
            });

            // Handle browser back/forward buttons
            $(window).on('popstate', function(e) {
                if (e.originalEvent.state) {
                    self.currentFilters = e.originalEvent.state;
                    self.filterProducts(false);
                }
            });
        },

        // Initialize price slider
        initPriceSlider: function() {
            if (!this.priceSlider.length) return;
            
            const self = this;
            const min = this.priceSlider.data('min');
            const max = this.priceSlider.data('max');
            const currentMin = this.priceSlider.data('current-min') || min;
            const currentMax = this.priceSlider.data('current-max') || max;
            
            this.priceSlider.slider({
                range: true,
                min: min,
                max: max,
                values: [currentMin, currentMax],
                slide: function(event, ui) {
                    self.priceMin.val(ui.values[0]);
                    self.priceMax.val(ui.values[1]);
                },
                change: function(event, ui) {
                    if (self.isAjaxEnabled() && event.originalEvent) {
                        self.collectFilters();
                        self.filterProducts();
                    }
                }
            });
            
            this.priceMin.val(this.priceSlider.slider('values', 0));
            this.priceMax.val(this.priceSlider.slider('values', 1));
            
            this.priceMin.on('change', function() {
                self.priceSlider.slider('values', 0, $(this).val());
            });
            
            this.priceMax.on('change', function() {
                self.priceSlider.slider('values', 1, $(this).val());
            });
        },

        // Initialize other range sliders
        initRangeSliders: function() {
            $('.aqualuxe-range-slider').each(function() {
                const slider = $(this);
                const min = slider.data('min');
                const max = slider.data('max');
                const currentMin = slider.data('current-min') || min;
                const currentMax = slider.data('current-max') || max;
                const minInput = $('#' + slider.data('min-input'));
                const maxInput = $('#' + slider.data('max-input'));
                
                slider.slider({
                    range: true,
                    min: min,
                    max: max,
                    values: [currentMin, currentMax],
                    slide: function(event, ui) {
                        minInput.val(ui.values[0]);
                        maxInput.val(ui.values[1]);
                    },
                    change: function(event, ui) {
                        if (event.originalEvent) {
                            minInput.trigger('change');
                        }
                    }
                });
                
                minInput.val(slider.slider('values', 0));
                maxInput.val(slider.slider('values', 1));
                
                minInput.on('change', function() {
                    slider.slider('values', 0, $(this).val());
                });
                
                maxInput.on('change', function() {
                    slider.slider('values', 1, $(this).val());
                });
            });
        },

        // Open filter sidebar
        openFilterSidebar: function() {
            this.filterContainer.addClass('active');
            this.filterOverlay.addClass('active');
            $('body').addClass('filter-sidebar-open');
        },

        // Close filter sidebar
        closeFilterSidebar: function() {
            this.filterContainer.removeClass('active');
            this.filterOverlay.removeClass('active');
            $('body').removeClass('filter-sidebar-open');
        },

        // Collect all filter values
        collectFilters: function() {
            const self = this;
            this.currentFilters = {};
            
            // Get all checked checkboxes
            this.filterForm.find('input[type="checkbox"]:checked').each(function() {
                const name = $(this).attr('name');
                const value = $(this).val();
                
                if (!self.currentFilters[name]) {
                    self.currentFilters[name] = [];
                }
                
                self.currentFilters[name].push(value);
            });
            
            // Get all selected dropdowns
            this.filterForm.find('select').each(function() {
                const name = $(this).attr('name');
                const value = $(this).val();
                
                if (value) {
                    self.currentFilters[name] = value;
                }
            });
            
            // Get price range
            if (this.priceMin.length && this.priceMax.length) {
                const min = this.priceMin.val();
                const max = this.priceMax.val();
                
                if (min || max) {
                    this.currentFilters.price = {
                        min: min,
                        max: max
                    };
                }
            }
            
            // Get rating
            const rating = this.filterForm.find('input[name="rating"]:checked').val();
            if (rating) {
                this.currentFilters.rating = rating;
            }
            
            // Get current page
            const page = this.pagination.find('.current').text();
            if (page) {
                this.currentFilters.page = page;
            }
            
            // Get orderby
            const orderby = this.orderby.find('select').val();
            if (orderby) {
                this.currentFilters.orderby = orderby;
            }
        },

        // Apply filters and trigger product filtering
        applyFilters: function() {
            this.collectFilters();
            this.filterProducts();
        },

        // Reset all filters
        resetFilters: function() {
            this.filterForm[0].reset();
            
            // Reset price slider
            if (this.priceSlider.length) {
                this.priceSlider.slider('values', 0, this.priceSlider.data('min'));
                this.priceSlider.slider('values', 1, this.priceSlider.data('max'));
                this.priceMin.val(this.priceSlider.data('min'));
                this.priceMax.val(this.priceSlider.data('max'));
            }
            
            // Reset range sliders
            $('.aqualuxe-range-slider').each(function() {
                const slider = $(this);
                slider.slider('values', 0, slider.data('min'));
                slider.slider('values', 1, slider.data('max'));
                $('#' + slider.data('min-input')).val(slider.data('min'));
                $('#' + slider.data('max-input')).val(slider.data('max'));
            });
            
            this.currentFilters = {};
            this.filterProducts();
        },

        // Filter products via AJAX
        filterProducts: function(updateHistory = true) {
            if (this.isFiltering) return;
            
            const self = this;
            this.isFiltering = true;
            
            // Show loading overlay
            this.showLoading();
            
            // Update URL and browser history
            if (updateHistory) {
                const url = this.buildFilterUrl();
                window.history.pushState(this.currentFilters, '', url);
            }
            
            // Make AJAX request
            $.ajax({
                url: aqualuxeFilter.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_filter_products',
                    nonce: aqualuxeFilter.nonce,
                    filters: this.currentFilters
                },
                success: function(response) {
                    if (response.success) {
                        // Update products
                        self.productsContainer.html(response.data.products);
                        
                        // Update pagination
                        if (response.data.pagination) {
                            self.pagination.html(response.data.pagination);
                        } else {
                            self.pagination.empty();
                        }
                        
                        // Update count
                        if (response.data.count) {
                            $('.woocommerce-result-count').html(response.data.count);
                        }
                        
                        // Update active filters
                        self.updateActiveFilters();
                        
                        // Scroll to top of products
                        $('html, body').animate({
                            scrollTop: self.shopContainer.offset().top - 100
                        }, 500);
                        
                        // Trigger event for other scripts
                        $(document.body).trigger('aqualuxe_filtered_products', [response.data]);
                    } else {
                        console.error('Error filtering products:', response.data);
                    }
                    
                    self.hideLoading();
                    self.isFiltering = false;
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    self.hideLoading();
                    self.isFiltering = false;
                }
            });
        },

        // Show loading overlay
        showLoading: function() {
            if (!$('.aqualuxe-filter-loading').length) {
                $('<div class="aqualuxe-filter-loading"><div class="aqualuxe-filter-loading-spinner"></div></div>').appendTo(this.shopContainer);
            }
            $('.aqualuxe-filter-loading').addClass('active');
        },

        // Hide loading overlay
        hideLoading: function() {
            $('.aqualuxe-filter-loading').removeClass('active');
        },

        // Build filter URL
        buildFilterUrl: function() {
            const baseUrl = window.location.href.split('?')[0];
            const params = new URLSearchParams();
            
            for (const [key, value] of Object.entries(this.currentFilters)) {
                if (Array.isArray(value)) {
                    value.forEach(val => {
                        params.append(key + '[]', val);
                    });
                } else if (typeof value === 'object' && value !== null) {
                    for (const [subKey, subValue] of Object.entries(value)) {
                        params.append(key + '_' + subKey, subValue);
                    }
                } else {
                    params.append(key, value);
                }
            }
            
            const queryString = params.toString();
            return queryString ? baseUrl + '?' + queryString : baseUrl;
        },

        // Update active filters display
        updateActiveFilters: function() {
            if (!this.activeFilters.length) return;
            
            let html = '';
            let hasFilters = false;
            
            // Process each filter type
            for (const [key, value] of Object.entries(this.currentFilters)) {
                if (key === 'page' || key === 'orderby') continue;
                
                hasFilters = true;
                
                if (key === 'price') {
                    const min = value.min || this.priceSlider.data('min');
                    const max = value.max || this.priceSlider.data('max');
                    html += '<span class="aqualuxe-active-filter">' +
                        '<span class="aqualuxe-active-filter-name">Price:</span> ' +
                        '<span class="aqualuxe-active-filter-value">' + aqualuxeFilter.currency + min + ' - ' + aqualuxeFilter.currency + max + '</span>' +
                        '<a href="#" class="aqualuxe-active-filter-remove" data-filter="price"><i class="fas fa-times"></i></a>' +
                        '</span>';
                } else if (key === 'rating') {
                    html += '<span class="aqualuxe-active-filter">' +
                        '<span class="aqualuxe-active-filter-name">Rating:</span> ' +
                        '<span class="aqualuxe-active-filter-value">' + value + '+ stars</span>' +
                        '<a href="#" class="aqualuxe-active-filter-remove" data-filter="rating"><i class="fas fa-times"></i></a>' +
                        '</span>';
                } else if (Array.isArray(value)) {
                    value.forEach(val => {
                        const label = this.getFilterLabel(key, val);
                        html += '<span class="aqualuxe-active-filter">' +
                            '<span class="aqualuxe-active-filter-name">' + this.formatFilterName(key) + ':</span> ' +
                            '<span class="aqualuxe-active-filter-value">' + label + '</span>' +
                            '<a href="#" class="aqualuxe-active-filter-remove" data-filter="' + key + '" data-value="' + val + '"><i class="fas fa-times"></i></a>' +
                            '</span>';
                    });
                } else {
                    const label = this.getFilterLabel(key, value);
                    html += '<span class="aqualuxe-active-filter">' +
                        '<span class="aqualuxe-active-filter-name">' + this.formatFilterName(key) + ':</span> ' +
                        '<span class="aqualuxe-active-filter-value">' + label + '</span>' +
                        '<a href="#" class="aqualuxe-active-filter-remove" data-filter="' + key + '" data-value="' + value + '"><i class="fas fa-times"></i></a>' +
                        '</span>';
                }
            }
            
            if (hasFilters) {
                html += '<a href="#" class="aqualuxe-active-filter-clear">Clear All</a>';
                this.activeFilters.html(html).show();
            } else {
                this.activeFilters.hide();
            }
            
            // Bind clear all event
            $('.aqualuxe-active-filter-clear').on('click', (e) => {
                e.preventDefault();
                this.resetFilters();
            });
        },

        // Format filter name for display
        formatFilterName: function(name) {
            name = name.replace(/[-_]/g, ' ');
            return name.charAt(0).toUpperCase() + name.slice(1);
        },

        // Get filter label from value
        getFilterLabel: function(key, value) {
            // Try to get label from select options
            const select = this.filterForm.find('select[name="' + key + '"]');
            if (select.length) {
                const option = select.find('option[value="' + value + '"]');
                if (option.length) {
                    return option.text();
                }
            }
            
            // Try to get label from radio/checkbox
            const input = this.filterForm.find('input[name="' + key + '"][value="' + value + '"]');
            if (input.length) {
                const label = input.next('label').text();
                if (label) {
                    return label;
                }
            }
            
            // Fallback to value
            return value;
        },

        // Check if AJAX filtering is enabled
        isAjaxEnabled: function() {
            return aqualuxeFilter.ajax_enabled === '1';
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeFilter.init();
    });

})(jQuery);