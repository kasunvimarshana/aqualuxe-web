/**
 * AquaLuxe Theme Dark Mode
 *
 * Handles toggling dark mode and persisting the user's preference.
 */

document.addEventListener('DOMContentLoaded', function() {
  const darkModeToggle = document.getElementById('dark-mode-toggle');
  const darkModeInput = document.getElementById('dark-mode-input');
  const html = document.documentElement;
  
  // Check for saved user preference, if any
  const darkModePreference = localStorage.getItem('aqualuxeDarkMode');
  
  // Check if the user has a system preference
  const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
  
  // Initialize dark mode based on preference
  if (darkModePreference === 'true' || (darkModePreference === null && prefersDarkMode)) {
    enableDarkMode();
  } else {
    disableDarkMode();
  }
  
  // Toggle dark mode on click
  if (darkModeToggle && darkModeInput) {
    darkModeToggle.addEventListener('click', function() {
      if (html.classList.contains('dark')) {
        disableDarkMode();
      } else {
        enableDarkMode();
      }
    });
    
    darkModeInput.addEventListener('change', function() {
      if (this.checked) {
        enableDarkMode();
      } else {
        disableDarkMode();
      }
    });
  }
  
  // Listen for system preference changes
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
    // Only change if the user hasn't manually set a preference
    if (localStorage.getItem('aqualuxeDarkMode') === null) {
      if (e.matches) {
        enableDarkMode();
      } else {
        disableDarkMode();
      }
    }
  });
  
  /**
   * Enable dark mode
   */
  function enableDarkMode() {
    html.classList.add('dark');
    
    if (darkModeInput) {
      darkModeInput.checked = true;
    }
    
    localStorage.setItem('aqualuxeDarkMode', 'true');
    
    // Dispatch event for other scripts
    document.dispatchEvent(new CustomEvent('aqualuxeDarkModeChange', { detail: { darkMode: true } }));
    
    // If user is logged in, save preference via AJAX
    if (typeof aqualuxeDarkMode !== 'undefined' && aqualuxeDarkMode.ajaxurl) {
      saveDarkModePreference(true);
    }
  }
  
  /**
   * Disable dark mode
   */
  function disableDarkMode() {
    html.classList.remove('dark');
    
    if (darkModeInput) {
      darkModeInput.checked = false;
    }
    
    localStorage.setItem('aqualuxeDarkMode', 'false');
    
    // Dispatch event for other scripts
    document.dispatchEvent(new CustomEvent('aqualuxeDarkModeChange', { detail: { darkMode: false } }));
    
    // If user is logged in, save preference via AJAX
    if (typeof aqualuxeDarkMode !== 'undefined' && aqualuxeDarkMode.ajaxurl) {
      saveDarkModePreference(false);
    }
  }
  
  /**
   * Save dark mode preference via AJAX for logged-in users
   */
  function saveDarkModePreference(isDarkMode) {
    // Only proceed if we have the AJAX URL
    if (typeof aqualuxeDarkMode === 'undefined' || !aqualuxeDarkMode.ajaxurl) {
      return;
    }
    
    const data = new FormData();
    data.append('action', 'aqualuxe_save_dark_mode');
    data.append('dark_mode', isDarkMode ? '1' : '0');
    data.append('nonce', aqualuxeDarkMode.nonce);
    
    fetch(aqualuxeDarkMode.ajaxurl, {
      method: 'POST',
      credentials: 'same-origin',
      body: data
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        console.log('Dark mode preference saved.');
      }
    })
    .catch(error => {
      console.error('Error saving dark mode preference:', error);
    });
  }
});