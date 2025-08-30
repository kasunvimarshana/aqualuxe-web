<?php
/**
 * AquaLuxe Theme Assets
 *
 * This file contains the Assets class for the AquaLuxe theme.
 *
 * @package AquaLuxe\Core
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Assets class.
 */
class Assets {
	/**
	 * Constructor.
	 */
	public function __construct() {
		// Register hooks.
		add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_admin_assets' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'register_editor_assets' ] );
		add_action( 'wp_head', [ $this, 'add_preload_assets' ] );
		add_filter( 'script_loader_tag', [ $this, 'add_async_defer_attributes' ], 10, 2 );
	}

	/**
	 * Register assets.
	 */
	public function register_assets() {
		// Register fonts.
		wp_register_style(
			'aqualuxe-fonts',
			'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap',
			[],
			AQUALUXE_VERSION
		);

		// Register vendor scripts.
		wp_register_script(
			'gsap',
			AQUALUXE_ASSETS_URI . 'js/vendor/gsap.min.js',
			[],
			'3.12.2',
			true
		);

		wp_register_script(
			'swiper',
			AQUALUXE_ASSETS_URI . 'js/vendor/swiper-bundle.min.js',
			[],
			'10.0.4',
			true
		);

		// Register vendor styles.
		wp_register_style(
			'swiper',
			AQUALUXE_ASSETS_URI . 'css/vendor/swiper-bundle.min.css',
			[],
			'10.0.4'
		);

		// Enqueue fonts.
		wp_enqueue_style( 'aqualuxe-fonts' );

		// Enqueue vendor scripts and styles if needed.
		if ( is_front_page() || is_home() || is_singular( 'product' ) ) {
			wp_enqueue_script( 'gsap' );
			wp_enqueue_script( 'swiper' );
			wp_enqueue_style( 'swiper' );
		}
	}

	/**
	 * Register admin assets.
	 *
	 * @param string $hook The current admin page.
	 */
	public function register_admin_assets( $hook ) {
		// Register admin scripts and styles.
		wp_register_script(
			'aqualuxe-admin-js',
			AQUALUXE_ASSETS_URI . 'js/admin.js',
			[ 'jquery' ],
			AQUALUXE_VERSION,
			true
		);

		wp_register_style(
			'aqualuxe-admin-css',
			AQUALUXE_ASSETS_URI . 'css/admin.css',
			[],
			AQUALUXE_VERSION
		);

		// Enqueue admin scripts and styles.
		wp_enqueue_script( 'aqualuxe-admin-js' );
		wp_enqueue_style( 'aqualuxe-admin-css' );

		// Add admin script data.
		wp_localize_script(
			'aqualuxe-admin-js',
			'aqualuxe_admin_params',
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'aqualuxe-admin-nonce' ),
			]
		);
	}

	/**
	 * Register editor assets.
	 */
	public function register_editor_assets() {
		// Register editor scripts and styles.
		wp_register_script(
			'aqualuxe-editor-js',
			AQUALUXE_ASSETS_URI . 'js/editor.js',
			[ 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ],
			AQUALUXE_VERSION,
			true
		);

		wp_register_style(
			'aqualuxe-editor-css',
			AQUALUXE_ASSETS_URI . 'css/editor.css',
			[ 'wp-edit-blocks' ],
			AQUALUXE_VERSION
		);

		// Enqueue editor scripts and styles.
		wp_enqueue_script( 'aqualuxe-editor-js' );
		wp_enqueue_style( 'aqualuxe-editor-css' );
	}

	/**
	 * Add preload assets.
	 */
	public function add_preload_assets() {
		// Preload fonts.
		echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
		echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';

		// Preload critical assets.
		echo '<link rel="preload" href="' . esc_url( AQUALUXE_ASSETS_URI . 'css/main.css' ) . '" as="style">';
		echo '<link rel="preload" href="' . esc_url( AQUALUXE_ASSETS_URI . 'js/app.js' ) . '" as="script">';

		// Preload logo.
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		if ( $custom_logo_id ) {
			$logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
			if ( $logo_url ) {
				echo '<link rel="preload" href="' . esc_url( $logo_url ) . '" as="image">';
			}
		}
	}

	/**
	 * Add async and defer attributes to scripts.
	 *
	 * @param string $tag    The script tag.
	 * @param string $handle The script handle.
	 * @return string The modified script tag.
	 */
	public function add_async_defer_attributes( $tag, $handle ) {
		// Add async attribute to specific scripts.
		$async_scripts = [ 'gsap', 'swiper' ];
		if ( in_array( $handle, $async_scripts, true ) ) {
			return str_replace( ' src', ' async src', $tag );
		}

		// Add defer attribute to specific scripts.
		$defer_scripts = [ 'aqualuxe-app' ];
		if ( in_array( $handle, $defer_scripts, true ) ) {
			return str_replace( ' src', ' defer src', $tag );
		}

		return $tag;
	}
}