/**
 * AquaLuxe Theme - Customizer Header Preview
 *
 * This file handles the JavaScript functionality for live previewing header changes in the WordPress Customizer.
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

    // Header layout
    wp.customize('aqualuxe_header_layout', function(value) {
        value.bind(function(to) {
            $('body')
                .removeClass('header-standard header-centered header-split header-transparent')
                .addClass('header-' + to);
        });
    });

    // Header width
    wp.customize('aqualuxe_header_width', function(value) {
        value.bind(function(to) {
            $('.site-header > div')
                .removeClass('container container-fluid')
                .addClass(to === 'contained' ? 'container' : 'container-fluid');
        });
    });

    // Header background color
    wp.customize('aqualuxe_header_background', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--header-background', to);
        });
    });

    // Header text color
    wp.customize('aqualuxe_header_text_color', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--header-text-color', to);
        });
    });

    // Header sticky
    wp.customize('aqualuxe_header_sticky', function(value) {
        value.bind(function(to) {
            if (to) {
                $('.site-header').addClass('sticky');
            } else {
                $('.site-header').removeClass('sticky');
            }
        });
    });

    // Header transparent
    wp.customize('aqualuxe_header_transparent', function(value) {
        value.bind(function(to) {
            if (to) {
                $('.site-header').addClass('transparent-header');
            } else {
                $('.site-header').removeClass('transparent-header');
            }
        });
    });

    // Header shadow
    wp.customize('aqualuxe_header_shadow', function(value) {
        value.bind(function(to) {
            if (to) {
                $('.site-header').addClass('shadow-sm');
            } else {
                $('.site-header').removeClass('shadow-sm');
            }
        });
    });

    // Header border
    wp.customize('aqualuxe_header_border', function(value) {
        value.bind(function(to) {
            if (to) {
                $('.site-header').addClass('border-bottom');
            } else {
                $('.site-header').removeClass('border-bottom');
            }
        });
    });

})(jQuery);