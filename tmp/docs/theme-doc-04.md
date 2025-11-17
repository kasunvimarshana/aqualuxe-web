<?php
/**
 * AquaLuxe WordPress + WooCommerce Theme
 * Premium, Production-Ready, Commercial-Grade Theme
 * 
 * File Structure:
 * /aqualuxe/
 * ├── style.css
 * ├── functions.php
 * ├── index.php
 * ├── header.php
 * ├── footer.php
 * ├── page.php
 * ├── single.php
 * ├── archive.php
 * ├── search.php
 * ├── 404.php
 * ├── screenshot.png
 * ├── assets/
 * │   ├── css/
 * │   │   ├── main.css
 * │   │   └── woocommerce.css
 * │   ├── js/
 * │   │   ├── main.js
 * │   │   └── woocommerce.js
 * │   └── images/
 * ├── inc/
 * │   ├── class-theme-setup.php
 * │   ├── class-customizer.php
 * │   ├── class-woocommerce.php
 * │   ├── class-security.php
 * │   └── helper-functions.php
 * ├── templates/
 * │   ├── page-home.php
 * │   └── parts/
 * └── woocommerce/
 *     ├── archive-product.php
 *     ├── single-product.php
 *     ├── cart/
 *     ├── checkout/
 *     └── myaccount/
 */

// =====================================================================
// 1. STYLE.CSS - Theme Header & Main Styles
// =====================================================================
/*
Theme Name: AquaLuxe
Theme URI: https://github.com/kasunvimarshana/aqualuxe
Description: Premium WooCommerce theme for aquatic and luxury businesses. Fast, secure, and fully customizable with modern design and complete WooCommerce integration.
Author: Kasun Vimarshana
Author URI: https://github.com/kasunvimarshana
Version: 1.0.0
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: aqualuxe
Domain Path: /languages
Tags: e-commerce, woocommerce, responsive, clean, modern, multipurpose, business, luxury, aquatic, fish, customizable, fast, seo-friendly
*/

// =====================================================================
// 2. FUNCTIONS.PHP - Main Theme Functions
// =====================================================================

<?php
/**
 * AquaLuxe Theme Functions
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_THEME_DIR', get_template_directory());
define('AQUALUXE_THEME_URL', get_template_directory_uri());
define('AQUALUXE_THEME_PATH', get_template_directory() . '/');

/**
 * AquaLuxe Theme Setup Class
 */
class AquaLuxe_Theme_Setup {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('after_setup_theme', array($this, 'setup'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('init', array($this, 'init'));
        
        // Load required files
        $this->load_dependencies();
    }
    
    /**
     * Theme setup
     */
    public function setup() {
        // Theme support
        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script'
        ));
        add_theme_support('customize-selective-refresh-widgets');
        add_theme_support('responsive-embeds');
        add_theme_support('align-wide');
        add_theme_support('editor-styles');
        
        // WooCommerce support
        add_theme_support('woocommerce', array(
            'thumbnail_image_width' => 300,
            'single_image_width' => 600,
            'product_grid' => array(
                'default_rows' => 3,
                'min_rows' => 2,
                'default_columns' => 4,
                'min_columns' => 2,
                'max_columns' => 5,
            ),
        ));
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        
        // Navigation menus
        register_nav_menus(array(
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'mobile' => esc_html__('Mobile Menu', 'aqualuxe'),
        ));
        
        // Content width
        $GLOBALS['content_width'] = 1200;
        
        // Load text domain
        load_theme_textdomain('aqualuxe', AQUALUXE_THEME_PATH . 'languages');
    }
    
    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Styles
        wp_enqueue_style('aqualuxe-style', get_stylesheet_uri(), array(), AQUALUXE_VERSION);
        wp_enqueue_style('aqualuxe-main', AQUALUXE_THEME_URL . '/assets/css/main.css', array(), AQUALUXE_VERSION);
        
        if (class_exists('WooCommerce')) {
            wp_enqueue_style('aqualuxe-woocommerce', AQUALUXE_THEME_URL . '/assets/css/woocommerce.css', array('woocommerce-general'), AQUALUXE_VERSION);
        }
        
        // Scripts
        wp_enqueue_script('aqualuxe-main', AQUALUXE_THEME_URL . '/assets/js/main.js', array('jquery'), AQUALUXE_VERSION, true);
        
        if (class_exists('WooCommerce')) {
            wp_enqueue_script('aqualuxe-woocommerce', AQUALUXE_THEME_URL . '/assets/js/woocommerce.js', array('jquery', 'wc-add-to-cart'), AQUALUXE_VERSION, true);
        }
        
        // Localize scripts
        wp_localize_script('aqualuxe-main', 'aqualuxe_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_nonce'),
        ));
        
        // Comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
    
    /**
     * Initialize theme
     */
    public function init() {
        // Register widget areas
        $this->register_widget_areas();
        
        // Add image sizes
        add_image_size('aqualuxe-featured', 800, 450, true);
        add_image_size('aqualuxe-thumbnail', 300, 200, true);
        add_image_size('aqualuxe-product-thumb', 300, 300, true);
    }
    
    /**
     * Register widget areas
     */
    private function register_widget_areas() {
        register_sidebar(array(
            'name' => esc_html__('Main Sidebar', 'aqualuxe'),
            'id' => 'sidebar-main',
            'description' => esc_html__('Main sidebar for pages and posts.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        
        register_sidebar(array(
            'name' => esc_html__('Shop Sidebar', 'aqualuxe'),
            'id' => 'sidebar-shop',
            'description' => esc_html__('Sidebar for WooCommerce shop pages.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        
        // Footer widgets
        for ($i = 1; $i <= 4; $i++) {
            register_sidebar(array(
                'name' => sprintf(esc_html__('Footer Widget %d', 'aqualuxe'), $i),
                'id' => 'footer-' . $i,
                'description' => sprintf(esc_html__('Footer widget area %d.', 'aqualuxe'), $i),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h4 class="widget-title">',
                'after_title' => '</h4>',
            ));
        }
    }
    
    /**
     * Load required files
     */
    private function load_dependencies() {
        require_once AQUALUXE_THEME_PATH . 'inc/class-customizer.php';
        require_once AQUALUXE_THEME_PATH . 'inc/class-woocommerce.php';
        require_once AQUALUXE_THEME_PATH . 'inc/class-security.php';
        require_once AQUALUXE_THEME_PATH . 'inc/helper-functions.php';
    }
}

// Initialize theme
new AquaLuxe_Theme_Setup();

// =====================================================================
// 3. INC/CLASS-CUSTOMIZER.PHP - Theme Customizer
// =====================================================================

<?php
/**
 * Theme Customizer Class
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Customizer {
    
    public function __construct() {
        add_action('customize_register', array($this, 'register_customizer'));
        add_action('wp_head', array($this, 'output_custom_css'));
    }
    
    /**
     * Register customizer settings
     */
    public function register_customizer($wp_customize) {
        // Add AquaLuxe Panel
        $wp_customize->add_panel('aqualuxe_panel', array(
            'title' => esc_html__('AquaLuxe Options', 'aqualuxe'),
            'priority' => 30,
        ));
        
        // Colors Section
        $wp_customize->add_section('aqualuxe_colors', array(
            'title' => esc_html__('Colors', 'aqualuxe'),
            'panel' => 'aqualuxe_panel',
            'priority' => 10,
        ));
        
        // Primary Color
        $wp_customize->add_setting('aqualuxe_primary_color', array(
            'default' => '#0073aa',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', array(
            'label' => esc_html__('Primary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'aqualuxe_primary_color',
        )));
        
        // Secondary Color
        $wp_customize->add_setting('aqualuxe_secondary_color', array(
            'default' => '#005177',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', array(
            'label' => esc_html__('Secondary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'aqualuxe_secondary_color',
        )));
        
        // Layout Section
        $wp_customize->add_section('aqualuxe_layout', array(
            'title' => esc_html__('Layout Options', 'aqualuxe'),
            'panel' => 'aqualuxe_panel',
            'priority' => 20,
        ));
        
        // Container Width
        $wp_customize->add_setting('aqualuxe_container_width', array(
            'default' => '1200',
            'sanitize_callback' => 'absint',
            'transport' => 'refresh',
        ));
        
        $wp_customize->add_control('aqualuxe_container_width', array(
            'label' => esc_html__('Container Width (px)', 'aqualuxe'),
            'section' => 'aqualuxe_layout',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 960,
                'max' => 1400,
                'step' => 10,
            ),
        ));
        
        // Typography Section
        $wp_customize->add_section('aqualuxe_typography', array(
            'title' => esc_html__('Typography', 'aqualuxe'),
            'panel' => 'aqualuxe_panel',
            'priority' => 30,
        ));
        
        // Heading Font
        $wp_customize->add_setting('aqualuxe_heading_font', array(
            'default' => 'Roboto',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ));
        
        $wp_customize->add_control('aqualuxe_heading_font', array(
            'label' => esc_html__('Heading Font', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'select',
            'choices' => array(
                'Roboto' => 'Roboto',
                'Open Sans' => 'Open Sans',
                'Lato' => 'Lato',
                'Montserrat' => 'Montserrat',
                'Poppins' => 'Poppins',
            ),
        ));
    }
    
    /**
     * Output custom CSS
     */
    public function output_custom_css() {
        $primary_color = get_theme_mod('aqualuxe_primary_color', '#0073aa');
        $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#005177');
        $container_width = get_theme_mod('aqualuxe_container_width', '1200');
        $heading_font = get_theme_mod('aqualuxe_heading_font', 'Roboto');
        
        ?>

        <style type="text/css">
            :root {
                --aqualuxe-primary: <?php echo esc_attr($primary_color); ?>;
                --aqualuxe-secondary: <?php echo esc_attr($secondary_color); ?>;
                --aqualuxe-container-width: <?php echo esc_attr($container_width); ?>px;
            }

            h1, h2, h3, h4, h5, h6 {
                font-family: '<?php echo esc_attr($heading_font); ?>', sans-serif;
            }

            .container {
                max-width: var(--aqualuxe-container-width);
            }

            .btn-primary, .button.alt {
                background-color: var(--aqualuxe-primary);
                border-color: var(--aqualuxe-primary);
            }

            .btn-primary:hover, .button.alt:hover {
                background-color: var(--aqualuxe-secondary);
                border-color: var(--aqualuxe-secondary);
            }
        </style>
        <?php
    }

}

new AquaLuxe_Customizer();

// =====================================================================
// 4. INC/CLASS-WOOCOMMERCE.PHP - WooCommerce Integration
// =====================================================================

<?php
/**
 * WooCommerce Integration Class
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_WooCommerce {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_woocommerce_styles'));
        
        // WooCommerce hooks
        add_action('woocommerce_before_shop_loop', array($this, 'add_shop_filters'));
        add_action('woocommerce_before_single_product_summary', array($this, 'add_product_badges'), 5);
        add_filter('woocommerce_product_tabs', array($this, 'customize_product_tabs'));
        add_action('wp_ajax_aqualuxe_quick_view', array($this, 'quick_view_ajax'));
        add_action('wp_ajax_nopriv_aqualuxe_quick_view', array($this, 'quick_view_ajax'));
        
        // Remove default WooCommerce styles
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
    }
    
    /**
     * Initialize WooCommerce features
     */
    public function init() {
        if (!class_exists('WooCommerce')) {
            return;
        }
        
        // Remove default WooCommerce actions
        remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
        
        // Add custom actions
        add_action('woocommerce_before_shop_loop_item_title', array($this, 'add_product_overlay'), 15);
        add_action('woocommerce_shop_loop_item_title', array($this, 'wrap_product_title'), 5);
    }
    
    /**
     * Enqueue WooCommerce styles
     */
    public function enqueue_woocommerce_styles() {
        if (class_exists('WooCommerce')) {
            wp_enqueue_style('woocommerce-layout', WC()->plugin_url() . '/assets/css/woocommerce-layout.css', array(), WC_VERSION);
            wp_enqueue_style('woocommerce-smallscreen', WC()->plugin_url() . '/assets/css/woocommerce-smallscreen.css', array('woocommerce-layout'), WC_VERSION);
        }
    }
    
    /**
     * Add shop filters
     */
    public function add_shop_filters() {
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }
        
        echo '<div class="aqualuxe-shop-filters">';
        echo '<div class="filter-toggle"><button class="btn-filter">' . esc_html__('Filters', 'aqualuxe') . '</button></div>';
        echo '<div class="filters-content">';
        
        // Price filter
        if (is_active_sidebar('sidebar-shop')) {
            dynamic_sidebar('sidebar-shop');
        }
        
        echo '</div>';
        echo '</div>';
    }
    
    /**
     * Add product badges
     */
    public function add_product_badges() {
        global $product;
        
        echo '<div class="product-badges">';
        
        if ($product->is_on_sale()) {
            echo '<span class="badge badge-sale">' . esc_html__('Sale', 'aqualuxe') . '</span>';
        }
        
        if ($product->is_featured()) {
            echo '<span class="badge badge-featured">' . esc_html__('Featured', 'aqualuxe') . '</span>';
        }
        
        if (!$product->is_in_stock()) {
            echo '<span class="badge badge-out-of-stock">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
        }
        
        echo '</div>';
    }
    
    /**
     * Add product overlay for quick actions
     */
    public function add_product_overlay() {
        global $product;
        
        echo '<div class="product-overlay">';
        echo '<div class="overlay-actions">';
        echo '<button class="btn-quick-view" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Quick View', 'aqualuxe') . '</button>';
        echo '</div>';
        echo '</div>';
    }
    
    /**
     * Wrap product title
     */
    public function wrap_product_title() {
        echo '<div class="product-title-wrapper">';
    }
    
    /**
     * Customize product tabs
     */
    public function customize_product_tabs($tabs) {
        // Reorder tabs
        if (isset($tabs['description'])) {
            $tabs['description']['priority'] = 10;
        }
        
        if (isset($tabs['additional_information'])) {
            $tabs['additional_information']['priority'] = 20;
        }
        
        if (isset($tabs['reviews'])) {
            $tabs['reviews']['priority'] = 30;
        }
        
        // Add custom tab
        $tabs['care_instructions'] = array(
            'title' => esc_html__('Care Instructions', 'aqualuxe'),
            'priority' => 25,
            'callback' => array($this, 'care_instructions_tab_content'),
        );
        
        return $tabs;
    }
    
    /**
     * Care instructions tab content
     */
    public function care_instructions_tab_content() {
        global $product;
        
        $care_instructions = get_post_meta($product->get_id(), '_care_instructions', true);
        
        if ($care_instructions) {
            echo '<div class="care-instructions">';
            echo wp_kses_post($care_instructions);
            echo '</div>';
        } else {
            echo '<p>' . esc_html__('No care instructions available for this product.', 'aqualuxe') . '</p>';
        }
    }
    
    /**
     * Quick view AJAX handler
     */
    public function quick_view_ajax() {
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_nonce')) {
            wp_die('Security check failed');
        }
        
        $product_id = intval($_POST['product_id']);
        $product = wc_get_product($product_id);
        
        if (!$product) {
            wp_die('Product not found');
        }
        
        // Output quick view content
        ob_start();
        ?>

        <div class="quick-view-content">
            <div class="product-images">
                <?php echo wp_kses_post($product->get_image('medium')); ?>
            </div>
            <div class="product-summary">
                <h2><?php echo esc_html($product->get_name()); ?></h2>
                <div class="price"><?php echo wp_kses_post($product->get_price_html()); ?></div>
                <div class="description"><?php echo wp_kses_post($product->get_short_description()); ?></div>
                <form class="cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data'>
                    <?php woocommerce_quantity_input(array('min_value' => 1, 'max_value' => $product->get_max_purchase_quantity())); ?>
                    <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="single_add_to_cart_button button alt">
                        <?php echo esc_html($product->single_add_to_cart_text()); ?>
                    </button>
                </form>
            </div>
        </div>
        <?php

        $content = ob_get_clean();
        wp_send_json_success($content);
    }

}

new AquaLuxe_WooCommerce();

// =====================================================================
// 5. INC/CLASS-SECURITY.PHP - Security & Performance
// =====================================================================

<?php
/**
 * Security and Performance Class
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Security {
    
    public function __construct() {
        add_action('init', array($this, 'init_security'));
        add_action('wp_head', array($this, 'add_security_headers'));
        add_filter('wp_headers', array($this, 'add_security_headers_filter'));
        
        // Performance optimizations
        add_action('wp_enqueue_scripts', array($this, 'optimize_scripts'), 100);
        add_filter('script_loader_tag', array($this, 'add_async_defer'), 10, 2);
    }
    
    /**
     * Initialize security measures
     */
    public function init_security() {
        // Remove WordPress version from head
        remove_action('wp_head', 'wp_generator');
        
        // Remove WordPress version from RSS feeds
        add_filter('the_generator', '__return_empty_string');
        
        // Hide login errors
        add_filter('login_errors', array($this, 'hide_login_errors'));
        
        // Disable XML-RPC
        add_filter('xmlrpc_enabled', '__return_false');
        
        // Remove RSD link
        remove_action('wp_head', 'rsd_link');
        
        // Remove Windows Live Writer link
        remove_action('wp_head', 'wlwmanifest_link');
        
        // Remove shortlink
        remove_action('wp_head', 'wp_shortlink_wp_head');
    }
    
    /**
     * Add security headers
     */
    public function add_security_headers() {
        if (!is_admin()) {
            echo '<meta http-equiv="X-Content-Type-Options" content="nosniff">' . "\n";
            echo '<meta http-equiv="X-Frame-Options" content="SAMEORIGIN">' . "\n";
            echo '<meta http-equiv="X-XSS-Protection" content="1; mode=block">' . "\n";
        }
    }
    
    /**
     * Add security headers via filter
     */
    public function add_security_headers_filter($headers) {
        if (!is_admin()) {
            $headers['X-Content-Type-Options'] = 'nosniff';
            $headers['X-Frame-Options'] = 'SAMEORIGIN';
            $headers['X-XSS-Protection'] = '1; mode=block';
            $headers['Referrer-Policy'] = 'strict-origin-when-cross-origin';
        }
        
        return $headers;
    }
    
    /**
     * Hide login errors
     */
    public function hide_login_errors() {
        return esc_html__('Login failed. Please check your credentials.', 'aqualuxe');
    }
    
    /**
     * Optimize scripts loading
     */
    public function optimize_scripts() {
        // Defer jQuery on non-admin pages
        if (!is_admin()) {
            wp_scripts()->add_data('jquery', 'group', 1);
        }
    }
    
    /**
     * Add async/defer attributes to scripts
     */
    public function add_async_defer($tag, $handle) {
        $defer_scripts = array('aqualuxe-main', 'aqualuxe-woocommerce');
        $async_scripts = array('google-analytics');
        
        if (in_array($handle, $defer_scripts)) {
            return str_replace(' src=', ' defer src=', $tag);
        }
        
        if (in_array($handle, $async_scripts)) {
            return str_replace(' src=', ' async src=', $tag);
        }
        
        return $tag;
    }
}

new AquaLuxe_Security();

// =====================================================================
// 6. INC/HELPER-FUNCTIONS.PHP - Helper Functions
// =====================================================================

<?php
/**
 * Helper Functions
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get theme option with default fallback
 */
function aqualuxe_get_option($option_name, $default = false) {
    return get_theme_mod($option_name, $default);
}

/**
 * Sanitize HTML output
 */
function aqualuxe_sanitize_html($html) {
    return wp_kses_post($html);
}

/**
 * Get post excerpt with custom length
 */
function aqualuxe_get_excerpt($post_id = null, $length = 55) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }
    
    $excerpt = get_post_field('post_excerpt', $post_id);
    
    if (empty($excerpt)) {
        $content = get_post_field('post_content', $post_id);
        $excerpt = wp_trim_words(strip_tags($content), $length);
    }
    
    return wp_kses_post($excerpt);
}

/**
 * Check if WooCommerce is active
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Get product categories for filter
 */
function aqualuxe_get_product_categories() {
    if (!aqualuxe_is_woocommerce_active()) {
        return array();
    }
    
    $categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
    ));
    
    return is_wp_error($categories) ? array() : $categories;
}

/**
 * Display breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    if (is_front_page()) {
        return;
    }
    
    $breadcrumbs = array();
    $breadcrumbs[] = '<a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'aqualuxe') . '</a>';
    
    if (is_category() || is_single()) {
        $category = get_the_category();
        if ($category) {
            foreach ($category as $cat) {
                $breadcrumbs[] = '<a href="' . esc_url(get_category_link($cat->term_id)) . '">' . esc_html($cat->name) . '</a>';
            }
        }
        
        if (is_single()) {
            $breadcrumbs[] = '<span>' . esc_html(get_the_title()) . '</span>';
        }
    } elseif (is_page()) {
        $breadcrumbs[] = '<span>' . esc_html(get_the_title()) . '</span>';
    } elseif (is_search()) {
        $breadcrumbs[] = '<span>' . esc_html__('Search Results', 'aqualuxe') . '</span>';
    } elseif (is_404()) {
        $breadcrumbs[] = '<span>' . esc_html__('Page Not Found', 'aqualuxe') . '</span>';
    }
    
    if (aqualuxe_is_woocommerce_active() && is_woocommerce()) {
        if (is_shop()) {
            $breadcrumbs[] = '<span>' . esc_html__('Shop', 'aqualuxe') . '</span>';
        } elseif (is_product_category() || is_product_tag()) {
            $breadcrumbs[] = '<span>' . esc_html(single_term_title('', false)) . '</span>';
        } elseif (is_product()) {
            $breadcrumbs[] = '<span>' . esc_html(get_the_title()) . '</span>';
        }
    }
    
    if (!empty($breadcrumbs)) {
        echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumb Navigation', 'aqualuxe') . '">';
        echo '<ol class="breadcrumb-list">';
        foreach ($breadcrumbs as $breadcrumb) {
            echo '<li class="breadcrumb-item">' . $breadcrumb . '</li>';
        }
        echo '</ol>';
        echo '</nav>';
    }
}

/**
 * Display social sharing buttons
 */
function aqualuxe_social_share() {
    if (!is_single()) {
        return;
    }
    
    $url = get_permalink();
    $title = get_the_title();
    $image = get_the_post_thumbnail_url(get_the_ID(), 'large');
    
    ?>

    <div class="social-share">
        <h4><?php esc_html_e('Share this:', 'aqualuxe'); ?></h4>
        <div class="share-buttons">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url($url); ?>" target="_blank" rel="noopener" class="share-facebook">
                <span class="sr-only"><?php esc_html_e('Share on Facebook', 'aqualuxe'); ?></span>
                Facebook
            </a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo esc_url($url); ?>&text=<?php echo esc_attr($title); ?>" target="_blank" rel="noopener" class="share-twitter">
                <span class="sr-only"><?php esc_html_e('Share on Twitter', 'aqualuxe'); ?></span>
                Twitter
            </a>
            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo esc_url($url); ?>" target="_blank" rel="noopener" class="share-linkedin">
                <span class="sr-only"><?php esc_html_e('Share on LinkedIn', 'aqualuxe'); ?></span>
                LinkedIn
            </a>
            <a href="https://pinterest.com/pin/create/button/?url=<?php echo esc_url($url); ?>&media=<?php echo esc_url($image); ?>&description=<?php echo esc_attr($title); ?>" target="_blank" rel="noopener" class="share-pinterest">
                <span class="sr-only"><?php esc_html_e('Share on Pinterest', 'aqualuxe'); ?></span>
                Pinterest
            </a>
        </div>
    </div>
    <?php

}

// =====================================================================
// 7. INDEX.PHP - Main Template File
// =====================================================================

<?php
/**
 * The main template file
 * 
 * @package AquaLuxe
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?php if (have_posts()) : ?>
                    <div class="posts-grid">
                        <?php while (have_posts()) : the_post(); ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class('post-item'); ?>>
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="post-thumbnail">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('aqualuxe-featured'); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="post-content">
                                    <header class="entry-header">
                                        <h2 class="entry-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        <div class="entry-meta">
                                            <span class="posted-on">
                                                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                                    <?php echo esc_html(get_the_date()); ?>
                                                </time>
                                            </span>
                                            <span class="byline">
                                                <?php esc_html_e('by', 'aqualuxe'); ?> 
                                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                                    <?php the_author(); ?>
                                                </a>
                                            </span>
                                        </div>
                                    </header>
                                    
                                    <div class="entry-summary">
                                        <?php echo wp_kses_post(aqualuxe_get_excerpt(get_the_ID(), 30)); ?>
                                    </div>
                                    
                                    <footer class="entry-footer">
                                        <a href="<?php the_permalink(); ?>" class="read-more">
                                            <?php esc_html_e('Read More', 'aqualuxe'); ?>
                                        </a>
                                    </footer>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                    
                    <?php
                    the_posts_pagination(array(
                        'mid_size' => 2,
                        'prev_text' => esc_html__('Previous', 'aqualuxe'),
                        'next_text' => esc_html__('Next', 'aqualuxe'),
                    ));
                    ?>
                    
                <?php else : ?>
                    <div class="no-posts">
                        <h2><?php esc_html_e('Nothing Found', 'aqualuxe'); ?></h2>
                        <p><?php esc_html_e('It seems we can\'t find what you\'re looking for.', 'aqualuxe'); ?></p>
                        <?php get_search_form(); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="col-md-4">
                <aside id="secondary" class="widget-area" role="complementary">
                    <?php if (is_active_sidebar('sidebar-main')) : ?>
                        <?php dynamic_sidebar('sidebar-main'); ?>
                    <?php endif; ?>
                </aside>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>

// =====================================================================
// 11. PAGE.PHP - Page Template
// =====================================================================

<?php
/**
 * The template for displaying pages
 * 
 * @package AquaLuxe
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php aqualuxe_breadcrumbs(); ?>
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="entry-thumbnail">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>
                
                <div class="entry-content">
                    <?php the_content(); ?>
                    
                    <?php
                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                        'after' => '</div>',
                    ));
                    ?>
                </div>
            </article>
            
            <?php
            // Comments
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            ?>
            
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>

// =====================================================================
// 12. WOOCOMMERCE/ARCHIVE-PRODUCT.PHP - Shop Page Template
// =====================================================================

<?php
/**
 * The Template for displaying product archives
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header('shop'); ?>

<div class="shop-container">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4">
                <aside class="shop-sidebar">
                    <?php if (is_active_sidebar('sidebar-shop')) : ?>
                        <?php dynamic_sidebar('sidebar-shop'); ?>
                    <?php else : ?>
                        <?php
                        // Default shop filters
                        the_widget('WC_Widget_Product_Categories');
                        the_widget('WC_Widget_Price_Filter');
                        the_widget('WC_Widget_Product_Tag_Cloud');
                        ?>
                    <?php endif; ?>
                </aside>
            </div>
            
            <div class="col-lg-9 col-md-8">
                <div class="shop-content">
                    <?php if (woocommerce_product_loop()) : ?>
                        
                        <?php do_action('woocommerce_before_shop_loop'); ?>
                        
                        <div class="shop-toolbar">
                            <div class="toolbar-left">
                                <?php woocommerce_catalog_ordering(); ?>
                            </div>
                            <div class="toolbar-right">
                                <div class="view-toggle">
                                    <button class="view-grid active" data-view="grid" aria-label="<?php esc_attr_e('Grid View', 'aqualuxe'); ?>">
                                        <i class="icon-grid" aria-hidden="true"></i>
                                    </button>
                                    <button class="view-list" data-view="list" aria-label="<?php esc_attr_e('List View', 'aqualuxe'); ?>">
                                        <i class="icon-list" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <?php woocommerce_result_count(); ?>
                            </div>
                        </div>
                        
                        <div class="products-wrapper">
                            <?php woocommerce_product_loop_start(); ?>
                            
                            <?php if (wc_get_loop_prop('is_shortcode')) : ?>
                                <?php
                                $columns = wc_get_loop_prop('columns');
                                while (have_posts()) :
                                    the_post();
                                    do_action('woocommerce_shop_loop');
                                    wc_get_template_part('content', 'product');
                                endwhile;
                                ?>
                            <?php else : ?>
                                <?php
                                while (have_posts()) :
                                    the_post();
                                    do_action('woocommerce_shop_loop');
                                    wc_get_template_part('content', 'product');
                                endwhile;
                                ?>
                            <?php endif; ?>
                            
                            <?php woocommerce_product_loop_end(); ?>
                        </div>
                        
                        <?php do_action('woocommerce_after_shop_loop'); ?>
                        
                    <?php else : ?>
                        
                        <?php do_action('woocommerce_no_products_found'); ?>
                        
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer('shop'); ?>

// =====================================================================
// 13. WOOCOMMERCE/SINGLE-PRODUCT.PHP - Single Product Template
// =====================================================================

<?php
/**
 * The Template for displaying single products
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header('shop'); ?>

<div class="product-page">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            
            <?php wc_get_template_part('content', 'single-product'); ?>
            
        <?php endwhile; ?>
    </div>
</div>

<?php get_footer('shop'); ?>

// =====================================================================
// 14. WOOCOMMERCE/CONTENT-PRODUCT.PHP - Product Loop Content
// =====================================================================

<?php
/**
 * The template for displaying product content within loops
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

global $product;

if (empty($product) || !$product->is_visible()) {
    return;
}
?>

<li <?php wc_product_class('product-item', $product); ?>>
    <div class="product-inner">
        <div class="product-image-wrapper">
            <?php
            // Product badges
            echo '<div class="product-badges">';
            if ($product->is_on_sale()) {
                echo '<span class="badge sale-badge">' . esc_html__('Sale', 'aqualuxe') . '</span>';
            }
            if ($product->is_featured()) {
                echo '<span class="badge featured-badge">' . esc_html__('Featured', 'aqualuxe') . '</span>';
            }
            if (!$product->is_in_stock()) {
                echo '<span class="badge out-of-stock-badge">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
            }
            echo '</div>';
            
            // Product image
            echo '<div class="product-image">';
            echo '<a href="' . esc_url(get_the_permalink()) . '">';
            echo woocommerce_get_product_thumbnail();
            echo '</a>';
            echo '</div>';
            
            // Product overlay with actions
            echo '<div class="product-overlay">';
            echo '<div class="overlay-actions">';
            
            // Quick view button
            echo '<button class="btn-quick-view" data-product-id="' . esc_attr($product->get_id()) . '" aria-label="' . esc_attr__('Quick View', 'aqualuxe') . '">';
            echo '<i class="icon-eye" aria-hidden="true"></i>';
            echo '</button>';
            
            // Wishlist button (if plugin available)
            if (function_exists('YITH_WCWL')) {
                echo do_shortcode('[yith_wcwl_add_to_wishlist]');
            }
            
            // Compare button (if plugin available)
            if (class_exists('YITH_Woocompare')) {
                echo '<button class="btn-compare" data-product-id="' . esc_attr($product->get_id()) . '" aria-label="' . esc_attr__('Compare', 'aqualuxe') . '">';
                echo '<i class="icon-balance-scale" aria-hidden="true"></i>';
                echo '</button>';
            }
            
            echo '</div>';
            echo '</div>';
            ?>
        </div>
        
        <div class="product-content">
            <div class="product-categories">
                <?php echo wc_get_product_category_list($product->get_id(), ', ', '<span class="posted_in">', '</span>'); ?>
            </div>
            
            <h3 class="product-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>
            
            <div class="product-rating">
                <?php woocommerce_template_loop_rating(); ?>
            </div>
            
            <div class="product-price">
                <?php woocommerce_template_loop_price(); ?>
            </div>
            
            <div class="product-excerpt">
                <?php echo wp_kses_post(wp_trim_words(get_the_excerpt(), 15)); ?>
            </div>
            
            <div class="product-actions">
                <?php woocommerce_template_loop_add_to_cart(); ?>
            </div>
        </div>
    </div>
</li>

// =====================================================================
// 15. ASSETS/CSS/MAIN.CSS - Main Stylesheet
// =====================================================================

/_ AquaLuxe Theme Main Styles _/

:root {
--aqualuxe-primary: #0073aa;
--aqualuxe-secondary: #005177;
--aqualuxe-accent: #00a0d2;
--aqualuxe-text: #333333;
--aqualuxe-text-light: #666666;
--aqualuxe-border: #e0e0e0;
--aqualuxe-background: #ffffff;
--aqualuxe-light-bg: #f8f9fa;
--aqualuxe-container-width: 1200px;
--aqualuxe-border-radius: 4px;
--aqualuxe-transition: all 0.3s ease;
--aqualuxe-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
--aqualuxe-shadow-hover: 0 5px 20px rgba(0, 0, 0, 0.15);
}

/_ Reset and Base Styles _/
_,
_::before,
\*::after {
box-sizing: border-box;
}

html {
scroll-behavior: smooth;
}

body {
font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
font-size: 16px;
line-height: 1.6;
color: var(--aqualuxe-text);
background-color: var(--aqualuxe-background);
margin: 0;
padding: 0;
}

/_ Typography _/
h1, h2, h3, h4, h5, h6 {
font-family: 'Roboto', sans-serif;
font-weight: 600;
line-height: 1.3;
margin: 0 0 1rem 0;
color: var(--aqualuxe-text);
}

h1 { font-size: 2.5rem; }
h2 { font-size: 2rem; }
h3 { font-size: 1.75rem; }
h4 { font-size: 1.5rem; }
h5 { font-size: 1.25rem; }
h6 { font-size: 1rem; }

p {
margin: 0 0 1rem 0;
}

a {
color: var(--aqualuxe-primary);
text-decoration: none;
transition: var(--aqualuxe-transition);
}

a:hover,
a:focus {
color: var(--aqualuxe-secondary);
}

/_ Layout _/
.container {
max-width: var(--aqualuxe-container-width);
margin: 0 auto;
padding: 0 20px;
}

.row {
display: flex;
flex-wrap: wrap;
margin: 0 -15px;
}

.col-md-3 { flex: 0 0 25%; max-width: 25%; }
.col-md-4 { flex: 0 0 33.333333%; max-width: 33.333333%; }
.col-md-6 { flex: 0 0 50%; max-width: 50%; }
.col-md-8 { flex: 0 0 66.666667%; max-width: 66.666667%; }
.col-md-9 { flex: 0 0 75%; max-width: 75%; }
.col-md-12 { flex: 0 0 100%; max-width: 100%; }

[class*="col-"] {
padding: 0 15px;
}

@media (max-width: 768px) {
[class*="col-md-"] {
flex: 0 0 100%;
max-width: 100%;
}
}

/_ Buttons _/
.btn,
.button,
input[type="submit"],
input[type="button"] {
display: inline-block;
padding: 12px 24px;
font-size: 14px;
font-weight: 600;
text-align: center;
text-decoration: none;
border: 2px solid transparent;
border-radius: var(--aqualuxe-border-radius);
cursor: pointer;
transition: var(--aqualuxe-transition);
background-color: var(--aqualuxe-primary);
color: #ffffff;
}

.btn:hover,
.button:hover,
input[type="submit"]:hover,
input[type="button"]:hover {
background-color: var(--aqualuxe-secondary);
transform: translateY(-2px);
box-shadow: var(--aqualuxe-shadow-hover);
}

.btn-secondary {
background-color: transparent;
color: var(--aqualuxe-primary);
border-color: var(--aqualuxe-primary);
}

.btn-secondary:hover {
background-color: var(--aqualuxe-primary);
color: #ffffff;
}

/_ Forms _/
input[type="text"],
input[type="email"],
input[type="password"],
input[type="tel"],
input[type="url"],
input[type="search"],
textarea,
select {
width: 100%;
padding: 12px 16px;
border: 1px solid var(--aqualuxe-border);
border-radius: var(--aqualuxe-border-radius);
font-size: 14px;
transition: var(--aqualuxe-transition);
}

input:focus,
textarea:focus,
select:focus {
outline: none;
border-color: var(--aqualuxe-primary);
box-shadow: 0 0 0 3px rgba(0, 115, 170, 0.1);
}

/_ Header Styles _/
.site-header {
background-color: var(--aqualuxe-background);
box-shadow: var(--aqualuxe-shadow);
position: sticky;
top: 0;
z-index: 1000;
}

.header-top {
background-color: var(--aqualuxe-light-bg);
padding: 8px 0;
font-size: 14px;
}

.contact-info span {
margin-right: 20px;
}

.contact-info i {
margin-right: 5px;
color: var(--aqualuxe-primary);
}

.header-actions {
text-align: right;
}

.cart-link {
position: relative;
padding: 8px 12px;
background-color: var(--aqualuxe-primary);
color: #ffffff;
border-radius: var(--aqualuxe-border-radius);
transition: var(--aqualuxe-transition);
}

.cart-count {
position: absolute;
top: -8px;
right: -8px;
background-color: var(--aqualuxe-accent);
color: #ffffff;
border-radius: 50%;
width: 20px;
height: 20px;
font-size: 12px;
display: flex;
align-items: center;
justify-content: center;
}

.header-main {
padding: 20px 0;
}

.site-branding h1 {
margin: 0;
font-size: 2rem;
font-weight: 700;
}

.site-description {
margin: 0;
font-size: 14px;
color: var(--aqualuxe-text-light);
}

/_ Navigation _/
.main-navigation ul {
list-style: none;
margin: 0;
padding: 0;
display: flex;
}

.main-navigation li {
position: relative;
margin: 0 15px;
}

.main-navigation a {
display: block;
padding: 10px 0;
font-weight: 500;
color: var(--aqualuxe-text);
transition: var(--aqualuxe-transition);
}

.main-navigation a:hover {
color: var(--aqualuxe-primary);
}

.menu-toggle {
display: none;
background: none;
border: none;
padding: 10px;
cursor: pointer;
}

@media (max-width: 768px) {
.menu-toggle {
display: block;
}

    .main-navigation ul {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background-color: var(--aqualuxe-background);
        box-shadow: var(--aqualuxe-shadow);
        flex-direction: column;
        padding: 20px;
    }

    .main-navigation.toggled ul {
        display: flex;
    }

    .main-navigation li {
        margin: 0;
        border-bottom: 1px solid var(--aqualuxe-border);
    }

}

/_ Search Form _/
.search-form {
position: relative;
}

.search-field {
padding-right: 50px;
}

.search-submit {
position: absolute;
right: 0;
top: 0;
bottom: 0;
padding: 0 15px;
background-color: var(--aqualuxe-primary);
color: #ffffff;
border: none;
border-radius: 0 var(--aqualuxe-border-radius) var(--aqualuxe-border-radius) 0;
}

/_ Breadcrumbs _/
.breadcrumbs {
padding: 15px 0;
background-color: var(--aqualuxe-light-bg);
font-size: 14px;
}

.breadcrumb-list {
list-style: none;
margin: 0;
padding: 0;
display: flex;
align-items: center;
}

.breadcrumb-item:not(:last-child)::after {
content: '›';
margin: 0 10px;
color: var(--aqualuxe-text-light);
}

/_ Posts Grid _/
.posts-grid {
display: grid;
grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
gap: 30px;
margin-bottom: 40px;
}

.post-item {
background-color: var(--aqualuxe-background);
border-radius: var(--aqualuxe-border-radius);
overflow: hidden;
box-shadow: var(--aqualuxe-shadow);
transition: var(--aqualuxe-transition);
}

.post-item:hover {
transform: translateY(-5px);
box-shadow: var(--aqualuxe-shadow-hover);
}

.post-thumbnail img {
width: 100%;
height: 200px;
object-fit: cover;
}

.post-content {
padding: 20px;
}

.entry-title {
margin-bottom: 10px;
}

.entry-title a {
color: var(--aqualuxe-text);
}

.entry-meta {
font-size: 14px;
color: var(--aqualuxe-text-light);
margin-bottom: 15px;
}

.entry-meta span {
margin-right: 15px;
}

.read-more {
display: inline-block;
margin-top: 15px;
font-weight: 600;
color: var(--aqualuxe-primary);
}

/_ Sidebar _/
.widget-area {
padding-left: 30px;
}

.widget {
background-color: var(--aqualuxe-light-bg);
padding: 20px;
border-radius: var(--aqualuxe-border-radius);
margin-bottom: 30px;
}

.widget-title {
margin-bottom: 15px;
padding-bottom: 10px;
border-bottom: 2px solid var(--aqualuxe-primary);
}

.widget ul {
list-style: none;
padding: 0;
margin: 0;
}

.widget li {
padding: 8px 0;
border-bottom: 1px solid var(--aqualuxe-border);
}

.widget li:last-child {
border-bottom: none;
}

/_ Footer _/
.site-footer {
background-color: var(--aqualuxe-text);
color: #ffffff;
margin-top: 60px;
}

.footer-widgets {
padding: 60px 0;
}

.footer-widget-area h4 {
color: #ffffff;
margin-bottom: 20px;
}

.footer-widget-area ul {
list-style: none;
padding: 0;
margin: 0;
}

.footer-widget-area li {
padding: 5px 0;
}

.footer-widget-area a {
color: #cccccc;
transition: var(--aqualuxe-transition);
}

.footer-widget-area a:hover {
color: #ffffff;
}

.footer-bottom {
background-color: rgba(0, 0, 0, 0.2);
padding: 20px 0;
text-align: center;
}

.footer-menu ul {
display: flex;
justify-content: center;
list-style: none;
margin: 0;
padding: 0;
}

.footer-menu li {
margin: 0 15px;
}

.footer-menu a {
color: #cccccc;
}

/_ Social Share _/
.social-share {
margin-top: 30px;
padding: 20px;
background-color: var(--aqualuxe-light-bg);
border-radius: var(--aqualuxe-border-radius);
}

.share-buttons {
display: flex;
gap: 10px;
margin-top: 15px;
}

.share-buttons a {
padding: 8px 16px;
border-radius: var(--aqualuxe-border-radius);
font-size: 14px;
font-weight: 600;
text-decoration: none;
transition: var(--aqualuxe-transition);
}

.share-facebook { background-color: #1877f2; color: #ffffff; }
.share-twitter { background-color: #1da1f2; color: #ffffff; }
.share-linkedin { background-color: #0077b5; color: #ffffff; }
.share-pinterest { background-color: #bd081c; color: #ffffff; }

/_ Pagination _/
.pagination {
display: flex;
justify-content: center;
margin: 40px 0;
}

.page-numbers {
padding: 10px 15px;
margin: 0 5px;
border: 1px solid var(--aqualuxe-border);
border-radius: var(--aqualuxe-border-radius);
color: var(--aqualuxe-text);
transition: var(--aqualuxe-transition);
}

.page-numbers:hover,
.page-numbers.current {
background-color: var(--aqualuxe-primary);
color: #ffffff;
border-color: var(--aqualuxe-primary);
}

/_ Comments _/
.comments-area {
margin-top: 40px;
padding-top: 40px;
border-top: 1px solid var(--aqualuxe-border);
}

.comment-list {
list-style: none;
padding: 0;
margin: 0;
}

.comment {
margin-bottom: 30px;
padding: 20px;
background-color: var(--aqualuxe-light-bg);
border-radius: var(--aqualuxe-border-radius);
}

.comment-meta {
font-size: 14px;
color: var(--aqualuxe-text-light);
margin-bottom: 10px;
}

.comment-author {
font-weight: 600;
color: var(--aqualuxe-text);
}

/_ Responsive Design _/
@media (max-width: 768px) {
.container {
padding: 0 15px;
}

    .header-main {
        padding: 15px 0;
    }

    .posts-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .widget-area {
        padding-left: 0;
        margin-top: 40px;
    }

    .footer-widgets .row {
        flex-direction: column;
    }

    .share-buttons {
        flex-wrap: wrap;
    }

    h1 { font-size: 2rem; }
    h2 { font-size: 1.75rem; }
    h3 { font-size: 1.5rem; }

}

/_ Utility Classes _/
.sr-only {
position: absolute;
width: 1px;
height: 1px;
padding: 0;
margin: -1px;
overflow: hidden;
clip: rect(0, 0, 0, 0);
white-space: nowrap;
border: 0;
}

.text-center { text-align: center; }
.text-left { text-align: left; }
.text-right { text-align: right; }

.mt-1 { margin-top: 0.25rem; }
.mt-2 { margin-top: 0.5rem; }
.mt-3 { margin-top: 1rem; }
.mt-4 { margin-top: 1.5rem; }
.mt-5 { margin-top: 3rem; }

.mb-1 { margin-bottom: 0.25rem; }
.mb-2 { margin-bottom: 0.5rem; }
.mb-3 { margin-bottom: 1rem; }
.mb-4 { margin-bottom: 1.5rem; }
.mb-5 { margin-bottom: 3rem; }

// =====================================================================
// 16. ASSETS/CSS/WOOCOMMERCE.CSS - WooCommerce Styles
// =====================================================================

/_ WooCommerce Specific Styles _/

/_ Shop Page _/
.shop-container {
padding: 40px 0;
}

.shop-sidebar {
background-color: var(--aqualuxe-light-bg);
padding: 30px;
border-radius: var(--aqualuxe-border-radius);
height: fit-content;
position: sticky;
top: 100px;
}

.shop-toolbar {
display: flex;
justify-content: space-between;
align-items: center;
padding: 20px 0;
margin-bottom: 30px;
border-bottom: 1px solid var(--aqualuxe-border);
}

.view-toggle {
display: flex;
gap: 5px;
margin-right: 20px;
}

.view-toggle button {
padding: 8px 12px;
border: 1px solid var(--aqualuxe-border);
background-color: transparent;
cursor: pointer;
border-radius: var(--aqualuxe-border-radius);
transition: var(--aqualuxe-transition);
}

.view-toggle button.active,
.view-toggle button:hover {
background-color: var(--aqualuxe-primary);
color: #ffffff;
border-color: var(--aqualuxe-primary);
}

/_ Product Grid _/
.products {
display: grid;
grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
gap: 30px;
list-style: none;
margin: 0;
padding: 0;
}

.product-item {
background-color: var(--aqualuxe-background);
border-radius: var(--aqualuxe-border-radius);
overflow: hidden;
box-shadow: var(--aqualuxe-shadow);
transition: var(--aqualuxe-transition);
position: relative;
}

.product-item:hover {
transform: translateY(-5px);
box-shadow: var(--aqualuxe-shadow-hover);
}

.product-image-wrapper {
position: relative;
overflow: hidden;
}

.product-image img {
width: 100%;
height: 250px;
object-fit: cover;
transition: var(--aqualuxe-transition);
}

.product-item:hover .product-image img {
transform: scale(1.05);
}

.product-badges {
position: absolute;
top: 10px;
left: 10px;
z-index: 2;
}

.badge {
display: inline-block;
padding: 4px 8px;
font-size: 12px;
font-weight: 600;
border-radius: 2px;
color: #ffffff;
margin-right: 5px;
margin-bottom: 5px;
}

.sale-badge { background-color: #e74c3c; }
.featured-badge { background-color: #f39c12; }
.out-of-stock-badge { background-color: #95a5a6; }

.product-overlay {
position: absolute;
top: 0;
left: 0;
right: 0;
bottom: 0;
background-color: rgba(0, 0, 0, 0.7);
display: flex;
align-items: center;
justify-content: center;
opacity: 0;
transition: var(--aqualuxe-transition);
}

.product-item:hover .product-overlay {
opacity: 1;
}

.overlay-actions {
display: flex;
gap: 10px;
}

.btn-quick-view,
.btn-compare {
width: 40px;
height: 40px;
border-radius: 50%;
background-color: var(--aqualuxe-primary);
color: #ffffff;
border: none;
cursor: pointer;
transition: var(--aqualuxe-transition);
display: flex;
align-items: center;
justify-content: center;
}

.btn-quick-view:hover,
.btn-compare:hover {
background-color: var(--aqualuxe-secondary);
transform: scale(1.1);
}

.product-content {
padding: 20px;
}

.product-categories {
font-size: 12px;
color: var(--aqualuxe-text-light);
margin-bottom: 8px;
}

.product-title {
margin-bottom: 10px;
font-size: 16px;
}

.product-title a {
color: var(--aqualuxe-text);
transition: var(--aqualuxe-transition);
}

.product-title a:hover {
color: var(--aqualuxe-primary);
}

.product-rating {
margin-bottom: 10px;
}

.star-rating {
color: #ffc107;
}

.product-price {
font-size: 18px;
font-weight: 600;
color: var(--aqualuxe-primary);
margin-bottom: 10px;
}

.product-price del {
color: var(--aqualuxe-text-light);
font-weight: normal;
}

.product-excerpt {
font-size: 14px;
color: var(--aqualuxe-text-light);
margin-bottom: 15px;
}

.product-actions .button {
width: 100%;
padding: 10px;
font-size: 14px;
}

/_ Single Product _/
.single-product .product {
display: grid;
grid-template-columns: 1fr 1fr;
gap: 40px;
margin-bottom: 40px;
}

.product-images {
position: relative;
}

.woocommerce-product-gallery\_\_image img {
width: 100%;
border-radius: var(--aqualuxe-border-radius);
}

.product-summary {
padding: 20px;
}

.product_title {
font-size: 2rem;
margin-bottom: 15px;
}

.price {
font-size: 1.5rem;
font-weight: 600;
color: var(--aqualuxe-primary);
margin-bottom: 20px;
}

.woocommerce-product-details\_\_short-description {
margin-bottom: 25px;
color: var(--aqualuxe-text-light);
}

.variations select {
width: 100%;
margin-bottom: 15px;
}

.quantity input {
width: 60px;
text-align: center;
margin-right: 15px;
}

/_ Product Tabs _/
.woocommerce-tabs {
margin-top: 40px;
}

.wc-tabs {
display: flex;
border-bottom: 1px solid var(--aqualuxe-border);
list-style: none;
margin: 0 0 30px 0;
padding: 0;
}

.wc-tabs li {
margin-right: 30px;
}

.wc-tabs a {
display: block;
padding: 15px 0;
color: var(--aqualuxe-text-light);
font-weight: 600;
border-bottom: 2px solid transparent;
transition: var(--aqualuxe-transition);
}

.wc-tabs li.active a,
.wc-tabs a:hover {
color: var(--aqualuxe-primary);
border-bottom-color: var(--aqualuxe-primary);
}

.wc-tab {
display: none;
}

.wc-tab.active {
display: block;
}

/_ Cart & Checkout _/
.cart-table {
width: 100%;
border-collapse: collapse;
margin-bottom: 30px;
}

.cart-table th,
.cart-table td {
padding: 15px;
border-bottom: 1px solid var(--aqualuxe-border);
text-align: left;
}

.cart-table th {
font-weight: 600;
background-color: var(--aqualuxe-light-bg);
}

.product-thumbnail img {
width: 80px;
height: 80px;
object-fit: cover;
border-radius: var(--aqualuxe-border-radius);
}

.cart-totals {
background-color: var(--aqualuxe-light-bg);
padding: 30px;
border-radius: var(--aqualuxe-border-radius);
}

.checkout-form {
display: grid;
grid-template-columns: 1fr 1fr;
gap: 40px;
}

.form-row {
margin-bottom: 20px;
}

.form-row label {
display: block;
margin-bottom: 5px;
font-weight: 600;
}

.form-row input,
.form-row select,
.form-row textarea {
width: 100%;
}

/_ Account Pages _/
.woocommerce-account .woocommerce-MyAccount-navigation {
background-color: var(--aqualuxe-light-bg);
padding: 30px;
border-radius: var(--aqualuxe-border-radius);
margin-bottom: 30px;
}

.woocommerce-MyAccount-navigation ul {
list-style: none;
margin: 0;
padding: 0;
}

.woocommerce-MyAccount-navigation li {
margin-bottom: 10px;
}

.woocommerce-MyAccount-navigation a {
display: block;
padding: 10px 15px;
color: var(--aqualuxe-text);
border-radius: var(--aqualuxe-border-radius);
transition: var(--aqualuxe-transition);
}

.woocommerce-MyAccount-navigation .is-active a,
.woocommerce-MyAccount-navigation a:hover {
background-color: var(--aqualuxe-primary);
color: #ffffff;
}

/_ Quick View Modal _/
.modal {
display: none;
position: fixed;
z-index: 9999;
left: 0;
top: 0;
width: 100%;
height: 100%;
background-color: rgba(0, 0, 0, 0.8);
}

.modal.show {
display: flex;
align-items: center;
justify-content: center;
}

.modal-dialog {
background-color: var(--aqualuxe-background);
border-radius: var(--aqualuxe-border-radius);
max-width: 800px;
width: 90%;
max-height: 90vh;
overflow-y: auto;
}

.modal-header {
padding: 20px;
border-bottom: 1px solid var(--aqualuxe-border);
display: flex;
justify-content: space-between;
align-items: center;
}

.modal-title {
margin: 0;
font-size: 1.25rem;
}

.close {
background: none;
border: none;
font-size: 24px;
cursor: pointer;
color: var(--aqualuxe-text-light);
}

.modal-body {
padding: 20px;
}

.quick-view-content {
display: grid;
grid-template-columns: 1fr 1fr;
gap: 30px;
}

/_ Responsive WooCommerce _/
@media (max-width: 768px) {
.products {
grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
gap: 20px;
}

    .shop-toolbar {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
    }

    .toolbar-right {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .single-product .product {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .checkout-form {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .quick-view-content {
        grid-template-columns: 1fr;
        gap: 20px;
    }

}

// =====================================================================
// 17. ASSETS/JS/MAIN.JS - Main JavaScript
// =====================================================================

/\*\*

- AquaLuxe Theme JavaScript
  \*/

(function($) {
'use strict';

    // Document ready
    $(document).ready(function() {

        // Initialize all components
        AquaLuxe.init();

    });

    // Main theme object
    window.AquaLuxe = {

        /**
         * Initialize theme
         */
        init: function() {
            this.mobileMenu();
            this.searchToggle();
            this.smoothScroll();
            this.backToTop();
            this.lazyLoadImages();
            this.handleForms();
            this.accessibility();
        },

        /**
         * Mobile menu functionality
         */
        mobileMenu: function() {
            const menuToggle = $('.menu-toggle');
            const navigation = $('.main-navigation');

            menuToggle.on('click', function(e) {
                e.preventDefault();

                const isExpanded = $(this).attr('aria-expanded') === 'true';

                $(this).attr('aria-expanded', !isExpanded);
                navigation.toggleClass('toggled');

                // Close menu when clicking outside
                if (!isExpanded) {
                    $(document).on('click.mobile-menu', function(event) {
                        if (!$(event.target).closest('.main-navigation, .menu-toggle').length) {
                            menuToggle.attr('aria-expanded', 'false');
                            navigation.removeClass('toggled');
                            $(document).off('click.mobile-menu');
                        }
                    });
                }
            });

            // Close mobile menu on window resize
            $(window).on('resize', function() {
                if ($(window).width() > 768) {
                    menuToggle.attr('aria-expanded', 'false');
                    navigation.removeClass('toggled');
                }
            });
        },

        /**
         * Search toggle functionality
         */
        searchToggle: function() {
            const searchToggle = $('.search-toggle');
            const searchForm = $('.header-search');

            searchToggle.on('click', function(e) {
                e.preventDefault();
                searchForm.toggleClass('active');

                if (searchForm.hasClass('active')) {
                    searchForm.find('.search-field').focus();
                }
            });
        },

        /**
         * Smooth scrolling for anchor links
         */
        smoothScroll: function() {
            $('a[href*="#"]:not([href="#"])').on('click', function(e) {
                const target = $(this.getAttribute('href'));

                if (target.length) {
                    e.preventDefault();

                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 600, 'swing');
                }
            });
        },

        /**
         * Back to top button
         */
        backToTop: function() {
            // Create back to top button
            $('body').append('<button id="back-to-top" class="back-to-top" aria-label="Back to top"><i class="icon-arrow-up"></i></button>');

            const backToTopBtn = $('#back-to-top');

            // Show/hide button based on scroll position
            $(window).on('scroll', function() {
                if ($(this).scrollTop() > 300) {
                    backToTopBtn.addClass('show');
                } else {
                    backToTopBtn.removeClass('show');
                }
            });

            // Scroll to top on click
            backToTopBtn.on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, 600);
            });
        },

        /**
         * Lazy load images
         */
        lazyLoadImages: function() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            img.classList.add('lazy-loaded');
                            imageObserver.unobserve(img);
                        }
                    });
                });

                document.querySelectorAll('img[data-src]').forEach(function(img) {
                    imageObserver.observe(img);
                });
            }
        },

        /**
         * Enhanced form handling
         */
        handleForms: function() {
            // Add loading state to forms
            $('form').on('submit', function() {
                const submitBtn = $(this).find('input[type="submit"], button[type="submit"]');
                const originalText = submitBtn.val() || submitBtn.text();

                submitBtn.prop('disabled', true);
                if (submitBtn.is('input')) {
                    submitBtn.val('Please wait...');
                } else {
                    submitBtn.text('Please wait...');
                }

                // Reset after 10 seconds if still processing
                setTimeout(function() {
                    submitBtn.prop('disabled', false);
                    if (submitBtn.is('input')) {
                        submitBtn.val(originalText);
                    } else {
                        submitBtn.text(originalText);
                    }
                }, 10000);
            });

            // Real-time form validation
            $('input[required], textarea[required]').on('blur', function() {
                const $this = $(this);
                const value = $this.val().trim();

                if (value === '') {
                    $this.addClass('error');
                    $this.attr('aria-invalid', 'true');
                } else {
                    $this.removeClass('error');
                    $this.attr('aria-invalid', 'false');
                }
            });
        },

        /**
         * Accessibility enhancements
         */
        accessibility: function() {
            // Skip link functionality
            $('.skip-link').on('click', function(e) {
                const target = $($(this).attr('href'));
                if (target.length) {
                    target.attr('tabindex', '-1').focus();
                }
            });

            // Keyboard navigation for dropdowns
            $('.menu-item-has-children > a').on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    $(this).next('.sub-menu').toggleClass('show');
                }
            });

            // Escape key to close modals and menus
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    // Close mobile menu
                    $('.main-navigation').removeClass('toggled');
                    $('.menu-toggle').attr('aria-expanded', 'false');

                    // Close modals
                    $('.modal').removeClass('show');
                }
            });
        },

        /**
         * Utility function to debounce events
         */
        debounce: function(func, wait, immediate) {
            let timeout;
            return function() {
                const context = this;
                const args = arguments;
                const later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                const callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        }
    };

})(jQuery);

// =====================================================================
// 18. ASSETS/JS/WOOCOMMERCE.JS - WooCommerce JavaScript
// =====================================================================

/\*\*

- AquaLuxe WooCommerce JavaScript
  \*/

(function($) {
'use strict';

    $(document).ready(function() {
        AquaLuxeWC.init();
    });

    window.AquaLuxeWC = {

        /**
         * Initialize WooCommerce features
         */
        init: function() {
            this.quickView();
            this.productFilters();
            this.cartActions();
            this.productGallery();
            this.viewToggle();
            this.quantityButtons();
            this.wishlistActions();
        },

        /**
         * Quick view functionality
         */
        quickView: function() {
            $(document).on('click', '.btn-quick-view', function(e) {
                e.preventDefault();

                const productId = $(this).data('product-id');
                const modal = $('#quick-view-modal');
                const modalBody = modal.find('.modal-body');

                // Show loading
                modalBody.html('<div class="loading">Loading...</div>');
                modal.addClass('show');

                // AJAX request
                $.ajax({
                    url: aqualuxe_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_quick_view',
                        product_id: productId,
                        nonce: aqualuxe_ajax.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            modalBody.html(response.data);
                        } else {
                            modalBody.html('<p>Error loading product details.</p>');
                        }
                    },
                    error: function() {
                        modalBody.html('<p>Error loading product details.</p>');
                    }
                });
            });

            // Close modal
            $(document).on('click', '.modal .close, .modal', function(e) {
                if (e.target === this) {
                    $('.modal').removeClass('show');
                }
            });
        },

        /**
         * Product filters
         */
        productFilters: function() {
            // Filter toggle for mobile
            $('.btn-filter').on('click', function(e) {
                e.preventDefault();
                $('.filters-content').slideToggle();
            });

            // Price filter
            if (typeof wc_price_slider_params !== 'undefined') {
                this.initPriceSlider();
            }
        },

        /**
         * Initialize price slider
         */
        initPriceSlider: function() {
            $('.price_slider').slider({
                range: true,
                animate: true,
                min: parseFloat(wc_price_slider_params.min_price),
                max: parseFloat(wc_price_slider_params.max_price),
                values: [
                    parseFloat(wc_price_slider_params.current_min_price),
                    parseFloat(wc_price_slider_params.current_max_price)
                ],
                create: function() {
                    $('.price_slider_amount #min_price').val(Math.floor(parseFloat(wc_price_slider_params.current_min_price)));
                    $('.price_slider_amount #max_price').val(Math.ceil(parseFloat(wc_price_slider_params.current_max_price)));
                },
                slide: function(event, ui) {
                    $('.price_slider_amount #min_price').val(Math.floor(ui.values[0]));
                    $('.price_slider_amount #max_price').val(Math.ceil(ui.values[1]));
                }
            });
        },

        /**
         * Cart actions
         */
        cartActions: function() {
            // Update cart quantities
            $(document).on('change', '.cart .qty', function() {
                const $form = $(this).closest('form');
                $form.find('input[name="update_cart"]').prop('disabled', false);
                $form.find('input[name="update_cart"]').trigger('click');
            });

            // Remove from cart with confirmation
            $(document).on('click', '.remove', function(e) {
                if (!confirm('Are you sure you want to remove this item?')) {
                    e.preventDefault();
                }
            });

            // Mini cart toggle
            $('.cart-toggle').on('click', function(e) {
                e.preventDefault();
                $('.mini-cart').toggleClass('show');
            });
        },

        /**
         * Product gallery enhancements
         */
        productGallery: function() {
            // Zoom functionality
            if ($.fn.zoom && wc_single_product_params.zoom_enabled === '1') {
                $('.woocommerce-product-gallery__image').zoom({
                    touch: false
                });
            }

            // Gallery slider
            $('.woocommerce-product-gallery').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: true,
                fade: true,
                asNavFor: '.woocommerce-product-gallery-thumbs'
            });

            $('.woocommerce-product-gallery-thumbs').slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                asNavFor: '.woocommerce-product-gallery',
                dots: false,
                arrows: false,
                focusOnSelect: true,
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 3
                        }
                    }
                ]
            });
        },

        /**
         * Product view toggle (grid/list)
         */
        viewToggle: function() {
            $('.view-toggle button').on('click', function(e) {
                e.preventDefault();

                const view = $(this).data('view');
                $('.view-toggle button').removeClass('active');
                $(this).addClass('active');

                const $products = $('.products');
                $products.removeClass('grid-view list-view').addClass(view + '-view');

                // Store preference
                localStorage.setItem('aqualuxe_shop_view', view);
            });

            // Load saved preference
            const savedView = localStorage.getItem('aqualuxe_shop_view');
            if (savedView) {
                $('.view-toggle button[data-view="' + savedView + '"]').trigger('click');
            }
        },

        /**
         * Quantity increment/decrement buttons
         */
        quantityButtons: function() {
            // Add quantity buttons
            $('.quantity').each(function() {
                const $qty = $(this).find('.qty');
                if ($qty.length) {
                    $qty.wrap('<div class="qty-wrapper"></div>');
                    $qty.before('<button type="button" class="qty-btn qty-minus">-</button>');
                    $qty.after('<button type="button" class="qty-btn qty-plus">+</button>');
                }
            });

            // Handle quantity changes
            $(document).on('click', '.qty-btn', function(e) {
                e.preventDefault();

                const $this = $(this);
                const $qty = $this.siblings('.qty');
                const currentVal = parseFloat($qty.val()) || 0;
                const max = parseFloat($qty.attr('max')) || 999;
                const min = parseFloat($qty.attr('min')) || 0;
                const step = parseFloat($qty.attr('step')) || 1;

                let newVal;
                if ($this.hasClass('qty-plus')) {
                    newVal = currentVal + step;
                    if (newVal > max) newVal = max;
                } else {
                    newVal = currentVal - step;
                    if (newVal < min) newVal = min;
                }

                $qty.val(newVal).trigger('change');
            });
        },

        /**
         * Wishlist actions (if YITH Wishlist is active)
         */
        wishlistActions: function() {
            if (typeof yith_wcwl_l10n !== 'undefined') {
                $(document).on('click', '.add_to_wishlist', function() {
                    $(this).addClass('loading');
                });

                $(document).on('added_to_wishlist removed_from_wishlist', function() {
                    $('.add_to_wishlist').removeClass('loading');
                });
            }
        }
    };

})(jQuery);

// =====================================================================
// 19. 404.PHP - 404 Error Page Template
// =====================================================================

<?php
/**
 * The template for displaying 404 pages (not found)
 * 
 * @package AquaLuxe
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    <div class="container">
        <div class="error-404 not-found">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'aqualuxe'); ?></h1>
            </header>
            
            <div class="page-content">
                <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe'); ?></p>
                
                <div class="error-actions">
                    <div class="search-box">
                        <?php get_search_form(); ?>
                    </div>
                    
                    <div class="helpful-links">
                        <h3><?php esc_html_e('Try these helpful links:', 'aqualuxe'); ?></h3>
                        <ul>
                            <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home Page', 'aqualuxe'); ?></a></li>
                            <?php if (aqualuxe_is_woocommerce_active()) : ?>
                                <li><a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"><?php esc_html_e('Shop', 'aqualuxe'); ?></a></li>
                            <?php endif; ?>
                            <li><a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>"><?php esc_html_e('Blog', 'aqualuxe'); ?></a></li>
                        </ul>
                    </div>
                    
                    <?php if (aqualuxe_is_woocommerce_active()) : ?>
                        <div class="featured-products">
                            <h3><?php esc_html_e('Featured Products', 'aqualuxe'); ?></h3>
                            <?php
                            echo do_shortcode('[featured_products limit="4" columns="4"]');
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>

// =====================================================================
// 20. SEARCH.PHP - Search Results Template
// =====================================================================

<?php
/**
 * The template for displaying search results pages
 * 
 * @package AquaLuxe
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title">
                <?php
                printf(
                    esc_html__('Search Results for: %s', 'aqualuxe'),
                    '<span>' . get_search_query() . '</span>'
                );
                ?>
            </h1>
        </header>
        
        <div class="row">
            <div class="col-md-8">
                <?php if (have_posts()) : ?>
                    <div class="search-results">
                        <?php while (have_posts()) : the_post(); ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class('search-result'); ?>>
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="result-thumbnail">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('aqualuxe-thumbnail'); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="result-content">
                                    <header class="entry-header">
                                        <h2 class="entry-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        <div class="entry-meta">
                                            <span class="post-type"><?php echo esc_html(get_post_type()); ?></span>
                                            <span class="posted-on">
                                                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                                    <?php echo esc_html(get_the_date()); ?>
                                                </time>
                                            </span>
                                        </div>
                                    </header>
                                    
                                    <div class="entry-summary">
                                        <?php echo wp_kses_post(aqualuxe_get_excerpt(get_the_ID(), 25)); ?>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                    
                    <?php
                    the_posts_pagination(array(
                        'mid_size' => 2,
                        'prev_text' => esc_html__('Previous', 'aqualuxe'),
                        'next_text' => esc_html__('Next', 'aqualuxe'),
                    ));
                    ?>
                    
                <?php else : ?>
                    <div class="no-results not-found">
                        <header class="page-header">
                            <h2 class="page-title"><?php esc_html_e('Nothing found', 'aqualuxe'); ?></h2>
                        </header>
                        
                        <div class="page-content">
                            <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe'); ?></p>
                            <?php get_search_form(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="col-md-4">
                <aside id="secondary" class="widget-area" role="complementary">
                    <?php if (is_active_sidebar('sidebar-main')) : ?>
                        <?php dynamic_sidebar('sidebar-main'); ?>
                    <?php endif; ?>
                </aside>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>

// =====================================================================
// INSTALLATION & SETUP INSTRUCTIONS
// =====================================================================

/\*
AQUALUXE WORDPRESS THEME - INSTALLATION GUIDE

1. THEME INSTALLATION:

   - Download all files as aqualuxe.zip
   - Upload via WordPress Admin: Appearance > Themes > Add New > Upload Theme
   - Or upload via FTP to /wp-content/themes/aqualuxe/
   - Activate the theme

2. REQUIRED PLUGINS:

   - WooCommerce (Required for e-commerce functionality)
   - YITH WooCommerce Wishlist (Optional)
   - YITH WooCommerce Compare (Optional)

3. THEME SETUP:

   - Configure menus: Appearance > Menus
   - Set up widgets: Appearance > Widgets
   - Customize theme: Appearance > Customize > AquaLuxe Options
   - Import demo content (if available)

4. WOOCOMMERCE SETUP:

   - Run WooCommerce setup wizard
   - Configure shop pages
   - Set up payment gateways
   - Configure shipping options
   - Add products

5. PERFORMANCE OPTIMIZATION:

   - Install caching plugin (W3 Total Cache, WP Rocket)
   - Optimize images (WebP format)
   - Minify CSS/JS files
   - Use CDN for static assets

6. SEO OPTIMIZATION:

   - Install Yoast SEO or RankMath
   - Set up Google Analytics
   - Configure meta tags
   - Create XML sitemap

7. SECURITY:

   - Keep WordPress and plugins updated
   - Use strong passwords
   - Install security plugin (Wordfence)
   - Regular backups

8. CUSTOMIZATION:

   - Use WordPress Customizer for basic changes
   - Child theme for custom modifications
   - Custom CSS via Appearance > Customize > Additional CSS
   - Custom functions via child theme functions.php

9. SUPPORT:

   - Documentation: /documentation folder
   - Demo site: Available upon request
   - Support: Contact theme author

10. THEME FEATURES: - Fully responsive design - WooCommerce integration - SEO optimized - Performance optimized - Accessibility ready - Translation ready - Customizer integration - Multiple layouts - Custom widgets - Social media integration
    \*/

// =====================================================================
// 8. HEADER.PHP - Header Template
// =====================================================================

<?php
/**
 * The header template
 * 
 * @package AquaLuxe
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#main">
        <?php esc_html_e('Skip to content', 'aqualuxe'); ?>
    </a>

    <header id="masthead" class="site-header" role="banner">
        <div class="header-top">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="contact-info">
                            <span class="phone">
                                <i class="icon-phone" aria-hidden="true"></i>
                                <a href="tel:+1234567890">+1 (234) 567-890</a>
                            </span>
                            <span class="email">
                                <i class="icon-envelope" aria-hidden="true"></i>
                                <a href="mailto:info@aqualuxe.com">info@aqualuxe.com</a>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="header-actions">
                            <?php if (aqualuxe_is_woocommerce_active()) : ?>
                                <div class="account-links">
                                    <?php if (is_user_logged_in()) : ?>
                                        <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>">
                                            <?php esc_html_e('My Account', 'aqualuxe'); ?>
                                        </a>
                                    <?php else : ?>
                                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>">
                                            <?php esc_html_e('Login', 'aqualuxe'); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>

                                <div class="cart-toggle">
                                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-link">
                                        <i class="icon-shopping-cart" aria-hidden="true"></i>
                                        <span class="cart-count"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="header-main">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <div class="site-branding">
                            <?php if (has_custom_logo()) : ?>
                                <?php the_custom_logo(); ?>
                            <?php else : ?>
                                <h1 class="site-title">
                                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                        <?php bloginfo('name'); ?>
                                    </a>
                                </h1>
                                <?php $description = get_bloginfo('description', 'display'); ?>
                                <?php if ($description || is_customize_preview()) : ?>
                                    <p class="site-description"><?php echo esc_html($description); ?></p>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary Menu', 'aqualuxe'); ?>">
                            <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                                <span class="sr-only"><?php esc_html_e('Menu', 'aqualuxe'); ?></span>
                                <span class="hamburger"></span>
                            </button>
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'primary',
                                'menu_id' => 'primary-menu',
                                'menu_class' => 'nav-menu',
                                'container' => false,
                                'fallback_cb' => false,
                            ));
                            ?>
                        </nav>
                    </div>

                    <div class="col-md-3">
                        <div class="header-search">
                            <?php if (aqualuxe_is_woocommerce_active()) : ?>
                                <?php get_product_search_form(); ?>
                            <?php else : ?>
                                <?php get_search_form(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (aqualuxe_is_woocommerce_active() && (is_shop() || is_product_category() || is_product_tag())) : ?>
            <div class="shop-header">
                <div class="container">
                    <?php woocommerce_breadcrumb(); ?>
                    <?php if (is_shop()) : ?>
                        <h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
                    <?php elseif (is_product_category() || is_product_tag()) : ?>
                        <h1 class="page-title"><?php single_term_title(); ?></h1>
                        <?php if (term_description()) : ?>
                            <div class="term-description"><?php echo wp_kses_post(term_description()); ?></div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </header>

// =====================================================================
// 9. FOOTER.PHP - Footer Template
// =====================================================================

<?php
/**
 * The footer template
 * 
 * @package AquaLuxe
 */
?>

    <footer id="colophon" class="site-footer" role="contentinfo">
        <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) : ?>
            <div class="footer-widgets">
                <div class="container">
                    <div class="row">
                        <?php for ($i = 1; $i <= 4; $i++) : ?>
                            <?php if (is_active_sidebar('footer-' . $i)) : ?>
                                <div class="col-md-3">
                                    <div class="footer-widget-area">
                                        <?php dynamic_sidebar('footer-' . $i); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="site-info">
                            <p>&copy; <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'aqualuxe'); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="footer-navigation">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'footer',
                                'menu_class' => 'footer-menu',
                                'container' => false,
                                'fallback_cb' => false,
                                'depth' => 1,
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</div>

<!-- Quick View Modal -->
<?php if (aqualuxe_is_woocommerce_active()) : ?>

    <div id="quick-view-modal" class="modal" role="dialog" aria-labelledby="quick-view-title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="quick-view-title" class="modal-title"><?php esc_html_e('Quick View', 'aqualuxe'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="<?php esc_attr_e('Close', 'aqualuxe'); ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Quick view content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>

// =====================================================================
// 10. SINGLE.PHP - Single Post Template
// =====================================================================

<?php
/**
 * The template for displaying single posts
 * 
 * @package AquaLuxe
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php aqualuxe_breadcrumbs(); ?>
                    
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    
                    <div class="entry-meta">
                        <span class="posted-on">
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                <?php echo esc_html(get_the_date()); ?>
                            </time>
                        </span>
                        <span class="byline">
                            <?php esc_html_e('by', 'aqualuxe'); ?> 
                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                <?php the_author(); ?>
                            </a>
                        </span>
                        <?php if (has_category()) : ?>
                            <span class="categories">
                                <?php esc_html_e('in', 'aqualuxe'); ?> <?php the_category(', '); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </header>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="entry-thumbnail">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>
                
                <div class="entry-content">
                    <?php the_content(); ?>
                    
                    <?php
                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                        'after' => '</div>',
                    ));
                    ?>
                </div>
                
                <footer class="entry-footer">
                    <?php if (has_tag()) : ?>
                        <div class="tags">
                            <?php the_tags('<span class="tags-label">' . esc_html__('Tags:', 'aqualuxe') . '</span> ', ', '); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php aqualuxe_social_share(); ?>
                </footer>
            </article>
            
            <?php
            // Previous/Next post navigation
            the_post_navigation(array(
                'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
                'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
            ));
            ?>
            
            <?php
            // Comments
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            ?>
            
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
