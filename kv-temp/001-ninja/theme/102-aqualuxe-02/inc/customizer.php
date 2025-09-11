<?php
/**
 * Customizer Integration
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register( $wp_customize ) {
    
    // Add postMessage support for site title and tagline
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

    // Theme Options Panel
    $wp_customize->add_panel( 'aqualuxe_theme_options', array(
        'title'       => esc_html__( 'AquaLuxe Options', 'aqualuxe' ),
        'description' => esc_html__( 'Customize your AquaLuxe theme settings.', 'aqualuxe' ),
        'priority'    => 30,
    ) );

    // Colors Section
    $wp_customize->add_section( 'aqualuxe_colors', array(
        'title'    => esc_html__( 'Colors', 'aqualuxe' ),
        'panel'    => 'aqualuxe_theme_options',
        'priority' => 10,
    ) );

    // Primary Color
    $wp_customize->add_setting( 'aqualuxe_primary_color', array(
        'default'           => '#0ea5e9',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_primary_color', array(
        'label'    => esc_html__( 'Primary Color', 'aqualuxe' ),
        'section'  => 'aqualuxe_colors',
        'priority' => 10,
    ) ) );

    // Secondary Color
    $wp_customize->add_setting( 'aqualuxe_secondary_color', array(
        'default'           => '#64748b',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_secondary_color', array(
        'label'    => esc_html__( 'Secondary Color', 'aqualuxe' ),
        'section'  => 'aqualuxe_colors',
        'priority' => 20,
    ) ) );

    // Accent Color
    $wp_customize->add_setting( 'aqualuxe_accent_color', array(
        'default'           => '#22c55e',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_accent_color', array(
        'label'    => esc_html__( 'Accent Color', 'aqualuxe' ),
        'section'  => 'aqualuxe_colors',
        'priority' => 30,
    ) ) );

    // Typography Section
    $wp_customize->add_section( 'aqualuxe_typography', array(
        'title'    => esc_html__( 'Typography', 'aqualuxe' ),
        'panel'    => 'aqualuxe_theme_options',
        'priority' => 20,
    ) );

    // Font Family
    $wp_customize->add_setting( 'aqualuxe_font_family', array(
        'default'           => 'Inter, system-ui, sans-serif',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'aqualuxe_font_family', array(
        'label'    => esc_html__( 'Font Family', 'aqualuxe' ),
        'section'  => 'aqualuxe_typography',
        'type'     => 'select',
        'choices'  => array(
            'Inter, system-ui, sans-serif'           => 'Inter',
            'Roboto, sans-serif'                     => 'Roboto',
            'Open Sans, sans-serif'                  => 'Open Sans',
            'Lato, sans-serif'                       => 'Lato',
            'Merriweather, Georgia, serif'           => 'Merriweather',
            'Playfair Display, Georgia, serif'       => 'Playfair Display',
            'JetBrains Mono, Consolas, monospace'    => 'JetBrains Mono',
        ),
        'priority' => 10,
    ) );

    // Font Size
    $wp_customize->add_setting( 'aqualuxe_font_size', array(
        'default'           => 16,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'aqualuxe_font_size', array(
        'label'       => esc_html__( 'Base Font Size (px)', 'aqualuxe' ),
        'section'     => 'aqualuxe_typography',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 12,
            'max'  => 24,
            'step' => 1,
        ),
        'priority'    => 20,
    ) );

    // Layout Section
    $wp_customize->add_section( 'aqualuxe_layout', array(
        'title'    => esc_html__( 'Layout', 'aqualuxe' ),
        'panel'    => 'aqualuxe_theme_options',
        'priority' => 30,
    ) );

    // Container Width
    $wp_customize->add_setting( 'aqualuxe_container_width', array(
        'default'           => 1280,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'aqualuxe_container_width', array(
        'label'       => esc_html__( 'Container Max Width (px)', 'aqualuxe' ),
        'section'     => 'aqualuxe_layout',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 960,
            'max'  => 1920,
            'step' => 40,
        ),
        'priority'    => 10,
    ) );

    // Header Layout
    $wp_customize->add_setting( 'aqualuxe_header_layout', array(
        'default'           => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'aqualuxe_header_layout', array(
        'label'    => esc_html__( 'Header Layout', 'aqualuxe' ),
        'section'  => 'aqualuxe_layout',
        'type'     => 'select',
        'choices'  => array(
            'default'  => esc_html__( 'Default', 'aqualuxe' ),
            'centered' => esc_html__( 'Centered', 'aqualuxe' ),
            'minimal'  => esc_html__( 'Minimal', 'aqualuxe' ),
        ),
        'priority' => 20,
    ) );

    // Sidebar Position
    $wp_customize->add_setting( 'aqualuxe_sidebar_position', array(
        'default'           => 'right',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'aqualuxe_sidebar_position', array(
        'label'    => esc_html__( 'Sidebar Position', 'aqualuxe' ),
        'section'  => 'aqualuxe_layout',
        'type'     => 'select',
        'choices'  => array(
            'left'  => esc_html__( 'Left', 'aqualuxe' ),
            'right' => esc_html__( 'Right', 'aqualuxe' ),
            'none'  => esc_html__( 'No Sidebar', 'aqualuxe' ),
        ),
        'priority' => 30,
    ) );

    // Blog Section
    $wp_customize->add_section( 'aqualuxe_blog', array(
        'title'    => esc_html__( 'Blog', 'aqualuxe' ),
        'panel'    => 'aqualuxe_theme_options',
        'priority' => 40,
    ) );

    // Excerpt Length
    $wp_customize->add_setting( 'aqualuxe_excerpt_length', array(
        'default'           => 25,
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'aqualuxe_excerpt_length', array(
        'label'       => esc_html__( 'Excerpt Length (words)', 'aqualuxe' ),
        'section'     => 'aqualuxe_blog',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 10,
            'max'  => 100,
            'step' => 5,
        ),
        'priority'    => 10,
    ) );

    // Show Featured Image
    $wp_customize->add_setting( 'aqualuxe_show_featured_image', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );

    $wp_customize->add_control( 'aqualuxe_show_featured_image', array(
        'label'    => esc_html__( 'Show Featured Image on Blog', 'aqualuxe' ),
        'section'  => 'aqualuxe_blog',
        'type'     => 'checkbox',
        'priority' => 20,
    ) );

    // Show Post Meta
    $wp_customize->add_setting( 'aqualuxe_show_post_meta', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );

    $wp_customize->add_control( 'aqualuxe_show_post_meta', array(
        'label'    => esc_html__( 'Show Post Meta (date, author)', 'aqualuxe' ),
        'section'  => 'aqualuxe_blog',
        'type'     => 'checkbox',
        'priority' => 30,
    ) );

    // Footer Section
    $wp_customize->add_section( 'aqualuxe_footer', array(
        'title'    => esc_html__( 'Footer', 'aqualuxe' ),
        'panel'    => 'aqualuxe_theme_options',
        'priority' => 50,
    ) );

    // Footer Description
    $wp_customize->add_setting( 'aqualuxe_footer_description', array(
        'default'           => esc_html__( 'Bringing elegance to aquatic life – globally. Premium ornamental fish, aquatic plants, and professional aquascaping services.', 'aqualuxe' ),
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );

    $wp_customize->add_control( 'aqualuxe_footer_description', array(
        'label'    => esc_html__( 'Footer Description', 'aqualuxe' ),
        'section'  => 'aqualuxe_footer',
        'type'     => 'textarea',
        'priority' => 10,
    ) );

    // Social Media Links
    $social_networks = array(
        'facebook'  => 'Facebook',
        'twitter'   => 'Twitter',
        'instagram' => 'Instagram',
        'youtube'   => 'YouTube',
        'linkedin'  => 'LinkedIn',
    );

    $priority = 20;
    foreach ( $social_networks as $network => $label ) {
        $wp_customize->add_setting( "aqualuxe_{$network}_url", array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ) );

        $wp_customize->add_control( "aqualuxe_{$network}_url", array(
            'label'    => sprintf( esc_html__( '%s URL', 'aqualuxe' ), $label ),
            'section'  => 'aqualuxe_footer',
            'type'     => 'url',
            'priority' => $priority,
        ) );

        $priority += 10;
    }

    // Contact Information
    $wp_customize->add_setting( 'aqualuxe_contact_address', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );

    $wp_customize->add_control( 'aqualuxe_contact_address', array(
        'label'    => esc_html__( 'Contact Address', 'aqualuxe' ),
        'section'  => 'aqualuxe_footer',
        'type'     => 'textarea',
        'priority' => 100,
    ) );

    $wp_customize->add_setting( 'aqualuxe_contact_phone', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'aqualuxe_contact_phone', array(
        'label'    => esc_html__( 'Contact Phone', 'aqualuxe' ),
        'section'  => 'aqualuxe_footer',
        'type'     => 'tel',
        'priority' => 110,
    ) );

    $wp_customize->add_setting( 'aqualuxe_contact_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
    ) );

    $wp_customize->add_control( 'aqualuxe_contact_email', array(
        'label'    => esc_html__( 'Contact Email', 'aqualuxe' ),
        'section'  => 'aqualuxe_footer',
        'type'     => 'email',
        'priority' => 120,
    ) );

    // Performance Section
    $wp_customize->add_section( 'aqualuxe_performance', array(
        'title'    => esc_html__( 'Performance', 'aqualuxe' ),
        'panel'    => 'aqualuxe_theme_options',
        'priority' => 60,
    ) );

    // Critical CSS
    $wp_customize->add_setting( 'aqualuxe_critical_css', array(
        'default'           => '',
        'sanitize_callback' => 'wp_strip_all_tags',
    ) );

    $wp_customize->add_control( 'aqualuxe_critical_css', array(
        'label'       => esc_html__( 'Critical CSS', 'aqualuxe' ),
        'description' => esc_html__( 'Add critical CSS to be inlined in the head for faster loading.', 'aqualuxe' ),
        'section'     => 'aqualuxe_performance',
        'type'        => 'textarea',
        'priority'    => 10,
    ) );
}
add_action( 'customize_register', 'aqualuxe_customize_register' );

/**
 * Sanitize select options
 */
function aqualuxe_sanitize_select( $input, $setting ) {
    $input = sanitize_key( $input );
    $choices = $setting->manager->get_control( $setting->id )->choices;
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

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
    wp_enqueue_script( 'aqualuxe-customizer', get_template_directory_uri() . '/assets/dist/js/customizer.js', array( 'customize-preview' ), AQUALUXE_VERSION, true );
}
add_action( 'customize_preview_init', 'aqualuxe_customize_preview_js' );