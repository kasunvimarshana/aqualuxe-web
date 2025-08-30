<?php
/**
 * AquaLuxe Child Theme Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_THEME_DIR', get_template_directory());
define('AQUALUXE_CHILD_THEME_DIR', get_stylesheet_directory());
define('AQUALUXE_CHILD_THEME_URI', get_stylesheet_directory_uri());

/**
 * Enqueue styles and scripts
 */
function aqualuxe_enqueue_scripts() {
    // Enqueue parent theme stylesheet
    wp_enqueue_style('storefront-style', AQUALUXE_THEME_DIR . '/style.css');
    
    // Enqueue child theme stylesheet
    wp_enqueue_style('aqualuxe-style', AQUALUXE_CHILD_THEME_URI . '/style.css', array('storefront-style'), AQUALUXE_VERSION);
    
    // Enqueue child theme JavaScript
    wp_enqueue_script('aqualuxe-js', AQUALUXE_CHILD_THEME_URI . '/assets/js/main.js', array('jquery'), AQUALUXE_VERSION, true);
    
    // Localize script for AJAX functionality
    wp_localize_script('aqualuxe-js', 'aqualuxe_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_scripts');

/**
 * Theme setup
 */
function aqualuxe_setup() {
    // Load text domain for translation
    load_child_theme_textdomain('aqualuxe', AQUALUXE_CHILD_THEME_DIR . '/languages');
    
    // Add theme support for various features
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    add_theme_support('custom-logo');
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_theme_support('responsive-embeds');
    
    // Add image sizes
    add_image_size('aqualuxe-product-large', 800, 800, true);
    add_image_size('aqualuxe-product-medium', 400, 400, true);
    add_image_size('aqualuxe-product-small', 200, 200, true);
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'aqualuxe'),
        'secondary' => __('Secondary Menu', 'aqualuxe'),
        'handheld' => __('Handheld Menu', 'aqualuxe'),
    ));
}
add_action('after_setup_theme', 'aqualuxe_setup');

/**
 * Register widget areas
 */
function aqualuxe_widgets_init() {
    // Shop sidebar
    register_sidebar(array(
        'name' => __('Shop Sidebar', 'aqualuxe'),
        'id' => 'sidebar-shop',
        'description' => __('Widgets displayed on shop pages.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    // Footer widget areas
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar(array(
            'name' => sprintf(__('Footer %d', 'aqualuxe'), $i),
            'id' => 'footer-' . $i,
            'description' => sprintf(__('Footer widget area %d.', 'aqualuxe'), $i),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
    }
}
add_action('widgets_init', 'aqualuxe_widgets_init');

/**
 * WooCommerce specific functions
 */
if (class_exists('WooCommerce')) {
    /**
     * Remove default Storefront actions
     */
    function aqualuxe_remove_storefront_actions() {
        // Remove default wrappers
        remove_action('woocommerce_before_main_content', 'storefront_before_content', 10);
        remove_action('woocommerce_after_main_content', 'storefront_after_content', 10);
        
        // Remove default breadcrumbs
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
    }
    add_action('init', 'aqualuxe_remove_storefront_actions');
    
    /**
     * Add custom WooCommerce wrappers
     */
    function aqualuxe_woocommerce_wrapper_before() {
        echo '<div id="primary" class="content-area">';
        echo '<main id="main" class="site-main" role="main">';
    }
    add_action('woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before', 10);
    
    function aqualuxe_woocommerce_wrapper_after() {
        echo '</main>';
        echo '</div>';
    }
    add_action('woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_after', 10);
    
    /**
     * Add breadcrumbs
     */
    function aqualuxe_woocommerce_breadcrumbs() {
        if (!is_front_page()) {
            woocommerce_breadcrumb();
        }
    }
    add_action('woocommerce_before_main_content', 'aqualuxe_woocommerce_breadcrumbs', 20);
    
    /**
     * Customize product gallery
     */
    function aqualuxe_woocommerce_product_gallery_options($options) {
        $options['flexslider']['directionNav'] = true;
        $options['flexslider']['controlNav'] = 'thumbnails';
        return $options;
    }
    add_filter('woocommerce_single_product_carousel_options', 'aqualuxe_woocommerce_product_gallery_options');
}

/**
 * Customizer additions
 */
require_once AQUALUXE_CHILD_THEME_DIR . '/inc/customizer.php';

/**
 * Demo content system
 */
require_once AQUALUXE_CHILD_THEME_DIR . '/inc/demo-content.php';

/**
 * SEO enhancements
 */
require_once AQUALUXE_CHILD_THEME_DIR . '/inc/seo.php';

/**
 * Security enhancements
 */
require_once AQUALUXE_CHILD_THEME_DIR . '/inc/security.php';

/**
 * AJAX handlers
 */
require_once AQUALUXE_CHILD_THEME_DIR . '/inc/ajax-handlers.php';

/**
 * Template functions
 */
require_once AQUALUXE_CHILD_THEME_DIR . '/inc/template-functions.php';

/**
 * Template hooks
 */
require_once AQUALUXE_CHILD_THEME_DIR . '/inc/template-hooks.php';