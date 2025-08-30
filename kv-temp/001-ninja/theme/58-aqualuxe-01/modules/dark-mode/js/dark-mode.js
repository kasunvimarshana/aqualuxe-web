/**
 * Dark Mode Module JavaScript
 *
 * @package AquaLuxe
 * @subpackage Modules/DarkMode
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Dark Mode Object
    var AqualuxeDarkMode = {
        // Initialize
        init: function() {
            // Set variables
            this.body = $('body');
            this.toggleButtons = $(aqualuxeDarkMode.toggleSelector);
            this.cookieName = aqualuxeDarkMode.cookieName;
            this.cookieExpiry = parseInt(aqualuxeDarkMode.cookieExpiry) || 30;
            this.default = aqualuxeDarkMode.default || 'light';
            
            // Initialize dark mode
            this.initDarkMode();
            
            // Bind events
            this.bindEvents();
        },

        // Initialize dark mode
        initDarkMode: function() {
            var savedMode = this.getCookie(this.cookieName);
            
            // If no saved preference, use default
            if (!savedMode) {
                if (this.default === 'auto') {
                    // Check system preference
                    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        this.enableDarkMode();
                    } else {
                        this.disableDarkMode();
                    }
                    
                    // Add auto class
                    this.body.addClass('auto-dark-mode');
                } else if (this.default === 'dark') {
                    this.enableDarkMode();
                } else {
                    this.disableDarkMode();
                }
            } else if (savedMode === 'dark') {
                this.enableDarkMode();
            } else {
                this.disableDarkMode();
            }
            
            // Update toggle buttons
            this.updateToggleButtons();
        },

        // Bind events
        bindEvents: function() {
            var self = this;
            
            // Toggle button click
            this.toggleButtons.on('click', function(e) {
                e.preventDefault();
                self.toggleDarkMode();
            });
            
            // System preference change
            if (window.matchMedia) {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                    if (self.body.hasClass('auto-dark-mode')) {
                        if (e.matches) {
                            self.enableDarkMode(false);
                        } else {
                            self.disableDarkMode(false);
                        }
                        self.updateToggleButtons();
                    }
                });
            }
        },

        // Toggle dark mode
        toggleDarkMode: function() {
            if (this.body.hasClass('dark-mode')) {
                this.disableDarkMode();
            } else {
                this.enableDarkMode();
            }
            
            // Update toggle buttons
            this.updateToggleButtons();
        },

        // Enable dark mode
        enableDarkMode: function(saveCookie = true) {
            this.body.addClass('dark-mode');
            
            // Save preference in cookie
            if (saveCookie) {
                this.setCookie(this.cookieName, 'dark', this.cookieExpiry);
                this.body.removeClass('auto-dark-mode');
            }
            
            // Trigger event
            $(document).trigger('aqualuxe:darkModeEnabled');
        },

        // Disable dark mode
        disableDarkMode: function(saveCookie = true) {
            this.body.removeClass('dark-mode');
            
            // Save preference in cookie
            if (saveCookie) {
                this.setCookie(this.cookieName, 'light', this.cookieExpiry);
                this.body.removeClass('auto-dark-mode');
            }
            
            // Trigger event
            $(document).trigger('aqualuxe:darkModeDisabled');
        },

        // Update toggle buttons
        updateToggleButtons: function() {
            var isDarkMode = this.body.hasClass('dark-mode');
            
            // Update aria attributes
            this.toggleButtons.attr('aria-pressed', isDarkMode);
            
            // Update toggle button text
            if (isDarkMode) {
                this.toggleButtons.find('.toggle-text-light').hide();
                this.toggleButtons.find('.toggle-text-dark').show();
            } else {
                this.toggleButtons.find('.toggle-text-light').show();
                this.toggleButtons.find('.toggle-text-dark').hide();
            }
        },

        // Set cookie
        setCookie: function(name, value, days) {
            var expires = '';
            
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = '; expires=' + date.toUTCString();
            }
            
            document.cookie = name + '=' + value + expires + '; path=/; SameSite=Lax';
        },

        // Get cookie
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
        AqualuxeDarkMode.init();
    });

})(jQuery);