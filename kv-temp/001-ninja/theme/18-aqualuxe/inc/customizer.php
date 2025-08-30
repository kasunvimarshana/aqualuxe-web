<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register($wp_customize) {
    // Add selective refresh for site title and description
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial('blogname', [
            'selector' => '.site-title a',
            'render_callback' => 'aqualuxe_customize_partial_blogname',
        ]);
        $wp_customize->selective_refresh->add_partial('blogdescription', [
            'selector' => '.site-description',
            'render_callback' => 'aqualuxe_customize_partial_blogdescription',
        ]);
    }

    // Add theme options panel
    $wp_customize->add_panel('aqualuxe_theme_options', [
        'title' => __('AquaLuxe Theme Options', 'aqualuxe'),
        'description' => __('Customize your AquaLuxe theme settings', 'aqualuxe'),
        'priority' => 130,
    ]);

    // Add header section
    $wp_customize->add_section('aqualuxe_header_options', [
        'title' => __('Header Options', 'aqualuxe'),
        'description' => __('Customize the header section of your theme', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 10,
    ]);

    // Add sticky header option
    $wp_customize->add_setting('aqualuxe_sticky_header', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_sticky_header', [
        'label' => __('Enable Sticky Header', 'aqualuxe'),
        'section' => 'aqualuxe_header_options',
        'type' => 'checkbox',
    ]);

    // Add transparent header option
    $wp_customize->add_setting('aqualuxe_transparent_header', [
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_transparent_header', [
        'label' => __('Enable Transparent Header on Homepage', 'aqualuxe'),
        'section' => 'aqualuxe_header_options',
        'type' => 'checkbox',
    ]);

    // Add header style option
    $wp_customize->add_setting('aqualuxe_header_style', [
        'default' => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_header_style', [
        'label' => __('Header Style', 'aqualuxe'),
        'section' => 'aqualuxe_header_options',
        'type' => 'select',
        'choices' => [
            'default' => __('Default', 'aqualuxe'),
            'centered' => __('Centered', 'aqualuxe'),
            'minimal' => __('Minimal', 'aqualuxe'),
        ],
    ]);

    // Add header button option
    $wp_customize->add_setting('aqualuxe_header_button_text', [
        'default' => __('Contact Us', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_header_button_text', [
        'label' => __('Header Button Text', 'aqualuxe'),
        'section' => 'aqualuxe_header_options',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('aqualuxe_header_button_url', [
        'default' => '#',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_header_button_url', [
        'label' => __('Header Button URL', 'aqualuxe'),
        'section' => 'aqualuxe_header_options',
        'type' => 'url',
    ]);

    $wp_customize->add_setting('aqualuxe_header_button_style', [
        'default' => 'primary',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_header_button_style', [
        'label' => __('Header Button Style', 'aqualuxe'),
        'section' => 'aqualuxe_header_options',
        'type' => 'select',
        'choices' => [
            'primary' => __('Primary', 'aqualuxe'),
            'secondary' => __('Secondary', 'aqualuxe'),
            'outline' => __('Outline', 'aqualuxe'),
        ],
    ]);

    // Add footer section
    $wp_customize->add_section('aqualuxe_footer_options', [
        'title' => __('Footer Options', 'aqualuxe'),
        'description' => __('Customize the footer section of your theme', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 20,
    ]);

    // Add footer style option
    $wp_customize->add_setting('aqualuxe_footer_style', [
        'default' => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_footer_style', [
        'label' => __('Footer Style', 'aqualuxe'),
        'section' => 'aqualuxe_footer_options',
        'type' => 'select',
        'choices' => [
            'default' => __('Default', 'aqualuxe'),
            'centered' => __('Centered', 'aqualuxe'),
            'minimal' => __('Minimal', 'aqualuxe'),
        ],
    ]);

    // Add footer logo option
    $wp_customize->add_setting('aqualuxe_footer_logo', [
        'default' => '',
        'sanitize_callback' => 'absint',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aqualuxe_footer_logo', [
        'label' => __('Footer Logo', 'aqualuxe'),
        'section' => 'aqualuxe_footer_options',
        'mime_type' => 'image',
    ]));

    // Add footer text option
    $wp_customize->add_setting('aqualuxe_footer_text', [
        'default' => '',
        'sanitize_callback' => 'wp_kses_post',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_footer_text', [
        'label' => __('Footer Text', 'aqualuxe'),
        'section' => 'aqualuxe_footer_options',
        'type' => 'textarea',
    ]);

    // Add copyright text option
    $wp_customize->add_setting('aqualuxe_copyright_text', [
        'default' => sprintf(
            /* translators: %1$s: current year, %2$s: site name */
            __('&copy; %1$s %2$s. All rights reserved.', 'aqualuxe'),
            date('Y'),
            get_bloginfo('name')
        ),
        'sanitize_callback' => 'wp_kses_post',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_copyright_text', [
        'label' => __('Copyright Text', 'aqualuxe'),
        'section' => 'aqualuxe_footer_options',
        'type' => 'textarea',
    ]);

    // Add colors section
    $wp_customize->add_section('aqualuxe_colors', [
        'title' => __('Colors', 'aqualuxe'),
        'description' => __('Customize the colors of your theme', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 30,
    ]);

    // Add primary color option
    $wp_customize->add_setting('aqualuxe_primary_color', [
        'default' => '#0077B6',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', [
        'label' => __('Primary Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
    ]));

    // Add secondary color option
    $wp_customize->add_setting('aqualuxe_secondary_color', [
        'default' => '#00B4D8',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', [
        'label' => __('Secondary Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
    ]));

    // Add accent color option
    $wp_customize->add_setting('aqualuxe_accent_color', [
        'default' => '#FFD166',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', [
        'label' => __('Accent Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
    ]));

    // Add dark mode section
    $wp_customize->add_section('aqualuxe_dark_mode', [
        'title' => __('Dark Mode', 'aqualuxe'),
        'description' => __('Customize dark mode settings', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 40,
    ]);

    // Add dark mode option
    $wp_customize->add_setting('aqualuxe_enable_dark_mode', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_enable_dark_mode', [
        'label' => __('Enable Dark Mode Toggle', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'type' => 'checkbox',
    ]);

    // Add default dark mode option
    $wp_customize->add_setting('aqualuxe_default_dark_mode', [
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_default_dark_mode', [
        'label' => __('Default to Dark Mode', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'type' => 'checkbox',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    ]);

    // Add dark mode background color option
    $wp_customize->add_setting('aqualuxe_dark_bg_color', [
        'default' => '#0A192F',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_bg_color', [
        'label' => __('Dark Mode Background Color', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    ]));

    // Add typography section
    $wp_customize->add_section('aqualuxe_typography', [
        'title' => __('Typography', 'aqualuxe'),
        'description' => __('Customize the typography of your theme', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 50,
    ]);

    // Add body font option
    $wp_customize->add_setting('aqualuxe_body_font', [
        'default' => 'Montserrat',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_body_font', [
        'label' => __('Body Font', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => [
            'Montserrat' => 'Montserrat',
            'Open Sans' => 'Open Sans',
            'Roboto' => 'Roboto',
            'Lato' => 'Lato',
            'Poppins' => 'Poppins',
            'Raleway' => 'Raleway',
            'Inter' => 'Inter',
        ],
    ]);

    // Add heading font option
    $wp_customize->add_setting('aqualuxe_heading_font', [
        'default' => 'Playfair Display',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_heading_font', [
        'label' => __('Heading Font', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => [
            'Playfair Display' => 'Playfair Display',
            'Montserrat' => 'Montserrat',
            'Roboto' => 'Roboto',
            'Lato' => 'Lato',
            'Poppins' => 'Poppins',
            'Raleway' => 'Raleway',
            'Inter' => 'Inter',
        ],
    ]);

    // Add base font size option
    $wp_customize->add_setting('aqualuxe_base_font_size', [
        'default' => '16',
        'sanitize_callback' => 'absint',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_base_font_size', [
        'label' => __('Base Font Size (px)', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'number',
        'input_attrs' => [
            'min' => 12,
            'max' => 24,
            'step' => 1,
        ],
    ]);

    // Add layout section
    $wp_customize->add_section('aqualuxe_layout', [
        'title' => __('Layout', 'aqualuxe'),
        'description' => __('Customize the layout of your theme', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 60,
    ]);

    // Add container width option
    $wp_customize->add_setting('aqualuxe_container_width', [
        'default' => '1280',
        'sanitize_callback' => 'absint',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_container_width', [
        'label' => __('Container Width (px)', 'aqualuxe'),
        'section' => 'aqualuxe_layout',
        'type' => 'number',
        'input_attrs' => [
            'min' => 960,
            'max' => 1600,
            'step' => 10,
        ],
    ]);

    // Add sidebar position option
    $wp_customize->add_setting('aqualuxe_sidebar_position', [
        'default' => 'right',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_sidebar_position', [
        'label' => __('Sidebar Position', 'aqualuxe'),
        'section' => 'aqualuxe_layout',
        'type' => 'select',
        'choices' => [
            'right' => __('Right', 'aqualuxe'),
            'left' => __('Left', 'aqualuxe'),
            'none' => __('No Sidebar', 'aqualuxe'),
        ],
    ]);

    // Add blog layout option
    $wp_customize->add_setting('aqualuxe_blog_layout', [
        'default' => 'grid',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_blog_layout', [
        'label' => __('Blog Layout', 'aqualuxe'),
        'section' => 'aqualuxe_layout',
        'type' => 'select',
        'choices' => [
            'grid' => __('Grid', 'aqualuxe'),
            'list' => __('List', 'aqualuxe'),
            'masonry' => __('Masonry', 'aqualuxe'),
        ],
    ]);

    // Add shop layout option
    $wp_customize->add_setting('aqualuxe_shop_layout', [
        'default' => 'grid',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_shop_layout', [
        'label' => __('Shop Layout', 'aqualuxe'),
        'section' => 'aqualuxe_layout',
        'type' => 'select',
        'choices' => [
            'grid' => __('Grid', 'aqualuxe'),
            'list' => __('List', 'aqualuxe'),
            'masonry' => __('Masonry', 'aqualuxe'),
        ],
        'active_callback' => 'aqualuxe_is_woocommerce_active',
    ]);

    // Add shop columns option
    $wp_customize->add_setting('aqualuxe_shop_columns', [
        'default' => '3',
        'sanitize_callback' => 'absint',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_shop_columns', [
        'label' => __('Shop Columns', 'aqualuxe'),
        'section' => 'aqualuxe_layout',
        'type' => 'select',
        'choices' => [
            '2' => '2',
            '3' => '3',
            '4' => '4',
        ],
        'active_callback' => 'aqualuxe_is_woocommerce_active',
    ]);

    // Add social media section
    $wp_customize->add_section('aqualuxe_social_media', [
        'title' => __('Social Media', 'aqualuxe'),
        'description' => __('Add your social media links', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 70,
    ]);

    // Add social media options
    $social_networks = [
        'facebook' => __('Facebook', 'aqualuxe'),
        'twitter' => __('Twitter', 'aqualuxe'),
        'instagram' => __('Instagram', 'aqualuxe'),
        'youtube' => __('YouTube', 'aqualuxe'),
        'linkedin' => __('LinkedIn', 'aqualuxe'),
        'pinterest' => __('Pinterest', 'aqualuxe'),
    ];

    foreach ($social_networks as $network => $label) {
        $wp_customize->add_setting('aqualuxe_' . $network . '_url', [
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport' => 'postMessage',
        ]);

        $wp_customize->add_control('aqualuxe_' . $network . '_url', [
            'label' => $label,
            'section' => 'aqualuxe_social_media',
            'type' => 'url',
        ]);
    }

    // Add contact information section
    $wp_customize->add_section('aqualuxe_contact_info', [
        'title' => __('Contact Information', 'aqualuxe'),
        'description' => __('Add your contact information', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 80,
    ]);

    // Add address option
    $wp_customize->add_setting('aqualuxe_address', [
        'default' => '',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_address', [
        'label' => __('Address', 'aqualuxe'),
        'section' => 'aqualuxe_contact_info',
        'type' => 'textarea',
    ]);

    // Add phone option
    $wp_customize->add_setting('aqualuxe_phone', [
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_phone', [
        'label' => __('Phone', 'aqualuxe'),
        'section' => 'aqualuxe_contact_info',
        'type' => 'text',
    ]);

    // Add email option
    $wp_customize->add_setting('aqualuxe_email', [
        'default' => '',
        'sanitize_callback' => 'sanitize_email',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_email', [
        'label' => __('Email', 'aqualuxe'),
        'section' => 'aqualuxe_contact_info',
        'type' => 'email',
    ]);

    // Add business hours option
    $wp_customize->add_setting('aqualuxe_hours', [
        'default' => '',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_hours', [
        'label' => __('Business Hours', 'aqualuxe'),
        'section' => 'aqualuxe_contact_info',
        'type' => 'textarea',
    ]);

    // Add performance section
    $wp_customize->add_section('aqualuxe_performance', [
        'title' => __('Performance', 'aqualuxe'),
        'description' => __('Optimize your site performance', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 90,
    ]);

    // Add lazy loading option
    $wp_customize->add_setting('aqualuxe_lazy_loading', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_lazy_loading', [
        'label' => __('Enable Lazy Loading for Images', 'aqualuxe'),
        'section' => 'aqualuxe_performance',
        'type' => 'checkbox',
    ]);

    // Add preload option
    $wp_customize->add_setting('aqualuxe_preload_fonts', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_preload_fonts', [
        'label' => __('Preload Fonts', 'aqualuxe'),
        'section' => 'aqualuxe_performance',
        'type' => 'checkbox',
    ]);

    // Add minification option
    $wp_customize->add_setting('aqualuxe_minify_assets', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_minify_assets', [
        'label' => __('Minify CSS and JavaScript', 'aqualuxe'),
        'section' => 'aqualuxe_performance',
        'type' => 'checkbox',
    ]);

    // Add multilingual section
    $wp_customize->add_section('aqualuxe_multilingual', [
        'title' => __('Multilingual', 'aqualuxe'),
        'description' => __('Multilingual settings', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 100,
    ]);

    // Add language switcher option
    $wp_customize->add_setting('aqualuxe_language_switcher', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_language_switcher', [
        'label' => __('Show Language Switcher', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'type' => 'checkbox',
    ]);

    // Add language switcher position option
    $wp_customize->add_setting('aqualuxe_language_switcher_position', [
        'default' => 'header',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_language_switcher_position', [
        'label' => __('Language Switcher Position', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'type' => 'select',
        'choices' => [
            'header' => __('Header', 'aqualuxe'),
            'footer' => __('Footer', 'aqualuxe'),
            'both' => __('Both', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_language_switcher', true);
        },
    ]);

    // Add RTL support option
    $wp_customize->add_setting('aqualuxe_rtl_support', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_rtl_support', [
        'label' => __('Enable RTL Support', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'type' => 'checkbox',
    ]);

    // Add WooCommerce section
    if (aqualuxe_is_woocommerce_active()) {
        $wp_customize->add_section('aqualuxe_woocommerce', [
            'title' => __('WooCommerce', 'aqualuxe'),
            'description' => __('WooCommerce settings', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 110,
        ]);

        // Add quick view option
        $wp_customize->add_setting('aqualuxe_quick_view', [
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'postMessage',
        ]);

        $wp_customize->add_control('aqualuxe_quick_view', [
            'label' => __('Enable Quick View', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'checkbox',
        ]);

        // Add wishlist option
        $wp_customize->add_setting('aqualuxe_wishlist', [
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'postMessage',
        ]);

        $wp_customize->add_control('aqualuxe_wishlist', [
            'label' => __('Enable Wishlist', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'checkbox',
        ]);

        // Add product hover effect option
        $wp_customize->add_setting('aqualuxe_product_hover', [
            'default' => 'zoom',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport' => 'postMessage',
        ]);

        $wp_customize->add_control('aqualuxe_product_hover', [
            'label' => __('Product Image Hover Effect', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'select',
            'choices' => [
                'none' => __('None', 'aqualuxe'),
                'zoom' => __('Zoom', 'aqualuxe'),
                'fade' => __('Fade', 'aqualuxe'),
                'flip' => __('Flip', 'aqualuxe'),
            ],
        ]);

        // Add product card style option
        $wp_customize->add_setting('aqualuxe_product_card', [
            'default' => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport' => 'postMessage',
        ]);

        $wp_customize->add_control('aqualuxe_product_card', [
            'label' => __('Product Card Style', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'select',
            'choices' => [
                'default' => __('Default', 'aqualuxe'),
                'minimal' => __('Minimal', 'aqualuxe'),
                'bordered' => __('Bordered', 'aqualuxe'),
                'shadow' => __('Shadow', 'aqualuxe'),
            ],
        ]);

        // Add sale badge style option
        $wp_customize->add_setting('aqualuxe_sale_badge', [
            'default' => 'circle',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport' => 'postMessage',
        ]);

        $wp_customize->add_control('aqualuxe_sale_badge', [
            'label' => __('Sale Badge Style', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'select',
            'choices' => [
                'circle' => __('Circle', 'aqualuxe'),
                'square' => __('Square', 'aqualuxe'),
                'ribbon' => __('Ribbon', 'aqualuxe'),
                'tag' => __('Tag', 'aqualuxe'),
            ],
        ]);

        // Add cart icon option
        $wp_customize->add_setting('aqualuxe_cart_icon', [
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'postMessage',
        ]);

        $wp_customize->add_control('aqualuxe_cart_icon', [
            'label' => __('Show Cart Icon in Header', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'checkbox',
        ]);

        // Add mini cart option
        $wp_customize->add_setting('aqualuxe_mini_cart', [
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'postMessage',
        ]);

        $wp_customize->add_control('aqualuxe_mini_cart', [
            'label' => __('Enable Mini Cart', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'checkbox',
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_cart_icon', true);
            },
        ]);

        // Add product gallery zoom option
        $wp_customize->add_setting('aqualuxe_product_zoom', [
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'postMessage',
        ]);

        $wp_customize->add_control('aqualuxe_product_zoom', [
            'label' => __('Enable Product Gallery Zoom', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'checkbox',
        ]);

        // Add product gallery lightbox option
        $wp_customize->add_setting('aqualuxe_product_lightbox', [
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'postMessage',
        ]);

        $wp_customize->add_control('aqualuxe_product_lightbox', [
            'label' => __('Enable Product Gallery Lightbox', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'checkbox',
        ]);

        // Add product gallery slider option
        $wp_customize->add_setting('aqualuxe_product_slider', [
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'postMessage',
        ]);

        $wp_customize->add_control('aqualuxe_product_slider', [
            'label' => __('Enable Product Gallery Slider', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'checkbox',
        ]);
    }

    // Add custom CSS section
    $wp_customize->add_section('aqualuxe_custom_css', [
        'title' => __('Custom CSS', 'aqualuxe'),
        'description' => __('Add custom CSS to your theme', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 120,
    ]);

    // Add custom CSS option
    $wp_customize->add_setting('aqualuxe_custom_css', [
        'default' => '',
        'sanitize_callback' => 'wp_strip_all_tags',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_custom_css', [
        'label' => __('Custom CSS', 'aqualuxe'),
        'section' => 'aqualuxe_custom_css',
        'type' => 'textarea',
    ]);

    // Add custom JavaScript section
    $wp_customize->add_section('aqualuxe_custom_js', [
        'title' => __('Custom JavaScript', 'aqualuxe'),
        'description' => __('Add custom JavaScript to your theme', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 130,
    ]);

    // Add custom JavaScript option
    $wp_customize->add_setting('aqualuxe_custom_js', [
        'default' => '',
        'sanitize_callback' => 'wp_strip_all_tags',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('aqualuxe_custom_js', [
        'label' => __('Custom JavaScript', 'aqualuxe'),
        'section' => 'aqualuxe_custom_js',
        'type' => 'textarea',
    ]);
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
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
    wp_enqueue_script('aqualuxe-customizer', AQUALUXE_URI . '/assets/js/customizer.js', ['customize-preview'], AQUALUXE_VERSION, true);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');

/**
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox($checked) {
    return (isset($checked) && true === $checked) ? true : false;
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

    // Return input if valid or return default option.
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Generate inline CSS for customizer options.
 */
function aqualuxe_customizer_css() {
    $primary_color = get_theme_mod('aqualuxe_primary_color', '#0077B6');
    $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#00B4D8');
    $accent_color = get_theme_mod('aqualuxe_accent_color', '#FFD166');
    $dark_bg_color = get_theme_mod('aqualuxe_dark_bg_color', '#0A192F');
    $body_font = get_theme_mod('aqualuxe_body_font', 'Montserrat');
    $heading_font = get_theme_mod('aqualuxe_heading_font', 'Playfair Display');
    $base_font_size = get_theme_mod('aqualuxe_base_font_size', '16');
    $container_width = get_theme_mod('aqualuxe_container_width', '1280');
    $custom_css = get_theme_mod('aqualuxe_custom_css', '');

    // Calculate color variations
    $primary_light = aqualuxe_adjust_brightness($primary_color, 40);
    $primary_dark = aqualuxe_adjust_brightness($primary_color, -40);
    $secondary_light = aqualuxe_adjust_brightness($secondary_color, 40);
    $secondary_dark = aqualuxe_adjust_brightness($secondary_color, -40);
    $accent_light = aqualuxe_adjust_brightness($accent_color, 40);
    $accent_dark = aqualuxe_adjust_brightness($accent_color, -40);

    $css = "
        :root {
            --color-primary: {$primary_color};
            --color-primary-light: {$primary_light};
            --color-primary-dark: {$primary_dark};
            --color-secondary: {$secondary_color};
            --color-secondary-light: {$secondary_light};
            --color-secondary-dark: {$secondary_dark};
            --color-accent: {$accent_color};
            --color-accent-light: {$accent_light};
            --color-accent-dark: {$accent_dark};
            --color-dark-bg: {$dark_bg_color};
            --font-body: '{$body_font}', sans-serif;
            --font-heading: '{$heading_font}', serif;
            --font-size-base: {$base_font_size}px;
            --container-width: {$container_width}px;
        }

        body {
            font-family: var(--font-body);
            font-size: var(--font-size-base);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-heading);
        }

        .container-fluid {
            max-width: var(--container-width);
        }

        .text-primary {
            color: var(--color-primary) !important;
        }

        .bg-primary {
            background-color: var(--color-primary) !important;
        }

        .border-primary {
            border-color: var(--color-primary) !important;
        }

        .btn-primary {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
        }

        .btn-primary:hover {
            background-color: var(--color-primary-dark);
            border-color: var(--color-primary-dark);
        }

        .btn-outline-primary {
            color: var(--color-primary);
            border-color: var(--color-primary);
        }

        .btn-outline-primary:hover {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
        }

        .dark {
            background-color: var(--color-dark-bg);
        }

        {$custom_css}
    ";

    return $css;
}

/**
 * Output customizer CSS.
 */
function aqualuxe_output_customizer_css() {
    echo '<style id="aqualuxe-customizer-css">' . aqualuxe_customizer_css() . '</style>';
}
add_action('wp_head', 'aqualuxe_output_customizer_css');

/**
 * Adjust color brightness.
 *
 * @param string $hex Hex color code.
 * @param int $steps Steps to adjust brightness (positive for lighter, negative for darker).
 * @return string
 */
function aqualuxe_adjust_brightness($hex, $steps) {
    // Remove # if present
    $hex = ltrim($hex, '#');

    // Convert to RGB
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    // Adjust brightness
    $r = max(0, min(255, $r + $steps));
    $g = max(0, min(255, $g + $steps));
    $b = max(0, min(255, $b + $steps));

    // Convert back to hex
    return sprintf('#%02x%02x%02x', $r, $g, $b);
}

/**
 * Enqueue Google Fonts.
 */
function aqualuxe_enqueue_google_fonts() {
    $body_font = get_theme_mod('aqualuxe_body_font', 'Montserrat');
    $heading_font = get_theme_mod('aqualuxe_heading_font', 'Playfair Display');

    $font_families = [];

    if ($body_font) {
        $font_families[] = $body_font . ':400,500,600,700';
    }

    if ($heading_font && $heading_font !== $body_font) {
        $font_families[] = $heading_font . ':400,500,600,700';
    }

    if (!empty($font_families)) {
        $query_args = [
            'family' => urlencode(implode('|', $font_families)),
            'display' => 'swap',
        ];

        $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css2');

        wp_enqueue_style('aqualuxe-google-fonts', $fonts_url, [], null);
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_google_fonts');

/**
 * Add preconnect for Google Fonts.
 *
 * @param array $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 * @return array $urls URLs to print for resource hints.
 */
function aqualuxe_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        $urls[] = [
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        ];
    }

    return $urls;
}
add_filter('wp_resource_hints', 'aqualuxe_resource_hints', 10, 2);

/**
 * Enqueue customizer controls script.
 */
function aqualuxe_customize_controls_enqueue_scripts() {
    wp_enqueue_script('aqualuxe-customize-controls', AQUALUXE_URI . '/assets/js/customize-controls.js', ['customize-controls'], AQUALUXE_VERSION, true);
}
add_action('customize_controls_enqueue_scripts', 'aqualuxe_customize_controls_enqueue_scripts');

/**
 * Output custom JavaScript.
 */
function aqualuxe_output_custom_js() {
    $custom_js = get_theme_mod('aqualuxe_custom_js', '');

    if (!empty($custom_js)) {
        echo '<script id="aqualuxe-custom-js">' . $custom_js . '</script>';
    }
}
add_action('wp_footer', 'aqualuxe_output_custom_js');