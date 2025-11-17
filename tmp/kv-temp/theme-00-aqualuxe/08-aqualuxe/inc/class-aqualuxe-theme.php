<?php
/**
 * Main Theme Class
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main AquaLuxe Theme Class
 */
class AquaLuxe_Theme {
    
    /**
     * Theme version
     *
     * @var string
     */
    public $version = '1.0.0';
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('init', array($this, 'init'), 0);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_conditional_scripts'));
        add_action('wp_head', array($this, 'add_meta_tags'));
        add_filter('body_class', array($this, 'add_body_classes'));
        add_filter('wp_nav_menu_args', array($this, 'modify_nav_menu_args'));
    }
    
    /**
     * Initialize theme
     */
    public function init() {
        // Set up theme defaults and register support for various WordPress features
        $this->setup_theme_support();
        $this->register_menus();
        $this->add_image_sizes();
    }
    
    /**
     * Setup theme support
     */
    private function setup_theme_support() {
        // Add support for wide and full-width blocks
        add_theme_support('align-wide');
        
        // Add support for responsive embeds
        add_theme_support('responsive-embeds');
        
        // Add support for custom line height
        add_theme_support('custom-line-height');
        
        // Add support for custom units
        add_theme_support('custom-units');
        
        // Add support for custom spacing
        add_theme_support('custom-spacing');
        
        // Add support for block styles
        add_theme_support('wp-block-styles');
        
        // Add support for editor styles
        add_theme_support('editor-styles');
        add_editor_style('assets/css/editor-style.css');
    }
    
    /**
     * Register navigation menus
     */
    private function register_menus() {
        register_nav_menus(array(
            'primary'    => esc_html__('Primary Menu', 'aqualuxe'),
            'footer'     => esc_html__('Footer Menu', 'aqualuxe'),
            'mobile'     => esc_html__('Mobile Menu', 'aqualuxe'),
            'top-bar'    => esc_html__('Top Bar Menu', 'aqualuxe'),
        ));
    }
    
    /**
     * Add custom image sizes
     */
    private function add_image_sizes() {
        add_image_size('aqualuxe-thumbnail', 350, 250, true);
        add_image_size('aqualuxe-medium', 600, 400, true);
        add_image_size('aqualuxe-large', 1200, 800, true);
        add_image_size('aqualuxe-hero', 1920, 1080, true);
        add_image_size('aqualuxe-square', 400, 400, true);
    }
    
    /**
     * Enqueue conditional scripts
     */
    public function enqueue_conditional_scripts() {
        // Enqueue scripts only where needed
        if (is_singular('product') && class_exists('WooCommerce')) {
            wp_enqueue_script('aqualuxe-product-gallery', get_theme_file_uri('/assets/js/product-gallery.js'), array('jquery'), $this->version, true);
        }
        
        if (is_shop() || is_product_category() || is_product_tag()) {
            wp_enqueue_script('aqualuxe-shop-filters', get_theme_file_uri('/assets/js/shop-filters.js'), array('jquery'), $this->version, true);
        }
        
        // Add inline styles for customizer options
        $this->add_customizer_css();
    }
    
    /**
     * Add customizer CSS
     */
    private function add_customizer_css() {
        $primary_color = get_theme_mod('aqualuxe_primary_color', '#0066cc');
        $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#14b8a6');
        $accent_color = get_theme_mod('aqualuxe_accent_color', '#f97316');
        
        $custom_css = "
            :root {
                --primary-color: {$primary_color};
                --secondary-color: {$secondary_color};
                --accent-color: {$accent_color};
            }
        ";
        
        wp_add_inline_style('aqualuxe-style', $custom_css);
    }
    
    /**
     * Add meta tags
     */
    public function add_meta_tags() {
        // Add theme color for mobile browsers
        echo '<meta name="theme-color" content="' . esc_attr(get_theme_mod('aqualuxe_primary_color', '#0066cc')) . '">';
        
        // Add preconnect for Google Fonts
        echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>';
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
        
        // Add DNS prefetch for external resources
        echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">';
        echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">';
    }
    
    /**
     * Add custom body classes
     */
    public function add_body_classes($classes) {
        // Add class for mobile detection
        if (wp_is_mobile()) {
            $classes[] = 'mobile-device';
        }
        
        // Add class for customizer preview
        if (is_customize_preview()) {
            $classes[] = 'customizer-preview';
        }
        
        // Add class for logged in users
        if (is_user_logged_in()) {
            $classes[] = 'logged-in-user';
        }
        
        // Add class for WooCommerce pages
        if (class_exists('WooCommerce')) {
            if (is_woocommerce()) {
                $classes[] = 'woocommerce-active';
            }
            
            if (is_shop()) {
                $classes[] = 'shop-page';
            }
            
            if (is_product()) {
                $classes[] = 'single-product-page';
            }
            
            if (is_cart()) {
                $classes[] = 'cart-page';
            }
            
            if (is_checkout()) {
                $classes[] = 'checkout-page';
            }
        }
        
        return $classes;
    }
    
    /**
     * Modify navigation menu arguments
     */
    public function modify_nav_menu_args($args) {
        // Add custom walker for primary menu
        if (isset($args['theme_location']) && $args['theme_location'] === 'primary') {
            $args['walker'] = new AquaLuxe_Walker_Nav_Menu();
        }
        
        return $args;
    }
    
    /**
     * Get theme option
     */
    public function get_option($option, $default = '') {
        return get_theme_mod("aqualuxe_{$option}", $default);
    }
    
    /**
     * Check if WooCommerce is active
     */
    public function is_woocommerce_active() {
        return class_exists('WooCommerce');
    }
    
    /**
     * Get theme version
     */
    public function get_version() {
        return $this->version;
    }
    
    /**
     * Get theme directory URI
     */
    public function get_theme_uri() {
        return get_template_directory_uri();
    }
    
    /**
     * Get asset URI
     */
    public function get_asset_uri($path) {
        return get_theme_file_uri('/assets/' . ltrim($path, '/'));
    }
}