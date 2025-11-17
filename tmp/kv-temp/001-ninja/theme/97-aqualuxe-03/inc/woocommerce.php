<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce setup and overrides.
 */
class AquaLuxe_WooCommerce {

	/**
	 * Register hooks.
	 */
	public function register() {
		// General setup is handled in AquaLuxe_Theme class
		
		// Remove default WooCommerce wrappers.
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
        remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

		// Add theme-specific wrappers.
		add_action( 'woocommerce_before_main_content', array( $this, 'theme_wrapper_start' ), 10 );
		add_action( 'woocommerce_after_main_content', array( $this, 'theme_wrapper_end' ), 10 );

        // Load custom functions
        $this->load_functions();
	}

    /**
     * Load custom WooCommerce functions.
     */
    private function load_functions() {
        // This file is not created yet, but will hold our custom functions
        // require_once get_template_directory() . '/inc/woocommerce-functions.php';
    }

	/**
	 * Before Content Wrapper.
	 */
	public function theme_wrapper_start() {
        ?>
        <div class="container mx-auto my-8 px-4 sm:px-6 lg:px-8">
            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">
        <?php
	}

	/**
	 * After Content Wrapper.
	 */
	public function theme_wrapper_end() {
        ?>
                </main><!-- #main -->
            </div><!-- #primary -->
        </div><!-- .container -->
        <?php
	}
}

