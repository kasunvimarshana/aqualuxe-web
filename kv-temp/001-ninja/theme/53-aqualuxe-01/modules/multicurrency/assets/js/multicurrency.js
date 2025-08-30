/**
 * Multicurrency functionality
 *
 * @package AquaLuxe
 */

(function ($) {
    'use strict';

    const AqualuxeMulticurrency = {
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

            // Currency switcher dropdown
            $('#currency-switcher-select').on('change', function () {
                const currency = $(this).val();
                self.switchCurrency(currency);
            });

            // Currency switcher symbols and codes
            $('.currency-switcher-symbols a, .currency-switcher-codes a').on('click', function (e) {
                e.preventDefault();
                const currency = $(this).data('currency');
                self.switchCurrency(currency);
            });
        },

        /**
         * Switch currency
         *
         * @param {string} currency Currency code
         */
        switchCurrency: function (currency) {
            // Save currency preference in cookie
            if (aqualuxeMulticurrency.saveInCookies) {
                this.setCookie(aqualuxeMulticurrency.cookieName, currency, aqualuxeMulticurrency.cookieExpiration);
            }

            // Reload page with currency parameter
            window.location.href = this.updateQueryStringParameter(window.location.href, 'currency', currency);
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
        AqualuxeMulticurrency.init();
    });

})(jQuery);