<?php
/**
 * AquaLuxe Assets Class
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * AquaLuxe Assets Class
 */
class AquaLuxe_Assets {
    /**
     * Manifest data
     *
     * @var array
     */
    private static $manifest = [];

    /**
     * Initialize assets
     */
    public static function init() {
        // Load manifest
        self::load_manifest();
    }

    /**
     * Load manifest
     */
    private static function load_manifest() {
        $manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';
        
        if (file_exists($manifest_path)) {
            $manifest_content = file_get_contents($manifest_path);
            self::$manifest = json_decode($manifest_content, true);
        }
    }

    /**
     * Get asset URL
     *
     * @param string $path Asset path
     * @return string Asset URL
     */
    public static function get_asset_url($path) {
        // Check if manifest is loaded
        if (empty(self::$manifest)) {
            self::load_manifest();
        }
        
        // Check if asset exists in manifest
        $manifest_path = '/' . $path;
        if (isset(self::$manifest[$manifest_path])) {
            $path = ltrim(self::$manifest[$manifest_path], '/');
        }
        
        return AQUALUXE_ASSETS_URI . $path;
    }

    /**
     * Enqueue style
     *
     * @param string $handle Handle
     * @param string $path Asset path
     * @param array $deps Dependencies
     * @param string $media Media
     */
    public static function enqueue_style($handle, $path, $deps = [], $media = 'all') {
        wp_enqueue_style(
            $handle,
            self::get_asset_url($path),
            $deps,
            AQUALUXE_VERSION,
            $media
        );
    }

    /**
     * Enqueue script
     *
     * @param string $handle Handle
     * @param string $path Asset path
     * @param array $deps Dependencies
     * @param bool $in_footer In footer
     */
    public static function enqueue_script($handle, $path, $deps = [], $in_footer = true) {
        wp_enqueue_script(
            $handle,
            self::get_asset_url($path),
            $deps,
            AQUALUXE_VERSION,
            $in_footer
        );
    }

    /**
     * Localize script
     *
     * @param string $handle Handle
     * @param string $object_name Object name
     * @param array $data Data
     */
    public static function localize_script($handle, $object_name, $data) {
        wp_localize_script($handle, $object_name, $data);
    }

    /**
     * Register style
     *
     * @param string $handle Handle
     * @param string $path Asset path
     * @param array $deps Dependencies
     * @param string $media Media
     */
    public static function register_style($handle, $path, $deps = [], $media = 'all') {
        wp_register_style(
            $handle,
            self::get_asset_url($path),
            $deps,
            AQUALUXE_VERSION,
            $media
        );
    }

    /**
     * Register script
     *
     * @param string $handle Handle
     * @param string $path Asset path
     * @param array $deps Dependencies
     * @param bool $in_footer In footer
     */
    public static function register_script($handle, $path, $deps = [], $in_footer = true) {
        wp_register_script(
            $handle,
            self::get_asset_url($path),
            $deps,
            AQUALUXE_VERSION,
            $in_footer
        );
    }

    /**
     * Preload asset
     *
     * @param string $path Asset path
     * @param string $type Asset type
     * @param string $media Media
     */
    public static function preload($path, $type = 'style', $media = 'all') {
        $url = self::get_asset_url($path);
        
        printf(
            '<link rel="preload" href="%s" as="%s" type="%s" media="%s">',
            esc_url($url),
            esc_attr($type === 'style' ? 'style' : 'script'),
            esc_attr($type === 'style' ? 'text/css' : 'text/javascript'),
            esc_attr($media)
        );
    }

    /**
     * Add preconnect
     *
     * @param string $url URL
     * @param bool $crossorigin Crossorigin
     */
    public static function preconnect($url, $crossorigin = true) {
        printf(
            '<link rel="preconnect" href="%s"%s>',
            esc_url($url),
            $crossorigin ? ' crossorigin' : ''
        );
    }

    /**
     * Add DNS prefetch
     *
     * @param string $url URL
     */
    public static function dns_prefetch($url) {
        printf(
            '<link rel="dns-prefetch" href="%s">',
            esc_url($url)
        );
    }

    /**
     * Add preload fonts
     *
     * @param string $url Font URL
     * @param string $format Font format
     */
    public static function preload_font($url, $format = 'woff2') {
        printf(
            '<link rel="preload" href="%s" as="font" type="font/%s" crossorigin>',
            esc_url($url),
            esc_attr($format)
        );
    }

    /**
     * Add inline style
     *
     * @param string $handle Handle
     * @param string $css CSS
     */
    public static function add_inline_style($handle, $css) {
        wp_add_inline_style($handle, $css);
    }

    /**
     * Add inline script
     *
     * @param string $handle Handle
     * @param string $js JavaScript
     * @param string $position Position
     */
    public static function add_inline_script($handle, $js, $position = 'after') {
        wp_add_inline_script($handle, $js, $position);
    }
}

// Initialize assets
AquaLuxe_Assets::init();