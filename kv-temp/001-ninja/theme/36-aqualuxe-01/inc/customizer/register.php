<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 * @since 1.0.0
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
	// Add selective refresh support for site title and description.
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'custom_logo' )->transport      = 'postMessage';

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
		$wp_customize->selective_refresh->add_partial(
			'custom_logo',
			array(
				'selector'        => '.site-logo',
				'render_callback' => 'aqualuxe_customize_partial_logo',
			)
		);
	}

	// Add theme options panel.
	$wp_customize->add_panel(
		'aqualuxe_theme_options',
		array(
			'title'       => __( 'AquaLuxe Theme Options', 'aqualuxe' ),
			'description' => __( 'Configure AquaLuxe theme settings', 'aqualuxe' ),
			'priority'    => 130,
		)
	);

	// Add sections to the theme options panel.
	aqualuxe_customize_register_general( $wp_customize );
	aqualuxe_customize_register_header( $wp_customize );
	aqualuxe_customize_register_footer( $wp_customize );
	aqualuxe_customize_register_colors( $wp_customize );
	aqualuxe_customize_register_typography( $wp_customize );
	aqualuxe_customize_register_layout( $wp_customize );
	aqualuxe_customize_register_blog( $wp_customize );
	aqualuxe_customize_register_social( $wp_customize );
	aqualuxe_customize_register_contact( $wp_customize );
	aqualuxe_customize_register_performance( $wp_customize );

	// Add WooCommerce section if WooCommerce is active.
	if ( class_exists( 'WooCommerce' ) ) {
		aqualuxe_customize_register_woocommerce( $wp_customize );
	}
}
add_action( 'customize_register', 'aqualuxe_customize_register' );

/**
 * Register general settings.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_general( $wp_customize ) {
	$wp_customize->add_section(
		'aqualuxe_general',
		array(
			'title'       => __( 'General Settings', 'aqualuxe' ),
			'description' => __( 'Configure general theme settings', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 10,
		)
	);

	// Enable dark mode toggle.
	$wp_customize->add_setting(
		'aqualuxe_enable_dark_mode',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_enable_dark_mode',
		array(
			'label'       => __( 'Enable Dark Mode Toggle', 'aqualuxe' ),
			'description' => __( 'Allow users to switch between light and dark mode', 'aqualuxe' ),
			'section'     => 'aqualuxe_general',
			'type'        => 'checkbox',
		)
	);

	// Default color scheme.
	$wp_customize->add_setting(
		'aqualuxe_default_color_scheme',
		array(
			'default'           => 'light',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_default_color_scheme',
		array(
			'label'       => __( 'Default Color Scheme', 'aqualuxe' ),
			'description' => __( 'Choose the default color scheme for your site', 'aqualuxe' ),
			'section'     => 'aqualuxe_general',
			'type'        => 'select',
			'choices'     => array(
				'light' => __( 'Light', 'aqualuxe' ),
				'dark'  => __( 'Dark', 'aqualuxe' ),
				'auto'  => __( 'Auto (follow system preference)', 'aqualuxe' ),
			),
		)
	);

	// Container width.
	$wp_customize->add_setting(
		'aqualuxe_container_width',
		array(
			'default'           => '1280',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_container_width',
		array(
			'label'       => __( 'Container Width (px)', 'aqualuxe' ),
			'description' => __( 'Set the maximum width of the content container', 'aqualuxe' ),
			'section'     => 'aqualuxe_general',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 960,
				'max'  => 1920,
				'step' => 10,
			),
		)
	);

	// Breadcrumbs.
	$wp_customize->add_setting(
		'aqualuxe_enable_breadcrumbs',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_enable_breadcrumbs',
		array(
			'label'       => __( 'Enable Breadcrumbs', 'aqualuxe' ),
			'description' => __( 'Show breadcrumb navigation on pages and posts', 'aqualuxe' ),
			'section'     => 'aqualuxe_general',
			'type'        => 'checkbox',
		)
	);

	// Back to top button.
	$wp_customize->add_setting(
		'aqualuxe_enable_back_to_top',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_enable_back_to_top',
		array(
			'label'       => __( 'Enable Back to Top Button', 'aqualuxe' ),
			'description' => __( 'Show a button to scroll back to the top of the page', 'aqualuxe' ),
			'section'     => 'aqualuxe_general',
			'type'        => 'checkbox',
		)
	);
}

/**
 * Register header settings.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_header( $wp_customize ) {
	$wp_customize->add_section(
		'aqualuxe_header',
		array(
			'title'       => __( 'Header Settings', 'aqualuxe' ),
			'description' => __( 'Configure header layout and elements', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 20,
		)
	);

	// Header layout.
	$wp_customize->add_setting(
		'aqualuxe_header_layout',
		array(
			'default'           => 'standard',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_header_layout',
		array(
			'label'       => __( 'Header Layout', 'aqualuxe' ),
			'description' => __( 'Choose the layout for your site header', 'aqualuxe' ),
			'section'     => 'aqualuxe_header',
			'type'        => 'select',
			'choices'     => array(
				'standard'    => __( 'Standard', 'aqualuxe' ),
				'centered'    => __( 'Centered', 'aqualuxe' ),
				'split'       => __( 'Split (Logo in Center)', 'aqualuxe' ),
				'transparent' => __( 'Transparent', 'aqualuxe' ),
			),
		)
	);

	// Sticky header.
	$wp_customize->add_setting(
		'aqualuxe_sticky_header',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_sticky_header',
		array(
			'label'       => __( 'Enable Sticky Header', 'aqualuxe' ),
			'description' => __( 'Keep the header visible when scrolling down', 'aqualuxe' ),
			'section'     => 'aqualuxe_header',
			'type'        => 'checkbox',
		)
	);

	// Header search.
	$wp_customize->add_setting(
		'aqualuxe_header_search',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_header_search',
		array(
			'label'       => __( 'Show Search in Header', 'aqualuxe' ),
			'description' => __( 'Display a search icon/form in the header', 'aqualuxe' ),
			'section'     => 'aqualuxe_header',
			'type'        => 'checkbox',
		)
	);

	// Header cart.
	$wp_customize->add_setting(
		'aqualuxe_header_cart',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_header_cart',
		array(
			'label'       => __( 'Show Cart in Header', 'aqualuxe' ),
			'description' => __( 'Display a shopping cart icon in the header (requires WooCommerce)', 'aqualuxe' ),
			'section'     => 'aqualuxe_header',
			'type'        => 'checkbox',
			'active_callback' => function() {
				return class_exists( 'WooCommerce' );
			},
		)
	);

	// Header account.
	$wp_customize->add_setting(
		'aqualuxe_header_account',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_header_account',
		array(
			'label'       => __( 'Show Account in Header', 'aqualuxe' ),
			'description' => __( 'Display an account/login icon in the header', 'aqualuxe' ),
			'section'     => 'aqualuxe_header',
			'type'        => 'checkbox',
		)
	);

	// Top bar.
	$wp_customize->add_setting(
		'aqualuxe_enable_top_bar',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_enable_top_bar',
		array(
			'label'       => __( 'Enable Top Bar', 'aqualuxe' ),
			'description' => __( 'Show a top bar above the main header', 'aqualuxe' ),
			'section'     => 'aqualuxe_header',
			'type'        => 'checkbox',
		)
	);

	// Top bar content.
	$wp_customize->add_setting(
		'aqualuxe_top_bar_content',
		array(
			'default'           => __( 'Welcome to AquaLuxe - Bringing elegance to aquatic life – globally.', 'aqualuxe' ),
			'sanitize_callback' => 'wp_kses_post',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_top_bar_content',
		array(
			'label'       => __( 'Top Bar Content', 'aqualuxe' ),
			'description' => __( 'Text or HTML to display in the top bar', 'aqualuxe' ),
			'section'     => 'aqualuxe_header',
			'type'        => 'textarea',
			'active_callback' => function() {
				return get_theme_mod( 'aqualuxe_enable_top_bar', true );
			},
		)
	);
}

/**
 * Register footer settings.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_footer( $wp_customize ) {
	$wp_customize->add_section(
		'aqualuxe_footer',
		array(
			'title'       => __( 'Footer Settings', 'aqualuxe' ),
			'description' => __( 'Configure footer layout and content', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 30,
		)
	);

	// Footer layout.
	$wp_customize->add_setting(
		'aqualuxe_footer_layout',
		array(
			'default'           => '4-columns',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_footer_layout',
		array(
			'label'       => __( 'Footer Widget Layout', 'aqualuxe' ),
			'description' => __( 'Choose the layout for footer widgets', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer',
			'type'        => 'select',
			'choices'     => array(
				'4-columns' => __( '4 Columns', 'aqualuxe' ),
				'3-columns' => __( '3 Columns', 'aqualuxe' ),
				'2-columns' => __( '2 Columns', 'aqualuxe' ),
				'1-column'  => __( '1 Column', 'aqualuxe' ),
				'none'      => __( 'No Widgets', 'aqualuxe' ),
			),
		)
	);

	// Footer logo.
	$wp_customize->add_setting(
		'aqualuxe_footer_logo',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'aqualuxe_footer_logo',
			array(
				'label'       => __( 'Footer Logo', 'aqualuxe' ),
				'description' => __( 'Upload a logo to display in the footer', 'aqualuxe' ),
				'section'     => 'aqualuxe_footer',
				'mime_type'   => 'image',
			)
		)
	);

	// Copyright text.
	$wp_customize->add_setting(
		'aqualuxe_copyright_text',
		array(
			'default'           => sprintf(
				/* translators: %1$s: Current year, %2$s: Site name */
				__( '© %1$s %2$s. All rights reserved.', 'aqualuxe' ),
				date( 'Y' ),
				get_bloginfo( 'name' )
			),
			'sanitize_callback' => 'wp_kses_post',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_copyright_text',
		array(
			'label'       => __( 'Copyright Text', 'aqualuxe' ),
			'description' => __( 'Text or HTML to display in the copyright area', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer',
			'type'        => 'textarea',
		)
	);

	// Payment icons.
	$wp_customize->add_setting(
		'aqualuxe_show_payment_icons',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_show_payment_icons',
		array(
			'label'       => __( 'Show Payment Icons', 'aqualuxe' ),
			'description' => __( 'Display payment method icons in the footer', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer',
			'type'        => 'checkbox',
		)
	);

	// Payment icons selection.
	$wp_customize->add_setting(
		'aqualuxe_payment_icons',
		array(
			'default'           => array( 'visa', 'mastercard', 'amex', 'paypal' ),
			'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'aqualuxe_payment_icons',
			array(
				'label'       => __( 'Select Payment Icons', 'aqualuxe' ),
				'description' => __( 'Choose which payment icons to display', 'aqualuxe' ),
				'section'     => 'aqualuxe_footer',
				'type'        => 'select',
				'multiple'    => true,
				'choices'     => array(
					'visa'       => __( 'Visa', 'aqualuxe' ),
					'mastercard' => __( 'Mastercard', 'aqualuxe' ),
					'amex'       => __( 'American Express', 'aqualuxe' ),
					'discover'   => __( 'Discover', 'aqualuxe' ),
					'paypal'     => __( 'PayPal', 'aqualuxe' ),
					'apple-pay'  => __( 'Apple Pay', 'aqualuxe' ),
					'google-pay' => __( 'Google Pay', 'aqualuxe' ),
					'stripe'     => __( 'Stripe', 'aqualuxe' ),
				),
				'active_callback' => function() {
					return get_theme_mod( 'aqualuxe_show_payment_icons', true );
				},
			)
		)
	);
}

/**
 * Register color settings.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_colors( $wp_customize ) {
	$wp_customize->add_section(
		'aqualuxe_colors',
		array(
			'title'       => __( 'Colors', 'aqualuxe' ),
			'description' => __( 'Customize theme colors', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 40,
		)
	);

	// Primary color.
	$wp_customize->add_setting(
		'aqualuxe_primary_color',
		array(
			'default'           => '#14b8a6',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_primary_color',
			array(
				'label'       => __( 'Primary Color', 'aqualuxe' ),
				'description' => __( 'Main brand color used for buttons, links, and accents', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors',
			)
		)
	);

	// Secondary color.
	$wp_customize->add_setting(
		'aqualuxe_secondary_color',
		array(
			'default'           => '#bfa094',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_secondary_color',
			array(
				'label'       => __( 'Secondary Color', 'aqualuxe' ),
				'description' => __( 'Secondary brand color used for accents and highlights', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors',
			)
		)
	);

	// Text color (light mode).
	$wp_customize->add_setting(
		'aqualuxe_text_color_light',
		array(
			'default'           => '#333333',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_text_color_light',
			array(
				'label'       => __( 'Text Color (Light Mode)', 'aqualuxe' ),
				'description' => __( 'Main text color in light mode', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors',
			)
		)
	);

	// Background color (light mode).
	$wp_customize->add_setting(
		'aqualuxe_background_color_light',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_background_color_light',
			array(
				'label'       => __( 'Background Color (Light Mode)', 'aqualuxe' ),
				'description' => __( 'Main background color in light mode', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors',
			)
		)
	);

	// Text color (dark mode).
	$wp_customize->add_setting(
		'aqualuxe_text_color_dark',
		array(
			'default'           => '#e5e7eb',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_text_color_dark',
			array(
				'label'       => __( 'Text Color (Dark Mode)', 'aqualuxe' ),
				'description' => __( 'Main text color in dark mode', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors',
			)
		)
	);

	// Background color (dark mode).
	$wp_customize->add_setting(
		'aqualuxe_background_color_dark',
		array(
			'default'           => '#111827',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_background_color_dark',
			array(
				'label'       => __( 'Background Color (Dark Mode)', 'aqualuxe' ),
				'description' => __( 'Main background color in dark mode', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors',
			)
		)
	);

	// Header background color.
	$wp_customize->add_setting(
		'aqualuxe_header_background_color',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_header_background_color',
			array(
				'label'       => __( 'Header Background Color', 'aqualuxe' ),
				'description' => __( 'Background color for the header', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors',
			)
		)
	);

	// Footer background color.
	$wp_customize->add_setting(
		'aqualuxe_footer_background_color',
		array(
			'default'           => '#042f2e',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_footer_background_color',
			array(
				'label'       => __( 'Footer Background Color', 'aqualuxe' ),
				'description' => __( 'Background color for the footer', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors',
			)
		)
	);

	// Footer text color.
	$wp_customize->add_setting(
		'aqualuxe_footer_text_color',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_footer_text_color',
			array(
				'label'       => __( 'Footer Text Color', 'aqualuxe' ),
				'description' => __( 'Text color for the footer', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors',
			)
		)
	);
}

/**
 * Register typography settings.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_typography( $wp_customize ) {
	$wp_customize->add_section(
		'aqualuxe_typography',
		array(
			'title'       => __( 'Typography', 'aqualuxe' ),
			'description' => __( 'Customize fonts and typography', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 50,
		)
	);

	// Body font family.
	$wp_customize->add_setting(
		'aqualuxe_body_font_family',
		array(
			'default'           => 'Inter',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_body_font_family',
		array(
			'label'       => __( 'Body Font Family', 'aqualuxe' ),
			'description' => __( 'Font family for body text', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography',
			'type'        => 'select',
			'choices'     => array(
				'Inter'       => 'Inter',
				'Roboto'      => 'Roboto',
				'Open Sans'   => 'Open Sans',
				'Lato'        => 'Lato',
				'Montserrat'  => 'Montserrat',
				'Source Sans Pro' => 'Source Sans Pro',
				'Nunito'      => 'Nunito',
				'Raleway'     => 'Raleway',
				'system-ui'   => 'System UI',
			),
		)
	);

	// Heading font family.
	$wp_customize->add_setting(
		'aqualuxe_heading_font_family',
		array(
			'default'           => 'Playfair Display',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_heading_font_family',
		array(
			'label'       => __( 'Heading Font Family', 'aqualuxe' ),
			'description' => __( 'Font family for headings', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography',
			'type'        => 'select',
			'choices'     => array(
				'Playfair Display' => 'Playfair Display',
				'Merriweather'     => 'Merriweather',
				'Roboto Slab'      => 'Roboto Slab',
				'Lora'             => 'Lora',
				'Montserrat'       => 'Montserrat',
				'Poppins'          => 'Poppins',
				'Oswald'           => 'Oswald',
				'Cormorant Garamond' => 'Cormorant Garamond',
				'system-ui'        => 'System UI',
			),
		)
	);

	// Base font size.
	$wp_customize->add_setting(
		'aqualuxe_base_font_size',
		array(
			'default'           => '16',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_base_font_size',
		array(
			'label'       => __( 'Base Font Size (px)', 'aqualuxe' ),
			'description' => __( 'Base font size for body text', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 12,
				'max'  => 24,
				'step' => 1,
			),
		)
	);

	// Line height.
	$wp_customize->add_setting(
		'aqualuxe_line_height',
		array(
			'default'           => '1.6',
			'sanitize_callback' => 'aqualuxe_sanitize_float',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_line_height',
		array(
			'label'       => __( 'Line Height', 'aqualuxe' ),
			'description' => __( 'Line height for body text', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 2,
				'step' => 0.1,
			),
		)
	);

	// Font weight.
	$wp_customize->add_setting(
		'aqualuxe_font_weight',
		array(
			'default'           => '400',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_font_weight',
		array(
			'label'       => __( 'Body Font Weight', 'aqualuxe' ),
			'description' => __( 'Font weight for body text', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography',
			'type'        => 'select',
			'choices'     => array(
				'300' => __( 'Light (300)', 'aqualuxe' ),
				'400' => __( 'Regular (400)', 'aqualuxe' ),
				'500' => __( 'Medium (500)', 'aqualuxe' ),
				'600' => __( 'Semi-Bold (600)', 'aqualuxe' ),
				'700' => __( 'Bold (700)', 'aqualuxe' ),
			),
		)
	);

	// Heading font weight.
	$wp_customize->add_setting(
		'aqualuxe_heading_font_weight',
		array(
			'default'           => '700',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_heading_font_weight',
		array(
			'label'       => __( 'Heading Font Weight', 'aqualuxe' ),
			'description' => __( 'Font weight for headings', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography',
			'type'        => 'select',
			'choices'     => array(
				'400' => __( 'Regular (400)', 'aqualuxe' ),
				'500' => __( 'Medium (500)', 'aqualuxe' ),
				'600' => __( 'Semi-Bold (600)', 'aqualuxe' ),
				'700' => __( 'Bold (700)', 'aqualuxe' ),
				'800' => __( 'Extra-Bold (800)', 'aqualuxe' ),
			),
		)
	);
}

/**
 * Register layout settings.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_layout( $wp_customize ) {
	$wp_customize->add_section(
		'aqualuxe_layout',
		array(
			'title'       => __( 'Layout Settings', 'aqualuxe' ),
			'description' => __( 'Configure layout options', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 60,
		)
	);

	// Page layout.
	$wp_customize->add_setting(
		'aqualuxe_page_layout',
		array(
			'default'           => 'right-sidebar',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_page_layout',
		array(
			'label'       => __( 'Page Layout', 'aqualuxe' ),
			'description' => __( 'Choose the layout for pages', 'aqualuxe' ),
			'section'     => 'aqualuxe_layout',
			'type'        => 'select',
			'choices'     => array(
				'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
				'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
				'no-sidebar'    => __( 'No Sidebar', 'aqualuxe' ),
			),
		)
	);

	// Post layout.
	$wp_customize->add_setting(
		'aqualuxe_post_layout',
		array(
			'default'           => 'right-sidebar',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_post_layout',
		array(
			'label'       => __( 'Post Layout', 'aqualuxe' ),
			'description' => __( 'Choose the layout for single posts', 'aqualuxe' ),
			'section'     => 'aqualuxe_layout',
			'type'        => 'select',
			'choices'     => array(
				'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
				'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
				'no-sidebar'    => __( 'No Sidebar', 'aqualuxe' ),
			),
		)
	);

	// Archive layout.
	$wp_customize->add_setting(
		'aqualuxe_archive_layout',
		array(
			'default'           => 'right-sidebar',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_archive_layout',
		array(
			'label'       => __( 'Archive Layout', 'aqualuxe' ),
			'description' => __( 'Choose the layout for archives and blog', 'aqualuxe' ),
			'section'     => 'aqualuxe_layout',
			'type'        => 'select',
			'choices'     => array(
				'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
				'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
				'no-sidebar'    => __( 'No Sidebar', 'aqualuxe' ),
			),
		)
	);

	// Shop layout.
	$wp_customize->add_setting(
		'aqualuxe_shop_layout',
		array(
			'default'           => 'right-sidebar',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_shop_layout',
		array(
			'label'       => __( 'Shop Layout', 'aqualuxe' ),
			'description' => __( 'Choose the layout for shop pages', 'aqualuxe' ),
			'section'     => 'aqualuxe_layout',
			'type'        => 'select',
			'choices'     => array(
				'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
				'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
				'no-sidebar'    => __( 'No Sidebar', 'aqualuxe' ),
			),
			'active_callback' => function() {
				return class_exists( 'WooCommerce' );
			},
		)
	);

	// Product layout.
	$wp_customize->add_setting(
		'aqualuxe_product_layout',
		array(
			'default'           => 'no-sidebar',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_product_layout',
		array(
			'label'       => __( 'Product Layout', 'aqualuxe' ),
			'description' => __( 'Choose the layout for single product pages', 'aqualuxe' ),
			'section'     => 'aqualuxe_layout',
			'type'        => 'select',
			'choices'     => array(
				'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
				'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
				'no-sidebar'    => __( 'No Sidebar', 'aqualuxe' ),
			),
			'active_callback' => function() {
				return class_exists( 'WooCommerce' );
			},
		)
	);
}

/**
 * Register blog settings.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_blog( $wp_customize ) {
	$wp_customize->add_section(
		'aqualuxe_blog',
		array(
			'title'       => __( 'Blog Settings', 'aqualuxe' ),
			'description' => __( 'Configure blog and post options', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 70,
		)
	);

	// Blog layout.
	$wp_customize->add_setting(
		'aqualuxe_blog_layout',
		array(
			'default'           => 'grid',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_blog_layout',
		array(
			'label'       => __( 'Blog Layout', 'aqualuxe' ),
			'description' => __( 'Choose the layout for the blog archive', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog',
			'type'        => 'select',
			'choices'     => array(
				'grid'     => __( 'Grid', 'aqualuxe' ),
				'list'     => __( 'List', 'aqualuxe' ),
				'standard' => __( 'Standard', 'aqualuxe' ),
			),
		)
	);

	// Posts per row.
	$wp_customize->add_setting(
		'aqualuxe_posts_per_row',
		array(
			'default'           => '3',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_posts_per_row',
		array(
			'label'       => __( 'Posts Per Row', 'aqualuxe' ),
			'description' => __( 'Number of posts per row in grid layout', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 4,
				'step' => 1,
			),
			'active_callback' => function() {
				return get_theme_mod( 'aqualuxe_blog_layout', 'grid' ) === 'grid';
			},
		)
	);

	// Featured image.
	$wp_customize->add_setting(
		'aqualuxe_show_featured_image',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_show_featured_image',
		array(
			'label'       => __( 'Show Featured Image', 'aqualuxe' ),
			'description' => __( 'Display featured image on blog and single posts', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog',
			'type'        => 'checkbox',
		)
	);

	// Post meta.
	$wp_customize->add_setting(
		'aqualuxe_show_post_meta',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_show_post_meta',
		array(
			'label'       => __( 'Show Post Meta', 'aqualuxe' ),
			'description' => __( 'Display post date, author, categories, etc.', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog',
			'type'        => 'checkbox',
		)
	);

	// Post excerpt.
	$wp_customize->add_setting(
		'aqualuxe_show_excerpt',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_show_excerpt',
		array(
			'label'       => __( 'Show Post Excerpt', 'aqualuxe' ),
			'description' => __( 'Display post excerpt on blog archive', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog',
			'type'        => 'checkbox',
		)
	);

	// Excerpt length.
	$wp_customize->add_setting(
		'aqualuxe_excerpt_length',
		array(
			'default'           => '25',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_excerpt_length',
		array(
			'label'       => __( 'Excerpt Length', 'aqualuxe' ),
			'description' => __( 'Number of words in the excerpt', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 10,
				'max'  => 100,
				'step' => 5,
			),
			'active_callback' => function() {
				return get_theme_mod( 'aqualuxe_show_excerpt', true );
			},
		)
	);

	// Read more text.
	$wp_customize->add_setting(
		'aqualuxe_read_more_text',
		array(
			'default'           => __( 'Read More', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_read_more_text',
		array(
			'label'       => __( 'Read More Text', 'aqualuxe' ),
			'description' => __( 'Text for the read more link', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog',
			'type'        => 'text',
		)
	);

	// Related posts.
	$wp_customize->add_setting(
		'aqualuxe_show_related_posts',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_show_related_posts',
		array(
			'label'       => __( 'Show Related Posts', 'aqualuxe' ),
			'description' => __( 'Display related posts on single post pages', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog',
			'type'        => 'checkbox',
		)
	);

	// Post navigation.
	$wp_customize->add_setting(
		'aqualuxe_show_post_navigation',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_show_post_navigation',
		array(
			'label'       => __( 'Show Post Navigation', 'aqualuxe' ),
			'description' => __( 'Display previous/next post navigation on single post pages', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog',
			'type'        => 'checkbox',
		)
	);
}

/**
 * Register social media settings.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_social( $wp_customize ) {
	$wp_customize->add_section(
		'aqualuxe_social',
		array(
			'title'       => __( 'Social Media', 'aqualuxe' ),
			'description' => __( 'Configure social media profiles and sharing', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 80,
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
			'label'       => __( 'Facebook URL', 'aqualuxe' ),
			'description' => __( 'Enter your Facebook profile or page URL', 'aqualuxe' ),
			'section'     => 'aqualuxe_social',
			'type'        => 'url',
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
			'label'       => __( 'Twitter URL', 'aqualuxe' ),
			'description' => __( 'Enter your Twitter profile URL', 'aqualuxe' ),
			'section'     => 'aqualuxe_social',
			'type'        => 'url',
		)
	);

	// Twitter username.
	$wp_customize->add_setting(
		'aqualuxe_twitter_username',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_twitter_username',
		array(
			'label'       => __( 'Twitter Username', 'aqualuxe' ),
			'description' => __( 'Enter your Twitter username without @', 'aqualuxe' ),
			'section'     => 'aqualuxe_social',
			'type'        => 'text',
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
			'label'       => __( 'Instagram URL', 'aqualuxe' ),
			'description' => __( 'Enter your Instagram profile URL', 'aqualuxe' ),
			'section'     => 'aqualuxe_social',
			'type'        => 'url',
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
			'label'       => __( 'LinkedIn URL', 'aqualuxe' ),
			'description' => __( 'Enter your LinkedIn profile or company URL', 'aqualuxe' ),
			'section'     => 'aqualuxe_social',
			'type'        => 'url',
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
			'label'       => __( 'YouTube URL', 'aqualuxe' ),
			'description' => __( 'Enter your YouTube channel URL', 'aqualuxe' ),
			'section'     => 'aqualuxe_social',
			'type'        => 'url',
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
			'label'       => __( 'Pinterest URL', 'aqualuxe' ),
			'description' => __( 'Enter your Pinterest profile URL', 'aqualuxe' ),
			'section'     => 'aqualuxe_social',
			'type'        => 'url',
		)
	);

	// Social sharing.
	$wp_customize->add_setting(
		'aqualuxe_enable_social_sharing',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_enable_social_sharing',
		array(
			'label'       => __( 'Enable Social Sharing', 'aqualuxe' ),
			'description' => __( 'Display social sharing buttons on posts and products', 'aqualuxe' ),
			'section'     => 'aqualuxe_social',
			'type'        => 'checkbox',
		)
	);

	// Social sharing networks.
	$wp_customize->add_setting(
		'aqualuxe_social_sharing_networks',
		array(
			'default'           => array( 'facebook', 'twitter', 'pinterest', 'linkedin' ),
			'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'aqualuxe_social_sharing_networks',
			array(
				'label'       => __( 'Social Sharing Networks', 'aqualuxe' ),
				'description' => __( 'Select which social networks to include in sharing buttons', 'aqualuxe' ),
				'section'     => 'aqualuxe_social',
				'type'        => 'select',
				'multiple'    => true,
				'choices'     => array(
					'facebook'  => __( 'Facebook', 'aqualuxe' ),
					'twitter'   => __( 'Twitter', 'aqualuxe' ),
					'pinterest' => __( 'Pinterest', 'aqualuxe' ),
					'linkedin'  => __( 'LinkedIn', 'aqualuxe' ),
					'email'     => __( 'Email', 'aqualuxe' ),
					'whatsapp'  => __( 'WhatsApp', 'aqualuxe' ),
					'telegram'  => __( 'Telegram', 'aqualuxe' ),
				),
				'active_callback' => function() {
					return get_theme_mod( 'aqualuxe_enable_social_sharing', true );
				},
			)
		)
	);

	// Default social image.
	$wp_customize->add_setting(
		'aqualuxe_default_social_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'aqualuxe_default_social_image',
			array(
				'label'       => __( 'Default Social Image', 'aqualuxe' ),
				'description' => __( 'Upload a default image for social sharing when no featured image is available', 'aqualuxe' ),
				'section'     => 'aqualuxe_social',
				'mime_type'   => 'image',
			)
		)
	);
}

/**
 * Register contact information settings.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_contact( $wp_customize ) {
	$wp_customize->add_section(
		'aqualuxe_contact',
		array(
			'title'       => __( 'Contact Information', 'aqualuxe' ),
			'description' => __( 'Configure contact details and information', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 90,
		)
	);

	// Phone.
	$wp_customize->add_setting(
		'aqualuxe_phone',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_phone',
		array(
			'label'       => __( 'Phone Number', 'aqualuxe' ),
			'description' => __( 'Enter your business phone number', 'aqualuxe' ),
			'section'     => 'aqualuxe_contact',
			'type'        => 'tel',
		)
	);

	// Email.
	$wp_customize->add_setting(
		'aqualuxe_email',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_email',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_email',
		array(
			'label'       => __( 'Email Address', 'aqualuxe' ),
			'description' => __( 'Enter your business email address', 'aqualuxe' ),
			'section'     => 'aqualuxe_contact',
			'type'        => 'email',
		)
	);

	// Address.
	$wp_customize->add_setting(
		'aqualuxe_address_street',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_address_street',
		array(
			'label'       => __( 'Street Address', 'aqualuxe' ),
			'description' => __( 'Enter your street address', 'aqualuxe' ),
			'section'     => 'aqualuxe_contact',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'aqualuxe_address_city',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_address_city',
		array(
			'label'       => __( 'City', 'aqualuxe' ),
			'description' => __( 'Enter your city', 'aqualuxe' ),
			'section'     => 'aqualuxe_contact',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'aqualuxe_address_state',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_address_state',
		array(
			'label'       => __( 'State/Province', 'aqualuxe' ),
			'description' => __( 'Enter your state or province', 'aqualuxe' ),
			'section'     => 'aqualuxe_contact',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'aqualuxe_address_zip',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_address_zip',
		array(
			'label'       => __( 'ZIP/Postal Code', 'aqualuxe' ),
			'description' => __( 'Enter your ZIP or postal code', 'aqualuxe' ),
			'section'     => 'aqualuxe_contact',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'aqualuxe_address_country',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_address_country',
		array(
			'label'       => __( 'Country', 'aqualuxe' ),
			'description' => __( 'Enter your country', 'aqualuxe' ),
			'section'     => 'aqualuxe_contact',
			'type'        => 'text',
		)
	);

	// Google Maps.
	$wp_customize->add_setting(
		'aqualuxe_google_maps_embed',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_google_maps_embed',
		array(
			'label'       => __( 'Google Maps Embed Code', 'aqualuxe' ),
			'description' => __( 'Enter the Google Maps embed code for your location', 'aqualuxe' ),
			'section'     => 'aqualuxe_contact',
			'type'        => 'textarea',
		)
	);

	// Business hours.
	$wp_customize->add_setting(
		'aqualuxe_business_hours',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_business_hours',
		array(
			'label'       => __( 'Business Hours', 'aqualuxe' ),
			'description' => __( 'Enter your business hours (HTML allowed)', 'aqualuxe' ),
			'section'     => 'aqualuxe_contact',
			'type'        => 'textarea',
		)
	);
}

/**
 * Register performance settings.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_performance( $wp_customize ) {
	$wp_customize->add_section(
		'aqualuxe_performance',
		array(
			'title'       => __( 'Performance', 'aqualuxe' ),
			'description' => __( 'Configure performance optimization settings', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 100,
		)
	);

	// Lazy loading.
	$wp_customize->add_setting(
		'aqualuxe_enable_lazy_loading',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_enable_lazy_loading',
		array(
			'label'       => __( 'Enable Lazy Loading', 'aqualuxe' ),
			'description' => __( 'Lazy load images and iframes for better performance', 'aqualuxe' ),
			'section'     => 'aqualuxe_performance',
			'type'        => 'checkbox',
		)
	);

	// Preload critical assets.
	$wp_customize->add_setting(
		'aqualuxe_preload_assets',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_preload_assets',
		array(
			'label'       => __( 'Preload Critical Assets', 'aqualuxe' ),
			'description' => __( 'Preload critical CSS and fonts for faster rendering', 'aqualuxe' ),
			'section'     => 'aqualuxe_performance',
			'type'        => 'checkbox',
		)
	);

	// Disable emojis.
	$wp_customize->add_setting(
		'aqualuxe_disable_emojis',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_disable_emojis',
		array(
			'label'       => __( 'Disable WordPress Emojis', 'aqualuxe' ),
			'description' => __( 'Remove emoji scripts for better performance', 'aqualuxe' ),
			'section'     => 'aqualuxe_performance',
			'type'        => 'checkbox',
		)
	);

	// Disable embeds.
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
			'description' => __( 'Remove embed scripts for better performance (may affect embedded content)', 'aqualuxe' ),
			'section'     => 'aqualuxe_performance',
			'type'        => 'checkbox',
		)
	);

	// Minify HTML.
	$wp_customize->add_setting(
		'aqualuxe_minify_html',
		array(
			'default'           => false,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_minify_html',
		array(
			'label'       => __( 'Minify HTML Output', 'aqualuxe' ),
			'description' => __( 'Minify HTML output for better performance (may affect some plugins)', 'aqualuxe' ),
			'section'     => 'aqualuxe_performance',
			'type'        => 'checkbox',
		)
	);

	// Defer JavaScript.
	$wp_customize->add_setting(
		'aqualuxe_defer_js',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_defer_js',
		array(
			'label'       => __( 'Defer JavaScript', 'aqualuxe' ),
			'description' => __( 'Defer non-critical JavaScript for better performance', 'aqualuxe' ),
			'section'     => 'aqualuxe_performance',
			'type'        => 'checkbox',
		)
	);
}

/**
 * Register WooCommerce settings.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_woocommerce( $wp_customize ) {
	$wp_customize->add_section(
		'aqualuxe_woocommerce',
		array(
			'title'       => __( 'WooCommerce', 'aqualuxe' ),
			'description' => __( 'Configure WooCommerce settings', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 110,
		)
	);

	// Products per row.
	$wp_customize->add_setting(
		'aqualuxe_products_per_row',
		array(
			'default'           => '3',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_products_per_row',
		array(
			'label'       => __( 'Products Per Row', 'aqualuxe' ),
			'description' => __( 'Number of products to display per row', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 6,
				'step' => 1,
			),
		)
	);

	// Products per page.
	$wp_customize->add_setting(
		'aqualuxe_products_per_page',
		array(
			'default'           => '12',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_products_per_page',
		array(
			'label'       => __( 'Products Per Page', 'aqualuxe' ),
			'description' => __( 'Number of products to display per page', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 48,
				'step' => 1,
			),
		)
	);

	// Product gallery zoom.
	$wp_customize->add_setting(
		'aqualuxe_product_gallery_zoom',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_product_gallery_zoom',
		array(
			'label'       => __( 'Enable Product Gallery Zoom', 'aqualuxe' ),
			'description' => __( 'Allow customers to zoom in on product images', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce',
			'type'        => 'checkbox',
		)
	);

	// Product gallery lightbox.
	$wp_customize->add_setting(
		'aqualuxe_product_gallery_lightbox',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_product_gallery_lightbox',
		array(
			'label'       => __( 'Enable Product Gallery Lightbox', 'aqualuxe' ),
			'description' => __( 'Allow customers to view product images in a lightbox', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce',
			'type'        => 'checkbox',
		)
	);

	// Product gallery slider.
	$wp_customize->add_setting(
		'aqualuxe_product_gallery_slider',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_product_gallery_slider',
		array(
			'label'       => __( 'Enable Product Gallery Slider', 'aqualuxe' ),
			'description' => __( 'Display product images in a slider', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce',
			'type'        => 'checkbox',
		)
	);

	// Related products.
	$wp_customize->add_setting(
		'aqualuxe_related_products',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_related_products',
		array(
			'label'       => __( 'Show Related Products', 'aqualuxe' ),
			'description' => __( 'Display related products on single product pages', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce',
			'type'        => 'checkbox',
		)
	);

	// Up-sells.
	$wp_customize->add_setting(
		'aqualuxe_upsells',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_upsells',
		array(
			'label'       => __( 'Show Up-Sells', 'aqualuxe' ),
			'description' => __( 'Display up-sell products on single product pages', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce',
			'type'        => 'checkbox',
		)
	);

	// Cross-sells.
	$wp_customize->add_setting(
		'aqualuxe_cross_sells',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_cross_sells',
		array(
			'label'       => __( 'Show Cross-Sells', 'aqualuxe' ),
			'description' => __( 'Display cross-sell products on cart page', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce',
			'type'        => 'checkbox',
		)
	);

	// Quick view.
	$wp_customize->add_setting(
		'aqualuxe_quick_view',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_quick_view',
		array(
			'label'       => __( 'Enable Quick View', 'aqualuxe' ),
			'description' => __( 'Allow customers to view product details in a modal', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce',
			'type'        => 'checkbox',
		)
	);

	// Wishlist.
	$wp_customize->add_setting(
		'aqualuxe_wishlist',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_wishlist',
		array(
			'label'       => __( 'Enable Wishlist', 'aqualuxe' ),
			'description' => __( 'Allow customers to add products to a wishlist', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce',
			'type'        => 'checkbox',
		)
	);

	// Catalog mode.
	$wp_customize->add_setting(
		'aqualuxe_catalog_mode',
		array(
			'default'           => false,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_catalog_mode',
		array(
			'label'       => __( 'Enable Catalog Mode', 'aqualuxe' ),
			'description' => __( 'Hide prices and add to cart buttons (display products only)', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce',
			'type'        => 'checkbox',
		)
	);

	// Fallback content.
	$wp_customize->add_setting(
		'aqualuxe_woocommerce_fallback_content',
		array(
			'default'           => __( '<h2>Our Products</h2><p>We offer a wide range of premium aquatic products. Please install WooCommerce to enable our online store.</p><a href="#" class="button">Contact Us</a>', 'aqualuxe' ),
			'sanitize_callback' => 'wp_kses_post',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_woocommerce_fallback_content',
		array(
			'label'       => __( 'WooCommerce Fallback Content', 'aqualuxe' ),
			'description' => __( 'Content to display when WooCommerce is not active (HTML allowed)', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce',
			'type'        => 'textarea',
		)
	);
}

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
 * Render the site logo for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_logo() {
	the_custom_logo();
}

/**
 * Sanitize checkbox.
 *
 * @param bool $input Input value.
 * @return bool Sanitized value.
 */
function aqualuxe_sanitize_checkbox( $input ) {
	return ( isset( $input ) && true === $input ) ? true : false;
}

/**
 * Sanitize select.
 *
 * @param string $input   Input value.
 * @param object $setting Setting object.
 * @return string Sanitized value.
 */
function aqualuxe_sanitize_select( $input, $setting ) {
	$choices = $setting->manager->get_control( $setting->id )->choices;
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize multi-select.
 *
 * @param array $input Input value.
 * @return array Sanitized value.
 */
function aqualuxe_sanitize_multi_select( $input ) {
	if ( ! is_array( $input ) ) {
		return array();
	}

	foreach ( $input as $key => $value ) {
		$input[ $key ] = sanitize_text_field( $value );
	}

	return $input;
}

/**
 * Sanitize float.
 *
 * @param float $input Input value.
 * @return float Sanitized value.
 */
function aqualuxe_sanitize_float( $input ) {
	return filter_var( $input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
	wp_enqueue_script(
		'aqualuxe-customizer',
		AQUALUXE_ASSETS_URI . 'js/customizer.js',
		array( 'customize-preview' ),
		AQUALUXE_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'aqualuxe_customize_preview_js' );