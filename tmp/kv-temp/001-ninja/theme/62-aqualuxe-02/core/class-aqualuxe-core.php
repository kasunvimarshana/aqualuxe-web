<?php
/**
 * AquaLuxe Core Class
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe Core Class
 */
class AquaLuxe_Core {
    /**
     * Instance of this class
     *
     * @var AquaLuxe_Core
     */
    private static $instance;

    /**
     * Main core instance
     *
     * @return AquaLuxe_Core
     */
    public static function instance() {
        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof AquaLuxe_Core ) ) {
            self::$instance = new AquaLuxe_Core();
            self::$instance->init_hooks();
        }
        return self::$instance;
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Register core hooks
        add_action( 'wp_enqueue_scripts', array( $this, 'register_core_assets' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_assets' ) );
        
        // Add body classes
        add_filter( 'body_class', array( $this, 'body_classes' ) );
        
        // Add pingback header
        add_action( 'wp_head', array( $this, 'pingback_header' ) );
    }

    /**
     * Register core assets
     *
     * @return void
     */
    public function register_core_assets() {
        // Core styles and scripts are registered in AquaLuxe_Assets class
    }

    /**
     * Register admin assets
     *
     * @return void
     */
    public function register_admin_assets() {
        // Admin styles and scripts are registered in AquaLuxe_Assets class
    }

    /**
     * Add custom body classes
     *
     * @param array $classes Body classes
     * @return array
     */
    public function body_classes( $classes ) {
        // Add a class if WooCommerce is active
        if ( class_exists( 'WooCommerce' ) ) {
            $classes[] = 'woocommerce-active';
        } else {
            $classes[] = 'woocommerce-inactive';
        }

        // Add a class for the dark mode
        if ( aqualuxe_is_dark_mode() ) {
            $classes[] = 'dark-mode';
        }

        // Add responsive classes
        $classes[] = 'aqualuxe-responsive';

        // Add a class if the sidebar is active
        if ( is_active_sidebar( 'sidebar-1' ) ) {
            $classes[] = 'has-sidebar';
        } else {
            $classes[] = 'no-sidebar';
        }

        return $classes;
    }

    /**
     * Add a pingback url auto-discovery header for single posts, pages, or attachments.
     *
     * @return void
     */
    public function pingback_header() {
        if ( is_singular() && pings_open() ) {
            printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
        }
    }
}