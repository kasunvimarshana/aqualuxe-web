/**
 * Language Switcher JavaScript
 */
(function($) {
    'use strict';

    /**
     * Language Switcher
     */
    var LanguageSwitcher = {
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Dropdown toggle
            $('.language-switcher--dropdown .language-switcher__current').on('click', this.toggleDropdown);
            
            // Close dropdown when clicking outside
            $(document).on('click', this.closeDropdowns);
            
            // Menu toggle
            $('.language-switcher--menu .language-switcher__toggle').on('click', this.toggleMenu);
            
            // Set cookie when changing language
            $('.language-switcher__link, .language-switcher__button').on('click', this.setLanguageCookie);
        },

        /**
         * Toggle dropdown
         * 
         * @param {Event} e
         */
        toggleDropdown: function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $dropdown = $(this).siblings('.language-switcher__dropdown');
            
            if ($dropdown.is(':visible')) {
                $dropdown.hide();
                $(this).find('.language-switcher__arrow').removeClass('rotate-180');
            } else {
                $('.language-switcher__dropdown').hide();
                $('.language-switcher__arrow').removeClass('rotate-180');
                
                $dropdown.show();
                $(this).find('.language-switcher__arrow').addClass('rotate-180');
            }
        },

        /**
         * Close dropdowns
         * 
         * @param {Event} e
         */
        closeDropdowns: function(e) {
            if (!$(e.target).closest('.language-switcher--dropdown').length) {
                $('.language-switcher__dropdown').hide();
                $('.language-switcher__arrow').removeClass('rotate-180');
            }
        },

        /**
         * Toggle menu
         * 
         * @param {Event} e
         */
        toggleMenu: function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $submenu = $(this).siblings('.language-switcher__submenu');
            
            if ($submenu.is(':visible')) {
                $submenu.hide();
            } else {
                $('.language-switcher__submenu').hide();
                $submenu.show();
            }
        },

        /**
         * Set language cookie
         * 
         * @param {Event} e
         */
        setLanguageCookie: function(e) {
            var lang = $(this).attr('href').split('lang=')[1];
            
            if (lang) {
                // Set cookie
                document.cookie = 'aqualuxe_language=' + lang + '; path=/; max-age=' + (365 * 24 * 60 * 60);
            }
        }
    };

    /**
     * RTL Support
     */
    var RTLSupport = {
        /**
         * Initialize
         */
        init: function() {
            this.setupRTL();
        },

        /**
         * Setup RTL
         */
        setupRTL: function() {
            // Check if current language is RTL
            if ($('body').hasClass('rtl')) {
                // Add RTL class to HTML element
                $('html').attr('dir', 'rtl').addClass('rtl');
                
                // Adjust Tailwind direction
                if (typeof aqualuxeSettings !== 'undefined') {
                    aqualuxeSettings.direction = 'rtl';
                }
            }
        }
    };

    /**
     * Document ready
     */
    $(document).ready(function() {
        LanguageSwitcher.init();
        RTLSupport.init();
    });

})(jQuery);