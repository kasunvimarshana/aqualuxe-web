<?php
/**
 * AquaLuxe Child Theme functions and definitions
 *
 * @package AquaLuxe_Child
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define constants
 */
define( 'AQUALUXE_CHILD_VERSION', '1.0.0' );
define( 'AQUALUXE_CHILD_DIR', trailingslashit( get_stylesheet_directory() ) );
define( 'AQUALUXE_CHILD_URI', trailingslashit( get_stylesheet_directory_uri() ) );

/**
 * Enqueue parent and child theme styles
 */
function aqualuxe_child_enqueue_styles() {
    // Enqueue parent theme's style
    wp_enqueue_style( 
        'aqualuxe-style', 
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme( 'aqualuxe' )->get( 'Version' )
    );
    
    // Enqueue child theme's style
    wp_enqueue_style( 
        'aqualuxe-child-style',
        get_stylesheet_uri(),
        array( 'aqualuxe-style' ),
        AQUALUXE_CHILD_VERSION
    );
    
    // Enqueue child theme's custom script if it exists
    if ( file_exists( AQUALUXE_CHILD_DIR . 'assets/js/custom.js' ) ) {
        wp_enqueue_script(
            'aqualuxe-child-custom',
            AQUALUXE_CHILD_URI . 'assets/js/custom.js',
            array( 'jquery' ),
            AQUALUXE_CHILD_VERSION,
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles' );

/**
 * Add custom image sizes (example)
 */
function aqualuxe_child_add_image_sizes() {
    // Add a custom image size for featured products
    add_image_size( 'aqualuxe-child-featured-product', 600, 600, true );
}
add_action( 'after_setup_theme', 'aqualuxe_child_add_image_sizes' );

/**
 * Add custom body classes
 */
function aqualuxe_child_body_classes( $classes ) {
    // Add a class to identify the child theme
    $classes[] = 'aqualuxe-child-theme';
    
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_child_body_classes' );

/**
 * Example of how to override a parent theme function
 * Uncomment and modify as needed
 */
/*
function aqualuxe_custom_header_style() {
    // Your custom header style implementation
}
add_action( 'wp_head', 'aqualuxe_custom_header_style', 20 );
*/

/**
 * Example of how to add a custom WooCommerce feature
 * Only runs if WooCommerce is active
 */
function aqualuxe_child_woocommerce_setup() {
    if ( class_exists( 'WooCommerce' ) ) {
        // Add custom WooCommerce functionality here
        
        // Example: Add a custom badge to featured products
        function aqualuxe_child_featured_product_badge() {
            global $product;
            
            if ( $product && $product->is_featured() ) {
                echo '<span class="featured-badge">Featured</span>';
            }
        }
        add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_child_featured_product_badge', 10 );
    }
}
add_action( 'after_setup_theme', 'aqualuxe_child_woocommerce_setup' );

/**
 * Include additional custom functionality files
 * Uncomment and modify as needed
 */
/*
// Include custom widgets
require_once AQUALUXE_CHILD_DIR . 'inc/widgets.php';

// Include custom shortcodes
require_once AQUALUXE_CHILD_DIR . 'inc/shortcodes.php';
*/