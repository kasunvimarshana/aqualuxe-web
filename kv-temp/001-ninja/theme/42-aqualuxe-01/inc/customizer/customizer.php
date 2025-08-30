<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 */

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

	// Add Theme Options Panel
	$wp_customize->add_panel(
		'aqualuxe_theme_options',
		array(
			'title'       => esc_html__( 'AquaLuxe Theme Options', 'aqualuxe' ),
			'description' => esc_html__( 'Configure your theme settings', 'aqualuxe' ),
			'priority'    => 10,
		)
	);

	// Add Header & Navigation Section
	$wp_customize->add_section(
		'aqualuxe_header_options',
		array(
			'title'       => esc_html__( 'Header & Navigation', 'aqualuxe' ),
			'description' => esc_html__( 'Customize the header and navigation settings', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 10,
		)
	);

	// Sticky Header
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
			'label'       => esc_html__( 'Enable Sticky Header', 'aqualuxe' ),
			'description' => esc_html__( 'Keep the header visible when scrolling down', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_options',
			'type'        => 'checkbox',
		)
	);

	// Top Bar
	$wp_customize->add_setting(
		'aqualuxe_show_top_bar',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_show_top_bar',
		array(
			'label'       => esc_html__( 'Show Top Bar', 'aqualuxe' ),
			'description' => esc_html__( 'Display the top bar with contact information and social links', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_options',
			'type'        => 'checkbox',
		)
	);

	// Phone Number
	$wp_customize->add_setting(
		'aqualuxe_phone_number',
		array(
			'default'           => '+1 (555) 123-4567',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_phone_number',
		array(
			'label'       => esc_html__( 'Phone Number', 'aqualuxe' ),
			'description' => esc_html__( 'Enter your business phone number', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_options',
			'type'        => 'text',
		)
	);

	// Email Address
	$wp_customize->add_setting(
		'aqualuxe_email',
		array(
			'default'           => 'info@aqualuxe.com',
			'sanitize_callback' => 'sanitize_email',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_email',
		array(
			'label'       => esc_html__( 'Email Address', 'aqualuxe' ),
			'description' => esc_html__( 'Enter your business email address', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_options',
			'type'        => 'email',
		)
	);

	// Address
	$wp_customize->add_setting(
		'aqualuxe_address',
		array(
			'default'           => '123 Water Street, Oceanview, CA 90210',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_address',
		array(
			'label'       => esc_html__( 'Address', 'aqualuxe' ),
			'description' => esc_html__( 'Enter your business address', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_options',
			'type'        => 'text',
		)
	);

	// Header Layout
	$wp_customize->add_setting(
		'aqualuxe_header_layout',
		array(
			'default'           => 'default',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_header_layout',
		array(
			'label'       => esc_html__( 'Header Layout', 'aqualuxe' ),
			'description' => esc_html__( 'Choose the header layout style', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_options',
			'type'        => 'select',
			'choices'     => array(
				'default'    => esc_html__( 'Default', 'aqualuxe' ),
				'centered'   => esc_html__( 'Centered', 'aqualuxe' ),
				'split'      => esc_html__( 'Split Menu', 'aqualuxe' ),
				'minimal'    => esc_html__( 'Minimal', 'aqualuxe' ),
				'transparent' => esc_html__( 'Transparent', 'aqualuxe' ),
			),
		)
	);

	// Add Footer Section
	$wp_customize->add_section(
		'aqualuxe_footer_options',
		array(
			'title'       => esc_html__( 'Footer', 'aqualuxe' ),
			'description' => esc_html__( 'Customize the footer settings', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 20,
		)
	);

	// Footer About Text
	$wp_customize->add_setting(
		'aqualuxe_footer_about',
		array(
			'default'           => 'AquaLuxe offers premium water-themed products and services with elegance and sophistication. Our commitment to quality and sustainability sets us apart in the industry.',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_footer_about',
		array(
			'label'       => esc_html__( 'Footer About Text', 'aqualuxe' ),
			'description' => esc_html__( 'Enter a short description about your business for the footer', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer_options',
			'type'        => 'textarea',
		)
	);

	// Footer Layout
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
			'label'       => esc_html__( 'Footer Layout', 'aqualuxe' ),
			'description' => esc_html__( 'Choose the footer widget layout', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer_options',
			'type'        => 'select',
			'choices'     => array(
				'4-columns' => esc_html__( '4 Columns', 'aqualuxe' ),
				'3-columns' => esc_html__( '3 Columns', 'aqualuxe' ),
				'2-columns' => esc_html__( '2 Columns', 'aqualuxe' ),
				'1-column'  => esc_html__( '1 Column', 'aqualuxe' ),
			),
		)
	);

	// Copyright Text
	$wp_customize->add_setting(
		'aqualuxe_copyright_text',
		array(
			'default'           => '&copy; ' . date( 'Y' ) . ' AquaLuxe. All Rights Reserved.',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_copyright_text',
		array(
			'label'       => esc_html__( 'Copyright Text', 'aqualuxe' ),
			'description' => esc_html__( 'Enter your copyright text for the footer', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer_options',
			'type'        => 'textarea',
		)
	);

	// Show Payment Methods
	$wp_customize->add_setting(
		'aqualuxe_show_payment_methods',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_show_payment_methods',
		array(
			'label'       => esc_html__( 'Show Payment Methods', 'aqualuxe' ),
			'description' => esc_html__( 'Display payment method icons in the footer', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer_options',
			'type'        => 'checkbox',
		)
	);

	// Back to Top Button
	$wp_customize->add_setting(
		'aqualuxe_back_to_top',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_back_to_top',
		array(
			'label'       => esc_html__( 'Show Back to Top Button', 'aqualuxe' ),
			'description' => esc_html__( 'Display a button to scroll back to the top of the page', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer_options',
			'type'        => 'checkbox',
		)
	);

	// Add Colors & Typography Section
	$wp_customize->add_section(
		'aqualuxe_colors_typography',
		array(
			'title'       => esc_html__( 'Colors & Typography', 'aqualuxe' ),
			'description' => esc_html__( 'Customize the colors and typography settings', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 30,
		)
	);

	// Primary Color
	$wp_customize->add_setting(
		'aqualuxe_primary_color',
		array(
			'default'           => '#0891b2',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_primary_color',
			array(
				'label'       => esc_html__( 'Primary Color', 'aqualuxe' ),
				'description' => esc_html__( 'Choose the primary color for buttons, links, and accents', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_typography',
			)
		)
	);

	// Secondary Color
	$wp_customize->add_setting(
		'aqualuxe_secondary_color',
		array(
			'default'           => '#0e7490',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_secondary_color',
			array(
				'label'       => esc_html__( 'Secondary Color', 'aqualuxe' ),
				'description' => esc_html__( 'Choose the secondary color for buttons, links, and accents', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_typography',
			)
		)
	);

	// Accent Color
	$wp_customize->add_setting(
		'aqualuxe_accent_color',
		array(
			'default'           => '#06b6d4',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_accent_color',
			array(
				'label'       => esc_html__( 'Accent Color', 'aqualuxe' ),
				'description' => esc_html__( 'Choose the accent color for highlights and special elements', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_typography',
			)
		)
	);

	// Heading Font
	$wp_customize->add_setting(
		'aqualuxe_heading_font',
		array(
			'default'           => 'Montserrat',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_heading_font',
		array(
			'label'       => esc_html__( 'Heading Font', 'aqualuxe' ),
			'description' => esc_html__( 'Choose the font for headings', 'aqualuxe' ),
			'section'     => 'aqualuxe_colors_typography',
			'type'        => 'select',
			'choices'     => array(
				'Montserrat'    => esc_html__( 'Montserrat', 'aqualuxe' ),
				'Roboto'        => esc_html__( 'Roboto', 'aqualuxe' ),
				'Open Sans'     => esc_html__( 'Open Sans', 'aqualuxe' ),
				'Lato'          => esc_html__( 'Lato', 'aqualuxe' ),
				'Poppins'       => esc_html__( 'Poppins', 'aqualuxe' ),
				'Playfair Display' => esc_html__( 'Playfair Display', 'aqualuxe' ),
				'Merriweather' => esc_html__( 'Merriweather', 'aqualuxe' ),
				'Raleway'       => esc_html__( 'Raleway', 'aqualuxe' ),
				'Nunito'        => esc_html__( 'Nunito', 'aqualuxe' ),
				'Quicksand'     => esc_html__( 'Quicksand', 'aqualuxe' ),
			),
		)
	);

	// Body Font
	$wp_customize->add_setting(
		'aqualuxe_body_font',
		array(
			'default'           => 'Open Sans',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_body_font',
		array(
			'label'       => esc_html__( 'Body Font', 'aqualuxe' ),
			'description' => esc_html__( 'Choose the font for body text', 'aqualuxe' ),
			'section'     => 'aqualuxe_colors_typography',
			'type'        => 'select',
			'choices'     => array(
				'Open Sans'     => esc_html__( 'Open Sans', 'aqualuxe' ),
				'Roboto'        => esc_html__( 'Roboto', 'aqualuxe' ),
				'Lato'          => esc_html__( 'Lato', 'aqualuxe' ),
				'Nunito'        => esc_html__( 'Nunito', 'aqualuxe' ),
				'Montserrat'    => esc_html__( 'Montserrat', 'aqualuxe' ),
				'Poppins'       => esc_html__( 'Poppins', 'aqualuxe' ),
				'Raleway'       => esc_html__( 'Raleway', 'aqualuxe' ),
				'Source Sans Pro' => esc_html__( 'Source Sans Pro', 'aqualuxe' ),
				'Work Sans'     => esc_html__( 'Work Sans', 'aqualuxe' ),
				'Quicksand'     => esc_html__( 'Quicksand', 'aqualuxe' ),
			),
		)
	);

	// Add Layout & Content Section
	$wp_customize->add_section(
		'aqualuxe_layout_content',
		array(
			'title'       => esc_html__( 'Layout & Content', 'aqualuxe' ),
			'description' => esc_html__( 'Customize the layout and content settings', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 40,
		)
	);

	// Container Width
	$wp_customize->add_setting(
		'aqualuxe_container_width',
		array(
			'default'           => '1200',
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_container_width',
		array(
			'label'       => esc_html__( 'Container Width (px)', 'aqualuxe' ),
			'description' => esc_html__( 'Set the maximum width of the content container', 'aqualuxe' ),
			'section'     => 'aqualuxe_layout_content',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 960,
				'max'  => 1600,
				'step' => 10,
			),
		)
	);

	// Sidebar Position
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
			'label'       => esc_html__( 'Sidebar Position', 'aqualuxe' ),
			'description' => esc_html__( 'Choose the position of the sidebar', 'aqualuxe' ),
			'section'     => 'aqualuxe_layout_content',
			'type'        => 'select',
			'choices'     => array(
				'right' => esc_html__( 'Right', 'aqualuxe' ),
				'left'  => esc_html__( 'Left', 'aqualuxe' ),
				'none'  => esc_html__( 'No Sidebar', 'aqualuxe' ),
			),
		)
	);

	// Blog Layout
	$wp_customize->add_setting(
		'aqualuxe_blog_layout',
		array(
			'default'           => 'standard',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_blog_layout',
		array(
			'label'       => esc_html__( 'Blog Layout', 'aqualuxe' ),
			'description' => esc_html__( 'Choose the layout for blog listings', 'aqualuxe' ),
			'section'     => 'aqualuxe_layout_content',
			'type'        => 'select',
			'choices'     => array(
				'standard' => esc_html__( 'Standard', 'aqualuxe' ),
				'grid'     => esc_html__( 'Grid', 'aqualuxe' ),
				'masonry'  => esc_html__( 'Masonry', 'aqualuxe' ),
				'list'     => esc_html__( 'List', 'aqualuxe' ),
			),
		)
	);

	// Posts Per Page
	$wp_customize->add_setting(
		'aqualuxe_posts_per_page',
		array(
			'default'           => get_option( 'posts_per_page' ),
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_posts_per_page',
		array(
			'label'       => esc_html__( 'Posts Per Page', 'aqualuxe' ),
			'description' => esc_html__( 'Set the number of posts to display per page', 'aqualuxe' ),
			'section'     => 'aqualuxe_layout_content',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 50,
				'step' => 1,
			),
		)
	);

	// Excerpt Length
	$wp_customize->add_setting(
		'aqualuxe_excerpt_length',
		array(
			'default'           => '20',
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_excerpt_length',
		array(
			'label'       => esc_html__( 'Excerpt Length', 'aqualuxe' ),
			'description' => esc_html__( 'Set the number of words in post excerpts', 'aqualuxe' ),
			'section'     => 'aqualuxe_layout_content',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 10,
				'max'  => 100,
				'step' => 5,
			),
		)
	);

	// Show Featured Image on Single Posts
	$wp_customize->add_setting(
		'aqualuxe_show_single_featured_image',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_show_single_featured_image',
		array(
			'label'       => esc_html__( 'Show Featured Image on Single Posts', 'aqualuxe' ),
			'description' => esc_html__( 'Display the featured image on single post pages', 'aqualuxe' ),
			'section'     => 'aqualuxe_layout_content',
			'type'        => 'checkbox',
		)
	);

	// Hide Page Featured Image
	$wp_customize->add_setting(
		'aqualuxe_hide_page_featured_image',
		array(
			'default'           => false,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_hide_page_featured_image',
		array(
			'label'       => esc_html__( 'Hide Featured Image on Pages', 'aqualuxe' ),
			'description' => esc_html__( 'Hide the featured image on static pages', 'aqualuxe' ),
			'section'     => 'aqualuxe_layout_content',
			'type'        => 'checkbox',
		)
	);

	// Add Social Media Section
	$wp_customize->add_section(
		'aqualuxe_social_media',
		array(
			'title'       => esc_html__( 'Social Media', 'aqualuxe' ),
			'description' => esc_html__( 'Add your social media profile links', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 50,
		)
	);

	// Facebook
	$wp_customize->add_setting(
		'aqualuxe_facebook',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_facebook',
		array(
			'label'       => esc_html__( 'Facebook', 'aqualuxe' ),
			'description' => esc_html__( 'Enter your Facebook profile or page URL', 'aqualuxe' ),
			'section'     => 'aqualuxe_social_media',
			'type'        => 'url',
		)
	);

	// Twitter
	$wp_customize->add_setting(
		'aqualuxe_twitter',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_twitter',
		array(
			'label'       => esc_html__( 'Twitter', 'aqualuxe' ),
			'description' => esc_html__( 'Enter your Twitter profile URL', 'aqualuxe' ),
			'section'     => 'aqualuxe_social_media',
			'type'        => 'url',
		)
	);

	// Instagram
	$wp_customize->add_setting(
		'aqualuxe_instagram',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_instagram',
		array(
			'label'       => esc_html__( 'Instagram', 'aqualuxe' ),
			'description' => esc_html__( 'Enter your Instagram profile URL', 'aqualuxe' ),
			'section'     => 'aqualuxe_social_media',
			'type'        => 'url',
		)
	);

	// Pinterest
	$wp_customize->add_setting(
		'aqualuxe_pinterest',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_pinterest',
		array(
			'label'       => esc_html__( 'Pinterest', 'aqualuxe' ),
			'description' => esc_html__( 'Enter your Pinterest profile URL', 'aqualuxe' ),
			'section'     => 'aqualuxe_social_media',
			'type'        => 'url',
		)
	);

	// YouTube
	$wp_customize->add_setting(
		'aqualuxe_youtube',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_youtube',
		array(
			'label'       => esc_html__( 'YouTube', 'aqualuxe' ),
			'description' => esc_html__( 'Enter your YouTube channel URL', 'aqualuxe' ),
			'section'     => 'aqualuxe_social_media',
			'type'        => 'url',
		)
	);

	// LinkedIn
	$wp_customize->add_setting(
		'aqualuxe_linkedin',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_linkedin',
		array(
			'label'       => esc_html__( 'LinkedIn', 'aqualuxe' ),
			'description' => esc_html__( 'Enter your LinkedIn profile or company URL', 'aqualuxe' ),
			'section'     => 'aqualuxe_social_media',
			'type'        => 'url',
		)
	);

	// Add Performance & SEO Section
	$wp_customize->add_section(
		'aqualuxe_performance_seo',
		array(
			'title'       => esc_html__( 'Performance & SEO', 'aqualuxe' ),
			'description' => esc_html__( 'Optimize your site performance and SEO settings', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 60,
		)
	);

	// Lazy Loading
	$wp_customize->add_setting(
		'aqualuxe_lazy_loading',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_lazy_loading',
		array(
			'label'       => esc_html__( 'Enable Lazy Loading', 'aqualuxe' ),
			'description' => esc_html__( 'Load images only when they enter the viewport', 'aqualuxe' ),
			'section'     => 'aqualuxe_performance_seo',
			'type'        => 'checkbox',
		)
	);

	// Minify CSS & JS
	$wp_customize->add_setting(
		'aqualuxe_minify_assets',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_minify_assets',
		array(
			'label'       => esc_html__( 'Minify CSS & JS', 'aqualuxe' ),
			'description' => esc_html__( 'Minify CSS and JavaScript files for faster loading', 'aqualuxe' ),
			'section'     => 'aqualuxe_performance_seo',
			'type'        => 'checkbox',
		)
	);

	// Preload Critical CSS
	$wp_customize->add_setting(
		'aqualuxe_preload_critical_css',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_preload_critical_css',
		array(
			'label'       => esc_html__( 'Preload Critical CSS', 'aqualuxe' ),
			'description' => esc_html__( 'Preload critical CSS for faster rendering', 'aqualuxe' ),
			'section'     => 'aqualuxe_performance_seo',
			'type'        => 'checkbox',
		)
	);

	// Enable Schema Markup
	$wp_customize->add_setting(
		'aqualuxe_schema_markup',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_schema_markup',
		array(
			'label'       => esc_html__( 'Enable Schema Markup', 'aqualuxe' ),
			'description' => esc_html__( 'Add schema.org structured data for better SEO', 'aqualuxe' ),
			'section'     => 'aqualuxe_performance_seo',
			'type'        => 'checkbox',
		)
	);

	// Open Graph Meta Tags
	$wp_customize->add_setting(
		'aqualuxe_open_graph',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_open_graph',
		array(
			'label'       => esc_html__( 'Open Graph Meta Tags', 'aqualuxe' ),
			'description' => esc_html__( 'Add Open Graph meta tags for better social sharing', 'aqualuxe' ),
			'section'     => 'aqualuxe_performance_seo',
			'type'        => 'checkbox',
		)
	);

	// Twitter Cards
	$wp_customize->add_setting(
		'aqualuxe_twitter_cards',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_twitter_cards',
		array(
			'label'       => esc_html__( 'Twitter Cards', 'aqualuxe' ),
			'description' => esc_html__( 'Add Twitter Card meta tags for better Twitter sharing', 'aqualuxe' ),
			'section'     => 'aqualuxe_performance_seo',
			'type'        => 'checkbox',
		)
	);

	// Add Advanced Section
	$wp_customize->add_section(
		'aqualuxe_advanced',
		array(
			'title'       => esc_html__( 'Advanced', 'aqualuxe' ),
			'description' => esc_html__( 'Advanced theme settings', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 70,
		)
	);

	// Custom CSS
	$wp_customize->add_setting(
		'aqualuxe_custom_css',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_strip_all_tags',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_custom_css',
		array(
			'label'       => esc_html__( 'Custom CSS', 'aqualuxe' ),
			'description' => esc_html__( 'Add your custom CSS code here', 'aqualuxe' ),
			'section'     => 'aqualuxe_advanced',
			'type'        => 'textarea',
		)
	);

	// Custom JavaScript
	$wp_customize->add_setting(
		'aqualuxe_custom_js',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_strip_all_tags',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_custom_js',
		array(
			'label'       => esc_html__( 'Custom JavaScript', 'aqualuxe' ),
			'description' => esc_html__( 'Add your custom JavaScript code here', 'aqualuxe' ),
			'section'     => 'aqualuxe_advanced',
			'type'        => 'textarea',
		)
	);

	// Google Analytics
	$wp_customize->add_setting(
		'aqualuxe_google_analytics',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_strip_all_tags',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_google_analytics',
		array(
			'label'       => esc_html__( 'Google Analytics', 'aqualuxe' ),
			'description' => esc_html__( 'Add your Google Analytics tracking code here (without the script tags)', 'aqualuxe' ),
			'section'     => 'aqualuxe_advanced',
			'type'        => 'textarea',
		)
	);

	// Facebook Pixel
	$wp_customize->add_setting(
		'aqualuxe_facebook_pixel',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_strip_all_tags',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_facebook_pixel',
		array(
			'label'       => esc_html__( 'Facebook Pixel', 'aqualuxe' ),
			'description' => esc_html__( 'Add your Facebook Pixel code here (without the script tags)', 'aqualuxe' ),
			'section'     => 'aqualuxe_advanced',
			'type'        => 'textarea',
		)
	);

	// Maintenance Mode
	$wp_customize->add_setting(
		'aqualuxe_maintenance_mode',
		array(
			'default'           => false,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_maintenance_mode',
		array(
			'label'       => esc_html__( 'Maintenance Mode', 'aqualuxe' ),
			'description' => esc_html__( 'Enable maintenance mode for visitors (admins can still view the site)', 'aqualuxe' ),
			'section'     => 'aqualuxe_advanced',
			'type'        => 'checkbox',
		)
	);

	// Maintenance Message
	$wp_customize->add_setting(
		'aqualuxe_maintenance_message',
		array(
			'default'           => esc_html__( 'We are currently performing scheduled maintenance. Please check back soon.', 'aqualuxe' ),
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_maintenance_message',
		array(
			'label'       => esc_html__( 'Maintenance Message', 'aqualuxe' ),
			'description' => esc_html__( 'Message to display during maintenance mode', 'aqualuxe' ),
			'section'     => 'aqualuxe_advanced',
			'type'        => 'textarea',
		)
	);

	// Add WooCommerce Section
	if ( class_exists( 'WooCommerce' ) ) {
		$wp_customize->add_section(
			'aqualuxe_woocommerce',
			array(
				'title'       => esc_html__( 'WooCommerce', 'aqualuxe' ),
				'description' => esc_html__( 'WooCommerce specific settings', 'aqualuxe' ),
				'panel'       => 'aqualuxe_theme_options',
				'priority'    => 80,
			)
		);

		// Products Per Page
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
				'label'       => esc_html__( 'Products Per Page', 'aqualuxe' ),
				'description' => esc_html__( 'Number of products to display per page', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 1,
					'max'  => 100,
					'step' => 1,
				),
			)
		);

		// Product Columns
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
				'label'       => esc_html__( 'Product Columns', 'aqualuxe' ),
				'description' => esc_html__( 'Number of columns in product grid', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce',
				'type'        => 'select',
				'choices'     => array(
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
			)
		);

		// Related Products
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
				'label'       => esc_html__( 'Show Related Products', 'aqualuxe' ),
				'description' => esc_html__( 'Display related products on single product pages', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce',
				'type'        => 'checkbox',
			)
		);

		// Product Sidebar
		$wp_customize->add_setting(
			'aqualuxe_product_sidebar',
			array(
				'default'           => 'none',
				'sanitize_callback' => 'aqualuxe_sanitize_select',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_product_sidebar',
			array(
				'label'       => esc_html__( 'Product Sidebar', 'aqualuxe' ),
				'description' => esc_html__( 'Choose sidebar position for single product pages', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce',
				'type'        => 'select',
				'choices'     => array(
					'right' => esc_html__( 'Right', 'aqualuxe' ),
					'left'  => esc_html__( 'Left', 'aqualuxe' ),
					'none'  => esc_html__( 'No Sidebar', 'aqualuxe' ),
				),
			)
		);

		// Shop Sidebar
		$wp_customize->add_setting(
			'aqualuxe_shop_sidebar',
			array(
				'default'           => 'right',
				'sanitize_callback' => 'aqualuxe_sanitize_select',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_shop_sidebar',
			array(
				'label'       => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
				'description' => esc_html__( 'Choose sidebar position for shop pages', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce',
				'type'        => 'select',
				'choices'     => array(
					'right' => esc_html__( 'Right', 'aqualuxe' ),
					'left'  => esc_html__( 'Left', 'aqualuxe' ),
					'none'  => esc_html__( 'No Sidebar', 'aqualuxe' ),
				),
			)
		);

		// Quick View
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
				'label'       => esc_html__( 'Enable Quick View', 'aqualuxe' ),
				'description' => esc_html__( 'Allow customers to view product details in a modal', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce',
				'type'        => 'checkbox',
			)
		);

		// AJAX Add to Cart
		$wp_customize->add_setting(
			'aqualuxe_ajax_add_to_cart',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_ajax_add_to_cart',
			array(
				'label'       => esc_html__( 'AJAX Add to Cart', 'aqualuxe' ),
				'description' => esc_html__( 'Enable AJAX add to cart functionality', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce',
				'type'        => 'checkbox',
			)
		);

		// Sticky Add to Cart
		$wp_customize->add_setting(
			'aqualuxe_sticky_add_to_cart',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_sticky_add_to_cart',
			array(
				'label'       => esc_html__( 'Sticky Add to Cart', 'aqualuxe' ),
				'description' => esc_html__( 'Show sticky add to cart bar when scrolling down product pages', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce',
				'type'        => 'checkbox',
			)
		);

		// Sale Badge Text
		$wp_customize->add_setting(
			'aqualuxe_sale_badge_text',
			array(
				'default'           => esc_html__( 'Sale!', 'aqualuxe' ),
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_sale_badge_text',
			array(
				'label'       => esc_html__( 'Sale Badge Text', 'aqualuxe' ),
				'description' => esc_html__( 'Text to display on sale badges', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce',
				'type'        => 'text',
			)
		);

		// Sale Badge Style
		$wp_customize->add_setting(
			'aqualuxe_sale_badge_style',
			array(
				'default'           => 'circle',
				'sanitize_callback' => 'aqualuxe_sanitize_select',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'aqualuxe_sale_badge_style',
			array(
				'label'       => esc_html__( 'Sale Badge Style', 'aqualuxe' ),
				'description' => esc_html__( 'Choose the style for sale badges', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce',
				'type'        => 'select',
				'choices'     => array(
					'circle'    => esc_html__( 'Circle', 'aqualuxe' ),
					'square'    => esc_html__( 'Square', 'aqualuxe' ),
					'rectangle' => esc_html__( 'Rectangle', 'aqualuxe' ),
					'ribbon'    => esc_html__( 'Ribbon', 'aqualuxe' ),
					'tag'       => esc_html__( 'Tag', 'aqualuxe' ),
				),
			)
		);
	}
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
 * Sanitize checkbox values
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

/**
 * Sanitize select values
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_select( $input, $setting ) {
	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;

	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Generate inline CSS for customizer options
 */
function aqualuxe_customizer_css() {
	$primary_color = get_theme_mod( 'aqualuxe_primary_color', '#0891b2' );
	$secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#0e7490' );
	$accent_color = get_theme_mod( 'aqualuxe_accent_color', '#06b6d4' );
	$heading_font = get_theme_mod( 'aqualuxe_heading_font', 'Montserrat' );
	$body_font = get_theme_mod( 'aqualuxe_body_font', 'Open Sans' );
	$container_width = get_theme_mod( 'aqualuxe_container_width', '1200' );
	$custom_css = get_theme_mod( 'aqualuxe_custom_css', '' );

	$css = "
		:root {
			--primary-color: {$primary_color};
			--secondary-color: {$secondary_color};
			--accent-color: {$accent_color};
			--heading-font: '{$heading_font}', sans-serif;
			--body-font: '{$body_font}', sans-serif;
			--container-width: {$container_width}px;
		}

		h1, h2, h3, h4, h5, h6 {
			font-family: var(--heading-font);
		}

		body {
			font-family: var(--body-font);
		}

		.container {
			max-width: var(--container-width);
		}

		a {
			color: var(--primary-color);
		}

		a:hover {
			color: var(--secondary-color);
		}

		.bg-primary {
			background-color: var(--primary-color) !important;
		}

		.bg-secondary {
			background-color: var(--secondary-color) !important;
		}

		.bg-accent {
			background-color: var(--accent-color) !important;
		}

		.text-primary {
			color: var(--primary-color) !important;
		}

		.text-secondary {
			color: var(--secondary-color) !important;
		}

		.text-accent {
			color: var(--accent-color) !important;
		}

		.border-primary {
			border-color: var(--primary-color) !important;
		}

		.border-secondary {
			border-color: var(--secondary-color) !important;
		}

		.border-accent {
			border-color: var(--accent-color) !important;
		}

		.btn-primary {
			background-color: var(--primary-color);
			border-color: var(--primary-color);
		}

		.btn-primary:hover {
			background-color: var(--secondary-color);
			border-color: var(--secondary-color);
		}

		.btn-secondary {
			background-color: var(--secondary-color);
			border-color: var(--secondary-color);
		}

		.btn-secondary:hover {
			background-color: var(--primary-color);
			border-color: var(--primary-color);
		}

		.btn-accent {
			background-color: var(--accent-color);
			border-color: var(--accent-color);
		}

		.btn-accent:hover {
			background-color: var(--primary-color);
			border-color: var(--primary-color);
		}

		{$custom_css}
	";

	wp_add_inline_style( 'aqualuxe-style', $css );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_customizer_css' );

/**
 * Load Google Fonts based on customizer settings
 */
function aqualuxe_google_fonts() {
	$heading_font = get_theme_mod( 'aqualuxe_heading_font', 'Montserrat' );
	$body_font = get_theme_mod( 'aqualuxe_body_font', 'Open Sans' );

	$fonts = array();

	if ( 'Montserrat' !== $heading_font ) {
		$fonts[] = $heading_font . ':400,500,600,700';
	}

	if ( 'Open Sans' !== $body_font && $body_font !== $heading_font ) {
		$fonts[] = $body_font . ':400,400i,700,700i';
	}

	if ( ! empty( $fonts ) ) {
		$fonts_url = add_query_arg(
			array(
				'family' => implode( '|', $fonts ),
				'display' => 'swap',
			),
			'https://fonts.googleapis.com/css'
		);

		wp_enqueue_style( 'aqualuxe-google-fonts', $fonts_url, array(), null );
	}
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_google_fonts' );

/**
 * Add customizer controls for dark mode
 */
function aqualuxe_dark_mode_customizer( $wp_customize ) {
	// Add Dark Mode Section
	$wp_customize->add_section(
		'aqualuxe_dark_mode',
		array(
			'title'       => esc_html__( 'Dark Mode', 'aqualuxe' ),
			'description' => esc_html__( 'Configure dark mode settings', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 35,
		)
	);

	// Enable Dark Mode
	$wp_customize->add_setting(
		'aqualuxe_enable_dark_mode',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_enable_dark_mode',
		array(
			'label'       => esc_html__( 'Enable Dark Mode', 'aqualuxe' ),
			'description' => esc_html__( 'Allow users to switch between light and dark mode', 'aqualuxe' ),
			'section'     => 'aqualuxe_dark_mode',
			'type'        => 'checkbox',
		)
	);

	// Default Mode
	$wp_customize->add_setting(
		'aqualuxe_default_mode',
		array(
			'default'           => 'light',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_default_mode',
		array(
			'label'       => esc_html__( 'Default Mode', 'aqualuxe' ),
			'description' => esc_html__( 'Choose the default color mode', 'aqualuxe' ),
			'section'     => 'aqualuxe_dark_mode',
			'type'        => 'select',
			'choices'     => array(
				'light'    => esc_html__( 'Light', 'aqualuxe' ),
				'dark'     => esc_html__( 'Dark', 'aqualuxe' ),
				'system'   => esc_html__( 'System Preference', 'aqualuxe' ),
			),
		)
	);

	// Auto Dark Mode
	$wp_customize->add_setting(
		'aqualuxe_auto_dark_mode',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_auto_dark_mode',
		array(
			'label'       => esc_html__( 'Auto Dark Mode', 'aqualuxe' ),
			'description' => esc_html__( 'Automatically switch to dark mode based on user\'s system preference', 'aqualuxe' ),
			'section'     => 'aqualuxe_dark_mode',
			'type'        => 'checkbox',
		)
	);

	// Dark Mode Toggle Position
	$wp_customize->add_setting(
		'aqualuxe_dark_mode_toggle_position',
		array(
			'default'           => 'top-bar',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_dark_mode_toggle_position',
		array(
			'label'       => esc_html__( 'Toggle Position', 'aqualuxe' ),
			'description' => esc_html__( 'Choose where to display the dark mode toggle', 'aqualuxe' ),
			'section'     => 'aqualuxe_dark_mode',
			'type'        => 'select',
			'choices'     => array(
				'top-bar'    => esc_html__( 'Top Bar', 'aqualuxe' ),
				'header'     => esc_html__( 'Header', 'aqualuxe' ),
				'footer'     => esc_html__( 'Footer', 'aqualuxe' ),
				'floating'   => esc_html__( 'Floating Button', 'aqualuxe' ),
			),
		)
	);

	// Dark Mode Primary Color
	$wp_customize->add_setting(
		'aqualuxe_dark_mode_primary_color',
		array(
			'default'           => '#0ea5e9',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_dark_mode_primary_color',
			array(
				'label'       => esc_html__( 'Dark Mode Primary Color', 'aqualuxe' ),
				'description' => esc_html__( 'Primary color for dark mode', 'aqualuxe' ),
				'section'     => 'aqualuxe_dark_mode',
			)
		)
	);

	// Dark Mode Background Color
	$wp_customize->add_setting(
		'aqualuxe_dark_mode_bg_color',
		array(
			'default'           => '#121212',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_dark_mode_bg_color',
			array(
				'label'       => esc_html__( 'Dark Mode Background Color', 'aqualuxe' ),
				'description' => esc_html__( 'Background color for dark mode', 'aqualuxe' ),
				'section'     => 'aqualuxe_dark_mode',
			)
		)
	);

	// Dark Mode Text Color
	$wp_customize->add_setting(
		'aqualuxe_dark_mode_text_color',
		array(
			'default'           => '#e5e5e5',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_dark_mode_text_color',
			array(
				'label'       => esc_html__( 'Dark Mode Text Color', 'aqualuxe' ),
				'description' => esc_html__( 'Text color for dark mode', 'aqualuxe' ),
				'section'     => 'aqualuxe_dark_mode',
			)
		)
	);
}
add_action( 'customize_register', 'aqualuxe_dark_mode_customizer' );