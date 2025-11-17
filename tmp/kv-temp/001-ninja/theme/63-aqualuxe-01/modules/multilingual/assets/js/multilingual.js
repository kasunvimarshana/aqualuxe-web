/**
 * Multilingual module JavaScript
 */

(function($) {
  'use strict';
  
  // AquaLuxe namespace
  window.AquaLuxe = window.AquaLuxe || {};
  
  // DOM ready
  $(function() {
    AquaLuxe.multilingual.init();
  });
  
  /**
   * Multilingual functionality
   */
  AquaLuxe.multilingual = {
    /**
     * Initialize multilingual functionality
     */
    init: function() {
      this.setupLanguageSwitcher();
      this.setupRTLSupport();
      this.setupAjaxRequests();
    },
    
    /**
     * Setup language switcher
     */
    setupLanguageSwitcher: function() {
      const $languageSwitcher = $('.language-switcher');
      
      if (!$languageSwitcher.length) {
        return;
      }
      
      // Dropdown language switcher
      $languageSwitcher.filter('.dropdown').each(function() {
        const $dropdown = $(this);
        const $toggle = $dropdown.find('.language-switcher-toggle');
        const $menu = $dropdown.find('.language-switcher-dropdown');
        
        // Toggle dropdown on click
        $toggle.on('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          
          const isExpanded = $toggle.attr('aria-expanded') === 'true';
          
          $toggle.attr('aria-expanded', !isExpanded);
          $menu.toggleClass('show');
        });
        
        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
          if (!$dropdown.is(e.target) && $dropdown.has(e.target).length === 0) {
            $toggle.attr('aria-expanded', 'false');
            $menu.removeClass('show');
          }
        });
        
        // Close dropdown when pressing escape
        $(document).on('keyup', function(e) {
          if (e.key === 'Escape' && $menu.hasClass('show')) {
            $toggle.attr('aria-expanded', 'false');
            $menu.removeClass('show');
            $toggle.focus();
          }
        });
        
        // Handle keyboard navigation
        $menu.on('keydown', 'a', function(e) {
          const $links = $menu.find('a');
          const index = $links.index(this);
          
          // Arrow up
          if (e.key === 'ArrowUp') {
            e.preventDefault();
            const $prev = $links.eq(Math.max(0, index - 1));
            $prev.focus();
          }
          
          // Arrow down
          if (e.key === 'ArrowDown') {
            e.preventDefault();
            const $next = $links.eq(Math.min($links.length - 1, index + 1));
            $next.focus();
          }
          
          // Home
          if (e.key === 'Home') {
            e.preventDefault();
            $links.first().focus();
          }
          
          // End
          if (e.key === 'End') {
            e.preventDefault();
            $links.last().focus();
          }
          
          // Escape
          if (e.key === 'Escape') {
            e.preventDefault();
            $toggle.attr('aria-expanded', 'false');
            $menu.removeClass('show');
            $toggle.focus();
          }
        });
      });
      
      // Track language changes
      $languageSwitcher.find('a').on('click', function() {
        const language = $(this).attr('lang');
        
        // Set cookie for language preference
        AquaLuxe.multilingual.setCookie('aqualuxe_language', language, 30);
      });
    },
    
    /**
     * Setup RTL support
     */
    setupRTLSupport: function() {
      // Check if current language is RTL
      const isRTL = $('html').attr('dir') === 'rtl';
      
      if (isRTL) {
        // Add RTL class to body
        $('body').addClass('rtl');
        
        // Reverse flexbox directions
        $('.flex-row').addClass('flex-row-reverse');
        
        // Adjust margins and paddings
        $('.ml-auto').removeClass('ml-auto').addClass('mr-auto');
        $('.mr-auto').removeClass('mr-auto').addClass('ml-auto');
        
        // Adjust text alignments
        $('.text-left').removeClass('text-left').addClass('text-right');
        $('.text-right').removeClass('text-right').addClass('text-left');
      }
    },
    
    /**
     * Setup AJAX requests
     */
    setupAjaxRequests: function() {
      // Add language parameter to AJAX requests
      $(document).ajaxSend(function(event, xhr, settings) {
        // Skip if already has language parameter
        if (settings.url.indexOf('lang=') !== -1) {
          return;
        }
        
        // Add language parameter
        const language = aqualuxeMultilingual.currentLanguage;
        
        if (settings.url.indexOf('?') !== -1) {
          settings.url += '&lang=' + language;
        } else {
          settings.url += '?lang=' + language;
        }
      });
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