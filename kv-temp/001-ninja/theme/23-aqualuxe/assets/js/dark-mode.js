/**
 * Dark Mode JavaScript for the AquaLuxe theme
 *
 * Handles the dark mode toggle functionality
 */

(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        const darkModeToggle = $('#dark-mode-toggle');
        const body = $('body');
        const html = $('html');
        const storageKey = 'aqualuxe_color_scheme';
        const defaultColorScheme = aqualuxeSettings.defaultColorScheme || 'light';
        
        // Function to set the color scheme
        function setColorScheme(scheme) {
            if (scheme === 'dark') {
                body.addClass('dark-mode');
                html.attr('data-theme', 'dark');
                localStorage.setItem(storageKey, 'dark');
                document.cookie = storageKey + '=dark; path=/; max-age=31536000'; // 1 year
            } else {
                body.removeClass('dark-mode');
                html.attr('data-theme', 'light');
                localStorage.setItem(storageKey, 'light');
                document.cookie = storageKey + '=light; path=/; max-age=31536000'; // 1 year
            }
            
            // Update toggle button aria-label
            if (darkModeToggle.length) {
                if (scheme === 'dark') {
                    darkModeToggle.attr('aria-label', 'Switch to Light Mode');
                    darkModeToggle.addClass('dark-active');
                } else {
                    darkModeToggle.attr('aria-label', 'Switch to Dark Mode');
                    darkModeToggle.removeClass('dark-active');
                }
            }
        }
        
        // Function to get the current color scheme
        function getColorScheme() {
            // Check local storage first
            const localScheme = localStorage.getItem(storageKey);
            if (localScheme) {
                return localScheme;
            }
            
            // Check cookie
            const cookies = document.cookie.split(';');
            for (let i = 0; i < cookies.length; i++) {
                const cookie = cookies[i].trim();
                if (cookie.startsWith(storageKey + '=')) {
                    return cookie.substring(storageKey.length + 1);
                }
            }
            
            // Check system preference
            if (defaultColorScheme === 'auto') {
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    return 'dark';
                }
            }
            
            // Default to theme setting
            return defaultColorScheme;
        }
        
        // Initialize color scheme
        const initialColorScheme = getColorScheme();
        setColorScheme(initialColorScheme);
        
        // Toggle dark mode on button click
        darkModeToggle.on('click', function() {
            const currentScheme = body.hasClass('dark-mode') ? 'dark' : 'light';
            const newScheme = currentScheme === 'dark' ? 'light' : 'dark';
            setColorScheme(newScheme);
        });
        
        // Listen for system preference changes
        if (window.matchMedia) {
            const colorSchemeQuery = window.matchMedia('(prefers-color-scheme: dark)');
            
            // Modern browsers
            if (colorSchemeQuery.addEventListener) {
                colorSchemeQuery.addEventListener('change', function(e) {
                    if (defaultColorScheme === 'auto') {
                        setColorScheme(e.matches ? 'dark' : 'light');
                    }
                });
            }
            // Older browsers
            else if (colorSchemeQuery.addListener) {
                colorSchemeQuery.addListener(function(e) {
                    if (defaultColorScheme === 'auto') {
                        setColorScheme(e.matches ? 'dark' : 'light');
                    }
                });
            }
        }
    });

})(jQuery);