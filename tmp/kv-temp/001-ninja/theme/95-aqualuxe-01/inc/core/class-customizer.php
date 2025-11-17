<?php
/**
 * Theme Customizer.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles Theme Customizer settings.
 */
class AquaLuxe_Customizer {

	/**
	 * Register customizer settings.
	 *
	 * @param WP_Customize_Manager $wp_customize The Customizer object.
	 */
	public static function register( $wp_customize ) {
		// Panel
		$wp_customize->add_panel( 'aqualuxe_theme_options', array(
			'title'    => __( 'AquaLuxe Theme Options', 'aqualuxe' ),
			'priority' => 10,
		) );

		// Sections
		self::add_colors_section( $wp_customize );
		self::add_typography_section( $wp_customize );
	}

	/**
	 * Add Colors section.
	 *
	 * @param WP_Customize_Manager $wp_customize The Customizer object.
	 */
	private static function add_colors_section( $wp_customize ) {
		$wp_customize->add_section( 'aqualuxe_colors', array(
			'title' => __( 'Colors', 'aqualuxe' ),
			'panel' => 'aqualuxe_theme_options',
		) );

		$colors = array(
			'primary_color'   => array(
				'label'   => __( 'Primary Color', 'aqualuxe' ),
				'default' => '#005f73',
			),
			'secondary_color' => array(
				'label'   => __( 'Secondary Color', 'aqualuxe' ),
				'default' => '#0a9396',
			),
		);

		foreach ( $colors as $slug => $options ) {
			$wp_customize->add_setting( $slug, array(
				'default'           => $options['default'],
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $slug, array(
				'label'    => $options['label'],
				'section'  => 'aqualuxe_colors',
				'settings' => $slug,
			) ) );
		}
	}

	/**
	 * Add Typography section.
	 *
	 * @param WP_Customize_Manager $wp_customize The Customizer object.
	 */
	private static function add_typography_section( $wp_customize ) {
		$wp_customize->add_section( 'aqualuxe_typography', array(
			'title' => __( 'Typography', 'aqualuxe' ),
			'panel' => 'aqualuxe_theme_options',
		) );

		// Add settings for font choices, sizes, etc.
	}
}
