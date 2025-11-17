<?php
/**
 * Assets management class
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Assets class
 */
class Assets {
    /**
     * Constructor
     */
    public function __construct() {
        // Register hooks
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'register_admin_assets' ] );
        add_action( 'enqueue_block_editor_assets', [ $this, 'register_editor_assets' ] );
        
        // Add async/defer attributes to scripts
        add_filter( 'script_loader_tag', [ $this, 'add_script_attributes' ], 10, 3 );
    }

    /**
     * Get the asset manifest
     *
     * @return array The asset manifest.
     */
    private function get_manifest() {
        static $manifest = null;

        if ( is_null( $manifest ) ) {
            $manifest_path = AQUALUXE_ASSETS_DIR . 'mix-manifest.json';
            $manifest = file_exists( $manifest_path ) ? json_decode( file_get_contents( $manifest_path ), true ) : [];
        }

        return $manifest;
    }

    /**
     * Get the versioned asset URL
     *
     * @param string $path The asset path.
     * @return string The versioned asset URL.
     */
    public function get_asset_url( $path ) {
        $manifest = $this->get_manifest();
        $versioned_path = isset( $manifest[ $path ] ) ? $manifest[ $path ] : $path;
        return AQUALUXE_ASSETS_URI . ltrim( $versioned_path, '/' );
    }

    /**
     * Register and enqueue assets
     */
    public function register_assets() {
        // Register styles
        wp_register_style(
            'aqualuxe-main',
            $this->get_asset_url( '/css/main.css' ),
            [],
            AQUALUXE_VERSION
        );

        // Register scripts
        wp_register_script(
            'aqualuxe-main',
            $this->get_asset_url( '/js/main.js' ),
            [ 'jquery' ],
            AQUALUXE_VERSION,
            true
        );

        // Enqueue main styles and scripts
        wp_enqueue_style( 'aqualuxe-main' );
        wp_enqueue_script( 'aqualuxe-main' );

        // Add localized script data
        wp_localize_script(
            'aqualuxe-main',
            'aqualuxeData',
            [
                'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
                'nonce'      => wp_create_nonce( 'aqualuxe-nonce' ),
                'homeUrl'    => home_url(),
                'themeUri'   => AQUALUXE_URI,
                'assetsUri'  => AQUALUXE_ASSETS_URI,
                'isRtl'      => is_rtl(),
                'i18n'       => [
                    'searchPlaceholder' => esc_html__( 'Search...', 'aqualuxe' ),
                    'menuToggle'        => esc_html__( 'Toggle Menu', 'aqualuxe' ),
                    'closeMenu'         => esc_html__( 'Close Menu', 'aqualuxe' ),
                    'expandMenu'        => esc_html__( 'Expand Menu', 'aqualuxe' ),
                    'collapseMenu'      => esc_html__( 'Collapse Menu', 'aqualuxe' ),
                    'darkMode'          => esc_html__( 'Dark Mode', 'aqualuxe' ),
                    'lightMode'         => esc_html__( 'Light Mode', 'aqualuxe' ),
                ],
            ]
        );

        // Load comment reply script
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }

        // Load module assets
        $this->load_module_assets();
    }

    /**
     * Register and enqueue admin assets
     */
    public function register_admin_assets() {
        // Register admin styles
        wp_register_style(
            'aqualuxe-admin',
            $this->get_asset_url( '/css/admin.css' ),
            [],
            AQUALUXE_VERSION
        );

        // Register admin scripts
        wp_register_script(
            'aqualuxe-admin',
            $this->get_asset_url( '/js/admin.js' ),
            [ 'jquery' ],
            AQUALUXE_VERSION,
            true
        );

        // Enqueue admin styles and scripts
        wp_enqueue_style( 'aqualuxe-admin' );
        wp_enqueue_script( 'aqualuxe-admin' );

        // Add localized script data
        wp_localize_script(
            'aqualuxe-admin',
            'aqualuxeAdminData',
            [
                'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
                'nonce'      => wp_create_nonce( 'aqualuxe-admin-nonce' ),
                'themeUri'   => AQUALUXE_URI,
                'assetsUri'  => AQUALUXE_ASSETS_URI,
            ]
        );
    }

    /**
     * Register and enqueue editor assets
     */
    public function register_editor_assets() {
        // Register editor styles
        wp_register_style(
            'aqualuxe-editor',
            $this->get_asset_url( '/css/editor-style.css' ),
            [],
            AQUALUXE_VERSION
        );

        // Register editor scripts
        wp_register_script(
            'aqualuxe-editor',
            $this->get_asset_url( '/js/customizer.js' ),
            [ 'jquery' ],
            AQUALUXE_VERSION,
            true
        );

        // Enqueue editor styles and scripts
        wp_enqueue_style( 'aqualuxe-editor' );
        wp_enqueue_script( 'aqualuxe-editor' );
    }

    /**
     * Load module assets
     */
    private function load_module_assets() {
        // Check if WooCommerce is active
        if ( class_exists( 'WooCommerce' ) ) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                $this->get_asset_url( '/css/woocommerce.css' ),
                [ 'aqualuxe-main' ],
                AQUALUXE_VERSION
            );

            wp_enqueue_script(
                'aqualuxe-woocommerce',
                $this->get_asset_url( '/js/modules/woocommerce.js' ),
                [ 'jquery', 'aqualuxe-main' ],
                AQUALUXE_VERSION,
                true
            );
        }

        // Load dark mode assets if enabled
        if ( apply_filters( 'aqualuxe_dark_mode_enabled', true ) ) {
            wp_enqueue_style(
                'aqualuxe-dark-mode',
                $this->get_asset_url( '/css/modules/dark-mode.css' ),
                [ 'aqualuxe-main' ],
                AQUALUXE_VERSION
            );

            wp_enqueue_script(
                'aqualuxe-dark-mode',
                $this->get_asset_url( '/js/modules/dark-mode.js' ),
                [ 'jquery', 'aqualuxe-main' ],
                AQUALUXE_VERSION,
                true
            );
        }

        // Load multilingual assets if enabled
        if ( apply_filters( 'aqualuxe_multilingual_enabled', true ) ) {
            wp_enqueue_style(
                'aqualuxe-multilingual',
                $this->get_asset_url( '/css/modules/multilingual.css' ),
                [ 'aqualuxe-main' ],
                AQUALUXE_VERSION
            );

            wp_enqueue_script(
                'aqualuxe-multilingual',
                $this->get_asset_url( '/js/modules/multilingual.js' ),
                [ 'jquery', 'aqualuxe-main' ],
                AQUALUXE_VERSION,
                true
            );
        }
    }

    /**
     * Add async/defer attributes to scripts
     *
     * @param string $tag The script tag.
     * @param string $handle The script handle.
     * @param string $src The script source.
     * @return string The modified script tag.
     */
    public function add_script_attributes( $tag, $handle, $src ) {
        // Add async attribute to specific scripts
        $async_scripts = apply_filters(
            'aqualuxe_async_scripts',
            [
                'aqualuxe-dark-mode',
                'aqualuxe-multilingual',
            ]
        );

        if ( in_array( $handle, $async_scripts, true ) ) {
            return str_replace( ' src', ' async src', $tag );
        }

        // Add defer attribute to specific scripts
        $defer_scripts = apply_filters(
            'aqualuxe_defer_scripts',
            [
                'aqualuxe-main',
                'aqualuxe-woocommerce',
            ]
        );

        if ( in_array( $handle, $defer_scripts, true ) ) {
            return str_replace( ' src', ' defer src', $tag );
        }

        return $tag;
    }
}

// Initialize the class
new Assets();