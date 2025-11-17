/**
 * AquaLuxe Theme Customizer JavaScript
 * 
 * This file contains the customizer live preview functionality for the AquaLuxe theme.
 * 
 * @package AquaLuxe
 * @since 1.0.0
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

    // Color settings
    const colorSettings = [
        {
            setting: 'aqualuxe_primary_color',
            property: '--aqualuxe-primary-color',
            selector: ':root'
        },
        {
            setting: 'aqualuxe_secondary_color',
            property: '--aqualuxe-secondary-color',
            selector: ':root'
        },
        {
            setting: 'aqualuxe_accent_color',
            property: '--aqualuxe-accent-color',
            selector: ':root'
        },
        {
            setting: 'aqualuxe_text_color',
            property: '--aqualuxe-text-color',
            selector: ':root'
        },
        {
            setting: 'aqualuxe_heading_color',
            property: '--aqualuxe-heading-color',
            selector: ':root'
        },
        {
            setting: 'aqualuxe_link_color',
            property: '--aqualuxe-link-color',
            selector: ':root'
        },
        {
            setting: 'aqualuxe_link_hover_color',
            property: '--aqualuxe-link-hover-color',
            selector: ':root'
        }
    ];

    // Apply color settings
    colorSettings.forEach(function(setting) {
        wp.customize(setting.setting, function(value) {
            value.bind(function(to) {
                $(setting.selector).css(setting.property, to);
            });
        });
    });

    // Typography settings
    wp.customize('aqualuxe_body_font', function(value) {
        value.bind(function(to) {
            $(':root').css('--aqualuxe-body-font', to + ', sans-serif');
        });
    });
    
    wp.customize('aqualuxe_heading_font', function(value) {
        value.bind(function(to) {
            $(':root').css('--aqualuxe-heading-font', to + ', serif');
        });
    });

    // Layout settings
    wp.customize('aqualuxe_container_width', function(value) {
        value.bind(function(to) {
            $('.container').css('max-width', to + 'px');
        });
    });
    
    wp.customize('aqualuxe_sidebar_width', function(value) {
        value.bind(function(to) {
            // Calculate content width based on sidebar width
            const contentWidth = 100 - to;
            
            // Apply the new widths
            $('.has-sidebar .content-area').css('width', contentWidth + '%');
            $('.sidebar').css('width', to + '%');
        });
    });

    // Header settings
    wp.customize('aqualuxe_header_layout', function(value) {
        value.bind(function(to) {
            // Remove all header layout classes
            const $header = $('#masthead');
            $header.removeClass('header-layout-default header-layout-centered header-layout-transparent');
            
            // Add the selected layout class
            $header.addClass('header-layout-' + to);
        });
    });
    
    wp.customize('aqualuxe_header_sticky', function(value) {
        value.bind(function(to) {
            const $header = $('#masthead');
            
            if (to) {
                $header.addClass('sticky-header');
            } else {
                $header.removeClass('sticky-header');
            }
        });
    });

    // Footer settings
    wp.customize('aqualuxe_footer_columns', function(value) {
        value.bind(function(to) {
            const $footerWidgets = $('.footer-widgets-row');
            
            // Remove all column classes
            $footerWidgets.removeClass('columns-1 columns-2 columns-3 columns-4');
            
            // Add the selected column class
            $footerWidgets.addClass('columns-' + to);
        });
    });
    
    wp.customize('aqualuxe_copyright_text', function(value) {
        value.bind(function(to) {
            $('.footer-copyright').html(to);
        });
    });

    // Blog settings
    wp.customize('aqualuxe_blog_layout', function(value) {
        value.bind(function(to) {
            const $blogContainer = $('.blog-posts');
            
            // Remove all layout classes
            $blogContainer.removeClass('blog-layout-standard blog-layout-grid blog-layout-masonry');
            
            // Add the selected layout class
            $blogContainer.addClass('blog-layout-' + to);
        });
    });
    
    wp.customize('aqualuxe_excerpt_length', function(value) {
        value.bind(function(to) {
            // This requires a page refresh to take effect
        });
    });

    // WooCommerce settings
    wp.customize('aqualuxe_product_columns', function(value) {
        value.bind(function(to) {
            const $products = $('.products');
            
            // Remove all column classes
            $products.removeClass('columns-2 columns-3 columns-4 columns-5 columns-6');
            
            // Add the selected column class
            $products.addClass('columns-' + to);
        });
    });
    
    wp.customize('aqualuxe_shop_sidebar', function(value) {
        value.bind(function(to) {
            const $shopPage = $('.woocommerce-shop');
            
            if (to) {
                $shopPage.addClass('has-sidebar');
                $('.shop-sidebar').show();
            } else {
                $shopPage.removeClass('has-sidebar');
                $('.shop-sidebar').hide();
            }
        });
    });

    // Dark mode settings
    wp.customize('aqualuxe_dark_mode_light_bg', function(value) {
        value.bind(function(to) {
            $(':root').css('--aqualuxe-light-bg', to);
        });
    });
    
    wp.customize('aqualuxe_dark_mode_light_text', function(value) {
        value.bind(function(to) {
            $(':root').css('--aqualuxe-light-text', to);
        });
    });
    
    wp.customize('aqualuxe_dark_mode_dark_bg', function(value) {
        value.bind(function(to) {
            $(':root').css('--aqualuxe-dark-bg', to);
        });
    });
    
    wp.customize('aqualuxe_dark_mode_dark_text', function(value) {
        value.bind(function(to) {
            $(':root').css('--aqualuxe-dark-text', to);
        });
    });
    
    wp.customize('aqualuxe_dark_mode_accent_color', function(value) {
        value.bind(function(to) {
            $(':root').css('--aqualuxe-dark-mode-accent', to);
        });
    });

    // Custom CSS
    wp.customize('aqualuxe_custom_css', function(value) {
        value.bind(function(to) {
            $('#aqualuxe-custom-css').html(to);
        });
    });

})(jQuery);