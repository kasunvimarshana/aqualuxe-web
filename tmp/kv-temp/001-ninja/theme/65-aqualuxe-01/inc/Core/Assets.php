<?php
/**
 * Assets Class
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core;

/**
 * Assets Class
 * 
 * Handles all asset loading for the theme
 */
class Assets {
    /**
     * Instance of this class
     *
     * @var Assets
     */
    private static $instance = null;

    /**
     * Get the singleton instance
     *
     * @return Assets
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Register hooks
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
        add_action( 'enqueue_block_editor_assets', [ $this, 'block_editor_assets' ] );
    }

    /**
     * Get the manifest file
     *
     * @return array
     */
    private function get_manifest() {
        static $manifest = null;

        if ( null === $manifest ) {
            $manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';
            
            if ( file_exists( $manifest_path ) ) {
                $manifest = json_decode( file_get_contents( $manifest_path ), true );
            } else {
                $manifest = [];
            }
        }

        return $manifest;
    }

    /**
     * Get the asset path from the manifest
     *
     * @param string $path Asset path
     * @return string
     */
    private function get_asset_path( $path ) {
        $manifest = $this->get_manifest();
        
        if ( isset( $manifest[ $path ] ) ) {
            return $manifest[ $path ];
        }
        
        return $path;
    }

    /**
     * Enqueue styles
     */
    public function enqueue_styles() {
        // Main stylesheet
        wp_enqueue_style(
            'aqualuxe-styles',
            AQUALUXE_ASSETS_URI . 'css' . $this->get_asset_path( '/css/main.css' ),
            [],
            AQUALUXE_VERSION
        );

        // Dark mode stylesheet
        if ( DarkMode::get_instance()->is_dark_mode_enabled() ) {
            wp_enqueue_style(
                'aqualuxe-dark-mode',
                AQUALUXE_ASSETS_URI . 'css' . $this->get_asset_path( '/css/dark-mode.css' ),
                [ 'aqualuxe-styles' ],
                AQUALUXE_VERSION
            );
        }

        // WooCommerce styles
        if ( class_exists( 'WooCommerce' ) ) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URI . 'css' . $this->get_asset_path( '/css/woocommerce.css' ),
                [ 'aqualuxe-styles' ],
                AQUALUXE_VERSION
            );
        }

        // RTL styles
        if ( is_rtl() ) {
            wp_enqueue_style(
                'aqualuxe-rtl',
                AQUALUXE_ASSETS_URI . 'css' . $this->get_asset_path( '/css/rtl.css' ),
                [ 'aqualuxe-styles' ],
                AQUALUXE_VERSION
            );
        }
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        // Main script
        wp_enqueue_script(
            'aqualuxe-scripts',
            AQUALUXE_ASSETS_URI . 'js' . $this->get_asset_path( '/js/main.js' ),
            [ 'jquery' ],
            AQUALUXE_VERSION,
            true
        );

        // Localize script
        wp_localize_script(
            'aqualuxe-scripts',
            'aqualuxeData',
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'aqualuxe-nonce' ),
                'isRtl'   => is_rtl(),
                'darkMode' => [
                    'enabled' => DarkMode::get_instance()->is_dark_mode_enabled(),
                    'auto'    => DarkMode::get_instance()->is_auto_dark_mode(),
                ],
            ]
        );

        // Comment reply script
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }

        // WooCommerce scripts
        if ( class_exists( 'WooCommerce' ) ) {
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URI . 'js' . $this->get_asset_path( '/js/woocommerce.js' ),
                [ 'jquery', 'aqualuxe-scripts' ],
                AQUALUXE_VERSION,
                true
            );

            // Localize WooCommerce script
            wp_localize_script(
                'aqualuxe-woocommerce',
                'aqualuxeWoocommerce',
                [
                    'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
                    'nonce'         => wp_create_nonce( 'aqualuxe-woocommerce-nonce' ),
                    'cartUrl'       => wc_get_cart_url(),
                    'checkoutUrl'   => wc_get_checkout_url(),
                    'isCart'        => is_cart(),
                    'isCheckout'    => is_checkout(),
                    'isAccount'     => is_account_page(),
                    'isProduct'     => is_product(),
                    'isShop'        => is_shop(),
                ]
            );
        }
    }

    /**
     * Enqueue admin scripts
     */
    public function admin_enqueue_scripts() {
        // Admin styles
        wp_enqueue_style(
            'aqualuxe-admin-styles',
            AQUALUXE_ASSETS_URI . 'css' . $this->get_asset_path( '/css/admin.css' ),
            [],
            AQUALUXE_VERSION
        );

        // Admin scripts
        wp_enqueue_script(
            'aqualuxe-admin-scripts',
            AQUALUXE_ASSETS_URI . 'js' . $this->get_asset_path( '/js/admin.js' ),
            [ 'jquery' ],
            AQUALUXE_VERSION,
            true
        );

        // Localize admin script
        wp_localize_script(
            'aqualuxe-admin-scripts',
            'aqualuxeAdmin',
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'aqualuxe-admin-nonce' ),
            ]
        );
    }

    /**
     * Enqueue block editor assets
     */
    public function block_editor_assets() {
        // Editor styles
        wp_enqueue_style(
            'aqualuxe-editor-styles',
            AQUALUXE_ASSETS_URI . 'css' . $this->get_asset_path( '/css/editor.css' ),
            [],
            AQUALUXE_VERSION
        );

        // Editor scripts
        wp_enqueue_script(
            'aqualuxe-editor-scripts',
            AQUALUXE_ASSETS_URI . 'js' . $this->get_asset_path( '/js/editor.js' ),
            [ 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ],
            AQUALUXE_VERSION,
            true
        );
    }
}