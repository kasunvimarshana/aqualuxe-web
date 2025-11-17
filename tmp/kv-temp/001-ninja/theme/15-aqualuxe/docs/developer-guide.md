# AquaLuxe WordPress Theme - Developer Guide

## Table of Contents
1. [Introduction](#introduction)
2. [Theme Structure](#theme-structure)
3. [Theme Features](#theme-features)
4. [Customization](#customization)
5. [WooCommerce Integration](#woocommerce-integration)
6. [Custom Post Types](#custom-post-types)
7. [Template Files](#template-files)
8. [Hooks and Filters](#hooks-and-filters)
9. [Assets Management](#assets-management)
10. [Performance Optimization](#performance-optimization)
11. [Multilingual Support](#multilingual-support)
12. [Dark Mode](#dark-mode)
13. [Mobile Responsiveness](#mobile-responsiveness)
14. [Schema.org and SEO](#schema-and-seo)
15. [Extending the Theme](#extending-the-theme)

## Introduction <a name="introduction"></a>

AquaLuxe is a premium WordPress theme designed specifically for aquarium-related businesses with WooCommerce integration. This developer guide provides comprehensive documentation on the theme's architecture, features, and customization options.

### Version Information
- Theme Name: AquaLuxe
- Version: 1.0.0
- Author: NinjaTech AI
- License: GPL v2 or later

## Theme Structure <a name="theme-structure"></a>

AquaLuxe follows an object-oriented approach with a modular file structure:

```
aqualuxe/
├── assets/
│   ├── css/
│   │   ├── admin.css
│   │   ├── blog.css
│   │   ├── cart-checkout.css
│   │   ├── critical.css
│   │   ├── editor-style.css
│   │   ├── main.css
│   │   ├── mobile.css
│   │   ├── print.css
│   │   ├── product.css
│   │   └── woocommerce.css
│   ├── js/
│   │   ├── admin.js
│   │   ├── blog.js
│   │   ├── cart-checkout.js
│   │   ├── dark-mode.js
│   │   ├── editor.js
│   │   ├── lazy-load.js
│   │   ├── main.js
│   │   ├── mobile-nav.js
│   │   ├── product.js
│   │   ├── vendors.js
│   │   └── woocommerce.js
│   ├── fonts/
│   └── images/
├── docs/
│   ├── developer-guide.md
│   └── user-guide.md
├── inc/
│   ├── classes/
│   │   ├── class-aqualuxe-assets.php
│   │   ├── class-aqualuxe-breadcrumb.php
│   │   ├── class-aqualuxe-customizer.php
│   │   ├── class-aqualuxe-schema.php
│   │   ├── class-aqualuxe-social-meta.php
│   │   ├── class-aqualuxe-walker-nav-menu.php
│   │   ├── class-aqualuxe-woocommerce.php
│   │   └── post-types/
│   │       ├── class-aqualuxe-event.php
│   │       ├── class-aqualuxe-service.php
│   │       └── class-aqualuxe-testimonial.php
│   ├── helpers/
│   │   ├── template-functions.php
│   │   └── woocommerce-functions.php
│   ├── customizer.php
│   ├── template-functions.php
│   ├── template-tags.php
│   └── woocommerce.php
├── languages/
├── templates/
│   ├── components/
│   │   ├── content-none.php
│   │   ├── content-page.php
│   │   ├── content-related.php
│   │   ├── content-search.php
│   │   ├── content-single.php
│   │   └── content.php
│   └── page-templates/
│       ├── template-about.php
│       ├── template-blog.php
│       ├── template-contact.php
│       ├── template-faq.php
│       └── template-services.php
├── woocommerce/
│   ├── archive-product.php
│   ├── cart/
│   ├── checkout/
│   ├── content-product.php
│   ├── content-single-product.php
│   ├── global/
│   ├── loop/
│   ├── myaccount/
│   └── single-product/
├── 404.php
├── archive.php
├── comments.php
├── footer.php
├── front-page.php
├── functions.php
├── header.php
├── index.php
├── page.php
├── screenshot.png
├── search.php
├── sidebar.php
├── single.php
└── style.css
```

## Theme Features <a name="theme-features"></a>

AquaLuxe includes the following features:

- **Responsive Design**: Mobile-first approach with responsive breakpoints
- **Dark Mode**: Toggle between light and dark modes with local storage persistence
- **WooCommerce Integration**: Custom styling and enhanced functionality for WooCommerce
- **Custom Post Types**: Services, Events, and Testimonials
- **Page Templates**: About, Services, Contact, Blog, and FAQ templates
- **Performance Optimization**: Asset loading optimization, lazy loading, and critical CSS
- **Schema.org Markup**: Structured data for better SEO
- **Open Graph & Twitter Cards**: Enhanced social media sharing
- **Multilingual Support**: Compatible with WPML and Polylang
- **Customizer Options**: Extensive theme customization options
- **Modular Component System**: Reusable template parts

## Customization <a name="customization"></a>

### Theme Customizer

AquaLuxe provides extensive customization options through the WordPress Customizer. The `AquaLuxe_Customizer` class in `inc/classes/class-aqualuxe-customizer.php` handles all customizer settings.

Key customization sections include:

- **Site Identity**: Logo, site title, tagline
- **Colors**: Primary, secondary, accent, text, and background colors
- **Typography**: Font families, sizes, and weights
- **Layout**: Container width, sidebar position, header layout
- **Header**: Header style, navigation options, top bar settings
- **Footer**: Widget areas, footer menu, copyright text
- **Blog**: Post layout, meta information, featured images
- **WooCommerce**: Shop layout, product display, cart/checkout settings
- **Performance**: Asset loading, lazy loading, cache busting
- **Dark Mode**: Default mode, toggle position
- **Social Media**: Social profile links

### Adding Custom Customizer Settings

To add custom customizer settings, use the `aqualuxe_customize_register` filter:

```php
function my_custom_customizer_settings($wp_customize) {
    // Add your custom settings here
    $wp_customize->add_setting('my_custom_setting', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('my_custom_setting', array(
        'label'    => __('My Custom Setting', 'aqualuxe'),
        'section'  => 'title_tagline',
        'type'     => 'text',
        'priority' => 30,
    ));
}
add_action('customize_register', 'my_custom_customizer_settings');
```

## WooCommerce Integration <a name="woocommerce-integration"></a>

AquaLuxe includes comprehensive WooCommerce integration with custom templates and styling. The `AquaLuxe_WooCommerce` class in `inc/classes/class-aqualuxe-woocommerce.php` handles all WooCommerce customizations.

### Custom WooCommerce Templates

AquaLuxe includes the following custom WooCommerce templates:

- `archive-product.php`: Shop page with grid/list view toggle
- `content-product.php`: Product card with customizable layout
- `content-single-product.php`: Single product page with enhanced layout
- `cart.php`: Enhanced cart page with cross-sells
- `checkout.php`: Multi-step checkout process

### WooCommerce Hooks

AquaLuxe uses WooCommerce hooks to customize the shop experience. Key hooks include:

```php
// Remove default WooCommerce styles
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// Customize product loop columns
add_filter('loop_shop_columns', 'aqualuxe_loop_shop_columns');

// Customize related products
add_filter('woocommerce_output_related_products_args', 'aqualuxe_related_products_args');

// Add custom product tabs
add_filter('woocommerce_product_tabs', 'aqualuxe_product_tabs');
```

## Custom Post Types <a name="custom-post-types"></a>

AquaLuxe includes three custom post types:

1. **Services**: For aquarium services offered
2. **Events**: For aquarium events and exhibitions
3. **Testimonials**: For customer testimonials

Each custom post type is implemented in a separate class in the `inc/classes/post-types/` directory.

### Adding Custom Fields

AquaLuxe supports custom fields for post types. You can add custom fields using Advanced Custom Fields (ACF) or the built-in custom fields system.

Example of adding custom fields to the Services post type:

```php
function aqualuxe_service_meta_boxes() {
    add_meta_box(
        'aqualuxe_service_details',
        __('Service Details', 'aqualuxe'),
        'aqualuxe_service_details_callback',
        'aqualuxe_service',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_service_meta_boxes');

function aqualuxe_service_details_callback($post) {
    wp_nonce_field('aqualuxe_service_details', 'aqualuxe_service_details_nonce');
    
    $price = get_post_meta($post->ID, '_aqualuxe_service_price', true);
    $duration = get_post_meta($post->ID, '_aqualuxe_service_duration', true);
    
    echo '<p>';
    echo '<label for="aqualuxe_service_price">' . __('Price', 'aqualuxe') . '</label> ';
    echo '<input type="text" id="aqualuxe_service_price" name="aqualuxe_service_price" value="' . esc_attr($price) . '" size="25" />';
    echo '</p>';
    
    echo '<p>';
    echo '<label for="aqualuxe_service_duration">' . __('Duration', 'aqualuxe') . '</label> ';
    echo '<input type="text" id="aqualuxe_service_duration" name="aqualuxe_service_duration" value="' . esc_attr($duration) . '" size="25" />';
    echo '</p>';
}

function aqualuxe_save_service_details($post_id) {
    if (!isset($_POST['aqualuxe_service_details_nonce'])) {
        return;
    }
    
    if (!wp_verify_nonce($_POST['aqualuxe_service_details_nonce'], 'aqualuxe_service_details')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (isset($_POST['aqualuxe_service_price'])) {
        update_post_meta($post_id, '_aqualuxe_service_price', sanitize_text_field($_POST['aqualuxe_service_price']));
    }
    
    if (isset($_POST['aqualuxe_service_duration'])) {
        update_post_meta($post_id, '_aqualuxe_service_duration', sanitize_text_field($_POST['aqualuxe_service_duration']));
    }
}
add_action('save_post', 'aqualuxe_save_service_details');
```

## Template Files <a name="template-files"></a>

AquaLuxe includes the following template files:

### Core Template Files
- `index.php`: Main template file
- `header.php`: Site header
- `footer.php`: Site footer
- `sidebar.php`: Sidebar with widget areas
- `single.php`: Single post template
- `page.php`: Page template
- `archive.php`: Archive template
- `search.php`: Search results template
- `404.php`: 404 error page
- `comments.php`: Comments template

### Page Templates
- `front-page.php`: Homepage template
- `template-about.php`: About page template
- `template-services.php`: Services page template
- `template-contact.php`: Contact page template
- `template-blog.php`: Blog page template
- `template-faq.php`: FAQ page template

### Component Templates
- `content.php`: Standard post content
- `content-single.php`: Single post content
- `content-page.php`: Page content
- `content-search.php`: Search results content
- `content-none.php`: No results content
- `content-related.php`: Related posts content

## Hooks and Filters <a name="hooks-and-filters"></a>

AquaLuxe provides a variety of hooks and filters for theme customization:

### Action Hooks

- `aqualuxe_before_header`: Before header content
- `aqualuxe_after_header`: After header content
- `aqualuxe_before_content`: Before main content
- `aqualuxe_after_content`: After main content
- `aqualuxe_before_footer`: Before footer content
- `aqualuxe_after_footer`: After footer content
- `aqualuxe_before_post`: Before post content
- `aqualuxe_after_post`: After post content
- `aqualuxe_before_comments`: Before comments
- `aqualuxe_after_comments`: After comments

### Filter Hooks

- `aqualuxe_body_classes`: Modify body classes
- `aqualuxe_post_classes`: Modify post classes
- `aqualuxe_excerpt_length`: Modify excerpt length
- `aqualuxe_excerpt_more`: Modify excerpt more text
- `aqualuxe_comment_form_args`: Modify comment form arguments
- `aqualuxe_related_posts_args`: Modify related posts arguments
- `aqualuxe_social_meta_title`: Modify social meta title
- `aqualuxe_social_meta_description`: Modify social meta description
- `aqualuxe_social_meta_image`: Modify social meta image

## Assets Management <a name="assets-management"></a>

AquaLuxe uses a sophisticated asset management system implemented in the `AquaLuxe_Assets` class. The class handles:

- Registration and enqueuing of styles and scripts
- Conditional loading based on page type
- Script attributes (defer, async)
- Resource preloading
- Cache busting
- Dark mode support
- Lazy loading

### Adding Custom Assets

To add custom assets, use the WordPress enqueue functions:

```php
function my_custom_assets() {
    wp_enqueue_style(
        'my-custom-style',
        get_template_directory_uri() . '/assets/css/my-custom-style.css',
        array(),
        '1.0.0'
    );
    
    wp_enqueue_script(
        'my-custom-script',
        get_template_directory_uri() . '/assets/js/my-custom-script.js',
        array('jquery'),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'my_custom_assets');
```

## Performance Optimization <a name="performance-optimization"></a>

AquaLuxe includes several performance optimization features:

### Critical CSS
Critical CSS is loaded inline in the head to prevent layout shifts and improve Core Web Vitals.

### Conditional Asset Loading
Assets are loaded conditionally based on page type to reduce unnecessary requests.

### Lazy Loading
Images and iframes are lazy loaded using the Intersection Observer API.

### Script Optimization
Scripts are loaded with defer or async attributes to improve page load performance.

### Resource Hints
Preconnect and preload hints are used to improve resource loading.

### Cache Busting
Asset versions are based on file modification time for efficient cache busting.

## Multilingual Support <a name="multilingual-support"></a>

AquaLuxe is compatible with popular translation plugins:

### WPML
AquaLuxe is fully compatible with WPML. All theme strings are translatable using WPML's String Translation module.

### Polylang
AquaLuxe works seamlessly with Polylang. All theme strings can be translated using Polylang's string translation feature.

### Translation Ready
AquaLuxe is translation-ready with all strings properly escaped and internationalized:

```php
// Internationalization example
__('This string can be translated', 'aqualuxe');
_e('This string can be translated and echoed', 'aqualuxe');
```

## Dark Mode <a name="dark-mode"></a>

AquaLuxe includes a dark mode feature with local storage persistence:

### CSS Variables
Dark mode is implemented using CSS variables with separate values for light and dark modes:

```css
:root {
  --text-color: #333333;
  --background-color: #ffffff;
}

.dark {
  --text-color: #e9ecef;
  --background-color: #121212;
}
```

### JavaScript Toggle
The dark mode toggle is implemented in `assets/js/dark-mode.js`. It handles:

- User preference detection
- Local storage persistence
- System preference detection
- Toggle button state

### Customizer Options
Dark mode can be customized through the WordPress Customizer:

- Default mode (light or dark)
- Toggle position
- Custom colors for dark mode

## Mobile Responsiveness <a name="mobile-responsiveness"></a>

AquaLuxe follows a mobile-first approach with responsive breakpoints:

### Breakpoints
- Small: 576px
- Medium: 768px
- Large: 992px
- Extra Large: 1200px

### Mobile Navigation
Mobile navigation is implemented in `assets/js/mobile-nav.js`. It handles:

- Menu toggle
- Dropdown toggles
- Touch support
- Accessibility

### Responsive Images
Images are responsive with appropriate srcset and sizes attributes:

```php
the_post_thumbnail('large', array(
    'class' => 'img-fluid',
    'srcset' => wp_get_attachment_image_srcset(get_post_thumbnail_id(), 'large'),
    'sizes' => wp_get_attachment_image_sizes(get_post_thumbnail_id(), 'large'),
));
```

## Schema.org and SEO <a name="schema-and-seo"></a>

AquaLuxe includes comprehensive Schema.org markup and SEO features:

### Schema.org Markup
Schema.org markup is implemented in the `AquaLuxe_Schema` class. It adds structured data for:

- Organization
- Website
- Article
- Product
- BreadcrumbList

### Open Graph and Twitter Cards
Social media metadata is implemented in the `AquaLuxe_Social_Meta` class. It adds:

- Open Graph meta tags
- Twitter Card meta tags
- Basic meta tags (description, canonical)

### SEO Best Practices
AquaLuxe follows SEO best practices:

- Semantic HTML
- Proper heading hierarchy
- Accessible navigation
- Responsive design
- Fast loading times

## Extending the Theme <a name="extending-the-theme"></a>

AquaLuxe can be extended in several ways:

### Child Theme
Create a child theme to customize AquaLuxe without modifying the parent theme:

```php
/*
 Theme Name:   AquaLuxe Child
 Theme URI:    https://example.com/aqualuxe-child/
 Description:  AquaLuxe Child Theme
 Author:       Your Name
 Author URI:   https://example.com
 Template:     aqualuxe
 Version:      1.0.0
 License:      GNU General Public License v2 or later
 License URI:  http://www.gnu.org/licenses/gpl-2.0.html
 Text Domain:  aqualuxe-child
*/

function aqualuxe_child_enqueue_styles() {
    wp_enqueue_style('aqualuxe-parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('aqualuxe-child-style', get_stylesheet_uri(), array('aqualuxe-parent-style'));
}
add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles');
```

### Hooks and Filters
Use AquaLuxe's hooks and filters to customize the theme without modifying core files.

### Custom Templates
Create custom page templates in your child theme to override parent theme templates.

### Custom Post Types
Extend AquaLuxe with additional custom post types for specific needs.

### Custom Blocks
Create custom Gutenberg blocks to enhance the editing experience.

---

This developer guide provides a comprehensive overview of the AquaLuxe WordPress theme. For more detailed information on specific features, please refer to the inline documentation in the theme files.