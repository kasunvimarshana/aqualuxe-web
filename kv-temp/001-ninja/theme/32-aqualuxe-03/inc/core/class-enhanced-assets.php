<?php
/**
 * Enhanced Assets Class
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.3.0
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enhanced Assets Class
 *
 * This class handles all asset loading for the theme using the enhanced asset pipeline.
 *
 * @since 1.3.0
 */
class Enhanced_Assets {

	/**
	 * The single instance of this class.
	 *
	 * @var Enhanced_Assets
	 */
	private static $instance = null;

	/**
	 * Constructor.
	 */
	private function __construct() {
		// Include the asset loader helper.
		require_once AQUALUXE_DIR . '/inc/helpers/asset-loader.php';

		// Add hooks.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'block_editor_assets' ) );
		add_action( 'wp_head', array( $this, 'add_preload_tags' ) );
		add_action( 'wp_head', array( $this, 'add_critical_css' ) );
		add_filter( 'script_loader_tag', array( $this, 'add_script_attributes' ), 10, 3 );
		add_filter( 'style_loader_tag', array( $this, 'add_style_attributes' ), 10, 4 );
	}

	/**
	 * Get the single instance of this class.
	 *
	 * @return Enhanced_Assets
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Enqueue scripts.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		// Register and enqueue vendor scripts.
		aqualuxe_enqueue_script( 'aqualuxe-vendor', 'assets/js/vendor.js', array( 'jquery' ), true );
		
		// Register and enqueue manifest script.
		aqualuxe_enqueue_script( 'aqualuxe-manifest', 'assets/js/manifest.js', array(), true );
		
		// Register and enqueue main scripts.
		aqualuxe_enqueue_script( 'aqualuxe-app', 'assets/js/app.js', array( 'jquery', 'aqualuxe-vendor', 'aqualuxe-manifest' ), true );

		// Localize script.
		wp_localize_script(
			'aqualuxe-app',
			'aqualuxeSettings',
			array(
				'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
				'nonce'          => wp_create_nonce( 'aqualuxe-nonce' ),
				'darkModeToggle' => get_theme_mod( 'aqualuxe_enable_dark_mode', true ),
				'isRTL'          => is_rtl(),
				'themeUri'       => get_template_directory_uri(),
				'homeUrl'        => home_url(),
			)
		);

		// Conditionally load WooCommerce scripts.
		if ( class_exists( 'WooCommerce' ) ) {
			aqualuxe_enqueue_script( 'aqualuxe-woocommerce', 'assets/js/woocommerce.js', array( 'jquery', 'aqualuxe-app' ), true );
			
			// Localize WooCommerce script.
			wp_localize_script(
				'aqualuxe-woocommerce',
				'aqualuxeWooCommerce',
				array(
					'ajaxUrl'             => admin_url( 'admin-ajax.php' ),
					'nonce'               => wp_create_nonce( 'aqualuxe-woocommerce' ),
					'addToCartText'       => esc_html__( 'Add to cart', 'aqualuxe' ),
					'viewCartText'        => esc_html__( 'View cart', 'aqualuxe' ),
					'addingToCartText'    => esc_html__( 'Adding...', 'aqualuxe' ),
					'addedToCartText'     => esc_html__( 'Added!', 'aqualuxe' ),
					'wishlistAddText'     => esc_html__( 'Add to wishlist', 'aqualuxe' ),
					'wishlistAddedText'   => esc_html__( 'Added to wishlist', 'aqualuxe' ),
					'wishlistExistsText'  => esc_html__( 'Already in wishlist', 'aqualuxe' ),
					'quickViewText'       => esc_html__( 'Quick view', 'aqualuxe' ),
					'loadingText'         => esc_html__( 'Loading...', 'aqualuxe' ),
					'cartUrl'             => wc_get_cart_url(),
					'isRTL'               => is_rtl(),
				)
			);
		}

		// Conditionally load comment-reply script.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Register and enqueue service worker.
		if ( ! is_admin() ) {
			aqualuxe_enqueue_script( 'aqualuxe-service-worker-register', 'assets/js/service-worker-register.js', array(), true );
		}
	}

	/**
	 * Enqueue styles.
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		// Register and enqueue main styles.
		aqualuxe_enqueue_style( 'aqualuxe-main', 'assets/css/main.css' );

		// Add RTL support.
		wp_style_add_data( 'aqualuxe-main', 'rtl', 'replace' );

		// Register and enqueue dark mode styles.
		aqualuxe_enqueue_style( 'aqualuxe-dark-mode', 'assets/css/dark-mode.css', array( 'aqualuxe-main' ) );

		// Conditionally load WooCommerce styles.
		if ( class_exists( 'WooCommerce' ) ) {
			aqualuxe_enqueue_style( 'aqualuxe-woocommerce', 'assets/css/woocommerce.css', array( 'aqualuxe-main' ) );
			
			// Add RTL support.
			wp_style_add_data( 'aqualuxe-woocommerce', 'rtl', 'replace' );
		}

		// Register and enqueue print styles.
		aqualuxe_enqueue_style( 'aqualuxe-print', 'assets/css/print.css', array(), 'print' );
	}

	/**
	 * Enqueue admin scripts.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		aqualuxe_enqueue_script( 'aqualuxe-admin', 'assets/js/admin.js', array( 'jquery' ), true );

		// Localize admin script.
		wp_localize_script(
			'aqualuxe-admin',
			'aqualuxeAdmin',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'aqualuxe-admin-nonce' ),
			)
		);
	}

	/**
	 * Enqueue admin styles.
	 *
	 * @return void
	 */
	public function admin_enqueue_styles() {
		aqualuxe_enqueue_style( 'aqualuxe-admin', 'assets/css/admin.css' );
	}

	/**
	 * Enqueue block editor assets.
	 *
	 * @return void
	 */
	public function block_editor_assets() {
		aqualuxe_enqueue_script( 'aqualuxe-editor', 'assets/js/editor.js', array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ), true );
		aqualuxe_enqueue_style( 'aqualuxe-editor-style', 'assets/css/editor.css' );
	}

	/**
	 * Add preload tags for critical resources.
	 *
	 * @return void
	 */
	public function add_preload_tags() {
		$preload_assets = array(
			'assets/fonts/main-font.woff2' => 'font',
			'assets/css/main.css' => 'style',
			'assets/js/manifest.js' => 'script',
			'assets/js/vendor.js' => 'script',
			'assets/images/sprite.svg' => 'image'
		);

		aqualuxe_preload_assets( $preload_assets );
	}

	/**
	 * Add critical CSS.
	 *
	 * @return void
	 */
	public function add_critical_css() {
		$template = 'home';

		if ( is_home() || is_archive() ) {
			$template = 'blog';
		} elseif ( class_exists( 'WooCommerce' ) ) {
			if ( is_shop() || is_product_category() || is_product_tag() ) {
				$template = 'shop';
			} elseif ( is_product() ) {
				$template = 'product';
			}
		}

		aqualuxe_critical_css( $template );
	}

	/**
	 * Add script attributes.
	 *
	 * @param string $tag    The script tag.
	 * @param string $handle The script handle.
	 * @param string $src    The script source.
	 * @return string
	 */
	public function add_script_attributes( $tag, $handle, $src ) {
		// Add defer attribute to non-critical scripts.
		$defer_scripts = array(
			'aqualuxe-app',
			'aqualuxe-woocommerce',
			'aqualuxe-service-worker-register'
		);

		if ( in_array( $handle, $defer_scripts, true ) ) {
			return str_replace( ' src', ' defer src', $tag );
		}

		// Add module attribute to ES module scripts.
		$module_scripts = array(
			'aqualuxe-module'
		);

		if ( in_array( $handle, $module_scripts, true ) ) {
			return str_replace( ' src', ' type="module" src', $tag );
		}

		return $tag;
	}

	/**
	 * Add style attributes.
	 *
	 * @param string $html   The style tag.
	 * @param string $handle The style handle.
	 * @param string $href   The style href.
	 * @param string $media  The style media.
	 * @return string
	 */
	public function add_style_attributes( $html, $handle, $href, $media ) {
		// Add preload attribute to critical styles.
		$preload_styles = array(
			'aqualuxe-main'
		);

		if ( in_array( $handle, $preload_styles, true ) ) {
			$html = str_replace( "media='all'", "media='all' onload=&quot;this.onload=null;this.rel='stylesheet'&quot; rel='preload' as='style'", $html );
		}

		return $html;
	}
}