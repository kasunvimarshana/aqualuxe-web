<?php

/**
 * AquaLuxe Customizer Options
 *
 * @package aqualuxe
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Add customizer options
 */
function aqualuxe_customizer_options($wp_customize)
{
    // Add a new section
    $wp_customize->add_section('aqualuxe_theme_options', array(
        'title'    => __('Theme Options', 'aqualuxe'),
        'priority' => 130,
    ));

    // Add setting for primary color
    $wp_customize->add_setting('aqualuxe_primary_color', array(
        'default'           => '#006994',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    // Add control for primary color
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', array(
        'label'    => __('Primary Color', 'aqualuxe'),
        'section'  => 'aqualuxe_theme_options',
        'settings' => 'aqualuxe_primary_color',
    )));

    // Add setting for secondary color
    $wp_customize->add_setting('aqualuxe_secondary_color', array(
        'default'           => '#00a8cc',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    // Add control for secondary color
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', array(
        'label'    => __('Secondary Color', 'aqualuxe'),
        'section'  => 'aqualuxe_theme_options',
        'settings' => 'aqualuxe_secondary_color',
    )));

    // Add setting for accent color
    $wp_customize->add_setting('aqualuxe_accent_color', array(
        'default'           => '#ffd166',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    // Add control for accent color
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', array(
        'label'    => __('Accent Color', 'aqualuxe'),
        'section'  => 'aqualuxe_theme_options',
        'settings' => 'aqualuxe_accent_color',
    )));

    // aqualuxe_customizer_options function

    // Hero Section
    $wp_customize->add_section('aqualuxe_hero_section', array(
        'title'    => __('Hero Section', 'aqualuxe'),
        'priority' => 120,
    ));

    $wp_customize->add_setting('aqualuxe_hero_title', array(
        'default'           => __('Discover the Beauty of Ornamental Fish', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_hero_title', array(
        'label'    => __('Hero Title', 'aqualuxe'),
        'section'  => 'aqualuxe_hero_section',
        'settings' => 'aqualuxe_hero_title',
        'type'     => 'text',
    ));

    $wp_customize->add_setting('aqualuxe_hero_subtitle', array(
        'default'           => __('Premium quality ornamental fish for enthusiasts and collectors', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_hero_subtitle', array(
        'label'    => __('Hero Subtitle', 'aqualuxe'),
        'section'  => 'aqualuxe_hero_section',
        'settings' => 'aqualuxe_hero_subtitle',
        'type'     => 'text',
    ));

    $wp_customize->add_setting('aqualuxe_hero_image', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aqualuxe_hero_image', array(
        'label'    => __('Hero Image', 'aqualuxe'),
        'section'  => 'aqualuxe_hero_section',
        'settings' => 'aqualuxe_hero_image',
        'mime_type' => 'image',
    )));

    // About Section
    $wp_customize->add_section('aqualuxe_about_section', array(
        'title'    => __('About Section', 'aqualuxe'),
        'priority' => 125,
    ));

    $wp_customize->add_setting('aqualuxe_about_text', array(
        'default'           => __('We are passionate breeders of premium ornamental fish, dedicated to providing the highest quality specimens to enthusiasts and collectors worldwide.', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_about_text', array(
        'label'    => __('About Text', 'aqualuxe'),
        'section'  => 'aqualuxe_about_section',
        'settings' => 'aqualuxe_about_text',
        'type'     => 'textarea',
    ));

    $wp_customize->add_setting('aqualuxe_about_image', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aqualuxe_about_image', array(
        'label'    => __('About Image', 'aqualuxe'),
        'section'  => 'aqualuxe_about_section',
        'settings' => 'aqualuxe_about_image',
        'mime_type' => 'image',
    )));

    // Contact Information
    $wp_customize->add_section('aqualuxe_contact_section', array(
        'title'    => __('Contact Information', 'aqualuxe'),
        'priority' => 130,
    ));

    $wp_customize->add_setting('aqualuxe_address', array(
        'default'           => __('123 Aqua Lane, Ocean City, OC 12345', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_address', array(
        'label'    => __('Address', 'aqualuxe'),
        'section'  => 'aqualuxe_contact_section',
        'settings' => 'aqualuxe_address',
        'type'     => 'text',
    ));

    $wp_customize->add_setting('aqualuxe_phone', array(
        'default'           => __('+1 (555) 123-4567', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_phone', array(
        'label'    => __('Phone', 'aqualuxe'),
        'section'  => 'aqualuxe_contact_section',
        'settings' => 'aqualuxe_phone',
        'type'     => 'text',
    ));

    $wp_customize->add_setting('aqualuxe_email', array(
        'default'           => __('info@aqualuxe.com', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_email',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_email', array(
        'label'    => __('Email', 'aqualuxe'),
        'section'  => 'aqualuxe_contact_section',
        'settings' => 'aqualuxe_email',
        'type'     => 'email',
    ));

    $wp_customize->add_setting('aqualuxe_hours', array(
        'default'           => __('Monday - Friday: 9am - 5pm<br>Saturday: 10am - 4pm<br>Sunday: Closed', 'aqualuxe'),
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_hours', array(
        'label'    => __('Business Hours', 'aqualuxe'),
        'section'  => 'aqualuxe_contact_section',
        'settings' => 'aqualuxe_hours',
        'type'     => 'textarea',
    ));

    // Social Media
    $wp_customize->add_section('aqualuxe_social_section', array(
        'title'    => __('Social Media', 'aqualuxe'),
        'priority' => 135,
    ));

    $wp_customize->add_setting('aqualuxe_facebook', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_facebook', array(
        'label'    => __('Facebook URL', 'aqualuxe'),
        'section'  => 'aqualuxe_social_section',
        'settings' => 'aqualuxe_facebook',
        'type'     => 'url',
    ));

    $wp_customize->add_setting('aqualuxe_twitter', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_twitter', array(
        'label'    => __('Twitter URL', 'aqualuxe'),
        'section'  => 'aqualuxe_social_section',
        'settings' => 'aqualuxe_twitter',
        'type'     => 'url',
    ));

    $wp_customize->add_setting('aqualuxe_instagram', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_instagram', array(
        'label'    => __('Instagram URL', 'aqualuxe'),
        'section'  => 'aqualuxe_social_section',
        'settings' => 'aqualuxe_instagram',
        'type'     => 'url',
    ));

    $wp_customize->add_setting('aqualuxe_youtube', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_youtube', array(
        'label'    => __('YouTube URL', 'aqualuxe'),
        'section'  => 'aqualuxe_social_section',
        'settings' => 'aqualuxe_youtube',
        'type'     => 'url',
    ));
}
add_action('customize_register', 'aqualuxe_customizer_options');
