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

/**
 * Define theme constants
 */
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_THEME_DIR', get_template_directory());
define('AQUALUXE_THEME_URI', get_template_directory_uri());
define('AQUALUXE_CHILD_DIR', get_stylesheet_directory());
define('AQUALUXE_CHILD_URI', get_stylesheet_directory_uri());

/**
 * Theme setup and initialization
 */
if (!function_exists('aqualuxe_setup')) {
    function aqualuxe_setup() {
        // Make theme available for translation
        load_theme_textdomain('aqualuxe', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');

        // Let WordPress manage the document title
        add_theme_support('title-tag');

        // Enable support for Post Thumbnails on posts and pages
        add_theme_support('post-thumbnails');

        // Add custom image sizes
        add_image_size('aqualuxe-hero', 1920, 800, true);
        add_image_size('aqualuxe-product-large', 800, 600, true);
        add_image_size('aqualuxe-product-medium', 400, 300, true);
        add_image_size('aqualuxe-blog-large', 800, 450, true);

        // Register navigation menus
        register_nav_menus(array(
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'mobile' => esc_html__('Mobile Menu', 'aqualuxe'),
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

        // Add theme support for selective refresh for widgets
        add_theme_support('customize-selective-refresh-widgets');

        // Add support for custom logo
        add_theme_support('custom-logo', array(
            'height'      => 100,
            'width'       => 300,
            'flex-width'  => true,
            'flex-height' => true,
        ));

        // Add support for custom header
        add_theme_support('custom-header', array(
            'default-image'      => '',
            'default-text-color' => '000000',
            'width'              => 1920,
            'height'             => 800,
            'flex-width'         => true,
            'flex-height'        => true,
        ));

        // Add support for custom background
        add_theme_support('custom-background', array(
            'default-color' => 'ffffff',
        ));

        // Add support for WooCommerce
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');

        // Add support for Gutenberg wide images
        add_theme_support('align-wide');

        // Add support for editor color palette
        add_theme_support('editor-color-palette', array(
            array(
                'name'  => esc_html__('Primary Color', 'aqualuxe'),
                'slug'  => 'primary',
                'color' => '#0066cc',
            ),
            array(
                'name'  => esc_html__('Secondary Color', 'aqualuxe'),
                'slug'  => 'secondary',
                'color' => '#14b8a6',
            ),
            array(
                'name'  => esc_html__('Accent Color', 'aqualuxe'),
                'slug'  => 'accent',
                'color' => '#f97316',
            ),
        ));

        // Add support for responsive embeds
        add_theme_support('responsive-embeds');
    }
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
 * Enqueue scripts and styles
 */
function aqualuxe_scripts() {
    // Enqueue parent theme styles if this is a child theme
    if (is_child_theme()) {
        wp_enqueue_style('aqualuxe-parent-style', get_template_directory_uri() . '/style.css', array(), AQUALUXE_VERSION);
    }

    // Theme stylesheet
    wp_enqueue_style('aqualuxe-style', get_stylesheet_uri(), array(), AQUALUXE_VERSION);

    // Google Fonts
    wp_enqueue_style('aqualuxe-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap', array(), null);

    // Theme JavaScript
    wp_enqueue_script('aqualuxe-navigation', get_theme_file_uri('/assets/js/navigation.js'), array('jquery'), AQUALUXE_VERSION, true);
    wp_enqueue_script('aqualuxe-main', get_theme_file_uri('/assets/js/main.js'), array('jquery'), AQUALUXE_VERSION, true);

    // Localize script for AJAX
    wp_localize_script('aqualuxe-main', 'aqualuxe_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('aqualuxe_nonce'),
    ));

    // Load comment reply script on single posts with comments
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // WooCommerce specific scripts
    if (class_exists('WooCommerce')) {
        if (is_shop() || is_product_category() || is_product_tag()) {
            wp_enqueue_script('aqualuxe-shop', get_theme_file_uri('/assets/js/shop.js'), array('jquery'), AQUALUXE_VERSION, true);
        }
        
        if (is_product()) {
            wp_enqueue_script('aqualuxe-product', get_theme_file_uri('/assets/js/product.js'), array('jquery'), AQUALUXE_VERSION, true);
        }
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_scripts');

/**
 * Enqueue admin styles and scripts
 */
function aqualuxe_admin_scripts($hook) {
    if ('appearance_page_theme-options' === $hook) {
        wp_enqueue_style('aqualuxe-admin', get_theme_file_uri('/assets/css/admin.css'), array(), AQUALUXE_VERSION);
        wp_enqueue_script('aqualuxe-admin', get_theme_file_uri('/assets/js/admin.js'), array('jquery'), AQUALUXE_VERSION, true);
    }
}
add_action('admin_enqueue_scripts', 'aqualuxe_admin_scripts');

/**
 * Register widget areas
 */
function aqualuxe_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Main Sidebar', 'aqualuxe'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here to appear in your sidebar.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Shop Sidebar', 'aqualuxe'),
        'id'            => 'sidebar-shop',
        'description'   => esc_html__('Add widgets here to appear in your shop sidebar.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    // Footer widget areas
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar(array(
            'name'          => sprintf(esc_html__('Footer Widget %d', 'aqualuxe'), $i),
            'id'            => 'footer-' . $i,
            'description'   => sprintf(esc_html__('Add widgets here to appear in footer column %d.', 'aqualuxe'), $i),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ));
    }
}
add_action('widgets_init', 'aqualuxe_widgets_init');

/**
 * Include theme functions and classes
 */
require_once get_theme_file_path('/inc/class-aqualuxe-theme.php');
require_once get_theme_file_path('/inc/class-aqualuxe-customizer.php');
require_once get_theme_file_path('/inc/class-aqualuxe-walker-nav-menu.php');
require_once get_theme_file_path('/inc/woocommerce-functions.php');
require_once get_theme_file_path('/inc/template-functions.php');
require_once get_theme_file_path('/inc/template-tags.php');
require_once get_theme_file_path('/inc/customizer.php');
require_once get_theme_file_path('/inc/jetpack.php');

/**
 * Initialize theme
 */
if (class_exists('AquaLuxe_Theme')) {
    new AquaLuxe_Theme();
}

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
 * Add custom body classes
 */
function aqualuxe_body_classes($classes) {
    // Adds a class of hfeed to non-singular pages
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar present
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }

    // Add class for WooCommerce pages
    if (class_exists('WooCommerce')) {
        if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
            $classes[] = 'woocommerce-page';
        }
    }

    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments
 */
function aqualuxe_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'aqualuxe_pingback_header');

/**
 * Security enhancements
 */

// Remove WordPress version from head
remove_action('wp_head', 'wp_generator');

// Disable XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

// Remove unnecessary head tags
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');

/**
 * Performance optimizations
 */

// Remove emoji scripts and styles
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');

// Disable embeds
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');

/**
 * Custom logo fallback
 */
function aqualuxe_custom_logo() {
    if (has_custom_logo()) {
        the_custom_logo();
    } else {
        echo '<h1 class="site-title"><a href="' . esc_url(home_url('/')) . '" rel="home">' . get_bloginfo('name') . '</a></h1>';
        $description = get_bloginfo('description', 'display');
        if ($description || is_customize_preview()) {
            echo '<p class="site-description">' . $description . '</p>';
        }
    }
}

/**
 * Schema markup for better SEO
 */
function aqualuxe_schema_markup() {
    $schema = '';
    
    if (is_single()) {
        $schema = 'itemscope itemtype="http://schema.org/Article"';
    } elseif (is_page()) {
        $schema = 'itemscope itemtype="http://schema.org/WebPage"';
    } elseif (is_author()) {
        $schema = 'itemscope itemtype="http://schema.org/ProfilePage"';
    } elseif (is_search()) {
        $schema = 'itemscope itemtype="http://schema.org/SearchResultsPage"';
    }
    
    return $schema;
}

/**
 * Add structured data for products
 */
function aqualuxe_product_structured_data() {
    if (!is_product()) {
        return;
    }
    
    global $product;
    
    $structured_data = array(
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->get_name(),
        'description' => wp_strip_all_tags($product->get_short_description()),
        'sku' => $product->get_sku(),
        'offers' => array(
            '@type' => 'Offer',
            'price' => $product->get_price(),
            'priceCurrency' => get_woocommerce_currency(),
            'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
        ),
    );
    
    if ($product->get_image_id()) {
        $structured_data['image'] = wp_get_attachment_url($product->get_image_id());
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode($structured_data) . '</script>';
}
add_action('wp_head', 'aqualuxe_product_structured_data');

// /**
//  * Custom breadcrumbs
//  */
// function aqualuxe_breadcrumbs() {
//     if (is_front_page()) {
//         return;
//     }
    
//     $separator = ' / ';
//     $home_title = esc_html__('Home', 'aqualuxe');
    
//     echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumb Navigation', 'aqualuxe') . '">';
//     echo '<a href="' . esc_url(home_url('/')) . '">' . $home_title . '</a>' . $separator;
    
//     if (is_category() || is_single()) {
//         if (is_single()) {
//             $category = get_the_category();
//             if ($category) {
//                 echo '<a href="' . esc_url(get_category_link($category[0]->term_id)) . '">' . esc_html($category[0]->cat_name) . '</a>' . $separator;
//             }
//             echo '<span>' . get_the_title() . '</span>';
//         } else {
//             echo '<span>' . single_cat_title('', false) . '</span>';
//         }
//     } elseif (is_page()) {
//         echo '<span>' . get_the_title() . '</span>';
//     } elseif (is_search()) {
//         echo '<span>' . sprintf(esc_html__('Search Results for "%s"', 'aqualuxe'), get_search_query()) . '</span>';
//     } elseif (is_404()) {
//         echo '<span>' . esc_html__('404 Error', 'aqualuxe') . '</span>';
//     }
    
//     echo '</nav>';
// }

/**
 * AJAX handler for quick view
 */
function aqualuxe_product_quick_view() {
    check_ajax_referer('aqualuxe_nonce', 'nonce');
    
    $product_id = intval($_POST['product_id']);
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_die();
    }
    
    wc_get_template_part('content', 'single-product-quick-view');
    wp_die();
}
add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_product_quick_view');
add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_product_quick_view');

/**
 * Optimize images for better performance
 */
function aqualuxe_optimize_images($attr, $attachment, $size) {
    $attr['loading'] = 'lazy';
    $attr['decoding'] = 'async';
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'aqualuxe_optimize_images', 10, 3);

/**
 * Add theme support for WooCommerce 3.0+ features
 */
function aqualuxe_woocommerce_support() {
    add_theme_support('woocommerce', array(
        'thumbnail_image_width' => 300,
        'single_image_width'    => 600,
        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 2,
            'max_rows'        => 8,
            'default_columns' => 3,
            'min_columns'     => 2,
            'max_columns'     => 5,
        ),
    ));
    
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_support');

/**
 * Custom template hierarchy for better organization
 */
function aqualuxe_template_hierarchy($template) {
    if (is_home() && file_exists(get_stylesheet_directory() . '/templates/blog.php')) {
        return get_stylesheet_directory() . '/templates/blog.php';
    }
    return $template;
}
add_filter('template_include', 'aqualuxe_template_hierarchy');