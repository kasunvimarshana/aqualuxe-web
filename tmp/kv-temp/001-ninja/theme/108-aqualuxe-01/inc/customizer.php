<?php
/**
 * Theme Customizer
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 */
function aqualuxe_customize_register($wp_customize)
{
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial('blogname', [
            'selector' => '.site-title',
            'render_callback' => 'aqualuxe_customize_partial_blogname',
        ]);
        $wp_customize->selective_refresh->add_partial('blogdescription', [
            'selector' => '.site-description',
            'render_callback' => 'aqualuxe_customize_partial_blogdescription',
        ]);
    }

    // Theme Options Panel
    $wp_customize->add_panel('aqualuxe_theme_options', [
        'title' => esc_html__('AquaLuxe Theme Options', 'aqualuxe'),
        'priority' => 30,
    ]);

    // Header Section
    $wp_customize->add_section('aqualuxe_header', [
        'title' => esc_html__('Header Options', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 10,
    ]);

    // Header Style
    $wp_customize->add_setting('aqualuxe_header_style', [
        'default' => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('aqualuxe_header_style', [
        'label' => esc_html__('Header Style', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'type' => 'select',
        'choices' => [
            'default' => esc_html__('Default', 'aqualuxe'),
            'transparent' => esc_html__('Transparent', 'aqualuxe'),
            'minimal' => esc_html__('Minimal', 'aqualuxe'),
        ],
    ]);

    // Social Media Section
    $wp_customize->add_section('aqualuxe_social', [
        'title' => esc_html__('Social Media', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 20,
    ]);

    $social_networks = [
        'facebook' => esc_html__('Facebook', 'aqualuxe'),
        'twitter' => esc_html__('Twitter', 'aqualuxe'),
        'instagram' => esc_html__('Instagram', 'aqualuxe'),
        'youtube' => esc_html__('YouTube', 'aqualuxe'),
        'linkedin' => esc_html__('LinkedIn', 'aqualuxe'),
    ];

    foreach ($social_networks as $network => $label) {
        $wp_customize->add_setting("aqualuxe_{$network}_url", [
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ]);

        $wp_customize->add_control("aqualuxe_{$network}_url", [
            'label' => $label . ' ' . esc_html__('URL', 'aqualuxe'),
            'section' => 'aqualuxe_social',
            'type' => 'url',
        ]);
    }

    // Footer Section
    $wp_customize->add_section('aqualuxe_footer', [
        'title' => esc_html__('Footer Options', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 30,
    ]);

    // Footer Copyright
    $wp_customize->add_setting('aqualuxe_footer_copyright', [
        'default' => '',
        'sanitize_callback' => 'wp_kses_post',
    ]);

    $wp_customize->add_control('aqualuxe_footer_copyright', [
        'label' => esc_html__('Footer Copyright Text', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'type' => 'textarea',
    ]);

    // Colors Section Enhancement
    $wp_customize->add_setting('aqualuxe_primary_color', [
        'default' => '#0ea5e9',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', [
        'label' => esc_html__('Primary Color', 'aqualuxe'),
        'section' => 'colors',
    ]));

    $wp_customize->add_setting('aqualuxe_secondary_color', [
        'default' => '#10b981',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', [
        'label' => esc_html__('Secondary Color', 'aqualuxe'),
        'section' => 'colors',
    ]));

    // Typography Section
    $wp_customize->add_section('aqualuxe_typography', [
        'title' => esc_html__('Typography', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 40,
    ]);

    // Body Font
    $wp_customize->add_setting('aqualuxe_body_font', [
        'default' => 'Inter',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_body_font', [
        'label' => esc_html__('Body Font', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => [
            'Inter' => 'Inter',
            'Open Sans' => 'Open Sans',
            'Roboto' => 'Roboto',
            'Lato' => 'Lato',
            'Poppins' => 'Poppins',
        ],
    ]);

    // Heading Font
    $wp_customize->add_setting('aqualuxe_heading_font', [
        'default' => 'Playfair Display',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_heading_font', [
        'label' => esc_html__('Heading Font', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => [
            'Playfair Display' => 'Playfair Display',
            'Merriweather' => 'Merriweather',
            'Crimson Text' => 'Crimson Text',
            'Libre Baskerville' => 'Libre Baskerville',
            'Source Serif Pro' => 'Source Serif Pro',
        ],
    ]);

    // Layout Section
    $wp_customize->add_section('aqualuxe_layout', [
        'title' => esc_html__('Layout Options', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 50,
    ]);

    // Container Width
    $wp_customize->add_setting('aqualuxe_container_width', [
        'default' => '1200',
        'sanitize_callback' => 'absint',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_container_width', [
        'label' => esc_html__('Container Max Width (px)', 'aqualuxe'),
        'section' => 'aqualuxe_layout',
        'type' => 'number',
        'input_attrs' => [
            'min' => 960,
            'max' => 1920,
            'step' => 10,
        ],
    ]);

    // Blog Layout
    $wp_customize->add_setting('aqualuxe_blog_layout', [
        'default' => 'grid',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('aqualuxe_blog_layout', [
        'label' => esc_html__('Blog Layout', 'aqualuxe'),
        'section' => 'aqualuxe_layout',
        'type' => 'select',
        'choices' => [
            'grid' => esc_html__('Grid', 'aqualuxe'),
            'list' => esc_html__('List', 'aqualuxe'),
            'masonry' => esc_html__('Masonry', 'aqualuxe'),
        ],
    ]);

    // WooCommerce Section (if active)
    if (class_exists('WooCommerce')) {
        $wp_customize->add_section('aqualuxe_woocommerce', [
            'title' => esc_html__('WooCommerce Options', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 60,
        ]);

        // Products per page
        $wp_customize->add_setting('aqualuxe_products_per_page', [
            'default' => 12,
            'sanitize_callback' => 'absint',
        ]);

        $wp_customize->add_control('aqualuxe_products_per_page', [
            'label' => esc_html__('Products per Page', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'number',
            'input_attrs' => [
                'min' => 1,
                'max' => 100,
            ],
        ]);

        // Shop Layout
        $wp_customize->add_setting('aqualuxe_shop_layout', [
            'default' => 'grid',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        ]);

        $wp_customize->add_control('aqualuxe_shop_layout', [
            'label' => esc_html__('Shop Layout', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'select',
            'choices' => [
                'grid' => esc_html__('Grid', 'aqualuxe'),
                'list' => esc_html__('List', 'aqualuxe'),
            ],
        ]);
    }
}
add_action('customize_register', 'aqualuxe_customize_register');

/**
 * Render the site title for the selective refresh partial.
 */
function aqualuxe_customize_partial_blogname()
{
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 */
function aqualuxe_customize_partial_blogdescription()
{
    bloginfo('description');
}

/**
 * Sanitize select input
 */
function aqualuxe_sanitize_select($input, $setting)
{
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js()
{
    wp_enqueue_script('aqualuxe-customizer', AQUALUXE_ASSETS_URL . '/js/customizer.js', ['customize-preview'], AQUALUXE_VERSION, true);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');

/**
 * Generate custom CSS based on customizer settings
 */
function aqualuxe_customize_css()
{
    $primary_color = get_theme_mod('aqualuxe_primary_color', '#0ea5e9');
    $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#10b981');
    $body_font = get_theme_mod('aqualuxe_body_font', 'Inter');
    $heading_font = get_theme_mod('aqualuxe_heading_font', 'Playfair Display');
    $container_width = get_theme_mod('aqualuxe_container_width', '1200');

    $css = "
        :root {
            --primary-color: {$primary_color};
            --secondary-color: {$secondary_color};
            --body-font: '{$body_font}', sans-serif;
            --heading-font: '{$heading_font}', serif;
            --container-width: {$container_width}px;
        }
        
        body {
            font-family: var(--body-font);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--heading-font);
        }
        
        .container {
            max-width: var(--container-width);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
        }
        
        .text-primary {
            color: var(--primary-color);
        }
        
        .text-secondary {
            color: var(--secondary-color);
        }
    ";

    return $css;
}

/**
 * Output custom CSS
 */
function aqualuxe_customize_css_output()
{
    echo '<style type="text/css">' . aqualuxe_customize_css() . '</style>';
}
add_action('wp_head', 'aqualuxe_customize_css_output');