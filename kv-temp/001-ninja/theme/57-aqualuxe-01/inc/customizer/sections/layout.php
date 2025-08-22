<?php
/**
 * Layout Customizer Section
 *
 * @package AquaLuxe
 */

/**
 * Add layout settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_layout($wp_customize) {
    // Add Layout section
    $wp_customize->add_section(
        'aqualuxe_layout',
        array(
            'title'    => esc_html__('Layout Settings', 'aqualuxe'),
            'priority' => 80,
        )
    );

    // Default Layout
    $wp_customize->add_setting(
        'aqualuxe_default_layout',
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_default_layout',
        array(
            'label'   => esc_html__('Default Layout', 'aqualuxe'),
            'section' => 'aqualuxe_layout',
            'type'    => 'select',
            'choices' => array(
                'right-sidebar' => esc_html__('Right Sidebar', 'aqualuxe'),
                'left-sidebar'  => esc_html__('Left Sidebar', 'aqualuxe'),
                'full-width'    => esc_html__('Full Width', 'aqualuxe'),
                'narrow'        => esc_html__('Narrow', 'aqualuxe'),
            ),
        )
    );

    // Archive Layout
    $wp_customize->add_setting(
        'aqualuxe_archive_layout',
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_archive_layout',
        array(
            'label'   => esc_html__('Archive Layout', 'aqualuxe'),
            'section' => 'aqualuxe_layout',
            'type'    => 'select',
            'choices' => array(
                'right-sidebar' => esc_html__('Right Sidebar', 'aqualuxe'),
                'left-sidebar'  => esc_html__('Left Sidebar', 'aqualuxe'),
                'full-width'    => esc_html__('Full Width', 'aqualuxe'),
                'narrow'        => esc_html__('Narrow', 'aqualuxe'),
            ),
        )
    );

    // Single Post Layout
    $wp_customize->add_setting(
        'aqualuxe_single_post_layout',
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_single_post_layout',
        array(
            'label'   => esc_html__('Single Post Layout', 'aqualuxe'),
            'section' => 'aqualuxe_layout',
            'type'    => 'select',
            'choices' => array(
                'right-sidebar' => esc_html__('Right Sidebar', 'aqualuxe'),
                'left-sidebar'  => esc_html__('Left Sidebar', 'aqualuxe'),
                'full-width'    => esc_html__('Full Width', 'aqualuxe'),
                'narrow'        => esc_html__('Narrow', 'aqualuxe'),
            ),
        )
    );

    // Page Layout
    $wp_customize->add_setting(
        'aqualuxe_page_layout',
        array(
            'default'           => 'full-width',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_page_layout',
        array(
            'label'   => esc_html__('Page Layout', 'aqualuxe'),
            'section' => 'aqualuxe_layout',
            'type'    => 'select',
            'choices' => array(
                'right-sidebar' => esc_html__('Right Sidebar', 'aqualuxe'),
                'left-sidebar'  => esc_html__('Left Sidebar', 'aqualuxe'),
                'full-width'    => esc_html__('Full Width', 'aqualuxe'),
                'narrow'        => esc_html__('Narrow', 'aqualuxe'),
            ),
        )
    );

    // WooCommerce Shop Layout
    if (class_exists('WooCommerce')) {
        $wp_customize->add_setting(
            'aqualuxe_shop_layout',
            array(
                'default'           => 'right-sidebar',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_shop_layout',
            array(
                'label'   => esc_html__('Shop Layout', 'aqualuxe'),
                'section' => 'aqualuxe_layout',
                'type'    => 'select',
                'choices' => array(
                    'right-sidebar' => esc_html__('Right Sidebar', 'aqualuxe'),
                    'left-sidebar'  => esc_html__('Left Sidebar', 'aqualuxe'),
                    'full-width'    => esc_html__('Full Width', 'aqualuxe'),
                ),
            )
        );

        // WooCommerce Product Layout
        $wp_customize->add_setting(
            'aqualuxe_product_layout',
            array(
                'default'           => 'right-sidebar',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_product_layout',
            array(
                'label'   => esc_html__('Product Layout', 'aqualuxe'),
                'section' => 'aqualuxe_layout',
                'type'    => 'select',
                'choices' => array(
                    'right-sidebar' => esc_html__('Right Sidebar', 'aqualuxe'),
                    'left-sidebar'  => esc_html__('Left Sidebar', 'aqualuxe'),
                    'full-width'    => esc_html__('Full Width', 'aqualuxe'),
                ),
            )
        );
    }

    // Sidebar Width
    $wp_customize->add_setting(
        'aqualuxe_sidebar_width',
        array(
            'default'           => '30',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_sidebar_width',
        array(
            'label'       => esc_html__('Sidebar Width (%)', 'aqualuxe'),
            'section'     => 'aqualuxe_layout',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 20,
                'max'  => 40,
                'step' => 1,
            ),
        )
    );

    // Content Width
    $wp_customize->add_setting(
        'aqualuxe_content_width',
        array(
            'default'           => '1200',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_content_width',
        array(
            'label'       => esc_html__('Content Width (px)', 'aqualuxe'),
            'section'     => 'aqualuxe_layout',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 800,
                'max'  => 1600,
                'step' => 10,
            ),
        )
    );

    // Narrow Width
    $wp_customize->add_setting(
        'aqualuxe_narrow_width',
        array(
            'default'           => '800',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_narrow_width',
        array(
            'label'       => esc_html__('Narrow Width (px)', 'aqualuxe'),
            'section'     => 'aqualuxe_layout',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 600,
                'max'  => 1000,
                'step' => 10,
            ),
        )
    );

    // Content Padding
    $wp_customize->add_setting(
        'aqualuxe_content_padding',
        array(
            'default'           => '80',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_content_padding',
        array(
            'label'       => esc_html__('Content Padding (px)', 'aqualuxe'),
            'description' => esc_html__('Padding between header/footer and content.', 'aqualuxe'),
            'section'     => 'aqualuxe_layout',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 20,
                'max'  => 200,
                'step' => 5,
            ),
        )
    );

    // Mobile Content Padding
    $wp_customize->add_setting(
        'aqualuxe_mobile_content_padding',
        array(
            'default'           => '40',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_mobile_content_padding',
        array(
            'label'       => esc_html__('Mobile Content Padding (px)', 'aqualuxe'),
            'description' => esc_html__('Padding between header/footer and content on mobile devices.', 'aqualuxe'),
            'section'     => 'aqualuxe_layout',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 10,
                'max'  => 100,
                'step' => 5,
            ),
        )
    );

    // Container Padding
    $wp_customize->add_setting(
        'aqualuxe_container_padding',
        array(
            'default'           => '15',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_container_padding',
        array(
            'label'       => esc_html__('Container Padding (px)', 'aqualuxe'),
            'description' => esc_html__('Horizontal padding for containers.', 'aqualuxe'),
            'section'     => 'aqualuxe_layout',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 50,
                'step' => 5,
            ),
        )
    );

    // Border Radius
    $wp_customize->add_setting(
        'aqualuxe_border_radius',
        array(
            'default'           => '4',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_border_radius',
        array(
            'label'       => esc_html__('Border Radius (px)', 'aqualuxe'),
            'description' => esc_html__('Global border radius for buttons, inputs, etc.', 'aqualuxe'),
            'section'     => 'aqualuxe_layout',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 20,
                'step' => 1,
            ),
        )
    );

    // Box Shadow
    $wp_customize->add_setting(
        'aqualuxe_enable_box_shadow',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_box_shadow',
        array(
            'label'       => esc_html__('Enable Box Shadow', 'aqualuxe'),
            'description' => esc_html__('Add subtle box shadows to cards, buttons, etc.', 'aqualuxe'),
            'section'     => 'aqualuxe_layout',
            'type'        => 'checkbox',
        )
    );

    // Box Shadow Intensity
    $wp_customize->add_setting(
        'aqualuxe_box_shadow_intensity',
        array(
            'default'           => 'medium',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_box_shadow_intensity',
        array(
            'label'       => esc_html__('Box Shadow Intensity', 'aqualuxe'),
            'section'     => 'aqualuxe_layout',
            'type'        => 'select',
            'choices'     => array(
                'light'  => esc_html__('Light', 'aqualuxe'),
                'medium' => esc_html__('Medium', 'aqualuxe'),
                'heavy'  => esc_html__('Heavy', 'aqualuxe'),
            ),
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_enable_box_shadow', true);
            },
        )
    );

    // Buttons Style
    $wp_customize->add_setting(
        'aqualuxe_button_style',
        array(
            'default'           => 'rounded',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_button_style',
        array(
            'label'       => esc_html__('Button Style', 'aqualuxe'),
            'section'     => 'aqualuxe_layout',
            'type'        => 'select',
            'choices'     => array(
                'square'  => esc_html__('Square', 'aqualuxe'),
                'rounded' => esc_html__('Rounded', 'aqualuxe'),
                'pill'    => esc_html__('Pill', 'aqualuxe'),
            ),
        )
    );

    // Buttons Size
    $wp_customize->add_setting(
        'aqualuxe_button_size',
        array(
            'default'           => 'medium',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_button_size',
        array(
            'label'       => esc_html__('Button Size', 'aqualuxe'),
            'section'     => 'aqualuxe_layout',
            'type'        => 'select',
            'choices'     => array(
                'small'  => esc_html__('Small', 'aqualuxe'),
                'medium' => esc_html__('Medium', 'aqualuxe'),
                'large'  => esc_html__('Large', 'aqualuxe'),
            ),
        )
    );

    // Form Fields Style
    $wp_customize->add_setting(
        'aqualuxe_form_style',
        array(
            'default'           => 'rounded',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_form_style',
        array(
            'label'       => esc_html__('Form Fields Style', 'aqualuxe'),
            'section'     => 'aqualuxe_layout',
            'type'        => 'select',
            'choices'     => array(
                'square'  => esc_html__('Square', 'aqualuxe'),
                'rounded' => esc_html__('Rounded', 'aqualuxe'),
                'pill'    => esc_html__('Pill', 'aqualuxe'),
            ),
        )
    );

    // Form Fields Size
    $wp_customize->add_setting(
        'aqualuxe_form_size',
        array(
            'default'           => 'medium',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_form_size',
        array(
            'label'       => esc_html__('Form Fields Size', 'aqualuxe'),
            'section'     => 'aqualuxe_layout',
            'type'        => 'select',
            'choices'     => array(
                'small'  => esc_html__('Small', 'aqualuxe'),
                'medium' => esc_html__('Medium', 'aqualuxe'),
                'large'  => esc_html__('Large', 'aqualuxe'),
            ),
        )
    );

    // Card Style
    $wp_customize->add_setting(
        'aqualuxe_card_style',
        array(
            'default'           => 'rounded',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_card_style',
        array(
            'label'       => esc_html__('Card Style', 'aqualuxe'),
            'section'     => 'aqualuxe_layout',
            'type'        => 'select',
            'choices'     => array(
                'square'  => esc_html__('Square', 'aqualuxe'),
                'rounded' => esc_html__('Rounded', 'aqualuxe'),
            ),
        )
    );

    // Animation
    $wp_customize->add_setting(
        'aqualuxe_enable_animations',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_animations',
        array(
            'label'       => esc_html__('Enable Animations', 'aqualuxe'),
            'description' => esc_html__('Enable subtle animations and transitions.', 'aqualuxe'),
            'section'     => 'aqualuxe_layout',
            'type'        => 'checkbox',
        )
    );

    // Reduced Motion
    $wp_customize->add_setting(
        'aqualuxe_respect_reduced_motion',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_respect_reduced_motion',
        array(
            'label'       => esc_html__('Respect Reduced Motion Preference', 'aqualuxe'),
            'description' => esc_html__('Disable animations for users who prefer reduced motion.', 'aqualuxe'),
            'section'     => 'aqualuxe_layout',
            'type'        => 'checkbox',
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_enable_animations', true);
            },
        )
    );
}
add_action('customize_register', 'aqualuxe_customize_register_layout');