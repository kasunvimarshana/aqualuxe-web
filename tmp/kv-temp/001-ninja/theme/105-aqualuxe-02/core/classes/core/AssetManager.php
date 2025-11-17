<?php
/**
 * Asset Manager Class
 *
 * Centralized asset management with manifest support and optimization
 *
 * @package AquaLuxe\Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

/**
 * Class AssetManager
 *
 * Manages theme assets with caching and optimization
 */
class AssetManager {
    
    /**
     * Single instance of the class
     *
     * @var AssetManager
     */
    private static $instance = null;

    /**
     * Mix manifest cache
     *
     * @var array
     */
    private static $manifest = null;

    /**
     * Get instance
     *
     * @return AssetManager
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Initialize on construct
    }

    /**
     * Get mix manifest
     *
     * @return array
     */
    public static function get_manifest() {
        if (self::$manifest === null) {
            $manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
            
            if (file_exists($manifest_path)) {
                $content = file_get_contents($manifest_path);
                self::$manifest = json_decode($content, true);
                self::$manifest = is_array(self::$manifest) ? self::$manifest : array();
            } else {
                self::$manifest = array();
            }
        }
        
        return self::$manifest;
    }

    /**
     * Get versioned file path from manifest
     *
     * @param string $file File path relative to dist directory
     * @return string Versioned file path
     */
    public static function get_versioned_file($file) {
        $manifest = self::get_manifest();
        $file_key = '/' . ltrim($file, '/');
        
        if (isset($manifest[$file_key])) {
            return ltrim($manifest[$file_key], '/');
        }
        
        return $file;
    }

    /**
     * Get full asset URL
     *
     * @param string $file File path relative to dist directory
     * @return string Full asset URL
     */
    public static function get_asset_url($file) {
        return AQUALUXE_ASSETS_URI . '/' . self::get_versioned_file($file);
    }

    /**
     * Check if asset file exists
     *
     * @param string $file File path relative to dist directory
     * @return bool
     */
    public static function asset_exists($file) {
        $file_path = AQUALUXE_THEME_DIR . '/assets/dist/' . ltrim($file, '/');
        return file_exists($file_path);
    }

    /**
     * Enqueue style with manifest support
     *
     * @param string $handle Style handle
     * @param string $file File path relative to dist directory
     * @param array $deps Dependencies
     * @param string|bool $ver Version string
     * @param string $media Media type
     */
    public static function enqueue_style($handle, $file, $deps = array(), $ver = AQUALUXE_VERSION, $media = 'all') {
        if (!self::asset_exists($file)) {
            return;
        }

        wp_enqueue_style(
            $handle,
            self::get_asset_url($file),
            $deps,
            $ver,
            $media
        );
    }

    /**
     * Enqueue script with manifest support
     *
     * @param string $handle Script handle
     * @param string $file File path relative to dist directory
     * @param array $deps Dependencies
     * @param string|bool $ver Version string
     * @param bool $in_footer Load in footer
     */
    public static function enqueue_script($handle, $file, $deps = array(), $ver = AQUALUXE_VERSION, $in_footer = true) {
        if (!self::asset_exists($file)) {
            return;
        }

        wp_enqueue_script(
            $handle,
            self::get_asset_url($file),
            $deps,
            $ver,
            $in_footer
        );
    }

    /**
     * Conditionally enqueue script based on context
     *
     * @param string $handle Script handle
     * @param string $file File path relative to dist directory
     * @param callable $condition Condition function
     * @param array $deps Dependencies
     * @param string|bool $ver Version string
     * @param bool $in_footer Load in footer
     */
    public static function enqueue_conditional_script($handle, $file, $condition, $deps = array(), $ver = AQUALUXE_VERSION, $in_footer = true) {
        if (is_callable($condition) && call_user_func($condition)) {
            self::enqueue_script($handle, $file, $deps, $ver, $in_footer);
        }
    }

    /**
     * Get critical CSS
     *
     * @return string Critical CSS content
     */
    public static function get_critical_css() {
        $critical_css = '
            :root {
                --color-primary: #006B7D;
                --color-background: #FFFFFF;
                --color-text: #2C3E50;
                --font-primary: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            }
            body { 
                font-family: var(--font-primary); 
                color: var(--color-text);
                background-color: var(--color-background);
                line-height: 1.6;
                margin: 0;
            }
            .site-header { 
                background: #fff; 
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                position: sticky;
                top: 0;
                z-index: 1000;
            }
            .skip-link { 
                position: absolute; 
                left: -9999px; 
                z-index: 999999;
                background: var(--color-primary);
                color: white;
                padding: 8px 16px;
                text-decoration: none;
            }
            .skip-link:focus { 
                position: absolute; 
                top: 0; 
                left: 0; 
                z-index: 999999; 
            }
            .screen-reader-text { 
                clip: rect(1px, 1px, 1px, 1px); 
                position: absolute !important; 
                height: 1px;
                width: 1px;
                overflow: hidden;
            }
            .dark { 
                color-scheme: dark;
                --color-background: #1A202C;
                --color-text: #E2E8F0;
            }
            .loading { 
                opacity: 0.6; 
                pointer-events: none; 
                position: relative;
            }
            .loading::after {
                content: "";
                position: absolute;
                top: 50%;
                left: 50%;
                width: 20px;
                height: 20px;
                margin: -10px 0 0 -10px;
                border: 2px solid var(--color-primary);
                border-radius: 50%;
                border-top-color: transparent;
                animation: aqualuxe-spin 1s linear infinite;
            }
            @keyframes aqualuxe-spin {
                to { transform: rotate(360deg); }
            }
            .lazyload {
                opacity: 0;
            }
            .lazyloaded {
                opacity: 1;
                transition: opacity 300ms;
            }
            img {
                max-width: 100%;
                height: auto;
            }
        ';
        
        return apply_filters('aqualuxe_critical_css', $critical_css);
    }

    /**
     * Inline critical CSS in head
     */
    public static function inline_critical_css() {
        echo '<style id="aqualuxe-critical-css">';
        echo self::get_critical_css();
        echo '</style>';
    }

    /**
     * Add preload links for critical assets
     */
    public static function add_preload_links() {
        // Preload critical fonts
        $critical_fonts = apply_filters('aqualuxe_critical_fonts', array(
            'fonts/inter-regular.woff2',
            'fonts/inter-medium.woff2',
        ));

        foreach ($critical_fonts as $font) {
            if (self::asset_exists($font)) {
                printf(
                    '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>',
                    esc_url(self::get_asset_url($font))
                );
            }
        }

        // Preload hero image if defined
        $hero_image = get_theme_mod('aqualuxe_hero_image');
        if ($hero_image) {
            printf(
                '<link rel="preload" href="%s" as="image">',
                esc_url($hero_image)
            );
        }
    }

    /**
     * Add DNS prefetch hints
     */
    public static function add_dns_prefetch() {
        $domains = apply_filters('aqualuxe_dns_prefetch_domains', array(
            'fonts.googleapis.com',
            'fonts.gstatic.com',
            'www.google-analytics.com',
            'www.googletagmanager.com',
            'maps.googleapis.com',
        ));

        foreach ($domains as $domain) {
            printf('<link rel="dns-prefetch" href="//%s">', esc_attr($domain));
        }
    }

    /**
     * Generate service worker
     */
    public static function generate_service_worker() {
        $sw_content = '
const CACHE_NAME = "aqualuxe-v' . AQUALUXE_VERSION . '";
const urlsToCache = [
    "' . self::get_asset_url('css/app.css') . '",
    "' . self::get_asset_url('js/app.js') . '",
    "' . get_stylesheet_directory_uri() . '/screenshot.png"
];

self.addEventListener("install", function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function(cache) {
                return cache.addAll(urlsToCache);
            })
    );
});

self.addEventListener("fetch", function(event) {
    event.respondWith(
        caches.match(event.request)
            .then(function(response) {
                if (response) {
                    return response;
                }
                return fetch(event.request);
            }
        )
    );
});

self.addEventListener("activate", function(event) {
    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(cacheName) {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});
        ';

        return $sw_content;
    }

    /**
     * Add lazy loading attributes to images
     */
    public static function add_lazy_loading($content) {
        // Add loading="lazy" to images
        $content = preg_replace('/<img([^>]+?)>/i', '<img$1 loading="lazy">', $content);
        
        // Add data-src for lazysizes library
        $content = preg_replace('/<img([^>]+?)src="([^"]*)"([^>]*?)>/i', '<img$1data-src="$2" src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E" class="lazyload"$3>', $content);
        
        return $content;
    }

    /**
     * Optimize image sizes
     */
    public static function get_responsive_image($attachment_id, $size = 'full', $attr = array()) {
        if (!$attachment_id) {
            return '';
        }

        $default_attr = array(
            'class' => 'lazyload',
            'loading' => 'lazy'
        );

        $attr = array_merge($default_attr, $attr);

        return wp_get_attachment_image($attachment_id, $size, false, $attr);
    }

    /**
     * Minify CSS content
     */
    public static function minify_css($css) {
        // Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Remove unnecessary whitespace
        $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
        
        // Remove trailing semicolon of last property in a declaration block
        $css = str_replace(';}', '}', $css);
        
        return trim($css);
    }

    /**
     * Minify JavaScript content
     */
    public static function minify_js($js) {
        // Basic minification - remove comments and unnecessary whitespace
        $js = preg_replace('/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/', '', $js);
        $js = preg_replace('/\s+/', ' ', $js);
        
        return trim($js);
    }
}