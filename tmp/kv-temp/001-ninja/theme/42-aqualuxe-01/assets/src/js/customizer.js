/**
 * AquaLuxe Theme Customizer JavaScript
 *
 * Handles the live preview functionality for the theme customizer.
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
            document.documentElement.style.setProperty('--primary-color', to);
        });
    });

    // Secondary color
    wp.customize('aqualuxe_secondary_color', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--secondary-color', to);
        });
    });

    // Accent color
    wp.customize('aqualuxe_accent_color', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--accent-color', to);
        });
    });

    // Container width
    wp.customize('aqualuxe_container_width', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--container-width', to + 'px');
        });
    });

    // Dark mode settings
    wp.customize('aqualuxe_enable_dark_mode', function(value) {
        value.bind(function(to) {
            if (to) {
                $('.dark-mode-toggle').removeClass('hidden');
            } else {
                $('.dark-mode-toggle').addClass('hidden');
            }
        });
    });

    wp.customize('aqualuxe_dark_mode_toggle_position', function(value) {
        value.bind(function(to) {
            // Remove all position classes
            $('.dark-mode-toggle')
                .removeClass('in-top-bar in-header in-footer floating');
            
            // Add the new position class
            $('.dark-mode-toggle').addClass('in-' + to);
            
            // Move the toggle to the appropriate container
            if (to === 'top-bar') {
                $('.top-bar-right').append($('.dark-mode-toggle'));
            } else if (to === 'header') {
                $('.header-actions').prepend($('.dark-mode-toggle'));
            } else if (to === 'footer') {
                $('.footer-bottom .footer-copyright').append($('.dark-mode-toggle'));
            } else if (to === 'floating') {
                $('body').append($('.dark-mode-toggle'));
            }
        });
    });

    wp.customize('aqualuxe_dark_mode_primary_color', function(value) {
        value.bind(function(to) {
            // Add a style element if it doesn't exist
            let styleElement = document.getElementById('dark-mode-customizer-css');
            if (!styleElement) {
                styleElement = document.createElement('style');
                styleElement.id = 'dark-mode-customizer-css';
                document.head.appendChild(styleElement);
            }
            
            // Update the CSS
            styleElement.innerHTML = `
                .dark {
                    --primary-color: ${to};
                }
            `;
        });
    });

    wp.customize('aqualuxe_dark_mode_bg_color', function(value) {
        value.bind(function(to) {
            // Add a style element if it doesn't exist
            let styleElement = document.getElementById('dark-mode-customizer-css');
            if (!styleElement) {
                styleElement = document.createElement('style');
                styleElement.id = 'dark-mode-customizer-css';
                document.head.appendChild(styleElement);
            }
            
            // Update the CSS
            styleElement.innerHTML += `
                .dark {
                    background-color: ${to};
                }
            `;
        });
    });

    wp.customize('aqualuxe_dark_mode_text_color', function(value) {
        value.bind(function(to) {
            // Add a style element if it doesn't exist
            let styleElement = document.getElementById('dark-mode-customizer-css');
            if (!styleElement) {
                styleElement = document.createElement('style');
                styleElement.id = 'dark-mode-customizer-css';
                document.head.appendChild(styleElement);
            }
            
            // Update the CSS
            styleElement.innerHTML += `
                .dark {
                    color: ${to};
                }
            `;
        });
    });

    // Footer settings
    wp.customize('aqualuxe_footer_about', function(value) {
        value.bind(function(to) {
            $('.footer-about p').html(to);
        });
    });

    wp.customize('aqualuxe_copyright_text', function(value) {
        value.bind(function(to) {
            $('.footer-copyright p').html(to);
        });
    });

    wp.customize('aqualuxe_show_payment_methods', function(value) {
        value.bind(function(to) {
            if (to) {
                $('.payment-methods').removeClass('hidden');
            } else {
                $('.payment-methods').addClass('hidden');
            }
        });
    });

    wp.customize('aqualuxe_back_to_top', function(value) {
        value.bind(function(to) {
            if (to) {
                $('#back-to-top').removeClass('hidden');
            } else {
                $('#back-to-top').addClass('hidden');
            }
        });
    });

    // Contact information
    wp.customize('aqualuxe_phone_number', function(value) {
        value.bind(function(to) {
            $('.top-bar-left .phone-number').text(to);
            $('.footer-contact .phone').text(to);
            $('.mobile-contact-info .phone').text(to);
        });
    });

    wp.customize('aqualuxe_email', function(value) {
        value.bind(function(to) {
            $('.top-bar-left .email').text(to);
            $('.footer-contact .email').text(to);
            $('.mobile-contact-info .email').text(to);
        });
    });

    wp.customize('aqualuxe_address', function(value) {
        value.bind(function(to) {
            $('.footer-contact .address').text(to);
            $('.mobile-contact-info .address').text(to);
        });
    });

})(jQuery);