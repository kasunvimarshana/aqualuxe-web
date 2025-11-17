<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register($wp_customize) {
    $wp_customize->get_setting('blogname')->transport         = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
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

    // Add theme options panel
    $wp_customize->add_panel(
        'aqualuxe_theme_options',
        array(
            'title'       => __('AquaLuxe Theme Options', 'aqualuxe'),
            'description' => __('Customize your theme settings', 'aqualuxe'),
            'priority'    => 130,
        )
    );

    // Add sections
    aqualuxe_customize_colors($wp_customize);
    aqualuxe_customize_header($wp_customize);
    aqualuxe_customize_footer($wp_customize);
    aqualuxe_customize_layout($wp_customize);
    aqualuxe_customize_typography($wp_customize);
    aqualuxe_customize_social_media($wp_customize);
    aqualuxe_customize_woocommerce($wp_customize);
    aqualuxe_customize_blog($wp_customize);
    aqualuxe_customize_performance($wp_customize);
}
add_action('customize_register', 'aqualuxe_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogname() {
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogdescription() {
    bloginfo('description');
}

/**
 * Colors section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_colors($wp_customize) {
    $wp_customize->add_section(
        'aqualuxe_colors',
        array(
            'title'    => __('Colors', 'aqualuxe'),
            'panel'    => 'aqualuxe_theme_options',
            'priority' => 10,
        )
    );

    // Primary Color
    $wp_customize->add_setting(
        'aqualuxe_primary_color',
        array(
            'default'           => '#0891b2',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_primary_color',
            array(
                'label'    => __('Primary Color', 'aqualuxe'),
                'section'  => 'aqualuxe_colors',
                'settings' => 'aqualuxe_primary_color',
            )
        )
    );

    // Secondary Color
    $wp_customize->add_setting(
        'aqualuxe_secondary_color',
        array(
            'default'           => '#7c3aed',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_secondary_color',
            array(
                'label'    => __('Secondary Color', 'aqualuxe'),
                'section'  => 'aqualuxe_colors',
                'settings' => 'aqualuxe_secondary_color',
            )
        )
    );

    // Body Background Color
    $wp_customize->add_setting(
        'aqualuxe_body_bg_color',
        array(
            'default'           => '#f0f9ff',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_body_bg_color',
            array(
                'label'    => __('Body Background Color', 'aqualuxe'),
                'section'  => 'aqualuxe_colors',
                'settings' => 'aqualuxe_body_bg_color',
            )
        )
    );

    // Text Color
    $wp_customize->add_setting(
        'aqualuxe_text_color',
        array(
            'default'           => '#1f2937',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_text_color',
            array(
                'label'    => __('Text Color', 'aqualuxe'),
                'section'  => 'aqualuxe_colors',
                'settings' => 'aqualuxe_text_color',
            )
        )
    );

    // Dark Mode Colors
    $wp_customize->add_setting(
        'aqualuxe_dark_mode_bg_color',
        array(
            'default'           => '#111827',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_mode_bg_color',
            array(
                'label'    => __('Dark Mode Background Color', 'aqualuxe'),
                'section'  => 'aqualuxe_colors',
                'settings' => 'aqualuxe_dark_mode_bg_color',
            )
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_dark_mode_text_color',
        array(
            'default'           => '#f3f4f6',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_mode_text_color',
            array(
                'label'    => __('Dark Mode Text Color', 'aqualuxe'),
                'section'  => 'aqualuxe_colors',
                'settings' => 'aqualuxe_dark_mode_text_color',
            )
        )
    );
}

/**
 * Header section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_header($wp_customize) {
    $wp_customize->add_section(
        'aqualuxe_header',
        array(
            'title'    => __('Header', 'aqualuxe'),
            'panel'    => 'aqualuxe_theme_options',
            'priority' => 20,
        )
    );

    // Top Bar
    $wp_customize->add_setting(
        'aqualuxe_enable_top_bar',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_top_bar',
        array(
            'label'    => __('Enable Top Bar', 'aqualuxe'),
            'section'  => 'aqualuxe_header',
            'type'     => 'checkbox',
            'priority' => 10,
        )
    );

    // Top Bar Background Color
    $wp_customize->add_setting(
        'aqualuxe_top_bar_bg_color',
        array(
            'default'           => '#0c4a6e',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_top_bar_bg_color',
            array(
                'label'    => __('Top Bar Background Color', 'aqualuxe'),
                'section'  => 'aqualuxe_header',
                'settings' => 'aqualuxe_top_bar_bg_color',
                'priority' => 20,
            )
        )
    );

    // Top Bar Text Color
    $wp_customize->add_setting(
        'aqualuxe_top_bar_text_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_top_bar_text_color',
            array(
                'label'    => __('Top Bar Text Color', 'aqualuxe'),
                'section'  => 'aqualuxe_header',
                'settings' => 'aqualuxe_top_bar_text_color',
                'priority' => 30,
            )
        )
    );

    // Contact Information
    $wp_customize->add_setting(
        'aqualuxe_contact_phone',
        array(
            'default'           => '+1 (555) 123-4567',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_contact_phone',
        array(
            'label'    => __('Phone Number', 'aqualuxe'),
            'section'  => 'aqualuxe_header',
            'type'     => 'text',
            'priority' => 40,
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_contact_email',
        array(
            'default'           => 'info@aqualuxe.com',
            'sanitize_callback' => 'sanitize_email',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_contact_email',
        array(
            'label'    => __('Email Address', 'aqualuxe'),
            'section'  => 'aqualuxe_header',
            'type'     => 'email',
            'priority' => 50,
        )
    );

    // Header Style
    $wp_customize->add_setting(
        'aqualuxe_header_style',
        array(
            'default'           => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_style',
        array(
            'label'    => __('Header Style', 'aqualuxe'),
            'section'  => 'aqualuxe_header',
            'type'     => 'select',
            'choices'  => array(
                'default'    => __('Default', 'aqualuxe'),
                'centered'   => __('Centered Logo', 'aqualuxe'),
                'transparent' => __('Transparent', 'aqualuxe'),
                'minimal'    => __('Minimal', 'aqualuxe'),
            ),
            'priority' => 60,
        )
    );

    // Header Background Color
    $wp_customize->add_setting(
        'aqualuxe_header_bg_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_header_bg_color',
            array(
                'label'    => __('Header Background Color', 'aqualuxe'),
                'section'  => 'aqualuxe_header',
                'settings' => 'aqualuxe_header_bg_color',
                'priority' => 70,
            )
        )
    );

    // Header Text Color
    $wp_customize->add_setting(
        'aqualuxe_header_text_color',
        array(
            'default'           => '#1f2937',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_header_text_color',
            array(
                'label'    => __('Header Text Color', 'aqualuxe'),
                'section'  => 'aqualuxe_header',
                'settings' => 'aqualuxe_header_text_color',
                'priority' => 80,
            )
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
            'label'    => __('Enable Sticky Header', 'aqualuxe'),
            'section'  => 'aqualuxe_header',
            'type'     => 'checkbox',
            'priority' => 90,
        )
    );

    // Dark Mode Toggle
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
            'label'    => __('Enable Dark Mode Toggle', 'aqualuxe'),
            'section'  => 'aqualuxe_header',
            'type'     => 'checkbox',
            'priority' => 100,
        )
    );

    // Breadcrumbs
    $wp_customize->add_setting(
        'aqualuxe_enable_breadcrumbs',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_breadcrumbs',
        array(
            'label'    => __('Enable Breadcrumbs', 'aqualuxe'),
            'section'  => 'aqualuxe_header',
            'type'     => 'checkbox',
            'priority' => 110,
        )
    );
}

/**
 * Footer section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_footer($wp_customize) {
    $wp_customize->add_section(
        'aqualuxe_footer',
        array(
            'title'    => __('Footer', 'aqualuxe'),
            'panel'    => 'aqualuxe_theme_options',
            'priority' => 30,
        )
    );

    // Footer Style
    $wp_customize->add_setting(
        'aqualuxe_footer_style',
        array(
            'default'           => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_style',
        array(
            'label'    => __('Footer Style', 'aqualuxe'),
            'section'  => 'aqualuxe_footer',
            'type'     => 'select',
            'choices'  => array(
                'default'    => __('Default (4 Columns)', 'aqualuxe'),
                'three-col'  => __('3 Columns', 'aqualuxe'),
                'two-col'    => __('2 Columns', 'aqualuxe'),
                'minimal'    => __('Minimal', 'aqualuxe'),
            ),
            'priority' => 10,
        )
    );

    // Footer Background Color
    $wp_customize->add_setting(
        'aqualuxe_footer_bg_color',
        array(
            'default'           => '#0c4a6e',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_footer_bg_color',
            array(
                'label'    => __('Footer Background Color', 'aqualuxe'),
                'section'  => 'aqualuxe_footer',
                'settings' => 'aqualuxe_footer_bg_color',
                'priority' => 20,
            )
        )
    );

    // Footer Text Color
    $wp_customize->add_setting(
        'aqualuxe_footer_text_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_footer_text_color',
            array(
                'label'    => __('Footer Text Color', 'aqualuxe'),
                'section'  => 'aqualuxe_footer',
                'settings' => 'aqualuxe_footer_text_color',
                'priority' => 30,
            )
        )
    );

    // Footer Copyright Text
    $wp_customize->add_setting(
        'aqualuxe_footer_copyright',
        array(
            'default'           => '© {year} AquaLuxe. All rights reserved.',
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_copyright',
        array(
            'label'       => __('Copyright Text', 'aqualuxe'),
            'description' => __('Use {year} to display the current year.', 'aqualuxe'),
            'section'     => 'aqualuxe_footer',
            'type'        => 'textarea',
            'priority'    => 40,
        )
    );

    // Footer Payment Icons
    $wp_customize->add_setting(
        'aqualuxe_footer_payment_icons',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_payment_icons',
        array(
            'label'    => __('Display Payment Icons', 'aqualuxe'),
            'section'  => 'aqualuxe_footer',
            'type'     => 'checkbox',
            'priority' => 50,
        )
    );

    // Footer Social Icons
    $wp_customize->add_setting(
        'aqualuxe_footer_social_icons',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_social_icons',
        array(
            'label'    => __('Display Social Icons', 'aqualuxe'),
            'section'  => 'aqualuxe_footer',
            'type'     => 'checkbox',
            'priority' => 60,
        )
    );

    // Back to Top Button
    $wp_customize->add_setting(
        'aqualuxe_back_to_top',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_back_to_top',
        array(
            'label'    => __('Display Back to Top Button', 'aqualuxe'),
            'section'  => 'aqualuxe_footer',
            'type'     => 'checkbox',
            'priority' => 70,
        )
    );

    // Footer Newsletter
    $wp_customize->add_setting(
        'aqualuxe_footer_newsletter',
        array(
            'default'           => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_newsletter',
        array(
            'label'    => __('Display Newsletter Form', 'aqualuxe'),
            'section'  => 'aqualuxe_footer',
            'type'     => 'checkbox',
            'priority' => 80,
        )
    );

    // Newsletter Shortcode
    $wp_customize->add_setting(
        'aqualuxe_newsletter_shortcode',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_newsletter_shortcode',
        array(
            'label'       => __('Newsletter Shortcode', 'aqualuxe'),
            'description' => __('Enter the shortcode for your newsletter form.', 'aqualuxe'),
            'section'     => 'aqualuxe_footer',
            'type'        => 'text',
            'priority'    => 90,
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_footer_newsletter', false);
            },
        )
    );
}

/**
 * Layout section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_layout($wp_customize) {
    $wp_customize->add_section(
        'aqualuxe_layout',
        array(
            'title'    => __('Layout', 'aqualuxe'),
            'panel'    => 'aqualuxe_theme_options',
            'priority' => 40,
        )
    );

    // Container Width
    $wp_customize->add_setting(
        'aqualuxe_container_width',
        array(
            'default'           => 1280,
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_container_width',
        array(
            'label'       => __('Container Width (px)', 'aqualuxe'),
            'section'     => 'aqualuxe_layout',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 960,
                'max'  => 1920,
                'step' => 10,
            ),
            'priority'    => 10,
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
            'label'    => __('Sidebar Position', 'aqualuxe'),
            'section'  => 'aqualuxe_layout',
            'type'     => 'select',
            'choices'  => array(
                'right' => __('Right', 'aqualuxe'),
                'left'  => __('Left', 'aqualuxe'),
                'none'  => __('No Sidebar', 'aqualuxe'),
            ),
            'priority' => 20,
        )
    );

    // Sidebar Width
    $wp_customize->add_setting(
        'aqualuxe_sidebar_width',
        array(
            'default'           => 30,
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_sidebar_width',
        array(
            'label'       => __('Sidebar Width (%)', 'aqualuxe'),
            'section'     => 'aqualuxe_layout',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 20,
                'max'  => 40,
                'step' => 1,
            ),
            'priority'    => 30,
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_sidebar_position', 'right') !== 'none';
            },
        )
    );

    // Page Layout
    $wp_customize->add_setting(
        'aqualuxe_page_layout',
        array(
            'default'           => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_page_layout',
        array(
            'label'    => __('Default Page Layout', 'aqualuxe'),
            'section'  => 'aqualuxe_layout',
            'type'     => 'select',
            'choices'  => array(
                'default'    => __('With Sidebar', 'aqualuxe'),
                'full-width' => __('Full Width', 'aqualuxe'),
                'narrow'     => __('Narrow Content', 'aqualuxe'),
            ),
            'priority' => 40,
        )
    );

    // Blog Layout
    $wp_customize->add_setting(
        'aqualuxe_blog_layout',
        array(
            'default'           => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_blog_layout',
        array(
            'label'    => __('Blog Layout', 'aqualuxe'),
            'section'  => 'aqualuxe_layout',
            'type'     => 'select',
            'choices'  => array(
                'default'  => __('Standard', 'aqualuxe'),
                'grid'     => __('Grid', 'aqualuxe'),
                'masonry'  => __('Masonry', 'aqualuxe'),
                'list'     => __('List', 'aqualuxe'),
            ),
            'priority' => 50,
        )
    );

    // Archive Layout
    $wp_customize->add_setting(
        'aqualuxe_archive_layout',
        array(
            'default'           => 'grid',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_archive_layout',
        array(
            'label'    => __('Archive Layout', 'aqualuxe'),
            'section'  => 'aqualuxe_layout',
            'type'     => 'select',
            'choices'  => array(
                'default'  => __('Standard', 'aqualuxe'),
                'grid'     => __('Grid', 'aqualuxe'),
                'masonry'  => __('Masonry', 'aqualuxe'),
                'list'     => __('List', 'aqualuxe'),
            ),
            'priority' => 60,
        )
    );
}

/**
 * Typography section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_typography($wp_customize) {
    $wp_customize->add_section(
        'aqualuxe_typography',
        array(
            'title'    => __('Typography', 'aqualuxe'),
            'panel'    => 'aqualuxe_theme_options',
            'priority' => 50,
        )
    );

    // Body Font
    $wp_customize->add_setting(
        'aqualuxe_body_font',
        array(
            'default'           => 'Montserrat, sans-serif',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_font',
        array(
            'label'    => __('Body Font', 'aqualuxe'),
            'section'  => 'aqualuxe_typography',
            'type'     => 'select',
            'choices'  => aqualuxe_get_google_fonts(),
            'priority' => 10,
        )
    );

    // Heading Font
    $wp_customize->add_setting(
        'aqualuxe_heading_font',
        array(
            'default'           => 'Playfair Display, serif',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_font',
        array(
            'label'    => __('Heading Font', 'aqualuxe'),
            'section'  => 'aqualuxe_typography',
            'type'     => 'select',
            'choices'  => aqualuxe_get_google_fonts(),
            'priority' => 20,
        )
    );

    // Base Font Size
    $wp_customize->add_setting(
        'aqualuxe_base_font_size',
        array(
            'default'           => 16,
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_base_font_size',
        array(
            'label'       => __('Base Font Size (px)', 'aqualuxe'),
            'section'     => 'aqualuxe_typography',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 12,
                'max'  => 24,
                'step' => 1,
            ),
            'priority'    => 30,
        )
    );

    // Line Height
    $wp_customize->add_setting(
        'aqualuxe_line_height',
        array(
            'default'           => 1.6,
            'sanitize_callback' => 'aqualuxe_sanitize_float',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_line_height',
        array(
            'label'       => __('Line Height', 'aqualuxe'),
            'section'     => 'aqualuxe_typography',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 2,
                'step' => 0.1,
            ),
            'priority'    => 40,
        )
    );

    // Font Weight
    $wp_customize->add_setting(
        'aqualuxe_font_weight',
        array(
            'default'           => '400',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_font_weight',
        array(
            'label'    => __('Body Font Weight', 'aqualuxe'),
            'section'  => 'aqualuxe_typography',
            'type'     => 'select',
            'choices'  => array(
                '300' => __('Light (300)', 'aqualuxe'),
                '400' => __('Regular (400)', 'aqualuxe'),
                '500' => __('Medium (500)', 'aqualuxe'),
                '600' => __('Semi-Bold (600)', 'aqualuxe'),
                '700' => __('Bold (700)', 'aqualuxe'),
            ),
            'priority' => 50,
        )
    );

    // Heading Font Weight
    $wp_customize->add_setting(
        'aqualuxe_heading_font_weight',
        array(
            'default'           => '600',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_font_weight',
        array(
            'label'    => __('Heading Font Weight', 'aqualuxe'),
            'section'  => 'aqualuxe_typography',
            'type'     => 'select',
            'choices'  => array(
                '400' => __('Regular (400)', 'aqualuxe'),
                '500' => __('Medium (500)', 'aqualuxe'),
                '600' => __('Semi-Bold (600)', 'aqualuxe'),
                '700' => __('Bold (700)', 'aqualuxe'),
            ),
            'priority' => 60,
        )
    );
}

/**
 * Social Media section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_social_media($wp_customize) {
    $wp_customize->add_section(
        'aqualuxe_social_media',
        array(
            'title'    => __('Social Media', 'aqualuxe'),
            'panel'    => 'aqualuxe_theme_options',
            'priority' => 60,
        )
    );

    // Facebook
    $wp_customize->add_setting(
        'aqualuxe_social_facebook',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_facebook',
        array(
            'label'    => __('Facebook URL', 'aqualuxe'),
            'section'  => 'aqualuxe_social_media',
            'type'     => 'url',
            'priority' => 10,
        )
    );

    // Twitter
    $wp_customize->add_setting(
        'aqualuxe_social_twitter',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_twitter',
        array(
            'label'    => __('Twitter URL', 'aqualuxe'),
            'section'  => 'aqualuxe_social_media',
            'type'     => 'url',
            'priority' => 20,
        )
    );

    // Instagram
    $wp_customize->add_setting(
        'aqualuxe_social_instagram',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_instagram',
        array(
            'label'    => __('Instagram URL', 'aqualuxe'),
            'section'  => 'aqualuxe_social_media',
            'type'     => 'url',
            'priority' => 30,
        )
    );

    // LinkedIn
    $wp_customize->add_setting(
        'aqualuxe_social_linkedin',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_linkedin',
        array(
            'label'    => __('LinkedIn URL', 'aqualuxe'),
            'section'  => 'aqualuxe_social_media',
            'type'     => 'url',
            'priority' => 40,
        )
    );

    // YouTube
    $wp_customize->add_setting(
        'aqualuxe_social_youtube',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_youtube',
        array(
            'label'    => __('YouTube URL', 'aqualuxe'),
            'section'  => 'aqualuxe_social_media',
            'type'     => 'url',
            'priority' => 50,
        )
    );

    // Pinterest
    $wp_customize->add_setting(
        'aqualuxe_social_pinterest',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_pinterest',
        array(
            'label'    => __('Pinterest URL', 'aqualuxe'),
            'section'  => 'aqualuxe_social_media',
            'type'     => 'url',
            'priority' => 60,
        )
    );

    // Social Icons Position
    $wp_customize->add_setting(
        'aqualuxe_social_icons_position',
        array(
            'default'           => 'both',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_icons_position',
        array(
            'label'    => __('Display Social Icons', 'aqualuxe'),
            'section'  => 'aqualuxe_social_media',
            'type'     => 'select',
            'choices'  => array(
                'header' => __('Header Only', 'aqualuxe'),
                'footer' => __('Footer Only', 'aqualuxe'),
                'both'   => __('Header and Footer', 'aqualuxe'),
                'none'   => __('Disable', 'aqualuxe'),
            ),
            'priority' => 70,
        )
    );
}

/**
 * WooCommerce section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_woocommerce($wp_customize) {
    // Only add these options if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }

    $wp_customize->add_section(
        'aqualuxe_woocommerce',
        array(
            'title'    => __('WooCommerce', 'aqualuxe'),
            'panel'    => 'aqualuxe_theme_options',
            'priority' => 70,
        )
    );

    // Shop Layout
    $wp_customize->add_setting(
        'aqualuxe_shop_layout',
        array(
            'default'           => 'grid',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_shop_layout',
        array(
            'label'    => __('Shop Layout', 'aqualuxe'),
            'section'  => 'aqualuxe_woocommerce',
            'type'     => 'select',
            'choices'  => array(
                'grid'    => __('Grid', 'aqualuxe'),
                'list'    => __('List', 'aqualuxe'),
                'compact' => __('Compact Grid', 'aqualuxe'),
            ),
            'priority' => 10,
        )
    );

    // Products Per Row
    $wp_customize->add_setting(
        'aqualuxe_products_per_row',
        array(
            'default'           => 4,
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_products_per_row',
        array(
            'label'       => __('Products Per Row', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 2,
                'max'  => 6,
                'step' => 1,
            ),
            'priority'    => 20,
        )
    );

    // Products Per Page
    $wp_customize->add_setting(
        'aqualuxe_products_per_page',
        array(
            'default'           => 12,
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_products_per_page',
        array(
            'label'       => __('Products Per Page', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 4,
                'max'  => 48,
                'step' => 4,
            ),
            'priority'    => 30,
        )
    );

    // Shop Sidebar
    $wp_customize->add_setting(
        'aqualuxe_shop_sidebar',
        array(
            'default'           => 'right',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_shop_sidebar',
        array(
            'label'    => __('Shop Sidebar Position', 'aqualuxe'),
            'section'  => 'aqualuxe_woocommerce',
            'type'     => 'select',
            'choices'  => array(
                'right' => __('Right', 'aqualuxe'),
                'left'  => __('Left', 'aqualuxe'),
                'none'  => __('No Sidebar', 'aqualuxe'),
            ),
            'priority' => 40,
        )
    );

    // Product Sidebar
    $wp_customize->add_setting(
        'aqualuxe_product_sidebar',
        array(
            'default'           => 'none',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_sidebar',
        array(
            'label'    => __('Product Page Sidebar', 'aqualuxe'),
            'section'  => 'aqualuxe_woocommerce',
            'type'     => 'select',
            'choices'  => array(
                'right' => __('Right', 'aqualuxe'),
                'left'  => __('Left', 'aqualuxe'),
                'none'  => __('No Sidebar', 'aqualuxe'),
            ),
            'priority' => 50,
        )
    );

    // Quick View
    $wp_customize->add_setting(
        'aqualuxe_quick_view',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_quick_view',
        array(
            'label'    => __('Enable Quick View', 'aqualuxe'),
            'section'  => 'aqualuxe_woocommerce',
            'type'     => 'checkbox',
            'priority' => 60,
        )
    );

    // Wishlist
    $wp_customize->add_setting(
        'aqualuxe_wishlist',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_wishlist',
        array(
            'label'    => __('Enable Wishlist', 'aqualuxe'),
            'section'  => 'aqualuxe_woocommerce',
            'type'     => 'checkbox',
            'priority' => 70,
        )
    );

    // AJAX Cart
    $wp_customize->add_setting(
        'aqualuxe_ajax_cart',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_ajax_cart',
        array(
            'label'    => __('Enable AJAX Cart', 'aqualuxe'),
            'section'  => 'aqualuxe_woocommerce',
            'type'     => 'checkbox',
            'priority' => 80,
        )
    );

    // Cart Drawer
    $wp_customize->add_setting(
        'aqualuxe_cart_drawer',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_cart_drawer',
        array(
            'label'    => __('Enable Cart Drawer', 'aqualuxe'),
            'section'  => 'aqualuxe_woocommerce',
            'type'     => 'checkbox',
            'priority' => 90,
        )
    );

    // Product Gallery Zoom
    $wp_customize->add_setting(
        'aqualuxe_product_zoom',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_zoom',
        array(
            'label'    => __('Enable Product Gallery Zoom', 'aqualuxe'),
            'section'  => 'aqualuxe_woocommerce',
            'type'     => 'checkbox',
            'priority' => 100,
        )
    );

    // Product Gallery Lightbox
    $wp_customize->add_setting(
        'aqualuxe_product_lightbox',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_lightbox',
        array(
            'label'    => __('Enable Product Gallery Lightbox', 'aqualuxe'),
            'section'  => 'aqualuxe_woocommerce',
            'type'     => 'checkbox',
            'priority' => 110,
        )
    );

    // Related Products
    $wp_customize->add_setting(
        'aqualuxe_related_products',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_related_products',
        array(
            'label'    => __('Show Related Products', 'aqualuxe'),
            'section'  => 'aqualuxe_woocommerce',
            'type'     => 'checkbox',
            'priority' => 120,
        )
    );

    // Number of Related Products
    $wp_customize->add_setting(
        'aqualuxe_related_products_count',
        array(
            'default'           => 4,
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_related_products_count',
        array(
            'label'       => __('Number of Related Products', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 2,
                'max'  => 8,
                'step' => 1,
            ),
            'priority'    => 130,
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_related_products', true);
            },
        )
    );

    // Multi-Currency Support
    $wp_customize->add_setting(
        'aqualuxe_multi_currency',
        array(
            'default'           => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_multi_currency',
        array(
            'label'    => __('Enable Multi-Currency Support', 'aqualuxe'),
            'section'  => 'aqualuxe_woocommerce',
            'type'     => 'checkbox',
            'priority' => 140,
        )
    );

    // Vendor Display
    $wp_customize->add_setting(
        'aqualuxe_vendor_display',
        array(
            'default'           => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_vendor_display',
        array(
            'label'    => __('Show Vendor Information', 'aqualuxe'),
            'section'  => 'aqualuxe_woocommerce',
            'type'     => 'checkbox',
            'priority' => 150,
        )
    );
}

/**
 * Blog section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_blog($wp_customize) {
    $wp_customize->add_section(
        'aqualuxe_blog',
        array(
            'title'    => __('Blog', 'aqualuxe'),
            'panel'    => 'aqualuxe_theme_options',
            'priority' => 80,
        )
    );

    // Blog Sidebar
    $wp_customize->add_setting(
        'aqualuxe_blog_sidebar',
        array(
            'default'           => 'right',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_blog_sidebar',
        array(
            'label'    => __('Blog Sidebar Position', 'aqualuxe'),
            'section'  => 'aqualuxe_blog',
            'type'     => 'select',
            'choices'  => array(
                'right' => __('Right', 'aqualuxe'),
                'left'  => __('Left', 'aqualuxe'),
                'none'  => __('No Sidebar', 'aqualuxe'),
            ),
            'priority' => 10,
        )
    );

    // Featured Image
    $wp_customize->add_setting(
        'aqualuxe_blog_featured_image',
        array(
            'default'           => 'large',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_blog_featured_image',
        array(
            'label'    => __('Featured Image Size', 'aqualuxe'),
            'section'  => 'aqualuxe_blog',
            'type'     => 'select',
            'choices'  => array(
                'large'     => __('Large (Above Content)', 'aqualuxe'),
                'medium'    => __('Medium (Above Content)', 'aqualuxe'),
                'thumbnail' => __('Thumbnail (Beside Content)', 'aqualuxe'),
                'none'      => __('No Featured Image', 'aqualuxe'),
            ),
            'priority' => 20,
        )
    );

    // Excerpt Length
    $wp_customize->add_setting(
        'aqualuxe_excerpt_length',
        array(
            'default'           => 55,
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_excerpt_length',
        array(
            'label'       => __('Excerpt Length', 'aqualuxe'),
            'description' => __('Number of words in excerpt', 'aqualuxe'),
            'section'     => 'aqualuxe_blog',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 10,
                'max'  => 200,
                'step' => 5,
            ),
            'priority'    => 30,
        )
    );

    // Meta Information
    $wp_customize->add_setting(
        'aqualuxe_show_post_date',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_post_date',
        array(
            'label'    => __('Show Post Date', 'aqualuxe'),
            'section'  => 'aqualuxe_blog',
            'type'     => 'checkbox',
            'priority' => 40,
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_show_post_author',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_post_author',
        array(
            'label'    => __('Show Post Author', 'aqualuxe'),
            'section'  => 'aqualuxe_blog',
            'type'     => 'checkbox',
            'priority' => 50,
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_show_post_categories',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_post_categories',
        array(
            'label'    => __('Show Post Categories', 'aqualuxe'),
            'section'  => 'aqualuxe_blog',
            'type'     => 'checkbox',
            'priority' => 60,
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_show_post_tags',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_post_tags',
        array(
            'label'    => __('Show Post Tags', 'aqualuxe'),
            'section'  => 'aqualuxe_blog',
            'type'     => 'checkbox',
            'priority' => 70,
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_show_post_comments',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_post_comments',
        array(
            'label'    => __('Show Post Comments Count', 'aqualuxe'),
            'section'  => 'aqualuxe_blog',
            'type'     => 'checkbox',
            'priority' => 80,
        )
    );

    // Related Posts
    $wp_customize->add_setting(
        'aqualuxe_related_posts',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_related_posts',
        array(
            'label'    => __('Show Related Posts', 'aqualuxe'),
            'section'  => 'aqualuxe_blog',
            'type'     => 'checkbox',
            'priority' => 90,
        )
    );

    // Author Bio
    $wp_customize->add_setting(
        'aqualuxe_author_bio',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_author_bio',
        array(
            'label'    => __('Show Author Bio', 'aqualuxe'),
            'section'  => 'aqualuxe_blog',
            'type'     => 'checkbox',
            'priority' => 100,
        )
    );

    // Post Navigation
    $wp_customize->add_setting(
        'aqualuxe_post_navigation',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_post_navigation',
        array(
            'label'    => __('Show Post Navigation', 'aqualuxe'),
            'section'  => 'aqualuxe_blog',
            'type'     => 'checkbox',
            'priority' => 110,
        )
    );

    // Social Sharing
    $wp_customize->add_setting(
        'aqualuxe_social_sharing',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_sharing',
        array(
            'label'    => __('Enable Social Sharing', 'aqualuxe'),
            'section'  => 'aqualuxe_blog',
            'type'     => 'checkbox',
            'priority' => 120,
        )
    );
}

/**
 * Performance section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_performance($wp_customize) {
    $wp_customize->add_section(
        'aqualuxe_performance',
        array(
            'title'    => __('Performance', 'aqualuxe'),
            'panel'    => 'aqualuxe_theme_options',
            'priority' => 90,
        )
    );

    // Lazy Loading
    $wp_customize->add_setting(
        'aqualuxe_lazy_loading',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_lazy_loading',
        array(
            'label'    => __('Enable Lazy Loading for Images', 'aqualuxe'),
            'section'  => 'aqualuxe_performance',
            'type'     => 'checkbox',
            'priority' => 10,
        )
    );

    // Preload Fonts
    $wp_customize->add_setting(
        'aqualuxe_preload_fonts',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_preload_fonts',
        array(
            'label'    => __('Preload Fonts', 'aqualuxe'),
            'section'  => 'aqualuxe_performance',
            'type'     => 'checkbox',
            'priority' => 20,
        )
    );

    // Minify CSS
    $wp_customize->add_setting(
        'aqualuxe_minify_css',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_minify_css',
        array(
            'label'    => __('Minify CSS', 'aqualuxe'),
            'section'  => 'aqualuxe_performance',
            'type'     => 'checkbox',
            'priority' => 30,
        )
    );

    // Minify JS
    $wp_customize->add_setting(
        'aqualuxe_minify_js',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_minify_js',
        array(
            'label'    => __('Minify JavaScript', 'aqualuxe'),
            'section'  => 'aqualuxe_performance',
            'type'     => 'checkbox',
            'priority' => 40,
        )
    );

    // Defer JavaScript
    $wp_customize->add_setting(
        'aqualuxe_defer_js',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_defer_js',
        array(
            'label'    => __('Defer JavaScript', 'aqualuxe'),
            'section'  => 'aqualuxe_performance',
            'type'     => 'checkbox',
            'priority' => 50,
        )
    );

    // Preconnect
    $wp_customize->add_setting(
        'aqualuxe_preconnect',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_preconnect',
        array(
            'label'    => __('Enable Preconnect for External Resources', 'aqualuxe'),
            'section'  => 'aqualuxe_performance',
            'type'     => 'checkbox',
            'priority' => 60,
        )
    );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
    wp_enqueue_script('aqualuxe-customizer', aqualuxe_asset_path('js/customizer.js'), array('customize-preview'), null, true);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');

/**
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function aqualuxe_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

/**
 * Sanitize select.
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_select($input, $setting) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control($setting->id)->choices;

    // If the input is a valid key, return it; otherwise, return the default.
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize float.
 *
 * @param float $input The input from the setting.
 * @return float The sanitized input.
 */
function aqualuxe_sanitize_float($input) {
    return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}

/**
 * Get Google Fonts.
 *
 * @return array Google fonts.
 */
function aqualuxe_get_google_fonts() {
    return array(
        'Montserrat, sans-serif'       => 'Montserrat',
        'Playfair Display, serif'      => 'Playfair Display',
        'Roboto, sans-serif'           => 'Roboto',
        'Open Sans, sans-serif'        => 'Open Sans',
        'Lato, sans-serif'             => 'Lato',
        'Poppins, sans-serif'          => 'Poppins',
        'Raleway, sans-serif'          => 'Raleway',
        'Nunito, sans-serif'           => 'Nunito',
        'Merriweather, serif'          => 'Merriweather',
        'Source Sans Pro, sans-serif'  => 'Source Sans Pro',
        'PT Sans, sans-serif'          => 'PT Sans',
        'Roboto Condensed, sans-serif' => 'Roboto Condensed',
        'Oswald, sans-serif'           => 'Oswald',
        'Roboto Slab, serif'           => 'Roboto Slab',
        'Noto Sans, sans-serif'        => 'Noto Sans',
        'Ubuntu, sans-serif'           => 'Ubuntu',
        'Quicksand, sans-serif'        => 'Quicksand',
        'Rubik, sans-serif'            => 'Rubik',
        'Work Sans, sans-serif'        => 'Work Sans',
        'Mulish, sans-serif'           => 'Mulish',
    );
}

/**
 * Get theme option.
 *
 * @param string $option Option name.
 * @param mixed  $default Default value.
 * @return mixed Option value.
 */
function aqualuxe_get_theme_option($option, $default = false) {
    return get_theme_mod($option, $default);
}