/**
 * AquaLuxe Theme Customizer Controls JavaScript
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        // Initialize color pickers
        initColorPickers();
        
        // Initialize range sliders
        initRangeSliders();
        
        // Initialize dependencies
        initDependencies();
        
        // Initialize custom controls
        initCustomControls();
    });

    /**
     * Initialize Color Pickers
     */
    function initColorPickers() {
        // Make sure wpColorPicker is available
        if (!$.fn.wpColorPicker) {
            return;
        }
        
        // Initialize color pickers
        $('.aqualuxe-color-picker').wpColorPicker({
            change: function(event, ui) {
                // Trigger change event for live preview
                $(this).val(ui.color.toString()).trigger('change');
            }
        });
    }

    /**
     * Initialize Range Sliders
     */
    function initRangeSliders() {
        $('.aqualuxe-range-slider').each(function() {
            var $slider = $(this);
            var $input = $slider.next('input[type="number"]');
            var min = parseInt($input.attr('min'));
            var max = parseInt($input.attr('max'));
            var step = parseInt($input.attr('step'));
            var value = parseInt($input.val());
            
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
                $slider.slider('value', $(this).val());
            });
        });
    }

    /**
     * Initialize Dependencies
     */
    function initDependencies() {
        // Dark mode dependencies
        wp.customize('dark_mode_enabled', function(setting) {
            var toggleVisibility = function(control, isVisible) {
                var $control = $('#customize-control-' + control);
                
                if (isVisible) {
                    $control.slideDown();
                } else {
                    $control.slideUp();
                }
            };
            
            // Initial visibility
            toggleVisibility('dark_mode_auto', setting.get());
            toggleVisibility('dark_mode_cookies', setting.get());
            toggleVisibility('dark_mode_default', setting.get());
            toggleVisibility('dark_mode_primary_color', setting.get());
            toggleVisibility('dark_mode_bg_color', setting.get());
            toggleVisibility('dark_mode_text_color', setting.get());
            
            // Update on change
            setting.bind(function(value) {
                toggleVisibility('dark_mode_auto', value);
                toggleVisibility('dark_mode_cookies', value);
                toggleVisibility('dark_mode_default', value);
                toggleVisibility('dark_mode_primary_color', value);
                toggleVisibility('dark_mode_bg_color', value);
                toggleVisibility('dark_mode_text_color', value);
            });
        });
        
        // WooCommerce dependencies
        wp.customize('related_products', function(setting) {
            var toggleVisibility = function(isVisible) {
                var $control = $('#customize-control-related_products_count');
                
                if (isVisible) {
                    $control.slideDown();
                } else {
                    $control.slideUp();
                }
            };
            
            // Initial visibility
            toggleVisibility(setting.get());
            
            // Update on change
            setting.bind(function(value) {
                toggleVisibility(value);
            });
        });
        
        // Quick view dependencies
        wp.customize('quick_view', function(setting) {
            var toggleVisibility = function(isVisible) {
                var $control = $('#customize-control-quick_view_gallery');
                
                if (isVisible) {
                    $control.slideDown();
                } else {
                    $control.slideUp();
                }
            };
            
            // Initial visibility
            toggleVisibility(setting.get());
            
            // Update on change
            setting.bind(function(value) {
                toggleVisibility(value);
            });
        });
    }

    /**
     * Initialize Custom Controls
     */
    function initCustomControls() {
        // Font selector
        $('.aqualuxe-font-selector').on('change', function() {
            var $select = $(this);
            var $preview = $select.next('.font-preview');
            var font = $select.val();
            
            // Update preview
            switch (font) {
                case 'sans':
                    $preview.css('font-family', '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif');
                    break;
                case 'serif':
                    $preview.css('font-family', 'Georgia, "Times New Roman", serif');
                    break;
                case 'mono':
                    $preview.css('font-family', 'Monaco, Consolas, "Andale Mono", "DejaVu Sans Mono", monospace');
                    break;
            }
        }).trigger('change');
        
        // Image radio buttons
        $('.aqualuxe-image-radio').on('change', function() {
            var $radio = $(this);
            var $label = $radio.closest('label');
            var $siblings = $label.siblings('label');
            
            // Update active state
            $siblings.removeClass('active');
            $label.addClass('active');
        });
        
        // Sortable sections
        $('.aqualuxe-sortable').sortable({
            update: function(event, ui) {
                // Get sorted items
                var items = [];
                
                $(this).find('li').each(function() {
                    items.push($(this).data('value'));
                });
                
                // Update hidden input
                $(this).next('input').val(items.join(',')).trigger('change');
            }
        });
        
        // Toggle switches
        $('.aqualuxe-toggle-switch').on('change', function() {
            var $checkbox = $(this);
            var $toggle = $checkbox.next('.toggle');
            
            // Update toggle state
            if ($checkbox.is(':checked')) {
                $toggle.addClass('active');
            } else {
                $toggle.removeClass('active');
            }
        }).trigger('change');
        
        // Responsive controls
        $('.responsive-tabs a').on('click', function(e) {
            e.preventDefault();
            
            var $tab = $(this);
            var $tabs = $tab.closest('.responsive-tabs');
            var $controls = $tabs.next('.responsive-controls');
            var device = $tab.data('device');
            
            // Update active tab
            $tabs.find('a').removeClass('active');
            $tab.addClass('active');
            
            // Show corresponding controls
            $controls.find('.control-responsive').hide();
            $controls.find('.control-' + device).show();
        });
        
        // Initialize first tab
        $('.responsive-tabs a:first').trigger('click');
    }

})(jQuery);