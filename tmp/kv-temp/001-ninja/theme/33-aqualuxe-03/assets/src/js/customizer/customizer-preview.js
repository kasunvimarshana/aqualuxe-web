/**
 * AquaLuxe Theme - Customizer Preview
 *
 * This file handles the JavaScript for the WordPress Customizer live preview.
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

  // Color settings
  const colorSettings = [
    'aqualuxe_primary_color',
    'aqualuxe_secondary_color',
    'aqualuxe_accent_color',
    'aqualuxe_text_color',
    'aqualuxe_heading_color',
    'aqualuxe_link_color',
    'aqualuxe_link_hover_color',
    'aqualuxe_background_color',
    'aqualuxe_header_background',
    'aqualuxe_header_text_color',
    'aqualuxe_footer_background',
    'aqualuxe_footer_text_color'
  ];

  colorSettings.forEach(function(setting) {
    wp.customize(setting, function(value) {
      value.bind(function(to) {
        // Create a style element if it doesn't exist
        let style = $('#aqualuxe-customizer-preview-styles');
        if (style.length === 0) {
          style = $('<style id="aqualuxe-customizer-preview-styles"></style>').appendTo('head');
        }

        // Update the CSS variables
        const cssVarName = setting.replace('aqualuxe_', '--');
        const css = `:root { ${cssVarName}: ${to}; }`;
        
        // Find and replace the specific variable in the style element
        let cssText = style.html();
        const regex = new RegExp(`:root\\s*{[^}]*${cssVarName}:[^;]*;[^}]*}`, 'g');
        
        if (regex.test(cssText)) {
          cssText = cssText.replace(regex, css);
        } else {
          cssText += css;
        }
        
        style.html(cssText);
      });
    });
  });

  // Typography settings
  wp.customize('aqualuxe_body_font_family', function(value) {
    value.bind(function(to) {
      // Create a style element if it doesn't exist
      let style = $('#aqualuxe-customizer-preview-styles');
      if (style.length === 0) {
        style = $('<style id="aqualuxe-customizer-preview-styles"></style>').appendTo('head');
      }

      // Update the CSS variables
      const css = `:root { --body-font-family: "${to}", sans-serif; }`;
      
      // Find and replace the specific variable in the style element
      let cssText = style.html();
      const regex = new RegExp(`:root\\s*{[^}]*--body-font-family:[^;]*;[^}]*}`, 'g');
      
      if (regex.test(cssText)) {
        cssText = cssText.replace(regex, css);
      } else {
        cssText += css;
      }
      
      style.html(cssText);
    });
  });

  wp.customize('aqualuxe_heading_font_family', function(value) {
    value.bind(function(to) {
      // Create a style element if it doesn't exist
      let style = $('#aqualuxe-customizer-preview-styles');
      if (style.length === 0) {
        style = $('<style id="aqualuxe-customizer-preview-styles"></style>').appendTo('head');
      }

      // Update the CSS variables
      const css = `:root { --heading-font-family: "${to}", serif; }`;
      
      // Find and replace the specific variable in the style element
      let cssText = style.html();
      const regex = new RegExp(`:root\\s*{[^}]*--heading-font-family:[^;]*;[^}]*}`, 'g');
      
      if (regex.test(cssText)) {
        cssText = cssText.replace(regex, css);
      } else {
        cssText += css;
      }
      
      style.html(cssText);
    });
  });

  // Layout settings
  wp.customize('aqualuxe_container_width', function(value) {
    value.bind(function(to) {
      // Create a style element if it doesn't exist
      let style = $('#aqualuxe-customizer-preview-styles');
      if (style.length === 0) {
        style = $('<style id="aqualuxe-customizer-preview-styles"></style>').appendTo('head');
      }

      // Update the CSS variables
      const css = `:root { --container-width: ${to}; }`;
      
      // Find and replace the specific variable in the style element
      let cssText = style.html();
      const regex = new RegExp(`:root\\s*{[^}]*--container-width:[^;]*;[^}]*}`, 'g');
      
      if (regex.test(cssText)) {
        cssText = cssText.replace(regex, css);
      } else {
        cssText += css;
      }
      
      style.html(cssText);
    });
  });

  // Footer copyright text
  wp.customize('aqualuxe_copyright_text', function(value) {
    value.bind(function(to) {
      $('.site-info').html(to);
    });
  });

  // Logo settings
  wp.customize('custom_logo', function(value) {
    value.bind(function(to) {
      if (to) {
        $.get(wp.ajax.settings.url, {
          action: 'aqualuxe_get_custom_logo',
          logo_id: to
        }, function(response) {
          if (response.success) {
            $('.custom-logo-link').html(response.data);
          }
        });
      } else {
        $('.custom-logo-link').empty();
      }
    });
  });
})(jQuery);