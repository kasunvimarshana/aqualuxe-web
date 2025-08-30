<?php
/**
 * Typography Customizer Section
 *
 * @package AquaLuxe
 */

/**
 * Add typography settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_typography($wp_customize) {
    // Add Typography section
    $wp_customize->add_section(
        'aqualuxe_typography',
        array(
            'title'    => esc_html__('Typography', 'aqualuxe'),
            'priority' => 70,
        )
    );

    // Body Font
    $wp_customize->add_setting(
        'aqualuxe_body_font',
        array(
            'default'           => 'Inter, sans-serif',
            'sanitize_callback' => 'aqualuxe_sanitize_font',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_font',
        array(
            'label'   => esc_html__('Body Font', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type'    => 'select',
            'choices' => array(
                'Inter, sans-serif'                => 'Inter',
                'Roboto, sans-serif'               => 'Roboto',
                'Open Sans, sans-serif'            => 'Open Sans',
                'Lato, sans-serif'                 => 'Lato',
                'Montserrat, sans-serif'           => 'Montserrat',
                'Poppins, sans-serif'              => 'Poppins',
                'Source Sans Pro, sans-serif'      => 'Source Sans Pro',
                'Nunito, sans-serif'               => 'Nunito',
                'Raleway, sans-serif'              => 'Raleway',
                'PT Sans, sans-serif'              => 'PT Sans',
                'Rubik, sans-serif'                => 'Rubik',
                'Work Sans, sans-serif'            => 'Work Sans',
                'Mulish, sans-serif'               => 'Mulish',
                'Noto Sans, sans-serif'            => 'Noto Sans',
                'system-ui, sans-serif'            => 'System UI',
                '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif' => 'System Default',
            ),
        )
    );

    // Heading Font
    $wp_customize->add_setting(
        'aqualuxe_heading_font',
        array(
            'default'           => 'Playfair Display, serif',
            'sanitize_callback' => 'aqualuxe_sanitize_font',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_font',
        array(
            'label'   => esc_html__('Heading Font', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type'    => 'select',
            'choices' => array(
                'Playfair Display, serif'          => 'Playfair Display',
                'Merriweather, serif'              => 'Merriweather',
                'Roboto Slab, serif'               => 'Roboto Slab',
                'Lora, serif'                      => 'Lora',
                'Source Serif Pro, serif'          => 'Source Serif Pro',
                'PT Serif, serif'                  => 'PT Serif',
                'Cormorant Garamond, serif'        => 'Cormorant Garamond',
                'Libre Baskerville, serif'         => 'Libre Baskerville',
                'Crimson Text, serif'              => 'Crimson Text',
                'Noto Serif, serif'                => 'Noto Serif',
                'Montserrat, sans-serif'           => 'Montserrat',
                'Poppins, sans-serif'              => 'Poppins',
                'Raleway, sans-serif'              => 'Raleway',
                'Inter, sans-serif'                => 'Inter',
                'Roboto, sans-serif'               => 'Roboto',
                'Open Sans, sans-serif'            => 'Open Sans',
                'system-ui, sans-serif'            => 'System UI',
            ),
        )
    );

    // Base Font Size
    $wp_customize->add_setting(
        'aqualuxe_base_font_size',
        array(
            'default'           => '16px',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_base_font_size',
        array(
            'label'       => esc_html__('Base Font Size', 'aqualuxe'),
            'section'     => 'aqualuxe_typography',
            'type'        => 'select',
            'choices'     => array(
                '14px' => '14px',
                '15px' => '15px',
                '16px' => '16px',
                '17px' => '17px',
                '18px' => '18px',
            ),
        )
    );

    // Body Line Height
    $wp_customize->add_setting(
        'aqualuxe_body_line_height',
        array(
            'default'           => '1.6',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_line_height',
        array(
            'label'       => esc_html__('Body Line Height', 'aqualuxe'),
            'section'     => 'aqualuxe_typography',
            'type'        => 'select',
            'choices'     => array(
                '1.3' => '1.3',
                '1.4' => '1.4',
                '1.5' => '1.5',
                '1.6' => '1.6',
                '1.7' => '1.7',
                '1.8' => '1.8',
                '1.9' => '1.9',
                '2.0' => '2.0',
            ),
        )
    );

    // Heading Line Height
    $wp_customize->add_setting(
        'aqualuxe_heading_line_height',
        array(
            'default'           => '1.2',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_line_height',
        array(
            'label'       => esc_html__('Heading Line Height', 'aqualuxe'),
            'section'     => 'aqualuxe_typography',
            'type'        => 'select',
            'choices'     => array(
                '1.0' => '1.0',
                '1.1' => '1.1',
                '1.2' => '1.2',
                '1.3' => '1.3',
                '1.4' => '1.4',
                '1.5' => '1.5',
            ),
        )
    );

    // Heading Font Weight
    $wp_customize->add_setting(
        'aqualuxe_heading_font_weight',
        array(
            'default'           => '700',
            'sanitize_callback' => 'aqualuxe_sanitize_font_weight',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_font_weight',
        array(
            'label'       => esc_html__('Heading Font Weight', 'aqualuxe'),
            'section'     => 'aqualuxe_typography',
            'type'        => 'select',
            'choices'     => array(
                '300' => esc_html__('Light (300)', 'aqualuxe'),
                '400' => esc_html__('Regular (400)', 'aqualuxe'),
                '500' => esc_html__('Medium (500)', 'aqualuxe'),
                '600' => esc_html__('Semi-Bold (600)', 'aqualuxe'),
                '700' => esc_html__('Bold (700)', 'aqualuxe'),
                '800' => esc_html__('Extra-Bold (800)', 'aqualuxe'),
                '900' => esc_html__('Black (900)', 'aqualuxe'),
            ),
        )
    );

    // Body Font Weight
    $wp_customize->add_setting(
        'aqualuxe_body_font_weight',
        array(
            'default'           => '400',
            'sanitize_callback' => 'aqualuxe_sanitize_font_weight',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_font_weight',
        array(
            'label'       => esc_html__('Body Font Weight', 'aqualuxe'),
            'section'     => 'aqualuxe_typography',
            'type'        => 'select',
            'choices'     => array(
                '300' => esc_html__('Light (300)', 'aqualuxe'),
                '400' => esc_html__('Regular (400)', 'aqualuxe'),
                '500' => esc_html__('Medium (500)', 'aqualuxe'),
                '600' => esc_html__('Semi-Bold (600)', 'aqualuxe'),
            ),
        )
    );

    // Heading Text Transform
    $wp_customize->add_setting(
        'aqualuxe_heading_text_transform',
        array(
            'default'           => 'none',
            'sanitize_callback' => 'aqualuxe_sanitize_text_transform',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_text_transform',
        array(
            'label'       => esc_html__('Heading Text Transform', 'aqualuxe'),
            'section'     => 'aqualuxe_typography',
            'type'        => 'select',
            'choices'     => array(
                'none'       => esc_html__('None', 'aqualuxe'),
                'capitalize' => esc_html__('Capitalize', 'aqualuxe'),
                'uppercase'  => esc_html__('Uppercase', 'aqualuxe'),
                'lowercase'  => esc_html__('Lowercase', 'aqualuxe'),
            ),
        )
    );

    // H1 Font Size
    $wp_customize->add_setting(
        'aqualuxe_h1_font_size',
        array(
            'default'           => '2.5rem',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_h1_font_size',
        array(
            'label'       => esc_html__('H1 Font Size', 'aqualuxe'),
            'section'     => 'aqualuxe_typography',
            'type'        => 'select',
            'choices'     => array(
                '2rem'    => '2rem',
                '2.25rem' => '2.25rem',
                '2.5rem'  => '2.5rem',
                '2.75rem' => '2.75rem',
                '3rem'    => '3rem',
                '3.5rem'  => '3.5rem',
                '4rem'    => '4rem',
            ),
        )
    );

    // H2 Font Size
    $wp_customize->add_setting(
        'aqualuxe_h2_font_size',
        array(
            'default'           => '2rem',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_h2_font_size',
        array(
            'label'       => esc_html__('H2 Font Size', 'aqualuxe'),
            'section'     => 'aqualuxe_typography',
            'type'        => 'select',
            'choices'     => array(
                '1.5rem'  => '1.5rem',
                '1.75rem' => '1.75rem',
                '2rem'    => '2rem',
                '2.25rem' => '2.25rem',
                '2.5rem'  => '2.5rem',
                '3rem'    => '3rem',
            ),
        )
    );

    // H3 Font Size
    $wp_customize->add_setting(
        'aqualuxe_h3_font_size',
        array(
            'default'           => '1.75rem',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_h3_font_size',
        array(
            'label'       => esc_html__('H3 Font Size', 'aqualuxe'),
            'section'     => 'aqualuxe_typography',
            'type'        => 'select',
            'choices'     => array(
                '1.25rem' => '1.25rem',
                '1.5rem'  => '1.5rem',
                '1.75rem' => '1.75rem',
                '2rem'    => '2rem',
                '2.25rem' => '2.25rem',
            ),
        )
    );

    // H4 Font Size
    $wp_customize->add_setting(
        'aqualuxe_h4_font_size',
        array(
            'default'           => '1.5rem',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_h4_font_size',
        array(
            'label'       => esc_html__('H4 Font Size', 'aqualuxe'),
            'section'     => 'aqualuxe_typography',
            'type'        => 'select',
            'choices'     => array(
                '1rem'    => '1rem',
                '1.25rem' => '1.25rem',
                '1.5rem'  => '1.5rem',
                '1.75rem' => '1.75rem',
                '2rem'    => '2rem',
            ),
        )
    );

    // H5 Font Size
    $wp_customize->add_setting(
        'aqualuxe_h5_font_size',
        array(
            'default'           => '1.25rem',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_h5_font_size',
        array(
            'label'       => esc_html__('H5 Font Size', 'aqualuxe'),
            'section'     => 'aqualuxe_typography',
            'type'        => 'select',
            'choices'     => array(
                '0.875rem' => '0.875rem',
                '1rem'     => '1rem',
                '1.125rem' => '1.125rem',
                '1.25rem'  => '1.25rem',
                '1.5rem'   => '1.5rem',
            ),
        )
    );

    // H6 Font Size
    $wp_customize->add_setting(
        'aqualuxe_h6_font_size',
        array(
            'default'           => '1rem',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_h6_font_size',
        array(
            'label'       => esc_html__('H6 Font Size', 'aqualuxe'),
            'section'     => 'aqualuxe_typography',
            'type'        => 'select',
            'choices'     => array(
                '0.75rem'  => '0.75rem',
                '0.875rem' => '0.875rem',
                '1rem'     => '1rem',
                '1.125rem' => '1.125rem',
                '1.25rem'  => '1.25rem',
            ),
        )
    );

    // Font Smoothing
    $wp_customize->add_setting(
        'aqualuxe_enable_font_smoothing',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_font_smoothing',
        array(
            'label'       => esc_html__('Enable Font Smoothing', 'aqualuxe'),
            'description' => esc_html__('Improves font rendering on WebKit and Mozilla browsers.', 'aqualuxe'),
            'section'     => 'aqualuxe_typography',
            'type'        => 'checkbox',
        )
    );

    // Custom Google Fonts
    $wp_customize->add_setting(
        'aqualuxe_custom_google_fonts',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_custom_google_fonts',
        array(
            'label'       => esc_html__('Custom Google Fonts', 'aqualuxe'),
            'description' => esc_html__('Enter Google Fonts to load (e.g., "Roboto:400,700|Open Sans:400,600,700").', 'aqualuxe'),
            'section'     => 'aqualuxe_typography',
            'type'        => 'text',
        )
    );

    // Custom Font CSS
    $wp_customize->add_setting(
        'aqualuxe_custom_font_css',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_custom_font_css',
        array(
            'label'       => esc_html__('Custom Font CSS', 'aqualuxe'),
            'description' => esc_html__('Add custom CSS for fonts (e.g., @font-face rules).', 'aqualuxe'),
            'section'     => 'aqualuxe_typography',
            'type'        => 'textarea',
        )
    );
}
add_action('customize_register', 'aqualuxe_customize_register_typography');