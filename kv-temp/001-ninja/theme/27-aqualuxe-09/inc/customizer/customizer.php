<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register( $wp_customize ) {
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    if ( isset( $wp_customize->selective_refresh ) ) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector'        => '.site-title a',
                'render_callback' => 'aqualuxe_customize_partial_blogname',
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector'        => '.site-description',
                'render_callback' => 'aqualuxe_customize_partial_blogdescription',
            )
        );
    }

    // Add Theme Options Panel
    $wp_customize->add_panel(
        'aqualuxe_theme_options',
        array(
            'title'       => __( 'AquaLuxe Theme Options', 'aqualuxe' ),
            'description' => __( 'Theme Options for AquaLuxe', 'aqualuxe' ),
            'priority'    => 130,
        )
    );

    // Add Header & Navigation Section
    $wp_customize->add_section(
        'aqualuxe_header_options',
        array(
            'title'       => __( 'Header & Navigation', 'aqualuxe' ),
            'description' => __( 'Customize the header and navigation', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 10,
        )
    );

    // Add Contact Information Section
    $wp_customize->add_section(
        'aqualuxe_contact_info',
        array(
            'title'       => __( 'Contact Information', 'aqualuxe' ),
            'description' => __( 'Add your contact information', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 20,
        )
    );

    // Add Footer Section
    $wp_customize->add_section(
        'aqualuxe_footer_options',
        array(
            'title'       => __( 'Footer Options', 'aqualuxe' ),
            'description' => __( 'Customize the footer', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 30,
        )
    );

    // Add Social Media Section
    $wp_customize->add_section(
        'aqualuxe_social_media',
        array(
            'title'       => __( 'Social Media', 'aqualuxe' ),
            'description' => __( 'Add your social media links', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 40,
        )
    );

    // Add Colors & Typography Section
    $wp_customize->add_section(
        'aqualuxe_colors_typography',
        array(
            'title'       => __( 'Colors & Typography', 'aqualuxe' ),
            'description' => __( 'Customize colors and typography', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 50,
        )
    );

    // Add Homepage Options Section
    $wp_customize->add_section(
        'aqualuxe_homepage_options',
        array(
            'title'       => __( 'Homepage Options', 'aqualuxe' ),
            'description' => __( 'Customize the homepage', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 60,
        )
    );

    // Add WooCommerce Options Section
    $wp_customize->add_section(
        'aqualuxe_woocommerce_options',
        array(
            'title'       => __( 'WooCommerce Options', 'aqualuxe' ),
            'description' => __( 'Customize WooCommerce settings', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 70,
        )
    );

    // Add Advanced Options Section
    $wp_customize->add_section(
        'aqualuxe_advanced_options',
        array(
            'title'       => __( 'Advanced Options', 'aqualuxe' ),
            'description' => __( 'Advanced theme settings', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 80,
        )
    );

    // Contact Information Settings
    $wp_customize->add_setting(
        'aqualuxe_phone_number',
        array(
            'default'           => '+1 (234) 567-890',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_phone_number',
        array(
            'label'       => __( 'Phone Number', 'aqualuxe' ),
            'description' => __( 'Enter your phone number', 'aqualuxe' ),
            'section'     => 'aqualuxe_contact_info',
            'type'        => 'text',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_email',
        array(
            'default'           => 'info@aqualuxe.com',
            'sanitize_callback' => 'sanitize_email',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_email',
        array(
            'label'       => __( 'Email Address', 'aqualuxe' ),
            'description' => __( 'Enter your email address', 'aqualuxe' ),
            'section'     => 'aqualuxe_contact_info',
            'type'        => 'email',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_address',
        array(
            'default'           => '123 Aquarium Street, Ocean City, FL 33333, USA',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_address',
        array(
            'label'       => __( 'Address', 'aqualuxe' ),
            'description' => __( 'Enter your address', 'aqualuxe' ),
            'section'     => 'aqualuxe_contact_info',
            'type'        => 'textarea',
        )
    );

    // Social Media Settings
    $wp_customize->add_setting(
        'aqualuxe_facebook',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_facebook',
        array(
            'label'       => __( 'Facebook URL', 'aqualuxe' ),
            'description' => __( 'Enter your Facebook page URL', 'aqualuxe' ),
            'section'     => 'aqualuxe_social_media',
            'type'        => 'url',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_twitter',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_twitter',
        array(
            'label'       => __( 'Twitter URL', 'aqualuxe' ),
            'description' => __( 'Enter your Twitter profile URL', 'aqualuxe' ),
            'section'     => 'aqualuxe_social_media',
            'type'        => 'url',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_instagram',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_instagram',
        array(
            'label'       => __( 'Instagram URL', 'aqualuxe' ),
            'description' => __( 'Enter your Instagram profile URL', 'aqualuxe' ),
            'section'     => 'aqualuxe_social_media',
            'type'        => 'url',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_youtube',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_youtube',
        array(
            'label'       => __( 'YouTube URL', 'aqualuxe' ),
            'description' => __( 'Enter your YouTube channel URL', 'aqualuxe' ),
            'section'     => 'aqualuxe_social_media',
            'type'        => 'url',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_linkedin',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_linkedin',
        array(
            'label'       => __( 'LinkedIn URL', 'aqualuxe' ),
            'description' => __( 'Enter your LinkedIn profile URL', 'aqualuxe' ),
            'section'     => 'aqualuxe_social_media',
            'type'        => 'url',
        )
    );

    // Colors & Typography Settings
    $wp_customize->add_setting(
        'aqualuxe_primary_color',
        array(
            'default'           => '#1e40af', // Blue-900
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_primary_color',
            array(
                'label'       => __( 'Primary Color', 'aqualuxe' ),
                'description' => __( 'Select the primary color for the theme', 'aqualuxe' ),
                'section'     => 'aqualuxe_colors_typography',
            )
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_secondary_color',
        array(
            'default'           => '#2563eb', // Blue-600
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_secondary_color',
            array(
                'label'       => __( 'Secondary Color', 'aqualuxe' ),
                'description' => __( 'Select the secondary color for the theme', 'aqualuxe' ),
                'section'     => 'aqualuxe_colors_typography',
            )
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_accent_color',
        array(
            'default'           => '#0ea5e9', // Sky-500
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_accent_color',
            array(
                'label'       => __( 'Accent Color', 'aqualuxe' ),
                'description' => __( 'Select the accent color for the theme', 'aqualuxe' ),
                'section'     => 'aqualuxe_colors_typography',
            )
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_text_color',
        array(
            'default'           => '#1f2937', // Gray-800
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_text_color',
            array(
                'label'       => __( 'Text Color', 'aqualuxe' ),
                'description' => __( 'Select the main text color', 'aqualuxe' ),
                'section'     => 'aqualuxe_colors_typography',
            )
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_heading_font',
        array(
            'default'           => 'Montserrat',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_font',
        array(
            'label'       => __( 'Heading Font', 'aqualuxe' ),
            'description' => __( 'Select the font for headings', 'aqualuxe' ),
            'section'     => 'aqualuxe_colors_typography',
            'type'        => 'select',
            'choices'     => array(
                'Montserrat'    => 'Montserrat',
                'Roboto'        => 'Roboto',
                'Open Sans'     => 'Open Sans',
                'Lato'          => 'Lato',
                'Poppins'       => 'Poppins',
                'Playfair Display' => 'Playfair Display',
                'Merriweather' => 'Merriweather',
            ),
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_body_font',
        array(
            'default'           => 'Open Sans',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_font',
        array(
            'label'       => __( 'Body Font', 'aqualuxe' ),
            'description' => __( 'Select the font for body text', 'aqualuxe' ),
            'section'     => 'aqualuxe_colors_typography',
            'type'        => 'select',
            'choices'     => array(
                'Open Sans'     => 'Open Sans',
                'Roboto'        => 'Roboto',
                'Lato'          => 'Lato',
                'Montserrat'    => 'Montserrat',
                'Poppins'       => 'Poppins',
                'Source Sans Pro' => 'Source Sans Pro',
                'Nunito'        => 'Nunito',
            ),
        )
    );

    // Footer Settings
    $wp_customize->add_setting(
        'aqualuxe_footer_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_text',
        array(
            'label'       => __( 'Footer Text', 'aqualuxe' ),
            'description' => __( 'Add custom text to the footer (HTML allowed)', 'aqualuxe' ),
            'section'     => 'aqualuxe_footer_options',
            'type'        => 'textarea',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_display_footer_widgets',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_display_footer_widgets',
        array(
            'label'       => __( 'Display Footer Widgets', 'aqualuxe' ),
            'description' => __( 'Show or hide the footer widgets area', 'aqualuxe' ),
            'section'     => 'aqualuxe_footer_options',
            'type'        => 'checkbox',
        )
    );

    // WooCommerce Settings
    if ( class_exists( 'WooCommerce' ) ) {
        $wp_customize->add_setting(
            'aqualuxe_products_per_page',
            array(
                'default'           => 12,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_products_per_page',
            array(
                'label'       => __( 'Products Per Page', 'aqualuxe' ),
                'description' => __( 'Number of products to display per page', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce_options',
                'type'        => 'number',
                'input_attrs' => array(
                    'min'  => 4,
                    'max'  => 48,
                    'step' => 4,
                ),
            )
        );

        $wp_customize->add_setting(
            'aqualuxe_shop_columns',
            array(
                'default'           => 3,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_shop_columns',
            array(
                'label'       => __( 'Shop Columns', 'aqualuxe' ),
                'description' => __( 'Number of columns in the shop page', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce_options',
                'type'        => 'select',
                'choices'     => array(
                    2 => '2',
                    3 => '3',
                    4 => '4',
                ),
            )
        );

        $wp_customize->add_setting(
            'aqualuxe_related_products',
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_related_products',
            array(
                'label'       => __( 'Show Related Products', 'aqualuxe' ),
                'description' => __( 'Display related products on single product pages', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce_options',
                'type'        => 'checkbox',
            )
        );
    }

    // Advanced Settings
    $wp_customize->add_setting(
        'aqualuxe_enable_dark_mode',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_dark_mode',
        array(
            'label'       => __( 'Enable Dark Mode Toggle', 'aqualuxe' ),
            'description' => __( 'Allow users to switch between light and dark mode', 'aqualuxe' ),
            'section'     => 'aqualuxe_advanced_options',
            'type'        => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_custom_css',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_strip_all_tags',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_custom_css',
        array(
            'label'       => __( 'Custom CSS', 'aqualuxe' ),
            'description' => __( 'Add custom CSS to customize the theme', 'aqualuxe' ),
            'section'     => 'aqualuxe_advanced_options',
            'type'        => 'textarea',
        )
    );
}
add_action( 'customize_register', 'aqualuxe_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogname() {
    bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogdescription() {
    bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
    wp_enqueue_script( 'aqualuxe-customizer', AQUALUXE_URI . 'assets/js/customizer.js', array( 'customize-preview' ), AQUALUXE_VERSION, true );
}
add_action( 'customize_preview_init', 'aqualuxe_customize_preview_js' );

/**
 * Sanitize checkbox values
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

/**
 * Sanitize select value
 */
function aqualuxe_sanitize_select( $input, $setting ) {
    // Get list of choices from the control associated with the setting
    $choices = $setting->manager->get_control( $setting->id )->choices;
    
    // Return input if valid or return default if not
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Generate CSS from customizer settings
 */
function aqualuxe_generate_custom_css() {
    $primary_color = get_theme_mod( 'aqualuxe_primary_color', '#1e40af' );
    $secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#2563eb' );
    $accent_color = get_theme_mod( 'aqualuxe_accent_color', '#0ea5e9' );
    $text_color = get_theme_mod( 'aqualuxe_text_color', '#1f2937' );
    $heading_font = get_theme_mod( 'aqualuxe_heading_font', 'Montserrat' );
    $body_font = get_theme_mod( 'aqualuxe_body_font', 'Open Sans' );
    $custom_css = get_theme_mod( 'aqualuxe_custom_css', '' );

    $css = "
        :root {
            --primary-color: {$primary_color};
            --secondary-color: {$secondary_color};
            --accent-color: {$accent_color};
            --text-color: {$text_color};
            --heading-font: '{$heading_font}', sans-serif;
            --body-font: '{$body_font}', sans-serif;
        }
        
        body {
            font-family: var(--body-font);
            color: var(--text-color);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--heading-font);
        }
        
        a {
            color: var(--secondary-color);
        }
        
        a:hover {
            color: var(--primary-color);
        }
        
        .bg-primary {
            background-color: var(--primary-color) !important;
        }
        
        .bg-secondary {
            background-color: var(--secondary-color) !important;
        }
        
        .bg-accent {
            background-color: var(--accent-color) !important;
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .text-secondary {
            color: var(--secondary-color) !important;
        }
        
        .text-accent {
            color: var(--accent-color) !important;
        }
        
        .border-primary {
            border-color: var(--primary-color) !important;
        }
        
        .border-secondary {
            border-color: var(--secondary-color) !important;
        }
        
        .border-accent {
            border-color: var(--accent-color) !important;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-secondary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        /* Custom CSS */
        {$custom_css}
    ";

    return $css;
}

/**
 * Output custom CSS to wp_head
 */
function aqualuxe_output_custom_css() {
    $css = aqualuxe_generate_custom_css();
    if ( ! empty( $css ) ) {
        echo '<style type="text/css">' . wp_strip_all_tags( $css ) . '</style>';
    }
}
add_action( 'wp_head', 'aqualuxe_output_custom_css' );

/**
 * Enqueue Google Fonts
 */
function aqualuxe_enqueue_google_fonts() {
    $heading_font = get_theme_mod( 'aqualuxe_heading_font', 'Montserrat' );
    $body_font = get_theme_mod( 'aqualuxe_body_font', 'Open Sans' );
    
    $font_families = array();
    
    if ( 'Montserrat' !== $heading_font ) {
        $font_families[] = $heading_font . ':400,500,600,700';
    } else {
        $font_families[] = 'Montserrat:400,500,600,700';
    }
    
    if ( 'Open Sans' !== $body_font && $body_font !== $heading_font ) {
        $font_families[] = $body_font . ':400,400i,700,700i';
    } else if ( 'Open Sans' !== $body_font ) {
        $font_families[] = 'Open Sans:400,400i,700,700i';
    }
    
    if ( ! empty( $font_families ) ) {
        $query_args = array(
            'family' => urlencode( implode( '|', $font_families ) ),
            'display' => 'swap',
        );
        
        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
        
        wp_enqueue_style( 'aqualuxe-google-fonts', $fonts_url, array(), null );
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_enqueue_google_fonts' );