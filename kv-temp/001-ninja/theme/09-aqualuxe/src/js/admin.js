/**
 * Admin JavaScript for AquaLuxe theme
 */

(function($) {
    'use strict';

    // Document Ready
    $(document).ready(function() {
        // Theme Info Page Tabs
        $('.aqualuxe-theme-tabs .nav-tab').on('click', function(e) {
            e.preventDefault();
            var target = $(this).attr('href');
            
            // Update tabs
            $('.aqualuxe-theme-tabs .nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            
            // Update content
            $('.aqualuxe-tab-content').removeClass('active');
            $(target).addClass('active');
        });

        // Service Icon Selector
        if ($('#service_icon').length) {
            // Initialize icon picker if available
            if (typeof $.fn.iconpicker !== 'undefined') {
                $('#service_icon').iconpicker({
                    placement: 'bottom',
                    hideOnSelect: true,
                    templates: {
                        search: '<input type="search" class="form-control iconpicker-search" placeholder="Search icons" />'
                    }
                });
            } else {
                // Simple icon preview
                var iconPreview = $('<div class="icon-preview"><i></i></div>');
                $('#service_icon').after(iconPreview);
                
                $('#service_icon').on('input', function() {
                    var iconClass = $(this).val();
                    iconPreview.find('i').attr('class', iconClass);
                }).trigger('input');
            }
        }

        // Date Picker for Event Meta Box
        if ($('#event_start_date, #event_end_date').length) {
            if (typeof $.fn.datepicker !== 'undefined') {
                $('#event_start_date, #event_end_date').datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true
                });
            }
        }

        // Media Uploader for Custom Meta Boxes
        var mediaUploader;
        
        $('.aqualuxe-upload-button').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var inputField = button.prev('.aqualuxe-upload-field');
            var previewContainer = button.next('.aqualuxe-image-preview');
            
            // If the media uploader already exists, open it
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            // Create the media uploader
            mediaUploader = wp.media({
                title: 'Select Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false
            });
            
            // When an image is selected, run a callback
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                inputField.val(attachment.url);
                
                // Update preview
                if (previewContainer.length) {
                    if (previewContainer.find('img').length) {
                        previewContainer.find('img').attr('src', attachment.url);
                    } else {
                        previewContainer.html('<img src="' + attachment.url + '" style="max-width: 100%; height: auto;" />');
                    }
                    previewContainer.show();
                }
            });
            
            // Open the uploader dialog
            mediaUploader.open();
        });
        
        // Remove image
        $('.aqualuxe-remove-image').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var inputField = button.prevAll('.aqualuxe-upload-field');
            var previewContainer = button.prev('.aqualuxe-image-preview');
            
            inputField.val('');
            previewContainer.hide().html('');
        });

        // Color Picker
        if (typeof $.fn.wpColorPicker !== 'undefined') {
            $('.aqualuxe-color-picker').wpColorPicker();
        }

        // Sortable Meta Boxes
        if (typeof $.fn.sortable !== 'undefined') {
            $('.aqualuxe-sortable').sortable({
                handle: '.aqualuxe-sortable-handle',
                update: function(event, ui) {
                    // Update order in hidden field
                    var order = [];
                    $(this).find('.aqualuxe-sortable-item').each(function() {
                        order.push($(this).data('id'));
                    });
                    $('#aqualuxe-sortable-order').val(order.join(','));
                }
            });
        }

        // Repeater Fields
        $('.aqualuxe-repeater-add').on('click', function(e) {
            e.preventDefault();
            
            var repeater = $(this).prev('.aqualuxe-repeater-container');
            var template = repeater.data('template');
            var count = repeater.data('count') || 0;
            
            // Increment count
            count++;
            repeater.data('count', count);
            
            // Replace placeholder with count
            var newRow = template.replace(/\{id\}/g, count);
            
            // Append new row
            repeater.append(newRow);
            
            // Initialize color picker in new row if needed
            repeater.find('.aqualuxe-color-picker').wpColorPicker();
        });
        
        // Remove repeater row
        $(document).on('click', '.aqualuxe-repeater-remove', function(e) {
            e.preventDefault();
            $(this).closest('.aqualuxe-repeater-row').remove();
        });

        // Toggle Meta Box Fields
        $('.aqualuxe-toggle-field').on('change', function() {
            var target = $(this).data('target');
            
            if ($(this).is(':checked')) {
                $('.' + target).show();
            } else {
                $('.' + target).hide();
            }
        }).trigger('change');

        // Dashboard Widget
        if ($('.aqualuxe-dashboard-widget').length) {
            // Check for theme updates
            if (typeof aqualuxe_admin_params !== 'undefined' && aqualuxe_admin_params.check_updates) {
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_check_theme_updates',
                        security: aqualuxe_admin_params.update_nonce
                    },
                    success: function(response) {
                        if (response.success && response.data.has_update) {
                            $('.aqualuxe-dashboard-widget').prepend(
                                '<div class="notice notice-warning inline"><p>' + 
                                response.data.message + 
                                '</p></div>'
                            );
                        }
                    }
                });
            }
        }
    });

})(jQuery);