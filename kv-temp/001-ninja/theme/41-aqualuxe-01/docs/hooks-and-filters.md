# AquaLuxe WordPress Theme - Hooks and Filters Guide

## Table of Contents

1. [Introduction](#introduction)
2. [Action Hooks](#action-hooks)
3. [Filter Hooks](#filter-hooks)
4. [WooCommerce Hooks](#woocommerce-hooks)
5. [Usage Examples](#usage-examples)

## Introduction

AquaLuxe theme provides a comprehensive set of hooks and filters that allow developers to customize and extend the theme's functionality without modifying core theme files. This document outlines all available hooks and filters and provides usage examples.

## Action Hooks

Action hooks allow you to insert custom code at specific points in the theme's execution.

### Header Hooks

| Hook Name | Description | Parameters |
|-----------|-------------|------------|
| `aqualuxe_before_header` | Fires before the header section | None |
| `aqualuxe_after_header` | Fires after the header section | None |
| `aqualuxe_before_main_navigation` | Fires before the main navigation | None |
| `aqualuxe_after_main_navigation` | Fires after the main navigation | None |
| `aqualuxe_header_top` | Fires at the top of the header area | None |
| `aqualuxe_header_bottom` | Fires at the bottom of the header area | None |

### Content Hooks

| Hook Name | Description | Parameters |
|-----------|-------------|------------|
| `aqualuxe_before_content` | Fires before the main content area | None |
| `aqualuxe_after_content` | Fires after the main content area | None |
| `aqualuxe_before_page_content` | Fires before page content | None |
| `aqualuxe_after_page_content` | Fires after page content | None |
| `aqualuxe_before_post_content` | Fires before post content | None |
| `aqualuxe_after_post_content` | Fires after post content | None |
| `aqualuxe_before_post_meta` | Fires before post meta information | None |
| `aqualuxe_after_post_meta` | Fires after post meta information | None |

### Footer Hooks

| Hook Name | Description | Parameters |
|-----------|-------------|------------|
| `aqualuxe_before_footer` | Fires before the footer section | None |
| `aqualuxe_after_footer` | Fires after the footer section | None |
| `aqualuxe_footer_top` | Fires at the top of the footer area | None |
| `aqualuxe_footer_bottom` | Fires at the bottom of the footer area | None |

### Sidebar Hooks

| Hook Name | Description | Parameters |
|-----------|-------------|------------|
| `aqualuxe_before_sidebar` | Fires before the sidebar | None |
| `aqualuxe_after_sidebar` | Fires after the sidebar | None |

## Filter Hooks

Filter hooks allow you to modify data before it's used by the theme.

### Content Filters

| Filter Name | Description | Parameters |
|-------------|-------------|------------|
| `aqualuxe_excerpt_length` | Modifies the excerpt length | `$length` (int) |
| `aqualuxe_excerpt_more` | Modifies the excerpt "more" text | `$more` (string) |
| `aqualuxe_content_width` | Modifies the content width | `$width` (int) |
| `aqualuxe_page_title` | Modifies the page title | `$title` (string) |
| `aqualuxe_post_title` | Modifies the post title | `$title` (string) |

### Layout Filters

| Filter Name | Description | Parameters |
|-------------|-------------|------------|
| `aqualuxe_sidebar_position` | Modifies the sidebar position | `$position` (string) |
| `aqualuxe_container_class` | Modifies the container class | `$class` (string) |
| `aqualuxe_body_classes` | Modifies the body classes | `$classes` (array) |

### Asset Filters

| Filter Name | Description | Parameters |
|-------------|-------------|------------|
| `aqualuxe_enqueue_styles` | Modifies the styles to be enqueued | `$styles` (array) |
| `aqualuxe_enqueue_scripts` | Modifies the scripts to be enqueued | `$scripts` (array) |
| `aqualuxe_google_fonts` | Modifies the Google Fonts to be loaded | `$fonts` (array) |

## WooCommerce Hooks

AquaLuxe extends WooCommerce with additional hooks for customizing the shop functionality.

### Shop Hooks

| Hook Name | Description | Parameters |
|-----------|-------------|------------|
| `aqualuxe_before_shop_loop` | Fires before the shop loop | None |
| `aqualuxe_after_shop_loop` | Fires after the shop loop | None |
| `aqualuxe_before_product_filter` | Fires before the product filter | None |
| `aqualuxe_after_product_filter` | Fires after the product filter | None |
| `aqualuxe_before_shop_topbar` | Fires before the shop topbar | None |
| `aqualuxe_after_shop_topbar` | Fires after the shop topbar | None |

### Product Hooks

| Hook Name | Description | Parameters |
|-----------|-------------|------------|
| `aqualuxe_before_product_summary` | Fires before the product summary | None |
| `aqualuxe_after_product_summary` | Fires after the product summary | None |
| `aqualuxe_before_add_to_cart` | Fires before the add to cart button | None |
| `aqualuxe_after_add_to_cart` | Fires after the add to cart button | None |
| `aqualuxe_before_product_meta` | Fires before the product meta | None |
| `aqualuxe_after_product_meta` | Fires after the product meta | None |

### Cart and Checkout Hooks

| Hook Name | Description | Parameters |
|-----------|-------------|------------|
| `aqualuxe_before_cart` | Fires before the cart | None |
| `aqualuxe_after_cart` | Fires after the cart | None |
| `aqualuxe_before_checkout` | Fires before the checkout | None |
| `aqualuxe_after_checkout` | Fires after the checkout | None |

## Usage Examples

### Adding Content to Header

```php
function my_custom_header_content() {
    echo '<div class="announcement-bar">Special offer: Free shipping on orders over $100!</div>';
}
add_action('aqualuxe_header_top', 'my_custom_header_content');
```

### Modifying Excerpt Length

```php
function my_custom_excerpt_length($length) {
    return 30; // Change excerpt length to 30 words
}
add_filter('aqualuxe_excerpt_length', 'my_custom_excerpt_length');
```

### Adding Content Before Product Summary

```php
function my_custom_product_badge() {
    global $product;
    if ($product->is_featured()) {
        echo '<span class="featured-badge">Featured Product</span>';
    }
}
add_action('aqualuxe_before_product_summary', 'my_custom_product_badge');
```

### Modifying Body Classes

```php
function my_custom_body_classes($classes) {
    if (is_page('about-us')) {
        $classes[] = 'about-page-custom';
    }
    return $classes;
}
add_filter('aqualuxe_body_classes', 'my_custom_body_classes');
```

### Adding Content to Footer

```php
function my_custom_footer_content() {
    echo '<div class="footer-disclaimer">All prices include applicable taxes.</div>';
}
add_action('aqualuxe_footer_bottom', 'my_custom_footer_content');
```