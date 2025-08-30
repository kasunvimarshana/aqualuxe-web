/**
 * AquaLuxe Theme - Customizer Controls
 *
 * This file handles the JavaScript for the WordPress Customizer controls.
 */

(function($) {
  'use strict';

  // When the document is ready
  $(document).ready(function() {
    // Initialize color pickers
    $('.aqualuxe-color-picker').wpColorPicker();

    // Initialize range sliders
    $('.aqualuxe-range-slider').each(function() {
      const slider = $(this);
      const input = slider.next('.aqualuxe-range-value');
      const min = parseFloat(slider.attr('min'));
      const max = parseFloat(slider.attr('max'));
      const step = parseFloat(slider.attr('step'));
      
      slider.on('input', function() {
        input.val(slider.val());
        input.trigger('change');
      });
      
      input.on('change', function() {
        let value = parseFloat(input.val());
        
        // Ensure the value is within the min and max range
        if (value < min) {
          value = min;
        } else if (value > max) {
          value = max;
        }
        
        // Round to the nearest step
        value = Math.round(value / step) * step;
        
        // Update the input and slider
        input.val(value);
        slider.val(value);
        
        // Trigger the change event on the slider
        slider.trigger('change');
      });
    });

    // Initialize sortable controls
    $('.aqualuxe-sortable').sortable({
      update: function(event, ui) {
        const values = [];
        
        $(this).find('.aqualuxe-sortable-item').each(function() {
          values.push($(this).data('value'));
        });
        
        $(this).next('input').val(JSON.stringify(values)).trigger('change');
      }
    });

    // Initialize typography controls
    $('.aqualuxe-typography-control').each(function() {
      const control = $(this);
      const fontFamily = control.find('.aqualuxe-font-family');
      const fontWeight = control.find('.aqualuxe-font-weight');
      const fontSize = control.find('.aqualuxe-font-size');
      const lineHeight = control.find('.aqualuxe-line-height');
      const letterSpacing = control.find('.aqualuxe-letter-spacing');
      const textTransform = control.find('.aqualuxe-text-transform');
      const input = control.find('.aqualuxe-typography-value');
      
      // Update the hidden input when any control changes
      control.on('change', 'select, input', function() {
        const value = {
          fontFamily: fontFamily.val(),
          fontWeight: fontWeight.val(),
          fontSize: fontSize.val(),
          lineHeight: lineHeight.val(),
          letterSpacing: letterSpacing.val(),
          textTransform: textTransform.val()
        };
        
        input.val(JSON.stringify(value)).trigger('change');
      });
      
      // Update font weights when font family changes
      fontFamily.on('change', function() {
        const family = $(this).val();
        const weights = window.aqualuxeCustomizerControls.fontWeights[family] || ['400', '700'];
        
        // Clear the current options
        fontWeight.empty();
        
        // Add the new options
        weights.forEach(function(weight) {
          fontWeight.append($('<option></option>').attr('value', weight).text(weight));
        });
        
        // Trigger change event
        fontWeight.trigger('change');
      });
    });

    // Initialize dimensions control
    $('.aqualuxe-dimensions-control').each(function() {
      const control = $(this);
      const inputs = control.find('.aqualuxe-dimension-input');
      const linkedButton = control.find('.aqualuxe-dimensions-link');
      const hiddenInput = control.find('.aqualuxe-dimensions-value');
      let linked = true;
      
      // Toggle linked state
      linkedButton.on('click', function(e) {
        e.preventDefault();
        
        linked = !linked;
        linkedButton.toggleClass('aqualuxe-dimensions-linked', linked);
        
        if (linked) {
          const value = inputs.first().val();
          inputs.val(value);
          updateValue();
        }
      });
      
      // Update when inputs change
      inputs.on('input', function() {
        if (linked) {
          const value = $(this).val();
          inputs.val(value);
        }
        
        updateValue();
      });
      
      // Update the hidden input
      function updateValue() {
        const values = {};
        
        inputs.each(function() {
          const side = $(this).data('side');
          values[side] = $(this).val();
        });
        
        hiddenInput.val(JSON.stringify(values)).trigger('change');
      }
    });

    // Initialize image radio buttons
    $('.aqualuxe-radio-image').on('click', function() {
      const radioGroup = $(this).closest('.aqualuxe-radio-image-group');
      const input = radioGroup.next('input');
      
      // Remove active class from all images
      radioGroup.find('.aqualuxe-radio-image').removeClass('active');
      
      // Add active class to the clicked image
      $(this).addClass('active');
      
      // Update the hidden input
      input.val($(this).data('value')).trigger('change');
    });

    // Initialize toggle controls
    $('.aqualuxe-toggle-control').on('click', function() {
      const control = $(this);
      const input = control.next('input');
      const isChecked = input.val() === 'true';
      
      // Toggle the value
      input.val(isChecked ? 'false' : 'true').trigger('change');
      
      // Update the control
      control.toggleClass('aqualuxe-toggle-on', !isChecked);
    });
  });
})(jQuery);