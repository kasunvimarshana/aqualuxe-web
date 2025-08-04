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
}
add_action('customize_register', 'aqualuxe_customizer_options');
