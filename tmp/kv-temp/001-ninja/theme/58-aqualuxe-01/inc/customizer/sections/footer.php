<?php
/**
 * Footer settings section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add footer settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_footer($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_footer_settings', array(
        'title'       => __('Footer Settings', 'aqualuxe'),
        'description' => __('Customize the footer section', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 30,
    ));

    // Footer columns
    $wp_customize->add_setting('aqualuxe_options[footer_columns]', array(
        'default'           => 4,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_options[footer_columns]', array(
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
    $wp_customize->add_setting('aqualuxe_options[footer_logo]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aqualuxe_options[footer_logo]', array(
        'label'       => __('Footer Logo', 'aqualuxe'),
        'description' => __('Upload a logo for the footer (optional)', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
    )));

    // Copyright text
    $wp_customize->add_setting('aqualuxe_options[copyright_text]', array(
        'default'           => '© ' . date('Y') . ' ' . get_bloginfo('name') . '. ' . __('All rights reserved.', 'aqualuxe'),
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_options[copyright_text]', array(
        'label'       => __('Copyright Text', 'aqualuxe'),
        'description' => __('Enter your copyright text', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
        'type'        => 'textarea',
    ));

    // Footer credits
    $wp_customize->add_setting('aqualuxe_options[footer_credits]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_options[footer_credits]', array(
        'label'       => __('Show Theme Credits', 'aqualuxe'),
        'description' => __('Display "Powered by AquaLuxe" in the footer', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
        'type'        => 'checkbox',
    ));

    // Footer background
    $wp_customize->add_setting('aqualuxe_options[footer_background]', array(
        'default'           => '#0a1a2a',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[footer_background]', array(
        'label'       => __('Footer Background Color', 'aqualuxe'),
        'description' => __('Set the background color for the footer', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
    )));

    // Footer text color
    $wp_customize->add_setting('aqualuxe_options[footer_text_color]', array(
        'default'           => '#ffffff',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[footer_text_color]', array(
        'label'       => __('Footer Text Color', 'aqualuxe'),
        'description' => __('Set the text color for the footer', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
    )));

    // Newsletter in footer
    $wp_customize->add_setting('aqualuxe_options[footer_newsletter]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_options[footer_newsletter]', array(
        'label'       => __('Show Newsletter in Footer', 'aqualuxe'),
        'description' => __('Display a newsletter signup form in the footer', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
        'type'        => 'checkbox',
    ));

    // Newsletter shortcode
    $wp_customize->add_setting('aqualuxe_options[newsletter_shortcode]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_options[newsletter_shortcode]', array(
        'label'       => __('Newsletter Shortcode', 'aqualuxe'),
        'description' => __('Enter a shortcode for your newsletter form (e.g., from Mailchimp)', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
        'type'        => 'text',
        'active_callback' => function() {
            $options = get_option('aqualuxe_options', array());
            return isset($options['footer_newsletter']) && $options['footer_newsletter'];
        },
    ));
    
    // Newsletter title
    $wp_customize->add_setting('aqualuxe_options[newsletter_title]', array(
        'default'           => __('Subscribe to Our Newsletter', 'aqualuxe'),
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_options[newsletter_title]', array(
        'label'       => __('Newsletter Title', 'aqualuxe'),
        'description' => __('Enter the title for the newsletter section', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
        'type'        => 'text',
        'active_callback' => function() {
            $options = get_option('aqualuxe_options', array());
            return isset($options['footer_newsletter']) && $options['footer_newsletter'];
        },
    ));
    
    // Newsletter description
    $wp_customize->add_setting('aqualuxe_options[newsletter_description]', array(
        'default'           => __('Stay updated with our latest products, services, and aquatic care tips.', 'aqualuxe'),
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_options[newsletter_description]', array(
        'label'       => __('Newsletter Description', 'aqualuxe'),
        'description' => __('Enter the description for the newsletter section', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
        'type'        => 'textarea',
        'active_callback' => function() {
            $options = get_option('aqualuxe_options', array());
            return isset($options['footer_newsletter']) && $options['footer_newsletter'];
        },
    ));
    
    // Footer widgets background
    $wp_customize->add_setting('aqualuxe_options[footer_widgets_background]', array(
        'default'           => '#0a1a2a',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[footer_widgets_background]', array(
        'label'       => __('Footer Widgets Background Color', 'aqualuxe'),
        'description' => __('Set the background color for the footer widgets area', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
    )));
    
    // Footer widgets text color
    $wp_customize->add_setting('aqualuxe_options[footer_widgets_text_color]', array(
        'default'           => '#ffffff',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[footer_widgets_text_color]', array(
        'label'       => __('Footer Widgets Text Color', 'aqualuxe'),
        'description' => __('Set the text color for the footer widgets area', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
    )));
    
    // Footer bottom background
    $wp_customize->add_setting('aqualuxe_options[footer_bottom_background]', array(
        'default'           => '#061525',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[footer_bottom_background]', array(
        'label'       => __('Footer Bottom Background Color', 'aqualuxe'),
        'description' => __('Set the background color for the footer bottom area', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
    )));
    
    // Footer bottom text color
    $wp_customize->add_setting('aqualuxe_options[footer_bottom_text_color]', array(
        'default'           => '#ffffff',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[footer_bottom_text_color]', array(
        'label'       => __('Footer Bottom Text Color', 'aqualuxe'),
        'description' => __('Set the text color for the footer bottom area', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
    )));
    
    // Footer padding
    $wp_customize->add_setting('aqualuxe_options[footer_padding]', array(
        'default'           => '60',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_options[footer_padding]', array(
        'label'       => __('Footer Padding (px)', 'aqualuxe'),
        'description' => __('Set the padding for the footer widgets area', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 200,
            'step' => 1,
        ),
    ));
    
    // Footer bottom padding
    $wp_customize->add_setting('aqualuxe_options[footer_bottom_padding]', array(
        'default'           => '20',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_options[footer_bottom_padding]', array(
        'label'       => __('Footer Bottom Padding (px)', 'aqualuxe'),
        'description' => __('Set the padding for the footer bottom area', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
        ),
    ));
    
    // Footer menu
    $wp_customize->add_setting('aqualuxe_options[footer_menu]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_options[footer_menu]', array(
        'label'       => __('Show Footer Menu', 'aqualuxe'),
        'description' => __('Display a menu in the footer bottom area', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
        'type'        => 'checkbox',
    ));
    
    // Footer social icons
    $wp_customize->add_setting('aqualuxe_options[footer_social]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_options[footer_social]', array(
        'label'       => __('Show Social Icons in Footer', 'aqualuxe'),
        'description' => __('Display social media icons in the footer', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_settings',
        'type'        => 'checkbox',
    ));
}
add_action('customize_register', 'aqualuxe_customize_register_footer');