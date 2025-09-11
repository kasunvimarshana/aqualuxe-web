<?php
/**
 * Asset Management
 *
 * Handle theme stylesheets, scripts, and asset loading
 * with cache busting and performance optimization
 *
 * @package AquaLuxe
 * @since 2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue frontend styles and scripts
 */
function aqualuxe_enqueue_assets() {
	// Get asset manifest for cache busting
	$manifest = aqualuxe_get_asset_manifest();
	
	// Main stylesheet
	wp_enqueue_style(
		'aqualuxe-main',
		aqualuxe_get_asset_url( 'css/main.css', $manifest ),
		array(),
		aqualuxe_get_asset_version( 'css/main.css', $manifest )
	);
	
	// WooCommerce styles (conditional)
	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style(
			'aqualuxe-woocommerce',
			aqualuxe_get_asset_url( 'css/woocommerce.css', $manifest ),
			array( 'aqualuxe-main' ),
			aqualuxe_get_asset_version( 'css/woocommerce.css', $manifest )
		);
	}
	
	// Main JavaScript
	wp_enqueue_script(
		'aqualuxe-main',
		aqualuxe_get_asset_url( 'js/main.js', $manifest ),
		array( 'jquery' ),
		aqualuxe_get_asset_version( 'js/main.js', $manifest ),
		true
	);
	
	// Localize script for AJAX and theme data
	wp_localize_script( 'aqualuxe-main', 'aqualuxeData', array(
		'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
		'nonce'        => wp_create_nonce( 'aqualuxe_nonce' ),
		'themeUrl'     => get_template_directory_uri(),
		'homeUrl'      => home_url(),
		'currentUrl'   => aqualuxe_get_current_url(),
		'isRTL'        => is_rtl(),
		'locale'       => get_locale(),
		'userId'       => get_current_user_id(),
		'darkMode'     => get_theme_mod( 'aqualuxe_dark_mode', 'auto' ),
		'animations'   => get_theme_mod( 'aqualuxe_enable_animations', true ),
		'lazyLoading'  => get_theme_mod( 'aqualuxe_enable_lazy_loading', true ),
		'strings'      => array(
			'loading'    => esc_html__( 'Loading...', 'aqualuxe' ),
			'error'      => esc_html__( 'An error occurred', 'aqualuxe' ),
			'success'    => esc_html__( 'Success!', 'aqualuxe' ),
			'close'      => esc_html__( 'Close', 'aqualuxe' ),
			'readMore'   => esc_html__( 'Read More', 'aqualuxe' ),
		),
	) );
	
	// Comment reply script
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	// Conditional assets based on page type
	aqualuxe_enqueue_conditional_assets( $manifest );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_enqueue_assets' );

/**
 * Enqueue admin styles and scripts
 */
function aqualuxe_enqueue_admin_assets( $hook ) {
	// Get asset manifest
	$manifest = aqualuxe_get_asset_manifest();
	
	// Admin styles
	wp_enqueue_style(
		'aqualuxe-admin',
		aqualuxe_get_asset_url( 'css/admin.css', $manifest ),
		array(),
		aqualuxe_get_asset_version( 'css/admin.css', $manifest )
	);
	
	// Admin JavaScript
	wp_enqueue_script(
		'aqualuxe-admin',
		aqualuxe_get_asset_url( 'js/admin.js', $manifest ),
		array( 'jquery', 'wp-color-picker' ),
		aqualuxe_get_asset_version( 'js/admin.js', $manifest ),
		true
	);
	
	// Enqueue color picker
	wp_enqueue_style( 'wp-color-picker' );
	
	// Localize admin script
	wp_localize_script( 'aqualuxe-admin', 'aqualuxeAdmin', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'aqualuxe_admin_nonce' ),
		'strings' => array(
			'confirmDelete' => esc_html__( 'Are you sure you want to delete this?', 'aqualuxe' ),
			'saved'        => esc_html__( 'Settings saved successfully!', 'aqualuxe' ),
			'error'        => esc_html__( 'An error occurred while saving.', 'aqualuxe' ),
		),
	) );
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_enqueue_admin_assets' );

/**
 * Enqueue Customizer assets
 */
function aqualuxe_enqueue_customizer_assets() {
	$manifest = aqualuxe_get_asset_manifest();
	
	wp_enqueue_script(
		'aqualuxe-customizer',
		aqualuxe_get_asset_url( 'js/customizer.js', $manifest ),
		array( 'customize-controls' ),
		aqualuxe_get_asset_version( 'js/customizer.js', $manifest ),
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'aqualuxe_enqueue_customizer_assets' );

/**
 * Enqueue conditional assets based on page context
 */
function aqualuxe_enqueue_conditional_assets( $manifest = null ) {
	if ( ! $manifest ) {
		$manifest = aqualuxe_get_asset_manifest();
	}
	
	// WooCommerce specific assets
	if ( class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
		wp_enqueue_script(
			'aqualuxe-woocommerce',
			aqualuxe_get_asset_url( 'js/woocommerce.js', $manifest ),
			array( 'aqualuxe-main' ),
			aqualuxe_get_asset_version( 'js/woocommerce.js', $manifest ),
			true
		);
	}
	
	// Contact page specific assets
	if ( is_page_template( 'templates/pages/contact.php' ) ) {
		// Google Maps API (if enabled)
		$google_maps_api_key = get_theme_mod( 'aqualuxe_google_maps_api_key', '' );
		if ( ! empty( $google_maps_api_key ) ) {
			wp_enqueue_script(
				'google-maps',
				'https://maps.googleapis.com/maps/api/js?key=' . esc_attr( $google_maps_api_key ),
				array(),
				null,
				true
			);
		}
	}
	
	// Gallery page assets
	if ( is_page_template( 'templates/pages/gallery.php' ) || has_post_format( 'gallery' ) ) {
		wp_enqueue_script( 'aqualuxe-gallery', aqualuxe_get_asset_url( 'js/gallery.js', $manifest ), array( 'aqualuxe-main' ), aqualuxe_get_asset_version( 'js/gallery.js', $manifest ), true );
	}
}

/**
 * Get asset manifest for cache busting
 */
function aqualuxe_get_asset_manifest() {
	static $manifest = null;
	
	if ( $manifest === null ) {
		$manifest_file = AQUALUXE_ASSETS_PATH . '/dist/mix-manifest.json';
		
		if ( file_exists( $manifest_file ) ) {
			$manifest_content = file_get_contents( $manifest_file );
			$manifest = json_decode( $manifest_content, true );
		} else {
			$manifest = array();
		}
	}
	
	return $manifest;
}

/**
 * Get asset URL with cache busting
 */
function aqualuxe_get_asset_url( $asset_path, $manifest = null ) {
	if ( ! $manifest ) {
		$manifest = aqualuxe_get_asset_manifest();
	}
	
	// Remove leading slash if present
	$asset_path = ltrim( $asset_path, '/' );
	
	// Check if asset exists in manifest
	if ( isset( $manifest[ $asset_path ] ) ) {
		return AQUALUXE_ASSETS_URL . '/dist' . $manifest[ $asset_path ];
	}
	
	// Fallback to original path
	return AQUALUXE_ASSETS_URL . '/dist/' . $asset_path;
}

/**
 * Get asset version for cache busting
 */
function aqualuxe_get_asset_version( $asset_path, $manifest = null ) {
	if ( ! $manifest ) {
		$manifest = aqualuxe_get_asset_manifest();
	}
	
	// Remove leading slash if present
	$asset_path = ltrim( $asset_path, '/' );
	
	// If we have a versioned file in manifest, use the theme version
	if ( isset( $manifest[ $asset_path ] ) ) {
		// Extract version from filename if it contains hash
		$versioned_file = $manifest[ $asset_path ];
		if ( preg_match( '/\?id=([a-f0-9]+)/', $versioned_file, $matches ) ) {
			return $matches[1];
		}
	}
	
	// Fallback to theme version
	return AQUALUXE_VERSION;
}

/**
 * Preload critical assets
 */
function aqualuxe_preload_assets() {
	$manifest = aqualuxe_get_asset_manifest();
	
	// Preload critical CSS
	echo '<link rel="preload" href="' . esc_url( aqualuxe_get_asset_url( 'css/main.css', $manifest ) ) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
	echo '<noscript><link rel="stylesheet" href="' . esc_url( aqualuxe_get_asset_url( 'css/main.css', $manifest ) ) . '"></noscript>';
	
	// Preload critical JavaScript
	echo '<link rel="preload" href="' . esc_url( aqualuxe_get_asset_url( 'js/main.js', $manifest ) ) . '" as="script">';
	
	// Preload web fonts if custom fonts are used
	$custom_fonts = get_theme_mod( 'aqualuxe_custom_fonts', array() );
	if ( ! empty( $custom_fonts ) ) {
		foreach ( $custom_fonts as $font ) {
			if ( isset( $font['url'] ) ) {
				echo '<link rel="preload" href="' . esc_url( $font['url'] ) . '" as="font" type="font/woff2" crossorigin>';
			}
		}
	}
}
add_action( 'wp_head', 'aqualuxe_preload_assets', 1 );

/**
 * Add resource hints
 */
function aqualuxe_resource_hints( $urls, $relation_type ) {
	switch ( $relation_type ) {
		case 'dns-prefetch':
			// Add external domains for DNS prefetch
			$external_domains = array(
				'//fonts.googleapis.com',
				'//fonts.gstatic.com',
				'//www.google-analytics.com',
				'//www.googletagmanager.com',
			);
			
			$urls = array_merge( $urls, $external_domains );
			break;
			
		case 'preconnect':
			// Add critical external connections
			$preconnect_urls = array(
				'https://fonts.gstatic.com',
			);
			
			$urls = array_merge( $urls, $preconnect_urls );
			break;
	}
	
	return $urls;
}
add_filter( 'wp_resource_hints', 'aqualuxe_resource_hints', 10, 2 );

/**
 * Optimize script loading
 */
function aqualuxe_optimize_scripts( $tag, $handle, $src ) {
	// Add defer to non-critical scripts
	$defer_scripts = array(
		'aqualuxe-gallery',
		'aqualuxe-animations',
		'contact-form',
	);
	
	if ( in_array( $handle, $defer_scripts, true ) ) {
		return str_replace( '<script ', '<script defer ', $tag );
	}
	
	// Add async to analytics scripts
	$async_scripts = array(
		'google-analytics',
		'facebook-pixel',
	);
	
	if ( in_array( $handle, $async_scripts, true ) ) {
		return str_replace( '<script ', '<script async ', $tag );
	}
	
	return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_optimize_scripts', 10, 3 );

/**
 * Remove jQuery Migrate in production
 */
function aqualuxe_remove_jquery_migrate() {
	if ( ! is_admin() && ! WP_DEBUG ) {
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', includes_url( '/js/jquery/jquery.min.js' ), false, null, true );
		wp_enqueue_script( 'jquery' );
	}
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_remove_jquery_migrate' );

/**
 * Get current URL helper
 */
function aqualuxe_get_current_url() {
	global $wp;
	return home_url( add_query_arg( array(), $wp->request ) );
}