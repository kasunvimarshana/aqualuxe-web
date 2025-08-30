/**
 * AquaLuxe Admin Scripts
 */

(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        // Initialize custom controls
        initCustomControls();
        
        // Initialize media uploader
        initMediaUploader();
        
        // Initialize sortable controls
        initSortableControls();
        
        // Initialize responsive controls
        initResponsiveControls();
        
        // Initialize range controls with previews
        initRangeControls();
        
        // Initialize color scheme controls
        initColorSchemeControls();
    });

    /**
     * Initialize custom controls
     */
    function initCustomControls() {
        // Toggle controls
        $('.customize-control-toggle input[type="checkbox"]').on('change', function() {
            var controlValue = $(this).is(':checked') ? true : false;
            $(this).parent().find('input[type="hidden"]').val(controlValue).trigger('change');
        });

        // Dimension controls
        $('.customize-control-dimensions input').on('change', function() {
            var dimensionValues = {};
            var wrapper = $(this).closest('.customize-control-dimensions');
            
            wrapper.find('input[type="number"]').each(function() {
                var dimension = $(this).data('dimension');
                dimensionValues[dimension] = $(this).val();
            });
            
            wrapper.find('input[type="hidden"]').val(JSON.stringify(dimensionValues)).trigger('change');
        });

        // Typography controls
        $('.customize-control-typography select, .customize-control-typography input').on('change', function() {
            var typographyValues = {};
            var wrapper = $(this).closest('.customize-control-typography');
            
            wrapper.find('select, input[type="number"]').each(function() {
                var property = $(this).data('property');
                typographyValues[property] = $(this).val();
            });
            
            wrapper.find('input[type="hidden"]').val(JSON.stringify(typographyValues)).trigger('change');
        });

        // Radio image controls
        $('.customize-control-radio-image input[type="radio"]').on('change', function() {
            $(this).closest('.customize-control-radio-image').find('input[type="hidden"]').val($(this).val()).trigger('change');
        });
    }

    /**
     * Initialize media uploader
     */
    function initMediaUploader() {
        // Image upload controls
        $('.customize-control-image-upload .upload-button').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var wrapper = button.closest('.customize-control-image-upload');
            var hiddenInput = wrapper.find('input[type="hidden"]');
            var previewImg = wrapper.find('.preview-image');
            var removeButton = wrapper.find('.remove-button');
            
            var mediaUploader = wp.media({
                title: 'Select Image',
                button: {
                    text: 'Use This Image'
                },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                hiddenInput.val(attachment.url).trigger('change');
                previewImg.attr('src', attachment.url).show();
                removeButton.show();
                button.text('Change Image');
            });
            
            mediaUploader.open();
        });
        
        // Remove image button
        $('.customize-control-image-upload .remove-button').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var wrapper = button.closest('.customize-control-image-upload');
            var hiddenInput = wrapper.find('input[type="hidden"]');
            var previewImg = wrapper.find('.preview-image');
            var uploadButton = wrapper.find('.upload-button');
            
            hiddenInput.val('').trigger('change');
            previewImg.attr('src', '').hide();
            button.hide();
            uploadButton.text('Select Image');
        });
    }

    /**
     * Initialize sortable controls
     */
    function initSortableControls() {
        $('.customize-control-sortable ul').sortable({
            update: function(event, ui) {
                var sortedItems = [];
                
                $(this).find('li').each(function() {
                    sortedItems.push({
                        id: $(this).data('id'),
                        visible: $(this).find('.visibility').hasClass('dashicons-visibility')
                    });
                });
                
                $(this).closest('.customize-control-sortable').find('input[type="hidden"]').val(JSON.stringify(sortedItems)).trigger('change');
            }
        });
        
        // Toggle visibility
        $('.customize-control-sortable .visibility').on('click', function() {
            var icon = $(this);
            var list = icon.closest('ul');
            
            if (icon.hasClass('dashicons-visibility')) {
                icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
            } else {
                icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
            }
            
            // Trigger update
            list.sortable('option', 'update')();
        });
    }

    /**
     * Initialize responsive controls
     */
    function initResponsiveControls() {
        // Switch between responsive views
        $('.customize-control-responsive .responsive-switchers button').on('click', function() {
            var device = $(this).data('device');
            var control = $(this).closest('.customize-control-responsive');
            
            // Update active button
            control.find('.responsive-switchers button').removeClass('active');
            $(this).addClass('active');
            
            // Show active control
            control.find('.responsive-control-wrap').removeClass('active');
            control.find('.responsive-control-' + device).addClass('active');
        });
        
        // Update hidden input when any control changes
        $('.customize-control-responsive .responsive-control-wrap input, .customize-control-responsive .responsive-control-wrap select').on('change', function() {
            var control = $(this).closest('.customize-control-responsive');
            var responsiveValues = {};
            
            control.find('.responsive-control-wrap').each(function() {
                var device = $(this).data('device');
                var value = $(this).find('input, select').val();
                responsiveValues[device] = value;
            });
            
            control.find('input[type="hidden"]').val(JSON.stringify(responsiveValues)).trigger('change');
        });
    }

    /**
     * Initialize range controls with previews
     */
    function initRangeControls() {
        $('.customize-control-range input[type="range"]').on('input', function() {
            var value = $(this).val();
            var preview = $(this).next('.range-value');
            
            if (preview.length) {
                preview.text(value + $(this).data('unit'));
            }
        });
    }

    /**
     * Initialize color scheme controls
     */
    function initColorSchemeControls() {
        $('.customize-control-color-scheme .color-scheme-option').on('click', function() {
            var scheme = $(this).data('scheme');
            var control = $(this).closest('.customize-control-color-scheme');
            
            // Update active scheme
            control.find('.color-scheme-option').removeClass('active');
            $(this).addClass('active');
            
            // Update hidden input
            control.find('input[type="hidden"]').val(scheme).trigger('change');
            
            // Update color controls with scheme values
            var schemeColors = $(this).data('colors');
            
            if (schemeColors) {
                $.each(schemeColors, function(setting, color) {
                    var colorControl = $('#customize-control-' + setting);
                    
                    if (colorControl.length) {
                        colorControl.find('.wp-color-picker').wpColorPicker('color', color);
                    }
                });
            }
        });
    }

    /**
     * Add custom controls to the WordPress Customizer
     */
    $(window).on('load', function() {
        // Add custom section buttons
        $('.customize-section-description').each(function() {
            var description = $(this);
            var button = description.find('.section-button');
            
            if (button.length) {
                var buttonText = button.text();
                var buttonUrl = button.attr('href');
                var buttonClass = button.attr('class').replace('section-button', '');
                
                var customButton = $('<a class="button ' + buttonClass + '" href="' + buttonUrl + '" target="_blank">' + buttonText + '</a>');
                description.append(customButton);
                button.remove();
            }
        });
        
        // Add custom panel buttons
        $('.customize-panel-description').each(function() {
            var description = $(this);
            var button = description.find('.panel-button');
            
            if (button.length) {
                var buttonText = button.text();
                var buttonUrl = button.attr('href');
                var buttonClass = button.attr('class').replace('panel-button', '');
                
                var customButton = $('<a class="button ' + buttonClass + '" href="' + buttonUrl + '" target="_blank">' + buttonText + '</a>');
                description.append(customButton);
                button.remove();
            }
        });
    });

})(jQuery);