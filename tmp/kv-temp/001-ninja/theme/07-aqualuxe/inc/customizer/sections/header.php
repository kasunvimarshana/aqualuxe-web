<?php
/**
 * Header Customizer Settings
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Add header settings to the Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_header($wp_customize) {
    // Add Header section
    $wp_customize->add_section('aqualuxe_header', array(
        'title' => esc_html__('Header Settings', 'aqualuxe'),
        'priority' => 30,
    ));

    // Header Style
    $wp_customize->add_setting('aqualuxe_header_style', array(
        'default' => 'default',
        'transport' => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Radio_Image($wp_customize, 'aqualuxe_header_style', array(
        'label' => esc_html__('Header Style', 'aqualuxe'),
        'description' => esc_html__('Select the header layout style.', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'priority' => 10,
        'choices' => array(
            'default' => array(
                'label' => esc_html__('Default', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/header-default.png',
            ),
            'centered' => array(
                'label' => esc_html__('Centered', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/header-centered.png',
            ),
            'split' => array(
                'label' => esc_html__('Split', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/header-split.png',
            ),
            'minimal' => array(
                'label' => esc_html__('Minimal', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/header-minimal.png',
            ),
        ),
    )));

    // Sticky Header
    $wp_customize->add_setting('aqualuxe_sticky_header', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_sticky_header', array(
        'label' => esc_html__('Sticky Header', 'aqualuxe'),
        'description' => esc_html__('Keep the header fixed at the top when scrolling.', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'priority' => 20,
    )));

    // Transparent Header
    $wp_customize->add_setting('aqualuxe_transparent_header', array(
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_transparent_header', array(
        'label' => esc_html__('Transparent Header on Homepage', 'aqualuxe'),
        'description' => esc_html__('Make the header transparent on the homepage.', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'priority' => 30,
    )));

    // Header Height
    $wp_customize->add_setting('aqualuxe_header_height', array(
        'default' => 80,
        'transport' => 'postMessage',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Slider($wp_customize, 'aqualuxe_header_height', array(
        'label' => esc_html__('Header Height', 'aqualuxe'),
        'description' => esc_html__('Set the height of the header in pixels.', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'priority' => 40,
        'min' => 60,
        'max' => 200,
        'step' => 1,
        'unit' => 'px',
    )));

    // Logo Size
    $wp_customize->add_setting('aqualuxe_logo_size', array(
        'default' => 50,
        'transport' => 'postMessage',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Slider($wp_customize, 'aqualuxe_logo_size', array(
        'label' => esc_html__('Logo Size', 'aqualuxe'),
        'description' => esc_html__('Set the maximum height of the logo in pixels.', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'priority' => 50,
        'min' => 20,
        'max' => 150,
        'step' => 1,
        'unit' => 'px',
    )));

    // Header Padding
    $wp_customize->add_setting('aqualuxe_header_padding', array(
        'default' => json_encode(array(
            'top' => '10',
            'right' => '20',
            'bottom' => '10',
            'left' => '20',
            'unit' => 'px',
        )),
        'transport' => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_dimensions',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Dimensions($wp_customize, 'aqualuxe_header_padding', array(
        'label' => esc_html__('Header Padding', 'aqualuxe'),
        'description' => esc_html__('Set the padding for the header.', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'priority' => 60,
    )));

    // Header Background Color
    $wp_customize->add_setting('aqualuxe_header_bg_color', array(
        'default' => '#ffffff',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_header_bg_color', array(
        'label' => esc_html__('Header Background Color', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'priority' => 70,
    )));

    // Header Text Color
    $wp_customize->add_setting('aqualuxe_header_text_color', array(
        'default' => '#1f2937',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    )));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_header_text_color', array(
        'label' => esc_html__('Header Text Color', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'priority' => 80,
    )));

    // Show Search
    $wp_customize->add_setting('aqualuxe_show_header_search', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_header_search', array(
        'label' => esc_html__('Show Search Icon', 'aqualuxe'),
        'description' => esc_html__('Display a search icon in the header.', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'priority' => 90,
    )));

    // Show Cart
    $wp_customize->add_setting('aqualuxe_show_header_cart', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_header_cart', array(
        'label' => esc_html__('Show Cart Icon', 'aqualuxe'),
        'description' => esc_html__('Display a shopping cart icon in the header.', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'priority' => 100,
        'active_callback' => function() {
            return class_exists('WooCommerce');
        },
    )));

    // Show Account
    $wp_customize->add_setting('aqualuxe_show_header_account', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_header_account', array(
        'label' => esc_html__('Show Account Icon', 'aqualuxe'),
        'description' => esc_html__('Display an account icon in the header.', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'priority' => 110,
        'active_callback' => function() {
            return class_exists('WooCommerce');
        },
    )));

    // Show Wishlist
    $wp_customize->add_setting('aqualuxe_show_header_wishlist', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_header_wishlist', array(
        'label' => esc_html__('Show Wishlist Icon', 'aqualuxe'),
        'description' => esc_html__('Display a wishlist icon in the header.', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'priority' => 120,
        'active_callback' => function() {
            return class_exists('WooCommerce') && get_theme_mod('aqualuxe_enable_wishlist', true);
        },
    )));

    // Top Bar
    $wp_customize->add_setting('aqualuxe_enable_top_bar', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_top_bar', array(
        'label' => esc_html__('Enable Top Bar', 'aqualuxe'),
        'description' => esc_html__('Display a top bar above the header.', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'priority' => 130,
    )));

    // Top Bar Content
    $wp_customize->add_setting('aqualuxe_top_bar_content', array(
        'default' => esc_html__('Free shipping on orders over $100', 'aqualuxe'),
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('aqualuxe_top_bar_content', array(
        'label' => esc_html__('Top Bar Content', 'aqualuxe'),
        'description' => esc_html__('Enter the content for the top bar.', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'type' => 'textarea',
        'priority' => 140,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_top_bar', true);
        },
    ));

    // Top Bar Background Color
    $wp_customize->add_setting('aqualuxe_top_bar_bg_color', array(
        'default' => '#0ea5e9',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_top_bar_bg_color', array(
        'label' => esc_html__('Top Bar Background Color', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'priority' => 150,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_top_bar', true);
        },
    )));

    // Top Bar Text Color
    $wp_customize->add_setting('aqualuxe_top_bar_text_color', array(
        'default' => '#ffffff',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_top_bar_text_color', array(
        'label' => esc_html__('Top Bar Text Color', 'aqualuxe'),
        'section' => 'aqualuxe_header',
        'priority' => 160,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_top_bar', true);
        },
    )));
}
add_action('customize_register', 'aqualuxe_customize_register_header');