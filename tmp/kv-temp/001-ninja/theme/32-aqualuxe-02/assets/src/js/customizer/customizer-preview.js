/**
 * AquaLuxe Theme - Customizer Preview
 *
 * Handles the customizer preview functionality.
 */

(function($) {
    'use strict';

    // Wait for the customizer preview to be ready
    wp.customize.bind('preview-ready', function() {
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

        // Colors
        wp.customize('aqualuxe_primary_color', function(value) {
            value.bind(function(to) {
                // Add custom CSS
                const css = `
                    :root {
                        --primary-color: ${to};
                    }
                `;
                updateCustomCSS('aqualuxe-primary-color', css);
            });
        });

        wp.customize('aqualuxe_secondary_color', function(value) {
            value.bind(function(to) {
                // Add custom CSS
                const css = `
                    :root {
                        --secondary-color: ${to};
                    }
                `;
                updateCustomCSS('aqualuxe-secondary-color', css);
            });
        });

        wp.customize('aqualuxe_accent_color', function(value) {
            value.bind(function(to) {
                // Add custom CSS
                const css = `
                    :root {
                        --accent-color: ${to};
                    }
                `;
                updateCustomCSS('aqualuxe-accent-color', css);
            });
        });

        wp.customize('aqualuxe_text_color', function(value) {
            value.bind(function(to) {
                // Add custom CSS
                const css = `
                    :root {
                        --text-color: ${to};
                    }
                `;
                updateCustomCSS('aqualuxe-text-color', css);
            });
        });

        wp.customize('aqualuxe_background_color', function(value) {
            value.bind(function(to) {
                // Add custom CSS
                const css = `
                    :root {
                        --background-color: ${to};
                    }
                    body {
                        background-color: ${to};
                    }
                `;
                updateCustomCSS('aqualuxe-background-color', css);
            });
        });

        // Typography
        wp.customize('aqualuxe_body_font_family', function(value) {
            value.bind(function(to) {
                // Add custom CSS
                const css = `
                    :root {
                        --font-family: ${to}, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
                    }
                    body {
                        font-family: var(--font-family);
                    }
                `;
                updateCustomCSS('aqualuxe-body-font-family', css);
            });
        });

        wp.customize('aqualuxe_heading_font_family', function(value) {
            value.bind(function(to) {
                // Add custom CSS
                const css = `
                    :root {
                        --heading-font-family: ${to}, Georgia, serif;
                    }
                    h1, h2, h3, h4, h5, h6 {
                        font-family: var(--heading-font-family);
                    }
                `;
                updateCustomCSS('aqualuxe-heading-font-family', css);
            });
        });

        wp.customize('aqualuxe_body_font_size', function(value) {
            value.bind(function(to) {
                // Add custom CSS
                const css = `
                    body {
                        font-size: ${to}px;
                    }
                `;
                updateCustomCSS('aqualuxe-body-font-size', css);
            });
        });

        wp.customize('aqualuxe_body_line_height', function(value) {
            value.bind(function(to) {
                // Add custom CSS
                const css = `
                    body {
                        line-height: ${to};
                    }
                `;
                updateCustomCSS('aqualuxe-body-line-height', css);
            });
        });

        // Layout
        wp.customize('aqualuxe_container_width', function(value) {
            value.bind(function(to) {
                // Add custom CSS
                const css = `
                    .container {
                        max-width: ${to}px;
                    }
                `;
                updateCustomCSS('aqualuxe-container-width', css);
            });
        });

        wp.customize('aqualuxe_sidebar_width', function(value) {
            value.bind(function(to) {
                // Add custom CSS
                const css = `
                    .sidebar {
                        width: ${to}px;
                    }
                `;
                updateCustomCSS('aqualuxe-sidebar-width', css);
            });
        });

        wp.customize('aqualuxe_layout', function(value) {
            value.bind(function(to) {
                // Remove all layout classes
                $('body').removeClass('layout-right-sidebar layout-left-sidebar layout-no-sidebar');
                
                // Add selected layout class
                $('body').addClass('layout-' + to);
            });
        });

        // Header
        wp.customize('aqualuxe_header_layout', function(value) {
            value.bind(function(to) {
                // Remove all header layout classes
                $('.site-header').removeClass('header-layout-1 header-layout-2 header-layout-3');
                
                // Add selected header layout class
                $('.site-header').addClass('header-layout-' + to);
            });
        });

        wp.customize('aqualuxe_sticky_header', function(value) {
            value.bind(function(to) {
                if (to) {
                    $('.site-header').addClass('sticky-enabled');
                } else {
                    $('.site-header').removeClass('sticky-enabled');
                }
            });
        });

        // Footer
        wp.customize('aqualuxe_footer_layout', function(value) {
            value.bind(function(to) {
                // Remove all footer layout classes
                $('.site-footer').removeClass('footer-layout-1 footer-layout-2 footer-layout-3');
                
                // Add selected footer layout class
                $('.site-footer').addClass('footer-layout-' + to);
            });
        });

        wp.customize('aqualuxe_footer_text', function(value) {
            value.bind(function(to) {
                $('.site-info').html(to);
            });
        });

        // Blog
        wp.customize('aqualuxe_blog_layout', function(value) {
            value.bind(function(to) {
                // Remove all blog layout classes
                $('.blog-posts').removeClass('blog-layout-grid blog-layout-list blog-layout-masonry');
                
                // Add selected blog layout class
                $('.blog-posts').addClass('blog-layout-' + to);
            });
        });

        wp.customize('aqualuxe_blog_columns', function(value) {
            value.bind(function(to) {
                // Remove all column classes
                $('.blog-posts').removeClass('columns-1 columns-2 columns-3 columns-4');
                
                // Add selected column class
                $('.blog-posts').addClass('columns-' + to);
            });
        });

        // WooCommerce
        wp.customize('aqualuxe_shop_layout', function(value) {
            value.bind(function(to) {
                // Remove all shop layout classes
                $('.products').removeClass('shop-layout-grid shop-layout-list shop-layout-masonry');
                
                // Add selected shop layout class
                $('.products').addClass('shop-layout-' + to);
            });
        });

        wp.customize('aqualuxe_shop_columns', function(value) {
            value.bind(function(to) {
                // Remove all column classes
                $('.products').removeClass('columns-1 columns-2 columns-3 columns-4 columns-5 columns-6');
                
                // Add selected column class
                $('.products').addClass('columns-' + to);
            });
        });

        // Helper function to update custom CSS
        function updateCustomCSS(id, css) {
            let $style = $('#' + id);
            
            if (!$style.length) {
                $style = $('<style type="text/css" id="' + id + '"></style>');
                $('head').append($style);
            }
            
            $style.html(css);
        }
    });

})(jQuery);