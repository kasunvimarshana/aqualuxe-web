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

    // Theme Options Panel
    $wp_customize->add_panel('aqualuxe_theme_options', array(
        'title'       => __('AquaLuxe Theme Options', 'aqualuxe'),
        'description' => __('Customize your AquaLuxe theme settings', 'aqualuxe'),
        'priority'    => 130,
    ));

    // Colors Section
    $wp_customize->add_section('aqualuxe_colors', array(
        'title'    => __('Colors', 'aqualuxe'),
        'panel'    => 'aqualuxe_theme_options',
        'priority' => 10,
    ));

    // Primary Color
    $wp_customize->add_setting('aqualuxe_primary_color', array(
        'default'           => '#0077b6',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', array(
        'label'    => __('Primary Color', 'aqualuxe'),
        'section'  => 'aqualuxe_colors',
        'settings' => 'aqualuxe_primary_color',
    )));

    // Secondary Color
    $wp_customize->add_setting('aqualuxe_secondary_color', array(
        'default'           => '#00b4d8',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', array(
        'label'    => __('Secondary Color', 'aqualuxe'),
        'section'  => 'aqualuxe_colors',
        'settings' => 'aqualuxe_secondary_color',
    )));

    // Accent Color
    $wp_customize->add_setting('aqualuxe_accent_color', array(
        'default'           => '#90e0ef',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', array(
        'label'    => __('Accent Color', 'aqualuxe'),
        'section'  => 'aqualuxe_colors',
        'settings' => 'aqualuxe_accent_color',
    )));

    // Dark Mode
    $wp_customize->add_setting('aqualuxe_enable_dark_mode', array(
        'default'           => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_dark_mode', array(
        'label'    => __('Enable Dark Mode by Default', 'aqualuxe'),
        'section'  => 'aqualuxe_colors',
        'settings' => 'aqualuxe_enable_dark_mode',
        'type'     => 'checkbox',
    ));

    // Layout Section
    $wp_customize->add_section('aqualuxe_layout', array(
        'title'    => __('Layout', 'aqualuxe'),
        'panel'    => 'aqualuxe_theme_options',
        'priority' => 20,
    ));

    // Container Width
    $wp_customize->add_setting('aqualuxe_container_width', array(
        'default'           => '1200',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_container_width', array(
        'label'       => __('Container Width (px)', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
        'settings'    => 'aqualuxe_container_width',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 960,
            'max'  => 1600,
            'step' => 10,
        ),
    ));

    // Sidebar Position
    $wp_customize->add_setting('aqualuxe_sidebar_position', array(
        'default'           => 'right',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_sidebar_position', array(
        'label'    => __('Sidebar Position', 'aqualuxe'),
        'section'  => 'aqualuxe_layout',
        'settings' => 'aqualuxe_sidebar_position',
        'type'     => 'select',
        'choices'  => array(
            'right' => __('Right', 'aqualuxe'),
            'left'  => __('Left', 'aqualuxe'),
            'none'  => __('No Sidebar', 'aqualuxe'),
        ),
    ));

    // Typography Section
    $wp_customize->add_section('aqualuxe_typography', array(
        'title'    => __('Typography', 'aqualuxe'),
        'panel'    => 'aqualuxe_theme_options',
        'priority' => 30,
    ));

    // Heading Font
    $wp_customize->add_setting('aqualuxe_heading_font', array(
        'default'           => 'Montserrat',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_heading_font', array(
        'label'    => __('Heading Font', 'aqualuxe'),
        'section'  => 'aqualuxe_typography',
        'settings' => 'aqualuxe_heading_font',
        'type'     => 'select',
        'choices'  => aqualuxe_get_google_fonts(),
    ));

    // Body Font
    $wp_customize->add_setting('aqualuxe_body_font', array(
        'default'           => 'Open Sans',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_body_font', array(
        'label'    => __('Body Font', 'aqualuxe'),
        'section'  => 'aqualuxe_typography',
        'settings' => 'aqualuxe_body_font',
        'type'     => 'select',
        'choices'  => aqualuxe_get_google_fonts(),
    ));

    // Base Font Size
    $wp_customize->add_setting('aqualuxe_base_font_size', array(
        'default'           => '16',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_base_font_size', array(
        'label'       => __('Base Font Size (px)', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'settings'    => 'aqualuxe_base_font_size',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 12,
            'max'  => 24,
            'step' => 1,
        ),
    ));

    // Header Section
    $wp_customize->add_section('aqualuxe_header', array(
        'title'    => __('Header', 'aqualuxe'),
        'panel'    => 'aqualuxe_theme_options',
        'priority' => 40,
    ));

    // Sticky Header
    $wp_customize->add_setting('aqualuxe_sticky_header', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_sticky_header', array(
        'label'    => __('Enable Sticky Header', 'aqualuxe'),
        'section'  => 'aqualuxe_header',
        'settings' => 'aqualuxe_sticky_header',
        'type'     => 'checkbox',
    ));

    // Header Style
    $wp_customize->add_setting('aqualuxe_header_style', array(
        'default'           => 'standard',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_header_style', array(
        'label'    => __('Header Style', 'aqualuxe'),
        'section'  => 'aqualuxe_header',
        'settings' => 'aqualuxe_header_style',
        'type'     => 'select',
        'choices'  => array(
            'standard'  => __('Standard', 'aqualuxe'),
            'centered'  => __('Centered', 'aqualuxe'),
            'split'     => __('Split Menu', 'aqualuxe'),
            'minimal'   => __('Minimal', 'aqualuxe'),
        ),
    ));

    // Footer Section
    $wp_customize->add_section('aqualuxe_footer', array(
        'title'    => __('Footer', 'aqualuxe'),
        'panel'    => 'aqualuxe_theme_options',
        'priority' => 50,
    ));

    // Footer Columns
    $wp_customize->add_setting('aqualuxe_footer_columns', array(
        'default'           => '4',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_footer_columns', array(
        'label'       => __('Footer Widget Columns', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'settings'    => 'aqualuxe_footer_columns',
        'type'        => 'select',
        'choices'     => array(
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
        ),
    ));

    // Footer Copyright Text
    $wp_customize->add_setting('aqualuxe_footer_copyright', array(
        'default'           => sprintf(__('© %s AquaLuxe. All rights reserved.', 'aqualuxe'), date('Y')),
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('aqualuxe_footer_copyright', array(
        'label'    => __('Footer Copyright Text', 'aqualuxe'),
        'section'  => 'aqualuxe_footer',
        'settings' => 'aqualuxe_footer_copyright',
        'type'     => 'textarea',
    ));

    // WooCommerce Section
    if (class_exists('WooCommerce')) {
        $wp_customize->add_section('aqualuxe_woocommerce', array(
            'title'    => __('WooCommerce', 'aqualuxe'),
            'panel'    => 'aqualuxe_theme_options',
            'priority' => 60,
        ));

        // Products per row
        $wp_customize->add_setting('aqualuxe_products_per_row', array(
            'default'           => '3',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control('aqualuxe_products_per_row', array(
            'label'       => __('Products per row', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce',
            'settings'    => 'aqualuxe_products_per_row',
            'type'        => 'select',
            'choices'     => array(
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
            ),
        ));

        // Related Products
        $wp_customize->add_setting('aqualuxe_related_products', array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));

        $wp_customize->add_control('aqualuxe_related_products', array(
            'label'    => __('Show Related Products', 'aqualuxe'),
            'section'  => 'aqualuxe_woocommerce',
            'settings' => 'aqualuxe_related_products',
            'type'     => 'checkbox',
        ));

        // Quick View
        $wp_customize->add_setting('aqualuxe_quick_view', array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));

        $wp_customize->add_control('aqualuxe_quick_view', array(
            'label'    => __('Enable Quick View', 'aqualuxe'),
            'section'  => 'aqualuxe_woocommerce',
            'settings' => 'aqualuxe_quick_view',
            'type'     => 'checkbox',
        ));

        // Wishlist
        $wp_customize->add_setting('aqualuxe_wishlist', array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));

        $wp_customize->add_control('aqualuxe_wishlist', array(
            'label'    => __('Enable Wishlist', 'aqualuxe'),
            'section'  => 'aqualuxe_woocommerce',
            'settings' => 'aqualuxe_wishlist',
            'type'     => 'checkbox',
        ));

        // Shipping Tab Content
        $wp_customize->add_setting('aqualuxe_shipping_tab_content', array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
        ));

        $wp_customize->add_control('aqualuxe_shipping_tab_content', array(
            'label'    => __('Shipping Tab Content', 'aqualuxe'),
            'section'  => 'aqualuxe_woocommerce',
            'settings' => 'aqualuxe_shipping_tab_content',
            'type'     => 'textarea',
        ));
    }

    // Social Media Section
    $wp_customize->add_section('aqualuxe_social', array(
        'title'    => __('Social Media', 'aqualuxe'),
        'panel'    => 'aqualuxe_theme_options',
        'priority' => 70,
    ));

    // Facebook
    $wp_customize->add_setting('aqualuxe_facebook', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_facebook', array(
        'label'    => __('Facebook URL', 'aqualuxe'),
        'section'  => 'aqualuxe_social',
        'settings' => 'aqualuxe_facebook',
        'type'     => 'url',
    ));

    // Instagram
    $wp_customize->add_setting('aqualuxe_instagram', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_instagram', array(
        'label'    => __('Instagram URL', 'aqualuxe'),
        'section'  => 'aqualuxe_social',
        'settings' => 'aqualuxe_instagram',
        'type'     => 'url',
    ));

    // Twitter
    $wp_customize->add_setting('aqualuxe_twitter', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_twitter', array(
        'label'    => __('Twitter URL', 'aqualuxe'),
        'section'  => 'aqualuxe_social',
        'settings' => 'aqualuxe_twitter',
        'type'     => 'url',
    ));

    // YouTube
    $wp_customize->add_setting('aqualuxe_youtube', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_youtube', array(
        'label'    => __('YouTube URL', 'aqualuxe'),
        'section'  => 'aqualuxe_social',
        'settings' => 'aqualuxe_youtube',
        'type'     => 'url',
    ));

    // Pinterest
    $wp_customize->add_setting('aqualuxe_pinterest', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_pinterest', array(
        'label'    => __('Pinterest URL', 'aqualuxe'),
        'section'  => 'aqualuxe_social',
        'settings' => 'aqualuxe_pinterest',
        'type'     => 'url',
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
    wp_enqueue_script('aqualuxe-customizer', AQUALUXE_URI . 'assets/js/customizer.js', array('customize-preview'), AQUALUXE_VERSION, true);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');

/**
 * Sanitize checkbox values
 */
function aqualuxe_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

/**
 * Sanitize select values
 */
function aqualuxe_sanitize_select($input, $setting) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    // If the input is a valid key, return it; otherwise, return the default.
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Get Google Fonts list
 */
function aqualuxe_get_google_fonts() {
    return array(
        'Montserrat'      => 'Montserrat',
        'Open Sans'       => 'Open Sans',
        'Lato'            => 'Lato',
        'Roboto'          => 'Roboto',
        'Raleway'         => 'Raleway',
        'Poppins'         => 'Poppins',
        'Playfair Display' => 'Playfair Display',
        'Merriweather'    => 'Merriweather',
        'Nunito'          => 'Nunito',
        'Ubuntu'          => 'Ubuntu',
    );
}

/**
 * Generate CSS from customizer settings
 */
function aqualuxe_generate_customizer_css() {
    $primary_color = get_theme_mod('aqualuxe_primary_color', '#0077b6');
    $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#00b4d8');
    $accent_color = get_theme_mod('aqualuxe_accent_color', '#90e0ef');
    $container_width = get_theme_mod('aqualuxe_container_width', '1200');
    $heading_font = get_theme_mod('aqualuxe_heading_font', 'Montserrat');
    $body_font = get_theme_mod('aqualuxe_body_font', 'Open Sans');
    $base_font_size = get_theme_mod('aqualuxe_base_font_size', '16');
    
    $css = "
        :root {
            --aqualuxe-primary-color: {$primary_color};
            --aqualuxe-secondary-color: {$secondary_color};
            --aqualuxe-accent-color: {$accent_color};
            --aqualuxe-heading-font: '{$heading_font}', sans-serif;
            --aqualuxe-body-font: '{$body_font}', sans-serif;
            --aqualuxe-base-font-size: {$base_font_size}px;
            --aqualuxe-container-width: {$container_width}px;
        }
        
        body {
            font-family: var(--aqualuxe-body-font);
            font-size: var(--aqualuxe-base-font-size);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--aqualuxe-heading-font);
        }
        
        .container {
            max-width: var(--aqualuxe-container-width);
        }
        
        a {
            color: var(--aqualuxe-primary-color);
        }
        
        a:hover {
            color: var(--aqualuxe-secondary-color);
        }
        
        .bg-primary {
            background-color: var(--aqualuxe-primary-color) !important;
        }
        
        .bg-secondary {
            background-color: var(--aqualuxe-secondary-color) !important;
        }
        
        .text-primary {
            color: var(--aqualuxe-primary-color) !important;
        }
        
        .text-secondary {
            color: var(--aqualuxe-secondary-color) !important;
        }
        
        .btn-primary {
            background-color: var(--aqualuxe-primary-color);
            border-color: var(--aqualuxe-primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--aqualuxe-secondary-color);
            border-color: var(--aqualuxe-secondary-color);
        }
        
        .btn-secondary {
            background-color: var(--aqualuxe-secondary-color);
            border-color: var(--aqualuxe-secondary-color);
        }
        
        .btn-secondary:hover {
            background-color: var(--aqualuxe-primary-color);
            border-color: var(--aqualuxe-primary-color);
        }
    ";
    
    // Add dark mode styles if enabled
    if (get_theme_mod('aqualuxe_enable_dark_mode', false)) {
        $css .= "
            body.dark-mode {
                background-color: #121212;
                color: #e0e0e0;
            }
            
            body.dark-mode a {
                color: #90e0ef;
            }
            
            body.dark-mode a:hover {
                color: #00b4d8;
            }
            
            body.dark-mode .site-header,
            body.dark-mode .site-footer {
                background-color: #1a1a1a;
            }
            
            body.dark-mode .widget-area {
                background-color: #1e1e1e;
            }
        ";
    }
    
    return $css;
}

/**
 * Enqueue customizer CSS
 */
function aqualuxe_customizer_css() {
    wp_add_inline_style('aqualuxe-style', aqualuxe_generate_customizer_css());
}
add_action('wp_enqueue_scripts', 'aqualuxe_customizer_css');

/**
 * Enqueue Google Fonts
 */
function aqualuxe_google_fonts() {
    $heading_font = get_theme_mod('aqualuxe_heading_font', 'Montserrat');
    $body_font = get_theme_mod('aqualuxe_body_font', 'Open Sans');
    
    $font_families = array();
    
    if ($heading_font != $body_font) {
        $font_families[] = $heading_font . ':400,500,600,700';
        $font_families[] = $body_font . ':400,400i,700,700i';
    } else {
        $font_families[] = $heading_font . ':400,400i,500,600,700,700i';
    }
    
    $query_args = array(
        'family' => urlencode(implode('|', $font_families)),
        'display' => 'swap',
    );
    
    $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
    
    wp_enqueue_style('aqualuxe-google-fonts', $fonts_url, array(), null);
}
add_action('wp_enqueue_scripts', 'aqualuxe_google_fonts');