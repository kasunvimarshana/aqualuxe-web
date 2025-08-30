# AquaLuxe WordPress Theme - User Guide

## Table of Contents
1. [Introduction](#introduction)
2. [Installation](#installation)
3. [Theme Setup](#theme-setup)
4. [Customizing Your Site](#customizing-your-site)
5. [Header Options](#header-options)
6. [Footer Options](#footer-options)
7. [Homepage Setup](#homepage-setup)
8. [Blog Configuration](#blog-configuration)
9. [WooCommerce Setup](#woocommerce-setup)
10. [Product Management](#product-management)
11. [Multilingual Setup](#multilingual-setup)
12. [Multivendor Setup](#multivendor-setup)
13. [Performance Optimization](#performance-optimization)
14. [Troubleshooting](#troubleshooting)
15. [Support](#support)

## Introduction

AquaLuxe is a premium WordPress theme designed specifically for aquatic-related e-commerce websites. It features a dual-state architecture that works seamlessly with or without WooCommerce enabled. The theme supports multilingual, multi-currency, multivendor, and multitenant functionality.

### Key Features

- **Responsive Design**: Looks great on all devices from mobile to desktop
- **WooCommerce Integration**: Full support for WooCommerce with custom templates
- **Multilingual Support**: Compatible with WPML and Polylang
- **Multi-Currency Support**: Sell in multiple currencies
- **Multivendor Support**: Create a marketplace with multiple sellers
- **Dark Mode**: Built-in dark mode toggle
- **Customization Options**: Extensive theme customizer options
- **Performance Optimized**: Fast loading times and optimized code

## Installation

### Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher
- WooCommerce 6.0 or higher (optional but recommended)

### Installation Steps

1. **Upload the Theme**:
   - Go to your WordPress admin panel
   - Navigate to Appearance > Themes
   - Click "Add New"
   - Click "Upload Theme"
   - Choose the `aqualuxe.zip` file
   - Click "Install Now"

2. **Activate the Theme**:
   - After installation, click "Activate"

3. **Install Required Plugins**:
   - After activation, you'll be prompted to install recommended plugins
   - Install and activate the following plugins:
     - WooCommerce (optional but recommended)
     - Elementor (optional)
     - Contact Form 7 (optional)
     - Yoast SEO (optional)

4. **Import Demo Content** (optional):
   - Go to Appearance > AquaLuxe Setup
   - Click on "Import Demo Content"
   - Choose the demo you want to import
   - Click "Import"

## Theme Setup

### Initial Setup Wizard

AquaLuxe includes a setup wizard to help you configure the theme:

1. Go to Appearance > AquaLuxe Setup
2. Follow the steps in the wizard:
   - Welcome screen
   - Plugin installation
   - Demo content import
   - Theme options
   - Finalization

### Manual Setup

If you prefer to set up the theme manually:

1. **Configure General Settings**:
   - Go to Settings > General
   - Set your site title, tagline, and timezone

2. **Set Up Menus**:
   - Go to Appearance > Menus
   - Create a new menu
   - Add pages, categories, or custom links
   - Assign the menu to a location (Primary Menu, Footer Menu, etc.)

3. **Configure Widgets**:
   - Go to Appearance > Widgets
   - Add widgets to the available widget areas (Sidebar, Footer 1-4, etc.)

4. **Set Up Homepage**:
   - Create a new page or edit an existing one
   - Set it as your homepage in Settings > Reading

## Customizing Your Site

AquaLuxe provides extensive customization options through the WordPress Customizer:

1. Go to Appearance > Customize
2. Explore the available sections:

### Theme Options Panel

The AquaLuxe Theme Options panel includes the following sections:

#### Colors

- **Primary Color**: The main color used throughout the site
- **Secondary Color**: The secondary accent color
- **Body Background Color**: The background color of the site
- **Text Color**: The default text color
- **Dark Mode Colors**: Colors used when dark mode is active

#### Header

- **Top Bar**: Enable/disable the top bar and configure its content
- **Header Style**: Choose from different header layouts
- **Sticky Header**: Enable/disable the sticky header
- **Dark Mode Toggle**: Enable/disable the dark mode toggle
- **Contact Information**: Add phone number and email address
- **Breadcrumbs**: Enable/disable breadcrumbs

#### Footer

- **Footer Style**: Choose from different footer layouts
- **Widget Areas**: Configure the number of widget areas
- **Copyright Text**: Customize the copyright text
- **Payment Icons**: Display payment method icons
- **Social Icons**: Display social media icons
- **Back to Top Button**: Enable/disable the back to top button
- **Newsletter Form**: Add a newsletter signup form

#### Layout

- **Container Width**: Set the maximum width of the content container
- **Sidebar Position**: Choose the position of the sidebar (left, right, none)
- **Sidebar Width**: Set the width of the sidebar
- **Page Layout**: Choose the default layout for pages
- **Blog Layout**: Choose the layout for the blog
- **Archive Layout**: Choose the layout for archives

#### Typography

- **Body Font**: Choose the font for body text
- **Heading Font**: Choose the font for headings
- **Base Font Size**: Set the base font size
- **Line Height**: Set the default line height
- **Font Weight**: Choose the font weight for body text and headings

#### Social Media

- **Social Profiles**: Add links to your social media profiles
- **Display Location**: Choose where to display social icons

#### WooCommerce

- **Shop Layout**: Choose the layout for the shop page
- **Products Per Row**: Set the number of products per row
- **Products Per Page**: Set the number of products per page
- **Shop Sidebar**: Configure the shop sidebar
- **Product Page Sidebar**: Configure the product page sidebar
- **Quick View**: Enable/disable product quick view
- **Wishlist**: Enable/disable wishlist functionality
- **AJAX Cart**: Enable/disable AJAX cart
- **Cart Drawer**: Enable/disable the cart drawer
- **Product Gallery**: Configure product gallery options
- **Related Products**: Configure related products display
- **Multi-Currency**: Enable/disable multi-currency support
- **Vendor Display**: Configure vendor information display

#### Blog

- **Blog Sidebar**: Configure the blog sidebar
- **Featured Image**: Configure featured image display
- **Excerpt Length**: Set the length of post excerpts
- **Meta Information**: Choose which meta information to display
- **Related Posts**: Enable/disable related posts
- **Author Bio**: Enable/disable author bio
- **Post Navigation**: Enable/disable post navigation
- **Social Sharing**: Enable/disable social sharing buttons

#### Performance

- **Lazy Loading**: Enable/disable lazy loading for images
- **Preload Fonts**: Enable/disable font preloading
- **Minify CSS**: Enable/disable CSS minification
- **Minify JavaScript**: Enable/disable JavaScript minification
- **Defer JavaScript**: Enable/disable JavaScript deferring
- **Preconnect**: Enable/disable preconnect for external resources

## Header Options

### Header Styles

AquaLuxe offers several header styles:

1. **Default**: Standard header with logo on the left and menu on the right
2. **Centered Logo**: Logo in the center with menu below
3. **Transparent**: Header with transparent background
4. **Minimal**: Simplified header with minimal elements

To change the header style:

1. Go to Appearance > Customize
2. Navigate to AquaLuxe Theme Options > Header
3. Select your preferred header style from the "Header Style" dropdown

### Top Bar

The top bar appears above the main header and can display contact information, social icons, and a secondary menu:

1. Go to Appearance > Customize
2. Navigate to AquaLuxe Theme Options > Header
3. Enable/disable the top bar
4. Configure the top bar content:
   - Contact information (phone, email)
   - Social icons
   - Secondary menu

### Navigation Menus

AquaLuxe supports multiple menu locations:

1. **Primary Menu**: Main navigation menu
2. **Top Bar Menu**: Secondary menu in the top bar
3. **Footer Menu**: Menu in the footer
4. **Mobile Menu**: Menu for mobile devices

To set up menus:

1. Go to Appearance > Menus
2. Create a new menu or edit an existing one
3. Add items to the menu
4. Under "Menu Settings", check the locations where you want the menu to appear
5. Click "Save Menu"

## Footer Options

### Footer Styles

AquaLuxe offers several footer styles:

1. **Default (4 Columns)**: Four widget areas
2. **3 Columns**: Three widget areas
3. **2 Columns**: Two widget areas
4. **Minimal**: Simplified footer with minimal elements

To change the footer style:

1. Go to Appearance > Customize
2. Navigate to AquaLuxe Theme Options > Footer
3. Select your preferred footer style from the "Footer Style" dropdown

### Footer Widgets

To add widgets to the footer:

1. Go to Appearance > Widgets
2. Drag and drop widgets to the Footer 1-4 widget areas
3. Configure each widget as needed

### Footer Elements

Additional footer elements can be configured:

1. **Copyright Text**: Customize the copyright text (use {year} to display the current year)
2. **Payment Icons**: Display payment method icons
3. **Social Icons**: Display social media icons
4. **Back to Top Button**: Add a button to scroll back to the top of the page
5. **Newsletter Form**: Add a newsletter signup form

## Homepage Setup

### Using the Homepage Template

AquaLuxe includes a dedicated homepage template:

1. Create a new page or edit an existing one
2. In the Page Attributes meta box, select "Homepage Template"
3. Set this page as your homepage in Settings > Reading

### Using Elementor

For more advanced homepage layouts, you can use Elementor:

1. Install and activate the Elementor plugin
2. Create a new page or edit an existing one
3. Click "Edit with Elementor"
4. Use Elementor's drag-and-drop interface to build your homepage
5. Set this page as your homepage in Settings > Reading

### Homepage Sections

Typical homepage sections for an aquatic e-commerce site:

1. **Hero Section**: Large banner with a call-to-action
2. **Featured Categories**: Showcase your main product categories
3. **Featured Products**: Display your best-selling or featured products
4. **About Section**: Brief information about your store
5. **Testimonials**: Customer reviews and testimonials
6. **Latest Blog Posts**: Recent articles from your blog
7. **Newsletter Signup**: Form to collect email addresses
8. **Instagram Feed**: Display your latest Instagram posts

## Blog Configuration

### Blog Layout

AquaLuxe offers several blog layouts:

1. **Standard**: Traditional blog layout with featured images above content
2. **Grid**: Grid layout with equal-sized cards
3. **Masonry**: Grid layout with variable heights
4. **List**: Compact list layout

To change the blog layout:

1. Go to Appearance > Customize
2. Navigate to AquaLuxe Theme Options > Layout
3. Select your preferred layout from the "Blog Layout" dropdown

### Blog Sidebar

To configure the blog sidebar:

1. Go to Appearance > Customize
2. Navigate to AquaLuxe Theme Options > Blog
3. Select the sidebar position (left, right, none)
4. Go to Appearance > Widgets
5. Add widgets to the Sidebar widget area

### Featured Images

To configure featured images:

1. Go to Appearance > Customize
2. Navigate to AquaLuxe Theme Options > Blog
3. Select the featured image size and position

### Meta Information

To configure which meta information is displayed:

1. Go to Appearance > Customize
2. Navigate to AquaLuxe Theme Options > Blog
3. Enable/disable the following options:
   - Post date
   - Author
   - Categories
   - Tags
   - Comments count

### Related Posts

To enable/disable related posts:

1. Go to Appearance > Customize
2. Navigate to AquaLuxe Theme Options > Blog
3. Enable/disable "Show Related Posts"

## WooCommerce Setup

### Basic WooCommerce Setup

1. Install and activate the WooCommerce plugin
2. Follow the WooCommerce setup wizard
3. Configure the following settings:
   - Store address
   - Currency
   - Payment methods
   - Shipping methods
   - Tax options

### Shop Page Configuration

To configure the shop page:

1. Go to Appearance > Customize
2. Navigate to AquaLuxe Theme Options > WooCommerce
3. Configure the following options:
   - Shop layout
   - Products per row
   - Products per page
   - Shop sidebar position

### Product Page Configuration

To configure product pages:

1. Go to Appearance > Customize
2. Navigate to AquaLuxe Theme Options > WooCommerce
3. Configure the following options:
   - Product page sidebar
   - Product gallery options
   - Related products
   - Product tabs

### Enhanced WooCommerce Features

AquaLuxe includes several enhanced WooCommerce features:

#### Quick View

Allows customers to preview products without leaving the current page:

1. Go to Appearance > Customize
2. Navigate to AquaLuxe Theme Options > WooCommerce
3. Enable "Quick View"

#### Wishlist

Allows customers to save products for later:

1. Go to Appearance > Customize
2. Navigate to AquaLuxe Theme Options > WooCommerce
3. Enable "Wishlist"

#### AJAX Cart

Updates the cart without page reload:

1. Go to Appearance > Customize
2. Navigate to AquaLuxe Theme Options > WooCommerce
3. Enable "AJAX Cart"

#### Cart Drawer

Shows a slide-in cart when products are added:

1. Go to Appearance > Customize
2. Navigate to AquaLuxe Theme Options > WooCommerce
3. Enable "Cart Drawer"

## Product Management

### Adding Products

To add a new product:

1. Go to Products > Add New
2. Enter the product title and description
3. Set the product price
4. Add product images
5. Select product categories and tags
6. Configure product data (simple, variable, etc.)
7. Click "Publish"

### Product Types

AquaLuxe supports all standard WooCommerce product types:

1. **Simple Product**: Standard product with no options
2. **Variable Product**: Product with variations (size, color, etc.)
3. **Grouped Product**: Collection of related products
4. **External/Affiliate Product**: Link to a product on another site

Additionally, AquaLuxe adds a custom product type for aquatic businesses:

5. **Live Fish**: Special product type with additional fields for fish species

### Product Categories

To manage product categories:

1. Go to Products > Categories
2. Add new categories or edit existing ones
3. Set category images and descriptions

### Product Attributes

To manage product attributes:

1. Go to Products > Attributes
2. Add new attributes (e.g., size, color, species)
3. Add terms to each attribute

### Product Tags

To manage product tags:

1. Go to Products > Tags
2. Add new tags or edit existing ones

## Multilingual Setup

AquaLuxe supports multilingual websites through WPML or Polylang:

### WPML Setup

1. Install and activate the WPML plugin
2. Follow the WPML setup wizard
3. Configure languages and translation options
4. Translate your content:
   - Pages
   - Posts
   - Products
   - Categories
   - Menus
   - Theme strings

### Polylang Setup

1. Install and activate the Polylang plugin
2. Add your languages in Settings > Languages
3. Configure language options
4. Translate your content:
   - Pages
   - Posts
   - Products
   - Categories
   - Menus
   - Theme strings

### Language Switcher

To add a language switcher:

1. Go to Appearance > Widgets
2. Add the "Language Switcher" widget to a widget area
3. Configure the widget options

Alternatively, you can add the language switcher to a menu:

1. Go to Appearance > Menus
2. Check "Languages" in the "Screen Options" at the top
3. Add language items to your menu

## Multivendor Setup

AquaLuxe supports multivendor marketplaces through compatible plugins:

### Compatible Plugins

- WC Vendors
- Dokan
- WCFM Marketplace
- WooCommerce Product Vendors

### Basic Setup

1. Install and activate your preferred multivendor plugin
2. Follow the plugin's setup wizard
3. Configure vendor settings:
   - Commission rates
   - Vendor registration
   - Vendor dashboard
   - Payment methods

### Vendor Display

AquaLuxe automatically displays vendor information on product pages:

1. Go to Appearance > Customize
2. Navigate to AquaLuxe Theme Options > WooCommerce
3. Enable "Show Vendor Information"

## Performance Optimization

AquaLuxe includes several performance optimization options:

### Image Optimization

1. Go to Appearance > Customize
2. Navigate to AquaLuxe Theme Options > Performance
3. Enable "Lazy Loading for Images"

### CSS and JavaScript Optimization

1. Go to Appearance > Customize
2. Navigate to AquaLuxe Theme Options > Performance
3. Enable the following options:
   - Minify CSS
   - Minify JavaScript
   - Defer JavaScript

### Font Optimization

1. Go to Appearance > Customize
2. Navigate to AquaLuxe Theme Options > Performance
3. Enable "Preload Fonts"

### External Resource Optimization

1. Go to Appearance > Customize
2. Navigate to AquaLuxe Theme Options > Performance
3. Enable "Preconnect for External Resources"

## Troubleshooting

### Common Issues and Solutions

#### Theme Not Working After Update

1. Clear your browser cache
2. Clear any caching plugins
3. Check for plugin conflicts by deactivating all plugins and reactivating them one by one

#### WooCommerce Templates Not Working

1. Go to WooCommerce > Status
2. Check the "Templates" tab for any outdated templates
3. Update any outdated templates

#### Mobile Menu Not Working

1. Check if you have assigned a menu to the "Mobile Menu" location
2. Clear your browser cache
3. Check for JavaScript errors in the browser console

#### Customizer Changes Not Showing

1. Clear your browser cache
2. Try a different browser
3. Check if you have a caching plugin active and clear its cache

#### Products Not Displaying Correctly

1. Go to WooCommerce > Status
2. Run the "Tools" to clear product caches
3. Regenerate product thumbnails

### Getting Support

If you encounter any issues not covered in this guide, please contact our support team:

1. Visit our support portal at [support.aqualuxe.com](https://support.aqualuxe.com)
2. Submit a support ticket with details of your issue
3. Include screenshots and steps to reproduce the issue

## Support

### Documentation

- User Guide: [docs.aqualuxe.com/user-guide](https://docs.aqualuxe.com/user-guide)
- Developer Documentation: [docs.aqualuxe.com/developer](https://docs.aqualuxe.com/developer)
- Video Tutorials: [youtube.com/aqualuxe](https://youtube.com/aqualuxe)

### Support Channels

- Support Portal: [support.aqualuxe.com](https://support.aqualuxe.com)
- Email Support: [support@aqualuxe.com](mailto:support@aqualuxe.com)
- Community Forum: [forum.aqualuxe.com](https://forum.aqualuxe.com)

### Updates

AquaLuxe is regularly updated with new features and improvements. To update the theme:

1. Go to Appearance > Themes
2. If an update is available, you'll see a notification
3. Click "Update Now"

Alternatively, you can update manually:

1. Download the latest version from your account
2. Go to Appearance > Themes
3. Click "Add New"
4. Click "Upload Theme"
5. Choose the downloaded zip file
6. Click "Install Now"
7. Click "Activate"

### Changelog

For a complete list of changes in each version, please refer to the changelog:
[docs.aqualuxe.com/changelog](https://docs.aqualuxe.com/changelog)