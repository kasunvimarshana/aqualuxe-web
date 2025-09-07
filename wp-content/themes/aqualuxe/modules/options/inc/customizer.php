<?php
/**
 * Aqualuxe Theme Customizer
 *
 * @package Aqualuxe
 * @subpackage Modules/Options
 */

namespace Aqualuxe\Modules\Options;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param \WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register( \WP_Customize_Manager $wp_customize ) {
	// Load Customizer sanitization callbacks.
	require_once __DIR__ . '/callbacks.php';

	// --- Aqualuxe Theme Options Panel ---
	$wp_customize->add_panel(
		'aqualuxe_theme_options_panel',
		array(
			'priority'   => 10,
			'capability' => 'edit_theme_options',
			'title'      => __( 'Aqualuxe Options', 'aqualuxe' ),
			'description' => __( 'Configure options for the Aqualuxe theme.', 'aqualuxe' ),
		)
	);

	// --- General Settings Section ---
	$wp_customize->add_section(
		'aqualuxe_general_section',
		array(
			'title'    => __( 'General Settings', 'aqualuxe' ),
			'panel'    => 'aqualuxe_theme_options_panel',
			'priority' => 10,
		)
	);

	// Logo Setting
	$wp_customize->add_setting(
		'aqualuxe_options[logo]',
		array(
			'default'           => '',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'Aqualuxe\Modules\Options\sanitize_image',
		)
	);
	$wp_customize->add_control(
		new \WP_Customize_Image_Control(
			$wp_customize,
			'aqualuxe_logo',
			array(
				'label'    => __( 'Logo', 'aqualuxe' ),
				'section'  => 'aqualuxe_general_section',
				'settings' => 'aqualuxe_options[logo]',
			)
		)
	);

	// --- Styling Section ---
	$wp_customize->add_section(
		'aqualuxe_styling_section',
		array(
			'title'    => __( 'Styling', 'aqualuxe' ),
			'panel'    => 'aqualuxe_theme_options_panel',
			'priority' => 20,
		)
	);

	// Primary Color Setting
	$wp_customize->add_setting(
		'aqualuxe_options[primary_color]',
		array(
			'default'           => '#0073aa',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new \WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_primary_color',
			array(
				'label'    => __( 'Primary Color', 'aqualuxe' ),
				'section'  => 'aqualuxe_styling_section',
				'settings' => 'aqualuxe_options[primary_color]',
			)
		)
	);

	// --- Footer Section ---
	$wp_customize->add_section(
		'aqualuxe_footer_section',
		array(
			'title'    => __( 'Footer', 'aqualuxe' ),
			'panel'    => 'aqualuxe_theme_options_panel',
			'priority' => 30,
		)
	);

	// Footer Copyright Text
	$wp_customize->add_setting(
		'aqualuxe_options[footer_copyright]',
		array(
			'default'           => sprintf( 'Copyright &copy; %s %s', date( 'Y' ), get_bloginfo( 'name' ) ),
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'wp_kses_post',
		)
	);
	$wp_customize->add_control(
		'aqualuxe_footer_copyright',
		array(
			'label'    => __( 'Footer Copyright Text', 'aqualuxe' ),
			'section'  => 'aqualuxe_footer_section',
			'type'     => 'textarea',
			'settings' => 'aqualuxe_options[footer_copyright]',
		)
	);
}
add_action( 'customize_register', __NAMESPACE__ . '\aqualuxe_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
	// This would be where you enqueue a JS file for live previewing changes.
	// For now, we will rely on the default refresh method.
}
add_action( 'customize_preview_init', __NAMESPACE__ . '\aqualuxe_customize_preview_js' );
