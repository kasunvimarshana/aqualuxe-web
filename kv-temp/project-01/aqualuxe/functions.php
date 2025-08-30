<?php
/**
 * AquaLuxe Child Theme Functions
 *
 * @package AquaLuxe
 * @author Kasun Vimarshana
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Theme Main Class
 */
class AquaLuxe_Theme {
    
    /**
     * Theme version
     */
    const VERSION = '1.0.0';
    
    /**
     * Theme text domain
     */
    const TEXT_DOMAIN = 'aqualuxe';
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init();
    }
    
    /**
     * Initialize theme
     */
    public function init() {
        add_action('after_setup_theme', array($this, 'setup_theme'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('init', array($this, 'load_textdomain'));
        add_action('widgets_init', array($this, 'register_widgets'));
        add_action('customize_register', array($this, 'customize_register'));
        
        // WooCommerce specific hooks
        add_action('woocommerce_before_shop_loop_item_title', array($this, 'add_product_badges'), 15);
        add_filter('woocommerce_loop_add_to_cart_link', array($this, 'add_to_cart_button_classes'), 10, 2);
        
        // Security enhancements
        $this->security_enhancements();
        
        // Performance optimizations
        $this->performance_optimizations();
    }
    
    /**
     * Theme setup
     */
    public function setup_theme() {
        // Add theme support
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        add_theme_support('post-thumbnails');
        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');
        add_theme_support('custom-logo');
        add_theme_support('customize-selective-refresh-widgets');
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption'
        ));
        
        // Set content width
        if (!isset($content_width)) {
            $content_width = 1200;
        }
        
        // Register navigation menus
        register_nav_menus(array(
            'primary' => __('Primary Menu', self::TEXT_DOMAIN),
            'footer' => __('Footer Menu', self::TEXT_DOMAIN),
            'mobile' => __('Mobile Menu', self::TEXT_DOMAIN),
        ));
        
        // Add image sizes
        add_image_size('aqualuxe-hero', 1920, 1080, true);
        add_image_size('aqualuxe-product-thumb', 400, 400, true);
        add_image_size('aqualuxe-blog-thumb', 600, 400, true);
    }
    
    /**
     * Enqueue styles
     */
    public function enqueue_styles() {
        // Parent theme style
        wp_enqueue_style('storefront-style', get_template_directory_uri() . '/style.css');
        
        // Child theme style
        wp_enqueue_style(
            'aqualuxe-style',
            get_stylesheet_directory_uri() . '/style.css',
            array('storefront-style'),
            self::VERSION
        );
        
        // Custom fonts
        wp_enqueue_style(
            'aqualuxe-fonts',
            'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Open+Sans:wght@300;400;600;700&display=swap',
            array(),
            self::VERSION
        );
        
        // Custom CSS for customizer
        $this->add_customizer_css();
    }
    
    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            'aqualuxe-main',
            get_stylesheet_directory_uri() . '/assets/js/main.js',
            array('jquery'),
            self::VERSION,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('aqualuxe-main', 'aqualuxe_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_nonce'),
            'text_domain' => self::TEXT_DOMAIN
        ));
        
        // Add to cart AJAX for WooCommerce
        if (class_exists('WooCommerce')) {
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                get_stylesheet_directory_uri() . '/assets/js/woocommerce.js',
                array('jquery', 'wc-add-to-cart'),
                self::VERSION,
                true
            );
        }
    }
    
    /**
     * Load text domain
     */
    public function load_textdomain() {
        load_child_theme_textdomain(self::TEXT_DOMAIN, get_stylesheet_directory() . '/languages');
    }
    
    /**
     * Register widgets
     */
    public function register_widgets() {
        register_sidebar(array(
            'name' => __('AquaLuxe Sidebar', self::TEXT_DOMAIN),
            'id' => 'aqualuxe-sidebar',
            'description' => __('Sidebar for AquaLuxe theme', self::TEXT_DOMAIN),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        
        register_sidebar(array(
            'name' => __('Footer Column 1', self::TEXT_DOMAIN),
            'id' => 'footer-1',
            'description' => __('First footer column', self::TEXT_DOMAIN),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        
        register_sidebar(array(
            'name' => __('Footer Column 2', self::TEXT_DOMAIN),
            'id' => 'footer-2',
            'description' => __('Second footer column', self::TEXT_DOMAIN),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        
        register_sidebar(array(
            'name' => __('Footer Column 3', self::TEXT_DOMAIN),
            'id' => 'footer-3',
            'description' => __('Third footer column', self::TEXT_DOMAIN),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
    }
    
    /**
     * Customizer settings
     */
    public function customize_register($wp_customize) {
        // AquaLuxe Settings Panel
        $wp_customize->add_panel('aqualuxe_panel', array(
            'title' => __('AquaLuxe Settings', self::TEXT_DOMAIN),
            'priority' => 30,
        ));
        
        // Colors Section
        $wp_customize->add_section('aqualuxe_colors', array(
            'title' => __('Colors', self::TEXT_DOMAIN),
            'panel' => 'aqualuxe_panel',
        ));
        
        // Primary Color
        $wp_customize->add_setting('aqualuxe_primary_color', array(
            'default' => '#0066cc',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', array(
            'label' => __('Primary Color', self::TEXT_DOMAIN),
            'section' => 'aqualuxe_colors',
        )));
        
        // Secondary Color
        $wp_customize->add_setting('aqualuxe_secondary_color', array(
            'default' => '#00ccaa',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', array(
            'label' => __('Secondary Color', self::TEXT_DOMAIN),
            'section' => 'aqualuxe_colors',
        )));
        
        // Typography Section
        $wp_customize->add_section('aqualuxe_typography', array(
            'title' => __('Typography', self::TEXT_DOMAIN),
            'panel' => 'aqualuxe_panel',
        ));
        
        // Header Section
        $wp_customize->add_section('aqualuxe_header', array(
            'title' => __('Header Settings', self::TEXT_DOMAIN),
            'panel' => 'aqualuxe_panel',
        ));
        
        // Hero Section
        $wp_customize->add_section('aqualuxe_hero', array(
            'title' => __('Hero Section', self::TEXT_DOMAIN),
            'panel' => 'aqualuxe_panel',
        ));
        
        // Hero Title
        $wp_customize->add_setting('aqualuxe_hero_title', array(
            'default' => __('Premium Ornamental Fish Collection', self::TEXT_DOMAIN),
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_hero_title', array(
            'label' => __('Hero Title', self::TEXT_DOMAIN),
            'section' => 'aqualuxe_hero',
            'type' => 'text',
        ));
        
        // Hero Subtitle
        $wp_customize->add_setting('aqualuxe_hero_subtitle', array(
            'default' => __('Discover the beauty and elegance of premium ornamental fish for your aquarium', self::TEXT_DOMAIN),
            'sanitize_callback' => 'sanitize_textarea_field',
        ));
        
        $wp_customize->add_control('aqualuxe_hero_subtitle', array(
            'label' => __('Hero Subtitle', self::TEXT_DOMAIN),
            'section' => 'aqualuxe_hero',
            'type' => 'textarea',
        ));
        
        // Hero Button Text
        $wp_customize->add_setting('aqualuxe_hero_button_text', array(
            'default' => __('Shop Now', self::TEXT_DOMAIN),
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_hero_button_text', array(
            'label' => __('Hero Button Text', self::TEXT_DOMAIN),
            'section' => 'aqualuxe_hero',
            'type' => 'text',
        ));
        
        // Hero Button URL
        $wp_customize->add_setting('aqualuxe_hero_button_url', array(
            'default' => '#',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control('aqualuxe_hero_button_url', array(
            'label' => __('Hero Button URL', self::TEXT_DOMAIN),
            'section' => 'aqualuxe_hero',
            'type' => 'url',
        ));
    }
    
    /**
     * Add customizer CSS
     */
    private function add_customizer_css() {
        $primary_color = get_theme_mod('aqualuxe_primary_color', '#0066cc');
        $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#00ccaa');
        
        $custom_css = "
            :root {
                --aqualuxe-primary: {$primary_color};
                --aqualuxe-secondary: {$secondary_color};
            }
        ";
        
        wp_add_inline_style('aqualuxe-style', $custom_css);
    }
    
    /**
     * Add product badges
     */
    public function add_product_badges() {
        global $product;
        
        if ($product->is_on_sale()) {
            echo '<span class="aqualuxe-badge sale-badge">' . esc_html__('Sale', self::TEXT_DOMAIN) . '</span>';
        }
        
        if ($product->is_featured()) {
            echo '<span class="aqualuxe-badge featured-badge">' . esc_html__('Featured', self::TEXT_DOMAIN) . '</span>';
        }
        
        if (!$product->is_in_stock()) {
            echo '<span class="aqualuxe-badge out-of-stock-badge">' . esc_html__('Out of Stock', self::TEXT_DOMAIN) . '</span>';
        }
    }
    
    /**
     * Add custom classes to add to cart button
     */
    public function add_to_cart_button_classes($button, $product) {
        $button = str_replace('class="button', 'class="button aqualuxe-btn', $button);
        return $button;
    }
    
    /**
     * Security enhancements
     */
    private function security_enhancements() {
        // Remove WordPress version from head
        remove_action('wp_head', 'wp_generator');
        
        // Remove WooCommerce version
        remove_action('wp_head', array('WC', 'generator'));
        
        // Disable XML-RPC
        add_filter('xmlrpc_enabled', '__return_false');
        
        // Remove REST API links
        remove_action('wp_head', 'rest_output_link_wp_head');
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        
        // Sanitize all inputs
        add_filter('pre_get_posts', array($this, 'sanitize_query_vars'));
    }
    
    /**
     * Performance optimizations
     */
    private function performance_optimizations() {
        // Remove query strings from static resources
        add_filter('script_loader_src', array($this, 'remove_query_strings'), 15, 1);
        add_filter('style_loader_src', array($this, 'remove_query_strings'), 15, 1);
        
        // Optimize database queries
        add_action('init', array($this, 'optimize_database_queries'));
        
        // Enable Gzip compression
        add_action('init', array($this, 'enable_gzip_compression'));
    }
    
    /**
     * Sanitize query vars
     */
    public function sanitize_query_vars($query) {
        if (!is_admin() && $query->is_main_query()) {
            // Add security checks here
        }
        return $query;
    }
    
    /**
     * Remove query strings from static resources
     */
    public function remove_query_strings($src) {
        $rqs = strpos($src, '?ver');
        if ($rqs) {
            $src = substr($src, 0, $rqs);
        }
        return $src;
    }
    
    /**
     * Optimize database queries
     */
    public function optimize_database_queries() {
        // Remove unnecessary queries
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
    }
    
    /**
     * Enable Gzip compression
     */
    public function enable_gzip_compression() {
        if (!ob_start('ob_gzhandler')) {
            ob_start();
        }
    }
}

// Initialize theme
new AquaLuxe_Theme();

/**
 * Helper Functions
 */

/**
 * Get theme option
 */
function aqualuxe_get_option($option_name, $default = '') {
    return get_theme_mod($option_name, $default);
}

/**
 * Display hero section
 */
function aqualuxe_display_hero() {
    if (is_front_page()) {
        get_template_part('template-parts/hero-section');
    }
}
add_action('storefront_homepage', 'aqualuxe_display_hero', 5);

/**
 * Custom WooCommerce hooks
 */
if (class_exists('WooCommerce')) {
    
    /**
     * Remove default WooCommerce styles
     */
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');
    
    /**
     * Add custom product fields
     */
    add_action('woocommerce_product_options_general_product_data', 'aqualuxe_add_custom_product_fields');
    add_action('woocommerce_process_product_meta', 'aqualuxe_save_custom_product_fields');
    
    /**
     * Display custom product fields
     */
    function aqualuxe_add_custom_product_fields() {
        woocommerce_wp_text_input(array(
            'id' => '_fish_origin',
            'label' => __('Fish Origin', AquaLuxe_Theme::TEXT_DOMAIN),
            'placeholder' => __('Enter fish origin country', AquaLuxe_Theme::TEXT_DOMAIN),
            'desc_tip' => 'true',
            'description' => __('The country where the fish originates from.', AquaLuxe_Theme::TEXT_DOMAIN)
        ));
        
        woocommerce_wp_text_input(array(
            'id' => '_fish_size',
            'label' => __('Adult Size (cm)', AquaLuxe_Theme::TEXT_DOMAIN),
            'placeholder' => __('Enter adult size', AquaLuxe_Theme::TEXT_DOMAIN),
            'type' => 'number',
            'desc_tip' => 'true',
            'description' => __('The adult size of the fish in centimeters.', AquaLuxe_Theme::TEXT_DOMAIN)
        ));
        
        woocommerce_wp_select(array(
            'id' => '_care_level',
            'label' => __('Care Level', AquaLuxe_Theme::TEXT_DOMAIN),
            'options' => array(
                '' => __('Select care level', AquaLuxe_Theme::TEXT_DOMAIN),
                'beginner' => __('Beginner', AquaLuxe_Theme::TEXT_DOMAIN),
                'intermediate' => __('Intermediate', AquaLuxe_Theme::TEXT_DOMAIN),
                'expert' => __('Expert', AquaLuxe_Theme::TEXT_DOMAIN)
            ),
            'desc_tip' => 'true',
            'description' => __('The care level required for this fish.', AquaLuxe_Theme::TEXT_DOMAIN)
        ));
    }
    
    /**
     * Save custom product fields
     */
    function aqualuxe_save_custom_product_fields($post_id) {
        $fish_origin = sanitize_text_field($_POST['_fish_origin']);
        $fish_size = sanitize_text_field($_POST['_fish_size']);
        $care_level = sanitize_text_field($_POST['_care_level']);
        
        update_post_meta($post_id, '_fish_origin', $fish_origin);
        update_post_meta($post_id, '_fish_size', $fish_size);
        update_post_meta($post_id, '_care_level', $care_level);
    }
    
    /**
     * Display custom fields on single product
     */
    add_action('woocommerce_single_product_summary', 'aqualuxe_display_custom_product_fields', 25);
    function aqualuxe_display_custom_product_fields() {
        global $product;
        
        $fish_origin = get_post_meta($product->get_id(), '_fish_origin', true);
        $fish_size = get_post_meta($product->get_id(), '_fish_size', true);
        $care_level = get_post_meta($product->get_id(), '_care_level', true);
        
        if ($fish_origin || $fish_size || $care_level) {
            echo '<div class="aqualuxe-product-details">';
            echo '<h3>' . __('Fish Details', AquaLuxe_Theme::TEXT_DOMAIN) . '</h3>';
            echo '<ul>';
            
            if ($fish_origin) {
                echo '<li><strong>' . __('Origin:', AquaLuxe_Theme::TEXT_DOMAIN) . '</strong> ' . esc_html($fish_origin) . '</li>';
            }
            
            if ($fish_size) {
                echo '<li><strong>' . __('Adult Size:', AquaLuxe_Theme::TEXT_DOMAIN) . '</strong> ' . esc_html($fish_size) . ' cm</li>';
            }
            
            if ($care_level) {
                echo '<li><strong>' . __('Care Level:', AquaLuxe_Theme::TEXT_DOMAIN) . '</strong> ' . esc_html(ucfirst($care_level)) . '</li>';
            }
            
            echo '</ul>';
            echo '</div>';
        }
    }
}

/**
 * AJAX handlers
 */
add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view_handler');
add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view_handler');

function aqualuxe_quick_view_handler() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_nonce')) {
        wp_die(__('Security check failed', AquaLuxe_Theme::TEXT_DOMAIN));
    }
    
    $product_id = intval($_POST['product_id']);
    $product = wc_get_product($product_id);
    
    if ($product) {
        // Return product data for quick view
        wp_send_json_success(array(
            'title' => $product->get_name(),
            'price' => $product->get_price_html(),
            'description' => $product->get_short_description(),
            'image' => wp_get_attachment_image_url($product->get_image_id(), 'full'),
            'add_to_cart_url' => $product->add_to_cart_url()
        ));
    } else {
        wp_send_json_error(__('Product not found', AquaLuxe_Theme::TEXT_DOMAIN));
    }
}

/**
 * SEO Enhancements
 */
class AquaLuxe_SEO {
    
    public function __construct() {
        add_action('wp_head', array($this, 'add_schema_markup'));
        add_filter('wpseo_breadcrumb_links', array($this, 'custom_breadcrumb'));
    }
    
    /**
     * Add schema markup
     */
    public function add_schema_markup() {
        if (is_singular('product')) {
            global $product;
            
            $schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => $product->get_name(),
                'description' => $product->get_short_description(),
                'sku' => $product->get_sku(),
                'offers' => array(
                    '@type' => 'Offer',
                    'price' => $product->get_price(),
                    'priceCurrency' => get_woocommerce_currency(),
                    'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock'
                )
            );
            
            if ($product->get_image_id()) {
                $schema['image'] = wp_get_attachment_image_url($product->get_image_id(), 'full');
            }
            
            echo '<script type="application/ld+json">' . json_encode($schema) . '</script>';
        }
    }
    
    /**
     * Custom breadcrumb
     */
    public function custom_breadcrumb($links) {
        // Customize breadcrumb for shop
        return $links;
    }
}

new AquaLuxe_SEO();