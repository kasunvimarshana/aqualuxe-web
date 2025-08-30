# AquaLuxe: Premium WordPress + WooCommerce Theme

I'll create a comprehensive, production-ready WordPress theme with full WooCommerce integration. Let's build this step by step, following all best practices.

## 1. Theme Structure

First, let's establish the file structure:

```
aqualuxe/
├── style.css
├── functions.php
├── screenshot.png
├── readme.txt
├── index.php
├── header.php
├── footer.php
├── single.php
├── page.php
├── archive.php
├── search.php
├── 404.php
├── comments.php
├── sidebar.php
├── woocommerce/
│   ├── archive-product.php
│   ├── single-product.php
│   ├── content-product.php
│   ├── content-single-product.php
│   ├── cart/
│   │   ├── cart.php
│   │   └── mini-cart.php
│   ├── checkout/
│   │   ├── form-checkout.php
│   │   └── payment.php
│   ├── myaccount/
│   │   ├── my-account.php
│   │   └── navigation.php
│   └── global/
│       ├── breadcrumb.php
│       └── pagination.php
├── assets/
│   ├── css/
│   │   ├── main.css
│   │   ├── woocommerce.css
│   │   └── responsive.css
│   ├── js/
│   │   ├── main.js
│   │   ├── woocommerce.js
│   │   └── ajax-cart.js
│   ├── images/
│   │   ├── logo.png
│   │   ├── icons/
│   │   └── placeholders/
│   └── fonts/
├── inc/
│   ├── class-aqualuxe-core.php
│   ├── class-aqualuxe-woocommerce.php
│   ├── class-aqualuxe-customizer.php
│   ├── class-aqualuxe-megamenu.php
│   ├── template-functions.php
│   ├── template-hooks.php
│   ├── shortcodes.php
│   └── widgets.php
└── templates/
    ├── parts/
    │   ├── header-top.php
    │   ├── main-navigation.php
    │   ├── page-header.php
    │   └── footer-widgets.php
    └── component-templates/
        ├── product-grid.php
        └── product-list.php
```

## 2. Core Theme Files

### style.css

```css
/*
Theme Name: AquaLuxe
Theme URI: https://example.com/aqualuxe-theme
Author: AquaLuxe Team
Author URI: https://example.com
Description: A premium, production-ready WordPress theme with full WooCommerce integration. Optimized for speed, SEO, and mobile responsiveness.
Version: 1.0.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: aqualuxe
Domain Path: /languages
Tags: e-commerce, woocommerce, responsive, clean, modern, multipurpose
*/

/* Reset and Base Styles */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  line-height: 1.6;
  color: #333;
  background-color: #fff;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

a {
  color: #0066cc;
  text-decoration: none;
  transition: color 0.3s ease;
}

a:hover {
  color: #004499;
}

img {
  max-width: 100%;
  height: auto;
}

/* Layout */
.container {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.row {
  display: flex;
  flex-wrap: wrap;
  margin: 0 -15px;
}

.col-md-6 {
  width: 50%;
  padding: 0 15px;
}

.col-md-4 {
  width: 33.333%;
  padding: 0 15px;
}

.col-md-3 {
  width: 25%;
  padding: 0 15px;
}

.col-md-8 {
  width: 66.666%;
  padding: 0 15px;
}

.col-md-9 {
  width: 75%;
  padding: 0 15px;
}

/* Typography */
h1,
h2,
h3,
h4,
h5,
h6 {
  margin-bottom: 20px;
  font-weight: 600;
  line-height: 1.2;
  color: #222;
}

h1 {
  font-size: 36px;
}

h2 {
  font-size: 30px;
}

h3 {
  font-size: 24px;
}

h4 {
  font-size: 20px;
}

h5 {
  font-size: 18px;
}

h6 {
  font-size: 16px;
}

p {
  margin-bottom: 20px;
}

/* Buttons */
.btn {
  display: inline-block;
  padding: 12px 24px;
  font-weight: 600;
  text-align: center;
  cursor: pointer;
  border: none;
  border-radius: 4px;
  transition: all 0.3s ease;
}

.btn-primary {
  background-color: #0066cc;
  color: white;
}

.btn-primary:hover {
  background-color: #004499;
  color: white;
}

.btn-secondary {
  background-color: #f8f9fa;
  color: #333;
  border: 1px solid #ddd;
}

.btn-secondary:hover {
  background-color: #e9ecef;
  color: #333;
}

/* Header Styles */
.site-header {
  position: relative;
  background-color: #fff;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.header-top {
  background-color: #f8f9fa;
  padding: 10px 0;
  font-size: 14px;
}

.header-main {
  padding: 20px 0;
}

.site-logo {
  display: block;
  max-width: 200px;
}

.site-navigation {
  display: flex;
  align-items: center;
}

.main-navigation ul {
  display: flex;
  list-style: none;
}

.main-navigation ul li {
  position: relative;
}

.main-navigation ul li a {
  display: block;
  padding: 10px 15px;
  color: #333;
  font-weight: 500;
}

.main-navigation ul li a:hover {
  color: #0066cc;
}

/* Footer Styles */
.site-footer {
  background-color: #222;
  color: #fff;
  padding: 60px 0 30px;
}

.footer-widgets {
  margin-bottom: 40px;
}

.footer-widget h3 {
  color: #fff;
  margin-bottom: 20px;
  font-size: 18px;
}

.footer-widget ul {
  list-style: none;
}

.footer-widget ul li {
  margin-bottom: 10px;
}

.footer-widget ul li a {
  color: #bbb;
}

.footer-widget ul li a:hover {
  color: #fff;
}

.footer-bottom {
  padding-top: 30px;
  border-top: 1px solid #444;
  text-align: center;
  font-size: 14px;
  color: #bbb;
}

/* WooCommerce Styles */
.woocommerce-result-count {
  margin-bottom: 20px;
}

.woocommerce-ordering {
  margin-bottom: 30px;
}

.products {
  display: flex;
  flex-wrap: wrap;
  margin: 0 -15px;
}

.product {
  width: 25%;
  padding: 0 15px 30px;
}

.product .woocommerce-loop-product__title {
  font-size: 16px;
  margin-bottom: 10px;
}

.product .price {
  margin-bottom: 15px;
  font-weight: 600;
}

.product .button {
  width: 100%;
}

/* Responsive Styles */
@media screen and (max-width: 992px) {
  .product {
    width: 33.333%;
  }
}

@media screen and (max-width: 768px) {
  .product {
    width: 50%;
  }

  .col-md-6,
  .col-md-4,
  .col-md-3,
  .col-md-8,
  .col-md-9 {
    width: 100%;
  }
}

@media screen and (max-width: 576px) {
  .product {
    width: 100%;
  }
}
```

### functions.php

```php
<?php
/**
 * AquaLuxe Theme Functions
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Define theme constants.
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_DIR', get_template_directory() );
define( 'AQUALUXE_URI', get_template_directory_uri() );

// Include core theme classes.
require_once AQUALUXE_DIR . '/inc/class-aqualuxe-core.php';
require_once AQUALUXE_DIR . '/inc/class-aqualuxe-woocommerce.php';
require_once AQUALUXE_DIR . '/inc/class-aqualuxe-customizer.php';
require_once AQUALUXE_DIR . '/inc/class-aqualuxe-megamenu.php';

// Include template functions and hooks.
require_once AQUALUXE_DIR . '/inc/template-functions.php';
require_once AQUALUXE_DIR . '/inc/template-hooks.php';
require_once AQUALUXE_DIR . '/inc/shortcodes.php';
require_once AQUALUXE_DIR . '/inc/widgets.php';

// Initialize theme.
if ( ! function_exists( 'aqualuxe_init' ) ) {
    /**
     * Initialize the theme.
     */
    function aqualuxe_init() {
        // Initialize core theme functionality.
        Aqualuxe_Core::get_instance();

        // Initialize WooCommerce integration if WooCommerce is active.
        if ( class_exists( 'WooCommerce' ) ) {
            Aqualuxe_WooCommerce::get_instance();
        }

        // Initialize customizer.
        Aqualuxe_Customizer::get_instance();

        // Initialize megamenu.
        Aqualuxe_Megamenu::get_instance();
    }
}
add_action( 'after_setup_theme', 'aqualuxe_init' );
```

## 3. Core Theme Classes

### inc/class-aqualuxe-core.php

```php
<?php
/**
 * Core Theme Class
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Core Theme Class
 */
class Aqualuxe_Core {

    /**
     * The single instance of the class.
     *
     * @var Aqualuxe_Core
     */
    protected static $_instance = null;

    /**
     * Main Aqualuxe_Core Instance.
     *
     * Ensures only one instance of Aqualuxe_Core is loaded or can be loaded.
     */
    public static function get_instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor.
     */
    public function __construct() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Include required core files.
     */
    public function includes() {
        // Include helper functions.
        require_once AQUALUXE_DIR . '/inc/helpers.php';
    }

    /**
     * Initialize hooks.
     */
    public function init_hooks() {
        // Theme setup.
        add_action( 'after_setup_theme', array( $this, 'setup' ) );

        // Enqueue scripts and styles.
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

        // Register widget areas.
        add_action( 'widgets_init', array( $this, 'widgets_init' ) );

        // Register navigation menus.
        add_action( 'init', array( $this, 'register_menus' ) );

        // Add theme support for various features.
        add_action( 'after_setup_theme', array( $this, 'add_theme_supports' ) );
    }

    /**
     * Theme setup.
     */
    public function setup() {
        // Load text domain.
        load_theme_textdomain( 'aqualuxe', AQUALUXE_DIR . '/languages' );

        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

        // Let WordPress manage the document title.
        add_theme_support( 'title-tag' );

        // Enable support for Post Thumbnails on posts and pages.
        add_theme_support( 'post-thumbnails' );

        // Set post thumbnail size.
        set_post_thumbnail_size( 825, 510, true );

        // Register image sizes.
        add_image_size( 'aqualuxe-thumbnail', 300, 300, true );
        add_image_size( 'aqualuxe-medium', 600, 400, true );
        add_image_size( 'aqualuxe-large', 1000, 700, true );

        // This theme uses wp_nav_menu() in two locations.
        register_nav_menus( array(
            'primary' => esc_html__( 'Primary Menu', 'aqualuxe' ),
            'footer'  => esc_html__( 'Footer Menu', 'aqualuxe' ),
        ) );

        // Switch default core markup for search form, comment form, and comments to output valid HTML5.
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ) );

        // Add theme support for selective refresh for widgets.
        add_theme_support( 'customize-selective-refresh-widgets' );
    }

    /**
     * Enqueue scripts.
     */
    public function enqueue_scripts() {
        // Register main script.
        wp_register_script(
            'aqualuxe-main',
            AQUALUXE_URI . '/assets/js/main.js',
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );

        // Enqueue main script.
        wp_enqueue_script( 'aqualuxe-main' );

        // Localize script.
        wp_localize_script( 'aqualuxe-main', 'aqualuxe_vars', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'aqualuxe_nonce' ),
        ) );

        // Comment reply script.
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }

    /**
     * Enqueue styles.
     */
    public function enqueue_styles() {
        // Register main style.
        wp_register_style(
            'aqualuxe-main',
            AQUALUXE_URI . '/assets/css/main.css',
            array(),
            AQUALUXE_VERSION
        );

        // Enqueue main style.
        wp_enqueue_style( 'aqualuxe-main' );

        // Register responsive style.
        wp_register_style(
            'aqualuxe-responsive',
            AQUALUXE_URI . '/assets/css/responsive.css',
            array( 'aqualuxe-main' ),
            AQUALUXE_VERSION
        );

        // Enqueue responsive style.
        wp_enqueue_style( 'aqualuxe-responsive' );

        // Google Fonts.
        wp_enqueue_style(
            'aqualuxe-google-fonts',
            'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap',
            array(),
            null
        );
    }

    /**
     * Register widget areas.
     */
    public function widgets_init() {
        // Sidebar.
        register_sidebar( array(
            'name'          => esc_html__( 'Sidebar', 'aqualuxe' ),
            'id'            => 'sidebar-1',
            'description'   => esc_html__( 'Add widgets here.', 'aqualuxe' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ) );

        // Footer widget areas.
        for ( $i = 1; $i <= 4; $i++ ) {
            register_sidebar( array(
                'name'          => sprintf( esc_html__( 'Footer %d', 'aqualuxe' ), $i ),
                'id'            => 'footer-' . $i,
                'description'   => sprintf( esc_html__( 'Add widgets to footer column %d.', 'aqualuxe' ), $i ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ) );
        }

        // Shop sidebar.
        if ( class_exists( 'WooCommerce' ) ) {
            register_sidebar( array(
                'name'          => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
                'id'            => 'shop-sidebar',
                'description'   => esc_html__( 'Add widgets here to appear in your shop sidebar.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ) );
        }
    }

    /**
     * Register navigation menus.
     */
    public function register_menus() {
        register_nav_menus( array(
            'primary' => esc_html__( 'Primary Menu', 'aqualuxe' ),
            'mobile'  => esc_html__( 'Mobile Menu', 'aqualuxe' ),
            'footer'  => esc_html__( 'Footer Menu', 'aqualuxe' ),
        ) );
    }

    /**
     * Add theme supports.
     */
    public function add_theme_supports() {
        // Add support for custom logo.
        add_theme_support( 'custom-logo', array(
            'height'      => 60,
            'width'       => 200,
            'flex-height' => true,
            'flex-width'  => true,
            'header-text' => array( 'site-title', 'site-description' ),
        ) );

        // Add support for custom header.
        add_theme_support( 'custom-header', array(
            'default-image'          => '',
            'default-text-color'     => '333333',
            'width'                  => 1920,
            'height'                 => 400,
            'flex-height'            => true,
            'flex-width'             => true,
            'wp-head-callback'       => 'aqualuxe_header_style',
            'admin-head-callback'    => 'aqualuxe_admin_header_style',
            'admin-preview-callback' => 'aqualuxe_admin_header_image',
        ) );

        // Add support for custom background.
        add_theme_support( 'custom-background', array(
            'default-color' => 'ffffff',
        ) );

        // Add support for Gutenberg.
        add_theme_support( 'align-wide' );
        add_theme_support( 'wp-block-styles' );
        add_theme_support( 'editor-styles' );
        add_theme_support( 'responsive-embeds' );
    }
}
```

### inc/class-aqualuxe-woocommerce.php

```php
<?php
/**
 * WooCommerce Integration Class
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce Integration Class
 */
class Aqualuxe_WooCommerce {

    /**
     * The single instance of the class.
     *
     * @var Aqualuxe_WooCommerce
     */
    protected static $_instance = null;

    /**
     * Main Aqualuxe_WooCommerce Instance.
     *
     * Ensures only one instance of Aqualuxe_WooCommerce is loaded or can be loaded.
     */
    public static function get_instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor.
     */
    public function __construct() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Include required WooCommerce files.
     */
    public function includes() {
        // WooCommerce template functions.
        require_once AQUALUXE_DIR . '/inc/woocommerce/template-functions.php';
        require_once AQUALUXE_DIR . '/inc/woocommerce/template-hooks.php';
        require_once AQUALUXE_DIR . '/inc/woocommerce/class-aqualuxe-wc-ajax.php';
    }

    /**
     * Initialize hooks.
     */
    public function init_hooks() {
        // Declare WooCommerce support.
        add_action( 'after_setup_theme', array( $this, 'woocommerce_support' ) );

        // WooCommerce scripts and styles.
        add_action( 'wp_enqueue_scripts', array( $this, 'woocommerce_scripts' ) );

        // WooCommerce template overrides.
        add_filter( 'woocommerce_template_path', array( $this, 'template_path' ) );

        // Remove default WooCommerce wrappers.
        remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
        remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

        // Add custom wrappers.
        add_action( 'woocommerce_before_main_content', array( $this, 'output_content_wrapper' ), 10 );
        add_action( 'woocommerce_after_main_content', array( $this, 'output_content_wrapper_end' ), 10 );

        // Product columns.
        add_filter( 'loop_shop_columns', array( $this, 'loop_columns' ) );

        // Products per page.
        add_filter( 'loop_shop_per_page', array( $this, 'products_per_page' ), 20 );

        // Related products.
        add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_args' ) );

        // Cross sells.
        add_filter( 'woocommerce_cross_sells_total', array( $this, 'cross_sells_total' ) );
        add_filter( 'woocommerce_cross_sells_columns', array( $this, 'cross_sells_columns' ) );

        // Product gallery.
        add_action( 'after_setup_theme', array( $this, 'product_gallery' ) );

        // Add to cart fragments.
        add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_link_fragment' ) );

        // Mini cart.
        add_filter( 'woocommerce_widget_cart_shopping_cart_buttons', array( $this, 'mini_cart_buttons' ) );
    }

    /**
     * Declare WooCommerce support.
     */
    public function woocommerce_support() {
        add_theme_support( 'woocommerce', array(
            'thumbnail_image_width' => 300,
            'gallery_thumbnail_image_width' => 100,
            'single_image_width' => 600,
            'product_grid'          => array(
                'default_rows'    => 3,
                'min_rows'        => 1,
                'max_rows'        => 6,
                'default_columns' => 4,
                'min_columns'     => 1,
                'max_columns'     => 6,
            ),
        ) );

        // Add support for new WooCommerce features.
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
    }

    /**
     * WooCommerce scripts and styles.
     */
    public function woocommerce_scripts() {
        // Register WooCommerce styles.
        wp_register_style(
            'aqualuxe-woocommerce',
            AQUALUXE_URI . '/assets/css/woocommerce.css',
            array(),
            AQUALUXE_VERSION
        );

        // Enqueue WooCommerce styles.
        wp_enqueue_style( 'aqualuxe-woocommerce' );

        // Register WooCommerce scripts.
        wp_register_script(
            'aqualuxe-woocommerce',
            AQUALUXE_URI . '/assets/js/woocommerce.js',
            array( 'jquery', 'wc-add-to-cart' ),
            AQUALUXE_VERSION,
            true
        );

        // Enqueue WooCommerce scripts.
        wp_enqueue_script( 'aqualuxe-woocommerce' );

        // AJAX add to cart script.
        wp_register_script(
            'aqualuxe-ajax-cart',
            AQUALUXE_URI . '/assets/js/ajax-cart.js',
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );

        // Enqueue AJAX add to cart script on shop and product pages.
        if ( is_shop() || is_product_category() || is_product_tag() || is_product() ) {
            wp_enqueue_script( 'aqualuxe-ajax-cart' );

            // Localize AJAX cart script.
            wp_localize_script( 'aqualuxe-ajax-cart', 'aqualuxe_ajax_cart', array(
                'ajax_url'   => admin_url( 'admin-ajax.php' ),
                'cart_url'   => wc_get_cart_url(),
                'cart_redirect_after_add' => get_option( 'woocommerce_cart_redirect_after_add' ) === 'yes' ? 'yes' : 'no',
            ) );
        }
    }

    /**
     * WooCommerce template path.
     *
     * @param string $template_path Template path.
     * @return string
     */
    public function template_path( $template_path ) {
        return AQUALUXE_DIR . '/woocommerce/';
    }

    /**
     * Output content wrapper.
     */
    public function output_content_wrapper() {
        echo '<div class="container"><div class="row"><div id="primary" class="content-area col-md-9">';
    }

    /**
     * Output content wrapper end.
     */
    public function output_content_wrapper_end() {
        echo '</div>';

        // Get shop sidebar if it's active.
        if ( is_active_sidebar( 'shop-sidebar' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
            echo '<div id="secondary" class="widget-area col-md-3" role="complementary">';
            dynamic_sidebar( 'shop-sidebar' );
            echo '</div>';
        }

        echo '</div></div>';
    }

    /**
     * Loop columns.
     *
     * @return int
     */
    public function loop_columns() {
        return 4;
    }

    /**
     * Products per page.
     *
     * @param int $per_page Products per page.
     * @return int
     */
    public function products_per_page( $per_page ) {
        return 12;
    }

    /**
     * Related products args.
     *
     * @param array $args Related products args.
     * @return array
     */
    public function related_products_args( $args ) {
        $args['posts_per_page'] = 4;
        $args['columns'] = 4;
        return $args;
    }

    /**
     * Cross sells total.
     *
     * @return int
     */
    public function cross_sells_total() {
        return 4;
    }

    /**
     * Cross sells columns.
     *
     * @return int
     */
    public function cross_sells_columns() {
        return 4;
    }

    /**
     * Product gallery.
     */
    public function product_gallery() {
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
    }

    /**
     * Cart link fragment.
     *
     * @param array $fragments Fragments.
     * @return array
     */
    public function cart_link_fragment( $fragments ) {
        ob_start();
        aqualuxe_cart_link();
        $fragments['a.cart-link'] = ob_get_clean();

        return $fragments;
    }

    /**
     * Mini cart buttons.
     *
     * @param array $buttons Buttons.
     * @return array
     */
    public function mini_cart_buttons( $buttons ) {
        // Remove view cart button.
        unset( $buttons['view_cart'] );

        // Change checkout button text.
        $buttons['checkout']['text'] = esc_html__( 'Checkout', 'aqualuxe' );

        return $buttons;
    }
}
```

### inc/class-aqualuxe-customizer.php

```php
<?php
/**
 * Theme Customizer Class
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Theme Customizer Class
 */
class Aqualuxe_Customizer {

    /**
     * The single instance of the class.
     *
     * @var Aqualuxe_Customizer
     */
    protected static $_instance = null;

    /**
     * Main Aqualuxe_Customizer Instance.
     *
     * Ensures only one instance of Aqualuxe_Customizer is loaded or can be loaded.
     */
    public static function get_instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'customize_register', array( $this, 'customize_register' ) );
        add_action( 'customize_preview_init', array( $this, 'customize_preview_js' ) );
    }

    /**
     * Add postMessage support for site title and description for the Theme Customizer.
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function customize_register( $wp_customize ) {
        // Add custom sections.
        $this->add_sections( $wp_customize );

        // Add custom settings.
        $this->add_settings( $wp_customize );

        // Add custom controls.
        $this->add_controls( $wp_customize );
    }

    /**
     * Add custom sections.
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function add_sections( $wp_customize ) {
        // General section.
        $wp_customize->add_section( 'aqualuxe_general', array(
            'title'    => esc_html__( 'General Settings', 'aqualuxe' ),
            'priority' => 10,
        ) );

        // Header section.
        $wp_customize->add_section( 'aqualuxe_header', array(
            'title'    => esc_html__( 'Header Settings', 'aqualuxe' ),
            'priority' => 20,
        ) );

        // Footer section.
        $wp_customize->add_section( 'aqualuxe_footer', array(
            'title'    => esc_html__( 'Footer Settings', 'aqualuxe' ),
            'priority' => 30,
        ) );

        // Typography section.
        $wp_customize->add_section( 'aqualuxe_typography', array(
            'title'    => esc_html__( 'Typography', 'aqualuxe' ),
            'priority' => 40,
        ) );

        // Colors section.
        $wp_customize->add_section( 'aqualuxe_colors', array(
            'title'    => esc_html__( 'Colors', 'aqualuxe' ),
            'priority' => 50,
        ) );

        // WooCommerce section.
        if ( class_exists( 'WooCommerce' ) ) {
            $wp_customize->add_section( 'aqualuxe_woocommerce', array(
                'title'    => esc_html__( 'WooCommerce Settings', 'aqualuxe' ),
                'priority' => 60,
            ) );
        }
    }

    /**
     * Add custom settings.
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function add_settings( $wp_customize ) {
        // General settings.
        $wp_customize->add_setting( 'aqualuxe_site_layout', array(
            'default'           => 'wide',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        ) );

        // Header settings.
        $wp_customize->add_setting( 'aqualuxe_header_style', array(
            'default'           => 'standard',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_setting( 'aqualuxe_sticky_header', array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        ) );

        // Footer settings.
        $wp_customize->add_setting( 'aqualuxe_footer_widgets', array(
            'default'           => 4,
            'sanitize_callback' => 'aqualuxe_sanitize_number',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_setting( 'aqualuxe_footer_copyright', array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'refresh',
        ) );

        // Typography settings.
        $wp_customize->add_setting( 'aqualuxe_body_font', array(
            'default'           => 'Inter',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_setting( 'aqualuxe_heading_font', array(
            'default'           => 'Inter',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_setting( 'aqualuxe_body_font_size', array(
            'default'           => 16,
            'sanitize_callback' => 'aqualuxe_sanitize_number',
            'transport'         => 'refresh',
        ) );

        // Color settings.
        $wp_customize->add_setting( 'aqualuxe_primary_color', array(
            'default'           => '#0066cc',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_setting( 'aqualuxe_secondary_color', array(
            'default'           => '#f8f9fa',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_setting( 'aqualuxe_text_color', array(
            'default'           => '#333333',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_setting( 'aqualuxe_link_color', array(
            'default'           => '#0066cc',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        ) );

        // WooCommerce settings.
        if ( class_exists( 'WooCommerce' ) ) {
            $wp_customize->add_setting( 'aqualuxe_shop_columns', array(
                'default'           => 4,
                'sanitize_callback' => 'aqualuxe_sanitize_number',
                'transport'         => 'refresh',
            ) );

            $wp_customize->add_setting( 'aqualuxe_shop_products_per_page', array(
                'default'           => 12,
                'sanitize_callback' => 'aqualuxe_sanitize_number',
                'transport'         => 'refresh',
            ) );

            $wp_customize->add_setting( 'aqualuxe_product_quick_view', array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ) );

            $wp_customize->add_setting( 'aqualuxe_ajax_add_to_cart', array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ) );
        }
    }

    /**
     * Add custom controls.
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function add_controls( $wp_customize ) {
        // General controls.
        $wp_customize->add_control( 'aqualuxe_site_layout', array(
            'label'    => esc_html__( 'Site Layout', 'aqualuxe' ),
            'section'  => 'aqualuxe_general',
            'type'     => 'select',
            'choices'  => array(
                'wide' => esc_html__( 'Wide', 'aqualuxe' ),
                'boxed' => esc_html__( 'Boxed', 'aqualuxe' ),
            ),
        ) );

        // Header controls.
        $wp_customize->add_control( 'aqualuxe_header_style', array(
            'label'    => esc_html__( 'Header Style', 'aqualuxe' ),
            'section'  => 'aqualuxe_header',
            'type'     => 'select',
            'choices'  => array(
                'standard' => esc_html__( 'Standard', 'aqualuxe' ),
                'minimal'  => esc_html__( 'Minimal', 'aqualuxe' ),
                'centered' => esc_html__( 'Centered', 'aqualuxe' ),
            ),
        ) );

        $wp_customize->add_control( 'aqualuxe_sticky_header', array(
            'label'    => esc_html__( 'Sticky Header', 'aqualuxe' ),
            'section'  => 'aqualuxe_header',
            'type'     => 'checkbox',
        ) );

        // Footer controls.
        $wp_customize->add_control( 'aqualuxe_footer_widgets', array(
            'label'       => esc_html__( 'Footer Widget Columns', 'aqualuxe' ),
            'section'     => 'aqualuxe_footer',
            'type'        => 'number',
            'input_attrs' => array(
                'min' => 1,
                'max' => 4,
            ),
        ) );

        $wp_customize->add_control( 'aqualuxe_footer_copyright', array(
            'label'   => esc_html__( 'Footer Copyright Text', 'aqualuxe' ),
            'section' => 'aqualuxe_footer',
            'type'    => 'textarea',
        ) );

        // Typography controls.
        $wp_customize->add_control( 'aqualuxe_body_font', array(
            'label'   => esc_html__( 'Body Font', 'aqualuxe' ),
            'section' => 'aqualuxe_typography',
            'type'    => 'text',
        ) );

        $wp_customize->add_control( 'aqualuxe_heading_font', array(
            'label'   => esc_html__( 'Heading Font', 'aqualuxe' ),
            'section' => 'aqualuxe_typography',
            'type'    => 'text',
        ) );

        $wp_customize->add_control( 'aqualuxe_body_font_size', array(
            'label'       => esc_html__( 'Body Font Size (px)', 'aqualuxe' ),
            'section'     => 'aqualuxe_typography',
            'type'        => 'number',
            'input_attrs' => array(
                'min' => 12,
                'max' => 24,
            ),
        ) );

        // Color controls.
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_primary_color', array(
            'label'   => esc_html__( 'Primary Color', 'aqualuxe' ),
            'section' => 'aqualuxe_colors',
        ) ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_secondary_color', array(
            'label'   => esc_html__( 'Secondary Color', 'aqualuxe' ),
            'section' => 'aqualuxe_colors',
        ) ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_text_color', array(
            'label'   => esc_html__( 'Text Color', 'aqualuxe' ),
            'section' => 'aqualuxe_colors',
        ) ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_link_color', array(
            'label'   => esc_html__( 'Link Color', 'aqualuxe' ),
            'section' => 'aqualuxe_colors',
        ) ) );

        // WooCommerce controls.
        if ( class_exists( 'WooCommerce' ) ) {
            $wp_customize->add_control( 'aqualuxe_shop_columns', array(
                'label'       => esc_html__( 'Shop Columns', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce',
                'type'        => 'number',
                'input_attrs' => array(
                    'min' => 1,
                    'max' => 6,
                ),
            ) );

            $wp_customize->add_control( 'aqualuxe_shop_products_per_page', array(
                'label'       => esc_html__( 'Products Per Page', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce',
                'type'        => 'number',
                'input_attrs' => array(
                    'min' => 1,
                    'max' => 50,
                ),
            ) );

            $wp_customize->add_control( 'aqualuxe_product_quick_view', array(
                'label'   => esc_html__( 'Enable Product Quick View', 'aqualuxe' ),
                'section' => 'aqualuxe_woocommerce',
                'type'    => 'checkbox',
            ) );

            $wp_customize->add_control( 'aqualuxe_ajax_add_to_cart', array(
                'label'   => esc_html__( 'Enable AJAX Add to Cart', 'aqualuxe' ),
                'section' => 'aqualuxe_woocommerce',
                'type'    => 'checkbox',
            ) );
        }
    }

    /**
     * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
     */
    public function customize_preview_js() {
        wp_enqueue_script(
            'aqualuxe-customizer',
            AQUALUXE_URI . '/assets/js/customizer.js',
            array( 'customize-preview' ),
            AQUALUXE_VERSION,
            true
        );
    }
}
```

### inc/class-aqualuxe-megamenu.php

```php
<?php
/**
 * Mega Menu Class
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Mega Menu Class
 */
class Aqualuxe_Megamenu {

    /**
     * The single instance of the class.
     *
     * @var Aqualuxe_Megamenu
     */
    protected static $_instance = null;

    /**
     * Main Aqualuxe_Megamenu Instance.
     *
     * Ensures only one instance of Aqualuxe_Megamenu is loaded or can be loaded.
     */
    public static function get_instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor.
     */
    public function __construct() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Include required mega menu files.
     */
    public function includes() {
        // Mega menu walker.
        require_once AQUALUXE_DIR . '/inc/megamenu/class-aqualuxe-megamenu-walker.php';
    }

    /**
     * Initialize hooks.
     */
    public function init_hooks() {
        // Add mega menu meta box.
        add_action( 'admin_init', array( $this, 'add_meta_box' ) );

        // Save mega menu meta.
        add_action( 'wp_update_nav_menu_item', array( $this, 'save_meta' ), 10, 3 );

        // Add mega menu custom fields.
        add_filter( 'wp_edit_nav_menu_walker', array( $this, 'edit_nav_menu_walker' ), 10, 2 );

        // Enqueue admin scripts.
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
    }

    /**
     * Add meta box.
     */
    public function add_meta_box() {
        add_meta_box(
            'aqualuxe-megamenu-menu-item',
            esc_html__( 'AquaLuxe Mega Menu Settings', 'aqualuxe' ),
            array( $this, 'meta_box_content' ),
            'nav-menus',
            'side',
            'default'
        );
    }

    /**
     * Meta box content.
     */
    public function meta_box_content() {
        ?>
        <div class="aqualuxe-megamenu-settings">
            <p class="description">
                <?php esc_html_e( 'Select menu items to enable mega menu options.', 'aqualuxe' ); ?>
            </p>
        </div>
        <?php
    }

    /**
     * Save meta.
     *
     * @param int   $menu_id Menu ID.
     * @param int   $menu_item_id Menu item ID.
     * @param array $args Menu item args.
     */
    public function save_meta( $menu_id, $menu_item_id, $args ) {
        // Check nonce.
        if ( ! isset( $_POST['aqualuxe_megamenu_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['aqualuxe_megamenu_nonce'] ), 'aqualuxe_megamenu_nonce' ) ) {
            return;
        }

        // Check user capabilities.
        if ( ! current_user_can( 'edit_theme_options' ) ) {
            return;
        }

        // Save mega menu enabled.
        $megamenu_enabled = isset( $_POST['aqualuxe_megamenu_enabled'][ $menu_item_id ] ) ? 'yes' : 'no';
        update_post_meta( $menu_item_id, '_aqualuxe_megamenu_enabled', $megamenu_enabled );

        // Save mega menu columns.
        $megamenu_columns = isset( $_POST['aqualuxe_megamenu_columns'][ $menu_item_id ] ) ? absint( $_POST['aqualuxe_megamenu_columns'][ $menu_item_id ] ) : 4;
        update_post_meta( $menu_item_id, '_aqualuxe_megamenu_columns', $megamenu_columns );

        // Save mega menu widget area.
        $megamenu_widget_area = isset( $_POST['aqualuxe_megamenu_widget_area'][ $menu_item_id ] ) ? sanitize_text_field( $_POST['aqualuxe_megamenu_widget_area'][ $menu_item_id ] ) : '';
        update_post_meta( $menu_item_id, '_aqualuxe_megamenu_widget_area', $megamenu_widget_area );
    }

    /**
     * Edit nav menu walker.
     *
     * @param string $walker Walker class name.
     * @param int    $menu_id Menu ID.
     * @return string
     */
    public function edit_nav_menu_walker( $walker, $menu_id ) {
        if ( ! class_exists( 'Aqualuxe_Megamenu_Walker' ) ) {
            require_once AQUALUXE_DIR . '/inc/megamenu/class-aqualuxe-megamenu-walker.php';
        }

        return 'Aqualuxe_Megamenu_Walker';
    }

    /**
     * Enqueue admin scripts.
     *
     * @param string $hook Hook.
     */
    public function admin_enqueue_scripts( $hook ) {
        if ( 'nav-menus.php' !== $hook ) {
            return;
        }

        // Enqueue admin styles.
        wp_enqueue_style(
            'aqualuxe-megamenu-admin',
            AQUALUXE_URI . '/assets/css/admin-megamenu.css',
            array(),
            AQUALUXE_VERSION
        );

        // Enqueue admin scripts.
        wp_enqueue_script(
            'aqualuxe-megamenu-admin',
            AQUALUXE_URI . '/assets/js/admin-megamenu.js',
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );

        // Localize script.
        wp_localize_script( 'aqualuxe-megamenu-admin', 'aqualuxe_megamenu_vars', array(
            'nonce' => wp_create_nonce( 'aqualuxe_megamenu_nonce' ),
        ) );
    }
}
```

## 4. Template Files

### header.php

```php
<?php
/**
 * Header template
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>

    <header id="masthead" class="site-header <?php echo get_theme_mod( 'aqualuxe_sticky_header', true ) ? 'sticky-header' : ''; ?>">
        <?php get_template_part( 'templates/parts/header-top' ); ?>

        <div class="header-main">
            <div class="container">
                <div class="header-main-inner">
                    <div class="site-branding">
                        <?php if ( has_custom_logo() ) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                            <?php if ( is_front_page() && is_home() ) : ?>
                                <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                            <?php else : ?>
                                <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                            <?php endif; ?>

                            <?php
                            $aqualuxe_description = get_bloginfo( 'description', 'display' );
                            if ( $aqualuxe_description || is_customize_preview() ) :
                                ?>
                                <p class="site-description"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div><!-- .site-branding -->

                    <div class="site-navigation">
                        <?php get_template_part( 'templates/parts/main-navigation' ); ?>
                    </div><!-- .site-navigation -->

                    <div class="header-actions">
                        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                            <div class="header-search">
                                <?php get_product_search_form(); ?>
                            </div>

                            <div class="header-account">
                                <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="account-link">
                                    <span class="icon"><?php echo aqualuxe_get_svg_icon( 'user' ); ?></span>
                                    <span class="text"><?php esc_html_e( 'Account', 'aqualuxe' ); ?></span>
                                </a>
                            </div>

                            <div class="header-cart">
                                <?php aqualuxe_cart_link(); ?>
                            </div>
                        <?php endif; ?>
                    </div><!-- .header-actions -->
                </div>
            </div>
        </div>
    </header><!-- #masthead -->

    <?php get_template_part( 'templates/parts/page-header' ); ?>

    <div id="content" class="site-content">
```

### footer.php

```php
<?php
/**
 * Footer template
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>
    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <?php get_template_part( 'templates/parts/footer-widgets' ); ?>

        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-inner">
                    <div class="footer-copyright">
                        <?php
                        $copyright_text = get_theme_mod( 'aqualuxe_footer_copyright' );
                        if ( ! empty( $copyright_text ) ) {
                            echo wp_kses_post( $copyright_text );
                        } else {
                            ?>
                            <p>&copy; <?php echo date_i18n( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'aqualuxe' ); ?></p>
                            <?php
                        }
                        ?>
                    </div>

                    <div class="footer-menu">
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer',
                            'menu_class'     => 'footer-navigation',
                            'depth'          => 1,
                            'fallback_cb'    => false,
                        ) );
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
```

### index.php

```php
<?php
/**
 * Main template file
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>
    <div class="container">
        <div class="row">
            <div id="primary" class="content-area col-md-8">
                <main id="main" class="site-main">
                    <?php
                    if ( have_posts() ) :
                        if ( is_home() && ! is_front_page() ) :
                            ?>
                            <header>
                                <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                            </header>
                            <?php
                        endif;

                        /* Start the Loop */
                        while ( have_posts() ) :
                            the_post();

                            /*
                             * Include the Post-Type-specific template for the content.
                             * If you want to override this in a child theme, then include a file
                             * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                             */
                            get_template_part( 'template-parts/content', get_post_type() );

                        endwhile;

                        the_posts_navigation();

                    else :

                        get_template_part( 'template-parts/content', 'none' );

                    endif;
                    ?>
                </main><!-- #main -->
            </div><!-- #primary -->

            <?php get_sidebar(); ?>
        </div>
    </div>
<?php
get_footer();
```

### page.php

```php
<?php
/**
 * Page template
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>
    <div class="container">
        <div class="row">
            <div id="primary" class="content-area col-md-8">
                <main id="main" class="site-main">
                    <?php
                    while ( have_posts() ) :
                        the_post();

                        get_template_part( 'template-parts/content', 'page' );

                        // If comments are open or we have at least one comment, load up the comment template.
                        if ( comments_open() || get_comments_number() ) :
                            comments_template();
                        endif;

                    endwhile; // End of the loop.
                    ?>
                </main><!-- #main -->
            </div><!-- #primary -->

            <?php get_sidebar(); ?>
        </div>
    </div>
<?php
get_footer();
```

### single.php

```php
<?php
/**
 * Single post template
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>
    <div class="container">
        <div class="row">
            <div id="primary" class="content-area col-md-8">
                <main id="main" class="site-main">
                    <?php
                    while ( have_posts() ) :
                        the_post();

                        get_template_part( 'template-parts/content', get_post_type() );

                        the_post_navigation(
                            array(
                                'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
                                'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
                            )
                        );

                        // If comments are open or we have at least one comment, load up the comment template.
                        if ( comments_open() || get_comments_number() ) :
                            comments_template();
                        endif;

                    endwhile; // End of the loop.
                    ?>
                </main><!-- #main -->
            </div><!-- #primary -->

            <?php get_sidebar(); ?>
        </div>
    </div>
<?php
get_footer();
```

### archive.php

```php
<?php
/**
 * Archive template
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>
    <div class="container">
        <div class="row">
            <div id="primary" class="content-area col-md-8">
                <main id="main" class="site-main">
                    <?php if ( have_posts() ) : ?>
                        <header class="page-header">
                            <?php
                            the_archive_title( '<h1 class="page-title">', '</h1>' );
                            the_archive_description( '<div class="archive-description">', '</div>' );
                            ?>
                        </header><!-- .page-header -->

                        <?php
                        /* Start the Loop */
                        while ( have_posts() ) :
                            the_post();

                            /*
                             * Include the Post-Type-specific template for the content.
                             * If you want to override this in a child theme, then include a file
                             * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                             */
                            get_template_part( 'template-parts/content', get_post_type() );

                        endwhile;

                        the_posts_navigation();

                    else :

                        get_template_part( 'template-parts/content', 'none' );

                    endif;
                    ?>
                </main><!-- #main -->
            </div><!-- #primary -->

            <?php get_sidebar(); ?>
        </div>
    </div>
<?php
get_footer();
```

### search.php

```php
<?php
/**
 * Search results template
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>
    <div class="container">
        <div class="row">
            <div id="primary" class="content-area col-md-8">
                <main id="main" class="site-main">
                    <?php if ( have_posts() ) : ?>
                        <header class="page-header">
                            <h1 class="page-title">
                                <?php
                                /* translators: %s: search query. */
                                printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span>' . get_search_query() . '</span>' );
                                ?>
                            </h1>
                        </header><!-- .page-header -->

                        <?php
                        /* Start the Loop */
                        while ( have_posts() ) :
                            the_post();

                            /**
                             * Run the loop for the search to output the results.
                             * If you want to overload this in a child theme then include a file
                             * called content-search.php and that will be used instead.
                             */
                            get_template_part( 'template-parts/content', 'search' );

                        endwhile;

                        the_posts_navigation();

                    else :

                        get_template_part( 'template-parts/content', 'none' );

                    endif;
                    ?>
                </main><!-- #main -->
            </div><!-- #primary -->

            <?php get_sidebar(); ?>
        </div>
    </div>
<?php
get_footer();
```

### 404.php

```php
<?php
/**
 * 404 template
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>
    <div class="container">
        <div class="row">
            <div id="primary" class="content-area col-md-8">
                <main id="main" class="site-main">
                    <section class="error-404 not-found">
                        <header class="page-header">
                            <h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?></h1>
                        </header><!-- .page-header -->

                        <div class="page-content">
                            <p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?></p>

                            <?php
                            get_search_form();

                            the_widget( 'WP_Widget_Recent_Posts' );
                            ?>

                            <div class="widget widget_categories">
                                <h2 class="widget-title"><?php esc_html_e( 'Most Used Categories', 'aqualuxe' ); ?></h2>
                                <ul>
                                    <?php
                                    wp_list_categories( array(
                                        'orderby'    => 'count',
                                        'order'      => 'DESC',
                                        'show_count' => 1,
                                        'title_li'   => '',
                                        'number'     => 10,
                                    ) );
                                    ?>
                                </ul>
                            </div><!-- .widget -->

                            <?php
                            /* translators: %1$s: smiley */
                            $aqualuxe_archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', 'aqualuxe' ), convert_smilies( ':)' ) ) . '</p>';
                            the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$aqualuxe_archive_content" );

                            the_widget( 'WP_Widget_Tag_Cloud' );
                            ?>
                        </div><!-- .page-content -->
                    </section><!-- .error-404 -->
                </main><!-- #main -->
            </div><!-- #primary -->

            <?php get_sidebar(); ?>
        </div>
    </div>
<?php
get_footer();
```

## 5. WooCommerce Templates

### woocommerce/archive-product.php

```php
<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
<header class="woocommerce-products-header">
    <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
        <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
    <?php endif; ?>

    <?php
    /**
     * Hook: woocommerce_archive_description.
     *
     * @hooked woocommerce_taxonomy_archive_description - 10
     * @hooked woocommerce_product_archive_description - 10
     */
    do_action( 'woocommerce_archive_description' );
    ?>
</header>

<?php
if ( woocommerce_product_loop() ) {

    /**
     * Hook: woocommerce_before_shop_loop.
     *
     * @hooked woocommerce_output_all_notices - 10
     * @hooked woocommerce_result_count - 20
     * @hooked woocommerce_catalog_ordering - 30
     */
    do_action( 'woocommerce_before_shop_loop' );

    woocommerce_product_loop_start();

    if ( wc_get_loop_prop( 'total' ) ) {
        while ( have_posts() ) {
            the_post();

            /**
             * Hook: woocommerce_shop_loop.
             *
             * @hooked WC_Structured_Data::generate_product_data() - 10
             */
            do_action( 'woocommerce_shop_loop' );

            wc_get_template_part( 'content', 'product' );
        }
    }

    woocommerce_product_loop_end();

    /**
     * Hook: woocommerce_after_shop_loop.
     *
     * @hooked woocommerce_pagination - 10
     */
    do_action( 'woocommerce_after_shop_loop' );
} else {
    /**
     * Hook: woocommerce_no_products_found.
     *
     * @hooked wc_no_products_found - 10
     */
    do_action( 'woocommerce_no_products_found' );
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
```

### woocommerce/single-product.php

```php
<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
<div class="container">
    <div class="row">
        <div id="primary" class="content-area col-md-9">
            <?php
            while ( have_posts() ) :
                the_post();
                ?>

                <div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

                    <?php
                    /**
                     * Hook: woocommerce_before_single_product_summary.
                     *
                     * @hooked woocommerce_show_product_sale_flash - 10
                     * @hooked woocommerce_show_product_images - 20
                     */
                    do_action( 'woocommerce_before_single_product_summary' );
                    ?>

                    <div class="summary entry-summary">
                        <?php
                        /**
                         * Hook: woocommerce_single_product_summary.
                         *
                         * @hooked woocommerce_template_single_title - 5
                         * @hooked woocommerce_template_single_rating - 10
                         * @hooked woocommerce_template_single_price - 10
                         * @hooked woocommerce_template_single_excerpt - 20
                         * @hooked woocommerce_template_single_add_to_cart - 30
                         * @hooked woocommerce_template_single_meta - 40
                         * @hooked woocommerce_template_single_sharing - 50
                         * @hooked WC_Structured_Data::generate_product_data() - 60
                         */
                        do_action( 'woocommerce_single_product_summary' );
                        ?>
                    </div>

                    <?php
                    /**
                     * Hook: woocommerce_after_single_product_summary.
                     *
                     * @hooked woocommerce_output_product_data_tabs - 10
                     * @hooked woocommerce_upsell_display - 15
                     * @hooked woocommerce_output_related_products - 20
                     */
                    do_action( 'woocommerce_after_single_product_summary' );
                    ?>
                </div>

                <?php
            endwhile; // End of the loop.
            ?>
        </div>

        <?php
        /**
         * Hook: woocommerce_sidebar.
         *
         * @hooked woocommerce_get_sidebar - 10
         */
        do_action( 'woocommerce_sidebar' );
        ?>
    </div>
</div>

<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

get_footer( 'shop' );
```

### woocommerce/content-product.php

```php
<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}
?>
<li <?php wc_product_class( '', $product ); ?>>
    <?php
    /**
     * Hook: woocommerce_before_shop_loop_item.
     *
     * @hooked woocommerce_template_loop_product_link_open - 10
     */
    do_action( 'woocommerce_before_shop_loop_item' );

    /**
     * Hook: woocommerce_before_shop_loop_item_title.
     *
     * @hooked woocommerce_show_product_loop_sale_flash - 10
     * @hooked woocommerce_template_loop_product_thumbnail - 10
     */
    do_action( 'woocommerce_before_shop_loop_item_title' );

    /**
     * Hook: woocommerce_shop_loop_item_title.
     *
     * @hooked woocommerce_template_loop_product_title - 10
     */
    do_action( 'woocommerce_shop_loop_item_title' );

    /**
     * Hook: woocommerce_after_shop_loop_item_title.
     *
     * @hooked woocommerce_template_loop_rating - 5
     * @hooked woocommerce_template_loop_price - 10
     */
    do_action( 'woocommerce_after_shop_loop_item_title' );

    /**
     * Hook: woocommerce_after_shop_loop_item.
     *
     * @hooked woocommerce_template_loop_product_link_close - 5
     * @hooked woocommerce_template_loop_add_to_cart - 10
     */
    do_action( 'woocommerce_after_shop_loop_item' );
    ?>
</li>
```

### woocommerce/content-single-product.php

```php
<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

    <div class="row">
        <div class="col-md-6">
            <?php
            /**
             * Hook: woocommerce_before_single_product_summary.
             *
             * @hooked woocommerce_show_product_sale_flash - 10
             * @hooked woocommerce_show_product_images - 20
             */
            do_action( 'woocommerce_before_single_product_summary' );
            ?>
        </div>

        <div class="col-md-6">
            <div class="summary entry-summary">
                <?php
                /**
                 * Hook: woocommerce_single_product_summary.
                 *
                 * @hooked woocommerce_template_single_title - 5
                 * @hooked woocommerce_template_single_rating - 10
                 * @hooked woocommerce_template_single_price - 10
                 * @hooked woocommerce_template_single_excerpt - 20
                 * @hooked woocommerce_template_single_add_to_cart - 30
                 * @hooked woocommerce_template_single_meta - 40
                 * @hooked woocommerce_template_single_sharing - 50
                 * @hooked WC_Structured_Data::generate_product_data() - 60
                 */
                do_action( 'woocommerce_single_product_summary' );
                ?>
            </div>
        </div>
    </div>

    <?php
    /**
     * Hook: woocommerce_after_single_product_summary.
     *
     * @hooked woocommerce_output_product_data_tabs - 10
     * @hooked woocommerce_upsell_display - 15
     * @hooked woocommerce_output_related_products - 20
     */
    do_action( 'woocommerce_after_single_product_summary' );
    ?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
```

### woocommerce/cart/cart.php

```php
<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
    <?php do_action( 'woocommerce_before_cart_table' ); ?>

    <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
        <thead>
            <tr>
                <th class="product-remove">&nbsp;</th>
                <th class="product-thumbnail">&nbsp;</th>
                <th class="product-name"><?php esc_html_e( 'Product', 'aqualuxe' ); ?></th>
                <th class="product-price"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></th>
                <th class="product-quantity"><?php esc_html_e( 'Quantity', 'aqualuxe' ); ?></th>
                <th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'aqualuxe' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php do_action( 'woocommerce_before_cart_contents' ); ?>

            <?php
            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                    $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                    ?>
                    <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                        <td class="product-remove">
                            <?php
                                echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                    'woocommerce_cart_item_remove_link',
                                    sprintf(
                                        '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                        esc_html__( 'Remove this item', 'aqualuxe' ),
                                        esc_attr( $product_id ),
                                        esc_attr( $_product->get_sku() )
                                    ),
                                    $cart_item_key
                                );
                            ?>
                        </td>

                        <td class="product-thumbnail">
                            <?php
                            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                            if ( ! $product_permalink ) {
                                echo $thumbnail; // PHPCS: XSS ok.
                            } else {
                                printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
                            }
                            ?>
                        </td>

                        <td class="product-name" data-title="<?php esc_attr_e( 'Product', 'aqualuxe' ); ?>">
                            <?php
                            if ( ! $product_permalink ) {
                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
                            } else {
                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                            }

                            do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                            // Meta data.
                            echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

                            // Backorder notification.
                            if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'aqualuxe' ) . '</p>', $product_id ) );
                            }
                            ?>
                        </td>

                        <td class="product-price" data-title="<?php esc_attr_e( 'Price', 'aqualuxe' ); ?>">
                            <?php
                                echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                            ?>
                        </td>

                        <td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'aqualuxe' ); ?>">
                            <?php
                            if ( $_product->is_sold_individually() ) {
                                $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                            } else {
                                $product_quantity = woocommerce_quantity_input(
                                    array(
                                        'input_name'   => "cart[{$cart_item_key}][qty]",
                                        'input_value'  => $cart_item['quantity'],
                                        'max_value'    => $_product->get_max_purchase_quantity(),
                                        'min_value'    => '0',
                                        'product_name' => $_product->get_name(),
                                    ),
                                    $_product,
                                    false
                                );
                            }

                            echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
                            ?>
                        </td>

                        <td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'aqualuxe' ); ?>">
                            <?php
                                echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                            ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>

            <?php do_action( 'woocommerce_cart_contents' ); ?>

            <tr>
                <td colspan="6" class="actions">

                    <?php if ( wc_coupons_enabled() ) : ?>
                        <div class="coupon">
                            <label for="coupon_code"><?php esc_html_e( 'Coupon:', 'aqualuxe' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'aqualuxe' ); ?>" /> <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'aqualuxe' ); ?>"><?php esc_attr_e( 'Apply coupon', 'aqualuxe' ); ?></button>
                            <?php do_action( 'woocommerce_cart_coupon' ); ?>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'aqualuxe' ); ?>"><?php esc_html_e( 'Update cart', 'aqualuxe' ); ?></button>

                    <?php do_action( 'woocommerce_cart_actions' ); ?>

                    <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                </td>
            </tr>

            <?php do_action( 'woocommerce_after_cart_contents' ); ?>
        </tbody>
    </table>
    <?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<div class="cart-collaterals">
    <?php
        /**
         * Cart collaterals hook.
         *
         * @hooked woocommerce_cross_sell_display
         * @hooked woocommerce_cart_totals - 10
         */
        do_action( 'woocommerce_cart_collaterals' );
    ?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
```

### woocommerce/checkout/form-checkout.php

```php
<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.3
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'aqualuxe' ) ) );
    return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

    <?php if ( $checkout->get_checkout_fields() ) : ?>

        <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

        <div class="row" id="customer_details">
            <div class="col-md-6">
                <?php do_action( 'woocommerce_checkout_billing' ); ?>
            </div>

            <div class="col-md-6">
                <?php do_action( 'woocommerce_checkout_shipping' ); ?>
            </div>
        </div>

        <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

    <?php endif; ?>

    <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

    <h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'aqualuxe' ); ?></h3>

    <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

    <div id="order_review" class="woocommerce-checkout-review-order">
        <?php do_action( 'woocommerce_checkout_order_review' ); ?>
    </div>

    <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
```

## 6. JavaScript Files

### assets/js/main.js

```javascript
/**
 * Main JavaScript file for AquaLuxe theme
 *
 * @package AquaLuxe
 */

(function ($) {
  'use strict';

  // Document ready.
  $(document).ready(function () {
    // Initialize mobile menu.
    aqualuxeMobileMenu();

    // Initialize sticky header.
    aqualuxeStickyHeader();

    // Initialize back to top button.
    aqualuxeBackToTop();

    // Initialize search toggle.
    aqualuxeSearchToggle();
  });

  // Window load.
  $(window).on('load', function () {
    // Initialize preloader.
    aqualuxePreloader();
  });

  // Window scroll.
  $(window).on('scroll', function () {
    // Show/hide back to top button.
    aqualuxeToggleBackToTop();
  });

  // Mobile menu.
  function aqualuxeMobileMenu() {
    var $mobileMenuToggle = $('.mobile-menu-toggle');
    var $mobileMenu = $('.mobile-navigation');
    var $mobileMenuClose = $('.mobile-menu-close');

    if ($mobileMenuToggle.length > 0) {
      $mobileMenuToggle.on('click', function (e) {
        e.preventDefault();
        $mobileMenu.addClass('active');
        $('body').addClass('mobile-menu-open');
      });
    }

    if ($mobileMenuClose.length > 0) {
      $mobileMenuClose.on('click', function (e) {
        e.preventDefault();
        $mobileMenu.removeClass('active');
        $('body').removeClass('mobile-menu-open');
      });
    }

    // Close mobile menu when clicking outside.
    $(document).on('click', function (e) {
      if (
        !$(e.target).closest('.mobile-navigation').length &&
        !$(e.target).closest('.mobile-menu-toggle').length
      ) {
        $mobileMenu.removeClass('active');
        $('body').removeClass('mobile-menu-open');
      }
    });

    // Mobile menu dropdown.
    $('.mobile-navigation ul li.menu-item-has-children > a').after(
      '<span class="dropdown-toggle"></span>'
    );
    $('.mobile-navigation .dropdown-toggle').on('click', function () {
      $(this).toggleClass('active').next('ul').slideToggle();
    });
  }

  // Sticky header.
  function aqualuxeStickyHeader() {
    var $header = $('.site-header');
    var headerHeight = $header.outerHeight();
    var lastScrollTop = 0;

    if ($header.hasClass('sticky-header')) {
      $(window).on('scroll', function () {
        var scrollTop = $(this).scrollTop();

        if (scrollTop > headerHeight) {
          $header.addClass('sticky');
        } else {
          $header.removeClass('sticky');
        }

        lastScrollTop = scrollTop;
      });
    }
  }

  // Back to top button.
  function aqualuxeBackToTop() {
    var $backToTop = $('.back-to-top');

    if ($backToTop.length > 0) {
      $backToTop.on('click', function (e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, 800);
      });
    }
  }

  // Toggle back to top button.
  function aqualuxeToggleBackToTop() {
    var $backToTop = $('.back-to-top');
    var scrollTop = $(window).scrollTop();

    if ($backToTop.length > 0) {
      if (scrollTop > 300) {
        $backToTop.addClass('show');
      } else {
        $backToTop.removeClass('show');
      }
    }
  }

  // Search toggle.
  function aqualuxeSearchToggle() {
    var $searchToggle = $('.search-toggle');
    var $searchForm = $('.product-search-form');

    if ($searchToggle.length > 0 && $searchForm.length > 0) {
      $searchToggle.on('click', function (e) {
        e.preventDefault();
        $searchForm.toggleClass('active');
        $searchForm.find('input[type="search"]').focus();
      });

      // Close search form when clicking outside.
      $(document).on('click', function (e) {
        if (
          !$(e.target).closest('.product-search-form').length &&
          !$(e.target).closest('.search-toggle').length
        ) {
          $searchForm.removeClass('active');
        }
      });
    }
  }

  // Preloader.
  function aqualuxePreloader() {
    var $preloader = $('.preloader');

    if ($preloader.length > 0) {
      $preloader.fadeOut(500);
    }
  }
})(jQuery);
```

### assets/js/woocommerce.js

```javascript
/**
 * WooCommerce JavaScript file for AquaLuxe theme
 *
 * @package AquaLuxe
 */

(function ($) {
  'use strict';

  // Document ready.
  $(document).ready(function () {
    // Initialize product gallery.
    aqualuxeProductGallery();

    // Initialize quantity buttons.
    aqualuxeQuantityButtons();

    // Initialize variation form.
    aqualuxeVariationForm();

    // Initialize tabs.
    aqualuxeTabs();

    // Initialize accordion.
    aqualuxeAccordion();
  });

  // Product gallery.
  function aqualuxeProductGallery() {
    var $productGallery = $('.woocommerce-product-gallery');

    if ($productGallery.length > 0) {
      // Initialize flexslider if available.
      if ($.fn.flexslider) {
        var flexsliderArgs = {
          animation: 'slide',
          controlNav: 'thumbnails',
          animationLoop: false,
          slideshow: false,
          smoothHeight: true,
        };

        $productGallery.flexslider(flexsliderArgs);
      }

      // Initialize zoom if available.
      if ($.fn.zoom) {
        $productGallery.find('.woocommerce-product-gallery__image').zoom();
      }

      // Initialize photoswipe if available.
      if (typeof PhotoSwipe !== 'undefined') {
        $productGallery.on(
          'click',
          '.woocommerce-product-gallery__trigger',
          function (e) {
            e.preventDefault();
            var pswpElement = $('.pswp')[0];
            var items = [];
            var index = 0;

            $productGallery
              .find('.woocommerce-product-gallery__image')
              .each(function () {
                var $img = $(this).find('img');
                var large_image_src = $img.attr('data-large_image');
                var large_image_w = $img.attr('data-large_image_width');
                var large_image_h = $img.attr('data-large_image_height');

                if (large_image_src) {
                  items.push({
                    src: large_image_src,
                    w: large_image_w,
                    h: large_image_h,
                    title: $img.attr('alt'),
                  });
                }
              });

            var gallery = new PhotoSwipe(
              pswpElement,
              PhotoSwipeUI_Default,
              items,
              {
                index: index,
                shareEl: false,
              }
            );

            gallery.init();
          }
        );
      }
    }
  }

  // Quantity buttons.
  function aqualuxeQuantityButtons() {
    $('div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)')
      .addClass('buttons_added')
      .append('<input type="button" value="+" class="plus" />')
      .prepend('<input type="button" value="-" class="minus" />');

    $(document).on('click', '.plus, .minus', function () {
      // Get values.
      var $qty = $(this).closest('.quantity').find('.qty'),
        currentVal = parseFloat($qty.val()),
        max = parseFloat($qty.attr('max')),
        min = parseFloat($qty.attr('min')),
        step = $qty.attr('step');

      // Format values.
      if (!currentVal || currentVal === '' || currentVal === 'NaN') {
        currentVal = 0;
      }
      if (max === '' || max === 'NaN') {
        max = '';
      }
      if (min === '' || min === 'NaN') {
        min = 0;
      }
      if (
        step === 'any' ||
        step === '' ||
        step === undefined ||
        parseFloat(step) === 'NaN'
      ) {
        step = 1;
      }

      // Change the value.
      if ($(this).is('.plus')) {
        if (max && currentVal >= max) {
          $qty.val(max);
        } else {
          $qty.val((currentVal + parseFloat(step)).toFixed(step.getDecimals()));
        }
      } else {
        if (min && currentVal <= min) {
          $qty.val(min);
        } else if (currentVal > 0) {
          $qty.val((currentVal - parseFloat(step)).toFixed(step.getDecimals()));
        }
      }

      // Trigger change event.
      $qty.trigger('change');
    });
  }

  // Variation form.
  function aqualuxeVariationForm() {
    var $variationForm = $('.variations_form');

    if ($variationForm.length > 0) {
      $variationForm.on('woocommerce_variation_select_change', function () {
        $variationForm.wc_variations_variation_update();
      });

      $variationForm.on('found_variation', function (event, variation) {
        var $singleVariation = $variationForm.find('.single_variation');
        var $singleVariationWrap = $variationForm.find(
          '.single_variation_wrap'
        );

        if (!variation) {
          $singleVariation.slideUp(200).html('');
          $singleVariationWrap.find('.variations_button').hide();
        } else {
          $singleVariation
            .html(variation.price_html + variation.availability_html)
            .slideDown(200);
          $singleVariationWrap.find('.variations_button').show();

          // Update image.
          if (
            variation.image &&
            variation.image.src &&
            variation.image.src.length > 1
          ) {
            $variationForm
              .find('.woocommerce-product-gallery__image img')
              .attr('src', variation.image.src);
            $variationForm
              .find('.woocommerce-product-gallery__image a')
              .attr('href', variation.image_link);
          }
        }
      });
    }
  }

  // Tabs.
  function aqualuxeTabs() {
    var $tabs = $('.woocommerce-tabs');

    if ($tabs.length > 0) {
      $tabs.on('click', 'ul.tabs li a', function (e) {
        e.preventDefault();
        var $tab = $(this);
        var $tabsWrapper = $tab.closest('.woocommerce-tabs');
        var $tabContent = $tabsWrapper.find('.panel');

        // Remove active class from all tabs.
        $tabsWrapper.find('ul.tabs li a').removeClass('active');

        // Add active class to clicked tab.
        $tab.addClass('active');

        // Hide all tab content.
        $tabContent.hide();

        // Show clicked tab content.
        $tabContent.filter($tab.attr('href')).show();
      });

      // Activate first tab.
      $tabs.find('ul.tabs li:first-child a').click();
    }
  }

  // Accordion.
  function aqualuxeAccordion() {
    var $accordion = $('.woocommerce-accordion');

    if ($accordion.length > 0) {
      $accordion.on('click', '.accordion-title', function (e) {
        e.preventDefault();
        var $title = $(this);
        var $content = $title.next('.accordion-content');
        var $accordionItem = $title.closest('.accordion-item');

        // Close other accordion items.
        $accordion
          .find('.accordion-item')
          .not($accordionItem)
          .removeClass('active');
        $accordion.find('.accordion-content').not($content).slideUp();

        // Toggle current accordion item.
        $accordionItem.toggleClass('active');
        $content.slideToggle();
      });

      // Activate first accordion item.
      $accordion.find('.accordion-item:first-child .accordion-title').click();
    }
  }

  // Helper function to get decimal places.
  Number.prototype.getDecimals = function () {
    var num = this,
      match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
    if (!match) {
      return 0;
    }
    return Math.max(
      0,
      (match[1] ? match[1].length : 0) - (match[2] ? +match[2] : 0)
    );
  };
})(jQuery);
```

### assets/js/ajax-cart.js

```javascript
/**
 * AJAX Cart JavaScript file for AquaLuxe theme
 *
 * @package AquaLuxe
 */

(function ($) {
  'use strict';

  // Document ready.
  $(document).ready(function () {
    // Initialize AJAX add to cart.
    aqualuxeAjaxAddToCart();

    // Initialize quick view.
    aqualuxeQuickView();

    // Initialize mini cart.
    aqualuxeMiniCart();
  });

  // AJAX add to cart.
  function aqualuxeAjaxAddToCart() {
    $('body').on('click', '.add_to_cart_button', function (e) {
      var $button = $(this);

      if ($button.is('.ajax_add_to_cart')) {
        e.preventDefault();

        var product_id = $button.val(),
          quantity = $button.data('quantity');

        var data = {
          action: 'aqualuxe_ajax_add_to_cart',
          product_id: product_id,
          quantity: quantity,
          nonce: aqualuxe_ajax_cart.nonce,
        };

        $(document.body).trigger('adding_to_cart', [$button, data]);

        $.ajax({
          type: 'post',
          url: aqualuxe_ajax_cart.ajax_url,
          data: data,
          beforeSend: function (response) {
            $button.removeClass('added').addClass('loading');
          },
          complete: function (response) {
            $button.addClass('added').removeClass('loading');
          },
          success: function (response) {
            if (response.error && response.product_url) {
              window.location = response.product_url;
              return;
            }

            // Redirect to cart if option is enabled.
            if (aqualuxe_ajax_cart.cart_redirect_after_add === 'yes') {
              window.location = aqualuxe_ajax_cart.cart_url;
              return;
            }

            // Trigger event.
            $(document.body).trigger('added_to_cart', [
              response.fragments,
              response.cart_hash,
              $button,
            ]);

            // Update cart fragments.
            if (response.fragments) {
              $.each(response.fragments, function (key, value) {
                $(key).replaceWith(value);
              });
            }

            // Show added to cart message.
            var message = response.message || aqualuxe_ajax_cart.i18n_view_cart;
            $button.siblings('.added_to_cart').remove();
            $button.after(
              '<a href="' +
                aqualuxe_ajax_cart.cart_url +
                '" class="added_to_cart wc-forward" title="' +
                message +
                '">' +
                message +
                '</a>'
            );
          },
        });

        return false;
      }
    });
  }

  // Quick view.
  function aqualuxeQuickView() {
    var $quickViewButton = $('.quick-view-button');
    var $quickViewModal = $('.quick-view-modal');
    var $quickViewContent = $('.quick-view-content');

    if ($quickViewButton.length > 0 && $quickViewModal.length > 0) {
      // Open quick view modal.
      $quickViewButton.on('click', function (e) {
        e.preventDefault();
        var $button = $(this);
        var product_id = $button.data('product_id');

        // Show loading.
        $quickViewContent.html(
          '<div class="loading">' + aqualuxe_ajax_cart.i18n_loading + '</div>'
        );
        $quickViewModal.addClass('active');
        $('body').addClass('quick-view-open');

        // Load product content.
        $.ajax({
          type: 'post',
          url: aqualuxe_ajax_cart.ajax_url,
          data: {
            action: 'aqualuxe_quick_view',
            product_id: product_id,
            nonce: aqualuxe_ajax_cart.nonce,
          },
          success: function (response) {
            if (response.success) {
              $quickViewContent.html(response.data);

              // Initialize product gallery.
              aqualuxeProductGallery();

              // Initialize variation form.
              aqualuxeVariationForm();

              // Initialize quantity buttons.
              aqualuxeQuantityButtons();
            } else {
              $quickViewContent.html(
                '<div class="error">' + response.data + '</div>'
              );
            }
          },
          error: function () {
            $quickViewContent.html(
              '<div class="error">' + aqualuxe_ajax_cart.i18n_error + '</div>'
            );
          },
        });
      });

      // Close quick view modal.
      $('.quick-view-close, .quick-view-overlay').on('click', function (e) {
        e.preventDefault();
        $quickViewModal.removeClass('active');
        $('body').removeClass('quick-view-open');
        $quickViewContent.html('');
      });
    }
  }

  // Mini cart.
  function aqualuxeMiniCart() {
    var $miniCartToggle = $('.cart-link');
    var $miniCart = $('.mini-cart');

    if ($miniCartToggle.length > 0 && $miniCart.length > 0) {
      // Toggle mini cart.
      $miniCartToggle.on('click', function (e) {
        e.preventDefault();
        $miniCart.toggleClass('active');
      });

      // Close mini cart when clicking outside.
      $(document).on('click', function (e) {
        if (
          !$(e.target).closest('.mini-cart').length &&
          !$(e.target).closest('.cart-link').length
        ) {
          $miniCart.removeClass('active');
        }
      });
    }
  }
})(jQuery);
```

## 7. Helper Functions

### inc/helpers.php

```php
<?php
/**
 * Helper Functions
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true === $checked ) ? true : false );
}

/**
 * Sanitize select.
 *
 * @param string $input Input to sanitize.
 * @param array  $choices Choices.
 * @return string
 */
function aqualuxe_sanitize_select( $input, $choices ) {
    return ( array_key_exists( $input, $choices ) ? $input : '' );
}

/**
 * Sanitize number.
 *
 * @param int $number Number to sanitize.
 * @return int
 */
function aqualuxe_sanitize_number( $number ) {
    return absint( $number );
}

/**
 * Get SVG icon.
 *
 * @param string $icon Icon name.
 * @return string
 */
function aqualuxe_get_svg_icon( $icon ) {
    $icons = array(
        'user'    => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
        'cart'    => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>',
        'search'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>',
        'close'   => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>',
        'chevron' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>',
    );

    return isset( $icons[ $icon ] ) ? $icons[ $icon ] : '';
}

/**
 * Get cart link.
 */
function aqualuxe_cart_link() {
    ?>
    <a class="cart-link" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
        <span class="icon"><?php echo aqualuxe_get_svg_icon( 'cart' ); ?></span>
        <span class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
    </a>
    <?php
}

/**
 * Get mini cart.
 */
function aqualuxe_mini_cart() {
    ?>
    <div class="mini-cart">
        <div class="mini-cart-content">
            <?php woocommerce_mini_cart(); ?>
        </div>
    </div>
    <?php
}

/**
 * Get post thumbnail.
 *
 * @param int    $post_id Post ID.
 * @param string $size Image size.
 * @return string
 */
function aqualuxe_get_post_thumbnail( $post_id = null, $size = 'post-thumbnail' ) {
    if ( null === $post_id ) {
        $post_id = get_the_ID();
    }

    if ( has_post_thumbnail( $post_id ) ) {
        return get_the_post_thumbnail( $post_id, $size );
    }

    return '';
}

/**
 * Get post excerpt.
 *
 * @param int    $post_id Post ID.
 * @param int    $length Excerpt length.
 * @param string $more More text.
 * @return string
 */
function aqualuxe_get_post_excerpt( $post_id = null, $length = 55, $more = '&hellip;' ) {
    if ( null === $post_id ) {
        $post_id = get_the_ID();
    }

    $post = get_post( $post_id );

    if ( ! $post ) {
        return '';
    }

    if ( has_excerpt( $post_id ) ) {
        $excerpt = get_the_excerpt( $post_id );
    } else {
        $excerpt = $post->post_content;
    }

    $excerpt = strip_shortcodes( $excerpt );
    $excerpt = wp_trim_words( $excerpt, $length, $more );

    return $excerpt;
}

/**
 * Get related posts.
 *
 * @param int   $post_id Post ID.
 * @param int   $number Number of posts.
 * @return array
 */
function aqualuxe_get_related_posts( $post_id = null, $number = 3 ) {
    if ( null === $post_id ) {
        $post_id = get_the_ID();
    }

    $categories = get_the_category( $post_id );
    $category_ids = array();

    if ( $categories ) {
        foreach ( $categories as $category ) {
            $category_ids[] = $category->term_id;
        }
    }

    $args = array(
        'category__in'        => $category_ids,
        'post__not_in'        => array( $post_id ),
        'posts_per_page'      => $number,
        'ignore_sticky_posts' => 1,
    );

    $related_posts = new WP_Query( $args );

    return $related_posts;
}

/**
 * Get post reading time.
 *
 * @param int $post_id Post ID.
 * @return int
 */
function aqualuxe_get_reading_time( $post_id = null ) {
    if ( null === $post_id ) {
        $post_id = get_the_ID();
    }

    $post = get_post( $post_id );

    if ( ! $post ) {
        return 0;
    }

    $content = $post->post_content;
    $content = strip_shortcodes( $content );
    $content = strip_tags( $content );
    $word_count = str_word_count( $content );
    $reading_time = ceil( $word_count / 200 );

    return $reading_time;
}

/**
 * Get social links.
 *
 * @return array
 */
function aqualuxe_get_social_links() {
    $social_links = array(
        'facebook'  => get_theme_mod( 'aqualuxe_social_facebook', '' ),
        'twitter'   => get_theme_mod( 'aqualuxe_social_twitter', '' ),
        'instagram' => get_theme_mod( 'aqualuxe_social_instagram', '' ),
        'linkedin'  => get_theme_mod( 'aqualuxe_social_linkedin', '' ),
        'youtube'   => get_theme_mod( 'aqualuxe_social_youtube', '' ),
        'pinterest' => get_theme_mod( 'aqualuxe_social_pinterest', '' ),
    );

    return array_filter( $social_links );
}

/**
 * Get theme option.
 *
 * @param string $option Option name.
 * @param mixed  $default Default value.
 * @return mixed
 */
function aqualuxe_get_option( $option, $default = null ) {
    return get_theme_mod( $option, $default );
}

/**
 * Check if WooCommerce is active.
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists( 'WooCommerce' );
}

/**
 * Get WooCommerce product categories.
 *
 * @return array
 */
function aqualuxe_get_product_categories() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }

    $args = array(
        'taxonomy'   => 'product_cat',
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => false,
    );

    $product_categories = get_terms( $args );

    return $product_categories;
}

/**
 * Get WooCommerce products.
 *
 * @param array $args Query arguments.
 * @return array
 */
function aqualuxe_get_products( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return array();
    }

    $defaults = array(
        'post_type'      => 'product',
        'posts_per_page' => 12,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $args = wp_parse_args( $args, $defaults );

    $products = new WP_Query( $args );

    return $products;
}

/**
 * Get featured products.
 *
 * @param int $number Number of products.
 * @return array
 */
function aqualuxe_get_featured_products( $number = 6 ) {
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $number,
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
            ),
        ),
    );

    return aqualuxe_get_products( $args );
}

/**
 * Get sale products.
 *
 * @param int $number Number of products.
 * @return array
 */
function aqualuxe_get_sale_products( $number = 6 ) {
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $number,
        'meta_query'     => array(
            'relation' => 'OR',
            array(
                'key'     => '_sale_price',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'numeric',
            ),
            array(
                'key'     => '_min_variation_sale_price',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'numeric',
            ),
        ),
    );

    return aqualuxe_get_products( $args );
}

/**
 * Get best selling products.
 *
 * @param int $number Number of products.
 * @return array
 */
function aqualuxe_get_best_selling_products( $number = 6 ) {
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $number,
        'meta_key'       => 'total_sales',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
    );

    return aqualuxe_get_products( $args );
}

/**
 * Get top rated products.
 *
 * @param int $number Number of products.
 * @return array
 */
function aqualuxe_get_top_rated_products( $number = 6 ) {
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $number,
        'meta_key'       => '_wc_average_rating',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
    );

    return aqualuxe_get_products( $args );
}

/**
 * Get new products.
 *
 * @param int $number Number of products.
 * @return array
 */
function aqualuxe_get_new_products( $number = 6 ) {
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $number,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    return aqualuxe_get_products( $args );
}
```

## 8. WooCommerce Template Functions

### inc/woocommerce/template-functions.php

```php
<?php
/**
 * WooCommerce Template Functions
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Output content wrapper start.
 */
function aqualuxe_output_content_wrapper() {
    echo '<div class="container"><div class="row"><div id="primary" class="content-area col-md-9">';
}

/**
 * Output content wrapper end.
 */
function aqualuxe_output_content_wrapper_end() {
    echo '</div>';

    // Get shop sidebar if it's active.
    if ( is_active_sidebar( 'shop-sidebar' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
        echo '<div id="secondary" class="widget-area col-md-3" role="complementary">';
        dynamic_sidebar( 'shop-sidebar' );
        echo '</div>';
    }

    echo '</div></div>';
}

/**
 * Before main content.
 */
function aqualuxe_before_main_content() {
    ?>
    <div class="container">
        <div class="row">
            <div id="primary" class="content-area col-md-9">
    <?php
}

/**
 * After main content.
 */
function aqualuxe_after_main_content() {
    ?>
            </div>

            <?php get_sidebar( 'shop' ); ?>
        </div>
    </div>
    <?php
}

/**
 * Shop sidebar.
 */
function aqualuxe_shop_sidebar() {
    if ( is_active_sidebar( 'shop-sidebar' ) ) {
        ?>
        <div id="secondary" class="widget-area col-md-3" role="complementary">
            <?php dynamic_sidebar( 'shop-sidebar' ); ?>
        </div>
        <?php
    }
}

/**
 * Product columns.
 *
 * @return int
 */
function aqualuxe_loop_columns() {
    return get_theme_mod( 'aqualuxe_shop_columns', 4 );
}

/**
 * Products per page.
 *
 * @return int
 */
function aqualuxe_products_per_page() {
    return get_theme_mod( 'aqualuxe_shop_products_per_page', 12 );
}

/**
 * Related products args.
 *
 * @param array $args Related products args.
 * @return array
 */
function aqualuxe_related_products_args( $args ) {
    $args['posts_per_page'] = 4;
    $args['columns'] = 4;
    return $args;
}

/**
 * Cross sells total.
 *
 * @return int
 */
function aqualuxe_cross_sells_total() {
    return 4;
}

/**
 * Cross sells columns.
 *
 * @return int
 */
function aqualuxe_cross_sells_columns() {
    return 4;
}

/**
 * Product gallery.
 */
function aqualuxe_product_gallery() {
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}

/**
 * Cart link fragment.
 *
 * @param array $fragments Fragments.
 * @return array
 */
function aqualuxe_cart_link_fragment( $fragments ) {
    ob_start();
    aqualuxe_cart_link();
    $fragments['a.cart-link'] = ob_get_clean();

    return $fragments;
}

/**
 * Mini cart buttons.
 *
 * @param array $buttons Buttons.
 * @return array
 */
function aqualuxe_mini_cart_buttons( $buttons ) {
    // Remove view cart button.
    unset( $buttons['view_cart'] );

    // Change checkout button text.
    $buttons['checkout']['text'] = esc_html__( 'Checkout', 'aqualuxe' );

    return $buttons;
}

/**
 * Quick view button.
 */
function aqualuxe_quick_view_button() {
    global $product;

    if ( get_theme_mod( 'aqualuxe_product_quick_view', true ) ) {
        echo '<a href="#" class="quick-view-button" data-product_id="' . esc_attr( $product->get_id() ) . '">' . esc_html__( 'Quick View', 'aqualuxe' ) . '</a>';
    }
}

/**
 * AJAX add to cart.
 */
function aqualuxe_ajax_add_to_cart() {
    check_ajax_referer( 'aqualuxe_nonce', 'nonce' );

    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
    $quantity = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;

    if ( $product_id > 0 ) {
        $added = WC()->cart->add_to_cart( $product_id, $quantity );

        if ( $added ) {
            $data = array(
                'success' => true,
                'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', array(
                    'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . WC()->cart->get_cart_html() . '</div>',
                ) ),
                'cart_hash' => WC()->cart->get_cart_hash(),
                'message' => esc_html__( 'Product added to cart successfully!', 'aqualuxe' ),
            );

            wp_send_json_success( $data );
        } else {
            wp_send_json_error( array( 'message' => esc_html__( 'Error adding product to cart.', 'aqualuxe' ) ) );
        }
    } else {
        wp_send_json_error( array( 'message' => esc_html__( 'Invalid product.', 'aqualuxe' ) ) );
    }
}
add_action( 'wp_ajax_aqualuxe_ajax_add_to_cart', 'aqualuxe_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_aqualuxe_ajax_add_to_cart', 'aqualuxe_ajax_add_to_cart' );

/**
 * Quick view.
 */
function aqualuxe_quick_view() {
    check_ajax_referer( 'aqualuxe_nonce', 'nonce' );

    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

    if ( $product_id > 0 ) {
        global $post, $product;

        $post = get_post( $product_id );
        $product = wc_get_product( $product_id );

        if ( $product ) {
            ob_start();

            wc_get_template_part( 'content', 'single-product' );

            $content = ob_get_clean();

            wp_send_json_success( $content );
        } else {
            wp_send_json_error( esc_html__( 'Product not found.', 'aqualuxe' ) );
        }
    } else {
        wp_send_json_error( esc_html__( 'Invalid product.', 'aqualuxe' ) );
    }
}
add_action( 'wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view' );
add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view' );

/**
 * Product grid/list view toggle.
 */
function aqualuxe_product_view_toggle() {
    if ( is_shop() || is_product_category() || is_product_tag() ) {
        ?>
        <div class="product-view-toggle">
            <a href="#" class="grid-view active" data-view="grid"><?php echo aqualuxe_get_svg_icon( 'grid' ); ?></a>
            <a href="#" class="list-view" data-view="list"><?php echo aqualuxe_get_svg_icon( 'list' ); ?></a>
        </div>
        <?php
    }
}

/**
 * Product categories.
 */
function aqualuxe_product_categories() {
    $categories = aqualuxe_get_product_categories();

    if ( ! empty( $categories ) ) {
        ?>
        <div class="product-categories">
            <h3><?php esc_html_e( 'Categories', 'aqualuxe' ); ?></h3>
            <ul>
                <?php foreach ( $categories as $category ) : ?>
                    <li>
                        <a href="<?php echo esc_url( get_term_link( $category ) ); ?>">
                            <?php echo esc_html( $category->name ); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }
}

/**
 * Product filters.
 */
function aqualuxe_product_filters() {
    if ( is_active_sidebar( 'shop-sidebar' ) ) {
        dynamic_sidebar( 'shop-sidebar' );
    }
}

/**
 * Breadcrumb.
 */
function aqualuxe_breadcrumb() {
    if ( function_exists( 'woocommerce_breadcrumb' ) ) {
        woocommerce_breadcrumb();
    }
}

/**
 * Pagination.
 */
function aqualuxe_pagination() {
    if ( function_exists( 'woocommerce_pagination' ) ) {
        woocommerce_pagination();
    }
}

/**
 * Result count.
 */
function aqualuxe_result_count() {
    if ( function_exists( 'woocommerce_result_count' ) ) {
        woocommerce_result_count();
    }
}

/**
 * Catalog ordering.
 */
function aqualuxe_catalog_ordering() {
    if ( function_exists( 'woocommerce_catalog_ordering' ) ) {
        woocommerce_catalog_ordering();
    }
}

/**
 * Product loop start.
 */
function aqualuxe_product_loop_start() {
    echo '<ul class="products columns-' . esc_attr( aqualuxe_loop_columns() ) . '">';
}

/**
 * Product loop end.
 */
function aqualuxe_product_loop_end() {
    echo '</ul>';
}

/**
 * Before shop loop.
 */
function aqualuxe_before_shop_loop() {
    ?>
    <div class="woocommerce-before-shop-loop">
        <div class="row">
            <div class="col-md-6">
                <?php aqualuxe_result_count(); ?>
            </div>
            <div class="col-md-6">
                <div class="shop-loop-actions">
                    <?php aqualuxe_catalog_ordering(); ?>
                    <?php aqualuxe_product_view_toggle(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * After shop loop.
 */
function aqualuxe_after_shop_loop() {
    aqualuxe_pagination();
}

/**
 * No products found.
 */
function aqualuxe_no_products_found() {
    ?>
    <div class="woocommerce-info">
        <?php esc_html_e( 'No products were found matching your selection.', 'aqualuxe' ); ?>
    </div>
    <?php
}
```

### inc/woocommerce/template-hooks.php

```php
<?php
/**
 * WooCommerce Template Hooks
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Layout
 *
 * @see aqualuxe_before_main_content()
 * @see aqualuxe_after_main_content()
 * @see aqualuxe_shop_sidebar()
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
add_action( 'woocommerce_before_main_content', 'aqualuxe_before_main_content', 10 );
add_action( 'woocommerce_after_main_content', 'aqualuxe_after_main_content', 10 );
add_action( 'woocommerce_sidebar', 'aqualuxe_shop_sidebar', 10 );

/**
 * Products Loop
 *
 * @see aqualuxe_product_loop_start()
 * @see aqualuxe_product_loop_end()
 * @see aqualuxe_before_shop_loop()
 * @see aqualuxe_after_shop_loop()
 * @see aqualuxe_no_products_found()
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
remove_action( 'woocommerce_no_products_found', 'wc_no_products_found', 10 );
add_action( 'woocommerce_before_shop_loop', 'aqualuxe_before_shop_loop', 10 );
add_action( 'woocommerce_after_shop_loop', 'aqualuxe_after_shop_loop', 10 );
add_action( 'woocommerce_no_products_found', 'aqualuxe_no_products_found', 10 );

/**
 * Product
 *
 * @see aqualuxe_quick_view_button()
 */
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_quick_view_button', 5 );

/**
 * Single Product
 *
 * @see aqualuxe_output_content_wrapper()
 * @see aqualuxe_output_content_wrapper_end()
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
add_action( 'woocommerce_before_main_content', 'aqualuxe_output_content_wrapper', 10 );
add_action( 'woocommerce_after_main_content', 'aqualuxe_output_content_wrapper_end', 10 );

/**
 * Cart
 *
 * @see aqualuxe_mini_cart_buttons()
 */
add_filter( 'woocommerce_widget_cart_shopping_cart_buttons', 'aqualuxe_mini_cart_buttons' );

/**
 * AJAX
 *
 * @see aqualuxe_ajax_add_to_cart()
 * @see aqualuxe_quick_view()
 */
add_action( 'wp_ajax_aqualuxe_ajax_add_to_cart', 'aqualuxe_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_aqualuxe_ajax_add_to_cart', 'aqualuxe_ajax_add_to_cart' );
add_action( 'wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view' );
add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view' );

/**
 * Fragments
 *
 * @see aqualuxe_cart_link_fragment()
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'aqualuxe_cart_link_fragment' );

/**
 * Product columns
 *
 * @see aqualuxe_loop_columns()
 */
add_filter( 'loop_shop_columns', 'aqualuxe_loop_columns' );

/**
 * Products per page
 *
 * @see aqualuxe_products_per_page()
 */
add_filter( 'loop_shop_per_page', 'aqualuxe_products_per_page', 20 );

/**
 * Related products
 *
 * @see aqualuxe_related_products_args()
 */
add_filter( 'woocommerce_output_related_products_args', 'aqualuxe_related_products_args' );

/**
 * Cross sells
 *
 * @see aqualuxe_cross_sells_total()
 * @see aqualuxe_cross_sells_columns()
 */
add_filter( 'woocommerce_cross_sells_total', 'aqualuxe_cross_sells_total' );
add_filter( 'woocommerce_cross_sells_columns', 'aqualuxe_cross_sells_columns' );

/**
 * Product gallery
 *
 * @see aqualuxe_product_gallery()
 */
add_action( 'after_setup_theme', 'aqualuxe_product_gallery' );
```

## 9. Installation and Setup Instructions

### Installation

1. Download the AquaLuxe theme ZIP file.
2. In your WordPress admin dashboard, navigate to **Appearance > Themes**.
3. Click **Add New** and then **Upload Theme**.
4. Choose the AquaLuxe theme ZIP file and click **Install Now**.
5. Once installed, click **Activate**.

### Setup

1. **Install WooCommerce**: If you haven't already, install and activate the WooCommerce plugin.
2. **Configure WooCommerce**: Follow the WooCommerce setup wizard to configure your store settings.
3. **Customize Theme**: Navigate to **Appearance > Customize** to customize the theme settings:
   - **General Settings**: Configure site layout and other general options.
   - **Header Settings**: Customize header style and enable/disable sticky header.
   - **Footer Settings**: Configure footer widget columns and copyright text.
   - **Typography**: Set body and heading fonts and font sizes.
   - **Colors**: Customize primary, secondary, text, and link colors.
   - **WooCommerce Settings**: Configure shop columns, products per page, and enable/disable product quick view and AJAX add to cart.
4. **Create Pages**: Create necessary pages for your store (Shop, Cart, Checkout, My Account, etc.) and assign them in WooCommerce settings.
5. **Set Up Menus**: Navigate to **Appearance > Menus** to set up your site navigation menus.
6. **Configure Widgets**: Navigate to **Appearance > Widgets** to add widgets to your sidebars and footer widget areas.

### Customization

The AquaLuxe theme is highly customizable. Here are some ways you can customize it:

1. **Theme Customizer**: Use the WordPress Customizer to customize colors, fonts, layout, and other settings.
2. **Child Theme**: Create a child theme to make customizations that won't be lost when you update the theme.
3. **Custom CSS**: Add custom CSS in the Customizer's **Additional CSS** section.
4. **Hooks and Filters**: Use WordPress hooks and filters to modify theme functionality.
5. **Page Builders**: Use your favorite page builder to create custom page layouts.

### Troubleshooting

If you encounter any issues with the theme, try the following:

1. **Check Plugin Conflicts**: Deactivate all plugins and see if the issue persists. If it does, reactivate plugins one by one to identify the conflicting plugin.
2. **Clear Cache**: Clear your browser cache and any caching plugins you may be using.
3. **Check PHP Version**: Make sure your server is running PHP 7.4 or higher.
4. **Check WordPress Version**: Make sure you're using the latest version of WordPress.
5. **Check WooCommerce Version**: Make sure you're using the latest version of WooCommerce.
6. **Contact Support**: If you still have issues, contact the theme developer for support.

## Conclusion

The AquaLuxe theme is a premium, production-ready WordPress theme with full WooCommerce integration. It's optimized for speed, SEO, and mobile responsiveness, and follows WordPress.org and WooCommerce coding standards and best practices. With its modular structure and extensive customization options, it's perfect for creating a professional e-commerce website.
