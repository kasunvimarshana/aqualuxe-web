/**
 * Admin JavaScript for AquaLuxe theme
 *
 * This file handles all the interactive functionality in the WordPress admin area.
 */

(function($) {
    'use strict';

    // Document Ready
    $(document).ready(function() {
        // Initialize theme admin components
        initializeThemeAdmin();
    });

    /**
     * Initialize all theme admin components
     */
    function initializeThemeAdmin() {
        // Initialize media uploader
        initializeMediaUploader();
        
        // Initialize color pickers
        initializeColorPickers();
        
        // Initialize metaboxes
        initializeMetaboxes();
        
        // Initialize admin notices
        initializeAdminNotices();
    }

    /**
     * Initialize WordPress media uploader
     */
    function initializeMediaUploader() {
        // Image upload fields
        $('.aqualuxe-upload-image').each(function() {
            var $button = $(this);
            var $field = $button.siblings('.aqualuxe-upload-field');
            var $preview = $button.siblings('.aqualuxe-upload-preview');
            var $remove = $button.siblings('.aqualuxe-remove-image');
            
            // Create media uploader
            $button.on('click', function(e) {
                e.preventDefault();
                
                var mediaUploader = wp.media({
                    title: 'Select or Upload Image',
                    button: {
                        text: 'Use this image'
                    },
                    multiple: false
                });
                
                // When image selected
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    $field.val(attachment.id);
                    
                    if ($preview.length) {
                        if (attachment.sizes && attachment.sizes.thumbnail) {
                            $preview.html('<img src="' + attachment.sizes.thumbnail.url + '" alt="">');
                        } else {
                            $preview.html('<img src="' + attachment.url + '" alt="">');
                        }
                        $preview.show();
                    }
                    
                    if ($remove.length) {
                        $remove.show();
                    }
                });
                
                mediaUploader.open();
            });
            
            // Remove image
            if ($remove.length) {
                $remove.on('click', function(e) {
                    e.preventDefault();
                    $field.val('');
                    $preview.hide().html('');
                    $remove.hide();
                });
                
                // Show/hide remove button based on field value
                if ($field.val() === '') {
                    $remove.hide();
                } else {
                    $remove.show();
                }
            }
        });
    }

    /**
     * Initialize color pickers
     */
    function initializeColorPickers() {
        if ($.fn.wpColorPicker) {
            $('.aqualuxe-color-picker').wpColorPicker();
        }
    }

    /**
     * Initialize metaboxes
     */
    function initializeMetaboxes() {
        // Toggle metabox visibility based on page template
        var $pageTemplate = $('#page_template');
        
        if ($pageTemplate.length) {
            function toggleMetaboxes() {
                var template = $pageTemplate.val();
                
                // Hide all template-specific metaboxes
                $('.aqualuxe-template-metabox').hide();
                
                // Show metaboxes for current template
                $('.aqualuxe-template-metabox[data-template="' + template + '"]').show();
                
                // Show metaboxes for all templates
                $('.aqualuxe-template-metabox[data-template="all"]').show();
            }
            
            // Initial toggle
            toggleMetaboxes();
            
            // Toggle on template change
            $pageTemplate.on('change', toggleMetaboxes);
        }
        
        // Tabs in metaboxes
        $('.aqualuxe-tabs').each(function() {
            var $tabs = $(this);
            var $tabLinks = $tabs.find('.aqualuxe-tab-link');
            var $tabContents = $tabs.find('.aqualuxe-tab-content');
            
            $tabLinks.on('click', function(e) {
                e.preventDefault();
                
                var tabId = $(this).attr('href');
                
                // Update active state
                $tabLinks.removeClass('active');
                $(this).addClass('active');
                
                // Show selected tab content
                $tabContents.hide();
                $(tabId).show();
            });
            
            // Show first tab by default
            $tabLinks.first().trigger('click');
        });
    }

    /**
     * Initialize admin notices
     */
    function initializeAdminNotices() {
        // Make notices dismissible
        $('.aqualuxe-admin-notice.is-dismissible').each(function() {
            var $notice = $(this);
            var $dismissButton = $('<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>');
            
            $notice.append($dismissButton);
            
            $dismissButton.on('click', function() {
                $notice.fadeTo(100, 0, function() {
                    $notice.slideUp(100, function() {
                        $notice.remove();
                    });
                });
                
                // Send AJAX request to remember dismissal
                if ($notice.data('notice')) {
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_dismiss_admin_notice',
                            notice: $notice.data('notice'),
                            nonce: aqualuxeAdminData.dismissNoticeNonce
                        }
                    });
                }
            });
        });
    }

})(jQuery);