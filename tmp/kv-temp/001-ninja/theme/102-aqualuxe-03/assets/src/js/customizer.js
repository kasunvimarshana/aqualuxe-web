/**
 * Customizer JavaScript
 * 
 * Handles theme customizer functionality.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // Customizer object
    const Customizer = {
        
        /**
         * Initialize customizer functionality
         */
        init() {
            this.bindEvents();
            this.initColorPickers();
            this.initRangeSliders();
        },
        
        /**
         * Bind events
         */
        bindEvents() {
            // Live preview updates
            wp.customize('blogname', function(value) {
                value.bind(function(newval) {
                    $('.site-title').text(newval);
                });
            });
            
            wp.customize('blogdescription', function(value) {
                value.bind(function(newval) {
                    $('.site-description').text(newval);
                });
            });
            
            // Color scheme updates
            wp.customize('primary_color', function(value) {
                value.bind(function(newval) {
                    Customizer.updateCSSVariable('--color-primary', newval);
                });
            });
            
            wp.customize('secondary_color', function(value) {
                value.bind(function(newval) {
                    Customizer.updateCSSVariable('--color-secondary', newval);
                });
            });
        },
        
        /**
         * Initialize color pickers
         */
        initColorPickers() {
            $('.customize-control-color input').wpColorPicker({
                change: function(event, ui) {
                    $(this).trigger('change');
                }
            });
        },
        
        /**
         * Initialize range sliders
         */
        initRangeSliders() {
            $('.customize-control-range input[type="range"]').on('input', function() {
                const $input = $(this);
                const $output = $input.siblings('.range-value');
                $output.text($input.val());
            });
        },
        
        /**
         * Update CSS variable
         */
        updateCSSVariable(property, value) {
            document.documentElement.style.setProperty(property, value);
        }
    };
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        Customizer.init();
    });
    
    // Expose to global scope
    window.AquaLuxeCustomizer = Customizer;
    
})(jQuery);