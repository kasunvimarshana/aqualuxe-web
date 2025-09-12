/**
 * Admin JavaScript
 * 
 * @package AquaLuxe
 */

(function($) {
    'use strict';

    const AquaLuxeAdmin = {
        init: function() {
            this.bindEvents();
            this.initTabs();
            this.initColorPickers();
            this.initImageUploaders();
        },

        bindEvents: function() {
            $(document).on('click', '.aqualuxe-save-settings', this.saveSettings.bind(this));
            $(document).on('click', '.aqualuxe-reset-settings', this.resetSettings.bind(this));
        },

        initTabs: function() {
            $('.admin-tabs .tab').on('click', function(e) {
                e.preventDefault();
                
                const $tab = $(this);
                const target = $tab.data('tab');
                
                $('.admin-tabs .tab').removeClass('active');
                $('.admin-tab-content').removeClass('active');
                
                $tab.addClass('active');
                $('#' + target).addClass('active');
            });
        },

        initColorPickers: function() {
            if ($.fn.wpColorPicker) {
                $('.color-picker').wpColorPicker();
            }
        },

        initImageUploaders: function() {
            $('.image-upload-button').on('click', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const $input = $button.siblings('input[type="hidden"]');
                const $preview = $button.siblings('.image-preview');
                
                const frame = wp.media({
                    title: 'Select Image',
                    button: {
                        text: 'Use this image'
                    },
                    multiple: false
                });
                
                frame.on('select', function() {
                    const attachment = frame.state().get('selection').first().toJSON();
                    $input.val(attachment.id);
                    $preview.html('<img src="' + attachment.url + '" style="max-width: 200px;">');
                });
                
                frame.open();
            });
        },

        saveSettings: function(e) {
            e.preventDefault();
            
            const $button = $(e.currentTarget);
            const $form = $button.closest('form');
            const originalText = $button.text();
            
            $button.text(window.aqualuxe_admin.strings.saving).prop('disabled', true);
            
            $.ajax({
                url: window.aqualuxe_admin.ajaxurl,
                type: 'POST',
                data: $form.serialize() + '&action=aqualuxe_save_settings&nonce=' + window.aqualuxe_admin.nonce,
                success: function(response) {
                    if (response.success) {
                        $button.text(window.aqualuxe_admin.strings.saved);
                        setTimeout(function() {
                            $button.text(originalText).prop('disabled', false);
                        }, 2000);
                    } else {
                        alert(response.data.message || window.aqualuxe_admin.strings.error);
                        $button.text(originalText).prop('disabled', false);
                    }
                },
                error: function() {
                    alert(window.aqualuxe_admin.strings.error);
                    $button.text(originalText).prop('disabled', false);
                }
            });
        },

        resetSettings: function(e) {
            e.preventDefault();
            
            if (!confirm(window.aqualuxe_admin.strings.confirm_reset)) {
                return;
            }
            
            const $button = $(e.currentTarget);
            
            $.ajax({
                url: window.aqualuxe_admin.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_reset_settings',
                    nonce: window.aqualuxe_admin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data.message || window.aqualuxe_admin.strings.error);
                    }
                },
                error: function() {
                    alert(window.aqualuxe_admin.strings.error);
                }
            });
        }
    };

    $(document).ready(function() {
        AquaLuxeAdmin.init();
    });

})(jQuery);