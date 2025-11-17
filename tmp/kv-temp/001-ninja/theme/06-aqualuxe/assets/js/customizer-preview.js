/**
 * AquaLuxe Theme Customizer Preview JavaScript
 *
 * This file contains the JavaScript functionality for the theme customizer preview.
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

    // Primary color
    wp.customize('aqualuxe_primary_color', function(value) {
        value.bind(function(to) {
            // Update CSS variables
            document.documentElement.style.setProperty('--color-primary', to);
            document.documentElement.style.setProperty('--aqualuxe-primary-color', to);
            
            // Update inline styles
            var style = $('#aqualuxe-primary-color-inline-css');
            
            if (style.length === 0) {
                style = $('<style id="aqualuxe-primary-color-inline-css"></style>').appendTo('head');
            }
            
            style.html(`
                a,
                .primary-color,
                .site-header .menu > li.current-menu-item > a,
                .site-header .menu > li.current-menu-ancestor > a,
                .site-header .menu > li.current_page_parent > a,
                .site-header .menu > li > a:hover {
                    color: ${to};
                }
                
                .btn-primary,
                .button,
                button,
                input[type="button"],
                input[type="reset"],
                input[type="submit"],
                .woocommerce #respond input#submit,
                .woocommerce a.button,
                .woocommerce button.button,
                .woocommerce input.button,
                .woocommerce #respond input#submit.alt,
                .woocommerce a.button.alt,
                .woocommerce button.button.alt,
                .woocommerce input.button.alt {
                    background-color: ${to};
                }
                
                .border-primary,
                .site-header .menu > li.current-menu-item > a:after,
                .site-header .menu > li.current-menu-ancestor > a:after,
                .site-header .menu > li.current_page_parent > a:after,
                .site-header .menu > li > a:hover:after {
                    border-color: ${to};
                }
            `);
        });
    });

    // Secondary color
    wp.customize('aqualuxe_secondary_color', function(value) {
        value.bind(function(to) {
            // Update CSS variables
            document.documentElement.style.setProperty('--color-secondary', to);
            document.documentElement.style.setProperty('--aqualuxe-secondary-color', to);
            
            // Update inline styles
            var style = $('#aqualuxe-secondary-color-inline-css');
            
            if (style.length === 0) {
                style = $('<style id="aqualuxe-secondary-color-inline-css"></style>').appendTo('head');
            }
            
            style.html(`
                .secondary-color,
                a:hover,
                a:focus,
                a:active {
                    color: ${to};
                }
                
                .btn-secondary,
                .button:hover,
                button:hover,
                input[type="button"]:hover,
                input[type="reset"]:hover,
                input[type="submit"]:hover,
                .woocommerce #respond input#submit:hover,
                .woocommerce a.button:hover,
                .woocommerce button.button:hover,
                .woocommerce input.button:hover,
                .woocommerce #respond input#submit.alt:hover,
                .woocommerce a.button.alt:hover,
                .woocommerce button.button.alt:hover,
                .woocommerce input.button.alt:hover {
                    background-color: ${to};
                }
                
                .border-secondary {
                    border-color: ${to};
                }
            `);
        });
    });

    // Body background color
    wp.customize('aqualuxe_body_background_color', function(value) {
        value.bind(function(to) {
            $('body').css('background-color', to);
        });
    });

    // Body text color
    wp.customize('aqualuxe_body_text_color', function(value) {
        value.bind(function(to) {
            $('body').css('color', to);
        });
    });

    // Header background color
    wp.customize('aqualuxe_header_background_color', function(value) {
        value.bind(function(to) {
            $('.site-header').css('background-color', to);
        });
    });

    // Header text color
    wp.customize('aqualuxe_header_text_color', function(value) {
        value.bind(function(to) {
            $('.site-header, .site-header a').css('color', to);
        });
    });

    // Footer background color
    wp.customize('aqualuxe_footer_background_color', function(value) {
        value.bind(function(to) {
            $('.site-footer').css('background-color', to);
        });
    });

    // Footer text color
    wp.customize('aqualuxe_footer_text_color', function(value) {
        value.bind(function(to) {
            $('.site-footer').css('color', to);
        });
    });

    // Body font family
    wp.customize('aqualuxe_body_font_family', function(value) {
        value.bind(function(to) {
            // Update inline styles
            var style = $('#aqualuxe-body-font-family-inline-css');
            
            if (style.length === 0) {
                style = $('<style id="aqualuxe-body-font-family-inline-css"></style>').appendTo('head');
            }
            
            style.html(`
                body {
                    font-family: "${to}", sans-serif;
                }
            `);
        });
    });

    // Headings font family
    wp.customize('aqualuxe_headings_font_family', function(value) {
        value.bind(function(to) {
            // Update inline styles
            var style = $('#aqualuxe-headings-font-family-inline-css');
            
            if (style.length === 0) {
                style = $('<style id="aqualuxe-headings-font-family-inline-css"></style>').appendTo('head');
            }
            
            style.html(`
                h1, h2, h3, h4, h5, h6 {
                    font-family: "${to}", serif;
                }
            `);
        });
    });

    // Container width
    wp.customize('aqualuxe_container_width', function(value) {
        value.bind(function(to) {
            // Update inline styles
            var style = $('#aqualuxe-container-width-inline-css');
            
            if (style.length === 0) {
                style = $('<style id="aqualuxe-container-width-inline-css"></style>').appendTo('head');
            }
            
            style.html(`
                .container {
                    max-width: ${to}px;
                }
            `);
        });
    });

    // Footer copyright text
    wp.customize('aqualuxe_footer_copyright_text', function(value) {
        value.bind(function(to) {
            $('.site-info').html(to);
        });
    });

    // Shop columns
    wp.customize('aqualuxe_shop_columns', function(value) {
        value.bind(function(to) {
            // Update inline styles
            var style = $('#aqualuxe-shop-columns-inline-css');
            
            if (style.length === 0) {
                style = $('<style id="aqualuxe-shop-columns-inline-css"></style>').appendTo('head');
            }
            
            style.html(`
                @media (min-width: 768px) {
                    .woocommerce ul.products {
                        grid-template-columns: repeat(${to}, 1fr);
                    }
                }
            `);
        });
    });

    // Related products columns
    wp.customize('aqualuxe_related_products_columns', function(value) {
        value.bind(function(to) {
            // Update inline styles
            var style = $('#aqualuxe-related-products-columns-inline-css');
            
            if (style.length === 0) {
                style = $('<style id="aqualuxe-related-products-columns-inline-css"></style>').appendTo('head');
            }
            
            style.html(`
                @media (min-width: 768px) {
                    .related.products ul.products {
                        grid-template-columns: repeat(${to}, 1fr);
                    }
                }
            `);
        });
    });

    // Upsell products columns
    wp.customize('aqualuxe_upsell_products_columns', function(value) {
        value.bind(function(to) {
            // Update inline styles
            var style = $('#aqualuxe-upsell-products-columns-inline-css');
            
            if (style.length === 0) {
                style = $('<style id="aqualuxe-upsell-products-columns-inline-css"></style>').appendTo('head');
            }
            
            style.html(`
                @media (min-width: 768px) {
                    .upsells.products ul.products {
                        grid-template-columns: repeat(${to}, 1fr);
                    }
                }
            `);
        });
    });

})(jQuery);