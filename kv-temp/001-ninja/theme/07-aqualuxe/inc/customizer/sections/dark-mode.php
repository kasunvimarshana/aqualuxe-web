<?php
/**
 * Dark Mode Customizer Settings
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Add dark mode settings to the Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_dark_mode($wp_customize) {
    // Add Dark Mode section
    $wp_customize->add_section('aqualuxe_dark_mode', array(
        'title' => esc_html__('Dark Mode', 'aqualuxe'),
        'priority' => 90,
    ));

    // Enable Dark Mode
    $wp_customize->add_setting('aqualuxe_enable_dark_mode', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_dark_mode', array(
        'label' => esc_html__('Enable Dark Mode', 'aqualuxe'),
        'description' => esc_html__('Enable dark mode toggle for your site.', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'priority' => 10,
    )));

    // Dark Mode Default
    $wp_customize->add_setting('aqualuxe_dark_mode_default', array(
        'default' => 'system',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_dark_mode_default', array(
        'label' => esc_html__('Default Mode', 'aqualuxe'),
        'description' => esc_html__('Select the default color mode for new visitors.', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'type' => 'select',
        'choices' => array(
            'light' => esc_html__('Light Mode', 'aqualuxe'),
            'dark' => esc_html__('Dark Mode', 'aqualuxe'),
            'system' => esc_html__('System Preference', 'aqualuxe'),
        ),
        'priority' => 20,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    ));

    // Toggle Position
    $wp_customize->add_setting('aqualuxe_dark_mode_toggle_position', array(
        'default' => 'header',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_dark_mode_toggle_position', array(
        'label' => esc_html__('Toggle Position', 'aqualuxe'),
        'description' => esc_html__('Select where to display the dark mode toggle.', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'type' => 'select',
        'choices' => array(
            'header' => esc_html__('Header', 'aqualuxe'),
            'fixed' => esc_html__('Fixed Button', 'aqualuxe'),
            'menu' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer', 'aqualuxe'),
        ),
        'priority' => 30,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    ));

    // Toggle Style
    $wp_customize->add_setting('aqualuxe_dark_mode_toggle_style', array(
        'default' => 'switch',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_dark_mode_toggle_style', array(
        'label' => esc_html__('Toggle Style', 'aqualuxe'),
        'description' => esc_html__('Select the style for the dark mode toggle.', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'type' => 'select',
        'choices' => array(
            'switch' => esc_html__('Switch', 'aqualuxe'),
            'icon' => esc_html__('Icon Only', 'aqualuxe'),
            'button' => esc_html__('Button', 'aqualuxe'),
        ),
        'priority' => 40,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    ));

    // Toggle Icon - Light
    $wp_customize->add_setting('aqualuxe_dark_mode_toggle_icon_light', array(
        'default' => 'sun',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_dark_mode_toggle_icon_light', array(
        'label' => esc_html__('Light Mode Icon', 'aqualuxe'),
        'description' => esc_html__('Select the icon to represent light mode.', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'type' => 'select',
        'choices' => array(
            'sun' => esc_html__('Sun', 'aqualuxe'),
            'brightness' => esc_html__('Brightness', 'aqualuxe'),
            'light-bulb' => esc_html__('Light Bulb', 'aqualuxe'),
            'day' => esc_html__('Day', 'aqualuxe'),
        ),
        'priority' => 50,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true) && 
                   (get_theme_mod('aqualuxe_dark_mode_toggle_style', 'switch') === 'icon' || 
                    get_theme_mod('aqualuxe_dark_mode_toggle_style', 'switch') === 'button');
        },
    ));

    // Toggle Icon - Dark
    $wp_customize->add_setting('aqualuxe_dark_mode_toggle_icon_dark', array(
        'default' => 'moon',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_dark_mode_toggle_icon_dark', array(
        'label' => esc_html__('Dark Mode Icon', 'aqualuxe'),
        'description' => esc_html__('Select the icon to represent dark mode.', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'type' => 'select',
        'choices' => array(
            'moon' => esc_html__('Moon', 'aqualuxe'),
            'night' => esc_html__('Night', 'aqualuxe'),
            'stars' => esc_html__('Stars', 'aqualuxe'),
        ),
        'priority' => 60,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true) && 
                   (get_theme_mod('aqualuxe_dark_mode_toggle_style', 'switch') === 'icon' || 
                    get_theme_mod('aqualuxe_dark_mode_toggle_style', 'switch') === 'button');
        },
    ));

    // Toggle Text - Light
    $wp_customize->add_setting('aqualuxe_dark_mode_toggle_text_light', array(
        'default' => esc_html__('Light', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_dark_mode_toggle_text_light', array(
        'label' => esc_html__('Light Mode Text', 'aqualuxe'),
        'description' => esc_html__('Text to display for light mode.', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'type' => 'text',
        'priority' => 70,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true) && 
                   get_theme_mod('aqualuxe_dark_mode_toggle_style', 'switch') === 'button';
        },
    ));

    // Toggle Text - Dark
    $wp_customize->add_setting('aqualuxe_dark_mode_toggle_text_dark', array(
        'default' => esc_html__('Dark', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_dark_mode_toggle_text_dark', array(
        'label' => esc_html__('Dark Mode Text', 'aqualuxe'),
        'description' => esc_html__('Text to display for dark mode.', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'type' => 'text',
        'priority' => 80,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true) && 
                   get_theme_mod('aqualuxe_dark_mode_toggle_style', 'switch') === 'button';
        },
    ));

    // Remember User Preference
    $wp_customize->add_setting('aqualuxe_dark_mode_remember_preference', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_dark_mode_remember_preference', array(
        'label' => esc_html__('Remember User Preference', 'aqualuxe'),
        'description' => esc_html__('Save user\'s dark mode preference in browser storage.', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'priority' => 90,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Transition Animation
    $wp_customize->add_setting('aqualuxe_dark_mode_transition', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_dark_mode_transition', array(
        'label' => esc_html__('Transition Animation', 'aqualuxe'),
        'description' => esc_html__('Add smooth transition animation when switching between modes.', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'priority' => 100,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Transition Duration
    $wp_customize->add_setting('aqualuxe_dark_mode_transition_duration', array(
        'default' => 300,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Slider($wp_customize, 'aqualuxe_dark_mode_transition_duration', array(
        'label' => esc_html__('Transition Duration', 'aqualuxe'),
        'description' => esc_html__('Duration of the transition animation in milliseconds.', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'priority' => 110,
        'min' => 100,
        'max' => 1000,
        'step' => 50,
        'unit' => 'ms',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true) && 
                   get_theme_mod('aqualuxe_dark_mode_transition', true);
        },
    )));

    // Dark Mode Colors Heading
    $wp_customize->add_setting('aqualuxe_dark_mode_colors_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_dark_mode_colors_heading', array(
        'label' => esc_html__('Dark Mode Colors', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'priority' => 120,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Dark Mode Background Color
    $wp_customize->add_setting('aqualuxe_dark_mode_background_color', array(
        'default' => '#111827',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_background_color', array(
        'label' => esc_html__('Background Color', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'priority' => 130,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Dark Mode Text Color
    $wp_customize->add_setting('aqualuxe_dark_mode_text_color', array(
        'default' => '#f9fafb',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_text_color', array(
        'label' => esc_html__('Text Color', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'priority' => 140,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Dark Mode Heading Color
    $wp_customize->add_setting('aqualuxe_dark_mode_heading_color', array(
        'default' => '#ffffff',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_heading_color', array(
        'label' => esc_html__('Heading Color', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'priority' => 150,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Dark Mode Link Color
    $wp_customize->add_setting('aqualuxe_dark_mode_link_color', array(
        'default' => '#38bdf8',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_link_color', array(
        'label' => esc_html__('Link Color', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'priority' => 160,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Dark Mode Link Hover Color
    $wp_customize->add_setting('aqualuxe_dark_mode_link_hover_color', array(
        'default' => '#7dd3fc',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_link_hover_color', array(
        'label' => esc_html__('Link Hover Color', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'priority' => 170,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Dark Mode Border Color
    $wp_customize->add_setting('aqualuxe_dark_mode_border_color', array(
        'default' => '#374151',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_border_color', array(
        'label' => esc_html__('Border Color', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'priority' => 180,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Dark Mode Input Background Color
    $wp_customize->add_setting('aqualuxe_dark_mode_input_background_color', array(
        'default' => '#1f2937',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_input_background_color', array(
        'label' => esc_html__('Input Background Color', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'priority' => 190,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Dark Mode Input Text Color
    $wp_customize->add_setting('aqualuxe_dark_mode_input_text_color', array(
        'default' => '#f9fafb',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_input_text_color', array(
        'label' => esc_html__('Input Text Color', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'priority' => 200,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Dark Mode Button Background Color
    $wp_customize->add_setting('aqualuxe_dark_mode_button_background_color', array(
        'default' => '#0ea5e9',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_button_background_color', array(
        'label' => esc_html__('Button Background Color', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'priority' => 210,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Dark Mode Button Text Color
    $wp_customize->add_setting('aqualuxe_dark_mode_button_text_color', array(
        'default' => '#ffffff',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_button_text_color', array(
        'label' => esc_html__('Button Text Color', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'priority' => 220,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Dark Mode Advanced Settings
    $wp_customize->add_setting('aqualuxe_dark_mode_advanced_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_dark_mode_advanced_heading', array(
        'label' => esc_html__('Advanced Settings', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'priority' => 230,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Dark Mode for Admin
    $wp_customize->add_setting('aqualuxe_dark_mode_admin', array(
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_dark_mode_admin', array(
        'label' => esc_html__('Dark Mode for Admin', 'aqualuxe'),
        'description' => esc_html__('Enable dark mode for the WordPress admin area.', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'priority' => 240,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Dark Mode Image Adjustments
    $wp_customize->add_setting('aqualuxe_dark_mode_image_adjustments', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_dark_mode_image_adjustments', array(
        'label' => esc_html__('Image Adjustments', 'aqualuxe'),
        'description' => esc_html__('Automatically adjust brightness and contrast of images in dark mode.', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'priority' => 250,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Dark Mode Image Filter
    $wp_customize->add_setting('aqualuxe_dark_mode_image_filter', array(
        'default' => 'brightness(0.8) contrast(1.2)',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_dark_mode_image_filter', array(
        'label' => esc_html__('Image Filter', 'aqualuxe'),
        'description' => esc_html__('CSS filter to apply to images in dark mode.', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'type' => 'text',
        'priority' => 260,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true) && 
                   get_theme_mod('aqualuxe_dark_mode_image_adjustments', true);
        },
    ));

    // Dark Mode Logo
    $wp_customize->add_setting('aqualuxe_dark_mode_logo', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aqualuxe_dark_mode_logo', array(
        'label' => esc_html__('Dark Mode Logo', 'aqualuxe'),
        'description' => esc_html__('Upload a logo to be displayed in dark mode. If not set, the default logo will be used.', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'mime_type' => 'image',
        'priority' => 270,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Dark Mode Custom CSS
    $wp_customize->add_setting('aqualuxe_dark_mode_custom_css', array(
        'default' => '',
        'sanitize_callback' => 'wp_strip_all_tags',
    ));

    $wp_customize->add_control('aqualuxe_dark_mode_custom_css', array(
        'label' => esc_html__('Custom Dark Mode CSS', 'aqualuxe'),
        'description' => esc_html__('Add custom CSS for dark mode. This will be applied only when dark mode is active.', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'type' => 'textarea',
        'priority' => 280,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    ));
}
add_action('customize_register', 'aqualuxe_customize_register_dark_mode');