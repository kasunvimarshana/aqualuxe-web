/**
 * Event Admin Scripts
 *
 * Handles event admin form functionality
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function ($) {
  'use strict';

  $(document).ready(function () {
    // Handle free event checkbox
    const freeCheckbox = $('#aqualuxe_is_free');
    const priceRow = $('.ticket-price-row');

    if (freeCheckbox.length && priceRow.length) {
      freeCheckbox.on('change', function () {
        if (this.checked) {
          priceRow.hide();
          priceRow.find('input').val('');
        } else {
          priceRow.show();
        }
      });

      // Initialize state
      freeCheckbox.trigger('change');
    }

    // Handle location type selection
    const locationTypeSelect = $('#aqualuxe_location_type');
    const physicalRows = $('.physical-location');
    const onlineRows = $('.online-location');

    if (locationTypeSelect.length) {
      locationTypeSelect.on('change', function () {
        const type = $(this).val();

        if (type === 'physical') {
          physicalRows.show();
          onlineRows.hide();
          onlineRows.find('input').val('');
        } else if (type === 'online') {
          onlineRows.show();
          physicalRows.hide();
          physicalRows.find('input').val('');
        } else if (type === 'hybrid') {
          physicalRows.show();
          onlineRows.show();
        }
      });

      // Initialize state
      locationTypeSelect.trigger('change');
    }

    // Handle capacity unlimited checkbox
    const unlimitedCheckbox = $('#aqualuxe_unlimited_capacity');
    const capacityRow = $('.capacity-number-row');

    if (unlimitedCheckbox.length && capacityRow.length) {
      unlimitedCheckbox.on('change', function () {
        if (this.checked) {
          capacityRow.hide();
          capacityRow.find('input').val('');
        } else {
          capacityRow.show();
        }
      });

      // Initialize state
      unlimitedCheckbox.trigger('change');
    }

    // Add repeatable fields functionality
    $('.add-speaker').on('click', function (e) {
      e.preventDefault();
      const container = $('.speakers-container');
      const template = $('.speaker-template').html();
      const index = container.find('.speaker-row').length;

      const newField = $(template.replace(/\[INDEX\]/g, index));
      container.append(newField);
    });

    // Remove speaker functionality
    $(document).on('click', '.remove-speaker', function (e) {
      e.preventDefault();
      $(this).closest('.speaker-row').remove();
    });

    // Date and time validation
    const startDate = $('#aqualuxe_start_date');
    const endDate = $('#aqualuxe_end_date');
    const startTime = $('#aqualuxe_start_time');
    const endTime = $('#aqualuxe_end_time');

    function validateDateTime() {
      if (!startDate.val() || !endDate.val()) {
        return;
      }

      const start = new Date(
        startDate.val() + 'T' + (startTime.val() || '00:00')
      );
      const end = new Date(endDate.val() + 'T' + (endTime.val() || '23:59'));

      if (end <= start) {
        endDate.css('border-color', '#dc3545');
        endTime.css('border-color', '#dc3545');

        // Show error message
        let errorMsg = endDate.siblings('.datetime-error');
        if (!errorMsg.length) {
          errorMsg = $(
            '<div class="datetime-error" style="color: #dc3545; font-size: 12px; margin-top: 5px;">End date/time must be after start date/time</div>'
          );
          endDate.after(errorMsg);
        }
      } else {
        endDate.css('border-color', '');
        endTime.css('border-color', '');
        endDate.siblings('.datetime-error').remove();
      }
    }

    startDate
      .add(endDate)
      .add(startTime)
      .add(endTime)
      .on('change', validateDateTime);
  });
})(jQuery);
