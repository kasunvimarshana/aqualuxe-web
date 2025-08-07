# AquaLuxe Theme Functions Reference

## Overview
This document provides a comprehensive reference for all custom functions available in the AquaLuxe WooCommerce child theme. These functions allow developers to extend and customize the theme functionality without modifying core theme files.

## Function Categories
1. Template Functions
2. WooCommerce Functions
3. Customizer Functions
4. Utility Functions
5. Helper Functions
6. AJAX Functions
7. Demo Content Functions

## 1. Template Functions

### aqualuxe_get_template_part( $slug, $name = null )
**Description**: Get template part with fallback to parent theme
**Parameters**:
- $slug (string): Template slug
- $name (string): Template name
**Return**: void
**Usage**:
```php
aqualuxe_get_template_part( 'content', 'page' );
```

### aqualuxe_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' )
**Description**: Load a template file
**Parameters**:
- $template_name (string): Template name
- $args (array): Arguments to pass to template
- $template_path (string): Template path
- $default_path (string): Default path
**Return**: void
**Usage**:
```php
aqualuxe_get_template( 'product-quick-view.php', array( 'product' => $product ) );
```

### aqualuxe_locate_template( $template_name, $template_path = '', $default_path = '' )
**Description**: Locate a template file
**Parameters**:
- $template_name (string): Template name
- $template_path (string): Template path
- $default_path (string): Default path
**Return**: string
**Usage**:
```php
$template = aqualuxe_locate_template( 'custom-template.php' );
```

### aqualuxe_get_sidebar( $name = null )
**Description**: Get sidebar with fallback to parent theme
**Parameters**:
- $name (string): Sidebar name
**Return**: void
**Usage**:
```php
aqualuxe_get_sidebar( 'shop' );
```

## 2. WooCommerce Functions

### aqualuxe_quick_view_button( $product_id )
**Description**: Display quick view button
**Parameters**:
- $product_id (int): Product ID
**Return**: void
**Usage**:
```php
aqualuxe_quick_view_button( get_the_ID() );
```

### aqualuxe_product_quick_view( $product_id )
**Description**: Display product quick view content
**Parameters**:
- $product_id (int): Product ID
**Return**: void
**Usage**:
```php
aqualuxe_product_quick_view( $product->get_id() );
```

### aqualuxe_ajax_add_to_cart( $product_id, $quantity = 1 )
**Description**: Add product to cart via AJAX
**Parameters**:
- $product_id (int): Product ID
- $quantity (int): Quantity to add
**Return**: array
**Usage**:
```php
$result = aqualuxe_ajax_add_to_cart( $product_id, 2 );
```

### aqualuxe_get_product_gallery_images( $product_id )
**Description**: Get product gallery images
**Parameters**:
- $product_id (int): Product ID
**Return**: array
**Usage**:
```php
$images = aqualuxe_get_product_gallery_images( $product->get_id() );
```

### aqualuxe_product_schema( $product = null )
**Description**: Generate product schema markup
**Parameters**:
- $product (WC_Product): Product object
**Return**: string
**Usage**:
```php
$schema = aqualuxe_product_schema( $product );
echo $schema;
```

### aqualuxe_breadcrumb( $args = array() )
**Description**: Display breadcrumb navigation
**Parameters**:
- $args (array): Breadcrumb arguments
**Return**: void
**Usage**:
```php
aqualuxe_breadcrumb( array(
    'delimiter' => ' > ',
    'wrap_before' => '<nav class="breadcrumb">',
    'wrap_after' => '</nav>'
) );
```

## 3. Customizer Functions

### aqualuxe_get_customizer_option( $option_name, $default = '' )
**Description**: Get customizer option value
**Parameters**:
- $option_name (string): Option name
- $default (mixed): Default value
**Return**: mixed
**Usage**:
```php
$color_scheme = aqualuxe_get_customizer_option( 'color_scheme', 'blue' );
```

### aqualuxe_set_customizer_option( $option_name, $value )
**Description**: Set customizer option value
**Parameters**:
- $option_name (string): Option name
- $value (mixed): Option value
**Return**: bool
**Usage**:
```php
aqualuxe_set_customizer_option( 'color_scheme', 'green' );
```

### aqualuxe_get_color_scheme( $scheme = null )
**Description**: Get color scheme values
**Parameters**:
- $scheme (string): Color scheme name
**Return**: array
**Usage**:
```php
$colors = aqualuxe_get_color_scheme( 'ocean' );
```

### aqualuxe_get_typography_options( $option = null )
**Description**: Get typography options
**Parameters**:
- $option (string): Specific typography option
**Return**: mixed
**Usage**:
```php
$font_family = aqualuxe_get_typography_options( 'font_family' );
```

## 4. Utility Functions

### aqualuxe_get_asset_uri( $path )
**Description**: Get asset URI with version parameter
**Parameters**:
- $path (string): Asset path
**Return**: string
**Usage**:
```php
$css_uri = aqualuxe_get_asset_uri( 'assets/css/aqualuxe-styles.css' );
```

### aqualuxe_get_theme_version()
**Description**: Get theme version
**Parameters**: None
**Return**: string
**Usage**:
```php
$version = aqualuxe_get_theme_version();
```

### aqualuxe_get_theme_directory()
**Description**: Get theme directory path
**Parameters**: None
**Return**: string
**Usage**:
```php
$theme_dir = aqualuxe_get_theme_directory();
```

### aqualuxe_get_theme_uri()
**Description**: Get theme URI
**Parameters**: None
**Return**: string
**Usage**:
```php
$theme_uri = aqualuxe_get_theme_uri();
```

### aqualuxe_is_woocommerce_active()
**Description**: Check if WooCommerce is active
**Parameters**: None
**Return**: bool
**Usage**:
```php
if ( aqualuxe_is_woocommerce_active() ) {
    // WooCommerce specific code
}
```

### aqualuxe_is_dev_mode()
**Description**: Check if development mode is enabled
**Parameters**: None
**Return**: bool
**Usage**:
```php
if ( aqualuxe_is_dev_mode() ) {
    // Development specific code
}
```

## 5. Helper Functions

### aqualuxe_get_excerpt( $post_id = null, $length = 55 )
**Description**: Get post excerpt with custom length
**Parameters**:
- $post_id (int): Post ID
- $length (int): Excerpt length
**Return**: string
**Usage**:
```php
$excerpt = aqualuxe_get_excerpt( get_the_ID(), 30 );
```

### aqualuxe_get_thumbnail_url( $post_id = null, $size = 'thumbnail' )
**Description**: Get post thumbnail URL
**Parameters**:
- $post_id (int): Post ID
- $size (string): Image size
**Return**: string
**Usage**:
```php
$thumbnail = aqualuxe_get_thumbnail_url( get_the_ID(), 'medium' );
```

### aqualuxe_get_social_icons( $platforms = array() )
**Description**: Get social media icons
**Parameters**:
- $platforms (array): Social platforms
**Return**: string
**Usage**:
```php
$icons = aqualuxe_get_social_icons( array( 'facebook', 'twitter', 'instagram' ) );
echo $icons;
```

### aqualuxe_format_price( $price )
**Description**: Format price with currency
**Parameters**:
- $price (float): Price value
**Return**: string
**Usage**:
```php
$formatted_price = aqualuxe_format_price( 29.99 );
```

### aqualuxe_sanitize_hex_color( $color )
**Description**: Sanitize hex color value
**Parameters**:
- $color (string): Hex color value
**Return**: string
**Usage**:
```php
$sanitized_color = aqualuxe_sanitize_hex_color( '#ff0000' );
```

## 6. AJAX Functions

### aqualuxe_ajax_quick_view()
**Description**: Handle quick view AJAX request
**Parameters**: None
**Return**: void
**Usage**:
```php
add_action( 'wp_ajax_aqualuxe_quick_view', 'aqualuxe_ajax_quick_view' );
```

### aqualuxe_ajax_add_to_cart_handler()
**Description**: Handle add to cart AJAX request
**Parameters**: None
**Return**: void
**Usage**:
```php
add_action( 'wp_ajax_aqualuxe_add_to_cart', 'aqualuxe_ajax_add_to_cart_handler' );
```

### aqualuxe_ajax_update_cart()
**Description**: Handle cart update AJAX request
**Parameters**: None
**Return**: void
**Usage**:
```php
add_action( 'wp_ajax_aqualuxe_update_cart', 'aqualuxe_ajax_update_cart' );
```

### aqualuxe_ajax_apply_coupon()
**Description**: Handle coupon apply AJAX request
**Parameters**: None
**Return**: void
**Usage**:
```php
add_action( 'wp_ajax_aqualuxe_apply_coupon', 'aqualuxe_ajax_apply_coupon' );
```

## 7. Demo Content Functions

### aqualuxe_import_demo_products()
**Description**: Import demo products
**Parameters**: None
**Return**: bool
**Usage**:
```php
$success = aqualuxe_import_demo_products();
```

### aqualuxe_import_demo_pages()
**Description**: Import demo pages
**Parameters**: None
**Return**: bool
**Usage**:
```php
$success = aqualuxe_import_demo_pages();
```

### aqualuxe_import_demo_widgets()
**Description**: Import demo widgets
**Parameters**: None
**Return**: bool
**Usage**:
```php
$success = aqualuxe_import_demo_widgets();
```

### aqualuxe_import_demo_menus()
**Description**: Import demo menus
**Parameters**: None
**Return**: bool
**Usage**:
```php
$success = aqualuxe_import_demo_menus();
```

### aqualuxe_import_demo_settings()
**Description**: Import demo settings
**Parameters**: None
**Return**: bool
**Usage**:
```php
$success = aqualuxe_import_demo_settings();
```

## 8. Widget Functions

### aqualuxe_register_widget_areas()
**Description**: Register custom widget areas
**Parameters**: None
**Return**: void
**Usage**:
```php
add_action( 'widgets_init', 'aqualuxe_register_widget_areas' );
```

### aqualuxe_get_widget_area( $area_name )
**Description**: Get widget area content
**Parameters**:
- $area_name (string): Widget area name
**Return**: string
**Usage**:
```php
$widget_content = aqualuxe_get_widget_area( 'sidebar-shop' );
echo $widget_content;
```

## 9. Navigation Functions

### aqualuxe_primary_navigation()
**Description**: Display primary navigation
**Parameters**: None
**Return**: void
**Usage**:
```php
aqualuxe_primary_navigation();
```

### aqualuxe_mobile_navigation()
**Description**: Display mobile navigation
**Parameters**: None
**Return**: void
**Usage**:
```php
aqualuxe_mobile_navigation();
```

### aqualuxe_footer_navigation()
**Description**: Display footer navigation
**Parameters**: None
**Return**: void
**Usage**:
```php
aqualuxe_footer_navigation();
```

### aqualuxe_get_menu( $menu_name, $args = array() )
**Description**: Get menu by name
**Parameters**:
- $menu_name (string): Menu name
- $args (array): Menu arguments
**Return**: string
**Usage**:
```php
$menu = aqualuxe_get_menu( 'primary', array( 'menu_class' => 'main-menu' ) );
echo $menu;
```

## 10. SEO Functions

### aqualuxe_meta_description()
**Description**: Generate meta description
**Parameters**: None
**Return**: string
**Usage**:
```php
$meta_desc = aqualuxe_meta_description();
echo '<meta name="description" content="' . esc_attr( $meta_desc ) . '">';
```

### aqualuxe_open_graph_tags()
**Description**: Generate Open Graph meta tags
**Parameters**: None
**Return**: void
**Usage**:
```php
aqualuxe_open_graph_tags();
```

### aqualuxe_twitter_cards()
**Description**: Generate Twitter Card meta tags
**Parameters**: None
**Return**: void
**Usage**:
```php
aqualuxe_twitter_cards();
```

### aqualuxe_canonical_url()
**Description**: Generate canonical URL
**Parameters**: None
**Return**: string
**Usage**:
```php
$canonical = aqualuxe_canonical_url();
echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">';
```

## 11. Performance Functions

### aqualuxe_lazy_load_image( $image_url, $alt = '', $classes = '' )
**Description**: Generate lazy loaded image HTML
**Parameters**:
- $image_url (string): Image URL
- $alt (string): Alt text
- $classes (string): CSS classes
**Return**: string
**Usage**:
```php
$lazy_image = aqualuxe_lazy_load_image( $image_url, 'Product Image', 'lazy-img' );
echo $lazy_image;
```

### aqualuxe_enqueue_async_script( $handle, $src, $deps = array() )
**Description**: Enqueue script with async attribute
**Parameters**:
- $handle (string): Script handle
- $src (string): Script source
- $deps (array): Dependencies
**Return**: void
**Usage**:
```php
aqualuxe_enqueue_async_script( 'custom-script', get_stylesheet_directory_uri() . '/assets/js/custom.js' );
```

### aqualuxe_enqueue_defer_script( $handle, $src, $deps = array() )
**Description**: Enqueue script with defer attribute
**Parameters**:
- $handle (string): Script handle
- $src (string): Script source
- $deps (array): Dependencies
**Return**: void
**Usage**:
```php
aqualuxe_enqueue_defer_script( 'custom-script', get_stylesheet_directory_uri() . '/assets/js/custom.js' );
```

## 12. Accessibility Functions

### aqualuxe_skip_link( $target = '#content' )
**Description**: Generate skip link for accessibility
**Parameters**:
- $target (string): Target element
**Return**: string
**Usage**:
```php
$skip_link = aqualuxe_skip_link( '#main-content' );
echo $skip_link;
```

### aqualuxe_get_aria_label( $context )
**Description**: Get ARIA label for context
**Parameters**:
- $context (string): Context name
**Return**: string
**Usage**:
```php
$aria_label = aqualuxe_get_aria_label( 'navigation' );
echo 'aria-label="' . esc_attr( $aria_label ) . '"';
```

### aqualuxe_get_focusable_elements( $container )
**Description**: Get focusable elements in container
**Parameters**:
- $container (string): Container selector
**Return**: array
**Usage**:
```php
$elements = aqualuxe_get_focusable_elements( '.modal' );
```

## 13. Internationalization Functions

### aqualuxe_get_text( $key, $context = 'general' )
**Description**: Get translated text by key
**Parameters**:
- $key (string): Text key
- $context (string): Text context
**Return**: string
**Usage**:
```php
$text = aqualuxe_get_text( 'quick_view', 'product' );
echo $text;
```

### aqualuxe_get_plural_text( $singular, $plural, $count, $context = 'general' )
**Description**: Get translated plural text
**Parameters**:
- $singular (string): Singular text
- $plural (string): Plural text
- $count (int): Count
- $context (string): Text context
**Return**: string
**Usage**:
```php
$text = aqualuxe_get_plural_text( 'item', 'items', 5, 'cart' );
echo $text;
```

## 14. Custom Functions

### aqualuxe_custom_function_example( $param1, $param2 = 'default' )
**Description**: Example custom function
**Parameters**:
- $param1 (mixed): First parameter
- $param2 (string): Second parameter with default value
**Return**: mixed
**Usage**:
```php
$result = aqualuxe_custom_function_example( 'value1', 'value2' );
```

## Function Best Practices

### 1. Naming Conventions
All functions should be prefixed with `aqualuxe_`:
```php
function aqualuxe_get_custom_data() {
    // Function implementation
}
```

### 2. Documentation
All functions should be properly documented:
```php
/**
 * Get custom data for theme
 *
 * @param string $type Type of data to retrieve
 * @param array $args Additional arguments
 * @return mixed Data or false on failure
 */
function aqualuxe_get_custom_data( $type, $args = array() ) {
    // Function implementation
}
```

### 3. Parameter Validation
Always validate function parameters:
```php
function aqualuxe_process_data( $data ) {
    if ( ! is_array( $data ) ) {
        return false;
    }
    
    // Process data
    return $processed_data;
}
```

### 4. Return Values
Functions should return appropriate values:
```php
function aqualuxe_get_status() {
    // Return boolean for status
    return true; // or false
}

function aqualuxe_get_data() {
    // Return array of data
    return array( 'key' => 'value' );
}

function aqualuxe_get_content() {
    // Return string content
    return 'Content here';
}
```

### 5. Error Handling
Implement proper error handling:
```php
function aqualuxe_safe_function( $param ) {
    // Check if parameter is valid
    if ( empty( $param ) ) {
        return new WP_Error( 'invalid_param', 'Parameter is required' );
    }
    
    // Process and return result
    return $result;
}
```

## Conclusion

The AquaLuxe theme provides a comprehensive set of functions that allow for extensive customization without modifying core theme files. These functions follow WordPress standards and provide developers with flexible ways to extend theme functionality.

When using these functions:
1. Always check if a function exists before using it
2. Follow the documented parameters and return values
3. Implement proper error handling
4. Document your custom implementations
5. Test thoroughly across different scenarios

This function reference will be updated as new functions are added to the theme, ensuring developers always have access to the latest customization options.