# AquaLuxe Theme - Developer Documentation

## Table of Contents
1. [Introduction](#introduction)
2. [Theme Structure](#theme-structure)
3. [Theme Features](#theme-features)
4. [Theme Hooks](#theme-hooks)
5. [Template Files](#template-files)
6. [WooCommerce Integration](#woocommerce-integration)
7. [Customization](#customization)
8. [Multilingual & Multi-currency](#multilingual--multi-currency)
9. [Multivendor Support](#multivendor-support)
10. [Multitenant Architecture](#multitenant-architecture)
11. [Asset Management](#asset-management)
12. [Performance Optimization](#performance-optimization)
13. [Security](#security)
14. [Extending the Theme](#extending-the-theme)

## Introduction

AquaLuxe is a premium WordPress theme designed for aquatic-related e-commerce websites. It features a dual-state architecture that works seamlessly with or without WooCommerce enabled, and supports multilingual, multi-currency, multivendor, and multitenant functionality.

This documentation is intended for developers who want to customize or extend the AquaLuxe theme.

## Theme Structure

The AquaLuxe theme follows a modular structure for better organization and maintainability:

```
aqualuxe-theme/
├── assets/
│   ├── admin/
│   │   ├── css/
│   │   └── js/
│   ├── dist/
│   │   ├── css/
│   │   ├── fonts/
│   │   ├── images/
│   │   └── js/
│   └── src/
│       ├── css/
│       │   └── components/
│       ├── fonts/
│       ├── images/
│       └── js/
├── documentation/
├── inc/
│   ├── customizer.php
│   ├── enqueue.php
│   ├── helpers.php
│   ├── hooks.php
│   ├── multilingual.php
│   ├── multitenant.php
│   ├── multivendor.php
│   ├── setup.php
│   ├── template-functions.php
│   ├── template-tags.php
│   ├── widgets.php
│   └── woocommerce.php
├── languages/
├── templates/
│   ├── content-archive.php
│   ├── content-none.php
│   ├── content-page.php
│   ├── content-search.php
│   ├── content-single.php
│   └── content.php
├── woocommerce/
│   ├── archive-product.php
│   ├── cart/
│   ├── cart-drawer.php
│   ├── checkout/
│   ├── content-product.php
│   ├── content-single-product.php
│   ├── product-filters.php
│   ├── quick-view.php
│   ├── single-product.php
│   └── wishlist.php
├── 404.php
├── archive.php
├── comments.php
├── footer.php
├── functions.php
├── header.php
├── index.php
├── package.json
├── page.php
├── search.php
├── sidebar.php
├── single.php
├── style.css
├── tailwind.config.js
└── webpack.mix.js
```

## Theme Features

AquaLuxe includes the following features:

- Responsive design using Tailwind CSS
- Dark mode support with toggle
- WooCommerce integration with custom templates
- Advanced product filtering
- Quick view functionality
- Wishlist system
- AJAX cart with drawer
- Multilingual support (WPML/Polylang)
- Multi-currency support
- Multivendor support
- Multitenant architecture
- Customizer options for theme settings
- Performance optimization
- Accessibility compliance
- SEO optimization

## Theme Hooks

AquaLuxe uses a comprehensive hooks system to allow for modular customization. The main hook groups are:

### Header Hooks
- `aqualuxe_before_header`: Actions before the header
- `aqualuxe_header`: Actions within the header
- `aqualuxe_after_header`: Actions after the header

### Content Hooks
- `aqualuxe_before_main_content`: Actions before the main content
- `aqualuxe_entry_header`: Actions within the entry header
- `aqualuxe_entry_content`: Actions within the entry content
- `aqualuxe_entry_footer`: Actions within the entry footer
- `aqualuxe_after_main_content`: Actions after the main content

### Footer Hooks
- `aqualuxe_before_footer`: Actions before the footer
- `aqualuxe_footer`: Actions within the footer
- `aqualuxe_after_footer`: Actions after the footer

### WooCommerce Hooks
- `aqualuxe_before_shop_loop`: Actions before the shop loop
- `aqualuxe_after_shop_loop`: Actions after the shop loop
- `aqualuxe_before_single_product`: Actions before the single product
- `aqualuxe_after_single_product`: Actions after the single product

### Example Usage

```php
// Add a custom element to the header
function my_custom_header_element() {
    echo '<div class="custom-element">Custom content</div>';
}
add_action('aqualuxe_header', 'my_custom_header_element', 25);
```

## Template Files

AquaLuxe includes the following template files:

### Core Templates
- `index.php`: The main template file
- `header.php`: The header template
- `footer.php`: The footer template
- `sidebar.php`: The sidebar template
- `single.php`: Template for single posts
- `page.php`: Template for pages
- `archive.php`: Template for archives
- `search.php`: Template for search results
- `404.php`: Template for 404 pages
- `comments.php`: Template for comments

### Content Templates
- `templates/content.php`: Default content template
- `templates/content-single.php`: Content template for single posts
- `templates/content-page.php`: Content template for pages
- `templates/content-archive.php`: Content template for archives
- `templates/content-search.php`: Content template for search results
- `templates/content-none.php`: Content template when no content is found

### WooCommerce Templates
- `woocommerce/single-product.php`: Template for single products
- `woocommerce/archive-product.php`: Template for product archives
- `woocommerce/content-product.php`: Template for product content in loops
- `woocommerce/content-single-product.php`: Template for single product content
- `woocommerce/cart/cart.php`: Template for the cart page
- `woocommerce/checkout/form-checkout.php`: Template for the checkout form
- `woocommerce/product-filters.php`: Template for product filters
- `woocommerce/quick-view.php`: Template for product quick view
- `woocommerce/cart-drawer.php`: Template for the cart drawer
- `woocommerce/wishlist.php`: Template for the wishlist

## WooCommerce Integration

AquaLuxe is designed to work seamlessly with WooCommerce. The theme includes custom templates and functionality for WooCommerce, but also works without WooCommerce activated.

### WooCommerce Features
- Custom product templates
- Advanced product filtering
- Quick view functionality
- Wishlist system
- AJAX cart with drawer
- Multi-currency support
- Vendor information display
- Estimated delivery calculations
- Product availability indicators

### WooCommerce Hooks

The theme uses WooCommerce hooks to customize the display of products and other WooCommerce elements. Here are some examples:

```php
// Remove default WooCommerce styles
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// Customize number of products per row
function aqualuxe_loop_columns() {
    return get_theme_mod('aqualuxe_products_per_row', 4);
}
add_filter('loop_shop_columns', 'aqualuxe_loop_columns');

// Customize number of products per page
function aqualuxe_products_per_page() {
    return get_theme_mod('aqualuxe_products_per_page', 12);
}
add_filter('loop_shop_per_page', 'aqualuxe_products_per_page');
```

## Customization

AquaLuxe includes a comprehensive customizer with options for:

- Colors
- Typography
- Layout
- Header
- Footer
- Blog
- WooCommerce
- Social Media
- Performance

### Adding Custom Customizer Options

You can add custom customizer options by using the `customize_register` hook:

```php
function my_custom_customizer_options($wp_customize) {
    // Add a new section
    $wp_customize->add_section(
        'my_custom_section',
        array(
            'title'    => __('My Custom Section', 'aqualuxe'),
            'panel'    => 'aqualuxe_theme_options',
            'priority' => 100,
        )
    );

    // Add a setting
    $wp_customize->add_setting(
        'my_custom_setting',
        array(
            'default'           => 'default_value',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    // Add a control
    $wp_customize->add_control(
        'my_custom_setting',
        array(
            'label'    => __('My Custom Setting', 'aqualuxe'),
            'section'  => 'my_custom_section',
            'type'     => 'text',
            'priority' => 10,
        )
    );
}
add_action('customize_register', 'my_custom_customizer_options');
```

## Multilingual & Multi-currency

AquaLuxe supports multilingual and multi-currency functionality through integration with popular plugins.

### Multilingual Support
- Compatible with WPML and Polylang
- Language switcher in the top bar
- All strings are translatable
- RTL language support

### Multi-currency Support
- Currency switcher in the top bar
- Compatible with WooCommerce Currency Switcher and WPML WooCommerce Multi-currency
- Price format customization

## Multivendor Support

AquaLuxe includes support for multivendor marketplaces through integration with plugins like WC Vendors, Dokan, or WC Marketplace.

### Multivendor Features
- Vendor profiles
- Vendor dashboards
- Commission systems
- Vendor-specific product displays

## Multitenant Architecture

AquaLuxe supports a multitenant architecture, allowing multiple stores to run on a single WordPress installation.

### Multitenant Features
- Tenant isolation
- Tenant-specific customization options
- Tenant switching mechanism

## Asset Management

AquaLuxe uses Laravel Mix (webpack wrapper) for asset management.

### Asset Structure
- `assets/src`: Source files
- `assets/dist`: Compiled files

### Compiling Assets
1. Install dependencies: `npm install`
2. Compile assets: `npm run dev`
3. Compile assets for production: `npm run production`

### Enqueuing Assets

Assets are enqueued in `inc/enqueue.php`:

```php
function aqualuxe_scripts() {
    // Enqueue styles
    wp_enqueue_style(
        'aqualuxe-style',
        aqualuxe_asset_path('css/main.css'),
        array(),
        null
    );

    // Enqueue scripts
    wp_enqueue_script(
        'aqualuxe-main',
        aqualuxe_asset_path('js/main.js'),
        array('jquery'),
        null,
        true
    );
}
add_action('wp_enqueue_scripts', 'aqualuxe_scripts');
```

## Performance Optimization

AquaLuxe includes several performance optimization features:

- Lazy loading for images
- Minification of CSS and JavaScript
- Deferred JavaScript loading
- Preconnect for external resources
- Optimized asset loading

### Performance Settings

Performance settings can be configured in the Customizer under the Performance section.

## Security

AquaLuxe includes several security features:

- Secure input handling
- Nonce verification
- Proper data sanitization and escaping
- Secure AJAX endpoints

### Security Best Practices

When extending the theme, follow these security best practices:

- Always sanitize user input
- Always escape output
- Use nonces for form submissions
- Use capability checks for user actions

## Extending the Theme

### Child Themes

The recommended way to customize AquaLuxe is through a child theme. Create a child theme with the following structure:

```
aqualuxe-child/
├── functions.php
└── style.css
```

Example `style.css`:

```css
/*
Theme Name: AquaLuxe Child
Theme URI: https://aqualuxe.example.com/
Description: Child theme for AquaLuxe
Author: Your Name
Author URI: https://example.com/
Template: aqualuxe
Version: 1.0.0
Text Domain: aqualuxe-child
*/
```

Example `functions.php`:

```php
<?php
/**
 * AquaLuxe Child Theme functions and definitions
 */

// Enqueue parent and child theme styles
function aqualuxe_child_enqueue_styles() {
    wp_enqueue_style('aqualuxe-parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('aqualuxe-child-style', get_stylesheet_directory_uri() . '/style.css', array('aqualuxe-parent-style'));
}
add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles');

// Add custom functionality here
```

### Plugins

You can also extend AquaLuxe functionality through plugins. Use the theme's hooks to integrate your plugin with the theme.

### Custom Templates

You can create custom templates for specific pages or post types. Create a file named `template-{name}.php` in your child theme with the following header:

```php
<?php
/**
 * Template Name: Custom Template Name
 *
 * @package AquaLuxe Child
 */

get_header();
?>

<!-- Custom template content here -->

<?php
get_footer();
```

Then select the template from the Page Attributes meta box when editing a page.