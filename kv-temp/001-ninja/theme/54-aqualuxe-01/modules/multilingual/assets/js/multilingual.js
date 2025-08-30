/**
 * AquaLuxe Multilingual Module Scripts
 */

(function($) {
    'use strict';

    /**
     * AquaLuxe Multilingual Module
     */
    var AquaLuxeMultilingualModule = {
        /**
         * Initialize module
         */
        init: function() {
            this.initLanguageSwitcher();
            this.initLanguageCookie();
            this.initRTLSupport();
        },

        /**
         * Initialize language switcher
         */
        initLanguageSwitcher: function() {
            // Dropdown language switcher
            $('.aqualuxe-language-select').on('change', function() {
                var url = $(this).find('option:selected').data('url');
                
                if (url) {
                    window.location.href = url;
                }
            });
            
            // Add active class to current language in flags and text switchers
            var currentLanguage = aqualuxeMultilingual.currentLanguage;
            
            $('.aqualuxe-language-flag-item').each(function() {
                var langCode = $(this).data('lang');
                
                if (langCode === currentLanguage) {
                    $(this).addClass('aqualuxe-language-active');
                }
            });
            
            $('.aqualuxe-language-text-item').each(function() {
                var langCode = $(this).data('lang');
                
                if (langCode === currentLanguage) {
                    $(this).addClass('aqualuxe-language-active');
                }
            });
        },

        /**
         * Initialize language cookie
         */
        initLanguageCookie: function() {
            // Set language cookie when language is switched
            $(document).on('click', '.aqualuxe-language-flag-item a, .aqualuxe-language-text-item a', function() {
                var langCode = $(this).parent().data('lang');
                
                if (langCode) {
                    AquaLuxeMultilingualModule.setCookie('aqualuxe_language', langCode, 30);
                }
            });
        },

        /**
         * Initialize RTL support
         */
        initRTLSupport: function() {
            // Add RTL class to body if current language is RTL
            var rtlLanguages = ['ar', 'fa', 'he', 'ur'];
            var currentLanguage = aqualuxeMultilingual.currentLanguage;
            
            // Extract language code without locale
            var langCode = currentLanguage.split('_')[0];
            
            if (rtlLanguages.indexOf(langCode) !== -1) {
                $('body').addClass('rtl');
                $('html').attr('dir', 'rtl');
            }
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
        AquaLuxeMultilingualModule.init();
    });

})(jQuery);