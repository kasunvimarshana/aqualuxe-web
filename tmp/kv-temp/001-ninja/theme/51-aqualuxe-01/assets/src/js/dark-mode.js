/**
 * AquaLuxe Theme Dark Mode JavaScript
 *
 * This file contains the dark mode functionality for the AquaLuxe theme.
 */

(function($) {
  'use strict';

  /**
   * Dark Mode functionality
   */
  const DarkMode = {
    /**
     * Initialize dark mode
     */
    init: function() {
      this.setupDarkModeToggle();
      this.setupSystemPreference();
      this.loadPreference();
    },

    /**
     * Setup dark mode toggle
     */
    setupDarkModeToggle: function() {
      const self = this;
      const $darkModeToggle = $('.dark-mode-toggle');

      $darkModeToggle.on('click', function() {
        self.toggleDarkMode();
      });
    },

    /**
     * Setup system preference detection
     */
    setupSystemPreference: function() {
      const self = this;
      const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

      // Check if user has a stored preference
      const hasStoredPreference = localStorage.getItem('aqualuxe_dark_mode') !== null;

      // If no stored preference, use system preference
      if (!hasStoredPreference) {
        if (darkModeMediaQuery.matches) {
          self.enableDarkMode(false);
        } else {
          self.disableDarkMode(false);
        }
      }

      // Listen for changes in system preference
      darkModeMediaQuery.addEventListener('change', function(e) {
        // Only apply system preference if user hasn't set a preference
        if (!hasStoredPreference) {
          if (e.matches) {
            self.enableDarkMode(false);
          } else {
            self.disableDarkMode(false);
          }
        }
      });
    },

    /**
     * Load user preference
     */
    loadPreference: function() {
      const darkModeEnabled = localStorage.getItem('aqualuxe_dark_mode') === 'true';
      
      if (darkModeEnabled) {
        this.enableDarkMode(false);
      } else if (localStorage.getItem('aqualuxe_dark_mode') === 'false') {
        this.disableDarkMode(false);
      }
    },

    /**
     * Toggle dark mode
     */
    toggleDarkMode: function() {
      const isDarkMode = $('body').hasClass('dark-mode');
      
      if (isDarkMode) {
        this.disableDarkMode(true);
      } else {
        this.enableDarkMode(true);
      }
    },

    /**
     * Enable dark mode
     * 
     * @param {boolean} savePreference Whether to save the preference
     */
    enableDarkMode: function(savePreference = true) {
      // Add dark mode class to body
      $('body').addClass('dark-mode');
      
      // Update toggle button
      $('.dark-mode-toggle').attr('aria-checked', 'true');
      $('.dark-mode-toggle-dot').removeClass('dark-mode-toggle-dot-light').addClass('dark-mode-toggle-dot-dark');
      
      // Update toggle text if available
      $('.dark-mode-label').text(typeof aqualuxeSettings !== 'undefined' ? aqualuxeSettings.i18n.lightMode : 'Light Mode');
      
      // Save preference if requested
      if (savePreference) {
        localStorage.setItem('aqualuxe_dark_mode', 'true');
        
        // Set cookie for server-side detection
        document.cookie = 'aqualuxe_dark_mode=true; path=/; max-age=31536000';
      }
      
      // Trigger event
      $(document).trigger('aqualuxe:darkmode:enabled');
    },

    /**
     * Disable dark mode
     * 
     * @param {boolean} savePreference Whether to save the preference
     */
    disableDarkMode: function(savePreference = true) {
      // Remove dark mode class from body
      $('body').removeClass('dark-mode');
      
      // Update toggle button
      $('.dark-mode-toggle').attr('aria-checked', 'false');
      $('.dark-mode-toggle-dot').removeClass('dark-mode-toggle-dot-dark').addClass('dark-mode-toggle-dot-light');
      
      // Update toggle text if available
      $('.dark-mode-label').text(typeof aqualuxeSettings !== 'undefined' ? aqualuxeSettings.i18n.darkMode : 'Dark Mode');
      
      // Save preference if requested
      if (savePreference) {
        localStorage.setItem('aqualuxe_dark_mode', 'false');
        
        // Set cookie for server-side detection
        document.cookie = 'aqualuxe_dark_mode=false; path=/; max-age=31536000';
      }
      
      // Trigger event
      $(document).trigger('aqualuxe:darkmode:disabled');
    }
  };

  // Initialize Dark Mode when DOM is ready
  $(document).ready(function() {
    DarkMode.init();
  });

  // Expose Dark Mode to global scope
  if (typeof window.AquaLuxe === 'undefined') {
    window.AquaLuxe = {};
  }
  
  window.AquaLuxe.DarkMode = DarkMode;

})(jQuery);