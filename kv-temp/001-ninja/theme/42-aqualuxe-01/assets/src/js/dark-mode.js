/**
 * AquaLuxe Theme Dark Mode
 * 
 * JavaScript for handling dark mode functionality
 */

(function($) {
    'use strict';

    // Dark Mode object
    var DarkMode = {
        /**
         * Initialize dark mode
         */
        init: function() {
            this.variables();
            this.bindEvents();
            this.checkMode();
        },

        /**
         * Define variables
         */
        variables: function() {
            this.toggle = $('#dark-mode-toggle');
            this.body = $('body');
            this.darkModeClass = 'dark';
            this.darkModeKey = 'aqualuxe_dark_mode';
            this.defaultMode = aqualuxeData && aqualuxeData.darkMode ? aqualuxeData.darkMode.defaultMode : 'light';
            this.autoMode = aqualuxeData && aqualuxeData.darkMode ? aqualuxeData.darkMode.autoMode : true;
            this.primaryColor = aqualuxeData && aqualuxeData.darkMode ? aqualuxeData.darkMode.primaryColor : '#0ea5e9';
            this.bgColor = aqualuxeData && aqualuxeData.darkMode ? aqualuxeData.darkMode.bgColor : '#121212';
            this.textColor = aqualuxeData && aqualuxeData.darkMode ? aqualuxeData.darkMode.textColor : '#e5e5e5';
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            var self = this;

            // Toggle dark mode
            this.toggle.on('click', function(e) {
                e.preventDefault();
                self.toggleMode();
            });

            // Listen for system preference changes
            if (window.matchMedia && this.autoMode) {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                    if (localStorage.getItem(self.darkModeKey) === null) {
                        self.setMode(e.matches ? 'dark' : 'light');
                    }
                });
            }

            // Listen for customizer changes
            $(document).on('darkModeChanged', function(e, mode) {
                self.setMode(mode);
            });
        },

        /**
         * Check and set the initial mode
         */
        checkMode: function() {
            var savedMode = localStorage.getItem(this.darkModeKey);
            
            if (savedMode) {
                // Use saved preference
                this.setMode(savedMode);
            } else if (this.defaultMode === 'system' && window.matchMedia) {
                // Use system preference
                var prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
                this.setMode(prefersDarkMode ? 'dark' : 'light');
            } else {
                // Use default mode
                this.setMode(this.defaultMode);
            }
        },

        /**
         * Toggle between light and dark mode
         */
        toggleMode: function() {
            var currentMode = this.body.hasClass(this.darkModeClass) ? 'dark' : 'light';
            var newMode = currentMode === 'dark' ? 'light' : 'dark';
            
            this.setMode(newMode);
            localStorage.setItem(this.darkModeKey, newMode);
        },

        /**
         * Set the mode to light or dark
         * 
         * @param {string} mode The mode to set ('light' or 'dark')
         */
        setMode: function(mode) {
            if (mode === 'dark') {
                this.body.addClass(this.darkModeClass);
                this.toggle.find('.dark-mode-icon').addClass('hidden');
                this.toggle.find('.light-mode-icon').removeClass('hidden');
                this.updateMetaThemeColor(this.bgColor);
                
                // Apply dark mode CSS variables
                document.documentElement.style.setProperty('--dark-mode-primary', this.primaryColor);
                document.documentElement.style.setProperty('--dark-mode-bg', this.bgColor);
                document.documentElement.style.setProperty('--dark-mode-text', this.textColor);
            } else {
                this.body.removeClass(this.darkModeClass);
                this.toggle.find('.dark-mode-icon').removeClass('hidden');
                this.toggle.find('.light-mode-icon').addClass('hidden');
                this.updateMetaThemeColor('#ffffff');
                
                // Reset dark mode CSS variables
                document.documentElement.style.removeProperty('--dark-mode-primary');
                document.documentElement.style.removeProperty('--dark-mode-bg');
                document.documentElement.style.removeProperty('--dark-mode-text');
            }

            // Trigger custom event
            $(document).trigger('darkModeChanged', [mode]);
        },

        /**
         * Update the meta theme-color tag
         * 
         * @param {string} color The color to set
         */
        updateMetaThemeColor: function(color) {
            var metaThemeColor = document.querySelector('meta[name="theme-color"]');
            
            if (metaThemeColor) {
                metaThemeColor.setAttribute('content', color);
            } else {
                var meta = document.createElement('meta');
                meta.name = 'theme-color';
                meta.content = color;
                document.head.appendChild(meta);
            }
        }
    };

    // Initialize Dark Mode
    $(function() {
        DarkMode.init();
    });

})(jQuery);