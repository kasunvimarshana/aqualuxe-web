/**
 * Booking Calendar Script
 *
 * Handles the booking calendar functionality.
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // Booking Calendar Class
    var AquaLuxeBookingCalendar = function(element, options) {
        this.$container = $(element);
        this.options = $.extend({}, AquaLuxeBookingCalendar.DEFAULTS, options);
        this.calendar = null;
        this.init();
    };

    // Default options
    AquaLuxeBookingCalendar.DEFAULTS = {
        serviceId: 0,
        view: 'dayGridMonth',
        height: 'auto',
        eventsLimit: 10,
        showWeekends: true,
        showAllDay: true,
        showTime: true,
        showBookingButton: true,
        bookingButtonText: 'Book Now',
        bookingUrl: '',
        calendarSelector: '.aqualuxe-bookings-calendar',
        legendSelector: '.aqualuxe-bookings-calendar-legend',
        ajaxUrl: '',
        nonce: '',
        i18n: {
            today: 'Today',
            month: 'Month',
            week: 'Week',
            day: 'Day',
            list: 'List',
            allDay: 'All Day',
            eventDetails: 'Event Details',
            bookingDetails: 'Booking Details',
            service: 'Service',
            customer: 'Customer',
            date: 'Date',
            time: 'Time',
            status: 'Status',
            total: 'Total',
            viewBooking: 'View Booking',
            bookNow: 'Book Now',
            available: 'Available',
            unavailable: 'Unavailable',
            fullyBooked: 'Fully Booked',
            selectDate: 'Select Date',
            selectTime: 'Select Time',
            monday: 'Monday',
            tuesday: 'Tuesday',
            wednesday: 'Wednesday',
            thursday: 'Thursday',
            friday: 'Friday',
            saturday: 'Saturday',
            sunday: 'Sunday',
            january: 'January',
            february: 'February',
            march: 'March',
            april: 'April',
            may: 'May',
            june: 'June',
            july: 'July',
            august: 'August',
            september: 'September',
            october: 'October',
            november: 'November',
            december: 'December'
        },
        settings: {
            calendarFirstDay: 0,
            colorScheme: '#0073aa',
            calendarStyle: 'default'
        }
    };

    // Initialize
    AquaLuxeBookingCalendar.prototype.init = function() {
        // Set options from data attributes
        this.options.serviceId = this.$container.data('service-id') || this.options.serviceId;
        this.options.view = this.$container.data('view') || this.options.view;
        this.options.height = this.$container.data('height') || this.options.height;
        this.options.eventsLimit = this.$container.data('events-limit') || this.options.eventsLimit;
        this.options.showWeekends = this.$container.data('show-weekends') !== 'false';
        this.options.showAllDay = this.$container.data('show-all-day') !== 'false';
        this.options.showTime = this.$container.data('show-time') !== 'false';

        // Find calendar element
        this.$calendar = this.$container.find(this.options.calendarSelector);

        // Initialize calendar
        this.initCalendar();
    };

    // Initialize calendar
    AquaLuxeBookingCalendar.prototype.initCalendar = function() {
        var self = this;
        var calendarEl = this.$calendar[0];

        if (!calendarEl) {
            return;
        }

        // Create calendar
        this.calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: this.options.view,
            height: this.options.height,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            firstDay: parseInt(this.options.settings.calendarFirstDay) || 0,
            weekends: this.options.showWeekends,
            allDaySlot: this.options.showAllDay,
            slotDuration: '00:30:00',
            slotLabelInterval: '01:00:00',
            snapDuration: '00:15:00',
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: 'short'
            },
            dayMaxEvents: this.options.eventsLimit,
            eventDisplay: 'block',
            eventColor: this.options.settings.colorScheme,
            nowIndicator: true,
            locale: document.documentElement.lang || 'en',
            buttonText: {
                today: this.options.i18n.today,
                month: this.options.i18n.month,
                week: this.options.i18n.week,
                day: this.options.i18n.day,
                list: this.options.i18n.list
            },
            eventDidMount: function(info) {
                // Add tooltip
                if (info.event.extendedProps.type === 'availability') {
                    var slots = info.event.extendedProps.available_slots || 0;
                    var tooltipText = slots > 0 
                        ? slots + ' ' + (slots === 1 ? 'slot' : 'slots') + ' available' 
                        : 'Fully booked';
                    
                    $(info.el).attr('title', tooltipText);
                }
            },
            eventClick: function(info) {
                // Handle event click
                if (info.event.extendedProps.type === 'availability' && info.event.extendedProps.available_slots > 0) {
                    // Redirect to booking page with date pre-selected
                    var date = info.event.start;
                    var formattedDate = date.toISOString().split('T')[0]; // YYYY-MM-DD
                    
                    if (self.options.bookingUrl) {
                        window.location.href = self.options.bookingUrl + (self.options.bookingUrl.indexOf('?') !== -1 ? '&' : '?') + 'date=' + formattedDate;
                    }
                }
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                // Fetch events via AJAX
                $.ajax({
                    url: self.options.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'get_calendar_events',
                        service_id: self.options.serviceId,
                        start: fetchInfo.startStr,
                        end: fetchInfo.endStr,
                        nonce: self.options.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            successCallback(response.data.events);
                        } else {
                            failureCallback(new Error(response.data.message));
                        }
                    },
                    error: function(xhr, status, error) {
                        failureCallback(new Error(error));
                    }
                });
            }
        });

        // Render calendar
        this.calendar.render();

        // Add calendar style class
        if (this.options.settings.calendarStyle) {
            this.$container.addClass('aqualuxe-bookings-calendar-style-' + this.options.settings.calendarStyle);
        }
    };

    // jQuery plugin
    $.fn.aqualuxeBookingCalendar = function(options) {
        return this.each(function() {
            if (!$.data(this, 'aqualuxe.bookingCalendar')) {
                $.data(this, 'aqualuxe.bookingCalendar', new AquaLuxeBookingCalendar(this, options));
            }
        });
    };

    // Auto-initialize
    $(document).ready(function() {
        $('.aqualuxe-bookings-calendar-container').aqualuxeBookingCalendar({
            ajaxUrl: aqualuxe_bookings_calendar_params.ajax_url,
            nonce: aqualuxe_bookings_calendar_params.nonce,
            bookingUrl: $('.aqualuxe-bookings-calendar-booking-button a').attr('href') || '',
            i18n: aqualuxe_bookings_calendar_params.i18n,
            settings: aqualuxe_bookings_calendar_params.settings
        });
    });

})(jQuery);

// Mini Calendar Class
(function($) {
    'use strict';

    // Mini Calendar Class
    var AquaLuxeBookingMiniCalendar = function(element, options) {
        this.$container = $(element);
        this.options = $.extend({}, AquaLuxeBookingMiniCalendar.DEFAULTS, options);
        this.init();
    };

    // Default options
    AquaLuxeBookingMiniCalendar.DEFAULTS = {
        serviceId: 0,
        months: 1,
        showTitle: false,
        showLegend: false,
        calendarSelector: '.aqualuxe-bookings-mini-calendar',
        legendSelector: '.aqualuxe-bookings-mini-calendar-legend',
        ajaxUrl: '',
        nonce: '',
        i18n: {
            available: 'Available',
            partiallyBooked: 'Partially Booked',
            fullyBooked: 'Fully Booked',
            monday: 'Mon',
            tuesday: 'Tue',
            wednesday: 'Wed',
            thursday: 'Thu',
            friday: 'Fri',
            saturday: 'Sat',
            sunday: 'Sun',
            january: 'January',
            february: 'February',
            march: 'March',
            april: 'April',
            may: 'May',
            june: 'June',
            july: 'July',
            august: 'August',
            september: 'September',
            october: 'October',
            november: 'November',
            december: 'December'
        },
        settings: {
            calendarFirstDay: 0,
            colorScheme: '#0073aa',
            calendarStyle: 'default'
        }
    };

    // Initialize
    AquaLuxeBookingMiniCalendar.prototype.init = function() {
        // Set options from data attributes
        this.options.serviceId = this.$container.data('service-id') || this.options.serviceId;
        this.options.months = this.$container.data('months') || this.options.months;
        this.options.showTitle = this.$container.data('show-title') !== false;
        this.options.showLegend = this.$container.data('show-legend') !== false;

        // Find calendar elements
        this.$calendars = this.$container.find(this.options.calendarSelector);

        // Load available dates
        this.loadAvailableDates();
    };

    // Load available dates
    AquaLuxeBookingMiniCalendar.prototype.loadAvailableDates = function() {
        var self = this;
        var date = new Date();
        var month = date.getMonth() + 1;
        var year = date.getFullYear();

        // Load dates for each month
        for (var i = 0; i < this.options.months; i++) {
            var currentMonth = (month + i) % 12;
            currentMonth = currentMonth === 0 ? 12 : currentMonth;
            var currentYear = year + Math.floor((month + i - 1) / 12);

            this.loadMonthDates(currentMonth, currentYear, i);
        }
    };

    // Load month dates
    AquaLuxeBookingMiniCalendar.prototype.loadMonthDates = function(month, year, index) {
        var self = this;
        var $calendar = $(this.$calendars[index]);

        if (!$calendar.length) {
            return;
        }

        $.ajax({
            url: this.options.ajaxUrl,
            type: 'POST',
            data: {
                action: 'get_available_dates',
                service_id: this.options.serviceId,
                month: month,
                year: year,
                nonce: this.options.nonce
            },
            success: function(response) {
                if (response.success) {
                    var dates = response.data.dates;
                    
                    // Update calendar
                    self.updateCalendar($calendar, month, year, dates);
                }
            }
        });
    };

    // Update calendar
    AquaLuxeBookingMiniCalendar.prototype.updateCalendar = function($calendar, month, year, availableDates) {
        // Get first day of month
        var firstDay = new Date(year, month - 1, 1);
        var firstDayOfWeek = firstDay.getDay();
        
        // Adjust first day of week based on settings
        firstDayOfWeek = (firstDayOfWeek - this.options.settings.calendarFirstDay + 7) % 7;
        
        // Get days in month
        var daysInMonth = new Date(year, month, 0).getDate();
        
        // Get month name
        var monthName = this.options.i18n[this.getMonthName(month - 1).toLowerCase()];
        
        // Update month header
        $calendar.find('.aqualuxe-bookings-mini-calendar-header h4').text(monthName + ' ' + year);
        
        // Get day names
        var dayNames = [];
        for (var i = 0; i < 7; i++) {
            var dayIndex = (i + this.options.settings.calendarFirstDay) % 7;
            dayNames.push(this.options.i18n[this.getDayName(dayIndex).toLowerCase()]);
        }
        
        // Update day names
        var $headerRow = $calendar.find('table thead tr');
        $headerRow.empty();
        
        for (var i = 0; i < 7; i++) {
            $headerRow.append('<th>' + dayNames[i] + '</th>');
        }
        
        // Update calendar days
        var $tbody = $calendar.find('table tbody');
        $tbody.empty();
        
        var day = 1;
        var rows = Math.ceil((daysInMonth + firstDayOfWeek) / 7);
        
        for (var row = 0; row < rows; row++) {
            var $tr = $('<tr></tr>');
            
            for (var col = 0; col < 7; col++) {
                if ((row === 0 && col < firstDayOfWeek) || day > daysInMonth) {
                    $tr.append('<td class="empty"></td>');
                } else {
                    var date = year + '-' + this.padNumber(month) + '-' + this.padNumber(day);
                    var isAvailable = $.inArray(date, availableDates) !== -1;
                    var isPast = new Date(date) < new Date().setHours(0, 0, 0, 0);
                    var className = isPast ? 'past' : (isAvailable ? 'available' : 'unavailable');
                    
                    if (isAvailable && !isPast && this.options.bookingUrl) {
                        var bookingUrl = this.options.bookingUrl + (this.options.bookingUrl.indexOf('?') !== -1 ? '&' : '?') + 'date=' + date;
                        $tr.append('<td class="' + className + '"><a href="' + bookingUrl + '">' + day + '</a></td>');
                    } else {
                        $tr.append('<td class="' + className + '">' + day + '</td>');
                    }
                    
                    day++;
                }
            }
            
            $tbody.append($tr);
        }
    };

    // Get month name
    AquaLuxeBookingMiniCalendar.prototype.getMonthName = function(month) {
        var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        return months[month];
    };

    // Get day name
    AquaLuxeBookingMiniCalendar.prototype.getDayName = function(day) {
        var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        return days[day];
    };

    // Pad number
    AquaLuxeBookingMiniCalendar.prototype.padNumber = function(number) {
        return number < 10 ? '0' + number : number;
    };

    // jQuery plugin
    $.fn.aqualuxeBookingMiniCalendar = function(options) {
        return this.each(function() {
            if (!$.data(this, 'aqualuxe.bookingMiniCalendar')) {
                $.data(this, 'aqualuxe.bookingMiniCalendar', new AquaLuxeBookingMiniCalendar(this, options));
            }
        });
    };

    // Auto-initialize
    $(document).ready(function() {
        $('.aqualuxe-bookings-mini-calendar-container').aqualuxeBookingMiniCalendar({
            ajaxUrl: aqualuxe_bookings_calendar_params.ajax_url,
            nonce: aqualuxe_bookings_calendar_params.nonce,
            bookingUrl: $('.aqualuxe-bookings-calendar-booking-button a').attr('href') || '',
            i18n: aqualuxe_bookings_calendar_params.i18n,
            settings: aqualuxe_bookings_calendar_params.settings
        });
    });

})(jQuery);