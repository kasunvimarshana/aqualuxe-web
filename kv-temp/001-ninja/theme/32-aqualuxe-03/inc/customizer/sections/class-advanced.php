<?php
/**
 * AquaLuxe Theme Customizer - Advanced Settings
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Customizer\Sections;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Advanced Settings
 */
class Advanced {

	/**
	 * Constructor
	 */
	public function __construct( $wp_customize ) {
		$this->register_advanced_section( $wp_customize );
	}

	/**
	 * Register Advanced Section
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function register_advanced_section( $wp_customize ) {
		// Advanced Section.
		$wp_customize->add_section(
			'aqualuxe_advanced_section',
			array(
				'title'    => __( 'Advanced Settings', 'aqualuxe' ),
				'priority' => 100,
				'panel'    => 'aqualuxe_theme_options',
			)
		);

		// Custom CSS.
		$wp_customize->add_setting(
			'aqualuxe_custom_css',
			array(
				'default'           => '',
				'sanitize_callback' => 'wp_strip_all_tags',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_custom_css',
			array(
				'label'       => __( 'Custom CSS', 'aqualuxe' ),
				'description' => __( 'Add your custom CSS here. It will be included in the site header.', 'aqualuxe' ),
				'section'     => 'aqualuxe_advanced_section',
				'type'        => 'textarea',
				'priority'    => 10,
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
				'label'       => __( 'Custom JavaScript', 'aqualuxe' ),
				'description' => __( 'Add your custom JavaScript here. It will be included in the site footer.', 'aqualuxe' ),
				'section'     => 'aqualuxe_advanced_section',
				'type'        => 'textarea',
				'priority'    => 20,
			)
		);

		// Google Analytics.
		$wp_customize->add_setting(
			'aqualuxe_google_analytics',
			array(
				'default'           => '',
				'sanitize_callback' => 'wp_strip_all_tags',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_google_analytics',
			array(
				'label'       => __( 'Google Analytics', 'aqualuxe' ),
				'description' => __( 'Enter your Google Analytics tracking ID (e.g., UA-XXXXX-Y or G-XXXXXXXX).', 'aqualuxe' ),
				'section'     => 'aqualuxe_advanced_section',
				'type'        => 'text',
				'priority'    => 30,
			)
		);

		// Disable Gutenberg.
		$wp_customize->add_setting(
			'aqualuxe_disable_gutenberg',
			array(
				'default'           => false,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_disable_gutenberg',
			array(
				'label'       => __( 'Disable Gutenberg Editor', 'aqualuxe' ),
				'description' => __( 'Check this to disable the Gutenberg editor and use the classic editor.', 'aqualuxe' ),
				'section'     => 'aqualuxe_advanced_section',
				'type'        => 'checkbox',
				'priority'    => 40,
			)
		);

		// Disable Emojis.
		$wp_customize->add_setting(
			'aqualuxe_disable_emojis',
			array(
				'default'           => false,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_disable_emojis',
			array(
				'label'       => __( 'Disable WordPress Emojis', 'aqualuxe' ),
				'description' => __( 'Check this to disable WordPress emojis script for better performance.', 'aqualuxe' ),
				'section'     => 'aqualuxe_advanced_section',
				'type'        => 'checkbox',
				'priority'    => 50,
			)
		);

		// Disable Embeds.
		$wp_customize->add_setting(
			'aqualuxe_disable_embeds',
			array(
				'default'           => false,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_disable_embeds',
			array(
				'label'       => __( 'Disable WordPress Embeds', 'aqualuxe' ),
				'description' => __( 'Check this to disable WordPress embeds script for better performance.', 'aqualuxe' ),
				'section'     => 'aqualuxe_advanced_section',
				'type'        => 'checkbox',
				'priority'    => 60,
			)
		);

		// Disable XML-RPC.
		$wp_customize->add_setting(
			'aqualuxe_disable_xmlrpc',
			array(
				'default'           => false,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_disable_xmlrpc',
			array(
				'label'       => __( 'Disable XML-RPC', 'aqualuxe' ),
				'description' => __( 'Check this to disable XML-RPC functionality for better security.', 'aqualuxe' ),
				'section'     => 'aqualuxe_advanced_section',
				'type'        => 'checkbox',
				'priority'    => 70,
			)
		);

		// Remove Version Info.
		$wp_customize->add_setting(
			'aqualuxe_remove_version',
			array(
				'default'           => false,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_remove_version',
			array(
				'label'       => __( 'Remove WordPress Version', 'aqualuxe' ),
				'description' => __( 'Check this to remove WordPress version from HTML source for better security.', 'aqualuxe' ),
				'section'     => 'aqualuxe_advanced_section',
				'type'        => 'checkbox',
				'priority'    => 80,
			)
		);
	}
}