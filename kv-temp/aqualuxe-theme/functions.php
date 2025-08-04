<?php
/**
 * AquaLuxe Child Theme Functions
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Theme Class
 * Main theme functionality using SOLID principles
 */
class AquaLuxeTheme {
    
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
        $this->init_hooks();
    }
    
    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('after_setup_theme', [$this, 'theme_setup']);
        add_action('widgets_init', [$this, 'register_sidebars']);
        add_action('init', [$this, 'register_menus']);
        
        // WooCommerce hooks
        add_action('after_setup_theme', [$this, 'woocommerce_support']);
        add_filter('woocommerce_enqueue_styles', [$this, 'dequeue_woocommerce_styles']);
        
        // Custom hooks
        add_action('wp_head', [$this, 'add_custom_meta']);
        add_filter('body_class', [$this, 'add_body_classes']);
    }
    
    /**
     * Enqueue theme styles
     */
    public function enqueue_styles() {
        // Parent theme style
        wp_enqueue_style(
            'storefront-style',
            get_template_directory_uri() . '/style.css',
            [],
            wp_get_theme()->get('Version')
        );
        
        // Child theme style
        wp_enqueue_style(
            'aqualuxe-style',
            get_stylesheet_directory_uri() . '/style.css',
            ['storefront-style'],
            self::VERSION
        );
        
        // Google Fonts
        wp_enqueue_style(
            'aqualuxe-fonts',
            'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap',
            [],
            null
        );
    }
    
    /**
     * Enqueue theme scripts
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            'aqualuxe-main',
            get_stylesheet_directory_uri() . '/assets/js/main.js',
            ['jquery'],
            self::VERSION,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('aqualuxe-main', 'aqualuxe_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_nonce'),
            'text_domain' => self::TEXT_DOMAIN
        ]);
    }
    
    /**
     * Theme setup
     */
    public function theme_setup() {
        // Add theme support
        add_theme_support('post-thumbnails');
        add_theme_support('custom-logo');
        add_theme_support('title-tag');
        add_theme_support('custom-background');
        add_theme_support('custom-header');
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption'
        ]);
        
        // Image sizes
        add_image_size('aqualuxe-product-thumb', 300, 300, true);
        add_image_size('aqualuxe-hero', 1200, 600, true);
        add_image_size('aqualuxe-gallery', 800, 600, true);
        
        // Load text domain
        load_child_theme_textdomain(self::TEXT_DOMAIN, get_stylesheet_directory() . '/languages');
    }
    
    /**
     * Register navigation menus
     */
    public function register_menus() {
        register_nav_menus([
            'primary' => __('Primary Menu', self::TEXT_DOMAIN),
            'footer' => __('Footer Menu', self::TEXT_DOMAIN),
            'mobile' => __('Mobile Menu', self::TEXT_DOMAIN)
        ]);
    }
    
    /**
     * Register sidebars
     */
    public function register_sidebars() {
        register_sidebar([
            'name' => __('AquaLuxe Sidebar', self::TEXT_DOMAIN),
            'id' => 'aqualuxe-sidebar',
            'description' => __('Main sidebar for AquaLuxe theme', self::TEXT_DOMAIN),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>'
        ]);
        
        register_sidebar([
            'name' => __('Footer Widget Area', self::TEXT_DOMAIN),
            'id' => 'footer-widgets',
            'description' => __('Footer widget area', self::TEXT_DOMAIN),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="footer-widget-title">',
            'after_title' => '</h3>'
        ]);
    }
    
    /**
     * Add WooCommerce support
     */
    public function woocommerce_support() {
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
    
    /**
     * Dequeue WooCommerce default styles
     */
    public function dequeue_woocommerce_styles($enqueue_styles) {
        unset($enqueue_styles['woocommerce-general']);
        unset($enqueue_styles['woocommerce-layout']);
        unset($enqueue_styles['woocommerce-smallscreen']);
        return $enqueue_styles;
    }
    
    /**
     * Add custom meta tags
     */
    public function add_custom_meta() {
        echo '<meta name="theme-color" content="#0B2447">' . "\n";
        echo '<meta name="msapplication-TileColor" content="#0B2447">' . "\n";
    }
    
    /**
     * Add custom body classes
     */
    public function add_body_classes($classes) {
        $classes[] = 'aqualuxe-theme';
        
        if (is_woocommerce() || is_cart() || is_checkout()) {
            $classes[] = 'aqualuxe-woocommerce';
        }
        
        return $classes;
    }
}

// Initialize theme
new AquaLuxeTheme();

/**
 * Custom Functions
 */

/**
 * Get theme option with default fallback
 */
function aqualuxe_get_option($option, $default = '') {
    return get_theme_mod($option, $default);
}

/**
 * Display custom logo or site title
 */
function aqualuxe_site_branding() {
    if (has_custom_logo()) {
        the_custom_logo();
    } else {
        echo '<h1 class="site-title"><a href="' . esc_url(home_url('/')) . '">' . get_bloginfo('name') . '</a></h1>';
        $description = get_bloginfo('description', 'display');
        if ($description) {
            echo '<p class="site-description">' . esc_html($description) . '</p>';
        }
    }
}

/**
 * Custom breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    if (!is_home() && !is_front_page()) {
        echo '<nav class="breadcrumbs" aria-label="' . __('Breadcrumb', AquaLuxeTheme::TEXT_DOMAIN) . '">';
        echo '<a href="' . esc_url(home_url('/')) . '">' . __('Home', AquaLuxeTheme::TEXT_DOMAIN) . '</a>';
        
        if (is_category() || is_single()) {
            echo ' &raquo; ';
            the_category(' &bull; ');
            if (is_single()) {
                echo ' &raquo; ';
                the_title();
            }
        } elseif (is_page()) {
            echo ' &raquo; ';
            the_title();
        }
        
        echo '</nav>';
    }
}

/**
 * Custom post excerpt
 */
function aqualuxe_excerpt($limit = 20) {
    $excerpt = explode(' ', get_the_excerpt(), $limit);
    if (count($excerpt) >= $limit) {
        array_pop($excerpt);
        $excerpt = implode(' ', $excerpt) . '...';
    } else {
        $excerpt = implode(' ', $excerpt);
    }
    return $excerpt;
}

/**
 * Social media links
 */
function aqualuxe_social_links() {
    $social_links = [
        'facebook' => aqualuxe_get_option('facebook_url'),
        'twitter' => aqualuxe_get_option('twitter_url'),
        'instagram' => aqualuxe_get_option('instagram_url'),
        'youtube' => aqualuxe_get_option('youtube_url')
    ];
    
    echo '<div class="social-links">';
    foreach ($social_links as $platform => $url) {
        if ($url) {
            echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener" class="social-link social-' . $platform . '">';
            echo '<span class="sr-only">' . ucfirst($platform) . '</span>';
            echo '</a>';
        }
    }
    echo '</div>';
}

/**
 * WooCommerce Customizations
 */

// Remove WooCommerce breadcrumbs (we'll use our own)
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

// Customize WooCommerce product loop
add_filter('woocommerce_loop_add_to_cart_link', 'aqualuxe_custom_add_to_cart_button', 10, 2);
function aqualuxe_custom_add_to_cart_button($button, $product) {
    $button = str_replace('class="', 'class="aqualuxe-add-to-cart ', $button);
    return $button;
}

// Add custom product badges
add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_product_badges', 5);
function aqualuxe_product_badges() {
    global $product;
    
    echo '<div class="product-badges">';
    
    if ($product->is_on_sale()) {
        echo '<span class="badge sale-badge">' . __('Sale', AquaLuxeTheme::TEXT_DOMAIN) . '</span>';
    }
    
    if ($product->is_featured()) {
        echo '<span class="badge featured-badge">' . __('Featured', AquaLuxeTheme::TEXT_DOMAIN) . '</span>';
    }
    
    if (!$product->is_in_stock()) {
        echo '<span class="badge out-of-stock-badge">' . __('Out of Stock', AquaLuxeTheme::TEXT_DOMAIN) . '</span>';
    }
    
    echo '</div>';
}

/**
 * Customizer Settings
 */
add_action('customize_register', 'aqualuxe_customize_register');
function aqualuxe_customize_register($wp_customize) {
    // AquaLuxe Settings Panel
    $wp_customize->add_panel('aqualuxe_settings', [
        'title' => __('AquaLuxe Settings', AquaLuxeTheme::TEXT_DOMAIN),
        'priority' => 30
    ]);
    
    // Social Media Section
    $wp_customize->add_section('aqualuxe_social', [
        'title' => __('Social Media', AquaLuxeTheme::TEXT_DOMAIN),
        'panel' => 'aqualuxe_settings'
    ]);
    
    $social_platforms = ['facebook', 'twitter', 'instagram', 'youtube'];
    foreach ($social_platforms as $platform) {
        $wp_customize->add_setting($platform . '_url', [
            'default' => '',
            'sanitize_callback' => 'esc_url_raw'
        ]);
        
        $wp_customize->add_control($platform . '_url', [
            'label' => sprintf(__('%s URL', AquaLuxeTheme::TEXT_DOMAIN), ucfirst($platform)),
            'section' => 'aqualuxe_social',
            'type' => 'url'
        ]);
    }
    
    // Colors Section
    $wp_customize->add_section('aqualuxe_colors', [
        'title' => __('AquaLuxe Colors', AquaLuxeTheme::TEXT_DOMAIN),
        'panel' => 'aqualuxe_settings'
    ]);
    
    $wp_customize->add_setting('primary_color', [
        'default' => '#0B2447',
        'sanitize_callback' => 'sanitize_hex_color'
    ]);
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', [
        'label' => __('Primary Color', AquaLuxeTheme::TEXT_DOMAIN),
        'section' => 'aqualuxe_colors'
    ]));
}

/**
 * Security Functions
 */

// Remove WordPress version from head
remove_action('wp_head', 'wp_generator');

// Disable XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

// Remove unnecessary meta tags
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');

/**
 * Performance Optimizations
 */

// Remove query strings from static resources
function aqualuxe_remove_query_strings($src) {
    $parts = explode('?ver', $src);
    return $parts[0];
}
add_filter('script_loader_src', 'aqualuxe_remove_query_strings', 15, 1);
add_filter('style_loader_src', 'aqualuxe_remove_query_strings', 15, 1);

// Defer JavaScript loading
function aqualuxe_defer_scripts($tag, $handle, $src) {
    $defer_scripts = ['aqualuxe-main'];
    
    if (in_array($handle, $defer_scripts)) {
        return '<script src="' . $src . '" defer></script>' . "\n";
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'aqualuxe_defer_scripts', 10, 3);

/**
 * AJAX Functions
 */

// Quick view functionality
add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view_handler');
add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view_handler');

function aqualuxe_quick_view_handler() {
    check_ajax_referer('aqualuxe_nonce', 'nonce');
    
    $product_id = intval($_POST['product_id']);
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_die();
    }
    
    // Return product data as JSON
    wp_send_json_success([
        'title' => $product->get_name(),
        'price' => $product->get_price_html(),
        'description' => $product->get_short_description(),
        'image' => wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'large')[0]
    ]);
}

/**
 * Error Handling
 */
function aqualuxe_log_error($message, $data = []) {
    if (WP_DEBUG && WP_DEBUG_LOG) {
        error_log('AquaLuxe Theme Error: ' . $message . ' Data: ' . print_r($data, true));
    }
}

/**
 * Utility Functions
 */

// Check if WooCommerce is active
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

// Get product count
function aqualuxe_get_product_count() {
    if (!aqualuxe_is_woocommerce_active()) {
        return 0;
    }
    
    $args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => -1
    ];
    
    $products = new WP_Query($args);
    return $products->found_posts;
}

// Format price with currency
function aqualuxe_format_price($price) {
    if (!aqualuxe_is_woocommerce_active()) {
        return $price;
    }
    
    return wc_price($price);
}
