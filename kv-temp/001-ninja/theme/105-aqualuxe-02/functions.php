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