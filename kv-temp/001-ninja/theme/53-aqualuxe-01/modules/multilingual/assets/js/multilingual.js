/**
 * Multilingual functionality
 *
 * @package AquaLuxe
 */

(function ($) {
    'use strict';

    const AqualuxeMultilingual = {
        /**
         * Initialize
         */
        init: function () {
            this.bindEvents();
        },

        /**
         * Bind events
         */
        bindEvents: function () {
            const self = this;

            // Language switcher dropdown
            $('#language-switcher-select').on('change', function () {
                const lang = $(this).val();
                self.switchLanguage(lang);
            });

            // Language switcher flags
            $('.language-switcher-flags a, .language-switcher-flags-names a').on('click', function (e) {
                e.preventDefault();
                const lang = $(this).data('lang');
                self.switchLanguage(lang);
            });
        },

        /**
         * Switch language
         *
         * @param {string} lang Language code
         */
        switchLanguage: function (lang) {
            // Save language preference in cookie
            if (aqualuxeMultilingual.saveInCookies) {
                this.setCookie(aqualuxeMultilingual.cookieName, lang, aqualuxeMultilingual.cookieExpiration);
            }

            // Reload page with language parameter
            window.location.href = this.updateQueryStringParameter(window.location.href, 'lang', lang);
        },

        /**
         * Update query string parameter
         *
         * @param {string} uri URI
         * @param {string} key Parameter key
         * @param {string} value Parameter value
         * @return {string} Updated URI
         */
        updateQueryStringParameter: function (uri, key, value) {
            const re = new RegExp('([?&])' + key + '=.*?(&|$)', 'i');
            const separator = uri.indexOf('?') !== -1 ? '&' : '?';
            
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + '=' + value + '$2');
            } else {
                return uri + separator + key + '=' + value;
            }
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
        AqualuxeMultilingual.init();
    });

})(jQuery);