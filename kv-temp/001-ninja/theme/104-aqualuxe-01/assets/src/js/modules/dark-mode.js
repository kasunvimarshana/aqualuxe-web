/**
 * Dark Mode Module
 * 
 * Handles dark mode toggle and persistence
 * 
 * @package AquaLuxe
 */

(function() {
    'use strict';
    
    const STORAGE_KEY = 'aqualuxe_dark_mode';
    const MEDIA_QUERY = '(prefers-color-scheme: dark)';
    
    class DarkMode {
        constructor() {
            this.isDark = this.getInitialMode();
            this.init();
        }
        
        /**
         * Initialize dark mode
         */
        init() {
            this.applyMode();
            this.setupToggle();
            this.setupMediaQueryListener();
        }
        
        /**
         * Get initial dark mode state
         */
        getInitialMode() {
            // Check localStorage first
            const stored = localStorage.getItem(STORAGE_KEY);
            if (stored !== null) {
                return stored === 'true';
            }
            
            // Fallback to system preference
            return window.matchMedia(MEDIA_QUERY).matches;
        }
        
        /**
         * Apply dark mode to document
         */
        applyMode() {
            const html = document.documentElement;
            const body = document.body;
            
            if (this.isDark) {
                html.classList.add('dark');
                body.classList.add('dark-mode');
            } else {
                html.classList.remove('dark');
                body.classList.remove('dark-mode');
            }
            
            // Update toggle button state
            this.updateToggleButton();
            
            // Store preference
            localStorage.setItem(STORAGE_KEY, this.isDark.toString());
            
            // Dispatch custom event
            window.dispatchEvent(new CustomEvent('darkModeChange', {
                detail: { isDark: this.isDark }
            }));
        }
        
        /**
         * Toggle dark mode
         */
        toggle() {
            this.isDark = !this.isDark;
            this.applyMode();
        }
        
        /**
         * Setup toggle button
         */
        setupToggle() {
            const toggleButton = document.getElementById('dark-mode-toggle');
            
            if (toggleButton) {
                toggleButton.addEventListener('click', () => {
                    this.toggle();
                });
                
                // Handle keyboard navigation
                toggleButton.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.toggle();
                    }
                });
            }
        }
        
        /**
         * Update toggle button appearance
         */
        updateToggleButton() {
            const toggleButton = document.getElementById('dark-mode-toggle');
            
            if (toggleButton) {
                const moonIcon = toggleButton.querySelector('.dark\\:hidden');
                const sunIcon = toggleButton.querySelector('.hidden.dark\\:block');
                
                if (moonIcon && sunIcon) {
                    if (this.isDark) {
                        moonIcon.classList.add('hidden');
                        sunIcon.classList.remove('hidden');
                    } else {
                        moonIcon.classList.remove('hidden');
                        sunIcon.classList.add('hidden');
                    }
                }
                
                // Update aria-label
                const label = this.isDark ? 'Switch to light mode' : 'Switch to dark mode';
                toggleButton.setAttribute('aria-label', label);
            }
        }
        
        /**
         * Setup media query listener for system preference changes
         */
        setupMediaQueryListener() {
            const mediaQuery = window.matchMedia(MEDIA_QUERY);
            
            mediaQuery.addEventListener('change', (e) => {
                // Only auto-switch if user hasn't manually set preference
                const stored = localStorage.getItem(STORAGE_KEY);
                if (stored === null) {
                    this.isDark = e.matches;
                    this.applyMode();
                }
            });
        }
        
        /**
         * Get current dark mode state
         */
        isDarkMode() {
            return this.isDark;
        }
        
        /**
         * Set dark mode programmatically
         */
        setMode(isDark) {
            this.isDark = isDark;
            this.applyMode();
        }
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            window.AquaLuxeDarkMode = new DarkMode();
        });
    } else {
        window.AquaLuxeDarkMode = new DarkMode();
    }
    
    // Also initialize immediately for instant dark mode application
    const quickInit = new DarkMode();
    
})();