/**
 * AquaLuxe Theme - Customizer Controls
 *
 * Handles the customizer controls functionality.
 */

(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        // Color controls
        initColorControls();

        // Range controls
        initRangeControls();

        // Toggle controls
        initToggleControls();

        // Radio image controls
        initRadioImageControls();

        // Sortable controls
        initSortableControls();

        // Dimensions controls
        initDimensionsControls();

        // Typography controls
        initTypographyControls();

        // Responsive controls
        initResponsiveControls();

        // Initialize color controls
        function initColorControls() {
            // Check if color controls exist
            if (!$('.customize-control-color').length) {
                return;
            }

            // Initialize color pickers
            $('.customize-control-color .color-picker').wpColorPicker({
                change: function(event, ui) {
                    // Update hidden input
                    $(this).val(ui.color.toString()).trigger('change');
                },
                clear: function() {
                    // Update hidden input
                    $(this).val('').trigger('change');
                }
            });
        }

        // Initialize range controls
        function initRangeControls() {
            // Check if range controls exist
            if (!$('.customize-control-range').length) {
                return;
            }

            // Handle range input change
            $('.customize-control-range input[type="range"]').on('input', function() {
                const $range = $(this);
                const value = $range.val();
                
                // Update value display
                $range.next('.range-value').text(value);
                
                // Update hidden input
                $range.closest('.customize-control-range').find('input[type="hidden"]').val(value).trigger('change');
            });
        }

        // Initialize toggle controls
        function initToggleControls() {
            // Check if toggle controls exist
            if (!$('.customize-control-toggle').length) {
                return;
            }

            // Handle toggle change
            $('.customize-control-toggle input[type="checkbox"]').on('change', function() {
                const $checkbox = $(this);
                const value = $checkbox.prop('checked') ? '1' : '0';
                
                // Update hidden input
                $checkbox.closest('.customize-control-toggle').find('input[type="hidden"]').val(value).trigger('change');
            });
        }

        // Initialize radio image controls
        function initRadioImageControls() {
            // Check if radio image controls exist
            if (!$('.customize-control-radio-image').length) {
                return;
            }

            // Handle radio image change
            $('.customize-control-radio-image input[type="radio"]').on('change', function() {
                const $radio = $(this);
                const value = $radio.val();
                
                // Update hidden input
                $radio.closest('.customize-control-radio-image').find('input[type="hidden"]').val(value).trigger('change');
            });
        }

        // Initialize sortable controls
        function initSortableControls() {
            // Check if sortable controls exist
            if (!$('.customize-control-sortable').length) {
                return;
            }

            // Initialize sortable
            $('.customize-control-sortable ul').sortable({
                axis: 'y',
                update: function() {
                    const $sortable = $(this);
                    const items = [];
                    
                    // Get sorted items
                    $sortable.find('li').each(function() {
                        const $item = $(this);
                        const id = $item.data('id');
                        const visibility = $item.hasClass('invisible') ? '0' : '1';
                        
                        items.push({
                            id: id,
                            visibility: visibility
                        });
                    });
                    
                    // Update hidden input
                    $sortable.closest('.customize-control-sortable').find('input[type="hidden"]').val(JSON.stringify(items)).trigger('change');
                }
            });

            // Handle visibility toggle
            $('.customize-control-sortable .visibility').on('click', function() {
                const $button = $(this);
                const $item = $button.closest('li');
                
                // Toggle visibility
                $item.toggleClass('invisible');
                
                // Trigger update
                $button.closest('ul').sortable('option', 'update')();
            });
        }

        // Initialize dimensions controls
        function initDimensionsControls() {
            // Check if dimensions controls exist
            if (!$('.customize-control-dimensions').length) {
                return;
            }

            // Handle dimension input change
            $('.customize-control-dimensions .dimension-input').on('input', function() {
                const $input = $(this);
                const $control = $input.closest('.customize-control-dimensions');
                const values = {};
                
                // Get all dimension values
                $control.find('.dimension-input').each(function() {
                    const $dimension = $(this);
                    const dimension = $dimension.data('dimension');
                    const value = $dimension.val();
                    
                    values[dimension] = value;
                });
                
                // Update hidden input
                $control.find('input[type="hidden"]').val(JSON.stringify(values)).trigger('change');
            });
        }

        // Initialize typography controls
        function initTypographyControls() {
            // Check if typography controls exist
            if (!$('.customize-control-typography').length) {
                return;
            }

            // Handle typography input change
            $('.customize-control-typography select, .customize-control-typography input').on('change input', function() {
                const $input = $(this);
                const $control = $input.closest('.customize-control-typography');
                const values = {};
                
                // Get all typography values
                $control.find('select, input').each(function() {
                    const $field = $(this);
                    const property = $field.data('property');
                    const value = $field.val();
                    
                    values[property] = value;
                });
                
                // Update hidden input
                $control.find('input[type="hidden"]').val(JSON.stringify(values)).trigger('change');
            });

            // Initialize select2 for font family if available
            if (typeof $.fn.select2 !== 'undefined') {
                $('.customize-control-typography .typography-font-family').select2({
                    width: '100%'
                });
            }
        }

        // Initialize responsive controls
        function initResponsiveControls() {
            // Check if responsive controls exist
            if (!$('.customize-control-responsive').length) {
                return;
            }

            // Handle responsive switcher click
            $('.responsive-switcher').on('click', function() {
                const $button = $(this);
                const device = $button.data('device');
                const $control = $button.closest('.customize-control-responsive');
                
                // Remove active class from all switchers
                $control.find('.responsive-switcher').removeClass('active');
                
                // Add active class to clicked switcher
                $button.addClass('active');
                
                // Hide all fields
                $control.find('.responsive-field').removeClass('active');
                
                // Show field for selected device
                $control.find(`.responsive-field[data-device="${device}"]`).addClass('active');
            });

            // Handle responsive input change
            $('.customize-control-responsive .responsive-field input').on('input', function() {
                const $input = $(this);
                const $control = $input.closest('.customize-control-responsive');
                const values = {};
                
                // Get all responsive values
                $control.find('.responsive-field input').each(function() {
                    const $field = $(this);
                    const device = $field.closest('.responsive-field').data('device');
                    const value = $field.val();
                    
                    values[device] = value;
                });
                
                // Update hidden input
                $control.find('input[type="hidden"]').val(JSON.stringify(values)).trigger('change');
            });
        }
    });

})(jQuery);