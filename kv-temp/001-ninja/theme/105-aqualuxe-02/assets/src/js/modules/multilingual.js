/**
 * AquaLuxe Multilingual Module JavaScript
 *
 * Handles language switching and multilingual functionality
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function ($) {
  'use strict';

  /**
   * Multilingual Module Object
   */
  const AquaLuxeMultilingual = {
    /**
     * Initialize the module
     */
    init: function () {
      this.bindEvents();
      this.initLanguageSwitcher();
      this.detectSystemPreference();
    },

    /**
     * Bind events
     */
    bindEvents: function () {
      $(document).on(
        'click',
        '.language-toggle',
        this.toggleLanguageDropdown.bind(this)
      );
      $(document).on(
        'click',
        '.language-option',
        this.handleLanguageSwitch.bind(this)
      );
      $(document).on('click', function (e) {
        if (!$(e.target).closest('.aqualuxe-language-switcher').length) {
          $('.aqualuxe-language-switcher').removeClass('open');
        }
      });
    },

    /**
     * Initialize language switcher
     */
    initLanguageSwitcher: function () {
      const switcher = $('.aqualuxe-language-switcher');

      if (switcher.length) {
        // Add keyboard support
        switcher.find('.language-toggle').on('keydown', function (e) {
          if (e.keyCode === 13 || e.keyCode === 32) {
            // Enter or Space
            e.preventDefault();
            $(this).click();
          }
        });

        // Add ARIA attributes
        switcher.find('.language-toggle').attr({
          'aria-haspopup': 'true',
          'aria-expanded': 'false',
          role: 'button',
          tabindex: '0',
        });
      }
    },

    /**
     * Toggle language dropdown
     */
    toggleLanguageDropdown: function (e) {
      e.preventDefault();
      e.stopPropagation();

      const switcher = $('.aqualuxe-language-switcher');
      const isOpen = switcher.hasClass('open');

      switcher.toggleClass('open');

      // Update ARIA attributes
      switcher.find('.language-toggle').attr('aria-expanded', !isOpen);
    },

    /**
     * Handle language switch
     */
    handleLanguageSwitch: function (e) {
      e.preventDefault();

      const option = $(e.target).closest('.language-option');
      const newLanguage = option.data('language');

      // Send AJAX request to switch language
      $.ajax({
        url: window.aqualuxeMultilingual.ajax_url,
        type: 'POST',
        data: {
          action: 'aqualuxe_switch_language',
          language: newLanguage,
          nonce: window.aqualuxeMultilingual.nonce,
        },
        success: function (response) {
          if (response.success) {
            window.location.href = response.data.redirect_url;
          }
        },
      });
    },

    /**
     * Detect system language preference
     */
    detectSystemPreference: function () {
      if (
        !window.aqualuxeMultilingual ||
        !window.aqualuxeMultilingual.languages
      ) {
        return;
      }

      const browserLang = navigator.language || navigator.userLanguage;
      const langCode = browserLang.substring(0, 2);
      const supportedLanguages = Object.keys(
        window.aqualuxeMultilingual.languages
      );

      // Auto-switch if supported and no preference set
      if (
        supportedLanguages.includes(langCode) &&
        langCode !== window.aqualuxeMultilingual.current_language &&
        !document.cookie.includes('aqualuxe_language=')
      ) {
        this.switchLanguage(langCode);
      }
    },

    /**
     * Switch to a specific language
     */
    switchLanguage: function (language) {
      const url = new URL(window.location.href);
      url.searchParams.set('lang', language);
      window.location.href = url.toString();
    },
  };

  /**
   * Initialize when document is ready
   */
  $(document).ready(function () {
    AquaLuxeMultilingual.init();
  });

  // Expose to global scope
  window.AquaLuxeMultilingual = AquaLuxeMultilingual;
})(jQuery);
