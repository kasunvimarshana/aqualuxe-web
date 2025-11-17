# AquaLuxe Theme Customization Guide

This document provides detailed information on how to customize the AquaLuxe WordPress theme to match your brand and requirements.

## Table of Contents

1. [Theme Customizer](#theme-customizer)
2. [Color Schemes](#color-schemes)
3. [Typography](#typography)
4. [Layout Options](#layout-options)
5. [Header Customization](#header-customization)
6. [Footer Customization](#footer-customization)
7. [Module Configuration](#module-configuration)
8. [Custom CSS](#custom-css)
9. [Child Theme Development](#child-theme-development)
10. [Hooks and Filters](#hooks-and-filters)

## Theme Customizer

The primary way to customize AquaLuxe is through the WordPress Customizer. Navigate to **Appearance > Customize** in your WordPress admin to access all theme options.

### Customizer Sections

- **Site Identity**: Logo, site title, tagline, and favicon
- **Colors**: Theme color scheme and custom colors
- **Typography**: Font families, sizes, and weights
- **Layout**: Container width, sidebar options, and grid settings
- **Header**: Header layout, navigation, and sticky options
- **Footer**: Footer layout, widgets, and copyright text
- **Blog**: Post display options, featured images, and metadata
- **WooCommerce**: Shop layout, product display, and checkout options
- **Modules**: Enable/disable and configure individual modules

## Color Schemes

AquaLuxe comes with multiple pre-defined color schemes that you can select from the Customizer. You can also create your own custom color scheme.

### Default Color Scheme

The default color scheme uses a blue primary color with dark secondary and warm accent colors:

- Primary Color: `#0ea5e9` (Sky Blue)
- Secondary Color: `#1e293b` (Dark Blue)
- Accent Color: `#f59e0b` (Amber)

### Custom Colors

To customize individual colors:

1. Go to **Appearance > Customize > Colors**
2. Select "Custom" from the Color Scheme dropdown
3. Use the color pickers to set your preferred colors:
   - Primary Color
   - Secondary Color
   - Accent Color
   - Success Color
   - Info Color
   - Warning Color
   - Danger Color
   - Light Color
   - Dark Color

### Dark Mode Colors

You can also customize the dark mode color scheme separately:

1. Go to **Appearance > Customize > Colors > Dark Mode**
2. Customize the dark mode specific colors:
   - Dark Background Color
   - Dark Text Color
   - Dark Border Color
   - Dark Link Color

## Typography

AquaLuxe provides extensive typography options to match your brand's style.

### Font Families

1. Go to **Appearance > Customize > Typography**
2. Select from the available font families or add custom fonts:
   - Base Font: Used for body text (default: Inter)
   - Heading Font: Used for headings (default: Playfair Display)
   - Monospace Font: Used for code (default: SFMono-Regular)

### Font Sizes

Customize the font sizes for different elements:

- Base Font Size (default: 16px)
- Heading Sizes (h1-h6)
- Small Text Size
- Large Text Size

### Font Weights

Set the font weights for different text elements:

- Body Text Weight (default: 400)
- Heading Weight (default: 700)
- Bold Text Weight (default: 700)

### Line Heights

Adjust the line heights for better readability:

- Base Line Height (default: 1.5)
- Heading Line Height (default: 1.2)
- Paragraph Line Height (default: 1.6)

## Layout Options

AquaLuxe offers flexible layout options to structure your site.

### Container Width

Set the maximum width for your site's content:

1. Go to **Appearance > Customize > Layout > Container**
2. Adjust the container width for different breakpoints:
   - Small (default: 540px)
   - Medium (default: 720px)
   - Large (default: 960px)
   - Extra Large (default: 1140px)
   - Extra Extra Large (default: 1320px)

### Sidebar Options

Configure the sidebar layout:

1. Go to **Appearance > Customize > Layout > Sidebar**
2. Choose from the following options:
   - Right Sidebar (default)
   - Left Sidebar
   - No Sidebar (Full Width)
   - Sidebar Width (default: 300px)

### Grid System

AquaLuxe uses a 12-column grid system. You can adjust the gutter width:

1. Go to **Appearance > Customize > Layout > Grid**
2. Set the gutter width (default: 2rem)

## Header Customization

Customize the header layout and functionality to match your site's design.

### Header Layout

1. Go to **Appearance > Customize > Header > Layout**
2. Choose from the available header layouts:
   - Default Header
   - Centered Logo
   - Split Menu
   - Minimal Header
   - Full Width Header

### Header Options

Configure additional header options:

- Sticky Header: Enable/disable and configure sticky header behavior
- Header Height: Set custom header height for desktop and mobile
- Header Padding: Adjust the padding around header elements
- Header Background: Set color, gradient, or image background
- Header Border: Enable/disable and style the header bottom border
- Header Shadow: Add and customize a shadow effect

### Navigation Options

Customize the main navigation:

1. Go to **Appearance > Customize > Header > Navigation**
2. Configure the following options:
   - Menu Position
   - Dropdown Style
   - Mobile Menu Type
   - Menu Item Spacing
   - Menu Typography

## Footer Customization

Create a custom footer that includes all necessary information and links.

### Footer Layout

1. Go to **Appearance > Customize > Footer > Layout**
2. Choose from the available footer layouts:
   - Default Footer (4 columns)
   - 3-Column Footer
   - 2-Column Footer
   - Simple Footer
   - Full Width Footer

### Footer Widgets

Configure the footer widget areas:

1. Go to **Appearance > Customize > Footer > Widgets**
2. Set the number of widget columns (1-6)
3. Adjust widget column widths

### Footer Options

Customize additional footer elements:

- Footer Background: Set color, gradient, or image background
- Footer Text Color: Customize the text and link colors
- Footer Padding: Adjust the padding around footer elements
- Footer Border: Enable/disable and style the footer top border
- Copyright Text: Add custom copyright text with dynamic year

## Module Configuration

AquaLuxe's modular architecture allows you to enable, disable, and configure individual modules.

### Dark Mode Module

1. Go to **Appearance > Customize > Modules > Dark Mode**
2. Configure the following options:
   - Enable/Disable Dark Mode
   - Default Theme (Light/Dark/System)
   - Toggle Position
   - Toggle Style
   - Animation Effect

### Multilingual Module

1. Go to **Appearance > Customize > Modules > Multilingual**
2. Configure the following options:
   - Enable/Disable Multilingual Support
   - Language Switcher Position
   - Language Switcher Style
   - Display Flags
   - Display Language Names

### WooCommerce Module

1. Go to **Appearance > Customize > Modules > WooCommerce**
2. Configure the following options:
   - Product Grid Columns
   - Products Per Page
   - Shop Sidebar Position
   - Product Image Style
   - Quick View
   - Ajax Add to Cart
   - Wishlist
   - Product Comparison

### Multicurrency Module

1. Go to **Appearance > Customize > Modules > Multicurrency**
2. Configure the following options:
   - Enable/Disable Multicurrency
   - Default Currency
   - Available Currencies
   - Currency Switcher Position
   - Currency Switcher Style
   - Exchange Rate Provider

### Performance Module

1. Go to **Appearance > Customize > Modules > Performance**
2. Configure the following options:
   - Lazy Loading
   - Resource Preloading
   - Script Optimization
   - Critical CSS
   - Image Optimization
   - Browser Caching

### SEO Module

1. Go to **Appearance > Customize > Modules > SEO**
2. Configure the following options:
   - Schema Markup
   - Breadcrumbs
   - Social Meta Tags
   - XML Sitemap
   - Structured Data

## Custom CSS

Add custom CSS to further customize your theme:

1. Go to **Appearance > Customize > Additional CSS**
2. Add your custom CSS code
3. Use the theme's CSS variables for consistent styling:

```css
/* Example: Change the primary color */
:root {
  --primary-color: #ff6b6b;
}

/* Example: Customize the header */
.site-header {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Example: Adjust typography */
body {
  letter-spacing: 0.01em;
}
```

## Child Theme Development

For advanced customizations, we recommend creating a child theme:

1. Create a new folder in your themes directory: `aqualuxe-child`
2. Create a `style.css` file with the following header:

```css
/*
Theme Name: AquaLuxe Child
Theme URI: https://aqualuxe.com
Description: Child theme for AquaLuxe
Author: Your Name
Author URI: https://yourwebsite.com
Template: aqualuxe
Version: 1.0.0
*/
```

3. Create a `functions.php` file:

```php
<?php
/**
 * AquaLuxe Child Theme functions and definitions
 */

// Enqueue parent and child theme styles
function aqualuxe_child_enqueue_styles() {
    wp_enqueue_style('aqualuxe-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('aqualuxe-child-style', get_stylesheet_directory_uri() . '/style.css', array('aqualuxe-style'));
}
add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles');

// Add your custom functions below this line
```

4. Activate the child theme from the WordPress admin

## Hooks and Filters

AquaLuxe provides numerous hooks and filters to extend and customize the theme functionality.

### Action Hooks

```php
// Add content before the header
add_action('aqualuxe_before_header', function() {
    echo '<div class="announcement-bar">Special offer: Free shipping on all orders!</div>';
});

// Add content after the header
add_action('aqualuxe_after_header', function() {
    // Your code here
});

// Add content before the footer
add_action('aqualuxe_before_footer', function() {
    // Your code here
});

// Add content after the footer
add_action('aqualuxe_after_footer', function() {
    // Your code here
});

// Add content before the main content
add_action('aqualuxe_before_main_content', function() {
    // Your code here
});

// Add content after the main content
add_action('aqualuxe_after_main_content', function() {
    // Your code here
});

// Add content before a post
add_action('aqualuxe_before_post', function() {
    // Your code here
});

// Add content after a post
add_action('aqualuxe_after_post', function() {
    // Your code here
});
```

### Filters

```php
// Modify the excerpt length
add_filter('aqualuxe_excerpt_length', function($length) {
    return 30; // Change excerpt length to 30 words
});

// Modify the excerpt "read more" text
add_filter('aqualuxe_excerpt_more', function($more) {
    return '... <a class="read-more" href="' . get_permalink() . '">Continue Reading</a>';
});

// Add custom classes to the body
add_filter('aqualuxe_body_classes', function($classes) {
    $classes[] = 'custom-class';
    return $classes;
});

// Modify the sidebar position
add_filter('aqualuxe_sidebar_position', function($position) {
    return 'left'; // Change sidebar position to left
});

// Modify the container width
add_filter('aqualuxe_container_width', function($width) {
    return 1200; // Change container width to 1200px
});
```

For a complete list of available hooks and filters, please refer to the [Developer Documentation](https://aqualuxe.com/docs/developers/).