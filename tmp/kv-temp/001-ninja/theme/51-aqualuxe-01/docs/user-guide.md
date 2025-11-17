# AquaLuxe Theme User Guide

Welcome to the AquaLuxe theme user guide! This document will help you get started with the AquaLuxe theme and show you how to use its features.

## Table of Contents

1. [Installation](#installation)
2. [Theme Setup](#theme-setup)
3. [Customizer Options](#customizer-options)
4. [Homepage Setup](#homepage-setup)
5. [WooCommerce Integration](#woocommerce-integration)
6. [Dark Mode](#dark-mode)
7. [Multilingual Support](#multilingual-support)
8. [Multi-currency Support](#multi-currency-support)
9. [Multivendor Support](#multivendor-support)
10. [Frequently Asked Questions](#frequently-asked-questions)

## Installation

### Manual Installation

1. Download the AquaLuxe theme ZIP file
2. Log in to your WordPress admin dashboard
3. Go to **Appearance > Themes**
4. Click the **Add New** button at the top of the page
5. Click the **Upload Theme** button
6. Click **Choose File**, select the ZIP file you downloaded, and click **Install Now**
7. After installation, click **Activate** to activate the theme

### Required Plugins

AquaLuxe works best with the following plugins:

- **WooCommerce**: For e-commerce functionality (optional but recommended)
- **Polylang** or **WPML**: For multilingual support
- **Currency Switcher for WooCommerce**: For multi-currency support
- **Elementor** or **WPBakery Page Builder**: For easy page building (optional)

## Theme Setup

After activating the theme, you'll see a notice at the top of your dashboard recommending you to install the required and recommended plugins. You can install them directly from there.

### Demo Import

AquaLuxe comes with demo content to help you get started quickly:

1. Go to **Appearance > Import Demo Data**
2. Choose the demo you want to import
3. Click **Import Demo Data**
4. Wait for the import process to complete

## Customizer Options

AquaLuxe provides extensive customization options through the WordPress Customizer:

1. Go to **Appearance > Customize**
2. Explore the following sections:

### Site Identity

- **Logo**: Upload your site logo
- **Site Title**: Set your site title
- **Tagline**: Set your site tagline
- **Site Icon**: Upload your favicon

### Colors

- **Primary Color**: Set the primary color for buttons, links, and accents
- **Secondary Color**: Set the secondary color for highlights and secondary elements
- **Accent Color**: Set the accent color for special elements
- **Text Color**: Set the main text color
- **Heading Color**: Set the color for headings
- **Background Color**: Set the background color for the site

### Typography

- **Body Font**: Choose the font for body text
- **Heading Font**: Choose the font for headings
- **Font Sizes**: Adjust font sizes for different elements

### Layout

- **Container Width**: Set the maximum width for the content container
- **Sidebar Position**: Choose the position of the sidebar (left, right, or none)
- **Header Layout**: Choose from different header layouts
- **Footer Layout**: Choose from different footer layouts

### Header Options

- **Sticky Header**: Enable/disable sticky header
- **Transparent Header**: Enable/disable transparent header on specific pages
- **Header Elements**: Show/hide and reorder header elements

### Footer Options

- **Footer Widgets**: Choose the number of footer widget columns
- **Footer Bottom Bar**: Customize the footer bottom bar content
- **Copyright Text**: Set the copyright text

### Blog Options

- **Blog Layout**: Choose from different blog layouts
- **Post Meta**: Show/hide post meta information
- **Featured Image**: Show/hide featured images
- **Related Posts**: Show/hide related posts

### WooCommerce Options

- **Shop Layout**: Choose from different shop layouts
- **Product Layout**: Choose from different product layouts
- **Cart Layout**: Choose from different cart layouts
- **Checkout Layout**: Choose from different checkout layouts
- **My Account Layout**: Choose from different account layouts

## Homepage Setup

AquaLuxe comes with several homepage templates:

1. Go to **Pages > Add New**
2. Give your page a title (e.g., "Home")
3. In the **Page Attributes** panel, select one of the homepage templates:
   - **Template: Homepage Default**
   - **Template: Homepage Modern**
   - **Template: Homepage Classic**
   - **Template: Homepage Minimal**
4. Use the page builder to customize the content
5. Publish the page
6. Go to **Settings > Reading**
7. Select **A static page** for your homepage
8. Choose the page you just created as your homepage
9. Click **Save Changes**

## WooCommerce Integration

AquaLuxe is fully compatible with WooCommerce and provides extensive customization options for your online store.

### Setting Up Your Shop

1. Install and activate WooCommerce
2. Follow the WooCommerce setup wizard
3. Go to **WooCommerce > Settings** to configure your store

### Product Display

AquaLuxe enhances the product display with:

- **Quick View**: Customers can view product details without leaving the page
- **Wishlist**: Customers can add products to their wishlist
- **AJAX Add to Cart**: Products can be added to cart without page reload
- **Custom Tabs**: Care Guide and Shipping tabs for aquatic products

### Shop Pages

AquaLuxe provides custom templates for all WooCommerce pages:

- **Shop Page**: Displays your products in a grid or list layout
- **Product Page**: Displays product details with enhanced features
- **Cart Page**: Displays the cart with a clean, user-friendly layout
- **Checkout Page**: Streamlined checkout process
- **My Account Page**: Enhanced account dashboard

## Dark Mode

AquaLuxe includes a dark mode feature that users can toggle:

1. The dark mode toggle is located in the header
2. Users can click it to switch between light and dark mode
3. The preference is saved and remembered for future visits

### Customizing Dark Mode

You can customize the dark mode colors in the Customizer:

1. Go to **Appearance > Customize**
2. Navigate to **Colors > Dark Mode**
3. Adjust the dark mode colors to your preference

## Multilingual Support

AquaLuxe is fully compatible with popular multilingual plugins:

### Setting Up with Polylang

1. Install and activate Polylang
2. Go to **Languages > Settings**
3. Add the languages you want to support
4. Translate your content, including pages, posts, products, etc.
5. The language switcher will automatically appear in the header

### Setting Up with WPML

1. Install and activate WPML
2. Follow the WPML setup wizard
3. Add the languages you want to support
4. Translate your content using WPML's translation management
5. Configure the language switcher in **WPML > Languages > Language Switcher**

## Multi-currency Support

AquaLuxe supports multiple currencies for international sales:

### Setting Up with Currency Switcher for WooCommerce

1. Install and activate Currency Switcher for WooCommerce
2. Go to **WooCommerce > Currency Switcher**
3. Add the currencies you want to support
4. Configure exchange rates and display options
5. The currency switcher will automatically appear in the header

## Multivendor Support

AquaLuxe is compatible with popular multivendor plugins:

### Setting Up with Dokan

1. Install and activate Dokan
2. Follow the Dokan setup wizard
3. Configure vendor settings in **Dokan > Settings**

### Setting Up with WC Vendors

1. Install and activate WC Vendors
2. Go to **WC Vendors > Settings**
3. Configure vendor settings

## Frequently Asked Questions

### How do I update the theme?

1. Download the latest version of the theme
2. Go to **Appearance > Themes**
3. Click on the AquaLuxe theme
4. Click **Theme Details**
5. Click **Update** and select the new ZIP file

### How do I get support?

For theme support, please contact us at support@example.com or visit our [support forum](https://example.com/support).

### How do I customize the theme beyond the Customizer options?

For advanced customization, you can use a child theme:

1. Create a new folder named `aqualuxe-child` in your `wp-content/themes` directory
2. Create a `style.css` file in the child theme folder with the following content:

```css
/*
Theme Name: AquaLuxe Child
Theme URI: https://example.com/aqualuxe-child/
Description: Child theme for AquaLuxe
Author: Your Name
Author URI: https://example.com/
Template: aqualuxe-theme
Version: 1.0.0
*/

/* Add your custom CSS here */
```

3. Create a `functions.php` file in the child theme folder with the following content:

```php
<?php
/**
 * AquaLuxe Child Theme functions and definitions
 */

// Enqueue parent and child theme styles
function aqualuxe_child_enqueue_styles() {
    wp_enqueue_style('aqualuxe-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('aqualuxe-child-style', get_stylesheet_uri(), array('aqualuxe-style'));
}
add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles');

// Add your custom functions here
```

4. Activate the child theme in **Appearance > Themes**

### How do I add custom code snippets?

For small code snippets, you can use the **Customizer > Additional CSS** section. For more extensive customizations, use a child theme or a functionality plugin.