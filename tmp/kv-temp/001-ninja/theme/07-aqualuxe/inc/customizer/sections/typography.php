<?php
/**
 * Typography Customizer Settings
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Add typography settings to the Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_typography($wp_customize) {
    // Add Typography section
    $wp_customize->add_section('aqualuxe_typography', array(
        'title' => esc_html__('Typography', 'aqualuxe'),
        'priority' => 70,
    ));

    // Body Typography
    $wp_customize->add_setting('aqualuxe_typography_body_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_typography_body_heading', array(
        'label' => esc_html__('Body Typography', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'priority' => 10,
    )));

    // Body Font
    $wp_customize->add_setting('aqualuxe_body_font', array(
        'default' => 'Montserrat',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_body_font', array(
        'label' => esc_html__('Body Font', 'aqualuxe'),
        'description' => esc_html__('Select the font for body text.', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => array(
            'Arial' => 'Arial',
            'Georgia' => 'Georgia',
            'Tahoma' => 'Tahoma',
            'Verdana' => 'Verdana',
            'Montserrat' => 'Montserrat',
            'Open Sans' => 'Open Sans',
            'Roboto' => 'Roboto',
            'Lato' => 'Lato',
            'Poppins' => 'Poppins',
            'Nunito' => 'Nunito',
            'Source Sans Pro' => 'Source Sans Pro',
        ),
        'priority' => 20,
    ));

    // Body Font Size
    $wp_customize->add_setting('aqualuxe_body_font_size', array(
        'default' => '16px',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_body_font_size', array(
        'label' => esc_html__('Body Font Size', 'aqualuxe'),
        'description' => esc_html__('Set the base font size for body text.', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'text',
        'input_attrs' => array(
            'placeholder' => '16px',
        ),
        'priority' => 30,
    ));

    // Body Line Height
    $wp_customize->add_setting('aqualuxe_body_line_height', array(
        'default' => '1.6',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_body_line_height', array(
        'label' => esc_html__('Body Line Height', 'aqualuxe'),
        'description' => esc_html__('Set the line height for body text.', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'text',
        'input_attrs' => array(
            'placeholder' => '1.6',
        ),
        'priority' => 40,
    ));

    // Headings Typography
    $wp_customize->add_setting('aqualuxe_typography_headings_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_typography_headings_heading', array(
        'label' => esc_html__('Headings Typography', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'priority' => 50,
    )));

    // Headings Font
    $wp_customize->add_setting('aqualuxe_heading_font', array(
        'default' => 'Playfair Display',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_heading_font', array(
        'label' => esc_html__('Headings Font', 'aqualuxe'),
        'description' => esc_html__('Select the font for headings.', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => array(
            'Arial' => 'Arial',
            'Georgia' => 'Georgia',
            'Tahoma' => 'Tahoma',
            'Verdana' => 'Verdana',
            'Montserrat' => 'Montserrat',
            'Open Sans' => 'Open Sans',
            'Roboto' => 'Roboto',
            'Lato' => 'Lato',
            'Playfair Display' => 'Playfair Display',
            'Oswald' => 'Oswald',
            'Raleway' => 'Raleway',
            'Poppins' => 'Poppins',
        ),
        'priority' => 60,
    ));

    // Headings Font Weight
    $wp_customize->add_setting('aqualuxe_heading_font_weight', array(
        'default' => '700',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_heading_font_weight', array(
        'label' => esc_html__('Headings Font Weight', 'aqualuxe'),
        'description' => esc_html__('Select the font weight for headings.', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => array(
            '300' => esc_html__('Light (300)', 'aqualuxe'),
            '400' => esc_html__('Regular (400)', 'aqualuxe'),
            '500' => esc_html__('Medium (500)', 'aqualuxe'),
            '600' => esc_html__('Semi-Bold (600)', 'aqualuxe'),
            '700' => esc_html__('Bold (700)', 'aqualuxe'),
            '800' => esc_html__('Extra-Bold (800)', 'aqualuxe'),
            '900' => esc_html__('Black (900)', 'aqualuxe'),
        ),
        'priority' => 70,
    ));

    // Headings Text Transform
    $wp_customize->add_setting('aqualuxe_heading_text_transform', array(
        'default' => 'none',
        'transport' => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_heading_text_transform', array(
        'label' => esc_html__('Headings Text Transform', 'aqualuxe'),
        'description' => esc_html__('Select the text transform for headings.', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => array(
            'none' => esc_html__('None', 'aqualuxe'),
            'capitalize' => esc_html__('Capitalize', 'aqualuxe'),
            'uppercase' => esc_html__('Uppercase', 'aqualuxe'),
            'lowercase' => esc_html__('Lowercase', 'aqualuxe'),
        ),
        'priority' => 80,
    ));

    // Headings Line Height
    $wp_customize->add_setting('aqualuxe_heading_line_height', array(
        'default' => '1.2',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_heading_line_height', array(
        'label' => esc_html__('Headings Line Height', 'aqualuxe'),
        'description' => esc_html__('Set the line height for headings.', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'text',
        'input_attrs' => array(
            'placeholder' => '1.2',
        ),
        'priority' => 90,
    ));

    // H1 Font Size
    $wp_customize->add_setting('aqualuxe_h1_font_size', array(
        'default' => '2.5rem',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_h1_font_size', array(
        'label' => esc_html__('H1 Font Size', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'text',
        'input_attrs' => array(
            'placeholder' => '2.5rem',
        ),
        'priority' => 100,
    ));

    // H2 Font Size
    $wp_customize->add_setting('aqualuxe_h2_font_size', array(
        'default' => '2rem',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_h2_font_size', array(
        'label' => esc_html__('H2 Font Size', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'text',
        'input_attrs' => array(
            'placeholder' => '2rem',
        ),
        'priority' => 110,
    ));

    // H3 Font Size
    $wp_customize->add_setting('aqualuxe_h3_font_size', array(
        'default' => '1.75rem',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_h3_font_size', array(
        'label' => esc_html__('H3 Font Size', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'text',
        'input_attrs' => array(
            'placeholder' => '1.75rem',
        ),
        'priority' => 120,
    ));

    // H4 Font Size
    $wp_customize->add_setting('aqualuxe_h4_font_size', array(
        'default' => '1.5rem',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_h4_font_size', array(
        'label' => esc_html__('H4 Font Size', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'text',
        'input_attrs' => array(
            'placeholder' => '1.5rem',
        ),
        'priority' => 130,
    ));

    // H5 Font Size
    $wp_customize->add_setting('aqualuxe_h5_font_size', array(
        'default' => '1.25rem',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_h5_font_size', array(
        'label' => esc_html__('H5 Font Size', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'text',
        'input_attrs' => array(
            'placeholder' => '1.25rem',
        ),
        'priority' => 140,
    ));

    // H6 Font Size
    $wp_customize->add_setting('aqualuxe_h6_font_size', array(
        'default' => '1rem',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_h6_font_size', array(
        'label' => esc_html__('H6 Font Size', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'text',
        'input_attrs' => array(
            'placeholder' => '1rem',
        ),
        'priority' => 150,
    ));

    // Menu Typography
    $wp_customize->add_setting('aqualuxe_typography_menu_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_typography_menu_heading', array(
        'label' => esc_html__('Menu Typography', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'priority' => 160,
    )));

    // Menu Font
    $wp_customize->add_setting('aqualuxe_menu_font', array(
        'default' => 'Montserrat',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_menu_font', array(
        'label' => esc_html__('Menu Font', 'aqualuxe'),
        'description' => esc_html__('Select the font for menus.', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => array(
            'Arial' => 'Arial',
            'Georgia' => 'Georgia',
            'Tahoma' => 'Tahoma',
            'Verdana' => 'Verdana',
            'Montserrat' => 'Montserrat',
            'Open Sans' => 'Open Sans',
            'Roboto' => 'Roboto',
            'Lato' => 'Lato',
            'Poppins' => 'Poppins',
            'Nunito' => 'Nunito',
            'Source Sans Pro' => 'Source Sans Pro',
        ),
        'priority' => 170,
    ));

    // Menu Font Size
    $wp_customize->add_setting('aqualuxe_menu_font_size', array(
        'default' => '14px',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_menu_font_size', array(
        'label' => esc_html__('Menu Font Size', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'text',
        'input_attrs' => array(
            'placeholder' => '14px',
        ),
        'priority' => 180,
    ));

    // Menu Font Weight
    $wp_customize->add_setting('aqualuxe_menu_font_weight', array(
        'default' => '500',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_menu_font_weight', array(
        'label' => esc_html__('Menu Font Weight', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => array(
            '300' => esc_html__('Light (300)', 'aqualuxe'),
            '400' => esc_html__('Regular (400)', 'aqualuxe'),
            '500' => esc_html__('Medium (500)', 'aqualuxe'),
            '600' => esc_html__('Semi-Bold (600)', 'aqualuxe'),
            '700' => esc_html__('Bold (700)', 'aqualuxe'),
        ),
        'priority' => 190,
    ));

    // Menu Text Transform
    $wp_customize->add_setting('aqualuxe_menu_text_transform', array(
        'default' => 'uppercase',
        'transport' => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_menu_text_transform', array(
        'label' => esc_html__('Menu Text Transform', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => array(
            'none' => esc_html__('None', 'aqualuxe'),
            'capitalize' => esc_html__('Capitalize', 'aqualuxe'),
            'uppercase' => esc_html__('Uppercase', 'aqualuxe'),
            'lowercase' => esc_html__('Lowercase', 'aqualuxe'),
        ),
        'priority' => 200,
    ));

    // Button Typography
    $wp_customize->add_setting('aqualuxe_typography_button_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_typography_button_heading', array(
        'label' => esc_html__('Button Typography', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'priority' => 210,
    )));

    // Button Font
    $wp_customize->add_setting('aqualuxe_button_font', array(
        'default' => 'Montserrat',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_button_font', array(
        'label' => esc_html__('Button Font', 'aqualuxe'),
        'description' => esc_html__('Select the font for buttons.', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => array(
            'Arial' => 'Arial',
            'Georgia' => 'Georgia',
            'Tahoma' => 'Tahoma',
            'Verdana' => 'Verdana',
            'Montserrat' => 'Montserrat',
            'Open Sans' => 'Open Sans',
            'Roboto' => 'Roboto',
            'Lato' => 'Lato',
            'Poppins' => 'Poppins',
            'Nunito' => 'Nunito',
            'Source Sans Pro' => 'Source Sans Pro',
        ),
        'priority' => 220,
    ));

    // Button Font Size
    $wp_customize->add_setting('aqualuxe_button_font_size', array(
        'default' => '14px',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_button_font_size', array(
        'label' => esc_html__('Button Font Size', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'text',
        'input_attrs' => array(
            'placeholder' => '14px',
        ),
        'priority' => 230,
    ));

    // Button Font Weight
    $wp_customize->add_setting('aqualuxe_button_font_weight', array(
        'default' => '600',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_button_font_weight', array(
        'label' => esc_html__('Button Font Weight', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => array(
            '400' => esc_html__('Regular (400)', 'aqualuxe'),
            '500' => esc_html__('Medium (500)', 'aqualuxe'),
            '600' => esc_html__('Semi-Bold (600)', 'aqualuxe'),
            '700' => esc_html__('Bold (700)', 'aqualuxe'),
        ),
        'priority' => 240,
    ));

    // Button Text Transform
    $wp_customize->add_setting('aqualuxe_button_text_transform', array(
        'default' => 'uppercase',
        'transport' => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_button_text_transform', array(
        'label' => esc_html__('Button Text Transform', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => array(
            'none' => esc_html__('None', 'aqualuxe'),
            'capitalize' => esc_html__('Capitalize', 'aqualuxe'),
            'uppercase' => esc_html__('Uppercase', 'aqualuxe'),
            'lowercase' => esc_html__('Lowercase', 'aqualuxe'),
        ),
        'priority' => 250,
    ));

    // Button Letter Spacing
    $wp_customize->add_setting('aqualuxe_button_letter_spacing', array(
        'default' => '1px',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_button_letter_spacing', array(
        'label' => esc_html__('Button Letter Spacing', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'text',
        'input_attrs' => array(
            'placeholder' => '1px',
        ),
        'priority' => 260,
    ));
}
add_action('customize_register', 'aqualuxe_customize_register_typography');