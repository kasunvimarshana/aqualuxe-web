<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register( $wp_customize ) {
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    if ( isset( $wp_customize->selective_refresh ) ) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector'        => '.site-title a',
                'render_callback' => 'aqualuxe_customize_partial_blogname',
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector'        => '.site-description',
                'render_callback' => 'aqualuxe_customize_partial_blogdescription',
            )
        );
    }

    // Add Theme Options Panel
    $wp_customize->add_panel(
        'aqualuxe_theme_options',
        array(
            'title'       => __( 'Theme Options', 'aqualuxe' ),
            'description' => __( 'Theme Options for AquaLuxe', 'aqualuxe' ),
            'priority'    => 130,
        )
    );

    // Add Header Section
    $wp_customize->add_section(
        'aqualuxe_header_options',
        array(
            'title'       => __( 'Header Options', 'aqualuxe' ),
            'description' => __( 'Customize the header section', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 10,
        )
    );

    // Header Layout
    $wp_customize->add_setting(
        'aqualuxe_header_layout',
        array(
            'default'           => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_layout',
        array(
            'label'       => __( 'Header Layout', 'aqualuxe' ),
            'description' => __( 'Select the header layout', 'aqualuxe' ),
            'section'     => 'aqualuxe_header_options',
            'type'        => 'select',
            'choices'     => array(
                'default'      => __( 'Default', 'aqualuxe' ),
                'centered'     => __( 'Centered', 'aqualuxe' ),
                'split'        => __( 'Split', 'aqualuxe' ),
                'transparent'  => __( 'Transparent', 'aqualuxe' ),
                'minimal'      => __( 'Minimal', 'aqualuxe' ),
            ),
        )
    );

    // Sticky Header
    $wp_customize->add_setting(
        'aqualuxe_sticky_header',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_sticky_header',
        array(
            'label'       => __( 'Sticky Header', 'aqualuxe' ),
            'description' => __( 'Enable sticky header', 'aqualuxe' ),
            'section'     => 'aqualuxe_header_options',
            'type'        => 'checkbox',
        )
    );

    // Add Footer Section
    $wp_customize->add_section(
        'aqualuxe_footer_options',
        array(
            'title'       => __( 'Footer Options', 'aqualuxe' ),
            'description' => __( 'Customize the footer section', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 20,
        )
    );

    // Footer Layout
    $wp_customize->add_setting(
        'aqualuxe_footer_layout',
        array(
            'default'           => '4-columns',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_layout',
        array(
            'label'       => __( 'Footer Layout', 'aqualuxe' ),
            'description' => __( 'Select the footer layout', 'aqualuxe' ),
            'section'     => 'aqualuxe_footer_options',
            'type'        => 'select',
            'choices'     => array(
                '4-columns'     => __( '4 Columns', 'aqualuxe' ),
                '3-columns'     => __( '3 Columns', 'aqualuxe' ),
                '2-columns'     => __( '2 Columns', 'aqualuxe' ),
                '1-column'      => __( '1 Column', 'aqualuxe' ),
                'custom'        => __( 'Custom', 'aqualuxe' ),
            ),
        )
    );

    // Footer Copyright Text
    $wp_customize->add_setting(
        'aqualuxe_footer_copyright',
        array(
            'default'           => sprintf( __( '© %s AquaLuxe. All rights reserved.', 'aqualuxe' ), date( 'Y' ) ),
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_copyright',
        array(
            'label'       => __( 'Copyright Text', 'aqualuxe' ),
            'description' => __( 'Enter your copyright text', 'aqualuxe' ),
            'section'     => 'aqualuxe_footer_options',
            'type'        => 'textarea',
        )
    );

    // Add Colors Section
    $wp_customize->add_section(
        'aqualuxe_colors',
        array(
            'title'       => __( 'Colors', 'aqualuxe' ),
            'description' => __( 'Customize theme colors', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 30,
        )
    );

    // Primary Color
    $wp_customize->add_setting(
        'aqualuxe_primary_color',
        array(
            'default'           => '#0073aa',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new \WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_primary_color',
            array(
                'label'       => __( 'Primary Color', 'aqualuxe' ),
                'description' => __( 'Select the primary color', 'aqualuxe' ),
                'section'     => 'aqualuxe_colors',
            )
        )
    );

    // Secondary Color
    $wp_customize->add_setting(
        'aqualuxe_secondary_color',
        array(
            'default'           => '#23282d',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new \WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_secondary_color',
            array(
                'label'       => __( 'Secondary Color', 'aqualuxe' ),
                'description' => __( 'Select the secondary color', 'aqualuxe' ),
                'section'     => 'aqualuxe_colors',
            )
        )
    );

    // Accent Color
    $wp_customize->add_setting(
        'aqualuxe_accent_color',
        array(
            'default'           => '#00a0d2',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new \WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_accent_color',
            array(
                'label'       => __( 'Accent Color', 'aqualuxe' ),
                'description' => __( 'Select the accent color', 'aqualuxe' ),
                'section'     => 'aqualuxe_colors',
            )
        )
    );

    // Add Typography Section
    $wp_customize->add_section(
        'aqualuxe_typography',
        array(
            'title'       => __( 'Typography', 'aqualuxe' ),
            'description' => __( 'Customize theme typography', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 40,
        )
    );

    // Body Font Family
    $wp_customize->add_setting(
        'aqualuxe_body_font_family',
        array(
            'default'           => 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_font_family',
        array(
            'label'       => __( 'Body Font Family', 'aqualuxe' ),
            'description' => __( 'Enter the body font family', 'aqualuxe' ),
            'section'     => 'aqualuxe_typography',
            'type'        => 'text',
        )
    );

    // Heading Font Family
    $wp_customize->add_setting(
        'aqualuxe_heading_font_family',
        array(
            'default'           => 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_font_family',
        array(
            'label'       => __( 'Heading Font Family', 'aqualuxe' ),
            'description' => __( 'Enter the heading font family', 'aqualuxe' ),
            'section'     => 'aqualuxe_typography',
            'type'        => 'text',
        )
    );

    // Add Layout Section
    $wp_customize->add_section(
        'aqualuxe_layout',
        array(
            'title'       => __( 'Layout', 'aqualuxe' ),
            'description' => __( 'Customize theme layout', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 50,
        )
    );

    // Container Width
    $wp_customize->add_setting(
        'aqualuxe_container_width',
        array(
            'default'           => '1200',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_container_width',
        array(
            'label'       => __( 'Container Width (px)', 'aqualuxe' ),
            'description' => __( 'Set the maximum width of the content container', 'aqualuxe' ),
            'section'     => 'aqualuxe_layout',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 960,
                'max'  => 1920,
                'step' => 10,
            ),
        )
    );

    // Sidebar Position
    $wp_customize->add_setting(
        'aqualuxe_sidebar_position',
        array(
            'default'           => 'right',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_sidebar_position',
        array(
            'label'       => __( 'Sidebar Position', 'aqualuxe' ),
            'description' => __( 'Select the sidebar position', 'aqualuxe' ),
            'section'     => 'aqualuxe_layout',
            'type'        => 'select',
            'choices'     => array(
                'right' => __( 'Right', 'aqualuxe' ),
                'left'  => __( 'Left', 'aqualuxe' ),
                'none'  => __( 'No Sidebar', 'aqualuxe' ),
            ),
        )
    );

    // Add Advanced Section
    $wp_customize->add_section(
        'aqualuxe_advanced',
        array(
            'title'       => __( 'Advanced', 'aqualuxe' ),
            'description' => __( 'Advanced theme options', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 60,
        )
    );

    // Enable Dark Mode
    $wp_customize->add_setting(
        'aqualuxe_enable_dark_mode',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_dark_mode',
        array(
            'label'       => __( 'Enable Dark Mode', 'aqualuxe' ),
            'description' => __( 'Allow users to switch to dark mode', 'aqualuxe' ),
            'section'     => 'aqualuxe_advanced',
            'type'        => 'checkbox',
        )
    );

    // Custom CSS
    $wp_customize->add_setting(
        'aqualuxe_custom_css',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_strip_all_tags',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_custom_css',
        array(
            'label'       => __( 'Custom CSS', 'aqualuxe' ),
            'description' => __( 'Add custom CSS styles', 'aqualuxe' ),
            'section'     => 'aqualuxe_advanced',
            'type'        => 'textarea',
        )
    );
}
add_action( 'customize_register', 'aqualuxe_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogname() {
    bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogdescription() {
    bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
    wp_enqueue_script( 'aqualuxe-customizer', AQUALUXE_URI . '/assets/dist/js/customizer.js', array( 'customize-preview' ), AQUALUXE_VERSION, true );
}
add_action( 'customize_preview_init', 'aqualuxe_customize_preview_js' );

/**
 * Sanitize checkbox value
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true === $checked ) ? true : false );
}

/**
 * Sanitize select value
 *
 * @param string $input   The input from the setting.
 * @param object $setting The selected setting.
 * @return string
 */
function aqualuxe_sanitize_select( $input, $setting ) {
    // Get the list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control( $setting->id )->choices;

    // If the input is a valid key, return it; otherwise, return the default.
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Generate inline CSS for customizer options
 */
function aqualuxe_customizer_inline_css() {
    $primary_color   = get_theme_mod( 'aqualuxe_primary_color', '#0073aa' );
    $secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#23282d' );
    $accent_color    = get_theme_mod( 'aqualuxe_accent_color', '#00a0d2' );
    $body_font       = get_theme_mod( 'aqualuxe_body_font_family', 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif' );
    $heading_font    = get_theme_mod( 'aqualuxe_heading_font_family', 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif' );
    $container_width = get_theme_mod( 'aqualuxe_container_width', '1200' );
    $custom_css      = get_theme_mod( 'aqualuxe_custom_css', '' );

    $css = "
        :root {
            --aqualuxe-primary-color: {$primary_color};
            --aqualuxe-secondary-color: {$secondary_color};
            --aqualuxe-accent-color: {$accent_color};
            --aqualuxe-body-font: {$body_font};
            --aqualuxe-heading-font: {$heading_font};
            --aqualuxe-container-width: {$container_width}px;
        }
        
        body {
            font-family: var(--aqualuxe-body-font);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--aqualuxe-heading-font);
        }
        
        .container {
            max-width: var(--aqualuxe-container-width);
        }
        
        a {
            color: var(--aqualuxe-primary-color);
        }
        
        a:hover {
            color: var(--aqualuxe-accent-color);
        }
        
        .btn-primary {
            background-color: var(--aqualuxe-primary-color);
            border-color: var(--aqualuxe-primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--aqualuxe-accent-color);
            border-color: var(--aqualuxe-accent-color);
        }
        
        {$custom_css}
    ";

    wp_add_inline_style( 'aqualuxe-main', $css );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_customizer_inline_css', 20 );