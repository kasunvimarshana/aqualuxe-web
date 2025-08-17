# AquaLuxe WordPress Theme - Hooks and Filters Reference

## Table of Contents
1. [Introduction](#introduction)
2. [Action Hooks](#action-hooks)
   - [Header Hooks](#header-hooks)
   - [Content Hooks](#content-hooks)
   - [Footer Hooks](#footer-hooks)
   - [Template Hooks](#template-hooks)
   - [WooCommerce Hooks](#woocommerce-hooks)
3. [Filter Hooks](#filter-hooks)
   - [Layout Filters](#layout-filters)
   - [Content Filters](#content-filters)
   - [Style Filters](#style-filters)
   - [Functionality Filters](#functionality-filters)
   - [WooCommerce Filters](#woocommerce-filters)
4. [Usage Examples](#usage-examples)
5. [Custom Hook Creation](#custom-hook-creation)

## Introduction

This document provides a comprehensive reference for all action and filter hooks available in the AquaLuxe WordPress theme. These hooks allow developers to modify and extend the theme's functionality without editing core theme files.

### How to Use Hooks

Hooks can be used in a child theme's `functions.php` file or in a custom plugin. Here's a basic example:

```php
// Action hook example
add_action('aqualuxe_before_header', 'my_custom_function');
function my_custom_function() {
    echo '<div class="announcement">Special offer this week!</div>';
}

// Filter hook example
add_filter('aqualuxe_body_classes', 'my_custom_body_class');
function my_custom_body_class($classes) {
    $classes[] = 'my-custom-class';
    return $classes;
}
```

## Action Hooks

Action hooks allow you to add custom content or functionality at specific points in the theme.

### Header Hooks

#### `aqualuxe_before_header`
Fires before the header element.

**Parameters:** None

**Example:**
```php
add_action('aqualuxe_before_header', 'my_before_header_content');
function my_before_header_content() {
    echo '<div class="announcement-bar">Special offer this week!</div>';
}
```

#### `aqualuxe_header_content`
Fires inside the header element, allowing you to add content to the header.

**Parameters:** None

**Example:**
```php
add_action('aqualuxe_header_content', 'my_header_content');
function my_header_content() {
    echo '<div class="header-phone">Call us: 123-456-7890</div>';
}
```

#### `aqualuxe_after_header`
Fires after the header element.

**Parameters:** None

**Example:**
```php
add_action('aqualuxe_after_header', 'my_after_header_content');
function my_after_header_content() {
    echo '<div class="breadcrumbs">Home > Products</div>';
}
```

#### `aqualuxe_before_logo`
Fires before the logo element.

**Parameters:** None

#### `aqualuxe_after_logo`
Fires after the logo element.

**Parameters:** None

#### `aqualuxe_before_navigation`
Fires before the main navigation.

**Parameters:** None

#### `aqualuxe_after_navigation`
Fires after the main navigation.

**Parameters:** None

### Content Hooks

#### `aqualuxe_before_content`
Fires before the main content area.

**Parameters:** None

**Example:**
```php
add_action('aqualuxe_before_content', 'my_before_content');
function my_before_content() {
    if (is_single()) {
        echo '<div class="featured-image-overlay"></div>';
    }
}
```

#### `aqualuxe_after_content`
Fires after the main content area.

**Parameters:** None

**Example:**
```php
add_action('aqualuxe_after_content', 'my_after_content');
function my_after_content() {
    if (is_single()) {
        echo '<div class="related-posts">Related posts here...</div>';
    }
}
```

#### `aqualuxe_before_post_content`
Fires before the post content.

**Parameters:** None

#### `aqualuxe_after_post_content`
Fires after the post content.

**Parameters:** None

**Example:**
```php
add_action('aqualuxe_after_post_content', 'my_after_post_content');
function my_after_post_content() {
    echo '<div class="author-bio">' . get_the_author_meta('description') . '</div>';
}
```

#### `aqualuxe_before_page_content`
Fires before the page content.

**Parameters:** None

#### `aqualuxe_after_page_content`
Fires after the page content.

**Parameters:** None

#### `aqualuxe_before_comments`
Fires before the comments section.

**Parameters:** None

#### `aqualuxe_after_comments`
Fires after the comments section.

**Parameters:** None

#### `aqualuxe_before_sidebar`
Fires before the sidebar.

**Parameters:** None

#### `aqualuxe_after_sidebar`
Fires after the sidebar.

**Parameters:** None

### Footer Hooks

#### `aqualuxe_before_footer`
Fires before the footer element.

**Parameters:** None

**Example:**
```php
add_action('aqualuxe_before_footer', 'my_before_footer_content');
function my_before_footer_content() {
    echo '<div class="pre-footer">Subscribe to our newsletter</div>';
}
```

#### `aqualuxe_footer_content`
Fires inside the footer element, allowing you to add content to the footer.

**Parameters:** None

**Example:**
```php
add_action('aqualuxe_footer_content', 'my_footer_content');
function my_footer_content() {
    echo '<div class="footer-awards">Award logos here...</div>';
}
```

#### `aqualuxe_after_footer`
Fires after the footer element.

**Parameters:** None

**Example:**
```php
add_action('aqualuxe_after_footer', 'my_after_footer_content');
function my_after_footer_content() {
    echo '<div class="cookie-notice">This site uses cookies...</div>';
}
```

#### `aqualuxe_before_footer_widgets`
Fires before the footer widgets area.

**Parameters:** None

#### `aqualuxe_after_footer_widgets`
Fires after the footer widgets area.

**Parameters:** None

#### `aqualuxe_before_footer_bottom`
Fires before the footer bottom area (copyright, etc.).

**Parameters:** None

#### `aqualuxe_after_footer_bottom`
Fires after the footer bottom area.

**Parameters:** None

### Template Hooks

#### `aqualuxe_before_template_part`
Fires before a template part is included.

**Parameters:**
- `$slug` (string) The slug name for the generic template.
- `$name` (string) The name of the specialized template.

**Example:**
```php
add_action('aqualuxe_before_template_part', 'my_before_template_part', 10, 2);
function my_before_template_part($slug, $name) {
    if ($slug === 'template-parts/content' && $name === 'page') {
        echo '<div class="template-part-wrapper">';
    }
}
```

#### `aqualuxe_after_template_part`
Fires after a template part is included.

**Parameters:**
- `$slug` (string) The slug name for the generic template.
- `$name` (string) The name of the specialized template.

**Example:**
```php
add_action('aqualuxe_after_template_part', 'my_after_template_part', 10, 2);
function my_after_template_part($slug, $name) {
    if ($slug === 'template-parts/content' && $name === 'page') {
        echo '</div><!-- .template-part-wrapper -->';
    }
}
```

#### `aqualuxe_before_loop`
Fires before the WordPress loop.

**Parameters:** None

#### `aqualuxe_after_loop`
Fires after the WordPress loop.

**Parameters:** None

#### `aqualuxe_no_results`
Fires when no results are found in a query.

**Parameters:** None

**Example:**
```php
add_action('aqualuxe_no_results', 'my_no_results_content');
function my_no_results_content() {
    echo '<div class="no-results-custom">Sorry, no results found. Try a different search.</div>';
}
```

### WooCommerce Hooks

#### `aqualuxe_before_shop`
Fires before the shop content.

**Parameters:** None

#### `aqualuxe_after_shop`
Fires after the shop content.

**Parameters:** None

#### `aqualuxe_before_product_summary`
Fires before the product summary.

**Parameters:** None

#### `aqualuxe_after_product_summary`
Fires after the product summary.

**Parameters:** None

#### `aqualuxe_before_cart`
Fires before the cart content.

**Parameters:** None

#### `aqualuxe_after_cart`
Fires after the cart content.

**Parameters:** None

#### `aqualuxe_before_checkout`
Fires before the checkout content.

**Parameters:** None

#### `aqualuxe_after_checkout`
Fires after the checkout content.

**Parameters:** None

## Filter Hooks

Filter hooks allow you to modify data or settings in the theme.

### Layout Filters

#### `aqualuxe_body_classes`
Filter the classes for the body element.

**Parameters:**
- `$classes` (array) An array of body classes.

**Example:**
```php
add_filter('aqualuxe_body_classes', 'my_body_classes');
function my_body_classes($classes) {
    if (is_front_page()) {
        $classes[] = 'home-special-layout';
    }
    return $classes;
}
```

#### `aqualuxe_post_classes`
Filter the classes for the post element.

**Parameters:**
- `$classes` (array) An array of post classes.

**Example:**
```php
add_filter('aqualuxe_post_classes', 'my_post_classes');
function my_post_classes($classes) {
    if (has_post_thumbnail()) {
        $classes[] = 'has-thumbnail';
    }
    return $classes;
}
```

#### `aqualuxe_sidebar_position`
Filter the sidebar position.

**Parameters:**
- `$position` (string) The sidebar position ('left', 'right', or 'none').

**Example:**
```php
add_filter('aqualuxe_sidebar_position', 'my_sidebar_position');
function my_sidebar_position($position) {
    if (is_page('about')) {
        return 'none'; // No sidebar on About page
    }
    return $position;
}
```

#### `aqualuxe_container_class`
Filter the container class.

**Parameters:**
- `$class` (string) The container class.

#### `aqualuxe_content_width`
Filter the content width.

**Parameters:**
- `$width` (int) The content width in pixels.

### Content Filters

#### `aqualuxe_excerpt_length`
Filter the excerpt length.

**Parameters:**
- `$length` (int) The excerpt length.

**Example:**
```php
add_filter('aqualuxe_excerpt_length', 'my_excerpt_length');
function my_excerpt_length($length) {
    return 30; // 30 words
}
```

#### `aqualuxe_excerpt_more`
Filter the excerpt "more" text.

**Parameters:**
- `$more` (string) The excerpt "more" text.

**Example:**
```php
add_filter('aqualuxe_excerpt_more', 'my_excerpt_more');
function my_excerpt_more($more) {
    return '... <a href="' . get_permalink() . '" class="read-more">Read More</a>';
}
```

#### `aqualuxe_page_title`
Filter the page title.

**Parameters:**
- `$title` (string) The page title.

**Example:**
```php
add_filter('aqualuxe_page_title', 'my_page_title');
function my_page_title($title) {
    if (is_category()) {
        return 'Category: ' . $title;
    }
    return $title;
}
```

#### `aqualuxe_post_meta`
Filter the post meta information.

**Parameters:**
- `$meta` (string) The post meta HTML.
- `$post_id` (int) The post ID.

**Example:**
```php
add_filter('aqualuxe_post_meta', 'my_post_meta', 10, 2);
function my_post_meta($meta, $post_id) {
    $meta .= '<span class="post-views">Views: ' . get_post_meta($post_id, 'post_views', true) . '</span>';
    return $meta;
}
```

#### `aqualuxe_comment_form_args`
Filter the comment form arguments.

**Parameters:**
- `$args` (array) The comment form arguments.

#### `aqualuxe_related_posts_args`
Filter the related posts query arguments.

**Parameters:**
- `$args` (array) The query arguments.
- `$post_id` (int) The post ID.

**Example:**
```php
add_filter('aqualuxe_related_posts_args', 'my_related_posts_args', 10, 2);
function my_related_posts_args($args, $post_id) {
    $args['posts_per_page'] = 4; // Show 4 related posts
    return $args;
}
```

### Style Filters

#### `aqualuxe_primary_color`
Filter the primary color.

**Parameters:**
- `$color` (string) The primary color hex code.

**Example:**
```php
add_filter('aqualuxe_primary_color', 'my_primary_color');
function my_primary_color($color) {
    return '#0073aa'; // Change primary color
}
```

#### `aqualuxe_secondary_color`
Filter the secondary color.

**Parameters:**
- `$color` (string) The secondary color hex code.

#### `aqualuxe_accent_color`
Filter the accent color.

**Parameters:**
- `$color` (string) The accent color hex code.

#### `aqualuxe_heading_font`
Filter the heading font.

**Parameters:**
- `$font` (string) The heading font family.

**Example:**
```php
add_filter('aqualuxe_heading_font', 'my_heading_font');
function my_heading_font($font) {
    return 'Montserrat, sans-serif';
}
```

#### `aqualuxe_body_font`
Filter the body font.

**Parameters:**
- `$font` (string) The body font family.

#### `aqualuxe_font_sizes`
Filter the font sizes.

**Parameters:**
- `$sizes` (array) An array of font sizes.

**Example:**
```php
add_filter('aqualuxe_font_sizes', 'my_font_sizes');
function my_font_sizes($sizes) {
    $sizes['h1'] = '3rem';
    return $sizes;
}
```

### Functionality Filters

#### `aqualuxe_social_sharing_networks`
Filter the social sharing networks.

**Parameters:**
- `$networks` (array) An array of social networks.

**Example:**
```php
add_filter('aqualuxe_social_sharing_networks', 'my_social_sharing_networks');
function my_social_sharing_networks($networks) {
    // Add LinkedIn to social sharing
    $networks[] = 'linkedin';
    // Remove Twitter from social sharing
    if (($key = array_search('twitter', $networks)) !== false) {
        unset($networks[$key]);
    }
    return $networks;
}
```

#### `aqualuxe_social_media_links`
Filter the social media links.

**Parameters:**
- `$links` (array) An array of social media links.

#### `aqualuxe_copyright_text`
Filter the copyright text.

**Parameters:**
- `$text` (string) The copyright text.

**Example:**
```php
add_filter('aqualuxe_copyright_text', 'my_copyright_text');
function my_copyright_text($text) {
    return '© ' . date('Y') . ' My Company. All rights reserved.';
}
```

#### `aqualuxe_footer_credits`
Filter the footer credits.

**Parameters:**
- `$credits` (string) The footer credits.

#### `aqualuxe_contact_info`
Filter the contact information.

**Parameters:**
- `$info` (array) An array of contact information.

**Example:**
```php
add_filter('aqualuxe_contact_info', 'my_contact_info');
function my_contact_info($info) {
    $info['phone'] = '123-456-7890';
    $info['email'] = 'info@example.com';
    return $info;
}
```

### WooCommerce Filters

#### `aqualuxe_product_columns`
Filter the number of product columns.

**Parameters:**
- `$columns` (int) The number of columns.

**Example:**
```php
add_filter('aqualuxe_product_columns', 'my_product_columns');
function my_product_columns($columns) {
    return 4; // 4 columns of products
}
```

#### `aqualuxe_products_per_page`
Filter the number of products per page.

**Parameters:**
- `$products` (int) The number of products.

#### `aqualuxe_related_products_args`
Filter the related products query arguments.

**Parameters:**
- `$args` (array) The query arguments.

**Example:**
```php
add_filter('aqualuxe_related_products_args', 'my_related_products_args');
function my_related_products_args($args) {
    $args['posts_per_page'] = 4; // Show 4 related products
    return $args;
}
```

#### `aqualuxe_add_to_cart_text`
Filter the "Add to Cart" button text.

**Parameters:**
- `$text` (string) The button text.

#### `aqualuxe_product_tabs`
Filter the product tabs.

**Parameters:**
- `$tabs` (array) An array of product tabs.

**Example:**
```php
add_filter('aqualuxe_product_tabs', 'my_product_tabs');
function my_product_tabs($tabs) {
    // Add a custom tab
    $tabs['custom_tab'] = array(
        'title'    => __('Custom Tab', 'aqualuxe'),
        'priority' => 50,
        'callback' => 'my_custom_tab_content',
    );
    return $tabs;
}

function my_custom_tab_content() {
    echo '<p>Custom tab content here.</p>';
}
```

## Usage Examples

### Adding Content to the Header

```php
add_action('aqualuxe_header_content', 'my_header_content');
function my_header_content() {
    echo '<div class="header-announcement">';
    echo '<p>Free shipping on orders over $50!</p>';
    echo '</div>';
}
```

### Modifying the Footer Copyright Text

```php
add_filter('aqualuxe_copyright_text', 'my_copyright_text');
function my_copyright_text($text) {
    return '© ' . date('Y') . ' My Company. All rights reserved.';
}
```

### Adding a Custom Body Class

```php
add_filter('aqualuxe_body_classes', 'my_body_classes');
function my_body_classes($classes) {
    if (is_page('about')) {
        $classes[] = 'about-page';
    }
    return $classes;
}
```

### Modifying the Excerpt Length

```php
add_filter('aqualuxe_excerpt_length', 'my_excerpt_length');
function my_excerpt_length($length) {
    return 30; // 30 words
}
```

### Adding Content After Post Content

```php
add_action('aqualuxe_after_post_content', 'my_after_post_content');
function my_after_post_content() {
    if (is_single()) {
        echo '<div class="author-bio">';
        echo '<h3>About the Author</h3>';
        echo '<p>' . get_the_author_meta('description') . '</p>';
        echo '</div>';
    }
}
```

### Modifying WooCommerce Product Columns

```php
add_filter('aqualuxe_product_columns', 'my_product_columns');
function my_product_columns($columns) {
    return 4; // 4 columns of products
}
```

### Adding a Custom Tab to Product Pages

```php
add_filter('aqualuxe_product_tabs', 'my_product_tabs');
function my_product_tabs($tabs) {
    // Add a custom tab
    $tabs['custom_tab'] = array(
        'title'    => __('Custom Tab', 'aqualuxe'),
        'priority' => 50,
        'callback' => 'my_custom_tab_content',
    );
    return $tabs;
}

function my_custom_tab_content() {
    echo '<p>Custom tab content here.</p>';
}
```

## Custom Hook Creation

If you're creating a child theme or plugin that extends AquaLuxe, you may want to create your own hooks. Here's how to do it:

### Creating an Action Hook

```php
function my_theme_section() {
    // Your code here
    
    // Create an action hook
    do_action('my_theme_custom_hook');
    
    // More code here
}
```

### Creating a Filter Hook

```php
function my_theme_get_option($option) {
    $value = get_theme_mod($option, '');
    
    // Create a filter hook
    return apply_filters('my_theme_option_value', $value, $option);
}
```

### Best Practices for Custom Hooks

1. **Naming Convention**: Use a unique prefix for your hooks to avoid conflicts.
2. **Documentation**: Document your hooks clearly for other developers.
3. **Parameters**: Pass relevant parameters to your hooks.
4. **Default Values**: For filters, always provide a default value.
5. **Priority**: Consider the priority of your hooks in relation to other hooks.