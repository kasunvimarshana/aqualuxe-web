/**
 * Bookings Module
 * 
 * Handles booking form functionality and calendar integration.
 */

(function($) {
    'use strict';

    // Booking Form Class
    class AquaLuxeBookingForm {
        /**
         * Constructor
         * 
         * @param {HTMLElement} element Form element
         * @param {Object} options Form options
         */
        constructor(element, options) {
            // Set properties
            this.form = $(element);
            this.options = $.extend({}, {
                style: 'standard',
                ajaxUrl: aqualuxeBookings.ajaxUrl,
                nonce: aqualuxeBookings.nonce,
                i18n: aqualuxeBookings.i18n
            }, options);
            
            // Set elements
            this.serviceSelect = this.form.find('.aqualuxe-booking-form__service-select');
            this.dateInput = this.form.find('.aqualuxe-booking-form__date-input');
            this.timeSelect = this.form.find('.aqualuxe-booking-form__time-select');
            this.customerFields = this.form.find('.aqualuxe-booking-form__customer-fields');
            this.submitButton = this.form.find('.aqualuxe-booking-form__submit');
            this.messageContainer = this.form.find('.aqualuxe-booking-form__message');
            
            // Initialize form
            this.init();
        }
        
        /**
         * Initialize form
         */
        init() {
            // Initialize datepicker
            this.initDatepicker();
            
            // Add event listeners
            this.addEventListeners();
            
            // Set initial state
            this.updateFormState();
        }
        
        /**
         * Initialize datepicker
         */
        initDatepicker() {
            // Get current date
            const currentDate = new Date();
            
            // Initialize datepicker
            this.dateInput.datepicker({
                dateFormat: 'yy-mm-dd',
                minDate: currentDate,
                maxDate: '+1y',
                beforeShowDay: (date) => {
                    // Get day of week
                    const dayOfWeek = date.getDay();
                    
                    // Get selected service
                    const serviceId = this.serviceSelect.val();
                    
                    // Check if service is selected
                    if (!serviceId) {
                        return [false, '', this.options.i18n.selectService];
                    }
                    
                    // Get service data
                    const serviceData = this.getServiceData(serviceId);
                    
                    // Check if service has availability data
                    if (!serviceData || !serviceData.availability) {
                        return [true, '', ''];
                    }
                    
                    // Check if date is blocked
                    const dateString = $.datepicker.formatDate('yy-mm-dd', date);
                    if (serviceData.availability.blockedDates && serviceData.availability.blockedDates.includes(dateString)) {
                        return [false, 'aqualuxe-booking-form__date--blocked', ''];
                    }
                    
                    // Check if day is enabled
                    const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                    const day = days[dayOfWeek];
                    
                    if (serviceData.availability.rules && serviceData.availability.rules[day]) {
                        return [serviceData.availability.rules[day].enabled, '', ''];
                    }
                    
                    return [true, '', ''];
                },
                onSelect: () => {
                    // Update time slots
                    this.updateTimeSlots();
                }
            });
        }
        
        /**
         * Add event listeners
         */
        addEventListeners() {
            // Service select change
            this.serviceSelect.on('change', () => {
                // Reset date and time
                this.dateInput.val('');
                this.timeSelect.empty();
                this.timeSelect.prop('disabled', true);
                
                // Update form state
                this.updateFormState();
            });
            
            // Time select change
            this.timeSelect.on('change', () => {
                // Update form state
                this.updateFormState();
            });
            
            // Form submit
            this.form.on('submit', (e) => {
                // Prevent default form submission
                e.preventDefault();
                
                // Submit form
                this.submitForm();
            });
        }
        
        /**
         * Update form state
         */
        updateFormState() {
            // Check if service is selected
            const serviceSelected = this.serviceSelect.val() !== '';
            
            // Check if date is selected
            const dateSelected = this.dateInput.val() !== '';
            
            // Check if time is selected
            const timeSelected = this.timeSelect.val() !== '';
            
            // Enable/disable date input
            this.dateInput.prop('disabled', !serviceSelected);
            
            // Enable/disable time select
            this.timeSelect.prop('disabled', !serviceSelected || !dateSelected);
            
            // Show/hide customer fields
            if (serviceSelected && dateSelected && timeSelected) {
                this.customerFields.slideDown();
                this.submitButton.prop('disabled', false);
            } else {
                this.customerFields.slideUp();
                this.submitButton.prop('disabled', true);
            }
        }
        
        /**
         * Update time slots
         */
        updateTimeSlots() {
            // Get service ID
            const serviceId = this.serviceSelect.val();
            
            // Get date
            const date = this.dateInput.val();
            
            // Check if service and date are selected
            if (!serviceId || !date) {
                return;
            }
            
            // Disable time select
            this.timeSelect.prop('disabled', true);
            this.timeSelect.empty();
            this.timeSelect.append($('<option>', {
                value: '',
                text: this.options.i18n.selectTime
            }));
            
            // Show loading message
            this.showMessage(this.options.i18n.loadingTimeSlots, 'info');
            
            // Send AJAX request
            $.ajax({
                url: this.options.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_check_availability',
                    nonce: this.options.nonce,
                    service_id: serviceId,
                    date: date
                },
                success: (response) => {
                    // Clear message
                    this.clearMessage();
                    
                    // Check if request was successful
                    if (response.success) {
                        // Get time slots
                        const timeSlots = response.data.time_slots;
                        
                        // Add time slots to select
                        if (timeSlots && timeSlots.length > 0) {
                            timeSlots.forEach((timeSlot) => {
                                this.timeSelect.append($('<option>', {
                                    value: timeSlot,
                                    text: this.formatTime(timeSlot)
                                }));
                            });
                            
                            // Enable time select
                            this.timeSelect.prop('disabled', false);
                        } else {
                            // Show no availability message
                            this.showMessage(this.options.i18n.noAvailability, 'warning');
                        }
                    } else {
                        // Show error message
                        this.showMessage(response.data.message || this.options.i18n.errorLoadingTimeSlots, 'error');
                    }
                    
                    // Update form state
                    this.updateFormState();
                },
                error: () => {
                    // Show error message
                    this.showMessage(this.options.i18n.errorLoadingTimeSlots, 'error');
                    
                    // Update form state
                    this.updateFormState();
                }
            });
        }
        
        /**
         * Submit form
         */
        submitForm() {
            // Get form data
            const formData = {
                service_id: this.serviceSelect.val(),
                date: this.dateInput.val(),
                time: this.timeSelect.val(),
                customer: {
                    name: this.form.find('[name="customer_name"]').val(),
                    email: this.form.find('[name="customer_email"]').val(),
                    phone: this.form.find('[name="customer_phone"]').val(),
                    notes: this.form.find('[name="customer_notes"]').val()
                }
            };
            
            // Validate form data
            if (!this.validateFormData(formData)) {
                return;
            }
            
            // Disable submit button
            this.submitButton.prop('disabled', true);
            
            // Show loading message
            this.showMessage(this.options.i18n.processingBooking, 'info');
            
            // Send AJAX request
            $.ajax({
                url: this.options.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_create_booking',
                    nonce: this.options.nonce,
                    service_id: formData.service_id,
                    date: formData.date,
                    time: formData.time,
                    customer: formData.customer
                },
                success: (response) => {
                    // Check if request was successful
                    if (response.success) {
                        // Show success message
                        this.showMessage(this.options.i18n.bookingSuccess, 'success');
                        
                        // Reset form
                        this.resetForm();
                        
                        // Redirect to confirmation page if provided
                        if (response.data.redirect_url) {
                            window.location.href = response.data.redirect_url;
                        }
                    } else {
                        // Show error message
                        this.showMessage(response.data.message || this.options.i18n.bookingError, 'error');
                        
                        // Enable submit button
                        this.submitButton.prop('disabled', false);
                    }
                },
                error: () => {
                    // Show error message
                    this.showMessage(this.options.i18n.bookingError, 'error');
                    
                    // Enable submit button
                    this.submitButton.prop('disabled', false);
                }
            });
        }
        
        /**
         * Validate form data
         * 
         * @param {Object} formData Form data
         * @return {boolean} Whether form data is valid
         */
        validateFormData(formData) {
            // Check if service is selected
            if (!formData.service_id) {
                this.showMessage(this.options.i18n.selectService, 'error');
                return false;
            }
            
            // Check if date is selected
            if (!formData.date) {
                this.showMessage(this.options.i18n.selectDate, 'error');
                return false;
            }
            
            // Check if time is selected
            if (!formData.time) {
                this.showMessage(this.options.i18n.selectTime, 'error');
                return false;
            }
            
            // Check if customer name is provided
            if (!formData.customer.name) {
                this.showMessage(this.options.i18n.enterName, 'error');
                return false;
            }
            
            // Check if customer email is provided
            if (!formData.customer.email) {
                this.showMessage(this.options.i18n.enterEmail, 'error');
                return false;
            }
            
            // Check if customer email is valid
            if (!this.validateEmail(formData.customer.email)) {
                this.showMessage(this.options.i18n.invalidEmail, 'error');
                return false;
            }
            
            // Check if customer phone is provided
            if (!formData.customer.phone) {
                this.showMessage(this.options.i18n.enterPhone, 'error');
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
         * Reset form
         */
        resetForm() {
            // Reset form fields
            this.form[0].reset();
            
            // Reset time select
            this.timeSelect.empty();
            this.timeSelect.prop('disabled', true);
            
            // Update form state
            this.updateFormState();
        }
        
        /**
         * Show message
         * 
         * @param {string} message Message text
         * @param {string} type Message type (info, success, warning, error)
         */
        showMessage(message, type) {
            // Clear existing message
            this.clearMessage();
            
            // Create message element
            const messageElement = $('<div>', {
                class: `aqualuxe-booking-form__message aqualuxe-booking-form__message--${type}`,
                text: message
            });
            
            // Add message to container
            this.messageContainer.append(messageElement);
            
            // Show message container
            this.messageContainer.slideDown();
        }
        
        /**
         * Clear message
         */
        clearMessage() {
            // Clear message container
            this.messageContainer.empty();
            
            // Hide message container
            this.messageContainer.slideUp();
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
        
        /**
         * Get service data
         * 
         * @param {number} serviceId Service ID
         * @return {Object|null} Service data
         */
        getServiceData(serviceId) {
            // Check if service data is available
            if (!window.aqualuxeServices || !window.aqualuxeServices[serviceId]) {
                return null;
            }
            
            // Return service data
            return window.aqualuxeServices[serviceId];
        }
    }
    
    // Booking Calendar Class
    class AquaLuxeBookingCalendar {
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
                style: 'standard',
                ajaxUrl: aqualuxeBookings.ajaxUrl,
                nonce: aqualuxeBookings.nonce,
                i18n: aqualuxeBookings.i18n
            }, options);
            
            // Set elements
            this.serviceSelect = this.calendar.find('.aqualuxe-booking-calendar__service-select');
            this.monthSelect = this.calendar.find('.aqualuxe-booking-calendar__month-select');
            this.yearSelect = this.calendar.find('.aqualuxe-booking-calendar__year-select');
            this.calendarContainer = this.calendar.find('.aqualuxe-booking-calendar__container');
            this.messageContainer = this.calendar.find('.aqualuxe-booking-calendar__message');
            
            // Initialize calendar
            this.init();
        }
        
        /**
         * Initialize calendar
         */
        init() {
            // Add event listeners
            this.addEventListeners();
            
            // Load calendar
            this.loadCalendar();
        }
        
        /**
         * Add event listeners
         */
        addEventListeners() {
            // Service select change
            this.serviceSelect.on('change', () => {
                // Load calendar
                this.loadCalendar();
            });
            
            // Month select change
            this.monthSelect.on('change', () => {
                // Load calendar
                this.loadCalendar();
            });
            
            // Year select change
            this.yearSelect.on('change', () => {
                // Load calendar
                this.loadCalendar();
            });
        }
        
        /**
         * Load calendar
         */
        loadCalendar() {
            // Get service ID
            const serviceId = this.serviceSelect.val();
            
            // Get month
            const month = this.monthSelect.val();
            
            // Get year
            const year = this.yearSelect.val();
            
            // Check if service, month, and year are selected
            if (!serviceId || !month || !year) {
                return;
            }
            
            // Show loading message
            this.showMessage(this.options.i18n.loadingCalendar, 'info');
            
            // Send AJAX request
            $.ajax({
                url: this.options.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_calendar',
                    nonce: this.options.nonce,
                    service_id: serviceId,
                    month: month,
                    year: year
                },
                success: (response) => {
                    // Clear message
                    this.clearMessage();
                    
                    // Check if request was successful
                    if (response.success) {
                        // Update calendar container
                        this.calendarContainer.html(response.data.calendar);
                        
                        // Add event listeners to calendar cells
                        this.addCalendarCellListeners();
                    } else {
                        // Show error message
                        this.showMessage(response.data.message || this.options.i18n.errorLoadingCalendar, 'error');
                    }
                },
                error: () => {
                    // Show error message
                    this.showMessage(this.options.i18n.errorLoadingCalendar, 'error');
                }
            });
        }
        
        /**
         * Add calendar cell listeners
         */
        addCalendarCellListeners() {
            // Get calendar cells
            const cells = this.calendarContainer.find('.aqualuxe-booking-calendar__cell--available');
            
            // Add click event listener
            cells.on('click', (e) => {
                // Get cell
                const cell = $(e.currentTarget);
                
                // Get date
                const date = cell.data('date');
                
                // Check if date is valid
                if (!date) {
                    return;
                }
                
                // Get service ID
                const serviceId = this.serviceSelect.val();
                
                // Redirect to booking page
                if (window.aqualuxeBookings.bookingPageUrl) {
                    window.location.href = window.aqualuxeBookings.bookingPageUrl + '?service_id=' + serviceId + '&date=' + date;
                }
            });
        }
        
        /**
         * Show message
         * 
         * @param {string} message Message text
         * @param {string} type Message type (info, success, warning, error)
         */
        showMessage(message, type) {
            // Clear existing message
            this.clearMessage();
            
            // Create message element
            const messageElement = $('<div>', {
                class: `aqualuxe-booking-calendar__message aqualuxe-booking-calendar__message--${type}`,
                text: message
            });
            
            // Add message to container
            this.messageContainer.append(messageElement);
            
            // Show message container
            this.messageContainer.slideDown();
        }
        
        /**
         * Clear message
         */
        clearMessage() {
            // Clear message container
            this.messageContainer.empty();
            
            // Hide message container
            this.messageContainer.slideUp();
        }
    }
    
    // Initialize booking forms
    $('.aqualuxe-booking-form').each(function() {
        new AquaLuxeBookingForm(this, {
            style: $(this).data('style') || 'standard'
        });
    });
    
    // Initialize booking calendars
    $('.aqualuxe-booking-calendar').each(function() {
        new AquaLuxeBookingCalendar(this, {
            style: $(this).data('style') || 'standard'
        });
    });
    
})(jQuery);