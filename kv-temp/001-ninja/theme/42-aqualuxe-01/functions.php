<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 */

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_DIR', trailingslashit(get_template_directory()));
define('AQUALUXE_URI', trailingslashit(get_template_directory_uri()));
define('AQUALUXE_ASSETS_URI', AQUALUXE_URI . 'assets/dist/');
define('AQUALUXE_INC_DIR', AQUALUXE_DIR . 'inc/');
define('AQUALUXE_LANG_DIR', AQUALUXE_DIR . 'languages');

/**
 * AquaLuxe setup function
 */
if (!function_exists('aqualuxe_setup')) :
    function aqualuxe_setup() {
        // Make theme available for translation
        load_theme_textdomain('aqualuxe', AQUALUXE_LANG_DIR);

        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');

        // Let WordPress manage the document title
        add_theme_support('title-tag');

        // Enable support for Post Thumbnails on posts and pages
        add_theme_support('post-thumbnails');

        // Set up custom image sizes
        add_image_size('aqualuxe-featured', 1200, 800, true);
        add_image_size('aqualuxe-product-thumbnail', 600, 600, true);
        add_image_size('aqualuxe-blog-thumbnail', 800, 450, true);

        // Register nav menus
        register_nav_menus(array(
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'mobile' => esc_html__('Mobile Menu', 'aqualuxe'),
            'top-bar' => esc_html__('Top Bar Menu', 'aqualuxe'),
        ));

        // Switch default core markup to output valid HTML5
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ));

        // Set up the WordPress core custom background feature
        add_theme_support('custom-background', apply_filters('aqualuxe_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));

        // Add theme support for selective refresh for widgets
        add_theme_support('customize-selective-refresh-widgets');

        // Add support for custom logo
        add_theme_support('custom-logo', array(
            'height' => 250,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        ));

        // Add support for full and wide align images
        add_theme_support('align-wide');

        // Add support for responsive embeds
        add_theme_support('responsive-embeds');

        // Add support for editor styles
        add_theme_support('editor-styles');

        // Add support for block templates
        add_theme_support('block-templates');
    }
endif;
add_action('after_setup_theme', 'aqualuxe_setup');

/**
 * Set the content width in pixels
 */
function aqualuxe_content_width() {
    $GLOBALS['content_width'] = apply_filters('aqualuxe_content_width', 1200);
}
add_action('after_setup_theme', 'aqualuxe_content_width', 0);

/**
 * Register widget areas
 */
function aqualuxe_widgets_init() {
    register_sidebar(array(
        'name' => esc_html__('Sidebar', 'aqualuxe'),
        'id' => 'sidebar-1',
        'description' => esc_html__('Add widgets here to appear in your sidebar.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer 1', 'aqualuxe'),
        'id' => 'footer-1',
        'description' => esc_html__('Add widgets here to appear in footer column 1.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer 2', 'aqualuxe'),
        'id' => 'footer-2',
        'description' => esc_html__('Add widgets here to appear in footer column 2.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer 3', 'aqualuxe'),
        'id' => 'footer-3',
        'description' => esc_html__('Add widgets here to appear in footer column 3.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer 4', 'aqualuxe'),
        'id' => 'footer-4',
        'description' => esc_html__('Add widgets here to appear in footer column 4.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Shop Sidebar', 'aqualuxe'),
        'id' => 'shop-sidebar',
        'description' => esc_html__('Add widgets here to appear in shop sidebar.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'aqualuxe_widgets_init');

/**
 * Include required files
 */
require AQUALUXE_INC_DIR . 'helpers/template-functions.php';
require AQUALUXE_INC_DIR . 'helpers/template-tags.php';
require AQUALUXE_INC_DIR . 'hooks/hooks.php';
require AQUALUXE_INC_DIR . 'customizer/customizer.php';

/**
 * WooCommerce specific functions and overrides
 */
if (class_exists('WooCommerce')) {
    require AQUALUXE_INC_DIR . 'woocommerce/woocommerce.php';
}

/**
 * Enqueue scripts and styles with cache busting
 */
function aqualuxe_scripts() {
    // Get the mix-manifest.json file contents
    $manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : null;

    // Main CSS file
    $css_path = '/assets/dist/css/main.css';
    $css_url = get_template_directory_uri() . $css_path;
    
    // Add version from mix-manifest if available
    if ($manifest && isset($manifest[$css_path])) {
        $css_url = get_template_directory_uri() . $manifest[$css_path];
    }
    
    wp_enqueue_style('aqualuxe-style', $css_url, array(), AQUALUXE_VERSION);
    
    // Main JS file
    $js_path = '/assets/dist/js/main.js';
    $js_url = get_template_directory_uri() . $js_path;
    
    // Add version from mix-manifest if available
    if ($manifest && isset($manifest[$js_path])) {
        $js_url = get_template_directory_uri() . $manifest[$js_path];
    }
    
    wp_enqueue_script('aqualuxe-script', $js_url, array('jquery'), AQUALUXE_VERSION, true);

    // Dark mode script
    $dark_mode_path = '/assets/dist/js/dark-mode.js';
    $dark_mode_url = get_template_directory_uri() . $dark_mode_path;
    
    if ($manifest && isset($manifest[$dark_mode_path])) {
        $dark_mode_url = get_template_directory_uri() . $manifest[$dark_mode_path];
    }
    
    wp_enqueue_script('aqualuxe-dark-mode', $dark_mode_url, array('jquery'), AQUALUXE_VERSION, true);

    // Localize script for AJAX and translations
    wp_localize_script('aqualuxe-script', 'aqualuxeData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'themeUri' => get_template_directory_uri(),
        'homeUrl' => home_url('/'),
        'isWooCommerceActive' => class_exists('WooCommerce'),
        'darkMode' => array(
            'enabled' => get_theme_mod('aqualuxe_enable_dark_mode', true),
            'defaultMode' => get_theme_mod('aqualuxe_default_mode', 'light'),
            'autoMode' => get_theme_mod('aqualuxe_auto_dark_mode', true),
            'togglePosition' => get_theme_mod('aqualuxe_dark_mode_toggle_position', 'top-bar'),
            'primaryColor' => get_theme_mod('aqualuxe_dark_mode_primary_color', '#0ea5e9'),
            'bgColor' => get_theme_mod('aqualuxe_dark_mode_bg_color', '#121212'),
            'textColor' => get_theme_mod('aqualuxe_dark_mode_text_color', '#e5e5e5'),
        ),
        'i18n' => array(
            'addToCart' => esc_html__('Add to cart', 'aqualuxe'),
            'viewCart' => esc_html__('View cart', 'aqualuxe'),
            'addToWishlist' => esc_html__('Add to wishlist', 'aqualuxe'),
            'removeFromWishlist' => esc_html__('Remove from wishlist', 'aqualuxe'),
            'quickView' => esc_html__('Quick view', 'aqualuxe'),
            'loadMore' => esc_html__('Load more', 'aqualuxe'),
            'noMoreProducts' => esc_html__('No more products to load', 'aqualuxe'),
            'searchPlaceholder' => esc_html__('Search...', 'aqualuxe'),
            'darkMode' => esc_html__('Dark Mode', 'aqualuxe'),
            'lightMode' => esc_html__('Light Mode', 'aqualuxe'),
        ),
    ));

    // Add comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_scripts');

/**
 * Admin styles and scripts
 */
function aqualuxe_admin_scripts() {
    wp_enqueue_style('aqualuxe-admin-style', get_template_directory_uri() . '/assets/dist/css/admin.css', array(), AQUALUXE_VERSION);
}
add_action('admin_enqueue_scripts', 'aqualuxe_admin_scripts');

/**
 * Custom template tags for this theme
 */
require AQUALUXE_INC_DIR . 'helpers/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress
 */
require AQUALUXE_INC_DIR . 'helpers/template-functions.php';

/**
 * Load Jetpack compatibility file
 */
if (defined('JETPACK__VERSION')) {
    require AQUALUXE_INC_DIR . 'jetpack.php';
}

/**
 * Load custom widgets
 */
require AQUALUXE_INC_DIR . 'widgets/widgets.php';

/**
 * Load multilingual support
 */
require AQUALUXE_INC_DIR . 'helpers/multilingual.php';

/**
 * Load multi-currency support
 */
require AQUALUXE_INC_DIR . 'helpers/multi-currency.php';

/**
 * Load multi-vendor support
 */
require AQUALUXE_INC_DIR . 'helpers/multi-vendor.php';

/**
 * Load multi-tenant support
 */
require AQUALUXE_INC_DIR . 'helpers/multi-tenant.php';

/**
 * Load demo importer
 */
require AQUALUXE_INC_DIR . 'helpers/demo-importer.php';