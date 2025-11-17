/**
 * Admin Calendar Script
 *
 * Handles the admin calendar functionality.
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // Admin Calendar Class
    var AquaLuxeAdminCalendar = function(element, options) {
        this.$container = $(element);
        this.options = $.extend({}, AquaLuxeAdminCalendar.DEFAULTS, options);
        this.calendar = null;
        this.init();
    };

    // Default options
    AquaLuxeAdminCalendar.DEFAULTS = {
        serviceId: 0,
        view: 'dayGridMonth',
        date: '',
        calendarSelector: '#aqualuxe-bookings-admin-calendar',
        eventDialogSelector: '#aqualuxe-bookings-event-dialog',
        availabilityDialogSelector: '#aqualuxe-bookings-availability-dialog',
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
            editBooking: 'Edit Booking',
            deleteBooking: 'Delete Booking',
            confirmDelete: 'Are you sure you want to delete this booking?',
            addAvailabilityRule: 'Add Availability Rule',
            editAvailabilityRule: 'Edit Availability Rule',
            deleteAvailabilityRule: 'Delete Availability Rule',
            confirmDeleteRule: 'Are you sure you want to delete this availability rule?',
            save: 'Save',
            cancel: 'Cancel',
            delete: 'Delete',
            serviceLabel: 'Service',
            startDateLabel: 'Start Date',
            endDateLabel: 'End Date',
            bookableLabel: 'Bookable',
            yes: 'Yes',
            no: 'No'
        },
        settings: {
            calendarFirstDay: 0,
            colorScheme: '#0073aa'
        }
    };

    // Initialize
    AquaLuxeAdminCalendar.prototype.init = function() {
        // Find calendar element
        this.$calendar = $(this.options.calendarSelector);
        this.$eventDialog = $(this.options.eventDialogSelector);
        this.$availabilityDialog = $(this.options.availabilityDialogSelector);

        // Initialize calendar
        this.initCalendar();

        // Initialize dialogs
        this.initDialogs();

        // Bind events
        this.bindEvents();
    };

    // Initialize calendar
    AquaLuxeAdminCalendar.prototype.initCalendar = function() {
        var self = this;
        var calendarEl = this.$calendar[0];

        if (!calendarEl) {
            return;
        }

        // Create calendar
        this.calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: this.options.view,
            initialDate: this.options.date || new Date(),
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            firstDay: parseInt(this.options.settings.calendarFirstDay) || 0,
            weekends: true,
            allDaySlot: true,
            slotDuration: '00:30:00',
            slotLabelInterval: '01:00:00',
            snapDuration: '00:15:00',
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: 'short'
            },
            dayMaxEvents: 0,
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
            selectable: true,
            select: function(info) {
                // Open availability dialog
                self.openAvailabilityDialog({
                    service_id: self.options.serviceId,
                    from_date: info.startStr,
                    to_date: info.endStr
                });
            },
            eventClick: function(info) {
                // Handle event click
                if (info.event.extendedProps.type === 'booking') {
                    self.openBookingDialog(info.event.id);
                } else if (info.event.extendedProps.type === 'availability_rule') {
                    self.openAvailabilityDialog({
                        rule_id: info.event.extendedProps.rule_id,
                        service_id: info.event.extendedProps.service_id,
                        from_date: info.event.start,
                        to_date: info.event.end,
                        bookable: info.event.extendedProps.bookable
                    });
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
    };

    // Initialize dialogs
    AquaLuxeAdminCalendar.prototype.initDialogs = function() {
        var self = this;

        // Initialize event dialog
        if (this.$eventDialog.length && $.fn.dialog) {
            this.$eventDialog.dialog({
                autoOpen: false,
                modal: true,
                width: 500,
                height: 'auto',
                resizable: false,
                draggable: false,
                closeOnEscape: true,
                close: function() {
                    $('.event-dialog-details', self.$eventDialog).hide();
                    $('.event-dialog-loading', self.$eventDialog).show();
                }
            });
        }

        // Initialize availability dialog
        if (this.$availabilityDialog.length && $.fn.dialog) {
            this.$availabilityDialog.dialog({
                autoOpen: false,
                modal: true,
                width: 400,
                height: 'auto',
                resizable: false,
                draggable: false,
                closeOnEscape: true,
                close: function() {
                    $('#availability-form', self.$availabilityDialog)[0].reset();
                    $('#rule_id', self.$availabilityDialog).val(0);
                    $('.delete-rule', self.$availabilityDialog).hide();
                }
            });
        }
    };

    // Bind events
    AquaLuxeAdminCalendar.prototype.bindEvents = function() {
        var self = this;

        // Handle update status
        $('.update-status', this.$eventDialog).on('click', function() {
            var bookingId = $(this).data('booking-id');
            var status = $('#booking-status', self.$eventDialog).val();
            
            self.updateBookingStatus(bookingId, status);
        });

        // Handle delete booking
        $('.delete-booking', this.$eventDialog).on('click', function(e) {
            e.preventDefault();
            
            if (!confirm(self.options.i18n.confirmDelete)) {
                return;
            }
            
            var bookingId = $(this).data('booking-id');
            self.deleteBooking(bookingId);
        });

        // Handle save availability rule
        $('#availability-form', this.$availabilityDialog).on('submit', function(e) {
            e.preventDefault();
            
            var ruleId = $('#rule_id', self.$availabilityDialog).val();
            var serviceId = $('#availability_service_id', self.$availabilityDialog).val();
            var fromDate = $('#availability_from_date', self.$availabilityDialog).val();
            var toDate = $('#availability_to_date', self.$availabilityDialog).val();
            var bookable = $('#availability_bookable', self.$availabilityDialog).val();
            
            self.saveAvailabilityRule(ruleId, serviceId, fromDate, toDate, bookable);
        });

        // Handle delete availability rule
        $('.delete-rule', this.$availabilityDialog).on('click', function(e) {
            e.preventDefault();
            
            if (!confirm(self.options.i18n.confirmDeleteRule)) {
                return;
            }
            
            var ruleId = $('#rule_id', self.$availabilityDialog).val();
            
            if (!ruleId) {
                self.$availabilityDialog.dialog('close');
                return;
            }
            
            self.deleteAvailabilityRule(ruleId);
        });

        // Handle cancel rule
        $('.cancel-rule', this.$availabilityDialog).on('click', function() {
            self.$availabilityDialog.dialog('close');
        });
    };

    // Open booking dialog
    AquaLuxeAdminCalendar.prototype.openBookingDialog = function(bookingId) {
        var self = this;

        // Open dialog
        this.$eventDialog.dialog('open');
        
        // Show loading
        $('.event-dialog-details', this.$eventDialog).hide();
        $('.event-dialog-loading', this.$eventDialog).show();
        
        // Get booking details
        $.ajax({
            url: this.options.ajaxUrl,
            type: 'POST',
            data: {
                action: 'get_booking_details',
                booking_id: bookingId,
                nonce: this.options.nonce
            },
            success: function(response) {
                if (response.success) {
                    var booking = response.data.booking;
                    
                    // Set dialog title
                    self.$eventDialog.dialog('option', 'title', self.options.i18n.bookingDetails);
                    
                    // Fill booking details
                    $('.event-title', self.$eventDialog).text(booking.customer_name + ' - ' + booking.service_name);
                    $('.booking-id', self.$eventDialog).text(booking.booking_id);
                    $('.service-name', self.$eventDialog).text(booking.service_name);
                    $('.booking-date', self.$eventDialog).text(booking.formatted_date);
                    $('.booking-time', self.$eventDialog).text(booking.formatted_time);
                    $('.booking-status', self.$eventDialog).text(self.getStatusLabel(booking.status));
                    $('.booking-quantity', self.$eventDialog).text(booking.quantity);
                    $('.booking-total', self.$eventDialog).text(booking.formatted_total);
                    $('.customer-name', self.$eventDialog).text(booking.customer_name);
                    $('.customer-email', self.$eventDialog).text(booking.customer_email);
                    $('.customer-phone', self.$eventDialog).text(booking.customer_phone);
                    
                    // Set status dropdown
                    $('#booking-status', self.$eventDialog).val(booking.status);
                    
                    // Set action URLs
                    $('.view-booking', self.$eventDialog).attr('href', ajaxurl.replace('admin-ajax.php', 'post.php?action=edit&post=' + booking.id));
                    $('.edit-booking', self.$eventDialog).attr('href', ajaxurl.replace('admin-ajax.php', 'post.php?action=edit&post=' + booking.id));
                    
                    // Set data attributes for actions
                    $('.update-status', self.$eventDialog).data('booking-id', booking.id);
                    $('.delete-booking', self.$eventDialog).data('booking-id', booking.id);
                    
                    // Show customer notes if available
                    if (booking.customer_notes) {
                        $('.customer-notes', self.$eventDialog).text(booking.customer_notes);
                        $('.event-notes', self.$eventDialog).show();
                    } else {
                        $('.event-notes', self.$eventDialog).hide();
                    }
                    
                    // Hide loading and show details
                    $('.event-dialog-loading', self.$eventDialog).hide();
                    $('.event-dialog-details', self.$eventDialog).show();
                } else {
                    alert(response.data.message);
                    self.$eventDialog.dialog('close');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                self.$eventDialog.dialog('close');
            }
        });
    };

    // Open availability dialog
    AquaLuxeAdminCalendar.prototype.openAvailabilityDialog = function(data) {
        // Set dialog title
        if (data.rule_id) {
            this.$availabilityDialog.dialog('option', 'title', this.options.i18n.editAvailabilityRule);
            $('.delete-rule', this.$availabilityDialog).show();
        } else {
            this.$availabilityDialog.dialog('option', 'title', this.options.i18n.addAvailabilityRule);
            $('.delete-rule', this.$availabilityDialog).hide();
        }
        
        // Fill form fields
        $('#rule_id', this.$availabilityDialog).val(data.rule_id || 0);
        $('#availability_service_id', this.$availabilityDialog).val(data.service_id || this.options.serviceId);
        
        if (data.from_date) {
            var fromDate = data.from_date instanceof Date ? data.from_date : new Date(data.from_date);
            $('#availability_from_date', this.$availabilityDialog).datepicker('setDate', fromDate);
        }
        
        if (data.to_date) {
            var toDate = data.to_date instanceof Date ? data.to_date : new Date(data.to_date);
            $('#availability_to_date', this.$availabilityDialog).datepicker('setDate', toDate);
        }
        
        $('#availability_bookable', this.$availabilityDialog).val(data.bookable !== undefined ? (data.bookable ? '1' : '0') : '1');
        
        // Open dialog
        this.$availabilityDialog.dialog('open');
    };

    // Update booking status
    AquaLuxeAdminCalendar.prototype.updateBookingStatus = function(bookingId, status) {
        var self = this;

        $.ajax({
            url: this.options.ajaxUrl,
            type: 'POST',
            data: {
                action: 'update_booking_status',
                booking_id: bookingId,
                status: status,
                nonce: this.options.nonce
            },
            beforeSend: function() {
                $('.update-status', self.$eventDialog).prop('disabled', true).text('Updating...');
            },
            success: function(response) {
                if (response.success) {
                    self.$eventDialog.dialog('close');
                    self.calendar.refetchEvents();
                } else {
                    alert(response.data.message);
                }
                $('.update-status', self.$eventDialog).prop('disabled', false).text('Update');
            },
            error: function() {
                alert('An error occurred. Please try again.');
                $('.update-status', self.$eventDialog).prop('disabled', false).text('Update');
            }
        });
    };

    // Delete booking
    AquaLuxeAdminCalendar.prototype.deleteBooking = function(bookingId) {
        var self = this;

        $.ajax({
            url: this.options.ajaxUrl,
            type: 'POST',
            data: {
                action: 'delete_booking',
                booking_id: bookingId,
                nonce: this.options.nonce
            },
            beforeSend: function() {
                $('.delete-booking', self.$eventDialog).prop('disabled', true).text('Deleting...');
            },
            success: function(response) {
                if (response.success) {
                    self.$eventDialog.dialog('close');
                    self.calendar.refetchEvents();
                } else {
                    alert(response.data.message);
                    $('.delete-booking', self.$eventDialog).prop('disabled', false).text('Delete Booking');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                $('.delete-booking', self.$eventDialog).prop('disabled', false).text('Delete Booking');
            }
        });
    };

    // Save availability rule
    AquaLuxeAdminCalendar.prototype.saveAvailabilityRule = function(ruleId, serviceId, fromDate, toDate, bookable) {
        var self = this;

        $.ajax({
            url: this.options.ajaxUrl,
            type: 'POST',
            data: {
                action: 'save_availability_rule',
                rule_id: ruleId,
                service_id: serviceId,
                date_from: fromDate,
                date_to: toDate,
                bookable: bookable,
                nonce: this.options.nonce
            },
            beforeSend: function() {
                $('#availability-form button[type="submit"]', self.$availabilityDialog).prop('disabled', true).text('Saving...');
            },
            success: function(response) {
                if (response.success) {
                    self.$availabilityDialog.dialog('close');
                    self.calendar.refetchEvents();
                } else {
                    alert(response.data.message);
                }
                $('#availability-form button[type="submit"]', self.$availabilityDialog).prop('disabled', false).text('Save');
            },
            error: function() {
                alert('An error occurred. Please try again.');
                $('#availability-form button[type="submit"]', self.$availabilityDialog).prop('disabled', false).text('Save');
            }
        });
    };

    // Delete availability rule
    AquaLuxeAdminCalendar.prototype.deleteAvailabilityRule = function(ruleId) {
        var self = this;

        $.ajax({
            url: this.options.ajaxUrl,
            type: 'POST',
            data: {
                action: 'delete_availability_rule',
                rule_id: ruleId,
                nonce: this.options.nonce
            },
            beforeSend: function() {
                $('.delete-rule', self.$availabilityDialog).prop('disabled', true).text('Deleting...');
            },
            success: function(response) {
                if (response.success) {
                    self.$availabilityDialog.dialog('close');
                    self.calendar.refetchEvents();
                } else {
                    alert(response.data.message);
                    $('.delete-rule', self.$availabilityDialog).prop('disabled', false).text('Delete');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                $('.delete-rule', self.$availabilityDialog).prop('disabled', false).text('Delete');
            }
        });
    };

    // Get status label
    AquaLuxeAdminCalendar.prototype.getStatusLabel = function(status) {
        switch (status) {
            case 'aqualuxe-pending':
                return 'Pending';
            case 'aqualuxe-confirmed':
                return 'Confirmed';
            case 'aqualuxe-completed':
                return 'Completed';
            case 'aqualuxe-cancelled':
                return 'Cancelled';
            default:
                return status.replace('aqualuxe-', '');
        }
    };

    // jQuery plugin
    $.fn.aqualuxeAdminCalendar = function(options) {
        return this.each(function() {
            if (!$.data(this, 'aqualuxe.adminCalendar')) {
                $.data(this, 'aqualuxe.adminCalendar', new AquaLuxeAdminCalendar(this, options));
            }
        });
    };

    // Auto-initialize
    $(document).ready(function() {
        $('.aqualuxe-bookings-calendar-page').aqualuxeAdminCalendar({
            serviceId: $('#service_id').val() || 0,
            view: $('#view').val() + 'Grid' || 'dayGridMonth',
            date: $('#date').val() || '',
            ajaxUrl: ajaxurl,
            nonce: aqualuxe_bookings_calendar_params.nonce,
            i18n: aqualuxe_bookings_calendar_params.i18n,
            settings: aqualuxe_bookings_calendar_params.settings
        });
    });

})(jQuery);