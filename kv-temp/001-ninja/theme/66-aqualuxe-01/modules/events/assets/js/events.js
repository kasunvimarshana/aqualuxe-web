/**
 * Events Module JavaScript
 *
 * @package AquaLuxe\Modules\Events
 */

(function($) {
    'use strict';

    /**
     * Events Calendar
     */
    var AquaLuxeEventsCalendar = {
        /**
         * Initialize calendar
         */
        init: function() {
            $('.aqualuxe-calendar').each(function() {
                var $calendar = $(this);
                var calendarData = $calendar.data('calendar');
                
                if (!calendarData) {
                    return;
                }
                
                AquaLuxeEventsCalendar.renderCalendar($calendar, calendarData);
            });
        },
        
        /**
         * Render calendar
         * 
         * @param {jQuery} $calendar Calendar element
         * @param {Object} calendarData Calendar data
         */
        renderCalendar: function($calendar, calendarData) {
            var $container = $calendar.find('.aqualuxe-calendar__container');
            var startDate = new Date(calendarData.startDate);
            var events = calendarData.events || [];
            var months = calendarData.months || 1;
            var firstDay = calendarData.firstDay || 0;
            var compact = calendarData.compact || false;
            
            // Clear container
            $container.empty();
            
            // Render months
            for (var i = 0; i < months; i++) {
                var currentDate = new Date(startDate);
                currentDate.setMonth(currentDate.getMonth() + i);
                
                var $month = AquaLuxeEventsCalendar.renderMonth(currentDate, events, firstDay, compact);
                $container.append($month);
            }
            
            // Initialize event tooltips
            AquaLuxeEventsCalendar.initTooltips($container);
            
            // Initialize event clicks
            AquaLuxeEventsCalendar.initEventClicks($container);
        },
        
        /**
         * Render month
         * 
         * @param {Date} date Month date
         * @param {Array} events Events
         * @param {number} firstDay First day of week (0 = Sunday, 1 = Monday)
         * @param {boolean} compact Compact mode
         * @return {jQuery} Month element
         */
        renderMonth: function(date, events, firstDay, compact) {
            var year = date.getFullYear();
            var month = date.getMonth();
            var monthName = date.toLocaleString('default', { month: 'long' });
            var daysInMonth = new Date(year, month + 1, 0).getDate();
            var firstDayOfMonth = new Date(year, month, 1).getDay();
            var lastDayOfMonth = new Date(year, month, daysInMonth).getDay();
            var today = new Date();
            today.setHours(0, 0, 0, 0);
            
            // Adjust first day of month based on first day of week
            firstDayOfMonth = (firstDayOfMonth - firstDay + 7) % 7;
            
            // Create month element
            var $month = $('<div class="aqualuxe-calendar__month-container"></div>');
            
            // Create header
            var $header = $('<div class="aqualuxe-calendar__header"></div>');
            var $monthName = $('<div class="aqualuxe-calendar__month">' + monthName + ' ' + year + '</div>');
            
            $header.append($monthName);
            $month.append($header);
            
            // Create table
            var $table = $('<table class="aqualuxe-calendar__table"></table>');
            var $thead = $('<thead></thead>');
            var $tbody = $('<tbody></tbody>');
            
            // Create weekdays
            var $weekdays = $('<tr class="aqualuxe-calendar__weekdays"></tr>');
            var weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            
            // Adjust weekdays based on first day of week
            weekdays = weekdays.slice(firstDay).concat(weekdays.slice(0, firstDay));
            
            for (var i = 0; i < 7; i++) {
                $weekdays.append('<th class="aqualuxe-calendar__weekday">' + weekdays[i] + '</th>');
            }
            
            $thead.append($weekdays);
            $table.append($thead);
            
            // Create days
            var day = 1;
            var nextMonthDay = 1;
            var prevMonthDays = new Date(year, month, 0).getDate();
            var prevMonthStartDay = prevMonthDays - firstDayOfMonth + 1;
            
            // Calculate rows needed
            var rows = Math.ceil((firstDayOfMonth + daysInMonth) / 7);
            
            for (var row = 0; row < rows; row++) {
                var $row = $('<tr></tr>');
                
                for (var col = 0; col < 7; col++) {
                    var $cell = $('<td class="aqualuxe-calendar__day"></td>');
                    var cellDate = null;
                    
                    // Previous month
                    if (row === 0 && col < firstDayOfMonth) {
                        var prevDay = prevMonthStartDay + col;
                        cellDate = new Date(year, month - 1, prevDay);
                        $cell.addClass('aqualuxe-calendar__day--other-month');
                        
                        if (!compact) {
                            $cell.append('<div class="aqualuxe-calendar__day-number">' + prevDay + '</div>');
                        }
                    }
                    // Current month
                    else if (day <= daysInMonth) {
                        cellDate = new Date(year, month, day);
                        
                        // Check if today
                        if (cellDate.getTime() === today.getTime()) {
                            $cell.addClass('aqualuxe-calendar__day--today');
                        }
                        
                        $cell.append('<div class="aqualuxe-calendar__day-number">' + day + '</div>');
                        day++;
                    }
                    // Next month
                    else {
                        cellDate = new Date(year, month + 1, nextMonthDay);
                        $cell.addClass('aqualuxe-calendar__day--other-month');
                        
                        if (!compact) {
                            $cell.append('<div class="aqualuxe-calendar__day-number">' + nextMonthDay + '</div>');
                        }
                        
                        nextMonthDay++;
                    }
                    
                    // Add events for this day
                    if (cellDate) {
                        var cellDateStr = cellDate.toISOString().split('T')[0];
                        var dayEvents = AquaLuxeEventsCalendar.getEventsForDay(events, cellDateStr);
                        
                        if (dayEvents.length > 0) {
                            var $events = $('<div class="aqualuxe-calendar__events"></div>');
                            
                            // Limit events in compact mode
                            var maxEvents = compact ? 2 : 4;
                            var displayEvents = dayEvents.slice(0, maxEvents);
                            var hiddenEvents = dayEvents.length - maxEvents;
                            
                            // Add events
                            for (var e = 0; e < displayEvents.length; e++) {
                                var event = displayEvents[e];
                                var $event = $('<div class="aqualuxe-calendar__event" data-event-id="' + event.id + '">' + event.title + '</div>');
                                
                                if (event.featured) {
                                    $event.addClass('aqualuxe-calendar__event--featured');
                                }
                                
                                // Add event data for tooltip
                                $event.data('event', event);
                                
                                $events.append($event);
                            }
                            
                            // Add more events indicator
                            if (hiddenEvents > 0) {
                                $events.append('<div class="aqualuxe-calendar__event aqualuxe-calendar__event--more">+' + hiddenEvents + ' more</div>');
                            }
                            
                            $cell.append($events);
                        }
                    }
                    
                    $row.append($cell);
                }
                
                $tbody.append($row);
            }
            
            $table.append($tbody);
            $month.append($table);
            
            return $month;
        },
        
        /**
         * Get events for day
         * 
         * @param {Array} events Events
         * @param {string} date Date string (YYYY-MM-DD)
         * @return {Array} Events for day
         */
        getEventsForDay: function(events, date) {
            return events.filter(function(event) {
                var startDate = event.start;
                var endDate = event.end || event.start;
                
                return date >= startDate && date <= endDate;
            });
        },
        
        /**
         * Initialize tooltips
         * 
         * @param {jQuery} $container Calendar container
         */
        initTooltips: function($container) {
            // Check if tippy.js is available
            if (typeof tippy === 'function') {
                tippy('.aqualuxe-calendar__event', {
                    content: function(reference) {
                        var event = $(reference).data('event');
                        
                        if (!event) {
                            return '';
                        }
                        
                        var content = '<div class="aqualuxe-calendar__tooltip">';
                        content += '<div class="aqualuxe-calendar__tooltip-title">' + event.title + '</div>';
                        
                        if (event.image) {
                            content += '<div class="aqualuxe-calendar__tooltip-image"><img src="' + event.image + '" alt="' + event.title + '"></div>';
                        }
                        
                        content += '<div class="aqualuxe-calendar__tooltip-date">' + event.formattedStart;
                        
                        if (event.start !== event.end) {
                            content += ' - ' + event.formattedEnd;
                        }
                        
                        content += '</div>';
                        content += '<div class="aqualuxe-calendar__tooltip-time">' + event.formattedStartTime + ' - ' + event.formattedEndTime + '</div>';
                        
                        if (event.venue && event.venue.name) {
                            content += '<div class="aqualuxe-calendar__tooltip-venue">' + event.venue.name + '</div>';
                        }
                        
                        if (event.excerpt) {
                            content += '<div class="aqualuxe-calendar__tooltip-excerpt">' + event.excerpt.substring(0, 100) + '...</div>';
                        }
                        
                        content += '</div>';
                        
                        return content;
                    },
                    allowHTML: true,
                    placement: 'top',
                    arrow: true,
                    theme: 'light',
                    interactive: true,
                    appendTo: document.body
                });
            }
        },
        
        /**
         * Initialize event clicks
         * 
         * @param {jQuery} $container Calendar container
         */
        initEventClicks: function($container) {
            $container.on('click', '.aqualuxe-calendar__event', function(e) {
                var event = $(this).data('event');
                
                if (event && event.url) {
                    window.location.href = event.url;
                }
            });
        }
    };

    /**
     * Events Registration
     */
    var AquaLuxeEventsRegistration = {
        /**
         * Initialize registration
         */
        init: function() {
            // Initialize ticket selection
            AquaLuxeEventsRegistration.initTicketSelection();
            
            // Initialize registration form
            AquaLuxeEventsRegistration.initRegistrationForm();
            
            // Initialize payment methods
            AquaLuxeEventsRegistration.initPaymentMethods();
        },
        
        /**
         * Initialize ticket selection
         */
        initTicketSelection: function() {
            $('.aqualuxe-event__ticket-select-button').on('click', function(e) {
                e.preventDefault();
                
                var $button = $(this);
                var ticketId = $button.data('ticket-id');
                var ticketName = $button.data('ticket-name');
                var ticketPrice = $button.data('ticket-price');
                
                // Set ticket ID in form
                $('#aqualuxe-event-ticket-id').val(ticketId);
                
                // Set ticket info in form
                $('.aqualuxe-event__registration-form-ticket-name').text(ticketName);
                $('.aqualuxe-event__registration-form-ticket-price').text(AquaLuxeEventsRegistration.formatPrice(ticketPrice));
                
                // Update total
                AquaLuxeEventsRegistration.updateTotal();
                
                // Show registration form
                $('.aqualuxe-event__tickets').hide();
                $('.aqualuxe-event__registration-form').show();
                
                // Scroll to registration form
                $('html, body').animate({
                    scrollTop: $('.aqualuxe-event__registration-form').offset().top - 100
                }, 500);
            });
            
            // Back button
            $('.aqualuxe-event__registration-form-back-button').on('click', function(e) {
                e.preventDefault();
                
                // Show tickets
                $('.aqualuxe-event__tickets').show();
                $('.aqualuxe-event__registration-form').hide();
                
                // Scroll to tickets
                $('html, body').animate({
                    scrollTop: $('.aqualuxe-event__tickets').offset().top - 100
                }, 500);
            });
            
            // Quantity change
            $('#aqualuxe-event-quantity').on('change', function() {
                AquaLuxeEventsRegistration.updateTotal();
            });
        },
        
        /**
         * Initialize registration form
         */
        initRegistrationForm: function() {
            $('#aqualuxe-event-registration-form').on('submit', function(e) {
                e.preventDefault();
                
                var $form = $(this);
                var $submitButton = $form.find('.aqualuxe-event__registration-form-submit-button');
                var $message = $form.find('.aqualuxe-event__registration-form-message');
                
                // Validate form
                if (!AquaLuxeEventsRegistration.validateForm($form)) {
                    return;
                }
                
                // Disable submit button
                $submitButton.prop('disabled', true).text(aqualuxeEvents.i18n.processingPayment);
                
                // Clear message
                $message.removeClass('aqualuxe-event__registration-form-message--success aqualuxe-event__registration-form-message--error').empty();
                
                // Get form data
                var formData = $form.serialize();
                
                // Process payment if needed
                var paymentMethod = $form.find('input[name="payment_method"]:checked').val();
                
                if (paymentMethod === 'stripe') {
                    // Simulate Stripe payment (in a real implementation, this would use the Stripe API)
                    var cardNumber = $('#aqualuxe-event-card-number').val();
                    var cardExpiry = $('#aqualuxe-event-card-expiry').val();
                    var cardCvc = $('#aqualuxe-event-card-cvc').val();
                    
                    // Validate card details
                    if (!cardNumber || !cardExpiry || !cardCvc) {
                        $message.addClass('aqualuxe-event__registration-form-message--error').text('Please enter valid card details.');
                        $submitButton.prop('disabled', false).text('Register');
                        return;
                    }
                    
                    // Simulate token creation
                    $('#aqualuxe-event-payment-token').val('tok_' + Math.random().toString(36).substring(2, 15));
                }
                
                // Submit registration
                $.ajax({
                    url: aqualuxeEvents.ajaxUrl,
                    type: 'POST',
                    data: formData + '&action=aqualuxe_event_registration',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $message.addClass('aqualuxe-event__registration-form-message--success').text(aqualuxeEvents.i18n.registrationSuccess);
                            
                            // Redirect to confirmation page if provided
                            if (response.redirect) {
                                setTimeout(function() {
                                    window.location.href = response.redirect;
                                }, 2000);
                            }
                        } else {
                            $message.addClass('aqualuxe-event__registration-form-message--error').text(response.message || aqualuxeEvents.i18n.registrationError);
                            $submitButton.prop('disabled', false).text('Register');
                        }
                    },
                    error: function() {
                        $message.addClass('aqualuxe-event__registration-form-message--error').text(aqualuxeEvents.i18n.registrationError);
                        $submitButton.prop('disabled', false).text('Register');
                    }
                });
            });
        },
        
        /**
         * Initialize payment methods
         */
        initPaymentMethods: function() {
            $('input[name="payment_method"]').on('change', function() {
                var method = $(this).val();
                
                // Hide all payment details
                $('.aqualuxe-event__registration-form-payment-detail').hide();
                
                // Show selected payment details
                $('#aqualuxe-event-payment-details-' + method).show();
            });
        },
        
        /**
         * Update total
         */
        updateTotal: function() {
            var ticketPrice = parseFloat($('.aqualuxe-event__registration-form-ticket-price').text().replace(/[^0-9.]/g, '')) || 0;
            var quantity = parseInt($('#aqualuxe-event-quantity').val()) || 1;
            var total = ticketPrice * quantity;
            
            $('.aqualuxe-event__registration-form-total-value').text(AquaLuxeEventsRegistration.formatPrice(total));
        },
        
        /**
         * Validate form
         * 
         * @param {jQuery} $form Form element
         * @return {boolean} Is valid
         */
        validateForm: function($form) {
            var isValid = true;
            var $message = $form.find('.aqualuxe-event__registration-form-message');
            
            // Clear message
            $message.removeClass('aqualuxe-event__registration-form-message--error').empty();
            
            // Check ticket
            if (!$('#aqualuxe-event-ticket-id').val()) {
                $message.addClass('aqualuxe-event__registration-form-message--error').text(aqualuxeEvents.i18n.selectTicket);
                isValid = false;
                return isValid;
            }
            
            // Check quantity
            if (!$('#aqualuxe-event-quantity').val()) {
                $message.addClass('aqualuxe-event__registration-form-message--error').text(aqualuxeEvents.i18n.selectQuantity);
                isValid = false;
                return isValid;
            }
            
            // Check name
            if (!$('#aqualuxe-event-attendee-name').val()) {
                $message.addClass('aqualuxe-event__registration-form-message--error').text(aqualuxeEvents.i18n.enterName);
                isValid = false;
                return isValid;
            }
            
            // Check email
            var email = $('#aqualuxe-event-attendee-email').val();
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!email || !emailRegex.test(email)) {
                $message.addClass('aqualuxe-event__registration-form-message--error').text(aqualuxeEvents.i18n.enterEmail);
                isValid = false;
                return isValid;
            }
            
            // Check phone
            if (!$('#aqualuxe-event-attendee-phone').val()) {
                $message.addClass('aqualuxe-event__registration-form-message--error').text(aqualuxeEvents.i18n.enterPhone);
                isValid = false;
                return isValid;
            }
            
            return isValid;
        },
        
        /**
         * Format price
         * 
         * @param {number} price Price
         * @return {string} Formatted price
         */
        formatPrice: function(price) {
            // Use browser's Intl.NumberFormat if available
            if (typeof Intl === 'object' && typeof Intl.NumberFormat === 'function') {
                return new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD'
                }).format(price);
            }
            
            // Fallback
            return '$' + parseFloat(price).toFixed(2);
        }
    };

    /**
     * Registration Confirmation
     */
    var AquaLuxeEventsConfirmation = {
        /**
         * Initialize confirmation
         */
        init: function() {
            // Initialize cancel registration
            AquaLuxeEventsConfirmation.initCancelRegistration();
            
            // Initialize print
            AquaLuxeEventsConfirmation.initPrint();
        },
        
        /**
         * Initialize cancel registration
         */
        initCancelRegistration: function() {
            $('.aqualuxe-registration-confirmation__action--cancel').on('click', function(e) {
                e.preventDefault();
                
                var $button = $(this);
                var registrationId = $button.data('registration-id');
                var nonce = $button.data('nonce');
                
                if (!registrationId || !nonce) {
                    return;
                }
                
                if (!confirm('Are you sure you want to cancel this registration?')) {
                    return;
                }
                
                // Disable button
                $button.prop('disabled', true).text('Cancelling...');
                
                // Cancel registration
                $.ajax({
                    url: aqualuxeEvents.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_cancel_registration',
                        registration_id: registrationId,
                        nonce: nonce
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Reload page
                            window.location.reload();
                        } else {
                            alert(response.message || 'Failed to cancel registration.');
                            $button.prop('disabled', false).text('Cancel Registration');
                        }
                    },
                    error: function() {
                        alert('An error occurred. Please try again.');
                        $button.prop('disabled', false).text('Cancel Registration');
                    }
                });
            });
        },
        
        /**
         * Initialize print
         */
        initPrint: function() {
            $('.aqualuxe-registration-confirmation__action--print').on('click', function(e) {
                e.preventDefault();
                window.print();
            });
        }
    };

    /**
     * Document ready
     */
    $(document).ready(function() {
        // Initialize calendar
        AquaLuxeEventsCalendar.init();
        
        // Initialize registration
        AquaLuxeEventsRegistration.init();
        
        // Initialize confirmation
        AquaLuxeEventsConfirmation.init();
    });
})(jQuery);