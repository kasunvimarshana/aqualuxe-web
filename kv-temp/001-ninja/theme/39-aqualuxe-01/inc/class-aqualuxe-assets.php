<?php
/**
 * Assets Management Class
 *
 * @package AquaLuxe
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AquaLuxe_Assets {
	
	/**
	 * Single instance of the class
	 *
	 * @var AquaLuxe_Assets
	 */
	protected static $_instance = null;

	/**
	 * Assets manifest
	 *
	 * @var array
	 */
	private $manifest = array();

	/**
	 * Main instance
	 *
	 * @return AquaLuxe_Assets
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->load_manifest();
		$this->init_hooks();
	}

	/**
	 * Load the mix manifest
	 */
	private function load_manifest() {
		$manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
		
		if ( file_exists( $manifest_path ) ) {
			$this->manifest = json_decode( file_get_contents( $manifest_path ), true );
		}
	}

	/**
	 * Initialize hooks
	 */
	private function init_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
		add_action( 'wp_head', array( $this, 'add_preload_links' ), 1 );
	}

	/**
	 * Enqueue frontend assets
	 */
	public function enqueue_frontend_assets() {
		// Main CSS
		wp_enqueue_style(
			'aqualuxe-main',
			$this->get_asset_url( '/css/main.css' ),
			array(),
			$this->get_asset_version( '/css/main.css' )
		);
		
		// WooCommerce CSS (only if WooCommerce is active)
		if ( aqualuxe_is_woocommerce_active() ) {
			wp_enqueue_style(
				'aqualuxe-woocommerce',
				$this->get_asset_url( '/css/woocommerce.css' ),
				array( 'aqualuxe-main' ),
				$this->get_asset_version( '/css/woocommerce.css' )
			);
		}
		
		// Main JavaScript
		wp_enqueue_script(
			'aqualuxe-main',
			$this->get_asset_url( '/js/main.js' ),
			array(),
			$this->get_asset_version( '/js/main.js' ),
			true
		);
		
		// WooCommerce JavaScript (only if WooCommerce is active)
		if ( aqualuxe_is_woocommerce_active() ) {
			wp_enqueue_script(
				'aqualuxe-woocommerce',
				$this->get_asset_url( '/js/woocommerce.js' ),
				array( 'aqualuxe-main', 'jquery' ),
				$this->get_asset_version( '/js/woocommerce.js' ),
				true
			);
		}
		
		// Localize script
		wp_localize_script( 'aqualuxe-main', 'aqualuxe', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'aqualuxe_nonce' ),
			'theme_url' => AQUALUXE_THEME_URI,
			'is_rtl' => is_rtl(),
			'is_woocommerce_active' => aqualuxe_is_woocommerce_active(),
			'strings' => array(
				'loading' => __( 'Loading...', 'aqualuxe' ),
				'error' => __( 'An error occurred. Please try again.', 'aqualuxe' ),
				'success' => __( 'Success!', 'aqualuxe' ),
			),
		) );
		
		// Comment reply script
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Enqueue admin assets
	 */
	public function enqueue_admin_assets( $hook ) {
		// Only load on theme-related admin pages
		$theme_pages = array(
			'appearance_page_aqualuxe-options',
			'customize.php',
			'themes.php',
		);
		
		if ( ! in_array( $hook, $theme_pages ) && strpos( $hook, 'aqualuxe' ) === false ) {
			return;
		}
		
		wp_enqueue_style(
			'aqualuxe-admin',
			$this->get_asset_url( '/css/admin.css' ),
			array(),
			$this->get_asset_version( '/css/admin.css' )
		);
		
		wp_enqueue_script(
			'aqualuxe-admin',
			$this->get_asset_url( '/js/admin.js' ),
			array( 'jquery' ),
			$this->get_asset_version( '/js/admin.js' ),
			true
		);
		
		wp_localize_script( 'aqualuxe-admin', 'aqualuxeAdmin', array(
			'nonce' => wp_create_nonce( 'aqualuxe_admin_nonce' ),
			'strings' => array(
				'confirm_import' => __( 'Are you sure you want to import demo content? This will add sample data to your site.', 'aqualuxe' ),
				'importing' => __( 'Importing...', 'aqualuxe' ),
				'import_complete' => __( 'Import completed successfully!', 'aqualuxe' ),
				'import_error' => __( 'Import failed. Please try again.', 'aqualuxe' ),
			),
		) );
	}

	/**
	 * Enqueue block editor assets
	 */
	public function enqueue_editor_assets() {
		wp_enqueue_style(
			'aqualuxe-editor',
			$this->get_asset_url( '/css/main.css' ),
			array(),
			$this->get_asset_version( '/css/main.css' )
		);
	}

	/**
	 * Add preload links for critical assets
	 */
	public function add_preload_links() {
		// Preload main CSS
		$main_css = $this->get_asset_url( '/css/main.css' );
		echo '<link rel="preload" href="' . esc_url( $main_css ) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
		
		// Preload main JS
		$main_js = $this->get_asset_url( '/js/main.js' );
		echo '<link rel="preload" href="' . esc_url( $main_js ) . '" as="script">' . "\n";
		
		// Preload critical fonts
		$fonts = array(
			'/fonts/inter-regular.woff2',
			'/fonts/inter-semibold.woff2',
			'/fonts/playfair-display-regular.woff2',
		);
		
		foreach ( $fonts as $font ) {
			$font_url = AQUALUXE_ASSETS_URI . $font;
			if ( file_exists( AQUALUXE_THEME_DIR . '/assets/dist' . $font ) ) {
				echo '<link rel="preload" href="' . esc_url( $font_url ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
			}
		}
	}

	/**
	 * Get asset URL with cache busting
	 */
	private function get_asset_url( $path ) {
		if ( isset( $this->manifest[ $path ] ) ) {
			return AQUALUXE_ASSETS_URI . $this->manifest[ $path ];
		}
		
		return AQUALUXE_ASSETS_URI . $path;
	}

	/**
	 * Get asset version for cache busting
	 */
	private function get_asset_version( $path ) {
		if ( isset( $this->manifest[ $path ] ) ) {
			// Extract version from manifested filename
			$filename = basename( $this->manifest[ $path ] );
			preg_match( '/\?id=([a-f0-9]+)/', $filename, $matches );
			return isset( $matches[1] ) ? $matches[1] : AQUALUXE_VERSION;
		}
		
		return AQUALUXE_VERSION;
	}
}