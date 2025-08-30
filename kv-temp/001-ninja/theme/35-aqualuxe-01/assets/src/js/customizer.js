/**
 * AquaLuxe WordPress Theme
 * Customizer JavaScript File
 */

/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

(function($) {
  'use strict';

  // Site title and description.
  wp.customize('blogname', function(value) {
    value.bind(function(to) {
      $('.site-title a').text(to);
    });
  });

  wp.customize('blogdescription', function(value) {
    value.bind(function(to) {
      $('.site-description').text(to);
    });
  });

  // Header text color.
  wp.customize('header_textcolor', function(value) {
    value.bind(function(to) {
      if ('blank' === to) {
        $('.site-title, .site-description').css({
          clip: 'rect(1px, 1px, 1px, 1px)',
          position: 'absolute',
        });
      } else {
        $('.site-title, .site-description').css({
          clip: 'auto',
          position: 'relative',
        });
        $('.site-title a, .site-description').css({
          color: to,
        });
      }
    });
  });

  // Primary color.
  wp.customize('aqualuxe_primary_color', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--color-primary', to);
      
      // Generate color shades
      const shades = generateColorShades(to);
      
      // Set color shades as CSS variables
      for (const [shade, color] of Object.entries(shades)) {
        document.documentElement.style.setProperty(`--color-primary-${shade}`, color);
      }
    });
  });

  // Secondary color.
  wp.customize('aqualuxe_secondary_color', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--color-secondary', to);
      
      // Generate color shades
      const shades = generateColorShades(to);
      
      // Set color shades as CSS variables
      for (const [shade, color] of Object.entries(shades)) {
        document.documentElement.style.setProperty(`--color-secondary-${shade}`, color);
      }
    });
  });

  // Container width.
  wp.customize('aqualuxe_container_width', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--container-max-width', to + 'px');
    });
  });

  // Container padding.
  wp.customize('aqualuxe_container_padding', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--container-padding', to + 'px');
    });
  });

  // Font family.
  wp.customize('aqualuxe_font_family', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--font-family-sans', to);
    });
  });

  // Font size.
  wp.customize('aqualuxe_font_size', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--font-size-base', to + 'px');
    });
  });

  // Line height.
  wp.customize('aqualuxe_line_height', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--line-height-normal', to);
    });
  });

  // Header layout.
  wp.customize('aqualuxe_header_layout', function(value) {
    value.bind(function(to) {
      const header = $('.site-header');
      
      // Remove all layout classes
      header.removeClass('header-layout-default header-layout-centered header-layout-split');
      
      // Add selected layout class
      header.addClass('header-layout-' + to);
    });
  });

  // Footer widget columns.
  wp.customize('aqualuxe_footer_widget_columns', function(value) {
    value.bind(function(to) {
      const footerWidgets = $('.footer-widgets');
      
      // Remove all column classes
      footerWidgets.removeClass('footer-widgets-1 footer-widgets-2 footer-widgets-3 footer-widgets-4');
      
      // Add selected column class
      footerWidgets.addClass('footer-widgets-' + to);
    });
  });

  // WooCommerce shop columns.
  wp.customize('aqualuxe_shop_columns', function(value) {
    value.bind(function(to) {
      const products = $('.products');
      
      // Remove all column classes
      products.removeClass('columns-2 columns-3 columns-4 columns-5 columns-6');
      
      // Add selected column class
      products.addClass('columns-' + to);
    });
  });

  // WooCommerce products per page.
  wp.customize('aqualuxe_products_per_page', function(value) {
    value.bind(function(to) {
      // This requires a page refresh to take effect
    });
  });

  // Show/hide elements.
  const toggleElements = [
    'aqualuxe_show_search',
    'aqualuxe_show_cart',
    'aqualuxe_show_wishlist',
    'aqualuxe_show_account',
    'aqualuxe_show_breadcrumbs',
    'aqualuxe_show_page_title',
    'aqualuxe_show_featured_image',
    'aqualuxe_show_post_meta',
    'aqualuxe_show_related_posts',
    'aqualuxe_show_author_box',
    'aqualuxe_show_post_navigation',
    'aqualuxe_show_comments',
  ];

  toggleElements.forEach(function(setting) {
    wp.customize(setting, function(value) {
      value.bind(function(to) {
        const element = setting.replace('aqualuxe_show_', '');
        const selector = $('.' + element + '-element');
        
        if (to) {
          selector.removeClass('hidden');
        } else {
          selector.addClass('hidden');
        }
      });
    });
  });

  /**
   * Generate color shades
   * @param {string} hex - Hex color
   * @return {Object} - Object with color shades
   */
  function generateColorShades(hex) {
    // Convert hex to RGB
    const r = parseInt(hex.substring(1, 3), 16);
    const g = parseInt(hex.substring(3, 5), 16);
    const b = parseInt(hex.substring(5, 7), 16);
    
    // Generate shades
    const shades = {
      '50': lighten([r, g, b], 0.9),
      '100': lighten([r, g, b], 0.8),
      '200': lighten([r, g, b], 0.6),
      '300': lighten([r, g, b], 0.4),
      '400': lighten([r, g, b], 0.2),
      '500': hex,
      '600': darken([r, g, b], 0.1),
      '700': darken([r, g, b], 0.2),
      '800': darken([r, g, b], 0.3),
      '900': darken([r, g, b], 0.4),
    };
    
    return shades;
  }

  /**
   * Lighten color
   * @param {Array} rgb - RGB color
   * @param {number} amount - Amount to lighten
   * @return {string} - Hex color
   */
  function lighten(rgb, amount) {
    const [r, g, b] = rgb;
    const newR = Math.round(r + (255 - r) * amount);
    const newG = Math.round(g + (255 - g) * amount);
    const newB = Math.round(b + (255 - b) * amount);
    
    return rgbToHex(newR, newG, newB);
  }

  /**
   * Darken color
   * @param {Array} rgb - RGB color
   * @param {number} amount - Amount to darken
   * @return {string} - Hex color
   */
  function darken(rgb, amount) {
    const [r, g, b] = rgb;
    const newR = Math.round(r * (1 - amount));
    const newG = Math.round(g * (1 - amount));
    const newB = Math.round(b * (1 - amount));
    
    return rgbToHex(newR, newG, newB);
  }

  /**
   * Convert RGB to hex
   * @param {number} r - Red
   * @param {number} g - Green
   * @param {number} b - Blue
   * @return {string} - Hex color
   */
  function rgbToHex(r, g, b) {
    return '#' + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
  }

})(jQuery);