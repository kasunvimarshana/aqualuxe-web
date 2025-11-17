/**
 * AquaLuxe Theme Dark Mode JavaScript
 * 
 * Handles the dark mode toggle functionality
 */

(function() {
    'use strict';

    /**
     * Initialize dark mode functionality
     */
    function init() {
        setupDarkModeToggle();
        setupSystemPreference();
        setupColorSchemeToggle();
    }

    /**
     * Dark Mode Toggle functionality
     */
    function setupDarkModeToggle() {
        const darkModeToggle = document.querySelector('.dark-mode-toggle');
        if (!darkModeToggle) return;

        // Check for saved user preference
        const darkModeEnabled = localStorage.getItem('aqualuxe_dark_mode') === 'enabled';
        const systemPreferenceEnabled = localStorage.getItem('aqualuxe_system_preference') === 'enabled';
        
        // Set initial state based on user preference or system preference
        if (darkModeEnabled) {
            enableDarkMode();
            darkModeToggle.setAttribute('aria-pressed', 'true');
        } else if (systemPreferenceEnabled) {
            // Check system preference
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                enableDarkMode();
                darkModeToggle.setAttribute('aria-pressed', 'true');
            }
        }
        
        // Toggle dark mode on click
        darkModeToggle.addEventListener('click', function() {
            if (document.documentElement.classList.contains('dark-mode')) {
                disableDarkMode();
                this.setAttribute('aria-pressed', 'false');
            } else {
                enableDarkMode();
                this.setAttribute('aria-pressed', 'true');
            }
            
            // Disable system preference when manually toggled
            localStorage.setItem('aqualuxe_system_preference', 'disabled');
        });
    }

    /**
     * System Preference functionality
     */
    function setupSystemPreference() {
        // Check if user has explicitly set a preference
        const userHasPreference = localStorage.getItem('aqualuxe_dark_mode') !== null;
        const systemPreferenceEnabled = localStorage.getItem('aqualuxe_system_preference') !== 'disabled';
        
        // If no user preference and system preference is enabled, use system preference
        if (!userHasPreference && systemPreferenceEnabled) {
            localStorage.setItem('aqualuxe_system_preference', 'enabled');
            
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                enableDarkMode();
                
                const darkModeToggle = document.querySelector('.dark-mode-toggle');
                if (darkModeToggle) {
                    darkModeToggle.setAttribute('aria-pressed', 'true');
                }
            }
        }
        
        // Listen for system preference changes
        if (window.matchMedia) {
            const colorSchemeQuery = window.matchMedia('(prefers-color-scheme: dark)');
            
            colorSchemeQuery.addEventListener('change', (e) => {
                const systemPreferenceEnabled = localStorage.getItem('aqualuxe_system_preference') === 'enabled';
                
                if (systemPreferenceEnabled) {
                    if (e.matches) {
                        enableDarkMode();
                        
                        const darkModeToggle = document.querySelector('.dark-mode-toggle');
                        if (darkModeToggle) {
                            darkModeToggle.setAttribute('aria-pressed', 'true');
                        }
                    } else {
                        disableDarkMode();
                        
                        const darkModeToggle = document.querySelector('.dark-mode-toggle');
                        if (darkModeToggle) {
                            darkModeToggle.setAttribute('aria-pressed', 'false');
                        }
                    }
                }
            });
        }
    }

    /**
     * Color Scheme Toggle in customizer
     */
    function setupColorSchemeToggle() {
        const colorSchemeToggles = document.querySelectorAll('.color-scheme-option');
        if (!colorSchemeToggles.length) return;

        colorSchemeToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const scheme = this.getAttribute('data-color-scheme');
                
                // Remove active class from all toggles
                colorSchemeToggles.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked toggle
                this.classList.add('active');
                
                // Apply color scheme
                applyColorScheme(scheme);
                
                // Save preference
                localStorage.setItem('aqualuxe_color_scheme', scheme);
            });
        });
        
        // Apply saved color scheme on load
        const savedScheme = localStorage.getItem('aqualuxe_color_scheme');
        if (savedScheme) {
            applyColorScheme(savedScheme);
            
            // Update active toggle
            colorSchemeToggles.forEach(toggle => {
                if (toggle.getAttribute('data-color-scheme') === savedScheme) {
                    toggle.classList.add('active');
                } else {
                    toggle.classList.remove('active');
                }
            });
        }
    }

    /**
     * Apply color scheme
     */
    function applyColorScheme(scheme) {
        // Remove all scheme classes
        document.documentElement.classList.remove('scheme-default', 'scheme-ocean', 'scheme-forest', 'scheme-sunset');
        
        // Add selected scheme class
        if (scheme !== 'default') {
            document.documentElement.classList.add(`scheme-${scheme}`);
        }
    }

    /**
     * Enable dark mode
     */
    function enableDarkMode() {
        document.documentElement.classList.add('dark-mode');
        localStorage.setItem('aqualuxe_dark_mode', 'enabled');
        
        // Update meta theme-color for mobile browsers
        const metaThemeColor = document.querySelector('meta[name="theme-color"]');
        if (metaThemeColor) {
            metaThemeColor.setAttribute('content', '#212529'); // Dark background color
        }
    }

    /**
     * Disable dark mode
     */
    function disableDarkMode() {
        document.documentElement.classList.remove('dark-mode');
        localStorage.setItem('aqualuxe_dark_mode', 'disabled');
        
        // Update meta theme-color for mobile browsers
        const metaThemeColor = document.querySelector('meta[name="theme-color"]');
        if (metaThemeColor) {
            metaThemeColor.setAttribute('content', '#ffffff'); // Light background color
        }
    }

    // Initialize when DOM is fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();