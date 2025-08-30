/**
 * AquaLuxe Theme Customizer JavaScript
 *
 * Handles real-time preview of customizer changes
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
                    'clip': 'rect(1px, 1px, 1px, 1px)',
                    'position': 'absolute'
                });
            } else {
                $('.site-title, .site-description').css({
                    'clip': 'auto',
                    'position': 'relative'
                });
                $('.site-title a, .site-description').css({
                    'color': to
                });
            }
        });
    });

    // Contact Information
    wp.customize('aqualuxe_phone_number', function(value) {
        value.bind(function(to) {
            $('.contact-phone').text(to);
        });
    });

    wp.customize('aqualuxe_email', function(value) {
        value.bind(function(to) {
            $('.contact-email').text(to);
            $('.contact-email').attr('href', 'mailto:' + to);
        });
    });

    wp.customize('aqualuxe_address', function(value) {
        value.bind(function(to) {
            $('.contact-address').text(to);
        });
    });

    // Footer Text
    wp.customize('aqualuxe_footer_text', function(value) {
        value.bind(function(to) {
            $('.site-info-custom').html(to);
        });
    });

    // Colors
    wp.customize('aqualuxe_primary_color', function(value) {
        value.bind(function(to) {
            // Update CSS variable
            document.documentElement.style.setProperty('--primary-color', to);
            
            // Update specific elements
            updateColorStyles('primary', to);
        });
    });

    wp.customize('aqualuxe_secondary_color', function(value) {
        value.bind(function(to) {
            // Update CSS variable
            document.documentElement.style.setProperty('--secondary-color', to);
            
            // Update specific elements
            updateColorStyles('secondary', to);
        });
    });

    wp.customize('aqualuxe_accent_color', function(value) {
        value.bind(function(to) {
            // Update CSS variable
            document.documentElement.style.setProperty('--accent-color', to);
            
            // Update specific elements
            updateColorStyles('accent', to);
        });
    });

    wp.customize('aqualuxe_text_color', function(value) {
        value.bind(function(to) {
            // Update CSS variable
            document.documentElement.style.setProperty('--text-color', to);
            
            // Update body text color
            $('body').css('color', to);
        });
    });

    // Typography
    wp.customize('aqualuxe_heading_font', function(value) {
        value.bind(function(to) {
            // This would typically require reloading the page to load the new font
            // But we can update the CSS variable for immediate feedback
            document.documentElement.style.setProperty('--heading-font', "'" + to + "', sans-serif");
        });
    });

    wp.customize('aqualuxe_body_font', function(value) {
        value.bind(function(to) {
            // This would typically require reloading the page to load the new font
            // But we can update the CSS variable for immediate feedback
            document.documentElement.style.setProperty('--body-font', "'" + to + "', sans-serif");
        });
    });

    // Helper function to update color styles
    function updateColorStyles(type, color) {
        // Background colors
        $('.bg-' + type).css('background-color', color);
        
        // Text colors
        $('.text-' + type).css('color', color);
        
        // Border colors
        $('.border-' + type).css('border-color', color);
        
        // Button styles
        if (type === 'primary' || type === 'secondary') {
            $('.btn-' + type).css({
                'background-color': color,
                'border-color': color
            });
        }
    }

})(jQuery);