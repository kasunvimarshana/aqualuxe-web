/**
 * Bookings Admin Module
 * 
 * Handles admin functionality for the bookings module.
 */

(function($) {
    'use strict';

    // Booking Calendar Admin Class
    class AquaLuxeBookingCalendarAdmin {
        /**
         * Constructor
         * 
         * @param {HTMLElement} element Calendar element
         * @param {Object} options Calendar options
         */
        constructor(element, options) {
            // Set properties
            this.calendar = $(element);
            this.options = $.extend({}, {
                ajaxUrl: aqualuxeBookingsAdmin.ajaxUrl,
                nonce: aqualuxeBookingsAdmin.nonce
            }, options);
            
            // Set elements
            this.monthSelect = this.calendar.find('.aqualuxe-booking-calendar__month-select');
            this.yearSelect = this.calendar.find('.aqualuxe-booking-calendar__year-select');
            this.serviceSelect = this.calendar.find('.aqualuxe-booking-calendar__service-select');
            this.calendarContainer = this.calendar.find('.aqualuxe-booking-calendar__container');
            
            // Initialize calendar
            this.init();
        }
        
        /**
         * Initialize calendar
         */
        init() {
            // Add event listeners
            this.addEventListeners();
            
            // Initialize tooltips
            this.initTooltips();
            
            // Initialize drag and drop
            this.initDragAndDrop();
        }
        
        /**
         * Add event listeners
         */
        addEventListeners() {
            // Month select change
            this.monthSelect.on('change', () => {
                // Update URL
                this.updateUrl();
                
                // Reload page
                window.location.reload();
            });
            
            // Year select change
            this.yearSelect.on('change', () => {
                // Update URL
                this.updateUrl();
                
                // Reload page
                window.location.reload();
            });
            
            // Service select change
            this.serviceSelect.on('change', () => {
                // Update URL
                this.updateUrl();
                
                // Reload page
                window.location.reload();
            });
            
            // Add booking button click
            this.calendar.find('.aqualuxe-booking-calendar__add-booking').on('click', (e) => {
                // Prevent default action
                e.preventDefault();
                
                // Get date
                const date = $(e.currentTarget).data('date');
                
                // Open add booking modal
                this.openAddBookingModal(date);
            });
            
            // Edit booking button click
            this.calendar.find('.aqualuxe-booking-calendar__edit-booking').on('click', (e) => {
                // Prevent default action
                e.preventDefault();
                
                // Get booking ID
                const bookingId = $(e.currentTarget).data('booking-id');
                
                // Open edit booking modal
                this.openEditBookingModal(bookingId);
            });
            
            // Delete booking button click
            this.calendar.find('.aqualuxe-booking-calendar__delete-booking').on('click', (e) => {
                // Prevent default action
                e.preventDefault();
                
                // Get booking ID
                const bookingId = $(e.currentTarget).data('booking-id');
                
                // Confirm deletion
                if (confirm('Are you sure you want to delete this booking?')) {
                    // Delete booking
                    this.deleteBooking(bookingId);
                }
            });
        }
        
        /**
         * Initialize tooltips
         */
        initTooltips() {
            // Initialize tooltips
            this.calendar.find('[data-tooltip]').each(function() {
                // Get tooltip content
                const tooltipContent = $(this).data('tooltip');
                
                // Add tooltip
                $(this).tooltip({
                    content: tooltipContent,
                    position: {
                        my: 'center bottom',
                        at: 'center top-10',
                        collision: 'flipfit'
                    }
                });
            });
        }
        
        /**
         * Initialize drag and drop
         */
        initDragAndDrop() {
            // Make bookings draggable
            this.calendar.find('.aqualuxe-booking-calendar__booking').draggable({
                revert: 'invalid',
                helper: 'clone',
                cursor: 'move',
                opacity: 0.7,
                zIndex: 100,
                containment: this.calendarContainer,
                start: (event, ui) => {
                    // Add dragging class
                    $(event.target).addClass('aqualuxe-booking-calendar__booking--dragging');
                },
                stop: (event, ui) => {
                    // Remove dragging class
                    $(event.target).removeClass('aqualuxe-booking-calendar__booking--dragging');
                }
            });
            
            // Make cells droppable
            this.calendar.find('.aqualuxe-booking-calendar__cell--available').droppable({
                accept: '.aqualuxe-booking-calendar__booking',
                hoverClass: 'aqualuxe-booking-calendar__cell--drop-hover',
                drop: (event, ui) => {
                    // Get booking ID
                    const bookingId = ui.draggable.data('booking-id');
                    
                    // Get date
                    const date = $(event.target).data('date');
                    
                    // Move booking
                    this.moveBooking(bookingId, date);
                }
            });
        }
        
        /**
         * Update URL
         */
        updateUrl() {
            // Get month
            const month = this.monthSelect.val();
            
            // Get year
            const year = this.yearSelect.val();
            
            // Get service ID
            const serviceId = this.serviceSelect.val();
            
            // Create URL
            let url = window.location.pathname + '?page=aqualuxe-booking-calendar';
            
            // Add month
            if (month) {
                url += '&month=' + month;
            }
            
            // Add year
            if (year) {
                url += '&year=' + year;
            }
            
            // Add service ID
            if (serviceId) {
                url += '&service_id=' + serviceId;
            }
            
            // Update browser history
            window.history.replaceState({}, '', url);
        }
        
        /**
         * Open add booking modal
         * 
         * @param {string} date Date
         */
        openAddBookingModal(date) {
            // Create modal
            const modal = $('<div>', {
                class: 'aqualuxe-booking-modal',
                title: 'Add Booking'
            });
            
            // Create form
            const form = $('<form>', {
                class: 'aqualuxe-booking-form'
            });
            
            // Add date field
            form.append($('<div>', {
                class: 'aqualuxe-booking-form__field'
            }).append($('<label>', {
                for: 'booking-date',
                text: 'Date'
            })).append($('<input>', {
                type: 'text',
                id: 'booking-date',
                name: 'date',
                value: date,
                readonly: true
            })));
            
            // Add service field
            const serviceField = $('<div>', {
                class: 'aqualuxe-booking-form__field'
            }).append($('<label>', {
                for: 'booking-service',
                text: 'Service'
            })).append($('<select>', {
                id: 'booking-service',
                name: 'service_id',
                required: true
            }).append($('<option>', {
                value: '',
                text: 'Select Service'
            })));
            
            // Add services to select
            if (window.aqualuxeServices) {
                Object.keys(window.aqualuxeServices).forEach((serviceId) => {
                    serviceField.find('select').append($('<option>', {
                        value: serviceId,
                        text: window.aqualuxeServices[serviceId].name
                    }));
                });
            }
            
            // Add service field to form
            form.append(serviceField);
            
            // Add time field
            form.append($('<div>', {
                class: 'aqualuxe-booking-form__field'
            }).append($('<label>', {
                for: 'booking-time',
                text: 'Time'
            })).append($('<select>', {
                id: 'booking-time',
                name: 'time',
                required: true,
                disabled: true
            }).append($('<option>', {
                value: '',
                text: 'Select Time'
            }))));
            
            // Add customer name field
            form.append($('<div>', {
                class: 'aqualuxe-booking-form__field'
            }).append($('<label>', {
                for: 'booking-customer-name',
                text: 'Customer Name'
            })).append($('<input>', {
                type: 'text',
                id: 'booking-customer-name',
                name: 'customer_name',
                required: true
            })));
            
            // Add customer email field
            form.append($('<div>', {
                class: 'aqualuxe-booking-form__field'
            }).append($('<label>', {
                for: 'booking-customer-email',
                text: 'Customer Email'
            })).append($('<input>', {
                type: 'email',
                id: 'booking-customer-email',
                name: 'customer_email',
                required: true
            })));
            
            // Add customer phone field
            form.append($('<div>', {
                class: 'aqualuxe-booking-form__field'
            }).append($('<label>', {
                for: 'booking-customer-phone',
                text: 'Customer Phone'
            })).append($('<input>', {
                type: 'tel',
                id: 'booking-customer-phone',
                name: 'customer_phone',
                required: true
            })));
            
            // Add customer notes field
            form.append($('<div>', {
                class: 'aqualuxe-booking-form__field'
            }).append($('<label>', {
                for: 'booking-customer-notes',
                text: 'Notes'
            })).append($('<textarea>', {
                id: 'booking-customer-notes',
                name: 'customer_notes',
                rows: 3
            })));
            
            // Add form to modal
            modal.append(form);
            
            // Add modal to body
            $('body').append(modal);
            
            // Initialize modal
            modal.dialog({
                modal: true,
                width: 500,
                buttons: {
                    'Add Booking': () => {
                        // Submit form
                        this.submitAddBookingForm(form, modal);
                    },
                    'Cancel': () => {
                        // Close modal
                        modal.dialog('close');
                    }
                },
                close: () => {
                    // Remove modal
                    modal.remove();
                }
            });
            
            // Add event listener to service select
            form.find('#booking-service').on('change', () => {
                // Get service ID
                const serviceId = form.find('#booking-service').val();
                
                // Get date
                const date = form.find('#booking-date').val();
                
                // Update time slots
                this.updateTimeSlots(serviceId, date, form.find('#booking-time'));
            });
        }
        
        /**
         * Open edit booking modal
         * 
         * @param {number} bookingId Booking ID
         */
        openEditBookingModal(bookingId) {
            // Send AJAX request
            $.ajax({
                url: this.options.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_booking',
                    nonce: this.options.nonce,
                    booking_id: bookingId
                },
                success: (response) => {
                    // Check if request was successful
                    if (response.success) {
                        // Get booking data
                        const bookingData = response.data.booking;
                        
                        // Create modal
                        const modal = $('<div>', {
                            class: 'aqualuxe-booking-modal',
                            title: 'Edit Booking'
                        });
                        
                        // Create form
                        const form = $('<form>', {
                            class: 'aqualuxe-booking-form'
                        });
                        
                        // Add booking ID field
                        form.append($('<input>', {
                            type: 'hidden',
                            name: 'booking_id',
                            value: bookingId
                        }));
                        
                        // Add date field
                        form.append($('<div>', {
                            class: 'aqualuxe-booking-form__field'
                        }).append($('<label>', {
                            for: 'booking-date',
                            text: 'Date'
                        })).append($('<input>', {
                            type: 'text',
                            id: 'booking-date',
                            name: 'date',
                            value: bookingData.date,
                            required: true
                        })));
                        
                        // Add service field
                        const serviceField = $('<div>', {
                            class: 'aqualuxe-booking-form__field'
                        }).append($('<label>', {
                            for: 'booking-service',
                            text: 'Service'
                        })).append($('<select>', {
                            id: 'booking-service',
                            name: 'service_id',
                            required: true
                        }).append($('<option>', {
                            value: '',
                            text: 'Select Service'
                        })));
                        
                        // Add services to select
                        if (window.aqualuxeServices) {
                            Object.keys(window.aqualuxeServices).forEach((serviceId) => {
                                serviceField.find('select').append($('<option>', {
                                    value: serviceId,
                                    text: window.aqualuxeServices[serviceId].name,
                                    selected: serviceId === bookingData.service_id
                                }));
                            });
                        }
                        
                        // Add service field to form
                        form.append(serviceField);
                        
                        // Add time field
                        const timeField = $('<div>', {
                            class: 'aqualuxe-booking-form__field'
                        }).append($('<label>', {
                            for: 'booking-time',
                            text: 'Time'
                        })).append($('<select>', {
                            id: 'booking-time',
                            name: 'time',
                            required: true
                        }).append($('<option>', {
                            value: '',
                            text: 'Select Time'
                        })));
                        
                        // Add time field to form
                        form.append(timeField);
                        
                        // Add status field
                        const statusField = $('<div>', {
                            class: 'aqualuxe-booking-form__field'
                        }).append($('<label>', {
                            for: 'booking-status',
                            text: 'Status'
                        })).append($('<select>', {
                            id: 'booking-status',
                            name: 'status',
                            required: true
                        }));
                        
                        // Add statuses to select
                        const statuses = {
                            'pending': 'Pending',
                            'confirmed': 'Confirmed',
                            'completed': 'Completed',
                            'cancelled': 'Cancelled'
                        };
                        
                        Object.keys(statuses).forEach((statusKey) => {
                            statusField.find('select').append($('<option>', {
                                value: statusKey,
                                text: statuses[statusKey],
                                selected: statusKey === bookingData.status
                            }));
                        });
                        
                        // Add status field to form
                        form.append(statusField);
                        
                        // Add customer name field
                        form.append($('<div>', {
                            class: 'aqualuxe-booking-form__field'
                        }).append($('<label>', {
                            for: 'booking-customer-name',
                            text: 'Customer Name'
                        })).append($('<input>', {
                            type: 'text',
                            id: 'booking-customer-name',
                            name: 'customer_name',
                            value: bookingData.customer.name,
                            required: true
                        })));
                        
                        // Add customer email field
                        form.append($('<div>', {
                            class: 'aqualuxe-booking-form__field'
                        }).append($('<label>', {
                            for: 'booking-customer-email',
                            text: 'Customer Email'
                        })).append($('<input>', {
                            type: 'email',
                            id: 'booking-customer-email',
                            name: 'customer_email',
                            value: bookingData.customer.email,
                            required: true
                        })));
                        
                        // Add customer phone field
                        form.append($('<div>', {
                            class: 'aqualuxe-booking-form__field'
                        }).append($('<label>', {
                            for: 'booking-customer-phone',
                            text: 'Customer Phone'
                        })).append($('<input>', {
                            type: 'tel',
                            id: 'booking-customer-phone',
                            name: 'customer_phone',
                            value: bookingData.customer.phone,
                            required: true
                        })));
                        
                        // Add customer notes field
                        form.append($('<div>', {
                            class: 'aqualuxe-booking-form__field'
                        }).append($('<label>', {
                            for: 'booking-customer-notes',
                            text: 'Notes'
                        })).append($('<textarea>', {
                            id: 'booking-customer-notes',
                            name: 'customer_notes',
                            rows: 3,
                            value: bookingData.customer.notes || ''
                        })));
                        
                        // Add form to modal
                        modal.append(form);
                        
                        // Add modal to body
                        $('body').append(modal);
                        
                        // Initialize modal
                        modal.dialog({
                            modal: true,
                            width: 500,
                            buttons: {
                                'Update Booking': () => {
                                    // Submit form
                                    this.submitEditBookingForm(form, modal);
                                },
                                'Cancel': () => {
                                    // Close modal
                                    modal.dialog('close');
                                }
                            },
                            close: () => {
                                // Remove modal
                                modal.remove();
                            }
                        });
                        
                        // Initialize datepicker
                        form.find('#booking-date').datepicker({
                            dateFormat: 'yy-mm-dd',
                            minDate: 0,
                            onSelect: () => {
                                // Get service ID
                                const serviceId = form.find('#booking-service').val();
                                
                                // Get date
                                const date = form.find('#booking-date').val();
                                
                                // Update time slots
                                this.updateTimeSlots(serviceId, date, form.find('#booking-time'), bookingData.time);
                            }
                        });
                        
                        // Add event listener to service select
                        form.find('#booking-service').on('change', () => {
                            // Get service ID
                            const serviceId = form.find('#booking-service').val();
                            
                            // Get date
                            const date = form.find('#booking-date').val();
                            
                            // Update time slots
                            this.updateTimeSlots(serviceId, date, form.find('#booking-time'), bookingData.time);
                        });
                        
                        // Update time slots
                        this.updateTimeSlots(bookingData.service_id, bookingData.date, form.find('#booking-time'), bookingData.time);
                    } else {
                        // Show error message
                        alert(response.data.message || 'Error loading booking data.');
                    }
                },
                error: () => {
                    // Show error message
                    alert('Error loading booking data.');
                }
            });
        }
        
        /**
         * Update time slots
         * 
         * @param {number} serviceId Service ID
         * @param {string} date Date
         * @param {jQuery} timeSelect Time select element
         * @param {string} selectedTime Selected time
         */
        updateTimeSlots(serviceId, date, timeSelect, selectedTime = '') {
            // Clear time select
            timeSelect.empty();
            
            // Add default option
            timeSelect.append($('<option>', {
                value: '',
                text: 'Select Time'
            }));
            
            // Disable time select
            timeSelect.prop('disabled', true);
            
            // Check if service and date are selected
            if (!serviceId || !date) {
                return;
            }
            
            // Send AJAX request
            $.ajax({
                url: this.options.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_check_availability',
                    nonce: this.options.nonce,
                    service_id: serviceId,
                    date: date,
                    include_selected: true,
                    selected_time: selectedTime
                },
                success: (response) => {
                    // Check if request was successful
                    if (response.success) {
                        // Get time slots
                        const timeSlots = response.data.time_slots;
                        
                        // Add time slots to select
                        if (timeSlots && timeSlots.length > 0) {
                            timeSlots.forEach((timeSlot) => {
                                timeSelect.append($('<option>', {
                                    value: timeSlot,
                                    text: this.formatTime(timeSlot),
                                    selected: timeSlot === selectedTime
                                }));
                            });
                            
                            // Enable time select
                            timeSelect.prop('disabled', false);
                        }
                    } else {
                        // Show error message
                        alert(response.data.message || 'Error loading time slots.');
                    }
                },
                error: () => {
                    // Show error message
                    alert('Error loading time slots.');
                }
            });
        }
        
        /**
         * Submit add booking form
         * 
         * @param {jQuery} form Form element
         * @param {jQuery} modal Modal element
         */
        submitAddBookingForm(form, modal) {
            // Get form data
            const formData = form.serializeArray();
            
            // Convert form data to object
            const data = {};
            formData.forEach((field) => {
                data[field.name] = field.value;
            });
            
            // Validate form data
            if (!this.validateBookingFormData(data)) {
                return;
            }
            
            // Send AJAX request
            $.ajax({
                url: this.options.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_admin_create_booking',
                    nonce: this.options.nonce,
                    service_id: data.service_id,
                    date: data.date,
                    time: data.time,
                    customer: {
                        name: data.customer_name,
                        email: data.customer_email,
                        phone: data.customer_phone,
                        notes: data.customer_notes
                    }
                },
                success: (response) => {
                    // Check if request was successful
                    if (response.success) {
                        // Close modal
                        modal.dialog('close');
                        
                        // Reload page
                        window.location.reload();
                    } else {
                        // Show error message
                        alert(response.data.message || 'Error creating booking.');
                    }
                },
                error: () => {
                    // Show error message
                    alert('Error creating booking.');
                }
            });
        }
        
        /**
         * Submit edit booking form
         * 
         * @param {jQuery} form Form element
         * @param {jQuery} modal Modal element
         */
        submitEditBookingForm(form, modal) {
            // Get form data
            const formData = form.serializeArray();
            
            // Convert form data to object
            const data = {};
            formData.forEach((field) => {
                data[field.name] = field.value;
            });
            
            // Validate form data
            if (!this.validateBookingFormData(data)) {
                return;
            }
            
            // Send AJAX request
            $.ajax({
                url: this.options.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_admin_update_booking',
                    nonce: this.options.nonce,
                    booking_id: data.booking_id,
                    service_id: data.service_id,
                    date: data.date,
                    time: data.time,
                    status: data.status,
                    customer: {
                        name: data.customer_name,
                        email: data.customer_email,
                        phone: data.customer_phone,
                        notes: data.customer_notes
                    }
                },
                success: (response) => {
                    // Check if request was successful
                    if (response.success) {
                        // Close modal
                        modal.dialog('close');
                        
                        // Reload page
                        window.location.reload();
                    } else {
                        // Show error message
                        alert(response.data.message || 'Error updating booking.');
                    }
                },
                error: () => {
                    // Show error message
                    alert('Error updating booking.');
                }
            });
        }
        
        /**
         * Delete booking
         * 
         * @param {number} bookingId Booking ID
         */
        deleteBooking(bookingId) {
            // Send AJAX request
            $.ajax({
                url: this.options.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_admin_delete_booking',
                    nonce: this.options.nonce,
                    booking_id: bookingId
                },
                success: (response) => {
                    // Check if request was successful
                    if (response.success) {
                        // Reload page
                        window.location.reload();
                    } else {
                        // Show error message
                        alert(response.data.message || 'Error deleting booking.');
                    }
                },
                error: () => {
                    // Show error message
                    alert('Error deleting booking.');
                }
            });
        }
        
        /**
         * Move booking
         * 
         * @param {number} bookingId Booking ID
         * @param {string} date Date
         */
        moveBooking(bookingId, date) {
            // Send AJAX request
            $.ajax({
                url: this.options.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_admin_move_booking',
                    nonce: this.options.nonce,
                    booking_id: bookingId,
                    date: date
                },
                success: (response) => {
                    // Check if request was successful
                    if (response.success) {
                        // Reload page
                        window.location.reload();
                    } else {
                        // Show error message
                        alert(response.data.message || 'Error moving booking.');
                    }
                },
                error: () => {
                    // Show error message
                    alert('Error moving booking.');
                }
            });
        }
        
        /**
         * Validate booking form data
         * 
         * @param {Object} data Form data
         * @return {boolean} Whether form data is valid
         */
        validateBookingFormData(data) {
            // Check if service is selected
            if (!data.service_id) {
                alert('Please select a service.');
                return false;
            }
            
            // Check if date is selected
            if (!data.date) {
                alert('Please select a date.');
                return false;
            }
            
            // Check if time is selected
            if (!data.time) {
                alert('Please select a time.');
                return false;
            }
            
            // Check if customer name is provided
            if (!data.customer_name) {
                alert('Please enter customer name.');
                return false;
            }
            
            // Check if customer email is provided
            if (!data.customer_email) {
                alert('Please enter customer email.');
                return false;
            }
            
            // Check if customer email is valid
            if (!this.validateEmail(data.customer_email)) {
                alert('Please enter a valid email address.');
                return false;
            }
            
            // Check if customer phone is provided
            if (!data.customer_phone) {
                alert('Please enter customer phone.');
                return false;
            }
            
            return true;
        }
        
        /**
         * Validate email
         * 
         * @param {string} email Email address
         * @return {boolean} Whether email is valid
         */
        validateEmail(email) {
            const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }
        
        /**
         * Format time
         * 
         * @param {string} time Time string (HH:MM)
         * @return {string} Formatted time
         */
        formatTime(time) {
            // Check if time is valid
            if (!time) {
                return '';
            }
            
            // Split time into hours and minutes
            const [hours, minutes] = time.split(':');
            
            // Convert to 12-hour format
            let hour = parseInt(hours, 10);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            hour = hour % 12;
            hour = hour ? hour : 12;
            
            // Return formatted time
            return `${hour}:${minutes} ${ampm}`;
        }
    }
    
    // Service Details Class
    class AquaLuxeServiceDetails {
        /**
         * Constructor
         * 
         * @param {HTMLElement} element Service details element
         * @param {Object} options Options
         */
        constructor(element, options) {
            // Set properties
            this.container = $(element);
            this.options = $.extend({}, {
                ajaxUrl: aqualuxeBookingsAdmin.ajaxUrl,
                nonce: aqualuxeBookingsAdmin.nonce
            }, options);
            
            // Initialize
            this.init();
        }
        
        /**
         * Initialize
         */
        init() {
            // Initialize price field
            this.initPriceField();
            
            // Initialize duration field
            this.initDurationField();
            
            // Initialize capacity field
            this.initCapacityField();
            
            // Initialize buffer time field
            this.initBufferTimeField();
        }
        
        /**
         * Initialize price field
         */
        initPriceField() {
            // Get price field
            const priceField = this.container.find('#aqualuxe-service-price');
            
            // Add currency symbol
            priceField.before($('<span>', {
                class: 'aqualuxe-service-price-symbol',
                text: '$'
            }));
        }
        
        /**
         * Initialize duration field
         */
        initDurationField() {
            // Get duration field
            const durationField = this.container.find('#aqualuxe-service-duration');
            
            // Add minutes label
            durationField.after($('<span>', {
                class: 'aqualuxe-service-duration-label',
                text: 'minutes'
            }));
        }
        
        /**
         * Initialize capacity field
         */
        initCapacityField() {
            // Get capacity field
            const capacityField = this.container.find('#aqualuxe-service-capacity');
            
            // Add spinner
            capacityField.spinner({
                min: 1,
                max: 100
            });
        }
        
        /**
         * Initialize buffer time field
         */
        initBufferTimeField() {
            // Get buffer time field
            const bufferTimeField = this.container.find('#aqualuxe-service-buffer-time');
            
            // Add minutes label
            bufferTimeField.after($('<span>', {
                class: 'aqualuxe-service-buffer-time-label',
                text: 'minutes'
            }));
        }
    }
    
    // Service Availability Class
    class AquaLuxeServiceAvailability {
        /**
         * Constructor
         * 
         * @param {HTMLElement} element Service availability element
         * @param {Object} options Options
         */
        constructor(element, options) {
            // Set properties
            this.container = $(element);
            this.options = $.extend({}, {
                ajaxUrl: aqualuxeBookingsAdmin.ajaxUrl,
                nonce: aqualuxeBookingsAdmin.nonce
            }, options);
            
            // Initialize
            this.init();
        }
        
        /**
         * Initialize
         */
        init() {
            // Initialize time pickers
            this.initTimePickers();
            
            // Initialize day toggles
            this.initDayToggles();
            
            // Initialize blocked dates
            this.initBlockedDates();
        }
        
        /**
         * Initialize time pickers
         */
        initTimePickers() {
            // Get time pickers
            const timePickers = this.container.find('.aqualuxe-service-availability__time');
            
            // Initialize time pickers
            timePickers.timepicker({
                timeFormat: 'HH:mm',
                step: 15,
                scrollDefault: '09:00'
            });
        }
        
        /**
         * Initialize day toggles
         */
        initDayToggles() {
            // Get day toggles
            const dayToggles = this.container.find('.aqualuxe-service-availability__day-toggle');
            
            // Add event listener
            dayToggles.on('change', (e) => {
                // Get toggle
                const toggle = $(e.target);
                
                // Get day
                const day = toggle.data('day');
                
                // Get time fields
                const timeFields = this.container.find(`.aqualuxe-service-availability__time[data-day="${day}"]`);
                
                // Enable/disable time fields
                timeFields.prop('disabled', !toggle.prop('checked'));
            });
        }
        
        /**
         * Initialize blocked dates
         */
        initBlockedDates() {
            // Get blocked dates field
            const blockedDatesField = this.container.find('#aqualuxe-service-blocked-dates');
            
            // Get blocked dates container
            const blockedDatesContainer = this.container.find('.aqualuxe-service-availability__blocked-dates');
            
            // Get add button
            const addButton = this.container.find('.aqualuxe-service-availability__add-blocked-date');
            
            // Initialize datepicker
            const datepicker = this.container.find('.aqualuxe-service-availability__blocked-date-picker').datepicker({
                dateFormat: 'yy-mm-dd',
                minDate: 0,
                onSelect: (date) => {
                    // Add blocked date
                    this.addBlockedDate(date);
                    
                    // Clear datepicker
                    datepicker.datepicker('setDate', null);
                }
            });
            
            // Add event listener to add button
            addButton.on('click', () => {
                // Show datepicker
                datepicker.datepicker('show');
            });
            
            // Add event listener to remove buttons
            blockedDatesContainer.on('click', '.aqualuxe-service-availability__remove-blocked-date', (e) => {
                // Get button
                const button = $(e.target);
                
                // Get date
                const date = button.data('date');
                
                // Remove blocked date
                this.removeBlockedDate(date);
            });
            
            // Initialize blocked dates
            this.initExistingBlockedDates();
        }
        
        /**
         * Initialize existing blocked dates
         */
        initExistingBlockedDates() {
            // Get blocked dates field
            const blockedDatesField = this.container.find('#aqualuxe-service-blocked-dates');
            
            // Get blocked dates
            const blockedDates = blockedDatesField.val() ? JSON.parse(blockedDatesField.val()) : [];
            
            // Add blocked dates
            blockedDates.forEach((date) => {
                this.addBlockedDate(date, false);
            });
        }
        
        /**
         * Add blocked date
         * 
         * @param {string} date Date
         * @param {boolean} updateField Whether to update field
         */
        addBlockedDate(date, updateField = true) {
            // Get blocked dates field
            const blockedDatesField = this.container.find('#aqualuxe-service-blocked-dates');
            
            // Get blocked dates container
            const blockedDatesContainer = this.container.find('.aqualuxe-service-availability__blocked-dates');
            
            // Get blocked dates
            const blockedDates = blockedDatesField.val() ? JSON.parse(blockedDatesField.val()) : [];
            
            // Check if date is already blocked
            if (blockedDates.includes(date)) {
                return;
            }
            
            // Add date to blocked dates
            blockedDates.push(date);
            
            // Sort blocked dates
            blockedDates.sort();
            
            // Update field
            if (updateField) {
                blockedDatesField.val(JSON.stringify(blockedDates));
            }
            
            // Create blocked date element
            const blockedDateElement = $('<div>', {
                class: 'aqualuxe-service-availability__blocked-date'
            }).append($('<span>', {
                class: 'aqualuxe-service-availability__blocked-date-text',
                text: this.formatDate(date)
            })).append($('<button>', {
                type: 'button',
                class: 'aqualuxe-service-availability__remove-blocked-date',
                'data-date': date,
                text: '×'
            }));
            
            // Add blocked date element to container
            blockedDatesContainer.append(blockedDateElement);
        }
        
        /**
         * Remove blocked date
         * 
         * @param {string} date Date
         */
        removeBlockedDate(date) {
            // Get blocked dates field
            const blockedDatesField = this.container.find('#aqualuxe-service-blocked-dates');
            
            // Get blocked dates container
            const blockedDatesContainer = this.container.find('.aqualuxe-service-availability__blocked-dates');
            
            // Get blocked dates
            const blockedDates = blockedDatesField.val() ? JSON.parse(blockedDatesField.val()) : [];
            
            // Remove date from blocked dates
            const index = blockedDates.indexOf(date);
            if (index !== -1) {
                blockedDates.splice(index, 1);
            }
            
            // Update field
            blockedDatesField.val(JSON.stringify(blockedDates));
            
            // Remove blocked date element
            blockedDatesContainer.find(`.aqualuxe-service-availability__remove-blocked-date[data-date="${date}"]`).closest('.aqualuxe-service-availability__blocked-date').remove();
        }
        
        /**
         * Format date
         * 
         * @param {string} date Date
         * @return {string} Formatted date
         */
        formatDate(date) {
            // Check if date is valid
            if (!date) {
                return '';
            }
            
            // Create date object
            const dateObj = new Date(date);
            
            // Get month names
            const monthNames = [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];
            
            // Get day names
            const dayNames = [
                'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
            ];
            
            // Format date
            return `${dayNames[dateObj.getDay()]}, ${monthNames[dateObj.getMonth()]} ${dateObj.getDate()}, ${dateObj.getFullYear()}`;
        }
    }
    
    // Initialize booking calendar
    $('.aqualuxe-booking-calendar-admin').each(function() {
        new AquaLuxeBookingCalendarAdmin(this);
    });
    
    // Initialize service details
    $('#aqualuxe_service_details').each(function() {
        new AquaLuxeServiceDetails(this);
    });
    
    // Initialize service availability
    $('#aqualuxe_service_availability').each(function() {
        new AquaLuxeServiceAvailability(this);
    });
    
})(jQuery);