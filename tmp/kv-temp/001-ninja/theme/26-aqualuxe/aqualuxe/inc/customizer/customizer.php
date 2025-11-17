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

    // Header Section
    $wp_customize->add_section(
        'aqualuxe_header_options',
        array(
            'title'    => __( 'Header Options', 'aqualuxe' ),
            'priority' => 10,
            'panel'    => 'aqualuxe_theme_options',
        )
    );

    // Sticky Header
    $wp_customize->add_setting(
        'aqualuxe_sticky_header',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_sticky_header',
        array(
            'label'    => __( 'Enable Sticky Header', 'aqualuxe' ),
            'section'  => 'aqualuxe_header_options',
            'type'     => 'checkbox',
            'priority' => 10,
        )
    );

    // Header Layout
    $wp_customize->add_setting(
        'aqualuxe_header_layout',
        array(
            'default'           => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_layout',
        array(
            'label'    => __( 'Header Layout', 'aqualuxe' ),
            'section'  => 'aqualuxe_header_options',
            'type'     => 'select',
            'choices'  => array(
                'default'      => __( 'Default', 'aqualuxe' ),
                'centered'     => __( 'Centered', 'aqualuxe' ),
                'transparent'  => __( 'Transparent', 'aqualuxe' ),
                'minimal'      => __( 'Minimal', 'aqualuxe' ),
            ),
            'priority' => 20,
        )
    );

    // Footer Section
    $wp_customize->add_section(
        'aqualuxe_footer_options',
        array(
            'title'    => __( 'Footer Options', 'aqualuxe' ),
            'priority' => 20,
            'panel'    => 'aqualuxe_theme_options',
        )
    );

    // Footer Widgets Column Count
    $wp_customize->add_setting(
        'aqualuxe_footer_widget_columns',
        array(
            'default'           => 4,
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_widget_columns',
        array(
            'label'    => __( 'Footer Widget Columns', 'aqualuxe' ),
            'section'  => 'aqualuxe_footer_options',
            'type'     => 'select',
            'choices'  => array(
                1 => __( '1 Column', 'aqualuxe' ),
                2 => __( '2 Columns', 'aqualuxe' ),
                3 => __( '3 Columns', 'aqualuxe' ),
                4 => __( '4 Columns', 'aqualuxe' ),
            ),
            'priority' => 10,
        )
    );

    // Footer Copyright Text
    $wp_customize->add_setting(
        'aqualuxe_footer_copyright',
        array(
            'default'           => sprintf( __( '© %s AquaLuxe. All rights reserved.', 'aqualuxe' ), date( 'Y' ) ),
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_copyright',
        array(
            'label'    => __( 'Footer Copyright Text', 'aqualuxe' ),
            'section'  => 'aqualuxe_footer_options',
            'type'     => 'textarea',
            'priority' => 20,
        )
    );

    // Colors Section
    $wp_customize->add_section(
        'aqualuxe_colors',
        array(
            'title'    => __( 'Colors', 'aqualuxe' ),
            'priority' => 30,
            'panel'    => 'aqualuxe_theme_options',
        )
    );

    // Primary Color
    $wp_customize->add_setting(
        'aqualuxe_primary_color',
        array(
            'default'           => '#0073aa',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_primary_color',
            array(
                'label'    => __( 'Primary Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_colors',
                'priority' => 10,
            )
        )
    );

    // Secondary Color
    $wp_customize->add_setting(
        'aqualuxe_secondary_color',
        array(
            'default'           => '#005177',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_secondary_color',
            array(
                'label'    => __( 'Secondary Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_colors',
                'priority' => 20,
            )
        )
    );

    // Default Color Scheme
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
            'label'    => __( 'Default Color Scheme', 'aqualuxe' ),
            'section'  => 'aqualuxe_colors',
            'type'     => 'select',
            'choices'  => array(
                'light' => __( 'Light', 'aqualuxe' ),
                'dark'  => __( 'Dark', 'aqualuxe' ),
            ),
            'priority' => 30,
        )
    );

    // Typography Section
    $wp_customize->add_section(
        'aqualuxe_typography',
        array(
            'title'    => __( 'Typography', 'aqualuxe' ),
            'priority' => 40,
            'panel'    => 'aqualuxe_theme_options',
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
            'label'    => __( 'Heading Font', 'aqualuxe' ),
            'section'  => 'aqualuxe_typography',
            'type'     => 'select',
            'choices'  => array(
                'Playfair Display' => 'Playfair Display',
                'Montserrat'       => 'Montserrat',
                'Roboto'           => 'Roboto',
                'Open Sans'        => 'Open Sans',
                'Lato'             => 'Lato',
                'Poppins'          => 'Poppins',
                'Raleway'          => 'Raleway',
                'Merriweather'     => 'Merriweather',
            ),
            'priority' => 10,
        )
    );

    // Body Font
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
            'label'    => __( 'Body Font', 'aqualuxe' ),
            'section'  => 'aqualuxe_typography',
            'type'     => 'select',
            'choices'  => array(
                'Open Sans'    => 'Open Sans',
                'Roboto'       => 'Roboto',
                'Lato'         => 'Lato',
                'Montserrat'   => 'Montserrat',
                'Poppins'      => 'Poppins',
                'Raleway'      => 'Raleway',
                'Source Sans Pro' => 'Source Sans Pro',
                'Nunito'       => 'Nunito',
            ),
            'priority' => 20,
        )
    );

    // Base Font Size
    $wp_customize->add_setting(
        'aqualuxe_base_font_size',
        array(
            'default'           => '16',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_base_font_size',
        array(
            'label'    => __( 'Base Font Size (px)', 'aqualuxe' ),
            'section'  => 'aqualuxe_typography',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 12,
                'max'  => 24,
                'step' => 1,
            ),
            'priority' => 30,
        )
    );

    // Layout Section
    $wp_customize->add_section(
        'aqualuxe_layout',
        array(
            'title'    => __( 'Layout Options', 'aqualuxe' ),
            'priority' => 50,
            'panel'    => 'aqualuxe_theme_options',
        )
    );

    // Container Width
    $wp_customize->add_setting(
        'aqualuxe_container_width',
        array(
            'default'           => '1200',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_container_width',
        array(
            'label'    => __( 'Container Width (px)', 'aqualuxe' ),
            'section'  => 'aqualuxe_layout',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 960,
                'max'  => 1600,
                'step' => 10,
            ),
            'priority' => 10,
        )
    );

    // Sidebar Position
    $wp_customize->add_setting(
        'aqualuxe_sidebar_position',
        array(
            'default'           => 'right',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_sidebar_position',
        array(
            'label'    => __( 'Sidebar Position', 'aqualuxe' ),
            'section'  => 'aqualuxe_layout',
            'type'     => 'select',
            'choices'  => array(
                'right' => __( 'Right', 'aqualuxe' ),
                'left'  => __( 'Left', 'aqualuxe' ),
                'none'  => __( 'No Sidebar', 'aqualuxe' ),
            ),
            'priority' => 20,
        )
    );

    // Blog Layout
    $wp_customize->add_setting(
        'aqualuxe_blog_layout',
        array(
            'default'           => 'grid',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_blog_layout',
        array(
            'label'    => __( 'Blog Layout', 'aqualuxe' ),
            'section'  => 'aqualuxe_layout',
            'type'     => 'select',
            'choices'  => array(
                'grid'    => __( 'Grid', 'aqualuxe' ),
                'list'    => __( 'List', 'aqualuxe' ),
                'masonry' => __( 'Masonry', 'aqualuxe' ),
                'classic' => __( 'Classic', 'aqualuxe' ),
            ),
            'priority' => 30,
        )
    );

    // Social Media Section
    $wp_customize->add_section(
        'aqualuxe_social_media',
        array(
            'title'    => __( 'Social Media', 'aqualuxe' ),
            'priority' => 60,
            'panel'    => 'aqualuxe_theme_options',
        )
    );

    // Facebook
    $wp_customize->add_setting(
        'aqualuxe_facebook',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_facebook',
        array(
            'label'    => __( 'Facebook URL', 'aqualuxe' ),
            'section'  => 'aqualuxe_social_media',
            'type'     => 'url',
            'priority' => 10,
        )
    );

    // Twitter
    $wp_customize->add_setting(
        'aqualuxe_twitter',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_twitter',
        array(
            'label'    => __( 'Twitter URL', 'aqualuxe' ),
            'section'  => 'aqualuxe_social_media',
            'type'     => 'url',
            'priority' => 20,
        )
    );

    // Instagram
    $wp_customize->add_setting(
        'aqualuxe_instagram',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_instagram',
        array(
            'label'    => __( 'Instagram URL', 'aqualuxe' ),
            'section'  => 'aqualuxe_social_media',
            'type'     => 'url',
            'priority' => 30,
        )
    );

    // YouTube
    $wp_customize->add_setting(
        'aqualuxe_youtube',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_youtube',
        array(
            'label'    => __( 'YouTube URL', 'aqualuxe' ),
            'section'  => 'aqualuxe_social_media',
            'type'     => 'url',
            'priority' => 40,
        )
    );

    // LinkedIn
    $wp_customize->add_setting(
        'aqualuxe_linkedin',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_linkedin',
        array(
            'label'    => __( 'LinkedIn URL', 'aqualuxe' ),
            'section'  => 'aqualuxe_social_media',
            'type'     => 'url',
            'priority' => 50,
        )
    );

    // Contact Information Section
    $wp_customize->add_section(
        'aqualuxe_contact_info',
        array(
            'title'    => __( 'Contact Information', 'aqualuxe' ),
            'priority' => 70,
            'panel'    => 'aqualuxe_theme_options',
        )
    );

    // Phone
    $wp_customize->add_setting(
        'aqualuxe_phone',
        array(
            'default'           => '+1-234-567-8901',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_phone',
        array(
            'label'    => __( 'Phone Number', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact_info',
            'type'     => 'text',
            'priority' => 10,
        )
    );

    // Email
    $wp_customize->add_setting(
        'aqualuxe_email',
        array(
            'default'           => 'info@aqualuxe.com',
            'sanitize_callback' => 'sanitize_email',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_email',
        array(
            'label'    => __( 'Email Address', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact_info',
            'type'     => 'email',
            'priority' => 20,
        )
    );

    // Address
    $wp_customize->add_setting(
        'aqualuxe_address',
        array(
            'default'           => '123 Aquarium Street, Ocean City, CA 90210',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_address',
        array(
            'label'    => __( 'Address', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact_info',
            'type'     => 'textarea',
            'priority' => 30,
        )
    );

    // Business Hours
    $wp_customize->add_setting(
        'aqualuxe_business_hours',
        array(
            'default'           => "Monday - Friday: 9:00 AM - 6:00 PM\nSaturday: 10:00 AM - 4:00 PM\nSunday: Closed",
            'sanitize_callback' => 'sanitize_textarea_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_business_hours',
        array(
            'label'    => __( 'Business Hours', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact_info',
            'type'     => 'textarea',
            'priority' => 40,
        )
    );

    // WooCommerce Section
    if ( class_exists( 'WooCommerce' ) ) {
        $wp_customize->add_section(
            'aqualuxe_woocommerce',
            array(
                'title'    => __( 'WooCommerce Options', 'aqualuxe' ),
                'priority' => 80,
                'panel'    => 'aqualuxe_theme_options',
            )
        );

        // Products per row
        $wp_customize->add_setting(
            'aqualuxe_products_per_row',
            array(
                'default'           => 3,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_products_per_row',
            array(
                'label'    => __( 'Products per row', 'aqualuxe' ),
                'section'  => 'aqualuxe_woocommerce',
                'type'     => 'select',
                'choices'  => array(
                    2 => '2',
                    3 => '3',
                    4 => '4',
                ),
                'priority' => 10,
            )
        );

        // Related Products Count
        $wp_customize->add_setting(
            'aqualuxe_related_products_count',
            array(
                'default'           => 4,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_related_products_count',
            array(
                'label'    => __( 'Related Products Count', 'aqualuxe' ),
                'section'  => 'aqualuxe_woocommerce',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 2,
                    'max'  => 8,
                    'step' => 1,
                ),
                'priority' => 20,
            )
        );

        // Enable Quick View
        $wp_customize->add_setting(
            'aqualuxe_enable_quick_view',
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_enable_quick_view',
            array(
                'label'    => __( 'Enable Quick View', 'aqualuxe' ),
                'section'  => 'aqualuxe_woocommerce',
                'type'     => 'checkbox',
                'priority' => 30,
            )
        );

        // Enable Wishlist
        $wp_customize->add_setting(
            'aqualuxe_enable_wishlist',
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_enable_wishlist',
            array(
                'label'    => __( 'Enable Wishlist', 'aqualuxe' ),
                'section'  => 'aqualuxe_woocommerce',
                'type'     => 'checkbox',
                'priority' => 40,
            )
        );

        // Product Image Zoom
        $wp_customize->add_setting(
            'aqualuxe_product_image_zoom',
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_product_image_zoom',
            array(
                'label'    => __( 'Enable Product Image Zoom', 'aqualuxe' ),
                'section'  => 'aqualuxe_woocommerce',
                'type'     => 'checkbox',
                'priority' => 50,
            )
        );
    }
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
 * Sanitize select values
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string
 */
function aqualuxe_sanitize_select( $input, $setting ) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control( $setting->id )->choices;
    
    // If the input is a valid key, return it; otherwise, return the default.
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Generate CSS from customizer settings
 */
function aqualuxe_customizer_css() {
    $primary_color = get_theme_mod( 'aqualuxe_primary_color', '#0073aa' );
    $secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#005177' );
    $heading_font = get_theme_mod( 'aqualuxe_heading_font', 'Playfair Display' );
    $body_font = get_theme_mod( 'aqualuxe_body_font', 'Open Sans' );
    $base_font_size = get_theme_mod( 'aqualuxe_base_font_size', '16' );
    $container_width = get_theme_mod( 'aqualuxe_container_width', '1200' );
    
    $css = ':root {
        --primary-color: ' . esc_attr( $primary_color ) . ';
        --primary-color-dark: ' . esc_attr( aqualuxe_adjust_brightness( $primary_color, -20 ) ) . ';
        --primary-color-light: ' . esc_attr( aqualuxe_adjust_brightness( $primary_color, 20 ) ) . ';
        --secondary-color: ' . esc_attr( $secondary_color ) . ';
        --secondary-color-dark: ' . esc_attr( aqualuxe_adjust_brightness( $secondary_color, -20 ) ) . ';
        --secondary-color-light: ' . esc_attr( aqualuxe_adjust_brightness( $secondary_color, 20 ) ) . ';
        --heading-font: "' . esc_attr( $heading_font ) . '", serif;
        --body-font: "' . esc_attr( $body_font ) . '", sans-serif;
        --base-font-size: ' . esc_attr( $base_font_size ) . 'px;
        --container-width: ' . esc_attr( $container_width ) . 'px;
    }';
    
    wp_add_inline_style( 'aqualuxe-style', $css );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_customizer_css' );

/**
 * Adjust color brightness
 *
 * @param string $hex Hex color code.
 * @param int $steps Steps to adjust brightness (negative for darker, positive for lighter).
 * @return string
 */
function aqualuxe_adjust_brightness( $hex, $steps ) {
    // Remove # if present
    $hex = ltrim( $hex, '#' );
    
    // Convert to RGB
    $r = hexdec( substr( $hex, 0, 2 ) );
    $g = hexdec( substr( $hex, 2, 2 ) );
    $b = hexdec( substr( $hex, 4, 2 ) );
    
    // Adjust brightness
    $r = max( 0, min( 255, $r + $steps ) );
    $g = max( 0, min( 255, $g + $steps ) );
    $b = max( 0, min( 255, $b + $steps ) );
    
    // Convert back to hex
    return sprintf( '#%02x%02x%02x', $r, $g, $b );
}