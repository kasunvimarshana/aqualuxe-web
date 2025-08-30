<?php
/**
 * AquaLuxe Theme Customizer - Colors Settings
 *
 * @package AquaLuxe
 * @subpackage Customizer
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
	 * WP_Customize_Manager instance
	 *
	 * @var \WP_Customize_Manager
	 */
	private $wp_customize;

	/**
	 * Constructor
	 *
	 * @param \WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 */
	public function __construct( $wp_customize ) {
		$this->wp_customize = $wp_customize;
		$this->register_sections();
		$this->register_settings();
	}

	/**
	 * Register customizer sections
	 *
	 * @return void
	 */
	public function register_sections() {
		$this->wp_customize->add_section(
			'aqualuxe_colors',
			array(
				'title'    => __( 'Colors', 'aqualuxe' ),
				'priority' => 40,
				'panel'    => 'aqualuxe_theme_options',
			)
		);
	}

	/**
	 * Register customizer settings
	 *
	 * @return void
	 */
	public function register_settings() {
		// Add heading for light mode colors.
		$this->wp_customize->add_setting(
			'aqualuxe_light_mode_heading',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Heading_Control(
				$this->wp_customize,
				'aqualuxe_light_mode_heading',
				array(
					'label'    => __( 'Light Mode Colors', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_light_mode_heading',
					'priority' => 10,
				)
			)
		);

		// Primary Color.
		$this->wp_customize->add_setting(
			'aqualuxe_primary_color',
			array(
				'default'           => '#0ea5e9',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Color_Alpha_Control(
				$this->wp_customize,
				'aqualuxe_primary_color',
				array(
					'label'    => __( 'Primary Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_primary_color',
					'priority' => 20,
				)
			)
		);

		// Secondary Color.
		$this->wp_customize->add_setting(
			'aqualuxe_secondary_color',
			array(
				'default'           => '#14b8a6',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Color_Alpha_Control(
				$this->wp_customize,
				'aqualuxe_secondary_color',
				array(
					'label'    => __( 'Secondary Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_secondary_color',
					'priority' => 30,
				)
			)
		);

		// Accent Color.
		$this->wp_customize->add_setting(
			'aqualuxe_accent_color',
			array(
				'default'           => '#f97316',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Color_Alpha_Control(
				$this->wp_customize,
				'aqualuxe_accent_color',
				array(
					'label'    => __( 'Accent Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_accent_color',
					'priority' => 40,
				)
			)
		);

		// Text Color.
		$this->wp_customize->add_setting(
			'aqualuxe_text_color',
			array(
				'default'           => '#374151',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Color_Alpha_Control(
				$this->wp_customize,
				'aqualuxe_text_color',
				array(
					'label'    => __( 'Text Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_text_color',
					'priority' => 50,
				)
			)
		);

		// Heading Color.
		$this->wp_customize->add_setting(
			'aqualuxe_heading_color',
			array(
				'default'           => '#1f2937',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Color_Alpha_Control(
				$this->wp_customize,
				'aqualuxe_heading_color',
				array(
					'label'    => __( 'Heading Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_heading_color',
					'priority' => 60,
				)
			)
		);

		// Background Color.
		$this->wp_customize->add_setting(
			'aqualuxe_background_color',
			array(
				'default'           => '#ffffff',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Color_Alpha_Control(
				$this->wp_customize,
				'aqualuxe_background_color',
				array(
					'label'    => __( 'Background Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_background_color',
					'priority' => 70,
				)
			)
		);

		// Link Color.
		$this->wp_customize->add_setting(
			'aqualuxe_link_color',
			array(
				'default'           => '#0ea5e9',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Color_Alpha_Control(
				$this->wp_customize,
				'aqualuxe_link_color',
				array(
					'label'    => __( 'Link Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_link_color',
					'priority' => 80,
				)
			)
		);

		// Link Hover Color.
		$this->wp_customize->add_setting(
			'aqualuxe_link_hover_color',
			array(
				'default'           => '#0369a1',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Color_Alpha_Control(
				$this->wp_customize,
				'aqualuxe_link_hover_color',
				array(
					'label'    => __( 'Link Hover Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_link_hover_color',
					'priority' => 90,
				)
			)
		);

		// Button Background Color.
		$this->wp_customize->add_setting(
			'aqualuxe_button_bg_color',
			array(
				'default'           => '#0ea5e9',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Color_Alpha_Control(
				$this->wp_customize,
				'aqualuxe_button_bg_color',
				array(
					'label'    => __( 'Button Background Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_button_bg_color',
					'priority' => 100,
				)
			)
		);

		// Button Text Color.
		$this->wp_customize->add_setting(
			'aqualuxe_button_text_color',
			array(
				'default'           => '#ffffff',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Color_Alpha_Control(
				$this->wp_customize,
				'aqualuxe_button_text_color',
				array(
					'label'    => __( 'Button Text Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_button_text_color',
					'priority' => 110,
				)
			)
		);

		// Button Hover Background Color.
		$this->wp_customize->add_setting(
			'aqualuxe_button_hover_bg_color',
			array(
				'default'           => '#0369a1',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Color_Alpha_Control(
				$this->wp_customize,
				'aqualuxe_button_hover_bg_color',
				array(
					'label'    => __( 'Button Hover Background Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_button_hover_bg_color',
					'priority' => 120,
				)
			)
		);

		// Button Hover Text Color.
		$this->wp_customize->add_setting(
			'aqualuxe_button_hover_text_color',
			array(
				'default'           => '#ffffff',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Color_Alpha_Control(
				$this->wp_customize,
				'aqualuxe_button_hover_text_color',
				array(
					'label'    => __( 'Button Hover Text Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_button_hover_text_color',
					'priority' => 130,
				)
			)
		);

		// Add divider.
		$this->wp_customize->add_setting(
			'aqualuxe_colors_divider',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Divider_Control(
				$this->wp_customize,
				'aqualuxe_colors_divider',
				array(
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_colors_divider',
					'priority' => 140,
				)
			)
		);

		// Add heading for dark mode colors.
		$this->wp_customize->add_setting(
			'aqualuxe_dark_mode_heading',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Heading_Control(
				$this->wp_customize,
				'aqualuxe_dark_mode_heading',
				array(
					'label'    => __( 'Dark Mode Colors', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_dark_mode_heading',
					'priority' => 150,
				)
			)
		);

		// Enable Dark Mode.
		$this->wp_customize->add_setting(
			'aqualuxe_enable_dark_mode',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Toggle_Control(
				$this->wp_customize,
				'aqualuxe_enable_dark_mode',
				array(
					'label'    => __( 'Enable Dark Mode', 'aqualuxe' ),
					'section'  => 'aqualuxe_colors',
					'settings' => 'aqualuxe_enable_dark_mode',
					'priority' => 160,
				)
			)
		);

		// Dark Mode Default.
		$this->wp_customize->add_setting(
			'aqualuxe_dark_mode_default',
			array(
				'default'           => 'auto',
				'sanitize_callback' => 'aqualuxe_sanitize_select',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_dark_mode_default',
			array(
				'label'           => __( 'Dark Mode Default', 'aqualuxe' ),
				'description'     => __( 'Choose the default mode for new visitors.', 'aqualuxe' ),
				'section'         => 'aqualuxe_colors',
				'settings'        => 'aqualuxe_dark_mode_default',
				'type'            => 'select',
				'choices'         => array(
					'light' => __( 'Light Mode', 'aqualuxe' ),
					'dark'  => __( 'Dark Mode', 'aqualuxe' ),
					'auto'  => __( 'Auto (System Preference)', 'aqualuxe' ),
				),
				'priority'        => 170,
				'active_callback' => function() {
					return get_theme_mod( 'aqualuxe_enable_dark_mode', true );
				},
			)
		);

		// Dark Mode Primary Color.
		$this->wp_customize->add_setting(
			'aqualuxe_dark_primary_color',
			array(
				'default'           => '#38bdf8',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Color_Alpha_Control(
				$this->wp_customize,
				'aqualuxe_dark_primary_color',
				array(
					'label'           => __( 'Primary Color (Dark Mode)', 'aqualuxe' ),
					'section'         => 'aqualuxe_colors',
					'settings'        => 'aqualuxe_dark_primary_color',
					'priority'        => 180,
					'active_callback' => function() {
						return get_theme_mod( 'aqualuxe_enable_dark_mode', true );
					},
				)
			)
		);

		// Dark Mode Secondary Color.
		$this->wp_customize->add_setting(
			'aqualuxe_dark_secondary_color',
			array(
				'default'           => '#2dd4bf',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Color_Alpha_Control(
				$this->wp_customize,
				'aqualuxe_dark_secondary_color',
				array(
					'label'           => __( 'Secondary Color (Dark Mode)', 'aqualuxe' ),
					'section'         => 'aqualuxe_colors',
					'settings'        => 'aqualuxe_dark_secondary_color',
					'priority'        => 190,
					'active_callback' => function() {
						return get_theme_mod( 'aqualuxe_enable_dark_mode', true );
					},
				)
			)
		);

		// Dark Mode Accent Color.
		$this->wp_customize->add_setting(
			'aqualuxe_dark_accent_color',
			array(
				'default'           => '#fb923c',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Color_Alpha_Control(
				$this->wp_customize,
				'aqualuxe_dark_accent_color',
				array(
					'label'           => __( 'Accent Color (Dark Mode)', 'aqualuxe' ),
					'section'         => 'aqualuxe_colors',
					'settings'        => 'aqualuxe_dark_accent_color',
					'priority'        => 200,
					'active_callback' => function() {
						return get_theme_mod( 'aqualuxe_enable_dark_mode', true );
					},
				)
			)
		);

		// Dark Mode Text Color.
		$this->wp_customize->add_setting(
			'aqualuxe_dark_text_color',
			array(
				'default'           => '#f3f4f6',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Color_Alpha_Control(
				$this->wp_customize,
				'aqualuxe_dark_text_color',
				array(
					'label'           => __( 'Text Color (Dark Mode)', 'aqualuxe' ),
					'section'         => 'aqualuxe_colors',
					'settings'        => 'aqualuxe_dark_text_color',
					'priority'        => 210,
					'active_callback' => function() {
						return get_theme_mod( 'aqualuxe_enable_dark_mode', true );
					},
				)
			)
		);

		// Dark Mode Heading Color.
		$this->wp_customize->add_setting(
			'aqualuxe_dark_heading_color',
			array(
				'default'           => '#f9fafb',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Color_Alpha_Control(
				$this->wp_customize,
				'aqualuxe_dark_heading_color',
				array(
					'label'           => __( 'Heading Color (Dark Mode)', 'aqualuxe' ),
					'section'         => 'aqualuxe_colors',
					'settings'        => 'aqualuxe_dark_heading_color',
					'priority'        => 220,
					'active_callback' => function() {
						return get_theme_mod( 'aqualuxe_enable_dark_mode', true );
					},
				)
			)
		);

		// Dark Mode Background Color.
		$this->wp_customize->add_setting(
			'aqualuxe_dark_background_color',
			array(
				'default'           => '#111827',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Color_Alpha_Control(
				$this->wp_customize,
				'aqualuxe_dark_background_color',
				array(
					'label'           => __( 'Background Color (Dark Mode)', 'aqualuxe' ),
					'section'         => 'aqualuxe_colors',
					'settings'        => 'aqualuxe_dark_background_color',
					'priority'        => 230,
					'active_callback' => function() {
						return get_theme_mod( 'aqualuxe_enable_dark_mode', true );
					},
				)
			)
		);

		// Dark Mode Link Color.
		$this->wp_customize->add_setting(
			'aqualuxe_dark_link_color',
			array(
				'default'           => '#38bdf8',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Color_Alpha_Control(
				$this->wp_customize,
				'aqualuxe_dark_link_color',
				array(
					'label'           => __( 'Link Color (Dark Mode)', 'aqualuxe' ),
					'section'         => 'aqualuxe_colors',
					'settings'        => 'aqualuxe_dark_link_color',
					'priority'        => 240,
					'active_callback' => function() {
						return get_theme_mod( 'aqualuxe_enable_dark_mode', true );
					},
				)
			)
		);

		// Dark Mode Link Hover Color.
		$this->wp_customize->add_setting(
			'aqualuxe_dark_link_hover_color',
			array(
				'default'           => '#7dd3fc',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$this->wp_customize->add_control(
			new \AquaLuxe\Customizer\Controls\Color_Alpha_Control(
				$this->wp_customize,
				'aqualuxe_dark_link_hover_color',
				array(
					'label'           => __( 'Link Hover Color (Dark Mode)', 'aqualuxe' ),
					'section'         => 'aqualuxe_colors',
					'settings'        => 'aqualuxe_dark_link_hover_color',
					'priority'        => 250,
					'active_callback' => function() {
						return get_theme_mod( 'aqualuxe_enable_dark_mode', true );
					},
				)
			)
		);
	}
}