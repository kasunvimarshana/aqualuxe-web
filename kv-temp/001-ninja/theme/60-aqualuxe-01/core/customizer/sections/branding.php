<?php
/**
 * AquaLuxe Theme Customizer - Branding Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add branding settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_branding($wp_customize) {
    // Add Branding section
    $wp_customize->add_section('aqualuxe_branding', array(
        'title'    => __('Branding', 'aqualuxe'),
        'priority' => 20,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Site Logo - Already handled by core WordPress
    
    // Site Icon - Already handled by core WordPress
    
    // Logo Width
    $wp_customize->add_setting('aqualuxe_logo_width', array(
        'default'           => 180,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_logo_width', array(
        'label'       => __('Logo Width (px)', 'aqualuxe'),
        'description' => __('Adjust the width of your logo.', 'aqualuxe'),
        'section'     => 'aqualuxe_branding',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 50,
            'max'  => 500,
            'step' => 1,
        ),
    ));
    
    // Logo Height
    $wp_customize->add_setting('aqualuxe_logo_height', array(
        'default'           => 60,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_logo_height', array(
        'label'       => __('Logo Height (px)', 'aqualuxe'),
        'description' => __('Adjust the height of your logo.', 'aqualuxe'),
        'section'     => 'aqualuxe_branding',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 20,
            'max'  => 300,
            'step' => 1,
        ),
    ));
    
    // Mobile Logo
    $wp_customize->add_setting('aqualuxe_mobile_logo', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aqualuxe_mobile_logo', array(
        'label'       => __('Mobile Logo', 'aqualuxe'),
        'description' => __('Upload a different logo for mobile devices (optional).', 'aqualuxe'),
        'section'     => 'aqualuxe_branding',
        'mime_type'   => 'image',
    )));
    
    // Transparent Header Logo (Light)
    $wp_customize->add_setting('aqualuxe_transparent_header_light_logo', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aqualuxe_transparent_header_light_logo', array(
        'label'       => __('Transparent Header Light Logo', 'aqualuxe'),
        'description' => __('Upload a light version of your logo for transparent headers on dark backgrounds.', 'aqualuxe'),
        'section'     => 'aqualuxe_branding',
        'mime_type'   => 'image',
    )));
    
    // Transparent Header Logo (Dark)
    $wp_customize->add_setting('aqualuxe_transparent_header_dark_logo', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aqualuxe_transparent_header_dark_logo', array(
        'label'       => __('Transparent Header Dark Logo', 'aqualuxe'),
        'description' => __('Upload a dark version of your logo for transparent headers on light backgrounds.', 'aqualuxe'),
        'section'     => 'aqualuxe_branding',
        'mime_type'   => 'image',
    )));
    
    // Footer Logo
    $wp_customize->add_setting('aqualuxe_footer_logo', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aqualuxe_footer_logo', array(
        'label'       => __('Footer Logo', 'aqualuxe'),
        'description' => __('Upload a logo for the footer (optional).', 'aqualuxe'),
        'section'     => 'aqualuxe_branding',
        'mime_type'   => 'image',
    )));
    
    // Show Site Title
    $wp_customize->add_setting('aqualuxe_show_site_title', array(
        'default'           => true,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_site_title', array(
        'label'    => __('Display Site Title', 'aqualuxe'),
        'section'  => 'aqualuxe_branding',
        'type'     => 'checkbox',
    ));
    
    // Show Tagline
    $wp_customize->add_setting('aqualuxe_show_tagline', array(
        'default'           => true,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_tagline', array(
        'label'    => __('Display Tagline', 'aqualuxe'),
        'section'  => 'aqualuxe_branding',
        'type'     => 'checkbox',
    ));
    
    // Brand Color
    $wp_customize->add_setting('aqualuxe_brand_color', array(
        'default'           => '#0073aa',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_brand_color', array(
        'label'    => __('Brand Color', 'aqualuxe'),
        'section'  => 'aqualuxe_branding',
    )));
    
    // Secondary Brand Color
    $wp_customize->add_setting('aqualuxe_secondary_brand_color', array(
        'default'           => '#23282d',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_brand_color', array(
        'label'    => __('Secondary Brand Color', 'aqualuxe'),
        'section'  => 'aqualuxe_branding',
    )));
    
    // Accent Color
    $wp_customize->add_setting('aqualuxe_accent_color', array(
        'default'           => '#00a0d2',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', array(
        'label'    => __('Accent Color', 'aqualuxe'),
        'section'  => 'aqualuxe_branding',
    )));
    
    // Favicon - Already handled by core WordPress
}

// Add the branding section to the customizer
add_action('customize_register', 'aqualuxe_customize_register_branding');

/**
 * Add branding CSS to the head.
 */
function aqualuxe_branding_css() {
    $logo_width = get_theme_mod('aqualuxe_logo_width', 180);
    $logo_height = get_theme_mod('aqualuxe_logo_height', 60);
    
    ?>
    <style type="text/css">
        .custom-logo-link img {
            width: <?php echo esc_attr($logo_width); ?>px;
            max-height: <?php echo esc_attr($logo_height); ?>px;
        }
        
        @media (max-width: 768px) {
            .custom-logo-link img {
                width: <?php echo esc_attr($logo_width * 0.8); ?>px;
                max-height: <?php echo esc_attr($logo_height * 0.8); ?>px;
            }
        }
    </style>
    <?php
}
add_action('wp_head', 'aqualuxe_branding_css');

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_branding_customize_preview_js() {
    wp_add_inline_script('aqualuxe-customizer', '
        // Logo width
        wp.customize("aqualuxe_logo_width", function(value) {
            value.bind(function(to) {
                $(".custom-logo-link img").css("width", to + "px");
            });
        });
        
        // Logo height
        wp.customize("aqualuxe_logo_height", function(value) {
            value.bind(function(to) {
                $(".custom-logo-link img").css("max-height", to + "px");
            });
        });
        
        // Show site title
        wp.customize("aqualuxe_show_site_title", function(value) {
            value.bind(function(to) {
                if (to) {
                    $(".site-title").css({
                        "clip": "auto",
                        "position": "relative"
                    });
                } else {
                    $(".site-title").css({
                        "clip": "rect(1px, 1px, 1px, 1px)",
                        "position": "absolute"
                    });
                }
            });
        });
        
        // Show tagline
        wp.customize("aqualuxe_show_tagline", function(value) {
            value.bind(function(to) {
                if (to) {
                    $(".site-description").css({
                        "clip": "auto",
                        "position": "relative"
                    });
                } else {
                    $(".site-description").css({
                        "clip": "rect(1px, 1px, 1px, 1px)",
                        "position": "absolute"
                    });
                }
            });
        });
        
        // Brand color
        wp.customize("aqualuxe_brand_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-primary-color", to);
            });
        });
        
        // Secondary brand color
        wp.customize("aqualuxe_secondary_brand_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-secondary-color", to);
            });
        });
        
        // Accent color
        wp.customize("aqualuxe_accent_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-accent-color", to);
            });
        });
    ');
}
add_action('customize_preview_init', 'aqualuxe_branding_customize_preview_js', 20);