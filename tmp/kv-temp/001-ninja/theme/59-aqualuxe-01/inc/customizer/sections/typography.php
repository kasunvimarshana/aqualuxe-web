<?php
/**
 * AquaLuxe Typography Customizer Settings
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Typography customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager
 */
function aqualuxe_customizer_typography_settings($wp_customize) {
    // Typography Panel
    $wp_customize->add_panel('aqualuxe_typography_panel', [
        'title'       => esc_html__('Typography', 'aqualuxe'),
        'description' => esc_html__('Typography settings', 'aqualuxe'),
        'priority'    => 40,
    ]);

    // Body Typography Section
    $wp_customize->add_section('aqualuxe_body_typography_section', [
        'title'       => esc_html__('Body', 'aqualuxe'),
        'description' => esc_html__('Body typography settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_typography_panel',
        'priority'    => 10,
    ]);

    // Body Font Family
    $wp_customize->add_setting('body_font_family', [
        'default'           => 'Montserrat',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('body_font_family', [
        'label'       => esc_html__('Body Font Family', 'aqualuxe'),
        'description' => esc_html__('Set the font family for body text', 'aqualuxe'),
        'section'     => 'aqualuxe_body_typography_section',
        'type'        => 'select',
        'choices'     => [
            'Arial'              => 'Arial',
            'Helvetica'          => 'Helvetica',
            'Georgia'            => 'Georgia',
            'Times New Roman'    => 'Times New Roman',
            'Verdana'            => 'Verdana',
            'Tahoma'             => 'Tahoma',
            'Trebuchet MS'       => 'Trebuchet MS',
            'Montserrat'         => 'Montserrat',
            'Open Sans'          => 'Open Sans',
            'Roboto'             => 'Roboto',
            'Lato'               => 'Lato',
            'Poppins'            => 'Poppins',
            'Raleway'            => 'Raleway',
            'Nunito'             => 'Nunito',
            'Nunito Sans'        => 'Nunito Sans',
            'Rubik'              => 'Rubik',
            'Work Sans'          => 'Work Sans',
            'PT Sans'            => 'PT Sans',
            'Mulish'             => 'Mulish',
            'Inter'              => 'Inter',
        ],
    ]);

    // Body Font Size
    $wp_customize->add_setting('body_font_size', [
        'default'           => 16,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'body_font_size', [
        'label'       => esc_html__('Body Font Size (px)', 'aqualuxe'),
        'description' => esc_html__('Set the font size for body text', 'aqualuxe'),
        'section'     => 'aqualuxe_body_typography_section',
        'input_attrs' => [
            'min'  => 12,
            'max'  => 24,
            'step' => 1,
        ],
    ]));

    // Body Line Height
    $wp_customize->add_setting('body_line_height', [
        'default'           => 1.6,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'body_line_height', [
        'label'       => esc_html__('Body Line Height', 'aqualuxe'),
        'description' => esc_html__('Set the line height for body text', 'aqualuxe'),
        'section'     => 'aqualuxe_body_typography_section',
        'input_attrs' => [
            'min'  => 1,
            'max'  => 2,
            'step' => 0.1,
        ],
    ]));

    // Body Font Weight
    $wp_customize->add_setting('body_font_weight', [
        'default'           => 400,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('body_font_weight', [
        'label'       => esc_html__('Body Font Weight', 'aqualuxe'),
        'description' => esc_html__('Set the font weight for body text', 'aqualuxe'),
        'section'     => 'aqualuxe_body_typography_section',
        'type'        => 'select',
        'choices'     => [
            '300' => esc_html__('Light', 'aqualuxe'),
            '400' => esc_html__('Regular', 'aqualuxe'),
            '500' => esc_html__('Medium', 'aqualuxe'),
            '600' => esc_html__('Semi Bold', 'aqualuxe'),
            '700' => esc_html__('Bold', 'aqualuxe'),
        ],
    ]);

    // Body Text Transform
    $wp_customize->add_setting('body_text_transform', [
        'default'           => 'none',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('body_text_transform', [
        'label'       => esc_html__('Body Text Transform', 'aqualuxe'),
        'description' => esc_html__('Set the text transform for body text', 'aqualuxe'),
        'section'     => 'aqualuxe_body_typography_section',
        'type'        => 'select',
        'choices'     => [
            'none'       => esc_html__('None', 'aqualuxe'),
            'capitalize' => esc_html__('Capitalize', 'aqualuxe'),
            'uppercase'  => esc_html__('Uppercase', 'aqualuxe'),
            'lowercase'  => esc_html__('Lowercase', 'aqualuxe'),
        ],
    ]);

    // Body Letter Spacing
    $wp_customize->add_setting('body_letter_spacing', [
        'default'           => 0,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'body_letter_spacing', [
        'label'       => esc_html__('Body Letter Spacing (px)', 'aqualuxe'),
        'description' => esc_html__('Set the letter spacing for body text', 'aqualuxe'),
        'section'     => 'aqualuxe_body_typography_section',
        'input_attrs' => [
            'min'  => -2,
            'max'  => 5,
            'step' => 0.1,
        ],
    ]));

    // Headings Typography Section
    $wp_customize->add_section('aqualuxe_headings_typography_section', [
        'title'       => esc_html__('Headings', 'aqualuxe'),
        'description' => esc_html__('Headings typography settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_typography_panel',
        'priority'    => 20,
    ]);

    // Heading Font Family
    $wp_customize->add_setting('heading_font_family', [
        'default'           => 'Playfair Display',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('heading_font_family', [
        'label'       => esc_html__('Heading Font Family', 'aqualuxe'),
        'description' => esc_html__('Set the font family for headings', 'aqualuxe'),
        'section'     => 'aqualuxe_headings_typography_section',
        'type'        => 'select',
        'choices'     => [
            'Arial'              => 'Arial',
            'Helvetica'          => 'Helvetica',
            'Georgia'            => 'Georgia',
            'Times New Roman'    => 'Times New Roman',
            'Verdana'            => 'Verdana',
            'Tahoma'             => 'Tahoma',
            'Trebuchet MS'       => 'Trebuchet MS',
            'Playfair Display'   => 'Playfair Display',
            'Merriweather'       => 'Merriweather',
            'Lora'               => 'Lora',
            'Cormorant Garamond' => 'Cormorant Garamond',
            'Libre Baskerville'  => 'Libre Baskerville',
            'Montserrat'         => 'Montserrat',
            'Open Sans'          => 'Open Sans',
            'Roboto'             => 'Roboto',
            'Lato'               => 'Lato',
            'Poppins'            => 'Poppins',
            'Raleway'            => 'Raleway',
            'Nunito'             => 'Nunito',
            'Nunito Sans'        => 'Nunito Sans',
            'Rubik'              => 'Rubik',
            'Work Sans'          => 'Work Sans',
            'PT Sans'            => 'PT Sans',
            'Mulish'             => 'Mulish',
            'Inter'              => 'Inter',
        ],
    ]);

    // Heading Font Weight
    $wp_customize->add_setting('heading_font_weight', [
        'default'           => 700,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('heading_font_weight', [
        'label'       => esc_html__('Heading Font Weight', 'aqualuxe'),
        'description' => esc_html__('Set the font weight for headings', 'aqualuxe'),
        'section'     => 'aqualuxe_headings_typography_section',
        'type'        => 'select',
        'choices'     => [
            '300' => esc_html__('Light', 'aqualuxe'),
            '400' => esc_html__('Regular', 'aqualuxe'),
            '500' => esc_html__('Medium', 'aqualuxe'),
            '600' => esc_html__('Semi Bold', 'aqualuxe'),
            '700' => esc_html__('Bold', 'aqualuxe'),
            '800' => esc_html__('Extra Bold', 'aqualuxe'),
            '900' => esc_html__('Black', 'aqualuxe'),
        ],
    ]);

    // Heading Text Transform
    $wp_customize->add_setting('heading_text_transform', [
        'default'           => 'none',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('heading_text_transform', [
        'label'       => esc_html__('Heading Text Transform', 'aqualuxe'),
        'description' => esc_html__('Set the text transform for headings', 'aqualuxe'),
        'section'     => 'aqualuxe_headings_typography_section',
        'type'        => 'select',
        'choices'     => [
            'none'       => esc_html__('None', 'aqualuxe'),
            'capitalize' => esc_html__('Capitalize', 'aqualuxe'),
            'uppercase'  => esc_html__('Uppercase', 'aqualuxe'),
            'lowercase'  => esc_html__('Lowercase', 'aqualuxe'),
        ],
    ]);

    // Heading Line Height
    $wp_customize->add_setting('heading_line_height', [
        'default'           => 1.2,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'heading_line_height', [
        'label'       => esc_html__('Heading Line Height', 'aqualuxe'),
        'description' => esc_html__('Set the line height for headings', 'aqualuxe'),
        'section'     => 'aqualuxe_headings_typography_section',
        'input_attrs' => [
            'min'  => 1,
            'max'  => 2,
            'step' => 0.1,
        ],
    ]));

    // Heading Letter Spacing
    $wp_customize->add_setting('heading_letter_spacing', [
        'default'           => 0,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'heading_letter_spacing', [
        'label'       => esc_html__('Heading Letter Spacing (px)', 'aqualuxe'),
        'description' => esc_html__('Set the letter spacing for headings', 'aqualuxe'),
        'section'     => 'aqualuxe_headings_typography_section',
        'input_attrs' => [
            'min'  => -2,
            'max'  => 5,
            'step' => 0.1,
        ],
    ]));

    // Add separator
    $wp_customize->add_setting('heading_separator', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control(new AquaLuxe_Separator_Control($wp_customize, 'heading_separator', [
        'section' => 'aqualuxe_headings_typography_section',
    ]));

    // Add heading for individual headings
    $wp_customize->add_setting('individual_headings_heading', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control(new AquaLuxe_Heading_Control($wp_customize, 'individual_headings_heading', [
        'label'   => esc_html__('Individual Headings', 'aqualuxe'),
        'section' => 'aqualuxe_headings_typography_section',
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

    $defaults = [
        'h1' => [
            'font_size'     => 36,
            'line_height'   => 1.2,
            'margin_bottom' => 20,
        ],
        'h2' => [
            'font_size'     => 30,
            'line_height'   => 1.2,
            'margin_bottom' => 20,
        ],
        'h3' => [
            'font_size'     => 24,
            'line_height'   => 1.3,
            'margin_bottom' => 15,
        ],
        'h4' => [
            'font_size'     => 20,
            'line_height'   => 1.4,
            'margin_bottom' => 15,
        ],
        'h5' => [
            'font_size'     => 18,
            'line_height'   => 1.4,
            'margin_bottom' => 10,
        ],
        'h6' => [
            'font_size'     => 16,
            'line_height'   => 1.4,
            'margin_bottom' => 10,
        ],
    ];

    foreach ($headings as $heading => $label) {
        // Add separator
        $wp_customize->add_setting($heading . '_separator', [
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control(new AquaLuxe_Separator_Control($wp_customize, $heading . '_separator', [
            'section' => 'aqualuxe_headings_typography_section',
        ]));

        // Add heading
        $wp_customize->add_setting($heading . '_heading', [
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control(new AquaLuxe_Heading_Control($wp_customize, $heading . '_heading', [
            'label'   => $label,
            'section' => 'aqualuxe_headings_typography_section',
        ]));

        // Font Size
        $wp_customize->add_setting($heading . '_font_size', [
            'default'           => $defaults[$heading]['font_size'],
            'transport'         => 'postMessage',
            'sanitize_callback' => 'aqualuxe_sanitize_number',
        ]);

        $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, $heading . '_font_size', [
            'label'       => esc_html__('Font Size (px)', 'aqualuxe'),
            'section'     => 'aqualuxe_headings_typography_section',
            'input_attrs' => [
                'min'  => 12,
                'max'  => 72,
                'step' => 1,
            ],
        ]));

        // Line Height
        $wp_customize->add_setting($heading . '_line_height', [
            'default'           => $defaults[$heading]['line_height'],
            'transport'         => 'postMessage',
            'sanitize_callback' => 'aqualuxe_sanitize_number',
        ]);

        $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, $heading . '_line_height', [
            'label'       => esc_html__('Line Height', 'aqualuxe'),
            'section'     => 'aqualuxe_headings_typography_section',
            'input_attrs' => [
                'min'  => 1,
                'max'  => 2,
                'step' => 0.1,
            ],
        ]));

        // Margin Bottom
        $wp_customize->add_setting($heading . '_margin_bottom', [
            'default'           => $defaults[$heading]['margin_bottom'],
            'transport'         => 'postMessage',
            'sanitize_callback' => 'aqualuxe_sanitize_number',
        ]);

        $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, $heading . '_margin_bottom', [
            'label'       => esc_html__('Margin Bottom (px)', 'aqualuxe'),
            'section'     => 'aqualuxe_headings_typography_section',
            'input_attrs' => [
                'min'  => 0,
                'max'  => 50,
                'step' => 1,
            ],
        ]));
    }

    // Buttons Typography Section
    $wp_customize->add_section('aqualuxe_buttons_typography_section', [
        'title'       => esc_html__('Buttons', 'aqualuxe'),
        'description' => esc_html__('Buttons typography settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_typography_panel',
        'priority'    => 30,
    ]);

    // Button Font Family
    $wp_customize->add_setting('button_font_family', [
        'default'           => 'inherit',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('button_font_family', [
        'label'       => esc_html__('Button Font Family', 'aqualuxe'),
        'description' => esc_html__('Set the font family for buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_typography_section',
        'type'        => 'select',
        'choices'     => [
            'inherit'            => esc_html__('Inherit', 'aqualuxe'),
            'Arial'              => 'Arial',
            'Helvetica'          => 'Helvetica',
            'Georgia'            => 'Georgia',
            'Times New Roman'    => 'Times New Roman',
            'Verdana'            => 'Verdana',
            'Tahoma'             => 'Tahoma',
            'Trebuchet MS'       => 'Trebuchet MS',
            'Montserrat'         => 'Montserrat',
            'Open Sans'          => 'Open Sans',
            'Roboto'             => 'Roboto',
            'Lato'               => 'Lato',
            'Poppins'            => 'Poppins',
            'Raleway'            => 'Raleway',
            'Nunito'             => 'Nunito',
            'Nunito Sans'        => 'Nunito Sans',
            'Rubik'              => 'Rubik',
            'Work Sans'          => 'Work Sans',
            'PT Sans'            => 'PT Sans',
            'Mulish'             => 'Mulish',
            'Inter'              => 'Inter',
        ],
    ]);

    // Button Font Size
    $wp_customize->add_setting('button_font_size', [
        'default'           => 14,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'button_font_size', [
        'label'       => esc_html__('Button Font Size (px)', 'aqualuxe'),
        'description' => esc_html__('Set the font size for buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_typography_section',
        'input_attrs' => [
            'min'  => 10,
            'max'  => 24,
            'step' => 1,
        ],
    ]));

    // Button Font Weight
    $wp_customize->add_setting('button_font_weight', [
        'default'           => 600,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('button_font_weight', [
        'label'       => esc_html__('Button Font Weight', 'aqualuxe'),
        'description' => esc_html__('Set the font weight for buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_typography_section',
        'type'        => 'select',
        'choices'     => [
            '300' => esc_html__('Light', 'aqualuxe'),
            '400' => esc_html__('Regular', 'aqualuxe'),
            '500' => esc_html__('Medium', 'aqualuxe'),
            '600' => esc_html__('Semi Bold', 'aqualuxe'),
            '700' => esc_html__('Bold', 'aqualuxe'),
        ],
    ]);

    // Button Text Transform
    $wp_customize->add_setting('button_text_transform', [
        'default'           => 'uppercase',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('button_text_transform', [
        'label'       => esc_html__('Button Text Transform', 'aqualuxe'),
        'description' => esc_html__('Set the text transform for buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_typography_section',
        'type'        => 'select',
        'choices'     => [
            'none'       => esc_html__('None', 'aqualuxe'),
            'capitalize' => esc_html__('Capitalize', 'aqualuxe'),
            'uppercase'  => esc_html__('Uppercase', 'aqualuxe'),
            'lowercase'  => esc_html__('Lowercase', 'aqualuxe'),
        ],
    ]);

    // Button Letter Spacing
    $wp_customize->add_setting('button_letter_spacing', [
        'default'           => 1,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'button_letter_spacing', [
        'label'       => esc_html__('Button Letter Spacing (px)', 'aqualuxe'),
        'description' => esc_html__('Set the letter spacing for buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_typography_section',
        'input_attrs' => [
            'min'  => -2,
            'max'  => 5,
            'step' => 0.1,
        ],
    ]));

    // Button Padding
    $wp_customize->add_setting('button_padding', [
        'default'           => [
            'top'    => 12,
            'right'  => 24,
            'bottom' => 12,
            'left'   => 24,
        ],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_dimensions',
    ]);

    $wp_customize->add_control(new AquaLuxe_Dimensions_Control($wp_customize, 'button_padding', [
        'label'       => esc_html__('Button Padding (px)', 'aqualuxe'),
        'description' => esc_html__('Set the padding for buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_typography_section',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 50,
            'step' => 1,
        ],
        'dimensions'  => [
            'top'    => esc_html__('Top', 'aqualuxe'),
            'right'  => esc_html__('Right', 'aqualuxe'),
            'bottom' => esc_html__('Bottom', 'aqualuxe'),
            'left'   => esc_html__('Left', 'aqualuxe'),
        ],
    ]));

    // Forms Typography Section
    $wp_customize->add_section('aqualuxe_forms_typography_section', [
        'title'       => esc_html__('Forms', 'aqualuxe'),
        'description' => esc_html__('Forms typography settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_typography_panel',
        'priority'    => 40,
    ]);

    // Form Input Font Size
    $wp_customize->add_setting('form_input_font_size', [
        'default'           => 14,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'form_input_font_size', [
        'label'       => esc_html__('Form Input Font Size (px)', 'aqualuxe'),
        'description' => esc_html__('Set the font size for form inputs', 'aqualuxe'),
        'section'     => 'aqualuxe_forms_typography_section',
        'input_attrs' => [
            'min'  => 10,
            'max'  => 24,
            'step' => 1,
        ],
    ]));

    // Form Input Height
    $wp_customize->add_setting('form_input_height', [
        'default'           => 40,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'form_input_height', [
        'label'       => esc_html__('Form Input Height (px)', 'aqualuxe'),
        'description' => esc_html__('Set the height for form inputs', 'aqualuxe'),
        'section'     => 'aqualuxe_forms_typography_section',
        'input_attrs' => [
            'min'  => 30,
            'max'  => 60,
            'step' => 1,
        ],
    ]));

    // Form Input Padding
    $wp_customize->add_setting('form_input_padding', [
        'default'           => [
            'top'    => 8,
            'right'  => 16,
            'bottom' => 8,
            'left'   => 16,
        ],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_dimensions',
    ]);

    $wp_customize->add_control(new AquaLuxe_Dimensions_Control($wp_customize, 'form_input_padding', [
        'label'       => esc_html__('Form Input Padding (px)', 'aqualuxe'),
        'description' => esc_html__('Set the padding for form inputs', 'aqualuxe'),
        'section'     => 'aqualuxe_forms_typography_section',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 30,
            'step' => 1,
        ],
        'dimensions'  => [
            'top'    => esc_html__('Top', 'aqualuxe'),
            'right'  => esc_html__('Right', 'aqualuxe'),
            'bottom' => esc_html__('Bottom', 'aqualuxe'),
            'left'   => esc_html__('Left', 'aqualuxe'),
        ],
    ]));

    // Form Input Border Radius
    $wp_customize->add_setting('form_input_border_radius', [
        'default'           => 4,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'form_input_border_radius', [
        'label'       => esc_html__('Form Input Border Radius (px)', 'aqualuxe'),
        'description' => esc_html__('Set the border radius for form inputs', 'aqualuxe'),
        'section'     => 'aqualuxe_forms_typography_section',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 20,
            'step' => 1,
        ],
    ]));

    // Form Input Border Width
    $wp_customize->add_setting('form_input_border_width', [
        'default'           => 1,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'form_input_border_width', [
        'label'       => esc_html__('Form Input Border Width (px)', 'aqualuxe'),
        'description' => esc_html__('Set the border width for form inputs', 'aqualuxe'),
        'section'     => 'aqualuxe_forms_typography_section',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 5,
            'step' => 1,
        ],
    ]));

    // Form Label Font Size
    $wp_customize->add_setting('form_label_font_size', [
        'default'           => 14,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'form_label_font_size', [
        'label'       => esc_html__('Form Label Font Size (px)', 'aqualuxe'),
        'description' => esc_html__('Set the font size for form labels', 'aqualuxe'),
        'section'     => 'aqualuxe_forms_typography_section',
        'input_attrs' => [
            'min'  => 10,
            'max'  => 20,
            'step' => 1,
        ],
    ]));

    // Form Label Font Weight
    $wp_customize->add_setting('form_label_font_weight', [
        'default'           => 600,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('form_label_font_weight', [
        'label'       => esc_html__('Form Label Font Weight', 'aqualuxe'),
        'description' => esc_html__('Set the font weight for form labels', 'aqualuxe'),
        'section'     => 'aqualuxe_forms_typography_section',
        'type'        => 'select',
        'choices'     => [
            '300' => esc_html__('Light', 'aqualuxe'),
            '400' => esc_html__('Regular', 'aqualuxe'),
            '500' => esc_html__('Medium', 'aqualuxe'),
            '600' => esc_html__('Semi Bold', 'aqualuxe'),
            '700' => esc_html__('Bold', 'aqualuxe'),
        ],
    ]));

    // Form Label Margin Bottom
    $wp_customize->add_setting('form_label_margin_bottom', [
        'default'           => 8,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'form_label_margin_bottom', [
        'label'       => esc_html__('Form Label Margin Bottom (px)', 'aqualuxe'),
        'description' => esc_html__('Set the margin bottom for form labels', 'aqualuxe'),
        'section'     => 'aqualuxe_forms_typography_section',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 20,
            'step' => 1,
        ],
    ]));

    // Google Fonts Section
    $wp_customize->add_section('aqualuxe_google_fonts_section', [
        'title'       => esc_html__('Google Fonts', 'aqualuxe'),
        'description' => esc_html__('Google Fonts settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_typography_panel',
        'priority'    => 50,
    ]);

    // Enable Google Fonts
    $wp_customize->add_setting('enable_google_fonts', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'enable_google_fonts', [
        'label'       => esc_html__('Enable Google Fonts', 'aqualuxe'),
        'description' => esc_html__('Enable Google Fonts for the theme', 'aqualuxe'),
        'section'     => 'aqualuxe_google_fonts_section',
    ]));

    // Google Fonts Subsets
    $wp_customize->add_setting('google_fonts_subsets', [
        'default'           => ['latin', 'latin-ext'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_multi_choices',
    ]);

    $wp_customize->add_control(new AquaLuxe_Sortable_Control($wp_customize, 'google_fonts_subsets', [
        'label'           => esc_html__('Google Fonts Subsets', 'aqualuxe'),
        'description'     => esc_html__('Select the Google Fonts subsets', 'aqualuxe'),
        'section'         => 'aqualuxe_google_fonts_section',
        'choices'         => [
            'latin'        => esc_html__('Latin', 'aqualuxe'),
            'latin-ext'    => esc_html__('Latin Extended', 'aqualuxe'),
            'cyrillic'     => esc_html__('Cyrillic', 'aqualuxe'),
            'cyrillic-ext' => esc_html__('Cyrillic Extended', 'aqualuxe'),
            'greek'        => esc_html__('Greek', 'aqualuxe'),
            'greek-ext'    => esc_html__('Greek Extended', 'aqualuxe'),
            'vietnamese'   => esc_html__('Vietnamese', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('enable_google_fonts', true);
        },
    ]));

    // Google Fonts Display
    $wp_customize->add_setting('google_fonts_display', [
        'default'           => 'swap',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('google_fonts_display', [
        'label'           => esc_html__('Google Fonts Display', 'aqualuxe'),
        'description'     => esc_html__('Select the Google Fonts display property', 'aqualuxe'),
        'section'         => 'aqualuxe_google_fonts_section',
        'type'            => 'select',
        'choices'         => [
            'auto'     => esc_html__('Auto', 'aqualuxe'),
            'block'    => esc_html__('Block', 'aqualuxe'),
            'swap'     => esc_html__('Swap', 'aqualuxe'),
            'fallback' => esc_html__('Fallback', 'aqualuxe'),
            'optional' => esc_html__('Optional', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('enable_google_fonts', true);
        },
    ]);

    // Custom Fonts Section
    $wp_customize->add_section('aqualuxe_custom_fonts_section', [
        'title'       => esc_html__('Custom Fonts', 'aqualuxe'),
        'description' => esc_html__('Custom Fonts settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_typography_panel',
        'priority'    => 60,
    ]);

    // Enable Custom Fonts
    $wp_customize->add_setting('enable_custom_fonts', [
        'default'           => false,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'enable_custom_fonts', [
        'label'       => esc_html__('Enable Custom Fonts', 'aqualuxe'),
        'description' => esc_html__('Enable custom fonts for the theme', 'aqualuxe'),
        'section'     => 'aqualuxe_custom_fonts_section',
    ]));

    // Custom Font 1 Name
    $wp_customize->add_setting('custom_font_1_name', [
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('custom_font_1_name', [
        'label'           => esc_html__('Custom Font 1 Name', 'aqualuxe'),
        'description'     => esc_html__('Enter the name of the custom font', 'aqualuxe'),
        'section'         => 'aqualuxe_custom_fonts_section',
        'type'            => 'text',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('enable_custom_fonts', false);
        },
    ]);

    // Custom Font 1 WOFF2
    $wp_customize->add_setting('custom_font_1_woff2', [
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_url',
    ]);

    $wp_customize->add_control(new WP_Customize_Upload_Control($wp_customize, 'custom_font_1_woff2', [
        'label'           => esc_html__('Custom Font 1 WOFF2', 'aqualuxe'),
        'description'     => esc_html__('Upload the WOFF2 file for the custom font', 'aqualuxe'),
        'section'         => 'aqualuxe_custom_fonts_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('enable_custom_fonts', false);
        },
    ]));

    // Custom Font 1 WOFF
    $wp_customize->add_setting('custom_font_1_woff', [
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_url',
    ]);

    $wp_customize->add_control(new WP_Customize_Upload_Control($wp_customize, 'custom_font_1_woff', [
        'label'           => esc_html__('Custom Font 1 WOFF', 'aqualuxe'),
        'description'     => esc_html__('Upload the WOFF file for the custom font', 'aqualuxe'),
        'section'         => 'aqualuxe_custom_fonts_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('enable_custom_fonts', false);
        },
    ]));

    // Custom Font 1 TTF
    $wp_customize->add_setting('custom_font_1_ttf', [
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_url',
    ]);

    $wp_customize->add_control(new WP_Customize_Upload_Control($wp_customize, 'custom_font_1_ttf', [
        'label'           => esc_html__('Custom Font 1 TTF', 'aqualuxe'),
        'description'     => esc_html__('Upload the TTF file for the custom font', 'aqualuxe'),
        'section'         => 'aqualuxe_custom_fonts_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('enable_custom_fonts', false);
        },
    ]));

    // Custom Font 1 EOT
    $wp_customize->add_setting('custom_font_1_eot', [
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_url',
    ]);

    $wp_customize->add_control(new WP_Customize_Upload_Control($wp_customize, 'custom_font_1_eot', [
        'label'           => esc_html__('Custom Font 1 EOT', 'aqualuxe'),
        'description'     => esc_html__('Upload the EOT file for the custom font', 'aqualuxe'),
        'section'         => 'aqualuxe_custom_fonts_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('enable_custom_fonts', false);
        },
    ]));

    // Custom Font 1 SVG
    $wp_customize->add_setting('custom_font_1_svg', [
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_url',
    ]);

    $wp_customize->add_control(new WP_Customize_Upload_Control($wp_customize, 'custom_font_1_svg', [
        'label'           => esc_html__('Custom Font 1 SVG', 'aqualuxe'),
        'description'     => esc_html__('Upload the SVG file for the custom font', 'aqualuxe'),
        'section'         => 'aqualuxe_custom_fonts_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('enable_custom_fonts', false);
        },
    ]));

    // Custom Font 1 Font Weight
    $wp_customize->add_setting('custom_font_1_font_weight', [
        'default'           => '400',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('custom_font_1_font_weight', [
        'label'           => esc_html__('Custom Font 1 Font Weight', 'aqualuxe'),
        'description'     => esc_html__('Select the font weight for the custom font', 'aqualuxe'),
        'section'         => 'aqualuxe_custom_fonts_section',
        'type'            => 'select',
        'choices'         => [
            '100' => esc_html__('Thin (100)', 'aqualuxe'),
            '200' => esc_html__('Extra Light (200)', 'aqualuxe'),
            '300' => esc_html__('Light (300)', 'aqualuxe'),
            '400' => esc_html__('Regular (400)', 'aqualuxe'),
            '500' => esc_html__('Medium (500)', 'aqualuxe'),
            '600' => esc_html__('Semi Bold (600)', 'aqualuxe'),
            '700' => esc_html__('Bold (700)', 'aqualuxe'),
            '800' => esc_html__('Extra Bold (800)', 'aqualuxe'),
            '900' => esc_html__('Black (900)', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('enable_custom_fonts', false);
        },
    ]);

    // Custom Font 1 Font Style
    $wp_customize->add_setting('custom_font_1_font_style', [
        'default'           => 'normal',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('custom_font_1_font_style', [
        'label'           => esc_html__('Custom Font 1 Font Style', 'aqualuxe'),
        'description'     => esc_html__('Select the font style for the custom font', 'aqualuxe'),
        'section'         => 'aqualuxe_custom_fonts_section',
        'type'            => 'select',
        'choices'         => [
            'normal' => esc_html__('Normal', 'aqualuxe'),
            'italic' => esc_html__('Italic', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('enable_custom_fonts', false);
        },
    ]);
}