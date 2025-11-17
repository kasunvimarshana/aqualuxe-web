/**
 * Language Switcher JavaScript
 *
 * @package AquaLuxe
 * @subpackage Modules/Multilingual
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Language Switcher object
    var AquaLuxeLanguageSwitcher = {
        /**
         * Initialize
         */
        init: function() {
            // Set up event listeners
            this.setupEventListeners();
        },

        /**
         * Set up event listeners
         */
        setupEventListeners: function() {
            // Dropdown toggle
            $(document).on('click', '.language-switcher__current', this.toggleDropdown);
            
            // Close dropdown when clicking outside
            $(document).on('click', this.closeDropdowns);
            
            // Language selection
            $(document).on('click', '.language-switcher__link', this.selectLanguage);
        },

        /**
         * Toggle dropdown
         * 
         * @param {Event} e Click event
         */
        toggleDropdown: function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $dropdown = $(this).closest('.language-switcher__dropdown');
            var $list = $dropdown.find('.language-switcher__list');
            var isExpanded = $(this).attr('aria-expanded') === 'true';
            
            // Toggle aria-expanded
            $(this).attr('aria-expanded', !isExpanded);
            
            // Toggle dropdown visibility
            if (isExpanded) {
                $list.slideUp(200);
            } else {
                // Close other dropdowns
                $('.language-switcher__list').slideUp(200);
                $('.language-switcher__current').attr('aria-expanded', 'false');
                
                // Open this dropdown
                $list.slideDown(200);
            }
        },

        /**
         * Close all dropdowns when clicking outside
         * 
         * @param {Event} e Click event
         */
        closeDropdowns: function(e) {
            if (!$(e.target).closest('.language-switcher__dropdown').length) {
                $('.language-switcher__list').slideUp(200);
                $('.language-switcher__current').attr('aria-expanded', 'false');
            }
        },

        /**
         * Select language
         * 
         * @param {Event} e Click event
         */
        selectLanguage: function(e) {
            var $link = $(this);
            var language = $link.data('lang');
            
            // Don't prevent default if we're already on the current language
            if ($link.closest('.language-switcher__item').hasClass('language-switcher__item--current')) {
                return;
            }
            
            e.preventDefault();
            
            // Save preference via AJAX
            AquaLuxeLanguageSwitcher.saveLanguagePreference(language, function(response) {
                if (response.success) {
                    // Redirect to the new language URL
                    window.location.href = response.data.url;
                }
            });
        },

        /**
         * Save language preference via AJAX
         * 
         * @param {string} language Language code
         * @param {Function} callback Callback function
         */
        saveLanguagePreference: function(language, callback) {
            $.ajax({
                url: aqualuxeLanguage.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_save_language_preference',
                    language: language,
                    nonce: aqualuxeLanguage.nonce
                },
                success: function(response) {
                    if (typeof callback === 'function') {
                        callback(response);
                    }
                }
            });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeLanguageSwitcher.init();
    });

})(jQuery);