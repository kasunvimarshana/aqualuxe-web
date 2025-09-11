<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

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

    /**
     * Theme Colors
     */
    $wp_customize->add_section(
        'aqualuxe_colors',
        array(
            'title'    => __( 'Theme Colors', 'aqualuxe' ),
            'priority' => 40,
        )
    );

    // Primary Color
    $wp_customize->add_setting(
        'aqualuxe_primary_color',
        array(
            'default'           => '#0077b6',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_primary_color',
            array(
                'label'   => __( 'Primary Color', 'aqualuxe' ),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Secondary Color
    $wp_customize->add_setting(
        'aqualuxe_secondary_color',
        array(
            'default'           => '#00b4d8',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_secondary_color',
            array(
                'label'   => __( 'Secondary Color', 'aqualuxe' ),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Accent Color
    $wp_customize->add_setting(
        'aqualuxe_accent_color',
        array(
            'default'           => '#90e0ef',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_accent_color',
            array(
                'label'   => __( 'Accent Color', 'aqualuxe' ),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    /**
     * Header Settings
     */
    $wp_customize->add_section(
        'aqualuxe_header',
        array(
            'title'    => __( 'Header Settings', 'aqualuxe' ),
            'priority' => 50,
        )
    );

    // Enable Top Bar
    $wp_customize->add_setting(
        'aqualuxe_enable_top_bar',
        array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_top_bar',
        array(
            'label'   => __( 'Enable Top Bar', 'aqualuxe' ),
            'section' => 'aqualuxe_header',
            'type'    => 'checkbox',
        )
    );

    // Top Bar Left Content
    $wp_customize->add_setting(
        'aqualuxe_top_bar_left',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_top_bar_left',
        array(
            'label'       => __( 'Top Bar Left Content', 'aqualuxe' ),
            'section'     => 'aqualuxe_header',
            'type'        => 'textarea',
            'description' => __( 'Leave empty to show contact info.', 'aqualuxe' ),
        )
    );

    // Top Bar Right Content
    $wp_customize->add_setting(
        'aqualuxe_top_bar_right',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_top_bar_right',
        array(
            'label'       => __( 'Top Bar Right Content', 'aqualuxe' ),
            'section'     => 'aqualuxe_header',
            'type'        => 'textarea',
            'description' => __( 'Leave empty to show social links.', 'aqualuxe' ),
        )
    );

    /**
     * Contact Information
     */
    $wp_customize->add_section(
        'aqualuxe_contact',
        array(
            'title'    => __( 'Contact Information', 'aqualuxe' ),
            'priority' => 60,
        )
    );

    // Phone Number
    $wp_customize->add_setting(
        'aqualuxe_phone',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_phone',
        array(
            'label'   => __( 'Phone Number', 'aqualuxe' ),
            'section' => 'aqualuxe_contact',
            'type'    => 'text',
        )
    );

    // Email Address
    $wp_customize->add_setting(
        'aqualuxe_email',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_email',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_email',
        array(
            'label'   => __( 'Email Address', 'aqualuxe' ),
            'section' => 'aqualuxe_contact',
            'type'    => 'email',
        )
    );

    // Address
    $wp_customize->add_setting(
        'aqualuxe_address',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_textarea_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_address',
        array(
            'label'   => __( 'Address', 'aqualuxe' ),
            'section' => 'aqualuxe_contact',
            'type'    => 'textarea',
        )
    );

    /**
     * Social Media
     */
    $wp_customize->add_section(
        'aqualuxe_social',
        array(
            'title'    => __( 'Social Media', 'aqualuxe' ),
            'priority' => 70,
        )
    );

    $social_networks = array(
        'facebook'  => __( 'Facebook URL', 'aqualuxe' ),
        'twitter'   => __( 'Twitter URL', 'aqualuxe' ),
        'instagram' => __( 'Instagram URL', 'aqualuxe' ),
        'linkedin'  => __( 'LinkedIn URL', 'aqualuxe' ),
        'youtube'   => __( 'YouTube URL', 'aqualuxe' ),
    );

    foreach ( $social_networks as $network => $label ) {
        $wp_customize->add_setting(
            "aqualuxe_{$network}_url",
            array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            )
        );

        $wp_customize->add_control(
            "aqualuxe_{$network}_url",
            array(
                'label'   => $label,
                'section' => 'aqualuxe_social',
                'type'    => 'url',
            )
        );
    }

    /**
     * Layout Settings
     */
    $wp_customize->add_section(
        'aqualuxe_layout',
        array(
            'title'    => __( 'Layout Settings', 'aqualuxe' ),
            'priority' => 80,
        )
    );

    // Container Width
    $wp_customize->add_setting(
        'aqualuxe_container_width',
        array(
            'default'           => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_container_width',
        array(
            'label'   => __( 'Container Width', 'aqualuxe' ),
            'section' => 'aqualuxe_layout',
            'type'    => 'select',
            'choices' => array(
                'default' => __( 'Default (1200px)', 'aqualuxe' ),
                'wide'    => __( 'Wide (1400px)', 'aqualuxe' ),
                'full'    => __( 'Full Width', 'aqualuxe' ),
            ),
        )
    );

    // Enable Breadcrumbs
    $wp_customize->add_setting(
        'aqualuxe_enable_breadcrumbs',
        array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_breadcrumbs',
        array(
            'label'   => __( 'Enable Breadcrumbs', 'aqualuxe' ),
            'section' => 'aqualuxe_layout',
            'type'    => 'checkbox',
        )
    );

    /**
     * Typography
     */
    $wp_customize->add_section(
        'aqualuxe_typography',
        array(
            'title'    => __( 'Typography', 'aqualuxe' ),
            'priority' => 90,
        )
    );

    // Primary Font
    $wp_customize->add_setting(
        'aqualuxe_primary_font',
        array(
            'default'           => 'Inter',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_primary_font',
        array(
            'label'       => __( 'Primary Font', 'aqualuxe' ),
            'section'     => 'aqualuxe_typography',
            'type'        => 'select',
            'choices'     => aqualuxe_get_google_fonts(),
            'description' => __( 'Used for body text and general content.', 'aqualuxe' ),
        )
    );

    // Heading Font
    $wp_customize->add_setting(
        'aqualuxe_heading_font',
        array(
            'default'           => 'Playfair Display',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_font',
        array(
            'label'       => __( 'Heading Font', 'aqualuxe' ),
            'section'     => 'aqualuxe_typography',
            'type'        => 'select',
            'choices'     => aqualuxe_get_google_fonts(),
            'description' => __( 'Used for headings and titles.', 'aqualuxe' ),
        )
    );

    /**
     * Performance
     */
    $wp_customize->add_section(
        'aqualuxe_performance',
        array(
            'title'    => __( 'Performance', 'aqualuxe' ),
            'priority' => 100,
        )
    );

    // Enable Lazy Loading
    $wp_customize->add_setting(
        'aqualuxe_enable_lazy_loading',
        array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_lazy_loading',
        array(
            'label'   => __( 'Enable Lazy Loading', 'aqualuxe' ),
            'section' => 'aqualuxe_performance',
            'type'    => 'checkbox',
        )
    );

    // Minify CSS
    $wp_customize->add_setting(
        'aqualuxe_minify_css',
        array(
            'default'           => false,
            'sanitize_callback' => 'wp_validate_boolean',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_minify_css',
        array(
            'label'   => __( 'Minify CSS', 'aqualuxe' ),
            'section' => 'aqualuxe_performance',
            'type'    => 'checkbox',
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
    wp_enqueue_script(
        'aqualuxe-customizer',
        get_template_directory_uri() . '/assets/dist/js/customizer.js',
        array( 'customize-preview' ),
        AQUALUXE_VERSION,
        true
    );
}
add_action( 'customize_preview_init', 'aqualuxe_customize_preview_js' );

/**
 * Sanitize select options.
 *
 * @param string $input The input value.
 * @param object $setting The setting object.
 * @return string
 */
function aqualuxe_sanitize_select( $input, $setting ) {
    $input = sanitize_key( $input );
    $choices = $setting->manager->get_control( $setting->id )->choices;
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Get Google Fonts list.
 *
 * @return array
 */
function aqualuxe_get_google_fonts() {
    return array(
        'Inter'             => 'Inter',
        'Roboto'            => 'Roboto',
        'Open Sans'         => 'Open Sans',
        'Lato'              => 'Lato',
        'Montserrat'        => 'Montserrat',
        'Playfair Display'  => 'Playfair Display',
        'Merriweather'      => 'Merriweather',
        'Poppins'           => 'Poppins',
        'Source Sans Pro'   => 'Source Sans Pro',
        'Nunito'            => 'Nunito',
    );
}

/**
 * Add custom CSS based on customizer settings.
 */
function aqualuxe_customizer_css() {
    $primary_color   = get_theme_mod( 'aqualuxe_primary_color', '#0077b6' );
    $secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#00b4d8' );
    $accent_color    = get_theme_mod( 'aqualuxe_accent_color', '#90e0ef' );
    $primary_font    = get_theme_mod( 'aqualuxe_primary_font', 'Inter' );
    $heading_font    = get_theme_mod( 'aqualuxe_heading_font', 'Playfair Display' );

    ?>
    <style type="text/css">
        :root {
            --color-primary: <?php echo esc_attr( $primary_color ); ?>;
            --color-secondary: <?php echo esc_attr( $secondary_color ); ?>;
            --color-accent: <?php echo esc_attr( $accent_color ); ?>;
            --font-primary: '<?php echo esc_attr( $primary_font ); ?>', sans-serif;
            --font-heading: '<?php echo esc_attr( $heading_font ); ?>', serif;
        }

        body {
            font-family: var(--font-primary);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-heading);
        }

        .bg-primary,
        .btn-primary {
            background-color: var(--color-primary);
        }

        .bg-secondary,
        .btn-secondary {
            background-color: var(--color-secondary);
        }

        .text-primary {
            color: var(--color-primary);
        }

        .text-secondary {
            color: var(--color-secondary);
        }

        .border-primary {
            border-color: var(--color-primary);
        }

        .site-title a {
            color: var(--color-primary);
        }

        .main-navigation a:hover,
        .main-navigation .current-menu-item a {
            color: var(--color-primary);
        }
    </style>
    <?php

    // Load Google Fonts
    if ( $primary_font !== 'system' || $heading_font !== 'system' ) {
        $fonts = array();
        
        if ( $primary_font !== 'system' ) {
            $fonts[] = str_replace( ' ', '+', $primary_font ) . ':400,500,600,700';
        }
        
        if ( $heading_font !== 'system' && $heading_font !== $primary_font ) {
            $fonts[] = str_replace( ' ', '+', $heading_font ) . ':400,500,600,700';
        }
        
        if ( ! empty( $fonts ) ) {
            ?>
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=<?php echo esc_attr( implode( '&family=', $fonts ) ); ?>&display=swap" rel="stylesheet">
            <?php
        }
    }
}
add_action( 'wp_head', 'aqualuxe_customizer_css' );