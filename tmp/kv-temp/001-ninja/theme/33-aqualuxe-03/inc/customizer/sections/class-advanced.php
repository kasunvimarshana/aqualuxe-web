<?php
/**
 * Advanced Customizer Section
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Customizer\Sections;

use AquaLuxe\Customizer\Controls\Heading_Control;
use AquaLuxe\Customizer\Controls\Divider_Control;
use AquaLuxe\Customizer\Controls\Toggle_Control;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Advanced Customizer Section Class
 */
class Advanced {

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
		// Add Advanced section.
		$wp_customize->add_section(
			'aqualuxe_advanced',
			array(
				'title'    => esc_html__( 'Advanced', 'aqualuxe' ),
				'priority' => 110,
				'panel'    => 'aqualuxe_theme_options',
			)
		);

		// Custom Code Settings.
		$wp_customize->add_setting(
			'aqualuxe_advanced_custom_code_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_advanced_custom_code_heading',
				array(
					'label'    => esc_html__( 'Custom Code', 'aqualuxe' ),
					'section'  => 'aqualuxe_advanced',
					'priority' => 10,
				)
			)
		);

		// Custom CSS.
		$wp_customize->add_setting(
			'aqualuxe_custom_css',
			array(
				'default'           => '',
				'sanitize_callback' => 'wp_strip_all_tags',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_custom_css',
			array(
				'label'       => esc_html__( 'Custom CSS', 'aqualuxe' ),
				'description' => esc_html__( 'Add custom CSS code here. This will be added to the header of your site.', 'aqualuxe' ),
				'section'     => 'aqualuxe_advanced',
				'type'        => 'textarea',
				'priority'    => 20,
			)
		);

		// Custom JavaScript.
		$wp_customize->add_setting(
			'aqualuxe_custom_js',
			array(
				'default'           => '',
				'sanitize_callback' => 'wp_strip_all_tags',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_custom_js',
			array(
				'label'       => esc_html__( 'Custom JavaScript', 'aqualuxe' ),
				'description' => esc_html__( 'Add custom JavaScript code here. This will be added to the footer of your site.', 'aqualuxe' ),
				'section'     => 'aqualuxe_advanced',
				'type'        => 'textarea',
				'priority'    => 30,
			)
		);

		// Header Code.
		$wp_customize->add_setting(
			'aqualuxe_header_code',
			array(
				'default'           => '',
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_header_code',
			array(
				'label'       => esc_html__( 'Header Code', 'aqualuxe' ),
				'description' => esc_html__( 'Add code to be included in the head section of your site (e.g., Google Analytics, meta tags).', 'aqualuxe' ),
				'section'     => 'aqualuxe_advanced',
				'type'        => 'textarea',
				'priority'    => 40,
			)
		);

		// Footer Code.
		$wp_customize->add_setting(
			'aqualuxe_footer_code',
			array(
				'default'           => '',
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_footer_code',
			array(
				'label'       => esc_html__( 'Footer Code', 'aqualuxe' ),
				'description' => esc_html__( 'Add code to be included at the end of the body section of your site (e.g., chat widgets, tracking scripts).', 'aqualuxe' ),
				'section'     => 'aqualuxe_advanced',
				'type'        => 'textarea',
				'priority'    => 50,
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_advanced_features_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_advanced_features_divider',
				array(
					'section'  => 'aqualuxe_advanced',
					'priority' => 60,
				)
			)
		);

		// Advanced Features.
		$wp_customize->add_setting(
			'aqualuxe_advanced_features_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_advanced_features_heading',
				array(
					'label'    => esc_html__( 'Advanced Features', 'aqualuxe' ),
					'section'  => 'aqualuxe_advanced',
					'priority' => 70,
				)
			)
		);

		// Dark Mode.
		$wp_customize->add_setting(
			'aqualuxe_enable_dark_mode',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_enable_dark_mode',
				array(
					'label'       => esc_html__( 'Dark Mode', 'aqualuxe' ),
					'description' => esc_html__( 'Enable dark mode feature with toggle switch.', 'aqualuxe' ),
					'section'     => 'aqualuxe_advanced',
					'priority'    => 80,
				)
			)
		);

		// RTL Support.
		$wp_customize->add_setting(
			'aqualuxe_enable_rtl_support',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_enable_rtl_support',
				array(
					'label'       => esc_html__( 'RTL Support', 'aqualuxe' ),
					'description' => esc_html__( 'Enable Right-to-Left language support.', 'aqualuxe' ),
					'section'     => 'aqualuxe_advanced',
					'priority'    => 90,
				)
			)
		);

		// Smooth Scroll.
		$wp_customize->add_setting(
			'aqualuxe_enable_smooth_scroll',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_enable_smooth_scroll',
				array(
					'label'       => esc_html__( 'Smooth Scroll', 'aqualuxe' ),
					'description' => esc_html__( 'Enable smooth scrolling effect.', 'aqualuxe' ),
					'section'     => 'aqualuxe_advanced',
					'priority'    => 100,
				)
			)
		);

		// Back to Top Button.
		$wp_customize->add_setting(
			'aqualuxe_enable_back_to_top',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_enable_back_to_top',
				array(
					'label'       => esc_html__( 'Back to Top Button', 'aqualuxe' ),
					'description' => esc_html__( 'Enable back to top button.', 'aqualuxe' ),
					'section'     => 'aqualuxe_advanced',
					'priority'    => 110,
				)
			)
		);

		// Preloader.
		$wp_customize->add_setting(
			'aqualuxe_enable_preloader',
			array(
				'default'           => false,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_enable_preloader',
				array(
					'label'       => esc_html__( 'Preloader', 'aqualuxe' ),
					'description' => esc_html__( 'Enable preloader animation when page loads.', 'aqualuxe' ),
					'section'     => 'aqualuxe_advanced',
					'priority'    => 120,
				)
			)
		);

		// Preloader Style.
		$wp_customize->add_setting(
			'aqualuxe_preloader_style',
			array(
				'default'           => 'spinner',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
				'active_callback'   => array( $this, 'is_preloader_enabled' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_preloader_style',
			array(
				'label'    => esc_html__( 'Preloader Style', 'aqualuxe' ),
				'section'  => 'aqualuxe_advanced',
				'type'     => 'select',
				'priority' => 130,
				'choices'  => array(
					'spinner'  => esc_html__( 'Spinner', 'aqualuxe' ),
					'dots'     => esc_html__( 'Dots', 'aqualuxe' ),
					'circle'   => esc_html__( 'Circle', 'aqualuxe' ),
					'bars'     => esc_html__( 'Bars', 'aqualuxe' ),
					'custom'   => esc_html__( 'Custom', 'aqualuxe' ),
				),
				'active_callback' => array( $this, 'is_preloader_enabled' ),
			)
		);

		// Custom Preloader.
		$wp_customize->add_setting(
			'aqualuxe_custom_preloader',
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
				'active_callback'   => array( $this, 'is_custom_preloader' ),
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Image_Control(
				$wp_customize,
				'aqualuxe_custom_preloader',
				array(
					'label'       => esc_html__( 'Custom Preloader Image', 'aqualuxe' ),
					'description' => esc_html__( 'Upload a custom preloader image (GIF, PNG, or SVG recommended).', 'aqualuxe' ),
					'section'     => 'aqualuxe_advanced',
					'priority'    => 140,
					'active_callback' => array( $this, 'is_custom_preloader' ),
				)
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_advanced_integrations_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_advanced_integrations_divider',
				array(
					'section'  => 'aqualuxe_advanced',
					'priority' => 150,
				)
			)
		);

		// Integrations.
		$wp_customize->add_setting(
			'aqualuxe_advanced_integrations_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_advanced_integrations_heading',
				array(
					'label'    => esc_html__( 'Integrations', 'aqualuxe' ),
					'section'  => 'aqualuxe_advanced',
					'priority' => 160,
				)
			)
		);

		// Google Analytics.
		$wp_customize->add_setting(
			'aqualuxe_google_analytics_id',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_google_analytics_id',
			array(
				'label'       => esc_html__( 'Google Analytics ID', 'aqualuxe' ),
				'description' => esc_html__( 'Enter your Google Analytics ID (e.g., UA-XXXXX-Y or G-XXXXXXXX).', 'aqualuxe' ),
				'section'     => 'aqualuxe_advanced',
				'type'        => 'text',
				'priority'    => 170,
			)
		);

		// Google Tag Manager.
		$wp_customize->add_setting(
			'aqualuxe_google_tag_manager_id',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_google_tag_manager_id',
			array(
				'label'       => esc_html__( 'Google Tag Manager ID', 'aqualuxe' ),
				'description' => esc_html__( 'Enter your Google Tag Manager ID (e.g., GTM-XXXX).', 'aqualuxe' ),
				'section'     => 'aqualuxe_advanced',
				'type'        => 'text',
				'priority'    => 180,
			)
		);

		// Facebook Pixel.
		$wp_customize->add_setting(
			'aqualuxe_facebook_pixel_id',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_facebook_pixel_id',
			array(
				'label'       => esc_html__( 'Facebook Pixel ID', 'aqualuxe' ),
				'description' => esc_html__( 'Enter your Facebook Pixel ID.', 'aqualuxe' ),
				'section'     => 'aqualuxe_advanced',
				'type'        => 'text',
				'priority'    => 190,
			)
		);

		// Mailchimp API Key.
		$wp_customize->add_setting(
			'aqualuxe_mailchimp_api_key',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_mailchimp_api_key',
			array(
				'label'       => esc_html__( 'Mailchimp API Key', 'aqualuxe' ),
				'description' => esc_html__( 'Enter your Mailchimp API key for newsletter integration.', 'aqualuxe' ),
				'section'     => 'aqualuxe_advanced',
				'type'        => 'text',
				'priority'    => 200,
			)
		);

		// Mailchimp List ID.
		$wp_customize->add_setting(
			'aqualuxe_mailchimp_list_id',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_mailchimp_list_id',
			array(
				'label'       => esc_html__( 'Mailchimp List ID', 'aqualuxe' ),
				'description' => esc_html__( 'Enter your Mailchimp List/Audience ID.', 'aqualuxe' ),
				'section'     => 'aqualuxe_advanced',
				'type'        => 'text',
				'priority'    => 210,
			)
		);

		// reCAPTCHA Site Key.
		$wp_customize->add_setting(
			'aqualuxe_recaptcha_site_key',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_recaptcha_site_key',
			array(
				'label'       => esc_html__( 'reCAPTCHA Site Key', 'aqualuxe' ),
				'description' => esc_html__( 'Enter your Google reCAPTCHA v3 Site Key.', 'aqualuxe' ),
				'section'     => 'aqualuxe_advanced',
				'type'        => 'text',
				'priority'    => 220,
			)
		);

		// reCAPTCHA Secret Key.
		$wp_customize->add_setting(
			'aqualuxe_recaptcha_secret_key',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_recaptcha_secret_key',
			array(
				'label'       => esc_html__( 'reCAPTCHA Secret Key', 'aqualuxe' ),
				'description' => esc_html__( 'Enter your Google reCAPTCHA v3 Secret Key.', 'aqualuxe' ),
				'section'     => 'aqualuxe_advanced',
				'type'        => 'text',
				'priority'    => 230,
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_advanced_maintenance_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_advanced_maintenance_divider',
				array(
					'section'  => 'aqualuxe_advanced',
					'priority' => 240,
				)
			)
		);

		// Maintenance Mode.
		$wp_customize->add_setting(
			'aqualuxe_advanced_maintenance_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_advanced_maintenance_heading',
				array(
					'label'    => esc_html__( 'Maintenance Mode', 'aqualuxe' ),
					'section'  => 'aqualuxe_advanced',
					'priority' => 250,
				)
			)
		);

		// Enable Maintenance Mode.
		$wp_customize->add_setting(
			'aqualuxe_enable_maintenance_mode',
			array(
				'default'           => false,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_enable_maintenance_mode',
				array(
					'label'       => esc_html__( 'Enable Maintenance Mode', 'aqualuxe' ),
					'description' => esc_html__( 'Put your site in maintenance mode. Only administrators can view the site.', 'aqualuxe' ),
					'section'     => 'aqualuxe_advanced',
					'priority'    => 260,
				)
			)
		);

		// Maintenance Mode Message.
		$wp_customize->add_setting(
			'aqualuxe_maintenance_message',
			array(
				'default'           => esc_html__( 'We are currently performing scheduled maintenance. Please check back soon.', 'aqualuxe' ),
				'sanitize_callback' => 'wp_kses_post',
				'active_callback'   => array( $this, 'is_maintenance_mode_enabled' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_maintenance_message',
			array(
				'label'       => esc_html__( 'Maintenance Message', 'aqualuxe' ),
				'description' => esc_html__( 'Message to display during maintenance mode.', 'aqualuxe' ),
				'section'     => 'aqualuxe_advanced',
				'type'        => 'textarea',
				'priority'    => 270,
				'active_callback' => array( $this, 'is_maintenance_mode_enabled' ),
			)
		);

		// Maintenance Mode Background.
		$wp_customize->add_setting(
			'aqualuxe_maintenance_background',
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
				'active_callback'   => array( $this, 'is_maintenance_mode_enabled' ),
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Image_Control(
				$wp_customize,
				'aqualuxe_maintenance_background',
				array(
					'label'       => esc_html__( 'Maintenance Background', 'aqualuxe' ),
					'description' => esc_html__( 'Background image for maintenance mode page.', 'aqualuxe' ),
					'section'     => 'aqualuxe_advanced',
					'priority'    => 280,
					'active_callback' => array( $this, 'is_maintenance_mode_enabled' ),
				)
			)
		);

		// Maintenance Mode Logo.
		$wp_customize->add_setting(
			'aqualuxe_maintenance_logo',
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
				'active_callback'   => array( $this, 'is_maintenance_mode_enabled' ),
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Image_Control(
				$wp_customize,
				'aqualuxe_maintenance_logo',
				array(
					'label'       => esc_html__( 'Maintenance Logo', 'aqualuxe' ),
					'description' => esc_html__( 'Logo to display on maintenance mode page.', 'aqualuxe' ),
					'section'     => 'aqualuxe_advanced',
					'priority'    => 290,
					'active_callback' => array( $this, 'is_maintenance_mode_enabled' ),
				)
			)
		);

		// Maintenance Mode Contact Email.
		$wp_customize->add_setting(
			'aqualuxe_maintenance_contact_email',
			array(
				'default'           => get_option( 'admin_email' ),
				'sanitize_callback' => 'sanitize_email',
				'active_callback'   => array( $this, 'is_maintenance_mode_enabled' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_maintenance_contact_email',
			array(
				'label'       => esc_html__( 'Contact Email', 'aqualuxe' ),
				'description' => esc_html__( 'Email address for visitors to contact during maintenance.', 'aqualuxe' ),
				'section'     => 'aqualuxe_advanced',
				'type'        => 'email',
				'priority'    => 300,
				'active_callback' => array( $this, 'is_maintenance_mode_enabled' ),
			)
		);

		// Maintenance Mode Countdown.
		$wp_customize->add_setting(
			'aqualuxe_maintenance_countdown',
			array(
				'default'           => false,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
				'active_callback'   => array( $this, 'is_maintenance_mode_enabled' ),
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_maintenance_countdown',
				array(
					'label'       => esc_html__( 'Show Countdown', 'aqualuxe' ),
					'description' => esc_html__( 'Display a countdown timer on the maintenance page.', 'aqualuxe' ),
					'section'     => 'aqualuxe_advanced',
					'priority'    => 310,
					'active_callback' => array( $this, 'is_maintenance_mode_enabled' ),
				)
			)
		);

		// Maintenance Mode End Date.
		$wp_customize->add_setting(
			'aqualuxe_maintenance_end_date',
			array(
				'default'           => date( 'Y-m-d', strtotime( '+1 week' ) ),
				'sanitize_callback' => 'sanitize_text_field',
				'active_callback'   => array( $this, 'is_maintenance_countdown_enabled' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_maintenance_end_date',
			array(
				'label'       => esc_html__( 'End Date', 'aqualuxe' ),
				'description' => esc_html__( 'Date when maintenance mode will end (YYYY-MM-DD).', 'aqualuxe' ),
				'section'     => 'aqualuxe_advanced',
				'type'        => 'date',
				'priority'    => 320,
				'active_callback' => array( $this, 'is_maintenance_countdown_enabled' ),
			)
		);
	}

	/**
	 * Check if preloader is enabled
	 *
	 * @return bool
	 */
	public function is_preloader_enabled() {
		return get_theme_mod( 'aqualuxe_enable_preloader', false );
	}

	/**
	 * Check if custom preloader is selected
	 *
	 * @return bool
	 */
	public function is_custom_preloader() {
		return $this->is_preloader_enabled() && 'custom' === get_theme_mod( 'aqualuxe_preloader_style', 'spinner' );
	}

	/**
	 * Check if maintenance mode is enabled
	 *
	 * @return bool
	 */
	public function is_maintenance_mode_enabled() {
		return get_theme_mod( 'aqualuxe_enable_maintenance_mode', false );
	}

	/**
	 * Check if maintenance countdown is enabled
	 *
	 * @return bool
	 */
	public function is_maintenance_countdown_enabled() {
		return $this->is_maintenance_mode_enabled() && get_theme_mod( 'aqualuxe_maintenance_countdown', false );
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
new Advanced();