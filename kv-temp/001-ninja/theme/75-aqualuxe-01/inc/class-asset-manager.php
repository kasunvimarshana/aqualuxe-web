<?php
/**
 * AquaLuxe Asset Manager
 * 
 * Handles all theme assets with webpack integration,
 * cache busting, and performance optimization.
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Asset_Manager {
    
    private $manifest;
    private $manifest_path;
    
    public function __construct() {
        $this->manifest_path = AQUALUXE_PATH . '/assets/dist/mix-manifest.json';
        $this->load_manifest();
    }
    
    /**
     * Load webpack manifest for cache busting
     */
    private function load_manifest() {
        if (file_exists($this->manifest_path)) {
            $this->manifest = json_decode(file_get_contents($this->manifest_path), true);
        } else {
            $this->manifest = [];
        }
    }
    
    /**
     * Get versioned asset URL
     */
    public function get_asset_url($path) {
        // Remove leading slash if present
        $path = ltrim($path, '/');
        
        // Check if asset exists in manifest
        if (isset($this->manifest['/' . $path])) {
            return AQUALUXE_ASSETS_URL . $this->manifest['/' . $path];
        }
        
        // Fallback to non-versioned asset
        return AQUALUXE_ASSETS_URL . '/' . $path;
    }
    
    /**
     * Enqueue theme assets
     */
    public function enqueue_assets() {
        // Main theme styles
        wp_enqueue_style(
            'aqualuxe-main',
            $this->get_asset_url('css/main.css'),
            [],
            AQUALUXE_VERSION
        );
        
        // Main theme scripts
        wp_enqueue_script(
            'aqualuxe-main',
            $this->get_asset_url('js/main.js'),
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('aqualuxe-main', 'aqualuxe_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_nonce'),
            'text_domain' => 'aqualuxe'
        ]);
        
        // Conditional WooCommerce assets
        if (aqualuxe_is_woocommerce_active() && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                $this->get_asset_url('css/woocommerce.css'),
                ['aqualuxe-main'],
                AQUALUXE_VERSION
            );
            
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                $this->get_asset_url('js/woocommerce.js'),
                ['aqualuxe-main'],
                AQUALUXE_VERSION,
                true
            );
        }
        
        // Load module assets
        $this->enqueue_module_assets();
        
        // Custom CSS from customizer
        $this->add_customizer_css();
    }
    
    /**
     * Enqueue module-specific assets
     */
    private function enqueue_module_assets() {
        $theme = aqualuxe();
        
        // Dark mode module
        if ($theme->is_module_active('dark-mode')) {
            wp_enqueue_style(
                'aqualuxe-dark-mode',
                $this->get_asset_url('css/modules/dark-mode.css'),
                ['aqualuxe-main'],
                AQUALUXE_VERSION
            );
        }
        
        // Multilingual module
        if ($theme->is_module_active('multilingual')) {
            wp_enqueue_script(
                'aqualuxe-multilingual',
                $this->get_asset_url('js/modules/multilingual.js'),
                ['aqualuxe-main'],
                AQUALUXE_VERSION,
                true
            );
        }
        
        // Events module
        if ($theme->is_module_active('events') && (is_page_template('page-events.php') || is_singular('event'))) {
            wp_enqueue_style(
                'aqualuxe-events',
                $this->get_asset_url('css/modules/events.css'),
                ['aqualuxe-main'],
                AQUALUXE_VERSION
            );
            
            wp_enqueue_script(
                'aqualuxe-events',
                $this->get_asset_url('js/modules/events.js'),
                ['aqualuxe-main'],
                AQUALUXE_VERSION,
                true
            );
        }
        
        // Bookings module
        if ($theme->is_module_active('bookings') && (is_page_template('page-services.php') || is_singular('booking'))) {
            wp_enqueue_style(
                'aqualuxe-bookings',
                $this->get_asset_url('css/modules/bookings.css'),
                ['aqualuxe-main'],
                AQUALUXE_VERSION
            );
            
            wp_enqueue_script(
                'aqualuxe-bookings',
                $this->get_asset_url('js/modules/bookings.js'),
                ['aqualuxe-main'],
                AQUALUXE_VERSION,
                true
            );
        }
    }
    
    /**
     * Add customizer CSS
     */
    private function add_customizer_css() {
        $custom_css = $this->generate_customizer_css();
        
        if (!empty($custom_css)) {
            wp_add_inline_style('aqualuxe-main', $custom_css);
        }
    }
    
    /**
     * Generate CSS from customizer options
     */
    private function generate_customizer_css() {
        $css = '';
        
        // Primary color
        $primary_color = get_theme_mod('aqualuxe_primary_color', '#0EA5E9');
        if ($primary_color !== '#0EA5E9') {
            $css .= "
                :root {
                    --color-primary: {$primary_color};
                }
            ";
        }
        
        // Secondary color
        $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#06B6D4');
        if ($secondary_color !== '#06B6D4') {
            $css .= "
                :root {
                    --color-secondary: {$secondary_color};
                }
            ";
        }
        
        // Custom typography
        $font_family = get_theme_mod('aqualuxe_font_family', 'Inter');
        if ($font_family !== 'Inter') {
            $css .= "
                :root {
                    --font-primary: '{$font_family}', sans-serif;
                }
            ";
        }
        
        // Custom logo sizing
        $logo_width = get_theme_mod('aqualuxe_logo_width', 200);
        if ($logo_width !== 200) {
            $css .= "
                .site-logo img {
                    max-width: {$logo_width}px;
                }
            ";
        }
        
        return $css;
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets() {
        $screen = get_current_screen();
        
        // Customizer preview assets
        if ($screen && $screen->id === 'customize') {
            wp_enqueue_script(
                'aqualuxe-customizer-preview',
                $this->get_asset_url('js/customizer-preview.js'),
                ['jquery'],
                AQUALUXE_VERSION,
                true
            );
        }
        
        // Theme admin styles
        wp_enqueue_style(
            'aqualuxe-admin',
            $this->get_asset_url('css/admin.css'),
            [],
            AQUALUXE_VERSION
        );
    }
    
    /**
     * Preload critical assets
     */
    public function preload_critical_assets() {
        echo '<link rel="preload" href="' . esc_url($this->get_asset_url('css/main.css')) . '" as="style">' . "\n";
        echo '<link rel="preload" href="' . esc_url($this->get_asset_url('js/main.js')) . '" as="script">' . "\n";
        
        // Preload critical fonts
        $fonts = [
            'fonts/inter-var.woff2',
            'fonts/playfair-display.woff2'
        ];
        
        foreach ($fonts as $font) {
            if (file_exists(AQUALUXE_PATH . '/assets/dist/' . $font)) {
                echo '<link rel="preload" href="' . esc_url($this->get_asset_url($font)) . '" as="font" type="font/woff2" crossorigin>' . "\n";
            }
        }
    }
}

// Initialize asset manager hooks
add_action('wp_head', function() {
    $asset_manager = new AquaLuxe_Asset_Manager();
    $asset_manager->preload_critical_assets();
}, 1);

add_action('admin_enqueue_scripts', function() {
    $asset_manager = new AquaLuxe_Asset_Manager();
    $asset_manager->enqueue_admin_assets();
});
