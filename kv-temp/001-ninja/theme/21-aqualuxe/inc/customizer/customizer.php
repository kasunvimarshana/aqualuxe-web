<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 * @since 1.0.0
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
    $wp_customize->add_panel('aqualuxe_theme_options', array(
        'title'       => __('AquaLuxe Theme Options', 'aqualuxe'),
        'description' => __('Customize your AquaLuxe theme settings', 'aqualuxe'),
        'priority'    => 130,
    ));

    // Add Color Scheme Section
    $wp_customize->add_section('aqualuxe_colors', array(
        'title'       => __('Colors', 'aqualuxe'),
        'description' => __('Customize the colors of your theme', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 10,
    ));

    // Primary Color
    $wp_customize->add_setting('aqualuxe_primary_color', array(
        'default'           => '#0891b2', // Default teal-600
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', array(
        'label'       => __('Primary Color', 'aqualuxe'),
        'description' => __('Select the primary color for buttons, links, and accents', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
        'settings'    => 'aqualuxe_primary_color',
    )));

    // Secondary Color
    $wp_customize->add_setting('aqualuxe_secondary_color', array(
        'default'           => '#1e40af', // Default blue-800
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', array(
        'label'       => __('Secondary Color', 'aqualuxe'),
        'description' => __('Select the secondary color for gradients and backgrounds', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
        'settings'    => 'aqualuxe_secondary_color',
    )));

    // Color Scheme
    $wp_customize->add_setting('aqualuxe_color_scheme', array(
        'default'           => 'light',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_color_scheme', array(
        'label'       => __('Default Color Scheme', 'aqualuxe'),
        'description' => __('Choose the default color scheme for your site', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
        'type'        => 'select',
        'choices'     => array(
            'light' => __('Light', 'aqualuxe'),
            'dark'  => __('Dark', 'aqualuxe'),
            'auto'  => __('Auto (follows system preference)', 'aqualuxe'),
        ),
    ));

    // Add Header Options Section
    $wp_customize->add_section('aqualuxe_header', array(
        'title'       => __('Header Options', 'aqualuxe'),
        'description' => __('Customize the header of your theme', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 20,
    ));

    // Sticky Header
    $wp_customize->add_setting('aqualuxe_sticky_header', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_sticky_header', array(
        'label'       => __('Enable Sticky Header', 'aqualuxe'),
        'description' => __('Keep the header visible when scrolling down', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'checkbox',
    ));

    // Header Style
    $wp_customize->add_setting('aqualuxe_header_style', array(
        'default'           => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_header_style', array(
        'label'       => __('Header Style', 'aqualuxe'),
        'description' => __('Choose the style for your header', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'select',
        'choices'     => array(
            'default'     => __('Default', 'aqualuxe'),
            'transparent' => __('Transparent', 'aqualuxe'),
            'centered'    => __('Centered Logo', 'aqualuxe'),
            'minimal'     => __('Minimal', 'aqualuxe'),
        ),
    ));

    // Add Footer Options Section
    $wp_customize->add_section('aqualuxe_footer', array(
        'title'       => __('Footer Options', 'aqualuxe'),
        'description' => __('Customize the footer of your theme', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 30,
    ));

    // Footer Columns
    $wp_customize->add_setting('aqualuxe_footer_columns', array(
        'default'           => '4',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_footer_columns', array(
        'label'       => __('Footer Widget Columns', 'aqualuxe'),
        'description' => __('Choose the number of widget columns in the footer', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'select',
        'choices'     => array(
            '1' => __('1 Column', 'aqualuxe'),
            '2' => __('2 Columns', 'aqualuxe'),
            '3' => __('3 Columns', 'aqualuxe'),
            '4' => __('4 Columns', 'aqualuxe'),
        ),
    ));

    // Footer Copyright Text
    $wp_customize->add_setting('aqualuxe_footer_copyright', array(
        'default'           => sprintf(__('© %1$s %2$s. All rights reserved.', 'aqualuxe'), date_i18n('Y'), get_bloginfo('name')),
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_footer_copyright', array(
        'label'       => __('Footer Copyright Text', 'aqualuxe'),
        'description' => __('Enter your copyright text for the footer', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'textarea',
    ));

    // Add Homepage Options Section
    $wp_customize->add_section('aqualuxe_homepage', array(
        'title'       => __('Homepage Options', 'aqualuxe'),
        'description' => __('Customize the homepage of your theme', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 40,
    ));

    // Hero Title
    $wp_customize->add_setting('aqualuxe_hero_title', array(
        'default'           => __('Bringing elegance to aquatic life – globally.', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_hero_title', array(
        'label'       => __('Hero Title', 'aqualuxe'),
        'description' => __('Enter the title for the hero section', 'aqualuxe'),
        'section'     => 'aqualuxe_homepage',
        'type'        => 'text',
    ));

    // Hero Description
    $wp_customize->add_setting('aqualuxe_hero_description', array(
        'default'           => __('Premium Ornamental Aquatic Solutions for local and international markets. Discover our exclusive collection of rare fish, aquatic plants, and custom aquarium designs.', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_hero_description', array(
        'label'       => __('Hero Description', 'aqualuxe'),
        'description' => __('Enter the description for the hero section', 'aqualuxe'),
        'section'     => 'aqualuxe_homepage',
        'type'        => 'textarea',
    ));

    // Hero Button Text
    $wp_customize->add_setting('aqualuxe_hero_button_text', array(
        'default'           => __('Shop Now', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_hero_button_text', array(
        'label'       => __('Primary Button Text', 'aqualuxe'),
        'description' => __('Enter the text for the primary button', 'aqualuxe'),
        'section'     => 'aqualuxe_homepage',
        'type'        => 'text',
    ));

    // Hero Button URL
    $wp_customize->add_setting('aqualuxe_hero_button_url', array(
        'default'           => home_url('/shop'),
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_hero_button_url', array(
        'label'       => __('Primary Button URL', 'aqualuxe'),
        'description' => __('Enter the URL for the primary button', 'aqualuxe'),
        'section'     => 'aqualuxe_homepage',
        'type'        => 'url',
    ));

    // Hero Button Text 2
    $wp_customize->add_setting('aqualuxe_hero_button_text_2', array(
        'default'           => __('Our Services', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_hero_button_text_2', array(
        'label'       => __('Secondary Button Text', 'aqualuxe'),
        'description' => __('Enter the text for the secondary button', 'aqualuxe'),
        'section'     => 'aqualuxe_homepage',
        'type'        => 'text',
    ));

    // Hero Button URL 2
    $wp_customize->add_setting('aqualuxe_hero_button_url_2', array(
        'default'           => home_url('/services'),
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_hero_button_url_2', array(
        'label'       => __('Secondary Button URL', 'aqualuxe'),
        'description' => __('Enter the URL for the secondary button', 'aqualuxe'),
        'section'     => 'aqualuxe_homepage',
        'type'        => 'url',
    ));

    // Hero Background Image
    $wp_customize->add_setting('aqualuxe_hero_image', array(
        'default'           => get_template_directory_uri() . '/assets/images/hero-default.jpg',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aqualuxe_hero_image', array(
        'label'       => __('Hero Background Image', 'aqualuxe'),
        'description' => __('Upload or select the background image for the hero section', 'aqualuxe'),
        'section'     => 'aqualuxe_homepage',
        'settings'    => 'aqualuxe_hero_image',
    )));

    // Add Shop Options Section
    $wp_customize->add_section('aqualuxe_shop', array(
        'title'       => __('Shop Options', 'aqualuxe'),
        'description' => __('Customize the shop settings of your theme', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 50,
    ));

    // Products Per Page
    $wp_customize->add_setting('aqualuxe_products_per_page', array(
        'default'           => 12,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_products_per_page', array(
        'label'       => __('Products Per Page', 'aqualuxe'),
        'description' => __('Set the number of products to display per page', 'aqualuxe'),
        'section'     => 'aqualuxe_shop',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 4,
            'max'  => 48,
            'step' => 4,
        ),
    ));

    // Shop Columns
    $wp_customize->add_setting('aqualuxe_shop_columns', array(
        'default'           => 3,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_shop_columns', array(
        'label'       => __('Shop Columns', 'aqualuxe'),
        'description' => __('Set the number of columns in the shop', 'aqualuxe'),
        'section'     => 'aqualuxe_shop',
        'type'        => 'select',
        'choices'     => array(
            2 => __('2 Columns', 'aqualuxe'),
            3 => __('3 Columns', 'aqualuxe'),
            4 => __('4 Columns', 'aqualuxe'),
        ),
    ));

    // Related Products
    $wp_customize->add_setting('aqualuxe_related_products', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_related_products', array(
        'label'       => __('Show Related Products', 'aqualuxe'),
        'description' => __('Display related products on single product pages', 'aqualuxe'),
        'section'     => 'aqualuxe_shop',
        'type'        => 'checkbox',
    ));

    // Quick View
    $wp_customize->add_setting('aqualuxe_quick_view', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_quick_view', array(
        'label'       => __('Enable Quick View', 'aqualuxe'),
        'description' => __('Allow customers to preview products in a modal', 'aqualuxe'),
        'section'     => 'aqualuxe_shop',
        'type'        => 'checkbox',
    ));

    // Add Social Media Section
    $wp_customize->add_section('aqualuxe_social', array(
        'title'       => __('Social Media', 'aqualuxe'),
        'description' => __('Add your social media profile links', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 60,
    ));

    // Facebook
    $wp_customize->add_setting('aqualuxe_facebook', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_facebook', array(
        'label'       => __('Facebook URL', 'aqualuxe'),
        'description' => __('Enter your Facebook profile URL', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'url',
    ));

    // Twitter
    $wp_customize->add_setting('aqualuxe_twitter', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_twitter', array(
        'label'       => __('Twitter URL', 'aqualuxe'),
        'description' => __('Enter your Twitter profile URL', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'url',
    ));

    // Twitter Username (without @)
    $wp_customize->add_setting('aqualuxe_twitter_username', array(
        'default'           => 'aqualuxe',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_twitter_username', array(
        'label'       => __('Twitter Username', 'aqualuxe'),
        'description' => __('Enter your Twitter username without the @ symbol', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'text',
    ));

    // Instagram
    $wp_customize->add_setting('aqualuxe_instagram', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_instagram', array(
        'label'       => __('Instagram URL', 'aqualuxe'),
        'description' => __('Enter your Instagram profile URL', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'url',
    ));

    // YouTube
    $wp_customize->add_setting('aqualuxe_youtube', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_youtube', array(
        'label'       => __('YouTube URL', 'aqualuxe'),
        'description' => __('Enter your YouTube channel URL', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'url',
    ));

    // LinkedIn
    $wp_customize->add_setting('aqualuxe_linkedin', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_linkedin', array(
        'label'       => __('LinkedIn URL', 'aqualuxe'),
        'description' => __('Enter your LinkedIn profile URL', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'url',
    ));

    // Pinterest
    $wp_customize->add_setting('aqualuxe_pinterest', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_pinterest', array(
        'label'       => __('Pinterest URL', 'aqualuxe'),
        'description' => __('Enter your Pinterest profile URL', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'url',
    ));

    // WhatsApp
    $wp_customize->add_setting('aqualuxe_whatsapp', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_whatsapp', array(
        'label'       => __('WhatsApp Number', 'aqualuxe'),
        'description' => __('Enter your WhatsApp number with country code (e.g., +1234567890)', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'text',
    ));

    // Add Contact Information Section
    $wp_customize->add_section('aqualuxe_contact', array(
        'title'       => __('Contact Information', 'aqualuxe'),
        'description' => __('Add your contact information', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 70,
    ));

    // Phone
    $wp_customize->add_setting('aqualuxe_phone', array(
        'default'           => '+94 123 456 7890',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_phone', array(
        'label'       => __('Phone Number', 'aqualuxe'),
        'description' => __('Enter your phone number', 'aqualuxe'),
        'section'     => 'aqualuxe_contact',
        'type'        => 'text',
    ));

    // Email
    $wp_customize->add_setting('aqualuxe_email', array(
        'default'           => 'info@aqualuxe.com',
        'sanitize_callback' => 'sanitize_email',
    ));

    $wp_customize->add_control('aqualuxe_email', array(
        'label'       => __('Email Address', 'aqualuxe'),
        'description' => __('Enter your email address', 'aqualuxe'),
        'section'     => 'aqualuxe_contact',
        'type'        => 'email',
    ));

    // Address
    $wp_customize->add_setting('aqualuxe_street_address', array(
        'default'           => '123 Aqua Lane',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_street_address', array(
        'label'       => __('Street Address', 'aqualuxe'),
        'description' => __('Enter your street address', 'aqualuxe'),
        'section'     => 'aqualuxe_contact',
        'type'        => 'text',
    ));

    // City
    $wp_customize->add_setting('aqualuxe_city', array(
        'default'           => 'Marine City',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_city', array(
        'label'       => __('City', 'aqualuxe'),
        'description' => __('Enter your city', 'aqualuxe'),
        'section'     => 'aqualuxe_contact',
        'type'        => 'text',
    ));

    // Region
    $wp_customize->add_setting('aqualuxe_region', array(
        'default'           => 'Colombo',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_region', array(
        'label'       => __('Region/State', 'aqualuxe'),
        'description' => __('Enter your region or state', 'aqualuxe'),
        'section'     => 'aqualuxe_contact',
        'type'        => 'text',
    ));

    // Postal Code
    $wp_customize->add_setting('aqualuxe_postal_code', array(
        'default'           => '10000',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_postal_code', array(
        'label'       => __('Postal/ZIP Code', 'aqualuxe'),
        'description' => __('Enter your postal or ZIP code', 'aqualuxe'),
        'section'     => 'aqualuxe_contact',
        'type'        => 'text',
    ));

    // Country
    $wp_customize->add_setting('aqualuxe_country', array(
        'default'           => 'Sri Lanka',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_country', array(
        'label'       => __('Country', 'aqualuxe'),
        'description' => __('Enter your country', 'aqualuxe'),
        'section'     => 'aqualuxe_contact',
        'type'        => 'text',
    ));

    // Business Hours
    $wp_customize->add_setting('aqualuxe_business_hours', array(
        'default'           => 'Mon-Sat: 9AM - 6PM',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_business_hours', array(
        'label'       => __('Business Hours', 'aqualuxe'),
        'description' => __('Enter your business hours', 'aqualuxe'),
        'section'     => 'aqualuxe_contact',
        'type'        => 'text',
    ));

    // Google Maps Embed Code
    $wp_customize->add_setting('aqualuxe_google_maps', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('aqualuxe_google_maps', array(
        'label'       => __('Google Maps Embed Code', 'aqualuxe'),
        'description' => __('Enter your Google Maps embed code', 'aqualuxe'),
        'section'     => 'aqualuxe_contact',
        'type'        => 'textarea',
    ));

    // Add Performance Options Section
    $wp_customize->add_section('aqualuxe_performance', array(
        'title'       => __('Performance Options', 'aqualuxe'),
        'description' => __('Optimize your site performance', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 80,
    ));

    // Lazy Loading
    $wp_customize->add_setting('aqualuxe_lazy_loading', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_lazy_loading', array(
        'label'       => __('Enable Lazy Loading', 'aqualuxe'),
        'description' => __('Load images only when they enter the viewport', 'aqualuxe'),
        'section'     => 'aqualuxe_performance',
        'type'        => 'checkbox',
    ));

    // Minify Assets
    $wp_customize->add_setting('aqualuxe_minify_assets', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_minify_assets', array(
        'label'       => __('Minify CSS & JS', 'aqualuxe'),
        'description' => __('Reduce file size of CSS and JavaScript files', 'aqualuxe'),
        'section'     => 'aqualuxe_performance',
        'type'        => 'checkbox',
    ));

    // Preload Critical Assets
    $wp_customize->add_setting('aqualuxe_preload_assets', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_preload_assets', array(
        'label'       => __('Preload Critical Assets', 'aqualuxe'),
        'description' => __('Preload critical CSS and fonts for faster rendering', 'aqualuxe'),
        'section'     => 'aqualuxe_performance',
        'type'        => 'checkbox',
    ));

    // Add Advanced Options Section
    $wp_customize->add_section('aqualuxe_advanced', array(
        'title'       => __('Advanced Options', 'aqualuxe'),
        'description' => __('Advanced theme settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 90,
    ));

    // Custom CSS
    $wp_customize->add_setting('aqualuxe_custom_css', array(
        'default'           => '',
        'sanitize_callback' => 'wp_strip_all_tags',
    ));

    $wp_customize->add_control('aqualuxe_custom_css', array(
        'label'       => __('Custom CSS', 'aqualuxe'),
        'description' => __('Add your custom CSS here', 'aqualuxe'),
        'section'     => 'aqualuxe_advanced',
        'type'        => 'textarea',
    ));

    // Custom JavaScript
    $wp_customize->add_setting('aqualuxe_custom_js', array(
        'default'           => '',
        'sanitize_callback' => 'wp_strip_all_tags',
    ));

    $wp_customize->add_control('aqualuxe_custom_js', array(
        'label'       => __('Custom JavaScript', 'aqualuxe'),
        'description' => __('Add your custom JavaScript here', 'aqualuxe'),
        'section'     => 'aqualuxe_advanced',
        'type'        => 'textarea',
    ));

    // Google Analytics
    $wp_customize->add_setting('aqualuxe_google_analytics', array(
        'default'           => '',
        'sanitize_callback' => 'wp_strip_all_tags',
    ));

    $wp_customize->add_control('aqualuxe_google_analytics', array(
        'label'       => __('Google Analytics Code', 'aqualuxe'),
        'description' => __('Enter your Google Analytics tracking code', 'aqualuxe'),
        'section'     => 'aqualuxe_advanced',
        'type'        => 'textarea',
    ));

    // Facebook Pixel
    $wp_customize->add_setting('aqualuxe_facebook_pixel', array(
        'default'           => '',
        'sanitize_callback' => 'wp_strip_all_tags',
    ));

    $wp_customize->add_control('aqualuxe_facebook_pixel', array(
        'label'       => __('Facebook Pixel Code', 'aqualuxe'),
        'description' => __('Enter your Facebook Pixel tracking code', 'aqualuxe'),
        'section'     => 'aqualuxe_advanced',
        'type'        => 'textarea',
    ));
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
 * Sanitize checkbox values
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

/**
 * Sanitize select values
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_select($input, $setting) {
    // Get the list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    // If the input is a valid key, return it; otherwise, return the default.
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Output custom CSS from customizer settings
 */
function aqualuxe_output_customizer_css() {
    $custom_css = get_theme_mod('aqualuxe_custom_css', '');
    
    if (!empty($custom_css)) {
        echo '<style type="text/css">' . wp_strip_all_tags($custom_css) . '</style>';
    }
}
add_action('wp_head', 'aqualuxe_output_customizer_css', 999);

/**
 * Output custom JavaScript from customizer settings
 */
function aqualuxe_output_customizer_js() {
    $custom_js = get_theme_mod('aqualuxe_custom_js', '');
    
    if (!empty($custom_js)) {
        echo '<script>' . wp_strip_all_tags($custom_js) . '</script>';
    }
    
    // Google Analytics
    $google_analytics = get_theme_mod('aqualuxe_google_analytics', '');
    if (!empty($google_analytics)) {
        echo $google_analytics;
    }
    
    // Facebook Pixel
    $facebook_pixel = get_theme_mod('aqualuxe_facebook_pixel', '');
    if (!empty($facebook_pixel)) {
        echo $facebook_pixel;
    }
}
add_action('wp_footer', 'aqualuxe_output_customizer_js', 999);