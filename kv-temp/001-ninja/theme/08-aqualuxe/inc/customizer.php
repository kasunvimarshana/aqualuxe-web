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
            'title'       => esc_html__( 'AquaLuxe Theme Options', 'aqualuxe' ),
            'description' => esc_html__( 'Customize your AquaLuxe theme settings', 'aqualuxe' ),
            'priority'    => 130,
        )
    );

    // Header Section
    $wp_customize->add_section(
        'aqualuxe_header_options',
        array(
            'title'    => esc_html__( 'Header Options', 'aqualuxe' ),
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
            'type'        => 'checkbox',
            'section'     => 'aqualuxe_header_options',
            'label'       => esc_html__( 'Enable Sticky Header', 'aqualuxe' ),
            'description' => esc_html__( 'Keep the header fixed at the top when scrolling', 'aqualuxe' ),
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
            'type'        => 'select',
            'section'     => 'aqualuxe_header_options',
            'label'       => esc_html__( 'Header Layout', 'aqualuxe' ),
            'description' => esc_html__( 'Choose the header layout style', 'aqualuxe' ),
            'choices'     => array(
                'default'      => esc_html__( 'Default', 'aqualuxe' ),
                'centered'     => esc_html__( 'Centered', 'aqualuxe' ),
                'transparent'  => esc_html__( 'Transparent', 'aqualuxe' ),
                'minimal'      => esc_html__( 'Minimal', 'aqualuxe' ),
            ),
        )
    );

    // Header Contact Info
    $wp_customize->add_setting(
        'aqualuxe_header_phone',
        array(
            'default'           => '+1 (555) 123-4567',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_phone',
        array(
            'type'        => 'text',
            'section'     => 'aqualuxe_header_options',
            'label'       => esc_html__( 'Phone Number', 'aqualuxe' ),
            'description' => esc_html__( 'Enter your business phone number', 'aqualuxe' ),
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_header_email',
        array(
            'default'           => 'info@aqualuxe.com',
            'sanitize_callback' => 'sanitize_email',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_email',
        array(
            'type'        => 'email',
            'section'     => 'aqualuxe_header_options',
            'label'       => esc_html__( 'Email Address', 'aqualuxe' ),
            'description' => esc_html__( 'Enter your business email address', 'aqualuxe' ),
        )
    );

    // Footer Section
    $wp_customize->add_section(
        'aqualuxe_footer_options',
        array(
            'title'    => esc_html__( 'Footer Options', 'aqualuxe' ),
            'priority' => 20,
            'panel'    => 'aqualuxe_theme_options',
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
            'type'        => 'select',
            'section'     => 'aqualuxe_footer_options',
            'label'       => esc_html__( 'Footer Layout', 'aqualuxe' ),
            'description' => esc_html__( 'Choose the footer column layout', 'aqualuxe' ),
            'choices'     => array(
                '4-columns' => esc_html__( '4 Columns', 'aqualuxe' ),
                '3-columns' => esc_html__( '3 Columns', 'aqualuxe' ),
                '2-columns' => esc_html__( '2 Columns', 'aqualuxe' ),
                '1-column'  => esc_html__( '1 Column', 'aqualuxe' ),
            ),
        )
    );

    // Footer Copyright
    $wp_customize->add_setting(
        'aqualuxe_footer_copyright',
        array(
            'default'           => sprintf( esc_html__( '© %s AquaLuxe. All Rights Reserved.', 'aqualuxe' ), date( 'Y' ) ),
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_copyright',
        array(
            'type'        => 'textarea',
            'section'     => 'aqualuxe_footer_options',
            'label'       => esc_html__( 'Copyright Text', 'aqualuxe' ),
            'description' => esc_html__( 'Enter your copyright text', 'aqualuxe' ),
        )
    );

    // Social Media Section
    $wp_customize->add_section(
        'aqualuxe_social_options',
        array(
            'title'    => esc_html__( 'Social Media', 'aqualuxe' ),
            'priority' => 30,
            'panel'    => 'aqualuxe_theme_options',
        )
    );

    // Facebook
    $wp_customize->add_setting(
        'aqualuxe_facebook_link',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_facebook_link',
        array(
            'type'        => 'url',
            'section'     => 'aqualuxe_social_options',
            'label'       => esc_html__( 'Facebook URL', 'aqualuxe' ),
            'description' => esc_html__( 'Enter your Facebook page URL', 'aqualuxe' ),
        )
    );

    // Twitter
    $wp_customize->add_setting(
        'aqualuxe_twitter_link',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_twitter_link',
        array(
            'type'        => 'url',
            'section'     => 'aqualuxe_social_options',
            'label'       => esc_html__( 'Twitter URL', 'aqualuxe' ),
            'description' => esc_html__( 'Enter your Twitter profile URL', 'aqualuxe' ),
        )
    );

    // Instagram
    $wp_customize->add_setting(
        'aqualuxe_instagram_link',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_instagram_link',
        array(
            'type'        => 'url',
            'section'     => 'aqualuxe_social_options',
            'label'       => esc_html__( 'Instagram URL', 'aqualuxe' ),
            'description' => esc_html__( 'Enter your Instagram profile URL', 'aqualuxe' ),
        )
    );

    // LinkedIn
    $wp_customize->add_setting(
        'aqualuxe_linkedin_link',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_linkedin_link',
        array(
            'type'        => 'url',
            'section'     => 'aqualuxe_social_options',
            'label'       => esc_html__( 'LinkedIn URL', 'aqualuxe' ),
            'description' => esc_html__( 'Enter your LinkedIn profile URL', 'aqualuxe' ),
        )
    );

    // YouTube
    $wp_customize->add_setting(
        'aqualuxe_youtube_link',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_youtube_link',
        array(
            'type'        => 'url',
            'section'     => 'aqualuxe_social_options',
            'label'       => esc_html__( 'YouTube URL', 'aqualuxe' ),
            'description' => esc_html__( 'Enter your YouTube channel URL', 'aqualuxe' ),
        )
    );

    // Pinterest
    $wp_customize->add_setting(
        'aqualuxe_pinterest_link',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_pinterest_link',
        array(
            'type'        => 'url',
            'section'     => 'aqualuxe_social_options',
            'label'       => esc_html__( 'Pinterest URL', 'aqualuxe' ),
            'description' => esc_html__( 'Enter your Pinterest profile URL', 'aqualuxe' ),
        )
    );

    // Colors Section
    $wp_customize->add_section(
        'aqualuxe_colors_options',
        array(
            'title'    => esc_html__( 'Theme Colors', 'aqualuxe' ),
            'priority' => 40,
            'panel'    => 'aqualuxe_theme_options',
        )
    );

    // Primary Color
    $wp_customize->add_setting(
        'aqualuxe_primary_color',
        array(
            'default'           => '#0891B2', // Cyan-600
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_primary_color',
            array(
                'label'       => esc_html__( 'Primary Color', 'aqualuxe' ),
                'description' => esc_html__( 'Choose the primary theme color', 'aqualuxe' ),
                'section'     => 'aqualuxe_colors_options',
            )
        )
    );

    // Secondary Color
    $wp_customize->add_setting(
        'aqualuxe_secondary_color',
        array(
            'default'           => '#0E7490', // Cyan-700
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_secondary_color',
            array(
                'label'       => esc_html__( 'Secondary Color', 'aqualuxe' ),
                'description' => esc_html__( 'Choose the secondary theme color', 'aqualuxe' ),
                'section'     => 'aqualuxe_colors_options',
            )
        )
    );

    // Accent Color
    $wp_customize->add_setting(
        'aqualuxe_accent_color',
        array(
            'default'           => '#14B8A6', // Teal-500
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_accent_color',
            array(
                'label'       => esc_html__( 'Accent Color', 'aqualuxe' ),
                'description' => esc_html__( 'Choose the accent theme color', 'aqualuxe' ),
                'section'     => 'aqualuxe_colors_options',
            )
        )
    );

    // Typography Section
    $wp_customize->add_section(
        'aqualuxe_typography_options',
        array(
            'title'    => esc_html__( 'Typography', 'aqualuxe' ),
            'priority' => 50,
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
            'type'        => 'select',
            'section'     => 'aqualuxe_typography_options',
            'label'       => esc_html__( 'Heading Font', 'aqualuxe' ),
            'description' => esc_html__( 'Select the font for headings', 'aqualuxe' ),
            'choices'     => array(
                'Playfair Display' => esc_html__( 'Playfair Display', 'aqualuxe' ),
                'Montserrat'       => esc_html__( 'Montserrat', 'aqualuxe' ),
                'Roboto'           => esc_html__( 'Roboto', 'aqualuxe' ),
                'Open Sans'        => esc_html__( 'Open Sans', 'aqualuxe' ),
                'Lato'             => esc_html__( 'Lato', 'aqualuxe' ),
                'Poppins'          => esc_html__( 'Poppins', 'aqualuxe' ),
            ),
        )
    );

    // Body Font
    $wp_customize->add_setting(
        'aqualuxe_body_font',
        array(
            'default'           => 'Montserrat',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_font',
        array(
            'type'        => 'select',
            'section'     => 'aqualuxe_typography_options',
            'label'       => esc_html__( 'Body Font', 'aqualuxe' ),
            'description' => esc_html__( 'Select the font for body text', 'aqualuxe' ),
            'choices'     => array(
                'Montserrat'       => esc_html__( 'Montserrat', 'aqualuxe' ),
                'Roboto'           => esc_html__( 'Roboto', 'aqualuxe' ),
                'Open Sans'        => esc_html__( 'Open Sans', 'aqualuxe' ),
                'Lato'             => esc_html__( 'Lato', 'aqualuxe' ),
                'Poppins'          => esc_html__( 'Poppins', 'aqualuxe' ),
                'Playfair Display' => esc_html__( 'Playfair Display', 'aqualuxe' ),
            ),
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
            'type'        => 'number',
            'section'     => 'aqualuxe_typography_options',
            'label'       => esc_html__( 'Base Font Size (px)', 'aqualuxe' ),
            'description' => esc_html__( 'Set the base font size in pixels', 'aqualuxe' ),
            'input_attrs' => array(
                'min'  => 12,
                'max'  => 24,
                'step' => 1,
            ),
        )
    );

    // WooCommerce Section
    if ( class_exists( 'WooCommerce' ) ) {
        $wp_customize->add_section(
            'aqualuxe_woocommerce_options',
            array(
                'title'    => esc_html__( 'WooCommerce Options', 'aqualuxe' ),
                'priority' => 60,
                'panel'    => 'aqualuxe_theme_options',
            )
        );

        // Products per row
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
                'type'        => 'number',
                'section'     => 'aqualuxe_woocommerce_options',
                'label'       => esc_html__( 'Products Per Row', 'aqualuxe' ),
                'description' => esc_html__( 'Number of products to display per row', 'aqualuxe' ),
                'input_attrs' => array(
                    'min'  => 2,
                    'max'  => 6,
                    'step' => 1,
                ),
            )
        );

        // Products per page
        $wp_customize->add_setting(
            'aqualuxe_products_per_page',
            array(
                'default'           => '12',
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_products_per_page',
            array(
                'type'        => 'number',
                'section'     => 'aqualuxe_woocommerce_options',
                'label'       => esc_html__( 'Products Per Page', 'aqualuxe' ),
                'description' => esc_html__( 'Number of products to display per page', 'aqualuxe' ),
                'input_attrs' => array(
                    'min'  => 4,
                    'max'  => 48,
                    'step' => 4,
                ),
            )
        );

        // Related Products
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
                'type'        => 'checkbox',
                'section'     => 'aqualuxe_woocommerce_options',
                'label'       => esc_html__( 'Show Related Products', 'aqualuxe' ),
                'description' => esc_html__( 'Display related products on product pages', 'aqualuxe' ),
            )
        );

        // Quick View
        $wp_customize->add_setting(
            'aqualuxe_quick_view',
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_quick_view',
            array(
                'type'        => 'checkbox',
                'section'     => 'aqualuxe_woocommerce_options',
                'label'       => esc_html__( 'Enable Quick View', 'aqualuxe' ),
                'description' => esc_html__( 'Allow customers to quick view products without visiting the product page', 'aqualuxe' ),
            )
        );

        // Wishlist
        $wp_customize->add_setting(
            'aqualuxe_wishlist',
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_wishlist',
            array(
                'type'        => 'checkbox',
                'section'     => 'aqualuxe_woocommerce_options',
                'label'       => esc_html__( 'Enable Wishlist', 'aqualuxe' ),
                'description' => esc_html__( 'Allow customers to add products to wishlist', 'aqualuxe' ),
            )
        );
    }

    // Blog Section
    $wp_customize->add_section(
        'aqualuxe_blog_options',
        array(
            'title'    => esc_html__( 'Blog Options', 'aqualuxe' ),
            'priority' => 70,
            'panel'    => 'aqualuxe_theme_options',
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
            'type'        => 'select',
            'section'     => 'aqualuxe_blog_options',
            'label'       => esc_html__( 'Blog Layout', 'aqualuxe' ),
            'description' => esc_html__( 'Choose the blog archive layout', 'aqualuxe' ),
            'choices'     => array(
                'grid'    => esc_html__( 'Grid', 'aqualuxe' ),
                'list'    => esc_html__( 'List', 'aqualuxe' ),
                'masonry' => esc_html__( 'Masonry', 'aqualuxe' ),
            ),
        )
    );

    // Featured Image
    $wp_customize->add_setting(
        'aqualuxe_featured_image',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_featured_image',
        array(
            'type'        => 'checkbox',
            'section'     => 'aqualuxe_blog_options',
            'label'       => esc_html__( 'Show Featured Image', 'aqualuxe' ),
            'description' => esc_html__( 'Display featured image on single post', 'aqualuxe' ),
        )
    );

    // Post Meta
    $wp_customize->add_setting(
        'aqualuxe_post_meta',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_post_meta',
        array(
            'type'        => 'checkbox',
            'section'     => 'aqualuxe_blog_options',
            'label'       => esc_html__( 'Show Post Meta', 'aqualuxe' ),
            'description' => esc_html__( 'Display post date, author, categories, etc.', 'aqualuxe' ),
        )
    );

    // Related Posts
    $wp_customize->add_setting(
        'aqualuxe_related_posts',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_related_posts',
        array(
            'type'        => 'checkbox',
            'section'     => 'aqualuxe_blog_options',
            'label'       => esc_html__( 'Show Related Posts', 'aqualuxe' ),
            'description' => esc_html__( 'Display related posts on single post', 'aqualuxe' ),
        )
    );

    // Performance Section
    $wp_customize->add_section(
        'aqualuxe_performance_options',
        array(
            'title'    => esc_html__( 'Performance', 'aqualuxe' ),
            'priority' => 80,
            'panel'    => 'aqualuxe_theme_options',
        )
    );

    // Lazy Loading
    $wp_customize->add_setting(
        'aqualuxe_lazy_loading',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_lazy_loading',
        array(
            'type'        => 'checkbox',
            'section'     => 'aqualuxe_performance_options',
            'label'       => esc_html__( 'Enable Lazy Loading', 'aqualuxe' ),
            'description' => esc_html__( 'Load images only when they enter the viewport', 'aqualuxe' ),
        )
    );

    // Minify Assets
    $wp_customize->add_setting(
        'aqualuxe_minify_assets',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_minify_assets',
        array(
            'type'        => 'checkbox',
            'section'     => 'aqualuxe_performance_options',
            'label'       => esc_html__( 'Minify Assets', 'aqualuxe' ),
            'description' => esc_html__( 'Use minified CSS and JavaScript files', 'aqualuxe' ),
        )
    );

    // Preload Critical Assets
    $wp_customize->add_setting(
        'aqualuxe_preload_assets',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_preload_assets',
        array(
            'type'        => 'checkbox',
            'section'     => 'aqualuxe_performance_options',
            'label'       => esc_html__( 'Preload Critical Assets', 'aqualuxe' ),
            'description' => esc_html__( 'Preload critical CSS and fonts', 'aqualuxe' ),
        )
    );

    // Advanced Section
    $wp_customize->add_section(
        'aqualuxe_advanced_options',
        array(
            'title'    => esc_html__( 'Advanced Options', 'aqualuxe' ),
            'priority' => 90,
            'panel'    => 'aqualuxe_theme_options',
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
            'type'        => 'textarea',
            'section'     => 'aqualuxe_advanced_options',
            'label'       => esc_html__( 'Custom CSS', 'aqualuxe' ),
            'description' => esc_html__( 'Add your custom CSS here', 'aqualuxe' ),
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
            'type'        => 'textarea',
            'section'     => 'aqualuxe_advanced_options',
            'label'       => esc_html__( 'Custom JavaScript', 'aqualuxe' ),
            'description' => esc_html__( 'Add your custom JavaScript here', 'aqualuxe' ),
        )
    );

    // Dark Mode Section
    $wp_customize->add_section(
        'aqualuxe_dark_mode_options',
        array(
            'title'    => esc_html__( 'Dark Mode', 'aqualuxe' ),
            'priority' => 100,
            'panel'    => 'aqualuxe_theme_options',
        )
    );

    // Enable Dark Mode
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
            'type'        => 'checkbox',
            'section'     => 'aqualuxe_dark_mode_options',
            'label'       => esc_html__( 'Enable Dark Mode', 'aqualuxe' ),
            'description' => esc_html__( 'Allow users to switch between light and dark mode', 'aqualuxe' ),
        )
    );

    // Default Mode
    $wp_customize->add_setting(
        'aqualuxe_default_mode',
        array(
            'default'           => 'light',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_default_mode',
        array(
            'type'        => 'select',
            'section'     => 'aqualuxe_dark_mode_options',
            'label'       => esc_html__( 'Default Mode', 'aqualuxe' ),
            'description' => esc_html__( 'Choose the default color mode', 'aqualuxe' ),
            'choices'     => array(
                'light'    => esc_html__( 'Light', 'aqualuxe' ),
                'dark'     => esc_html__( 'Dark', 'aqualuxe' ),
                'system'   => esc_html__( 'System Preference', 'aqualuxe' ),
            ),
        )
    );

    // Dark Mode Background
    $wp_customize->add_setting(
        'aqualuxe_dark_background',
        array(
            'default'           => '#121212',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_background',
            array(
                'label'       => esc_html__( 'Dark Mode Background', 'aqualuxe' ),
                'description' => esc_html__( 'Choose the dark mode background color', 'aqualuxe' ),
                'section'     => 'aqualuxe_dark_mode_options',
            )
        )
    );

    // Dark Mode Text Color
    $wp_customize->add_setting(
        'aqualuxe_dark_text_color',
        array(
            'default'           => '#f5f5f5',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_text_color',
            array(
                'label'       => esc_html__( 'Dark Mode Text Color', 'aqualuxe' ),
                'description' => esc_html__( 'Choose the dark mode text color', 'aqualuxe' ),
                'section'     => 'aqualuxe_dark_mode_options',
            )
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
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function aqualuxe_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true === $checked ) ? true : false );
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
 * Generate CSS from customizer settings
 */
function aqualuxe_generate_custom_css() {
    $primary_color   = get_theme_mod( 'aqualuxe_primary_color', '#0891B2' );
    $secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#0E7490' );
    $accent_color    = get_theme_mod( 'aqualuxe_accent_color', '#14B8A6' );
    $heading_font    = get_theme_mod( 'aqualuxe_heading_font', 'Playfair Display' );
    $body_font       = get_theme_mod( 'aqualuxe_body_font', 'Montserrat' );
    $base_font_size  = get_theme_mod( 'aqualuxe_base_font_size', '16' );
    $custom_css      = get_theme_mod( 'aqualuxe_custom_css', '' );
    $dark_background = get_theme_mod( 'aqualuxe_dark_background', '#121212' );
    $dark_text_color = get_theme_mod( 'aqualuxe_dark_text_color', '#f5f5f5' );

    $css = "
        :root {
            --primary-color: {$primary_color};
            --secondary-color: {$secondary_color};
            --accent-color: {$accent_color};
            --heading-font: '{$heading_font}', serif;
            --body-font: '{$body_font}', sans-serif;
            --base-font-size: {$base_font_size}px;
            --dark-background: {$dark_background};
            --dark-text-color: {$dark_text_color};
        }

        body {
            font-family: var(--body-font);
            font-size: var(--base-font-size);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--heading-font);
        }

        .primary-color {
            color: var(--primary-color);
        }

        .secondary-color {
            color: var(--secondary-color);
        }

        .accent-color {
            color: var(--accent-color);
        }

        .bg-primary {
            background-color: var(--primary-color);
        }

        .bg-secondary {
            background-color: var(--secondary-color);
        }

        .bg-accent {
            background-color: var(--accent-color);
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

        .btn-accent {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .btn-accent:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .dark-mode {
            background-color: var(--dark-background);
            color: var(--dark-text-color);
        }

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
 * Output custom JavaScript to wp_footer
 */
function aqualuxe_output_custom_js() {
    $custom_js = get_theme_mod( 'aqualuxe_custom_js', '' );
    if ( ! empty( $custom_js ) ) {
        echo '<script>' . wp_strip_all_tags( $custom_js ) . '</script>';
    }
}
add_action( 'wp_footer', 'aqualuxe_output_custom_js' );