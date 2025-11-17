<?php
/**
 * Assets Management Class
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Assets Management Class
 * 
 * This class is responsible for loading and managing all theme assets.
 * It handles the enqueuing of styles and scripts with cache busting.
 */
class Assets {
    /**
     * Instance of this class
     *
     * @var Assets
     */
    private static $instance = null;

    /**
     * Mix manifest data
     *
     * @var array
     */
    private $mix_manifest = [];

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
        $this->load_mix_manifest();
        $this->setup_hooks();
    }

    /**
     * Load mix manifest file
     *
     * @return void
     */
    private function load_mix_manifest() {
        $manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';
        
        if ( file_exists( $manifest_path ) ) {
            $this->mix_manifest = json_decode( file_get_contents( $manifest_path ), true );
        }
    }

    /**
     * Setup hooks
     *
     * @return void
     */
    private function setup_hooks() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_assets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
        add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_editor_assets' ] );
    }

    /**
     * Enqueue frontend assets
     *
     * @return void
     */
    public function enqueue_frontend_assets() {
        // Enqueue main stylesheet
        wp_enqueue_style(
            'aqualuxe-styles',
            $this->get_asset_url( 'css/main.css' ),
            [],
            $this->get_asset_version( 'css/main.css' )
        );

        // Enqueue main script
        wp_enqueue_script(
            'aqualuxe-scripts',
            $this->get_asset_url( 'js/main.js' ),
            [ 'jquery' ],
            $this->get_asset_version( 'js/main.js' ),
            true
        );

        // Add theme settings to JavaScript
        wp_localize_script(
            'aqualuxe-scripts',
            'aqualuxeSettings',
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'themeUri' => AQUALUXE_URI,
                'assetsUri' => AQUALUXE_ASSETS_URI,
                'isWooCommerceActive' => \AquaLuxe\Core\Theme::get_instance()->is_woocommerce_active(),
                'nonce' => wp_create_nonce( 'aqualuxe-nonce' ),
            ]
        );

        // If WooCommerce is active, enqueue WooCommerce specific styles and scripts
        if ( \AquaLuxe\Core\Theme::get_instance()->is_woocommerce_active() ) {
            if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
                wp_enqueue_style(
                    'aqualuxe-woocommerce',
                    $this->get_asset_url( 'css/woocommerce.css' ),
                    [ 'aqualuxe-styles' ],
                    $this->get_asset_version( 'css/woocommerce.css' )
                );

                wp_enqueue_script(
                    'aqualuxe-woocommerce',
                    $this->get_asset_url( 'js/woocommerce.js' ),
                    [ 'jquery', 'aqualuxe-scripts' ],
                    $this->get_asset_version( 'js/woocommerce.js' ),
                    true
                );
            }
        }

        // Load comment-reply script if needed
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }

    /**
     * Enqueue admin assets
     *
     * @return void
     */
    public function enqueue_admin_assets() {
        wp_enqueue_style(
            'aqualuxe-admin',
            $this->get_asset_url( 'css/admin.css' ),
            [],
            $this->get_asset_version( 'css/admin.css' )
        );

        wp_enqueue_script(
            'aqualuxe-admin',
            $this->get_asset_url( 'js/admin.js' ),
            [ 'jquery' ],
            $this->get_asset_version( 'js/admin.js' ),
            true
        );
    }

    /**
     * Enqueue editor assets
     *
     * @return void
     */
    public function enqueue_editor_assets() {
        wp_enqueue_style(
            'aqualuxe-editor',
            $this->get_asset_url( 'css/editor.css' ),
            [],
            $this->get_asset_version( 'css/editor.css' )
        );

        wp_enqueue_script(
            'aqualuxe-editor',
            $this->get_asset_url( 'js/editor.js' ),
            [ 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ],
            $this->get_asset_version( 'js/editor.js' ),
            true
        );
    }

    /**
     * Get asset URL with cache busting
     *
     * @param string $path Asset path relative to assets/dist directory
     * @return string
     */
    public function get_asset_url( $path ) {
        $asset_path = '/' . $path;
        
        // Check if the asset exists in the mix manifest
        if ( isset( $this->mix_manifest[ $asset_path ] ) ) {
            return AQUALUXE_ASSETS_URI . ltrim( $this->mix_manifest[ $asset_path ], '/' );
        }
        
        // Fallback to the original path
        return AQUALUXE_ASSETS_URI . $path;
    }

    /**
     * Get asset version for cache busting
     *
     * @param string $path Asset path relative to assets/dist directory
     * @return string
     */
    public function get_asset_version( $path ) {
        $asset_path = '/' . $path;
        
        // Check if the asset exists in the mix manifest
        if ( isset( $this->mix_manifest[ $asset_path ] ) ) {
            // Extract version from the filename if it contains a hash
            if ( preg_match( '/\.([a-f0-9]{8})\./', $this->mix_manifest[ $asset_path ], $matches ) ) {
                return $matches[1];
            }
        }
        
        // Fallback to theme version
        return AQUALUXE_VERSION;
    }

    /**
     * Register and enqueue a custom stylesheet or script
     *
     * @param string $handle Script or style handle
     * @param string $path Asset path relative to assets/dist directory
     * @param array $deps Dependencies
     * @param boolean $in_footer Whether to enqueue the script in the footer
     * @param string $media Media for the stylesheet
     * @return void
     */
    public function enqueue_asset( $handle, $path, $deps = [], $in_footer = true, $media = 'all' ) {
        $file_extension = pathinfo( $path, PATHINFO_EXTENSION );
        
        if ( $file_extension === 'css' ) {
            wp_enqueue_style(
                $handle,
                $this->get_asset_url( $path ),
                $deps,
                $this->get_asset_version( $path ),
                $media
            );
        } elseif ( $file_extension === 'js' ) {
            wp_enqueue_script(
                $handle,
                $this->get_asset_url( $path ),
                $deps,
                $this->get_asset_version( $path ),
                $in_footer
            );
        }
    }

    /**
     * Register a custom stylesheet or script without enqueuing it
     *
     * @param string $handle Script or style handle
     * @param string $path Asset path relative to assets/dist directory
     * @param array $deps Dependencies
     * @param boolean $in_footer Whether to enqueue the script in the footer
     * @param string $media Media for the stylesheet
     * @return void
     */
    public function register_asset( $handle, $path, $deps = [], $in_footer = true, $media = 'all' ) {
        $file_extension = pathinfo( $path, PATHINFO_EXTENSION );
        
        if ( $file_extension === 'css' ) {
            wp_register_style(
                $handle,
                $this->get_asset_url( $path ),
                $deps,
                $this->get_asset_version( $path ),
                $media
            );
        } elseif ( $file_extension === 'js' ) {
            wp_register_script(
                $handle,
                $this->get_asset_url( $path ),
                $deps,
                $this->get_asset_version( $path ),
                $in_footer
            );
        }
    }
}