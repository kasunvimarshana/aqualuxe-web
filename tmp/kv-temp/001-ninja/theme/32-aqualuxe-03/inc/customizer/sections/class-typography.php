<?php
/**
 * AquaLuxe Typography Customizer Section
 *
 * @package AquaLuxe
 * @subpackage Customizer
 */

namespace AquaLuxe\Customizer\Sections;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Typography Customizer Section
 */
class Typography {

	/**
	 * Constructor
	 */
	public function __construct( $wp_customize ) {
		$this->register_typography_section( $wp_customize );
	}

	/**
	 * Register Typography Section
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function register_typography_section( $wp_customize ) {
		// Typography Section.
		$wp_customize->add_section(
			'aqualuxe_typography_section',
			array(
				'title'    => __( 'Typography', 'aqualuxe' ),
				'priority' => 40,
				'panel'    => 'aqualuxe_theme_options',
			)
		);

		// Body Font Family.
		$wp_customize->add_setting(
			'aqualuxe_body_font_family',
			array(
				'default'           => 'Roboto',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_body_font_family',
			array(
				'label'    => __( 'Body Font Family', 'aqualuxe' ),
				'section'  => 'aqualuxe_typography_section',
				'type'     => 'select',
				'choices'  => array(
					'Arial'           => 'Arial',
					'Helvetica'       => 'Helvetica',
					'Georgia'         => 'Georgia',
					'Roboto'          => 'Roboto',
					'Lato'            => 'Lato',
					'Open Sans'       => 'Open Sans',
					'Montserrat'      => 'Montserrat',
					'Raleway'         => 'Raleway',
					'Playfair Display' => 'Playfair Display',
					'Merriweather'    => 'Merriweather',
				),
				'priority' => 10,
			)
		);

		// Heading Font Family.
		$wp_customize->add_setting(
			'aqualuxe_heading_font_family',
			array(
				'default'           => 'Montserrat',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_heading_font_family',
			array(
				'label'    => __( 'Heading Font Family', 'aqualuxe' ),
				'section'  => 'aqualuxe_typography_section',
				'type'     => 'select',
				'choices'  => array(
					'Arial'           => 'Arial',
					'Helvetica'       => 'Helvetica',
					'Georgia'         => 'Georgia',
					'Roboto'          => 'Roboto',
					'Lato'            => 'Lato',
					'Open Sans'       => 'Open Sans',
					'Montserrat'      => 'Montserrat',
					'Raleway'         => 'Raleway',
					'Playfair Display' => 'Playfair Display',
					'Merriweather'    => 'Merriweather',
				),
				'priority' => 20,
			)
		);

		// Body Font Size.
		$wp_customize->add_setting(
			'aqualuxe_body_font_size',
			array(
				'default'           => '16',
				'sanitize_callback' => 'absint',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_body_font_size',
			array(
				'label'       => __( 'Body Font Size (px)', 'aqualuxe' ),
				'section'     => 'aqualuxe_typography_section',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 12,
					'max'  => 24,
					'step' => 1,
				),
				'priority'    => 30,
			)
		);

		// Line Height.
		$wp_customize->add_setting(
			'aqualuxe_line_height',
			array(
				'default'           => '1.6',
				'sanitize_callback' => 'aqualuxe_sanitize_float',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_line_height',
			array(
				'label'       => __( 'Line Height', 'aqualuxe' ),
				'section'     => 'aqualuxe_typography_section',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 1,
					'max'  => 2,
					'step' => 0.1,
				),
				'priority'    => 40,
			)
		);

		// Heading Line Height.
		$wp_customize->add_setting(
			'aqualuxe_heading_line_height',
			array(
				'default'           => '1.2',
				'sanitize_callback' => 'aqualuxe_sanitize_float',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_heading_line_height',
			array(
				'label'       => __( 'Heading Line Height', 'aqualuxe' ),
				'section'     => 'aqualuxe_typography_section',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 1,
					'max'  => 2,
					'step' => 0.1,
				),
				'priority'    => 50,
			)
		);

		// Font Weight.
		$wp_customize->add_setting(
			'aqualuxe_body_font_weight',
			array(
				'default'           => '400',
				'sanitize_callback' => 'absint',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_body_font_weight',
			array(
				'label'    => __( 'Body Font Weight', 'aqualuxe' ),
				'section'  => 'aqualuxe_typography_section',
				'type'     => 'select',
				'choices'  => array(
					'300' => __( 'Light (300)', 'aqualuxe' ),
					'400' => __( 'Regular (400)', 'aqualuxe' ),
					'500' => __( 'Medium (500)', 'aqualuxe' ),
					'600' => __( 'Semi-Bold (600)', 'aqualuxe' ),
					'700' => __( 'Bold (700)', 'aqualuxe' ),
				),
				'priority' => 60,
			)
		);

		// Heading Font Weight.
		$wp_customize->add_setting(
			'aqualuxe_heading_font_weight',
			array(
				'default'           => '700',
				'sanitize_callback' => 'absint',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_heading_font_weight',
			array(
				'label'    => __( 'Heading Font Weight', 'aqualuxe' ),
				'section'  => 'aqualuxe_typography_section',
				'type'     => 'select',
				'choices'  => array(
					'300' => __( 'Light (300)', 'aqualuxe' ),
					'400' => __( 'Regular (400)', 'aqualuxe' ),
					'500' => __( 'Medium (500)', 'aqualuxe' ),
					'600' => __( 'Semi-Bold (600)', 'aqualuxe' ),
					'700' => __( 'Bold (700)', 'aqualuxe' ),
				),
				'priority' => 70,
			)
		);
	}
}