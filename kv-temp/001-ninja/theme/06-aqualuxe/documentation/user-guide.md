# AquaLuxe WordPress Theme User Guide

## Table of Contents

1. [Introduction](#introduction)
2. [Installation](#installation)
3. [Theme Setup](#theme-setup)
4. [Customization](#customization)
5. [WooCommerce Integration](#woocommerce-integration)
6. [Multi-Currency Support](#multi-currency-support)
7. [International Shipping](#international-shipping)
8. [Fish Species Custom Post Type](#fish-species-custom-post-type)
9. [Performance Optimization](#performance-optimization)
10. [Security Features](#security-features)
11. [Troubleshooting](#troubleshooting)
12. [Support](#support)

## Introduction

AquaLuxe is a premium WordPress theme designed specifically for ornamental fish farming businesses. It features a clean, modern design with full WooCommerce integration, making it perfect for showcasing and selling your aquatic products online.

### Key Features

- Responsive design that looks great on all devices
- Full WooCommerce integration with custom styling
- Multi-currency support for international sales
- International shipping optimization
- Custom post type for fish species/collections
- Advanced filtering system for products
- Quick view and wishlist functionality
- Performance optimized for fast loading
- Enhanced security features
- SEO-friendly structure with schema.org markup

## Installation

### Requirements

- WordPress 5.6 or higher
- PHP 7.4 or higher
- WooCommerce 5.0 or higher (if you plan to use the shop features)

### Installation Steps

1. Log in to your WordPress admin dashboard
2. Go to Appearance > Themes
3. Click "Add New" and then "Upload Theme"
4. Choose the aqualuxe.zip file and click "Install Now"
5. After installation is complete, click "Activate"

## Theme Setup

### Initial Setup

After activating the theme, you'll see a notice recommending the installation of required and optional plugins. We recommend installing all suggested plugins for the best experience.

### Setting Up Menus

1. Go to Appearance > Menus
2. Create a new menu or edit an existing one
3. Add pages, categories, or custom links to your menu
4. Under "Menu Settings," select the location where you want the menu to appear (Primary Menu, Secondary Menu, or Footer Menu)
5. Click "Save Menu"

### Setting Up Widgets

1. Go to Appearance > Widgets
2. Drag and drop widgets to the available widget areas:
   - Sidebar
   - Footer 1
   - Footer 2
   - Footer 3
   - Footer 4
   - Shop Sidebar (if WooCommerce is active)

## Customization

### Theme Customizer

AquaLuxe comes with extensive customization options available through the WordPress Customizer:

1. Go to Appearance > Customize
2. Explore the following sections:
   - General Settings
   - Header
   - Footer
   - Typography
   - Colors
   - Blog
   - WooCommerce (if active)

### General Settings

- **Layout**: Adjust container width and layout settings
- **Performance**: Enable/disable performance optimization features

### Header Settings

- **Layout**: Choose header layout style
- **Navigation**: Customize menu appearance and behavior

### Footer Settings

- **Layout**: Select footer layout and column configuration
- **Widgets**: Configure footer widget areas
- **Copyright**: Edit copyright text and appearance

### Typography Settings

- **General**: Set base font family and sizes
- **Headings**: Customize heading font family and sizes

### Colors Settings

- **General**: Set primary and secondary colors
- **Header**: Customize header background and text colors
- **Footer**: Adjust footer background and text colors

### Blog Settings

- **Archive**: Configure blog listing page layout
- **Single Post**: Customize single post display options

## WooCommerce Integration

AquaLuxe is fully compatible with WooCommerce and includes custom styling and enhanced features.

### Shop Settings

1. Go to Appearance > Customize > WooCommerce > Shop
2. Configure the following options:
   - Shop Layout (Right Sidebar, Left Sidebar, or Full Width)
   - Number of products per row
   - Number of products per page
   - Product filter options

### Product Settings

1. Go to Appearance > Customize > WooCommerce > Product
2. Customize the following:
   - Product page layout
   - Gallery options
   - Related products display
   - Upsell products display

### Cart & Checkout Settings

1. Go to Appearance > Customize > WooCommerce > Cart/Checkout
2. Configure cart and checkout page layouts and features

### Additional WooCommerce Features

- **Quick View**: Allow customers to preview products without leaving the shop page
- **Wishlist**: Let customers save products to a wishlist for later
- **AJAX Add to Cart**: Add products to cart without page refresh
- **Advanced Filtering**: Help customers find products with advanced filtering options

## Multi-Currency Support

AquaLuxe includes built-in multi-currency support, allowing you to sell your products in different currencies.

### Enabling Multi-Currency Support

1. Go to Appearance > Customize > WooCommerce > Features
2. Check "Enable Multi-Currency Support"
3. Select the currencies you want to support
4. Set exchange rates for each currency

### Currency Switcher

Once enabled, a currency switcher will appear on:
- Shop pages
- Product pages
- Cart page
- Checkout page

Customers can select their preferred currency, and all prices will be automatically converted based on the exchange rates you've set.

## International Shipping

AquaLuxe includes features to optimize international shipping for your global customers.

### Enabling International Shipping

1. Go to Appearance > Customize > WooCommerce > Features
2. Check "Enable International Shipping Optimization"
3. Configure shipping zones and rates

### Shipping Zones

The theme includes predefined shipping zones:
- Europe
- North America
- Asia Pacific
- Rest of World

For each zone, you can set:
- Rate adjustment (multiplier for base shipping rates)
- Estimated delivery time

### Shipping Information

When international shipping is enabled, customers will see:
- Estimated delivery times on the checkout page
- Shipping zone information
- International shipping notices

## Fish Species Custom Post Type

AquaLuxe includes a custom post type for fish species, allowing you to showcase your fish collections separately from your products.

### Adding Fish Species

1. Go to Fish Species > Add New
2. Enter the fish species name as the title
3. Add a description in the main content area
4. Set categories and tags as needed
5. Add featured image and gallery images
6. Fill in custom fields:
   - Scientific Name
   - Origin
   - Size
   - Temperature Range
   - pH Range
   - Care Level
   - Diet
   - Lifespan
   - Breeding
7. Click "Publish"

### Displaying Fish Species

Fish species are automatically displayed in the Fish Species archive page. You can also:
- Add a fish species to a product as related information
- Create a custom page to showcase specific fish species
- Use the [fish_species] shortcode to display fish species in any page or post

## Performance Optimization

AquaLuxe includes built-in performance optimization features to ensure your site loads quickly.

### Performance Features

- **Asset Minification**: CSS and JavaScript files are minified to reduce file size
- **Image Optimization**: Images are automatically optimized and lazy-loaded
- **Caching**: Browser caching headers are added for better performance
- **Critical CSS**: Above-the-fold CSS is inlined for faster initial rendering

### Enabling Performance Features

Performance features are enabled by default. You can configure them in:
1. Go to Appearance > Customize > General Settings > Performance
2. Enable/disable specific performance features as needed

## Security Features

AquaLuxe includes enhanced security features to protect your website.

### Security Features

- **Input Sanitization**: All user inputs are properly sanitized
- **Data Escaping**: All outputs are properly escaped
- **Nonce Verification**: Forms and AJAX requests include nonce verification
- **Secure Cookies**: Cookies are secured when SSL is enabled
- **Security Headers**: HTTP security headers are added to protect against common attacks

## Troubleshooting

### Common Issues

#### Theme not displaying correctly
- Make sure you have the latest version of WordPress and the theme
- Check if all required plugins are installed and activated
- Try switching to a default WordPress theme to see if the issue persists
- Check your browser console for JavaScript errors

#### WooCommerce features not working
- Make sure WooCommerce is installed and activated
- Check if the theme's WooCommerce integration is enabled
- Verify that you're using a compatible version of WooCommerce

#### Performance issues
- Check if all performance optimization features are enabled
- Consider using a caching plugin for additional performance improvements
- Optimize your images before uploading them
- Check with your hosting provider for server-side optimizations

## Support

If you need help with the AquaLuxe theme, please contact our support team:

- **Email**: support@aqualuxetheme.com
- **Website**: https://aqualuxetheme.com/support
- **Documentation**: https://aqualuxetheme.com/docs

Our support team is available Monday through Friday, 9 AM to 5 PM EST.