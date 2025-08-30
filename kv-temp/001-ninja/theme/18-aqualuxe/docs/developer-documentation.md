# AquaLuxe WordPress Theme - Developer Documentation

## Table of Contents
1. [Introduction](#introduction)
2. [Theme Structure](#theme-structure)
3. [Theme Setup](#theme-setup)
4. [Template Hierarchy](#template-hierarchy)
5. [Custom Post Types](#custom-post-types)
6. [Custom Taxonomies](#custom-taxonomies)
7. [Theme Features](#theme-features)
8. [WooCommerce Integration](#woocommerce-integration)
9. [SEO Implementation](#seo-implementation)
10. [Performance Optimization](#performance-optimization)
11. [Hooks and Filters](#hooks-and-filters)
12. [Function Reference](#function-reference)
13. [Customization](#customization)
14. [Troubleshooting](#troubleshooting)

## Introduction

AquaLuxe is a premium WordPress theme designed specifically for ornamental fish farming businesses. It features a modern, responsive design with WooCommerce integration, dark mode support, and multilingual capabilities.

This documentation is intended for developers who want to customize or extend the AquaLuxe theme.

### Version Information
- **Theme Version:** 1.0.0
- **WordPress Compatibility:** 5.8+
- **WooCommerce Compatibility:** 6.0+
- **PHP Compatibility:** 7.4+

## Theme Structure

The AquaLuxe theme follows a modular structure for better organization and maintainability:

```
aqualuxe/
├── assets/
│   ├── dist/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   ├── src/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── fonts/
├── inc/
│   ├── custom-post-types.php
│   ├── custom-taxonomies.php
│   ├── customizer.php
│   ├── helpers.php
│   ├── multilingual.php
│   ├── rest-api.php
│   ├── schema.php
│   ├── open-graph.php
│   ├── image-optimization.php
│   ├── caching.php
│   ├── template-functions.php
│   └── template-tags.php
├── template-parts/
│   ├── content.php
│   ├── content-search.php
│   ├── content-none.php
│   ├── biography.php
│   └── related-posts.php
├── templates/
│   └── (custom page templates)
├── woocommerce/
│   ├── archive-product.php
│   ├── content-product.php
│   ├── content-single-product.php
│   ├── cart/
│   └── checkout/
├── languages/
├── docs/
├── 404.php
├── archive.php
├── comments.php
├── footer.php
├── front-page.php
├── functions.php
├── header.php
├── index.php
├── page.php
├── search.php
├── sidebar.php
├── single.php
├── single-care-guide.php
├── single-faq.php
├── single-fish-species.php
├── single-team.php
├── single-testimonial.php
├── style.css
├── tailwind.config.js
└── webpack.mix.js
```

### Key Directories and Files

- **assets/**: Contains all theme assets including CSS, JavaScript, and images
  - **dist/**: Compiled and optimized assets
  - **src/**: Source files for development
- **inc/**: PHP includes for theme functionality
- **template-parts/**: Reusable template parts
- **templates/**: Custom page templates
- **woocommerce/**: WooCommerce template overrides
- **functions.php**: Main theme functions and initialization
- **style.css**: Theme information and basic styles

## Theme Setup

The AquaLuxe theme is initialized through the `AquaLuxe` class in `functions.php`. This class follows a singleton pattern to ensure only one instance is created.

### Theme Initialization

```php
// Initialize the theme
AquaLuxe::instance();
```

### Theme Features

The theme sets up the following features during initialization:

- **Text Domain**: `aqualuxe`
- **Theme Support**:
  - Automatic feed links
  - Title tag
  - Post thumbnails
  - HTML5 support
  - Customize selective refresh for widgets
  - Editor styles
  - Responsive embeds
  - Custom line height
  - Experimental link color
  - Custom units
  - Custom spacing
  - Custom logo
  - Custom background
  - Post formats
  - Align wide
  - Editor color palette

### Image Sizes

The theme registers the following custom image sizes:

```php
// Set post thumbnail size
set_post_thumbnail_size(1200, 9999);

// Add custom image sizes
add_image_size('aqualuxe-featured', 1600, 900, true);
add_image_size('aqualuxe-card', 600, 400, true);
add_image_size('aqualuxe-thumbnail', 300, 300, true);

// Responsive image sizes
add_image_size('aqualuxe-small', 400, 9999);
add_image_size('aqualuxe-medium', 800, 9999);
add_image_size('aqualuxe-large', 1200, 9999);
add_image_size('aqualuxe-xlarge', 1600, 9999);
add_image_size('aqualuxe-xxlarge', 2000, 9999);

// Featured image sizes
add_image_size('aqualuxe-featured-small', 600, 400, true);
add_image_size('aqualuxe-featured-medium', 900, 600, true);
add_image_size('aqualuxe-featured-large', 1200, 800, true);

// Product image sizes
add_image_size('aqualuxe-product-thumbnail', 300, 300, true);
add_image_size('aqualuxe-product-small', 600, 600, true);
add_image_size('aqualuxe-product-medium', 800, 800, true);
add_image_size('aqualuxe-product-large', 1000, 1000, true);
```

### Navigation Menus

The theme registers the following navigation menus:

```php
register_nav_menus([
    'primary' => esc_html__('Primary Menu', 'aqualuxe'),
    'footer' => esc_html__('Footer Menu', 'aqualuxe'),
    'social' => esc_html__('Social Links Menu', 'aqualuxe'),
    'mobile' => esc_html__('Mobile Menu', 'aqualuxe'),
]);
```

### Widget Areas

The theme registers the following widget areas:

```php
register_sidebar([
    'name'          => esc_html__('Blog Sidebar', 'aqualuxe'),
    'id'            => 'sidebar-1',
    'description'   => esc_html__('Add widgets here to appear in your blog sidebar.', 'aqualuxe'),
    'before_widget' => '<div id="%1$s" class="widget %2$s mb-8">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="widget-title text-xl font-bold mb-4">',
    'after_title'   => '</h3>',
]);

register_sidebar([
    'name'          => esc_html__('Shop Sidebar', 'aqualuxe'),
    'id'            => 'sidebar-shop',
    'description'   => esc_html__('Add widgets here to appear in your shop sidebar.', 'aqualuxe'),
    'before_widget' => '<div id="%1$s" class="widget %2$s mb-8">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="widget-title text-xl font-bold mb-4">',
    'after_title'   => '</h3>',
]);

// Footer widget areas
register_sidebar([
    'name'          => esc_html__('Footer 1', 'aqualuxe'),
    'id'            => 'footer-1',
    'description'   => esc_html__('Add widgets here to appear in footer column 1.', 'aqualuxe'),
    'before_widget' => '<div id="%1$s" class="widget %2$s mb-6">',
    'after_widget'  => '</div>',
    'before_title'  => '<h4 class="widget-title text-lg font-bold mb-4">',
    'after_title'   => '</h4>',
]);

// Footer 2, 3, and 4 are also registered similarly
```

## Template Hierarchy

AquaLuxe follows the WordPress template hierarchy with some custom templates for specific content types:

### Core Templates
- **index.php**: Main template file
- **header.php**: Site header with navigation
- **footer.php**: Site footer with widgets
- **sidebar.php**: Sidebar template
- **front-page.php**: Homepage template
- **page.php**: Static page template
- **single.php**: Single post template
- **archive.php**: Archive template
- **search.php**: Search results template
- **404.php**: Error page template
- **comments.php**: Comments template

### Custom Post Type Templates
- **single-fish-species.php**: Fish species post type template
- **single-care-guide.php**: Care guide post type template
- **single-team.php**: Team member post type template
- **single-testimonial.php**: Testimonial post type template
- **single-faq.php**: FAQ post type template

### WooCommerce Templates
- **woocommerce.php**: Main WooCommerce template
- **woocommerce/archive-product.php**: Product archive template
- **woocommerce/content-product.php**: Product content template
- **woocommerce/content-single-product.php**: Single product content template
- **woocommerce/cart/**: Cart templates
- **woocommerce/checkout/**: Checkout templates

## Custom Post Types

AquaLuxe includes several custom post types for specific content needs. These are defined in `inc/custom-post-types.php`:

### Fish Species
```php
register_post_type('fish-species', [
    'labels' => [
        'name' => __('Fish Species', 'aqualuxe'),
        'singular_name' => __('Fish Species', 'aqualuxe'),
        // Other labels...
    ],
    'public' => true,
    'has_archive' => true,
    'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
    'menu_icon' => 'dashicons-fish',
    'rewrite' => ['slug' => 'fish-species'],
    'show_in_rest' => true,
]);
```

### Care Guides
```php
register_post_type('care-guide', [
    'labels' => [
        'name' => __('Care Guides', 'aqualuxe'),
        'singular_name' => __('Care Guide', 'aqualuxe'),
        // Other labels...
    ],
    'public' => true,
    'has_archive' => true,
    'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
    'menu_icon' => 'dashicons-book',
    'rewrite' => ['slug' => 'care-guides'],
    'show_in_rest' => true,
]);
```

### Team Members
```php
register_post_type('team', [
    'labels' => [
        'name' => __('Team', 'aqualuxe'),
        'singular_name' => __('Team Member', 'aqualuxe'),
        // Other labels...
    ],
    'public' => true,
    'has_archive' => true,
    'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
    'menu_icon' => 'dashicons-groups',
    'rewrite' => ['slug' => 'team'],
    'show_in_rest' => true,
]);
```

### Testimonials
```php
register_post_type('testimonial', [
    'labels' => [
        'name' => __('Testimonials', 'aqualuxe'),
        'singular_name' => __('Testimonial', 'aqualuxe'),
        // Other labels...
    ],
    'public' => true,
    'has_archive' => true,
    'supports' => ['title', 'editor', 'thumbnail', 'custom-fields'],
    'menu_icon' => 'dashicons-format-quote',
    'rewrite' => ['slug' => 'testimonials'],
    'show_in_rest' => true,
]);
```

### FAQs
```php
register_post_type('faq', [
    'labels' => [
        'name' => __('FAQs', 'aqualuxe'),
        'singular_name' => __('FAQ', 'aqualuxe'),
        // Other labels...
    ],
    'public' => true,
    'has_archive' => true,
    'supports' => ['title', 'editor', 'excerpt', 'custom-fields'],
    'menu_icon' => 'dashicons-editor-help',
    'rewrite' => ['slug' => 'faqs'],
    'show_in_rest' => true,
]);
```

## Custom Taxonomies

AquaLuxe includes several custom taxonomies for organizing content. These are defined in `inc/custom-taxonomies.php`:

### Fish Family
```php
register_taxonomy('fish-family', ['fish-species'], [
    'labels' => [
        'name' => __('Fish Families', 'aqualuxe'),
        'singular_name' => __('Fish Family', 'aqualuxe'),
        // Other labels...
    ],
    'hierarchical' => true,
    'show_admin_column' => true,
    'rewrite' => ['slug' => 'fish-family'],
    'show_in_rest' => true,
]);
```

### Fish Origin
```php
register_taxonomy('fish-origin', ['fish-species'], [
    'labels' => [
        'name' => __('Origins', 'aqualuxe'),
        'singular_name' => __('Origin', 'aqualuxe'),
        // Other labels...
    ],
    'hierarchical' => true,
    'show_admin_column' => true,
    'rewrite' => ['slug' => 'fish-origin'],
    'show_in_rest' => true,
]);
```

### Water Type
```php
register_taxonomy('water-type', ['fish-species'], [
    'labels' => [
        'name' => __('Water Types', 'aqualuxe'),
        'singular_name' => __('Water Type', 'aqualuxe'),
        // Other labels...
    ],
    'hierarchical' => true,
    'show_admin_column' => true,
    'rewrite' => ['slug' => 'water-type'],
    'show_in_rest' => true,
]);
```

### Care Level
```php
register_taxonomy('care-level', ['fish-species'], [
    'labels' => [
        'name' => __('Care Levels', 'aqualuxe'),
        'singular_name' => __('Care Level', 'aqualuxe'),
        // Other labels...
    ],
    'hierarchical' => true,
    'show_admin_column' => true,
    'rewrite' => ['slug' => 'care-level'],
    'show_in_rest' => true,
]);
```

### Guide Category
```php
register_taxonomy('guide-category', ['care-guide'], [
    'labels' => [
        'name' => __('Guide Categories', 'aqualuxe'),
        'singular_name' => __('Guide Category', 'aqualuxe'),
        // Other labels...
    ],
    'hierarchical' => true,
    'show_admin_column' => true,
    'rewrite' => ['slug' => 'guide-category'],
    'show_in_rest' => true,
]);
```

### FAQ Category
```php
register_taxonomy('faq-category', ['faq'], [
    'labels' => [
        'name' => __('FAQ Categories', 'aqualuxe'),
        'singular_name' => __('FAQ Category', 'aqualuxe'),
        // Other labels...
    ],
    'hierarchical' => true,
    'show_admin_column' => true,
    'rewrite' => ['slug' => 'faq-category'],
    'show_in_rest' => true,
]);
```

## Theme Features

### Dark Mode

AquaLuxe includes a dark mode toggle that persists user preferences using localStorage:

```javascript
// Dark mode toggle
const darkModeToggle = document.getElementById('dark-mode-toggle');
const htmlElement = document.documentElement;

// Check for saved user preference
const darkMode = localStorage.getItem('aqualuxeDarkMode');

// Set initial state
if (darkMode === 'true') {
    htmlElement.classList.add('dark');
    darkModeToggle.checked = true;
}

// Toggle dark mode
darkModeToggle.addEventListener('change', function() {
    if (this.checked) {
        htmlElement.classList.add('dark');
        localStorage.setItem('aqualuxeDarkMode', 'true');
    } else {
        htmlElement.classList.remove('dark');
        localStorage.setItem('aqualuxeDarkMode', 'false');
    }
});
```

### Multilingual Support

AquaLuxe is compatible with popular translation plugins like WPML and Polylang. The theme includes:

- Translation-ready strings with proper text domains
- RTL support
- Language switcher integration in the header
- Translation management for custom post types and taxonomies

### Wishlist Functionality

The theme includes a custom wishlist feature for WooCommerce products:

```javascript
// Wishlist functionality
document.querySelectorAll('.add-to-wishlist').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        
        const productId = this.dataset.productId;
        
        // Send AJAX request to add/remove from wishlist
        fetch(aqualuxeData.ajaxUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'aqualuxe_update_wishlist',
                product_id: productId,
                nonce: aqualuxeData.nonce,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update button state
                this.classList.toggle('in-wishlist');
                
                // Update wishlist count
                const wishlistCount = document.querySelector('.wishlist-count');
                if (wishlistCount) {
                    wishlistCount.textContent = data.count;
                }
            }
        });
    });
});
```

### Quick View Functionality

The theme includes a quick view feature for WooCommerce products:

```javascript
// Quick view functionality
document.querySelectorAll('.quick-view-button').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        
        const productId = this.dataset.productId;
        
        // Show loading overlay
        document.querySelector('.quick-view-overlay').classList.add('active');
        
        // Fetch product data
        fetch(aqualuxeData.ajaxUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'aqualuxe_quick_view',
                product_id: productId,
                nonce: aqualuxeData.nonce,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Populate quick view modal
                document.querySelector('.quick-view-content').innerHTML = data.html;
                
                // Initialize product gallery
                initProductGallery();
                
                // Show quick view modal
                document.querySelector('.quick-view-modal').classList.add('active');
            }
        })
        .finally(() => {
            // Hide loading overlay
            document.querySelector('.quick-view-overlay').classList.remove('active');
        });
    });
});
```

## WooCommerce Integration

AquaLuxe is fully integrated with WooCommerce and includes custom templates for all WooCommerce pages.

### WooCommerce Setup

```php
/**
 * WooCommerce setup
 */
public function woocommerce_setup() {
    // Add theme support for WooCommerce
    add_theme_support('woocommerce');
    
    // Add support for WooCommerce product gallery features
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Declare WooCommerce support
    add_theme_support('woocommerce', [
        'thumbnail_image_width' => 400,
        'single_image_width'    => 800,
        'product_grid'          => [
            'default_rows'    => 3,
            'min_rows'        => 2,
            'max_rows'        => 8,
            'default_columns' => 4,
            'min_columns'     => 2,
            'max_columns'     => 5,
        ],
    ]);
}
```

### Custom WooCommerce Templates

AquaLuxe includes custom templates for all WooCommerce pages:

- **woocommerce.php**: Main WooCommerce template
- **woocommerce/archive-product.php**: Product archive template
- **woocommerce/content-product.php**: Product content template
- **woocommerce/content-single-product.php**: Single product content template
- **woocommerce/cart/cart.php**: Cart template
- **woocommerce/cart/cart-totals.php**: Cart totals template
- **woocommerce/checkout/form-checkout.php**: Checkout form template
- **woocommerce/checkout/review-order.php**: Order review template
- **woocommerce/checkout/payment.php**: Payment template
- **woocommerce/checkout/payment-method.php**: Payment method template

### Advanced Product Filtering

AquaLuxe includes advanced product filtering using AJAX:

```javascript
// Advanced product filtering
const filterForm = document.querySelector('.product-filter-form');
if (filterForm) {
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'aqualuxe_filter_products');
        formData.append('nonce', aqualuxeData.nonce);
        
        // Show loading overlay
        document.querySelector('.products-loading').classList.add('active');
        
        // Fetch filtered products
        fetch(aqualuxeData.ajaxUrl, {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update products container
                document.querySelector('.products').innerHTML = data.html;
                
                // Update URL with filter parameters
                window.history.pushState({}, '', data.url);
            }
        })
        .finally(() => {
            // Hide loading overlay
            document.querySelector('.products-loading').classList.remove('active');
        });
    });
}
```

## SEO Implementation

AquaLuxe includes comprehensive SEO features:

### Schema.org Markup

The theme implements schema.org structured data for better search engine visibility:

- Organization schema
- Website schema
- WebPage schema
- Article schema for blog posts
- Product schema for WooCommerce products
- Custom schema for Fish Species and Care Guides

```php
/**
 * Output schema markup in head
 */
public function output_schema() {
    // Get all schema data
    $schema = $this->get_schema_data();
    
    // Output schema JSON-LD
    if (!empty($schema)) {
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
    }
}
```

### Open Graph Metadata

The theme includes Open Graph and Twitter Card metadata for better social sharing:

```php
/**
 * Output Open Graph tags
 */
public function output_open_graph_tags() {
    // Get Open Graph data
    $og_tags = $this->get_open_graph_data();
    
    // Output Open Graph tags
    foreach ($og_tags as $property => $content) {
        if (!empty($content)) {
            echo '<meta property="' . esc_attr($property) . '" content="' . esc_attr($content) . '" />' . "\n";
        }
    }
}

/**
 * Output Twitter Card tags
 */
public function output_twitter_card_tags() {
    // Get Twitter Card data
    $twitter_tags = $this->get_twitter_card_data();
    
    // Output Twitter Card tags
    foreach ($twitter_tags as $name => $content) {
        if (!empty($content)) {
            echo '<meta name="' . esc_attr($name) . '" content="' . esc_attr($content) . '" />' . "\n";
        }
    }
}
```

## Performance Optimization

AquaLuxe includes several performance optimization features:

### Image Optimization

- WebP conversion for JPEG and PNG images
- Responsive image sizes
- Image compression
- Lazy loading for images

```php
/**
 * Create WebP version of uploaded image
 * 
 * @param array $upload Upload data
 * @return array Upload data
 */
public function create_webp_version($upload) {
    // Check if WebP conversion is enabled
    if (get_option('aqualuxe_enable_webp', 'yes') !== 'yes') {
        return $upload;
    }
    
    // Check if the uploaded file is an image
    $file = $upload['file'];
    $type = $upload['type'];
    
    if (!in_array($type, ['image/jpeg', 'image/png'])) {
        return $upload;
    }
    
    // Get image resource
    switch ($type) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($file);
            break;
        case 'image/png':
            $image = imagecreatefrompng($file);
            break;
        default:
            return $upload;
    }
    
    if (!$image) {
        return $upload;
    }
    
    // Create WebP version
    $webp_file = preg_replace('/\.(jpe?g|png)$/i', '.webp', $file);
    $quality = get_option('aqualuxe_webp_quality', 80);
    
    // Convert to WebP
    imagewebp($image, $webp_file, $quality);
    imagedestroy($image);
    
    return $upload;
}
```

### Caching Strategies

- Browser caching headers
- Page caching
- Asset optimization
- REST API caching

```php
/**
 * Add browser caching headers
 */
public function add_browser_caching_headers() {
    // Check if browser caching is enabled
    if (get_option('aqualuxe_enable_browser_caching', 'yes') !== 'yes') {
        return;
    }
    
    // Don't add headers for logged-in users
    if (is_user_logged_in()) {
        return;
    }
    
    // Get file type from URL
    $url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $file_extension = pathinfo($url_path, PATHINFO_EXTENSION);
    
    // Set cache time based on file type
    $cache_time = 0;
    
    switch ($file_extension) {
        case 'css':
        case 'js':
        case 'svg':
            $cache_time = WEEK_IN_SECONDS;
            break;
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
        case 'webp':
        case 'ico':
            $cache_time = MONTH_IN_SECONDS;
            break;
        case 'woff':
        case 'woff2':
        case 'ttf':
        case 'eot':
        case 'otf':
            $cache_time = YEAR_IN_SECONDS;
            break;
        default:
            // For HTML and other content
            if (empty($file_extension) || $file_extension === 'html' || $file_extension === 'php') {
                $cache_time = DAY_IN_SECONDS;
            } else {
                $cache_time = WEEK_IN_SECONDS;
            }
            break;
    }
    
    // Set cache headers if cache time is greater than 0
    if ($cache_time > 0) {
        header('Cache-Control: public, max-age=' . $cache_time);
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $cache_time) . ' GMT');
    }
}
```

## Hooks and Filters

AquaLuxe provides several hooks and filters for customization:

### Action Hooks

- `aqualuxe_before_header`: Executes before the header
- `aqualuxe_after_header`: Executes after the header
- `aqualuxe_before_footer`: Executes before the footer
- `aqualuxe_after_footer`: Executes after the footer
- `aqualuxe_before_content`: Executes before the main content
- `aqualuxe_after_content`: Executes after the main content
- `aqualuxe_before_sidebar`: Executes before the sidebar
- `aqualuxe_after_sidebar`: Executes after the sidebar
- `aqualuxe_before_post_content`: Executes before post content
- `aqualuxe_after_post_content`: Executes after post content

### Filter Hooks

- `aqualuxe_schema_organization`: Filters organization schema data
- `aqualuxe_schema_website`: Filters website schema data
- `aqualuxe_schema_webpage`: Filters webpage schema data
- `aqualuxe_schema_article`: Filters article schema data
- `aqualuxe_schema_product`: Filters product schema data
- `aqualuxe_schema_fish_species`: Filters fish species schema data
- `aqualuxe_schema_care_guide`: Filters care guide schema data
- `aqualuxe_open_graph_tags`: Filters Open Graph tags
- `aqualuxe_twitter_card_tags`: Filters Twitter Card tags

## Function Reference

### Template Functions

- `aqualuxe_get_header()`: Gets the header template
- `aqualuxe_get_footer()`: Gets the footer template
- `aqualuxe_get_sidebar()`: Gets the sidebar template
- `aqualuxe_get_template_part($slug, $name = null)`: Gets a template part
- `aqualuxe_get_post_thumbnail($size = 'post-thumbnail')`: Gets the post thumbnail
- `aqualuxe_get_excerpt($length = 55)`: Gets the post excerpt
- `aqualuxe_get_related_posts($post_id, $count = 3)`: Gets related posts
- `aqualuxe_get_breadcrumbs()`: Gets breadcrumbs
- `aqualuxe_get_pagination()`: Gets pagination
- `aqualuxe_get_social_links()`: Gets social links

### Helper Functions

- `aqualuxe_is_dark_mode()`: Checks if dark mode is enabled
- `aqualuxe_get_theme_option($option, $default = '')`: Gets a theme option
- `aqualuxe_get_post_views($post_id)`: Gets post views
- `aqualuxe_set_post_views($post_id)`: Sets post views
- `aqualuxe_get_fish_species_data($post_id)`: Gets fish species data
- `aqualuxe_get_care_guide_data($post_id)`: Gets care guide data
- `aqualuxe_get_team_member_data($post_id)`: Gets team member data
- `aqualuxe_get_testimonial_data($post_id)`: Gets testimonial data
- `aqualuxe_get_faq_data($post_id)`: Gets FAQ data

## Customization

### Theme Customizer

AquaLuxe includes a comprehensive theme customizer with the following sections:

- **General Settings**: Logo, favicon, layout options
- **Colors**: Primary, secondary, accent, dark, light
- **Typography**: Font families, sizes, weights
- **Header**: Header layout, navigation options
- **Footer**: Footer layout, widget areas
- **Blog**: Blog layout, post meta options
- **Shop**: Shop layout, product display options
- **Social Media**: Social media links
- **Advanced**: Custom CSS, JavaScript

### Custom CSS

You can add custom CSS through the theme customizer or by creating a child theme.

### Child Theme

To create a child theme for AquaLuxe:

1. Create a new folder named `aqualuxe-child` in the themes directory
2. Create a `style.css` file with the following content:

```css
/*
Theme Name: AquaLuxe Child
Theme URI: https://example.com/aqualuxe-child/
Description: Child theme for AquaLuxe
Author: Your Name
Author URI: https://example.com/
Template: aqualuxe
Version: 1.0.0
Text Domain: aqualuxe-child
*/

/* Add your custom styles below this line */
```

3. Create a `functions.php` file with the following content:

```php
<?php
/**
 * AquaLuxe Child Theme functions and definitions
 *
 * @package AquaLuxe_Child
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue parent and child theme styles
 */
function aqualuxe_child_enqueue_styles() {
    // Enqueue parent theme style
    wp_enqueue_style('aqualuxe-parent-style', get_template_directory_uri() . '/style.css');
    
    // Enqueue child theme style
    wp_enqueue_style('aqualuxe-child-style', get_stylesheet_uri(), ['aqualuxe-parent-style']);
}
add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles');

/**
 * Add your custom functions below this line
 */
```

## Troubleshooting

### Common Issues

#### 1. WooCommerce Templates Not Working

If WooCommerce templates are not working correctly, make sure WooCommerce is activated and the theme's WooCommerce support is enabled:

```php
add_theme_support('woocommerce');
```

#### 2. Custom Post Types Not Showing

If custom post types are not showing, check if they are registered correctly and have the correct capabilities:

```php
register_post_type('fish-species', [
    'public' => true,
    'has_archive' => true,
    // Other arguments...
]);
```

#### 3. Theme Options Not Saving

If theme options are not saving, check if the customizer settings are registered correctly:

```php
$wp_customize->add_setting('aqualuxe_primary_color', [
    'default' => '#0077B6',
    'sanitize_callback' => 'sanitize_hex_color',
]);
```

#### 4. Performance Issues

If the theme is experiencing performance issues, try the following:

- Enable caching
- Optimize images
- Minimize JavaScript and CSS
- Use a CDN
- Enable Gzip compression

### Support

For additional support, please contact:

- **Email**: support@aqualuxe-theme.com
- **Website**: https://aqualuxe-theme.com/support
- **Documentation**: https://aqualuxe-theme.com/docs