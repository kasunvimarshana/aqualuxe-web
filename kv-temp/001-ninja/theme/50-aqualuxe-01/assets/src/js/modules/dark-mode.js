/**
 * Dark Mode Module
 * 
 * Handles the dark mode functionality with user preference persistence.
 */

// Dark mode initialization
export function initDarkMode() {
  // Check for saved user preference
  const darkModeEnabled = localStorage.getItem('darkMode') === 'true';
  
  // Check for system preference if no saved preference
  const systemPrefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
  
  // Set initial state
  if (darkModeEnabled || (systemPrefersDark && localStorage.getItem('darkMode') === null)) {
    enableDarkMode();
  } else {
    disableDarkMode();
  }
  
  // Add event listener for toggle button
  const darkModeToggle = document.getElementById('dark-mode-toggle');
  if (darkModeToggle) {
    darkModeToggle.addEventListener('click', toggleDarkMode);
    
    // Update toggle button state
    updateToggleButton(darkModeEnabled || (systemPrefersDark && localStorage.getItem('darkMode') === null));
  }
  
  // Listen for system preference changes
  if (window.matchMedia) {
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
      // Only apply system preference if user hasn't set a preference
      if (localStorage.getItem('darkMode') === null) {
        if (e.matches) {
          enableDarkMode();
        } else {
          disableDarkMode();
        }
        
        // Update toggle button state
        if (darkModeToggle) {
          updateToggleButton(e.matches);
        }
      }
    });
  }
}

// Toggle dark mode
function toggleDarkMode() {
  if (document.documentElement.classList.contains('dark-mode')) {
    disableDarkMode();
    localStorage.setItem('darkMode', 'false');
    updateToggleButton(false);
  } else {
    enableDarkMode();
    localStorage.setItem('darkMode', 'true');
    updateToggleButton(true);
  }
}

// Enable dark mode
function enableDarkMode() {
  document.documentElement.classList.add('dark-mode');
  document.body.classList.add('dark-mode');
  
  // Dispatch event for other components to react
  document.dispatchEvent(new CustomEvent('darkmode-changed', { detail: { darkMode: true } }));
}

// Disable dark mode
function disableDarkMode() {
  document.documentElement.classList.remove('dark-mode');
  document.body.classList.remove('dark-mode');
  
  // Dispatch event for other components to react
  document.dispatchEvent(new CustomEvent('darkmode-changed', { detail: { darkMode: false } }));
}

// Update toggle button state
function updateToggleButton(isDark) {
  const darkModeToggle = document.getElementById('dark-mode-toggle');
  if (!darkModeToggle) return;
  
  const sunIcon = darkModeToggle.querySelector('.sun-icon');
  const moonIcon = darkModeToggle.querySelector('.moon-icon');
  
  if (isDark) {
    darkModeToggle.setAttribute('aria-pressed', 'true');
    darkModeToggle.setAttribute('title', 'Switch to light mode');
    
    if (sunIcon) sunIcon.classList.add('hidden');
    if (moonIcon) moonIcon.classList.remove('hidden');
  } else {
    darkModeToggle.setAttribute('aria-pressed', 'false');
    darkModeToggle.setAttribute('title', 'Switch to dark mode');
    
    if (sunIcon) sunIcon.classList.remove('hidden');
    if (moonIcon) moonIcon.classList.add('hidden');
  }
}

// Export the function for use in other modules
export default initDarkMode;