<?php
/**
 * Assets management class
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Assets {
    
    /**
     * Get mix manifest
     */
    private static function get_mix_manifest() {
        static $manifest = null;
        
        if ($manifest === null) {
            $manifest_path = AQUALUXE_ASSETS_DIR . '/dist/mix-manifest.json';
            if (file_exists($manifest_path)) {
                $manifest = json_decode(file_get_contents($manifest_path), true);
            } else {
                $manifest = [];
            }
        }
        
        return $manifest;
    }
    
    /**
     * Get versioned asset URL
     */
    private static function get_asset_url($path) {
        $manifest = self::get_mix_manifest();
        $versioned_path = isset($manifest[$path]) ? $manifest[$path] : $path;
        return AQUALUXE_ASSETS_URI . '/dist' . $versioned_path;
    }
    
    /**
     * Enqueue frontend assets
     */
    public static function enqueue_frontend_assets() {
        // Main CSS
        wp_enqueue_style(
            'aqualuxe-main',
            self::get_asset_url('/css/main.css'),
            [],
            AQUALUXE_VERSION
        );
        
        // Main JavaScript
        wp_enqueue_script(
            'aqualuxe-main',
            self::get_asset_url('/js/main.js'),
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script('aqualuxe-main', 'aqualuxe_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_nonce'),
            'strings' => [
                'loading' => esc_html__('Loading...', 'aqualuxe'),
                'error' => esc_html__('An error occurred. Please try again.', 'aqualuxe'),
                'success' => esc_html__('Success!', 'aqualuxe'),
            ],
        ]);
        
        // Comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
        
        // Load WooCommerce assets if needed
        if (class_exists('WooCommerce') && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())) {
            self::enqueue_woocommerce_assets();
        }
    }
    
    /**
     * Enqueue WooCommerce specific assets
     */
    private static function enqueue_woocommerce_assets() {
        wp_enqueue_style(
            'aqualuxe-woocommerce',
            self::get_asset_url('/css/woocommerce.css'),
            ['aqualuxe-main'],
            AQUALUXE_VERSION
        );
        
        wp_enqueue_script(
            'aqualuxe-woocommerce',
            self::get_asset_url('/js/woocommerce.js'),
            ['aqualuxe-main', 'jquery'],
            AQUALUXE_VERSION,
            true
        );
    }
    
    /**
     * Enqueue admin assets
     */
    public static function enqueue_admin_assets() {
        $screen = get_current_screen();
        
        // Admin CSS
        wp_enqueue_style(
            'aqualuxe-admin',
            self::get_asset_url('/css/admin.css'),
            [],
            AQUALUXE_VERSION
        );
        
        // Admin JavaScript
        wp_enqueue_script(
            'aqualuxe-admin',
            self::get_asset_url('/js/admin.js'),
            ['jquery', 'wp-util'],
            AQUALUXE_VERSION,
            true
        );
        
        // Localize admin script
        wp_localize_script('aqualuxe-admin', 'aqualuxe_admin', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_admin_nonce'),
            'strings' => [
                'confirm_reset' => esc_html__('Are you sure you want to reset all data? This action cannot be undone.', 'aqualuxe'),
                'importing' => esc_html__('Importing...', 'aqualuxe'),
                'import_complete' => esc_html__('Import completed successfully!', 'aqualuxe'),
                'import_error' => esc_html__('Import failed. Please try again.', 'aqualuxe'),
            ],
        ]);
        
        // Media uploader
        if ($screen && in_array($screen->id, ['appearance_page_aqualuxe-demo-import', 'customize'])) {
            wp_enqueue_media();
        }
    }
    
    /**
     * Enqueue customizer assets
     */
    public static function enqueue_customizer_assets() {
        wp_enqueue_script(
            'aqualuxe-customizer',
            self::get_asset_url('/js/customizer.js'),
            ['customize-preview'],
            AQUALUXE_VERSION,
            true
        );
    }
    
    /**
     * Enqueue editor assets
     */
    public static function enqueue_editor_assets() {
        wp_enqueue_style(
            'aqualuxe-editor',
            self::get_asset_url('/css/editor.css'),
            [],
            AQUALUXE_VERSION
        );
    }
}
