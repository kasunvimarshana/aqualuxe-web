<?php
/**
 * Typography settings section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add typography settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_typography($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_typography_settings', array(
        'title'       => __('Typography Settings', 'aqualuxe'),
        'description' => __('Customize the typography', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 40,
    ));

    // Body font family
    $wp_customize->add_setting('aqualuxe_options[body_font_family]', array(
        'default'           => 'Montserrat, sans-serif',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_options[body_font_family]', array(
        'label'       => __('Body Font Family', 'aqualuxe'),
        'description' => __('Enter a font family for the body text', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'select',
        'choices'     => array(
            'Montserrat, sans-serif'      => __('Montserrat', 'aqualuxe'),
            'Open Sans, sans-serif'       => __('Open Sans', 'aqualuxe'),
            'Roboto, sans-serif'          => __('Roboto', 'aqualuxe'),
            'Lato, sans-serif'            => __('Lato', 'aqualuxe'),
            'Poppins, sans-serif'         => __('Poppins', 'aqualuxe'),
            'Source Sans Pro, sans-serif' => __('Source Sans Pro', 'aqualuxe'),
        ),
    ));

    // Heading font family
    $wp_customize->add_setting('aqualuxe_options[heading_font_family]', array(
        'default'           => 'Playfair Display, serif',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_options[heading_font_family]', array(
        'label'       => __('Heading Font Family', 'aqualuxe'),
        'description' => __('Enter a font family for headings', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'select',
        'choices'     => array(
            'Playfair Display, serif'     => __('Playfair Display', 'aqualuxe'),
            'Montserrat, sans-serif'      => __('Montserrat', 'aqualuxe'),
            'Merriweather, serif'         => __('Merriweather', 'aqualuxe'),
            'Roboto Slab, serif'          => __('Roboto Slab', 'aqualuxe'),
            'Lora, serif'                 => __('Lora', 'aqualuxe'),
            'Cormorant Garamond, serif'   => __('Cormorant Garamond', 'aqualuxe'),
        ),
    ));

    // Body font size
    $wp_customize->add_setting('aqualuxe_options[body_font_size]', array(
        'default'           => '16',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_options[body_font_size]', array(
        'label'       => __('Body Font Size (px)', 'aqualuxe'),
        'description' => __('Set the base font size in pixels', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 12,
            'max'  => 24,
            'step' => 1,
        ),
    ));

    // Line height
    $wp_customize->add_setting('aqualuxe_options[line_height]', array(
        'default'           => '1.6',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_float',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_options[line_height]', array(
        'label'       => __('Line Height', 'aqualuxe'),
        'description' => __('Set the line height multiplier', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 2,
            'step' => 0.1,
        ),
    ));

    // Heading line height
    $wp_customize->add_setting('aqualuxe_options[heading_line_height]', array(
        'default'           => '1.2',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_float',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_options[heading_line_height]', array(
        'label'       => __('Heading Line Height', 'aqualuxe'),
        'description' => __('Set the line height multiplier for headings', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 2,
            'step' => 0.1,
        ),
    ));

    // Font weight
    $wp_customize->add_setting('aqualuxe_options[body_font_weight]', array(
        'default'           => '400',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_options[body_font_weight]', array(
        'label'       => __('Body Font Weight', 'aqualuxe'),
        'description' => __('Set the font weight for body text', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'select',
        'choices'     => array(
            '300' => __('Light (300)', 'aqualuxe'),
            '400' => __('Regular (400)', 'aqualuxe'),
            '500' => __('Medium (500)', 'aqualuxe'),
            '600' => __('Semi-Bold (600)', 'aqualuxe'),
            '700' => __('Bold (700)', 'aqualuxe'),
        ),
    ));

    // Heading font weight
    $wp_customize->add_setting('aqualuxe_options[heading_font_weight]', array(
        'default'           => '600',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_options[heading_font_weight]', array(
        'label'       => __('Heading Font Weight', 'aqualuxe'),
        'description' => __('Set the font weight for headings', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'select',
        'choices'     => array(
            '400' => __('Regular (400)', 'aqualuxe'),
            '500' => __('Medium (500)', 'aqualuxe'),
            '600' => __('Semi-Bold (600)', 'aqualuxe'),
            '700' => __('Bold (700)', 'aqualuxe'),
        ),
    ));
    
    // H1 font size
    $wp_customize->add_setting('aqualuxe_options[h1_font_size]', array(
        'default'           => '36',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_options[h1_font_size]', array(
        'label'       => __('H1 Font Size (px)', 'aqualuxe'),
        'description' => __('Set the font size for H1 headings', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 20,
            'max'  => 72,
            'step' => 1,
        ),
    ));
    
    // H2 font size
    $wp_customize->add_setting('aqualuxe_options[h2_font_size]', array(
        'default'           => '30',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_options[h2_font_size]', array(
        'label'       => __('H2 Font Size (px)', 'aqualuxe'),
        'description' => __('Set the font size for H2 headings', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 18,
            'max'  => 60,
            'step' => 1,
        ),
    ));
    
    // H3 font size
    $wp_customize->add_setting('aqualuxe_options[h3_font_size]', array(
        'default'           => '24',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_options[h3_font_size]', array(
        'label'       => __('H3 Font Size (px)', 'aqualuxe'),
        'description' => __('Set the font size for H3 headings', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 16,
            'max'  => 48,
            'step' => 1,
        ),
    ));
    
    // H4 font size
    $wp_customize->add_setting('aqualuxe_options[h4_font_size]', array(
        'default'           => '20',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_options[h4_font_size]', array(
        'label'       => __('H4 Font Size (px)', 'aqualuxe'),
        'description' => __('Set the font size for H4 headings', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 14,
            'max'  => 36,
            'step' => 1,
        ),
    ));
    
    // H5 font size
    $wp_customize->add_setting('aqualuxe_options[h5_font_size]', array(
        'default'           => '18',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_options[h5_font_size]', array(
        'label'       => __('H5 Font Size (px)', 'aqualuxe'),
        'description' => __('Set the font size for H5 headings', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 12,
            'max'  => 30,
            'step' => 1,
        ),
    ));
    
    // H6 font size
    $wp_customize->add_setting('aqualuxe_options[h6_font_size]', array(
        'default'           => '16',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_options[h6_font_size]', array(
        'label'       => __('H6 Font Size (px)', 'aqualuxe'),
        'description' => __('Set the font size for H6 headings', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 10,
            'max'  => 24,
            'step' => 1,
        ),
    ));
    
    // Letter spacing
    $wp_customize->add_setting('aqualuxe_options[letter_spacing]', array(
        'default'           => 'normal',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_options[letter_spacing]', array(
        'label'       => __('Letter Spacing', 'aqualuxe'),
        'description' => __('Set the letter spacing (e.g., normal, 0.5px, -0.5px)', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'text',
    ));
    
    // Heading letter spacing
    $wp_customize->add_setting('aqualuxe_options[heading_letter_spacing]', array(
        'default'           => 'normal',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_options[heading_letter_spacing]', array(
        'label'       => __('Heading Letter Spacing', 'aqualuxe'),
        'description' => __('Set the letter spacing for headings (e.g., normal, 0.5px, -0.5px)', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'text',
    ));
    
    // Text transform
    $wp_customize->add_setting('aqualuxe_options[text_transform]', array(
        'default'           => 'none',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_text_transform',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_options[text_transform]', array(
        'label'       => __('Text Transform', 'aqualuxe'),
        'description' => __('Set the text transform for body text', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'select',
        'choices'     => array(
            'none'       => __('None', 'aqualuxe'),
            'capitalize' => __('Capitalize', 'aqualuxe'),
            'uppercase'  => __('Uppercase', 'aqualuxe'),
            'lowercase'  => __('Lowercase', 'aqualuxe'),
        ),
    ));
    
    // Heading text transform
    $wp_customize->add_setting('aqualuxe_options[heading_text_transform]', array(
        'default'           => 'none',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_text_transform',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_options[heading_text_transform]', array(
        'label'       => __('Heading Text Transform', 'aqualuxe'),
        'description' => __('Set the text transform for headings', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'select',
        'choices'     => array(
            'none'       => __('None', 'aqualuxe'),
            'capitalize' => __('Capitalize', 'aqualuxe'),
            'uppercase'  => __('Uppercase', 'aqualuxe'),
            'lowercase'  => __('Lowercase', 'aqualuxe'),
        ),
    ));
    
    // Custom fonts
    $wp_customize->add_setting('aqualuxe_options[custom_fonts]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_options[custom_fonts]', array(
        'label'       => __('Custom Fonts', 'aqualuxe'),
        'description' => __('Enter Google Fonts URL to load custom fonts (e.g., https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap)', 'aqualuxe'),
        'section'     => 'aqualuxe_typography_settings',
        'type'        => 'text',
    ));
}
add_action('customize_register', 'aqualuxe_customize_register_typography');