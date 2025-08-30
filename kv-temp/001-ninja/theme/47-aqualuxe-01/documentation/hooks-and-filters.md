# AquaLuxe WordPress Theme - Hooks and Filters Reference

## Table of Contents
1. [Introduction](#introduction)
2. [Action Hooks](#action-hooks)
   - [Template Hooks](#template-hooks)
   - [Header Hooks](#header-hooks)
   - [Footer Hooks](#footer-hooks)
   - [Content Hooks](#content-hooks)
   - [WooCommerce Hooks](#woocommerce-hooks)
3. [Filter Hooks](#filter-hooks)
   - [Template Filters](#template-filters)
   - [Content Filters](#content-filters)
   - [WooCommerce Filters](#woocommerce-filters)
   - [Customizer Filters](#customizer-filters)
4. [Usage Examples](#usage-examples)
5. [Creating Custom Hooks](#creating-custom-hooks)

## Introduction

AquaLuxe provides a comprehensive set of hooks and filters that allow developers to modify and extend the theme's functionality without editing core theme files. This document serves as a reference for all available hooks and filters.

### What are Hooks and Filters?

- **Action Hooks**: Allow you to add or modify functionality at specific points in the theme
- **Filter Hooks**: Allow you to modify data before it's used by the theme

### How to Use Hooks and Filters

Hooks and filters are used through WordPress's `add_action()` and `add_filter()` functions:

```php
// Using an action hook
add_action('hook_name', 'your_function_name', priority, accepted_args);

// Using a filter hook
add_filter('filter_name', 'your_function_name', priority, accepted_args);
```

## Action Hooks

### Template Hooks

These hooks are fired at specific points in the theme's template files.

#### `aqualuxe_before_doctype`
- **Description**: Fires before the DOCTYPE declaration
- **Parameters**: None
- **Location**: header.php
- **Example Use**: Adding meta tags or scripts that need to be before the DOCTYPE

#### `aqualuxe_head_top`
- **Description**: Fires at the top of the `<head>` section
- **Parameters**: None
- **Location**: header.php
- **Example Use**: Adding meta tags or early scripts

#### `aqualuxe_head_bottom`
- **Description**: Fires at the bottom of the `<head>` section
- **Parameters**: None
- **Location**: header.php
- **Example Use**: Adding late-loading scripts or styles

#### `aqualuxe_body_top`
- **Description**: Fires at the top of the `<body>` element
- **Parameters**: None
- **Location**: header.php
- **Example Use**: Adding body-level scripts or elements

#### `aqualuxe_body_bottom`
- **Description**: Fires at the bottom of the `<body>` element
- **Parameters**: None
- **Location**: footer.php
- **Example Use**: Adding scripts that need to load at the end of the page

### Header Hooks

These hooks are specific to the header area of the theme.

#### `aqualuxe_before_header`
- **Description**: Fires before the header
- **Parameters**: None
- **Location**: header.php
- **Example Use**: Adding content above the header, such as announcements

#### `aqualuxe_header_top`
- **Description**: Fires at the top of the header
- **Parameters**: None
- **Location**: header.php
- **Example Use**: Adding content to the top of the header

#### `aqualuxe_before_logo`
- **Description**: Fires before the logo
- **Parameters**: None
- **Location**: template-parts/header/logo.php
- **Example Use**: Adding content before the logo

#### `aqualuxe_after_logo`
- **Description**: Fires after the logo
- **Parameters**: None
- **Location**: template-parts/header/logo.php
- **Example Use**: Adding content after the logo

#### `aqualuxe_before_main_navigation`
- **Description**: Fires before the main navigation
- **Parameters**: None
- **Location**: template-parts/header/navigation.php
- **Example Use**: Adding content before the main navigation

#### `aqualuxe_after_main_navigation`
- **Description**: Fires after the main navigation
- **Parameters**: None
- **Location**: template-parts/header/navigation.php
- **Example Use**: Adding content after the main navigation

#### `aqualuxe_header_bottom`
- **Description**: Fires at the bottom of the header
- **Parameters**: None
- **Location**: header.php
- **Example Use**: Adding content to the bottom of the header

#### `aqualuxe_after_header`
- **Description**: Fires after the header
- **Parameters**: None
- **Location**: header.php
- **Example Use**: Adding content below the header

### Footer Hooks

These hooks are specific to the footer area of the theme.

#### `aqualuxe_before_footer`
- **Description**: Fires before the footer
- **Parameters**: None
- **Location**: footer.php
- **Example Use**: Adding content above the footer

#### `aqualuxe_footer_top`
- **Description**: Fires at the top of the footer
- **Parameters**: None
- **Location**: footer.php
- **Example Use**: Adding content to the top of the footer

#### `aqualuxe_before_footer_widgets`
- **Description**: Fires before the footer widgets
- **Parameters**: None
- **Location**: template-parts/footer/widgets.php
- **Example Use**: Adding content before the footer widgets

#### `aqualuxe_after_footer_widgets`
- **Description**: Fires after the footer widgets
- **Parameters**: None
- **Location**: template-parts/footer/widgets.php
- **Example Use**: Adding content after the footer widgets

#### `aqualuxe_before_footer_bottom`
- **Description**: Fires before the footer bottom section
- **Parameters**: None
- **Location**: template-parts/footer/bottom.php
- **Example Use**: Adding content before the footer bottom section

#### `aqualuxe_after_footer_bottom`
- **Description**: Fires after the footer bottom section
- **Parameters**: None
- **Location**: template-parts/footer/bottom.php
- **Example Use**: Adding content after the footer bottom section

#### `aqualuxe_footer_bottom`
- **Description**: Fires at the bottom of the footer
- **Parameters**: None
- **Location**: footer.php
- **Example Use**: Adding content to the bottom of the footer

#### `aqualuxe_after_footer`
- **Description**: Fires after the footer
- **Parameters**: None
- **Location**: footer.php
- **Example Use**: Adding content below the footer

### Content Hooks

These hooks are related to the main content area of the theme.

#### `aqualuxe_before_content`
- **Description**: Fires before the main content
- **Parameters**: None
- **Location**: index.php, page.php, single.php, archive.php
- **Example Use**: Adding content before the main content area

#### `aqualuxe_after_content`
- **Description**: Fires after the main content
- **Parameters**: None
- **Location**: index.php, page.php, single.php, archive.php
- **Example Use**: Adding content after the main content area

#### `aqualuxe_before_post_content`
- **Description**: Fires before the post content
- **Parameters**: None
- **Location**: template-parts/content.php, template-parts/content-single.php
- **Example Use**: Adding content before the post content

#### `aqualuxe_after_post_content`
- **Description**: Fires after the post content
- **Parameters**: None
- **Location**: template-parts/content.php, template-parts/content-single.php
- **Example Use**: Adding content after the post content

#### `aqualuxe_before_post_meta`
- **Description**: Fires before the post meta information
- **Parameters**: None
- **Location**: template-parts/content.php, template-parts/content-single.php
- **Example Use**: Adding content before the post meta information

#### `aqualuxe_after_post_meta`
- **Description**: Fires after the post meta information
- **Parameters**: None
- **Location**: template-parts/content.php, template-parts/content-single.php
- **Example Use**: Adding content after the post meta information

#### `aqualuxe_before_comments`
- **Description**: Fires before the comments section
- **Parameters**: None
- **Location**: comments.php
- **Example Use**: Adding content before the comments section

#### `aqualuxe_after_comments`
- **Description**: Fires after the comments section
- **Parameters**: None
- **Location**: comments.php
- **Example Use**: Adding content after the comments section

#### `aqualuxe_sidebar`
- **Description**: Fires in the sidebar area
- **Parameters**: None
- **Location**: sidebar.php
- **Example Use**: Adding content to the sidebar

### WooCommerce Hooks

These hooks are specific to WooCommerce functionality and are only available when WooCommerce is active.

#### `aqualuxe_before_shop_loop`
- **Description**: Fires before the product loop on shop pages
- **Parameters**: None
- **Location**: woocommerce/archive-product.php
- **Example Use**: Adding content before the product loop

#### `aqualuxe_after_shop_loop`
- **Description**: Fires after the product loop on shop pages
- **Parameters**: None
- **Location**: woocommerce/archive-product.php
- **Example Use**: Adding content after the product loop

#### `aqualuxe_before_single_product`
- **Description**: Fires before the single product content
- **Parameters**: None
- **Location**: woocommerce/single-product.php
- **Example Use**: Adding content before the single product content

#### `aqualuxe_after_single_product`
- **Description**: Fires after the single product content
- **Parameters**: None
- **Location**: woocommerce/single-product.php
- **Example Use**: Adding content after the single product content

#### `aqualuxe_before_product_meta`
- **Description**: Fires before the product meta information
- **Parameters**: None
- **Location**: woocommerce/content-single-product.php
- **Example Use**: Adding content before the product meta information

#### `aqualuxe_after_product_meta`
- **Description**: Fires after the product meta information
- **Parameters**: None
- **Location**: woocommerce/content-single-product.php
- **Example Use**: Adding content after the product meta information

#### `aqualuxe_before_cart_table`
- **Description**: Fires before the cart table
- **Parameters**: None
- **Location**: woocommerce/cart/cart.php
- **Example Use**: Adding content before the cart table

#### `aqualuxe_after_cart_table`
- **Description**: Fires after the cart table
- **Parameters**: None
- **Location**: woocommerce/cart/cart.php
- **Example Use**: Adding content after the cart table

#### `aqualuxe_before_checkout_form`
- **Description**: Fires before the checkout form
- **Parameters**: None
- **Location**: woocommerce/checkout/form-checkout.php
- **Example Use**: Adding content before the checkout form

#### `aqualuxe_after_checkout_form`
- **Description**: Fires after the checkout form
- **Parameters**: None
- **Location**: woocommerce/checkout/form-checkout.php
- **Example Use**: Adding content after the checkout form

## Filter Hooks

### Template Filters

These filters allow you to modify template-related functionality.

#### `aqualuxe_page_layout`
- **Description**: Filters the page layout
- **Parameters**: `$layout` (string) The current layout
- **Default**: Based on theme options
- **Example Use**: Changing the page layout for specific pages

```php
add_filter('aqualuxe_page_layout', 'my_custom_page_layout', 10, 1);
function my_custom_page_layout($layout) {
    if (is_page('about')) {
        return 'full-width';
    }
    return $layout;
}
```

#### `aqualuxe_sidebar_position`
- **Description**: Filters the sidebar position
- **Parameters**: `$position` (string) The current position
- **Default**: Based on theme options
- **Example Use**: Changing the sidebar position for specific pages

```php
add_filter('aqualuxe_sidebar_position', 'my_custom_sidebar_position', 10, 1);
function my_custom_sidebar_position($position) {
    if (is_page('contact')) {
        return 'none';
    }
    return $position;
}
```

#### `aqualuxe_show_sidebar`
- **Description**: Filters whether to show the sidebar
- **Parameters**: `$show` (boolean) Whether to show the sidebar
- **Default**: Based on theme options
- **Example Use**: Hiding the sidebar on specific pages

```php
add_filter('aqualuxe_show_sidebar', 'my_custom_show_sidebar', 10, 1);
function my_custom_show_sidebar($show) {
    if (is_page('landing-page')) {
        return false;
    }
    return $show;
}
```

### Content Filters

These filters allow you to modify content-related functionality.

#### `aqualuxe_excerpt_length`
- **Description**: Filters the excerpt length
- **Parameters**: `$length` (int) The current excerpt length
- **Default**: 55
- **Example Use**: Changing the excerpt length

```php
add_filter('aqualuxe_excerpt_length', 'my_custom_excerpt_length', 10, 1);
function my_custom_excerpt_length($length) {
    return 30;
}
```

#### `aqualuxe_excerpt_more`
- **Description**: Filters the excerpt "more" text
- **Parameters**: `$more` (string) The current "more" text
- **Default**: &hellip;
- **Example Use**: Changing the excerpt "more" text

```php
add_filter('aqualuxe_excerpt_more', 'my_custom_excerpt_more', 10, 1);
function my_custom_excerpt_more($more) {
    return '... <a href="' . get_permalink() . '">Read More</a>';
}
```

#### `aqualuxe_post_meta`
- **Description**: Filters the post meta information
- **Parameters**: `$meta` (array) The current meta information
- **Default**: Array of meta items
- **Example Use**: Adding or removing meta information

```php
add_filter('aqualuxe_post_meta', 'my_custom_post_meta', 10, 1);
function my_custom_post_meta($meta) {
    // Add a custom meta item
    $meta['custom'] = '<span class="custom-meta">Custom Meta</span>';
    
    // Remove the author meta
    unset($meta['author']);
    
    return $meta;
}
```

#### `aqualuxe_related_posts_args`
- **Description**: Filters the arguments for related posts
- **Parameters**: `$args` (array) The current arguments
- **Default**: Array of arguments
- **Example Use**: Changing the number of related posts

```php
add_filter('aqualuxe_related_posts_args', 'my_custom_related_posts_args', 10, 1);
function my_custom_related_posts_args($args) {
    $args['posts_per_page'] = 6;
    return $args;
}
```

### WooCommerce Filters

These filters are specific to WooCommerce functionality and are only available when WooCommerce is active.

#### `aqualuxe_products_per_page`
- **Description**: Filters the number of products per page
- **Parameters**: `$per_page` (int) The current number of products per page
- **Default**: Based on theme options
- **Example Use**: Changing the number of products per page

```php
add_filter('aqualuxe_products_per_page', 'my_custom_products_per_page', 10, 1);
function my_custom_products_per_page($per_page) {
    return 24;
}
```

#### `aqualuxe_products_per_row`
- **Description**: Filters the number of products per row
- **Parameters**: `$per_row` (int) The current number of products per row
- **Default**: Based on theme options
- **Example Use**: Changing the number of products per row

```php
add_filter('aqualuxe_products_per_row', 'my_custom_products_per_row', 10, 1);
function my_custom_products_per_row($per_row) {
    return 3;
}
```

#### `aqualuxe_shop_layout`
- **Description**: Filters the shop layout
- **Parameters**: `$layout` (string) The current layout
- **Default**: Based on theme options
- **Example Use**: Changing the shop layout

```php
add_filter('aqualuxe_shop_layout', 'my_custom_shop_layout', 10, 1);
function my_custom_shop_layout($layout) {
    return 'list';
}
```

#### `aqualuxe_related_products_args`
- **Description**: Filters the arguments for related products
- **Parameters**: `$args` (array) The current arguments
- **Default**: Array of arguments
- **Example Use**: Changing the number of related products

```php
add_filter('aqualuxe_related_products_args', 'my_custom_related_products_args', 10, 1);
function my_custom_related_products_args($args) {
    $args['posts_per_page'] = 6;
    $args['columns'] = 3;
    return $args;
}
```

### Customizer Filters

These filters allow you to modify Customizer-related functionality.

#### `aqualuxe_customizer_sections`
- **Description**: Filters the Customizer sections
- **Parameters**: `$sections` (array) The current sections
- **Default**: Array of sections
- **Example Use**: Adding or removing Customizer sections

```php
add_filter('aqualuxe_customizer_sections', 'my_custom_customizer_sections', 10, 1);
function my_custom_customizer_sections($sections) {
    // Add a new section
    $sections['my_section'] = array(
        'title' => 'My Section',
        'priority' => 200,
    );
    
    // Remove a section
    unset($sections['social_media']);
    
    return $sections;
}
```

#### `aqualuxe_customizer_settings`
- **Description**: Filters the Customizer settings
- **Parameters**: `$settings` (array) The current settings
- **Default**: Array of settings
- **Example Use**: Adding or removing Customizer settings

```php
add_filter('aqualuxe_customizer_settings', 'my_custom_customizer_settings', 10, 1);
function my_custom_customizer_settings($settings) {
    // Add a new setting
    $settings['my_setting'] = array(
        'default' => 'default_value',
        'sanitize_callback' => 'sanitize_text_field',
    );
    
    // Remove a setting
    unset($settings['aqualuxe_footer_copyright']);
    
    return $settings;
}
```

#### `aqualuxe_customizer_controls`
- **Description**: Filters the Customizer controls
- **Parameters**: `$controls` (array) The current controls
- **Default**: Array of controls
- **Example Use**: Adding or removing Customizer controls

```php
add_filter('aqualuxe_customizer_controls', 'my_custom_customizer_controls', 10, 1);
function my_custom_customizer_controls($controls) {
    // Add a new control
    $controls['my_setting'] = array(
        'label' => 'My Setting',
        'section' => 'my_section',
        'type' => 'text',
    );
    
    // Remove a control
    unset($controls['aqualuxe_footer_copyright']);
    
    return $controls;
}
```

## Usage Examples

### Adding Content Before the Header

```php
add_action('aqualuxe_before_header', 'my_custom_header_content');
function my_custom_header_content() {
    echo '<div class="announcement-bar">Special offer: Free shipping on orders over $50!</div>';
}
```

### Adding Content After the Footer

```php
add_action('aqualuxe_after_footer', 'my_custom_footer_content');
function my_custom_footer_content() {
    echo '<div class="post-footer">Designed by <a href="https://example.com">Your Name</a></div>';
}
```

### Modifying the Excerpt Length

```php
add_filter('aqualuxe_excerpt_length', 'my_custom_excerpt_length');
function my_custom_excerpt_length($length) {
    return 30; // 30 words
}
```

### Adding a Custom Meta Item to Posts

```php
add_action('aqualuxe_after_post_meta', 'my_custom_post_meta_item');
function my_custom_post_meta_item() {
    echo '<span class="custom-meta">Custom Meta: ' . get_post_meta(get_the_ID(), 'custom_meta', true) . '</span>';
}
```

### Changing the Shop Layout for a Specific Category

```php
add_filter('aqualuxe_shop_layout', 'my_custom_category_shop_layout');
function my_custom_category_shop_layout($layout) {
    if (is_product_category('fish')) {
        return 'grid';
    }
    return $layout;
}
```

### Adding Content Before the Product Meta

```php
add_action('aqualuxe_before_product_meta', 'my_custom_product_notice');
function my_custom_product_notice() {
    echo '<div class="product-notice">All fish come with our 7-day live arrival guarantee!</div>';
}
```

## Creating Custom Hooks

If you need to create your own hooks in a child theme or plugin, follow these best practices:

### Creating an Action Hook

```php
/**
 * Example of creating a custom action hook
 */
function my_theme_custom_section() {
    // This is where the hook will be executed
    do_action('my_theme_custom_hook');
}

// Use the hook in your template
add_action('aqualuxe_after_content', 'my_theme_custom_section');
```

### Creating a Filter Hook

```php
/**
 * Example of creating a custom filter hook
 */
function my_theme_custom_text() {
    // Get the default text
    $text = 'Default text';
    
    // Apply the filter
    $text = apply_filters('my_theme_custom_text', $text);
    
    // Output the text
    echo $text;
}

// Use the hook in your template
add_action('aqualuxe_after_content', 'my_theme_custom_text');
```

### Using Your Custom Hooks

```php
// Using your custom action hook
add_action('my_theme_custom_hook', 'my_custom_function');
function my_custom_function() {
    echo 'This is added through my custom hook!';
}

// Using your custom filter hook
add_filter('my_theme_custom_text', 'my_custom_text_filter');
function my_custom_text_filter($text) {
    return 'Modified text: ' . $text;
}
```