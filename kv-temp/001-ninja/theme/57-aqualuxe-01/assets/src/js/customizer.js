/**
 * Customizer JavaScript file for the AquaLuxe theme
 * 
 * This file handles the customizer live preview functionality
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

    // Header settings
    wp.customize('aqualuxe_header_height', function(value) {
        value.bind(function(to) {
            $('.site-header').css('height', to + 'px');
        });
    });

    wp.customize('aqualuxe_mobile_header_height', function(value) {
        value.bind(function(to) {
            $('<style>.site-header { height: ' + to + 'px; } @media (min-width: 768px) { .site-header { height: auto; } }</style>').appendTo('head');
        });
    });

    wp.customize('aqualuxe_header_background', function(value) {
        value.bind(function(to) {
            $('.site-header').css('background-color', to);
        });
    });

    wp.customize('aqualuxe_header_text_color', function(value) {
        value.bind(function(to) {
            $('.site-header, .site-header a').css('color', to);
        });
    });

    wp.customize('aqualuxe_top_bar_background', function(value) {
        value.bind(function(to) {
            $('.top-bar').css('background-color', to);
        });
    });

    wp.customize('aqualuxe_top_bar_text_color', function(value) {
        value.bind(function(to) {
            $('.top-bar, .top-bar a').css('color', to);
        });
    });

    wp.customize('aqualuxe_header_border_color', function(value) {
        value.bind(function(to) {
            $('.site-header').css('border-bottom-color', to);
        });
    });

    // Page header settings
    wp.customize('aqualuxe_page_header_background', function(value) {
        value.bind(function(to) {
            $('.page-header').css('background-color', to);
        });
    });

    wp.customize('aqualuxe_page_header_text_color', function(value) {
        value.bind(function(to) {
            $('.page-header, .page-header a').css('color', to);
        });
    });

    wp.customize('aqualuxe_page_header_padding_top', function(value) {
        value.bind(function(to) {
            $('.page-header').css('padding-top', to + 'px');
        });
    });

    wp.customize('aqualuxe_page_header_padding_bottom', function(value) {
        value.bind(function(to) {
            $('.page-header').css('padding-bottom', to + 'px');
        });
    });

    // Footer settings
    wp.customize('aqualuxe_footer_background', function(value) {
        value.bind(function(to) {
            $('.site-footer').css('background-color', to);
        });
    });

    wp.customize('aqualuxe_footer_text_color', function(value) {
        value.bind(function(to) {
            $('.site-footer, .site-footer a').css('color', to);
        });
    });

    wp.customize('aqualuxe_footer_heading_color', function(value) {
        value.bind(function(to) {
            $('.site-footer h1, .site-footer h2, .site-footer h3, .site-footer h4, .site-footer h5, .site-footer h6').css('color', to);
        });
    });

    wp.customize('aqualuxe_footer_link_color', function(value) {
        value.bind(function(to) {
            $('.site-footer a').css('color', to);
        });
    });

    wp.customize('aqualuxe_footer_border_color', function(value) {
        value.bind(function(to) {
            $('.site-footer').css('border-top-color', to);
            $('.footer-widgets').css('border-bottom-color', to);
        });
    });

    wp.customize('aqualuxe_copyright_text', function(value) {
        value.bind(function(to) {
            $('.copyright-text').html(to);
        });
    });

    // Typography settings
    wp.customize('aqualuxe_body_font_family', function(value) {
        value.bind(function(to) {
            $('body').css('font-family', to);
        });
    });

    wp.customize('aqualuxe_headings_font_family', function(value) {
        value.bind(function(to) {
            $('h1, h2, h3, h4, h5, h6').css('font-family', to);
        });
    });

    wp.customize('aqualuxe_body_font_size', function(value) {
        value.bind(function(to) {
            $('body').css('font-size', to + 'px');
        });
    });

    wp.customize('aqualuxe_body_line_height', function(value) {
        value.bind(function(to) {
            $('body').css('line-height', to);
        });
    });

    // Color settings
    wp.customize('aqualuxe_primary_color', function(value) {
        value.bind(function(to) {
            const style = $('#aqualuxe-primary-color-css');
            if (style.length) {
                style.remove();
            }
            $('head').append(
                '<style id="aqualuxe-primary-color-css">' +
                '.has-primary-color { color: ' + to + '; }' +
                '.has-primary-background-color { background-color: ' + to + '; }' +
                'a, .primary-color, .btn-link, .site-header .menu > li.current-menu-item > a { color: ' + to + '; }' +
                '.btn-primary, .wp-block-button:not(.is-style-outline) .wp-block-button__link { background-color: ' + to + '; border-color: ' + to + '; }' +
                '</style>'
            );
        });
    });

    wp.customize('aqualuxe_secondary_color', function(value) {
        value.bind(function(to) {
            const style = $('#aqualuxe-secondary-color-css');
            if (style.length) {
                style.remove();
            }
            $('head').append(
                '<style id="aqualuxe-secondary-color-css">' +
                '.has-secondary-color { color: ' + to + '; }' +
                '.has-secondary-background-color { background-color: ' + to + '; }' +
                '.secondary-color, a:hover, .btn-link:hover { color: ' + to + '; }' +
                '.btn-secondary { background-color: ' + to + '; border-color: ' + to + '; }' +
                '</style>'
            );
        });
    });

    wp.customize('aqualuxe_text_color', function(value) {
        value.bind(function(to) {
            $('body').css('color', to);
        });
    });

    wp.customize('aqualuxe_heading_color', function(value) {
        value.bind(function(to) {
            $('h1, h2, h3, h4, h5, h6').css('color', to);
        });
    });

    wp.customize('aqualuxe_link_color', function(value) {
        value.bind(function(to) {
            $('a').css('color', to);
        });
    });

    wp.customize('aqualuxe_link_hover_color', function(value) {
        value.bind(function(to) {
            const style = $('#aqualuxe-link-hover-css');
            if (style.length) {
                style.remove();
            }
            $('head').append(
                '<style id="aqualuxe-link-hover-css">' +
                'a:hover, a:focus, a:active { color: ' + to + '; }' +
                '</style>'
            );
        });
    });

    // Layout settings
    wp.customize('aqualuxe_container_width', function(value) {
        value.bind(function(to) {
            const style = $('#aqualuxe-container-width-css');
            if (style.length) {
                style.remove();
            }
            $('head').append(
                '<style id="aqualuxe-container-width-css">' +
                '.container { max-width: ' + to + 'px; }' +
                '</style>'
            );
        });
    });

    wp.customize('aqualuxe_content_width', function(value) {
        value.bind(function(to) {
            const style = $('#aqualuxe-content-width-css');
            if (style.length) {
                style.remove();
            }
            $('head').append(
                '<style id="aqualuxe-content-width-css">' +
                '.content-area { width: ' + to + '%; }' +
                '.sidebar { width: ' + (100 - to) + '%; }' +
                '</style>'
            );
        });
    });

    // Blog settings
    wp.customize('aqualuxe_blog_layout', function(value) {
        value.bind(function(to) {
            $('.blog .posts-container').removeClass('layout-grid layout-list layout-masonry').addClass('layout-' + to);
        });
    });

    wp.customize('aqualuxe_excerpt_length', function(value) {
        value.bind(function(to) {
            // This requires a page refresh to take effect
        });
    });

    wp.customize('aqualuxe_read_more_text', function(value) {
        value.bind(function(to) {
            $('.read-more-link').text(to);
        });
    });

    // WooCommerce settings
    wp.customize('aqualuxe_products_per_page', function(value) {
        value.bind(function(to) {
            // This requires a page refresh to take effect
        });
    });

    wp.customize('aqualuxe_product_columns', function(value) {
        value.bind(function(to) {
            $('.woocommerce ul.products').removeClass('columns-2 columns-3 columns-4 columns-5 columns-6').addClass('columns-' + to);
        });
    });

})(jQuery);