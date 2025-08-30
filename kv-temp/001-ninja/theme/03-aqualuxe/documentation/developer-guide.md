# AquaLuxe WordPress Theme Developer Guide

## Table of Contents

1. [Introduction](#introduction)
2. [Theme Structure](#theme-structure)
3. [Core Classes](#core-classes)
4. [Template Hierarchy](#template-hierarchy)
5. [Hooks and Filters](#hooks-and-filters)
6. [Customizer API](#customizer-api)
7. [WooCommerce Integration](#woocommerce-integration)
8. [Multi-Currency Implementation](#multi-currency-implementation)
9. [International Shipping Implementation](#international-shipping-implementation)
10. [Custom Post Types](#custom-post-types)
11. [Performance Optimization](#performance-optimization)
12. [Security Implementation](#security-implementation)
13. [JavaScript Components](#javascript-components)
14. [CSS Architecture](#css-architecture)
15. [Extending the Theme](#extending-the-theme)

## Introduction

This developer guide provides detailed information about the AquaLuxe WordPress theme architecture, components, and how to extend or customize it. The theme follows WordPress coding standards and best practices, with an object-oriented approach for better organization and maintainability.

## Theme Structure

The AquaLuxe theme follows a modular structure with clear separation of concerns:

```
aqualuxe/
├── assets/
│   ├── css/
│   │   ├── main.css
│   │   ├── responsive.css
│   │   └── woocommerce.css
│   ├── js/
│   │   ├── admin/
│   │   │   └── shipping-zones.js
│   │   ├── customizer-controls.js
│   │   ├── customizer-preview.js
│   │   ├── currency-switcher.js
│   │   ├── main.js
│   │   └── woocommerce.js
│   └── images/
├── documentation/
│   ├── developer-guide.md
│   └── user-guide.md
├── inc/
│   ├── customizer/
│   │   ├── controls/
│   │   ├── sections/
│   │   └── class-aqualuxe-customizer.php
│   ├── helpers/
│   │   ├── accessibility.php
│   │   ├── lazy-loading.php
│   │   ├── open-graph.php
│   │   ├── schema-markup.php
│   │   ├── template-functions.php
│   │   └── template-tags.php
│   ├── post-types/
│   │   └── class-aqualuxe-fish-species.php
│   ├── widgets/
│   │   └── class-aqualuxe-widgets.php
│   ├── woocommerce/
│   │   ├── class-aqualuxe-woocommerce.php
│   │   ├── class-aqualuxe-multi-currency.php
│   │   ├── class-aqualuxe-international-shipping.php
│   │   ├── woocommerce-template-functions.php
│   │   └── woocommerce-template-hooks.php
│   ├── class-aqualuxe-assets.php
│   ├── class-aqualuxe-nav-menus.php
│   ├── class-aqualuxe-performance.php
│   ├── class-aqualuxe-security.php
│   ├── class-aqualuxe-setup.php
│   └── class-aqualuxe-walker-nav-menu.php
├── languages/
├── templates/
│   ├── content-none.php
│   ├── content-page.php
│   ├── content-search.php
│   ├── content-single.php
│   ├── content.php
│   ├── parts/
│   │   ├── footer-default.php
│   │   └── header-default.php
│   └── single-fish_species.php
├── woocommerce/
│   ├── loop/
│   │   └── filter.php
│   ├── quick-view.php
│   └── wishlist.php
├── 404.php
├── archive.php
├── comments.php
├── footer.php
├── functions.php
├── header.php
├── index.php
├── page.php
├── search.php
├── single.php
└── style.css
```

## Core Classes

AquaLuxe uses an object-oriented approach with several core classes:

### AquaLuxe_Theme

The main theme class that initializes all components and features.

```php
// functions.php
final class AquaLuxe_Theme {
    private static $_instance = null;
    
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function __construct() {
        $this->includes();
        $this->init_hooks();
    }
    
    // ...
}
```

### AquaLuxe_Setup

Handles theme setup, including features, menus, and sidebars.

```php
// inc/class-aqualuxe-setup.php
class AquaLuxe_Setup {
    public function __construct() {
        add_action( 'after_setup_theme', array( $this, 'setup' ) );
        add_action( 'widgets_init', array( $this, 'widgets_init' ) );
    }
    
    public function setup() {
        // Theme setup code
    }
    
    public function widgets_init() {
        // Register widget areas
    }
}
```

### AquaLuxe_Assets

Manages theme assets, including CSS and JavaScript files.

```php
// inc/class-aqualuxe-assets.php
class AquaLuxe_Assets {
    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }
    
    public function enqueue_styles() {
        // Enqueue styles
    }
    
    public function enqueue_scripts() {
        // Enqueue scripts
    }
}
```

### AquaLuxe_Customizer

Handles theme customization options using the WordPress Customizer API.

```php
// inc/customizer/class-aqualuxe-customizer.php
class AquaLuxe_Customizer {
    public function __construct() {
        $this->includes();
        add_action( 'customize_register', array( $this, 'register' ) );
    }
    
    private function includes() {
        // Include customizer files
    }
    
    public function register( $wp_customize ) {
        // Register customizer settings
    }
}
```

### AquaLuxe_WooCommerce

Integrates WooCommerce with the theme, adding custom styling and features.

```php
// inc/woocommerce/class-aqualuxe-woocommerce.php
class AquaLuxe_WooCommerce {
    public function __construct() {
        $this->includes();
        add_action( 'after_setup_theme', array( $this, 'setup' ) );
    }
    
    private function includes() {
        // Include WooCommerce files
    }
    
    public function setup() {
        // WooCommerce setup code
    }
}
```

## Template Hierarchy

AquaLuxe follows the WordPress template hierarchy with some custom templates:

- `index.php`: Main template file
- `archive.php`: Archive template
- `single.php`: Single post template
- `page.php`: Page template
- `search.php`: Search results template
- `404.php`: 404 error template
- `templates/content-*.php`: Content template parts
- `templates/parts/`: Header and footer parts
- `templates/single-fish_species.php`: Custom post type template

## Hooks and Filters

AquaLuxe provides several custom hooks and filters for extending the theme:

### Action Hooks

```php
// Before header
do_action( 'aqualuxe_before_header' );

// After header
do_action( 'aqualuxe_after_header' );

// Before footer
do_action( 'aqualuxe_before_footer' );

// After footer
do_action( 'aqualuxe_after_footer' );

// Before main content
do_action( 'aqualuxe_before_main_content' );

// After main content
do_action( 'aqualuxe_after_main_content' );

// Before sidebar
do_action( 'aqualuxe_before_sidebar' );

// After sidebar
do_action( 'aqualuxe_after_sidebar' );

// Before entry content
do_action( 'aqualuxe_before_entry_content' );

// After entry content
do_action( 'aqualuxe_after_entry_content' );
```

### Filter Hooks

```php
// Modify body classes
$classes = apply_filters( 'aqualuxe_body_classes', $classes );

// Modify post classes
$classes = apply_filters( 'aqualuxe_post_classes', $classes );

// Modify sidebar position
$position = apply_filters( 'aqualuxe_sidebar_position', $position );

// Modify excerpt length
$length = apply_filters( 'aqualuxe_excerpt_length', $length );

// Modify excerpt more
$more = apply_filters( 'aqualuxe_excerpt_more', $more );
```

## Customizer API

AquaLuxe uses the WordPress Customizer API for theme options. The customizer is organized into panels, sections, and settings:

### Panels

- General Settings
- Header
- Footer
- Typography
- Colors
- Blog
- WooCommerce

### Sections

Each panel contains multiple sections. For example, the WooCommerce panel includes:

- Shop
- Product
- Cart
- Checkout
- My Account
- Features

### Custom Controls

AquaLuxe includes several custom Customizer controls:

- Toggle Control
- Slider Control
- Color Alpha Control
- Typography Control
- Sortable Control
- Multi-Select Control

Example of adding a custom control:

```php
// Register custom control type
$wp_customize->register_control_type( 'AquaLuxe_Customizer_Control_Toggle' );

// Add setting
$wp_customize->add_setting(
    'aqualuxe_multi_currency',
    array(
        'default'           => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    )
);

// Add control
$wp_customize->add_control(
    new AquaLuxe_Customizer_Control_Toggle(
        $wp_customize,
        'aqualuxe_multi_currency',
        array(
            'label'    => esc_html__( 'Enable Multi-Currency Support', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_features',
            'priority' => 10,
        )
    )
);
```

## WooCommerce Integration

AquaLuxe includes comprehensive WooCommerce integration:

### Template Overrides

The theme overrides several WooCommerce templates:

- `woocommerce/loop/filter.php`: Custom product filter
- `woocommerce/quick-view.php`: Quick view template
- `woocommerce/wishlist.php`: Wishlist template

### Custom Features

- Quick View: Preview products without leaving the shop page
- Wishlist: Save products for later
- AJAX Add to Cart: Add products to cart without page refresh
- Advanced Filtering: Filter products by various attributes

### Hooks and Filters

The theme adds custom hooks and filters for WooCommerce:

```php
// Before shop loop
do_action( 'aqualuxe_before_shop_loop' );

// After shop loop
do_action( 'aqualuxe_after_shop_loop' );

// Before single product
do_action( 'aqualuxe_before_single_product' );

// After single product
do_action( 'aqualuxe_after_single_product' );
```

## Multi-Currency Implementation

AquaLuxe includes a multi-currency system for WooCommerce:

### Currency Switcher

The currency switcher is implemented in `class-aqualuxe-multi-currency.php`:

```php
class AquaLuxe_Multi_Currency {
    public function __construct() {
        // Check if multi-currency is enabled
        if ( ! get_theme_mod( 'aqualuxe_multi_currency', false ) ) {
            return;
        }

        // Add currency switcher
        add_action( 'woocommerce_before_shop_loop', array( $this, 'add_currency_switcher' ), 15 );
        add_action( 'woocommerce_before_single_product', array( $this, 'add_currency_switcher' ), 5 );
        
        // Handle currency switching
        add_filter( 'woocommerce_currency', array( $this, 'change_currency' ) );
        add_filter( 'woocommerce_currency_symbol', array( $this, 'change_currency_symbol' ), 10, 2 );
        
        // Handle price conversion
        add_filter( 'woocommerce_product_get_price', array( $this, 'convert_price' ), 10, 2 );
        // ...
    }
    
    // ...
}
```

### Price Conversion

Prices are converted based on exchange rates set in the Customizer:

```php
public function convert_price( $price, $product ) {
    // Skip if price is empty
    if ( '' === $price || 0 === $price ) {
        return $price;
    }
    
    $base_currency = get_option( 'woocommerce_currency' );
    $current_currency = $this->get_current_currency();
    
    // Skip if currency is the same
    if ( $base_currency === $current_currency ) {
        return $price;
    }
    
    // Get exchange rate
    $exchange_rate = $this->get_exchange_rate( $current_currency );
    
    // Convert price
    $converted_price = $price * $exchange_rate;
    
    return $converted_price;
}
```

## International Shipping Implementation

AquaLuxe includes an international shipping optimization system:

### Shipping Zones

Shipping zones are defined in `class-aqualuxe-international-shipping.php`:

```php
public function get_shipping_zones() {
    return array(
        'europe' => array(
            'name'           => esc_html__( 'Europe', 'aqualuxe' ),
            'countries'      => WC()->countries->get_european_union_countries(),
            'rate_adjustment' => get_theme_mod( 'aqualuxe_shipping_rate_europe', 1.0 ),
            'delivery_time'   => get_theme_mod( 'aqualuxe_shipping_time_europe', esc_html__( '5-7 business days', 'aqualuxe' ) ),
        ),
        'north_america' => array(
            'name'           => esc_html__( 'North America', 'aqualuxe' ),
            'countries'      => array( 'US', 'CA', 'MX' ),
            'rate_adjustment' => get_theme_mod( 'aqualuxe_shipping_rate_north_america', 1.2 ),
            'delivery_time'   => get_theme_mod( 'aqualuxe_shipping_time_north_america', esc_html__( '7-10 business days', 'aqualuxe' ) ),
        ),
        // ...
    );
}
```

### Shipping Rate Optimization

Shipping rates are optimized based on the customer's location:

```php
public function optimize_shipping_rates( $rates, $package ) {
    // Get customer country
    $customer_country = $package['destination']['country'];
    
    // Get store country
    $store_country = WC()->countries->get_base_country();
    
    // Check if international shipping
    if ( $customer_country !== $store_country ) {
        // Get international shipping zones
        $shipping_zones = $this->get_shipping_zones();
        
        // Find the zone for the customer country
        $customer_zone = 'rest_of_world';
        
        foreach ( $shipping_zones as $zone_id => $zone_data ) {
            if ( in_array( $customer_country, $zone_data['countries'], true ) ) {
                $customer_zone = $zone_id;
                break;
            }
        }
        
        // Apply zone-specific rates
        foreach ( $rates as $rate_id => $rate ) {
            // Get rate adjustment from theme mod
            $adjustment = get_theme_mod( 'aqualuxe_shipping_rate_' . $customer_zone, $shipping_zones[ $customer_zone ]['rate_adjustment'] );
            
            // Apply adjustment
            $rates[ $rate_id ]->cost *= $adjustment;
            
            // Update label to indicate international shipping
            $rates[ $rate_id ]->label = sprintf(
                /* translators: %s: shipping method label */
                esc_html__( 'International: %s', 'aqualuxe' ),
                $rates[ $rate_id ]->label
            );
            
            // Add meta data for estimated delivery time
            $delivery_time = get_theme_mod( 'aqualuxe_shipping_time_' . $customer_zone, $shipping_zones[ $customer_zone ]['delivery_time'] );
            $rates[ $rate_id ]->add_meta_data( 'delivery_time', $delivery_time );
            
            // Add meta data for shipping zone
            $rates[ $rate_id ]->add_meta_data( 'shipping_zone', $shipping_zones[ $customer_zone ]['name'] );
        }
    }
    
    return $rates;
}
```

## Custom Post Types

AquaLuxe includes a custom post type for fish species:

### Fish Species CPT

The Fish Species CPT is defined in `class-aqualuxe-fish-species.php`:

```php
class AquaLuxe_Fish_Species {
    public function __construct() {
        // Register post type
        add_action( 'init', array( $this, 'register_post_type' ) );
        
        // Register taxonomies
        add_action( 'init', array( $this, 'register_taxonomies' ) );
        
        // Add meta boxes
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        
        // Save meta box data
        add_action( 'save_post', array( $this, 'save_meta_box_data' ) );
        
        // Add admin columns
        add_filter( 'manage_fish_species_posts_columns', array( $this, 'set_custom_columns' ) );
        add_action( 'manage_fish_species_posts_custom_column', array( $this, 'custom_column' ), 10, 2 );
        
        // Add shortcode
        add_shortcode( 'fish_species', array( $this, 'shortcode' ) );
    }
    
    public function register_post_type() {
        $labels = array(
            'name'               => _x( 'Fish Species', 'post type general name', 'aqualuxe' ),
            'singular_name'      => _x( 'Fish Species', 'post type singular name', 'aqualuxe' ),
            'menu_name'          => _x( 'Fish Species', 'admin menu', 'aqualuxe' ),
            'name_admin_bar'     => _x( 'Fish Species', 'add new on admin bar', 'aqualuxe' ),
            'add_new'            => _x( 'Add New', 'fish species', 'aqualuxe' ),
            'add_new_item'       => __( 'Add New Fish Species', 'aqualuxe' ),
            'new_item'           => __( 'New Fish Species', 'aqualuxe' ),
            'edit_item'          => __( 'Edit Fish Species', 'aqualuxe' ),
            'view_item'          => __( 'View Fish Species', 'aqualuxe' ),
            'all_items'          => __( 'All Fish Species', 'aqualuxe' ),
            'search_items'       => __( 'Search Fish Species', 'aqualuxe' ),
            'parent_item_colon'  => __( 'Parent Fish Species:', 'aqualuxe' ),
            'not_found'          => __( 'No fish species found.', 'aqualuxe' ),
            'not_found_in_trash' => __( 'No fish species found in Trash.', 'aqualuxe' ),
        );
        
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'fish-species' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-fish',
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
        );
        
        register_post_type( 'fish_species', $args );
    }
    
    // ...
}
```

## Performance Optimization

AquaLuxe includes several performance optimization features:

### Asset Minification

CSS and JavaScript files are minified to reduce file size:

```php
public function minify_css( $css ) {
    // Remove comments
    $css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
    
    // Remove space after colons
    $css = str_replace( ': ', ':', $css );
    
    // Remove whitespace
    $css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );
    
    return $css;
}
```

### Image Optimization

Images are automatically optimized and lazy-loaded:

```php
public function add_image_loading_attribute( $attributes, $attachment, $size ) {
    // Skip if image is in admin
    if ( is_admin() ) {
        return $attributes;
    }
    
    // Add loading attribute
    $attributes['loading'] = 'lazy';
    
    return $attributes;
}
```

### Critical CSS

Above-the-fold CSS is inlined for faster initial rendering:

```php
public function add_critical_css() {
    // Skip if in admin
    if ( is_admin() ) {
        return;
    }
    
    // Get critical CSS
    $critical_css = $this->get_critical_css();
    
    // Output critical CSS
    if ( ! empty( $critical_css ) ) {
        echo '<style id="aqualuxe-critical-css">' . $this->minify_css( $critical_css ) . '</style>';
    }
}
```

## Security Implementation

AquaLuxe includes enhanced security features:

### Input Sanitization

All user inputs are properly sanitized:

```php
public static function sanitize_input( $input, $type = 'text' ) {
    switch ( $type ) {
        case 'text':
            return sanitize_text_field( $input );
        case 'email':
            return sanitize_email( $input );
        case 'url':
            return esc_url_raw( $input );
        case 'textarea':
            return sanitize_textarea_field( $input );
        case 'html':
            return wp_kses_post( $input );
        case 'int':
            return intval( $input );
        case 'float':
            return floatval( $input );
        case 'bool':
            return (bool) $input;
        case 'array':
            // Handle array sanitization
            // ...
        default:
            return sanitize_text_field( $input );
    }
}
```

### Data Escaping

All outputs are properly escaped:

```php
public static function escape_output( $output, $type = 'html' ) {
    switch ( $type ) {
        case 'html':
            return wp_kses_post( $output );
        case 'attr':
            return esc_attr( $output );
        case 'url':
            return esc_url( $output );
        case 'js':
            return esc_js( $output );
        case 'textarea':
            return esc_textarea( $output );
        case 'json':
            return esc_html( wp_json_encode( $output ) );
        default:
            return esc_html( $output );
    }
}
```

### Nonce Verification

Forms and AJAX requests include nonce verification:

```php
public function verify_ajax_request() {
    // Check nonce
    if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'aqualuxe-ajax-nonce' ) ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Security check failed', 'aqualuxe' ) ) );
    }
    
    // Process request
    // ...
}
```

## JavaScript Components

AquaLuxe includes several JavaScript components:

### Main JavaScript

The main JavaScript file (`main.js`) handles core functionality:

```javascript
var AquaLuxe = {
    init: function() {
        this.mobileMenu();
        this.stickyHeader();
        this.smoothScroll();
        this.backToTop();
    },
    
    mobileMenu: function() {
        // Mobile menu functionality
    },
    
    stickyHeader: function() {
        // Sticky header functionality
    },
    
    smoothScroll: function() {
        // Smooth scroll functionality
    },
    
    backToTop: function() {
        // Back to top functionality
    }
};

jQuery(document).ready(function($) {
    AquaLuxe.init();
});
```

### WooCommerce JavaScript

The WooCommerce JavaScript file (`woocommerce.js`) handles WooCommerce-specific functionality:

```javascript
var AquaLuxeWooCommerce = {
    init: function() {
        this.quantityButtons();
        this.productGallery();
        this.productTabs();
        this.ajaxAddToCart();
        this.quickView();
        this.wishlist();
        this.productFilter();
        this.viewSwitcher();
        this.currencySwitcher();
    },
    
    // Component methods
    // ...
};

jQuery(document).ready(function($) {
    AquaLuxeWooCommerce.init();
});
```

## CSS Architecture

AquaLuxe uses a modular CSS architecture:

### Main CSS

The main CSS file (`main.css`) includes:

- Reset and normalize styles
- Typography
- Grid system
- Layout components
- UI components
- Utility classes

### Responsive CSS

The responsive CSS file (`responsive.css`) includes media queries for different screen sizes:

- Large desktop (1200px and up)
- Desktop (992px to 1199px)
- Tablet (768px to 991px)
- Mobile (767px and below)
- Small mobile (576px and below)

### WooCommerce CSS

The WooCommerce CSS file (`woocommerce.css`) includes styles for WooCommerce components:

- Shop page
- Product listings
- Single product
- Cart
- Checkout
- My Account
- Custom features (quick view, wishlist, etc.)

## Extending the Theme

### Creating a Child Theme

To create a child theme for AquaLuxe:

1. Create a new folder in the `wp-content/themes` directory (e.g., `aqualuxe-child`)
2. Create a `style.css` file with the following header:

```css
/*
Theme Name: AquaLuxe Child
Theme URI: https://aqualuxetheme.com/
Description: Child theme for AquaLuxe
Author: Your Name
Author URI: https://yourwebsite.com/
Template: aqualuxe
Version: 1.0.0
Text Domain: aqualuxe-child
*/
```

3. Create a `functions.php` file:

```php
<?php
/**
 * AquaLuxe Child Theme functions and definitions
 */

// Enqueue parent and child theme styles
function aqualuxe_child_enqueue_styles() {
    wp_enqueue_style( 'aqualuxe-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'aqualuxe-child-style', get_stylesheet_directory_uri() . '/style.css', array( 'aqualuxe-style' ) );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles' );

// Add custom functionality here
```

### Using Hooks and Filters

Example of using AquaLuxe hooks and filters:

```php
// Add content before header
function my_custom_before_header() {
    echo '<div class="announcement-bar">Special offer: Free shipping on orders over $50!</div>';
}
add_action( 'aqualuxe_before_header', 'my_custom_before_header' );

// Modify body classes
function my_custom_body_classes( $classes ) {
    $classes[] = 'custom-class';
    return $classes;
}
add_filter( 'aqualuxe_body_classes', 'my_custom_body_classes' );
```

### Customizing Templates

To customize a template file:

1. Copy the template file from the parent theme to your child theme
2. Modify the file as needed
3. The child theme template will automatically override the parent theme template

For example, to customize the single post template:

1. Copy `single.php` from the parent theme to your child theme
2. Modify the file as needed

### Customizing WooCommerce Templates

To customize a WooCommerce template:

1. Create a `woocommerce` folder in your child theme
2. Copy the template file from the parent theme's `woocommerce` folder to your child theme's `woocommerce` folder
3. Modify the file as needed

For example, to customize the quick view template:

1. Copy `woocommerce/quick-view.php` from the parent theme to your child theme's `woocommerce` folder
2. Modify the file as needed