<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe\Inc\Customizer
 */

namespace AquaLuxe\Inc\Customizer;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer class.
 */
class Customizer {
	/**
	 * The WP_Customize_Manager instance.
	 *
	 * @var \WP_Customize_Manager
	 */
	private $wp_customize;

	/**
	 * Constructor.
	 *
	 * @param \WP_Customize_Manager $wp_customize The WP_Customize_Manager instance.
	 */
	public function __construct( $wp_customize ) {
		$this->wp_customize = $wp_customize;
	}

	/**
	 * Register customizer settings.
	 */
	public function register() {
		// Add panels.
		$this->add_panels();

		// Add sections.
		$this->add_sections();

		// Add settings and controls.
		$this->add_settings_and_controls();
	}

	/**
	 * Add panels.
	 */
	private function add_panels() {
		// Theme Options panel.
		$this->wp_customize->add_panel(
			'aqualuxe_theme_options',
			array(
				'title'       => __( 'AquaLuxe Theme Options', 'aqualuxe' ),
				'description' => __( 'Theme options for AquaLuxe theme.', 'aqualuxe' ),
				'priority'    => 130,
			)
		);

		// Header panel.
		$this->wp_customize->add_panel(
			'aqualuxe_header',
			array(
				'title'       => __( 'Header', 'aqualuxe' ),
				'description' => __( 'Header options for AquaLuxe theme.', 'aqualuxe' ),
				'priority'    => 140,
				'panel'       => 'aqualuxe_theme_options',
			)
		);

		// Footer panel.
		$this->wp_customize->add_panel(
			'aqualuxe_footer',
			array(
				'title'       => __( 'Footer', 'aqualuxe' ),
				'description' => __( 'Footer options for AquaLuxe theme.', 'aqualuxe' ),
				'priority'    => 150,
				'panel'       => 'aqualuxe_theme_options',
			)
		);

		// Blog panel.
		$this->wp_customize->add_panel(
			'aqualuxe_blog',
			array(
				'title'       => __( 'Blog', 'aqualuxe' ),
				'description' => __( 'Blog options for AquaLuxe theme.', 'aqualuxe' ),
				'priority'    => 160,
				'panel'       => 'aqualuxe_theme_options',
			)
		);

		// WooCommerce panel.
		if ( class_exists( 'WooCommerce' ) ) {
			$this->wp_customize->add_panel(
				'aqualuxe_woocommerce',
				array(
					'title'       => __( 'WooCommerce', 'aqualuxe' ),
					'description' => __( 'WooCommerce options for AquaLuxe theme.', 'aqualuxe' ),
					'priority'    => 170,
					'panel'       => 'aqualuxe_theme_options',
				)
			);
		}
	}

	/**
	 * Add sections.
	 */
	private function add_sections() {
		// General section.
		$this->wp_customize->add_section(
			'aqualuxe_general',
			array(
				'title'       => __( 'General', 'aqualuxe' ),
				'description' => __( 'General options for AquaLuxe theme.', 'aqualuxe' ),
				'priority'    => 130,
				'panel'       => 'aqualuxe_theme_options',
			)
		);

		// Colors section.
		$this->wp_customize->add_section(
			'aqualuxe_colors',
			array(
				'title'       => __( 'Colors', 'aqualuxe' ),
				'description' => __( 'Color options for AquaLuxe theme.', 'aqualuxe' ),
				'priority'    => 140,
				'panel'       => 'aqualuxe_theme_options',
			)
		);

		// Typography section.
		$this->wp_customize->add_section(
			'aqualuxe_typography',
			array(
				'title'       => __( 'Typography', 'aqualuxe' ),
				'description' => __( 'Typography options for AquaLuxe theme.', 'aqualuxe' ),
				'priority'    => 150,
				'panel'       => 'aqualuxe_theme_options',
			)
		);

		// Layout section.
		$this->wp_customize->add_section(
			'aqualuxe_layout',
			array(
				'title'       => __( 'Layout', 'aqualuxe' ),
				'description' => __( 'Layout options for AquaLuxe theme.', 'aqualuxe' ),
				'priority'    => 160,
				'panel'       => 'aqualuxe_theme_options',
			)
		);

		// Header Top section.
		$this->wp_customize->add_section(
			'aqualuxe_header_top',
			array(
				'title'       => __( 'Header Top', 'aqualuxe' ),
				'description' => __( 'Header top options for AquaLuxe theme.', 'aqualuxe' ),
				'priority'    => 10,
				'panel'       => 'aqualuxe_header',
			)
		);

		// Header Main section.
		$this->wp_customize->add_section(
			'aqualuxe_header_main',
			array(
				'title'       => __( 'Header Main', 'aqualuxe' ),
				'description' => __( 'Header main options for AquaLuxe theme.', 'aqualuxe' ),
				'priority'    => 20,
				'panel'       => 'aqualuxe_header',
			)
		);

		// Header Bottom section.
		$this->wp_customize->add_section(
			'aqualuxe_header_bottom',
			array(
				'title'       => __( 'Header Bottom', 'aqualuxe' ),
				'description' => __( 'Header bottom options for AquaLuxe theme.', 'aqualuxe' ),
				'priority'    => 30,
				'panel'       => 'aqualuxe_header',
			)
		);

		// Footer Top section.
		$this->wp_customize->add_section(
			'aqualuxe_footer_top',
			array(
				'title'       => __( 'Footer Top', 'aqualuxe' ),
				'description' => __( 'Footer top options for AquaLuxe theme.', 'aqualuxe' ),
				'priority'    => 10,
				'panel'       => 'aqualuxe_footer',
			)
		);

		// Footer Main section.
		$this->wp_customize->add_section(
			'aqualuxe_footer_main',
			array(
				'title'       => __( 'Footer Main', 'aqualuxe' ),
				'description' => __( 'Footer main options for AquaLuxe theme.', 'aqualuxe' ),
				'priority'    => 20,
				'panel'       => 'aqualuxe_footer',
			)
		);

		// Footer Bottom section.
		$this->wp_customize->add_section(
			'aqualuxe_footer_bottom',
			array(
				'title'       => __( 'Footer Bottom', 'aqualuxe' ),
				'description' => __( 'Footer bottom options for AquaLuxe theme.', 'aqualuxe' ),
				'priority'    => 30,
				'panel'       => 'aqualuxe_footer',
			)
		);

		// Blog Archive section.
		$this->wp_customize->add_section(
			'aqualuxe_blog_archive',
			array(
				'title'       => __( 'Blog Archive', 'aqualuxe' ),
				'description' => __( 'Blog archive options for AquaLuxe theme.', 'aqualuxe' ),
				'priority'    => 10,
				'panel'       => 'aqualuxe_blog',
			)
		);

		// Blog Single section.
		$this->wp_customize->add_section(
			'aqualuxe_blog_single',
			array(
				'title'       => __( 'Blog Single', 'aqualuxe' ),
				'description' => __( 'Blog single options for AquaLuxe theme.', 'aqualuxe' ),
				'priority'    => 20,
				'panel'       => 'aqualuxe_blog',
			)
		);

		// WooCommerce Shop section.
		if ( class_exists( 'WooCommerce' ) ) {
			$this->wp_customize->add_section(
				'aqualuxe_woocommerce_shop',
				array(
					'title'       => __( 'Shop', 'aqualuxe' ),
					'description' => __( 'Shop options for AquaLuxe theme.', 'aqualuxe' ),
					'priority'    => 10,
					'panel'       => 'aqualuxe_woocommerce',
				)
			);

			// WooCommerce Product section.
			$this->wp_customize->add_section(
				'aqualuxe_woocommerce_product',
				array(
					'title'       => __( 'Product', 'aqualuxe' ),
					'description' => __( 'Product options for AquaLuxe theme.', 'aqualuxe' ),
					'priority'    => 20,
					'panel'       => 'aqualuxe_woocommerce',
				)
			);

			// WooCommerce Cart section.
			$this->wp_customize->add_section(
				'aqualuxe_woocommerce_cart',
				array(
					'title'       => __( 'Cart', 'aqualuxe' ),
					'description' => __( 'Cart options for AquaLuxe theme.', 'aqualuxe' ),
					'priority'    => 30,
					'panel'       => 'aqualuxe_woocommerce',
				)
			);

			// WooCommerce Checkout section.
			$this->wp_customize->add_section(
				'aqualuxe_woocommerce_checkout',
				array(
					'title'       => __( 'Checkout', 'aqualuxe' ),
					'description' => __( 'Checkout options for AquaLuxe theme.', 'aqualuxe' ),
					'priority'    => 40,
					'panel'       => 'aqualuxe_woocommerce',
				)
			);

			// WooCommerce Account section.
			$this->wp_customize->add_section(
				'aqualuxe_woocommerce_account',
				array(
					'title'       => __( 'Account', 'aqualuxe' ),
					'description' => __( 'Account options for AquaLuxe theme.', 'aqualuxe' ),
					'priority'    => 50,
					'panel'       => 'aqualuxe_woocommerce',
				)
			);
		}
	}

	/**
	 * Add settings and controls.
	 */
	private function add_settings_and_controls() {
		// General settings.
		$this->add_general_settings();

		// Color settings.
		$this->add_color_settings();

		// Typography settings.
		$this->add_typography_settings();

		// Layout settings.
		$this->add_layout_settings();

		// Header settings.
		$this->add_header_settings();

		// Footer settings.
		$this->add_footer_settings();

		// Blog settings.
		$this->add_blog_settings();

		// WooCommerce settings.
		if ( class_exists( 'WooCommerce' ) ) {
			$this->add_woocommerce_settings();
		}
	}

	/**
	 * Add general settings.
	 */
	private function add_general_settings() {
		// Container width.
		$this->wp_customize->add_setting(
			'aqualuxe_container_width',
			array(
				'default'           => 'default',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_container_width',
			array(
				'label'       => __( 'Container Width', 'aqualuxe' ),
				'description' => __( 'Select the container width.', 'aqualuxe' ),
				'section'     => 'aqualuxe_general',
				'type'        => 'select',
				'choices'     => array(
					'default' => __( 'Default (1280px)', 'aqualuxe' ),
					'narrow'  => __( 'Narrow (1024px)', 'aqualuxe' ),
					'wide'    => __( 'Wide (1536px)', 'aqualuxe' ),
					'full'    => __( 'Full Width', 'aqualuxe' ),
				),
			)
		);

		// Show breadcrumbs.
		$this->wp_customize->add_setting(
			'aqualuxe_show_breadcrumbs',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_breadcrumbs',
			array(
				'label'       => __( 'Show Breadcrumbs', 'aqualuxe' ),
				'description' => __( 'Show breadcrumbs on pages.', 'aqualuxe' ),
				'section'     => 'aqualuxe_general',
				'type'        => 'checkbox',
			)
		);

		// Breadcrumbs separator.
		$this->wp_customize->add_setting(
			'aqualuxe_breadcrumbs_separator',
			array(
				'default'           => '/',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_breadcrumbs_separator',
			array(
				'label'       => __( 'Breadcrumbs Separator', 'aqualuxe' ),
				'description' => __( 'Enter the breadcrumbs separator.', 'aqualuxe' ),
				'section'     => 'aqualuxe_general',
				'type'        => 'text',
			)
		);

		// Show dark mode toggle.
		$this->wp_customize->add_setting(
			'aqualuxe_show_dark_mode',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_dark_mode',
			array(
				'label'       => __( 'Show Dark Mode Toggle', 'aqualuxe' ),
				'description' => __( 'Show dark mode toggle in header.', 'aqualuxe' ),
				'section'     => 'aqualuxe_general',
				'type'        => 'checkbox',
			)
		);

		// Contact information.
		$this->wp_customize->add_setting(
			'aqualuxe_contact_phone',
			array(
				'default'           => '+1 (555) 123-4567',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_contact_phone',
			array(
				'label'       => __( 'Phone Number', 'aqualuxe' ),
				'description' => __( 'Enter the phone number.', 'aqualuxe' ),
				'section'     => 'aqualuxe_general',
				'type'        => 'text',
			)
		);

		$this->wp_customize->add_setting(
			'aqualuxe_contact_email',
			array(
				'default'           => 'info@aqualuxe.example.com',
				'sanitize_callback' => 'sanitize_email',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_contact_email',
			array(
				'label'       => __( 'Email Address', 'aqualuxe' ),
				'description' => __( 'Enter the email address.', 'aqualuxe' ),
				'section'     => 'aqualuxe_general',
				'type'        => 'email',
			)
		);

		// Social links.
		$this->wp_customize->add_setting(
			'aqualuxe_social_facebook',
			array(
				'default'           => '#',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_social_facebook',
			array(
				'label'       => __( 'Facebook URL', 'aqualuxe' ),
				'description' => __( 'Enter the Facebook URL.', 'aqualuxe' ),
				'section'     => 'aqualuxe_general',
				'type'        => 'url',
			)
		);

		$this->wp_customize->add_setting(
			'aqualuxe_social_twitter',
			array(
				'default'           => '#',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_social_twitter',
			array(
				'label'       => __( 'Twitter URL', 'aqualuxe' ),
				'description' => __( 'Enter the Twitter URL.', 'aqualuxe' ),
				'section'     => 'aqualuxe_general',
				'type'        => 'url',
			)
		);

		$this->wp_customize->add_setting(
			'aqualuxe_social_instagram',
			array(
				'default'           => '#',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_social_instagram',
			array(
				'label'       => __( 'Instagram URL', 'aqualuxe' ),
				'description' => __( 'Enter the Instagram URL.', 'aqualuxe' ),
				'section'     => 'aqualuxe_general',
				'type'        => 'url',
			)
		);

		$this->wp_customize->add_setting(
			'aqualuxe_social_youtube',
			array(
				'default'           => '#',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_social_youtube',
			array(
				'label'       => __( 'YouTube URL', 'aqualuxe' ),
				'description' => __( 'Enter the YouTube URL.', 'aqualuxe' ),
				'section'     => 'aqualuxe_general',
				'type'        => 'url',
			)
		);

		$this->wp_customize->add_setting(
			'aqualuxe_social_linkedin',
			array(
				'default'           => '#',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_social_linkedin',
			array(
				'label'       => __( 'LinkedIn URL', 'aqualuxe' ),
				'description' => __( 'Enter the LinkedIn URL.', 'aqualuxe' ),
				'section'     => 'aqualuxe_general',
				'type'        => 'url',
			)
		);

		// Copyright text.
		$this->wp_customize->add_setting(
			'aqualuxe_copyright_text',
			array(
				'default'           => '&copy; ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ) . '. All rights reserved.',
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_copyright_text',
			array(
				'label'       => __( 'Copyright Text', 'aqualuxe' ),
				'description' => __( 'Enter the copyright text.', 'aqualuxe' ),
				'section'     => 'aqualuxe_general',
				'type'        => 'textarea',
			)
		);
	}

	/**
	 * Add color settings.
	 */
	private function add_color_settings() {
		// Color scheme.
		$this->wp_customize->add_setting(
			'aqualuxe_color_scheme',
			array(
				'default'           => 'default',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_color_scheme',
			array(
				'label'       => __( 'Color Scheme', 'aqualuxe' ),
				'description' => __( 'Select the color scheme.', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors',
				'type'        => 'select',
				'choices'     => array(
					'default' => __( 'Default', 'aqualuxe' ),
					'blue'    => __( 'Blue', 'aqualuxe' ),
					'green'   => __( 'Green', 'aqualuxe' ),
					'purple'  => __( 'Purple', 'aqualuxe' ),
					'red'     => __( 'Red', 'aqualuxe' ),
					'custom'  => __( 'Custom', 'aqualuxe' ),
				),
			)
		);

		// Primary color.
		$this->wp_customize->add_setting(
			'aqualuxe_primary_color',
			array(
				'default'           => '#0072B5',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$this->wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$this->wp_customize,
				'aqualuxe_primary_color',
				array(
					'label'       => __( 'Primary Color', 'aqualuxe' ),
					'description' => __( 'Select the primary color.', 'aqualuxe' ),
					'section'     => 'aqualuxe_colors',
				)
			)
		);

		// Secondary color.
		$this->wp_customize->add_setting(
			'aqualuxe_secondary_color',
			array(
				'default'           => '#00A896',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$this->wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$this->wp_customize,
				'aqualuxe_secondary_color',
				array(
					'label'       => __( 'Secondary Color', 'aqualuxe' ),
					'description' => __( 'Select the secondary color.', 'aqualuxe' ),
					'section'     => 'aqualuxe_colors',
				)
			)
		);

		// Accent color.
		$this->wp_customize->add_setting(
			'aqualuxe_accent_color',
			array(
				'default'           => '#F8C630',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$this->wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$this->wp_customize,
				'aqualuxe_accent_color',
				array(
					'label'       => __( 'Accent Color', 'aqualuxe' ),
					'description' => __( 'Select the accent color.', 'aqualuxe' ),
					'section'     => 'aqualuxe_colors',
				)
			)
		);

		// Text color.
		$this->wp_customize->add_setting(
			'aqualuxe_text_color',
			array(
				'default'           => '#0A1828',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$this->wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$this->wp_customize,
				'aqualuxe_text_color',
				array(
					'label'       => __( 'Text Color', 'aqualuxe' ),
					'description' => __( 'Select the text color.', 'aqualuxe' ),
					'section'     => 'aqualuxe_colors',
				)
			)
		);

		// Background color.
		$this->wp_customize->add_setting(
			'aqualuxe_background_color',
			array(
				'default'           => '#F5F7F9',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$this->wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$this->wp_customize,
				'aqualuxe_background_color',
				array(
					'label'       => __( 'Background Color', 'aqualuxe' ),
					'description' => __( 'Select the background color.', 'aqualuxe' ),
					'section'     => 'aqualuxe_colors',
				)
			)
		);

		// Dark mode text color.
		$this->wp_customize->add_setting(
			'aqualuxe_dark_mode_text_color',
			array(
				'default'           => '#F5F7F9',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$this->wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$this->wp_customize,
				'aqualuxe_dark_mode_text_color',
				array(
					'label'       => __( 'Dark Mode Text Color', 'aqualuxe' ),
					'description' => __( 'Select the dark mode text color.', 'aqualuxe' ),
					'section'     => 'aqualuxe_colors',
				)
			)
		);

		// Dark mode background color.
		$this->wp_customize->add_setting(
			'aqualuxe_dark_mode_background_color',
			array(
				'default'           => '#0A1828',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$this->wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$this->wp_customize,
				'aqualuxe_dark_mode_background_color',
				array(
					'label'       => __( 'Dark Mode Background Color', 'aqualuxe' ),
					'description' => __( 'Select the dark mode background color.', 'aqualuxe' ),
					'section'     => 'aqualuxe_colors',
				)
			)
		);
	}

	/**
	 * Add typography settings.
	 */
	private function add_typography_settings() {
		// Typography.
		$this->wp_customize->add_setting(
			'aqualuxe_typography',
			array(
				'default'           => 'default',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_typography',
			array(
				'label'       => __( 'Typography', 'aqualuxe' ),
				'description' => __( 'Select the typography.', 'aqualuxe' ),
				'section'     => 'aqualuxe_typography',
				'type'        => 'select',
				'choices'     => array(
					'default' => __( 'Default', 'aqualuxe' ),
					'modern'  => __( 'Modern', 'aqualuxe' ),
					'classic' => __( 'Classic', 'aqualuxe' ),
					'custom'  => __( 'Custom', 'aqualuxe' ),
				),
			)
		);

		// Body font family.
		$this->wp_customize->add_setting(
			'aqualuxe_body_font_family',
			array(
				'default'           => 'Montserrat',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_body_font_family',
			array(
				'label'       => __( 'Body Font Family', 'aqualuxe' ),
				'description' => __( 'Enter the body font family.', 'aqualuxe' ),
				'section'     => 'aqualuxe_typography',
				'type'        => 'text',
			)
		);

		// Heading font family.
		$this->wp_customize->add_setting(
			'aqualuxe_heading_font_family',
			array(
				'default'           => 'Playfair Display',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_heading_font_family',
			array(
				'label'       => __( 'Heading Font Family', 'aqualuxe' ),
				'description' => __( 'Enter the heading font family.', 'aqualuxe' ),
				'section'     => 'aqualuxe_typography',
				'type'        => 'text',
			)
		);

		// Base font size.
		$this->wp_customize->add_setting(
			'aqualuxe_base_font_size',
			array(
				'default'           => '16px',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_base_font_size',
			array(
				'label'       => __( 'Base Font Size', 'aqualuxe' ),
				'description' => __( 'Enter the base font size.', 'aqualuxe' ),
				'section'     => 'aqualuxe_typography',
				'type'        => 'text',
			)
		);

		// Line height.
		$this->wp_customize->add_setting(
			'aqualuxe_line_height',
			array(
				'default'           => '1.5',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_line_height',
			array(
				'label'       => __( 'Line Height', 'aqualuxe' ),
				'description' => __( 'Enter the line height.', 'aqualuxe' ),
				'section'     => 'aqualuxe_typography',
				'type'        => 'text',
			)
		);
	}

	/**
	 * Add layout settings.
	 */
	private function add_layout_settings() {
		// Layout.
		$this->wp_customize->add_setting(
			'aqualuxe_layout',
			array(
				'default'           => 'right-sidebar',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_layout',
			array(
				'label'       => __( 'Layout', 'aqualuxe' ),
				'description' => __( 'Select the layout.', 'aqualuxe' ),
				'section'     => 'aqualuxe_layout',
				'type'        => 'select',
				'choices'     => array(
					'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
					'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
					'full-width'    => __( 'Full Width', 'aqualuxe' ),
				),
			)
		);

		// Front page layout.
		$this->wp_customize->add_setting(
			'aqualuxe_front_page_layout',
			array(
				'default'           => 'full-width',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_front_page_layout',
			array(
				'label'       => __( 'Front Page Layout', 'aqualuxe' ),
				'description' => __( 'Select the front page layout.', 'aqualuxe' ),
				'section'     => 'aqualuxe_layout',
				'type'        => 'select',
				'choices'     => array(
					'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
					'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
					'full-width'    => __( 'Full Width', 'aqualuxe' ),
				),
			)
		);

		// Blog layout.
		$this->wp_customize->add_setting(
			'aqualuxe_blog_layout',
			array(
				'default'           => 'right-sidebar',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_blog_layout',
			array(
				'label'       => __( 'Blog Layout', 'aqualuxe' ),
				'description' => __( 'Select the blog layout.', 'aqualuxe' ),
				'section'     => 'aqualuxe_layout',
				'type'        => 'select',
				'choices'     => array(
					'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
					'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
					'full-width'    => __( 'Full Width', 'aqualuxe' ),
				),
			)
		);

		// Archive layout.
		$this->wp_customize->add_setting(
			'aqualuxe_archive_layout',
			array(
				'default'           => 'right-sidebar',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_archive_layout',
			array(
				'label'       => __( 'Archive Layout', 'aqualuxe' ),
				'description' => __( 'Select the archive layout.', 'aqualuxe' ),
				'section'     => 'aqualuxe_layout',
				'type'        => 'select',
				'choices'     => array(
					'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
					'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
					'full-width'    => __( 'Full Width', 'aqualuxe' ),
				),
			)
		);

		// Search layout.
		$this->wp_customize->add_setting(
			'aqualuxe_search_layout',
			array(
				'default'           => 'right-sidebar',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_search_layout',
			array(
				'label'       => __( 'Search Layout', 'aqualuxe' ),
				'description' => __( 'Select the search layout.', 'aqualuxe' ),
				'section'     => 'aqualuxe_layout',
				'type'        => 'select',
				'choices'     => array(
					'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
					'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
					'full-width'    => __( 'Full Width', 'aqualuxe' ),
				),
			)
		);

		// WooCommerce shop layout.
		if ( class_exists( 'WooCommerce' ) ) {
			$this->wp_customize->add_setting(
				'aqualuxe_shop_layout',
				array(
					'default'           => 'left-sidebar',
					'sanitize_callback' => array( $this, 'sanitize_select' ),
				)
			);

			$this->wp_customize->add_control(
				'aqualuxe_shop_layout',
				array(
					'label'       => __( 'Shop Layout', 'aqualuxe' ),
					'description' => __( 'Select the shop layout.', 'aqualuxe' ),
					'section'     => 'aqualuxe_layout',
					'type'        => 'select',
					'choices'     => array(
						'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
						'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
						'full-width'    => __( 'Full Width', 'aqualuxe' ),
					),
				)
			);

			// WooCommerce product layout.
			$this->wp_customize->add_setting(
				'aqualuxe_product_layout',
				array(
					'default'           => 'full-width',
					'sanitize_callback' => array( $this, 'sanitize_select' ),
				)
			);

			$this->wp_customize->add_control(
				'aqualuxe_product_layout',
				array(
					'label'       => __( 'Product Layout', 'aqualuxe' ),
					'description' => __( 'Select the product layout.', 'aqualuxe' ),
					'section'     => 'aqualuxe_layout',
					'type'        => 'select',
					'choices'     => array(
						'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
						'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
						'full-width'    => __( 'Full Width', 'aqualuxe' ),
					),
				)
			);

			// WooCommerce product archive layout.
			$this->wp_customize->add_setting(
				'aqualuxe_product_archive_layout',
				array(
					'default'           => 'left-sidebar',
					'sanitize_callback' => array( $this, 'sanitize_select' ),
				)
			);

			$this->wp_customize->add_control(
				'aqualuxe_product_archive_layout',
				array(
					'label'       => __( 'Product Archive Layout', 'aqualuxe' ),
					'description' => __( 'Select the product archive layout.', 'aqualuxe' ),
					'section'     => 'aqualuxe_layout',
					'type'        => 'select',
					'choices'     => array(
						'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
						'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
						'full-width'    => __( 'Full Width', 'aqualuxe' ),
					),
				)
			);
		}
	}

	/**
	 * Add header settings.
	 */
	private function add_header_settings() {
		// Header style.
		$this->wp_customize->add_setting(
			'aqualuxe_header_style',
			array(
				'default'           => 'default',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_header_style',
			array(
				'label'       => __( 'Header Style', 'aqualuxe' ),
				'description' => __( 'Select the header style.', 'aqualuxe' ),
				'section'     => 'aqualuxe_header_main',
				'type'        => 'select',
				'choices'     => array(
					'default' => __( 'Default', 'aqualuxe' ),
					'centered' => __( 'Centered', 'aqualuxe' ),
					'split'   => __( 'Split', 'aqualuxe' ),
					'minimal' => __( 'Minimal', 'aqualuxe' ),
				),
			)
		);

		// Sticky header.
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
				'label'       => __( 'Sticky Header', 'aqualuxe' ),
				'description' => __( 'Enable sticky header.', 'aqualuxe' ),
				'section'     => 'aqualuxe_header_main',
				'type'        => 'checkbox',
			)
		);

		// Show header top.
		$this->wp_customize->add_setting(
			'aqualuxe_show_header_top',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_header_top',
			array(
				'label'       => __( 'Show Header Top', 'aqualuxe' ),
				'description' => __( 'Show header top section.', 'aqualuxe' ),
				'section'     => 'aqualuxe_header_top',
				'type'        => 'checkbox',
			)
		);

		// Show header contact info.
		$this->wp_customize->add_setting(
			'aqualuxe_show_header_contact',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_header_contact',
			array(
				'label'       => __( 'Show Header Contact Info', 'aqualuxe' ),
				'description' => __( 'Show contact information in header top.', 'aqualuxe' ),
				'section'     => 'aqualuxe_header_top',
				'type'        => 'checkbox',
			)
		);

		// Show header social icons.
		$this->wp_customize->add_setting(
			'aqualuxe_show_header_social',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_header_social',
			array(
				'label'       => __( 'Show Header Social Icons', 'aqualuxe' ),
				'description' => __( 'Show social icons in header top.', 'aqualuxe' ),
				'section'     => 'aqualuxe_header_top',
				'type'        => 'checkbox',
			)
		);

		// Show header account links.
		$this->wp_customize->add_setting(
			'aqualuxe_show_header_account',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_header_account',
			array(
				'label'       => __( 'Show Header Account Links', 'aqualuxe' ),
				'description' => __( 'Show account links in header top.', 'aqualuxe' ),
				'section'     => 'aqualuxe_header_top',
				'type'        => 'checkbox',
			)
		);

		// Show header search.
		$this->wp_customize->add_setting(
			'aqualuxe_show_header_search',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_header_search',
			array(
				'label'       => __( 'Show Header Search', 'aqualuxe' ),
				'description' => __( 'Show search in header.', 'aqualuxe' ),
				'section'     => 'aqualuxe_header_main',
				'type'        => 'checkbox',
			)
		);

		// Show header cart.
		$this->wp_customize->add_setting(
			'aqualuxe_show_header_cart',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_header_cart',
			array(
				'label'       => __( 'Show Header Cart', 'aqualuxe' ),
				'description' => __( 'Show cart in header.', 'aqualuxe' ),
				'section'     => 'aqualuxe_header_main',
				'type'        => 'checkbox',
			)
		);

		// Show header wishlist.
		$this->wp_customize->add_setting(
			'aqualuxe_show_header_wishlist',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_header_wishlist',
			array(
				'label'       => __( 'Show Header Wishlist', 'aqualuxe' ),
				'description' => __( 'Show wishlist in header.', 'aqualuxe' ),
				'section'     => 'aqualuxe_header_main',
				'type'        => 'checkbox',
			)
		);

		// Show header bottom.
		$this->wp_customize->add_setting(
			'aqualuxe_show_header_bottom',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_header_bottom',
			array(
				'label'       => __( 'Show Header Bottom', 'aqualuxe' ),
				'description' => __( 'Show header bottom section.', 'aqualuxe' ),
				'section'     => 'aqualuxe_header_bottom',
				'type'        => 'checkbox',
			)
		);
	}

	/**
	 * Add footer settings.
	 */
	private function add_footer_settings() {
		// Footer style.
		$this->wp_customize->add_setting(
			'aqualuxe_footer_style',
			array(
				'default'           => 'default',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_footer_style',
			array(
				'label'       => __( 'Footer Style', 'aqualuxe' ),
				'description' => __( 'Select the footer style.', 'aqualuxe' ),
				'section'     => 'aqualuxe_footer_main',
				'type'        => 'select',
				'choices'     => array(
					'default' => __( 'Default', 'aqualuxe' ),
					'centered' => __( 'Centered', 'aqualuxe' ),
					'minimal' => __( 'Minimal', 'aqualuxe' ),
				),
			)
		);

		// Show footer widgets.
		$this->wp_customize->add_setting(
			'aqualuxe_show_footer_widgets',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_footer_widgets',
			array(
				'label'       => __( 'Show Footer Widgets', 'aqualuxe' ),
				'description' => __( 'Show footer widgets section.', 'aqualuxe' ),
				'section'     => 'aqualuxe_footer_main',
				'type'        => 'checkbox',
			)
		);

		// Show footer newsletter.
		$this->wp_customize->add_setting(
			'aqualuxe_show_footer_newsletter',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_footer_newsletter',
			array(
				'label'       => __( 'Show Footer Newsletter', 'aqualuxe' ),
				'description' => __( 'Show newsletter in footer top.', 'aqualuxe' ),
				'section'     => 'aqualuxe_footer_top',
				'type'        => 'checkbox',
			)
		);

		// Newsletter title.
		$this->wp_customize->add_setting(
			'aqualuxe_newsletter_title',
			array(
				'default'           => __( 'Subscribe to our newsletter', 'aqualuxe' ),
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_newsletter_title',
			array(
				'label'       => __( 'Newsletter Title', 'aqualuxe' ),
				'description' => __( 'Enter the newsletter title.', 'aqualuxe' ),
				'section'     => 'aqualuxe_footer_top',
				'type'        => 'text',
			)
		);

		// Newsletter description.
		$this->wp_customize->add_setting(
			'aqualuxe_newsletter_description',
			array(
				'default'           => __( 'Get the latest news and updates from AquaLuxe.', 'aqualuxe' ),
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_newsletter_description',
			array(
				'label'       => __( 'Newsletter Description', 'aqualuxe' ),
				'description' => __( 'Enter the newsletter description.', 'aqualuxe' ),
				'section'     => 'aqualuxe_footer_top',
				'type'        => 'text',
			)
		);
	}

	/**
	 * Add blog settings.
	 */
	private function add_blog_settings() {
		// Blog archive layout.
		$this->wp_customize->add_setting(
			'aqualuxe_blog_archive_layout',
			array(
				'default'           => 'grid',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_blog_archive_layout',
			array(
				'label'       => __( 'Blog Archive Layout', 'aqualuxe' ),
				'description' => __( 'Select the blog archive layout.', 'aqualuxe' ),
				'section'     => 'aqualuxe_blog_archive',
				'type'        => 'select',
				'choices'     => array(
					'grid'    => __( 'Grid', 'aqualuxe' ),
					'list'    => __( 'List', 'aqualuxe' ),
					'masonry' => __( 'Masonry', 'aqualuxe' ),
				),
			)
		);

		// Blog archive columns.
		$this->wp_customize->add_setting(
			'aqualuxe_blog_archive_columns',
			array(
				'default'           => '3',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_blog_archive_columns',
			array(
				'label'       => __( 'Blog Archive Columns', 'aqualuxe' ),
				'description' => __( 'Select the number of columns for blog archive.', 'aqualuxe' ),
				'section'     => 'aqualuxe_blog_archive',
				'type'        => 'select',
				'choices'     => array(
					'1' => __( '1 Column', 'aqualuxe' ),
					'2' => __( '2 Columns', 'aqualuxe' ),
					'3' => __( '3 Columns', 'aqualuxe' ),
					'4' => __( '4 Columns', 'aqualuxe' ),
				),
			)
		);

		// Excerpt length.
		$this->wp_customize->add_setting(
			'aqualuxe_excerpt_length',
			array(
				'default'           => 55,
				'sanitize_callback' => 'absint',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_excerpt_length',
			array(
				'label'       => __( 'Excerpt Length', 'aqualuxe' ),
				'description' => __( 'Enter the excerpt length.', 'aqualuxe' ),
				'section'     => 'aqualuxe_blog_archive',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 5,
				),
			)
		);

		// Excerpt more.
		$this->wp_customize->add_setting(
			'aqualuxe_excerpt_more',
			array(
				'default'           => '&hellip;',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_excerpt_more',
			array(
				'label'       => __( 'Excerpt More', 'aqualuxe' ),
				'description' => __( 'Enter the excerpt more text.', 'aqualuxe' ),
				'section'     => 'aqualuxe_blog_archive',
				'type'        => 'text',
			)
		);

		// Show post author.
		$this->wp_customize->add_setting(
			'aqualuxe_show_post_author',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_post_author',
			array(
				'label'       => __( 'Show Post Author', 'aqualuxe' ),
				'description' => __( 'Show post author in meta.', 'aqualuxe' ),
				'section'     => 'aqualuxe_blog_single',
				'type'        => 'checkbox',
			)
		);

		// Show post date.
		$this->wp_customize->add_setting(
			'aqualuxe_show_post_date',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_post_date',
			array(
				'label'       => __( 'Show Post Date', 'aqualuxe' ),
				'description' => __( 'Show post date in meta.', 'aqualuxe' ),
				'section'     => 'aqualuxe_blog_single',
				'type'        => 'checkbox',
			)
		);

		// Show post categories.
		$this->wp_customize->add_setting(
			'aqualuxe_show_post_categories',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_post_categories',
			array(
				'label'       => __( 'Show Post Categories', 'aqualuxe' ),
				'description' => __( 'Show post categories in meta.', 'aqualuxe' ),
				'section'     => 'aqualuxe_blog_single',
				'type'        => 'checkbox',
			)
		);

		// Show post tags.
		$this->wp_customize->add_setting(
			'aqualuxe_show_post_tags',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_post_tags',
			array(
				'label'       => __( 'Show Post Tags', 'aqualuxe' ),
				'description' => __( 'Show post tags in meta.', 'aqualuxe' ),
				'section'     => 'aqualuxe_blog_single',
				'type'        => 'checkbox',
			)
		);

		// Show post comments.
		$this->wp_customize->add_setting(
			'aqualuxe_show_post_comments',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_post_comments',
			array(
				'label'       => __( 'Show Post Comments', 'aqualuxe' ),
				'description' => __( 'Show post comments in meta.', 'aqualuxe' ),
				'section'     => 'aqualuxe_blog_single',
				'type'        => 'checkbox',
			)
		);

		// Show author bio.
		$this->wp_customize->add_setting(
			'aqualuxe_show_author_bio',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_author_bio',
			array(
				'label'       => __( 'Show Author Bio', 'aqualuxe' ),
				'description' => __( 'Show author bio on single post.', 'aqualuxe' ),
				'section'     => 'aqualuxe_blog_single',
				'type'        => 'checkbox',
			)
		);

		// Show post navigation.
		$this->wp_customize->add_setting(
			'aqualuxe_show_post_navigation',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_post_navigation',
			array(
				'label'       => __( 'Show Post Navigation', 'aqualuxe' ),
				'description' => __( 'Show post navigation on single post.', 'aqualuxe' ),
				'section'     => 'aqualuxe_blog_single',
				'type'        => 'checkbox',
			)
		);

		// Show related posts.
		$this->wp_customize->add_setting(
			'aqualuxe_show_related_posts',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_related_posts',
			array(
				'label'       => __( 'Show Related Posts', 'aqualuxe' ),
				'description' => __( 'Show related posts on single post.', 'aqualuxe' ),
				'section'     => 'aqualuxe_blog_single',
				'type'        => 'checkbox',
			)
		);
	}

	/**
	 * Add WooCommerce settings.
	 */
	private function add_woocommerce_settings() {
		// Shop columns.
		$this->wp_customize->add_setting(
			'aqualuxe_shop_columns',
			array(
				'default'           => '4',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_shop_columns',
			array(
				'label'       => __( 'Shop Columns', 'aqualuxe' ),
				'description' => __( 'Select the number of columns for shop.', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_shop',
				'type'        => 'select',
				'choices'     => array(
					'2' => __( '2 Columns', 'aqualuxe' ),
					'3' => __( '3 Columns', 'aqualuxe' ),
					'4' => __( '4 Columns', 'aqualuxe' ),
					'5' => __( '5 Columns', 'aqualuxe' ),
				),
			)
		);

		// Products per page.
		$this->wp_customize->add_setting(
			'aqualuxe_products_per_page',
			array(
				'default'           => 12,
				'sanitize_callback' => 'absint',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_products_per_page',
			array(
				'label'       => __( 'Products Per Page', 'aqualuxe' ),
				'description' => __( 'Enter the number of products per page.', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_shop',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 1,
					'max'  => 100,
					'step' => 1,
				),
			)
		);

		// Show shop filters.
		$this->wp_customize->add_setting(
			'aqualuxe_show_shop_filters',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_shop_filters',
			array(
				'label'       => __( 'Show Shop Filters', 'aqualuxe' ),
				'description' => __( 'Show filters on shop page.', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_shop',
				'type'        => 'checkbox',
			)
		);

		// Show product badges.
		$this->wp_customize->add_setting(
			'aqualuxe_show_product_badges',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_product_badges',
			array(
				'label'       => __( 'Show Product Badges', 'aqualuxe' ),
				'description' => __( 'Show badges on products.', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_product',
				'type'        => 'checkbox',
			)
		);

		// Show related products.
		$this->wp_customize->add_setting(
			'aqualuxe_show_related_products',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_related_products',
			array(
				'label'       => __( 'Show Related Products', 'aqualuxe' ),
				'description' => __( 'Show related products on single product.', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_product',
				'type'        => 'checkbox',
			)
		);

		// Show upsell products.
		$this->wp_customize->add_setting(
			'aqualuxe_show_upsell_products',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_upsell_products',
			array(
				'label'       => __( 'Show Upsell Products', 'aqualuxe' ),
				'description' => __( 'Show upsell products on single product.', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_product',
				'type'        => 'checkbox',
			)
		);

		// Show product sharing.
		$this->wp_customize->add_setting(
			'aqualuxe_show_product_sharing',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_product_sharing',
			array(
				'label'       => __( 'Show Product Sharing', 'aqualuxe' ),
				'description' => __( 'Show sharing buttons on single product.', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_product',
				'type'        => 'checkbox',
			)
		);

		// Cross-sells columns.
		$this->wp_customize->add_setting(
			'aqualuxe_cross_sells_columns',
			array(
				'default'           => '2',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_cross_sells_columns',
			array(
				'label'       => __( 'Cross-Sells Columns', 'aqualuxe' ),
				'description' => __( 'Select the number of columns for cross-sells.', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_cart',
				'type'        => 'select',
				'choices'     => array(
					'1' => __( '1 Column', 'aqualuxe' ),
					'2' => __( '2 Columns', 'aqualuxe' ),
					'3' => __( '3 Columns', 'aqualuxe' ),
					'4' => __( '4 Columns', 'aqualuxe' ),
				),
			)
		);

		// Distraction free checkout.
		$this->wp_customize->add_setting(
			'aqualuxe_distraction_free_checkout',
			array(
				'default'           => false,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_distraction_free_checkout',
			array(
				'label'       => __( 'Distraction Free Checkout', 'aqualuxe' ),
				'description' => __( 'Enable distraction free checkout.', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_checkout',
				'type'        => 'checkbox',
			)
		);

		// Show account dashboard welcome.
		$this->wp_customize->add_setting(
			'aqualuxe_show_account_dashboard_welcome',
			array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_show_account_dashboard_welcome',
			array(
				'label'       => __( 'Show Account Dashboard Welcome', 'aqualuxe' ),
				'description' => __( 'Show welcome message on account dashboard.', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_account',
				'type'        => 'checkbox',
			)
		);

		// Account dashboard welcome text.
		$this->wp_customize->add_setting(
			'aqualuxe_account_dashboard_welcome_text',
			array(
				'default'           => __( 'Welcome to your account dashboard. Here you can manage your orders, addresses, account details, and more.', 'aqualuxe' ),
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$this->wp_customize->add_control(
			'aqualuxe_account_dashboard_welcome_text',
			array(
				'label'       => __( 'Account Dashboard Welcome Text', 'aqualuxe' ),
				'description' => __( 'Enter the account dashboard welcome text.', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_account',
				'type'        => 'textarea',
			)
		);
	}

	/**
	 * Sanitize checkbox.
	 *
	 * @param bool $checked Whether the checkbox is checked.
	 * @return bool Whether the checkbox is checked.
	 */
	public function sanitize_checkbox( $checked ) {
		return ( isset( $checked ) && true === $checked ) ? true : false;
	}

	/**
	 * Sanitize select.
	 *
	 * @param string $input   The input from the setting.
	 * @param object $setting The selected setting.
	 * @return string The sanitized input.
	 */
	public function sanitize_select( $input, $setting ) {
		// Get the list of choices from the control associated with the setting.
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// Return input if valid or return default option.
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}
}