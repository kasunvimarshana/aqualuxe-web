/**
 * AquaLuxe Services Module Frontend JavaScript
 */

(function($) {
    'use strict';

    /**
     * AquaLuxe Services
     */
    var AquaLuxeServices = {
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
            this.initBookingForm();
            this.initServiceComparison();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Service booking form submission
            $(document).on('submit', '.aqualuxe-service-booking-form', this.handleBookingFormSubmit);
            
            // Service filter change
            $(document).on('change', '.aqualuxe-service-filter select', this.handleServiceFilterChange);
            
            // Service search
            $(document).on('input', '.aqualuxe-service-search input', this.handleServiceSearch);
            
            // Service sort
            $(document).on('change', '.aqualuxe-service-sort select', this.handleServiceSort);
        },

        /**
         * Initialize booking form
         */
        initBookingForm: function() {
            var $bookingForm = $('.aqualuxe-service-booking-form');
            
            if ($bookingForm.length === 0) {
                return;
            }

            // Initialize date picker
            if ($.fn.datepicker) {
                $('.aqualuxe-service-booking-date').datepicker({
                    dateFormat: 'yy-mm-dd',
                    minDate: 0,
                    beforeShowDay: function(date) {
                        // Check if date is available
                        return [true, ''];
                    }
                });
            }

            // Initialize time picker
            if ($.fn.timepicker) {
                $('.aqualuxe-service-booking-time').timepicker({
                    timeFormat: 'HH:mm',
                    interval: 30,
                    minTime: '9:00',
                    maxTime: '18:00',
                    dynamic: false,
                    dropdown: true,
                    scrollbar: true
                });
            }

            // Update available times when date changes
            $('.aqualuxe-service-booking-date').on('change', function() {
                var serviceId = $bookingForm.data('service-id');
                var date = $(this).val();
                
                if (!serviceId || !date) {
                    return;
                }

                AquaLuxeServices.getAvailableTimes(serviceId, date);
            });
        },

        /**
         * Initialize service comparison
         */
        initServiceComparison: function() {
            var $comparison = $('.aqualuxe-service-comparison');
            
            if ($comparison.length === 0) {
                return;
            }

            // Add service to comparison
            $('.add-to-comparison').on('click', function(e) {
                e.preventDefault();
                
                var serviceId = $(this).data('service-id');
                var serviceTitle = $(this).data('service-title');
                
                if (!serviceId) {
                    return;
                }

                // Check if service is already in comparison
                if (AquaLuxeServices.isServiceInComparison(serviceId)) {
                    alert('This service is already in the comparison.');
                    return;
                }

                // Add service to comparison
                AquaLuxeServices.addServiceToComparison(serviceId, serviceTitle);
            });

            // Remove service from comparison
            $(document).on('click', '.remove-from-comparison', function(e) {
                e.preventDefault();
                
                var serviceId = $(this).data('service-id');
                
                if (!serviceId) {
                    return;
                }

                // Remove service from comparison
                AquaLuxeServices.removeServiceFromComparison(serviceId);
            });

            // View comparison
            $('.view-comparison').on('click', function(e) {
                e.preventDefault();
                
                var services = AquaLuxeServices.getComparisonServices();
                
                if (services.length < 2) {
                    alert('Please add at least 2 services to compare.');
                    return;
                }

                // Redirect to comparison page
                window.location.href = $(this).attr('href') + '?ids=' + services.join(',');
            });
        },

        /**
         * Handle booking form submit
         * 
         * @param {Event} e 
         */
        handleBookingFormSubmit: function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $submit = $form.find('button[type="submit"]');
            var formData = $form.serialize();
            
            // Disable submit button
            $submit.prop('disabled', true).text('Processing...');
            
            // Submit form
            $.ajax({
                url: aqualuxeServices.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_service_booking',
                    nonce: aqualuxeServices.nonce,
                    formData: formData
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        $form.html('<div class="aqualuxe-service-booking-success">' + response.data.message + '</div>');
                        
                        // Redirect if needed
                        if (response.data.redirect) {
                            setTimeout(function() {
                                window.location.href = response.data.redirect;
                            }, 2000);
                        }
                    } else {
                        // Show error message
                        $form.find('.aqualuxe-service-booking-error').remove();
                        $form.prepend('<div class="aqualuxe-service-booking-error">' + response.data.message + '</div>');
                        
                        // Enable submit button
                        $submit.prop('disabled', false).text('Book Now');
                    }
                },
                error: function() {
                    // Show error message
                    $form.find('.aqualuxe-service-booking-error').remove();
                    $form.prepend('<div class="aqualuxe-service-booking-error">An error occurred. Please try again.</div>');
                    
                    // Enable submit button
                    $submit.prop('disabled', false).text('Book Now');
                }
            });
        },

        /**
         * Handle service filter change
         * 
         * @param {Event} e 
         */
        handleServiceFilterChange: function(e) {
            var $filter = $(this);
            var category = $filter.val();
            var $grid = $('.aqualuxe-service-grid');
            
            if (!$grid.length) {
                return;
            }

            // Show loading
            $grid.addClass('loading');
            
            // Filter services
            $.ajax({
                url: aqualuxeServices.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_filter_services',
                    nonce: aqualuxeServices.nonce,
                    category: category
                },
                success: function(response) {
                    if (response.success) {
                        // Update grid
                        $grid.html(response.data.html);
                    }
                    
                    // Hide loading
                    $grid.removeClass('loading');
                },
                error: function() {
                    // Hide loading
                    $grid.removeClass('loading');
                }
            });
        },

        /**
         * Handle service search
         * 
         * @param {Event} e 
         */
        handleServiceSearch: function(e) {
            var $search = $(this);
            var query = $search.val();
            var $grid = $('.aqualuxe-service-grid');
            
            if (!$grid.length) {
                return;
            }

            // Clear timeout
            clearTimeout(AquaLuxeServices.searchTimeout);
            
            // Set timeout
            AquaLuxeServices.searchTimeout = setTimeout(function() {
                // Show loading
                $grid.addClass('loading');
                
                // Search services
                $.ajax({
                    url: aqualuxeServices.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_search_services',
                        nonce: aqualuxeServices.nonce,
                        query: query
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update grid
                            $grid.html(response.data.html);
                        }
                        
                        // Hide loading
                        $grid.removeClass('loading');
                    },
                    error: function() {
                        // Hide loading
                        $grid.removeClass('loading');
                    }
                });
            }, 500);
        },

        /**
         * Handle service sort
         * 
         * @param {Event} e 
         */
        handleServiceSort: function(e) {
            var $sort = $(this);
            var orderby = $sort.val();
            var $grid = $('.aqualuxe-service-grid');
            
            if (!$grid.length) {
                return;
            }

            // Show loading
            $grid.addClass('loading');
            
            // Sort services
            $.ajax({
                url: aqualuxeServices.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_sort_services',
                    nonce: aqualuxeServices.nonce,
                    orderby: orderby
                },
                success: function(response) {
                    if (response.success) {
                        // Update grid
                        $grid.html(response.data.html);
                    }
                    
                    // Hide loading
                    $grid.removeClass('loading');
                },
                error: function() {
                    // Hide loading
                    $grid.removeClass('loading');
                }
            });
        },

        /**
         * Get available times
         * 
         * @param {int} serviceId 
         * @param {string} date 
         */
        getAvailableTimes: function(serviceId, date) {
            var $timeSelect = $('.aqualuxe-service-booking-time');
            
            if (!$timeSelect.length) {
                return;
            }

            // Show loading
            $timeSelect.prop('disabled', true);
            
            // Get available times
            $.ajax({
                url: aqualuxeServices.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_available_times',
                    nonce: aqualuxeServices.nonce,
                    service_id: serviceId,
                    date: date
                },
                success: function(response) {
                    if (response.success) {
                        // Update time select
                        $timeSelect.html('');
                        
                        $.each(response.data.times, function(index, time) {
                            $timeSelect.append('<option value="' + time.value + '">' + time.label + '</option>');
                        });
                    }
                    
                    // Hide loading
                    $timeSelect.prop('disabled', false);
                },
                error: function() {
                    // Hide loading
                    $timeSelect.prop('disabled', false);
                }
            });
        },

        /**
         * Check if service is in comparison
         * 
         * @param {int} serviceId 
         * @returns {boolean}
         */
        isServiceInComparison: function(serviceId) {
            var services = this.getComparisonServices();
            return services.indexOf(serviceId.toString()) !== -1;
        },

        /**
         * Add service to comparison
         * 
         * @param {int} serviceId 
         * @param {string} serviceTitle 
         */
        addServiceToComparison: function(serviceId, serviceTitle) {
            var services = this.getComparisonServices();
            
            // Add service to array
            services.push(serviceId.toString());
            
            // Save to localStorage
            localStorage.setItem('aqualuxeServiceComparison', JSON.stringify(services));
            
            // Update comparison count
            this.updateComparisonCount();
            
            // Show notification
            this.showNotification(serviceTitle + ' added to comparison.');
        },

        /**
         * Remove service from comparison
         * 
         * @param {int} serviceId 
         */
        removeServiceFromComparison: function(serviceId) {
            var services = this.getComparisonServices();
            var index = services.indexOf(serviceId.toString());
            
            if (index !== -1) {
                // Remove service from array
                services.splice(index, 1);
                
                // Save to localStorage
                localStorage.setItem('aqualuxeServiceComparison', JSON.stringify(services));
                
                // Update comparison count
                this.updateComparisonCount();
                
                // Show notification
                this.showNotification('Service removed from comparison.');
                
                // Reload page if on comparison page
                if ($('.aqualuxe-service-comparison').length) {
                    window.location.reload();
                }
            }
        },

        /**
         * Get comparison services
         * 
         * @returns {array}
         */
        getComparisonServices: function() {
            var services = localStorage.getItem('aqualuxeServiceComparison');
            
            if (!services) {
                return [];
            }
            
            try {
                return JSON.parse(services);
            } catch (e) {
                return [];
            }
        },

        /**
         * Update comparison count
         */
        updateComparisonCount: function() {
            var services = this.getComparisonServices();
            var $count = $('.comparison-count');
            
            if ($count.length) {
                $count.text(services.length);
            }
        },

        /**
         * Show notification
         * 
         * @param {string} message 
         */
        showNotification: function(message) {
            var $notification = $('.aqualuxe-notification');
            
            if (!$notification.length) {
                $('body').append('<div class="aqualuxe-notification"></div>');
                $notification = $('.aqualuxe-notification');
            }
            
            $notification.text(message).addClass('show');
            
            setTimeout(function() {
                $notification.removeClass('show');
            }, 3000);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeServices.init();
    });

})(jQuery);