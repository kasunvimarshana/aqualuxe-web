<?php
/**
 * General Customizer Section
 *
 * @package AquaLuxe
 */

/**
 * Add general settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_general($wp_customize) {
    // Add General section
    $wp_customize->add_section(
        'aqualuxe_general',
        array(
            'title'    => esc_html__('General Settings', 'aqualuxe'),
            'priority' => 20,
        )
    );

    // Site Identity
    $wp_customize->add_setting(
        'aqualuxe_site_logo_width',
        array(
            'default'           => '180',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_site_logo_width',
        array(
            'label'       => esc_html__('Logo Width (px)', 'aqualuxe'),
            'section'     => 'title_tagline',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 50,
                'max'  => 500,
                'step' => 5,
            ),
        )
    );

    // Dark Mode Logo
    $wp_customize->add_setting(
        'aqualuxe_dark_logo',
        array(
            'default'           => '',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'aqualuxe_dark_logo',
            array(
                'label'     => esc_html__('Dark Mode Logo', 'aqualuxe'),
                'section'   => 'title_tagline',
                'mime_type' => 'image',
                'priority'  => 9,
            )
        )
    );

    // Favicon
    $wp_customize->add_setting(
        'aqualuxe_favicon',
        array(
            'default'           => '',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'aqualuxe_favicon',
            array(
                'label'     => esc_html__('Favicon', 'aqualuxe'),
                'section'   => 'title_tagline',
                'mime_type' => 'image',
                'priority'  => 10,
            )
        )
    );

    // Container Width
    $wp_customize->add_setting(
        'aqualuxe_container_width',
        array(
            'default'           => '1200',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_container_width',
        array(
            'label'       => esc_html__('Container Width (px)', 'aqualuxe'),
            'section'     => 'aqualuxe_general',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 960,
                'max'  => 1920,
                'step' => 10,
            ),
        )
    );

    // Preloader
    $wp_customize->add_setting(
        'aqualuxe_enable_preloader',
        array(
            'default'           => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_preloader',
        array(
            'label'   => esc_html__('Enable Preloader', 'aqualuxe'),
            'section' => 'aqualuxe_general',
            'type'    => 'checkbox',
        )
    );

    // Back to Top Button
    $wp_customize->add_setting(
        'aqualuxe_enable_back_to_top',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_back_to_top',
        array(
            'label'   => esc_html__('Enable Back to Top Button', 'aqualuxe'),
            'section' => 'aqualuxe_general',
            'type'    => 'checkbox',
        )
    );

    // Lazy Loading
    $wp_customize->add_setting(
        'aqualuxe_enable_lazy_loading',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_lazy_loading',
        array(
            'label'   => esc_html__('Enable Lazy Loading for Images', 'aqualuxe'),
            'section' => 'aqualuxe_general',
            'type'    => 'checkbox',
        )
    );

    // Disable Emojis
    $wp_customize->add_setting(
        'aqualuxe_disable_emojis',
        array(
            'default'           => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_disable_emojis',
        array(
            'label'       => esc_html__('Disable WordPress Emojis', 'aqualuxe'),
            'description' => esc_html__('Improves performance by removing emoji-related scripts and styles.', 'aqualuxe'),
            'section'     => 'aqualuxe_general',
            'type'        => 'checkbox',
        )
    );

    // Cookie Notice
    $wp_customize->add_setting(
        'aqualuxe_enable_cookie_notice',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_cookie_notice',
        array(
            'label'   => esc_html__('Enable Cookie Notice', 'aqualuxe'),
            'section' => 'aqualuxe_general',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_cookie_notice_text',
        array(
            'default'           => esc_html__('We use cookies to improve your experience on our website. By browsing this website, you agree to our use of cookies.', 'aqualuxe'),
            'sanitize_callback' => 'aqualuxe_sanitize_textarea',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_cookie_notice_text',
        array(
            'label'   => esc_html__('Cookie Notice Text', 'aqualuxe'),
            'section' => 'aqualuxe_general',
            'type'    => 'textarea',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_cookie_notice_button_text',
        array(
            'default'           => esc_html__('Accept', 'aqualuxe'),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_cookie_notice_button_text',
        array(
            'label'   => esc_html__('Cookie Notice Button Text', 'aqualuxe'),
            'section' => 'aqualuxe_general',
            'type'    => 'text',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_cookie_notice_privacy_link',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_cookie_notice_privacy_link',
        array(
            'label'   => esc_html__('Privacy Policy Link', 'aqualuxe'),
            'section' => 'aqualuxe_general',
            'type'    => 'url',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_cookie_notice_privacy_text',
        array(
            'default'           => esc_html__('Privacy Policy', 'aqualuxe'),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_cookie_notice_privacy_text',
        array(
            'label'   => esc_html__('Privacy Policy Text', 'aqualuxe'),
            'section' => 'aqualuxe_general',
            'type'    => 'text',
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
            'section'     => 'aqualuxe_general',
            'type'        => 'text',
        )
    );

    // Schema.org Logo
    $wp_customize->add_setting(
        'aqualuxe_schema_logo',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_schema_logo',
        array(
            'label'       => esc_html__('Schema.org Logo URL', 'aqualuxe'),
            'description' => esc_html__('URL to the logo used in Schema.org structured data. If empty, the site icon will be used.', 'aqualuxe'),
            'section'     => 'aqualuxe_general',
            'type'        => 'url',
        )
    );

    // Twitter Username
    $wp_customize->add_setting(
        'aqualuxe_twitter_username',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_twitter_username',
        array(
            'label'       => esc_html__('Twitter Username', 'aqualuxe'),
            'description' => esc_html__('Enter your Twitter username without the @ symbol.', 'aqualuxe'),
            'section'     => 'aqualuxe_general',
            'type'        => 'text',
        )
    );
}
add_action('customize_register', 'aqualuxe_customize_register_general');