# AquaLuxe Developer Guide

This guide provides detailed information for developers who want to extend or customize the AquaLuxe WordPress theme. It covers the theme's architecture, module system, hooks and filters, and best practices for development.

## Table of Contents

1. [Theme Architecture](#theme-architecture)
2. [Core Classes](#core-classes)
3. [Module System](#module-system)
4. [Template Hierarchy](#template-hierarchy)
5. [Hooks and Filters](#hooks-and-filters)
6. [Asset Management](#asset-management)
7. [Custom Post Types](#custom-post-types)
8. [Custom Taxonomies](#custom-taxonomies)
9. [Custom Fields](#custom-fields)
10. [WooCommerce Integration](#woocommerce-integration)
11. [Multilingual Support](#multilingual-support)
12. [Performance Optimization](#performance-optimization)
13. [Child Theme Development](#child-theme-development)
14. [Testing and Debugging](#testing-and-debugging)

## Theme Architecture

AquaLuxe follows a modern, object-oriented architecture that emphasizes modularity, extensibility, and maintainability.

### Directory Structure

```
aqualuxe/
├── assets/                 # Theme assets
│   ├── dist/               # Compiled assets
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── src/                # Source files
│       ├── scss/
│       ├── js/
│       └── images/
├── core/                   # Core theme functionality
│   ├── Admin/              # Admin-related classes
│   ├── Customizer/         # Customizer classes
│   └── Setup/              # Theme setup classes
├── inc/                    # Helper functions and classes
│   ├── classes/            # Utility classes
│   └── functions/          # Helper functions
├── modules/                # Theme modules
│   ├── dark-mode/
│   ├── multilingual/
│   ├── multicurrency/
│   ├── performance/
│   ├── seo/
│   └── woocommerce/
├── templates/              # Template parts
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
└── style.css
```

### Autoloading

AquaLuxe uses a PSR-4 compatible autoloader to automatically load classes. Class names should follow the WordPress coding standards with class names in `class-{name}.php` format.

```php
// Example class file: core/class-aqualuxe-theme.php
namespace AquaLuxe;

class AquaLuxe_Theme {
    // Class implementation
}
```

## Core Classes

### AquaLuxe_Theme

The main theme class that initializes all core components and modules.

```php
// Example usage
$theme = AquaLuxe\AquaLuxe_Theme::get_instance();
$theme->init();
```

### Theme_Setup

Handles basic theme setup such as theme supports, content width, and navigation menus.

```php
// Example: Adding a custom theme support
add_action('after_setup_theme', function() {
    add_theme_support('custom-feature');
});
```

### Assets

Manages the enqueuing of styles and scripts.

```php
// Example: Enqueue a custom script
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script(
        'custom-script',
        get_template_directory_uri() . '/assets/js/custom.js',
        array('jquery'),
        '1.0.0',
        true
    );
});
```

### Customizer

Handles the WordPress Customizer integration.

```php
// Example: Add a custom Customizer section
add_action('customize_register', function($wp_customize) {
    $wp_customize->add_section('custom_section', array(
        'title' => __('Custom Section', 'aqualuxe'),
        'priority' => 30,
    ));
    
    $wp_customize->add_setting('custom_setting', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('custom_setting', array(
        'label' => __('Custom Setting', 'aqualuxe'),
        'section' => 'custom_section',
        'type' => 'text',
    ));
});
```

## Module System

AquaLuxe uses a modular architecture that allows you to create, enable, or disable specific features as needed.

### Module Structure

Each module follows a standard structure:

```
modules/example-module/
├── assets/
│   ├── css/
│   └── js/
├── inc/
│   ├── classes/
│   └── functions/
├── templates/
└── module.php
```

### Creating a Custom Module

1. Create a new directory in the `modules` folder
2. Create a `module.php` file with the module definition:

```php
<?php
/**
 * Module Name: Example Module
 * Description: This is an example module.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * Requires: dark-mode, woocommerce
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define module constants
define('AQUALUXE_EXAMPLE_MODULE_PATH', dirname(__FILE__));
define('AQUALUXE_EXAMPLE_MODULE_URL', get_template_directory_uri() . '/modules/example-module');

// Include module files
require_once AQUALUXE_EXAMPLE_MODULE_PATH . '/inc/functions/helpers.php';
require_once AQUALUXE_EXAMPLE_MODULE_PATH . '/inc/classes/class-example.php';

// Initialize module
function aqualuxe_example_module_init() {
    // Module initialization code
    wp_enqueue_style(
        'aqualuxe-example-module',
        AQUALUXE_EXAMPLE_MODULE_URL . '/assets/css/example-module.css',
        array(),
        '1.0.0'
    );
    
    wp_enqueue_script(
        'aqualuxe-example-module',
        AQUALUXE_EXAMPLE_MODULE_URL . '/assets/js/example-module.js',
        array('jquery'),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'aqualuxe_example_module_init');

// Add module settings to Customizer
function aqualuxe_example_module_customizer($wp_customize) {
    $wp_customize->add_section('aqualuxe_example_module', array(
        'title' => __('Example Module', 'aqualuxe'),
        'priority' => 30,
        'panel' => 'aqualuxe_modules',
    ));
    
    $wp_customize->add_setting('aqualuxe_example_module_setting', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_example_module_setting', array(
        'label' => __('Example Setting', 'aqualuxe'),
        'section' => 'aqualuxe_example_module',
        'type' => 'text',
    ));
}
add_action('customize_register', 'aqualuxe_example_module_customizer');
```

### Module Interface

All modules should implement the `Module` interface:

```php
namespace AquaLuxe\Modules;

interface Module {
    public function init();
    public function get_name();
    public function get_description();
    public function get_version();
    public function get_dependencies();
}
```

### Module Base Class

For convenience, you can extend the `Module_Base` class:

```php
namespace AquaLuxe\Modules\Example;

use AquaLuxe\Modules\Module_Base;

class Example_Module extends Module_Base {
    public function init() {
        // Module initialization code
    }
    
    public function get_name() {
        return 'Example Module';
    }
    
    public function get_description() {
        return 'This is an example module.';
    }
    
    public function get_version() {
        return '1.0.0';
    }
    
    public function get_dependencies() {
        return array('dark-mode', 'woocommerce');
    }
}
```

## Template Hierarchy

AquaLuxe follows the WordPress template hierarchy with some enhancements for modularity and reusability.

### Template Parts

Template parts are stored in the `templates` directory and can be included using the `get_template_part()` function:

```php
get_template_part('templates/content', 'page');
```

### Custom Template Tags

AquaLuxe provides custom template tags in `inc/functions/template-tags.php`:

```php
// Example: Display post meta
aqualuxe_post_meta();

// Example: Display post thumbnail with custom size
aqualuxe_post_thumbnail('large');

// Example: Display breadcrumbs
aqualuxe_breadcrumbs();
```

### Template Functions

Helper functions for templates are available in `inc/functions/template-functions.php`:

```php
// Example: Check if sidebar should be displayed
if (aqualuxe_display_sidebar()) {
    get_sidebar();
}

// Example: Get the container class based on layout settings
$container_class = aqualuxe_get_container_class();
```

## Hooks and Filters

AquaLuxe provides numerous hooks and filters to extend and customize the theme.

### Action Hooks

```php
// Before header
add_action('aqualuxe_before_header', function() {
    echo '<div class="announcement-bar">Special offer!</div>';
});

// After header
add_action('aqualuxe_after_header', function() {
    // Your code here
});

// Before footer
add_action('aqualuxe_before_footer', function() {
    // Your code here
});

// After footer
add_action('aqualuxe_after_footer', function() {
    // Your code here
});

// Before main content
add_action('aqualuxe_before_main_content', function() {
    // Your code here
});

// After main content
add_action('aqualuxe_after_main_content', function() {
    // Your code here
});

// Before post content
add_action('aqualuxe_before_post_content', function() {
    // Your code here
});

// After post content
add_action('aqualuxe_after_post_content', function() {
    // Your code here
});

// Before comments
add_action('aqualuxe_before_comments', function() {
    // Your code here
});

// After comments
add_action('aqualuxe_after_comments', function() {
    // Your code here
});

// Before sidebar
add_action('aqualuxe_before_sidebar', function() {
    // Your code here
});

// After sidebar
add_action('aqualuxe_after_sidebar', function() {
    // Your code here
});
```

### Filters

```php
// Modify the excerpt length
add_filter('aqualuxe_excerpt_length', function($length) {
    return 30; // Change excerpt length to 30 words
});

// Modify the excerpt "read more" text
add_filter('aqualuxe_excerpt_more', function($more) {
    return '... <a class="read-more" href="' . get_permalink() . '">Continue Reading</a>';
});

// Add custom classes to the body
add_filter('aqualuxe_body_classes', function($classes) {
    $classes[] = 'custom-class';
    return $classes;
});

// Modify the sidebar position
add_filter('aqualuxe_sidebar_position', function($position) {
    return 'left'; // Change sidebar position to left
});

// Modify the container width
add_filter('aqualuxe_container_width', function($width) {
    return 1200; // Change container width to 1200px
});

// Modify the grid columns
add_filter('aqualuxe_grid_columns', function($columns) {
    return 16; // Change grid columns to 16
});

// Modify the primary color
add_filter('aqualuxe_primary_color', function($color) {
    return '#ff6b6b'; // Change primary color
});
```

## Asset Management

AquaLuxe uses a modern asset management system with SCSS for styles and modular JavaScript.

### SCSS Structure

The SCSS files are organized in a logical structure:

```
assets/src/scss/
├── abstracts/
│   ├── _functions.scss
│   ├── _mixins.scss
│   └── _variables.scss
├── base/
│   ├── _accessibility.scss
│   ├── _buttons.scss
│   ├── _forms.scss
│   ├── _reset.scss
│   └── _typography.scss
├── components/
│   ├── _alerts.scss
│   ├── _breadcrumbs.scss
│   ├── _cards.scss
│   ├── _comments.scss
│   ├── _navigation.scss
│   ├── _pagination.scss
│   ├── _posts.scss
│   └── _widgets.scss
├── layout/
│   ├── _footer.scss
│   ├── _grid.scss
│   └── _header.scss
├── themes/
│   ├── _dark.scss
│   └── _light.scss
├── utilities/
│   ├── _colors.scss
│   ├── _display.scss
│   ├── _flex.scss
│   ├── _spacing.scss
│   └── _text.scss
└── style.scss
```

### JavaScript Modules

JavaScript files are organized in a modular structure:

```
assets/src/js/
├── modules/
│   ├── ajax-loader.js
│   ├── currency-switcher.js
│   ├── dark-mode.js
│   ├── form-validation.js
│   ├── language-switcher.js
│   ├── navigation.js
│   ├── product-filter.js
│   └── scroll-effects.js
└── main.js
```

### Enqueuing Assets

Assets are enqueued in the `core/Setup/class-assets.php` file:

```php
namespace AquaLuxe\Core\Setup;

class Assets {
    public function init() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }
    
    public function enqueue_styles() {
        wp_enqueue_style(
            'aqualuxe-style',
            get_template_directory_uri() . '/assets/dist/css/style.css',
            array(),
            AQUALUXE_VERSION
        );
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script(
            'aqualuxe-script',
            get_template_directory_uri() . '/assets/dist/js/main.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
}
```

## Custom Post Types

AquaLuxe provides a simple way to register custom post types:

```php
// Example: Register a "Portfolio" custom post type
function aqualuxe_register_portfolio_post_type() {
    $labels = array(
        'name' => _x('Portfolio Items', 'post type general name', 'aqualuxe'),
        'singular_name' => _x('Portfolio Item', 'post type singular name', 'aqualuxe'),
        'menu_name' => _x('Portfolio', 'admin menu', 'aqualuxe'),
        'name_admin_bar' => _x('Portfolio Item', 'add new on admin bar', 'aqualuxe'),
        'add_new' => _x('Add New', 'portfolio item', 'aqualuxe'),
        'add_new_item' => __('Add New Portfolio Item', 'aqualuxe'),
        'new_item' => __('New Portfolio Item', 'aqualuxe'),
        'edit_item' => __('Edit Portfolio Item', 'aqualuxe'),
        'view_item' => __('View Portfolio Item', 'aqualuxe'),
        'all_items' => __('All Portfolio Items', 'aqualuxe'),
        'search_items' => __('Search Portfolio Items', 'aqualuxe'),
        'parent_item_colon' => __('Parent Portfolio Items:', 'aqualuxe'),
        'not_found' => __('No portfolio items found.', 'aqualuxe'),
        'not_found_in_trash' => __('No portfolio items found in Trash.', 'aqualuxe')
    );
    
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'portfolio'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-portfolio',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt')
    );
    
    register_post_type('portfolio', $args);
}
add_action('init', 'aqualuxe_register_portfolio_post_type');
```

## Custom Taxonomies

Register custom taxonomies to organize your content:

```php
// Example: Register a "Portfolio Category" taxonomy
function aqualuxe_register_portfolio_category_taxonomy() {
    $labels = array(
        'name' => _x('Portfolio Categories', 'taxonomy general name', 'aqualuxe'),
        'singular_name' => _x('Portfolio Category', 'taxonomy singular name', 'aqualuxe'),
        'search_items' => __('Search Portfolio Categories', 'aqualuxe'),
        'all_items' => __('All Portfolio Categories', 'aqualuxe'),
        'parent_item' => __('Parent Portfolio Category', 'aqualuxe'),
        'parent_item_colon' => __('Parent Portfolio Category:', 'aqualuxe'),
        'edit_item' => __('Edit Portfolio Category', 'aqualuxe'),
        'update_item' => __('Update Portfolio Category', 'aqualuxe'),
        'add_new_item' => __('Add New Portfolio Category', 'aqualuxe'),
        'new_item_name' => __('New Portfolio Category Name', 'aqualuxe'),
        'menu_name' => __('Categories', 'aqualuxe'),
    );
    
    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'portfolio-category'),
    );
    
    register_taxonomy('portfolio_category', array('portfolio'), $args);
}
add_action('init', 'aqualuxe_register_portfolio_category_taxonomy');
```

## Custom Fields

AquaLuxe is compatible with popular custom fields plugins like Advanced Custom Fields (ACF) and CMB2.

### ACF Integration

```php
// Example: Register ACF fields
function aqualuxe_register_acf_fields() {
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_portfolio_details',
            'title' => 'Portfolio Details',
            'fields' => array(
                array(
                    'key' => 'field_client_name',
                    'label' => 'Client Name',
                    'name' => 'client_name',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_project_date',
                    'label' => 'Project Date',
                    'name' => 'project_date',
                    'type' => 'date_picker',
                ),
                array(
                    'key' => 'field_project_url',
                    'label' => 'Project URL',
                    'name' => 'project_url',
                    'type' => 'url',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'portfolio',
                    ),
                ),
            ),
        ));
    }
}
add_action('acf/init', 'aqualuxe_register_acf_fields');
```

## WooCommerce Integration

AquaLuxe provides deep integration with WooCommerce through its WooCommerce module.

### WooCommerce Templates

Override WooCommerce templates by placing them in the `modules/woocommerce/templates` directory:

```
modules/woocommerce/templates/
├── archive-product.php
├── content-product.php
├── content-single-product.php
├── single-product.php
└── ...
```

### WooCommerce Hooks

```php
// Example: Add a custom section to single product pages
function aqualuxe_add_product_custom_section() {
    echo '<div class="product-custom-section">';
    echo '<h3>' . __('Custom Section', 'aqualuxe') . '</h3>';
    echo '<p>' . __('This is a custom section added to the product page.', 'aqualuxe') . '</p>';
    echo '</div>';
}
add_action('woocommerce_after_single_product_summary', 'aqualuxe_add_product_custom_section', 15);

// Example: Modify the number of products per row
function aqualuxe_loop_columns() {
    return 4; // 4 products per row
}
add_filter('loop_shop_columns', 'aqualuxe_loop_columns');

// Example: Modify the number of related products
function aqualuxe_related_products_args($args) {
    $args['posts_per_page'] = 4; // 4 related products
    $args['columns'] = 4; // 4 columns
    return $args;
}
add_filter('woocommerce_output_related_products_args', 'aqualuxe_related_products_args');
```

## Multilingual Support

AquaLuxe is fully compatible with WPML and other translation plugins.

### WPML Integration

```php
// Example: Register strings for translation
function aqualuxe_register_strings_for_translation() {
    if (function_exists('icl_register_string')) {
        icl_register_string('aqualuxe', 'footer_copyright', get_theme_mod('footer_copyright'));
        icl_register_string('aqualuxe', 'header_phone', get_theme_mod('header_phone'));
        icl_register_string('aqualuxe', 'header_email', get_theme_mod('header_email'));
    }
}
add_action('init', 'aqualuxe_register_strings_for_translation');

// Example: Translate a string
function aqualuxe_translate_string($string, $key) {
    if (function_exists('icl_t')) {
        return icl_t('aqualuxe', $key, $string);
    }
    return $string;
}

// Usage
echo aqualuxe_translate_string(get_theme_mod('footer_copyright'), 'footer_copyright');
```

## Performance Optimization

AquaLuxe includes various performance optimizations through its Performance module.

### Lazy Loading

```php
// Example: Add lazy loading to images
function aqualuxe_add_lazy_loading_to_images($content) {
    return preg_replace_callback('/<img([^>]+)>/i', function($matches) {
        // Skip if already has loading attribute
        if (strpos($matches[1], 'loading=') !== false) {
            return $matches[0];
        }
        
        // Add loading="lazy" attribute
        return '<img' . $matches[1] . ' loading="lazy">';
    }, $content);
}
add_filter('the_content', 'aqualuxe_add_lazy_loading_to_images');
```

### Resource Preloading

```php
// Example: Preload critical resources
function aqualuxe_preload_resources() {
    echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/fonts/inter.woff2" as="font" type="font/woff2" crossorigin>';
    echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/images/logo.svg" as="image">';
}
add_action('wp_head', 'aqualuxe_preload_resources', 1);
```

### Script Optimization

```php
// Example: Defer non-critical JavaScript
function aqualuxe_defer_scripts($tag, $handle, $src) {
    $defer_scripts = array(
        'aqualuxe-script',
        'aqualuxe-dark-mode',
        'aqualuxe-scroll-effects'
    );
    
    if (in_array($handle, $defer_scripts)) {
        return str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'aqualuxe_defer_scripts', 10, 3);
```

## Child Theme Development

For advanced customizations, we recommend creating a child theme.

### Child Theme Structure

```
aqualuxe-child/
├── assets/
│   ├── css/
│   └── js/
├── functions.php
└── style.css
```

### Child Theme style.css

```css
/*
Theme Name: AquaLuxe Child
Theme URI: https://aqualuxe.com
Description: Child theme for AquaLuxe
Author: Your Name
Author URI: https://yourwebsite.com
Template: aqualuxe
Version: 1.0.0
*/
```

### Child Theme functions.php

```php
<?php
/**
 * AquaLuxe Child Theme functions and definitions
 */

// Enqueue parent and child theme styles
function aqualuxe_child_enqueue_styles() {
    wp_enqueue_style('aqualuxe-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('aqualuxe-child-style', get_stylesheet_directory_uri() . '/style.css', array('aqualuxe-style'));
}
add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles');

// Override parent theme functions
function aqualuxe_child_override_parent_functions() {
    // Remove parent theme function
    remove_action('aqualuxe_before_footer', 'aqualuxe_footer_widgets');
    
    // Add custom function
    add_action('aqualuxe_before_footer', 'aqualuxe_child_footer_widgets');
}
add_action('after_setup_theme', 'aqualuxe_child_override_parent_functions');

// Custom footer widgets function
function aqualuxe_child_footer_widgets() {
    // Custom implementation
}
```

## Testing and Debugging

AquaLuxe includes tools and guidelines for testing and debugging your theme customizations.

### Debug Mode

Enable WordPress debug mode in your `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

### Theme Testing

Use the Theme Unit Test data from WordPress.org to test your theme with various content scenarios:
https://github.com/WPTT/theme-unit-test

### Browser Testing

Test your theme in multiple browsers and devices:
- Chrome
- Firefox
- Safari
- Edge
- Mobile devices (iOS and Android)

### Performance Testing

Use tools like Google PageSpeed Insights, GTmetrix, and WebPageTest to evaluate your theme's performance.

### Accessibility Testing

Ensure your theme meets accessibility standards using tools like:
- WAVE (Web Accessibility Evaluation Tool)
- Axe DevTools
- Lighthouse Accessibility Audit

## Conclusion

This developer guide provides a comprehensive overview of the AquaLuxe theme architecture and customization options. For more detailed information, please refer to the inline code documentation and comments throughout the theme files.

If you have any questions or need assistance, please contact our support team at support@aqualuxe.com or visit our support forum at https://aqualuxe.com/support.