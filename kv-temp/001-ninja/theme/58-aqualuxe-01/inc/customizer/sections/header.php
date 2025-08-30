<?php
/**
 * Header settings section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add header settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_header($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_header_settings', array(
        'title'       => __('Header Settings', 'aqualuxe'),
        'description' => __('Customize the header section', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 20,
    ));

    // Header layout
    $wp_customize->add_setting('aqualuxe_options[header_layout]', array(
        'default'           => 'default',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_options[header_layout]', array(
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
    $wp_customize->add_setting('aqualuxe_options[sticky_header]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_options[sticky_header]', array(
        'label'       => __('Enable Sticky Header', 'aqualuxe'),
        'description' => __('Keep the header visible when scrolling down', 'aqualuxe'),
        'section'     => 'aqualuxe_header_settings',
        'type'        => 'checkbox',
    ));

    // Header top bar
    $wp_customize->add_setting('aqualuxe_options[enable_top_bar]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_options[enable_top_bar]', array(
        'label'       => __('Enable Top Bar', 'aqualuxe'),
        'description' => __('Show a top bar above the main header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_settings',
        'type'        => 'checkbox',
    ));

    // Top bar content
    $wp_customize->add_setting('aqualuxe_options[top_bar_content_left]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('aqualuxe_options[top_bar_content_left]', array(
        'label'       => __('Top Bar Left Content', 'aqualuxe'),
        'description' => __('HTML or text for the left side of the top bar', 'aqualuxe'),
        'section'     => 'aqualuxe_header_settings',
        'type'        => 'textarea',
        'active_callback' => function() {
            $options = get_option('aqualuxe_options', array());
            return isset($options['enable_top_bar']) && $options['enable_top_bar'];
        },
    ));

    $wp_customize->add_setting('aqualuxe_options[top_bar_content_right]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('aqualuxe_options[top_bar_content_right]', array(
        'label'       => __('Top Bar Right Content', 'aqualuxe'),
        'description' => __('HTML or text for the right side of the top bar', 'aqualuxe'),
        'section'     => 'aqualuxe_header_settings',
        'type'        => 'textarea',
        'active_callback' => function() {
            $options = get_option('aqualuxe_options', array());
            return isset($options['enable_top_bar']) && $options['enable_top_bar'];
        },
    ));

    // Top bar background color
    $wp_customize->add_setting('aqualuxe_options[top_bar_background]', array(
        'default'           => '#0a1a2a',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[top_bar_background]', array(
        'label'       => __('Top Bar Background Color', 'aqualuxe'),
        'section'     => 'aqualuxe_header_settings',
        'active_callback' => function() {
            $options = get_option('aqualuxe_options', array());
            return isset($options['enable_top_bar']) && $options['enable_top_bar'];
        },
    )));

    // Top bar text color
    $wp_customize->add_setting('aqualuxe_options[top_bar_text_color]', array(
        'default'           => '#ffffff',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[top_bar_text_color]', array(
        'label'       => __('Top Bar Text Color', 'aqualuxe'),
        'section'     => 'aqualuxe_header_settings',
        'active_callback' => function() {
            $options = get_option('aqualuxe_options', array());
            return isset($options['enable_top_bar']) && $options['enable_top_bar'];
        },
    )));

    // Logo height
    $wp_customize->add_setting('aqualuxe_options[logo_height]', array(
        'default'           => '60',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_options[logo_height]', array(
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

    // Header background color
    $wp_customize->add_setting('aqualuxe_options[header_background]', array(
        'default'           => '#ffffff',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[header_background]', array(
        'label'       => __('Header Background Color', 'aqualuxe'),
        'section'     => 'aqualuxe_header_settings',
    )));

    // Header text color
    $wp_customize->add_setting('aqualuxe_options[header_text_color]', array(
        'default'           => '#333333',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[header_text_color]', array(
        'label'       => __('Header Text Color', 'aqualuxe'),
        'section'     => 'aqualuxe_header_settings',
    )));

    // Search in header
    $wp_customize->add_setting('aqualuxe_options[header_search]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_options[header_search]', array(
        'label'       => __('Show Search in Header', 'aqualuxe'),
        'description' => __('Display a search icon in the header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_settings',
        'type'        => 'checkbox',
    ));

    // Cart in header (if WooCommerce is active)
    if (class_exists('WooCommerce')) {
        $wp_customize->add_setting('aqualuxe_options[header_cart]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));

        $wp_customize->add_control('aqualuxe_options[header_cart]', array(
            'label'       => __('Show Cart in Header', 'aqualuxe'),
            'description' => __('Display a cart icon in the header', 'aqualuxe'),
            'section'     => 'aqualuxe_header_settings',
            'type'        => 'checkbox',
        ));
    }

    // Account in header (if WooCommerce is active)
    if (class_exists('WooCommerce')) {
        $wp_customize->add_setting('aqualuxe_options[header_account]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));

        $wp_customize->add_control('aqualuxe_options[header_account]', array(
            'label'       => __('Show Account in Header', 'aqualuxe'),
            'description' => __('Display an account icon in the header', 'aqualuxe'),
            'section'     => 'aqualuxe_header_settings',
            'type'        => 'checkbox',
        ));
    }
    
    // Header padding
    $wp_customize->add_setting('aqualuxe_options[header_padding]', array(
        'default'           => '20',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_options[header_padding]', array(
        'label'       => __('Header Padding (px)', 'aqualuxe'),
        'description' => __('Set the padding for the header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
        ),
    ));
    
    // Header shadow
    $wp_customize->add_setting('aqualuxe_options[header_shadow]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_options[header_shadow]', array(
        'label'       => __('Enable Header Shadow', 'aqualuxe'),
        'description' => __('Add a shadow effect to the header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_settings',
        'type'        => 'checkbox',
    ));
    
    // Header border
    $wp_customize->add_setting('aqualuxe_options[header_border]', array(
        'default'           => false,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_options[header_border]', array(
        'label'       => __('Enable Header Border', 'aqualuxe'),
        'description' => __('Add a border at the bottom of the header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_settings',
        'type'        => 'checkbox',
    ));
    
    // Header border color
    $wp_customize->add_setting('aqualuxe_options[header_border_color]', array(
        'default'           => '#eeeeee',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[header_border_color]', array(
        'label'       => __('Header Border Color', 'aqualuxe'),
        'section'     => 'aqualuxe_header_settings',
        'active_callback' => function() {
            $options = get_option('aqualuxe_options', array());
            return isset($options['header_border']) && $options['header_border'];
        },
    )));
    
    // Mobile menu breakpoint
    $wp_customize->add_setting('aqualuxe_options[mobile_menu_breakpoint]', array(
        'default'           => '768',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_options[mobile_menu_breakpoint]', array(
        'label'       => __('Mobile Menu Breakpoint (px)', 'aqualuxe'),
        'description' => __('Set the screen width at which the mobile menu appears', 'aqualuxe'),
        'section'     => 'aqualuxe_header_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 320,
            'max'  => 1200,
            'step' => 1,
        ),
    ));
    
    // Mobile menu style
    $wp_customize->add_setting('aqualuxe_options[mobile_menu_style]', array(
        'default'           => 'slide',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_options[mobile_menu_style]', array(
        'label'       => __('Mobile Menu Style', 'aqualuxe'),
        'description' => __('Select the style for the mobile menu', 'aqualuxe'),
        'section'     => 'aqualuxe_header_settings',
        'type'        => 'select',
        'choices'     => array(
            'slide'    => __('Slide from Side', 'aqualuxe'),
            'dropdown' => __('Dropdown', 'aqualuxe'),
            'fullscreen' => __('Fullscreen', 'aqualuxe'),
        ),
    ));
}
add_action('customize_register', 'aqualuxe_customize_register_header');