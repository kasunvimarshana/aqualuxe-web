/**
 * AquaLuxe Theme - Dark Mode
 *
 * This file handles the dark mode functionality including:
 * - Toggle between light and dark mode
 * - Persist user preference
 * - Respect system preference
 * - Early detection to prevent flash of incorrect theme
 */

(function() {
  'use strict';
  
  // DOM elements
  const darkModeToggle = document.querySelector('.dark-mode-toggle');
  const html = document.documentElement;
  
  // Variables
  const STORAGE_KEY = 'aqualuxe-theme-preference';
  const DARK_CLASS = 'dark';
  const LIGHT_CLASS = 'light';
  const DARK_MODE_MEDIA_QUERY = '(prefers-color-scheme: dark)';
  
  /**
   * Initialize dark mode functionality
   */
  function init() {
    setupDarkModeToggle();
    updateThemeIcons();
  }
  
  /**
   * Set up dark mode toggle functionality
   */
  function setupDarkModeToggle() {
    if (!darkModeToggle) {
      return;
    }
    
    // Set initial ARIA attributes
    darkModeToggle.setAttribute('role', 'button');
    darkModeToggle.setAttribute('aria-pressed', isDarkMode());
    darkModeToggle.setAttribute('aria-label', isDarkMode() ? 'Switch to light mode' : 'Switch to dark mode');
    
    // Toggle dark mode on click
    darkModeToggle.addEventListener('click', function() {
      toggleDarkMode();
      
      // Update ARIA attributes
      darkModeToggle.setAttribute('aria-pressed', isDarkMode());
      darkModeToggle.setAttribute('aria-label', isDarkMode() ? 'Switch to light mode' : 'Switch to dark mode');
    });
    
    // Toggle dark mode on keypress (Enter or Space)
    darkModeToggle.addEventListener('keydown', function(event) {
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault();
        darkModeToggle.click();
      }
    });
  }
  
  /**
   * Toggle between dark and light mode
   */
  function toggleDarkMode() {
    if (isDarkMode()) {
      setLightMode();
    } else {
      setDarkMode();
    }
    
    updateThemeIcons();
  }
  
  /**
   * Check if dark mode is currently active
   * @return {boolean} True if dark mode is active
   */
  function isDarkMode() {
    return html.classList.contains(DARK_CLASS);
  }
  
  /**
   * Set dark mode
   */
  function setDarkMode() {
    html.classList.remove(LIGHT_CLASS);
    html.classList.add(DARK_CLASS);
    localStorage.setItem(STORAGE_KEY, DARK_CLASS);
    
    // Dispatch event for other scripts
    window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: DARK_CLASS } }));
  }
  
  /**
   * Set light mode
   */
  function setLightMode() {
    html.classList.remove(DARK_CLASS);
    html.classList.add(LIGHT_CLASS);
    localStorage.setItem(STORAGE_KEY, LIGHT_CLASS);
    
    // Dispatch event for other scripts
    window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: LIGHT_CLASS } }));
  }
  
  /**
   * Update theme icons based on current mode
   */
  function updateThemeIcons() {
    if (!darkModeToggle) {
      return;
    }
    
    const sunIcon = darkModeToggle.querySelector('.sun-icon');
    const moonIcon = darkModeToggle.querySelector('.moon-icon');
    
    if (sunIcon && moonIcon) {
      if (isDarkMode()) {
        sunIcon.style.display = 'block';
        moonIcon.style.display = 'none';
      } else {
        sunIcon.style.display = 'none';
        moonIcon.style.display = 'block';
      }
    }
  }
  
  /**
   * Get user's theme preference
   * @return {string} Theme preference ('dark', 'light', or null)
   */
  function getThemePreference() {
    // Check localStorage first
    const storedPreference = localStorage.getItem(STORAGE_KEY);
    if (storedPreference) {
      return storedPreference;
    }
    
    // Check system preference
    if (window.matchMedia && window.matchMedia(DARK_MODE_MEDIA_QUERY).matches) {
      return DARK_CLASS;
    }
    
    // Default to light mode
    return LIGHT_CLASS;
  }
  
  /**
   * Apply theme based on user preference
   */
  function applyThemePreference() {
    const preference = getThemePreference();
    
    if (preference === DARK_CLASS) {
      html.classList.add(DARK_CLASS);
    } else {
      html.classList.add(LIGHT_CLASS);
    }
  }
  
  /**
   * Listen for system preference changes
   */
  function listenForSystemPreferenceChanges() {
    if (!window.matchMedia) {
      return;
    }
    
    const mediaQuery = window.matchMedia(DARK_MODE_MEDIA_QUERY);
    
    mediaQuery.addEventListener('change', function(event) {
      // Only apply system preference if user hasn't set a preference
      if (!localStorage.getItem(STORAGE_KEY)) {
        if (event.matches) {
          setDarkMode();
        } else {
          setLightMode();
        }
        updateThemeIcons();
      }
    });
  }
  
  // Apply theme preference immediately to prevent flash
  applyThemePreference();
  
  // Listen for system preference changes
  listenForSystemPreferenceChanges();
  
  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();

/**
 * Early detection of dark mode preference
 * This script is inlined in the head to prevent flash of incorrect theme
 */
function aqualuxeDarkModeEarlyDetection() {
  const STORAGE_KEY = 'aqualuxe-theme-preference';
  const DARK_CLASS = 'dark';
  const LIGHT_CLASS = 'light';
  const DARK_MODE_MEDIA_QUERY = '(prefers-color-scheme: dark)';
  const html = document.documentElement;
  
  // Get user's theme preference
  function getThemePreference() {
    // Check localStorage first
    const storedPreference = localStorage.getItem(STORAGE_KEY);
    if (storedPreference) {
      return storedPreference;
    }
    
    // Check system preference
    if (window.matchMedia && window.matchMedia(DARK_MODE_MEDIA_QUERY).matches) {
      return DARK_CLASS;
    }
    
    // Default to light mode
    return LIGHT_CLASS;
  }
  
  // Apply theme based on user preference
  const preference = getThemePreference();
  
  if (preference === DARK_CLASS) {
    html.classList.add(DARK_CLASS);
  } else {
    html.classList.add(LIGHT_CLASS);
  }
}

// Execute early detection immediately
aqualuxeDarkModeEarlyDetection();