<?php
/**
 * Theme Customizer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add postMessage support for site title and description for the Theme Customizer
 */
function aqualuxe_customize_register($wp_customize) {
    
    // Update transport method for default controls
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial('blogname', array(
            'selector'        => '.site-title a',
            'render_callback' => 'aqualuxe_customize_partial_blogname',
        ));
        $wp_customize->selective_refresh->add_partial('blogdescription', array(
            'selector'        => '.site-description',
            'render_callback' => 'aqualuxe_customize_partial_blogdescription',
        ));
    }

    // AquaLuxe Theme Options Panel
    $wp_customize->add_panel('aqualuxe_theme_options', array(
        'title'       => __('AquaLuxe Theme Options', 'aqualuxe'),
        'description' => __('Customize your AquaLuxe theme settings', 'aqualuxe'),
        'priority'    => 10,
    ));

    // Header Section
    $wp_customize->add_section('aqualuxe_header', array(
        'title'    => __('Header Settings', 'aqualuxe'),
        'panel'    => 'aqualuxe_theme_options',
        'priority' => 10,
    ));

    // Header Layout
    $wp_customize->add_setting('aqualuxe_header_layout', array(
        'default'           => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_header_layout', array(
        'label'    => __('Header Layout', 'aqualuxe'),
        'section'  => 'aqualuxe_header',
        'type'     => 'select',
        'choices'  => array(
            'default' => __('Default', 'aqualuxe'),
            'centered' => __('Centered', 'aqualuxe'),
            'minimal' => __('Minimal', 'aqualuxe'),
        ),
    ));

    // Sticky Header
    $wp_customize->add_setting('aqualuxe_sticky_header', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_sticky_header', array(
        'label'   => __('Enable Sticky Header', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'type'    => 'checkbox',
    ));

    // Colors Section
    $wp_customize->add_section('aqualuxe_colors', array(
        'title'    => __('Color Settings', 'aqualuxe'),
        'panel'    => 'aqualuxe_theme_options',
        'priority' => 20,
    ));

    // Primary Color
    $wp_customize->add_setting('aqualuxe_primary_color', array(
        'default'           => '#0891b2',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', array(
        'label'   => __('Primary Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
    )));

    // Secondary Color
    $wp_customize->add_setting('aqualuxe_secondary_color', array(
        'default'           => '#14b8a6',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', array(
        'label'   => __('Secondary Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
    )));

    // Accent Color
    $wp_customize->add_setting('aqualuxe_accent_color', array(
        'default'           => '#06b6d4',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', array(
        'label'   => __('Accent Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
    )));

    // Typography Section
    $wp_customize->add_section('aqualuxe_typography', array(
        'title'    => __('Typography', 'aqualuxe'),
        'panel'    => 'aqualuxe_theme_options',
        'priority' => 30,
    ));

    // Body Font
    $wp_customize->add_setting('aqualuxe_body_font', array(
        'default'           => 'Inter',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_body_font', array(
        'label'   => __('Body Font Family', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type'    => 'select',
        'choices' => array(
            'Inter' => 'Inter',
            'Roboto' => 'Roboto',
            'Open Sans' => 'Open Sans',
            'Lato' => 'Lato',
            'Nunito' => 'Nunito',
            'Poppins' => 'Poppins',
        ),
    ));

    // Heading Font
    $wp_customize->add_setting('aqualuxe_heading_font', array(
        'default'           => 'Playfair Display',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_heading_font', array(
        'label'   => __('Heading Font Family', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type'    => 'select',
        'choices' => array(
            'Playfair Display' => 'Playfair Display',
            'Merriweather' => 'Merriweather',
            'Source Serif Pro' => 'Source Serif Pro',
            'Lora' => 'Lora',
            'Crimson Text' => 'Crimson Text',
            'Inter' => 'Inter (Same as body)',
        ),
    ));

    // Font Size
    $wp_customize->add_setting('aqualuxe_font_size', array(
        'default'           => 'medium',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_font_size', array(
        'label'   => __('Base Font Size', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type'    => 'select',
        'choices' => array(
            'small'  => __('Small', 'aqualuxe'),
            'medium' => __('Medium', 'aqualuxe'),
            'large'  => __('Large', 'aqualuxe'),
        ),
    ));

    // Layout Section
    $wp_customize->add_section('aqualuxe_layout', array(
        'title'    => __('Layout Settings', 'aqualuxe'),
        'panel'    => 'aqualuxe_theme_options',
        'priority' => 40,
    ));

    // Container Width
    $wp_customize->add_setting('aqualuxe_container_width', array(
        'default'           => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_container_width', array(
        'label'   => __('Container Width', 'aqualuxe'),
        'section' => 'aqualuxe_layout',
        'type'    => 'select',
        'choices' => array(
            'narrow'  => __('Narrow (1200px)', 'aqualuxe'),
            'default' => __('Default (1400px)', 'aqualuxe'),
            'wide'    => __('Wide (1600px)', 'aqualuxe'),
            'full'    => __('Full Width', 'aqualuxe'),
        ),
    ));

    // Blog Layout
    $wp_customize->add_setting('aqualuxe_blog_layout', array(
        'default'           => 'grid',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_blog_layout', array(
        'label'   => __('Blog Layout', 'aqualuxe'),
        'section' => 'aqualuxe_layout',
        'type'    => 'select',
        'choices' => array(
            'list' => __('List', 'aqualuxe'),
            'grid' => __('Grid', 'aqualuxe'),
            'masonry' => __('Masonry', 'aqualuxe'),
        ),
    ));

    // Sidebar Position
    $wp_customize->add_setting('aqualuxe_sidebar_position', array(
        'default'           => 'right',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_sidebar_position', array(
        'label'   => __('Sidebar Position', 'aqualuxe'),
        'section' => 'aqualuxe_layout',
        'type'    => 'select',
        'choices' => array(
            'left'  => __('Left', 'aqualuxe'),
            'right' => __('Right', 'aqualuxe'),
            'none'  => __('No Sidebar', 'aqualuxe'),
        ),
    ));

    // Performance Section
    $wp_customize->add_section('aqualuxe_performance', array(
        'title'    => __('Performance', 'aqualuxe'),
        'panel'    => 'aqualuxe_theme_options',
        'priority' => 50,
    ));

    // Lazy Loading
    $wp_customize->add_setting('aqualuxe_lazy_loading', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_lazy_loading', array(
        'label'   => __('Enable Lazy Loading', 'aqualuxe'),
        'section' => 'aqualuxe_performance',
        'type'    => 'checkbox',
    ));

    // Minify CSS/JS
    $wp_customize->add_setting('aqualuxe_minify_assets', array(
        'default'           => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_minify_assets', array(
        'label'       => __('Minify CSS/JS', 'aqualuxe'),
        'description' => __('Enable asset minification (requires build tools)', 'aqualuxe'),
        'section'     => 'aqualuxe_performance',
        'type'        => 'checkbox',
    ));

    // Social Media Section
    $wp_customize->add_section('aqualuxe_social', array(
        'title'    => __('Social Media', 'aqualuxe'),
        'panel'    => 'aqualuxe_theme_options',
        'priority' => 60,
    ));

    // Social Media Links
    $social_networks = array(
        'facebook'  => __('Facebook', 'aqualuxe'),
        'twitter'   => __('Twitter', 'aqualuxe'),
        'instagram' => __('Instagram', 'aqualuxe'),
        'linkedin'  => __('LinkedIn', 'aqualuxe'),
        'youtube'   => __('YouTube', 'aqualuxe'),
        'pinterest' => __('Pinterest', 'aqualuxe'),
    );

    foreach ($social_networks as $network => $label) {
        $wp_customize->add_setting("aqualuxe_social_{$network}", array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control("aqualuxe_social_{$network}", array(
            'label'   => $label . ' ' . __('URL', 'aqualuxe'),
            'section' => 'aqualuxe_social',
            'type'    => 'url',
        ));
    }

    // Footer Section
    $wp_customize->add_section('aqualuxe_footer', array(
        'title'    => __('Footer Settings', 'aqualuxe'),
        'panel'    => 'aqualuxe_theme_options',
        'priority' => 70,
    ));

    // Footer Copyright
    $wp_customize->add_setting('aqualuxe_footer_copyright', array(
        'default'           => sprintf(__('© %s %s. All rights reserved.', 'aqualuxe'), date('Y'), get_bloginfo('name')),
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_footer_copyright', array(
        'label'   => __('Copyright Text', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'type'    => 'textarea',
    ));

    // Show Back to Top
    $wp_customize->add_setting('aqualuxe_back_to_top', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_back_to_top', array(
        'label'   => __('Show Back to Top Button', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'type'    => 'checkbox',
    ));
}
add_action('customize_register', 'aqualuxe_customize_register');

/**
 * Render the site title for the selective refresh partial
 */
function aqualuxe_customize_partial_blogname() {
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial
 */
function aqualuxe_customize_partial_blogdescription() {
    bloginfo('description');
}

/**
 * Sanitize select input
 */
function aqualuxe_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize checkbox input
 */
function aqualuxe_sanitize_checkbox($checked) {
    return ((isset($checked) && true === $checked) ? true : false);
}

/**
 * Bind JS handlers to instantly live-preview changes
 * 
 * Note: Customizer preview scripts are handled in core/functions/enqueue-scripts.php
 */