<?php
/**
 * Asset Manager Service
 *
 * Handles all asset enqueueing with proper cache busting and optimization
 *
 * @package AquaLuxe\Core\Services
 * @version 1.0.0
 */

namespace AquaLuxe\Core\Services;

use AquaLuxe\Core\Interfaces\ServiceInterface;

/**
 * Asset Manager Class
 */
class AssetManager implements ServiceInterface {
    
    /**
     * Mix manifest cache
     *
     * @var array|null
     */
    private $manifest = null;
    
    /**
     * Registered assets
     *
     * @var array
     */
    private $assets = [];
    
    /**
     * Initialize asset manager
     *
     * @return void
     */
    public function init(): void {
        // Frontend assets
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        
        // Admin assets
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        
        // Customizer assets
        add_action('customize_preview_init', [$this, 'enqueue_customizer_assets']);
        
        // Editor assets
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_editor_assets']);
        
        // Optimize assets
        add_action('wp_head', [$this, 'add_resource_hints'], 1);
        add_action('wp_head', [$this, 'add_critical_css'], 2);
        
        // Preload assets
        add_action('wp_head', [$this, 'preload_assets'], 3);
    }
    
    /**
     * Get service name
     *
     * @return string
     */
    public function getName(): string {
        return 'AssetManager';
    }
    
    /**
     * Enqueue frontend assets
     *
     * @return void
     */
    public function enqueue_frontend_assets(): void {
        // Main stylesheet
        wp_enqueue_style(
            'aqualuxe-style',
            $this->get_asset_url('/css/app.css'),
            [],
            $this->get_asset_version('/css/app.css')
        );
        
        // WooCommerce styles (if active)
        if (aqualuxe_is_woocommerce_active()) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                $this->get_asset_url('/css/woocommerce.css'),
                ['aqualuxe-style'],
                $this->get_asset_version('/css/woocommerce.css')
            );
        }
        
        // Main JavaScript
        wp_enqueue_script(
            'aqualuxe-script',
            $this->get_asset_url('/js/app.js'),
            [],
            $this->get_asset_version('/js/app.js'),
            true
        );
        
        // Localize script with data
        wp_localize_script('aqualuxe-script', 'aqualuxe', $this->get_localization_data());
        
        // Conditional assets
        $this->enqueue_conditional_assets();
    }
    
    /**
     * Enqueue admin assets
     *
     * @param string $hook
     * @return void
     */
    public function enqueue_admin_assets($hook): void {
        // Admin stylesheet
        wp_enqueue_style(
            'aqualuxe-admin',
            $this->get_asset_url('/css/admin.css'),
            [],
            $this->get_asset_version('/css/admin.css')
        );
        
        // Admin JavaScript
        wp_enqueue_script(
            'aqualuxe-admin',
            $this->get_asset_url('/js/admin.js'),
            ['jquery'],
            $this->get_asset_version('/js/admin.js'),
            true
        );
        
        // Localize admin script
        wp_localize_script('aqualuxe-admin', 'aqualuxe_admin', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_admin_nonce'),
            'strings' => [
                'loading' => __('Loading...', 'aqualuxe'),
                'error' => __('An error occurred. Please try again.', 'aqualuxe'),
                'success' => __('Operation completed successfully.', 'aqualuxe'),
            ],
        ]);
    }
    
    /**
     * Enqueue customizer assets
     *
     * @return void
     */
    public function enqueue_customizer_assets(): void {
        wp_enqueue_script(
            'aqualuxe-customizer',
            $this->get_asset_url('/js/customizer.js'),
            ['customize-preview'],
            $this->get_asset_version('/js/customizer.js'),
            true
        );
    }
    
    /**
     * Enqueue editor assets
     *
     * @return void
     */
    public function enqueue_editor_assets(): void {
        wp_enqueue_style(
            'aqualuxe-editor',
            $this->get_asset_url('/css/editor.css'),
            [],
            $this->get_asset_version('/css/editor.css')
        );
    }
    
    /**
     * Enqueue conditional assets based on context
     *
     * @return void
     */
    private function enqueue_conditional_assets(): void {
        // Comments script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
        
        // Shop scripts (WooCommerce)
        if (aqualuxe_is_woocommerce_active() && (is_shop() || is_product_category() || is_product_tag())) {
            wp_enqueue_script('wc-add-to-cart-variation');
        }
        
        // Contact form scripts (if on contact page)
        if (is_page_template('templates/pages/contact.php')) {
            wp_enqueue_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=' . get_theme_mod('google_maps_api_key', ''), [], null, true);
        }
    }
    
    /**
     * Get asset URL with cache busting
     *
     * @param string $path
     * @return string
     */
    public function get_asset_url($path): string {
        return aqualuxe_asset($path);
    }
    
    /**
     * Get asset version for cache busting
     *
     * @param string $path
     * @return string
     */
    public function get_asset_version($path): string {
        $manifest = $this->get_manifest();
        $versioned_path = $manifest[$path] ?? $path;
        
        // Extract version from filename or use theme version
        if (preg_match('/\?id=([a-f0-9]+)$/', $versioned_path, $matches)) {
            return $matches[1];
        }
        
        return AQUALUXE_VERSION;
    }
    
    /**
     * Get Mix manifest
     *
     * @return array
     */
    private function get_manifest(): array {
        if ($this->manifest === null) {
            $manifest_path = AQUALUXE_PATH . '/assets/dist/mix-manifest.json';
            $this->manifest = file_exists($manifest_path) 
                ? json_decode(file_get_contents($manifest_path), true) 
                : [];
        }
        
        return $this->manifest;
    }
    
    /**
     * Get localization data for JavaScript
     *
     * @return array
     */
    private function get_localization_data(): array {
        return [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_nonce'),
            'theme_url' => AQUALUXE_URI,
            'is_admin' => is_admin(),
            'is_rtl' => is_rtl(),
            'is_mobile' => wp_is_mobile(),
            'woocommerce_active' => aqualuxe_is_woocommerce_active(),
            'strings' => [
                'loading' => __('Loading...', 'aqualuxe'),
                'error' => __('An error occurred. Please try again.', 'aqualuxe'),
                'close' => __('Close', 'aqualuxe'),
                'view_more' => __('View More', 'aqualuxe'),
                'add_to_cart' => __('Add to Cart', 'aqualuxe'),
                'quick_view' => __('Quick View', 'aqualuxe'),
            ],
            'breakpoints' => [
                'sm' => 640,
                'md' => 768,
                'lg' => 1024,
                'xl' => 1280,
                '2xl' => 1536,
            ],
        ];
    }
    
    /**
     * Add resource hints for performance
     *
     * @return void
     */
    public function add_resource_hints(): void {
        // DNS prefetch for external resources
        echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
        echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
        
        // Preconnect for critical resources
        echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    }
    
    /**
     * Add critical CSS inline
     *
     * @return void
     */
    public function add_critical_css(): void {
        $critical_css_file = AQUALUXE_PATH . '/assets/dist/css/critical.css';
        
        if (file_exists($critical_css_file)) {
            $critical_css = file_get_contents($critical_css_file);
            if ($critical_css) {
                echo '<style id="aqualuxe-critical-css">' . $critical_css . '</style>' . "\n";
            }
        }
    }
    
    /**
     * Preload critical assets
     *
     * @return void
     */
    public function preload_assets(): void {
        // Preload main stylesheet
        echo '<link rel="preload" href="' . esc_url($this->get_asset_url('/css/app.css')) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
        
        // Preload main script
        echo '<link rel="preload" href="' . esc_url($this->get_asset_url('/js/app.js')) . '" as="script">' . "\n";
        
        // Preload fonts
        $fonts = [
            '/fonts/Inter-Regular.woff2',
            '/fonts/Inter-Medium.woff2',
            '/fonts/Inter-SemiBold.woff2',
            '/fonts/PlayfairDisplay-Regular.woff2',
        ];
        
        foreach ($fonts as $font) {
            $font_url = AQUALUXE_ASSETS_URI . $font;
            if (file_exists(AQUALUXE_PATH . '/assets/dist' . $font)) {
                echo '<link rel="preload" href="' . esc_url($font_url) . '" as="font" type="font/woff2" crossorigin>' . "\n";
            }
        }
    }
    
    /**
     * Register an asset
     *
     * @param string $handle
     * @param string $path
     * @param array $deps
     * @param string $type
     * @return void
     */
    public function register_asset($handle, $path, $deps = [], $type = 'script'): void {
        $this->assets[$handle] = [
            'path' => $path,
            'deps' => $deps,
            'type' => $type,
            'version' => $this->get_asset_version($path),
        ];
    }
    
    /**
     * Enqueue a registered asset
     *
     * @param string $handle
     * @return bool
     */
    public function enqueue_asset($handle): bool {
        if (!isset($this->assets[$handle])) {
            return false;
        }
        
        $asset = $this->assets[$handle];
        $url = $this->get_asset_url($asset['path']);
        
        if ($asset['type'] === 'style') {
            wp_enqueue_style($handle, $url, $asset['deps'], $asset['version']);
        } else {
            wp_enqueue_script($handle, $url, $asset['deps'], $asset['version'], true);
        }
        
        return true;
    }
}