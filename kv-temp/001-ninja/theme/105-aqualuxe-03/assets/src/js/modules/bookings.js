/**
 * Bookings Module JavaScript
 *
 * Handles booking form functionality and appointment scheduling
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function ($) {
  'use strict';

  const AquaLuxeBookings = {
    /**
     * Initialize bookings functionality
     */
    init() {
      this.bindEvents();
      this.initDatePicker();
      this.loadInitialSlots();
    },

    /**
     * Bind event handlers
     */
    bindEvents() {
      // Service selection change
      $(document).on('change', '#booking-service', e => {
        this.onServiceChange(e.target.value);
      });

      // Date selection change
      $(document).on('change', '#booking-date', e => {
        this.onDateChange(e.target.value);
      });

      // Time slot selection
      $(document).on('click', '.time-slot', e => {
        e.preventDefault();
        this.selectTimeSlot($(e.currentTarget));
      });

      // Form submission
      $(document).on('submit', '.booking-form', e => {
        e.preventDefault();
        this.submitBooking($(e.currentTarget));
      });

      // Booking cancellation
      $(document).on('click', '.cancel-booking', e => {
        e.preventDefault();
        this.cancelBooking($(e.currentTarget));
      });

      // Form validation
      $(document).on(
        'blur',
        '.booking-form input, .booking-form textarea',
        e => {
          this.validateField($(e.currentTarget));
        }
      );
    },

    /**
     * Initialize date picker
     */
    initDatePicker() {
      const $datePicker = $('#booking-date');

      if ($datePicker.length && $.fn.datepicker) {
        const today = new Date();
        const maxDate = new Date();
        maxDate.setDate(today.getDate() + 30); // 30 days in advance

        $datePicker.datepicker({
          dateFormat: 'yy-mm-dd',
          minDate: 1, // Tomorrow
          maxDate: maxDate,
          beforeShowDay: date => {
            // Disable Sundays (day 0) by default
            const day = date.getDay();
            return [day !== 0, day !== 0 ? '' : 'ui-datepicker-unselectable'];
          },
          onSelect: dateText => {
            this.onDateChange(dateText);
          },
        });
      }
    },

    /**
     * Handle service change
     */
    onServiceChange(serviceId) {
      if (!serviceId) {
        this.clearTimeSlots();
        return;
      }

      // Clear date and time selections
      $('#booking-date').val('');
      this.clearTimeSlots();

      // Update form for selected service
      this.loadServiceDetails(serviceId);
    },

    /**
     * Handle date change
     */
    onDateChange(date) {
      if (!date) {
        this.clearTimeSlots();
        return;
      }

      const serviceId = $('#booking-service').val();
      if (!serviceId) {
        this.showMessage('Please select a service first', 'error');
        return;
      }

      this.loadAvailableSlots(serviceId, date);
    },

    /**
     * Load service details
     */
    loadServiceDetails() {
      // This would typically load service-specific information
      // like duration, price, description, etc.
      // Service details loading functionality
    },

    /**
     * Load available time slots
     */
    loadAvailableSlots(serviceId, date) {
      const $slotsContainer = $('#time-slots');

      $slotsContainer.html(
        '<div class="loading">Loading available times...</div>'
      );

      $.ajax({
        url: aqualuxe_bookings.ajax_url,
        type: 'POST',
        data: {
          action: 'aqualuxe_get_available_slots',
          nonce: aqualuxe_bookings.nonce,
          service_id: serviceId,
          date: date,
        },
        success: response => {
          if (response.success) {
            this.renderTimeSlots(response.data.slots);
          } else {
            this.showMessage(
              response.data || 'Error loading time slots',
              'error'
            );
            this.clearTimeSlots();
          }
        },
        error: () => {
          this.showMessage('Failed to load available times', 'error');
          this.clearTimeSlots();
        },
      });
    },

    /**
     * Render time slots
     */
    renderTimeSlots(slots) {
      const $slotsContainer = $('#time-slots');

      if (!slots || slots.length === 0) {
        $slotsContainer.html(
          '<p class="no-slots">No available time slots for this date.</p>'
        );
        return;
      }

      let slotsHtml = '<div class="time-slots-grid">';
      slots.forEach(slot => {
        slotsHtml += `
          <button type="button" class="time-slot" data-time="${slot.datetime}">
            ${slot.formatted}
          </button>
        `;
      });
      slotsHtml += '</div>';

      $slotsContainer.html(slotsHtml);
    },

    /**
     * Clear time slots
     */
    clearTimeSlots() {
      $('#time-slots').empty();
      $('#booking-time').val('');
    },

    /**
     * Select time slot
     */
    selectTimeSlot($slot) {
      // Remove previous selection
      $('.time-slot').removeClass('selected');

      // Select new slot
      $slot.addClass('selected');

      // Update hidden field
      $('#booking-time').val($slot.data('time'));

      // Show booking form if hidden
      $('.booking-details').slideDown();
    },

    /**
     * Load initial slots if date and service are pre-selected
     */
    loadInitialSlots() {
      const serviceId = $('#booking-service').val();
      const date = $('#booking-date').val();

      if (serviceId && date) {
        this.loadAvailableSlots(serviceId, date);
      }
    },

    /**
     * Submit booking
     */
    submitBooking($form) {
      // Validate form
      if (!this.validateForm($form)) {
        return;
      }

      const $submitBtn = $form.find('[type="submit"]');
      const originalText = $submitBtn.text();

      // Show loading state
      $submitBtn.prop('disabled', true).text(aqualuxe_bookings.strings.loading);
      $form.addClass('loading');

      const formData = this.getFormData($form);

      $.ajax({
        url: aqualuxe_bookings.ajax_url,
        type: 'POST',
        data: {
          action: 'aqualuxe_create_booking',
          nonce: aqualuxe_bookings.nonce,
          ...formData,
        },
        success: response => {
          if (response.success) {
            this.showMessage(response.data.message, 'success');

            // Redirect to confirmation page if specified
            if (response.data.redirect) {
              setTimeout(() => {
                window.location.href = response.data.redirect;
              }, 1500);
            } else {
              // Reset form
              this.resetForm($form);
            }
          } else {
            this.showMessage(
              response.data || aqualuxe_bookings.strings.booking_error,
              'error'
            );
          }
        },
        error: () => {
          this.showMessage(aqualuxe_bookings.strings.booking_error, 'error');
        },
        complete: () => {
          $submitBtn.prop('disabled', false).text(originalText);
          $form.removeClass('loading');
        },
      });
    },

    /**
     * Cancel booking
     */
    cancelBooking($button) {
      if (!confirm(aqualuxe_bookings.strings.confirm_cancel)) {
        return;
      }

      const bookingId = $button.data('booking-id');
      const originalText = $button.text();

      $button.prop('disabled', true).text(aqualuxe_bookings.strings.loading);

      $.ajax({
        url: aqualuxe_bookings.ajax_url,
        type: 'POST',
        data: {
          action: 'aqualuxe_cancel_booking',
          nonce: aqualuxe_bookings.nonce,
          booking_id: bookingId,
        },
        success: response => {
          if (response.success) {
            this.showMessage(response.data.message, 'success');

            // Remove booking from list or update status
            const $bookingItem = $button.closest('.booking-item');
            $bookingItem
              .addClass('cancelled')
              .find('.booking-status')
              .text('Cancelled');
            $button.hide();
          } else {
            this.showMessage(
              response.data || 'Error cancelling booking',
              'error'
            );
          }
        },
        error: () => {
          this.showMessage('Failed to cancel booking', 'error');
        },
        complete: () => {
          $button.prop('disabled', false).text(originalText);
        },
      });
    },

    /**
     * Validate entire form
     */
    validateForm($form) {
      let isValid = true;
      const requiredFields = $form.find('[required]');

      requiredFields.each((index, field) => {
        if (!this.validateField($(field))) {
          isValid = false;
        }
      });

      // Custom validation
      const serviceId = $('#booking-service').val();
      const date = $('#booking-date').val();
      const time = $('#booking-time').val();

      if (!serviceId) {
        this.showFieldError(
          '#booking-service',
          aqualuxe_bookings.strings.select_service || 'Please select a service'
        );
        isValid = false;
      }

      if (!date) {
        this.showFieldError(
          '#booking-date',
          aqualuxe_bookings.strings.select_date
        );
        isValid = false;
      }

      if (!time) {
        this.showFieldError(
          '#time-slots',
          aqualuxe_bookings.strings.select_time
        );
        isValid = false;
      }

      return isValid;
    },

    /**
     * Validate individual field
     */
    validateField($field) {
      const value = $field.val().trim();
      const fieldType = $field.attr('type');
      const isRequired = $field.attr('required');
      let isValid = true;
      let errorMessage = '';

      // Clear previous errors
      this.clearFieldError($field);

      // Required field validation
      if (isRequired && !value) {
        errorMessage = `${this.getFieldLabel($field)} is required`;
        isValid = false;
      }
      // Email validation
      else if (fieldType === 'email' && value && !this.isValidEmail(value)) {
        errorMessage = 'Please enter a valid email address';
        isValid = false;
      }
      // Phone validation
      else if (fieldType === 'tel' && value && !this.isValidPhone(value)) {
        errorMessage = 'Please enter a valid phone number';
        isValid = false;
      }

      if (!isValid) {
        this.showFieldError($field, errorMessage);
      }

      return isValid;
    },

    /**
     * Get form data
     */
    getFormData($form) {
      const data = {};

      $form.find('input, select, textarea').each((index, field) => {
        const $field = $(field);
        const name = $field.attr('name');
        const value = $field.val();

        if (name && value) {
          data[name] = value;
        }
      });

      return data;
    },

    /**
     * Reset form
     */
    resetForm($form) {
      $form[0].reset();
      this.clearTimeSlots();
      $('.booking-details').hide();
      this.clearAllErrors();
    },

    /**
     * Show field error
     */
    showFieldError($field, message) {
      const fieldId = $field.attr('id') || $field.attr('name');
      const $errorElement = $(
        `<div class="field-error" data-field="${fieldId}">${message}</div>`
      );

      $field.addClass('error');

      // Remove existing error for this field
      this.clearFieldError($field);

      // Add new error
      if ($field.is('select')) {
        $field.after($errorElement);
      } else {
        $field.parent().append($errorElement);
      }
    },

    /**
     * Clear field error
     */
    clearFieldError($field) {
      const fieldId = $field.attr('id') || $field.attr('name');
      $field.removeClass('error');
      $(`.field-error[data-field="${fieldId}"]`).remove();
    },

    /**
     * Clear all errors
     */
    clearAllErrors() {
      $('.field-error').remove();
      $('.error').removeClass('error');
    },

    /**
     * Get field label
     */
    getFieldLabel($field) {
      const fieldId = $field.attr('id');
      const $label = $(`label[for="${fieldId}"]`);

      if ($label.length) {
        return $label.text().replace('*', '').trim();
      }

      return $field.attr('placeholder') || $field.attr('name') || 'Field';
    },

    /**
     * Validate email format
     */
    isValidEmail(email) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
    },

    /**
     * Validate phone format
     */
    isValidPhone(phone) {
      const phoneRegex = /^[+]?[1-9][\d]{0,15}$/;
      return phoneRegex.test(phone.replace(/[\s\-()]/g, ''));
    },

    /**
     * Show message to user
     */
    showMessage(message, type = 'info') {
      // Remove existing messages
      $('.booking-message').remove();

      const $message = $(`
        <div class="booking-message message-${type}" role="alert">
          <p>${message}</p>
          <button type="button" class="message-close" aria-label="Close message">&times;</button>
        </div>
      `);

      // Add to top of form or page
      const $container = $('.booking-form').length
        ? $('.booking-form')
        : $('body');
      $container.prepend($message);

      // Auto-hide success messages
      if (type === 'success') {
        setTimeout(() => {
          $message.fadeOut();
        }, 5000);
      }

      // Handle close button
      $message.find('.message-close').on('click', () => {
        $message.fadeOut();
      });

      // Scroll to message
      $('html, body').animate(
        {
          scrollTop: $message.offset().top - 20,
        },
        300
      );
    },

    /**
     * Format date for display
     */
    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
      });
    },

    /**
     * Format time for display
     */
    formatTime(timeString) {
      const [hours, minutes] = timeString.split(':');
      const date = new Date();
      date.setHours(parseInt(hours), parseInt(minutes));

      return date.toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true,
      });
    },
  };

  // Initialize when DOM is ready
  $(document).ready(() => {
    if ($('.booking-form, .my-bookings').length) {
      AquaLuxeBookings.init();
    }
  });

  // Make available globally
  window.AquaLuxeBookings = AquaLuxeBookings;
})(jQuery);
