<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

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

    // Add custom sections, settings, and controls
    aqualuxe_customize_general($wp_customize);
    aqualuxe_customize_header($wp_customize);
    aqualuxe_customize_footer($wp_customize);
    aqualuxe_customize_colors($wp_customize);
    aqualuxe_customize_typography($wp_customize);
    aqualuxe_customize_layout($wp_customize);
    aqualuxe_customize_blog($wp_customize);
    aqualuxe_customize_social($wp_customize);
    aqualuxe_customize_contact($wp_customize);
    aqualuxe_customize_woocommerce($wp_customize);
    aqualuxe_customize_performance($wp_customize);
    aqualuxe_customize_custom_css($wp_customize);
}
add_action('customize_register', 'aqualuxe_customize_register');

/**
 * General settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_general($wp_customize) {
    // General section
    $wp_customize->add_section('aqualuxe_general', array(
        'title'    => __('General Settings', 'aqualuxe'),
        'priority' => 30,
    ));

    // Logo settings
    $wp_customize->add_setting('aqualuxe_options[dark_logo]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'dark_logo', array(
        'label'     => __('Dark Mode Logo', 'aqualuxe'),
        'section'   => 'title_tagline',
        'settings'  => 'aqualuxe_options[dark_logo]',
        'priority'  => 9,
    )));

    // Favicon
    $wp_customize->add_setting('aqualuxe_options[favicon]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'favicon', array(
        'label'     => __('Favicon', 'aqualuxe'),
        'section'   => 'title_tagline',
        'settings'  => 'aqualuxe_options[favicon]',
        'priority'  => 11,
    )));

    // Dark mode
    $wp_customize->add_setting('aqualuxe_options[enable_dark_mode]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_dark_mode', array(
        'label'    => __('Enable Dark Mode Toggle', 'aqualuxe'),
        'section'  => 'aqualuxe_general',
        'settings' => 'aqualuxe_options[enable_dark_mode]',
        'type'     => 'checkbox',
    ));

    // Default dark mode
    $wp_customize->add_setting('aqualuxe_options[default_dark_mode]', array(
        'default'           => false,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('default_dark_mode', array(
        'label'    => __('Default to Dark Mode', 'aqualuxe'),
        'section'  => 'aqualuxe_general',
        'settings' => 'aqualuxe_options[default_dark_mode]',
        'type'     => 'checkbox',
    ));

    // Back to top button
    $wp_customize->add_setting('aqualuxe_options[enable_back_to_top]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_back_to_top', array(
        'label'    => __('Enable Back to Top Button', 'aqualuxe'),
        'section'  => 'aqualuxe_general',
        'settings' => 'aqualuxe_options[enable_back_to_top]',
        'type'     => 'checkbox',
    ));

    // Page loader
    $wp_customize->add_setting('aqualuxe_options[enable_page_loader]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_page_loader', array(
        'label'    => __('Enable Page Loader', 'aqualuxe'),
        'section'  => 'aqualuxe_general',
        'settings' => 'aqualuxe_options[enable_page_loader]',
        'type'     => 'checkbox',
    ));

    // Breadcrumbs
    $wp_customize->add_setting('aqualuxe_options[enable_breadcrumbs]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_breadcrumbs', array(
        'label'    => __('Enable Breadcrumbs', 'aqualuxe'),
        'section'  => 'aqualuxe_general',
        'settings' => 'aqualuxe_options[enable_breadcrumbs]',
        'type'     => 'checkbox',
    ));

    // Smooth scroll
    $wp_customize->add_setting('aqualuxe_options[enable_smooth_scroll]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_smooth_scroll', array(
        'label'    => __('Enable Smooth Scroll', 'aqualuxe'),
        'section'  => 'aqualuxe_general',
        'settings' => 'aqualuxe_options[enable_smooth_scroll]',
        'type'     => 'checkbox',
    ));
}

/**
 * Header settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_header($wp_customize) {
    // Header section
    $wp_customize->add_section('aqualuxe_header', array(
        'title'    => __('Header Settings', 'aqualuxe'),
        'priority' => 40,
    ));

    // Header layout
    $wp_customize->add_setting('aqualuxe_options[header_layout]', array(
        'default'           => 'default',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('header_layout', array(
        'label'    => __('Header Layout', 'aqualuxe'),
        'section'  => 'aqualuxe_header',
        'settings' => 'aqualuxe_options[header_layout]',
        'type'     => 'select',
        'choices'  => array(
            'default'      => __('Default', 'aqualuxe'),
            'centered'     => __('Centered', 'aqualuxe'),
            'transparent'  => __('Transparent', 'aqualuxe'),
            'minimal'      => __('Minimal', 'aqualuxe'),
            'split'        => __('Split', 'aqualuxe'),
        ),
    ));

    // Sticky header
    $wp_customize->add_setting('aqualuxe_options[sticky_header]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('sticky_header', array(
        'label'    => __('Enable Sticky Header', 'aqualuxe'),
        'section'  => 'aqualuxe_header',
        'settings' => 'aqualuxe_options[sticky_header]',
        'type'     => 'checkbox',
    ));

    // Top bar
    $wp_customize->add_setting('aqualuxe_options[enable_top_bar]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_top_bar', array(
        'label'    => __('Enable Top Bar', 'aqualuxe'),
        'section'  => 'aqualuxe_header',
        'settings' => 'aqualuxe_options[enable_top_bar]',
        'type'     => 'checkbox',
    ));

    // Bottom bar
    $wp_customize->add_setting('aqualuxe_options[enable_bottom_bar]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_bottom_bar', array(
        'label'    => __('Enable Bottom Bar', 'aqualuxe'),
        'section'  => 'aqualuxe_header',
        'settings' => 'aqualuxe_options[enable_bottom_bar]',
        'type'     => 'checkbox',
    ));

    // Search in header
    $wp_customize->add_setting('aqualuxe_options[header_search]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('header_search', array(
        'label'    => __('Show Search in Header', 'aqualuxe'),
        'section'  => 'aqualuxe_header',
        'settings' => 'aqualuxe_options[header_search]',
        'type'     => 'checkbox',
    ));

    // Cart in header
    $wp_customize->add_setting('aqualuxe_options[header_cart]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('header_cart', array(
        'label'    => __('Show Cart in Header', 'aqualuxe'),
        'section'  => 'aqualuxe_header',
        'settings' => 'aqualuxe_options[header_cart]',
        'type'     => 'checkbox',
    ));

    // Account in header
    $wp_customize->add_setting('aqualuxe_options[header_account]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('header_account', array(
        'label'    => __('Show Account in Header', 'aqualuxe'),
        'section'  => 'aqualuxe_header',
        'settings' => 'aqualuxe_options[header_account]',
        'type'     => 'checkbox',
    ));

    // Wishlist in header
    $wp_customize->add_setting('aqualuxe_options[header_wishlist]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('header_wishlist', array(
        'label'    => __('Show Wishlist in Header', 'aqualuxe'),
        'section'  => 'aqualuxe_header',
        'settings' => 'aqualuxe_options[header_wishlist]',
        'type'     => 'checkbox',
    ));

    // Social icons in header
    $wp_customize->add_setting('aqualuxe_options[header_social]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('header_social', array(
        'label'    => __('Show Social Icons in Header', 'aqualuxe'),
        'section'  => 'aqualuxe_header',
        'settings' => 'aqualuxe_options[header_social]',
        'type'     => 'checkbox',
    ));

    // Contact info in header
    $wp_customize->add_setting('aqualuxe_options[header_contact]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('header_contact', array(
        'label'    => __('Show Contact Info in Header', 'aqualuxe'),
        'section'  => 'aqualuxe_header',
        'settings' => 'aqualuxe_options[header_contact]',
        'type'     => 'checkbox',
    ));
}

/**
 * Footer settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_footer($wp_customize) {
    // Footer section
    $wp_customize->add_section('aqualuxe_footer', array(
        'title'    => __('Footer Settings', 'aqualuxe'),
        'priority' => 50,
    ));

    // Footer layout
    $wp_customize->add_setting('aqualuxe_options[footer_layout]', array(
        'default'           => 'default',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('footer_layout', array(
        'label'    => __('Footer Layout', 'aqualuxe'),
        'section'  => 'aqualuxe_footer',
        'settings' => 'aqualuxe_options[footer_layout]',
        'type'     => 'select',
        'choices'  => array(
            'default'      => __('Default (4 Columns)', 'aqualuxe'),
            'three-column' => __('3 Columns', 'aqualuxe'),
            'two-column'   => __('2 Columns', 'aqualuxe'),
            'one-column'   => __('1 Column', 'aqualuxe'),
            'custom'       => __('Custom', 'aqualuxe'),
        ),
    ));

    // Footer widgets
    $wp_customize->add_setting('aqualuxe_options[footer_widgets]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('footer_widgets', array(
        'label'    => __('Enable Footer Widgets', 'aqualuxe'),
        'section'  => 'aqualuxe_footer',
        'settings' => 'aqualuxe_options[footer_widgets]',
        'type'     => 'checkbox',
    ));

    // Footer logo
    $wp_customize->add_setting('aqualuxe_options[footer_logo]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'footer_logo', array(
        'label'     => __('Footer Logo', 'aqualuxe'),
        'section'   => 'aqualuxe_footer',
        'settings'  => 'aqualuxe_options[footer_logo]',
    )));

    // Copyright text
    $wp_customize->add_setting('aqualuxe_options[copyright_text]', array(
        'default'           => '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. ' . __('All rights reserved.', 'aqualuxe'),
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('copyright_text', array(
        'label'    => __('Copyright Text', 'aqualuxe'),
        'section'  => 'aqualuxe_footer',
        'settings' => 'aqualuxe_options[copyright_text]',
        'type'     => 'textarea',
    ));

    // Footer menu
    $wp_customize->add_setting('aqualuxe_options[footer_menu]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('footer_menu', array(
        'label'    => __('Show Footer Menu', 'aqualuxe'),
        'section'  => 'aqualuxe_footer',
        'settings' => 'aqualuxe_options[footer_menu]',
        'type'     => 'checkbox',
    ));

    // Social icons in footer
    $wp_customize->add_setting('aqualuxe_options[footer_social]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('footer_social', array(
        'label'    => __('Show Social Icons in Footer', 'aqualuxe'),
        'section'  => 'aqualuxe_footer',
        'settings' => 'aqualuxe_options[footer_social]',
        'type'     => 'checkbox',
    ));

    // Payment icons
    $wp_customize->add_setting('aqualuxe_options[footer_payment]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('footer_payment', array(
        'label'    => __('Show Payment Icons', 'aqualuxe'),
        'section'  => 'aqualuxe_footer',
        'settings' => 'aqualuxe_options[footer_payment]',
        'type'     => 'checkbox',
    ));

    // Newsletter
    $wp_customize->add_setting('aqualuxe_options[enable_newsletter]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_newsletter', array(
        'label'    => __('Enable Newsletter Form', 'aqualuxe'),
        'section'  => 'aqualuxe_footer',
        'settings' => 'aqualuxe_options[enable_newsletter]',
        'type'     => 'checkbox',
    ));

    // Newsletter title
    $wp_customize->add_setting('aqualuxe_options[newsletter_title]', array(
        'default'           => __('Subscribe to Our Newsletter', 'aqualuxe'),
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('newsletter_title', array(
        'label'    => __('Newsletter Title', 'aqualuxe'),
        'section'  => 'aqualuxe_footer',
        'settings' => 'aqualuxe_options[newsletter_title]',
        'type'     => 'text',
    ));

    // Newsletter description
    $wp_customize->add_setting('aqualuxe_options[newsletter_description]', array(
        'default'           => __('Stay updated with our latest news and offers.', 'aqualuxe'),
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('newsletter_description', array(
        'label'    => __('Newsletter Description', 'aqualuxe'),
        'section'  => 'aqualuxe_footer',
        'settings' => 'aqualuxe_options[newsletter_description]',
        'type'     => 'text',
    ));

    // Newsletter form action
    $wp_customize->add_setting('aqualuxe_options[newsletter_form_action]', array(
        'default'           => '#',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('newsletter_form_action', array(
        'label'    => __('Newsletter Form Action URL', 'aqualuxe'),
        'section'  => 'aqualuxe_footer',
        'settings' => 'aqualuxe_options[newsletter_form_action]',
        'type'     => 'url',
    ));

    // Newsletter button text
    $wp_customize->add_setting('aqualuxe_options[newsletter_button_text]', array(
        'default'           => __('Subscribe', 'aqualuxe'),
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('newsletter_button_text', array(
        'label'    => __('Newsletter Button Text', 'aqualuxe'),
        'section'  => 'aqualuxe_footer',
        'settings' => 'aqualuxe_options[newsletter_button_text]',
        'type'     => 'text',
    ));
}

/**
 * Colors settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_colors($wp_customize) {
    // Primary color
    $wp_customize->add_setting('aqualuxe_options[primary_color]', array(
        'default'           => '#0077B6',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', array(
        'label'    => __('Primary Color', 'aqualuxe'),
        'section'  => 'colors',
        'settings' => 'aqualuxe_options[primary_color]',
    )));

    // Secondary color
    $wp_customize->add_setting('aqualuxe_options[secondary_color]', array(
        'default'           => '#CAF0F8',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_color', array(
        'label'    => __('Secondary Color', 'aqualuxe'),
        'section'  => 'colors',
        'settings' => 'aqualuxe_options[secondary_color]',
    )));

    // Accent color
    $wp_customize->add_setting('aqualuxe_options[accent_color]', array(
        'default'           => '#FFD700',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'accent_color', array(
        'label'    => __('Accent Color', 'aqualuxe'),
        'section'  => 'colors',
        'settings' => 'aqualuxe_options[accent_color]',
    )));

    // Text color
    $wp_customize->add_setting('aqualuxe_options[text_color]', array(
        'default'           => '#333333',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'text_color', array(
        'label'    => __('Text Color', 'aqualuxe'),
        'section'  => 'colors',
        'settings' => 'aqualuxe_options[text_color]',
    )));

    // Heading color
    $wp_customize->add_setting('aqualuxe_options[heading_color]', array(
        'default'           => '#222222',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'heading_color', array(
        'label'    => __('Heading Color', 'aqualuxe'),
        'section'  => 'colors',
        'settings' => 'aqualuxe_options[heading_color]',
    )));

    // Link color
    $wp_customize->add_setting('aqualuxe_options[link_color]', array(
        'default'           => '#0077B6',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'link_color', array(
        'label'    => __('Link Color', 'aqualuxe'),
        'section'  => 'colors',
        'settings' => 'aqualuxe_options[link_color]',
    )));

    // Link hover color
    $wp_customize->add_setting('aqualuxe_options[link_hover_color]', array(
        'default'           => '#03045E',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'link_hover_color', array(
        'label'    => __('Link Hover Color', 'aqualuxe'),
        'section'  => 'colors',
        'settings' => 'aqualuxe_options[link_hover_color]',
    )));

    // Button color
    $wp_customize->add_setting('aqualuxe_options[button_color]', array(
        'default'           => '#0077B6',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_color', array(
        'label'    => __('Button Color', 'aqualuxe'),
        'section'  => 'colors',
        'settings' => 'aqualuxe_options[button_color]',
    )));

    // Button text color
    $wp_customize->add_setting('aqualuxe_options[button_text_color]', array(
        'default'           => '#FFFFFF',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_text_color', array(
        'label'    => __('Button Text Color', 'aqualuxe'),
        'section'  => 'colors',
        'settings' => 'aqualuxe_options[button_text_color]',
    )));

    // Button hover color
    $wp_customize->add_setting('aqualuxe_options[button_hover_color]', array(
        'default'           => '#03045E',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_hover_color', array(
        'label'    => __('Button Hover Color', 'aqualuxe'),
        'section'  => 'colors',
        'settings' => 'aqualuxe_options[button_hover_color]',
    )));

    // Button hover text color
    $wp_customize->add_setting('aqualuxe_options[button_hover_text_color]', array(
        'default'           => '#FFFFFF',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_hover_text_color', array(
        'label'    => __('Button Hover Text Color', 'aqualuxe'),
        'section'  => 'colors',
        'settings' => 'aqualuxe_options[button_hover_text_color]',
    )));

    // Header background color
    $wp_customize->add_setting('aqualuxe_options[header_bg_color]', array(
        'default'           => '#FFFFFF',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_bg_color', array(
        'label'    => __('Header Background Color', 'aqualuxe'),
        'section'  => 'colors',
        'settings' => 'aqualuxe_options[header_bg_color]',
    )));

    // Footer background color
    $wp_customize->add_setting('aqualuxe_options[footer_bg_color]', array(
        'default'           => '#F8F9FA',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_bg_color', array(
        'label'    => __('Footer Background Color', 'aqualuxe'),
        'section'  => 'colors',
        'settings' => 'aqualuxe_options[footer_bg_color]',
    )));

    // Dark mode colors section
    $wp_customize->add_section('aqualuxe_dark_mode_colors', array(
        'title'    => __('Dark Mode Colors', 'aqualuxe'),
        'priority' => 31,
    ));

    // Dark mode background color
    $wp_customize->add_setting('aqualuxe_options[dark_bg_color]', array(
        'default'           => '#121212',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'dark_bg_color', array(
        'label'    => __('Background Color', 'aqualuxe'),
        'section'  => 'aqualuxe_dark_mode_colors',
        'settings' => 'aqualuxe_options[dark_bg_color]',
    )));

    // Dark mode text color
    $wp_customize->add_setting('aqualuxe_options[dark_text_color]', array(
        'default'           => '#E0E0E0',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'dark_text_color', array(
        'label'    => __('Text Color', 'aqualuxe'),
        'section'  => 'aqualuxe_dark_mode_colors',
        'settings' => 'aqualuxe_options[dark_text_color]',
    )));

    // Dark mode heading color
    $wp_customize->add_setting('aqualuxe_options[dark_heading_color]', array(
        'default'           => '#FFFFFF',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'dark_heading_color', array(
        'label'    => __('Heading Color', 'aqualuxe'),
        'section'  => 'aqualuxe_dark_mode_colors',
        'settings' => 'aqualuxe_options[dark_heading_color]',
    )));

    // Dark mode link color
    $wp_customize->add_setting('aqualuxe_options[dark_link_color]', array(
        'default'           => '#90E0EF',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'dark_link_color', array(
        'label'    => __('Link Color', 'aqualuxe'),
        'section'  => 'aqualuxe_dark_mode_colors',
        'settings' => 'aqualuxe_options[dark_link_color]',
    )));

    // Dark mode link hover color
    $wp_customize->add_setting('aqualuxe_options[dark_link_hover_color]', array(
        'default'           => '#CAF0F8',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'dark_link_hover_color', array(
        'label'    => __('Link Hover Color', 'aqualuxe'),
        'section'  => 'aqualuxe_dark_mode_colors',
        'settings' => 'aqualuxe_options[dark_link_hover_color]',
    )));

    // Dark mode header background color
    $wp_customize->add_setting('aqualuxe_options[dark_header_bg_color]', array(
        'default'           => '#1A1A1A',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'dark_header_bg_color', array(
        'label'    => __('Header Background Color', 'aqualuxe'),
        'section'  => 'aqualuxe_dark_mode_colors',
        'settings' => 'aqualuxe_options[dark_header_bg_color]',
    )));

    // Dark mode footer background color
    $wp_customize->add_setting('aqualuxe_options[dark_footer_bg_color]', array(
        'default'           => '#1A1A1A',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'dark_footer_bg_color', array(
        'label'    => __('Footer Background Color', 'aqualuxe'),
        'section'  => 'aqualuxe_dark_mode_colors',
        'settings' => 'aqualuxe_options[dark_footer_bg_color]',
    )));
}

/**
 * Typography settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_typography($wp_customize) {
    // Typography section
    $wp_customize->add_section('aqualuxe_typography', array(
        'title'    => __('Typography', 'aqualuxe'),
        'priority' => 60,
    ));

    // Body font family
    $wp_customize->add_setting('aqualuxe_options[body_font_family]', array(
        'default'           => 'Montserrat, sans-serif',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('body_font_family', array(
        'label'    => __('Body Font Family', 'aqualuxe'),
        'section'  => 'aqualuxe_typography',
        'settings' => 'aqualuxe_options[body_font_family]',
        'type'     => 'select',
        'choices'  => array(
            'Montserrat, sans-serif'       => 'Montserrat',
            'Roboto, sans-serif'           => 'Roboto',
            'Open Sans, sans-serif'        => 'Open Sans',
            'Lato, sans-serif'             => 'Lato',
            'Poppins, sans-serif'          => 'Poppins',
            'Source Sans Pro, sans-serif'  => 'Source Sans Pro',
            'Raleway, sans-serif'          => 'Raleway',
            'PT Sans, sans-serif'          => 'PT Sans',
            'Nunito, sans-serif'           => 'Nunito',
            'Nunito Sans, sans-serif'      => 'Nunito Sans',
            'Merriweather Sans, sans-serif' => 'Merriweather Sans',
            'Work Sans, sans-serif'        => 'Work Sans',
        ),
    ));

    // Heading font family
    $wp_customize->add_setting('aqualuxe_options[heading_font_family]', array(
        'default'           => 'Playfair Display, serif',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('heading_font_family', array(
        'label'    => __('Heading Font Family', 'aqualuxe'),
        'section'  => 'aqualuxe_typography',
        'settings' => 'aqualuxe_options[heading_font_family]',
        'type'     => 'select',
        'choices'  => array(
            'Playfair Display, serif'      => 'Playfair Display',
            'Merriweather, serif'          => 'Merriweather',
            'Lora, serif'                  => 'Lora',
            'Roboto Slab, serif'           => 'Roboto Slab',
            'PT Serif, serif'              => 'PT Serif',
            'Noto Serif, serif'            => 'Noto Serif',
            'Cormorant Garamond, serif'    => 'Cormorant Garamond',
            'Libre Baskerville, serif'     => 'Libre Baskerville',
            'Crimson Text, serif'          => 'Crimson Text',
            'Montserrat, sans-serif'       => 'Montserrat',
            'Roboto, sans-serif'           => 'Roboto',
            'Open Sans, sans-serif'        => 'Open Sans',
        ),
    ));

    // Body font size
    $wp_customize->add_setting('aqualuxe_options[body_font_size]', array(
        'default'           => '16px',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('body_font_size', array(
        'label'    => __('Body Font Size', 'aqualuxe'),
        'section'  => 'aqualuxe_typography',
        'settings' => 'aqualuxe_options[body_font_size]',
        'type'     => 'text',
    ));

    // Body line height
    $wp_customize->add_setting('aqualuxe_options[body_line_height]', array(
        'default'           => '1.6',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('body_line_height', array(
        'label'    => __('Body Line Height', 'aqualuxe'),
        'section'  => 'aqualuxe_typography',
        'settings' => 'aqualuxe_options[body_line_height]',
        'type'     => 'text',
    ));

    // Heading line height
    $wp_customize->add_setting('aqualuxe_options[heading_line_height]', array(
        'default'           => '1.2',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('heading_line_height', array(
        'label'    => __('Heading Line Height', 'aqualuxe'),
        'section'  => 'aqualuxe_typography',
        'settings' => 'aqualuxe_options[heading_line_height]',
        'type'     => 'text',
    ));

    // H1 font size
    $wp_customize->add_setting('aqualuxe_options[h1_font_size]', array(
        'default'           => '2.5rem',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('h1_font_size', array(
        'label'    => __('H1 Font Size', 'aqualuxe'),
        'section'  => 'aqualuxe_typography',
        'settings' => 'aqualuxe_options[h1_font_size]',
        'type'     => 'text',
    ));

    // H2 font size
    $wp_customize->add_setting('aqualuxe_options[h2_font_size]', array(
        'default'           => '2rem',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('h2_font_size', array(
        'label'    => __('H2 Font Size', 'aqualuxe'),
        'section'  => 'aqualuxe_typography',
        'settings' => 'aqualuxe_options[h2_font_size]',
        'type'     => 'text',
    ));

    // H3 font size
    $wp_customize->add_setting('aqualuxe_options[h3_font_size]', array(
        'default'           => '1.75rem',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('h3_font_size', array(
        'label'    => __('H3 Font Size', 'aqualuxe'),
        'section'  => 'aqualuxe_typography',
        'settings' => 'aqualuxe_options[h3_font_size]',
        'type'     => 'text',
    ));

    // H4 font size
    $wp_customize->add_setting('aqualuxe_options[h4_font_size]', array(
        'default'           => '1.5rem',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('h4_font_size', array(
        'label'    => __('H4 Font Size', 'aqualuxe'),
        'section'  => 'aqualuxe_typography',
        'settings' => 'aqualuxe_options[h4_font_size]',
        'type'     => 'text',
    ));

    // H5 font size
    $wp_customize->add_setting('aqualuxe_options[h5_font_size]', array(
        'default'           => '1.25rem',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('h5_font_size', array(
        'label'    => __('H5 Font Size', 'aqualuxe'),
        'section'  => 'aqualuxe_typography',
        'settings' => 'aqualuxe_options[h5_font_size]',
        'type'     => 'text',
    ));

    // H6 font size
    $wp_customize->add_setting('aqualuxe_options[h6_font_size]', array(
        'default'           => '1rem',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('h6_font_size', array(
        'label'    => __('H6 Font Size', 'aqualuxe'),
        'section'  => 'aqualuxe_typography',
        'settings' => 'aqualuxe_options[h6_font_size]',
        'type'     => 'text',
    ));

    // Font weight
    $wp_customize->add_setting('aqualuxe_options[body_font_weight]', array(
        'default'           => '400',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('body_font_weight', array(
        'label'    => __('Body Font Weight', 'aqualuxe'),
        'section'  => 'aqualuxe_typography',
        'settings' => 'aqualuxe_options[body_font_weight]',
        'type'     => 'select',
        'choices'  => array(
            '300' => __('Light (300)', 'aqualuxe'),
            '400' => __('Regular (400)', 'aqualuxe'),
            '500' => __('Medium (500)', 'aqualuxe'),
            '600' => __('Semi-Bold (600)', 'aqualuxe'),
            '700' => __('Bold (700)', 'aqualuxe'),
        ),
    ));

    // Heading font weight
    $wp_customize->add_setting('aqualuxe_options[heading_font_weight]', array(
        'default'           => '700',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('heading_font_weight', array(
        'label'    => __('Heading Font Weight', 'aqualuxe'),
        'section'  => 'aqualuxe_typography',
        'settings' => 'aqualuxe_options[heading_font_weight]',
        'type'     => 'select',
        'choices'  => array(
            '300' => __('Light (300)', 'aqualuxe'),
            '400' => __('Regular (400)', 'aqualuxe'),
            '500' => __('Medium (500)', 'aqualuxe'),
            '600' => __('Semi-Bold (600)', 'aqualuxe'),
            '700' => __('Bold (700)', 'aqualuxe'),
            '800' => __('Extra-Bold (800)', 'aqualuxe'),
            '900' => __('Black (900)', 'aqualuxe'),
        ),
    ));

    // Google Fonts
    $wp_customize->add_setting('aqualuxe_options[load_google_fonts]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('load_google_fonts', array(
        'label'    => __('Load Google Fonts', 'aqualuxe'),
        'section'  => 'aqualuxe_typography',
        'settings' => 'aqualuxe_options[load_google_fonts]',
        'type'     => 'checkbox',
    ));
}

/**
 * Layout settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_layout($wp_customize) {
    // Layout section
    $wp_customize->add_section('aqualuxe_layout', array(
        'title'    => __('Layout Settings', 'aqualuxe'),
        'priority' => 70,
    ));

    // Container width
    $wp_customize->add_setting('aqualuxe_options[container_width]', array(
        'default'           => '1200px',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('container_width', array(
        'label'    => __('Container Width', 'aqualuxe'),
        'section'  => 'aqualuxe_layout',
        'settings' => 'aqualuxe_options[container_width]',
        'type'     => 'text',
    ));

    // Content layout
    $wp_customize->add_setting('aqualuxe_options[content_layout]', array(
        'default'           => 'right-sidebar',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('content_layout', array(
        'label'    => __('Content Layout', 'aqualuxe'),
        'section'  => 'aqualuxe_layout',
        'settings' => 'aqualuxe_options[content_layout]',
        'type'     => 'select',
        'choices'  => array(
            'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
            'left-sidebar'  => __('Left Sidebar', 'aqualuxe'),
            'no-sidebar'    => __('No Sidebar', 'aqualuxe'),
        ),
    ));

    // Blog layout
    $wp_customize->add_setting('aqualuxe_options[blog_layout]', array(
        'default'           => 'grid',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('blog_layout', array(
        'label'    => __('Blog Layout', 'aqualuxe'),
        'section'  => 'aqualuxe_layout',
        'settings' => 'aqualuxe_options[blog_layout]',
        'type'     => 'select',
        'choices'  => array(
            'grid'    => __('Grid', 'aqualuxe'),
            'list'    => __('List', 'aqualuxe'),
            'masonry' => __('Masonry', 'aqualuxe'),
            'classic' => __('Classic', 'aqualuxe'),
        ),
    ));

    // Blog columns
    $wp_customize->add_setting('aqualuxe_options[blog_columns]', array(
        'default'           => '3',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('blog_columns', array(
        'label'    => __('Blog Columns', 'aqualuxe'),
        'section'  => 'aqualuxe_layout',
        'settings' => 'aqualuxe_options[blog_columns]',
        'type'     => 'select',
        'choices'  => array(
            '2' => __('2 Columns', 'aqualuxe'),
            '3' => __('3 Columns', 'aqualuxe'),
            '4' => __('4 Columns', 'aqualuxe'),
        ),
    ));

    // Shop layout
    $wp_customize->add_setting('aqualuxe_options[shop_layout]', array(
        'default'           => 'grid',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('shop_layout', array(
        'label'    => __('Shop Layout', 'aqualuxe'),
        'section'  => 'aqualuxe_layout',
        'settings' => 'aqualuxe_options[shop_layout]',
        'type'     => 'select',
        'choices'  => array(
            'grid'    => __('Grid', 'aqualuxe'),
            'list'    => __('List', 'aqualuxe'),
            'masonry' => __('Masonry', 'aqualuxe'),
        ),
    ));

    // Shop columns
    $wp_customize->add_setting('aqualuxe_options[shop_columns]', array(
        'default'           => '4',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('shop_columns', array(
        'label'    => __('Shop Columns', 'aqualuxe'),
        'section'  => 'aqualuxe_layout',
        'settings' => 'aqualuxe_options[shop_columns]',
        'type'     => 'select',
        'choices'  => array(
            '2' => __('2 Columns', 'aqualuxe'),
            '3' => __('3 Columns', 'aqualuxe'),
            '4' => __('4 Columns', 'aqualuxe'),
            '5' => __('5 Columns', 'aqualuxe'),
        ),
    ));

    // Products per page
    $wp_customize->add_setting('aqualuxe_options[products_per_page]', array(
        'default'           => '12',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('products_per_page', array(
        'label'    => __('Products Per Page', 'aqualuxe'),
        'section'  => 'aqualuxe_layout',
        'settings' => 'aqualuxe_options[products_per_page]',
        'type'     => 'number',
        'input_attrs' => array(
            'min'  => 4,
            'max'  => 48,
            'step' => 4,
        ),
    ));

    // Related products columns
    $wp_customize->add_setting('aqualuxe_options[related_products_columns]', array(
        'default'           => '4',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('related_products_columns', array(
        'label'    => __('Related Products Columns', 'aqualuxe'),
        'section'  => 'aqualuxe_layout',
        'settings' => 'aqualuxe_options[related_products_columns]',
        'type'     => 'select',
        'choices'  => array(
            '2' => __('2 Columns', 'aqualuxe'),
            '3' => __('3 Columns', 'aqualuxe'),
            '4' => __('4 Columns', 'aqualuxe'),
            '5' => __('5 Columns', 'aqualuxe'),
        ),
    ));
}

/**
 * Blog settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_blog($wp_customize) {
    // Blog section
    $wp_customize->add_section('aqualuxe_blog', array(
        'title'    => __('Blog Settings', 'aqualuxe'),
        'priority' => 80,
    ));

    // Blog sidebar
    $wp_customize->add_setting('aqualuxe_options[blog_sidebar]', array(
        'default'           => 'right-sidebar',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('blog_sidebar', array(
        'label'    => __('Blog Sidebar', 'aqualuxe'),
        'section'  => 'aqualuxe_blog',
        'settings' => 'aqualuxe_options[blog_sidebar]',
        'type'     => 'select',
        'choices'  => array(
            'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
            'left-sidebar'  => __('Left Sidebar', 'aqualuxe'),
            'no-sidebar'    => __('No Sidebar', 'aqualuxe'),
        ),
    ));

    // Single post sidebar
    $wp_customize->add_setting('aqualuxe_options[single_sidebar]', array(
        'default'           => 'right-sidebar',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('single_sidebar', array(
        'label'    => __('Single Post Sidebar', 'aqualuxe'),
        'section'  => 'aqualuxe_blog',
        'settings' => 'aqualuxe_options[single_sidebar]',
        'type'     => 'select',
        'choices'  => array(
            'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
            'left-sidebar'  => __('Left Sidebar', 'aqualuxe'),
            'no-sidebar'    => __('No Sidebar', 'aqualuxe'),
        ),
    ));

    // Featured image
    $wp_customize->add_setting('aqualuxe_options[blog_featured_image]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('blog_featured_image', array(
        'label'    => __('Show Featured Image', 'aqualuxe'),
        'section'  => 'aqualuxe_blog',
        'settings' => 'aqualuxe_options[blog_featured_image]',
        'type'     => 'checkbox',
    ));

    // Post meta
    $wp_customize->add_setting('aqualuxe_options[blog_post_meta]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('blog_post_meta', array(
        'label'    => __('Show Post Meta', 'aqualuxe'),
        'section'  => 'aqualuxe_blog',
        'settings' => 'aqualuxe_options[blog_post_meta]',
        'type'     => 'checkbox',
    ));

    // Post author
    $wp_customize->add_setting('aqualuxe_options[blog_post_author]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('blog_post_author', array(
        'label'    => __('Show Post Author', 'aqualuxe'),
        'section'  => 'aqualuxe_blog',
        'settings' => 'aqualuxe_options[blog_post_author]',
        'type'     => 'checkbox',
    ));

    // Post date
    $wp_customize->add_setting('aqualuxe_options[blog_post_date]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('blog_post_date', array(
        'label'    => __('Show Post Date', 'aqualuxe'),
        'section'  => 'aqualuxe_blog',
        'settings' => 'aqualuxe_options[blog_post_date]',
        'type'     => 'checkbox',
    ));

    // Post categories
    $wp_customize->add_setting('aqualuxe_options[blog_post_categories]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('blog_post_categories', array(
        'label'    => __('Show Post Categories', 'aqualuxe'),
        'section'  => 'aqualuxe_blog',
        'settings' => 'aqualuxe_options[blog_post_categories]',
        'type'     => 'checkbox',
    ));

    // Post tags
    $wp_customize->add_setting('aqualuxe_options[blog_post_tags]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('blog_post_tags', array(
        'label'    => __('Show Post Tags', 'aqualuxe'),
        'section'  => 'aqualuxe_blog',
        'settings' => 'aqualuxe_options[blog_post_tags]',
        'type'     => 'checkbox',
    ));

    // Post comments
    $wp_customize->add_setting('aqualuxe_options[blog_post_comments]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('blog_post_comments', array(
        'label'    => __('Show Post Comments', 'aqualuxe'),
        'section'  => 'aqualuxe_blog',
        'settings' => 'aqualuxe_options[blog_post_comments]',
        'type'     => 'checkbox',
    ));

    // Excerpt length
    $wp_customize->add_setting('aqualuxe_options[excerpt_length]', array(
        'default'           => '55',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('excerpt_length', array(
        'label'    => __('Excerpt Length', 'aqualuxe'),
        'section'  => 'aqualuxe_blog',
        'settings' => 'aqualuxe_options[excerpt_length]',
        'type'     => 'number',
        'input_attrs' => array(
            'min'  => 10,
            'max'  => 200,
            'step' => 5,
        ),
    ));

    // Read more text
    $wp_customize->add_setting('aqualuxe_options[read_more_text]', array(
        'default'           => __('Read More', 'aqualuxe'),
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('read_more_text', array(
        'label'    => __('Read More Text', 'aqualuxe'),
        'section'  => 'aqualuxe_blog',
        'settings' => 'aqualuxe_options[read_more_text]',
        'type'     => 'text',
    ));

    // Related posts
    $wp_customize->add_setting('aqualuxe_options[related_posts]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('related_posts', array(
        'label'    => __('Show Related Posts', 'aqualuxe'),
        'section'  => 'aqualuxe_blog',
        'settings' => 'aqualuxe_options[related_posts]',
        'type'     => 'checkbox',
    ));

    // Related posts title
    $wp_customize->add_setting('aqualuxe_options[related_posts_title]', array(
        'default'           => __('Related Posts', 'aqualuxe'),
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('related_posts_title', array(
        'label'    => __('Related Posts Title', 'aqualuxe'),
        'section'  => 'aqualuxe_blog',
        'settings' => 'aqualuxe_options[related_posts_title]',
        'type'     => 'text',
    ));

    // Related posts count
    $wp_customize->add_setting('aqualuxe_options[related_posts_count]', array(
        'default'           => '3',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('related_posts_count', array(
        'label'    => __('Related Posts Count', 'aqualuxe'),
        'section'  => 'aqualuxe_blog',
        'settings' => 'aqualuxe_options[related_posts_count]',
        'type'     => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 12,
            'step' => 1,
        ),
    ));

    // Author box
    $wp_customize->add_setting('aqualuxe_options[author_box]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('author_box', array(
        'label'    => __('Show Author Box', 'aqualuxe'),
        'section'  => 'aqualuxe_blog',
        'settings' => 'aqualuxe_options[author_box]',
        'type'     => 'checkbox',
    ));

    // Post navigation
    $wp_customize->add_setting('aqualuxe_options[post_navigation]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('post_navigation', array(
        'label'    => __('Show Post Navigation', 'aqualuxe'),
        'section'  => 'aqualuxe_blog',
        'settings' => 'aqualuxe_options[post_navigation]',
        'type'     => 'checkbox',
    ));

    // Social sharing
    $wp_customize->add_setting('aqualuxe_options[social_sharing]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('social_sharing', array(
        'label'    => __('Show Social Sharing', 'aqualuxe'),
        'section'  => 'aqualuxe_blog',
        'settings' => 'aqualuxe_options[social_sharing]',
        'type'     => 'checkbox',
    ));
}

/**
 * Social settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_social($wp_customize) {
    // Social section
    $wp_customize->add_section('aqualuxe_social', array(
        'title'    => __('Social Media', 'aqualuxe'),
        'priority' => 90,
    ));

    // Facebook
    $wp_customize->add_setting('aqualuxe_options[facebook_url]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('facebook_url', array(
        'label'    => __('Facebook URL', 'aqualuxe'),
        'section'  => 'aqualuxe_social',
        'settings' => 'aqualuxe_options[facebook_url]',
        'type'     => 'url',
    ));

    // Twitter
    $wp_customize->add_setting('aqualuxe_options[twitter_url]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('twitter_url', array(
        'label'    => __('Twitter URL', 'aqualuxe'),
        'section'  => 'aqualuxe_social',
        'settings' => 'aqualuxe_options[twitter_url]',
        'type'     => 'url',
    ));

    // Instagram
    $wp_customize->add_setting('aqualuxe_options[instagram_url]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('instagram_url', array(
        'label'    => __('Instagram URL', 'aqualuxe'),
        'section'  => 'aqualuxe_social',
        'settings' => 'aqualuxe_options[instagram_url]',
        'type'     => 'url',
    ));

    // YouTube
    $wp_customize->add_setting('aqualuxe_options[youtube_url]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('youtube_url', array(
        'label'    => __('YouTube URL', 'aqualuxe'),
        'section'  => 'aqualuxe_social',
        'settings' => 'aqualuxe_options[youtube_url]',
        'type'     => 'url',
    ));

    // LinkedIn
    $wp_customize->add_setting('aqualuxe_options[linkedin_url]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('linkedin_url', array(
        'label'    => __('LinkedIn URL', 'aqualuxe'),
        'section'  => 'aqualuxe_social',
        'settings' => 'aqualuxe_options[linkedin_url]',
        'type'     => 'url',
    ));

    // Pinterest
    $wp_customize->add_setting('aqualuxe_options[pinterest_url]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('pinterest_url', array(
        'label'    => __('Pinterest URL', 'aqualuxe'),
        'section'  => 'aqualuxe_social',
        'settings' => 'aqualuxe_options[pinterest_url]',
        'type'     => 'url',
    ));

    // TikTok
    $wp_customize->add_setting('aqualuxe_options[tiktok_url]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('tiktok_url', array(
        'label'    => __('TikTok URL', 'aqualuxe'),
        'section'  => 'aqualuxe_social',
        'settings' => 'aqualuxe_options[tiktok_url]',
        'type'     => 'url',
    ));

    // Social sharing options
    $wp_customize->add_setting('aqualuxe_options[share_facebook]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('share_facebook', array(
        'label'    => __('Enable Facebook Sharing', 'aqualuxe'),
        'section'  => 'aqualuxe_social',
        'settings' => 'aqualuxe_options[share_facebook]',
        'type'     => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_options[share_twitter]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('share_twitter', array(
        'label'    => __('Enable Twitter Sharing', 'aqualuxe'),
        'section'  => 'aqualuxe_social',
        'settings' => 'aqualuxe_options[share_twitter]',
        'type'     => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_options[share_linkedin]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('share_linkedin', array(
        'label'    => __('Enable LinkedIn Sharing', 'aqualuxe'),
        'section'  => 'aqualuxe_social',
        'settings' => 'aqualuxe_options[share_linkedin]',
        'type'     => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_options[share_pinterest]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('share_pinterest', array(
        'label'    => __('Enable Pinterest Sharing', 'aqualuxe'),
        'section'  => 'aqualuxe_social',
        'settings' => 'aqualuxe_options[share_pinterest]',
        'type'     => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_options[share_email]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('share_email', array(
        'label'    => __('Enable Email Sharing', 'aqualuxe'),
        'section'  => 'aqualuxe_social',
        'settings' => 'aqualuxe_options[share_email]',
        'type'     => 'checkbox',
    ));
}

/**
 * Contact settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_contact($wp_customize) {
    // Contact section
    $wp_customize->add_section('aqualuxe_contact', array(
        'title'    => __('Contact Information', 'aqualuxe'),
        'priority' => 100,
    ));

    // Contact address
    $wp_customize->add_setting('aqualuxe_options[contact_address]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('contact_address', array(
        'label'    => __('Address', 'aqualuxe'),
        'section'  => 'aqualuxe_contact',
        'settings' => 'aqualuxe_options[contact_address]',
        'type'     => 'text',
    ));

    // Contact phone
    $wp_customize->add_setting('aqualuxe_options[contact_phone]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('contact_phone', array(
        'label'    => __('Phone', 'aqualuxe'),
        'section'  => 'aqualuxe_contact',
        'settings' => 'aqualuxe_options[contact_phone]',
        'type'     => 'text',
    ));

    // Contact email
    $wp_customize->add_setting('aqualuxe_options[contact_email]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_email',
    ));

    $wp_customize->add_control('contact_email', array(
        'label'    => __('Email', 'aqualuxe'),
        'section'  => 'aqualuxe_contact',
        'settings' => 'aqualuxe_options[contact_email]',
        'type'     => 'email',
    ));

    // Contact hours
    $wp_customize->add_setting('aqualuxe_options[contact_hours]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('contact_hours', array(
        'label'    => __('Working Hours', 'aqualuxe'),
        'section'  => 'aqualuxe_contact',
        'settings' => 'aqualuxe_options[contact_hours]',
        'type'     => 'text',
    ));

    // Google Maps API key
    $wp_customize->add_setting('aqualuxe_options[google_maps_api_key]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('google_maps_api_key', array(
        'label'    => __('Google Maps API Key', 'aqualuxe'),
        'section'  => 'aqualuxe_contact',
        'settings' => 'aqualuxe_options[google_maps_api_key]',
        'type'     => 'text',
    ));

    // Google Maps latitude
    $wp_customize->add_setting('aqualuxe_options[google_maps_latitude]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('google_maps_latitude', array(
        'label'    => __('Google Maps Latitude', 'aqualuxe'),
        'section'  => 'aqualuxe_contact',
        'settings' => 'aqualuxe_options[google_maps_latitude]',
        'type'     => 'text',
    ));

    // Google Maps longitude
    $wp_customize->add_setting('aqualuxe_options[google_maps_longitude]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('google_maps_longitude', array(
        'label'    => __('Google Maps Longitude', 'aqualuxe'),
        'section'  => 'aqualuxe_contact',
        'settings' => 'aqualuxe_options[google_maps_longitude]',
        'type'     => 'text',
    ));

    // Google Maps zoom
    $wp_customize->add_setting('aqualuxe_options[google_maps_zoom]', array(
        'default'           => '14',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('google_maps_zoom', array(
        'label'    => __('Google Maps Zoom Level', 'aqualuxe'),
        'section'  => 'aqualuxe_contact',
        'settings' => 'aqualuxe_options[google_maps_zoom]',
        'type'     => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 20,
            'step' => 1,
        ),
    ));

    // Contact form shortcode
    $wp_customize->add_setting('aqualuxe_options[contact_form_shortcode]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('contact_form_shortcode', array(
        'label'    => __('Contact Form Shortcode', 'aqualuxe'),
        'section'  => 'aqualuxe_contact',
        'settings' => 'aqualuxe_options[contact_form_shortcode]',
        'type'     => 'text',
    ));
}

/**
 * WooCommerce settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_woocommerce($wp_customize) {
    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }

    // WooCommerce section
    $wp_customize->add_section('aqualuxe_woocommerce', array(
        'title'    => __('WooCommerce Settings', 'aqualuxe'),
        'priority' => 110,
    ));

    // Shop sidebar
    $wp_customize->add_setting('aqualuxe_options[shop_sidebar]', array(
        'default'           => 'right-sidebar',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('shop_sidebar', array(
        'label'    => __('Shop Sidebar', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[shop_sidebar]',
        'type'     => 'select',
        'choices'  => array(
            'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
            'left-sidebar'  => __('Left Sidebar', 'aqualuxe'),
            'no-sidebar'    => __('No Sidebar', 'aqualuxe'),
        ),
    ));

    // Product sidebar
    $wp_customize->add_setting('aqualuxe_options[product_sidebar]', array(
        'default'           => 'no-sidebar',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('product_sidebar', array(
        'label'    => __('Product Sidebar', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[product_sidebar]',
        'type'     => 'select',
        'choices'  => array(
            'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
            'left-sidebar'  => __('Left Sidebar', 'aqualuxe'),
            'no-sidebar'    => __('No Sidebar', 'aqualuxe'),
        ),
    ));

    // Quick view
    $wp_customize->add_setting('aqualuxe_options[enable_quick_view]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_quick_view', array(
        'label'    => __('Enable Quick View', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[enable_quick_view]',
        'type'     => 'checkbox',
    ));

    // Wishlist
    $wp_customize->add_setting('aqualuxe_options[enable_wishlist]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_wishlist', array(
        'label'    => __('Enable Wishlist', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[enable_wishlist]',
        'type'     => 'checkbox',
    ));

    // Compare
    $wp_customize->add_setting('aqualuxe_options[enable_compare]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_compare', array(
        'label'    => __('Enable Compare', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[enable_compare]',
        'type'     => 'checkbox',
    ));

    // Product zoom
    $wp_customize->add_setting('aqualuxe_options[enable_product_zoom]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_product_zoom', array(
        'label'    => __('Enable Product Image Zoom', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[enable_product_zoom]',
        'type'     => 'checkbox',
    ));

    // Product gallery lightbox
    $wp_customize->add_setting('aqualuxe_options[enable_product_lightbox]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_product_lightbox', array(
        'label'    => __('Enable Product Gallery Lightbox', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[enable_product_lightbox]',
        'type'     => 'checkbox',
    ));

    // Product gallery slider
    $wp_customize->add_setting('aqualuxe_options[enable_product_slider]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_product_slider', array(
        'label'    => __('Enable Product Gallery Slider', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[enable_product_slider]',
        'type'     => 'checkbox',
    ));

    // Related products
    $wp_customize->add_setting('aqualuxe_options[enable_related_products]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_related_products', array(
        'label'    => __('Enable Related Products', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[enable_related_products]',
        'type'     => 'checkbox',
    ));

    // Upsell products
    $wp_customize->add_setting('aqualuxe_options[enable_upsell_products]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_upsell_products', array(
        'label'    => __('Enable Upsell Products', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[enable_upsell_products]',
        'type'     => 'checkbox',
    ));

    // Cross-sell products
    $wp_customize->add_setting('aqualuxe_options[enable_cross_sell_products]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_cross_sell_products', array(
        'label'    => __('Enable Cross-Sell Products', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[enable_cross_sell_products]',
        'type'     => 'checkbox',
    ));

    // Product sharing
    $wp_customize->add_setting('aqualuxe_options[enable_product_sharing]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_product_sharing', array(
        'label'    => __('Enable Product Sharing', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[enable_product_sharing]',
        'type'     => 'checkbox',
    ));

    // Shop filters
    $wp_customize->add_setting('aqualuxe_options[enable_shop_filters]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_shop_filters', array(
        'label'    => __('Enable Shop Filters', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[enable_shop_filters]',
        'type'     => 'checkbox',
    ));

    // Shop view switcher
    $wp_customize->add_setting('aqualuxe_options[enable_view_switcher]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_view_switcher', array(
        'label'    => __('Enable View Switcher', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[enable_view_switcher]',
        'type'     => 'checkbox',
    ));

    // Cart progress
    $wp_customize->add_setting('aqualuxe_options[enable_cart_progress]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_cart_progress', array(
        'label'    => __('Enable Cart Progress', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[enable_cart_progress]',
        'type'     => 'checkbox',
    ));

    // Checkout progress
    $wp_customize->add_setting('aqualuxe_options[enable_checkout_progress]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_checkout_progress', array(
        'label'    => __('Enable Checkout Progress', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[enable_checkout_progress]',
        'type'     => 'checkbox',
    ));

    // Additional product information
    $wp_customize->add_setting('aqualuxe_options[enable_product_additional_info]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_product_additional_info', array(
        'label'    => __('Enable Additional Product Information', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[enable_product_additional_info]',
        'type'     => 'checkbox',
    ));

    // Shipping information
    $wp_customize->add_setting('aqualuxe_options[enable_shipping_info]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_shipping_info', array(
        'label'    => __('Enable Shipping Information', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[enable_shipping_info]',
        'type'     => 'checkbox',
    ));

    // Shipping information text
    $wp_customize->add_setting('aqualuxe_options[shipping_info]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('shipping_info', array(
        'label'    => __('Shipping Information Text', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[shipping_info]',
        'type'     => 'textarea',
    ));

    // Returns information
    $wp_customize->add_setting('aqualuxe_options[enable_returns_info]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_returns_info', array(
        'label'    => __('Enable Returns Information', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[enable_returns_info]',
        'type'     => 'checkbox',
    ));

    // Returns information text
    $wp_customize->add_setting('aqualuxe_options[returns_info]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('returns_info', array(
        'label'    => __('Returns Information Text', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[returns_info]',
        'type'     => 'textarea',
    ));

    // Size guide
    $wp_customize->add_setting('aqualuxe_options[enable_size_guide]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_size_guide', array(
        'label'    => __('Enable Size Guide', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[enable_size_guide]',
        'type'     => 'checkbox',
    ));

    // Size guide text
    $wp_customize->add_setting('aqualuxe_options[size_guide]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('size_guide', array(
        'label'    => __('Size Guide Text', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[size_guide]',
        'type'     => 'textarea',
    ));

    // New product days
    $wp_customize->add_setting('aqualuxe_options[new_product_days]', array(
        'default'           => '30',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('new_product_days', array(
        'label'    => __('New Product Badge Days', 'aqualuxe'),
        'description' => __('Number of days to show the "New" badge on products.', 'aqualuxe'),
        'section'  => 'aqualuxe_woocommerce',
        'settings' => 'aqualuxe_options[new_product_days]',
        'type'     => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 100,
            'step' => 1,
        ),
    ));
}

/**
 * Performance settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_performance($wp_customize) {
    // Performance section
    $wp_customize->add_section('aqualuxe_performance', array(
        'title'    => __('Performance Settings', 'aqualuxe'),
        'priority' => 120,
    ));

    // Lazy loading
    $wp_customize->add_setting('aqualuxe_options[enable_lazy_loading]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_lazy_loading', array(
        'label'    => __('Enable Lazy Loading', 'aqualuxe'),
        'section'  => 'aqualuxe_performance',
        'settings' => 'aqualuxe_options[enable_lazy_loading]',
        'type'     => 'checkbox',
    ));

    // Preload
    $wp_customize->add_setting('aqualuxe_options[enable_preload]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_preload', array(
        'label'    => __('Enable Preloading', 'aqualuxe'),
        'section'  => 'aqualuxe_performance',
        'settings' => 'aqualuxe_options[enable_preload]',
        'type'     => 'checkbox',
    ));

    // Minify HTML
    $wp_customize->add_setting('aqualuxe_options[enable_minify_html]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_minify_html', array(
        'label'    => __('Enable HTML Minification', 'aqualuxe'),
        'section'  => 'aqualuxe_performance',
        'settings' => 'aqualuxe_options[enable_minify_html]',
        'type'     => 'checkbox',
    ));

    // Defer JavaScript
    $wp_customize->add_setting('aqualuxe_options[enable_defer_js]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_defer_js', array(
        'label'    => __('Enable JavaScript Defer', 'aqualuxe'),
        'section'  => 'aqualuxe_performance',
        'settings' => 'aqualuxe_options[enable_defer_js]',
        'type'     => 'checkbox',
    ));

    // Preconnect
    $wp_customize->add_setting('aqualuxe_options[enable_preconnect]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_preconnect', array(
        'label'    => __('Enable Preconnect', 'aqualuxe'),
        'section'  => 'aqualuxe_performance',
        'settings' => 'aqualuxe_options[enable_preconnect]',
        'type'     => 'checkbox',
    ));

    // Cache busting
    $wp_customize->add_setting('aqualuxe_options[enable_cache_busting]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('enable_cache_busting', array(
        'label'    => __('Enable Cache Busting', 'aqualuxe'),
        'section'  => 'aqualuxe_performance',
        'settings' => 'aqualuxe_options[enable_cache_busting]',
        'type'     => 'checkbox',
    ));

    // Emoji removal
    $wp_customize->add_setting('aqualuxe_options[disable_emojis]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('disable_emojis', array(
        'label'    => __('Disable WordPress Emojis', 'aqualuxe'),
        'section'  => 'aqualuxe_performance',
        'settings' => 'aqualuxe_options[disable_emojis]',
        'type'     => 'checkbox',
    ));

    // Embeds removal
    $wp_customize->add_setting('aqualuxe_options[disable_embeds]', array(
        'default'           => false,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('disable_embeds', array(
        'label'    => __('Disable WordPress Embeds', 'aqualuxe'),
        'section'  => 'aqualuxe_performance',
        'settings' => 'aqualuxe_options[disable_embeds]',
        'type'     => 'checkbox',
    ));

    // Heartbeat control
    $wp_customize->add_setting('aqualuxe_options[heartbeat_control]', array(
        'default'           => 'default',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('heartbeat_control', array(
        'label'    => __('Heartbeat Control', 'aqualuxe'),
        'section'  => 'aqualuxe_performance',
        'settings' => 'aqualuxe_options[heartbeat_control]',
        'type'     => 'select',
        'choices'  => array(
            'default' => __('Default', 'aqualuxe'),
            'limited' => __('Limited', 'aqualuxe'),
            'disable' => __('Disable', 'aqualuxe'),
        ),
    ));
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
    wp_enqueue_script('aqualuxe-customizer', AQUALUXE_ASSETS_URI . '/js/customizer.js', array('customize-preview'), AQUALUXE_VERSION, true);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');

/**
 * Sanitize checkbox
 *
 * @param bool $input Input value
 * @return bool
 */
function aqualuxe_sanitize_checkbox($input) {
    return (isset($input) && true == $input) ? true : false;
}

/**
 * Sanitize select
 *
 * @param string $input Input value
 * @param WP_Customize_Setting $setting Setting object
 * @return string
 */
function aqualuxe_sanitize_select($input, $setting) {
    // Get the list of choices from the control associated with the setting
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    // Return input if valid or return default if not
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}