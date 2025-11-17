# AquaLuxe Theme Hooks Reference

## Overview
This document provides a comprehensive reference for all custom hooks available in the AquaLuxe WooCommerce child theme. These hooks allow developers to extend and customize the theme functionality without modifying core theme files.

## Hook Types
- **Actions**: Execute code at specific points
- **Filters**: Modify data before output

## 1. Header Hooks

### Actions

#### aqualuxe_before_header
**Description**: Executed before the header element
**Location**: header.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_before_header', 'my_custom_function' );
function my_custom_function() {
    echo '<div class="custom-header-content">My Content</div>';
}
```

#### aqualuxe_header
**Description**: Executed within the header element
**Location**: header.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_header', 'my_header_function' );
function my_header_function() {
    echo '<div class="custom-header-element">Custom Element</div>';
}
```

#### aqualuxe_after_header
**Description**: Executed after the header element
**Location**: header.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_after_header', 'my_after_header_function' );
function my_after_header_function() {
    echo '<div class="after-header-content">After Header</div>';
}
```

#### aqualuxe_before_site_branding
**Description**: Executed before site branding
**Location**: template-parts/header/site-branding.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_before_site_branding', 'my_branding_function' );
function my_branding_function() {
    echo '<div class="before-branding">Before Branding</div>';
}
```

#### aqualuxe_after_site_branding
**Description**: Executed after site branding
**Location**: template-parts/header/site-branding.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_after_site_branding', 'my_after_branding_function' );
function my_after_branding_function() {
    echo '<div class="after-branding">After Branding</div>';
}
```

#### aqualuxe_before_navigation
**Description**: Executed before navigation
**Location**: template-parts/header/site-navigation.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_before_navigation', 'my_navigation_function' );
function my_navigation_function() {
    echo '<div class="before-navigation">Before Navigation</div>';
}
```

#### aqualuxe_after_navigation
**Description**: Executed after navigation
**Location**: template-parts/header/site-navigation.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_after_navigation', 'my_after_navigation_function' );
function my_after_navigation_function() {
    echo '<div class="after-navigation">After Navigation</div>';
}
```

### Filters

#### aqualuxe_site_title
**Description**: Modify the site title
**Location**: template-parts/header/site-branding.php
**Parameters**: $title (string)
**Usage**:
```php
add_filter( 'aqualuxe_site_title', 'modify_site_title' );
function modify_site_title( $title ) {
    return 'Custom: ' . $title;
}
```

#### aqualuxe_site_description
**Description**: Modify the site description
**Location**: template-parts/header/site-branding.php
**Parameters**: $description (string)
**Usage**:
```php
add_filter( 'aqualuxe_site_description', 'modify_site_description' );
function modify_site_description( $description ) {
    return 'Premium ' . $description;
}
```

## 2. Content Hooks

### Actions

#### aqualuxe_before_content
**Description**: Executed before the main content area
**Location**: Various template files
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_before_content', 'my_before_content_function' );
function my_before_content_function() {
    echo '<div class="before-content">Before Content</div>';
}
```

#### aqualuxe_content_top
**Description**: Executed at the top of the content area
**Location**: Various template files
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_content_top', 'my_content_top_function' );
function my_content_top_function() {
    echo '<div class="content-top">Content Top</div>';
}
```

#### aqualuxe_content_bottom
**Description**: Executed at the bottom of the content area
**Location**: Various template files
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_content_bottom', 'my_content_bottom_function' );
function my_content_bottom_function() {
    echo '<div class="content-bottom">Content Bottom</div>';
}
```

#### aqualuxe_after_content
**Description**: Executed after the main content area
**Location**: Various template files
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_after_content', 'my_after_content_function' );
function my_after_content_function() {
    echo '<div class="after-content">After Content</div>';
}
```

#### aqualuxe_before_page_content
**Description**: Executed before page content
**Location**: template-parts/content/content-page.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_before_page_content', 'my_before_page_content_function' );
function my_before_page_content_function() {
    echo '<div class="before-page-content">Before Page Content</div>';
}
```

#### aqualuxe_after_page_content
**Description**: Executed after page content
**Location**: template-parts/content/content-page.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_after_page_content', 'my_after_page_content_function' );
function my_after_page_content_function() {
    echo '<div class="after-page-content">After Page Content</div>';
}
```

#### aqualuxe_before_post_content
**Description**: Executed before post content
**Location**: template-parts/content/content-single.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_before_post_content', 'my_before_post_content_function' );
function my_before_post_content_function() {
    echo '<div class="before-post-content">Before Post Content</div>';
}
```

#### aqualuxe_after_post_content
**Description**: Executed after post content
**Location**: template-parts/content/content-single.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_after_post_content', 'my_after_post_content_function' );
function my_after_post_content_function() {
    echo '<div class="after-post-content">After Post Content</div>';
}
```

### Filters

#### aqualuxe_page_title
**Description**: Modify the page title
**Location**: template-parts/content/content-page.php
**Parameters**: $title (string)
**Usage**:
```php
add_filter( 'aqualuxe_page_title', 'modify_page_title' );
function modify_page_title( $title ) {
    return 'Custom: ' . $title;
}
```

#### aqualuxe_post_title
**Description**: Modify the post title
**Location**: template-parts/content/content-single.php
**Parameters**: $title (string)
**Usage**:
```php
add_filter( 'aqualuxe_post_title', 'modify_post_title' );
function modify_post_title( $title ) {
    return 'Custom: ' . $title;
}
```

## 3. Footer Hooks

### Actions

#### aqualuxe_before_footer
**Description**: Executed before the footer element
**Location**: footer.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_before_footer', 'my_before_footer_function' );
function my_before_footer_function() {
    echo '<div class="before-footer">Before Footer</div>';
}
```

#### aqualuxe_footer
**Description**: Executed within the footer element
**Location**: footer.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_footer', 'my_footer_function' );
function my_footer_function() {
    echo '<div class="custom-footer-element">Custom Footer Element</div>';
}
```

#### aqualuxe_after_footer
**Description**: Executed after the footer element
**Location**: footer.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_after_footer', 'my_after_footer_function' );
function my_after_footer_function() {
    echo '<div class="after-footer">After Footer</div>';
}
```

#### aqualuxe_before_footer_widgets
**Description**: Executed before footer widgets
**Location**: template-parts/footer/footer-widgets.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_before_footer_widgets', 'my_before_footer_widgets_function' );
function my_before_footer_widgets_function() {
    echo '<div class="before-footer-widgets">Before Footer Widgets</div>';
}
```

#### aqualuxe_after_footer_widgets
**Description**: Executed after footer widgets
**Location**: template-parts/footer/footer-widgets.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_after_footer_widgets', 'my_after_footer_widgets_function' );
function my_after_footer_widgets_function() {
    echo '<div class="after-footer-widgets">After Footer Widgets</div>';
}
```

#### aqualuxe_before_site_info
**Description**: Executed before site information
**Location**: template-parts/footer/site-info.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_before_site_info', 'my_before_site_info_function' );
function my_before_site_info_function() {
    echo '<div class="before-site-info">Before Site Info</div>';
}
```

#### aqualuxe_after_site_info
**Description**: Executed after site information
**Location**: template-parts/footer/site-info.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_after_site_info', 'my_after_site_info_function' );
function my_after_site_info_function() {
    echo '<div class="after-site-info">After Site Info</div>';
}
```

### Filters

#### aqualuxe_copyright_text
**Description**: Modify the copyright text
**Location**: template-parts/footer/site-info.php
**Parameters**: $text (string)
**Usage**:
```php
add_filter( 'aqualuxe_copyright_text', 'modify_copyright_text' );
function modify_copyright_text( $text ) {
    return $text . ' - All Rights Reserved';
}
```

## 4. WooCommerce Hooks

### Actions

#### aqualuxe_before_shop_loop
**Description**: Executed before the shop loop
**Location**: woocommerce/archive-product.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_before_shop_loop', 'my_before_shop_loop_function' );
function my_before_shop_loop_function() {
    echo '<div class="before-shop-loop">Before Shop Loop</div>';
}
```

#### aqualuxe_after_shop_loop
**Description**: Executed after the shop loop
**Location**: woocommerce/archive-product.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_after_shop_loop', 'my_after_shop_loop_function' );
function my_after_shop_loop_function() {
    echo '<div class="after-shop-loop">After Shop Loop</div>';
}
```

#### aqualuxe_before_single_product
**Description**: Executed before single product
**Location**: woocommerce/single-product.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_before_single_product', 'my_before_single_product_function' );
function my_before_single_product_function() {
    echo '<div class="before-single-product">Before Single Product</div>';
}
```

#### aqualuxe_after_single_product
**Description**: Executed after single product
**Location**: woocommerce/single-product.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_after_single_product', 'my_after_single_product_function' );
function my_after_single_product_function() {
    echo '<div class="after-single-product">After Single Product</div>';
}
```

#### aqualuxe_product_quick_view
**Description**: Executed in product quick view modal
**Location**: JavaScript/AJAX handler
**Parameters**: $product_id (int)
**Usage**:
```php
add_action( 'aqualuxe_product_quick_view', 'my_quick_view_function' );
function my_quick_view_function( $product_id ) {
    echo '<div class="quick-view-custom-content">Custom Quick View Content</div>';
}
```

#### aqualuxe_before_cart_table
**Description**: Executed before cart table
**Location**: woocommerce/cart/cart.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_before_cart_table', 'my_before_cart_table_function' );
function my_before_cart_table_function() {
    echo '<div class="before-cart-table">Before Cart Table</div>';
}
```

#### aqualuxe_after_cart_table
**Description**: Executed after cart table
**Location**: woocommerce/cart/cart.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_after_cart_table', 'my_after_cart_table_function' );
function my_after_cart_table_function() {
    echo '<div class="after-cart-table">After Cart Table</div>';
}
```

### Filters

#### aqualuxe_product_archive_columns
**Description**: Modify the number of product columns in archive
**Location**: woocommerce/archive-product.php
**Parameters**: $columns (int)
**Usage**:
```php
add_filter( 'aqualuxe_product_archive_columns', 'modify_product_columns' );
function modify_product_columns( $columns ) {
    return 4; // 4 columns instead of default
}
```

#### aqualuxe_product_archive_per_page
**Description**: Modify the number of products per page
**Location**: woocommerce/archive-product.php
**Parameters**: $per_page (int)
**Usage**:
```php
add_filter( 'aqualuxe_product_archive_per_page', 'modify_products_per_page' );
function modify_products_per_page( $per_page ) {
    return 16; // 16 products per page instead of default
}
```

#### aqualuxe_quick_view_button_text
**Description**: Modify the quick view button text
**Location**: template-functions.php
**Parameters**: $text (string)
**Usage**:
```php
add_filter( 'aqualuxe_quick_view_button_text', 'modify_quick_view_text' );
function modify_quick_view_text( $text ) {
    return 'Quick Preview';
}
```

#### aqualuxe_add_to_cart_text
**Description**: Modify the add to cart button text
**Location**: woocommerce/single-product/add-to-cart.php
**Parameters**: $text (string), $product (WC_Product)
**Usage**:
```php
add_filter( 'aqualuxe_add_to_cart_text', 'modify_add_to_cart_text', 10, 2 );
function modify_add_to_cart_text( $text, $product ) {
    if ( $product->is_type( 'variable' ) ) {
        return 'Select Options';
    }
    return 'Add to Tank';
}
```

## 5. Customizer Hooks

### Actions

#### aqualuxe_customizer_options
**Description**: Add customizer options
**Location**: inc/customizer.php
**Parameters**: $wp_customize (WP_Customize_Manager)
**Usage**:
```php
add_action( 'aqualuxe_customizer_options', 'my_customizer_options' );
function my_customizer_options( $wp_customize ) {
    $wp_customize->add_section( 'my_custom_section', array(
        'title' => 'My Custom Section',
        'priority' => 30,
    ) );
}
```

#### aqualuxe_customizer_controls
**Description**: Add customizer controls
**Location**: inc/customizer.php
**Parameters**: $wp_customize (WP_Customize_Manager)
**Usage**:
```php
add_action( 'aqualuxe_customizer_controls', 'my_customizer_controls' );
function my_customizer_controls( $wp_customize ) {
    $wp_customize->add_setting( 'my_custom_setting', array(
        'default' => 'default_value',
    ) );
    
    $wp_customize->add_control( 'my_custom_control', array(
        'label' => 'My Custom Control',
        'section' => 'my_custom_section',
        'type' => 'text',
    ) );
}
```

### Filters

#### aqualuxe_customizer_color_schemes
**Description**: Modify available color schemes
**Location**: inc/customizer.php
**Parameters**: $schemes (array)
**Usage**:
```php
add_filter( 'aqualuxe_customizer_color_schemes', 'modify_color_schemes' );
function modify_color_schemes( $schemes ) {
    $schemes['ocean'] = 'Ocean Blue';
    return $schemes;
}
```

#### aqualuxe_customizer_header_layouts
**Description**: Modify available header layouts
**Location**: inc/customizer.php
**Parameters**: $layouts (array)
**Usage**:
```php
add_filter( 'aqualuxe_customizer_header_layouts', 'modify_header_layouts' );
function modify_header_layouts( $layouts ) {
    $layouts['minimal'] = 'Minimal Header';
    return $layouts;
}
```

## 6. JavaScript Hooks

### Actions

#### aqualuxe_enqueue_scripts
**Description**: Enqueue additional scripts
**Location**: functions.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_enqueue_scripts', 'my_enqueue_scripts' );
function my_enqueue_scripts() {
    wp_enqueue_script( 'my-custom-script', get_stylesheet_directory_uri() . '/assets/js/my-script.js', array( 'jquery' ), '1.0.0', true );
}
```

#### aqualuxe_localize_script
**Description**: Add localized script data
**Location**: functions.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_localize_script', 'my_localize_script' );
function my_localize_script() {
    wp_localize_script( 'aqualuxe-scripts', 'my_custom_vars', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'my_custom_nonce' ),
    ) );
}
```

### Filters

#### aqualuxe_ajax_url
**Description**: Modify AJAX URL
**Location**: functions.php
**Parameters**: $url (string)
**Usage**:
```php
add_filter( 'aqualuxe_ajax_url', 'modify_ajax_url' );
function modify_ajax_url( $url ) {
    return admin_url( 'admin-ajax.php' );
}
```

## 7. Demo Content Hooks

### Actions

#### aqualuxe_import_demo_content
**Description**: Import demo content
**Location**: inc/class-aqualuxe-demo-importer.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_import_demo_content', 'my_import_demo_content' );
function my_import_demo_content() {
    // Custom demo content import logic
}
```

#### aqualuxe_after_demo_import
**Description**: Executed after demo content import
**Location**: inc/class-aqualuxe-demo-importer.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_after_demo_import', 'my_after_demo_import' );
function my_after_demo_import() {
    // Custom logic after demo import
}
```

### Filters

#### aqualuxe_demo_products
**Description**: Modify demo products data
**Location**: inc/class-aqualuxe-demo-importer.php
**Parameters**: $products (array)
**Usage**:
```php
add_filter( 'aqualuxe_demo_products', 'modify_demo_products' );
function modify_demo_products( $products ) {
    $products[] = array(
        'name' => 'Custom Demo Product',
        'price' => '29.99',
    );
    return $products;
}
```

## 8. Security Hooks

### Actions

#### aqualuxe_security_check
**Description**: Perform security checks
**Location**: Various files
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_security_check', 'my_security_check' );
function my_security_check() {
    // Custom security check logic
}
```

### Filters

#### aqualuxe_nonce_action
**Description**: Modify nonce action
**Location**: Various files
**Parameters**: $action (string)
**Usage**:
```php
add_filter( 'aqualuxe_nonce_action', 'modify_nonce_action' );
function modify_nonce_action( $action ) {
    return 'my_custom_nonce_action';
}
```

## 9. Performance Hooks

### Actions

#### aqualuxe_optimize_assets
**Description**: Optimize theme assets
**Location**: functions.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_optimize_assets', 'my_asset_optimization' );
function my_asset_optimization() {
    // Custom asset optimization logic
}
```

### Filters

#### aqualuxe_lazy_load_attributes
**Description**: Modify lazy load attributes
**Location**: template-functions.php
**Parameters**: $attributes (array)
**Usage**:
```php
add_filter( 'aqualuxe_lazy_load_attributes', 'modify_lazy_load_attributes' );
function modify_lazy_load_attributes( $attributes ) {
    $attributes['data-custom'] = 'custom-value';
    return $attributes;
}
```

## 10. Accessibility Hooks

### Actions

#### aqualuxe_accessibility_setup
**Description**: Set up accessibility features
**Location**: functions.php
**Parameters**: None
**Usage**:
```php
add_action( 'aqualuxe_accessibility_setup', 'my_accessibility_setup' );
function my_accessibility_setup() {
    // Custom accessibility setup
}
```

### Filters

#### aqualuxe_aria_attributes
**Description**: Modify ARIA attributes
**Location**: Various template files
**Parameters**: $attributes (array), $context (string)
**Usage**:
```php
add_filter( 'aqualuxe_aria_attributes', 'modify_aria_attributes', 10, 2 );
function modify_aria_attributes( $attributes, $context ) {
    if ( $context === 'navigation' ) {
        $attributes['aria-label'] = 'Main Navigation';
    }
    return $attributes;
}
```

## Best Practices for Using Hooks

### 1. Priority Settings
When adding actions or filters, consider the execution order:
```php
// Execute early
add_action( 'aqualuxe_header', 'my_function', 5 );

// Execute late
add_action( 'aqualuxe_header', 'my_function', 20 );
```

### 2. Conditional Hook Usage
Only add hooks when needed:
```php
if ( is_woocommerce() ) {
    add_action( 'aqualuxe_before_content', 'my_woocommerce_function' );
}
```

### 3. Proper Documentation
Document your custom hooks:
```php
/**
 * Add custom content before the header
 *
 * @since 1.0.0
 */
add_action( 'aqualuxe_before_header', 'my_custom_function' );
```

### 4. Hook Removal
Remove hooks when necessary:
```php
remove_action( 'aqualuxe_before_header', 'unwanted_function' );
```

## Conclusion

The AquaLuxe theme provides a comprehensive hook system that allows for extensive customization without modifying core theme files. These hooks follow WordPress standards and provide developers with flexible ways to extend theme functionality.

When using these hooks:
1. Always check if a hook exists before using it
2. Follow the documented parameters and return values
3. Use appropriate priorities for execution order
4. Document your custom implementations
5. Test thoroughly across different scenarios

This hook reference will be updated as new hooks are added to the theme, ensuring developers always have access to the latest customization options.