/**
 * Dark Mode JavaScript
 *
 * @package AquaLuxe
 * @subpackage Modules/DarkMode
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Dark Mode object
    var AquaLuxeDarkMode = {
        /**
         * Initialize
         */
        init: function() {
            // Set up event listeners
            this.setupEventListeners();
            
            // Initialize dark mode based on settings
            this.initializeDarkMode();
        },

        /**
         * Set up event listeners
         */
        setupEventListeners: function() {
            // Toggle button click
            $(document).on('click', '#dark-mode-toggle-btn', this.toggleDarkMode.bind(this));
        },

        /**
         * Initialize dark mode
         */
        initializeDarkMode: function() {
            var darkMode = this.getDarkModePreference();
            
            // Apply dark mode if needed
            if (darkMode === 'dark') {
                this.enableDarkMode();
            } else {
                this.disableDarkMode();
            }
            
            // Add transition after initial load to prevent flash
            setTimeout(function() {
                $('body').addClass('dark-mode-transitions');
            }, 100);
        },

        /**
         * Toggle dark mode
         * 
         * @param {Event} e Click event
         */
        toggleDarkMode: function(e) {
            e.preventDefault();
            
            var currentMode = this.getDarkModePreference();
            var newMode = currentMode === 'dark' ? 'light' : 'dark';
            
            // Update preference
            this.setDarkModePreference(newMode);
            
            // Apply the new mode
            if (newMode === 'dark') {
                this.enableDarkMode();
            } else {
                this.disableDarkMode();
            }
            
            // Update toggle button text
            this.updateToggleButtonText(newMode);
            
            // Save preference via AJAX
            this.savePreference(newMode);
        },

        /**
         * Enable dark mode
         */
        enableDarkMode: function() {
            $('body').addClass('dark-mode');
            $('html').attr('data-theme', 'dark');
            
            // Update meta tags
            $('meta[name="color-scheme"]').attr('content', 'dark');
            
            // Dispatch event
            this.dispatchEvent('darkModeEnabled');
        },

        /**
         * Disable dark mode
         */
        disableDarkMode: function() {
            $('body').removeClass('dark-mode');
            $('html').attr('data-theme', 'light');
            
            // Update meta tags
            $('meta[name="color-scheme"]').attr('content', 'light');
            
            // Dispatch event
            this.dispatchEvent('darkModeDisabled');
        },

        /**
         * Get dark mode preference
         * 
         * @return {string} 'dark' or 'light'
         */
        getDarkModePreference: function() {
            // Check cookie first
            var cookiePreference = this.getCookie('aqualuxe_dark_mode');
            if (cookiePreference) {
                return cookiePreference;
            }
            
            // Check system preference if auto-detect is enabled
            if (aqualuxeDarkMode.autoDetect) {
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    return 'dark';
                }
            }
            
            // Fall back to default
            return aqualuxeDarkMode.defaultMode;
        },

        /**
         * Set dark mode preference
         * 
         * @param {string} mode 'dark' or 'light'
         */
        setDarkModePreference: function(mode) {
            // Set cookie for 30 days
            this.setCookie('aqualuxe_dark_mode', mode, 30);
        },

        /**
         * Update toggle button text
         * 
         * @param {string} mode 'dark' or 'light'
         */
        updateToggleButtonText: function(mode) {
            var $button = $('#dark-mode-toggle-btn');
            var $screenReaderText = $button.find('.screen-reader-text');
            
            if (mode === 'dark') {
                $screenReaderText.text('Switch to Light Mode');
                $button.attr('aria-label', 'Switch to Light Mode');
            } else {
                $screenReaderText.text('Switch to Dark Mode');
                $button.attr('aria-label', 'Switch to Dark Mode');
            }
        },

        /**
         * Save preference via AJAX
         * 
         * @param {string} mode 'dark' or 'light'
         */
        savePreference: function(mode) {
            $.ajax({
                url: aqualuxeDarkMode.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_save_dark_mode_preference',
                    mode: mode,
                    nonce: aqualuxeDarkMode.nonce
                }
            });
        },

        /**
         * Get cookie value
         * 
         * @param {string} name Cookie name
         * @return {string|null} Cookie value or null
         */
        getCookie: function(name) {
            var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            return match ? match[2] : null;
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
         * Dispatch custom event
         * 
         * @param {string} eventName Event name
         */
        dispatchEvent: function(eventName) {
            var event = new CustomEvent('aqualuxe.' + eventName, {
                detail: {
                    mode: this.getDarkModePreference()
                }
            });
            
            document.dispatchEvent(event);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeDarkMode.init();
        
        // Listen for system preference changes
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                if (aqualuxeDarkMode.autoDetect) {
                    if (e.matches) {
                        AquaLuxeDarkMode.enableDarkMode();
                        AquaLuxeDarkMode.setDarkModePreference('dark');
                    } else {
                        AquaLuxeDarkMode.disableDarkMode();
                        AquaLuxeDarkMode.setDarkModePreference('light');
                    }
                    
                    // Update toggle button text
                    AquaLuxeDarkMode.updateToggleButtonText(e.matches ? 'dark' : 'light');
                }
            });
        }
    });

})(jQuery);