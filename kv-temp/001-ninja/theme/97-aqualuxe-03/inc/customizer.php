<?php
/**
 * Theme Customizer
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds Customizer options.
 */
class AquaLuxe_Customizer {

	/**
	 * Register hooks.
	 */
	public function register() {
		add_action( 'customize_register', array( $this, 'add_customizer_options' ) );
	}

	/**
	 * Add Customizer options.
	 *
	 * @param WP_Customize_Manager $wp_customize The Customizer object.
	 */
	public function add_customizer_options( $wp_customize ) {
		// Panel
		$wp_customize->add_panel(
			'aqualuxe_theme_options',
			array(
				'title'       => __( 'AquaLuxe Theme Options', 'aqualuxe' ),
				'description' => __( 'Theme options for AquaLuxe', 'aqualuxe' ),
				'priority'    => 10,
			)
		);

		// Sections
		$this->add_layout_section( $wp_customize );
		$this->add_color_section( $wp_customize );
		$this->add_typography_section( $wp_customize );

		// Example setting for logo (WordPress handles this by default, but as an example)
		$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
		$wp_customize->get_control( 'custom_logo' )->section = 'title_tagline';
	}

	/**
	 * Add layout section.
	 *
	 * @param WP_Customize_Manager $wp_customize The Customizer object.
	 */
	private function add_layout_section( $wp_customize ) {
		$wp_customize->add_section(
			'aqualuxe_layout_section',
			array(
				'title' => __( 'Layout', 'aqualuxe' ),
				'panel' => 'aqualuxe_theme_options',
			)
		);

		// Example setting
		$wp_customize->add_setting(
			'aqualuxe_container_width',
			array(
				'default'           => '1200px',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_container_width',
			array(
				'label'   => __( 'Container Width', 'aqualuxe' ),
				'section' => 'aqualuxe_layout_section',
				'type'    => 'text',
			)
		);
	}

	/**
	 * Add color section.
	 *
	 * @param WP_Customize_Manager $wp_customize The Customizer object.
	 */
	private function add_color_section( $wp_customize ) {
		$wp_customize->add_section(
			'aqualuxe_color_section',
			array(
				'title' => __( 'Colors', 'aqualuxe' ),
				'panel' => 'aqualuxe_theme_options',
			)
		);

		// Primary Color
		$wp_customize->add_setting(
			'aqualuxe_primary_color',
			array(
				'default'           => '#0073aa',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_primary_color',
				array(
					'label'   => __( 'Primary Color', 'aqualuxe' ),
					'section' => 'aqualuxe_color_section',
				)
			)
		);
	}

	/**
	 * Add typography section.
	 *
	 * @param WP_Customize_Manager $wp_customize The Customizer object.
	 */
	private function add_typography_section( $wp_customize ) {
		$wp_customize->add_section(
			'aqualuxe_typography_section',
			array(
				'title' => __( 'Typography', 'aqualuxe' ),
				'panel' => 'aqualuxe_theme_options',
			)
		);

		// Body Font
		$wp_customize->add_setting(
			'aqualuxe_body_font',
			array(
				'default'           => 'sans-serif',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_body_font',
			array(
				'label'   => __( 'Body Font', 'aqualuxe' ),
				'section' => 'aqualuxe_typography_section',
				'type'    => 'text',
			)
		);
	}
}
