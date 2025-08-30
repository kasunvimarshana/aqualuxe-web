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
			'title'       => __( 'AquaLuxe Theme Options', 'aqualuxe' ),
			'description' => __( 'Customize your AquaLuxe theme settings', 'aqualuxe' ),
			'priority'    => 130,
		)
	);
	
	// Add General Section
	$wp_customize->add_section(
		'aqualuxe_general_options',
		array(
			'title'       => __( 'General Options', 'aqualuxe' ),
			'description' => __( 'Configure general theme settings', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 10,
		)
	);
	
	// Add Logo Height Setting
	$wp_customize->add_setting(
		'aqualuxe_logo_height',
		array(
			'default'           => '60',
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_logo_height',
		array(
			'label'       => __( 'Logo Height (px)', 'aqualuxe' ),
			'description' => __( 'Adjust the height of your logo', 'aqualuxe' ),
			'section'     => 'aqualuxe_general_options',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 20,
				'max'  => 200,
				'step' => 1,
			),
		)
	);
	
	// Add Container Width Setting
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
			'description' => __( 'Adjust the maximum width of the content container', 'aqualuxe' ),
			'section'     => 'aqualuxe_general_options',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 960,
				'max'  => 1920,
				'step' => 10,
			),
		)
	);
	
	// Add Theme Color Setting
	$wp_customize->add_setting(
		'aqualuxe_theme_color',
		array(
			'default'           => '#0077B6',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_theme_color',
			array(
				'label'       => __( 'Theme Color', 'aqualuxe' ),
				'description' => __( 'Used for browser theme color and PWA', 'aqualuxe' ),
				'section'     => 'aqualuxe_general_options',
			)
		)
	);
	
	// Add Favicon Setting
	$wp_customize->add_setting(
		'aqualuxe_favicon',
		array(
			'default'           => get_template_directory_uri() . '/assets/dist/images/favicon.ico',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'aqualuxe_favicon',
			array(
				'label'       => __( 'Favicon', 'aqualuxe' ),
				'description' => __( 'Upload a custom favicon (supports .ico, .png, .jpg)', 'aqualuxe' ),
				'section'     => 'aqualuxe_general_options',
			)
		)
	);
	
	// Add Default Open Graph Image Setting
	$wp_customize->add_setting(
		'aqualuxe_default_opengraph_image',
		array(
			'default'           => get_template_directory_uri() . '/assets/dist/images/default-opengraph.jpg',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'aqualuxe_default_opengraph_image',
			array(
				'label'       => __( 'Default Social Sharing Image', 'aqualuxe' ),
				'description' => __( 'Used when no featured image is set (recommended size: 1200x630px)', 'aqualuxe' ),
				'section'     => 'aqualuxe_general_options',
			)
		)
	);
	
	// Add Colors Section
	$wp_customize->add_section(
		'aqualuxe_colors_options',
		array(
			'title'       => __( 'Colors', 'aqualuxe' ),
			'description' => __( 'Customize theme colors', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 20,
		)
	);
	
	// Add Primary Color Setting
	$wp_customize->add_setting(
		'aqualuxe_primary_color',
		array(
			'default'           => '#0077B6',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_primary_color',
			array(
				'label'       => __( 'Primary Color', 'aqualuxe' ),
				'description' => __( 'Used for primary buttons, links, and accents', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_options',
			)
		)
	);
	
	// Add Secondary Color Setting
	$wp_customize->add_setting(
		'aqualuxe_secondary_color',
		array(
			'default'           => '#00B4D8',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_secondary_color',
			array(
				'label'       => __( 'Secondary Color', 'aqualuxe' ),
				'description' => __( 'Used for secondary buttons and accents', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_options',
			)
		)
	);
	
	// Add Accent Color Setting
	$wp_customize->add_setting(
		'aqualuxe_accent_color',
		array(
			'default'           => '#90E0EF',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_accent_color',
			array(
				'label'       => __( 'Accent Color', 'aqualuxe' ),
				'description' => __( 'Used for highlights and subtle accents', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_options',
			)
		)
	);
	
	// Add Luxe Color Setting
	$wp_customize->add_setting(
		'aqualuxe_luxe_color',
		array(
			'default'           => '#CAB79F',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_luxe_color',
			array(
				'label'       => __( 'Luxe Color', 'aqualuxe' ),
				'description' => __( 'Used for luxury elements and gold accents', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_options',
			)
		)
	);
	
	// Add Dark Color Setting
	$wp_customize->add_setting(
		'aqualuxe_dark_color',
		array(
			'default'           => '#023E8A',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_dark_color',
			array(
				'label'       => __( 'Dark Color', 'aqualuxe' ),
				'description' => __( 'Used for dark mode and deep blue elements', 'aqualuxe' ),
				'section'     => 'aqualuxe_colors_options',
			)
		)
	);
	
	// Add Typography Section
	$wp_customize->add_section(
		'aqualuxe_typography_options',
		array(
			'title'       => __( 'Typography', 'aqualuxe' ),
			'description' => __( 'Customize font settings', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 30,
		)
	);
	
	// Add Base Font Size Setting
	$wp_customize->add_setting(
		'aqualuxe_base_font_size',
		array(
			'default'           => '16',
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_base_font_size',
		array(
			'label'       => __( 'Base Font Size (px)', 'aqualuxe' ),
			'description' => __( 'Adjust the base font size for the entire site', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography_options',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 12,
				'max'  => 24,
				'step' => 1,
			),
		)
	);
	
	// Add Heading Font Setting
	$wp_customize->add_setting(
		'aqualuxe_heading_font',
		array(
			'default'           => 'Playfair Display',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_heading_font',
		array(
			'label'       => __( 'Heading Font', 'aqualuxe' ),
			'description' => __( 'Select font for headings', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography_options',
			'type'        => 'select',
			'choices'     => array(
				'Playfair Display' => 'Playfair Display',
				'Montserrat'       => 'Montserrat',
				'Roboto'           => 'Roboto',
				'Open Sans'        => 'Open Sans',
				'Lora'             => 'Lora',
			),
		)
	);
	
	// Add Body Font Setting
	$wp_customize->add_setting(
		'aqualuxe_body_font',
		array(
			'default'           => 'Montserrat',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_body_font',
		array(
			'label'       => __( 'Body Font', 'aqualuxe' ),
			'description' => __( 'Select font for body text', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography_options',
			'type'        => 'select',
			'choices'     => array(
				'Montserrat' => 'Montserrat',
				'Roboto'     => 'Roboto',
				'Open Sans'  => 'Open Sans',
				'Lato'       => 'Lato',
				'Nunito'     => 'Nunito',
			),
		)
	);
	
	// Add Header Section
	$wp_customize->add_section(
		'aqualuxe_header_options',
		array(
			'title'       => __( 'Header', 'aqualuxe' ),
			'description' => __( 'Customize header settings', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 40,
		)
	);
	
	// Add Header Layout Setting
	$wp_customize->add_setting(
		'aqualuxe_header_layout',
		array(
			'default'           => 'default',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_header_layout',
		array(
			'label'       => __( 'Header Layout', 'aqualuxe' ),
			'description' => __( 'Select the header layout style', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_options',
			'type'        => 'select',
			'choices'     => array(
				'default'      => __( 'Default', 'aqualuxe' ),
				'centered'     => __( 'Centered', 'aqualuxe' ),
				'transparent'  => __( 'Transparent', 'aqualuxe' ),
				'minimal'      => __( 'Minimal', 'aqualuxe' ),
			),
		)
	);
	
	// Add Sticky Header Setting
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
			'section'     => 'aqualuxe_header_options',
			'type'        => 'checkbox',
		)
	);
	
	// Add Top Bar Enable Setting
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
			'description' => __( 'Show the top bar above the header', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_options',
			'type'        => 'checkbox',
		)
	);
	
	// Add Top Bar Text Setting
	$wp_customize->add_setting(
		'aqualuxe_top_bar_text',
		array(
			'default'           => __( 'Free shipping on orders over $100', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_top_bar_text',
		array(
			'label'       => __( 'Top Bar Text', 'aqualuxe' ),
			'description' => __( 'Text to display in the top bar', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_options',
			'type'        => 'text',
			'active_callback' => 'aqualuxe_is_top_bar_enabled',
		)
	);
	
	// Add Top Bar Phone Setting
	$wp_customize->add_setting(
		'aqualuxe_top_bar_phone',
		array(
			'default'           => '+1 (555) 123-4567',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_top_bar_phone',
		array(
			'label'       => __( 'Top Bar Phone', 'aqualuxe' ),
			'description' => __( 'Phone number to display in the top bar', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_options',
			'type'        => 'text',
			'active_callback' => 'aqualuxe_is_top_bar_enabled',
		)
	);
	
	// Add Top Bar Email Setting
	$wp_customize->add_setting(
		'aqualuxe_top_bar_email',
		array(
			'default'           => 'info@aqualuxe.com',
			'sanitize_callback' => 'sanitize_email',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_top_bar_email',
		array(
			'label'       => __( 'Top Bar Email', 'aqualuxe' ),
			'description' => __( 'Email address to display in the top bar', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_options',
			'type'        => 'email',
			'active_callback' => 'aqualuxe_is_top_bar_enabled',
		)
	);
	
	// Add Top Bar Hours Setting
	$wp_customize->add_setting(
		'aqualuxe_top_bar_hours',
		array(
			'default'           => __( 'Mon-Fri: 9AM-5PM', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_top_bar_hours',
		array(
			'label'       => __( 'Top Bar Hours', 'aqualuxe' ),
			'description' => __( 'Business hours to display in the top bar', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_options',
			'type'        => 'text',
			'active_callback' => 'aqualuxe_is_top_bar_enabled',
		)
	);
	
	// Add Search Toggle Enable Setting
	$wp_customize->add_setting(
		'aqualuxe_search_toggle_enable',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_search_toggle_enable',
		array(
			'label'       => __( 'Enable Search Toggle', 'aqualuxe' ),
			'description' => __( 'Show the search toggle button in the header', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_options',
			'type'        => 'checkbox',
		)
	);
	
	// Add Search Form Enable Setting
	$wp_customize->add_setting(
		'aqualuxe_search_form_enable',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_search_form_enable',
		array(
			'label'       => __( 'Enable Search Form', 'aqualuxe' ),
			'description' => __( 'Show the search form in the header', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_options',
			'type'        => 'checkbox',
			'active_callback' => 'aqualuxe_is_search_toggle_enabled',
		)
	);
	
	// Add Search Form Placeholder Setting
	$wp_customize->add_setting(
		'aqualuxe_search_form_placeholder',
		array(
			'default'           => __( 'Search for products, species, or articles...', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_search_form_placeholder',
		array(
			'label'       => __( 'Search Form Placeholder', 'aqualuxe' ),
			'description' => __( 'Placeholder text for the search form', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_options',
			'type'        => 'text',
			'active_callback' => 'aqualuxe_is_search_form_enabled',
		)
	);
	
	// Add Popular Searches Setting
	$wp_customize->add_setting(
		'aqualuxe_popular_searches',
		array(
			'default'           => array(
				__( 'Discus Fish', 'aqualuxe' ),
				__( 'Aquarium Plants', 'aqualuxe' ),
				__( 'LED Lighting', 'aqualuxe' ),
				__( 'Aquascaping', 'aqualuxe' ),
			),
			'sanitize_callback' => 'aqualuxe_sanitize_array',
		)
	);
	
	$wp_customize->add_control(
		new Aqualuxe_Customize_Repeater_Control(
			$wp_customize,
			'aqualuxe_popular_searches',
			array(
				'label'       => __( 'Popular Searches', 'aqualuxe' ),
				'description' => __( 'Add popular search terms to display in the search form', 'aqualuxe' ),
				'section'     => 'aqualuxe_header_options',
				'fields'      => array(
					'text' => array(
						'type'        => 'text',
						'label'       => __( 'Search Term', 'aqualuxe' ),
						'default'     => '',
					),
				),
				'active_callback' => 'aqualuxe_is_search_form_enabled',
			)
		)
	);
	
	// Add Footer Section
	$wp_customize->add_section(
		'aqualuxe_footer_options',
		array(
			'title'       => __( 'Footer', 'aqualuxe' ),
			'description' => __( 'Customize footer settings', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 50,
		)
	);
	
	// Add Footer Layout Setting
	$wp_customize->add_setting(
		'aqualuxe_footer_layout',
		array(
			'default'           => 'default',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_footer_layout',
		array(
			'label'       => __( 'Footer Layout', 'aqualuxe' ),
			'description' => __( 'Select the footer layout style', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer_options',
			'type'        => 'select',
			'choices'     => array(
				'default'      => __( 'Default (4 Columns)', 'aqualuxe' ),
				'three-column' => __( '3 Columns', 'aqualuxe' ),
				'two-column'   => __( '2 Columns', 'aqualuxe' ),
				'minimal'      => __( 'Minimal', 'aqualuxe' ),
			),
		)
	);
	
	// Add Footer Copyright Setting
	$wp_customize->add_setting(
		'aqualuxe_footer_copyright',
		array(
			'default'           => sprintf(
				/* translators: %s: Current year */
				__( '© %s AquaLuxe. All rights reserved.', 'aqualuxe' ),
				date_i18n( 'Y' )
			),
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_footer_copyright',
		array(
			'label'       => __( 'Footer Copyright Text', 'aqualuxe' ),
			'description' => __( 'Copyright text to display in the footer', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer_options',
			'type'        => 'textarea',
		)
	);
	
	// Add Footer Background Color Setting
	$wp_customize->add_setting(
		'aqualuxe_footer_background_color',
		array(
			'default'           => '#023E8A',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_footer_background_color',
			array(
				'label'       => __( 'Footer Background Color', 'aqualuxe' ),
				'description' => __( 'Background color for the footer', 'aqualuxe' ),
				'section'     => 'aqualuxe_footer_options',
			)
		)
	);
	
	// Add Footer Text Color Setting
	$wp_customize->add_setting(
		'aqualuxe_footer_text_color',
		array(
			'default'           => '#FFFFFF',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_footer_text_color',
			array(
				'label'       => __( 'Footer Text Color', 'aqualuxe' ),
				'description' => __( 'Text color for the footer', 'aqualuxe' ),
				'section'     => 'aqualuxe_footer_options',
			)
		)
	);
	
	// Add Social Media Section
	$wp_customize->add_section(
		'aqualuxe_social_options',
		array(
			'title'       => __( 'Social Media', 'aqualuxe' ),
			'description' => __( 'Configure social media links', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 60,
		)
	);
	
	// Add Facebook Setting
	$wp_customize->add_setting(
		'aqualuxe_social_facebook',
		array(
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_social_facebook',
		array(
			'label'       => __( 'Facebook URL', 'aqualuxe' ),
			'description' => __( 'Enter your Facebook page URL', 'aqualuxe' ),
			'section'     => 'aqualuxe_social_options',
			'type'        => 'url',
		)
	);
	
	// Add Twitter Setting
	$wp_customize->add_setting(
		'aqualuxe_social_twitter',
		array(
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_social_twitter',
		array(
			'label'       => __( 'Twitter URL', 'aqualuxe' ),
			'description' => __( 'Enter your Twitter profile URL', 'aqualuxe' ),
			'section'     => 'aqualuxe_social_options',
			'type'        => 'url',
		)
	);
	
	// Add Instagram Setting
	$wp_customize->add_setting(
		'aqualuxe_social_instagram',
		array(
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_social_instagram',
		array(
			'label'       => __( 'Instagram URL', 'aqualuxe' ),
			'description' => __( 'Enter your Instagram profile URL', 'aqualuxe' ),
			'section'     => 'aqualuxe_social_options',
			'type'        => 'url',
		)
	);
	
	// Add YouTube Setting
	$wp_customize->add_setting(
		'aqualuxe_social_youtube',
		array(
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_social_youtube',
		array(
			'label'       => __( 'YouTube URL', 'aqualuxe' ),
			'description' => __( 'Enter your YouTube channel URL', 'aqualuxe' ),
			'section'     => 'aqualuxe_social_options',
			'type'        => 'url',
		)
	);
	
	// Add LinkedIn Setting
	$wp_customize->add_setting(
		'aqualuxe_social_linkedin',
		array(
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_social_linkedin',
		array(
			'label'       => __( 'LinkedIn URL', 'aqualuxe' ),
			'description' => __( 'Enter your LinkedIn profile URL', 'aqualuxe' ),
			'section'     => 'aqualuxe_social_options',
			'type'        => 'url',
		)
	);
	
	// Add Social Sharing Setting
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
			'label'       => __( 'Enable Social Sharing', 'aqualuxe' ),
			'description' => __( 'Show social sharing buttons on posts and products', 'aqualuxe' ),
			'section'     => 'aqualuxe_social_options',
			'type'        => 'checkbox',
		)
	);
	
	// Add Homepage Section
	$wp_customize->add_section(
		'aqualuxe_homepage_options',
		array(
			'title'       => __( 'Homepage', 'aqualuxe' ),
			'description' => __( 'Configure homepage sections', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 70,
		)
	);
	
	// Add Hero Title Setting
	$wp_customize->add_setting(
		'aqualuxe_hero_title',
		array(
			'default'           => __( 'Bringing elegance to aquatic life – globally', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_hero_title',
		array(
			'label'       => __( 'Hero Title', 'aqualuxe' ),
			'description' => __( 'Main heading for the hero section', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add Hero Subtitle Setting
	$wp_customize->add_setting(
		'aqualuxe_hero_subtitle',
		array(
			'default'           => __( 'Premium ornamental fish, aquatic plants, and custom aquarium solutions for collectors and enthusiasts', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_hero_subtitle',
		array(
			'label'       => __( 'Hero Subtitle', 'aqualuxe' ),
			'description' => __( 'Subheading for the hero section', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add Hero Button Text Setting
	$wp_customize->add_setting(
		'aqualuxe_hero_button_text',
		array(
			'default'           => __( 'Explore Collection', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_hero_button_text',
		array(
			'label'       => __( 'Hero Button Text', 'aqualuxe' ),
			'description' => __( 'Text for the primary hero button', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add Hero Button URL Setting
	$wp_customize->add_setting(
		'aqualuxe_hero_button_url',
		array(
			'default'           => class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : '#',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_hero_button_url',
		array(
			'label'       => __( 'Hero Button URL', 'aqualuxe' ),
			'description' => __( 'URL for the primary hero button', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'url',
		)
	);
	
	// Add Hero Secondary Button Text Setting
	$wp_customize->add_setting(
		'aqualuxe_hero_secondary_button_text',
		array(
			'default'           => __( 'Our Services', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_hero_secondary_button_text',
		array(
			'label'       => __( 'Hero Secondary Button Text', 'aqualuxe' ),
			'description' => __( 'Text for the secondary hero button', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add Hero Secondary Button URL Setting
	$wp_customize->add_setting(
		'aqualuxe_hero_secondary_button_url',
		array(
			'default'           => home_url( '/services' ),
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_hero_secondary_button_url',
		array(
			'label'       => __( 'Hero Secondary Button URL', 'aqualuxe' ),
			'description' => __( 'URL for the secondary hero button', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'url',
		)
	);
	
	// Add Hero Background Setting
	$wp_customize->add_setting(
		'aqualuxe_hero_background',
		array(
			'default'           => get_template_directory_uri() . '/assets/dist/images/hero-background.jpg',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'aqualuxe_hero_background',
			array(
				'label'       => __( 'Hero Background Image', 'aqualuxe' ),
				'description' => __( 'Background image for the hero section', 'aqualuxe' ),
				'section'     => 'aqualuxe_homepage_options',
			)
		)
	);
	
	// Add Hero Video Setting
	$wp_customize->add_setting(
		'aqualuxe_hero_video',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'aqualuxe_hero_video',
			array(
				'label'       => __( 'Hero Background Video', 'aqualuxe' ),
				'description' => __( 'Optional background video for the hero section (MP4 format)', 'aqualuxe' ),
				'section'     => 'aqualuxe_homepage_options',
				'mime_type'   => 'video',
			)
		)
	);
	
	// Add Featured Products Title Setting
	$wp_customize->add_setting(
		'aqualuxe_featured_products_title',
		array(
			'default'           => __( 'Featured Products', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_featured_products_title',
		array(
			'label'       => __( 'Featured Products Title', 'aqualuxe' ),
			'description' => __( 'Heading for the featured products section', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add Featured Products Subtitle Setting
	$wp_customize->add_setting(
		'aqualuxe_featured_products_subtitle',
		array(
			'default'           => __( 'Discover our premium selection of rare and exotic aquatic species', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_featured_products_subtitle',
		array(
			'label'       => __( 'Featured Products Subtitle', 'aqualuxe' ),
			'description' => __( 'Subheading for the featured products section', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add Featured Products Count Setting
	$wp_customize->add_setting(
		'aqualuxe_featured_products_count',
		array(
			'default'           => 4,
			'sanitize_callback' => 'absint',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_featured_products_count',
		array(
			'label'       => __( 'Featured Products Count', 'aqualuxe' ),
			'description' => __( 'Number of products to display', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 12,
				'step' => 1,
			),
		)
	);
	
	// Add Featured Products Columns Setting
	$wp_customize->add_setting(
		'aqualuxe_featured_products_columns',
		array(
			'default'           => 4,
			'sanitize_callback' => 'absint',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_featured_products_columns',
		array(
			'label'       => __( 'Featured Products Columns', 'aqualuxe' ),
			'description' => __( 'Number of columns to display', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 6,
				'step' => 1,
			),
		)
	);
	
	// Add Featured Products Type Setting
	$wp_customize->add_setting(
		'aqualuxe_featured_products_type',
		array(
			'default'           => 'featured',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_featured_products_type',
		array(
			'label'       => __( 'Featured Products Type', 'aqualuxe' ),
			'description' => __( 'Type of products to display', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'select',
			'choices'     => array(
				'featured'     => __( 'Featured Products', 'aqualuxe' ),
				'best_selling' => __( 'Best Selling Products', 'aqualuxe' ),
				'newest'       => __( 'Newest Products', 'aqualuxe' ),
				'sale'         => __( 'Sale Products', 'aqualuxe' ),
			),
		)
	);
	
	// Add Featured Products Button Text Setting
	$wp_customize->add_setting(
		'aqualuxe_featured_products_button_text',
		array(
			'default'           => __( 'View All Products', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_featured_products_button_text',
		array(
			'label'       => __( 'Featured Products Button Text', 'aqualuxe' ),
			'description' => __( 'Text for the featured products button', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add Featured Products Button URL Setting
	$wp_customize->add_setting(
		'aqualuxe_featured_products_button_url',
		array(
			'default'           => class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : '#',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_featured_products_button_url',
		array(
			'label'       => __( 'Featured Products Button URL', 'aqualuxe' ),
			'description' => __( 'URL for the featured products button', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'url',
		)
	);
	
	// Add About Title Setting
	$wp_customize->add_setting(
		'aqualuxe_about_title',
		array(
			'default'           => __( 'About AquaLuxe', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_about_title',
		array(
			'label'       => __( 'About Title', 'aqualuxe' ),
			'description' => __( 'Heading for the about section', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add About Subtitle Setting
	$wp_customize->add_setting(
		'aqualuxe_about_subtitle',
		array(
			'default'           => __( 'Discover our story and passion for aquatic life', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_about_subtitle',
		array(
			'label'       => __( 'About Subtitle', 'aqualuxe' ),
			'description' => __( 'Subheading for the about section', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add About Content Setting
	$wp_customize->add_setting(
		'aqualuxe_about_content',
		array(
			'default'           => __( 'AquaLuxe is a vertically integrated ornamental fish farming and aquatic lifestyle company serving both local and international markets. We specialize in rare and exotic fish species, premium aquatic plants, and custom aquarium solutions for collectors and enthusiasts. Our commitment to quality, sustainability, and exceptional service has made us a trusted name in the aquatic industry.', 'aqualuxe' ),
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_about_content',
		array(
			'label'       => __( 'About Content', 'aqualuxe' ),
			'description' => __( 'Content for the about section', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'textarea',
		)
	);
	
	// Add About Image Setting
	$wp_customize->add_setting(
		'aqualuxe_about_image',
		array(
			'default'           => get_template_directory_uri() . '/assets/dist/images/about-image.jpg',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'aqualuxe_about_image',
			array(
				'label'       => __( 'About Image', 'aqualuxe' ),
				'description' => __( 'Image for the about section', 'aqualuxe' ),
				'section'     => 'aqualuxe_homepage_options',
			)
		)
	);
	
	// Add About Button Text Setting
	$wp_customize->add_setting(
		'aqualuxe_about_button_text',
		array(
			'default'           => __( 'Learn More', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_about_button_text',
		array(
			'label'       => __( 'About Button Text', 'aqualuxe' ),
			'description' => __( 'Text for the about button', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add About Button URL Setting
	$wp_customize->add_setting(
		'aqualuxe_about_button_url',
		array(
			'default'           => home_url( '/about' ),
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_about_button_url',
		array(
			'label'       => __( 'About Button URL', 'aqualuxe' ),
			'description' => __( 'URL for the about button', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'url',
		)
	);
	
	// Add Services Title Setting
	$wp_customize->add_setting(
		'aqualuxe_services_title',
		array(
			'default'           => __( 'Our Services', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_services_title',
		array(
			'label'       => __( 'Services Title', 'aqualuxe' ),
			'description' => __( 'Heading for the services section', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add Services Subtitle Setting
	$wp_customize->add_setting(
		'aqualuxe_services_subtitle',
		array(
			'default'           => __( 'Professional aquatic solutions for every need', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_services_subtitle',
		array(
			'label'       => __( 'Services Subtitle', 'aqualuxe' ),
			'description' => __( 'Subheading for the services section', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add Services Button Text Setting
	$wp_customize->add_setting(
		'aqualuxe_services_button_text',
		array(
			'default'           => __( 'View All Services', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_services_button_text',
		array(
			'label'       => __( 'Services Button Text', 'aqualuxe' ),
			'description' => __( 'Text for the services button', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add Services Button URL Setting
	$wp_customize->add_setting(
		'aqualuxe_services_button_url',
		array(
			'default'           => home_url( '/services' ),
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_services_button_url',
		array(
			'label'       => __( 'Services Button URL', 'aqualuxe' ),
			'description' => __( 'URL for the services button', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'url',
		)
	);
	
	// Add Testimonials Title Setting
	$wp_customize->add_setting(
		'aqualuxe_testimonials_title',
		array(
			'default'           => __( 'What Our Clients Say', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_testimonials_title',
		array(
			'label'       => __( 'Testimonials Title', 'aqualuxe' ),
			'description' => __( 'Heading for the testimonials section', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add Testimonials Subtitle Setting
	$wp_customize->add_setting(
		'aqualuxe_testimonials_subtitle',
		array(
			'default'           => __( 'Hear from our satisfied customers around the world', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_testimonials_subtitle',
		array(
			'label'       => __( 'Testimonials Subtitle', 'aqualuxe' ),
			'description' => __( 'Subheading for the testimonials section', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add Testimonials Background Setting
	$wp_customize->add_setting(
		'aqualuxe_testimonials_background',
		array(
			'default'           => get_template_directory_uri() . '/assets/dist/images/testimonials-background.jpg',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'aqualuxe_testimonials_background',
			array(
				'label'       => __( 'Testimonials Background', 'aqualuxe' ),
				'description' => __( 'Background image for the testimonials section', 'aqualuxe' ),
				'section'     => 'aqualuxe_homepage_options',
			)
		)
	);
	
	// Add Blog Title Setting
	$wp_customize->add_setting(
		'aqualuxe_blog_title',
		array(
			'default'           => __( 'Latest Articles', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_blog_title',
		array(
			'label'       => __( 'Blog Title', 'aqualuxe' ),
			'description' => __( 'Heading for the blog section', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add Blog Subtitle Setting
	$wp_customize->add_setting(
		'aqualuxe_blog_subtitle',
		array(
			'default'           => __( 'Tips, guides, and news from the aquatic world', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_blog_subtitle',
		array(
			'label'       => __( 'Blog Subtitle', 'aqualuxe' ),
			'description' => __( 'Subheading for the blog section', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add Blog Count Setting
	$wp_customize->add_setting(
		'aqualuxe_blog_count',
		array(
			'default'           => 3,
			'sanitize_callback' => 'absint',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_blog_count',
		array(
			'label'       => __( 'Blog Count', 'aqualuxe' ),
			'description' => __( 'Number of posts to display', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 9,
				'step' => 1,
			),
		)
	);
	
	// Add Blog Button Text Setting
	$wp_customize->add_setting(
		'aqualuxe_blog_button_text',
		array(
			'default'           => __( 'View All Articles', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_blog_button_text',
		array(
			'label'       => __( 'Blog Button Text', 'aqualuxe' ),
			'description' => __( 'Text for the blog button', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add Blog Button URL Setting
	$wp_customize->add_setting(
		'aqualuxe_blog_button_url',
		array(
			'default'           => get_permalink( get_option( 'page_for_posts' ) ),
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_blog_button_url',
		array(
			'label'       => __( 'Blog Button URL', 'aqualuxe' ),
			'description' => __( 'URL for the blog button', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'url',
		)
	);
	
	// Add Newsletter Title Setting
	$wp_customize->add_setting(
		'aqualuxe_newsletter_title',
		array(
			'default'           => __( 'Subscribe to Our Newsletter', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_newsletter_title',
		array(
			'label'       => __( 'Newsletter Title', 'aqualuxe' ),
			'description' => __( 'Heading for the newsletter section', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add Newsletter Subtitle Setting
	$wp_customize->add_setting(
		'aqualuxe_newsletter_subtitle',
		array(
			'default'           => __( 'Stay updated with our latest arrivals, exclusive offers, and expert aquatic tips', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_newsletter_subtitle',
		array(
			'label'       => __( 'Newsletter Subtitle', 'aqualuxe' ),
			'description' => __( 'Subheading for the newsletter section', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add Newsletter Background Setting
	$wp_customize->add_setting(
		'aqualuxe_newsletter_background',
		array(
			'default'           => get_template_directory_uri() . '/assets/dist/images/newsletter-background.jpg',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'aqualuxe_newsletter_background',
			array(
				'label'       => __( 'Newsletter Background', 'aqualuxe' ),
				'description' => __( 'Background image for the newsletter section', 'aqualuxe' ),
				'section'     => 'aqualuxe_homepage_options',
			)
		)
	);
	
	// Add Newsletter Form Shortcode Setting
	$wp_customize->add_setting(
		'aqualuxe_newsletter_form_shortcode',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_newsletter_form_shortcode',
		array(
			'label'       => __( 'Newsletter Form Shortcode', 'aqualuxe' ),
			'description' => __( 'Shortcode for the newsletter form (e.g., from MailChimp, Constant Contact, etc.)', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add Partners Title Setting
	$wp_customize->add_setting(
		'aqualuxe_partners_title',
		array(
			'default'           => __( 'Our Partners', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_partners_title',
		array(
			'label'       => __( 'Partners Title', 'aqualuxe' ),
			'description' => __( 'Heading for the partners section', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add Partners Subtitle Setting
	$wp_customize->add_setting(
		'aqualuxe_partners_subtitle',
		array(
			'default'           => __( 'Trusted by leading organizations worldwide', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_partners_subtitle',
		array(
			'label'       => __( 'Partners Subtitle', 'aqualuxe' ),
			'description' => __( 'Subheading for the partners section', 'aqualuxe' ),
			'section'     => 'aqualuxe_homepage_options',
			'type'        => 'text',
		)
	);
	
	// Add WooCommerce Section
	if ( class_exists( 'WooCommerce' ) ) {
		$wp_customize->add_section(
			'aqualuxe_woocommerce_options',
			array(
				'title'       => __( 'WooCommerce', 'aqualuxe' ),
				'description' => __( 'Configure WooCommerce settings', 'aqualuxe' ),
				'panel'       => 'aqualuxe_theme_options',
				'priority'    => 80,
			)
		);
		
		// Add Cart Icon Enable Setting
		$wp_customize->add_setting(
			'aqualuxe_cart_icon_enable',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);
		
		$wp_customize->add_control(
			'aqualuxe_cart_icon_enable',
			array(
				'label'       => __( 'Enable Cart Icon', 'aqualuxe' ),
				'description' => __( 'Show the cart icon in the header', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_options',
				'type'        => 'checkbox',
			)
		);
		
		// Add Cart Dropdown Enable Setting
		$wp_customize->add_setting(
			'aqualuxe_cart_dropdown_enable',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);
		
		$wp_customize->add_control(
			'aqualuxe_cart_dropdown_enable',
			array(
				'label'       => __( 'Enable Cart Dropdown', 'aqualuxe' ),
				'description' => __( 'Show the cart dropdown when hovering over the cart icon', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_options',
				'type'        => 'checkbox',
				'active_callback' => 'aqualuxe_is_cart_icon_enabled',
			)
		);
		
		// Add Quick View Enable Setting
		$wp_customize->add_setting(
			'aqualuxe_quick_view_enable',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);
		
		$wp_customize->add_control(
			'aqualuxe_quick_view_enable',
			array(
				'label'       => __( 'Enable Quick View', 'aqualuxe' ),
				'description' => __( 'Show the quick view button on products', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_options',
				'type'        => 'checkbox',
			)
		);
		
		// Add Wishlist Enable Setting
		$wp_customize->add_setting(
			'aqualuxe_wishlist_enable',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);
		
		$wp_customize->add_control(
			'aqualuxe_wishlist_enable',
			array(
				'label'       => __( 'Enable Wishlist', 'aqualuxe' ),
				'description' => __( 'Show the wishlist button on products', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_options',
				'type'        => 'checkbox',
			)
		);
		
		// Add Product Columns Setting
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
				'description' => __( 'Number of product columns on shop page', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_options',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 1,
					'max'  => 6,
					'step' => 1,
				),
			)
		);
		
		// Add Products Per Page Setting
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
				'description' => __( 'Number of products to display per page', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_options',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 1,
					'max'  => 48,
					'step' => 1,
				),
			)
		);
		
		// Add Related Products Count Setting
		$wp_customize->add_setting(
			'aqualuxe_related_products_count',
			array(
				'default'           => 4,
				'sanitize_callback' => 'absint',
			)
		);
		
		$wp_customize->add_control(
			'aqualuxe_related_products_count',
			array(
				'label'       => __( 'Related Products Count', 'aqualuxe' ),
				'description' => __( 'Number of related products to display', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_options',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 12,
					'step' => 1,
				),
			)
		);
		
		// Add Related Products Columns Setting
		$wp_customize->add_setting(
			'aqualuxe_related_products_columns',
			array(
				'default'           => 4,
				'sanitize_callback' => 'absint',
			)
		);
		
		$wp_customize->add_control(
			'aqualuxe_related_products_columns',
			array(
				'label'       => __( 'Related Products Columns', 'aqualuxe' ),
				'description' => __( 'Number of columns for related products', 'aqualuxe' ),
				'section'     => 'aqualuxe_woocommerce_options',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 1,
					'max'  => 6,
					'step' => 1,
				),
			)
		);
	}
	
	// Add Performance Section
	$wp_customize->add_section(
		'aqualuxe_performance_options',
		array(
			'title'       => __( 'Performance', 'aqualuxe' ),
			'description' => __( 'Configure performance settings', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
			'priority'    => 90,
		)
	);
	
	// Add Lazy Loading Setting
	$wp_customize->add_setting(
		'aqualuxe_lazy_loading',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_lazy_loading',
		array(
			'label'       => __( 'Enable Lazy Loading', 'aqualuxe' ),
			'description' => __( 'Lazy load images and iframes for better performance', 'aqualuxe' ),
			'section'     => 'aqualuxe_performance_options',
			'type'        => 'checkbox',
		)
	);
	
	// Add Minify Assets Setting
	$wp_customize->add_setting(
		'aqualuxe_minify_assets',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_minify_assets',
		array(
			'label'       => __( 'Minify Assets', 'aqualuxe' ),
			'description' => __( 'Minify CSS and JavaScript files for better performance', 'aqualuxe' ),
			'section'     => 'aqualuxe_performance_options',
			'type'        => 'checkbox',
		)
	);
	
	// Add Preload Critical Assets Setting
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
			'description' => __( 'Preload critical assets like fonts and CSS for better performance', 'aqualuxe' ),
			'section'     => 'aqualuxe_performance_options',
			'type'        => 'checkbox',
		)
	);
	
	// Add Defer JavaScript Setting
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
			'description' => __( 'Defer JavaScript loading for better performance', 'aqualuxe' ),
			'section'     => 'aqualuxe_performance_options',
			'type'        => 'checkbox',
		)
	);
	
	// Add Remove Emoji Script Setting
	$wp_customize->add_setting(
		'aqualuxe_remove_emoji_script',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_remove_emoji_script',
		array(
			'label'       => __( 'Remove Emoji Script', 'aqualuxe' ),
			'description' => __( 'Remove WordPress emoji script for better performance', 'aqualuxe' ),
			'section'     => 'aqualuxe_performance_options',
			'type'        => 'checkbox',
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
	wp_enqueue_script( 'aqualuxe-customizer', aqualuxe_asset( 'js/customizer.js' ), array( 'customize-preview' ), AQUALUXE_VERSION, true );
}
add_action( 'customize_preview_init', 'aqualuxe_customize_preview_js' );

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
 * @param string $input   The input from the setting.
 * @param object $setting The selected setting.
 * @return string
 */
function aqualuxe_sanitize_select( $input, $setting ) {
	$input   = sanitize_key( $input );
	$choices = $setting->manager->get_control( $setting->id )->choices;
	
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize array.
 *
 * @param array $input The input from the setting.
 * @return array
 */
function aqualuxe_sanitize_array( $input ) {
	if ( ! is_array( $input ) ) {
		return array();
	}
	
	foreach ( $input as $key => $value ) {
		if ( is_array( $value ) ) {
			$input[ $key ] = aqualuxe_sanitize_array( $value );
		} else {
			$input[ $key ] = sanitize_text_field( $value );
		}
	}
	
	return $input;
}

/**
 * Check if top bar is enabled.
 *
 * @return bool
 */
function aqualuxe_is_top_bar_enabled() {
	return get_theme_mod( 'aqualuxe_top_bar_enable', true );
}

/**
 * Check if search toggle is enabled.
 *
 * @return bool
 */
function aqualuxe_is_search_toggle_enabled() {
	return get_theme_mod( 'aqualuxe_search_toggle_enable', true );
}

/**
 * Check if search form is enabled.
 *
 * @return bool
 */
function aqualuxe_is_search_form_enabled() {
	return get_theme_mod( 'aqualuxe_search_form_enable', true ) && get_theme_mod( 'aqualuxe_search_toggle_enable', true );
}

/**
 * Check if cart icon is enabled.
 *
 * @return bool
 */
function aqualuxe_is_cart_icon_enabled() {
	return get_theme_mod( 'aqualuxe_cart_icon_enable', true );
}

/**
 * Customizer Repeater Control
 */
if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Aqualuxe_Customize_Repeater_Control' ) ) {
	/**
	 * Repeater control class.
	 */
	class Aqualuxe_Customize_Repeater_Control extends WP_Customize_Control {
		/**
		 * Control type.
		 *
		 * @var string
		 */
		public $type = 'aqualuxe-repeater';
		
		/**
		 * Fields.
		 *
		 * @var array
		 */
		public $fields = array();
		
		/**
		 * Constructor.
		 *
		 * @param WP_Customize_Manager $manager Customizer manager.
		 * @param string               $id      Control ID.
		 * @param array                $args    Control arguments.
		 */
		public function __construct( $manager, $id, $args = array() ) {
			parent::__construct( $manager, $id, $args );
			
			if ( isset( $args['fields'] ) ) {
				$this->fields = $args['fields'];
			}
		}
		
		/**
		 * Enqueue control related scripts/styles.
		 */
		public function enqueue() {
			wp_enqueue_script( 'aqualuxe-repeater-control', aqualuxe_asset( 'js/customizer-repeater.js' ), array( 'jquery', 'customize-controls' ), AQUALUXE_VERSION, true );
			wp_enqueue_style( 'aqualuxe-repeater-control', aqualuxe_asset( 'css/customizer-repeater.css' ), array(), AQUALUXE_VERSION );
		}
		
		/**
		 * Render control content.
		 */
		public function render_content() {
			if ( empty( $this->fields ) ) {
				return;
			}
			
			$values = $this->value();
			$values = ! is_array( $values ) ? array() : $values;
			?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>
			
			<div class="aqualuxe-repeater-control">
				<div class="aqualuxe-repeater-items">
					<?php foreach ( $values as $index => $value ) : ?>
						<div class="aqualuxe-repeater-item">
							<div class="aqualuxe-repeater-item-header">
								<span class="aqualuxe-repeater-item-title">
									<?php
									if ( isset( $value['text'] ) ) {
										echo esc_html( $value['text'] );
									} else {
										echo esc_html__( 'Item', 'aqualuxe' ) . ' ' . ( $index + 1 );
									}
									?>
								</span>
								<button type="button" class="aqualuxe-repeater-item-toggle" aria-expanded="false">
									<span class="screen-reader-text"><?php esc_html_e( 'Toggle item', 'aqualuxe' ); ?></span>
									<span class="aqualuxe-repeater-item-toggle-icon"></span>
								</button>
							</div>
							<div class="aqualuxe-repeater-item-content">
								<?php foreach ( $this->fields as $field_id => $field ) : ?>
									<div class="aqualuxe-repeater-field">
										<label>
											<?php echo esc_html( $field['label'] ); ?>
											<?php if ( 'text' === $field['type'] ) : ?>
												<input type="text" name="<?php echo esc_attr( $field_id ); ?>" value="<?php echo esc_attr( isset( $value[ $field_id ] ) ? $value[ $field_id ] : '' ); ?>" data-field="<?php echo esc_attr( $field_id ); ?>" />
											<?php elseif ( 'textarea' === $field['type'] ) : ?>
												<textarea name="<?php echo esc_attr( $field_id ); ?>" data-field="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_textarea( isset( $value[ $field_id ] ) ? $value[ $field_id ] : '' ); ?></textarea>
											<?php elseif ( 'url' === $field['type'] ) : ?>
												<input type="url" name="<?php echo esc_attr( $field_id ); ?>" value="<?php echo esc_url( isset( $value[ $field_id ] ) ? $value[ $field_id ] : '' ); ?>" data-field="<?php echo esc_attr( $field_id ); ?>" />
											<?php elseif ( 'select' === $field['type'] && isset( $field['choices'] ) ) : ?>
												<select name="<?php echo esc_attr( $field_id ); ?>" data-field="<?php echo esc_attr( $field_id ); ?>">
													<?php foreach ( $field['choices'] as $choice_value => $choice_label ) : ?>
														<option value="<?php echo esc_attr( $choice_value ); ?>" <?php selected( isset( $value[ $field_id ] ) ? $value[ $field_id ] : '', $choice_value ); ?>><?php echo esc_html( $choice_label ); ?></option>
													<?php endforeach; ?>
												</select>
											<?php endif; ?>
										</label>
									</div>
								<?php endforeach; ?>
								<div class="aqualuxe-repeater-item-actions">
									<button type="button" class="aqualuxe-repeater-item-remove"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<button type="button" class="button aqualuxe-repeater-add-item"><?php esc_html_e( 'Add Item', 'aqualuxe' ); ?></button>
				<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( wp_json_encode( $values ) ); ?>" <?php $this->link(); ?> />
			</div>
			
			<script type="text/html" id="tmpl-aqualuxe-repeater-item">
				<div class="aqualuxe-repeater-item">
					<div class="aqualuxe-repeater-item-header">
						<span class="aqualuxe-repeater-item-title"><?php esc_html_e( 'New Item', 'aqualuxe' ); ?></span>
						<button type="button" class="aqualuxe-repeater-item-toggle" aria-expanded="false">
							<span class="screen-reader-text"><?php esc_html_e( 'Toggle item', 'aqualuxe' ); ?></span>
							<span class="aqualuxe-repeater-item-toggle-icon"></span>
						</button>
					</div>
					<div class="aqualuxe-repeater-item-content">
						<?php foreach ( $this->fields as $field_id => $field ) : ?>
							<div class="aqualuxe-repeater-field">
								<label>
									<?php echo esc_html( $field['label'] ); ?>
									<?php if ( 'text' === $field['type'] ) : ?>
										<input type="text" name="<?php echo esc_attr( $field_id ); ?>" value="<?php echo esc_attr( isset( $field['default'] ) ? $field['default'] : '' ); ?>" data-field="<?php echo esc_attr( $field_id ); ?>" />
									<?php elseif ( 'textarea' === $field['type'] ) : ?>
										<textarea name="<?php echo esc_attr( $field_id ); ?>" data-field="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_textarea( isset( $field['default'] ) ? $field['default'] : '' ); ?></textarea>
									<?php elseif ( 'url' === $field['type'] ) : ?>
										<input type="url" name="<?php echo esc_attr( $field_id ); ?>" value="<?php echo esc_url( isset( $field['default'] ) ? $field['default'] : '' ); ?>" data-field="<?php echo esc_attr( $field_id ); ?>" />
									<?php elseif ( 'select' === $field['type'] && isset( $field['choices'] ) ) : ?>
										<select name="<?php echo esc_attr( $field_id ); ?>" data-field="<?php echo esc_attr( $field_id ); ?>">
											<?php foreach ( $field['choices'] as $choice_value => $choice_label ) : ?>
												<option value="<?php echo esc_attr( $choice_value ); ?>" <?php selected( isset( $field['default'] ) ? $field['default'] : '', $choice_value ); ?>><?php echo esc_html( $choice_label ); ?></option>
											<?php endforeach; ?>
										</select>
									<?php endif; ?>
								</label>
							</div>
						<?php endforeach; ?>
						<div class="aqualuxe-repeater-item-actions">
							<button type="button" class="aqualuxe-repeater-item-remove"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
						</div>
					</div>
				</div>
			</script>
			<?php
		}
	}
        
        // API Integrations Section
        $wp_customize->add_section(
                'aqualuxe_api_integrations',
                array(
                        'title'       => __( 'API Integrations', 'aqualuxe' ),
                        'priority'    => 170,
                        'capability'  => 'edit_theme_options',
                        'description' => __( 'Configure API keys and integrations.', 'aqualuxe' ),
                )
        );

        // Google Maps API Key
        $wp_customize->add_setting(
                'aqualuxe_google_maps_api_key',
                array(
                        'default'           => '',
                        'sanitize_callback' => 'sanitize_text_field',
                        'transport'         => 'refresh',
                )
        );

        $wp_customize->add_control(
                'aqualuxe_google_maps_api_key',
                array(
                        'label'       => __( 'Google Maps API Key', 'aqualuxe' ),
                        'description' => __( 'Enter your Google Maps API key to enable maps on the contact page.', 'aqualuxe' ),
                        'section'     => 'aqualuxe_api_integrations',
                        'type'        => 'text',
                        'input_attrs' => array(
                                'placeholder' => __( 'Enter API key', 'aqualuxe' ),
                        ),
                )
        );
}