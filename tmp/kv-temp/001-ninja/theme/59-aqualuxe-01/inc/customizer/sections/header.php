<?php
/**
 * AquaLuxe Header Customizer Settings
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Header customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager
 */
function aqualuxe_customizer_header_settings($wp_customize) {
    // Header Panel
    $wp_customize->add_panel('aqualuxe_header_panel', [
        'title'       => esc_html__('Header', 'aqualuxe'),
        'description' => esc_html__('Header settings', 'aqualuxe'),
        'priority'    => 20,
    ]);

    // General Header Section
    $wp_customize->add_section('aqualuxe_header_general_section', [
        'title'       => esc_html__('General', 'aqualuxe'),
        'description' => esc_html__('General header settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_header_panel',
        'priority'    => 10,
    ]);

    // Header Layout
    $wp_customize->add_setting('header_layout', [
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control(new AquaLuxe_Radio_Image_Control($wp_customize, 'header_layout', [
        'label'       => esc_html__('Header Layout', 'aqualuxe'),
        'description' => esc_html__('Select the header layout', 'aqualuxe'),
        'section'     => 'aqualuxe_header_general_section',
        'choices'     => [
            'default'      => [
                'label' => esc_html__('Default', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/header-default.png',
            ],
            'centered'     => [
                'label' => esc_html__('Centered', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/header-centered.png',
            ],
            'split'        => [
                'label' => esc_html__('Split', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/header-split.png',
            ],
            'transparent'  => [
                'label' => esc_html__('Transparent', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/header-transparent.png',
            ],
            'minimal'      => [
                'label' => esc_html__('Minimal', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/header-minimal.png',
            ],
        ],
    ]));

    // Header Width
    $wp_customize->add_setting('header_width', [
        'default'           => 'container',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('header_width', [
        'label'       => esc_html__('Header Width', 'aqualuxe'),
        'description' => esc_html__('Select the header width', 'aqualuxe'),
        'section'     => 'aqualuxe_header_general_section',
        'type'        => 'select',
        'choices'     => [
            'container'     => esc_html__('Container', 'aqualuxe'),
            'container-fluid' => esc_html__('Full Width', 'aqualuxe'),
        ],
    ]);

    // Header Height
    $wp_customize->add_setting('header_height', [
        'default'           => 80,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'header_height', [
        'label'       => esc_html__('Header Height (px)', 'aqualuxe'),
        'description' => esc_html__('Set the height of the header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_general_section',
        'input_attrs' => [
            'min'  => 50,
            'max'  => 200,
            'step' => 1,
        ],
    ]));

    // Header Padding
    $wp_customize->add_setting('header_padding', [
        'default'           => [
            'top'    => 10,
            'right'  => 20,
            'bottom' => 10,
            'left'   => 20,
        ],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_dimensions',
    ]);

    $wp_customize->add_control(new AquaLuxe_Dimensions_Control($wp_customize, 'header_padding', [
        'label'       => esc_html__('Header Padding (px)', 'aqualuxe'),
        'description' => esc_html__('Set the padding of the header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_general_section',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
        ],
        'dimensions'  => [
            'top'    => esc_html__('Top', 'aqualuxe'),
            'right'  => esc_html__('Right', 'aqualuxe'),
            'bottom' => esc_html__('Bottom', 'aqualuxe'),
            'left'   => esc_html__('Left', 'aqualuxe'),
        ],
    ]));

    // Header Background Color
    $wp_customize->add_setting('header_background_color', [
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_background_color', [
        'label'       => esc_html__('Header Background Color', 'aqualuxe'),
        'description' => esc_html__('Set the background color of the header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_general_section',
    ]));

    // Header Text Color
    $wp_customize->add_setting('header_text_color', [
        'default'           => '#000814',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_text_color', [
        'label'       => esc_html__('Header Text Color', 'aqualuxe'),
        'description' => esc_html__('Set the text color of the header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_general_section',
    ]));

    // Header Link Color
    $wp_customize->add_setting('header_link_color', [
        'default'           => '#000814',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_link_color', [
        'label'       => esc_html__('Header Link Color', 'aqualuxe'),
        'description' => esc_html__('Set the link color of the header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_general_section',
    ]));

    // Header Link Hover Color
    $wp_customize->add_setting('header_link_hover_color', [
        'default'           => '#0077b6',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_link_hover_color', [
        'label'       => esc_html__('Header Link Hover Color', 'aqualuxe'),
        'description' => esc_html__('Set the link hover color of the header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_general_section',
    ]));

    // Header Border Bottom
    $wp_customize->add_setting('header_border_bottom', [
        'default'           => false,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'header_border_bottom', [
        'label'       => esc_html__('Header Border Bottom', 'aqualuxe'),
        'description' => esc_html__('Enable border bottom for header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_general_section',
    ]));

    // Header Border Bottom Color
    $wp_customize->add_setting('header_border_bottom_color', [
        'default'           => '#f0f0f0',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_border_bottom_color', [
        'label'           => esc_html__('Header Border Bottom Color', 'aqualuxe'),
        'description'     => esc_html__('Set the border bottom color of the header', 'aqualuxe'),
        'section'         => 'aqualuxe_header_general_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('header_border_bottom', false);
        },
    ]));

    // Sticky Header Section
    $wp_customize->add_section('aqualuxe_header_sticky_section', [
        'title'       => esc_html__('Sticky Header', 'aqualuxe'),
        'description' => esc_html__('Sticky header settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_header_panel',
        'priority'    => 20,
    ]);

    // Enable Sticky Header
    $wp_customize->add_setting('header_sticky', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'header_sticky', [
        'label'       => esc_html__('Enable Sticky Header', 'aqualuxe'),
        'description' => esc_html__('Enable sticky header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_sticky_section',
    ]));

    // Sticky Header Background Color
    $wp_customize->add_setting('header_sticky_background_color', [
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_sticky_background_color', [
        'label'           => esc_html__('Sticky Header Background Color', 'aqualuxe'),
        'description'     => esc_html__('Set the background color of the sticky header', 'aqualuxe'),
        'section'         => 'aqualuxe_header_sticky_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('header_sticky', true);
        },
    ]));

    // Sticky Header Text Color
    $wp_customize->add_setting('header_sticky_text_color', [
        'default'           => '#000814',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_sticky_text_color', [
        'label'           => esc_html__('Sticky Header Text Color', 'aqualuxe'),
        'description'     => esc_html__('Set the text color of the sticky header', 'aqualuxe'),
        'section'         => 'aqualuxe_header_sticky_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('header_sticky', true);
        },
    ]));

    // Sticky Header Link Color
    $wp_customize->add_setting('header_sticky_link_color', [
        'default'           => '#000814',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_sticky_link_color', [
        'label'           => esc_html__('Sticky Header Link Color', 'aqualuxe'),
        'description'     => esc_html__('Set the link color of the sticky header', 'aqualuxe'),
        'section'         => 'aqualuxe_header_sticky_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('header_sticky', true);
        },
    ]));

    // Sticky Header Link Hover Color
    $wp_customize->add_setting('header_sticky_link_hover_color', [
        'default'           => '#0077b6',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_sticky_link_hover_color', [
        'label'           => esc_html__('Sticky Header Link Hover Color', 'aqualuxe'),
        'description'     => esc_html__('Set the link hover color of the sticky header', 'aqualuxe'),
        'section'         => 'aqualuxe_header_sticky_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('header_sticky', true);
        },
    ]));

    // Sticky Header Shadow
    $wp_customize->add_setting('header_sticky_shadow', [
        'default'           => true,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'header_sticky_shadow', [
        'label'           => esc_html__('Sticky Header Shadow', 'aqualuxe'),
        'description'     => esc_html__('Enable shadow for sticky header', 'aqualuxe'),
        'section'         => 'aqualuxe_header_sticky_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('header_sticky', true);
        },
    ]));

    // Logo Section
    $wp_customize->add_section('aqualuxe_header_logo_section', [
        'title'       => esc_html__('Logo', 'aqualuxe'),
        'description' => esc_html__('Logo settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_header_panel',
        'priority'    => 30,
    ]);

    // Logo Type
    $wp_customize->add_setting('logo_type', [
        'default'           => 'image',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('logo_type', [
        'label'       => esc_html__('Logo Type', 'aqualuxe'),
        'description' => esc_html__('Select the logo type', 'aqualuxe'),
        'section'     => 'aqualuxe_header_logo_section',
        'type'        => 'select',
        'choices'     => [
            'image' => esc_html__('Image', 'aqualuxe'),
            'text'  => esc_html__('Text', 'aqualuxe'),
        ],
    ]);

    // Logo Text
    $wp_customize->add_setting('logo_text', [
        'default'           => get_bloginfo('name'),
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('logo_text', [
        'label'           => esc_html__('Logo Text', 'aqualuxe'),
        'description'     => esc_html__('Set the logo text', 'aqualuxe'),
        'section'         => 'aqualuxe_header_logo_section',
        'type'            => 'text',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('logo_type', 'image') === 'text';
        },
    ]);

    // Logo Text Color
    $wp_customize->add_setting('logo_text_color', [
        'default'           => '#000814',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'logo_text_color', [
        'label'           => esc_html__('Logo Text Color', 'aqualuxe'),
        'description'     => esc_html__('Set the logo text color', 'aqualuxe'),
        'section'         => 'aqualuxe_header_logo_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('logo_type', 'image') === 'text';
        },
    ]));

    // Logo Text Font Size
    $wp_customize->add_setting('logo_text_font_size', [
        'default'           => 24,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'logo_text_font_size', [
        'label'           => esc_html__('Logo Text Font Size (px)', 'aqualuxe'),
        'description'     => esc_html__('Set the font size of the logo text', 'aqualuxe'),
        'section'         => 'aqualuxe_header_logo_section',
        'input_attrs'     => [
            'min'  => 12,
            'max'  => 48,
            'step' => 1,
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('logo_type', 'image') === 'text';
        },
    ]));

    // Logo Text Font Weight
    $wp_customize->add_setting('logo_text_font_weight', [
        'default'           => 700,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('logo_text_font_weight', [
        'label'           => esc_html__('Logo Text Font Weight', 'aqualuxe'),
        'description'     => esc_html__('Set the font weight of the logo text', 'aqualuxe'),
        'section'         => 'aqualuxe_header_logo_section',
        'type'            => 'select',
        'choices'         => [
            '300' => esc_html__('Light', 'aqualuxe'),
            '400' => esc_html__('Regular', 'aqualuxe'),
            '500' => esc_html__('Medium', 'aqualuxe'),
            '600' => esc_html__('Semi Bold', 'aqualuxe'),
            '700' => esc_html__('Bold', 'aqualuxe'),
            '800' => esc_html__('Extra Bold', 'aqualuxe'),
            '900' => esc_html__('Black', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('logo_type', 'image') === 'text';
        },
    ]);

    // Logo Text Text Transform
    $wp_customize->add_setting('logo_text_transform', [
        'default'           => 'uppercase',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('logo_text_transform', [
        'label'           => esc_html__('Logo Text Transform', 'aqualuxe'),
        'description'     => esc_html__('Set the text transform of the logo text', 'aqualuxe'),
        'section'         => 'aqualuxe_header_logo_section',
        'type'            => 'select',
        'choices'         => [
            'none'       => esc_html__('None', 'aqualuxe'),
            'capitalize' => esc_html__('Capitalize', 'aqualuxe'),
            'uppercase'  => esc_html__('Uppercase', 'aqualuxe'),
            'lowercase'  => esc_html__('Lowercase', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('logo_type', 'image') === 'text';
        },
    ]);

    // Logo Width
    $wp_customize->add_setting('logo_width', [
        'default'           => 150,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'logo_width', [
        'label'           => esc_html__('Logo Width (px)', 'aqualuxe'),
        'description'     => esc_html__('Set the width of the logo', 'aqualuxe'),
        'section'         => 'aqualuxe_header_logo_section',
        'input_attrs'     => [
            'min'  => 50,
            'max'  => 300,
            'step' => 1,
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('logo_type', 'image') === 'image';
        },
    ]));

    // Logo Height
    $wp_customize->add_setting('logo_height', [
        'default'           => 50,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'logo_height', [
        'label'           => esc_html__('Logo Height (px)', 'aqualuxe'),
        'description'     => esc_html__('Set the height of the logo', 'aqualuxe'),
        'section'         => 'aqualuxe_header_logo_section',
        'input_attrs'     => [
            'min'  => 20,
            'max'  => 200,
            'step' => 1,
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('logo_type', 'image') === 'image';
        },
    ]));

    // Logo Padding
    $wp_customize->add_setting('logo_padding', [
        'default'           => [
            'top'    => 10,
            'right'  => 0,
            'bottom' => 10,
            'left'   => 0,
        ],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_dimensions',
    ]);

    $wp_customize->add_control(new AquaLuxe_Dimensions_Control($wp_customize, 'logo_padding', [
        'label'       => esc_html__('Logo Padding (px)', 'aqualuxe'),
        'description' => esc_html__('Set the padding of the logo', 'aqualuxe'),
        'section'     => 'aqualuxe_header_logo_section',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 50,
            'step' => 1,
        ],
        'dimensions'  => [
            'top'    => esc_html__('Top', 'aqualuxe'),
            'right'  => esc_html__('Right', 'aqualuxe'),
            'bottom' => esc_html__('Bottom', 'aqualuxe'),
            'left'   => esc_html__('Left', 'aqualuxe'),
        ],
    ]));

    // Navigation Section
    $wp_customize->add_section('aqualuxe_header_navigation_section', [
        'title'       => esc_html__('Navigation', 'aqualuxe'),
        'description' => esc_html__('Navigation settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_header_panel',
        'priority'    => 40,
    ]);

    // Menu Position
    $wp_customize->add_setting('menu_position', [
        'default'           => 'right',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('menu_position', [
        'label'       => esc_html__('Menu Position', 'aqualuxe'),
        'description' => esc_html__('Select the menu position', 'aqualuxe'),
        'section'     => 'aqualuxe_header_navigation_section',
        'type'        => 'select',
        'choices'     => [
            'left'   => esc_html__('Left', 'aqualuxe'),
            'center' => esc_html__('Center', 'aqualuxe'),
            'right'  => esc_html__('Right', 'aqualuxe'),
        ],
    ]);

    // Menu Font Size
    $wp_customize->add_setting('menu_font_size', [
        'default'           => 14,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'menu_font_size', [
        'label'       => esc_html__('Menu Font Size (px)', 'aqualuxe'),
        'description' => esc_html__('Set the font size of the menu', 'aqualuxe'),
        'section'     => 'aqualuxe_header_navigation_section',
        'input_attrs' => [
            'min'  => 10,
            'max'  => 24,
            'step' => 1,
        ],
    ]));

    // Menu Font Weight
    $wp_customize->add_setting('menu_font_weight', [
        'default'           => 500,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('menu_font_weight', [
        'label'       => esc_html__('Menu Font Weight', 'aqualuxe'),
        'description' => esc_html__('Set the font weight of the menu', 'aqualuxe'),
        'section'     => 'aqualuxe_header_navigation_section',
        'type'        => 'select',
        'choices'     => [
            '300' => esc_html__('Light', 'aqualuxe'),
            '400' => esc_html__('Regular', 'aqualuxe'),
            '500' => esc_html__('Medium', 'aqualuxe'),
            '600' => esc_html__('Semi Bold', 'aqualuxe'),
            '700' => esc_html__('Bold', 'aqualuxe'),
        ],
    ]);

    // Menu Text Transform
    $wp_customize->add_setting('menu_text_transform', [
        'default'           => 'uppercase',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('menu_text_transform', [
        'label'       => esc_html__('Menu Text Transform', 'aqualuxe'),
        'description' => esc_html__('Set the text transform of the menu', 'aqualuxe'),
        'section'     => 'aqualuxe_header_navigation_section',
        'type'        => 'select',
        'choices'     => [
            'none'       => esc_html__('None', 'aqualuxe'),
            'capitalize' => esc_html__('Capitalize', 'aqualuxe'),
            'uppercase'  => esc_html__('Uppercase', 'aqualuxe'),
            'lowercase'  => esc_html__('Lowercase', 'aqualuxe'),
        ],
    ]);

    // Menu Item Spacing
    $wp_customize->add_setting('menu_item_spacing', [
        'default'           => 20,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'menu_item_spacing', [
        'label'       => esc_html__('Menu Item Spacing (px)', 'aqualuxe'),
        'description' => esc_html__('Set the spacing between menu items', 'aqualuxe'),
        'section'     => 'aqualuxe_header_navigation_section',
        'input_attrs' => [
            'min'  => 10,
            'max'  => 50,
            'step' => 1,
        ],
    ]));

    // Dropdown Background Color
    $wp_customize->add_setting('dropdown_background_color', [
        'default'           => '#ffffff',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'dropdown_background_color', [
        'label'       => esc_html__('Dropdown Background Color', 'aqualuxe'),
        'description' => esc_html__('Set the background color of dropdown menus', 'aqualuxe'),
        'section'     => 'aqualuxe_header_navigation_section',
    ]));

    // Dropdown Text Color
    $wp_customize->add_setting('dropdown_text_color', [
        'default'           => '#000814',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'dropdown_text_color', [
        'label'       => esc_html__('Dropdown Text Color', 'aqualuxe'),
        'description' => esc_html__('Set the text color of dropdown menus', 'aqualuxe'),
        'section'     => 'aqualuxe_header_navigation_section',
    ]));

    // Dropdown Link Hover Color
    $wp_customize->add_setting('dropdown_link_hover_color', [
        'default'           => '#0077b6',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'dropdown_link_hover_color', [
        'label'       => esc_html__('Dropdown Link Hover Color', 'aqualuxe'),
        'description' => esc_html__('Set the link hover color of dropdown menus', 'aqualuxe'),
        'section'     => 'aqualuxe_header_navigation_section',
    ]));

    // Dropdown Shadow
    $wp_customize->add_setting('dropdown_shadow', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'dropdown_shadow', [
        'label'       => esc_html__('Dropdown Shadow', 'aqualuxe'),
        'description' => esc_html__('Enable shadow for dropdown menus', 'aqualuxe'),
        'section'     => 'aqualuxe_header_navigation_section',
    ]));

    // Dropdown Border Radius
    $wp_customize->add_setting('dropdown_border_radius', [
        'default'           => 4,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'dropdown_border_radius', [
        'label'       => esc_html__('Dropdown Border Radius (px)', 'aqualuxe'),
        'description' => esc_html__('Set the border radius of dropdown menus', 'aqualuxe'),
        'section'     => 'aqualuxe_header_navigation_section',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 20,
            'step' => 1,
        ],
    ]));

    // Mobile Menu Section
    $wp_customize->add_section('aqualuxe_header_mobile_menu_section', [
        'title'       => esc_html__('Mobile Menu', 'aqualuxe'),
        'description' => esc_html__('Mobile menu settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_header_panel',
        'priority'    => 50,
    ]);

    // Mobile Menu Breakpoint
    $wp_customize->add_setting('mobile_menu_breakpoint', [
        'default'           => 992,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'mobile_menu_breakpoint', [
        'label'       => esc_html__('Mobile Menu Breakpoint (px)', 'aqualuxe'),
        'description' => esc_html__('Set the breakpoint for mobile menu', 'aqualuxe'),
        'section'     => 'aqualuxe_header_mobile_menu_section',
        'input_attrs' => [
            'min'  => 576,
            'max'  => 1200,
            'step' => 1,
        ],
    ]));

    // Mobile Menu Icon Color
    $wp_customize->add_setting('mobile_menu_icon_color', [
        'default'           => '#000814',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'mobile_menu_icon_color', [
        'label'       => esc_html__('Mobile Menu Icon Color', 'aqualuxe'),
        'description' => esc_html__('Set the color of the mobile menu icon', 'aqualuxe'),
        'section'     => 'aqualuxe_header_mobile_menu_section',
    ]));

    // Mobile Menu Background Color
    $wp_customize->add_setting('mobile_menu_background_color', [
        'default'           => '#ffffff',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'mobile_menu_background_color', [
        'label'       => esc_html__('Mobile Menu Background Color', 'aqualuxe'),
        'description' => esc_html__('Set the background color of the mobile menu', 'aqualuxe'),
        'section'     => 'aqualuxe_header_mobile_menu_section',
    ]));

    // Mobile Menu Text Color
    $wp_customize->add_setting('mobile_menu_text_color', [
        'default'           => '#000814',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'mobile_menu_text_color', [
        'label'       => esc_html__('Mobile Menu Text Color', 'aqualuxe'),
        'description' => esc_html__('Set the text color of the mobile menu', 'aqualuxe'),
        'section'     => 'aqualuxe_header_mobile_menu_section',
    ]));

    // Mobile Menu Link Hover Color
    $wp_customize->add_setting('mobile_menu_link_hover_color', [
        'default'           => '#0077b6',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'mobile_menu_link_hover_color', [
        'label'       => esc_html__('Mobile Menu Link Hover Color', 'aqualuxe'),
        'description' => esc_html__('Set the link hover color of the mobile menu', 'aqualuxe'),
        'section'     => 'aqualuxe_header_mobile_menu_section',
    ]));

    // Mobile Menu Animation
    $wp_customize->add_setting('mobile_menu_animation', [
        'default'           => 'slide',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('mobile_menu_animation', [
        'label'       => esc_html__('Mobile Menu Animation', 'aqualuxe'),
        'description' => esc_html__('Select the animation for mobile menu', 'aqualuxe'),
        'section'     => 'aqualuxe_header_mobile_menu_section',
        'type'        => 'select',
        'choices'     => [
            'slide' => esc_html__('Slide', 'aqualuxe'),
            'fade'  => esc_html__('Fade', 'aqualuxe'),
            'none'  => esc_html__('None', 'aqualuxe'),
        ],
    ]);

    // Header Elements Section
    $wp_customize->add_section('aqualuxe_header_elements_section', [
        'title'       => esc_html__('Header Elements', 'aqualuxe'),
        'description' => esc_html__('Header elements settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_header_panel',
        'priority'    => 60,
    ]);

    // Show Search
    $wp_customize->add_setting('header_show_search', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'header_show_search', [
        'label'       => esc_html__('Show Search', 'aqualuxe'),
        'description' => esc_html__('Show search icon in header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_elements_section',
    ]));

    // Show Cart
    $wp_customize->add_setting('header_show_cart', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'header_show_cart', [
        'label'       => esc_html__('Show Cart', 'aqualuxe'),
        'description' => esc_html__('Show cart icon in header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_elements_section',
    ]));

    // Show Account
    $wp_customize->add_setting('header_show_account', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'header_show_account', [
        'label'       => esc_html__('Show Account', 'aqualuxe'),
        'description' => esc_html__('Show account icon in header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_elements_section',
    ]));

    // Show Wishlist
    $wp_customize->add_setting('header_show_wishlist', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'header_show_wishlist', [
        'label'       => esc_html__('Show Wishlist', 'aqualuxe'),
        'description' => esc_html__('Show wishlist icon in header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_elements_section',
    ]));

    // Show Language Switcher
    $wp_customize->add_setting('header_show_language_switcher', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'header_show_language_switcher', [
        'label'       => esc_html__('Show Language Switcher', 'aqualuxe'),
        'description' => esc_html__('Show language switcher in header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_elements_section',
    ]));

    // Show Currency Switcher
    $wp_customize->add_setting('header_show_currency_switcher', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'header_show_currency_switcher', [
        'label'       => esc_html__('Show Currency Switcher', 'aqualuxe'),
        'description' => esc_html__('Show currency switcher in header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_elements_section',
    ]));

    // Show Dark Mode Toggle
    $wp_customize->add_setting('header_show_dark_mode_toggle', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'header_show_dark_mode_toggle', [
        'label'       => esc_html__('Show Dark Mode Toggle', 'aqualuxe'),
        'description' => esc_html__('Show dark mode toggle in header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_elements_section',
    ]));

    // Header Elements Order
    $wp_customize->add_setting('header_elements_order', [
        'default'           => [
            'search',
            'wishlist',
            'cart',
            'account',
            'language_switcher',
            'currency_switcher',
            'dark_mode_toggle',
        ],
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_sortable',
    ]);

    $wp_customize->add_control(new AquaLuxe_Sortable_Control($wp_customize, 'header_elements_order', [
        'label'       => esc_html__('Header Elements Order', 'aqualuxe'),
        'description' => esc_html__('Drag and drop to reorder header elements', 'aqualuxe'),
        'section'     => 'aqualuxe_header_elements_section',
        'choices'     => [
            'search'            => esc_html__('Search', 'aqualuxe'),
            'wishlist'          => esc_html__('Wishlist', 'aqualuxe'),
            'cart'              => esc_html__('Cart', 'aqualuxe'),
            'account'           => esc_html__('Account', 'aqualuxe'),
            'language_switcher' => esc_html__('Language Switcher', 'aqualuxe'),
            'currency_switcher' => esc_html__('Currency Switcher', 'aqualuxe'),
            'dark_mode_toggle'  => esc_html__('Dark Mode Toggle', 'aqualuxe'),
        ],
    ]));

    // Top Bar Section
    $wp_customize->add_section('aqualuxe_header_top_bar_section', [
        'title'       => esc_html__('Top Bar', 'aqualuxe'),
        'description' => esc_html__('Top bar settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_header_panel',
        'priority'    => 70,
    ]);

    // Show Top Bar
    $wp_customize->add_setting('show_top_bar', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'show_top_bar', [
        'label'       => esc_html__('Show Top Bar', 'aqualuxe'),
        'description' => esc_html__('Show top bar above header', 'aqualuxe'),
        'section'     => 'aqualuxe_header_top_bar_section',
    ]));

    // Top Bar Background Color
    $wp_customize->add_setting('top_bar_background_color', [
        'default'           => '#03045e',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'top_bar_background_color', [
        'label'           => esc_html__('Top Bar Background Color', 'aqualuxe'),
        'description'     => esc_html__('Set the background color of the top bar', 'aqualuxe'),
        'section'         => 'aqualuxe_header_top_bar_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_top_bar', true);
        },
    ]));

    // Top Bar Text Color
    $wp_customize->add_setting('top_bar_text_color', [
        'default'           => '#ffffff',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'top_bar_text_color', [
        'label'           => esc_html__('Top Bar Text Color', 'aqualuxe'),
        'description'     => esc_html__('Set the text color of the top bar', 'aqualuxe'),
        'section'         => 'aqualuxe_header_top_bar_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_top_bar', true);
        },
    ]));

    // Top Bar Link Color
    $wp_customize->add_setting('top_bar_link_color', [
        'default'           => '#ffffff',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'top_bar_link_color', [
        'label'           => esc_html__('Top Bar Link Color', 'aqualuxe'),
        'description'     => esc_html__('Set the link color of the top bar', 'aqualuxe'),
        'section'         => 'aqualuxe_header_top_bar_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_top_bar', true);
        },
    ]));

    // Top Bar Link Hover Color
    $wp_customize->add_setting('top_bar_link_hover_color', [
        'default'           => '#48cae4',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'top_bar_link_hover_color', [
        'label'           => esc_html__('Top Bar Link Hover Color', 'aqualuxe'),
        'description'     => esc_html__('Set the link hover color of the top bar', 'aqualuxe'),
        'section'         => 'aqualuxe_header_top_bar_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_top_bar', true);
        },
    ]));

    // Top Bar Left Content
    $wp_customize->add_setting('top_bar_left_content', [
        'default'           => '<span class="top-bar-phone"><i class="fas fa-phone"></i> +1 (555) 123-4567</span> <span class="top-bar-email"><i class="fas fa-envelope"></i> info@aqualuxe.com</span>',
        'transport'         => 'refresh',
        'sanitize_callback' => 'wp_kses_post',
    ]);

    $wp_customize->add_control('top_bar_left_content', [
        'label'           => esc_html__('Top Bar Left Content', 'aqualuxe'),
        'description'     => esc_html__('Set the content for the left side of the top bar', 'aqualuxe'),
        'section'         => 'aqualuxe_header_top_bar_section',
        'type'            => 'textarea',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_top_bar', true);
        },
    ]);

    // Top Bar Right Content
    $wp_customize->add_setting('top_bar_right_content', [
        'default'           => '<span class="top-bar-shipping"><i class="fas fa-truck"></i> Free Shipping on Orders Over $100</span>',
        'transport'         => 'refresh',
        'sanitize_callback' => 'wp_kses_post',
    ]);

    $wp_customize->add_control('top_bar_right_content', [
        'label'           => esc_html__('Top Bar Right Content', 'aqualuxe'),
        'description'     => esc_html__('Set the content for the right side of the top bar', 'aqualuxe'),
        'section'         => 'aqualuxe_header_top_bar_section',
        'type'            => 'textarea',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_top_bar', true);
        },
    ]);
}