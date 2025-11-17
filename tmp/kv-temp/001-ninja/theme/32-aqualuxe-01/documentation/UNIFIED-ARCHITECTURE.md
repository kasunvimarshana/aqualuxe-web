# AquaLuxe Theme Unified Architecture

## Overview

This document outlines the unified architecture implemented in the AquaLuxe theme to address issues with duplicate code, improve maintainability, and enhance performance. The unified architecture follows the service container pattern for dependency injection and provides a consistent approach to common theme functionality.

## Key Components

### 1. Service Container

The theme uses a service container pattern for dependency injection, allowing components to be registered and retrieved as needed. This approach reduces tight coupling between components and makes the theme more maintainable.

```php
// Register a service
$theme->register_service('service_name', 'Service_Class_Name');

// Get a service
$service = $theme->get_service('service_name');
```

### 2. Unified Asset Loading

The unified asset loading system provides a centralized approach to registering and enqueuing scripts and styles. This eliminates duplicate code and ensures consistent asset handling throughout the theme.

```php
// Get the Assets instance
$assets = \AquaLuxe\Core\Assets::get_instance();

// Register a script
$assets->register_script(
    'handle',
    'path/to/script.js',
    array('dependency'),
    'version',
    true
);

// Register a style
$assets->register_style(
    'handle',
    'path/to/style.css',
    array('dependency'),
    'version',
    'all'
);

// Add script localization
$assets->add_localization(
    'handle',
    'objectName',
    array('key' => 'value')
);
```

### 3. Unified Template System

The unified template system provides a centralized approach to managing body classes, template parts, and other template-related functionality. This eliminates duplicate code and ensures consistent template handling throughout the theme.

```php
// Get the Template instance
$template = \AquaLuxe\Core\Template::get_instance();

// Register a body class
$template->register_body_class(
    function() {
        return is_single();
    },
    'single-post'
);

// Get a template part with data
$template->get_template_part(
    'template-parts/content',
    'page',
    array('key' => 'value')
);
```

### 4. Unified WooCommerce Integration

The unified WooCommerce integration provides a centralized approach to WooCommerce-related functionality. This eliminates duplicate code and ensures consistent WooCommerce handling throughout the theme.

```php
// Get the WooCommerce instance
$woocommerce = \AquaLuxe\WooCommerce\WooCommerce::get_instance();
```

## Implementation Details

### 1. Service Container Implementation

The service container is implemented in the `\AquaLuxe\Core\Theme` class. It provides methods for registering and retrieving services.

```php
class Theme {
    private $services = array();
    private $instances = array();

    public function register_service($name, $class) {
        $this->services[$name] = $class;
    }

    public function get_service($name) {
        if (!isset($this->instances[$name])) {
            if (isset($this->services[$name])) {
                $class = $this->services[$name];
                $this->instances[$name] = call_user_func(array($class, 'get_instance'));
            } else {
                return null;
            }
        }
        return $this->instances[$name];
    }
}
```

### 2. Unified Asset Loading Implementation

The unified asset loading system is implemented in the `\AquaLuxe\Core\Assets` class. It provides methods for registering and enqueuing scripts and styles.

```php
class Assets {
    private static $instance = null;
    private $scripts = array();
    private $styles = array();
    private $localizations = array();

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function register_script($handle, $src, $deps = array(), $ver = false, $in_footer = true) {
        $this->scripts[$handle] = array(
            'src' => $src,
            'deps' => $deps,
            'ver' => $ver ? $ver : AQUALUXE_VERSION,
            'in_footer' => $in_footer,
        );
    }

    public function register_style($handle, $src, $deps = array(), $ver = false, $media = 'all') {
        $this->styles[$handle] = array(
            'src' => $src,
            'deps' => $deps,
            'ver' => $ver ? $ver : AQUALUXE_VERSION,
            'media' => $media,
        );
    }

    public function add_localization($handle, $name, $data) {
        $this->localizations[$handle][] = array(
            'name' => $name,
            'data' => $data,
        );
    }

    public function enqueue_scripts() {
        // Enqueue core scripts
        
        // Enqueue registered scripts
        foreach ($this->scripts as $handle => $script) {
            wp_register_script(
                $handle,
                $script['src'],
                $script['deps'],
                $script['ver'],
                $script['in_footer']
            );
            
            wp_enqueue_script($handle);
            
            // Add localizations if any
            if (isset($this->localizations[$handle])) {
                foreach ($this->localizations[$handle] as $localization) {
                    wp_localize_script(
                        $handle,
                        $localization['name'],
                        $localization['data']
                    );
                }
            }
        }
    }

    public function enqueue_styles() {
        // Enqueue core styles
        
        // Enqueue registered styles
        foreach ($this->styles as $handle => $style) {
            wp_register_style(
                $handle,
                $style['src'],
                $style['deps'],
                $style['ver'],
                $style['media']
            );
            
            wp_enqueue_style($handle);
        }
    }
}
```

### 3. Unified Template System Implementation

The unified template system is implemented in the `\AquaLuxe\Core\Template` class. It provides methods for managing body classes, template parts, and other template-related functionality.

```php
class Template {
    private static $instance = null;
    private $body_classes = array();

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function register_body_class($condition, $class) {
        $this->body_classes[] = array(
            'condition' => $condition,
            'class' => $class,
        );
    }

    public function body_classes($classes) {
        // Add registered body classes
        foreach ($this->body_classes as $body_class) {
            $condition = $body_class['condition'];
            $class = $body_class['class'];
            
            // Check if condition is met
            $condition_met = is_callable($condition) ? call_user_func($condition) : call_user_func($condition);
            
            // Add class if condition is met
            if ($condition_met) {
                if (is_callable($class)) {
                    $class_name = call_user_func($class);
                    if (is_array($class_name)) {
                        $classes = array_merge($classes, $class_name);
                    } else {
                        $classes[] = $class_name;
                    }
                } else {
                    $classes[] = $class;
                }
            }
        }
        
        return $classes;
    }

    public function get_template_part($slug, $name = null, $args = array()) {
        if ($args && is_array($args)) {
            extract($args);
        }
        
        $templates = array();
        $name = (string) $name;
        if ('' !== $name) {
            $templates[] = "{$slug}-{$name}.php";
        }
        
        $templates[] = "{$slug}.php";
        
        locate_template($templates, true, false, $args);
    }
}
```

### 4. Unified WooCommerce Integration Implementation

The unified WooCommerce integration is implemented in the `\AquaLuxe\WooCommerce\WooCommerce` class. It provides methods for WooCommerce-related functionality.

```php
class WooCommerce {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return;
        }
        
        // Add theme support for WooCommerce
        $this->add_theme_support();
        
        // Register hooks
        $this->register_hooks();
        
        // Register assets
        $this->register_assets();
        
        // Register body classes
        $this->register_body_classes();
    }

    private function add_theme_support() {
        // Add theme support for WooCommerce
    }

    private function register_hooks() {
        // Register WooCommerce hooks
    }

    private function register_assets() {
        // Register WooCommerce assets
        $assets = \AquaLuxe\Core\Assets::get_instance();
        
        // Register styles
        $assets->register_style(...);
        
        // Register scripts
        $assets->register_script(...);
        
        // Add localizations
        $assets->add_localization(...);
    }

    private function register_body_classes() {
        // Register WooCommerce body classes
        $template = \AquaLuxe\Core\Template::get_instance();
        
        // Register body classes
        $template->register_body_class(...);
    }
}
```

## Migration Guide

### 1. Migrating from Legacy Asset Loading

If you're currently using the legacy asset loading functions, you should migrate to the unified asset loading system:

**Legacy approach:**
```php
function my_enqueue_scripts() {
    wp_enqueue_script(
        'my-script',
        get_template_directory_uri() . '/assets/js/my-script.js',
        array('jquery'),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'my_enqueue_scripts');
```

**Unified approach:**
```php
function my_register_assets() {
    $assets = \AquaLuxe\Core\Assets::get_instance();
    
    $assets->register_script(
        'my-script',
        AQUALUXE_URI . '/assets/js/my-script.js',
        array('jquery'),
        '1.0.0',
        true
    );
}
add_action('after_setup_theme', 'my_register_assets');
```

### 2. Migrating from Legacy Body Classes

If you're currently using the legacy body class functions, you should migrate to the unified template system:

**Legacy approach:**
```php
function my_body_classes($classes) {
    if (is_page('about')) {
        $classes[] = 'about-page';
    }
    return $classes;
}
add_filter('body_class', 'my_body_classes');
```

**Unified approach:**
```php
function my_register_body_classes() {
    $template = \AquaLuxe\Core\Template::get_instance();
    
    $template->register_body_class(
        function() {
            return is_page('about');
        },
        'about-page'
    );
}
add_action('after_setup_theme', 'my_register_body_classes');
```

### 3. Migrating from Legacy WooCommerce Integration

If you're currently using the legacy WooCommerce integration functions, you should migrate to the unified WooCommerce integration:

**Legacy approach:**
```php
function my_woocommerce_related_products_args($args) {
    $args['posts_per_page'] = 3;
    return $args;
}
add_filter('woocommerce_output_related_products_args', 'my_woocommerce_related_products_args');
```

**Unified approach:**
```php
// The WooCommerce class already handles this, but if you need to customize it:
function my_customize_woocommerce() {
    add_filter('woocommerce_output_related_products_args', function($args) {
        $args['posts_per_page'] = 3;
        return $args;
    });
}
add_action('after_setup_theme', 'my_customize_woocommerce');
```

## Best Practices

1. **Use the Service Container**: Always use the service container to get instances of core classes.
2. **Register Assets Early**: Register assets during the `after_setup_theme` action to ensure they're available when needed.
3. **Use Conditional Body Classes**: Register body classes with conditions to ensure they're only added when needed.
4. **Follow the Singleton Pattern**: Core classes follow the singleton pattern to ensure only one instance exists.
5. **Use Namespaces**: All classes are namespaced to avoid conflicts with other plugins and themes.
6. **Document Your Code**: Add proper documentation to your code to make it easier to understand and maintain.
7. **Use Constants**: Use theme constants like `AQUALUXE_URI` and `AQUALUXE_VERSION` for consistency.
8. **Follow WordPress Coding Standards**: Follow the WordPress coding standards for consistent code style.

## Conclusion

The unified architecture provides a more maintainable, consistent, and performant approach to theme development. By centralizing common functionality and following best practices, the theme becomes easier to extend and maintain.