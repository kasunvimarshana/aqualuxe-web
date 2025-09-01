<?php
/** Theme Customizer */
if (!defined('ABSPATH')) { exit; }

add_action('customize_register', function($wp_customize){
    $wp_customize->add_section('aqualuxe_brand', [
        'title' => __('AquaLuxe Branding', 'aqualuxe'),
        'priority' => 30,
    ]);

    // Colors
    $wp_customize->add_setting('aqualuxe_primary_color', [
        'default' => '#0ea5e9',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', [
        'label' => __('Primary Color', 'aqualuxe'),
        'section' => 'aqualuxe_brand',
    ]));

    // Typography (simple select)
    $wp_customize->add_setting('aqualuxe_font_family', [
        'default' => 'ui-sans-serif',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('aqualuxe_font_family', [
        'label' => __('Base Font Family', 'aqualuxe'),
        'type' => 'select',
        'choices' => [
            'ui-sans-serif' => 'System Sans',
            'serif' => 'Serif',
            'mono' => 'Monospace',
        ],
        'section' => 'aqualuxe_brand',
    ]);

    // Layout width
    $wp_customize->add_setting('aqualuxe_container_width', [
        'default' => 'max-w-7xl',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('aqualuxe_container_width', [
        'label' => __('Container Width', 'aqualuxe'),
        'type' => 'select',
        'choices' => [
            'max-w-5xl' => 'Narrow',
            'max-w-7xl' => 'Default',
            'max-w-screen-2xl' => 'Wide',
        ],
        'section' => 'aqualuxe_brand',
    ]);

    // Dark mode default
    $wp_customize->add_setting('aqualuxe_dark_mode_default', [
        'default' => false,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ]);
    $wp_customize->add_control('aqualuxe_dark_mode_default', [
        'label' => __('Enable Dark Mode by default', 'aqualuxe'),
        'type' => 'checkbox',
        'section' => 'aqualuxe_brand',
    ]);

    // Visible breadcrumbs toggle
    $wp_customize->add_setting('aqualuxe_breadcrumbs_enabled', [
        'default' => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ]);
    $wp_customize->add_control('aqualuxe_breadcrumbs_enabled', [
        'label' => __('Show breadcrumbs', 'aqualuxe'),
        'type' => 'checkbox',
        'section' => 'aqualuxe_brand',
    ]);

    // Social: Twitter handle
    $wp_customize->add_setting('aqualuxe_twitter_handle', [
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('aqualuxe_twitter_handle', [
        'label' => __('Twitter handle (without @)', 'aqualuxe'),
        'type' => 'text',
        'section' => 'aqualuxe_brand',
    ]);

    // Social: Organization SameAs URLs (one per line)
    $wp_customize->add_setting('aqualuxe_sameas', [
        'default' => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp_customize->add_control('aqualuxe_sameas', [
        'label' => __('Organization profiles (one URL per line)', 'aqualuxe'),
        'type' => 'textarea',
        'section' => 'aqualuxe_brand',
    ]);
});
