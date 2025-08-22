/**
 * AquaLuxe Bookings JavaScript
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // AquaLuxe Bookings object
    const AquaLuxeBookings = {
        /**
         * Initialize
         */
        init: function() {
            this.initBookingForm();
            this.initBookingCalendar();
            this.initMyBookings();
        },

        /**
         * Initialize booking form
         */
        initBookingForm: function() {
            // Date picker is initialized in the booking form template
            // Time picker is populated via AJAX in the booking form template
            
            // Service change event
            $(document).on('change', '.booking-form-field-service select', function() {
                const $form = $(this).closest('form');
                const $resourceField = $form.find('.booking-form-field-resource');
                const $resourceSelect = $resourceField.find('select');
                const $dateField = $form.find('.booking-form-field-date input');
                const $timeField = $form.find('.booking-form-field-time select');
                
                // Reset date and time
                $dateField.val('');
                $timeField.empty().append('<option value="">' + aqualuxeBookings.i18n.selectDate + '</option>').prop('disabled', true);
                
                // Get service ID
                const serviceId = $(this).val();
                
                if (!serviceId) {
                    // Hide resource field if no service selected
                    $resourceField.hide();
                    $resourceSelect.prop('disabled', true);
                    return;
                }
                
                // Show loading
                $resourceField.show();
                $resourceSelect.prop('disabled', true).empty().append('<option value="">' + aqualuxeBookings.i18n.loading + '</option>');
                
                // Get resources for service
                $.ajax({
                    url: aqualuxeBookings.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_get_service_resources',
                        service_id: serviceId,
                        nonce: aqualuxeBookings.nonce
                    },
                    success: function(response) {
                        if (response.success && response.data.resources.length > 0) {
                            // Populate resource select
                            $resourceSelect.empty().append('<option value="">' + aqualuxeBookings.i18n.selectResource + '</option>');
                            
                            $.each(response.data.resources, function(index, resource) {
                                $resourceSelect.append('<option value="' + resource.id + '">' + resource.title + '</option>');
                            });
                            
                            $resourceSelect.prop('disabled', false);
                        } else {
                            // Hide resource field if no resources
                            $resourceField.hide();
                            $resourceSelect.prop('disabled', true);
                        }
                    },
                    error: function() {
                        // Hide resource field on error
                        $resourceField.hide();
                        $resourceSelect.prop('disabled', true);
                    }
                });
            });
            
            // Handle booking form submission
            // This is handled in the booking form template
        },

        /**
         * Initialize booking calendar
         */
        initBookingCalendar: function() {
            // Service and resource filters
            $(document).on('change', '.booking-calendar-filter select', function() {
                const $form = $(this).closest('form');
                $form.submit();
            });
            
            // View buttons
            $(document).on('click', '.booking-calendar-view', function(e) {
                e.preventDefault();
                
                const view = $(this).data('view');
                const currentUrl = window.location.href;
                const url = new URL(currentUrl);
                
                url.searchParams.set('view', view);
                
                window.location.href = url.toString();
            });
            
            // Navigation buttons
            $(document).on('click', '.booking-calendar-nav-button', function(e) {
                e.preventDefault();
                
                const url = $(this).attr('href');
                
                if (url) {
                    window.location.href = url;
                }
            });
            
            // Event click
            $(document).on('click', '.day-event, .week-event, .list-event', function(e) {
                e.preventDefault();
                
                const bookingId = $(this).data('booking-id');
                
                if (bookingId) {
                    AquaLuxeBookings.showBookingDetails(bookingId);
                }
            });
        },

        /**
         * Initialize my bookings
         */
        initMyBookings: function() {
            // View booking details
            $(document).on('click', '.booking-action-button.view', function(e) {
                e.preventDefault();
                
                const bookingId = $(this).data('booking-id');
                
                if (bookingId) {
                    AquaLuxeBookings.showBookingDetails(bookingId);
                }
            });
            
            // Cancel booking
            $(document).on('click', '.booking-action-button.cancel', function(e) {
                e.preventDefault();
                
                const bookingId = $(this).data('booking-id');
                
                if (bookingId && confirm(aqualuxeBookings.i18n.confirmCancel)) {
                    AquaLuxeBookings.cancelBooking(bookingId);
                }
            });
            
            // Reschedule booking
            $(document).on('click', '.booking-action-button.reschedule', function(e) {
                e.preventDefault();
                
                const bookingId = $(this).data('booking-id');
                
                if (bookingId) {
                    AquaLuxeBookings.rescheduleBooking(bookingId);
                }
            });
        },

        /**
         * Show booking details
         *
         * @param {number} bookingId Booking ID
         */
        showBookingDetails: function(bookingId) {
            // Get booking details
            $.ajax({
                url: aqualuxeBookings.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_booking_details',
                    booking_id: bookingId,
                    nonce: aqualuxeBookings.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Show booking details in modal
                        AquaLuxeBookings.showBookingModal(response.data);
                    } else {
                        alert(response.data.message || aqualuxeBookings.i18n.bookingError);
                    }
                },
                error: function() {
                    alert(aqualuxeBookings.i18n.bookingError);
                }
            });
        },

        /**
         * Show booking modal
         *
         * @param {Object} booking Booking data
         */
        showBookingModal: function(booking) {
            // Create modal if it doesn't exist
            if (!$('#booking-details-modal').length) {
                $('body').append(`
                    <div id="booking-details-modal" class="booking-modal">
                        <div class="booking-modal-backdrop"></div>
                        <div class="booking-modal-content">
                            <div class="booking-modal-header">
                                <h3 class="booking-modal-title"></h3>
                                <button class="booking-modal-close">&times;</button>
                            </div>
                            <div class="booking-modal-body"></div>
                            <div class="booking-modal-footer">
                                <button class="booking-modal-button booking-modal-close-button">Close</button>
                            </div>
                        </div>
                    </div>
                `);
                
                // Close modal on click outside or close button
                $(document).on('click', '.booking-modal-backdrop, .booking-modal-close, .booking-modal-close-button', function() {
                    $('#booking-details-modal').removeClass('active');
                });
                
                // Prevent closing when clicking on modal content
                $(document).on('click', '.booking-modal-content', function(e) {
                    e.stopPropagation();
                });
                
                // Close modal on escape key
                $(document).on('keyup', function(e) {
                    if (e.key === 'Escape' && $('#booking-details-modal').hasClass('active')) {
                        $('#booking-details-modal').removeClass('active');
                    }
                });
            }
            
            // Format booking details
            const formattedDate = booking.date;
            const formattedTime = booking.time;
            const formattedEndTime = booking.end_time;
            const formattedPrice = booking.price;
            const statusClass = booking.status;
            const statusLabel = booking.status_label;
            
            // Build modal content
            let content = `
                <div class="booking-details">
                    <div class="booking-detail">
                        <div class="booking-detail-label">Reference:</div>
                        <div class="booking-detail-value">${booking.booking_reference}</div>
                    </div>
                    <div class="booking-detail">
                        <div class="booking-detail-label">Service:</div>
                        <div class="booking-detail-value">${booking.service_name}</div>
                    </div>
            `;
            
            if (booking.resource_name) {
                content += `
                    <div class="booking-detail">
                        <div class="booking-detail-label">Resource:</div>
                        <div class="booking-detail-value">${booking.resource_name}</div>
                    </div>
                `;
            }
            
            content += `
                    <div class="booking-detail">
                        <div class="booking-detail-label">Date:</div>
                        <div class="booking-detail-value">${formattedDate}</div>
                    </div>
                    <div class="booking-detail">
                        <div class="booking-detail-label">Time:</div>
                        <div class="booking-detail-value">${formattedTime} - ${formattedEndTime}</div>
                    </div>
                    <div class="booking-detail">
                        <div class="booking-detail-label">Duration:</div>
                        <div class="booking-detail-value">${booking.duration} minutes</div>
                    </div>
                    <div class="booking-detail">
                        <div class="booking-detail-label">Price:</div>
                        <div class="booking-detail-value">${formattedPrice}</div>
                    </div>
                    <div class="booking-detail">
                        <div class="booking-detail-label">Status:</div>
                        <div class="booking-detail-value">
                            <span class="booking-status ${statusClass}">${statusLabel}</span>
                        </div>
                    </div>
                    <div class="booking-detail">
                        <div class="booking-detail-label">Customer:</div>
                        <div class="booking-detail-value">${booking.customer_name}</div>
                    </div>
                    <div class="booking-detail">
                        <div class="booking-detail-label">Email:</div>
                        <div class="booking-detail-value">${booking.customer_email}</div>
                    </div>
            `;
            
            if (booking.customer_phone) {
                content += `
                    <div class="booking-detail">
                        <div class="booking-detail-label">Phone:</div>
                        <div class="booking-detail-value">${booking.customer_phone}</div>
                    </div>
                `;
            }
            
            if (booking.notes) {
                content += `
                    <div class="booking-detail">
                        <div class="booking-detail-label">Notes:</div>
                        <div class="booking-detail-value">${booking.notes}</div>
                    </div>
                `;
            }
            
            content += `</div>`;
            
            // Add actions if applicable
            if (booking.status === 'pending' || booking.status === 'confirmed') {
                content += `
                    <div class="booking-actions">
                        <button class="booking-action-button cancel" data-booking-id="${booking.id}">Cancel Booking</button>
                        <button class="booking-action-button reschedule" data-booking-id="${booking.id}">Reschedule</button>
                    </div>
                `;
            }
            
            // Update modal content
            $('#booking-details-modal .booking-modal-title').text('Booking Details');
            $('#booking-details-modal .booking-modal-body').html(content);
            
            // Show modal
            $('#booking-details-modal').addClass('active');
        },

        /**
         * Cancel booking
         *
         * @param {number} bookingId Booking ID
         */
        cancelBooking: function(bookingId) {
            // Cancel booking
            $.ajax({
                url: aqualuxeBookings.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_cancel_booking',
                    booking_id: bookingId,
                    nonce: aqualuxeBookings.nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.data.message || 'Booking cancelled successfully.');
                        
                        // Reload page
                        window.location.reload();
                    } else {
                        alert(response.data.message || aqualuxeBookings.i18n.bookingError);
                    }
                },
                error: function() {
                    alert(aqualuxeBookings.i18n.bookingError);
                }
            });
        },

        /**
         * Reschedule booking
         *
         * @param {number} bookingId Booking ID
         */
        rescheduleBooking: function(bookingId) {
            // Get booking details
            $.ajax({
                url: aqualuxeBookings.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_booking_details',
                    booking_id: bookingId,
                    nonce: aqualuxeBookings.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Show reschedule form in modal
                        AquaLuxeBookings.showRescheduleModal(response.data);
                    } else {
                        alert(response.data.message || aqualuxeBookings.i18n.bookingError);
                    }
                },
                error: function() {
                    alert(aqualuxeBookings.i18n.bookingError);
                }
            });
        },

        /**
         * Show reschedule modal
         *
         * @param {Object} booking Booking data
         */
        showRescheduleModal: function(booking) {
            // Create modal if it doesn't exist
            if (!$('#reschedule-booking-modal').length) {
                $('body').append(`
                    <div id="reschedule-booking-modal" class="booking-modal">
                        <div class="booking-modal-backdrop"></div>
                        <div class="booking-modal-content">
                            <div class="booking-modal-header">
                                <h3 class="booking-modal-title">Reschedule Booking</h3>
                                <button class="booking-modal-close">&times;</button>
                            </div>
                            <div class="booking-modal-body">
                                <form id="reschedule-booking-form">
                                    <input type="hidden" name="booking_id" value="">
                                    <div class="booking-form-field booking-form-field-date">
                                        <label for="reschedule-date">New Date <span class="required">*</span></label>
                                        <input type="text" id="reschedule-date" name="date" class="booking-date-picker" required readonly>
                                    </div>
                                    <div class="booking-form-field booking-form-field-time">
                                        <label for="reschedule-time">New Time <span class="required">*</span></label>
                                        <select id="reschedule-time" name="time" required disabled>
                                            <option value="">Select a date first</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="booking-modal-footer">
                                <button class="booking-modal-button booking-modal-close-button">Cancel</button>
                                <button class="booking-modal-button booking-modal-submit-button">Reschedule</button>
                            </div>
                        </div>
                    </div>
                `);
                
                // Close modal on click outside or close button
                $(document).on('click', '.booking-modal-backdrop, .booking-modal-close, .booking-modal-close-button', function() {
                    $('#reschedule-booking-modal').removeClass('active');
                });
                
                // Prevent closing when clicking on modal content
                $(document).on('click', '.booking-modal-content', function(e) {
                    e.stopPropagation();
                });
                
                // Close modal on escape key
                $(document).on('keyup', function(e) {
                    if (e.key === 'Escape' && $('#reschedule-booking-modal').hasClass('active')) {
                        $('#reschedule-booking-modal').removeClass('active');
                    }
                });
                
                // Submit reschedule form
                $(document).on('click', '.booking-modal-submit-button', function() {
                    const $form = $('#reschedule-booking-form');
                    
                    // Validate form
                    if (!$form.get(0).checkValidity()) {
                        $form.get(0).reportValidity();
                        return;
                    }
                    
                    // Get form data
                    const bookingId = $form.find('input[name="booking_id"]').val();
                    const date = $form.find('input[name="date"]').val();
                    const time = $form.find('select[name="time"]').val();
                    
                    // Disable submit button
                    $(this).prop('disabled', true).text('Processing...');
                    
                    // Submit reschedule
                    $.ajax({
                        url: aqualuxeBookings.ajaxUrl,
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_reschedule_booking',
                            booking_id: bookingId,
                            date: date,
                            time: time,
                            nonce: aqualuxeBookings.nonce
                        },
                        success: function(response) {
                            // Enable submit button
                            $('.booking-modal-submit-button').prop('disabled', false).text('Reschedule');
                            
                            if (response.success) {
                                alert(response.data.message || 'Booking rescheduled successfully.');
                                
                                // Close modal
                                $('#reschedule-booking-modal').removeClass('active');
                                
                                // Reload page
                                window.location.reload();
                            } else {
                                alert(response.data.message || aqualuxeBookings.i18n.bookingError);
                            }
                        },
                        error: function() {
                            // Enable submit button
                            $('.booking-modal-submit-button').prop('disabled', false).text('Reschedule');
                            
                            alert(aqualuxeBookings.i18n.bookingError);
                        }
                    });
                });
            }
            
            // Set booking ID
            $('#reschedule-booking-form input[name="booking_id"]').val(booking.id);
            
            // Initialize date picker
            const $datePicker = $('#reschedule-date');
            const $timePicker = $('#reschedule-time');
            
            $datePicker.datepicker({
                dateFormat: aqualuxeBookings.settings.dateFormat,
                minDate: '+' + aqualuxeBookings.settings.minDaysAdvance + 'd',
                maxDate: '+' + aqualuxeBookings.settings.maxDaysAdvance + 'd',
                firstDay: aqualuxeBookings.settings.firstDay,
                beforeShowDay: function(date) {
                    // Disable past dates
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    
                    if (date < today) {
                        return [false, '', aqualuxeBookings.i18n.noAvailability];
                    }
                    
                    return [true, '', ''];
                },
                onSelect: function(dateText, inst) {
                    // Clear time picker
                    $timePicker.empty().append('<option value="">' + aqualuxeBookings.i18n.loading + '</option>').prop('disabled', true);
                    
                    // Get available time slots
                    $.ajax({
                        url: aqualuxeBookings.ajaxUrl,
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_booking_check_availability',
                            service_id: booking.service_id,
                            resource_id: booking.resource_id,
                            date: dateText,
                            nonce: aqualuxeBookings.nonce
                        },
                        success: function(response) {
                            if (response.success && response.data.slots.length > 0) {
                                // Populate time picker
                                $timePicker.empty().append('<option value="">' + aqualuxeBookings.i18n.selectTime + '</option>');
                                
                                $.each(response.data.slots, function(index, slot) {
                                    $timePicker.append('<option value="' + slot.time + '">' + slot.label + '</option>');
                                });
                                
                                $timePicker.prop('disabled', false);
                            } else {
                                $timePicker.empty().append('<option value="">' + aqualuxeBookings.i18n.noAvailability + '</option>').prop('disabled', true);
                            }
                        },
                        error: function() {
                            $timePicker.empty().append('<option value="">' + aqualuxeBookings.i18n.noAvailability + '</option>').prop('disabled', true);
                        }
                    });
                }
            });
            
            // Show modal
            $('#reschedule-booking-modal').addClass('active');
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeBookings.init();
    });

})(jQuery);