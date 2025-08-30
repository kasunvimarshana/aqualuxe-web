<?php
/**
 * AquaLuxe Customizer General Settings
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add general settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customizer_general( $wp_customize ) {
    /**
     * Layout Settings
     */
    
    // Container Width
    $wp_customize->add_setting(
        'aqualuxe_container_width',
        array(
            'default'           => '1200',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'absint',
        )
    );
    
    $wp_customize->add_control(
        new AquaLuxe_Customizer_Control_Slider(
            $wp_customize,
            'aqualuxe_container_width',
            array(
                'label'       => __( 'Container Width', 'aqualuxe' ),
                'description' => __( 'Set the maximum width of the content container (in pixels)', 'aqualuxe' ),
                'section'     => 'aqualuxe_general_layout',
                'input_attrs' => array(
                    'min'  => 960,
                    'max'  => 1600,
                    'step' => 10,
                ),
            )
        )
    );
    
    // Content Layout
    $wp_customize->add_setting(
        'aqualuxe_content_layout',
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'aqualuxe_content_layout',
        array(
            'label'       => __( 'Content Layout', 'aqualuxe' ),
            'description' => __( 'Choose the default layout for pages and posts', 'aqualuxe' ),
            'section'     => 'aqualuxe_general_layout',
            'type'        => 'select',
            'choices'     => array(
                'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
                'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
                'no-sidebar'    => __( 'No Sidebar', 'aqualuxe' ),
            ),
        )
    );
    
    // Boxed Layout
    $wp_customize->add_setting(
        'aqualuxe_boxed_layout',
        array(
            'default'           => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new AquaLuxe_Customizer_Control_Toggle(
            $wp_customize,
            'aqualuxe_boxed_layout',
            array(
                'label'       => __( 'Boxed Layout', 'aqualuxe' ),
                'description' => __( 'Enable boxed layout for the site', 'aqualuxe' ),
                'section'     => 'aqualuxe_general_layout',
            )
        )
    );
    
    // Boxed Layout Width
    $wp_customize->add_setting(
        'aqualuxe_boxed_layout_width',
        array(
            'default'           => '1200',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'absint',
        )
    );
    
    $wp_customize->add_control(
        new AquaLuxe_Customizer_Control_Slider(
            $wp_customize,
            'aqualuxe_boxed_layout_width',
            array(
                'label'           => __( 'Boxed Layout Width', 'aqualuxe' ),
                'description'     => __( 'Set the width of the boxed layout (in pixels)', 'aqualuxe' ),
                'section'         => 'aqualuxe_general_layout',
                'input_attrs'     => array(
                    'min'  => 960,
                    'max'  => 1600,
                    'step' => 10,
                ),
                'active_callback' => function() {
                    return get_theme_mod( 'aqualuxe_boxed_layout', false );
                },
            )
        )
    );
    
    // Sidebar Width
    $wp_customize->add_setting(
        'aqualuxe_sidebar_width',
        array(
            'default'           => '30',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'absint',
        )
    );
    
    $wp_customize->add_control(
        new AquaLuxe_Customizer_Control_Slider(
            $wp_customize,
            'aqualuxe_sidebar_width',
            array(
                'label'       => __( 'Sidebar Width', 'aqualuxe' ),
                'description' => __( 'Set the width of the sidebar (in percentage)', 'aqualuxe' ),
                'section'     => 'aqualuxe_general_layout',
                'input_attrs' => array(
                    'min'  => 20,
                    'max'  => 40,
                    'step' => 1,
                ),
                'active_callback' => function() {
                    return get_theme_mod( 'aqualuxe_content_layout', 'right-sidebar' ) !== 'no-sidebar';
                },
            )
        )
    );
    
    /**
     * Performance Settings
     */
    
    // Enable Lazy Loading
    $wp_customize->add_setting(
        'aqualuxe_lazy_loading',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new AquaLuxe_Customizer_Control_Toggle(
            $wp_customize,
            'aqualuxe_lazy_loading',
            array(
                'label'       => __( 'Lazy Loading', 'aqualuxe' ),
                'description' => __( 'Enable lazy loading for images', 'aqualuxe' ),
                'section'     => 'aqualuxe_general_performance',
            )
        )
    );
    
    // Preload Critical CSS
    $wp_customize->add_setting(
        'aqualuxe_preload_critical_css',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new AquaLuxe_Customizer_Control_Toggle(
            $wp_customize,
            'aqualuxe_preload_critical_css',
            array(
                'label'       => __( 'Preload Critical CSS', 'aqualuxe' ),
                'description' => __( 'Enable preloading of critical CSS for better performance', 'aqualuxe' ),
                'section'     => 'aqualuxe_general_performance',
            )
        )
    );
    
    // Minify CSS
    $wp_customize->add_setting(
        'aqualuxe_minify_css',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new AquaLuxe_Customizer_Control_Toggle(
            $wp_customize,
            'aqualuxe_minify_css',
            array(
                'label'       => __( 'Minify CSS', 'aqualuxe' ),
                'description' => __( 'Enable minification of CSS files', 'aqualuxe' ),
                'section'     => 'aqualuxe_general_performance',
            )
        )
    );
    
    // Minify JavaScript
    $wp_customize->add_setting(
        'aqualuxe_minify_js',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new AquaLuxe_Customizer_Control_Toggle(
            $wp_customize,
            'aqualuxe_minify_js',
            array(
                'label'       => __( 'Minify JavaScript', 'aqualuxe' ),
                'description' => __( 'Enable minification of JavaScript files', 'aqualuxe' ),
                'section'     => 'aqualuxe_general_performance',
            )
        )
    );
    
    // Defer JavaScript
    $wp_customize->add_setting(
        'aqualuxe_defer_js',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new AquaLuxe_Customizer_Control_Toggle(
            $wp_customize,
            'aqualuxe_defer_js',
            array(
                'label'       => __( 'Defer JavaScript', 'aqualuxe' ),
                'description' => __( 'Enable deferring of JavaScript files', 'aqualuxe' ),
                'section'     => 'aqualuxe_general_performance',
            )
        )
    );
    
    // Enable Browser Caching
    $wp_customize->add_setting(
        'aqualuxe_browser_caching',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new AquaLuxe_Customizer_Control_Toggle(
            $wp_customize,
            'aqualuxe_browser_caching',
            array(
                'label'       => __( 'Browser Caching', 'aqualuxe' ),
                'description' => __( 'Enable browser caching for better performance', 'aqualuxe' ),
                'section'     => 'aqualuxe_general_performance',
            )
        )
    );
}

/**
 * Sanitize checkbox value
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox( $checked ) {
    return ( isset( $checked ) && true === $checked ) ? true : false;
}

/**
 * Sanitize select value
 *
 * @param string $input Select value.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return string
 */
function aqualuxe_sanitize_select( $input, $setting ) {
    // Get list of choices from the control associated with the setting
    $choices = $setting->manager->get_control( $setting->id )->choices;
    
    // Return input if valid or return default option
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}