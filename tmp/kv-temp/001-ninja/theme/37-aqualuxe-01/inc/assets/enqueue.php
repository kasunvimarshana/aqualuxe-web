<?php
/**
 * Asset enqueuing functions
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AquaLuxe Asset Management Class
 */
class AquaLuxe_Assets {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Enqueue frontend scripts and styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
		
		// Enqueue admin scripts and styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		
		// Enqueue block editor assets.
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
		
		// Enqueue customizer assets.
		add_action( 'customize_preview_init', array( $this, 'enqueue_customizer_preview_assets' ) );
		
		// Enqueue customizer controls assets.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_customizer_controls_assets' ) );
		
		// Add preload tags for critical assets.
		add_action( 'wp_head', array( $this, 'add_preload_tags' ), 1 );
		
		// Add defer attribute to non-critical scripts.
		add_filter( 'script_loader_tag', array( $this, 'add_defer_attribute' ), 10, 2 );
	}

	/**
	 * Get asset info from mix-manifest.json
	 *
	 * @param string $path Asset path.
	 * @return array Asset info.
	 */
	public function get_asset_info( $path ) {
		static $manifest = null;
		
		// Default values.
		$result = array(
			'path' => $path,
			'version' => AQUALUXE_VERSION,
		);
		
		// Try to get the manifest file.
		if ( null === $manifest ) {
			$manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';
			
			if ( file_exists( $manifest_path ) ) {
				$manifest = json_decode( file_get_contents( $manifest_path ), true );
			} else {
				$manifest = array();
			}
		}
		
		// Check if the asset is in the manifest.
		$manifest_key = '/' . $path;
		if ( isset( $manifest[ $manifest_key ] ) ) {
			$result['path'] = ltrim( $manifest[ $manifest_key ], '/' );
			
			// Extract version hash if available.
			if ( strpos( $result['path'], '?id=' ) !== false ) {
				$parts = explode( '?id=', $result['path'] );
				$result['path'] = $parts[0];
				$result['version'] = $parts[1];
			}
		}
		
		return $result;
	}

	/**
	 * Enqueue frontend scripts and styles
	 */
	public function enqueue_frontend_assets() {
		// Main stylesheet.
		$css_info = $this->get_asset_info( 'css/tailwind.css' );
		wp_enqueue_style(
			'aqualuxe-style',
			AQUALUXE_URI . 'assets/dist/' . $css_info['path'],
			array(),
			$css_info['version']
		);
		
		// Main script.
		$js_info = $this->get_asset_info( 'js/app.js' );
		wp_enqueue_script(
			'aqualuxe-script',
			AQUALUXE_URI . 'assets/dist/' . $js_info['path'],
			array( 'jquery' ),
			$js_info['version'],
			true
		);
		
		// Localize script.
		wp_localize_script(
			'aqualuxe-script',
			'aqualuxeData',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'aqualuxe-nonce' ),
				'homeUrl' => home_url(),
				'themeUri' => AQUALUXE_URI,
				'i18n'    => array(
					'close'   => esc_html__( 'Close', 'aqualuxe' ),
					'loading' => esc_html__( 'Loading...', 'aqualuxe' ),
					'error'   => esc_html__( 'Error', 'aqualuxe' ),
				),
			)
		);
		
		// Comment reply script.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		
		// WooCommerce styles and scripts.
		if ( class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
			$wc_css_info = $this->get_asset_info( 'css/woocommerce.css' );
			wp_enqueue_style(
				'aqualuxe-woocommerce',
				AQUALUXE_URI . 'assets/dist/' . $wc_css_info['path'],
				array( 'aqualuxe-style' ),
				$wc_css_info['version']
			);
			
			$wc_js_info = $this->get_asset_info( 'js/woocommerce.js' );
			wp_enqueue_script(
				'aqualuxe-woocommerce',
				AQUALUXE_URI . 'assets/dist/' . $wc_js_info['path'],
				array( 'jquery', 'aqualuxe-script' ),
				$wc_js_info['version'],
				true
			);
			
			// Localize WooCommerce script.
			wp_localize_script(
				'aqualuxe-woocommerce',
				'aqualuxeWooCommerce',
				array(
					'ajaxUrl' => admin_url( 'admin-ajax.php' ),
					'nonce'   => wp_create_nonce( 'aqualuxe-woocommerce-nonce' ),
					'i18n'    => array(
						'addToCart' => esc_html__( 'Add to cart', 'aqualuxe' ),
						'added'     => esc_html__( 'Added to cart', 'aqualuxe' ),
						'adding'    => esc_html__( 'Adding...', 'aqualuxe' ),
					),
				)
			);
		}
	}

	/**
	 * Enqueue admin scripts and styles
	 *
	 * @param string $hook Current admin page.
	 */
	public function enqueue_admin_assets( $hook ) {
		// Admin styles.
		$admin_css_info = $this->get_asset_info( 'css/admin.css' );
		wp_enqueue_style(
			'aqualuxe-admin',
			AQUALUXE_URI . 'assets/dist/' . $admin_css_info['path'],
			array(),
			$admin_css_info['version']
		);
		
		// Admin script.
		$admin_js_info = $this->get_asset_info( 'js/admin.js' );
		wp_enqueue_script(
			'aqualuxe-admin',
			AQUALUXE_URI . 'assets/dist/' . $admin_js_info['path'],
			array( 'jquery' ),
			$admin_js_info['version'],
			true
		);
	}

	/**
	 * Enqueue block editor assets
	 */
	public function enqueue_block_editor_assets() {
		// Editor styles.
		$editor_css_info = $this->get_asset_info( 'css/editor-style.css' );
		wp_enqueue_style(
			'aqualuxe-editor-style',
			AQUALUXE_URI . 'assets/dist/' . $editor_css_info['path'],
			array(),
			$editor_css_info['version']
		);
	}

	/**
	 * Enqueue customizer preview assets
	 */
	public function enqueue_customizer_preview_assets() {
		// Customizer preview script.
		$customizer_js_info = $this->get_asset_info( 'js/customizer.js' );
		wp_enqueue_script(
			'aqualuxe-customizer',
			AQUALUXE_URI . 'assets/dist/' . $customizer_js_info['path'],
			array( 'jquery', 'customize-preview' ),
			$customizer_js_info['version'],
			true
		);
	}

	/**
	 * Enqueue customizer controls assets
	 */
	public function enqueue_customizer_controls_assets() {
		// Customizer controls styles.
		$customizer_css_info = $this->get_asset_info( 'css/customizer-controls.css' );
		wp_enqueue_style(
			'aqualuxe-customizer-controls',
			AQUALUXE_URI . 'assets/dist/' . $customizer_css_info['path'],
			array(),
			$customizer_css_info['version']
		);
	}

	/**
	 * Add preload tags for critical assets
	 */
	public function add_preload_tags() {
		// Preload main stylesheet.
		$css_info = $this->get_asset_info( 'css/tailwind.css' );
		echo '<link rel="preload" href="' . esc_url( AQUALUXE_URI . 'assets/dist/' . $css_info['path'] ) . '" as="style">';
		
		// Preload fonts if used.
		$font_paths = array(
			'fonts/Montserrat-Regular.woff2',
			'fonts/Montserrat-Bold.woff2',
			'fonts/PlayfairDisplay-Regular.woff2',
			'fonts/PlayfairDisplay-Bold.woff2',
		);
		
		foreach ( $font_paths as $font_path ) {
			if ( file_exists( AQUALUXE_DIR . 'assets/dist/' . $font_path ) ) {
				echo '<link rel="preload" href="' . esc_url( AQUALUXE_URI . 'assets/dist/' . $font_path ) . '" as="font" type="font/woff2" crossorigin>';
			}
		}
	}

	/**
	 * Add defer attribute to non-critical scripts
	 *
	 * @param string $tag    Script tag.
	 * @param string $handle Script handle.
	 * @return string
	 */
	public function add_defer_attribute( $tag, $handle ) {
		// List of scripts to defer.
		$defer_scripts = array(
			'aqualuxe-script',
			'aqualuxe-woocommerce',
		);
		
		if ( in_array( $handle, $defer_scripts, true ) ) {
			return str_replace( ' src', ' defer src', $tag );
		}
		
		return $tag;
	}
}

// Initialize asset management.
new AquaLuxe_Assets();