<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Define theme constants
define('AQUALUXE_VERSION', '1.1.0');
define('AQUALUXE_DIR', get_template_directory());
define('AQUALUXE_URI', get_template_directory_uri());

/**
 * AquaLuxe setup.
 *
 * Sets up theme defaults and registers support for various WordPress features.
 */
function aqualuxe_setup() {
    /*
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     * This must be the first thing in the function to avoid warnings.
     */
    load_theme_textdomain('aqualuxe', AQUALUXE_DIR . '/languages');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support('title-tag');

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    // Set default thumbnail size
    set_post_thumbnail_size(1200, 9999);

    // Add custom image sizes
    add_image_size('aqualuxe-featured', 1600, 900, true);
    add_image_size('aqualuxe-product-thumbnail', 600, 600, true);
    add_image_size('aqualuxe-product-gallery', 1200, 1200, true);
    add_image_size('aqualuxe-recent-post-thumbnail', 400, 300, true);
    add_image_size('aqualuxe-recent-post-small', 100, 100, true);

    // Register navigation menus
    register_nav_menus(
        array(
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'social' => esc_html__('Social Links Menu', 'aqualuxe'),
            'mobile' => esc_html__('Mobile Menu', 'aqualuxe'),
        )
    );

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );

    // Set up the WordPress core custom background feature.
    add_theme_support(
        'custom-background',
        apply_filters(
            'aqualuxe_custom_background_args',
            array(
                'default-color' => 'ffffff',
                'default-image' => '',
            )
        )
    );

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Add support for core custom logo.
     *
     * @link https://codex.wordpress.org/Theme_Logo
     */
    add_theme_support(
        'custom-logo',
        array(
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        )
    );

    // Add support for full and wide align images.
    add_theme_support('align-wide');

    // Add support for editor styles.
    add_theme_support('editor-styles');

    // Add support for responsive embeds.
    add_theme_support('responsive-embeds');

    // Add theme support for block styles
    add_theme_support('wp-block-styles');
}
add_action('after_setup_theme', 'aqualuxe_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function aqualuxe_content_width() {
    $GLOBALS['content_width'] = apply_filters('aqualuxe_content_width', 1200);
}
add_action('after_setup_theme', 'aqualuxe_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function aqualuxe_widgets_init() {
    register_sidebar(
        array(
            'name'          => esc_html__('Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-1',
            'description'   => esc_html__('Add widgets here.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__('Footer 1', 'aqualuxe'),
            'id'            => 'footer-1',
            'description'   => esc_html__('Add footer widgets here.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__('Footer 2', 'aqualuxe'),
            'id'            => 'footer-2',
            'description'   => esc_html__('Add footer widgets here.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__('Footer 3', 'aqualuxe'),
            'id'            => 'footer-3',
            'description'   => esc_html__('Add footer widgets here.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__('Footer 4', 'aqualuxe'),
            'id'            => 'footer-4',
            'description'   => esc_html__('Add footer widgets here.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    // Shop sidebar
    register_sidebar(
        array(
            'name'          => esc_html__('Shop Sidebar', 'aqualuxe'),
            'id'            => 'shop-sidebar',
            'description'   => esc_html__('Add shop widgets here.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );
}
add_action('widgets_init', 'aqualuxe_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style('aqualuxe-style', get_stylesheet_uri(), array(), AQUALUXE_VERSION);
    
    // Enqueue Tailwind CSS
    wp_enqueue_style('aqualuxe-tailwind', AQUALUXE_URI . '/assets/css/tailwind.css', array(), AQUALUXE_VERSION);
    
    // Enqueue custom styles
    wp_enqueue_style('aqualuxe-custom', AQUALUXE_URI . '/assets/css/custom.css', array('aqualuxe-tailwind'), AQUALUXE_VERSION);
    
    // Enqueue dark mode styles
    wp_enqueue_style('aqualuxe-dark-mode', AQUALUXE_URI . '/assets/css/dark-mode.css', array('aqualuxe-custom'), AQUALUXE_VERSION);
    
    // Enqueue accessibility styles
    wp_enqueue_style('aqualuxe-accessibility', AQUALUXE_URI . '/assets/css/accessibility.css', array('aqualuxe-dark-mode'), AQUALUXE_VERSION);
    
    // Enqueue main JavaScript file
    wp_enqueue_script('aqualuxe-navigation', AQUALUXE_URI . '/assets/js/navigation.js', array(), AQUALUXE_VERSION, true);
    
    // Enqueue dark mode script
    wp_enqueue_script('aqualuxe-dark-mode', AQUALUXE_URI . '/assets/js/dark-mode.js', array(), AQUALUXE_VERSION, true);
    
    // Enqueue custom scripts
    wp_enqueue_script('aqualuxe-custom', AQUALUXE_URI . '/assets/js/custom.js', array('jquery'), AQUALUXE_VERSION, true);
    
    // Enqueue accessibility scripts
    wp_enqueue_script('aqualuxe-keyboard-navigation', AQUALUXE_URI . '/assets/js/keyboard-navigation.js', array('jquery'), AQUALUXE_VERSION, true);
    wp_enqueue_script('aqualuxe-focus-management', AQUALUXE_URI . '/assets/js/focus-management.js', array('jquery'), AQUALUXE_VERSION, true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    // Localize script for dark mode
    wp_localize_script('aqualuxe-dark-mode', 'aqualuxeSettings', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe-dark-mode-nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'aqualuxe_scripts');

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function aqualuxe_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }
    return $urls;
}
add_filter('wp_resource_hints', 'aqualuxe_resource_hints', 10, 2);

/**
 * Include required files
 */
// Core functionality
require_once AQUALUXE_DIR . '/inc/core/template-functions.php';
require_once AQUALUXE_DIR . '/inc/core/template-tags.php';
require_once AQUALUXE_DIR . '/inc/core/hooks.php';
require_once AQUALUXE_DIR . '/inc/core/pagination.php';
require_once AQUALUXE_DIR . '/inc/core/walker-nav-menu.php';

// Helper functions
require_once AQUALUXE_DIR . '/inc/helpers/sanitize.php';
require_once AQUALUXE_DIR . '/inc/helpers/markup.php';

// Customizer
require_once AQUALUXE_DIR . '/inc/customizer/customizer.php';

// Custom post types and taxonomies
require_once AQUALUXE_DIR . '/inc/core/post-types.php';
require_once AQUALUXE_DIR . '/inc/core/taxonomies.php';

// Widgets - Use enhanced widget system
require_once AQUALUXE_DIR . '/inc/widgets/widgets.php';

// Dark mode
require_once AQUALUXE_DIR . '/inc/core/dark-mode.php';

// Multilingual support
require_once AQUALUXE_DIR . '/inc/core/multilingual.php';

// Accessibility
require_once AQUALUXE_DIR . '/inc/core/accessibility.php';

// SEO
require_once AQUALUXE_DIR . '/inc/core/seo.php';

// Demo Importer
require_once AQUALUXE_DIR . '/inc/demo-importer/class-aqualuxe-demo-importer-enhanced.php';

/**
 * WooCommerce specific functions and overrides.
 */
if (class_exists('WooCommerce')) {
    require_once AQUALUXE_DIR . '/inc/woocommerce/woocommerce.php';
    require_once AQUALUXE_DIR . '/inc/woocommerce/template-hooks.php';
    require_once AQUALUXE_DIR . '/inc/woocommerce/template-functions.php';
    require_once AQUALUXE_DIR . '/inc/woocommerce/quick-view.php';
    require_once AQUALUXE_DIR . '/inc/woocommerce/wishlist.php';
    require_once AQUALUXE_DIR . '/inc/woocommerce/multi-currency.php';
    require_once AQUALUXE_DIR . '/inc/woocommerce/international-shipping.php';
}

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
    require_once AQUALUXE_DIR . '/inc/jetpack.php';
}

/**
 * Performance optimizations
 */
function aqualuxe_performance_optimizations() {
    // Remove emoji scripts
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    
    // Remove REST API link
    remove_action('wp_head', 'rest_output_link_wp_head');
    
    // Remove oEmbed discovery links
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    
    // Remove shortlink
    remove_action('wp_head', 'wp_shortlink_wp_head');
    
    // Remove generator tag
    remove_action('wp_head', 'wp_generator');
    
    // Remove wlwmanifest link
    remove_action('wp_head', 'wlwmanifest_link');
    
    // Remove RSD link
    remove_action('wp_head', 'rsd_link');
}
add_action('after_setup_theme', 'aqualuxe_performance_optimizations');

/**
 * Add theme info page
 */
function aqualuxe_theme_info_page() {
    add_theme_page(
        __('AquaLuxe Theme', 'aqualuxe'),
        __('AquaLuxe Theme', 'aqualuxe'),
        'manage_options',
        'aqualuxe-theme-info',
        'aqualuxe_theme_info_page_content'
    );
}
add_action('admin_menu', 'aqualuxe_theme_info_page');

/**
 * Theme info page content
 */
function aqualuxe_theme_info_page_content() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('AquaLuxe Theme Information', 'aqualuxe'); ?></h1>
        
        <div class="aqualuxe-theme-info-wrapper">
            <div class="aqualuxe-theme-info-header">
                <img src="<?php echo esc_url(AQUALUXE_URI . '/screenshot.png'); ?>" alt="<?php esc_attr_e('AquaLuxe Theme', 'aqualuxe'); ?>" class="aqualuxe-theme-screenshot">
                <div class="aqualuxe-theme-info-header-content">
                    <h2><?php esc_html_e('AquaLuxe Premium Theme', 'aqualuxe'); ?></h2>
                    <p><?php esc_html_e('Version', 'aqualuxe'); ?>: <?php echo AQUALUXE_VERSION; ?></p>
                    <p><?php esc_html_e('A premium WordPress theme for luxury aquatic retail businesses.', 'aqualuxe'); ?></p>
                </div>
            </div>
            
            <div class="aqualuxe-theme-info-content">
                <h3><?php esc_html_e('Theme Features', 'aqualuxe'); ?></h3>
                <ul>
                    <li><?php esc_html_e('Responsive Design', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('WooCommerce Integration', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Dark Mode Support', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Enhanced Widgets', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Demo Content Importer', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Accessibility Ready', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('SEO Optimized', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Multilingual Support', 'aqualuxe'); ?></li>
                </ul>
                
                <h3><?php esc_html_e('Quick Links', 'aqualuxe'); ?></h3>
                <p>
                    <a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="button button-primary"><?php esc_html_e('Customize Theme', 'aqualuxe'); ?></a>
                    <a href="<?php echo esc_url(admin_url('themes.php?page=aqualuxe-demo-import')); ?>" class="button"><?php esc_html_e('Import Demo Content', 'aqualuxe'); ?></a>
                    <a href="<?php echo esc_url('https://aqualuxe.example.com/documentation'); ?>" class="button" target="_blank"><?php esc_html_e('Documentation', 'aqualuxe'); ?></a>
                    <a href="<?php echo esc_url('https://aqualuxe.example.com/support'); ?>" class="button" target="_blank"><?php esc_html_e('Support', 'aqualuxe'); ?></a>
                </p>
            </div>
        </div>
    </div>
    <style>
        .aqualuxe-theme-info-wrapper {
            max-width: 1200px;
            margin-top: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .aqualuxe-theme-info-header {
            display: flex;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        .aqualuxe-theme-screenshot {
            width: 200px;
            height: auto;
            margin-right: 20px;
            border: 1px solid #ddd;
        }
        .aqualuxe-theme-info-header-content {
            flex: 1;
        }
        .aqualuxe-theme-info-content {
            padding: 20px;
        }
        .aqualuxe-theme-info-content ul {
            list-style: disc;
            margin-left: 20px;
        }
        .aqualuxe-theme-info-content .button {
            margin-right: 10px;
        }
    </style>
    <?php
}