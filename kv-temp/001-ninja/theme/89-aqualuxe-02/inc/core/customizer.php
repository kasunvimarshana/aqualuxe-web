<?php
/**
 * Theme Customizer
 *
 * @package AquaLuxe\Core
 */

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'customize_register', function ( $wp_customize ) {
	// Panel
	$wp_customize->add_panel( 'alx_theme_options', [
		'title'       => __( 'AquaLuxe Theme Options', 'aqualuxe' ),
		'description' => __( 'Customize brand and layout settings.', 'aqualuxe' ),
		'priority'    => 10,
	] );

	// Section: Branding
	$wp_customize->add_section( 'alx_branding', [
		'panel' => 'alx_theme_options',
		'title' => __( 'Branding', 'aqualuxe' ),
	] );

	$wp_customize->add_setting( 'alx_primary_color', [
		'default'           => '#0ea5e9',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	] );
	$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'alx_primary_color', [
		'label'    => __( 'Primary Color', 'aqualuxe' ),
		'section'  => 'alx_branding',
		'settings' => 'alx_primary_color',
	] ) );

	$wp_customize->add_setting( 'alx_accent_color', [
		'default'           => '#b45309',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	] );
	$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'alx_accent_color', [
		'label'    => __( 'Accent Color', 'aqualuxe' ),
		'section'  => 'alx_branding',
		'settings' => 'alx_accent_color',
	] ) );

	// Section: Layout
	$wp_customize->add_section( 'alx_layout', [
		'panel' => 'alx_theme_options',
		'title' => __( 'Layout', 'aqualuxe' ),
	] );

	$wp_customize->add_setting( 'alx_container_width', [
		'default'           => 1200,
		'sanitize_callback' => 'absint',
	] );
	$wp_customize->add_control( 'alx_container_width', [
		'label'       => __( 'Container Max Width (px)', 'aqualuxe' ),
		'section'     => 'alx_layout',
		'settings'    => 'alx_container_width',
		'type'        => 'number',
		'input_attrs' => [ 'min' => 960, 'max' => 1600, 'step' => 10 ],
	] );
} );

// Expose settings to front-end CSS vars.
add_action( 'wp_head', function () {
	$primary = get_theme_mod( 'alx_primary_color', '#0ea5e9' );
	$accent  = get_theme_mod( 'alx_accent_color', '#b45309' );
	$width   = (int) get_theme_mod( 'alx_container_width', 1200 );
	echo '<style id="alx-customizer-vars">:root{--alx-color-primary:' . esc_attr( $primary ) . ';--alx-color-accent:' . esc_attr( $accent ) . ';}.alx-container{max-width:' . esc_attr( $width ) . 'px}</style>';
}, 20 );
