# AquaLuxe WordPress Theme - Installation Guide

This guide provides step-by-step instructions for installing and setting up the AquaLuxe WordPress theme.

## Table of Contents

1. [Requirements](#requirements)
2. [Installation](#installation)
3. [Theme Setup](#theme-setup)
4. [Demo Content Import](#demo-content-import)
5. [WooCommerce Setup](#woocommerce-setup)
6. [Theme Customization](#theme-customization)
7. [Development Environment](#development-environment)

## Requirements

Before installing the AquaLuxe theme, ensure your environment meets these requirements:

- WordPress 6.0 or higher
- PHP 7.4 or higher
- MySQL 5.7 or higher
- WooCommerce 7.0 or higher (if using e-commerce features)
- Node.js 18.0.0 or higher (for development only)
- npm 8.0.0 or higher (for development only)

## Installation

### Method 1: WordPress Admin Dashboard

1. Log in to your WordPress admin dashboard
2. Navigate to **Appearance > Themes**
3. Click **Add New**
4. Click **Upload Theme**
5. Choose the `aqualuxe.zip` file
6. Click **Install Now**
7. After installation completes, click **Activate**

### Method 2: FTP Upload

1. Extract the `aqualuxe.zip` file on your computer
2. Using an FTP client, connect to your web server
3. Navigate to the `/wp-content/themes/` directory
4. Upload the `aqualuxe` folder to this directory
5. Log in to your WordPress admin dashboard
6. Navigate to **Appearance > Themes**
7. Find AquaLuxe and click **Activate**

### Method 3: WP-CLI (Advanced)

If you have WP-CLI installed, you can install the theme using the command line:

```bash
wp theme install path/to/aqualuxe.zip --activate
```

## Theme Setup

After activating the theme, follow these steps to set up the theme:

1. **Install Required Plugins**
   - Upon activation, you'll be prompted to install required plugins
   - Click **Begin Installing Plugins**
   - Select all recommended plugins and click **Install**
   - After installation, activate all plugins

2. **Configure Theme Settings**
   - Navigate to **Appearance > Customize**
   - Configure the following sections:
     - Site Identity (logo, site title, favicon)
     - Colors & Typography
     - Header Options
     - Footer Options
     - Layout Settings
     - Blog Settings
   - Click **Publish** to save your changes

3. **Set Up Menus**
   - Navigate to **Appearance > Menus**
   - Create a primary menu and assign it to the "Primary Menu" location
   - Optionally create additional menus for:
     - Footer Menu
     - Mobile Menu
     - Secondary Menu

4. **Configure Widgets**
   - Navigate to **Appearance > Widgets**
   - Add widgets to the available widget areas:
     - Sidebar
     - Footer Columns (1-4)
     - Shop Sidebar (if WooCommerce is active)

## Demo Content Import

To make your site look like the demo, you can import the demo content:

1. Ensure the **One-Click Demo Import** plugin is installed and activated
2. Navigate to **Appearance > Import Demo Data**
3. Click **Import Demo Data**
4. Choose which content to import:
   - Pages & Posts
   - Media
   - Widgets
   - Customizer Settings
   - WooCommerce Products (if applicable)
5. Click **Import** and wait for the process to complete

## WooCommerce Setup

If you're using WooCommerce with AquaLuxe, follow these additional steps:

1. **Complete WooCommerce Setup Wizard**
   - After activating WooCommerce, follow the setup wizard
   - Configure store details, payment methods, shipping, etc.

2. **Configure AquaLuxe WooCommerce Settings**
   - Navigate to **Appearance > Customize > WooCommerce**
   - Configure product layouts, shop page settings, etc.
   - Set up the cart and checkout page designs

3. **Set Up Product Categories**
   - Navigate to **Products > Categories**
   - Create your product categories and assign featured images

4. **Configure Shop Pages**
   - Ensure the following pages are created and assigned in WooCommerce settings:
     - Shop
     - Cart
     - Checkout
     - My Account

## Theme Customization

AquaLuxe offers extensive customization options:

1. **Using the Customizer**
   - Navigate to **Appearance > Customize**
   - All visual customizations can be made here without coding

2. **Using Custom CSS**
   - Navigate to **Appearance > Customize > Additional CSS**
   - Add your custom CSS here

3. **Child Theme (For Advanced Customization)**
   - For extensive customization, use a child theme
   - A child theme template is included in the `child-theme` folder

## Development Environment

For developers who want to modify the theme:

1. **Set Up Development Environment**
   - Ensure Node.js (≥ 18.0.0) and npm (≥ 8.0.0) are installed
   - Clone the theme repository or extract the theme files
   - Navigate to the theme directory in your terminal

2. **Install Dependencies**
   ```bash
   npm install
   ```

3. **Available Build Commands**
   - Development build: `npm run dev`
   - Production build: `npm run build`
   - Watch for changes: `npm run watch`
   - Clean assets: `npm run clean`

4. **File Structure**
   - `assets/src/` - Source files (SCSS, JS, images)
   - `assets/` - Compiled assets
   - `template-parts/` - Template partials
   - `inc/` - PHP includes and functions
   - `woocommerce/` - WooCommerce template overrides

For more detailed information about the development environment and build process, refer to the [BUILD-PROCESS.md](BUILD-PROCESS.md) document.

## Support and Documentation

- Theme documentation: [DOCUMENTATION.md](DOCUMENTATION.md)
- Troubleshooting: [TROUBLESHOOTING.md](TROUBLESHOOTING.md)
- Support: support@example.com