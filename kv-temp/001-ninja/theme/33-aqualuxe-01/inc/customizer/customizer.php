<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load all the customizer files.
 */
require_once AQUALUXE_INC_DIR . 'customizer/class-customizer.php';
require_once AQUALUXE_INC_DIR . 'customizer/controls/class-toggle-control.php';
require_once AQUALUXE_INC_DIR . 'customizer/controls/class-slider-control.php';
require_once AQUALUXE_INC_DIR . 'customizer/controls/class-sortable-control.php';
require_once AQUALUXE_INC_DIR . 'customizer/controls/class-radio-image-control.php';
require_once AQUALUXE_INC_DIR . 'customizer/controls/class-typography-control.php';
require_once AQUALUXE_INC_DIR . 'customizer/controls/class-color-alpha-control.php';
require_once AQUALUXE_INC_DIR . 'customizer/controls/class-dimensions-control.php';
require_once AQUALUXE_INC_DIR . 'customizer/controls/class-heading-control.php';
require_once AQUALUXE_INC_DIR . 'customizer/controls/class-divider-control.php';
require_once AQUALUXE_INC_DIR . 'customizer/sections/class-general.php';
require_once AQUALUXE_INC_DIR . 'customizer/sections/class-header.php';
require_once AQUALUXE_INC_DIR . 'customizer/sections/class-footer.php';
require_once AQUALUXE_INC_DIR . 'customizer/sections/class-typography.php';
require_once AQUALUXE_INC_DIR . 'customizer/sections/class-colors.php';
require_once AQUALUXE_INC_DIR . 'customizer/sections/class-layout.php';
require_once AQUALUXE_INC_DIR . 'customizer/sections/class-blog.php';
require_once AQUALUXE_INC_DIR . 'customizer/sections/class-performance.php';
require_once AQUALUXE_INC_DIR . 'customizer/sections/class-social-media.php';
require_once AQUALUXE_INC_DIR . 'customizer/sections/class-advanced.php';
require_once AQUALUXE_INC_DIR . 'customizer/sections/class-pro-section.php';

if ( class_exists( 'WooCommerce' ) ) {
	require_once AQUALUXE_INC_DIR . 'customizer/sections/class-woocommerce.php';
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
	wp_enqueue_script( 'aqualuxe-customizer', AQUALUXE_ASSETS_URI . 'js/customizer.js', array( 'customize-preview' ), AQUALUXE_VERSION, true );
}
add_action( 'customize_preview_init', 'aqualuxe_customize_preview_js' );

/**
 * Enqueue customizer controls scripts and styles.
 */
function aqualuxe_customize_controls_enqueue_scripts() {
	wp_enqueue_style( 'aqualuxe-customizer-controls', AQUALUXE_ASSETS_URI . 'css/customizer-controls.css', array(), AQUALUXE_VERSION );
	wp_enqueue_script( 'aqualuxe-customizer-controls', AQUALUXE_ASSETS_URI . 'js/customizer-controls.js', array( 'jquery', 'customize-controls' ), AQUALUXE_VERSION, true );
}
add_action( 'customize_controls_enqueue_scripts', 'aqualuxe_customize_controls_enqueue_scripts' );

/**
 * Generate custom CSS based on customizer settings.
 */
function aqualuxe_customizer_css() {
	// Get the customizer instance.
	$theme = aqualuxe_get_theme_instance();
	$customizer = $theme->get_service( 'customizer' );
	
	// Generate and output custom CSS.
	$css = $customizer->generate_custom_css();
	
	if ( $css ) {
		echo '<style id="aqualuxe-customizer-css">' . wp_strip_all_tags( $css ) . '</style>' . "\n";
	}
}
add_action( 'wp_head', 'aqualuxe_customizer_css', 100 );

/**
 * Generate custom CSS file.
 */
function aqualuxe_generate_custom_css_file() {
	// Get the customizer instance.
	$theme = aqualuxe_get_theme_instance();
	$customizer = $theme->get_service( 'customizer' );
	
	// Generate custom CSS.
	$css = $customizer->generate_custom_css();
	
	if ( ! $css ) {
		return;
	}
	
	// Create upload directory if it doesn't exist.
	$upload_dir = wp_upload_dir();
	$dir = trailingslashit( $upload_dir['basedir'] ) . 'aqualuxe';
	
	if ( ! file_exists( $dir ) ) {
		wp_mkdir_p( $dir );
	}
	
	// Create index.php file.
	if ( ! file_exists( trailingslashit( $dir ) . 'index.php' ) ) {
		$index_file = fopen( trailingslashit( $dir ) . 'index.php', 'w' );
		fwrite( $index_file, '<?php // Silence is golden.' );
		fclose( $index_file );
	}
	
	// Create custom CSS file.
	$css_file = fopen( trailingslashit( $dir ) . 'custom-styles.css', 'w' );
	fwrite( $css_file, $css );
	fclose( $css_file );
}
add_action( 'customize_save_after', 'aqualuxe_generate_custom_css_file' );

/**
 * Output custom CSS file.
 */
function aqualuxe_output_custom_css_file() {
	// Check if custom CSS file exists.
	$upload_dir = wp_upload_dir();
	$css_file = trailingslashit( $upload_dir['basedir'] ) . 'aqualuxe/custom-styles.css';
	
	if ( file_exists( $css_file ) ) {
		// Output link to custom CSS file.
		$css_url = trailingslashit( $upload_dir['baseurl'] ) . 'aqualuxe/custom-styles.css';
		echo '<link rel="stylesheet" id="aqualuxe-custom-styles" href="' . esc_url( $css_url ) . '?ver=' . esc_attr( AQUALUXE_VERSION ) . '" media="all">' . "\n";
	}
}
add_action( 'wp_head', 'aqualuxe_output_custom_css_file', 99 );