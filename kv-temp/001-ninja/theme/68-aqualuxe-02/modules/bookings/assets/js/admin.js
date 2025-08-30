/**
 * Bookings Module Admin Scripts
 *
 * @package AquaLuxe
 * @subpackage Modules\Bookings
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Booking Admin Handler
     */
    var BookingAdmin = {
        /**
         * Initialize
         */
        init: function() {
            this.initCalendar();
            this.bindEvents();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            $(document).on('click', '.confirm-booking', this.handleStatusChange);
            $(document).on('click', '.complete-booking', this.handleStatusChange);
            $(document).on('click', '.cancel-booking', this.handleStatusChange);
            $(document).on('click', '.view-booking', this.handleViewBooking);
            $(document).on('change', '#aqualuxe-bookable-duration, #aqualuxe-bookable-capacity', this.validateNumericInput);
            $(document).on('change', '#aqualuxe-booking-bookable-id', this.handleBookableChange);
        },

        /**
         * Initialize calendar
         */
        initCalendar: function() {
            var calendarEl = document.getElementById('aqualuxe-booking-calendar');
            
            if (!calendarEl) {
                return;
            }
            
            // Check if FullCalendar is available
            if (typeof FullCalendar === 'undefined') {
                return;
            }
            
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                navLinks: true,
                editable: false,
                dayMaxEvents: true,
                events: function(info, successCallback, failureCallback) {
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_get_calendar_events',
                            nonce: aqualuxeBookingsAdmin.nonce,
                            start: info.startStr,
                            end: info.endStr
                        },
                        success: function(response) {
                            if (response.success) {
                                successCallback(response.data);
                            } else {
                                failureCallback(response.data.message);
                            }
                        },
                        error: function() {
                            failureCallback('Error loading events.');
                        }
                    });
                },
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    var bookingId = info.event.id;
                    BookingAdmin.viewBookingDetails(bookingId);
                }
            });
            
            calendar.render();
        },

        /**
         * Handle status change
         * 
         * @param {object} e Event object
         */
        handleStatusChange: function(e) {
            e.preventDefault();
            
            var $link = $(this);
            var url = $link.attr('href');
            var bookingId = $link.data('booking-id');
            var status = '';
            
            if ($link.hasClass('confirm-booking')) {
                status = 'confirmed';
            } else if ($link.hasClass('complete-booking')) {
                status = 'completed';
            } else if ($link.hasClass('cancel-booking')) {
                status = 'cancelled';
            }
            
            if (!status) {
                return;
            }
            
            // Confirm action
            if (!confirm('Are you sure you want to mark this booking as ' + status + '?')) {
                return;
            }
            
            // Show loading
            $link.text('Processing...');
            
            // Send AJAX request
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_update_booking_status',
                    _wpnonce: aqualuxeBookingsAdmin.nonce,
                    booking_id: bookingId,
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        // Reload page
                        window.location.reload();
                    } else {
                        alert(response.data.message || 'Error updating booking status.');
                        $link.text($link.data('original-text'));
                    }
                },
                error: function() {
                    alert('Error updating booking status.');
                    $link.text($link.data('original-text'));
                }
            });
        },

        /**
         * Handle view booking
         * 
         * @param {object} e Event object
         */
        handleViewBooking: function(e) {
            e.preventDefault();
            
            var $link = $(this);
            var bookingId = $link.data('booking-id');
            
            BookingAdmin.viewBookingDetails(bookingId);
        },

        /**
         * View booking details
         * 
         * @param {number} bookingId 
         */
        viewBookingDetails: function(bookingId) {
            // Send AJAX request
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_booking_details',
                    _wpnonce: aqualuxeBookingsAdmin.nonce,
                    booking_id: bookingId
                },
                success: function(response) {
                    if (response.success) {
                        BookingAdmin.showBookingDetailsModal(response.data);
                    } else {
                        alert(response.data.message || 'Error loading booking details.');
                    }
                },
                error: function() {
                    alert('Error loading booking details.');
                }
            });
        },

        /**
         * Show booking details modal
         * 
         * @param {object} booking 
         */
        showBookingDetailsModal: function(booking) {
            // Create modal if it doesn't exist
            if (!$('#aqualuxe-booking-details-modal').length) {
                $('body').append('<div id="aqualuxe-booking-details-modal" class="aqualuxe-modal"><div class="aqualuxe-modal-content"><span class="aqualuxe-modal-close">&times;</span><div class="aqualuxe-modal-body"></div></div></div>');
                
                // Close modal when clicking on close button or outside the modal
                $(document).on('click', '.aqualuxe-modal-close, .aqualuxe-modal', function(e) {
                    if (e.target === this) {
                        $('.aqualuxe-modal').hide();
                    }
                });
            }
            
            // Build modal content
            var html = '<h2>Booking #' + booking.id + '</h2>';
            
            html += '<div class="aqualuxe-booking-details">';
            
            // Booking details
            html += '<div class="aqualuxe-booking-detail"><span class="aqualuxe-booking-detail-label">Status:</span> <span class="aqualuxe-booking-status-' + booking.status + '">' + booking.status_label + '</span></div>';
            
            if (booking.bookable) {
                html += '<div class="aqualuxe-booking-detail"><span class="aqualuxe-booking-detail-label">Service:</span> ' + booking.bookable.title + '</div>';
            }
            
            html += '<div class="aqualuxe-booking-detail"><span class="aqualuxe-booking-detail-label">Date:</span> ' + booking.date + '</div>';
            html += '<div class="aqualuxe-booking-detail"><span class="aqualuxe-booking-detail-label">Time:</span> ' + booking.time + '</div>';
            html += '<div class="aqualuxe-booking-detail"><span class="aqualuxe-booking-detail-label">Created:</span> ' + booking.created + '</div>';
            
            // Customer details
            html += '<h3>Customer Information</h3>';
            html += '<div class="aqualuxe-booking-detail"><span class="aqualuxe-booking-detail-label">Name:</span> ' + booking.customer.name + '</div>';
            html += '<div class="aqualuxe-booking-detail"><span class="aqualuxe-booking-detail-label">Email:</span> <a href="mailto:' + booking.customer.email + '">' + booking.customer.email + '</a></div>';
            
            if (booking.customer.phone) {
                html += '<div class="aqualuxe-booking-detail"><span class="aqualuxe-booking-detail-label">Phone:</span> ' + booking.customer.phone + '</div>';
            }
            
            if (booking.customer.notes) {
                html += '<div class="aqualuxe-booking-detail"><span class="aqualuxe-booking-detail-label">Notes:</span> ' + booking.customer.notes + '</div>';
            }
            
            // Actions
            html += '<div class="aqualuxe-booking-actions">';
            html += '<a href="' + booking.edit_url + '" class="button button-primary">Edit Booking</a> ';
            
            if (booking.status === 'pending') {
                html += '<a href="#" class="button confirm-booking" data-booking-id="' + booking.id + '">Confirm</a> ';
            }
            
            if (booking.status === 'pending' || booking.status === 'confirmed') {
                html += '<a href="#" class="button complete-booking" data-booking-id="' + booking.id + '">Complete</a> ';
                html += '<a href="#" class="button cancel-booking" data-booking-id="' + booking.id + '">Cancel</a>';
            }
            
            html += '</div>';
            
            html += '</div>';
            
            // Update modal content and show it
            $('#aqualuxe-booking-details-modal .aqualuxe-modal-body').html(html);
            $('#aqualuxe-booking-details-modal').show();
        },

        /**
         * Validate numeric input
         */
        validateNumericInput: function() {
            var value = $(this).val();
            
            // Ensure value is a positive integer
            value = parseInt(value);
            
            if (isNaN(value) || value <= 0) {
                value = 1;
            }
            
            $(this).val(value);
        },

        /**
         * Handle bookable change
         */
        handleBookableChange: function() {
            var bookableId = $(this).val();
            
            if (!bookableId) {
                return;
            }
            
            // Get bookable details
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_bookable_details',
                    _wpnonce: aqualuxeBookingsAdmin.nonce,
                    bookable_id: bookableId
                },
                success: function(response) {
                    if (response.success) {
                        // Update duration field if it exists
                        if ($('#aqualuxe-booking-duration').length && response.data.duration) {
                            $('#aqualuxe-booking-duration').val(response.data.duration);
                        }
                        
                        // Update location field if it exists
                        if ($('#aqualuxe-booking-location').length && response.data.location) {
                            $('#aqualuxe-booking-location').val(response.data.location);
                        }
                    }
                }
            });
        }
    };

    /**
     * Bookable Admin Handler
     */
    var BookableAdmin = {
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            $(document).on('change', '.aqualuxe-bookable-availability input[type="checkbox"]', this.toggleTimeFields);
        },

        /**
         * Toggle time fields
         */
        toggleTimeFields: function() {
            var $row = $(this).closest('tr');
            var $timeFields = $row.find('input[type="time"]');
            
            if ($(this).is(':checked')) {
                $timeFields.prop('disabled', false);
            } else {
                $timeFields.prop('disabled', true);
            }
        }
    };

    /**
     * Document ready
     */
    $(document).ready(function() {
        BookingAdmin.init();
        BookableAdmin.init();
        
        // Initialize time fields
        $('.aqualuxe-bookable-availability input[type="checkbox"]').each(function() {
            var $row = $(this).closest('tr');
            var $timeFields = $row.find('input[type="time"]');
            
            if ($(this).is(':checked')) {
                $timeFields.prop('disabled', false);
            } else {
                $timeFields.prop('disabled', true);
            }
        });
    });

})(jQuery);

/**
 * Modal styles
 */
document.addEventListener('DOMContentLoaded', function() {
    // Add modal styles if they don't exist
    if (!document.getElementById('aqualuxe-modal-styles')) {
        var style = document.createElement('style');
        style.id = 'aqualuxe-modal-styles';
        style.innerHTML = `
            .aqualuxe-modal {
                display: none;
                position: fixed;
                z-index: 9999;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.5);
            }
            
            .aqualuxe-modal-content {
                position: relative;
                background-color: #fefefe;
                margin: 10% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 80%;
                max-width: 600px;
                border-radius: 4px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            
            .aqualuxe-modal-close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
                cursor: pointer;
            }
            
            .aqualuxe-modal-close:hover,
            .aqualuxe-modal-close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }
            
            .aqualuxe-booking-details {
                margin-top: 20px;
            }
            
            .aqualuxe-booking-detail {
                margin-bottom: 10px;
            }
            
            .aqualuxe-booking-detail-label {
                font-weight: bold;
                margin-right: 10px;
                min-width: 100px;
                display: inline-block;
            }
            
            .aqualuxe-booking-actions {
                margin-top: 20px;
                padding-top: 20px;
                border-top: 1px solid #eee;
            }
            
            .aqualuxe-booking-status-pending {
                color: #f39c12;
                font-weight: bold;
            }
            
            .aqualuxe-booking-status-confirmed {
                color: #2ecc71;
                font-weight: bold;
            }
            
            .aqualuxe-booking-status-completed {
                color: #3498db;
                font-weight: bold;
            }
            
            .aqualuxe-booking-status-cancelled {
                color: #e74c3c;
                font-weight: bold;
            }
        `;
        document.head.appendChild(style);
    }
});