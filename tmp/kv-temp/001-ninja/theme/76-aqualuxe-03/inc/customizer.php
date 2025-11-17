<?php
defined('ABSPATH') || exit;

add_action('customize_register', function ($wp_customize) {
    // Colors
    $wp_customize->add_section('aqlx_colors', [
        'title' => __('AquaLuxe Colors', AQUALUXE_TEXT),
        'priority' => 30,
    ]);

    $wp_customize->add_setting('aqualuxe_primary_color', [
        'default' => '#0ea5e9',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', [
        'label' => __('Primary Color', AQUALUXE_TEXT),
        'section' => 'aqlx_colors',
    ]));

    $wp_customize->add_setting('aqualuxe_accent_color', [
        'default' => '#14b8a6',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', [
        'label' => __('Accent Color', AQUALUXE_TEXT),
        'section' => 'aqlx_colors',
    ]));

    // Typography
    $wp_customize->add_section('aqlx_typography', [
        'title' => __('AquaLuxe Typography', AQUALUXE_TEXT),
    ]);
    $wp_customize->add_setting('aqualuxe_font_family', [
        'default' => 'ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, Noto Sans, "Apple Color Emoji", "Segoe UI Emoji"',
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('aqualuxe_font_family', [
        'label' => __('Base Font Stack', AQUALUXE_TEXT),
        'type' => 'text',
        'section' => 'aqlx_typography',
    ]);

    // Layout
    $wp_customize->add_section('aqlx_layout', [
        'title' => __('AquaLuxe Layout', AQUALUXE_TEXT),
    ]);
    $wp_customize->add_setting('aqualuxe_container_width', [
        'default' => 1200,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('aqualuxe_container_width', [
        'label' => __('Container Width (px)', AQUALUXE_TEXT),
        'type' => 'number',
        'section' => 'aqlx_layout',
    ]);

    // Dark mode default
    $wp_customize->add_setting('aqualuxe_dark_mode_default', [
        'default' => false,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ]);
    $wp_customize->add_control('aqualuxe_dark_mode_default', [
        'label' => __('Enable Dark Mode by default', AQUALUXE_TEXT),
        'type' => 'checkbox',
        'section' => 'aqlx_colors',
    ]);

    // Contact page settings
    $wp_customize->add_section('aqlx_contact', [
        'title' => __('AquaLuxe Contact', AQUALUXE_TEXT),
    ]);
    $wp_customize->add_setting('aqualuxe_contact_email', [
        'default' => get_option('admin_email'),
        'sanitize_callback' => 'sanitize_email',
    ]);
    $wp_customize->add_control('aqualuxe_contact_email', [
        'label' => __('Contact Form Recipient Email', AQUALUXE_TEXT),
        'type' => 'email',
        'section' => 'aqlx_contact',
    ]);
    $wp_customize->add_setting('aqualuxe_map_embed', [
        'default' => '',
        'sanitize_callback' => function ($val) {
            // Allow only a very small subset of tags/attrs for map embeds
            $allowed = [
                'iframe' => [
                    'src' => true,
                    'width' => true,
                    'height' => true,
                    'style' => true,
                    'loading' => true,
                    'referrerpolicy' => true,
                    'allowfullscreen' => true,
                ],
            ];
            return wp_kses($val, $allowed);
        },
    ]);
    $wp_customize->add_control('aqualuxe_map_embed', [
        'label' => __('Map Embed (optional iframe)', AQUALUXE_TEXT),
        'type' => 'textarea',
        'section' => 'aqlx_contact',
        'description' => __('Paste an iframe embed (e.g., OpenStreetMap). Kept to a safe subset of attributes.', AQUALUXE_TEXT),
    ]);

    // B2B settings
    $wp_customize->add_section('aqlx_b2b', [
        'title' => __('AquaLuxe B2B', AQUALUXE_TEXT),
    ]);
    $wp_customize->add_setting('aqlx_b2b_hide_guest_prices', [
        'default' => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ]);
    $wp_customize->add_control('aqlx_b2b_hide_guest_prices', [
        'label' => __('Hide prices and purchasing for guests', AQUALUXE_TEXT),
        'type' => 'checkbox',
        'section' => 'aqlx_b2b',
    ]);
    $wp_customize->add_setting('aqlx_b2b_notice', [
        'default' => __('Please log in to view prices and request a quote.', AQUALUXE_TEXT),
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('aqlx_b2b_notice', [
        'label' => __('B2B Notice (shown when prices hidden)', AQUALUXE_TEXT),
        'type' => 'textarea',
        'section' => 'aqlx_b2b',
    ]);
});
