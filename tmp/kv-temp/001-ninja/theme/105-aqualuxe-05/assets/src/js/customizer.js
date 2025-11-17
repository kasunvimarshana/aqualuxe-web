/**
 * Theme Customizer Controls
 * Handles customizer interface and live preview functionality
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function ($) {
  'use strict';

  /**
   * Customizer Handler
   */
  class CustomizerHandler {
    constructor() {
      this.api = wp.customize;
      this.init();
    }

    /**
     * Initialize customizer functionality
     */
    init() {
      this.setupColorControls();
      this.setupTypographyControls();
      this.setupLayoutControls();
      this.setupCustomControls();
    }

    /**
     * Setup color controls
     */
    setupColorControls() {
      // Primary color
      this.api('primary_color', value => {
        value.bind(to => {
          this.updateCustomProperty('--color-primary', to);
        });
      });

      // Secondary color
      this.api('secondary_color', value => {
        value.bind(to => {
          this.updateCustomProperty('--color-secondary', to);
        });
      });

      // Accent color
      this.api('accent_color', value => {
        value.bind(to => {
          this.updateCustomProperty('--color-accent', to);
        });
      });
    }

    /**
     * Setup typography controls
     */
    setupTypographyControls() {
      // Heading font
      this.api('heading_font', value => {
        value.bind(to => {
          this.updateFontFamily('--font-heading', to);
        });
      });

      // Body font
      this.api('body_font', value => {
        value.bind(to => {
          this.updateFontFamily('--font-body', to);
        });
      });

      // Font sizes
      this.api('font_size_base', value => {
        value.bind(to => {
          this.updateCustomProperty('--font-size-base', to + 'px');
        });
      });
    }

    /**
     * Setup layout controls
     */
    setupLayoutControls() {
      // Container width
      this.api('container_width', value => {
        value.bind(to => {
          this.updateCustomProperty('--container-width', to + 'px');
        });
      });

      // Header layout
      this.api('header_layout', value => {
        value.bind(to => {
          document.body.className = document.body.className.replace(
            /header-layout-\w+/g,
            ''
          );
          document.body.classList.add('header-layout-' + to);
        });
      });

      // Sidebar position
      this.api('sidebar_position', value => {
        value.bind(to => {
          document.body.className = document.body.className.replace(
            /sidebar-\w+/g,
            ''
          );
          document.body.classList.add('sidebar-' + to);
        });
      });
    }

    /**
     * Setup custom controls
     */
    setupCustomControls() {
      // Dark mode toggle
      this.api('enable_dark_mode', value => {
        value.bind(to => {
          if (to) {
            document.body.classList.add('dark-mode-enabled');
          } else {
            document.body.classList.remove('dark-mode-enabled');
          }
        });
      });

      // Animation enable/disable
      this.api('enable_animations', value => {
        value.bind(to => {
          if (to) {
            document.body.classList.remove('no-animations');
          } else {
            document.body.classList.add('no-animations');
          }
        });
      });

      // Logo max height
      this.api('logo_max_height', value => {
        value.bind(to => {
          this.updateCustomProperty('--logo-max-height', to + 'px');
        });
      });
    }

    /**
     * Update CSS custom property
     * @param {string} property
     * @param {string} value
     */
    updateCustomProperty(property, value) {
      document.documentElement.style.setProperty(property, value);
    }

    /**
     * Update font family
     * @param {string} property
     * @param {string} fontFamily
     */
    updateFontFamily(property, fontFamily) {
      // Load Google Font if needed
      if (fontFamily && !this.isFontLoaded(fontFamily)) {
        this.loadGoogleFont(fontFamily);
      }

      this.updateCustomProperty(property, fontFamily);
    }

    /**
     * Check if font is already loaded
     * @param {string} fontFamily
     * @returns {boolean}
     */
    isFontLoaded(fontFamily) {
      const existingLink = document.querySelector(
        `link[href*="${fontFamily.replace(' ', '+')}"]`
      );
      return !!existingLink;
    }

    /**
     * Load Google Font
     * @param {string} fontFamily
     */
    loadGoogleFont(fontFamily) {
      const link = document.createElement('link');
      link.rel = 'stylesheet';
      link.href = `https://fonts.googleapis.com/css2?family=${fontFamily.replace(' ', '+')}:wght@300;400;500;600;700&display=swap`;
      document.head.appendChild(link);
    }
  }

  // Initialize when customizer is ready
  $(document).ready(() => {
    if (typeof wp !== 'undefined' && wp.customize) {
      new CustomizerHandler();
    }
  });
})(jQuery);
