<?php
/**
 * Asset Manager Class
 * 
 * Handles all theme assets including CSS, JS, fonts, and images
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Asset Manager class
 */
class Asset_Manager {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Mix manifest data
     */
    private $mix_manifest;
    
    /**
     * Assets base path
     */
    private $assets_path;
    
    /**
     * Assets base URL
     */
    private $assets_url;
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->assets_path = AQUALUXE_THEME_DIR . '/assets/dist';
        $this->assets_url = AQUALUXE_ASSETS_URI;
        $this->load_mix_manifest();
        $this->init_hooks();
    }
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_theme_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('customize_controls_enqueue_scripts', [$this, 'enqueue_customizer_assets']);
        add_action('customize_preview_init', [$this, 'enqueue_customizer_preview_assets']);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_block_editor_assets']);
        add_filter('script_loader_tag', [$this, 'add_script_attributes'], 10, 3);
        add_filter('style_loader_tag', [$this, 'add_style_attributes'], 10, 4);
    }
    
    /**
     * Load mix manifest
     */
    private function load_mix_manifest() {
        $manifest_path = $this->assets_path . '/mix-manifest.json';
        
        if (file_exists($manifest_path)) {
            $this->mix_manifest = json_decode(file_get_contents($manifest_path), true);
        } else {
            $this->mix_manifest = [];
        }
    }
    
    /**
     * Get versioned asset URL
     */
    public function get_asset_url($path) {
        // Remove leading slash if present
        $path = ltrim($path, '/');
        
        // Check if we have a versioned file in mix manifest
        if (isset($this->mix_manifest['/' . $path])) {
            return $this->assets_url . $this->mix_manifest['/' . $path];
        }
        
        // Fallback to regular file with theme version
        $file_path = $this->assets_path . '/' . $path;
        $version = file_exists($file_path) ? filemtime($file_path) : AQUALUXE_VERSION;
        
        return $this->assets_url . '/' . $path . '?v=' . $version;
    }
    
    /**
     * Check if asset exists
     */
    public function asset_exists($path) {
        $path = ltrim($path, '/');
        return file_exists($this->assets_path . '/' . $path);
    }
    
    /**
     * Enqueue theme assets
     */
    public function enqueue_theme_assets() {
        // Remove WordPress default styles we don't need
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('classic-theme-styles');
        
        // Enqueue Google Fonts
        wp_enqueue_style(
            'aqualuxe-google-fonts',
            aqualuxe_google_fonts_url(),
            [],
            null
        );
        
        // Enqueue main CSS
        wp_enqueue_style(
            'aqualuxe-style',
            $this->get_asset_url('css/app.css'),
            ['aqualuxe-google-fonts'],
            null
        );
        
        // Enqueue WooCommerce styles if WooCommerce is active
        if (class_exists('WooCommerce') && $this->asset_exists('css/woocommerce.css')) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                $this->get_asset_url('css/woocommerce.css'),
                ['aqualuxe-style'],
                null
            );
        }
        
        // Enqueue dark mode styles if enabled
        if (get_theme_mod('aqualuxe_dark_mode_enabled', true) && $this->asset_exists('css/modules/dark-mode.css')) {
            wp_enqueue_style(
                'aqualuxe-dark-mode',
                $this->get_asset_url('css/modules/dark-mode.css'),
                ['aqualuxe-style'],
                null
            );
        }
        
        // Deregister default jQuery and enqueue our own
        wp_deregister_script('jquery');
        
        // Enqueue vendor scripts
        if ($this->asset_exists('js/vendor.js')) {
            wp_enqueue_script(
                'aqualuxe-vendor',
                $this->get_asset_url('js/vendor.js'),
                [],
                null,
                true
            );
        }
        
        // Enqueue main JavaScript
        wp_enqueue_script(
            'aqualuxe-script',
            $this->get_asset_url('js/app.js'),
            $this->asset_exists('js/vendor.js') ? ['aqualuxe-vendor'] : [],
            null,
            true
        );
        
        // Enqueue WooCommerce scripts if WooCommerce is active
        if (class_exists('WooCommerce') && $this->asset_exists('js/woocommerce.js')) {
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                $this->get_asset_url('js/woocommerce.js'),
                ['aqualuxe-script'],
                null,
                true
            );
        }
        
        // Enqueue module scripts
        $modules = ['dark-mode', 'search', 'cart', 'wishlist', 'gallery', 'forms', 'animations'];
        foreach ($modules as $module) {
            if ($this->asset_exists("js/modules/{$module}.js")) {
                wp_enqueue_script(
                    "aqualuxe-{$module}",
                    $this->get_asset_url("js/modules/{$module}.js"),
                    ['aqualuxe-script'],
                    null,
                    true
                );
            }
        }
        
        // Localize script with theme data
        wp_localize_script('aqualuxe-script', 'aqualuxeData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_ajax_nonce'),
            'homeUrl' => home_url('/'),
            'themeUrl' => AQUALUXE_THEME_URI,
            'assetsUrl' => $this->assets_url,
            'isWooCommerceActive' => class_exists('WooCommerce'),
            'darkModeEnabled' => get_theme_mod('aqualuxe_dark_mode_enabled', true),
            'strings' => [
                'loading' => __('Loading...', 'aqualuxe'),
                'loadMore' => __('Load More', 'aqualuxe'),
                'noMorePosts' => __('No more posts to load', 'aqualuxe'),
                'addedToCart' => __('Added to cart', 'aqualuxe'),
                'addedToWishlist' => __('Added to wishlist', 'aqualuxe'),
                'removedFromWishlist' => __('Removed from wishlist', 'aqualuxe'),
                'error' => __('An error occurred', 'aqualuxe'),
            ],
            'settings' => [
                'lazyLoading' => get_theme_mod('aqualuxe_lazy_loading', true),
                'animationsEnabled' => get_theme_mod('aqualuxe_animations_enabled', true),
                'socialSharing' => get_theme_mod('aqualuxe_social_sharing', true),
            ]
        ]);
        
        // Add inline CSS for customizer colors
        $this->add_customizer_css();
        
        // Enqueue comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        // Enqueue admin styles
        if ($this->asset_exists('css/admin.css')) {
            wp_enqueue_style(
                'aqualuxe-admin',
                $this->get_asset_url('css/admin.css'),
                [],
                null
            );
        }
        
        // Enqueue admin scripts
        if ($this->asset_exists('js/admin.js')) {
            wp_enqueue_script(
                'aqualuxe-admin',
                $this->get_asset_url('js/admin.js'),
                ['jquery'],
                null,
                true
            );
            
            wp_localize_script('aqualuxe-admin', 'aqualuxeAdmin', [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_admin_nonce'),
                'strings' => [
                    'confirm' => __('Are you sure?', 'aqualuxe'),
                    'success' => __('Success!', 'aqualuxe'),
                    'error' => __('An error occurred', 'aqualuxe'),
                ]
            ]);
        }
        
        // Enqueue media scripts on specific pages
        if (in_array($hook, ['post.php', 'post-new.php', 'customize.php'])) {
            wp_enqueue_media();
        }
    }
    
    /**
     * Enqueue customizer assets
     */
    public function enqueue_customizer_assets() {
        if ($this->asset_exists('css/customizer.css')) {
            wp_enqueue_style(
                'aqualuxe-customizer',
                $this->get_asset_url('css/customizer.css'),
                [],
                null
            );
        }
        
        if ($this->asset_exists('js/customizer.js')) {
            wp_enqueue_script(
                'aqualuxe-customizer',
                $this->get_asset_url('js/customizer.js'),
                ['jquery', 'customize-controls'],
                null,
                true
            );
        }
    }
    
    /**
     * Enqueue customizer preview assets
     */
    public function enqueue_customizer_preview_assets() {
        wp_enqueue_script(
            'aqualuxe-customizer-preview',
            $this->get_asset_url('js/customizer-preview.js'),
            ['jquery', 'customize-preview'],
            null,
            true
        );
    }
    
    /**
     * Enqueue block editor assets
     */
    public function enqueue_block_editor_assets() {
        if ($this->asset_exists('css/editor.css')) {
            wp_enqueue_style(
                'aqualuxe-editor',
                $this->get_asset_url('css/editor.css'),
                [],
                null
            );
        }
        
        if ($this->asset_exists('js/editor.js')) {
            wp_enqueue_script(
                'aqualuxe-editor',
                $this->get_asset_url('js/editor.js'),
                ['wp-blocks', 'wp-dom-ready', 'wp-edit-post'],
                null,
                true
            );
        }
    }
    
    /**
     * Add script attributes
     */
    public function add_script_attributes($tag, $handle, $src) {
        // Add defer attribute to non-critical scripts
        $defer_scripts = [
            'aqualuxe-animations',
            'aqualuxe-gallery',
            'aqualuxe-forms'
        ];
        
        if (in_array($handle, $defer_scripts)) {
            $tag = str_replace(' src', ' defer src', $tag);
        }
        
        // Add async attribute to analytics scripts
        $async_scripts = [
            'google-analytics',
            'gtag'
        ];
        
        if (in_array($handle, $async_scripts)) {
            $tag = str_replace(' src', ' async src', $tag);
        }
        
        return $tag;
    }
    
    /**
     * Add style attributes
     */
    public function add_style_attributes($html, $handle, $href, $media) {
        // Add preload for critical CSS
        $critical_styles = ['aqualuxe-style'];
        
        if (in_array($handle, $critical_styles)) {
            $html = str_replace("rel='stylesheet'", "rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"", $html);
            $html .= '<noscript>' . str_replace("rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"", "rel='stylesheet'", $html) . '</noscript>';
        }
        
        return $html;
    }
    
    /**
     * Add customizer CSS
     */
    private function add_customizer_css() {
        $primary_color = get_theme_mod('aqualuxe_primary_color', '#06b6d4');
        $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#d946ef');
        $custom_css = get_theme_mod('aqualuxe_custom_css', '');
        
        $css = ":root {
            --color-primary: {$primary_color};
            --color-secondary: {$secondary_color};
        }";
        
        if (!empty($custom_css)) {
            $css .= "\n" . $custom_css;
        }
        
        wp_add_inline_style('aqualuxe-style', $css);
    }
    
    /**
     * Preload critical assets
     */
    public function preload_critical_assets() {
        // Preload critical CSS
        echo '<link rel="preload" href="' . $this->get_asset_url('css/app.css') . '" as="style">' . "\n";
        
        // Preload critical JavaScript
        echo '<link rel="preload" href="' . $this->get_asset_url('js/app.js') . '" as="script">' . "\n";
        
        // Preload fonts
        $fonts = [
            'fonts/inter-var.woff2',
            'fonts/playfair-display-var.woff2'
        ];
        
        foreach ($fonts as $font) {
            if ($this->asset_exists($font)) {
                echo '<link rel="preload" href="' . $this->get_asset_url($font) . '" as="font" type="font/woff2" crossorigin>' . "\n";
            }
        }
    }
    
    /**
     * Get inline SVG
     */
    public function get_svg($name, $class = '') {
        $svg_path = $this->assets_path . "/images/svg/{$name}.svg";
        
        if (file_exists($svg_path)) {
            $svg = file_get_contents($svg_path);
            
            if (!empty($class)) {
                $svg = str_replace('<svg', '<svg class="' . esc_attr($class) . '"', $svg);
            }
            
            return $svg;
        }
        
        return '';
    }
    
    /**
     * Generate critical CSS
     */
    public function generate_critical_css() {
        // This would be implemented with a critical CSS generation tool
        // For now, we'll return a basic set of critical styles
        return '';
    }
}
