/**
 * Dark Mode Module JavaScript
 *
 * Handles dark mode toggle functionality with persistence
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function ($) {
  'use strict';

  const AquaLuxeDarkMode = {
    /**
     * Initialize dark mode functionality
     */
    init() {
      this.bindEvents();
      this.syncWithSystemPreference();
      this.updateToggleStates();
    },

    /**
     * Bind event handlers
     */
    bindEvents() {
      // Handle toggle button clicks
      $(document).on('click', '[data-dark-mode-toggle]', e => {
        e.preventDefault();
        this.toggle();
      });

      // Listen for system preference changes
      if (window.matchMedia) {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        mediaQuery.addEventListener('change', () => {
          this.syncWithSystemPreference();
        });
      }

      // Keyboard shortcut (Ctrl/Cmd + Shift + D)
      $(document).on('keydown', e => {
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'D') {
          e.preventDefault();
          this.toggle();
        }
      });
    },

    /**
     * Toggle dark mode
     */
    toggle() {
      const isDark = document.documentElement.classList.contains('dark');
      this.setDarkMode(!isDark);
    },

    /**
     * Set dark mode state
     */
    setDarkMode(enabled) {
      const html = document.documentElement;
      const body = document.body;

      if (enabled) {
        html.classList.add('dark');
        body.classList.add('dark-mode-enabled');
        localStorage.setItem('aqualuxe_dark_mode', 'enabled');
      } else {
        html.classList.remove('dark');
        body.classList.remove('dark-mode-enabled');
        localStorage.setItem('aqualuxe_dark_mode', 'disabled');
      }

      // Sync with server via AJAX
      this.syncWithServer(enabled);

      // Update toggle button states
      this.updateToggleStates();

      // Dispatch custom event
      $(document).trigger('aqualuxe:darkModeChanged', [enabled]);

      // Update meta theme-color for mobile browsers
      this.updateThemeColor(enabled);

      // Announce change to screen readers
      this.announceChange(enabled);
    },

    /**
     * Sync dark mode preference with server
     */
    syncWithServer(enabled) {
      if (typeof aqualuxe_dark_mode === 'undefined') {
        return;
      }

      $.ajax({
        url: aqualuxe_dark_mode.ajax_url,
        type: 'POST',
        data: {
          action: 'aqualuxe_toggle_dark_mode',
          nonce: aqualuxe_dark_mode.nonce,
          enabled: enabled ? 'true' : 'false',
        },
        success: response => {
          if (response.success) {
            // Dark mode preference saved successfully
          }
        },
        error: () => {
          // Failed to save dark mode preference - handle silently
        },
      });
    },

    /**
     * Sync with system preference if no manual preference set
     */
    syncWithSystemPreference() {
      const savedPreference = localStorage.getItem('aqualuxe_dark_mode');

      if (!savedPreference && window.matchMedia) {
        const prefersDark = window.matchMedia(
          '(prefers-color-scheme: dark)'
        ).matches;
        this.setDarkMode(prefersDark);
      }
    },

    /**
     * Update toggle button states
     */
    updateToggleStates() {
      const isDark = document.documentElement.classList.contains('dark');
      const toggles = document.querySelectorAll('[data-dark-mode-toggle]');

      toggles.forEach(toggle => {
        const sunIcon = toggle.querySelector('svg:not(.dark\\:hidden)');
        const moonIcon = toggle.querySelector('svg.dark\\:hidden');

        if (isDark) {
          toggle.setAttribute('aria-pressed', 'true');
          toggle.setAttribute('aria-label', 'Switch to light mode');
          if (sunIcon) {
            sunIcon.style.display = 'block';
          }
          if (moonIcon) {
            moonIcon.style.display = 'none';
          }
        } else {
          toggle.setAttribute('aria-pressed', 'false');
          toggle.setAttribute('aria-label', 'Switch to dark mode');
          if (sunIcon) {
            sunIcon.style.display = 'none';
          }
          if (moonIcon) {
            moonIcon.style.display = 'block';
          }
        }
      });
    },

    /**
     * Update theme color meta tag for mobile browsers
     */
    updateThemeColor(isDark) {
      let themeColorMeta = document.querySelector('meta[name="theme-color"]');

      if (!themeColorMeta) {
        themeColorMeta = document.createElement('meta');
        themeColorMeta.name = 'theme-color';
        document.head.appendChild(themeColorMeta);
      }

      themeColorMeta.content = isDark ? '#1A202C' : '#006B7D';
    },

    /**
     * Announce dark mode change to screen readers
     */
    announceChange(enabled) {
      const announcement = document.createElement('div');
      announcement.setAttribute('aria-live', 'polite');
      announcement.setAttribute('aria-atomic', 'true');
      announcement.className = 'sr-only';
      announcement.textContent = enabled
        ? 'Dark mode enabled'
        : 'Light mode enabled';

      document.body.appendChild(announcement);

      // Remove announcement after brief delay
      setTimeout(() => {
        document.body.removeChild(announcement);
      }, 1000);
    },

    /**
     * Get current dark mode state
     */
    isDarkMode() {
      return document.documentElement.classList.contains('dark');
    },

    /**
     * Add transition classes to prevent flash
     */
    enableTransitions() {
      document.documentElement.classList.add('color-transition');

      // Remove after transition completes
      setTimeout(() => {
        document.documentElement.classList.remove('color-transition');
      }, 300);
    },
  };

  // Initialize when DOM is ready
  $(document).ready(() => {
    AquaLuxeDarkMode.init();
  });

  // Make available globally
  window.AquaLuxeDarkMode = AquaLuxeDarkMode;
})(jQuery);
