<?php
/**
 * Performance Customizer Section
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Customizer\Sections;

use AquaLuxe\Customizer\Controls\Heading_Control;
use AquaLuxe\Customizer\Controls\Divider_Control;
use AquaLuxe\Customizer\Controls\Toggle_Control;
use AquaLuxe\Customizer\Controls\Slider_Control;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Performance Customizer Section Class
 */
class Performance {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'register_settings' ) );
	}

	/**
	 * Register customizer settings
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function register_settings( $wp_customize ) {
		// Add Performance section.
		$wp_customize->add_section(
			'aqualuxe_performance',
			array(
				'title'    => esc_html__( 'Performance', 'aqualuxe' ),
				'priority' => 90,
				'panel'    => 'aqualuxe_theme_options',
			)
		);

		// Asset Optimization.
		$wp_customize->add_setting(
			'aqualuxe_performance_assets_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_performance_assets_heading',
				array(
					'label'    => esc_html__( 'Asset Optimization', 'aqualuxe' ),
					'section'  => 'aqualuxe_performance',
					'priority' => 10,
				)
			)
		);

		// CSS Optimization.
		$wp_customize->add_setting(
			'aqualuxe_optimize_css',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_optimize_css',
				array(
					'label'       => esc_html__( 'Optimize CSS', 'aqualuxe' ),
					'description' => esc_html__( 'Minify and combine CSS files to reduce HTTP requests.', 'aqualuxe' ),
					'section'     => 'aqualuxe_performance',
					'priority'    => 20,
				)
			)
		);

		// JavaScript Optimization.
		$wp_customize->add_setting(
			'aqualuxe_optimize_js',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_optimize_js',
				array(
					'label'       => esc_html__( 'Optimize JavaScript', 'aqualuxe' ),
					'description' => esc_html__( 'Minify and combine JavaScript files to reduce HTTP requests.', 'aqualuxe' ),
					'section'     => 'aqualuxe_performance',
					'priority'    => 30,
				)
			)
		);

		// Defer JavaScript.
		$wp_customize->add_setting(
			'aqualuxe_defer_js',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_defer_js',
				array(
					'label'       => esc_html__( 'Defer JavaScript', 'aqualuxe' ),
					'description' => esc_html__( 'Defer JavaScript execution to improve page load speed.', 'aqualuxe' ),
					'section'     => 'aqualuxe_performance',
					'priority'    => 40,
				)
			)
		);

		// Critical CSS.
		$wp_customize->add_setting(
			'aqualuxe_critical_css',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_critical_css',
				array(
					'label'       => esc_html__( 'Critical CSS', 'aqualuxe' ),
					'description' => esc_html__( 'Generate and inline critical CSS for above-the-fold content.', 'aqualuxe' ),
					'section'     => 'aqualuxe_performance',
					'priority'    => 50,
				)
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_performance_images_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_performance_images_divider',
				array(
					'section'  => 'aqualuxe_performance',
					'priority' => 60,
				)
			)
		);

		// Image Optimization.
		$wp_customize->add_setting(
			'aqualuxe_performance_images_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_performance_images_heading',
				array(
					'label'    => esc_html__( 'Image Optimization', 'aqualuxe' ),
					'section'  => 'aqualuxe_performance',
					'priority' => 70,
				)
			)
		);

		// Lazy Loading.
		$wp_customize->add_setting(
			'aqualuxe_lazy_loading',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_lazy_loading',
				array(
					'label'       => esc_html__( 'Lazy Loading', 'aqualuxe' ),
					'description' => esc_html__( 'Load images only when they enter the viewport.', 'aqualuxe' ),
					'section'     => 'aqualuxe_performance',
					'priority'    => 80,
				)
			)
		);

		// WebP Support.
		$wp_customize->add_setting(
			'aqualuxe_webp_support',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_webp_support',
				array(
					'label'       => esc_html__( 'WebP Support', 'aqualuxe' ),
					'description' => esc_html__( 'Serve WebP images to browsers that support them.', 'aqualuxe' ),
					'section'     => 'aqualuxe_performance',
					'priority'    => 90,
				)
			)
		);

		// Image Quality.
		$wp_customize->add_setting(
			'aqualuxe_image_quality',
			array(
				'default'           => 82,
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_image_quality',
				array(
					'label'       => esc_html__( 'Image Quality', 'aqualuxe' ),
					'description' => esc_html__( 'Set the quality of JPEG images (lower values = smaller file sizes).', 'aqualuxe' ),
					'section'     => 'aqualuxe_performance',
					'priority'    => 100,
					'input_attrs' => array(
						'min'  => 50,
						'max'  => 100,
						'step' => 1,
					),
				)
			)
		);

		// Responsive Images.
		$wp_customize->add_setting(
			'aqualuxe_responsive_images',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_responsive_images',
				array(
					'label'       => esc_html__( 'Responsive Images', 'aqualuxe' ),
					'description' => esc_html__( 'Serve appropriately sized images based on device screen size.', 'aqualuxe' ),
					'section'     => 'aqualuxe_performance',
					'priority'    => 110,
				)
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_performance_caching_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_performance_caching_divider',
				array(
					'section'  => 'aqualuxe_performance',
					'priority' => 120,
				)
			)
		);

		// Caching & Browser Optimization.
		$wp_customize->add_setting(
			'aqualuxe_performance_caching_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_performance_caching_heading',
				array(
					'label'    => esc_html__( 'Caching & Browser Optimization', 'aqualuxe' ),
					'section'  => 'aqualuxe_performance',
					'priority' => 130,
				)
			)
		);

		// Browser Caching.
		$wp_customize->add_setting(
			'aqualuxe_browser_caching',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_browser_caching',
				array(
					'label'       => esc_html__( 'Browser Caching', 'aqualuxe' ),
					'description' => esc_html__( 'Set browser cache headers to improve load times for returning visitors.', 'aqualuxe' ),
					'section'     => 'aqualuxe_performance',
					'priority'    => 140,
				)
			)
		);

		// Cache Expiration.
		$wp_customize->add_setting(
			'aqualuxe_cache_expiration',
			array(
				'default'           => 7,
				'sanitize_callback' => 'absint',
				'active_callback'   => array( $this, 'is_browser_caching_enabled' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_cache_expiration',
			array(
				'label'       => esc_html__( 'Cache Expiration (days)', 'aqualuxe' ),
				'description' => esc_html__( 'Set how long browsers should cache your assets.', 'aqualuxe' ),
				'section'     => 'aqualuxe_performance',
				'type'        => 'select',
				'priority'    => 150,
				'choices'     => array(
					1  => esc_html__( '1 day', 'aqualuxe' ),
					3  => esc_html__( '3 days', 'aqualuxe' ),
					7  => esc_html__( '1 week', 'aqualuxe' ),
					14 => esc_html__( '2 weeks', 'aqualuxe' ),
					30 => esc_html__( '1 month', 'aqualuxe' ),
				),
				'active_callback' => array( $this, 'is_browser_caching_enabled' ),
			)
		);

		// Resource Hints.
		$wp_customize->add_setting(
			'aqualuxe_resource_hints',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_resource_hints',
				array(
					'label'       => esc_html__( 'Resource Hints', 'aqualuxe' ),
					'description' => esc_html__( 'Add DNS prefetch, preconnect, and preload hints to speed up external resources.', 'aqualuxe' ),
					'section'     => 'aqualuxe_performance',
					'priority'    => 160,
				)
			)
		);

		// GZIP Compression.
		$wp_customize->add_setting(
			'aqualuxe_gzip_compression',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_gzip_compression',
				array(
					'label'       => esc_html__( 'GZIP Compression', 'aqualuxe' ),
					'description' => esc_html__( 'Enable GZIP compression for faster page loading.', 'aqualuxe' ),
					'section'     => 'aqualuxe_performance',
					'priority'    => 170,
				)
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_performance_advanced_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_performance_advanced_divider',
				array(
					'section'  => 'aqualuxe_performance',
					'priority' => 180,
				)
			)
		);

		// Advanced Optimization.
		$wp_customize->add_setting(
			'aqualuxe_performance_advanced_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_performance_advanced_heading',
				array(
					'label'    => esc_html__( 'Advanced Optimization', 'aqualuxe' ),
					'section'  => 'aqualuxe_performance',
					'priority' => 190,
				)
			)
		);

		// Disable Emojis.
		$wp_customize->add_setting(
			'aqualuxe_disable_emojis',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_disable_emojis',
				array(
					'label'       => esc_html__( 'Disable Emojis', 'aqualuxe' ),
					'description' => esc_html__( 'Remove WordPress emoji scripts and styles.', 'aqualuxe' ),
					'section'     => 'aqualuxe_performance',
					'priority'    => 200,
				)
			)
		);

		// Disable Embeds.
		$wp_customize->add_setting(
			'aqualuxe_disable_embeds',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_disable_embeds',
				array(
					'label'       => esc_html__( 'Disable Embeds', 'aqualuxe' ),
					'description' => esc_html__( 'Remove WordPress embed functionality.', 'aqualuxe' ),
					'section'     => 'aqualuxe_performance',
					'priority'    => 210,
				)
			)
		);

		// Remove Query Strings.
		$wp_customize->add_setting(
			'aqualuxe_remove_query_strings',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_remove_query_strings',
				array(
					'label'       => esc_html__( 'Remove Query Strings', 'aqualuxe' ),
					'description' => esc_html__( 'Remove version query strings from static resources for better caching.', 'aqualuxe' ),
					'section'     => 'aqualuxe_performance',
					'priority'    => 220,
				)
			)
		);

		// Disable Heartbeat.
		$wp_customize->add_setting(
			'aqualuxe_disable_heartbeat',
			array(
				'default'           => 'optimize',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_disable_heartbeat',
			array(
				'label'       => esc_html__( 'WordPress Heartbeat', 'aqualuxe' ),
				'description' => esc_html__( 'Control the WordPress Heartbeat API.', 'aqualuxe' ),
				'section'     => 'aqualuxe_performance',
				'type'        => 'select',
				'priority'    => 230,
				'choices'     => array(
					'enable'   => esc_html__( 'Enable', 'aqualuxe' ),
					'optimize' => esc_html__( 'Optimize', 'aqualuxe' ),
					'disable'  => esc_html__( 'Disable', 'aqualuxe' ),
				),
			)
		);

		// Limit Post Revisions.
		$wp_customize->add_setting(
			'aqualuxe_limit_post_revisions',
			array(
				'default'           => 5,
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_limit_post_revisions',
				array(
					'label'       => esc_html__( 'Limit Post Revisions', 'aqualuxe' ),
					'description' => esc_html__( 'Limit the number of post revisions stored in the database.', 'aqualuxe' ),
					'section'     => 'aqualuxe_performance',
					'priority'    => 240,
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => 1,
					),
				)
			)
		);

		// Autosave Interval.
		$wp_customize->add_setting(
			'aqualuxe_autosave_interval',
			array(
				'default'           => 60,
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_autosave_interval',
			array(
				'label'       => esc_html__( 'Autosave Interval (seconds)', 'aqualuxe' ),
				'description' => esc_html__( 'Set how often WordPress should autosave posts while editing.', 'aqualuxe' ),
				'section'     => 'aqualuxe_performance',
				'type'        => 'select',
				'priority'    => 250,
				'choices'     => array(
					30  => esc_html__( '30 seconds', 'aqualuxe' ),
					60  => esc_html__( '1 minute', 'aqualuxe' ),
					120 => esc_html__( '2 minutes', 'aqualuxe' ),
					180 => esc_html__( '3 minutes', 'aqualuxe' ),
					300 => esc_html__( '5 minutes', 'aqualuxe' ),
				),
			)
		);
	}

	/**
	 * Check if browser caching is enabled
	 *
	 * @return bool
	 */
	public function is_browser_caching_enabled() {
		return get_theme_mod( 'aqualuxe_browser_caching', true );
	}

	/**
	 * Sanitize choices
	 *
	 * @param string $input Value to sanitize.
	 * @param object $setting Setting object.
	 * @return string Sanitized value.
	 */
	public function sanitize_choices( $input, $setting ) {
		// Get the list of possible choices.
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// Return input if valid or return default.
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}
}

// Initialize the class.
new Performance();