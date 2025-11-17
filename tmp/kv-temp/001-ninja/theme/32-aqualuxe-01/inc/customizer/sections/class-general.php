<?php
/**
 * AquaLuxe Theme Customizer - General Settings
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Customizer\Sections;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer General Settings
 */
class General {

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
			'aqualuxe_general',
			array(
				'title'    => __( 'General Settings', 'aqualuxe' ),
				'priority' => 10,
			)
		);
	}

	/**
	 * Register customizer settings.
	 *
	 * @return void
	 */
	public function register_settings() {
		// Site Logo.
		$this->wp_customize->add_setting(
			'aqualuxe_logo',
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$this->wp_customize->add_control(
			new \WP_Customize_Image_Control(
				$this->wp_customize,
				'aqualuxe_logo',
				array(
					'label'    => __( 'Logo', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'settings' => 'aqualuxe_logo',
				)
			)
		);

		// Site Logo Width.
		$this->wp_customize->add_setting(
			'aqualuxe_logo_width',
			array(
				'default'           => '150',
				'sanitize_callback' => 'absint',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_logo_width',
			array(
				'label'    => __( 'Logo Width (px)', 'aqualuxe' ),
				'section'  => 'aqualuxe_general',
				'settings' => 'aqualuxe_logo_width',
				'type'     => 'number',
			)
		);

		// Site Logo Height.
		$this->wp_customize->add_setting(
			'aqualuxe_logo_height',
			array(
				'default'           => '50',
				'sanitize_callback' => 'absint',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_logo_height',
			array(
				'label'    => __( 'Logo Height (px)', 'aqualuxe' ),
				'section'  => 'aqualuxe_general',
				'settings' => 'aqualuxe_logo_height',
				'type'     => 'number',
			)
		);

		// Site Favicon.
		$this->wp_customize->add_setting(
			'aqualuxe_favicon',
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$this->wp_customize->add_control(
			new \WP_Customize_Image_Control(
				$this->wp_customize,
				'aqualuxe_favicon',
				array(
					'label'    => __( 'Favicon', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'settings' => 'aqualuxe_favicon',
				)
			)
		);

		// Container Width.
		$this->wp_customize->add_setting(
			'aqualuxe_container_width',
			array(
				'default'           => '1200',
				'sanitize_callback' => 'absint',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_container_width',
			array(
				'label'    => __( 'Container Width (px)', 'aqualuxe' ),
				'section'  => 'aqualuxe_general',
				'settings' => 'aqualuxe_container_width',
				'type'     => 'number',
			)
		);

		// Enable Preloader.
		$this->wp_customize->add_setting(
			'aqualuxe_enable_preloader',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_enable_preloader',
			array(
				'label'    => __( 'Enable Preloader', 'aqualuxe' ),
				'section'  => 'aqualuxe_general',
				'settings' => 'aqualuxe_enable_preloader',
				'type'     => 'checkbox',
			)
		);

		// Enable Back to Top.
		$this->wp_customize->add_setting(
			'aqualuxe_enable_back_to_top',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_enable_back_to_top',
			array(
				'label'    => __( 'Enable Back to Top', 'aqualuxe' ),
				'section'  => 'aqualuxe_general',
				'settings' => 'aqualuxe_enable_back_to_top',
				'type'     => 'checkbox',
			)
		);

		// Enable Smooth Scroll.
		$this->wp_customize->add_setting(
			'aqualuxe_enable_smooth_scroll',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_enable_smooth_scroll',
			array(
				'label'    => __( 'Enable Smooth Scroll', 'aqualuxe' ),
				'section'  => 'aqualuxe_general',
				'settings' => 'aqualuxe_enable_smooth_scroll',
				'type'     => 'checkbox',
			)
		);
	}

	/**
	 * Sanitize checkbox.
	 *
	 * @param bool $checked Whether the checkbox is checked.
	 * @return bool
	 */
	public function sanitize_checkbox( $checked ) {
		return ( isset( $checked ) && true === $checked ) ? true : false;
	}
}