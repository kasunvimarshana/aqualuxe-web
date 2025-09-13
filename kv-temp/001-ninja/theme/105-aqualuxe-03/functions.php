<?php
/**
 * AquaLuxe Theme Functions
 *
 * Main functions file that bootstraps the theme and loads all modules
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Define theme constants
 */
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_THEME_DIR', get_template_directory());
define('AQUALUXE_THEME_URI', get_template_directory_uri());
define('AQUALUXE_ASSETS_URI', AQUALUXE_THEME_URI . '/assets/dist');
define('AQUALUXE_INCLUDES_DIR', AQUALUXE_THEME_DIR . '/inc');
define('AQUALUXE_CORE_DIR', AQUALUXE_THEME_DIR . '/core');
define('AQUALUXE_MODULES_DIR', AQUALUXE_THEME_DIR . '/modules');

/**
 * Theme setup and initialization
 */
function aqualuxe_setup() {
    // Make theme available for translation
    load_theme_textdomain('aqualuxe', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Set default image sizes
    set_post_thumbnail_size(1200, 9999);
    add_image_size('aqualuxe-featured', 1200, 600, true);
    add_image_size('aqualuxe-thumbnail', 400, 300, true);
    add_image_size('aqualuxe-medium', 800, 600, true);

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'aqualuxe'),
        'footer' => esc_html__('Footer Menu', 'aqualuxe'),
        'mobile' => esc_html__('Mobile Menu', 'aqualuxe'),
    ));

    // Switch default core markup to HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for core custom logo
    add_theme_support('custom-logo', array(
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ));

    // Add support for editor styles
    add_theme_support('editor-styles');
    add_editor_style('assets/dist/css/editor.css');
}
add_action('after_setup_theme', 'aqualuxe_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet
 */
function aqualuxe_content_width() {
    $GLOBALS['content_width'] = apply_filters('aqualuxe_content_width', 1200);
}
add_action('after_setup_theme', 'aqualuxe_content_width', 0);

/**
 * Autoloader for theme classes (PSR-4 compliant)
 */
spl_autoload_register(function ($class) {
    // Project-specific namespace prefix
    $prefix = 'AquaLuxe\\';

    // Base directory for the namespace prefix
    $base_dir = AQUALUXE_CORE_DIR . '/classes/';

    // Check if the class uses the namespace prefix
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace namespace separators with directory separators
    $file_path = str_replace('\\', '/', $relative_class);
    $file = $base_dir . $file_path . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

/**
 * Include core theme functions
 */
require_once AQUALUXE_CORE_DIR . '/functions/theme-support.php';
require_once AQUALUXE_CORE_DIR . '/functions/enqueue-scripts.php';
require_once AQUALUXE_CORE_DIR . '/functions/template-functions.php';
require_once AQUALUXE_CORE_DIR . '/functions/security.php';
require_once AQUALUXE_CORE_DIR . '/functions/enhanced-security.php';

/**
 * Include additional theme functionality
 */
require_once AQUALUXE_INCLUDES_DIR . '/nav-walker.php';
require_once AQUALUXE_INCLUDES_DIR . '/config.php';
require_once AQUALUXE_INCLUDES_DIR . '/customizer/customizer.php';
require_once AQUALUXE_INCLUDES_DIR . '/admin/admin-init.php';
require_once AQUALUXE_INCLUDES_DIR . '/custom-post-types.php';
require_once AQUALUXE_INCLUDES_DIR . '/custom-taxonomies.php';
require_once AQUALUXE_INCLUDES_DIR . '/custom-fields.php';
require_once AQUALUXE_INCLUDES_DIR . '/seo-functions.php';
require_once AQUALUXE_INCLUDES_DIR . '/performance-functions.php';
require_once AQUALUXE_INCLUDES_DIR . '/demo-importer/demo-importer.php';
require_once AQUALUXE_INCLUDES_DIR . '/demo-importer/demo-importer-admin.php';

/**
 * Initialize theme core
 */
function aqualuxe_init() {
    // Initialize theme core
    \AquaLuxe\Core\ThemeCore::get_instance();
    
    // Initialize module system
    \AquaLuxe\Core\ModuleManager::get_instance();
    
    // Initialize WooCommerce compatibility
    \AquaLuxe\Core\WooCommerceCompat::get_instance();
}
add_action('init', 'aqualuxe_init');

/**
 * Register widget areas
 */
function aqualuxe_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Primary Sidebar', 'aqualuxe'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Area 1', 'aqualuxe'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add widgets here to appear in footer.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Area 2', 'aqualuxe'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Add widgets here to appear in footer.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Area 3', 'aqualuxe'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Add widgets here to appear in footer.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Area 4', 'aqualuxe'),
        'id'            => 'footer-4',
        'description'   => esc_html__('Add widgets here to appear in footer.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'aqualuxe_widgets_init');

/**
 * Custom excerpt length
 */
function aqualuxe_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length', 999);

/**
 * Custom excerpt more
 */
function aqualuxe_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Initialize enhanced WooCommerce features
 */
function aqualuxe_init_woocommerce_enhancements() {
    if (class_exists('WooCommerce')) {
        // Initialize WooCommerce Security
        if (class_exists('AquaLuxe\Core\WooCommerceSecurity')) {
            \AquaLuxe\Core\WooCommerceSecurity::get_instance();
        }
        
        // Initialize WooCommerce Performance
        if (class_exists('AquaLuxe\Core\WooCommercePerformance')) {
            \AquaLuxe\Core\WooCommercePerformance::get_instance();
        }
        
        // Initialize Multi-Currency
        if (class_exists('AquaLuxe\Core\MultiCurrency')) {
            \AquaLuxe\Core\MultiCurrency::get_instance();
        }
    }
}
add_action('after_setup_theme', 'aqualuxe_init_woocommerce_enhancements');

/**
 * Initialize AJAX handlers for enhanced functionality
 */
function aqualuxe_init_ajax_handlers() {
    $endpoints = array(
        'aqualuxe_add_to_cart',
        'aqualuxe_quick_view', 
        'aqualuxe_wishlist_toggle',
        'aqualuxe_track_order',
        'aqualuxe_product_search',
        'aqualuxe_load_more_products'
    );
    
    foreach ($endpoints as $endpoint) {
        add_action("wp_ajax_{$endpoint}", function() use ($endpoint) {
            aqualuxe_handle_ajax_request($endpoint);
        });
        add_action("wp_ajax_nopriv_{$endpoint}", function() use ($endpoint) {
            aqualuxe_handle_ajax_request($endpoint);
        });
    }
}
add_action('init', 'aqualuxe_init_ajax_handlers');

/**
 * Enqueue AJAX variables with security and performance optimization
 */
function aqualuxe_localize_scripts() {
    if (wp_script_is('aqualuxe-app', 'enqueued') || wp_script_is('aqualuxe-woocommerce', 'enqueued')) {
        $cart_url = class_exists('WooCommerce') ? wc_get_cart_url() : '#';
        $checkout_url = class_exists('WooCommerce') ? wc_get_checkout_url() : '#';
        
        wp_localize_script('aqualuxe-app', 'aqualuxe_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_ajax_nonce'),
            'messages' => array(
                'added_to_cart' => __('Product added to cart!', 'aqualuxe'),
                'removed_from_cart' => __('Product removed from cart!', 'aqualuxe'),
                'added_to_wishlist' => __('Added to wishlist!', 'aqualuxe'),
                'removed_from_wishlist' => __('Removed from wishlist!', 'aqualuxe'),
                'loading' => __('Loading...', 'aqualuxe'),
                'error' => __('Something went wrong. Please try again.', 'aqualuxe')
            ),
            'cart_url' => esc_url($cart_url),
            'checkout_url' => esc_url($checkout_url),
            'is_user_logged_in' => is_user_logged_in(),
            'woocommerce_active' => class_exists('WooCommerce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_localize_scripts', 30);

/**
 * Generic AJAX handler router with enhanced security
 */
function aqualuxe_handle_ajax_request($action) {
    // Comprehensive security checks
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_ajax_nonce')) {
        wp_send_json_error(__('Security check failed.', 'aqualuxe'), 403);
    }
    
    // Rate limiting check
    if (!aqualuxe_check_rate_limit()) {
        wp_send_json_error(__('Too many requests. Please try again later.', 'aqualuxe'), 429);
    }
    
    // Route to appropriate handler based on action
    switch ($action) {
        case 'aqualuxe_add_to_cart':
        case 'aqualuxe_quick_view':
        case 'aqualuxe_wishlist_toggle':
        case 'aqualuxe_track_order':
            if (class_exists('AquaLuxe\Core\WooCommerceSecurity')) {
                $security_handler = \AquaLuxe\Core\WooCommerceSecurity::get_instance();
                $method_name = 'secure_ajax_' . str_replace('aqualuxe_', '', $action);
                
                if (method_exists($security_handler, $method_name)) {
                    call_user_func(array($security_handler, $method_name));
                } else {
                    wp_send_json_error(__('Handler not found.', 'aqualuxe'), 404);
                }
            } else {
                wp_send_json_error(__('WooCommerce not available.', 'aqualuxe'), 503);
            }
            break;
            
        case 'aqualuxe_product_search':
        case 'aqualuxe_load_more_products':
            // Handle product search and pagination
            wp_send_json_error(__('Feature coming soon.', 'aqualuxe'), 501);
            break;
            
        default:
            wp_send_json_error(__('Invalid action.', 'aqualuxe'), 400);
    }
}

/**
 * Simple rate limiting for AJAX requests
 */
function aqualuxe_check_rate_limit() {
    $user_ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $transient_key = 'aqualuxe_rate_limit_' . md5($user_ip);
    $requests = get_transient($transient_key);
    
    if ($requests === false) {
        set_transient($transient_key, 1, MINUTE_IN_SECONDS);
        return true;
    }
    
    if ($requests >= 30) { // Max 30 requests per minute
        return false;
    }
    
    set_transient($transient_key, $requests + 1, MINUTE_IN_SECONDS);
    return true;
}