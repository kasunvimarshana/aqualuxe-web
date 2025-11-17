/**
 * Services Module Scripts
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

(function($) {
    'use strict';

    /**
     * Services Module
     */
    var AquaLuxeServices = {
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
            this.initMasonry();
            this.initFilters();
            this.initLoadMore();
            this.initBookingForm();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            $(document).on('click', '.aqualuxe-service-quick-view-button', this.quickView);
            $(document).on('click', '.aqualuxe-service-quick-view-close', this.closeQuickView);
            $(document).on('click', '.aqualuxe-service-booking-button', this.scrollToBookingForm);
        },

        /**
         * Initialize masonry layout
         */
        initMasonry: function() {
            if ($('.aqualuxe-services-layout-masonry').length) {
                // Check if Masonry is available
                if (typeof $.fn.masonry !== 'undefined') {
                    $('.aqualuxe-services-layout-masonry .aqualuxe-services-grid').masonry({
                        itemSelector: '.aqualuxe-service',
                        columnWidth: '.aqualuxe-service',
                        percentPosition: true
                    });
                }
            }
        },

        /**
         * Initialize filters
         */
        initFilters: function() {
            var self = this;

            $('.aqualuxe-services-archive-filters-form').on('submit', function(e) {
                e.preventDefault();

                var $form = $(this);
                var data = $form.serialize();
                var $container = $('.aqualuxe-services-grid');
                var $wrapper = $('.aqualuxe-services-archive');

                $wrapper.addClass('aqualuxe-services-loading');

                $.ajax({
                    url: aqualuxeServices.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_filter_services',
                        nonce: aqualuxeServices.nonce,
                        data: data
                    },
                    success: function(response) {
                        if (response.success) {
                            $container.html(response.data.html);
                            
                            // Update URL with filters
                            if (history.pushState) {
                                var url = window.location.protocol + '//' + window.location.host + window.location.pathname + '?' + data;
                                window.history.pushState({ path: url }, '', url);
                            }

                            // Reinitialize masonry
                            self.initMasonry();
                        }
                    },
                    complete: function() {
                        $wrapper.removeClass('aqualuxe-services-loading');
                    }
                });
            });

            // Reset filters
            $('.aqualuxe-services-archive-filters-reset').on('click', function(e) {
                e.preventDefault();
                
                var $form = $('.aqualuxe-services-archive-filters-form');
                $form.find('select, input[type="text"]').val('');
                $form.find('input[type="checkbox"]').prop('checked', false);
                $form.submit();
            });
        },

        /**
         * Initialize load more
         */
        initLoadMore: function() {
            var self = this;
            var $button = $('.aqualuxe-services-load-more');
            
            if (!$button.length) {
                return;
            }

            $button.on('click', function(e) {
                e.preventDefault();

                var $this = $(this);
                var page = parseInt($this.data('page'));
                var maxPages = parseInt($this.data('max-pages'));
                var $container = $('.aqualuxe-services-grid');
                var $wrapper = $('.aqualuxe-services-archive');

                if (page >= maxPages) {
                    $this.remove();
                    return;
                }

                $this.addClass('loading').text(aqualuxeServices.i18n.loading);
                $wrapper.addClass('aqualuxe-services-loading');

                $.ajax({
                    url: aqualuxeServices.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_load_more_services',
                        nonce: aqualuxeServices.nonce,
                        page: page,
                        query: $this.data('query')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Append new services
                            $container.append(response.data.html);
                            
                            // Update page
                            $this.data('page', page + 1);
                            
                            // Remove button if last page
                            if (page + 1 >= maxPages) {
                                $this.remove();
                            } else {
                                $this.removeClass('loading').text(aqualuxeServices.i18n.loadMore);
                            }

                            // Reinitialize masonry
                            self.initMasonry();
                        }
                    },
                    complete: function() {
                        $wrapper.removeClass('aqualuxe-services-loading');
                    }
                });
            });
        },

        /**
         * Initialize booking form
         */
        initBookingForm: function() {
            // Date picker
            if (typeof $.fn.datepicker !== 'undefined') {
                $('.aqualuxe-service-booking-date').datepicker({
                    dateFormat: 'yy-mm-dd',
                    minDate: 0
                });
            }

            // Time picker
            if (typeof $.fn.timepicker !== 'undefined') {
                $('.aqualuxe-service-booking-time').timepicker({
                    timeFormat: 'HH:mm',
                    interval: 30,
                    minTime: '8:00',
                    maxTime: '20:00',
                    defaultTime: '12:00',
                    startTime: '8:00',
                    dynamic: false,
                    dropdown: true,
                    scrollbar: true
                });
            }

            // Form validation
            $('.aqualuxe-service-booking-form form').on('submit', function(e) {
                var $form = $(this);
                var $required = $form.find('[required]');
                var valid = true;

                $required.each(function() {
                    var $this = $(this);
                    if (!$this.val()) {
                        valid = false;
                        $this.addClass('error');
                    } else {
                        $this.removeClass('error');
                    }
                });

                if (!valid) {
                    e.preventDefault();
                    return false;
                }
            });
        },

        /**
         * Quick view
         */
        quickView: function(e) {
            e.preventDefault();

            var $this = $(this);
            var serviceId = $this.data('service-id');

            // Show loading
            $('body').addClass('aqualuxe-service-quick-view-loading');

            // Get quick view content
            $.ajax({
                url: aqualuxeServices.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_quick_view',
                    nonce: aqualuxeServices.nonce,
                    service_id: serviceId
                },
                success: function(response) {
                    if (response.success) {
                        // Append quick view modal
                        $('body').append(response.data.html);
                        
                        // Show quick view modal
                        setTimeout(function() {
                            $('.aqualuxe-service-quick-view-modal').addClass('open');
                        }, 100);
                    }
                },
                complete: function() {
                    $('body').removeClass('aqualuxe-service-quick-view-loading');
                }
            });
        },

        /**
         * Close quick view
         */
        closeQuickView: function(e) {
            e.preventDefault();

            var $modal = $('.aqualuxe-service-quick-view-modal');

            // Hide quick view modal
            $modal.removeClass('open');

            // Remove quick view modal
            setTimeout(function() {
                $modal.remove();
            }, 300);
        },

        /**
         * Scroll to booking form
         */
        scrollToBookingForm: function(e) {
            e.preventDefault();

            var $form = $('.aqualuxe-single-service-booking-form');

            if ($form.length) {
                $('html, body').animate({
                    scrollTop: $form.offset().top - 100
                }, 500);
            }
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeServices.init();
    });

})(jQuery);