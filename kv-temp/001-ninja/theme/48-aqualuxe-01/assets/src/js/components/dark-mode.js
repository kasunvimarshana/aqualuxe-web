/**
 * Dark Mode Toggle Functionality
 * 
 * Handles the dark mode toggle functionality for the AquaLuxe theme.
 */

(function($) {
    'use strict';

    const DarkMode = {
        /**
         * Initialize the dark mode functionality
         */
        init: function() {
            this.cacheDom();
            this.bindEvents();
            this.checkDarkMode();
        },

        /**
         * Cache DOM elements
         */
        cacheDom: function() {
            this.$body = $('body');
            this.$darkModeToggle = $('#dark-mode-toggle');
            this.darkModeKey = 'aqualuxe_dark_mode';
            this.darkModeClass = 'dark-mode';
            this.darkModeActiveClass = 'dark-mode-active';
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            this.$darkModeToggle.on('click', this.toggleDarkMode.bind(this));
            
            // Listen for system preference changes
            if (window.matchMedia) {
                const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                
                // Initial check
                if (mediaQuery.matches && !this.getUserPreference()) {
                    this.enableDarkMode();
                }
                
                // Add change listener if supported
                if (mediaQuery.addEventListener) {
                    mediaQuery.addEventListener('change', (e) => {
                        if (!this.getUserPreference()) {
                            if (e.matches) {
                                this.enableDarkMode();
                            } else {
                                this.disableDarkMode();
                            }
                        }
                    });
                }
            }
        },

        /**
         * Check if dark mode should be enabled
         */
        checkDarkMode: function() {
            // Check for saved preference
            const darkMode = this.getUserPreference();
            
            // If dark mode is enabled in localStorage or has class
            if (darkMode === 'true' || this.$body.hasClass(this.darkModeClass)) {
                this.enableDarkMode();
            }
        },

        /**
         * Toggle dark mode
         */
        toggleDarkMode: function() {
            if (this.$body.hasClass(this.darkModeClass)) {
                this.disableDarkMode();
            } else {
                this.enableDarkMode();
            }
        },

        /**
         * Enable dark mode
         */
        enableDarkMode: function() {
            this.$body.addClass(this.darkModeClass);
            this.$darkModeToggle.addClass(this.darkModeActiveClass);
            this.saveUserPreference('true');
            
            // Dispatch custom event
            this.dispatchEvent('darkModeEnabled');
        },

        /**
         * Disable dark mode
         */
        disableDarkMode: function() {
            this.$body.removeClass(this.darkModeClass);
            this.$darkModeToggle.removeClass(this.darkModeActiveClass);
            this.saveUserPreference('false');
            
            // Dispatch custom event
            this.dispatchEvent('darkModeDisabled');
        },

        /**
         * Save user preference
         * 
         * @param {string} value The preference value
         */
        saveUserPreference: function(value) {
            // Save preference to localStorage
            localStorage.setItem(this.darkModeKey, value);
            
            // Set cookie for server-side detection
            document.cookie = this.darkModeKey + '=' + value + ';path=/;max-age=' + (60 * 60 * 24 * 30); // 30 days
        },

        /**
         * Get user preference
         * 
         * @return {string|null} The preference value
         */
        getUserPreference: function() {
            return localStorage.getItem(this.darkModeKey);
        },

        /**
         * Dispatch custom event
         * 
         * @param {string} eventName The event name
         */
        dispatchEvent: function(eventName) {
            const event = new CustomEvent(eventName, {
                detail: {
                    darkMode: this.$body.hasClass(this.darkModeClass)
                }
            });
            
            document.dispatchEvent(event);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        DarkMode.init();
    });

})(jQuery);