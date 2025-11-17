<?php
/**
 * Contact Information Customizer Section
 *
 * @package AquaLuxe
 */

/**
 * Add contact information settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_contact($wp_customize) {
    // Add Contact Information section
    $wp_customize->add_section(
        'aqualuxe_contact',
        array(
            'title'    => esc_html__('Contact Information', 'aqualuxe'),
            'priority' => 100,
        )
    );

    // Phone Number
    $wp_customize->add_setting(
        'aqualuxe_phone',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_phone',
        array(
            'label'   => esc_html__('Phone Number', 'aqualuxe'),
            'section' => 'aqualuxe_contact',
            'type'    => 'text',
        )
    );

    // Email Address
    $wp_customize->add_setting(
        'aqualuxe_email',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_email',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_email',
        array(
            'label'   => esc_html__('Email Address', 'aqualuxe'),
            'section' => 'aqualuxe_contact',
            'type'    => 'email',
        )
    );

    // Address
    $wp_customize->add_setting(
        'aqualuxe_address',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_address',
        array(
            'label'   => esc_html__('Address', 'aqualuxe'),
            'section' => 'aqualuxe_contact',
            'type'    => 'textarea',
        )
    );

    // Business Hours
    $wp_customize->add_setting(
        'aqualuxe_hours',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_hours',
        array(
            'label'   => esc_html__('Business Hours', 'aqualuxe'),
            'section' => 'aqualuxe_contact',
            'type'    => 'textarea',
        )
    );

    // Google Maps
    $wp_customize->add_setting(
        'aqualuxe_google_maps_embed',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_google_maps_embed',
        array(
            'label'       => esc_html__('Google Maps Embed Code', 'aqualuxe'),
            'description' => esc_html__('Paste the iframe embed code from Google Maps.', 'aqualuxe'),
            'section'     => 'aqualuxe_contact',
            'type'        => 'textarea',
        )
    );

    // Google Maps API Key
    $wp_customize->add_setting(
        'aqualuxe_google_maps_api_key',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_google_maps_api_key',
        array(
            'label'       => esc_html__('Google Maps API Key', 'aqualuxe'),
            'description' => esc_html__('Required for displaying Google Maps on your website.', 'aqualuxe'),
            'section'     => 'aqualuxe_contact',
            'type'        => 'text',
        )
    );

    // Google Maps Latitude
    $wp_customize->add_setting(
        'aqualuxe_google_maps_latitude',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_google_maps_latitude',
        array(
            'label'   => esc_html__('Google Maps Latitude', 'aqualuxe'),
            'section' => 'aqualuxe_contact',
            'type'    => 'text',
        )
    );

    // Google Maps Longitude
    $wp_customize->add_setting(
        'aqualuxe_google_maps_longitude',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_google_maps_longitude',
        array(
            'label'   => esc_html__('Google Maps Longitude', 'aqualuxe'),
            'section' => 'aqualuxe_contact',
            'type'    => 'text',
        )
    );

    // Google Maps Zoom Level
    $wp_customize->add_setting(
        'aqualuxe_google_maps_zoom',
        array(
            'default'           => '14',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_google_maps_zoom',
        array(
            'label'       => esc_html__('Google Maps Zoom Level', 'aqualuxe'),
            'section'     => 'aqualuxe_contact',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 20,
                'step' => 1,
            ),
        )
    );

    // Contact Form 7 Shortcode
    $wp_customize->add_setting(
        'aqualuxe_contact_form_shortcode',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_contact_form_shortcode',
        array(
            'label'       => esc_html__('Contact Form Shortcode', 'aqualuxe'),
            'description' => esc_html__('Enter the shortcode for your contact form (e.g., Contact Form 7).', 'aqualuxe'),
            'section'     => 'aqualuxe_contact',
            'type'        => 'text',
        )
    );

    // Contact Page Settings
    $wp_customize->add_setting(
        'aqualuxe_contact_page_layout',
        array(
            'default'           => 'standard',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_contact_page_layout',
        array(
            'label'   => esc_html__('Contact Page Layout', 'aqualuxe'),
            'section' => 'aqualuxe_contact',
            'type'    => 'select',
            'choices' => array(
                'standard'      => esc_html__('Standard (Form and Info)', 'aqualuxe'),
                'form_left'     => esc_html__('Form Left, Info Right', 'aqualuxe'),
                'form_right'    => esc_html__('Form Right, Info Left', 'aqualuxe'),
                'form_above'    => esc_html__('Form Above, Info Below', 'aqualuxe'),
                'form_below'    => esc_html__('Form Below, Info Above', 'aqualuxe'),
                'map_above'     => esc_html__('Map Above, Form and Info Below', 'aqualuxe'),
                'map_below'     => esc_html__('Map Below, Form and Info Above', 'aqualuxe'),
            ),
        )
    );

    // Show Contact Information on Contact Page
    $wp_customize->add_setting(
        'aqualuxe_show_contact_info_on_page',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_contact_info_on_page',
        array(
            'label'   => esc_html__('Show Contact Information on Contact Page', 'aqualuxe'),
            'section' => 'aqualuxe_contact',
            'type'    => 'checkbox',
        )
    );

    // Show Map on Contact Page
    $wp_customize->add_setting(
        'aqualuxe_show_map_on_contact_page',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_map_on_contact_page',
        array(
            'label'   => esc_html__('Show Map on Contact Page', 'aqualuxe'),
            'section' => 'aqualuxe_contact',
            'type'    => 'checkbox',
        )
    );

    // Contact Icons
    $wp_customize->add_setting(
        'aqualuxe_show_contact_icons',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_contact_icons',
        array(
            'label'   => esc_html__('Show Contact Icons', 'aqualuxe'),
            'section' => 'aqualuxe_contact',
            'type'    => 'checkbox',
        )
    );

    // Contact Icons Style
    $wp_customize->add_setting(
        'aqualuxe_contact_icons_style',
        array(
            'default'           => 'filled',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_contact_icons_style',
        array(
            'label'   => esc_html__('Contact Icons Style', 'aqualuxe'),
            'section' => 'aqualuxe_contact',
            'type'    => 'select',
            'choices' => array(
                'filled'    => esc_html__('Filled', 'aqualuxe'),
                'outlined'  => esc_html__('Outlined', 'aqualuxe'),
                'minimal'   => esc_html__('Minimal', 'aqualuxe'),
                'rounded'   => esc_html__('Rounded', 'aqualuxe'),
                'circle'    => esc_html__('Circle', 'aqualuxe'),
                'square'    => esc_html__('Square', 'aqualuxe'),
            ),
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_show_contact_icons', true);
            },
        )
    );

    // Contact Icons Color
    $wp_customize->add_setting(
        'aqualuxe_contact_icons_color',
        array(
            'default'           => '#0073aa',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_contact_icons_color',
            array(
                'label'   => esc_html__('Contact Icons Color', 'aqualuxe'),
                'section' => 'aqualuxe_contact',
                'active_callback' => function() {
                    return get_theme_mod('aqualuxe_show_contact_icons', true);
                },
            )
        )
    );

    // Show Contact Information in Header
    $wp_customize->add_setting(
        'aqualuxe_show_contact_in_header',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_contact_in_header',
        array(
            'label'   => esc_html__('Show Contact Information in Header', 'aqualuxe'),
            'section' => 'aqualuxe_contact',
            'type'    => 'checkbox',
        )
    );

    // Show Contact Information in Footer
    $wp_customize->add_setting(
        'aqualuxe_show_contact_in_footer',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_contact_in_footer',
        array(
            'label'   => esc_html__('Show Contact Information in Footer', 'aqualuxe'),
            'section' => 'aqualuxe_contact',
            'type'    => 'checkbox',
        )
    );

    // Contact Information to Show in Header
    $contact_items = array(
        'phone'   => esc_html__('Phone', 'aqualuxe'),
        'email'   => esc_html__('Email', 'aqualuxe'),
        'address' => esc_html__('Address', 'aqualuxe'),
        'hours'   => esc_html__('Business Hours', 'aqualuxe'),
    );

    foreach ($contact_items as $item => $label) {
        $wp_customize->add_setting(
            'aqualuxe_show_' . $item . '_in_header',
            array(
                'default'           => $item === 'phone' || $item === 'email',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_' . $item . '_in_header',
            array(
                'label'   => sprintf(esc_html__('Show %s in Header', 'aqualuxe'), $label),
                'section' => 'aqualuxe_contact',
                'type'    => 'checkbox',
                'active_callback' => function() {
                    return get_theme_mod('aqualuxe_show_contact_in_header', true);
                },
            )
        );
    }

    // Contact Information to Show in Footer
    foreach ($contact_items as $item => $label) {
        $wp_customize->add_setting(
            'aqualuxe_show_' . $item . '_in_footer',
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_' . $item . '_in_footer',
            array(
                'label'   => sprintf(esc_html__('Show %s in Footer', 'aqualuxe'), $label),
                'section' => 'aqualuxe_contact',
                'type'    => 'checkbox',
                'active_callback' => function() {
                    return get_theme_mod('aqualuxe_show_contact_in_footer', true);
                },
            )
        );
    }
}
add_action('customize_register', 'aqualuxe_customize_register_contact');