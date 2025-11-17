<?php
/**
 * AquaLuxe Child Theme Functions
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup Class
 */
class AquaLuxe_Theme_Setup {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles_scripts'));
        add_action('after_setup_theme', array($this, 'theme_setup'));
        add_action('widgets_init', array($this, 'register_sidebars'));
        add_action('init', array($this, 'register_menus'));
        
        // WooCommerce hooks
        add_action('woocommerce_before_shop_loop', array($this, 'add_hero_section'));
        add_filter('woocommerce_product_loop_start', array($this, 'custom_product_loop_start'));
        add_action('woocommerce_before_single_product_summary', array($this, 'add_product_badge'), 25);
        
        // Custom hooks
        add_action('wp_head', array($this, 'add_custom_css_variables'));
        add_filter('body_class', array($this, 'add_body_classes'));
    }
    
    /**
     * Enqueue styles and scripts
     */
    public function enqueue_styles_scripts() {
        // Parent theme style
        wp_enqueue_style('storefront-style', get_template_directory_uri() . '/style.css');
        
        // Child theme style
        wp_enqueue_style(
            'aqualuxe-style',
            get_stylesheet_directory_uri() . '/style.css',
            array('storefront-style'),
            wp_get_theme()->get('Version')
        );
        
        // Google Fonts
        wp_enqueue_style(
            'aqualuxe-fonts',
            'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap',
            array(),
            null
        );
        
        // Custom JavaScript
        wp_enqueue_script(
            'aqualuxe-scripts',
            get_stylesheet_directory_uri() . '/assets/js/custom.js',
            array('jquery'),
            wp_get_theme()->get('Version'),
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('aqualuxe-scripts', 'aqualuxe_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_nonce')
        ));
    }
    
    /**
     * Theme setup
     */
    public function theme_setup() {
        // Add theme support
        add_theme_support('post-thumbnails');
        add_theme_support('custom-logo');
        add_theme_support('custom-header');
        add_theme_support('custom-background');
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));
        
        // WooCommerce support
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        
        // Set content width
        if (!isset($content_width)) {
            $content_width = 1200;
        }
    }
    
    /**
     * Register sidebars
     */
    public function register_sidebars() {
        register_sidebar(array(
            'name' => __('Footer Widget 1', 'aqualuxe'),
            'id' => 'footer-1',
            'description' => __('Add widgets here to appear in your footer.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        
        register_sidebar(array(
            'name' => __('Footer Widget 2', 'aqualuxe'),
            'id' => 'footer-2',
            'description' => __('Add widgets here to appear in your footer.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        
        register_sidebar(array(
            'name' => __('Footer Widget 3', 'aqualuxe'),
            'id' => 'footer-3',
            'description' => __('Add widgets here to appear in your footer.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
    }
    
    /**
     * Register navigation menus
     */
    public function register_menus() {
        register_nav_menus(array(
            'primary' => __('Primary Menu', 'aqualuxe'),
            'footer' => __('Footer Menu', 'aqualuxe'),
        ));
    }
    
    /**
     * Add hero section to shop page
     */
    public function add_hero_section() {
        if (is_shop() && !is_paged()) {
            get_template_part('template-parts/hero-section');
        }
    }
    
    /**
     * Custom product loop start
     */
    public function custom_product_loop_start($html) {
        return '<ul class="products columns-' . wc_get_loop_prop('columns') . '">';
    }
    
    /**
     * Add product badge
     */
    public function add_product_badge() {
        global $product;
        
        if ($product->is_on_sale()) {
            echo '<div class="product-badge sale-badge">' . __('Sale!', 'aqualuxe') . '</div>';
        }
        
        if ($product->is_featured()) {
            echo '<div class="product-badge featured-badge">' . __('Featured', 'aqualuxe') . '</div>';
        }
    }
    
    /**
     * Add custom CSS variables
     */
    public function add_custom_css_variables() {
        $primary_color = get_theme_mod('aqualuxe_primary_color', '#0077be');
        $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#00a8cc');
        $accent_color = get_theme_mod('aqualuxe_accent_color', '#ffd700');
        
        echo "<style>
        :root {
            --primary-color: {$primary_color};
            --secondary-color: {$secondary_color};
            --accent-color: {$accent_color};
        }
        </style>";
    }
    
    /**
     * Add body classes
     */
    public function add_body_classes($classes) {
        $classes[] = 'aqualuxe-theme';
        
        if (is_woocommerce() || is_cart() || is_checkout()) {
            $classes[] = 'woocommerce-page';
        }
        
        return $classes;
    }
}

// Initialize theme
new AquaLuxe_Theme_Setup();

/**
 * Customizer Class
 */
class AquaLuxe_Customizer {
    
    public function __construct() {
        add_action('customize_register', array($this, 'register_customizer_settings'));
    }
    
    /**
     * Register customizer settings
     */
    public function register_customizer_settings($wp_customize) {
        // Add AquaLuxe section
        $wp_customize->add_section('aqualuxe_colors', array(
            'title' => __('AquaLuxe Colors', 'aqualuxe'),
            'priority' => 30,
        ));
        
        // Primary color
        $wp_customize->add_setting('aqualuxe_primary_color', array(
            'default' => '#0077be',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', array(
            'label' => __('Primary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'aqualuxe_primary_color',
        )));
        
        // Secondary color
        $wp_customize->add_setting('aqualuxe_secondary_color', array(
            'default' => '#00a8cc',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', array(
            'label' => __('Secondary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'aqualuxe_secondary_color',
        )));
        
        // Accent color
        $wp_customize->add_setting('aqualuxe_accent_color', array(
            'default' => '#ffd700',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', array(
            'label' => __('Accent Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'aqualuxe_accent_color',
        )));
    }
}

new AquaLuxe_Customizer();

/**
 * Helper Functions
 */

/**
 * Get theme option
 */
function aqualuxe_get_option($option, $default = '') {
    return get_theme_mod($option, $default);
}

/**
 * Display breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    if (function_exists('woocommerce_breadcrumb')) {
        woocommerce_breadcrumb();
    }
}

/**
 * Custom excerpt length
 */
function aqualuxe_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Custom excerpt more
 */
function aqualuxe_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Add custom post types for fish categories
 */
function aqualuxe_register_fish_post_type() {
    $labels = array(
        'name' => _x('Fish Species', 'Post Type General Name', 'aqualuxe'),
        'singular_name' => _x('Fish Species', 'Post Type Singular Name', 'aqualuxe'),
        'menu_name' => __('Fish Species', 'aqualuxe'),
        'name_admin_bar' => __('Fish Species', 'aqualuxe'),
    );
    
    $args = array(
        'label' => __('Fish Species', 'aqualuxe'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
        'show_in_rest' => true,
    );
    
    register_post_type('fish_species', $args);
}
add_action('init', 'aqualuxe_register_fish_post_type', 0);

/**
 * AJAX handler for quick view
 */
function aqualuxe_quick_view_handler() {
    check_ajax_referer('aqualuxe_nonce', 'nonce');
    
    $product_id = intval($_POST['product_id']);
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_die();
    }
    
    // Return product data
    wp_send_json_success(array(
        'title' => $product->get_name(),
        'price' => $product->get_price_html(),
        'description' => $product->get_short_description(),
        'image' => wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'medium')[0],
    ));
}
add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view_handler');
add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view_handler');
