<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Define theme constants
 */
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_DIR', get_template_directory() );
define( 'AQUALUXE_URI', get_template_directory_uri() );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
require AQUALUXE_DIR . '/inc/setup.php';

/**
 * Register and enqueue styles and scripts.
 */
require AQUALUXE_DIR . '/inc/assets.php';

/**
 * Theme hooks system.
 */
require AQUALUXE_DIR . '/inc/hooks.php';

/**
 * Custom template functions for this theme.
 */
require AQUALUXE_DIR . '/inc/template-functions.php';

/**
 * Custom template tags for this theme.
 */
require AQUALUXE_DIR . '/inc/template-tags.php';

/**
 * Navigation related functions and custom walkers.
 */
require AQUALUXE_DIR . '/inc/navigation.php';

/**
 * Customizer additions.
 */
require AQUALUXE_DIR . '/inc/customizer.php';

/**
 * Widget areas and custom widgets.
 */
require AQUALUXE_DIR . '/inc/widgets.php';

/**
 * Multilingual support.
 */
require AQUALUXE_DIR . '/inc/multilingual.php';

/**
 * Demo content importer.
 */
require AQUALUXE_DIR . '/inc/demo-importer.php';

/**
 * Homepage functions.
 */
require AQUALUXE_DIR . '/inc/homepage-functions.php';

/**
 * WooCommerce integration.
 */
if ( class_exists( 'WooCommerce' ) ) {
    require AQUALUXE_DIR . '/inc/woocommerce/woocommerce.php';
    require AQUALUXE_DIR . '/inc/woocommerce/template-functions.php';
    require AQUALUXE_DIR . '/inc/woocommerce/template-hooks.php';
    require AQUALUXE_DIR . '/inc/woocommerce/quick-view.php';
    require AQUALUXE_DIR . '/inc/woocommerce/wishlist.php';
    require AQUALUXE_DIR . '/inc/woocommerce/advanced-filters.php';
    require AQUALUXE_DIR . '/inc/woocommerce/multi-currency.php';
}

/**
 * Testing and optimization tools.
 * Only load in admin area to avoid frontend performance impact.
 */
if ( is_admin() ) {
    require AQUALUXE_DIR . '/inc/testing.php';
}