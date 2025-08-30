# AquaLuxe Theme - Developer Documentation

## Introduction

AquaLuxe is a premium WordPress + WooCommerce theme designed for luxury aquatic retail businesses. This document provides comprehensive documentation for developers who want to extend or customize the theme.

## Architecture

AquaLuxe follows a modern, object-oriented architecture with a focus on modularity, maintainability, and extensibility. The theme is built using the following principles:

- **SOLID Principles**: Each class has a single responsibility, interfaces are used for abstraction, and dependencies are injected.
- **Service Container Pattern**: A central service container manages dependencies and provides access to services.
- **Namespaces**: PHP namespaces are used to organize code and prevent naming conflicts.
- **Autoloading**: PSR-4 autoloading is used to automatically load classes.
- **Hooks and Filters**: WordPress hooks and filters are used to extend functionality.

## Directory Structure

```
aqualuxe/
├── assets/
│   └── src/
│       ├── fonts/
│       ├── images/
│       │   └── icons/
│       ├── js/
│       │   ├── components/
│       │   ├── customizer/
│       │   ├── dark-mode.js
│       │   ├── lazy-loading.js
│       │   ├── navigation.js
│       │   ├── service-worker.js
│       │   └── service-worker-register.js
│       └── scss/
│           ├── abstracts/
│           ├── base/
│           ├── components/
│           ├── layout/
│           ├── pages/
│           ├── themes/
│           └── vendors/
├── inc/
│   ├── core/
│   ├── customizer/
│   │   ├── controls/
│   │   └── sections/
│   ├── demo/
│   ├── demo-importer/
│   ├── helpers/
│   ├── template-functions/
│   ├── widgets/
│   └── woocommerce/
├── template-parts/
│   ├── about/
│   ├── blog/
│   ├── contact/
│   ├── content/
│   ├── faq/
│   ├── footer/
│   ├── header/
│   ├── home/
│   ├── services/
│   └── woocommerce/
└── woocommerce/
    ├── archive-product/
    ├── cart/
    ├── checkout/
    ├── global/
    ├── loop/
    └── single-product/
```

## Core Classes

### Theme Class

The `Theme` class is the main entry point for the theme. It implements a service container pattern and manages the theme's components.

```php
namespace AquaLuxe\Core;

class Theme {
    // ...
}
```

#### Key Methods

- `get_instance()`: Returns the single instance of the class (singleton pattern).
- `initialize()`: Initializes the theme.
- `register_service($name, $class, $dependencies = [])`: Registers a service.
- `get_service($name)`: Gets a service instance.
- `is_woocommerce_active()`: Checks if WooCommerce is active.

### Setup Class

The `Setup` class handles theme setup, features, and initialization.

```php
namespace AquaLuxe\Core;

class Setup {
    // ...
}
```

#### Key Methods

- `setup()`: Sets up theme defaults and registers support for various WordPress features.
- `content_width()`: Sets the content width in pixels.
- `body_classes($classes)`: Adds custom classes to the body tag.

### Assets Class

The `Assets` class handles asset management, including scripts, styles, and fonts.

```php
namespace AquaLuxe\Core;

class Assets {
    // ...
}
```

#### Key Methods

- `enqueue_styles()`: Enqueues styles.
- `enqueue_scripts()`: Enqueues scripts.
- `enqueue_editor_assets()`: Enqueues editor assets.
- `preload_assets()`: Preloads critical assets.
- `add_critical_css()`: Adds critical CSS inline.

## Service Container

AquaLuxe uses a service container pattern to manage dependencies and provide access to services. The `Theme` class acts as the service container.

### Registering a Service

```php
$theme = \AquaLuxe\Core\Theme::get_instance();
$theme->register_service('my_service', 'MyNamespace\MyClass', ['dependency1', 'dependency2']);
```

### Getting a Service

```php
$theme = \AquaLuxe\Core\Theme::get_instance();
$my_service = $theme->get_service('my_service');
```

## Customizer

AquaLuxe provides a comprehensive customizer implementation with support for custom controls and sections.

### Customizer Class

The `Customizer` class is the main entry point for the customizer.

```php
namespace AquaLuxe\Customizer;

class Customizer {
    // ...
}
```

### Adding a Customizer Section

```php
namespace AquaLuxe\Customizer\Sections;

class My_Section {
    public function __construct($wp_customize) {
        $this->register_section($wp_customize);
    }

    public function register_section($wp_customize) {
        $wp_customize->add_section(
            'aqualuxe_my_section',
            [
                'title' => __('My Section', 'aqualuxe'),
                'priority' => 30,
                'panel' => 'aqualuxe_theme_options',
            ]
        );

        // Add settings and controls...
    }
}
```

### Adding a Customizer Control

```php
namespace AquaLuxe\Customizer\Controls;

class My_Control extends \WP_Customize_Control {
    public $type = 'my-control';

    public function render_content() {
        // Render control...
    }
}
```

## WooCommerce Integration

AquaLuxe provides comprehensive WooCommerce integration with support for all product types, custom templates, and enhanced functionality.

### WooCommerce Class

The `WooCommerce` class is the main entry point for WooCommerce integration.

```php
namespace AquaLuxe\WooCommerce;

class WooCommerce {
    // ...
}
```

### Adding WooCommerce Support

AquaLuxe automatically adds WooCommerce support when WooCommerce is active. You can extend this support by adding custom templates and functionality.

```php
add_theme_support(
    'woocommerce',
    [
        'thumbnail_image_width' => 400,
        'single_image_width' => 800,
        'product_grid' => [
            'default_rows' => 3,
            'min_rows' => 1,
            'max_rows' => 8,
            'default_columns' => 4,
            'min_columns' => 1,
            'max_columns' => 6,
        ],
    ]
);
```

## Hooks and Filters

AquaLuxe provides numerous hooks and filters for extending functionality.

### Action Hooks

- `aqualuxe_before_header`: Executes before the header.
- `aqualuxe_after_header`: Executes after the header.
- `aqualuxe_before_content`: Executes before the main content.
- `aqualuxe_after_content`: Executes after the main content.
- `aqualuxe_before_footer`: Executes before the footer.
- `aqualuxe_after_footer`: Executes after the footer.
- `aqualuxe_before_sidebar`: Executes before the sidebar.
- `aqualuxe_after_sidebar`: Executes after the sidebar.

### Filter Hooks

- `aqualuxe_header_attributes`: Filters header HTML attributes.
- `aqualuxe_main_attributes`: Filters main content HTML attributes.
- `aqualuxe_sidebar_attributes`: Filters sidebar HTML attributes.
- `aqualuxe_footer_attributes`: Filters footer HTML attributes.
- `aqualuxe_navigation_attributes`: Filters navigation HTML attributes.
- `aqualuxe_content_attributes`: Filters content HTML attributes.
- `aqualuxe_article_attributes`: Filters article HTML attributes.

### Using Hooks

```php
// Action hook example
add_action('aqualuxe_before_header', function() {
    echo '<div class="announcement-bar">Special offer today!</div>';
});

// Filter hook example
add_filter('aqualuxe_header_attributes', function($attributes) {
    $attributes['class'] .= ' sticky-header';
    return $attributes;
});
```

## Templates

AquaLuxe uses a template hierarchy that extends the WordPress template hierarchy. Templates are organized in the `template-parts` directory.

### Template Parts

Template parts are reusable components that can be included in templates using `get_template_part()`.

```php
get_template_part('template-parts/header/header-content');
```

### Template Functions

AquaLuxe provides several template functions for displaying content.

```php
aqualuxe_posted_on(); // Displays post date
aqualuxe_posted_by(); // Displays post author
aqualuxe_entry_footer(); // Displays post footer
aqualuxe_post_thumbnail(); // Displays post thumbnail
```

## Assets

AquaLuxe uses Laravel Mix (a webpack wrapper) for asset compilation. The build system is configured to:

- Compile SCSS to CSS
- Transpile modern JavaScript
- Optimize images
- Generate SVG sprites
- Create critical CSS
- Generate WebP images

### Build Commands

- `npm run dev`: Build assets for development
- `npm run watch`: Build assets and watch for changes
- `npm run prod`: Build assets for production
- `npm run analyze`: Analyze the bundle size
- `npm run lint`: Lint JavaScript files
- `npm run lint:fix`: Fix linting issues in JavaScript files
- `npm run stylelint`: Lint SCSS files
- `npm run stylelint:fix`: Fix linting issues in SCSS files
- `npm run critical`: Generate critical CSS
- `npm run imagemin`: Optimize images
- `npm run svg-sprite`: Generate SVG sprite
- `npm run test`: Run tests

### SCSS Architecture

AquaLuxe uses a 7-1 SCSS architecture pattern:

- `abstracts/`: Variables, mixins, functions, and placeholders
- `base/`: Base styles, typography, and utilities
- `components/`: UI components
- `layout/`: Layout components
- `pages/`: Page-specific styles
- `themes/`: Theme variations (e.g., dark mode)
- `vendors/`: Third-party styles

### JavaScript Architecture

AquaLuxe uses a modular JavaScript architecture:

- `components/`: UI components
- `customizer/`: Customizer scripts
- `dark-mode.js`: Dark mode functionality
- `lazy-loading.js`: Lazy loading functionality
- `navigation.js`: Navigation functionality
- `service-worker.js`: Service worker
- `service-worker-register.js`: Service worker registration

## Performance Optimization

AquaLuxe includes several performance optimization features:

### Critical CSS

Critical CSS is the minimal CSS needed to render the above-the-fold content of a page. AquaLuxe generates critical CSS for different page types and inlines it in the head.

```php
namespace AquaLuxe\Core;

class Critical_CSS {
    // ...
}
```

### Lazy Loading

AquaLuxe implements lazy loading for images and iframes to improve page load performance.

```php
namespace AquaLuxe\Core;

class Lazy_Loading {
    // ...
}
```

### Resource Hints

AquaLuxe uses resource hints to preconnect to external domains and preload critical assets.

```php
namespace AquaLuxe\Core;

class Resource_Hints {
    // ...
}
```

### WebP Support

AquaLuxe supports WebP images with fallbacks for browsers that don't support WebP.

```php
namespace AquaLuxe\Core;

class WebP_Support {
    // ...
}
```

### Browser Caching

AquaLuxe optimizes browser caching to improve page load performance.

```php
namespace AquaLuxe\Core;

class Browser_Caching {
    // ...
}
```

### Minification

AquaLuxe minifies CSS and JavaScript to reduce file size and improve page load performance.

```php
namespace AquaLuxe\Core;

class Minification {
    // ...
}
```

### Script Loading

AquaLuxe optimizes script loading with async and defer attributes.

```php
namespace AquaLuxe\Core;

class Script_Loading {
    // ...
}
```

## Accessibility

AquaLuxe is designed with accessibility in mind and follows WCAG 2.1 AA guidelines.

```php
namespace AquaLuxe\Core;

class Accessibility {
    // ...
}
```

### Skip Links

AquaLuxe provides skip links for keyboard navigation.

```php
<a class="skip-link screen-reader-text" href="#content">Skip to content</a>
```

### ARIA Attributes

AquaLuxe adds ARIA attributes to improve accessibility.

```php
<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="Primary Menu">
    <!-- Navigation content -->
</nav>
```

### Focus Styles

AquaLuxe provides focus styles for keyboard users.

```css
:focus {
    outline: 2px solid var(--primary-color);
    outline-offset: 2px;
}
```

### Screen Reader Text

AquaLuxe provides screen reader text for better accessibility.

```php
<span class="screen-reader-text">Search</span>
```

## Internationalization

AquaLuxe is fully translatable and supports RTL languages.

### Translation Functions

```php
__('Text to translate', 'aqualuxe');
_e('Text to translate and echo', 'aqualuxe');
_x('Text to translate with context', 'context', 'aqualuxe');
_n('Singular', 'Plural', $count, 'aqualuxe');
```

### Text Domain

The text domain for AquaLuxe is `aqualuxe`.

### RTL Support

AquaLuxe supports RTL languages with proper RTL stylesheets.

```php
wp_style_add_data('aqualuxe-style', 'rtl', 'replace');
```

## Dark Mode

AquaLuxe includes a dark mode implementation with system preference detection and user preference storage.

```javascript
// Dark mode toggle
const darkModeToggle = document.querySelector('.theme-toggle');
darkModeToggle.addEventListener('click', () => {
    AquaLuxeDarkMode.toggle();
});

// Check if dark mode is active
if (AquaLuxeDarkMode.isDarkMode()) {
    // Dark mode is active
}

// Set dark mode
AquaLuxeDarkMode.setMode('dark'); // 'dark', 'light', or 'auto'

// Get current mode
const currentMode = AquaLuxeDarkMode.getMode(); // 'dark', 'light', or 'auto'
```

## Extending AquaLuxe

### Creating a Child Theme

1. Create a new directory in `wp-content/themes/` with your child theme name (e.g., `aqualuxe-child`).
2. Create a `style.css` file with the following content:

```css
/*
Theme Name: AquaLuxe Child
Theme URI: https://aqualuxe.example.com/
Description: Child theme for AquaLuxe
Author: Your Name
Author URI: https://example.com/
Template: aqualuxe
Version: 1.0.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: aqualuxe-child
*/
```

3. Create a `functions.php` file with the following content:

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
        [],
        wp_get_theme('aqualuxe')->get('Version')
    );

    wp_enqueue_style(
        'aqualuxe-child-style',
        get_stylesheet_uri(),
        ['aqualuxe-style'],
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles');
```

### Creating a Custom Template

1. Create a new file in your child theme directory with the template name (e.g., `template-custom.php`).
2. Add the template header:

```php
<?php
/**
 * Template Name: Custom Template
 *
 * @package AquaLuxe_Child
 */

get_header();
?>

<main id="primary" class="site-main">
    <!-- Custom template content -->
</main>

<?php
get_footer();
```

### Adding Custom Functionality

1. Create a new file in your child theme directory (e.g., `custom-functions.php`).
2. Include the file in your `functions.php`:

```php
require_once get_stylesheet_directory() . '/custom-functions.php';
```

3. Add your custom functionality to the file:

```php
<?php
/**
 * Custom functions for AquaLuxe Child Theme
 */

// Add custom functionality here
```

## Best Practices

### Code Standards

AquaLuxe follows the WordPress PHP Coding Standards. When extending or customizing the theme, it's recommended to follow these standards.

### Security

AquaLuxe implements security best practices, including:

- Data sanitization
- Data validation
- Nonce verification
- Capability checks
- Proper escaping

When extending or customizing the theme, it's important to follow these security practices.

### Performance

AquaLuxe is optimized for performance. When extending or customizing the theme, it's important to consider performance implications.

### Accessibility

AquaLuxe is designed with accessibility in mind. When extending or customizing the theme, it's important to maintain accessibility.

## Troubleshooting

### Common Issues

- **Theme not loading styles**: Check if the parent theme is installed and activated.
- **WooCommerce templates not working**: Check if WooCommerce is installed and activated.
- **JavaScript errors**: Check the browser console for errors.
- **PHP errors**: Check the WordPress debug log.

### Debugging

AquaLuxe supports WordPress debugging. To enable debugging, add the following to your `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

## Support

For support and documentation, please visit:

- [Documentation](https://aqualuxe.example.com/documentation/)
- [Support Forum](https://aqualuxe.example.com/support/)
- [FAQ](https://aqualuxe.example.com/faq/)

## License

AquaLuxe is licensed under the GPL-2.0-or-later license.