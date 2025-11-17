<?php
/**
 * AquaLuxe Theme Customizer - Colors Settings
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Customizer\Sections;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Colors Settings
 */
class Colors {

	/**
	 * WP_Customize_Manager instance.
	 *
	 * @var WP_Customize_Manager
	 */
	private $wp_customize;

	/**
	 * Constructor.
	 *
	 * @param \WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 */
	public function __construct( $wp_customize ) {
		$this->wp_customize = $wp_customize;
		$this->register_sections();
		$this->register_settings();
	}

	/**
	 * Register customizer sections.
	 *
	 * @return void
	 */
	public function register_sections() {
		$this->wp_customize->add_section(
			'aqualuxe_colors',
			array(
				'title'    => __( 'Colors', 'aqualuxe' ),
				'priority' => 40,
			)
		);
	}

	/**
	 * Register customizer settings.
	 *
	 * @return void
	 */
	public function register_settings() {
		// Primary Color.
		$this->wp_customize->add_setting(
			'aqualuxe_primary_color',
			array(
				'default'           => '#0ea5e9',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$this->wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$this->wp_customize,
				'aqualuxe_primary_color',
				array(
					'label'    => __( 'Primary Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_primary_color',
				)
			)
		);

		// Secondary Color.
		$this->wp_customize->add_setting(
			'aqualuxe_secondary_color',
			array(
				'default'           => '#14b8a6',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$this->wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$this->wp_customize,
				'aqualuxe_secondary_color',
				array(
					'label'    => __( 'Secondary Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_secondary_color',
				)
			)
		);

		// Accent Color.
		$this->wp_customize->add_setting(
			'aqualuxe_accent_color',
			array(
				'default'           => '#f97316',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$this->wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$this->wp_customize,
				'aqualuxe_accent_color',
				array(
					'label'    => __( 'Accent Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_accent_color',
				)
			)
		);

		// Text Color.
		$this->wp_customize->add_setting(
			'aqualuxe_text_color',
			array(
				'default'           => '#374151',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$this->wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$this->wp_customize,
				'aqualuxe_text_color',
				array(
					'label'    => __( 'Text Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_text_color',
				)
			)
		);

		// Heading Color.
		$this->wp_customize->add_setting(
			'aqualuxe_heading_color',
			array(
				'default'           => '#1f2937',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$this->wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$this->wp_customize,
				'aqualuxe_heading_color',
				array(
					'label'    => __( 'Heading Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_heading_color',
				)
			)
		);

		// Background Color.
		$this->wp_customize->add_setting(
			'aqualuxe_background_color',
			array(
				'default'           => '#ffffff',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$this->wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$this->wp_customize,
				'aqualuxe_background_color',
				array(
					'label'    => __( 'Background Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_background_color',
				)
			)
		);

		// Link Color.
		$this->wp_customize->add_setting(
			'aqualuxe_link_color',
			array(
				'default'           => '#0ea5e9',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$this->wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$this->wp_customize,
				'aqualuxe_link_color',
				array(
					'label'    => __( 'Link Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_link_color',
				)
			)
		);

		// Link Hover Color.
		$this->wp_customize->add_setting(
			'aqualuxe_link_hover_color',
			array(
				'default'           => '#0369a1',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$this->wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$this->wp_customize,
				'aqualuxe_link_hover_color',
				array(
					'label'    => __( 'Link Hover Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_link_hover_color',
				)
			)
		);

		// Button Background Color.
		$this->wp_customize->add_setting(
			'aqualuxe_button_bg_color',
			array(
				'default'           => '#0ea5e9',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$this->wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$this->wp_customize,
				'aqualuxe_button_bg_color',
				array(
					'label'    => __( 'Button Background Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_button_bg_color',
				)
			)
		);

		// Button Text Color.
		$this->wp_customize->add_setting(
			'aqualuxe_button_text_color',
			array(
				'default'           => '#ffffff',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$this->wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$this->wp_customize,
				'aqualuxe_button_text_color',
				array(
					'label'    => __( 'Button Text Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_button_text_color',
				)
			)
		);

		// Button Hover Background Color.
		$this->wp_customize->add_setting(
			'aqualuxe_button_hover_bg_color',
			array(
				'default'           => '#0369a1',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$this->wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$this->wp_customize,
				'aqualuxe_button_hover_bg_color',
				array(
					'label'    => __( 'Button Hover Background Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_button_hover_bg_color',
				)
			)
		);

		// Button Hover Text Color.
		$this->wp_customize->add_setting(
			'aqualuxe_button_hover_text_color',
			array(
				'default'           => '#ffffff',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$this->wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$this->wp_customize,
				'aqualuxe_button_hover_text_color',
				array(
					'label'    => __( 'Button Hover Text Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_button_hover_text_color',
				)
			)
		);
	}
}