/**
 * AquaLuxe Dark Mode Module Scripts
 *
 * @package AquaLuxe
 * @subpackage Modules/Dark_Mode
 * @since 1.0.0
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
            this.bindEvents();
            this.initAccessibility();
            this.setupSystemPreferenceListener();
        },
        
        /**
         * Bind events
         */
        bindEvents: function() {
            // Switch toggle
            $(document).on('change', '.aqualuxe-dark-mode-toggle--switch .aqualuxe-dark-mode-toggle__checkbox', this.toggleDarkMode);
            
            // Icon toggle
            $(document).on('click', '.aqualuxe-dark-mode-toggle--icon .aqualuxe-dark-mode-toggle__button', this.toggleDarkMode);
            
            // Button toggle
            $(document).on('click', '.aqualuxe-dark-mode-toggle--button .aqualuxe-dark-mode-toggle__button', this.toggleDarkMode);
        },
        
        /**
         * Initialize accessibility
         */
        initAccessibility: function() {
            // Add ARIA attributes to toggle buttons
            $('.aqualuxe-dark-mode-toggle--icon .aqualuxe-dark-mode-toggle__button, .aqualuxe-dark-mode-toggle--button .aqualuxe-dark-mode-toggle__button').attr({
                'role': 'switch',
                'aria-checked': $('html').hasClass('dark-mode') ? 'true' : 'false',
                'tabindex': '0'
            });
            
            // Add keyboard support for toggle buttons
            $(document).on('keydown', '.aqualuxe-dark-mode-toggle--icon .aqualuxe-dark-mode-toggle__button, .aqualuxe-dark-mode-toggle--button .aqualuxe-dark-mode-toggle__button', function(e) {
                // Enter or Space
                if (e.keyCode === 13 || e.keyCode === 32) {
                    e.preventDefault();
                    $(this).trigger('click');
                }
            });
            
            // Update checkbox state based on dark mode class
            $('.aqualuxe-dark-mode-toggle--switch .aqualuxe-dark-mode-toggle__checkbox').prop('checked', $('html').hasClass('dark-mode'));
        },
        
        /**
         * Set up system preference listener
         */
        setupSystemPreferenceListener: function() {
            // Check if auto detect is enabled
            if (!aqualuxeDarkMode.autoDetect) {
                return;
            }
            
            // Check if user has already set a preference
            if (localStorage.getItem('aqualuxeDarkMode')) {
                return;
            }
            
            // Listen for system preference changes
            if (window.matchMedia) {
                var mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                
                // Set initial state based on system preference
                if (mediaQuery.matches) {
                    this.enableDarkMode();
                } else {
                    this.disableDarkMode();
                }
                
                // Listen for changes
                try {
                    // Chrome & Firefox
                    mediaQuery.addEventListener('change', function(e) {
                        if (e.matches) {
                            DarkMode.enableDarkMode();
                        } else {
                            DarkMode.disableDarkMode();
                        }
                    });
                } catch (e1) {
                    try {
                        // Safari
                        mediaQuery.addListener(function(e) {
                            if (e.matches) {
                                DarkMode.enableDarkMode();
                            } else {
                                DarkMode.disableDarkMode();
                            }
                        });
                    } catch (e2) {
                        console.error('Error setting up dark mode listener:', e2);
                    }
                }
            }
        },
        
        /**
         * Toggle dark mode
         *
         * @param {Event} e Event
         */
        toggleDarkMode: function(e) {
            e.preventDefault();
            
            // Toggle dark mode class
            if ($('html').hasClass('dark-mode')) {
                DarkMode.disableDarkMode();
            } else {
                DarkMode.enableDarkMode();
            }
            
            // Update toggle state
            DarkMode.updateToggleState();
        },
        
        /**
         * Enable dark mode
         */
        enableDarkMode: function() {
            // Add dark mode class
            $('html').addClass('dark-mode');
            
            // Save preference
            if (aqualuxeDarkMode.savePreference) {
                localStorage.setItem('aqualuxeDarkMode', 'dark');
                
                // Set cookie for server-side detection
                this.setCookie('aqualuxe_dark_mode', 'dark', aqualuxeDarkMode.cookieDuration);
            }
            
            // Trigger event
            $(document).trigger('aqualuxe:darkModeEnabled');
        },
        
        /**
         * Disable dark mode
         */
        disableDarkMode: function() {
            // Remove dark mode class
            $('html').removeClass('dark-mode');
            
            // Save preference
            if (aqualuxeDarkMode.savePreference) {
                localStorage.setItem('aqualuxeDarkMode', 'light');
                
                // Set cookie for server-side detection
                this.setCookie('aqualuxe_dark_mode', 'light', aqualuxeDarkMode.cookieDuration);
            }
            
            // Trigger event
            $(document).trigger('aqualuxe:darkModeDisabled');
        },
        
        /**
         * Update toggle state
         */
        updateToggleState: function() {
            // Update checkbox state
            $('.aqualuxe-dark-mode-toggle--switch .aqualuxe-dark-mode-toggle__checkbox').prop('checked', $('html').hasClass('dark-mode'));
            
            // Update ARIA attributes
            $('.aqualuxe-dark-mode-toggle--icon .aqualuxe-dark-mode-toggle__button, .aqualuxe-dark-mode-toggle--button .aqualuxe-dark-mode-toggle__button').attr('aria-checked', $('html').hasClass('dark-mode') ? 'true' : 'false');
        },
        
        /**
         * Set cookie
         *
         * @param {string} name Cookie name
         * @param {string} value Cookie value
         * @param {number} days Expiration days
         */
        setCookie: function(name, value, days) {
            var expires = '';
            
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = '; expires=' + date.toUTCString();
            }
            
            document.cookie = name + '=' + value + expires + '; path=/; SameSite=Lax';
        }
    };
    
    /**
     * Document ready
     */
    $(document).ready(function() {
        DarkMode.init();
    });
    
})(jQuery);