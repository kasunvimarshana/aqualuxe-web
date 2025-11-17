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
    $wp_customize->add_panel(
        'aqualuxe_theme_options',
        array(
            'title'       => __('AquaLuxe Theme Options', 'aqualuxe'),
            'description' => __('Customize your AquaLuxe theme settings', 'aqualuxe'),
            'priority'    => 130,
        )
    );

    // Add Header & Navigation Section
    $wp_customize->add_section(
        'aqualuxe_header_section',
        array(
            'title'       => __('Header & Navigation', 'aqualuxe'),
            'description' => __('Customize the header and navigation settings', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 10,
        )
    );

    // Add Sticky Header Option
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
            'description' => __('Keep the header visible when scrolling down', 'aqualuxe'),
            'section'     => 'aqualuxe_header_section',
            'type'        => 'checkbox',
        )
    );

    // Add Header Style Option
    $wp_customize->add_setting(
        'aqualuxe_header_style',
        array(
            'default'           => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_style',
        array(
            'label'       => __('Header Style', 'aqualuxe'),
            'description' => __('Choose the header layout style', 'aqualuxe'),
            'section'     => 'aqualuxe_header_section',
            'type'        => 'select',
            'choices'     => array(
                'default'      => __('Default', 'aqualuxe'),
                'centered'     => __('Centered', 'aqualuxe'),
                'transparent'  => __('Transparent', 'aqualuxe'),
                'minimal'      => __('Minimal', 'aqualuxe'),
            ),
        )
    );

    // Add Colors Section
    $wp_customize->add_section(
        'aqualuxe_colors_section',
        array(
            'title'       => __('Colors', 'aqualuxe'),
            'description' => __('Customize the theme colors', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 20,
        )
    );

    // Add Primary Color Option
    $wp_customize->add_setting(
        'aqualuxe_primary_color',
        array(
            'default'           => '#0073aa',
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
                'description' => __('The main accent color used throughout the site', 'aqualuxe'),
                'section'     => 'aqualuxe_colors_section',
            )
        )
    );

    // Add Secondary Color Option
    $wp_customize->add_setting(
        'aqualuxe_secondary_color',
        array(
            'default'           => '#005177',
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
                'description' => __('The secondary accent color used throughout the site', 'aqualuxe'),
                'section'     => 'aqualuxe_colors_section',
            )
        )
    );

    // Add Dark Mode Colors
    $wp_customize->add_setting(
        'aqualuxe_dark_mode_bg',
        array(
            'default'           => '#121212',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_mode_bg',
            array(
                'label'       => __('Dark Mode Background', 'aqualuxe'),
                'description' => __('Background color for dark mode', 'aqualuxe'),
                'section'     => 'aqualuxe_colors_section',
            )
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_dark_mode_text',
        array(
            'default'           => '#f5f5f5',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_mode_text',
            array(
                'label'       => __('Dark Mode Text', 'aqualuxe'),
                'description' => __('Text color for dark mode', 'aqualuxe'),
                'section'     => 'aqualuxe_colors_section',
            )
        )
    );

    // Add Typography Section
    $wp_customize->add_section(
        'aqualuxe_typography_section',
        array(
            'title'       => __('Typography', 'aqualuxe'),
            'description' => __('Customize the theme fonts', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 30,
        )
    );

    // Add Heading Font Option
    $wp_customize->add_setting(
        'aqualuxe_heading_font',
        array(
            'default'           => 'Montserrat',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_font',
        array(
            'label'       => __('Heading Font', 'aqualuxe'),
            'description' => __('Select the font for headings', 'aqualuxe'),
            'section'     => 'aqualuxe_typography_section',
            'type'        => 'select',
            'choices'     => array(
                'Montserrat'    => 'Montserrat',
                'Roboto'        => 'Roboto',
                'Open Sans'     => 'Open Sans',
                'Lato'          => 'Lato',
                'Poppins'       => 'Poppins',
                'Playfair Display' => 'Playfair Display',
                'Merriweather' => 'Merriweather',
                'Oswald'        => 'Oswald',
                'Raleway'       => 'Raleway',
                'Source Sans Pro' => 'Source Sans Pro',
            ),
        )
    );

    // Add Body Font Option
    $wp_customize->add_setting(
        'aqualuxe_body_font',
        array(
            'default'           => 'Open Sans',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_font',
        array(
            'label'       => __('Body Font', 'aqualuxe'),
            'description' => __('Select the font for body text', 'aqualuxe'),
            'section'     => 'aqualuxe_typography_section',
            'type'        => 'select',
            'choices'     => array(
                'Open Sans'     => 'Open Sans',
                'Roboto'        => 'Roboto',
                'Lato'          => 'Lato',
                'Montserrat'    => 'Montserrat',
                'Poppins'       => 'Poppins',
                'Source Sans Pro' => 'Source Sans Pro',
                'Nunito'        => 'Nunito',
                'Raleway'       => 'Raleway',
                'Work Sans'     => 'Work Sans',
                'PT Sans'       => 'PT Sans',
            ),
        )
    );

    // Add Layout Section
    $wp_customize->add_section(
        'aqualuxe_layout_section',
        array(
            'title'       => __('Layout', 'aqualuxe'),
            'description' => __('Customize the theme layout', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 40,
        )
    );

    // Add Container Width Option
    $wp_customize->add_setting(
        'aqualuxe_container_width',
        array(
            'default'           => '1200',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_container_width',
        array(
            'label'       => __('Container Width (px)', 'aqualuxe'),
            'description' => __('Set the maximum width of the content container', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_section',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 960,
                'max'  => 1920,
                'step' => 10,
            ),
        )
    );

    // Add Sidebar Position Option
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
            'description' => __('Choose the default sidebar position', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_section',
            'type'        => 'select',
            'choices'     => array(
                'right' => __('Right', 'aqualuxe'),
                'left'  => __('Left', 'aqualuxe'),
                'none'  => __('No Sidebar', 'aqualuxe'),
            ),
        )
    );

    // Add Footer Section
    $wp_customize->add_section(
        'aqualuxe_footer_section',
        array(
            'title'       => __('Footer', 'aqualuxe'),
            'description' => __('Customize the footer settings', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 50,
        )
    );

    // Add Footer Columns Option
    $wp_customize->add_setting(
        'aqualuxe_footer_columns',
        array(
            'default'           => '4',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_columns',
        array(
            'label'       => __('Footer Widget Columns', 'aqualuxe'),
            'description' => __('Select the number of widget columns in the footer', 'aqualuxe'),
            'section'     => 'aqualuxe_footer_section',
            'type'        => 'select',
            'choices'     => array(
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
            ),
        )
    );

    // Add Copyright Text Option
    $wp_customize->add_setting(
        'aqualuxe_copyright_text',
        array(
            'default'           => sprintf(__('© %s AquaLuxe. All rights reserved.', 'aqualuxe'), date('Y')),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_copyright_text',
        array(
            'label'       => __('Copyright Text', 'aqualuxe'),
            'description' => __('Enter your copyright text for the footer', 'aqualuxe'),
            'section'     => 'aqualuxe_footer_section',
            'type'        => 'textarea',
        )
    );

    // Add Social Media Section
    $wp_customize->add_section(
        'aqualuxe_social_section',
        array(
            'title'       => __('Social Media', 'aqualuxe'),
            'description' => __('Add your social media links', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 60,
        )
    );

    // Add Facebook URL
    $wp_customize->add_setting(
        'aqualuxe_facebook_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_facebook_url',
        array(
            'label'       => __('Facebook URL', 'aqualuxe'),
            'description' => __('Enter your Facebook page URL', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'url',
        )
    );

    // Add Twitter URL
    $wp_customize->add_setting(
        'aqualuxe_twitter_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_twitter_url',
        array(
            'label'       => __('Twitter URL', 'aqualuxe'),
            'description' => __('Enter your Twitter profile URL', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'url',
        )
    );

    // Add Instagram URL
    $wp_customize->add_setting(
        'aqualuxe_instagram_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_instagram_url',
        array(
            'label'       => __('Instagram URL', 'aqualuxe'),
            'description' => __('Enter your Instagram profile URL', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'url',
        )
    );

    // Add LinkedIn URL
    $wp_customize->add_setting(
        'aqualuxe_linkedin_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_linkedin_url',
        array(
            'label'       => __('LinkedIn URL', 'aqualuxe'),
            'description' => __('Enter your LinkedIn profile URL', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'url',
        )
    );

    // Add YouTube URL
    $wp_customize->add_setting(
        'aqualuxe_youtube_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_youtube_url',
        array(
            'label'       => __('YouTube URL', 'aqualuxe'),
            'description' => __('Enter your YouTube channel URL', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'url',
        )
    );

    // Add Pinterest URL
    $wp_customize->add_setting(
        'aqualuxe_pinterest_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_pinterest_url',
        array(
            'label'       => __('Pinterest URL', 'aqualuxe'),
            'description' => __('Enter your Pinterest profile URL', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'url',
        )
    );

    // Add WooCommerce Section (if WooCommerce is active)
    if (class_exists('WooCommerce')) {
        $wp_customize->add_section(
            'aqualuxe_woocommerce_section',
            array(
                'title'       => __('WooCommerce', 'aqualuxe'),
                'description' => __('Customize WooCommerce settings', 'aqualuxe'),
                'panel'       => 'aqualuxe_theme_options',
                'priority'    => 70,
            )
        );

        // Add Products Per Page Option
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
                'label'       => __('Products Per Page', 'aqualuxe'),
                'description' => __('Set the number of products to display per page', 'aqualuxe'),
                'section'     => 'aqualuxe_woocommerce_section',
                'type'        => 'number',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 100,
                    'step' => 1,
                ),
            )
        );

        // Add Product Columns Option
        $wp_customize->add_setting(
            'aqualuxe_product_columns',
            array(
                'default'           => '4',
                'sanitize_callback' => 'absint',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_product_columns',
            array(
                'label'       => __('Product Columns', 'aqualuxe'),
                'description' => __('Set the number of product columns on shop pages', 'aqualuxe'),
                'section'     => 'aqualuxe_woocommerce_section',
                'type'        => 'select',
                'choices'     => array(
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                ),
            )
        );

        // Add Related Products Option
        $wp_customize->add_setting(
            'aqualuxe_related_products',
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_related_products',
            array(
                'label'       => __('Show Related Products', 'aqualuxe'),
                'description' => __('Display related products on single product pages', 'aqualuxe'),
                'section'     => 'aqualuxe_woocommerce_section',
                'type'        => 'checkbox',
            )
        );

        // Add Quick View Option
        $wp_customize->add_setting(
            'aqualuxe_quick_view',
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_quick_view',
            array(
                'label'       => __('Enable Quick View', 'aqualuxe'),
                'description' => __('Allow customers to view product details in a modal', 'aqualuxe'),
                'section'     => 'aqualuxe_woocommerce_section',
                'type'        => 'checkbox',
            )
        );

        // Add Wishlist Option
        $wp_customize->add_setting(
            'aqualuxe_wishlist',
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_wishlist',
            array(
                'label'       => __('Enable Wishlist', 'aqualuxe'),
                'description' => __('Allow customers to save products to a wishlist', 'aqualuxe'),
                'section'     => 'aqualuxe_woocommerce_section',
                'type'        => 'checkbox',
            )
        );
    }

    // Add Performance Section
    $wp_customize->add_section(
        'aqualuxe_performance_section',
        array(
            'title'       => __('Performance', 'aqualuxe'),
            'description' => __('Optimize theme performance', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 80,
        )
    );

    // Add Lazy Loading Option
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
            'label'       => __('Enable Lazy Loading', 'aqualuxe'),
            'description' => __('Load images only when they enter the viewport', 'aqualuxe'),
            'section'     => 'aqualuxe_performance_section',
            'type'        => 'checkbox',
        )
    );

    // Add Minification Option
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
            'label'       => __('Minify Assets', 'aqualuxe'),
            'description' => __('Use minified CSS and JavaScript files', 'aqualuxe'),
            'section'     => 'aqualuxe_performance_section',
            'type'        => 'checkbox',
        )
    );

    // Add Preload Option
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
            'label'       => __('Preload Critical Assets', 'aqualuxe'),
            'description' => __('Preload critical CSS and fonts for faster rendering', 'aqualuxe'),
            'section'     => 'aqualuxe_performance_section',
            'type'        => 'checkbox',
        )
    );

    // Add Advanced Section
    $wp_customize->add_section(
        'aqualuxe_advanced_section',
        array(
            'title'       => __('Advanced', 'aqualuxe'),
            'description' => __('Advanced theme settings', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 90,
        )
    );

    // Add Custom CSS Option
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
            'description' => __('Add your custom CSS here', 'aqualuxe'),
            'section'     => 'aqualuxe_advanced_section',
            'type'        => 'textarea',
        )
    );

    // Add Custom JavaScript Option
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
            'description' => __('Add your custom JavaScript here', 'aqualuxe'),
            'section'     => 'aqualuxe_advanced_section',
            'type'        => 'textarea',
        )
    );

    // Add Google Analytics Option
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
            'label'       => __('Google Analytics', 'aqualuxe'),
            'description' => __('Enter your Google Analytics tracking ID (e.g., UA-XXXXX-Y or G-XXXXXXXX)', 'aqualuxe'),
            'section'     => 'aqualuxe_advanced_section',
            'type'        => 'text',
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
    wp_enqueue_script('aqualuxe-customizer', AQUALUXE_ASSETS_URI . 'js/customizer.js', array('customize-preview'), AQUALUXE_VERSION, true);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');

/**
 * Sanitize checkbox values.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

/**
 * Sanitize select values.
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_select($input, $setting) {
    // Get the list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control($setting->id)->choices;

    // Return input if valid or return default option.
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Generate CSS from customizer settings.
 */
function aqualuxe_generate_customizer_css() {
    $primary_color = get_theme_mod('aqualuxe_primary_color', '#0073aa');
    $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#005177');
    $dark_mode_bg = get_theme_mod('aqualuxe_dark_mode_bg', '#121212');
    $dark_mode_text = get_theme_mod('aqualuxe_dark_mode_text', '#f5f5f5');
    $heading_font = get_theme_mod('aqualuxe_heading_font', 'Montserrat');
    $body_font = get_theme_mod('aqualuxe_body_font', 'Open Sans');
    $container_width = get_theme_mod('aqualuxe_container_width', '1200');
    $custom_css = get_theme_mod('aqualuxe_custom_css', '');

    $css = "
        :root {
            --primary-color: {$primary_color};
            --primary-color-dark: " . aqualuxe_adjust_brightness($primary_color, -20) . ";
            --primary-color-light: " . aqualuxe_adjust_brightness($primary_color, 20) . ";
            --secondary-color: {$secondary_color};
            --secondary-color-dark: " . aqualuxe_adjust_brightness($secondary_color, -20) . ";
            --secondary-color-light: " . aqualuxe_adjust_brightness($secondary_color, 20) . ";
            --dark-mode-bg: {$dark_mode_bg};
            --dark-mode-text: {$dark_mode_text};
            --heading-font: '{$heading_font}', sans-serif;
            --body-font: '{$body_font}', sans-serif;
            --container-width: {$container_width}px;
        }

        body {
            font-family: var(--body-font);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--heading-font);
        }

        .container {
            max-width: var(--container-width);
        }

        .bg-primary {
            background-color: var(--primary-color);
        }

        .bg-primary-dark {
            background-color: var(--primary-color-dark);
        }

        .bg-secondary {
            background-color: var(--secondary-color);
        }

        .text-primary {
            color: var(--primary-color);
        }

        .text-secondary {
            color: var(--secondary-color);
        }

        .hover\\:bg-primary:hover {
            background-color: var(--primary-color);
        }

        .hover\\:bg-primary-dark:hover {
            background-color: var(--primary-color-dark);
        }

        .hover\\:text-primary:hover {
            color: var(--primary-color);
        }

        .border-primary {
            border-color: var(--primary-color);
        }

        .focus\\:border-primary:focus {
            border-color: var(--primary-color);
        }

        .focus\\:ring-primary:focus {
            --tw-ring-color: var(--primary-color);
        }

        /* Dark Mode Styles */
        .dark-mode {
            background-color: var(--dark-mode-bg);
            color: var(--dark-mode-text);
        }

        .dark-mode .bg-white {
            background-color: #1e1e1e;
        }

        .dark-mode .text-gray-600 {
            color: #b0b0b0;
        }

        .dark-mode .text-gray-900 {
            color: var(--dark-mode-text);
        }

        .dark-mode .border-gray-200 {
            border-color: #2c2c2c;
        }

        .dark-mode .bg-gray-50 {
            background-color: #252525;
        }

        .dark-mode .bg-gray-100 {
            background-color: #2a2a2a;
        }

        /* Custom CSS */
        {$custom_css}
    ";

    return $css;
}

/**
 * Output customizer CSS to wp_head.
 */
function aqualuxe_customizer_css() {
    echo '<style type="text/css">' . aqualuxe_generate_customizer_css() . '</style>';
}
add_action('wp_head', 'aqualuxe_customizer_css');

/**
 * Output Google Analytics code to wp_head.
 */
function aqualuxe_google_analytics() {
    $google_analytics = get_theme_mod('aqualuxe_google_analytics', '');

    if (!empty($google_analytics)) {
        if (strpos($google_analytics, 'UA-') === 0) {
            // Universal Analytics
            echo "
            <!-- Google Analytics -->
            <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
            ga('create', '" . esc_js($google_analytics) . "', 'auto');
            ga('send', 'pageview');
            </script>
            <!-- End Google Analytics -->
            ";
        } elseif (strpos($google_analytics, 'G-') === 0) {
            // Google Analytics 4
            echo "
            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src='https://www.googletagmanager.com/gtag/js?id=" . esc_js($google_analytics) . "'></script>
            <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '" . esc_js($google_analytics) . "');
            </script>
            ";
        }
    }
}
add_action('wp_head', 'aqualuxe_google_analytics');

/**
 * Output custom JavaScript to wp_footer.
 */
function aqualuxe_custom_js() {
    $custom_js = get_theme_mod('aqualuxe_custom_js', '');

    if (!empty($custom_js)) {
        echo "
        <!-- Custom JavaScript -->
        <script>
        " . $custom_js . "
        </script>
        ";
    }
}
add_action('wp_footer', 'aqualuxe_custom_js');

/**
 * Helper function to adjust brightness of a hex color.
 *
 * @param string $hex Hex color code.
 * @param int $steps Steps to adjust brightness (negative for darker, positive for lighter).
 * @return string Adjusted hex color.
 */
function aqualuxe_adjust_brightness($hex, $steps) {
    // Remove # if present
    $hex = ltrim($hex, '#');

    // Convert to RGB
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    // Adjust brightness
    $r = max(0, min(255, $r + $steps));
    $g = max(0, min(255, $g + $steps));
    $b = max(0, min(255, $b + $steps));

    // Convert back to hex
    return sprintf('#%02x%02x%02x', $r, $g, $b);
}

/**
 * Enqueue Google Fonts based on customizer settings.
 */
function aqualuxe_enqueue_google_fonts() {
    $heading_font = get_theme_mod('aqualuxe_heading_font', 'Montserrat');
    $body_font = get_theme_mod('aqualuxe_body_font', 'Open Sans');

    $fonts = array();

    if ($heading_font) {
        $fonts[] = $heading_font . ':400,500,600,700';
    }

    if ($body_font && $body_font !== $heading_font) {
        $fonts[] = $body_font . ':400,400i,700,700i';
    }

    if (!empty($fonts)) {
        $fonts_url = 'https://fonts.googleapis.com/css2?family=' . implode('&family=', array_map('urlencode', $fonts)) . '&display=swap';
        
        wp_enqueue_style('aqualuxe-google-fonts', $fonts_url, array(), AQUALUXE_VERSION);
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_google_fonts');