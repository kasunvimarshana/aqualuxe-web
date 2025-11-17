/**
 * Events Module JavaScript
 *
 * Handles event calendar, booking, and ticket management
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function ($) {
  'use strict';

  const AquaLuxeEvents = {
    /**
     * Initialize events functionality
     */
    init() {
      this.bindEvents();
      this.initCalendar();
      this.initTicketCalculator();
    },

    /**
     * Bind event handlers
     */
    bindEvents() {
      // Event booking form submission
      $(document).on('submit', '.event-booking-form', e => {
        e.preventDefault();
        this.submitEventBooking($(e.currentTarget));
      });

      // Ticket quantity change
      $(document).on('change', '.ticket-quantity', () => {
        this.updateTicketTotal();
      });

      // Ticket type change
      $(document).on('change', '.ticket-type', () => {
        this.updateTicketTotal();
      });

      // Calendar navigation
      $(document).on('click', '.calendar-nav', e => {
        e.preventDefault();
        this.navigateCalendar($(e.currentTarget));
      });

      // Event details toggle
      $(document).on('click', '.event-details-toggle', e => {
        e.preventDefault();
        this.toggleEventDetails($(e.currentTarget));
      });

      // Cancel event booking
      $(document).on('click', '.cancel-event-booking', e => {
        e.preventDefault();
        this.cancelEventBooking($(e.currentTarget));
      });

      // Add to calendar
      $(document).on('click', '.add-to-calendar', e => {
        e.preventDefault();
        this.addToCalendar($(e.currentTarget));
      });

      // Form validation
      $(document).on(
        'blur',
        '.event-booking-form input, .event-booking-form textarea',
        e => {
          this.validateField($(e.currentTarget));
        }
      );
    },

    /**
     * Initialize calendar
     */
    initCalendar() {
      const $calendar = $('.events-calendar');

      if ($calendar.length) {
        this.renderCalendar();
      }
    },

    /**
     * Initialize ticket calculator
     */
    initTicketCalculator() {
      this.updateTicketTotal();
    },

    /**
     * Submit event booking
     */
    submitEventBooking($form) {
      // Validate form
      if (!this.validateEventForm($form)) {
        return;
      }

      const $submitBtn = $form.find('[type="submit"]');
      const originalText = $submitBtn.text();

      // Show loading state
      $submitBtn.prop('disabled', true).text(aqualuxe_events.strings.loading);
      $form.addClass('loading');

      const formData = this.getEventFormData($form);

      $.ajax({
        url: aqualuxe_events.ajax_url,
        type: 'POST',
        data: {
          action: 'aqualuxe_book_event',
          nonce: aqualuxe_events.nonce,
          ...formData,
        },
        success: response => {
          if (response.success) {
            this.showEventMessage(response.data.message, 'success');

            // Redirect to confirmation page if specified
            if (response.data.redirect) {
              setTimeout(() => {
                window.location.href = response.data.redirect;
              }, 1500);
            } else {
              // Reset form
              this.resetEventForm($form);
            }
          } else {
            this.showEventMessage(
              response.data || aqualuxe_events.strings.booking_error,
              'error'
            );
          }
        },
        error: () => {
          this.showEventMessage(aqualuxe_events.strings.booking_error, 'error');
        },
        complete: () => {
          $submitBtn.prop('disabled', false).text(originalText);
          $form.removeClass('loading');
        },
      });
    },

    /**
     * Update ticket total
     */
    updateTicketTotal() {
      const $calculator = $('.ticket-calculator');

      if (!$calculator.length) {
        return;
      }

      let total = 0;
      let totalTickets = 0;

      $calculator.find('.ticket-row').each(function () {
        const $row = $(this);
        const quantity = parseInt($row.find('.ticket-quantity').val()) || 0;
        const price = parseFloat($row.find('.ticket-price').data('price')) || 0;

        const rowTotal = quantity * price;
        total += rowTotal;
        totalTickets += quantity;

        $row.find('.row-total').text(this.formatCurrency(rowTotal));
      });

      $('.total-tickets').text(totalTickets);
      $('.total-amount').text(this.formatCurrency(total));

      // Update hidden fields
      $('#total_tickets').val(totalTickets);
      $('#total_amount').val(total.toFixed(2));

      // Show/hide payment section
      const $paymentSection = $('.payment-section');
      if (total > 0) {
        $paymentSection.slideDown();
      } else {
        $paymentSection.slideUp();
      }
    },

    /**
     * Navigate calendar
     */
    navigateCalendar($button) {
      const direction = $button.data('direction');
      const currentMonth = parseInt($('.calendar-container').data('month'));
      const currentYear = parseInt($('.calendar-container').data('year'));

      let newMonth = currentMonth;
      let newYear = currentYear;

      if (direction === 'prev') {
        newMonth--;
        if (newMonth < 1) {
          newMonth = 12;
          newYear--;
        }
      } else if (direction === 'next') {
        newMonth++;
        if (newMonth > 12) {
          newMonth = 1;
          newYear++;
        }
      }

      this.loadCalendarMonth(newMonth, newYear);
    },

    /**
     * Load calendar month
     */
    loadCalendarMonth(month, year) {
      const $calendar = $('.calendar-container');

      $calendar.addClass('loading');

      $.ajax({
        url: aqualuxe_events.ajax_url,
        type: 'POST',
        data: {
          action: 'aqualuxe_get_event_calendar',
          nonce: aqualuxe_events.nonce,
          month: month,
          year: year,
        },
        success: response => {
          if (response.success) {
            this.renderCalendarData(response.data);
          } else {
            this.showEventMessage('Error loading calendar', 'error');
          }
        },
        error: () => {
          this.showEventMessage('Failed to load calendar', 'error');
        },
        complete: () => {
          $calendar.removeClass('loading');
        },
      });
    },

    /**
     * Render calendar
     */
    renderCalendar() {
      const currentDate = new Date();
      this.loadCalendarMonth(
        currentDate.getMonth() + 1,
        currentDate.getFullYear()
      );
    },

    /**
     * Render calendar data
     */
    renderCalendarData(data) {
      const $calendar = $('.calendar-container');

      // Update month/year data
      $calendar.data('month', data.month);
      $calendar.data('year', data.year);

      // Update month/year display
      const monthNames = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December',
      ];

      $('.calendar-month-year').text(
        `${monthNames[data.month - 1]} ${data.year}`
      );

      // Generate calendar HTML
      const calendarHtml = this.generateCalendarHtml(
        data.month,
        data.year,
        data.events
      );
      $('.calendar-grid').html(calendarHtml);
    },

    /**
     * Generate calendar HTML
     */
    generateCalendarHtml(month, year, events) {
      const firstDay = new Date(year, month - 1, 1).getDay();
      const daysInMonth = new Date(year, month, 0).getDate();
      const today = new Date();

      let html = '<div class="calendar-header">';
      const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
      dayNames.forEach(day => {
        html += `<div class="day-header">${day}</div>`;
      });
      html += '</div>';

      html += '<div class="calendar-body">';

      // Empty cells for days before month starts
      for (let i = 0; i < firstDay; i++) {
        html += '<div class="calendar-day empty"></div>';
      }

      // Days of the month
      for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const dayEvents = events.filter(event => event.date === dateStr);

        let dayClass = 'calendar-day';

        // Check if today
        if (
          year === today.getFullYear() &&
          month === today.getMonth() + 1 &&
          day === today.getDate()
        ) {
          dayClass += ' today';
        }

        // Check if has events
        if (dayEvents.length > 0) {
          dayClass += ' has-events';
        }

        html += `<div class="${dayClass}" data-date="${dateStr}">`;
        html += `<span class="day-number">${day}</span>`;

        if (dayEvents.length > 0) {
          html += '<div class="day-events">';
          dayEvents.forEach(event => {
            html += `
              <div class="day-event" data-event-id="${event.id}">
                <a href="${event.url}" class="event-link">
                  <span class="event-time">${event.time || ''}</span>
                  <span class="event-title">${event.title}</span>
                </a>
              </div>
            `;
          });
          html += '</div>';
        }

        html += '</div>';
      }

      html += '</div>';

      return html;
    },

    /**
     * Toggle event details
     */
    toggleEventDetails($button) {
      const $details = $button.next('.event-details');

      $details.slideToggle();
      $button.toggleClass('expanded');

      const isExpanded = $button.hasClass('expanded');
      $button.attr('aria-expanded', isExpanded);
      $button
        .find('.toggle-text')
        .text(isExpanded ? 'Hide Details' : 'Show Details');
    },

    /**
     * Cancel event booking
     */
    cancelEventBooking($button) {
      if (!confirm(aqualuxe_events.strings.confirm_cancel)) {
        return;
      }

      const bookingId = $button.data('booking-id');
      const originalText = $button.text();

      $button.prop('disabled', true).text(aqualuxe_events.strings.loading);

      $.ajax({
        url: aqualuxe_events.ajax_url,
        type: 'POST',
        data: {
          action: 'aqualuxe_cancel_event_booking',
          nonce: aqualuxe_events.nonce,
          booking_id: bookingId,
        },
        success: response => {
          if (response.success) {
            this.showEventMessage(response.data.message, 'success');

            // Update booking display
            const $bookingItem = $button.closest('.event-booking-item');
            $bookingItem
              .addClass('cancelled')
              .find('.booking-status')
              .text('Cancelled');
            $button.hide();
          } else {
            this.showEventMessage(
              response.data || 'Error cancelling booking',
              'error'
            );
          }
        },
        error: () => {
          this.showEventMessage('Failed to cancel booking', 'error');
        },
        complete: () => {
          $button.prop('disabled', false).text(originalText);
        },
      });
    },

    /**
     * Add to calendar
     */
    addToCalendar($button) {
      const eventData = $button.data();

      const startDate = new Date(
        eventData.startDate + ' ' + (eventData.startTime || '')
      );
      const endDate = eventData.endDate
        ? new Date(eventData.endDate + ' ' + (eventData.endTime || ''))
        : new Date(startDate.getTime() + 2 * 60 * 60 * 1000); // Default 2 hours

      const calendarData = {
        title: eventData.title,
        start: this.formatCalendarDate(startDate),
        end: this.formatCalendarDate(endDate),
        description: eventData.description || '',
        location: eventData.location || '',
      };

      // Generate calendar URLs
      const urls = {
        google: this.generateGoogleCalendarUrl(calendarData),
        outlook: this.generateOutlookCalendarUrl(calendarData),
        ics: this.generateIcsFile(calendarData),
      };

      // Show calendar options
      this.showCalendarOptions(urls);
    },

    /**
     * Generate Google Calendar URL
     */
    generateGoogleCalendarUrl(data) {
      const baseUrl =
        'https://calendar.google.com/calendar/render?action=TEMPLATE';
      const params = new URLSearchParams({
        text: data.title,
        dates: `${data.start}/${data.end}`,
        details: data.description,
        location: data.location,
      });

      return `${baseUrl}&${params.toString()}`;
    },

    /**
     * Generate Outlook Calendar URL
     */
    generateOutlookCalendarUrl(data) {
      const baseUrl = 'https://outlook.live.com/calendar/0/deeplink/compose';
      const params = new URLSearchParams({
        subject: data.title,
        startdt: data.start,
        enddt: data.end,
        body: data.description,
        location: data.location,
      });

      return `${baseUrl}?${params.toString()}`;
    },

    /**
     * Generate ICS file
     */
    generateIcsFile(data) {
      const icsContent = [
        'BEGIN:VCALENDAR',
        'VERSION:2.0',
        'PRODID:-//AquaLuxe//Event//EN',
        'BEGIN:VEVENT',
        `UID:${Date.now()}@aqualuxe.com`,
        `DTSTART:${data.start}`,
        `DTEND:${data.end}`,
        `SUMMARY:${data.title}`,
        `DESCRIPTION:${data.description}`,
        `LOCATION:${data.location}`,
        'END:VEVENT',
        'END:VCALENDAR',
      ].join('\r\n');

      const blob = new Blob([icsContent], { type: 'text/calendar' });
      return URL.createObjectURL(blob);
    },

    /**
     * Show calendar options
     */
    showCalendarOptions(urls) {
      const $modal = $(`
        <div class="calendar-modal-overlay">
          <div class="calendar-modal">
            <div class="calendar-modal-header">
              <h3>Add to Calendar</h3>
              <button class="calendar-modal-close">&times;</button>
            </div>
            <div class="calendar-modal-body">
              <div class="calendar-options">
                <a href="${urls.google}" target="_blank" class="calendar-option google">
                  <span class="icon">📅</span>
                  <span class="text">Google Calendar</span>
                </a>
                <a href="${urls.outlook}" target="_blank" class="calendar-option outlook">
                  <span class="icon">📅</span>
                  <span class="text">Outlook Calendar</span>
                </a>
                <a href="${urls.ics}" download="event.ics" class="calendar-option ics">
                  <span class="icon">📥</span>
                  <span class="text">Download ICS file</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      `);

      $('body').append($modal);

      // Handle modal close
      $modal
        .find('.calendar-modal-close, .calendar-modal-overlay')
        .on('click', e => {
          if (e.target === e.currentTarget) {
            $modal.remove();
          }
        });

      // Auto-close after selection
      $modal.find('.calendar-option').on('click', () => {
        setTimeout(() => $modal.remove(), 1000);
      });
    },

    /**
     * Format date for calendar
     */
    formatCalendarDate(date) {
      return date.toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z';
    },

    /**
     * Validate event form
     */
    validateEventForm($form) {
      let isValid = true;
      const requiredFields = $form.find('[required]');

      requiredFields.each((index, field) => {
        if (!this.validateField($(field))) {
          isValid = false;
        }
      });

      // Custom validation
      const ticketsQuantity = parseInt($('#tickets_quantity').val()) || 0;

      if (ticketsQuantity <= 0) {
        this.showFieldError(
          '#tickets_quantity',
          aqualuxe_events.strings.select_tickets
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
     * Get event form data
     */
    getEventFormData($form) {
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
     * Reset event form
     */
    resetEventForm($form) {
      $form[0].reset();
      this.updateTicketTotal();
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
     * Format currency
     */
    formatCurrency(amount) {
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
      }).format(amount);
    },

    /**
     * Show message to user
     */
    showEventMessage(message, type = 'info') {
      // Remove existing messages
      $('.event-message').remove();

      const $message = $(`
        <div class="event-message message-${type}" role="alert">
          <p>${message}</p>
          <button type="button" class="message-close" aria-label="Close message">&times;</button>
        </div>
      `);

      // Add to top of form or page
      const $container = $('.event-booking-form').length
        ? $('.event-booking-form')
        : $('.events-container').length
          ? $('.events-container')
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
  };

  // Initialize when DOM is ready
  $(document).ready(() => {
    if ($('.events-container, .event-booking-form, .events-calendar').length) {
      AquaLuxeEvents.init();
    }
  });

  // Make available globally
  window.AquaLuxeEvents = AquaLuxeEvents;
})(jQuery);
