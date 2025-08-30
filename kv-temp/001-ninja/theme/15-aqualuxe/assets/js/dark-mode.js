/**
 * AquaLuxe Dark Mode
 * 
 * Handles dark mode toggle functionality with local storage persistence
 * 
 * @package AquaLuxe
 */
(function() {
    'use strict';

    // Dark mode variables
    const STORAGE_KEY = 'aqualuxe-theme';
    const DARK_CLASS = 'dark';
    const LIGHT_CLASS = 'light';
    const THEME_TOGGLE_SELECTOR = '.dark-mode-toggle, #dark-mode-toggle';
    const THEME_TOGGLE_ICON_SELECTOR = '.dark-mode-toggle-icon';
    const THEME_TOGGLE_TEXT_SELECTOR = '.dark-mode-toggle-text';
    
    // SVG icons for dark/light mode
    const MOON_ICON = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-moon"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>`;
    const SUN_ICON = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>`;
    
    // Text for dark/light mode
    const DARK_MODE_TEXT = window.aqualuxeData?.i18n?.darkMode || 'Dark Mode';
    const LIGHT_MODE_TEXT = window.aqualuxeData?.i18n?.lightMode || 'Light Mode';

    /**
     * Initialize dark mode functionality
     */
    function initDarkMode() {
        // Get saved theme from local storage
        const savedTheme = localStorage.getItem(STORAGE_KEY);
        
        // Check if user prefers dark mode
        const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        // Get default theme from WordPress settings
        const defaultDarkMode = window.aqualuxeData?.darkModeDefault || false;
        
        // Set initial theme based on saved preference, user preference, or default
        if (savedTheme === DARK_CLASS || (savedTheme === null && (prefersDarkMode || defaultDarkMode))) {
            setDarkMode();
        } else {
            setLightMode();
        }
        
        // Add event listeners to theme toggle buttons when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            const themeToggles = document.querySelectorAll(THEME_TOGGLE_SELECTOR);
            
            themeToggles.forEach(toggle => {
                toggle.addEventListener('click', toggleTheme);
                
                // Update toggle button state
                updateToggleState(toggle);
            });
            
            // Listen for system preference changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                // Only change theme if user hasn't set a preference
                if (localStorage.getItem(STORAGE_KEY) === null) {
                    if (e.matches) {
                        setDarkMode();
                    } else {
                        setLightMode();
                    }
                    
                    // Update all toggle buttons
                    themeToggles.forEach(toggle => {
                        updateToggleState(toggle);
                    });
                }
            });
        });
    }

    /**
     * Toggle between dark and light mode
     * 
     * @param {Event} e Click event
     */
    function toggleTheme(e) {
        e.preventDefault();
        
        if (document.documentElement.classList.contains(DARK_CLASS)) {
            setLightMode();
        } else {
            setDarkMode();
        }
        
        // Update all toggle buttons
        document.querySelectorAll(THEME_TOGGLE_SELECTOR).forEach(toggle => {
            updateToggleState(toggle);
        });
    }

    /**
     * Set dark mode
     */
    function setDarkMode() {
        document.documentElement.classList.add(DARK_CLASS);
        document.documentElement.classList.remove(LIGHT_CLASS);
        localStorage.setItem(STORAGE_KEY, DARK_CLASS);
        
        // Dispatch event for other scripts
        document.dispatchEvent(new CustomEvent('aqualuxeThemeChanged', { detail: { theme: DARK_CLASS } }));
    }

    /**
     * Set light mode
     */
    function setLightMode() {
        document.documentElement.classList.remove(DARK_CLASS);
        document.documentElement.classList.add(LIGHT_CLASS);
        localStorage.setItem(STORAGE_KEY, LIGHT_CLASS);
        
        // Dispatch event for other scripts
        document.dispatchEvent(new CustomEvent('aqualuxeThemeChanged', { detail: { theme: LIGHT_CLASS } }));
    }

    /**
     * Update toggle button state
     * 
     * @param {HTMLElement} toggle Toggle button element
     */
    function updateToggleState(toggle) {
        const isDark = document.documentElement.classList.contains(DARK_CLASS);
        const iconElement = toggle.querySelector(THEME_TOGGLE_ICON_SELECTOR);
        const textElement = toggle.querySelector(THEME_TOGGLE_TEXT_SELECTOR);
        
        if (iconElement) {
            iconElement.innerHTML = isDark ? SUN_ICON : MOON_ICON;
        }
        
        if (textElement) {
            textElement.textContent = isDark ? LIGHT_MODE_TEXT : DARK_MODE_TEXT;
        }
        
        // Update aria-label for accessibility
        toggle.setAttribute('aria-label', isDark ? LIGHT_MODE_TEXT : DARK_MODE_TEXT);
        
        // Update aria-pressed for accessibility
        toggle.setAttribute('aria-pressed', isDark ? 'true' : 'false');
    }

    // Initialize dark mode
    initDarkMode();
})();