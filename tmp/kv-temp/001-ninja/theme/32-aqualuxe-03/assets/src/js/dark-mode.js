/**
 * AquaLuxe Theme Dark Mode
 *
 * This script implements dark mode functionality with system preference detection,
 * user preference storage, and smooth transitions.
 */

(function() {
  'use strict';

  // Configuration
  const config = {
    darkClass: 'dark',
    lightClass: 'light',
    storageKey: 'aqualuxe-theme-mode',
    transitionClass: 'theme-transition',
    toggleSelector: '.theme-toggle',
    prefersDarkMediaQuery: '(prefers-color-scheme: dark)',
    defaultMode: 'auto', // 'auto', 'dark', or 'light'
    transitionDuration: 300, // milliseconds
    icons: {
      dark: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>',
      light: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>',
      auto: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>'
    }
  };

  // State
  let currentMode = config.defaultMode;
  let systemPrefersDark = false;
  let toggleButtons = [];

  /**
   * Initialize dark mode
   */
  function initialize() {
    // Check system preference
    const mediaQuery = window.matchMedia(config.prefersDarkMediaQuery);
    systemPrefersDark = mediaQuery.matches;
    
    // Listen for system preference changes
    mediaQuery.addEventListener('change', handleSystemPreferenceChange);
    
    // Get user preference from localStorage
    const storedMode = localStorage.getItem(config.storageKey);
    
    if (storedMode) {
      currentMode = storedMode;
    }
    
    // Apply initial mode
    applyMode(currentMode);
    
    // Find toggle buttons
    toggleButtons = document.querySelectorAll(config.toggleSelector);
    
    // Add event listeners to toggle buttons
    toggleButtons.forEach(button => {
      button.addEventListener('click', toggleMode);
      updateToggleButton(button);
    });
    
    // Add event listener for new toggle buttons (e.g., added dynamically)
    document.addEventListener('DOMContentLoaded', () => {
      toggleButtons = document.querySelectorAll(config.toggleSelector);
      
      toggleButtons.forEach(button => {
        button.addEventListener('click', toggleMode);
        updateToggleButton(button);
      });
    });
    
    // Create and dispatch a custom event
    const event = new CustomEvent('aqualuxe:darkmode:initialized', {
      detail: { mode: currentMode }
    });
    
    document.dispatchEvent(event);
  }

  /**
   * Apply mode (dark, light, or auto)
   *
   * @param {string} mode The mode to apply ('dark', 'light', or 'auto')
   */
  function applyMode(mode) {
    // Add transition class
    document.documentElement.classList.add(config.transitionClass);
    
    // Remove existing classes
    document.documentElement.classList.remove(config.darkClass, config.lightClass);
    
    // Apply mode
    if (mode === 'auto') {
      // Apply system preference
      if (systemPrefersDark) {
        document.documentElement.classList.add(config.darkClass);
      } else {
        document.documentElement.classList.add(config.lightClass);
      }
    } else if (mode === 'dark') {
      document.documentElement.classList.add(config.darkClass);
    } else {
      document.documentElement.classList.add(config.lightClass);
    }
    
    // Store user preference
    localStorage.setItem(config.storageKey, mode);
    
    // Update current mode
    currentMode = mode;
    
    // Update toggle buttons
    toggleButtons.forEach(button => {
      updateToggleButton(button);
    });
    
    // Remove transition class after transition completes
    setTimeout(() => {
      document.documentElement.classList.remove(config.transitionClass);
    }, config.transitionDuration);
    
    // Create and dispatch a custom event
    const event = new CustomEvent('aqualuxe:darkmode:changed', {
      detail: { mode: mode }
    });
    
    document.dispatchEvent(event);
  }

  /**
   * Toggle between dark, light, and auto modes
   */
  function toggleMode() {
    if (currentMode === 'dark') {
      applyMode('light');
    } else if (currentMode === 'light') {
      applyMode('auto');
    } else {
      applyMode('dark');
    }
  }

  /**
   * Handle system preference change
   *
   * @param {MediaQueryListEvent} event The media query change event
   */
  function handleSystemPreferenceChange(event) {
    systemPrefersDark = event.matches;
    
    // If current mode is auto, apply system preference
    if (currentMode === 'auto') {
      applyMode('auto');
    }
  }

  /**
   * Update toggle button icon and aria attributes
   *
   * @param {HTMLElement} button The toggle button
   */
  function updateToggleButton(button) {
    // Update icon
    if (button.querySelector('.theme-toggle-icon')) {
      const icon = button.querySelector('.theme-toggle-icon');
      
      if (currentMode === 'dark') {
        icon.innerHTML = config.icons.dark;
      } else if (currentMode === 'light') {
        icon.innerHTML = config.icons.light;
      } else {
        icon.innerHTML = config.icons.auto;
      }
    }
    
    // Update aria attributes
    button.setAttribute('aria-pressed', currentMode === 'dark');
    
    // Update title
    if (currentMode === 'dark') {
      button.setAttribute('title', 'Switch to light mode');
      button.setAttribute('aria-label', 'Switch to light mode');
    } else if (currentMode === 'light') {
      button.setAttribute('title', 'Switch to auto mode');
      button.setAttribute('aria-label', 'Switch to auto mode');
    } else {
      button.setAttribute('title', 'Switch to dark mode');
      button.setAttribute('aria-label', 'Switch to dark mode');
    }
  }

  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initialize);
  } else {
    initialize();
  }

  // Public API
  window.AquaLuxeDarkMode = {
    /**
     * Get current mode
     *
     * @returns {string} Current mode ('dark', 'light', or 'auto')
     */
    getMode: function() {
      return currentMode;
    },
    
    /**
     * Set mode
     *
     * @param {string} mode The mode to set ('dark', 'light', or 'auto')
     */
    setMode: function(mode) {
      if (['dark', 'light', 'auto'].includes(mode)) {
        applyMode(mode);
      }
    },
    
    /**
     * Check if dark mode is active
     *
     * @returns {boolean} True if dark mode is active
     */
    isDarkMode: function() {
      return document.documentElement.classList.contains(config.darkClass);
    },
    
    /**
     * Toggle between dark, light, and auto modes
     */
    toggle: toggleMode
  };
})();