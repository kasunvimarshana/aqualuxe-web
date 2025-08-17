# AquaLuxe Developer Guide

This guide provides detailed information for developers who want to customize or extend the AquaLuxe WordPress theme.

## Table of Contents

1. [Theme Architecture](#theme-architecture)
2. [Service Container](#service-container)
3. [Hooks and Filters](#hooks-and-filters)
4. [Asset Management](#asset-management)
5. [Customizer API](#customizer-api)
6. [WooCommerce Integration](#woocommerce-integration)
7. [Template Structure](#template-structure)
8. [Accessibility](#accessibility)
9. [Performance Optimization](#performance-optimization)
10. [Dark Mode](#dark-mode)

## Theme Architecture

AquaLuxe follows a service-based architecture pattern that promotes modularity, separation of concerns, and testability. The theme is built around a central `Theme` class that acts as a service container, initializing and managing various services.

### Core Classes

- `Theme`: The main theme class that initializes all services
- `Service`: Abstract base class for all service classes
- `Assets`: Handles asset loading and optimization
- `Template`: Provides template-related functionality
- `Accessibility`: Manages accessibility features
- `Dark_Mode`: Handles dark mode functionality

### Directory Structure

```
aqualuxe/
├── assets/               # Compiled assets and source files
├── inc/                  # Theme PHP includes
│   ├── core/             # Core functionality
│   ├── customizer/       # Customizer settings
│   ├── helpers/          # Helper functions
│   ├── performance/      # Performance optimizations
│   └── woocommerce/      # WooCommerce integration
├── template-parts/       # Template partials
├── woocommerce/          # WooCommerce template overrides
```

## Service Container

The theme uses a simple service container pattern to manage dependencies and services. Services are registered in the `Theme` class and can be accessed through the `get_service()` method.

### Creating a New Service

1. Create a new class that extends the `Service` abstract class:

```php
namespace AquaLuxe\Core;

class My_Service extends Service {
    public function initialize() {
        $this->register_hooks();
    }

    public function register_hooks() {
        add_action('init', array($this, 'my_init_function'));
    }

    public function my_init_function() {
        // Your code here
    }
}
```

2. Register the service in the `Theme` class:

```php
// In inc/core/class-aqualuxe-theme.php
private function register_services() {
    // Existing services...
    $this->services['my_service'] = new My_Service();
}
```

3. Access the service:

```php
$theme = aqualuxe_get_theme_instance();
$my_service = $theme->get_service('my_service');
```

## Hooks and Filters

AquaLuxe provides various hooks and filters to extend and customize the theme's functionality.

### Action Hooks

- `aqualuxe_before_header`: Fires before the header content
- `aqualuxe_after_header`: Fires after the header content
- `aqualuxe_before_footer`: Fires before the footer content
- `aqualuxe_after_footer`: Fires after the footer content
- `aqualuxe_before_content`: Fires before the main content
- `aqualuxe_after_content`: Fires after the main content
- `aqualuxe_sidebar`: Fires inside the sidebar
- `aqualuxe_before_post`: Fires before each post
- `aqualuxe_after_post`: Fires after each post

### Filter Hooks

- `aqualuxe_body_classes`: Filters the body classes
- `aqualuxe_post_classes`: Filters the post classes
- `aqualuxe_comment_form_args`: Filters the comment form arguments
- `aqualuxe_excerpt_length`: Filters the excerpt length
- `aqualuxe_excerpt_more`: Filters the excerpt "more" string
- `aqualuxe_content_width`: Filters the content width
- `aqualuxe_sidebar_position`: Filters the sidebar position

## Asset Management

AquaLuxe uses Laravel Mix (a wrapper around webpack) for asset compilation and optimization.

### Asset Directory Structure

```
assets/
├── css/              # Compiled CSS files
├── fonts/            # Font files
├── images/           # Optimized images
├── js/               # Compiled JavaScript files
├── src/              # Source files
│   ├── fonts/        # Font source files
│   ├── images/       # Image source files
│   ├── js/           # JavaScript source files
│   └── scss/         # SCSS source files
└── vendor/           # Third-party assets
```

### SCSS Structure

```
scss/
├── abstracts/        # Variables, mixins, functions
├── base/             # Base styles, typography, utilities
├── components/       # UI components
├── layouts/          # Layout components
├── pages/            # Page-specific styles
├── themes/           # Theme variations (e.g., dark mode)
└── vendors/          # Third-party styles
```

### Adding Custom Styles

1. Create a new SCSS file in the appropriate directory
2. Import the file in `assets/src/scss/main.scss`
3. Run `npm run dev` to compile the styles

### Adding Custom Scripts

1. Create a new JS file in `assets/src/js/`
2. Add the file to the appropriate bundle in `webpack.mix.js`
3. Run `npm run dev` to compile the scripts

## Customizer API

AquaLuxe extends the WordPress Customizer API to provide a comprehensive set of customization options.

### Customizer Structure

```
inc/customizer/
├── class-customizer.php           # Main customizer class
├── controls/                      # Custom control classes
└── sections/                      # Section classes
    ├── class-general.php          # General settings
    ├── class-header.php           # Header options
    ├── class-footer.php           # Footer options
    ├── class-typography.php       # Typography options
    ├── class-colors.php           # Color options
    ├── class-layout.php           # Layout options
    ├── class-blog.php             # Blog settings
    ├── class-woocommerce.php      # WooCommerce settings
    ├── class-performance.php      # Performance options
    └── class-advanced.php         # Advanced settings
```

### Adding a Custom Customizer Setting

1. Create a new section class or modify an existing one:

```php
namespace AquaLuxe\Customizer\Sections;

class My_Section {
    public function __construct($wp_customize) {
        // Add section
        $wp_customize->add_section('aqualuxe_my_section', array(
            'title' => __('My Section', 'aqualuxe'),
            'priority' => 30,
            'panel' => 'aqualuxe_theme_options',
        ));

        // Add setting
        $wp_customize->add_setting('aqualuxe_my_setting', array(
            'default' => 'default_value',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));

        // Add control
        $wp_customize->add_control('aqualuxe_my_setting', array(
            'label' => __('My Setting', 'aqualuxe'),
            'section' => 'aqualuxe_my_section',
            'type' => 'text',
        ));

        // Add partial refresh
        if (isset($wp_customize->selective_refresh)) {
            $wp_customize->selective_refresh->add_partial('aqualuxe_my_setting', array(
                'selector' => '.my-element',
                'render_callback' => function() {
                    return get_theme_mod('aqualuxe_my_setting', 'default_value');
                },
            ));
        }
    }
}
```

2. Register the section in the `Customizer` class:

```php
// In inc/customizer/class-customizer.php
private function register_sections($wp_customize) {
    // Existing sections...
    $my_section = new Sections\My_Section($wp_customize);
}
```

## WooCommerce Integration

AquaLuxe provides comprehensive WooCommerce integration with template overrides and custom functionality.

### WooCommerce Structure

```
inc/woocommerce/
├── class-woocommerce.php      # Main WooCommerce integration class
├── class-product.php          # Product-related functionality
├── class-cart.php             # Cart-related functionality
├── class-checkout.php         # Checkout-related functionality
├── class-account.php          # Account-related functionality
├── class-wishlist.php         # Wishlist functionality
└── class-quick-view.php       # Quick view functionality

woocommerce/
├── archive-product.php        # Product archive template
├── content-product.php        # Product content template
├── content-single-product.php # Single product content template
├── single-product.php         # Single product template
└── [other template overrides]
```

### Adding Custom WooCommerce Functionality

1. Create a new class in `inc/woocommerce/`:

```php
namespace AquaLuxe\WooCommerce;

use AquaLuxe\Core\Service;

class My_WooCommerce_Feature extends Service {
    public function initialize() {
        $this->register_hooks();
    }

    public function register_hooks() {
        add_action('woocommerce_before_shop_loop', array($this, 'my_feature_function'));
    }

    public function my_feature_function() {
        // Your code here
    }
}
```

2. Register the service in the `Theme` class:

```php
// In inc/core/class-aqualuxe-theme.php
private function register_services() {
    // Existing services...
    if (class_exists('WooCommerce')) {
        // Existing WooCommerce services...
        $this->services['my_woocommerce_feature'] = new \AquaLuxe\WooCommerce\My_WooCommerce_Feature();
    }
}
```

## Template Structure

AquaLuxe uses a modular template structure with template parts for reusable components.

### Main Templates

- `index.php`: Main template file
- `archive.php`: Archive template
- `single.php`: Single post template
- `page.php`: Page template
- `search.php`: Search results template
- `404.php`: 404 error template

### Template Parts

```
template-parts/
├── content/           # Content templates
│   ├── content.php    # Default content template
│   ├── content-page.php
│   ├── content-search.php
│   └── content-none.php
├── footer/            # Footer templates
│   └── footer-content.php
└── header/            # Header templates
    └── header-content.php
```

### Using Template Parts

```php
get_template_part('template-parts/content/content', get_post_type());
```

## Accessibility

AquaLuxe is built with accessibility in mind, following WCAG 2.1 AA guidelines.

### Accessibility Features

- Skip links for keyboard navigation
- ARIA attributes for interactive elements
- Focus management for modals and dropdowns
- Keyboard navigation support
- Screen reader text for visual elements
- High contrast mode support

### Accessibility Helper Functions

```php
// Add screen reader text
aqualuxe_screen_reader_text('Text only for screen readers');

// Check if keyboard navigation is enabled
$accessibility = aqualuxe_get_theme_instance()->get_service('accessibility');
if ($accessibility->is_keyboard_navigation_enabled()) {
    // Do something
}

// Check if high contrast mode is enabled
if ($accessibility->is_high_contrast_mode_enabled()) {
    // Do something
}
```

## Performance Optimization

AquaLuxe includes various performance optimizations to ensure fast loading times.

### Performance Features

- Critical CSS generation
- Asset minification and optimization
- Lazy loading for images
- Responsive image srcsets
- Font loading optimization
- Service worker for offline support
- Resource hints (preconnect, dns-prefetch)

### Performance Helper Functions

```php
// Get critical CSS for a specific template
aqualuxe_get_critical_css('home');

// Print critical CSS inline
aqualuxe_critical_css('home');

// Get versioned asset URL
aqualuxe_asset('css/main.css');

// Print versioned asset URL
aqualuxe_asset_url('css/main.css');
```

## Dark Mode

AquaLuxe includes a built-in dark mode feature with system preference detection and user preference storage.

### Dark Mode Features

- Toggle button for user preference
- System preference detection
- Cookie storage for persistence
- CSS variables for theming
- Smooth transition between modes

### Dark Mode Helper Functions

```php
// Check if dark mode is active
$dark_mode = aqualuxe_get_theme_instance()->get_service('dark_mode');
if ($dark_mode->is_dark_mode_active()) {
    // Do something
}

// Get dark mode class
$dark_mode_class = aqualuxe_get_dark_mode_class();

// Add dark mode toggle button
aqualuxe_dark_mode_toggle();
```

## Conclusion

This developer guide provides an overview of the AquaLuxe theme architecture and key features. For more detailed information, please refer to the inline code documentation or contact our support team.