<?php
/**
 * Theme Assets Manager
 *
 * @package AquaLuxe\Core
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Assets management class
 */
class AquaLuxe_Assets {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('customize_preview_init', array($this, 'enqueue_customizer_preview_assets'));
        add_action('customize_controls_enqueue_scripts', array($this, 'enqueue_customizer_control_assets'));
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        $assets = $this->get_asset_manifest();
        
        // Main stylesheet
        if (isset($assets['css/app.css'])) {
            wp_enqueue_style(
                'aqualuxe-style',
                AQUALUXE_ASSETS_URI . $assets['css/app.css'],
                array(),
                AQUALUXE_VERSION
            );
        }
        
        // WooCommerce stylesheet
        if (aqualuxe_is_woocommerce_active() && isset($assets['css/woocommerce.css'])) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URI . $assets['css/woocommerce.css'],
                array('aqualuxe-style'),
                AQUALUXE_VERSION
            );
        }
        
        // Main JavaScript
        if (isset($assets['js/app.js'])) {
            wp_enqueue_script(
                'aqualuxe-script',
                AQUALUXE_ASSETS_URI . $assets['js/app.js'],
                array('jquery'),
                AQUALUXE_VERSION,
                true
            );
        }
        
        // WooCommerce JavaScript
        if (aqualuxe_is_woocommerce_active() && isset($assets['js/woocommerce.js'])) {
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URI . $assets['js/woocommerce.js'],
                array('jquery', 'wc-add-to-cart', 'wc-cart-fragments'),
                AQUALUXE_VERSION,
                true
            );
        }
        
        // Localize scripts
        wp_localize_script('aqualuxe-script', 'aqualuxe_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_ajax_nonce'),
            'is_rtl' => is_rtl(),
            'language' => get_locale(),
            'is_woocommerce_active' => aqualuxe_is_woocommerce_active(),
            'strings' => array(
                'loading' => __('Loading...', 'aqualuxe'),
                'error' => __('An error occurred. Please try again.', 'aqualuxe'),
                'success' => __('Success!', 'aqualuxe'),
            ),
        ));
        
        // Add custom CSS variables
        $this->add_custom_css_variables();
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        $assets = $this->get_asset_manifest();
        
        // Admin stylesheet
        if (isset($assets['css/admin.css'])) {
            wp_enqueue_style(
                'aqualuxe-admin',
                AQUALUXE_ASSETS_URI . $assets['css/admin.css'],
                array(),
                AQUALUXE_VERSION
            );
        }
        
        // Editor stylesheet
        if (isset($assets['css/editor-style.css'])) {
            add_editor_style(str_replace(AQUALUXE_THEME_URI . '/', '', AQUALUXE_ASSETS_URI . $assets['css/editor-style.css']));
        }
    }
    
    /**
     * Enqueue customizer preview assets
     */
    public function enqueue_customizer_preview_assets() {
        $assets = $this->get_asset_manifest();
        
        if (isset($assets['js/customizer-preview.js'])) {
            wp_enqueue_script(
                'aqualuxe-customizer-preview',
                AQUALUXE_ASSETS_URI . $assets['js/customizer-preview.js'],
                array('jquery', 'customize-preview'),
                AQUALUXE_VERSION,
                true
            );
        }
    }
    
    /**
     * Enqueue customizer control assets
     */
    public function enqueue_customizer_control_assets() {
        $assets = $this->get_asset_manifest();
        
        if (isset($assets['js/customizer.js'])) {
            wp_enqueue_script(
                'aqualuxe-customizer',
                AQUALUXE_ASSETS_URI . $assets['js/customizer.js'],
                array('jquery', 'customize-controls'),
                AQUALUXE_VERSION,
                true
            );
        }
    }
    
    /**
     * Get asset manifest
     */
    private function get_asset_manifest() {
        static $manifest = null;
        
        if ($manifest === null) {
            $manifest_path = AQUALUXE_THEME_DIR . '/assets/mix-manifest.json';
            if (file_exists($manifest_path)) {
                $manifest_content = file_get_contents($manifest_path);
                $manifest = json_decode($manifest_content, true);
                
                // Remove leading slash from manifest keys
                if (is_array($manifest)) {
                    $manifest = array_combine(
                        array_map(function($key) { return ltrim($key, '/'); }, array_keys($manifest)),
                        array_map(function($value) { return ltrim($value, '/'); }, array_values($manifest))
                    );
                }
            } else {
                $manifest = array();
            }
        }
        
        return $manifest;
    }
    
    /**
     * Add custom CSS variables based on theme options
     */
    private function add_custom_css_variables() {
        $primary_color = get_theme_mod('primary_color', '#0ea5e9');
        $secondary_color = get_theme_mod('secondary_color', '#64748b');
        $header_bg = get_theme_mod('header_background_color', '#ffffff');
        $body_font = get_theme_mod('typography_body_font', 'Inter');
        $heading_font = get_theme_mod('typography_heading_font', 'Playfair Display');
        
        $custom_css = "
            :root {
                --color-primary: {$primary_color};
                --color-secondary: {$secondary_color};
                --header-bg: {$header_bg};
                --font-body: '{$body_font}', system-ui, sans-serif;
                --font-heading: '{$heading_font}', Georgia, serif;
            }
        ";
        
        wp_add_inline_style('aqualuxe-style', $custom_css);
    }
    
    /**
     * Preload critical assets
     */
    public function preload_critical_assets() {
        $assets = $this->get_asset_manifest();
        
        // Preload critical CSS
        if (isset($assets['css/app.css'])) {
            echo '<link rel="preload" href="' . esc_url(AQUALUXE_ASSETS_URI . $assets['css/app.css']) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
        }
        
        // Preload critical JS
        if (isset($assets['js/app.js'])) {
            echo '<link rel="preload" href="' . esc_url(AQUALUXE_ASSETS_URI . $assets['js/app.js']) . '" as="script">' . "\n";
        }
    }
}

// Initialize assets manager
new AquaLuxe_Assets();