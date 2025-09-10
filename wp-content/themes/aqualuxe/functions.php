<?php
/**
 * AquaLuxe Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 */

// Define theme constants
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_DIR', __DIR__ );
define( 'AQUALUXE_URL', get_stylesheet_directory_uri() );
define( 'AQUALUXE_APP_DIR', AQUALUXE_DIR . '/app' );

// Bootstrap the application
require_once AQUALUXE_APP_DIR . '/bootstrap/app.php';

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts() {
    // Get asset manifest for versioning
    $manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : array();

    // Helper function to get versioned asset path
    $get_asset_path = function($asset) use ($manifest) {
        return isset($manifest[$asset]) ? $manifest[$asset] : $asset;
    };

    // Main stylesheet
    $css_path = $get_asset_path('/css/app.css');
    wp_enqueue_style(
        'aqualuxe-main-style',
        get_template_directory_uri() . '/assets/dist' . $css_path,
        array(),
        AQUALUXE_VERSION
    );

    // Main JavaScript
    $js_path = $get_asset_path('/js/app.js');
    wp_enqueue_script(
        'aqualuxe-main-script',
        get_template_directory_uri() . '/assets/dist' . $js_path,
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );

    // Localize script data
    wp_localize_script('aqualuxe-main-script', 'aqualuxe_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe_nonce'),
        'theme_url' => get_template_directory_uri(),
        'is_rtl' => is_rtl(),
        'is_mobile' => wp_is_mobile(),
        'breakpoints' => array(
            'sm' => 640,
            'md' => 768,
            'lg' => 1024,
            'xl' => 1280,
            '2xl' => 1536
        )
    ));

    // Customizer preview script
    if (is_customize_preview()) {
        $customizer_path = $get_asset_path('/js/customizer.js');
        wp_enqueue_script(
            'aqualuxe-customizer',
            get_template_directory_uri() . '/assets/dist' . $customizer_path,
            array('customize-preview', 'jquery'),
            AQUALUXE_VERSION,
            true
        );
    }

    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Load Google Fonts
    wp_enqueue_style(
        'aqualuxe-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700;800&display=swap',
        array(),
        null
    );
}
add_action('wp_enqueue_scripts', 'aqualuxe_scripts');

// Include advanced enhancements
require_once get_template_directory() . '/inc/cart-enhancements.php';
require_once get_template_directory() . '/inc/search-enhancements.php';
require_once get_template_directory() . '/inc/wishlist-compare.php';

/**
 * Enhanced theme setup
 */
function aqualuxe_enhanced_setup() {
    // Add theme support for WooCommerce features
    add_theme_support('woocommerce', array(
        'thumbnail_image_width' => 300,
        'single_image_width' => 600,
        'product_grid' => array(
            'default_rows' => 3,
            'min_rows' => 2,
            'max_rows' => 8,
            'default_columns' => 4,
            'min_columns' => 2,
            'max_columns' => 5,
        ),
    ));

    // Add support for WooCommerce product gallery features
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // Add custom image sizes for enhanced product display
    add_image_size('aqualuxe-product-thumb', 120, 120, true);
    add_image_size('aqualuxe-product-medium', 300, 300, true);
    add_image_size('aqualuxe-product-large', 600, 600, true);

    // Add theme support for custom header and background
    add_theme_support('custom-header', array(
        'default-image' => '',
        'width' => 1920,
        'height' => 1080,
        'flex-height' => true,
        'flex-width' => true,
    ));

    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
    ));
}
add_action('after_setup_theme', 'aqualuxe_enhanced_setup');

/**
 * Register enhanced widget areas
 */
function aqualuxe_enhanced_widgets_init() {
    // Shop sidebar
    register_sidebar(array(
        'name' => esc_html__('Shop Sidebar', 'aqualuxe'),
        'id' => 'shop-sidebar',
        'description' => esc_html__('Add widgets here for shop pages.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s bg-white rounded-lg shadow-sm p-6 mb-6">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="pb-2 mb-4 text-lg font-semibold text-gray-900 border-b border-gray-200 widget-title">',
        'after_title' => '</h3>',
    ));

    // Product filters widget area
    register_sidebar(array(
        'name' => esc_html__('Product Filters', 'aqualuxe'),
        'id' => 'product-filters',
        'description' => esc_html__('Add filter widgets for product pages.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="filter-widget %2$s mb-4">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="mb-2 text-sm font-medium tracking-wide text-gray-900 uppercase filter-title">',
        'after_title' => '</h4>',
    ));

    // Footer columns
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar(array(
            'name' => sprintf(esc_html__('Footer Column %d', 'aqualuxe'), $i),
            'id' => 'footer-' . $i,
            'description' => sprintf(esc_html__('Add widgets here for footer column %d.', 'aqualuxe'), $i),
            'before_widget' => '<div id="%1$s" class="widget %2$s mb-6">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="mb-4 text-lg font-semibold text-white widget-title">',
            'after_title' => '</h3>',
        ));
    }
}
add_action('widgets_init', 'aqualuxe_enhanced_widgets_init');

/**
 * Enhanced navigation menus
 */
function aqualuxe_enhanced_menus() {
    register_nav_menus(array(
        'primary' => esc_html__('Primary Navigation', 'aqualuxe'),
        'footer' => esc_html__('Footer Navigation', 'aqualuxe'),
        'account' => esc_html__('Account Navigation', 'aqualuxe'),
        'mobile' => esc_html__('Mobile Navigation', 'aqualuxe'),
    ));
}
add_action('init', 'aqualuxe_enhanced_menus');

/**
 * Add enhanced body classes
 */
function aqualuxe_enhanced_body_classes($classes) {
    global $post;

    // Add page-specific classes
    if (is_woocommerce()) {
        $classes[] = 'woocommerce-page';

        if (is_shop()) {
            $classes[] = 'shop-page';
        } elseif (is_product()) {
            $classes[] = 'single-product-page';
        } elseif (is_cart()) {
            $classes[] = 'cart-page';
        } elseif (is_checkout()) {
            $classes[] = 'checkout-page';
        }
    }

    // Add device-specific classes
    if (wp_is_mobile()) {
        $classes[] = 'is-mobile';
    }

    // Add RTL class if needed
    if (is_rtl()) {
        $classes[] = 'rtl';
    }

    return $classes;
}
add_filter('body_class', 'aqualuxe_enhanced_body_classes');

/**
 * Enhanced excerpt length and more text
 */
function aqualuxe_enhanced_excerpt_length($length) {
    return is_admin() ? $length : 25;
}
add_filter('excerpt_length', 'aqualuxe_enhanced_excerpt_length');

function aqualuxe_enhanced_excerpt_more($more) {
    return is_admin() ? $more : '...';
}
add_filter('excerpt_more', 'aqualuxe_enhanced_excerpt_more');

/**
 * Add enhanced meta tags for better SEO
 */
function aqualuxe_enhanced_meta_tags() {
    if (is_product()) {
        global $product;

        // Open Graph tags for products
        echo '<meta property="og:type" content="product" />' . "\n";
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr(wp_strip_all_tags($product->get_short_description())) . '" />' . "\n";

        if ($product->get_image_id()) {
            $image_url = wp_get_attachment_image_url($product->get_image_id(), 'large');
            echo '<meta property="og:image" content="' . esc_url($image_url) . '" />' . "\n";
        }

        // Product-specific meta tags
        echo '<meta property="product:price:amount" content="' . esc_attr($product->get_price()) . '" />' . "\n";
        echo '<meta property="product:price:currency" content="' . esc_attr(get_woocommerce_currency()) . '" />' . "\n";
        echo '<meta property="product:availability" content="' . ($product->is_in_stock() ? 'in stock' : 'out of stock') . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_enhanced_meta_tags');

/**
 * Enhanced search functionality
 */
function aqualuxe_enhanced_search_query($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        // Only search in products for shop searches
        if (isset($_GET['post_type']) && $_GET['post_type'] === 'product') {
            $query->set('post_type', array('product'));

            // Add meta query for product visibility
            $meta_query = array(
                array(
                    'key' => '_visibility',
                    'value' => array('catalog', 'visible'),
                    'compare' => 'IN'
                )
            );

            $query->set('meta_query', $meta_query);
        }
    }
}
add_action('pre_get_posts', 'aqualuxe_enhanced_search_query');

/**
 * Enhanced admin customizations
 */
function aqualuxe_enhanced_admin_init() {
    // Add custom admin styles
    add_action('admin_head', function() {
        echo '<style>
            .aqualuxe-admin-notice {
                border-left: 4px solid #0891b2;
                background: #f0f9ff;
            }
            .aqualuxe-admin-notice .notice-title {
                color: #0891b2;
                font-weight: 600;
            }
        </style>';
    });
}
add_action('admin_init', 'aqualuxe_enhanced_admin_init');
