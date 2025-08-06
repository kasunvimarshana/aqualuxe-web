# AquaLuxe WooCommerce Child Theme - Complete File Structure

## Theme Root Files

### style.css

```css
/*
Theme Name: AquaLuxe
Theme URI: https://github.com/kasunvimarshana
Description: Premium WooCommerce Child Theme for Ornamental Fish Business - Luxury aquatic elegance meets modern e-commerce functionality
Author: Kasun Vimarshana
Author URI: https://github.com/kasunvimarshana
Template: storefront
Version: 1.0.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: aqualuxe
Tags: e-commerce, woocommerce, responsive, clean, modern, multipurpose, aquatic, luxury, ornamental-fish
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
WC requires at least: 5.0
WC tested up to: 8.4
*/

:root {
  --aqualuxe-primary: #0066cc;
  --aqualuxe-secondary: #00cccc;
  --aqualuxe-accent: #ffcc00;
  --aqualuxe-dark: #003366;
  --aqualuxe-light: #f0f8ff;
  --aqualuxe-gradient: linear-gradient(135deg, #0066cc 0%, #00cccc 100%);
  --aqualuxe-shadow: 0 4px 15px rgba(0, 102, 204, 0.1);
  --aqualuxe-transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

/* Import parent theme styles */
@import url('../storefront/style.css');

/* Modern Typography */
body {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  line-height: 1.7;
  color: var(--aqualuxe-dark);
}

h1,
h2,
h3,
h4,
h5,
h6 {
  font-family: 'Poppins', sans-serif;
  font-weight: 600;
  color: var(--aqualuxe-dark);
}

/* Header Enhancements */
.site-header {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border-bottom: 1px solid rgba(0, 102, 204, 0.1);
  transition: var(--aqualuxe-transition);
}

.site-header.sticky {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 999;
  box-shadow: var(--aqualuxe-shadow);
}

.site-branding a {
  color: var(--aqualuxe-primary) !important;
  font-weight: 700;
  font-size: 1.8em;
  text-decoration: none;
}

/* Navigation */
.main-navigation a {
  color: var(--aqualuxe-dark);
  font-weight: 500;
  transition: var(--aqualuxe-transition);
}

.main-navigation a:hover {
  color: var(--aqualuxe-primary);
}

/* Hero Section */
.aqualuxe-hero {
  background: var(--aqualuxe-gradient);
  color: white;
  padding: 100px 0;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.aqualuxe-hero::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('assets/images/wave-pattern.svg') repeat-x bottom;
  opacity: 0.1;
}

.aqualuxe-hero-content {
  position: relative;
  z-index: 2;
}

.aqualuxe-hero h1 {
  font-size: 3.5em;
  margin-bottom: 20px;
  color: white;
}

.aqualuxe-hero p {
  font-size: 1.2em;
  margin-bottom: 30px;
  opacity: 0.9;
}

/* Buttons */
.aqualuxe-btn {
  display: inline-block;
  padding: 15px 30px;
  background: var(--aqualuxe-accent);
  color: var(--aqualuxe-dark);
  text-decoration: none;
  border-radius: 50px;
  font-weight: 600;
  transition: var(--aqualuxe-transition);
  border: none;
  cursor: pointer;
}

.aqualuxe-btn:hover {
  background: #e6b800;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(255, 204, 0, 0.3);
}

.aqualuxe-btn-outline {
  background: transparent;
  color: white;
  border: 2px solid white;
}

.aqualuxe-btn-outline:hover {
  background: white;
  color: var(--aqualuxe-primary);
}

/* Product Grid */
.woocommerce ul.products {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 30px;
  margin: 0;
  padding: 0;
}

.woocommerce ul.products li.product {
  background: white;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
  transition: var(--aqualuxe-transition);
  margin: 0;
}

.woocommerce ul.products li.product:hover {
  transform: translateY(-5px);
  box-shadow: var(--aqualuxe-shadow);
}

.woocommerce ul.products li.product img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  transition: var(--aqualuxe-transition);
}

.woocommerce ul.products li.product:hover img {
  transform: scale(1.05);
}

.woocommerce ul.products li.product h2,
.woocommerce ul.products li.product h3 {
  padding: 15px;
  margin: 0;
  font-size: 1.1em;
}

.woocommerce ul.products li.product .price {
  padding: 0 15px;
  color: var(--aqualuxe-primary);
  font-weight: 700;
  font-size: 1.2em;
}

/* Product Single */
.single-product .summary {
  padding: 20px;
}

.single-product .woocommerce-product-gallery {
  border-radius: 15px;
  overflow: hidden;
}

/* Cart & Checkout */
.woocommerce-cart table.cart,
.woocommerce-checkout {
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

/* Features Section */
.aqualuxe-features {
  padding: 80px 0;
  background: var(--aqualuxe-light);
}

.aqualuxe-features-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 40px;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.aqualuxe-feature {
  text-align: center;
  padding: 30px 20px;
  background: white;
  border-radius: 15px;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
  transition: var(--aqualuxe-transition);
}

.aqualuxe-feature:hover {
  transform: translateY(-5px);
  box-shadow: var(--aqualuxe-shadow);
}

.aqualuxe-feature-icon {
  font-size: 3em;
  color: var(--aqualuxe-primary);
  margin-bottom: 20px;
}

/* Footer */
.site-footer {
  background: var(--aqualuxe-dark);
  color: white;
  padding: 60px 0 20px;
}

.site-footer a {
  color: var(--aqualuxe-secondary);
  text-decoration: none;
  transition: var(--aqualuxe-transition);
}

.site-footer a:hover {
  color: var(--aqualuxe-accent);
}

/* Responsive Design */
@media (max-width: 768px) {
  .aqualuxe-hero h1 {
    font-size: 2.5em;
  }

  .woocommerce ul.products {
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
  }

  .aqualuxe-features-grid {
    grid-template-columns: 1fr;
    gap: 20px;
  }
}

/* Loading Animation */
.aqualuxe-loading {
  display: inline-block;
  width: 20px;
  height: 20px;
  border: 2px solid rgba(0, 102, 204, 0.3);
  border-radius: 50%;
  border-top-color: var(--aqualuxe-primary);
  animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

/* Custom Scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: var(--aqualuxe-light);
}

::-webkit-scrollbar-thumb {
  background: var(--aqualuxe-primary);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: var(--aqualuxe-dark);
}
```

### functions.php

```php
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

// Theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_THEME_DIR', get_stylesheet_directory());
define('AQUALUXE_THEME_URI', get_stylesheet_directory_uri());

/**
 * Main AquaLuxe Theme Class
 */
class AquaLuxe_Theme {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('after_setup_theme', array($this, 'setup'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('init', array($this, 'init'));
        add_action('widgets_init', array($this, 'widgets_init'));

        // Include required files
        $this->includes();

        // Initialize demo content
        if (is_admin()) {
            require_once AQUALUXE_THEME_DIR . '/inc/demo-content.php';
        }
    }

    /**
     * Theme setup
     */
    public function setup() {
        // Add theme support
        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));
        add_theme_support('customize-selective-refresh-widgets');
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');

        // Register navigation menus
        register_nav_menus(array(
            'primary' => esc_html__('Primary', 'aqualuxe'),
            'footer' => esc_html__('Footer', 'aqualuxe'),
            'mobile' => esc_html__('Mobile', 'aqualuxe'),
        ));

        // Set content width
        if (!isset($content_width)) {
            $content_width = 1200;
        }

        // Load textdomain
        load_theme_textdomain('aqualuxe', AQUALUXE_THEME_DIR . '/languages');
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Parent theme style
        wp_enqueue_style('storefront-style', get_template_directory_uri() . '/style.css');

        // Child theme style
        wp_enqueue_style('aqualuxe-style',
            AQUALUXE_THEME_URI . '/style.css',
            array('storefront-style'),
            AQUALUXE_VERSION
        );

        // Google Fonts
        wp_enqueue_style('aqualuxe-fonts',
            'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap',
            array(),
            null
        );

        // Custom JavaScript
        wp_enqueue_script('aqualuxe-script',
            AQUALUXE_THEME_URI . '/assets/js/main.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );

        // Localize script
        wp_localize_script('aqualuxe-script', 'aqualuxe_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_nonce'),
        ));

        // Conditional scripts
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    /**
     * Initialize theme
     */
    public function init() {
        // Add image sizes
        add_image_size('aqualuxe-product-thumb', 300, 300, true);
        add_image_size('aqualuxe-product-large', 600, 600, true);
        add_image_size('aqualuxe-hero', 1920, 800, true);

        // Remove Storefront customizations we want to override
        remove_action('storefront_header', 'storefront_product_search', 40);
        remove_action('storefront_header', 'storefront_secondary_navigation', 30);

        // Add custom actions
        add_action('storefront_header', array($this, 'enhanced_search'), 40);
        add_action('wp_head', array($this, 'custom_styles'));
    }

    /**
     * Register widget areas
     */
    public function widgets_init() {
        register_sidebar(array(
            'name' => esc_html__('AquaLuxe Hero Section', 'aqualuxe'),
            'id' => 'aqualuxe-hero',
            'description' => esc_html__('Add widgets here to appear in the hero section.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ));

        register_sidebar(array(
            'name' => esc_html__('Shop Sidebar', 'aqualuxe'),
            'id' => 'shop-sidebar',
            'description' => esc_html__('Add widgets here to appear in the shop sidebar.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ));

        register_sidebar(array(
            'name' => esc_html__('Footer Column 1', 'aqualuxe'),
            'id' => 'footer-1',
            'description' => esc_html__('Add widgets here to appear in footer column 1.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ));

        register_sidebar(array(
            'name' => esc_html__('Footer Column 2', 'aqualuxe'),
            'id' => 'footer-2',
            'description' => esc_html__('Add widgets here to appear in footer column 2.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ));
    }

    /**
     * Include required files
     */
    private function includes() {
        require_once AQUALUXE_THEME_DIR . '/inc/customizer.php';
        require_once AQUALUXE_THEME_DIR . '/inc/woocommerce.php';
        require_once AQUALUXE_THEME_DIR . '/inc/template-functions.php';
        require_once AQUALUXE_THEME_DIR . '/inc/security.php';
        require_once AQUALUXE_THEME_DIR . '/inc/performance.php';
    }

    /**
     * Enhanced search form
     */
    public function enhanced_search() {
        if (function_exists('is_woocommerce') && (is_shop() || is_product_category() || is_product_tag())) {
            get_product_search_form();
        } else {
            get_search_form();
        }
    }

    /**
     * Custom styles in head
     */
    public function custom_styles() {
        $primary_color = get_theme_mod('aqualuxe_primary_color', '#0066cc');
        $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#00cccc');
        $accent_color = get_theme_mod('aqualuxe_accent_color', '#ffcc00');

        echo "<style id='aqualuxe-custom-styles'>
            :root {
                --aqualuxe-primary: {$primary_color};
                --aqualuxe-secondary: {$secondary_color};
                --aqualuxe-accent: {$accent_color};
                --aqualuxe-gradient: linear-gradient(135deg, {$primary_color} 0%, {$secondary_color} 100%);
            }
        </style>";
    }
}

// Initialize theme
new AquaLuxe_Theme();

/**
 * Utility Functions
 */

/**
 * Get theme option
 */
function aqualuxe_get_option($option, $default = '') {
    return get_theme_mod('aqualuxe_' . $option, $default);
}

/**
 * Display breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    if (function_exists('woocommerce_breadcrumb')) {
        woocommerce_breadcrumb();
    } else {
        // Custom breadcrumb implementation
        $separator = '<span class="sep">/</span>';
        $home = esc_html__('Home', 'aqualuxe');

        echo '<nav class="aqualuxe-breadcrumbs">';
        echo '<a href="' . home_url('/') . '">' . $home . '</a>' . $separator;

        if (is_single()) {
            $category = get_the_category();
            if ($category) {
                echo '<a href="' . get_category_link($category[0]->term_id) . '">' . $category[0]->name . '</a>' . $separator;
            }
            echo '<span>' . get_the_title() . '</span>';
        } elseif (is_page()) {
            echo '<span>' . get_the_title() . '</span>';
        } elseif (is_category()) {
            echo '<span>' . single_cat_title('', false) . '</span>';
        }

        echo '</nav>';
    }
}

/**
 * Custom excerpt length
 */
function aqualuxe_excerpt_length($length) {
    return is_admin() ? $length : 20;
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Custom excerpt more
 */
function aqualuxe_excerpt_more($more) {
    return is_admin() ? $more : '...';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Add custom body classes
 */
function aqualuxe_body_classes($classes) {
    $classes[] = 'aqualuxe-theme';

    if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
        $classes[] = 'woocommerce-page';
    }

    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Security enhancements
 */
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');

// Disable file editing
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}
```

### inc/customizer.php

````php
<?php
/**
 * AquaLuxe Customizer Settings
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Customizer Class
 */
class AquaLuxe_Customizer {

    public function __construct() {
        add_action('customize_register', array($this, 'register'));
        add_action('wp_head', array($this, 'header_output'));
        add_action('customize_preview_init', array($this, 'live_preview'));
    }

    /**
     * Register customizer settings
     */
    public function register($wp_customize) {

        // AquaLuxe Panel
        $wp_customize->add_panel('aqualuxe_panel', array(
            'title' => esc_html__('AquaLuxe Options', 'aqualuxe'),
            'description' => esc_html__('Customize your AquaLuxe theme', 'aqualuxe'),
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
            'default' => '#0066cc',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', array(
            'label' => esc_html__('Primary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'description' => esc_html__('Main brand color used throughout the site', 'aqualuxe'),
        )));

        // Secondary Color
        $wp_customize->add_setting('aqualuxe_secondary_color', array(
            'default' => '#00cccc',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', array(
            'label' => esc_html__('Secondary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'description' => esc_html__('Secondary color for gradients and accents', 'aqualuxe'),
        )));

        // Accent Color
        $wp_customize->add_setting('aqualuxe_accent_color', array(
            'default' => '#ffcc00',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', array(
            'label' => esc_html__('Accent Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'description' => esc_html__('Accent color for buttons and highlights', 'aqualuxe'),
        )));

        // Typography Section
        $wp_customize->add_section('aqualuxe_typography', array(
            'title' => esc_html__('Typography', 'aqualuxe'),
            'panel' => 'aqualuxe_panel',
            'priority' => 20,
        ));

        // Heading Font
        $wp_customize->add_setting('aqualuxe_heading_font', array(
            'default' => 'Poppins',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ));

        $wp_customize->add_control('aqualuxe_heading_font', array(
            'label' => esc_html__('Heading Font', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'select',
            'choices' => array(
                'Poppins' => 'Poppins',
                'Inter' => 'Inter',
                'Roboto' => 'Roboto',
                'Open Sans' => 'Open Sans',
                'Lato' => 'Lato',
            ),
        ));

        // Body Font
        $wp_customize->add_setting('aqualuxe_body_font', array(
            'default' => 'Inter',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ));

        $wp_customize->add_control('aqualuxe_body_font', array(
            'label' => esc_html__('Body Font', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'select',
            'choices' => array(
                'Inter' => 'Inter',
                'Poppins' => 'Poppins',
                'Roboto' => 'Roboto',
                'Open Sans' => 'Open Sans',
                'Lato' => 'Lato',
            ),
        ));

        // Header Section
        $wp_customize->add_section('aqualuxe_header', array(
            'title' => esc_html__('Header', 'aqualuxe'),
            'panel' => 'aqualuxe_panel',
            'priority' => 30,
        ));

        // Sticky Header
        $wp_customize->add_setting('aqualuxe_sticky_header', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'refresh',
        ));

        $wp_customize->add_control('aqualuxe_sticky_header', array(
            'label' => esc_html__('Enable Sticky Header', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type' => 'checkbox',
        ));

        // Shop Section
        $wp_customize->add_section('aqualuxe_shop', array(
            'title' => esc_html__('Shop Settings', 'aqualuxe'),
            'panel' => 'aqualuxe_panel',
            'priority' => 40,
        ));

        // Products per row
        $wp_customize->add_setting('aqualuxe_products_per_row', array(
            'default' => 4,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh',
        ));

        $wp_customize->add_control('aqualuxe_products_per_row', array(
            'label' => esc_html__('Products per row', 'aqualuxe'),
            'section' => 'aqualuxe_shop',
            'type' => 'select',
            'choices' => array(
                2 => '2',
                3 => '3',
                4 => '4',
                5 => '5',
            ),
        ));

        // Footer Section
        $wp_customize->add_section('aqualuxe_footer', array(
            'title' => esc_html__('Footer', 'aqualuxe'),
            'panel' => 'aqualuxe_panel',
            'priority' => 50,
        ));

        // Copyright Text
        $wp_customize->add_setting('aqualuxe_copyright_text', array(
            'default' => '© 2024 AquaLuxe. All rights reserved.',
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'refresh',
        ));

        $wp_customize->add_control('aqualuxe_copyright_text', array(
            'label' => esc_html__('Copyright Text', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type' => 'textarea',
        ));
    }

    /**
     * Output customizer styles
     */
    public function header_output() {
        $primary_color = get_theme_mod('aqualuxe_primary_color', '#0066cc');
        $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#00cccc');
        $accent_color = get_theme_mod('aqualuxe_accent_color', '#ffcc00');
        $heading_font = get_theme_mod('aqualuxe_heading_font', 'Poppins');
        $body_font = get_theme_mod('aqualuxe_body_font', 'Inter');

        echo '<style type="text/css" id="aqualuxe-customizer-styles">';
        echo ':root {';
        echo '--aqualuxe-primary: ' . esc_attr($primary_color) . ';';
        echo '--aqualuxe-secondary: ' . esc_attr($secondary_color) . ';';
        echo '--aqualuxe-accent: ' . esc_attr($accent_color) . ';';
        echo '--aqualuxe-gradient: linear-gradient(135deg, ' . esc_attr($primary_color) . ' 0%, ' . esc_attr($secondary_color) . ' 100%);';
        echo '}';
        echo 'h1, h2, h3, h4, h5, h6 { font-family: "' . esc_attr($heading_font) . '", sans-serif; }';
        echo 'body { font-family: "' . esc_attr($body_font) . '", sans-serif; }';
        echo '</style>';
    }

    /**
     * Live preview script
     */
    public function live_preview() {
        wp_enqueue_script('aqualuxe-customizer',
            AQUALUXE_THEME_URI . '/assets/js/customizer.js',
            array('jquery', 'customize-preview'),
            AQUALUXE_VERSION,
            true
        );
    }
}

new AquaLuxe_Customizer();

### inc/woocommerce.php
```php
<?php
/**
 * AquaLuxe WooCommerce Customizations
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_WooCommerce {

    public function __construct() {
        // Theme support
        add_action('after_setup_theme', array($this, 'woocommerce_support'));

        // Hooks
        add_filter('woocommerce_output_related_products_args', array($this, 'related_products_args'));
        add_filter('woocommerce_product_thumbnails_columns', array($this, 'thumbnail_columns'));
        add_filter('woocommerce_cross_sells_columns', array($this, 'cross_sells_columns'));
        add_filter('loop_shop_per_page', array($this, 'products_per_page'));
        add_filter('woocommerce_cross_sells_total', array($this, 'cross_sells_total'));

        // Remove default actions
        remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
        remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
        remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

        // Add custom actions
        add_action('woocommerce_before_main_content', array($this, 'wrapper_start'), 10);
        add_action('woocommerce_after_main_content', array($this, 'wrapper_end'), 10);

        // AJAX actions
        add_action('wp_ajax_aqualuxe_quick_view', array($this, 'quick_view_ajax'));
        add_action('wp_ajax_nopriv_aqualuxe_quick_view', array($this, 'quick_view_ajax'));

        // Product page enhancements
        add_action('woocommerce_single_product_summary', array($this, 'product_video'), 25);
        add_action('woocommerce_after_single_product_summary', array($this, 'product_tabs_enhancement'), 15);

        // Shop page enhancements
        add_action('woocommerce_before_shop_loop', array($this, 'shop_toolbar'), 15);
        add_action('woocommerce_before_shop_loop_item', array($this, 'product_badges'), 5);
        add_action('woocommerce_after_shop_loop_item', array($this, 'quick_view_button'), 15);

        // Cart enhancements
        add_filter('woocommerce_add_to_cart_fragments', array($this, 'cart_count_fragments'));

        // Checkout enhancements
        add_filter('woocommerce_checkout_fields', array($this, 'checkout_fields'));
    }

    /**
     * Add WooCommerce support
     */
    public function woocommerce_support() {
        add_theme_support('woocommerce', array(
            'thumbnail_image_width' => 300,
            'gallery_thumbnail_image_width' => 100,
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

        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }

    /**
     * Related products args
     */
    public function related_products_args($args) {
        $defaults = array(
            'posts_per_page' => 4,
            'columns' => 4,
        );

        $args = wp_parse_args($defaults, $args);
        return $args;
    }

    /**
     * Product thumbnail columns
     */
    public function thumbnail_columns() {
        return 4;
    }

    /**
     * Cross sells columns
     */
    public function cross_sells_columns($columns) {
        return 2;
    }

    /**
     * Products per page
     */
    public function products_per_page($cols) {
        return 12;
    }

    /**
     * Cross sells total
     */
    public function cross_sells_total($limit) {
        return 2;
    }

    /**
     * Wrapper start
     */
    public function wrapper_start() {
        echo '<main id="main" class="site-main aqualuxe-woocommerce-wrapper" role="main">';
        echo '<div class="container">';
    }

    /**
     * Wrapper end
     */
    public function wrapper_end() {
        echo '</div>';
        echo '</main>';
    }

    /**
     * Quick view AJAX
     */
    public function quick_view_ajax() {
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_nonce')) {
            wp_die();
        }

        $product_id = intval($_POST['product_id']);

        if (!$product_id) {
            wp_die();
        }

        global $post, $product;

        $post = get_post($product_id);
        $product = wc_get_product($product_id);

        ob_start();
        wc_get_template('single-product/quick-view.php');
        $output = ob_get_clean();

        wp_send_json_success($output);
    }

    /**
     * Product video
     */
    public function product_video() {
        global $product;

        $video_url = get_post_meta($product->get_id(), '_aqualuxe_product_video', true);

        if ($video_url) {
            echo '<div class="aqualuxe-product-video">';
            echo '<h3>' . esc_html__('Product Video', 'aqualuxe') . '</h3>';
            echo wp_oembed_get($video_url);
            echo '</div>';
        }
    }

    /**
     * Enhanced product tabs
     */
    public function product_tabs_enhancement() {
        global $product;

        $care_instructions = get_post_meta($product->get_id(), '_aqualuxe_care_instructions', true);
        $feeding_guide = get_post_meta($product->get_id(), '_aqualuxe_feeding_guide', true);

        if ($care_instructions || $feeding_guide) {
            echo '<div class="aqualuxe-product-extras">';

            if ($care_instructions) {
                echo '<div class="care-instructions">';
                echo '<h3>' . esc_html__('Care Instructions', 'aqualuxe') . '</h3>';
                echo wp_kses_post($care_instructions);
                echo '</div>';
            }

            if ($feeding_guide) {
                echo '<div class="feeding-guide">';
                echo '<h3>' . esc_html__('Feeding Guide', 'aqualuxe') . '</h3>';
                echo wp_kses_post($feeding_guide);
                echo '</div>';
            }

            echo '</div>';
        }
    }

    /**
     * Shop toolbar
     */
    public function shop_toolbar() {
        echo '<div class="aqualuxe-shop-toolbar">';
        echo '<div class="toolbar-left">';
        woocommerce_result_count();
        echo '</div>';
        echo '<div class="toolbar-right">';
        woocommerce_catalog_ordering();
        echo '</div>';
        echo '</div>';
    }

    /**
     * Product badges
     */
    public function product_badges() {
        global $product;

        echo '<div class="aqualuxe-product-badges">';

        if ($product->is_on_sale()) {
            $percentage = '';
            if ($product->get_regular_price() > 0) {
                $percentage = round((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100);
                $percentage = '-' . $percentage . '%';
            }
            echo '<span class="badge sale-badge">' . esc_html__('Sale', 'aqualuxe') . ' ' . $percentage . '</span>';
        }

        if (!$product->is_in_stock()) {
            echo '<span class="badge stock-badge">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
        }

        $new_days = 30; // Consider products new for 30 days
        $created = strtotime($product->get_date_created());
        if ((time() - $created) < ($new_days * DAY_IN_SECONDS)) {
            echo '<span class="badge new-badge">' . esc_html__('New', 'aqualuxe') . '</span>';
        }

        echo '</div>';
    }

    /**
     * Quick view button
     */
    public function quick_view_button() {
        global $product;

        echo '<a href="#" class="aqualuxe-quick-view" data-product-id="' . esc_attr($product->get_id()) . '">';
        echo '<i class="icon-eye"></i>';
        echo esc_html__('Quick View', 'aqualuxe');
        echo '</a>';
    }

    /**
     * Cart count fragments
     */
    public function cart_count_fragments($fragments) {
        $cart_count = WC()->cart->get_cart_contents_count();

        $fragments['span.cart-count'] = '<span class="cart-count">' . esc_html($cart_count) . '</span>';

        return $fragments;
    }

    /**
     * Checkout fields
     */
    public function checkout_fields($fields) {
        // Add aquarium size field
        $fields['billing']['billing_aquarium_size'] = array(
            'label' => esc_html__('Aquarium Size (Liters)', 'aqualuxe'),
            'placeholder' => esc_html__('Enter your aquarium size', 'aqualuxe'),
            'required' => false,
            'class' => array('form-row-wide'),
            'clear' => true,
            'type' => 'number',
            'custom_attributes' => array(
                'min' => '1',
                'step' => '1',
            ),
        );

        // Add fish keeping experience field
        $fields['billing']['billing_experience_level'] = array(
            'label' => esc_html__('Fish Keeping Experience', 'aqualuxe'),
            'required' => false,
            'class' => array('form-row-wide'),
            'clear' => true,
            'type' => 'select',
            'options' => array(
                '' => esc_html__('Select experience level', 'aqualuxe'),
                'beginner' => esc_html__('Beginner (0-1 years)', 'aqualuxe'),
                'intermediate' => esc_html__('Intermediate (1-5 years)', 'aqualuxe'),
                'advanced' => esc_html__('Advanced (5+ years)', 'aqualuxe'),
            ),
        );

        return $fields;
    }
}

new AquaLuxe_WooCommerce();

### inc/template-functions.php
```php
<?php
/**
 * AquaLuxe Template Functions
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display hero section
 */
function aqualuxe_hero_section() {
    $hero_title = get_theme_mod('aqualuxe_hero_title', 'Discover Premium Ornamental Fish');
    $hero_subtitle = get_theme_mod('aqualuxe_hero_subtitle', 'Transform your aquarium with our exquisite collection of ornamental fish');
    $hero_button_text = get_theme_mod('aqualuxe_hero_button_text', 'Shop Now');
    $hero_button_url = get_theme_mod('aqualuxe_hero_button_url', wc_get_page_permalink('shop'));
    $hero_image = get_theme_mod('aqualuxe_hero_image', '');

    if (is_front_page() && ($hero_title || $hero_subtitle)) {
        ?>
        <section class="aqualuxe-hero" <?php if ($hero_image) echo 'style="background-image: url(' . esc_url($hero_image) . ');"'; ?>>
            <div class="container">
                <div class="aqualuxe-hero-content">
                    <?php if ($hero_title) : ?>
                        <h1 class="hero-title"><?php echo esc_html($hero_title); ?></h1>
                    <?php endif; ?>

                    <?php if ($hero_subtitle) : ?>
                        <p class="hero-subtitle"><?php echo esc_html($hero_subtitle); ?></p>
                    <?php endif; ?>

                    <?php if ($hero_button_text && $hero_button_url) : ?>
                        <div class="hero-actions">
                            <a href="<?php echo esc_url($hero_button_url); ?>" class="aqualuxe-btn aqualuxe-btn-primary">
                                <?php echo esc_html($hero_button_text); ?>
                            </a>
                            <a href="#features" class="aqualuxe-btn aqualuxe-btn-outline">
                                <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php
    }
}

/**
 * Display features section
 */
function aqualuxe_features_section() {
    if (is_front_page()) {
        $features = array(
            array(
                'icon' => '🐠',
                'title' => esc_html__('Premium Quality', 'aqualuxe'),
                'description' => esc_html__('Hand-selected ornamental fish from certified breeders worldwide', 'aqualuxe'),
            ),
            array(
                'icon' => '🚚',
                'title' => esc_html__('Safe Delivery', 'aqualuxe'),
                'description' => esc_html__('Specialized packaging and express delivery to ensure fish arrive healthy', 'aqualuxe'),
            ),
            array(
                'icon' => '💬',
                'title' => esc_html__('Expert Support', 'aqualuxe'),
                'description' => esc_html__('Get advice from our aquarium experts for optimal fish care', 'aqualuxe'),
            ),
            array(
                'icon' => '🛡️',
                'title' => esc_html__('Health Guarantee', 'aqualuxe'),
                'description' => esc_html__('All fish come with a health guarantee for your peace of mind', 'aqualuxe'),
            ),
        );

        ?>
        <section id="features" class="aqualuxe-features">
            <div class="container">
                <div class="section-header">
                    <h2><?php esc_html_e('Why Choose AquaLuxe?', 'aqualuxe'); ?></h2>
                    <p><?php esc_html_e('We provide the finest ornamental fish and exceptional service', 'aqualuxe'); ?></p>
                </div>

                <div class="aqualuxe-features-grid">
                    <?php foreach ($features as $feature) : ?>
                        <div class="aqualuxe-feature">
                            <div class="aqualuxe-feature-icon"><?php echo $feature['icon']; ?></div>
                            <h3><?php echo esc_html($feature['title']); ?></h3>
                            <p><?php echo esc_html($feature['description']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }
}

/**
 * Display product categories
 */
function aqualuxe_product_categories() {
    if (is_front_page() && class_exists('WooCommerce')) {
        $categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'number' => 6,
            'orderby' => 'count',
            'order' => 'DESC',
        ));

        if ($categories && !is_wp_error($categories)) {
            ?>
            <section class="aqualuxe-categories">
                <div class="container">
                    <div class="section-header">
                        <h2><?php esc_html_e('Shop by Category', 'aqualuxe'); ?></h2>
                        <p><?php esc_html_e('Explore our diverse collection of ornamental fish', 'aqualuxe'); ?></p>
                    </div>

                    <div class="categories-grid">
                        <?php foreach ($categories as $category) : ?>
                            <?php
                            $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                            $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : wc_placeholder_img_src();
                            ?>
                            <div class="category-item">
                                <a href="<?php echo esc_url(get_term_link($category)); ?>" class="category-link">
                                    <div class="category-image">
                                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($category->name); ?>">
                                    </div>
                                    <div class="category-content">
                                        <h3><?php echo esc_html($category->name); ?></h3>
                                        <p><?php echo sprintf(_n('%d Product', '%d Products', $category->count, 'aqualuxe'), $category->count); ?></p>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
            <?php
        }
    }
}

/**
 * Display featured products
 */
function aqualuxe_featured_products() {
    if (is_front_page() && class_exists('WooCommerce')) {
        $featured_products = wc_get_featured_product_ids();

        if ($featured_products) {
            ?>
            <section class="aqualuxe-featured-products">
                <div class="container">
                    <div class="section-header">
                        <h2><?php esc_html_e('Featured Products', 'aqualuxe'); ?></h2>
                        <p><?php esc_html_e('Handpicked premium ornamental fish', 'aqualuxe'); ?></p>
                    </div>

                    <?php
                    echo do_shortcode('[products limit="8" columns="4" orderby="popularity" class="featured-products" ids="' . implode(',', $featured_products) . '"]');
                    ?>

                    <div class="section-footer">
                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="aqualuxe-btn aqualuxe-btn-outline">
                            <?php esc_html_e('View All Products', 'aqualuxe'); ?>
                        </a>
                    </div>
                </div>
            </section>
            <?php
        }
    }
}

/**
 * Display testimonials
 */
function aqualuxe_testimonials() {
    if (is_front_page()) {
        $testimonials = array(
            array(
                'name' => 'Sarah Johnson',
                'text' => 'Amazing quality fish and excellent customer service. My aquarium has never looked better!',
                'rating' => 5,
            ),
            array(
                'name' => 'Michael Chen',
                'text' => 'Fast delivery and healthy fish. The care instructions were very helpful.',
                'rating' => 5,
            ),
            array(
                'name' => 'Emma Rodriguez',
                'text' => 'Beautiful selection of ornamental fish. Highly recommend AquaLuxe!',
                'rating' => 5,
            ),
        );

        ?>
        <section class="aqualuxe-testimonials">
            <div class="container">
                <div class="section-header">
                    <h2><?php esc_html_e('What Our Customers Say', 'aqualuxe'); ?></h2>
                </div>

                <div class="testimonials-grid">
                    <?php foreach ($testimonials as $testimonial) : ?>
                        <div class="testimonial-item">
                            <div class="testimonial-content">
                                <div class="stars">
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <span class="star <?php echo $i <= $testimonial['rating'] ? 'filled' : ''; ?>">★</span>
                                    <?php endfor; ?>
                                </div>
                                <p>"<?php echo esc_html($testimonial['text']); ?>"</p>
                                <cite>— <?php echo esc_html($testimonial['name']); ?></cite>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }
}

/**
 * Display newsletter signup
 */
function aqualuxe_newsletter_signup() {
    if (is_front_page()) {
        ?>
        <section class="aqualuxe-newsletter">
            <div class="container">
                <div class="newsletter-content">
                    <div class="newsletter-text">
                        <h2><?php esc_html_e('Stay Updated', 'aqualuxe'); ?></h2>
                        <p><?php esc_html_e('Get the latest news about new arrivals and special offers', 'aqualuxe'); ?></p>
                    </div>
                    <div class="newsletter-form">
                        <form id="aqualuxe-newsletter-form" method="post">
                            <div class="form-group">
                                <input type="email" name="email" placeholder="<?php esc_attr_e('Enter your email', 'aqualuxe'); ?>" required>
                                <button type="submit" class="aqualuxe-btn">
                                    <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                                </button>
                            </div>
                            <?php wp_nonce_field('aqualuxe_newsletter', 'newsletter_nonce'); ?>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}

_next_posts_link()) {
        printf('<li>%s</li>' . "\n", get_next_posts_link('Next →'));
    }

    echo '</ul></div>' . "\n";
}
````
