/**
 * Dark Mode functionality
 *
 * @package AquaLuxe
 */

(function ($) {
    'use strict';

    const AqualuxeDarkMode = {
        /**
         * Initialize
         */
        init: function () {
            this.setupDarkMode();
            this.bindEvents();
        },

        /**
         * Setup dark mode
         */
        setupDarkMode: function () {
            const darkMode = this.getDarkMode();
            this.setDarkMode(darkMode);
        },

        /**
         * Bind events
         */
        bindEvents: function () {
            const self = this;

            // Toggle dark mode
            $('#dark-mode-toggle').on('click', function () {
                const isDark = $('html').attr('data-theme') === 'dark';
                self.setDarkMode(!isDark);
                self.saveDarkMode(!isDark);
            });

            // Listen for system preference changes
            if (window.matchMedia && aqualuxeDarkMode.auto) {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
                    const newDarkMode = e.matches;
                    self.setDarkMode(newDarkMode);
                    self.saveDarkMode(newDarkMode);
                });
            }
        },

        /**
         * Get dark mode preference
         *
         * @return {boolean} Dark mode preference
         */
        getDarkMode: function () {
            // Check cookies first
            if (aqualuxeDarkMode.saveInCookies) {
                const darkModeCookie = this.getCookie(aqualuxeDarkMode.cookieName);
                if (darkModeCookie !== null) {
                    return darkModeCookie === 'true';
                }
            }

            // Check system preference if auto is enabled
            if (aqualuxeDarkMode.auto && window.matchMedia) {
                return window.matchMedia('(prefers-color-scheme: dark)').matches;
            }

            // Fallback to default
            return aqualuxeDarkMode.defaultDark;
        },

        /**
         * Set dark mode
         *
         * @param {boolean} isDark Whether to enable dark mode
         */
        setDarkMode: function (isDark) {
            if (isDark) {
                $('html').attr('data-theme', 'dark');
                $('#dark-mode-toggle').addClass('is-dark');
            } else {
                $('html').attr('data-theme', 'light');
                $('#dark-mode-toggle').removeClass('is-dark');
            }
        },

        /**
         * Save dark mode preference
         *
         * @param {boolean} isDark Whether to enable dark mode
         */
        saveDarkMode: function (isDark) {
            if (aqualuxeDarkMode.saveInCookies) {
                this.setCookie(aqualuxeDarkMode.cookieName, isDark, aqualuxeDarkMode.cookieExpiration);
            }
        },

        /**
         * Get cookie
         *
         * @param {string} name Cookie name
         * @return {string|null} Cookie value
         */
        getCookie: function (name) {
            const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            return match ? match[2] : null;
        },

        /**
         * Set cookie
         *
         * @param {string} name Cookie name
         * @param {string} value Cookie value
         * @param {number} days Cookie expiration in days
         */
        setCookie: function (name, value, days) {
            let expires = '';
            if (days) {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = '; expires=' + date.toUTCString();
            }
            document.cookie = name + '=' + value + expires + '; path=/; SameSite=Lax';
        }
    };

    // Initialize on document ready
    $(document).ready(function () {
        AqualuxeDarkMode.init();
    });

})(jQuery);