/**
 * Dark Mode JavaScript
 */
(function($) {
    'use strict';

    /**
     * Dark Mode
     */
    var DarkMode = {
        /**
         * Initialize
         */
        init: function() {
            this.cookieName = 'aqualuxe_dark_mode';
            this.transitionDuration = AqualuxeDarkMode && AqualuxeDarkMode.transitionDuration ? AqualuxeDarkMode.transitionDuration : 300;
            this.defaultMode = 'auto';
            
            if (typeof aqualuxeSettings !== 'undefined' && aqualuxeSettings.darkMode) {
                this.defaultMode = aqualuxeSettings.darkMode.defaultMode || 'auto';
            }
            
            this.bindEvents();
            this.initMode();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Toggle dark mode
            $('.dark-mode-toggle').on('click', this.toggleDarkMode.bind(this));
            
            // Admin bar toggle
            $('#wp-admin-bar-aqualuxe-dark-mode-toggle').on('click', this.toggleDarkMode.bind(this));
            
            // Listen for system preference changes
            this.listenForSystemPreferenceChanges();
        },

        /**
         * Initialize mode
         */
        initMode: function() {
            // Get mode from cookie
            var mode = this.getCookie(this.cookieName);
            
            // If no cookie, use default mode
            if (!mode) {
                mode = this.defaultMode;
                this.setCookie(this.cookieName, mode, 365);
            }
            
            // Apply mode
            this.applyMode(mode);
        },

        /**
         * Toggle dark mode
         * 
         * @param {Event} e
         */
        toggleDarkMode: function(e) {
            e.preventDefault();
            
            // Get current mode
            var currentMode = this.getCookie(this.cookieName);
            
            // Toggle mode
            var newMode;
            if (currentMode === 'dark') {
                newMode = 'light';
            } else if (currentMode === 'light') {
                newMode = 'auto';
            } else {
                newMode = 'dark';
            }
            
            // Set cookie
            this.setCookie(this.cookieName, newMode, 365);
            
            // Apply mode
            this.applyMode(newMode);
        },

        /**
         * Apply mode
         * 
         * @param {string} mode
         */
        applyMode: function(mode) {
            // Update toggle state
            this.updateToggleState(mode);
            
            // If mode is auto, check system preference
            if (mode === 'auto') {
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    this.enableDarkMode();
                } else {
                    this.disableDarkMode();
                }
            } else if (mode === 'dark') {
                this.enableDarkMode();
            } else {
                this.disableDarkMode();
            }
        },

        /**
         * Enable dark mode
         */
        enableDarkMode: function() {
            $('body').addClass('dark-mode');
            
            // Add transition after a short delay to prevent flash
            setTimeout(function() {
                $('body').css('transition', 'background-color ' + this.transitionDuration + 'ms ease, color ' + this.transitionDuration + 'ms ease');
            }.bind(this), 10);
            
            // Update HTML class for Tailwind
            $('html').addClass('dark');
            
            // Dispatch event
            $(document).trigger('aqualuxe:dark-mode-enabled');
        },

        /**
         * Disable dark mode
         */
        disableDarkMode: function() {
            $('body').removeClass('dark-mode');
            
            // Add transition after a short delay to prevent flash
            setTimeout(function() {
                $('body').css('transition', 'background-color ' + this.transitionDuration + 'ms ease, color ' + this.transitionDuration + 'ms ease');
            }.bind(this), 10);
            
            // Update HTML class for Tailwind
            $('html').removeClass('dark');
            
            // Dispatch event
            $(document).trigger('aqualuxe:dark-mode-disabled');
        },

        /**
         * Update toggle state
         * 
         * @param {string} mode
         */
        updateToggleState: function(mode) {
            // Update switch
            $('.dark-mode-toggle--switch .dark-mode-toggle__input').prop('checked', mode === 'dark');
            
            // Update text
            var lightText = $('.dark-mode-toggle__text-light');
            var darkText = $('.dark-mode-toggle__text-dark');
            var autoText = $('.dark-mode-toggle__text-auto');
            
            lightText.hide();
            darkText.hide();
            autoText.hide();
            
            if (mode === 'light') {
                lightText.show();
            } else if (mode === 'dark') {
                darkText.show();
            } else {
                autoText.show();
            }
            
            // Update icon
            var lightIcon = $('.dark-mode-toggle__icon-light');
            var darkIcon = $('.dark-mode-toggle__icon-dark');
            var autoIcon = $('.dark-mode-toggle__icon-auto');
            
            lightIcon.hide();
            darkIcon.hide();
            autoIcon.hide();
            
            if (mode === 'light') {
                lightIcon.show();
            } else if (mode === 'dark') {
                darkIcon.show();
            } else {
                autoIcon.show();
            }
        },

        /**
         * Listen for system preference changes
         */
        listenForSystemPreferenceChanges: function() {
            if (window.matchMedia) {
                var mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                
                // Add listener
                try {
                    // Chrome & Firefox
                    mediaQuery.addEventListener('change', function(e) {
                        if (this.getCookie(this.cookieName) === 'auto') {
                            if (e.matches) {
                                this.enableDarkMode();
                            } else {
                                this.disableDarkMode();
                            }
                        }
                    }.bind(this));
                } catch (e1) {
                    try {
                        // Safari
                        mediaQuery.addListener(function(e) {
                            if (this.getCookie(this.cookieName) === 'auto') {
                                if (e.matches) {
                                    this.enableDarkMode();
                                } else {
                                    this.disableDarkMode();
                                }
                            }
                        }.bind(this));
                    } catch (e2) {
                        console.error('Could not add media query listener', e2);
                    }
                }
            }
        },

        /**
         * Get cookie
         * 
         * @param {string} name
         * @return {string}
         */
        getCookie: function(name) {
            var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            return match ? match[2] : '';
        },

        /**
         * Set cookie
         * 
         * @param {string} name
         * @param {string} value
         * @param {number} days
         */
        setCookie: function(name, value, days) {
            var expires = '';
            
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = '; expires=' + date.toUTCString();
            }
            
            document.cookie = name + '=' + value + expires + '; path=/';
        }
    };

    /**
     * Document ready
     */
    $(document).ready(function() {
        DarkMode.init();
    });

})(jQuery);