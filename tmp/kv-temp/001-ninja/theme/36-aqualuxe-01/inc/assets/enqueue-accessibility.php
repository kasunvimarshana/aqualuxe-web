<?php
/**
 * Enqueue Accessibility Assets
 *
 * Enqueues accessibility-related scripts and styles.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue accessibility scripts and styles.
 */
function aqualuxe_enqueue_accessibility_assets() {
	// Enqueue accessibility CSS.
	wp_enqueue_style(
		'aqualuxe-accessibility',
		get_template_directory_uri() . '/assets/css/accessibility.css',
		array(),
		AQUALUXE_VERSION
	);

	// Enqueue skip link focus fix script.
	wp_enqueue_script(
		'aqualuxe-skip-link-focus-fix',
		get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js',
		array(),
		AQUALUXE_VERSION,
		true
	);

	// Enqueue dropdown keyboard navigation script.
	wp_enqueue_script(
		'aqualuxe-dropdown-keyboard-navigation',
		get_template_directory_uri() . '/assets/js/dropdown-keyboard-navigation.js',
		array( 'jquery' ),
		AQUALUXE_VERSION,
		true
	);

	// Conditionally enqueue accessibility toolbar script.
	if ( get_theme_mod( 'aqualuxe_accessibility_toolbar', false ) ) {
		wp_enqueue_script(
			'aqualuxe-accessibility-toolbar',
			get_template_directory_uri() . '/assets/js/accessibility-toolbar.js',
			array( 'jquery' ),
			AQUALUXE_VERSION,
			true
		);

		// Add a variable to indicate that the accessibility toolbar is enabled.
		wp_localize_script(
			'aqualuxe-accessibility-toolbar',
			'aqualuxeAccessibilityToolbar',
			array(
				'enabled' => true,
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_enqueue_accessibility_assets' );

/**
 * Add accessibility settings to the customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_accessibility_assets_customizer( $wp_customize ) {
	// Add accessibility toolbar setting.
	$wp_customize->add_setting(
		'aqualuxe_accessibility_toolbar',
		array(
			'default'           => false,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_accessibility_toolbar',
		array(
			'label'       => __( 'Enable accessibility toolbar', 'aqualuxe' ),
			'description' => __( 'Add an accessibility toolbar with high contrast mode, font size adjustment, and reduced motion options.', 'aqualuxe' ),
			'section'     => 'aqualuxe_accessibility',
			'type'        => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'aqualuxe_accessibility_assets_customizer' );