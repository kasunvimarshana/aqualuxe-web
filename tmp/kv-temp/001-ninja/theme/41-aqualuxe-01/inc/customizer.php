<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add theme customizer options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register($wp_customize) {
    // Add postMessage support for site title and description
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';
    
    // Add selective refresh for site title and description
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
    
    // Add sections
    aqualuxe_customize_general_section($wp_customize);
    aqualuxe_customize_header_section($wp_customize);
    aqualuxe_customize_footer_section($wp_customize);
    aqualuxe_customize_colors_section($wp_customize);
    aqualuxe_customize_typography_section($wp_customize);
    aqualuxe_customize_layout_section($wp_customize);
    aqualuxe_customize_blog_section($wp_customize);
    aqualuxe_customize_woocommerce_section($wp_customize);
    aqualuxe_customize_social_section($wp_customize);
    aqualuxe_customize_performance_section($wp_customize);
}
add_action('customize_register', 'aqualuxe_customize_register');

/**
 * Render the site title for the selective refresh partial
 *
 * @return void
 */
function aqualuxe_customize_partial_blogname() {
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial
 *
 * @return void
 */
function aqualuxe_customize_partial_blogdescription() {
    bloginfo('description');
}

/**
 * Add general section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_general_section($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_general_section', [
        'title' => __('General Settings', 'aqualuxe'),
        'description' => __('General theme settings', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 10,
    ]);
    
    // Add settings and controls
    
    // Logo
    $wp_customize->add_setting('aqualuxe_dark_logo', [
        'default' => '',
        'sanitize_callback' => 'absint',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aqualuxe_dark_logo', [
        'label' => __('Dark Mode Logo', 'aqualuxe'),
        'description' => __('Upload a logo to be displayed in dark mode', 'aqualuxe'),
        'section' => 'aqualuxe_general_section',
        'mime_type' => 'image',
        'priority' => 10,
    ]));
    
    // Favicon
    $wp_customize->add_setting('aqualuxe_favicon', [
        'default' => '',
        'sanitize_callback' => 'absint',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aqualuxe_favicon', [
        'label' => __('Favicon', 'aqualuxe'),
        'description' => __('Upload a favicon (will be used if Site Icon is not set)', 'aqualuxe'),
        'section' => 'aqualuxe_general_section',
        'mime_type' => 'image',
        'priority' => 20,
    ]));
    
    // Color Mode
    $wp_customize->add_setting('aqualuxe_default_color_mode', [
        'default' => 'light',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);
    
    $wp_customize->add_control('aqualuxe_default_color_mode', [
        'label' => __('Default Color Mode', 'aqualuxe'),
        'description' => __('Select the default color mode for your site', 'aqualuxe'),
        'section' => 'aqualuxe_general_section',
        'type' => 'select',
        'choices' => [
            'light' => __('Light', 'aqualuxe'),
            'dark' => __('Dark', 'aqualuxe'),
        ],
        'priority' => 30,
    ]);
    
    // Google Maps API Key
    $wp_customize->add_setting('aqualuxe_google_maps_api_key', [
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    
    $wp_customize->add_control('aqualuxe_google_maps_api_key', [
        'label' => __('Google Maps API Key', 'aqualuxe'),
        'description' => __('Enter your Google Maps API key for map features', 'aqualuxe'),
        'section' => 'aqualuxe_general_section',
        'type' => 'text',
        'priority' => 40,
    ]);
}

/**
 * Add header section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_header_section($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_header_section', [
        'title' => __('Header Settings', 'aqualuxe'),
        'description' => __('Customize the header section', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 20,
    ]);
    
    // Add settings and controls
    
    // Header Style
    $wp_customize->add_setting('aqualuxe_header_style', [
        'default' => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);
    
    $wp_customize->add_control('aqualuxe_header_style', [
        'label' => __('Header Style', 'aqualuxe'),
        'description' => __('Select the header style', 'aqualuxe'),
        'section' => 'aqualuxe_header_section',
        'type' => 'select',
        'choices' => [
            'default' => __('Default', 'aqualuxe'),
            'centered' => __('Centered', 'aqualuxe'),
            'minimal' => __('Minimal', 'aqualuxe'),
            'split' => __('Split', 'aqualuxe'),
            'transparent' => __('Transparent', 'aqualuxe'),
        ],
        'priority' => 10,
    ]);
    
    // Sticky Header
    $wp_customize->add_setting('aqualuxe_sticky_header', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_sticky_header', [
        'label' => __('Sticky Header', 'aqualuxe'),
        'description' => __('Enable sticky header', 'aqualuxe'),
        'section' => 'aqualuxe_header_section',
        'type' => 'checkbox',
        'priority' => 20,
    ]);
    
    // Show Search
    $wp_customize->add_setting('aqualuxe_header_show_search', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_header_show_search', [
        'label' => __('Show Search', 'aqualuxe'),
        'description' => __('Show search in header', 'aqualuxe'),
        'section' => 'aqualuxe_header_section',
        'type' => 'checkbox',
        'priority' => 30,
    ]);
    
    // Show Cart
    $wp_customize->add_setting('aqualuxe_header_show_cart', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_header_show_cart', [
        'label' => __('Show Cart', 'aqualuxe'),
        'description' => __('Show cart in header (requires WooCommerce)', 'aqualuxe'),
        'section' => 'aqualuxe_header_section',
        'type' => 'checkbox',
        'priority' => 40,
    ]);
    
    // Show Account
    $wp_customize->add_setting('aqualuxe_header_show_account', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_header_show_account', [
        'label' => __('Show Account', 'aqualuxe'),
        'description' => __('Show account in header (requires WooCommerce)', 'aqualuxe'),
        'section' => 'aqualuxe_header_section',
        'type' => 'checkbox',
        'priority' => 50,
    ]);
    
    // Show Wishlist
    $wp_customize->add_setting('aqualuxe_header_show_wishlist', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_header_show_wishlist', [
        'label' => __('Show Wishlist', 'aqualuxe'),
        'description' => __('Show wishlist in header (requires YITH WooCommerce Wishlist)', 'aqualuxe'),
        'section' => 'aqualuxe_header_section',
        'type' => 'checkbox',
        'priority' => 60,
    ]);
    
    // Show Language Switcher
    $wp_customize->add_setting('aqualuxe_header_show_language_switcher', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_header_show_language_switcher', [
        'label' => __('Show Language Switcher', 'aqualuxe'),
        'description' => __('Show language switcher in header (requires WPML or Polylang)', 'aqualuxe'),
        'section' => 'aqualuxe_header_section',
        'type' => 'checkbox',
        'priority' => 70,
    ]);
    
    // Show Currency Switcher
    $wp_customize->add_setting('aqualuxe_header_show_currency_switcher', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_header_show_currency_switcher', [
        'label' => __('Show Currency Switcher', 'aqualuxe'),
        'description' => __('Show currency switcher in header (requires WooCommerce Currency Switcher or WPML WooCommerce Multilingual)', 'aqualuxe'),
        'section' => 'aqualuxe_header_section',
        'type' => 'checkbox',
        'priority' => 80,
    ]);
    
    // Show Color Mode Toggle
    $wp_customize->add_setting('aqualuxe_header_show_color_mode_toggle', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_header_show_color_mode_toggle', [
        'label' => __('Show Color Mode Toggle', 'aqualuxe'),
        'description' => __('Show color mode toggle in header', 'aqualuxe'),
        'section' => 'aqualuxe_header_section',
        'type' => 'checkbox',
        'priority' => 90,
    ]);
}

/**
 * Add footer section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_footer_section($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_footer_section', [
        'title' => __('Footer Settings', 'aqualuxe'),
        'description' => __('Customize the footer section', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 30,
    ]);
    
    // Add settings and controls
    
    // Footer Style
    $wp_customize->add_setting('aqualuxe_footer_style', [
        'default' => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);
    
    $wp_customize->add_control('aqualuxe_footer_style', [
        'label' => __('Footer Style', 'aqualuxe'),
        'description' => __('Select the footer style', 'aqualuxe'),
        'section' => 'aqualuxe_footer_section',
        'type' => 'select',
        'choices' => [
            'default' => __('Default', 'aqualuxe'),
            'centered' => __('Centered', 'aqualuxe'),
            'minimal' => __('Minimal', 'aqualuxe'),
            'expanded' => __('Expanded', 'aqualuxe'),
            'dark' => __('Dark', 'aqualuxe'),
        ],
        'priority' => 10,
    ]);
    
    // Footer Widgets Columns
    $wp_customize->add_setting('aqualuxe_footer_widgets_columns', [
        'default' => 4,
        'sanitize_callback' => 'absint',
    ]);
    
    $wp_customize->add_control('aqualuxe_footer_widgets_columns', [
        'label' => __('Footer Widgets Columns', 'aqualuxe'),
        'description' => __('Select the number of footer widget columns', 'aqualuxe'),
        'section' => 'aqualuxe_footer_section',
        'type' => 'select',
        'choices' => [
            1 => __('1 Column', 'aqualuxe'),
            2 => __('2 Columns', 'aqualuxe'),
            3 => __('3 Columns', 'aqualuxe'),
            4 => __('4 Columns', 'aqualuxe'),
        ],
        'priority' => 20,
    ]);
    
    // Footer Copyright Text
    $wp_customize->add_setting('aqualuxe_footer_copyright', [
        'default' => sprintf(
            /* translators: %1$s: Current year, %2$s: Site name */
            __('&copy; %1$s %2$s. All rights reserved.', 'aqualuxe'),
            date('Y'),
            get_bloginfo('name')
        ),
        'sanitize_callback' => 'wp_kses_post',
    ]);
    
    $wp_customize->add_control('aqualuxe_footer_copyright', [
        'label' => __('Footer Copyright Text', 'aqualuxe'),
        'description' => __('Enter your copyright text', 'aqualuxe'),
        'section' => 'aqualuxe_footer_section',
        'type' => 'textarea',
        'priority' => 30,
    ]);
    
    // Show Back to Top
    $wp_customize->add_setting('aqualuxe_footer_show_back_to_top', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_footer_show_back_to_top', [
        'label' => __('Show Back to Top', 'aqualuxe'),
        'description' => __('Show back to top button', 'aqualuxe'),
        'section' => 'aqualuxe_footer_section',
        'type' => 'checkbox',
        'priority' => 40,
    ]);
    
    // Show Social Icons
    $wp_customize->add_setting('aqualuxe_footer_show_social_icons', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_footer_show_social_icons', [
        'label' => __('Show Social Icons', 'aqualuxe'),
        'description' => __('Show social icons in footer', 'aqualuxe'),
        'section' => 'aqualuxe_footer_section',
        'type' => 'checkbox',
        'priority' => 50,
    ]);
    
    // Show Payment Icons
    $wp_customize->add_setting('aqualuxe_footer_show_payment_icons', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_footer_show_payment_icons', [
        'label' => __('Show Payment Icons', 'aqualuxe'),
        'description' => __('Show payment icons in footer', 'aqualuxe'),
        'section' => 'aqualuxe_footer_section',
        'type' => 'checkbox',
        'priority' => 60,
    ]);
}

/**
 * Add colors section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_colors_section($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_colors_section', [
        'title' => __('Colors', 'aqualuxe'),
        'description' => __('Customize the theme colors', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 40,
    ]);
    
    // Add settings and controls
    
    // Color Scheme
    $wp_customize->add_setting('aqualuxe_color_scheme', [
        'default' => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);
    
    $wp_customize->add_control('aqualuxe_color_scheme', [
        'label' => __('Color Scheme', 'aqualuxe'),
        'description' => __('Select a predefined color scheme', 'aqualuxe'),
        'section' => 'aqualuxe_colors_section',
        'type' => 'select',
        'choices' => [
            'default' => __('Default', 'aqualuxe'),
            'ocean' => __('Ocean', 'aqualuxe'),
            'coral' => __('Coral', 'aqualuxe'),
            'emerald' => __('Emerald', 'aqualuxe'),
            'amethyst' => __('Amethyst', 'aqualuxe'),
        ],
        'priority' => 10,
    ]);
    
    // Primary Color
    $wp_customize->add_setting('aqualuxe_primary_color', [
        'default' => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', [
        'label' => __('Primary Color', 'aqualuxe'),
        'description' => __('Select the primary color', 'aqualuxe'),
        'section' => 'aqualuxe_colors_section',
        'priority' => 20,
    ]));
    
    // Secondary Color
    $wp_customize->add_setting('aqualuxe_secondary_color', [
        'default' => '#005177',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', [
        'label' => __('Secondary Color', 'aqualuxe'),
        'description' => __('Select the secondary color', 'aqualuxe'),
        'section' => 'aqualuxe_colors_section',
        'priority' => 30,
    ]));
    
    // Accent Color
    $wp_customize->add_setting('aqualuxe_accent_color', [
        'default' => '#00c6ff',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', [
        'label' => __('Accent Color', 'aqualuxe'),
        'description' => __('Select the accent color', 'aqualuxe'),
        'section' => 'aqualuxe_colors_section',
        'priority' => 40,
    ]));
    
    // Text Color
    $wp_customize->add_setting('aqualuxe_text_color', [
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_text_color', [
        'label' => __('Text Color', 'aqualuxe'),
        'description' => __('Select the text color', 'aqualuxe'),
        'section' => 'aqualuxe_colors_section',
        'priority' => 50,
    ]));
    
    // Background Color
    $wp_customize->add_setting('aqualuxe_background_color', [
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_background_color', [
        'label' => __('Background Color', 'aqualuxe'),
        'description' => __('Select the background color', 'aqualuxe'),
        'section' => 'aqualuxe_colors_section',
        'priority' => 60,
    ]));
    
    // Header Background Color
    $wp_customize->add_setting('aqualuxe_header_background_color', [
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_header_background_color', [
        'label' => __('Header Background Color', 'aqualuxe'),
        'description' => __('Select the header background color', 'aqualuxe'),
        'section' => 'aqualuxe_colors_section',
        'priority' => 70,
    ]));
    
    // Footer Background Color
    $wp_customize->add_setting('aqualuxe_footer_background_color', [
        'default' => '#f8f9fa',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_footer_background_color', [
        'label' => __('Footer Background Color', 'aqualuxe'),
        'description' => __('Select the footer background color', 'aqualuxe'),
        'section' => 'aqualuxe_colors_section',
        'priority' => 80,
    ]));
}

/**
 * Add typography section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_typography_section($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_typography_section', [
        'title' => __('Typography', 'aqualuxe'),
        'description' => __('Customize the theme typography', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 50,
    ]);
    
    // Add settings and controls
    
    // Typography Style
    $wp_customize->add_setting('aqualuxe_typography', [
        'default' => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);
    
    $wp_customize->add_control('aqualuxe_typography', [
        'label' => __('Typography Style', 'aqualuxe'),
        'description' => __('Select a predefined typography style', 'aqualuxe'),
        'section' => 'aqualuxe_typography_section',
        'type' => 'select',
        'choices' => [
            'default' => __('Default', 'aqualuxe'),
            'classic' => __('Classic', 'aqualuxe'),
            'modern' => __('Modern', 'aqualuxe'),
            'minimal' => __('Minimal', 'aqualuxe'),
            'elegant' => __('Elegant', 'aqualuxe'),
        ],
        'priority' => 10,
    ]);
    
    // Body Font
    $wp_customize->add_setting('aqualuxe_body_font', [
        'default' => "'Open Sans', sans-serif",
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    
    $wp_customize->add_control('aqualuxe_body_font', [
        'label' => __('Body Font', 'aqualuxe'),
        'description' => __('Enter the body font family', 'aqualuxe'),
        'section' => 'aqualuxe_typography_section',
        'type' => 'text',
        'priority' => 20,
    ]);
    
    // Heading Font
    $wp_customize->add_setting('aqualuxe_heading_font', [
        'default' => "'Montserrat', sans-serif",
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    
    $wp_customize->add_control('aqualuxe_heading_font', [
        'label' => __('Heading Font', 'aqualuxe'),
        'description' => __('Enter the heading font family', 'aqualuxe'),
        'section' => 'aqualuxe_typography_section',
        'type' => 'text',
        'priority' => 30,
    ]);
    
    // Base Font Size
    $wp_customize->add_setting('aqualuxe_base_font_size', [
        'default' => '16px',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    
    $wp_customize->add_control('aqualuxe_base_font_size', [
        'label' => __('Base Font Size', 'aqualuxe'),
        'description' => __('Enter the base font size (e.g., 16px)', 'aqualuxe'),
        'section' => 'aqualuxe_typography_section',
        'type' => 'text',
        'priority' => 40,
    ]);
    
    // Font Scale
    $wp_customize->add_setting('aqualuxe_font_scale', [
        'default' => '1.25',
        'sanitize_callback' => 'aqualuxe_sanitize_float',
    ]);
    
    $wp_customize->add_control('aqualuxe_font_scale', [
        'label' => __('Font Scale', 'aqualuxe'),
        'description' => __('Enter the font scale ratio (e.g., 1.25)', 'aqualuxe'),
        'section' => 'aqualuxe_typography_section',
        'type' => 'number',
        'input_attrs' => [
            'min' => 1,
            'max' => 2,
            'step' => 0.01,
        ],
        'priority' => 50,
    ]);
}

/**
 * Add layout section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_layout_section($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_layout_section', [
        'title' => __('Layout', 'aqualuxe'),
        'description' => __('Customize the theme layout', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 60,
    ]);
    
    // Add settings and controls
    
    // Container Width
    $wp_customize->add_setting('aqualuxe_container_width', [
        'default' => '1200px',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    
    $wp_customize->add_control('aqualuxe_container_width', [
        'label' => __('Container Width', 'aqualuxe'),
        'description' => __('Enter the container width (e.g., 1200px)', 'aqualuxe'),
        'section' => 'aqualuxe_layout_section',
        'type' => 'text',
        'priority' => 10,
    ]);
    
    // Container Padding
    $wp_customize->add_setting('aqualuxe_container_padding', [
        'default' => '1rem',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    
    $wp_customize->add_control('aqualuxe_container_padding', [
        'label' => __('Container Padding', 'aqualuxe'),
        'description' => __('Enter the container padding (e.g., 1rem)', 'aqualuxe'),
        'section' => 'aqualuxe_layout_section',
        'type' => 'text',
        'priority' => 20,
    ]);
    
    // Grid Gutter
    $wp_customize->add_setting('aqualuxe_grid_gutter', [
        'default' => '1.5rem',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    
    $wp_customize->add_control('aqualuxe_grid_gutter', [
        'label' => __('Grid Gutter', 'aqualuxe'),
        'description' => __('Enter the grid gutter width (e.g., 1.5rem)', 'aqualuxe'),
        'section' => 'aqualuxe_layout_section',
        'type' => 'text',
        'priority' => 30,
    ]);
    
    // Spacing Unit
    $wp_customize->add_setting('aqualuxe_spacing_unit', [
        'default' => '1rem',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    
    $wp_customize->add_control('aqualuxe_spacing_unit', [
        'label' => __('Spacing Unit', 'aqualuxe'),
        'description' => __('Enter the spacing unit (e.g., 1rem)', 'aqualuxe'),
        'section' => 'aqualuxe_layout_section',
        'type' => 'text',
        'priority' => 40,
    ]);
    
    // Border Radius
    $wp_customize->add_setting('aqualuxe_border_radius', [
        'default' => 'medium',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);
    
    $wp_customize->add_control('aqualuxe_border_radius', [
        'label' => __('Border Radius', 'aqualuxe'),
        'description' => __('Select the border radius style', 'aqualuxe'),
        'section' => 'aqualuxe_layout_section',
        'type' => 'select',
        'choices' => [
            'none' => __('None', 'aqualuxe'),
            'small' => __('Small', 'aqualuxe'),
            'medium' => __('Medium', 'aqualuxe'),
            'large' => __('Large', 'aqualuxe'),
        ],
        'priority' => 50,
    ]);
    
    // Box Shadow
    $wp_customize->add_setting('aqualuxe_box_shadow', [
        'default' => 'medium',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);
    
    $wp_customize->add_control('aqualuxe_box_shadow', [
        'label' => __('Box Shadow', 'aqualuxe'),
        'description' => __('Select the box shadow style', 'aqualuxe'),
        'section' => 'aqualuxe_layout_section',
        'type' => 'select',
        'choices' => [
            'none' => __('None', 'aqualuxe'),
            'small' => __('Small', 'aqualuxe'),
            'medium' => __('Medium', 'aqualuxe'),
            'large' => __('Large', 'aqualuxe'),
        ],
        'priority' => 60,
    ]);
    
    // Default Layout
    $wp_customize->add_setting('aqualuxe_default_layout', [
        'default' => 'right-sidebar',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);
    
    $wp_customize->add_control('aqualuxe_default_layout', [
        'label' => __('Default Layout', 'aqualuxe'),
        'description' => __('Select the default layout', 'aqualuxe'),
        'section' => 'aqualuxe_layout_section',
        'type' => 'select',
        'choices' => [
            'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
            'left-sidebar' => __('Left Sidebar', 'aqualuxe'),
            'no-sidebar' => __('No Sidebar', 'aqualuxe'),
        ],
        'priority' => 70,
    ]);
    
    // Page Layout
    $wp_customize->add_setting('aqualuxe_page_layout', [
        'default' => 'no-sidebar',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);
    
    $wp_customize->add_control('aqualuxe_page_layout', [
        'label' => __('Page Layout', 'aqualuxe'),
        'description' => __('Select the layout for pages', 'aqualuxe'),
        'section' => 'aqualuxe_layout_section',
        'type' => 'select',
        'choices' => [
            'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
            'left-sidebar' => __('Left Sidebar', 'aqualuxe'),
            'no-sidebar' => __('No Sidebar', 'aqualuxe'),
        ],
        'priority' => 80,
    ]);
}

/**
 * Add blog section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_blog_section($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_blog_section', [
        'title' => __('Blog Settings', 'aqualuxe'),
        'description' => __('Customize the blog settings', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 70,
    ]);
    
    // Add settings and controls
    
    // Blog Layout
    $wp_customize->add_setting('aqualuxe_blog_layout', [
        'default' => 'right-sidebar',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);
    
    $wp_customize->add_control('aqualuxe_blog_layout', [
        'label' => __('Blog Layout', 'aqualuxe'),
        'description' => __('Select the layout for blog pages', 'aqualuxe'),
        'section' => 'aqualuxe_blog_section',
        'type' => 'select',
        'choices' => [
            'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
            'left-sidebar' => __('Left Sidebar', 'aqualuxe'),
            'no-sidebar' => __('No Sidebar', 'aqualuxe'),
        ],
        'priority' => 10,
    ]);
    
    // Post Layout
    $wp_customize->add_setting('aqualuxe_post_layout', [
        'default' => 'right-sidebar',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);
    
    $wp_customize->add_control('aqualuxe_post_layout', [
        'label' => __('Post Layout', 'aqualuxe'),
        'description' => __('Select the layout for single posts', 'aqualuxe'),
        'section' => 'aqualuxe_blog_section',
        'type' => 'select',
        'choices' => [
            'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
            'left-sidebar' => __('Left Sidebar', 'aqualuxe'),
            'no-sidebar' => __('No Sidebar', 'aqualuxe'),
        ],
        'priority' => 20,
    ]);
    
    // Blog Style
    $wp_customize->add_setting('aqualuxe_blog_style', [
        'default' => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);
    
    $wp_customize->add_control('aqualuxe_blog_style', [
        'label' => __('Blog Style', 'aqualuxe'),
        'description' => __('Select the blog style', 'aqualuxe'),
        'section' => 'aqualuxe_blog_section',
        'type' => 'select',
        'choices' => [
            'default' => __('Default', 'aqualuxe'),
            'grid' => __('Grid', 'aqualuxe'),
            'masonry' => __('Masonry', 'aqualuxe'),
            'list' => __('List', 'aqualuxe'),
        ],
        'priority' => 30,
    ]);
    
    // Blog Columns
    $wp_customize->add_setting('aqualuxe_blog_columns', [
        'default' => 2,
        'sanitize_callback' => 'absint',
    ]);
    
    $wp_customize->add_control('aqualuxe_blog_columns', [
        'label' => __('Blog Columns', 'aqualuxe'),
        'description' => __('Select the number of columns for grid and masonry layouts', 'aqualuxe'),
        'section' => 'aqualuxe_blog_section',
        'type' => 'select',
        'choices' => [
            1 => __('1 Column', 'aqualuxe'),
            2 => __('2 Columns', 'aqualuxe'),
            3 => __('3 Columns', 'aqualuxe'),
            4 => __('4 Columns', 'aqualuxe'),
        ],
        'priority' => 40,
    ]);
    
    // Show Featured Image
    $wp_customize->add_setting('aqualuxe_blog_show_featured_image', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_blog_show_featured_image', [
        'label' => __('Show Featured Image', 'aqualuxe'),
        'description' => __('Show featured image in blog posts', 'aqualuxe'),
        'section' => 'aqualuxe_blog_section',
        'type' => 'checkbox',
        'priority' => 50,
    ]);
    
    // Show Post Meta
    $wp_customize->add_setting('aqualuxe_blog_show_post_meta', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_blog_show_post_meta', [
        'label' => __('Show Post Meta', 'aqualuxe'),
        'description' => __('Show post meta information (date, author, categories, etc.)', 'aqualuxe'),
        'section' => 'aqualuxe_blog_section',
        'type' => 'checkbox',
        'priority' => 60,
    ]);
    
    // Show Excerpt
    $wp_customize->add_setting('aqualuxe_blog_show_excerpt', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_blog_show_excerpt', [
        'label' => __('Show Excerpt', 'aqualuxe'),
        'description' => __('Show post excerpt in blog posts', 'aqualuxe'),
        'section' => 'aqualuxe_blog_section',
        'type' => 'checkbox',
        'priority' => 70,
    ]);
    
    // Excerpt Length
    $wp_customize->add_setting('aqualuxe_blog_excerpt_length', [
        'default' => 55,
        'sanitize_callback' => 'absint',
    ]);
    
    $wp_customize->add_control('aqualuxe_blog_excerpt_length', [
        'label' => __('Excerpt Length', 'aqualuxe'),
        'description' => __('Set the excerpt length in words', 'aqualuxe'),
        'section' => 'aqualuxe_blog_section',
        'type' => 'number',
        'input_attrs' => [
            'min' => 10,
            'max' => 200,
            'step' => 5,
        ],
        'priority' => 80,
    ]);
    
    // Show Read More
    $wp_customize->add_setting('aqualuxe_blog_show_read_more', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_blog_show_read_more', [
        'label' => __('Show Read More', 'aqualuxe'),
        'description' => __('Show read more link in blog posts', 'aqualuxe'),
        'section' => 'aqualuxe_blog_section',
        'type' => 'checkbox',
        'priority' => 90,
    ]);
    
    // Read More Text
    $wp_customize->add_setting('aqualuxe_blog_read_more_text', [
        'default' => __('Read More', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    
    $wp_customize->add_control('aqualuxe_blog_read_more_text', [
        'label' => __('Read More Text', 'aqualuxe'),
        'description' => __('Enter the read more link text', 'aqualuxe'),
        'section' => 'aqualuxe_blog_section',
        'type' => 'text',
        'priority' => 100,
    ]);
    
    // Show Related Posts
    $wp_customize->add_setting('aqualuxe_blog_show_related_posts', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_blog_show_related_posts', [
        'label' => __('Show Related Posts', 'aqualuxe'),
        'description' => __('Show related posts in single post', 'aqualuxe'),
        'section' => 'aqualuxe_blog_section',
        'type' => 'checkbox',
        'priority' => 110,
    ]);
    
    // Related Posts Count
    $wp_customize->add_setting('aqualuxe_blog_related_posts_count', [
        'default' => 3,
        'sanitize_callback' => 'absint',
    ]);
    
    $wp_customize->add_control('aqualuxe_blog_related_posts_count', [
        'label' => __('Related Posts Count', 'aqualuxe'),
        'description' => __('Set the number of related posts to show', 'aqualuxe'),
        'section' => 'aqualuxe_blog_section',
        'type' => 'number',
        'input_attrs' => [
            'min' => 1,
            'max' => 12,
            'step' => 1,
        ],
        'priority' => 120,
    ]);
    
    // Show Social Share
    $wp_customize->add_setting('aqualuxe_blog_show_social_share', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_blog_show_social_share', [
        'label' => __('Show Social Share', 'aqualuxe'),
        'description' => __('Show social share buttons in single post', 'aqualuxe'),
        'section' => 'aqualuxe_blog_section',
        'type' => 'checkbox',
        'priority' => 130,
    ]);
    
    // Show Author Bio
    $wp_customize->add_setting('aqualuxe_blog_show_author_bio', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_blog_show_author_bio', [
        'label' => __('Show Author Bio', 'aqualuxe'),
        'description' => __('Show author bio in single post', 'aqualuxe'),
        'section' => 'aqualuxe_blog_section',
        'type' => 'checkbox',
        'priority' => 140,
    ]);
    
    // Show Post Navigation
    $wp_customize->add_setting('aqualuxe_blog_show_post_navigation', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_blog_show_post_navigation', [
        'label' => __('Show Post Navigation', 'aqualuxe'),
        'description' => __('Show previous/next post navigation in single post', 'aqualuxe'),
        'section' => 'aqualuxe_blog_section',
        'type' => 'checkbox',
        'priority' => 150,
    ]);
}

/**
 * Add WooCommerce section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_woocommerce_section($wp_customize) {
    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }
    
    // Add section
    $wp_customize->add_section('aqualuxe_woocommerce_section', [
        'title' => __('WooCommerce Settings', 'aqualuxe'),
        'description' => __('Customize the WooCommerce settings', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 80,
    ]);
    
    // Add settings and controls
    
    // Shop Layout
    $wp_customize->add_setting('aqualuxe_shop_layout', [
        'default' => 'left-sidebar',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);
    
    $wp_customize->add_control('aqualuxe_shop_layout', [
        'label' => __('Shop Layout', 'aqualuxe'),
        'description' => __('Select the layout for shop pages', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce_section',
        'type' => 'select',
        'choices' => [
            'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
            'left-sidebar' => __('Left Sidebar', 'aqualuxe'),
            'no-sidebar' => __('No Sidebar', 'aqualuxe'),
        ],
        'priority' => 10,
    ]);
    
    // Product Layout
    $wp_customize->add_setting('aqualuxe_product_layout', [
        'default' => 'no-sidebar',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);
    
    $wp_customize->add_control('aqualuxe_product_layout', [
        'label' => __('Product Layout', 'aqualuxe'),
        'description' => __('Select the layout for single product pages', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce_section',
        'type' => 'select',
        'choices' => [
            'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
            'left-sidebar' => __('Left Sidebar', 'aqualuxe'),
            'no-sidebar' => __('No Sidebar', 'aqualuxe'),
        ],
        'priority' => 20,
    ]);
    
    // Products Per Page
    $wp_customize->add_setting('aqualuxe_products_per_page', [
        'default' => 12,
        'sanitize_callback' => 'absint',
    ]);
    
    $wp_customize->add_control('aqualuxe_products_per_page', [
        'label' => __('Products Per Page', 'aqualuxe'),
        'description' => __('Set the number of products per page', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce_section',
        'type' => 'number',
        'input_attrs' => [
            'min' => 1,
            'max' => 100,
            'step' => 1,
        ],
        'priority' => 30,
    ]);
    
    // Product Columns
    $wp_customize->add_setting('aqualuxe_product_columns', [
        'default' => 4,
        'sanitize_callback' => 'absint',
    ]);
    
    $wp_customize->add_control('aqualuxe_product_columns', [
        'label' => __('Product Columns', 'aqualuxe'),
        'description' => __('Set the number of product columns', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce_section',
        'type' => 'select',
        'choices' => [
            2 => __('2 Columns', 'aqualuxe'),
            3 => __('3 Columns', 'aqualuxe'),
            4 => __('4 Columns', 'aqualuxe'),
            5 => __('5 Columns', 'aqualuxe'),
        ],
        'priority' => 40,
    ]);
    
    // Related Products Count
    $wp_customize->add_setting('aqualuxe_related_products_count', [
        'default' => 4,
        'sanitize_callback' => 'absint',
    ]);
    
    $wp_customize->add_control('aqualuxe_related_products_count', [
        'label' => __('Related Products Count', 'aqualuxe'),
        'description' => __('Set the number of related products', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce_section',
        'type' => 'number',
        'input_attrs' => [
            'min' => 0,
            'max' => 12,
            'step' => 1,
        ],
        'priority' => 50,
    ]);
    
    // Upsell Products Count
    $wp_customize->add_setting('aqualuxe_upsell_products_count', [
        'default' => 4,
        'sanitize_callback' => 'absint',
    ]);
    
    $wp_customize->add_control('aqualuxe_upsell_products_count', [
        'label' => __('Upsell Products Count', 'aqualuxe'),
        'description' => __('Set the number of upsell products', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce_section',
        'type' => 'number',
        'input_attrs' => [
            'min' => 0,
            'max' => 12,
            'step' => 1,
        ],
        'priority' => 60,
    ]);
    
    // Cross-Sell Products Count
    $wp_customize->add_setting('aqualuxe_cross_sell_products_count', [
        'default' => 4,
        'sanitize_callback' => 'absint',
    ]);
    
    $wp_customize->add_control('aqualuxe_cross_sell_products_count', [
        'label' => __('Cross-Sell Products Count', 'aqualuxe'),
        'description' => __('Set the number of cross-sell products', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce_section',
        'type' => 'number',
        'input_attrs' => [
            'min' => 0,
            'max' => 12,
            'step' => 1,
        ],
        'priority' => 70,
    ]);
    
    // Show Quick View
    $wp_customize->add_setting('aqualuxe_show_quick_view', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_show_quick_view', [
        'label' => __('Show Quick View', 'aqualuxe'),
        'description' => __('Show quick view button on products', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce_section',
        'type' => 'checkbox',
        'priority' => 80,
    ]);
    
    // Show Wishlist
    $wp_customize->add_setting('aqualuxe_show_wishlist', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_show_wishlist', [
        'label' => __('Show Wishlist', 'aqualuxe'),
        'description' => __('Show wishlist button on products (requires YITH WooCommerce Wishlist)', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce_section',
        'type' => 'checkbox',
        'priority' => 90,
    ]);
    
    // Show Compare
    $wp_customize->add_setting('aqualuxe_show_compare', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_show_compare', [
        'label' => __('Show Compare', 'aqualuxe'),
        'description' => __('Show compare button on products (requires YITH WooCommerce Compare)', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce_section',
        'type' => 'checkbox',
        'priority' => 100,
    ]);
    
    // Show Product Categories
    $wp_customize->add_setting('aqualuxe_show_product_categories', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_show_product_categories', [
        'label' => __('Show Product Categories', 'aqualuxe'),
        'description' => __('Show product categories on single product', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce_section',
        'type' => 'checkbox',
        'priority' => 110,
    ]);
    
    // Show Product Tags
    $wp_customize->add_setting('aqualuxe_show_product_tags', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_show_product_tags', [
        'label' => __('Show Product Tags', 'aqualuxe'),
        'description' => __('Show product tags on single product', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce_section',
        'type' => 'checkbox',
        'priority' => 120,
    ]);
    
    // Show Product SKU
    $wp_customize->add_setting('aqualuxe_show_product_sku', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_show_product_sku', [
        'label' => __('Show Product SKU', 'aqualuxe'),
        'description' => __('Show product SKU on single product', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce_section',
        'type' => 'checkbox',
        'priority' => 130,
    ]);
    
    // Show Product Social Share
    $wp_customize->add_setting('aqualuxe_show_product_social_share', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_show_product_social_share', [
        'label' => __('Show Product Social Share', 'aqualuxe'),
        'description' => __('Show social share buttons on single product', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce_section',
        'type' => 'checkbox',
        'priority' => 140,
    ]);
}

/**
 * Add social section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_social_section($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_social_section', [
        'title' => __('Social Media', 'aqualuxe'),
        'description' => __('Customize social media settings', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 90,
    ]);
    
    // Add settings and controls
    
    // Facebook
    $wp_customize->add_setting('aqualuxe_social_facebook', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    
    $wp_customize->add_control('aqualuxe_social_facebook', [
        'label' => __('Facebook URL', 'aqualuxe'),
        'description' => __('Enter your Facebook URL', 'aqualuxe'),
        'section' => 'aqualuxe_social_section',
        'type' => 'url',
        'priority' => 10,
    ]);
    
    // Twitter
    $wp_customize->add_setting('aqualuxe_social_twitter', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    
    $wp_customize->add_control('aqualuxe_social_twitter', [
        'label' => __('Twitter URL', 'aqualuxe'),
        'description' => __('Enter your Twitter URL', 'aqualuxe'),
        'section' => 'aqualuxe_social_section',
        'type' => 'url',
        'priority' => 20,
    ]);
    
    // Instagram
    $wp_customize->add_setting('aqualuxe_social_instagram', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    
    $wp_customize->add_control('aqualuxe_social_instagram', [
        'label' => __('Instagram URL', 'aqualuxe'),
        'description' => __('Enter your Instagram URL', 'aqualuxe'),
        'section' => 'aqualuxe_social_section',
        'type' => 'url',
        'priority' => 30,
    ]);
    
    // LinkedIn
    $wp_customize->add_setting('aqualuxe_social_linkedin', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    
    $wp_customize->add_control('aqualuxe_social_linkedin', [
        'label' => __('LinkedIn URL', 'aqualuxe'),
        'description' => __('Enter your LinkedIn URL', 'aqualuxe'),
        'section' => 'aqualuxe_social_section',
        'type' => 'url',
        'priority' => 40,
    ]);
    
    // YouTube
    $wp_customize->add_setting('aqualuxe_social_youtube', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    
    $wp_customize->add_control('aqualuxe_social_youtube', [
        'label' => __('YouTube URL', 'aqualuxe'),
        'description' => __('Enter your YouTube URL', 'aqualuxe'),
        'section' => 'aqualuxe_social_section',
        'type' => 'url',
        'priority' => 50,
    ]);
    
    // Pinterest
    $wp_customize->add_setting('aqualuxe_social_pinterest', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    
    $wp_customize->add_control('aqualuxe_social_pinterest', [
        'label' => __('Pinterest URL', 'aqualuxe'),
        'description' => __('Enter your Pinterest URL', 'aqualuxe'),
        'section' => 'aqualuxe_social_section',
        'type' => 'url',
        'priority' => 60,
    ]);
    
    // TikTok
    $wp_customize->add_setting('aqualuxe_social_tiktok', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    
    $wp_customize->add_control('aqualuxe_social_tiktok', [
        'label' => __('TikTok URL', 'aqualuxe'),
        'description' => __('Enter your TikTok URL', 'aqualuxe'),
        'section' => 'aqualuxe_social_section',
        'type' => 'url',
        'priority' => 70,
    ]);
    
    // WhatsApp
    $wp_customize->add_setting('aqualuxe_social_whatsapp', [
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    
    $wp_customize->add_control('aqualuxe_social_whatsapp', [
        'label' => __('WhatsApp Number', 'aqualuxe'),
        'description' => __('Enter your WhatsApp number with country code (e.g., +1234567890)', 'aqualuxe'),
        'section' => 'aqualuxe_social_section',
        'type' => 'text',
        'priority' => 80,
    ]);
    
    // Email
    $wp_customize->add_setting('aqualuxe_social_email', [
        'default' => '',
        'sanitize_callback' => 'sanitize_email',
    ]);
    
    $wp_customize->add_control('aqualuxe_social_email', [
        'label' => __('Email Address', 'aqualuxe'),
        'description' => __('Enter your email address', 'aqualuxe'),
        'section' => 'aqualuxe_social_section',
        'type' => 'email',
        'priority' => 90,
    ]);
    
    // Phone
    $wp_customize->add_setting('aqualuxe_social_phone', [
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    
    $wp_customize->add_control('aqualuxe_social_phone', [
        'label' => __('Phone Number', 'aqualuxe'),
        'description' => __('Enter your phone number', 'aqualuxe'),
        'section' => 'aqualuxe_social_section',
        'type' => 'text',
        'priority' => 100,
    ]);
}

/**
 * Add performance section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_performance_section($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_performance_section', [
        'title' => __('Performance', 'aqualuxe'),
        'description' => __('Customize performance settings', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 100,
    ]);
    
    // Add settings and controls
    
    // Enable Lazy Loading
    $wp_customize->add_setting('aqualuxe_enable_lazy_loading', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_enable_lazy_loading', [
        'label' => __('Enable Lazy Loading', 'aqualuxe'),
        'description' => __('Enable lazy loading for images', 'aqualuxe'),
        'section' => 'aqualuxe_performance_section',
        'type' => 'checkbox',
        'priority' => 10,
    ]);
    
    // Enable Minification
    $wp_customize->add_setting('aqualuxe_enable_minification', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_enable_minification', [
        'label' => __('Enable Minification', 'aqualuxe'),
        'description' => __('Enable minification for CSS and JavaScript', 'aqualuxe'),
        'section' => 'aqualuxe_performance_section',
        'type' => 'checkbox',
        'priority' => 20,
    ]);
    
    // Enable Critical CSS
    $wp_customize->add_setting('aqualuxe_enable_critical_css', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_enable_critical_css', [
        'label' => __('Enable Critical CSS', 'aqualuxe'),
        'description' => __('Enable critical CSS for above-the-fold content', 'aqualuxe'),
        'section' => 'aqualuxe_performance_section',
        'type' => 'checkbox',
        'priority' => 30,
    ]);
    
    // Enable Preload
    $wp_customize->add_setting('aqualuxe_enable_preload', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_enable_preload', [
        'label' => __('Enable Preload', 'aqualuxe'),
        'description' => __('Enable preloading for critical resources', 'aqualuxe'),
        'section' => 'aqualuxe_performance_section',
        'type' => 'checkbox',
        'priority' => 40,
    ]);
    
    // Enable Prefetch
    $wp_customize->add_setting('aqualuxe_enable_prefetch', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_enable_prefetch', [
        'label' => __('Enable Prefetch', 'aqualuxe'),
        'description' => __('Enable prefetching for external resources', 'aqualuxe'),
        'section' => 'aqualuxe_performance_section',
        'type' => 'checkbox',
        'priority' => 50,
    ]);
    
    // Enable Preconnect
    $wp_customize->add_setting('aqualuxe_enable_preconnect', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_enable_preconnect', [
        'label' => __('Enable Preconnect', 'aqualuxe'),
        'description' => __('Enable preconnect for external domains', 'aqualuxe'),
        'section' => 'aqualuxe_performance_section',
        'type' => 'checkbox',
        'priority' => 60,
    ]);
    
    // Enable Browser Caching
    $wp_customize->add_setting('aqualuxe_enable_browser_caching', [
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_enable_browser_caching', [
        'label' => __('Enable Browser Caching', 'aqualuxe'),
        'description' => __('Enable browser caching for static resources', 'aqualuxe'),
        'section' => 'aqualuxe_performance_section',
        'type' => 'checkbox',
        'priority' => 70,
    ]);
}

/**
 * Sanitize checkbox
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox($checked) {
    return (isset($checked) && true === $checked) ? true : false;
}

/**
 * Sanitize select
 *
 * @param string $input Select value.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return string
 */
function aqualuxe_sanitize_select($input, $setting) {
    // Get the list of choices from the control associated with the setting
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    // Return input if valid or return default if not
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize float
 *
 * @param string $input Float value.
 * @return float
 */
function aqualuxe_sanitize_float($input) {
    return floatval($input);
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
    wp_enqueue_script('aqualuxe-customizer', AQUALUXE_ASSETS_URI . 'js/customizer.js', ['customize-preview'], AQUALUXE_VERSION, true);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');

/**
 * Enqueue customizer controls scripts
 */
function aqualuxe_customize_controls_js() {
    wp_enqueue_script('aqualuxe-customizer-controls', AQUALUXE_ASSETS_URI . 'js/customizer-controls.js', ['customize-controls'], AQUALUXE_VERSION, true);
}
add_action('customize_controls_enqueue_scripts', 'aqualuxe_customize_controls_js');

/**
 * Enqueue customizer controls styles
 */
function aqualuxe_customize_controls_css() {
    wp_enqueue_style('aqualuxe-customizer-controls', AQUALUXE_ASSETS_URI . 'css/customizer-controls.css', [], AQUALUXE_VERSION);
}
add_action('customize_controls_enqueue_scripts', 'aqualuxe_customize_controls_css');