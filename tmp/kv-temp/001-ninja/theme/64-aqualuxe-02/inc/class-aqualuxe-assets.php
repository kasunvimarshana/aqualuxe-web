<?php
/**
 * AquaLuxe Assets Manager
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * AquaLuxe Assets Class
 * 
 * Handles asset management with cache busting
 */
class AquaLuxe_Assets {
    /**
     * Instance of this class
     *
     * @var AquaLuxe_Assets
     */
    private static $instance = null;

    /**
     * Asset manifest
     *
     * @var array
     */
    private $manifest = [];

    /**
     * Get singleton instance
     *
     * @return AquaLuxe_Assets
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Load manifest file
        $this->load_manifest();
    }

    /**
     * Load manifest file
     */
    private function load_manifest() {
        $manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';
        
        if ( file_exists( $manifest_path ) ) {
            $manifest_content = file_get_contents( $manifest_path );
            $this->manifest = json_decode( $manifest_content, true ) ?: [];
        }
    }

    /**
     * Get asset path from manifest
     *
     * @param string $path Asset path
     * @return string Asset path with version hash
     */
    public function get_asset_path( $path ) {
        // Add leading slash for manifest lookup
        $manifest_key = '/' . ltrim( $path, '/' );
        
        // Check if asset exists in manifest
        if ( isset( $this->manifest[ $manifest_key ] ) ) {
            return ltrim( $this->manifest[ $manifest_key ], '/' );
        }
        
        // Return original path if not found in manifest
        return $path;
    }

    /**
     * Enqueue a stylesheet
     *
     * @param string $handle Handle name
     * @param string $path Asset path relative to assets/dist/
     * @param array $deps Dependencies
     * @param string $media Media type
     */
    public function enqueue_style( $handle, $path, $deps = [], $media = 'all' ) {
        // Get asset path from manifest
        $asset_path = $this->get_asset_path( $path );
        
        // Enqueue stylesheet
        wp_enqueue_style(
            $handle,
            AQUALUXE_ASSETS_URI . $asset_path,
            $deps,
            null,
            $media
        );
    }

    /**
     * Enqueue a script
     *
     * @param string $handle Handle name
     * @param string $path Asset path relative to assets/dist/
     * @param array $deps Dependencies
     * @param bool $in_footer Whether to enqueue in footer
     */
    public function enqueue_script( $handle, $path, $deps = [], $in_footer = true ) {
        // Get asset path from manifest
        $asset_path = $this->get_asset_path( $path );
        
        // Enqueue script
        wp_enqueue_script(
            $handle,
            AQUALUXE_ASSETS_URI . $asset_path,
            $deps,
            null,
            $in_footer
        );
    }

    /**
     * Register a stylesheet
     *
     * @param string $handle Handle name
     * @param string $path Asset path relative to assets/dist/
     * @param array $deps Dependencies
     * @param string $media Media type
     */
    public function register_style( $handle, $path, $deps = [], $media = 'all' ) {
        // Get asset path from manifest
        $asset_path = $this->get_asset_path( $path );
        
        // Register stylesheet
        wp_register_style(
            $handle,
            AQUALUXE_ASSETS_URI . $asset_path,
            $deps,
            null,
            $media
        );
    }

    /**
     * Register a script
     *
     * @param string $handle Handle name
     * @param string $path Asset path relative to assets/dist/
     * @param array $deps Dependencies
     * @param bool $in_footer Whether to enqueue in footer
     */
    public function register_script( $handle, $path, $deps = [], $in_footer = true ) {
        // Get asset path from manifest
        $asset_path = $this->get_asset_path( $path );
        
        // Register script
        wp_register_script(
            $handle,
            AQUALUXE_ASSETS_URI . $asset_path,
            $deps,
            null,
            $in_footer
        );
    }
}