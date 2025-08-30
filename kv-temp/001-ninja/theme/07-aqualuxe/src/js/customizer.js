/**
 * AquaLuxe Theme Customizer JavaScript
 * 
 * This file handles live preview functionality for the WordPress Customizer.
 */

(function($) {
  'use strict';
  
  // Site title and description
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
  
  // Header text color
  wp.customize('header_textcolor', function(value) {
    value.bind(function(to) {
      if ('blank' === to) {
        $('.site-title, .site-description').css({
          clip: 'rect(1px, 1px, 1px, 1px)',
          position: 'absolute'
        });
      } else {
        $('.site-title, .site-description').css({
          clip: 'auto',
          position: 'relative'
        });
        $('.site-title a, .site-description').css({
          color: to
        });
      }
    });
  });
  
  // Primary color
  wp.customize('aqualuxe_primary_color', function(value) {
    value.bind(function(to) {
      // Update CSS variables
      document.documentElement.style.setProperty('--color-primary', to);
      
      // Generate different shades
      const lighten = (color, percent) => {
        const num = parseInt(color.replace('#', ''), 16),
              amt = Math.round(2.55 * percent),
              R = (num >> 16) + amt,
              G = (num >> 8 & 0x00FF) + amt,
              B = (num & 0x0000FF) + amt;
        return '#' + (0x1000000 + (R < 255 ? (R < 1 ? 0 : R) : 255) * 0x10000 + (G < 255 ? (G < 1 ? 0 : G) : 255) * 0x100 + (B < 255 ? (B < 1 ? 0 : B) : 255)).toString(16).slice(1);
      };
      
      const darken = (color, percent) => {
        const num = parseInt(color.replace('#', ''), 16),
              amt = Math.round(2.55 * percent),
              R = (num >> 16) - amt,
              G = (num >> 8 & 0x00FF) - amt,
              B = (num & 0x0000FF) - amt;
        return '#' + (0x1000000 + (R < 255 ? (R < 1 ? 0 : R) : 255) * 0x10000 + (G < 255 ? (G < 1 ? 0 : G) : 255) * 0x100 + (B < 255 ? (B < 1 ? 0 : B) : 255)).toString(16).slice(1);
      };
      
      // Set different shades as CSS variables
      document.documentElement.style.setProperty('--color-primary-light', lighten(to, 20));
      document.documentElement.style.setProperty('--color-primary-lighter', lighten(to, 40));
      document.documentElement.style.setProperty('--color-primary-dark', darken(to, 20));
      document.documentElement.style.setProperty('--color-primary-darker', darken(to, 40));
    });
  });
  
  // Secondary color
  wp.customize('aqualuxe_secondary_color', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--color-secondary', to);
    });
  });
  
  // Accent color
  wp.customize('aqualuxe_accent_color', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--color-accent', to);
    });
  });
  
  // Text color
  wp.customize('aqualuxe_text_color', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--color-text', to);
    });
  });
  
  // Background color
  wp.customize('aqualuxe_background_color', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--color-background', to);
    });
  });
  
  // Header style
  wp.customize('aqualuxe_header_style', function(value) {
    value.bind(function(to) {
      // Remove all header style classes
      $('header.site-header').removeClass('header-default header-centered header-split header-minimal');
      
      // Add selected style class
      $('header.site-header').addClass('header-' + to);
    });
  });
  
  // Footer columns
  wp.customize('aqualuxe_footer_columns', function(value) {
    value.bind(function(to) {
      const footer = $('.footer-widgets');
      
      // Remove all column classes
      footer.removeClass('grid-cols-1 grid-cols-2 grid-cols-3 grid-cols-4');
      
      // Add new column class
      footer.addClass('grid-cols-' + to);
    });
  });
  
  // Container width
  wp.customize('aqualuxe_container_width', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--container-width', to + 'px');
    });
  });
  
  // Typography - Body font
  wp.customize('aqualuxe_body_font', function(value) {
    value.bind(function(to) {
      // Check if the font is already loaded
      if (!document.getElementById('google-font-' + to.replace(' ', '-').toLowerCase())) {
        // Load Google Font
        const link = document.createElement('link');
        link.id = 'google-font-' + to.replace(' ', '-').toLowerCase();
        link.href = 'https://fonts.googleapis.com/css2?family=' + to.replace(' ', '+') + ':wght@400;500;600;700&display=swap';
        link.rel = 'stylesheet';
        document.head.appendChild(link);
      }
      
      // Apply font to body
      document.body.style.fontFamily = "'" + to + "', sans-serif";
    });
  });
  
  // Typography - Heading font
  wp.customize('aqualuxe_heading_font', function(value) {
    value.bind(function(to) {
      // Check if the font is already loaded
      if (!document.getElementById('google-font-' + to.replace(' ', '-').toLowerCase())) {
        // Load Google Font
        const link = document.createElement('link');
        link.id = 'google-font-' + to.replace(' ', '-').toLowerCase();
        link.href = 'https://fonts.googleapis.com/css2?family=' + to.replace(' ', '+') + ':wght@400;500;600;700&display=swap';
        link.rel = 'stylesheet';
        document.head.appendChild(link);
      }
      
      // Apply font to headings
      const headings = document.querySelectorAll('h1, h2, h3, h4, h5, h6');
      headings.forEach(heading => {
        heading.style.fontFamily = "'" + to + "', serif";
      });
    });
  });
  
  // Shop layout
  wp.customize('aqualuxe_shop_layout', function(value) {
    value.bind(function(to) {
      // Remove all shop layout classes
      $('.woocommerce .products').removeClass('grid-layout list-layout mixed-layout');
      
      // Add selected layout class
      $('.woocommerce .products').addClass(to + '-layout');
    });
  });
  
  // Product columns
  wp.customize('aqualuxe_product_columns', function(value) {
    value.bind(function(to) {
      // Remove all column classes
      $('.woocommerce .products').removeClass('grid-cols-2 grid-cols-3 grid-cols-4 grid-cols-5');
      
      // Add new column class
      $('.woocommerce .products').addClass('grid-cols-' + to);
    });
  });
  
  // Hero section height
  wp.customize('aqualuxe_hero_height', function(value) {
    value.bind(function(to) {
      $('.hero-section').css('height', to + 'px');
    });
  });
  
  // Hero overlay opacity
  wp.customize('aqualuxe_hero_overlay_opacity', function(value) {
    value.bind(function(to) {
      $('.hero-overlay').css('opacity', to);
    });
  });
  
  // Logo size
  wp.customize('aqualuxe_logo_size', function(value) {
    value.bind(function(to) {
      $('.site-logo img').css('max-height', to + 'px');
    });
  });
  
  // Button border radius
  wp.customize('aqualuxe_button_radius', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--button-radius', to + 'px');
    });
  });
  
  // Card border radius
  wp.customize('aqualuxe_card_radius', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--card-radius', to + 'px');
    });
  });
  
  // Enable/disable dark mode toggle
  wp.customize('aqualuxe_enable_dark_mode', function(value) {
    value.bind(function(to) {
      if (to) {
        $('.dark-mode-toggle-wrapper').removeClass('hidden');
      } else {
        $('.dark-mode-toggle-wrapper').addClass('hidden');
      }
    });
  });
  
  // Enable/disable multilingual support
  wp.customize('aqualuxe_enable_multilingual', function(value) {
    value.bind(function(to) {
      if (to) {
        $('.language-switcher').removeClass('hidden');
      } else {
        $('.language-switcher').addClass('hidden');
      }
    });
  });
  
})(jQuery);