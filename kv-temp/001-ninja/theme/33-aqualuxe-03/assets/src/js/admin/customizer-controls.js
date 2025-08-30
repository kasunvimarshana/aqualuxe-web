/**
 * AquaLuxe Theme - Customizer Controls
 *
 * This file handles the JavaScript functionality for the WordPress Customizer controls.
 */

(function($) {
    'use strict';

    // Document ready
    $(function() {
        // Color picker initialization
        $('.aqualuxe-color-picker').wpColorPicker();

        // Slider control
        $('.aqualuxe-slider').each(function() {
            var $this = $(this);
            var $input = $this.closest('.aqualuxe-slider-control').find('input[type="number"]');
            var min = parseInt($this.attr('min'), 10);
            var max = parseInt($this.attr('max'), 10);
            var step = parseInt($this.attr('step'), 10);

            $this.slider({
                range: 'min',
                min: min,
                max: max,
                step: step,
                value: $input.val(),
                slide: function(event, ui) {
                    $input.val(ui.value).trigger('change');
                }
            });

            $input.on('change', function() {
                $this.slider('value', $(this).val());
            });
        });

        // Sortable control
        $('.aqualuxe-sortable').sortable({
            update: function(event, ui) {
                var sortedItems = [];
                $(this).find('li').each(function() {
                    sortedItems.push($(this).attr('data-value'));
                });
                $(this).closest('.aqualuxe-sortable-control').find('input').val(sortedItems.join(',')).trigger('change');
            }
        }).disableSelection();

        // Radio image control
        $('.aqualuxe-radio-image-control input[type="radio"]').on('change', function() {
            $(this).closest('.aqualuxe-radio-image-control').find('label').removeClass('selected');
            $(this).closest('label').addClass('selected');
        });

        // Typography control
        $('.aqualuxe-typography-control select').on('change', function() {
            var $control = $(this).closest('.aqualuxe-typography-control');
            var values = {};

            $control.find('select, input').each(function() {
                var $this = $(this);
                values[$this.attr('data-name')] = $this.val();
            });

            $control.find('input[type="hidden"]').val(JSON.stringify(values)).trigger('change');
        });

        // Dimensions control
        $('.aqualuxe-dimensions-control input[type="number"]').on('change', function() {
            var $control = $(this).closest('.aqualuxe-dimensions-control');
            var values = {};

            $control.find('input[type="number"]').each(function() {
                var $this = $(this);
                values[$this.attr('data-name')] = $this.val();
            });

            $control.find('input[type="hidden"]').val(JSON.stringify(values)).trigger('change');
        });

        // Toggle control
        $('.aqualuxe-toggle-control input[type="checkbox"]').on('change', function() {
            var $this = $(this);
            var value = $this.is(':checked') ? 'true' : 'false';
            $this.closest('.aqualuxe-toggle-control').find('input[type="hidden"]').val(value).trigger('change');
        });
    });

})(jQuery);