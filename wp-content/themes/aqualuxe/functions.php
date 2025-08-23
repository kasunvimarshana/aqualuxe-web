<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Define theme constants
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_DIR', trailingslashit( get_template_directory() ) );
define( 'AQUALUXE_URI', trailingslashit( get_template_directory_uri() ) );
define( 'AQUALUXE_ASSETS_URI', trailingslashit( AQUALUXE_URI . 'assets/dist' ) );
define( 'AQUALUXE_INC_DIR', trailingslashit( AQUALUXE_DIR . 'inc' ) );
define( 'AQUALUXE_MODULES_DIR', trailingslashit( AQUALUXE_DIR . 'modules' ) );

/**
 * Autoloader for theme classes
 *
 * @param string $class_name The class name to load
 * @return void
 */
function aqualuxe_autoloader( $class_name ) {
    // Only handle our own classes
    if ( strpos( $class_name, 'AquaLuxe\\' ) !== 0 ) {
        return;
    }

    // Remove the namespace prefix
    $class_name = str_replace( 'AquaLuxe\\', '', $class_name );
    
    // Convert class name format to file name format
    $class_file = strtolower( str_replace( '_', '-', str_replace( '\\', '/', $class_name ) ) );
    
    // Build the file path
    $file_path = AQUALUXE_INC_DIR . 'class-' . $class_file . '.php';
    
    // Check if the file exists in the inc directory
    if ( file_exists( $file_path ) ) {
        require_once $file_path;
        return;
    }
    
    // Check if it's a module class
    if ( strpos( $class_name, 'Modules\\' ) === 0 ) {
        $module_class = str_replace( 'Modules\\', '', $class_name );
        $module_parts = explode( '\\', $module_class );
        $module_name = strtolower( $module_parts[0] );
        
        array_shift( $module_parts );
        $class_path = implode( '/', array_map( 'strtolower', $module_parts ) );
        
        if ( !empty( $class_path ) ) {
            $file_path = AQUALUXE_MODULES_DIR . $module_name . '/inc/class-' . strtolower( str_replace( '_', '-', $class_path ) ) . '.php';
        } else {
            $file_path = AQUALUXE_MODULES_DIR . $module_name . '/class-' . $module_name . '.php';
        }
        
        if ( file_exists( $file_path ) ) {
            require_once $file_path;
        }
    }
}

// Register the autoloader
spl_autoload_register( 'aqualuxe_autoloader' );

// Load the theme core
require_once AQUALUXE_INC_DIR . 'core/class-theme.php';

// Initialize the theme
AquaLuxe\Core\Theme::get_instance();

// --- WooCommerce Product Quick View Integration ---
add_action( 'wp_enqueue_scripts', function() {
    // Enqueue quick view JS (adjust path if needed)
    wp_enqueue_script(
        'aqualuxe-quick-view',
        AQUALUXE_URI . 'assets/dist/js/quick-view.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );
    // Localize script for AJAX
    wp_localize_script('aqualuxe-quick-view', 'aqualuxe_quick_view', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'i18n_quick_view' => __('Quick View', 'aqualuxe'),
    ));
});

add_action( 'wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax' );
add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax' );

/**
 * AJAX handler for WooCommerce product quick view
 */
function aqualuxe_quick_view_ajax() {
    if ( ! isset( $_POST['product_id'] ) ) {
        wp_send_json_error( array( 'error' => 'Missing product ID.' ) );
    }
    $product_id = absint( $_POST['product_id'] );
    $product = wc_get_product( $product_id );
    if ( ! $product ) {
        wp_send_json_error( array( 'error' => 'Product not found.' ) );
    }
    // Render quick view template (adjust path if needed)
    ob_start();
    wc_get_template( 'quick-view.php', array( 'product' => $product ), '', get_template_directory() . '/woocommerce/' );
    $html = ob_get_clean();
    wp_send_json_success( array( 'html' => $html ) );
}