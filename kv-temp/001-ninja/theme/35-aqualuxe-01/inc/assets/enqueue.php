<?php
/**
 * Enqueue scripts and styles
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_enqueue_scripts() {
	$theme_version = AQUALUXE_VERSION;
	$is_debug      = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;

	// Get the manifest file.
	$manifest_path = AQUALUXE_THEME_DIR . 'assets/dist/mix-manifest.json';
	$manifest      = file_exists( $manifest_path ) ? json_decode( file_get_contents( $manifest_path ), true ) : array();

	// Helper function to get versioned asset path.
	$get_asset_path = function( $path ) use ( $manifest ) {
		$path = '/' . ltrim( $path, '/' );
		return isset( $manifest[ $path ] ) ? $manifest[ $path ] : $path;
	};

	// Enqueue main stylesheet.
	$css_file = $is_debug ? 'assets/dist/css/app.css' : 'assets/dist/css/app.min.css';
	$css_path = $get_asset_path( $css_file );
	wp_enqueue_style(
		'aqualuxe-styles',
		AQUALUXE_THEME_URI . ltrim( $css_path, '/' ),
		array(),
		$theme_version
	);

	// Enqueue main script.
	$js_file = $is_debug ? 'assets/dist/js/app.js' : 'assets/dist/js/app.min.js';
	$js_path = $get_asset_path( $js_file );
	wp_enqueue_script(
		'aqualuxe-scripts',
		AQUALUXE_THEME_URI . ltrim( $js_path, '/' ),
		array( 'jquery' ),
		$theme_version,
		true
	);

	// Add localized script data.
	wp_localize_script(
		'aqualuxe-scripts',
		'aqualuxeData',
		array(
			'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
			'ajaxNonce'  => wp_create_nonce( 'aqualuxe-ajax-nonce' ),
			'themeUri'   => AQUALUXE_THEME_URI,
			'isWooActive' => class_exists( 'WooCommerce' ),
		)
	);

	// Enqueue comment reply script if needed.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Conditionally load WooCommerce styles.
	if ( class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
		$woo_css_file = $is_debug ? 'assets/dist/css/woocommerce.css' : 'assets/dist/css/woocommerce.min.css';
		$woo_css_path = $get_asset_path( $woo_css_file );
		wp_enqueue_style(
			'aqualuxe-woocommerce',
			AQUALUXE_THEME_URI . ltrim( $woo_css_path, '/' ),
			array( 'aqualuxe-styles' ),
			$theme_version
		);

		$woo_js_file = $is_debug ? 'assets/dist/js/woocommerce.js' : 'assets/dist/js/woocommerce.min.js';
		$woo_js_path = $get_asset_path( $woo_js_file );
		wp_enqueue_script(
			'aqualuxe-woocommerce',
			AQUALUXE_THEME_URI . ltrim( $woo_js_path, '/' ),
			array( 'jquery', 'aqualuxe-scripts' ),
			$theme_version,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_enqueue_scripts' );

/**
 * Enqueue editor assets.
 */
function aqualuxe_enqueue_block_editor_assets() {
	$theme_version = AQUALUXE_VERSION;
	$is_debug      = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;

	// Get the manifest file.
	$manifest_path = AQUALUXE_THEME_DIR . 'assets/dist/mix-manifest.json';
	$manifest      = file_exists( $manifest_path ) ? json_decode( file_get_contents( $manifest_path ), true ) : array();

	// Helper function to get versioned asset path.
	$get_asset_path = function( $path ) use ( $manifest ) {
		$path = '/' . ltrim( $path, '/' );
		return isset( $manifest[ $path ] ) ? $manifest[ $path ] : $path;
	};

	// Enqueue editor styles.
	$editor_css_file = $is_debug ? 'assets/dist/css/editor.css' : 'assets/dist/css/editor.min.css';
	$editor_css_path = $get_asset_path( $editor_css_file );
	wp_enqueue_style(
		'aqualuxe-editor-styles',
		AQUALUXE_THEME_URI . ltrim( $editor_css_path, '/' ),
		array(),
		$theme_version
	);

	// Enqueue editor scripts.
	$editor_js_file = $is_debug ? 'assets/dist/js/editor.js' : 'assets/dist/js/editor.min.js';
	$editor_js_path = $get_asset_path( $editor_js_file );
	wp_enqueue_script(
		'aqualuxe-editor-scripts',
		AQUALUXE_THEME_URI . ltrim( $editor_js_path, '/' ),
		array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
		$theme_version,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'aqualuxe_enqueue_block_editor_assets' );

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function aqualuxe_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'aqualuxe-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'aqualuxe_resource_hints', 10, 2 );

/**
 * Disable WordPress emojis.
 */
function aqualuxe_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'aqualuxe_disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'aqualuxe_disable_emojis_remove_dns_prefetch', 10, 2 );
}
add_action( 'init', 'aqualuxe_disable_emojis' );

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param array $plugins Array of TinyMCE plugins.
 * @return array Difference between the two arrays.
 */
function aqualuxe_disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	}
	return array();
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @param array  $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array Difference between the two arrays.
 */
function aqualuxe_disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/13.0.1/svg/' );
		$urls          = array_diff( $urls, array( $emoji_svg_url ) );
	}
	return $urls;
}