<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access to this file
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
    $wp_customize->get_setting('custom_logo')->transport = 'postMessage';
    
    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial('blogname', array(
            'selector' => '.site-title a',
            'render_callback' => 'aqualuxe_customize_partial_blogname',
        ));
        
        $wp_customize->selective_refresh->add_partial('blogdescription', array(
            'selector' => '.site-description',
            'render_callback' => 'aqualuxe_customize_partial_blogdescription',
        ));
    }
    
    // Add AquaLuxe theme options panel
    $wp_customize->add_panel('aqualuxe_theme_options', array(
        'title' => __('AquaLuxe Theme Options', 'aqualuxe'),
        'description' => __('Customize your AquaLuxe theme settings', 'aqualuxe'),
        'priority' => 130,
    ));
    
    // Add sections
    aqualuxe_customize_general_section($wp_customize);
    aqualuxe_customize_header_section($wp_customize);
    aqualuxe_customize_footer_section($wp_customize);
    aqualuxe_customize_colors_section($wp_customize);
    aqualuxe_customize_typography_section($wp_customize);
    aqualuxe_customize_layout_section($wp_customize);
    aqualuxe_customize_homepage_section($wp_customize);
    aqualuxe_customize_blog_section($wp_customize);
    
    // Add WooCommerce sections if WooCommerce is active
    if (aqualuxe_is_woocommerce_active()) {
        aqualuxe_customize_woocommerce_section($wp_customize);
        aqualuxe_customize_product_section($wp_customize);
        aqualuxe_customize_cart_checkout_section($wp_customize);
    }
    
    // Add advanced sections
    aqualuxe_customize_advanced_section($wp_customize);
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
 * Add general section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_general_section($wp_customize) {
    // Add general section
    $wp_customize->add_section('aqualuxe_general', array(
        'title' => __('General Settings', 'aqualuxe'),
        'description' => __('General theme settings', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 10,
    ));
    
    // Add settings
    $wp_customize->add_setting('aqualuxe_options[default_dark_mode]', array(
        'default' => false,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[default_dark_mode]', array(
        'label' => __('Enable Dark Mode by Default', 'aqualuxe'),
        'description' => __('Enable dark mode by default for all visitors', 'aqualuxe'),
        'section' => 'aqualuxe_general',
        'type' => 'checkbox',
    ));
    
    // Contact information
    $wp_customize->add_setting('aqualuxe_options[contact_phone]', array(
        'default' => '',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_options[contact_phone]', array(
        'label' => __('Phone Number', 'aqualuxe'),
        'description' => __('Enter your phone number', 'aqualuxe'),
        'section' => 'aqualuxe_general',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[contact_email]', array(
        'default' => '',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_email',
    ));
    
    $wp_customize->add_control('aqualuxe_options[contact_email]', array(
        'label' => __('Email Address', 'aqualuxe'),
        'description' => __('Enter your email address', 'aqualuxe'),
        'section' => 'aqualuxe_general',
        'type' => 'email',
    ));
    
    // Social media
    $wp_customize->add_setting('aqualuxe_options[social_facebook]', array(
        'default' => '',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('aqualuxe_options[social_facebook]', array(
        'label' => __('Facebook URL', 'aqualuxe'),
        'description' => __('Enter your Facebook URL', 'aqualuxe'),
        'section' => 'aqualuxe_general',
        'type' => 'url',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[social_twitter]', array(
        'default' => '',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('aqualuxe_options[social_twitter]', array(
        'label' => __('Twitter URL', 'aqualuxe'),
        'description' => __('Enter your Twitter URL', 'aqualuxe'),
        'section' => 'aqualuxe_general',
        'type' => 'url',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[social_instagram]', array(
        'default' => '',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('aqualuxe_options[social_instagram]', array(
        'label' => __('Instagram URL', 'aqualuxe'),
        'description' => __('Enter your Instagram URL', 'aqualuxe'),
        'section' => 'aqualuxe_general',
        'type' => 'url',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[social_youtube]', array(
        'default' => '',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('aqualuxe_options[social_youtube]', array(
        'label' => __('YouTube URL', 'aqualuxe'),
        'description' => __('Enter your YouTube URL', 'aqualuxe'),
        'section' => 'aqualuxe_general',
        'type' => 'url',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[social_linkedin]', array(
        'default' => '',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('aqualuxe_options[social_linkedin]', array(
        'label' => __('LinkedIn URL', 'aqualuxe'),
        'description' => __('Enter your LinkedIn URL', 'aqualuxe'),
        'section' => 'aqualuxe_general',
        'type' => 'url',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[social_pinterest]', array(
        'default' => '',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('aqualuxe_options[social_pinterest]', array(
        'label' => __('Pinterest URL', 'aqualuxe'),
        'description' => __('Enter your Pinterest URL', 'aqualuxe'),
        'section' => 'aqualuxe_general',
        'type' => 'url',
    ));
}

/**
 * Add header section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_header_section($wp_customize) {
    // Add header section
    $wp_customize->add_section('aqualuxe_header', array(
        'title' => __('Header Settings', 'aqualuxe'),
        'description' => __('Customize header settings', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 20,
    ));
    
    // Add settings
    $wp_customize->add_setting('aqualuxe_options[header_layout]', array(
        'default' => 'default',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_options[header_layout]', array(
        'label' => __('Header Layout', 'aqualuxe'),
        'description' => __('Select header layout', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'type' => 'select',
        'choices' => array(
            'default' => __('Default', 'aqualuxe'),
            'centered' => __('Centered', 'aqualuxe'),
            'minimal' => __('Minimal', 'aqualuxe'),
        ),
    ));
    
    $wp_customize->add_setting('aqualuxe_options[header_sticky]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[header_sticky]', array(
        'label' => __('Sticky Header', 'aqualuxe'),
        'description' => __('Enable sticky header', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[header_transparent]', array(
        'default' => false,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[header_transparent]', array(
        'label' => __('Transparent Header', 'aqualuxe'),
        'description' => __('Enable transparent header on homepage', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[header_top_bar]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[header_top_bar]', array(
        'label' => __('Top Bar', 'aqualuxe'),
        'description' => __('Enable top bar', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[categories_menu_enabled]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[categories_menu_enabled]', array(
        'label' => __('Categories Menu', 'aqualuxe'),
        'description' => __('Enable categories menu in header', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'type' => 'checkbox',
    ));
}

/**
 * Add footer section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_footer_section($wp_customize) {
    // Add footer section
    $wp_customize->add_section('aqualuxe_footer', array(
        'title' => __('Footer Settings', 'aqualuxe'),
        'description' => __('Customize footer settings', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 30,
    ));
    
    // Add settings
    $wp_customize->add_setting('aqualuxe_options[footer_layout]', array(
        'default' => 'default',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_options[footer_layout]', array(
        'label' => __('Footer Layout', 'aqualuxe'),
        'description' => __('Select footer layout', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'type' => 'select',
        'choices' => array(
            'default' => __('Default (4 columns)', 'aqualuxe'),
            'three-columns' => __('3 Columns', 'aqualuxe'),
            'two-columns' => __('2 Columns', 'aqualuxe'),
        ),
    ));
    
    $wp_customize->add_setting('aqualuxe_options[copyright_text]', array(
        'default' => sprintf(
            /* translators: %1$s: Current year, %2$s: Site name */
            __('&copy; %1$s %2$s. All rights reserved.', 'aqualuxe'),
            date('Y'),
            get_bloginfo('name')
        ),
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('aqualuxe_options[copyright_text]', array(
        'label' => __('Copyright Text', 'aqualuxe'),
        'description' => __('Enter copyright text', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'type' => 'textarea',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[payment_icons]', array(
        'default' => array('visa', 'mastercard', 'amex', 'paypal'),
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
    ));
    
    $wp_customize->add_control(new AquaLuxe_Customize_Multi_Checkbox_Control($wp_customize, 'aqualuxe_options[payment_icons]', array(
        'label' => __('Payment Icons', 'aqualuxe'),
        'description' => __('Select payment icons to display in footer', 'aqualuxe'),
        'section' => 'aqualuxe_footer',
        'choices' => array(
            'visa' => __('Visa', 'aqualuxe'),
            'mastercard' => __('Mastercard', 'aqualuxe'),
            'amex' => __('American Express', 'aqualuxe'),
            'discover' => __('Discover', 'aqualuxe'),
            'paypal' => __('PayPal', 'aqualuxe'),
            'apple-pay' => __('Apple Pay', 'aqualuxe'),
            'google-pay' => __('Google Pay', 'aqualuxe'),
        ),
    )));
}

/**
 * Add colors section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_colors_section($wp_customize) {
    // Add colors section
    $wp_customize->add_section('aqualuxe_colors', array(
        'title' => __('Colors', 'aqualuxe'),
        'description' => __('Customize theme colors', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 40,
    ));
    
    // Add settings
    $wp_customize->add_setting('aqualuxe_options[primary_color]', array(
        'default' => '#0073aa',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[primary_color]', array(
        'label' => __('Primary Color', 'aqualuxe'),
        'description' => __('Select primary color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
    )));
    
    $wp_customize->add_setting('aqualuxe_options[secondary_color]', array(
        'default' => '#00a0d2',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[secondary_color]', array(
        'label' => __('Secondary Color', 'aqualuxe'),
        'description' => __('Select secondary color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
    )));
    
    $wp_customize->add_setting('aqualuxe_options[accent_color]', array(
        'default' => '#00c1af',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[accent_color]', array(
        'label' => __('Accent Color', 'aqualuxe'),
        'description' => __('Select accent color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
    )));
    
    $wp_customize->add_setting('aqualuxe_options[text_color]', array(
        'default' => '#333333',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[text_color]', array(
        'label' => __('Text Color', 'aqualuxe'),
        'description' => __('Select text color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
    )));
    
    $wp_customize->add_setting('aqualuxe_options[heading_color]', array(
        'default' => '#222222',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[heading_color]', array(
        'label' => __('Heading Color', 'aqualuxe'),
        'description' => __('Select heading color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
    )));
    
    $wp_customize->add_setting('aqualuxe_options[background_color]', array(
        'default' => '#ffffff',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[background_color]', array(
        'label' => __('Background Color', 'aqualuxe'),
        'description' => __('Select background color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
    )));
    
    // Dark mode colors
    $wp_customize->add_setting('aqualuxe_options[dark_background_color]', array(
        'default' => '#121212',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[dark_background_color]', array(
        'label' => __('Dark Mode Background Color', 'aqualuxe'),
        'description' => __('Select dark mode background color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
    )));
    
    $wp_customize->add_setting('aqualuxe_options[dark_text_color]', array(
        'default' => '#f5f5f5',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[dark_text_color]', array(
        'label' => __('Dark Mode Text Color', 'aqualuxe'),
        'description' => __('Select dark mode text color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
    )));
}

/**
 * Add typography section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_typography_section($wp_customize) {
    // Add typography section
    $wp_customize->add_section('aqualuxe_typography', array(
        'title' => __('Typography', 'aqualuxe'),
        'description' => __('Customize theme typography', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 50,
    ));
    
    // Add settings
    $wp_customize->add_setting('aqualuxe_options[body_font]', array(
        'default' => 'Inter',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_options[body_font]', array(
        'label' => __('Body Font', 'aqualuxe'),
        'description' => __('Select body font', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => array(
            'Inter' => 'Inter',
            'Roboto' => 'Roboto',
            'Open Sans' => 'Open Sans',
            'Lato' => 'Lato',
            'Montserrat' => 'Montserrat',
            'Poppins' => 'Poppins',
            'Nunito' => 'Nunito',
            'Raleway' => 'Raleway',
            'Source Sans Pro' => 'Source Sans Pro',
            'PT Sans' => 'PT Sans',
        ),
    ));
    
    $wp_customize->add_setting('aqualuxe_options[heading_font]', array(
        'default' => 'Montserrat',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_options[heading_font]', array(
        'label' => __('Heading Font', 'aqualuxe'),
        'description' => __('Select heading font', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => array(
            'Montserrat' => 'Montserrat',
            'Roboto' => 'Roboto',
            'Open Sans' => 'Open Sans',
            'Lato' => 'Lato',
            'Inter' => 'Inter',
            'Poppins' => 'Poppins',
            'Nunito' => 'Nunito',
            'Raleway' => 'Raleway',
            'Playfair Display' => 'Playfair Display',
            'Merriweather' => 'Merriweather',
        ),
    ));
    
    $wp_customize->add_setting('aqualuxe_options[body_font_size]', array(
        'default' => '16px',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_options[body_font_size]', array(
        'label' => __('Body Font Size', 'aqualuxe'),
        'description' => __('Enter body font size (e.g., 16px)', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[heading_font_weight]', array(
        'default' => '600',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_options[heading_font_weight]', array(
        'label' => __('Heading Font Weight', 'aqualuxe'),
        'description' => __('Select heading font weight', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => array(
            '400' => 'Normal (400)',
            '500' => 'Medium (500)',
            '600' => 'Semi-Bold (600)',
            '700' => 'Bold (700)',
            '800' => 'Extra Bold (800)',
        ),
    ));
}

/**
 * Add layout section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_layout_section($wp_customize) {
    // Add layout section
    $wp_customize->add_section('aqualuxe_layout', array(
        'title' => __('Layout Settings', 'aqualuxe'),
        'description' => __('Customize layout settings', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 60,
    ));
    
    // Add settings
    $wp_customize->add_setting('aqualuxe_options[container_width]', array(
        'default' => '1200px',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_options[container_width]', array(
        'label' => __('Container Width', 'aqualuxe'),
        'description' => __('Enter container width (e.g., 1200px)', 'aqualuxe'),
        'section' => 'aqualuxe_layout',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[sidebar_position]', array(
        'default' => 'right',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_options[sidebar_position]', array(
        'label' => __('Sidebar Position', 'aqualuxe'),
        'description' => __('Select sidebar position', 'aqualuxe'),
        'section' => 'aqualuxe_layout',
        'type' => 'select',
        'choices' => array(
            'right' => __('Right', 'aqualuxe'),
            'left' => __('Left', 'aqualuxe'),
            'none' => __('No Sidebar', 'aqualuxe'),
        ),
    ));
    
    $wp_customize->add_setting('aqualuxe_options[blog_layout]', array(
        'default' => 'grid',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_options[blog_layout]', array(
        'label' => __('Blog Layout', 'aqualuxe'),
        'description' => __('Select blog layout', 'aqualuxe'),
        'section' => 'aqualuxe_layout',
        'type' => 'select',
        'choices' => array(
            'grid' => __('Grid', 'aqualuxe'),
            'list' => __('List', 'aqualuxe'),
            'masonry' => __('Masonry', 'aqualuxe'),
        ),
    ));
    
    $wp_customize->add_setting('aqualuxe_options[archive_columns]', array(
        'default' => '3',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_options[archive_columns]', array(
        'label' => __('Archive Columns', 'aqualuxe'),
        'description' => __('Select number of columns for archive pages', 'aqualuxe'),
        'section' => 'aqualuxe_layout',
        'type' => 'select',
        'choices' => array(
            '2' => '2',
            '3' => '3',
            '4' => '4',
        ),
    ));
}

/**
 * Add homepage section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_homepage_section($wp_customize) {
    // Add homepage section
    $wp_customize->add_section('aqualuxe_homepage', array(
        'title' => __('Homepage Settings', 'aqualuxe'),
        'description' => __('Customize homepage settings', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 70,
    ));
    
    // Hero section
    $wp_customize->add_setting('aqualuxe_options[homepage_hero_enabled]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_hero_enabled]', array(
        'label' => __('Enable Hero Section', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[homepage_hero_title]', array(
        'default' => __('Bringing elegance to aquatic life – globally.', 'aqualuxe'),
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_hero_title]', array(
        'label' => __('Hero Title', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[homepage_hero_description]', array(
        'default' => __('Discover our premium collection of rare fish species, aquatic plants, and high-quality equipment.', 'aqualuxe'),
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_hero_description]', array(
        'label' => __('Hero Description', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'textarea',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[homepage_hero_button_text]', array(
        'default' => __('Shop Now', 'aqualuxe'),
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_hero_button_text]', array(
        'label' => __('Hero Button Text', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[homepage_hero_button_url]', array(
        'default' => '#',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_hero_button_url]', array(
        'label' => __('Hero Button URL', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'url',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[homepage_hero_image]', array(
        'default' => '',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aqualuxe_options[homepage_hero_image]', array(
        'label' => __('Hero Background Image', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
    )));
    
    // Featured products section
    $wp_customize->add_setting('aqualuxe_options[homepage_featured_products_enabled]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_featured_products_enabled]', array(
        'label' => __('Enable Featured Products Section', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[homepage_featured_products_title]', array(
        'default' => __('Featured Products', 'aqualuxe'),
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_featured_products_title]', array(
        'label' => __('Featured Products Title', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[homepage_featured_products_description]', array(
        'default' => __('Explore our handpicked selection of premium aquatic products.', 'aqualuxe'),
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_featured_products_description]', array(
        'label' => __('Featured Products Description', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'textarea',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[homepage_featured_products_count]', array(
        'default' => 4,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_featured_products_count]', array(
        'label' => __('Featured Products Count', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 12,
            'step' => 1,
        ),
    ));
    
    // Categories section
    $wp_customize->add_setting('aqualuxe_options[homepage_categories_enabled]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_categories_enabled]', array(
        'label' => __('Enable Categories Section', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[homepage_categories_title]', array(
        'default' => __('Shop by Category', 'aqualuxe'),
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_categories_title]', array(
        'label' => __('Categories Title', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[homepage_categories_description]', array(
        'default' => __('Browse our wide range of aquatic categories.', 'aqualuxe'),
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_categories_description]', array(
        'label' => __('Categories Description', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'textarea',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[homepage_categories_count]', array(
        'default' => 4,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_categories_count]', array(
        'label' => __('Categories Count', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 12,
            'step' => 1,
        ),
    ));
    
    // Services section
    $wp_customize->add_setting('aqualuxe_options[homepage_services_enabled]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_services_enabled]', array(
        'label' => __('Enable Services Section', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[homepage_services_title]', array(
        'default' => __('Our Services', 'aqualuxe'),
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_services_title]', array(
        'label' => __('Services Title', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[homepage_services_description]', array(
        'default' => __('Professional aquatic services for your needs.', 'aqualuxe'),
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_services_description]', array(
        'label' => __('Services Description', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'textarea',
    ));
    
    // Testimonials section
    $wp_customize->add_setting('aqualuxe_options[homepage_testimonials_enabled]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_testimonials_enabled]', array(
        'label' => __('Enable Testimonials Section', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[homepage_testimonials_title]', array(
        'default' => __('What Our Customers Say', 'aqualuxe'),
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_testimonials_title]', array(
        'label' => __('Testimonials Title', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[homepage_testimonials_description]', array(
        'default' => __('Read testimonials from our satisfied customers.', 'aqualuxe'),
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_testimonials_description]', array(
        'label' => __('Testimonials Description', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'textarea',
    ));
    
    // Blog section
    $wp_customize->add_setting('aqualuxe_options[homepage_blog_enabled]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_blog_enabled]', array(
        'label' => __('Enable Blog Section', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[homepage_blog_title]', array(
        'default' => __('Latest Articles', 'aqualuxe'),
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_blog_title]', array(
        'label' => __('Blog Title', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[homepage_blog_description]', array(
        'default' => __('Read our latest articles and guides.', 'aqualuxe'),
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_blog_description]', array(
        'label' => __('Blog Description', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'textarea',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[homepage_blog_count]', array(
        'default' => 3,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_blog_count]', array(
        'label' => __('Blog Posts Count', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 12,
            'step' => 1,
        ),
    ));
    
    // Newsletter section
    $wp_customize->add_setting('aqualuxe_options[homepage_newsletter_enabled]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_newsletter_enabled]', array(
        'label' => __('Enable Newsletter Section', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[homepage_newsletter_title]', array(
        'default' => __('Subscribe to Our Newsletter', 'aqualuxe'),
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_newsletter_title]', array(
        'label' => __('Newsletter Title', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[homepage_newsletter_description]', array(
        'default' => __('Stay updated with our latest products, offers, and aquatic care tips.', 'aqualuxe'),
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_newsletter_description]', array(
        'label' => __('Newsletter Description', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'textarea',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[homepage_newsletter_form]', array(
        'default' => '',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('aqualuxe_options[homepage_newsletter_form]', array(
        'label' => __('Newsletter Form Shortcode', 'aqualuxe'),
        'description' => __('Enter newsletter form shortcode (e.g., [contact-form-7 id="123" title="Newsletter Form"])', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[homepage_newsletter_image]', array(
        'default' => '',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aqualuxe_options[homepage_newsletter_image]', array(
        'label' => __('Newsletter Background Image', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
    )));
}

/**
 * Add blog section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_blog_section($wp_customize) {
    // Add blog section
    $wp_customize->add_section('aqualuxe_blog', array(
        'title' => __('Blog Settings', 'aqualuxe'),
        'description' => __('Customize blog settings', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 80,
    ));
    
    // Add settings
    $wp_customize->add_setting('aqualuxe_options[blog_excerpt_length]', array(
        'default' => 30,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_options[blog_excerpt_length]', array(
        'label' => __('Excerpt Length', 'aqualuxe'),
        'description' => __('Number of words in excerpt', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 10,
            'max' => 100,
            'step' => 1,
        ),
    ));
    
    $wp_customize->add_setting('aqualuxe_options[blog_read_more_text]', array(
        'default' => __('Read More', 'aqualuxe'),
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_options[blog_read_more_text]', array(
        'label' => __('Read More Text', 'aqualuxe'),
        'description' => __('Text for read more link', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[blog_show_author]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[blog_show_author]', array(
        'label' => __('Show Author', 'aqualuxe'),
        'description' => __('Show author name in blog posts', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[blog_show_date]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[blog_show_date]', array(
        'label' => __('Show Date', 'aqualuxe'),
        'description' => __('Show date in blog posts', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[blog_show_category]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[blog_show_category]', array(
        'label' => __('Show Category', 'aqualuxe'),
        'description' => __('Show category in blog posts', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[blog_show_comments]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[blog_show_comments]', array(
        'label' => __('Show Comments', 'aqualuxe'),
        'description' => __('Show comments count in blog posts', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[related_posts_enabled]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[related_posts_enabled]', array(
        'label' => __('Enable Related Posts', 'aqualuxe'),
        'description' => __('Show related posts on single post page', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[related_posts_title]', array(
        'default' => __('Related Posts', 'aqualuxe'),
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_options[related_posts_title]', array(
        'label' => __('Related Posts Title', 'aqualuxe'),
        'description' => __('Title for related posts section', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[related_posts_count]', array(
        'default' => 3,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_options[related_posts_count]', array(
        'label' => __('Related Posts Count', 'aqualuxe'),
        'description' => __('Number of related posts to show', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 12,
            'step' => 1,
        ),
    ));
}

/**
 * Add WooCommerce section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_woocommerce_section($wp_customize) {
    // Add WooCommerce section
    $wp_customize->add_section('aqualuxe_woocommerce', array(
        'title' => __('WooCommerce Settings', 'aqualuxe'),
        'description' => __('Customize WooCommerce settings', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 90,
    ));
    
    // Add settings
    $wp_customize->add_setting('aqualuxe_options[shop_columns]', array(
        'default' => 3,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_options[shop_columns]', array(
        'label' => __('Shop Columns', 'aqualuxe'),
        'description' => __('Number of columns in shop page', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
        ),
    ));
    
    $wp_customize->add_setting('aqualuxe_options[products_per_page]', array(
        'default' => 12,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_options[products_per_page]', array(
        'label' => __('Products Per Page', 'aqualuxe'),
        'description' => __('Number of products per page', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 100,
            'step' => 1,
        ),
    ));
    
    $wp_customize->add_setting('aqualuxe_options[shop_sidebar]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[shop_sidebar]', array(
        'label' => __('Show Shop Sidebar', 'aqualuxe'),
        'description' => __('Show sidebar on shop page', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[shop_sidebar_position]', array(
        'default' => 'right',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_options[shop_sidebar_position]', array(
        'label' => __('Shop Sidebar Position', 'aqualuxe'),
        'description' => __('Select shop sidebar position', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            'right' => __('Right', 'aqualuxe'),
            'left' => __('Left', 'aqualuxe'),
        ),
    ));
    
    $wp_customize->add_setting('aqualuxe_options[wishlist_enabled]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[wishlist_enabled]', array(
        'label' => __('Enable Wishlist', 'aqualuxe'),
        'description' => __('Enable wishlist functionality', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[quick_view_enabled]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[quick_view_enabled]', array(
        'label' => __('Enable Quick View', 'aqualuxe'),
        'description' => __('Enable quick view functionality', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[ajax_add_to_cart]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[ajax_add_to_cart]', array(
        'label' => __('Enable AJAX Add to Cart', 'aqualuxe'),
        'description' => __('Enable AJAX add to cart functionality', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ));
}

/**
 * Add product section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_product_section($wp_customize) {
    // Add product section
    $wp_customize->add_section('aqualuxe_product', array(
        'title' => __('Product Settings', 'aqualuxe'),
        'description' => __('Customize product settings', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 100,
    ));
    
    // Add settings
    $wp_customize->add_setting('aqualuxe_options[product_gallery_zoom]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[product_gallery_zoom]', array(
        'label' => __('Enable Gallery Zoom', 'aqualuxe'),
        'description' => __('Enable zoom functionality in product gallery', 'aqualuxe'),
        'section' => 'aqualuxe_product',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[product_gallery_lightbox]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[product_gallery_lightbox]', array(
        'label' => __('Enable Gallery Lightbox', 'aqualuxe'),
        'description' => __('Enable lightbox functionality in product gallery', 'aqualuxe'),
        'section' => 'aqualuxe_product',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[product_gallery_slider]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[product_gallery_slider]', array(
        'label' => __('Enable Gallery Slider', 'aqualuxe'),
        'description' => __('Enable slider functionality in product gallery', 'aqualuxe'),
        'section' => 'aqualuxe_product',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[product_sticky_add_to_cart]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[product_sticky_add_to_cart]', array(
        'label' => __('Enable Sticky Add to Cart', 'aqualuxe'),
        'description' => __('Enable sticky add to cart bar when scrolling down product page', 'aqualuxe'),
        'section' => 'aqualuxe_product',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[product_related_count]', array(
        'default' => 4,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_options[product_related_count]', array(
        'label' => __('Related Products Count', 'aqualuxe'),
        'description' => __('Number of related products to show', 'aqualuxe'),
        'section' => 'aqualuxe_product',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 12,
            'step' => 1,
        ),
    ));
    
    $wp_customize->add_setting('aqualuxe_options[product_upsells_count]', array(
        'default' => 4,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_options[product_upsells_count]', array(
        'label' => __('Upsells Count', 'aqualuxe'),
        'description' => __('Number of upsells to show', 'aqualuxe'),
        'section' => 'aqualuxe_product',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 12,
            'step' => 1,
        ),
    ));
    
    $wp_customize->add_setting('aqualuxe_options[product_cross_sells_count]', array(
        'default' => 4,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_options[product_cross_sells_count]', array(
        'label' => __('Cross-Sells Count', 'aqualuxe'),
        'description' => __('Number of cross-sells to show', 'aqualuxe'),
        'section' => 'aqualuxe_product',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 12,
            'step' => 1,
        ),
    ));
}

/**
 * Add cart and checkout section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_cart_checkout_section($wp_customize) {
    // Add cart and checkout section
    $wp_customize->add_section('aqualuxe_cart_checkout', array(
        'title' => __('Cart & Checkout Settings', 'aqualuxe'),
        'description' => __('Customize cart and checkout settings', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 110,
    ));
    
    // Add settings
    $wp_customize->add_setting('aqualuxe_options[cart_cross_sells_enabled]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[cart_cross_sells_enabled]', array(
        'label' => __('Enable Cart Cross-Sells', 'aqualuxe'),
        'description' => __('Show cross-sells on cart page', 'aqualuxe'),
        'section' => 'aqualuxe_cart_checkout',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[checkout_layout]', array(
        'default' => 'default',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_options[checkout_layout]', array(
        'label' => __('Checkout Layout', 'aqualuxe'),
        'description' => __('Select checkout layout', 'aqualuxe'),
        'section' => 'aqualuxe_cart_checkout',
        'type' => 'select',
        'choices' => array(
            'default' => __('Default', 'aqualuxe'),
            'two-column' => __('Two Column', 'aqualuxe'),
            'one-column' => __('One Column', 'aqualuxe'),
        ),
    ));
    
    $wp_customize->add_setting('aqualuxe_options[checkout_distraction_free]', array(
        'default' => false,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[checkout_distraction_free]', array(
        'label' => __('Distraction-Free Checkout', 'aqualuxe'),
        'description' => __('Enable distraction-free checkout (removes header and footer)', 'aqualuxe'),
        'section' => 'aqualuxe_cart_checkout',
        'type' => 'checkbox',
    ));
}

/**
 * Add advanced section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_advanced_section($wp_customize) {
    // Add advanced section
    $wp_customize->add_section('aqualuxe_advanced', array(
        'title' => __('Advanced Settings', 'aqualuxe'),
        'description' => __('Advanced theme settings', 'aqualuxe'),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 120,
    ));
    
    // Add settings
    $wp_customize->add_setting('aqualuxe_options[custom_css]', array(
        'default' => '',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    
    $wp_customize->add_control('aqualuxe_options[custom_css]', array(
        'label' => __('Custom CSS', 'aqualuxe'),
        'description' => __('Add custom CSS styles', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'type' => 'textarea',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[custom_js]', array(
        'default' => '',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    
    $wp_customize->add_control('aqualuxe_options[custom_js]', array(
        'label' => __('Custom JavaScript', 'aqualuxe'),
        'description' => __('Add custom JavaScript code', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'type' => 'textarea',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[header_code]', array(
        'default' => '',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('aqualuxe_options[header_code]', array(
        'label' => __('Header Code', 'aqualuxe'),
        'description' => __('Add code to header (e.g., Google Analytics)', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'type' => 'textarea',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[footer_code]', array(
        'default' => '',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('aqualuxe_options[footer_code]', array(
        'label' => __('Footer Code', 'aqualuxe'),
        'description' => __('Add code to footer', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'type' => 'textarea',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[lazy_load_images]', array(
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[lazy_load_images]', array(
        'label' => __('Lazy Load Images', 'aqualuxe'),
        'description' => __('Enable lazy loading for images', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_options[minify_html]', array(
        'default' => false,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_options[minify_html]', array(
        'label' => __('Minify HTML', 'aqualuxe'),
        'description' => __('Enable HTML minification', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'type' => 'checkbox',
    ));
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
    wp_enqueue_script('aqualuxe-customizer', AQUALUXE_ASSETS_URI . 'js/customizer.js', array('customize-preview'), AQUALUXE_VERSION, true);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');

/**
 * Sanitize checkbox
 *
 * @param bool $checked
 * @return bool
 */
function aqualuxe_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

/**
 * Sanitize select
 *
 * @param string $input
 * @param WP_Customize_Setting $setting
 * @return string
 */
function aqualuxe_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize multi select
 *
 * @param array $input
 * @return array
 */
function aqualuxe_sanitize_multi_select($input) {
    if (!is_array($input)) {
        return array();
    }
    
    $valid_input = array();
    
    foreach ($input as $value) {
        $valid_input[] = sanitize_key($value);
    }
    
    return $valid_input;
}

/**
 * Multi checkbox control class
 */
if (class_exists('WP_Customize_Control')) {
    class AquaLuxe_Customize_Multi_Checkbox_Control extends WP_Customize_Control {
        /**
         * Control type
         *
         * @var string
         */
        public $type = 'multi-checkbox';
        
        /**
         * Render control content
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
            ?>
            <ul>
                <?php foreach ($this->choices as $value => $label) : ?>
                    <li>
                        <label>
                            <input type="checkbox" value="<?php echo esc_attr($value); ?>" <?php checked(in_array($value, $multi_values)); ?> />
                            <?php echo esc_html($label); ?>
                        </label>
                    </li>
                <?php endforeach; ?>
            </ul>
            <input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr(implode(',', $multi_values)); ?>" />
            <script>
                jQuery(document).ready(function($) {
                    "use strict";
                    
                    var control = $('#<?php echo esc_attr($this->id); ?>');
                    
                    control.find('input[type="checkbox"]').on('change', function() {
                        var values = [];
                        
                        control.find('input[type="checkbox"]:checked').each(function() {
                            values.push($(this).val());
                        });
                        
                        control.find('input[type="hidden"]').val(values.join(',')).trigger('change');
                    });
                });
            </script>
            <?php
        }
    }
}