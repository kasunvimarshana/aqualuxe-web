<?php
/**
 * AquaLuxe Theme Customizer - Integrations Settings
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add integrations settings to the Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_integrations_settings($wp_customize) {
    // Integrations Section
    $wp_customize->add_section(
        'aqualuxe_integrations_section',
        array(
            'title'       => __('Integrations', 'aqualuxe'),
            'description' => __('Configure third-party integrations and services.', 'aqualuxe'),
            'priority'    => 70,
        )
    );

    // Google Analytics Heading
    $wp_customize->add_setting(
        'aqualuxe_analytics_heading',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Customize_Heading_Control(
            $wp_customize,
            'aqualuxe_analytics_heading',
            array(
                'label'   => __('Analytics', 'aqualuxe'),
                'section' => 'aqualuxe_integrations_section',
            )
        )
    );

    // Google Analytics ID
    $wp_customize->add_setting(
        'aqualuxe_options[google_analytics_id]',
        array(
            'default'           => '',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[google_analytics_id]',
        array(
            'label'       => __('Google Analytics ID', 'aqualuxe'),
            'description' => __('Enter your Google Analytics ID (e.g., G-XXXXXXXXXX or UA-XXXXXXXX-X).', 'aqualuxe'),
            'section'     => 'aqualuxe_integrations_section',
            'type'        => 'text',
            'input_attrs' => array(
                'placeholder' => 'G-XXXXXXXXXX',
            ),
        )
    );

    // Google Analytics 4 Mode
    $wp_customize->add_setting(
        'aqualuxe_options[google_analytics_v4]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[google_analytics_v4]',
        array(
            'label'       => __('Use Google Analytics 4', 'aqualuxe'),
            'description' => __('Enable if using Google Analytics 4 (G-XXXXXXXXXX format).', 'aqualuxe'),
            'section'     => 'aqualuxe_integrations_section',
            'type'        => 'checkbox',
        )
    );

    // Facebook Pixel ID
    $wp_customize->add_setting(
        'aqualuxe_options[facebook_pixel_id]',
        array(
            'default'           => '',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[facebook_pixel_id]',
        array(
            'label'       => __('Facebook Pixel ID', 'aqualuxe'),
            'description' => __('Enter your Facebook Pixel ID.', 'aqualuxe'),
            'section'     => 'aqualuxe_integrations_section',
            'type'        => 'text',
            'input_attrs' => array(
                'placeholder' => 'XXXXXXXXXXXXXXXXXX',
            ),
        )
    );

    // Hotjar ID
    $wp_customize->add_setting(
        'aqualuxe_options[hotjar_id]',
        array(
            'default'           => '',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[hotjar_id]',
        array(
            'label'       => __('Hotjar Site ID', 'aqualuxe'),
            'description' => __('Enter your Hotjar Site ID.', 'aqualuxe'),
            'section'     => 'aqualuxe_integrations_section',
            'type'        => 'text',
            'input_attrs' => array(
                'placeholder' => '1234567',
            ),
        )
    );

    // Maps Heading
    $wp_customize->add_setting(
        'aqualuxe_maps_heading',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Customize_Heading_Control(
            $wp_customize,
            'aqualuxe_maps_heading',
            array(
                'label'   => __('Maps', 'aqualuxe'),
                'section' => 'aqualuxe_integrations_section',
            )
        )
    );

    // Google Maps API Key
    $wp_customize->add_setting(
        'aqualuxe_options[google_maps_api_key]',
        array(
            'default'           => '',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[google_maps_api_key]',
        array(
            'label'       => __('Google Maps API Key', 'aqualuxe'),
            'description' => __('Enter your Google Maps API Key for map features.', 'aqualuxe'),
            'section'     => 'aqualuxe_integrations_section',
            'type'        => 'text',
        )
    );

    // Google Maps Style
    $wp_customize->add_setting(
        'aqualuxe_options[google_maps_style]',
        array(
            'default'           => 'default',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[google_maps_style]',
        array(
            'label'       => __('Google Maps Style', 'aqualuxe'),
            'description' => __('Choose a style for Google Maps.', 'aqualuxe'),
            'section'     => 'aqualuxe_integrations_section',
            'type'        => 'select',
            'choices'     => array(
                'default'   => __('Default', 'aqualuxe'),
                'silver'    => __('Silver', 'aqualuxe'),
                'retro'     => __('Retro', 'aqualuxe'),
                'dark'      => __('Dark', 'aqualuxe'),
                'night'     => __('Night', 'aqualuxe'),
                'aubergine' => __('Aubergine', 'aqualuxe'),
                'custom'    => __('Custom JSON', 'aqualuxe'),
            ),
        )
    );

    // Custom Google Maps Style JSON
    $wp_customize->add_setting(
        'aqualuxe_options[google_maps_custom_style]',
        array(
            'default'           => '',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_json',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[google_maps_custom_style]',
        array(
            'label'       => __('Custom Maps Style JSON', 'aqualuxe'),
            'description' => __('Enter custom Google Maps style JSON. Get styles from <a href="https://mapstyle.withgoogle.com/" target="_blank">Google Maps Styling Wizard</a> or <a href="https://snazzymaps.com/" target="_blank">Snazzy Maps</a>.', 'aqualuxe'),
            'section'     => 'aqualuxe_integrations_section',
            'type'        => 'textarea',
            'active_callback' => function() {
                $options = get_option('aqualuxe_options', array());
                return isset($options['google_maps_style']) && $options['google_maps_style'] === 'custom';
            },
        )
    );

    // reCAPTCHA Heading
    $wp_customize->add_setting(
        'aqualuxe_recaptcha_heading',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Customize_Heading_Control(
            $wp_customize,
            'aqualuxe_recaptcha_heading',
            array(
                'label'   => __('reCAPTCHA', 'aqualuxe'),
                'section' => 'aqualuxe_integrations_section',
            )
        )
    );

    // Enable reCAPTCHA
    $wp_customize->add_setting(
        'aqualuxe_options[enable_recaptcha]',
        array(
            'default'           => false,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[enable_recaptcha]',
        array(
            'label'       => __('Enable Google reCAPTCHA', 'aqualuxe'),
            'description' => __('Add reCAPTCHA protection to forms.', 'aqualuxe'),
            'section'     => 'aqualuxe_integrations_section',
            'type'        => 'checkbox',
        )
    );

    // reCAPTCHA Version
    $wp_customize->add_setting(
        'aqualuxe_options[recaptcha_version]',
        array(
            'default'           => 'v3',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[recaptcha_version]',
        array(
            'label'       => __('reCAPTCHA Version', 'aqualuxe'),
            'description' => __('Select which version of reCAPTCHA to use.', 'aqualuxe'),
            'section'     => 'aqualuxe_integrations_section',
            'type'        => 'select',
            'choices'     => array(
                'v2_checkbox' => __('v2 Checkbox', 'aqualuxe'),
                'v2_invisible' => __('v2 Invisible', 'aqualuxe'),
                'v3'          => __('v3', 'aqualuxe'),
            ),
            'active_callback' => function() {
                $options = get_option('aqualuxe_options', array());
                return isset($options['enable_recaptcha']) ? $options['enable_recaptcha'] : false;
            },
        )
    );

    // reCAPTCHA Site Key
    $wp_customize->add_setting(
        'aqualuxe_options[recaptcha_site_key]',
        array(
            'default'           => '',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[recaptcha_site_key]',
        array(
            'label'       => __('reCAPTCHA Site Key', 'aqualuxe'),
            'description' => __('Enter your reCAPTCHA Site Key.', 'aqualuxe'),
            'section'     => 'aqualuxe_integrations_section',
            'type'        => 'text',
            'active_callback' => function() {
                $options = get_option('aqualuxe_options', array());
                return isset($options['enable_recaptcha']) ? $options['enable_recaptcha'] : false;
            },
        )
    );

    // reCAPTCHA Secret Key
    $wp_customize->add_setting(
        'aqualuxe_options[recaptcha_secret_key]',
        array(
            'default'           => '',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[recaptcha_secret_key]',
        array(
            'label'       => __('reCAPTCHA Secret Key', 'aqualuxe'),
            'description' => __('Enter your reCAPTCHA Secret Key.', 'aqualuxe'),
            'section'     => 'aqualuxe_integrations_section',
            'type'        => 'text',
            'active_callback' => function() {
                $options = get_option('aqualuxe_options', array());
                return isset($options['enable_recaptcha']) ? $options['enable_recaptcha'] : false;
            },
        )
    );

    // Custom Code Heading
    $wp_customize->add_setting(
        'aqualuxe_custom_code_heading',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Customize_Heading_Control(
            $wp_customize,
            'aqualuxe_custom_code_heading',
            array(
                'label'   => __('Custom Code', 'aqualuxe'),
                'section' => 'aqualuxe_integrations_section',
            )
        )
    );

    // Header Code
    $wp_customize->add_setting(
        'aqualuxe_options[header_code]',
        array(
            'default'           => '',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_html',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[header_code]',
        array(
            'label'       => __('Header Code', 'aqualuxe'),
            'description' => __('Add custom code to the &lt;head&gt; section.', 'aqualuxe'),
            'section'     => 'aqualuxe_integrations_section',
            'type'        => 'textarea',
        )
    );

    // Footer Code
    $wp_customize->add_setting(
        'aqualuxe_options[footer_code]',
        array(
            'default'           => '',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_html',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[footer_code]',
        array(
            'label'       => __('Footer Code', 'aqualuxe'),
            'description' => __('Add custom code before the closing &lt;/body&gt; tag.', 'aqualuxe'),
            'section'     => 'aqualuxe_integrations_section',
            'type'        => 'textarea',
        )
    );

    // Custom CSS
    $wp_customize->add_setting(
        'aqualuxe_custom_css',
        array(
            'default'           => '',
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_strip_all_tags',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_custom_css',
        array(
            'label'       => __('Custom CSS', 'aqualuxe'),
            'description' => __('Add custom CSS styles.', 'aqualuxe'),
            'section'     => 'aqualuxe_integrations_section',
            'type'        => 'textarea',
        )
    );
}
add_action('customize_register', 'aqualuxe_customize_integrations_settings');

/**
 * Sanitize JSON input
 *
 * @param string $input JSON string to sanitize
 * @return string Sanitized JSON string
 */
function aqualuxe_sanitize_json($input) {
    if (empty($input)) {
        return '';
    }
    
    // Attempt to decode the JSON
    $decoded = json_decode($input);
    
    // Check if the JSON is valid
    if (json_last_error() !== JSON_ERROR_NONE) {
        return '';
    }
    
    // Re-encode to ensure proper formatting
    return json_encode($decoded);
}

/**
 * Sanitize HTML input
 *
 * @param string $input HTML string to sanitize
 * @return string Sanitized HTML string
 */
function aqualuxe_sanitize_html($input) {
    // Allow certain HTML tags and attributes
    return wp_kses($input, array(
        'script' => array(
            'type' => array(),
            'src' => array(),
            'async' => array(),
            'defer' => array(),
        ),
        'style' => array(
            'type' => array(),
        ),
        'link' => array(
            'rel' => array(),
            'href' => array(),
            'type' => array(),
        ),
        'meta' => array(
            'name' => array(),
            'content' => array(),
            'property' => array(),
        ),
        'div' => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'span' => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'a' => array(
            'href' => array(),
            'id' => array(),
            'class' => array(),
            'target' => array(),
            'rel' => array(),
        ),
        'img' => array(
            'src' => array(),
            'alt' => array(),
            'id' => array(),
            'class' => array(),
            'width' => array(),
            'height' => array(),
        ),
        'noscript' => array(),
    ));
}