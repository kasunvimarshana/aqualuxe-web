<?php
/**
 * Colors Customizer Section
 *
 * @package AquaLuxe
 */

/**
 * Add color settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_colors($wp_customize) {
    // Add Colors section
    $wp_customize->add_section(
        'aqualuxe_colors',
        array(
            'title'    => esc_html__('Colors', 'aqualuxe'),
            'priority' => 60,
        )
    );

    // Primary Color
    $wp_customize->add_setting(
        'aqualuxe_primary_color',
        array(
            'default'           => '#0073aa',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_primary_color',
            array(
                'label'   => esc_html__('Primary Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Secondary Color
    $wp_customize->add_setting(
        'aqualuxe_secondary_color',
        array(
            'default'           => '#005177',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_secondary_color',
            array(
                'label'   => esc_html__('Secondary Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Dark Blue Color
    $wp_customize->add_setting(
        'aqualuxe_dark_blue_color',
        array(
            'default'           => '#1e3a8a',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_blue_color',
            array(
                'label'   => esc_html__('Dark Blue Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Light Blue Color
    $wp_customize->add_setting(
        'aqualuxe_light_blue_color',
        array(
            'default'           => '#bfdbfe',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_light_blue_color',
            array(
                'label'   => esc_html__('Light Blue Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Teal Color
    $wp_customize->add_setting(
        'aqualuxe_teal_color',
        array(
            'default'           => '#14b8a6',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_teal_color',
            array(
                'label'   => esc_html__('Teal Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Dark Color
    $wp_customize->add_setting(
        'aqualuxe_dark_color',
        array(
            'default'           => '#111827',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_color',
            array(
                'label'   => esc_html__('Dark Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Light Color
    $wp_customize->add_setting(
        'aqualuxe_light_color',
        array(
            'default'           => '#f9fafb',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_light_color',
            array(
                'label'   => esc_html__('Light Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Body Background Color
    $wp_customize->add_setting(
        'aqualuxe_body_background_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_body_background_color',
            array(
                'label'   => esc_html__('Body Background Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Body Text Color
    $wp_customize->add_setting(
        'aqualuxe_body_text_color',
        array(
            'default'           => '#333333',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_body_text_color',
            array(
                'label'   => esc_html__('Body Text Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Heading Color
    $wp_customize->add_setting(
        'aqualuxe_heading_color',
        array(
            'default'           => '#111827',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_heading_color',
            array(
                'label'   => esc_html__('Heading Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Link Color
    $wp_customize->add_setting(
        'aqualuxe_link_color',
        array(
            'default'           => '#0073aa',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_link_color',
            array(
                'label'   => esc_html__('Link Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Link Hover Color
    $wp_customize->add_setting(
        'aqualuxe_link_hover_color',
        array(
            'default'           => '#005177',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_link_hover_color',
            array(
                'label'   => esc_html__('Link Hover Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Button Background Color
    $wp_customize->add_setting(
        'aqualuxe_button_background_color',
        array(
            'default'           => '#0073aa',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_button_background_color',
            array(
                'label'   => esc_html__('Button Background Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Button Text Color
    $wp_customize->add_setting(
        'aqualuxe_button_text_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_button_text_color',
            array(
                'label'   => esc_html__('Button Text Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Button Hover Background Color
    $wp_customize->add_setting(
        'aqualuxe_button_hover_background_color',
        array(
            'default'           => '#005177',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_button_hover_background_color',
            array(
                'label'   => esc_html__('Button Hover Background Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Button Hover Text Color
    $wp_customize->add_setting(
        'aqualuxe_button_hover_text_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_button_hover_text_color',
            array(
                'label'   => esc_html__('Button Hover Text Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Input Background Color
    $wp_customize->add_setting(
        'aqualuxe_input_background_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_input_background_color',
            array(
                'label'   => esc_html__('Input Background Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Input Text Color
    $wp_customize->add_setting(
        'aqualuxe_input_text_color',
        array(
            'default'           => '#333333',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_input_text_color',
            array(
                'label'   => esc_html__('Input Text Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Input Border Color
    $wp_customize->add_setting(
        'aqualuxe_input_border_color',
        array(
            'default'           => '#dddddd',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_input_border_color',
            array(
                'label'   => esc_html__('Input Border Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Input Focus Border Color
    $wp_customize->add_setting(
        'aqualuxe_input_focus_border_color',
        array(
            'default'           => '#0073aa',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_input_focus_border_color',
            array(
                'label'   => esc_html__('Input Focus Border Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Dark Mode Settings
    $wp_customize->add_setting(
        'aqualuxe_default_color_scheme',
        array(
            'default'           => 'light',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_default_color_scheme',
        array(
            'label'   => esc_html__('Default Color Scheme', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'type'    => 'select',
            'choices' => array(
                'light' => esc_html__('Light', 'aqualuxe'),
                'dark'  => esc_html__('Dark', 'aqualuxe'),
            ),
        )
    );

    // Dark Mode Colors
    $wp_customize->add_setting(
        'aqualuxe_dark_mode_background',
        array(
            'default'           => '#111827',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_mode_background',
            array(
                'label'   => esc_html__('Dark Mode Background Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_dark_mode_text_color',
        array(
            'default'           => '#f9fafb',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_mode_text_color',
            array(
                'label'   => esc_html__('Dark Mode Text Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_dark_mode_link_color',
        array(
            'default'           => '#bfdbfe',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_mode_link_color',
            array(
                'label'   => esc_html__('Dark Mode Link Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_dark_mode_heading_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_mode_heading_color',
            array(
                'label'   => esc_html__('Dark Mode Heading Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_dark_mode_input_background',
        array(
            'default'           => '#1f2937',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_mode_input_background',
            array(
                'label'   => esc_html__('Dark Mode Input Background Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_dark_mode_input_text',
        array(
            'default'           => '#f9fafb',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_mode_input_text',
            array(
                'label'   => esc_html__('Dark Mode Input Text Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_dark_mode_input_border',
        array(
            'default'           => '#374151',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_mode_input_border',
            array(
                'label'   => esc_html__('Dark Mode Input Border Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );
}
add_action('customize_register', 'aqualuxe_customize_register_colors');