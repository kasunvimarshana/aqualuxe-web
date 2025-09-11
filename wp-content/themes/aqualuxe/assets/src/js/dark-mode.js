/**
 * Dark Mode Toggle Functionality
 * 
 * Handles dark mode switching with persistent user preference
 */

(function() {
    'use strict';

    /**
     * Dark Mode Controller
     */
    const DarkMode = {
        
        /**
         * Initialize dark mode
         */
        init: function() {
            this.createToggleButton();
            this.loadUserPreference();
            this.initToggleListeners();
            this.watchSystemPreference();
        },

        /**
         * Create dark mode toggle button
         */
        createToggleButton: function() {
            // Check if toggle already exists
            if (document.querySelector('.dark-mode-toggle')) {
                return;
            }

            // Create toggle button
            const toggle = document.createElement('button');
            toggle.className = 'dark-mode-toggle fixed top-20 right-4 z-40 p-3 bg-white dark:bg-gray-800 rounded-full shadow-lg border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-primary-500';
            toggle.setAttribute('aria-label', 'Toggle dark mode');
            toggle.setAttribute('data-tooltip', 'Toggle dark mode');
            
            // Sun icon (for light mode)
            const sunIcon = `
                <svg class="sun-icon w-6 h-6 text-yellow-500 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            `;
            
            // Moon icon (for dark mode)
            const moonIcon = `
                <svg class="moon-icon w-6 h-6 text-blue-400 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
            `;
            
            toggle.innerHTML = `
                <div class="relative">
                    ${sunIcon}
                    ${moonIcon}
                </div>
            `;

            // Add to page
            document.body.appendChild(toggle);
        },

        /**
         * Load user preference from localStorage
         */
        loadUserPreference: function() {
            const savedTheme = localStorage.getItem('aqualuxe-theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            // Determine initial theme
            let isDark = false;
            
            if (savedTheme === 'dark') {
                isDark = true;
            } else if (savedTheme === 'light') {
                isDark = false;
            } else {
                // No saved preference, use system preference
                isDark = systemPrefersDark;
            }
            
            this.setTheme(isDark, false);
        },

        /**
         * Initialize toggle button listeners
         */
        initToggleListeners: function() {
            const toggle = document.querySelector('.dark-mode-toggle');
            
            if (toggle) {
                toggle.addEventListener('click', () => {
                    const isDark = document.documentElement.classList.contains('dark');
                    this.setTheme(!isDark, true);
                });
                
                // Keyboard support
                toggle.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        const isDark = document.documentElement.classList.contains('dark');
                        this.setTheme(!isDark, true);
                    }
                });
            }
        },

        /**
         * Watch for system preference changes
         */
        watchSystemPreference: function() {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            
            mediaQuery.addEventListener('change', (e) => {
                // Only auto-switch if user hasn't manually set a preference
                const savedTheme = localStorage.getItem('aqualuxe-theme');
                if (!savedTheme) {
                    this.setTheme(e.matches, false);
                }
            });
        },

        /**
         * Set theme
         * 
         * @param {boolean} isDark - Whether to enable dark mode
         * @param {boolean} savePreference - Whether to save the preference
         */
        setTheme: function(isDark, savePreference = true) {
            const html = document.documentElement;
            const toggle = document.querySelector('.dark-mode-toggle');
            
            if (isDark) {
                html.classList.add('dark');
                this.updateToggleIcon(true);
                
                if (savePreference) {
                    localStorage.setItem('aqualuxe-theme', 'dark');
                }
                
                // Dispatch custom event
                this.dispatchThemeEvent('dark');
                
                // Announce to screen readers
                this.announceThemeChange('Dark mode enabled');
                
            } else {
                html.classList.remove('dark');
                this.updateToggleIcon(false);
                
                if (savePreference) {
                    localStorage.setItem('aqualuxe-theme', 'light');
                }
                
                // Dispatch custom event
                this.dispatchThemeEvent('light');
                
                // Announce to screen readers
                this.announceThemeChange('Light mode enabled');
            }
            
            // Update meta theme color
            this.updateMetaThemeColor(isDark);
            
            // Update toggle tooltip
            if (toggle) {
                toggle.setAttribute('data-tooltip', isDark ? 'Switch to light mode' : 'Switch to dark mode');
                toggle.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
            }
        },

        /**
         * Update toggle button icon
         * 
         * @param {boolean} isDark - Current theme state
         */
        updateToggleIcon: function(isDark) {
            const toggle = document.querySelector('.dark-mode-toggle');
            
            if (toggle) {
                const sunIcon = toggle.querySelector('.sun-icon');
                const moonIcon = toggle.querySelector('.moon-icon');
                
                if (isDark) {
                    sunIcon.style.opacity = '0';
                    moonIcon.style.opacity = '1';
                } else {
                    sunIcon.style.opacity = '1';
                    moonIcon.style.opacity = '0';
                }
            }
        },

        /**
         * Update meta theme color
         * 
         * @param {boolean} isDark - Current theme state
         */
        updateMetaThemeColor: function(isDark) {
            let metaThemeColor = document.querySelector('meta[name="theme-color"]');
            
            if (!metaThemeColor) {
                metaThemeColor = document.createElement('meta');
                metaThemeColor.name = 'theme-color';
                document.head.appendChild(metaThemeColor);
            }
            
            metaThemeColor.content = isDark ? '#1f2937' : '#14b8a6';
        },

        /**
         * Dispatch theme change event
         * 
         * @param {string} theme - Current theme (light/dark)
         */
        dispatchThemeEvent: function(theme) {
            const event = new CustomEvent('themechange', {
                detail: { theme: theme }
            });
            document.dispatchEvent(event);
        },

        /**
         * Announce theme change to screen readers
         * 
         * @param {string} message - Message to announce
         */
        announceThemeChange: function(message) {
            // Create or get live region
            let liveRegion = document.getElementById('theme-announcements');
            
            if (!liveRegion) {
                liveRegion = document.createElement('div');
                liveRegion.id = 'theme-announcements';
                liveRegion.setAttribute('aria-live', 'polite');
                liveRegion.setAttribute('aria-atomic', 'true');
                liveRegion.className = 'sr-only';
                document.body.appendChild(liveRegion);
            }
            
            // Announce the change
            liveRegion.textContent = message;
            
            // Clear after a delay
            setTimeout(() => {
                liveRegion.textContent = '';
            }, 1000);
        },

        /**
         * Get current theme
         * 
         * @returns {string} Current theme (light/dark)
         */
        getCurrentTheme: function() {
            return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        },

        /**
         * Toggle theme
         */
        toggle: function() {
            const isDark = this.getCurrentTheme() === 'dark';
            this.setTheme(!isDark, true);
        },

        /**
         * Add theme change listener
         * 
         * @param {function} callback - Callback function
         */
        onThemeChange: function(callback) {
            document.addEventListener('themechange', callback);
        }
    };

    /**
     * Initialize when DOM is loaded
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            DarkMode.init();
        });
    } else {
        DarkMode.init();
    }

    /**
     * Make DarkMode globally available
     */
    window.AquaLuxeDarkMode = DarkMode;

    /**
     * CSS for smooth transitions
     */
    const style = document.createElement('style');
    style.textContent = `
        .sun-icon,
        .moon-icon {
            position: absolute;
            top: 0;
            left: 0;
            transition: opacity 0.3s ease-in-out;
        }
        
        .dark-mode-toggle {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        
        .dark-mode-toggle:hover {
            box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .dark .dark-mode-toggle:hover {
            box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
        }
        
        /* Screen reader only class */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        
        /* Smooth transitions for theme switching */
        * {
            transition: background-color 0.3s ease-in-out, border-color 0.3s ease-in-out, color 0.3s ease-in-out;
        }
        
        /* Respect user's motion preferences */
        @media (prefers-reduced-motion: reduce) {
            * {
                transition: none !important;
            }
        }
    `;
    
    document.head.appendChild(style);

})();