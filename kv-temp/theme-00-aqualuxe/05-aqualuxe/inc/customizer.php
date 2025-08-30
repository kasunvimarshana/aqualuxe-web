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

	// Add AquaLuxe branding section
	$wp_customize->add_section(
		'aqualuxe_branding',
		array(
			'title'    => __( 'AquaLuxe Branding', 'aqualuxe' ),
			'priority' => 30,
		)
	);

	// Add logo setting and control
	$wp_customize->add_setting(
		'aqualuxe_logo',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'aqualuxe_logo',
			array(
				'label'    => __( 'Custom Logo', 'aqualuxe' ),
				'section'  => 'aqualuxe_branding',
				'settings' => 'aqualuxe_logo',
			)
		)
	);

	// Add primary color setting and control
	$wp_customize->add_setting(
		'aqualuxe_primary_color',
		array(
			'default'           => '#0073e6',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_primary_color',
			array(
				'label'    => __( 'Primary Color', 'aqualuxe' ),
				'section'  => 'aqualuxe_branding',
				'settings' => 'aqualuxe_primary_color',
			)
		)
	);

	// Add secondary color setting and control
	$wp_customize->add_setting(
		'aqualuxe_secondary_color',
		array(
			'default'           => '#333333',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_secondary_color',
			array(
				'label'    => __( 'Secondary Color', 'aqualuxe' ),
				'section'  => 'aqualuxe_branding',
				'settings' => 'aqualuxe_secondary_color',
			)
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
 * Output customizer styles
 */
function aqualuxe_customizer_css() {
	$primary_color   = get_theme_mod( 'aqualuxe_primary_color', '#0073e6' );
	$secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#333333' );

	$css = "
		:root {
			--primary-color: {$primary_color};
			--secondary-color: {$secondary_color};
		}
	";

	wp_add_inline_style( 'aqualuxe-style', $css );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_customizer_css' );