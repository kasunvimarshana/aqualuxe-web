<?php
/**
 * AquaLuxe Colors Customizer Settings
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Colors customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager
 */
function aqualuxe_customizer_colors_settings($wp_customize) {
    // Colors Panel
    $wp_customize->add_panel('aqualuxe_colors_panel', [
        'title'       => esc_html__('Colors', 'aqualuxe'),
        'description' => esc_html__('Colors settings', 'aqualuxe'),
        'priority'    => 50,
    ]);

    // General Colors Section
    $wp_customize->add_section('aqualuxe_general_colors_section', [
        'title'       => esc_html__('General', 'aqualuxe'),
        'description' => esc_html__('General colors settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_colors_panel',
        'priority'    => 10,
    ]);

    // Primary Color
    $wp_customize->add_setting('primary_color', [
        'default'           => '#0077b6',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', [
        'label'       => esc_html__('Primary Color', 'aqualuxe'),
        'description' => esc_html__('Set the primary color', 'aqualuxe'),
        'section'     => 'aqualuxe_general_colors_section',
    ]));

    // Secondary Color
    $wp_customize->add_setting('secondary_color', [
        'default'           => '#00b4d8',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_color', [
        'label'       => esc_html__('Secondary Color', 'aqualuxe'),
        'description' => esc_html__('Set the secondary color', 'aqualuxe'),
        'section'     => 'aqualuxe_general_colors_section',
    ]));

    // Accent Color
    $wp_customize->add_setting('accent_color', [
        'default'           => '#48cae4',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'accent_color', [
        'label'       => esc_html__('Accent Color', 'aqualuxe'),
        'description' => esc_html__('Set the accent color', 'aqualuxe'),
        'section'     => 'aqualuxe_general_colors_section',
    ]));

    // Dark Color
    $wp_customize->add_setting('dark_color', [
        'default'           => '#03045e',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'dark_color', [
        'label'       => esc_html__('Dark Color', 'aqualuxe'),
        'description' => esc_html__('Set the dark color', 'aqualuxe'),
        'section'     => 'aqualuxe_general_colors_section',
    ]));

    // Light Color
    $wp_customize->add_setting('light_color', [
        'default'           => '#caf0f8',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'light_color', [
        'label'       => esc_html__('Light Color', 'aqualuxe'),
        'description' => esc_html__('Set the light color', 'aqualuxe'),
        'section'     => 'aqualuxe_general_colors_section',
    ]));

    // Gold Color
    $wp_customize->add_setting('gold_color', [
        'default'           => '#d4af37',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'gold_color', [
        'label'       => esc_html__('Gold Color', 'aqualuxe'),
        'description' => esc_html__('Set the gold color', 'aqualuxe'),
        'section'     => 'aqualuxe_general_colors_section',
    ]));

    // Body Colors Section
    $wp_customize->add_section('aqualuxe_body_colors_section', [
        'title'       => esc_html__('Body', 'aqualuxe'),
        'description' => esc_html__('Body colors settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_colors_panel',
        'priority'    => 20,
    ]);

    // Body Background Color
    $wp_customize->add_setting('body_background_color', [
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'body_background_color', [
        'label'       => esc_html__('Body Background Color', 'aqualuxe'),
        'description' => esc_html__('Set the background color of the body', 'aqualuxe'),
        'section'     => 'aqualuxe_body_colors_section',
    ]));

    // Body Text Color
    $wp_customize->add_setting('body_text_color', [
        'default'           => '#333333',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'body_text_color', [
        'label'       => esc_html__('Body Text Color', 'aqualuxe'),
        'description' => esc_html__('Set the text color of the body', 'aqualuxe'),
        'section'     => 'aqualuxe_body_colors_section',
    ]));

    // Body Link Color
    $wp_customize->add_setting('body_link_color', [
        'default'           => '#0077b6',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'body_link_color', [
        'label'       => esc_html__('Body Link Color', 'aqualuxe'),
        'description' => esc_html__('Set the link color of the body', 'aqualuxe'),
        'section'     => 'aqualuxe_body_colors_section',
    ]));

    // Body Link Hover Color
    $wp_customize->add_setting('body_link_hover_color', [
        'default'           => '#03045e',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'body_link_hover_color', [
        'label'       => esc_html__('Body Link Hover Color', 'aqualuxe'),
        'description' => esc_html__('Set the link hover color of the body', 'aqualuxe'),
        'section'     => 'aqualuxe_body_colors_section',
    ]));

    // Headings Colors Section
    $wp_customize->add_section('aqualuxe_headings_colors_section', [
        'title'       => esc_html__('Headings', 'aqualuxe'),
        'description' => esc_html__('Headings colors settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_colors_panel',
        'priority'    => 30,
    ]);

    // Heading Color
    $wp_customize->add_setting('heading_color', [
        'default'           => '#000814',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'heading_color', [
        'label'       => esc_html__('Heading Color', 'aqualuxe'),
        'description' => esc_html__('Set the color of headings', 'aqualuxe'),
        'section'     => 'aqualuxe_headings_colors_section',
    ]));

    // Individual Headings
    $headings = [
        'h1' => esc_html__('Heading 1 (H1)', 'aqualuxe'),
        'h2' => esc_html__('Heading 2 (H2)', 'aqualuxe'),
        'h3' => esc_html__('Heading 3 (H3)', 'aqualuxe'),
        'h4' => esc_html__('Heading 4 (H4)', 'aqualuxe'),
        'h5' => esc_html__('Heading 5 (H5)', 'aqualuxe'),
        'h6' => esc_html__('Heading 6 (H6)', 'aqualuxe'),
    ];

    foreach ($headings as $heading => $label) {
        // Add separator
        $wp_customize->add_setting($heading . '_color_separator', [
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control(new AquaLuxe_Separator_Control($wp_customize, $heading . '_color_separator', [
            'section' => 'aqualuxe_headings_colors_section',
        ]));

        // Add heading
        $wp_customize->add_setting($heading . '_color_heading', [
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control(new AquaLuxe_Heading_Control($wp_customize, $heading . '_color_heading', [
            'label'   => $label,
            'section' => 'aqualuxe_headings_colors_section',
        ]));

        // Heading Color
        $wp_customize->add_setting($heading . '_color', [
            'default'           => '#000814',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $heading . '_color', [
            'label'       => esc_html__('Color', 'aqualuxe'),
            'section'     => 'aqualuxe_headings_colors_section',
        ]));
    }

    // Buttons Colors Section
    $wp_customize->add_section('aqualuxe_buttons_colors_section', [
        'title'       => esc_html__('Buttons', 'aqualuxe'),
        'description' => esc_html__('Buttons colors settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_colors_panel',
        'priority'    => 40,
    ]);

    // Button Background Color
    $wp_customize->add_setting('button_background_color', [
        'default'           => '#0077b6',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_background_color', [
        'label'       => esc_html__('Button Background Color', 'aqualuxe'),
        'description' => esc_html__('Set the background color of buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_colors_section',
    ]));

    // Button Text Color
    $wp_customize->add_setting('button_text_color', [
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_text_color', [
        'label'       => esc_html__('Button Text Color', 'aqualuxe'),
        'description' => esc_html__('Set the text color of buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_colors_section',
    ]));

    // Button Border Color
    $wp_customize->add_setting('button_border_color', [
        'default'           => '#0077b6',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_border_color', [
        'label'       => esc_html__('Button Border Color', 'aqualuxe'),
        'description' => esc_html__('Set the border color of buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_colors_section',
    ]));

    // Button Hover Background Color
    $wp_customize->add_setting('button_hover_background_color', [
        'default'           => '#03045e',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_hover_background_color', [
        'label'       => esc_html__('Button Hover Background Color', 'aqualuxe'),
        'description' => esc_html__('Set the hover background color of buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_colors_section',
    ]));

    // Button Hover Text Color
    $wp_customize->add_setting('button_hover_text_color', [
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_hover_text_color', [
        'label'       => esc_html__('Button Hover Text Color', 'aqualuxe'),
        'description' => esc_html__('Set the hover text color of buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_colors_section',
    ]));

    // Button Hover Border Color
    $wp_customize->add_setting('button_hover_border_color', [
        'default'           => '#03045e',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_hover_border_color', [
        'label'       => esc_html__('Button Hover Border Color', 'aqualuxe'),
        'description' => esc_html__('Set the hover border color of buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_colors_section',
    ]));

    // Add separator
    $wp_customize->add_setting('button_alt_separator', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control(new AquaLuxe_Separator_Control($wp_customize, 'button_alt_separator', [
        'section' => 'aqualuxe_buttons_colors_section',
    ]));

    // Add heading for alt buttons
    $wp_customize->add_setting('button_alt_heading', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control(new AquaLuxe_Heading_Control($wp_customize, 'button_alt_heading', [
        'label'   => esc_html__('Alternative Buttons', 'aqualuxe'),
        'section' => 'aqualuxe_buttons_colors_section',
    ]));

    // Alt Button Background Color
    $wp_customize->add_setting('button_alt_background_color', [
        'default'           => '#03045e',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_alt_background_color', [
        'label'       => esc_html__('Alt Button Background Color', 'aqualuxe'),
        'description' => esc_html__('Set the background color of alternative buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_colors_section',
    ]));

    // Alt Button Text Color
    $wp_customize->add_setting('button_alt_text_color', [
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_alt_text_color', [
        'label'       => esc_html__('Alt Button Text Color', 'aqualuxe'),
        'description' => esc_html__('Set the text color of alternative buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_colors_section',
    ]));

    // Alt Button Border Color
    $wp_customize->add_setting('button_alt_border_color', [
        'default'           => '#03045e',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_alt_border_color', [
        'label'       => esc_html__('Alt Button Border Color', 'aqualuxe'),
        'description' => esc_html__('Set the border color of alternative buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_colors_section',
    ]));

    // Alt Button Hover Background Color
    $wp_customize->add_setting('button_alt_hover_background_color', [
        'default'           => '#0077b6',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_alt_hover_background_color', [
        'label'       => esc_html__('Alt Button Hover Background Color', 'aqualuxe'),
        'description' => esc_html__('Set the hover background color of alternative buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_colors_section',
    ]));

    // Alt Button Hover Text Color
    $wp_customize->add_setting('button_alt_hover_text_color', [
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_alt_hover_text_color', [
        'label'       => esc_html__('Alt Button Hover Text Color', 'aqualuxe'),
        'description' => esc_html__('Set the hover text color of alternative buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_colors_section',
    ]));

    // Alt Button Hover Border Color
    $wp_customize->add_setting('button_alt_hover_border_color', [
        'default'           => '#0077b6',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_alt_hover_border_color', [
        'label'       => esc_html__('Alt Button Hover Border Color', 'aqualuxe'),
        'description' => esc_html__('Set the hover border color of alternative buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_colors_section',
    ]));

    // Forms Colors Section
    $wp_customize->add_section('aqualuxe_forms_colors_section', [
        'title'       => esc_html__('Forms', 'aqualuxe'),
        'description' => esc_html__('Forms colors settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_colors_panel',
        'priority'    => 50,
    ]);

    // Form Input Background Color
    $wp_customize->add_setting('form_input_background_color', [
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_input_background_color', [
        'label'       => esc_html__('Form Input Background Color', 'aqualuxe'),
        'description' => esc_html__('Set the background color of form inputs', 'aqualuxe'),
        'section'     => 'aqualuxe_forms_colors_section',
    ]));

    // Form Input Text Color
    $wp_customize->add_setting('form_input_text_color', [
        'default'           => '#333333',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_input_text_color', [
        'label'       => esc_html__('Form Input Text Color', 'aqualuxe'),
        'description' => esc_html__('Set the text color of form inputs', 'aqualuxe'),
        'section'     => 'aqualuxe_forms_colors_section',
    ]));

    // Form Input Border Color
    $wp_customize->add_setting('form_input_border_color', [
        'default'           => '#dddddd',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_input_border_color', [
        'label'       => esc_html__('Form Input Border Color', 'aqualuxe'),
        'description' => esc_html__('Set the border color of form inputs', 'aqualuxe'),
        'section'     => 'aqualuxe_forms_colors_section',
    ]));

    // Form Input Focus Background Color
    $wp_customize->add_setting('form_input_focus_background_color', [
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_input_focus_background_color', [
        'label'       => esc_html__('Form Input Focus Background Color', 'aqualuxe'),
        'description' => esc_html__('Set the focus background color of form inputs', 'aqualuxe'),
        'section'     => 'aqualuxe_forms_colors_section',
    ]));

    // Form Input Focus Text Color
    $wp_customize->add_setting('form_input_focus_text_color', [
        'default'           => '#333333',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_input_focus_text_color', [
        'label'       => esc_html__('Form Input Focus Text Color', 'aqualuxe'),
        'description' => esc_html__('Set the focus text color of form inputs', 'aqualuxe'),
        'section'     => 'aqualuxe_forms_colors_section',
    ]));

    // Form Input Focus Border Color
    $wp_customize->add_setting('form_input_focus_border_color', [
        'default'           => '#0077b6',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_input_focus_border_color', [
        'label'       => esc_html__('Form Input Focus Border Color', 'aqualuxe'),
        'description' => esc_html__('Set the focus border color of form inputs', 'aqualuxe'),
        'section'     => 'aqualuxe_forms_colors_section',
    ]));

    // Form Label Color
    $wp_customize->add_setting('form_label_color', [
        'default'           => '#333333',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_label_color', [
        'label'       => esc_html__('Form Label Color', 'aqualuxe'),
        'description' => esc_html__('Set the color of form labels', 'aqualuxe'),
        'section'     => 'aqualuxe_forms_colors_section',
    ]));

    // Form Placeholder Color
    $wp_customize->add_setting('form_placeholder_color', [
        'default'           => '#999999',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_placeholder_color', [
        'label'       => esc_html__('Form Placeholder Color', 'aqualuxe'),
        'description' => esc_html__('Set the color of form placeholders', 'aqualuxe'),
        'section'     => 'aqualuxe_forms_colors_section',
    ]));

    // Form Help Text Color
    $wp_customize->add_setting('form_help_text_color', [
        'default'           => '#666666',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_help_text_color', [
        'label'       => esc_html__('Form Help Text Color', 'aqualuxe'),
        'description' => esc_html__('Set the color of form help text', 'aqualuxe'),
        'section'     => 'aqualuxe_forms_colors_section',
    ]));

    // Form Error Color
    $wp_customize->add_setting('form_error_color', [
        'default'           => '#dc3545',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_error_color', [
        'label'       => esc_html__('Form Error Color', 'aqualuxe'),
        'description' => esc_html__('Set the color of form errors', 'aqualuxe'),
        'section'     => 'aqualuxe_forms_colors_section',
    ]));

    // Form Success Color
    $wp_customize->add_setting('form_success_color', [
        'default'           => '#28a745',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'form_success_color', [
        'label'       => esc_html__('Form Success Color', 'aqualuxe'),
        'description' => esc_html__('Set the color of form success messages', 'aqualuxe'),
        'section'     => 'aqualuxe_forms_colors_section',
    ]));

    // Sidebar Colors Section
    $wp_customize->add_section('aqualuxe_sidebar_colors_section', [
        'title'       => esc_html__('Sidebar', 'aqualuxe'),
        'description' => esc_html__('Sidebar colors settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_colors_panel',
        'priority'    => 60,
    ]);

    // Sidebar Background Color
    $wp_customize->add_setting('sidebar_background_color', [
        'default'           => '#f8f9fa',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'sidebar_background_color', [
        'label'       => esc_html__('Sidebar Background Color', 'aqualuxe'),
        'description' => esc_html__('Set the background color of the sidebar', 'aqualuxe'),
        'section'     => 'aqualuxe_sidebar_colors_section',
    ]));

    // Sidebar Text Color
    $wp_customize->add_setting('sidebar_text_color', [
        'default'           => '#333333',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'sidebar_text_color', [
        'label'       => esc_html__('Sidebar Text Color', 'aqualuxe'),
        'description' => esc_html__('Set the text color of the sidebar', 'aqualuxe'),
        'section'     => 'aqualuxe_sidebar_colors_section',
    ]));

    // Sidebar Link Color
    $wp_customize->add_setting('sidebar_link_color', [
        'default'           => '#0077b6',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'sidebar_link_color', [
        'label'       => esc_html__('Sidebar Link Color', 'aqualuxe'),
        'description' => esc_html__('Set the link color of the sidebar', 'aqualuxe'),
        'section'     => 'aqualuxe_sidebar_colors_section',
    ]));

    // Sidebar Link Hover Color
    $wp_customize->add_setting('sidebar_link_hover_color', [
        'default'           => '#03045e',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'sidebar_link_hover_color', [
        'label'       => esc_html__('Sidebar Link Hover Color', 'aqualuxe'),
        'description' => esc_html__('Set the link hover color of the sidebar', 'aqualuxe'),
        'section'     => 'aqualuxe_sidebar_colors_section',
    ]));

    // Sidebar Widget Title Color
    $wp_customize->add_setting('sidebar_widget_title_color', [
        'default'           => '#000814',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'sidebar_widget_title_color', [
        'label'       => esc_html__('Sidebar Widget Title Color', 'aqualuxe'),
        'description' => esc_html__('Set the widget title color of the sidebar', 'aqualuxe'),
        'section'     => 'aqualuxe_sidebar_colors_section',
    ]));

    // Sidebar Border Color
    $wp_customize->add_setting('sidebar_border_color', [
        'default'           => '#dddddd',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'sidebar_border_color', [
        'label'       => esc_html__('Sidebar Border Color', 'aqualuxe'),
        'description' => esc_html__('Set the border color of the sidebar', 'aqualuxe'),
        'section'     => 'aqualuxe_sidebar_colors_section',
    ]));

    // Breadcrumbs Colors Section
    $wp_customize->add_section('aqualuxe_breadcrumbs_colors_section', [
        'title'       => esc_html__('Breadcrumbs', 'aqualuxe'),
        'description' => esc_html__('Breadcrumbs colors settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_colors_panel',
        'priority'    => 70,
    ]);

    // Breadcrumbs Background Color
    $wp_customize->add_setting('breadcrumbs_background_color', [
        'default'           => '#f8f9fa',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'breadcrumbs_background_color', [
        'label'       => esc_html__('Breadcrumbs Background Color', 'aqualuxe'),
        'description' => esc_html__('Set the background color of breadcrumbs', 'aqualuxe'),
        'section'     => 'aqualuxe_breadcrumbs_colors_section',
    ]));

    // Breadcrumbs Text Color
    $wp_customize->add_setting('breadcrumbs_text_color', [
        'default'           => '#333333',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'breadcrumbs_text_color', [
        'label'       => esc_html__('Breadcrumbs Text Color', 'aqualuxe'),
        'description' => esc_html__('Set the text color of breadcrumbs', 'aqualuxe'),
        'section'     => 'aqualuxe_breadcrumbs_colors_section',
    ]));

    // Breadcrumbs Link Color
    $wp_customize->add_setting('breadcrumbs_link_color', [
        'default'           => '#0077b6',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'breadcrumbs_link_color', [
        'label'       => esc_html__('Breadcrumbs Link Color', 'aqualuxe'),
        'description' => esc_html__('Set the link color of breadcrumbs', 'aqualuxe'),
        'section'     => 'aqualuxe_breadcrumbs_colors_section',
    ]));

    // Breadcrumbs Link Hover Color
    $wp_customize->add_setting('breadcrumbs_link_hover_color', [
        'default'           => '#03045e',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'breadcrumbs_link_hover_color', [
        'label'       => esc_html__('Breadcrumbs Link Hover Color', 'aqualuxe'),
        'description' => esc_html__('Set the link hover color of breadcrumbs', 'aqualuxe'),
        'section'     => 'aqualuxe_breadcrumbs_colors_section',
    ]));

    // Breadcrumbs Separator Color
    $wp_customize->add_setting('breadcrumbs_separator_color', [
        'default'           => '#999999',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'breadcrumbs_separator_color', [
        'label'       => esc_html__('Breadcrumbs Separator Color', 'aqualuxe'),
        'description' => esc_html__('Set the separator color of breadcrumbs', 'aqualuxe'),
        'section'     => 'aqualuxe_breadcrumbs_colors_section',
    ]));

    // Breadcrumbs Border Color
    $wp_customize->add_setting('breadcrumbs_border_color', [
        'default'           => '#dddddd',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'breadcrumbs_border_color', [
        'label'       => esc_html__('Breadcrumbs Border Color', 'aqualuxe'),
        'description' => esc_html__('Set the border color of breadcrumbs', 'aqualuxe'),
        'section'     => 'aqualuxe_breadcrumbs_colors_section',
    ]));

    // Pagination Colors Section
    $wp_customize->add_section('aqualuxe_pagination_colors_section', [
        'title'       => esc_html__('Pagination', 'aqualuxe'),
        'description' => esc_html__('Pagination colors settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_colors_panel',
        'priority'    => 80,
    ]);

    // Pagination Background Color
    $wp_customize->add_setting('pagination_background_color', [
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'pagination_background_color', [
        'label'       => esc_html__('Pagination Background Color', 'aqualuxe'),
        'description' => esc_html__('Set the background color of pagination', 'aqualuxe'),
        'section'     => 'aqualuxe_pagination_colors_section',
    ]));

    // Pagination Text Color
    $wp_customize->add_setting('pagination_text_color', [
        'default'           => '#333333',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'pagination_text_color', [
        'label'       => esc_html__('Pagination Text Color', 'aqualuxe'),
        'description' => esc_html__('Set the text color of pagination', 'aqualuxe'),
        'section'     => 'aqualuxe_pagination_colors_section',
    ]));

    // Pagination Border Color
    $wp_customize->add_setting('pagination_border_color', [
        'default'           => '#dddddd',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'pagination_border_color', [
        'label'       => esc_html__('Pagination Border Color', 'aqualuxe'),
        'description' => esc_html__('Set the border color of pagination', 'aqualuxe'),
        'section'     => 'aqualuxe_pagination_colors_section',
    ]));

    // Pagination Active Background Color
    $wp_customize->add_setting('pagination_active_background_color', [
        'default'           => '#0077b6',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'pagination_active_background_color', [
        'label'       => esc_html__('Pagination Active Background Color', 'aqualuxe'),
        'description' => esc_html__('Set the active background color of pagination', 'aqualuxe'),
        'section'     => 'aqualuxe_pagination_colors_section',
    ]));

    // Pagination Active Text Color
    $wp_customize->add_setting('pagination_active_text_color', [
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'pagination_active_text_color', [
        'label'       => esc_html__('Pagination Active Text Color', 'aqualuxe'),
        'description' => esc_html__('Set the active text color of pagination', 'aqualuxe'),
        'section'     => 'aqualuxe_pagination_colors_section',
    ]));

    // Pagination Hover Background Color
    $wp_customize->add_setting('pagination_hover_background_color', [
        'default'           => '#f8f9fa',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'pagination_hover_background_color', [
        'label'       => esc_html__('Pagination Hover Background Color', 'aqualuxe'),
        'description' => esc_html__('Set the hover background color of pagination', 'aqualuxe'),
        'section'     => 'aqualuxe_pagination_colors_section',
    ]));

    // Pagination Hover Text Color
    $wp_customize->add_setting('pagination_hover_text_color', [
        'default'           => '#0077b6',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'pagination_hover_text_color', [
        'label'       => esc_html__('Pagination Hover Text Color', 'aqualuxe'),
        'description' => esc_html__('Set the hover text color of pagination', 'aqualuxe'),
        'section'     => 'aqualuxe_pagination_colors_section',
    ]));
}