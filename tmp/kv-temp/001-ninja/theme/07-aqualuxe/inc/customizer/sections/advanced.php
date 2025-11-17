<?php
/**
 * Advanced Customizer Settings
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Add advanced settings to the Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_advanced($wp_customize) {
    // Add Advanced section
    $wp_customize->add_section('aqualuxe_advanced', array(
        'title' => esc_html__('Advanced Settings', 'aqualuxe'),
        'priority' => 120,
    ));

    // Performance Settings
    $wp_customize->add_setting('aqualuxe_advanced_performance_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_advanced_performance_heading', array(
        'label' => esc_html__('Performance Settings', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'priority' => 10,
    )));

    // Enable CSS Minification
    $wp_customize->add_setting('aqualuxe_enable_css_minification', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_css_minification', array(
        'label' => esc_html__('Enable CSS Minification', 'aqualuxe'),
        'description' => esc_html__('Minify CSS files to improve page load speed.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'priority' => 20,
    )));

    // Enable JS Minification
    $wp_customize->add_setting('aqualuxe_enable_js_minification', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_js_minification', array(
        'label' => esc_html__('Enable JS Minification', 'aqualuxe'),
        'description' => esc_html__('Minify JavaScript files to improve page load speed.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'priority' => 30,
    )));

    // Enable Lazy Loading
    $wp_customize->add_setting('aqualuxe_enable_lazy_loading', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_lazy_loading', array(
        'label' => esc_html__('Enable Lazy Loading', 'aqualuxe'),
        'description' => esc_html__('Lazy load images and iframes to improve page load speed.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'priority' => 40,
    )));

    // Enable Critical CSS
    $wp_customize->add_setting('aqualuxe_enable_critical_css', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_critical_css', array(
        'label' => esc_html__('Enable Critical CSS', 'aqualuxe'),
        'description' => esc_html__('Inline critical CSS to improve page load speed.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'priority' => 50,
    )));

    // Enable Browser Caching
    $wp_customize->add_setting('aqualuxe_enable_browser_caching', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_browser_caching', array(
        'label' => esc_html__('Enable Browser Caching', 'aqualuxe'),
        'description' => esc_html__('Add browser caching headers to improve page load speed.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'priority' => 60,
    )));

    // Enable GZIP Compression
    $wp_customize->add_setting('aqualuxe_enable_gzip_compression', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_gzip_compression', array(
        'label' => esc_html__('Enable GZIP Compression', 'aqualuxe'),
        'description' => esc_html__('Compress files to improve page load speed.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'priority' => 70,
    )));

    // Security Settings
    $wp_customize->add_setting('aqualuxe_advanced_security_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_advanced_security_heading', array(
        'label' => esc_html__('Security Settings', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'priority' => 80,
    )));

    // Enable Content Security Policy
    $wp_customize->add_setting('aqualuxe_enable_content_security_policy', array(
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_content_security_policy', array(
        'label' => esc_html__('Enable Content Security Policy', 'aqualuxe'),
        'description' => esc_html__('Add Content Security Policy headers to improve security.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'priority' => 90,
    )));

    // Enable Feature Policy
    $wp_customize->add_setting('aqualuxe_enable_feature_policy', array(
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_feature_policy', array(
        'label' => esc_html__('Enable Feature Policy', 'aqualuxe'),
        'description' => esc_html__('Add Feature Policy headers to improve security.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'priority' => 100,
    )));

    // Enable HSTS
    $wp_customize->add_setting('aqualuxe_enable_hsts', array(
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_hsts', array(
        'label' => esc_html__('Enable HSTS', 'aqualuxe'),
        'description' => esc_html__('Add HTTP Strict Transport Security headers to improve security.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'priority' => 110,
    )));

    // Disable XML-RPC
    $wp_customize->add_setting('aqualuxe_disable_xmlrpc', array(
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_disable_xmlrpc', array(
        'label' => esc_html__('Disable XML-RPC', 'aqualuxe'),
        'description' => esc_html__('Disable XML-RPC functionality to improve security.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'priority' => 120,
    )));

    // Hide WordPress Version
    $wp_customize->add_setting('aqualuxe_hide_wp_version', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_hide_wp_version', array(
        'label' => esc_html__('Hide WordPress Version', 'aqualuxe'),
        'description' => esc_html__('Remove WordPress version from HTML source to improve security.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'priority' => 130,
    )));

    // SEO Settings
    $wp_customize->add_setting('aqualuxe_advanced_seo_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_advanced_seo_heading', array(
        'label' => esc_html__('SEO Settings', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'priority' => 140,
    )));

    // Enable Schema Markup
    $wp_customize->add_setting('aqualuxe_enable_schema_markup', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_schema_markup', array(
        'label' => esc_html__('Enable Schema Markup', 'aqualuxe'),
        'description' => esc_html__('Add schema.org markup to improve SEO.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'priority' => 150,
    )));

    // Enable Open Graph
    $wp_customize->add_setting('aqualuxe_enable_open_graph', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_open_graph', array(
        'label' => esc_html__('Enable Open Graph', 'aqualuxe'),
        'description' => esc_html__('Add Open Graph meta tags for better social sharing.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'priority' => 160,
    )));

    // Enable Twitter Cards
    $wp_customize->add_setting('aqualuxe_enable_twitter_cards', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_twitter_cards', array(
        'label' => esc_html__('Enable Twitter Cards', 'aqualuxe'),
        'description' => esc_html__('Add Twitter Card meta tags for better social sharing.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'priority' => 170,
    )));

    // Enable Breadcrumbs
    $wp_customize->add_setting('aqualuxe_enable_breadcrumbs', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_breadcrumbs', array(
        'label' => esc_html__('Enable Breadcrumbs', 'aqualuxe'),
        'description' => esc_html__('Display breadcrumb navigation on pages.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'priority' => 180,
    )));

    // Code Injection
    $wp_customize->add_setting('aqualuxe_advanced_code_injection_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_advanced_code_injection_heading', array(
        'label' => esc_html__('Code Injection', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'priority' => 190,
    )));

    // Header Code
    $wp_customize->add_setting('aqualuxe_header_code', array(
        'default' => '',
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('aqualuxe_header_code', array(
        'label' => esc_html__('Header Code', 'aqualuxe'),
        'description' => esc_html__('Add custom code to the header. This will be added inside the head tag.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'type' => 'textarea',
        'priority' => 200,
    ));

    // Footer Code
    $wp_customize->add_setting('aqualuxe_footer_code', array(
        'default' => '',
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('aqualuxe_footer_code', array(
        'label' => esc_html__('Footer Code', 'aqualuxe'),
        'description' => esc_html__('Add custom code to the footer. This will be added before the closing body tag.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'type' => 'textarea',
        'priority' => 210,
    ));

    // Custom CSS
    $wp_customize->add_setting('aqualuxe_custom_css', array(
        'default' => '',
        'sanitize_callback' => 'wp_strip_all_tags',
    ));

    $wp_customize->add_control('aqualuxe_custom_css', array(
        'label' => esc_html__('Custom CSS', 'aqualuxe'),
        'description' => esc_html__('Add your custom CSS here.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'type' => 'textarea',
        'priority' => 220,
    ));

    // Custom JavaScript
    $wp_customize->add_setting('aqualuxe_custom_js', array(
        'default' => '',
        'sanitize_callback' => 'wp_strip_all_tags',
    ));

    $wp_customize->add_control('aqualuxe_custom_js', array(
        'label' => esc_html__('Custom JavaScript', 'aqualuxe'),
        'description' => esc_html__('Add your custom JavaScript here.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'type' => 'textarea',
        'priority' => 230,
    ));

    // Maintenance Mode
    $wp_customize->add_setting('aqualuxe_advanced_maintenance_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_advanced_maintenance_heading', array(
        'label' => esc_html__('Maintenance Mode', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'priority' => 240,
    )));

    // Enable Maintenance Mode
    $wp_customize->add_setting('aqualuxe_enable_maintenance_mode', array(
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_maintenance_mode', array(
        'label' => esc_html__('Enable Maintenance Mode', 'aqualuxe'),
        'description' => esc_html__('Show a maintenance page to visitors while you work on your site.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'priority' => 250,
    )));

    // Maintenance Mode Message
    $wp_customize->add_setting('aqualuxe_maintenance_message', array(
        'default' => esc_html__('We are currently performing scheduled maintenance. Please check back soon.', 'aqualuxe'),
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('aqualuxe_maintenance_message', array(
        'label' => esc_html__('Maintenance Message', 'aqualuxe'),
        'description' => esc_html__('The message to display during maintenance mode.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'type' => 'textarea',
        'priority' => 260,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_maintenance_mode', false);
        },
    ));

    // Maintenance Mode Background
    $wp_customize->add_setting('aqualuxe_maintenance_background', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aqualuxe_maintenance_background', array(
        'label' => esc_html__('Maintenance Background', 'aqualuxe'),
        'description' => esc_html__('Upload a background image for the maintenance page.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'mime_type' => 'image',
        'priority' => 270,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_maintenance_mode', false);
        },
    )));

    // Maintenance Mode Logo
    $wp_customize->add_setting('aqualuxe_maintenance_logo', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aqualuxe_maintenance_logo', array(
        'label' => esc_html__('Maintenance Logo', 'aqualuxe'),
        'description' => esc_html__('Upload a logo for the maintenance page.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'mime_type' => 'image',
        'priority' => 280,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_maintenance_mode', false);
        },
    )));

    // Maintenance Mode Countdown
    $wp_customize->add_setting('aqualuxe_enable_maintenance_countdown', array(
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_maintenance_countdown', array(
        'label' => esc_html__('Enable Countdown Timer', 'aqualuxe'),
        'description' => esc_html__('Show a countdown timer on the maintenance page.', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'priority' => 290,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_maintenance_mode', false);
        },
    )));

    // Maintenance Mode End Date
    $wp_customize->add_setting('aqualuxe_maintenance_end_date', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_maintenance_end_date', array(
        'label' => esc_html__('Maintenance End Date', 'aqualuxe'),
        'description' => esc_html__('Enter the date when maintenance will end (YYYY-MM-DD HH:MM:SS).', 'aqualuxe'),
        'section' => 'aqualuxe_advanced',
        'type' => 'text',
        'priority' => 300,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_maintenance_mode', false) && get_theme_mod('aqualuxe_enable_maintenance_countdown', false);
        },
    ));
}
add_action('customize_register', 'aqualuxe_customize_register_advanced');