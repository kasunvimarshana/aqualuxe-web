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
      // Update CSS variables
      document.documentElement.style.setProperty('--color-primary-50', adjustColor(to, 0.9));
      document.documentElement.style.setProperty('--color-primary-100', adjustColor(to, 0.8));
      document.documentElement.style.setProperty('--color-primary-200', adjustColor(to, 0.6));
      document.documentElement.style.setProperty('--color-primary-300', adjustColor(to, 0.4));
      document.documentElement.style.setProperty('--color-primary-400', adjustColor(to, 0.2));
      document.documentElement.style.setProperty('--color-primary-500', to);
      document.documentElement.style.setProperty('--color-primary-600', adjustColor(to, -0.1));
      document.documentElement.style.setProperty('--color-primary-700', adjustColor(to, -0.2));
      document.documentElement.style.setProperty('--color-primary-800', adjustColor(to, -0.3));
      document.documentElement.style.setProperty('--color-primary-900', adjustColor(to, -0.4));
    });
  });

  // Secondary color.
  wp.customize('aqualuxe_secondary_color', function(value) {
    value.bind(function(to) {
      // Update CSS variables
      document.documentElement.style.setProperty('--color-secondary-50', adjustColor(to, 0.9));
      document.documentElement.style.setProperty('--color-secondary-100', adjustColor(to, 0.8));
      document.documentElement.style.setProperty('--color-secondary-200', adjustColor(to, 0.6));
      document.documentElement.style.setProperty('--color-secondary-300', adjustColor(to, 0.4));
      document.documentElement.style.setProperty('--color-secondary-400', adjustColor(to, 0.2));
      document.documentElement.style.setProperty('--color-secondary-500', to);
      document.documentElement.style.setProperty('--color-secondary-600', adjustColor(to, -0.1));
      document.documentElement.style.setProperty('--color-secondary-700', adjustColor(to, -0.2));
      document.documentElement.style.setProperty('--color-secondary-800', adjustColor(to, -0.3));
      document.documentElement.style.setProperty('--color-secondary-900', adjustColor(to, -0.4));
    });
  });

  // Top bar background color.
  wp.customize('aqualuxe_top_bar_bg_color', function(value) {
    value.bind(function(to) {
      $('.top-bar').css('background-color', to);
    });
  });

  // Top bar text color.
  wp.customize('aqualuxe_top_bar_text_color', function(value) {
    value.bind(function(to) {
      $('.top-bar, .top-bar a').css('color', to);
    });
  });

  // Header background color.
  wp.customize('aqualuxe_header_bg_color', function(value) {
    value.bind(function(to) {
      $('.site-header').css('background-color', to);
    });
  });

  // Header text color.
  wp.customize('aqualuxe_header_text_color', function(value) {
    value.bind(function(to) {
      $('.site-header, .site-header a:not(.btn)').css('color', to);
    });
  });

  // Footer background color.
  wp.customize('aqualuxe_footer_bg_color', function(value) {
    value.bind(function(to) {
      $('.site-footer').css('background-color', to);
    });
  });

  // Footer text color.
  wp.customize('aqualuxe_footer_text_color', function(value) {
    value.bind(function(to) {
      $('.site-footer, .site-footer a:not(.btn)').css('color', to);
    });
  });

  // Footer copyright text.
  wp.customize('aqualuxe_footer_copyright', function(value) {
    value.bind(function(to) {
      $('.site-info').html(to.replace('{year}', new Date().getFullYear()));
    });
  });

  // Contact phone.
  wp.customize('aqualuxe_contact_phone', function(value) {
    value.bind(function(to) {
      $('.top-bar-phone a').text(to);
      $('.top-bar-phone a').attr('href', 'tel:' + to.replace(/\s+/g, ''));
    });
  });

  // Contact email.
  wp.customize('aqualuxe_contact_email', function(value) {
    value.bind(function(to) {
      $('.top-bar-email a').text(to);
      $('.top-bar-email a').attr('href', 'mailto:' + to);
    });
  });

  // Social media links.
  wp.customize('aqualuxe_social_facebook', function(value) {
    value.bind(function(to) {
      updateSocialLink('facebook', to);
    });
  });

  wp.customize('aqualuxe_social_twitter', function(value) {
    value.bind(function(to) {
      updateSocialLink('twitter', to);
    });
  });

  wp.customize('aqualuxe_social_instagram', function(value) {
    value.bind(function(to) {
      updateSocialLink('instagram', to);
    });
  });

  wp.customize('aqualuxe_social_linkedin', function(value) {
    value.bind(function(to) {
      updateSocialLink('linkedin', to);
    });
  });

  wp.customize('aqualuxe_social_youtube', function(value) {
    value.bind(function(to) {
      updateSocialLink('youtube', to);
    });
  });

  wp.customize('aqualuxe_social_pinterest', function(value) {
    value.bind(function(to) {
      updateSocialLink('pinterest', to);
    });
  });

  // Layout options.
  wp.customize('aqualuxe_container_width', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--container-width', to + 'px');
    });
  });

  wp.customize('aqualuxe_sidebar_width', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--sidebar-width', to + '%');
    });
  });

  // Typography options.
  wp.customize('aqualuxe_body_font', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--font-body', to);
    });
  });

  wp.customize('aqualuxe_heading_font', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--font-heading', to);
    });
  });

  wp.customize('aqualuxe_base_font_size', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--font-size-base', to + 'px');
    });
  });

  // Helper function to update social media links.
  function updateSocialLink(platform, url) {
    const $link = $('.social-icon.' + platform);
    
    if (url) {
      if ($link.length) {
        $link.attr('href', url);
      } else {
        const $socialIcons = $('.social-icons');
        const icon = getSocialIcon(platform);
        $socialIcons.append('<a href="' + url + '" class="social-icon ' + platform + '" target="_blank" rel="noopener noreferrer">' + icon + '</a>');
      }
    } else {
      $link.remove();
    }
  }

  // Helper function to get social icon SVG.
  function getSocialIcon(platform) {
    switch (platform) {
      case 'facebook':
        return '<svg class="svg-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>';
      case 'twitter':
        return '<svg class="svg-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/></svg>';
      case 'instagram':
        return '<svg class="svg-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>';
      case 'linkedin':
        return '<svg class="svg-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>';
      case 'youtube':
        return '<svg class="svg-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M23.495 6.205a3.007 3.007 0 0 0-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 0 0 .527 6.205a31.247 31.247 0 0 0-.522 5.805 31.247 31.247 0 0 0 .522 5.783 3.007 3.007 0 0 0 2.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 0 0 2.088-2.088 31.247 31.247 0 0 0 .5-5.783 31.247 31.247 0 0 0-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/></svg>';
      case 'pinterest':
        return '<svg class="svg-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/></svg>';
      default:
        return '';
    }
  }

  // Helper function to adjust color brightness.
  function adjustColor(color, amount) {
    // Convert hex to RGB
    let r, g, b;
    
    if (color.startsWith('#')) {
      // Hex color
      const hex = color.slice(1);
      r = parseInt(hex.length === 3 ? hex.charAt(0) + hex.charAt(0) : hex.substring(0, 2), 16);
      g = parseInt(hex.length === 3 ? hex.charAt(1) + hex.charAt(1) : hex.substring(2, 4), 16);
      b = parseInt(hex.length === 3 ? hex.charAt(2) + hex.charAt(2) : hex.substring(4, 6), 16);
    } else if (color.startsWith('rgb')) {
      // RGB color
      const rgbValues = color.match(/\d+/g);
      r = parseInt(rgbValues[0]);
      g = parseInt(rgbValues[1]);
      b = parseInt(rgbValues[2]);
    } else {
      return color; // Return original color if format is not recognized
    }
    
    // Adjust brightness
    if (amount > 0) {
      // Lighten
      r = Math.min(255, Math.floor(r + (255 - r) * amount));
      g = Math.min(255, Math.floor(g + (255 - g) * amount));
      b = Math.min(255, Math.floor(b + (255 - b) * amount));
    } else {
      // Darken
      amount = Math.abs(amount);
      r = Math.max(0, Math.floor(r * (1 - amount)));
      g = Math.max(0, Math.floor(g * (1 - amount)));
      b = Math.max(0, Math.floor(b * (1 - amount)));
    }
    
    // Convert back to hex
    return '#' + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
  }

})(jQuery);