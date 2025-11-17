/**
 * AquaLuxe Theme Customizer Controls JavaScript
 *
 * This file contains the JavaScript functionality for the theme customizer controls.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        // Color Alpha Control
        $('.aqualuxe-color-alpha-control').wpColorPicker({
            change: function(event, ui) {
                // Update the value in the input field
                $(this).val(ui.color.toString());
                $(this).trigger('change');
            },
            clear: function() {
                // Update the value in the input field
                $(this).val('');
                $(this).trigger('change');
            }
        });

        // Toggle Control
        $('.aqualuxe-toggle-control').on('change', function() {
            var $this = $(this);
            var value = $this.is(':checked') ? true : false;
            
            // Update the hidden input value
            $this.closest('.aqualuxe-toggle-wrapper').find('input[type="hidden"]').val(value).trigger('change');
        });

        // Slider Control
        $('.aqualuxe-slider-control').each(function() {
            var $this = $(this);
            var $slider = $this.find('.aqualuxe-slider');
            var $input = $this.find('.aqualuxe-slider-input');
            var min = parseInt($slider.data('min'), 10);
            var max = parseInt($slider.data('max'), 10);
            var step = parseInt($slider.data('step'), 10);
            var value = parseInt($input.val(), 10);

            // Initialize slider
            $slider.slider({
                range: 'min',
                min: min,
                max: max,
                step: step,
                value: value,
                slide: function(event, ui) {
                    $input.val(ui.value);
                    $input.trigger('change');
                }
            });

            // Update slider when input changes
            $input.on('change', function() {
                var newValue = parseInt($(this).val(), 10);
                
                if (isNaN(newValue)) {
                    newValue = min;
                }
                
                if (newValue < min) {
                    newValue = min;
                    $(this).val(min);
                }
                
                if (newValue > max) {
                    newValue = max;
                    $(this).val(max);
                }
                
                $slider.slider('value', newValue);
            });
        });

        // Typography Control
        $('.aqualuxe-typography-control').each(function() {
            var $this = $(this);
            var $fontFamily = $this.find('.aqualuxe-typography-font-family');
            var $fontSize = $this.find('.aqualuxe-typography-font-size');
            var $fontWeight = $this.find('.aqualuxe-typography-font-weight');
            var $lineHeight = $this.find('.aqualuxe-typography-line-height');
            var $textTransform = $this.find('.aqualuxe-typography-text-transform');
            var $hidden = $this.find('.aqualuxe-typography-value');

            // Update hidden input when any control changes
            $fontFamily.add($fontSize).add($fontWeight).add($lineHeight).add($textTransform).on('change', function() {
                var value = {
                    'font-family': $fontFamily.val(),
                    'font-size': $fontSize.val(),
                    'font-weight': $fontWeight.val(),
                    'line-height': $lineHeight.val(),
                    'text-transform': $textTransform.val()
                };
                
                $hidden.val(JSON.stringify(value)).trigger('change');
            });
        });

        // Sortable Control
        $('.aqualuxe-sortable-control').each(function() {
            var $this = $(this);
            var $sortable = $this.find('.aqualuxe-sortable-list');
            var $hidden = $this.find('.aqualuxe-sortable-value');

            // Initialize sortable
            $sortable.sortable({
                update: function() {
                    var value = [];
                    
                    $sortable.find('li').each(function() {
                        value.push($(this).data('value'));
                    });
                    
                    $hidden.val(JSON.stringify(value)).trigger('change');
                }
            });

            // Toggle items
            $sortable.on('click', '.aqualuxe-sortable-toggle', function() {
                var $item = $(this).closest('li');
                
                $item.toggleClass('disabled');
                
                var value = [];
                
                $sortable.find('li').each(function() {
                    var $li = $(this);
                    var itemValue = $li.data('value');
                    
                    if (!$li.hasClass('disabled')) {
                        value.push(itemValue);
                    }
                });
                
                $hidden.val(JSON.stringify(value)).trigger('change');
            });
        });

        // Dependency Control
        function handleDependency() {
            $('.aqualuxe-control-dependency').each(function() {
                var $this = $(this);
                var controlId = $this.data('control');
                var operator = $this.data('operator');
                var value = $this.data('value');
                var $control = $('#customize-control-' + controlId);
                var currentValue = wp.customize(controlId).get();
                var isVisible = false;

                // Check if control should be visible
                switch (operator) {
                    case '==':
                        isVisible = (currentValue == value);
                        break;
                    case '!=':
                        isVisible = (currentValue != value);
                        break;
                    case '>':
                        isVisible = (currentValue > value);
                        break;
                    case '<':
                        isVisible = (currentValue < value);
                        break;
                    case '>=':
                        isVisible = (currentValue >= value);
                        break;
                    case '<=':
                        isVisible = (currentValue <= value);
                        break;
                    case 'in':
                        isVisible = ($.inArray(currentValue, value.split(',')) !== -1);
                        break;
                    case 'not_in':
                        isVisible = ($.inArray(currentValue, value.split(',')) === -1);
                        break;
                }

                // Show or hide control
                if (isVisible) {
                    $control.show();
                } else {
                    $control.hide();
                }
            });
        }

        // Handle dependency on load
        handleDependency();

        // Handle dependency on change
        wp.customize.bind('change', function(setting) {
            handleDependency();
        });
    });

})(jQuery);