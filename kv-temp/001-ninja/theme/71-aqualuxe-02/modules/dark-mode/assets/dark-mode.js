/**
 * Dark Mode JavaScript Module
 * 
 * Handles dark mode functionality including:
 * - Theme switching
 * - Local storage persistence
 * - System preference detection
 * - Smooth transitions
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Dark Mode Controller
     */
    class DarkModeController {
        constructor() {
            this.settings = window.aqualuxeDarkMode || {};
            this.storageKey = 'aqualuxe-dark-mode';
            this.currentTheme = this.getCurrentTheme();
            this.mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            
            this.init();
        }

        /**
         * Initialize the dark mode controller
         */
        init() {
            if (!this.settings.enabled) {
                return;
            }

            this.bindEvents();
            this.applyTheme(this.currentTheme);
            this.updateToggleButtons();
            
            // Listen for system theme changes
            this.mediaQuery.addEventListener('change', this.handleSystemThemeChange.bind(this));
            
            // Handle keyboard shortcuts
            this.setupKeyboardShortcuts();
            
            console.log('AquaLuxe Dark Mode initialized');
        }

        /**
         * Bind event listeners
         */
        bindEvents() {
            // Toggle buttons
            $(document).on('click', '[data-toggle="dark-mode"]', this.handleToggleClick.bind(this));
            
            // Custom events
            $(document).on('aqualuxe:darkModeToggle', this.handleCustomToggle.bind(this));
            $(document).on('aqualuxe:darkModeSet', this.handleCustomSet.bind(this));
            
            // Page visibility change (to sync across tabs)
            document.addEventListener('visibilitychange', this.handleVisibilityChange.bind(this));
            
            // Storage events (for cross-tab sync)
            window.addEventListener('storage', this.handleStorageChange.bind(this));
        }

        /**
         * Handle toggle button click
         */
        handleToggleClick(event) {
            event.preventDefault();
            this.toggle();
        }

        /**
         * Handle custom toggle event
         */
        handleCustomToggle(event) {
            this.toggle();
        }

        /**
         * Handle custom set event
         */
        handleCustomSet(event, theme) {
            if (theme === 'dark' || theme === 'light') {
                this.setTheme(theme);
            }
        }

        /**
         * Handle system theme change
         */
        handleSystemThemeChange(event) {
            // Only auto-switch if user hasn't set a preference
            if (this.settings.autoDetect && !this.hasUserPreference()) {
                const theme = event.matches ? 'dark' : 'light';
                this.applyTheme(theme);
                this.currentTheme = theme;
                this.updateToggleButtons();
                this.triggerChangeEvent(theme);
            }
        }

        /**
         * Handle page visibility change
         */
        handleVisibilityChange() {
            if (!document.hidden) {
                // Page became visible, check for theme changes
                const storedTheme = this.getStoredTheme();
                if (storedTheme && storedTheme !== this.currentTheme) {
                    this.setTheme(storedTheme);
                }
            }
        }

        /**
         * Handle storage change (cross-tab sync)
         */
        handleStorageChange(event) {
            if (event.key === this.storageKey && event.newValue !== event.oldValue) {
                const newTheme = event.newValue === 'true' ? 'dark' : 'light';
                if (newTheme !== this.currentTheme) {
                    this.setTheme(newTheme);
                }
            }
        }

        /**
         * Setup keyboard shortcuts
         */
        setupKeyboardShortcuts() {
            $(document).on('keydown', (event) => {
                // Ctrl/Cmd + Shift + D to toggle dark mode
                if ((event.ctrlKey || event.metaKey) && event.shiftKey && event.key === 'D') {
                    event.preventDefault();
                    this.toggle();
                }
            });
        }

        /**
         * Toggle dark mode
         */
        toggle() {
            const newTheme = this.currentTheme === 'dark' ? 'light' : 'dark';
            this.setTheme(newTheme);
        }

        /**
         * Set specific theme
         */
        setTheme(theme) {
            if (theme !== 'dark' && theme !== 'light') {
                return;
            }

            this.currentTheme = theme;
            this.applyTheme(theme);
            this.updateToggleButtons();
            this.saveTheme(theme);
            this.triggerChangeEvent(theme);
            this.sendAjaxUpdate(theme);
        }

        /**
         * Apply theme to document
         */
        applyTheme(theme) {
            const $html = $('html');
            const $body = $('body');
            
            // Add/remove classes with animation
            if (theme === 'dark') {
                $html.addClass('dark').attr('data-theme', 'dark');
                $body.addClass('dark-mode');
            } else {
                $html.removeClass('dark').attr('data-theme', 'light');
                $body.removeClass('dark-mode');
            }

            // Update CSS custom properties
            this.updateCSSProperties(theme);
            
            // Announce to screen readers
            this.announceThemeChange(theme);
        }

        /**
         * Update CSS custom properties
         */
        updateCSSProperties(theme) {
            const root = document.documentElement;
            
            if (theme === 'dark') {
                root.style.setProperty('--current-bg', 'var(--color-bg-dark)');
                root.style.setProperty('--current-text', 'var(--color-text-dark)');
                root.style.setProperty('--current-border', 'var(--color-border-dark)');
            } else {
                root.style.setProperty('--current-bg', 'var(--color-bg-light)');
                root.style.setProperty('--current-text', 'var(--color-text-light)');
                root.style.setProperty('--current-border', 'var(--color-border-light)');
            }
        }

        /**
         * Update toggle buttons
         */
        updateToggleButtons() {
            const $toggles = $('[data-toggle="dark-mode"]');
            const isDark = this.currentTheme === 'dark';
            
            $toggles.each(function() {
                const $toggle = $(this);
                $toggle.attr('aria-pressed', isDark);
                $toggle.toggleClass('active', isDark);
                
                // Update title attribute
                const title = isDark ? 
                    'Switch to light mode' : 
                    'Switch to dark mode';
                $toggle.attr('title', title);
            });
        }

        /**
         * Save theme preference
         */
        saveTheme(theme) {
            if (this.settings.persistence) {
                try {
                    localStorage.setItem(this.storageKey, theme === 'dark');
                } catch (e) {
                    console.warn('Failed to save dark mode preference:', e);
                }
            }
        }

        /**
         * Get stored theme preference
         */
        getStoredTheme() {
            if (!this.settings.persistence) {
                return null;
            }

            try {
                const stored = localStorage.getItem(this.storageKey);
                if (stored !== null) {
                    return stored === 'true' ? 'dark' : 'light';
                }
            } catch (e) {
                console.warn('Failed to read dark mode preference:', e);
            }
            
            return null;
        }

        /**
         * Get current theme
         */
        getCurrentTheme() {
            // Check for stored preference first
            const stored = this.getStoredTheme();
            if (stored) {
                return stored;
            }

            // Check for system preference if auto-detect is enabled
            if (this.settings.autoDetect) {
                return this.mediaQuery.matches ? 'dark' : 'light';
            }

            // Default to light mode
            return 'light';
        }

        /**
         * Check if user has set a preference
         */
        hasUserPreference() {
            return this.getStoredTheme() !== null;
        }

        /**
         * Trigger theme change event
         */
        triggerChangeEvent(theme) {
            $(document).trigger('aqualuxe:darkModeChanged', [theme, this.currentTheme]);
            
            // Also trigger a custom event on window for other scripts
            window.dispatchEvent(new CustomEvent('aqualuxe-theme-changed', {
                detail: { theme, previousTheme: this.currentTheme }
            }));
        }

        /**
         * Send AJAX update to server
         */
        sendAjaxUpdate(theme) {
            if (!this.settings.ajaxUrl || !this.settings.nonce) {
                return;
            }

            $.ajax({
                url: this.settings.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_toggle_dark_mode',
                    nonce: this.settings.nonce,
                    is_dark: theme === 'dark'
                },
                success: (response) => {
                    if (response.success) {
                        console.log('Dark mode preference saved:', response.data.message);
                    }
                },
                error: (xhr, status, error) => {
                    console.warn('Failed to save dark mode preference:', error);
                }
            });
        }

        /**
         * Announce theme change to screen readers
         */
        announceThemeChange(theme) {
            const message = theme === 'dark' ? 
                'Dark mode enabled' : 
                'Light mode enabled';
            
            // Create or update live region
            let $liveRegion = $('#aqualuxe-theme-announcer');
            if (!$liveRegion.length) {
                $liveRegion = $('<div id="aqualuxe-theme-announcer" aria-live="polite" class="sr-only"></div>');
                $('body').append($liveRegion);
            }
            
            $liveRegion.text(message);
        }

        /**
         * Public API methods
         */

        /**
         * Get current theme
         */
        getTheme() {
            return this.currentTheme;
        }

        /**
         * Check if dark mode is active
         */
        isDarkMode() {
            return this.currentTheme === 'dark';
        }

        /**
         * Force light mode
         */
        setLightMode() {
            this.setTheme('light');
        }

        /**
         * Force dark mode
         */
        setDarkMode() {
            this.setTheme('dark');
        }

        /**
         * Reset to system preference
         */
        resetToSystem() {
            if (this.settings.persistence) {
                try {
                    localStorage.removeItem(this.storageKey);
                } catch (e) {
                    console.warn('Failed to remove dark mode preference:', e);
                }
            }
            
            const systemTheme = this.mediaQuery.matches ? 'dark' : 'light';
            this.setTheme(systemTheme);
        }
    }

    /**
     * Initialize when DOM is ready
     */
    $(document).ready(function() {
        // Initialize dark mode controller
        window.aqualuxeDarkMode = new DarkModeController();
        
        // Expose public API
        window.AquaLuxe = window.AquaLuxe || {};
        window.AquaLuxe.darkMode = {
            toggle: () => window.aqualuxeDarkMode.toggle(),
            setTheme: (theme) => window.aqualuxeDarkMode.setTheme(theme),
            getTheme: () => window.aqualuxeDarkMode.getTheme(),
            isDarkMode: () => window.aqualuxeDarkMode.isDarkMode(),
            setLightMode: () => window.aqualuxeDarkMode.setLightMode(),
            setDarkMode: () => window.aqualuxeDarkMode.setDarkMode(),
            resetToSystem: () => window.aqualuxeDarkMode.resetToSystem()
        };
    });

    /**
     * Handle theme transitions
     */
    $(document).on('aqualuxe:darkModeChanged', function(event, newTheme, oldTheme) {
        // Add transition class for smooth animations
        $('body').addClass('theme-transitioning');
        
        setTimeout(() => {
            $('body').removeClass('theme-transitioning');
        }, 300);
        
        // Update meta theme-color for mobile browsers
        const themeColor = newTheme === 'dark' ? '#1a1a1a' : '#ffffff';
        $('meta[name="theme-color"]').attr('content', themeColor);
        
        // Update any third-party integrations
        if (window.gtag) {
            gtag('event', 'theme_change', {
                theme: newTheme,
                previous_theme: oldTheme
            });
        }
    });

})(jQuery);
