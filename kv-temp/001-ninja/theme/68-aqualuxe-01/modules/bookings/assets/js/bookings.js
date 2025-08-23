/**
 * Bookings Module Scripts
 *
 * @package AquaLuxe
 * @subpackage Modules\Bookings
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Booking Form Handler
     */
    var BookingForm = {
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
            this.initDatePicker();
            this.loadTimeSlots();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            $(document).on('change', '#bookable_id, #booking_date', this.handleServiceOrDateChange);
            $(document).on('click', '.aqualuxe-booking-time-slot:not(.unavailable)', this.handleTimeSlotClick);
            $(document).on('submit', '.aqualuxe-booking-form', this.validateForm);
        },

        /**
         * Initialize date picker
         */
        initDatePicker: function() {
            // If flatpickr is available, enhance the date picker
            if (typeof flatpickr !== 'undefined') {
                var bookableId = $('#bookable_id').val();
                var dateField = $('#booking_date');
                
                if (bookableId && dateField.length) {
                    this.initFlatpickr(bookableId, dateField);
                }
            }
        },

        /**
         * Initialize flatpickr
         * 
         * @param {number} bookableId 
         * @param {object} dateField 
         */
        initFlatpickr: function(bookableId, dateField) {
            var self = this;
            
            // Get available dates for the selected service
            $.ajax({
                url: aqualuxeBookings.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_available_dates',
                    nonce: aqualuxeBookings.nonce,
                    bookable_id: bookableId
                },
                success: function(response) {
                    if (response.success && response.data.dates) {
                        var availableDates = response.data.dates.map(function(date) {
                            return date.date;
                        });
                        
                        flatpickr(dateField[0], {
                            minDate: 'today',
                            dateFormat: 'Y-m-d',
                            enable: availableDates,
                            disable: [
                                function(date) {
                                    // Disable dates not in the available dates array
                                    var dateString = date.getFullYear() + '-' + 
                                                    ('0' + (date.getMonth() + 1)).slice(-2) + '-' + 
                                                    ('0' + date.getDate()).slice(-2);
                                    return !availableDates.includes(dateString);
                                }
                            ],
                            locale: {
                                firstDayOfWeek: parseInt(aqualuxeBookings.firstDayOfWeek || 0)
                            },
                            onChange: function(selectedDates, dateStr) {
                                self.loadTimeSlots();
                            }
                        });
                    }
                }
            });
        },

        /**
         * Handle service or date change
         */
        handleServiceOrDateChange: function() {
            BookingForm.loadTimeSlots();
        },

        /**
         * Load time slots
         */
        loadTimeSlots: function() {
            var bookableId = $('#bookable_id').val();
            var bookingDate = $('#booking_date').val();
            var timeSlots = $('.aqualuxe-booking-time-slots');
            var bookingTimeInput = $('#booking_time');

            if (!bookableId || !bookingDate) {
                timeSlots.html('<p class="aqualuxe-booking-time-message">' + aqualuxeBookings.messages.selectServiceAndDate + '</p>');
                bookingTimeInput.val('');
                return;
            }

            timeSlots.html('<p class="aqualuxe-booking-time-message">' + aqualuxeBookings.messages.loadingTimes + '</p>');

            $.ajax({
                url: aqualuxeBookings.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_available_times',
                    nonce: aqualuxeBookings.nonce,
                    bookable_id: bookableId,
                    date: bookingDate
                },
                success: function(response) {
                    if (response.success) {
                        var slots = response.data.available_slots;
                        var html = '';

                        if (slots.length === 0) {
                            html = '<p class="aqualuxe-booking-time-message">' + aqualuxeBookings.messages.noTimeSlotsAvailable + '</p>';
                        } else {
                            html = '<div class="aqualuxe-booking-time-grid">';
                            for (var i = 0; i < slots.length; i++) {
                                var slot = slots[i];
                                var classes = 'aqualuxe-booking-time-slot';
                                if (!slot.available) {
                                    classes += ' unavailable';
                                }
                                if (bookingTimeInput.val() === slot.time) {
                                    classes += ' selected';
                                }
                                html += '<div class="' + classes + '" data-time="' + slot.time + '">' + slot.formatted + '</div>';
                            }
                            html += '</div>';
                        }

                        timeSlots.html(html);
                    } else {
                        timeSlots.html('<p class="aqualuxe-booking-time-message">' + response.data.message + '</p>');
                        bookingTimeInput.val('');
                    }
                },
                error: function() {
                    timeSlots.html('<p class="aqualuxe-booking-time-message">' + aqualuxeBookings.messages.errorLoadingTimes + '</p>');
                    bookingTimeInput.val('');
                }
            });
        },

        /**
         * Handle time slot click
         */
        handleTimeSlotClick: function() {
            var $this = $(this);
            $('.aqualuxe-booking-time-slot').removeClass('selected');
            $this.addClass('selected');
            $('#booking_time').val($this.data('time'));
        },

        /**
         * Validate form
         * 
         * @param {object} e Event object
         */
        validateForm: function(e) {
            var valid = true;
            var errorMessages = {};

            // Check required fields
            if (!$('#bookable_id').val()) {
                errorMessages.bookable_id = aqualuxeBookings.messages.selectService;
                valid = false;
            }

            if (!$('#booking_date').val()) {
                errorMessages.date = aqualuxeBookings.messages.selectDate;
                valid = false;
            }

            if (!$('#booking_time').val()) {
                errorMessages.time = aqualuxeBookings.messages.selectTime;
                valid = false;
            }

            if (!$('#customer_name').val()) {
                errorMessages.customer_name = aqualuxeBookings.messages.enterName;
                valid = false;
            }

            if (!$('#customer_email').val()) {
                errorMessages.customer_email = aqualuxeBookings.messages.enterEmail;
                valid = false;
            } else if (!BookingForm.isValidEmail($('#customer_email').val())) {
                errorMessages.customer_email = aqualuxeBookings.messages.enterValidEmail;
                valid = false;
            }

            // If form is not valid, prevent submission
            if (!valid) {
                e.preventDefault();
                
                // Display error messages
                for (var field in errorMessages) {
                    var $field = $('#' + field);
                    $field.closest('.aqualuxe-booking-form-field').addClass('has-error');
                    $field.closest('.aqualuxe-booking-form-field').find('.aqualuxe-booking-form-error').remove();
                    $field.closest('.aqualuxe-booking-form-field').append('<div class="aqualuxe-booking-form-error">' + errorMessages[field] + '</div>');
                }

                return false;
            }

            // Validate form via AJAX
            $.ajax({
                url: aqualuxeBookings.ajaxUrl,
                type: 'POST',
                async: false,
                data: {
                    action: 'aqualuxe_validate_booking_form',
                    nonce: aqualuxeBookings.nonce,
                    bookable_id: $('#bookable_id').val(),
                    date: $('#booking_date').val(),
                    time: $('#booking_time').val(),
                    customer_name: $('#customer_name').val(),
                    customer_email: $('#customer_email').val(),
                    customer_phone: $('#customer_phone').val(),
                    customer_notes: $('#customer_notes').val()
                },
                success: function(response) {
                    if (!response.success) {
                        e.preventDefault();
                        
                        // Display error messages
                        for (var field in response.data.errors) {
                            var $field = $('#' + field);
                            $field.closest('.aqualuxe-booking-form-field').addClass('has-error');
                            $field.closest('.aqualuxe-booking-form-field').find('.aqualuxe-booking-form-error').remove();
                            $field.closest('.aqualuxe-booking-form-field').append('<div class="aqualuxe-booking-form-error">' + response.data.errors[field] + '</div>');
                        }
                        
                        valid = false;
                    }
                },
                error: function() {
                    e.preventDefault();
                    alert(aqualuxeBookings.messages.errorValidatingForm);
                    valid = false;
                }
            });

            return valid;
        },

        /**
         * Check if email is valid
         * 
         * @param {string} email 
         * @returns {boolean}
         */
        isValidEmail: function(email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }
    };

    /**
     * Booking Calendar Handler
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
         * Bind events
         */
        bindEvents: function() {
            $(document).on('change', '#aqualuxe-booking-calendar-filter-bookable', this.handleFilterChange);
            $(document).on('click', '.aqualuxe-booking-availability-calendar-day.available', this.handleAvailableDayClick);
        },

        /**
         * Initialize calendars
         */
        initCalendars: function() {
            // Initialize FullCalendar if available
            if (typeof FullCalendar !== 'undefined') {
                this.initFullCalendar();
            }
        },

        /**
         * Initialize FullCalendar
         */
        initFullCalendar: function() {
            var calendarEl = document.getElementById('aqualuxe-booking-calendar');
            
            if (!calendarEl) {
                return;
            }
            
            var bookableId = $('#aqualuxe-booking-calendar-filter-bookable').val() || 0;
            var defaultView = calendarEl.dataset.view || 'dayGridMonth';
            var height = calendarEl.dataset.height || 'auto';

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: defaultView,
                height: height,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                navLinks: true,
                editable: false,
                selectable: false,
                dayMaxEvents: true,
                events: function(info, successCallback, failureCallback) {
                    $.ajax({
                        url: aqualuxeBookings.ajaxUrl,
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_get_calendar_events',
                            nonce: aqualuxeBookings.nonce,
                            start: info.startStr,
                            end: info.endStr,
                            bookable_id: bookableId
                        },
                        success: function(response) {
                            if (response.success) {
                                successCallback(response.data);
                            } else {
                                failureCallback(response.data.message);
                            }
                        },
                        error: function() {
                            failureCallback(aqualuxeBookings.messages.errorLoadingEvents);
                        }
                    });
                },
                eventDidMount: function(info) {
                    // Add tooltip if tippy.js is available
                    if (typeof tippy !== 'undefined') {
                        tippy(info.el, {
                            content: info.event.extendedProps.description,
                            allowHTML: true,
                            placement: 'top',
                            arrow: true,
                            theme: 'light'
                        });
                    }
                }
            });

            calendar.render();
            
            // Store calendar instance
            this.calendar = calendar;
        },

        /**
         * Handle filter change
         */
        handleFilterChange: function() {
            var bookableId = $(this).val();
            
            // If FullCalendar is initialized, refetch events
            if (BookingCalendar.calendar) {
                BookingCalendar.calendar.refetchEvents();
            }
        },

        /**
         * Handle available day click
         */
        handleAvailableDayClick: function() {
            var date = $(this).data('date');
            var bookableId = $('#bookable_id').val();
            
            if (date && bookableId) {
                $('#booking_date').val(date).trigger('change');
                
                // Scroll to time slots
                $('html, body').animate({
                    scrollTop: $('.aqualuxe-booking-time-slots').offset().top - 100
                }, 500);
            }
        }
    };

    /**
     * Document ready
     */
    $(document).ready(function() {
        BookingForm.init();
        BookingCalendar.init();
    });

})(jQuery);