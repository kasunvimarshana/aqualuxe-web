# AquaLuxe WordPress Theme - Installation Guide

## Table of Contents
1. [Requirements](#requirements)
2. [Installation](#installation)
3. [Plugin Installation](#plugin-installation)
4. [Demo Content Import](#demo-content-import)
5. [Manual Setup](#manual-setup)
6. [WooCommerce Setup](#woocommerce-setup)
7. [Theme Options](#theme-options)
8. [Troubleshooting](#troubleshooting)

## Requirements <a name="requirements"></a>

Before installing the AquaLuxe theme, ensure your hosting environment meets the following requirements:

### Server Requirements
- PHP 7.4 or higher
- MySQL 5.6 or higher
- WordPress 5.8 or higher
- PHP memory limit of at least 128MB
- max_execution_time of at least 120 seconds

### Recommended PHP Extensions
- cURL
- GD or ImageMagick
- mod_rewrite
- mbstring
- zip/unzip

### Browser Support
AquaLuxe is optimized for the following browsers:
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Opera (latest)

## Installation <a name="installation"></a>

### Standard Installation

1. **Download the theme**
   - Log in to your account at [aqualuxetheme.com](https://aqualuxetheme.com)
   - Go to "Downloads" section
   - Download the latest version of AquaLuxe theme (aqualuxe.zip)

2. **Install via WordPress Admin**
   - Log in to your WordPress admin panel
   - Navigate to Appearance > Themes
   - Click "Add New"
   - Click "Upload Theme"
   - Choose the aqualuxe.zip file you downloaded
   - Click "Install Now"
   - After installation completes, click "Activate"

### FTP Installation

If you encounter issues with the standard installation method, you can use FTP:

1. **Extract the theme**
   - Extract the aqualuxe.zip file on your computer
   - This will create an "aqualuxe-theme" folder

2. **Upload via FTP**
   - Connect to your server using an FTP client
   - Navigate to wp-content/themes/ directory
   - Upload the entire "aqualuxe-theme" folder
   - Log in to your WordPress admin panel
   - Go to Appearance > Themes
   - Find AquaLuxe and click "Activate"

## Plugin Installation <a name="plugin-installation"></a>

AquaLuxe works best with certain plugins. After activating the theme, you'll see a notification to install recommended plugins.

### Required Plugins
These plugins are necessary for full theme functionality:
- **WooCommerce** - For e-commerce functionality
- **Elementor** - For page building
- **Contact Form 7** - For contact forms
- **AquaLuxe Core** - Theme companion plugin (included with theme)

### Recommended Plugins
These plugins enhance the theme but are optional:
- **WP Rocket** - For performance optimization
- **Yoast SEO** - For SEO features
- **WPML** - For multilingual functionality
- **WooCommerce Currency Switcher** - For multi-currency support

### Installing Plugins

1. **Via Theme Notification**
   - After activating the theme, you'll see a notification at the top of the admin panel
   - Click "Begin installing plugins"
   - Select the plugins you want to install
   - Click "Install"
   - After installation, activate the plugins

2. **Manual Installation**
   - Go to Plugins > Add New
   - Search for each plugin by name
   - Click "Install Now" and then "Activate"
   - For AquaLuxe Core, use the plugin zip file included with the theme

## Demo Content Import <a name="demo-content-import"></a>

AquaLuxe includes demo content to help you get started quickly.

### Automatic Import

1. **Access the Import Tool**
   - Go to Appearance > AquaLuxe Setup
   - Click on the "Demo Import" tab
   - You'll see available demo options

2. **Choose a Demo**
   - AquaLuxe Main Demo - Complete shop setup
   - AquaLuxe Minimal - Simplified version
   - AquaLuxe Blog - Focus on blog content

3. **Import Options**
   - Select what you want to import:
     - Content (pages, posts, products)
     - Media (images, videos)
     - Widgets
     - Theme options
     - Sliders
     - Customizer settings

4. **Start Import**
   - Click "Import Demo"
   - Wait for the import process to complete
   - This may take several minutes depending on your server

### Manual Import

If the automatic import fails, you can import manually:

1. **Download Demo Files**
   - Go to [aqualuxetheme.com/demos](https://aqualuxetheme.com/demos)
   - Download the demo XML file

2. **Import Content**
   - Go to Tools > Import
   - Click "WordPress" (install the importer if prompted)
   - Choose the XML file and click "Upload file and import"
   - Follow the prompts to complete the import

3. **Import Customizer Settings**
   - Go to Appearance > Customize
   - Click the gear icon at the bottom
   - Select "Import/Export"
   - Upload the customizer.dat file from the demo files

## Manual Setup <a name="manual-setup"></a>

If you prefer to set up your site manually instead of importing demo content:

### Homepage Setup

1. **Create Homepage**
   - Go to Pages > Add New
   - Name it "Home" or "Homepage"
   - Use Elementor to design the page or use the "Homepage" template
   - Publish the page

2. **Set as Front Page**
   - Go to Settings > Reading
   - Set "Your homepage displays" to "A static page"
   - Select your new homepage from the dropdown
   - Click "Save Changes"

### Menu Setup

1. **Create Menus**
   - Go to Appearance > Menus
   - Click "create a new menu"
   - Name it (e.g., "Main Menu")
   - Add pages, categories, custom links, etc.
   - Check "Primary Menu" under "Display location"
   - Click "Save Menu"

2. **Additional Menus**
   - Repeat the process for other menu locations:
     - Secondary Menu
     - Footer Menu
     - Mobile Menu

### Widget Setup

1. **Configure Widgets**
   - Go to Appearance > Widgets
   - Add widgets to the available widget areas:
     - Sidebar
     - Footer 1-4
     - Shop Sidebar (if WooCommerce is active)

## WooCommerce Setup <a name="woocommerce-setup"></a>

If you're using WooCommerce with AquaLuxe:

### Basic Setup

1. **Run WooCommerce Setup Wizard**
   - After activating WooCommerce, the setup wizard will launch
   - Follow the steps to configure:
     - Store location
     - Currency
     - Shipping
     - Payment methods
     - Optional extensions

2. **Configure WooCommerce Settings**
   - Go to WooCommerce > Settings
   - Review and adjust settings in each tab:
     - General
     - Products
     - Shipping
     - Payments
     - Accounts
     - Emails

### AquaLuxe WooCommerce Features

1. **Advanced Product Filtering**
   - Go to Appearance > Customize > WooCommerce > Product Filters
   - Enable and configure filtering options

2. **Custom Checkout Fields**
   - Go to WooCommerce > Settings > AquaLuxe
   - Enable and configure custom checkout fields

3. **Stock Notification System**
   - Go to WooCommerce > Settings > AquaLuxe > Stock Notifications
   - Enable and configure notification settings

4. **Review System with Photos**
   - Go to WooCommerce > Settings > AquaLuxe > Reviews
   - Enable and configure photo review settings

## Theme Options <a name="theme-options"></a>

AquaLuxe offers extensive customization options:

### Customizer

1. **Access Customizer**
   - Go to Appearance > Customize
   - Browse through the available sections

2. **Key Customization Areas**
   - Site Identity - Logo, site title, favicon
   - Colors - Theme color scheme
   - Typography - Fonts and text settings
   - Header Options - Header layout and elements
   - Footer Options - Footer layout and content
   - WooCommerce Options - Shop and product settings
   - Performance - Optimization settings
   - SEO - Search engine optimization settings

### AquaLuxe Settings Panel

1. **Access Settings**
   - Go to Appearance > AquaLuxe Options
   - Explore the available tabs

2. **Key Setting Areas**
   - General Settings - Basic theme configuration
   - Header Settings - Advanced header options
   - Footer Settings - Advanced footer options
   - Blog Settings - Post display options
   - Shop Settings - Advanced WooCommerce options
   - Social Media - Social network links
   - Performance - Advanced optimization options
   - Import/Export - Backup and restore settings

## Troubleshooting <a name="troubleshooting"></a>

### Common Installation Issues

1. **Theme Installation Fails**
   - Check PHP memory limit (increase to at least 128MB)
   - Try FTP installation method
   - Ensure server meets requirements

2. **Demo Import Fails**
   - Check PHP max_execution_time (increase to at least 300)
   - Try importing in parts (content first, then settings)
   - Try manual import method

3. **Plugin Conflicts**
   - Temporarily deactivate all plugins
   - Activate AquaLuxe required plugins one by one
   - Check for conflicts with existing plugins

4. **WooCommerce Integration Issues**
   - Ensure WooCommerce is updated to latest version
   - Reset WooCommerce permalinks (Settings > Permalinks > Save)
   - Check for conflicts with other WooCommerce extensions

### Getting Support

If you encounter issues during installation:

1. **Check Documentation**
   - Review this installation guide
   - Check the [knowledge base](https://aqualuxetheme.com/kb)

2. **Support Channels**
   - Submit a ticket at [support.aqualuxetheme.com](https://support.aqualuxetheme.com)
   - Email support@aqualuxetheme.com
   - Check the [support forum](https://aqualuxetheme.com/forum)

3. **Provide Information**
   When seeking support, please provide:
   - WordPress version
   - PHP version
   - Active plugins list
   - Description of the issue
   - Screenshots if applicable
   - Error messages if any