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
    // Add selective refresh support
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
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
    $wp_customize->add_panel('aqualuxe_theme_options', array(
        'title'       => __('AquaLuxe Theme Options', 'aqualuxe'),
        'description' => __('Customize your AquaLuxe theme settings', 'aqualuxe'),
        'priority'    => 130,
    ));

    // Add sections
    aqualuxe_add_general_settings($wp_customize);
    aqualuxe_add_header_settings($wp_customize);
    aqualuxe_add_footer_settings($wp_customize);
    aqualuxe_add_typography_settings($wp_customize);
    aqualuxe_add_color_settings($wp_customize);
    aqualuxe_add_layout_settings($wp_customize);
    aqualuxe_add_blog_settings($wp_customize);
    aqualuxe_add_social_settings($wp_customize);
    aqualuxe_add_integration_settings($wp_customize);
    
    // Add WooCommerce settings if WooCommerce is active
    if (class_exists('WooCommerce')) {
        aqualuxe_add_woocommerce_settings($wp_customize);
    }
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
 * Add general settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_add_general_settings($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_general_settings', array(
        'title'       => __('General Settings', 'aqualuxe'),
        'description' => __('General theme settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 10,
    ));

    // Add settings
    $wp_customize->add_setting('aqualuxe_container_width', array(
        'default'           => '1280px',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_container_width', array(
        'label'       => __('Container Width', 'aqualuxe'),
        'description' => __('Set the maximum width of the content container (e.g., 1280px)', 'aqualuxe'),
        'section'     => 'aqualuxe_general_settings',
        'type'        => 'text',
    ));

    // Preloader
    $wp_customize->add_setting('aqualuxe_enable_preloader', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_preloader', array(
        'label'       => __('Enable Preloader', 'aqualuxe'),
        'description' => __('Show a loading animation while the page loads', 'aqualuxe'),
        'section'     => 'aqualuxe_general_settings',
        'type'        => 'checkbox',
    ));

    // Back to top button
    $wp_customize->add_setting('aqualuxe_enable_back_to_top', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_back_to_top', array(
        'label'       => __('Enable Back to Top Button', 'aqualuxe'),
        'description' => __('Show a button to scroll back to the top of the page', 'aqualuxe'),
        'section'     => 'aqualuxe_general_settings',
        'type'        => 'checkbox',
    ));

    // Smooth scroll
    $wp_customize->add_setting('aqualuxe_enable_smooth_scroll', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_smooth_scroll', array(
        'label'       => __('Enable Smooth Scrolling', 'aqualuxe'),
        'description' => __('Enable smooth scrolling effect on the page', 'aqualuxe'),
        'section'     => 'aqualuxe_general_settings',
        'type'        => 'checkbox',
    ));

    // Breadcrumbs
    $wp_customize->add_setting('aqualuxe_enable_breadcrumbs', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_breadcrumbs', array(
        'label'       => __('Enable Breadcrumbs', 'aqualuxe'),
        'description' => __('Show breadcrumb navigation on pages', 'aqualuxe'),
        'section'     => 'aqualuxe_general_settings',
        'type'        => 'checkbox',
    ));
}

/**
 * Add header settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_add_header_settings($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_header_settings', array(
        'title'       => __('Header Settings', 'aqualuxe'),
        'description' => __('Customize the header section', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 20,
    ));

    // Header layout
    $wp_customize->add_setting('aqualuxe_header_layout', array(
        'default'           => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_header_layout', array(
        'label'       => __('Header Layout', 'aqualuxe'),
        'description' => __('Select the header layout style', 'aqualuxe'),
        'section'     => 'aqualuxe_header_settings',
        'type'        => 'select',
        'choices'     => array(
            'default'      => __('Default', 'aqualuxe'),
            'centered'     => __('Centered', 'aqualuxe'),
            'transparent'  => __('Transparent', 'aqualuxe'),
            'minimal'      => __('Minimal', 'aqualuxe'),
        ),
    ));

    // Sticky header
    $wp_customize->add_setting('aqualuxe_sticky_header', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_sticky_header', array(
        'label'       => __('Enable Sticky Header', 'aqualuxe'),
        'description' => __('Keep the header visible when scrolling down', 'aqualuxe'),
        'section'     => 'aqualuxe_header_settings',
        'type'        => 'checkbox',
    ));

    // Header top bar
    $wp_customize->add_setting('aqualuxe_enable_top_bar', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_top_bar', array(
        'label'       => __('Enable Top Bar', 'aqualuxe'),
        'description' => __('Show a top bar above the main header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_settings',
        'type'        => 'checkbox',
    ));

    // Top bar content
    $wp_customize->add_setting('aqualuxe_top_bar_content_left', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('aqualuxe_top_bar_content_left', array(
        'label'       => __('Top Bar Left Content', 'aqualuxe'),
        'description' => __('HTML or text for the left side of the top bar', 'aqualuxe'),
        'section'     => 'aqualuxe_header_settings',
        'type'        => 'textarea',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_top_bar', true);
        },
    ));

    $wp_customize->add_setting('aqualuxe_top_bar_content_right', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('aqualuxe_top_bar_content_right', array(
        'label'       => __('Top Bar Right Content', 'aqualuxe'),
        'description' => __('HTML or text for the right side of the top bar', 'aqualuxe'),
        'section'     => 'aqualuxe_header_settings',
        'type'        => 'textarea',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_top_bar', true);
        },
    ));

    // Logo height
    $wp_customize->add_setting('aqualuxe_logo_height', array(
        'default'           => '60',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_logo_height', array(
        'label'       => __('Logo Height (px)', 'aqualuxe'),
        'description' => __('Set the height of the logo in pixels', 'aqualuxe'),
        'section'     => 'aqualuxe_header_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 20,
            'max'  => 200,
            'step' => 1,
        ),
    ));

    // Search in header
    $wp_customize->add_setting('aqualuxe_header_search', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_header_search', array(
        'label'       => __('Show Search in Header', 'aqualuxe'),
        'description' => __('Display a search icon in the header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_settings',
        'type'        => 'checkbox',
    ));

    // Cart in header (if WooCommerce is active)
    if (class_exists('WooCommerce')) {
        $wp_customize->add_setting('aqualuxe_header_cart', array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));

        $wp_customize->add_control('aqualuxe_header_cart', array(
            'label'       => __('Show Cart in Header', 'aqualuxe'),
            'description' => __('Display a cart icon in the header', 'aqualuxe'),
            'section'     => 'aqualuxe_header_settings',
            'type'        => 'checkbox',
        ));
    }

    // Account in header (if WooCommerce is active)
    if (class_exists('WooCommerce')) {
        $wp_customize->add_setting('aqualuxe_header_account', array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));

        $wp_customize->add_control('aqualuxe_header_account', array(
            'label'       => __('Show Account in Header', 'aqualuxe'),
            'description' => __('Display an account icon in the header', 'aqualuxe'),
            'section'     => 'aqualuxe_header_settings',
            'type'        => 'checkbox',
        ));
    }
}

/**
 * Add footer settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_add_footer_settings($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_footer_settings', array(
        'title'       => __('Footer Settings', 'aqualuxe'),
        'description' => __('Customize the footer section', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 30,
    ));

    // Footer columns
    $wp_customize->add_setting('aqualuxe_footer_columns', array(
        'default'           => 4,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_footer_columns', array(
        'label'       => __('Footer Widget Columns', 'aqualuxe'),
        'description' => __('Number of widget columns in the footer', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
        'type'        => 'select',
        'choices'     => array(
            1 => __('1 Column', 'aqualuxe'),
            2 => __('2 Columns', 'aqualuxe'),
            3 => __('3 Columns', 'aqualuxe'),
            4 => __('4 Columns', 'aqualuxe'),
            5 => __('5 Columns', 'aqualuxe'),
            6 => __('6 Columns', 'aqualuxe'),
        ),
    ));

    // Footer logo
    $wp_customize->add_setting('aqualuxe_footer_logo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aqualuxe_footer_logo', array(
        'label'       => __('Footer Logo', 'aqualuxe'),
        'description' => __('Upload a logo for the footer (optional)', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
    )));

    // Copyright text
    $wp_customize->add_setting('aqualuxe_copyright_text', array(
        'default'           => '© ' . date('Y') . ' ' . get_bloginfo('name') . '. ' . __('All rights reserved.', 'aqualuxe'),
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_copyright_text', array(
        'label'       => __('Copyright Text', 'aqualuxe'),
        'description' => __('Enter your copyright text', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
        'type'        => 'textarea',
    ));

    // Footer credits
    $wp_customize->add_setting('aqualuxe_footer_credits', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_footer_credits', array(
        'label'       => __('Show Theme Credits', 'aqualuxe'),
        'description' => __('Display "Powered by AquaLuxe" in the footer', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
        'type'        => 'checkbox',
    ));

    // Footer background
    $wp_customize->add_setting('aqualuxe_footer_background', array(
        'default'           => '#0a1a2a',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_footer_background', array(
        'label'       => __('Footer Background Color', 'aqualuxe'),
        'description' => __('Set the background color for the footer', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
    )));

    // Footer text color
    $wp_customize->add_setting('aqualuxe_footer_text_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_footer_text_color', array(
        'label'       => __('Footer Text Color', 'aqualuxe'),
        'description' => __('Set the text color for the footer', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
    )));

    // Newsletter in footer
    $wp_customize->add_setting('aqualuxe_footer_newsletter', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_footer_newsletter', array(
        'label'       => __('Show Newsletter in Footer', 'aqualuxe'),
        'description' => __('Display a newsletter signup form in the footer', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
        'type'        => 'checkbox',
    ));

    // Newsletter shortcode
    $wp_customize->add_setting('aqualuxe_newsletter_shortcode', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_newsletter_shortcode', array(
        'label'       => __('Newsletter Shortcode', 'aqualuxe'),
        'description' => __('Enter a shortcode for your newsletter form (e.g., from Mailchimp)', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
        'type'        => 'text',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_footer_newsletter', true);
        },
    ));
}

/**
 * Add typography settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_add_typography_settings($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_typography_settings', array(
        'title'       => __('Typography Settings', 'aqualuxe'),
        'description' => __('Customize the typography', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 40,
    ));

    // Body font family
    $wp_customize->add_setting('aqualuxe_body_font_family', array(
        'default'           => 'Montserrat, sans-serif',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_body_font_family', array(
        'label'       => __('Body Font Family', 'aqualuxe'),
        'description' => __('Enter a font family for the body text', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'select',
        'choices'     => array(
            'Montserrat, sans-serif'      => __('Montserrat', 'aqualuxe'),
            'Open Sans, sans-serif'       => __('Open Sans', 'aqualuxe'),
            'Roboto, sans-serif'          => __('Roboto', 'aqualuxe'),
            'Lato, sans-serif'            => __('Lato', 'aqualuxe'),
            'Poppins, sans-serif'         => __('Poppins', 'aqualuxe'),
            'Source Sans Pro, sans-serif' => __('Source Sans Pro', 'aqualuxe'),
        ),
    ));

    // Heading font family
    $wp_customize->add_setting('aqualuxe_heading_font_family', array(
        'default'           => 'Playfair Display, serif',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_heading_font_family', array(
        'label'       => __('Heading Font Family', 'aqualuxe'),
        'description' => __('Enter a font family for headings', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'select',
        'choices'     => array(
            'Playfair Display, serif'     => __('Playfair Display', 'aqualuxe'),
            'Montserrat, sans-serif'      => __('Montserrat', 'aqualuxe'),
            'Merriweather, serif'         => __('Merriweather', 'aqualuxe'),
            'Roboto Slab, serif'          => __('Roboto Slab', 'aqualuxe'),
            'Lora, serif'                 => __('Lora', 'aqualuxe'),
            'Cormorant Garamond, serif'   => __('Cormorant Garamond', 'aqualuxe'),
        ),
    ));

    // Body font size
    $wp_customize->add_setting('aqualuxe_body_font_size', array(
        'default'           => '16',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_body_font_size', array(
        'label'       => __('Body Font Size (px)', 'aqualuxe'),
        'description' => __('Set the base font size in pixels', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 12,
            'max'  => 24,
            'step' => 1,
        ),
    ));

    // Line height
    $wp_customize->add_setting('aqualuxe_line_height', array(
        'default'           => '1.6',
        'sanitize_callback' => 'aqualuxe_sanitize_float',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_line_height', array(
        'label'       => __('Line Height', 'aqualuxe'),
        'description' => __('Set the line height multiplier', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 2,
            'step' => 0.1,
        ),
    ));

    // Heading line height
    $wp_customize->add_setting('aqualuxe_heading_line_height', array(
        'default'           => '1.2',
        'sanitize_callback' => 'aqualuxe_sanitize_float',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_heading_line_height', array(
        'label'       => __('Heading Line Height', 'aqualuxe'),
        'description' => __('Set the line height multiplier for headings', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 2,
            'step' => 0.1,
        ),
    ));

    // Font weight
    $wp_customize->add_setting('aqualuxe_body_font_weight', array(
        'default'           => '400',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_body_font_weight', array(
        'label'       => __('Body Font Weight', 'aqualuxe'),
        'description' => __('Set the font weight for body text', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'select',
        'choices'     => array(
            '300' => __('Light (300)', 'aqualuxe'),
            '400' => __('Regular (400)', 'aqualuxe'),
            '500' => __('Medium (500)', 'aqualuxe'),
            '600' => __('Semi-Bold (600)', 'aqualuxe'),
            '700' => __('Bold (700)', 'aqualuxe'),
        ),
    ));

    // Heading font weight
    $wp_customize->add_setting('aqualuxe_heading_font_weight', array(
        'default'           => '600',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_heading_font_weight', array(
        'label'       => __('Heading Font Weight', 'aqualuxe'),
        'description' => __('Set the font weight for headings', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'select',
        'choices'     => array(
            '400' => __('Regular (400)', 'aqualuxe'),
            '500' => __('Medium (500)', 'aqualuxe'),
            '600' => __('Semi-Bold (600)', 'aqualuxe'),
            '700' => __('Bold (700)', 'aqualuxe'),
        ),
    ));
}

/**
 * Add color settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_add_color_settings($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_color_settings', array(
        'title'       => __('Color Settings', 'aqualuxe'),
        'description' => __('Customize the theme colors', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 50,
    ));

    // Primary color
    $wp_customize->add_setting('aqualuxe_primary_color', array(
        'default'           => '#0077b6',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', array(
        'label'       => __('Primary Color', 'aqualuxe'),
        'description' => __('Set the primary theme color', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));

    // Secondary color
    $wp_customize->add_setting('aqualuxe_secondary_color', array(
        'default'           => '#00b4d8',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', array(
        'label'       => __('Secondary Color', 'aqualuxe'),
        'description' => __('Set the secondary theme color', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));

    // Accent color
    $wp_customize->add_setting('aqualuxe_accent_color', array(
        'default'           => '#90e0ef',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', array(
        'label'       => __('Accent Color', 'aqualuxe'),
        'description' => __('Set the accent theme color', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));

    // Text color
    $wp_customize->add_setting('aqualuxe_text_color', array(
        'default'           => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_text_color', array(
        'label'       => __('Text Color', 'aqualuxe'),
        'description' => __('Set the main text color', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));

    // Heading color
    $wp_customize->add_setting('aqualuxe_heading_color', array(
        'default'           => '#222222',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_heading_color', array(
        'label'       => __('Heading Color', 'aqualuxe'),
        'description' => __('Set the color for headings', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));

    // Background color
    $wp_customize->add_setting('aqualuxe_background_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_background_color', array(
        'label'       => __('Background Color', 'aqualuxe'),
        'description' => __('Set the main background color', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));

    // Dark mode colors
    $wp_customize->add_setting('aqualuxe_dark_background_color', array(
        'default'           => '#121212',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_background_color', array(
        'label'       => __('Dark Mode Background Color', 'aqualuxe'),
        'description' => __('Set the background color for dark mode', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));

    $wp_customize->add_setting('aqualuxe_dark_text_color', array(
        'default'           => '#e0e0e0',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_text_color', array(
        'label'       => __('Dark Mode Text Color', 'aqualuxe'),
        'description' => __('Set the text color for dark mode', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));
}

/**
 * Add layout settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_add_layout_settings($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_layout_settings', array(
        'title'       => __('Layout Settings', 'aqualuxe'),
        'description' => __('Customize the layout settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 60,
    ));

    // Sidebar position
    $wp_customize->add_setting('aqualuxe_sidebar_position', array(
        'default'           => 'right',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_sidebar_position', array(
        'label'       => __('Sidebar Position', 'aqualuxe'),
        'description' => __('Choose the position of the sidebar', 'aqualuxe'),
        'section'     => 'aqualuxe_layout_settings',
        'type'        => 'select',
        'choices'     => array(
            'right' => __('Right', 'aqualuxe'),
            'left'  => __('Left', 'aqualuxe'),
            'none'  => __('No Sidebar', 'aqualuxe'),
        ),
    ));

    // Page layout
    $wp_customize->add_setting('aqualuxe_page_layout', array(
        'default'           => 'normal',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_page_layout', array(
        'label'       => __('Page Layout', 'aqualuxe'),
        'description' => __('Choose the layout for pages', 'aqualuxe'),
        'section'     => 'aqualuxe_layout_settings',
        'type'        => 'select',
        'choices'     => array(
            'normal'     => __('Normal', 'aqualuxe'),
            'full-width' => __('Full Width', 'aqualuxe'),
            'boxed'      => __('Boxed', 'aqualuxe'),
        ),
    ));

    // Content width
    $wp_customize->add_setting('aqualuxe_content_width', array(
        'default'           => '800',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_content_width', array(
        'label'       => __('Content Width (px)', 'aqualuxe'),
        'description' => __('Set the maximum width of the content area in pixels', 'aqualuxe'),
        'section'     => 'aqualuxe_layout_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 600,
            'max'  => 1200,
            'step' => 10,
        ),
    ));

    // Sidebar width
    $wp_customize->add_setting('aqualuxe_sidebar_width', array(
        'default'           => '300',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_sidebar_width', array(
        'label'       => __('Sidebar Width (px)', 'aqualuxe'),
        'description' => __('Set the width of the sidebar in pixels', 'aqualuxe'),
        'section'     => 'aqualuxe_layout_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 200,
            'max'  => 500,
            'step' => 10,
        ),
    ));

    // Content padding
    $wp_customize->add_setting('aqualuxe_content_padding', array(
        'default'           => '40',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_content_padding', array(
        'label'       => __('Content Padding (px)', 'aqualuxe'),
        'description' => __('Set the padding around the content area in pixels', 'aqualuxe'),
        'section'     => 'aqualuxe_layout_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 100,
            'step' => 5,
        ),
    ));
}

/**
 * Add blog settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_add_blog_settings($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_blog_settings', array(
        'title'       => __('Blog Settings', 'aqualuxe'),
        'description' => __('Customize the blog settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 70,
    ));

    // Blog layout
    $wp_customize->add_setting('aqualuxe_blog_layout', array(
        'default'           => 'grid',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_blog_layout', array(
        'label'       => __('Blog Layout', 'aqualuxe'),
        'description' => __('Choose the layout for the blog archive', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_settings',
        'type'        => 'select',
        'choices'     => array(
            'grid'    => __('Grid', 'aqualuxe'),
            'list'    => __('List', 'aqualuxe'),
            'masonry' => __('Masonry', 'aqualuxe'),
            'classic' => __('Classic', 'aqualuxe'),
        ),
    ));

    // Blog columns
    $wp_customize->add_setting('aqualuxe_blog_columns', array(
        'default'           => '3',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_blog_columns', array(
        'label'       => __('Blog Columns', 'aqualuxe'),
        'description' => __('Number of columns for grid and masonry layouts', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_settings',
        'type'        => 'select',
        'choices'     => array(
            '2' => __('2 Columns', 'aqualuxe'),
            '3' => __('3 Columns', 'aqualuxe'),
            '4' => __('4 Columns', 'aqualuxe'),
        ),
        'active_callback' => function() {
            $layout = get_theme_mod('aqualuxe_blog_layout', 'grid');
            return ($layout === 'grid' || $layout === 'masonry');
        },
    ));

    // Posts per page
    $wp_customize->add_setting('aqualuxe_posts_per_page', array(
        'default'           => get_option('posts_per_page'),
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_posts_per_page', array(
        'label'       => __('Posts Per Page', 'aqualuxe'),
        'description' => __('Number of posts to show per page', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 50,
            'step' => 1,
        ),
    ));

    // Excerpt length
    $wp_customize->add_setting('aqualuxe_excerpt_length', array(
        'default'           => '55',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_excerpt_length', array(
        'label'       => __('Excerpt Length', 'aqualuxe'),
        'description' => __('Number of words in the excerpt', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 10,
            'max'  => 200,
            'step' => 5,
        ),
    ));

    // Featured image
    $wp_customize->add_setting('aqualuxe_show_featured_image', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_show_featured_image', array(
        'label'       => __('Show Featured Image', 'aqualuxe'),
        'description' => __('Display the featured image in blog posts', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_settings',
        'type'        => 'checkbox',
    ));

    // Post meta
    $wp_customize->add_setting('aqualuxe_show_post_date', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_show_post_date', array(
        'label'       => __('Show Post Date', 'aqualuxe'),
        'description' => __('Display the post date in blog posts', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_settings',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_show_post_author', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_show_post_author', array(
        'label'       => __('Show Post Author', 'aqualuxe'),
        'description' => __('Display the post author in blog posts', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_settings',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_show_post_categories', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_show_post_categories', array(
        'label'       => __('Show Post Categories', 'aqualuxe'),
        'description' => __('Display the post categories in blog posts', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_settings',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_show_post_tags', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_show_post_tags', array(
        'label'       => __('Show Post Tags', 'aqualuxe'),
        'description' => __('Display the post tags in blog posts', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_settings',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_show_post_comments', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_show_post_comments', array(
        'label'       => __('Show Post Comments', 'aqualuxe'),
        'description' => __('Display the post comments in blog posts', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_settings',
        'type'        => 'checkbox',
    ));

    // Related posts
    $wp_customize->add_setting('aqualuxe_show_related_posts', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_show_related_posts', array(
        'label'       => __('Show Related Posts', 'aqualuxe'),
        'description' => __('Display related posts at the end of blog posts', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_settings',
        'type'        => 'checkbox',
    ));

    // Social sharing
    $wp_customize->add_setting('aqualuxe_show_social_sharing', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_show_social_sharing', array(
        'label'       => __('Show Social Sharing', 'aqualuxe'),
        'description' => __('Display social sharing buttons in blog posts', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_settings',
        'type'        => 'checkbox',
    ));
}

/**
 * Add social settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_add_social_settings($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_social_settings', array(
        'title'       => __('Social Media Settings', 'aqualuxe'),
        'description' => __('Enter your social media URLs', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 80,
    ));

    // Social media URLs
    $social_platforms = array(
        'facebook'  => __('Facebook', 'aqualuxe'),
        'twitter'   => __('Twitter', 'aqualuxe'),
        'instagram' => __('Instagram', 'aqualuxe'),
        'linkedin'  => __('LinkedIn', 'aqualuxe'),
        'youtube'   => __('YouTube', 'aqualuxe'),
        'pinterest' => __('Pinterest', 'aqualuxe'),
        'tiktok'    => __('TikTok', 'aqualuxe'),
    );

    foreach ($social_platforms as $platform => $label) {
        $wp_customize->add_setting('aqualuxe_social_' . $platform, array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control('aqualuxe_social_' . $platform, array(
            'label'       => $label,
            'description' => sprintf(__('Enter your %s URL', 'aqualuxe'), $label),
            'section'     => 'aqualuxe_social_settings',
            'type'        => 'url',
        ));
    }

    // Social icons in header
    $wp_customize->add_setting('aqualuxe_show_social_header', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_show_social_header', array(
        'label'       => __('Show Social Icons in Header', 'aqualuxe'),
        'description' => __('Display social media icons in the header', 'aqualuxe'),
        'section'     => 'aqualuxe_social_settings',
        'type'        => 'checkbox',
    ));

    // Social icons in footer
    $wp_customize->add_setting('aqualuxe_show_social_footer', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_show_social_footer', array(
        'label'       => __('Show Social Icons in Footer', 'aqualuxe'),
        'description' => __('Display social media icons in the footer', 'aqualuxe'),
        'section'     => 'aqualuxe_social_settings',
        'type'        => 'checkbox',
    ));

    // Social sharing platforms
    $wp_customize->add_setting('aqualuxe_social_sharing_platforms', array(
        'default'           => array('facebook', 'twitter', 'linkedin', 'pinterest', 'email'),
        'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Multicheck_Control($wp_customize, 'aqualuxe_social_sharing_platforms', array(
        'label'       => __('Social Sharing Platforms', 'aqualuxe'),
        'description' => __('Select which platforms to include in social sharing', 'aqualuxe'),
        'section'     => 'aqualuxe_social_settings',
        'choices'     => array(
            'facebook'  => __('Facebook', 'aqualuxe'),
            'twitter'   => __('Twitter', 'aqualuxe'),
            'linkedin'  => __('LinkedIn', 'aqualuxe'),
            'pinterest' => __('Pinterest', 'aqualuxe'),
            'reddit'    => __('Reddit', 'aqualuxe'),
            'tumblr'    => __('Tumblr', 'aqualuxe'),
            'whatsapp'  => __('WhatsApp', 'aqualuxe'),
            'telegram'  => __('Telegram', 'aqualuxe'),
            'email'     => __('Email', 'aqualuxe'),
        ),
    )));
}

/**
 * Add integration settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_add_integration_settings($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_integration_settings', array(
        'title'       => __('Integration Settings', 'aqualuxe'),
        'description' => __('Enter API keys and integration settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 90,
    ));

    // Google Maps API key
    $wp_customize->add_setting('aqualuxe_google_maps_api_key', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_google_maps_api_key', array(
        'label'       => __('Google Maps API Key', 'aqualuxe'),
        'description' => __('Enter your Google Maps API key for map functionality', 'aqualuxe'),
        'section'     => 'aqualuxe_integration_settings',
        'type'        => 'text',
    ));

    // Google Analytics ID
    $wp_customize->add_setting('aqualuxe_google_analytics_id', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_google_analytics_id', array(
        'label'       => __('Google Analytics ID', 'aqualuxe'),
        'description' => __('Enter your Google Analytics ID (e.g., UA-XXXXX-Y or G-XXXXXXXX)', 'aqualuxe'),
        'section'     => 'aqualuxe_integration_settings',
        'type'        => 'text',
    ));

    // Facebook Pixel ID
    $wp_customize->add_setting('aqualuxe_facebook_pixel_id', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_facebook_pixel_id', array(
        'label'       => __('Facebook Pixel ID', 'aqualuxe'),
        'description' => __('Enter your Facebook Pixel ID', 'aqualuxe'),
        'section'     => 'aqualuxe_integration_settings',
        'type'        => 'text',
    ));

    // Mailchimp API key
    $wp_customize->add_setting('aqualuxe_mailchimp_api_key', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_mailchimp_api_key', array(
        'label'       => __('Mailchimp API Key', 'aqualuxe'),
        'description' => __('Enter your Mailchimp API key for newsletter functionality', 'aqualuxe'),
        'section'     => 'aqualuxe_integration_settings',
        'type'        => 'text',
    ));

    // Mailchimp list ID
    $wp_customize->add_setting('aqualuxe_mailchimp_list_id', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_mailchimp_list_id', array(
        'label'       => __('Mailchimp List ID', 'aqualuxe'),
        'description' => __('Enter your Mailchimp list ID', 'aqualuxe'),
        'section'     => 'aqualuxe_integration_settings',
        'type'        => 'text',
    ));

    // reCAPTCHA site key
    $wp_customize->add_setting('aqualuxe_recaptcha_site_key', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_recaptcha_site_key', array(
        'label'       => __('reCAPTCHA Site Key', 'aqualuxe'),
        'description' => __('Enter your Google reCAPTCHA site key', 'aqualuxe'),
        'section'     => 'aqualuxe_integration_settings',
        'type'        => 'text',
    ));

    // reCAPTCHA secret key
    $wp_customize->add_setting('aqualuxe_recaptcha_secret_key', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_recaptcha_secret_key', array(
        'label'       => __('reCAPTCHA Secret Key', 'aqualuxe'),
        'description' => __('Enter your Google reCAPTCHA secret key', 'aqualuxe'),
        'section'     => 'aqualuxe_integration_settings',
        'type'        => 'text',
    ));

    // Custom CSS
    $wp_customize->add_setting('aqualuxe_custom_css', array(
        'default'           => '',
        'sanitize_callback' => 'wp_strip_all_tags',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_custom_css', array(
        'label'       => __('Custom CSS', 'aqualuxe'),
        'description' => __('Add custom CSS styles', 'aqualuxe'),
        'section'     => 'aqualuxe_integration_settings',
        'type'        => 'textarea',
    ));

    // Custom JavaScript
    $wp_customize->add_setting('aqualuxe_custom_js', array(
        'default'           => '',
        'sanitize_callback' => 'wp_strip_all_tags',
    ));

    $wp_customize->add_control('aqualuxe_custom_js', array(
        'label'       => __('Custom JavaScript', 'aqualuxe'),
        'description' => __('Add custom JavaScript code', 'aqualuxe'),
        'section'     => 'aqualuxe_integration_settings',
        'type'        => 'textarea',
    ));
}

/**
 * Add WooCommerce settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_add_woocommerce_settings($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_woocommerce_settings', array(
        'title'       => __('WooCommerce Settings', 'aqualuxe'),
        'description' => __('Customize WooCommerce settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 100,
    ));

    // Shop layout
    $wp_customize->add_setting('aqualuxe_shop_layout', array(
        'default'           => 'grid',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_shop_layout', array(
        'label'       => __('Shop Layout', 'aqualuxe'),
        'description' => __('Choose the layout for the shop page', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce_settings',
        'type'        => 'select',
        'choices'     => array(
            'grid'    => __('Grid', 'aqualuxe'),
            'list'    => __('List', 'aqualuxe'),
            'masonry' => __('Masonry', 'aqualuxe'),
        ),
    ));

    // Products per row
    $wp_customize->add_setting('aqualuxe_products_per_row', array(
        'default'           => '3',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_products_per_row', array(
        'label'       => __('Products Per Row', 'aqualuxe'),
        'description' => __('Number of products to show per row', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce_settings',
        'type'        => 'select',
        'choices'     => array(
            '2' => __('2 Products', 'aqualuxe'),
            '3' => __('3 Products', 'aqualuxe'),
            '4' => __('4 Products', 'aqualuxe'),
            '5' => __('5 Products', 'aqualuxe'),
        ),
    ));

    // Products per page
    $wp_customize->add_setting('aqualuxe_products_per_page', array(
        'default'           => '12',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_products_per_page', array(
        'label'       => __('Products Per Page', 'aqualuxe'),
        'description' => __('Number of products to show per page', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 100,
            'step' => 1,
        ),
    ));

    // Shop sidebar
    $wp_customize->add_setting('aqualuxe_shop_sidebar', array(
        'default'           => 'right',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_shop_sidebar', array(
        'label'       => __('Shop Sidebar Position', 'aqualuxe'),
        'description' => __('Choose the position of the shop sidebar', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce_settings',
        'type'        => 'select',
        'choices'     => array(
            'right' => __('Right', 'aqualuxe'),
            'left'  => __('Left', 'aqualuxe'),
            'none'  => __('No Sidebar', 'aqualuxe'),
        ),
    ));

    // Product sidebar
    $wp_customize->add_setting('aqualuxe_product_sidebar', array(
        'default'           => 'none',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_product_sidebar', array(
        'label'       => __('Product Page Sidebar', 'aqualuxe'),
        'description' => __('Choose the position of the sidebar on product pages', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce_settings',
        'type'        => 'select',
        'choices'     => array(
            'right' => __('Right', 'aqualuxe'),
            'left'  => __('Left', 'aqualuxe'),
            'none'  => __('No Sidebar', 'aqualuxe'),
        ),
    ));

    // Related products
    $wp_customize->add_setting('aqualuxe_related_products', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_related_products', array(
        'label'       => __('Show Related Products', 'aqualuxe'),
        'description' => __('Display related products on product pages', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce_settings',
        'type'        => 'checkbox',
    ));

    // Upsells
    $wp_customize->add_setting('aqualuxe_product_upsells', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_product_upsells', array(
        'label'       => __('Show Upsell Products', 'aqualuxe'),
        'description' => __('Display upsell products on product pages', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce_settings',
        'type'        => 'checkbox',
    ));

    // Cross-sells
    $wp_customize->add_setting('aqualuxe_cart_cross_sells', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_cart_cross_sells', array(
        'label'       => __('Show Cross-Sell Products', 'aqualuxe'),
        'description' => __('Display cross-sell products on the cart page', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce_settings',
        'type'        => 'checkbox',
    ));

    // Product gallery zoom
    $wp_customize->add_setting('aqualuxe_product_zoom', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_product_zoom', array(
        'label'       => __('Enable Product Image Zoom', 'aqualuxe'),
        'description' => __('Allow zooming of product images', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce_settings',
        'type'        => 'checkbox',
    ));

    // Product gallery lightbox
    $wp_customize->add_setting('aqualuxe_product_lightbox', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_product_lightbox', array(
        'label'       => __('Enable Product Image Lightbox', 'aqualuxe'),
        'description' => __('Allow lightbox view of product images', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce_settings',
        'type'        => 'checkbox',
    ));

    // Product gallery slider
    $wp_customize->add_setting('aqualuxe_product_slider', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_product_slider', array(
        'label'       => __('Enable Product Image Slider', 'aqualuxe'),
        'description' => __('Allow sliding through product images', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce_settings',
        'type'        => 'checkbox',
    ));

    // Quick view
    $wp_customize->add_setting('aqualuxe_quick_view', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_quick_view', array(
        'label'       => __('Enable Quick View', 'aqualuxe'),
        'description' => __('Allow quick view of products from the shop page', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce_settings',
        'type'        => 'checkbox',
    ));

    // Wishlist
    $wp_customize->add_setting('aqualuxe_wishlist', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_wishlist', array(
        'label'       => __('Enable Wishlist', 'aqualuxe'),
        'description' => __('Allow adding products to a wishlist', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce_settings',
        'type'        => 'checkbox',
    ));

    // Sale badge text
    $wp_customize->add_setting('aqualuxe_sale_badge_text', array(
        'default'           => __('Sale!', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_sale_badge_text', array(
        'label'       => __('Sale Badge Text', 'aqualuxe'),
        'description' => __('Text to display on the sale badge', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce_settings',
        'type'        => 'text',
    ));

    // Sale badge color
    $wp_customize->add_setting('aqualuxe_sale_badge_color', array(
        'default'           => '#e53935',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_sale_badge_color', array(
        'label'       => __('Sale Badge Color', 'aqualuxe'),
        'description' => __('Color of the sale badge', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce_settings',
    )));

    // Checkout layout
    $wp_customize->add_setting('aqualuxe_checkout_layout', array(
        'default'           => 'two-column',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_checkout_layout', array(
        'label'       => __('Checkout Layout', 'aqualuxe'),
        'description' => __('Choose the layout for the checkout page', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce_settings',
        'type'        => 'select',
        'choices'     => array(
            'one-column'  => __('One Column', 'aqualuxe'),
            'two-column'  => __('Two Column', 'aqualuxe'),
            'multi-step'  => __('Multi-Step', 'aqualuxe'),
        ),
    ));
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
    wp_enqueue_script('aqualuxe-customizer', aqualuxe_asset('js/customizer.js'), array('customize-preview'), AQUALUXE_VERSION, true);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');

/**
 * Sanitize checkbox
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function aqualuxe_sanitize_checkbox($checked) {
    return (isset($checked) && true === $checked) ? true : false;
}

/**
 * Sanitize select
 *
 * @param string $input The input from the setting.
 * @param WP_Customize_Setting $setting The setting object.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_select($input, $setting) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    // Return input if valid or return default option.
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize float
 *
 * @param float $input The input from the setting.
 * @return float The sanitized input.
 */
function aqualuxe_sanitize_float($input) {
    return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}

/**
 * Sanitize multi-select
 *
 * @param array $input The input from the setting.
 * @return array The sanitized input.
 */
function aqualuxe_sanitize_multi_select($input) {
    if (!is_array($input)) {
        return array();
    }
    
    $valid_input = array();
    foreach ($input as $value) {
        $valid_input[] = sanitize_text_field($value);
    }
    
    return $valid_input;
}

/**
 * Multi-checkbox control class
 */
if (class_exists('WP_Customize_Control')) {
    class AquaLuxe_Customize_Multicheck_Control extends WP_Customize_Control {
        /**
         * The type of customize control being rendered.
         *
         * @var string
         */
        public $type = 'multicheck';

        /**
         * Render the control's content.
         */
        public function render_content() {
            if (empty($this->choices)) {
                return;
            }
            
            if (!empty($this->label)) {
                echo '<span class="customize-control-title">' . esc_html($this->label) . '</span>';
            }
            
            if (!empty($this->description)) {
                echo '<span class="description customize-control-description">' . esc_html($this->description) . '</span>';
            }
            
            $multi_values = !is_array($this->value()) ? explode(',', $this->value()) : $this->value();
            
            echo '<ul>';
            foreach ($this->choices as $value => $label) {
                echo '<li>';
                echo '<label>';
                echo '<input type="checkbox" value="' . esc_attr($value) . '" ' . checked(in_array($value, $multi_values), true, false) . ' />';
                echo esc_html($label);
                echo '</label>';
                echo '</li>';
            }
            echo '</ul>';
            
            echo '<input type="hidden" ' . $this->get_link() . ' value="' . esc_attr(implode(',', $multi_values)) . '" />';
            
            echo '<script type="text/javascript">
                jQuery(document).ready(function($) {
                    $("input[type=\'checkbox\']").on("change", function() {
                        var values = [];
                        $(this).closest("ul").find("input[type=\'checkbox\']:checked").each(function() {
                            values.push($(this).val());
                        });
                        $(this).closest("li").find("input[type=\'hidden\']").val(values.join(",")).trigger("change");
                    });
                });
            </script>';
        }
    }
}