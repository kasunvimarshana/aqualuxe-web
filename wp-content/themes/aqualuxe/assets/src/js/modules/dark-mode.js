/**
 * Dark Mode Module JavaScript
 * Handles client-side dark mode functionality
 * 
 * @package AquaLuxe
 */

(function($) {
    'use strict';

    class DarkModeHandler {
        constructor() {
            this.toggle = null;
            this.currentMode = 'system';
            this.init();
        }

        /**
         * Initialize dark mode functionality
         */
        init() {
            this.bindEvents();
            this.detectSystemPreference();
            this.applyStoredPreference();
            this.setupMediaQueryListener();
        }

        /**
         * Bind event handlers
         */
        bindEvents() {
            $(document).ready(() => {
                this.toggle = $('#dark-mode-toggle');
                this.setupToggleHandler();
            });
        }

        /**
         * Setup toggle button handler
         */
        setupToggleHandler() {
            if (!this.toggle.length) return;

            this.toggle.on('click', (e) => {
                e.preventDefault();
                this.toggleMode();
            });

            // Keyboard accessibility
            this.toggle.on('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.toggleMode();
                }
            });
        }

        /**
         * Toggle between dark and light modes
         */
        toggleMode() {
            const currentMode = this.getCurrentMode();
            let newMode;

            switch (currentMode) {
                case 'light':
                    newMode = 'dark';
                    break;
                case 'dark':
                    newMode = 'system';
                    break;
                case 'system':
                default:
                    newMode = 'light';
                    break;
            }

            this.setMode(newMode);
        }

        /**
         * Set dark mode
         * 
         * @param {string} mode - 'light', 'dark', or 'system'
         */
        setMode(mode) {
            this.currentMode = mode;
            
            // Add transition class for smooth animation
            $('html').addClass('dark-mode-transition');
            
            // Apply mode
            this.applyMode(mode);
            
            // Update toggle button
            this.updateToggleButton(mode);
            
            // Store preference
            this.storePreference(mode);
            
            // Send to server
            this.saveToServer(mode);
            
            // Remove transition class after animation
            setTimeout(() => {
                $('html').removeClass('dark-mode-transition');
            }, 300);
        }

        /**
         * Apply the specified mode
         * 
         * @param {string} mode - 'light', 'dark', or 'system'
         */
        applyMode(mode) {
            const html = document.documentElement;
            
            // Remove existing classes
            html.classList.remove('dark', 'light');
            
            if (mode === 'dark') {
                html.classList.add('dark');
            } else if (mode === 'light') {
                html.classList.add('light');
            } else if (mode === 'system') {
                // Apply system preference
                if (this.getSystemPreference() === 'dark') {
                    html.classList.add('dark');
                } else {
                    html.classList.add('light');
                }
            }
        }

        /**
         * Update toggle button appearance and accessibility
         * 
         * @param {string} mode - Current mode
         */
        updateToggleButton(mode) {
            if (!this.toggle.length) return;

            const sunIcon = this.toggle.find('.sun-icon');
            const moonIcon = this.toggle.find('.moon-icon');
            
            // Update icons
            if (this.isDarkModeActive(mode)) {
                sunIcon.removeClass('hidden');
                moonIcon.addClass('hidden');
                this.toggle.attr('aria-label', aqualuxe_dark_mode.strings.disable_dark_mode);
            } else {
                sunIcon.addClass('hidden');
                moonIcon.removeClass('hidden');
                this.toggle.attr('aria-label', aqualuxe_dark_mode.strings.enable_dark_mode);
            }
            
            // Update data attribute
            this.toggle.attr('data-current-mode', mode);
        }

        /**
         * Check if dark mode is currently active
         * 
         * @param {string} mode - Mode to check
         * @return {boolean}
         */
        isDarkModeActive(mode) {
            if (mode === 'dark') {
                return true;
            } else if (mode === 'system') {
                return this.getSystemPreference() === 'dark';
            }
            return false;
        }

        /**
         * Get current mode from various sources
         * 
         * @return {string}
         */
        getCurrentMode() {
            // Check data attribute first
            if (this.toggle && this.toggle.length) {
                const dataMode = this.toggle.attr('data-current-mode');
                if (dataMode) {
                    return dataMode;
                }
            }
            
            // Check cookie
            const cookieMode = this.getCookie('aqualuxe-dark-mode');
            if (cookieMode && ['light', 'dark', 'system'].includes(cookieMode)) {
                return cookieMode;
            }
            
            return 'system';
        }

        /**
         * Detect system preference for dark mode
         * 
         * @return {string} 'dark' or 'light'
         */
        getSystemPreference() {
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                return 'dark';
            }
            return 'light';
        }

        /**
         * Detect initial system preference and set if no preference stored
         */
        detectSystemPreference() {
            const storedMode = this.getCookie('aqualuxe-dark-mode');
            
            if (!storedMode) {
                // No stored preference, detect system preference
                const systemPreference = this.getSystemPreference();
                this.applyMode('system');
            }
        }

        /**
         * Apply stored preference on page load
         */
        applyStoredPreference() {
            const mode = this.getCurrentMode();
            this.applyMode(mode);
            
            // Update toggle button when it's available
            $(document).ready(() => {
                this.updateToggleButton(mode);
            });
        }

        /**
         * Setup media query listener for system preference changes
         */
        setupMediaQueryListener() {
            if (window.matchMedia) {
                const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                
                mediaQuery.addEventListener('change', (e) => {
                    const currentMode = this.getCurrentMode();
                    
                    // Only react if in system mode
                    if (currentMode === 'system') {
                        this.applyMode('system');
                        this.updateToggleButton('system');
                    }
                });
            }
        }

        /**
         * Store preference in cookie
         * 
         * @param {string} mode - Mode to store
         */
        storePreference(mode) {
            const expires = new Date();
            expires.setFullYear(expires.getFullYear() + 1);
            
            document.cookie = `aqualuxe-dark-mode=${mode}; expires=${expires.toUTCString()}; path=/; SameSite=Lax`;
        }

        /**
         * Save preference to server via AJAX
         * 
         * @param {string} mode - Mode to save
         */
        saveToServer(mode) {
            if (typeof aqualuxe_dark_mode === 'undefined') {
                return;
            }

            $.ajax({
                url: aqualuxe_dark_mode.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_toggle_dark_mode',
                    mode: mode,
                    nonce: aqualuxe_dark_mode.nonce
                },
                success: (response) => {
                    if (response.success) {
                        console.log('Dark mode preference saved:', mode);
                    }
                },
                error: (xhr, status, error) => {
                    console.error('Failed to save dark mode preference:', error);
                }
            });
        }

        /**
         * Get cookie value
         * 
         * @param {string} name - Cookie name
         * @return {string|null}
         */
        getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) {
                return parts.pop().split(';').shift();
            }
            return null;
        }

        /**
         * Public method to get current mode
         * 
         * @return {string}
         */
        getMode() {
            return this.currentMode;
        }

        /**
         * Public method to check if dark mode is active
         * 
         * @return {boolean}
         */
        isActive() {
            return this.isDarkModeActive(this.currentMode);
        }
    }

    // Initialize dark mode handler
    const darkModeHandler = new DarkModeHandler();

    // Make available globally
    window.AquaLuxeDarkMode = darkModeHandler;

    // Expose utility functions
    window.aqualuxeDarkMode = {
        toggle: () => darkModeHandler.toggleMode(),
        setMode: (mode) => darkModeHandler.setMode(mode),
        getMode: () => darkModeHandler.getMode(),
        isActive: () => darkModeHandler.isActive()
    };

})(jQuery);