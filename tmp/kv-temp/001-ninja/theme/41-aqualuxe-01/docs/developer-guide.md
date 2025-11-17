# AquaLuxe WordPress Theme - Developer Guide

## Table of Contents

1. [Introduction](#introduction)
2. [Theme Structure](#theme-structure)
3. [Core Architecture](#core-architecture)
4. [Template Hierarchy](#template-hierarchy)
5. [Asset Management](#asset-management)
6. [Customizer Options](#customizer-options)
7. [Hooks and Filters](#hooks-and-filters)
8. [WooCommerce Integration](#woocommerce-integration)
9. [Multilingual Support](#multilingual-support)
10. [Multi-Currency Support](#multi-currency-support)
11. [Dark Mode](#dark-mode)
12. [Performance Optimization](#performance-optimization)
13. [Security Implementation](#security-implementation)
14. [Accessibility Features](#accessibility-features)
15. [SEO Features](#seo-features)
16. [Custom Post Types](#custom-post-types)
17. [JavaScript Components](#javascript-components)
18. [CSS Architecture](#css-architecture)
19. [Extending the Theme](#extending-the-theme)
20. [Troubleshooting](#troubleshooting)

## Introduction

AquaLuxe is a comprehensive WordPress theme designed for luxury aquatic retail businesses. It features a multitenant, multivendor, multi-language, multi-currency architecture with full WooCommerce integration. The theme is built with modern web development practices, including Tailwind CSS, modular JavaScript, and a focus on performance, accessibility, and security.

### Key Features

- Dual-state architecture that works with and without WooCommerce
- Multilingual support with WPML/Polylang integration
- Multi-currency support for WooCommerce
- Dark mode with user preference persistence
- Advanced product filtering system
- Quick view functionality for products
- Responsive design with mobile-first approach
- Performance optimizations for fast loading
- Accessibility compliance with WCAG 2.1 guidelines
- SEO optimizations with schema.org markup
- Security hardening with proper sanitization and escaping

## Theme Structure

The AquaLuxe theme follows a modular structure to keep code organized and maintainable:

```
aqualuxe-theme/
├── assets/
│   ├── dist/           # Compiled assets
│   │   ├── css/
│   │   ├── fonts/
│   │   ├── images/
│   │   └── js/
│   └── src/            # Source assets
│       ├── css/
│       ├── fonts/
│       ├── images/
│       └── js/
├── docs/               # Documentation
├── inc/                # Theme functionality
│   ├── customizer.php
│   ├── helpers.php
│   ├── hooks.php
│   ├── multi-currency.php
│   ├── multilingual.php
│   ├── performance.php
│   ├── post-types.php
│   ├── schema.php
│   ├── security.php
│   ├── social-meta.php
│   ├── taxonomies.php
│   ├── template-functions.php
│   ├── template-tags.php
│   └── woocommerce.php
├── languages/          # Translation files
├── template-parts/     # Reusable template parts
│   ├── components/
│   ├── content/
│   ├── footer/
│   └── header/
├── templates/          # Page templates
├── woocommerce/        # WooCommerce template overrides
├── 404.php
├── archive.php
├── comments.php
├── footer.php
├── functions.php
├── header.php
├── index.php
├── page.php
├── screenshot.png
├── search.php
├── searchform.php
├── sidebar.php
├── single.php
├── style.css
├── tailwind.config.js
└── webpack.mix.js
```

## Core Architecture

AquaLuxe uses a class-based architecture for its core functionality, with each major feature encapsulated in its own class. The main theme class is initialized in `functions.php` and handles loading all required files and setting up theme features.

### Main Theme Class

```php
class AquaLuxe_Theme {
    /**
     * Constructor
     */
    public function __construct() {
        // Define constants
        $this->define_constants();
        
        // Load dependencies
        $this->load_dependencies();
        
        // Setup theme features
        $this->setup_theme();
        
        // Initialize components
        $this->init_components();
    }
    
    // Other methods...
}
```

### Dual-State Architecture

The theme is designed to work with or without WooCommerce activated. This is achieved through conditional loading of WooCommerce-specific functionality and graceful fallbacks when WooCommerce is not available.

```php
// Check if WooCommerce is active
function aqualuxe_is_woocommerce_active() {
    return class_exists( 'WooCommerce' );
}

// Conditionally load WooCommerce functionality
if ( aqualuxe_is_woocommerce_active() ) {
    require_once get_template_directory() . '/inc/woocommerce.php';
}
```

## Template Hierarchy

AquaLuxe follows the WordPress template hierarchy with some enhancements for better organization and reusability:

1. **Main Templates**: Standard WordPress templates like `index.php`, `single.php`, `page.php`, etc.
2. **Template Parts**: Reusable components in the `template-parts/` directory
3. **Page Templates**: Custom page templates in the `templates/` directory
4. **WooCommerce Templates**: Custom WooCommerce templates in the `woocommerce/` directory

### Template Parts Structure

Template parts are organized into subdirectories based on their purpose:

- `components/`: Reusable UI components like pagination, breadcrumbs, etc.
- `content/`: Content display templates for different post types
- `footer/`: Footer-specific components
- `header/`: Header-specific components

## Asset Management

AquaLuxe uses Laravel Mix (webpack wrapper) for asset compilation and management.

### Compilation Process

1. Source files are stored in `assets/src/`
2. Compiled files are output to `assets/dist/`
3. The `webpack.mix.js` file configures the compilation process

### Using Laravel Mix

```javascript
// webpack.mix.js
const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

// Set public path
mix.setPublicPath('assets/dist');

// Compile SCSS
mix.sass('assets/src/css/main.scss', 'css')
   .options({
      processCssUrls: false,
      postCss: [
         tailwindcss('./tailwind.config.js'),
         require('autoprefixer')
      ],
   });

// Compile JavaScript
mix.js('assets/src/js/main.js', 'js')
   .js('assets/src/js/darkmode.js', 'js')
   .js('assets/src/js/navigation.js', 'js');

// WooCommerce specific scripts
if (process.env.WITH_WOOCOMMERCE === 'true') {
   mix.js('assets/src/js/woocommerce/product-gallery.js', 'js/woocommerce')
      .js('assets/src/js/woocommerce/quick-view.js', 'js/woocommerce')
      .js('assets/src/js/woocommerce/cart.js', 'js/woocommerce')
      .js('assets/src/js/woocommerce/checkout.js', 'js/woocommerce');
}
```

### Enqueuing Assets

Assets are enqueued in the theme using WordPress's standard enqueuing functions with cache busting:

```php
/**
 * Enqueue scripts and styles
 */
function aqualuxe_scripts() {
    // Enqueue styles
    wp_enqueue_style( 'aqualuxe-style', get_template_directory_uri() . '/assets/dist/css/main.css', array(), AQUALUXE_VERSION );
    
    // Enqueue scripts
    wp_enqueue_script( 'aqualuxe-navigation', get_template_directory_uri() . '/assets/dist/js/navigation.js', array(), AQUALUXE_VERSION, true );
    wp_enqueue_script( 'aqualuxe-darkmode', get_template_directory_uri() . '/assets/dist/js/darkmode.js', array(), AQUALUXE_VERSION, true );
    wp_enqueue_script( 'aqualuxe-main', get_template_directory_uri() . '/assets/dist/js/main.js', array( 'jquery', 'alpine' ), AQUALUXE_VERSION, true );
    
    // Conditionally load WooCommerce scripts
    if ( aqualuxe_is_woocommerce_active() && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
        wp_enqueue_script( 'aqualuxe-product-gallery', get_template_directory_uri() . '/assets/dist/js/woocommerce/product-gallery.js', array( 'jquery' ), AQUALUXE_VERSION, true );
        wp_enqueue_script( 'aqualuxe-quick-view', get_template_directory_uri() . '/assets/dist/js/woocommerce/quick-view.js', array( 'jquery' ), AQUALUXE_VERSION, true );
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_scripts' );
```

## Customizer Options

AquaLuxe provides extensive customization options through the WordPress Customizer. These are organized into sections for better user experience:

1. **Site Identity**: Logo, site title, tagline, etc.
2. **Colors**: Primary, secondary, accent, and dark mode colors
3. **Typography**: Font families, sizes, and weights
4. **Layout**: Container width, sidebar position, etc.
5. **Header**: Header layout, navigation style, etc.
6. **Footer**: Footer layout, widgets, etc.
7. **Blog**: Blog layout, post meta display, etc.
8. **WooCommerce**: Shop layout, product display, etc.
9. **Social Media**: Social media links and sharing options
10. **Performance**: Performance optimization options
11. **Advanced**: Custom CSS, JavaScript, etc.

### Adding Customizer Options

Customizer options are added in `inc/customizer.php`:

```php
/**
 * Add customizer options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register( $wp_customize ) {
    // Add sections, settings, and controls
    
    // Example: Adding a color setting
    $wp_customize->add_setting( 'aqualuxe_primary_color', array(
        'default'           => '#0ea5e9',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_primary_color', array(
        'label'    => __( 'Primary Color', 'aqualuxe' ),
        'section'  => 'colors',
        'settings' => 'aqualuxe_primary_color',
    ) ) );
}
add_action( 'customize_register', 'aqualuxe_customize_register' );
```

## Hooks and Filters

AquaLuxe provides a comprehensive set of hooks and filters to allow for easy customization and extension. These are documented in `inc/hooks.php`.

### Action Hooks

```php
/**
 * Before header
 */
function aqualuxe_before_header() {
    do_action( 'aqualuxe_before_header' );
}

/**
 * After header
 */
function aqualuxe_after_header() {
    do_action( 'aqualuxe_after_header' );
}

// More action hooks...
```

### Filter Hooks

```php
/**
 * Filter the excerpt length
 *
 * @param int $length Excerpt length.
 * @return int Modified excerpt length.
 */
function aqualuxe_excerpt_length( $length ) {
    return apply_filters( 'aqualuxe_excerpt_length', 30 );
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length' );

// More filter hooks...
```

## WooCommerce Integration

AquaLuxe provides deep integration with WooCommerce, with custom templates and functionality to enhance the shopping experience.

### Template Overrides

WooCommerce templates are overridden in the `woocommerce/` directory. These templates are organized according to the WooCommerce template structure:

- `archive-product.php`: Shop page template
- `single-product.php`: Single product page template
- `content-product.php`: Product loop item template
- `cart/`: Cart page templates
- `checkout/`: Checkout page templates
- `myaccount/`: My Account page templates
- `global/`: Global templates like quantity inputs, etc.

### Custom WooCommerce Functionality

Custom WooCommerce functionality is added in `inc/woocommerce.php`:

```php
/**
 * WooCommerce setup function.
 */
function aqualuxe_woocommerce_setup() {
    // Add theme support for WooCommerce
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    
    // Remove default WooCommerce styles
    add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
}
add_action( 'after_setup_theme', 'aqualuxe_woocommerce_setup' );
```

### Product Filtering System

AquaLuxe includes an advanced product filtering system that allows users to filter products by various attributes, price range, rating, etc. This is implemented using AJAX to provide a seamless user experience.

```php
/**
 * Handle AJAX product filtering
 */
function aqualuxe_filter_products() {
    // Check nonce
    check_ajax_referer( 'aqualuxe_ajax_nonce', 'security' );
    
    // Parse form data
    parse_str( $_POST['form_data'], $form_data );
    
    // Build query args
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => get_option( 'posts_per_page' ),
        'paged'          => isset( $form_data['paged'] ) ? absint( $form_data['paged'] ) : 1,
    );
    
    // Add tax query for categories, tags, attributes, etc.
    
    // Add meta query for price, rating, etc.
    
    // Run query
    $query = new WP_Query( $args );
    
    // Prepare response
    $response = array(
        'products'     => aqualuxe_get_filtered_products_html( $query ),
        'pagination'   => aqualuxe_get_filtered_pagination_html( $query ),
        'result_count' => aqualuxe_get_filtered_result_count_html( $query ),
    );
    
    wp_send_json_success( $response );
}
add_action( 'wp_ajax_aqualuxe_filter_products', 'aqualuxe_filter_products' );
add_action( 'wp_ajax_nopriv_aqualuxe_filter_products', 'aqualuxe_filter_products' );
```

## Multilingual Support

AquaLuxe is fully compatible with popular multilingual plugins like WPML and Polylang. The theme includes specific functions to handle multilingual content in `inc/multilingual.php`.

### Language Switcher

The theme includes a language switcher component that can be displayed in the header or footer:

```php
/**
 * Display language switcher
 */
function aqualuxe_language_switcher() {
    // Check if multilingual is active
    if ( ! aqualuxe_is_multilingual_active() ) {
        return;
    }
    
    // Get available languages
    $languages = aqualuxe_get_available_languages();
    
    if ( empty( $languages ) || count( $languages ) <= 1 ) {
        return;
    }
    
    // Get current language
    $current_language = aqualuxe_get_current_language();
    
    // Display language switcher
    include get_template_directory() . '/template-parts/header/language-switcher.php';
}
```

### Translation Functions

The theme uses WordPress's standard translation functions for all user-facing strings:

```php
__( 'Text to translate', 'aqualuxe' )
_e( 'Text to translate and echo', 'aqualuxe' )
esc_html__( 'Text to translate and escape', 'aqualuxe' )
esc_html_e( 'Text to translate, escape, and echo', 'aqualuxe' )
```

## Multi-Currency Support

AquaLuxe includes built-in support for multiple currencies when using WooCommerce. This functionality is implemented in `inc/multi-currency.php`.

### Currency Switcher

The theme includes a currency switcher component that can be displayed in the header or footer:

```php
/**
 * Display currency switcher
 */
function aqualuxe_currency_switcher() {
    // Check if WooCommerce and multi-currency are active
    if ( ! aqualuxe_is_woocommerce_active() || ! aqualuxe_is_multi_currency_active() ) {
        return;
    }
    
    // Get available currencies
    $currencies = aqualuxe_get_available_currencies();
    
    if ( empty( $currencies ) || count( $currencies ) <= 1 ) {
        return;
    }
    
    // Get current currency
    $current_currency = aqualuxe_get_current_currency();
    
    // Display currency switcher
    include get_template_directory() . '/template-parts/footer/currency-switcher.php';
}
```

## Dark Mode

AquaLuxe includes a dark mode feature that allows users to switch between light and dark themes. This preference is saved using localStorage and persists across visits.

### Dark Mode Toggle

The dark mode toggle is implemented in `template-parts/header/dark-mode-toggle.php` and uses Alpine.js for interactivity:

```php
<div x-data class="dark-mode-toggle">
    <button 
        @click="$store.theme.toggleDarkMode()" 
        class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 dark:hover:bg-dark-700 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors"
        aria-label="<?php esc_attr_e( 'Toggle dark mode', 'aqualuxe' ); ?>"
    >
        <!-- Sun icon (shown in dark mode) -->
        <svg 
            x-cloak
            x-show="$store.theme.darkMode" 
            class="w-5 h-5 text-yellow-400" 
            xmlns="http://www.w3.org/2000/svg" 
            fill="none" 
            viewBox="0 0 24 24" 
            stroke="currentColor"
        >
            <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" 
            />
        </svg>
        
        <!-- Moon icon (shown in light mode) -->
        <svg 
            x-cloak
            x-show="!$store.theme.darkMode" 
            class="w-5 h-5 text-dark-700" 
            xmlns="http://www.w3.org/2000/svg" 
            fill="none" 
            viewBox="0 0 24 24" 
            stroke="currentColor"
        >
            <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" 
            />
        </svg>
    </button>
</div>
```

### Dark Mode JavaScript

The dark mode functionality is implemented in `assets/src/js/darkmode.js`:

```javascript
/**
 * AquaLuxe Theme Dark Mode
 *
 * Handles dark mode functionality and persistence.
 */

// This script runs immediately to prevent FOUC (Flash of Unstyled Content)
(function() {
    // Check for saved theme preference or use OS preference
    const darkModeEnabled = 
        localStorage.getItem('aqualuxe_dark_mode') === 'true' || 
        (!localStorage.getItem('aqualuxe_dark_mode') && 
         window.matchMedia('(prefers-color-scheme: dark)').matches);
    
    // Apply theme immediately
    if (darkModeEnabled) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
})();
```

## Performance Optimization

AquaLuxe includes various performance optimizations to ensure fast loading times and a smooth user experience. These are implemented in `inc/performance.php`.

### Asset Minification

Assets are minified during the build process using Laravel Mix:

```javascript
// webpack.mix.js
if (mix.inProduction()) {
    mix.version();
}
```

### Cache Busting

Cache busting is implemented for all theme assets:

```php
/**
 * Add cache busting to stylesheets and scripts
 *
 * @param string $src    The source URL of the resource.
 * @param string $handle The resource handle.
 * @return string Modified source URL.
 */
function aqualuxe_add_cache_busting( $src, $handle ) {
    if ( strpos( $handle, 'aqualuxe-' ) === 0 ) {
        // Only apply to theme assets
        $theme_version = wp_get_theme()->get( 'Version' );
        
        // Check if the file exists and get its modification time
        $file_path = str_replace( get_template_directory_uri(), get_template_directory(), $src );
        $file_path = preg_replace( '/\?.*/', '', $file_path );
        
        if ( file_exists( $file_path ) ) {
            $version = filemtime( $file_path );
        } else {
            $version = $theme_version;
        }
        
        // Remove existing version and add our version
        $src = remove_query_arg( 'ver', $src );
        $src = add_query_arg( 'ver', $version, $src );
    }
    
    return $src;
}
```

### Lazy Loading

Images and iframes are lazy loaded to improve initial page load time:

```php
/**
 * Add lazy loading to images and iframes in content
 *
 * @param string $content The content.
 * @return string Modified content.
 */
function aqualuxe_add_lazy_loading( $content ) {
    // Skip if content is empty
    if ( empty( $content ) ) {
        return $content;
    }
    
    // Don't lazy load in admin or feeds
    if ( is_admin() || is_feed() ) {
        return $content;
    }
    
    // Don't lazy load AMP pages
    if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
        return $content;
    }
    
    // Add loading="lazy" to images
    $content = preg_replace( '/<img(.*?)>/i', '<img$1 loading="lazy">', $content );
    
    // Add loading="lazy" to iframes
    $content = preg_replace( '/<iframe(.*?)>/i', '<iframe$1 loading="lazy">', $content );
    
    return $content;
}
```

### Critical CSS

Critical CSS is inlined in the head to improve perceived loading time:

```php
/**
 * Add critical CSS
 */
function aqualuxe_add_critical_css() {
    $critical_css_path = get_template_directory() . '/assets/dist/css/critical.css';
    
    if ( file_exists( $critical_css_path ) ) {
        echo '<style id="aqualuxe-critical-css">';
        // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
        echo file_get_contents( $critical_css_path );
        echo '</style>' . "\n";
    }
}
```

## Security Implementation

AquaLuxe includes various security measures to protect against common vulnerabilities. These are implemented in `inc/security.php`.

### Input Sanitization

All user input is properly sanitized:

```php
/**
 * Sanitize text field with additional security measures
 *
 * @param string $input The input to sanitize.
 * @return string Sanitized input.
 */
function aqualuxe_sanitize_text( $input ) {
    return sanitize_text_field( $input );
}
```

### Output Escaping

All output is properly escaped:

```php
/**
 * Escape HTML with additional security measures
 *
 * @param string $input The input to escape.
 * @return string Escaped input.
 */
function aqualuxe_esc_html( $input ) {
    return esc_html( $input );
}
```

### Nonce Verification

Nonces are used to protect against CSRF attacks:

```php
/**
 * Verify nonce with additional security measures
 *
 * @param string $nonce  The nonce to verify.
 * @param string $action The nonce action.
 * @return bool Whether the nonce is valid.
 */
function aqualuxe_verify_nonce( $nonce, $action ) {
    return wp_verify_nonce( $nonce, $action );
}
```

### User Capability Checks

User capabilities are checked before performing actions:

```php
/**
 * Check user capability with additional security measures
 *
 * @param string $capability The capability to check.
 * @param int    $user_id    The user ID to check.
 * @return bool Whether the user has the capability.
 */
function aqualuxe_user_can( $capability, $user_id = null ) {
    if ( null === $user_id ) {
        $user_id = get_current_user_id();
    }
    
    return user_can( $user_id, $capability );
}
```

## Accessibility Features

AquaLuxe is built with accessibility in mind, following WCAG 2.1 guidelines. Key accessibility features include:

1. **Semantic HTML**: Proper use of HTML5 semantic elements
2. **ARIA Attributes**: ARIA roles, states, and properties where needed
3. **Keyboard Navigation**: Full keyboard navigation support
4. **Focus Management**: Proper focus management for interactive elements
5. **Color Contrast**: Sufficient color contrast for text and UI elements
6. **Screen Reader Support**: Screen reader friendly content and navigation
7. **Skip Links**: Skip to content links for keyboard users
8. **Form Labels**: Properly labeled form fields
9. **Error Messages**: Accessible error messages for forms
10. **Responsive Design**: Accessible on all screen sizes

## SEO Features

AquaLuxe includes various SEO optimizations to improve search engine visibility:

1. **Schema.org Markup**: Structured data for better search engine understanding
2. **Open Graph Tags**: Social media sharing optimization
3. **Twitter Cards**: Twitter-specific meta tags
4. **Semantic HTML**: Proper use of headings and semantic elements
5. **Breadcrumbs**: SEO-friendly breadcrumb navigation
6. **XML Sitemap**: Support for XML sitemaps
7. **Canonical URLs**: Proper canonical URL handling
8. **Meta Tags**: Title, description, and keyword meta tags
9. **Responsive Design**: Mobile-friendly design for better rankings
10. **Performance Optimization**: Fast loading times for better user experience

## Custom Post Types

AquaLuxe includes several custom post types for specific content types:

1. **Testimonials**: Customer testimonials
2. **Team Members**: Staff profiles
3. **Services**: Service offerings
4. **Projects**: Portfolio projects
5. **Events**: Upcoming events
6. **FAQs**: Frequently asked questions

These are implemented in `inc/post-types.php`:

```php
/**
 * Register custom post types
 */
function aqualuxe_register_post_types() {
    // Register testimonials post type
    register_post_type( 'testimonial', array(
        'labels'              => array(
            'name'               => __( 'Testimonials', 'aqualuxe' ),
            'singular_name'      => __( 'Testimonial', 'aqualuxe' ),
            // Other labels...
        ),
        'public'              => true,
        'has_archive'         => true,
        'supports'            => array( 'title', 'editor', 'thumbnail' ),
        'menu_icon'           => 'dashicons-format-quote',
        'show_in_rest'        => true,
    ) );
    
    // Register other post types...
}
add_action( 'init', 'aqualuxe_register_post_types' );
```

## JavaScript Components

AquaLuxe includes several JavaScript components for interactive elements:

1. **Dropdown**: Dropdown menus
2. **Modal**: Modal dialogs
3. **Tabs**: Tabbed content
4. **Accordion**: Accordion content
5. **Quick View**: Product quick view
6. **Product Gallery**: Product image gallery
7. **Product Filter**: AJAX product filtering
8. **Dark Mode**: Dark mode toggle

These are implemented as modular JavaScript files in `assets/src/js/components/` and `assets/src/js/woocommerce/`.

## CSS Architecture

AquaLuxe uses a combination of Tailwind CSS and custom SCSS for styling:

1. **Tailwind CSS**: Utility-first CSS framework
2. **Custom SCSS**: Custom styles for specific components
3. **Dark Mode**: Dark mode styles using Tailwind's dark mode feature
4. **Responsive Design**: Mobile-first responsive design
5. **CSS Variables**: Custom properties for theming

The CSS architecture is organized as follows:

```
assets/src/css/
├── base/           # Base styles
├── components/     # Component styles
├── layout/         # Layout styles
├── pages/          # Page-specific styles
├── tailwind/       # Tailwind imports
├── utilities/      # Utility styles
├── variables/      # CSS variables
├── vendor/         # Third-party styles
└── woocommerce/    # WooCommerce styles
```

## Extending the Theme

AquaLuxe is designed to be easily extended through child themes or plugins. Here are some common extension points:

### Child Theme

Create a child theme to customize the theme without modifying the parent theme:

```php
/*
Theme Name: AquaLuxe Child
Theme URI: https://example.com/aqualuxe-child
Description: Child theme for AquaLuxe
Author: Your Name
Author URI: https://example.com
Template: aqualuxe
Version: 1.0.0
*/

// Enqueue parent and child theme styles
function aqualuxe_child_enqueue_styles() {
    wp_enqueue_style( 'aqualuxe-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'aqualuxe-child-style', get_stylesheet_directory_uri() . '/style.css', array( 'aqualuxe-style' ) );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles' );
```

### Hooks and Filters

Use the theme's hooks and filters to customize functionality:

```php
// Add content before header
function my_custom_before_header() {
    echo '<div class="announcement-bar">Special offer: Free shipping on orders over $50!</div>';
}
add_action( 'aqualuxe_before_header', 'my_custom_before_header' );

// Modify excerpt length
function my_custom_excerpt_length( $length ) {
    return 20;
}
add_filter( 'aqualuxe_excerpt_length', 'my_custom_excerpt_length' );
```

### Template Overrides

Override template files in a child theme:

1. Copy a template file from the parent theme to the child theme
2. Modify the file in the child theme
3. WordPress will use the child theme version instead of the parent theme version

## Troubleshooting

### Common Issues

1. **Styles Not Loading**: Check if the theme is properly enqueuing styles and if the compiled CSS files exist
2. **JavaScript Errors**: Check the browser console for JavaScript errors
3. **WooCommerce Templates Not Working**: Check if the theme is properly supporting WooCommerce
4. **Multilingual Issues**: Check if the theme is properly supporting multilingual plugins
5. **Performance Issues**: Check if the theme is properly optimizing assets and using caching

### Debugging

Enable WordPress debugging to help identify issues:

```php
// wp-config.php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

### Support

For support, please contact the theme author at support@aqualuxe.com or visit the support forum at https://aqualuxe.com/support.