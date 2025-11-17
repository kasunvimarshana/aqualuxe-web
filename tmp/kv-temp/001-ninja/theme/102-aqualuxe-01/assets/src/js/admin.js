/**
 * Admin JavaScript
 * 
 * @package AquaLuxe
 */

(function($) {
    'use strict';

    class AdminHandler {
        constructor() {
            this.init();
        }

        init() {
            this.setupColorPickers();
            this.setupMediaUploaders();
            this.setupTabs();
        }

        setupColorPickers() {
            if ($.fn.wpColorPicker) {
                $('.color-picker').wpColorPicker();
            }
        }

        setupMediaUploaders() {
            $('.media-upload-btn').on('click', function(e) {
                e.preventDefault();
                
                const button = $(this);
                const inputField = button.siblings('.media-url-input');
                const previewContainer = button.siblings('.media-preview');
                
                const frame = wp.media({
                    title: 'Select Media',
                    button: {
                        text: 'Use this media'
                    },
                    multiple: false
                });
                
                frame.on('select', function() {
                    const attachment = frame.state().get('selection').first().toJSON();
                    inputField.val(attachment.url);
                    
                    if (attachment.type === 'image') {
                        previewContainer.html('<img src="' + attachment.url + '" style="max-width: 200px; height: auto;">');
                    }
                });
                
                frame.open();
            });
        }

        setupTabs() {
            $('.nav-tab-wrapper .nav-tab').on('click', function(e) {
                e.preventDefault();
                
                const tab = $(this);
                const target = tab.attr('href');
                
                // Update active tab
                tab.siblings().removeClass('nav-tab-active');
                tab.addClass('nav-tab-active');
                
                // Show target content
                $('.tab-content').removeClass('active');
                $(target).addClass('active');
            });
        }
    }

    // Initialize when ready
    $(document).ready(function() {
        new AdminHandler();
    });

})(jQuery);