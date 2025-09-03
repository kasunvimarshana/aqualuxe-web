<?php
/**
 * Theme bootstrap.
 *
 * @package Aqualuxe
 */

defined('ABSPATH') || exit;

// Load Composer autoloader if available (plugins may also provide it in mu-plugins).
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Simple PSR-4 like autoloader fallback for the theme's App namespace.
spl_autoload_register( static function ( $class ) {
    $prefix = 'Aqualuxe\\';
    if ( strncmp( $prefix, $class, strlen( $prefix ) ) !== 0 ) {
        return;
    }
    $relative = str_replace( '\\', '/', substr( $class, strlen( $prefix ) ) );
    $file = __DIR__ . '/app/' . $relative . '.php';
    if ( file_exists( $file ) ) {
        require_once $file;
    }
});

// Define constants.
define( 'AQUALUXE_VERSION', '0.1.0' );
define( 'AQUALUXE_PATH', trailingslashit( get_template_directory() ) );
define( 'AQUALUXE_URI', trailingslashit( get_template_directory_uri() ) );

// Theme setup.
add_action( 'after_setup_theme', static function () {
    // i18n
    load_theme_textdomain( 'aqualuxe', AQUALUXE_PATH . 'languages' );

    // Theme supports
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ] );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/dist/css/editor.css' );

    // Menus
    register_nav_menus([
        'primary' => __( 'Primary Menu', 'aqualuxe' ),
        'footer'  => __( 'Footer Menu', 'aqualuxe' ),
        'account' => __( 'Account Menu', 'aqualuxe' )
    ]);
});

// Enqueue assets with cache busting.
add_action( 'wp_enqueue_scripts', static function () {
    // Resolve hashed assets via manifests or smart fallback
    $css    = aqlx_asset( 'css/style.css' );
    $vendor = aqlx_asset( 'js/vendor.js' );
    $js     = aqlx_asset( 'js/app.js' );

    wp_enqueue_style( 'aqualuxe-style', $css, [], AQUALUXE_VERSION, 'all' );
    wp_enqueue_script( 'aqualuxe-vendor', $vendor, [], AQUALUXE_VERSION, true );
    wp_enqueue_script( 'aqualuxe-app', $js, [ 'aqualuxe-vendor' ], AQUALUXE_VERSION, true );

    // Pass localized data, nonce for AJAX.
    wp_add_inline_script( 'aqualuxe-app', 'window.Aqualuxe = window.Aqualuxe || {}; window.Aqualuxe.ajaxUrl = ' . wp_json_encode( admin_url( 'admin-ajax.php' ) ) . '; window.Aqualuxe.nonce = ' . wp_json_encode( wp_create_nonce( 'aqlx_ajax' ) ) . ';', 'before' );
});

// Register sidebars.
add_action( 'widgets_init', static function () {
    register_sidebar([
        'name'          => __( 'Sidebar', 'aqualuxe' ),
        'id'            => 'sidebar-1',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ]);
});

// Security hardening & headers.
add_action( 'send_headers', static function () {
    header( 'X-Content-Type-Options: nosniff' );
    header( 'X-Frame-Options: SAMEORIGIN' );
    header( 'Referrer-Policy: no-referrer-when-downgrade' );
});

// Initialize core services.
add_action( 'init', static function () {
    // Multitenancy: allow per-site overrides via child themes or mu-plugins.
    if ( is_multisite() ) {
        // Example hook for per-site customization.
        do_action( 'aqlx/multisite/init', get_current_blog_id() );
    }

    // Load modules.
    \Aqualuxe\Core\Modules::boot();
});

// Helper: WooCommerce activation state
if ( ! function_exists( 'is_woocommerce_activated' ) ) {
    function is_woocommerce_activated(): bool {
        return class_exists( 'WooCommerce' );
    }
}

// Helper to resolve Laravel Mix versioned asset paths.
if ( ! function_exists( 'aqlx_mix' ) ) {
    function aqlx_mix( string $path ): string {
        $path = '/' . ltrim( $path, '/' );
        $manifest = AQUALUXE_PATH . 'assets/dist/mix-manifest.json';
        if ( file_exists( $manifest ) ) {
            $map = json_decode( (string) file_get_contents( $manifest ), true );
            if ( is_array( $map ) && isset( $map[ $path ] ) ) {
                return AQUALUXE_URI . 'assets/dist' . $map[ $path ];
            }
        }
        return AQUALUXE_URI . 'assets/dist' . $path;
    }
}

// Helper to resolve an asset path using Mix manifest first, then webpack manifest.json.
if ( ! function_exists( 'aqlx_asset' ) ) {
    function aqlx_asset( string $relative ): string {
        $relative = ltrim( $relative, '/' );
        $mix_manifest = AQUALUXE_PATH . 'assets/dist/mix-manifest.json';
        $wp_manifest  = AQUALUXE_PATH . 'assets/dist/manifest.json';
        // Try Mix
        if ( file_exists( $mix_manifest ) ) {
            $map = json_decode( (string) file_get_contents( $mix_manifest ), true );
            $key = '/' . $relative;
            if ( is_array( $map ) && isset( $map[ $key ] ) ) {
                return AQUALUXE_URI . 'assets/dist' . $map[ $key ];
            }
        }
        // Try webpack manifest
        if ( file_exists( $wp_manifest ) ) {
            $map = json_decode( (string) file_get_contents( $wp_manifest ), true );
            if ( is_array( $map ) && isset( $map[ $relative ] ) ) {
                return AQUALUXE_URI . $map[ $relative ];
            }
        }
        // Smart fallback: try to locate hashed file in dist
        $dist_dir = AQUALUXE_PATH . 'assets/dist/';
        $dir  = dirname( $relative );
        $file = basename( $relative );
        $dot  = strrpos( $file, '.' );
        $name = $dot !== false ? substr( $file, 0, $dot ) : $file;
        $ext  = $dot !== false ? substr( $file, $dot + 1 ) : '';
        $pattern = $dist_dir . $dir . '/' . $name . '.*.' . $ext;
        $candidates = glob( $pattern );
        if ( $candidates ) {
            // Prefer non-map file and longest hash name
            $candidates = array_values( array_filter( $candidates, static function( $p ) { return substr( $p, -4 ) !== '.map'; } ) );
            if ( $candidates ) {
                usort( $candidates, static function( $a, $b ) { return strlen( $b ) <=> strlen( $a ); } );
                $picked = $candidates[0];
                $rel = str_replace( AQUALUXE_PATH, '', $picked );
                return AQUALUXE_URI . $rel;
            }
        }
        // Fallback to direct non-hashed path
        return AQUALUXE_URI . 'assets/dist/' . $relative;
    }
}

// REST API and AJAX endpoints.
add_action( 'rest_api_init', static function () {
    ( new \Aqualuxe\API\Routes() )->register();
});

add_action( 'wp_ajax_aqlx_action', [ '\\Aqualuxe\\API\\Ajax', 'handle' ] );
add_action( 'wp_ajax_nopriv_aqlx_action', [ '\\Aqualuxe\\API\\Ajax', 'handle' ] );
