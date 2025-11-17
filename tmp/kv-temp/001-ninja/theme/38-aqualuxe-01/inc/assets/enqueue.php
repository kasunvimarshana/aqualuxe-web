<?php
/**
 * Asset Enqueue Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the asset path from mix-manifest.json
 *
 * @param string $path The path to the asset.
 * @return string The versioned path to the asset.
 */
function aqualuxe_asset_path( $path ) {
	static $manifest = null;
	
	if ( null === $manifest ) {
		$manifest_path = AQUALUXE_THEME_DIR . 'assets/dist/mix-manifest.json';
		
		if ( file_exists( $manifest_path ) ) {
			$manifest = json_decode( file_get_contents( $manifest_path ), true );
		} else {
			$manifest = array();
		}
	}
	
	$path = '/' . ltrim( $path, '/' );
	
	if ( isset( $manifest[ $path ] ) ) {
		return AQUALUXE_ASSETS_URI . ltrim( $manifest[ $path ], '/' );
	}
	
	return AQUALUXE_ASSETS_URI . ltrim( $path, '/' );
}

/**
 * Enqueue scripts and styles
 */
function aqualuxe_enqueue_scripts() {
	// Register and enqueue styles
	wp_enqueue_style(
		'aqualuxe-styles',
		aqualuxe_asset_path( 'css/app.css' ),
		array(),
		AQUALUXE_VERSION
	);
	
	// Register and enqueue scripts
	wp_enqueue_script(
		'aqualuxe-scripts',
		aqualuxe_asset_path( 'js/app.js' ),
		array( 'jquery' ),
		AQUALUXE_VERSION,
		true
	);
	
	// Add script data
	wp_localize_script(
		'aqualuxe-scripts',
		'aqualuxeData',
		array(
			'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
			'nonce'      => wp_create_nonce( 'aqualuxe-ajax-nonce' ),
			'siteUrl'    => site_url(),
			'themeUrl'   => AQUALUXE_THEME_URI,
			'assetsUrl'  => AQUALUXE_ASSETS_URI,
			'isLoggedIn' => is_user_logged_in(),
			'i18n'       => array(
				'loading'   => esc_html__( 'Loading...', 'aqualuxe' ),
				'loadMore'  => esc_html__( 'Load More', 'aqualuxe' ),
				'noResults' => esc_html__( 'No results found', 'aqualuxe' ),
				'error'     => esc_html__( 'Error loading content', 'aqualuxe' ),
			),
		)
	);
	
	// Enqueue comment reply script if needed
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	// Enqueue WooCommerce specific assets if WooCommerce is active
	if ( class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
		wp_enqueue_style(
			'aqualuxe-woocommerce',
			aqualuxe_asset_path( 'css/woocommerce.css' ),
			array( 'aqualuxe-styles' ),
			AQUALUXE_VERSION
		);
		
		wp_enqueue_script(
			'aqualuxe-woocommerce',
			aqualuxe_asset_path( 'js/woocommerce.js' ),
			array( 'jquery', 'aqualuxe-scripts' ),
			AQUALUXE_VERSION,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_enqueue_scripts' );

/**
 * Enqueue admin scripts and styles
 *
 * @param string $hook The current admin page.
 */
function aqualuxe_admin_enqueue_scripts( $hook ) {
	// Enqueue admin styles
	wp_enqueue_style(
		'aqualuxe-admin-styles',
		aqualuxe_asset_path( 'css/admin.css' ),
		array(),
		AQUALUXE_VERSION
	);
	
	// Enqueue admin scripts
	wp_enqueue_script(
		'aqualuxe-admin-scripts',
		aqualuxe_asset_path( 'js/admin.js' ),
		array( 'jquery' ),
		AQUALUXE_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_admin_enqueue_scripts' );

/**
 * Enqueue block editor assets
 */
function aqualuxe_block_editor_assets() {
	// Enqueue block editor styles
	wp_enqueue_style(
		'aqualuxe-editor-styles',
		aqualuxe_asset_path( 'css/editor-style.css' ),
		array(),
		AQUALUXE_VERSION
	);
	
	// Enqueue block editor scripts
	wp_enqueue_script(
		'aqualuxe-editor-scripts',
		aqualuxe_asset_path( 'js/editor.js' ),
		array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
		AQUALUXE_VERSION,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'aqualuxe_block_editor_assets' );

/**
 * Add preload for critical assets
 */
function aqualuxe_preload_assets() {
	// Preload critical CSS
	echo '<link rel="preload" href="' . esc_url( aqualuxe_asset_path( 'css/app.css' ) ) . '" as="style">';
	
	// Preload web fonts if used
	// echo '<link rel="preload" href="' . esc_url( AQUALUXE_ASSETS_URI . 'fonts/font-file.woff2' ) . '" as="font" type="font/woff2" crossorigin>';
}
add_action( 'wp_head', 'aqualuxe_preload_assets', 1 );

/**
 * Add defer attribute to non-critical scripts
 *
 * @param string $tag The script tag.
 * @param string $handle The script handle.
 * @return string Modified script tag.
 */
function aqualuxe_defer_scripts( $tag, $handle ) {
	// List of scripts to defer
	$defer_scripts = array( 'aqualuxe-scripts' );
	
	if ( in_array( $handle, $defer_scripts, true ) ) {
		return str_replace( ' src', ' defer src', $tag );
	}
	
	return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_defer_scripts', 10, 2 );

/**
 * Remove emoji script and styles for performance
 */
function aqualuxe_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	
	// Remove from TinyMCE
	add_filter( 'tiny_mce_plugins', 'aqualuxe_disable_emojis_tinymce' );
	
	// Remove DNS prefetch
	add_filter( 'emoji_svg_url', '__return_false' );
}
add_action( 'init', 'aqualuxe_disable_emojis' );

/**
 * Filter function used to remove the tinymce emoji plugin
 *
 * @param array $plugins TinyMCE plugins.
 * @return array Modified TinyMCE plugins.
 */
function aqualuxe_disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	}
	
	return array();
}

/**
 * Disable oEmbed discovery links for performance
 */
function aqualuxe_disable_oembed() {
	// Remove the REST API endpoint
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );
	
	// Turn off oEmbed auto discovery
	add_filter( 'embed_oembed_discover', '__return_false' );
	
	// Don't filter oEmbed results
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
	
	// Remove oEmbed discovery links
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	
	// Remove oEmbed JavaScript from the front-end and back-end
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	
	// Remove filter of the oEmbed result before any HTTP requests are made
	remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
}
add_action( 'init', 'aqualuxe_disable_oembed', 9999 );