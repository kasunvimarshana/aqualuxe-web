<?php
/** Theme Customizer settings */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'customize_register', function( $wp_customize ) {
	// Colors section.
	$wp_customize->add_section( 'aqlx_branding', [ 'title' => __( 'AquaLuxe Branding', 'aqualuxe' ) ] );

	$wp_customize->add_setting( 'aqlx_accent_color', [ 'default' => '#00b4d8', 'sanitize_callback' => 'sanitize_hex_color' ] );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqlx_accent_color', [
		'label' => __( 'Accent Color', 'aqualuxe' ),
		'section' => 'aqlx_branding'
	] ) );

	$wp_customize->add_setting( 'aqlx_enable_dark_mode', [ 'default' => true, 'sanitize_callback' => 'rest_sanitize_boolean' ] );
	$wp_customize->add_control( 'aqlx_enable_dark_mode', [
		'label' => __( 'Enable Dark Mode Toggle', 'aqualuxe' ),
		'section' => 'aqlx_branding',
		'type' => 'checkbox'
	] );
} );
