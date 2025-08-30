<?php
/**
 * AquaLuxe Footer Customizer Settings
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Footer customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager
 */
function aqualuxe_customizer_footer_settings($wp_customize) {
    // Footer Panel
    $wp_customize->add_panel('aqualuxe_footer_panel', [
        'title'       => esc_html__('Footer', 'aqualuxe'),
        'description' => esc_html__('Footer settings', 'aqualuxe'),
        'priority'    => 30,
    ]);

    // General Footer Section
    $wp_customize->add_section('aqualuxe_footer_general_section', [
        'title'       => esc_html__('General', 'aqualuxe'),
        'description' => esc_html__('General footer settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_footer_panel',
        'priority'    => 10,
    ]);

    // Footer Layout
    $wp_customize->add_setting('footer_layout', [
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control(new AquaLuxe_Radio_Image_Control($wp_customize, 'footer_layout', [
        'label'       => esc_html__('Footer Layout', 'aqualuxe'),
        'description' => esc_html__('Select the footer layout', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_general_section',
        'choices'     => [
            'default'      => [
                'label' => esc_html__('Default', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/footer-default.png',
            ],
            'centered'     => [
                'label' => esc_html__('Centered', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/footer-centered.png',
            ],
            'minimal'      => [
                'label' => esc_html__('Minimal', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/footer-minimal.png',
            ],
            'dark'         => [
                'label' => esc_html__('Dark', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/footer-dark.png',
            ],
            'light'        => [
                'label' => esc_html__('Light', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/footer-light.png',
            ],
        ],
    ]));

    // Footer Width
    $wp_customize->add_setting('footer_width', [
        'default'           => 'container',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('footer_width', [
        'label'       => esc_html__('Footer Width', 'aqualuxe'),
        'description' => esc_html__('Select the footer width', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_general_section',
        'type'        => 'select',
        'choices'     => [
            'container'     => esc_html__('Container', 'aqualuxe'),
            'container-fluid' => esc_html__('Full Width', 'aqualuxe'),
        ],
    ]);

    // Footer Padding
    $wp_customize->add_setting('footer_padding', [
        'default'           => [
            'top'    => 60,
            'right'  => 20,
            'bottom' => 60,
            'left'   => 20,
        ],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_dimensions',
    ]);

    $wp_customize->add_control(new AquaLuxe_Dimensions_Control($wp_customize, 'footer_padding', [
        'label'       => esc_html__('Footer Padding (px)', 'aqualuxe'),
        'description' => esc_html__('Set the padding of the footer', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_general_section',
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

    // Footer Background Color
    $wp_customize->add_setting('footer_background_color', [
        'default'           => '#000814',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_background_color', [
        'label'       => esc_html__('Footer Background Color', 'aqualuxe'),
        'description' => esc_html__('Set the background color of the footer', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_general_section',
    ]));

    // Footer Text Color
    $wp_customize->add_setting('footer_text_color', [
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_text_color', [
        'label'       => esc_html__('Footer Text Color', 'aqualuxe'),
        'description' => esc_html__('Set the text color of the footer', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_general_section',
    ]));

    // Footer Link Color
    $wp_customize->add_setting('footer_link_color', [
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_link_color', [
        'label'       => esc_html__('Footer Link Color', 'aqualuxe'),
        'description' => esc_html__('Set the link color of the footer', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_general_section',
    ]));

    // Footer Link Hover Color
    $wp_customize->add_setting('footer_link_hover_color', [
        'default'           => '#0077b6',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_link_hover_color', [
        'label'       => esc_html__('Footer Link Hover Color', 'aqualuxe'),
        'description' => esc_html__('Set the link hover color of the footer', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_general_section',
    ]));

    // Footer Widget Title Color
    $wp_customize->add_setting('footer_widget_title_color', [
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_widget_title_color', [
        'label'       => esc_html__('Footer Widget Title Color', 'aqualuxe'),
        'description' => esc_html__('Set the widget title color of the footer', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_general_section',
    ]));

    // Footer Border Top
    $wp_customize->add_setting('footer_border_top', [
        'default'           => true,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'footer_border_top', [
        'label'       => esc_html__('Footer Border Top', 'aqualuxe'),
        'description' => esc_html__('Enable border top for footer', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_general_section',
    ]));

    // Footer Border Top Color
    $wp_customize->add_setting('footer_border_top_color', [
        'default'           => '#0077b6',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_border_top_color', [
        'label'           => esc_html__('Footer Border Top Color', 'aqualuxe'),
        'description'     => esc_html__('Set the border top color of the footer', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_general_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('footer_border_top', true);
        },
    ]));

    // Footer Widgets Section
    $wp_customize->add_section('aqualuxe_footer_widgets_section', [
        'title'       => esc_html__('Widgets', 'aqualuxe'),
        'description' => esc_html__('Footer widgets settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_footer_panel',
        'priority'    => 20,
    ]);

    // Footer Widgets Layout
    $wp_customize->add_setting('footer_widgets_layout', [
        'default'           => '4-columns',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control(new AquaLuxe_Radio_Image_Control($wp_customize, 'footer_widgets_layout', [
        'label'       => esc_html__('Footer Widgets Layout', 'aqualuxe'),
        'description' => esc_html__('Select the footer widgets layout', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_widgets_section',
        'choices'     => [
            '4-columns'      => [
                'label' => esc_html__('4 Columns', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/footer-4-columns.png',
            ],
            '3-columns'      => [
                'label' => esc_html__('3 Columns', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/footer-3-columns.png',
            ],
            '2-columns'      => [
                'label' => esc_html__('2 Columns', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/footer-2-columns.png',
            ],
            '1-column'       => [
                'label' => esc_html__('1 Column', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/footer-1-column.png',
            ],
            'custom'         => [
                'label' => esc_html__('Custom', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/footer-custom.png',
            ],
        ],
    ]));

    // Footer Widgets Custom Layout
    $wp_customize->add_setting('footer_widgets_custom_layout', [
        'default'           => '3-3-3-3',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('footer_widgets_custom_layout', [
        'label'           => esc_html__('Footer Widgets Custom Layout', 'aqualuxe'),
        'description'     => esc_html__('Set the custom layout for footer widgets (e.g., 3-3-3-3, 4-4-4, 6-6, etc.)', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_widgets_section',
        'type'            => 'text',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('footer_widgets_layout', '4-columns') === 'custom';
        },
    ]);

    // Footer Widgets Spacing
    $wp_customize->add_setting('footer_widgets_spacing', [
        'default'           => 30,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'footer_widgets_spacing', [
        'label'       => esc_html__('Footer Widgets Spacing (px)', 'aqualuxe'),
        'description' => esc_html__('Set the spacing between footer widgets', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_widgets_section',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
        ],
    ]));

    // Footer Widgets Title Font Size
    $wp_customize->add_setting('footer_widgets_title_font_size', [
        'default'           => 18,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'footer_widgets_title_font_size', [
        'label'       => esc_html__('Footer Widgets Title Font Size (px)', 'aqualuxe'),
        'description' => esc_html__('Set the font size of footer widgets title', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_widgets_section',
        'input_attrs' => [
            'min'  => 12,
            'max'  => 36,
            'step' => 1,
        ],
    ]));

    // Footer Widgets Title Font Weight
    $wp_customize->add_setting('footer_widgets_title_font_weight', [
        'default'           => 600,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('footer_widgets_title_font_weight', [
        'label'       => esc_html__('Footer Widgets Title Font Weight', 'aqualuxe'),
        'description' => esc_html__('Set the font weight of footer widgets title', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_widgets_section',
        'type'        => 'select',
        'choices'     => [
            '300' => esc_html__('Light', 'aqualuxe'),
            '400' => esc_html__('Regular', 'aqualuxe'),
            '500' => esc_html__('Medium', 'aqualuxe'),
            '600' => esc_html__('Semi Bold', 'aqualuxe'),
            '700' => esc_html__('Bold', 'aqualuxe'),
        ],
    ]));

    // Footer Widgets Title Text Transform
    $wp_customize->add_setting('footer_widgets_title_text_transform', [
        'default'           => 'uppercase',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('footer_widgets_title_text_transform', [
        'label'       => esc_html__('Footer Widgets Title Text Transform', 'aqualuxe'),
        'description' => esc_html__('Set the text transform of footer widgets title', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_widgets_section',
        'type'        => 'select',
        'choices'     => [
            'none'       => esc_html__('None', 'aqualuxe'),
            'capitalize' => esc_html__('Capitalize', 'aqualuxe'),
            'uppercase'  => esc_html__('Uppercase', 'aqualuxe'),
            'lowercase'  => esc_html__('Lowercase', 'aqualuxe'),
        ],
    ]));

    // Footer Widgets Title Margin Bottom
    $wp_customize->add_setting('footer_widgets_title_margin_bottom', [
        'default'           => 20,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'footer_widgets_title_margin_bottom', [
        'label'       => esc_html__('Footer Widgets Title Margin Bottom (px)', 'aqualuxe'),
        'description' => esc_html__('Set the margin bottom of footer widgets title', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_widgets_section',
        'input_attrs' => [
            'min'  => 0,
            'max'  => 50,
            'step' => 1,
        ],
    ]));

    // Bottom Footer Section
    $wp_customize->add_section('aqualuxe_footer_bottom_section', [
        'title'       => esc_html__('Bottom Footer', 'aqualuxe'),
        'description' => esc_html__('Bottom footer settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_footer_panel',
        'priority'    => 30,
    ]);

    // Show Bottom Footer
    $wp_customize->add_setting('show_bottom_footer', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'show_bottom_footer', [
        'label'       => esc_html__('Show Bottom Footer', 'aqualuxe'),
        'description' => esc_html__('Show bottom footer', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_bottom_section',
    ]));

    // Bottom Footer Background Color
    $wp_customize->add_setting('bottom_footer_background_color', [
        'default'           => '#000814',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'bottom_footer_background_color', [
        'label'           => esc_html__('Bottom Footer Background Color', 'aqualuxe'),
        'description'     => esc_html__('Set the background color of the bottom footer', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_bottom_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_bottom_footer', true);
        },
    ]));

    // Bottom Footer Text Color
    $wp_customize->add_setting('bottom_footer_text_color', [
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'bottom_footer_text_color', [
        'label'           => esc_html__('Bottom Footer Text Color', 'aqualuxe'),
        'description'     => esc_html__('Set the text color of the bottom footer', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_bottom_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_bottom_footer', true);
        },
    ]));

    // Bottom Footer Link Color
    $wp_customize->add_setting('bottom_footer_link_color', [
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'bottom_footer_link_color', [
        'label'           => esc_html__('Bottom Footer Link Color', 'aqualuxe'),
        'description'     => esc_html__('Set the link color of the bottom footer', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_bottom_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_bottom_footer', true);
        },
    ]));

    // Bottom Footer Link Hover Color
    $wp_customize->add_setting('bottom_footer_link_hover_color', [
        'default'           => '#0077b6',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'bottom_footer_link_hover_color', [
        'label'           => esc_html__('Bottom Footer Link Hover Color', 'aqualuxe'),
        'description'     => esc_html__('Set the link hover color of the bottom footer', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_bottom_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_bottom_footer', true);
        },
    ]));

    // Bottom Footer Border Top
    $wp_customize->add_setting('bottom_footer_border_top', [
        'default'           => true,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'bottom_footer_border_top', [
        'label'           => esc_html__('Bottom Footer Border Top', 'aqualuxe'),
        'description'     => esc_html__('Enable border top for bottom footer', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_bottom_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_bottom_footer', true);
        },
    ]));

    // Bottom Footer Border Top Color
    $wp_customize->add_setting('bottom_footer_border_top_color', [
        'default'           => '#1e1e1e',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'bottom_footer_border_top_color', [
        'label'           => esc_html__('Bottom Footer Border Top Color', 'aqualuxe'),
        'description'     => esc_html__('Set the border top color of the bottom footer', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_bottom_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_bottom_footer', true) && aqualuxe_get_theme_mod('bottom_footer_border_top', true);
        },
    ]));

    // Bottom Footer Padding
    $wp_customize->add_setting('bottom_footer_padding', [
        'default'           => [
            'top'    => 20,
            'right'  => 20,
            'bottom' => 20,
            'left'   => 20,
        ],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_dimensions',
    ]);

    $wp_customize->add_control(new AquaLuxe_Dimensions_Control($wp_customize, 'bottom_footer_padding', [
        'label'           => esc_html__('Bottom Footer Padding (px)', 'aqualuxe'),
        'description'     => esc_html__('Set the padding of the bottom footer', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_bottom_section',
        'input_attrs'     => [
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
        ],
        'dimensions'      => [
            'top'    => esc_html__('Top', 'aqualuxe'),
            'right'  => esc_html__('Right', 'aqualuxe'),
            'bottom' => esc_html__('Bottom', 'aqualuxe'),
            'left'   => esc_html__('Left', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_bottom_footer', true);
        },
    ]));

    // Bottom Footer Layout
    $wp_customize->add_setting('bottom_footer_layout', [
        'default'           => 'copyright-menu',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('bottom_footer_layout', [
        'label'           => esc_html__('Bottom Footer Layout', 'aqualuxe'),
        'description'     => esc_html__('Select the bottom footer layout', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_bottom_section',
        'type'            => 'select',
        'choices'         => [
            'copyright-menu'  => esc_html__('Copyright Left - Menu Right', 'aqualuxe'),
            'menu-copyright'  => esc_html__('Menu Left - Copyright Right', 'aqualuxe'),
            'centered'        => esc_html__('Centered', 'aqualuxe'),
            'custom'          => esc_html__('Custom', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_bottom_footer', true);
        },
    ]);

    // Copyright Text
    $wp_customize->add_setting('copyright_text', [
        'default'           => sprintf(esc_html__('© %s AquaLuxe. All Rights Reserved.', 'aqualuxe'), date('Y')),
        'transport'         => 'postMessage',
        'sanitize_callback' => 'wp_kses_post',
    ]);

    $wp_customize->add_control('copyright_text', [
        'label'           => esc_html__('Copyright Text', 'aqualuxe'),
        'description'     => esc_html__('Set the copyright text', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_bottom_section',
        'type'            => 'textarea',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_bottom_footer', true);
        },
    ]);

    // Bottom Footer Custom Content
    $wp_customize->add_setting('bottom_footer_custom_content', [
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'wp_kses_post',
    ]);

    $wp_customize->add_control('bottom_footer_custom_content', [
        'label'           => esc_html__('Bottom Footer Custom Content', 'aqualuxe'),
        'description'     => esc_html__('Set the custom content for bottom footer', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_bottom_section',
        'type'            => 'textarea',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_bottom_footer', true) && aqualuxe_get_theme_mod('bottom_footer_layout', 'copyright-menu') === 'custom';
        },
    ]);

    // Footer Logo Section
    $wp_customize->add_section('aqualuxe_footer_logo_section', [
        'title'       => esc_html__('Footer Logo', 'aqualuxe'),
        'description' => esc_html__('Footer logo settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_footer_panel',
        'priority'    => 40,
    ]);

    // Show Footer Logo
    $wp_customize->add_setting('show_footer_logo', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'show_footer_logo', [
        'label'       => esc_html__('Show Footer Logo', 'aqualuxe'),
        'description' => esc_html__('Show logo in footer', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_logo_section',
    ]));

    // Footer Logo Type
    $wp_customize->add_setting('footer_logo_type', [
        'default'           => 'same',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('footer_logo_type', [
        'label'           => esc_html__('Footer Logo Type', 'aqualuxe'),
        'description'     => esc_html__('Select the footer logo type', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_logo_section',
        'type'            => 'select',
        'choices'         => [
            'same'  => esc_html__('Same as Header Logo', 'aqualuxe'),
            'image' => esc_html__('Custom Image', 'aqualuxe'),
            'text'  => esc_html__('Custom Text', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_footer_logo', true);
        },
    ]);

    // Footer Logo
    $wp_customize->add_setting('footer_logo', [
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_image',
    ]);

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'footer_logo', [
        'label'           => esc_html__('Footer Logo', 'aqualuxe'),
        'description'     => esc_html__('Upload footer logo', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_logo_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_footer_logo', true) && aqualuxe_get_theme_mod('footer_logo_type', 'same') === 'image';
        },
    ]));

    // Footer Logo Text
    $wp_customize->add_setting('footer_logo_text', [
        'default'           => get_bloginfo('name'),
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('footer_logo_text', [
        'label'           => esc_html__('Footer Logo Text', 'aqualuxe'),
        'description'     => esc_html__('Set the footer logo text', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_logo_section',
        'type'            => 'text',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_footer_logo', true) && aqualuxe_get_theme_mod('footer_logo_type', 'same') === 'text';
        },
    ]);

    // Footer Logo Text Color
    $wp_customize->add_setting('footer_logo_text_color', [
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_logo_text_color', [
        'label'           => esc_html__('Footer Logo Text Color', 'aqualuxe'),
        'description'     => esc_html__('Set the footer logo text color', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_logo_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_footer_logo', true) && aqualuxe_get_theme_mod('footer_logo_type', 'same') === 'text';
        },
    ]));

    // Footer Logo Width
    $wp_customize->add_setting('footer_logo_width', [
        'default'           => 150,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'footer_logo_width', [
        'label'           => esc_html__('Footer Logo Width (px)', 'aqualuxe'),
        'description'     => esc_html__('Set the width of the footer logo', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_logo_section',
        'input_attrs'     => [
            'min'  => 50,
            'max'  => 300,
            'step' => 1,
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_footer_logo', true) && (aqualuxe_get_theme_mod('footer_logo_type', 'same') === 'image' || aqualuxe_get_theme_mod('footer_logo_type', 'same') === 'same');
        },
    ]));

    // Footer Logo Margin Bottom
    $wp_customize->add_setting('footer_logo_margin_bottom', [
        'default'           => 20,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'footer_logo_margin_bottom', [
        'label'           => esc_html__('Footer Logo Margin Bottom (px)', 'aqualuxe'),
        'description'     => esc_html__('Set the margin bottom of the footer logo', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_logo_section',
        'input_attrs'     => [
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_footer_logo', true);
        },
    ]));

    // Footer Social Section
    $wp_customize->add_section('aqualuxe_footer_social_section', [
        'title'       => esc_html__('Footer Social', 'aqualuxe'),
        'description' => esc_html__('Footer social settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_footer_panel',
        'priority'    => 50,
    ]);

    // Show Footer Social
    $wp_customize->add_setting('show_footer_social', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'show_footer_social', [
        'label'       => esc_html__('Show Footer Social', 'aqualuxe'),
        'description' => esc_html__('Show social icons in footer', 'aqualuxe'),
        'section'     => 'aqualuxe_footer_social_section',
    ]));

    // Footer Social Position
    $wp_customize->add_setting('footer_social_position', [
        'default'           => 'bottom',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('footer_social_position', [
        'label'           => esc_html__('Footer Social Position', 'aqualuxe'),
        'description'     => esc_html__('Select the position of social icons in footer', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_social_section',
        'type'            => 'select',
        'choices'         => [
            'top'    => esc_html__('Top', 'aqualuxe'),
            'bottom' => esc_html__('Bottom', 'aqualuxe'),
            'both'   => esc_html__('Both', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_footer_social', true);
        },
    ]);

    // Footer Social Title
    $wp_customize->add_setting('footer_social_title', [
        'default'           => esc_html__('Follow Us', 'aqualuxe'),
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('footer_social_title', [
        'label'           => esc_html__('Footer Social Title', 'aqualuxe'),
        'description'     => esc_html__('Set the title for social icons in footer', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_social_section',
        'type'            => 'text',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_footer_social', true);
        },
    ]);

    // Footer Social Icon Size
    $wp_customize->add_setting('footer_social_icon_size', [
        'default'           => 16,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'footer_social_icon_size', [
        'label'           => esc_html__('Footer Social Icon Size (px)', 'aqualuxe'),
        'description'     => esc_html__('Set the size of social icons in footer', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_social_section',
        'input_attrs'     => [
            'min'  => 12,
            'max'  => 36,
            'step' => 1,
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_footer_social', true);
        },
    ]));

    // Footer Social Icon Color
    $wp_customize->add_setting('footer_social_icon_color', [
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_social_icon_color', [
        'label'           => esc_html__('Footer Social Icon Color', 'aqualuxe'),
        'description'     => esc_html__('Set the color of social icons in footer', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_social_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_footer_social', true);
        },
    ]));

    // Footer Social Icon Hover Color
    $wp_customize->add_setting('footer_social_icon_hover_color', [
        'default'           => '#0077b6',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_social_icon_hover_color', [
        'label'           => esc_html__('Footer Social Icon Hover Color', 'aqualuxe'),
        'description'     => esc_html__('Set the hover color of social icons in footer', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_social_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_footer_social', true);
        },
    ]));

    // Footer Social Icon Background
    $wp_customize->add_setting('footer_social_icon_background', [
        'default'           => false,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'footer_social_icon_background', [
        'label'           => esc_html__('Footer Social Icon Background', 'aqualuxe'),
        'description'     => esc_html__('Enable background for social icons in footer', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_social_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_footer_social', true);
        },
    ]));

    // Footer Social Icon Background Color
    $wp_customize->add_setting('footer_social_icon_background_color', [
        'default'           => '#1e1e1e',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_social_icon_background_color', [
        'label'           => esc_html__('Footer Social Icon Background Color', 'aqualuxe'),
        'description'     => esc_html__('Set the background color of social icons in footer', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_social_section',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_footer_social', true) && aqualuxe_get_theme_mod('footer_social_icon_background', false);
        },
    ]));

    // Footer Social Icon Shape
    $wp_customize->add_setting('footer_social_icon_shape', [
        'default'           => 'circle',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('footer_social_icon_shape', [
        'label'           => esc_html__('Footer Social Icon Shape', 'aqualuxe'),
        'description'     => esc_html__('Select the shape of social icons in footer', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_social_section',
        'type'            => 'select',
        'choices'         => [
            'circle'  => esc_html__('Circle', 'aqualuxe'),
            'square'  => esc_html__('Square', 'aqualuxe'),
            'rounded' => esc_html__('Rounded', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_footer_social', true) && aqualuxe_get_theme_mod('footer_social_icon_background', false);
        },
    ]));

    // Footer Social Icon Spacing
    $wp_customize->add_setting('footer_social_icon_spacing', [
        'default'           => 10,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control(new AquaLuxe_Range_Control($wp_customize, 'footer_social_icon_spacing', [
        'label'           => esc_html__('Footer Social Icon Spacing (px)', 'aqualuxe'),
        'description'     => esc_html__('Set the spacing between social icons in footer', 'aqualuxe'),
        'section'         => 'aqualuxe_footer_social_section',
        'input_attrs'     => [
            'min'  => 0,
            'max'  => 50,
            'step' => 1,
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('show_footer_social', true);
        },
    ]));
}