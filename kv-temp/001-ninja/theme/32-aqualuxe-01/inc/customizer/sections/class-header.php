<?php
/**
 * AquaLuxe Theme Customizer - Header Settings
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Customizer\Sections;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Header Settings
 */
class Header {

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
			'aqualuxe_header',
			array(
				'title'    => __( 'Header Settings', 'aqualuxe' ),
				'priority' => 20,
			)
		);
	}

	/**
	 * Register customizer settings.
	 *
	 * @return void
	 */
	public function register_settings() {
		// Header Layout.
		$this->wp_customize->add_setting(
			'aqualuxe_header_layout',
			array(
				'default'           => 'default',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_header_layout',
			array(
				'label'    => __( 'Header Layout', 'aqualuxe' ),
				'section'  => 'aqualuxe_header',
				'settings' => 'aqualuxe_header_layout',
				'type'     => 'select',
				'choices'  => array(
					'default'     => __( 'Default', 'aqualuxe' ),
					'centered'    => __( 'Centered', 'aqualuxe' ),
					'transparent' => __( 'Transparent', 'aqualuxe' ),
					'split'       => __( 'Split', 'aqualuxe' ),
					'minimal'     => __( 'Minimal', 'aqualuxe' ),
				),
			)
		);

		// Sticky Header.
		$this->wp_customize->add_setting(
			'aqualuxe_sticky_header',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_sticky_header',
			array(
				'label'    => __( 'Enable Sticky Header', 'aqualuxe' ),
				'section'  => 'aqualuxe_header',
				'settings' => 'aqualuxe_sticky_header',
				'type'     => 'checkbox',
			)
		);

		// Header Top Bar.
		$this->wp_customize->add_setting(
			'aqualuxe_header_top_bar',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_header_top_bar',
			array(
				'label'    => __( 'Enable Top Bar', 'aqualuxe' ),
				'section'  => 'aqualuxe_header',
				'settings' => 'aqualuxe_header_top_bar',
				'type'     => 'checkbox',
			)
		);

		// Header Top Bar Text.
		$this->wp_customize->add_setting(
			'aqualuxe_header_top_bar_text',
			array(
				'default'           => __( 'Welcome to AquaLuxe', 'aqualuxe' ),
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_header_top_bar_text',
			array(
				'label'    => __( 'Top Bar Text', 'aqualuxe' ),
				'section'  => 'aqualuxe_header',
				'settings' => 'aqualuxe_header_top_bar_text',
				'type'     => 'text',
			)
		);

		// Header Phone.
		$this->wp_customize->add_setting(
			'aqualuxe_header_phone',
			array(
				'default'           => '+1 (555) 123-4567',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_header_phone',
			array(
				'label'    => __( 'Phone Number', 'aqualuxe' ),
				'section'  => 'aqualuxe_header',
				'settings' => 'aqualuxe_header_phone',
				'type'     => 'text',
			)
		);

		// Header Email.
		$this->wp_customize->add_setting(
			'aqualuxe_header_email',
			array(
				'default'           => 'info@aqualuxe.example.com',
				'sanitize_callback' => 'sanitize_email',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_header_email',
			array(
				'label'    => __( 'Email Address', 'aqualuxe' ),
				'section'  => 'aqualuxe_header',
				'settings' => 'aqualuxe_header_email',
				'type'     => 'email',
			)
		);

		// Header Search.
		$this->wp_customize->add_setting(
			'aqualuxe_header_search',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_header_search',
			array(
				'label'    => __( 'Enable Search', 'aqualuxe' ),
				'section'  => 'aqualuxe_header',
				'settings' => 'aqualuxe_header_search',
				'type'     => 'checkbox',
			)
		);

		// Header Cart.
		$this->wp_customize->add_setting(
			'aqualuxe_header_cart',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_header_cart',
			array(
				'label'    => __( 'Enable Cart', 'aqualuxe' ),
				'section'  => 'aqualuxe_header',
				'settings' => 'aqualuxe_header_cart',
				'type'     => 'checkbox',
			)
		);

		// Header Account.
		$this->wp_customize->add_setting(
			'aqualuxe_header_account',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_header_account',
			array(
				'label'    => __( 'Enable Account', 'aqualuxe' ),
				'section'  => 'aqualuxe_header',
				'settings' => 'aqualuxe_header_account',
				'type'     => 'checkbox',
			)
		);

		// Header Wishlist.
		$this->wp_customize->add_setting(
			'aqualuxe_header_wishlist',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_header_wishlist',
			array(
				'label'    => __( 'Enable Wishlist', 'aqualuxe' ),
				'section'  => 'aqualuxe_header',
				'settings' => 'aqualuxe_header_wishlist',
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

	/**
	 * Sanitize select.
	 *
	 * @param string $input Select value.
	 * @param object $setting Setting object.
	 * @return string
	 */
	public function sanitize_select( $input, $setting ) {
		$input   = sanitize_key( $input );
		$choices = $setting->manager->get_control( $setting->id )->choices;

		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}
}