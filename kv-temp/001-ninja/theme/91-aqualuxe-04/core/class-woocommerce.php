<?php
/**
 * WooCommerce Compatibility.
 *
 * @package AquaLuxe
 * @since   1.1.0
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! \defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WooCommerce
 */
class WooCommerce {

	/**
	 * WooCommerce constructor.
	 */
	public function __construct() {
		\add_action( 'after_setup_theme', [ $this, 'setup' ] );
		\add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		\remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		\remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

		\add_action( 'woocommerce_before_main_content', [ $this, 'wrapper_before' ] );
		\add_action( 'woocommerce_after_main_content', [ $this, 'wrapper_after' ] );
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	public function setup(): void {
		\add_theme_support( 'woocommerce' );
		\add_theme_support( 'wc-product-gallery-zoom' );
		\add_theme_support( 'wc-product-gallery-lightbox' );
		\add_theme_support( 'wc-product-gallery-slider' );
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts(): void {
		// You can add WooCommerce specific styles here.
	}

	/**
	 * Before Content.
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 */
	public function wrapper_before(): void {
		?>
		<main id="primary" class="site-main">
		<?php
	}

	/**
	 * After Content.
	 *
	 * Closes the wrapping divs.
	 */
	public function wrapper_after(): void {
		?>
		</main><!-- #main -->
		<?php
	}
}
