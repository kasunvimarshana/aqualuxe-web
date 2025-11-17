# AquaLuxe WordPress Theme Installation Guide

## Table of Contents

1. [Requirements](#requirements)
2. [Installation](#installation)
3. [Plugin Installation](#plugin-installation)
4. [Demo Content Import](#demo-content-import)
5. [Theme Setup](#theme-setup)
6. [WooCommerce Setup](#woocommerce-setup)
7. [Multi-Currency Setup](#multi-currency-setup)
8. [International Shipping Setup](#international-shipping-setup)
9. [Performance Optimization](#performance-optimization)
10. [Troubleshooting](#troubleshooting)

## Requirements

Before installing the AquaLuxe theme, ensure your hosting environment meets the following requirements:

- WordPress 5.6 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher
- WooCommerce 5.0 or higher (if you plan to use the shop features)
- Memory limit of at least 128MB
- Max execution time of at least 120 seconds
- Post max size of at least 32MB
- Upload max filesize of at least 32MB

## Installation

### Standard Installation

1. Log in to your WordPress admin dashboard
2. Go to **Appearance > Themes**
3. Click **Add New** and then **Upload Theme**
4. Choose the `aqualuxe.zip` file and click **Install Now**
5. After installation is complete, click **Activate**

### Manual Installation

If you encounter issues with the standard installation method, you can install the theme manually:

1. Extract the `aqualuxe.zip` file to your computer
2. Using an FTP client (like FileZilla), connect to your web server
3. Navigate to the `/wp-content/themes/` directory
4. Upload the extracted `aqualuxe` folder to this directory
5. Log in to your WordPress admin dashboard
6. Go to **Appearance > Themes**
7. Find the AquaLuxe theme and click **Activate**

## Plugin Installation

AquaLuxe works best with the following plugins:

### Required Plugins

- **WooCommerce**: For e-commerce functionality
- **Contact Form 7**: For contact forms
- **Yoast SEO**: For search engine optimization

### Recommended Plugins

- **WP Rocket**: For additional performance optimization
- **Wordfence Security**: For enhanced security
- **UpdraftPlus**: For backups
- **WooCommerce Currency Switcher**: For advanced multi-currency features

### Installing Plugins

After activating the theme, you'll see a notice recommending the installation of required and recommended plugins:

1. Click on **Begin installing plugins**
2. Select all the plugins you want to install
3. Choose **Install** from the dropdown menu
4. Click **Apply**
5. After installation, activate all plugins

Alternatively, you can install plugins manually:

1. Go to **Plugins > Add New**
2. Search for the plugin name
3. Click **Install Now**
4. After installation, click **Activate**

## Demo Content Import

AquaLuxe comes with demo content to help you get started quickly:

1. Go to **Appearance > Import Demo Data**
2. Choose the demo you want to import (e.g., "AquaLuxe Main Demo")
3. Click **Import Demo**
4. Wait for the import process to complete

The import process will add:

- Sample pages (Home, About, Contact, etc.)
- Sample blog posts
- Sample products
- Sample fish species
- Menu structure
- Widget configurations
- Customizer settings

## Theme Setup

### General Settings

1. Go to **Appearance > Customize**
2. Navigate to **General Settings**
3. Configure the following:
   - Layout options
   - Container width
   - Performance settings
4. Click **Publish** to save changes

### Header Setup

1. Go to **Appearance > Customize**
2. Navigate to **Header**
3. Configure the following:
   - Header layout
   - Logo and site title
   - Navigation menu style
   - Sticky header options
4. Click **Publish** to save changes

### Footer Setup

1. Go to **Appearance > Customize**
2. Navigate to **Footer**
3. Configure the following:
   - Footer layout
   - Widget areas
   - Copyright text
4. Click **Publish** to save changes

### Menu Setup

1. Go to **Appearance > Menus**
2. Create a new menu or edit an existing one
3. Add pages, categories, or custom links to your menu
4. Under "Menu Settings," select the location where you want the menu to appear:
   - Primary Menu
   - Secondary Menu
   - Footer Menu
5. Click **Save Menu**

### Widget Setup

1. Go to **Appearance > Widgets**
2. Drag and drop widgets to the available widget areas:
   - Sidebar
   - Footer 1
   - Footer 2
   - Footer 3
   - Footer 4
   - Shop Sidebar (if WooCommerce is active)

## WooCommerce Setup

### Basic WooCommerce Setup

1. Go to **WooCommerce > Settings**
2. Configure the following:
   - General options
   - Products
   - Tax
   - Shipping
   - Payments
   - Accounts & Privacy
   - Emails

### AquaLuxe WooCommerce Settings

1. Go to **Appearance > Customize**
2. Navigate to **WooCommerce**
3. Configure the following:
   - Shop layout
   - Product display
   - Cart and checkout
   - Additional features

### Product Categories

1. Go to **Products > Categories**
2. Add product categories relevant to your fish business
3. For each category:
   - Add a name
   - Add a description
   - Upload a category image
   - Set a parent category (if applicable)

### Adding Products

1. Go to **Products > Add New**
2. Enter product details:
   - Title
   - Description
   - Short description
   - Regular price
   - Sale price (if applicable)
   - Product categories
   - Product tags
   - Product image and gallery
3. Configure product data:
   - Product type (Simple, Variable, etc.)
   - Inventory
   - Shipping
   - Linked products
   - Attributes
4. Click **Publish**

## Multi-Currency Setup

AquaLuxe includes built-in multi-currency support:

1. Go to **Appearance > Customize**
2. Navigate to **WooCommerce > Features**
3. Check **Enable Multi-Currency Support**
4. Select the currencies you want to support
5. Set exchange rates for each currency
6. Click **Publish** to save changes

### Advanced Multi-Currency Setup

For more advanced multi-currency features, you can use the WooCommerce Currency Switcher plugin:

1. Install and activate the WooCommerce Currency Switcher plugin
2. Go to **WooCommerce > Currency Switcher**
3. Configure the plugin settings
4. AquaLuxe will automatically integrate with the plugin

## International Shipping Setup

AquaLuxe includes features to optimize international shipping:

1. Go to **Appearance > Customize**
2. Navigate to **WooCommerce > Features**
3. Check **Enable International Shipping Optimization**
4. Configure shipping zones and rates
5. Click **Publish** to save changes

### Shipping Zones Configuration

1. Go to **WooCommerce > International Shipping**
2. Configure each shipping zone:
   - Europe
   - North America
   - Asia Pacific
   - Rest of World
3. For each zone, set:
   - Rate adjustment (multiplier for base shipping rates)
   - Estimated delivery time
   - Countries included in the zone

## Performance Optimization

AquaLuxe includes built-in performance optimization features:

1. Go to **Appearance > Customize**
2. Navigate to **General Settings > Performance**
3. Configure the following:
   - Asset minification
   - Image optimization
   - Lazy loading
   - Browser caching
   - Critical CSS
4. Click **Publish** to save changes

### Additional Performance Optimization

For additional performance optimization, we recommend using the WP Rocket plugin:

1. Install and activate the WP Rocket plugin
2. Go to **Settings > WP Rocket**
3. Configure the plugin settings
4. AquaLuxe is already compatible with WP Rocket and will work seamlessly with it

## Troubleshooting

### Common Installation Issues

#### Theme Installation Fails

**Issue**: The theme installation fails with an error message.

**Solution**:
1. Check if your server meets the requirements
2. Increase PHP memory limit in wp-config.php: `define('WP_MEMORY_LIMIT', '256M');`
3. Try the manual installation method

#### Plugin Installation Fails

**Issue**: Plugin installation fails with an error message.

**Solution**:
1. Check if your server meets the requirements
2. Increase PHP memory limit
3. Install plugins manually one by one

#### Demo Import Fails

**Issue**: Demo import fails or times out.

**Solution**:
1. Increase PHP max execution time in wp-config.php: `set_time_limit(300);`
2. Increase PHP memory limit
3. Import demo content in parts (pages first, then posts, then products)

### Theme Customization Issues

#### Customizer Not Saving Changes

**Issue**: Changes made in the Customizer are not being saved.

**Solution**:
1. Check if your browser has cookies enabled
2. Clear browser cache and cookies
3. Try a different browser

#### Layout Issues

**Issue**: Theme layout appears broken or different from the demo.

**Solution**:
1. Check if all required plugins are activated
2. Clear browser cache
3. Check for JavaScript errors in the browser console
4. Make sure your content matches the demo content structure

### WooCommerce Issues

#### Products Not Displaying Correctly

**Issue**: Products are not displaying correctly in the shop.

**Solution**:
1. Go to **WooCommerce > Status > Tools**
2. Click **Clear product transients**
3. Regenerate product thumbnails

#### Multi-Currency Not Working

**Issue**: Multi-currency features are not working correctly.

**Solution**:
1. Make sure multi-currency support is enabled in the Customizer
2. Check if exchange rates are set correctly
3. Clear browser cookies and cache
4. Test in a private/incognito browser window

### Getting Support

If you encounter any issues not covered in this guide, please contact our support team:

- **Email**: support@aqualuxetheme.com
- **Website**: https://aqualuxetheme.com/support
- **Documentation**: https://aqualuxetheme.com/docs

Our support team is available Monday through Friday, 9 AM to 5 PM EST.