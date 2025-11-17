<?php
/**
 * AquaLuxe Theme bootstrap.
 *
 * @package AquaLuxe
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// -----------------------------------------------------------------------------
// Constants
// -----------------------------------------------------------------------------
define( 'AQLX_VERSION', '1.0.0' );
define( 'AQLX_DIR', trailingslashit( get_template_directory() ) );
define( 'AQLX_URI', trailingslashit( get_template_directory_uri() ) );
define( 'AQLX_INC', AQLX_DIR . 'inc/' );
define( 'AQLX_ASSETS_DIST', AQLX_DIR . 'assets/dist/' );
define( 'AQLX_TEXT', 'aqualuxe' );

// -----------------------------------------------------------------------------
// PSR-4 like autoloader for theme classes under inc/.
// -----------------------------------------------------------------------------
spl_autoload_register( static function ( $class ) {
    if ( strpos( $class, 'AquaLuxe\\' ) !== 0 ) {
        return;
    }
    $rel = str_replace( [ 'AquaLuxe\\', '\\' ], [ '', '/' ], $class );
    $path = AQLX_INC . $rel . '.php';
    if ( file_exists( $path ) ) {
        require_once $path;
    }
} );

// -----------------------------------------------------------------------------
// Helpers
// -----------------------------------------------------------------------------
if ( ! function_exists( 'aqlx_mix' ) ) {
    /**
     * Return versioned asset from mix-manifest.json with cache-busting.
     */
    function aqlx_mix( string $path ): string {
        $path = '/' . ltrim( $path, '/' );
        $manifest_path = AQLX_ASSETS_DIST . 'mix-manifest.json';
        static $manifest;
        if ( null === $manifest ) {
            $manifest = file_exists( $manifest_path ) ? json_decode( (string) file_get_contents( $manifest_path ), true ) : [];
        }
        $ver = isset( $manifest[ $path ] ) ? $manifest[ $path ] : $path;
        return esc_url( AQLX_URI . 'assets/dist' . $ver );
    }
}

if ( ! function_exists( 'aqlx_is_woocommerce_active' ) ) {
    function aqlx_is_woocommerce_active(): bool {
        return class_exists( '\\WooCommerce' );
    }
}

// -----------------------------------------------------------------------------
// Theme setup
// -----------------------------------------------------------------------------
add_action( 'after_setup_theme', static function () {
    function_exists( 'load_theme_textdomain' ) && call_user_func( 'load_theme_textdomain', AQLX_TEXT, AQLX_DIR . 'languages' );
    function_exists( 'add_theme_support' ) && call_user_func( 'add_theme_support', 'title-tag' );
    function_exists( 'add_theme_support' ) && call_user_func( 'add_theme_support', 'post-thumbnails' );
    function_exists( 'add_theme_support' ) && call_user_func( 'add_theme_support', 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
    function_exists( 'add_theme_support' ) && call_user_func( 'add_theme_support', 'custom-logo', [ 'height' => 80, 'width' => 240, 'flex-width' => true, 'flex-height' => true ] );
    function_exists( 'add_theme_support' ) && call_user_func( 'add_theme_support', 'woocommerce' );
    function_exists( 'add_theme_support' ) && call_user_func( 'add_theme_support', 'editor-styles' );
    function_exists( 'add_theme_support' ) && call_user_func( 'add_theme_support', 'responsive-embeds' );
    function_exists( 'register_nav_menus' ) && call_user_func( 'register_nav_menus', [
        'primary'   => __( 'Primary Menu', AQLX_TEXT ),
        'footer'    => __( 'Footer Menu', AQLX_TEXT ),
        'account'   => __( 'Account Menu', AQLX_TEXT ),
    ] );
} );

// -----------------------------------------------------------------------------
// Enqueue assets via mix-manifest.json only.
// -----------------------------------------------------------------------------
add_action( 'wp_enqueue_scripts', static function () {
    function_exists( 'wp_enqueue_style' ) && call_user_func( 'wp_enqueue_style', 'aqlx-app', aqlx_mix( 'css/app.css' ), [], AQLX_VERSION );
    function_exists( 'wp_enqueue_script' ) && call_user_func( 'wp_enqueue_script', 'aqlx-app', aqlx_mix( 'js/app.js' ), [], AQLX_VERSION, true );
    $admin_url = function_exists( 'admin_url' ) ? call_user_func( 'admin_url', 'admin-ajax.php' ) : '';
    $rest_url  = function_exists( 'rest_url' ) ? call_user_func( 'rest_url', 'aqlx/v1/' ) : '';
    $nonce     = function_exists( 'wp_create_nonce' ) ? call_user_func( 'wp_create_nonce', 'wp_rest' ) : '';
    function_exists( 'wp_localize_script' ) && call_user_func( 'wp_localize_script', 'aqlx-app', 'AQLX', [
        'ajaxUrl' => $admin_url,
        'restUrl' => $rest_url,
        'nonce'   => $nonce,
    ] );
}, 20 );

// -----------------------------------------------------------------------------
// Register sidebars
// -----------------------------------------------------------------------------
add_action( 'widgets_init', static function () {
    function_exists( 'register_sidebar' ) && call_user_func( 'register_sidebar', [
        'name'          => __( 'Primary Sidebar', AQLX_TEXT ),
        'id'            => 'primary-sidebar',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ] );
} );

// -----------------------------------------------------------------------------
// Core bootstrapping (Service container + Modules registry)
// -----------------------------------------------------------------------------
add_action( 'after_setup_theme', static function () {
    // Initialize container.
    AquaLuxe\Core\Container::init();
    // Register core services.
    AquaLuxe\Core\Container::bind( 'config', new AquaLuxe\Core\Config() );
    AquaLuxe\Core\Container::bind( 'security', new AquaLuxe\Core\Security() );
    AquaLuxe\Core\Container::bind( 'rest', new AquaLuxe\Core\RestApi() );
    AquaLuxe\Core\Container::bind( 'templates', new AquaLuxe\Core\Templates() );
    AquaLuxe\Core\Container::bind( 'seo', new AquaLuxe\Core\Seo() );
    AquaLuxe\Core\Container::bind( 'customizer', new AquaLuxe\Core\Customizer() );
    AquaLuxe\Core\Container::bind( 'demo_importer', new AquaLuxe\Core\DemoImporter() );

    // Load enabled modules from config.
    $modules = AquaLuxe\Core\Container::get( 'config' )->enabled_modules();
    foreach ( $modules as $module_class ) {
        if ( class_exists( $module_class ) ) {
            $module = new $module_class();
            if ( $module instanceof AquaLuxe\Core\Contracts\Module ) {
                $module->boot();
            }
        }
    }
}, 15 );

// -----------------------------------------------------------------------------
// Security headers and hardening.
// -----------------------------------------------------------------------------
add_action( 'send_headers', static function () {
    header( 'X-Content-Type-Options: nosniff' );
    header( 'X-Frame-Options: SAMEORIGIN' );
    header( 'Referrer-Policy: strict-origin-when-cross-origin' );
    header( 'X-XSS-Protection: 0' );
    header( 'Permissions-Policy: geolocation=(self), camera=()' );
} );

// -----------------------------------------------------------------------------
// WooCommerce graceful fallbacks
// -----------------------------------------------------------------------------
if ( ! aqlx_is_woocommerce_active() ) {
    add_filter( 'body_class', static function ( $classes ) { $classes[] = 'aqlx-no-wc'; return $classes; } );
}

// -----------------------------------------------------------------------------
// Admin: Demo Importer page (lightweight UI, full features in class)
// -----------------------------------------------------------------------------
add_action( 'admin_menu', static function () {
    if ( function_exists( 'add_menu_page' ) ) {
        call_user_func( 'add_menu_page',
            __( 'AquaLuxe Setup', AQLX_TEXT ),
            __( 'AquaLuxe', AQLX_TEXT ),
            'manage_options',
            'aqlx-setup',
            function () {
                echo '<div class="wrap"><h1>' . esc_html__( 'AquaLuxe Demo Importer', AQLX_TEXT ) . '</h1>';
                echo '<div id="aqlx-importer" class="aqlx-admin-card"></div>';
                echo '</div>';
            },
            'dashicons-palmtree',
            3
        );
    }
} );

// Admin assets: enqueue only our importer JS on our page
add_action( 'admin_enqueue_scripts', static function ( $hook ) {
    if ( isset( $_GET['page'] ) && $_GET['page'] === 'aqlx-setup' ) {
    function_exists( 'wp_enqueue_script' ) && call_user_func( 'wp_enqueue_script', 'aqlx-admin', aqlx_mix( 'js/admin.js' ), [], AQLX_VERSION, true );
    $ajax_url = function_exists( 'admin_url' ) ? call_user_func( 'admin_url', 'admin-ajax.php' ) : '';
    $nonce    = function_exists( 'wp_create_nonce' ) ? call_user_func( 'wp_create_nonce', 'wp_rest' ) : '';
    function_exists( 'wp_localize_script' ) && call_user_func( 'wp_localize_script', 'aqlx-admin', 'AQLX', [ 'ajaxUrl' => $ajax_url, 'nonce' => $nonce ] );
    }
} );

// Newsletter non-JS fallback handling (progressive enhancement)
add_action( 'init', static function () {
    if ( isset( $_POST['newsletter_email'], $_POST['aqlx_newsletter_nonce'] ) && function_exists( 'wp_verify_nonce' ) && call_user_func( 'wp_verify_nonce', $_POST['aqlx_newsletter_nonce'], 'aqlx_newsletter' ) ) {
        $raw   = function_exists('wp_unslash') ? call_user_func('wp_unslash', $_POST['newsletter_email'] ) : $_POST['newsletter_email'];
        $email = function_exists('sanitize_email') ? call_user_func('sanitize_email', $raw ) : $raw;
        if ( function_exists('is_email') && call_user_func('is_email', $email ) ) {
            // For demo, store transient. In production, integrate with provider via REST.
            $day = ( function_exists('defined') && defined('DAY_IN_SECONDS') ) ? constant('DAY_IN_SECONDS') : 86400;
            function_exists('set_transient') && call_user_func( 'set_transient', 'aqlx_newsletter_' . md5( (string) $email ), time(), $day );
            $refer = function_exists('wp_get_referer') ? call_user_func('wp_get_referer') : '';
            $home  = function_exists('home_url') ? call_user_func('home_url', '/' ) : '/';
            $dest  = function_exists('add_query_arg') ? call_user_func('add_query_arg', 'subscribed', '1', $refer ?: $home ) : $home;
            if ( function_exists('wp_safe_redirect') ) { call_user_func('wp_safe_redirect', $dest ); exit; }
        }
    }
} );
