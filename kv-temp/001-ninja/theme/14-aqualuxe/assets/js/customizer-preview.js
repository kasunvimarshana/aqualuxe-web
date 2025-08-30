/**
 * AquaLuxe Theme Customizer Preview JavaScript
 *
 * @package AquaLuxe
 * @since 1.0.0
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

    // Primary color.
    wp.customize('primary_color', function(value) {
        value.bind(function(to) {
            // Update CSS variables
            document.documentElement.style.setProperty('--color-primary', to);
            
            // Calculate darker shade for hover states
            var darkerColor = adjustColor(to, -15);
            document.documentElement.style.setProperty('--color-primary-dark', darkerColor);
        });
    });

    // Secondary color.
    wp.customize('secondary_color', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--color-secondary', to);
        });
    });

    // Text color.
    wp.customize('text_color', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--color-text', to);
        });
    });

    // Link color.
    wp.customize('link_color', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--color-link', to);
        });
    });

    // Dark mode primary color.
    wp.customize('dark_mode_primary_color', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--color-primary-dark-mode', to);
        });
    });

    // Dark mode background color.
    wp.customize('dark_mode_bg_color', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--color-bg-dark-mode', to);
        });
    });

    // Dark mode text color.
    wp.customize('dark_mode_text_color', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--color-text-dark-mode', to);
        });
    });

    // Container width.
    wp.customize('container_width', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--container-width', to + 'px');
        });
    });

    // Logo size.
    wp.customize('logo_size', function(value) {
        value.bind(function(to) {
            var sizes = {
                'small': '100px',
                'medium': '150px',
                'large': '200px'
            };
            
            $('.custom-logo').css('max-width', sizes[to]);
        });
    });

    // Header style.
    wp.customize('header_style', function(value) {
        value.bind(function(to) {
            // Remove all header style classes
            $('#masthead').removeClass('standard centered minimal');
            
            // Add selected style class
            $('#masthead').addClass(to);
        });
    });

    // Sticky header.
    wp.customize('sticky_header', function(value) {
        value.bind(function(to) {
            if (to) {
                $('#masthead').addClass('sticky');
            } else {
                $('#masthead').removeClass('sticky');
            }
        });
    });

    // Footer columns.
    wp.customize('footer_columns', function(value) {
        value.bind(function(to) {
            var $footerWidgets = $('#colophon .footer-widget');
            
            // Remove all column classes
            $footerWidgets.removeClass('lg:w-full lg:w-1/2 lg:w-1/3 lg:w-1/4');
            
            // Add appropriate column class
            switch (to) {
                case '1':
                    $footerWidgets.addClass('lg:w-full');
                    break;
                case '2':
                    $footerWidgets.addClass('lg:w-1/2');
                    break;
                case '3':
                    $footerWidgets.addClass('lg:w-1/3');
                    break;
                case '4':
                    $footerWidgets.addClass('lg:w-1/4');
                    break;
            }
        });
    });

    // Copyright text.
    wp.customize('copyright_text', function(value) {
        value.bind(function(to) {
            $('#colophon .copyright-text').html(to);
        });
    });

    // Sidebar position.
    wp.customize('sidebar_position', function(value) {
        value.bind(function(to) {
            var $content = $('.content-area');
            var $sidebar = $('.widget-area');
            
            if (to === 'left') {
                $content.removeClass('order-1').addClass('order-2');
                $sidebar.removeClass('order-2').addClass('order-1');
            } else {
                $content.removeClass('order-2').addClass('order-1');
                $sidebar.removeClass('order-1').addClass('order-2');
            }
        });
    });

    // Base font size.
    wp.customize('base_font_size', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--base-font-size', to + 'px');
        });
    });

    // Heading font.
    wp.customize('heading_font', function(value) {
        value.bind(function(to) {
            var fontFamilies = {
                'sans': '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
                'serif': 'Georgia, "Times New Roman", serif',
                'mono': 'Monaco, Consolas, "Andale Mono", "DejaVu Sans Mono", monospace'
            };
            
            document.documentElement.style.setProperty('--font-heading', fontFamilies[to]);
        });
    });

    // Body font.
    wp.customize('body_font', function(value) {
        value.bind(function(to) {
            var fontFamilies = {
                'sans': '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
                'serif': 'Georgia, "Times New Roman", serif',
                'mono': 'Monaco, Consolas, "Andale Mono", "DejaVu Sans Mono", monospace'
            };
            
            document.documentElement.style.setProperty('--font-body', fontFamilies[to]);
        });
    });

    // Custom CSS.
    wp.customize('custom_css', function(value) {
        value.bind(function(to) {
            $('#aqualuxe-custom-css').html(to);
        });
    });

    /**
     * Helper function to adjust color brightness
     *
     * @param {string} hex Hex color code
     * @param {number} percent Percentage to adjust (-100 to 100)
     * @return {string} Adjusted hex color
     */
    function adjustColor(hex, percent) {
        // Remove # if present
        hex = hex.replace(/^#/, '');
        
        // Convert to RGB
        var r = parseInt(hex.substring(0, 2), 16);
        var g = parseInt(hex.substring(2, 4), 16);
        var b = parseInt(hex.substring(4, 6), 16);
        
        // Adjust color
        r = Math.max(0, Math.min(255, r + Math.round(r * (percent / 100))));
        g = Math.max(0, Math.min(255, g + Math.round(g * (percent / 100))));
        b = Math.max(0, Math.min(255, b + Math.round(b * (percent / 100))));
        
        // Convert back to hex
        return '#' + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
    }

})(jQuery);