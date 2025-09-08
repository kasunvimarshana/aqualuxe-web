<?php
/**
 * AquaLuxe Customizer Service
 *
 * @package AquaLuxe
 * @since   1.2.0
 */

namespace AquaLuxe\Services;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Customizer_Service
 *
 * Handles WordPress Customizer integration
 */
class Customizer_Service {

	/**
	 * Configuration
	 *
	 * @var array
	 */
	protected $config;

	/**
	 * Constructor
	 *
	 * @param array $config Configuration
	 */
	public function __construct( array $config = [] ) {
		$this->config = array_merge( $this->get_default_config(), $config );
	}

	/**
	 * Register customizer settings
	 *
	 * @param \WP_Customize_Manager $wp_customize Customizer manager
	 * @return void
	 */
	public function register( $wp_customize ): void {
		$this->register_panels( $wp_customize );
		$this->register_sections( $wp_customize );
		$this->register_controls( $wp_customize );
	}

	/**
	 * Register customizer panels
	 *
	 * @param \WP_Customize_Manager $wp_customize Customizer manager
	 * @return void
	 */
	protected function register_panels( $wp_customize ): void {
		$panels = $this->config['panels'] ?? [];

		foreach ( $panels as $panel_id => $panel_args ) {
			$wp_customize->add_panel( $panel_id, $panel_args );
		}
	}

	/**
	 * Register customizer sections
	 *
	 * @param \WP_Customize_Manager $wp_customize Customizer manager
	 * @return void
	 */
	protected function register_sections( $wp_customize ): void {
		$sections = $this->config['sections'] ?? [];

		foreach ( $sections as $section_id => $section_args ) {
			$wp_customize->add_section( $section_id, $section_args );
		}
	}

	/**
	 * Register customizer controls
	 *
	 * @param \WP_Customize_Manager $wp_customize Customizer manager
	 * @return void
	 */
	protected function register_controls( $wp_customize ): void {
		$controls = $this->config['controls'] ?? [];

		foreach ( $controls as $control_id => $control_args ) {
			// Add setting first
			if ( isset( $control_args['setting'] ) ) {
				$wp_customize->add_setting( $control_id, $control_args['setting'] );
			}

			// Add control
			if ( isset( $control_args['control'] ) ) {
				$control_type = $control_args['control']['type'] ?? 'text';
				$control_class = $this->get_control_class( $control_type );

				if ( $control_class ) {
					$wp_customize->add_control(
						new $control_class(
							$wp_customize,
							$control_id,
							$control_args['control']
						)
					);
				} else {
					$wp_customize->add_control( $control_id, $control_args['control'] );
				}
			}
		}
	}

	/**
	 * Get control class for custom control types
	 *
	 * @param string $type Control type
	 * @return string|null Control class name
	 */
	protected function get_control_class( string $type ): ?string {
		$control_classes = [
			'color' => 'WP_Customize_Color_Control',
			'upload' => 'WP_Customize_Upload_Control',
			'image' => 'WP_Customize_Image_Control',
			'media' => 'WP_Customize_Media_Control',
			'cropped_image' => 'WP_Customize_Cropped_Image_Control',
		];

		return $control_classes[ $type ] ?? null;
	}

	/**
	 * Get default configuration
	 *
	 * @return array
	 */
	protected function get_default_config(): array {
		return [
			'panels' => [
				'aqualuxe_theme' => [
					'title' => __( 'AquaLuxe Theme Options', 'aqualuxe' ),
					'priority' => 30,
				],
			],
			'sections' => [
				'aqualuxe_general' => [
					'title' => __( 'General Settings', 'aqualuxe' ),
					'panel' => 'aqualuxe_theme',
					'priority' => 10,
				],
				'aqualuxe_colors' => [
					'title' => __( 'Color Scheme', 'aqualuxe' ),
					'panel' => 'aqualuxe_theme',
					'priority' => 20,
				],
			],
			'controls' => [
				'aqualuxe_primary_color' => [
					'setting' => [
						'default' => '#1e40af',
						'sanitize_callback' => 'sanitize_hex_color',
					],
					'control' => [
						'type' => 'color',
						'section' => 'aqualuxe_colors',
						'label' => __( 'Primary Color', 'aqualuxe' ),
						'description' => __( 'Choose the primary color for your theme', 'aqualuxe' ),
					],
				],
				'aqualuxe_secondary_color' => [
					'setting' => [
						'default' => '#0891b2',
						'sanitize_callback' => 'sanitize_hex_color',
					],
					'control' => [
						'type' => 'color',
						'section' => 'aqualuxe_colors',
						'label' => __( 'Secondary Color', 'aqualuxe' ),
						'description' => __( 'Choose the secondary color for your theme', 'aqualuxe' ),
					],
				],
			],
		];
	}
}
