<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register($wp_customize) {
    // Add selective refresh support
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector' => '.site-title a',
                'render_callback' => 'aqualuxe_customize_partial_blogname',
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector' => '.site-description',
                'render_callback' => 'aqualuxe_customize_partial_blogdescription',
            )
        );
    }

    // Add Theme Options Panel
    $wp_customize->add_panel(
        'aqualuxe_theme_options',
        array(
            'title' => __('AquaLuxe Theme Options', 'aqualuxe'),
            'description' => __('Customize your AquaLuxe theme settings', 'aqualuxe'),
            'priority' => 130,
        )
    );

    // General Settings Section
    $wp_customize->add_section(
        'aqualuxe_general_settings',
        array(
            'title' => __('General Settings', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 10,
        )
    );

    // Color Scheme
    $wp_customize->add_setting(
        'aqualuxe_default_color_scheme',
        array(
            'default' => 'light',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport' => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_default_color_scheme',
        array(
            'label' => __('Default Color Scheme', 'aqualuxe'),
            'section' => 'aqualuxe_general_settings',
            'type' => 'select',
            'choices' => array(
                'light' => __('Light', 'aqualuxe'),
                'dark' => __('Dark', 'aqualuxe'),
            ),
        )
    );

    // Enable Dark Mode Toggle
    $wp_customize->add_setting(
        'aqualuxe_enable_dark_mode',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_dark_mode',
        array(
            'label' => __('Enable Dark Mode Toggle', 'aqualuxe'),
            'section' => 'aqualuxe_general_settings',
            'type' => 'checkbox',
        )
    );

    // Container Width
    $wp_customize->add_setting(
        'aqualuxe_container_width',
        array(
            'default' => '1200',
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_container_width',
        array(
            'label' => __('Container Width (px)', 'aqualuxe'),
            'section' => 'aqualuxe_general_settings',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 960,
                'max' => 1920,
                'step' => 10,
            ),
        )
    );

    // Enable Breadcrumbs
    $wp_customize->add_setting(
        'aqualuxe_enable_breadcrumbs',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_breadcrumbs',
        array(
            'label' => __('Enable Breadcrumbs', 'aqualuxe'),
            'section' => 'aqualuxe_general_settings',
            'type' => 'checkbox',
        )
    );

    // Header Settings Section
    $wp_customize->add_section(
        'aqualuxe_header_settings',
        array(
            'title' => __('Header Settings', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 20,
        )
    );

    // Header Layout
    $wp_customize->add_setting(
        'aqualuxe_header_layout',
        array(
            'default' => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport' => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_layout',
        array(
            'label' => __('Header Layout', 'aqualuxe'),
            'section' => 'aqualuxe_header_settings',
            'type' => 'select',
            'choices' => array(
                'default' => __('Default', 'aqualuxe'),
                'centered' => __('Centered', 'aqualuxe'),
                'split' => __('Split', 'aqualuxe'),
                'minimal' => __('Minimal', 'aqualuxe'),
            ),
        )
    );

    // Enable Top Bar
    $wp_customize->add_setting(
        'aqualuxe_enable_top_bar',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_top_bar',
        array(
            'label' => __('Enable Top Bar', 'aqualuxe'),
            'section' => 'aqualuxe_header_settings',
            'type' => 'checkbox',
        )
    );

    // Enable Header Search
    $wp_customize->add_setting(
        'aqualuxe_enable_header_search',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_header_search',
        array(
            'label' => __('Enable Header Search', 'aqualuxe'),
            'section' => 'aqualuxe_header_settings',
            'type' => 'checkbox',
        )
    );

    // Enable Header Account
    $wp_customize->add_setting(
        'aqualuxe_enable_header_account',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_header_account',
        array(
            'label' => __('Enable Header Account', 'aqualuxe'),
            'section' => 'aqualuxe_header_settings',
            'type' => 'checkbox',
        )
    );

    // Enable Header Cart
    $wp_customize->add_setting(
        'aqualuxe_enable_header_cart',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_header_cart',
        array(
            'label' => __('Enable Header Cart', 'aqualuxe'),
            'section' => 'aqualuxe_header_settings',
            'type' => 'checkbox',
        )
    );

    // Sticky Header
    $wp_customize->add_setting(
        'aqualuxe_enable_sticky_header',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_sticky_header',
        array(
            'label' => __('Enable Sticky Header', 'aqualuxe'),
            'section' => 'aqualuxe_header_settings',
            'type' => 'checkbox',
        )
    );

    // Footer Settings Section
    $wp_customize->add_section(
        'aqualuxe_footer_settings',
        array(
            'title' => __('Footer Settings', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 30,
        )
    );

    // Footer Layout
    $wp_customize->add_setting(
        'aqualuxe_footer_layout',
        array(
            'default' => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport' => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_layout',
        array(
            'label' => __('Footer Layout', 'aqualuxe'),
            'section' => 'aqualuxe_footer_settings',
            'type' => 'select',
            'choices' => array(
                'default' => __('Default (4 Columns)', 'aqualuxe'),
                'three-columns' => __('3 Columns', 'aqualuxe'),
                'two-columns' => __('2 Columns', 'aqualuxe'),
                'one-column' => __('1 Column', 'aqualuxe'),
            ),
        )
    );

    // Footer Text
    $wp_customize->add_setting(
        'aqualuxe_footer_text',
        array(
            'default' => sprintf(
                /* translators: %1$s: current year, %2$s: site name */
                __('&copy; %1$s %2$s. All rights reserved.', 'aqualuxe'),
                date('Y'),
                get_bloginfo('name')
            ),
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_text',
        array(
            'label' => __('Footer Text', 'aqualuxe'),
            'section' => 'aqualuxe_footer_settings',
            'type' => 'textarea',
        )
    );

    // Blog Settings Section
    $wp_customize->add_section(
        'aqualuxe_blog_settings',
        array(
            'title' => __('Blog Settings', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 40,
        )
    );

    // Blog Layout
    $wp_customize->add_setting(
        'aqualuxe_blog_layout',
        array(
            'default' => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport' => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_blog_layout',
        array(
            'label' => __('Blog Layout', 'aqualuxe'),
            'section' => 'aqualuxe_blog_settings',
            'type' => 'select',
            'choices' => array(
                'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
                'left-sidebar' => __('Left Sidebar', 'aqualuxe'),
                'no-sidebar' => __('No Sidebar', 'aqualuxe'),
            ),
        )
    );

    // Enable Related Posts
    $wp_customize->add_setting(
        'aqualuxe_enable_related_posts',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_related_posts',
        array(
            'label' => __('Enable Related Posts', 'aqualuxe'),
            'section' => 'aqualuxe_blog_settings',
            'type' => 'checkbox',
        )
    );

    // Enable Post Share
    $wp_customize->add_setting(
        'aqualuxe_enable_post_share',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_post_share',
        array(
            'label' => __('Enable Post Share', 'aqualuxe'),
            'section' => 'aqualuxe_blog_settings',
            'type' => 'checkbox',
        )
    );

    // Enable Author Bio
    $wp_customize->add_setting(
        'aqualuxe_enable_author_bio',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_author_bio',
        array(
            'label' => __('Enable Author Bio', 'aqualuxe'),
            'section' => 'aqualuxe_blog_settings',
            'type' => 'checkbox',
        )
    );

    // WooCommerce Settings Section
    if (class_exists('WooCommerce')) {
        $wp_customize->add_section(
            'aqualuxe_woocommerce_settings',
            array(
                'title' => __('WooCommerce Settings', 'aqualuxe'),
                'panel' => 'aqualuxe_theme_options',
                'priority' => 50,
            )
        );

        // Shop Layout
        $wp_customize->add_setting(
            'aqualuxe_shop_layout',
            array(
                'default' => 'right-sidebar',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
                'transport' => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_shop_layout',
            array(
                'label' => __('Shop Layout', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_settings',
                'type' => 'select',
                'choices' => array(
                    'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
                    'left-sidebar' => __('Left Sidebar', 'aqualuxe'),
                    'no-sidebar' => __('No Sidebar', 'aqualuxe'),
                ),
            )
        );

        // Product Layout
        $wp_customize->add_setting(
            'aqualuxe_product_layout',
            array(
                'default' => 'no-sidebar',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
                'transport' => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_product_layout',
            array(
                'label' => __('Product Layout', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_settings',
                'type' => 'select',
                'choices' => array(
                    'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
                    'left-sidebar' => __('Left Sidebar', 'aqualuxe'),
                    'no-sidebar' => __('No Sidebar', 'aqualuxe'),
                ),
            )
        );

        // Products Per Row
        $wp_customize->add_setting(
            'aqualuxe_products_per_row',
            array(
                'default' => '3',
                'sanitize_callback' => 'absint',
                'transport' => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_products_per_row',
            array(
                'label' => __('Products Per Row', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_settings',
                'type' => 'select',
                'choices' => array(
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                ),
            )
        );

        // Products Per Page
        $wp_customize->add_setting(
            'aqualuxe_products_per_page',
            array(
                'default' => '12',
                'sanitize_callback' => 'absint',
                'transport' => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_products_per_page',
            array(
                'label' => __('Products Per Page', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_settings',
                'type' => 'number',
                'input_attrs' => array(
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ),
            )
        );

        // Enable Shop Filters
        $wp_customize->add_setting(
            'aqualuxe_enable_shop_filters',
            array(
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport' => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_enable_shop_filters',
            array(
                'label' => __('Enable Shop Filters', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_settings',
                'type' => 'checkbox',
            )
        );

        // Enable Shop View Switcher
        $wp_customize->add_setting(
            'aqualuxe_enable_shop_view_switcher',
            array(
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport' => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_enable_shop_view_switcher',
            array(
                'label' => __('Enable Shop View Switcher', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_settings',
                'type' => 'checkbox',
            )
        );

        // Enable Quick View
        $wp_customize->add_setting(
            'aqualuxe_enable_quick_view',
            array(
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport' => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_enable_quick_view',
            array(
                'label' => __('Enable Quick View', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_settings',
                'type' => 'checkbox',
            )
        );

        // Enable Wishlist
        $wp_customize->add_setting(
            'aqualuxe_enable_wishlist',
            array(
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport' => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_enable_wishlist',
            array(
                'label' => __('Enable Wishlist', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_settings',
                'type' => 'checkbox',
            )
        );

        // Enable Compare
        $wp_customize->add_setting(
            'aqualuxe_enable_compare',
            array(
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport' => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_enable_compare',
            array(
                'label' => __('Enable Compare', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_settings',
                'type' => 'checkbox',
            )
        );

        // Enable Product Share
        $wp_customize->add_setting(
            'aqualuxe_enable_product_share',
            array(
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport' => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_enable_product_share',
            array(
                'label' => __('Enable Product Share', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_settings',
                'type' => 'checkbox',
            )
        );
    }

    // Contact Information Section
    $wp_customize->add_section(
        'aqualuxe_contact_info',
        array(
            'title' => __('Contact Information', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 60,
        )
    );

    // Contact Phone
    $wp_customize->add_setting(
        'aqualuxe_contact_phone',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_contact_phone',
        array(
            'label' => __('Phone Number', 'aqualuxe'),
            'section' => 'aqualuxe_contact_info',
            'type' => 'text',
        )
    );

    // Contact Email
    $wp_customize->add_setting(
        'aqualuxe_contact_email',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_email',
            'transport' => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_contact_email',
        array(
            'label' => __('Email Address', 'aqualuxe'),
            'section' => 'aqualuxe_contact_info',
            'type' => 'email',
        )
    );

    // Contact Address
    $wp_customize->add_setting(
        'aqualuxe_contact_address',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_contact_address',
        array(
            'label' => __('Address', 'aqualuxe'),
            'section' => 'aqualuxe_contact_info',
            'type' => 'text',
        )
    );

    // Contact Hours
    $wp_customize->add_setting(
        'aqualuxe_contact_hours',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_contact_hours',
        array(
            'label' => __('Business Hours', 'aqualuxe'),
            'section' => 'aqualuxe_contact_info',
            'type' => 'text',
        )
    );

    // Social Media Section
    $wp_customize->add_section(
        'aqualuxe_social_media',
        array(
            'title' => __('Social Media', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 70,
        )
    );

    // Facebook
    $wp_customize->add_setting(
        'aqualuxe_social_facebook',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport' => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_facebook',
        array(
            'label' => __('Facebook URL', 'aqualuxe'),
            'section' => 'aqualuxe_social_media',
            'type' => 'url',
        )
    );

    // Twitter
    $wp_customize->add_setting(
        'aqualuxe_social_twitter',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport' => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_twitter',
        array(
            'label' => __('Twitter URL', 'aqualuxe'),
            'section' => 'aqualuxe_social_media',
            'type' => 'url',
        )
    );

    // Instagram
    $wp_customize->add_setting(
        'aqualuxe_social_instagram',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport' => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_instagram',
        array(
            'label' => __('Instagram URL', 'aqualuxe'),
            'section' => 'aqualuxe_social_media',
            'type' => 'url',
        )
    );

    // YouTube
    $wp_customize->add_setting(
        'aqualuxe_social_youtube',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport' => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_youtube',
        array(
            'label' => __('YouTube URL', 'aqualuxe'),
            'section' => 'aqualuxe_social_media',
            'type' => 'url',
        )
    );

    // LinkedIn
    $wp_customize->add_setting(
        'aqualuxe_social_linkedin',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport' => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_linkedin',
        array(
            'label' => __('LinkedIn URL', 'aqualuxe'),
            'section' => 'aqualuxe_social_media',
            'type' => 'url',
        )
    );

    // Pinterest
    $wp_customize->add_setting(
        'aqualuxe_social_pinterest',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport' => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_pinterest',
        array(
            'label' => __('Pinterest URL', 'aqualuxe'),
            'section' => 'aqualuxe_social_media',
            'type' => 'url',
        )
    );

    // Typography Section
    $wp_customize->add_section(
        'aqualuxe_typography',
        array(
            'title' => __('Typography', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 80,
        )
    );

    // Body Font
    $wp_customize->add_setting(
        'aqualuxe_body_font',
        array(
            'default' => 'Inter',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_font',
        array(
            'label' => __('Body Font', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'select',
            'choices' => array(
                'Inter' => 'Inter',
                'Roboto' => 'Roboto',
                'Open Sans' => 'Open Sans',
                'Lato' => 'Lato',
                'Montserrat' => 'Montserrat',
                'Poppins' => 'Poppins',
                'Raleway' => 'Raleway',
                'Source Sans Pro' => 'Source Sans Pro',
                'Nunito' => 'Nunito',
                'Playfair Display' => 'Playfair Display',
            ),
        )
    );

    // Heading Font
    $wp_customize->add_setting(
        'aqualuxe_heading_font',
        array(
            'default' => 'Playfair Display',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_font',
        array(
            'label' => __('Heading Font', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'select',
            'choices' => array(
                'Inter' => 'Inter',
                'Roboto' => 'Roboto',
                'Open Sans' => 'Open Sans',
                'Lato' => 'Lato',
                'Montserrat' => 'Montserrat',
                'Poppins' => 'Poppins',
                'Raleway' => 'Raleway',
                'Source Sans Pro' => 'Source Sans Pro',
                'Nunito' => 'Nunito',
                'Playfair Display' => 'Playfair Display',
            ),
        )
    );

    // Base Font Size
    $wp_customize->add_setting(
        'aqualuxe_base_font_size',
        array(
            'default' => '16',
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_base_font_size',
        array(
            'label' => __('Base Font Size (px)', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 12,
                'max' => 24,
                'step' => 1,
            ),
        )
    );

    // Colors Section
    $wp_customize->add_section(
        'aqualuxe_colors',
        array(
            'title' => __('Colors', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 90,
        )
    );

    // Primary Color
    $wp_customize->add_setting(
        'aqualuxe_primary_color',
        array(
            'default' => '#0077b6',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_primary_color',
            array(
                'label' => __('Primary Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Secondary Color
    $wp_customize->add_setting(
        'aqualuxe_secondary_color',
        array(
            'default' => '#00b4d8',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_secondary_color',
            array(
                'label' => __('Secondary Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Accent Color
    $wp_customize->add_setting(
        'aqualuxe_accent_color',
        array(
            'default' => '#90e0ef',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_accent_color',
            array(
                'label' => __('Accent Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Text Color (Light Mode)
    $wp_customize->add_setting(
        'aqualuxe_text_color_light',
        array(
            'default' => '#333333',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_text_color_light',
            array(
                'label' => __('Text Color (Light Mode)', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Background Color (Light Mode)
    $wp_customize->add_setting(
        'aqualuxe_background_color_light',
        array(
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_background_color_light',
            array(
                'label' => __('Background Color (Light Mode)', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Text Color (Dark Mode)
    $wp_customize->add_setting(
        'aqualuxe_text_color_dark',
        array(
            'default' => '#f0f0f0',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_text_color_dark',
            array(
                'label' => __('Text Color (Dark Mode)', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Background Color (Dark Mode)
    $wp_customize->add_setting(
        'aqualuxe_background_color_dark',
        array(
            'default' => '#121212',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_background_color_dark',
            array(
                'label' => __('Background Color (Dark Mode)', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        )
    );

    // Performance Section
    $wp_customize->add_section(
        'aqualuxe_performance',
        array(
            'title' => __('Performance', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 100,
        )
    );

    // Enable Lazy Loading
    $wp_customize->add_setting(
        'aqualuxe_enable_lazy_loading',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_lazy_loading',
        array(
            'label' => __('Enable Lazy Loading for Images', 'aqualuxe'),
            'section' => 'aqualuxe_performance',
            'type' => 'checkbox',
        )
    );

    // Enable Preload
    $wp_customize->add_setting(
        'aqualuxe_enable_preload',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_preload',
        array(
            'label' => __('Enable Preload for Critical Assets', 'aqualuxe'),
            'section' => 'aqualuxe_performance',
            'type' => 'checkbox',
        )
    );

    // Enable CSS Minification
    $wp_customize->add_setting(
        'aqualuxe_enable_css_minification',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_css_minification',
        array(
            'label' => __('Enable CSS Minification', 'aqualuxe'),
            'section' => 'aqualuxe_performance',
            'type' => 'checkbox',
        )
    );

    // Enable JS Minification
    $wp_customize->add_setting(
        'aqualuxe_enable_js_minification',
        array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport' => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_js_minification',
        array(
            'label' => __('Enable JS Minification', 'aqualuxe'),
            'section' => 'aqualuxe_performance',
            'type' => 'checkbox',
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
    wp_enqueue_script('aqualuxe-customizer', get_template_directory_uri() . '/assets/dist/js/customizer.js', array('customize-preview'), AQUALUXE_VERSION, true);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');

/**
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox($checked) {
    return (isset($checked) && true === $checked) ? true : false;
}

/**
 * Sanitize select.
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_select($input, $setting) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control($setting->id)->choices;

    // If the input is a valid key, return it; otherwise, return the default.
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Generate inline CSS for customizer options
 */
function aqualuxe_customizer_css() {
    $css = '';

    // Container Width
    $container_width = get_theme_mod('aqualuxe_container_width', '1200');
    $css .= ".container { max-width: {$container_width}px; }";

    // Colors
    $primary_color = get_theme_mod('aqualuxe_primary_color', '#0077b6');
    $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#00b4d8');
    $accent_color = get_theme_mod('aqualuxe_accent_color', '#90e0ef');
    $text_color_light = get_theme_mod('aqualuxe_text_color_light', '#333333');
    $background_color_light = get_theme_mod('aqualuxe_background_color_light', '#ffffff');
    $text_color_dark = get_theme_mod('aqualuxe_text_color_dark', '#f0f0f0');
    $background_color_dark = get_theme_mod('aqualuxe_background_color_dark', '#121212');

    // Light Mode Colors
    $css .= ":root {
        --primary-color: {$primary_color};
        --secondary-color: {$secondary_color};
        --accent-color: {$accent_color};
        --text-color: {$text_color_light};
        --background-color: {$background_color_light};
    }";

    // Dark Mode Colors
    $css .= ".dark-mode {
        --text-color: {$text_color_dark};
        --background-color: {$background_color_dark};
    }";

    // Typography
    $body_font = get_theme_mod('aqualuxe_body_font', 'Inter');
    $heading_font = get_theme_mod('aqualuxe_heading_font', 'Playfair Display');
    $base_font_size = get_theme_mod('aqualuxe_base_font_size', '16');

    $css .= "body {
        font-family: '{$body_font}', sans-serif;
        font-size: {$base_font_size}px;
    }";

    $css .= "h1, h2, h3, h4, h5, h6 {
        font-family: '{$heading_font}', serif;
    }";

    // Output the CSS
    if (!empty($css)) {
        wp_add_inline_style('aqualuxe-style', $css);
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_customizer_css', 20);