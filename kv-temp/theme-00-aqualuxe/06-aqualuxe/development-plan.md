# AquaLuxe WooCommerce Child Theme Development Plan

## Theme Information
- **Theme Name**: AquaLuxe
- **Parent Theme**: Storefront
- **Description**: Premium WooCommerce Child Theme for Ornamental Fish Business
- **Author**: Kasun Vimarshana
- **Version**: 1.0.0

## Folder Structure
```
aqualuxe/
├── style.css
├── functions.php
├── screenshot.png
├── readme.txt
├── readme.md
├── assets/
│   ├── css/
│   │   ├── customizer.css
│   │   └── aqualuxe-styles.css
│   ├── js/
│   │   ├── navigation.js
│   │   ├── customizer.js
│   │   └── aqualuxe-scripts.js
│   └── images/
│       └── (theme images)
├── inc/
│   ├── customizer.php
│   ├── template-hooks.php
│   ├── template-functions.php
│   └── class-aqualuxe.php
├── templates/
│   ├── global/
│   ├── single/
│   ├── archive/
│   └── parts/
├── woocommerce/
│   ├── cart/
│   ├── checkout/
│   ├── global/
│   ├── loop/
│   ├── myaccount/
│   ├── single-product/
│   └── archive-product.php
└── template-parts/
    ├── header/
    ├── footer/
    └── content/
```

## Required Files

### 1. style.css
```css
/*
Theme Name: AquaLuxe
Theme URI: https://github.com/kasunvimarshana
Description: Premium WooCommerce Child Theme for Ornamental Fish Business
Author: Kasun Vimarshana
Author URI: https://github.com/kasunvimarshana
Template: storefront
Version: 1.0.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: aqualuxe
Tags: e-commerce, woocommerce, responsive, clean, modern, multipurpose
*/

/* Import Storefront styles */
@import url("../storefront/style.css");

/* Theme custom styles */
/* Add custom CSS here */
```

### 2. functions.php
```php
<?php
/**
 * AquaLuxe Child Theme Functions
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_THEME_DIR', get_stylesheet_directory());
define('AQUALUXE_THEME_URI', get_stylesheet_directory_uri());

// Theme setup
if (!function_exists('aqualuxe_setup')) {
    function aqualuxe_setup() {
        // Load text domain
        load_child_theme_textdomain('aqualuxe', get_stylesheet_directory() . '/languages');
        
        // Add theme support
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        add_theme_support('custom-logo', array(
            'height'      => 100,
            'width'       => 400,
            'flex-height' => true,
            'flex-width'  => true,
            'header-text' => array('site-title', 'site-description'),
        ));
        add_theme_support('custom-header');
        add_theme_support('custom-background');
        add_theme_support('title-tag');
        add_theme_support('automatic-feed-links');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ));
        
        // Add image sizes
        add_image_size('aqualuxe-product-image', 600, 600, true);
    }
}
add_action('after_setup_theme', 'aqualuxe_setup');

// Enqueue scripts and styles
if (!function_exists('aqualuxe_enqueue_scripts')) {
    function aqualuxe_enqueue_scripts() {
        // Enqueue Storefront parent theme styles
        wp_enqueue_style('storefront-style', get_template_directory_uri() . '/style.css');
        
        // Enqueue child theme styles
        wp_enqueue_style('aqualuxe-style', get_stylesheet_uri(), array('storefront-style'), AQUALUXE_VERSION);
        
        // Enqueue custom styles
        wp_enqueue_style('aqualuxe-custom-style', get_stylesheet_directory_uri() . '/assets/css/aqualuxe-styles.css', array(), AQUALUXE_VERSION);
        
        // Enqueue scripts
        wp_enqueue_script('aqualuxe-navigation', get_stylesheet_directory_uri() . '/assets/js/navigation.js', array('jquery'), AQUALUXE_VERSION, true);
        wp_enqueue_script('aqualuxe-scripts', get_stylesheet_directory_uri() . '/assets/js/aqualuxe-scripts.js', array('jquery'), AQUALUXE_VERSION, true);
        
        // Localize script for AJAX
        wp_localize_script('aqualuxe-scripts', 'aqualuxe_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('aqualuxe_nonce'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_scripts');

// Enqueue customizer scripts
if (!function_exists('aqualuxe_customizer_scripts')) {
    function aqualuxe_customizer_scripts() {
        wp_enqueue_script('aqualuxe-customizer', get_stylesheet_directory_uri() . '/assets/js/customizer.js', array('jquery', 'customize-controls'), AQUALUXE_VERSION, true);
    }
}
add_action('customize_controls_enqueue_scripts', 'aqualuxe_customizer_scripts');

// Include customizer
if (is_admin()) {
    require_once get_stylesheet_directory() . '/inc/customizer.php';
}

// Include template functions
require_once get_stylesheet_directory() . '/inc/template-functions.php';

// Include template hooks
require_once get_stylesheet_directory() . '/inc/template-hooks.php';

// Include main theme class
require_once get_stylesheet_directory() . '/inc/class-aqualuxe.php';

// Initialize theme
AquaLuxe::get_instance();
?>
```

### 3. Template Files

#### header.php
```php
<?php
/**
 * The header for our theme
 *
 * @package AquaLuxe
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'aqualuxe'); ?></a>

    <header id="masthead" class="site-header" role="banner">
        <?php
        /**
         * Functions hooked into storefront_header action
         *
         * @hooked storefront_header_container                 - 0
         * @hooked storefront_skip_links                         - 5
         * @hooked storefront_social_icons                       - 10
         * @hooked storefront_site_branding                     - 20
         * @hooked storefront_secondary_navigation              - 30
         * @hooked storefront_header_container_close            - 40
         * @hooked storefront_primary_navigation_wrapper         - 42
         * @hooked storefront_primary_navigation                - 50
         * @hooked storefront_header_cart                       - 60
         * @hooked storefront_primary_navigation_wrapper_close  - 68
         */
        do_action('storefront_header');
        ?>
    </header><!-- #masthead -->

    <?php
    /**
     * Functions hooked in to storefront_before_content
     *
     * @hooked storefront_header_widget_region - 10
     * @hooked storefront_before_content - 10
     */
    do_action('storefront_before_content');
    ?>

    <div id="content" class="site-content" tabindex="-1">
        <div class="col-full">
            <?php
            /**
             * Functions hooked in to storefront_content_top
             *
             * @hooked woocommerce_breadcrumb - 10
             */
            do_action('storefront_content_top');
            ?>