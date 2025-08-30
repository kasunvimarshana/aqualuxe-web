/**
 * File dark-mode.js.
 *
 * Handles toggling dark mode and persisting the user's preference.
 */
(function() {
  // DOM elements
  const darkModeToggle = document.getElementById('dark-mode-toggle');
  const html = document.documentElement;
  
  // Check for saved user preference, if any
  const savedTheme = localStorage.getItem('aqualuxe-theme');
  const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  
  // Function to set theme
  const setTheme = (isDark) => {
    if (isDark) {
      html.classList.add('dark');
      if (darkModeToggle) {
        darkModeToggle.setAttribute('aria-checked', 'true');
      }
      localStorage.setItem('aqualuxe-theme', 'dark');
      document.cookie = 'aqualuxe_dark_mode=1; path=/; max-age=31536000'; // 1 year
    } else {
      html.classList.remove('dark');
      if (darkModeToggle) {
        darkModeToggle.setAttribute('aria-checked', 'false');
      }
      localStorage.setItem('aqualuxe-theme', 'light');
      document.cookie = 'aqualuxe_dark_mode=0; path=/; max-age=31536000'; // 1 year
    }
  };
  
  // Initialize theme based on saved preference or system preference
  if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
    setTheme(true);
  } else {
    setTheme(false);
  }
  
  // Toggle theme when the button is clicked
  if (darkModeToggle) {
    darkModeToggle.addEventListener('click', () => {
      const isDark = html.classList.contains('dark');
      setTheme(!isDark);
      
      // Send AJAX request to save preference in user meta if logged in
      if (typeof aqualuxeSettings !== 'undefined') {
        fetch(aqualuxeSettings.ajaxUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: new URLSearchParams({
            action: 'aqualuxe_save_dark_mode',
            nonce: aqualuxeSettings.nonce,
            dark_mode: !isDark ? '1' : '0',
          }),
        })
        .catch(error => console.error('Error saving dark mode preference:', error));
      }
    });
  }
  
  // Listen for system preference changes
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
    // Only change theme automatically if user hasn't set a preference
    if (!localStorage.getItem('aqualuxe-theme')) {
      setTheme(e.matches);
    }
  });
})();