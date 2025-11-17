/**
 * Admin JavaScript
 * 
 * Handles admin-specific functionality
 */

(function($) {
    'use strict';

    const Admin = {
        
        init: function() {
            this.initModuleSettings();
            this.initColorPickers();
            this.initImageUploaders();
            this.initTabs();
            this.initTooltips();
        },

        initModuleSettings: function() {
            // Module toggle functionality is handled in PHP
            // This is for additional admin interactions
        },

        initColorPickers: function() {
            if ($.fn.wpColorPicker) {
                $('.color-picker').wpColorPicker();
            }
        },

        initImageUploaders: function() {
            $('.image-upload-button').on('click', function(e) {
                e.preventDefault();
                
                const button = $(this);
                const input = button.siblings('.image-upload-input');
                const preview = button.siblings('.image-preview');
                
                const mediaUploader = wp.media({
                    title: 'Select Image',
                    button: {
                        text: 'Use this image'
                    },
                    multiple: false
                });
                
                mediaUploader.on('select', function() {
                    const attachment = mediaUploader.state().get('selection').first().toJSON();
                    input.val(attachment.url);
                    preview.attr('src', attachment.url).show();
                });
                
                mediaUploader.open();
            });
            
            $('.image-remove-button').on('click', function(e) {
                e.preventDefault();
                
                const button = $(this);
                const input = button.siblings('.image-upload-input');
                const preview = button.siblings('.image-preview');
                
                input.val('');
                preview.hide();
            });
        },

        initTabs: function() {
            $('.nav-tab').on('click', function(e) {
                e.preventDefault();
                
                const target = $(this).attr('href');
                
                // Update active tab
                $('.nav-tab').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');
                
                // Show target content
                $('.tab-content').hide();
                $(target).show();
            });
        },

        initTooltips: function() {
            $('[data-tooltip]').each(function() {
                $(this).attr('title', $(this).data('tooltip'));
            });
        }
    };

    // Initialize when ready
    $(document).ready(function() {
        Admin.init();
    });

    // Make available globally
    window.AquaLuxeAdmin = Admin;

})(jQuery);