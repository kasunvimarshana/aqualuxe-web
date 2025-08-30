/**
 * AquaLuxe Theme Dark Mode JavaScript
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * AquaLuxe Dark Mode Object
     */
    var AquaLuxeDarkMode = {
        /**
         * Initialize
         */
        init: function() {
            this.toggleButton = $('#dark-mode-toggle');
            this.isDarkMode = document.documentElement.classList.contains('dark');
            this.setupEventListeners();
            this.initSystemPreference();
        },

        /**
         * Setup Event Listeners
         */
        setupEventListeners: function() {
            var self = this;

            // Toggle dark mode on button click
            this.toggleButton.on('click', function() {
                self.toggle();
            });

            // Listen for system preference changes
            if (window.matchMedia) {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                    if (self.isAutoMode()) {
                        self.setDarkMode(e.matches);
                    }
                });
            }
        },

        /**
         * Initialize System Preference
         */
        initSystemPreference: function() {
            // If auto mode is enabled and no saved preference exists
            if (this.isAutoMode() && !this.getSavedPreference()) {
                var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                this.setDarkMode(prefersDark);
            }
        },

        /**
         * Toggle Dark Mode
         */
        toggle: function() {
            this.setDarkMode(!this.isDarkMode);
        },

        /**
         * Set Dark Mode
         *
         * @param {boolean} isDark Whether to enable dark mode
         */
        setDarkMode: function(isDark) {
            // Update state
            this.isDarkMode = isDark;

            // Update DOM
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            // Save preference
            this.savePreference(isDark);

            // Trigger event
            $(document).trigger('darkModeChanged', [isDark]);
        },

        /**
         * Save Preference
         *
         * @param {boolean} isDark Whether dark mode is enabled
         */
        savePreference: function(isDark) {
            if (this.isSaveInCookies()) {
                this.setCookie('aqualuxe_dark_mode', isDark ? 'dark' : 'light', 30);
            }
        },

        /**
         * Get Saved Preference
         *
         * @return {string|null} Saved preference
         */
        getSavedPreference: function() {
            if (this.isSaveInCookies()) {
                return this.getCookie('aqualuxe_dark_mode');
            }
            return null;
        },

        /**
         * Check if Auto Mode is Enabled
         *
         * @return {boolean} Whether auto mode is enabled
         */
        isAutoMode: function() {
            return typeof aqualuxeDarkMode !== 'undefined' && aqualuxeDarkMode.auto;
        },

        /**
         * Check if Save in Cookies is Enabled
         *
         * @return {boolean} Whether save in cookies is enabled
         */
        isSaveInCookies: function() {
            return typeof aqualuxeDarkMode !== 'undefined' && aqualuxeDarkMode.saveInCookies;
        },

        /**
         * Set Cookie
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
            document.cookie = name + '=' + (value || '') + expires + '; path=/';
        },

        /**
         * Get Cookie
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
        AquaLuxeDarkMode.init();
    });

})(jQuery);