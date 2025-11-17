/**
 * Dark Mode Module
 * 
 * Handles dark mode functionality with persistent user preference
 */

(function($) {
  'use strict';

  const AquaLuxeDarkMode = {
    /**
     * Initialize dark mode
     */
    init: function() {
      // Check if Alpine.js is available
      if (typeof Alpine !== 'undefined') {
        this.initAlpineData();
      } else {
        this.initVanillaJS();
      }
      
      this.initAccessibility();
      this.initKeyboardShortcut();
    },

    /**
     * Initialize Alpine.js data
     */
    initAlpineData: function() {
      // Register Alpine data
      Alpine.data('darkMode', () => ({
        isDark: Alpine.$persist(this.getDefaultMode() === 'dark').as('aqualuxe_dark_mode'),
        
        init() {
          // Check for system preference if set to auto
          if (this.getDefaultMode() === 'auto' && !localStorage.getItem('_x_aqualuxe_dark_mode')) {
            this.isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
          }
          
          this.$watch('isDark', (value) => {
            this.updateDarkMode(value);
          });
          
          // Set initial class
          this.updateDarkMode(this.isDark);
          
          // Listen for system preference changes
          window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (this.getDefaultMode() === 'auto' && !localStorage.getItem('_x_aqualuxe_dark_mode')) {
              this.isDark = e.matches;
            }
          });
        },
        
        toggle() {
          this.isDark = !this.isDark;
        },
        
        getDefaultMode() {
          return aqualuxe_dark_mode ? aqualuxe_dark_mode.default_mode : 'auto';
        },
        
        updateDarkMode(isDark) {
          if (isDark) {
            document.documentElement.classList.add('dark');
            document.documentElement.classList.remove('light');
          } else {
            document.documentElement.classList.remove('dark');
            document.documentElement.classList.add('light');
          }
          
          // Update toggle button
          const toggleButton = document.querySelector('.aqualuxe-dark-mode-toggle__button');
          if (toggleButton) {
            toggleButton.setAttribute('aria-checked', isDark ? 'true' : 'false');
          }
        }
      }));
    },

    /**
     * Initialize vanilla JS implementation
     */
    initVanillaJS: function() {
      // Get default mode
      const defaultMode = this.getDefaultMode();
      
      // Get dark mode preference
      let isDark = localStorage.getItem('aqualuxe_dark_mode');
      
      // If no preference is set, use default mode
      if (isDark === null) {
        if (defaultMode === 'dark') {
          isDark = 'true';
        } else if (defaultMode === 'light') {
          isDark = 'false';
        } else {
          // Auto mode - use system preference
          isDark = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'true' : 'false';
        }
      }
      
      // Set initial dark mode
      this.updateDarkMode(isDark === 'true');
      
      // Toggle button click
      $(document).on('click', '.aqualuxe-dark-mode-toggle__button', function(e) {
        e.preventDefault();
        
        // Toggle dark mode
        const isDark = document.documentElement.classList.contains('dark');
        AquaLuxeDarkMode.updateDarkMode(!isDark);
        
        // Save preference
        localStorage.setItem('aqualuxe_dark_mode', !isDark ? 'true' : 'false');
      });
      
      // Listen for system preference changes
      window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (defaultMode === 'auto' && localStorage.getItem('aqualuxe_dark_mode') === null) {
          AquaLuxeDarkMode.updateDarkMode(e.matches);
        }
      });
    },

    /**
     * Initialize accessibility features
     */
    initAccessibility: function() {
      // Add accessibility attributes to toggle button
      const toggleButton = document.querySelector('.aqualuxe-dark-mode-toggle__button');
      if (toggleButton) {
        toggleButton.setAttribute('role', 'switch');
        toggleButton.setAttribute('aria-checked', document.documentElement.classList.contains('dark') ? 'true' : 'false');
      }
    },

    /**
     * Initialize keyboard shortcut
     */
    initKeyboardShortcut: function() {
      // Alt + D shortcut for dark mode toggle
      document.addEventListener('keydown', function(e) {
        if (e.altKey && e.key === 'd') {
          e.preventDefault();
          
          // Get current state
          const isDark = document.documentElement.classList.contains('dark');
          
          // Toggle dark mode
          AquaLuxeDarkMode.updateDarkMode(!isDark);
          
          // Save preference
          localStorage.setItem('aqualuxe_dark_mode', !isDark ? 'true' : 'false');
          
          // Update Alpine.js state if available
          if (typeof Alpine !== 'undefined') {
            const darkModeData = Alpine.store('darkMode');
            if (darkModeData) {
              darkModeData.isDark = !isDark;
            }
          }
        }
      });
    },

    /**
     * Get default mode
     * 
     * @return {string} Default mode (light, dark, auto)
     */
    getDefaultMode: function() {
      return aqualuxe_dark_mode ? aqualuxe_dark_mode.default_mode : 'auto';
    },

    /**
     * Update dark mode
     * 
     * @param {boolean} isDark - Whether dark mode is enabled
     */
    updateDarkMode: function(isDark) {
      if (isDark) {
        document.documentElement.classList.add('dark');
        document.documentElement.classList.remove('light');
      } else {
        document.documentElement.classList.remove('dark');
        document.documentElement.classList.add('light');
      }
      
      // Update toggle button
      const toggleButton = document.querySelector('.aqualuxe-dark-mode-toggle__button');
      if (toggleButton) {
        toggleButton.setAttribute('aria-checked', isDark ? 'true' : 'false');
      }
      
      // Dispatch event
      document.dispatchEvent(new CustomEvent('aqualuxe:darkModeChanged', {
        detail: { isDark }
      }));
    }
  };

  // Initialize on document ready
  $(document).ready(function() {
    AquaLuxeDarkMode.init();
  });

  // Export to global scope
  window.AquaLuxeDarkMode = AquaLuxeDarkMode;

})(jQuery);