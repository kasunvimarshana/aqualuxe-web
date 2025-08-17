<?php

/**
 * Theme Customizer registration
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Register theme customizer options.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register($wp_customize)
{
	// Add custom controls.
	require_once AQUALUXE_THEME_DIR . 'inc/customizer/controls/class-aqualuxe-toggle-control.php';
	require_once AQUALUXE_THEME_DIR . 'inc/customizer/controls/class-aqualuxe-range-control.php';
	require_once AQUALUXE_THEME_DIR . 'inc/customizer/controls/class-aqualuxe-color-alpha-control.php';
	require_once AQUALUXE_THEME_DIR . 'inc/customizer/controls/class-aqualuxe-separator-control.php';
	require_once AQUALUXE_THEME_DIR . 'inc/customizer/controls/class-aqualuxe-heading-control.php';
	require_once AQUALUXE_THEME_DIR . 'inc/customizer/controls/class-aqualuxe-radio-image-control.php';
	require_once AQUALUXE_THEME_DIR . 'inc/customizer/controls/class-aqualuxe-color-alpha-control.php';

	// Register custom control types.
	$wp_customize->register_control_type('AquaLuxe_Toggle_Control');
	$wp_customize->register_control_type('AquaLuxe_Range_Control');
	$wp_customize->register_control_type('AquaLuxe_Color_Alpha_Control');
	$wp_customize->register_control_type('AquaLuxe_Separator_Control');
	$wp_customize->register_control_type('AquaLuxe_Heading_Control');

	// Add panels.
	$wp_customize->add_panel(
		'aqualuxe_theme_options',
		array(
			'title'       => esc_html__('AquaLuxe Theme Options', 'aqualuxe'),
			'description' => esc_html__('Customize the appearance and behavior of your AquaLuxe theme.', 'aqualuxe'),
			'priority'    => 130,
		)
	);

	// Add sections.
	aqualuxe_customize_register_general($wp_customize);
	aqualuxe_customize_register_header($wp_customize);
	aqualuxe_customize_register_footer($wp_customize);
	aqualuxe_customize_register_typography($wp_customize);
	aqualuxe_customize_register_colors($wp_customize);
	aqualuxe_customize_register_blog($wp_customize);
	aqualuxe_customize_register_social($wp_customize);
	aqualuxe_customize_register_contact($wp_customize);

	// Add WooCommerce section if active.
	if (class_exists('WooCommerce')) {
		aqualuxe_customize_register_woocommerce($wp_customize);
	}

	// Add performance section.
	aqualuxe_customize_register_performance($wp_customize);
}
add_action('customize_register', 'aqualuxe_customize_register');

/**
 * Register general settings.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_general($wp_customize)
{
	$wp_customize->add_section(
		'aqualuxe_general',
		array(
			'title'    => esc_html__('General Settings', 'aqualuxe'),
			'priority' => 10,
			'panel'    => 'aqualuxe_theme_options',
		)
	);

	// Container width.
	$wp_customize->add_setting(
		'aqualuxe_container_width',
		array(
			'default'           => '1200',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Range_Control(
			$wp_customize,
			'aqualuxe_container_width',
			array(
				'label'       => esc_html__('Container Width (px)', 'aqualuxe'),
				'section'     => 'aqualuxe_general',
				'input_attrs' => array(
					'min'  => 960,
					'max'  => 1600,
					'step' => 10,
				),
			)
		)
	);

	// Enable dark mode toggle.
	$wp_customize->add_setting(
		'aqualuxe_enable_dark_mode',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_enable_dark_mode',
			array(
				'label'   => esc_html__('Enable Dark Mode Toggle', 'aqualuxe'),
				'section' => 'aqualuxe_general',
			)
		)
	);

	// Default color scheme.
	$wp_customize->add_setting(
		'aqualuxe_default_color_scheme',
		array(
			'default'           => 'light',
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_default_color_scheme',
		array(
			'label'           => esc_html__('Default Color Scheme', 'aqualuxe'),
			'section'         => 'aqualuxe_general',
			'type'            => 'select',
			'choices'         => array(
				'light' => esc_html__('Light', 'aqualuxe'),
				'dark'  => esc_html__('Dark', 'aqualuxe'),
				'auto'  => esc_html__('Auto (System Preference)', 'aqualuxe'),
			),
			'active_callback' => function () {
				return get_theme_mod('aqualuxe_enable_dark_mode', true);
			},
		)
	);

	// Breadcrumbs.
	$wp_customize->add_setting(
		'aqualuxe_enable_breadcrumbs',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_enable_breadcrumbs',
			array(
				'label'   => esc_html__('Enable Breadcrumbs', 'aqualuxe'),
				'section' => 'aqualuxe_general',
			)
		)
	);

	// Back to top button.
	$wp_customize->add_setting(
		'aqualuxe_enable_back_to_top',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_enable_back_to_top',
			array(
				'label'   => esc_html__('Enable Back to Top Button', 'aqualuxe'),
				'section' => 'aqualuxe_general',
			)
		)
	);
}

/**
 * Register header settings.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_header($wp_customize)
{
	$wp_customize->add_section(
		'aqualuxe_header',
		array(
			'title'    => esc_html__('Header Settings', 'aqualuxe'),
			'priority' => 20,
			'panel'    => 'aqualuxe_theme_options',
		)
	);

	// Header layout.
	$wp_customize->add_setting(
		'aqualuxe_header_layout',
		array(
			'default'           => 'default',
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_header_layout',
		array(
			'label'   => esc_html__('Header Layout', 'aqualuxe'),
			'section' => 'aqualuxe_header',
			'type'    => 'select',
			'choices' => array(
				'default'      => esc_html__('Default', 'aqualuxe'),
				'centered'     => esc_html__('Centered', 'aqualuxe'),
				'transparent'  => esc_html__('Transparent', 'aqualuxe'),
				'minimal'      => esc_html__('Minimal', 'aqualuxe'),
			),
		)
	);

	// Sticky header.
	$wp_customize->add_setting(
		'aqualuxe_sticky_header',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_sticky_header',
			array(
				'label'   => esc_html__('Enable Sticky Header', 'aqualuxe'),
				'section' => 'aqualuxe_header',
			)
		)
	);

	// Show search in header.
	$wp_customize->add_setting(
		'aqualuxe_header_search',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_header_search',
			array(
				'label'   => esc_html__('Show Search in Header', 'aqualuxe'),
				'section' => 'aqualuxe_header',
			)
		)
	);

	// Show cart in header.
	$wp_customize->add_setting(
		'aqualuxe_header_cart',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_header_cart',
			array(
				'label'           => esc_html__('Show Cart in Header', 'aqualuxe'),
				'section'         => 'aqualuxe_header',
				'active_callback' => function () {
					return class_exists('WooCommerce');
				},
			)
		)
	);

	// Show account in header.
	$wp_customize->add_setting(
		'aqualuxe_header_account',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_header_account',
			array(
				'label'           => esc_html__('Show Account in Header', 'aqualuxe'),
				'section'         => 'aqualuxe_header',
				'active_callback' => function () {
					return class_exists('WooCommerce');
				},
			)
		)
	);

	// Show wishlist in header.
	$wp_customize->add_setting(
		'aqualuxe_header_wishlist',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_header_wishlist',
			array(
				'label'           => esc_html__('Show Wishlist in Header', 'aqualuxe'),
				'section'         => 'aqualuxe_header',
				'active_callback' => function () {
					return class_exists('WooCommerce');
				},
			)
		)
	);
}

/**
 * Register footer settings.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_footer($wp_customize)
{
	$wp_customize->add_section(
		'aqualuxe_footer',
		array(
			'title'    => esc_html__('Footer Settings', 'aqualuxe'),
			'priority' => 30,
			'panel'    => 'aqualuxe_theme_options',
		)
	);

	// Footer layout.
	$wp_customize->add_setting(
		'aqualuxe_footer_layout',
		array(
			'default'           => '4-columns',
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_footer_layout',
		array(
			'label'   => esc_html__('Footer Layout', 'aqualuxe'),
			'section' => 'aqualuxe_footer',
			'type'    => 'select',
			'choices' => array(
				'4-columns' => esc_html__('4 Columns', 'aqualuxe'),
				'3-columns' => esc_html__('3 Columns', 'aqualuxe'),
				'2-columns' => esc_html__('2 Columns', 'aqualuxe'),
				'1-column'  => esc_html__('1 Column', 'aqualuxe'),
			),
		)
	);

	// Footer text.
	$wp_customize->add_setting(
		'aqualuxe_footer_text',
		array(
			'default'           => sprintf(
				/* translators: %1$s: Current year, %2$s: Site name */
				esc_html__('© %1$s %2$s. All rights reserved.', 'aqualuxe'),
				date_i18n('Y'),
				get_bloginfo('name')
			),
			'transport'         => 'refresh',
			'sanitize_callback' => 'wp_kses_post',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_footer_text',
		array(
			'label'   => esc_html__('Footer Text', 'aqualuxe'),
			'section' => 'aqualuxe_footer',
			'type'    => 'textarea',
		)
	);

	// Show social icons in footer.
	$wp_customize->add_setting(
		'aqualuxe_footer_social',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_footer_social',
			array(
				'label'   => esc_html__('Show Social Icons in Footer', 'aqualuxe'),
				'section' => 'aqualuxe_footer',
			)
		)
	);

	// Show payment icons in footer.
	$wp_customize->add_setting(
		'aqualuxe_footer_payment',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_footer_payment',
			array(
				'label'           => esc_html__('Show Payment Icons in Footer', 'aqualuxe'),
				'section'         => 'aqualuxe_footer',
				'active_callback' => function () {
					return class_exists('WooCommerce');
				},
			)
		)
	);
}

/**
 * Register typography settings.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_typography($wp_customize)
{
	$wp_customize->add_section(
		'aqualuxe_typography',
		array(
			'title'    => esc_html__('Typography', 'aqualuxe'),
			'priority' => 40,
			'panel'    => 'aqualuxe_theme_options',
		)
	);

	// Body font family.
	$wp_customize->add_setting(
		'aqualuxe_body_font_family',
		array(
			'default'           => 'Inter, sans-serif',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_body_font_family',
		array(
			'label'   => esc_html__('Body Font Family', 'aqualuxe'),
			'section' => 'aqualuxe_typography',
			'type'    => 'select',
			'choices' => array(
				'Inter, sans-serif'                => esc_html__('Inter (Default)', 'aqualuxe'),
				'Roboto, sans-serif'               => esc_html__('Roboto', 'aqualuxe'),
				'Open Sans, sans-serif'            => esc_html__('Open Sans', 'aqualuxe'),
				'Lato, sans-serif'                 => esc_html__('Lato', 'aqualuxe'),
				'Montserrat, sans-serif'           => esc_html__('Montserrat', 'aqualuxe'),
				'Poppins, sans-serif'              => esc_html__('Poppins', 'aqualuxe'),
				'Nunito, sans-serif'               => esc_html__('Nunito', 'aqualuxe'),
				'Raleway, sans-serif'              => esc_html__('Raleway', 'aqualuxe'),
				'PT Serif, serif'                  => esc_html__('PT Serif', 'aqualuxe'),
				'Merriweather, serif'              => esc_html__('Merriweather', 'aqualuxe'),
				'Playfair Display, serif'          => esc_html__('Playfair Display', 'aqualuxe'),
				'system-ui, sans-serif'            => esc_html__('System UI', 'aqualuxe'),
			),
		)
	);

	// Heading font family.
	$wp_customize->add_setting(
		'aqualuxe_heading_font_family',
		array(
			'default'           => 'Playfair Display, serif',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_heading_font_family',
		array(
			'label'   => esc_html__('Heading Font Family', 'aqualuxe'),
			'section' => 'aqualuxe_typography',
			'type'    => 'select',
			'choices' => array(
				'Playfair Display, serif'          => esc_html__('Playfair Display (Default)', 'aqualuxe'),
				'Inter, sans-serif'                => esc_html__('Inter', 'aqualuxe'),
				'Roboto, sans-serif'               => esc_html__('Roboto', 'aqualuxe'),
				'Open Sans, sans-serif'            => esc_html__('Open Sans', 'aqualuxe'),
				'Lato, sans-serif'                 => esc_html__('Lato', 'aqualuxe'),
				'Montserrat, sans-serif'           => esc_html__('Montserrat', 'aqualuxe'),
				'Poppins, sans-serif'              => esc_html__('Poppins', 'aqualuxe'),
				'Nunito, sans-serif'               => esc_html__('Nunito', 'aqualuxe'),
				'Raleway, sans-serif'              => esc_html__('Raleway', 'aqualuxe'),
				'PT Serif, serif'                  => esc_html__('PT Serif', 'aqualuxe'),
				'Merriweather, serif'              => esc_html__('Merriweather', 'aqualuxe'),
				'system-ui, sans-serif'            => esc_html__('System UI', 'aqualuxe'),
			),
		)
	);

	// Base font size.
	$wp_customize->add_setting(
		'aqualuxe_base_font_size',
		array(
			'default'           => '16',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Range_Control(
			$wp_customize,
			'aqualuxe_base_font_size',
			array(
				'label'       => esc_html__('Base Font Size (px)', 'aqualuxe'),
				'section'     => 'aqualuxe_typography',
				'input_attrs' => array(
					'min'  => 12,
					'max'  => 24,
					'step' => 1,
				),
			)
		)
	);

	// Line height.
	$wp_customize->add_setting(
		'aqualuxe_line_height',
		array(
			'default'           => '1.6',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'aqualuxe_sanitize_float',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Range_Control(
			$wp_customize,
			'aqualuxe_line_height',
			array(
				'label'       => esc_html__('Line Height', 'aqualuxe'),
				'section'     => 'aqualuxe_typography',
				'input_attrs' => array(
					'min'  => 1,
					'max'  => 2,
					'step' => 0.1,
				),
			)
		)
	);
}

/**
 * Register colors settings.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_colors($wp_customize)
{
	$wp_customize->add_section(
		'aqualuxe_colors',
		array(
			'title'    => esc_html__('Colors', 'aqualuxe'),
			'priority' => 50,
			'panel'    => 'aqualuxe_theme_options',
		)
	);

	// Light mode colors.
	$wp_customize->add_setting(
		'aqualuxe_light_mode_heading',
		array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Heading_Control(
			$wp_customize,
			'aqualuxe_light_mode_heading',
			array(
				'label'   => esc_html__('Light Mode Colors', 'aqualuxe'),
				'section' => 'aqualuxe_colors',
			)
		)
	);

	// Primary color.
	$wp_customize->add_setting(
		'aqualuxe_primary_color',
		array(
			'default'           => '#0891b2',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_primary_color',
			array(
				'label'   => esc_html__('Primary Color', 'aqualuxe'),
				'section' => 'aqualuxe_colors',
			)
		)
	);

	// Secondary color.
	$wp_customize->add_setting(
		'aqualuxe_secondary_color',
		array(
			'default'           => '#7e22ce',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_secondary_color',
			array(
				'label'   => esc_html__('Secondary Color', 'aqualuxe'),
				'section' => 'aqualuxe_colors',
			)
		)
	);

	// Accent color.
	$wp_customize->add_setting(
		'aqualuxe_accent_color',
		array(
			'default'           => '#f59e0b',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_accent_color',
			array(
				'label'   => esc_html__('Accent Color', 'aqualuxe'),
				'section' => 'aqualuxe_colors',
			)
		)
	);

	// Background color.
	$wp_customize->add_setting(
		'aqualuxe_background_color',
		array(
			'default'           => '#ffffff',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_background_color',
			array(
				'label'   => esc_html__('Background Color', 'aqualuxe'),
				'section' => 'aqualuxe_colors',
			)
		)
	);

	// Text color.
	$wp_customize->add_setting(
		'aqualuxe_text_color',
		array(
			'default'           => '#0f172a',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_text_color',
			array(
				'label'   => esc_html__('Text Color', 'aqualuxe'),
				'section' => 'aqualuxe_colors',
			)
		)
	);

	// Dark mode colors.
	$wp_customize->add_setting(
		'aqualuxe_dark_mode_heading',
		array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Heading_Control(
			$wp_customize,
			'aqualuxe_dark_mode_heading',
			array(
				'label'   => esc_html__('Dark Mode Colors', 'aqualuxe'),
				'section' => 'aqualuxe_colors',
			)
		)
	);

	// Dark mode primary color.
	$wp_customize->add_setting(
		'aqualuxe_dark_primary_color',
		array(
			'default'           => '#06b6d4',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_dark_primary_color',
			array(
				'label'   => esc_html__('Primary Color', 'aqualuxe'),
				'section' => 'aqualuxe_colors',
			)
		)
	);

	// Dark mode secondary color.
	$wp_customize->add_setting(
		'aqualuxe_dark_secondary_color',
		array(
			'default'           => '#a855f7',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_dark_secondary_color',
			array(
				'label'   => esc_html__('Secondary Color', 'aqualuxe'),
				'section' => 'aqualuxe_colors',
			)
		)
	);

	// Dark mode accent color.
	$wp_customize->add_setting(
		'aqualuxe_dark_accent_color',
		array(
			'default'           => '#fbbf24',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_dark_accent_color',
			array(
				'label'   => esc_html__('Accent Color', 'aqualuxe'),
				'section' => 'aqualuxe_colors',
			)
		)
	);

	// Dark mode background color.
	$wp_customize->add_setting(
		'aqualuxe_dark_background_color',
		array(
			'default'           => '#0f172a',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_dark_background_color',
			array(
				'label'   => esc_html__('Background Color', 'aqualuxe'),
				'section' => 'aqualuxe_colors',
			)
		)
	);

	// Dark mode text color.
	$wp_customize->add_setting(
		'aqualuxe_dark_text_color',
		array(
			'default'           => '#f8fafc',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'aqualuxe_dark_text_color',
			array(
				'label'   => esc_html__('Text Color', 'aqualuxe'),
				'section' => 'aqualuxe_colors',
			)
		)
	);
}

/**
 * Register blog settings.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_blog($wp_customize)
{
	$wp_customize->add_section(
		'aqualuxe_blog',
		array(
			'title'    => esc_html__('Blog Settings', 'aqualuxe'),
			'priority' => 60,
			'panel'    => 'aqualuxe_theme_options',
		)
	);

	// Blog layout.
	$wp_customize->add_setting(
		'aqualuxe_blog_layout',
		array(
			'default'           => 'grid',
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_blog_layout',
		array(
			'label'   => esc_html__('Blog Layout', 'aqualuxe'),
			'section' => 'aqualuxe_blog',
			'type'    => 'select',
			'choices' => array(
				'grid'    => esc_html__('Grid', 'aqualuxe'),
				'list'    => esc_html__('List', 'aqualuxe'),
				'masonry' => esc_html__('Masonry', 'aqualuxe'),
				'classic' => esc_html__('Classic', 'aqualuxe'),
			),
		)
	);

	// Blog sidebar.
	$wp_customize->add_setting(
		'aqualuxe_blog_sidebar',
		array(
			'default'           => 'right',
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_blog_sidebar',
		array(
			'label'   => esc_html__('Blog Sidebar', 'aqualuxe'),
			'section' => 'aqualuxe_blog',
			'type'    => 'select',
			'choices' => array(
				'right' => esc_html__('Right Sidebar', 'aqualuxe'),
				'left'  => esc_html__('Left Sidebar', 'aqualuxe'),
				'none'  => esc_html__('No Sidebar', 'aqualuxe'),
			),
		)
	);

	// Posts per page.
	$wp_customize->add_setting(
		'aqualuxe_posts_per_page',
		array(
			'default'           => '9',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_posts_per_page',
		array(
			'label'   => esc_html__('Posts Per Page', 'aqualuxe'),
			'section' => 'aqualuxe_blog',
			'type'    => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 50,
				'step' => 1,
			),
		)
	);

	// Show post date.
	$wp_customize->add_setting(
		'aqualuxe_show_post_date',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_show_post_date',
			array(
				'label'   => esc_html__('Show Post Date', 'aqualuxe'),
				'section' => 'aqualuxe_blog',
			)
		)
	);

	// Show post author.
	$wp_customize->add_setting(
		'aqualuxe_show_post_author',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_show_post_author',
			array(
				'label'   => esc_html__('Show Post Author', 'aqualuxe'),
				'section' => 'aqualuxe_blog',
			)
		)
	);

	// Show post categories.
	$wp_customize->add_setting(
		'aqualuxe_show_post_categories',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_show_post_categories',
			array(
				'label'   => esc_html__('Show Post Categories', 'aqualuxe'),
				'section' => 'aqualuxe_blog',
			)
		)
	);

	// Show post tags.
	$wp_customize->add_setting(
		'aqualuxe_show_post_tags',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_show_post_tags',
			array(
				'label'   => esc_html__('Show Post Tags', 'aqualuxe'),
				'section' => 'aqualuxe_blog',
			)
		)
	);

	// Show post comments count.
	$wp_customize->add_setting(
		'aqualuxe_show_post_comments',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_show_post_comments',
			array(
				'label'   => esc_html__('Show Post Comments Count', 'aqualuxe'),
				'section' => 'aqualuxe_blog',
			)
		)
	);

	// Show related posts.
	$wp_customize->add_setting(
		'aqualuxe_show_related_posts',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_show_related_posts',
			array(
				'label'   => esc_html__('Show Related Posts', 'aqualuxe'),
				'section' => 'aqualuxe_blog',
			)
		)
	);

	// Related posts count.
	$wp_customize->add_setting(
		'aqualuxe_related_posts_count',
		array(
			'default'           => '3',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_related_posts_count',
		array(
			'label'           => esc_html__('Related Posts Count', 'aqualuxe'),
			'section'         => 'aqualuxe_blog',
			'type'            => 'number',
			'input_attrs'     => array(
				'min'  => 1,
				'max'  => 12,
				'step' => 1,
			),
			'active_callback' => function () {
				return get_theme_mod('aqualuxe_show_related_posts', true);
			},
		)
	);
}

/**
 * Register social settings.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_social($wp_customize)
{
	$wp_customize->add_section(
		'aqualuxe_social',
		array(
			'title'    => esc_html__('Social Media', 'aqualuxe'),
			'priority' => 70,
			'panel'    => 'aqualuxe_theme_options',
		)
	);

	// Facebook URL.
	$wp_customize->add_setting(
		'aqualuxe_facebook_url',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_facebook_url',
		array(
			'label'   => esc_html__('Facebook URL', 'aqualuxe'),
			'section' => 'aqualuxe_social',
			'type'    => 'url',
		)
	);

	// Twitter URL.
	$wp_customize->add_setting(
		'aqualuxe_twitter_url',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_twitter_url',
		array(
			'label'   => esc_html__('Twitter URL', 'aqualuxe'),
			'section' => 'aqualuxe_social',
			'type'    => 'url',
		)
	);

	// Instagram URL.
	$wp_customize->add_setting(
		'aqualuxe_instagram_url',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_instagram_url',
		array(
			'label'   => esc_html__('Instagram URL', 'aqualuxe'),
			'section' => 'aqualuxe_social',
			'type'    => 'url',
		)
	);

	// LinkedIn URL.
	$wp_customize->add_setting(
		'aqualuxe_linkedin_url',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_linkedin_url',
		array(
			'label'   => esc_html__('LinkedIn URL', 'aqualuxe'),
			'section' => 'aqualuxe_social',
			'type'    => 'url',
		)
	);

	// YouTube URL.
	$wp_customize->add_setting(
		'aqualuxe_youtube_url',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_youtube_url',
		array(
			'label'   => esc_html__('YouTube URL', 'aqualuxe'),
			'section' => 'aqualuxe_social',
			'type'    => 'url',
		)
	);

	// Pinterest URL.
	$wp_customize->add_setting(
		'aqualuxe_pinterest_url',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_pinterest_url',
		array(
			'label'   => esc_html__('Pinterest URL', 'aqualuxe'),
			'section' => 'aqualuxe_social',
			'type'    => 'url',
		)
	);
}

/**
 * Register contact settings.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_contact($wp_customize)
{
	$wp_customize->add_section(
		'aqualuxe_contact',
		array(
			'title'    => esc_html__('Contact Information', 'aqualuxe'),
			'priority' => 80,
			'panel'    => 'aqualuxe_theme_options',
		)
	);

	// Phone number.
	$wp_customize->add_setting(
		'aqualuxe_phone',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_phone',
		array(
			'label'   => esc_html__('Phone Number', 'aqualuxe'),
			'section' => 'aqualuxe_contact',
			'type'    => 'text',
		)
	);

	// Email address.
	$wp_customize->add_setting(
		'aqualuxe_email',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_email',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_email',
		array(
			'label'   => esc_html__('Email Address', 'aqualuxe'),
			'section' => 'aqualuxe_contact',
			'type'    => 'email',
		)
	);

	// Address street.
	$wp_customize->add_setting(
		'aqualuxe_address_street',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_address_street',
		array(
			'label'   => esc_html__('Street Address', 'aqualuxe'),
			'section' => 'aqualuxe_contact',
			'type'    => 'text',
		)
	);

	// Address city.
	$wp_customize->add_setting(
		'aqualuxe_address_city',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_address_city',
		array(
			'label'   => esc_html__('City', 'aqualuxe'),
			'section' => 'aqualuxe_contact',
			'type'    => 'text',
		)
	);

	// Address state.
	$wp_customize->add_setting(
		'aqualuxe_address_state',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_address_state',
		array(
			'label'   => esc_html__('State/Province', 'aqualuxe'),
			'section' => 'aqualuxe_contact',
			'type'    => 'text',
		)
	);

	// Address zip.
	$wp_customize->add_setting(
		'aqualuxe_address_zip',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_address_zip',
		array(
			'label'   => esc_html__('ZIP/Postal Code', 'aqualuxe'),
			'section' => 'aqualuxe_contact',
			'type'    => 'text',
		)
	);

	// Address country.
	$wp_customize->add_setting(
		'aqualuxe_address_country',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_address_country',
		array(
			'label'   => esc_html__('Country', 'aqualuxe'),
			'section' => 'aqualuxe_contact',
			'type'    => 'text',
		)
	);

	// Google Maps API key.
	$wp_customize->add_setting(
		'aqualuxe_google_maps_api_key',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_google_maps_api_key',
		array(
			'label'       => esc_html__('Google Maps API Key', 'aqualuxe'),
			'description' => esc_html__('Required for displaying maps on the contact page.', 'aqualuxe'),
			'section'     => 'aqualuxe_contact',
			'type'        => 'text',
		)
	);
}

/**
 * Register WooCommerce settings.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_woocommerce($wp_customize)
{
	$wp_customize->add_section(
		'aqualuxe_woocommerce',
		array(
			'title'    => esc_html__('WooCommerce', 'aqualuxe'),
			'priority' => 90,
			'panel'    => 'aqualuxe_theme_options',
		)
	);

	// Shop layout.
	$wp_customize->add_setting(
		'aqualuxe_shop_layout',
		array(
			'default'           => 'grid',
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_shop_layout',
		array(
			'label'   => esc_html__('Shop Layout', 'aqualuxe'),
			'section' => 'aqualuxe_woocommerce',
			'type'    => 'select',
			'choices' => array(
				'grid'    => esc_html__('Grid', 'aqualuxe'),
				'list'    => esc_html__('List', 'aqualuxe'),
				'masonry' => esc_html__('Masonry', 'aqualuxe'),
			),
		)
	);

	// Shop sidebar.
	$wp_customize->add_setting(
		'aqualuxe_shop_sidebar',
		array(
			'default'           => 'right',
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_shop_sidebar',
		array(
			'label'   => esc_html__('Shop Sidebar', 'aqualuxe'),
			'section' => 'aqualuxe_woocommerce',
			'type'    => 'select',
			'choices' => array(
				'right' => esc_html__('Right Sidebar', 'aqualuxe'),
				'left'  => esc_html__('Left Sidebar', 'aqualuxe'),
				'none'  => esc_html__('No Sidebar', 'aqualuxe'),
			),
		)
	);

	// Products per page.
	$wp_customize->add_setting(
		'aqualuxe_products_per_page',
		array(
			'default'           => '12',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_products_per_page',
		array(
			'label'   => esc_html__('Products Per Page', 'aqualuxe'),
			'section' => 'aqualuxe_woocommerce',
			'type'    => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 100,
				'step' => 1,
			),
		)
	);

	// Products per row.
	$wp_customize->add_setting(
		'aqualuxe_products_per_row',
		array(
			'default'           => '4',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_products_per_row',
		array(
			'label'   => esc_html__('Products Per Row', 'aqualuxe'),
			'section' => 'aqualuxe_woocommerce',
			'type'    => 'select',
			'choices' => array(
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6',
			),
		)
	);

	// Product gallery zoom.
	$wp_customize->add_setting(
		'aqualuxe_product_gallery_zoom',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_product_gallery_zoom',
			array(
				'label'   => esc_html__('Product Gallery Zoom', 'aqualuxe'),
				'section' => 'aqualuxe_woocommerce',
			)
		)
	);

	// Product gallery lightbox.
	$wp_customize->add_setting(
		'aqualuxe_product_gallery_lightbox',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_product_gallery_lightbox',
			array(
				'label'   => esc_html__('Product Gallery Lightbox', 'aqualuxe'),
				'section' => 'aqualuxe_woocommerce',
			)
		)
	);

	// Product gallery slider.
	$wp_customize->add_setting(
		'aqualuxe_product_gallery_slider',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_product_gallery_slider',
			array(
				'label'   => esc_html__('Product Gallery Slider', 'aqualuxe'),
				'section' => 'aqualuxe_woocommerce',
			)
		)
	);

	// Quick view.
	$wp_customize->add_setting(
		'aqualuxe_quick_view',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_quick_view',
			array(
				'label'   => esc_html__('Enable Quick View', 'aqualuxe'),
				'section' => 'aqualuxe_woocommerce',
			)
		)
	);

	// Wishlist.
	$wp_customize->add_setting(
		'aqualuxe_wishlist',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_wishlist',
			array(
				'label'   => esc_html__('Enable Wishlist', 'aqualuxe'),
				'section' => 'aqualuxe_woocommerce',
			)
		)
	);

	// Related products.
	$wp_customize->add_setting(
		'aqualuxe_related_products',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_related_products',
			array(
				'label'   => esc_html__('Show Related Products', 'aqualuxe'),
				'section' => 'aqualuxe_woocommerce',
			)
		)
	);

	// Related products count.
	$wp_customize->add_setting(
		'aqualuxe_related_products_count',
		array(
			'default'           => '4',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_related_products_count',
		array(
			'label'           => esc_html__('Related Products Count', 'aqualuxe'),
			'section'         => 'aqualuxe_woocommerce',
			'type'            => 'number',
			'input_attrs'     => array(
				'min'  => 1,
				'max'  => 12,
				'step' => 1,
			),
			'active_callback' => function () {
				return get_theme_mod('aqualuxe_related_products', true);
			},
		)
	);

	// Upsell products.
	$wp_customize->add_setting(
		'aqualuxe_upsell_products',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_upsell_products',
			array(
				'label'   => esc_html__('Show Upsell Products', 'aqualuxe'),
				'section' => 'aqualuxe_woocommerce',
			)
		)
	);

	// Cross-sell products.
	$wp_customize->add_setting(
		'aqualuxe_cross_sell_products',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_cross_sell_products',
			array(
				'label'   => esc_html__('Show Cross-Sell Products', 'aqualuxe'),
				'section' => 'aqualuxe_woocommerce',
			)
		)
	);

	// Product tabs.
	$wp_customize->add_setting(
		'aqualuxe_product_tabs',
		array(
			'default'           => 'default',
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_product_tabs',
		array(
			'label'   => esc_html__('Product Tabs Style', 'aqualuxe'),
			'section' => 'aqualuxe_woocommerce',
			'type'    => 'select',
			'choices' => array(
				'default'  => esc_html__('Default', 'aqualuxe'),
				'vertical' => esc_html__('Vertical', 'aqualuxe'),
				'accordion' => esc_html__('Accordion', 'aqualuxe'),
			),
		)
	);

	// Fallback settings.
	$wp_customize->add_setting(
		'aqualuxe_woo_fallback_heading',
		array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Heading_Control(
			$wp_customize,
			'aqualuxe_woo_fallback_heading',
			array(
				'label'   => esc_html__('WooCommerce Fallback Settings', 'aqualuxe'),
				'section' => 'aqualuxe_woocommerce',
			)
		)
	);

	// Fallback shop page.
	$wp_customize->add_setting(
		'aqualuxe_fallback_shop_page',
		array(
			'default'           => '0',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_fallback_shop_page',
		array(
			'label'       => esc_html__('Fallback Shop Page', 'aqualuxe'),
			'description' => esc_html__('Select a page to display when WooCommerce is not active and a user tries to access the shop.', 'aqualuxe'),
			'section'     => 'aqualuxe_woocommerce',
			'type'        => 'dropdown-pages',
		)
	);

	// Fallback message.
	$wp_customize->add_setting(
		'aqualuxe_fallback_message',
		array(
			'default'           => esc_html__('Our shop is currently being updated. Please check back soon.', 'aqualuxe'),
			'transport'         => 'refresh',
			'sanitize_callback' => 'wp_kses_post',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_fallback_message',
		array(
			'label'   => esc_html__('Fallback Message', 'aqualuxe'),
			'section' => 'aqualuxe_woocommerce',
			'type'    => 'textarea',
		)
	);
}

/**
 * Register performance settings.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_performance($wp_customize)
{
	$wp_customize->add_section(
		'aqualuxe_performance',
		array(
			'title'    => esc_html__('Performance', 'aqualuxe'),
			'priority' => 100,
			'panel'    => 'aqualuxe_theme_options',
		)
	);

	// Disable emojis.
	$wp_customize->add_setting(
		'aqualuxe_disable_emojis',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_disable_emojis',
			array(
				'label'   => esc_html__('Disable WordPress Emojis', 'aqualuxe'),
				'section' => 'aqualuxe_performance',
			)
		)
	);

	// Disable embeds.
	$wp_customize->add_setting(
		'aqualuxe_disable_embeds',
		array(
			'default'           => false,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_disable_embeds',
			array(
				'label'   => esc_html__('Disable WordPress Embeds', 'aqualuxe'),
				'section' => 'aqualuxe_performance',
			)
		)
	);

	// Disable XML-RPC.
	$wp_customize->add_setting(
		'aqualuxe_disable_xmlrpc',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_disable_xmlrpc',
			array(
				'label'   => esc_html__('Disable XML-RPC', 'aqualuxe'),
				'section' => 'aqualuxe_performance',
			)
		)
	);

	// Disable REST API for unauthenticated users.
	$wp_customize->add_setting(
		'aqualuxe_disable_rest_api',
		array(
			'default'           => false,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_disable_rest_api',
			array(
				'label'       => esc_html__('Disable REST API for Unauthenticated Users', 'aqualuxe'),
				'description' => esc_html__('Warning: This may break some plugins or Gutenberg.', 'aqualuxe'),
				'section'     => 'aqualuxe_performance',
			)
		)
	);

	// Lazy load images.
	$wp_customize->add_setting(
		'aqualuxe_lazy_load_images',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_lazy_load_images',
			array(
				'label'   => esc_html__('Lazy Load Images', 'aqualuxe'),
				'section' => 'aqualuxe_performance',
			)
		)
	);

	// Preload critical CSS.
	$wp_customize->add_setting(
		'aqualuxe_preload_critical_css',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_preload_critical_css',
			array(
				'label'   => esc_html__('Preload Critical CSS', 'aqualuxe'),
				'section' => 'aqualuxe_performance',
			)
		)
	);

	// Defer non-critical JavaScript.
	$wp_customize->add_setting(
		'aqualuxe_defer_js',
		array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new AquaLuxe_Toggle_Control(
			$wp_customize,
			'aqualuxe_defer_js',
			array(
				'label'   => esc_html__('Defer Non-Critical JavaScript', 'aqualuxe'),
				'section' => 'aqualuxe_performance',
			)
		)
	);
}

/**
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
if (! function_exists('aqualuxe_sanitize_checkbox')) :
	function aqualuxe_sanitize_checkbox($checked)
	{
		return (isset($checked) && true === $checked) ? true : false;
	}
endif;
/**
 * Sanitize select.
 *
 * @param string $input   The input from the setting.
 * @param object $setting The selected setting.
 * @return string The sanitized input.
 */
if (! function_exists('aqualuxe_sanitize_select')) :
	function aqualuxe_sanitize_select($input, $setting)
	{
		$input   = sanitize_key($input);
		$choices = $setting->manager->get_control($setting->id)->choices;
		return (array_key_exists($input, $choices) ? $input : $setting->default);
	}
endif;

/**
 * Sanitize float.
 *
 * @param float $input The input from the setting.
 * @return float The sanitized input.
 */
if (! function_exists('aqualuxe_sanitize_float')) :
	function aqualuxe_sanitize_float($input)
	{
		return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	}
endif;

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js()
{
	wp_enqueue_script(
		'aqualuxe-customizer',
		AQUALUXE_THEME_URI . 'assets/dist/js/customizer.js',
		array('customize-preview'),
		AQUALUXE_VERSION,
		true
	);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');

/**
 * Enqueue customizer controls scripts.
 */
function aqualuxe_customize_controls_js()
{
	wp_enqueue_script(
		'aqualuxe-customizer-controls',
		AQUALUXE_THEME_URI . 'assets/dist/js/customizer-controls.js',
		array('customize-controls'),
		AQUALUXE_VERSION,
		true
	);

	wp_enqueue_style(
		'aqualuxe-customizer-controls',
		AQUALUXE_THEME_URI . 'assets/dist/css/customizer-controls.css',
		array(),
		AQUALUXE_VERSION
	);
}
add_action('customize_controls_enqueue_scripts', 'aqualuxe_customize_controls_js');
