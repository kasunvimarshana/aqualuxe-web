<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
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

	// Add sections
	aqualuxe_customize_general_settings( $wp_customize );
	aqualuxe_customize_header_settings( $wp_customize );
	aqualuxe_customize_footer_settings( $wp_customize );
	aqualuxe_customize_blog_settings( $wp_customize );
	aqualuxe_customize_social_settings( $wp_customize );
	aqualuxe_customize_woocommerce_settings( $wp_customize );
	aqualuxe_customize_typography_settings( $wp_customize );
	aqualuxe_customize_colors_settings( $wp_customize );
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
	wp_enqueue_script( 'aqualuxe-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), AQUALUXE_VERSION, true );
}
add_action( 'customize_preview_init', 'aqualuxe_customize_preview_js' );

/**
 * General Settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_general_settings( $wp_customize ) {
	// General Settings Section
	$wp_customize->add_section(
		'aqualuxe_general_settings',
		array(
			'title'    => __( 'General Settings', 'aqualuxe' ),
			'priority' => 10,
			'panel'    => 'aqualuxe_theme_options',
		)
	);

	// Container Width
	$wp_customize->add_setting(
		'aqualuxe_container_width',
		array(
			'default'           => '1280',
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_container_width',
		array(
			'label'       => __( 'Container Width (px)', 'aqualuxe' ),
			'description' => __( 'Set the maximum width of the content container.', 'aqualuxe' ),
			'section'     => 'aqualuxe_general_settings',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 960,
				'max'  => 1920,
				'step' => 10,
			),
		)
	);

	// Dark Mode Default
	$wp_customize->add_setting(
		'aqualuxe_dark_mode_default',
		array(
			'default'           => false,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_dark_mode_default',
		array(
			'label'       => __( 'Dark Mode by Default', 'aqualuxe' ),
			'description' => __( 'Enable dark mode by default for all visitors.', 'aqualuxe' ),
			'section'     => 'aqualuxe_general_settings',
			'type'        => 'checkbox',
		)
	);

	// Dark Mode Toggle
	$wp_customize->add_setting(
		'aqualuxe_dark_mode_toggle',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_dark_mode_toggle',
		array(
			'label'       => __( 'Show Dark Mode Toggle', 'aqualuxe' ),
			'description' => __( 'Show a toggle button for users to switch between light and dark mode.', 'aqualuxe' ),
			'section'     => 'aqualuxe_general_settings',
			'type'        => 'checkbox',
		)
	);

	// Breadcrumbs
	$wp_customize->add_setting(
		'aqualuxe_breadcrumbs_enable',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_breadcrumbs_enable',
		array(
			'label'       => __( 'Enable Breadcrumbs', 'aqualuxe' ),
			'description' => __( 'Show breadcrumbs navigation on pages.', 'aqualuxe' ),
			'section'     => 'aqualuxe_general_settings',
			'type'        => 'checkbox',
		)
	);

	// Back to Top Button
	$wp_customize->add_setting(
		'aqualuxe_back_to_top',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_back_to_top',
		array(
			'label'       => __( 'Show Back to Top Button', 'aqualuxe' ),
			'description' => __( 'Display a button to scroll back to the top of the page.', 'aqualuxe' ),
			'section'     => 'aqualuxe_general_settings',
			'type'        => 'checkbox',
		)
	);

	// Preloader
	$wp_customize->add_setting(
		'aqualuxe_preloader',
		array(
			'default'           => false,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_preloader',
		array(
			'label'       => __( 'Enable Preloader', 'aqualuxe' ),
			'description' => __( 'Show a loading animation while the page loads.', 'aqualuxe' ),
			'section'     => 'aqualuxe_general_settings',
			'type'        => 'checkbox',
		)
	);
}

/**
 * Header Settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_header_settings( $wp_customize ) {
	// Header Settings Section
	$wp_customize->add_section(
		'aqualuxe_header_settings',
		array(
			'title'    => __( 'Header Settings', 'aqualuxe' ),
			'priority' => 20,
			'panel'    => 'aqualuxe_theme_options',
		)
	);

	// Header Layout
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
			'description' => __( 'Choose the layout for your site header.', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_settings',
			'type'        => 'select',
			'choices'     => array(
				'standard'    => __( 'Standard', 'aqualuxe' ),
				'centered'    => __( 'Centered', 'aqualuxe' ),
				'split'       => __( 'Split', 'aqualuxe' ),
				'transparent' => __( 'Transparent', 'aqualuxe' ),
			),
		)
	);

	// Sticky Header
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
			'label'       => __( 'Sticky Header', 'aqualuxe' ),
			'description' => __( 'Keep the header visible when scrolling down.', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_settings',
			'type'        => 'checkbox',
		)
	);

	// Top Bar
	$wp_customize->add_setting(
		'aqualuxe_top_bar_enable',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_top_bar_enable',
		array(
			'label'       => __( 'Enable Top Bar', 'aqualuxe' ),
			'description' => __( 'Show the top bar above the header.', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_settings',
			'type'        => 'checkbox',
		)
	);

	// Top Bar Text
	$wp_customize->add_setting(
		'aqualuxe_top_bar_text',
		array(
			'default'           => __( 'Welcome to AquaLuxe!', 'aqualuxe' ),
			'sanitize_callback' => 'wp_kses_post',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_top_bar_text',
		array(
			'label'       => __( 'Top Bar Text', 'aqualuxe' ),
			'description' => __( 'Text to display in the top bar.', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_settings',
			'type'        => 'text',
		)
	);

	// Top Bar Phone
	$wp_customize->add_setting(
		'aqualuxe_top_bar_phone',
		array(
			'default'           => '+1 (555) 123-4567',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_top_bar_phone',
		array(
			'label'       => __( 'Phone Number', 'aqualuxe' ),
			'description' => __( 'Phone number to display in the top bar.', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_settings',
			'type'        => 'text',
		)
	);

	// Top Bar Email
	$wp_customize->add_setting(
		'aqualuxe_top_bar_email',
		array(
			'default'           => 'info@aqualuxe.com',
			'sanitize_callback' => 'sanitize_email',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_top_bar_email',
		array(
			'label'       => __( 'Email Address', 'aqualuxe' ),
			'description' => __( 'Email address to display in the top bar.', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_settings',
			'type'        => 'email',
		)
	);

	// Header Search
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
			'description' => __( 'Display a search icon in the header.', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_settings',
			'type'        => 'checkbox',
		)
	);

	// WooCommerce Header Icons
	if ( class_exists( 'WooCommerce' ) ) {
		// Header Cart
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
				'description' => __( 'Display a cart icon in the header.', 'aqualuxe' ),
				'section'     => 'aqualuxe_header_settings',
				'type'        => 'checkbox',
			)
		);

		// Header Account
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
				'description' => __( 'Display an account icon in the header.', 'aqualuxe' ),
				'section'     => 'aqualuxe_header_settings',
				'type'        => 'checkbox',
			)
		);

		// Header Wishlist
		$wp_customize->add_setting(
			'aqualuxe_header_wishlist',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_header_wishlist',
			array(
				'label'       => __( 'Show Wishlist in Header', 'aqualuxe' ),
				'description' => __( 'Display a wishlist icon in the header.', 'aqualuxe' ),
				'section'     => 'aqualuxe_header_settings',
				'type'        => 'checkbox',
			)
		);
	}
}

/**
 * Footer Settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_footer_settings( $wp_customize ) {
	// Footer Settings Section
	$wp_customize->add_section(
		'aqualuxe_footer_settings',
		array(
			'title'    => __( 'Footer Settings', 'aqualuxe' ),
			'priority' => 30,
			'panel'    => 'aqualuxe_theme_options',
		)
	);

	// Footer Widgets
	$wp_customize->add_setting(
		'aqualuxe_footer_widgets',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_footer_widgets',
		array(
			'label'       => __( 'Enable Footer Widgets', 'aqualuxe' ),
			'description' => __( 'Show widget areas in the footer.', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer_settings',
			'type'        => 'checkbox',
		)
	);

	// Footer Columns
	$wp_customize->add_setting(
		'aqualuxe_footer_columns',
		array(
			'default'           => '4',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_footer_columns',
		array(
			'label'       => __( 'Footer Widget Columns', 'aqualuxe' ),
			'description' => __( 'Choose the number of widget columns in the footer.', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer_settings',
			'type'        => 'select',
			'choices'     => array(
				'1' => __( '1 Column', 'aqualuxe' ),
				'2' => __( '2 Columns', 'aqualuxe' ),
				'3' => __( '3 Columns', 'aqualuxe' ),
				'4' => __( '4 Columns', 'aqualuxe' ),
			),
		)
	);

	// Footer Copyright Text
	$wp_customize->add_setting(
		'aqualuxe_footer_copyright',
		array(
			'default'           => __( '© {year} {site_title}. All rights reserved.', 'aqualuxe' ),
			'sanitize_callback' => 'wp_kses_post',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_footer_copyright',
		array(
			'label'       => __( 'Footer Copyright Text', 'aqualuxe' ),
			'description' => __( 'Enter your copyright text. Use {year} for current year and {site_title} for site name.', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer_settings',
			'type'        => 'textarea',
		)
	);

	// Payment Icons
	$wp_customize->add_setting(
		'aqualuxe_payment_icons_enable',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_payment_icons_enable',
		array(
			'label'       => __( 'Show Payment Icons', 'aqualuxe' ),
			'description' => __( 'Display payment method icons in the footer.', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer_settings',
			'type'        => 'checkbox',
		)
	);

	// Individual Payment Icons
	$payment_icons = array(
		'visa'       => __( 'Visa', 'aqualuxe' ),
		'mastercard' => __( 'Mastercard', 'aqualuxe' ),
		'amex'       => __( 'American Express', 'aqualuxe' ),
		'paypal'     => __( 'PayPal', 'aqualuxe' ),
		'apple_pay'  => __( 'Apple Pay', 'aqualuxe' ),
		'google_pay' => __( 'Google Pay', 'aqualuxe' ),
	);

	foreach ( $payment_icons as $icon_id => $icon_label ) {
		$wp_customize->add_setting(
			'aqualuxe_payment_icon_' . $icon_id,
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_payment_icon_' . $icon_id,
			array(
				'label'    => $icon_label,
				'section'  => 'aqualuxe_footer_settings',
				'type'     => 'checkbox',
				'priority' => 50,
			)
		);
	}

	// Newsletter Section
	$wp_customize->add_setting(
		'aqualuxe_newsletter_enable',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_newsletter_enable',
		array(
			'label'       => __( 'Enable Newsletter Section', 'aqualuxe' ),
			'description' => __( 'Show a newsletter signup form in the footer.', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer_settings',
			'type'        => 'checkbox',
		)
	);

	// Newsletter Title
	$wp_customize->add_setting(
		'aqualuxe_newsletter_title',
		array(
			'default'           => __( 'Subscribe to Our Newsletter', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_newsletter_title',
		array(
			'label'    => __( 'Newsletter Title', 'aqualuxe' ),
			'section'  => 'aqualuxe_footer_settings',
			'type'     => 'text',
			'priority' => 60,
		)
	);

	// Newsletter Description
	$wp_customize->add_setting(
		'aqualuxe_newsletter_description',
		array(
			'default'           => __( 'Stay updated with our latest news and offers.', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_newsletter_description',
		array(
			'label'    => __( 'Newsletter Description', 'aqualuxe' ),
			'section'  => 'aqualuxe_footer_settings',
			'type'     => 'text',
			'priority' => 61,
		)
	);

	// Newsletter Button Text
	$wp_customize->add_setting(
		'aqualuxe_newsletter_button_text',
		array(
			'default'           => __( 'Subscribe', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_newsletter_button_text',
		array(
			'label'    => __( 'Newsletter Button Text', 'aqualuxe' ),
			'section'  => 'aqualuxe_footer_settings',
			'type'     => 'text',
			'priority' => 62,
		)
	);

	// Newsletter Privacy Text
	$wp_customize->add_setting(
		'aqualuxe_newsletter_privacy_text',
		array(
			'default'           => __( 'By subscribing, you agree to our Privacy Policy and consent to receive marketing emails.', 'aqualuxe' ),
			'sanitize_callback' => 'wp_kses_post',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_newsletter_privacy_text',
		array(
			'label'    => __( 'Newsletter Privacy Text', 'aqualuxe' ),
			'section'  => 'aqualuxe_footer_settings',
			'type'     => 'textarea',
			'priority' => 63,
		)
	);
}

/**
 * Blog Settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_blog_settings( $wp_customize ) {
	// Blog Settings Section
	$wp_customize->add_section(
		'aqualuxe_blog_settings',
		array(
			'title'    => __( 'Blog Settings', 'aqualuxe' ),
			'priority' => 40,
			'panel'    => 'aqualuxe_theme_options',
		)
	);

	// Blog Layout
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
			'description' => __( 'Choose the layout for your blog archive pages.', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'select',
			'choices'     => array(
				'grid'    => __( 'Grid', 'aqualuxe' ),
				'list'    => __( 'List', 'aqualuxe' ),
				'masonry' => __( 'Masonry', 'aqualuxe' ),
			),
		)
	);

	// Blog Sidebar
	$wp_customize->add_setting(
		'aqualuxe_blog_sidebar',
		array(
			'default'           => 'right',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_blog_sidebar',
		array(
			'label'       => __( 'Blog Sidebar Position', 'aqualuxe' ),
			'description' => __( 'Choose the sidebar position for blog pages.', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'select',
			'choices'     => array(
				'right' => __( 'Right Sidebar', 'aqualuxe' ),
				'left'  => __( 'Left Sidebar', 'aqualuxe' ),
				'none'  => __( 'No Sidebar', 'aqualuxe' ),
			),
		)
	);

	// Posts Per Page
	$wp_customize->add_setting(
		'aqualuxe_posts_per_page',
		array(
			'default'           => get_option( 'posts_per_page' ),
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_posts_per_page',
		array(
			'label'       => __( 'Posts Per Page', 'aqualuxe' ),
			'description' => __( 'Number of posts to display on blog pages.', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'number',
			'input_attrs' => array(
				'min' => 1,
				'max' => 50,
			),
		)
	);

	// Excerpt Length
	$wp_customize->add_setting(
		'aqualuxe_excerpt_length',
		array(
			'default'           => 55,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_excerpt_length',
		array(
			'label'       => __( 'Excerpt Length', 'aqualuxe' ),
			'description' => __( 'Number of words in post excerpts.', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'number',
			'input_attrs' => array(
				'min' => 10,
				'max' => 200,
			),
		)
	);

	// Read More Text
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
			'description' => __( 'Text for the read more link.', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'text',
		)
	);

	// Featured Image
	$wp_customize->add_setting(
		'aqualuxe_featured_image',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_featured_image',
		array(
			'label'       => __( 'Show Featured Image', 'aqualuxe' ),
			'description' => __( 'Display featured images on blog and single posts.', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'checkbox',
		)
	);

	// Post Meta
	$wp_customize->add_setting(
		'aqualuxe_post_meta',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_post_meta',
		array(
			'label'       => __( 'Show Post Meta', 'aqualuxe' ),
			'description' => __( 'Display post meta information (author, date, comments).', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'checkbox',
		)
	);

	// Post Author
	$wp_customize->add_setting(
		'aqualuxe_post_author',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_post_author',
		array(
			'label'       => __( 'Show Post Author', 'aqualuxe' ),
			'description' => __( 'Display the post author name.', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'checkbox',
		)
	);

	// Post Date
	$wp_customize->add_setting(
		'aqualuxe_post_date',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_post_date',
		array(
			'label'       => __( 'Show Post Date', 'aqualuxe' ),
			'description' => __( 'Display the post publication date.', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'checkbox',
		)
	);

	// Post Comments
	$wp_customize->add_setting(
		'aqualuxe_post_comments',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_post_comments',
		array(
			'label'       => __( 'Show Post Comments Count', 'aqualuxe' ),
			'description' => __( 'Display the number of comments.', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'checkbox',
		)
	);

	// Post Categories
	$wp_customize->add_setting(
		'aqualuxe_post_categories',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_post_categories',
		array(
			'label'       => __( 'Show Post Categories', 'aqualuxe' ),
			'description' => __( 'Display the post categories.', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'checkbox',
		)
	);

	// Post Tags
	$wp_customize->add_setting(
		'aqualuxe_post_tags',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_post_tags',
		array(
			'label'       => __( 'Show Post Tags', 'aqualuxe' ),
			'description' => __( 'Display the post tags.', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'checkbox',
		)
	);

	// Related Posts
	$wp_customize->add_setting(
		'aqualuxe_related_posts',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_related_posts',
		array(
			'label'       => __( 'Show Related Posts', 'aqualuxe' ),
			'description' => __( 'Display related posts on single post pages.', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'checkbox',
		)
	);

	// Author Box
	$wp_customize->add_setting(
		'aqualuxe_author_box',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_author_box',
		array(
			'label'       => __( 'Show Author Box', 'aqualuxe' ),
			'description' => __( 'Display author information on single post pages.', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'checkbox',
		)
	);

	// Social Sharing
	$wp_customize->add_setting(
		'aqualuxe_social_sharing',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_social_sharing',
		array(
			'label'       => __( 'Show Social Sharing', 'aqualuxe' ),
			'description' => __( 'Display social sharing buttons on single post pages.', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'checkbox',
		)
	);
}

/**
 * Social Media Settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_social_settings( $wp_customize ) {
	// Social Media Section
	$wp_customize->add_section(
		'aqualuxe_social_settings',
		array(
			'title'    => __( 'Social Media', 'aqualuxe' ),
			'priority' => 50,
			'panel'    => 'aqualuxe_theme_options',
		)
	);

	// Social Media Links
	$social_platforms = array(
		'facebook'  => __( 'Facebook URL', 'aqualuxe' ),
		'twitter'   => __( 'Twitter URL', 'aqualuxe' ),
		'instagram' => __( 'Instagram URL', 'aqualuxe' ),
		'linkedin'  => __( 'LinkedIn URL', 'aqualuxe' ),
		'youtube'   => __( 'YouTube URL', 'aqualuxe' ),
		'pinterest' => __( 'Pinterest URL', 'aqualuxe' ),
	);

	foreach ( $social_platforms as $platform => $label ) {
		$wp_customize->add_setting(
			'aqualuxe_' . $platform . '_url',
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_' . $platform . '_url',
			array(
				'label'    => $label,
				'section'  => 'aqualuxe_social_settings',
				'type'     => 'url',
				'priority' => 10,
			)
		);
	}

	// Twitter Username (without @)
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
			'description' => __( 'Enter your Twitter username without the @ symbol (used for Twitter Cards).', 'aqualuxe' ),
			'section'     => 'aqualuxe_social_settings',
			'type'        => 'text',
			'priority'    => 20,
		)
	);

	// Social Icons in Header
	$wp_customize->add_setting(
		'aqualuxe_social_icons_header',
		array(
			'default'           => false,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_social_icons_header',
		array(
			'label'       => __( 'Show Social Icons in Header', 'aqualuxe' ),
			'description' => __( 'Display social media icons in the header.', 'aqualuxe' ),
			'section'     => 'aqualuxe_social_settings',
			'type'        => 'checkbox',
			'priority'    => 30,
		)
	);

	// Social Icons in Footer
	$wp_customize->add_setting(
		'aqualuxe_social_icons_footer',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_social_icons_footer',
		array(
			'label'       => __( 'Show Social Icons in Footer', 'aqualuxe' ),
			'description' => __( 'Display social media icons in the footer.', 'aqualuxe' ),
			'section'     => 'aqualuxe_social_settings',
			'type'        => 'checkbox',
			'priority'    => 31,
		)
	);
}

/**
 * WooCommerce Settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_woocommerce_settings( $wp_customize ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	// WooCommerce Section
	$wp_customize->add_section(
		'aqualuxe_woocommerce_settings',
		array(
			'title'    => __( 'WooCommerce Settings', 'aqualuxe' ),
			'priority' => 60,
			'panel'    => 'aqualuxe_theme_options',
		)
	);

	// Shop Sidebar
	$wp_customize->add_setting(
		'aqualuxe_shop_sidebar',
		array(
			'default'           => 'right',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_shop_sidebar',
		array(
			'label'       => __( 'Shop Sidebar Position', 'aqualuxe' ),
			'description' => __( 'Choose the sidebar position for shop pages.', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'select',
			'choices'     => array(
				'right' => __( 'Right Sidebar', 'aqualuxe' ),
				'left'  => __( 'Left Sidebar', 'aqualuxe' ),
				'none'  => __( 'No Sidebar', 'aqualuxe' ),
			),
		)
	);

	// Products Per Page
	$wp_customize->add_setting(
		'aqualuxe_products_per_page',
		array(
			'default'           => 12,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_products_per_page',
		array(
			'label'       => __( 'Products Per Page', 'aqualuxe' ),
			'description' => __( 'Number of products to display on shop pages.', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'number',
			'input_attrs' => array(
				'min' => 1,
				'max' => 100,
			),
		)
	);

	// Product Columns
	$wp_customize->add_setting(
		'aqualuxe_product_columns',
		array(
			'default'           => 4,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_product_columns',
		array(
			'label'       => __( 'Product Columns', 'aqualuxe' ),
			'description' => __( 'Number of columns on shop pages.', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'number',
			'input_attrs' => array(
				'min' => 1,
				'max' => 6,
			),
		)
	);

	// Related Products
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
			'description' => __( 'Display related products on single product pages.', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'checkbox',
		)
	);

	// Quick View
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
			'description' => __( 'Allow customers to view product details in a popup.', 'aqualuxe' ),
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
		)
	);

	$wp_customize->add_control(
		'aqualuxe_wishlist',
		array(
			'label'       => __( 'Enable Wishlist', 'aqualuxe' ),
			'description' => __( 'Allow customers to add products to a wishlist.', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'checkbox',
		)
	);

	// Product Zoom
	$wp_customize->add_setting(
		'aqualuxe_product_zoom',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_product_zoom',
		array(
			'label'       => __( 'Enable Product Image Zoom', 'aqualuxe' ),
			'description' => __( 'Allow customers to zoom in on product images.', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'checkbox',
		)
	);

	// Product Gallery Lightbox
	$wp_customize->add_setting(
		'aqualuxe_product_lightbox',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_product_lightbox',
		array(
			'label'       => __( 'Enable Product Gallery Lightbox', 'aqualuxe' ),
			'description' => __( 'Allow customers to view product images in a lightbox.', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'checkbox',
		)
	);

	// Product Gallery Slider
	$wp_customize->add_setting(
		'aqualuxe_product_slider',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_product_slider',
		array(
			'label'       => __( 'Enable Product Gallery Slider', 'aqualuxe' ),
			'description' => __( 'Display product gallery images in a slider.', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'checkbox',
		)
	);

	// Sale Badge
	$wp_customize->add_setting(
		'aqualuxe_sale_badge',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_sale_badge',
		array(
			'label'       => __( 'Show Sale Badge', 'aqualuxe' ),
			'description' => __( 'Display a sale badge on discounted products.', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'checkbox',
		)
	);

	// Sale Badge Text
	$wp_customize->add_setting(
		'aqualuxe_sale_badge_text',
		array(
			'default'           => __( 'Sale!', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_sale_badge_text',
		array(
			'label'       => __( 'Sale Badge Text', 'aqualuxe' ),
			'description' => __( 'Text to display on the sale badge.', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'text',
		)
	);

	// Out of Stock Badge
	$wp_customize->add_setting(
		'aqualuxe_out_of_stock_badge',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_out_of_stock_badge',
		array(
			'label'       => __( 'Show Out of Stock Badge', 'aqualuxe' ),
			'description' => __( 'Display an out of stock badge on unavailable products.', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'checkbox',
		)
	);

	// Out of Stock Badge Text
	$wp_customize->add_setting(
		'aqualuxe_out_of_stock_badge_text',
		array(
			'default'           => __( 'Out of Stock', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_out_of_stock_badge_text',
		array(
			'label'       => __( 'Out of Stock Badge Text', 'aqualuxe' ),
			'description' => __( 'Text to display on the out of stock badge.', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'text',
		)
	);

	// New Badge
	$wp_customize->add_setting(
		'aqualuxe_new_badge',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_new_badge',
		array(
			'label'       => __( 'Show New Badge', 'aqualuxe' ),
			'description' => __( 'Display a new badge on recently added products.', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'checkbox',
		)
	);

	// New Badge Text
	$wp_customize->add_setting(
		'aqualuxe_new_badge_text',
		array(
			'default'           => __( 'New!', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_new_badge_text',
		array(
			'label'       => __( 'New Badge Text', 'aqualuxe' ),
			'description' => __( 'Text to display on the new badge.', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'text',
		)
	);

	// New Badge Days
	$wp_customize->add_setting(
		'aqualuxe_new_badge_days',
		array(
			'default'           => 14,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_new_badge_days',
		array(
			'label'       => __( 'New Badge Days', 'aqualuxe' ),
			'description' => __( 'Number of days to display the new badge after product creation.', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_settings',
			'type'        => 'number',
			'input_attrs' => array(
				'min' => 1,
				'max' => 100,
			),
		)
	);
}

/**
 * Typography Settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_typography_settings( $wp_customize ) {
	// Typography Section
	$wp_customize->add_section(
		'aqualuxe_typography_settings',
		array(
			'title'    => __( 'Typography', 'aqualuxe' ),
			'priority' => 70,
			'panel'    => 'aqualuxe_theme_options',
		)
	);

	// Body Font Family
	$wp_customize->add_setting(
		'aqualuxe_body_font_family',
		array(
			'default'           => 'Inter, sans-serif',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_body_font_family',
		array(
			'label'       => __( 'Body Font Family', 'aqualuxe' ),
			'description' => __( 'Font family for body text.', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography_settings',
			'type'        => 'select',
			'choices'     => array(
				'Inter, sans-serif'                => 'Inter (Default)',
				'Roboto, sans-serif'               => 'Roboto',
				'Open Sans, sans-serif'            => 'Open Sans',
				'Lato, sans-serif'                 => 'Lato',
				'Montserrat, sans-serif'           => 'Montserrat',
				'Poppins, sans-serif'              => 'Poppins',
				'Raleway, sans-serif'              => 'Raleway',
				'Nunito, sans-serif'               => 'Nunito',
				'Playfair Display, serif'          => 'Playfair Display',
				'Merriweather, serif'              => 'Merriweather',
				'Source Sans Pro, sans-serif'      => 'Source Sans Pro',
				'PT Sans, sans-serif'              => 'PT Sans',
				'Roboto Condensed, sans-serif'     => 'Roboto Condensed',
				'Noto Sans, sans-serif'            => 'Noto Sans',
				'Work Sans, sans-serif'            => 'Work Sans',
				'Quicksand, sans-serif'            => 'Quicksand',
				'Rubik, sans-serif'                => 'Rubik',
				'Nunito Sans, sans-serif'          => 'Nunito Sans',
				'Mulish, sans-serif'               => 'Mulish',
				'Karla, sans-serif'                => 'Karla',
			),
		)
	);

	// Heading Font Family
	$wp_customize->add_setting(
		'aqualuxe_heading_font_family',
		array(
			'default'           => 'Inter, sans-serif',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_heading_font_family',
		array(
			'label'       => __( 'Heading Font Family', 'aqualuxe' ),
			'description' => __( 'Font family for headings.', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography_settings',
			'type'        => 'select',
			'choices'     => array(
				'Inter, sans-serif'                => 'Inter (Default)',
				'Roboto, sans-serif'               => 'Roboto',
				'Open Sans, sans-serif'            => 'Open Sans',
				'Lato, sans-serif'                 => 'Lato',
				'Montserrat, sans-serif'           => 'Montserrat',
				'Poppins, sans-serif'              => 'Poppins',
				'Raleway, sans-serif'              => 'Raleway',
				'Nunito, sans-serif'               => 'Nunito',
				'Playfair Display, serif'          => 'Playfair Display',
				'Merriweather, serif'              => 'Merriweather',
				'Source Sans Pro, sans-serif'      => 'Source Sans Pro',
				'PT Sans, sans-serif'              => 'PT Sans',
				'Roboto Condensed, sans-serif'     => 'Roboto Condensed',
				'Noto Sans, sans-serif'            => 'Noto Sans',
				'Work Sans, sans-serif'            => 'Work Sans',
				'Quicksand, sans-serif'            => 'Quicksand',
				'Rubik, sans-serif'                => 'Rubik',
				'Nunito Sans, sans-serif'          => 'Nunito Sans',
				'Mulish, sans-serif'               => 'Mulish',
				'Karla, sans-serif'                => 'Karla',
			),
		)
	);

	// Body Font Size
	$wp_customize->add_setting(
		'aqualuxe_body_font_size',
		array(
			'default'           => '16',
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_body_font_size',
		array(
			'label'       => __( 'Body Font Size (px)', 'aqualuxe' ),
			'description' => __( 'Font size for body text.', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography_settings',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 12,
				'max'  => 24,
				'step' => 1,
			),
		)
	);

	// Line Height
	$wp_customize->add_setting(
		'aqualuxe_line_height',
		array(
			'default'           => '1.6',
			'sanitize_callback' => 'aqualuxe_sanitize_float',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_line_height',
		array(
			'label'       => __( 'Line Height', 'aqualuxe' ),
			'description' => __( 'Line height for body text.', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography_settings',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 2,
				'step' => 0.1,
			),
		)
	);

	// Heading Line Height
	$wp_customize->add_setting(
		'aqualuxe_heading_line_height',
		array(
			'default'           => '1.2',
			'sanitize_callback' => 'aqualuxe_sanitize_float',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_heading_line_height',
		array(
			'label'       => __( 'Heading Line Height', 'aqualuxe' ),
			'description' => __( 'Line height for headings.', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography_settings',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 2,
				'step' => 0.1,
			),
		)
	);

	// Font Weight
	$wp_customize->add_setting(
		'aqualuxe_font_weight',
		array(
			'default'           => '400',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_font_weight',
		array(
			'label'       => __( 'Body Font Weight', 'aqualuxe' ),
			'description' => __( 'Font weight for body text.', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography_settings',
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

	// Heading Font Weight
	$wp_customize->add_setting(
		'aqualuxe_heading_font_weight',
		array(
			'default'           => '700',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_heading_font_weight',
		array(
			'label'       => __( 'Heading Font Weight', 'aqualuxe' ),
			'description' => __( 'Font weight for headings.', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography_settings',
			'type'        => 'select',
			'choices'     => array(
				'400' => __( 'Regular (400)', 'aqualuxe' ),
				'500' => __( 'Medium (500)', 'aqualuxe' ),
				'600' => __( 'Semi-Bold (600)', 'aqualuxe' ),
				'700' => __( 'Bold (700)', 'aqualuxe' ),
				'800' => __( 'Extra-Bold (800)', 'aqualuxe' ),
				'900' => __( 'Black (900)', 'aqualuxe' ),
			),
		)
	);
}

/**
 * Colors Settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_colors_settings( $wp_customize ) {
	// Colors Section
	$wp_customize->add_section(
		'aqualuxe_colors_settings',
		array(
			'title'    => __( 'Colors', 'aqualuxe' ),
			'priority' => 80,
			'panel'    => 'aqualuxe_theme_options',
		)
	);

	// Primary Color
	$wp_customize->add_setting(
		'aqualuxe_primary_color',
		array(
			'default'           => '#0891b2',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_primary_color',
			array(
				'label'       => __( 'Primary Color', 'aqualuxe' ),
				'description' => __( 'Main theme color used for links, buttons, and accents.', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);

	// Secondary Color
	$wp_customize->add_setting(
		'aqualuxe_secondary_color',
		array(
			'default'           => '#6366f1',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_secondary_color',
			array(
				'label'       => __( 'Secondary Color', 'aqualuxe' ),
				'description' => __( 'Secondary theme color used for buttons and accents.', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);

	// Body Text Color
	$wp_customize->add_setting(
		'aqualuxe_body_text_color',
		array(
			'default'           => '#374151',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_body_text_color',
			array(
				'label'       => __( 'Body Text Color', 'aqualuxe' ),
				'description' => __( 'Color for body text.', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);

	// Heading Color
	$wp_customize->add_setting(
		'aqualuxe_heading_color',
		array(
			'default'           => '#1f2937',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_heading_color',
			array(
				'label'       => __( 'Heading Color', 'aqualuxe' ),
				'description' => __( 'Color for headings.', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);

	// Link Color
	$wp_customize->add_setting(
		'aqualuxe_link_color',
		array(
			'default'           => '#0891b2',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_link_color',
			array(
				'label'       => __( 'Link Color', 'aqualuxe' ),
				'description' => __( 'Color for links.', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);

	// Link Hover Color
	$wp_customize->add_setting(
		'aqualuxe_link_hover_color',
		array(
			'default'           => '#0e7490',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_link_hover_color',
			array(
				'label'       => __( 'Link Hover Color', 'aqualuxe' ),
				'description' => __( 'Color for links on hover.', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);

	// Button Text Color
	$wp_customize->add_setting(
		'aqualuxe_button_text_color',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_button_text_color',
			array(
				'label'       => __( 'Button Text Color', 'aqualuxe' ),
				'description' => __( 'Text color for buttons.', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);

	// Button Background Color
	$wp_customize->add_setting(
		'aqualuxe_button_background_color',
		array(
			'default'           => '#0891b2',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_button_background_color',
			array(
				'label'       => __( 'Button Background Color', 'aqualuxe' ),
				'description' => __( 'Background color for buttons.', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);

	// Button Hover Background Color
	$wp_customize->add_setting(
		'aqualuxe_button_hover_background_color',
		array(
			'default'           => '#0e7490',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_button_hover_background_color',
			array(
				'label'       => __( 'Button Hover Background Color', 'aqualuxe' ),
				'description' => __( 'Background color for buttons on hover.', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);

	// Header Background Color
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
				'description' => __( 'Background color for the header.', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);

	// Header Text Color
	$wp_customize->add_setting(
		'aqualuxe_header_text_color',
		array(
			'default'           => '#1f2937',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_header_text_color',
			array(
				'label'       => __( 'Header Text Color', 'aqualuxe' ),
				'description' => __( 'Text color for the header.', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);

	// Footer Background Color
	$wp_customize->add_setting(
		'aqualuxe_footer_background_color',
		array(
			'default'           => '#1f2937',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_footer_background_color',
			array(
				'label'       => __( 'Footer Background Color', 'aqualuxe' ),
				'description' => __( 'Background color for the footer.', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);

	// Footer Text Color
	$wp_customize->add_setting(
		'aqualuxe_footer_text_color',
		array(
			'default'           => '#f9fafb',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_footer_text_color',
			array(
				'label'       => __( 'Footer Text Color', 'aqualuxe' ),
				'description' => __( 'Text color for the footer.', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);

	// Dark Mode Colors
	$wp_customize->add_setting(
		'aqualuxe_dark_mode_background_color',
		array(
			'default'           => '#111827',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_dark_mode_background_color',
			array(
				'label'       => __( 'Dark Mode Background Color', 'aqualuxe' ),
				'description' => __( 'Background color for dark mode.', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);

	// Dark Mode Text Color
	$wp_customize->add_setting(
		'aqualuxe_dark_mode_text_color',
		array(
			'default'           => '#f9fafb',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_dark_mode_text_color',
			array(
				'label'       => __( 'Dark Mode Text Color', 'aqualuxe' ),
				'description' => __( 'Text color for dark mode.', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_settings',
			)
		)
	);
}

/**
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true === $checked ) ? true : false );
}

/**
 * Sanitize select.
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string
 */
function aqualuxe_sanitize_select( $input, $setting = null ) {
	// Get list of choices from the control associated with the setting.
	if ( $setting ) {
		$choices = $setting->manager->get_control( $setting->id )->choices;
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}
	return $input;
}

/**
 * Sanitize float.
 *
 * @param float $input The input from the setting.
 * @return float
 */
function aqualuxe_sanitize_float( $input ) {
	return filter_var( $input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
}