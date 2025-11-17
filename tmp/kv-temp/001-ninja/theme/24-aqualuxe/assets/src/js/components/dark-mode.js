/**
 * Dark mode functionality
 * 
 * Handles theme switching between light and dark modes with persistent preferences
 */

export default (function() {
  // Initialize dark mode when DOM is ready
  document.addEventListener('DOMContentLoaded', function() {
    initDarkMode();
  });

  /**
   * Initialize dark mode functionality
   */
  function initDarkMode() {
    // Get theme toggle button and icons
    const themeToggleBtn = document.getElementById('theme-toggle');
    const darkIcon = document.getElementById('theme-toggle-dark-icon');
    const lightIcon = document.getElementById('theme-toggle-light-icon');
    
    if (!themeToggleBtn) {
      return;
    }
    
    // Set initial theme based on preference
    const currentTheme = getThemePreference();
    setTheme(currentTheme);
    
    // Toggle theme when button is clicked
    themeToggleBtn.addEventListener('click', function() {
      const isDark = document.documentElement.classList.contains('dark');
      setTheme(isDark ? 'light' : 'dark');
    });
    
    // Listen for system theme changes
    if (window.matchMedia) {
      window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
        // Only change theme if user hasn't manually set a preference
        if (!hasStoredPreference()) {
          setTheme(event.matches ? 'dark' : 'light');
        }
      });
    }
    
    /**
     * Get theme preference from cookie or system preference
     * 
     * @return {string} 'dark' or 'light'
     */
    function getThemePreference() {
      // Check if theme preference is stored in cookie
      const cookieName = window.aqualuxeDarkMode?.cookieName || 'aqualuxe_color_scheme';
      const cookies = document.cookie.split(';');
      let storedTheme = null;
      
      // Look for our theme cookie
      for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i].trim();
        if (cookie.indexOf(cookieName + '=') === 0) {
          storedTheme = cookie.substring(cookieName.length + 1);
          break;
        }
      }
      
      // If theme preference exists in cookie, return it
      if (storedTheme) {
        return storedTheme;
      }
      
      // If no cookie, check for system preference
      if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        return 'dark';
      }
      
      // Default to theme setting or light
      return window.aqualuxeDarkMode?.defaultScheme || 'light';
    }
    
    /**
     * Check if user has stored a theme preference
     * 
     * @return {boolean} True if preference exists
     */
    function hasStoredPreference() {
      const cookieName = window.aqualuxeDarkMode?.cookieName || 'aqualuxe_color_scheme';
      const cookies = document.cookie.split(';');
      
      for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i].trim();
        if (cookie.indexOf(cookieName + '=') === 0) {
          return true;
        }
      }
      
      return false;
    }
    
    /**
     * Set theme and save preference
     * 
     * @param {string} theme 'dark' or 'light'
     */
    function setTheme(theme) {
      if (theme === 'dark') {
        document.documentElement.classList.add('dark');
        if (darkIcon) darkIcon.classList.remove('hidden');
        if (lightIcon) lightIcon.classList.add('hidden');
      } else {
        document.documentElement.classList.remove('dark');
        if (darkIcon) darkIcon.classList.add('hidden');
        if (lightIcon) lightIcon.classList.remove('hidden');
      }
      
      // Save theme preference to cookie
      const cookieName = window.aqualuxeDarkMode?.cookieName || 'aqualuxe_color_scheme';
      const expiryDays = window.aqualuxeDarkMode?.cookieExpiry || 365;
      const date = new Date();
      date.setTime(date.getTime() + (expiryDays * 24 * 60 * 60 * 1000));
      const expires = "; expires=" + date.toUTCString();
      document.cookie = cookieName + "=" + theme + expires + "; path=/; SameSite=Lax";
      
      // Dispatch event for other scripts
      const event = new CustomEvent('themeChanged', { detail: { theme } });
      document.dispatchEvent(event);
    }
  }
})();