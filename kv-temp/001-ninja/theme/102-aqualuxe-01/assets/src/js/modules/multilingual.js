/**
 * Multilingual Module JavaScript
 * 
 * @package AquaLuxe
 */

(function($) {
    'use strict';

    class MultilingualHandler {
        constructor() {
            this.init();
        }

        init() {
            this.setupLanguageSwitcher();
            this.handleRTLLanguages();
        }

        setupLanguageSwitcher() {
            $('.language-switcher select').on('change', function() {
                const selectedLang = $(this).val();
                if (selectedLang) {
                    window.location.href = selectedLang;
                }
            });
            
            // Dropdown toggle for mobile
            $('.language-switcher-toggle').on('click', function(e) {
                e.preventDefault();
                $(this).siblings('.language-dropdown').toggleClass('active');
            });
            
            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.language-switcher').length) {
                    $('.language-dropdown').removeClass('active');
                }
            });
        }

        handleRTLLanguages() {
            const htmlLang = $('html').attr('lang');
            const rtlLanguages = ['ar', 'he', 'fa', 'ur'];
            
            if (rtlLanguages.some(lang => htmlLang.startsWith(lang))) {
                $('html').attr('dir', 'rtl').addClass('rtl');
            }
        }
    }

    // Initialize when ready
    $(document).ready(function() {
        new MultilingualHandler();
    });

})(jQuery);