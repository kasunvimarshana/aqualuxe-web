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
function aqualuxe_customize_register($wp_customize)
{
	$wp_customize->get_setting('blogname')->transport         = 'postMessage';
	$wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
	$wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

	if (isset($wp_customize->selective_refresh)) {
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

	// Add customizer options
	$wp_customize->add_section(
		'aqualuxe_theme_options',
		array(
			'title'    => __('AquaLuxe Options', 'aqualuxe'),
			'priority' => 30,
		)
	);

	// Add color scheme setting
	$wp_customize->add_setting(
		'aqualuxe_color_scheme',
		array(
			'default'           => 'blue',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_color_scheme',
		array(
			'label'    => __('Color Scheme', 'aqualuxe'),
			'section'  => 'aqualuxe_theme_options',
			'type'     => 'select',
			'choices'  => array(
				'blue'   => __('Ocean Blue', 'aqualuxe'),
				'green'  => __('Aquamarine', 'aqualuxe'),
				'purple' => __('Coral Purple', 'aqualuxe'),
			),
		)
	);

	// Add header layout setting
	$wp_customize->add_setting(
		'aqualuxe_header_layout',
		array(
			'default'           => 'standard',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_header_layout',
		array(
			'label'    => __('Header Layout', 'aqualuxe'),
			'section'  => 'aqualuxe_theme_options',
			'type'     => 'select',
			'choices'  => array(
				'standard' => __('Standard', 'aqualuxe'),
				'sticky'   => __('Sticky', 'aqualuxe'),
				'minimal'  => __('Minimal', 'aqualuxe'),
			),
		)
	);
}
add_action('customize_register', 'aqualuxe_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogname()
{
	bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogdescription()
{
	bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js()
{
	wp_enqueue_script('aqualuxe-customizer', get_stylesheet_directory_uri() . '/assets/js/customizer.js', array('customize-preview'), AQUALUXE_VERSION, true);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');