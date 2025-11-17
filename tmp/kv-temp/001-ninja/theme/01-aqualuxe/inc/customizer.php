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
function aqualuxe_customize_register($wp_customize) {
    $wp_customize->get_setting('blogname')->transport         = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
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
            'title'       => __('AquaLuxe Theme Options', 'aqualuxe'),
            'description' => __('Theme Options for AquaLuxe', 'aqualuxe'),
            'priority'    => 130,
        )
    );

    // Header Section
    $wp_customize->add_section(
        'aqualuxe_header_options',
        array(
            'title'       => __('Header Options', 'aqualuxe'),
            'description' => __('Customize the header section', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 10,
        )
    );

    // Sticky Header
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
            'label'       => __('Enable Sticky Header', 'aqualuxe'),
            'section'     => 'aqualuxe_header_options',
            'type'        => 'checkbox',
            'description' => __('Check to enable sticky header on scroll', 'aqualuxe'),
        )
    );

    // Top Bar
    $wp_customize->add_setting(
        'aqualuxe_enable_topbar',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_topbar',
        array(
            'label'       => __('Enable Top Bar', 'aqualuxe'),
            'section'     => 'aqualuxe_header_options',
            'type'        => 'checkbox',
            'description' => __('Check to enable the top bar above the main header', 'aqualuxe'),
        )
    );

    // Phone Number
    $wp_customize->add_setting(
        'aqualuxe_phone_number',
        array(
            'default'           => '+1 (234) 567-8900',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_phone_number',
        array(
            'label'       => __('Phone Number', 'aqualuxe'),
            'section'     => 'aqualuxe_header_options',
            'type'        => 'text',
            'description' => __('Enter your phone number to display in the header', 'aqualuxe'),
        )
    );

    // Email Address
    $wp_customize->add_setting(
        'aqualuxe_email_address',
        array(
            'default'           => 'info@aqualuxe.com',
            'sanitize_callback' => 'sanitize_email',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_email_address',
        array(
            'label'       => __('Email Address', 'aqualuxe'),
            'section'     => 'aqualuxe_header_options',
            'type'        => 'email',
            'description' => __('Enter your email address to display in the header', 'aqualuxe'),
        )
    );

    // Footer Section
    $wp_customize->add_section(
        'aqualuxe_footer_options',
        array(
            'title'       => __('Footer Options', 'aqualuxe'),
            'description' => __('Customize the footer section', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 20,
        )
    );

    // Footer Logo
    $wp_customize->add_setting(
        'aqualuxe_footer_logo',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'aqualuxe_footer_logo',
            array(
                'label'       => __('Footer Logo', 'aqualuxe'),
                'section'     => 'aqualuxe_footer_options',
                'description' => __('Upload a logo for the footer. If not set, the main logo will be used.', 'aqualuxe'),
            )
        )
    );

    // Copyright Text
    $wp_customize->add_setting(
        'aqualuxe_copyright_text',
        array(
            'default'           => sprintf(__('© %s AquaLuxe. All rights reserved.', 'aqualuxe'), date('Y')),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_copyright_text',
        array(
            'label'       => __('Copyright Text', 'aqualuxe'),
            'section'     => 'aqualuxe_footer_options',
            'type'        => 'textarea',
            'description' => __('Enter your copyright text for the footer', 'aqualuxe'),
        )
    );

    // Social Media Section
    $wp_customize->add_section(
        'aqualuxe_social_options',
        array(
            'title'       => __('Social Media', 'aqualuxe'),
            'description' => __('Add your social media links', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 30,
        )
    );

    // Facebook
    $wp_customize->add_setting(
        'aqualuxe_facebook_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_facebook_url',
        array(
            'label'       => __('Facebook URL', 'aqualuxe'),
            'section'     => 'aqualuxe_social_options',
            'type'        => 'url',
            'description' => __('Enter your Facebook profile/page URL', 'aqualuxe'),
        )
    );

    // Twitter
    $wp_customize->add_setting(
        'aqualuxe_twitter_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_twitter_url',
        array(
            'label'       => __('Twitter URL', 'aqualuxe'),
            'section'     => 'aqualuxe_social_options',
            'type'        => 'url',
            'description' => __('Enter your Twitter profile URL', 'aqualuxe'),
        )
    );

    // Instagram
    $wp_customize->add_setting(
        'aqualuxe_instagram_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_instagram_url',
        array(
            'label'       => __('Instagram URL', 'aqualuxe'),
            'section'     => 'aqualuxe_social_options',
            'type'        => 'url',
            'description' => __('Enter your Instagram profile URL', 'aqualuxe'),
        )
    );

    // YouTube
    $wp_customize->add_setting(
        'aqualuxe_youtube_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_youtube_url',
        array(
            'label'       => __('YouTube URL', 'aqualuxe'),
            'section'     => 'aqualuxe_social_options',
            'type'        => 'url',
            'description' => __('Enter your YouTube channel URL', 'aqualuxe'),
        )
    );

    // LinkedIn
    $wp_customize->add_setting(
        'aqualuxe_linkedin_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_linkedin_url',
        array(
            'label'       => __('LinkedIn URL', 'aqualuxe'),
            'section'     => 'aqualuxe_social_options',
            'type'        => 'url',
            'description' => __('Enter your LinkedIn profile/company URL', 'aqualuxe'),
        )
    );

    // Colors Section
    $wp_customize->add_section(
        'aqualuxe_colors_options',
        array(
            'title'       => __('Theme Colors', 'aqualuxe'),
            'description' => __('Customize the theme colors', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 40,
        )
    );

    // Primary Color
    $wp_customize->add_setting(
        'aqualuxe_primary_color',
        array(
            'default'           => '#0077B6',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_primary_color',
            array(
                'label'       => __('Primary Color', 'aqualuxe'),
                'section'     => 'aqualuxe_colors_options',
                'description' => __('Choose the primary color for the theme', 'aqualuxe'),
            )
        )
    );

    // Secondary Color
    $wp_customize->add_setting(
        'aqualuxe_secondary_color',
        array(
            'default'           => '#00B4D8',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_secondary_color',
            array(
                'label'       => __('Secondary Color', 'aqualuxe'),
                'section'     => 'aqualuxe_colors_options',
                'description' => __('Choose the secondary color for the theme', 'aqualuxe'),
            )
        )
    );

    // Accent Color
    $wp_customize->add_setting(
        'aqualuxe_accent_color',
        array(
            'default'           => '#FFD700',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_accent_color',
            array(
                'label'       => __('Accent Color', 'aqualuxe'),
                'section'     => 'aqualuxe_colors_options',
                'description' => __('Choose the accent color for the theme', 'aqualuxe'),
            )
        )
    );

    // Dark Mode Default
    $wp_customize->add_setting(
        'aqualuxe_dark_mode_default',
        array(
            'default'           => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_dark_mode_default',
        array(
            'label'       => __('Enable Dark Mode by Default', 'aqualuxe'),
            'section'     => 'aqualuxe_colors_options',
            'type'        => 'checkbox',
            'description' => __('Check to enable dark mode by default. Users can still toggle between light and dark mode.', 'aqualuxe'),
        )
    );

    // Typography Section
    $wp_customize->add_section(
        'aqualuxe_typography_options',
        array(
            'title'       => __('Typography', 'aqualuxe'),
            'description' => __('Customize the typography settings', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 50,
        )
    );

    // Heading Font
    $wp_customize->add_setting(
        'aqualuxe_heading_font',
        array(
            'default'           => 'Playfair Display',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_font',
        array(
            'label'       => __('Heading Font', 'aqualuxe'),
            'section'     => 'aqualuxe_typography_options',
            'type'        => 'select',
            'choices'     => array(
                'Playfair Display' => 'Playfair Display',
                'Montserrat'       => 'Montserrat',
                'Roboto'           => 'Roboto',
                'Open Sans'        => 'Open Sans',
                'Lato'             => 'Lato',
                'Poppins'          => 'Poppins',
                'Merriweather'     => 'Merriweather',
                'Raleway'          => 'Raleway',
            ),
            'description' => __('Select the font for headings', 'aqualuxe'),
        )
    );

    // Body Font
    $wp_customize->add_setting(
        'aqualuxe_body_font',
        array(
            'default'           => 'Montserrat',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_font',
        array(
            'label'       => __('Body Font', 'aqualuxe'),
            'section'     => 'aqualuxe_typography_options',
            'type'        => 'select',
            'choices'     => array(
                'Montserrat'       => 'Montserrat',
                'Roboto'           => 'Roboto',
                'Open Sans'        => 'Open Sans',
                'Lato'             => 'Lato',
                'Poppins'          => 'Poppins',
                'Raleway'          => 'Raleway',
                'Source Sans Pro'  => 'Source Sans Pro',
                'Nunito'           => 'Nunito',
            ),
            'description' => __('Select the font for body text', 'aqualuxe'),
        )
    );

    // Layout Section
    $wp_customize->add_section(
        'aqualuxe_layout_options',
        array(
            'title'       => __('Layout Options', 'aqualuxe'),
            'description' => __('Customize the layout settings', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 60,
        )
    );

    // Container Width
    $wp_customize->add_setting(
        'aqualuxe_container_width',
        array(
            'default'           => '1280',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_container_width',
        array(
            'label'       => __('Container Width (px)', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_options',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 960,
                'max'  => 1920,
                'step' => 10,
            ),
            'description' => __('Set the maximum width of the content container in pixels', 'aqualuxe'),
        )
    );

    // Blog Layout
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
            'label'       => __('Blog Layout', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_options',
            'type'        => 'select',
            'choices'     => array(
                'grid'    => __('Grid', 'aqualuxe'),
                'list'    => __('List', 'aqualuxe'),
                'masonry' => __('Masonry', 'aqualuxe'),
            ),
            'description' => __('Select the layout for the blog archive pages', 'aqualuxe'),
        )
    );

    // Shop Layout
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
            'label'       => __('Shop Layout', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_options',
            'type'        => 'select',
            'choices'     => array(
                'grid'    => __('Grid', 'aqualuxe'),
                'list'    => __('List', 'aqualuxe'),
                'masonry' => __('Masonry', 'aqualuxe'),
            ),
            'description' => __('Select the layout for the shop archive pages', 'aqualuxe'),
        )
    );

    // Sidebar Position
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
            'label'       => __('Sidebar Position', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_options',
            'type'        => 'select',
            'choices'     => array(
                'right' => __('Right', 'aqualuxe'),
                'left'  => __('Left', 'aqualuxe'),
                'none'  => __('No Sidebar', 'aqualuxe'),
            ),
            'description' => __('Select the position of the sidebar', 'aqualuxe'),
        )
    );

    // WooCommerce Section
    if (class_exists('WooCommerce')) {
        $wp_customize->add_section(
            'aqualuxe_woocommerce_options',
            array(
                'title'       => __('WooCommerce Options', 'aqualuxe'),
                'description' => __('Customize WooCommerce settings', 'aqualuxe'),
                'panel'       => 'aqualuxe_theme_options',
                'priority'    => 70,
            )
        );

        // Products per row
        $wp_customize->add_setting(
            'aqualuxe_products_per_row',
            array(
                'default'           => '3',
                'sanitize_callback' => 'absint',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_products_per_row',
            array(
                'label'       => __('Products per Row', 'aqualuxe'),
                'section'     => 'aqualuxe_woocommerce_options',
                'type'        => 'number',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 6,
                    'step' => 1,
                ),
                'description' => __('Set the number of products per row in the shop', 'aqualuxe'),
            )
        );

        // Products per page
        $wp_customize->add_setting(
            'aqualuxe_products_per_page',
            array(
                'default'           => '12',
                'sanitize_callback' => 'absint',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_products_per_page',
            array(
                'label'       => __('Products per Page', 'aqualuxe'),
                'section'     => 'aqualuxe_woocommerce_options',
                'type'        => 'number',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 100,
                    'step' => 1,
                ),
                'description' => __('Set the number of products per page in the shop', 'aqualuxe'),
            )
        );

        // Related Products Count
        $wp_customize->add_setting(
            'aqualuxe_related_products_count',
            array(
                'default'           => '4',
                'sanitize_callback' => 'absint',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_related_products_count',
            array(
                'label'       => __('Related Products Count', 'aqualuxe'),
                'section'     => 'aqualuxe_woocommerce_options',
                'type'        => 'number',
                'input_attrs' => array(
                    'min'  => 0,
                    'max'  => 12,
                    'step' => 1,
                ),
                'description' => __('Set the number of related products (0 to disable)', 'aqualuxe'),
            )
        );

        // Quick View
        $wp_customize->add_setting(
            'aqualuxe_enable_quick_view',
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_enable_quick_view',
            array(
                'label'       => __('Enable Quick View', 'aqualuxe'),
                'section'     => 'aqualuxe_woocommerce_options',
                'type'        => 'checkbox',
                'description' => __('Check to enable quick view feature for products', 'aqualuxe'),
            )
        );

        // Wishlist
        $wp_customize->add_setting(
            'aqualuxe_enable_wishlist',
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_enable_wishlist',
            array(
                'label'       => __('Enable Wishlist', 'aqualuxe'),
                'section'     => 'aqualuxe_woocommerce_options',
                'type'        => 'checkbox',
                'description' => __('Check to enable wishlist feature for products', 'aqualuxe'),
            )
        );
    }

    // Performance Section
    $wp_customize->add_section(
        'aqualuxe_performance_options',
        array(
            'title'       => __('Performance Options', 'aqualuxe'),
            'description' => __('Optimize theme performance', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 80,
        )
    );

    // Lazy Loading
    $wp_customize->add_setting(
        'aqualuxe_enable_lazy_loading',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_lazy_loading',
        array(
            'label'       => __('Enable Lazy Loading', 'aqualuxe'),
            'section'     => 'aqualuxe_performance_options',
            'type'        => 'checkbox',
            'description' => __('Check to enable lazy loading for images', 'aqualuxe'),
        )
    );

    // Minify CSS
    $wp_customize->add_setting(
        'aqualuxe_minify_css',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_minify_css',
        array(
            'label'       => __('Minify CSS', 'aqualuxe'),
            'section'     => 'aqualuxe_performance_options',
            'type'        => 'checkbox',
            'description' => __('Check to enable CSS minification', 'aqualuxe'),
        )
    );

    // Minify JS
    $wp_customize->add_setting(
        'aqualuxe_minify_js',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_minify_js',
        array(
            'label'       => __('Minify JavaScript', 'aqualuxe'),
            'section'     => 'aqualuxe_performance_options',
            'type'        => 'checkbox',
            'description' => __('Check to enable JavaScript minification', 'aqualuxe'),
        )
    );

    // Preload Key Resources
    $wp_customize->add_setting(
        'aqualuxe_preload_resources',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_preload_resources',
        array(
            'label'       => __('Preload Key Resources', 'aqualuxe'),
            'section'     => 'aqualuxe_performance_options',
            'type'        => 'checkbox',
            'description' => __('Check to enable preloading of key resources', 'aqualuxe'),
        )
    );

    // Advanced Section
    $wp_customize->add_section(
        'aqualuxe_advanced_options',
        array(
            'title'       => __('Advanced Options', 'aqualuxe'),
            'description' => __('Advanced theme settings', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 90,
        )
    );

    // Custom CSS
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
            'label'       => __('Custom CSS', 'aqualuxe'),
            'section'     => 'aqualuxe_advanced_options',
            'type'        => 'textarea',
            'description' => __('Add your custom CSS here', 'aqualuxe'),
        )
    );

    // Custom JavaScript
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
            'label'       => __('Custom JavaScript', 'aqualuxe'),
            'section'     => 'aqualuxe_advanced_options',
            'type'        => 'textarea',
            'description' => __('Add your custom JavaScript here (without script tags)', 'aqualuxe'),
        )
    );

    // Google Analytics
    $wp_customize->add_setting(
        'aqualuxe_google_analytics',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_strip_all_tags',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_google_analytics',
        array(
            'label'       => __('Google Analytics Code', 'aqualuxe'),
            'section'     => 'aqualuxe_advanced_options',
            'type'        => 'textarea',
            'description' => __('Add your Google Analytics tracking code here (with script tags)', 'aqualuxe'),
        )
    );

    // Header Scripts
    $wp_customize->add_setting(
        'aqualuxe_header_scripts',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_scripts',
        array(
            'label'       => __('Header Scripts', 'aqualuxe'),
            'section'     => 'aqualuxe_advanced_options',
            'type'        => 'textarea',
            'description' => __('Add scripts to the header (before closing head tag)', 'aqualuxe'),
        )
    );

    // Footer Scripts
    $wp_customize->add_setting(
        'aqualuxe_footer_scripts',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_scripts',
        array(
            'label'       => __('Footer Scripts', 'aqualuxe'),
            'section'     => 'aqualuxe_advanced_options',
            'type'        => 'textarea',
            'description' => __('Add scripts to the footer (before closing body tag)', 'aqualuxe'),
        )
    );
}
add_action('customize_register', 'aqualuxe_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogname() {
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogdescription() {
    bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
    wp_enqueue_script('aqualuxe-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array('customize-preview'), AQUALUXE_VERSION, true);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');

/**
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

/**
 * Sanitize select.
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string
 */
function aqualuxe_sanitize_select($input, $setting) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    // If the input is a valid key, return it; otherwise, return the default.
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Generate inline CSS for customizer options.
 */
function aqualuxe_customizer_css() {
    $primary_color   = get_theme_mod('aqualuxe_primary_color', '#0077B6');
    $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#00B4D8');
    $accent_color    = get_theme_mod('aqualuxe_accent_color', '#FFD700');
    $heading_font    = get_theme_mod('aqualuxe_heading_font', 'Playfair Display');
    $body_font       = get_theme_mod('aqualuxe_body_font', 'Montserrat');
    $container_width = get_theme_mod('aqualuxe_container_width', '1280');
    $custom_css      = get_theme_mod('aqualuxe_custom_css', '');

    $css = '';

    // Primary Color
    $css .= "
        :root {
            --color-primary: {$primary_color};
            --color-secondary: {$secondary_color};
            --color-accent: {$accent_color};
            --font-heading: '{$heading_font}', serif;
            --font-body: '{$body_font}', sans-serif;
            --container-width: {$container_width}px;
        }
    ";

    // Custom CSS
    if ($custom_css) {
        $css .= $custom_css;
    }

    if ($css) {
        wp_add_inline_style('aqualuxe-main', $css);
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_customizer_css');

/**
 * Add scripts to head and footer based on customizer settings.
 */
function aqualuxe_header_scripts() {
    $header_scripts = get_theme_mod('aqualuxe_header_scripts', '');
    $google_analytics = get_theme_mod('aqualuxe_google_analytics', '');

    if ($header_scripts) {
        echo $header_scripts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    if ($google_analytics) {
        echo $google_analytics; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
add_action('wp_head', 'aqualuxe_header_scripts');

function aqualuxe_footer_scripts() {
    $footer_scripts = get_theme_mod('aqualuxe_footer_scripts', '');
    $custom_js = get_theme_mod('aqualuxe_custom_js', '');

    if ($footer_scripts) {
        echo $footer_scripts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    if ($custom_js) {
        echo '<script>' . $custom_js . '</script>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
add_action('wp_footer', 'aqualuxe_footer_scripts');

/**
 * Add preload for key resources.
 */
function aqualuxe_preload_resources() {
    $preload_resources = get_theme_mod('aqualuxe_preload_resources', true);

    if ($preload_resources) {
        echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    }
}
add_action('wp_head', 'aqualuxe_preload_resources', 1);

/**
 * Set WooCommerce options based on customizer settings.
 */
function aqualuxe_woocommerce_customizer_settings() {
    if (class_exists('WooCommerce')) {
        // Products per page
        $products_per_page = get_theme_mod('aqualuxe_products_per_page', 12);
        add_filter('loop_shop_per_page', function () use ($products_per_page) {
            return $products_per_page;
        }, 20);

        // Related products count
        add_filter('woocommerce_output_related_products_args', function ($args) {
            $related_count = get_theme_mod('aqualuxe_related_products_count', 4);
            $args['posts_per_page'] = $related_count;
            return $args;
        });
    }
}
add_action('init', 'aqualuxe_woocommerce_customizer_settings');