/**
 * AquaLuxe Dark Mode JS
 * Handles dark mode functionality
 */

(function() {
  'use strict';
  
  // Variables
  const darkModeToggle = document.getElementById('dark-mode-toggle');
  const darkModeClass = 'dark-mode'; // Class name for dark mode
  const storageKey = 'aqualuxeDarkMode'; // Storage key for dark mode preference
  const body = document.body;
  
  // Initialize dark mode
  function initDarkMode() {
    if (!darkModeToggle) return;
    
    // Check for saved theme preference or system preference
    const savedTheme = localStorage.getItem(storageKey);
    const systemDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    // Apply theme based on saved preference or system preference
    if (savedTheme === 'true' || (!savedTheme && systemDarkMode)) {
      enableDarkMode();
    } else {
      disableDarkMode();
    }
    
    // Make toggle visible after initial theme is applied (prevents flash)
    darkModeToggle.classList.remove('invisible');
    darkModeToggle.classList.add('visible');
    
    // Toggle dark mode on click
    darkModeToggle.addEventListener('click', toggleDarkMode);
    
    // Listen for system preference changes
    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    
    // Use addEventListener with a fallback for older browsers
    if (mediaQuery.addEventListener) {
      mediaQuery.addEventListener('change', handleSystemPreferenceChange);
    } else if (mediaQuery.addListener) {
      // Deprecated but needed for older browsers
      mediaQuery.addListener(handleSystemPreferenceChange);
    }
  }
  
  // Handle system preference change
  function handleSystemPreferenceChange(e) {
    if (localStorage.getItem(storageKey)) return; // Don't override user preference
    
    if (e.matches) {
      enableDarkMode(false); // Don't save to storage, just follow system
    } else {
      disableDarkMode(false); // Don't save to storage, just follow system
    }
  }
  
  // Toggle dark mode
  function toggleDarkMode() {
    if (body.classList.contains(darkModeClass)) {
      disableDarkMode();
    } else {
      enableDarkMode();
    }
    
    // Dispatch custom event
    const event = new CustomEvent('themeChanged', {
      detail: { 
        isDark: body.classList.contains(darkModeClass) 
      }
    });
    document.dispatchEvent(event);
  }
  
  // Enable dark mode
  function enableDarkMode(savePreference = true) {
    body.classList.add(darkModeClass);
    document.documentElement.classList.add(darkModeClass); // Also add to html element for consistency
    darkModeToggle.setAttribute('aria-checked', 'true');
    
    // Update toggle icons
    const moonIcon = document.querySelector('.dark-mode-toggle-icon.moon');
    const sunIcon = document.querySelector('.dark-mode-toggle-icon.sun');
    if (moonIcon) moonIcon.classList.add('active');
    if (sunIcon) sunIcon.classList.remove('active');
    
    if (savePreference) {
      localStorage.setItem(storageKey, 'true');
      
      // Also save via AJAX if the aqualuxeDarkMode object exists
      if (typeof aqualuxeDarkMode !== 'undefined') {
        jQuery.ajax({
          url: aqualuxeDarkMode.ajaxUrl,
          type: 'POST',
          data: {
            action: 'aqualuxe_toggle_dark_mode',
            darkMode: true,
            nonce: aqualuxeDarkMode.nonce
          }
        });
      }
    }
    
    // Update meta theme-color
    updateThemeColor(true);
  }
  
  // Disable dark mode
  function disableDarkMode(savePreference = true) {
    body.classList.remove(darkModeClass);
    document.documentElement.classList.remove(darkModeClass); // Also remove from html element
    darkModeToggle.setAttribute('aria-checked', 'false');
    
    // Update toggle icons
    const moonIcon = document.querySelector('.dark-mode-toggle-icon.moon');
    const sunIcon = document.querySelector('.dark-mode-toggle-icon.sun');
    if (moonIcon) moonIcon.classList.remove('active');
    if (sunIcon) sunIcon.classList.add('active');
    
    if (savePreference) {
      localStorage.setItem(storageKey, 'false');
      
      // Also save via AJAX if the aqualuxeDarkMode object exists
      if (typeof aqualuxeDarkMode !== 'undefined') {
        jQuery.ajax({
          url: aqualuxeDarkMode.ajaxUrl,
          type: 'POST',
          data: {
            action: 'aqualuxe_toggle_dark_mode',
            darkMode: false,
            nonce: aqualuxeDarkMode.nonce
          }
        });
      }
    }
    
    // Update meta theme-color
    updateThemeColor(false);
  }
  
  // Update theme-color meta tag for browser UI
  function updateThemeColor(isDark) {
    const metaThemeColor = document.querySelector('meta[name="theme-color"]');
    
    if (metaThemeColor) {
      metaThemeColor.setAttribute('content', isDark ? '#121212' : '#ffffff');
    } else {
      // Create meta tag if it doesn't exist
      const meta = document.createElement('meta');
      meta.name = 'theme-color';
      meta.content = isDark ? '#121212' : '#ffffff';
      document.head.appendChild(meta);
    }
  }
  
  // Initialize when DOM is loaded
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDarkMode);
  } else {
    // DOM already loaded, run immediately
    initDarkMode();
  }
})();