/**
 * Multilingual Module
 * 
 * Handles multilingual functionality for the theme
 */

(function($) {
  'use strict';

  const AquaLuxeMultilingual = {
    /**
     * Initialize multilingual
     */
    init: function() {
      this.initLanguageSwitcher();
      this.initLanguageRedirection();
      this.initLanguageSpecificContent();
    },

    /**
     * Initialize language switcher
     */
    initLanguageSwitcher: function() {
      // Language switcher dropdown
      const $languageSwitcher = $('.aqualuxe-language-switcher--dropdown');
      
      if ($languageSwitcher.length) {
        const $toggle = $languageSwitcher.find('.aqualuxe-language-switcher__toggle');
        const $dropdown = $languageSwitcher.find('.aqualuxe-language-switcher__list');
        
        // Toggle dropdown
        $toggle.on('click', function(e) {
          e.preventDefault();
          
          const expanded = $toggle.attr('aria-expanded') === 'true';
          
          $toggle.attr('aria-expanded', !expanded);
          $dropdown.attr('hidden', expanded);
          
          // Close dropdown when clicking outside
          if (!expanded) {
            $(document).on('click.language-switcher', function(event) {
              if (!$languageSwitcher.is(event.target) && $languageSwitcher.has(event.target).length === 0) {
                $toggle.attr('aria-expanded', 'false');
                $dropdown.attr('hidden', true);
                $(document).off('click.language-switcher');
              }
            });
          }
        });
        
        // Close dropdown on escape key
        $dropdown.on('keydown', function(event) {
          if (event.key === 'Escape') {
            $toggle.attr('aria-expanded', 'false');
            $dropdown.attr('hidden', true);
            $toggle.focus();
          }
        });
        
        // Keyboard navigation
        $dropdown.on('keydown', function(event) {
          const $items = $dropdown.find('.aqualuxe-language-switcher__item');
          const currentIndex = $items.index(document.activeElement);
          
          if (event.key === 'ArrowDown') {
            event.preventDefault();
            if (currentIndex < $items.length - 1) {
              $items.eq(currentIndex + 1).focus();
            } else {
              $items.eq(0).focus();
            }
          } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            if (currentIndex > 0) {
              $items.eq(currentIndex - 1).focus();
            } else {
              $items.eq($items.length - 1).focus();
            }
          } else if (event.key === 'Home') {
            event.preventDefault();
            $items.eq(0).focus();
          } else if (event.key === 'End') {
            event.preventDefault();
            $items.eq($items.length - 1).focus();
          }
        });
      }
    },

    /**
     * Initialize language redirection
     */
    initLanguageRedirection: function() {
      // Add loading class when switching languages
      $('.aqualuxe-language-switcher__item, .aqualuxe-footer-language-switcher__item').on('click', 'a', function() {
        // Add loading class to body
        $('body').addClass('language-switching');
        
        // Store scroll position
        const scrollPosition = window.scrollY;
        sessionStorage.setItem('aqualuxe_scroll_position', scrollPosition);
      });
      
      // Restore scroll position after language change
      if (sessionStorage.getItem('aqualuxe_scroll_position')) {
        const scrollPosition = parseInt(sessionStorage.getItem('aqualuxe_scroll_position'), 10);
        window.scrollTo(0, scrollPosition);
        sessionStorage.removeItem('aqualuxe_scroll_position');
      }
      
      // Browser language detection and redirection
      if (aqualuxe_multilingual && aqualuxe_multilingual.auto_redirect) {
        // Check if user has already been redirected
        if (!sessionStorage.getItem('aqualuxe_language_redirected')) {
          const browserLanguage = navigator.language.split('-')[0];
          const availableLanguages = aqualuxe_multilingual.languages || {};
          const currentLanguage = aqualuxe_multilingual.current_language;
          
          // Redirect if browser language is available and different from current language
          if (availableLanguages[browserLanguage] && browserLanguage !== currentLanguage) {
            sessionStorage.setItem('aqualuxe_language_redirected', 'true');
            window.location.href = availableLanguages[browserLanguage];
          }
        }
      }
    },

    /**
     * Initialize language specific content
     */
    initLanguageSpecificContent: function() {
      // Get current language
      const currentLanguage = $('html').attr('lang') || document.documentElement.lang;
      
      if (currentLanguage) {
        // Add language class to body
        $('body').addClass('lang-' + currentLanguage);
        
        // Show/hide language-specific content
        $('[data-language]').each(function() {
          const $element = $(this);
          const language = $element.data('language');
          
          if (language && language !== currentLanguage) {
            $element.hide();
          }
        });
      }
      
      // Handle RTL languages
      const isRTL = $('html').attr('dir') === 'rtl';
      if (isRTL) {
        $('body').addClass('rtl');
      }
    },

    /**
     * Get URL with language parameter
     * 
     * @param {string} url - The URL to modify
     * @param {string} language - The language code
     * @return {string} - The modified URL
     */
    getUrlWithLanguage: function(url, language) {
      // Check if URL already has query parameters
      const hasQuery = url.indexOf('?') !== -1;
      
      // Add language parameter
      if (hasQuery) {
        // Check if URL already has language parameter
        if (url.indexOf('lang=') !== -1) {
          // Replace language parameter
          return url.replace(/lang=[^&]+/, 'lang=' + language);
        } else {
          // Add language parameter
          return url + '&lang=' + language;
        }
      } else {
        // Add language parameter
        return url + '?lang=' + language;
      }
    },

    /**
     * Get current language
     * 
     * @return {string} - The current language code
     */
    getCurrentLanguage: function() {
      return $('html').attr('lang') || document.documentElement.lang || 'en';
    },

    /**
     * Check if language is RTL
     * 
     * @param {string} language - The language code
     * @return {boolean} - Whether the language is RTL
     */
    isRTL: function(language) {
      const rtlLanguages = ['ar', 'fa', 'he', 'ur'];
      return rtlLanguages.includes(language);
    }
  };

  // Initialize on document ready
  $(document).ready(function() {
    AquaLuxeMultilingual.init();
  });

  // Export to global scope
  window.AquaLuxeMultilingual = AquaLuxeMultilingual;

})(jQuery);