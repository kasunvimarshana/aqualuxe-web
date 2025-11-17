/**
 * AquaLuxe Dark Mode Module Admin Scripts
 *
 * @package AquaLuxe
 * @subpackage Modules/Dark_Mode
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    /**
     * Dark Mode Admin
     */
    var DarkModeAdmin = {
        /**
         * Initialize
         */
        init: function() {
            this.initColorPickers();
            this.bindEvents();
            this.initPreview();
        },
        
        /**
         * Initialize color pickers
         */
        initColorPickers: function() {
            // Initialize color pickers
            $('.aqualuxe-color-picker').wpColorPicker({
                change: function(event, ui) {
                    // Update preview when color changes
                    DarkModeAdmin.updatePreview();
                }
            });
        },
        
        /**
         * Bind events
         */
        bindEvents: function() {
            // Toggle style change
            $('#aqualuxe_dark_mode_toggle_style').on('change', this.handleToggleStyleChange);
            
            // Default mode change
            $('#aqualuxe_dark_mode_default_mode').on('change', this.handleDefaultModeChange);
            
            // Auto detect change
            $('input[name="aqualuxe_dark_mode_auto_detect"]').on('change', this.handleAutoDetectChange);
            
            // Save preference change
            $('input[name="aqualuxe_dark_mode_save_preference"]').on('change', this.handleSavePreferenceChange);
            
            // Location toggles
            $('input[name="aqualuxe_dark_mode_show_in_header"], input[name="aqualuxe_dark_mode_show_in_footer"], input[name="aqualuxe_dark_mode_show_in_mobile"]').on('change', this.updatePreview);
            
            // Preview toggle
            $('.aqualuxe-dark-mode-preview-toggle').on('click', this.togglePreview);
        },
        
        /**
         * Initialize preview
         */
        initPreview: function() {
            // Create preview if it doesn't exist
            if (!$('.aqualuxe-dark-mode-preview').length) {
                this.createPreview();
            }
            
            // Create toggle preview if it doesn't exist
            if (!$('.aqualuxe-dark-mode-toggle-preview').length) {
                this.createTogglePreview();
            }
            
            // Update preview
            this.updatePreview();
        },
        
        /**
         * Create preview
         */
        createPreview: function() {
            // Get colors section
            var $colorsSection = $('#aqualuxe_dark_mode_colors').closest('.aqualuxe-dark-mode-settings-section');
            
            // Create preview
            var $preview = $('<div class="aqualuxe-dark-mode-preview">' +
                '<h4>' + aqualuxeDarkModeAdmin.previewTitle + '</h4>' +
                '<div class="aqualuxe-dark-mode-preview-item">' +
                    '<span class="aqualuxe-dark-mode-preview-label">' + aqualuxeDarkModeAdmin.textLabel + '</span>' +
                    '<p>' + aqualuxeDarkModeAdmin.previewText + '</p>' +
                '</div>' +
                '<div class="aqualuxe-dark-mode-preview-item">' +
                    '<span class="aqualuxe-dark-mode-preview-label">' + aqualuxeDarkModeAdmin.linkLabel + '</span>' +
                    '<p><a href="#">' + aqualuxeDarkModeAdmin.previewLink + '</a></p>' +
                '</div>' +
                '<div class="aqualuxe-dark-mode-preview-item">' +
                    '<span class="aqualuxe-dark-mode-preview-label">' + aqualuxeDarkModeAdmin.borderLabel + '</span>' +
                    '<hr>' +
                '</div>' +
                '<button type="button" class="button aqualuxe-dark-mode-preview-toggle">' + aqualuxeDarkModeAdmin.togglePreview + '</button>' +
            '</div>');
            
            // Append preview to colors section
            $colorsSection.append($preview);
        },
        
        /**
         * Create toggle preview
         */
        createTogglePreview: function() {
            // Get appearance section
            var $appearanceSection = $('#aqualuxe_dark_mode_appearance').closest('.aqualuxe-dark-mode-settings-section');
            
            // Create toggle preview
            var $preview = $('<div class="aqualuxe-dark-mode-toggle-preview">' +
                '<h4>' + aqualuxeDarkModeAdmin.togglePreviewTitle + '</h4>' +
                '<div class="aqualuxe-dark-mode-toggle-preview-item">' +
                    '<span class="aqualuxe-dark-mode-toggle-preview-label">' + aqualuxeDarkModeAdmin.switchLabel + '</span>' +
                    '<div class="aqualuxe-dark-mode-toggle aqualuxe-dark-mode-toggle--switch aqualuxe-dark-mode-toggle--preview">' +
                        '<label class="aqualuxe-dark-mode-toggle__label">' +
                            '<input type="checkbox" class="aqualuxe-dark-mode-toggle__checkbox">' +
                            '<span class="aqualuxe-dark-mode-toggle__switch"></span>' +
                            '<span class="aqualuxe-dark-mode-toggle__text">' + aqualuxeDarkModeAdmin.darkModeLabel + '</span>' +
                        '</label>' +
                    '</div>' +
                '</div>' +
                '<div class="aqualuxe-dark-mode-toggle-preview-item">' +
                    '<span class="aqualuxe-dark-mode-toggle-preview-label">' + aqualuxeDarkModeAdmin.iconLabel + '</span>' +
                    '<div class="aqualuxe-dark-mode-toggle aqualuxe-dark-mode-toggle--icon aqualuxe-dark-mode-toggle--preview">' +
                        '<button type="button" class="aqualuxe-dark-mode-toggle__button" aria-label="' + aqualuxeDarkModeAdmin.toggleLabel + '">' +
                            '<span class="aqualuxe-dark-mode-toggle__icon aqualuxe-dark-mode-toggle__icon--light">' +
                                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 18a6 6 0 1 1 0-12 6 6 0 0 1 0 12zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM11 1h2v3h-2V1zm0 19h2v3h-2v-3zM3.515 4.929l1.414-1.414L7.05 5.636 5.636 7.05 3.515 4.93zM16.95 18.364l1.414-1.414 2.121 2.121-1.414 1.414-2.121-2.121zm2.121-14.85l1.414 1.415-2.121 2.121-1.414-1.414 2.121-2.121zM5.636 16.95l1.414 1.414-2.121 2.121-1.414-1.414 2.121-2.121zM23 11v2h-3v-2h3zM4 11v2H1v-2h3z"/></svg>' +
                            '</span>' +
                            '<span class="aqualuxe-dark-mode-toggle__icon aqualuxe-dark-mode-toggle__icon--dark">' +
                                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M10 7a7 7 0 0 0 12 4.9v.1c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2h.1A6.979 6.979 0 0 0 10 7zm-6 5a8 8 0 0 0 15.062 3.762A9 9 0 0 1 8.238 4.938 7.999 7.999 0 0 0 4 12z"/></svg>' +
                            '</span>' +
                        '</button>' +
                    '</div>' +
                '</div>' +
                '<div class="aqualuxe-dark-mode-toggle-preview-item">' +
                    '<span class="aqualuxe-dark-mode-toggle-preview-label">' + aqualuxeDarkModeAdmin.buttonLabel + '</span>' +
                    '<div class="aqualuxe-dark-mode-toggle aqualuxe-dark-mode-toggle--button aqualuxe-dark-mode-toggle--preview">' +
                        '<button type="button" class="aqualuxe-dark-mode-toggle__button">' +
                            '<span class="aqualuxe-dark-mode-toggle__icon aqualuxe-dark-mode-toggle__icon--light">' +
                                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 18a6 6 0 1 1 0-12 6 6 0 0 1 0 12zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM11 1h2v3h-2V1zm0 19h2v3h-2v-3zM3.515 4.929l1.414-1.414L7.05 5.636 5.636 7.05 3.515 4.93zM16.95 18.364l1.414-1.414 2.121 2.121-1.414 1.414-2.121-2.121zm2.121-14.85l1.414 1.415-2.121 2.121-1.414-1.414 2.121-2.121zM5.636 16.95l1.414 1.414-2.121 2.121-1.414-1.414 2.121-2.121zM23 11v2h-3v-2h3zM4 11v2H1v-2h3z"/></svg>' +
                            '</span>' +
                            '<span class="aqualuxe-dark-mode-toggle__icon aqualuxe-dark-mode-toggle__icon--dark">' +
                                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M10 7a7 7 0 0 0 12 4.9v.1c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2h.1A6.979 6.979 0 0 0 10 7zm-6 5a8 8 0 0 0 15.062 3.762A9 9 0 0 1 8.238 4.938 7.999 7.999 0 0 0 4 12z"/></svg>' +
                            '</span>' +
                            '<span class="aqualuxe-dark-mode-toggle__text aqualuxe-dark-mode-toggle__text--light">' + aqualuxeDarkModeAdmin.lightLabel + '</span>' +
                            '<span class="aqualuxe-dark-mode-toggle__text aqualuxe-dark-mode-toggle__text--dark">' + aqualuxeDarkModeAdmin.darkLabel + '</span>' +
                        '</button>' +
                    '</div>' +
                '</div>' +
            '</div>');
            
            // Append toggle preview to appearance section
            $appearanceSection.append($preview);
        },
        
        /**
         * Update preview
         */
        updatePreview: function() {
            // Get colors
            var backgroundColor = $('#aqualuxe_dark_mode_background_color').val() || '#121212';
            var textColor = $('#aqualuxe_dark_mode_text_color').val() || '#ffffff';
            var linkColor = $('#aqualuxe_dark_mode_link_color').val() || '#4dabf7';
            var borderColor = $('#aqualuxe_dark_mode_border_color').val() || '#333333';
            
            // Update preview styles
            $('.aqualuxe-dark-mode-preview').css({
                'background-color': backgroundColor,
                'color': textColor
            });
            
            $('.aqualuxe-dark-mode-preview a').css('color', linkColor);
            $('.aqualuxe-dark-mode-preview hr').css('border-color', borderColor);
            
            // Update toggle preview
            var toggleStyle = $('#aqualuxe_dark_mode_toggle_style').val() || 'switch';
            
            // Show all toggle previews
            $('.aqualuxe-dark-mode-toggle-preview-item').show();
            
            // Highlight selected toggle style
            $('.aqualuxe-dark-mode-toggle-preview-item').removeClass('active');
            $('.aqualuxe-dark-mode-toggle-preview-item').eq(toggleStyle === 'switch' ? 0 : toggleStyle === 'icon' ? 1 : 2).addClass('active');
        },
        
        /**
         * Toggle preview
         */
        togglePreview: function() {
            var $preview = $('.aqualuxe-dark-mode-preview');
            
            // Toggle between light and dark mode
            if ($preview.hasClass('light-mode')) {
                $preview.removeClass('light-mode');
                $(this).text(aqualuxeDarkModeAdmin.togglePreview);
            } else {
                $preview.addClass('light-mode');
                $(this).text(aqualuxeDarkModeAdmin.togglePreviewDark);
            }
        },
        
        /**
         * Handle toggle style change
         */
        handleToggleStyleChange: function() {
            // Update preview
            DarkModeAdmin.updatePreview();
        },
        
        /**
         * Handle default mode change
         */
        handleDefaultModeChange: function() {
            var defaultMode = $(this).val();
            
            // If default mode is dark, disable auto detect
            if (defaultMode === 'dark') {
                $('#aqualuxe_dark_mode_auto_detect').prop('checked', false);
            }
            
            // Update preview
            DarkModeAdmin.updatePreview();
        },
        
        /**
         * Handle auto detect change
         */
        handleAutoDetectChange: function() {
            var autoDetect = $(this).is(':checked');
            
            // If auto detect is enabled, set default mode to light
            if (autoDetect) {
                $('#aqualuxe_dark_mode_default_mode').val('light');
            }
            
            // Update preview
            DarkModeAdmin.updatePreview();
        },
        
        /**
         * Handle save preference change
         */
        handleSavePreferenceChange: function() {
            var savePreference = $(this).is(':checked');
            
            // If save preference is disabled, disable cookie duration
            $('#aqualuxe_dark_mode_cookie_duration').prop('disabled', !savePreference);
            
            // Update preview
            DarkModeAdmin.updatePreview();
        }
    };
    
    /**
     * Document ready
     */
    $(document).ready(function() {
        DarkModeAdmin.init();
    });
    
})(jQuery);