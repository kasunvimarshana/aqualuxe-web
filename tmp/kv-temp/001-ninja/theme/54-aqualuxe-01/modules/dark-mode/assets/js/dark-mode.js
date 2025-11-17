/**
 * AquaLuxe Dark Mode Module Scripts
 */

(function($) {
    'use strict';

    /**
     * AquaLuxe Dark Mode Module
     */
    var AquaLuxeDarkModeModule = {
        /**
         * Initialize module
         */
        init: function() {
            this.initDarkMode();
            this.initToggles();
            this.initSystemPreference();
        },

        /**
         * Initialize dark mode
         */
        initDarkMode: function() {
            // Get current mode
            var currentMode = aqualuxeDarkMode.currentMode;
            
            // Apply mode
            if (currentMode === 'dark') {
                this.enableDarkMode();
            } else {
                this.disableDarkMode();
            }
        },

        /**
         * Initialize toggles
         */
        initToggles: function() {
            // Switch toggle
            $('.aqualuxe-dark-mode-checkbox').on('change', function() {
                if ($(this).is(':checked')) {
                    AquaLuxeDarkModeModule.enableDarkMode();
                } else {
                    AquaLuxeDarkModeModule.disableDarkMode();
                }
            });
            
            // Icon toggle
            $('.aqualuxe-dark-mode-icon').on('click', function(e) {
                e.preventDefault();
                
                if ($('body').hasClass('aqualuxe-dark-mode')) {
                    AquaLuxeDarkModeModule.disableDarkMode();
                } else {
                    AquaLuxeDarkModeModule.enableDarkMode();
                }
            });
            
            // Text toggle
            $('.aqualuxe-dark-mode-text').on('click', function(e) {
                e.preventDefault();
                
                if ($('body').hasClass('aqualuxe-dark-mode')) {
                    AquaLuxeDarkModeModule.disableDarkMode();
                } else {
                    AquaLuxeDarkModeModule.enableDarkMode();
                }
            });
            
            // Menu toggle
            $('.aqualuxe-dark-mode-menu-toggle').on('click', function(e) {
                e.preventDefault();
                
                if ($('body').hasClass('aqualuxe-dark-mode')) {
                    AquaLuxeDarkModeModule.disableDarkMode();
                } else {
                    AquaLuxeDarkModeModule.enableDarkMode();
                }
            });
        },

        /**
         * Initialize system preference
         */
        initSystemPreference: function() {
            // Check if auto detect is enabled
            if (!aqualuxeDarkMode.autoDetectSystem) {
                return;
            }
            
            // Check if user has a preference
            if (aqualuxeDarkMode.rememberPreference && this.getCookie('aqualuxe_dark_mode')) {
                return;
            }
            
            // Check if default mode is auto
            if (aqualuxeDarkMode.defaultMode !== 'auto') {
                return;
            }
            
            // Check for system preference
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                this.enableDarkMode();
            } else {
                this.disableDarkMode();
            }
            
            // Listen for changes
            if (window.matchMedia) {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                    if (e.matches) {
                        AquaLuxeDarkModeModule.enableDarkMode();
                    } else {
                        AquaLuxeDarkModeModule.disableDarkMode();
                    }
                });
            }
        },

        /**
         * Enable dark mode
         */
        enableDarkMode: function() {
            // Add class to body
            $('body').addClass('aqualuxe-dark-mode');
            
            // Update toggles
            $('.aqualuxe-dark-mode-checkbox').prop('checked', true);
            $('.aqualuxe-dark-mode-icon-light').addClass('active');
            $('.aqualuxe-dark-mode-icon-dark').removeClass('active');
            $('.aqualuxe-dark-mode-text-light').removeClass('active');
            $('.aqualuxe-dark-mode-text-dark').addClass('active');
            
            // Update menu toggle
            $('.aqualuxe-dark-mode-menu-toggle').html(
                '<span class="aqualuxe-dark-mode-menu-icon">' +
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 18a6 6 0 1 1 0-12 6 6 0 0 1 0 12zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM11 1h2v3h-2V1zm0 19h2v3h-2v-3zM3.515 4.929l1.414-1.414L7.05 5.636 5.636 7.05 3.515 4.93zM16.95 18.364l1.414-1.414 2.121 2.121-1.414 1.414-2.121-2.121zm2.121-14.85l1.414 1.415-2.121 2.121-1.414-1.414 2.121-2.121zM5.636 16.95l1.414 1.414-2.121 2.121-1.414-1.414 2.121-2.121zM23 11v2h-3v-2h3zM4 11v2H1v-2h3z"/></svg>' +
                '</span>' +
                '<span class="aqualuxe-dark-mode-menu-text">' + aqualuxeL10n.lightMode + '</span>'
            );
            
            // Set cookie
            if (aqualuxeDarkMode.rememberPreference) {
                this.setCookie('aqualuxe_dark_mode', 'dark', aqualuxeDarkMode.cookieExpiration);
            }
            
            // Trigger event
            $(document).trigger('aqualuxe_dark_mode_enabled');
        },

        /**
         * Disable dark mode
         */
        disableDarkMode: function() {
            // Remove class from body
            $('body').removeClass('aqualuxe-dark-mode');
            
            // Update toggles
            $('.aqualuxe-dark-mode-checkbox').prop('checked', false);
            $('.aqualuxe-dark-mode-icon-light').removeClass('active');
            $('.aqualuxe-dark-mode-icon-dark').addClass('active');
            $('.aqualuxe-dark-mode-text-light').addClass('active');
            $('.aqualuxe-dark-mode-text-dark').removeClass('active');
            
            // Update menu toggle
            $('.aqualuxe-dark-mode-menu-toggle').html(
                '<span class="aqualuxe-dark-mode-menu-icon">' +
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M10 7a7 7 0 0 0 12 4.9v.1c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2h.1A6.979 6.979 0 0 0 10 7zm-6 5a8 8 0 0 0 15.062 3.762A9 9 0 0 1 8.238 4.938 7.999 7.999 0 0 0 4 12z"/></svg>' +
                '</span>' +
                '<span class="aqualuxe-dark-mode-menu-text">' + aqualuxeL10n.darkMode + '</span>'
            );
            
            // Set cookie
            if (aqualuxeDarkMode.rememberPreference) {
                this.setCookie('aqualuxe_dark_mode', 'light', aqualuxeDarkMode.cookieExpiration);
            }
            
            // Trigger event
            $(document).trigger('aqualuxe_dark_mode_disabled');
        },

        /**
         * Set cookie
         *
         * @param {string} name Cookie name
         * @param {string} value Cookie value
         * @param {number} days Cookie expiration in days
         */
        setCookie: function(name, value, days) {
            var expires = '';
            
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = '; expires=' + date.toUTCString();
            }
            
            document.cookie = name + '=' + value + expires + '; path=/';
        },

        /**
         * Get cookie
         *
         * @param {string} name Cookie name
         * @return {string|null} Cookie value
         */
        getCookie: function(name) {
            var nameEQ = name + '=';
            var ca = document.cookie.split(';');
            
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                
                while (c.charAt(0) === ' ') {
                    c = c.substring(1, c.length);
                }
                
                if (c.indexOf(nameEQ) === 0) {
                    return c.substring(nameEQ.length, c.length);
                }
            }
            
            return null;
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeDarkModeModule.init();
    });

})(jQuery);