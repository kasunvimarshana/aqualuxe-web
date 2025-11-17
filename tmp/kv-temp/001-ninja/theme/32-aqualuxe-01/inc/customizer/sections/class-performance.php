<?php
/**
 * Performance Customizer Section
 *
 * @package AquaLuxe
 * @subpackage Customizer
 * @since 1.1.0
 */

namespace AquaLuxe\Customizer\Sections;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Performance Customizer Section Class
 *
 * Handles the performance settings in the WordPress Customizer.
 *
 * @since 1.1.0
 */
class Performance {

	/**
	 * Constructor
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function __construct( $wp_customize = null ) {
		if ( $wp_customize ) {
			$this->register_settings( $wp_customize );
		} else {
			$this->register_hooks();
		}
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action( 'customize_register', array( $this, 'register_settings' ) );
	}

	/**
	 * Register customizer settings.
	 *
	 * @param \WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	public function register_settings( $wp_customize ) {
		// Add Performance section.
		$wp_customize->add_section(
			'aqualuxe_performance',
			array(
				'title'    => __( 'Performance', 'aqualuxe' ),
				'priority' => 80,
				'panel'    => 'aqualuxe_theme_options',
			)
		);

		// Add settings.
		$this->add_critical_css_settings( $wp_customize );
		$this->add_resource_hints_settings( $wp_customize );
		$this->add_lazy_loading_settings( $wp_customize );
		$this->add_webp_support_settings( $wp_customize );
	}

	/**
	 * Add Critical CSS settings.
	 *
	 * @param \WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	private function add_critical_css_settings( $wp_customize ) {
		// Critical CSS heading.
		$wp_customize->add_setting(
			'aqualuxe_critical_css_heading',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Heading_Control(
				$wp_customize,
				'aqualuxe_critical_css_heading',
				array(
					'label'    => __( 'Critical CSS', 'aqualuxe' ),
					'section'  => 'aqualuxe_performance',
					'priority' => 10,
				)
			)
		);

		// Enable Critical CSS.
		$wp_customize->add_setting(
			'aqualuxe_enable_critical_css',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Toggle_Control(
				$wp_customize,
				'aqualuxe_enable_critical_css',
				array(
					'label'    => __( 'Enable Critical CSS', 'aqualuxe' ),
					'description' => __( 'Inline critical CSS in the head for faster rendering.', 'aqualuxe' ),
					'section'  => 'aqualuxe_performance',
					'priority' => 20,
				)
			)
		);

		// Regenerate Critical CSS.
		$wp_customize->add_setting(
			'aqualuxe_regenerate_critical_css',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_regenerate_critical_css',
			array(
				'label'       => __( 'Regenerate Critical CSS', 'aqualuxe' ),
				'description' => __( 'Click the button below to regenerate critical CSS for all templates.', 'aqualuxe' ),
				'section'     => 'aqualuxe_performance',
				'type'        => 'button',
				'input_attrs' => array(
					'value' => __( 'Regenerate', 'aqualuxe' ),
					'class' => 'button button-secondary',
				),
				'priority'    => 30,
			)
		);
	}

	/**
	 * Add Resource Hints settings.
	 *
	 * @param \WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	private function add_resource_hints_settings( $wp_customize ) {
		// Resource Hints heading.
		$wp_customize->add_setting(
			'aqualuxe_resource_hints_heading',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Heading_Control(
				$wp_customize,
				'aqualuxe_resource_hints_heading',
				array(
					'label'    => __( 'Resource Hints', 'aqualuxe' ),
					'section'  => 'aqualuxe_performance',
					'priority' => 40,
				)
			)
		);

		// Enable Preconnect.
		$wp_customize->add_setting(
			'aqualuxe_enable_preconnect',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Toggle_Control(
				$wp_customize,
				'aqualuxe_enable_preconnect',
				array(
					'label'    => __( 'Enable Preconnect', 'aqualuxe' ),
					'description' => __( 'Establish early connections to important third-party origins.', 'aqualuxe' ),
					'section'  => 'aqualuxe_performance',
					'priority' => 50,
				)
			)
		);

		// Enable Prefetch.
		$wp_customize->add_setting(
			'aqualuxe_enable_prefetch',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Toggle_Control(
				$wp_customize,
				'aqualuxe_enable_prefetch',
				array(
					'label'    => __( 'Enable Prefetch', 'aqualuxe' ),
					'description' => __( 'Prefetch resources that are likely to be needed soon.', 'aqualuxe' ),
					'section'  => 'aqualuxe_performance',
					'priority' => 60,
				)
			)
		);

		// Enable Preload.
		$wp_customize->add_setting(
			'aqualuxe_enable_preload',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Toggle_Control(
				$wp_customize,
				'aqualuxe_enable_preload',
				array(
					'label'    => __( 'Enable Preload', 'aqualuxe' ),
					'description' => __( 'Preload critical resources needed for the current page.', 'aqualuxe' ),
					'section'  => 'aqualuxe_performance',
					'priority' => 70,
				)
			)
		);
	}

	/**
	 * Add Lazy Loading settings.
	 *
	 * @param \WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	private function add_lazy_loading_settings( $wp_customize ) {
		// Lazy Loading heading.
		$wp_customize->add_setting(
			'aqualuxe_lazy_loading_heading',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Heading_Control(
				$wp_customize,
				'aqualuxe_lazy_loading_heading',
				array(
					'label'    => __( 'Lazy Loading', 'aqualuxe' ),
					'section'  => 'aqualuxe_performance',
					'priority' => 80,
				)
			)
		);

		// Enable Lazy Loading.
		$wp_customize->add_setting(
			'aqualuxe_enable_lazy_loading',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Toggle_Control(
				$wp_customize,
				'aqualuxe_enable_lazy_loading',
				array(
					'label'    => __( 'Enable Lazy Loading', 'aqualuxe' ),
					'description' => __( 'Load images and iframes only when they enter the viewport.', 'aqualuxe' ),
					'section'  => 'aqualuxe_performance',
					'priority' => 90,
				)
			)
		);

		// Lazy Load Images.
		$wp_customize->add_setting(
			'aqualuxe_lazy_load_images',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Toggle_Control(
				$wp_customize,
				'aqualuxe_lazy_load_images',
				array(
					'label'    => __( 'Lazy Load Images', 'aqualuxe' ),
					'section'  => 'aqualuxe_performance',
					'priority' => 100,
					'active_callback' => function() {
						return get_theme_mod( 'aqualuxe_enable_lazy_loading', true );
					},
				)
			)
		);

		// Lazy Load Iframes.
		$wp_customize->add_setting(
			'aqualuxe_lazy_load_iframes',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Toggle_Control(
				$wp_customize,
				'aqualuxe_lazy_load_iframes',
				array(
					'label'    => __( 'Lazy Load Iframes', 'aqualuxe' ),
					'section'  => 'aqualuxe_performance',
					'priority' => 110,
					'active_callback' => function() {
						return get_theme_mod( 'aqualuxe_enable_lazy_loading', true );
					},
				)
			)
		);

		// Skip Classes.
		$wp_customize->add_setting(
			'aqualuxe_lazy_load_skip_classes',
			array(
				'default'           => 'no-lazy, skip-lazy',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_lazy_load_skip_classes',
			array(
				'label'       => __( 'Skip Classes', 'aqualuxe' ),
				'description' => __( 'Comma-separated list of CSS classes to skip lazy loading. Example: no-lazy, skip-lazy', 'aqualuxe' ),
				'section'     => 'aqualuxe_performance',
				'type'        => 'text',
				'priority'    => 120,
				'active_callback' => function() {
					return get_theme_mod( 'aqualuxe_enable_lazy_loading', true );
				},
			)
		);
	}

	/**
	 * Add WebP Support settings.
	 *
	 * @param \WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	private function add_webp_support_settings( $wp_customize ) {
		// WebP Support heading.
		$wp_customize->add_setting(
			'aqualuxe_webp_support_heading',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Heading_Control(
				$wp_customize,
				'aqualuxe_webp_support_heading',
				array(
					'label'    => __( 'WebP Support', 'aqualuxe' ),
					'section'  => 'aqualuxe_performance',
					'priority' => 130,
				)
			)
		);

		// Enable WebP.
		$wp_customize->add_setting(
			'aqualuxe_enable_webp',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Toggle_Control(
				$wp_customize,
				'aqualuxe_enable_webp',
				array(
					'label'    => __( 'Enable WebP Support', 'aqualuxe' ),
					'description' => __( 'Serve WebP images to browsers that support them.', 'aqualuxe' ),
					'section'  => 'aqualuxe_performance',
					'priority' => 140,
				)
			)
		);

		// WebP Quality.
		$wp_customize->add_setting(
			'aqualuxe_webp_quality',
			array(
				'default'           => 80,
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Slider_Control(
				$wp_customize,
				'aqualuxe_webp_quality',
				array(
					'label'       => __( 'WebP Quality', 'aqualuxe' ),
					'description' => __( 'Set the quality of WebP images (higher = better quality but larger file size).', 'aqualuxe' ),
					'section'     => 'aqualuxe_performance',
					'input_attrs' => array(
						'min'  => 50,
						'max'  => 100,
						'step' => 5,
					),
					'priority'    => 150,
					'active_callback' => function() {
						return get_theme_mod( 'aqualuxe_enable_webp', true );
					},
				)
			)
		);

		// Convert Existing Images.
		$wp_customize->add_setting(
			'aqualuxe_convert_existing_images',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_convert_existing_images',
			array(
				'label'       => __( 'Convert Existing Images', 'aqualuxe' ),
				'description' => __( 'Click the button below to convert existing images to WebP format.', 'aqualuxe' ),
				'section'     => 'aqualuxe_performance',
				'type'        => 'button',
				'input_attrs' => array(
					'value' => __( 'Convert', 'aqualuxe' ),
					'class' => 'button button-secondary',
				),
				'priority'    => 160,
				'active_callback' => function() {
					return get_theme_mod( 'aqualuxe_enable_webp', true );
				},
			)
		);
	}
}