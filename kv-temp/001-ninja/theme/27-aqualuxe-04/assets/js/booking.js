/**
 * AquaLuxe Booking System
 * Handles booking form functionality including date/time picker
 */

(function($) {
  'use strict';
  
  // Booking System Object
  const AquaLuxeBooking = {
    // Cache DOM elements
    dom: {
      bookingForm: $('.booking-form'),
      dateInput: $('.booking-date'),
      timeSelect: $('.booking-time'),
      serviceSelect: $('.booking-service'),
      durationField: $('.booking-duration'),
      priceField: $('.booking-price'),
      submitButton: $('.booking-submit'),
      bookingResult: $('.booking-result'),
      bookingSummary: $('.booking-summary'),
      loadingOverlay: $('.booking-loading')
    },
    
    // Available time slots
    timeSlots: [
      '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
      '13:00', '13:30', '14:00', '14:30', '15:00', '15:30',
      '16:00', '16:30', '17:00'
    ],
    
    // Booked slots (will be populated from the server)
    bookedSlots: {},
    
    // Services data (will be populated from the server)
    services: {},
    
    // Initialize booking system
    init: function() {
      const self = this;
      
      // Skip if booking form doesn't exist
      if (!this.dom.bookingForm.length) return;
      
      // Initialize date picker
      if (this.dom.dateInput.length) {
        this.initDatePicker();
      }
      
      // Handle service selection
      if (this.dom.serviceSelect.length) {
        this.dom.serviceSelect.on('change', function() {
          self.updateServiceDetails();
        });
      }
      
      // Handle form submission
      if (this.dom.bookingForm.length) {
        this.dom.bookingForm.on('submit', function(e) {
          e.preventDefault();
          self.submitBooking();
        });
      }
      
      // Load initial data
      this.loadBookingData();
    },
    
    // Initialize date picker
    initDatePicker: function() {
      const self = this;
      
      // Initialize flatpickr date picker
      if (typeof flatpickr !== 'undefined') {
        flatpickr(this.dom.dateInput[0], {
          minDate: 'today',
          maxDate: new Date().fp_incr(60), // Allow booking up to 60 days in advance
          disable: [
            function(date) {
              // Disable weekends if needed
              // return date.getDay() === 0 || date.getDay() === 6;
              return false;
            }
          ],
          locale: {
            firstDayOfWeek: 1 // Start week on Monday
          },
          onChange: function(selectedDates, dateStr) {
            self.updateTimeSlots(dateStr);
          }
        });
      } else {
        // Fallback for when flatpickr is not available
        this.dom.dateInput.attr('type', 'date');
        
        // Set min date to today
        const today = new Date().toISOString().split('T')[0];
        this.dom.dateInput.attr('min', today);
        
        // Set max date to 60 days from now
        const maxDate = new Date();
        maxDate.setDate(maxDate.getDate() + 60);
        this.dom.dateInput.attr('max', maxDate.toISOString().split('T')[0]);
        
        // Handle date change
        this.dom.dateInput.on('change', function() {
          self.updateTimeSlots($(this).val());
        });
      }
    },
    
    // Load booking data from the server
    loadBookingData: function() {
      const self = this;
      
      // Show loading overlay
      this.dom.loadingOverlay.removeClass('hidden');
      
      // Fetch booking data from the server
      $.ajax({
        url: aqualuxeSettings.ajaxUrl,
        type: 'POST',
        data: {
          action: 'aqualuxe_get_booking_data',
          nonce: aqualuxeSettings.nonce
        },
        success: function(response) {
          if (response.success) {
            // Store booked slots
            self.bookedSlots = response.data.bookedSlots || {};
            
            // Store services data
            self.services = response.data.services || {};
            
            // Update service select options
            self.updateServiceOptions();
            
            // Update service details for the initially selected service
            self.updateServiceDetails();
            
            // Update time slots if date is already selected
            const selectedDate = self.dom.dateInput.val();
            if (selectedDate) {
              self.updateTimeSlots(selectedDate);
            }
          } else {
            console.error('Error loading booking data:', response.data);
          }
          
          // Hide loading overlay
          self.dom.loadingOverlay.addClass('hidden');
        },
        error: function(xhr, status, error) {
          console.error('AJAX error:', error);
          
          // Hide loading overlay
          self.dom.loadingOverlay.addClass('hidden');
        }
      });
    },
    
    // Update service select options
    updateServiceOptions: function() {
      const select = this.dom.serviceSelect;
      
      // Skip if select doesn't exist or no services data
      if (!select.length || $.isEmptyObject(this.services)) return;
      
      // Clear existing options except the placeholder
      select.find('option:not([value=""])').remove();
      
      // Add options for each service
      $.each(this.services, function(id, service) {
        select.append(
          $('<option></option>')
            .attr('value', id)
            .text(service.name)
            .data('duration', service.duration)
            .data('price', service.price)
        );
      });
    },
    
    // Update service details when service is selected
    updateServiceDetails: function() {
      const serviceId = this.dom.serviceSelect.val();
      
      // Skip if no service is selected
      if (!serviceId) {
        this.dom.durationField.text('');
        this.dom.priceField.text('');
        return;
      }
      
      // Get selected option
      const selectedOption = this.dom.serviceSelect.find('option:selected');
      
      // Update duration and price fields
      this.dom.durationField.text(selectedOption.data('duration'));
      this.dom.priceField.text(selectedOption.data('price'));
    },
    
    // Update available time slots for the selected date
    updateTimeSlots: function(dateStr) {
      const self = this;
      const timeSelect = this.dom.timeSelect;
      
      // Skip if time select doesn't exist
      if (!timeSelect.length) return;
      
      // Clear existing options
      timeSelect.empty();
      
      // Add placeholder option
      timeSelect.append(
        $('<option></option>')
          .attr('value', '')
          .text(aqualuxeSettings.i18n.selectTime || 'Select a time')
      );
      
      // Get booked slots for the selected date
      const dateBookings = this.bookedSlots[dateStr] || [];
      
      // Add available time slots
      $.each(this.timeSlots, function(index, time) {
        // Skip if time slot is already booked
        if (dateBookings.includes(time)) return;
        
        // Add time option
        timeSelect.append(
          $('<option></option>')
            .attr('value', time)
            .text(time)
        );
      });
      
      // Enable time select if there are available slots
      if (timeSelect.find('option').length > 1) {
        timeSelect.prop('disabled', false);
      } else {
        // Add "No available slots" option if all slots are booked
        timeSelect.append(
          $('<option></option>')
            .attr('value', '')
            .attr('disabled', true)
            .text(aqualuxeSettings.i18n.noAvailableSlots || 'No available slots')
        );
        timeSelect.prop('disabled', true);
      }
    },
    
    // Submit booking form
    submitBooking: function() {
      const self = this;
      
      // Validate form
      if (!this.validateForm()) {
        return;
      }
      
      // Show loading overlay
      this.dom.loadingOverlay.removeClass('hidden');
      
      // Get form data
      const formData = this.dom.bookingForm.serialize();
      
      // Submit booking to the server
      $.ajax({
        url: aqualuxeSettings.ajaxUrl,
        type: 'POST',
        data: formData + '&action=aqualuxe_submit_booking&nonce=' + aqualuxeSettings.nonce,
        success: function(response) {
          // Hide loading overlay
          self.dom.loadingOverlay.addClass('hidden');
          
          if (response.success) {
            // Show success message
            self.showBookingResult(true, response.data.message);
            
            // Show booking summary
            self.showBookingSummary(response.data.booking);
            
            // Reset form
            self.dom.bookingForm[0].reset();
          } else {
            // Show error message
            self.showBookingResult(false, response.data.message);
          }
        },
        error: function(xhr, status, error) {
          // Hide loading overlay
          self.dom.loadingOverlay.addClass('hidden');
          
          // Show error message
          self.showBookingResult(false, 'An error occurred while processing your booking. Please try again.');
          console.error('AJAX error:', error);
        }
      });
    },
    
    // Validate booking form
    validateForm: function() {
      const form = this.dom.bookingForm[0];
      
      // Use HTML5 validation if available
      if (form.checkValidity) {
        return form.checkValidity();
      }
      
      // Manual validation as fallback
      let isValid = true;
      
      // Check required fields
      this.dom.bookingForm.find('[required]').each(function() {
        if (!$(this).val()) {
          isValid = false;
          $(this).addClass('error');
        } else {
          $(this).removeClass('error');
        }
      });
      
      return isValid;
    },
    
    // Show booking result message
    showBookingResult: function(success, message) {
      const resultElement = this.dom.bookingResult;
      
      // Skip if result element doesn't exist
      if (!resultElement.length) return;
      
      // Set message and class
      resultElement.text(message);
      resultElement.removeClass('hidden success error');
      resultElement.addClass(success ? 'success' : 'error');
      
      // Scroll to result message
      $('html, body').animate({
        scrollTop: resultElement.offset().top - 100
      }, 500);
      
      // Hide message after 10 seconds if it's a success message
      if (success) {
        setTimeout(function() {
          resultElement.addClass('hidden');
        }, 10000);
      }
    },
    
    // Show booking summary
    showBookingSummary: function(booking) {
      const summaryElement = this.dom.bookingSummary;
      
      // Skip if summary element doesn't exist or no booking data
      if (!summaryElement.length || !booking) return;
      
      // Create summary HTML
      const summaryHtml = `
        <div class="booking-summary-content p-6 bg-primary-50 dark:bg-primary-900/30 rounded-lg">
          <h3 class="text-xl font-bold mb-4">${aqualuxeSettings.i18n.bookingConfirmed || 'Booking Confirmed'}</h3>
          
          <div class="booking-summary-details space-y-2">
            <div class="flex justify-between">
              <span class="font-medium">${aqualuxeSettings.i18n.bookingId || 'Booking ID'}:</span>
              <span>${booking.id}</span>
            </div>
            <div class="flex justify-between">
              <span class="font-medium">${aqualuxeSettings.i18n.service || 'Service'}:</span>
              <span>${booking.service}</span>
            </div>
            <div class="flex justify-between">
              <span class="font-medium">${aqualuxeSettings.i18n.date || 'Date'}:</span>
              <span>${booking.date}</span>
            </div>
            <div class="flex justify-between">
              <span class="font-medium">${aqualuxeSettings.i18n.time || 'Time'}:</span>
              <span>${booking.time}</span>
            </div>
            <div class="flex justify-between">
              <span class="font-medium">${aqualuxeSettings.i18n.duration || 'Duration'}:</span>
              <span>${booking.duration}</span>
            </div>
            <div class="flex justify-between">
              <span class="font-medium">${aqualuxeSettings.i18n.price || 'Price'}:</span>
              <span>${booking.price}</span>
            </div>
          </div>
          
          <div class="booking-summary-note mt-4 text-sm">
            <p>${aqualuxeSettings.i18n.bookingNote || 'A confirmation email has been sent to your email address.'}</p>
          </div>
        </div>
      `;
      
      // Update summary element
      summaryElement.html(summaryHtml);
      summaryElement.removeClass('hidden');
    }
  };
  
  // Initialize booking system when document is ready
  $(document).ready(function() {
    AquaLuxeBooking.init();
  });
  
})(jQuery);