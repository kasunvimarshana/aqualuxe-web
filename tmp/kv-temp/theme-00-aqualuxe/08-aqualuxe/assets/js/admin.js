/**
 * Admin JavaScript for AquaLuxe Theme
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

(function($) {
    'use strict';

    var AquaLuxeAdmin = {
        
        /**
         * Initialize admin functions
         */
        init: function() {
            this.tabs();
            this.colorPicker();
            this.toggleSwitches();
            this.formValidation();
            this.ajaxForms();
            this.mediaUploader();
        },

        /**
         * Tab functionality
         */
        tabs: function() {
            $('.aqualuxe-admin-tabs a').on('click', function(e) {
                e.preventDefault();
                
                var $tab = $(this);
                var target = $tab.attr('href');
                
                // Update active states
                $('.aqualuxe-admin-tabs a').removeClass('active');
                $tab.addClass('active');
                
                // Show/hide content
                $('.aqualuxe-tab-content').removeClass('active');
                $(target).addClass('active');
                
                // Store active tab
                localStorage.setItem('aqualuxe_active_tab', target);
            });
            
            // Restore active tab
            var activeTab = localStorage.getItem('aqualuxe_active_tab');
            if (activeTab && $(activeTab).length) {
                $('.aqualuxe-admin-tabs a[href="' + activeTab + '"]').trigger('click');
            }
        },

        /**
         * Color picker initialization
         */
        colorPicker: function() {
            if (typeof $.fn.wpColorPicker !== 'undefined') {
                $('.aqualuxe-color-picker input[type="text"]').wpColorPicker({
                    change: function(event, ui) {
                        var color = ui.color.toString();
                        $(this).val(color).trigger('change');
                    },
                    clear: function() {
                        $(this).val('').trigger('change');
                    }
                });
            }
        },

        /**
         * Toggle switches
         */
        toggleSwitches: function() {
            $('.aqualuxe-toggle input').on('change', function() {
                var $toggle = $(this);
                var value = $toggle.is(':checked') ? 1 : 0;
                
                // Update hidden input if exists
                var $hidden = $toggle.siblings('input[type="hidden"]');
                if ($hidden.length) {
                    $hidden.val(value);
                }
                
                // Trigger custom event
                $toggle.trigger('aqualuxe:toggle', [value]);
            });
        },

        /**
         * Form validation
         */
        formValidation: function() {
            $('.aqualuxe-form').on('submit', function(e) {
                var $form = $(this);
                var isValid = true;
                
                // Clear previous errors
                $form.find('.error').removeClass('error');
                $form.find('.error-message').remove();
                
                // Validate required fields
                $form.find('[required]').each(function() {
                    var $field = $(this);
                    var value = $field.val().trim();
                    
                    if (!value) {
                        isValid = false;
                        $field.addClass('error');
                        $field.after('<span class="error-message">This field is required.</span>');
                    }
                });
                
                // Validate email fields
                $form.find('input[type="email"]').each(function() {
                    var $field = $(this);
                    var value = $field.val().trim();
                    
                    if (value && !AquaLuxeAdmin.isValidEmail(value)) {
                        isValid = false;
                        $field.addClass('error');
                        $field.after('<span class="error-message">Please enter a valid email address.</span>');
                    }
                });
                
                // Validate URL fields
                $form.find('input[type="url"]').each(function() {
                    var $field = $(this);
                    var value = $field.val().trim();
                    
                    if (value && !AquaLuxeAdmin.isValidUrl(value)) {
                        isValid = false;
                        $field.addClass('error');
                        $field.after('<span class="error-message">Please enter a valid URL.</span>');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    
                    // Scroll to first error
                    var $firstError = $form.find('.error').first();
                    if ($firstError.length) {
                        $('html, body').animate({
                            scrollTop: $firstError.offset().top - 100
                        }, 300);
                        $firstError.focus();
                    }
                }
            });
        },

        /**
         * AJAX form handling
         */
        ajaxForms: function() {
            $('.aqualuxe-ajax-form').on('submit', function(e) {
                e.preventDefault();
                
                var $form = $(this);
                var $submitBtn = $form.find('[type="submit"]');
                var originalText = $submitBtn.val() || $submitBtn.text();
                
                // Show loading state
                $form.addClass('aqualuxe-loading');
                $submitBtn.prop('disabled', true);
                
                if ($submitBtn.is('input')) {
                    $submitBtn.val('Saving...');
                } else {
                    $submitBtn.text('Saving...');
                }
                
                // Prepare form data
                var formData = new FormData($form[0]);
                formData.append('action', $form.data('action') || 'aqualuxe_save_options');
                formData.append('nonce', $form.find('[name="nonce"]').val());
                
                // Send AJAX request
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            AquaLuxeAdmin.showMessage('Settings saved successfully!', 'success');
                        } else {
                            AquaLuxeAdmin.showMessage(response.data || 'An error occurred.', 'error');
                        }
                    },
                    error: function() {
                        AquaLuxeAdmin.showMessage('An error occurred while saving.', 'error');
                    },
                    complete: function() {
                        // Reset form state
                        $form.removeClass('aqualuxe-loading');
                        $submitBtn.prop('disabled', false);
                        
                        if ($submitBtn.is('input')) {
                            $submitBtn.val(originalText);
                        } else {
                            $submitBtn.text(originalText);
                        }
                    }
                });
            });
        },

        /**
         * Media uploader
         */
        mediaUploader: function() {
            var mediaUploader;
            
            $('.aqualuxe-upload-btn').on('click', function(e) {
                e.preventDefault();
                
                var $button = $(this);
                var $input = $button.siblings('input[type="text"]');
                var $preview = $button.siblings('.aqualuxe-image-preview');
                
                // Create media uploader if it doesn't exist
                if (!mediaUploader) {
                    mediaUploader = wp.media({
                        title: 'Select Image',
                        button: {
                            text: 'Use This Image'
                        },
                        multiple: false
                    });
                }
                
                // Handle selection
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    
                    $input.val(attachment.url);
                    
                    if ($preview.length) {
                        $preview.html('<img src="' + attachment.url + '" alt="" style="max-width: 200px; height: auto;">');
                    }
                });
                
                // Open media uploader
                mediaUploader.open();
            });
            
            // Remove image
            $('.aqualuxe-remove-image').on('click', function(e) {
                e.preventDefault();
                
                var $button = $(this);
                var $input = $button.siblings('input[type="text"]');
                var $preview = $button.siblings('.aqualuxe-image-preview');
                
                $input.val('');
                $preview.empty();
            });
        },

        /**
         * Show admin message
         */
        showMessage: function(message, type) {
            type = type || 'info';
            
            var $message = $('<div class="aqualuxe-message aqualuxe-message-' + type + '">' + message + '</div>');
            
            // Remove existing messages
            $('.aqualuxe-message').remove();
            
            // Add new message
            $('.aqualuxe-admin-header').after($message);
            
            // Auto-hide success messages
            if (type === 'success') {
                setTimeout(function() {
                    $message.fadeOut(function() {
                        $(this).remove();
                    });
                }, 3000);
            }
            
            // Scroll to message
            $('html, body').animate({
                scrollTop: $message.offset().top - 100
            }, 300);
        },

        /**
         * Email validation
         */
        isValidEmail: function(email) {
            var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        },

        /**
         * URL validation
         */
        isValidUrl: function(url) {
            try {
                new URL(url);
                return true;
            } catch (e) {
                return false;
            }
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        AquaLuxeAdmin.init();
    });

    // Handle WordPress media uploader
    if (typeof wp !== 'undefined' && wp.media) {
        wp.media.controller.Library.prototype.defaults.contentUserSetting = false;
    }

})(jQuery);