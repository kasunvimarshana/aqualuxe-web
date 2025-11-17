<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register( $wp_customize ) {
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
		'aqualuxe_theme_options',
		array(
			'title'       => __( 'AquaLuxe Theme Options', 'aqualuxe' ),
			'description' => __( 'Customize your AquaLuxe theme settings', 'aqualuxe' ),
			'priority'    => 130,
		)
	);

	// Add sections to the panel
	aqualuxe_customize_general_section( $wp_customize );
	aqualuxe_customize_header_section( $wp_customize );
	aqualuxe_customize_footer_section( $wp_customize );
	aqualuxe_customize_colors_section( $wp_customize );
	aqualuxe_customize_typography_section( $wp_customize );
	aqualuxe_customize_layout_section( $wp_customize );
	aqualuxe_customize_blog_section( $wp_customize );
	
	// Add WooCommerce section if WooCommerce is active
	if ( class_exists( 'WooCommerce' ) ) {
		aqualuxe_customize_woocommerce_section( $wp_customize );
	}
	
	// Add newsletter section
	aqualuxe_customize_newsletter_section( $wp_customize );
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
	wp_enqueue_script( 'aqualuxe-customizer', get_template_directory_uri() . '/assets/dist/js/customizer.js', array( 'customize-preview' ), AQUALUXE_VERSION, true );
}
add_action( 'customize_preview_init', 'aqualuxe_customize_preview_js' );

/**
 * Add general settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_general_section( $wp_customize ) {
	$wp_customize->add_section(
		'aqualuxe_general_settings',
		array(
			'title'       => __( 'General Settings', 'aqualuxe' ),
			'description' => __( 'Configure general theme settings', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 10,
		)
	);

	// Default color scheme
	$wp_customize->add_setting(
		'aqualuxe_default_color_scheme',
		array(
			'default'           => 'light',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_default_color_scheme',
		array(
			'label'       => __( 'Default Color Scheme', 'aqualuxe' ),
			'description' => __( 'Choose the default color scheme for your site', 'aqualuxe' ),
			'section'     => 'aqualuxe_general_settings',
			'type'        => 'select',
			'choices'     => array(
				'light' => __( 'Light Mode', 'aqualuxe' ),
				'dark'  => __( 'Dark Mode', 'aqualuxe' ),
			),
		)
	);

	// Show dark mode toggle
	$wp_customize->add_setting(
		'aqualuxe_show_dark_mode_toggle',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_show_dark_mode_toggle',
		array(
			'label'       => __( 'Show Dark Mode Toggle', 'aqualuxe' ),
			'description' => __( 'Display a toggle button for users to switch between light and dark mode', 'aqualuxe' ),
			'section'     => 'aqualuxe_general_settings',
			'type'        => 'checkbox',
		)
	);

	// Breadcrumbs
	$wp_customize->add_setting(
		'aqualuxe_display_breadcrumbs',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_display_breadcrumbs',
		array(
			'label'       => __( 'Display Breadcrumbs', 'aqualuxe' ),
			'description' => __( 'Show breadcrumb navigation on pages', 'aqualuxe' ),
			'section'     => 'aqualuxe_general_settings',
			'type'        => 'checkbox',
		)
	);

	// Social sharing
	$wp_customize->add_setting(
		'aqualuxe_display_social_sharing',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_display_social_sharing',
		array(
			'label'       => __( 'Display Social Sharing', 'aqualuxe' ),
			'description' => __( 'Show social sharing buttons on posts and products', 'aqualuxe' ),
			'section'     => 'aqualuxe_general_settings',
			'type'        => 'checkbox',
		)
	);

	// Related posts
	$wp_customize->add_setting(
		'aqualuxe_display_related_posts',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_display_related_posts',
		array(
			'label'       => __( 'Display Related Posts', 'aqualuxe' ),
			'description' => __( 'Show related posts on single post pages', 'aqualuxe' ),
			'section'     => 'aqualuxe_general_settings',
			'type'        => 'checkbox',
		)
	);
}

/**
 * Add header settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_header_section( $wp_customize ) {
	$wp_customize->add_section(
		'aqualuxe_header_settings',
		array(
			'title'       => __( 'Header Settings', 'aqualuxe' ),
			'description' => __( 'Configure header layout and options', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 20,
		)
	);

	// Header layout
	$wp_customize->add_setting(
		'aqualuxe_header_layout',
		array(
			'default'           => 'standard',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_header_layout',
		array(
			'label'       => __( 'Header Layout', 'aqualuxe' ),
			'description' => __( 'Choose the layout for your site header', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_settings',
			'type'        => 'select',
			'choices'     => array(
				'standard'   => __( 'Standard', 'aqualuxe' ),
				'centered'   => __( 'Centered', 'aqualuxe' ),
				'split'      => __( 'Split Menu', 'aqualuxe' ),
				'transparent' => __( 'Transparent', 'aqualuxe' ),
			),
		)
	);

	// Sticky header
	$wp_customize->add_setting(
		'aqualuxe_sticky_header',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_sticky_header',
		array(
			'label'       => __( 'Sticky Header', 'aqualuxe' ),
			'description' => __( 'Keep the header visible when scrolling down', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_settings',
			'type'        => 'checkbox',
		)
	);

	// Show search in header
	$wp_customize->add_setting(
		'aqualuxe_header_search',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_header_search',
		array(
			'label'       => __( 'Show Search in Header', 'aqualuxe' ),
			'description' => __( 'Display a search icon/form in the header', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_settings',
			'type'        => 'checkbox',
		)
	);

	// Show language switcher in header
	$wp_customize->add_setting(
		'aqualuxe_header_language_switcher',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_header_language_switcher',
		array(
			'label'       => __( 'Show Language Switcher in Header', 'aqualuxe' ),
			'description' => __( 'Display language switcher in the header (if multilingual plugin is active)', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_settings',
			'type'        => 'checkbox',
		)
	);

	// Show currency switcher in header
	$wp_customize->add_setting(
		'aqualuxe_header_currency_switcher',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_header_currency_switcher',
		array(
			'label'       => __( 'Show Currency Switcher in Header', 'aqualuxe' ),
			'description' => __( 'Display currency switcher in the header (if WooCommerce is active)', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_settings',
			'type'        => 'checkbox',
		)
	);

	// Header top bar
	$wp_customize->add_setting(
		'aqualuxe_header_top_bar',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_header_top_bar',
		array(
			'label'       => __( 'Show Top Bar', 'aqualuxe' ),
			'description' => __( 'Display a top bar above the main header', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_settings',
			'type'        => 'checkbox',
		)
	);

	// Top bar content
	$wp_customize->add_setting(
		'aqualuxe_header_top_bar_content',
		array(
			'default'           => __( 'Free shipping on orders over $100', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_header_top_bar_content',
		array(
			'label'       => __( 'Top Bar Content', 'aqualuxe' ),
			'description' => __( 'Text or HTML to display in the top bar', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_settings',
			'type'        => 'text',
		)
	);
}

/**
 * Add footer settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_footer_section( $wp_customize ) {
	$wp_customize->add_section(
		'aqualuxe_footer_settings',
		array(
			'title'       => __( 'Footer Settings', 'aqualuxe' ),
			'description' => __( 'Configure footer layout and options', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 30,
		)
	);

	// Footer layout
	$wp_customize->add_setting(
		'aqualuxe_footer_layout',
		array(
			'default'           => '4-columns',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_footer_layout',
		array(
			'label'       => __( 'Footer Layout', 'aqualuxe' ),
			'description' => __( 'Choose the layout for your site footer', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer_settings',
			'type'        => 'select',
			'choices'     => array(
				'1-column'  => __( '1 Column', 'aqualuxe' ),
				'2-columns' => __( '2 Columns', 'aqualuxe' ),
				'3-columns' => __( '3 Columns', 'aqualuxe' ),
				'4-columns' => __( '4 Columns', 'aqualuxe' ),
			),
		)
	);

	// Footer logo
	$wp_customize->add_setting(
		'aqualuxe_footer_logo',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'aqualuxe_footer_logo',
			array(
				'label'       => __( 'Footer Logo', 'aqualuxe' ),
				'description' => __( 'Upload a logo for the footer (optional)', 'aqualuxe' ),
				'section'     => 'aqualuxe_footer_settings',
				'mime_type'   => 'image',
			)
		)
	);

	// Copyright text
	$wp_customize->add_setting(
		'aqualuxe_copyright_text',
		array(
			'default'           => sprintf( __( '© %s AquaLuxe. All rights reserved.', 'aqualuxe' ), date( 'Y' ) ),
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_copyright_text',
		array(
			'label'       => __( 'Copyright Text', 'aqualuxe' ),
			'description' => __( 'Text or HTML to display in the footer copyright area', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer_settings',
			'type'        => 'textarea',
		)
	);

	// Payment icons
	$wp_customize->add_setting(
		'aqualuxe_show_payment_icons',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_show_payment_icons',
		array(
			'label'       => __( 'Show Payment Icons', 'aqualuxe' ),
			'description' => __( 'Display payment method icons in the footer', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer_settings',
			'type'        => 'checkbox',
		)
	);

	// Social media icons
	$wp_customize->add_setting(
		'aqualuxe_show_social_icons',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_show_social_icons',
		array(
			'label'       => __( 'Show Social Media Icons', 'aqualuxe' ),
			'description' => __( 'Display social media icons in the footer', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer_settings',
			'type'        => 'checkbox',
		)
	);

	// Social media links
	$social_platforms = array(
		'facebook'  => __( 'Facebook URL', 'aqualuxe' ),
		'twitter'   => __( 'Twitter URL', 'aqualuxe' ),
		'instagram' => __( 'Instagram URL', 'aqualuxe' ),
		'youtube'   => __( 'YouTube URL', 'aqualuxe' ),
		'linkedin'  => __( 'LinkedIn URL', 'aqualuxe' ),
		'pinterest' => __( 'Pinterest URL', 'aqualuxe' ),
	);

	foreach ( $social_platforms as $platform => $label ) {
		$wp_customize->add_setting(
			'aqualuxe_social_' . $platform,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_social_' . $platform,
			array(
				'label'       => $label,
				'description' => sprintf( __( 'Enter your %s profile URL', 'aqualuxe' ), $label ),
				'section'     => 'aqualuxe_footer_settings',
				'type'        => 'url',
			)
		);
	}
}

/**
 * Add colors settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_colors_section( $wp_customize ) {
	$wp_customize->add_section(
		'aqualuxe_colors_settings',
		array(
			'title'       => __( 'Colors', 'aqualuxe' ),
			'description' => __( 'Customize theme colors', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 40,
		)
	);

	// Primary color
	$wp_customize->add_setting(
		'aqualuxe_primary_color',
		array(
			'default'           => '#0073aa',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_primary_color',
			array(
				'label'       => __( 'Primary Color', 'aqualuxe' ),
				'description' => __( 'Main brand color used for buttons, links, and accents', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);

	// Secondary color
	$wp_customize->add_setting(
		'aqualuxe_secondary_color',
		array(
			'default'           => '#005177',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_secondary_color',
			array(
				'label'       => __( 'Secondary Color', 'aqualuxe' ),
				'description' => __( 'Secondary brand color used for hover states and secondary elements', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);

	// Light mode background
	$wp_customize->add_setting(
		'aqualuxe_light_background',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_light_background',
			array(
				'label'       => __( 'Light Mode Background', 'aqualuxe' ),
				'description' => __( 'Background color for light mode', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);

	// Light mode text
	$wp_customize->add_setting(
		'aqualuxe_light_text',
		array(
			'default'           => '#333333',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_light_text',
			array(
				'label'       => __( 'Light Mode Text', 'aqualuxe' ),
				'description' => __( 'Text color for light mode', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);

	// Dark mode background
	$wp_customize->add_setting(
		'aqualuxe_dark_background',
		array(
			'default'           => '#121212',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_dark_background',
			array(
				'label'       => __( 'Dark Mode Background', 'aqualuxe' ),
				'description' => __( 'Background color for dark mode', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);

	// Dark mode text
	$wp_customize->add_setting(
		'aqualuxe_dark_text',
		array(
			'default'           => '#f9f9f9',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_dark_text',
			array(
				'label'       => __( 'Dark Mode Text', 'aqualuxe' ),
				'description' => __( 'Text color for dark mode', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);

	// Header background
	$wp_customize->add_setting(
		'aqualuxe_header_background',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_header_background',
			array(
				'label'       => __( 'Header Background', 'aqualuxe' ),
				'description' => __( 'Background color for the header', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);

	// Footer background
	$wp_customize->add_setting(
		'aqualuxe_footer_background',
		array(
			'default'           => '#f5f5f5',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_footer_background',
			array(
				'label'       => __( 'Footer Background', 'aqualuxe' ),
				'description' => __( 'Background color for the footer', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);
}

/**
 * Add typography settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_typography_section( $wp_customize ) {
	$wp_customize->add_section(
		'aqualuxe_typography_settings',
		array(
			'title'       => __( 'Typography', 'aqualuxe' ),
			'description' => __( 'Customize fonts and typography', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 50,
		)
	);

	// Body font family
	$wp_customize->add_setting(
		'aqualuxe_body_font_family',
		array(
			'default'           => 'Inter, sans-serif',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_body_font_family',
		array(
			'label'       => __( 'Body Font Family', 'aqualuxe' ),
			'description' => __( 'Font family for body text', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography_settings',
			'type'        => 'select',
			'choices'     => array(
				'Inter, sans-serif'                => 'Inter (Default)',
				'Roboto, sans-serif'               => 'Roboto',
				'Open Sans, sans-serif'            => 'Open Sans',
				'Lato, sans-serif'                 => 'Lato',
				'Montserrat, sans-serif'           => 'Montserrat',
				'Poppins, sans-serif'              => 'Poppins',
				'Source Sans Pro, sans-serif'      => 'Source Sans Pro',
				'Noto Sans, sans-serif'            => 'Noto Sans',
				'Raleway, sans-serif'              => 'Raleway',
				'PT Sans, sans-serif'              => 'PT Sans',
			),
		)
	);

	// Heading font family
	$wp_customize->add_setting(
		'aqualuxe_heading_font_family',
		array(
			'default'           => 'Playfair Display, serif',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_heading_font_family',
		array(
			'label'       => __( 'Heading Font Family', 'aqualuxe' ),
			'description' => __( 'Font family for headings', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography_settings',
			'type'        => 'select',
			'choices'     => array(
				'Playfair Display, serif'          => 'Playfair Display (Default)',
				'Merriweather, serif'              => 'Merriweather',
				'Roboto Slab, serif'               => 'Roboto Slab',
				'Lora, serif'                      => 'Lora',
				'Montserrat, sans-serif'           => 'Montserrat',
				'Poppins, sans-serif'              => 'Poppins',
				'Oswald, sans-serif'               => 'Oswald',
				'Cormorant Garamond, serif'        => 'Cormorant Garamond',
				'Libre Baskerville, serif'         => 'Libre Baskerville',
				'Crimson Text, serif'              => 'Crimson Text',
			),
		)
	);

	// Base font size
	$wp_customize->add_setting(
		'aqualuxe_base_font_size',
		array(
			'default'           => '16px',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_base_font_size',
		array(
			'label'       => __( 'Base Font Size', 'aqualuxe' ),
			'description' => __( 'Base font size for the theme', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography_settings',
			'type'        => 'select',
			'choices'     => array(
				'14px' => '14px',
				'15px' => '15px',
				'16px' => '16px (Default)',
				'17px' => '17px',
				'18px' => '18px',
			),
		)
	);

	// Line height
	$wp_customize->add_setting(
		'aqualuxe_line_height',
		array(
			'default'           => '1.6',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_line_height',
		array(
			'label'       => __( 'Line Height', 'aqualuxe' ),
			'description' => __( 'Base line height for the theme', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography_settings',
			'type'        => 'select',
			'choices'     => array(
				'1.4' => '1.4 (Compact)',
				'1.5' => '1.5',
				'1.6' => '1.6 (Default)',
				'1.7' => '1.7',
				'1.8' => '1.8 (Spacious)',
			),
		)
	);
}

/**
 * Add layout settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_layout_section( $wp_customize ) {
	$wp_customize->add_section(
		'aqualuxe_layout_settings',
		array(
			'title'       => __( 'Layout', 'aqualuxe' ),
			'description' => __( 'Configure layout settings', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 60,
		)
	);

	// Container width
	$wp_customize->add_setting(
		'aqualuxe_container_width',
		array(
			'default'           => '1200px',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_container_width',
		array(
			'label'       => __( 'Container Width', 'aqualuxe' ),
			'description' => __( 'Maximum width of the main content container', 'aqualuxe' ),
			'section'     => 'aqualuxe_layout_settings',
			'type'        => 'select',
			'choices'     => array(
				'1140px' => '1140px (Narrow)',
				'1200px' => '1200px (Default)',
				'1320px' => '1320px (Wide)',
				'1440px' => '1440px (Extra Wide)',
				'100%'   => '100% (Full Width)',
			),
		)
	);

	// Sidebar position
	$wp_customize->add_setting(
		'aqualuxe_sidebar_position',
		array(
			'default'           => 'right',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_sidebar_position',
		array(
			'label'       => __( 'Sidebar Position', 'aqualuxe' ),
			'description' => __( 'Position of the sidebar on blog and archive pages', 'aqualuxe' ),
			'section'     => 'aqualuxe_layout_settings',
			'type'        => 'select',
			'choices'     => array(
				'right' => __( 'Right', 'aqualuxe' ),
				'left'  => __( 'Left', 'aqualuxe' ),
				'none'  => __( 'No Sidebar', 'aqualuxe' ),
			),
		)
	);

	// Shop sidebar position
	$wp_customize->add_setting(
		'aqualuxe_shop_sidebar_position',
		array(
			'default'           => 'left',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_shop_sidebar_position',
		array(
			'label'       => __( 'Shop Sidebar Position', 'aqualuxe' ),
			'description' => __( 'Position of the sidebar on shop and product archive pages', 'aqualuxe' ),
			'section'     => 'aqualuxe_layout_settings',
			'type'        => 'select',
			'choices'     => array(
				'right' => __( 'Right', 'aqualuxe' ),
				'left'  => __( 'Left', 'aqualuxe' ),
				'none'  => __( 'No Sidebar', 'aqualuxe' ),
			),
		)
	);

	// Page layout
	$wp_customize->add_setting(
		'aqualuxe_page_layout',
		array(
			'default'           => 'no-sidebar',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_page_layout',
		array(
			'label'       => __( 'Page Layout', 'aqualuxe' ),
			'description' => __( 'Default layout for pages', 'aqualuxe' ),
			'section'     => 'aqualuxe_layout_settings',
			'type'        => 'select',
			'choices'     => array(
				'no-sidebar'    => __( 'No Sidebar', 'aqualuxe' ),
				'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
				'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
			),
		)
	);

	// Blog layout
	$wp_customize->add_setting(
		'aqualuxe_blog_layout',
		array(
			'default'           => 'grid',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_blog_layout',
		array(
			'label'       => __( 'Blog Layout', 'aqualuxe' ),
			'description' => __( 'Layout for blog and archive pages', 'aqualuxe' ),
			'section'     => 'aqualuxe_layout_settings',
			'type'        => 'select',
			'choices'     => array(
				'grid'    => __( 'Grid', 'aqualuxe' ),
				'list'    => __( 'List', 'aqualuxe' ),
				'masonry' => __( 'Masonry', 'aqualuxe' ),
				'classic' => __( 'Classic', 'aqualuxe' ),
			),
		)
	);
}

/**
 * Add blog settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_blog_section( $wp_customize ) {
	$wp_customize->add_section(
		'aqualuxe_blog_settings',
		array(
			'title'       => __( 'Blog Settings', 'aqualuxe' ),
			'description' => __( 'Configure blog and post settings', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 70,
		)
	);

	// Blog posts per page
	$wp_customize->add_setting(
		'aqualuxe_blog_posts_per_page',
		array(
			'default'           => '9',
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_blog_posts_per_page',
		array(
			'label'       => __( 'Posts Per Page', 'aqualuxe' ),
			'description' => __( 'Number of posts to display per page', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 50,
				'step' => 1,
			),
		)
	);

	// Show post date
	$wp_customize->add_setting(
		'aqualuxe_show_post_date',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_show_post_date',
		array(
			'label'       => __( 'Show Post Date', 'aqualuxe' ),
			'description' => __( 'Display the post date on blog and archive pages', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'checkbox',
		)
	);

	// Show post author
	$wp_customize->add_setting(
		'aqualuxe_show_post_author',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_show_post_author',
		array(
			'label'       => __( 'Show Post Author', 'aqualuxe' ),
			'description' => __( 'Display the post author on blog and archive pages', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'checkbox',
		)
	);

	// Show post categories
	$wp_customize->add_setting(
		'aqualuxe_show_post_categories',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_show_post_categories',
		array(
			'label'       => __( 'Show Post Categories', 'aqualuxe' ),
			'description' => __( 'Display the post categories on blog and archive pages', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'checkbox',
		)
	);

	// Show post excerpt
	$wp_customize->add_setting(
		'aqualuxe_show_post_excerpt',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_show_post_excerpt',
		array(
			'label'       => __( 'Show Post Excerpt', 'aqualuxe' ),
			'description' => __( 'Display the post excerpt on blog and archive pages', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'checkbox',
		)
	);

	// Excerpt length
	$wp_customize->add_setting(
		'aqualuxe_excerpt_length',
		array(
			'default'           => '25',
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_excerpt_length',
		array(
			'label'       => __( 'Excerpt Length', 'aqualuxe' ),
			'description' => __( 'Number of words in the excerpt', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 10,
				'max'  => 100,
				'step' => 5,
			),
		)
	);

	// Read more text
	$wp_customize->add_setting(
		'aqualuxe_read_more_text',
		array(
			'default'           => __( 'Read More', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_read_more_text',
		array(
			'label'       => __( 'Read More Text', 'aqualuxe' ),
			'description' => __( 'Text for the read more link', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'text',
		)
	);
}

/**
 * Add WooCommerce settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_woocommerce_section( $wp_customize ) {
	$wp_customize->add_section(
		'aqualuxe_woocommerce_settings',
		array(
			'title'       => __( 'WooCommerce', 'aqualuxe' ),
			'description' => __( 'Configure WooCommerce settings', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 80,
		)
	);

	// Products per page
	$wp_customize->add_setting(
		'aqualuxe_products_per_page',
		array(
			'default'           => '12',
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_products_per_page',
		array(
			'label'       => __( 'Products Per Page', 'aqualuxe' ),
			'description' => __( 'Number of products to display per page', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 50,
				'step' => 1,
			),
		)
	);

	// Product columns
	$wp_customize->add_setting(
		'aqualuxe_product_columns',
		array(
			'default'           => '4',
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_product_columns',
		array(
			'label'       => __( 'Product Columns', 'aqualuxe' ),
			'description' => __( 'Number of columns in product grids', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'select',
			'choices'     => array(
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
			),
		)
	);

	// Related products
	$wp_customize->add_setting(
		'aqualuxe_related_products',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_related_products',
		array(
			'label'       => __( 'Show Related Products', 'aqualuxe' ),
			'description' => __( 'Display related products on single product pages', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'checkbox',
		)
	);

	// Quick view
	$wp_customize->add_setting(
		'aqualuxe_quick_view',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_quick_view',
		array(
			'label'       => __( 'Enable Quick View', 'aqualuxe' ),
			'description' => __( 'Allow customers to view product details in a modal', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'checkbox',
		)
	);

	// Wishlist
	$wp_customize->add_setting(
		'aqualuxe_wishlist',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_wishlist',
		array(
			'label'       => __( 'Enable Wishlist', 'aqualuxe' ),
			'description' => __( 'Allow customers to add products to a wishlist', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'checkbox',
		)
	);

	// Product gallery zoom
	$wp_customize->add_setting(
		'aqualuxe_product_zoom',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_product_zoom',
		array(
			'label'       => __( 'Enable Product Zoom', 'aqualuxe' ),
			'description' => __( 'Allow customers to zoom in on product images', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'checkbox',
		)
	);

	// Product gallery lightbox
	$wp_customize->add_setting(
		'aqualuxe_product_lightbox',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_product_lightbox',
		array(
			'label'       => __( 'Enable Product Lightbox', 'aqualuxe' ),
			'description' => __( 'Allow customers to view product images in a lightbox', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'checkbox',
		)
	);

	// Product gallery slider
	$wp_customize->add_setting(
		'aqualuxe_product_slider',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_product_slider',
		array(
			'label'       => __( 'Enable Product Gallery Slider', 'aqualuxe' ),
			'description' => __( 'Display product images in a slider', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'checkbox',
		)
	);

	// Cart cross-sells
	$wp_customize->add_setting(
		'aqualuxe_cart_cross_sells',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_cart_cross_sells',
		array(
			'label'       => __( 'Show Cart Cross-Sells', 'aqualuxe' ),
			'description' => __( 'Display cross-sell products on the cart page', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'checkbox',
		)
	);
}

/**
 * Add newsletter settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_newsletter_section( $wp_customize ) {
	$wp_customize->add_section(
		'aqualuxe_newsletter_settings',
		array(
			'title'       => __( 'Newsletter', 'aqualuxe' ),
			'description' => __( 'Configure newsletter settings', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 90,
		)
	);

	// Display newsletter
	$wp_customize->add_setting(
		'aqualuxe_display_newsletter',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_display_newsletter',
		array(
			'label'       => __( 'Display Newsletter', 'aqualuxe' ),
			'description' => __( 'Show newsletter signup form on the site', 'aqualuxe' ),
			'section'     => 'aqualuxe_newsletter_settings',
			'type'        => 'checkbox',
		)
	);

	// Newsletter title
	$wp_customize->add_setting(
		'aqualuxe_newsletter_title',
		array(
			'default'           => __( 'Subscribe to Our Newsletter', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_newsletter_title',
		array(
			'label'       => __( 'Newsletter Title', 'aqualuxe' ),
			'description' => __( 'Title for the newsletter section', 'aqualuxe' ),
			'section'     => 'aqualuxe_newsletter_settings',
			'type'        => 'text',
		)
	);

	// Newsletter text
	$wp_customize->add_setting(
		'aqualuxe_newsletter_text',
		array(
			'default'           => __( 'Stay updated with our latest products and news.', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_newsletter_text',
		array(
			'label'       => __( 'Newsletter Text', 'aqualuxe' ),
			'description' => __( 'Text for the newsletter section', 'aqualuxe' ),
			'section'     => 'aqualuxe_newsletter_settings',
			'type'        => 'textarea',
		)
	);

	// Newsletter form
	$wp_customize->add_setting(
		'aqualuxe_newsletter_form',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_newsletter_form',
		array(
			'label'       => __( 'Newsletter Form', 'aqualuxe' ),
			'description' => __( 'Shortcode for the newsletter form (e.g., from MailChimp, Contact Form 7, etc.)', 'aqualuxe' ),
			'section'     => 'aqualuxe_newsletter_settings',
			'type'        => 'text',
		)
	);

	// Newsletter position
	$wp_customize->add_setting(
		'aqualuxe_newsletter_position',
		array(
			'default'           => 'footer',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_newsletter_position',
		array(
			'label'       => __( 'Newsletter Position', 'aqualuxe' ),
			'description' => __( 'Where to display the newsletter signup form', 'aqualuxe' ),
			'section'     => 'aqualuxe_newsletter_settings',
			'type'        => 'select',
			'choices'     => array(
				'footer'  => __( 'Footer', 'aqualuxe' ),
				'popup'   => __( 'Popup', 'aqualuxe' ),
				'sidebar' => __( 'Sidebar', 'aqualuxe' ),
				'custom'  => __( 'Custom (use shortcode)', 'aqualuxe' ),
			),
		)
	);
}

/**
 * Sanitize checkbox
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

/**
 * Sanitize select
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string
 */
function aqualuxe_sanitize_select( $input, $setting = null ) {
	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;
	
	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}