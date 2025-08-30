/**
 * Dark Mode Module
 * 
 * Handles the dark mode functionality for the theme.
 */

const DarkMode = {
  /**
   * Initialize the dark mode functionality
   */
  init() {
    this.cacheDOM();
    this.bindEvents();
    this.checkPreference();
  },

  /**
   * Cache DOM elements
   */
  cacheDOM() {
    this.toggleButtons = document.querySelectorAll('.dark-mode-toggle');
    this.html = document.documentElement;
    this.body = document.body;
  },

  /**
   * Bind events
   */
  bindEvents() {
    // Toggle dark mode when clicking the toggle button
    this.toggleButtons.forEach(button => {
      button.addEventListener('click', () => this.toggle());
    });

    // Listen for system preference changes
    if (window.matchMedia) {
      const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
      
      // Add change listener (modern browsers)
      if (mediaQuery.addEventListener) {
        mediaQuery.addEventListener('change', e => {
          // Only apply if user hasn't manually set preference
          if (!localStorage.getItem('aqualuxe-dark-mode')) {
            this.setMode(e.matches);
          }
        });
      } else if (mediaQuery.addListener) {
        // Fallback for older browsers
        mediaQuery.addListener(e => {
          // Only apply if user hasn't manually set preference
          if (!localStorage.getItem('aqualuxe-dark-mode')) {
            this.setMode(e.matches);
          }
        });
      }
    }

    // Custom event for other scripts to listen for dark mode changes
    document.addEventListener('aqualuxe:dark-mode-changed', event => {
      // Additional actions when dark mode changes
      // This can be used by other scripts to respond to dark mode changes
    });
  },

  /**
   * Check user preference for dark mode
   */
  checkPreference() {
    // Check for saved preference
    const savedPreference = localStorage.getItem('aqualuxe-dark-mode');
    
    if (savedPreference !== null) {
      // Apply saved preference
      this.setMode(savedPreference === 'true');
    } else {
      // Check system preference
      if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        this.setMode(true);
      } else {
        this.setMode(false);
      }
    }
  },

  /**
   * Toggle dark mode
   */
  toggle() {
    const isDarkMode = this.body.classList.contains('dark-mode');
    this.setMode(!isDarkMode);
  },

  /**
   * Set dark mode state
   * 
   * @param {boolean} enable - Whether to enable dark mode
   */
  setMode(enable) {
    if (enable) {
      this.body.classList.add('dark-mode');
      this.html.classList.add('dark');
      localStorage.setItem('aqualuxe-dark-mode', 'true');
    } else {
      this.body.classList.remove('dark-mode');
      this.html.classList.remove('dark');
      localStorage.setItem('aqualuxe-dark-mode', 'false');
    }

    // Update toggle button state
    this.toggleButtons.forEach(button => {
      button.setAttribute('aria-pressed', enable ? 'true' : 'false');
    });

    // Dispatch custom event
    document.dispatchEvent(
      new CustomEvent('aqualuxe:dark-mode-changed', {
        detail: { darkMode: enable }
      })
    );
  }
};

export default DarkMode;