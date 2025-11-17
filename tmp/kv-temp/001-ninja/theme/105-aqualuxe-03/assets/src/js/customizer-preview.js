/**
 * Customizer Preview Handler
 * Handles live preview updates for theme customizer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function ($) {
  'use strict';

  // Ensure wp.customize is available
  if (typeof wp === 'undefined' || typeof wp.customize === 'undefined') {
    return;
  }

  /**
   * Live preview updates for customizer settings
   */
  wp.customize('blogname', function (value) {
    value.bind(function (to) {
      $('.site-title a').text(to);
    });
  });

  wp.customize('blogdescription', function (value) {
    value.bind(function (to) {
      $('.site-description').text(to);
    });
  });

  // Header text color
  wp.customize('header_textcolor', function (value) {
    value.bind(function (to) {
      if ('blank' === to) {
        $('.site-title, .site-description').css({
          clip: 'rect(1px, 1px, 1px, 1px)',
          position: 'absolute',
        });
      } else {
        $('.site-title, .site-description').css({
          clip: 'auto',
          position: 'relative',
          color: to,
        });
      }
    });
  });

  // Primary color
  wp.customize('aqualuxe_primary_color', function (value) {
    value.bind(function (to) {
      document.documentElement.style.setProperty('--color-primary', to);
    });
  });

  // Secondary color
  wp.customize('aqualuxe_secondary_color', function (value) {
    value.bind(function (to) {
      document.documentElement.style.setProperty('--color-secondary', to);
    });
  });

  // Accent color
  wp.customize('aqualuxe_accent_color', function (value) {
    value.bind(function (to) {
      document.documentElement.style.setProperty('--color-accent', to);
    });
  });

  // Typography
  wp.customize('aqualuxe_heading_font', function (value) {
    value.bind(function (to) {
      if (to) {
        loadGoogleFont(to);
        $('h1, h2, h3, h4, h5, h6').css('font-family', to);
      }
    });
  });

  wp.customize('aqualuxe_body_font', function (value) {
    value.bind(function (to) {
      if (to) {
        loadGoogleFont(to);
        $('body').css('font-family', to);
      }
    });
  });

  // Layout options
  wp.customize('aqualuxe_container_width', function (value) {
    value.bind(function (to) {
      document.documentElement.style.setProperty(
        '--container-width',
        to + 'px'
      );
    });
  });

  wp.customize('aqualuxe_header_layout', function (value) {
    value.bind(function (to) {
      $('body').removeClass(function (index, className) {
        return (className.match(/(^|\s)header-layout-\S+/g) || []).join(' ');
      });
      $('body').addClass('header-layout-' + to);
    });
  });

  // Dark mode
  wp.customize('aqualuxe_enable_dark_mode', function (value) {
    value.bind(function (to) {
      if (to) {
        $('body').addClass('dark-mode-enabled');
      } else {
        $('body').removeClass('dark-mode-enabled');
      }
    });
  });

  // Logo settings
  wp.customize('aqualuxe_logo_max_height', function (value) {
    value.bind(function (to) {
      $('.custom-logo').css('max-height', to + 'px');
    });
  });

  // Footer settings
  wp.customize('aqualuxe_footer_text', function (value) {
    value.bind(function (to) {
      $('.footer-text').html(to);
    });
  });

  wp.customize('aqualuxe_show_footer_widgets', function (value) {
    value.bind(function (to) {
      if (to) {
        $('.footer-widgets').show();
      } else {
        $('.footer-widgets').hide();
      }
    });
  });

  // Animation settings
  wp.customize('aqualuxe_enable_animations', function (value) {
    value.bind(function (to) {
      if (to) {
        $('body').removeClass('no-animations');
      } else {
        $('body').addClass('no-animations');
      }
    });
  });

  // Performance settings
  wp.customize('aqualuxe_lazy_loading', function (value) {
    value.bind(function (to) {
      if (to) {
        $('img').each(function () {
          if (!$(this).attr('loading')) {
            $(this).attr('loading', 'lazy');
          }
        });
      } else {
        $('img').removeAttr('loading');
      }
    });
  });

  /**
   * Load Google Font
   * @param {string} fontFamily
   */
  function loadGoogleFont(fontFamily) {
    if (!fontFamily) {
      return;
    }

    const fontName = fontFamily.replace(/\s+/g, '+');
    const fontId = 'google-font-' + fontName.toLowerCase();

    // Check if font is already loaded
    if ($('#' + fontId).length) {
      return;
    }

    // Load the font
    $('<link>')
      .attr('id', fontId)
      .attr('rel', 'stylesheet')
      .attr(
        'href',
        'https://fonts.googleapis.com/css2?family=' +
          fontName +
          ':wght@300;400;500;600;700&display=swap'
      )
      .appendTo('head');
  }

  // Initialize preview enhancements
  $(document).ready(function () {
    // Add smooth transitions for live preview
    $('body').addClass('customizer-preview-active');

    // Enhance contrast for better accessibility in preview
    $('*').css('transition', 'all 0.3s ease');
  });
})(jQuery);
