/**
 * Events Calendar Scripts
 *
 * @package AquaLuxe
 * @subpackage Events
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Events Calendar
     */
    var AquaLuxeEvents = {
        /**
         * Initialize
         */
        init: function() {
            this.initCalendar();
            this.initEventList();
            this.initTickets();
            this.initSearch();
            this.initSingleEvent();
        },

        /**
         * Initialize Calendar
         */
        initCalendar: function() {
            // Month Calendar
            if ($('.aqualuxe-events-calendar-month').length) {
                this.initMonthCalendar();
            }

            // Week Calendar
            if ($('.aqualuxe-events-calendar-week').length) {
                this.initWeekCalendar();
            }

            // Day Calendar
            if ($('.aqualuxe-events-calendar-day').length) {
                this.initDayCalendar();
            }

            // List Calendar
            if ($('.aqualuxe-events-calendar-list').length) {
                this.initListCalendar();
            }

            // Calendar Navigation
            this.initCalendarNavigation();
        },

        /**
         * Initialize Month Calendar
         */
        initMonthCalendar: function() {
            // Handle day click
            $('.aqualuxe-events-calendar-day').on('click', function(e) {
                if (!$(e.target).closest('a').length) {
                    var date = $(this).data('date');
                    
                    if (date) {
                        window.location.href = AquaLuxeEventsParams.calendar_url + '?view=day&date=' + date;
                    }
                }
            });

            // Limit event display
            $('.aqualuxe-events-calendar-day-events').each(function() {
                var events = $(this).find('.aqualuxe-events-calendar-event');
                
                if (events.length > 3) {
                    events.slice(3).hide();
                    
                    if (!$(this).find('.aqualuxe-events-calendar-more-events').length) {
                        var date = $(this).closest('.aqualuxe-events-calendar-day').data('date');
                        var moreLink = $('<div class="aqualuxe-events-calendar-more-events"><a href="' + AquaLuxeEventsParams.calendar_url + '?view=day&date=' + date + '" class="aqualuxe-events-calendar-more-events-link">+ ' + (events.length - 3) + ' more</a></div>');
                        $(this).append(moreLink);
                    }
                }
            });
        },

        /**
         * Initialize Week Calendar
         */
        initWeekCalendar: function() {
            // Scroll to current time
            var now = new Date();
            var hours = now.getHours();
            var scrollPosition = hours * 60;
            
            $('.aqualuxe-events-calendar-timeline').scrollTop(scrollPosition);
        },

        /**
         * Initialize Day Calendar
         */
        initDayCalendar: function() {
            // Scroll to current time
            var now = new Date();
            var hours = now.getHours();
            var scrollPosition = hours * 60;
            
            $('.aqualuxe-events-calendar-timeline').scrollTop(scrollPosition);
        },

        /**
         * Initialize List Calendar
         */
        initListCalendar: function() {
            // Nothing specific for list calendar yet
        },

        /**
         * Initialize Calendar Navigation
         */
        initCalendarNavigation: function() {
            // View switcher
            $('.aqualuxe-events-view-switcher a').on('click', function(e) {
                e.preventDefault();
                
                var view = $(this).attr('class').match(/aqualuxe-events-view-([a-z]+)/)[1];
                var currentUrl = window.location.href;
                var newUrl;
                
                if (currentUrl.indexOf('?') !== -1) {
                    var urlParts = currentUrl.split('?');
                    var baseUrl = urlParts[0];
                    var queryParams = new URLSearchParams(urlParts[1]);
                    
                    queryParams.set('view', view);
                    newUrl = baseUrl + '?' + queryParams.toString();
                } else {
                    newUrl = currentUrl + '?view=' + view;
                }
                
                window.location.href = newUrl;
            });

            // Navigation buttons
            $('.aqualuxe-events-calendar-navigation a').on('click', function(e) {
                e.preventDefault();
                
                var url = $(this).attr('href');
                window.location.href = url;
            });
        },

        /**
         * Initialize Event List
         */
        initEventList: function() {
            // Nothing specific for event list yet
        },

        /**
         * Initialize Tickets
         */
        initTickets: function() {
            // Ticket quantity change
            $('.aqualuxe-ticket-quantity-select').on('change', function() {
                var ticketId = $(this).attr('name').match(/\[(\d+)\]/)[1];
                var quantity = parseInt($(this).val());
                
                // Update order summary
                AquaLuxeEvents.updateOrderSummary();
            });

            // Initialize order summary
            this.updateOrderSummary();
        },

        /**
         * Update Order Summary
         */
        updateOrderSummary: function() {
            if (!$('.aqualuxe-ticket-summary-body').length) {
                return;
            }
            
            var summaryBody = $('.aqualuxe-ticket-summary-body');
            var subtotal = 0;
            
            // Clear summary body
            summaryBody.empty();
            
            // Add selected tickets to summary
            $('.aqualuxe-ticket-quantity-select').each(function() {
                var ticketId = $(this).attr('name').match(/\[(\d+)\]/)[1];
                var quantity = parseInt($(this).val());
                
                if (quantity > 0) {
                    var ticketTitle = $(this).closest('.aqualuxe-ticket').find('.aqualuxe-ticket-title').text();
                    var ticketPrice = parseFloat($(this).closest('.aqualuxe-ticket').find('.aqualuxe-ticket-sale-price, .aqualuxe-ticket-regular-price').first().text().replace(/[^0-9\.]/g, ''));
                    var total = ticketPrice * quantity;
                    
                    // Add to subtotal
                    subtotal += total;
                    
                    // Add row to summary
                    summaryBody.append(
                        '<div class="aqualuxe-ticket-summary-row">' +
                            '<div class="aqualuxe-ticket-summary-ticket">' + ticketTitle + '</div>' +
                            '<div class="aqualuxe-ticket-summary-price">' + AquaLuxeEvents.formatCurrency(ticketPrice) + '</div>' +
                            '<div class="aqualuxe-ticket-summary-quantity">' + quantity + '</div>' +
                            '<div class="aqualuxe-ticket-summary-total">' + AquaLuxeEvents.formatCurrency(total) + '</div>' +
                        '</div>'
                    );
                }
            });
            
            // Calculate tax (10%)
            var tax = subtotal * 0.1;
            var grandTotal = subtotal + tax;
            
            // Update summary footer
            $('.aqualuxe-ticket-summary-subtotal .aqualuxe-ticket-summary-value').text(AquaLuxeEvents.formatCurrency(subtotal));
            $('.aqualuxe-ticket-summary-tax .aqualuxe-ticket-summary-value').text(AquaLuxeEvents.formatCurrency(tax));
            $('.aqualuxe-ticket-summary-grand-total .aqualuxe-ticket-summary-value').text(AquaLuxeEvents.formatCurrency(grandTotal));
            
            // Disable submit button if no tickets selected
            if (subtotal === 0) {
                $('.aqualuxe-ticket-purchase-button').prop('disabled', true);
            } else {
                $('.aqualuxe-ticket-purchase-button').prop('disabled', false);
            }
        },

        /**
         * Format Currency
         */
        formatCurrency: function(amount) {
            return AquaLuxeEventsParams.currency_symbol + parseFloat(amount).toFixed(2);
        },

        /**
         * Initialize Search
         */
        initSearch: function() {
            // Date filter
            $('input[name="event_date"]').on('change', function() {
                $(this).closest('form').submit();
            });

            // Category filter
            $('select[name="event_category"]').on('change', function() {
                $(this).closest('form').submit();
            });
        },

        /**
         * Initialize Single Event
         */
        initSingleEvent: function() {
            // Share dropdown
            $('.aqualuxe-event-share-toggle').on('click', function() {
                $(this).next('.aqualuxe-event-share-dropdown').toggleClass('active');
            });
            
            // Calendar dropdown
            $('.aqualuxe-event-calendar-toggle').on('click', function() {
                $(this).next('.aqualuxe-event-calendar-dropdown').toggleClass('active');
            });
            
            // Close dropdowns when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.aqualuxe-event-share, .aqualuxe-event-calendar').length) {
                    $('.aqualuxe-event-share-dropdown, .aqualuxe-event-calendar-dropdown').removeClass('active');
                }
            });

            // Event countdown
            $('.aqualuxe-event-countdown').each(function() {
                var countdownElement = $(this);
                var countdownDate = new Date(countdownElement.data('countdown')).getTime();
                
                var countdownTimer = setInterval(function() {
                    var now = new Date().getTime();
                    var distance = countdownDate - now;
                    
                    if (distance < 0) {
                        clearInterval(countdownTimer);
                        countdownElement.html('<div class="aqualuxe-event-countdown-expired">Event has started</div>');
                        return;
                    }
                    
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    countdownElement.find('.aqualuxe-event-countdown-days .aqualuxe-event-countdown-value').text(days < 10 ? '0' + days : days);
                    countdownElement.find('.aqualuxe-event-countdown-hours .aqualuxe-event-countdown-value').text(hours < 10 ? '0' + hours : hours);
                    countdownElement.find('.aqualuxe-event-countdown-minutes .aqualuxe-event-countdown-value').text(minutes < 10 ? '0' + minutes : minutes);
                    countdownElement.find('.aqualuxe-event-countdown-seconds .aqualuxe-event-countdown-value').text(seconds < 10 ? '0' + seconds : seconds);
                }, 1000);
            });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeEvents.init();
    });

})(jQuery);