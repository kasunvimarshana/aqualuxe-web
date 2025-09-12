<?php
/**
 * Asset Manager Class
 * 
 * Handles asset enqueuing and cache busting
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Asset_Manager {
    
    /**
     * Mix manifest data
     */
    private $manifest = null;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->load_manifest();
    }
    
    /**
     * Load mix manifest for cache busting
     */
    private function load_manifest() {
        $manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
        if (file_exists($manifest_path)) {
            $this->manifest = json_decode(file_get_contents($manifest_path), true);
        }
    }
    
    /**
     * Get asset URL with cache busting
     */
    public function get_asset_url($file) {
        if ($this->manifest && isset($this->manifest['/' . $file])) {
            return AQUALUXE_ASSETS_URI . $this->manifest['/' . $file];
        }
        
        return AQUALUXE_ASSETS_URI . '/' . $file;
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        // Main stylesheet
        wp_enqueue_style(
            'aqualuxe-style',
            $this->get_asset_url('css/app.css'),
            [],
            AQUALUXE_VERSION
        );
        
        // WooCommerce styles if active
        if (aqualuxe_is_woocommerce_active() && $this->is_woocommerce_page()) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                $this->get_asset_url('css/woocommerce.css'),
                ['aqualuxe-style'],
                AQUALUXE_VERSION
            );
        }
        
        // Main JavaScript
        wp_enqueue_script(
            'aqualuxe-script',
            $this->get_asset_url('js/app.js'),
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
        
        // Dark mode script
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            $this->get_asset_url('js/modules/dark-mode.js'),
            [],
            AQUALUXE_VERSION,
            true
        );
        
        // WooCommerce scripts if active
        if (aqualuxe_is_woocommerce_active() && $this->is_woocommerce_page()) {
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                $this->get_asset_url('js/modules/woocommerce.js'),
                ['jquery', 'aqualuxe-script'],
                AQUALUXE_VERSION,
                true
            );
        }
        
        // Localize script
        wp_localize_script('aqualuxe-script', 'aqualuxe_vars', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_nonce'),
            'home_url' => home_url('/'),
            'is_rtl' => is_rtl(),
            'is_mobile' => wp_is_mobile(),
            'strings' => [
                'loading' => esc_html__('Loading...', 'aqualuxe'),
                'error' => esc_html__('An error occurred. Please try again.', 'aqualuxe'),
                'success' => esc_html__('Success!', 'aqualuxe'),
            ]
        ]);
        
        // Comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
        
        // FontAwesome (conditionally loaded based on content)
        if ($this->should_load_fontawesome()) {
            wp_enqueue_style(
                'aqualuxe-fontawesome',
                $this->get_asset_url('css/fontawesome.css'),
                [],
                AQUALUXE_VERSION
            );
        }
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        // Admin styles
        wp_enqueue_style(
            'aqualuxe-admin',
            $this->get_asset_url('css/admin.css'),
            [],
            AQUALUXE_VERSION
        );
        
        // Admin scripts
        wp_enqueue_script(
            'aqualuxe-admin',
            $this->get_asset_url('js/admin.js'),
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
        
        // Demo importer on specific pages
        if (strpos($hook, 'aqualuxe-demo-importer') !== false) {
            wp_enqueue_script(
                'aqualuxe-demo-importer',
                $this->get_asset_url('js/modules/demo-importer.js'),
                ['jquery', 'aqualuxe-admin'],
                AQUALUXE_VERSION,
                true
            );
        }
        
        // Customizer scripts
        if ($hook === 'customize.php') {
            wp_enqueue_script(
                'aqualuxe-customizer',
                $this->get_asset_url('js/customizer.js'),
                ['jquery', 'customize-controls'],
                AQUALUXE_VERSION,
                true
            );
            
            wp_enqueue_style(
                'aqualuxe-customizer',
                $this->get_asset_url('css/customizer.css'),
                [],
                AQUALUXE_VERSION
            );
        }
        
        // Localize admin script
        wp_localize_script('aqualuxe-admin', 'aqualuxe_admin_vars', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_admin_nonce'),
            'strings' => [
                'confirm_delete' => esc_html__('Are you sure you want to delete this item?', 'aqualuxe'),
                'confirm_reset' => esc_html__('Are you sure you want to reset all data? This action cannot be undone.', 'aqualuxe'),
                'importing' => esc_html__('Importing...', 'aqualuxe'),
                'import_complete' => esc_html__('Import completed successfully!', 'aqualuxe'),
                'import_error' => esc_html__('Import failed. Please try again.', 'aqualuxe'),
            ]
        ]);
    }
    
    /**
     * Check if we're on a WooCommerce page
     */
    private function is_woocommerce_page() {
        if (!function_exists('is_woocommerce')) {
            return false;
        }
        
        return is_woocommerce() || is_cart() || is_checkout() || is_account_page() || is_shop();
    }
    
    /**
     * Check if FontAwesome should be loaded based on content
     */
    private function should_load_fontawesome() {
        global $post;
        
        // Always load on admin, customizer, and certain pages
        if (is_admin() || is_customize_preview()) {
            return true;
        }
        
        // Load on contact, about, and service pages (common icon usage)
        if (is_page(['contact', 'about', 'services']) || is_front_page()) {
            return true;
        }
        
        // Check post content for FontAwesome classes
        if ($post && (strpos($post->post_content, 'fa-') !== false || strpos($post->post_content, 'fab ') !== false)) {
            return true;
        }
        
        // Load on WooCommerce pages (ratings, social icons)
        if ($this->is_woocommerce_page()) {
            return true;
        }
        
        return false;
    }
}