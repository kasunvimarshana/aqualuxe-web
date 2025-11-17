/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
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

    // Primary color
    wp.customize('aqualuxe_primary_color', function(value) {
        value.bind(function(to) {
            $(':root').get(0).style.setProperty('--primary-color', to);
        });
    });

    // Secondary color
    wp.customize('aqualuxe_secondary_color', function(value) {
        value.bind(function(to) {
            $(':root').get(0).style.setProperty('--secondary-color', to);
        });
    });

    // Accent color
    wp.customize('aqualuxe_accent_color', function(value) {
        value.bind(function(to) {
            $(':root').get(0).style.setProperty('--accent-color', to);
        });
    });

    // Body font family
    wp.customize('aqualuxe_body_font_family', function(value) {
        value.bind(function(to) {
            $('body').css('font-family', to + ', sans-serif');
        });
    });

    // Body font size
    wp.customize('aqualuxe_body_font_size', function(value) {
        value.bind(function(to) {
            $('body').css('font-size', to + 'px');
        });
    });

    // Heading font family
    wp.customize('aqualuxe_heading_font_family', function(value) {
        value.bind(function(to) {
            $('h1, h2, h3, h4, h5, h6').css('font-family', to + ', serif');
        });
    });

    // Container width
    wp.customize('aqualuxe_container_width', function(value) {
        value.bind(function(to) {
            $('.container').css('max-width', to + 'px');
        });
    });

    // Footer text
    wp.customize('aqualuxe_footer_text', function(value) {
        value.bind(function(to) {
            $('.site-info .copyright p').text(to);
        });
    });

})(jQuery);