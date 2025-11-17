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
    // Add selective refresh for site title and description
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

    // Add theme options panel
    $wp_customize->add_panel(
        'aqualuxe_theme_options',
        array(
            'title'       => __( 'AquaLuxe Theme Options', 'aqualuxe' ),
            'description' => __( 'Customize your AquaLuxe theme settings', 'aqualuxe' ),
            'priority'    => 130,
        )
    );

    // Add sections to the panel
    aqualuxe_customize_colors( $wp_customize );
    aqualuxe_customize_typography( $wp_customize );
    aqualuxe_customize_layout( $wp_customize );
    aqualuxe_customize_header( $wp_customize );
    aqualuxe_customize_footer( $wp_customize );
    aqualuxe_customize_blog( $wp_customize );
    aqualuxe_customize_woocommerce( $wp_customize );
    aqualuxe_customize_social( $wp_customize );
    aqualuxe_customize_contact( $wp_customize );
    aqualuxe_customize_advanced( $wp_customize );
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
 * Add color settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_colors( $wp_customize ) {
    // Add colors section
    $wp_customize->add_section(
        'aqualuxe_colors',
        array(
            'title'    => __( 'Colors', 'aqualuxe' ),
            'panel'    => 'aqualuxe_theme_options',
            'priority' => 10,
        )
    );

    // Primary Color
    $wp_customize->add_setting(
        'aqualuxe_primary_color',
        array(
            'default'           => '#0891B2', // Tailwind cyan-600
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_primary_color',
            array(
                'label'    => __( 'Primary Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_colors',
                'settings' => 'aqualuxe_primary_color',
            )
        )
    );

    // Secondary Color
    $wp_customize->add_setting(
        'aqualuxe_secondary_color',
        array(
            'default'           => '#0E7490', // Tailwind cyan-700
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_secondary_color',
            array(
                'label'    => __( 'Secondary Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_colors',
                'settings' => 'aqualuxe_secondary_color',
            )
        )
    );

    // Accent Color
    $wp_customize->add_setting(
        'aqualuxe_accent_color',
        array(
            'default'           => '#14B8A6', // Tailwind teal-500
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_accent_color',
            array(
                'label'    => __( 'Accent Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_colors',
                'settings' => 'aqualuxe_accent_color',
            )
        )
    );

    // Text Color
    $wp_customize->add_setting(
        'aqualuxe_text_color',
        array(
            'default'           => '#1F2937', // Tailwind gray-800
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_text_color',
            array(
                'label'    => __( 'Text Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_colors',
                'settings' => 'aqualuxe_text_color',
            )
        )
    );

    // Background Color
    $wp_customize->add_setting(
        'aqualuxe_background_color',
        array(
            'default'           => '#FFFFFF',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_background_color',
            array(
                'label'    => __( 'Background Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_colors',
                'settings' => 'aqualuxe_background_color',
            )
        )
    );

    // Dark Mode Settings
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
            'label'    => __( 'Enable Dark Mode Toggle', 'aqualuxe' ),
            'section'  => 'aqualuxe_colors',
            'type'     => 'checkbox',
        )
    );

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
                'auto'  => __( 'Auto (follow system)', 'aqualuxe' ),
            ),
        )
    );

    // Dark Mode Colors
    $wp_customize->add_setting(
        'aqualuxe_dark_background_color',
        array(
            'default'           => '#111827', // Tailwind gray-900
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_background_color',
            array(
                'label'    => __( 'Dark Mode Background Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_colors',
                'settings' => 'aqualuxe_dark_background_color',
            )
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_dark_text_color',
        array(
            'default'           => '#F9FAFB', // Tailwind gray-50
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_text_color',
            array(
                'label'    => __( 'Dark Mode Text Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_colors',
                'settings' => 'aqualuxe_dark_text_color',
            )
        )
    );
}

/**
 * Add typography settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_typography( $wp_customize ) {
    // Add typography section
    $wp_customize->add_section(
        'aqualuxe_typography',
        array(
            'title'    => __( 'Typography', 'aqualuxe' ),
            'panel'    => 'aqualuxe_theme_options',
            'priority' => 20,
        )
    );

    // Body Font Family
    $wp_customize->add_setting(
        'aqualuxe_body_font_family',
        array(
            'default'           => 'Inter, sans-serif',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_font_family',
        array(
            'label'    => __( 'Body Font Family', 'aqualuxe' ),
            'section'  => 'aqualuxe_typography',
            'type'     => 'select',
            'choices'  => array(
                'Inter, sans-serif'                => 'Inter (Default)',
                'Roboto, sans-serif'               => 'Roboto',
                'Open Sans, sans-serif'            => 'Open Sans',
                'Lato, sans-serif'                 => 'Lato',
                'Montserrat, sans-serif'           => 'Montserrat',
                'Poppins, sans-serif'              => 'Poppins',
                'Raleway, sans-serif'              => 'Raleway',
                'Source Sans Pro, sans-serif'      => 'Source Sans Pro',
                'Nunito, sans-serif'               => 'Nunito',
                'Merriweather, serif'              => 'Merriweather',
                'Playfair Display, serif'          => 'Playfair Display',
                'Lora, serif'                      => 'Lora',
                'PT Serif, serif'                  => 'PT Serif',
                'Roboto Slab, serif'               => 'Roboto Slab',
                'Noto Serif, serif'                => 'Noto Serif',
                'Crimson Text, serif'              => 'Crimson Text',
                'Libre Baskerville, serif'         => 'Libre Baskerville',
                'Cormorant Garamond, serif'        => 'Cormorant Garamond',
            ),
        )
    );

    // Heading Font Family
    $wp_customize->add_setting(
        'aqualuxe_heading_font_family',
        array(
            'default'           => 'Playfair Display, serif',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_font_family',
        array(
            'label'    => __( 'Heading Font Family', 'aqualuxe' ),
            'section'  => 'aqualuxe_typography',
            'type'     => 'select',
            'choices'  => array(
                'Playfair Display, serif'          => 'Playfair Display (Default)',
                'Inter, sans-serif'                => 'Inter',
                'Roboto, sans-serif'               => 'Roboto',
                'Open Sans, sans-serif'            => 'Open Sans',
                'Lato, sans-serif'                 => 'Lato',
                'Montserrat, sans-serif'           => 'Montserrat',
                'Poppins, sans-serif'              => 'Poppins',
                'Raleway, sans-serif'              => 'Raleway',
                'Source Sans Pro, sans-serif'      => 'Source Sans Pro',
                'Nunito, sans-serif'               => 'Nunito',
                'Merriweather, serif'              => 'Merriweather',
                'Lora, serif'                      => 'Lora',
                'PT Serif, serif'                  => 'PT Serif',
                'Roboto Slab, serif'               => 'Roboto Slab',
                'Noto Serif, serif'                => 'Noto Serif',
                'Crimson Text, serif'              => 'Crimson Text',
                'Libre Baskerville, serif'         => 'Libre Baskerville',
                'Cormorant Garamond, serif'        => 'Cormorant Garamond',
            ),
        )
    );

    // Base Font Size
    $wp_customize->add_setting(
        'aqualuxe_base_font_size',
        array(
            'default'           => '16px',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_base_font_size',
        array(
            'label'    => __( 'Base Font Size', 'aqualuxe' ),
            'section'  => 'aqualuxe_typography',
            'type'     => 'select',
            'choices'  => array(
                '14px' => '14px',
                '15px' => '15px',
                '16px' => '16px (Default)',
                '17px' => '17px',
                '18px' => '18px',
            ),
        )
    );

    // Line Height
    $wp_customize->add_setting(
        'aqualuxe_line_height',
        array(
            'default'           => '1.6',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_line_height',
        array(
            'label'    => __( 'Line Height', 'aqualuxe' ),
            'section'  => 'aqualuxe_typography',
            'type'     => 'select',
            'choices'  => array(
                '1.4' => '1.4 (Compact)',
                '1.5' => '1.5',
                '1.6' => '1.6 (Default)',
                '1.7' => '1.7',
                '1.8' => '1.8 (Spacious)',
            ),
        )
    );
}

/**
 * Add layout settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_layout( $wp_customize ) {
    // Add layout section
    $wp_customize->add_section(
        'aqualuxe_layout',
        array(
            'title'    => __( 'Layout', 'aqualuxe' ),
            'panel'    => 'aqualuxe_theme_options',
            'priority' => 30,
        )
    );

    // Container Width
    $wp_customize->add_setting(
        'aqualuxe_container_width',
        array(
            'default'           => '1280px',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_container_width',
        array(
            'label'    => __( 'Container Width', 'aqualuxe' ),
            'section'  => 'aqualuxe_layout',
            'type'     => 'select',
            'choices'  => array(
                '1024px' => '1024px (Narrow)',
                '1140px' => '1140px',
                '1280px' => '1280px (Default)',
                '1440px' => '1440px (Wide)',
                '1536px' => '1536px (Extra Wide)',
            ),
        )
    );

    // Content Layout
    $wp_customize->add_setting(
        'aqualuxe_content_layout',
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_content_layout',
        array(
            'label'    => __( 'Content Layout', 'aqualuxe' ),
            'section'  => 'aqualuxe_layout',
            'type'     => 'select',
            'choices'  => array(
                'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
                'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
                'no-sidebar'    => __( 'No Sidebar', 'aqualuxe' ),
            ),
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
                'grid'     => __( 'Grid', 'aqualuxe' ),
                'list'     => __( 'List', 'aqualuxe' ),
                'standard' => __( 'Standard', 'aqualuxe' ),
            ),
        )
    );

    // Shop Layout
    $wp_customize->add_setting(
        'aqualuxe_shop_layout',
        array(
            'default'           => 'grid',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_shop_layout',
        array(
            'label'    => __( 'Shop Layout', 'aqualuxe' ),
            'section'  => 'aqualuxe_layout',
            'type'     => 'select',
            'choices'  => array(
                'grid'     => __( 'Grid', 'aqualuxe' ),
                'list'     => __( 'List', 'aqualuxe' ),
            ),
        )
    );

    // Products Per Row
    $wp_customize->add_setting(
        'aqualuxe_products_per_row',
        array(
            'default'           => '3',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_products_per_row',
        array(
            'label'    => __( 'Products Per Row', 'aqualuxe' ),
            'section'  => 'aqualuxe_layout',
            'type'     => 'select',
            'choices'  => array(
                '2' => '2',
                '3' => '3',
                '4' => '4',
            ),
        )
    );
}

/**
 * Add header settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_header( $wp_customize ) {
    // Add header section
    $wp_customize->add_section(
        'aqualuxe_header',
        array(
            'title'    => __( 'Header', 'aqualuxe' ),
            'panel'    => 'aqualuxe_theme_options',
            'priority' => 40,
        )
    );

    // Header Layout
    $wp_customize->add_setting(
        'aqualuxe_header_layout',
        array(
            'default'           => 'standard',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_layout',
        array(
            'label'    => __( 'Header Layout', 'aqualuxe' ),
            'section'  => 'aqualuxe_header',
            'type'     => 'select',
            'choices'  => array(
                'standard'    => __( 'Standard', 'aqualuxe' ),
                'centered'    => __( 'Centered', 'aqualuxe' ),
                'transparent' => __( 'Transparent', 'aqualuxe' ),
                'minimal'     => __( 'Minimal', 'aqualuxe' ),
            ),
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
            'section'  => 'aqualuxe_header',
            'type'     => 'checkbox',
        )
    );

    // Show Search
    $wp_customize->add_setting(
        'aqualuxe_show_header_search',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_header_search',
        array(
            'label'    => __( 'Show Search in Header', 'aqualuxe' ),
            'section'  => 'aqualuxe_header',
            'type'     => 'checkbox',
        )
    );

    // Show Cart
    $wp_customize->add_setting(
        'aqualuxe_show_header_cart',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_header_cart',
        array(
            'label'    => __( 'Show Cart in Header', 'aqualuxe' ),
            'section'  => 'aqualuxe_header',
            'type'     => 'checkbox',
        )
    );

    // Show Account
    $wp_customize->add_setting(
        'aqualuxe_show_header_account',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_header_account',
        array(
            'label'    => __( 'Show Account in Header', 'aqualuxe' ),
            'section'  => 'aqualuxe_header',
            'type'     => 'checkbox',
        )
    );

    // Top Bar
    $wp_customize->add_setting(
        'aqualuxe_show_top_bar',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_top_bar',
        array(
            'label'    => __( 'Show Top Bar', 'aqualuxe' ),
            'section'  => 'aqualuxe_header',
            'type'     => 'checkbox',
        )
    );

    // Top Bar Content
    $wp_customize->add_setting(
        'aqualuxe_top_bar_content',
        array(
            'default'           => __( 'Free shipping on orders over $100', 'aqualuxe' ),
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_top_bar_content',
        array(
            'label'    => __( 'Top Bar Content', 'aqualuxe' ),
            'section'  => 'aqualuxe_header',
            'type'     => 'textarea',
        )
    );
}

/**
 * Add footer settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_footer( $wp_customize ) {
    // Add footer section
    $wp_customize->add_section(
        'aqualuxe_footer',
        array(
            'title'    => __( 'Footer', 'aqualuxe' ),
            'panel'    => 'aqualuxe_theme_options',
            'priority' => 50,
        )
    );

    // Footer Layout
    $wp_customize->add_setting(
        'aqualuxe_footer_layout',
        array(
            'default'           => '4-columns',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_layout',
        array(
            'label'    => __( 'Footer Layout', 'aqualuxe' ),
            'section'  => 'aqualuxe_footer',
            'type'     => 'select',
            'choices'  => array(
                '4-columns' => __( '4 Columns', 'aqualuxe' ),
                '3-columns' => __( '3 Columns', 'aqualuxe' ),
                '2-columns' => __( '2 Columns', 'aqualuxe' ),
                '1-column'  => __( '1 Column', 'aqualuxe' ),
            ),
        )
    );

    // Footer Background
    $wp_customize->add_setting(
        'aqualuxe_footer_background',
        array(
            'default'           => '#0C4A6E', // Tailwind cyan-900
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_footer_background',
            array(
                'label'    => __( 'Footer Background', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer',
                'settings' => 'aqualuxe_footer_background',
            )
        )
    );

    // Footer Text Color
    $wp_customize->add_setting(
        'aqualuxe_footer_text_color',
        array(
            'default'           => '#F9FAFB', // Tailwind gray-50
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_footer_text_color',
            array(
                'label'    => __( 'Footer Text Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer',
                'settings' => 'aqualuxe_footer_text_color',
            )
        )
    );

    // Copyright Text
    $wp_customize->add_setting(
        'aqualuxe_copyright_text',
        array(
            'default'           => sprintf( __( '© %s AquaLuxe. All rights reserved.', 'aqualuxe' ), date( 'Y' ) ),
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_copyright_text',
        array(
            'label'    => __( 'Copyright Text', 'aqualuxe' ),
            'section'  => 'aqualuxe_footer',
            'type'     => 'textarea',
        )
    );

    // Payment Icons
    $wp_customize->add_setting(
        'aqualuxe_show_payment_icons',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_payment_icons',
        array(
            'label'    => __( 'Show Payment Icons', 'aqualuxe' ),
            'section'  => 'aqualuxe_footer',
            'type'     => 'checkbox',
        )
    );

    // Newsletter Form
    $wp_customize->add_setting(
        'aqualuxe_show_newsletter',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_newsletter',
        array(
            'label'    => __( 'Show Newsletter Form', 'aqualuxe' ),
            'section'  => 'aqualuxe_footer',
            'type'     => 'checkbox',
        )
    );

    // Newsletter Shortcode
    $wp_customize->add_setting(
        'aqualuxe_newsletter_shortcode',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_newsletter_shortcode',
        array(
            'label'       => __( 'Newsletter Shortcode', 'aqualuxe' ),
            'description' => __( 'Enter the shortcode for your newsletter form (e.g., from Mailchimp, Contact Form 7, etc.)', 'aqualuxe' ),
            'section'     => 'aqualuxe_footer',
            'type'        => 'text',
        )
    );
}

/**
 * Add blog settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_blog( $wp_customize ) {
    // Add blog section
    $wp_customize->add_section(
        'aqualuxe_blog',
        array(
            'title'    => __( 'Blog', 'aqualuxe' ),
            'panel'    => 'aqualuxe_theme_options',
            'priority' => 60,
        )
    );

    // Featured Image
    $wp_customize->add_setting(
        'aqualuxe_show_featured_image',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_featured_image',
        array(
            'label'    => __( 'Show Featured Image', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog',
            'type'     => 'checkbox',
        )
    );

    // Post Meta
    $wp_customize->add_setting(
        'aqualuxe_show_post_meta',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_post_meta',
        array(
            'label'    => __( 'Show Post Meta', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog',
            'type'     => 'checkbox',
        )
    );

    // Excerpt Length
    $wp_customize->add_setting(
        'aqualuxe_excerpt_length',
        array(
            'default'           => 20,
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_excerpt_length',
        array(
            'label'    => __( 'Excerpt Length', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 10,
                'max'  => 100,
                'step' => 5,
            ),
        )
    );

    // Read More Text
    $wp_customize->add_setting(
        'aqualuxe_read_more_text',
        array(
            'default'           => __( 'Read More', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_read_more_text',
        array(
            'label'    => __( 'Read More Text', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog',
            'type'     => 'text',
        )
    );

    // Related Posts
    $wp_customize->add_setting(
        'aqualuxe_show_related_posts',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_related_posts',
        array(
            'label'    => __( 'Show Related Posts', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog',
            'type'     => 'checkbox',
        )
    );

    // Number of Related Posts
    $wp_customize->add_setting(
        'aqualuxe_related_posts_count',
        array(
            'default'           => 3,
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_related_posts_count',
        array(
            'label'    => __( 'Number of Related Posts', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 2,
                'max'  => 6,
                'step' => 1,
            ),
        )
    );
}

/**
 * Add WooCommerce settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_woocommerce( $wp_customize ) {
    // Add WooCommerce section
    $wp_customize->add_section(
        'aqualuxe_woocommerce',
        array(
            'title'    => __( 'WooCommerce', 'aqualuxe' ),
            'panel'    => 'aqualuxe_theme_options',
            'priority' => 70,
        )
    );

    // Product Gallery Zoom
    $wp_customize->add_setting(
        'aqualuxe_product_gallery_zoom',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_gallery_zoom',
        array(
            'label'    => __( 'Enable Product Gallery Zoom', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce',
            'type'     => 'checkbox',
        )
    );

    // Product Gallery Lightbox
    $wp_customize->add_setting(
        'aqualuxe_product_gallery_lightbox',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_gallery_lightbox',
        array(
            'label'    => __( 'Enable Product Gallery Lightbox', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce',
            'type'     => 'checkbox',
        )
    );

    // Product Gallery Slider
    $wp_customize->add_setting(
        'aqualuxe_product_gallery_slider',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_gallery_slider',
        array(
            'label'    => __( 'Enable Product Gallery Slider', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce',
            'type'     => 'checkbox',
        )
    );

    // Quick View
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
        )
    );

    // Wishlist
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
        )
    );

    // Related Products
    $wp_customize->add_setting(
        'aqualuxe_show_related_products',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_related_products',
        array(
            'label'    => __( 'Show Related Products', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce',
            'type'     => 'checkbox',
        )
    );

    // Number of Related Products
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
            'label'    => __( 'Number of Related Products', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 2,
                'max'  => 8,
                'step' => 1,
            ),
        )
    );

    // Shop Columns
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
            'label'    => __( 'Shop Columns', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce',
            'type'     => 'select',
            'choices'  => array(
                2 => '2',
                3 => '3',
                4 => '4',
            ),
        )
    );

    // Products Per Page
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
            'label'    => __( 'Products Per Page', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 4,
                'max'  => 48,
                'step' => 4,
            ),
        )
    );
}

/**
 * Add social media settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_social( $wp_customize ) {
    // Add social section
    $wp_customize->add_section(
        'aqualuxe_social',
        array(
            'title'    => __( 'Social Media', 'aqualuxe' ),
            'panel'    => 'aqualuxe_theme_options',
            'priority' => 80,
        )
    );

    // Facebook
    $wp_customize->add_setting(
        'aqualuxe_social_facebook',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_facebook',
        array(
            'label'    => __( 'Facebook URL', 'aqualuxe' ),
            'section'  => 'aqualuxe_social',
            'type'     => 'url',
        )
    );

    // Twitter
    $wp_customize->add_setting(
        'aqualuxe_social_twitter',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_twitter',
        array(
            'label'    => __( 'Twitter URL', 'aqualuxe' ),
            'section'  => 'aqualuxe_social',
            'type'     => 'url',
        )
    );

    // Instagram
    $wp_customize->add_setting(
        'aqualuxe_social_instagram',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_instagram',
        array(
            'label'    => __( 'Instagram URL', 'aqualuxe' ),
            'section'  => 'aqualuxe_social',
            'type'     => 'url',
        )
    );

    // LinkedIn
    $wp_customize->add_setting(
        'aqualuxe_social_linkedin',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_linkedin',
        array(
            'label'    => __( 'LinkedIn URL', 'aqualuxe' ),
            'section'  => 'aqualuxe_social',
            'type'     => 'url',
        )
    );

    // YouTube
    $wp_customize->add_setting(
        'aqualuxe_social_youtube',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_youtube',
        array(
            'label'    => __( 'YouTube URL', 'aqualuxe' ),
            'section'  => 'aqualuxe_social',
            'type'     => 'url',
        )
    );

    // Pinterest
    $wp_customize->add_setting(
        'aqualuxe_social_pinterest',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_pinterest',
        array(
            'label'    => __( 'Pinterest URL', 'aqualuxe' ),
            'section'  => 'aqualuxe_social',
            'type'     => 'url',
        )
    );
}

/**
 * Add contact information settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_contact( $wp_customize ) {
    // Add contact section
    $wp_customize->add_section(
        'aqualuxe_contact',
        array(
            'title'    => __( 'Contact Information', 'aqualuxe' ),
            'panel'    => 'aqualuxe_theme_options',
            'priority' => 90,
        )
    );

    // Phone
    $wp_customize->add_setting(
        'aqualuxe_contact_phone',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_contact_phone',
        array(
            'label'    => __( 'Phone Number', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact',
            'type'     => 'text',
        )
    );

    // Email
    $wp_customize->add_setting(
        'aqualuxe_contact_email',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_email',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_contact_email',
        array(
            'label'    => __( 'Email Address', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact',
            'type'     => 'email',
        )
    );

    // Address
    $wp_customize->add_setting(
        'aqualuxe_contact_address',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_contact_address',
        array(
            'label'    => __( 'Address', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact',
            'type'     => 'textarea',
        )
    );

    // Business Hours
    $wp_customize->add_setting(
        'aqualuxe_business_hours',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_business_hours',
        array(
            'label'    => __( 'Business Hours', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact',
            'type'     => 'textarea',
        )
    );

    // Google Maps API Key
    $wp_customize->add_setting(
        'aqualuxe_google_maps_api_key',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_google_maps_api_key',
        array(
            'label'    => __( 'Google Maps API Key', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact',
            'type'     => 'text',
        )
    );

    // Google Maps Latitude
    $wp_customize->add_setting(
        'aqualuxe_google_maps_latitude',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_google_maps_latitude',
        array(
            'label'    => __( 'Google Maps Latitude', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact',
            'type'     => 'text',
        )
    );

    // Google Maps Longitude
    $wp_customize->add_setting(
        'aqualuxe_google_maps_longitude',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_google_maps_longitude',
        array(
            'label'    => __( 'Google Maps Longitude', 'aqualuxe' ),
            'section'  => 'aqualuxe_contact',
            'type'     => 'text',
        )
    );
}

/**
 * Add advanced settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_advanced( $wp_customize ) {
    // Add advanced section
    $wp_customize->add_section(
        'aqualuxe_advanced',
        array(
            'title'    => __( 'Advanced', 'aqualuxe' ),
            'panel'    => 'aqualuxe_theme_options',
            'priority' => 100,
        )
    );

    // Custom CSS
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
            'label'    => __( 'Custom CSS', 'aqualuxe' ),
            'section'  => 'aqualuxe_advanced',
            'type'     => 'textarea',
        )
    );

    // Custom JavaScript
    $wp_customize->add_setting(
        'aqualuxe_custom_js',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_strip_all_tags',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_custom_js',
        array(
            'label'    => __( 'Custom JavaScript', 'aqualuxe' ),
            'section'  => 'aqualuxe_advanced',
            'type'     => 'textarea',
        )
    );

    // Google Analytics
    $wp_customize->add_setting(
        'aqualuxe_google_analytics',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_strip_all_tags',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_google_analytics',
        array(
            'label'    => __( 'Google Analytics Code', 'aqualuxe' ),
            'section'  => 'aqualuxe_advanced',
            'type'     => 'textarea',
        )
    );

    // Lazy Loading
    $wp_customize->add_setting(
        'aqualuxe_enable_lazy_loading',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_lazy_loading',
        array(
            'label'    => __( 'Enable Lazy Loading for Images', 'aqualuxe' ),
            'section'  => 'aqualuxe_advanced',
            'type'     => 'checkbox',
        )
    );

    // Preload Key Resources
    $wp_customize->add_setting(
        'aqualuxe_enable_preloading',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_preloading',
        array(
            'label'    => __( 'Enable Preloading for Key Resources', 'aqualuxe' ),
            'section'  => 'aqualuxe_advanced',
            'type'     => 'checkbox',
        )
    );

    // Multilingual Support
    $wp_customize->add_setting(
        'aqualuxe_enable_multilingual',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_multilingual',
        array(
            'label'    => __( 'Enable Multilingual Support', 'aqualuxe' ),
            'section'  => 'aqualuxe_advanced',
            'type'     => 'checkbox',
        )
    );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
    wp_enqueue_script( 'aqualuxe-customizer', AQUALUXE_URI . 'assets/js/customizer.js', array( 'customize-preview' ), AQUALUXE_VERSION, true );
}
add_action( 'customize_preview_init', 'aqualuxe_customize_preview_js' );

/**
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function aqualuxe_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

/**
 * Sanitize select.
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_select( $input, $setting ) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control( $setting->id )->choices;

    // If the input is a valid key, return it; otherwise, return the default.
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Output the custom CSS in the header.
 */
function aqualuxe_output_customizer_css() {
    $custom_css = get_theme_mod( 'aqualuxe_custom_css', '' );
    
    if ( ! empty( $custom_css ) ) {
        echo '<style type="text/css">' . wp_strip_all_tags( $custom_css ) . '</style>';
    }
}
add_action( 'wp_head', 'aqualuxe_output_customizer_css', 999 );

/**
 * Output the custom JavaScript in the footer.
 */
function aqualuxe_output_customizer_js() {
    $custom_js = get_theme_mod( 'aqualuxe_custom_js', '' );
    
    if ( ! empty( $custom_js ) ) {
        echo '<script>' . wp_strip_all_tags( $custom_js ) . '</script>';
    }
}
add_action( 'wp_footer', 'aqualuxe_output_customizer_js', 999 );

/**
 * Output the Google Analytics code in the header.
 */
function aqualuxe_output_google_analytics() {
    $google_analytics = get_theme_mod( 'aqualuxe_google_analytics', '' );
    
    if ( ! empty( $google_analytics ) ) {
        echo wp_strip_all_tags( $google_analytics );
    }
}
add_action( 'wp_head', 'aqualuxe_output_google_analytics' );