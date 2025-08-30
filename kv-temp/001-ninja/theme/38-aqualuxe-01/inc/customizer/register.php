<?php
/**
 * Customizer Registration
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register( $wp_customize ) {
	// Add sanitization functions
	require_once AQUALUXE_INC_DIR . 'customizer/sanitize.php';
	
	// Add Theme Info Section
	$wp_customize->add_section(
		'aqualuxe_theme_info',
		array(
			'title'    => esc_html__( 'Theme Information', 'aqualuxe' ),
			'priority' => 1,
		)
	);
	
	$wp_customize->add_setting(
		'aqualuxe_theme_info',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'aqualuxe_theme_info',
			array(
				'label'       => esc_html__( 'AquaLuxe Theme', 'aqualuxe' ),
				'description' => sprintf(
					'%1$s <a href="%2$s" target="_blank">%3$s</a>',
					esc_html__( 'Thank you for using AquaLuxe! For theme documentation and support, please visit', 'aqualuxe' ),
					esc_url( 'https://example.com/aqualuxe-docs' ),
					esc_html__( 'AquaLuxe Documentation', 'aqualuxe' )
				),
				'section'     => 'aqualuxe_theme_info',
				'type'        => 'hidden',
			)
		)
	);
	
	// Add General Settings Section
	$wp_customize->add_section(
		'aqualuxe_general_settings',
		array(
			'title'    => esc_html__( 'General Settings', 'aqualuxe' ),
			'priority' => 10,
		)
	);
	
	// Layout Setting
	$wp_customize->add_setting(
		'aqualuxe_layout',
		array(
			'default'           => 'right-sidebar',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_layout',
		array(
			'label'       => esc_html__( 'Site Layout', 'aqualuxe' ),
			'description' => esc_html__( 'Choose the default layout for pages and posts.', 'aqualuxe' ),
			'section'     => 'aqualuxe_general_settings',
			'type'        => 'select',
			'choices'     => array(
				'right-sidebar' => esc_html__( 'Right Sidebar', 'aqualuxe' ),
				'left-sidebar'  => esc_html__( 'Left Sidebar', 'aqualuxe' ),
				'full-width'    => esc_html__( 'Full Width', 'aqualuxe' ),
			),
		)
	);
	
	// Container Width
	$wp_customize->add_setting(
		'aqualuxe_container_width',
		array(
			'default'           => '1200',
			'sanitize_callback' => 'absint',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_container_width',
		array(
			'label'       => esc_html__( 'Container Width (px)', 'aqualuxe' ),
			'description' => esc_html__( 'Set the maximum width of the content container.', 'aqualuxe' ),
			'section'     => 'aqualuxe_general_settings',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 960,
				'max'  => 1920,
				'step' => 10,
			),
		)
	);
	
	// Enable Dark Mode Toggle
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
			'label'       => esc_html__( 'Enable Dark Mode Toggle', 'aqualuxe' ),
			'description' => esc_html__( 'Allow users to switch between light and dark mode.', 'aqualuxe' ),
			'section'     => 'aqualuxe_general_settings',
			'type'        => 'checkbox',
		)
	);
	
	// Default Color Scheme
	$wp_customize->add_setting(
		'aqualuxe_color_scheme',
		array(
			'default'           => 'light',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_color_scheme',
		array(
			'label'       => esc_html__( 'Default Color Scheme', 'aqualuxe' ),
			'description' => esc_html__( 'Choose the default color scheme for your site.', 'aqualuxe' ),
			'section'     => 'aqualuxe_general_settings',
			'type'        => 'select',
			'choices'     => array(
				'light' => esc_html__( 'Light', 'aqualuxe' ),
				'dark'  => esc_html__( 'Dark', 'aqualuxe' ),
			),
		)
	);
	
	// Add Header Settings Section
	$wp_customize->add_section(
		'aqualuxe_header_settings',
		array(
			'title'    => esc_html__( 'Header Settings', 'aqualuxe' ),
			'priority' => 20,
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
			'label'       => esc_html__( 'Enable Sticky Header', 'aqualuxe' ),
			'description' => esc_html__( 'Keep the header visible when scrolling down.', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_settings',
			'type'        => 'checkbox',
		)
	);
	
	// Header Style
	$wp_customize->add_setting(
		'aqualuxe_header_style',
		array(
			'default'           => 'standard',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_header_style',
		array(
			'label'       => esc_html__( 'Header Style', 'aqualuxe' ),
			'description' => esc_html__( 'Choose the style for your site header.', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_settings',
			'type'        => 'select',
			'choices'     => array(
				'standard'   => esc_html__( 'Standard', 'aqualuxe' ),
				'centered'   => esc_html__( 'Centered Logo', 'aqualuxe' ),
				'transparent' => esc_html__( 'Transparent', 'aqualuxe' ),
			),
		)
	);
	
	// Show Search in Header
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
			'label'       => esc_html__( 'Show Search in Header', 'aqualuxe' ),
			'description' => esc_html__( 'Display a search icon in the header.', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_settings',
			'type'        => 'checkbox',
		)
	);
	
	// Show Cart in Header (if WooCommerce is active)
	if ( class_exists( 'WooCommerce' ) ) {
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
				'label'       => esc_html__( 'Show Cart in Header', 'aqualuxe' ),
				'description' => esc_html__( 'Display a shopping cart icon in the header.', 'aqualuxe' ),
				'section'     => 'aqualuxe_header_settings',
				'type'        => 'checkbox',
			)
		);
	}
	
	// Add Footer Settings Section
	$wp_customize->add_section(
		'aqualuxe_footer_settings',
		array(
			'title'    => esc_html__( 'Footer Settings', 'aqualuxe' ),
			'priority' => 30,
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
			'label'       => esc_html__( 'Footer Widget Columns', 'aqualuxe' ),
			'description' => esc_html__( 'Choose the number of widget columns in the footer.', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer_settings',
			'type'        => 'select',
			'choices'     => array(
				'1' => esc_html__( '1 Column', 'aqualuxe' ),
				'2' => esc_html__( '2 Columns', 'aqualuxe' ),
				'3' => esc_html__( '3 Columns', 'aqualuxe' ),
				'4' => esc_html__( '4 Columns', 'aqualuxe' ),
			),
		)
	);
	
	// Copyright Text
	$wp_customize->add_setting(
		'aqualuxe_copyright_text',
		array(
			'default'           => sprintf( esc_html__( '© %s AquaLuxe. All Rights Reserved.', 'aqualuxe' ), date( 'Y' ) ),
			'sanitize_callback' => 'wp_kses_post',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_copyright_text',
		array(
			'label'       => esc_html__( 'Copyright Text', 'aqualuxe' ),
			'description' => esc_html__( 'Enter your copyright text. Use {year} for the current year.', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer_settings',
			'type'        => 'textarea',
		)
	);
	
	// Add Typography Section
	$wp_customize->add_section(
		'aqualuxe_typography',
		array(
			'title'    => esc_html__( 'Typography', 'aqualuxe' ),
			'priority' => 40,
		)
	);
	
	// Heading Font
	$wp_customize->add_setting(
		'aqualuxe_heading_font',
		array(
			'default'           => 'Playfair Display',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_heading_font',
		array(
			'label'       => esc_html__( 'Heading Font', 'aqualuxe' ),
			'description' => esc_html__( 'Select the font for headings.', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography',
			'type'        => 'select',
			'choices'     => array(
				'Playfair Display' => 'Playfair Display',
				'Montserrat'       => 'Montserrat',
				'Roboto'           => 'Roboto',
				'Open Sans'        => 'Open Sans',
				'Lato'             => 'Lato',
				'Raleway'          => 'Raleway',
				'Poppins'          => 'Poppins',
			),
		)
	);
	
	// Body Font
	$wp_customize->add_setting(
		'aqualuxe_body_font',
		array(
			'default'           => 'Montserrat',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_body_font',
		array(
			'label'       => esc_html__( 'Body Font', 'aqualuxe' ),
			'description' => esc_html__( 'Select the font for body text.', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography',
			'type'        => 'select',
			'choices'     => array(
				'Montserrat'       => 'Montserrat',
				'Roboto'           => 'Roboto',
				'Open Sans'        => 'Open Sans',
				'Lato'             => 'Lato',
				'Raleway'          => 'Raleway',
				'Poppins'          => 'Poppins',
				'Playfair Display' => 'Playfair Display',
			),
		)
	);
	
	// Base Font Size
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
			'label'       => esc_html__( 'Base Font Size (px)', 'aqualuxe' ),
			'description' => esc_html__( 'Set the base font size for your site.', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 12,
				'max'  => 24,
				'step' => 1,
			),
		)
	);
	
	// Add Colors Section
	$wp_customize->get_section( 'colors' )->title = esc_html__( 'Color Settings', 'aqualuxe' );
	$wp_customize->get_section( 'colors' )->priority = 50;
	
	// Primary Color
	$wp_customize->add_setting(
		'aqualuxe_primary_color',
		array(
			'default'           => AQUALUXE_COLOR_PRIMARY,
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_primary_color',
			array(
				'label'       => esc_html__( 'Primary Color', 'aqualuxe' ),
				'description' => esc_html__( 'Set the primary color for your site.', 'aqualuxe' ),
				'section'     => 'colors',
			)
		)
	);
	
	// Secondary Color
	$wp_customize->add_setting(
		'aqualuxe_secondary_color',
		array(
			'default'           => AQUALUXE_COLOR_SECONDARY,
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_secondary_color',
			array(
				'label'       => esc_html__( 'Secondary Color', 'aqualuxe' ),
				'description' => esc_html__( 'Set the secondary color for your site.', 'aqualuxe' ),
				'section'     => 'colors',
			)
		)
	);
	
	// Accent Color
	$wp_customize->add_setting(
		'aqualuxe_accent_color',
		array(
			'default'           => AQUALUXE_COLOR_ACCENT,
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_accent_color',
			array(
				'label'       => esc_html__( 'Accent Color', 'aqualuxe' ),
				'description' => esc_html__( 'Set the accent color for your site.', 'aqualuxe' ),
				'section'     => 'colors',
			)
		)
	);
	
	// Text Color
	$wp_customize->add_setting(
		'aqualuxe_text_color',
		array(
			'default'           => AQUALUXE_COLOR_DARK,
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_text_color',
			array(
				'label'       => esc_html__( 'Text Color', 'aqualuxe' ),
				'description' => esc_html__( 'Set the main text color for your site.', 'aqualuxe' ),
				'section'     => 'colors',
			)
		)
	);
	
	// Background Color
	$wp_customize->add_setting(
		'aqualuxe_background_color',
		array(
			'default'           => AQUALUXE_COLOR_LIGHT,
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_background_color',
			array(
				'label'       => esc_html__( 'Background Color', 'aqualuxe' ),
				'description' => esc_html__( 'Set the background color for your site.', 'aqualuxe' ),
				'section'     => 'colors',
			)
		)
	);
	
	// Add Blog Settings Section
	$wp_customize->add_section(
		'aqualuxe_blog_settings',
		array(
			'title'    => esc_html__( 'Blog Settings', 'aqualuxe' ),
			'priority' => 60,
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
			'label'       => esc_html__( 'Blog Layout', 'aqualuxe' ),
			'description' => esc_html__( 'Choose the layout for your blog archive pages.', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'select',
			'choices'     => array(
				'grid'    => esc_html__( 'Grid', 'aqualuxe' ),
				'list'    => esc_html__( 'List', 'aqualuxe' ),
				'masonry' => esc_html__( 'Masonry', 'aqualuxe' ),
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
			'label'       => esc_html__( 'Posts Per Page', 'aqualuxe' ),
			'description' => esc_html__( 'Set the number of posts to display per page.', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 50,
				'step' => 1,
			),
		)
	);
	
	// Show Featured Image
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
			'label'       => esc_html__( 'Show Featured Image', 'aqualuxe' ),
			'description' => esc_html__( 'Display featured images on single posts.', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'checkbox',
		)
	);
	
	// Show Post Meta
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
			'label'       => esc_html__( 'Show Post Meta', 'aqualuxe' ),
			'description' => esc_html__( 'Display post meta information (author, date, categories).', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'checkbox',
		)
	);
	
	// Show Author Bio
	$wp_customize->add_setting(
		'aqualuxe_show_author_bio',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_show_author_bio',
		array(
			'label'       => esc_html__( 'Show Author Bio', 'aqualuxe' ),
			'description' => esc_html__( 'Display author biography on single posts.', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'checkbox',
		)
	);
	
	// Show Related Posts
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
			'label'       => esc_html__( 'Show Related Posts', 'aqualuxe' ),
			'description' => esc_html__( 'Display related posts on single posts.', 'aqualuxe' ),
			'section'     => 'aqualuxe_blog_settings',
			'type'        => 'checkbox',
		)
	);
	
	// Add WooCommerce Settings Section if WooCommerce is active
	if ( class_exists( 'WooCommerce' ) ) {
		$wp_customize->add_section(
			'aqualuxe_woocommerce_settings',
			array(
				'title'    => esc_html__( 'WooCommerce Settings', 'aqualuxe' ),
				'priority' => 70,
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
				'label'       => esc_html__( 'Products Per Page', 'aqualuxe' ),
				'description' => esc_html__( 'Set the number of products to display per page.', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_settings',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 1,
					'max'  => 50,
					'step' => 1,
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
				'label'       => esc_html__( 'Product Columns', 'aqualuxe' ),
				'description' => esc_html__( 'Set the number of columns for product archives.', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_settings',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 1,
					'max'  => 6,
					'step' => 1,
				),
			)
		);
		
		// Related Products Count
		$wp_customize->add_setting(
			'aqualuxe_related_products',
			array(
				'default'           => 4,
				'sanitize_callback' => 'absint',
			)
		);
		
		$wp_customize->add_control(
			'aqualuxe_related_products',
			array(
				'label'       => esc_html__( 'Related Products Count', 'aqualuxe' ),
				'description' => esc_html__( 'Set the number of related products to display.', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_settings',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 12,
					'step' => 1,
				),
			)
		);
		
		// Show Quick View
		$wp_customize->add_setting(
			'aqualuxe_show_quick_view',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);
		
		$wp_customize->add_control(
			'aqualuxe_show_quick_view',
			array(
				'label'       => esc_html__( 'Show Quick View', 'aqualuxe' ),
				'description' => esc_html__( 'Display quick view button on product archives.', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_settings',
				'type'        => 'checkbox',
			)
		);
		
		// Show Wishlist
		$wp_customize->add_setting(
			'aqualuxe_show_wishlist',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);
		
		$wp_customize->add_control(
			'aqualuxe_show_wishlist',
			array(
				'label'       => esc_html__( 'Show Wishlist', 'aqualuxe' ),
				'description' => esc_html__( 'Display wishlist button on product archives and single products.', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_settings',
				'type'        => 'checkbox',
			)
		);
	}
	
	// Add Social Media Section
	$wp_customize->add_section(
		'aqualuxe_social_media',
		array(
			'title'    => esc_html__( 'Social Media', 'aqualuxe' ),
			'priority' => 80,
		)
	);
	
	// Facebook URL
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
			'label'       => esc_html__( 'Facebook URL', 'aqualuxe' ),
			'description' => esc_html__( 'Enter your Facebook profile or page URL.', 'aqualuxe' ),
			'section'     => 'aqualuxe_social_media',
			'type'        => 'url',
		)
	);
	
	// Twitter URL
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
			'label'       => esc_html__( 'Twitter URL', 'aqualuxe' ),
			'description' => esc_html__( 'Enter your Twitter profile URL.', 'aqualuxe' ),
			'section'     => 'aqualuxe_social_media',
			'type'        => 'url',
		)
	);
	
	// Instagram URL
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
			'label'       => esc_html__( 'Instagram URL', 'aqualuxe' ),
			'description' => esc_html__( 'Enter your Instagram profile URL.', 'aqualuxe' ),
			'section'     => 'aqualuxe_social_media',
			'type'        => 'url',
		)
	);
	
	// YouTube URL
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
			'label'       => esc_html__( 'YouTube URL', 'aqualuxe' ),
			'description' => esc_html__( 'Enter your YouTube channel URL.', 'aqualuxe' ),
			'section'     => 'aqualuxe_social_media',
			'type'        => 'url',
		)
	);
	
	// LinkedIn URL
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
			'label'       => esc_html__( 'LinkedIn URL', 'aqualuxe' ),
			'description' => esc_html__( 'Enter your LinkedIn profile or company URL.', 'aqualuxe' ),
			'section'     => 'aqualuxe_social_media',
			'type'        => 'url',
		)
	);
	
	// Add Contact Information Section
	$wp_customize->add_section(
		'aqualuxe_contact_info',
		array(
			'title'    => esc_html__( 'Contact Information', 'aqualuxe' ),
			'priority' => 90,
		)
	);
	
	// Email Address
	$wp_customize->add_setting(
		'aqualuxe_contact_email',
		array(
			'default'           => AQUALUXE_CONTACT_EMAIL,
			'sanitize_callback' => 'sanitize_email',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_contact_email',
		array(
			'label'       => esc_html__( 'Email Address', 'aqualuxe' ),
			'description' => esc_html__( 'Enter your contact email address.', 'aqualuxe' ),
			'section'     => 'aqualuxe_contact_info',
			'type'        => 'email',
		)
	);
	
	// Phone Number
	$wp_customize->add_setting(
		'aqualuxe_contact_phone',
		array(
			'default'           => AQUALUXE_CONTACT_PHONE,
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_contact_phone',
		array(
			'label'       => esc_html__( 'Phone Number', 'aqualuxe' ),
			'description' => esc_html__( 'Enter your contact phone number.', 'aqualuxe' ),
			'section'     => 'aqualuxe_contact_info',
			'type'        => 'text',
		)
	);
	
	// Address
	$wp_customize->add_setting(
		'aqualuxe_contact_address',
		array(
			'default'           => AQUALUXE_CONTACT_ADDRESS,
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_contact_address',
		array(
			'label'       => esc_html__( 'Address', 'aqualuxe' ),
			'description' => esc_html__( 'Enter your physical address.', 'aqualuxe' ),
			'section'     => 'aqualuxe_contact_info',
			'type'        => 'textarea',
		)
	);
	
	// Google Maps API Key
	$wp_customize->add_setting(
		'aqualuxe_google_maps_api_key',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_google_maps_api_key',
		array(
			'label'       => esc_html__( 'Google Maps API Key', 'aqualuxe' ),
			'description' => esc_html__( 'Enter your Google Maps API key for the contact page map.', 'aqualuxe' ),
			'section'     => 'aqualuxe_contact_info',
			'type'        => 'text',
		)
	);
	
	// Add Performance Section
	$wp_customize->add_section(
		'aqualuxe_performance',
		array(
			'title'    => esc_html__( 'Performance', 'aqualuxe' ),
			'priority' => 100,
		)
	);
	
	// Disable Emojis
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
			'label'       => esc_html__( 'Disable WordPress Emojis', 'aqualuxe' ),
			'description' => esc_html__( 'Remove WordPress emoji scripts for better performance.', 'aqualuxe' ),
			'section'     => 'aqualuxe_performance',
			'type'        => 'checkbox',
		)
	);
	
	// Disable oEmbed
	$wp_customize->add_setting(
		'aqualuxe_disable_oembed',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_disable_oembed',
		array(
			'label'       => esc_html__( 'Disable WordPress oEmbed', 'aqualuxe' ),
			'description' => esc_html__( 'Remove WordPress oEmbed scripts for better performance.', 'aqualuxe' ),
			'section'     => 'aqualuxe_performance',
			'type'        => 'checkbox',
		)
	);
	
	// Lazy Load Images
	$wp_customize->add_setting(
		'aqualuxe_lazy_load_images',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_lazy_load_images',
		array(
			'label'       => esc_html__( 'Lazy Load Images', 'aqualuxe' ),
			'description' => esc_html__( 'Enable lazy loading for images to improve page load speed.', 'aqualuxe' ),
			'section'     => 'aqualuxe_performance',
			'type'        => 'checkbox',
		)
	);
	
	// Preload Critical CSS
	$wp_customize->add_setting(
		'aqualuxe_preload_critical_css',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_preload_critical_css',
		array(
			'label'       => esc_html__( 'Preload Critical CSS', 'aqualuxe' ),
			'description' => esc_html__( 'Preload critical CSS for faster rendering.', 'aqualuxe' ),
			'section'     => 'aqualuxe_performance',
			'type'        => 'checkbox',
		)
	);
	
	// Defer Non-Critical JavaScript
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
			'label'       => esc_html__( 'Defer Non-Critical JavaScript', 'aqualuxe' ),
			'description' => esc_html__( 'Defer non-critical JavaScript for faster page load.', 'aqualuxe' ),
			'section'     => 'aqualuxe_performance',
			'type'        => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'aqualuxe_customize_register' );

/**
 * Render the site title for the selective refresh partial
 *
 * @return void
 */
function aqualuxe_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial
 *
 * @return void
 */
function aqualuxe_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously
 */
function aqualuxe_customize_preview_js() {
	wp_enqueue_script(
		'aqualuxe-customizer',
		aqualuxe_asset_path( 'js/customizer.js' ),
		array( 'customize-preview' ),
		AQUALUXE_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'aqualuxe_customize_preview_js' );

/**
 * Generate CSS from customizer settings
 *
 * @return string Generated CSS.
 */
function aqualuxe_generate_customizer_css() {
	$css = '';
	
	// Primary Color
	$primary_color = get_theme_mod( 'aqualuxe_primary_color', AQUALUXE_COLOR_PRIMARY );
	if ( $primary_color !== AQUALUXE_COLOR_PRIMARY ) {
		$css .= ':root { --color-primary: ' . esc_attr( $primary_color ) . '; }';
	}
	
	// Secondary Color
	$secondary_color = get_theme_mod( 'aqualuxe_secondary_color', AQUALUXE_COLOR_SECONDARY );
	if ( $secondary_color !== AQUALUXE_COLOR_SECONDARY ) {
		$css .= ':root { --color-secondary: ' . esc_attr( $secondary_color ) . '; }';
	}
	
	// Accent Color
	$accent_color = get_theme_mod( 'aqualuxe_accent_color', AQUALUXE_COLOR_ACCENT );
	if ( $accent_color !== AQUALUXE_COLOR_ACCENT ) {
		$css .= ':root { --color-accent: ' . esc_attr( $accent_color ) . '; }';
	}
	
	// Text Color
	$text_color = get_theme_mod( 'aqualuxe_text_color', AQUALUXE_COLOR_DARK );
	if ( $text_color !== AQUALUXE_COLOR_DARK ) {
		$css .= ':root { --color-text: ' . esc_attr( $text_color ) . '; }';
	}
	
	// Background Color
	$background_color = get_theme_mod( 'aqualuxe_background_color', AQUALUXE_COLOR_LIGHT );
	if ( $background_color !== AQUALUXE_COLOR_LIGHT ) {
		$css .= ':root { --color-background: ' . esc_attr( $background_color ) . '; }';
	}
	
	// Container Width
	$container_width = get_theme_mod( 'aqualuxe_container_width', '1200' );
	if ( $container_width !== '1200' ) {
		$css .= ':root { --container-width: ' . esc_attr( $container_width ) . 'px; }';
	}
	
	// Base Font Size
	$base_font_size = get_theme_mod( 'aqualuxe_base_font_size', '16' );
	if ( $base_font_size !== '16' ) {
		$css .= ':root { --font-size-base: ' . esc_attr( $base_font_size ) . 'px; }';
	}
	
	// Heading Font
	$heading_font = get_theme_mod( 'aqualuxe_heading_font', 'Playfair Display' );
	if ( $heading_font !== 'Playfair Display' ) {
		$css .= ':root { --font-heading: "' . esc_attr( $heading_font ) . '", serif; }';
	}
	
	// Body Font
	$body_font = get_theme_mod( 'aqualuxe_body_font', 'Montserrat' );
	if ( $body_font !== 'Montserrat' ) {
		$css .= ':root { --font-body: "' . esc_attr( $body_font ) . '", sans-serif; }';
	}
	
	return $css;
}

/**
 * Output generated CSS to wp_head
 */
function aqualuxe_customizer_css() {
	$css = aqualuxe_generate_customizer_css();
	
	if ( ! empty( $css ) ) {
		echo '<style type="text/css">' . wp_strip_all_tags( $css ) . '</style>';
	}
}
add_action( 'wp_head', 'aqualuxe_customizer_css' );