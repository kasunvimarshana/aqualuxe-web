/**
 * AquaLuxe Theme Customizer JavaScript
 * This file handles real-time preview for the WordPress Customizer
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
      // Update custom properties
      document.documentElement.style.setProperty('--color-primary-500', to);
      
      // Generate color variations
      const primaryColor = to;
      const primaryRgb = hexToRgb(primaryColor);
      
      if (primaryRgb) {
        // Generate lighter and darker shades
        const primaryLight = lightenDarkenColor(primaryColor, 40);
        const primaryLighter = lightenDarkenColor(primaryColor, 80);
        const primaryLightest = lightenDarkenColor(primaryColor, 120);
        const primaryDark = lightenDarkenColor(primaryColor, -40);
        const primaryDarker = lightenDarkenColor(primaryColor, -80);
        const primaryDarkest = lightenDarkenColor(primaryColor, -120);
        
        // Update custom properties
        document.documentElement.style.setProperty('--color-primary-50', primaryLightest);
        document.documentElement.style.setProperty('--color-primary-100', primaryLighter);
        document.documentElement.style.setProperty('--color-primary-200', primaryLight);
        document.documentElement.style.setProperty('--color-primary-300', lightenDarkenColor(primaryColor, 20));
        document.documentElement.style.setProperty('--color-primary-400', lightenDarkenColor(primaryColor, 10));
        document.documentElement.style.setProperty('--color-primary-600', lightenDarkenColor(primaryColor, -20));
        document.documentElement.style.setProperty('--color-primary-700', primaryDark);
        document.documentElement.style.setProperty('--color-primary-800', primaryDarker);
        document.documentElement.style.setProperty('--color-primary-900', primaryDarkest);
      }
    });
  });
  
  // Secondary color
  wp.customize('aqualuxe_secondary_color', function(value) {
    value.bind(function(to) {
      // Update custom properties
      document.documentElement.style.setProperty('--color-secondary-500', to);
      
      // Generate color variations
      const secondaryColor = to;
      const secondaryRgb = hexToRgb(secondaryColor);
      
      if (secondaryRgb) {
        // Generate lighter and darker shades
        const secondaryLight = lightenDarkenColor(secondaryColor, 40);
        const secondaryLighter = lightenDarkenColor(secondaryColor, 80);
        const secondaryLightest = lightenDarkenColor(secondaryColor, 120);
        const secondaryDark = lightenDarkenColor(secondaryColor, -40);
        const secondaryDarker = lightenDarkenColor(secondaryColor, -80);
        const secondaryDarkest = lightenDarkenColor(secondaryColor, -120);
        
        // Update custom properties
        document.documentElement.style.setProperty('--color-secondary-50', secondaryLightest);
        document.documentElement.style.setProperty('--color-secondary-100', secondaryLighter);
        document.documentElement.style.setProperty('--color-secondary-200', secondaryLight);
        document.documentElement.style.setProperty('--color-secondary-300', lightenDarkenColor(secondaryColor, 20));
        document.documentElement.style.setProperty('--color-secondary-400', lightenDarkenColor(secondaryColor, 10));
        document.documentElement.style.setProperty('--color-secondary-600', lightenDarkenColor(secondaryColor, -20));
        document.documentElement.style.setProperty('--color-secondary-700', secondaryDark);
        document.documentElement.style.setProperty('--color-secondary-800', secondaryDarker);
        document.documentElement.style.setProperty('--color-secondary-900', secondaryDarkest);
      }
    });
  });
  
  // Accent color
  wp.customize('aqualuxe_accent_color', function(value) {
    value.bind(function(to) {
      // Update custom properties
      document.documentElement.style.setProperty('--color-accent-500', to);
      
      // Generate color variations
      const accentColor = to;
      const accentRgb = hexToRgb(accentColor);
      
      if (accentRgb) {
        // Generate lighter and darker shades
        const accentLight = lightenDarkenColor(accentColor, 40);
        const accentLighter = lightenDarkenColor(accentColor, 80);
        const accentLightest = lightenDarkenColor(accentColor, 120);
        const accentDark = lightenDarkenColor(accentColor, -40);
        const accentDarker = lightenDarkenColor(accentColor, -80);
        const accentDarkest = lightenDarkenColor(accentColor, -120);
        
        // Update custom properties
        document.documentElement.style.setProperty('--color-accent-50', accentLightest);
        document.documentElement.style.setProperty('--color-accent-100', accentLighter);
        document.documentElement.style.setProperty('--color-accent-200', accentLight);
        document.documentElement.style.setProperty('--color-accent-300', lightenDarkenColor(accentColor, 20));
        document.documentElement.style.setProperty('--color-accent-400', lightenDarkenColor(accentColor, 10));
        document.documentElement.style.setProperty('--color-accent-600', lightenDarkenColor(accentColor, -20));
        document.documentElement.style.setProperty('--color-accent-700', accentDark);
        document.documentElement.style.setProperty('--color-accent-800', accentDarker);
        document.documentElement.style.setProperty('--color-accent-900', accentDarkest);
      }
    });
  });
  
  // Container width
  wp.customize('aqualuxe_container_width', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--container-width', to + 'px');
    });
  });
  
  // Font family
  wp.customize('aqualuxe_body_font', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--font-family-sans', to);
    });
  });
  
  wp.customize('aqualuxe_heading_font', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--font-family-serif', to);
    });
  });
  
  // Footer text
  wp.customize('aqualuxe_footer_text', function(value) {
    value.bind(function(to) {
      $('.site-info').html(to);
    });
  });
  
  // Logo size
  wp.customize('aqualuxe_logo_size', function(value) {
    value.bind(function(to) {
      $('.custom-logo-link img').css('max-width', to + 'px');
    });
  });
  
  // Helper function to convert hex to RGB
  function hexToRgb(hex) {
    const shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
    hex = hex.replace(shorthandRegex, function(m, r, g, b) {
      return r + r + g + g + b + b;
    });
    
    const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
      r: parseInt(result[1], 16),
      g: parseInt(result[2], 16),
      b: parseInt(result[3], 16)
    } : null;
  }
  
  // Helper function to lighten or darken a color
  function lightenDarkenColor(color, amount) {
    let usePound = false;
    
    if (color[0] === '#') {
      color = color.slice(1);
      usePound = true;
    }
    
    const num = parseInt(color, 16);
    
    let r = (num >> 16) + amount;
    if (r > 255) r = 255;
    else if (r < 0) r = 0;
    
    let g = ((num >> 8) & 0x00FF) + amount;
    if (g > 255) g = 255;
    else if (g < 0) g = 0;
    
    let b = (num & 0x0000FF) + amount;
    if (b > 255) b = 255;
    else if (b < 0) b = 0;
    
    return (usePound ? '#' : '') + (g | (b << 8) | (r << 16)).toString(16).padStart(6, '0');
  }
})(jQuery);