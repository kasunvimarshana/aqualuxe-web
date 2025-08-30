<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

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

    // Add theme options panel
    $wp_customize->add_panel(
        'aqualuxe_theme_options',
        array(
            'title'       => __( 'Theme Options', 'aqualuxe' ),
            'description' => __( 'Theme Options for AquaLuxe', 'aqualuxe' ),
            'priority'    => 130,
        )
    );

    // Add header section
    $wp_customize->add_section(
        'aqualuxe_header_options',
        array(
            'title'       => __( 'Header Options', 'aqualuxe' ),
            'description' => __( 'Customize the header section', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 10,
        )
    );

    // Add header layout setting
    $wp_customize->add_setting(
        'aqualuxe_header_layout',
        array(
            'default'           => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_layout',
        array(
            'label'       => __( 'Header Layout', 'aqualuxe' ),
            'description' => __( 'Select the header layout', 'aqualuxe' ),
            'section'     => 'aqualuxe_header_options',
            'type'        => 'select',
            'choices'     => array(
                'default' => __( 'Default', 'aqualuxe' ),
                'centered' => __( 'Centered', 'aqualuxe' ),
                'split' => __( 'Split', 'aqualuxe' ),
                'minimal' => __( 'Minimal', 'aqualuxe' ),
            ),
        )
    );

    // Add sticky header setting
    $wp_customize->add_setting(
        'aqualuxe_sticky_header',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_sticky_header',
        array(
            'label'       => __( 'Sticky Header', 'aqualuxe' ),
            'description' => __( 'Enable sticky header', 'aqualuxe' ),
            'section'     => 'aqualuxe_header_options',
            'type'        => 'checkbox',
        )
    );

    // Add transparent header setting
    $wp_customize->add_setting(
        'aqualuxe_transparent_header',
        array(
            'default'           => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_transparent_header',
        array(
            'label'       => __( 'Transparent Header', 'aqualuxe' ),
            'description' => __( 'Enable transparent header on homepage', 'aqualuxe' ),
            'section'     => 'aqualuxe_header_options',
            'type'        => 'checkbox',
        )
    );

    // Add header search setting
    $wp_customize->add_setting(
        'aqualuxe_header_search',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_search',
        array(
            'label'       => __( 'Header Search', 'aqualuxe' ),
            'description' => __( 'Show search in header', 'aqualuxe' ),
            'section'     => 'aqualuxe_header_options',
            'type'        => 'checkbox',
        )
    );

    // Add header cart setting
    $wp_customize->add_setting(
        'aqualuxe_header_cart',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_cart',
        array(
            'label'       => __( 'Header Cart', 'aqualuxe' ),
            'description' => __( 'Show cart in header', 'aqualuxe' ),
            'section'     => 'aqualuxe_header_options',
            'type'        => 'checkbox',
        )
    );

    // Add header account setting
    $wp_customize->add_setting(
        'aqualuxe_header_account',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_account',
        array(
            'label'       => __( 'Header Account', 'aqualuxe' ),
            'description' => __( 'Show account in header', 'aqualuxe' ),
            'section'     => 'aqualuxe_header_options',
            'type'        => 'checkbox',
        )
    );

    // Add header wishlist setting
    $wp_customize->add_setting(
        'aqualuxe_header_wishlist',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_wishlist',
        array(
            'label'       => __( 'Header Wishlist', 'aqualuxe' ),
            'description' => __( 'Show wishlist in header', 'aqualuxe' ),
            'section'     => 'aqualuxe_header_options',
            'type'        => 'checkbox',
        )
    );

    // Add footer section
    $wp_customize->add_section(
        'aqualuxe_footer_options',
        array(
            'title'       => __( 'Footer Options', 'aqualuxe' ),
            'description' => __( 'Customize the footer section', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 20,
        )
    );

    // Add footer layout setting
    $wp_customize->add_setting(
        'aqualuxe_footer_layout',
        array(
            'default'           => '4-columns',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_layout',
        array(
            'label'       => __( 'Footer Layout', 'aqualuxe' ),
            'description' => __( 'Select the footer layout', 'aqualuxe' ),
            'section'     => 'aqualuxe_footer_options',
            'type'        => 'select',
            'choices'     => array(
                '1-column' => __( '1 Column', 'aqualuxe' ),
                '2-columns' => __( '2 Columns', 'aqualuxe' ),
                '3-columns' => __( '3 Columns', 'aqualuxe' ),
                '4-columns' => __( '4 Columns', 'aqualuxe' ),
            ),
        )
    );

    // Add footer copyright setting
    $wp_customize->add_setting(
        'aqualuxe_footer_copyright',
        array(
            'default'           => '© ' . date( 'Y' ) . ' AquaLuxe. All rights reserved.',
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_copyright',
        array(
            'label'       => __( 'Footer Copyright', 'aqualuxe' ),
            'description' => __( 'Enter the footer copyright text', 'aqualuxe' ),
            'section'     => 'aqualuxe_footer_options',
            'type'        => 'textarea',
        )
    );

    // Add footer payment icons setting
    $wp_customize->add_setting(
        'aqualuxe_footer_payment_icons',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_payment_icons',
        array(
            'label'       => __( 'Footer Payment Icons', 'aqualuxe' ),
            'description' => __( 'Show payment icons in footer', 'aqualuxe' ),
            'section'     => 'aqualuxe_footer_options',
            'type'        => 'checkbox',
        )
    );

    // Add footer newsletter setting
    $wp_customize->add_setting(
        'aqualuxe_footer_newsletter',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_newsletter',
        array(
            'label'       => __( 'Footer Newsletter', 'aqualuxe' ),
            'description' => __( 'Show newsletter form in footer', 'aqualuxe' ),
            'section'     => 'aqualuxe_footer_options',
            'type'        => 'checkbox',
        )
    );

    // Add colors section
    $wp_customize->add_section(
        'aqualuxe_colors',
        array(
            'title'       => __( 'Colors', 'aqualuxe' ),
            'description' => __( 'Customize the theme colors', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 30,
        )
    );

    // Add primary color setting
    $wp_customize->add_setting(
        'aqualuxe_primary_color',
        array(
            'default'           => '#0073aa',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_primary_color',
            array(
                'label'       => __( 'Primary Color', 'aqualuxe' ),
                'description' => __( 'Select the primary color', 'aqualuxe' ),
                'section'     => 'aqualuxe_colors',
            )
        )
    );

    // Add secondary color setting
    $wp_customize->add_setting(
        'aqualuxe_secondary_color',
        array(
            'default'           => '#23282d',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_secondary_color',
            array(
                'label'       => __( 'Secondary Color', 'aqualuxe' ),
                'description' => __( 'Select the secondary color', 'aqualuxe' ),
                'section'     => 'aqualuxe_colors',
            )
        )
    );

    // Add accent color setting
    $wp_customize->add_setting(
        'aqualuxe_accent_color',
        array(
            'default'           => '#00a0d2',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_accent_color',
            array(
                'label'       => __( 'Accent Color', 'aqualuxe' ),
                'description' => __( 'Select the accent color', 'aqualuxe' ),
                'section'     => 'aqualuxe_colors',
            )
        )
    );

    // Add text color setting
    $wp_customize->add_setting(
        'aqualuxe_text_color',
        array(
            'default'           => '#333333',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_text_color',
            array(
                'label'       => __( 'Text Color', 'aqualuxe' ),
                'description' => __( 'Select the text color', 'aqualuxe' ),
                'section'     => 'aqualuxe_colors',
            )
        )
    );

    // Add background color setting
    $wp_customize->add_setting(
        'aqualuxe_background_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_background_color',
            array(
                'label'       => __( 'Background Color', 'aqualuxe' ),
                'description' => __( 'Select the background color', 'aqualuxe' ),
                'section'     => 'aqualuxe_colors',
            )
        )
    );

    // Add typography section
    $wp_customize->add_section(
        'aqualuxe_typography',
        array(
            'title'       => __( 'Typography', 'aqualuxe' ),
            'description' => __( 'Customize the theme typography', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 40,
        )
    );

    // Add body font setting
    $wp_customize->add_setting(
        'aqualuxe_body_font',
        array(
            'default'           => 'system-ui',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_font',
        array(
            'label'       => __( 'Body Font', 'aqualuxe' ),
            'description' => __( 'Select the body font', 'aqualuxe' ),
            'section'     => 'aqualuxe_typography',
            'type'        => 'select',
            'choices'     => array(
                'system-ui' => __( 'System UI', 'aqualuxe' ),
                'inter' => __( 'Inter', 'aqualuxe' ),
                'roboto' => __( 'Roboto', 'aqualuxe' ),
                'open-sans' => __( 'Open Sans', 'aqualuxe' ),
                'lato' => __( 'Lato', 'aqualuxe' ),
                'montserrat' => __( 'Montserrat', 'aqualuxe' ),
                'poppins' => __( 'Poppins', 'aqualuxe' ),
            ),
        )
    );

    // Add heading font setting
    $wp_customize->add_setting(
        'aqualuxe_heading_font',
        array(
            'default'           => 'system-ui',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_font',
        array(
            'label'       => __( 'Heading Font', 'aqualuxe' ),
            'description' => __( 'Select the heading font', 'aqualuxe' ),
            'section'     => 'aqualuxe_typography',
            'type'        => 'select',
            'choices'     => array(
                'system-ui' => __( 'System UI', 'aqualuxe' ),
                'inter' => __( 'Inter', 'aqualuxe' ),
                'roboto' => __( 'Roboto', 'aqualuxe' ),
                'open-sans' => __( 'Open Sans', 'aqualuxe' ),
                'lato' => __( 'Lato', 'aqualuxe' ),
                'montserrat' => __( 'Montserrat', 'aqualuxe' ),
                'poppins' => __( 'Poppins', 'aqualuxe' ),
                'playfair-display' => __( 'Playfair Display', 'aqualuxe' ),
                'merriweather' => __( 'Merriweather', 'aqualuxe' ),
            ),
        )
    );

    // Add body font size setting
    $wp_customize->add_setting(
        'aqualuxe_body_font_size',
        array(
            'default'           => '16',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_font_size',
        array(
            'label'       => __( 'Body Font Size (px)', 'aqualuxe' ),
            'description' => __( 'Select the body font size', 'aqualuxe' ),
            'section'     => 'aqualuxe_typography',
            'type'        => 'number',
            'input_attrs' => array(
                'min' => 12,
                'max' => 24,
                'step' => 1,
            ),
        )
    );

    // Add heading font weight setting
    $wp_customize->add_setting(
        'aqualuxe_heading_font_weight',
        array(
            'default'           => '700',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_font_weight',
        array(
            'label'       => __( 'Heading Font Weight', 'aqualuxe' ),
            'description' => __( 'Select the heading font weight', 'aqualuxe' ),
            'section'     => 'aqualuxe_typography',
            'type'        => 'select',
            'choices'     => array(
                '400' => __( 'Regular (400)', 'aqualuxe' ),
                '500' => __( 'Medium (500)', 'aqualuxe' ),
                '600' => __( 'Semi-Bold (600)', 'aqualuxe' ),
                '700' => __( 'Bold (700)', 'aqualuxe' ),
                '800' => __( 'Extra Bold (800)', 'aqualuxe' ),
            ),
        )
    );

    // Add layout section
    $wp_customize->add_section(
        'aqualuxe_layout',
        array(
            'title'       => __( 'Layout', 'aqualuxe' ),
            'description' => __( 'Customize the theme layout', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 50,
        )
    );

    // Add container width setting
    $wp_customize->add_setting(
        'aqualuxe_container_width',
        array(
            'default'           => '1200',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_container_width',
        array(
            'label'       => __( 'Container Width (px)', 'aqualuxe' ),
            'description' => __( 'Select the container width', 'aqualuxe' ),
            'section'     => 'aqualuxe_layout',
            'type'        => 'number',
            'input_attrs' => array(
                'min' => 960,
                'max' => 1600,
                'step' => 10,
            ),
        )
    );

    // Add sidebar position setting
    $wp_customize->add_setting(
        'aqualuxe_sidebar_position',
        array(
            'default'           => 'right',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_sidebar_position',
        array(
            'label'       => __( 'Sidebar Position', 'aqualuxe' ),
            'description' => __( 'Select the sidebar position', 'aqualuxe' ),
            'section'     => 'aqualuxe_layout',
            'type'        => 'select',
            'choices'     => array(
                'left' => __( 'Left', 'aqualuxe' ),
                'right' => __( 'Right', 'aqualuxe' ),
            ),
        )
    );

    // Add blog layout setting
    $wp_customize->add_setting(
        'aqualuxe_blog_layout',
        array(
            'default'           => 'grid',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_blog_layout',
        array(
            'label'       => __( 'Blog Layout', 'aqualuxe' ),
            'description' => __( 'Select the blog layout', 'aqualuxe' ),
            'section'     => 'aqualuxe_layout',
            'type'        => 'select',
            'choices'     => array(
                'grid' => __( 'Grid', 'aqualuxe' ),
                'list' => __( 'List', 'aqualuxe' ),
                'masonry' => __( 'Masonry', 'aqualuxe' ),
            ),
        )
    );

    // Add shop layout setting
    $wp_customize->add_setting(
        'aqualuxe_shop_layout',
        array(
            'default'           => 'grid',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_shop_layout',
        array(
            'label'       => __( 'Shop Layout', 'aqualuxe' ),
            'description' => __( 'Select the shop layout', 'aqualuxe' ),
            'section'     => 'aqualuxe_layout',
            'type'        => 'select',
            'choices'     => array(
                'grid' => __( 'Grid', 'aqualuxe' ),
                'list' => __( 'List', 'aqualuxe' ),
                'masonry' => __( 'Masonry', 'aqualuxe' ),
            ),
        )
    );

    // Add shop columns setting
    $wp_customize->add_setting(
        'aqualuxe_shop_columns',
        array(
            'default'           => '4',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_shop_columns',
        array(
            'label'       => __( 'Shop Columns', 'aqualuxe' ),
            'description' => __( 'Select the number of columns for the shop', 'aqualuxe' ),
            'section'     => 'aqualuxe_layout',
            'type'        => 'number',
            'input_attrs' => array(
                'min' => 2,
                'max' => 6,
                'step' => 1,
            ),
        )
    );

    // Add shop products per page setting
    $wp_customize->add_setting(
        'aqualuxe_shop_products_per_page',
        array(
            'default'           => '12',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_shop_products_per_page',
        array(
            'label'       => __( 'Shop Products Per Page', 'aqualuxe' ),
            'description' => __( 'Select the number of products per page for the shop', 'aqualuxe' ),
            'section'     => 'aqualuxe_layout',
            'type'        => 'number',
            'input_attrs' => array(
                'min' => 4,
                'max' => 48,
                'step' => 4,
            ),
        )
    );

    // Add WooCommerce section
    $wp_customize->add_section(
        'aqualuxe_woocommerce',
        array(
            'title'       => __( 'WooCommerce', 'aqualuxe' ),
            'description' => __( 'Customize WooCommerce settings', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 60,
        )
    );

    // Add product gallery zoom setting
    $wp_customize->add_setting(
        'aqualuxe_product_gallery_zoom',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_gallery_zoom',
        array(
            'label'       => __( 'Product Gallery Zoom', 'aqualuxe' ),
            'description' => __( 'Enable product gallery zoom', 'aqualuxe' ),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'checkbox',
        )
    );

    // Add product gallery lightbox setting
    $wp_customize->add_setting(
        'aqualuxe_product_gallery_lightbox',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_gallery_lightbox',
        array(
            'label'       => __( 'Product Gallery Lightbox', 'aqualuxe' ),
            'description' => __( 'Enable product gallery lightbox', 'aqualuxe' ),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'checkbox',
        )
    );

    // Add product gallery slider setting
    $wp_customize->add_setting(
        'aqualuxe_product_gallery_slider',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_gallery_slider',
        array(
            'label'       => __( 'Product Gallery Slider', 'aqualuxe' ),
            'description' => __( 'Enable product gallery slider', 'aqualuxe' ),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'checkbox',
        )
    );

    // Add product image hover effect setting
    $wp_customize->add_setting(
        'aqualuxe_product_image_hover',
        array(
            'default'           => 'zoom',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_image_hover',
        array(
            'label'       => __( 'Product Image Hover Effect', 'aqualuxe' ),
            'description' => __( 'Select the product image hover effect', 'aqualuxe' ),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'select',
            'choices'     => array(
                'none' => __( 'None', 'aqualuxe' ),
                'zoom' => __( 'Zoom', 'aqualuxe' ),
                'swap' => __( 'Image Swap', 'aqualuxe' ),
                'slide' => __( 'Slide', 'aqualuxe' ),
            ),
        )
    );

    // Add product quick view setting
    $wp_customize->add_setting(
        'aqualuxe_product_quick_view',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_quick_view',
        array(
            'label'       => __( 'Product Quick View', 'aqualuxe' ),
            'description' => __( 'Enable product quick view', 'aqualuxe' ),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'checkbox',
        )
    );

    // Add product wishlist setting
    $wp_customize->add_setting(
        'aqualuxe_product_wishlist',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_wishlist',
        array(
            'label'       => __( 'Product Wishlist', 'aqualuxe' ),
            'description' => __( 'Enable product wishlist', 'aqualuxe' ),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'checkbox',
        )
    );

    // Add product compare setting
    $wp_customize->add_setting(
        'aqualuxe_product_compare',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_compare',
        array(
            'label'       => __( 'Product Compare', 'aqualuxe' ),
            'description' => __( 'Enable product compare', 'aqualuxe' ),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'checkbox',
        )
    );

    // Add product ajax add to cart setting
    $wp_customize->add_setting(
        'aqualuxe_product_ajax_add_to_cart',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_ajax_add_to_cart',
        array(
            'label'       => __( 'Product AJAX Add to Cart', 'aqualuxe' ),
            'description' => __( 'Enable product AJAX add to cart', 'aqualuxe' ),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'checkbox',
        )
    );

    // Add product related products setting
    $wp_customize->add_setting(
        'aqualuxe_product_related_products',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_related_products',
        array(
            'label'       => __( 'Product Related Products', 'aqualuxe' ),
            'description' => __( 'Show related products', 'aqualuxe' ),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'checkbox',
        )
    );

    // Add product upsells setting
    $wp_customize->add_setting(
        'aqualuxe_product_upsells',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_upsells',
        array(
            'label'       => __( 'Product Upsells', 'aqualuxe' ),
            'description' => __( 'Show upsell products', 'aqualuxe' ),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'checkbox',
        )
    );

    // Add cart cross-sells setting
    $wp_customize->add_setting(
        'aqualuxe_cart_cross_sells',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_cart_cross_sells',
        array(
            'label'       => __( 'Cart Cross-Sells', 'aqualuxe' ),
            'description' => __( 'Show cross-sell products in cart', 'aqualuxe' ),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'checkbox',
        )
    );

    // Add performance section
    $wp_customize->add_section(
        'aqualuxe_performance',
        array(
            'title'       => __( 'Performance', 'aqualuxe' ),
            'description' => __( 'Customize performance settings', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 70,
        )
    );

    // Add lazy loading setting
    $wp_customize->add_setting(
        'aqualuxe_lazy_loading',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_lazy_loading',
        array(
            'label'       => __( 'Lazy Loading', 'aqualuxe' ),
            'description' => __( 'Enable lazy loading for images', 'aqualuxe' ),
            'section'     => 'aqualuxe_performance',
            'type'        => 'checkbox',
        )
    );

    // Add minify assets setting
    $wp_customize->add_setting(
        'aqualuxe_minify_assets',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_minify_assets',
        array(
            'label'       => __( 'Minify Assets', 'aqualuxe' ),
            'description' => __( 'Enable minification of CSS and JS assets', 'aqualuxe' ),
            'section'     => 'aqualuxe_performance',
            'type'        => 'checkbox',
        )
    );

    // Add preload critical assets setting
    $wp_customize->add_setting(
        'aqualuxe_preload_assets',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_preload_assets',
        array(
            'label'       => __( 'Preload Critical Assets', 'aqualuxe' ),
            'description' => __( 'Enable preloading of critical assets', 'aqualuxe' ),
            'section'     => 'aqualuxe_performance',
            'type'        => 'checkbox',
        )
    );

    // Add defer non-critical JS setting
    $wp_customize->add_setting(
        'aqualuxe_defer_js',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_defer_js',
        array(
            'label'       => __( 'Defer Non-Critical JS', 'aqualuxe' ),
            'description' => __( 'Enable deferring of non-critical JavaScript', 'aqualuxe' ),
            'section'     => 'aqualuxe_performance',
            'type'        => 'checkbox',
        )
    );

    // Add advanced section
    $wp_customize->add_section(
        'aqualuxe_advanced',
        array(
            'title'       => __( 'Advanced', 'aqualuxe' ),
            'description' => __( 'Advanced theme settings', 'aqualuxe' ),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 80,
        )
    );

    // Add custom CSS setting
    $wp_customize->add_setting(
        'aqualuxe_custom_css',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_strip_all_tags',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_custom_css',
        array(
            'label'       => __( 'Custom CSS', 'aqualuxe' ),
            'description' => __( 'Add custom CSS styles', 'aqualuxe' ),
            'section'     => 'aqualuxe_advanced',
            'type'        => 'textarea',
        )
    );

    // Add custom JS setting
    $wp_customize->add_setting(
        'aqualuxe_custom_js',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_strip_all_tags',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_custom_js',
        array(
            'label'       => __( 'Custom JS', 'aqualuxe' ),
            'description' => __( 'Add custom JavaScript code', 'aqualuxe' ),
            'section'     => 'aqualuxe_advanced',
            'type'        => 'textarea',
        )
    );

    // Add custom header code setting
    $wp_customize->add_setting(
        'aqualuxe_header_code',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_code',
        array(
            'label'       => __( 'Header Code', 'aqualuxe' ),
            'description' => __( 'Add custom code to the header (e.g., analytics)', 'aqualuxe' ),
            'section'     => 'aqualuxe_advanced',
            'type'        => 'textarea',
        )
    );

    // Add custom footer code setting
    $wp_customize->add_setting(
        'aqualuxe_footer_code',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_code',
        array(
            'label'       => __( 'Footer Code', 'aqualuxe' ),
            'description' => __( 'Add custom code to the footer', 'aqualuxe' ),
            'section'     => 'aqualuxe_advanced',
            'type'        => 'textarea',
        )
    );

    // Add maintenance mode setting
    $wp_customize->add_setting(
        'aqualuxe_maintenance_mode',
        array(
            'default'           => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_maintenance_mode',
        array(
            'label'       => __( 'Maintenance Mode', 'aqualuxe' ),
            'description' => __( 'Enable maintenance mode (only administrators can view the site)', 'aqualuxe' ),
            'section'     => 'aqualuxe_advanced',
            'type'        => 'checkbox',
        )
    );

    // Add maintenance mode message setting
    $wp_customize->add_setting(
        'aqualuxe_maintenance_message',
        array(
            'default'           => __( 'We are currently performing scheduled maintenance. Please check back soon.', 'aqualuxe' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_maintenance_message',
        array(
            'label'       => __( 'Maintenance Message', 'aqualuxe' ),
            'description' => __( 'Message to display during maintenance mode', 'aqualuxe' ),
            'section'     => 'aqualuxe_advanced',
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
    wp_enqueue_script( 'aqualuxe-customizer', aqualuxe_asset_path( '/js/customizer.js' ), array( 'customize-preview' ), AQUALUXE_VERSION, true );
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
function aqualuxe_sanitize_select( $input, $setting = null ) {
    // Get the list of possible select options
    $choices = $setting->manager->get_control( $setting->id )->choices;

    // Return input if valid or return default option
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Generate inline CSS for customizer options.
 */
function aqualuxe_customizer_css() {
    $primary_color = get_theme_mod( 'aqualuxe_primary_color', '#0073aa' );
    $secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#23282d' );
    $accent_color = get_theme_mod( 'aqualuxe_accent_color', '#00a0d2' );
    $text_color = get_theme_mod( 'aqualuxe_text_color', '#333333' );
    $background_color = get_theme_mod( 'aqualuxe_background_color', '#ffffff' );
    $body_font = get_theme_mod( 'aqualuxe_body_font', 'system-ui' );
    $heading_font = get_theme_mod( 'aqualuxe_heading_font', 'system-ui' );
    $body_font_size = get_theme_mod( 'aqualuxe_body_font_size', '16' );
    $heading_font_weight = get_theme_mod( 'aqualuxe_heading_font_weight', '700' );
    $container_width = get_theme_mod( 'aqualuxe_container_width', '1200' );

    // Convert font family to CSS value
    $body_font_css = 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
    $heading_font_css = 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';

    if ( $body_font === 'inter' ) {
        $body_font_css = '"Inter", sans-serif';
    } elseif ( $body_font === 'roboto' ) {
        $body_font_css = '"Roboto", sans-serif';
    } elseif ( $body_font === 'open-sans' ) {
        $body_font_css = '"Open Sans", sans-serif';
    } elseif ( $body_font === 'lato' ) {
        $body_font_css = '"Lato", sans-serif';
    } elseif ( $body_font === 'montserrat' ) {
        $body_font_css = '"Montserrat", sans-serif';
    } elseif ( $body_font === 'poppins' ) {
        $body_font_css = '"Poppins", sans-serif';
    }

    if ( $heading_font === 'inter' ) {
        $heading_font_css = '"Inter", sans-serif';
    } elseif ( $heading_font === 'roboto' ) {
        $heading_font_css = '"Roboto", sans-serif';
    } elseif ( $heading_font === 'open-sans' ) {
        $heading_font_css = '"Open Sans", sans-serif';
    } elseif ( $heading_font === 'lato' ) {
        $heading_font_css = '"Lato", sans-serif';
    } elseif ( $heading_font === 'montserrat' ) {
        $heading_font_css = '"Montserrat", sans-serif';
    } elseif ( $heading_font === 'poppins' ) {
        $heading_font_css = '"Poppins", sans-serif';
    } elseif ( $heading_font === 'playfair-display' ) {
        $heading_font_css = '"Playfair Display", serif';
    } elseif ( $heading_font === 'merriweather' ) {
        $heading_font_css = '"Merriweather", serif';
    }

    $css = '
        :root {
            --primary-color: ' . $primary_color . ';
            --secondary-color: ' . $secondary_color . ';
            --accent-color: ' . $accent_color . ';
            --text-color: ' . $text_color . ';
            --background-color: ' . $background_color . ';
            --body-font: ' . $body_font_css . ';
            --heading-font: ' . $heading_font_css . ';
            --body-font-size: ' . $body_font_size . 'px;
            --heading-font-weight: ' . $heading_font_weight . ';
            --container-width: ' . $container_width . 'px;
        }

        body {
            font-family: var(--body-font);
            font-size: var(--body-font-size);
            color: var(--text-color);
            background-color: var(--background-color);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--heading-font);
            font-weight: var(--heading-font-weight);
            color: var(--secondary-color);
        }

        a {
            color: var(--primary-color);
        }

        a:hover {
            color: var(--accent-color);
        }

        .container {
            max-width: var(--container-width);
        }

        .button,
        button,
        input[type="button"],
        input[type="reset"],
        input[type="submit"] {
            background-color: var(--primary-color);
            color: #ffffff;
        }

        .button:hover,
        button:hover,
        input[type="button"]:hover,
        input[type="reset"]:hover,
        input[type="submit"]:hover {
            background-color: var(--accent-color);
        }

        .site-header {
            background-color: var(--background-color);
        }

        .site-footer {
            background-color: var(--secondary-color);
            color: #ffffff;
        }

        .main-navigation a {
            color: var(--text-color);
        }

        .main-navigation a:hover {
            color: var(--primary-color);
        }

        .entry-title a {
            color: var(--secondary-color);
        }

        .entry-title a:hover {
            color: var(--primary-color);
        }

        .widget-title {
            color: var(--secondary-color);
        }

        /* WooCommerce styles */
        .woocommerce #respond input#submit,
        .woocommerce a.button,
        .woocommerce button.button,
        .woocommerce input.button {
            background-color: var(--primary-color);
            color: #ffffff;
        }

        .woocommerce #respond input#submit:hover,
        .woocommerce a.button:hover,
        .woocommerce button.button:hover,
        .woocommerce input.button:hover {
            background-color: var(--accent-color);
            color: #ffffff;
        }

        .woocommerce span.onsale {
            background-color: var(--accent-color);
            color: #ffffff;
        }

        .woocommerce ul.products li.product .price {
            color: var(--primary-color);
        }

        .woocommerce div.product p.price,
        .woocommerce div.product span.price {
            color: var(--primary-color);
        }

        .woocommerce #respond input#submit.alt,
        .woocommerce a.button.alt,
        .woocommerce button.button.alt,
        .woocommerce input.button.alt {
            background-color: var(--primary-color);
            color: #ffffff;
        }

        .woocommerce #respond input#submit.alt:hover,
        .woocommerce a.button.alt:hover,
        .woocommerce button.button.alt:hover,
        .woocommerce input.button.alt:hover {
            background-color: var(--accent-color);
            color: #ffffff;
        }
    ';

    // Add custom CSS
    $custom_css = get_theme_mod( 'aqualuxe_custom_css', '' );
    if ( ! empty( $custom_css ) ) {
        $css .= $custom_css;
    }

    return $css;
}

/**
 * Enqueue customizer CSS.
 */
function aqualuxe_enqueue_customizer_css() {
    wp_add_inline_style( 'aqualuxe-styles', aqualuxe_customizer_css() );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_enqueue_customizer_css', 20 );

/**
 * Enqueue Google Fonts.
 */
function aqualuxe_enqueue_google_fonts() {
    $body_font = get_theme_mod( 'aqualuxe_body_font', 'system-ui' );
    $heading_font = get_theme_mod( 'aqualuxe_heading_font', 'system-ui' );
    $fonts = array();

    // Add body font
    if ( $body_font !== 'system-ui' ) {
        $fonts[] = $body_font;
    }

    // Add heading font
    if ( $heading_font !== 'system-ui' && $heading_font !== $body_font ) {
        $fonts[] = $heading_font;
    }

    // If no custom fonts, return
    if ( empty( $fonts ) ) {
        return;
    }

    // Format fonts for Google Fonts URL
    $fonts_url = '';
    $font_families = array();

    foreach ( $fonts as $font ) {
        $font_families[] = str_replace( '-', '+', $font ) . ':400,500,600,700';
    }

    $query_args = array(
        'family' => implode( '|', $font_families ),
        'display' => 'swap',
    );

    $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css2' );

    // Enqueue Google Fonts
    wp_enqueue_style( 'aqualuxe-google-fonts', $fonts_url, array(), null );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_enqueue_google_fonts' );

/**
 * Add custom header code.
 */
function aqualuxe_header_code() {
    $header_code = get_theme_mod( 'aqualuxe_header_code', '' );
    if ( ! empty( $header_code ) ) {
        echo $header_code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
add_action( 'wp_head', 'aqualuxe_header_code' );

/**
 * Add custom footer code.
 */
function aqualuxe_footer_code() {
    $footer_code = get_theme_mod( 'aqualuxe_footer_code', '' );
    if ( ! empty( $footer_code ) ) {
        echo $footer_code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
add_action( 'wp_footer', 'aqualuxe_footer_code' );

/**
 * Add custom JavaScript.
 */
function aqualuxe_custom_js() {
    $custom_js = get_theme_mod( 'aqualuxe_custom_js', '' );
    if ( ! empty( $custom_js ) ) {
        echo '<script>' . $custom_js . '</script>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
add_action( 'wp_footer', 'aqualuxe_custom_js' );

/**
 * Enable maintenance mode.
 */
function aqualuxe_maintenance_mode() {
    $maintenance_mode = get_theme_mod( 'aqualuxe_maintenance_mode', false );
    if ( $maintenance_mode && ! current_user_can( 'manage_options' ) && ! is_admin() ) {
        $maintenance_message = get_theme_mod( 'aqualuxe_maintenance_message', __( 'We are currently performing scheduled maintenance. Please check back soon.', 'aqualuxe' ) );
        wp_die( $maintenance_message, __( 'Maintenance Mode', 'aqualuxe' ), array( 'response' => 503 ) );
    }
}
add_action( 'template_redirect', 'aqualuxe_maintenance_mode' );