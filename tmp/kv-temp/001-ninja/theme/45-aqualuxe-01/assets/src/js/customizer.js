/**
 * AquaLuxe Theme Customizer JavaScript
 * 
 * This file contains JavaScript functionality for the WordPress Customizer.
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
      // Update custom property
      document.documentElement.style.setProperty('--color-primary', to);
      
      // Update inline CSS
      let style = $('#aqualuxe-primary-color-css');
      if (style.length === 0) {
        style = $('<style id="aqualuxe-primary-color-css"></style>').appendTo('head');
      }
      
      style.html(`
        .bg-primary { background-color: ${to}; }
        .text-primary { color: ${to}; }
        .border-primary { border-color: ${to}; }
      `);
    });
  });
  
  // Secondary color
  wp.customize('aqualuxe_secondary_color', function(value) {
    value.bind(function(to) {
      // Update custom property
      document.documentElement.style.setProperty('--color-secondary', to);
      
      // Update inline CSS
      let style = $('#aqualuxe-secondary-color-css');
      if (style.length === 0) {
        style = $('<style id="aqualuxe-secondary-color-css"></style>').appendTo('head');
      }
      
      style.html(`
        .bg-secondary { background-color: ${to}; }
        .text-secondary { color: ${to}; }
        .border-secondary { border-color: ${to}; }
      `);
    });
  });
  
  // Accent color
  wp.customize('aqualuxe_accent_color', function(value) {
    value.bind(function(to) {
      // Update custom property
      document.documentElement.style.setProperty('--color-accent', to);
      
      // Update inline CSS
      let style = $('#aqualuxe-accent-color-css');
      if (style.length === 0) {
        style = $('<style id="aqualuxe-accent-color-css"></style>').appendTo('head');
      }
      
      style.html(`
        .bg-accent { background-color: ${to}; }
        .text-accent { color: ${to}; }
        .border-accent { border-color: ${to}; }
      `);
    });
  });
  
  // Logo size
  wp.customize('aqualuxe_logo_size', function(value) {
    value.bind(function(to) {
      $('.custom-logo-link img').css('max-width', to + 'px');
    });
  });
  
  // Container width
  wp.customize('aqualuxe_container_width', function(value) {
    value.bind(function(to) {
      // Update custom property
      document.documentElement.style.setProperty('--container-max-width', to + 'px');
      
      // Update inline CSS
      let style = $('#aqualuxe-container-width-css');
      if (style.length === 0) {
        style = $('<style id="aqualuxe-container-width-css"></style>').appendTo('head');
      }
      
      style.html(`
        .container {
          max-width: ${to}px;
        }
      `);
    });
  });
  
  // Header style
  wp.customize('aqualuxe_header_style', function(value) {
    value.bind(function(to) {
      // Remove all header style classes
      $('header.site-header').removeClass('header-default header-centered header-split header-minimal');
      
      // Add the selected header style class
      $('header.site-header').addClass('header-' + to);
    });
  });
  
  // Footer columns
  wp.customize('aqualuxe_footer_columns', function(value) {
    value.bind(function(to) {
      // Remove all column classes
      $('.footer-widgets').removeClass('grid-cols-1 grid-cols-2 grid-cols-3 grid-cols-4');
      
      // Add the selected column class
      $('.footer-widgets').addClass('grid-cols-' + to);
    });
  });
  
  // Show/hide elements
  const toggleElements = [
    'aqualuxe_show_topbar',
    'aqualuxe_show_search',
    'aqualuxe_show_breadcrumbs',
    'aqualuxe_show_related_posts',
    'aqualuxe_show_author_bio',
    'aqualuxe_show_post_nav'
  ];
  
  toggleElements.forEach(function(setting) {
    wp.customize(setting, function(value) {
      value.bind(function(to) {
        const element = setting.replace('aqualuxe_show_', '.');
        if (to) {
          $(element).removeClass('hidden');
        } else {
          $(element).addClass('hidden');
        }
      });
    });
  });
  
  // Typography
  wp.customize('aqualuxe_body_font', function(value) {
    value.bind(function(to) {
      // Update inline CSS
      let style = $('#aqualuxe-body-font-css');
      if (style.length === 0) {
        style = $('<style id="aqualuxe-body-font-css"></style>').appendTo('head');
      }
      
      style.html(`
        body {
          font-family: "${to}", var(--font-sans);
        }
      `);
    });
  });
  
  wp.customize('aqualuxe_heading_font', function(value) {
    value.bind(function(to) {
      // Update inline CSS
      let style = $('#aqualuxe-heading-font-css');
      if (style.length === 0) {
        style = $('<style id="aqualuxe-heading-font-css"></style>').appendTo('head');
      }
      
      style.html(`
        h1, h2, h3, h4, h5, h6 {
          font-family: "${to}", var(--font-serif);
        }
      `);
    });
  });
  
  // Base font size
  wp.customize('aqualuxe_base_font_size', function(value) {
    value.bind(function(to) {
      // Update inline CSS
      let style = $('#aqualuxe-base-font-size-css');
      if (style.length === 0) {
        style = $('<style id="aqualuxe-base-font-size-css"></style>').appendTo('head');
      }
      
      style.html(`
        html {
          font-size: ${to}px;
        }
      `);
    });
  });
  
})(jQuery);