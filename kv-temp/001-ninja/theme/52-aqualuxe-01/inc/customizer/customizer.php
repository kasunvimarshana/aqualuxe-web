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
			'description' => __( 'Theme Options for AquaLuxe', 'aqualuxe' ),
			'priority'    => 30,
		)
	);

	// Add Header Options Section
	aqualuxe_header_options( $wp_customize );

	// Add Footer Options Section
	aqualuxe_footer_options( $wp_customize );

	// Add Color Options Section
	aqualuxe_color_options( $wp_customize );

	// Add Typography Options Section
	aqualuxe_typography_options( $wp_customize );

	// Add Layout Options Section
	aqualuxe_layout_options( $wp_customize );

	// Add WooCommerce Options Section
	if ( class_exists( 'WooCommerce' ) ) {
		aqualuxe_woocommerce_options( $wp_customize );
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
 * Header Options
 */
function aqualuxe_header_options( $wp_customize ) {
	// Header Section
	$wp_customize->add_section(
		'aqualuxe_header_options',
		array(
			'title'       => __( 'Header Options', 'aqualuxe' ),
			'description' => __( 'Customize the header section', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
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
			'label'       => __( 'Header Layout', 'aqualuxe' ),
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
			'label'       => __( 'Enable Sticky Header', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_options',
			'type'        => 'checkbox',
		)
	);

	// Header Search
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
			'section'     => 'aqualuxe_header_options',
			'type'        => 'checkbox',
		)
	);

	// Header Cart
	$wp_customize->add_setting(
		'aqualuxe_header_cart',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_header_cart',
		array(
			'label'       => __( 'Show Cart in Header', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_options',
			'type'        => 'checkbox',
		)
	);

	// Header Account
	$wp_customize->add_setting(
		'aqualuxe_header_account',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_header_account',
		array(
			'label'       => __( 'Show Account in Header', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_options',
			'type'        => 'checkbox',
		)
	);

	// Header Top Bar
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
			'section'     => 'aqualuxe_header_options',
			'type'        => 'checkbox',
		)
	);

	// Top Bar Content
	$wp_customize->add_setting(
		'aqualuxe_top_bar_content',
		array(
			'default'           => __( 'Free shipping on all orders over $50', 'aqualuxe' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_top_bar_content',
		array(
			'label'       => __( 'Top Bar Content', 'aqualuxe' ),
			'section'     => 'aqualuxe_header_options',
			'type'        => 'text',
		)
	);
}

/**
 * Footer Options
 */
function aqualuxe_footer_options( $wp_customize ) {
	// Footer Section
	$wp_customize->add_section(
		'aqualuxe_footer_options',
		array(
			'title'       => __( 'Footer Options', 'aqualuxe' ),
			'description' => __( 'Customize the footer section', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
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
			'label'       => __( 'Footer Widget Layout', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer_options',
			'type'        => 'select',
			'choices'     => array(
				'4-columns'     => __( '4 Columns', 'aqualuxe' ),
				'3-columns'     => __( '3 Columns', 'aqualuxe' ),
				'2-columns'     => __( '2 Columns', 'aqualuxe' ),
				'1-column'      => __( '1 Column', 'aqualuxe' ),
			),
		)
	);

	// Footer Copyright
	$wp_customize->add_setting(
		'aqualuxe_footer_copyright',
		array(
			'default'           => __( '© 2025 AquaLuxe. All rights reserved.', 'aqualuxe' ),
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_footer_copyright',
		array(
			'label'       => __( 'Copyright Text', 'aqualuxe' ),
			'section'     => 'aqualuxe_footer_options',
			'type'        => 'textarea',
		)
	);

	// Payment Icons
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
			'section'     => 'aqualuxe_footer_options',
			'type'        => 'checkbox',
		)
	);
}

/**
 * Color Options
 */
function aqualuxe_color_options( $wp_customize ) {
	// Color Section
	$wp_customize->add_section(
		'aqualuxe_color_options',
		array(
			'title'       => __( 'Color Options', 'aqualuxe' ),
			'description' => __( 'Customize the theme colors', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
		)
	);

	// Primary Color
	$wp_customize->add_setting(
		'aqualuxe_primary_color',
		array(
			'default'           => '#0ea5e9',
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
				'section'     => 'aqualuxe_color_options',
			)
		)
	);

	// Secondary Color
	$wp_customize->add_setting(
		'aqualuxe_secondary_color',
		array(
			'default'           => '#0369a1',
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
				'section'     => 'aqualuxe_color_options',
			)
		)
	);

	// Accent Color
	$wp_customize->add_setting(
		'aqualuxe_accent_color',
		array(
			'default'           => '#10b981',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_accent_color',
			array(
				'label'       => __( 'Accent Color', 'aqualuxe' ),
				'section'     => 'aqualuxe_color_options',
			)
		)
	);

	// Dark Mode
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
			'label'       => __( 'Enable Dark Mode Toggle', 'aqualuxe' ),
			'section'     => 'aqualuxe_color_options',
			'type'        => 'checkbox',
		)
	);

	// Default Color Scheme
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
			'section'     => 'aqualuxe_color_options',
			'type'        => 'select',
			'choices'     => array(
				'light'      => __( 'Light', 'aqualuxe' ),
				'dark'       => __( 'Dark', 'aqualuxe' ),
				'auto'       => __( 'Auto (System Preference)', 'aqualuxe' ),
			),
		)
	);
}

/**
 * Typography Options
 */
function aqualuxe_typography_options( $wp_customize ) {
	// Typography Section
	$wp_customize->add_section(
		'aqualuxe_typography_options',
		array(
			'title'       => __( 'Typography Options', 'aqualuxe' ),
			'description' => __( 'Customize the theme typography', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
		)
	);

	// Heading Font
	$wp_customize->add_setting(
		'aqualuxe_heading_font',
		array(
			'default'           => 'Inter',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_heading_font',
		array(
			'label'       => __( 'Heading Font', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography_options',
			'type'        => 'select',
			'choices'     => array(
				'Inter'       => 'Inter',
				'Montserrat'  => 'Montserrat',
				'Roboto'      => 'Roboto',
				'Open Sans'   => 'Open Sans',
				'Lato'        => 'Lato',
				'Poppins'     => 'Poppins',
				'Playfair Display' => 'Playfair Display',
			),
		)
	);

	// Body Font
	$wp_customize->add_setting(
		'aqualuxe_body_font',
		array(
			'default'           => 'Inter',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_body_font',
		array(
			'label'       => __( 'Body Font', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography_options',
			'type'        => 'select',
			'choices'     => array(
				'Inter'       => 'Inter',
				'Montserrat'  => 'Montserrat',
				'Roboto'      => 'Roboto',
				'Open Sans'   => 'Open Sans',
				'Lato'        => 'Lato',
				'Poppins'     => 'Poppins',
				'Source Sans Pro' => 'Source Sans Pro',
			),
		)
	);

	// Base Font Size
	$wp_customize->add_setting(
		'aqualuxe_base_font_size',
		array(
			'default'           => '16',
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_base_font_size',
		array(
			'label'       => __( 'Base Font Size (px)', 'aqualuxe' ),
			'section'     => 'aqualuxe_typography_options',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 12,
				'max'  => 24,
				'step' => 1,
			),
		)
	);
}

/**
 * Layout Options
 */
function aqualuxe_layout_options( $wp_customize ) {
	// Layout Section
	$wp_customize->add_section(
		'aqualuxe_layout_options',
		array(
			'title'       => __( 'Layout Options', 'aqualuxe' ),
			'description' => __( 'Customize the theme layout', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
		)
	);

	// Container Width
	$wp_customize->add_setting(
		'aqualuxe_container_width',
		array(
			'default'           => '1280',
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_container_width',
		array(
			'label'       => __( 'Container Width (px)', 'aqualuxe' ),
			'section'     => 'aqualuxe_layout_options',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 960,
				'max'  => 1920,
				'step' => 10,
			),
		)
	);

	// Blog Layout
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
			'section'     => 'aqualuxe_layout_options',
			'type'        => 'select',
			'choices'     => array(
				'grid'      => __( 'Grid', 'aqualuxe' ),
				'list'      => __( 'List', 'aqualuxe' ),
				'masonry'   => __( 'Masonry', 'aqualuxe' ),
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
			'label'       => __( 'Sidebar Position', 'aqualuxe' ),
			'section'     => 'aqualuxe_layout_options',
			'type'        => 'select',
			'choices'     => array(
				'right'     => __( 'Right', 'aqualuxe' ),
				'left'      => __( 'Left', 'aqualuxe' ),
				'none'      => __( 'No Sidebar', 'aqualuxe' ),
			),
		)
	);
}

/**
 * WooCommerce Options
 */
function aqualuxe_woocommerce_options( $wp_customize ) {
	// WooCommerce Section
	$wp_customize->add_section(
		'aqualuxe_woocommerce_options',
		array(
			'title'       => __( 'WooCommerce Options', 'aqualuxe' ),
			'description' => __( 'Customize WooCommerce settings', 'aqualuxe' ),
			'panel'       => 'aqualuxe_theme_options',
		)
	);

	// Products Per Row
	$wp_customize->add_setting(
		'aqualuxe_products_per_row',
		array(
			'default'           => '3',
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_products_per_row',
		array(
			'label'       => __( 'Products Per Row', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_options',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 2,
				'max'  => 6,
				'step' => 1,
			),
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
			'label'       => __( 'Products Per Page', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_options',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 4,
				'max'  => 48,
				'step' => 4,
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
			'label'       => __( 'Show Related Products', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_options',
			'type'        => 'checkbox',
		)
	);

	// Product Gallery Zoom
	$wp_customize->add_setting(
		'aqualuxe_product_gallery_zoom',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_product_gallery_zoom',
		array(
			'label'       => __( 'Enable Product Gallery Zoom', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_options',
			'type'        => 'checkbox',
		)
	);

	// Product Gallery Lightbox
	$wp_customize->add_setting(
		'aqualuxe_product_gallery_lightbox',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_product_gallery_lightbox',
		array(
			'label'       => __( 'Enable Product Gallery Lightbox', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_options',
			'type'        => 'checkbox',
		)
	);

	// Product Gallery Slider
	$wp_customize->add_setting(
		'aqualuxe_product_gallery_slider',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_product_gallery_slider',
		array(
			'label'       => __( 'Enable Product Gallery Slider', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_options',
			'type'        => 'checkbox',
		)
	);

	// Quick View
	$wp_customize->add_setting(
		'aqualuxe_product_quick_view',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_product_quick_view',
		array(
			'label'       => __( 'Enable Product Quick View', 'aqualuxe' ),
			'section'     => 'aqualuxe_woocommerce_options',
			'type'        => 'checkbox',
		)
	);
}

/**
 * Sanitize select
 */
function aqualuxe_sanitize_select( $input, $setting ) {
	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;
	
	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize checkbox
 */
function aqualuxe_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
	wp_enqueue_script( 'aqualuxe-customizer', get_template_directory_uri() . '/assets/dist/js/customizer.js', array( 'customize-preview' ), AQUALUXE_VERSION, true );
}
add_action( 'customize_preview_init', 'aqualuxe_customize_preview_js' );

/**
 * Generate CSS from customizer settings
 */
function aqualuxe_generate_customizer_css() {
	$primary_color = get_theme_mod( 'aqualuxe_primary_color', '#0ea5e9' );
	$secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#0369a1' );
	$accent_color = get_theme_mod( 'aqualuxe_accent_color', '#10b981' );
	$base_font_size = get_theme_mod( 'aqualuxe_base_font_size', '16' );
	$heading_font = get_theme_mod( 'aqualuxe_heading_font', 'Inter' );
	$body_font = get_theme_mod( 'aqualuxe_body_font', 'Inter' );
	
	$css = "
		:root {
			--color-primary: {$primary_color};
			--color-primary-dark: {$secondary_color};
			--color-accent: {$accent_color};
			--font-base-size: {$base_font_size}px;
			--font-heading: '{$heading_font}', sans-serif;
			--font-body: '{$body_font}', sans-serif;
		}
	";
	
	return $css;
}

/**
 * Output customizer CSS to wp_head
 */
function aqualuxe_customizer_css() {
	echo '<style type="text/css">' . aqualuxe_generate_customizer_css() . '</style>';
}
add_action( 'wp_head', 'aqualuxe_customizer_css' );