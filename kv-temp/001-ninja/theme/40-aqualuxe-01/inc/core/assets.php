<?php
/**
 * Asset management functions
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Get the asset path from mix-manifest.json
 *
 * @param string $path The path to the asset.
 * @return string The versioned asset path.
 */
function aqualuxe_asset_path( $path ) {
    static $manifest = null;
    
    if ( is_null( $manifest ) ) {
        $manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';
        
        if ( file_exists( $manifest_path ) ) {
            $manifest = json_decode( file_get_contents( $manifest_path ), true );
        } else {
            $manifest = [];
        }
    }
    
    if ( array_key_exists( $path, $manifest ) ) {
        return AQUALUXE_URI . 'assets/dist' . $manifest[$path];
    }
    
    return AQUALUXE_URI . 'assets/dist/' . ltrim( $path, '/' );
}

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts() {
    // Register and enqueue styles
    wp_enqueue_style(
        'aqualuxe-styles',
        aqualuxe_asset_path( '/css/main.css' ),
        [],
        AQUALUXE_VERSION
    );
    
    // Register and enqueue scripts
    wp_enqueue_script(
        'aqualuxe-scripts',
        aqualuxe_asset_path( '/js/main.js' ),
        ['jquery'],
        AQUALUXE_VERSION,
        true
    );
    
    // Add localized script data
    $script_data = [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'themeUri' => AQUALUXE_URI,
        'isWooCommerceActive' => aqualuxe_is_woocommerce_active(),
        'i18n' => [
            'searchPlaceholder' => esc_html__( 'Search...', 'aqualuxe' ),
            'menuToggle' => esc_html__( 'Menu', 'aqualuxe' ),
            'darkModeToggle' => esc_html__( 'Toggle Dark Mode', 'aqualuxe' ),
            'addToCart' => esc_html__( 'Add to Cart', 'aqualuxe' ),
            'viewCart' => esc_html__( 'View Cart', 'aqualuxe' ),
            'checkout' => esc_html__( 'Checkout', 'aqualuxe' ),
        ],
    ];
    
    // Add WooCommerce specific data
    if ( aqualuxe_is_woocommerce_active() ) {
        $script_data['quickViewNonce'] = wp_create_nonce( 'aqualuxe_quick_view_nonce' );
        $script_data['addToCartNonce'] = wp_create_nonce( 'woocommerce-add-to-cart' );
    }
    
    // Allow plugins and themes to filter script data
    $script_data = apply_filters( 'aqualuxe_localize_script_data', $script_data );
    
    wp_localize_script(
        'aqualuxe-scripts',
        'aqualuxeData',
        $script_data
    );
    
    // Conditionally load WooCommerce-specific assets
    if ( aqualuxe_is_woocommerce_active() ) {
        wp_enqueue_style(
            'aqualuxe-woocommerce',
            aqualuxe_asset_path( '/css/woocommerce.css' ),
            ['aqualuxe-styles'],
            AQUALUXE_VERSION
        );
        
        wp_enqueue_script(
            'aqualuxe-woocommerce',
            aqualuxe_asset_path( '/js/woocommerce.js' ),
            ['aqualuxe-scripts'],
            AQUALUXE_VERSION,
            true
        );
    }
    
    // Add comment reply script if needed
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_scripts' );

/**
 * Enqueue admin scripts and styles.
 */
function aqualuxe_admin_scripts() {
    wp_enqueue_style(
        'aqualuxe-admin-styles',
        aqualuxe_asset_path( '/css/admin.css' ),
        [],
        AQUALUXE_VERSION
    );
    
    wp_enqueue_script(
        'aqualuxe-admin-scripts',
        aqualuxe_asset_path( '/js/admin.js' ),
        ['jquery'],
        AQUALUXE_VERSION,
        true
    );
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_admin_scripts' );

/**
 * Enqueue block editor assets.
 */
function aqualuxe_block_editor_assets() {
    wp_enqueue_style(
        'aqualuxe-editor-styles',
        aqualuxe_asset_path( '/css/editor.css' ),
        [],
        AQUALUXE_VERSION
    );
}
add_action( 'enqueue_block_editor_assets', 'aqualuxe_block_editor_assets' );

/**
 * Add preload for critical assets
 */
function aqualuxe_resource_hints( $urls, $relation_type ) {
    if ( 'preconnect' === $relation_type ) {
        // Add Google Fonts if used
        // $urls[] = [
        //     'href' => 'https://fonts.gstatic.com',
        //     'crossorigin',
        // ];
    }
    
    return $urls;
}
add_filter( 'wp_resource_hints', 'aqualuxe_resource_hints', 10, 2 );

/**
 * Add async/defer attributes to enqueued scripts where needed.
 *
 * @param string $tag The script tag.
 * @param string $handle The script handle.
 * @return string Script HTML string.
 */
function aqualuxe_script_loader_tag( $tag, $handle ) {
    // Add script handles to the array below to add async/defer attributes
    $scripts_to_defer = [ 'aqualuxe-scripts' ];
    $scripts_to_async = [];
    
    if ( in_array( $handle, $scripts_to_defer, true ) ) {
        return str_replace( ' src', ' defer src', $tag );
    }
    
    if ( in_array( $handle, $scripts_to_async, true ) ) {
        return str_replace( ' src', ' async src', $tag );
    }
    
    return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_script_loader_tag', 10, 2 );