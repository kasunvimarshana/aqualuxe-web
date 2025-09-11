/**
 * Customizer JavaScript
 * 
 * Handles live preview updates in the WordPress Customizer
 */

(function($) {
    'use strict';

    wp.customize = wp.customize || {};

    // Site title and tagline
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
                    'position': 'relative',
                    'color': to
                });
            }
        });
    });

    // Primary color
    wp.customize('primary_color', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--color-primary', to);
        });
    });

    // Secondary color
    wp.customize('secondary_color', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--color-secondary', to);
        });
    });

    // Accent color
    wp.customize('accent_color', function(value) {
        value.bind(function(to) {
            document.documentElement.style.setProperty('--color-accent', to);
        });
    });

    // Dark mode colors
    wp.customize('dark_primary_color', function(value) {
        value.bind(function(to) {
            const style = document.getElementById('customizer-dark-colors') || document.createElement('style');
            style.id = 'customizer-dark-colors';
            style.textContent = `.dark { --color-primary: ${to} !important; }`;
            document.head.appendChild(style);
        });
    });

    wp.customize('dark_secondary_color', function(value) {
        value.bind(function(to) {
            const style = document.getElementById('customizer-dark-colors') || document.createElement('style');
            const existingContent = style.textContent || '';
            style.id = 'customizer-dark-colors';
            style.textContent = existingContent.replace(/--color-secondary: [^;]+;/, '') + `--color-secondary: ${to};`;
            document.head.appendChild(style);
        });
    });

    wp.customize('dark_accent_color', function(value) {
        value.bind(function(to) {
            const style = document.getElementById('customizer-dark-colors') || document.createElement('style');
            const existingContent = style.textContent || '';
            style.id = 'customizer-dark-colors';
            style.textContent = existingContent.replace(/--color-accent: [^;]+;/, '') + `--color-accent: ${to};`;
            document.head.appendChild(style);
        });
    });

    // Typography settings
    wp.customize('font_family_headings', function(value) {
        value.bind(function(to) {
            $('h1, h2, h3, h4, h5, h6').css('font-family', to);
        });
    });

    wp.customize('font_family_body', function(value) {
        value.bind(function(to) {
            $('body').css('font-family', to);
        });
    });

    // Layout settings
    wp.customize('container_width', function(value) {
        value.bind(function(to) {
            $('.container').css('max-width', to + 'px');
        });
    });

    wp.customize('sidebar_width', function(value) {
        value.bind(function(to) {
            $('.sidebar').css('width', to + '%');
        });
    });

    // Custom CSS
    wp.customize('custom_css', function(value) {
        value.bind(function(to) {
            let style = document.getElementById('customizer-custom-css');
            if (!style) {
                style = document.createElement('style');
                style.id = 'customizer-custom-css';
                document.head.appendChild(style);
            }
            style.textContent = to;
        });
    });

    // Footer settings
    wp.customize('footer_copyright', function(value) {
        value.bind(function(to) {
            $('.footer-copyright').text(to);
        });
    });

    // Social media links
    const socialPlatforms = ['facebook', 'twitter', 'instagram', 'youtube', 'linkedin'];
    
    socialPlatforms.forEach(platform => {
        wp.customize(`social_${platform}`, function(value) {
            value.bind(function(to) {
                const link = $(`.social-links .${platform}-link`);
                if (to) {
                    link.attr('href', to).show();
                } else {
                    link.hide();
                }
            });
        });
    });

    // Logo and branding
    wp.customize('custom_logo', function(value) {
        value.bind(function(to) {
            if (to) {
                const img = wp.media.attachment(to);
                img.fetch().then(function() {
                    $('.custom-logo').attr('src', img.get('url'));
                });
            } else {
                $('.custom-logo').remove();
            }
        });
    });

    // Header image
    wp.customize('header_image', function(value) {
        value.bind(function(to) {
            $('.hero-section, .page-header').css('background-image', to ? `url(${to})` : 'none');
        });
    });

    // Background color
    wp.customize('background_color', function(value) {
        value.bind(function(to) {
            $('body').css('background-color', '#' + to);
        });
    });

    // WooCommerce settings
    if (typeof woocommerce !== 'undefined') {
        wp.customize('shop_columns', function(value) {
            value.bind(function(to) {
                $('.products').removeClass('columns-1 columns-2 columns-3 columns-4 columns-5 columns-6')
                              .addClass('columns-' + to);
            });
        });

        wp.customize('products_per_page', function(value) {
            value.bind(function(to) {
                // This would typically require a page refresh
                wp.customize.preview.send('refresh');
            });
        });
    }

    // Dark mode toggle for preview
    wp.customize('dark_mode_enabled', function(value) {
        value.bind(function(to) {
            if (to) {
                $('body').addClass('dark-mode-enabled');
                // Add dark mode toggle if it doesn't exist
                if (!$('.dark-mode-toggle').length && window.AquaLuxeDarkMode) {
                    window.AquaLuxeDarkMode.init();
                }
            } else {
                $('body').removeClass('dark-mode-enabled');
                $('.dark-mode-toggle').remove();
            }
        });
    });

    // Live preview for module settings
    const modules = ['multilingual', 'multicurrency', 'multivendor', 'subscriptions', 'bookings'];
    
    modules.forEach(module => {
        wp.customize(`${module}_enabled`, function(value) {
            value.bind(function(to) {
                $('body').toggleClass(`${module}-enabled`, to);
            });
        });
    });

})(jQuery);