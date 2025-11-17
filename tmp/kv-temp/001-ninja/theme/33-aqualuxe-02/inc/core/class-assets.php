<?php
/**
 * Assets Management Class
 *
 * @package AquaLuxe
 * @subpackage Core
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Assets Management Class
 *
 * This class handles all asset loading and management.
 */
class Assets extends Service {

	/**
	 * Initialize the service
	 *
	 * @return void
	 */
	public function initialize() {
		$this->register_hooks();
	}

	/**
	 * Register hooks
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
		add_action( 'wp_head', array( $this, 'add_preload_links' ), 1 );
		add_filter( 'script_loader_tag', array( $this, 'add_async_defer_attributes' ), 10, 2 );
		add_filter( 'style_loader_tag', array( $this, 'add_preload_attributes' ), 10, 4 );
	}

	/**
	 * Enqueue frontend assets
	 *
	 * @return void
	 */
	public function enqueue_frontend_assets() {
		// Register and enqueue styles.
		wp_register_style( 'aqualuxe-main', AQUALUXE_ASSETS_URI . 'css/main.css', array(), AQUALUXE_VERSION );
		wp_enqueue_style( 'aqualuxe-main' );

		// Add RTL support.
		wp_style_add_data( 'aqualuxe-main', 'rtl', 'replace' );

		// Register and enqueue scripts.
		wp_register_script( 'aqualuxe-vendors', AQUALUXE_ASSETS_URI . 'js/vendors.js', array(), AQUALUXE_VERSION, true );
		wp_register_script( 'aqualuxe-main', AQUALUXE_ASSETS_URI . 'js/main.js', array( 'jquery', 'aqualuxe-vendors' ), AQUALUXE_VERSION, true );
		
		wp_enqueue_script( 'aqualuxe-vendors' );
		wp_enqueue_script( 'aqualuxe-main' );

		// Localize the script with new data.
		wp_localize_script(
			'aqualuxe-main',
			'aqualuxeVars',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'aqualuxe-nonce' ),
				'is_rtl'  => is_rtl(),
				'theme_url' => AQUALUXE_URI,
			)
		);

		// Enqueue comment reply script if needed.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Enqueue WooCommerce specific assets if WooCommerce is active.
		if ( $this->is_woocommerce_active() ) {
			$this->enqueue_woocommerce_assets();
		}
	}

	/**
	 * Enqueue WooCommerce assets
	 *
	 * @return void
	 */
	public function enqueue_woocommerce_assets() {
		wp_register_style( 'aqualuxe-woocommerce', AQUALUXE_ASSETS_URI . 'css/woocommerce.css', array( 'aqualuxe-main' ), AQUALUXE_VERSION );
		wp_register_script( 'aqualuxe-woocommerce', AQUALUXE_ASSETS_URI . 'js/woocommerce.js', array( 'jquery', 'aqualuxe-main' ), AQUALUXE_VERSION, true );
		
		wp_enqueue_style( 'aqualuxe-woocommerce' );
		wp_enqueue_script( 'aqualuxe-woocommerce' );

		// Localize the script with WooCommerce specific data.
		wp_localize_script(
			'aqualuxe-woocommerce',
			'aqualuxeWooVars',
			array(
				'ajax_url'                => admin_url( 'admin-ajax.php' ),
				'wc_ajax_url'             => WC_AJAX::get_endpoint( '%%endpoint%%' ),
				'cart_url'                => wc_get_cart_url(),
				'checkout_url'            => wc_get_checkout_url(),
				'is_cart'                 => is_cart(),
				'is_checkout'             => is_checkout(),
				'is_product'              => is_product(),
				'is_shop'                 => is_shop(),
				'cart_redirect_after_add' => get_option( 'woocommerce_cart_redirect_after_add' ),
				'enable_ajax_add_to_cart' => 'yes' === get_option( 'woocommerce_enable_ajax_add_to_cart' ),
				'i18n_view_cart'          => esc_html__( 'View cart', 'aqualuxe' ),
				'i18n_add_to_cart'        => esc_html__( 'Add to cart', 'aqualuxe' ),
				'i18n_added_to_cart'      => esc_html__( 'Added to cart', 'aqualuxe' ),
				'i18n_adding_to_cart'     => esc_html__( 'Adding to cart...', 'aqualuxe' ),
			)
		);
	}

	/**
	 * Enqueue admin assets
	 *
	 * @param string $hook Current admin page hook.
	 * @return void
	 */
	public function enqueue_admin_assets( $hook ) {
		wp_register_style( 'aqualuxe-admin', AQUALUXE_ASSETS_URI . 'css/admin.css', array(), AQUALUXE_VERSION );
		wp_register_script( 'aqualuxe-admin', AQUALUXE_ASSETS_URI . 'js/admin.js', array( 'jquery' ), AQUALUXE_VERSION, true );
		
		wp_enqueue_style( 'aqualuxe-admin' );
		wp_enqueue_script( 'aqualuxe-admin' );

		// Localize the script with admin specific data.
		wp_localize_script(
			'aqualuxe-admin',
			'aqualuxeAdminVars',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'aqualuxe-admin-nonce' ),
			)
		);
	}

	/**
	 * Enqueue block editor assets
	 *
	 * @return void
	 */
	public function enqueue_block_editor_assets() {
		wp_register_style( 'aqualuxe-editor', AQUALUXE_ASSETS_URI . 'css/editor.css', array(), AQUALUXE_VERSION );
		wp_register_script( 'aqualuxe-editor', AQUALUXE_ASSETS_URI . 'js/editor.js', array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ), AQUALUXE_VERSION, true );
		
		wp_enqueue_style( 'aqualuxe-editor' );
		wp_enqueue_script( 'aqualuxe-editor' );
	}

	/**
	 * Add preload links to head
	 *
	 * @return void
	 */
	public function add_preload_links() {
		// Preload fonts.
		echo '<link rel="preload" href="' . esc_url( AQUALUXE_ASSETS_URI . 'fonts/inter-var.woff2' ) . '" as="font" type="font/woff2" crossorigin>';
		
		// Preload critical CSS.
		echo '<link rel="preload" href="' . esc_url( AQUALUXE_ASSETS_URI . 'css/critical.css' ) . '" as="style">';
		
		// Preconnect to external domains.
		echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
		echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
		
		// DNS prefetch.
		echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">';
		echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">';
		echo '<link rel="dns-prefetch" href="//ajax.googleapis.com">';
		
		// Inline critical CSS.
		$critical_css_path = AQUALUXE_ASSETS_DIR . 'css/critical.css';
		if ( file_exists( $critical_css_path ) ) {
			$critical_css = file_get_contents( $critical_css_path );
			if ( $critical_css ) {
				echo '<style id="aqualuxe-critical-css">' . $critical_css . '</style>';
			}
		}
	}

	/**
	 * Add async/defer attributes to script tags
	 *
	 * @param string $tag Script tag.
	 * @param string $handle Script handle.
	 * @return string
	 */
	public function add_async_defer_attributes( $tag, $handle ) {
		// Add async attribute to non-critical scripts.
		$async_scripts = array( 'aqualuxe-vendors' );
		if ( in_array( $handle, $async_scripts, true ) ) {
			return str_replace( ' src', ' async src', $tag );
		}

		// Add defer attribute to non-critical scripts.
		$defer_scripts = array( 'aqualuxe-main' );
		if ( in_array( $handle, $defer_scripts, true ) ) {
			return str_replace( ' src', ' defer src', $tag );
		}

		return $tag;
	}

	/**
	 * Add preload attributes to style tags
	 *
	 * @param string $html Style tag.
	 * @param string $handle Style handle.
	 * @param string $href Style URL.
	 * @param string $media Media attribute.
	 * @return string
	 */
	public function add_preload_attributes( $html, $handle, $href, $media ) {
		// Add preload attribute to critical styles.
		$preload_styles = array( 'aqualuxe-critical' );
		if ( in_array( $handle, $preload_styles, true ) ) {
			$html = str_replace( "rel='stylesheet'", "rel='preload' as='style' onload=&quot;this.onload=null;this.rel='stylesheet'&quot;", $html );
			$html .= "<noscript><link rel='stylesheet' href='" . esc_url( $href ) . "' media='" . esc_attr( $media ) . "'></noscript>";
		}

		return $html;
	}
}