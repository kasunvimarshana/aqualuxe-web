<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register( $wp_customize ) {
	// Change default section titles
	$wp_customize->get_section( 'title_tagline' )->title = __( 'Site Identity', 'aqualuxe' );
	$wp_customize->get_section( 'colors' )->title = __( 'Color Scheme', 'aqualuxe' );
	$wp_customize->get_section( 'header_image' )->title = __( 'Header Media', 'aqualuxe' );
	$wp_customize->get_section( 'background_image' )->title = __( 'Background Image', 'aqualuxe' );
	
	// Change default control labels
	$wp_customize->get_control( 'blogname' )->label = __( 'Site Title', 'aqualuxe' );
	$wp_customize->get_control( 'blogdescription' )->label = __( 'Tagline', 'aqualuxe' );
	$wp_customize->get_control( 'header_textcolor' )->label = __( 'Header Text Color', 'aqualuxe' );
	
	// Add postMessage support for site title and description.
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'aqualuxe_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'aqualuxe_customize_partial_blogdescription',
			)
		);
	}

	// Add theme options panel
	$wp_customize->add_panel(
		'aqualuxe_options',
		array(
			'title'       => __( 'AquaLuxe Options', 'aqualuxe' ),
			'description' => __( 'Customize the AquaLuxe theme settings.', 'aqualuxe' ),
			'priority'    => 160,
		)
	);

	// Add homepage section
	$wp_customize->add_section(
		'aqualuxe_homepage',
		array(
			'title'       => __( 'Homepage', 'aqualuxe' ),
			'description' => __( 'Customize the homepage settings.', 'aqualuxe' ),
			'panel'       => 'aqualuxe_options',
			'priority'    => 10,
		)
	);

	// Hero title setting
	$wp_customize->add_setting(
		'hero_title',
		array(
			'default'           => __( 'Premium Ornamental Fish for Discerning Collectors', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'hero_title',
		array(
			'label'       => __( 'Hero Title', 'aqualuxe' ),
			'description' => __( 'The main title displayed in the hero section.', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage',
			'type'        => 'text',
		)
	);

	// Hero description setting
	$wp_customize->add_setting(
		'hero_description',
		array(
			'default'           => __( 'Discover our exclusive collection of rare and exotic aquatic species, expertly bred and sustainably sourced for the most discerning collectors.', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'hero_description',
		array(
			'label'       => __( 'Hero Description', 'aqualuxe' ),
			'description' => __( 'The description text displayed in the hero section.', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage',
			'type'        => 'textarea',
		)
	);

	// Hero button text setting
	$wp_customize->add_setting(
		'hero_button_text',
		array(
			'default'           => __( 'Explore Our Collection', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'hero_button_text',
		array(
			'label'       => __( 'Hero Button Text', 'aqualuxe' ),
			'description' => __( 'The text displayed on the hero button.', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage',
			'type'        => 'text',
		)
	);

	// Hero button URL setting
	$wp_customize->add_setting(
		'hero_button_url',
		array(
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'hero_button_url',
		array(
			'label'       => __( 'Hero Button URL', 'aqualuxe' ),
			'description' => __( 'The URL the hero button links to.', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage',
			'type'        => 'url',
		)
	);

	// Featured products title setting
	$wp_customize->add_setting(
		'featured_products_title',
		array(
			'default'           => __( 'Featured Products', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'featured_products_title',
		array(
			'label'       => __( 'Featured Products Title', 'aqualuxe' ),
			'description' => __( 'The title for the featured products section.', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage',
			'type'        => 'text',
		)
	);

	// Testimonials title setting
	$wp_customize->add_setting(
		'testimonials_title',
		array(
			'default'           => __( 'What Our Customers Say', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'testimonials_title',
		array(
			'label'       => __( 'Testimonials Title', 'aqualuxe' ),
			'description' => __( 'The title for the testimonials section.', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage',
			'type'        => 'text',
		)
	);

	// Newsletter title setting
	$wp_customize->add_setting(
		'newsletter_title',
		array(
			'default'           => __( 'Join Our Community', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'newsletter_title',
		array(
			'label'       => __( 'Newsletter Title', 'aqualuxe' ),
			'description' => __( 'The title for the newsletter section.', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage',
			'type'        => 'text',
		)
	);

	// Newsletter description setting
	$wp_customize->add_setting(
		'newsletter_description',
		array(
			'default'           => __( 'Subscribe to our newsletter for exclusive offers, care tips, and the latest arrivals.', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'newsletter_description',
		array(
			'label'       => __( 'Newsletter Description', 'aqualuxe' ),
			'description' => __( 'The description text for the newsletter section.', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage',
			'type'        => 'textarea',
		)
	);

	// Add about page section
	$wp_customize->add_section(
		'aqualuxe_about',
		array(
			'title'       => __( 'About Page', 'aqualuxe' ),
			'description' => __( 'Customize the about page settings.', 'aqualuxe' ),
			'panel'       => 'aqualuxe_options',
			'priority'    => 20,
		)
	);

	// About image setting
	$wp_customize->add_setting(
		'about_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'about_image',
			array(
				'label'       => __( 'About Image', 'aqualuxe' ),
				'description' => __( 'Upload an image for the about page.', 'aqualuxe' ),
				'section'     => 'aqualuxe_about',
				'settings'    => 'about_image',
			)
		)
	);

	// History title setting
	$wp_customize->add_setting(
		'history_title',
		array(
			'default'           => __( 'Our History', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'history_title',
		array(
			'label'       => __( 'History Title', 'aqualuxe' ),
			'description' => __( 'The title for the history section.', 'aqualuxe' ),
			'section'     => 'aqualuxe_about',
			'type'        => 'text',
		)
	);

	// History content setting
	$wp_customize->add_setting(
		'history_content',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
		)
	);

	$wp_customize->add_control(
		'history_content',
		array(
			'label'       => __( 'History Content', 'aqualuxe' ),
			'description' => __( 'The content for the history section.', 'aqualuxe' ),
			'section'     => 'aqualuxe_about',
			'type'        => 'textarea',
		)
	);

	// Team title setting
	$wp_customize->add_setting(
		'team_title',
		array(
			'default'           => __( 'Meet Our Team', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'team_title',
		array(
			'label'       => __( 'Team Title', 'aqualuxe' ),
			'description' => __( 'The title for the team section.', 'aqualuxe' ),
			'section'     => 'aqualuxe_about',
			'type'        => 'text',
		)
	);

	// Add services page section
	$wp_customize->add_section(
		'aqualuxe_services',
		array(
			'title'       => __( 'Services Page', 'aqualuxe' ),
			'description' => __( 'Customize the services page settings.', 'aqualuxe' ),
			'panel'       => 'aqualuxe_options',
			'priority'    => 30,
		)
	);

	// Breeding title setting
	$wp_customize->add_setting(
		'breeding_title',
		array(
			'default'           => __( 'Our Breeding Programs', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'breeding_title',
		array(
			'label'       => __( 'Breeding Programs Title', 'aqualuxe' ),
			'description' => __( 'The title for the breeding programs section.', 'aqualuxe' ),
			'section'     => 'aqualuxe_services',
			'type'        => 'text',
		)
	);

	// Breeding content setting
	$wp_customize->add_setting(
		'breeding_content',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
		)
	);

	$wp_customize->add_control(
		'breeding_content',
		array(
			'label'       => __( 'Breeding Programs Content', 'aqualuxe' ),
			'description' => __( 'The content for the breeding programs section.', 'aqualuxe' ),
			'section'     => 'aqualuxe_services',
			'type'        => 'textarea',
		)
	);

	// Consultation title setting
	$wp_customize->add_setting(
		'consultation_title',
		array(
			'default'           => __( 'Expert Consultation', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'consultation_title',
		array(
			'label'       => __( 'Consultation Title', 'aqualuxe' ),
			'description' => __( 'The title for the consultation section.', 'aqualuxe' ),
			'section'     => 'aqualuxe_services',
			'type'        => 'text',
		)
	);

	// Consultation content setting
	$wp_customize->add_setting(
		'consultation_content',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
		)
	);

	$wp_customize->add_control(
		'consultation_content',
		array(
			'label'       => __( 'Consultation Content', 'aqualuxe' ),
			'description' => __( 'The content for the consultation section.', 'aqualuxe' ),
			'section'     => 'aqualuxe_services',
			'type'        => 'textarea',
		)
	);

	// Add social media section
	$wp_customize->add_section(
		'aqualuxe_social',
		array(
			'title'       => __( 'Social Media', 'aqualuxe' ),
			'description' => __( 'Add links to your social media profiles.', 'aqualuxe' ),
			'panel'       => 'aqualuxe_options',
			'priority'    => 40,
		)
	);

	// Facebook URL setting
	$wp_customize->add_setting(
		'facebook_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'facebook_url',
		array(
			'label'       => __( 'Facebook URL', 'aqualuxe' ),
			'description' => __( 'Enter your Facebook profile URL.', 'aqualuxe' ),
			'section'     => 'aqualuxe_social',
			'type'        => 'url',
		)
	);

	// Twitter URL setting
	$wp_customize->add_setting(
		'twitter_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'twitter_url',
		array(
			'label'       => __( 'Twitter URL', 'aqualuxe' ),
			'description' => __( 'Enter your Twitter profile URL.', 'aqualuxe' ),
			'section'     => 'aqualuxe_social',
			'type'        => 'url',
		)
	);

	// Instagram URL setting
	$wp_customize->add_setting(
		'instagram_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'instagram_url',
		array(
			'label'       => __( 'Instagram URL', 'aqualuxe' ),
			'description' => __( 'Enter your Instagram profile URL.', 'aqualuxe' ),
			'section'     => 'aqualuxe_social',
			'type'        => 'url',
		)
	);

	// LinkedIn URL setting
	$wp_customize->add_setting(
		'linkedin_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'linkedin_url',
		array(
			'label'       => __( 'LinkedIn URL', 'aqualuxe' ),
			'description' => __( 'Enter your LinkedIn profile URL.', 'aqualuxe' ),
			'section'     => 'aqualuxe_social',
			'type'        => 'url',
		)
	);

	// YouTube URL setting
	$wp_customize->add_setting(
		'youtube_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'youtube_url',
		array(
			'label'       => __( 'YouTube URL', 'aqualuxe' ),
			'description' => __( 'Enter your YouTube channel URL.', 'aqualuxe' ),
			'section'     => 'aqualuxe_social',
			'type'        => 'url',
		)
	);

	// Add contact information section
	$wp_customize->add_section(
		'aqualuxe_contact',
		array(
			'title'       => __( 'Contact Information', 'aqualuxe' ),
			'description' => __( 'Add your contact information.', 'aqualuxe' ),
			'panel'       => 'aqualuxe_options',
			'priority'    => 50,
		)
	);

	// Contact address setting
	$wp_customize->add_setting(
		'contact_address',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);

	$wp_customize->add_control(
		'contact_address',
		array(
			'label'       => __( 'Address', 'aqualuxe' ),
			'description' => __( 'Enter your business address.', 'aqualuxe' ),
			'section'     => 'aqualuxe_contact',
			'type'        => 'textarea',
		)
	);

	// Contact phone setting
	$wp_customize->add_setting(
		'contact_phone',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'contact_phone',
		array(
			'label'       => __( 'Phone Number', 'aqualuxe' ),
			'description' => __( 'Enter your phone number.', 'aqualuxe' ),
			'section'     => 'aqualuxe_contact',
			'type'        => 'text',
		)
	);

	// Contact email setting
	$wp_customize->add_setting(
		'contact_email',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_email',
		)
	);

	$wp_customize->add_control(
		'contact_email',
		array(
			'label'       => __( 'Email Address', 'aqualuxe' ),
			'description' => __( 'Enter your email address.', 'aqualuxe' ),
			'section'     => 'aqualuxe_contact',
			'type'        => 'email',
		)
	);
}
add_action( 'customize_register', 'aqualuxe_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
	wp_enqueue_script( 'aqualuxe-customizer', AQUALUXE_URI . '/assets/js/customizer.js', array( 'customize-preview' ), AQUALUXE_VERSION, true );
}
add_action( 'customize_preview_init', 'aqualuxe_customize_preview_js' );