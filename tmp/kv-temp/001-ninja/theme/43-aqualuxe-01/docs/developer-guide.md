# AquaLuxe WordPress Theme - Developer Guide

## Table of Contents
1. [Introduction](#introduction)
2. [Theme Architecture](#theme-architecture)
3. [File Structure](#file-structure)
4. [Core Features](#core-features)
5. [Customization](#customization)
6. [WooCommerce Integration](#woocommerce-integration)
7. [Advanced Features](#advanced-features)
8. [Hooks & Filters](#hooks-filters)
9. [Performance Optimization](#performance-optimization)
10. [Accessibility](#accessibility)
11. [SEO](#seo)
12. [Security](#security)
13. [Multilingual & Multi-currency](#multilingual-multi-currency)
14. [Contributing](#contributing)

## Introduction <a name="introduction"></a>

AquaLuxe is a premium WordPress theme designed specifically for luxury aquatic retail businesses. It features a dual-state architecture that works both with and without WooCommerce, multilingual support, multi-currency capabilities, and a comprehensive set of features tailored for aquatic product stores.

This developer guide provides detailed information about the theme's architecture, customization options, and extension points to help developers understand, customize, and extend the theme.

## Theme Architecture <a name="theme-architecture"></a>

AquaLuxe follows a modular architecture with clear separation of concerns:

### Core Architecture
- **Dual-state functionality**: The theme works seamlessly with or without WooCommerce activated
- **Modular includes**: Functionality is separated into logical modules in the `inc/` directory
- **Template parts**: Reusable template components are stored in `template-parts/`
- **Asset compilation**: Uses Laravel Mix (webpack wrapper) for modern asset management

### Design Principles
- **Mobile-first**: All layouts are designed with mobile-first approach
- **Accessibility**: WCAG 2.1 AA compliance throughout the theme
- **Performance**: Optimized for Core Web Vitals and PageSpeed
- **Extensibility**: Comprehensive hook system for easy customization

## File Structure <a name="file-structure"></a>

```
aqualuxe-theme/
├── assets/                  # Compiled assets (do not edit directly)
│   ├── css/                 # Compiled CSS files
│   ├── js/                  # Compiled JS files
│   └── images/              # Optimized images
├── dist/                    # Compiled and minified assets
│   ├── css/                 # Production CSS
│   ├── js/                  # Production JS
│   └── images/              # Production images
├── docs/                    # Documentation
├── inc/                     # Theme functionality
│   ├── accessibility.php    # Accessibility features
│   ├── blocks.php           # Custom blocks
│   ├── custom-post-types.php # Custom post types
│   ├── custom-taxonomies.php # Custom taxonomies
│   ├── customizer.php       # Theme customizer settings
│   ├── demo-importer.php    # Demo content import
│   ├── helpers.php          # Helper functions
│   ├── hooks.php            # Theme hooks
│   ├── multi-currency.php   # Multi-currency support
│   ├── multilingual.php     # Multilingual support
│   ├── performance.php      # Performance optimizations
│   ├── related-products.php # Related products algorithm
│   ├── review-system.php    # Review system with photos
│   ├── scripts.php          # Script and style enqueuing
│   ├── security-audit.php   # Security audit functionality
│   ├── seo.php              # SEO enhancements
│   ├── shortcodes.php       # Shortcodes
│   ├── template-functions.php # Template functions
│   ├── template-tags.php    # Template tags
│   ├── widgets.php          # Custom widgets
│   ├── woocommerce.php      # WooCommerce integration
│   ├── woocommerce-checkout.php # Custom checkout fields
│   ├── woocommerce-fallback.php # Fallback when WooCommerce is inactive
│   ├── woocommerce-filters.php # Advanced filtering
│   └── woocommerce-stock-notification.php # Stock notifications
├── languages/               # Translation files
├── src/                     # Source files for development
│   ├── js/                  # JavaScript source files
│   ├── scss/                # SCSS source files
│   └── images/              # Original images
├── template-parts/          # Reusable template parts
│   ├── content/             # Content templates
│   ├── footer/              # Footer templates
│   ├── header/              # Header templates
│   └── navigation/          # Navigation templates
├── templates/               # Page templates
├── woocommerce/             # WooCommerce template overrides
├── 404.php                  # 404 template
├── archive.php              # Archive template
├── comments.php             # Comments template
├── footer.php               # Footer template
├── functions.php            # Theme functions
├── header.php               # Header template
├── index.php                # Main template
├── package.json             # NPM dependencies
├── page.php                 # Page template
├── README.md                # Theme readme
├── screenshot.png           # Theme screenshot
├── search.php               # Search template
├── searchform.php           # Search form template
├── sidebar.php              # Sidebar template
├── single.php               # Single post template
├── style.css                # Theme stylesheet
├── tailwind.config.js       # Tailwind CSS configuration
├── theme.json               # Block editor theme settings
└── webpack.mix.js           # Laravel Mix configuration
```

## Core Features <a name="core-features"></a>

### Theme Setup
The theme setup is handled in `functions.php`, which loads all required files and sets up theme features:

```php
function aqualuxe_setup() {
    // Theme features setup
    load_theme_textdomain('aqualuxe', AQUALUXE_DIR . 'languages');
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    add_theme_support('editor-styles');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary'   => esc_html__('Primary Menu', 'aqualuxe'),
        'secondary' => esc_html__('Secondary Menu', 'aqualuxe'),
        'footer'    => esc_html__('Footer Menu', 'aqualuxe'),
        'mobile'    => esc_html__('Mobile Menu', 'aqualuxe'),
    ));
    
    // Custom logo
    add_theme_support('custom-logo', array(
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ));
    
    // Editor color palette
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => esc_html__('Primary', 'aqualuxe'),
            'slug'  => 'primary',
            'color' => '#0073aa',
        ),
        // Additional colors...
    ));
}
```

### Template Hierarchy
AquaLuxe follows the WordPress template hierarchy with additional custom templates:

- `index.php`: Main template file
- `singular.php`: Template for single posts and pages
- `archive.php`: Template for archives
- `search.php`: Template for search results
- Custom templates in the `templates/` directory

## Customization <a name="customization"></a>

### Theme Customizer
The theme includes extensive customizer options in `inc/customizer.php`:

```php
function aqualuxe_customize_register($wp_customize) {
    // Add panels, sections, and settings
    
    // Header Options
    $wp_customize->add_section('aqualuxe_header_options', array(
        'title'    => __('Header Options', 'aqualuxe'),
        'priority' => 30,
    ));
    
    // Add settings and controls
    $wp_customize->add_setting('aqualuxe_header_layout', array(
        'default'           => 'standard',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_header_layout', array(
        'label'    => __('Header Layout', 'aqualuxe'),
        'section'  => 'aqualuxe_header_options',
        'type'     => 'select',
        'choices'  => array(
            'standard' => __('Standard', 'aqualuxe'),
            'centered' => __('Centered', 'aqualuxe'),
            'minimal'  => __('Minimal', 'aqualuxe'),
        ),
    ));
    
    // Additional customizer settings...
}
```

### Custom CSS Variables
The theme uses CSS variables for easy styling customization:

```css
:root {
    --color-primary: #0073aa;
    --color-secondary: #005177;
    --color-accent: #00a0d2;
    --color-dark: #111111;
    --color-light: #f8f9fa;
    --color-white: #ffffff;
    --color-black: #000000;
    
    --font-primary: 'Montserrat', sans-serif;
    --font-secondary: 'Playfair Display', serif;
    
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 1rem;
    --spacing-lg: 2rem;
    --spacing-xl: 4rem;
    
    --border-radius: 4px;
    --box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
```

### Tailwind CSS
The theme uses Tailwind CSS for utility-first styling. The configuration is in `tailwind.config.js`:

```js
module.exports = {
    content: [
        './**/*.php',
        './src/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                primary: 'var(--color-primary)',
                secondary: 'var(--color-secondary)',
                accent: 'var(--color-accent)',
            },
            fontFamily: {
                primary: 'var(--font-primary)',
                secondary: 'var(--font-secondary)',
            },
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
        require('@tailwindcss/forms'),
    ],
};
```

## WooCommerce Integration <a name="woocommerce-integration"></a>

AquaLuxe includes extensive WooCommerce integration with custom features:

### Template Overrides
WooCommerce templates are overridden in the `woocommerce/` directory following the WooCommerce template hierarchy.

### Custom Features

#### Advanced Filtering System
The advanced filtering system in `inc/woocommerce-filters.php` provides:

- AJAX-powered filtering without page reload
- Price range sliders
- Attribute filters with color swatches
- Custom filter widgets
- Mobile-friendly filter toggle

Usage:
```php
// Add a custom filter
add_filter('aqualuxe_product_filters', 'my_custom_filter');
function my_custom_filter($filters) {
    $filters['my_filter'] = array(
        'title' => __('My Filter', 'my-theme'),
        'callback' => 'my_filter_callback',
    );
    return $filters;
}

// Filter callback
function my_filter_callback() {
    // Filter implementation
}
```

#### Custom Checkout Fields
Custom checkout fields in `inc/woocommerce-checkout.php` add aquarium-specific fields:

- Tank size and dimensions
- Water type (freshwater, saltwater, brackish)
- Livestock compatibility check
- Special delivery instructions

#### Stock Notification System
The stock notification system in `inc/woocommerce-stock-notification.php` allows customers to:

- Subscribe to out-of-stock products
- Receive email notifications when products are back in stock
- Manage their notifications in their account

#### Related Products Algorithm
The enhanced related products system in `inc/related-products.php` uses multiple factors:

- Products frequently purchased together
- Products with similar attributes
- Products in the same category
- Products with the same tags
- Products viewed together

#### Review System with Photos
The review system in `inc/review-system.php` allows customers to:

- Upload photos with their reviews
- View photo galleries from other customers
- Filter products by reviews with photos

## Hooks & Filters <a name="hooks-filters"></a>

AquaLuxe provides numerous hooks and filters for extending functionality:

### Action Hooks

```php
// Before header
do_action('aqualuxe_before_header');

// After header
do_action('aqualuxe_after_header');

// Before footer
do_action('aqualuxe_before_footer');

// After footer
do_action('aqualuxe_after_footer');

// Before main content
do_action('aqualuxe_before_main_content');

// After main content
do_action('aqualuxe_after_main_content');

// Before sidebar
do_action('aqualuxe_before_sidebar');

// After sidebar
do_action('aqualuxe_after_sidebar');

// Before single product
do_action('aqualuxe_before_single_product');

// After single product
do_action('aqualuxe_after_single_product');
```

### Filter Hooks

```php
// Modify header classes
$header_classes = apply_filters('aqualuxe_header_classes', $classes);

// Modify footer classes
$footer_classes = apply_filters('aqualuxe_footer_classes', $classes);

// Modify sidebar classes
$sidebar_classes = apply_filters('aqualuxe_sidebar_classes', $classes);

// Modify product classes
$product_classes = apply_filters('aqualuxe_product_classes', $classes);

// Modify related products
$related_products = apply_filters('aqualuxe_related_products', $products);

// Modify checkout fields
$checkout_fields = apply_filters('aqualuxe_checkout_fields', $fields);
```

## Performance Optimization <a name="performance-optimization"></a>

AquaLuxe includes several performance optimizations:

### Asset Loading
- CSS and JavaScript minification and concatenation
- Conditional loading of assets
- Deferred and async loading of scripts
- Critical CSS extraction

### Image Optimization
- Lazy loading of images
- WebP image support
- Responsive images with srcset
- Image compression

### Caching
- Browser caching headers
- Page caching support
- Object caching support

## Accessibility <a name="accessibility"></a>

AquaLuxe is built with accessibility in mind:

### ARIA Landmarks
- Proper ARIA roles for landmarks
- Skip links for keyboard navigation
- Focus management for modals and dropdowns

### Keyboard Navigation
- All interactive elements are keyboard accessible
- Focus styles for interactive elements
- Dropdown menus accessible via keyboard

### Screen Reader Support
- Screen reader text for icons
- Proper alt text for images
- ARIA attributes for dynamic content

## SEO <a name="seo"></a>

AquaLuxe includes several SEO enhancements:

### Schema.org Markup
- Organization schema for the website
- Product schema for WooCommerce products
- Article schema for blog posts
- BreadcrumbList schema for navigation

### Open Graph Tags
- Title, description, and image tags
- Twitter Card support
- Facebook Open Graph support

### XML Sitemap
- Built-in XML sitemap generation
- Customizable sitemap settings
- Automatic ping to search engines

## Security <a name="security"></a>

AquaLuxe includes several security features:

### Input Validation
- Proper sanitization of user input
- Validation of form data
- Escaping of output

### Nonce Verification
- CSRF protection with nonces
- Nonce verification for forms
- Nonce verification for AJAX requests

### Capability Checks
- Proper capability checks for admin functions
- Role-based access control
- User permission validation

### Security Headers
- Content Security Policy
- X-XSS-Protection
- X-Frame-Options
- Referrer-Policy

## Multilingual & Multi-currency <a name="multilingual-multi-currency"></a>

AquaLuxe supports multilingual and multi-currency functionality:

### Multilingual Support
- Compatible with WPML and Polylang
- Translation-ready with .pot file
- RTL support

### Multi-currency Support
- Compatible with WooCommerce Currency Switcher
- Currency conversion
- Currency symbol display options

## Contributing <a name="contributing"></a>

We welcome contributions to AquaLuxe! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/my-feature`
3. Make your changes
4. Run tests: `npm run test`
5. Commit your changes: `git commit -m 'Add my feature'`
6. Push to the branch: `git push origin feature/my-feature`
7. Submit a pull request

### Coding Standards
- Follow WordPress coding standards
- Use ESLint for JavaScript
- Use Stylelint for CSS/SCSS
- Document your code with PHPDoc comments