# AquaLuxe WordPress Theme - Developer Documentation

## Table of Contents
1. [Introduction](#introduction)
2. [Theme Structure](#theme-structure)
3. [Core Features](#core-features)
4. [Theme Hooks and Filters](#theme-hooks-and-filters)
5. [WooCommerce Integration](#woocommerce-integration)
6. [Customizer Options](#customizer-options)
7. [JavaScript Components](#javascript-components)
8. [CSS Architecture](#css-architecture)
9. [Multilingual Support](#multilingual-support)
10. [Multivendor Support](#multivendor-support)
11. [Multitenant Architecture](#multitenant-architecture)
12. [Extending the Theme](#extending-the-theme)

## Introduction

AquaLuxe is a premium WordPress theme designed specifically for aquatic-related e-commerce websites. It features a dual-state architecture that works seamlessly with or without WooCommerce enabled. The theme supports multilingual, multi-currency, multivendor, and multitenant functionality.

### Requirements
- WordPress 5.8+
- PHP 7.4+
- WooCommerce 6.0+ (optional but recommended)

### Browser Support
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Opera (latest)

## Theme Structure

The AquaLuxe theme follows a modular architecture for better organization and maintainability:

```
aqualuxe-theme/
├── assets/
│   ├── admin/
│   │   ├── css/
│   │   └── js/
│   ├── dist/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── src/
│       ├── css/
│       ├── js/
│       └── images/
├── docs/
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
│   ├── content-post.php
│   ├── content-search.php
│   ├── content-single.php
│   └── content.php
├── woocommerce/
│   ├── archive-product.php
│   ├── cart-drawer.php
│   ├── cart/
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
├── page.php
├── search.php
├── sidebar.php
├── single.php
├── style.css
├── tailwind.config.js
└── webpack.mix.js
```

### Key Directories and Files

- **assets/**: Contains all theme assets (CSS, JavaScript, images)
  - **admin/**: Admin-specific assets
  - **dist/**: Compiled and optimized assets
  - **src/**: Source files for development
- **inc/**: Core theme functionality files
- **templates/**: Template parts used throughout the theme
- **woocommerce/**: Custom WooCommerce templates
- **functions.php**: Main theme functions file
- **tailwind.config.js**: Tailwind CSS configuration
- **webpack.mix.js**: Laravel Mix configuration for asset compilation

## Core Features

### Dual-State Architecture

AquaLuxe is designed to work with or without WooCommerce activated. The theme checks for WooCommerce's presence and loads appropriate functionality:

```php
// Load WooCommerce compatibility file if WooCommerce is active
if (class_exists('WooCommerce')) {
    $aqualuxe_includes[] = 'inc/woocommerce.php';
}
```

### Helper Function for WooCommerce Detection

```php
/**
 * Check if WooCommerce is active
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}
```

### Asset Management

AquaLuxe uses Laravel Mix (webpack wrapper) for asset compilation. Assets are versioned using the mix-manifest.json file:

```php
/**
 * Get asset path with version
 *
 * @param string $path Path to asset
 * @return string Versioned path
 */
function aqualuxe_asset_path($path) {
    // Check if mix-manifest.json exists
    $manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    
    if (file_exists($manifest_path)) {
        $manifest = json_decode(file_get_contents($manifest_path), true);
        $path_key = '/' . $path;
        
        if (isset($manifest[$path_key])) {
            return AQUALUXE_ASSETS_URI . ltrim($manifest[$path_key], '/');
        }
    }
    
    // Fallback to regular path with theme version
    return AQUALUXE_ASSETS_URI . $path . '?ver=' . AQUALUXE_VERSION;
}
```

## Theme Hooks and Filters

AquaLuxe provides a comprehensive set of hooks and filters for extending the theme's functionality:

### Action Hooks

- `aqualuxe_before_header`: Executes before the header
- `aqualuxe_after_header`: Executes after the header
- `aqualuxe_before_content`: Executes before the main content
- `aqualuxe_after_content`: Executes after the main content
- `aqualuxe_before_footer`: Executes before the footer
- `aqualuxe_after_footer`: Executes after the footer
- `aqualuxe_sidebar`: Executes in the sidebar area

### Filter Hooks

- `aqualuxe_excerpt_length`: Modifies the excerpt length
- `aqualuxe_excerpt_more`: Modifies the excerpt "more" text
- `aqualuxe_comment_form_args`: Modifies comment form arguments
- `aqualuxe_body_classes`: Modifies body classes

### Example Usage

```php
// Add content before the header
add_action('aqualuxe_before_header', 'my_custom_header_content');
function my_custom_header_content() {
    echo '<div class="announcement-bar">Special offer: Free shipping on orders over $50!</div>';
}

// Modify excerpt length
add_filter('aqualuxe_excerpt_length', 'my_custom_excerpt_length');
function my_custom_excerpt_length($length) {
    return 30; // 30 words
}
```

## WooCommerce Integration

AquaLuxe provides extensive WooCommerce integration with custom templates and enhanced functionality:

### Custom WooCommerce Templates

- Product archive (shop page)
- Single product page
- Cart page
- Checkout page
- Product filters
- Quick view
- Wishlist
- Cart drawer

### Enhanced WooCommerce Features

- **Quick View**: Allows customers to preview products without leaving the current page
- **Wishlist**: Enables customers to save products for later
- **Advanced Filtering**: Provides AJAX-powered product filtering
- **Cart Drawer**: Shows a slide-in cart when products are added
- **Product Image Gallery**: Enhanced with zoom and lightbox functionality
- **Custom Product Types**: Includes special product types for aquatic items

### WooCommerce Hooks

AquaLuxe adds several custom hooks for WooCommerce:

- `aqualuxe_before_shop_loop`: Executes before the product loop on shop pages
- `aqualuxe_after_shop_loop`: Executes after the product loop on shop pages
- `aqualuxe_before_single_product`: Executes before the single product content
- `aqualuxe_after_single_product`: Executes after the single product content

### Example: Adding Content to Single Product Page

```php
add_action('aqualuxe_before_single_product', 'my_custom_product_notice');
function my_custom_product_notice() {
    echo '<div class="product-notice">All fish come with our 7-day live arrival guarantee!</div>';
}
```

## Customizer Options

AquaLuxe provides extensive theme customization options through the WordPress Customizer:

### Customizer Sections

1. **Colors**: Primary, secondary, background, and text colors
2. **Header**: Layout, styles, and elements
3. **Footer**: Layout, widgets, and copyright
4. **Layout**: Container width, sidebar position, and page layouts
5. **Typography**: Font families, sizes, and weights
6. **Social Media**: Social media profile links
7. **WooCommerce**: Shop layout, product display, and features
8. **Blog**: Post layouts, meta information, and features
9. **Performance**: Optimization options

### Adding Custom Customizer Options

```php
add_action('customize_register', 'my_custom_customizer_options');
function my_custom_customizer_options($wp_customize) {
    // Add a new section
    $wp_customize->add_section(
        'my_custom_section',
        array(
            'title'    => __('My Custom Options', 'aqualuxe'),
            'priority' => 200,
        )
    );
    
    // Add a setting
    $wp_customize->add_setting(
        'my_custom_option',
        array(
            'default'           => 'default_value',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    // Add a control
    $wp_customize->add_control(
        'my_custom_option',
        array(
            'label'    => __('My Custom Option', 'aqualuxe'),
            'section'  => 'my_custom_section',
            'type'     => 'text',
        )
    );
}
```

## JavaScript Components

AquaLuxe includes several JavaScript components for enhanced functionality:

### Core JavaScript (main.js)

- Mobile menu
- Header search
- Sticky header
- Dark mode toggle
- Back to top button
- Dropdown menus
- Accessibility improvements

### WooCommerce JavaScript (woocommerce.js)

- Quantity buttons
- Product gallery
- Quick view
- Wishlist
- AJAX cart
- Cart drawer
- Product filters
- Currency switcher
- Variation swatches
- Product tabs
- Product reviews

### Customizer JavaScript (customizer.js)

- Live preview of customizer changes
- Color adjustments
- Social media icon updates

### Extending JavaScript Functionality

You can extend or modify the theme's JavaScript by adding your custom scripts:

```javascript
// In your custom JavaScript file
(function($) {
    'use strict';
    
    // Add your custom functionality
    function myCustomFunction() {
        // Your code here
    }
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        myCustomFunction();
    });
    
})(jQuery);
```

Then enqueue your script:

```php
add_action('wp_enqueue_scripts', 'my_custom_scripts');
function my_custom_scripts() {
    wp_enqueue_script(
        'my-custom-script',
        get_stylesheet_directory_uri() . '/js/custom.js',
        array('jquery', 'aqualuxe-main'),
        '1.0.0',
        true
    );
}
```

## CSS Architecture

AquaLuxe uses Tailwind CSS for its styling framework, providing a utility-first approach to styling:

### Tailwind Configuration

The theme includes a custom `tailwind.config.js` file that extends the default Tailwind configuration with theme-specific values:

```javascript
// tailwind.config.js
module.exports = {
    content: [
        './**/*.php',
        './assets/src/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: 'var(--color-primary-50)',
                    // ... other shades
                    900: 'var(--color-primary-900)',
                },
                secondary: {
                    50: 'var(--color-secondary-50)',
                    // ... other shades
                    900: 'var(--color-secondary-900)',
                },
                // ... other custom colors
            },
            fontFamily: {
                body: 'var(--font-body)',
                heading: 'var(--font-heading)',
            },
            // ... other theme extensions
        },
    },
    plugins: [
        // ... Tailwind plugins
    ],
};
```

### CSS Variables

AquaLuxe uses CSS variables (custom properties) for theme settings that can be modified through the Customizer:

```css
:root {
    /* Colors */
    --color-primary-50: #f0f9ff;
    --color-primary-100: #e0f2fe;
    /* ... other color variables */
    
    /* Typography */
    --font-body: 'Montserrat', sans-serif;
    --font-heading: 'Playfair Display', serif;
    --font-size-base: 16px;
    
    /* Layout */
    --container-width: 1280px;
    --sidebar-width: 30%;
}

/* Dark mode variables */
.dark {
    --color-bg: #111827;
    --color-text: #f3f4f6;
    /* ... other dark mode variables */
}
```

### Adding Custom CSS

You can add custom CSS to the theme by creating a child theme or using the WordPress Customizer's Additional CSS section.

For more extensive customizations, create a child theme:

```css
/* In your child theme's style.css */
.my-custom-element {
    @apply bg-primary-500 text-white p-4 rounded;
}

.another-element {
    background-color: var(--color-secondary-300);
    padding: 1rem;
}
```

## Multilingual Support

AquaLuxe includes built-in support for multilingual websites:

### WPML Compatibility

The theme is fully compatible with WPML (WordPress Multilingual) plugin:

```php
/**
 * Add compatibility with WPML for multilingual support
 */
function aqualuxe_wpml_compatibility() {
    // Register strings for translation
    if (function_exists('icl_register_string')) {
        // Register theme options
        $options = get_theme_mods();
        
        if (!empty($options)) {
            foreach ($options as $option_key => $option_value) {
                if (is_string($option_value) && !empty($option_value)) {
                    icl_register_string('Theme Options', $option_key, $option_value);
                }
            }
        }
    }
}
add_action('after_setup_theme', 'aqualuxe_wpml_compatibility');
```

### Polylang Compatibility

The theme also supports Polylang for multilingual functionality:

```php
/**
 * Add compatibility with Polylang for multilingual support
 */
function aqualuxe_polylang_compatibility() {
    // Register strings for translation
    if (function_exists('pll_register_string')) {
        // Register theme options
        $options = get_theme_mods();
        
        if (!empty($options)) {
            foreach ($options as $option_key => $option_value) {
                if (is_string($option_value) && !empty($option_value)) {
                    pll_register_string($option_key, $option_value, 'Theme Options');
                }
            }
        }
    }
}
add_action('after_setup_theme', 'aqualuxe_polylang_compatibility');
```

### Language Switcher

The theme includes a language switcher component that can be displayed in the header or footer:

```php
/**
 * Display language switcher
 */
function aqualuxe_language_switcher() {
    if (function_exists('icl_get_languages')) {
        $languages = icl_get_languages('skip_missing=0');
        
        if (!empty($languages)) {
            echo '<div class="language-switcher">';
            echo '<ul>';
            
            foreach ($languages as $language) {
                $class = $language['active'] ? 'active' : '';
                echo '<li class="' . $class . '">';
                echo '<a href="' . $language['url'] . '">';
                
                if ($language['country_flag_url']) {
                    echo '<img src="' . $language['country_flag_url'] . '" alt="' . $language['language_code'] . '" />';
                }
                
                echo $language['native_name'];
                echo '</a>';
                echo '</li>';
            }
            
            echo '</ul>';
            echo '</div>';
        }
    } elseif (function_exists('pll_the_languages')) {
        echo '<div class="language-switcher">';
        pll_the_languages(array(
            'show_flags' => 1,
            'show_names' => 1,
            'display_names_as' => 'name',
        ));
        echo '</div>';
    }
}
```

## Multivendor Support

AquaLuxe includes support for multivendor marketplaces:

### Vendor Information Display

The theme displays vendor information on product pages:

```php
/**
 * Add vendor information to product page
 */
function aqualuxe_vendor_info() {
    // Only show on product pages
    if (!is_product()) {
        return;
    }
    
    global $product;
    
    // Get vendor ID (author of the product)
    $vendor_id = get_post_field('post_author', $product->get_id());
    $vendor = get_userdata($vendor_id);
    
    if (!$vendor) {
        return;
    }
    
    // Get vendor meta
    $vendor_name = get_user_meta($vendor_id, 'vendor_name', true);
    $vendor_name = !empty($vendor_name) ? $vendor_name : $vendor->display_name;
    
    $vendor_logo = get_user_meta($vendor_id, 'vendor_logo', true);
    $vendor_description = get_user_meta($vendor_id, 'vendor_description', true);
    
    // Display vendor information
    // ...
}
add_action('woocommerce_single_product_summary', 'aqualuxe_vendor_info', 45);
```

### Compatibility with Vendor Plugins

AquaLuxe is compatible with popular multivendor plugins:

- WC Vendors
- Dokan
- WCFM Marketplace
- WooCommerce Product Vendors

### Vendor Dashboard Styling

The theme includes custom styling for vendor dashboards to maintain a consistent look and feel.

## Multitenant Architecture

AquaLuxe supports multitenant setups where multiple stores can run on a single WordPress installation:

### Tenant Isolation

The theme provides tenant isolation through custom functions:

```php
/**
 * Get current tenant ID
 *
 * @return int|null Tenant ID or null if not in a multitenant setup
 */
function aqualuxe_get_current_tenant_id() {
    // Implementation depends on the multitenant solution being used
    // Example for a subdomain-based approach:
    $current_site = get_current_site();
    return $current_site->id;
}

/**
 * Check if a feature is enabled for the current tenant
 *
 * @param string $feature Feature to check
 * @return bool Whether the feature is enabled
 */
function aqualuxe_is_tenant_feature_enabled($feature) {
    $tenant_id = aqualuxe_get_current_tenant_id();
    
    if (!$tenant_id) {
        return true; // Default to enabled if not in a multitenant setup
    }
    
    $tenant_features = get_option('aqualuxe_tenant_' . $tenant_id . '_features', array());
    
    return isset($tenant_features[$feature]) ? (bool) $tenant_features[$feature] : true;
}
```

### Tenant-Specific Customization

The theme allows for tenant-specific customization options:

```php
/**
 * Get tenant-specific theme mod
 *
 * @param string $name Theme mod name
 * @param mixed $default Default value
 * @return mixed Theme mod value
 */
function aqualuxe_get_tenant_theme_mod($name, $default = false) {
    $tenant_id = aqualuxe_get_current_tenant_id();
    
    if (!$tenant_id) {
        return get_theme_mod($name, $default);
    }
    
    $tenant_mods = get_option('aqualuxe_tenant_' . $tenant_id . '_theme_mods', array());
    
    return isset($tenant_mods[$name]) ? $tenant_mods[$name] : get_theme_mod($name, $default);
}
```

## Extending the Theme

### Child Themes

The recommended way to customize AquaLuxe is through a child theme:

1. Create a new directory: `aqualuxe-child`
2. Create a `style.css` file:

```css
/*
Theme Name: AquaLuxe Child
Theme URI: https://example.com/aqualuxe-child/
Description: Child theme for AquaLuxe
Author: Your Name
Author URI: https://example.com/
Template: aqualuxe-theme
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
    wp_enqueue_style(
        'aqualuxe-style',
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme('aqualuxe-theme')->get('Version')
    );
    
    wp_enqueue_style(
        'aqualuxe-child-style',
        get_stylesheet_uri(),
        array('aqualuxe-style'),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles');

// Add your custom functions below this line
```

### Plugin Integration

AquaLuxe is designed to work seamlessly with popular WordPress plugins:

- **WooCommerce**: Full integration with custom templates
- **WPML/Polylang**: Multilingual support
- **Elementor/Beaver Builder**: Page builder compatibility
- **Yoast SEO/Rank Math**: SEO optimization
- **Contact Form 7/WPForms**: Form handling
- **Jetpack**: Additional functionality

### Custom Templates

You can create custom page templates in your child theme:

1. Create a file named `template-custom.php` in your child theme:

```php
<?php
/**
 * Template Name: Custom Template
 *
 * @package AquaLuxe Child
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        while (have_posts()) :
            the_post();
            
            // Your custom content here
            
        endwhile;
        ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
```

2. The template will now be available in the Page Attributes meta box when editing a page.

### Custom Shortcodes

You can add custom shortcodes to extend the theme's functionality:

```php
/**
 * Featured Products Shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string Shortcode output
 */
function aqualuxe_featured_products_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => 4,
        'columns' => 4,
        'category' => '',
    ), $atts);
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $atts['limit'],
        'tax_query' => array(
            array(
                'taxonomy' => 'product_visibility',
                'field' => 'name',
                'terms' => 'featured',
                'operator' => 'IN',
            ),
        ),
    );
    
    if (!empty($atts['category'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => explode(',', $atts['category']),
        );
    }
    
    $products = new WP_Query($args);
    
    ob_start();
    
    if ($products->have_posts()) {
        echo '<div class="featured-products columns-' . esc_attr($atts['columns']) . '">';
        
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        echo '</div>';
    }
    
    wp_reset_postdata();
    
    return ob_get_clean();
}
add_shortcode('aqualuxe_featured_products', 'aqualuxe_featured_products_shortcode');
```

Usage: `[aqualuxe_featured_products limit="4" columns="4" category="fish,plants"]`