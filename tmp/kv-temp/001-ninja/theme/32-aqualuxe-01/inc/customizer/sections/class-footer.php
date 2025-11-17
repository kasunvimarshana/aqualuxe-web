<?php
/**
 * AquaLuxe Theme Customizer - Footer Settings
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Customizer\Sections;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Footer Settings
 */
class Footer {

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
			'aqualuxe_footer',
			array(
				'title'    => __( 'Footer Settings', 'aqualuxe' ),
				'priority' => 100,
			)
		);
	}

	/**
	 * Register customizer settings.
	 *
	 * @return void
	 */
	public function register_settings() {
		// Footer Layout.
		$this->wp_customize->add_setting(
			'aqualuxe_footer_layout',
			array(
				'default'           => 'default',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_footer_layout',
			array(
				'label'    => __( 'Footer Layout', 'aqualuxe' ),
				'section'  => 'aqualuxe_footer',
				'settings' => 'aqualuxe_footer_layout',
				'type'     => 'select',
				'choices'  => array(
					'default'  => __( 'Default', 'aqualuxe' ),
					'centered' => __( 'Centered', 'aqualuxe' ),
					'minimal'  => __( 'Minimal', 'aqualuxe' ),
					'expanded' => __( 'Expanded', 'aqualuxe' ),
				),
			)
		);

		// Footer Widgets.
		$this->wp_customize->add_setting(
			'aqualuxe_footer_widgets',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_footer_widgets',
			array(
				'label'    => __( 'Enable Footer Widgets', 'aqualuxe' ),
				'section'  => 'aqualuxe_footer',
				'settings' => 'aqualuxe_footer_widgets',
				'type'     => 'checkbox',
			)
		);

		// Footer Widget Columns.
		$this->wp_customize->add_setting(
			'aqualuxe_footer_widget_columns',
			array(
				'default'           => '4',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_footer_widget_columns',
			array(
				'label'    => __( 'Footer Widget Columns', 'aqualuxe' ),
				'section'  => 'aqualuxe_footer',
				'settings' => 'aqualuxe_footer_widget_columns',
				'type'     => 'select',
				'choices'  => array(
					'1' => __( '1 Column', 'aqualuxe' ),
					'2' => __( '2 Columns', 'aqualuxe' ),
					'3' => __( '3 Columns', 'aqualuxe' ),
					'4' => __( '4 Columns', 'aqualuxe' ),
				),
			)
		);

		// Footer Copyright.
		$this->wp_customize->add_setting(
			'aqualuxe_footer_copyright',
			array(
				'default'           => sprintf(
					/* translators: %1$s: Current year, %2$s: Site name */
					__( '© %1$s %2$s. All rights reserved.', 'aqualuxe' ),
					date_i18n( 'Y' ),
					get_bloginfo( 'name' )
				),
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_footer_copyright',
			array(
				'label'    => __( 'Copyright Text', 'aqualuxe' ),
				'section'  => 'aqualuxe_footer',
				'settings' => 'aqualuxe_footer_copyright',
				'type'     => 'textarea',
			)
		);

		// Footer Payment Icons.
		$this->wp_customize->add_setting(
			'aqualuxe_footer_payment_icons',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_footer_payment_icons',
			array(
				'label'    => __( 'Show Payment Icons', 'aqualuxe' ),
				'section'  => 'aqualuxe_footer',
				'settings' => 'aqualuxe_footer_payment_icons',
				'type'     => 'checkbox',
			)
		);

		// Footer Social Icons.
		$this->wp_customize->add_setting(
			'aqualuxe_footer_social_icons',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_footer_social_icons',
			array(
				'label'    => __( 'Show Social Icons', 'aqualuxe' ),
				'section'  => 'aqualuxe_footer',
				'settings' => 'aqualuxe_footer_social_icons',
				'type'     => 'checkbox',
			)
		);

		// Footer Menu.
		$this->wp_customize->add_setting(
			'aqualuxe_footer_menu',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_footer_menu',
			array(
				'label'    => __( 'Show Footer Menu', 'aqualuxe' ),
				'section'  => 'aqualuxe_footer',
				'settings' => 'aqualuxe_footer_menu',
				'type'     => 'checkbox',
			)
		);

		// Footer Address.
		$this->wp_customize->add_setting(
			'aqualuxe_footer_address',
			array(
				'default'           => '123 Main Street, New York, NY 10001',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_footer_address',
			array(
				'label'    => __( 'Address', 'aqualuxe' ),
				'section'  => 'aqualuxe_footer',
				'settings' => 'aqualuxe_footer_address',
				'type'     => 'text',
			)
		);

		// Footer Phone.
		$this->wp_customize->add_setting(
			'aqualuxe_footer_phone',
			array(
				'default'           => '+1 (555) 123-4567',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_footer_phone',
			array(
				'label'    => __( 'Phone Number', 'aqualuxe' ),
				'section'  => 'aqualuxe_footer',
				'settings' => 'aqualuxe_footer_phone',
				'type'     => 'text',
			)
		);

		// Footer Email.
		$this->wp_customize->add_setting(
			'aqualuxe_footer_email',
			array(
				'default'           => 'info@aqualuxe.example.com',
				'sanitize_callback' => 'sanitize_email',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_footer_email',
			array(
				'label'    => __( 'Email Address', 'aqualuxe' ),
				'section'  => 'aqualuxe_footer',
				'settings' => 'aqualuxe_footer_email',
				'type'     => 'email',
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