<?php
/**
 * Assets Management Class
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Assets {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('login_enqueue_scripts', array($this, 'login_enqueue_scripts'));
        add_action('customize_controls_enqueue_scripts', array($this, 'customizer_enqueue_scripts'));
    }
    
    /**
     * Enqueue frontend scripts
     */
    public function enqueue_scripts() {
        $manifest = $this->get_manifest();
        $version = AQUALUXE_VERSION;
        
        // Main theme JavaScript
        if (isset($manifest['/js/app.js'])) {
            wp_enqueue_script(
                'aqualuxe-main',
                AQUALUXE_THEME_URL . '/assets/dist' . $manifest['/js/app.js'],
                array('jquery'),
                $version,
                true
            );
        }
        
        // WooCommerce scripts
        if (class_exists('WooCommerce') && isset($manifest['/js/woocommerce.js'])) {
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                AQUALUXE_THEME_URL . '/assets/dist' . $manifest['/js/woocommerce.js'],
                array('jquery', 'aqualuxe-main'),
                $version,
                true
            );
        }
        
        // Dark mode script
        if (get_theme_mod('aqualuxe_enable_dark_mode', true) && isset($manifest['/js/dark-mode.js'])) {
            wp_enqueue_script(
                'aqualuxe-dark-mode',
                AQUALUXE_THEME_URL . '/assets/dist' . $manifest['/js/dark-mode.js'],
                array('jquery'),
                $version,
                true
            );
        }
        
        // Multilingual script
        if (get_theme_mod('aqualuxe_enable_multilingual', true) && isset($manifest['/js/multilingual.js'])) {
            wp_enqueue_script(
                'aqualuxe-multilingual',
                AQUALUXE_THEME_URL . '/assets/dist' . $manifest['/js/multilingual.js'],
                array('jquery'),
                $version,
                true
            );
        }
        
        // Localize script
        wp_localize_script('aqualuxe-main', 'aqualuxeAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_nonce'),
            'theme_url' => AQUALUXE_THEME_URL,
            'is_woocommerce_active' => class_exists('WooCommerce'),
            'is_rtl' => is_rtl(),
            'dark_mode_enabled' => get_theme_mod('aqualuxe_enable_dark_mode', true),
            'multilingual_enabled' => get_theme_mod('aqualuxe_enable_multilingual', true),
            'strings' => array(
                'loading' => esc_html__('Loading...', 'aqualuxe'),
                'error' => esc_html__('Something went wrong. Please try again.', 'aqualuxe'),
                'success' => esc_html__('Success!', 'aqualuxe'),
                'close' => esc_html__('Close', 'aqualuxe'),
                'view_more' => esc_html__('View More', 'aqualuxe'),
                'add_to_cart' => esc_html__('Add to Cart', 'aqualuxe'),
                'quick_view' => esc_html__('Quick View', 'aqualuxe'),
                'wishlist_add' => esc_html__('Add to Wishlist', 'aqualuxe'),
                'wishlist_remove' => esc_html__('Remove from Wishlist', 'aqualuxe'),
            )
        ));
        
        // Comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
    
    /**
     * Enqueue frontend styles
     */
    public function enqueue_styles() {
        $manifest = $this->get_manifest();
        $version = AQUALUXE_VERSION;
        
        // Main theme stylesheet
        if (isset($manifest['/css/app.css'])) {
            wp_enqueue_style(
                'aqualuxe-main',
                AQUALUXE_THEME_URL . '/assets/dist' . $manifest['/css/app.css'],
                array(),
                $version
            );
        }
        
        // WooCommerce styles
        if (class_exists('WooCommerce') && isset($manifest['/css/woocommerce.css'])) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                AQUALUXE_THEME_URL . '/assets/dist' . $manifest['/css/woocommerce.css'],
                array('aqualuxe-main'),
                $version
            );
        }
        
        // Dark mode styles
        if (get_theme_mod('aqualuxe_enable_dark_mode', true) && isset($manifest['/css/dark-mode.css'])) {
            wp_enqueue_style(
                'aqualuxe-dark-mode',
                AQUALUXE_THEME_URL . '/assets/dist' . $manifest['/css/dark-mode.css'],
                array('aqualuxe-main'),
                $version
            );
        }
        
        // RTL styles
        if (is_rtl() && isset($manifest['/css/rtl.css'])) {
            wp_enqueue_style(
                'aqualuxe-rtl',
                AQUALUXE_THEME_URL . '/assets/dist' . $manifest['/css/rtl.css'],
                array('aqualuxe-main'),
                $version
            );
        }
        
        // Google Fonts
        $this->enqueue_google_fonts();
    }
    
    /**
     * Enqueue admin scripts
     */
    public function admin_enqueue_scripts($hook) {
        $manifest = $this->get_manifest();
        $version = AQUALUXE_VERSION;
        
        // Admin styles
        if (isset($manifest['/css/admin.css'])) {
            wp_enqueue_style(
                'aqualuxe-admin',
                AQUALUXE_THEME_URL . '/assets/dist' . $manifest['/css/admin.css'],
                array(),
                $version
            );
        }
        
        // Admin scripts
        if (isset($manifest['/js/admin.js'])) {
            wp_enqueue_script(
                'aqualuxe-admin',
                AQUALUXE_THEME_URL . '/assets/dist' . $manifest['/js/admin.js'],
                array('jquery'),
                $version,
                true
            );
        }
        
        // Media uploader
        if (in_array($hook, array('post.php', 'post-new.php', 'appearance_page_aqualuxe-options'))) {
            wp_enqueue_media();
        }
    }
    
    /**
     * Enqueue login scripts
     */
    public function login_enqueue_scripts() {
        $manifest = $this->get_manifest();
        $version = AQUALUXE_VERSION;
        
        if (isset($manifest['/css/login.css'])) {
            wp_enqueue_style(
                'aqualuxe-login',
                AQUALUXE_THEME_URL . '/assets/dist' . $manifest['/css/login.css'],
                array(),
                $version
            );
        }
    }
    
    /**
     * Enqueue customizer scripts
     */
    public function customizer_enqueue_scripts() {
        $manifest = $this->get_manifest();
        $version = AQUALUXE_VERSION;
        
        if (isset($manifest['/js/customizer.js'])) {
            wp_enqueue_script(
                'aqualuxe-customizer',
                AQUALUXE_THEME_URL . '/assets/dist' . $manifest['/js/customizer.js'],
                array('jquery', 'customize-controls'),
                $version,
                true
            );
        }
        
        if (isset($manifest['/css/customizer.css'])) {
            wp_enqueue_style(
                'aqualuxe-customizer',
                AQUALUXE_THEME_URL . '/assets/dist' . $manifest['/css/customizer.css'],
                array(),
                $version
            );
        }
    }
    
    /**
     * Get webpack manifest
     */
    private function get_manifest() {
        static $manifest = null;
        
        if ($manifest === null) {
            $manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
            
            if (file_exists($manifest_path)) {
                $manifest = json_decode(file_get_contents($manifest_path), true);
            } else {
                $manifest = array();
            }
        }
        
        return $manifest;
    }
    
    /**
     * Enqueue Google Fonts
     */
    private function enqueue_google_fonts() {
        $primary_font = get_theme_mod('aqualuxe_primary_font', 'Inter');
        $secondary_font = get_theme_mod('aqualuxe_secondary_font', 'Playfair Display');
        
        $fonts = array();
        
        if ($primary_font && $primary_font !== 'default') {
            $fonts[] = $primary_font . ':300,400,500,600,700';
        }
        
        if ($secondary_font && $secondary_font !== 'default' && $secondary_font !== $primary_font) {
            $fonts[] = $secondary_font . ':300,400,500,600,700';
        }
        
        if (!empty($fonts)) {
            $query_args = array(
                'family' => implode('|', $fonts),
                'subset' => 'latin,latin-ext',
                'display' => 'swap',
            );
            
            $font_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
            
            wp_enqueue_style('aqualuxe-google-fonts', $font_url, array(), AQUALUXE_VERSION);
        }
    }
    
    /**
     * Preload critical assets
     */
    public function preload_assets() {
        $manifest = $this->get_manifest();
        
        // Preload critical CSS
        if (isset($manifest['/css/app.css'])) {
            echo '<link rel="preload" href="' . esc_url(AQUALUXE_THEME_URL . '/assets/dist' . $manifest['/css/app.css']) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
        }
        
        // Preload critical JS
        if (isset($manifest['/js/app.js'])) {
            echo '<link rel="preload" href="' . esc_url(AQUALUXE_THEME_URL . '/assets/dist' . $manifest['/js/app.js']) . '" as="script">';
        }
    }
}
