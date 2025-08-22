<?php
/**
 * Header Customizer Section
 *
 * @package AquaLuxe
 */

/**
 * Add header settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_header($wp_customize) {
    // Add Header section
    $wp_customize->add_section(
        'aqualuxe_header',
        array(
            'title'    => esc_html__('Header Settings', 'aqualuxe'),
            'priority' => 30,
        )
    );

    // Header Style
    $wp_customize->add_setting(
        'aqualuxe_header_style',
        array(
            'default'           => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_style',
        array(
            'label'   => esc_html__('Header Style', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type'    => 'select',
            'choices' => array(
                'default'      => esc_html__('Default', 'aqualuxe'),
                'centered'     => esc_html__('Centered', 'aqualuxe'),
                'transparent'  => esc_html__('Transparent', 'aqualuxe'),
                'minimal'      => esc_html__('Minimal', 'aqualuxe'),
                'split'        => esc_html__('Split', 'aqualuxe'),
                'stacked'      => esc_html__('Stacked', 'aqualuxe'),
            ),
        )
    );

    // Sticky Header
    $wp_customize->add_setting(
        'aqualuxe_enable_sticky_header',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_sticky_header',
        array(
            'label'   => esc_html__('Enable Sticky Header', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type'    => 'checkbox',
        )
    );

    // Top Bar
    $wp_customize->add_setting(
        'aqualuxe_enable_top_bar',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_top_bar',
        array(
            'label'   => esc_html__('Enable Top Bar', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type'    => 'checkbox',
        )
    );

    // Header Search
    $wp_customize->add_setting(
        'aqualuxe_enable_header_search',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_header_search',
        array(
            'label'   => esc_html__('Enable Header Search', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type'    => 'checkbox',
        )
    );

    // Header CTA Button
    $wp_customize->add_setting(
        'aqualuxe_enable_header_cta',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_header_cta',
        array(
            'label'   => esc_html__('Enable Header CTA Button', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_header_cta_text',
        array(
            'default'           => esc_html__('Get a Quote', 'aqualuxe'),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_cta_text',
        array(
            'label'   => esc_html__('CTA Button Text', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type'    => 'text',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_header_cta_url',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_cta_url',
        array(
            'label'   => esc_html__('CTA Button URL', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type'    => 'url',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_header_cta_target',
        array(
            'default'           => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_cta_target',
        array(
            'label'   => esc_html__('Open CTA in New Tab', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type'    => 'checkbox',
        )
    );

    // Header Height
    $wp_customize->add_setting(
        'aqualuxe_header_height',
        array(
            'default'           => '80',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_height',
        array(
            'label'       => esc_html__('Header Height (px)', 'aqualuxe'),
            'section'     => 'aqualuxe_header',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 50,
                'max'  => 200,
                'step' => 5,
            ),
        )
    );

    // Mobile Header Height
    $wp_customize->add_setting(
        'aqualuxe_mobile_header_height',
        array(
            'default'           => '60',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_mobile_header_height',
        array(
            'label'       => esc_html__('Mobile Header Height (px)', 'aqualuxe'),
            'section'     => 'aqualuxe_header',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 40,
                'max'  => 150,
                'step' => 5,
            ),
        )
    );

    // Header Background
    $wp_customize->add_setting(
        'aqualuxe_header_background',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_header_background',
            array(
                'label'   => esc_html__('Header Background Color', 'aqualuxe'),
                'section' => 'aqualuxe_header',
            )
        )
    );

    // Header Text Color
    $wp_customize->add_setting(
        'aqualuxe_header_text_color',
        array(
            'default'           => '#333333',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_header_text_color',
            array(
                'label'   => esc_html__('Header Text Color', 'aqualuxe'),
                'section' => 'aqualuxe_header',
            )
        )
    );

    // Top Bar Background
    $wp_customize->add_setting(
        'aqualuxe_top_bar_background',
        array(
            'default'           => '#f5f5f5',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_top_bar_background',
            array(
                'label'   => esc_html__('Top Bar Background Color', 'aqualuxe'),
                'section' => 'aqualuxe_header',
            )
        )
    );

    // Top Bar Text Color
    $wp_customize->add_setting(
        'aqualuxe_top_bar_text_color',
        array(
            'default'           => '#666666',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_top_bar_text_color',
            array(
                'label'   => esc_html__('Top Bar Text Color', 'aqualuxe'),
                'section' => 'aqualuxe_header',
            )
        )
    );

    // Header Border
    $wp_customize->add_setting(
        'aqualuxe_enable_header_border',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_header_border',
        array(
            'label'   => esc_html__('Enable Header Bottom Border', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_header_border_color',
        array(
            'default'           => '#eeeeee',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_header_border_color',
            array(
                'label'   => esc_html__('Header Border Color', 'aqualuxe'),
                'section' => 'aqualuxe_header',
            )
        )
    );

    // Mobile Menu Settings
    $wp_customize->add_setting(
        'aqualuxe_mobile_menu_breakpoint',
        array(
            'default'           => '1024',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_mobile_menu_breakpoint',
        array(
            'label'       => esc_html__('Mobile Menu Breakpoint (px)', 'aqualuxe'),
            'description' => esc_html__('Screen width at which the mobile menu appears.', 'aqualuxe'),
            'section'     => 'aqualuxe_header',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 480,
                'max'  => 1366,
                'step' => 1,
            ),
        )
    );

    // Mobile Menu Style
    $wp_customize->add_setting(
        'aqualuxe_mobile_menu_style',
        array(
            'default'           => 'slide',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_mobile_menu_style',
        array(
            'label'   => esc_html__('Mobile Menu Style', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type'    => 'select',
            'choices' => array(
                'slide'  => esc_html__('Slide from Side', 'aqualuxe'),
                'dropdown' => esc_html__('Dropdown', 'aqualuxe'),
                'fullscreen' => esc_html__('Fullscreen', 'aqualuxe'),
            ),
        )
    );

    // Mobile Menu Position
    $wp_customize->add_setting(
        'aqualuxe_mobile_menu_position',
        array(
            'default'           => 'right',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_mobile_menu_position',
        array(
            'label'   => esc_html__('Mobile Menu Position', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type'    => 'select',
            'choices' => array(
                'left'  => esc_html__('Left', 'aqualuxe'),
                'right' => esc_html__('Right', 'aqualuxe'),
            ),
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_mobile_menu_style', 'slide') === 'slide';
            },
        )
    );

    // Page Header
    $wp_customize->add_setting(
        'aqualuxe_show_page_header',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_page_header',
        array(
            'label'   => esc_html__('Show Page Header', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type'    => 'checkbox',
        )
    );

    // Breadcrumbs
    $wp_customize->add_setting(
        'aqualuxe_enable_breadcrumbs',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_breadcrumbs',
        array(
            'label'   => esc_html__('Enable Breadcrumbs', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type'    => 'checkbox',
        )
    );

    // Page Header Background
    $wp_customize->add_setting(
        'aqualuxe_page_header_background',
        array(
            'default'           => '#f5f5f5',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_page_header_background',
            array(
                'label'   => esc_html__('Page Header Background Color', 'aqualuxe'),
                'section' => 'aqualuxe_header',
            )
        )
    );

    // Page Header Text Color
    $wp_customize->add_setting(
        'aqualuxe_page_header_text_color',
        array(
            'default'           => '#333333',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_page_header_text_color',
            array(
                'label'   => esc_html__('Page Header Text Color', 'aqualuxe'),
                'section' => 'aqualuxe_header',
            )
        )
    );

    // Page Header Padding
    $wp_customize->add_setting(
        'aqualuxe_page_header_padding_top',
        array(
            'default'           => '40',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_page_header_padding_top',
        array(
            'label'       => esc_html__('Page Header Top Padding (px)', 'aqualuxe'),
            'section'     => 'aqualuxe_header',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 200,
                'step' => 5,
            ),
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_page_header_padding_bottom',
        array(
            'default'           => '40',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_page_header_padding_bottom',
        array(
            'label'       => esc_html__('Page Header Bottom Padding (px)', 'aqualuxe'),
            'section'     => 'aqualuxe_header',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 200,
                'step' => 5,
            ),
        )
    );

    // Page Header Background Image
    $wp_customize->add_setting(
        'aqualuxe_page_header_bg_image',
        array(
            'default'           => '',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'aqualuxe_page_header_bg_image',
            array(
                'label'     => esc_html__('Page Header Background Image', 'aqualuxe'),
                'section'   => 'aqualuxe_header',
                'mime_type' => 'image',
            )
        )
    );

    // Page Header Background Overlay
    $wp_customize->add_setting(
        'aqualuxe_page_header_bg_overlay',
        array(
            'default'           => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_page_header_bg_overlay',
        array(
            'label'   => esc_html__('Enable Background Overlay', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_page_header_bg_overlay_color',
        array(
            'default'           => 'rgba(0,0,0,0.5)',
            'sanitize_callback' => 'aqualuxe_sanitize_rgba_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_page_header_bg_overlay_color',
            array(
                'label'   => esc_html__('Background Overlay Color', 'aqualuxe'),
                'section' => 'aqualuxe_header',
            )
        )
    );
}
add_action('customize_register', 'aqualuxe_customize_register_header');