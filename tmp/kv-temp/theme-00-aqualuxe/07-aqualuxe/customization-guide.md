# AquaLuxe Theme Customization Guide

## Overview
This document provides a comprehensive guide to customizing the AquaLuxe WooCommerce child theme. It covers all available customization options, methods for extending functionality, and best practices for maintaining theme updates.

## Customization Methods

### 1. WordPress Customizer
The easiest way to customize the theme without coding:
- Access via **Appearance > Customize** in WordPress admin
- Real-time preview of changes
- No technical knowledge required
- Changes saved automatically

### 2. Child Theme Customization
For advanced customizations that won't be lost during theme updates:
- Create a child theme of AquaLuxe
- Add custom CSS, JavaScript, and PHP
- Override template files
- Extend theme functionality with hooks

### 3. Plugin Integration
Extend theme functionality with compatible plugins:
- WooCommerce extensions
- SEO plugins
- Performance optimization plugins
- Social media plugins

## 1. WordPress Customizer Options

### 1.1 Site Identity
Access via **Appearance > Customize > Site Identity**

#### Available Options:
- **Site Title**: Your website's main title
- **Tagline**: Short description of your site
- **Logo**: Upload custom logo (recommended size: 400x100px)
- **Retina Logo**: High-resolution logo for retina displays
- **Site Icon**: Favicon for your site (recommended size: 512x512px)
- **Display Site Title and Tagline**: Toggle visibility of text elements

#### Best Practices:
- Use high-quality, compressed images
- Maintain consistent branding colors
- Ensure logo is readable at small sizes
- Test site icon on various devices

### 1.2 Colors
Access via **Appearance > Customize > Colors**

#### Color Schemes:
- **Ocean Blue**: Professional blue-based scheme
- **Aquamarine**: Teal and green-based scheme
- **Coral Purple**: Warm purple-based scheme

#### Individual Color Controls:
- **Primary Color**: Main accent color for buttons and links
- **Secondary Color**: Supporting accent color
- **Header Background Color**: Background color for site header
- **Footer Background Color**: Background color for site footer
- **Button Background Color**: Background color for buttons
- **Button Text Color**: Text color for buttons

#### Customization Tips:
- Use color contrast checking tools for accessibility
- Maintain consistent color palette
- Test colors on different backgrounds
- Consider seasonal or promotional color changes

### 1.3 Header Options
Access via **Appearance > Customize > Header Options**

#### Layout Options:
- **Header Layout**: Choose between Standard, Sticky, or Minimal layouts
- **Header Background**: Set header background color or image
- **Header Padding**: Adjust top and bottom padding
- **Sticky Header**: Enable/disable sticky header functionality

#### Navigation Options:
- **Primary Navigation Style**: Choose navigation style
- **Mobile Navigation Style**: Choose mobile navigation style
- **Search Bar Position**: Position search bar in header
- **Header Cart Icon**: Toggle cart icon visibility

#### Design Recommendations:
- Use sticky header for better user experience
- Ensure navigation is intuitive and organized
- Place search bar in easily accessible location
- Test mobile navigation on various devices

### 1.4 AquaLuxe Theme Options
Access via **Appearance > Customize > AquaLuxe Options**

#### Design Options:
- **Color Scheme**: Select from predefined color schemes
- **Typography**: Choose font combinations
- **Layout Width**: Set maximum layout width
- **Content Background Color**: Set background color for content area

#### Feature Toggles:
- **Enable Quick View**: Toggle product quick view functionality
- **Enable AJAX Add to Cart**: Toggle AJAX add to cart on shop pages
- **Display Breadcrumbs**: Toggle breadcrumb navigation
- **Product Hover Effect**: Choose hover effect for product images

#### Customization Workflow:
1. Start with default settings
2. Make incremental changes
3. Preview changes in real-time
4. Test on different devices
5. Save when satisfied with results

## 2. Advanced Customization with Child Themes

### 2.1 Creating a Child Theme

#### Step 1: Create Child Theme Directory
Create a new directory in `wp-content/themes/` named `aqualuxe-child`

#### Step 2: Create style.css
```css
/*
Theme Name: AquaLuxe Child
Theme URI: https://yourwebsite.com
Description: Child theme for AquaLuxe
Author: Your Name
Author URI: https://yourwebsite.com
Template: aqualuxe
Version: 1.0.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: aqualuxe-child
*/
```

#### Step 3: Create functions.php
```php
<?php
/**
 * AquaLuxe Child Theme Functions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue parent and child theme styles
function aqualuxe_child_enqueue_styles() {
    wp_enqueue_style('aqualuxe-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('aqualuxe-child-style', get_stylesheet_directory_uri() . '/style.css', array('aqualuxe-style'));
}
add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles');

// Add custom functionality here
```

### 2.2 Custom CSS Customization

#### Adding Custom Styles
```css
/* Custom button styles */
.button--custom {
    background-color: #ff6b6b;
    color: #ffffff;
    border: 2px solid #ff6b6b;
    border-radius: 30px;
    padding: 12px 24px;
    text-transform: uppercase;
    font-weight: 600;
    transition: all 0.3s ease;
}

.button--custom:hover {
    background-color: #ffffff;
    color: #ff6b6b;
}

/* Custom product card styles */
.product-card--featured {
    border: 3px solid #ffd166;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

/* Custom typography */
body {
    font-family: 'Inter', sans-serif;
}

h1, h2, h3, h4, h5, h6 {
    font-family: 'Playfair Display', serif;
}
```

### 2.3 Template Customization

#### Overriding Template Files
Copy template files from `wp-content/themes/aqualuxe/` to `wp-content/themes/aqualuxe-child/` and modify:

```php
<!-- Example: Custom header.php in child theme -->
<header class="site-header custom-header">
    <div class="custom-header-content">
        <div class="announcement-bar">
            <p>Free shipping on orders over $50!</p>
        </div>
        <div class="header-main">
            <?php aqualuxe_site_branding(); ?>
            <?php aqualuxe_primary_navigation(); ?>
        </div>
    </div>
</header>
```

### 2.4 Functionality Extensions

#### Adding Custom Functions
```php
// Add custom post type
function aqualuxe_register_custom_post_types() {
    register_post_type('testimonial', array(
        'labels' => array(
            'name' => 'Testimonials',
            'singular_name' => 'Testimonial'
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail')
    ));
}
add_action('init', 'aqualuxe_register_custom_post_types');

// Add custom shortcode
function aqualuxe_custom_testimonial_shortcode($atts) {
    $atts = shortcode_atts(array(
        'count' => 3
    ), $atts);
    
    $testimonials = get_posts(array(
        'post_type' => 'testimonial',
        'numberposts' => $atts['count']
    ));
    
    $output = '<div class="testimonials-slider">';
    foreach ($testimonials as $testimonial) {
        $output .= '<div class="testimonial">';
        $output .= '<div class="testimonial-content">' . $testimonial->post_content . '</div>';
        $output .= '<div class="testimonial-author">' . $testimonial->post_title . '</div>';
        $output .= '</div>';
    }
    $output .= '</div>';
    
    return $output;
}
add_shortcode('testimonials', 'aqualuxe_custom_testimonial_shortcode');
```

## 3. WooCommerce Customization

### 3.1 Product Display Customization

#### Custom Product Archive
```php
// Modify product archive columns
function aqualuxe_custom_product_columns($columns) {
    return 4; // 4 columns instead of default
}
add_filter('loop_shop_columns', 'aqualuxe_custom_product_columns');

// Modify products per page
function aqualuxe_custom_products_per_page($per_page) {
    return 16; // 16 products per page
}
add_filter('loop_shop_per_page', 'aqualuxe_custom_products_per_page');
```

#### Custom Single Product Layout
```php
// Add custom tabs to single product
function aqualuxe_custom_product_tabs($tabs) {
    $tabs['care_instructions'] = array(
        'title'    => __('Care Instructions', 'aqualuxe'),
        'priority' => 50,
        'callback' => 'aqualuxe_custom_care_instructions_tab_content'
    );
    
    return $tabs;
}
add_filter('woocommerce_product_tabs', 'aqualuxe_custom_product_tabs');

function aqualuxe_custom_care_instructions_tab_content() {
    global $product;
    echo '<h2>' . __('Care Instructions', 'aqualuxe') . '</h2>';
    echo '<p>Detailed care instructions for ' . $product->get_name() . '...</p>';
}
```

### 3.2 Cart and Checkout Customization

#### Custom Cart Modifications
```php
// Add custom message to cart
function aqualuxe_custom_cart_message() {
    if (WC()->cart->get_cart_contents_count() > 0) {
        echo '<div class="cart-message">';
        echo __('Thank you for supporting our business!', 'aqualuxe');
        echo '</div>';
    }
}
add_action('woocommerce_before_cart_table', 'aqualuxe_custom_cart_message');

// Modify checkout fields
function aqualuxe_custom_checkout_fields($fields) {
    $fields['billing']['billing_phone']['placeholder'] = 'e.g. (123) 456-7890';
    $fields['billing']['billing_phone']['required'] = true;
    
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'aqualuxe_custom_checkout_fields');
```

## 4. Performance Customization

### 4.1 Asset Optimization

#### Custom CSS and JavaScript
```php
// Dequeue unnecessary scripts
function aqualuxe_dequeue_scripts() {
    if (!is_admin() && !is_customize_preview()) {
        // Dequeue scripts on non-admin pages
        wp_dequeue_script('jquery-ui-accordion');
        wp_dequeue_script('jquery-ui-autocomplete');
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_dequeue_scripts', 100);

// Add custom async scripts
function aqualuxe_enqueue_custom_async_scripts() {
    wp_enqueue_script(
        'custom-async-script',
        get_stylesheet_directory_uri() . '/assets/js/custom-async.js',
        array(),
        '1.0.0',
        array(
            'strategy'  => 'async',
            'in_footer' => true
        )
    );
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_custom_async_scripts');
```

### 4.2 Caching Customization

#### Custom Transients
```php
// Cache expensive operations
function aqualuxe_get_cached_expensive_data($product_id) {
    $cache_key = 'aqualuxe_expensive_data_' . $product_id;
    $cached_data = get_transient($cache_key);
    
    if ($cached_data !== false) {
        return $cached_data;
    }
    
    // Expensive operation
    $expensive_data = aqualuxe_perform_expensive_operation($product_id);
    
    // Cache for 1 hour
    set_transient($cache_key, $expensive_data, HOUR_IN_SECONDS);
    
    return $expensive_data;
}

// Clear cache when product is updated
function aqualuxe_clear_expensive_data_cache($product_id) {
    delete_transient('aqualuxe_expensive_data_' . $product_id);
}
add_action('woocommerce_update_product', 'aqualuxe_clear_expensive_data_cache');
```

## 5. Accessibility Customization

### 5.1 Custom Accessibility Features

#### Skip Links Enhancement
```php
// Add additional skip links
function aqualuxe_custom_skip_links() {
    echo '<a class="skip-link screen-reader-text" href="#main-navigation">' . 
         __('Skip to navigation', 'aqualuxe') . '</a>';
    echo '<a class="skip-link screen-reader-text" href="#search-form">' . 
         __('Skip to search', 'aqualuxe') . '</a>';
}
add_action('wp_body_open', 'aqualuxe_custom_skip_links');

// Custom focus styles
function aqualuxe_custom_focus_styles() {
    echo '<style>
        :focus {
            outline: 3px solid #ff6b6b !important;
            outline-offset: 2px;
        }
        
        .button:focus,
        .input:focus,
        .select:focus {
            outline: 3px solid #ff6b6b !important;
            outline-offset: 2px;
        }
    </style>';
}
add_action('wp_head', 'aqualuxe_custom_focus_styles');
```

### 5.2 ARIA Attribute Customization

#### Custom ARIA Labels
```php
// Add custom ARIA attributes to navigation
function aqualuxe_custom_navigation_aria($args) {
    $args['menu_class'] .= ' custom-navigation';
    return $args;
}
add_filter('wp_nav_menu_args', 'aqualuxe_custom_navigation_aria');

// Add ARIA labels to form elements
function aqualuxe_custom_form_aria_labels() {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            // Add ARIA labels to form elements
            var inputs = document.querySelectorAll("input, textarea, select");
            inputs.forEach(function(input) {
                if (input.id && !input.getAttribute("aria-label")) {
                    var label = document.querySelector("label[for=\"" + input.id + "\"]");
                    if (label) {
                        input.setAttribute("aria-label", label.textContent);
                    }
                }
            });
        });
    </script>';
}
add_action('wp_footer', 'aqualuxe_custom_form_aria_labels');
```

## 6. SEO Customization

### 6.1 Custom Meta Tags

#### Additional Meta Tags
```php
// Add custom meta tags
function aqualuxe_custom_meta_tags() {
    if (is_product()) {
        global $product;
        echo '<meta name="product-category" content="' . esc_attr($product->get_category_ids()[0]) . '">' . "\n";
        echo '<meta name="product-brand" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_custom_meta_tags');

// Custom canonical URLs
function aqualuxe_custom_canonical_url() {
    if (is_product_category()) {
        $category = get_queried_object();
        if ($category) {
            echo '<link rel="canonical" href="' . esc_url(get_term_link($category)) . '">' . "\n";
        }
    }
}
add_action('wp_head', 'aqualuxe_custom_canonical_url');
```

### 6.2 Schema Markup Extensions

#### Custom Schema Markup
```php
// Add custom schema markup
function aqualuxe_custom_schema_markup() {
    if (is_product()) {
        global $product;
        
        $custom_schema = array(
            '@context' => 'https://schema.org/',
            '@type' => 'LocalBusiness',
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'address' => array(
                '@type' => 'PostalAddress',
                'streetAddress' => get_option('woocommerce_store_address'),
                'addressLocality' => get_option('woocommerce_store_city'),
                'addressRegion' => get_option('woocommerce_store_state'),
                'postalCode' => get_option('woocommerce_store_postcode'),
                'addressCountry' => get_option('woocommerce_default_country')
            ),
            'telephone' => get_option('woocommerce_store_phone'),
            'email' => get_option('woocommerce_store_email')
        );
        
        echo '<script type="application/ld+json">' . json_encode($custom_schema) . '</script>';
    }
}
add_action('wp_head', 'aqualuxe_custom_schema_markup');
```

## 7. Custom Widget Areas

### 7.1 Registering Custom Widget Areas

#### Additional Widget Areas
```php
// Register custom widget areas
function aqualuxe_register_custom_widget_areas() {
    register_sidebar(array(
        'name'          => __('Above Header', 'aqualuxe'),
        'id'            => 'above-header',
        'description'   => __('Add widgets here to appear above the header.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'   => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    
    register_sidebar(array(
        'name'          => __('Below Footer', 'aqualuxe'),
        'id'            => 'below-footer',
        'description'   => __('Add widgets here to appear below the footer.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'   => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'aqualuxe_register_custom_widget_areas');

// Display custom widget areas
function aqualuxe_display_above_header_widgets() {
    if (is_active_sidebar('above-header')) {
        echo '<div class="above-header-widgets">';
        dynamic_sidebar('above-header');
        echo '</div>';
    }
}
add_action('aqualuxe_before_header', 'aqualuxe_display_above_header_widgets');
```

## 8. Custom Hooks and Filters

### 8.1 Using Theme Hooks

#### Action Hooks
```php
// Using theme action hooks
add_action('aqualuxe_before_header', function() {
    echo '<div class="announcement-bar">';
    echo '<p>Free shipping on orders over $50!</p>';
    echo '</div>';
});

add_action('aqualuxe_after_footer', function() {
    echo '<div class="back-to-top">';
    echo '<a href="#top">Back to Top</a>';
    echo '</div>';
});
```

#### Filter Hooks
```php
// Using theme filter hooks
add_filter('aqualuxe_site_title', function($title) {
    return '<span class="custom-title">' . $title . '</span>';
});

add_filter('aqualuxe_add_to_cart_text', function($text, $product) {
    if ($product->is_type('variable')) {
        return __('Select Options', 'aqualuxe');
    }
    return __('Add to Cart', 'aqualuxe');
}, 10, 2);
```

### 8.2 Creating Custom Hooks

#### Custom Action Hooks
```php
// Creating custom action hooks
function aqualuxe_custom_action_hook() {
    do_action('aqualuxe_custom_action');
}

// Add to template file
// aqualuxe_custom_action_hook();

// Using the custom hook
add_action('aqualuxe_custom_action', function() {
    echo '<div class="custom-content">Custom content added via hook</div>';
});
```

#### Custom Filter Hooks
```php
// Creating custom filter hooks
function aqualuxe_custom_filter_hook($value) {
    return apply_filters('aqualuxe_custom_filter', $value);
}

// Add to template file
// $custom_value = aqualuxe_custom_filter_hook('default_value');

// Using the custom filter
add_filter('aqualuxe_custom_filter', function($value) {
    return 'modified_' . $value;
});
```

## 9. Customization Best Practices

### 9.1 Performance Considerations

#### Efficient Customization
```php
// Load scripts conditionally
function aqualuxe_conditional_script_loading() {
    if (is_page_template('template-full-width.php')) {
        wp_enqueue_script('full-width-script', get_stylesheet_directory_uri() . '/assets/js/full-width.js', array('jquery'), '1.0.0', true);
    }
    
    if (is_product()) {
        wp_enqueue_script('product-customizations', get_stylesheet_directory_uri() . '/assets/js/product-custom.js', array('jquery'), '1.0.0', true);
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_conditional_script_loading');

// Minimize database queries
function aqualuxe_optimize_database_queries() {
    // Use transients for expensive queries
    $cache_key = 'aqualuxe_expensive_query';
    $result = get_transient($cache_key);
    
    if ($result === false) {
        global $wpdb;
        $result = $wpdb->get_results("SELECT * FROM expensive_query");
        set_transient($cache_key, $result, HOUR_IN_SECONDS);
    }
    
    return $result;
}
```

### 9.2 Security Considerations

#### Secure Customizations
```php
// Validate and sanitize custom inputs
function aqualuxe_validate_custom_input($input) {
    // Sanitize text input
    $sanitized = sanitize_text_field($input);
    
    // Validate against allowed values
    $allowed_values = array('option1', 'option2', 'option3');
    if (!in_array($sanitized, $allowed_values)) {
        $sanitized = 'option1'; // Default fallback
    }
    
    return $sanitized;
}

// Nonce verification for custom forms
function aqualuxe_custom_form_nonce() {
    wp_nonce_field('aqualuxe_custom_form_action', 'aqualuxe_custom_nonce');
}

function aqualuxe_verify_custom_form_nonce() {
    if (!isset($_POST['aqualuxe_custom_nonce']) || 
        !wp_verify_nonce($_POST['aqualuxe_custom_nonce'], 'aqualuxe_custom_form_action')) {
        wp_die('Security check failed');
    }
}
```

### 9.3 Maintenance Considerations

#### Update-Safe Customizations
```php
// Check for theme updates
function aqualuxe_check_theme_version() {
    $theme = wp_get_theme('aqualuxe');
    $current_version = $theme->get('Version');
    
    // Store current version in options
    $stored_version = get_option('aqualuxe_theme_version', '1.0.0');
    
    if (version_compare($current_version, $stored_version, '>')) {
        // Theme has been updated
        aqualuxe_handle_theme_update($current_version, $stored_version);
        update_option('aqualuxe_theme_version', $current_version);
    }
}
add_action('init', 'aqualuxe_check_theme_version');

function aqualuxe_handle_theme_update($new_version, $old_version) {
    // Handle any necessary updates
    error_log("AquaLuxe theme updated from {$old_version} to {$new_version}");
    
    // Clear any custom transients
    delete_transient('aqualuxe_custom_cache');
}
```

## 10. Troubleshooting Customizations

### 10.1 Common Issues and Solutions

#### CSS Conflicts
```css
/* Use higher specificity to override theme styles */
.aqualuxe-child .custom-button {
    background-color: #ff6b6b !important;
}

/* Or use custom CSS classes */
.custom-styled-button {
    background-color: #ff6b6b;
    border: none;
    padding: 12px 24px;
}
```

#### JavaScript Conflicts
```javascript
// Use jQuery noConflict mode
jQuery(document).ready(function($) {
    // Custom JavaScript here
    $('.custom-element').on('click', function() {
        // Handle click event
    });
});

// Or use vanilla JavaScript
document.addEventListener('DOMContentLoaded', function() {
    var customElements = document.querySelectorAll('.custom-element');
    customElements.forEach(function(element) {
        element.addEventListener('click', function() {
            // Handle click event
        });
    });
});
```

#### Template Override Issues
```php
// Check if template exists in child theme
function aqualuxe_check_template_override($template_name) {
    $child_template = get_stylesheet_directory() . '/' . $template_name;
    $parent_template = get_template_directory() . '/' . $template_name;
    
    if (file_exists($child_template)) {
        return $child_template;
    } elseif (file_exists($parent_template)) {
        return $parent_template;
    }
    
    return false;
}
```

## Conclusion

The AquaLuxe theme provides extensive customization options through various methods, from simple WordPress Customizer adjustments to advanced child theme development. By following the guidelines and best practices outlined in this guide, you can create a unique, high-performing website that meets your specific needs while maintaining compatibility with future theme updates.

Key customization approaches include:
1. **WordPress Customizer**: Easy, no-code customization options
2. **Child Themes**: Advanced customization without losing updates
3. **WooCommerce Extensions**: E-commerce specific customizations
4. **Performance Optimization**: Speed and efficiency enhancements
5. **Accessibility Features**: Inclusive design considerations
6. **SEO Enhancements**: Search engine optimization improvements

Regular testing and maintenance of customizations will ensure your website continues to perform optimally and provide an excellent user experience.