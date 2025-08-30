/**
 * AquaLuxe Dark Mode JS
 * Handles dark mode functionality
 */

(function() {
  'use strict';
  
  // Variables
  const darkModeToggle = document.getElementById('dark-mode-toggle');
  const darkModeClass = 'dark';
  const storageKey = 'aqualuxe-theme';
  const body = document.body;
  
  // Initialize dark mode
  function initDarkMode() {
    if (!darkModeToggle) return;
    
    // Check for saved theme preference or system preference
    const savedTheme = localStorage.getItem(storageKey);
    const systemDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    // Apply theme based on saved preference or system preference
    if (savedTheme === 'dark' || (!savedTheme && systemDarkMode)) {
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
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
      if (localStorage.getItem(storageKey)) return; // Don't override user preference
      
      if (e.matches) {
        enableDarkMode(false); // Don't save to storage, just follow system
      } else {
        disableDarkMode(false); // Don't save to storage, just follow system
      }
    });
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
    darkModeToggle.setAttribute('aria-checked', 'true');
    
    if (savePreference) {
      localStorage.setItem(storageKey, 'dark');
    }
    
    // Update meta theme-color
    updateThemeColor(true);
  }
  
  // Disable dark mode
  function disableDarkMode(savePreference = true) {
    body.classList.remove(darkModeClass);
    darkModeToggle.setAttribute('aria-checked', 'false');
    
    if (savePreference) {
      localStorage.setItem(storageKey, 'light');
    }
    
    // Update meta theme-color
    updateThemeColor(false);
  }
  
  // Update theme-color meta tag for browser UI
  function updateThemeColor(isDark) {
    const metaThemeColor = document.querySelector('meta[name="theme-color"]');
    
    if (metaThemeColor) {
      metaThemeColor.setAttribute('content', isDark ? '#0f172a' : '#ffffff');
    }
  }
  
  // Initialize when DOM is loaded
  document.addEventListener('DOMContentLoaded', initDarkMode);
})();