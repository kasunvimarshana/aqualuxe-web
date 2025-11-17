/**
 * Booking Form Script
 *
 * Handles the booking form functionality.
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // Booking Form Class
    var AquaLuxeBookingForm = function(element, options) {
        this.$form = $(element);
        this.options = $.extend({}, AquaLuxeBookingForm.DEFAULTS, options);
        this.init();
    };

    // Default options
    AquaLuxeBookingForm.DEFAULTS = {
        serviceSelector: '#service_id',
        dateSelector: '#booking_date',
        timeSelector: '#booking_time',
        durationSelector: '#booking_duration',
        durationSliderSelector: '#booking_duration_slider',
        durationDisplaySelector: '#booking_duration_display',
        quantitySelector: '#booking_quantity',
        summaryDateSelector: '#summary_date',
        summaryTimeSelector: '#summary_time',
        summaryDurationSelector: '#summary_duration',
        summaryQuantitySelector: '#summary_quantity',
        summaryTotalSelector: '#summary_total',
        termsSelector: '#terms',
        submitButtonSelector: 'button[type="submit"]',
        loadingClass: 'is-loading',
        disabledClass: 'is-disabled',
        errorClass: 'has-error',
        successClass: 'has-success',
        minDuration: 60,
        maxDuration: 480,
        durationStep: 15,
        bufferTime: 30,
        basePrice: 0,
        baseDuration: 60,
        currencySymbol: '$',
        currencyPosition: 'left',
        decimalSeparator: '.',
        thousandSeparator: ',',
        decimals: 2,
        dateFormat: 'yy-mm-dd',
        timeFormat: '12h',
        ajaxUrl: '',
        nonce: '',
        i18n: {
            selectDate: 'Please select a date',
            selectTime: 'Please select a time',
            selectService: 'Please select a service',
            minimumDuration: 'Minimum booking duration is %s',
            maximumDuration: 'Maximum booking duration is %s',
            loading: 'Loading...',
            noTimesAvailable: 'No available times',
            errorLoadingTimes: 'Error loading times',
            selectTimeFirst: 'Select a date first',
            confirmCancel: 'Are you sure you want to cancel this booking?',
            errorOccurred: 'An error occurred. Please try again.'
        }
    };

    // Initialize
    AquaLuxeBookingForm.prototype.init = function() {
        // Cache elements
        this.$service = this.$form.find(this.options.serviceSelector);
        this.$date = this.$form.find(this.options.dateSelector);
        this.$time = this.$form.find(this.options.timeSelector);
        this.$duration = this.$form.find(this.options.durationSelector);
        this.$durationSlider = this.$form.find(this.options.durationSliderSelector);
        this.$durationDisplay = this.$form.find(this.options.durationDisplaySelector);
        this.$quantity = this.$form.find(this.options.quantitySelector);
        this.$summaryDate = this.$form.find(this.options.summaryDateSelector);
        this.$summaryTime = this.$form.find(this.options.summaryTimeSelector);
        this.$summaryDuration = this.$form.find(this.options.summaryDurationSelector);
        this.$summaryQuantity = this.$form.find(this.options.summaryQuantitySelector);
        this.$summaryTotal = this.$form.find(this.options.summaryTotalSelector);
        this.$terms = this.$form.find(this.options.termsSelector);
        this.$submitButton = this.$form.find(this.options.submitButtonSelector);

        // Set options from data attributes
        this.serviceId = this.$form.data('service-id') || 0;
        this.options.basePrice = parseFloat(this.$form.data('price') || this.options.basePrice);
        this.options.baseDuration = parseInt(this.$form.data('duration') || this.options.baseDuration);
        this.options.minDuration = parseInt(this.$form.data('min-duration') || this.options.minDuration);
        this.options.maxDuration = parseInt(this.$form.data('max-duration') || this.options.maxDuration);
        this.options.bufferTime = parseInt(this.$form.data('buffer-time') || this.options.bufferTime);

        // Initialize datepicker
        this.initDatepicker();

        // Initialize duration slider
        this.initDurationSlider();

        // Bind events
        this.bindEvents();

        // Load available dates
        this.loadAvailableDates();
    };

    // Initialize datepicker
    AquaLuxeBookingForm.prototype.initDatepicker = function() {
        var self = this;

        this.$date.datepicker({
            dateFormat: this.options.dateFormat,
            minDate: 0,
            maxDate: '+3m',
            beforeShowDay: function(date) {
                // This will be populated via AJAX
                return [true, ''];
            },
            onSelect: function(dateText) {
                // Load available times for selected date
                self.loadAvailableTimes(dateText);
                
                // Update summary
                self.$summaryDate.text($.datepicker.formatDate(self.options.dateFormat, new Date(dateText)));
            }
        });
    };

    // Initialize duration slider
    AquaLuxeBookingForm.prototype.initDurationSlider = function() {
        var self = this;

        if (this.$durationSlider.length) {
            this.$durationSlider.slider({
                min: this.options.minDuration,
                max: this.options.maxDuration,
                step: this.options.durationStep,
                value: this.$duration.val() || this.options.baseDuration,
                slide: function(event, ui) {
                    self.$duration.val(ui.value);
                    self.$durationDisplay.text(self.formatDuration(ui.value));
                    
                    if (self.$summaryDuration.length) {
                        self.$summaryDuration.text(self.formatDuration(ui.value));
                    }
                    
                    self.updateTotal();
                }
            });
        }
    };

    // Bind events
    AquaLuxeBookingForm.prototype.bindEvents = function() {
        var self = this;

        // Service change
        this.$service.on('change', function() {
            self.serviceId = $(this).val();
            self.loadAvailableDates();
        });

        // Time change
        this.$time.on('change', function() {
            self.$summaryTime.text($(this).val());
            self.updateTotal();
        });

        // Quantity change
        this.$quantity.on('change', function() {
            self.$summaryQuantity.text($(this).val());
            self.updateTotal();
        });

        // Form submission
        this.$form.on('submit', function(e) {
            return self.validateForm(e);
        });
    };

    // Load available dates
    AquaLuxeBookingForm.prototype.loadAvailableDates = function() {
        var self = this;

        if (!this.serviceId) {
            return;
        }

        $.ajax({
            url: this.options.ajaxUrl,
            type: 'POST',
            data: {
                action: 'get_available_dates',
                service_id: this.serviceId,
                month: new Date().getMonth() + 1,
                year: new Date().getFullYear(),
                nonce: this.options.nonce
            },
            success: function(response) {
                if (response.success) {
                    var dates = response.data.dates;
                    
                    // Update datepicker to only allow available dates
                    self.$date.datepicker('option', 'beforeShowDay', function(date) {
                        var dateString = $.datepicker.formatDate('yy-mm-dd', date);
                        return [$.inArray(dateString, dates) !== -1, ''];
                    });
                    
                    // If date is pre-selected, load available times
                    if (self.$date.val()) {
                        self.loadAvailableTimes(self.$date.val());
                    }
                }
            }
        });
    };

    // Load available times
    AquaLuxeBookingForm.prototype.loadAvailableTimes = function(date) {
        var self = this;

        if (!this.serviceId || !date) {
            return;
        }

        $.ajax({
            url: this.options.ajaxUrl,
            type: 'POST',
            data: {
                action: 'get_available_times',
                service_id: this.serviceId,
                date: date,
                nonce: this.options.nonce
            },
            beforeSend: function() {
                self.$time.prop('disabled', true).html('<option value="">' + self.options.i18n.loading + '</option>');
            },
            success: function(response) {
                if (response.success) {
                    var times = response.data.times;
                    var options = '';
                    
                    if (times.length > 0) {
                        options += '<option value="">' + self.options.i18n.selectTime + '</option>';
                        
                        for (var i = 0; i < times.length; i++) {
                            options += '<option value="' + times[i].time + '">' + times[i].time + '</option>';
                        }
                        
                        self.$time.prop('disabled', false).html(options);
                    } else {
                        options = '<option value="">' + self.options.i18n.noTimesAvailable + '</option>';
                        self.$time.prop('disabled', true).html(options);
                    }
                } else {
                    self.$time.prop('disabled', true).html('<option value="">' + self.options.i18n.errorLoadingTimes + '</option>');
                }
            },
            error: function() {
                self.$time.prop('disabled', true).html('<option value="">' + self.options.i18n.errorLoadingTimes + '</option>');
            }
        });
    };

    // Update total price
    AquaLuxeBookingForm.prototype.updateTotal = function() {
        var quantity = this.$quantity.val() || 1;
        var duration = this.$duration.val() || this.options.baseDuration;
        var basePrice = this.options.basePrice;
        var total = basePrice * quantity;
        
        // Adjust price based on duration
        if (this.$duration.length) {
            var durationFactor = duration / this.options.baseDuration;
            total = total * durationFactor;
        }
        
        // Format total
        var formattedTotal = this.formatPrice(total);
        
        // Update summary
        this.$summaryTotal.text(formattedTotal);
    };

    // Format duration
    AquaLuxeBookingForm.prototype.formatDuration = function(minutes) {
        if (minutes < 60) {
            return minutes + ' ' + (minutes === 1 ? 'minute' : 'minutes');
        } else {
            var hours = Math.floor(minutes / 60);
            var mins = minutes % 60;
            
            if (mins === 0) {
                return hours + ' ' + (hours === 1 ? 'hour' : 'hours');
            } else {
                return hours + ' ' + (hours === 1 ? 'hour' : 'hours') + ' ' + mins + ' ' + (mins === 1 ? 'minute' : 'minutes');
            }
        }
    };

    // Format price
    AquaLuxeBookingForm.prototype.formatPrice = function(price) {
        // Format number
        var formattedNumber = price.toFixed(this.options.decimals);
        var parts = formattedNumber.split('.');
        var integerPart = parts[0];
        var decimalPart = parts.length > 1 ? parts[1] : '';
        
        // Add thousand separators
        integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, this.options.thousandSeparator);
        
        // Combine parts
        var formattedPrice = integerPart;
        
        if (decimalPart) {
            formattedPrice += this.options.decimalSeparator + decimalPart;
        }
        
        // Add currency symbol
        if (this.options.currencyPosition === 'left') {
            formattedPrice = this.options.currencySymbol + formattedPrice;
        } else if (this.options.currencyPosition === 'right') {
            formattedPrice = formattedPrice + this.options.currencySymbol;
        } else if (this.options.currencyPosition === 'left_space') {
            formattedPrice = this.options.currencySymbol + ' ' + formattedPrice;
        } else if (this.options.currencyPosition === 'right_space') {
            formattedPrice = formattedPrice + ' ' + this.options.currencySymbol;
        }
        
        return formattedPrice;
    };

    // Validate form
    AquaLuxeBookingForm.prototype.validateForm = function(e) {
        var self = this;
        var isValid = true;
        
        // Reset errors
        this.$form.find('.' + this.options.errorClass).removeClass(this.options.errorClass);
        
        // Validate service
        if (this.$service.length && !this.$service.val()) {
            this.$service.addClass(this.options.errorClass);
            alert(this.options.i18n.selectService);
            isValid = false;
        }
        
        // Validate date
        if (!this.$date.val()) {
            this.$date.addClass(this.options.errorClass);
            alert(this.options.i18n.selectDate);
            isValid = false;
        }
        
        // Validate time
        if (!this.$time.val()) {
            this.$time.addClass(this.options.errorClass);
            alert(this.options.i18n.selectTime);
            isValid = false;
        }
        
        // Validate terms
        if (this.$terms.length && !this.$terms.is(':checked')) {
            this.$terms.addClass(this.options.errorClass);
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
        
        // Validate booking
        $.ajax({
            url: this.options.ajaxUrl,
            type: 'POST',
            data: {
                action: 'validate_booking_form',
                service_id: this.serviceId,
                date: this.$date.val(),
                time: this.$time.val(),
                duration: this.$duration.val() || this.options.baseDuration,
                nonce: this.options.nonce
            },
            async: false,
            success: function(response) {
                if (!response.success) {
                    alert(response.data.message);
                    isValid = false;
                }
            },
            error: function() {
                alert(self.options.i18n.errorOccurred);
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
        
        // Disable submit button
        this.$submitButton.prop('disabled', true).addClass(this.options.loadingClass);
        
        return true;
    };

    // jQuery plugin
    $.fn.aqualuxeBookingForm = function(options) {
        return this.each(function() {
            if (!$.data(this, 'aqualuxe.bookingForm')) {
                $.data(this, 'aqualuxe.bookingForm', new AquaLuxeBookingForm(this, options));
            }
        });
    };

    // Auto-initialize
    $(document).ready(function() {
        $('.aqualuxe-bookings-form').aqualuxeBookingForm({
            ajaxUrl: aqualuxe_bookings_params.ajax_url,
            nonce: aqualuxe_bookings_params.nonce,
            dateFormat: aqualuxe_bookings_params.i18n.date_format,
            timeFormat: aqualuxe_bookings_params.i18n.time_format,
            currencySymbol: aqualuxe_bookings_params.i18n.currency_symbol,
            currencyPosition: aqualuxe_bookings_params.i18n.currency_position,
            decimalSeparator: aqualuxe_bookings_params.i18n.decimal_separator,
            thousandSeparator: aqualuxe_bookings_params.i18n.thousand_separator,
            decimals: parseInt(aqualuxe_bookings_params.i18n.decimals),
            minDuration: parseInt(aqualuxe_bookings_params.settings.min_booking_time),
            maxDuration: parseInt(aqualuxe_bookings_params.settings.max_booking_time),
            bufferTime: parseInt(aqualuxe_bookings_params.settings.buffer_time),
            i18n: aqualuxe_bookings_params.i18n
        });
    });

})(jQuery);