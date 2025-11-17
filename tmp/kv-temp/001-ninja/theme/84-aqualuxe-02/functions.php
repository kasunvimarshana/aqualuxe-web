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
    $manifest_path = AQUALUXE_PATH . 'assets/dist/manifest.json';
    $manifest = [];
    if ( file_exists( $manifest_path ) ) {
        $manifest = json_decode( (string) file_get_contents( $manifest_path ), true ) ?: [];
    }

    $css = $manifest['css/style.css'] ?? 'assets/dist/css/style.css';
    $js  = $manifest['js/app.js'] ?? 'assets/dist/js/app.js';

    wp_enqueue_style( 'aqualuxe-style', AQUALUXE_URI . $css, [], AQUALUXE_VERSION, 'all' );
    wp_enqueue_script( 'aqualuxe-vendor', AQUALUXE_URI . ( $manifest['js/vendor.js'] ?? 'assets/dist/js/vendor.js' ), [], AQUALUXE_VERSION, true );
    wp_enqueue_script( 'aqualuxe-app', AQUALUXE_URI . $js, [ 'aqualuxe-vendor' ], AQUALUXE_VERSION, true );

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

// REST API and AJAX endpoints.
add_action( 'rest_api_init', static function () {
    ( new \Aqualuxe\API\Routes() )->register();
});

add_action( 'wp_ajax_aqlx_action', [ '\\Aqualuxe\\API\\Ajax', 'handle' ] );
add_action( 'wp_ajax_nopriv_aqlx_action', [ '\\Aqualuxe\\API\\Ajax', 'handle' ] );
