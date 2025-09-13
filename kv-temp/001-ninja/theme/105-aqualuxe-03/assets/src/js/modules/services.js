/**
 * AquaLuxe Services Module JavaScript
 *
 * Handles service inquiry forms and interactions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function ($) {
  'use strict';

  /**
   * Services Module Object
   */
  const AquaLuxeServices = {
    /**
     * Initialize the module
     */
    init: function () {
      this.bindEvents();
      this.initServiceInquiry();
    },

    /**
     * Bind events
     */
    bindEvents: function () {
      $(document).on(
        'submit',
        '#service-inquiry-form',
        this.handleServiceInquiry.bind(this)
      );
      $(document).on(
        'click',
        '.service-booking-btn',
        this.handleBookingClick.bind(this)
      );
    },

    /**
     * Initialize service inquiry form
     */
    initServiceInquiry: function () {
      // Add form validation
      $('#service-inquiry-form').on('submit', function (e) {
        const form = $(this);
        const requiredFields = form.find('[required]');
        let isValid = true;

        // Clear previous error states
        form.find('.error').removeClass('error');
        form.find('.error-message').remove();

        // Validate required fields
        requiredFields.each(function () {
          const field = $(this);
          const value = field.val().trim();

          if (!value) {
            field.addClass('error');
            field.after(
              '<span class="error-message text-red-500 text-sm mt-1">' +
                window.aqualuxeServices.strings.required_fields +
                '</span>'
            );
            isValid = false;
          }
        });

        // Validate email format
        const emailField = form.find('input[type="email"]');
        if (emailField.length && emailField.val()) {
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!emailRegex.test(emailField.val())) {
            emailField.addClass('error');
            emailField.after(
              '<span class="error-message text-red-500 text-sm mt-1">Please enter a valid email address.</span>'
            );
            isValid = false;
          }
        }

        if (!isValid) {
          e.preventDefault();
          // Scroll to first error
          const firstError = form.find('.error').first();
          if (firstError.length) {
            $('html, body').animate(
              {
                scrollTop: firstError.offset().top - 100,
              },
              500
            );
          }
        }
      });
    },

    /**
     * Handle service inquiry form submission
     */
    handleServiceInquiry: function (e) {
      e.preventDefault();

      const form = $(e.target);
      const formData = new FormData(form[0]);
      const submitBtn = form.find('button[type="submit"]');
      const messagesContainer = form.find('.form-messages');

      // Add AJAX data
      formData.append('action', 'aqualuxe_service_inquiry');
      formData.append('nonce', window.aqualuxeServices.nonce);

      // Disable submit button and show loading
      submitBtn.prop('disabled', true);
      submitBtn.html('<span class="loading-spinner"></span> Sending...');

      // Clear previous messages
      messagesContainer.empty();

      $.ajax({
        url: window.aqualuxeServices.ajax_url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          if (response.success) {
            messagesContainer.html(
              '<div class="alert alert-success">' + response.data + '</div>'
            );
            form[0].reset();
          } else {
            messagesContainer.html(
              '<div class="alert alert-error">' + response.data + '</div>'
            );
          }
        },
        error: function () {
          messagesContainer.html(
            '<div class="alert alert-error">' +
              window.aqualuxeServices.strings.inquiry_error +
              '</div>'
          );
        },
        complete: function () {
          // Re-enable submit button
          submitBtn.prop('disabled', false);
          submitBtn.html('Send Inquiry');

          // Scroll to messages
          if (messagesContainer.children().length > 0) {
            $('html, body').animate(
              {
                scrollTop: messagesContainer.offset().top - 100,
              },
              500
            );
          }
        },
      });
    },

    /**
     * Handle booking button click
     */
    handleBookingClick: function (e) {
      const button = $(e.target);
      const serviceId = button.data('service-id');

      // Add click tracking or analytics here if needed
      if (window.gtag) {
        gtag('event', 'service_booking_clicked', {
          event_category: 'Services',
          event_label: 'Service ID: ' + serviceId,
        });
      }
    },

    /**
     * Smooth scroll to element
     */
    scrollToElement: function (element) {
      $('html, body').animate(
        {
          scrollTop: $(element).offset().top - 100,
        },
        500
      );
    },
  };

  /**
   * Service Filter and Search
   */
  const ServiceFilters = {
    init: function () {
      this.bindEvents();
    },

    bindEvents: function () {
      $(document).on(
        'change',
        '.service-filter',
        this.handleFilterChange.bind(this)
      );
      $(document).on(
        'submit',
        '.service-search-form',
        this.handleSearch.bind(this)
      );
    },

    handleFilterChange: function (e) {
      const filter = $(e.target);
      const filterType = filter.data('filter-type');
      const filterValue = filter.val();

      // Update URL parameters
      const url = new URL(window.location);
      if (filterValue) {
        url.searchParams.set(filterType, filterValue);
      } else {
        url.searchParams.delete(filterType);
      }

      // Navigate to filtered results
      window.location.href = url.toString();
    },

    handleSearch: function (e) {
      e.preventDefault();

      const form = $(e.target);
      const searchTerm = form.find('input[name="s"]').val();

      if (searchTerm.trim()) {
        const url = new URL(window.location);
        url.searchParams.set('s', searchTerm);
        window.location.href = url.toString();
      }
    },
  };

  /**
   * Service Gallery
   */
  const ServiceGallery = {
    init: function () {
      this.initLightbox();
    },

    initLightbox: function () {
      // Initialize lightbox for service images
      if (typeof $.fn.magnificPopup !== 'undefined') {
        $('.service-gallery').magnificPopup({
          delegate: 'a',
          type: 'image',
          gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0, 1],
          },
          image: {
            titleSrc: function (item) {
              return item.el.attr('title') || item.el.find('img').attr('alt');
            },
          },
        });
      }
    },
  };

  /**
   * Initialize when document is ready
   */
  $(document).ready(function () {
    AquaLuxeServices.init();
    ServiceFilters.init();
    ServiceGallery.init();
  });

  // Expose to global scope for external access
  window.AquaLuxeServices = AquaLuxeServices;
})(jQuery);
