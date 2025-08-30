/**
 * Bookings Module JavaScript
 */
(function($) {
    'use strict';

    /**
     * Booking Calendar
     */
    var BookingCalendar = {
        /**
         * Initialize
         */
        init: function() {
            this.initCalendars();
            this.bindEvents();
        },

        /**
         * Initialize calendars
         */
        initCalendars: function() {
            $('.aqualuxe-calendar').each(function() {
                var $calendar = $(this);
                var data = $calendar.data('calendar');
                
                if (!data) {
                    return;
                }
                
                var $container = $calendar.find('.aqualuxe-calendar__container');
                var $timeSlots = $calendar.find('.aqualuxe-calendar__time-slots-container');
                
                // Initialize datepicker
                $container.datepicker({
                    dateFormat: 'yy-mm-dd',
                    firstDay: data.firstDay,
                    numberOfMonths: data.months,
                    minDate: data.minDate,
                    maxDate: data.maxDate,
                    beforeShowDay: function(date) {
                        var dateString = $.datepicker.formatDate('yy-mm-dd', date);
                        var available = $.inArray(dateString, data.availableDates) !== -1;
                        
                        return [available, available ? 'date-available' : 'date-unavailable'];
                    },
                    onSelect: function(dateText) {
                        // Update selected date
                        $calendar.data('selected-date', dateText);
                        
                        // If date_time or time booking type, load time slots
                        if (data.bookingType === 'date_time' || data.bookingType === 'time') {
                            BookingCalendar.loadTimeSlots($calendar, dateText);
                        } else {
                            // Trigger date selected event
                            $calendar.trigger('aqualuxe:date-selected', [dateText]);
                        }
                    }
                });
                
                // Set initial date if provided
                if (data.selectedDate) {
                    $container.datepicker('setDate', data.selectedDate);
                }
                
                // If inline is false, create input field
                if (!data.inline) {
                    var $input = $('<input type="text" class="aqualuxe-calendar__input" readonly>');
                    $container.before($input);
                    
                    $container.datepicker('option', 'altField', $input);
                    $container.datepicker('option', 'altFormat', 'MM d, yy');
                    
                    $container.hide();
                    
                    $input.on('click', function() {
                        $container.toggle();
                    });
                    
                    $(document).on('click', function(e) {
                        if (!$(e.target).closest('.aqualuxe-calendar').length) {
                            $container.hide();
                        }
                    });
                }
            });
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Time slot selection
            $(document).on('click', '.aqualuxe-calendar__time-slot', function() {
                var $slot = $(this);
                var $calendar = $slot.closest('.aqualuxe-calendar');
                var $timeSlots = $calendar.find('.aqualuxe-calendar__time-slots-container');
                
                // Remove selected class from all slots
                $timeSlots.find('.aqualuxe-calendar__time-slot').removeClass('aqualuxe-calendar__time-slot--selected');
                
                // Add selected class to clicked slot
                $slot.addClass('aqualuxe-calendar__time-slot--selected');
                
                // Get selected date and time
                var date = $calendar.data('selected-date');
                var time = $slot.data('time');
                
                // Trigger time selected event
                $calendar.trigger('aqualuxe:time-selected', [date, time]);
            });
        },

        /**
         * Load time slots
         * 
         * @param {jQuery} $calendar
         * @param {string} date
         */
        loadTimeSlots: function($calendar, date) {
            var data = $calendar.data('calendar');
            var $timeSlots = $calendar.find('.aqualuxe-calendar__time-slots-container');
            
            // Show loading
            $timeSlots.html('<div class="aqualuxe-calendar__loading">Loading time slots...</div>');
            
            // Get time slots via AJAX
            $.ajax({
                url: aqualuxeBookings.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_time_slots',
                    nonce: aqualuxeBookings.nonce,
                    bookable_id: data.bookableId,
                    date: date
                },
                success: function(response) {
                    if (response.success) {
                        // Clear time slots
                        $timeSlots.empty();
                        
                        // Add time slots
                        if (response.data.length > 0) {
                            $.each(response.data, function(index, slot) {
                                var $slot = $('<div class="aqualuxe-calendar__time-slot"></div>');
                                $slot.text(slot.formatted_start);
                                $slot.data('time', slot.start);
                                $timeSlots.append($slot);
                            });
                        } else {
                            $timeSlots.html('<div class="aqualuxe-calendar__no-slots">No time slots available for this date.</div>');
                        }
                    } else {
                        $timeSlots.html('<div class="aqualuxe-calendar__error">' + response.data + '</div>');
                    }
                },
                error: function() {
                    $timeSlots.html('<div class="aqualuxe-calendar__error">Error loading time slots.</div>');
                }
            });
        }
    };

    /**
     * Booking Form
     */
    var BookingForm = {
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
            this.initForms();
        },

        /**
         * Initialize forms
         */
        initForms: function() {
            $('.aqualuxe-booking-form').each(function() {
                var $form = $(this);
                var $calendar = $form.find('.aqualuxe-calendar');
                var $startDate = $form.find('[name="start_date"]');
                var $endDate = $form.find('[name="end_date"]');
                var $startTime = $form.find('[name="start_time"]');
                var $endTime = $form.find('[name="end_time"]');
                var $quantity = $form.find('[name="quantity"]');
                var $price = $form.find('.aqualuxe-booking-form__price');
                
                // Calendar date selected event
                $calendar.on('aqualuxe:date-selected', function(e, date) {
                    $startDate.val(date);
                    
                    // If date range booking type, set end date to same as start date
                    if ($endDate.length) {
                        $endDate.val(date);
                    }
                    
                    // Check availability and update price
                    BookingForm.checkAvailability($form);
                });
                
                // Calendar time selected event
                $calendar.on('aqualuxe:time-selected', function(e, date, time) {
                    $startTime.val(time);
                    
                    // If time booking type, set end time based on duration
                    if ($endTime.length) {
                        // This would need to calculate end time based on duration
                        // For now, just set it to the same as start time
                        $endTime.val(time);
                    }
                    
                    // Check availability and update price
                    BookingForm.checkAvailability($form);
                });
                
                // Quantity change event
                $quantity.on('change', function() {
                    BookingForm.checkAvailability($form);
                });
            });
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Form submission
            $(document).on('submit', '.aqualuxe-booking-form', function(e) {
                e.preventDefault();
                
                var $form = $(this);
                
                // Validate form
                if (!BookingForm.validateForm($form)) {
                    return;
                }
                
                // Submit form
                BookingForm.submitForm($form);
            });
            
            // Payment method selection
            $(document).on('change', '.aqualuxe-payment-methods__radio', function() {
                var $radio = $(this);
                var $item = $radio.closest('.aqualuxe-payment-methods__item');
                var $list = $radio.closest('.aqualuxe-payment-methods__list');
                
                // Remove active class from all items
                $list.find('.aqualuxe-payment-methods__item').removeClass('aqualuxe-payment-methods__item--active');
                
                // Add active class to selected item
                $item.addClass('aqualuxe-payment-methods__item--active');
                
                // Show selected payment method content
                $list.find('.aqualuxe-payment-methods__content').hide();
                $item.find('.aqualuxe-payment-methods__content').show();
            });
        },

        /**
         * Validate form
         * 
         * @param {jQuery} $form
         * @return {boolean}
         */
        validateForm: function($form) {
            var valid = true;
            var $startDate = $form.find('[name="start_date"]');
            var $endDate = $form.find('[name="end_date"]');
            var $startTime = $form.find('[name="start_time"]');
            var $endTime = $form.find('[name="end_time"]');
            var $quantity = $form.find('[name="quantity"]');
            var $customerName = $form.find('[name="customer_name"]');
            var $customerEmail = $form.find('[name="customer_email"]');
            var $customerPhone = $form.find('[name="customer_phone"]');
            
            // Clear previous errors
            $form.find('.aqualuxe-booking-form__error').remove();
            
            // Validate start date
            if (!$startDate.val()) {
                BookingForm.addError($startDate, aqualuxeBookings.i18n.selectDate);
                valid = false;
            }
            
            // Validate end date if present
            if ($endDate.length && !$endDate.val()) {
                BookingForm.addError($endDate, aqualuxeBookings.i18n.selectEndDate);
                valid = false;
            }
            
            // Validate start time if present
            if ($startTime.length && !$startTime.val()) {
                BookingForm.addError($startTime, aqualuxeBookings.i18n.selectTime);
                valid = false;
            }
            
            // Validate end time if present
            if ($endTime.length && !$endTime.val()) {
                BookingForm.addError($endTime, aqualuxeBookings.i18n.selectEndTime);
                valid = false;
            }
            
            // Validate quantity
            if (!$quantity.val() || parseInt($quantity.val()) < 1) {
                BookingForm.addError($quantity, aqualuxeBookings.i18n.selectQuantity);
                valid = false;
            }
            
            // Validate customer name
            if (!$customerName.val()) {
                BookingForm.addError($customerName, aqualuxeBookings.i18n.enterName);
                valid = false;
            }
            
            // Validate customer email
            if (!$customerEmail.val() || !BookingForm.isValidEmail($customerEmail.val())) {
                BookingForm.addError($customerEmail, aqualuxeBookings.i18n.enterEmail);
                valid = false;
            }
            
            // Validate customer phone
            if (!$customerPhone.val()) {
                BookingForm.addError($customerPhone, aqualuxeBookings.i18n.enterPhone);
                valid = false;
            }
            
            return valid;
        },

        /**
         * Add error message
         * 
         * @param {jQuery} $element
         * @param {string} message
         */
        addError: function($element, message) {
            $element.after('<div class="aqualuxe-booking-form__error">' + message + '</div>');
        },

        /**
         * Check if email is valid
         * 
         * @param {string} email
         * @return {boolean}
         */
        isValidEmail: function(email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        },

        /**
         * Check availability
         * 
         * @param {jQuery} $form
         */
        checkAvailability: function($form) {
            var $startDate = $form.find('[name="start_date"]');
            var $endDate = $form.find('[name="end_date"]');
            var $startTime = $form.find('[name="start_time"]');
            var $endTime = $form.find('[name="end_time"]');
            var $quantity = $form.find('[name="quantity"]');
            var $price = $form.find('.aqualuxe-booking-form__price');
            var $availabilityMessage = $form.find('.aqualuxe-booking-form__availability');
            var $submitButton = $form.find('.aqualuxe-booking-form__button');
            
            // Check if we have required data
            if (!$startDate.val()) {
                return;
            }
            
            // Prepare start date
            var startDate = $startDate.val();
            if ($startTime.length && $startTime.val()) {
                startDate += ' ' + $startTime.val();
            }
            
            // Prepare end date
            var endDate = $endDate.length && $endDate.val() ? $endDate.val() : startDate;
            if ($endTime.length && $endTime.val()) {
                endDate += ' ' + $endTime.val();
            } else if ($startTime.length && $startTime.val()) {
                endDate += ' ' + $startTime.val();
            }
            
            // Get quantity
            var quantity = $quantity.val() ? parseInt($quantity.val()) : 1;
            
            // Get bookable ID
            var bookableId = $form.data('bookable-id');
            
            // Show checking message
            if ($availabilityMessage.length) {
                $availabilityMessage.html('<div class="aqualuxe-booking-form__checking">' + aqualuxeBookings.i18n.checkingAvailability + '</div>');
            }
            
            // Disable submit button
            $submitButton.prop('disabled', true);
            
            // Check availability via AJAX
            $.ajax({
                url: aqualuxeBookings.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_check_availability',
                    nonce: aqualuxeBookings.nonce,
                    bookable_id: bookableId,
                    start_date: startDate,
                    end_date: endDate,
                    quantity: quantity
                },
                success: function(response) {
                    if (response.success) {
                        // Update price
                        if ($price.length) {
                            $price.text(response.data.formatted_price);
                        }
                        
                        // Update availability message
                        if ($availabilityMessage.length) {
                            if (response.data.available) {
                                $availabilityMessage.html('<div class="aqualuxe-booking-form__available">' + aqualuxeBookings.i18n.available + '</div>');
                            } else {
                                $availabilityMessage.html('<div class="aqualuxe-booking-form__not-available">' + aqualuxeBookings.i18n.notAvailable + '</div>');
                            }
                        }
                        
                        // Enable/disable submit button
                        $submitButton.prop('disabled', !response.data.available);
                    } else {
                        // Show error message
                        if ($availabilityMessage.length) {
                            $availabilityMessage.html('<div class="aqualuxe-booking-form__error">' + response.data.message + '</div>');
                        }
                        
                        // Disable submit button
                        $submitButton.prop('disabled', true);
                    }
                },
                error: function() {
                    // Show error message
                    if ($availabilityMessage.length) {
                        $availabilityMessage.html('<div class="aqualuxe-booking-form__error">Error checking availability.</div>');
                    }
                    
                    // Disable submit button
                    $submitButton.prop('disabled', true);
                }
            });
        },

        /**
         * Submit form
         * 
         * @param {jQuery} $form
         */
        submitForm: function($form) {
            var $submitButton = $form.find('.aqualuxe-booking-form__button');
            var $message = $form.find('.aqualuxe-booking-form__message');
            
            // Disable submit button
            $submitButton.prop('disabled', true);
            $submitButton.text(aqualuxeBookings.i18n.processingBooking);
            
            // Clear message
            if ($message.length) {
                $message.empty();
            }
            
            // Get form data
            var formData = $form.serialize();
            
            // Add action and nonce
            formData += '&action=aqualuxe_booking_submit&nonce=' + aqualuxeBookings.nonce;
            
            // Submit form via AJAX
            $.ajax({
                url: aqualuxeBookings.ajaxUrl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        if ($message.length) {
                            $message.html('<div class="aqualuxe-booking-form__success">' + aqualuxeBookings.i18n.bookingSuccess + '</div>');
                        }
                        
                        // If payment is required, process payment
                        if (response.data.requires_payment) {
                            BookingForm.processPayment($form, response.data.booking_id);
                        } else {
                            // Redirect to confirmation page
                            if (response.data.redirect) {
                                window.location.href = response.data.redirect;
                            }
                        }
                    } else {
                        // Show error message
                        if ($message.length) {
                            $message.html('<div class="aqualuxe-booking-form__error">' + response.data.message + '</div>');
                        }
                        
                        // Enable submit button
                        $submitButton.prop('disabled', false);
                        $submitButton.text(aqualuxeBookings.i18n.submitBooking);
                    }
                },
                error: function() {
                    // Show error message
                    if ($message.length) {
                        $message.html('<div class="aqualuxe-booking-form__error">' + aqualuxeBookings.i18n.bookingError + '</div>');
                    }
                    
                    // Enable submit button
                    $submitButton.prop('disabled', false);
                    $submitButton.text(aqualuxeBookings.i18n.submitBooking);
                }
            });
        },

        /**
         * Process payment
         * 
         * @param {jQuery} $form
         * @param {int} bookingId
         */
        processPayment: function($form, bookingId) {
            var $paymentMethods = $form.find('.aqualuxe-payment-methods');
            var $selectedMethod = $paymentMethods.find('.aqualuxe-payment-methods__radio:checked');
            var $submitButton = $form.find('.aqualuxe-booking-form__button');
            var $message = $form.find('.aqualuxe-booking-form__message');
            
            // Check if payment method is selected
            if (!$selectedMethod.length) {
                // Show error message
                if ($message.length) {
                    $message.html('<div class="aqualuxe-booking-form__error">Please select a payment method.</div>');
                }
                
                // Enable submit button
                $submitButton.prop('disabled', false);
                $submitButton.text(aqualuxeBookings.i18n.submitBooking);
                
                return;
            }
            
            // Get payment method
            var paymentMethod = $selectedMethod.val();
            
            // Process payment based on method
            switch (paymentMethod) {
                case 'stripe':
                    BookingForm.processStripePayment($form, bookingId);
                    break;
                    
                case 'paypal':
                    BookingForm.processPayPalPayment($form, bookingId);
                    break;
                    
                case 'bank_transfer':
                case 'cash':
                    // No payment processing needed, redirect to confirmation page
                    BookingForm.completeBooking($form, bookingId, paymentMethod, '');
                    break;
                    
                default:
                    // Show error message
                    if ($message.length) {
                        $message.html('<div class="aqualuxe-booking-form__error">Invalid payment method.</div>');
                    }
                    
                    // Enable submit button
                    $submitButton.prop('disabled', false);
                    $submitButton.text(aqualuxeBookings.i18n.submitBooking);
            }
        },

        /**
         * Process Stripe payment
         * 
         * @param {jQuery} $form
         * @param {int} bookingId
         */
        processStripePayment: function($form, bookingId) {
            var $submitButton = $form.find('.aqualuxe-booking-form__button');
            var $message = $form.find('.aqualuxe-booking-form__message');
            
            // Update button text
            $submitButton.text(aqualuxeBookings.i18n.processingPayment);
            
            // In a real implementation, we would use Stripe.js to create a payment token
            // For now, we'll simulate a successful payment
            setTimeout(function() {
                // Create a fake token
                var token = 'tok_' + Math.random().toString(36).substr(2, 9);
                
                // Complete booking with token
                BookingForm.completeBooking($form, bookingId, 'stripe', token);
            }, 1000);
        },

        /**
         * Process PayPal payment
         * 
         * @param {jQuery} $form
         * @param {int} bookingId
         */
        processPayPalPayment: function($form, bookingId) {
            var $submitButton = $form.find('.aqualuxe-booking-form__button');
            var $message = $form.find('.aqualuxe-booking-form__message');
            
            // Update button text
            $submitButton.text(aqualuxeBookings.i18n.processingPayment);
            
            // In a real implementation, we would use PayPal SDK to create a payment
            // For now, we'll simulate a successful payment
            setTimeout(function() {
                // Create a fake token
                var token = 'PAYPAL-' + Math.random().toString(36).substr(2, 9);
                
                // Complete booking with token
                BookingForm.completeBooking($form, bookingId, 'paypal', token);
            }, 1000);
        },

        /**
         * Complete booking
         * 
         * @param {jQuery} $form
         * @param {int} bookingId
         * @param {string} paymentMethod
         * @param {string} paymentToken
         */
        completeBooking: function($form, bookingId, paymentMethod, paymentToken) {
            var $submitButton = $form.find('.aqualuxe-booking-form__button');
            var $message = $form.find('.aqualuxe-booking-form__message');
            
            // Process payment via AJAX
            $.ajax({
                url: aqualuxeBookings.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_process_payment',
                    nonce: aqualuxeBookings.nonce,
                    booking_id: bookingId,
                    payment_method: paymentMethod,
                    payment_token: paymentToken
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        if ($message.length) {
                            $message.html('<div class="aqualuxe-booking-form__success">' + aqualuxeBookings.i18n.paymentSuccess + '</div>');
                        }
                        
                        // Redirect to confirmation page
                        if (response.data.redirect) {
                            window.location.href = response.data.redirect;
                        }
                    } else {
                        // Show error message
                        if ($message.length) {
                            $message.html('<div class="aqualuxe-booking-form__error">' + response.data.message + '</div>');
                        }
                        
                        // Enable submit button
                        $submitButton.prop('disabled', false);
                        $submitButton.text(aqualuxeBookings.i18n.submitBooking);
                    }
                },
                error: function() {
                    // Show error message
                    if ($message.length) {
                        $message.html('<div class="aqualuxe-booking-form__error">' + aqualuxeBookings.i18n.paymentError + '</div>');
                    }
                    
                    // Enable submit button
                    $submitButton.prop('disabled', false);
                    $submitButton.text(aqualuxeBookings.i18n.submitBooking);
                }
            });
        }
    };

    /**
     * Document ready
     */
    $(document).ready(function() {
        BookingCalendar.init();
        BookingForm.init();
    });

})(jQuery);