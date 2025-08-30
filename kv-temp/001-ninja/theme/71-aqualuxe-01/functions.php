<?php
/**
 * AquaLuxe Theme Functions
 * 
 * Main theme setup and configuration file.
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('AQUALUXE_VERSION', wp_get_theme()->get('Version'));
define('AQUALUXE_THEME_DIR', get_template_directory());
define('AQUALUXE_THEME_URI', get_template_directory_uri());
define('AQUALUXE_ASSETS_URI', AQUALUXE_THEME_URI . '/assets/dist');
define('AQUALUXE_INCLUDES_DIR', AQUALUXE_THEME_DIR . '/inc');
define('AQUALUXE_MODULES_DIR', AQUALUXE_THEME_DIR . '/modules');
define('AQUALUXE_MIN_PHP', '8.0');
define('AQUALUXE_MIN_WP', '6.0');

/**
 * Check PHP and WordPress versions
 */
if (version_compare(PHP_VERSION, AQUALUXE_MIN_PHP, '<') || version_compare($GLOBALS['wp_version'], AQUALUXE_MIN_WP, '<')) {
    require_once AQUALUXE_INCLUDES_DIR . '/class-version-check.php';
    return;
}

/**
 * Autoloader for theme classes
 */
spl_autoload_register(function ($class) {
    $prefix = 'AquaLuxe\\';
    $base_dir = AQUALUXE_INCLUDES_DIR . '/classes/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . 'class-' . str_replace(['\\', '_'], ['-', '-'], strtolower($relative_class)) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

/**
 * Load core theme classes
 */
require_once AQUALUXE_INCLUDES_DIR . '/class-aqualuxe-theme.php';
require_once AQUALUXE_INCLUDES_DIR . '/class-asset-manager.php';
require_once AQUALUXE_INCLUDES_DIR . '/class-customizer.php';
require_once AQUALUXE_INCLUDES_DIR . '/class-woocommerce-integration.php';
require_once AQUALUXE_INCLUDES_DIR . '/class-security.php';
require_once AQUALUXE_INCLUDES_DIR . '/class-performance.php';
require_once AQUALUXE_INCLUDES_DIR . '/class-seo.php';
require_once AQUALUXE_INCLUDES_DIR . '/class-multilingual.php';
require_once AQUALUXE_INCLUDES_DIR . '/class-admin.php';
require_once AQUALUXE_INCLUDES_DIR . '/class-demo-content.php';

/**
 * Load helper functions
 */
require_once AQUALUXE_INCLUDES_DIR . '/template-functions.php';
require_once AQUALUXE_INCLUDES_DIR . '/template-hooks.php';
require_once AQUALUXE_INCLUDES_DIR . '/helper-functions.php';
require_once AQUALUXE_INCLUDES_DIR . '/woocommerce-functions.php';

/**
 * Load modules
 */
require_once AQUALUXE_INCLUDES_DIR . '/class-module-manager.php';

/**
 * Initialize the theme
 */
function aqualuxe_init() {
    // Initialize main theme class
    AquaLuxe_Theme::get_instance();
    
    // Initialize asset manager
    Asset_Manager::get_instance();
    
    // Initialize customizer
    AquaLuxe_Customizer::get_instance();
    
    // Initialize WooCommerce integration if WooCommerce is active
    if (class_exists('WooCommerce')) {
        WooCommerce_Integration::get_instance();
    }
    
    // Initialize security
    AquaLuxe_Security::get_instance();
    
    // Initialize performance optimizations
    AquaLuxe_Performance::get_instance();
    
    // Initialize SEO
    AquaLuxe_SEO::get_instance();
    
    // Initialize multilingual support
    AquaLuxe_Multilingual::get_instance();
    
    // Initialize admin features
    AquaLuxe_Admin::get_instance();
    
    // Initialize demo content
    AquaLuxe_Demo_Content::get_instance();
    
    // Initialize module manager
    AquaLuxe_Module_Manager::get_instance();
}
add_action('after_setup_theme', 'aqualuxe_init');

/**
 * Theme setup
 */
function aqualuxe_setup() {
    // Add theme support for various features
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'script',
        'style',
        'navigation-widgets',
    ]);
    
    // Add theme support for custom logo
    add_theme_support('custom-logo', [
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ]);
    
    // Add theme support for custom background
    add_theme_support('custom-background', [
        'default-color' => 'ffffff',
        'default-image' => '',
    ]);
    
    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');
    
    // Add theme support for editor styles
    add_theme_support('editor-styles');
    add_editor_style('assets/dist/css/editor.css');
    
    // Add theme support for wide alignment
    add_theme_support('align-wide');
    
    // Add theme support for block editor color palette
    add_theme_support('editor-color-palette', [
        [
            'name'  => esc_html__('Primary', 'aqualuxe'),
            'slug'  => 'primary',
            'color' => '#06b6d4',
        ],
        [
            'name'  => esc_html__('Secondary', 'aqualuxe'),
            'slug'  => 'secondary',
            'color' => '#d946ef',
        ],
        [
            'name'  => esc_html__('Aqua', 'aqualuxe'),
            'slug'  => 'aqua',
            'color' => '#22d3ee',
        ],
        [
            'name'  => esc_html__('Luxe', 'aqualuxe'),
            'slug'  => 'luxe',
            'color' => '#eab308',
        ],
        [
            'name'  => esc_html__('Coral', 'aqualuxe'),
            'slug'  => 'coral',
            'color' => '#ef4444',
        ],
        [
            'name'  => esc_html__('Kelp', 'aqualuxe'),
            'slug'  => 'kelp',
            'color' => '#22c55e',
        ],
    ]);
    
    // Add theme support for block editor gradient presets
    add_theme_support('editor-gradient-presets', [
        [
            'name'     => esc_html__('Aqua to Luxe', 'aqualuxe'),
            'gradient' => 'linear-gradient(135deg, #06b6d4 0%, #eab308 100%)',
            'slug'     => 'aqua-to-luxe',
        ],
        [
            'name'     => esc_html__('Ocean Depths', 'aqualuxe'),
            'gradient' => 'linear-gradient(135deg, #164e63 0%, #0891b2 100%)',
            'slug'     => 'ocean-depths',
        ],
    ]);
    
    // Register navigation menus
    register_nav_menus([
        'primary'   => esc_html__('Primary Navigation', 'aqualuxe'),
        'secondary' => esc_html__('Secondary Navigation', 'aqualuxe'),
        'footer'    => esc_html__('Footer Navigation', 'aqualuxe'),
        'mobile'    => esc_html__('Mobile Navigation', 'aqualuxe'),
    ]);
    
    // Set content width
    if (!isset($content_width)) {
        $content_width = 1200;
    }
    
    // Load text domain for translations
    load_theme_textdomain('aqualuxe', AQUALUXE_THEME_DIR . '/languages');
    
    // Add image sizes
    add_image_size('aqualuxe-featured', 1200, 675, true);
    add_image_size('aqualuxe-medium', 800, 600, true);
    add_image_size('aqualuxe-small', 400, 300, true);
    add_image_size('aqualuxe-thumbnail', 300, 300, true);
    add_image_size('aqualuxe-hero', 1920, 1080, true);
    add_image_size('aqualuxe-gallery', 600, 400, true);
    
    // WooCommerce theme support
    if (class_exists('WooCommerce')) {
        add_theme_support('woocommerce', [
            'thumbnail_image_width' => 300,
            'single_image_width'    => 600,
            'product_grid'          => [
                'default_rows'    => 3,
                'min_rows'        => 2,
                'max_rows'        => 8,
                'default_columns' => 4,
                'min_columns'     => 2,
                'max_columns'     => 5,
            ],
        ]);
        
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
}
add_action('after_setup_theme', 'aqualuxe_setup');

/**
 * Register widget areas
 */
function aqualuxe_widgets_init() {
    register_sidebar([
        'name'          => esc_html__('Primary Sidebar', 'aqualuxe'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ]);
    
    register_sidebar([
        'name'          => esc_html__('Footer 1', 'aqualuxe'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Footer widget area 1.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);
    
    register_sidebar([
        'name'          => esc_html__('Footer 2', 'aqualuxe'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Footer widget area 2.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);
    
    register_sidebar([
        'name'          => esc_html__('Footer 3', 'aqualuxe'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Footer widget area 3.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);
    
    register_sidebar([
        'name'          => esc_html__('Footer 4', 'aqualuxe'),
        'id'            => 'footer-4',
        'description'   => esc_html__('Footer widget area 4.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);
    
    // WooCommerce widget areas
    if (class_exists('WooCommerce')) {
        register_sidebar([
            'name'          => esc_html__('Shop Sidebar', 'aqualuxe'),
            'id'            => 'shop-sidebar',
            'description'   => esc_html__('Shop and product page sidebar.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ]);
    }
}
add_action('widgets_init', 'aqualuxe_widgets_init');

/**
 * Set up theme defaults and register support for various WordPress features
 */
function aqualuxe_content_width() {
    $GLOBALS['content_width'] = apply_filters('aqualuxe_content_width', 1200);
}
add_action('after_setup_theme', 'aqualuxe_content_width', 0);

/**
 * Enqueue scripts and styles
 */
function aqualuxe_scripts() {
    // Get the asset manager instance
    $asset_manager = Asset_Manager::get_instance();
    $asset_manager->enqueue_theme_assets();
}
add_action('wp_enqueue_scripts', 'aqualuxe_scripts');

/**
 * Enqueue admin scripts and styles
 */
function aqualuxe_admin_scripts($hook) {
    $asset_manager = Asset_Manager::get_instance();
    $asset_manager->enqueue_admin_assets($hook);
}
add_action('admin_enqueue_scripts', 'aqualuxe_admin_scripts');

/**
 * Enqueue customizer scripts and styles
 */
function aqualuxe_customizer_scripts() {
    $asset_manager = Asset_Manager::get_instance();
    $asset_manager->enqueue_customizer_assets();
}
add_action('customize_controls_enqueue_scripts', 'aqualuxe_customizer_scripts');

/**
 * Add custom body classes
 */
function aqualuxe_body_classes($classes) {
    // Add class for WooCommerce
    if (class_exists('WooCommerce')) {
        $classes[] = 'woocommerce-enabled';
    }
    
    // Add class for dark mode preference
    if (get_theme_mod('aqualuxe_dark_mode_enabled', false)) {
        $classes[] = 'dark-mode-available';
    }
    
    // Add class based on page template
    if (is_page_template()) {
        $template = str_replace('.php', '', basename(get_page_template()));
        $classes[] = 'page-template-' . $template;
    }
    
    // Add class for mobile detection
    if (wp_is_mobile()) {
        $classes[] = 'mobile-device';
    }
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Add pingback url auto-discovery header for single posts, pages, or attachments
 */
function aqualuxe_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'aqualuxe_pingback_header');

/**
 * Disable WordPress emoji support
 */
function aqualuxe_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    
    add_filter('tiny_mce_plugins', function($plugins) {
        if (is_array($plugins)) {
            return array_diff($plugins, ['wpemoji']);
        }
        return [];
    });
    
    add_filter('wp_resource_hints', function($urls, $relation_type) {
        if ('dns-prefetch' == $relation_type) {
            $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');
            $urls = array_diff($urls, [$emoji_svg_url]);
        }
        return $urls;
    }, 10, 2);
}
add_action('init', 'aqualuxe_disable_emojis');

/**
 * Remove jQuery Migrate
 */
function aqualuxe_remove_jquery_migrate() {
    if (!is_admin() && !is_customize_preview()) {
        wp_deregister_script('jquery');
        wp_register_script('jquery', false, ['jquery-core'], false, true);
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_remove_jquery_migrate');

/**
 * Remove unnecessary WordPress features for performance
 */
function aqualuxe_cleanup_wp_head() {
    // Remove really simple discovery link
    remove_action('wp_head', 'rsd_link');
    
    // Remove windows live writer link
    remove_action('wp_head', 'wlwmanifest_link');
    
    // Remove wp version
    remove_action('wp_head', 'wp_generator');
    
    // Remove shortlink
    remove_action('wp_head', 'wp_shortlink_wp_head');
    
    // Remove feed links
    remove_action('wp_head', 'feed_links_extra', 3);
    
    // Remove REST API links
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    
    // Remove oEmbed-specific JavaScript
    remove_action('wp_head', 'wp_oembed_add_host_js');
}
add_action('init', 'aqualuxe_cleanup_wp_head');

/**
 * Add schema.org markup
 */
function aqualuxe_schema_markup() {
    $schema = '';
    
    if (is_home() || is_front_page()) {
        $schema = 'itemscope itemtype="https://schema.org/WebSite"';
    } elseif (is_single()) {
        $schema = 'itemscope itemtype="https://schema.org/Article"';
    } elseif (is_page()) {
        $schema = 'itemscope itemtype="https://schema.org/WebPage"';
    } elseif (is_archive()) {
        $schema = 'itemscope itemtype="https://schema.org/CollectionPage"';
    }
    
    return $schema;
}

/**
 * Add Open Graph and Twitter Card meta tags
 */
function aqualuxe_social_meta() {
    if (is_front_page()) {
        $title = get_bloginfo('name') . ' - ' . get_bloginfo('description');
        $description = get_bloginfo('description');
        $image = get_theme_mod('aqualuxe_social_image', '');
    } elseif (is_single() || is_page()) {
        $title = get_the_title();
        $description = get_the_excerpt() ?: wp_trim_words(get_the_content(), 20);
        $image = get_the_post_thumbnail_url(get_the_ID(), 'large');
    } else {
        $title = wp_get_document_title();
        $description = get_bloginfo('description');
        $image = get_theme_mod('aqualuxe_social_image', '');
    }
    
    if (!$image) {
        $image = AQUALUXE_THEME_URI . '/assets/src/images/default-social-image.jpg';
    }
    
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
    echo '<meta property="og:type" content="website">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "\n";
}
add_action('wp_head', 'aqualuxe_social_meta');

/**
 * Add theme support for Gutenberg
 */
function aqualuxe_gutenberg_support() {
    // Add theme support for responsive embeds
    add_theme_support('responsive-embeds');
    
    // Add theme support for editor styles
    add_theme_support('editor-styles');
    
    // Add theme support for dark editor style
    add_theme_support('dark-editor-style');
    
    // Enqueue editor styles
    add_editor_style([
        'assets/dist/css/editor.css',
        aqualuxe_google_fonts_url(),
    ]);
}
add_action('after_setup_theme', 'aqualuxe_gutenberg_support');

/**
 * Get Google Fonts URL
 */
function aqualuxe_google_fonts_url() {
    $fonts_url = '';
    
    $font_families = [
        'Inter:wght@300;400;500;600;700',
        'Playfair+Display:wght@400;500;600;700',
        'Montserrat:wght@300;400;500;600;700',
        'JetBrains+Mono:wght@400;500',
    ];
    
    $query_args = [
        'family' => implode('&family=', $font_families),
        'subset' => 'latin,latin-ext',
        'display' => 'swap',
    ];
    
    $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css2');
    
    return esc_url_raw($fonts_url);
}

/**
 * Custom excerpt length
 */
function aqualuxe_excerpt_length($length) {
    return 30;
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
 * Add custom query vars
 */
function aqualuxe_query_vars($vars) {
    $vars[] = 'ajax';
    $vars[] = 'load_more';
    return $vars;
}
add_filter('query_vars', 'aqualuxe_query_vars');

/**
 * Theme activation hook
 */
function aqualuxe_activation() {
    // Flush rewrite rules
    flush_rewrite_rules();
    
    // Set default theme options
    if (!get_option('aqualuxe_theme_activated')) {
        // Set default customizer options
        set_theme_mod('aqualuxe_primary_color', '#06b6d4');
        set_theme_mod('aqualuxe_secondary_color', '#d946ef');
        set_theme_mod('aqualuxe_dark_mode_enabled', true);
        set_theme_mod('aqualuxe_performance_optimizations', true);
        
        // Mark theme as activated
        update_option('aqualuxe_theme_activated', true);
    }
}
add_action('after_switch_theme', 'aqualuxe_activation');

/**
 * Theme deactivation hook
 */
function aqualuxe_deactivation() {
    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('switch_theme', 'aqualuxe_deactivation');

/**
 * AJAX handler for theme features
 */
function aqualuxe_ajax_handler() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_ajax_nonce')) {
        wp_die('Security check failed');
    }
    
    $action = sanitize_text_field($_POST['action_type']);
    
    switch ($action) {
        case 'load_more_posts':
            // Handle load more posts
            break;
        case 'quick_view':
            // Handle quick view
            break;
        case 'add_to_wishlist':
            // Handle add to wishlist
            break;
        default:
            wp_die('Invalid action');
    }
}
add_action('wp_ajax_aqualuxe_ajax', 'aqualuxe_ajax_handler');
add_action('wp_ajax_nopriv_aqualuxe_ajax', 'aqualuxe_ajax_handler');
