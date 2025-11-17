# AquaLuxe WordPress Theme - Developer Guide

## Table of Contents

1. [Introduction](#introduction)
2. [Theme Structure](#theme-structure)
3. [Getting Started](#getting-started)
4. [Core Architecture](#core-architecture)
5. [Template Files](#template-files)
6. [Frontend Components](#frontend-components)
7. [WooCommerce Integration](#woocommerce-integration)
8. [Features Implementation](#features-implementation)
9. [Testing & Optimization](#testing--optimization)
10. [Customization](#customization)
11. [Hooks & Filters](#hooks--filters)
12. [Troubleshooting](#troubleshooting)

## Introduction

AquaLuxe is a modern, feature-rich WordPress theme built with a focus on performance, accessibility, and WooCommerce integration. This developer guide provides comprehensive documentation on how to work with, customize, and extend the AquaLuxe theme.

### Key Features

- Object-oriented PHP architecture
- Tailwind CSS for styling
- WooCommerce integration
- Dark mode support
- Multilingual support
- Schema.org markup
- Open Graph metadata
- Lazy loading implementation
- Responsive design
- Accessibility compliance

## Theme Structure

The AquaLuxe theme follows a modular structure to keep code organized and maintainable:

```
aqualuxe/
├── assets/
│   ├── css/
│   ├── js/
│   ├── fonts/
│   └── images/
├── documentation/
├── inc/
│   ├── admin/
│   ├── classes/
│   ├── customizer/
│   └── testing/
├── languages/
├── templates/
│   └── parts/
├── woocommerce/
│   ├── cart/
│   ├── checkout/
│   └── myaccount/
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

### Key Directories

- **assets/**: Contains all CSS, JavaScript, fonts, and images
- **inc/**: Contains PHP classes and functions that power the theme
- **templates/**: Contains template parts used throughout the theme
- **woocommerce/**: Contains WooCommerce template overrides

## Getting Started

### Prerequisites

- WordPress 5.8+
- PHP 7.4+
- MySQL 5.6+ or MariaDB 10.0+
- Node.js 14+ (for development)

### Installation

1. Upload the `aqualuxe` folder to the `/wp-content/themes/` directory
2. Activate the theme through the 'Themes' menu in WordPress
3. Navigate to Appearance > Customize to configure theme settings

### Development Environment Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/aqualuxe.git
   ```

2. Install dependencies:
   ```bash
   cd aqualuxe
   npm install
   ```

3. Start development server:
   ```bash
   npm run dev
   ```

4. Build for production:
   ```bash
   npm run build
   ```

## Core Architecture

AquaLuxe uses an object-oriented approach to organize its functionality. The main theme class is initialized in `functions.php`:

```php
final class AquaLuxe {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Load required files
        $this->load_files();

        // Setup theme
        add_action('after_setup_theme', array($this, 'setup'));

        // Register widget areas
        add_action('widgets_init', array($this, 'widgets_init'));

        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        // Add theme supports
        $this->add_theme_supports();
    }

    // Other methods...
}

// Initialize the theme
AquaLuxe::get_instance();
```

### Autoloader

The theme uses an autoloader to automatically load class files when they are needed:

```php
class AquaLuxe_Autoloader {
    public static function register() {
        spl_autoload_register(array(self::class, 'autoload'));
    }

    public static function autoload($class) {
        // Check if class has AquaLuxe_ prefix
        if (strpos($class, 'AquaLuxe_') !== 0) {
            return;
        }

        // Convert class name to file path
        $class_name = str_replace('AquaLuxe_', '', $class);
        $class_name = str_replace('_', '-', $class_name);
        $class_name = strtolower($class_name);

        // Build file path
        $file_path = AQUALUXE_DIR . '/inc/classes/class-' . $class_name . '.php';

        // Load file if it exists
        if (file_exists($file_path)) {
            require_once $file_path;
        }
    }
}

// Register autoloader
AquaLuxe_Autoloader::register();
```

## Template Files

AquaLuxe includes all standard WordPress template files, plus additional templates for enhanced functionality.

### Main Template Files

- **index.php**: The main template file
- **header.php**: The header template
- **footer.php**: The footer template
- **sidebar.php**: The sidebar template
- **single.php**: The single post template
- **page.php**: The page template
- **archive.php**: The archive template
- **search.php**: The search results template
- **404.php**: The 404 error page template
- **comments.php**: The comments template

### Template Parts

Template parts are reusable components that can be included in multiple templates:

- **templates/content.php**: The default content template
- **templates/content-single.php**: The single post content template
- **templates/content-page.php**: The page content template
- **templates/content-archive.php**: The archive content template
- **templates/content-search.php**: The search results content template
- **templates/content-none.php**: The no results content template
- **templates/parts/header-topbar.php**: The header top bar
- **templates/parts/header-mobile-menu.php**: The mobile menu
- **templates/parts/header-search.php**: The search form
- **templates/parts/pagination.php**: The pagination
- **templates/parts/related-posts.php**: The related posts

### Using Template Parts

To include a template part in your template:

```php
get_template_part('templates/parts/header', 'topbar');
```

## Frontend Components

AquaLuxe includes several frontend components that you can use in your templates.

### Hero Section

The hero section is implemented in `templates/parts/header-topbar.php`:

```php
<div class="hero-section bg-primary text-white py-16">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold mb-4"><?php echo get_the_title(); ?></h1>
        <?php if (has_excerpt()): ?>
            <div class="text-xl"><?php echo get_the_excerpt(); ?></div>
        <?php endif; ?>
    </div>
</div>
```

### Card Component

The card component is used for posts and products:

```php
<div class="card bg-white rounded-lg shadow-md overflow-hidden">
    <?php if (has_post_thumbnail()): ?>
        <div class="card-image">
            <?php the_post_thumbnail('aqualuxe-blog', array('class' => 'w-full h-auto')); ?>
        </div>
    <?php endif; ?>
    <div class="card-content p-6">
        <h2 class="card-title text-2xl font-bold mb-2">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        <div class="card-excerpt mb-4">
            <?php the_excerpt(); ?>
        </div>
        <a href="<?php the_permalink(); ?>" class="btn btn-primary">
            <?php esc_html_e('Read More', 'aqualuxe'); ?>
        </a>
    </div>
</div>
```

### Button Styles

AquaLuxe includes several button styles that you can use:

```html
<button class="btn btn-primary">Primary Button</button>
<button class="btn btn-secondary">Secondary Button</button>
<button class="btn btn-accent">Accent Button</button>
<button class="btn btn-outline">Outline Button</button>
```

### Form Styling

Form styling is implemented in `comments.php` and can be used for any form:

```html
<form class="aqualuxe-form">
    <div class="form-group mb-4">
        <label for="name" class="form-label block mb-2">Name</label>
        <input type="text" id="name" class="form-control w-full px-4 py-2 border rounded-lg">
    </div>
    <div class="form-group mb-4">
        <label for="email" class="form-label block mb-2">Email</label>
        <input type="email" id="email" class="form-control w-full px-4 py-2 border rounded-lg">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
```

### Pagination

Pagination is implemented in `templates/parts/pagination.php`:

```php
<?php
$prev_text = '<span class="icon"><svg>...</svg></span> ' . esc_html__('Previous', 'aqualuxe');
$next_text = esc_html__('Next', 'aqualuxe') . ' <span class="icon"><svg>...</svg></span>';

the_posts_pagination(array(
    'mid_size'  => 2,
    'prev_text' => $prev_text,
    'next_text' => $next_text,
    'screen_reader_text' => esc_html__('Posts Navigation', 'aqualuxe'),
    'class' => 'pagination',
));
?>
```

## WooCommerce Integration

AquaLuxe includes comprehensive WooCommerce integration with custom templates and functionality.

### WooCommerce Setup

WooCommerce support is added in `functions.php`:

```php
private function init_woocommerce() {
    if (!class_exists('WooCommerce')) {
        return;
    }

    // Add theme support for WooCommerce
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // Remove default WooCommerce styles
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');
}
```

### WooCommerce Templates

AquaLuxe overrides the following WooCommerce templates:

- **woocommerce/archive-product.php**: The product archive template
- **woocommerce/single-product.php**: The single product template
- **woocommerce/content-single-product.php**: The single product content template
- **woocommerce/cart/cart.php**: The cart template
- **woocommerce/checkout/form-checkout.php**: The checkout form template
- **woocommerce/myaccount/my-account.php**: The my account template

### WooCommerce Functions

Custom WooCommerce functions are defined in `inc/woocommerce.php`:

```php
/**
 * Add custom WooCommerce functionality
 */
function aqualuxe_woocommerce_setup() {
    // Add product short description support
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // Remove default WooCommerce styles
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_setup');

/**
 * Add custom classes to product loops
 */
function aqualuxe_woocommerce_product_loop_classes($classes) {
    $classes[] = 'product-card';
    return $classes;
}
add_filter('woocommerce_post_class', 'aqualuxe_woocommerce_product_loop_classes', 10, 1);
```

## Features Implementation

### Schema.org Markup

Schema.org markup is implemented in `inc/schema.php`:

```php
/**
 * AquaLuxe Schema Class
 */
class AquaLuxe_Schema {
    /**
     * Constructor
     */
    public function __construct() {
        // Add schema markup to HTML tag
        add_filter('language_attributes', array($this, 'html_schema_markup'));

        // Add schema markup to the body
        add_filter('aqualuxe_body_attributes', array($this, 'body_schema_markup'));

        // Add schema markup to the head
        add_action('wp_head', array($this, 'head_schema_markup'));

        // Add schema markup to the content
        add_filter('the_content', array($this, 'content_schema_markup'));
    }

    // Other methods...
}

// Initialize the schema class
new AquaLuxe_Schema();
```

### Open Graph Metadata

Open Graph metadata is implemented in `inc/open-graph.php`:

```php
/**
 * AquaLuxe Open Graph Class
 */
class AquaLuxe_Open_Graph {
    /**
     * Constructor
     */
    public function __construct() {
        // Add Open Graph meta tags to the head
        add_action('wp_head', array($this, 'add_open_graph_tags'), 5);
        
        // Add Twitter Card meta tags to the head
        add_action('wp_head', array($this, 'add_twitter_card_tags'), 6);
    }

    // Other methods...
}

// Initialize the Open Graph class
new AquaLuxe_Open_Graph();
```

### Lazy Loading

Lazy loading is implemented in `inc/lazy-loading.php`:

```php
/**
 * AquaLuxe Lazy Loading Class
 */
class AquaLuxe_Lazy_Loading {
    /**
     * Constructor
     */
    public function __construct() {
        // Add lazy loading to images in content
        add_filter('the_content', array($this, 'add_lazy_loading_to_content'), 99);
        
        // Add lazy loading to post thumbnails
        add_filter('post_thumbnail_html', array($this, 'add_lazy_loading_to_post_thumbnail'), 10, 5);
        
        // Add lazy loading to images in widgets
        add_filter('widget_text', array($this, 'add_lazy_loading_to_content'), 99);
        
        // Add lazy loading to avatar
        add_filter('get_avatar', array($this, 'add_lazy_loading_to_avatar'), 10);
    }

    // Other methods...
}

// Initialize the Lazy Loading class
new AquaLuxe_Lazy_Loading();
```

### Dark Mode

Dark mode is implemented in `inc/dark-mode.php`:

```php
/**
 * AquaLuxe Dark Mode Class
 */
class AquaLuxe_Dark_Mode {
    /**
     * Constructor
     */
    public function __construct() {
        // Add dark mode toggle to header
        add_action('aqualuxe_header_after_navigation', array($this, 'add_dark_mode_toggle'));
        
        // Add dark mode script
        add_action('wp_enqueue_scripts', array($this, 'enqueue_dark_mode_script'));
        
        // Add dark mode body class
        add_filter('body_class', array($this, 'add_dark_mode_body_class'));
    }

    // Other methods...
}

// Initialize the Dark Mode class
new AquaLuxe_Dark_Mode();
```

### Multilingual Support

Multilingual support is implemented in `inc/multilingual.php`:

```php
/**
 * AquaLuxe Multilingual Class
 */
class AquaLuxe_Multilingual {
    /**
     * Constructor
     */
    public function __construct() {
        // Add language switcher to header
        add_action('aqualuxe_header_after_navigation', array($this, 'add_language_switcher'));
        
        // Add multilingual support for customizer
        add_filter('aqualuxe_customizer_options', array($this, 'filter_customizer_options'));
    }

    // Other methods...
}

// Initialize the Multilingual class
new AquaLuxe_Multilingual();
```

## Testing & Optimization

AquaLuxe includes a comprehensive testing framework to ensure the theme works correctly and is optimized for performance.

### Theme Testing

The theme testing framework is implemented in `inc/testing/theme-test.php`:

```php
/**
 * AquaLuxe Theme Testing Class
 */
class AquaLuxe_Theme_Test {
    /**
     * Constructor
     */
    public function __construct() {
        // Add admin menu
        add_action('admin_menu', array($this, 'add_test_menu'));
        
        // Add AJAX handlers
        add_action('wp_ajax_aqualuxe_run_test', array($this, 'run_test'));
    }

    // Other methods...
}

// Initialize the theme test class
new AquaLuxe_Theme_Test();
```

### Running Tests

To run tests:

1. Log in to the WordPress admin
2. Navigate to Appearance > Theme Testing
3. Select the test category you want to run
4. Click the test button

### Performance Optimization

AquaLuxe includes several performance optimizations:

- **Lazy Loading**: Images and iframes are lazy loaded to improve page load times
- **Minification**: CSS and JavaScript files are minified for production
- **Deferred Loading**: JavaScript files are loaded with the `defer` attribute
- **Critical CSS**: Critical CSS is inlined in the head for faster rendering
- **Image Optimization**: Images are optimized for web use

## Customization

AquaLuxe includes a comprehensive customizer framework for easy theme customization.

### Customizer Framework

The customizer framework is implemented in `inc/customizer/customizer.php`:

```php
/**
 * AquaLuxe Customizer Class
 */
class AquaLuxe_Customizer {
    /**
     * Constructor
     */
    public function __construct($wp_customize) {
        $this->wp_customize = $wp_customize;
    }

    /**
     * Register customizer settings
     */
    public function register() {
        $this->add_panels();
        $this->add_sections();
        $this->add_settings();
    }

    // Other methods...
}
```

### Customizer Sections

AquaLuxe includes the following customizer sections:

- **General Settings**: Basic theme settings
- **Header Settings**: Header layout and styling
- **Footer Settings**: Footer layout and styling
- **Typography Settings**: Font family and size settings
- **Color Settings**: Color scheme settings
- **Blog Settings**: Blog layout and styling
- **WooCommerce Settings**: WooCommerce layout and styling
- **Advanced Settings**: Advanced theme settings

### Adding Custom Customizer Settings

To add custom customizer settings:

```php
/**
 * Add custom customizer settings
 */
function my_custom_customizer_settings($wp_customize) {
    // Add section
    $wp_customize->add_section('my_custom_section', array(
        'title'    => esc_html__('My Custom Section', 'aqualuxe'),
        'priority' => 160,
    ));

    // Add setting
    $wp_customize->add_setting('my_custom_setting', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    // Add control
    $wp_customize->add_control('my_custom_setting', array(
        'label'    => esc_html__('My Custom Setting', 'aqualuxe'),
        'section'  => 'my_custom_section',
        'type'     => 'text',
        'priority' => 10,
    ));
}
add_action('customize_register', 'my_custom_customizer_settings');
```

## Hooks & Filters

AquaLuxe includes several hooks and filters that you can use to customize the theme.

### Action Hooks

- **aqualuxe_before_header**: Fires before the header
- **aqualuxe_after_header**: Fires after the header
- **aqualuxe_before_footer**: Fires before the footer
- **aqualuxe_after_footer**: Fires after the footer
- **aqualuxe_before_content**: Fires before the main content
- **aqualuxe_after_content**: Fires after the main content
- **aqualuxe_before_sidebar**: Fires before the sidebar
- **aqualuxe_after_sidebar**: Fires after the sidebar
- **aqualuxe_before_post**: Fires before each post
- **aqualuxe_after_post**: Fires after each post
- **aqualuxe_before_post_content**: Fires before the post content
- **aqualuxe_after_post_content**: Fires after the post content
- **aqualuxe_before_comments**: Fires before the comments
- **aqualuxe_after_comments**: Fires after the comments

### Filter Hooks

- **aqualuxe_body_classes**: Filters the body classes
- **aqualuxe_post_classes**: Filters the post classes
- **aqualuxe_comment_classes**: Filters the comment classes
- **aqualuxe_excerpt_length**: Filters the excerpt length
- **aqualuxe_excerpt_more**: Filters the excerpt more text
- **aqualuxe_content_width**: Filters the content width
- **aqualuxe_sidebar_position**: Filters the sidebar position
- **aqualuxe_header_layout**: Filters the header layout
- **aqualuxe_footer_layout**: Filters the footer layout
- **aqualuxe_google_fonts**: Filters the Google Fonts
- **aqualuxe_color_scheme**: Filters the color scheme

### Using Hooks

```php
/**
 * Add content before the header
 */
function my_custom_before_header() {
    echo '<div class="announcement-bar bg-accent text-white py-2 text-center">';
    echo esc_html__('Special offer: Free shipping on all orders over $50!', 'aqualuxe');
    echo '</div>';
}
add_action('aqualuxe_before_header', 'my_custom_before_header');

/**
 * Filter the body classes
 */
function my_custom_body_classes($classes) {
    $classes[] = 'my-custom-class';
    return $classes;
}
add_filter('aqualuxe_body_classes', 'my_custom_body_classes');
```

## Troubleshooting

### Common Issues

#### Theme not loading styles

If the theme is not loading styles, check the following:

1. Make sure the theme is activated
2. Check if the CSS files are being enqueued correctly
3. Check for JavaScript errors in the browser console
4. Clear your browser cache

#### WooCommerce templates not working

If WooCommerce templates are not working, check the following:

1. Make sure WooCommerce is installed and activated
2. Check if the theme has WooCommerce support enabled
3. Check if the WooCommerce templates are in the correct location
4. Clear your WooCommerce cache

#### Customizer settings not saving

If customizer settings are not saving, check the following:

1. Make sure you have the necessary permissions
2. Check if the customizer settings are registered correctly
3. Check for JavaScript errors in the browser console
4. Try a different browser

### Getting Help

If you need help with the AquaLuxe theme, you can:

1. Check the documentation
2. Visit the support forum
3. Submit a support ticket
4. Contact the theme author

## License

AquaLuxe is licensed under the GPL v2 or later.

Copyright © 2025 AquaLuxe Theme