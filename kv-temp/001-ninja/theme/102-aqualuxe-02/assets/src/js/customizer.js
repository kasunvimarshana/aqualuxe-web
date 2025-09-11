/**
 * AquaLuxe Customizer JavaScript
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Customizer functionality
(function($) {
    'use strict';

    /**
     * Live preview for site title and description
     */
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

    /**
     * Live preview for header text color
     */
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
                $('.site-title a, .site-description').css('color', to);
            }
        });
    });

    /**
     * Live preview for primary color
     */
    wp.customize('aqualuxe_primary_color', function(value) {
        value.bind(function(to) {
            const style = '<style id="aqualuxe-primary-color">:root{--color-primary:' + to + ';}</style>';
            $('head').find('#aqualuxe-primary-color').remove();
            $('head').append(style);
        });
    });

    /**
     * Live preview for secondary color
     */
    wp.customize('aqualuxe_secondary_color', function(value) {
        value.bind(function(to) {
            const style = '<style id="aqualuxe-secondary-color">:root{--color-secondary:' + to + ';}</style>';
            $('head').find('#aqualuxe-secondary-color').remove();
            $('head').append(style);
        });
    });

    /**
     * Live preview for accent color
     */
    wp.customize('aqualuxe_accent_color', function(value) {
        value.bind(function(to) {
            const style = '<style id="aqualuxe-accent-color">:root{--color-accent:' + to + ';}</style>';
            $('head').find('#aqualuxe-accent-color').remove();
            $('head').append(style);
        });
    });

    /**
     * Live preview for font family
     */
    wp.customize('aqualuxe_font_family', function(value) {
        value.bind(function(to) {
            $('body').css('font-family', to);
        });
    });

    /**
     * Live preview for font size
     */
    wp.customize('aqualuxe_font_size', function(value) {
        value.bind(function(to) {
            $('body').css('font-size', to + 'px');
        });
    });

    /**
     * Live preview for header layout
     */
    wp.customize('aqualuxe_header_layout', function(value) {
        value.bind(function(to) {
            $('.site-header').removeClass('layout-default layout-centered layout-minimal').addClass('layout-' + to);
        });
    });

    /**
     * Live preview for footer layout
     */
    wp.customize('aqualuxe_footer_layout', function(value) {
        value.bind(function(to) {
            $('.site-footer').removeClass('layout-default layout-centered layout-minimal').addClass('layout-' + to);
        });
    });

    /**
     * Live preview for container width
     */
    wp.customize('aqualuxe_container_width', function(value) {
        value.bind(function(to) {
            const style = '<style id="aqualuxe-container-width">.container{max-width:' + to + 'px;}</style>';
            $('head').find('#aqualuxe-container-width').remove();
            $('head').append(style);
        });
    });

    /**
     * Live preview for sidebar position
     */
    wp.customize('aqualuxe_sidebar_position', function(value) {
        value.bind(function(to) {
            const $body = $('body');
            $body.removeClass('sidebar-left sidebar-right sidebar-none').addClass('sidebar-' + to);
        });
    });

    /**
     * Toggle customizer sections based on conditions
     */
    wp.customize.bind('ready', function() {
        // Show/hide WooCommerce options based on plugin status
        const woocommerceInstalled = wp.customize.control('woocommerce_shop_page_id') !== undefined;
        
        if (!woocommerceInstalled) {
            wp.customize.section('aqualuxe_woocommerce', function(section) {
                section.active.set(false);
            });
        }

        // Show/hide blog options based on page template
        wp.customize('page_template', function(value) {
            value.bind(function(to) {
                const isBlogTemplate = to.includes('blog') || to.includes('archive');
                
                wp.customize.section('aqualuxe_blog', function(section) {
                    section.active.set(isBlogTemplate);
                });
            });
        });
    });

    /**
     * Custom control handlers
     */
    
    // Range slider control
    $('.customize-control-range input[type="range"]').on('input change', function() {
        const $this = $(this);
        const value = $this.val();
        const $output = $this.siblings('.range-value');
        
        if ($output.length) {
            $output.text(value);
        }
    });

    // Color picker with alpha channel
    $('.customize-control-color-alpha .color-picker-alpha').wpColorPicker({
        change: function(event, ui) {
            const $this = $(this);
            const color = ui.color.toString();
            
            // Trigger customizer change
            if (typeof wp.customize !== 'undefined') {
                const setting = $this.data('customize-setting-link');
                if (setting) {
                    wp.customize(setting).set(color);
                }
            }
        }
    });

    // Image upload control
    $('.customize-control-image .upload-button').on('click', function(e) {
        e.preventDefault();
        
        const $this = $(this);
        const $input = $this.siblings('input[type="hidden"]');
        const $preview = $this.siblings('.image-preview');
        
        // WordPress media uploader
        const frame = wp.media({
            title: 'Select Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });
        
        frame.on('select', function() {
            const attachment = frame.state().get('selection').first().toJSON();
            
            $input.val(attachment.url).trigger('change');
            $preview.html('<img src="' + attachment.url + '" alt="">');
            $this.text('Change Image');
        });
        
        frame.open();
    });

    // Remove image button
    $('.customize-control-image .remove-button').on('click', function(e) {
        e.preventDefault();
        
        const $this = $(this);
        const $input = $this.siblings('input[type="hidden"]');
        const $preview = $this.siblings('.image-preview');
        const $uploadBtn = $this.siblings('.upload-button');
        
        $input.val('').trigger('change');
        $preview.empty();
        $uploadBtn.text('Select Image');
    });

})(jQuery);