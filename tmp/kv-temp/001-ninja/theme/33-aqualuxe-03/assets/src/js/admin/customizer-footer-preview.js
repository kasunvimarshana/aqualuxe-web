/**
 * AquaLuxe Theme - Customizer Footer Preview
 *
 * This file handles the JavaScript functionality for live previewing footer changes in the WordPress Customizer.
 */

(function($) {
    'use strict';

    // Footer layout
    wp.customize('aqualuxe_footer_layout', function(value) {
        value.bind(function(to) {
            $('body')
                .removeClass('footer-1 footer-2 footer-3 footer-4 footer-5 footer-6 footer-custom')
                .addClass('footer-' + to);
        });
    });

    // Footer width
    wp.customize('aqualuxe_footer_width', function(value) {
        value.bind(function(to) {
            $('.site-footer > div')
                .removeClass('container container-fluid')
                .addClass(to === 'contained' ? 'container' : 'container-fluid');
        });
    });

    // Footer background color
    wp.customize('aqualuxe_footer_background', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--footer-background', to);
        });
    });

    // Footer text color
    wp.customize('aqualuxe_footer_text_color', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--footer-text-color', to);
        });
    });

    // Footer bottom layout
    wp.customize('aqualuxe_footer_bottom_layout', function(value) {
        value.bind(function(to) {
            $('.site-info')
                .removeClass('flex-column flex-md-row justify-content-between items-center')
                .addClass(to === 'centered' ? 'flex-column text-center' : 'flex-column flex-md-row justify-content-between items-center');
        });
    });

    // Copyright text
    wp.customize('aqualuxe_copyright_text', function(value) {
        value.bind(function(to) {
            $('.copyright').html(to);
        });
    });

    // Footer border
    wp.customize('aqualuxe_footer_border', function(value) {
        value.bind(function(to) {
            if (to) {
                $('.site-footer').addClass('border-top');
            } else {
                $('.site-footer').removeClass('border-top');
            }
        });
    });

    // Footer widgets
    wp.customize('aqualuxe_footer_widgets', function(value) {
        value.bind(function(to) {
            if (to) {
                $('.footer-widgets').removeClass('d-none');
            } else {
                $('.footer-widgets').addClass('d-none');
            }
        });
    });

    // Footer bottom
    wp.customize('aqualuxe_footer_bottom', function(value) {
        value.bind(function(to) {
            if (to) {
                $('.site-info').removeClass('d-none');
            } else {
                $('.site-info').addClass('d-none');
            }
        });
    });

})(jQuery);