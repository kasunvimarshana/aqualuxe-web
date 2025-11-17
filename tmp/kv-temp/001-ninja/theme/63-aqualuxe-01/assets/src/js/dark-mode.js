/**
 * Dark mode functionality for AquaLuxe theme
 */

(function($) {
  'use strict';
  
  // AquaLuxe namespace
  window.AquaLuxe = window.AquaLuxe || {};
  
  // DOM ready
  $(function() {
    AquaLuxe.darkMode.init();
  });
  
  /**
   * Dark mode functionality
   */
  AquaLuxe.darkMode = {
    /**
     * Initialize dark mode
     */
    init: function() {
      this.setupToggle();
      this.setupSystemPreference();
    },
    
    /**
     * Setup dark mode toggle
     */
    setupToggle: function() {
      const self = this;
      const $toggle = $('.dark-mode-toggle-button');
      
      if (!$toggle.length) {
        return;
      }
      
      $toggle.on('click', function() {
        const isDarkMode = $('html').hasClass('dark-mode');
        self.toggleDarkMode(!isDarkMode);
      });
    },
    
    /**
     * Setup system preference detection
     */
    setupSystemPreference: function() {
      const self = this;
      
      // Check if we should respect system preference
      const respectSystemPreference = aqualuxeDarkMode.respectSystemPreference === '1';
      
      if (respectSystemPreference) {
        // Check if the user has a preference stored
        const hasUserPreference = this.getUserPreference() !== null;
        
        // If no user preference, use system preference
        if (!hasUserPreference && window.matchMedia) {
          const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
          self.toggleDarkMode(prefersDarkMode, false);
          
          // Listen for changes in system preference
          window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            // Only change if the user hasn't set a preference
            if (self.getUserPreference() === null) {
              self.toggleDarkMode(e.matches, false);
            }
          });
        }
      }
    },
    
    /**
     * Toggle dark mode
     * 
     * @param {boolean} enable Whether to enable dark mode
     * @param {boolean} savePreference Whether to save the preference (default: true)
     */
    toggleDarkMode: function(enable, savePreference = true) {
      const $html = $('html');
      const $toggle = $('.dark-mode-toggle-button');
      
      if (enable) {
        $html.addClass('dark-mode');
        $toggle.attr('aria-pressed', 'true');
        $toggle.find('i').removeClass('fa-moon').addClass('fa-sun');
        $toggle.find('.screen-reader-text').text(aqualuxeDarkMode.lightModeText);
      } else {
        $html.removeClass('dark-mode');
        $toggle.attr('aria-pressed', 'false');
        $toggle.find('i').removeClass('fa-sun').addClass('fa-moon');
        $toggle.find('.screen-reader-text').text(aqualuxeDarkMode.darkModeText);
      }
      
      // Save preference if requested
      if (savePreference) {
        this.savePreference(enable);
      }
      
      // Trigger custom event
      $(document).trigger('darkModeChanged', [enable]);
    },
    
    /**
     * Save user preference
     * 
     * @param {boolean} darkMode Whether dark mode is enabled
     */
    savePreference: function(darkMode) {
      // Save to cookie (for non-logged in users)
      this.setCookie('aqualuxe_dark_mode', darkMode ? 'true' : 'false', 365);
      
      // Save to user meta (for logged in users)
      if (aqualuxeDarkMode.isLoggedIn) {
        $.ajax({
          url: aqualuxeDarkMode.ajaxUrl,
          type: 'POST',
          data: {
            action: 'aqualuxe_save_dark_mode',
            dark_mode: darkMode ? 'true' : 'false',
            nonce: aqualuxeDarkMode.nonce
          }
        });
      }
    },
    
    /**
     * Get user preference
     * 
     * @return {boolean|null} User preference or null if not set
     */
    getUserPreference: function() {
      const cookieValue = this.getCookie('aqualuxe_dark_mode');
      
      if (cookieValue === 'true') {
        return true;
      } else if (cookieValue === 'false') {
        return false;
      }
      
      return null;
    },
    
    /**
     * Set cookie
     * 
     * @param {string} name Cookie name
     * @param {string} value Cookie value
     * @param {number} days Cookie expiration in days
     */
    setCookie: function(name, value, days) {
      let expires = '';
      
      if (days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = '; expires=' + date.toUTCString();
      }
      
      document.cookie = name + '=' + value + expires + '; path=/; SameSite=Lax';
    },
    
    /**
     * Get cookie
     * 
     * @param {string} name Cookie name
     * @return {string|null} Cookie value or null if not found
     */
    getCookie: function(name) {
      const nameEQ = name + '=';
      const ca = document.cookie.split(';');
      
      for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') {
          c = c.substring(1, c.length);
        }
        if (c.indexOf(nameEQ) === 0) {
          return c.substring(nameEQ.length, c.length);
        }
      }
      
      return null;
    }
  };
  
})(jQuery);