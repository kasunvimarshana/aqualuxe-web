<?php
/**
 * AquaLuxe General Customizer Settings
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * General customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager
 */
function aqualuxe_customizer_general_settings($wp_customize) {
    // General Panel
    $wp_customize->add_panel('aqualuxe_general_panel', [
        'title'       => esc_html__('General Settings', 'aqualuxe'),
        'description' => esc_html__('General theme settings', 'aqualuxe'),
        'priority'    => 10,
    ]);

    // Layout Section
    $wp_customize->add_section('aqualuxe_layout_section', [
        'title'       => esc_html__('Layout', 'aqualuxe'),
        'description' => esc_html__('Layout settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_general_panel',
        'priority'    => 10,
    ]);

    // Container Width
    $wp_customize->add_setting('container_width', [
        'default'           => 1140,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'container_width', [
        'label'       => esc_html__('Container Width (px)', 'aqualuxe'),
        'description' => esc_html__('Set the width of the main container', 'aqualuxe'),
        'section'     => 'aqualuxe_layout_section',
        'input_attrs' => [
            'min'  => 960,
            'max'  => 1600,
            'step' => 10,
        ],
    ]));

    // Site Layout
    $wp_customize->add_setting('site_layout', [
        'default'           => 'wide',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control(new AquaLuxe_Radio_Image_Control($wp_customize, 'site_layout', [
        'label'   => esc_html__('Site Layout', 'aqualuxe'),
        'section' => 'aqualuxe_layout_section',
        'choices' => [
            'wide'  => [
                'label' => esc_html__('Wide', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/wide-layout.png',
            ],
            'boxed' => [
                'label' => esc_html__('Boxed', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/boxed-layout.png',
            ],
        ],
    ]));

    // Boxed Width
    $wp_customize->add_setting('site_boxed_width', [
        'default'           => 1200,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'site_boxed_width', [
        'label'           => esc_html__('Boxed Width (px)', 'aqualuxe'),
        'description'     => esc_html__('Set the width of the boxed layout', 'aqualuxe'),
        'section'         => 'aqualuxe_layout_section',
        'input_attrs'     => [
            'min'  => 960,
            'max'  => 1600,
            'step' => 10,
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('site_layout', 'wide') === 'boxed';
        },
    ]));

    // Boxed Shadow
    $wp_customize->add_setting('site_boxed_shadow', [
        'default'           => true,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'site_boxed_shadow', [
        'label'           => esc_html__('Boxed Shadow', 'aqualuxe'),
        'description'     => esc_html__('Enable shadow for boxed layout', 'aqualuxe'),
        'section'         => 'aqualuxe_layout_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('site_layout', 'wide') === 'boxed';
        },
    ]));

    // Boxed Border Radius
    $wp_customize->add_setting('site_boxed_border_radius', [
        'default'           => 10,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'site_boxed_border_radius', [
        'label'           => esc_html__('Boxed Border Radius (px)', 'aqualuxe'),
        'description'     => esc_html__('Set the border radius of the boxed layout', 'aqualuxe'),
        'section'         => 'aqualuxe_layout_section',
        'input_attrs'     => [
            'min'  => 0,
            'max'  => 50,
            'step' => 1,
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('site_layout', 'wide') === 'boxed';
        },
    ]));

    // Performance Section
    $wp_customize->add_section('aqualuxe_performance_section', [
        'title'       => esc_html__('Performance', 'aqualuxe'),
        'description' => esc_html__('Performance settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_general_panel',
        'priority'    => 20,
    ]);

    // Enable Preload
    $wp_customize->add_setting('enable_preload', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'enable_preload', [
        'label'       => esc_html__('Enable Preload', 'aqualuxe'),
        'description' => esc_html__('Enable preloading of critical assets', 'aqualuxe'),
        'section'     => 'aqualuxe_performance_section',
    ]));

    // Enable Lazy Load
    $wp_customize->add_setting('enable_lazy_load', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'enable_lazy_load', [
        'label'       => esc_html__('Enable Lazy Load', 'aqualuxe'),
        'description' => esc_html__('Enable lazy loading of images', 'aqualuxe'),
        'section'     => 'aqualuxe_performance_section',
    ]));

    // Enable Minification
    $wp_customize->add_setting('enable_minification', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'enable_minification', [
        'label'       => esc_html__('Enable Minification', 'aqualuxe'),
        'description' => esc_html__('Enable minification of CSS and JS files', 'aqualuxe'),
        'section'     => 'aqualuxe_performance_section',
    ]));

    // Breadcrumbs Section
    $wp_customize->add_section('aqualuxe_breadcrumbs_section', [
        'title'       => esc_html__('Breadcrumbs', 'aqualuxe'),
        'description' => esc_html__('Breadcrumbs settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_general_panel',
        'priority'    => 30,
    ]);

    // Enable Breadcrumbs
    $wp_customize->add_setting('enable_breadcrumbs', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'enable_breadcrumbs', [
        'label'       => esc_html__('Enable Breadcrumbs', 'aqualuxe'),
        'description' => esc_html__('Enable breadcrumbs on pages', 'aqualuxe'),
        'section'     => 'aqualuxe_breadcrumbs_section',
    ]));

    // Breadcrumbs Separator
    $wp_customize->add_setting('breadcrumbs_separator', [
        'default'           => 'chevron-right',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('breadcrumbs_separator', [
        'label'       => esc_html__('Breadcrumbs Separator', 'aqualuxe'),
        'description' => esc_html__('Select the separator for breadcrumbs', 'aqualuxe'),
        'section'     => 'aqualuxe_breadcrumbs_section',
        'type'        => 'select',
        'choices'     => [
            'chevron-right' => esc_html__('Chevron Right', 'aqualuxe'),
            'chevron-left'  => esc_html__('Chevron Left', 'aqualuxe'),
            'arrow-right'   => esc_html__('Arrow Right', 'aqualuxe'),
            'arrow-left'    => esc_html__('Arrow Left', 'aqualuxe'),
            'slash'         => esc_html__('Slash', 'aqualuxe'),
            'dot'           => esc_html__('Dot', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('enable_breadcrumbs', true);
        },
    ]);

    // Breadcrumbs Background
    $wp_customize->add_setting('breadcrumbs_background', [
        'default'           => '#f8f9fa',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'breadcrumbs_background', [
        'label'           => esc_html__('Breadcrumbs Background', 'aqualuxe'),
        'description'     => esc_html__('Set the background color for breadcrumbs', 'aqualuxe'),
        'section'         => 'aqualuxe_breadcrumbs_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('enable_breadcrumbs', true);
        },
    ]));

    // Breadcrumbs Text Color
    $wp_customize->add_setting('breadcrumbs_text_color', [
        'default'           => '#000814',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'breadcrumbs_text_color', [
        'label'           => esc_html__('Breadcrumbs Text Color', 'aqualuxe'),
        'description'     => esc_html__('Set the text color for breadcrumbs', 'aqualuxe'),
        'section'         => 'aqualuxe_breadcrumbs_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('enable_breadcrumbs', true);
        },
    ]));

    // Breadcrumbs Link Color
    $wp_customize->add_setting('breadcrumbs_link_color', [
        'default'           => '#0077b6',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'breadcrumbs_link_color', [
        'label'           => esc_html__('Breadcrumbs Link Color', 'aqualuxe'),
        'description'     => esc_html__('Set the link color for breadcrumbs', 'aqualuxe'),
        'section'         => 'aqualuxe_breadcrumbs_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('enable_breadcrumbs', true);
        },
    ]));

    // Buttons Section
    $wp_customize->add_section('aqualuxe_buttons_section', [
        'title'       => esc_html__('Buttons', 'aqualuxe'),
        'description' => esc_html__('Button settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_general_panel',
        'priority'    => 40,
    ]);

    // Button Background Color
    $wp_customize->add_setting('button_background_color', [
        'default'           => '#0077b6',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_background_color', [
        'label'       => esc_html__('Button Background Color', 'aqualuxe'),
        'description' => esc_html__('Set the background color for buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_section',
    ]));

    // Button Text Color
    $wp_customize->add_setting('button_text_color', [
        'default'           => '#ffffff',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_text_color', [
        'label'       => esc_html__('Button Text Color', 'aqualuxe'),
        'description' => esc_html__('Set the text color for buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_section',
    ]));

    // Button Border Radius
    $wp_customize->add_setting('button_border_radius', [
        'default'           => 4,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'button_border_radius', [
        'label'       => esc_html__('Button Border Radius (px)', 'aqualuxe'),
        'description' => esc_html__('Set the border radius for buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_section',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 50,
            'step' => 1,
        ],
    ]));

    // Button Hover Background Color
    $wp_customize->add_setting('button_hover_background_color', [
        'default'           => '#03045e',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_hover_background_color', [
        'label'       => esc_html__('Button Hover Background Color', 'aqualuxe'),
        'description' => esc_html__('Set the hover background color for buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_section',
    ]));

    // Button Hover Text Color
    $wp_customize->add_setting('button_hover_text_color', [
        'default'           => '#ffffff',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_hover_text_color', [
        'label'       => esc_html__('Button Hover Text Color', 'aqualuxe'),
        'description' => esc_html__('Set the hover text color for buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_buttons_section',
    ]));

    // Custom Code Section
    $wp_customize->add_section('aqualuxe_custom_code_section', [
        'title'       => esc_html__('Custom Code', 'aqualuxe'),
        'description' => esc_html__('Custom code settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_general_panel',
        'priority'    => 50,
    ]);

    // Custom CSS
    $wp_customize->add_setting('custom_css', [
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_css',
    ]);

    $wp_customize->add_control('custom_css', [
        'label'       => esc_html__('Custom CSS', 'aqualuxe'),
        'description' => esc_html__('Add custom CSS code', 'aqualuxe'),
        'section'     => 'aqualuxe_custom_code_section',
        'type'        => 'textarea',
    ]);

    // Custom JS
    $wp_customize->add_setting('custom_js', [
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_js',
    ]);

    $wp_customize->add_control('custom_js', [
        'label'       => esc_html__('Custom JS', 'aqualuxe'),
        'description' => esc_html__('Add custom JS code', 'aqualuxe'),
        'section'     => 'aqualuxe_custom_code_section',
        'type'        => 'textarea',
    ]);

    // Header Code
    $wp_customize->add_setting('header_code', [
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_html',
    ]);

    $wp_customize->add_control('header_code', [
        'label'       => esc_html__('Header Code', 'aqualuxe'),
        'description' => esc_html__('Add code to the header', 'aqualuxe'),
        'section'     => 'aqualuxe_custom_code_section',
        'type'        => 'textarea',
    ]);

    // Footer Code
    $wp_customize->add_setting('footer_code', [
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_html',
    ]);

    $wp_customize->add_control('footer_code', [
        'label'       => esc_html__('Footer Code', 'aqualuxe'),
        'description' => esc_html__('Add code to the footer', 'aqualuxe'),
        'section'     => 'aqualuxe_custom_code_section',
        'type'        => 'textarea',
    ]);
}