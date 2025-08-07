/**
 * AquaLuxe Admin JavaScript
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    // Document ready
    $(document).ready(function() {
        // Initialize admin features
        AquaLuxeAdmin.init();
    });
    
    // AquaLuxeAdmin object
    window.AquaLuxeAdmin = {
        /**
         * Initialize admin features
         */
        init: function() {
            this.bindEvents();
            this.initColorPicker();
            this.initMediaUploader();
        },
        
        /**
         * Bind events
         */
        bindEvents: function() {
            // Demo content import
            $('#aqualuxe-import-demo').on('click', this.handleDemoImport);
            
            // Demo content reset
            $('#aqualuxe-reset-content').on('click', this.handleContentReset);
            
            // Color picker
            $('.aqualuxe-color-picker').on('change', this.handleColorChange);
            
            // Media uploader
            $('.aqualuxe-upload-button').on('click', this.handleMediaUpload);
        },
        
        /**
         * Initialize color picker
         */
        initColorPicker: function() {
            // Only run if wpColorPicker exists
            if ($.fn.wpColorPicker) {
                $('.aqualuxe-color-picker').wpColorPicker();
            }
        },
        
        /**
         * Initialize media uploader
         */
        initMediaUploader: function() {
            // Only run if wp.media exists
            if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
                $(document).on('click', '.aqualuxe-upload-button', function(e) {
                    e.preventDefault();
                    var button = $(this);
                    var input = button.siblings('input');
                    var mediaUploader;
                    
                    // If the uploader object has already been created, reopen the dialog
                    if (mediaUploader) {
                        mediaUploader.open();
                        return;
                    }
                    
                    // Extend the wp.media object
                    mediaUploader = wp.media.frames.file_frame = wp.media({
                        title: 'Choose Image',
                        button: {
                            text: 'Choose Image'
                        },
                        multiple: false
                    });
                    
                    // When a file is selected, grab the URL and set it as the text field's value
                    mediaUploader.on('select', function() {
                        var attachment = mediaUploader.state().get('selection').first().toJSON();
                        input.val(attachment.url);
                    });
                    
                    // Open the uploader dialog
                    mediaUploader.open();
                });
            }
        },
        
        /**
         * Handle demo content import
         */
        handleDemoImport: function() {
            var button = $(this);
            
            // Confirm import
            if (!confirm('Are you sure you want to import demo content? This will add sample products and pages to your site.')) {
                return;
            }
            
            // Add loading class
            button.addClass('updating-message').text('Importing...');
            
            // Send AJAX request
            $.post(ajaxurl, {
                action: 'aqualuxe_import_demo_content',
                nonce: aqualuxe_vars.nonce
            }, function(response) {
                // Remove loading class
                button.removeClass('updating-message').text('Import Demo Content');
                
                // Handle response
                if (response.success) {
                    alert('Demo content imported successfully!');
                    location.reload();
                } else {
                    alert('Error importing demo content: ' + response.data);
                }
            });
        },
        
        /**
         * Handle content reset
         */
        handleContentReset: function() {
            var button = $(this);
            
            // Confirm reset
            if (!confirm('Are you sure you want to reset all content? This cannot be undone.')) {
                return;
            }
            
            // Add loading class
            button.addClass('updating-message').text('Resetting...');
            
            // Send AJAX request
            $.post(ajaxurl, {
                action: 'aqualuxe_reset_content',
                nonce: aqualuxe_vars.nonce
            }, function(response) {
                // Remove loading class
                button.removeClass('updating-message').text('Reset Content');
                
                // Handle response
                if (response.success) {
                    alert('Content reset successfully!');
                    location.reload();
                } else {
                    alert('Error resetting content: ' + response.data);
                }
            });
        },
        
        /**
         * Handle color change
         */
        handleColorChange: function() {
            var input = $(this);
            var color = input.val();
            var preview = input.siblings('.color-preview');
            
            // Update preview
            preview.css('background-color', color);
        },
        
        /**
         * Handle media upload
         */
        handleMediaUpload: function(e) {
            e.preventDefault();
            var button = $(this);
            var input = button.siblings('input');
            var mediaUploader;
            
            // If the uploader object has already been created, reopen the dialog
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            // Extend the wp.media object
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Image',
                button: {
                    text: 'Choose Image'
                },
                multiple: false
            });
            
            // When a file is selected, grab the URL and set it as the text field's value
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                input.val(attachment.url);
            });
            
            // Open the uploader dialog
            mediaUploader.open();
        },
        
        /**
         * Show notice
         */
        showNotice: function(message, type) {
            // Create notice element
            var notice = $('<div class="notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>');
            
            // Add to page
            $('.wrap h1').after(notice);
            
            // Make dismissible
            notice.on('click', '.notice-dismiss', function() {
                notice.remove();
            });
        }
    };
    
    // Initialize color picker for new fields
    $(document).on('widget-added widget-updated', function(e, widget) {
        if (widget.find('.aqualuxe-color-picker').length) {
            widget.find('.aqualuxe-color-picker').wpColorPicker();
        }
    });
    
})(jQuery);