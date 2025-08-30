/**
 * Customizer Controls JavaScript file for the AquaLuxe theme
 * 
 * This file handles the customizer controls functionality
 */

(function($) {
    'use strict';

    // Document ready
    $(function() {
        // Initialize color pickers
        initColorPickers();
        
        // Initialize font selectors
        initFontSelectors();
        
        // Initialize range controls
        initRangeControls();
        
        // Initialize image selectors
        initImageSelectors();
        
        // Initialize dependency controls
        initDependencyControls();
    });

    /**
     * Initialize color pickers
     */
    function initColorPickers() {
        // Alpha color picker
        $('.aqualuxe-alpha-color-control').each(function() {
            const $control = $(this);
            const paletteInput = $control.data('palette');
            const showPalette = (paletteInput === true || paletteInput === 'true');
            
            $control.wpColorPicker({
                palettes: showPalette ? true : [],
                change: function(event, ui) {
                    const $input = $control.parents('.wp-picker-container').find('.wp-color-picker');
                    $input.trigger('change');
                },
                clear: function() {
                    const $input = $control.parents('.wp-picker-container').find('.wp-color-picker');
                    $input.trigger('change');
                }
            });
        });
    }

    /**
     * Initialize font selectors
     */
    function initFontSelectors() {
        $('.aqualuxe-font-family-select').on('change', function() {
            const $select = $(this);
            const $weightSelect = $select.parents('.customize-control').siblings('.customize-control-aqualuxe_font_weight').find('select');
            const fontFamily = $select.val();
            
            // Update font weights based on selected font
            if ($weightSelect.length) {
                const weights = aqualuxeCustomizer.fontWeights[fontFamily] || ['400', '700'];
                
                // Store current value
                const currentValue = $weightSelect.val();
                
                // Clear options
                $weightSelect.empty();
                
                // Add new options
                $.each(weights, function(index, weight) {
                    const weightName = aqualuxeCustomizer.fontWeightNames[weight] || weight;
                    $weightSelect.append($('<option></option>').attr('value', weight).text(weightName));
                });
                
                // Try to restore previous value or set default
                if (weights.includes(currentValue)) {
                    $weightSelect.val(currentValue);
                } else {
                    $weightSelect.val(weights[0]);
                }
                
                $weightSelect.trigger('change');
            }
            
            // Update font preview
            updateFontPreview($select);
        });
        
        // Initialize font previews
        $('.aqualuxe-font-family-select').each(function() {
            updateFontPreview($(this));
        });
    }

    /**
     * Update font preview
     * 
     * @param {jQuery} $select Font family select element
     */
    function updateFontPreview($select) {
        const fontFamily = $select.val();
        const $preview = $select.siblings('.aqualuxe-font-preview');
        
        if ($preview.length) {
            // Load font if needed
            if (aqualuxeCustomizer.googleFonts.includes(fontFamily)) {
                const fontWeight = $select.parents('.customize-control').siblings('.customize-control-aqualuxe_font_weight').find('select').val() || '400';
                const fontStyle = $select.parents('.customize-control').siblings('.customize-control-aqualuxe_font_style').find('select').val() || 'normal';
                
                // Check if font is already loaded
                const fontId = fontFamily.replace(/\s+/g, '+') + ':' + fontWeight + fontStyle;
                if (!$('link#' + fontId).length) {
                    $('head').append('<link id="' + fontId + '" href="https://fonts.googleapis.com/css?family=' + fontFamily.replace(/\s+/g, '+') + ':' + fontWeight + fontStyle + '" rel="stylesheet">');
                }
            }
            
            // Update preview style
            $preview.css('font-family', fontFamily);
        }
    }

    /**
     * Initialize range controls
     */
    function initRangeControls() {
        $('.aqualuxe-range-control').each(function() {
            const $range = $(this);
            const $input = $range.siblings('input[type="number"]');
            const $value = $range.siblings('.aqualuxe-range-value');
            
            // Update value display and input when range changes
            $range.on('input', function() {
                const value = $range.val();
                $input.val(value);
                $value.text(value);
            });
            
            // Update range when input changes
            $input.on('change', function() {
                const value = $input.val();
                $range.val(value);
                $value.text(value);
            });
        });
    }

    /**
     * Initialize image selectors
     */
    function initImageSelectors() {
        $('.aqualuxe-image-select').on('click', '.aqualuxe-image-select-item', function() {
            const $item = $(this);
            const $select = $item.parents('.aqualuxe-image-select').siblings('select');
            
            // Update select value
            $select.val($item.data('value')).trigger('change');
            
            // Update active state
            $item.siblings().removeClass('active');
            $item.addClass('active');
        });
        
        // Initialize active state
        $('.aqualuxe-image-select').each(function() {
            const $container = $(this);
            const $select = $container.siblings('select');
            const value = $select.val();
            
            $container.find('.aqualuxe-image-select-item[data-value="' + value + '"]').addClass('active');
        });
    }

    /**
     * Initialize dependency controls
     */
    function initDependencyControls() {
        // Process all controls with dependencies
        $('.customize-control[data-dependency]').each(function() {
            const $control = $(this);
            const dependency = JSON.parse($control.attr('data-dependency'));
            
            // Find the control we depend on
            const $dependencyControl = $('#customize-control-' + dependency.id);
            
            if ($dependencyControl.length) {
                const $dependencyInput = $dependencyControl.find('input[type="checkbox"], select, input[type="radio"]:checked');
                
                // Initial visibility
                updateDependencyVisibility($control, $dependencyInput, dependency);
                
                // Listen for changes
                $dependencyControl.on('change', 'input, select', function() {
                    const $changedInput = $(this);
                    updateDependencyVisibility($control, $changedInput, dependency);
                });
            }
        });
    }

    /**
     * Update dependency visibility
     * 
     * @param {jQuery} $control Control element
     * @param {jQuery} $dependencyInput Dependency input element
     * @param {Object} dependency Dependency configuration
     */
    function updateDependencyVisibility($control, $dependencyInput, dependency) {
        let isVisible = false;
        
        // Check if condition is met
        if ($dependencyInput.is('input[type="checkbox"]')) {
            // Checkbox
            isVisible = ($dependencyInput.is(':checked') === dependency.value);
        } else if ($dependencyInput.is('select')) {
            // Select
            isVisible = ($dependencyInput.val() === dependency.value);
        } else if ($dependencyInput.is('input[type="radio"]')) {
            // Radio
            isVisible = ($dependencyInput.val() === dependency.value);
        }
        
        // Invert if needed
        if (dependency.invert) {
            isVisible = !isVisible;
        }
        
        // Update visibility
        if (isVisible) {
            $control.show();
        } else {
            $control.hide();
        }
    }

})(jQuery);