/**
 * AquaLuxe Theme - Dark Mode Module
 * 
 * Handles dark mode functionality with user preference persistence.
 */

const DarkMode = (function() {
    'use strict';
    
    // DOM elements
    let darkModeToggle;
    let darkModeClass = 'dark-mode';
    
    // Local storage key
    const storageKey = 'aquaLuxeDarkMode';
    
    /**
     * Initialize the dark mode functionality
     */
    function init() {
        darkModeToggle = document.querySelector('.dark-mode-toggle');
        
        if (!darkModeToggle) return;
        
        // Set initial state based on user preference or system preference
        setInitialState();
        
        // Add event listener to toggle
        darkModeToggle.addEventListener('click', toggleDarkMode);
        
        // Listen for system preference changes
        if (window.matchMedia) {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            
            // Add change listener
            try {
                // Chrome & Firefox
                mediaQuery.addEventListener('change', systemPreferenceChanged);
            } catch (error) {
                // Safari
                try {
                    mediaQuery.addListener(systemPreferenceChanged);
                } catch (error2) {
                    console.error('Dark mode system preference detection not supported');
                }
            }
        }
    }
    
    /**
     * Set initial dark mode state based on saved preference or system preference
     */
    function setInitialState() {
        // Check for saved user preference
        const savedPreference = localStorage.getItem(storageKey);
        
        if (savedPreference !== null) {
            // User has a saved preference
            const isDarkMode = savedPreference === 'true';
            setDarkModeState(isDarkMode);
        } else {
            // No saved preference, check system preference
            const prefersDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            setDarkModeState(prefersDarkMode);
        }
    }
    
    /**
     * Toggle dark mode state
     */
    function toggleDarkMode() {
        const isDarkMode = document.documentElement.classList.contains(darkModeClass);
        setDarkModeState(!isDarkMode);
        
        // Save user preference
        localStorage.setItem(storageKey, (!isDarkMode).toString());
        
        // Dispatch custom event for other components to react
        document.dispatchEvent(new CustomEvent('darkModeChanged', {
            detail: { isDarkMode: !isDarkMode }
        }));
    }
    
    /**
     * Set dark mode state
     * 
     * @param {boolean} isDarkMode - Whether to enable dark mode
     */
    function setDarkModeState(isDarkMode) {
        if (isDarkMode) {
            document.documentElement.classList.add(darkModeClass);
            darkModeToggle.setAttribute('aria-pressed', 'true');
            darkModeToggle.setAttribute('title', 'Switch to light mode');
        } else {
            document.documentElement.classList.remove(darkModeClass);
            darkModeToggle.setAttribute('aria-pressed', 'false');
            darkModeToggle.setAttribute('title', 'Switch to dark mode');
        }
    }
    
    /**
     * Handle system preference change
     * 
     * @param {MediaQueryListEvent} event - Media query change event
     */
    function systemPreferenceChanged(event) {
        // Only apply system preference if user hasn't set a preference
        if (localStorage.getItem(storageKey) === null) {
            setDarkModeState(event.matches);
        }
    }
    
    // Public API
    return {
        init: init
    };
})();