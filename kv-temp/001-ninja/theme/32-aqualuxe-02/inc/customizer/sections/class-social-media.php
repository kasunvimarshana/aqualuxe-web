<?php
/**
 * AquaLuxe Social Media Customizer Section
 *
 * @package AquaLuxe
 * @subpackage Customizer
 */

namespace AquaLuxe\Customizer\Sections;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Social Media Customizer Section
 */
class Social_Media {

	/**
	 * Constructor
	 */
	public function __construct( $wp_customize ) {
		$this->register_social_media_section( $wp_customize );
	}

	/**
	 * Register Social Media Section
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function register_social_media_section( $wp_customize ) {
		// Social Media Section.
		$wp_customize->add_section(
			'aqualuxe_social_media_section',
			array(
				'title'    => __( 'Social Media', 'aqualuxe' ),
				'priority' => 70,
				'panel'    => 'aqualuxe_theme_options',
			)
		);

		// Social Media Display.
		$wp_customize->add_setting(
			'aqualuxe_social_media_display',
			array(
				'default'           => array( 'header', 'footer' ),
				'sanitize_callback' => 'aqualuxe_sanitize_multi_choices',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_social_media_display',
			array(
				'label'    => __( 'Display Social Media Icons', 'aqualuxe' ),
				'section'  => 'aqualuxe_social_media_section',
				'type'     => 'select',
				'choices'  => array(
					'header' => __( 'Header', 'aqualuxe' ),
					'footer' => __( 'Footer', 'aqualuxe' ),
					'both'   => __( 'Both', 'aqualuxe' ),
					'none'   => __( 'None', 'aqualuxe' ),
				),
				'priority' => 10,
			)
		);

		// Social Media Icon Size.
		$wp_customize->add_setting(
			'aqualuxe_social_media_icon_size',
			array(
				'default'           => 'medium',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_social_media_icon_size',
			array(
				'label'    => __( 'Icon Size', 'aqualuxe' ),
				'section'  => 'aqualuxe_social_media_section',
				'type'     => 'select',
				'choices'  => array(
					'small'  => __( 'Small', 'aqualuxe' ),
					'medium' => __( 'Medium', 'aqualuxe' ),
					'large'  => __( 'Large', 'aqualuxe' ),
				),
				'priority' => 20,
			)
		);

		// Social Media Icon Style.
		$wp_customize->add_setting(
			'aqualuxe_social_media_icon_style',
			array(
				'default'           => 'filled',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_social_media_icon_style',
			array(
				'label'    => __( 'Icon Style', 'aqualuxe' ),
				'section'  => 'aqualuxe_social_media_section',
				'type'     => 'select',
				'choices'  => array(
					'filled'    => __( 'Filled', 'aqualuxe' ),
					'outlined'  => __( 'Outlined', 'aqualuxe' ),
					'rounded'   => __( 'Rounded', 'aqualuxe' ),
					'shadowed'  => __( 'Shadowed', 'aqualuxe' ),
				),
				'priority' => 30,
			)
		);

		// Facebook.
		$wp_customize->add_setting(
			'aqualuxe_facebook_url',
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_facebook_url',
			array(
				'label'    => __( 'Facebook URL', 'aqualuxe' ),
				'section'  => 'aqualuxe_social_media_section',
				'type'     => 'url',
				'priority' => 40,
			)
		);

		// Twitter.
		$wp_customize->add_setting(
			'aqualuxe_twitter_url',
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_twitter_url',
			array(
				'label'    => __( 'Twitter URL', 'aqualuxe' ),
				'section'  => 'aqualuxe_social_media_section',
				'type'     => 'url',
				'priority' => 50,
			)
		);

		// Instagram.
		$wp_customize->add_setting(
			'aqualuxe_instagram_url',
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_instagram_url',
			array(
				'label'    => __( 'Instagram URL', 'aqualuxe' ),
				'section'  => 'aqualuxe_social_media_section',
				'type'     => 'url',
				'priority' => 60,
			)
		);

		// LinkedIn.
		$wp_customize->add_setting(
			'aqualuxe_linkedin_url',
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_linkedin_url',
			array(
				'label'    => __( 'LinkedIn URL', 'aqualuxe' ),
				'section'  => 'aqualuxe_social_media_section',
				'type'     => 'url',
				'priority' => 70,
			)
		);

		// YouTube.
		$wp_customize->add_setting(
			'aqualuxe_youtube_url',
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_youtube_url',
			array(
				'label'    => __( 'YouTube URL', 'aqualuxe' ),
				'section'  => 'aqualuxe_social_media_section',
				'type'     => 'url',
				'priority' => 80,
			)
		);

		// Pinterest.
		$wp_customize->add_setting(
			'aqualuxe_pinterest_url',
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_pinterest_url',
			array(
				'label'    => __( 'Pinterest URL', 'aqualuxe' ),
				'section'  => 'aqualuxe_social_media_section',
				'type'     => 'url',
				'priority' => 90,
			)
		);

		// TikTok.
		$wp_customize->add_setting(
			'aqualuxe_tiktok_url',
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_tiktok_url',
			array(
				'label'    => __( 'TikTok URL', 'aqualuxe' ),
				'section'  => 'aqualuxe_social_media_section',
				'type'     => 'url',
				'priority' => 100,
			)
		);
	}
}